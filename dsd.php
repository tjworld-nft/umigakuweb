<?php
/*
Template Name: DSD Diving
*/

// Google FontsとFont Awesomeの読み込み
function add_try_diving_fonts() {
  if (is_page_template('try-diving.php') || is_page_template('dsd-diving.php')) {
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=M+PLUS+Rounded+1c:wght@400;500;700&display=swap">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">';
  }
}
add_action('wp_head', 'add_try_diving_fonts');

// SEO対策メタタグ
function add_try_diving_meta_tags() {
  if (is_page_template('try-diving.php') || is_page_template('dsd-diving.php')) {
    echo '<meta name="description" content="神奈川・三浦で初めての体験ダイビングなら三浦海の学校。専用プール完備で器材レンタル込み。日帰りOK、女性やおひとり様にも人気です。インストラクター完全同行で安全第一の体験ダイビング。">';
    echo '<meta name="keywords" content="体験ダイビング, 初心者ダイビング, 神奈川ダイビング, 三浦ダイビング, ダイビング初めて, 日帰りダイビング, 女性向けダイビング, 三浦ダイビングスクール, 少人数制ダイビング, プール付きダイビング">';
    echo '<meta property="og:title" content="神奈川・三浦で体験ダイビング｜初心者に優しい少人数制｜三浦海の学校">';
    echo '<meta property="og:description" content="神奈川・三浦で体験ダイビングするなら三浦海の学校。専用プール完備で初めてでも安心。90分で行ける都内近郊の体験ダイビングスポット。">';
    echo '<meta property="og:type" content="website">';
    echo '<meta property="og:url" content="' . get_permalink() . '">';
    echo '<meta property="og:image" content="' . get_stylesheet_directory_uri() . '/images/og-diving.jpg">';
    echo '<meta name="twitter:card" content="summary_large_image">';
    echo '<title>神奈川・三浦で体験ダイビング｜初心者に優しい少人数制｜三浦海の学校</title>';
    
    // 構造化データの追加
    echo '<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SportsActivityLocation",
      "name": "三浦海の学校 - 体験ダイビング",
      "description": "神奈川県三浦市で体験ダイビングを提供しているダイビングスクール。初心者向けの安全な環境で、専用プール完備。器材レンタル込みで手ぶらでOK。",
      "url": "' . get_permalink() . '",
      "telephone": "012-345-6789",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "三浦市",
        "addressRegion": "神奈川県",
        "addressCountry": "JP"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "35.1401",
        "longitude": "139.6208"
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday", "Tuesday", "Thursday", "Friday", "Saturday", "Sunday"
        ],
        "opens": "09:00",
        "closes": "18:00"
      },
      "priceRange": "¥14,800〜",
      "image": "' . get_stylesheet_directory_uri() . '/images/og-diving.jpg",
      "offers": {
        "@type": "Offer",
        "price": "14800",
        "priceCurrency": "JPY",
        "name": "体験ダイビング プラン",
        "description": "体験ダイビング1回、プール練習、専任インストラクター、器材レンタル一式、保険料、ログブック付き"
      }
    }
    </script>';
  }
}
add_action('wp_head', 'add_try_diving_meta_tags');

// ページ内リンクのスムーススクロールとインタラクティブ要素
function add_diving_smooth_scroll() {
  if (is_page_template('try-diving.php') || is_page_template('dsd-diving.php')) {
  ?>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // ページ内リンクのスムーススクロール
    const pageLinks = document.querySelectorAll('a[href^="#"]');
    pageLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });
    
    // テスティモニアルのスライダー
    let currentTestimonial = 0;
    const testimonials = document.querySelectorAll('.testimonial-item');
    const testimonialCount = testimonials.length;
    
    if (testimonialCount > 1) {
      setInterval(() => {
        testimonials[currentTestimonial].style.opacity = '0';
        
        setTimeout(() => {
          testimonials[currentTestimonial].style.display = 'none';
          currentTestimonial = (currentTestimonial + 1) % testimonialCount;
          testimonials[currentTestimonial].style.display = 'block';
          
          setTimeout(() => {
            testimonials[currentTestimonial].style.opacity = '1';
          }, 50);
        }, 500);
      }, 8000);
    }
  });
  </script>
  <?php
  }
}
add_action('wp_footer', 'add_diving_smooth_scroll');

