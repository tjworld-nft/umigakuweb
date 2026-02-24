<?php
/*--------------------------------------------------
  子テーマ functions.php  – 2025‑04‑17
  ※丸ごとコピペ用
--------------------------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* 1) ───────── ビジュアルエディタ用 CSS */
add_editor_style();

/* 2) ───────── ナビゲーションメニュー */
add_action( 'after_setup_theme', function () {
  register_nav_menus( [
    'header-menu' => 'ヘッダーメニュー',
    'footer-menu' => 'フッターメニュー',
    'mobile-menu' => 'モバイルメニュー',
  ] );
} );

/* 3) ───────── CSS / JS の読込（パフォーマンス最適化） */
add_action( 'wp_enqueue_scripts', function () {
  // バージョン番号を動的に管理
  $theme_version = wp_get_theme()->get('Version');
  
  // 全ページ共通
  wp_enqueue_style( 'owd-styles', get_stylesheet_directory_uri().'/owd-styles.css', [], $theme_version );
  
  // 条件付きで JavaScript を読み込み
  if ( is_front_page() || is_page_template('owd-template.php') ) {
    wp_enqueue_script( 'jquery-easing', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js', ['jquery'], '1.4.1', true );
    wp_enqueue_script( 'owd-scripts', get_stylesheet_directory_uri().'/owd-scripts.js', ['jquery','jquery-easing'], $theme_version, true );
  }
  
  // Swiper.js（フロントページのみ）
  if ( is_front_page() ) {
    wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0.0' );
    wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11.0.0', true );
    
    // Swiper初期化スクリプト
    wp_add_inline_script( 'swiper-js', '
      document.addEventListener("DOMContentLoaded", function() {
        const heroSwiper = new Swiper(".hero-swiper", {
          loop: true,
          autoplay: {
            delay: 6000,
            disableOnInteraction: false,
          },
          slidesPerView: 1,
          effect: "fade",
          fadeEffect: {
            crossFade: true
          },
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
          speed: 1000,
          allowTouchMove: true,
          grabCursor: true,
        });
      });
    ' );
  }

  /* Ajax 用 URL を渡す */
  wp_localize_script( 'jquery', 'ajax_object', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );
} );

/* ★FIX: フロントページ専用CSS・JS + Google Fonts preload + WebP + 構造化データ */
add_action( 'wp_enqueue_scripts', function () {
  $theme_version = wp_get_theme()->get('Version');
  
  // フロントページ専用CSS・JS
  if ( is_front_page() ) {
    wp_enqueue_style( 'front-css', get_stylesheet_directory_uri().'/assets/css/front.css', [], $theme_version );
    wp_enqueue_script( 'front-js', get_stylesheet_directory_uri().'/assets/js/front.js', [], $theme_version, true );
  }
  
  // ★ v2: Google Fonts preload + Framer Motion
  add_action( 'wp_head', function() {
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Noto+Sans+JP:wght@400;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    echo '<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Noto+Sans+JP:wght@400;700&display=swap"></noscript>';
  }, 5 );
  
  // Framer Motion for animations (フロントページのみ)
  if ( is_front_page() ) {
    wp_enqueue_script('framer-motion', 'https://unpkg.com/framer-motion@latest/dist/framer-motion.umd.js', null, null, true);
  }
} );

/* title-tag サポート確認 */
add_theme_support('title-tag');

/* パフォーマンス最適化 */
// 画像の遅延読み込み（既存のWordPress機能を活用）
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

// 不要なWordPressのデフォルト機能を無効化
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/* コンテンツに画像遅延読み込み自動付与 */
add_filter( 'the_content', function( $content ) {
  // img タグに loading="lazy" を自動追加
  $content = preg_replace('/<img(?![^>]*loading=)([^>]*)>/i', '<img$1 loading="lazy">', $content);
  return $content;
} );

/* nav メニューにaria-current自動付与 */
add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args ) {
  if ( in_array( 'current-menu-item', $item->classes ) ) {
    $atts['aria-current'] = 'page';
  }
  return $atts;
}, 10, 3 );

/* WebP対応とsrcset最適化 */
add_theme_support('post-thumbnails');
add_theme_support('html5', array('gallery', 'caption'));

