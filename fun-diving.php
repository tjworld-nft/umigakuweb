<?php
/**
 * Template Name: ファンダイビング
 *
 * @package WordPress
 * @subpackage Cocoon
 */

get_header(); ?>

<div id="content" class="content cf">
  <div id="content-in" class="content-in wrap cf">
    <main id="main" class="main cf" itemscope itemtype="https://schema.org/Blog">
      <article id="fun-diving-page" class="article cf" itemscope itemtype="https://schema.org/BlogPosting">
        <div class="article-body entry-content cf" itemprop="articleBody">

        <!-- SEO対策用のメタデータ改善 -->
        <meta itemprop="headline" content="三浦半島で体験する最高のファンダイビング | 神奈川県三浦市">
        <meta itemprop="description" content="三浦の海で四季折々の海洋生物に出会える透明度抜群のファンダイビング。東京・横浜から日帰りアクセス可能。初心者からベテランまで楽しめる充実のダイビングプラン。">
        <meta itemprop="keywords" content="三浦 ダイビング, ファンダイビング, 神奈川 ダイビング, 日帰りダイビング, 東京近郊 ダイビング, 初心者ダイビング, ビーチダイビング, ボートダイビング">

        <!-- 構造化データの拡充 -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "SportsActivityLocation",
          "name": "三浦海の学校 ファンダイビング",
          "description": "三浦半島で最高のファンダイビング体験。東京・横浜から日帰りで楽しめる透明度抜群の海。四季折々の海洋生物との出会いを提供。",
          "url": "https://miura-diving.com/fundiving/",
          "telephone": "046-880-0835",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "三浦市南下浦町菊名",
            "addressLocality": "三浦市",
            "addressRegion": "神奈川県",
            "postalCode": "238-0101",
            "addressCountry": "JP"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": "35.1809",
            "longitude": "139.6313"
          },
          "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
              "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"
            ],
            "opens": "09:00",
            "closes": "16:00"
          },
          "priceRange": "¥13,200〜¥19,800",
          "offers": {
            "@type": "Offer",
            "price": "13200",
            "priceCurrency": "JPY",
            "availability": "https://schema.org/InStock"
          }
        }
        </script>

          <!-- ページ内ナビゲーション（改良版スティッキー） -->
          <div class="page-nav sticky-nav">
            <div class="page-nav-container">
              <a href="#diving-types" class="nav-item">ダイビングプラン<span class="nav-icon">🤿</span></a>
              <a href="#price" class="nav-item">料金表<span class="nav-icon">💰</span></a>
              <a href="#season" class="nav-item">季節情報<span class="nav-icon">🌊</span></a>
              <a href="#points" class="nav-item">ダイビングポイント<span class="nav-icon">🗺️</span></a>
              <a href="#faq" class="nav-item">よくある質問<span class="nav-icon">❓</span></a>
              <a href="#contact" class="nav-item cta-nav">予約する<span class="nav-icon">📞</span></a>
            </div>
          </div>

          <!-- メインビジュアル（パフォーマンス最適化スライダー） -->
          <div class="main-visual-slider">
            <div class="main-visual slide1">
              <div class="main-visual-content">
                <h1>三浦の海でファンダイビング</h1>
                <p>東京・横浜から日帰りで楽しめる透明度抜群の海</p>
                <div class="visual-cta">
                  <a href="#contact" class="btn btn-lg btn-primary-gradient pulse-animation">今すぐ予約する</a>
                  <a href="#diving-types" class="btn btn-lg btn-secondary-outline">プランを見る</a>
                </div>
              </div>
            </div>
            <div class="main-visual slide2">
              <div class="main-visual-content">
                <h1>四季折々の海洋生物に出会える</h1>
                <p>豊かな生態系が残る三浦半島の海</p>
                <div class="visual-cta">
                  <a href="#contact" class="btn btn-lg btn-primary-gradient pulse-animation">今すぐ予約する</a>
                  <a href="#season" class="btn btn-lg btn-secondary-outline">ベストシーズンを見る</a>
                </div>
              </div>
            </div>
            <div class="main-visual slide3">
              <div class="main-visual-content">
                <h1>初心者も安心のガイド付き</h1>
                <p>ブランクがあっても大丈夫。丁寧にサポートします</p>
                <div class="visual-cta">
                  <a href="#contact" class="btn btn-lg btn-primary-gradient pulse-animation">今すぐ予約する</a>
                  <a href="#faq" class="btn btn-lg btn-secondary-outline">よくある質問</a>
                </div>
              </div>
            </div>
          </div>

          

          <!-- 最適化したスライダー用JavaScriptの追加 -->
          <script>
          jQuery(document).ready(function($) {
            // パフォーマンス改善のためのslickスライダーの遅延読み込み
            function loadSlickIfVisible() {
              var sliderVisible = isElementInViewport($('.main-visual-slider'));
              if (sliderVisible && typeof $.fn.slick === 'undefined') {
                $('<link>')
                  .appendTo('head')
                  .attr({type: 'text/css', rel: 'stylesheet'})
                  .attr('href', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
                  
                $('<link>')
                  .appendTo('head')
                  .attr({type: 'text/css', rel: 'stylesheet'})
                  .attr('href', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
                  
                $.getScript('https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', function() {
                  initializeSlider();
                });
              } else if (sliderVisible && typeof $.fn.slick !== 'undefined') {
                initializeSlider();
              }
            }
            
            // 要素が表示されているかチェック
            function isElementInViewport(el) {
              if (typeof jQuery === "function" && el instanceof jQuery) {
                el = el[0];
              }
              var rect = el.getBoundingClientRect();
              return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.bottom >= 0
              );
            }
            
            // ページ読み込み時とスクロール時に実行
            $(window).on('load scroll', loadSlickIfVisible);
            
            function initializeSlider() {
              // メインビジュアルスライダーの初期化（既に初期化されていない場合のみ）
              if (!$('.main-visual-slider').hasClass('slick-initialized')) {
                $('.main-visual-slider').slick({
                  dots: true,
                  infinite: true,
                  speed: 800,
                  fade: true,
                  cssEase: 'linear',
                  autoplay: true,
                  autoplaySpeed: 5000,
                  arrows: true,
                  lazyLoad: 'ondemand',
                  responsive: [{
                    breakpoint: 768,
                    settings: {
                      arrows: false
                    }
                  }]
                });
              }
            }
            
            // スクロール時のナビゲーション変更
            $(window).on('scroll', function() {
              var scrollPosition = $(window).scrollTop();
              
              if (scrollPosition > 100) {
                $('.page-nav').addClass('scrolled');
              } else {
                $('.page-nav').removeClass('scrolled');
              }
              
              // アクティブなナビゲーションアイテムのハイライト
              $('section[id]').each(function() {
                var target = $(this);
                var id = target.attr('id');
                var offset = target.offset().top - 100;
                var height = target.outerHeight();
                
                if(scrollPosition >= offset && scrollPosition < offset + height) {
                  $('.nav-item').removeClass('active');
                  $('.nav-item[href="#' + id + '"]').addClass('active');
                }
              });
            });
          });
          </script>

          <!-- ここから改良されたコンテンツ部分を始めます -->
           <!-- イントロダクション - SEOとUX改善版 -->
<section class="intro-section">
  <div class="two-column aligned-columns">
    <div class="column">
      <h2 class="section-title">三浦の海を満喫する<br>ファンダイビング体験</h2>
      <div class="section-lead">
        <p>都心から約90分。三浦半島の透明度抜群の海で、季節ごとに変化する水中世界を体験しませんか？</p>
      </div>
      <p>三浦海の学校では、<strong>初心者からベテランまで</strong>、あらゆる方に楽しんでいただけるファンダイビングプログラムをご用意しています。経験豊富なガイドがご案内し、安全で思い出に残るダイビング体験をお約束します。</p>
      
      <div class="feature-badges">
        <div class="feature-badge">
          <span class="feature-icon">🏆</span>
          <span class="feature-text">PADI認定<br>正規ショップ</span>
        </div>
        <div class="feature-badge">
          <span class="feature-icon">🚇</span>
          <span class="feature-text">東京から<br>約90分</span>
        </div>
        <div class="feature-badge">
          <span class="feature-icon">👨‍👩‍👧‍👦</span>
          <span class="feature-text">少人数<br>丁寧ガイド</span>
        </div>
        <div class="feature-badge">
          <span class="feature-icon">🐠</span>
          <span class="feature-text">豊富な<br>海洋生物</span>
        </div>
      </div>
      
      <div class="cta-container">
        <a href="#contact" class="btn btn-primary cta-btn">ご予約・お問い合わせはこちら</a>
        <p class="cta-subtext">お電話でのご予約も承ります: <strong><a href="tel:0468800835" class="phone-link">046-880-0835</a></strong></p>
      </div>
    </div>
    <div class="column image-column">
      <div class="intro-image-container">
        <div class="intro-image main-image">
          <img src="https://miura-diving.com/wp-content/uploads/5.png" alt="三浦ファンダイビングの様子 - 青い海でダイビングを楽しむダイバーたち" loading="lazy" width="600" height="400">
        </div>
        <div class="intro-image sub-image top-right">
          <img src="https://miura-diving.com/wp-content/uploads/8.png" alt="三浦の海の水中写真 - カラフルな魚や海洋生物" loading="lazy" width="300" height="200">
        </div>
        <div class="intro-image sub-image bottom-right">
          <img src="https://miura-diving.com/wp-content/uploads/15.png" alt="三浦の豊かな海の生物 - ウミウシや珊瑚の群生" loading="lazy" width="300" height="200">
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ファンダイビングの種類 - 最適化版 -->
<section id="diving-types" class="diving-types-section">
  <div class="section-header-container">
    <h2 class="section-header">選べるダイビングプラン</h2>
    <p class="section-subtitle">あなたの経験やご希望に合わせたプランをご用意しています</p>
  </div>
  
  <!-- プランタブ - UXとアクセシビリティ向上 -->
  <div class="plan-tabs" role="tablist">
    <button class="plan-tab active" data-plan="beach" role="tab" aria-selected="true" aria-controls="beach-content">ビーチダイビング<span class="level-badge beginner">初心者OK</span></button>
    <button class="plan-tab" data-plan="boat" role="tab" aria-selected="false" aria-controls="boat-content">ボートダイビング<span class="level-badge intermediate">中級者向け</span></button>
    <button class="plan-tab" data-plan="refresh" role="tab" aria-selected="false" aria-controls="refresh-content">リフレッシュダイビング<span class="level-badge refresh">ブランク解消</span></button>
    <button class="plan-tab" data-plan="self" role="tab" aria-selected="false" aria-controls="self-content">セルフダイビング<span class="level-badge advanced">上級者向け</span></button>
  </div>
  
  <!-- プランコンテンツ - アクセシビリティとSEO向上 -->
  <div class="plan-content-wrapper">
    <!-- ビーチダイビング -->
    <div class="plan-content active" id="beach-content" role="tabpanel" aria-labelledby="beach-tab">
      <div class="diving-type">
        <div class="two-column aligned-columns">
          <div class="column image-column">
            <div class="enhanced-gallery">
              <div class="gallery-grid">
                <div class="gallery-item large">
                <img src="https://miura-diving.com/wp-content/uploads/5.png" alt="三浦のビーチダイビングの様子 - 透明度の高い浅瀬でのダイビング風景" loading="lazy" width="600" height="400">
                  <div class="image-overlay">
                    <span>ショップ前のビーチダイビング</span>
                  </div>
                </div>
                <div class="gallery-item">
                  <img src="https://miura-diving.com/wp-content/uploads/8.png" alt="ビーチダイビングでの水中写真 - カラフルな魚やサンゴの様子" loading="lazy" width="300" height="200">
                  <div class="image-overlay">
                    <span>豊富な海洋生物</span>
                  </div>
                </div>
                <div class="gallery-item">
                  <img src="https://miura-diving.com/wp-content/uploads/15.png" alt="穏やかな環境でのビーチダイビング - 初心者にも安心の浅場" loading="lazy" width="300" height="200">
                  <div class="image-overlay">
                    <span>穏やかな環境で安心</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column content-column">
            <div class="plan-top-content">
              <div class="plan-badges">
                <span class="plan-badge recommend">おすすめ</span>
                <span class="plan-badge beginner">初心者OK</span>
              </div>
              <h3 class="plan-title">ビーチダイビング</h3>
              <p class="plan-description">三浦海の学校の目の前から入水できる便利なビーチダイビング。最大水深が6〜7mと浅いため、<strong>初心者の方やブランクダイバーの方にも安心</strong>して楽しんでいただけます。豊富な海洋生物と穏やかな環境で、のんびりとダイビングを楽しめます。</p>
            </div>
            
            <div class="plan-features">
              <h4 class="features-title">このプランの特徴</h4>
              <ul class="feature-list">
                <li>初心者に優しい水深と環境</li>
                <li>四季折々の海洋生物との出会い</li>
                <li>ショップから歩いてすぐのエントリーポイント</li>
                <li>ブランクダイバーのリフレッシュにも最適</li>
              </ul>
            </div>
            
            <div class="price-info-box">
              <div class="price-header">
                <h4>ビーチダイビング料金</h4>
                <span class="price-tag">13,200<small>円（税込）〜</small></span>
              </div>
              <table class="mini-price-table">
                <tr>
                  <th>基本料金（2本）</th>
                  <td>13,200円（税込）</td>
                </tr>
                <tr>
                  <th>追加ダイブ</th>
                  <td>6,600円/本（税込）</td>
                </tr>
                <tr>
                  <th>含まれるもの</th>
                  <td>ガイド料、タンク代、ウェイト代</td>
                </tr>
                <tr>
                  <th>別途必要なもの</th>
                  <td>器材レンタル代（お持ちでない場合）</td>
                </tr>
                <tr>
                  <th>所要時間</th>
                  <td>約5時間（2本の場合）</td>
                </tr>
              </table>
              <div class="plan-cta">
                <a href="#contact" class="btn-plan-cta">ビーチダイビングを予約する</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- ボートダイビング -->
    <div class="plan-content" id="boat-content" role="tabpanel" aria-labelledby="boat-tab">
      <div class="diving-type">
        <div class="two-column aligned-columns">
          <div class="column image-column">
            <div class="enhanced-gallery">
              <div class="gallery-grid">
                <div class="gallery-item large">
                  <img src="https://miura-diving.com/wp-content/uploads/6.png" alt="三浦ボートダイビングの様子 - 漁船から海の上での準備風景" loading="lazy" width="600" height="400">
                  <div class="image-overlay">
                    <span>ボートから見る三浦の海</span>
                  </div>
                </div>
                <div class="gallery-item">
                  <img src="https://miura-diving.com/wp-content/uploads/13.png" alt="ボートダイビングの魅力的なポイント - 水中で泳ぐ魚の群れ" loading="lazy" width="300" height="200">
                  <div class="image-overlay">
                    <span>魅力的なポイントへ</span>
                  </div>
                </div>
                <div class="gallery-item">
                  <img src="https://miura-diving.com/wp-content/uploads/11.png" alt="ボートダイビングならではのダイナミックな水中風景 - 岩場や水中洞窟" loading="lazy" width="300" height="200">
                  <div class="image-overlay">
                    <span>ダイナミックな水中風景</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column content-column">
            <div class="plan-top-content">
              <div class="plan-badges">
                <span class="plan-badge advanced">中級者向け</span>
                <span class="plan-badge special">特別体験</span>
                </div>
              <h3 class="plan-title">ボートダイビング</h3>
              <p class="plan-description">宮川湾や城ヶ島周辺の魅力的なポイントへボートで向かうダイビングプラン。<strong>ビーチからは行けないポイントでのダイビングを楽しめます</strong>。様々な地形や豊富な生物との出会いが魅力です。</p>
            </div>
            
            <div class="plan-features">
              <h4 class="features-title">このプランの特徴</h4>
              <div class="two-column-mini">
                <div class="mini-column">
                  <h5>宮川湾ポイント</h5>
                  <p>穏やかな湾内のポイントで、多様な海洋生物が生息。水中写真家にも人気のスポットです。</p>
                </div>
                <div class="mini-column">
                  <h5>城ヶ島ポイント</h5>
                  <p>ダイナミックな地形と豊富な回遊魚が魅力。海況が良ければ視界も良好で、ワイドな景観を楽しめます。</p>
                </div>
              </div>
            </div>
            
            <div class="price-info-box">
              <div class="price-header">
                <h4>ボートダイビング料金</h4>
                <span class="price-tag">19,800<small>円（税込）〜</small></span>
              </div>
              <table class="mini-price-table">
                <tr>
                  <th>基本料金（2本）</th>
                  <td>19,800円（税込）</td>
                </tr>
                <tr>
                  <th>含まれるもの</th>
                  <td>ガイド料、タンク代、ウェイト代、ボート代</td>
                </tr>
                <tr>
                  <th>別途必要なもの</th>
                  <td>器材レンタル代（お持ちでない場合）</td>
                </tr>
                <tr>
                  <th>集合時間</th>
                  <td>出航の45分前にショップに集合</td>
                </tr>
                <tr>
                  <th>所要時間</th>
                  <td>約6時間（2本の場合）</td>
                </tr>
              </table>
              <div class="plan-cta">
                <a href="#contact" class="btn-plan-cta">ボートダイビングを予約する</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- リフレッシュダイビング -->
<div class="plan-content" id="refresh-content" role="tabpanel" aria-labelledby="refresh-tab">
  <div class="diving-type">
    <div class="two-column aligned-columns">
      <div class="column image-column">
        <div class="enhanced-gallery">
          <div class="gallery-grid">
            <div class="gallery-item large">
              <img src="https://miura-diving.com/wp-content/uploads/14.png" alt="リフレッシュダイビングの指導風景 - インストラクターが丁寧にサポート" loading="lazy" width="600" height="400">
              <div class="image-overlay">
                <span>安心のリフレッシュ指導</span>
              </div>
            </div>
            <div class="gallery-item">
              <img src="https://miura-diving.com/wp-content/uploads/10.png" alt="リフレッシュダイビングでの基本スキル練習 - マスククリアやブイ展開" loading="lazy" width="300" height="200">
              <div class="image-overlay">
                <span>基本スキルの復習</span>
              </div>
            </div>
            <div class="gallery-item">
              <img src="https://miura-diving.com/wp-content/uploads/7.png" alt="ブランク解消に最適なリフレッシュダイビング環境 - 浅瀬での練習" loading="lazy" width="300" height="200">
              <div class="image-overlay">
                <span>ブランク解消に最適</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column content-column">
        <div class="plan-top-content">
          <div class="plan-badges">
            <span class="plan-badge popular">人気</span>
            <span class="plan-badge refresh">ブランク解消</span>
          </div>
          <h3 class="plan-title">リフレッシュダイビング</h3>
          <p class="plan-description"><strong>ブランクがある方や基本スキルを見直したい方</strong>のためのプログラム。安全に楽しくダイビングを再開できるよう、経験豊富なインストラクターがサポートします。久しぶりのダイビングでも安心して海に戻れます。</p>
        </div>
        
        <div class="plan-features">
          <h4 class="features-title">このプランの特徴</h4>
          <ul class="feature-list">
            <li>ブランクダイバーのための基本スキル復習</li>
            <li>少人数制で丁寧な指導</li>
            <li>安全第一のプログラム設計</li>
            <li>自信を持ってファンダイビングに参加できるようサポート</li>
          </ul>
        </div>
        
        <div class="price-info-box">
          <div class="price-header">
            <h4>リフレッシュダイビング料金</h4>
            <span class="price-tag">14,800<small>円（税込）〜</small></span>
          </div>
          <table class="mini-price-table">
            <tr>
              <th>料金</th>
              <td>14,800円（税込）</td>
            </tr>
            <tr>
              <th>含まれるもの</th>
              <td>インストラクター料、タンク代、ウェイト代</td>
            </tr>
            <tr>
              <th>別途必要なもの</th>
              <td>器材レンタル代（お持ちでない場合）</td>
            </tr>
            <tr>
              <th>所要時間</th>
              <td>約5時間</td>
            </tr>
          </table>
          <div class="info-note">
            <p>※久しぶりのダイビングでも安心。インストラクターが丁寧にサポートします！</p>
            <p>※ブランクの長さや不安点をお知らせいただければ、個別に対応いたします。</p>
          </div>
          <div class="plan-cta">
            <a href="#contact" class="btn-plan-cta">リフレッシュダイビングを予約する</a>
            <a href="https://miura-diving.com/refresh-diving/" class="btn-plan-cta" style="background: #ff6b6b; margin-top: 15px;">
              <i class="fas fa-external-link-alt" style="margin-right: 8px;"></i>リフレッシュダイビングの詳細を見る
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    
    <!-- セルフダイビング -->
    <div class="plan-content" id="self-content" role="tabpanel" aria-labelledby="self-tab">
      <div class="diving-type">
        <div class="two-column aligned-columns">
          <div class="column image-column">
            <div class="enhanced-gallery">
              <div class="gallery-grid">
                <div class="gallery-item large">
                  <img src="https://miura-diving.com/wp-content/uploads/16.png" alt="セルフダイビングの様子 - 経験者が自分のペースで楽しむダイビング" loading="lazy" width="600" height="400">
                  <div class="image-overlay">
                    <span>自分のペースでダイビング</span>
                  </div>
                </div>
                <div class="gallery-item">
                  <img src="https://miura-diving.com/wp-content/uploads/12.png" alt="充実のセルフダイビング装備 - タンクやウェイト" loading="lazy" width="300" height="200">
                  <div class="image-overlay">
                    <span>充実の器材</span>
                  </div>
                </div>
                <div class="gallery-item">
                  <img src="https://miura-diving.com/wp-content/uploads/18.png" alt="セルフダイビングのポイント情報 - 経験者向けの詳細マップ" loading="lazy" width="300" height="200">
                  <div class="image-overlay">
                    <span>経験者向けポイント情報</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column content-column">
            <div class="plan-top-content">
              <div class="plan-badges">
                <span class="plan-badge expert">上級者向け</span>
                <span class="plan-badge economy">お得</span>
              </div>
              <h3 class="plan-title">セルフダイビング</h3>
              <p class="plan-description"><strong>経験豊富なダイバーの方向け</strong>のプログラム。タンク・ウェイトのレンタルと入水場所の提供で、ご自身のペースでダイビングをお楽しみいただけます。ベテランダイバーの方に最適です。</p>
              <div class="special-note">
                <p>※安全のため、バディ同士でのダイビングをお願いしています。</p>
                <p>※目安として経験本数50本以上、最近1年以内にダイビング経験があることをおすすめしています。</p>
              </div>
            </div>
            
            <div class="price-info-box">
              <div class="price-header">
                <h4>セルフダイビング料金</h4>
                <span class="price-tag">7,700<small>円（税込）〜</small></span>
              </div>
              <table class="mini-price-table">
                <tr>
                  <th>料金</th>
                  <td>7,700円（税込）</td>
                </tr>
                <tr>
                  <th>含まれるもの</th>
                  <td>タンク代（1本）、ウェイト代</td>
                </tr>
                <tr>
                  <th>追加タンク</th>
                  <td>3,300円/本（税込）</td>
                </tr>
                <tr>
                  <th>別途必要なもの</th>
                  <td>器材レンタル代（お持ちでない場合）</td>
                </tr>
                <tr>
                  <th>所要時間</th>
                  <td>約5時間</td>
                </tr>
              </table>
              <div class="plan-cta">
                <a href="#contact" class="btn-plan-cta">セルフダイビングを予約する</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- レンタル器材料金 -->
  <div id="price" class="rental-section">
    <h3 class="rental-title">レンタル器材料金</h3>
    <div class="rental-table-container">
      <table class="rental-table">
        <caption class="screen-reader-text">各種レンタル器材の料金表</caption>
        <thead>
          <tr>
            <th scope="col">レンタル内容</th>
            <th scope="col">料金（税込）</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>フルセット（ウェットスーツ付き）</td>
            <td>5,500円</td>
          </tr>
          <tr>
            <td>フルセット（ドライスーツ付き）</td>
            <td>8,800円</td>
          </tr>
          <tr>
            <td>ウェットスーツ</td>
            <td>2,200円</td>
          </tr>
          <tr>
            <td>ドライスーツ</td>
            <td>5,500円</td>
          </tr>
          <tr>
            <td>BCD</td>
            <td>2,750円</td>
          </tr>
          <tr>
            <td>レギュレーター</td>
            <td>2,750円</td>
          </tr>
          <tr>
            <td>水中ライト</td>
            <td>1,650円</td>
          </tr>
          <tr>
            <td>マスク・スノーケル・フィン・ブーツ・グローブ・フード・フードベスト・コンパス・フロート</td>
            <td>各種550円</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="rental-notes">
      <p class="rental-note">※上記は基本料金です。</p>
      <p class="rental-note">※レンタル器材は数に限りがありますので、ご予約時にお申し付けください。</p>
      <p class="rental-note">※ほかレンタルにつきましてはお問い合わせください。</p>
    </div>
  </div>
</section>



<script>
jQuery(document).ready(function($) {
  // プランタブの切り替え - アクセシビリティ向上
  $('.plan-tab').on('click', function() {
    const planId = $(this).data('plan');
    
    // タブの切り替え（ARIA属性も更新）
    $('.plan-tab').removeClass('active').attr('aria-selected', 'false');
    $(this).addClass('active').attr('aria-selected', 'true');
    
    // コンテンツの切り替え
    $('.plan-content').removeClass('active').attr('aria-hidden', 'true');
    $(`#${planId}-content`).addClass('active').attr('aria-hidden', 'false');
    
    // スクロール位置の調整（モバイルでの操作性向上）
    if (window.innerWidth < 768) {
      const targetOffset = $(`#${planId}-content`).offset().top - 100;
      $('html, body').animate({
        scrollTop: targetOffset
      }, 300);
    }
  });
  
  // キーボード操作のサポート (アクセシビリティ対応)
  $('.plan-tab').on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      $(this).click();
    }
  });
  
  // レスポンシブテーブルの処理
  function adjustTables() {
    const windowWidth = $(window).width();
    if (windowWidth < 768) {
      $('.rental-table-container').each(function() {
        const $table = $(this).find('table');
        if (!$table.parent().hasClass('table-responsive') && !$table.hasClass('responsive-handled')) {
          $table.addClass('responsive-handled');
          $table.wrap('<div class="table-responsive"></div>');
        }
      });
    }
  }
  
  // 初期化時とリサイズ時に実行
  adjustTables();
  $(window).on('resize', adjustTables);
});
</script>
<!-- 季節情報 - SEO・UX最適化版 -->
<section id="season" class="season-section">
  <div class="section-header-container">
    <h2 class="section-header">三浦の海の四季</h2>
    <p class="section-subtitle">透明度、水温、出会える生物など、季節ごとの海況情報をご紹介します</p>
  </div>
  
  <!-- ベストシーズングラフ - インタラクティブ改良 -->
  <div class="best-season-graph">
    <div class="graph-title">
      <h3>三浦ダイビングのベストシーズン</h3>
      <p>年間を通して潜れる三浦の海ですが、特にベストシーズンは春と秋です</p>
    </div>
    <div class="season-graph-container">
      <div class="graph-legend">
        <div class="legend-item">
          <span class="legend-color" style="background-color: rgba(30, 115, 190, 0.8);"></span>
          <span class="legend-text">おすすめ度</span>
        </div>
        <div class="legend-item">
          <span class="legend-color" style="background-color: rgba(255, 87, 51, 0.8);"></span>
          <span class="legend-text">水温</span>
        </div>
        <div class="legend-item">
          <span class="legend-color" style="background-color: rgba(46, 204, 113, 0.8);"></span>
          <span class="legend-text">透明度</span>
        </div>
      </div>
      <div class="season-graph" aria-label="三浦の海の季節変動グラフ">
        <div class="graph-columns">
          <div class="graph-column" data-month="1月" data-temp="15℃" data-visibility="15m" data-recommend="★★★☆☆">
            <div class="month-marker">1月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 60%;" title="1月のおすすめ度: 60%"></div>
              <div class="graph-bar temperature" style="height: 40%;" title="1月の水温: 15-16℃"></div>
              <div class="graph-bar visibility" style="height: 75%;" title="1月の透明度: 10-20m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="2月" data-temp="14℃" data-visibility="15m" data-recommend="★★★☆☆">
            <div class="month-marker">2月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 60%;" title="2月のおすすめ度: 60%"></div>
              <div class="graph-bar temperature" style="height: 30%;" title="2月の水温: 13-15℃"></div>
              <div class="graph-bar visibility" style="height: 80%;" title="2月の透明度: 10-20m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="3月" data-temp="14℃" data-visibility="8m" data-recommend="★★★★☆">
            <div class="month-marker">3月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 80%;" title="3月のおすすめ度: 80%"></div>
              <div class="graph-bar temperature" style="height: 30%;" title="3月の水温: 13-15℃"></div>
              <div class="graph-bar visibility" style="height: 60%;" title="3月の透明度: 5-10m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="4月" data-temp="17℃" data-visibility="8m" data-recommend="★★★★★">
            <div class="month-marker">4月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 90%;" title="4月のおすすめ度: 90%"></div>
              <div class="graph-bar temperature" style="height: 40%;" title="4月の水温: 15-18℃"></div>
              <div class="graph-bar visibility" style="height: 50%;" title="4月の透明度: 5-10m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="5月" data-temp="19℃" data-visibility="10m" data-recommend="★★★★★">
            <div class="month-marker">5月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 95%;" title="5月のおすすめ度: 95%"></div>
              <div class="graph-bar temperature" style="height: 50%;" title="5月の水温: 18-20℃"></div>
              <div class="graph-bar visibility" style="height: 60%;" title="5月の透明度: 5-15m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="6月" data-temp="21℃" data-visibility="10m" data-recommend="★★★★☆">
            <div class="month-marker">6月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 85%;" title="6月のおすすめ度: 85%"></div>
              <div class="graph-bar temperature" style="height: 60%;" title="6月の水温: 19-22℃"></div>
              <div class="graph-bar visibility" style="height: 70%;" title="6月の透明度: 5-15m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="7月" data-temp="23℃" data-visibility="8m" data-recommend="★★★★☆">
            <div class="month-marker">7月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 80%;" title="7月のおすすめ度: 80%"></div>
              <div class="graph-bar temperature" style="height: 65%;" title="7月の水温: 21-24℃"></div>
              <div class="graph-bar visibility" style="height: 50%;" title="7月の透明度: 5-10m"></div>
            </div>
          </div>
          <div class="graph-column highlight-column" data-month="8月" data-temp="25℃" data-visibility="8m" data-recommend="★★★★☆">
            <div class="month-marker">8月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 75%;" title="8月のおすすめ度: 75%"></div>
              <div class="graph-bar temperature" style="height: 85%;" title="8月の水温: 23-27℃"></div>
              <div class="graph-bar visibility" style="height: 45%;" title="8月の透明度: 5-10m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="9月" data-temp="24℃" data-visibility="10m" data-recommend="★★★★★">
            <div class="month-marker">9月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 90%;" title="9月のおすすめ度: 90%"></div>
              <div class="graph-bar temperature" style="height: 75%;" title="9月の水温: 22-25℃"></div>
              <div class="graph-bar visibility" style="height: 70%;" title="9月の透明度: 5-15m"></div>
            </div>
          </div>
          <div class="graph-column highlight-column" data-month="10月" data-temp="22℃" data-visibility="10m" data-recommend="★★★★★">
            <div class="month-marker">10月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 95%;" title="10月のおすすめ度: 95%"></div>
              <div class="graph-bar temperature" style="height: 65%;" title="10月の水温: 20-23℃"></div>
              <div class="graph-bar visibility" style="height: 75%;" title="10月の透明度: 5-15m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="11月" data-temp="20℃" data-visibility="10m" data-recommend="★★★★☆">
            <div class="month-marker">11月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 85%;" title="11月のおすすめ度: 85%"></div>
              <div class="graph-bar temperature" style="height: 55%;" title="11月の水温: 18-21℃"></div>
              <div class="graph-bar visibility" style="height: 65%;" title="11月の透明度: 5-15m"></div>
            </div>
          </div>
          <div class="graph-column" data-month="12月" data-temp="18℃" data-visibility="10m" data-recommend="★★★☆☆">
            <div class="month-marker">12月</div>
            <div class="graph-bar-container">
              <div class="graph-bar recommend" style="height: 65%;" title="12月のおすすめ度: 65%"></div>
              <div class="graph-bar temperature" style="height: 45%;" title="12月の水温: 16-19℃"></div>
              <div class="graph-bar visibility" style="height: 60%;" title="12月の透明度: 5-15m"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="graph-info">
        <div id="graph-tooltip" class="graph-tooltip">月を選択するとデータが表示されます</div>
      </div>
    </div>
  </div>
  
  <!-- 詳細な季節データテーブル - アクセシビリティ改善 -->
  <div class="season-details">
    <h3>月別の詳細データ</h3>
    <div class="responsive-table-container">
      <table class="temperature-table">
        <caption class="screen-reader-text">三浦の海の月別水温、透明度、生物情報</caption>
        <thead>
          <tr>
            <th scope="col">月</th>
            <th scope="col">水温</th>
            <th scope="col">透明度</th>
            <th scope="col">主な生物</th>
            <th scope="col">おすすめ度</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1月</td>
            <td>15-16℃</td>
            <td>10-20m</td>
            <td>根魚類、カサゴ</td>
            <td class="stars">★★★☆☆</td>
          </tr>
          <tr>
            <td>2月</td>
            <td>13-15℃</td>
            <td>10-20m</td>
            <td>ウミウシ類、根魚類</td>
            <td class="stars">★★★☆☆</td>
          </tr>
          <tr>
            <td>3月</td>
            <td>13-15℃</td>
            <td>5-10m</td>
            <td>ウミウシ類、メバル</td>
            <td class="stars">★★★★☆</td>
          </tr>
          <tr>
            <td>4月</td>
            <td>15-18℃</td>
            <td>5-10m</td>
            <td>アオリイカ（産卵）、ウミウシ類</td>
            <td class="stars">★★★★★</td>
          </tr>
          <tr>
            <td>5月</td>
            <td>18-20℃</td>
            <td>5-15m</td>
            <td>コブダイ、メバル、ベラ類</td>
            <td class="stars">★★★★★</td>
          </tr>
          <tr>
            <td>6月</td>
            <td>19-22℃</td>
            <td>5-15m</td>
            <td>イサキ、メジナ、カサゴ</td>
            <td class="stars">★★★★☆</td>
          </tr>
          <tr>
            <td>7月</td>
            <td>21-24℃</td>
            <td>5-10m</td>
            <td>メジナ、クロダイ、キンメモドキ</td>
            <td class="stars">★★★★☆</td>
          </tr>
          <tr>
            <td>8月</td>
            <td>23-27℃</td>
            <td>5-10m</td>
            <td>クマノミ、スズメダイ類、イサキ</td>
            <td class="stars">★★★★☆</td>
          </tr>
          <tr>
            <td>9月</td>
            <td>22-25℃</td>
            <td>5-15m</td>
            <td>カンパチ、イサキ、ハナダイ類</td>
            <td class="stars">★★★★★</td>
          </tr>
          <tr>
            <td>10月</td>
            <td>20-23℃</td>
            <td>5-15m</td>
            <td>ブリ、イナダ、メジナ</td>
            <td class="stars">★★★★★</td>
          </tr>
          <tr>
            <td>11月</td>
            <td>18-21℃</td>
            <td>5-15m</td>
            <td>メジナ、カサゴ、ソラスズメダイ</td>
            <td class="stars">★★★★☆</td>
          </tr>
          <tr>
            <td>12月</td>
            <td>16-19℃</td>
            <td>5-15m</td>
            <td>カサゴ、アイナメ、メバル</td>
            <td class="stars">★★★☆☆</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- 季節別おすすめポイント - デザイン改良 -->
  <div class="seasonal-recommendations">
    <h3>季節別おすすめポイント</h3>
    <div class="season-cards">
      <div class="season-card spring">
        <div class="season-card-inner">
          <div class="season-icon">🌸</div>
          <h4>春（3月〜5月）</h4>
          <p>水温が上昇し始め、透明度も安定してくる時期。特に<strong>ウミウシ類が多く見られ、アオリイカの産卵シーン</strong>に遭遇することも。生物の活動が活発になる季節です。</p>
          <div class="season-highlights">
            <span class="highlight-badge">アオリイカの産卵</span>
            <span class="highlight-badge">ウミウシの季節</span>
            <span class="highlight-badge">安定した海況</span>
          </div>
        </div>
      </div>
      <div class="season-card summer">
        <div class="season-card-inner">
          <div class="season-icon">☀️</div>
          <h4>夏（6月〜8月）</h4>
          <p>水温が上がり、熱帯性の魚も見られるようになります。<strong>イサキの群れやカラフルな魚たち</strong>との出会いが楽しめます。初心者ダイバーに最適な季節です。</p>
          <div class="season-highlights">
            <span class="highlight-badge">イサキの大群</span>
            <span class="highlight-badge">水温上昇</span>
            <span class="highlight-badge">初心者シーズン</span>
          </div>
        </div>
      </div>
      <div class="season-card autumn">
        <div class="season-card-inner">
          <div class="season-icon">🍂</div>
          <h4>秋（9月〜11月）</h4>
          <p>台風の影響が少なくなると、透明度が上がります。水温もまだ暖かく、<strong>多くの海洋生物が観察できるベストシーズン</strong>です。ダイビングの黄金期です。</p>
          <div class="season-highlights">
            <span class="highlight-badge">透明度上昇</span>
            <span class="highlight-badge">回遊魚</span>
            <span class="highlight-badge">黄金シーズン</span>
          </div>
        </div>
      </div>
      <div class="season-card winter">
        <div class="season-card-inner">
          <div class="season-icon">❄️</div>
          <h4>冬（12月〜2月）</h4>
          <p>水温は下がりますが、<strong>透明度は年間を通して高め</strong>です。防寒対策をしっかりすれば、静かな海でのダイビングを楽しめます。根魚の観察に適した季節です。</p>
          <div class="season-highlights">
            <span class="highlight-badge">高透明度</span>
            <span class="highlight-badge">根魚の季節</span>
            <span class="highlight-badge">静かな海</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- 予約CTAセクション - UX改善 -->
  <div class="mid-page-cta">
    <div class="cta-content">
      <h3>あなたの予定に合わせたベストシーズンをご案内します</h3>
      <p>季節ごとの生物情報や海況をもとに、あなたの希望に合ったベストなタイミングでのダイビングをご提案。経験豊富なガイドがサポートします。</p>
      <div class="cta-buttons">
        <a href="#contact" class="btn btn-cta">お問い合わせ・ご予約はこちら</a>
        <a href="tel:0468800835" class="btn btn-cta-outline">電話で相談する</a>
      </div>
    </div>
  </div>
