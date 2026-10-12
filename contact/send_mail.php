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

// エラー内容をお客さまの画面に出さず、サーバーのログへ残す。
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');
mb_language('Japanese');
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

$TO_EMAIL = 'info@miura-diving.com';
$FROM_EMAIL = 'info@miura-diving.com';
$SITE_NAME = '三浦 海の学校';

function respond($code, $ok, $message, $extra = []) {
    // 更新前のHTMLやJSなしの送信にも、既存の戻り先を用意する。
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' &&
        strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') === false) {
        $reasons = [400 => 'validation', 403 => 'csrf', 409 => 'duplicate', 413 => 'validation', 429 => 'rate', 500 => 'send', 503 => 'storage'];
        $location = $ok ? '/contact/?status=success' : '/contact/?status=error&reason=' . ($reasons[$code] ?? 'send');
        header('Location: ' . $location, true, 303);
        exit;
    }
    http_response_code($code);
    echo json_encode(array_merge(['success' => $ok, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

define('UMIGAKU_CONTACT_HANDLER', true);
require __DIR__ . '/contact-guard.php';
contact_check_origin();
$method = $_SERVER['REQUEST_METHOD'] ?? '';
if ($method === 'GET' && ($_GET['action'] ?? '') === 'token') contact_issue_token();
if ($method !== 'POST') {
    header('Allow: GET, POST');
    respond(405, false, 'お問い合わせページから送信してください。');
}
if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 32768) respond(413, false, '入力内容が長すぎます。');
foreach ($_POST as $value) {
    if (!is_string($value) || !mb_check_encoding($value, 'UTF-8')) respond(400, false, '入力内容をご確認ください。');
}
if (trim($_POST['website'] ?? '') !== '') respond(200, true, 'お問い合わせを受け付けました。');
$token = contact_verify_token();

// メールはプレーンテキスト。HTMLエスケープやバックスラッシュ除去で原文を変えない。
function field($key, $max, $multiline = false) {
    $value = trim($_POST[$key] ?? '');
    if (mb_strlen($value, 'UTF-8') > $max || preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $value)) {
        respond(400, false, '入力内容の長さや文字をご確認ください。');
    }
    $value = str_replace(["\r\n", "\r"], "\n", $value);
    if (!$multiline && strpos($value, "\n") !== false) respond(400, false, '入力内容の改行をご確認ください。');
    return $value;
}
$name = field('name', 100);
$kana = field('kana', 100);
$email = field('email', 200);
$phone = field('phone', 40);
$category = field('category', 40);
$date = field('date', 10);
$people = field('people', 10);
$message = field('message', 5000, true);
$ref = substr(preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['ref'] ?? ''), 0, 32);
$ref_line = $ref !== '' ? "【流入元】 {$ref}\n" : '';
$categories = ['体験ダイビング', 'OWDライセンス', 'AOWライセンス', 'ファンダイビング', 'リフレッシュ', 'マリンアクティビティ', '書籍・講座', 'その他'];
$errors = [];
if ($name === '') $errors[] = 'お名前を入力してください。';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = '有効なメールアドレスを入力してください。';
if (!in_array($category, $categories, true)) $errors[] = 'お問い合わせ種別を選択してください。';
if ($message === '') $errors[] = 'お問い合わせ内容を入力してください。';
if (!in_array($people, ['', '1名', '2名', '3名', '4名', '5名以上'], true)) $errors[] = '参加人数を選択してください。';
if ($date !== '') {
    $parts = explode('-', $date);
    if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/D', $date) ||
        !checkdate((int)($parts[1] ?? 0), (int)($parts[2] ?? 0), (int)($parts[0] ?? 0))) $errors[] = 'ご希望日をご確認ください。';
}
if ($errors) respond(400, false, implode("\n", $errors));

unset($_SESSION['contact_tokens'][$token]);
if (!session_write_close()) contact_unavailable();
// 希望日・人数・コースを変えた再相談は、同じ文面でも別の問い合わせとして扱う。
$reservation = contact_reserve($email, $message, $name, implode("\n", [$category, $date, $people]));

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
{$ref_line}
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
// mb_send_mail は件名・本文を自動で ISO-2022-JP に変換するため
// Content-Type に UTF-8 を指定しない（衝突して文字化けする）
$subject = "【お問い合わせ】{$category} - {$name}様";

// Fromの日本語名をMIMEエンコード。Reply-Toは検証済みアドレスだけに限定する。
$from_name_encoded = mb_encode_mimeheader($SITE_NAME, 'ISO-2022-JP', 'B');

$headers  = "From: {$from_name_encoded} <{$FROM_EMAIL}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
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

内容を確認のうえ、担当者からご連絡いたします。
────────────────────────────

◆ 三浦 海の学校
◆ 神奈川県三浦市（開催場所・集合場所はご予約時にご案内します）
◆ LINE: https://lin.ee/Y3nB18U
◆ TEL: 080-4350-0412
◆ 営業時間: 9:00〜16:00（不定休）
◆ https://miura-diving.com

※ このメールは自動送信です。
  心当たりのない場合は、お手数ですが破棄してください。
EOT;

    $auto_reply_headers  = "From: {$from_name_encoded} <{$FROM_EMAIL}>\r\n";

    mb_send_mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_headers);
}

if (!$result) {
    contact_release($reservation);
    respond(500, false, 'メールの送信に失敗しました。入力内容を控えて、LINEまたはinfo@miura-diving.comへお問い合わせください。');
}
respond(200, true, 'お問い合わせを受け付けました。通常1営業日以内にご返信します。');
