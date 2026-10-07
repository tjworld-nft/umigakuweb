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
check((int)$pdo->query('PRAGMA user_version')->fetchColumn() === 4, 'fresh schema version 4');
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
check(course_available('night') && (int)$pdo->query('PRAGMA user_version')->fetchColumn() === 4, 'schema 3 upgrades and adds night');
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

echo json_encode(['passed' => count($checks), 'checks' => $checks, 'database' => app_config()['database_path']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
