<?php
// 旧PHP入口も現在の静的フォームへ。確認トークンはJSが送信APIから取得する。
$query = $_SERVER['QUERY_STRING'] ?? '';
header('Location: /contact/' . ($query !== '' ? '?' . $query : ''), true, 301);
exit;
