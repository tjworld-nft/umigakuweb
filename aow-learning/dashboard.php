<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$user = require_user();
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $invite = invite_record((string)($_POST['invite_code'] ?? ''));
    if (!$invite) {
        $error = '講座追加コードが無効、使用済み、または期限切れです。';
    } else {
        $pdo = db();
        try {
            $pdo->beginTransaction();
            redeem_invite($pdo, $invite, (int)$user['id']);
            $pdo->commit();
            $message = '新しい講座を追加しました。';
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = '講座を追加できませんでした。';
        }
    }
}
$courses = enrolled_courses((int)$user['id']);
$allCourses = db()->query('SELECT * FROM courses ORDER BY sort_order')->fetchAll();
$enrolledSlugs = array_column($courses, 'slug');
portal_head('マイコース');
?>
<header class="portal-header"><div class="shell"><a class="brand" href="dashboard.php">三浦 海の学校</a><div class="header-actions"><span><?= h((string)$user['learner_id']) ?></span><a href="logout.php">ログアウト</a></div></div></header>
<main class="dashboard shell">
  <div class="dashboard-top"><div><p class="eyebrow">MY COURSES</p><h1>海へ行く前の、学びの場所。</h1><p class="lead">途中の進捗はサーバーに保存され、別の端末からも続けられます。</p></div><div class="identity"><span>LEARNER ID</span><b><?= h((string)$user['learner_id']) ?></b></div></div>
  <?php if ($message): ?><p class="alert alert--success" style="margin:0 0 20px"><?= h($message) ?></p><?php endif; ?>
  <?php if ($error): ?><p class="alert" style="margin:0 0 20px"><?= h($error) ?></p><?php endif; ?>
  <section class="course-list" aria-label="受講できる講座">
    <?php foreach ($allCourses as $course):
      $enrolled = in_array($course['slug'], $enrolledSlugs, true);
      $record = null;
      foreach ($courses as $candidate) if ($candidate['slug'] === $course['slug']) $record = $candidate;
      $state = $record && $record['state_json'] ? json_decode((string)$record['state_json'], true) : [];
      $completedModules = 0;
      if (isset($state['modules']) && is_array($state['modules'])) foreach ($state['modules'] as $module) if (!empty($module['complete'])) $completedModules++;
      $percent = $record && $record['completion_code'] ? 100 : (int)round(($completedModules / 3) * 100);
    ?>
    <article class="course-card <?= $enrolled ? '' : 'is-locked' ?>">
      <span><?= $enrolled ? 'AVAILABLE' : ((int)$course['active'] ? 'CODE REQUIRED' : 'COMING LATER') ?></span>
      <h2><?= h((string)$course['title']) ?></h2><p><?= h((string)$course['description']) ?></p>
      <?php if ($enrolled): ?><div class="progress-bar"><i style="width:<?= $percent ?>%"></i></div><?php endif; ?>
      <div class="course-card__footer">
        <?php if ($enrolled && $course['slug'] === 'aow'): ?><b><?= $record && $record['completion_code'] ? '修了' : $percent . '%完了' ?></b><a href="course.php?course=aow"><?= $percent ? '続きから' : '学習開始' ?> →</a>
        <?php elseif ($enrolled): ?><b>教材準備中</b>
        <?php else: ?><b><?= (int)$course['active'] ? '追加コードで開放' : '準備中' ?></b><?php endif; ?>
      </div>
    </article>
    <?php endforeach; ?>
  </section>
  <section class="redeem-panel"><div><h2>講座を追加する</h2><p>追加講座のお申し込み後に受け取ったコードを入力してください。</p></div><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input name="invite_code" required placeholder="CRS-XXXX-XXXX"><button class="primary" type="submit">追加</button></form></section>
</main>
<?php portal_end(); ?>
