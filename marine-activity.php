<?php
/**
 * Template Name: マリンアクティビティ
 */
get_header(); ?>

<!-- ヒーローセクション -->
<div class="hero-container" style="position: relative; height: 80vh; max-height: 700px; overflow: hidden;">
  <div class="hero-slide" style="background-image: url('https://miura-diving.com/wp-content/uploads/名称未設定のデザイン-32.png'); background-size: cover; background-position: center; height: 100%; position: relative;">
    <div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.5));">
      <div class="hero-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #fff; width: 90%; max-width: 800px;">
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">三浦の海で最高の思い出を</h1>
        <p style="font-size: 1.5rem; margin-bottom: 2rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">東京からおよそ90分。神奈川県三浦半島で体験する<br>感動のマリンアクティビティ</p>
        <div class="hero-buttons">
          <a href="#activities" class="btn-primary" style="display: inline-block; background-color: #1e73be; color: #fff; padding: 15px 30px; font-size: 1.2rem; border-radius: 4px; text-decoration: none; margin: 0 10px; transition: all 0.3s;">アクティビティを見る</a>
          <a href="#contact" class="btn-secondary" style="display: inline-block; background-color: rgba(255,255,255,0.2); border: 2px solid #fff; color: #fff; padding: 15px 30px; font-size: 1.2rem; border-radius: 4px; text-decoration: none; margin: 0 10px; transition: all 0.3s;">予約する</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- スクロールダウンアイコン -->
  <div class="scroll-down" style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); text-align: center; color: #fff;">
    <span style="display: block; margin-bottom: 10px; font-size: 14px;">スクロールして詳細を見る</span>
    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M12 5V19M12 19L5 12M12 19L19 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>
</div>

<!-- ページ内ナビゲーション -->
<div class="page-nav" style="background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100;">
  <div class="page-nav-container container" style="display: flex; justify-content: center; padding: 15px 0;">
    <a href="#about" style="margin: 0 15px; text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s;">アクティビティとは</a>
    <a href="#activities" style="margin: 0 15px; text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s;">アクティビティ</a>
    <a href="#features" style="margin: 0 15px; text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s;">選ばれる理由</a>
    <a href="#voice" style="margin: 0 15px; text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s;">お客様の声</a>
    <a href="#faq" style="margin: 0 15px; text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s;">よくある質問</a>
    <a href="#contact" style="margin: 0 15px; text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s;">予約する</a>
  </div>
</div>

<div id="content" class="content cf" style="margin-top: 0;">
  <div id="main" class="main_wide cf" role="main">
    <article id="post-<?php the_ID(); ?>" <?php post_class('article cf'); ?>>
      <div class="entry-content">
      
      <!-- SEO対策用のメタデータ -->
<meta itemprop="headline" content="三浦半島で最高の思い出を作るマリンアクティビティ | 神奈川県三浦市">
<meta itemprop="description" content="東京から約90分。神奈川県三浦半島で体験する感動のマリンアクティビティ。スノーケリング、SUP、シーカヤックなど、初心者からお子様連れまで楽しめる海のレジャー。">
<meta itemprop="keywords" content="三浦 マリンアクティビティ, スノーケリング, SUP, シーカヤック, 神奈川 海遊び, 東京近郊 海">

