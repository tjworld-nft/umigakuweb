<?php
/**
 * お問い合わせページ（CSRF トークン生成付き）
 * 
 * このファイルがXserver上で /contact/ にアクセスされた時に読み込まれます。
 * index.html の代わりにこちらが優先されます。
 * CSRFトークンを生成してフォームに埋め込みます。
 */
session_start();

// CSRFトークンの生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// index.htmlを読み込んでCSRFトークンを埋め込む
$html = file_get_contents(__DIR__ . '/index.html');

// CSRFトークンを埋め込む
$html = str_replace(
    'name="csrf_token" id="csrfToken" value=""',
    'name="csrf_token" id="csrfToken" value="' . htmlspecialchars($csrf_token) . '"',
    $html
);

echo $html;