get_header();
?>

<main id="main-content" class="diving-experience-page">
  <!-- ヒーローセクション -->
  <section class="page-header">
    <div class="inner">
      <div class="header-content">
        <h1 class="page-title">はじめての体験ダイビング</h1>
        <p class="page-subtitle">海の世界へ、やさしく一歩</p>
        <div class="cta-button-container">
          <a href="#reserve" class="primary-button pulse-animation"><i class="fas fa-calendar-alt mr-2"></i>今すぐ予約する</a>
        </div>
      </div>
      <div class="hero-image-container">
        <img src="https://miura-diving.com/wp-content/uploads/DSDヘッダー.png" alt="三浦の美しい海で体験ダイビングを楽しむ参加者" class="hero-image" />
        <div class="overlay-text">
          <span class="highlight-text"><i class="fas fa-heart"></i>初心者歓迎</span>
          <span class="highlight-text"><i class="fas fa-clock"></i>日帰りOK</span>
          <span class="highlight-text"><i class="fas fa-swimming-pool"></i>専用プール完備</span>
        </div>
      </div>
    </div>
  </section>

  <!-- イントロセクション -->
<section class="section intro" id="intro">
  <div class="inner">
    <div class="section-header">
      <h2 class="section-title">ライセンスがなくても大丈夫！</h2>
      <div class="section-subtitle">初めてでも安心の体験ダイビング</div>
    </div>
    <div class="content-centered">
      <div class="text-content">
        <p>
          「ダイビングに興味はあるけれど、ちょっと不安…」<br>
          「海の中はどんな世界なんだろう？」<br>
          「泳ぎが得意ではないけど大丈夫？」
        </p>
        <p>
          そんなあなたにぴったりのプログラムが<span class="highlight">体験ダイビング</span>です。
          三浦海の学校では、<strong>専用プール完備＆インストラクター完全同行</strong>で、<strong>初めてでも安心して楽しめる</strong>環境を整えています。
        </p>
        <p>
          泳げなくても大丈夫！水中での呼吸方法からしっかりとレクチャーします。三浦の澄んだ海で、カラフルな魚たちや海藻の森など、豊かな海中生物との出会いを体験してみませんか？
        </p>
        <div class="intro-points">
          <div class="point">
            <i class="fas fa-shield-alt"></i>
            <span>安全第一</span>
          </div>
          <div class="point">
            <i class="fas fa-child"></i>
            <span>初心者歓迎</span>
          </div>
          <div class="point">
            <i class="fas fa-user-friends"></i>
            <span>少人数制なので安心</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <!-- 魅力セクション -->
  <section class="section features" id="features">
    <div class="inner">
      <div class="section-header">
        <h2 class="section-title">三浦海の学校の体験ダイビング<span class="accent-color">5</span>つの魅力</h2>
      </div>
      <div class="feature-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <img src="https://miura-diving.com/wp-content/uploads/1-16.png" alt="アクセス" />
          </div>
          <div class="feature-badge">Point 1</div>
          <h3 class="feature-title">抜群のアクセス</h3>
          <p class="feature-desc"><strong>都内から約90分</strong>！日帰りでも十分に海の世界を満喫できます。京急三崎口駅からは無料送迎サービスもご用意しています。</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <img src="https://miura-diving.com/wp-content/uploads/2-17.png" alt="プール" />
          </div>
          <div class="feature-badge">Point 2</div>
          <h3 class="feature-title">専用プール完備</h3>
          <p class="feature-desc"><strong>海に入る前に専用プールで練習</strong>できるので、初めての方も安心です。基本的な呼吸法や水中での動き方をしっかり学べます。</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <img src="https://miura-diving.com/wp-content/uploads/3-16.png" alt="海" />
          </div>
          <div class="feature-badge">Point 3</div>
          <h3 class="feature-title">穏やかな海域</h3>
          <p class="feature-desc"><strong>最大水深6〜7mの穏やかな湾内</strong>で体験ダイビングを行います。透明度も高く、カジキや根魚、ソフトコーラルなど三浦ならではの海の生き物に会えるチャンスも！</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <img src="https://miura-diving.com/wp-content/uploads/4-10.png" alt="女性" />
          </div>
          <div class="feature-badge">Point 4</div>
          <h3 class="feature-title">女性にも安心</h3>
          <p class="feature-desc"><strong>女性インストラクター対応可能</strong>。清潔な更衣室・シャワールームも完備しており、女性の方も安心してご参加いただけます。</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <img src="https://miura-diving.com/wp-content/uploads/5-3.png" alt="少人数制" />
          </div>
          <div class="feature-badge">Point 5</div>
          <h3 class="feature-title">少人数制</h3>
          <p class="feature-desc"><strong>インストラクター1名につき最大2〜4名まで</strong>の少人数制で安全を確保。おひとり様参加も大歓迎です！</p>
        </div>
      </div>
      <div class="testimonials">
        <h3 class="testimonial-heading">参加者の声</h3>
        <div class="testimonial-slider">
          <div class="testimonial-item">
            <div class="testimonial-content">
              <p><i class="fas fa-quote-left mr-2"></i>ダイビングは難しそうと思っていましたが、丁寧に教えていただいたので安心して楽しめました。海の中は想像以上に美しく、感動しました！<i class="fas fa-quote-right ml-2"></i></p>
            </div>
            <div class="testimonial-author">東京都 K.S様（30代・女性）<i class="fas fa-female ml-2" style="color: #ff9f7f;"></i></div>
          </div>
          <div class="testimonial-item">
            <div class="testimonial-content">
              <p><i class="fas fa-quote-left mr-2"></i>泳ぎが苦手でしたが、インストラクターさんのサポートで問題なく体験できました。魚たちとの触れ合いは一生の思い出です。<i class="fas fa-quote-right ml-2"></i></p>
            </div>
            <div class="testimonial-author">神奈川県 T.N様（40代・男性）<i class="fas fa-male ml-2" style="color: #5e72e4;"></i></div>
          </div>
        </div>
      </div>
    </div>
  </section>

