<?php
declare(strict_types=1);
// Isolated test DB only; never use production configuration or student records.
$testDirectory = sys_get_temp_dir() . '/miura-learning-tests-' . bin2hex(random_bytes(6));
mkdir($testDirectory, 0700);
$testDatabase = $testDirectory . '/learning.sqlite';
putenv('AOW_DB_PATH=' . $testDatabase);
register_shutdown_function(function () use ($testDirectory, $testDatabase): void {
    foreach ([$testDatabase, $testDatabase . '-wal', $testDatabase . '-shm'] as $file) if (is_file($file)) unlink($file);
    if (is_dir($testDirectory)) rmdir($testDirectory);
});
putenv('AOW_APP_KEY=' . str_repeat('test-key-', 8));
putenv('AOW_ADMIN_HASH=' . password_hash('local-test-admin-password', PASSWORD_BCRYPT, ['cost' => 4]));
require dirname(__DIR__, 2) . '/aow-learning/lib.php';

$checks = [];
function check(bool $passed, string $label): void {
    global $checks;
    if (!$passed) throw new RuntimeException('FAILED: ' . $label);
    $checks[] = $label;
}
function complete_input(string $slug): array {
    $state = ['modules' => [], 'ready' => ['gear' => true, 'condition' => true, 'question' => true]];
    foreach (course_answer_key($slug) as $module => $answers) $state['modules'][$module] = ['answers' => $answers, 'complete' => true];
    return $state;
}
function add_user(PDO $pdo, string $id): int {
    $pdo->prepare('INSERT INTO users (learner_id, password_hash, recovery_hash, created_at) VALUES (?, ?, ?, ?)')
        ->execute([$id, password_hash('local-test-password', PASSWORD_BCRYPT, ['cost' => 4]), token_hash('LOCAL-RECOVERY'), now_iso()]);
    return (int)$pdo->lastInsertId();
}

$pdo = db();
check((int)$pdo->query('PRAGMA user_version')->fetchColumn() === 7, 'fresh schema version 7');
check(course_available('night') && course_available('aow'), 'night and aow active');
check(!course_available('deep') && !course_available('../access-config.php'), 'unsupported and inactive courses rejected');
check(count(course_answer_key('night')) === 6 && array_sum(array_map('count', course_answer_key('night'))) === 30, 'night has six modules and thirty questions');
check(array_sum(array_map('count', course_answer_key())) === 41, 'aow answer key remains 41 questions');

$legacyId = add_user($pdo, 'LOCAL-LEGACY');
$dualId = add_user($pdo, 'LOCAL-DUAL');
$legacy = complete_input('aow');
unset($legacy['modules']['deep'], $legacy['modules']['boat']);
$legacyJson = json_encode($legacy);
$legacyCode = 'AOW-20260901-LEGACY01';
$legacyDate = '2026-09-01T12:00:00+09:00';
$pdo->prepare('INSERT INTO course_progress (user_id, course_slug, state_json, completion_code, completed_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)')
    ->execute([$legacyId, 'aow', $legacyJson, $legacyCode, $legacyDate, $legacyDate]);
$pdo->exec('DELETE FROM courses WHERE slug = "night"');
$pdo->exec('PRAGMA user_version = 3');
migrate($pdo);
check(course_available('night') && (int)$pdo->query('PRAGMA user_version')->fetchColumn() === 7, 'schema 3 upgrades and adds night with current schema');
$preserved = $pdo->query('SELECT state_json FROM course_progress WHERE user_id = ' . $legacyId)->fetchColumn();
check($preserved === $legacyJson, 'migration leaves legacy aow progress byte-for-byte');
$loadedLegacy = load_progress($legacyId);
check($loadedLegacy['completion']['curriculumVersion'] === 1 && $loadedLegacy['completion']['code'] === $legacyCode, 'legacy implicit curriculum version one preserved');

$inviteCode = 'ENR-LOCAL-NIGHT';
$pdo->prepare('INSERT INTO invite_codes (code_hash, code_hint, course_slugs, created_at) VALUES (?, ?, ?, ?)')->execute([token_hash($inviteCode), 'LOCAL', '["aow","night"]', now_iso()]);
$pdo->beginTransaction();
redeem_invite($pdo, invite_record($inviteCode), $dualId);
$pdo->commit();
check(has_course($dualId, 'night') && has_course($dualId, 'aow') && invite_record($inviteCode) === null, 'one-time invite enrolls both courses');