</section>



<script>
jQuery(document).ready(function($) {
  // グラフバーのアニメーション
  function animateGraphBars() {
    $('.graph-bar').each(function() {
      let targetHeight = $(this).css('height');
      $(this).css('height', '0').animate({
        height: targetHeight
      }, 1000);
    });
  }
  
  // グラフが表示されたらアニメーション開始
  function checkGraphVisibility() {
    const graph = $('.season-graph');
    if (isElementInViewport(graph)) {
      animateGraphBars();
      $(window).off('scroll', checkGraphVisibility);
    }
  }
  
  // 要素が表示されているかチェック
  function isElementInViewport(el) {
    if (typeof jQuery === "function" && el instanceof jQuery) {
      el = el[0];
    }
    
    const rect = el.getBoundingClientRect();
    return (
      rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.bottom >= 0
    );
  }
  
  // イベントリスナー設定
  $(window).on('scroll', checkGraphVisibility);
  checkGraphVisibility();
  
  // グラフカラムのインタラクティブ機能
  $('.graph-column').on('mouseenter', function() {
    const month = $(this).data('month');
    const temp = $(this).data('temp');
    const visibility = $(this).data('visibility');
    const recommend = $(this).data('recommend');
    
    $('#graph-tooltip').html(
      `<strong>${month}</strong> - 水温: ${temp} | 透明度: ${visibility} | おすすめ度: ${recommend}`
    );
  });
  
  // 現在の月を自動ハイライト
  function highlightCurrentMonth() {
    const now = new Date();
    const currentMonth = now.getMonth(); // 0-11
    
    // すべてのカラムからハイライトを削除
    $('.graph-column').removeClass('highlight-column');
    
    // 現在の月のカラムをハイライト
    $('.graph-column').eq(currentMonth).addClass('highlight-column');
    
    // 現在の海況情報の更新
    updateCurrentConditions(currentMonth);
  }
  
  // 現在の海況情報を更新
  function updateCurrentConditions(month) {
    // 月ごとの水温、透明度データ
    const tempData = ['15-16℃', '13-15℃', '13-15℃', '15-18℃', '18-20℃', '19-22℃', 
                     '21-24℃', '23-27℃', '22-25℃', '20-23℃', '18-21℃', '16-19℃'];
    
    const visibilityData = ['10-20m', '10-20m', '5-10m', '5-10m', '5-15m', '5-15m',
                           '5-10m', '5-10m', '5-15m', '5-15m', '5-15m', '5-15m'];
    
    const seaConditionData = ['穏やか', '穏やか', '変動あり', '変動あり', '比較的穏やか', 
                             '比較的穏やか', '変動あり', '変動あり', '比較的穏やか', 
                             '比較的穏やか', '変動あり', '穏やか'];
    
    $('#current-water-temp').text(tempData[month]);
    $('#current-visibility').text(visibilityData[month]);
    $('#current-sea-condition').text(seaConditionData[month]);
  }
  
  // 初期化
  highlightCurrentMonth();
  
  // ウィンドウがリサイズされたら調整
  $(window).on('resize', function() {
    checkGraphVisibility();
  });
});
</script>
<!-- ダイビングポイント - UX・アクセシビリティ改善版 -->
<section id="points" class="diving-points-section">
  <div class="section-header-container">
    <h2 class="section-header">三浦のダイビングポイント</h2>
    <p class="section-subtitle">初心者から上級者まで楽しめる多彩なポイントをご紹介</p>
  </div>
  
  <!-- マップナビゲーション - インタラクティブ強化 -->
  <div class="points-map-container">
    <div class="points-map">
      <img src="https://miura-diving.com/wp-content/uploads/mapp.png" alt="三浦半島ダイビングポイントマップ - 主要ポイントの位置関係" loading="lazy" width="600" height="400">
      <div class="map-marker beach-marker" data-point="beach" role="button" tabindex="0" aria-label="ショップ前ポイント - 初心者向け">
        <span class="marker-dot"></span>
        <span class="marker-label">ショップ前</span>
      </div>
      <div class="map-marker miyagawa-marker" data-point="miyagawa" role="button" tabindex="0" aria-label="宮川湾ポイント - 中級者向け">
        <span class="marker-dot"></span>
        <span class="marker-label">宮川湾</span>
      </div>
      <div class="map-marker jogashima-marker" data-point="jogashima" role="button" tabindex="0" aria-label="城ヶ島ポイント - 上級者向け">
        <span class="marker-dot"></span>
        <span class="marker-label">城ヶ島</span>
      </div>
    </div>
    
    <div class="points-navigation">
      <button class="point-nav-btn active" data-point="beach" aria-pressed="true">三浦海の学校前<span class="level-badge beginner">初心者</span></button>
      <button class="point-nav-btn" data-point="miyagawa" aria-pressed="false">宮川湾<span class="level-badge intermediate">中級者</span></button>
      <button class="point-nav-btn" data-point="jogashima" aria-pressed="false">城ヶ島<span class="level-badge advanced">上級者</span></button>
    </div>
  </div>
  
  <!-- ポイント詳細 - SEO強化 -->
  <div class="point-details-container">
    <!-- ビーチポイント -->
    <div class="point-detail active" id="beach-detail" aria-hidden="false">
      <div class="point-header">
        <h3>ビーチポイント（三浦海の学校前）</h3>
        <div class="point-tags">
          <span class="point-tag">水深: 6-7m</span>
          <span class="point-tag">難易度: 初心者向け</span>
          <span class="point-tag">アクセス: 徒歩0分</span>
        </div>
      </div>
      
      <div class="point-content">
        <div class="point-gallery">
          <div class="main-point-image">
            <img src="https://miura-diving.com/wp-content/uploads/P1133800-scaled.jpg" alt="三浦海の学校前のビーチポイント - 穏やかな入り江と砂浜" loading="lazy" width="600" height="400">
          </div>
          <div class="thumbnail-images">
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/5.png" alt="ビーチポイントで観察できる多様な生物" loading="lazy" width="150" height="100">
            </div>
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/8.png" alt="ビーチポイントの美しい水中景観" loading="lazy" width="150" height="100">
            </div>
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/15.png" alt="ビーチポイントでダイビングを楽しむダイバー" loading="lazy" width="150" height="100">
            </div>
          </div>
        </div>
        
        <div class="point-info">
          <p class="point-desc">三浦海の学校の目の前に広がるビーチポイント。<strong>最大水深は6〜7m程度と浅く、初心者の方やブランクダイバーに最適</strong>です。四季を通じて様々な生物が観察でき、環境に応じたスキルアップのトレーニングにも最適な場所です。</p>
          
          <div class="feature-blocks">
            <div class="feature-block">
              <div class="feature-icon">🌊</div>
              <h4>地形の特徴</h4>
              <p>浅瀬では砂地が広がり、深くなるにつれて岩場になる変化に富んだ地形。初心者でも安心して潜れる環境です。水中写真の練習にも最適な場所です。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">🐠</div>
              <h4>主な生物</h4>
              <p>ベラ、メバル、カサゴなどの根魚に加え、春にはウミウシ類、夏にはクマノミなども観察できます。初心者でも簡単に多くの生物と出会えます。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">🏊</div>
              <h4>アクセス</h4>
              <p>ショップから徒歩0分。準備から潜水まで効率的に行えるため、複数本のダイビングが楽しめます。更衣室やシャワーなどの設備も充実。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">📅</div>
              <h4>ベストシーズン</h4>
              <p>年間を通して潜水可能。特に4〜6月、9〜11月は水温と透明度のバランスが良く最適です。初心者は夏の温かい時期がおすすめです。</p>
            </div>
          </div>
          
          <div class="point-cta">
            <a href="#contact" class="btn-point-cta">このポイントで潜る（予約する）</a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 宮川湾ポイント -->
    <div class="point-detail" id="miyagawa-detail" aria-hidden="true">
      <div class="point-header">
        <h3>ボートポイント（宮川湾）</h3>
        <div class="point-tags">
          <span class="point-tag">水深: 最大18m</span>
          <span class="point-tag">難易度: 中級者向け</span>
          <span class="point-tag">アクセス: ボート10分</span>
        </div>
      </div>
      
      <div class="point-content">
        <div class="point-gallery">
          <div class="main-point-image">
            <img src="https://miura-diving.com/wp-content/uploads/IMG_9269.png" alt="宮川湾のボートポイント - 穏やかな湾と透明度の高い海" loading="lazy" width="600" height="400">
          </div>
          <div class="thumbnail-images">
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/6.png" alt="宮川湾で見られる多様な海洋生物" loading="lazy" width="150" height="100">
            </div>
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/13.png" alt="宮川湾の水中景観 - 美しい岩場と珊瑚" loading="lazy" width="150" height="100">
            </div>
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/10.png" alt="宮川湾でダイビングを楽しむダイバー" loading="lazy" width="150" height="100">
            </div>
          </div>
        </div>
        
        <div class="point-info">
          <p class="point-desc">三浦半島東側にある宮川湾のポイントは、<strong>様々な海洋生物との出会いが魅力</strong>です。比較的穏やかな海況が多く、ボートダイビング初心者の方にもおすすめです。三浦地域の中でも人気のダイビングスポットです。</p>
          
          <div class="feature-blocks">
            <div class="feature-block">
              <div class="feature-icon">🌊</div>
              <h4>地形の特徴</h4>
              <p>砂地、岩場など多様な海底環境が広がり、水深も6mから18mまで様々なレベルのダイバーが楽しめます。水中洞窟や起伏に富んだ地形も魅力的です。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">🐠</div>
              <h4>主な生物</h4>
              <p>イサキの群れ、カサゴ、ウミウシ類など多様な生物が生息。水中写真家にも人気のスポットです。季節によってはキンメモドキの大群にも遭遇できます。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">🏊</div>
              <h4>アクセス</h4>
              <p>港から約10分のボート移動。ビーチよりも深く潜れるため、より多様な生物に出会えます。ボートのためエントリー・エキジットも楽に行えます。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">📅</div>
              <h4>ベストシーズン</h4>
              <p>6〜11月が特におすすめ。夏から秋にかけては魚影も濃く、水中景観も楽しめます。透明度も比較的安定している時期です。</p>
            </div>
          </div>
          
          <div class="point-cta">
            <a href="#contact" class="btn-point-cta">このポイントで潜る（予約する）</a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 城ヶ島ポイント -->
    <div class="point-detail" id="jogashima-detail" aria-hidden="true">
      <div class="point-header">
        <h3>ボートポイント（城ヶ島）</h3>
        <div class="point-tags">
          <span class="point-tag">水深: 最大25m</span>
          <span class="point-tag">難易度: 上級者向け</span>
          <span class="point-tag">アクセス: ボート20分</span>
        </div>
      </div>
      
      <div class="point-content">
        <div class="point-gallery">
          <div class="main-point-image">
            <img src="https://miura-diving.com/wp-content/uploads/PC113766-scaled.jpg" alt="城ヶ島のボートポイント - ダイナミックな外海の風景" loading="lazy" width="600" height="400">
          </div>
          <div class="thumbnail-images">
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/7.png" alt="城ヶ島で見られる大型回遊魚" loading="lazy" width="150" height="100">
            </div>
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/12.png" alt="城ヶ島の水中景観 - 迫力ある岩場と地形" loading="lazy" width="150" height="100">
            </div>
            <div class="thumbnail" role="button" tabindex="0">
              <img src="https://miura-diving.com/wp-content/uploads/11.png" alt="城ヶ島でダイビングを楽しむ経験豊富なダイバー" loading="lazy" width="150" height="100">
            </div>
          </div>
        </div>
        
        <div class="point-info">
          <p class="point-desc">三浦半島最南端の城ヶ島周辺は、<strong>ダイナミックな地形が魅力のポイント</strong>です。流れがあり、水深も深いため、アドバンスド以上のダイバー向けのスポットです。迫力ある海を体験したい方におすすめです。</p>
          
          <div class="feature-blocks">
            <div class="feature-block">
              <div class="feature-icon">🌊</div>
              <h4>地形の特徴</h4>
              <p>切り立った崖や水中洞窟など変化に富んだ地形が魅力。ダイナミックな地形探索を楽しめます。外洋に面しているため水中視界も良好です。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">🐠</div>
              <h4>主な生物</h4>
              <p>外洋に面しているため、ブリ、カンパチなどの回遊魚との遭遇率が高い。時期によってはマンタなどの大型生物との出会いも期待できます。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">🏊</div>
              <h4>アクセス</h4>
              <p>港から約20分のボート移動。海況によっては揺れることもあるため、船酔いが心配な方は事前に酔い止めをおすすめします。外海の醍醐味が味わえます。</p>
            </div>
            <div class="feature-block">
              <div class="feature-icon">📅</div>
              <h4>ベストシーズン</h4>
              <p>9〜11月が特におすすめ。比較的海が穏やかで、回遊魚との遭遇も期待できます。水温と透明度のバランスも良い時期です。</p>
            </div>
          </div>
          
          <div class="point-cta">
            <a href="#contact" class="btn-point-cta">このポイントで潜る（予約する）</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- 体験談 - 信頼性向上 -->
  <div class="testimonials-section">
    <h3>ダイバーの声</h3>
    <div class="testimonials-container">
      <div class="testimonial">
        <div class="testimonial-content">
          <p>「三浦海の学校前のビーチは初心者の私でも安心して潜れました。インストラクターさんも丁寧で、<strong>たくさんの魚を見ることができて大満足</strong>です！特にウミウシの種類の多さに感動しました。」</p>
        </div>
        <div class="testimonial-footer">
          <div class="testimonial-avatar">
            <img src="https://miura-diving.com/wp-content/uploads/guest-voice9.webp" alt="女性ダイバーの写真" loading="lazy" width="50" height="50">
          </div>
          <div class="testimonial-author">
            <div class="author-name">N.Tさん</div>
            <div class="author-info">30代女性・ダイビング歴1年</div>
          </div>
        </div>
      </div>
      
      <div class="testimonial">
        <div class="testimonial-content">
          <p>「宮川湾でのボートダイビングは<strong>生物の種類が豊富で水中写真を撮るのに最高</strong>でした。特にウミウシの種類が多く、マクロ派にはたまらないポイントです。ガイドさんの知識も豊富で勉強になりました。」</p>
        </div>
        <div class="testimonial-footer">
          <div class="testimonial-avatar">
            <img src="https://miura-diving.com/wp-content/uploads/guest-voice8.webp" alt="男性ダイバーの写真" loading="lazy" width="50" height="50">
          </div>
          <div class="testimonial-author">
            <div class="author-name">K.Sさん</div>
            <div class="author-info">40代男性・ダイビング歴5年</div>
          </div>
        </div>
      </div>
      
      <div class="testimonial">
        <div class="testimonial-content">
          <p>「城ヶ島周辺は流れもあり上級者向けですが、その分<strong>魚影が濃くてワイドな景観が素晴らしい</strong>です。回遊魚の大群に遭遇したときは感動しました！三浦でこんな本格的なダイビングができるとは思いませんでした。」</p>
        </div>
        <div class="testimonial-footer">
          <div class="testimonial-avatar">
            <img src="https://miura-diving.com/wp-content/uploads/guest-voice10.webp" alt="経験豊富な男性ダイバーの写真" loading="lazy" width="50" height="50">
          </div>
          <div class="testimonial-author">
            <div class="author-name">M.Yさん</div>
            <div class="author-info">50代男性・ダイビング歴10年</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