<!-- 全幅画像セクション -->
<section class="fullwidth-image">
  <img src="https://miura-diving.com/wp-content/uploads/DSDヘッダー-1.png" alt="三浦の美しい海中世界" class="fullwidth-img">
  <div class="image-overlay">
    <h2>忘れられない海中体験があなたを待っています</h2>
  </div>
</section>

  <!-- 当日の流れセクション -->
  <section class="section schedule" id="schedule">
    <div class="inner">
      <div class="section-header">
        <h2 class="section-title">当日の流れ</h2>
        <p class="section-subtitle">約4時間で海中世界を体験</p>
      </div>
      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-number">STEP1</div>
          <div class="timeline-content">
            <div class="timeline-icon">
              <img src="https://miura-diving.com/wp-content/uploads/1-17.png" alt="受付" />
            </div>
            <h3 class="timeline-title">集合・受付</h3>
            <p class="timeline-desc">京急三崎口駅から無料送迎あり（要事前予約）。スタッフが明るく出迎えます。必要書類の記入を行います。</p>
            <div class="timeline-time">所要時間：約20分</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-number">STEP2</div>
          <div class="timeline-content">
            <div class="timeline-icon">
              <img src="https://miura-diving.com/wp-content/uploads/2-18.png" alt="説明" />
            </div>
            <h3 class="timeline-title">カウンセリング・説明</h3>
            <p class="timeline-desc">ダイビングの基本や安全についての説明。不安なことや質問があれば何でもお聞きください。</p>
            <div class="timeline-time">所要時間：約30分</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-number">STEP3</div>
          <div class="timeline-content">
            <div class="timeline-icon">
              <img src="https://miura-diving.com/wp-content/uploads/4-11.png" alt="プール練習" />
            </div>
            <h3 class="timeline-title">プールで練習</h3>
            <p class="timeline-desc">専用プールで呼吸法や耳抜き、基本動作を練習。水中での呼吸に慣れることが目的です。</p>
            <div class="timeline-time">所要時間：約30〜40分</div>
          </div>
        </div>
        <div class="timeline-item highlight-step">
          <div class="timeline-number">STEP4</div>
          <div class="timeline-content">
            <div class="timeline-icon">
              <img src="https://miura-diving.com/wp-content/uploads/3-17.png" alt="ダイビング" />
            </div>
            <h3 class="timeline-title">海でダイビング体験</h3>
            <p class="timeline-desc">いよいよ海へ！インストラクターと一緒に水中世界を探検します。カラフルな魚たちや海の生き物との出会いをお楽しみください。</p>
            <div class="timeline-time">所要時間：約40分</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-number">STEP5</div>
          <div class="timeline-content">
            <div class="timeline-icon">
              <img src="https://miura-diving.com/wp-content/uploads/6-2.png" alt="シャワー" />
            </div>
            <h3 class="timeline-title">シャワー・着替え</h3>
            <p class="timeline-desc">温水シャワー完備。シャンプー、トリートメント、ボディソープの貸出もあります。</p>
            <div class="timeline-time">所要時間：約20分</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-number">STEP6</div>
          <div class="timeline-content">
            <div class="timeline-icon">
              <img src="https://miura-diving.com/wp-content/uploads/5-4.png" alt="ログ付け" />
            </div>
            <h3 class="timeline-title">ログ付け・解散</h3>
            <p class="timeline-desc">体験の記録を専用のログブックに記入します。記念写真サービスも！（水中写真は別途オプション）</p>
            <div class="timeline-time">所要時間：約20分</div>
          </div>
        </div>
      </div>
      <div class="schedule-note">
        <p>※天候や海況によりスケジュールが変更になる場合があります。</p>
        <p>※所要時間の目安：全行程で約3〜4時間</p>
      </div>
    </div>
  </section>

  <!-- 料金セクション -->
  <section class="section price" id="price">
    <div class="inner">
      <div class="section-header">
        <h2 class="section-title">料金プラン</h2>
        <p class="section-subtitle">明朗会計で安心</p>
      </div>
      <div class="price-container">
        <div class="price-box recommended">
          <div class="recommended-badge">スタンダード</div>
          <div class="price-header">
            <h3 class="price-title">体験ダイビングプラン</h3>
            <div class="price-value">14,800<span class="price-unit">円（税込）</span></div>
          </div>
          <div class="price-content">
            <ul class="price-features">
              <li>体験ダイビング1回（約40分）</li>
              <li>プール練習</li>
              <li>専任インストラクター</li>
              <li>器材レンタル一式</li>
              <li>保険料</li>
            </ul>
            <div class="price-note">※初めての方も安心！全て込みの料金です</div>
          </div>
          <a href="#reserve" class="price-button primary">このプランで予約する</a>
        </div>
        <div class="price-box">
          <div class="price-header">
            <h3 class="price-title">オプション</h3>
          </div>
          <div class="price-content">
            <ul class="option-list">
              <li><span class="option-name">追加ダイビング（1本）</span><span class="option-price">8,800円</span></li>
            </ul>
          </div>
        </div>
        <div class="price-box">
          <div class="price-header">
            <h3 class="price-title">グループ割引</h3>
          </div>
          <div class="price-content">
            <ul class="discount-list">
              <li><span class="discount-name">4名以上</span><span class="discount-value">5%OFF</span></li>
              <li><span class="discount-name">5名以上</span><span class="discount-value">10%OFF</span></li>
            </ul>
            <div class="price-note">※割引の併用はできません</div>
          </div>
        </div>
      </div>
      <div class="price-notes">
        <div class="notes-title">注意事項</div>
        <ul class="notes-list">
          <li>お申し込みは前日までにお願いします（当日予約はお電話にてご相談ください）</li>
          <li>10歳以上から参加可能です（10〜15歳は保護者同伴）</li>
          <li>健康状態によってはご参加いただけない場合があります</li>
          <li>アルコールを摂取した当日のダイビングはできません</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- 予約セクション（メールフォーム形式に変更） -->
  <section class="section reserve" id="reserve">
    <div class="inner">
      <div class="section-header">
        <h2 class="section-title">ご予約・お問い合わせ</h2>
        <p class="section-subtitle">簡単ステップでご予約完了</p>
      </div>
      <div class="reserve-container">
        <div class="reserve-methods">
          <div class="reserve-method email">
            <div class="method-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <h3 class="method-title">メールでのお問い合わせ</h3>
            <p class="method-desc">下記フォームからお気軽にお問い合わせください。24時間受付中です。</p>
            
            <div class="contact-form">
  <?php echo do_shortcode('[contact-form-7 id="8227ee1" title="コンタクトフォーム 1"]'); ?>
  <!-- Contact Form 7のショートコードを設置しました -->
