<?php
/**
 * Contact Form Handler
 * 三浦 海の学校 お問い合わせフォーム処理
 */

// WordPressの読み込み
require_once dirname(__FILE__) . '/../../../wp-load.php';

// セキュリティチェック
if (!wp_verify_nonce($_POST['_wpnonce'], 'umigaku_contact')) {
    wp_die('不正なアクセスです。');
}

// POSTメソッドのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wp_die('不正なアクセス方法です。');
}

// エラーメッセージ配列
$errors = array();

// 入力値の取得とサニタイズ
$name_kanji = sanitize_text_field($_POST['name_kanji'] ?? '');
$name_romaji = sanitize_text_field($_POST['name_romaji'] ?? '');
$email = sanitize_email($_POST['email'] ?? '');
$preferred_date = sanitize_text_field($_POST['preferred_date'] ?? '');
$message = sanitize_textarea_field($_POST['message'] ?? '');

// バリデーション
if (empty($name_kanji)) {
    $errors[] = '氏名（漢字）は必須です。';
}

if (empty($name_romaji)) {
    $errors[] = '氏名（ローマ字）は必須です。';
}

if (empty($email)) {
    $errors[] = 'メールアドレスは必須です。';
} elseif (!is_email($email)) {
    $errors[] = '正しいメールアドレスを入力してください。';
}

if (empty($message)) {
    $errors[] = 'お問い合わせ内容は必須です。';
}

// エラーがある場合はセッションに保存してリダイレクト
if (!empty($errors)) {
    session_start();
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_data'] = $_POST;
    wp_safe_redirect(site_url('/contact/'));
    exit;
}

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
$mail_body .= "送信日時: " . current_time('Y年n月j日 H:i') . "\n";
$mail_body .= "送信者IP: " . $_SERVER['REMOTE_ADDR'] . "\n";

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

// メールヘッダー設定
$headers = array(
    'Content-Type: text/plain; charset=UTF-8',
    'From: 三浦 海の学校 <info@miura-diving.com>',
    'Reply-To: info@miura-diving.com'
);

$reply_headers = array(
    'Content-Type: text/plain; charset=UTF-8',
    'From: 三浦 海の学校 <info@miura-diving.com>',
    'Reply-To: info@miura-diving.com'
);

// メール送信実行
$mail_sent = wp_mail($to, $subject, $mail_body, $headers);
$reply_sent = wp_mail($email, $reply_subject, $reply_body, $reply_headers);

// 送信結果の確認
if ($mail_sent) {
    // 成功時はサンクスページにリダイレクト
    wp_safe_redirect(site_url('/contact-thanks/'));
    exit;
} else {
    // 失敗時はエラーメッセージをセッションに保存
    session_start();
    $_SESSION['contact_errors'] = array('メールの送信に失敗しました。しばらく時間をおいて再度お試しください。');
    $_SESSION['contact_data'] = $_POST;
    wp_safe_redirect(site_url('/contact/'));
    exit;
}
?>