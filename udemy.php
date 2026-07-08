<?php
/**
 * Template Name: Udemy講座/出版書籍
 * Description: ABLのUdemy講座と出版書籍を紹介するページ
 */

// SEO対策：ページ固有のメタ情報を設定
$page_title = "【2025年最新】三浦半島発のUdemy講座・出版書籍 | 三浦海の学校";
$page_description = "吉田が制作するダイビング入門やAI活用講座。三浦半島を拠点にした海洋教育と最新スキルを学べるUdemy講座と書籍をご紹介。時間や場所を選ばず学べる新しい学習方法。";
$page_keywords = "ダイビング,Udemy,三浦半島,オンライン講座,AI,PADI,スキルアップ,海洋教育,ダイビングライセンス";

// カスタムヘッダーメタ情報を追加
add_filter('wp_title', function($title) use ($page_title) {
    return $page_title;
}, 10, 1);

add_action('wp_head', function() use ($page_description, $page_keywords) {
    echo '<meta name="description" content="' . esc_attr($page_description) . '">' . "\n";
    echo '<meta name="keywords" content="' . esc_attr($page_keywords) . '">' . "\n";
    echo '<meta property="og:title" content="【2025年最新】三浦半島発のUdemy講座・出版書籍 | 三浦海の学校">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($page_description) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<meta property="og:image" content="https://miura-diving.com/wp-content/uploads/diving-udemy-course-top.jpg">' . "\n";
    echo '<meta property="og:site_name" content="三浦海の学校">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 1);

get_header(); 
?>

<div class="content-wrap udemy-courses-page">
  <!-- ヒーローセクション：注目を引く画像と強力なCTA -->
  <div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container">
      <div class="hero-content">
        <span class="hero-badge">2025年最新コンテンツ</span>
        <h1 class="hero-title">三浦半島発のUdemy講座と書籍</h1>
        <p class="hero-description">吉田が教える海と創造性の世界へようこそ</p>
        <div class="hero-cta">
          <a href="#courses" class="btn btn-primary btn-lg">講座を見る <i class="fas fa-chevron-right"></i></a>
          <a href="#books" class="btn btn-outline-light btn-lg">書籍を見る <i class="fas fa-book"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="container main-content">
    <!-- パンくずリスト：SEO対策とユーザー導線 -->
    <div class="breadcrumb-wrapper">
      <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
          <a itemprop="item" href="<?php echo home_url(); ?>">
            <span itemprop="name">ホーム</span>
          </a>
          <meta itemprop="position" content="1" />
        </li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
          <span itemprop="name">Udemy講座/出版書籍</span>
          <meta itemprop="position" content="2" />
        </li>
      </ol>
    </div>

    <!-- SEOのための概要文 -->
    <div class="intro-section">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <h2 class="intro-title">三浦半島から世界へ - 海と創造性を繋ぐ学び</h2>
          <p class="intro-text">三浦半島を拠点に活動するABLの代表・吉田が制作した<span class="text-highlight">オンライン講座やオリジナル書籍</span>をご紹介します。世界最大級のオンライン学習プラットフォーム「Udemy」での講座や書籍を通じて、新しい知識とスキルを習得できます。</p>
          <p>ダイビングをはじめとする様々なジャンルのコンテンツは、新しいことへの挑戦を応援します。<span class="text-highlight">ダイビングに挑戦する方は、新しいことに挑戦する意欲が高く、学ぶことを楽しむ方が多いという共通点</span>があります。その探究心にお応えするコンテンツをご用意しました。</p>
        </div>
        <div class="col-lg-4">
          <div class="intro-image-wrapper">
            <img src="https://miura-diving.com/wp-content/uploads/tjimage.png" alt="三浦海の学校 代表 吉田" class="img-fluid rounded intro-image">
          </div>
        </div>
      </div>
    </div>
    
    <!-- Udemyの説明セクション -->
    <div class="udemy-intro-section" id="what-is-udemy">
      <div class="section-header">
        <h2 class="section-title"><i class="fas fa-laptop"></i> Udemyとは？</h2>
        <p class="section-subtitle">世界中の1,100万人以上が利用する学習プラットフォーム</p>
      </div>
      <div class="section-content">
        <div class="row align-items-center">
          <div class="col-md-7">
            <div class="feature-list">
              <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-clock"></i></div>
                <div class="feature-text">
                  <h3>好きな時間に、好きな場所で学習可能</h3>
                  <p>いつでもどこでも、自分のペースで学べます。</p>
                </div>
              </div>
              <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-infinity"></i></div>
                <div class="feature-text">
                  <h3>一度購入すれば、生涯アクセス可能</h3>
                  <p>追加料金なしで何度でも視聴できます。</p>
                </div>
              </div>
              <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <div class="feature-text">
                  <h3>あらゆるデバイスで視聴可能</h3>
                  <p>PC、スマートフォン、タブレットに対応。</p>
                </div>
              </div>
              <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-comments"></i></div>
                <div class="feature-text">
                  <h3>講師に直接質問できる</h3>
                  <p>疑問点は質問機能でいつでも解決できます。</p>
                </div>
              </div>
            </div>
            <div class="udemy-intro-cta">
              <p>ABL代表・吉田が制作した講座は、<span class="text-highlight">実際の現場経験と専門知識に基づいた質の高い内容</span>を、お手頃な価格で学ぶことができます。</p>
            </div>
          </div>
          <div class="col-md-5">
            <div class="udemy-image-wrapper">
              <img src="https://miura-diving.com/wp-content/uploads/udemy-new-20212512.jpg" alt="Udemyロゴ" class="img-fluid">
              <div class="udemy-badge">
                <span>ベネッセ公認</span>
              </div>
            </div>
            <div class="udemy-stats">
              <div class="stat-item">
                <span class="stat-number">60,000+</span>
                <span class="stat-label">講師数</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">204,000+</span>
                <span class="stat-label">コース数</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Udemy講座セクション (リニューアル版) -->
    <div class="courses-section compact-layout" id="courses">
      <div class="section-header compact-header">
        <h2 class="section-title"><i class="fas fa-play-circle"></i> 人気のUdemy講座</h2>
        <p class="section-subtitle">三浦半島発の特別割引クーポン付き</p>
      </div>
      <div class="section-content">
        <div class="course-grid">
          <!-- ダイビング講座 -->
          <div class="course-item">
            <div class="course-card">
              <div class="course-badge">ダイビング前に</div>
              <div class="course-image">
                <img src="https://miura-diving.com/wp-content/uploads/名称未設定-400-x-300-px-1.png" alt="ダイビング入門講座" class="img-fluid">
              </div>
              <div class="course-details">
                <h3 class="course-title">初心者向けダイビング入門：プロが教える安全で楽しい始め方完全ガイド</h3>
                <div class="course-meta">
                  <span class="instructor"><i class="fas fa-user"></i> 講師：吉田</span>
                  <span class="badge badge-primary">PADIコースディレクター</span>
                </div>
                <p class="course-description">器材選びからライセンス取得まで、ダイビングを始めるために必要な知識を完全網羅。初心者でも安心して始められる内容です。</p>
                <div class="course-highlights">
                  <div class="highlight-item"><i class="fas fa-check-circle"></i> ダイビングの基礎知識と安全対策</div>
                  <div class="highlight-item"><i class="fas fa-check-circle"></i> 初心者におすすめの器材選び</div>
                </div>
                <div class="course-price">
                  <span class="price-regular">¥12,800</span>
                  <span class="price-sale">¥1,500</span>
                  <span class="price-discount">88%OFF</span>
                </div>
                <div class="course-cta">
                  <a href="https://www.udemy.com/course/start-diving/?couponCode=20250428" class="btn btn-primary btn-block" target="_blank">
                    <i class="fas fa-shopping-cart"></i> 割引クーポンで購入する
                  </a>
                  <div class="coupon-info">
                    <i class="fas fa-tag"></i> クーポンコード：20250428
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- LINEスタンプ講座 -->
          <div class="course-item">
            <div class="course-card">
              <div class="course-badge bg-success">初心者向け</div>
              <div class="course-image">
                <img src="https://miura-diving.com/wp-content/uploads/絵が描けなくても大丈夫-400-x-300-px.png" alt="LINEスタンプ制作講座" class="img-fluid">
              </div>
              <div class="course-details">
                <h3 class="course-title">【はじめての副業にも最適】AI×Canvaで静止画＆動くLINEスタンプ制作</h3>
                <div class="course-meta">
                  <span class="instructor"><i class="fas fa-user"></i> 講師：吉田</span>
                </div>
                <p class="course-description">AIツールとCanvaを活用して、デザインスキル不要でLINEスタンプを作成・販売する方法を学びます。親子で楽しみながら取り組めます。</p>
                <div class="course-highlights">
                  <div class="highlight-item"><i class="fas fa-check-circle"></i> AIを活用したイラスト生成の基礎</div>
                  <div class="highlight-item"><i class="fas fa-check-circle"></i> Canvaでのスタンプデザイン方法</div>
                </div>
                <div class="course-price">
                  <span class="price-regular">¥12,800</span>
                  <span class="price-sale">¥1,500</span>
                  <span class="price-discount">88%OFF</span>
                </div>
                <div class="course-cta">
                  <a href="https://www.udemy.com/course/line_stamp/?couponCode=20250428" class="btn btn-primary btn-block" target="_blank">
                    <i class="fas fa-shopping-cart"></i> 割引クーポンで購入する
                  </a>
                  <div class="coupon-info">
                    <i class="fas fa-tag"></i> クーポンコード：20250428
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- AI作曲講座 -->
          <div class="course-item">
            <div class="course-card">
              <div class="course-badge bg-info">話題のAI</div>
              <div class="course-image">
                <img src="https://miura-diving.com/wp-content/uploads/名称未設定-400-x-300-px.png" alt="AI作曲講座" class="img-fluid">
              </div>
              <div class="course-details">
                <h3 class="course-title">【AI革命】Chat GPT、SUNO AIを使って作詞作曲経験ゼロでもプロ級楽曲が完成！</h3>
                <div class="course-meta">
                  <span class="instructor"><i class="fas fa-user"></i> 講師：吉田</span>
                </div>
                <p class="course-description">最新のAIツールを活用して、音楽経験がなくても素晴らしいオリジナル楽曲を作れる方法を解説。海をテーマにした曲作りにも応用できます。</p>
                <div class="course-highlights">
                  <div class="highlight-item"><i class="fas fa-check-circle"></i> Chat GPTを使った作詞テクニック</div>
                  <div class="highlight-item"><i class="fas fa-check-circle"></i> SUNO AIによる作曲方法</div>
                </div>
                <div class="course-price">
                  <span class="price-regular">¥15,800</span>
                  <span class="price-sale">¥1,500</span>
                  <span class="price-discount">90%OFF</span>
                </div>
                <div class="course-cta">
                  <a href="https://www.udemy.com/course/ai-chatgpt-sunoai/?couponCode=20250428" class="btn btn-primary btn-block" target="_blank">
                    <i class="fas fa-shopping-cart"></i> 割引クーポンで購入する
                  </a>
                  <div class="coupon-info">
                    <i class="fas fa-tag"></i> クーポンコード：20250428
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 講座が増えた場合のページネーション -->
        <div class="courses-pagination">
          <a href="#courses-more" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-play-circle"></i> もっと見る
          </a>
        </div>
      </div>
    </div>

   <!-- 出版書籍セクション（リニューアル版 - 新書籍更新） -->
<div class="books-section compact-layout" id="books">
  <div class="section-header compact-header">
    <h2 class="section-title"><i class="fas fa-book"></i> 出版書籍</h2>
    <p class="section-subtitle">子供から大人まで楽しめる海と創造性の世界</p>
  </div>
  <div class="section-content">
    <div class="book-grid">
      <!-- うみがめになったぜんくんの大冒険 -->
      <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/3-3.png" alt="うみがめになったぜんくんの大冒険" class="img-fluid">
            <div class="book-badge">子供向け</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">うみがめになったぜんくんの大冒険</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <p class="book-description">子供向けの絵本で、環境問題や海洋保全の大切さを伝えます。</p>
            <div class="book-cta">
              <a href="https://amzn.to/4bCuVMq" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- 水中で学ぶマインドフルネス -->
      <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/1-3.png" alt="水中で学ぶマインドフルネス" class="img-fluid">
            <div class="book-badge">大人向け</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">水中で学ぶマインドフルネス</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">ダイビングを通じて得られる心の安らぎと気づきについて解説。</p>
            <div class="book-cta">
              <a href="https://amzn.to/3Dz81ZP" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- おかしだいすきみーちゃん -->
      <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/2-3.png" alt="おかしだいすきみーちゃん" class="img-fluid">
            <div class="book-badge">親子向け</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">おかしだいすきみーちゃん</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="far fa-star"></i>
            </div>
            <p class="book-description">10歳の女の子が描いた心温まる絵本。子供の視点から見た世界。</p>
            <div class="book-cta">
              <a href="https://amzn.to/429vR7C" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- 「私にはムリ・・」から「潜りたい」に変わるダイビングのはじめ方 -->
      <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/ダイビングのはじめ方-1600-x-2560-px.jpg" alt="「私にはムリ・・」から「潜りたい」に変わるダイビングのはじめ方" class="img-fluid">
            <div class="book-badge">ダイビング</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">「私にはムリ・・」から「潜りたい」に変わるダイビングのはじめ方</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="far fa-star"></i>
            </div>
            <p class="book-description">これからダイビングを始めたい方は必見！一番最初に読む本</p>
            <div class="book-cta">
              <a href="https://amzn.to/42PdQMh" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- むずかしく考えなくて大丈夫！ はじめてのセルフ（バディ）ダイビング -->
      <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/セルフダイビン表紙-1.jpg" alt="むずかしく考えなくて大丈夫！ はじめてのセルフ（バディ）ダイビング" class="img-fluid">
            <div class="book-badge">ダイビング</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">むずかしく考えなくて大丈夫！ はじめてのセルフ（バディ）ダイビング</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <p class="book-description">ガイド付きのダイビングも楽しいけど、セルフダイビングという選択肢もあるんですよ。</p>
            <div class="book-cta">
              <a href="https://amzn.to/3GbkUu1" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- 海に一歩、人生にひと花: 「年だから」と言わない60代のためのダイビング入門 -->
      <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/海に一歩.jpg" alt="海に一歩、人生にひと花: 「年だから」と言わない60代のためのダイビング入門" class="img-fluid">
            <div class="book-badge">ダイビング</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">海に一歩、人生にひと花: 「年だから」と言わない60代のためのダイビング入門</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">60代、70代、80代、あるいはそれ以上の「人生の先輩」である皆さまに向けて、「いくつになっても新しい挑戦はできる！」と伝えたい</p>
            <div class="book-cta">
              <a href="https://amzn.to/42S6WG6" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- ブランクダイバー復活ガイド -->
      <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/ブランクダイバー復活ガイド.jpg" alt="ブランクダイバー復活ガイド-半年以上潜っていない人のための “ReActivate” 完全ロードマップ" class="img-fluid">
            <div class="book-badge">新刊</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">ブランクダイバー復活ガイド:半年以上潜っていない人のための “ReActivate” 完全ロードマップ</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">「また潜りたいけど不安…」そんなダイビングから遠ざかっているあなたへ！スキルや情報の不安を解消し、楽しく安全な再開をサポートします。</p>
            <div class="book-cta">
              <a href="https://amzn.to/4kec8Ke" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>

     <!-- ぷかぷか浮かんで水中散歩！ 今日から始めるごきげんスノーケリング -->
     <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/ぷかぷか浮かんで水中散歩-1.jpg" alt="ぷかぷか浮かんで水中散歩！ 今日から始めるごきげんスノーケリング : プロが教える安心安全テクニック＆国内外おすすめスポットガイド" class="img-fluid">
            <div class="book-badge">スノーケリング</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">ぷかぷか浮かんで水中散歩！ 今日から始めるごきげんスノーケリング</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">スノーケリングに興味があるすべての方、特に初心者の方や泳ぎがちょっぴり苦手な方に読んでほしい一冊</p>
            <div class="book-cta">
              <a href="https://amzn.to/42R3Wc0" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>

        <!-- はじめてのSUP（スタンドアップパドルボード） -->
        <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/はじめてのSUP.jpg" alt="はじめてのSUP（スタンドアップパドルボード）: ドキドキの初体験から、ワンちゃんとの水上散歩まで。楽しみながら学ぶ、優しい冒険ブック" class="img-fluid">
            <div class="book-badge">SUP</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">はじめてのSUP（スタンドアップパドルボード）: ドキドキの初体験から、ワンちゃんとの水上散歩まで。楽しみながら学ぶ、優しい冒険ブック</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">SUPに興味があるすべての方、特に初心者の方、そして運動神経やバランス感覚にちょっぴり不安を感じている方に向けて書いています。</p>
            <div class="book-cta">
              <a href="https://amzn.to/3RE7ARb" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>

        <!-- シーカヤック入門 -->
        <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/シーカヤック入門.jpg" alt="大切な人と、海の上で過ごす時間: 家族・仲間と楽しむ、やさしいシーカヤック入門" class="img-fluid">
            <div class="book-badge">シーカヤック</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">大切な人と、海の上で過ごす時間: 家族・仲間と楽しむ、やさしいシーカヤック入門</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">初めてシーカヤックに挑戦する方、そして大切な人と一緒に安全に海を楽しみたい方へ。優しい気持ちでこの入門書を書きました。</p>
            <div class="book-cta">
              <a href="https://amzn.to/4iKscT2" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>

        <!-- 親子で楽しむマリンアクティビティ -->
        <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/親子で楽しむ.jpg" alt="親子で楽しむ！マリンアクティビティ完全ガイド: SUP・シーカヤック・スノーケリングから体験ダイビングまで！プロが教える海の遊び方" class="img-fluid">
            <div class="book-badge">新刊</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">親子で楽しむ！マリンアクティビティ完全ガイド</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">家族でマリンアクティビティを楽しみたい方のための1冊。SUP・シーカヤック・スノーケリングから体験ダイビングまで！プロが教える海の遊び方。</p>
            <div class="book-cta">
              <a href="https://amzn.to/3GIEM80" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>

     <!-- AIは、あなたの「魔法の杖」: ～知識ゼロ・パソコン苦手でも大丈夫！ 今日から使える優しいAI超入門～ -->
     <div class="book-item">
        <div class="book-card">
          <div class="book-image">
            <img src="https://miura-diving.com/wp-content/uploads/AIはあなたの魔法の杖.jpg" alt="AIは、あなたの「魔法の杖」: ～知識ゼロ・パソコン苦手でも大丈夫！ 今日から使える優しいAI超入門～" class="img-fluid">
            <div class="book-badge">AI</div>
          </div>
          <div class="book-details">
            <h3 class="book-title">AIは、あなたの「魔法の杖」: ～知識ゼロ・パソコン苦手でも大丈夫！ 今日から使える優しいAI超入門～</h3>
            <div class="book-rating">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              <i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="book-description">この本は、AIへの「苦手意識」を「面白いかも！」に変える、優しいガイドブックです</p>
            <div class="book-cta">
              <a href="https://amzn.to/3RCEstx" class="btn btn-amazon btn-block" target="_blank">
                <i class="fab fa-amazon"></i> Amazonで見る
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    

    <!-- 書籍が増えたのでページネーションを追加 -->
    <div class="books-pagination">
      <a href="#books-more" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-book"></i> もっと見る
      </a>
    </div>
  </div>
</div>
    
    <!-- オンライン学習のメリットセクション -->
    <div class="benefits-section">
      <div class="section-header compact-header">
        <h2 class="section-title"><i class="fas fa-star"></i> チャレンジ精神を刺激するオンライン学習</h2>
        <p class="section-subtitle">ダイビングと学びに共通する「探究心」</p>
      </div>
      <div class="section-content">
        <div class="benefits-intro">
          <p>ダイビングに挑戦する人は、新しいことへのチャレンジ精神が旺盛で、学びに対する意欲が高い方が多いという特徴があります。そんな探究心旺盛な方におすすめのコンテンツをご用意しました。</p>
        </div>
        <div class="row benefit-grid">
          <div class="col-md-6 col-lg-3 benefit-item">
            <div class="benefit-card">
              <div class="benefit-icon">
                <i class="fas fa-robot"></i>
              </div>
              <h3 class="benefit-title">AI技術で広がる新世界</h3>
              <p class="benefit-description">急速に発展するAI技術を学ぶことは、現代において最もエキサイティングな挑戦の一つ。創作活動や問題解決に革命をもたらすAIツールの活用法を学べます。</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 benefit-item">
            <div class="benefit-card">
              <div class="benefit-icon">
                <i class="fas fa-clock"></i>
              </div>
              <h3 class="benefit-title">いつでもどこでも学べる</h3>
              <p class="benefit-description">実際に三浦半島に来る前の事前学習や、ダイビング後の知識拡充に最適。ライフスタイルに合わせた学習が可能です。</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 benefit-item">
            <div class="benefit-card">
              <div class="benefit-icon">
                <i class="fas fa-brain"></i>
              </div>
              <h3 class="benefit-title">クリエイティブな発想力</h3>
              <p class="benefit-description">AIツールを使った創作活動やLINEスタンプ制作は、新しい発想力を鍛え、日常に創造性をもたらします。</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 benefit-item">
            <div class="benefit-card">
              <div class="benefit-icon">
                <i class="fas fa-lightbulb"></i>
              </div>
              <h3 class="benefit-title">新しい趣味や副業へ</h3>
              <p class="benefit-description">ダイビング、創作活動、AI活用など、あなたの挑戦が新たな趣味や副業、キャリアの可能性を広げます。</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- ユーザーレビュー -->
    <div class="reviews-section">
      <div class="section-header compact-header">
        <h2 class="section-title"><i class="fas fa-comments"></i> 受講生の声</h2>
        <p class="section-subtitle">実際に講座を受けた方々からのフィードバック</p>
      </div>
      <div class="section-content">
        <div class="row review-grid">
          <div class="col-md-4 review-item">
            <div class="review-card">
              <div class="review-top">
                <div class="review-avatar">
                  <img src="https://miura-diving.com/wp-content/uploads/review-avatar-1.jpg" alt="レビュアー1" class="img-fluid">
                </div>
                <div class="review-info">
                  <h4 class="reviewer-name">山田さん</h4>
                  <div class="review-course">ダイビング入門講座</div>
                  <div class="review-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                  </div>
                </div>
              </div>
              <div class="review-content">
                <p>初めてのダイビングに不安がありましたが、この講座のおかげで安心して挑戦できました。事前知識を得ておくことで、実際のレッスンがより充実したものになりました。講師の説明がとても分かりやすかったです。</p>
              </div>
              <div class="review-date">2025年1月</div>
            </div>
          </div>
          <div class="col-md-4 review-item">
            <div class="review-card">
              <div class="review-top">
                <div class="review-avatar">
                  <img src="https://miura-diving.com/wp-content/uploads/review-avatar-2.jpg" alt="レビュアー2" class="img-fluid">
                </div>
                <div class="review-info">
                  <h4 class="reviewer-name">佐藤さん</h4>
                  <div class="review-course">LINEスタンプ制作講座</div>
                  <div class="review-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                  </div>
                </div>
              </div>
              <div class="review-content">
                <p>イラストが苦手な私でも、AIツールとCanvaの組み合わせで素敵なLINEスタンプが作れました！実際に販売までできて、小さな収入も得られています。子供と一緒に楽しく学べたのが最高でした。</p>
              </div>
              <div class="review-date">2024年12月</div>
            </div>
          </div>
          <div class="col-md-4 review-item">
            <div class="review-card">
              <div class="review-top">
                <div class="review-avatar">
                  <img src="https://miura-diving.com/wp-content/uploads/review-avatar-3.jpg" alt="レビュアー3" class="img-fluid">
                </div>
                <div class="review-info">
                  <h4 class="reviewer-name">鈴木さん</h4>
                  <div class="review-course">AI作曲講座</div>
                  <div class="review-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                  </div>
                </div>
              </div>
              <div class="review-content">
                <p>音楽経験がまったくない状態でしたが、この講座を受けて海をテーマにしたオリジナル曲を作ることができました。YouTubeの動画BGMに使用して、視聴者からも好評です。AIの可能性に驚きました！</p>
              </div>
              <div class="review-date">2025年2月</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 挑戦する心と学びのセクション -->
    <div class="connection-section">
      <div class="section-header compact-header">
        <h2 class="section-title"><i class="fas fa-link"></i> 挑戦する心が未来を切り拓く</h2>
        <p class="section-subtitle">ダイビングと学びで広がる可能性</p>
      </div>
      <div class="section-content">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <p>ダイビングに挑戦する方と同じように、新しい技術や知識に挑戦することで、あなたの可能性は大きく広がります。特に今日のAI技術の発展は目覚ましく、学ぶことで様々な創造的活動が可能になっています。</p>
            
            <div class="ai-highlight">
              <h3><i class="fas fa-rocket"></i> なぜ今、AIを学ぶべきなのか</h3>
              <p>現代のAI技術は、数年前には想像もできなかったような創造的活動を可能にしています。文章生成、画像作成、音楽制作などが、専門知識がなくても誰でも挑戦できるようになりました。この革命的な変化の波に乗ることは、新たな可能性を発見する絶好の機会です。</p>
            </div>
            
            <div class="connection-steps">
              <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                  <h3>新しい分野への挑戦</h3>
                  <p>ダイビング、AI、クリエイティブ活動など、あなたの興味に合わせて新しい分野に挑戦</p>
                </div>
              </div>
              <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                  <h3>スキルの習得</h3>
                  <p>Udemy講座で基礎から応用まで、自分のペースで効率的にスキルを習得</p>
                </div>
              </div>
              <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                  <h3>創造と実践</h3>
                  <p>習得したスキルを活かして、創作活動や実践的な挑戦へとステップアップ</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="connection-image-grid">
              <div class="connection-image">
                <img src="https://miura-diving.com/wp-content/uploads/2-4.png" alt="ダイビングシーン1" class="img-fluid">
              </div>
              <div class="connection-image">
                <img src="https://miura-diving.com/wp-content/uploads/4-2.png" alt="AI創作" class="img-fluid">
              </div>
              <div class="connection-image">
                <img src="https://miura-diving.com/wp-content/uploads/1-4.png" alt="ダイビングシーン2" class="img-fluid">
              </div>
              <div class="connection-image">
                <img src="https://miura-diving.com/wp-content/uploads/3-4.png" alt="オンライン学習" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
        
        <div class="cta-banner">
          <div class="cta-content">
            <h3>挑戦する心が未来を創る</h3>
            <p>ダイビングの冒険と同じように、新しい知識とスキルの探求があなたの世界を広げます。吉田が制作したUdemy講座と書籍で、その第一歩を踏み出しませんか？</p>
          </div>
          <div class="cta-buttons">
            <a href="/diving-license" class="btn btn-primary btn-lg">
              <i class="fas fa-water"></i> ダイビングライセンス取得を見る
            </a>
            <a href="https://www.udemy.com/user/ji-tian-zhe-si/" class="btn btn-secondary btn-lg" target="_blank">
              <i class="fas fa-external-link-alt"></i> Udemyで講師プロフィールを見る
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- FAQ セクション -->
    <div class="faq-section" id="faq">
      <div class="section-header compact-header">
        <h2 class="section-title"><i class="fas fa-question-circle"></i> よくある質問</h2>
        <p class="section-subtitle">講座や書籍についてのご質問にお答えします</p>
      </div>
      <div class="section-content">
        <div class="row">
          <div class="col-lg-6">
            <div class="accordion" id="faqAccordion1">
              <div class="faq-card">
                <div class="faq-header" id="faqOne">
                  <h3 class="mb-0">
                    <button class="faq-btn" type="button" data-toggle="collapse" data-target="#collapseOne">
                      <i class="fas fa-plus-circle"></i> Udemyの講座を購入後、いつまで視聴できますか？
                    </button>
                  </h3>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="faqOne" data-parent="#faqAccordion1">
                  <div class="faq-body">
                    <p>Udemyの講座は<span class="highlight">一度購入すると、生涯アクセス可能</span>です。購入後にコンテンツが更新された場合も、追加料金なしで最新版を視聴できます。インターネット環境があれば、いつでもどこでも学習を続けることができます。</p>
                  </div>
                </div>
              </div>
              <div class="faq-card">
                <div class="faq-header" id="faqTwo">
                  <h3 class="mb-0">
                    <button class="faq-btn" type="button" data-toggle="collapse" data-target="#collapseTwo">
                      <i class="fas fa-plus-circle"></i> クーポンコードはいつまで有効ですか？
                    </button>
                  </h3>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="faqTwo" data-parent="#faqAccordion1">
                  <div class="faq-body">
                    <p>クーポンコードは期間限定です。このサイトからお申し込みされる方には<span class="highlight">最安クーポンをご提供</span>しています。期限が切れた場合は、<a href="/contact">お問い合わせフォーム</a>から最新のクーポンコードをお問い合わせください。すぐに対応いたします。</p>
                  </div>
                </div>
              </div>
              <div class="faq-card">
                <div class="faq-header" id="faqThree">
                  <h3 class="mb-0">
                    <button class="faq-btn" type="button" data-toggle="collapse" data-target="#collapseThree">
                      <i class="fas fa-plus-circle"></i> 実際のダイビング体験と組み合わせるとどんなメリットがありますか？
                    </button>
                  </h3>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="faqThree" data-parent="#faqAccordion1">
                  <div class="faq-body">
                    <p>オンライン講座で基礎知識を学んでから実際のダイビングに参加することで、<span class="highlight">より深い理解と効果的な技術習得が可能</span>です。事前に知識を得ておくことで不安が軽減され、実際の体験をより楽しむことができます。また、体験後に再度講座を復習することで、学びを定着させることができます。</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="accordion" id="faqAccordion2">
              <div class="faq-card">
                <div class="faq-header" id="faqFour">
                  <h3 class="mb-0">
                    <button class="faq-btn" type="button" data-toggle="collapse" data-target="#collapseFour">
                      <i class="fas fa-plus-circle"></i> パソコンが苦手でも講座は受講できますか？
                    </button>
                  </h3>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="faqFour" data-parent="#faqAccordion2">
                  <div class="faq-body">
                    <p>はい、パソコンやデジタル機器が苦手な方でも安心して受講いただけます。講座は<span class="highlight">初心者にもわかりやすく丁寧に解説</span>されており、スマートフォンからでも視聴可能です。また、質問機能を使えば、わからないことをいつでも講師に質問できます。</p>
                  </div>
                </div>
              </div>
              <div class="faq-card">
                <div class="faq-header" id="faqFive">
                  <h3 class="mb-0">
                    <button class="faq-btn" type="button" data-toggle="collapse" data-target="#collapseFive">
                      <i class="fas fa-plus-circle"></i> 講座の内容について質問できますか？
                    </button>
                  </h3>
                </div>
                <div id="collapseFive" class="collapse" aria-labelledby="faqFive" data-parent="#faqAccordion2">
                  <div class="faq-body">
                    <p>もちろんです。Udemyの講座には<span class="highlight">質問機能</span>が付いており、講座内容について不明点があれば直接講師に質問することができます。通常1〜2営業日以内に返答がありますので、疑問点をそのままにせず解決できます。</p>
                  </div>
                </div>
              </div>
              <div class="faq-card">
                <div class="faq-header" id="faqSix">
                  <h3 class="mb-0">
                    <button class="faq-btn" type="button" data-toggle="collapse" data-target="#collapseSix">
                      <i class="fas fa-plus-circle"></i> 書籍は電子書籍でも入手できますか？
                    </button>
                  </h3>
                </div>
                <div id="collapseSix" class="collapse" aria-labelledby="faqSix" data-parent="#faqAccordion2">
                  <div class="faq-body">
                    <p>はい、Amazonのリンク先で<span class="highlight">Kindle版</span>も選択できます。電子書籍は紙の本より安価で、すぐに読み始めることができます。また、スマートフォンやタブレットで持ち運びも便利です。お好みの形式でお楽しみください。</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 最終CTA -->
    <div class="final-cta">
      <div class="final-cta-overlay"></div>
      <div class="final-cta-content">
        <h2>新しい挑戦を始めませんか？</h2>
        <p>三浦半島の海の魅力と新しい知識の世界があなたを待っています</p>
        <div class="final-cta-buttons">
          <a href="#courses" class="btn btn-primary btn-lg">講座を見る <i class="fas fa-chevron-right"></i></a>
          <a href="/contact" class="btn btn-outline-light btn-lg">お問い合わせ <i class="fas fa-envelope"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- フッターエリア -->
<footer class="footer-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="footer-widget about-widget">
          <div class="footer-logo">
            <img src="https://miura-diving.com/wp-content/uploads/logo-white.png" alt="三浦海の学校" class="img-fluid">
          </div>
          <p>三浦半島を拠点としたダイビングスクール。美しい海での体験と学びを提供しています。</p>
          <div class="social-icons">
            <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="youtube"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6">
        <div class="footer-widget links-widget">
          <h4 class="widget-title">サービス</h4>
          <ul>
            <li><a href="/fun-diving">ファンダイビング</a></li>
            <li><a href="/diving-license">ライセンス講習</a></li>
            <li><a href="/specialty-diving">スペシャルティコース</a></li>
            <li><a href="/udemy-books">Udemy講座/書籍</a></li>
          </ul>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="footer-widget info-widget">
          <h4 class="widget-title">営業時間</h4>
          <ul class="info-list">
            <li>
              <span class="icon"><i class="far fa-clock"></i></span>
              <span class="text">平日: 9:00～16:00</span>
            </li>
            <li>
              <span class="icon"><i class="far fa-clock"></i></span>
              <span class="text">土日祝: 9:00～16:00</span>
            </li>
            <li>
              <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
              <span class="text">神奈川県三浦市</span>
            </li>
          </ul>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="footer-widget contact-widget">
          <h4 class="widget-title">お問い合わせ</h4>
          <ul class="contact-list">
            <li>
              <span class="icon"><i class="fas fa-phone-alt"></i></span>
              <span class="text">080-4350-0412</span>
            </li>
            <li>
              <span class="icon"><i class="far fa-envelope"></i></span>
              <span class="text">info@miura-diving.com</span>
            </li>
          </ul>
          <div class="newsletter">
            <form action="#">
              <input type="email" placeholder="メールアドレス">
              <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="copyright-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <p>&copy; 2025 三浦海の学校. All rights reserved.</p>
        </div>
        <div class="col-lg-6">
          <ul class="footer-nav">
            <li><a href="/privacy-policy">プライバシーポリシー</a></li>
            <li><a href="/terms">利用規約</a></li>
            <li><a href="/sitemap">サイトマップ</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- 構造化データ：SEO対策 -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    {
      "@type": "Course",
      "position": 1,
      "name": "初心者向けダイビング入門：プロが教える安全で楽しい始め方完全ガイド",
      "description": "器材選びからライセンス取得まで、ダイビングを始めるために必要な知識を完全網羅。PADIコースディレクターの経験を活かし、初心者でも安心して始められる内容です。",
      "provider": {
        "@type": "Organization",
        "name": "三浦海の学校",
        "sameAs": "https://miura-diving.com"
      },
      "url": "https://www.udemy.com/course/start-diving/?couponCode=20250316"
    },
    {
      "@type": "Course",
      "position": 2,
      "name": "【はじめての副業にも最適】AI×Canvaで静止画＆動くLINEスタンプ制作",
      "description": "AIツールとCanvaを活用して、デザインスキル不要でLINEスタンプを作成・販売する方法を学びます。親子で楽しみながら取り組めます。",
      "provider": {
        "@type": "Organization",
        "name": "三浦海の学校",
        "sameAs": "https://miura-diving.com"
      },
      "url": "https://www.udemy.com/course/line_stamp/?couponCode=20250316"
    },
    {
      "@type": "Course",
      "position": 3,
      "name": "【AI革命】Chat GPT、SUNO AIを使って作詞作曲経験ゼロでもプロ級楽曲が完成！",
      "description": "最新のAIツールを活用して、音楽経験がなくても素晴らしいオリジナル楽曲を作れる方法を解説。海をテーマにした曲作りにも応用できます。",
      "provider": {
        "@type": "Organization",
        "name": "三浦海の学校",
        "sameAs": "https://miura-diving.com"
      },
      "url": "https://www.udemy.com/course/ai-chatgpt-sunoai/?couponCode=20250316"
    }
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Udemyの講座を購入後、いつまで視聴できますか？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Udemyの講座は一度購入すると、生涯アクセス可能です。購入後にコンテンツが更新された場合も、追加料金なしで最新版を視聴できます。"
      }
    },
    {
      "@type": "Question",
      "name": "クーポンコードはいつまで有効ですか？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "クーポンコードは期間限定です。しかしこのサイトからお申し込みされる方には最安クーポンをお配りしたいと思っております。期限が切れた場合は、最新のクーポンコードをお問い合わせください。"
      }
    },
    {
      "@type": "Question",
      "name": "実際のダイビング体験と組み合わせるとどんなメリットがありますか？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "オンライン講座で基礎知識を学んでから実際のダイビングに参加することで、より深い理解と効果的な技術習得が可能です。事前に知識を得ておくことで不安が軽減され、実際の体験をより楽しむことができます。また、体験後に再度講座を復習することで、学びを定着させることができます。"
      }
    }
  ]
}
</script>