$aow = complete_input('aow');
$aow['modules']['boat']['complete'] = false;
$beforeAow = save_progress($dualId, $aow);
check($beforeAow['curriculumVersion'] === 2 && !$beforeAow['completion'], 'unfinished aow remains curriculum two');
$aowJson = $pdo->query('SELECT state_json FROM course_progress WHERE user_id = ' . $dualId . ' AND course_slug = "aow"')->fetchColumn();
$empty = load_progress($dualId, 'night');
check($empty['curriculumVersion'] === 1 && json_encode($empty['modules']['plan']['answers']) === '{}', 'night empty answers remain JSON objects');

$spoofed = ['modules' => [], 'ready' => ['gear' => true, 'condition' => true, 'question' => true], 'completion' => ['code' => 'SPOOFED'], 'curriculumVersion' => 999];
foreach (array_keys(course_answer_key('night')) as $module) $spoofed['modules'][$module] = ['complete' => true];
$saved = save_progress($dualId, $spoofed, true, 'night');
check($saved['completion'] === null && $saved['curriculumVersion'] === 1, 'spoofed completion and empty answers rejected');

$night = complete_input('night');
$night['modules']['plan']['answers']['plan1'] = 'a';
$saved = save_progress($dualId, $night, true, 'night');
check(!$saved['modules']['plan']['complete'] && $saved['completion'] === null, 'wrong answer prevents module and overall completion');
$night = complete_input('night');
$night['ready']['condition'] = false;
$saved = save_progress($dualId, $night, true, 'night');
check($saved['completion'] === null, 'missing final readiness check prevents completion');
$night = complete_input('night');
$night['modules']['plan']['answers']['extra'] = 'b';
$night['modules']['boat'] = ['answers' => ['boat1' => 'b'], 'complete' => true];
$saved = save_progress($dualId, $night, true, 'night');
check(str_starts_with($saved['completion']['code'], 'NIGHT-') && $saved['completion']['curriculumVersion'] === 1, 'full night answers issue independent NIGHT record');
check(!isset($saved['modules']['boat']) && !isset($saved['modules']['plan']['answers']->extra), 'foreign modules and unknown answer ids stripped');
$nightCode = $saved['completion']['code'];
$nightDate = $saved['completion']['issuedAt'];
$saved = save_progress($dualId, [], false, 'night');
check($saved['completion']['code'] === $nightCode && $saved['completion']['issuedAt'] === $nightDate, 'issued night record survives later save');
$afterAowJson = $pdo->query('SELECT state_json FROM course_progress WHERE user_id = ' . $dualId . ' AND course_slug = "aow"')->fetchColumn();
check($afterAowJson === $aowJson, 'night saves never touch aow progress');
$savedLegacy = save_progress($legacyId, $legacy);
check($savedLegacy['completion']['code'] === $legacyCode && $savedLegacy['completion']['issuedAt'] === $legacyDate && $savedLegacy['curriculumVersion'] === 1, 'legacy aow record survives new backend save');
$doneAow = save_progress($dualId, complete_input('aow'), true);
check(str_starts_with($doneAow['completion']['code'], 'AOW-') && $doneAow['completion']['curriculumVersion'] === 2, 'current aow completes with unchanged prefix and version');
check(course_definition('night')['completion_label'] === '事前学習完了（インストラクター確認待ち）', 'night completion wording requires instructor confirmation');

