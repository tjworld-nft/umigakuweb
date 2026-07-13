<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
if (current_user()) redirect('dashboard.php');
$error = '';
$created = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && app_ready()) {
    require_csrf();
    $attemptKey = attempt_key('REGISTER');
    $invite = invite_record((string)($_POST['invite_code'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (login_blocked($attemptKey)) {
        $error = '入力回数が上限に達しました。10分ほど待ってからお試しください。';
    } elseif (!$invite) {
        record_login_failure($attemptKey);
        $error = '初回登録コードが無効、使用済み、または期限切れです。';
    } elseif (strlen($password) < 10) {
        $error = 'パスワードは10文字以上で設定してください。';
    } elseif (!hash_equals($password, (string)($_POST['password_confirm'] ?? ''))) {
        $error = '確認用パスワードが一致しません。';
    } else {
        $pdo = db();
        $learnerId = unique_learner_id($pdo);
        $recoveryCode = random_code('REC', 3);
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO users (learner_id, password_hash, recovery_hash, created_at) VALUES (?, ?, ?, ?)');
            $stmt->execute([$learnerId, password_hash($password, PASSWORD_DEFAULT), token_hash($recoveryCode), now_iso()]);
            $userId = (int)$pdo->lastInsertId();
            redeem_invite($pdo, $invite, $userId);
            $pdo->commit();
            clear_login_failures($attemptKey);
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;
            $_SESSION['new_credentials'] = ['learner_id' => $learnerId, 'recovery_code' => $recoveryCode];
            redirect('welcome.php');
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = '登録を完了できませんでした。コードを確認してもう一度お試しください。';
        }
    }
}
portal_head('初回登録');
?>
<main class="auth-page"><section class="auth-card">
  <div class="auth-card__head"><p class="eyebrow">FIRST REGISTRATION</p><h1>自分のパスワードを設定</h1><p class="lead">申込時に受け取った初回登録コードを入力します。氏名・メールアドレス・電話番号は登録しません。</p></div>
  <?php if (!app_ready()): ?><p class="setup-note">現在、受講システムの初期設定中です。</p><?php endif; ?>
  <?php if ($error): ?><p class="alert"><?= h($error) ?></p><?php endif; ?>
  <form method="post" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
    <div class="field"><label for="invite_code">初回登録コード</label><input id="invite_code" name="invite_code" required placeholder="ENR-XXXX-XXXX" autocapitalize="characters"><small>1回だけ使用できるコードです。</small></div>
    <div class="field"><label for="password">設定するパスワード</label><input id="password" name="password" type="password" minlength="10" required autocomplete="new-password"><small>10文字以上。ほかのサービスと同じパスワードは避けてください。</small></div>
    <div class="field"><label for="password_confirm">パスワードをもう一度</label><input id="password_confirm" name="password_confirm" type="password" minlength="10" required autocomplete="new-password"></div>
    <button class="primary" type="submit" <?= app_ready() ? '' : 'disabled' ?>>匿名アカウントを作成</button>
  </form>
  <div class="auth-links"><a href="login.php">すでに登録済みの方</a></div>
</section></main>
<?php portal_end(); ?>
