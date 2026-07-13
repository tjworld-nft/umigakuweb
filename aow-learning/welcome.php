<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$user = require_user();
$credentials = $_SESSION['new_credentials'] ?? null;
if (!is_array($credentials)) redirect('dashboard.php');
unset($_SESSION['new_credentials']);
portal_head('登録完了');
?>
<main class="auth-page"><section class="auth-card">
  <div class="auth-card__head"><p class="eyebrow">REGISTRATION COMPLETE</p><h1>登録が完了しました</h1><p class="lead">以下の2つは、ログインとパスワード再設定に必要です。</p></div>
  <div class="credentials">
    <div class="credential-box"><span>受講者ID</span><b><?= h((string)$credentials['learner_id']) ?></b></div>
    <div class="credential-box"><span>復旧コード</span><b><?= h((string)$credentials['recovery_code']) ?></b></div>
    <p class="warning">復旧コードはこの画面で一度だけ表示されます。スクリーンショットやパスワード管理アプリに保存してください。</p>
    <a class="primary" style="display:block;text-align:center;text-decoration:none" href="dashboard.php">マイコースへ</a>
  </div>
</section></main>
<?php portal_end(); ?>
