<?php
// 旧フォームの送信入口は廃止。残存URLから保護のないメールを送らせない。
header('Cache-Control: no-store');
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET' || ($_SERVER['REQUEST_METHOD'] ?? '') === 'HEAD') {
    header('Location: /contact/', true, 301);
    exit;
}
http_response_code(410);
if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['success' => false, 'message' => 'この送信先は現在使われていません。お問い合わせページから送信してください。'], JSON_UNESCAPED_UNICODE);
} else {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="ja"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>お問い合わせ先の変更</title><p>この送信先は現在使われていません。</p><p><a href="/contact/">現在のお問い合わせフォーム</a>から送信してください。</p></html>';
}
exit;
