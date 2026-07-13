<?php
declare(strict_types=1);

const APP_COURSE = 'aow';

function app_config(): array
{
    static $config;
    if (is_array($config)) return $config;

    $path = __DIR__ . '/access-config.php';
    $fileConfig = is_file($path) ? require $path : [];
    if (!is_array($fileConfig)) $fileConfig = [];

    $config = [
        'database_path' => getenv('AOW_DB_PATH') ?: ($fileConfig['database_path'] ?? dirname(__DIR__, 2) . '/private-data/aow-learning.sqlite'),
        'app_key' => getenv('AOW_APP_KEY') ?: ($fileConfig['app_key'] ?? ''),
        'admin_password_hash' => getenv('AOW_ADMIN_HASH') ?: ($fileConfig['admin_password_hash'] ?? ''),
    ];
    return $config;
}

function app_ready(): bool
{
    $config = app_config();
    return strlen((string)$config['app_key']) >= 32 && str_starts_with((string)$config['admin_password_hash'], '$2');
}

function is_https(): bool
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') return true;
    return isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
}

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) return;
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_trans_sid', '0');
    session_name('miura_learning');
    session_set_cookie_params([
        'lifetime' => 60 * 60 * 24 * 30,
        'path' => '/aow-learning/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function security_headers(bool $html = true): void
{
    header('Cache-Control: private, no-store, max-age=0');
    header('Pragma: no-cache');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: same-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    if ($html) {
        header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self'; form-action 'self'; base-uri 'self'; frame-ancestors 'self'");
    }
}

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    if (!app_ready()) throw new RuntimeException('Application configuration is incomplete.');

    $path = (string)app_config()['database_path'];
    $directory = dirname($path);
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create the data directory.');
    }
    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');
    migrate($pdo);
    return $pdo;
}

function migrate(PDO $pdo): void
{
    $statements = [
        'CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            learner_id TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            recovery_hash TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT "active",
            created_at TEXT NOT NULL,
            last_login_at TEXT
        )',
        'CREATE TABLE IF NOT EXISTS courses (
            slug TEXT PRIMARY KEY,
            title TEXT NOT NULL,
            description TEXT NOT NULL,
            sort_order INTEGER NOT NULL DEFAULT 0,
            active INTEGER NOT NULL DEFAULT 0
        )',
        'CREATE TABLE IF NOT EXISTS enrollments (
            user_id INTEGER NOT NULL,
            course_slug TEXT NOT NULL,
            enrolled_at TEXT NOT NULL,
            PRIMARY KEY (user_id, course_slug),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_slug) REFERENCES courses(slug)
        )',
        'CREATE TABLE IF NOT EXISTS invite_codes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code_hash TEXT NOT NULL UNIQUE,
            code_hint TEXT NOT NULL,
            course_slugs TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT "active",
            expires_at TEXT,
            created_at TEXT NOT NULL,
            redeemed_by INTEGER,
            redeemed_at TEXT,
            FOREIGN KEY (redeemed_by) REFERENCES users(id)
        )',
        'CREATE TABLE IF NOT EXISTS course_progress (
            user_id INTEGER NOT NULL,
            course_slug TEXT NOT NULL,
            state_json TEXT NOT NULL DEFAULT "{}",
            completion_code TEXT,
            completed_at TEXT,
            updated_at TEXT NOT NULL,
            PRIMARY KEY (user_id, course_slug),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_slug) REFERENCES courses(slug)
        )',
        'CREATE TABLE IF NOT EXISTS login_attempts (
            attempt_key TEXT NOT NULL,
            attempted_at INTEGER NOT NULL
        )',
        'CREATE INDEX IF NOT EXISTS login_attempts_key_time ON login_attempts(attempt_key, attempted_at)',
    ];
    foreach ($statements as $sql) $pdo->exec($sql);

    $seed = $pdo->prepare('INSERT OR IGNORE INTO courses (slug, title, description, sort_order, active) VALUES (?, ?, ?, ?, ?)');
    $seed->execute(['aow', 'AOW 週末前の3レッスン', 'PPB・ナビゲーション・ナチュラリスト', 10, 1]);
    $seed->execute(['deep', 'ディープ・ダイビング', '深度計画・ガス管理・安全手順', 20, 0]);
    $seed->execute(['boat', 'ボート・ダイビング', '乗船準備・エントリー・ボート上の安全', 30, 0]);
}

function now_iso(): string
{
    return (new DateTimeImmutable('now', new DateTimeZone('Asia/Tokyo')))->format(DATE_ATOM);
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path, true, 303);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
    return (string)$_SESSION['csrf'];
}