// WebP対応画像生成
add_filter('wp_generate_attachment_metadata', function($metadata, $attachment_id) {
  $file = get_attached_file($attachment_id);
  $info = pathinfo($file);
  
  if (in_array($info['extension'], ['jpg', 'jpeg', 'png'])) {
    $webp_file = $info['dirname'] . '/' . $info['filename'] . '.webp';
    
    if (function_exists('imagewebp')) {
      $image = null;
      if ($info['extension'] === 'png') {
        $image = imagecreatefrompng($file);
      } else {
        $image = imagecreatefromjpeg($file);
      }
      
      if ($image) {
        imagewebp($image, $webp_file, 90);
        imagedestroy($image);
      }
    }
  }
  
  return $metadata;
}, 10, 2);

// srcset最適化
add_filter('wp_calculate_image_srcset', function($sources, $size_array, $image_src, $image_meta, $attachment_id) {
  foreach ($sources as $width => $source) {
    $info = pathinfo($source['url']);
    $webp_url = $info['dirname'] . '/' . $info['filename'] . '.webp';
    
    if (file_exists(str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $webp_url))) {
      $sources[$width]['url'] = $webp_url;
    }
  }
  return $sources;
}, 10, 5);

/* ★FIX: 拡張JSON-LD構造化データ - Organization & FAQPage (6Q) */
add_action('wp_head', 'insert_enhanced_ldjson_structured_data');
function insert_enhanced_ldjson_structured_data() {
  if (is_front_page()) {
    ?>
    <!-- Enhanced Organization JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "三浦 海の学校",
      "alternateName": "Miura Diving School",
      "url": "https://miura-diving.com",
      "logo": "https://miura-diving.com/wp-content/uploads/umigaku-logo.webp",
      "description": "神奈川県三浦市のPADI公認5スターIDCセンター。初心者からプロまで安心安全のダイビングスクール。都心から60分、専用プール完備。",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "三崎町諸磯1621",
        "addressLocality": "三浦市",
        "addressRegion": "神奈川県",
        "postalCode": "238-0224",
        "addressCountry": "JP"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "35.1617993",
        "longitude": "139.6252699"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+81-46-880-0835",
        "contactType": "customer service",
        "availableLanguage": ["Japanese"]
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
        "opens": "08:00",
        "closes": "16:00"
      },
      "sameAs": [
        "https://www.facebook.com/MiuraDiving",
        "https://www.instagram.com/miura_diving/"
      ]
    }
    </script>
    
    <!-- Enhanced FAQ JSON-LD (6 Questions) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "初心者でもダイビングライセンスは取得できますか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "はい、当スクールは初心者の方でも安心してライセンス取得できるよう専用プールを完備しております。基礎からしっかりと指導いたします。"
          }
        },
        {
          "@type": "Question", 
          "name": "ライセンス取得にかかる期間はどのくらいですか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "オープンウォーターダイバーコースは通常3-4日間です。お客様のペースに合わせて調整可能です。"
          }
        },
        {
          "@type": "Question",
          "name": "都心からのアクセスはどうですか？",
          "acceptedAnswer": {
            "@type": "Answer", 
            "text": "東京都心から約60分でお越しいただけます。京急三崎口駅からバスでアクセス可能です。"
          }
        },
        {
          "@type": "Question",
          "name": "料金にはどのような費用が含まれていますか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "コース料金には教材費、器材レンタル代、海洋実習費、認定カード発行費が含まれます。追加費用は一切ありません。"
          }
        },
        {
          "@type": "Question",
          "name": "年齢制限はありますか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "10歳から参加可能です。未成年の方は保護者の同意が必要となります。上限年齢の制限はございません。"
          }
        },
        {
          "@type": "Question",
          "name": "冬でもダイビングはできますか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "三浦半島の海は年中ダイビングが可能です。冬季はドライスーツを使用し、透明度の高い海を楽しめます。"
          }
        }
      ]
    }
    </script>
    <?php
  }
}

// DNS prefetch でパフォーマンス向上
add_action( 'wp_head', function() {
  echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
  echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">';
  echo '<link rel="dns-prefetch" href="//connect.facebook.net">';
  echo '<link rel="dns-prefetch" href="//platform.twitter.com">';
} );

// ★FIX: 画像サイズ最適化 + Hero用追加
add_theme_support( 'post-thumbnails' );
add_image_size( 'hero', 1920, 1080, true );
add_image_size( 'service-card', 400, 240, true );
add_image_size( 'feature-icon', 80, 80, true );

