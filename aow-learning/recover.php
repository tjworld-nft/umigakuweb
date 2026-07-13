<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$error = '';
$newRecovery = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && app_ready()) {
    require_csrf();
    $learnerId = strtoupper(trim((string)($_POST['learner_id'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $attemptKey = attempt_key('RECOVERY:' . $learnerId);
    $stmt = db()->prepare('SELECT * FROM users WHERE learner_id = ? AND status = "active"');
    $stmt->execute([$learnerId]);
    $user = $stmt->fetch();
    if (login_blocked($attemptKey)) {
        $error = '入力回数が上限に達しました。10分ほど待ってからお試しください。';
    } elseif (!$user || !hash_equals((string)$user['recovery_hash'], token_hash((string)($_POST['recovery_code'] ?? '')))) {
        record_login_failure($attemptKey);
        $error = '受講者IDまたは復旧コードが一致しません。';
    } elseif (strlen($password) < 10) {
        $error = '新しいパスワードは10文字以上で設定してください。';
    } elseif (!hash_equals($password, (string)($_POST['password_confirm'] ?? ''))) {
        $error = '確認用パスワードが一致しません。';
    } else {
        $newRecovery = random_code('REC', 3);
        db()->prepare('UPDATE users SET password_hash = ?, recovery_hash = ? WHERE id = ?')->execute([
            password_hash($password, PASSWORD_DEFAULT), token_hash($newRecovery), (int)$user['id']
        ]);
        clear_login_failures($attemptKey);
        $_SESSION = [];
    }
}
portal_head('パスワード再設定');
?>
<main class="auth-page"><section class="auth-card">
  <div class="auth-card__head"><p class="eyebrow">PASSWORD RECOVERY</p><h1>パスワードを再設定</h1><p class="lead">初回登録時に保存した復旧コードを使います。</p></div>
  <?php if ($error): ?><p class="alert"><?= h($error) ?></p><?php endif; ?>
  <?php if ($newRecovery): ?>
    <div class="credentials"><p class="alert alert--success" style="margin:0">パスワードを変更しました。</p><div class="credential-box"><span>新しい復旧コード</span><b><?= h($newRecovery) ?></b></div><p class="warning">以前の復旧コードは無効です。新しいコードを保存してください。</p><a class="primary" style="display:block;text-align:center;text-decoration:none" href="login.php">ログインへ</a></div>
  <?php else: ?>
  <form method="post" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
    <div class="field"><label for="learner_id">受講者ID</label><input id="learner_id" name="learner_id" required placeholder="UMI-XXXX-XXXX"></div>
    <div class="field"><label for="recovery_code">復旧コード</label><input id="recovery_code" name="recovery_code" required placeholder="REC-XXXX-XXXX-XXXX"></div>
    <div class="field"><label for="password">新しいパスワード</label><input id="password" name="password" type="password" minlength="10" required autocomplete="new-password"></div>
    <div class="field"><label for="password_confirm">新しいパスワードをもう一度</label><input id="password_confirm" name="password_confirm" type="password" minlength="10" required autocomplete="new-password"></div>
    <button class="primary" type="submit">再設定する</button>
  </form>
  <div class="auth-links"><a href="login.php">ログインへ戻る</a></div>
  <?php endif; ?>
</section></main>
<?php portal_end(); ?>