jQuery(document).ready(function($) {
  // ポイントタブの切り替え - アクセシビリティ向上
  $('.point-nav-btn, .map-marker').on('click keypress', function(e) {
    // キーボード操作のサポート
    if (e.type === 'keypress' && e.which !== 13) {
      return;
    }
    
    const pointId = $(this).data('point');
    
    // タブとマーカーの切り替え
    $('.point-nav-btn, .map-marker').removeClass('active');
    $('.point-nav-btn').attr('aria-pressed', 'false');
    $(`.point-nav-btn[data-point="${pointId}"], .map-marker[data-point="${pointId}"]`).addClass('active');
    $(`.point-nav-btn[data-point="${pointId}"]`).attr('aria-pressed', 'true');
    
    // 詳細コンテンツの切り替え
    $('.point-detail').removeClass('active').attr('aria-hidden', 'true');
    $(`#${pointId}-detail`).addClass('active').attr('aria-hidden', 'false');
    
    // スクロール位置の調整（モバイルでの操作性向上）
    if (window.innerWidth < 768) {
      const targetOffset = $(`#${pointId}-detail`).offset().top - 100;
      $('html, body').animate({
        scrollTop: targetOffset
      }, 300);
    }
  });
  
  // サムネイル画像クリック時のメイン画像入れ替え - アクセシビリティ強化
  $('.thumbnail').on('click keypress', function(e) {
    // キーボード操作のサポート
    if (e.type === 'keypress' && e.which !== 13) {
      return;
    }
    
    const thumbnailImg = $(this).find('img');
    const thumbnailSrc = thumbnailImg.attr('src');
    const thumbnailAlt = thumbnailImg.attr('alt');
    const mainImgContainer = $(this).closest('.point-gallery').find('.main-point-image');
    const mainImg = mainImgContainer.find('img');
    const currentMainSrc = mainImg.attr('src');
    const currentMainAlt = mainImg.attr('alt');
    
    // アニメーションで入れ替え
    mainImgContainer.addClass('switching');
    
    setTimeout(function() {
      // メイン画像を更新
      mainImg.attr({
        'src': thumbnailSrc,
        'alt': thumbnailAlt
      });
      
      // サムネイル画像を更新
      thumbnailImg.attr({
        'src': currentMainSrc,
        'alt': currentMainAlt
      });
      
      mainImgContainer.removeClass('switching');
    }, 300);
  });
  
  // 要素が表示されているかチェック - パフォーマンス最適化
  function isElementInViewport(el) {
    if (typeof jQuery === "function" && el instanceof jQuery) {
      el = el[0];
    }
    
    const rect = el.getBoundingClientRect();
    return (
      rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.bottom >= 0
    );
  }
  
  // 画像のレイジーロード実装
  function lazyLoadImages() {
    $('.point-gallery img[loading="lazy"]').each(function() {
      if (isElementInViewport(this) && $(this).attr('data-src')) {
        $(this).attr('src', $(this).attr('data-src')).removeAttr('data-src');
      }
    });
  }
  
  // 初期ロード時とスクロール時に実行
  lazyLoadImages();
  $(window).on('scroll resize', lazyLoadImages);
  
  // フォーカス管理 - アクセシビリティ向上
  $('.map-marker, .thumbnail').attr('tabindex', '0');
  
  // モバイルデバイス検出
  const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  
  // モバイルデバイスの場合、タップ操作の改善
  if (isMobile) {
    $('.map-marker').on('touchstart', function() {
      $('.map-marker .marker-label').css('opacity', '0');
      $(this).find('.marker-label').css('opacity', '1');
    });
  }
});
</script>
<!-- よくある質問 - SEO・アクセシビリティ最適化版 -->
<section id="faq" class="faq-section">
  <div class="section-header-container">
    <h2 class="section-header">よくある質問</h2>
    <p class="section-subtitle">ファンダイビングについてのよくある質問をまとめました</p>
  </div>
  
  <div class="faq-container">
    <div class="faq-categories" role="tablist" aria-label="よくある質問のカテゴリー">
      <button class="faq-category active" data-category="beginner" role="tab" aria-selected="true" aria-controls="beginner-faq">初心者の方<span class="category-icon">🔰</span></button>
      <button class="faq-category" data-category="experience" role="tab" aria-selected="false" aria-controls="experience-faq">経験者の方<span class="category-icon">🏆</span></button>
      <button class="faq-category" data-category="booking" role="tab" aria-selected="false" aria-controls="booking-faq">予約・料金<span class="category-icon">📅</span></button>
      <button class="faq-category" data-category="equipment" role="tab" aria-selected="false" aria-controls="equipment-faq">器材・準備<span class="category-icon">🤿</span></button>
    </div>
    
    <div class="faq-content-container">
      <!-- 初心者向けFAQ -->
      <div class="faq-content active" id="beginner-faq" role="tabpanel" aria-labelledby="beginner-tab">
        <div class="faq-item expanded">
          <div class="faq-question" role="button" aria-expanded="true" tabindex="0">
            <span class="faq-icon">Q</span>
            <span class="question-text">初心者でも参加できますか？</span>
            <span class="toggle-icon"></span>
          </div>
          <div class="faq-answer">
            <span class="faq-icon">A</span>
            <div class="answer-content">
              <p>はい、Cカード（ダイビングライセンス）をお持ちであれば、経験本数が少なくても参加いただけます。初心者の方には特に丁寧にガイドいたしますのでご安心ください。<strong>三浦海の学校前のビーチポイントは水深が6〜7mと浅く、穏やかな海況が多いため、初心者の方に特におすすめ</strong>です。</p>
            </div>
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-question" role="button" aria-expanded="false" tabindex="0">
            <span class="faq-icon">Q</span>
            <span class="question-text">ブランクがあるのですが大丈夫ですか？</span>
            <span class="toggle-icon"></span>
          </div>
          <div class="faq-answer">
            <span class="faq-icon">A</span>
            <div class="answer-content">
              <p>もちろん大丈夫です。ブランクのある方には<strong>「リフレッシュダイビング」をご用意</strong>しています。ダイビング専用プールで基本スキルを復習してから海に行くので、安心してダイビングを再開いただけます。ブランクの期間や不安なポイントをお知らせいただければ、個別に対応いたします。</p>
            </div>
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-question" role="button" aria-expanded="false" tabindex="0">
            <span class="faq-icon">Q</span>
            <span class="question-text">ダイビングライセンス（Cカード）を持っていませんが参加できますか？</span>
            <span class="toggle-icon"></span>
          </div>
          <div class="faq-answer">
            <span class="faq-icon">A</span>
            <div class="answer-content">
              <p>ファンダイビングにはCカード（ダイビングライセンス）が必要です。まだお持ちでない方は、当店で開催している「<strong>PADIオープンウォーターダイバーコース</strong>」を受講していただくことをおすすめします。3日間程度で資格を取得でき、その後ファンダイビングにご参加いただけます。</p>
              <div class="answer-cta">
                <a href="https://miura-diving.com/license/" class="btn-small">ライセンス取得コースを見る</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-question" role="button" aria-expanded="false" tabindex="0">
            <span class="faq-icon">Q</span>
            <span class="question-text">1人での参加は可能ですか？</span>
            <span class="toggle-icon"></span>
          </div>
          <div class="faq-answer">
            <span class="faq-icon">A</span>
            <div class="answer-content">
              <p>はい、1人での参加も大歓迎です。<strong>実際に多くの方が1人で参加されています</strong>。ガイドダイビングなので、インストラクターがしっかりとサポートしますのでご安心ください。また、ダイビングを通じて新しい仲間ができることも多いです。</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- 経験者向けFAQ （他のFAQも同様の構造で） -->
      <!-- 省略：他のFAQカテゴリも同様の構造で作成 -->
    </div>
  </div>
  
  <div class="more-questions">
    <h3>まだ質問がある方へ</h3>
    <p>その他のご質問やご不明点がございましたら、お気軽にお問い合わせください。経験豊富なスタッフが丁寧にお答えします。</p>
    <div class="more-questions-cta">
      <a href="#contact" class="btn btn-secondary">お問い合わせはこちら</a>
      <p>または、お電話でもお気軽に：<strong><a href="tel:0468800835" class="phone-link">046-880-0835</a></strong></p>
    </div>
  </div>