<!-- 構造化データ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SportsActivityLocation",
  "name": "三浦海の学校 マリンアクティビティ",
  "description": "東京から約90分。神奈川県三浦半島で体験する感動のマリンアクティビティ。",
  "url": "https://miura-diving.com/marine-activity/",
  "telephone": "046-880-0835",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "三浦市",
    "addressRegion": "神奈川県",
    "postalCode": "238-0224",
    "streetAddress": "三崎町諸磯1621",
    "addressCountry": "JP"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "35.1513745",
    "longitude": "139.615763"
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
    "opens": "09:00",
    "closes": "16:00"
  },
  "priceRange": "¥5,500〜"
}
</script>

        <!-- アバウトセクション -->
        <section id="about" class="about-section" style="padding: 80px 0; background-color: #f8f9fa;">
          <div class="container">
            <div class="section-header text-center" style="margin-bottom: 50px;">
              <span class="section-subtitle" style="display: block; font-size: 1.2rem; color: #1e73be; margin-bottom: 15px;">三浦海の学校</span>
              <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #333;">東京から日帰りで楽しむ<br>三浦半島の海の魅力</h2>
              <div class="section-line" style="width: 80px; height: 3px; background-color: #1e73be; margin: 0 auto;"></div>
            </div>
            
            <div class="row" style="display: flex; align-items: center; margin-bottom: 50px;">
              <div class="col-md-6" style="width: 50%; padding: 0 20px;">
                <img src="https://miura-diving.com/wp-content/uploads/名称未設定-800-x-600-px.png" alt="三浦半島の美しい海" style="width: 100%; height: auto; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
              </div>
              <div class="col-md-6" style="width: 50%; padding: 0 20px;">
                <h3 style="font-size: 1.8rem; margin-bottom: 20px; color: #333;">美しい海と豊かな自然体験</h3>
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px; color: #555;">東京から電車で約90分。神奈川県三浦半島は、都心からのアクセスの良さと豊かな自然が魅力のエリアです。透明度の高い青い海と多彩な海洋生物は、一年を通して多くの人々を魅了しています。</p>
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px; color: #555;">三浦海の学校では、経験豊富なPADIコースディレクターが、安全かつ楽しいマリンアクティビティの体験をお届けします。初めての方や泳ぎが苦手な方、お子様連れのファミリーでも安心してご参加いただけます。</p>
                <ul style="list-style-type: none; padding: 0; margin-bottom: 30px;">
                  <li style="font-size: 1.1rem; margin-bottom: 10px; color: #555;">✓ 初心者・お子様大歓迎</li>
                  <li style="font-size: 1.1rem; margin-bottom: 10px; color: #555;">✓ 全てのプログラムにガイド付き</li>
                  <li style="font-size: 1.1rem; margin-bottom: 10px; color: #555;">✓ 必要な器材はすべてレンタル可能</li>
                  <li style="font-size: 1.1rem; margin-bottom: 10px; color: #555;">✓ シャワー・更衣室完備</li>
                </ul>
                <a href="#activities" class="btn-more" style="display: inline-block; padding: 12px 28px; background-color: #1e73be; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 500; transition: all 0.3s;">アクティビティを見る</a>
              </div>
            </div>
          </div>
        </section>
        
        <!-- アクティビティセクション -->
        <section id="activities" class="activities-section" style="padding: 80px 0; background-color: #fff;">
          <div class="container">
            <div class="section-header text-center" style="margin-bottom: 50px;">
              <span class="section-subtitle" style="display: block; font-size: 1.2rem; color: #1e73be; margin-bottom: 15px;">充実のプログラム</span>
              <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #333;">楽しみ方いろいろ！<br>三浦のマリンアクティビティ</h2>
              <div class="section-line" style="width: 80px; height: 3px; background-color: #1e73be; margin: 0 auto;"></div>
              <p class="section-description" style="max-width: 700px; margin: 30px auto 0; font-size: 1.1rem; line-height: 1.7; color: #555;">
                三浦の海を存分に楽しめる、バラエティ豊かなマリンアクティビティをご用意しています。<br>
                初めての方から経験者まで、それぞれのレベルに合わせて楽しめるプログラムです。
              </p>
            </div>
            
            <div class="activities-container">
              <!-- スノーケリング -->
              <div class="activity-card" style="margin-bottom: 60px; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="activity-header" style="position: relative; height: 400px; overflow: hidden;">
                  <img src="https://miura-diving.com/wp-content/uploads/3-2.png" alt="三浦半島でのスノーケリング体験" style="width: 100%; height: 100%; object-fit: cover;">
                  <div class="activity-badge" style="position: absolute; top: 20px; left: 20px; background-color: #1e73be; color: #fff; padding: 8px 15px; border-radius: 4px; font-weight: 500;">人気No.1</div>
                </div>
                <div class="activity-body" style="padding: 30px; background-color: #fff;">
                  <div class="activity-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.8rem; margin: 0; color: #333;">スノーケリング</h3>
                    <div class="activity-price" style="font-size: 1.2rem; font-weight: 700; color: #1e73be;">5,500円/人</div>
                  </div>
                  <p class="activity-description" style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 25px; color: #555;">
                    三浦半島の美しい海中世界を覗いてみませんか？透明度の高い海でカラフルな魚や珍しい海藻など、様々な海の生物に出会えます。初めての方には基本的な呼吸法からしっかりレクチャー。水中で使えるカメラやスマホなどあれば思い出をカタチに残せます。
                  </p>
                  <div class="activity-features" style="display: flex; flex-wrap: wrap; margin-bottom: 25px;">
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">⏱</span>
                      所要時間: 約2時間
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">👥</span>
                      最小催行: 2名〜
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">✓</span>
                      初心者OK
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">👶</span>
                      8歳以上参加可
                    </div>
                  </div>
                  <div class="activity-included" style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                    <h4 style="font-size: 1.1rem; margin-top: 0; margin-bottom: 10px; color: #333;">料金に含まれるもの</h4>
                    <p style="font-size: 1rem; line-height: 1.7; color: #555; margin: 0;">
                      ガイド料、スノーケリングセット、ライフジャケット、保険料、シャワー・更衣室利用
                    </p>
                  </div>
                  <a href="https://miura-diving.com/contact#reservation-form" class="btn-reservation" style="display: inline-block; width: 100%; padding: 15px; background-color: #1e73be; color: #fff; text-align: center; text-decoration: none; border-radius: 4px; font-size: 1.1rem; font-weight: 500; transition: all 0.3s;">予約する</a>
                </div>
              </div>
              
              <!-- SUP -->
              <div class="activity-card" style="margin-bottom: 60px; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="activity-header" style="position: relative; height: 400px; overflow: hidden;">
                  <img src="https://miura-diving.com/wp-content/uploads/2-2.png" alt="三浦半島でのSUP体験" style="width: 100%; height: 100%; object-fit: cover;">
                  <div class="activity-badge" style="position: absolute; top: 20px; left: 20px; background-color: #27ae60; color: #fff; padding: 8px 15px; border-radius: 4px; font-weight: 500;">初心者おすすめ</div>
                </div>
                <div class="activity-body" style="padding: 30px; background-color: #fff;">
                  <div class="activity-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.8rem; margin: 0; color: #333;">SUP（スタンドアップパドルボード）</h3>
                    <div class="activity-price" style="font-size: 1.2rem; font-weight: 700; color: #1e73be;">5,500円/人</div>
                  </div>
                  <p class="activity-description" style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 25px; color: #555;">
                    今大人気のSUPを三浦の穏やかな海で体験しませんか？立ったままボードを漕ぐ新感覚のマリンスポーツは、バランス感覚を鍛えながら海の景色を一望できる魅力があります。初めての方でも、丁寧な指導ですぐに漕げるようになります！
                  </p>
                  <div class="activity-features" style="display: flex; flex-wrap: wrap; margin-bottom: 25px;">
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">⏱</span>
                      所要時間: 約2時間
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">👥</span>
                      最小催行: 2名〜
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">✓</span>
                      初心者OK
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">👶</span>
                      10歳以上参加可
                    </div>
                  </div>
                  <div class="activity-included" style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                    <h4 style="font-size: 1.1rem; margin-top: 0; margin-bottom: 10px; color: #333;">料金に含まれるもの</h4>
                    <p style="font-size: 1rem; line-height: 1.7; color: #555; margin: 0;">
                      ガイド料、SUPボード・パドル、ライフジャケット、保険料、シャワー・更衣室利用
                    </p>
                  </div>
                  <a href="https://miura-diving.com/contact#reservation-form" class="btn-reservation" style="display: inline-block; width: 100%; padding: 15px; background-color: #1e73be; color: #fff; text-align: center; text-decoration: none; border-radius: 4px; font-size: 1.1rem; font-weight: 500; transition: all 0.3s;">予約する</a>
                </div>
              </div>
              
              <!-- シーカヤック -->
              <div class="activity-card" style="margin-bottom: 60px; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="activity-header" style="position: relative; height: 400px; overflow: hidden;">
                  <img src="https://miura-diving.com/wp-content/uploads/1-2.png" alt="三浦半島でのシーカヤック体験" style="width: 100%; height: 100%; object-fit: cover;">
                  <div class="activity-badge" style="position: absolute; top: 20px; left: 20px; background-color: #e67e22; color: #fff; padding: 8px 15px; border-radius: 4px; font-weight: 500;">冒険気分</div>
                </div>
                <div class="activity-body" style="padding: 30px; background-color: #fff;">
                  <div class="activity-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.8rem; margin: 0; color: #333;">シーカヤック</h3>
                    <div class="activity-price" style="font-size: 1.2rem; font-weight: 700; color: #1e73be;">5,500円/人</div>
                  </div>
                  <p class="activity-description" style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 25px; color: #555;">
                    三浦半島の海岸線を冒険しませんか？1人乗りと2人乗りから選べるシーカヤックで、岩場や入江など、陸からは見ることのできない絶景ポイントを巡ります。カップルや親子で一緒に漕ぐ2人乗りカヤックも大人気です！
                  </p>
                  <div class="activity-features" style="display: flex; flex-wrap: wrap; margin-bottom: 25px;">
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">⏱</span>
                      所要時間: 約2時間
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">👥</span>
                      最小催行: 2名〜
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">✓</span>
                      初心者OK
                    </div>
                    <div class="feature-item" style="width: 50%; margin-bottom: 15px; display: flex; align-items: center;">
                      <span style="margin-right: 10px; color: #1e73be;">👶</span>
                      8歳以上参加可
                    </div>
                  </div>
                  <div class="activity-included" style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                    <h4 style="font-size: 1.1rem; margin-top: 0; margin-bottom: 10px; color: #333;">料金に含まれるもの</h4>
                    <p style="font-size: 1rem; line-height: 1.7; color: #555; margin: 0;">
                      ガイド料、カヤック・パドル、ライフジャケット、保険料、シャワー・更衣室利用
                    </p>
                  </div>
                  <a href="https://miura-diving.com/contact#reservation-form" class="btn-reservation" style="display: inline-block; width: 100%; padding: 15px; background-color: #1e73be; color: #fff; text-align: center; text-decoration: none; border-radius: 4px; font-size: 1.1rem; font-weight: 500; transition: all 0.3s;">予約する</a>
                </div>
              </div>
            </div>
          </div>
        </section>
        
        <!-- 特徴セクション -->
        <section id="features" class="features-section" style="padding: 80px 0; background-color: #f8f9fa;">
          <div class="container">
            <div class="section-header text-center" style="margin-bottom: 50px;">
              <span class="section-subtitle" style="display: block; font-size: 1.2rem; color: #1e73be; margin-bottom: 15px;">三浦海の学校が選ばれる理由</span>
              <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #333;">安心・安全のマリンアクティビティ</h2>
              <div class="section-line" style="width: 80px; height: 3px; background-color: #1e73be; margin: 0 auto;"></div>
            </div>
            
            <div class="features-grid" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
              <!-- 特徴1 -->
              <div class="feature-card" style="width: 33.333%; padding: 0 15px; margin-bottom: 30px;">
                <div class="feature-inner" style="background-color: #fff; border-radius: 8px; padding: 30px; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                  <div class="feature-icon" style="margin-bottom: 20px; font-size: 40px; color: #1e73be;">✓</div>
                  <h3 style="font-size: 1.3rem; margin-bottom: 15px; color: #333;">PADIコースディレクターによる直接指導</h3>
                  <p style="font-size: 1rem; line-height: 1.7; color: #555;">国際的に認められたPADIコースディレクターが直接指導。経験豊富なインストラクターが安全管理と技術指導を徹底します。</p>
                </div>
              </div>
              
              <!-- 特徴2 -->
              <div class="feature-card" style="width: 33.333%; padding: 0 15px; margin-bottom: 30px;">
                <div class="feature-inner" style="background-color: #fff; border-radius: 8px; padding: 30px; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                  <div class="feature-icon" style="margin-bottom: 20px; font-size: 40px; color: #1e73be;">👪</div>
                  <h3 style="font-size: 1.3rem; margin-bottom: 15px; color: #333;">初心者・お子様向けの安心設計</h3>
                  <p style="font-size: 1rem; line-height: 1.7; color: #555;">マリンスポーツが初めての方やお子様でも安心して参加できるよう、丁寧な説明と少人数制のプログラムをご用意しています。</p>
                </div>
              </div>
              
              <!-- 特徴3 -->
              <div class="feature-card" style="width: 33.333%; padding: 0 15px; margin-bottom: 30px;">
                <div class="feature-inner" style="background-color: #fff; border-radius: 8px; padding: 30px; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                  <div class="feature-icon" style="margin-bottom: 20px; font-size: 40px; color: #1e73be;">🌏</div>
                  <h3 style="font-size: 1.3rem; margin-bottom: 15px; color: #333;">都心から日帰りアクセス</h3>
                  <p style="font-size: 1rem; line-height: 1.7; color: #555;">東京駅から電車で約90分。抜群のアクセスで、都心からの日帰りでも充実した海のレジャーが楽しめます。</p>
                </div>
              </div>
            </div>
          </div>
        </section>
        
        <!-- お客様の声 -->
        <section id="voice" class="testimonial-section" style="padding: 80px 0; background-color: #fff;">
          <div class="container">
            <div class="section-header text-center" style="margin-bottom: 50px;">
              <span class="section-subtitle" style="display: block; font-size: 1.2rem; color: #1e73be; margin-bottom: 15px;">参加者の声</span>
              <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #333;">実際に体験された方の感想</h2>
              <div class="section-line" style="width: 80px; height: 3px; background-color: #1e73be; margin: 0 auto;"></div>
            </div>
            
            <div class="testimonial-slider" style="max-width: 800px; margin: 0 auto;">
              <!-- 感想1 -->
              <div class="testimonial-item" style="background-color: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 30px;">
                <div class="testimonial-content" style="position: relative; padding-left: 40px; margin-bottom: 20px;">
                  <span style="position: absolute; top: 0; left: 0; color: #1e73be; opacity: 0.3; font-size: 30px;">"</span>
                  <p style="font-size: 1.1rem; line-height: 1.8; color: #555; font-style: italic;">家族でSUPを体験しました。子供たちは初めは怖がっていましたが、優しいインストラクターさんのおかげですぐに上達！最後には立ってパドルを漕げるようになり、子供たちも大喜びでした。安全管理も徹底されていて安心して楽しめました。</p>
                </div>
                <div class="testimonial-author" style="display: flex; align-items: center;">
                  <div class="author-info">
                    <h4 style="font-size: 1.1rem; margin: 0 0 5px; color: #333;">田中さん家族</h4>
                    <p style="font-size: 0.9rem; margin: 0; color: #777;">SUP体験</p>
                  </div>
                </div>
              </div>
              
              <!-- 感想2 -->
              <div class="testimonial-item" style="background-color: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 30px;">
                <div class="testimonial-content" style="position: relative; padding-left: 40px; margin-bottom: 20px;">
                  <span style="position: absolute; top: 0; left: 0; color: #1e73be; opacity: 0.3; font-size: 30px;">"</span>
                  <p style="font-size: 1.1rem; line-height: 1.8; color: #555; font-style: italic;">大学のサークルで20人ほどでお邪魔しました。スノーケリングとシーカヤックを半日ずつ体験。普段見ることのできない海の中の世界に感動！カヤックでは岩場の洞窟まで連れて行ってもらい、探検気分も味わえました。団体割引もあり、学生にはとてもありがたかったです。</p>
                </div>
                <div class="testimonial-author" style="display: flex; align-items: center;">
                  <div class="author-info">
                    <h4 style="font-size: 1.1rem; margin: 0 0 5px; color: #333;">大学サークル代表 佐藤さん</h4>
                    <p style="font-size: 0.9rem; margin: 0; color: #777;">スノーケリング・シーカヤック体験</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
        <!-- よくある質問 -->
        <section id="faq" class="faq-section" style="padding: 80px 0; background-color: #f8f9fa;">
          <div class="container">
            <div class="section-header text-center" style="margin-bottom: 50px;">
              <span class="section-subtitle" style="display: block; font-size: 1.2rem; color: #1e73be; margin-bottom: 15px;">疑問解決</span>
              <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px; color: #333;">よくある質問</h2>
              <div class="section-line" style="width: 80px; height: 3px; background-color: #1e73be; margin: 0 auto;"></div>
            </div>
            
            <div class="faq-container" style="max-width: 800px; margin: 0 auto;">
              <!-- 質問1 -->
              <div class="faq-item" style="margin-bottom: 20px; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <div class="faq-question" style="padding: 20px; background-color: #f8f9fa; cursor: pointer; position: relative;">
                  <h3 style="font-size: 1.2rem; margin: 0; color: #333; padding-right: 30px;">泳げなくても参加できますか？</h3>
                  <span style="position: absolute; top: 20px; right: 20px;">▼</span>
                </div>
                <div class="faq-answer" style="padding: 20px; border-top: 1px solid #f1f1f1;">
                  <p style="font-size: 1rem; line-height: 1.7; color: #555; margin: 0;">はい、参加いただけます。全てのアクティビティでライフジャケットを着用していただくため、泳げない方でも安心です。ただし、スノーケリングは水中に顔をつけるため、水に対する抵抗感が少ない方に向いています。SUPやカヤックは水に顔をつける必要がないので、泳げない方にもおすすめです。</p>
                </div>
              </div>
              
              <!-- 質問2 -->
              <div class="faq-item" style="margin-bottom: 20px; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <div class="faq-question" style="padding: 20px; background-color: #f8f9fa; cursor: pointer; position: relative;">
                  <h3 style="font-size: 1.2rem; margin: 0; color: #333; padding-right: 30px;">子どもも参加できますか？</h3>
                  <span style="position: absolute; top: 20px; right: 20px;">▼</span>
                </div>
                <div class="faq-answer" style="padding: 20px; border-top: 1px solid #f1f1f1;">
                  <p style="font-size: 1rem; line-height: 1.7; color: #555; margin: 0;">スノーケリングとシーカヤックは8歳以上、SUPは10歳以上のお子様から参加可能です。ただし、小学生のお子様は保護者の同伴が必要です。中学生以上は保護者の同意があれば単独での参加も可能です。また、お子様用のライフジャケットも各サイズご用意しております。</p>
                </div>
              </div>
              
              <!-- 質問3 -->
              <div class="faq-item" style="margin-bottom: 20px; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <div class="faq-question" style="padding: 20px; background-color: #f8f9fa; cursor: pointer; position: relative;">
                  <h3 style="font-size: 1.2rem; margin: 0; color: #333; padding-right: 30px;">天候が悪い場合はどうなりますか？</h3>
                  <span style="position: absolute; top: 20px; right: 20px;">▼</span>
                </div>
                <div class="faq-answer" style="padding: 20px; border-top: 1px solid #f1f1f1;">
                  <p style="font-size: 1rem; line-height: 1.7; color: #555; margin: 0;">安全第一を考え、荒天時や波が高い場合はツアーを中止する場合があります。中止の判断は前日の夕方までに行い、ご連絡いたします。当日の天候判断による中止の場合は、日程変更または全額返金いたします。小雨程度であれば実施することもありますので、事前にお問い合わせください。</p>
                </div>
              </div>
              
              <!-- 質問4 -->
              <div class="faq-item" style="margin-bottom: 20px; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <div class="faq-question" style="padding: 20px; background-color: #f8f9fa; cursor: pointer; position: relative;">
                  <h3 style="font-size: 1.2rem; margin: 0; color: #333; padding-right: 30px;">予約のキャンセルはできますか？</h3>
                  <span style="position: absolute; top: 20px; right: 20px;">▼</span>
                </div>
                <div class="faq-answer" style="padding: 20px; border-top: 1px solid #f1f1f1;">
                  <p style="font-size: 1rem; line-height: 1.7; color: #555; margin: 0;">15日前までは無料 ・14日～7日前：料金の30% ・6日～2日前：料金の40% ・前日：料金の80% ・当日：料金の100%
