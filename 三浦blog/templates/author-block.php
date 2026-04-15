<?php
/**
 * 著者プロフィール コンポーネント（E-E-A-T対応）
 * -------------------------------------------------
 * 全ブログ記事の末尾で使用する再利用コンポーネント。
 *
 * 使用方法:
 *   $author      = miura_load_author($post_frontmatter['author']); // 'tetsuji-yoshida' などID
 *   $verification = $post_frontmatter['verification'] ?? null;     // 任意
 *   include __DIR__ . '/templates/author-block.php';
 *
 * 入力データ ($author):
 *   - id, name, name_kana, role, organization
 *   - credentials: string[]
 *   - experience: { years: int, summary: string }
 *   - bio: string
 *   - photo: string (URL)
 *   - profile_url: string
 *   - books: [{ title, url }]
 *   - social: { website, line, ... }
 *
 * 任意入力 ($verification):  海況・体験記事向けメタ情報
 *   - type: 'field-verified' | 'experience-based' | 'research-based'
 *   - checked_at: 'YYYY-MM-DD'  (現地確認日)
 *   - location: '三浦半島 城ヶ島'
 *   - note: '現地で実測したデータです'
 */

if (!isset($author) || !is_array($author)) {
    return;
}

$credentials = $author['credentials'] ?? [];
$books       = $author['books'] ?? [];
$experience  = $author['experience'] ?? [];
$profile_url = $author['profile_url'] ?? '#';
$photo       = $author['photo'] ?? '';
?>
<aside class="author-card" itemscope itemtype="https://schema.org/Person" aria-labelledby="author-card-heading">
  <h2 id="author-card-heading" class="author-card__heading">この記事を書いた人</h2>

  <?php if (!empty($verification)) : ?>
    <div class="author-card__verification author-card__verification--<?= htmlspecialchars($verification['type'] ?? 'experience-based') ?>">
      <?php
        $label = [
          'field-verified'   => '現地確認済み',
          'experience-based' => '体験ベースで執筆',
          'research-based'   => '取材・調査ベース',
        ][$verification['type'] ?? 'experience-based'] ?? '体験ベースで執筆';
      ?>
      <span class="author-card__verification-label"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($label) ?></span>
      <?php if (!empty($verification['checked_at'])) : ?>
        <span class="author-card__verification-date">
          現地確認日: <time datetime="<?= htmlspecialchars($verification['checked_at']) ?>"><?= htmlspecialchars($verification['checked_at']) ?></time>
        </span>
      <?php endif; ?>
      <?php if (!empty($verification['location'])) : ?>
        <span class="author-card__verification-location"><?= htmlspecialchars($verification['location']) ?></span>
      <?php endif; ?>
      <?php if (!empty($verification['note'])) : ?>
        <p class="author-card__verification-note"><?= htmlspecialchars($verification['note']) ?></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="author-card__body">
    <?php if ($photo) : ?>
      <img class="author-card__photo" src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($author['name']) ?>" itemprop="image" width="96" height="96" loading="lazy">
    <?php endif; ?>

    <div class="author-card__info">
      <p class="author-card__name">
        <strong itemprop="name"><?= htmlspecialchars($author['name']) ?></strong>
        <?php if (!empty($author['name_kana'])) : ?>
          <span class="author-card__kana">（<?= htmlspecialchars($author['name_kana']) ?>）</span>
        <?php endif; ?>
      </p>
      <p class="author-card__role" itemprop="jobTitle"><?= htmlspecialchars($author['role'] ?? '') ?></p>

      <?php if (!empty($author['bio'])) : ?>
        <p class="author-card__bio" itemprop="description"><?= nl2br(htmlspecialchars(trim($author['bio']))) ?></p>
      <?php endif; ?>

      <?php if (!empty($credentials)) : ?>
        <ul class="author-card__credentials">
          <?php foreach ($credentials as $cred) : ?>
            <li><i class="fa-solid fa-certificate"></i> <?= htmlspecialchars($cred) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php if (!empty($experience['summary'])) : ?>
        <p class="author-card__experience"><i class="fa-solid fa-water"></i> <?= htmlspecialchars($experience['summary']) ?></p>
      <?php endif; ?>

      <?php if (!empty($books)) : ?>
        <div class="author-card__books">
          <p class="author-card__books-label">関連書籍</p>
          <ul>
            <?php foreach ($books as $book) : ?>
              <li><a href="<?= htmlspecialchars($book['url']) ?>"><i class="fa-solid fa-book"></i> <?= htmlspecialchars($book['title']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <p class="author-card__more">
        <a href="<?= htmlspecialchars($profile_url) ?>" itemprop="url">プロフィール詳細を見る <i class="fa-solid fa-arrow-right"></i></a>
      </p>
    </div>
  </div>
</aside>