function require_csrf(?string $token = null): void
{
    $provided = $token ?? (isset($_POST['csrf']) ? (string)$_POST['csrf'] : '');
    if (!hash_equals(csrf_token(), $provided)) {
        http_response_code(419);
        exit('セッションの有効期限が切れました。ページを再読み込みしてください。');
    }
}

function token_hash(string $token): string
{
    return hash_hmac('sha256', normalize_code($token), (string)app_config()['app_key']);
}

function normalize_code(string $value): string
{
    return strtoupper((string)preg_replace('/[^A-Za-z0-9]/', '', trim($value)));
}

function random_code(string $prefix, int $groups = 2): string
{
    $alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
    $parts = [];
    for ($group = 0; $group < $groups; $group++) {
        $part = '';
        for ($i = 0; $i < 4; $i++) $part .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        $parts[] = $part;
    }
    return $prefix . '-' . implode('-', $parts);
}

function unique_learner_id(PDO $pdo): string
{
    do {
        $id = random_code('UMI', 2);
        $stmt = $pdo->prepare('SELECT 1 FROM users WHERE learner_id = ?');
        $stmt->execute([$id]);
    } while ($stmt->fetchColumn());
    return $id;
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    $stmt = db()->prepare('SELECT id, learner_id, status, created_at, last_login_at FROM users WHERE id = ? AND status = "active"');
    $stmt->execute([(int)$_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function require_user(): array
{
    $user = current_user();
    if (!$user) redirect('login.php');
    return $user;
}

function has_course(int $userId, string $slug): bool
{
    $stmt = db()->prepare('SELECT 1 FROM enrollments WHERE user_id = ? AND course_slug = ?');
    $stmt->execute([$userId, $slug]);
    return (bool)$stmt->fetchColumn();
}

function enrolled_courses(int $userId): array
{
    $stmt = db()->prepare('SELECT c.*, p.completion_code, p.completed_at, p.state_json
        FROM enrollments e JOIN courses c ON c.slug = e.course_slug
        LEFT JOIN course_progress p ON p.user_id = e.user_id AND p.course_slug = e.course_slug
        WHERE e.user_id = ? ORDER BY c.sort_order');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function invite_record(string $code): ?array
{
    $stmt = db()->prepare('SELECT * FROM invite_codes WHERE code_hash = ? AND status = "active"');
    $stmt->execute([token_hash($code)]);
    $invite = $stmt->fetch();
    if (!$invite) return null;
    if ($invite['expires_at'] && strtotime((string)$invite['expires_at']) < time()) return null;
    return $invite;
}

function redeem_invite(PDO $pdo, array $invite, int $userId): void
{
    $slugs = json_decode((string)$invite['course_slugs'], true);
    if (!is_array($slugs) || !$slugs) throw new RuntimeException('登録コードに講座が設定されていません。');
    $insert = $pdo->prepare('INSERT OR IGNORE INTO enrollments (user_id, course_slug, enrolled_at) VALUES (?, ?, ?)');
    foreach ($slugs as $slug) $insert->execute([$userId, (string)$slug, now_iso()]);
    $used = $pdo->prepare('UPDATE invite_codes SET status = "used", redeemed_by = ?, redeemed_at = ? WHERE id = ? AND status = "active"');
    $used->execute([$userId, now_iso(), (int)$invite['id']]);
    if ($used->rowCount() !== 1) throw new RuntimeException('この登録コードはすでに使用されています。');
}

function attempt_key(string $learnerId): string
{
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    return token_hash('LOGIN:' . strtoupper(trim($learnerId)) . ':' . $ip);
}

function login_blocked(string $key): bool
{
    $cutoff = time() - 600;
    $pdo = db();
    $pdo->prepare('DELETE FROM login_attempts WHERE attempted_at < ?')->execute([$cutoff]);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE attempt_key = ? AND attempted_at >= ?');
    $stmt->execute([$key, $cutoff]);
    return (int)$stmt->fetchColumn() >= 8;
}

function record_login_failure(string $key): void
{
    db()->prepare('INSERT INTO login_attempts (attempt_key, attempted_at) VALUES (?, ?)')->execute([$key, time()]);
    usleep(500000);
}

function clear_login_failures(string $key): void
{
    db()->prepare('DELETE FROM login_attempts WHERE attempt_key = ?')->execute([$key]);
}

function course_answer_key(): array
{
    return [
        'ppb' => ['ppb1'=>'b','ppb2'=>'a','ppb3'=>'c','ppb4'=>'b','ppb5'=>'c','ppb6'=>'a','ppb7'=>'b'],
        'navigation' => ['nav1'=>'c','nav2'=>'b','nav3'=>'a','nav4'=>'b','nav5'=>'c','nav6'=>'a','nav7'=>'b'],
        'naturalist' => ['nat1'=>'b','nat2'=>'c','nat3'=>'a','nat4'=>'b','nat5'=>'c','nat6'=>'a','nat7'=>'c'],
    ];
}

function clean_course_state(array $input): array
{
    $keys = course_answer_key();
    $state = ['modules' => [], 'ready' => []];
    $inputModules = isset($input['modules']) && is_array($input['modules']) ? $input['modules'] : [];
    foreach ($keys as $module => $answers) {
        $submitted = isset($inputModules[$module]['answers']) && is_array($inputModules[$module]['answers']) ? $inputModules[$module]['answers'] : [];
        $cleanAnswers = [];
        foreach ($answers as $question => $correct) {
            $value = isset($submitted[$question]) ? (string)$submitted[$question] : '';
            if (in_array($value, ['a', 'b', 'c'], true)) $cleanAnswers[$question] = $value;
        }
        $allCorrect = count($cleanAnswers) === count($answers);
        foreach ($answers as $question => $correct) {
            if (($cleanAnswers[$question] ?? '') !== $correct) $allCorrect = false;
        }
        $requestedComplete = !empty($inputModules[$module]['complete']);
        $state['modules'][$module] = ['answers' => $cleanAnswers, 'complete' => $allCorrect && $requestedComplete];
    }
    $inputReady = isset($input['ready']) && is_array($input['ready']) ? $input['ready'] : [];
    foreach (['gear', 'condition', 'question'] as $key) $state['ready'][$key] = !empty($inputReady[$key]);
    return $state;
}

function course_is_complete(array $state): bool
{
    foreach (array_keys(course_answer_key()) as $module) {
        if (empty($state['modules'][$module]['complete'])) return false;
    }
    foreach (['gear', 'condition', 'question'] as $key) {
        if (empty($state['ready'][$key])) return false;
    }
    return true;
}

function load_progress(int $userId, string $slug = APP_COURSE): array
{
    $stmt = db()->prepare('SELECT state_json, completion_code, completed_at FROM course_progress WHERE user_id = ? AND course_slug = ?');
    $stmt->execute([$userId, $slug]);
    $row = $stmt->fetch();
    $state = $row ? json_decode((string)$row['state_json'], true) : null;
    if (!is_array($state)) $state = clean_course_state([]);
    $learnerStmt = db()->prepare('SELECT learner_id FROM users WHERE id = ?');
    $learnerStmt->execute([$userId]);
    $state['completion'] = $row && $row['completion_code'] ? [
        'learnerId' => (string)($learnerStmt->fetchColumn() ?: ''),
        'issuedAt' => $row['completed_at'],
        'code' => $row['completion_code'],
    ] : null;
    return $state;
}

function save_progress(int $userId, array $input, bool $issueCompletion = false): array
{
    $pdo = db();
    $state = clean_course_state($input);
    $existing = $pdo->prepare('SELECT completion_code, completed_at FROM course_progress WHERE user_id = ? AND course_slug = ?');
    $existing->execute([$userId, APP_COURSE]);
    $row = $existing->fetch();
    $code = $row['completion_code'] ?? null;
    $completedAt = $row['completed_at'] ?? null;
    if ($issueCompletion && course_is_complete($state) && !$code) {
        $date = (new DateTimeImmutable('now', new DateTimeZone('Asia/Tokyo')))->format('Ymd');
        $code = 'AOW-' . $date . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        $completedAt = now_iso();
    }
    $json = json_encode($state, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    $stmt = $pdo->prepare('INSERT INTO course_progress (user_id, course_slug, state_json, completion_code, completed_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?)
        ON CONFLICT(user_id, course_slug) DO UPDATE SET state_json=excluded.state_json, completion_code=excluded.completion_code, completed_at=excluded.completed_at, updated_at=excluded.updated_at');
    $stmt->execute([$userId, APP_COURSE, $json, $code, $completedAt, now_iso()]);
    return load_progress($userId);
}

function portal_head(string $title): void
{
    security_headers();
    echo '<!doctype html><html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<meta name="robots" content="noindex,nofollow,noarchive"><meta name="theme-color" content="#12324a">';
    echo '<title>' . h($title) . ' | 三浦 海の学校</title><link rel="stylesheet" href="portal.css"><link rel="stylesheet" href="admin-preview.css"></head><body>';
}

function portal_end(): void
{
    echo '</body></html>';
}