</section>



<script>
jQuery(document).ready(function($) {
  // FAQ カテゴリーの切り替え - アクセシビリティ強化
  $('.faq-category').on('click keypress', function(e) {
    // キーボード操作のサポート
    if (e.type === 'keypress' && e.which !== 13) {
      return;
    }
    
    const category = $(this).data('category');
    
    // カテゴリーの切り替え（ARIA属性も更新）
    $('.faq-category').removeClass('active').attr('aria-selected', 'false');
    $(this).addClass('active').attr('aria-selected', 'true');
    
    // FAQ コンテンツの切り替え
    $('.faq-content').removeClass('active').attr('aria-hidden', 'true');
    $(`#${category}-faq`).addClass('active').attr('aria-hidden', 'false');
    
    // スクロール位置の調整（モバイルでの操作性向上）
    if (window.innerWidth < 768) {
      const targetOffset = $(`#${category}-faq`).offset().top - 100;
      $('html, body').animate({
        scrollTop: targetOffset
      }, 300);
    }
  });
  
  // FAQ項目の開閉 - パフォーマンスとアクセシビリティ向上
  $('.faq-question').on('click keypress', function(e) {
    // キーボード操作のサポート
    if (e.type === 'keypress' && e.which !== 13) {
      return;
    }
    
    const $item = $(this).closest('.faq-item');
    const $answer = $item.find('.faq-answer');
    const isExpanded = $item.hasClass('expanded');
    
    // アコーディオン状態の更新
    $(this).attr('aria-expanded', !isExpanded);
    
    if (isExpanded) {
      // 閉じる
      $item.removeClass('expanded');
      $answer.css('height', $answer.outerHeight()).animate({height: 0}, 300, function() {
        $answer.css('height', '');
        $answer.css('opacity', 0);
      });
    } else {
      // 開く
      // 他の項目を閉じる（1つだけ開く仕様の場合）
      /*
      $('.faq-item').removeClass('expanded');
      $('.faq-question').attr('aria-expanded', 'false');
      $('.faq-answer').css('height', '').css('opacity', 0);
      */
      
      $item.addClass('expanded');
      $answer.css('height', 'auto');
      const autoHeight = $answer.outerHeight();
      $answer.css('height', 0).animate({height: autoHeight}, 300, function() {
        $answer.css('height', '');
        $answer.css('opacity', 1);
      });
    }
  });
  
  // URLハッシュに基づいてFAQカテゴリとアイテムを開く
  function handleFAQHashNavigation() {
    const hash = window.location.hash;
    
    if (hash) {
      // カテゴリへのナビゲーション（例：#equipment-faq）
      if (hash.endsWith('-faq')) {
        const category = hash.replace('-faq', '').substring(1);
        $(`.faq-category[data-category="${category}"]`).trigger('click');
      }
      
      // 特定の質問へのナビゲーション（例：#faq-初心者）
      if (hash.startsWith('#faq-')) {
        const questionText = decodeURIComponent(hash.replace('#faq-', ''));
        
        // 該当する質問を探して開く
        $('.question-text').each(function() {
          if ($(this).text().includes(questionText)) {
            // 該当カテゴリを開く
            const category = $(this).closest('.faq-content').attr('id').replace('-faq', '');
            $(`.faq-category[data-category="${category}"]`).trigger('click');
            
            // 質問を開く
            setTimeout(() => {
              $(this).closest('.faq-question').trigger('click');
              
              // スクロール位置の調整
              const targetOffset = $(this).closest('.faq-item').offset().top - 120;
              $('html, body').animate({
                scrollTop: targetOffset
              }, 500);
            }, 300);
          }
        });
      }
    }
  }
  
  // ページロード時にハッシュナビゲーションを処理
  handleFAQHashNavigation();
  
  // ハッシュ変更時にも処理
  $(window).on('hashchange', handleFAQHashNavigation);
  
  // FAQ 項目のキーボードフォーカス管理
  $('.faq-question').attr('tabindex', '0');
});
</script>
<!-- 予約・問い合わせ - 高機能化＆SEO最適化版 -->
<section id="contact" class="contact-section">
  <div class="section-header-container">
    <h2 class="section-header">ご予約・お問い合わせ</h2>
    <p class="section-subtitle">ファンダイビングのご予約やお問い合わせはこちらから</p>
  </div>
  
  <div class="contact-methods">
    <div class="contact-card phone-contact">
      <div class="contact-icon">
        <i class="fas fa-phone-alt"></i>
      </div>
      <h3>お電話でのご予約</h3>
      <p class="phone-number"><a href="tel:0468800835">046-880-0835</a></p>
      <p class="contact-details">受付時間：9:00〜16:00（不定休）</p>
      <p class="contact-note">当日予約も空きがあれば可能です。まずはお気軽にお電話ください。</p>
    </div>
    
    <div class="contact-card web-contact">
      <div class="contact-icon">
        <i class="fas fa-envelope"></i>
      </div>
      <h3>メールフォームでのご予約</h3>
      <p class="contact-details">24時間受付中</p>
      <p class="contact-note">フォームから送信いただければ、24時間以内に返信いたします。</p>
      <div class="contact-action">
        <a href="#booking-form" class="btn-contact web-btn js-scroll-to-form">フォームで予約する</a>
      </div>
    </div>
    
    <!-- LINE予約機能の追加 -->
    <div class="contact-card line-contact">
      <div class="contact-icon">
        <i class="fab fa-line"></i>
      </div>
      <h3>LINEでのご予約</h3>
      <p class="contact-details">友だち追加で簡単予約</p>
      <p class="contact-note">LINEからの予約やお問い合わせも受け付けています。</p>
      <div class="contact-action">
        <a href="https://line.me/R/ti/p/@miuraumi" class="btn-contact line-btn" target="_blank" rel="noopener">LINEで友だち追加</a>
      </div>
    </div>
  </div>
  
  <!-- 予約の流れ - ビジュアル強化 -->
  <div class="booking-flow">
    <h3>ご予約から当日までの流れ</h3>
    <div class="flow-steps">
      <div class="flow-step">
        <div class="step-number">1</div>
        <div class="step-content">
          <h4>ご予約</h4>
          <p>電話・メールフォーム・LINEのいずれかでご予約ください。日程、希望コース、人数などをお知らせください。</p>
          <div class="step-icon">📱</div>
        </div>
      </div>
      
      <div class="flow-step">
        <div class="step-number">2</div>
        <div class="step-content">
          <h4>予約確定</h4>
          <p>スタッフより予約確定のご連絡をいたします。集合時間や持ち物などの詳細もご案内します。</p>
          <div class="step-icon">📅</div>
        </div>
      </div>
      
      <div class="flow-step">
        <div class="step-number">3</div>
        <div class="step-content">
          <h4>当日</h4>
          <p>指定の時間にショップへお越しください。受付後、器材準備、ブリーフィングを行います。</p>
          <div class="step-icon">🏢</div>
        </div>
      </div>
      
      <div class="flow-step">
        <div class="step-number">4</div>
        <div class="step-content">
          <h4>ダイビング</h4>
          <p>インストラクターがガイドする安全で楽しいダイビングをお楽しみください。水中写真も撮影します。</p>
          <div class="step-icon">🤿</div>
        </div>
      </div>
      
      <div class="flow-step">
        <div class="step-number">5</div>
        <div class="step-content">
          <h4>終了</h4>
          <p>ダイビング後はログ付けをし、水中写真のデータをお渡しします。次回の予定もご相談ください。</p>
          <div class="step-icon">📸</div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- 予約フォーム - アクセシビリティ向上 -->
  <div id="booking-form" class="booking-form-container">
    <h3>予約・お問い合わせフォーム</h3>
    <p class="form-intro">下記フォームに必要事項をご記入の上、送信してください。<br>通常24時間以内にご返信いたします。</p>
    
    <div class="booking-form">
      <?php echo do_shortcode('[contact-form-7 id="8227ee1" title="コンタクトフォーム 1"]'); ?>
    </div>
  </div>
  
  <!-- アクセスマップ - 新規追加 -->
  <div class="access-map-section">
    <h3>アクセス情報</h3>
    <div class="access-map-container">
      <div class="map-wrapper">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3262.2144633303583!2d139.61097915196197!3d35.15127178890279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60183d437b46dd8b%3A0x3b48336047ed3d4c!2z5LiJ5rWmIOa1t-OBruWtpuagoSAvIEFxdWFCaXQgTEFC!5e0!3m2!1sja!2sjp!4v1743570189719!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="三浦海の学校のGoogleマップ"></iframe>
      </div>
      <div class="access-info">
        <div class="access-detail">
          <div class="access-icon">📍</div>
          <div class="access-text">
            <h4>住所</h4>
            <p>〒238-0224 神奈川県三浦市三崎町諸磯1621</p>
          </div>
        </div>
        <div class="access-detail">
          <div class="access-icon">🚗</div>
          <div class="access-text">
            <h4>お車でお越しの場合</h4>
            <p>横浜横須賀道路 林ICから約30分</p>
          </div>
        </div>
        <div class="access-detail">
          <div class="access-icon">🚈</div>
          <div class="access-text">
            <h4>電車でお越しの場合</h4>
            <p>京急線三崎口駅からバスで約15分</p>
          </div>
        </div>
        <div class="access-detail">
          <div class="access-icon">🅿️</div>
          <div class="access-text">
            <h4>駐車場</h4>
            <p>店舗前に有料駐車場あり（10台）</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<script>