※海況不良や体調不良の場合は、日程変更を無料で承ります。</p>
                </div>
              </div>
            </div>
          </div>
        </section>
        
<!-- お問い合わせ -->
<section id="contact" class="contact-section" style="padding: 50px 0; background-color: #fff;">
  <div class="container" style="max-width: 900px; margin: 0 auto; text-align: center;">
    <div class="section-header text-center" style="margin-bottom: 30px;">
      <span class="section-subtitle" style="display: block; font-size: 1.2rem; color: #1e73be; margin-bottom: 10px;">さあ、海へ出かけよう</span>
      <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 15px; color: #333;">ご予約・お問い合わせ</h2>
      <div class="section-line" style="width: 80px; height: 3px; background-color: #1e73be; margin: 10px auto;"></div>
      <p class="section-description" style="max-width: 700px; margin: 20px auto 0; font-size: 1.1rem; line-height: 1.6; color: #555;">
        ご予約やご質問は、下記フォームまたはお電話にてお気軽にお問い合わせください。<br>
        海にでている時はお電話に出られない場合がありますのでご了承ください。
      </p>
    </div>
  </div>
</section>

</section>
    <div class="contact-container" style="display: flex; flex-wrap: wrap; justify-content: space-between; margin: 0 -10px; overflow-x: auto;">
      
      <div class="contact-info" style="flex: 1; min-width: 300px; max-width: 33.333%; padding: 0 10px;">
        <div class="info-card" style="background-color: #f8f9fa; border-radius: 8px; padding: 30px; height: 100%;">
          <h3 style="font-size: 1.3rem; margin-bottom: 15px; color: #333;">メールでのお問い合わせ</h3>
          <p style="font-size: 1rem; line-height: 1.7; color: #555; margin-bottom: 15px;">お問い合わせフォームから24時間受付中です。翌営業日までにご返信いたします。</p>
          <p style="text-align: center; margin-top: 15px;">
            <a href="https://miura-diving.com/contact" style="display:inline-block; background-color:#1e73be; color:white; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold; transition:all 0.3s ease;">
              お問い合わせページへ
            </a>
          </p>
        </div>
      </div>

      <div class="contact-info" style="flex: 1; min-width: 300px; max-width: 33.333%; padding: 0 10px;">
        <div class="info-card" style="background-color: #f8f9fa; border-radius: 8px; padding: 30px; height: 100%;">
          <h3 style="font-size: 1.3rem; margin-bottom: 15px; color: #333;">お電話でのお問い合わせ</h3>
          <p style="font-size: 1rem; line-height: 1.7; color: #555; margin-bottom: 15px;">ご不明点はお気軽にお電話ください。予約状況の確認もこちらで可能です。</p>
          <a href="tel:046-880-0835" style="color: #1e73be; text-decoration: none; font-weight: 500; font-size: 1.2rem;">046-880-0835</a>
          <p style="font-size: 0.9rem; color: #777; margin-top: 10px;">受付時間: 9:00〜16:00（不定休）</p>
        </div>
      </div>

      <div class="contact-info" style="flex: 1; min-width: 300px; max-width: 33.333%; padding: 0 10px;">
        <div class="info-card" style="background-color: #f8f9fa; border-radius: 8px; padding: 30px; height: 100%;">
          <h3 style="font-size: 1.3rem; margin-bottom: 15px; color: #333;">アクセス</h3>
          <p style="font-size: 1rem; line-height: 1.7; color: #555; margin-bottom: 15px;">
            〒238-0224<br>
            神奈川県三浦市三崎町諸磯1621<br><br>
            京急線三崎口駅からバスで約20分<br>
            天神町バス停から徒歩で約15分<br>
            駐車場完備（有料：1,100円）
          </p>
        </div>
      </div>
    </div>
  </div>
