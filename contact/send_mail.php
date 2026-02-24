<?php
/**
 * お問い合わせフォーム メール送信スクリプト
 * 三浦 海の学校
 * 
 * Xserverの PHP mail() 関数を使用。
 * Xserver管理画面でメール転送設定をすればGmailにも自動転送されます。
 * 
 * セキュリティ: CSRF対策、入力サニタイズ、バリデーション、レート制限
 */

// エラー表示を本番環境では無効化
ini_set('display_errors', 0);
error_reporting(0);

// 日本語メール送信設定
mb_internal_encoding('UTF-8');
mb_language('Japanese');

// セッション開始（CSRF対策用）
session_start();

// ===== 設定 =====
$TO_EMAIL      = 'info@miura-diving.com';  // 送信先メールアドレス（Xserver上のメールアドレス）
$FROM_EMAIL    = 'info@miura-diving.com';  // 送信元もinfo@（Xserverに登録済みのアドレスを使用）
$SITE_NAME     = '三浦 海の学校';
$REDIRECT_OK   = '/contact/?status=success';  // 送信成功時のリダイレクト先
$REDIRECT_ERR  = '/contact/?status=error';    // エラー時のリダイレクト先

// ===== アクセス制御 =====
// POST以外のリクエストは拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method Not Allowed');
}

// ===== CSRFトークン検証 =====
// index.php経由ならトークンあり、index.html直接アクセスなら空値でスキップ
$csrf_token = $_POST['csrf_token'] ?? '';
if (!empty($csrf_token) && isset($_SESSION['csrf_token'])) {
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        header('Location: ' . $REDIRECT_ERR . '&reason=csrf');
        exit;
    }
    // トークンを使い捨てにする（リプレイ攻撃防止）
    unset($_SESSION['csrf_token']);
}

// ===== レート制限（1分間に3回まで） =====
$rate_key = 'contact_rate_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
if (!isset($_SESSION[$rate_key])) {
    $_SESSION[$rate_key] = ['count' => 0, 'time' => time()];
}
if (time() - $_SESSION[$rate_key]['time'] < 60) {
    $_SESSION[$rate_key]['count']++;
    if ($_SESSION[$rate_key]['count'] > 3) {
        header('Location: ' . $REDIRECT_ERR . '&reason=rate');
        exit;
    }
} else {
    $_SESSION[$rate_key] = ['count' => 1, 'time' => time()];
}

// ===== ハニーポット（bot対策） =====
if (!empty($_POST['website'])) {
    // botが自動入力するフィールド。人間は入力しない
    header('Location: ' . $REDIRECT_OK); // botにはエラーを見せない
    exit;
}

// ===== 入力値の取得とサニタイズ =====
function clean($str) {
    $str = trim($str);
    $str = stripslashes($str);
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    // メールヘッダインジェクション対策
    $str = str_replace(["\r", "\n", "%0a", "%0d"], '', $str);
    return $str;
}

$name     = clean($_POST['name'] ?? '');
$kana     = clean($_POST['kana'] ?? '');
$email    = clean($_POST['email'] ?? '');
$phone    = clean($_POST['phone'] ?? '');
$category = clean($_POST['category'] ?? '');
$date     = clean($_POST['date'] ?? '');
$people   = clean($_POST['people'] ?? '');
$message  = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));

// ===== バリデーション =====
$errors = [];

if (empty($name)) {
    $errors[] = 'お名前は必須です';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '有効なメールアドレスを入力してください';
}
if (empty($category)) {
    $errors[] = 'お問い合わせ種別を選択してください';
}
if (empty($message)) {
    $errors[] = 'お問い合わせ内容を入力してください';
}
// 名前・メール長さチェック
if (mb_strlen($name) > 100) {
    $errors[] = 'お名前が長すぎます';
}
if (mb_strlen($message) > 5000) {
    $errors[] = 'メッセージが長すぎます（5000文字以内）';
}

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . $REDIRECT_ERR . '&reason=validation');
    exit;
}

// ===== メール本文の組み立て =====
$body = <<<EOT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  三浦 海の学校 - お問い合わせ
━━━━━━━━━━━━━━━━━━━━━━━━━━━━

【お名前】 {$name}
【フリガナ】 {$kana}
【メールアドレス】 {$email}
【電話番号】 {$phone}
【お問い合わせ種別】 {$category}
【ご希望日】 {$date}
【参加人数】 {$people}

── お問い合わせ内容 ──────────────
{$message}
────────────────────────────

送信日時: SEND_DATE
送信元IP: {$_SERVER['REMOTE_ADDR']}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━
※ このメールは miura-diving.com のお問い合わせフォームから
  自動送信されました。
EOT;

// 日時を埋め込み
$body = str_replace('SEND_DATE', date('Y年m月d日 H:i:s'), $body);

// ===== メールヘッダー =====
$subject = "【お問い合わせ】{$category} - {$name}様";

$headers  = "From: {$SITE_NAME} <{$FROM_EMAIL}>\r\n";
$headers .= "Reply-To: {$name} <{$email}>\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// ===== メール送信 =====
$result = mb_send_mail($TO_EMAIL, $subject, $body, $headers);

// ===== 自動返信メール（お客様向け） =====
if ($result) {
    $auto_reply_subject = "【三浦 海の学校】お問い合わせを受け付けました";
    $auto_reply_body = <<<EOT
{$name} 様

この度は三浦 海の学校にお問い合わせいただき、
誠にありがとうございます。

以下の内容でお問い合わせを受け付けました。
通常1営業日以内にご返信いたします。

────────────────────────────
【お問い合わせ種別】 {$category}
【ご希望日】 {$date}
【参加人数】 {$people}

── お問い合わせ内容 ──
{$message}
────────────────────────────

◆ 三浦 海の学校
◆ TEL: 046-880-0835
◆ 営業時間: 9:00〜16:00（不定休）
◆ 〒238-0224 神奈川県三浦市三崎町諸磯1621
◆ https://miura-diving.com

※ このメールは自動送信です。
  心当たりのない場合は、お手数ですが破棄してください。
EOT;

    $auto_reply_headers  = "From: {$SITE_NAME} <{$FROM_EMAIL}>\r\n";
    $auto_reply_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mb_send_mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_headers);
}

// ===== リダイレクト =====
if ($result) {
    header('Location: ' . $REDIRECT_OK);
} else {
    header('Location: ' . $REDIRECT_ERR . '&reason=send');
}
exit;