// lazy loading フィルター追加
add_filter( 'the_content', 'add_lazy_loading_to_content' );
function add_lazy_loading_to_content( $content ) {
  // img タグに loading="lazy" を自動追加（Hero以外）
  $content = preg_replace('/<img(?![^>]*loading=)([^>]*?)(?![^>]*fetchpriority)>/i', '<img$1 loading="lazy">', $content);
  return $content;
}

/* 4) ───────── OWD 専用リライトルール */
add_action( 'init', function () {
  add_rewrite_rule( '^owd-course/?$', 'index.php?owd_template=1', 'top' );
} );
add_filter( 'query_vars', function ( $vars ){
  $vars[] = 'owd_template';
  return $vars;
} );
add_filter( 'template_include', function ( $template ){
  if ( get_query_var( 'owd_template' ) ){
    $new = locate_template( 'owd-template.php' );
    if ( $new ) return $new;
  }
  return $template;
} );

/* 5) ───────── ライセンスページ専用 META / JSON‑LD */
add_action( 'wp_head', function () {
  if ( is_page_template( 'diving-license.php' ) ){ ?>

<!-- ────── ライセンスページ専用 META ────── -->
<meta name="description" content="神奈川県三浦市のPADIダイビングショップ「三浦 海の学校」。体験ダイビングから各種ライセンス取得まで幅広く対応。" />
<meta name="keywords"    content="ダイビングライセンス,PADI,三浦,体験ダイビング,OWD,AOW,レスキュー" />

<meta property="og:title"       content="ダイビングライセンスコース一覧 | 三浦 海の学校" />
<meta property="og:description" content="初心者からプロまで、安心・安全にダイビングを学べます。都心から90分、専用プール完備。" />
<meta property="og:url"         content="https://miura-diving.com/license" />
<meta property="og:image"       content="https://miura-diving.com/wp-content/uploads/P6292740-1-scaled.jpg" />
<meta property="og:type"        content="website" />
<meta property="og:site_name"   content="三浦 海の学校" />
<meta property="og:locale"      content="ja_JP" />

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"LocalBusiness",
  "name":"三浦 海の学校",
  "image":"https://miura-diving.com/wp-content/uploads/海学-1.png",
  "description":"神奈川県三浦市のPADIダイビングショップ。体験ダイビングから各種ライセンス取得まで提供。",
  "address":{"@type":"PostalAddress","streetAddress":"三崎町諸磯1621","addressLocality":"三浦市","addressRegion":"神奈川県","postalCode":"238-0224","addressCountry":"JP"},
  "geo":{"@type":"GeoCoordinates","latitude":"35.1814","longitude":"139.6742"},
  "telephone":"046-884-8878",
  "url":"https://miura-diving.com/license",
  "priceRange":"¥16,500〜",
  "openingHours":"Mo-Su 09:00-16:00",
  "sameAs":["https://www.facebook.com/MiuraDiving","https://www.instagram.com/miura_diving/"]
}
</script>

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"ItemList",
  "itemListElement":[
    {"@type":"Course","position":1,"name":"体験ダイビング","url":"https://miura-diving.com/license#experience"},
    {"@type":"Course","position":2,"name":"オープンウォーターダイバー (OWD)","url":"https://miura-diving.com/owd-course/"}
  ]
}
</script>
<!-- ───────────────────── -->
<?php }
} );

// トップページだけ<title>をカスタマイズ（SEOキーワード追加）
add_filter('pre_get_document_title', function($title) {
  if (is_front_page() || is_home()) {
    return '【公式】三浦 海の学校｜神奈川のPADIダイビングライセンス＆体験ダイビング';
  }
  return $title;
});

function enqueue_child_theme_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
    // ファンダイビングページ用のカスタムCSS
    if (is_page('fun-diving')) {
        wp_enqueue_style('fun-diving-style', get_stylesheet_directory_uri() . '/fundiving-styles.css', array('child-style'));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles');

// /blog（投稿一覧）では diving-miura カテゴリーだけ表示
function miura_blog_only_diving( $query ) {
  if ( ! is_admin()
    && $query->is_main_query()
    && $query->is_home()     // 投稿ページ（/blog/）のみ
  ) {
    $query->set( 'category_name', 'diving-miura' );
  }
}
add_action( 'pre_get_posts', 'miura_blog_only_diving' );

