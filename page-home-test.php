<?php
// ★Hero+Wow
/**
 * Template Name: Home Test Page v2
 * Test homepage for Miura Diving - Enhanced SEO & Accessibility
 */

get_header(); ?>

<main class="home-test" role="main">
    <!-- Hero Section -->
    <section class="hero" role="banner" aria-labelledby="hero-title">
        <ul class="hero-slides">
            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/image/umigaku-hero.png" alt="施設俯瞰"></li>
            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/image/owd-hero.png" alt="OWD講習"></li>
            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/image/boat-hero.png" alt="ボートダイブ"></li>
        </ul>
        <div class="hero-overlay"></div>
        <div class="hero-inner">
            <h1 id="hero-title">三浦 海の学校｜PADI5⭐IDC</h1>
            <p class="hero-lead">一般の方なら誰でも参加OK！<br>都心から60分、美しい三浦の海で本格ダイビング体験</p>
            <a class="btn-cta ripple" href="#cta">LINEで問い合わせ</a>
        </div>
    </section>

    <!-- Why Miura Section -->
    <section class="why-miura" aria-labelledby="why-title">
        <div class="container">
            <h2 id="why-title" class="section-title">なぜ三浦海の学校なのか</h2>
            <div class="why-grid" role="list">
                <!-- 都心60分 -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/misakiguchi.png" alt="">
                    <h3>都心から60分のアクセス</h3>
                    <p><strong>一般の方でも気軽に通える</strong>立地。平日・土日問わず、お仕事帰りや休日に手軽にダイビングを楽しめます。</p>
                </article>
                
                <!-- 専用プール完備 -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/pool-practice.png" alt="">
                    <h3>専用プール完備</h3>
                    <p><strong>誰でも安心して始められる</strong>環境。海に入る前にプールでしっかり練習できるので、泳ぎが苦手な方も安心です。</p>
                </article>
                
                <!-- 初心者歓迎 -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/owd-equipmetn.png" alt="">
                    <h3>初心者大歓迎</h3>
                    <p><strong>年齢・経験問わず誰でも参加OK</strong>。<span data-countup="1500">0</span>名以上の受講実績で安心サポート。</p>
                </article>
                
                <!-- 新カード① 少人数制 1:4 -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/pool.png" alt="">
                    <h3>少人数レッスン</h3>
                    <p>最大<strong>1 : 4</strong> の少人数制。経験豊富なインストラクターが、丁寧できめ細かなサポートを行います。</p>
                </article>
                
                <!-- 新カード② 多彩なプログラム -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/boat.png" alt="">
                    <h3>多彩なプログラム</h3>
                    <p>ファンダイブから <span lang="en">OWD</span> 初心者コース、プロ講習まで幅広く開催。目的に合ったコースを選べます。</p>
                </article>
                
                <!-- 新カード③ 海は目の前 -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/surface.png" alt="">
                    <h3>海は目の前</h3>
                    <p>専用プールから海までわずか数歩。器材を背負ったまま海洋実習へ直行できる絶好の導線です。</p>
                </article>
                
                <!-- 新カード④ 安全第一の指導体制 -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/padi.png" alt="">
                    <h3>安全第一の指導体制</h3>
                    <p>PADI安全基準を順守し、酸素キット・AEDを完備。万一に備えた安心サポート体制です。</p>
                </article>
                
                <!-- 新カード⑤ メンテナンスされたレンタル器材 -->
                <article class="why-card parallax wait" role="listitem">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/equipment.png" alt="メンテナンスされたレンタル器材">
                    <h3>メンテナンスされたレンタル器材</h3>
                    <p>定期点検済みの高品質レンタル器材を完備。いつでも安心してご利用いただけます。</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Activities Slider -->
    <section class="activities" aria-labelledby="activities-title">
        <div class="container">
            <h2 id="activities-title" class="section-title">マリンアクティビティ</h2>
            <div class="activities-slider" role="region" aria-label="マリンアクティビティスライダー" tabindex="0">
                <article class="activity-card">
                    <img src="<?php echo get_template_directory_uri(); ?>/image/sup.png" alt="SUP（スタンドアップパドル）体験" class="activity-image" width="350" height="200">
                    <h3>SUP（スタンドアップパドル）</h3>
                    <p><strong>誰でも簡単に始められる</strong>人気のマリンスポーツ。年齢・体力に関係なく、穏やかな海でリラックスしながら楽しめます。</p>
                    <a href="#cta" class="btn-secondary" aria-label="SUP体験の詳細・予約">詳細を見る</a>
                </article>
                <article class="activity-card">
                    <img src="<?php echo get_template_directory_uri(); ?>/image/snorkel.png" alt="シュノーケリング体験" class="activity-image" width="350" height="200">
                    <h3>シュノーケリング</h3>
                    <p><strong>泳げない方でも参加OK</strong>。海面から水中の美しい世界を覗き見る、ダイビングの入門に最適なアクティビティです。</p>
                    <a href="#cta" class="btn-secondary" aria-label="シュノーケリング体験の詳細・予約">詳細を見る</a>
                </article>
                <article class="activity-card">
                    <img src="<?php echo get_template_directory_uri(); ?>/image/seakayac.png" alt="シーカヤック体験" class="activity-image" width="350" height="200">
                    <h3>シーカヤック</h3>
                    <p><strong>初心者から楽しめる</strong>三浦半島の美しい海岸線ツアー。経験豊富なガイドが同行するので安心です。</p>
                    <a href="#cta" class="btn-secondary" aria-label="カヤック体験の詳細・予約">詳細を見る</a>
                </article>
            </div>
        </div>
    </section>

    <!-- Course Path -->
    <section class="course-path" aria-labelledby="course-title">
        <div class="container">
            <h2 id="course-title" class="section-title">ダイビングコースの流れ</h2>
            <p class="section-subtitle course-subtitle"><strong>一般の方でもステップアップできる</strong>安心のコース設計</p>
            <ol class="timeline" role="list">
                <li class="timeline-item" role="listitem">
                    <div class="timeline-number" aria-hidden="true">1</div>
                    <div class="timeline-content">
                        <h3>オープンウォーターダイバーコース</h3>
                        <p><strong>誰でも取得可能</strong>なライセンス取得の第一歩。18mまでの深度でダイビングができるようになります。</p>
                    </div>
                </li>
                <li class="timeline-item" role="listitem">
                    <div class="timeline-number" aria-hidden="true">2</div>
                    <div class="timeline-content">
                        <h3>アドバンスドオープンウォーターダイバーコース</h3>
                        <p><strong>一般ダイバーも無理なく挑戦</strong>。30mまでの深度でより多様な海洋環境を体験します。</p>
                    </div>
                </li>
                <li class="timeline-item" role="listitem">
                    <div class="timeline-number" aria-hidden="true">3</div>
                    <div class="timeline-content">
                        <h3>スペシャルティダイバーコース</h3>
                        <p><strong>趣味に応じて選択</strong>。ナビゲーション、ナイトダイビング、ディープなど様々な専門技術を習得。</p>
                    </div>
                </li>
                <li class="timeline-item" role="listitem">
                    <div class="timeline-number" aria-hidden="true">4</div>
                    <div class="timeline-content">
                        <h3>レスキューダイバーコース</h3>
                        <p><strong>経験を積んだ一般ダイバーの目標</strong>。他のダイバーの安全をサポートする技術を身につけます。</p>
                    </div>
                </li>
                <li class="timeline-item" role="listitem">
                    <div class="timeline-number" aria-hidden="true">5</div>
                    <div class="timeline-content">
                        <h3>プロコース</h3>
                        <p><strong>プロダイバーへの道</strong>。ダイブマスター、インストラクターなど、プロフェッショナルなダイバーを目指します。</p>
                    </div>
                </li>
            </ol>
            <div class="course-cta">
                <a href="/diving-license/" class="btn-primary course-details-btn">ライセンス講習の詳細をみる</a>
            </div>
        </div>
    </section>

    <!-- OWD / AOW Comparison -->
    <section class="course-comparison" aria-labelledby="comparison-title">
        <div class="container">
            <h2 id="comparison-title" class="section-title">コース比較</h2>
            <p class="section-subtitle"><strong>一般の方向けの人気コース</strong>を比較</p>
            <div class="comparison-tabs" role="tablist">
                <input type="radio" name="course-tab" id="owd-tab" checked aria-describedby="owd-desc">
                <input type="radio" name="course-tab" id="aow-tab" aria-describedby="aow-desc">
                
                <div class="tab-labels">
                    <label for="owd-tab" class="tab-label" role="tab" aria-selected="true" aria-controls="owd-content" tabindex="0">
                        オープンウォーター
                    </label>
                    <label for="aow-tab" class="tab-label" role="tab" aria-selected="false" aria-controls="aow-content" tabindex="-1">
                        アドバンス
                    </label>
                </div>
                
                <div class="tab-contents">
                    <div class="tab-content" id="owd-content" role="tabpanel" aria-labelledby="owd-tab">
                        <div class="course-details">
                            <div class="course-price">
                                <span class="price-label">料金</span>
                                <span class="price-value">¥53,900</span>
                                <small>レンタル器材：1日5,500円</small>
                            </div>
                            <div class="course-features">
                                <h3>特徴</h3>
                                <ul>
                                    <li><strong>初心者向け</strong> - 泳げない方もOK</li>
                                    <li>3日間の講習</li>
                                    <li>最大深度18m</li>
                                    <li>学科講習 + 実技講習</li>
                                    <li>認定証発行</li>
                                    <li>レンタル器材：1日5,500円（別途）</li>
                                </ul>
                            </div>
                            <a href="#cta" class="btn-primary" id="owd-desc">OWDコースを申し込む</a>
                        </div>
                    </div>
                    <div class="tab-content" id="aow-content" role="tabpanel" aria-labelledby="aow-tab">
                        <div class="course-details">
                            <div class="course-price">
                                <span class="price-label">料金</span>
                                <span class="price-value">¥53,900</span>
                                <small>レンタル器材：1日5,500円</small>
                            </div>
                            <div class="course-features">
                                <h3>特徴</h3>
                                <ul>
                                    <li><strong>一般ダイバー向け</strong> - OWD取得者対象</li>
                                    <li>2日間の講習</li>
                                    <li>最大深度30m</li>
                                    <li>5つのアドベンチャーダイブ</li>
                                    <li>ナビゲーション・深度ダイブ必須</li>
                                    <li>レンタル器材：1日5,500円（別途）</li>
                                </ul>
                            </div>
                            <a href="#cta" class="btn-primary" id="aow-desc">AOWコースを申し込む</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instructor Bio -->
    <section class="instructor-bio" aria-labelledby="instructor-title">
        <div class="container">
            <h2 id="instructor-title" class="section-title">インストラクター紹介</h2>
            <div class="bio-content">
                <div class="bio-image">
                    <figure class="instructor">
                        <img src="<?php echo get_template_directory_uri(); ?>/image/instructor.png"
                             width="600" height="600" loading="lazy"
                             alt="PADIコースディレクター 吉田哲司">
                    </figure>
                </div>
                <div class="bio-text">
                    <h3 class="inst-name">吉田 哲司</h3>
                    <p class="inst-title">PADIコースディレクター / AquaBit LAB代表</p>
                    
                    <p class="inst-text">
                        1997年からダイビングプロフェッショナルとして活動を続け、<strong>延べ1,500名以上</strong>の一般ダイバーを育成してきたベテランインストラクター。<br>
                        安全管理とわかりやすい指導に定評があり、PADI最高位資格であるコースディレクターとして多くのインストラクター候補生も直接指導しています。<br>
                        海洋生物の知識と最新テクノロジーを融合した独自メソッドで、初心者からプロを目指す方まで幅広くサポートします。
                    </p>
                    
                    <ul class="inst-badges">
                        <li>PADIコースディレクター</li>
                        <li>Tec Trimix インストラクタートレーナー</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- AquaBit LAB -->
    <section class="aquabit-lab" aria-labelledby="lab-title">
        <div class="container">
            <h2 id="lab-title" class="section-title">AquaBit LAB</h2>
            <p class="section-subtitle"><strong>一般の方でも簡単に使える</strong>最新テクノロジーで海洋体験を革新</p>
            <div class="lab-grid" role="list">
                <article class="lab-card" role="listitem">
                    <div class="lab-icon" aria-hidden="true">
                        <svg viewBox="0 0 100 100" class="lab-svg">
                            <rect x="20" y="30" width="60" height="40" fill="#4A90E2" opacity="0.8"/>
                            <rect x="25" y="35" width="50" height="30" fill="#357ABD" opacity="0.6"/>
                        </svg>
                    </div>
                    <h3>NFT技術の活用</h3>
                    <p><strong>誰でも参加できる</strong>ダイビング体験をNFTで記録・認証。デジタル証明書として永続保存。</p>
                </article>
                <article class="lab-card" role="listitem">
                    <div class="lab-icon" aria-hidden="true">
                        <svg viewBox="0 0 100 100" class="lab-svg">
                            <circle cx="50" cy="50" r="25" fill="#50C878" opacity="0.8"/>
                            <circle cx="50" cy="50" r="15" fill="#228B22" opacity="0.6"/>
                        </svg>
                    </div>
                    <h3>WEB3体験</h3>
                    <p><strong>一般の方でも簡単に</strong>。ブロックチェーン技術でダイビングコミュニティに参加。</p>
                </article>
                <article class="lab-card" role="listitem">
                    <figure class="lab-icon">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/icon-ai.svg"
                             width="72" height="72" alt="">
                    </figure>
                    <h3>AI活用サポート</h3>
                    <p>最新AIツールの“使える”実践術を提案。仕事・学習・趣味に応用。</p>
                </article>
                <article class="lab-card" role="listitem">
                    <figure class="lab-icon">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/icon-learning.svg"
                             width="72" height="72" alt="">
                    </figure>
                    <h3>Udemy講座 &amp; 書籍</h3>
                    <p>海×テクノロジーを学べる動画講座と専門書籍を継続リリース。</p>
                </article>
            </div>
            <div class="lab-cta">
                <a href="#cta" class="btn-primary">AquaBit LABについて詳しく見る</a>
            </div>
        </div>
    </section>

    <!-- Latest Blog -->
    <section class="latest-blog" aria-labelledby="blog-title">
        <div class="container">
            <h2 id="blog-title" class="section-title">最新ブログ</h2>
            <div class="blog-grid" role="list">
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 3,
                    'post_status' => 'publish'
                ));
                
                if (!empty($recent_posts)) {
                    foreach($recent_posts as $post) : ?>
                        <article class="blog-card" role="listitem">
                            <figure class="ph blog-thumb" aria-label="ブログ記事のダミーサムネイル"></figure>
                            <div class="blog-content">
                                <h3><?php echo esc_html($post['post_title']); ?></h3>
                                <p class="blog-excerpt"><?php echo wp_trim_words($post['post_content'], 20); ?></p>
                                <div class="blog-meta">
                                    <time datetime="<?php echo $post['post_date']; ?>">
                                        <?php echo date('Y.m.d', strtotime($post['post_date'])); ?>
                                    </time>
                                </div>
                                <a href="<?php echo get_permalink($post['ID']); ?>" class="blog-link">続きを読む</a>
                            </div>
                        </article>
                    <?php endforeach;
                } else {
                    // Placeholder articles if no posts exist
                    for ($i = 1; $i <= 3; $i++) : ?>
                        <article class="blog-card" role="listitem">
                            <figure class="ph blog-thumb" aria-label="ブログ記事のダミーサムネイル"></figure>
                            <div class="blog-content">
                                <h3>一般の方向けダイビング情報 <?php echo $i; ?></h3>
                                <p class="blog-excerpt">初心者でも安心してダイビングを始められる情報をお届けします。基本的な器材の使い方から安全対策まで...</p>
                                <div class="blog-meta">
                                    <time datetime="2024-01-<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>">2024.01.<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></time>
                                </div>
                                <a href="#cta" class="blog-link">続きを読む</a>
                            </div>
                        </article>
                    <?php endfor;
                } ?>
            </div>
        </div>
    </section>

    <!-- Global CTA -->
    <section class="global-cta" id="cta" aria-labelledby="cta-title">
        <div class="container">
            <div class="cta-content">
                <h2 id="cta-title">海の世界への第一歩を踏み出そう</h2>
                <p><strong>一般の方なら誰でも参加OK！</strong><br>三浦海の学校で、あなたの海洋体験を始めませんか？</p>
                <div class="cta-buttons">
                    <a href="tel:046-880-0835" class="btn-cta" aria-label="電話で問い合わせ">
                        <span aria-hidden="true">📞</span> 046-880-0835
                    </a>
                    <a href="mailto:info@miura-diving.com" class="btn-cta" aria-label="メールで問い合わせ">
                        <span aria-hidden="true">✉️</span> お問い合わせ
                    </a>
                    <a href="https://lin.ee/kK3d5p2" class="btn-cta btn-cta-line" aria-label="LINEで問い合わせ">
                        <span aria-hidden="true">💬</span> LINE相談
                    </a>
                </div>
                
                <ul class="cta-info">
                    <li>〒238-0224 神奈川県三浦市三崎町諸磯1621</li>
                    <li>有料駐車場完備</li>
                    <li>電車：京急三崎口駅 → <ruby>屋敷倉<rt>やしきくら</rt></ruby>行バス「屋敷倉」下車 徒歩1分</li>
                    <li><a href="https://aquabit-lab.com" target="_blank" rel="noopener">🔗 AquaBit LAB</a></li>
                    <li><a href="https://tj-music.com" target="_blank" rel="noopener">🎵 TJ Music</a></li>
                </ul>
                <p class="cta-note">
                    <small>営業時間：9:00〜16:00｜不定休</small>
                </p>
            </div>
        </div>
    </section>