</section>



        
<!-- アクセスマップ -->
<section class="map-section" style="padding: 0 0 80px;">
  <div class="container">
    <div class="map-container" style="height: 400px; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3262.2144633303583!2d139.61097915196197!3d35.15127178890279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60183d437b46dd8b%3A0x3b48336047ed3d4c!2z5LiJ5rWmIOa1t-OBruWtpuagoSAvIEFxdWFCaXQgTEFC!5e0!3m2!1sja!2sjp!4v1743570189719!5m2!1sja!2sjp" 
        width="100%" 
        height="400" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
      </iframe>
    </div>
  </div>
</section>

        
        <!-- JavaScript コード -->
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            // FAQ開閉処理
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
              question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('span');
                if (answer.style.display === 'none' || answer.style.display === '') {
                  answer.style.display = 'block';
                  icon.textContent = '▲';
                } else {
                  answer.style.display = 'none';
                  icon.textContent = '▼';
                }
              });
            });
            
            // 最初のFAQを開いておく
            if (faqQuestions.length > 0) {
              const firstAnswer = faqQuestions[0].nextElementSibling;
              const firstIcon = faqQuestions[0].querySelector('span');
              firstAnswer.style.display = 'block';
              firstIcon.textContent = '▲';
            }
            
            // スムーススクロール
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
              anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                  behavior: 'smooth'
                });
              });
            });
          });
        </script>
      </div><!-- .entry-content -->
    </article>
  </div><!-- #main -->