// Dry SP is independent of both existing courses, including on a v4 database.
check(course_available('dry') && (int)$pdo->query('SELECT COUNT(*) FROM courses WHERE active = 1')->fetchColumn() === 5, 'five supported courses are active (aow, night, dry, dpv, naturalist)');
$dryKey = course_answer_key('dry');
check(array_keys($dryKey) === ['suit', 'prepare', 'buoyancy', 'skills', 'problems', 'care'] && array_sum(array_map('count', $dryKey)) === 30, 'dry has six named modules and thirty questions');
$expectedDryAnswers = [
    'suit' => ['b','a','c','b','a'], 'prepare' => ['c','b','a','c','b'],
    'buoyancy' => ['a','c','b','a','c'], 'skills' => ['b','c','a','b','c'],
    'problems' => ['c','a','b','c','a'], 'care' => ['a','b','c','a','b'],
];
foreach ($dryKey as $module => $answers) {
    $expected = [];
    foreach ($expectedDryAnswers[$module] as $index => $answer) $expected[$module . ($index + 1)] = $answer;
    check($answers === $expected, 'dry answer key and question ids: ' . $module);
}
$dryEmpty = load_progress($dualId, 'dry');
check($dryEmpty['curriculumVersion'] === 1 && json_encode($dryEmpty['modules']['suit']['answers']) === '{}', 'dry empty answers serialize as objects at curriculum one');
$existingRows = $pdo->query('SELECT * FROM course_progress ORDER BY user_id, course_slug')->fetchAll();
$existingEnrollments = $pdo->query('SELECT * FROM enrollments ORDER BY user_id, course_slug')->fetchAll();
$dryVersionFourJson = '{"curriculumVersion":1,"modules":{"suit":{"answers":{"suit1":"b"},"complete":false}},"ready":{}}';
$pdo->prepare('INSERT INTO course_progress (user_id, course_slug, state_json, updated_at) VALUES (?, ?, ?, ?)')->execute([$dualId, 'dry', $dryVersionFourJson, $legacyDate]);
$pdo->exec('UPDATE courses SET active = 0, title = "Dry SP（準備中）" WHERE slug = "dry"');
$pdo->exec('PRAGMA user_version = 4');
migrate($pdo);
check((int)$pdo->query('PRAGMA user_version')->fetchColumn() === 7 && course_available('dry') && (int)$pdo->query('SELECT COUNT(*) FROM courses WHERE slug = "dry"')->fetchColumn() === 1, 'schema four activates existing dry catalog row without duplicating it');
check($pdo->query('SELECT * FROM course_progress WHERE course_slug != "dry" ORDER BY user_id, course_slug')->fetchAll() === $existingRows, 'v4 migration preserves all aow and night records byte-for-byte');
check($pdo->query('SELECT * FROM enrollments ORDER BY user_id, course_slug')->fetchAll() === $existingEnrollments, 'v4 migration preserves all existing enrollments');
check($pdo->query('SELECT state_json FROM course_progress WHERE course_slug = "dry"')->fetchColumn() === $dryVersionFourJson, 'activating a prepared dry course preserves its stored answers');
$dryInviteCode = 'ENR-LOCAL-DRY';
$pdo->prepare('INSERT INTO invite_codes (code_hash, code_hint, course_slugs, created_at) VALUES (?, ?, ?, ?)')->execute([token_hash($dryInviteCode), 'LOCAL', '["dry"]', now_iso()]);
$pdo->beginTransaction();
redeem_invite($pdo, invite_record($dryInviteCode), $dualId);
$pdo->commit();
check(has_course($dualId, 'aow') && has_course($dualId, 'night') && has_course($dualId, 'dry') && invite_record($dryInviteCode) === null, 'existing account redeems one-time dry code and keeps three course permissions');
$beforeDryRows = $pdo->query('SELECT * FROM course_progress WHERE course_slug != "dry" ORDER BY user_id, course_slug')->fetchAll();
$drySpoofed = ['curriculumVersion' => 999, 'completion' => ['code' => 'DRY-SPOOFED'], 'modules' => [], 'ready' => ['gear' => true, 'condition' => true, 'question' => true]];
foreach (array_keys($dryKey) as $module) $drySpoofed['modules'][$module] = ['complete' => true];
$drySaved = save_progress($dualId, $drySpoofed, true, 'dry');
check($drySaved['completion'] === null && $drySaved['curriculumVersion'] === 1, 'dry rejects forged completion and unanswered complete flags');
$dryInput = complete_input('dry');
$dryInput['modules']['suit']['answers']['suit1'] = 'c';
$drySaved = save_progress($dualId, $dryInput, true, 'dry');
check(!$drySaved['modules']['suit']['complete'] && $drySaved['completion'] === null, 'dry wrong answer prevents module and completion record');
$dryInput = complete_input('dry');
$dryInput['modules']['care']['complete'] = false;
$drySaved = save_progress($dualId, $dryInput, true, 'dry');
check($drySaved['completion'] === null, 'dry requires learner to complete all six modules');
$dryInput = complete_input('dry');
$dryInput['ready']['gear'] = false;
$drySaved = save_progress($dualId, $dryInput, true, 'dry');
check($drySaved['completion'] === null, 'dry requires all final readiness checks');
$drySaved = save_progress($dualId, complete_input('night'), true, 'dry');
check($drySaved['completion'] === null && !isset($drySaved['modules']['plan']), 'night answers cannot complete the dry course');
$dryInput = complete_input('dry');
$dryInput['modules']['plan'] = ['answers' => ['plan1' => 'b'], 'complete' => true];
$dryInput['modules']['suit']['answers']['unknown'] = 'b';
$drySaved = save_progress($dualId, $dryInput, true, 'dry');
check(str_starts_with($drySaved['completion']['code'], 'DRY-') && $drySaved['completion']['curriculumVersion'] === 1 && !isset($drySaved['modules']['plan']) && !isset($drySaved['modules']['suit']['answers']->unknown), 'dry issues DRY version one record and strips other course modules');
check($pdo->query('SELECT * FROM course_progress WHERE course_slug != "dry" ORDER BY user_id, course_slug')->fetchAll() === $beforeDryRows, 'all dry saves leave both aow and night rows byte-for-byte unchanged');
$dryCode = $drySaved['completion']['code'];
$dryDate = $drySaved['completion']['issuedAt'];
$drySaved = save_progress($dualId, [], false, 'dry');
check($drySaved['completion']['code'] === $dryCode && $drySaved['completion']['issuedAt'] === $dryDate, 'dry completion number and date survive a later incomplete save');
$afterDryRow = $pdo->query('SELECT * FROM course_progress WHERE course_slug = "dry"')->fetchAll();
$beforeNightRow = $pdo->query('SELECT * FROM course_progress WHERE course_slug = "night"')->fetchAll();
save_progress($dualId, complete_input('aow'), true, 'aow');
check($pdo->query('SELECT * FROM course_progress WHERE course_slug = "dry"')->fetchAll() === $afterDryRow && $pdo->query('SELECT * FROM course_progress WHERE course_slug = "night"')->fetchAll() === $beforeNightRow, 'aow save leaves dry and night rows unchanged');
$beforeAowRows = $pdo->query('SELECT * FROM course_progress WHERE course_slug = "aow" ORDER BY user_id')->fetchAll();
save_progress($dualId, complete_input('night'), true, 'night');
check($pdo->query('SELECT * FROM course_progress WHERE course_slug = "dry"')->fetchAll() === $afterDryRow && $pdo->query('SELECT * FROM course_progress WHERE course_slug = "aow" ORDER BY user_id')->fetchAll() === $beforeAowRows, 'night save leaves dry and aow rows unchanged');
check(load_progress($dualId, 'aow')['completion']['code'] === $doneAow['completion']['code'] && load_progress($dualId, 'night')['completion']['code'] === $nightCode && load_progress($dualId, 'dry')['completion']['code'] === $dryCode, 'three courses reload their independent record numbers');
$dryConfig = course_client_config('dry');
$nightConfig = course_client_config('night');
$aowConfig = course_client_config('aow');
check($dryConfig['modules'] === array_keys($dryKey) && $dryConfig['instructorReview'] && $dryConfig['legacyModules'] === [] && $dryConfig['completionLabel'] === '事前学習完了（インストラクター確認待ち）', 'dry shared UI configuration requires instructor confirmation and has no aow legacy modules');
check($nightConfig['instructorReview'] && $nightConfig['legacyModules'] === [] && !$aowConfig['instructorReview'] && $aowConfig['legacyModules'] === ['ppb', 'navigation', 'naturalist'], 'shared UI keeps night independent and retains aow legacy compatibility');
check(course_definition('dry')['styles'] === ['styles.css', 'dry.css'] && course_definition('night')['styles'] === ['styles.css', 'night.css'], 'course-specific styles remain independently selected');