</main>

<!-- ========== SNS FEEDS ========== -->
<section class="sns-section" style="padding:4rem 1rem 6rem;background:#f7f9fc;">
  <div style="max-width:1200px;margin:0 auto;">
    <h2 style="text-align:center;margin-bottom:3rem;font-size:2rem;color:#2c3e50;font-weight:700;">SNSで最新情報をチェック</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:2rem;justify-items:center;">
      
      <!-- X Timeline -->
      <div style="width:100%;max-width:350px;">
        <a class="twitter-timeline" 
           data-height="500" 
           data-width="350"
           data-theme="light"
           data-chrome="noheader nofooter noborders transparent"
           href="https://twitter.com/mumigaku?ref_src=twsrc%5Etfw">
           Tweets by mumigaku
        </a>
      </div>

      <!-- Facebook Page -->
      <div style="width:100%;max-width:350px;">
        <div id="fb-root"></div>
        <div class="fb-page" 
             data-href="https://www.facebook.com/miuraumigaku/" 
             data-tabs="timeline"
             data-width="350" 
             data-height="500" 
             data-small-header="true" 
             data-adapt-container-width="true"
             data-hide-cover="false" 
             data-show-facepile="false">
          <blockquote cite="https://www.facebook.com/miuraumigaku/" class="fb-xfbml-parse-ignore">
            <a href="https://www.facebook.com/miuraumigaku/">三浦 海の学校</a>
          </blockquote>
        </div>
      </div>

      <!-- Instagram -->
      <div style="width:100%;max-width:350px;">
        <div style="background:white;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
          <!-- Instagram Embed using Elfsight or alternative -->
          <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/tj_official_umigaku/" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:8px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:350px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
            <div style="padding:16px;">
              <a href="https://www.instagram.com/tj_official_umigaku/" style="background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank">
                <div style="display: flex; flex-direction: row; align-items: center;">
                  <div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div>
                  <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                    <div style="background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div>
                    <div style="background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div>
                  </div>
                </div>
                <div style="padding: 19% 0;"></div>
                <div style="display:block; height:50px; margin:0 auto 12px; width:50px;">
                  <svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <g transform="translate(-511.000000, -20.000000)" fill="#000000">
                        <g>
                          <path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631"></path>
                        </g>
                      </g>
                    </g>
                  </svg>
                </div>
                <div style="padding-top: 8px;">
                  <div style="color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">tj_official_umigaku のInstagramを見る</div>
                </div>
              </a>
            </div>
          </blockquote>
        </div>
      </div>
      
    </div>
  </div>
</section>

<!-- SNS Scripts -->
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v18.0&appId=YOUR_APP_ID" nonce="YOUR_NONCE"></script>
<script async src="//www.instagram.com/embed.js"></script>

<style>
/* SNS Section Responsive Styles */
@media (max-width: 768px) {
  .sns-section {
    padding: 3rem 0.5rem 4rem !important;
  }
  .sns-section h2 {
    font-size: 1.5rem !important;
    margin-bottom: 2rem !important;
  }
  .sns-section > div > div {
    grid-template-columns: 1fr !important;
    gap: 1.5rem !important;
  }
  .sns-section iframe,
  .sns-section .twitter-timeline,
  .sns-section .fb-page {
    max-width: 100% !important;
  }
}

@media (max-width: 480px) {
  .sns-section {
    padding: 2rem 0.5rem 3rem !important;
  }
  .sns-section h2 {
    font-size: 1.3rem !important;
  }
}

/* Instagram widget styling */
.snapwidget-widget {
  border-radius: 8px;
}

/* Facebook widget styling */
.fb-page {
  background: white;
  border-radius: 8px;
  overflow: hidden;
}

/* Twitter widget styling */
.twitter-timeline {
  border-radius: 8px !important;
}
</style>

<?php get_footer(); ?>