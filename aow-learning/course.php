<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$user = current_user();
$isAdminPreview = !empty($_SESSION['admin_authenticated']);
if (!$user && !$isAdminPreview) redirect('login.php');
$slug = isset($_GET['course']) ? (is_string($_GET['course']) ? $_GET['course'] : '') : APP_COURSE;
$definition = course_definition($slug);
if (!$definition || !course_available($slug) || (!$isAdminPreview && (!$user || !has_course((int)$user['id'], $slug)))) {
    http_response_code(403);
    exit('この講座へのアクセス権がありません。');
}
security_headers();
$html = file_get_contents(__DIR__ . '/' . $definition['file']);
if ($html === false) {
    http_response_code(500);
    exit('Course content unavailable.');
}
$learnerLabel = $isAdminPreview ? '管理者プレビュー' : (string)$user['learner_id'];
$meta = '<meta name="csrf-token" content="' . h(csrf_token()) . '"><meta name="learner-id" content="' . h($learnerLabel) . '">';
$meta .= '<meta name="course-slug" content="' . h($slug) . '"><meta name="curriculum-version" content="' . (int)$definition['curriculum_version'] . '">';
$meta .= '<meta name="course-config" content="' . h(json_encode(course_client_config($slug), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)) . '">';
if ($isAdminPreview) $meta .= '<meta name="admin-preview" content="1">';
$html = str_replace('</head>', $meta . '</head>', $html);
$appVersion = (string)(filemtime(__DIR__ . '/app.js') ?: time());
$html = str_replace('src="app.js"', 'src="app.js?v=' . rawurlencode($appVersion) . '"', $html);
foreach ($definition['styles'] as $styleFile) {
    $styleVersion = (string)(filemtime(__DIR__ . '/' . $styleFile) ?: time());
    $html = str_replace('href="' . $styleFile . '"', 'href="' . $styleFile . '?v=' . rawurlencode($styleVersion) . '"', $html);
}
if ($isAdminPreview) {
    $html = preg_replace('/(<body\b[^>]*>)/', '$1<div class="admin-preview-banner"><b>管理者プレビュー</b><span>回答や完了操作は受講生の記録に保存されません。</span></div>', $html, 1) ?? $html;
}
$html = str_replace(
    '<span class="course-label">' . h((string)$definition['label']) . '</span>',
    $isAdminPreview
        ? '<div class="auth-actions"><a class="logout-link" href="admin.php">管理画面へ戻る</a><span class="course-label">ADMIN PREVIEW</span></div>'
        : '<div class="auth-actions"><a class="logout-link" href="dashboard.php">マイコース</a><span class="course-label">' . h($learnerLabel) . '</span><a class="logout-link" href="logout.php">ログアウト</a></div>',
    $html
);
echo $html;
