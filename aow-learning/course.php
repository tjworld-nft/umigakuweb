<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$user = require_user();
$slug = (string)($_GET['course'] ?? '');
if ($slug !== APP_COURSE || !has_course((int)$user['id'], $slug)) {
    http_response_code(403);
    exit('この講座へのアクセス権がありません。');
}
security_headers();
$html = file_get_contents(__DIR__ . '/index.html');
if ($html === false) {
    http_response_code(500);
    exit('Course content unavailable.');
}
$meta = '<meta name="csrf-token" content="' . h(csrf_token()) . '"><meta name="learner-id" content="' . h((string)$user['learner_id']) . '">';
$html = str_replace('</head>', $meta . '</head>', $html);
$html = str_replace(
    '<span class="course-label">AOW PRE-STUDY</span>',
    '<div class="auth-actions"><a class="logout-link" href="dashboard.php">マイコース</a><span class="course-label">' . h((string)$user['learner_id']) . '</span><a class="logout-link" href="logout.php">ログアウト</a></div>',
    $html
);
echo $html;
