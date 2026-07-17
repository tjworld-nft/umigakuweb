<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
if (current_user()) redirect('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && app_ready()) {
    require_csrf();
    $learnerId = strtoupper(trim((string)($_POST['learner_id'] ?? '')));
    $key = attempt_key($learnerId);
    if (login_blocked($key)) {
        $error = '入力回数が上限に達しました。10分ほど待ってからお試しください。';
    } else {
        $stmt = db()->prepare('SELECT * FROM users WHERE learner_id = ? AND status = "active"');
        $stmt->execute([$learnerId]);
        $user = $stmt->fetch();
        if ($user && password_verify((string)($_POST['password'] ?? ''), (string)$user['password_hash'])) {
            clear_login_failures($key);
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            db()->prepare('UPDATE users SET last_login_at = ? WHERE id = ?')->execute([now_iso(), (int)$user['id']]);
            redirect('dashboard.php');
        }
        record_login_failure($key);
        $error = '受講者IDまたはパスワードが一致しません。';
    }
}
portal_head('受講生ログイン');
?>
<main class="auth-page"><section class="auth-card">
  <div class="auth-card__head"><p class="eyebrow">STUDENT SIGN IN</p><h1>学習を続ける</h1><p class="lead">初回登録で発行された受講者IDと、ご自身で設定したパスワードを入力してください。</p></div>
  <?php if (!app_ready()): ?><p class="setup-note">現在、受講システムの初期設定中です。</p><?php endif; ?>
  <?php if ($error): ?><p class="alert"><?= h($error) ?></p><?php endif; ?>
  <form method="post" autocomplete="on">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
    <div class="field"><label for="learner_id">受講者ID</label><input id="learner_id" name="learner_id" required autocomplete="username" placeholder="UMI-XXXX-XXXX"></div>
    <div class="field"><label for="password">パスワード</label><input id="password" name="password" type="password" required autocomplete="current-password"></div>
    <button class="primary" type="submit" <?= app_ready() ? '' : 'disabled' ?>>ログイン</button>
  </form>
  <div class="auth-links"><a href="register.php">初回登録はこちら</a><a href="recover.php">パスワードを忘れた方</a></div>
</section></main>
<?php portal_end(); ?>