<!-- CSSスタイル -->
<style>
  /* グローバルスタイル */
  .udemy-courses-page {
    font-family: 'Noto Sans JP', sans-serif;
    color: #333;
    line-height: 1.6;
  }
  
  .udemy-courses-page a:hover {
    text-decoration: none;
  }
  
  .section-header {
    text-align: center;
    margin-bottom: 40px;
  }
  
  .compact-header {
    margin-bottom: 25px;
  }
  
  .section-title {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
    padding-bottom: 10px;
  }
  
  .section-title::after {
    content: '';
    position: absolute;
    width: 60px;
    height: 3px;
    background-color: #3498db;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
  }
  
  .section-subtitle {
    font-size: 16px;
    color: #7f8c8d;
    margin-top: 5px;
  }
  
  .section-content {
    margin-bottom: 40px;
  }
  
  .text-highlight {
    color: #3498db;
    font-weight: 600;
  }
  
  .container.main-content {
    padding-top: 40px;
    padding-bottom: 60px;
  }
  
  .btn-block {
    display: block;
    width: 100%;
  }
  
  /* ヒーローセクション */
  .hero-section {
    position: relative;
    background-image: url('https://miura-diving.com/wp-content/uploads/名称未設定のデザイン-33.png');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 0;
    margin-bottom: 40px;
    text-align: center;
  }
  
  .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(44, 62, 80, 0.7);
  }
  
  .hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
  }
  
  .hero-badge {
    display: inline-block;
    background-color: #e74c3c;
    color: white;
    font-size: 14px;
    font-weight: bold;
    padding: 5px 15px;
    border-radius: 20px;
    margin-bottom: 20px;
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
  
  .hero-title {
    font-size: 42px;
    font-weight: bold;
    margin-bottom: 15px;
  }
  
  .hero-description {
    font-size: 20px;
    margin-bottom: 30px;
    opacity: 0.9;
  }
  
  .hero-cta {
    margin-top: 30px;
  }
  
  .hero-cta .btn {
    margin: 0 10px;
    padding: 12px 30px;
    font-weight: 600;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
  }
  
  .hero-cta .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 8px rgba(0,0,0,0.15);
  }
  
  /* パンくずリスト */
  .breadcrumb-wrapper {
    margin-bottom: 30px;
  }
  
  .breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 0;
  }
  
  .breadcrumb li {
    font-size: 14px;
    color: #95a5a6;
  }
  
  .breadcrumb li a {
    color: #3498db;
  }
  
  .breadcrumb li+li:before {
    content: ">";
    color: #95a5a6;
  }
  
  /* イントロセクション */
  .intro-section {
    margin-bottom: 40px;
    padding: 25px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
  }
  
  .intro-title {
    font-size: 24px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
  }
  
  .intro-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 60px;
    background-color: #3498db;
  }
  
  .intro-text {
    margin-bottom: 15px;
  }
  
  .intro-image-wrapper {
    position: relative;
  }
  
  .intro-image {
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
  }
  
  /* Udemy紹介セクション */
  .udemy-intro-section {
    margin-bottom: 40px;
  }
  
  .feature-list {
    margin-bottom: 20px;
  }
  
  .feature-item {
    display: flex;
    margin-bottom: 15px;
  }
  
  .feature-icon {
    width: 50px;
    height: 50px;
    background-color: #3498db;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 15px;
    flex-shrink: 0;
  }
  
  .feature-text h3 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #2c3e50;
  }
  
  .feature-text p {
    color: #7f8c8d;
    margin-bottom: 0;
  }
  
  .udemy-image-wrapper {
    position: relative;
    margin-bottom: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
  }
  
  .udemy-badge {
    position: absolute;
    top: 15px;
    right: -10px;
    background-color: #e74c3c;
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding: 5px 15px;
    border-radius: 3px;
    z-index: 2;
  }
  
  .udemy-badge:after {
    content: '';
    position: absolute;
    right: 0;
    bottom: -10px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-top: 10px solid #c0392b;
  }
  
  .udemy-stats {
    display: flex;
    justify-content: center;
  }
  
  .udemy-stats .stat-item {
    text-align: center;
    margin: 0 10px;
    flex: 1;
    background-color: white;
    padding: 12px;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
  }
  
  .stat-number {
    display: block;
    font-size: 24px;
    font-weight: bold;
    color: #3498db;
  }
  
  .stat-label {
    font-size: 13px;
    color: #7f8c8d;
  }
  
  .udemy-intro-cta {
    background-color: #ecf0f1;
    padding: 15px;
    border-radius: 5px;
    margin-top: 20px;
    text-align: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
  }

  /* コースグリッド（改良版）*/
  .course-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 0;
  }
  
  .course-item {
    padding: 0;
    margin-bottom: 0;
  }
  
  /* コースカード */
  .course-card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    background-color: white;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  
  .course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }
  
  .course-badge {
    position: absolute;
    top: 10px;
    left: -30px;
    background-color: #3498db;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 5px 30px;
    transform: rotate(-45deg);
    z-index: 3;
  }
  
  .course-image {
    position: relative;
    overflow: hidden;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    aspect-ratio: 1 / 1;
  }
  
  /* aspect-ratioをサポートしていないブラウザのフォールバック */
  @supports not (aspect-ratio: 1 / 1) {
    .course-image::before {
      content: "";
      display: block;
      padding-bottom: 100%;
    }
  }
  
  .course-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
  }
  
  .course-card:hover .course-image img {
    transform: scale(1.05);
  }
  
  .course-details {
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }
  
  .course-title {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #2c3e50;
    line-height: 1.3;
    height: auto;
    min-height: 42px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
  
  .course-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 10px;
    font-size: 12px;
    color: #7f8c8d;
  }
  
  .course-meta .instructor {
    margin-right: 10px;
  }
  
  .course-meta .badge {
    font-size: 10px;
    padding: 3px 6px;
    background-color: #3498db;
    color: white;
  }
  
  .course-description {
    font-size: 13px;
    color: #555;
    margin-bottom: 10px;
    height: auto;
    min-height: 60px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
  }
  
  .course-highlights {
    margin-bottom: 15px;
  }
  
  .highlight-item {
    font-size: 12px;
    color: #555;
    margin-bottom: 5px;
  }
  
  .highlight-item i {
    color: #2ecc71;
    margin-right: 5px;
    font-size: 10px;
  }
  
  .course-price {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
  }
  
  .price-regular {
    font-size: 12px;
    color: #7f8c8d;
    text-decoration: line-through;
    margin-right: 8px;
  }
  
  .price-sale {
    font-size: 18px;
    font-weight: bold;
    color: #2c3e50;
    margin-right: 8px;
  }
  
  .price-discount {
    background-color: #e74c3c;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 3px;
  }
  
  .course-cta .btn {
    font-size: 13px;
    padding: 8px 12px;
    margin-bottom: 8px;
  }
  
  .coupon-info {
    font-size: 11px;
    color: #7f8c8d;
    text-align: center;
  }
  
  .courses-pagination {
    text-align: center;
    margin-top: 20px;
  }
  
  .courses-pagination .btn {
    padding: 5px 15px;
    font-size: 14px;
  }
  
  /* 書籍グリッド（改良版）*/
  .book-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin: 0;
  }
  
  .book-item {
    padding: 0;
    margin-bottom: 0;
  }
  
  /* 書籍カード */
  .book-card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    background-color: white;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  
  .book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
  }
  
  .book-image {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
  }
  
  /* aspect-ratioをサポートしていないブラウザのフォールバック */
  @supports not (aspect-ratio: 1 / 1) {
    .book-image::before {
      content: "";
      display: block;
      padding-bottom: 100%;
    }
  }
  
  .book-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
  }
  
  .book-card:hover .book-image img {
    transform: scale(1.05);
  }
  
  .book-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 3px;
    background-color: #3498db;
    color: white;
    font-weight: bold;
    z-index: 2;
  }
  
  .book-details {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }
  
  .book-title {
    font-size: 15px;
    margin-bottom: 5px;
    line-height: 1.3;
    height: auto;
    min-height: 40px;
    overflow: hidden;
    font-weight: bold;
    color: #2c3e50;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
  
  .book-rating {
    font-size: 11px;
    margin-bottom: 5px;
    color: #f39c12;
  }
  
  .book-description {
    font-size: 12px;
    margin-bottom: 10px;
    height: auto;
    min-height: 50px;
    overflow: hidden;
    color: #777;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
  }
  
  .book-cta {
    margin-top: auto;
  }
  
  .book-cta .btn {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 4px;
  }
  
  .btn-amazon {
    background-color: #ff9900;
    color: white;
    border-color: #ff9900;
  }
  
  .btn-amazon:hover {
    background-color: #e68a00;
    color: white;
    border-color: #e68a00;
  }
  
  .books-pagination {
    text-align: center;
    margin-top: 20px;
  }
  
  .books-pagination .btn {
    padding: 5px 15px;
    font-size: 14px;
  }
  
  /* レスポンシブ対応 */
  @media (max-width: 1199px) {
    .course-grid {
      grid-template-columns: repeat(3, 1fr);
    }
    
    .book-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  
  @media (max-width: 991px) {
    .course-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .book-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 767px) {
    .course-grid,
    .book-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }
  }
  
  @media (max-width: 575px) {
    .course-grid,
    .book-grid {
      grid-template-columns: repeat(1, 1fr);
    }
  }
  
  /* メリットカード */
  .benefits-section {
    background-color: #f9f9f9;
    padding: 40px 0;
    margin-bottom: 40px;
    border-radius: 10px;
  }
  
  .benefits-intro {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 30px;
  }
  
  .benefit-grid {
    margin-left: -10px;
    margin-right: -10px;
  }
  
  .benefit-item {
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 20px;
  }
  
  .benefit-card {
    background-color: white;
    border-radius: 10px;
    padding: 25px 15px;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
    text-align: center;
  }
  
  .benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  }
  
  .benefit-icon {
    font-size: 36px;
    color: #3498db;
    margin-bottom: 15px;
  }
  
  .benefit-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 12px;
    color: #2c3e50;
  }
  
  .benefit-description {
    color: #555;
    font-size: 14px;
  }
  
  /* レビューカード */
  .reviews-section {
    margin-bottom: 40px;
  }
  
  .review-grid {
    margin-left: -10px;
    margin-right: -10px;
  }
  
  .review-item {
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 20px;
  }
  
  .review-card {
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    position: relative;
  }
  
  .review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  }
  
  .review-top {
    display: flex;
    margin-bottom: 15px;
  }
  
  .review-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 15px;
  }
  
  .reviewer-name {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 2px;
    color: #2c3e50;
  }
  
  .review-course {
    font-size: 12px;
    color: #7f8c8d;
    margin-bottom: 4px;
  }
  
  .review-rating {
    color: #f39c12;
    font-size: 12px;
  }
  
  .review-content {
    font-size: 14px;
    line-height: 1.5;
    color: #555;
    font-style: italic;
    margin-bottom: 10px;
    position: relative;
    padding: 0 10px;
  }
  
  .review-content:before {
    content: """;
    position: absolute;
    left: -5px;
    top: -5px;
    font-size: 24px;
    color: #3498db;
    opacity: 0.3;
  }
  
  .review-content:after {
    content: """;
    position: absolute;
    right: -5px;
    bottom: -15px;
    font-size: 24px;
    color: #3498db;
    opacity: 0.3;
  }
  
  .review-date {
    font-size: 12px;
    color: #95a5a6;
    text-align: right;
  }
  
  /* ステップ */
  .connection-section {
    margin-bottom: 40px;
  }
  
  .connection-steps {
    margin: 25px 0;
  }
  
  .step {
    display: flex;
    margin-bottom: 20px;
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
  }
  
  .step-number {
    background-color: #3498db;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
    flex-shrink: 0;
  }
  
  .step-content h3 {
    margin-top: 0;
    font-size: 17px;
    color: #2c3e50;
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  .ai-highlight {
    background-color: #ecf0f1;
    border-left: 5px solid #3498db;
    padding: 15px;
    margin: 25px 0;
    border-radius: 0 8px 8px 0;
  }
  
  .ai-highlight h3 {
    color: #2c3e50;
    font-size: 17px;
    font-weight: bold;
    margin-top: 0;
    margin-bottom: 10px;
  }
  
  .ai-highlight p {
    margin-bottom: 0;
    font-size: 14px;
  }
  
  .connection-image-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }
  
  .connection-image img {
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    width: 100%;
    height: 120px;
    object-fit: cover;
  }
  
  .connection-image img:hover {
    transform: scale(1.05);
  }
  
  /* CTA バナー */
  .cta-banner {
    background-color: #3498db;
    border-radius: 10px;
    padding: 30px 20px;
    margin: 30px 0;
    text-align: center;
    color: white;
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
  }
  
  .cta-content h3 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
  }
  
  .cta-content p {
    font-size: 16px;
    margin-bottom: 25px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
  }
  
  .cta-buttons .btn {
    margin: 0 8px 8px;
    padding: 10px 20px;
    font-weight: 600;
  }
  
  /* FAQ */
  .faq-section {
    margin-bottom: 40px;
  }
  
  .faq-card {
    margin-bottom: 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
  }
  
  .faq-header {
    background-color: #f8f9fa;
    padding: 0;
    border-bottom: none;
  }
  
  .faq-btn {
    color: #2c3e50;
    font-weight: 600;
    text-decoration: none;
    width: 100%;
    text-align: left;
    padding: 15px;
    position: relative;
    transition: all 0.3s ease;
    background-color: transparent;
    border: none;
  }
  
  .faq-btn i {
    margin-right: 10px;
    transition: transform 0.3s ease;
  }
  
  .faq-btn[aria-expanded="true"] i {
    transform: rotate(45deg);
  }
  
  .faq-btn:hover,
  .faq-btn:focus {
    text-decoration: none;
    background-color: #eef1f5;
    outline: none;
  }
  
  .faq-body {
    background-color: #ffffff;
    color: #333333;
    padding: 15px;
  }
  
  .faq-body p {
    margin-bottom: 0;
    font-size: 14px;
  }
  
  .highlight {
    background-color: #fffde7;
    padding: 0 3px;
    border-bottom: 2px solid #f1c40f;
  }
  
  /* 関連コンテンツ */
  .related-content-section {
    margin-bottom: 40px;
  }
  
  .related-grid {
    margin-left: -10px;
    margin-right: -10px;
  }
  
  .related-item {
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 20px;
  }
  
  .related-card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    height: 100%;
    background-color: white;
  }
  
  .related-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  }
  
  .related-image img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .related-card:hover .related-image img {
    transform: scale(1.05);
  }
  
  .related-content {
    padding: 15px;
  }
  
  .related-content h3 {
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #2c3e50;
  }
  
  .related-content p {
    font-size: 13px;
    color: #555;
    margin-bottom: 15px;
    height: 55px;
    overflow: hidden;
  }
  
  /* 最終CTA */
  .final-cta {
    background-image: url('https://miura-diving.com/wp-content/uploads/名称未設定のデザイン-35.png');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 60px 0;
    position: relative;
    text-align: center;
    margin-top: 40px;
  }
  
  .final-cta-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(44, 62, 80, 0.8);
  }
  
  .final-cta-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
  }
  
  .final-cta h2 {
    font-size: 30px;
    font-weight: bold;
    margin-bottom: 15px;
  }
  
  .final-cta p {
    font-size: 18px;
    margin-bottom: 25px;
    opacity: 0.9;
  }
  
  .final-cta-buttons .btn {
    margin: 0 8px 8px;
    padding: 10px 20px;
    font-weight: 600;
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
  }
  
  /* フッターエリア */
  .footer-area {
    background-color: #2c3e50;
    padding-top: 50px;
    color: #ecf0f1;
  }
  
  .footer-widget {
    margin-bottom: 30px;
  }
  
  .footer-logo {
    margin-bottom: 15px;
  }
  
  .footer-logo img {
    max-width: 160px;
  }
  
  .widget-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    color: white;
    position: relative;
    padding-bottom: 10px;
  }
  
  .widget-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 2px;
    background-color: #3498db;
  }
  
  .footer-widget p {
    margin-bottom: 15px;
    color: #bdc3c7;
    font-size: 14px;
  }
  
  .social-icons {
    display: flex;
  }
  
  .social-icons a {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 10px;
    transition: all 0.3s ease;
  }
  
  .social-icons a:hover {
    background-color: #3498db;
  }
  
  .links-widget ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .links-widget ul li {
    margin-bottom: 8px;
  }
  
  .links-widget ul li a {
    color: #bdc3c7;
    font-size: 14px;
    transition: all 0.3s ease;
  }
  
  .links-widget ul li a:hover {
    color: white;
    text-decoration: none;
    padding-left: 5px;
  }
  
  .info-list, .contact-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .info-list li, .contact-list li {
    display: flex;
    margin-bottom: 12px;
    font-size: 14px;
    color: #bdc3c7;
  }
  
  .info-list li .icon, .contact-list li .icon {
    width: 20px;
    margin-right: 10px;
    color: #3498db;
  }
  
  .newsletter {
    position: relative;
    margin-top: 15px;
  }
  
  .newsletter input {
    width: 100%;
    background-color: rgba(255,255,255,0.1);
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    color: white;
    font-size: 14px;
  }
  
  .newsletter button {
    position: absolute;
    right: 5px;
    top: 5px;
    background-color: #3498db;
    border: none;
    color: white;
    border-radius: 3px;
    padding: 4px 8px;
    cursor: pointer;
  }
  
  .copyright-area {
    background-color: #1a252f;
    padding: 15px 0;
    margin-top: 15px;
  }
  
  .copyright-area p {
    margin-bottom: 0;
    font-size: 13px;
    color: #95a5a6;
  }
  
  .footer-nav {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: flex-end;
  }
  
  .footer-nav li {
    margin-left: 15px;
  }
  
  .footer-nav li a {
    color: #95a5a6;
    font-size: 13px;
    transition: color 0.3s ease;
  }
  
  .footer-nav li a:hover {
    color: white;
    text-decoration: none;
  }
  
  /* 共通のボタンスタイル */
  .btn-primary {
    background-color: #3498db;
    border-color: #3498db;
  }
  
  .btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
  }
  
  .btn-secondary {
    background-color: #95a5a6;
    border-color: #95a5a6;
  }
  
  .btn-secondary:hover {
    background-color: #7f8c8d;
    border-color: #7f8c8d;
  }
  
  .btn-outline-primary {
    color: #3498db;
    border-color: #3498db;
  }
  
  .btn-outline-primary:hover {
    background-color: #3498db;
    color: white;
  }
  
  .btn-outline-light {
    color: white;
    border-color: white;
  }
  
  .btn-outline-light:hover {
    background-color: white;
    color: #2c3e50;
  }
  
  /* アニメーション */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .fade-in {
    animation: fadeIn 0.5s ease-in-out forwards;
  }
  
  /* レスポンシブ対応 */
  @media (max-width: 1199px) {
    .hero-title {
      font-size: 38px;
    }
    
    .hero-description {
      font-size: 18px;
    }
  }
  
  @media (max-width: 991px) {
    .hero-title {
      font-size: 34px;
    }
    
    .hero-description {
      font-size: 16px;
    }
    
    .section-title {
      font-size: 24px;
    }
    
    .footer-nav {
      justify-content: flex-start;
      margin-top: 10px;
    }
    
    .footer-nav li {
      margin-left: 0;
      margin-right: 15px;
    }
  }
  
  @media (max-width: 767px) {
    .hero-section {
      padding: 60px 0;
    }
    
    .hero-title {
      font-size: 30px;
    }
    
    .hero-cta .btn {
      display: block;
      width: 100%;
      margin: 10px 0;
    }
    
    .intro-image-wrapper {
      margin-top: 20px;
      text-align: center;
    }
    
    .feature-item {
      flex-direction: column;
      text-align: center;
    }
    
    .feature-icon {
      margin: 0 auto 15px;
    }
    
    .copyright-area p {
      text-align: center;
    }
    
    .footer-nav {
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .cta-banner {
      padding: 20px 15px;
    }
    
    .cta-buttons .btn {
      display: block;
      width: 100%;
      margin: 8px 0;
    }
    
    .final-cta h2 {
      font-size: 24px;
    }
    
    .final-cta p {
      font-size: 16px;
    }
    
    .final-cta-buttons .btn {
      display: block;
      width: 100%;
      margin: 8px 0;
    }
  }
  
  @media (max-width: 576px) {
    .hero-title {
      font-size: 26px;
    }
    
    .hero-badge {
      font-size: 12px;
    }
    
    .intro-title {
      font-size: 20px;
    }
    
    .section-title {
      font-size: 22px;
    }
  }
</style>

<!-- JavaScriptの追加 -->
<script>
  jQuery(document).ready(function($) {
    // スムーズスクロール
    $('a[href^="#"]').on('click', function(e) {
      e.preventDefault();
      var target = this.hash;
      var $target = $(target);
      
      if($target.length){
        $('html, body').stop().animate({
          'scrollTop': $target.offset().top - 80
        }, 800, 'swing');
      }
    });
    
    // FAQアコーディオンのアイコン切り替え
    $('.faq-btn').on('click', function() {
      var expanded = $(this).attr('aria-expanded');
      
      // 全てのアイコンをリセット
      $('.faq-btn i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
      
      // クリックされたボタンのアイコンを変更
      if(expanded === "true") {
        $(this).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
      } else {
        $(this).find('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
      }
    });
    
    // カードの高さを揃える
    function equalizeHeight(selector) {
      var maxHeight = 0;
      $(selector).height('auto');
      
      $(selector).each(function() {
        if ($(this).height() > maxHeight) {
          maxHeight = $(this).height();
        }
      });
      
      $(selector).height(maxHeight);
    }
    
    // ウィンドウのリサイズ時に高さを再調整
    $(window).on('load resize', function() {
      if ($(window).width() > 767) {
        equalizeHeight('.benefit-card');
        equalizeHeight('.review-card');
        equalizeHeight('.related-card');
      } else {
        $('.benefit-card, .review-card, .related-card').height('auto');
      }
    });
    
    // 画像の遅延読み込み
    if ('loading' in HTMLImageElement.prototype) {
      const images = document.querySelectorAll('img[loading="lazy"]');
      images.forEach(img => {
        if (img.dataset.src) {
          img.src = img.dataset.src;
        }
      });
    } else {
      // Fallback for browsers that don't support lazy loading
      const script = document.createElement('script');
      script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
      document.body.appendChild(script);
    }
    
    // スクロールアニメーション
    function checkVisibility() {
      $('.benefit-card, .review-card, .related-card').each(function() {
        var windowHeight = $(window).height();
        var elementTop = $(this).offset().top;
        var elementVisible = 150;
        
        if (elementTop < (windowHeight - elementVisible)) {
          $(this).addClass('fade-in');
        }
      });
    }
    
    // 初期表示時とスクロール時に実行
    $(window).on('scroll load', checkVisibility);
    
    // カウントアップアニメーション
    $('.stat-number').each(function() {
      var $this = $(this);
      var countTo = $this.text().replace(/,/g, '').replace(/\+/g, '');
      
      $({ countNum: 0 }).animate({
        countNum: countTo
      }, {
        duration: 1500,
        easing: 'swing',
        step: function() {
          $this.text(Math.floor(this.countNum).toLocaleString());
        },
        complete: function() {
          $this.text(parseFloat(countTo).toLocaleString() + ($this.text().indexOf('+') !== -1 ? '+' : ''));
        }
      });
    });
    
    // モバイルメニューのトグル
    $('.mobile-menu-toggle').on('click', function() {
      $('.mobile-menu').toggleClass('active');
      $(this).toggleClass('active');
    });
  });
</script>

<?php get_footer(); ?><?php
