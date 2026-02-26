<?php
/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link: https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="referrer" content="<?php echo apply_filters('cocoon_meta_referrer_content', get_meta_referrer_content()); ?>">
  <meta name="format-detection" content="telephone=no">

  <?php // アクセス解析などヘッド内挿入パーツ
  cocoon_template_part('tmp/head-analytics'); ?>

  <?php if ( has_amp_page() ): ?>
    <link rel="amphtml" href="<?php echo get_amp_permalink(); ?>">
  <?php endif; ?>

  <?php if ( get_google_search_console_id() ): ?>
    <!-- Google Search Console -->
    <meta name="google-site-verification" content="<?php echo get_google_search_console_id(); ?>" />
    <!-- /Google Search Console -->
  <?php endif; ?>

  <?php
  // preconnect / dns-prefetch
  $domains = list_text_to_array(get_pre_acquisition_list());
  if ( $domains ) echo "<!-- preconnect dns-prefetch -->\n";
  foreach ( $domains as $domain ): ?>
    <link rel="preconnect dns-prefetch" href="//<?php echo esc_attr( $domain ); ?>">
  <?php endforeach; ?>

  <!-- フォント先読み -->
  <link rel="preload" as="font" type="font/woff" href="<?php echo FONT_ICOMOON_WOFF_URL; ?>" crossorigin>
  <?php if ( is_site_icon_font_font_awesome_4() ): ?>
    <link rel="preload" as="font" type="font/woff2" href="<?php echo FONT_AWESOME_4_WOFF2_URL; ?>" crossorigin>
  <?php else: ?>
    <link rel="preload" as="font" type="font/woff2" href="<?php echo FONT_AWESOME_5_BRANDS_WOFF2_URL; ?>" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="<?php echo FONT_AWESOME_5_REGULAR_WOFF2_URL; ?>" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="<?php echo FONT_AWESOME_5_SOLID_WOFF2_URL; ?>" crossorigin>
  <?php endif; ?>

  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


  <?php // WordPress が出力するヘッダー情報
  wp_head(); ?>

  <?php // カスタムフィールド head_custom
  cocoon_template_part('tmp/head-custom-field'); ?>

  <?php // headで読み込む必要があるJavaScript
  cocoon_template_part('tmp/head-javascript'); ?>

  <?php // PWA スクリプト
  cocoon_template_part('tmp/head-pwa'); ?>

  <?php // ユーザー挿入用 head-insert
  cocoon_template_part('tmp-user/head-insert'); ?>


  <!-- OGP設定（全ページ共通） -->
  <meta property="og:title"       content="<?php echo is_home() ? esc_attr(get_bloginfo('name')) : esc_attr(wp_get_document_title()); ?>">
  <meta property="og:description" content="<?php echo is_home() ? esc_attr(get_bloginfo('description')) : esc_attr(wp_strip_all_tags(get_the_excerpt(), true)); ?>">
  <meta property="og:url"         content="<?php echo esc_url( ( empty($_SERVER['HTTPS']) ? 'http://' : 'https://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>">
  <meta property="og:type"        content="<?php echo is_single() ? 'article' : 'website'; ?>">
  <meta property="og:image"       content="https://miura-diving.com/wp-content/uploads/1.png">
  <meta property="og:image:width"  content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:site_name"    content="<?php bloginfo('name'); ?>">

  <?php
  // 「ダイビングライセンス」ページだけに出力したい固定OGP／JSON‑LD
  if ( is_page_template( 'diving-license.php' ) ) : ?>
    <!-- 固定：SEOメタ -->
    <meta name="description" content="神奈川県三浦市のPADIダイビングショップ「三浦 海の学校」。体験ダイビングから各種PADIライセンス取得、インストラクターコースまで幅広いプログラムをご用意。都心から約90分。専用プール完備で初心者も安心。">
    <meta name="keywords"    content="ダイビングライセンス,PADI,三浦,体験ダイビング,OWD,AOW,レスキュー,ダイブマスター">

    <!-- 固定：OGP -->
    <meta property="og:title"       content="ダイビングライセンスコース一覧 | 三浦 海の学校">
    <meta property="og:description" content="神奈川県三浦市のPADIダイビングショップ「三浦 海の学校」。初心者からプロまで安心・安全にダイビングを学べます。都心から90分、専用プール完備。">
    <meta property="og:url"         content="https://miura-diving.com/license">
    <meta property="og:image"       content="https://miura-diving.com/wp-content/uploads/P6292740-1-scaled.jpg">
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="三浦 海の学校">

    <!-- 固定：構造化データ① LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "三浦 海の学校",
      "image": "https://miura-diving.com/wp-content/uploads/海学-1.png",
      "description": "神奈川県三浦市のPADIダイビングショップ。体験ダイビングから各種PADIライセンス取得、インストラクターコースまで提供。専用プール完備で初心者も安心。",
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
        "latitude": "35.1814",
        "longitude": "139.6742"
      },
      "telephone": "046-884-8878",
      "url": "https://miura-diving.com/license",
      "priceRange": "¥16,500〜",
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
        "opens": "09:00",
        "closes": "16:00"
      },
      "sameAs": [
        "https://www.facebook.com/MiuraDiving",
        "https://www.instagram.com/miura_diving/"
      ]
    }
    </script>

    <!-- 固定：構造化データ② ItemList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ItemList",
      "itemListElement": [
        {
          "@type": "Course",
          "position": 1,
          "name": "体験ダイビング",
          "description": "実際の海で魚たちと一緒に泳ぐ感動を体験できるコース。インストラクターが常に横について安全に案内します。",
          "provider": {
            "@type": "Organization",
            "name": "三浦 海の学校",
            "sameAs": "https://miura-diving.com"
          },
          "url": "https://miura-diving.com/license#experience"
        },
        {
          "@type": "Course",
          "position": 2,
          "name": "オープンウォーターダイバー (OWD)",
          "description": "ダイビングの基本スキルを習得する入門コース。18mまでのダイビングを楽しめます。",
          "provider": {
            "@type": "Organization",
            "name": "三浦 海の学校",
            "sameAs": "https://miura-diving.com"
          },
          "url": "https://miura-diving.com/owd-course/"
        }
      ]
    }
    </script>

    <!-- 固定：追加メタ -->
    <link rel="canonical" href="https://miura-diving.com/license/">
    <meta name="robots"     content="index, follow">
    <meta name="geo.region" content="JP-14">
  <?php endif; ?>


  <!-- 全ページ共通：構造化データ（SportsActivityLocation） -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "SportsActivityLocation",
    "name": "三浦 海の学校【公式】",
    "url": "https://miura-diving.com/",
    "logo": "https://miura-diving.com/wp-content/uploads/海学.png",
    "description": "神奈川県三浦市のPADI公認5スターIDCセンター。都心から60分の好アクセス、専用ダイビングプール完備のダイビングスクール。初心者も安心のPADIライセンス講習、ファンダイビング、マリンアクティビティを提供。PADIコースディレクター指導の安全で質の高いダイビング体験をお届けします。",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "三浦市三崎町諸磯",
      "addressRegion": "神奈川県",
      "postalCode": "238-0224",
      "streetAddress": "1621"
    },
    "telephone": "046-880-0835",
    "email": "info@miura-diving.com",
    "openingHours": "Mo-Su 08:00-16:00",
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": "35.1617993",
      "longitude": "139.6252699"
    },
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name": "ダイビングサービス",
      "itemListElement": [
        {
          "@type": "Offer",
          "name": "PADIダイビングライセンス取得",
          "description": "初めてのダイビングからPADIオープンウォーターなど、各種ライセンス講習を開催。日帰りコースもご用意。",
          "url": "https://miura-diving.com/diving-license/"
        },
        {
          "@type": "Offer",
          "name": "ファンダイビング",
          "description": "ライセンス保持者向けの多彩なダイビングプラン。三浦半島の魅力的なポイントで四季折々の海を体験。",
          "url": "https://miura-diving.com/fun-diving/"
        },
        {
          "@type": "Offer",
          "name": "マリンアクティビティ",
          "description": "SUP、シュノーケリング、シーカヤックなど、ダイビング以外の多彩な海のアクティビティを気軽に楽しめます。",
          "url": "https://miura-diving.com/marine-activity/"
        }
      ]
    },
    "potentialAction": {
      "@type": "ReserveAction",
      "target": {
        "@type": "EntryPoint",
        "urlTemplate": "https://miura-diving.com/contact",
        "inLanguage": "ja",
        "actionPlatform": ["http://schema.org/DesktopWebPlatform"]
      },
      "result": {
        "@type": "Reservation",
        "name": "ダイビング予約"
      }
    }
  }
  </script>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
  <?php cocoon_template_part('tmp/body-top-analytics'); ?>
  <?php cocoon_template_part('tmp/body-top'); ?>

  <!-- Modern Navigation Header -->
  <header class="modern-header" role="banner">
    <div class="header-container">
      <div class="header-brand">
        <a href="<?php echo home_url(); ?>" class="brand-logo" aria-label="ホームに戻る">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/umigaku-logo.webp" alt="三浦 海の学校" width="120" height="40">
        </a>
      </div>
      
      <!-- PC Navigation -->
      <nav class="desktop-nav" role="navigation" aria-label="メインナビゲーション">
        <?php wp_nav_menu([
          'theme_location' => 'header-menu',
          'container' => false,
          'menu_class' => 'nav-menu',
          'fallback_cb' => false
        ]); ?>
      </nav>
      
      <!-- Mobile Menu Button -->
      <button id="burger" class="mobile-menu-btn" aria-label="メニューを開く" tabindex="0" role="button">
        <span class="burger-line"></span>
        <span class="burger-line"></span>
        <span class="burger-line"></span>
      </button>
    </div>
    
    <!-- Mobile Navigation -->
    <!-- ★FIX: dialog → nav変更、translateX開閉、body.lock追加 -->
    <nav id="mobileNav" class="mobile-nav" aria-label="モバイルメニュー">
      <div class="mobile-nav-overlay" onclick="closeMobileNav()"></div>
      <div class="mobile-nav-content">
        <button class="close-btn" onclick="closeMobileNav()" aria-label="メニューを閉じる" role="button" tabindex="0">
          <span>&times;</span>
        </button>
        <?php wp_nav_menu([
          'theme_location' => 'mobile-menu',
          'container' => false,
          'menu_class' => 'mobile-nav-menu',
          'fallback_cb' => false
        ]); ?>
      </div>
    </nav>
  </header>
