<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$user = current_user();
$isAdminPreview = !empty($_SESSION['admin_authenticated']);
if (!$user && !$isAdminPreview) redirect('login.php');
$slug = (string)($_GET['course'] ?? '');
if ($slug !== APP_COURSE || (!$isAdminPreview && (!$user || !has_course((int)$user['id'], $slug)))) {
    http_response_code(403);
    exit('この講座へのアクセス権がありません。');
}
security_headers();
$html = file_get_contents(__DIR__ . '/index.html');
if ($html === false) {
    http_response_code(500);
    exit('Course content unavailable.');
}
$learnerLabel = $isAdminPreview ? '管理者プレビュー' : (string)$user['learner_id'];
$meta = '<meta name="csrf-token" content="' . h(csrf_token()) . '"><meta name="learner-id" content="' . h($learnerLabel) . '">';
if ($isAdminPreview) $meta .= '<meta name="admin-preview" content="1">';
$html = str_replace('</head>', $meta . '</head>', $html);
$appVersion = (string)(filemtime(__DIR__ . '/app.js') ?: time());
$html = str_replace('src="app.js"', 'src="app.js?v=' . rawurlencode($appVersion) . '"', $html);
$html = $isAdminPreview ? str_replace('<body>', '<body><div class="admin-preview-banner"><b>管理者プレビュー</b><span>回答や修了操作は受講生の記録に保存されません。</span></div>', $html) : $html;
$html = str_replace(
    '<span class="course-label">AOW PRE-STUDY</span>',
    $isAdminPreview
        ? '<div class="auth-actions"><a class="logout-link" href="admin.php">管理画面へ戻る</a><span class="course-label">ADMIN PREVIEW</span></div>'
        : '<div class="auth-actions"><a class="logout-link" href="dashboard.php">マイコース</a><span class="course-label">' . h($learnerLabel) . '</span><a class="logout-link" href="logout.php">ログアウト</a></div>',
    $html
);
echo $html;