jQuery(document).ready(function($) {
  // フォームへのスクロール
  $('.js-scroll-to-form').on('click', function(e) {
    e.preventDefault();
    
    const target = $($(this).attr('href'));
    
    $('html, body').animate({
      scrollTop: target.offset().top - 100
    }, 800);
    
    // フォームの最初の入力欄にフォーカス
    setTimeout(function() {
      target.find('input:visible').first().focus();
    }, 850);
  });
  
  // フォームバリデーション強化
  function enhanceFormValidation() {
    // 電話番号のフォーマットチェック
    $('input[type="tel"]').on('blur', function() {
      const phoneVal = $(this).val();
      if (phoneVal && !phoneVal.match(/^[0-9\-\+\s\(\)]{10,}$/)) {
        $(this).addClass('validation-warning');
        
        if (!$(this).next('.custom-validation-message').length) {
          $(this).after('<span class="custom-validation-message">電話番号の形式が正しくないようです</span>');
        }
      } else {
        $(this).removeClass('validation-warning');
        $(this).next('.custom-validation-message').remove();
      }
    });
    
    // メールアドレスのフォーマットチェック
    $('input[type="email"]').on('blur', function() {
      const emailVal = $(this).val();
      if (emailVal && !emailVal.match(/^[\w\-\.\+]+@([\w-]+\.)+[\w-]{2,}$/)) {
        $(this).addClass('validation-warning');
        
        if (!$(this).next('.custom-validation-message').length) {
          $(this).after('<span class="custom-validation-message">メールアドレスの形式が正しくないようです</span>');
        }
      } else {
        $(this).removeClass('validation-warning');
        $(this).next('.custom-validation-message').remove();
      }
    });
  }
  
  // Contact Form 7の読み込みが完了したらバリデーション強化を適用
  if (typeof wpcf7 !== 'undefined') {
    enhanceFormValidation();
  } else {
    $(document).on('wpcf7init', function() {
      enhanceFormValidation();
    });
  }
  
  // アクセス情報の拡張
  $('.access-detail').on('click', function() {
    const accessType = $(this).find('h4').text();
    
    // Google Maps方向案内を開く
    if (accessType === '住所') {
      window.open('https://www.google.com/maps/dir/?api=1&destination=三浦海の学校', '_blank');
    }
  });
  
  // 現在営業中かどうかを判定して表示
  function updateBusinessHours() {
    const now = new Date();
    const hours = now.getHours();
    const isOpen = hours >= 9 && hours < 16;
    
    // 営業状況の更新
    if ($('.business-hours-status').length === 0) {
      $('.phone-number').after('<p class="business-hours-status"></p>');
    }
    
    if (isOpen) {
      $('.business-hours-status').html('<span class="status-open">ただいま営業中</span>');
    } else {
      $('.business-hours-status').html('<span class="status-closed">現在は営業時間外です</span>');
    }
  }
  
  // 初期化時に営業状況を更新
  updateBusinessHours();
  
  // 1分ごとに更新
  setInterval(updateBusinessHours, 60000);
  
  // フォーム送信の成功・エラーメッセージをカスタマイズ
  $(document).on('wpcf7submit', function(event) {
    setTimeout(function() {
      // 送信成功時
      $('.wpcf7-response-output.wpcf7-mail-sent-ok').each(function() {
        $(this).html('<div class="success-icon">✓</div><div class="success-message">お問い合わせありがとうございます！<br>24時間以内にご返信いたします。</div>');
        $(this).closest('form').find('input, textarea').val('');
      });
      
      // 送信エラー時
      $('.wpcf7-response-output.wpcf7-validation-errors').each(function() {
        $(this).html('<div class="error-icon">!</div><div class="error-message">入力内容に問題があります。<br>赤色の項目をご確認ください。</div>');
      });
    }, 100);
  });
});
</script>