// DPV SP must be added to a v5 database without changing existing records or access.
$dpvKey = course_answer_key('dpv');
check(course_available('dpv') && array_keys($dpvKey) === ['basics', 'subnado', 'prepare', 'plan', 'ride', 'trouble', 'care'] && array_sum(array_map('count', $dpvKey)) === 35, 'dpv has seven modules and thirty-five questions');
foreach ($dpvKey as $module => $answers) {
    $expected = [];
    foreach (['b', 'a', 'c', 'b', 'a'] as $index => $answer) $expected[$module . ($index + 1)] = $answer;
    check($answers === $expected, 'dpv answer key and question ids: ' . $module);
}
$beforeDpvMigrationRows = $pdo->query('SELECT * FROM course_progress ORDER BY user_id, course_slug')->fetchAll();
$beforeDpvMigrationEnrollments = $pdo->query('SELECT * FROM enrollments ORDER BY user_id, course_slug')->fetchAll();
$beforeDpvMigrationUsers = $pdo->query('SELECT * FROM users ORDER BY id')->fetchAll();
$beforeDpvMigrationInvites = $pdo->query('SELECT * FROM invite_codes ORDER BY id')->fetchAll();
$pdo->exec('DELETE FROM courses WHERE slug = "dpv"');
$pdo->exec('PRAGMA user_version = 5');
migrate($pdo);
check((int)$pdo->query('PRAGMA user_version')->fetchColumn() === 7 && course_available('dpv') && (int)$pdo->query('SELECT COUNT(*) FROM courses WHERE slug = "dpv"')->fetchColumn() === 1, 'schema five adds one active dpv course');
check($pdo->query('SELECT * FROM course_progress ORDER BY user_id, course_slug')->fetchAll() === $beforeDpvMigrationRows, 'v5 migration preserves all existing learning records byte-for-byte');
check($pdo->query('SELECT * FROM enrollments ORDER BY user_id, course_slug')->fetchAll() === $beforeDpvMigrationEnrollments && $pdo->query('SELECT * FROM users ORDER BY id')->fetchAll() === $beforeDpvMigrationUsers && $pdo->query('SELECT * FROM invite_codes ORDER BY id')->fetchAll() === $beforeDpvMigrationInvites, 'v5 migration preserves all existing accounts, course permissions, and invite codes');
check(!has_course($dualId, 'dpv') && !has_course($legacyId, 'dpv'), 'adding dpv to the catalog does not grant access to existing learners');
$dpvEmpty = load_progress($dualId, 'dpv');
check($dpvEmpty['curriculumVersion'] === 1 && json_encode($dpvEmpty['modules']['basics']['answers']) === '{}' && $dpvEmpty['completion'] === null, 'dpv starts with empty answer objects and no completion');
$dpvInviteCode = 'ENR-LOCAL-DPV';
$pdo->prepare('INSERT INTO invite_codes (code_hash, code_hint, course_slugs, created_at) VALUES (?, ?, ?, ?)')->execute([token_hash($dpvInviteCode), 'LOCAL', '["dpv"]', now_iso()]);
$dpvInvite = invite_record($dpvInviteCode);
$pdo->beginTransaction();
redeem_invite($pdo, $dpvInvite, $dualId);
$pdo->commit();
check(has_course($dualId, 'dpv') && has_course($dualId, 'aow') && has_course($dualId, 'night') && has_course($dualId, 'dry') && invite_record($dpvInviteCode) === null && !has_course($legacyId, 'dpv'), 'one-time dpv code grants only the selected learner and keeps other course permissions');
$reuseRejected = false;
$pdo->beginTransaction();
try {
    redeem_invite($pdo, $dpvInvite, $legacyId);
    $pdo->commit();
} catch (RuntimeException $e) {
    $pdo->rollBack();
    $reuseRejected = true;
}
check($reuseRejected && !has_course($legacyId, 'dpv'), 'reusing a consumed dpv code rolls back the unauthorized enrollment');
$dpvOnlyId = add_user($pdo, 'LOCAL-DPV-ONLY');
$dpvOnlyInviteCode = 'ENR-LOCAL-DPV-ONLY';
$pdo->prepare('INSERT INTO invite_codes (code_hash, code_hint, course_slugs, created_at) VALUES (?, ?, ?, ?)')->execute([token_hash($dpvOnlyInviteCode), 'LOCAL', '["dpv"]', now_iso()]);
$pdo->beginTransaction();
redeem_invite($pdo, invite_record($dpvOnlyInviteCode), $dpvOnlyId);
$pdo->commit();
check(has_course($dpvOnlyId, 'dpv') && !has_course($dpvOnlyId, 'aow') && !has_course($dpvOnlyId, 'night') && !has_course($dpvOnlyId, 'dry') && array_column(enrolled_courses($dpvOnlyId), 'slug') === ['dpv'], 'new dpv-only account receives no other courses');
$beforeDpvSaves = $pdo->query('SELECT * FROM course_progress WHERE course_slug != "dpv" ORDER BY user_id, course_slug')->fetchAll();
$dpvSpoofed = ['curriculumVersion' => 999, 'completion' => ['code' => 'DPV-SPOOFED'], 'modules' => [], 'ready' => ['gear' => true, 'condition' => true, 'question' => true]];
foreach (array_keys($dpvKey) as $module) $dpvSpoofed['modules'][$module] = ['complete' => true];
$dpvSaved = save_progress($dualId, $dpvSpoofed, true, 'dpv');
check($dpvSaved['completion'] === null && $dpvSaved['curriculumVersion'] === 1, 'dpv rejects forged completion, curriculum, and empty complete flags');
$dpvInput = complete_input('dpv');
$dpvInput['modules']['subnado']['answers']['subnado1'] = 'a';
$dpvSaved = save_progress($dualId, $dpvInput, true, 'dpv');
check(!$dpvSaved['modules']['subnado']['complete'] && $dpvSaved['completion'] === null, 'one incorrect dpv answer prevents module and overall completion');
$dpvInput = complete_input('dpv');
$dpvInput['modules']['care']['complete'] = false;
check(save_progress($dualId, $dpvInput, true, 'dpv')['completion'] === null, 'dpv requires the learner to mark all seven modules complete');
$dpvInput = complete_input('dpv');
$dpvInput['ready']['question'] = false;
check(save_progress($dualId, $dpvInput, true, 'dpv')['completion'] === null, 'dpv requires all final readiness checks');
$dpvSaved = save_progress($dualId, complete_input('dry'), true, 'dpv');
check($dpvSaved['completion'] === null && !isset($dpvSaved['modules']['suit']), 'shared question names from dry cannot complete the dpv course');
$dpvInput = ['modules' => ['prepare' => ['answers' => ['prepare1' => 'b'], 'complete' => false]], 'ready' => []];
save_progress($dualId, $dpvInput, false, 'dpv');
$dpvResumed = load_progress($dualId, 'dpv');
check($dpvResumed['modules']['prepare']['answers']->prepare1 === 'b' && !$dpvResumed['modules']['prepare']['complete'] && $dpvResumed['completion'] === null, 'dpv partial answers survive save and reload without completion');
$dpvInput = complete_input('dpv');
$dpvInput['modules']['suit'] = ['answers' => ['suit1' => 'b'], 'complete' => true];
$dpvInput['modules']['basics']['answers']['unknown'] = 'b';
$dpvSaved = save_progress($dualId, $dpvInput, true, 'dpv');
check((bool)preg_match('/^DPV-\d{8}-[A-F0-9]{8}$/', $dpvSaved['completion']['code']) && $dpvSaved['completion']['curriculumVersion'] === 1 && !isset($dpvSaved['modules']['suit']) && !isset($dpvSaved['modules']['basics']['answers']->unknown), 'dpv issues its own version one record and strips foreign input');
check($pdo->query('SELECT * FROM course_progress WHERE course_slug != "dpv" ORDER BY user_id, course_slug')->fetchAll() === $beforeDpvSaves, 'all dpv saves preserve existing aow, night, and dry records byte-for-byte');
$dpvCode = $dpvSaved['completion']['code'];
$dpvDate = $dpvSaved['completion']['issuedAt'];
$dpvSaved = save_progress($dualId, [], false, 'dpv');
check($dpvSaved['completion']['code'] === $dpvCode && $dpvSaved['completion']['issuedAt'] === $dpvDate && $dpvSaved['completion']['curriculumVersion'] === 1, 'dpv completion number and date survive a later incomplete save');
$dpvRows = $pdo->query('SELECT * FROM course_progress WHERE course_slug = "dpv" ORDER BY user_id')->fetchAll();
foreach (['aow', 'night', 'dry'] as $otherCourse) save_progress($dualId, complete_input($otherCourse), true, $otherCourse);
check($pdo->query('SELECT * FROM course_progress WHERE course_slug = "dpv" ORDER BY user_id')->fetchAll() === $dpvRows, 'saving every earlier course leaves dpv progress unchanged');
check(load_progress($dualId, 'aow')['completion']['code'] === $doneAow['completion']['code'] && load_progress($dualId, 'night')['completion']['code'] === $nightCode && load_progress($dualId, 'dry')['completion']['code'] === $dryCode && load_progress($dualId, 'dpv')['completion']['code'] === $dpvCode, 'all four courses retain independent completion numbers');
$dpvConfig = course_client_config('dpv');
check($dpvConfig['modules'] === array_keys($dpvKey) && $dpvConfig['questionCount'] === 35 && $dpvConfig['instructorReview'] && $dpvConfig['legacyModules'] === [] && $dpvConfig['completionLabel'] === '事前学習完了（インストラクター確認待ち）', 'dpv shared UI configuration requires instructor review with thirty-five questions');
check(course_definition('dpv')['file'] === 'dpv.html' && course_definition('dpv')['styles'] === ['styles.css', 'dpv.css'], 'dpv selects its own protected content and stylesheet');
// Naturalist SP must be added to a v6 database without changing existing records or access.
$natKey = course_answer_key('naturalist');
check(course_available('naturalist') && array_keys($natKey) === ['ecosystem', 'relations', 'names', 'invertebrates', 'vertebrates', 'myths', 'interact', 'protect'] && array_sum(array_map('count', $natKey)) === 40, 'naturalist has eight modules and forty questions');
check($natKey === ['ecosystem' => ['ecosystem1' => 'a', 'ecosystem2' => 'b', 'ecosystem3' => 'c', 'ecosystem4' => 'a', 'ecosystem5' => 'b'], 'relations' => ['relations1' => 'b', 'relations2' => 'a', 'relations3' => 'c', 'relations4' => 'b', 'relations5' => 'a'], 'names' => ['names1' => 'c', 'names2' => 'b', 'names3' => 'a', 'names4' => 'c', 'names5' => 'c'], 'invertebrates' => ['invertebrates1' => 'b', 'invertebrates2' => 'a', 'invertebrates3' => 'b', 'invertebrates4' => 'c', 'invertebrates5' => 'a'], 'vertebrates' => ['vertebrates1' => 'a', 'vertebrates2' => 'b', 'vertebrates3' => 'c', 'vertebrates4' => 'a', 'vertebrates5' => 'b'], 'myths' => ['myths1' => 'a', 'myths2' => 'c', 'myths3' => 'b', 'myths4' => 'a', 'myths5' => 'c'], 'interact' => ['interact1' => 'b', 'interact2' => 'c', 'interact3' => 'a', 'interact4' => 'a', 'interact5' => 'c'], 'protect' => ['protect1' => 'c', 'protect2' => 'a', 'protect3' => 'b', 'protect4' => 'c', 'protect5' => 'b']], 'naturalist answer key matches the content source');
$beforeNatMigrationRows = $pdo->query('SELECT * FROM course_progress ORDER BY user_id, course_slug')->fetchAll();
$beforeNatMigrationEnrollments = $pdo->query('SELECT * FROM enrollments ORDER BY user_id, course_slug')->fetchAll();
$beforeNatMigrationUsers = $pdo->query('SELECT * FROM users ORDER BY id')->fetchAll();
$beforeNatMigrationInvites = $pdo->query('SELECT * FROM invite_codes ORDER BY id')->fetchAll();
$pdo->exec('DELETE FROM courses WHERE slug = "naturalist"');
$pdo->exec('PRAGMA user_version = 6');
migrate($pdo);
check((int)$pdo->query('PRAGMA user_version')->fetchColumn() === 7 && course_available('naturalist') && (int)$pdo->query('SELECT COUNT(*) FROM courses WHERE slug = "naturalist"')->fetchColumn() === 1, 'schema six adds one active naturalist course');
check($pdo->query('SELECT * FROM course_progress ORDER BY user_id, course_slug')->fetchAll() === $beforeNatMigrationRows, 'v6 migration preserves all existing learning records byte-for-byte');
check($pdo->query('SELECT * FROM enrollments ORDER BY user_id, course_slug')->fetchAll() === $beforeNatMigrationEnrollments && $pdo->query('SELECT * FROM users ORDER BY id')->fetchAll() === $beforeNatMigrationUsers && $pdo->query('SELECT * FROM invite_codes ORDER BY id')->fetchAll() === $beforeNatMigrationInvites, 'v6 migration preserves all existing accounts, course permissions, and invite codes');
check(!has_course($dualId, 'naturalist') && !has_course($legacyId, 'naturalist') && !has_course($dpvOnlyId, 'naturalist'), 'adding naturalist to the catalog does not grant access to existing learners');
$natEmpty = load_progress($dualId, 'naturalist');
check($natEmpty['curriculumVersion'] === 1 && json_encode($natEmpty['modules']['ecosystem']['answers']) === '{}' && $natEmpty['completion'] === null, 'naturalist starts with empty answer objects and no completion');
$natInviteCode = 'ENR-LOCAL-NAT';
$pdo->prepare('INSERT INTO invite_codes (code_hash, code_hint, course_slugs, created_at) VALUES (?, ?, ?, ?)')->execute([token_hash($natInviteCode), 'LOCAL', '["naturalist"]', now_iso()]);
$natInvite = invite_record($natInviteCode);
$pdo->beginTransaction();
redeem_invite($pdo, $natInvite, $dualId);
$pdo->commit();
check(has_course($dualId, 'naturalist') && has_course($dualId, 'aow') && has_course($dualId, 'night') && has_course($dualId, 'dry') && has_course($dualId, 'dpv') && invite_record($natInviteCode) === null && !has_course($legacyId, 'naturalist'), 'one-time naturalist code grants only the selected learner and keeps other course permissions');
$natOnlyId = add_user($pdo, 'LOCAL-NAT-ONLY');
$natOnlyInviteCode = 'ENR-LOCAL-NAT-ONLY';
$pdo->prepare('INSERT INTO invite_codes (code_hash, code_hint, course_slugs, created_at) VALUES (?, ?, ?, ?)')->execute([token_hash($natOnlyInviteCode), 'LOCAL', '["naturalist"]', now_iso()]);
$pdo->beginTransaction();
redeem_invite($pdo, invite_record($natOnlyInviteCode), $natOnlyId);
$pdo->commit();
check(array_column(enrolled_courses($natOnlyId), 'slug') === ['naturalist'], 'new naturalist-only account receives no other courses');
$beforeNatSaves = $pdo->query('SELECT * FROM course_progress WHERE course_slug != "naturalist" ORDER BY user_id, course_slug')->fetchAll();
$natSpoofed = ['curriculumVersion' => 999, 'completion' => ['code' => 'NAT-SPOOFED'], 'modules' => [], 'ready' => ['gear' => true, 'condition' => true, 'question' => true]];
foreach (array_keys($natKey) as $module) $natSpoofed['modules'][$module] = ['complete' => true];
$natSaved = save_progress($dualId, $natSpoofed, true, 'naturalist');
check($natSaved['completion'] === null && $natSaved['curriculumVersion'] === 1, 'naturalist rejects forged completion, curriculum, and empty complete flags');
$natInput = complete_input('naturalist');
$wrong = $natInput['modules']['names']['answers']['names1'] === 'a' ? 'b' : 'a';
$natInput['modules']['names']['answers']['names1'] = $wrong;
$natSaved = save_progress($dualId, $natInput, true, 'naturalist');
check(!$natSaved['modules']['names']['complete'] && $natSaved['completion'] === null, 'one incorrect naturalist answer prevents module and overall completion');
$natInput = complete_input('naturalist');
$natInput['modules']['protect']['complete'] = false;
check(save_progress($dualId, $natInput, true, 'naturalist')['completion'] === null, 'naturalist requires the learner to mark all eight modules complete');
$natInput = complete_input('naturalist');
$natInput['ready']['gear'] = false;
check(save_progress($dualId, $natInput, true, 'naturalist')['completion'] === null, 'naturalist requires all final readiness checks');
$natSaved = save_progress($dualId, complete_input('aow'), true, 'naturalist');
check($natSaved['completion'] === null && !isset($natSaved['modules']['naturalist']) && !isset($natSaved['modules']['ppb']), 'aow answers, including its naturalist module, cannot complete the naturalist course');
$natInput = ['modules' => ['myths' => ['answers' => ['myths2' => 'c'], 'complete' => false]], 'ready' => []];
save_progress($dualId, $natInput, false, 'naturalist');
$natResumed = load_progress($dualId, 'naturalist');
check($natResumed['modules']['myths']['answers']->myths2 === 'c' && !$natResumed['modules']['myths']['complete'] && $natResumed['completion'] === null, 'naturalist partial answers survive save and reload without completion');
$natInput = complete_input('naturalist');
$natInput['modules']['basics'] = ['answers' => ['basics1' => 'b'], 'complete' => true];
$natInput['modules']['ecosystem']['answers']['unknown'] = 'b';
$natSaved = save_progress($dualId, $natInput, true, 'naturalist');
check((bool)preg_match('/^NAT-\d{8}-[A-F0-9]{8}$/', $natSaved['completion']['code']) && $natSaved['completion']['curriculumVersion'] === 1 && !isset($natSaved['modules']['basics']) && !isset($natSaved['modules']['ecosystem']['answers']->unknown), 'naturalist issues its own version one record and strips foreign input');
check($pdo->query('SELECT * FROM course_progress WHERE course_slug != "naturalist" ORDER BY user_id, course_slug')->fetchAll() === $beforeNatSaves, 'all naturalist saves preserve existing aow, night, dry, and dpv records byte-for-byte');
$natCode = $natSaved['completion']['code'];
$natDate = $natSaved['completion']['issuedAt'];
$natSaved = save_progress($dualId, [], false, 'naturalist');
check($natSaved['completion']['code'] === $natCode && $natSaved['completion']['issuedAt'] === $natDate, 'naturalist completion number and date survive a later incomplete save');
$natRows = $pdo->query('SELECT * FROM course_progress WHERE course_slug = "naturalist" ORDER BY user_id')->fetchAll();
foreach (['aow', 'night', 'dry', 'dpv'] as $otherCourse) save_progress($dualId, complete_input($otherCourse), true, $otherCourse);
check($pdo->query('SELECT * FROM course_progress WHERE course_slug = "naturalist" ORDER BY user_id')->fetchAll() === $natRows, 'saving every earlier course leaves naturalist progress unchanged');
check(load_progress($dualId, 'dpv')['completion']['code'] === $dpvCode && load_progress($dualId, 'naturalist')['completion']['code'] === $natCode, 'dpv and naturalist keep independent completion numbers');
$natConfig = course_client_config('naturalist');
check($natConfig['modules'] === array_keys($natKey) && $natConfig['questionCount'] === 40 && $natConfig['instructorReview'] && $natConfig['legacyModules'] === [] && $natConfig['completionLabel'] === '事前学習完了（インストラクター確認待ち）', 'naturalist shared UI configuration requires instructor review with forty questions');
check(course_definition('naturalist')['file'] === 'naturalist.html' && course_definition('naturalist')['styles'] === ['styles.css', 'dpv.css', 'naturalist.css'], 'naturalist selects its own protected content and stylesheets');
$beforeRepeatMigration = $pdo->query('SELECT * FROM course_progress ORDER BY user_id, course_slug')->fetchAll();
migrate($pdo);
check($pdo->query('SELECT * FROM course_progress ORDER BY user_id, course_slug')->fetchAll() === $beforeRepeatMigration && (int)$pdo->query('SELECT COUNT(*) FROM courses WHERE slug = "dpv"')->fetchColumn() === 1 && (int)$pdo->query('SELECT COUNT(*) FROM courses WHERE slug = "naturalist"')->fetchColumn() === 1, 'current schema migration is idempotent and preserves all progress');

echo json_encode(['passed' => count($checks), 'checks' => $checks, 'database' => app_config()['database_path']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
