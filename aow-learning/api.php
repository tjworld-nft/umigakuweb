<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
security_headers(false);
header('Content-Type: application/json; charset=utf-8');
// 未指定は既存AOWクライアントとの互換性を保つ。講座権限はPOST本文では決めない。
$slug = isset($_GET['course']) ? (is_string($_GET['course']) ? $_GET['course'] : '') : APP_COURSE;
$user = current_user();
if (!$user || !course_available($slug) || !has_course((int)$user['id'], $slug)) {
    http_response_code(401);
    echo json_encode(['error' => 'authentication_required']);
    exit;
}
try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode(['state' => load_progress((int)$user['id'], $slug), 'learnerId' => $user['learner_id']], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'method_not_allowed']);
        exit;
    }
    $payload = json_decode((string)file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    require_csrf(isset($payload['csrf']) ? (string)$payload['csrf'] : '', true);
    $state = isset($payload['state']) && is_array($payload['state']) ? $payload['state'] : [];
    $saved = save_progress((int)$user['id'], $state, !empty($payload['issueCompletion']), $slug);
    echo json_encode(['state' => $saved, 'learnerId' => $user['learner_id']], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    error_log('[Learning progress API] ' . get_class($e) . ': ' . $e->getMessage());
    http_response_code(400);
    echo json_encode(['error' => 'request_failed']);
}
