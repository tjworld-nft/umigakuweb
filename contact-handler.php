<?php
// エラー表示設定（デバッグ用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 文字エンコーディング設定
mb_language("japanese");
mb_internal_encoding("UTF-8");

// POSTメソッドチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

// 入力データ取得
$name_kanji = isset($_POST['name_kanji']) ? trim($_POST['name_kanji']) : '';
$name_romaji = isset($_POST['name_romaji']) ? trim($_POST['name_romaji']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$preferred_date = isset($_POST['preferred_date']) ? trim($_POST['preferred_date']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// バリデーション
$errors = array();

if (empty($name_kanji)) {
    $errors[] = '氏名（漢字）は必須です。';
}
if (empty($name_romaji)) {
    $errors[] = '氏名（ローマ字）は必須です。';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '正しいメールアドレスを入力してください。';
}
if (empty($message)) {
    $errors[] = 'お問い合わせ内容は必須です。';
}

// エラーがある場合はリダイレクト
if (!empty($errors)) {
    header('Location: contact.html?error=1');
    exit;
}

// データサニタイズ
$name_kanji = htmlspecialchars($name_kanji, ENT_QUOTES, 'UTF-8');
$name_romaji = htmlspecialchars($name_romaji, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$preferred_date = htmlspecialchars($preferred_date, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// メール設定
$to = 'info@miura-diving.com';
$subject = mb_encode_mimeheader('【三浦 海の学校】お問い合わせ', 'UTF-8');

// メール本文
$body = "三浦 海の学校へお問い合わせをいただきました。\n\n";
$body .= "氏名（漢字）: " . $name_kanji . "\n";
$body .= "氏名（ローマ字）: " . $name_romaji . "\n";
$body .= "メールアドレス: " . $email . "\n";
$body .= "ご希望日: " . ($preferred_date ? $preferred_date : '指定なし') . "\n\n";
$body .= "お問い合わせ内容:\n" . $message . "\n\n";
$body .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n";
$body .= "送信者IP: " . $_SERVER['REMOTE_ADDR'] . "\n";

// X-Server対応ヘッダー
$headers = "From: info@miura-diving.com\r\n";
$headers .= "Return-Path: info@miura-diving.com\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";

// メール送信
$mail_sent = mb_send_mail($to, $subject, $body, $headers);

// 結果処理
if ($mail_sent) {
    header('Location: contact-thanks.html');
    exit;
} else {
    // デバッグ情報
    echo "メール送信に失敗しました。<br>";
    echo "送信先: " . $to . "<br>";
    echo "件名: " . $subject . "<br>";
    echo "本文の長さ: " . strlen($body) . " bytes<br>";
    echo "<br><a href='contact.html'>戻る</a>";
    exit;
}
?>