<?php
// エラー表示設定（デバッグ用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 文字エンコーディング設定
mb_language("japanese");
mb_internal_encoding("UTF-8");

// セキュリティヘッダー
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// POSTメソッドチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /owd-license/');
    exit;
}

// CSRF保護（簡易版） - 一時的に無効化
// session_start();
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     header('Location: /owd-license/?error=csrf');
//     exit;
// }

// 入力データ取得
$course_type = isset($_POST['course_type']) ? trim($_POST['course_type']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$plan = isset($_POST['plan']) ? trim($_POST['plan']) : '';
$participants = isset($_POST['participants']) ? trim($_POST['participants']) : '';
$preferred_date = isset($_POST['preferred_date']) ? trim($_POST['preferred_date']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// バリデーション
$errors = array();

if (empty($name)) {
    $errors[] = 'お名前は必須です。';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '正しいメールアドレスを入力してください。';
}
if (empty($phone)) {
    $errors[] = '電話番号は必須です。';
}
if (empty($plan)) {
    $errors[] = 'コースプランを選択してください。';
}

// エラーがある場合はリダイレクト
if (!empty($errors)) {
    $error_msg = urlencode(implode('、', $errors));
    header('Location: /owd-license/?error=' . $error_msg);
    exit;
}

// データサニタイズ
$course_type = htmlspecialchars($course_type, ENT_QUOTES, 'UTF-8');
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$plan = htmlspecialchars($plan, ENT_QUOTES, 'UTF-8');
$participants = htmlspecialchars($participants, ENT_QUOTES, 'UTF-8');
$preferred_date = htmlspecialchars($preferred_date, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// メール設定
$to = 'info@miura-diving.com';
$subject = mb_encode_mimeheader('【三浦海の学校】OWDライセンス講習お申し込み', 'UTF-8');

// メール本文作成
$body = "三浦海の学校 OWDライセンス講習のお申し込みをいただきました。\n\n";
$body .= "=== お申し込み内容 ===\n";
$body .= "コース種別: " . $course_type . "\n";
$body .= "選択プラン: " . $plan . "\n";
$body .= "参加人数: " . ($participants ? $participants . "名" : "1名") . "\n\n";

$body .= "=== お客様情報 ===\n";
$body .= "お名前: " . $name . "\n";
$body .= "メールアドレス: " . $email . "\n";
$body .= "電話番号: " . $phone . "\n";
$body .= "ご希望日: " . ($preferred_date ? $preferred_date : '指定なし') . "\n\n";

$body .= "=== お問い合わせ内容 ===\n";
$body .= ($message ? $message : 'なし') . "\n\n";

$body .= "=== 送信情報 ===\n";
$body .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n";
$body .= "送信者IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$body .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";

// メールヘッダー
$headers = "From: info@miura-diving.com\r\n";
$headers .= "Return-Path: info@miura-diving.com\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// 顧客向け自動返信メール
$customer_subject = mb_encode_mimeheader('【三浦海の学校】OWDライセンス講習お申し込み受付完了', 'UTF-8');
$customer_body = $name . " 様\n\n";
$customer_body .= "この度は三浦海の学校のOWDライセンス講習にお申し込みいただき、ありがとうございます。\n\n";
$customer_body .= "以下の内容でお申し込みを受け付けました。\n\n";
$customer_body .= "選択プラン: " . $plan . "\n";
$customer_body .= "参加人数: " . ($participants ? $participants . "名" : "1名") . "\n";
$customer_body .= "ご希望日: " . ($preferred_date ? $preferred_date : '指定なし') . "\n\n";
$customer_body .= "担当者より24時間以内にご連絡いたします。\n";
$customer_body .= "しばらくお待ちください。\n\n";
$customer_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$customer_body .= "三浦海の学校\n";
$customer_body .= "〒238-0225 神奈川県三浦市三崎町城ヶ島658-8\n";
$customer_body .= "TEL: 046-888-0099\n";
$customer_body .= "Email: info@miura-diving.com\n";
$customer_body .= "Website: https://miura-diving.com/\n";
$customer_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$customer_headers = "From: info@miura-diving.com\r\n";
$customer_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// メール送信
$admin_mail_sent = mb_send_mail($to, $subject, $body, $headers);
$customer_mail_sent = mb_send_mail($email, $customer_subject, $customer_body, $customer_headers);

// ログ記録（オプション）
$log_entry = date('Y-m-d H:i:s') . " - OWD申し込み - " . $name . " (" . $email . ") - " . $plan . "\n";
file_put_contents('form_submissions.log', $log_entry, FILE_APPEND | LOCK_EX);

// 結果処理
if ($admin_mail_sent) {
    echo "<h1>✅ メール送信成功！</h1>";
    echo "<p>管理者メール送信: " . ($admin_mail_sent ? "成功" : "失敗") . "</p>";
    echo "<p>お客様メール送信: " . ($customer_mail_sent ? "成功" : "失敗") . "</p>";
    echo "<p>送信先: " . $to . "</p>";
    echo "<p>お客様メール: " . $email . "</p>";
    echo "<p><a href='/owd-license/'>戻る</a></p>";
    // header('Location: /owd-thanks.html');
    exit;
} else {
    echo "<h1>❌ メール送信失敗</h1>";
    echo "<p>送信先: " . $to . "</p>";
    echo "<p>件名: " . $subject . "</p>";
    echo "<p>本文の長さ: " . strlen($body) . " bytes</p>";
    echo "<p>PHPのmail関数: " . (function_exists('mail') ? "利用可能" : "利用不可") . "</p>";
    echo "<p>mb_send_mail関数: " . (function_exists('mb_send_mail') ? "利用可能" : "利用不可") . "</p>";
    echo "<p><a href='/owd-license/'>戻る</a></p>";
    exit;
}
?>