</div>
          </div>
          
          <div class="reserve-method phone">
            <div class="method-icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <h3 class="method-title">お電話で予約</h3>
            <p class="method-desc">直接スタッフにご質問・ご相談いただけます。</p>
            <div class="method-action">
              <a href="tel:0123456789" class="phone-number">046-880-0835</a>
              <p class="phone-hours">受付時間：9:00〜16:00（不定休）</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 予約・お問い合わせセクション -->
<section id="reserve" class="section contact">
  <div class="inner">
    <h2 class="section-title">ご予約・お問い合わせ</h2>
    <div class="section-intro">
      <h3 class="cta-title">さあ、海の世界へ飛び込もう！</h3>
      <p class="cta-text">三浦の美しい海で、一生の思い出を作りませんか？<br>初めての方も、おひとり様も大歓迎です。</p>
    </div>
  </div>
</section>
</main>

<style>
/* 体験ダイビングページ専用スタイル */
.diving-experience-page {
  font-family: 'Noto Sans JP', 'M PLUS Rounded 1c', sans-serif;
  color: #333;
}

/* マージンユーティリティ */
.mr-2 {
  margin-right: 8px;
}
.ml-2 {
  margin-left: 8px;
}

/* ヒーローセクション（スクエアデザインに変更） */
.page-header {
  position: relative;
  background: linear-gradient(135deg, #5e72e4, #82ccdd);
  padding: 0;
  overflow: hidden;
}

.page-header .inner {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
}

.header-content {
  position: relative;
  z-index: 2;
  padding: 60px 20px;
  text-align: center;
  color: #fff;
}

.page-title {
  font-size: 2.8rem;
  font-weight: 700;
  margin-bottom: 15px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.page-subtitle {
  font-size: 1.4rem;
  margin-bottom: 30px;
  font-weight: 300;
}

.hero-image-container {
  position: relative;
  height: 500px;
  overflow: hidden;
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.overlay-text {
  position: absolute;
  bottom: 30px;
  right: 30px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 10px;
}

.highlight-text {
  background-color: rgba(94, 114, 228, 0.8);
  color: #fff;
  padding: 8px 15px;
  border-radius: 30px;
  font-weight: 600;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 8px;
}

.highlight-text i {
  font-size: 1.3rem;
}

.cta-button-container {
  margin-top: 30px;
}

.primary-button {
  display: inline-block;
  background-color: #ff9f7f;
  color: #fff;
  padding: 15px 30px;
  border-radius: 50px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.primary-button:hover {
  background-color: #ffb199;
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

.pulse-animation {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

/* セクション共通スタイル */
.section {
  padding: 80px 0;
}

.section:nth-child(even) {
  background-color: #f8f9fa;
}

.inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-header {
  text-align: center;
  margin-bottom: 50px;
}

.section-title {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 15px;
  color: #5e72e4;
  position: relative;
  display: inline-block;
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.section-title:after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background-color: #ff9f7f;
}

.section-subtitle {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 30px;
}

.accent-color {
  color: #ff9f7f;
  font-size: 1.2em;
  margin: 0 5px;
}

/* イントロセクション */
.content-with-image {
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  align-items: center;
  position: relative;
}

.content-with-image::before {
  content: '';
  position: absolute;
  width: 100px;
  height: 100px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23ff9f7f' opacity='0.2' d='M320 336c0 8.84-7.16 16-16 16h-96c-8.84 0-16-7.16-16-16v-48H0v144c0 25.6 22.4 48 48 48h416c25.6 0 48-22.4 48-48V288H320v48zm144-208h-80V80c0-25.6-22.4-48-48-48H176c-25.6 0-48 22.4-48 48v48H48c-25.6 0-48 22.4-48 48v80h512v-80c0-25.6-22.4-48-48-48zm-144 0H192V96h128v32z'%3E%3C/path%3E%3C/svg%3E");
  background-size: contain;
  background-repeat: no-repeat;
  top: -20px;
  right: -20px;
  z-index: -1;
  opacity: 0.7;
}

.text-content {
  flex: 1;
  min-width: 300px;
}

.text-content p {
  margin-bottom: 20px;
  font-size: 1.1rem;
  line-height: 1.8;
}

.highlight {
  background-color: #e8f0fe;
  padding: 2px 5px;
  font-weight: 600;
  color: #5e72e4;
  border-radius: 4px;
}

.image-content {
  flex: 1;
  min-width: 300px;
  position: relative;
}

.rounded-image {
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  width: 100%;
  height: auto;
}

.image-caption {
  position: absolute;
  bottom: -10px;
  right: -10px;
  background-color: #ff9f7f;
  color: #fff;
  padding: 8px 15px;
  border-radius: 4px;
  font-size: 0.9rem;
}

.intro-points {
  display: flex;
  gap: 15px;
  margin-top: 30px;
}

.point {
  flex: 1;
  text-align: center;
  background-color: #e6f3ff;
  padding: 15px;
  border-radius: 8px;
}

.point i {
  display: block;
  font-size: 2rem;
  margin-bottom: 10px;
  color: #5e72e4;
}

/* 特徴セクション */
.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
  margin-bottom: 50px;
}

.feature-card {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  padding: 30px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
}

.feature-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.feature-badge {
  position: absolute;
  top: -10px;
  right: 20px;
  background-color: #ff9f7f;
  color: #fff;
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  letter-spacing: 1px;
}

.feature-icon {
  width: 70px;
  height: 70px;
  margin: 0 auto 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #e8f0fe;
  border-radius: 50%;
  padding: 15px;
  position: relative;
}

.feature-icon:after {
  content: '';
  position: absolute;
  top: -5px;
  left: -5px;
  right: -5px;
  bottom: -5px;
  border: 2px dashed #5e72e4;
  border-radius: 50%;
  animation: rotate 15s linear infinite;
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.feature-icon img {
  max-width: 100%;
  height: auto;
}

.feature-title {
  font-size: 1.3rem;
  color: #5e72e4;
  margin-bottom: 15px;
  text-align: center;
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.feature-desc {
  font-size: 1rem;
  line-height: 1.6;
  color: #555;
}

.testimonials {
  background-color: #e8f0fe;
  padding: 40px;
  border-radius: 20px;
  margin-top: 50px;
  position: relative;
}

.testimonials:before {
  content: '"';
  position: absolute;
  top: 10px;
  left: 20px;
  font-size: 60px;
  color: #5e72e4;
  opacity: 0.2;
  font-family: serif;
}

/* テスティモニアルセクション */
.testimonial-heading {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 30px;
  color: #5e72e4;
  position: relative;
  display: inline-block;
  left: 50%;
  transform: translateX(-50%);
}

.testimonial-heading:after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, transparent, #ff9f7f, transparent);
}

.testimonial-slider {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
}

.testimonial-item {
  flex: 1;
  min-width: 300px;
  background-color: #fff;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
  transition: opacity 0.5s ease;
  position: relative;
}

.testimonial-content {
  font-style: italic;
  margin-bottom: 15px;
  line-height: 1.6;
  position: relative;
}

.testimonial-content i {
  color: #5e72e4;
  opacity: 0.5;
}

.testimonial-author {
  text-align: right;
  font-weight: 600;
  color: #666;
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

/* タイムラインセクション */
.timeline {
  position: relative;
  margin: 40px 0;
}

.timeline:before {
  content: '';
  position: absolute;
  top: 0;
  left: 30px;
  height: 100%;
  width: 4px;
  background-color: #5e72e4;
}

.timeline-item {
  position: relative;
  padding-left: 80px;
  margin-bottom: 40px;
}

.timeline-number {
  position: absolute;
  left: 0;
  top: 0;
  width: 60px;
  height: 60px;
  background-color: #5e72e4;
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  z-index: 1;
}

.timeline-content {
  background-color: #fff;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  position: relative;
}

.highlight-step .timeline-content {
  border: 2px solid #ff9f7f;
  background-color: #fff9f7;
}

.highlight-step .timeline-number {
  background-color: #ff9f7f;
}

.timeline-icon {
  position: absolute;
  top: -20px;
  right: 20px;
  width: 50px;
  height: 50px;
  background-color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.timeline-icon img {
  width: 70%;
  height: auto;
  max-width: 35px;
  max-height: 35px;
}

.timeline-title {
  font-size: 1.2rem;
  color: #5e72e4;
  margin-bottom: 10px;
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.timeline-desc {
  margin-bottom: 15px;
  line-height: 1.6;
}

.timeline-time {
  display: inline-block;
  background-color: #e8f0fe;
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 0.9rem;
  color: #5e72e4;
}

.schedule-note {
  text-align: center;
  margin-top: 40px;
  padding: 15px;
  background-color: #f8f9fa;
  border-radius: 8px;
}

/* 料金セクション */
.price-container {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  justify-content: center;
  margin-bottom: 40px;
}

.price-box {
  flex: 1;
  min-width: 300px;
  max-width: 350px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  overflow: hidden;
  transition: transform 0.3s ease;
}

.price-box:hover {
  transform: translateY(-5px);
}

.price-box.recommended {
  border: 2px solid #ff9f7f;
  position: relative;
  z-index: 1;
  transform: scale(1.05);
}

.price-box.recommended:hover {
  transform: scale(1.05) translateY(-5px);
}

.recommended-badge {
  position: absolute;
  top: -12px;
  right: 20px;
  background-color: #ff9f7f;
  color: #fff;
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
  z-index: 5;
}

.price-header {
  background-color: #5e72e4;
  color: #fff;
  padding: 25px 20px;
  text-align: center;
  border-radius: 10px 10px 0 0;
  position: relative;
  overflow: hidden;
}

.price-header:after {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(ellipse at center, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
  opacity: 0.6;
  transform: rotate(30deg);
}

.price-title {
  font-size: 1.4rem;
  margin-bottom: 15px;
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.price-value {
  font-size: 2.2rem;
  font-weight: 700;
}

.price-unit {
  font-size: 1rem;
  font-weight: 400;
}

.price-content {
  padding: 30px 20px;
}

.price-features {
  list-style: none;
  padding: 0;
  margin: 0 0 20px 0;
}

.price-features li {
  padding: 8px 0 8px 25px;
  position: relative;
}

.price-features li:before {
  content: '✓';
  position: absolute;
  left: 0;
  color: #5e72e4;
  font-weight: 600;
}

.price-note {
  text-align: center;
  font-size: 0.9rem;
  color: #666;
  margin-top: 20px;
}

.price-button {
  display: block;
  text-align: center;
  padding: 15px;
  background-color: #f1f1f1;
  color: #333;
  text-decoration: none;
  transition: all 0.3s ease;
}

.price-button:hover {
  background-color: #e6e6e6;
}

.price-button.primary {
  background-color: #ff9f7f;
  color: #fff;
}

.price-button.primary:hover {
  background-color: #ffb199;
}

.option-list {
  list-style: none;
  padding: 0;
}

.option-list li {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.option-name {
  font-weight: 600;
}

.option-price {
  color: #5e72e4;
}

.discount-list {
  list-style: none;
  padding: 0;
}

.discount-list li {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.discount-name {
  font-weight: 600;
}

.discount-value {
  color: #5e72e4;
}

.price-notes {
  background-color: #f8f9fa;
  padding: 25px;
  border-radius: 10px;
}

.notes-title {
  font-weight: 600;
  margin-bottom: 15px;
}

.notes-list {
  padding-left: 20px;
}

.notes-list li {
  margin-bottom: 8px;
}

/* 予約セクション（メールフォーム） */
.reserve-container {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  padding: 40px;
  margin-bottom: 50px;
}

.reserve-methods {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  margin-bottom: 40px;
}

.reserve-method {
  flex: 1;
  min-width: 300px;
  padding: 30px;
  border-radius: 8px;
  text-align: center;
}

.reserve-method.email {
  background-color: #e6f3ff;
  text-align: left;
}

.reserve-method.phone {
  background-color: #f1f1f1;
}

.method-icon {
  width: 60px;
  height: 60px;
  margin: 0 auto 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #5e72e4;
  font-size: 2.5rem;
}

.method-title {
  font-size: 1.3rem;
  margin-bottom: 15px;
  color: #5e72e4;
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.method-desc {
  margin-bottom: 20px;
  line-height: 1.6;
}

.contact-form {
  margin-top: 20px;
}

/* Contact Form 7スタイル調整 */
.wpcf7-form input[type="text"],
.wpcf7-form input[type="email"],
.wpcf7-form input[type="tel"],
.wpcf7-form textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-family: inherit;
  margin-bottom: 15px;
}

.wpcf7-form input[type="submit"] {
  background-color: #5e72e4;
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 30px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.wpcf7-form input[type="submit"]:hover {
  background-color: #4a5bbe;
}

.phone-number {
  display: block;
  font-size: 1.8rem;
  font-weight: 700;
  color: #5e72e4;
  text-decoration: none;
  margin-bottom: 10px;
}

.phone-hours {
  font-size: 0.9rem;
  color: #666;
}

/* CTA（コールトゥアクション）セクション */
.cta {
  background: linear-gradient(135deg, #5e72e4, #82ccdd);
  padding: 80px 0;
  color: #fff;
  position: relative;
  overflow: hidden;
}

.cta:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
  opacity: 0.2;
}

.cta-container {
  text-align: center;
  max-width: 800px;
  margin: 0 auto;
}

.cta-title {
  font-size: 2.5rem;
  margin-bottom: 20px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.cta-text {
  font-size: 1.2rem;
  margin-bottom: 40px;
  line-height: 1.8;
}

.cta-buttons {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
}

.cta-button {
  background-color: #ff9f7f;
  color: #fff;
  padding: 15px 30px;
  border-radius: 50px;
  font-size: 1.1rem;
  font-family: 'M PLUS Rounded 1c', sans-serif;
}

.cta-button:hover {
  background-color: #ffb199;
}

.cta-button-alt {
  background-color: transparent;
  color: #fff;
  border: 2px solid #fff;
  padding: 15px 30px;
  border-radius: 50px;
  font-size: 1.1rem;
}

.cta-button-alt:hover {
  background-color: rgba(255,255,255,0.1);
}

/* レスポンシブデザイン */
@media (max-width: 768px) {
  .page-title {
    font-size: 2.2rem;
  }
  
  .hero-image-container {
    height: 350px;
  }
  
  .section {
    padding: 60px 0;
  }
  
  .section-title {
    font-size: 1.8rem;
  }
  
  .content-with-image {
    flex-direction: column;
  }
  
  .timeline:before {
    left: 20px;
  }
  
  .timeline-item {
    padding-left: 60px;
  }
  
  .timeline-number {
    width: 40px;
    height: 40px;
    font-size: 0.9rem;
  }
  
  .price-box.recommended {
    transform: none;
  }
  
  .price-box.recommended:hover {
    transform: translateY(-5px);
  }
  
  .cta-title {
    font-size: 2rem;
  }
}
/* 全幅画像セクション */
.fullwidth-image { 
  position: relative; 
  width: 100%; 
  height: 400px; /* 高さは必要に応じて調整 */ 
  overflow: hidden; 
  margin: 60px 0; /* 上下のマージンでスペースを確保 */ 
} 

.fullwidth-img { 
  width: 100%; 
  height: 100%; 
  object-fit: cover; /* 画像が歪まないようにカバー表示 */ 
} 

.image-overlay { 
  position: absolute; 
  top: 0; 
  left: 0; 
  width: 100%; 
  height: 100%; 
  background: rgba(0, 0, 0, 0.3); /* 半透明のオーバーレイ */ 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  text-align: center; 
} 

.image-overlay h2 { 
  color: white; 
  font-size: 2.5rem; 
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); 
  padding: 0 20px; 
}
</style>