<!-- フッター上CTA - 改良版（四角形・高コントラスト） -->
<section class="footer-cta">
  <!-- 波形を四角形に変更 -->
  <div class="cta-rectangle"></div>
  <div class="cta-content">
    <h2 class="cta-heading">三浦の海で素敵なダイビング体験を</h2>
    <p class="cta-description">透明度抜群の海、豊富な生物、東京・横浜から日帰りアクセス。<br>初心者からベテランまで、三浦海の学校で思い出に残るダイビングを。</p>
    <div class="cta-buttons">
      <a href="#contact" class="btn-cta primary-cta">今すぐ予約する</a>
      <a href="tel:0468800835" class="btn-cta secondary-cta">お電話で予約</a>
    </div>
    <div class="social-links">
      <a href="https://www.facebook.com/miuradivingjp" class="social-link" target="_blank" rel="noopener" aria-label="Facebookページ"><i class="fab fa-facebook-f"></i></a>
      <a href="https://www.instagram.com/miuradivingjp" class="social-link" target="_blank" rel="noopener" aria-label="Instagramアカウント"><i class="fab fa-instagram"></i></a>
      <a href="https://line.me/R/ti/p/@miuraumi" class="social-link" target="_blank" rel="noopener" aria-label="LINE公式アカウント"><i class="fab fa-line"></i></a>
    </div>
  </div>
