<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
start_secure_session();
$error = '';
$generated = '';

if (isset($_GET['logout'])) {
    unset($_SESSION['admin_authenticated']);
    redirect('admin.php');
}

if (empty($_SESSION['admin_authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && app_ready()) {
        require_csrf();
        $key = attempt_key('ADMIN');
        if (login_blocked($key)) {
            $error = '入力回数が上限に達しました。10分ほど待ってください。';
        } elseif (password_verify((string)($_POST['password'] ?? ''), (string)app_config()['admin_password_hash'])) {
            clear_login_failures($key);
            session_regenerate_id(true);
            $_SESSION['admin_authenticated'] = true;
            redirect('admin.php');
        } else {
            record_login_failure($key);
            $error = '管理パスワードが一致しません。';
        }
    }
    portal_head('管理者ログイン');
    ?>
    <main class="shell"><section class="auth-card admin-login"><div class="auth-card__head"><p class="eyebrow">ADMIN</p><h1>講座管理</h1><p class="lead">登録コードの発行と匿名受講状況の確認を行います。</p></div>
      <?php if (!app_ready()): ?><p class="setup-note">管理設定が完了していません。</p><?php endif; ?>
      <?php if ($error): ?><p class="alert"><?= h($error) ?></p><?php endif; ?>
      <form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><div class="field"><label for="password">管理パスワード</label><input id="password" name="password" type="password" required autocomplete="current-password"></div><button class="primary" type="submit" <?= app_ready() ? '' : 'disabled' ?>>管理画面へ</button></form>
    </section></main>
    <?php portal_end(); exit;
}

$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $action = (string)($_POST['action'] ?? '');
    if ($action === 'create_invite') {
        $available = array_column($pdo->query('SELECT slug FROM courses WHERE active = 1')->fetchAll(), 'slug');
        $selected = isset($_POST['courses']) && is_array($_POST['courses']) ? array_values(array_intersect($available, array_map('strval', $_POST['courses']))) : [];
        if (!$selected) {
            $error = '少なくとも1講座を選択してください。';
        } else {
            $generated = random_code('ENR', 2);
            $days = max(1, min(365, (int)($_POST['valid_days'] ?? 30)));
            $expires = (new DateTimeImmutable('now', new DateTimeZone('Asia/Tokyo')))->modify('+' . $days . ' days')->format(DATE_ATOM);
            $stmt = $pdo->prepare('INSERT INTO invite_codes (code_hash, code_hint, course_slugs, expires_at, created_at) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([token_hash($generated), substr($generated, 0, 8) . '••••', json_encode($selected), $expires, now_iso()]);
        }
    } elseif ($action === 'user_status') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $status = (string)($_POST['status'] ?? '') === 'active' ? 'active' : 'suspended';
        $pdo->prepare('UPDATE users SET status = ? WHERE id = ?')->execute([$status, $userId]);
    }
}
$courses = $pdo->query('SELECT * FROM courses ORDER BY sort_order')->fetchAll();
$users = $pdo->query('SELECT u.id, u.learner_id, u.status, u.created_at, u.last_login_at,
    GROUP_CONCAT(e.course_slug) AS courses,
    MAX(CASE WHEN p.course_slug = "aow" THEN p.completed_at END) AS aow_completed
    FROM users u LEFT JOIN enrollments e ON e.user_id = u.id
    LEFT JOIN course_progress p ON p.user_id = u.id
    GROUP BY u.id ORDER BY u.id DESC')->fetchAll();
$invites = $pdo->query('SELECT * FROM invite_codes ORDER BY id DESC LIMIT 30')->fetchAll();
portal_head('講座管理');
?>
<header class="portal-header"><div class="shell"><a class="brand" href="admin.php">三浦 海の学校｜講座管理</a><div class="header-actions"><a href="course.php?course=aow">AOW教材をすべて確認</a><a href="admin.php?logout=1">管理画面をログアウト</a></div></div></header>
<main class="dashboard shell">
  <div class="dashboard-top"><div><p class="eyebrow">LEARNING ADMIN</p><h1>匿名受講アカウント管理</h1><p class="lead">個人情報を保存せず、受講者IDと講座権限だけを管理します。</p></div></div>
  <?php if ($generated): ?><div class="generated-code">今回発行した初回登録コード（この画面でのみ表示）<b><?= h($generated) ?></b></div><?php endif; ?>
  <?php if ($error): ?><p class="alert" style="margin:0 0 20px"><?= h($error) ?></p><?php endif; ?>
  <section class="student-url-card" aria-labelledby="student-url-title"><div><p class="eyebrow">SEND TO STUDENTS</p><h2 id="student-url-title">お客様へ送る受講生サイト</h2><p>初回登録コードと一緒に、このURLをお客様へ送ってください。</p></div><div class="student-url-actions"><label for="studentSiteUrl">受講生サイトURL</label><div><input id="studentSiteUrl" type="text" value="https://miura-diving.com/aow-learning/" readonly><button type="button" data-copy-target="studentSiteUrl">URLをコピー</button><a href="https://miura-diving.com/aow-learning/" target="_blank" rel="noopener">サイトを開く ↗</a></div><p data-copy-status aria-live="polite"></p></div></section>
  <section class="admin-preview-card"><div><p class="eyebrow">CONTENT PREVIEW</p><h2>AOW教材の全内容を確認</h2><p>PPB・ナビゲーション・ナチュラリスト、全21問、修了画面まで管理者専用プレビューで確認できます。操作は受講者記録へ保存されません。</p></div><a href="course.php?course=aow">教材を開く →</a></section>
  <div class="admin-grid">
    <section class="panel"><h2>初回登録コードを発行</h2><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="create_invite"><div class="check-options">
      <?php foreach ($courses as $course): ?><label><input type="checkbox" name="courses[]" value="<?= h((string)$course['slug']) ?>" <?= (int)$course['active'] ? '' : 'disabled' ?>> <?= h((string)$course['title']) ?><?= (int)$course['active'] ? '' : '（準備中）' ?></label><?php endforeach; ?>
      </div><div class="field"><label for="valid_days">有効日数</label><input id="valid_days" name="valid_days" type="number" min="1" max="365" value="30"></div><button class="primary" type="submit">1回限りのコードを発行</button></form></section>
    <section class="panel"><h2>発行済みコード</h2><table><thead><tr><th>表示</th><th>講座</th><th>状態</th><th>期限</th></tr></thead><tbody><?php foreach ($invites as $invite): ?><tr><td><?= h((string)$invite['code_hint']) ?></td><td><?= h(implode(', ', json_decode((string)$invite['course_slugs'], true) ?: [])) ?></td><td><?= h((string)$invite['status']) ?></td><td><?= h(substr((string)$invite['expires_at'], 0, 10)) ?></td></tr><?php endforeach; ?></tbody></table></section>
  </div>
  <section class="panel" style="margin-top:20px"><h2>受講状況</h2><?php if (!$users): ?><p class="empty">まだ受講者はいません。</p><?php else: ?><table><thead><tr><th>受講者ID</th><th>講座</th><th>AOW</th><th>最終ログイン</th><th>状態</th></tr></thead><tbody>
    <?php foreach ($users as $row): ?><tr><td><b><?= h((string)$row['learner_id']) ?></b></td><td><?= h((string)($row['courses'] ?: '—')) ?></td><td><?= $row['aow_completed'] ? '修了 ' . h(substr((string)$row['aow_completed'], 0, 10)) : '学習中' ?></td><td><?= h($row['last_login_at'] ? substr((string)$row['last_login_at'], 0, 16) : '—') ?></td><td><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="user_status"><input type="hidden" name="user_id" value="<?= (int)$row['id'] ?>"><input type="hidden" name="status" value="<?= $row['status'] === 'active' ? 'suspended' : 'active' ?>"><button class="secondary" type="submit"><?= $row['status'] === 'active' ? '停止する' : '再開する' ?></button></form></td></tr><?php endforeach; ?>
  </tbody></table><?php endif; ?></section>
</main>
<?php portal_end(); ?>
