<?php
/**
 * Contact Form Handler - Standalone Version
 * 三浦 海の学校 お問い合わせフォーム処理（Xサーバー対応版）
 */

// セッション開始
session_start();

// POSTメソッドのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

// CSRF対策：簡易トークンチェック
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = '不正なアクセスです。';
    header('Location: contact.html');
    exit;
}

// エラーメッセージ配列
$errors = array();

// 入力値の取得とサニタイズ
$name_kanji = trim(htmlspecialchars($_POST['name_kanji'] ?? '', ENT_QUOTES, 'UTF-8'));
$name_romaji = trim(htmlspecialchars($_POST['name_romaji'] ?? '', ENT_QUOTES, 'UTF-8'));
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$preferred_date = trim(htmlspecialchars($_POST['preferred_date'] ?? '', ENT_QUOTES, 'UTF-8'));
$message = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));

// バリデーション
if (empty($name_kanji)) {
    $errors[] = '氏名（漢字）は必須です。';
}

if (empty($name_romaji)) {
    $errors[] = '氏名（ローマ字）は必須です。';
}

if (empty($email)) {
    $errors[] = 'メールアドレスは必須です。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '正しいメールアドレスを入力してください。';
}

if (empty($message)) {
    $errors[] = 'お問い合わせ内容は必須です。';
}

// 文字数制限チェック
if (mb_strlen($name_kanji) > 50) {
    $errors[] = '氏名（漢字）は50文字以内で入力してください。';
}

if (mb_strlen($name_romaji) > 100) {
    $errors[] = '氏名（ローマ字）は100文字以内で入力してください。';
}

if (mb_strlen($message) > 2000) {
    $errors[] = 'お問い合わせ内容は2000文字以内で入力してください。';
}

// 日付形式チェック（入力があった場合のみ）
if (!empty($preferred_date)) {
    $date_check = DateTime::createFromFormat('Y-m-d', $preferred_date);
    if (!$date_check || $date_check->format('Y-m-d') !== $preferred_date) {
        $errors[] = '正しい日付形式で入力してください。';
    }
}

// エラーがある場合はセッションに保存してリダイレクト
if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_data'] = $_POST;
    header('Location: contact.html');
    exit;
}

// 現在の日時
date_default_timezone_set('Asia/Tokyo');
$current_time = date('Y年n月j日 H:i');

// メール送信準備
$to = 'info@miura-diving.com';
$subject = '【三浦 海の学校】お問い合わせフォームより';

// メール本文作成
$mail_body = "三浦 海の学校へお問い合わせをいただき、ありがとうございます。\n\n";
$mail_body .= "■ お問い合わせ内容\n";
$mail_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$mail_body .= "氏名（漢字）: " . $name_kanji . "\n";
$mail_body .= "氏名（ローマ字）: " . $name_romaji . "\n";
$mail_body .= "メールアドレス: " . $email . "\n";
if (!empty($preferred_date)) {
    $mail_body .= "ご希望日: " . $preferred_date . "\n";
}
$mail_body .= "お問い合わせ内容:\n" . $message . "\n";
$mail_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
$mail_body .= "送信日時: " . $current_time . "\n";
$mail_body .= "送信者IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$mail_body .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";

// 自動返信メール本文
$reply_subject = '【三浦 海の学校】お問い合わせありがとうございます';
$reply_body = $name_kanji . " 様\n\n";
$reply_body .= "この度は三浦 海の学校へお問い合わせいただき、誠にありがとうございます。\n\n";
$reply_body .= "以下の内容でお問い合わせを承りました。\n";
$reply_body .= "2営業日以内にご返信いたしますので、今しばらくお待ちください。\n\n";
$reply_body .= "■ お問い合わせ内容\n";
$reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$reply_body .= "氏名（漢字）: " . $name_kanji . "\n";
$reply_body .= "氏名（ローマ字）: " . $name_romaji . "\n";
$reply_body .= "メールアドレス: " . $email . "\n";
if (!empty($preferred_date)) {
    $reply_body .= "ご希望日: " . $preferred_date . "\n";
}
$reply_body .= "お問い合わせ内容:\n" . $message . "\n";
$reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
$reply_body .= "■ 三浦 海の学校\n";
$reply_body .= "〒238-0224 神奈川県三浦市三崎町諸磯1621\n";
$reply_body .= "TEL: 046-880-0835\n";
$reply_body .= "Email: info@miura-diving.com\n";
$reply_body .= "Website: https://miura-diving.com/\n\n";
$reply_body .= "※このメールは自動送信です。直接返信いただいても対応できませんのでご了承ください。";

// メールヘッダー設定（Xサーバー対応）
$headers = "From: 三浦 海の学校 <info@miura-diving.com>\r\n";
$headers .= "Reply-To: info@miura-diving.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$reply_headers = "From: 三浦 海の学校 <info@miura-diving.com>\r\n";
$reply_headers .= "Reply-To: info@miura-diving.com\r\n";
$reply_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$reply_headers .= "Content-Transfer-Encoding: 8bit\r\n";
$reply_headers .= "X-Mailer: PHP/" . phpversion();

// メール送信実行
$mail_sent = false;
$reply_sent = false;

try {
    // 管理者向けメール送信
    $mail_sent = mail($to, $subject, $mail_body, $headers);
    
    // 自動返信メール送信
    $reply_sent = mail($email, $reply_subject, $reply_body, $reply_headers);
    
} catch (Exception $e) {
    error_log('Mail sending error: ' . $e->getMessage());
}

// 送信結果の確認
if ($mail_sent) {
    // セッションデータクリア
    unset($_SESSION['contact_errors']);
    unset($_SESSION['contact_data']);
    unset($_SESSION['csrf_token']);
    
    // 成功時はサンクスページにリダイレクト
    header('Location: contact-thanks.html');
    exit;
} else {
    // 失敗時はエラーメッセージをセッションに保存
    $_SESSION['contact_errors'] = array('メールの送信に失敗しました。しばらく時間をおいて再度お試しください。');
    $_SESSION['contact_data'] = $_POST;
    header('Location: contact.html');
    exit;
}
?>