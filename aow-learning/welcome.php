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
    <div class="credential-box">
      <span>受講者ID</span><b id="welcomeLearnerId"><?= h((string)$credentials['learner_id']) ?></b>
      <button class="secondary copy-button" type="button" data-copy-target="welcomeLearnerId">コピー</button>
    </div>
    <div class="credential-box">
      <span>復旧コード</span><b id="welcomeRecovery"><?= h((string)$credentials['recovery_code']) ?></b>
      <button class="secondary copy-button" type="button" data-copy-target="welcomeRecovery">コピー</button>
    </div>
    <p data-copy-status aria-live="polite" class="copy-status"></p>
    <p class="warning">復旧コードはこの画面で一度だけ表示されます。スクリーンショットを撮るか、パスワード管理アプリに保存してください。</p>
    <p class="reassure">もし保存しそびれても大丈夫です。講習担当までご連絡いただければ、受講者IDを確認のうえ再発行します。進捗が消えることはありません。</p>
    <a class="primary" style="display:block;text-align:center;text-decoration:none" href="dashboard.php">保存したのでマイコースへ</a>
  </div>
</section></main>
<?php portal_end(); ?>