</section>

<script>
/* スクロールトップ機能 */
jQuery(document).ready(function($) {
  if ($('.scroll-to-top').length === 0) {
    $('body').append('<a href="#" class="scroll-to-top" aria-label="ページトップへ戻る"><i class="fas fa-chevron-up"></i></a>');
  }
  
  $(window).scroll(function() {
    if ($(this).scrollTop() > 300) {
      $('.scroll-to-top').addClass('show');
    } else {
      $('.scroll-to-top').removeClass('show');
    }
  });
  
  $('.scroll-to-top').click(function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop: 0}, 800);
    return false;
  });
});
</script>


<!-- フッターリンク修正用スクリプト -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // フッターリンクのクリックイベントを強制的に有効化
  document.querySelectorAll('.footer-links a, .footer-col a, .privacy-link a').forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.stopPropagation();
      window.location.href = this.getAttribute('href');
    });
  });
});
</script>

<!-- カスタムフッター - Appleスタイル -->
<footer class="apple-footer">
    <div class="footer-container">
        <div class="footer-top">
            <div class="footer-col">
                <h3 class="footer-title">三浦 海の学校</h3>
                <p>PADI公認５スターIDCセンター</p>
                <p>神奈川県三浦市の首都圏最大級ダイビングセンター</p>
                <p>専用ダイビングプール完備、日帰りでPADIライセンス取得可能</p>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">サービス</h3>
                <ul class="footer-links">
                    <li><a href="https://miura-diving.com/diving-license/">ダイビングライセンス取得</a></li>
                    <li><a href="https://miura-diving.com/fun-diving/">ファンダイビング</a></li>
                    <li><a href="https://miura-diving.com/marine-activity/">マリンアクティビティ</a></li>
                    <li><a href="https://miura-diving.com/udemy/">Udemy講座 / 出版書籍</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">お問い合わせ</h3>
                <p><strong>所在地:</strong> 〒238-0224 神奈川県三浦市三崎町諸磯1621</p>
                <p><strong>電話:</strong> 046-880-0835</p>
                <p><strong>メール:</strong> info@miura-diving.com</p>
                <p><strong>営業時間:</strong> 8:00〜16:00　不定休</p>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">AquaBit LAB</h3>
                <ul class="footer-links">
                    <li><a href="https://miura-diving.com/udemy/">AI活用術講座</a></li>
                    <li><a href="https://miura-diving.com/blog/">海とテクノロジーブログ</a></li>
                    <li><a href="https://miura-diving.com/contact">お問い合わせ</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="copyright">Copyright © 2025 AquaBit LAB All Rights Reserved.</p>
            <p class="privacy-link"><a href="https://miura-diving.com/privacy/">プライバシーポリシー</a></p>
        </div>
    </div>
</footer>

<!-- フッターリンク修正用スクリプト -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // フッターリンクのクリックイベントを強制的に有効化
  document.querySelectorAll('.footer-links a, .footer-col a, .privacy-link a').forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.stopPropagation();
      window.location.href = this.getAttribute('href');
    });
  });
});
</script>