</div><!-- #content -->

<!-- モバイル対応の強化 -->
<style>
  /* スマートフォン向け調整 (768px以下) */
  @media screen and (max-width: 768px) {
    /* ヒーローセクション調整 */
    .hero-content h1 {
      font-size: 2rem !important;
      margin-bottom: 0.5rem !important;
    }
    
    .hero-content p {
      font-size: 1.1rem !important;
      margin-bottom: 1.5rem !important;
    }
    
    .hero-buttons {
      flex-direction: column !important;
    }
    
    .hero-buttons a {
      display: block !important;
      margin: 10px auto !important;
      width: 80% !important;
    }
    
    /* ナビゲーション調整 */
    .page-nav-container {
      flex-wrap: wrap !important;
      justify-content: center !important;
      padding: 10px 0 !important;
    }
    
    .page-nav-container a {
      margin: 5px 8px !important;
      font-size: 0.9rem !important;
    }
    
    /* レイアウト調整 */
    .row {
      flex-direction: column !important;
    }
    
    .col-md-6 {
      width: 100% !important;
      padding: 0 15px !important;
      margin-bottom: 30px !important;
    }
    
    /* 見出し調整 */
    .section-title {
      font-size: 2rem !important;
    }
    
    .section-subtitle {
      font-size: 1.1rem !important;
    }
    
    /* 特徴カード */
    .feature-card {
      width: 100% !important;
      margin-bottom: 20px !important;
    }
    
    /* アクティビティカード */
    .activity-header {
      height: 250px !important;
    }
    
    .activity-title {
      flex-direction: column !important;
      align-items: flex-start !important;
    }
    
    .activity-price {
      margin-top: 10px !important;
    }
    
    .activity-features .feature-item {
      width: 100% !important;
    }
    
    /* コンタクト情報 */
    .contact-info {
      min-width: 100% !important;
      max-width: 100% !important;
      margin-bottom: 20px !important;
    }
    
    /* MAP */
    .map-container {
      height: 300px !important;
    }
  }
  
  /* タブレット向け調整 (769px〜1024px) */
  @media screen and (min-width: 769px) and (max-width: 1024px) {
    /* ヒーローセクション調整 */
    .hero-content h1 {
      font-size: 3rem !important;
    }
    
    /* 特徴カード */
    .feature-card {
      width: 50% !important;
    }
    
    /* コンタクト情報 */
    .contact-info {
      min-width: 45% !important;
      max-width: 45% !important;
    }
  }
  
  /* タッチデバイス向け調整 */
  @media (hover: none) {
    .btn-primary, .btn-secondary, .btn-more, .btn-reservation {
      padding: 15px 25px !important;  /* タップ領域拡大 */
    }
    
    .page-nav-container a {
      padding: 10px 15px !important;  /* タップ領域拡大 */
    }
    
    .faq-question {
      padding: 25px 20px !important;  /* タップ領域拡大 */
    }
  }
  
  /* ボタンのホバーアニメーション */
  .btn-reservation, .btn-primary {
    transition: all 0.3s ease !important;
  }
  
  .btn-reservation:hover, .btn-primary:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
  }
</style>

<!-- 画像遅延読み込みのスクリプト追加 -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // すべての画像に遅延読み込み属性を追加
    const images = document.querySelectorAll('img:not([loading])');
    images.forEach(img => {
      img.setAttribute('loading', 'lazy');
    });
    
    // スムーススクロールの強化
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 70,
            behavior: 'smooth'
          });
        }
      });
    });
  });
</script>

<?php get_footer(); ?>