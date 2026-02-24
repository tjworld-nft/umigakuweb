<?php
/*
Template Name: ダイビングライセンス
*/
get_header(); 
?>

<!-- SEOメタタグ (拡張) -->
<meta name="description" content="神奈川県三浦市のPADIダイビングショップ「三浦 海の学校」。体験ダイビングから各種PADIライセンス取得、インストラクターコースまで幅広いプログラムをご用意。初心者からプロを目指す方まで経験豊富なインストラクターが丁寧に指導します。都心から約90分のアクセスで、専用プール完備。">
<meta name="keywords" content="ダイビングライセンス,PADI,三浦,神奈川,体験ダイビング,オープンウォーターダイバー,アドバンス,レスキュー,ダイブマスター,インストラクター,海,スキューバ,ダイビングスクール">

<!-- OGPタグ（SNSシェア用）- 拡張 -->
<meta property="og:title" content="ダイビングライセンスコース一覧 | 三浦 海の学校 | PADI認定ダイビングスクール">
<meta property="og:description" content="神奈川県三浦市のPADIダイビングショップ「三浦 海の学校」。初心者からプロまで、安心・安全に楽しくダイビングを学べます。都心から約90分、専用プール完備で快適に講習可能。">
<meta property="og:url" content="https://miura-diving.com/license">
<meta property="og:image" content="https://miura-diving.com/wp-content/uploads/P6292740-1-scaled.jpg">
<meta property="og:type" content="website">
<meta property="og:site_name" content="三浦 海の学校">
<meta property="og:locale" content="ja_JP">

<!-- 構造化データ (拡張) -->
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
  "url": "https://miura-diving.com",
  "priceRange": "¥16,500〜",
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
    "opens": "09:00",
    "closes": "16:00"
  },
  "sameAs": [
    "https://www.facebook.com/MiuraDiving",
    "https://www.instagram.com/miura_diving/"
  ]
}
</script>

<!-- OWD用構造化データ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "オープンウォーターダイバー (OWD)",
  "description": "ダイビングの基本スキルを習得する入門コース。このライセンスを取得すれば、世界中の海で18mまでのダイビングを楽しめます。",
  "provider": {
    "@type": "Organization",
    "name": "三浦 海の学校",
    "sameAs": "https://miura-diving.com"
  },
  "hasCourseInstance": {
    "@type": "CourseInstance",
    "courseMode": "inPerson",
    "startDate": "2025-05-01",
    "endDate": "2025-05-03",
    "location": {
      "@type": "Place",
      "name": "三浦 海の学校",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "三崎町諸磯1621",
        "addressLocality": "三浦市",
        "addressRegion": "神奈川県",
        "postalCode": "238-0224",
        "addressCountry": "JP"
      }
    },
    "offers": {
      "@type": "Offer",
      "price": "53900",
      "priceCurrency": "JPY",
      "availability": "https://schema.org/InStock",
      "url": "https://miura-diving.com/owd-course/"
    }
  }
}
</script>

<!-- 体験ダイビング用構造化データ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "体験ダイビング",
  "description": "実際の海で魚たちと一緒に泳ぐ感動を体験できるコース。インストラクターが常に横について安全に海中世界を案内します。",
  "provider": {
    "@type": "Organization",
    "name": "三浦 海の学校",
    "sameAs": "https://miura-diving.com"
  },
  "hasCourseInstance": {
    "@type": "CourseInstance",
    "courseMode": "inPerson",
    "startDate": "2025-05-10",
    "endDate": "2025-05-10",
    "location": {
      "@type": "Place",
      "name": "三浦 海の学校",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "三崎町諸磯1621",
        "addressLocality": "三浦市",
        "addressRegion": "神奈川県",
        "postalCode": "238-0224",
        "addressCountry": "JP"
      }
    },
    "offers": {
      "@type": "Offer",
      "price": "16500",
      "priceCurrency": "JPY",
      "availability": "https://schema.org/InStock",
      "url": "https://miura-diving.com/try-diving/"
    }
  }
}
</script>


<!-- PADIダイビングコース専用スキーマ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    {
      "@type": "Course",
      "position": 1,
      "name": "体験ダイビング",
      "description": "実際の海で魚たちと一緒に泳ぐ感動を体験できるコース。インストラクターが常に横について安全に海中世界を案内します。",
      "provider": {
        "@type": "Organization",
        "name": "三浦 海の学校",
        "sameAs": "https://miura-diving.com"
      },
      "url": "https://miura-diving.com/license#experience",
      "hasCourseInstance": {
        "@type": "CourseInstance",
        "courseMode": "inPerson",
        "startDate": "2025-05-10",
        "endDate": "2025-05-10",
        "location": {
          "@type": "Place",
          "name": "三浦 海の学校",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "三崎町諸磯1621",
            "addressLocality": "三浦市",
            "addressRegion": "神奈川県",
            "postalCode": "238-0224",
            "addressCountry": "JP"
          }
        },
        "offers": {
          "@type": "Offer",
          "price": "16500",
          "priceCurrency": "JPY",
          "availability": "https://schema.org/InStock",
          "url": "https://miura-diving.com/try-diving/"
        }
      }
    },
    {
      "@type": "Course",
      "position": 2,
      "name": "オープンウォーターダイバー (OWD)",
      "description": "ダイビングの基本スキルを習得する入門コース。このライセンスを取得すれば、世界中の海で18mまでのダイビングを楽しめます。",
      "provider": {
        "@type": "Organization",
        "name": "三浦 海の学校",
        "sameAs": "https://miura-diving.com"
      },
      "url": "https://miura-diving.com/owd-course/",
      "hasCourseInstance": {
        "@type": "CourseInstance",
        "courseMode": "inPerson",
        "startDate": "2025-05-01",
        "endDate": "2025-05-03",
        "location": {
          "@type": "Place",
          "name": "三浦 海の学校",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "三崎町諸磯1621",
            "addressLocality": "三浦市",
            "addressRegion": "神奈川県",
            "postalCode": "238-0224",
            "addressCountry": "JP"
          }
        },
        "offers": {
          "@type": "Offer",
          "price": "53900",
          "priceCurrency": "JPY",
          "availability": "https://schema.org/InStock",
          "url": "https://miura-diving.com/owd-course/"
        }
      }
    }
  ]
}

</script>

<!-- 追加メタデータ -->
<link rel="canonical" href="https://miura-diving.com/license" />
<meta name="robots" content="index, follow" />
<meta name="geo.region" content="JP-14" />
<meta name="geo.placename" content="三浦市" />
<meta name="geo.position" content="35.1814;139.6742" />
<meta name="ICBM" content="35.1814, 139.6742" />

<!-- ビューポート設定 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">

<!-- JavaScriptの機能拡張 -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  // スムーズスクロール
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);
      
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: 'smooth'
        });
        
        // ナビゲーションのアクティブ状態を更新
        document.querySelectorAll('.course-nav-link').forEach(link => {
          link.classList.remove('active');
        });
        this.classList.add('active');
      }
    });
  });
  
  // スクロール時のアニメーション
  const revealElements = document.querySelectorAll('.reveal-on-scroll');
  
  function checkReveal() {
    revealElements.forEach(element => {
      const elementTop = element.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;
      
      if (elementTop < windowHeight - 100) {
        element.classList.add('reveal-active');
      }
    });
  }
  
  window.addEventListener('scroll', checkReveal);
  checkReveal(); // 初期チェック
  
  // 現在のハッシュに基づいてナビゲーションのアクティブ状態を設定
  if (window.location.hash) {
    const activeLink = document.querySelector(`.course-nav-link[href="${window.location.hash}"]`);
    if (activeLink) {
      activeLink.classList.add('active');
    }
  }
});
</script>

<!-- ダイビングライセンスコース一覧ページ -->
<div class="section section--license">
  <!-- 背景装飾 -->
  <div style="position:absolute; top:0; left:0; width:100%; height:100%; background-image:url('https://miura-diving.com/wp-content/uploads/海学-1.png'); background-repeat:repeat; opacity:0.05; z-index:1;"></div>
  
  <div class="container">
    <!-- ヘッダーセクション -->
    <div class="hero-section">
      <h1 class="page-title">
        ダイビングライセンスコース一覧
      </h1>
      <p class="page-subtitle">
        体験ダイビングから各種PADIライセンス、インストラクターコースまで<br>幅広いダイビングコースをご用意しています
      </p>
    </div>

    <div class="features-badge">
      <p style="margin:0; font-weight:bold;">
        「都心からおよそ90分」「ダイビング専用プール完備」「海は目の前」
      </p>
    </div>
    
   <!-- コースカテゴリナビゲーション -->
    <div class="course-nav">
      <div class="course-nav-inner">
        <a href="#experience" class="course-nav-link">体験ダイビング</a>
        <a href="#beginner" class="course-nav-link">初級コース</a>
        <a href="#advanced" class="course-nav-link">中級コース</a>
        <a href="#specialty" class="course-nav-link">スペシャルティ</a>
        <a href="#pro" class="course-nav-link">プロコース</a>
      </div>
    </div>
    
    <!-- コース一覧の説明 -->
    <div class="card reveal-on-scroll">
      <h2 class="section-title" style="text-align:left; margin-top:0;">ダイビングライセンスについて</h2>
      <p class="mb-20">
        ダイビングを安全に楽しむために必要な「ライセンス（Cカード）」は、国際的に認められた資格です。三浦 海の学校では世界最大のダイビング指導団体「PADI」の各種ライセンスコースを提供しています。
      </p>
      <p class="mb-20">
        初めての方向けの体験ダイビングから、趣味でダイビングを楽しむための各種ライセンス、さらにはプロフェッショナルを目指すためのインストラクターコースまで、あなたのレベルや目標に合わせた幅広いコース選択が可能です。
      </p>
      <p>
        各コースの詳細、必要日数、料金などを以下にまとめました。どのコースも経験豊富なインストラクターが丁寧に指導いたしますので、安心してご参加ください。
      </p>
    </div>
    
    <!-- 体験ダイビング -->
    <div id="experience" class="reveal-on-scroll reveal-offset">
    <h2 class="section-title title--center">
        <span style="background-color:var(--primary); color:var(--white); width:40px; height:40px; border-radius:50%; text-align:center; line-height:40px; margin-right:15px;">1</span>
        体験ダイビング
      </h2>
      
      <div class="card overflow-hidden mb-50">
        <div class="flex-row card-reverse">
          <div class="flex-1 min-width-300" style="padding:30px;">
            <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px;">体験ダイビング（海）</h3>
            <p class="mb-20">
              実際の海で魚たちと一緒に泳ぐ感動を体験できるコースです。インストラクターが常に横について安全に海中世界を案内します。
            </p>
            <div class="mb-20">
              <div class="info-row">
                <div class="info-label">所要時間：</div>
                <div class="info-value">約3時間（1ダイブ）</div>
              </div>
              <div class="info-row">
                <div class="info-label">料金：</div>
                <div class="info-value">¥16,500（税込）</div>
              </div>
              <div class="info-row">
                <div class="info-label">含まれるもの：</div>
                <div class="info-value">器材レンタル一式、保険料、ガイド料</div>
              </div>
              <div class="info-row">
                <div class="info-label">別途必要：</div>
                <div class="info-value">水着、タオル、昼食</div>
              </div>
            </div>
            <a href="/contact" class="btn">
              予約・お問い合わせ
            </a>
          </div>
          <div class="img-feature" style="background-image:url('https://miura-diving.com/wp-content/uploads/P6292740-1-scaled.jpg');"></div>
        </div>
      </div>
    </div>
    
    <!-- 即時予約促進CTA -->
<div class="cta-section">
  <div class="cta-circle-1"></div>
  <div class="cta-circle-2"></div>

  <div class="cta-content">
    <h3 class="cta-title">今すぐ体験ダイビングを予約する</h3>
    <p class="cta-text">
      <span style="font-weight:bold;">今週末の予約枠</span>があと<span style="font-weight:bold;">3
      </span>です！<br>
      新規のお客様は<span style="font-weight:bold; text-decoration:underline;">10%オフクーポン</span>プレゼント中
    </p>
    <div style="margin:0 auto; max-width:500px; display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
      <a href="/contact" class="btn btn-accent btn-lg" style="display:flex; align-items:center; justify-content:center; min-width:250px;">
        <span style="margin-right:8px;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="white"/>
          </svg>
        </span>
        メールで予約する
      </a>
      <a href="https://miura-diving.com/try-diving/" class="btn btn-light btn-lg" style="display:flex; align-items:center; justify-content:center; min-width:250px;">
        <span style="margin-right:8px;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" fill="currentColor"/>
          </svg>
        </span>
        詳細を見る
      </a>
    </div>
  </div>
</div>

    <!-- 初級コース -->
    <div id="beginner" class="reveal-on-scroll" style="scroll-margin-top:80px;">
      <h2 class="section-title" style="display:flex; align-items:center; justify-content:center;">
        <span style="background-color:var(--primary); color:var(--white); width:40px; height:40px; border-radius:50%; text-align:center; line-height:40px; margin-right:15px;">2</span>
        初級コース
      </h2>
      
      <!-- OWD講習 -->
      <div class="card mb-30">
        <!-- 人気コース強調表示 -->
        <div class="popular-course mb-20">
          <div class="popular-badge">
            人気No.1
          </div>
          <h3 style="color:var(--accent); margin-top:0; font-size:1.3rem;">オープンウォーターダイバーコース</h3>
          <div class="flex-row gap-20">
            <div class="flex-1 min-width-200">
              <p class="mb-10"><strong>リクエスト開催:</strong> なるべく早めにご予約ください</p>
              <p class="mb-10"><strong>料金:</strong> ¥53,900（税込）</p>
            </div>
            <div class="flex-1 min-width-200">
              <p class="mb-10"><strong>特典あり:</strong> いますぐチェック！</p>
              <p><strong>所要日数:</strong> 3日間（eラーニング・プール・海洋実習）</p>
            </div>
          </div>
          <div class="text-center" style="margin-top:15px;">
            <a href="https://miura-diving.com/owd-course/" class="btn btn-accent">詳細・お申し込みはこちら →</a>
          </div>
        </div>
        
        <div class="flex-row gap-30">
          <div class="flex-1 min-width-300">
            <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
              <img src="https://miura-diving.com/wp-content/uploads/オープンウォーターダイバー_Gold.png" alt="OWD" class="course-icon">
              オープンウォーターダイバー (OWD)
            </h3>
            <p class="mb-20">
              ダイビングの基本スキルを習得する入門コース。このライセンスを取得すれば、世界中の海で18mまでのダイビングを楽しめます。
            </p>
            <div class="mb-20">
              <div class="info-row">
                <div class="info-label">必要日数：</div>
                <div class="info-value">3日間</div>
              </div>
              <div class="info-row">
                <div class="info-label">ダイブ数：</div>
                <div class="info-value">プール講習＋海洋実習4ダイブ</div>
              </div>
              <div class="info-row">
                <div class="info-label">料金：</div>
                <div class="info-value">¥53,900（税込）</div>
              </div>
              <div class="info-row">
                <div class="info-label">含まれるもの：</div>
                <div class="info-value">eラーニング、ログブック、プール・海洋実習費、申請料、保険料</div>
              </div>
              <div class="info-row">
                <div class="info-label">別途必要：</div>
                <div class="info-value">レンタル器材代、水着、タオル、筆記用具、昼食代、交通費</div>
              </div>
            </div>
          </div>
          <div class="flex-1 min-width-300">
            <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
            <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
              <li>ダイビング器材の知識と使用法</li>
              <li>水中での呼吸と基本スキル</li>
              <li>耳抜きなどの圧力調整方法</li>
              <li>中性浮力の取り方</li>
              <li>水中サインと基本的なコミュニケーション</li>
              <li>バディシステムと安全管理</li>
              <li>トラブル対処法と緊急手順</li>
            </ul>
            <h4 style="color:var(--primary); margin-bottom:15px;">対象者</h4>
            <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
              <li>10歳以上（10〜14歳はジュニアOWD）</li>
              <li>健康状態に問題がない方</li>
              <li>基本的な泳力があると望ましい</li>
            </ul>
            <a href="https://miura-diving.com/owd-course/" class="btn btn-accent btn-lg" style="position:relative; box-shadow:0 4px 10px rgba(255,126,0,0.3);">
              <span style="position:relative; z-index:2; font-size:1.05rem;">PADIオープンウォーターコース詳細 →</span>
              <span style="position:absolute; top:-10px; right:-20px; background-color:var(--accent-light); color:#ff4500; font-size:0.7rem; padding:3px 8px; transform:rotate(45deg); box-shadow:0 2px 5px rgba(0,0,0,0.2);">人気No.1</span>
            </a>
          </div>
        </div>
      </div>
      <!-- アドバンスド・オープンウォーター・ダイバー (AOW) -->
<div class="card mb-30 reveal-on-scroll">
  <div class="flex-row gap-30">
    <div class="flex-1 min-width-300">
      <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
        <img src="https://miura-diving.com/wp-content/uploads/アドバンスドオープンウォーターダイバー_Gold.png" alt="AOW" class="course-icon">
        アドバンスド・オープンウォーター (AOW)
      </h3>
      <p class="mb-20">
        ディープダイビング、ナビゲーションなど5つの冒険ダイブを通じて、ダイビングの楽しさを知るコース。18mを超えるダイビングを経験します。
      </p>
      <div class="mb-20">
        <div class="info-row">
          <div class="info-label">必要日数：</div>
          <div class="info-value">2日間</div>
        </div>
        <div class="info-row">
          <div class="info-label">ダイブ数：</div>
          <div class="info-value">5ダイブ</div>
        </div>
        <div class="info-row">
          <div class="info-label">料金：</div>
          <div class="info-value">¥53,900（税込）</div>
        </div>
        <div class="info-row">
          <div class="info-label">含まれるもの：</div>
          <div class="info-value">教材一式、実習費、申請料、保険料</div>
        </div>
      </div>
      <div class="text-center" style="margin-top:15px;">
  <a href="https://miura-diving.com/aow-diving/" class="btn btn-accent">詳細・お申し込みはこちら →</a>
</div>
    </div>
    <div class="flex-1 min-width-300">
      <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
      <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
        <li>ディープダイビング</li>
        <li>水中ナビゲーション</li>
        <li>その他選択できる冒険ダイブ</li>
        <li>（ナイトダイビング、ドリフトダイビング、魚類の識別など）</li>
      </ul>
      <h4 style="color:var(--primary); margin-bottom:15px;">対象者</h4>
      <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
        <li>OWDライセンス保持者</li>
        <li>12歳以上（12〜14歳はジュニアAOW）</li>
        <li>より深い水深でのダイビングを楽しみたい方</li>
      </ul>
      <a href="/contact" class="btn">
        お問い合わせ
      </a>
    </div>
  </div>
</div>

<!-- EFR講習 -->
<div class="card mb-50 reveal-on-scroll">
  <div class="flex-row gap-30">
    <div class="flex-1 min-width-300">
      <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
        <img src="https://miura-diving.com/wp-content/uploads/エマージェンシーファーストレスポンス.jpeg" alt="EFR" class="course-icon">
        エマージェンシー・ファースト・レスポンス (EFR)
      </h3>
      <p class="mb-20">
        応急処置、CPR（心肺蘇生法）、AEDの使用法などを学ぶコースです。レスキューダイバーになるための前提条件にもなります。
      </p>
      <div class="mb-20">
        <div class="info-row">
          <div class="info-label">必要日数：</div>
          <div class="info-value">1日</div>
        </div>
        <div class="info-row">
          <div class="info-label">料金：</div>
          <div class="info-value">¥22,000（税込）</div>
        </div>
        <div class="info-row">
          <div class="info-label">含まれるもの：</div>
          <div class="info-value">教材一式、実習費、申請料</div>
        </div>
        <div class="info-row">
          <div class="info-label">別途必要：</div>
          <div class="info-value">筆記用具、昼食代</div>
        </div>
      </div>
    </div>
    <div class="flex-1 min-width-300">
      <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
      <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
        <li>一次救命処置（CPR）</li>
        <li>AEDの使用方法</li>
        <li>出血の処置</li>
        <li>ショックへの対応</li>
        <li>けがや病気の評価</li>
        <li>二次救命処置</li>
      </ul>
      <h4 style="color:var(--primary); margin-bottom:15px;">対象者</h4>
      <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
        <li>誰でも参加できます</li>
        <li>ダイビング経験の有無は問いません</li>
        <li>レスキューダイバーを目指す方</li>
        <li>応急処置スキルを身につけたい方</li>
      </ul>
      <a href="/contact" class="btn">
        お問い合わせ
      </a>
    </div>
  </div>
</div>

<!-- 中級コース -->
<div id="advanced" class="reveal-on-scroll" style="scroll-margin-top:80px;">
  <h2 class="section-title" style="display:flex; align-items:center; justify-content:center;">
    <span style="background-color:var(--primary); color:var(--white); width:40px; height:40px; border-radius:50%; text-align:center; line-height:40px; margin-right:15px;">3</span>
    中級コース
  </h2>
  
  <!-- レスキュー・ダイバー -->
  <div class="card mb-50 reveal-on-scroll">
    <div class="flex-row gap-30">
      <div class="flex-1 min-width-300">
        <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
          <img src="https://miura-diving.com/wp-content/uploads/レスキューダイバー_Gold.png" alt="Rescue" class="course-icon">
          レスキュー・ダイバー
        </h3>
        <p class="mb-20">
          ダイビングの安全と救助について学ぶコース。自己救助スキルと他者を助けるための技術を習得し、より安全な環境を作るダイバーになります。
        </p>
        <div class="mb-20">
          <div class="info-row">
            <div class="info-label">必要日数：</div>
            <div class="info-value">2日間</div>
          </div>
          <div class="info-row">
            <div class="info-label">前提条件：</div>
            <div class="info-value">EFRコース修了もしくは同等資格（2年以内）</div>
          </div>
          <div class="info-row">
            <div class="info-label">料金：</div>
            <div class="info-value">¥64,900（税込）</div>
          </div>
          <div class="info-row">
            <div class="info-label">含まれるもの：</div>
            <div class="info-value">教材一式、実習費、申請料、保険料</div>
          </div>
        </div>
      </div>
      <div class="flex-1 min-width-300">
        <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
        <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
          <li>ダイバーのストレスや疲労の認識方法</li>
          <li>緊急時の対応計画</li>
          <li>行方不明ダイバーの捜索</li>
          <li>水面でのレスキュー技術</li>
          <li>パニックダイバーへの対応</li>
          <li>無反応ダイバーの救助と搬送</li>
        </ul>
        <h4 style="color:var(--primary); margin-bottom:15px;">対象者</h4>
        <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
          <li>12歳以上</li>
          <li>AOWライセンス保持者もしくは同等資格</li>
          <li>有効なEFR資格もしくは同等資格保持者（修了から2年以内）</li>
        </ul>
        <div style="display:flex; gap:10px;">
          <a href="/contact" class="btn">
            お問い合わせ
          </a>
          <a href="https://miura-diving.com/rescue-diver/" class="btn btn-outline">
            詳細を見る
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- スペシャルティコース -->
<div id="specialty" class="reveal-on-scroll" style="scroll-margin-top:80px;">
  <h2 class="section-title" style="display:flex; align-items:center; justify-content:center;">
    <span style="background-color:var(--primary); color:var(--white); width:40px; height:40px; border-radius:50%; text-align:center; line-height:40px; margin-right:15px;">4</span>
    スペシャルティコース
  </h2>
  
  <p class="text-center mb-30" style="max-width:900px; margin-left:auto; margin-right:auto;">
    特定の分野でのスキルを磨くスペシャルティコース。OWDなどライセンス保持者が対象です。5つのスペシャルティを取得し条件を満たすと「マスター・スクーバ・ダイバー」の称号を得られます。
  </p>
  
  <!-- スペシャルティグリッド -->
  <div class="specialty-grid">
    <!-- PPB（中性浮力） -->
    <div class="card">
      <h3 style="color:var(--primary); font-size:1.2rem; margin-bottom:10px; display:flex; align-items:center;">
        <span style="min-width:24px; height:24px; border-radius:50%; background-color:var(--primary); color:var(--white); text-align:center; line-height:24px; margin-right:10px; font-size:0.8rem;">SP</span>
        PPB（中性浮力）
      </h3>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">日数/ダイブ：</div>
        <div class="info-value" style="font-size:0.9rem;">1日/2ダイブ</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">料金：</div>
        <div class="info-value" style="font-size:0.9rem;">¥27,500（税込）</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">内容：</div>
        <div class="info-value" style="font-size:0.9rem;">中性浮力の取り方とコントロール技術を習得</div>
      </div>
      <p style="color:var(--text-medium); font-size:0.9rem; margin-top:10px;">実習費、教材費、申請料込み</p>
    </div>
    
    <!-- ドライスーツダイバー -->
    <div class="card">
      <h3 style="color:var(--primary); font-size:1.2rem; margin-bottom:10px; display:flex; align-items:center;">
        <span style="min-width:24px; height:24px; border-radius:50%; background-color:var(--primary); color:var(--white); text-align:center; line-height:24px; margin-right:10px; font-size:0.8rem;">SP</span>
        ドライスーツダイバー
      </h3>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">日数/ダイブ：</div>
        <div class="info-value" style="font-size:0.9rem;">1日/2ダイブ</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">料金：</div>
        <div class="info-value" style="font-size:0.9rem;">¥27,500（税込）</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">内容：</div>
        <div class="info-value" style="font-size:0.9rem;">ドライスーツの使用方法と浮力調整技術</div>
      </div>
      <p style="color:var(--text-medium); font-size:0.9rem; margin-top:10px;">実習費、教材費、申請料込み</p>
    </div>
    
    <!-- ボートダイバー -->
    <div class="card">
      <h3 style="color:var(--primary); font-size:1.2rem; margin-bottom:10px; display:flex; align-items:center;">
        <span style="min-width:24px; height:24px; border-radius:50%; background-color:var(--primary); color:var(--white); text-align:center; line-height:24px; margin-right:10px; font-size:0.8rem;">SP</span>
        ボートダイバー
      </h3>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">日数/ダイブ：</div>
        <div class="info-value" style="font-size:0.9rem;">1日/2ダイブ</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">料金：</div>
        <div class="info-value" style="font-size:0.9rem;">¥36,300（税込）</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">内容：</div>
        <div class="info-value" style="font-size:0.9rem;">ボートからのエントリー/エキジット方法等</div>
      </div>
      <p style="color:var(--text-medium); font-size:0.9rem; margin-top:10px;">実習費、教材費、申請料、ボート代込み</p>
    </div>
    
    <!-- 魚の見分け方 -->
    <div class="card">
      <h3 style="color:var(--primary); font-size:1.2rem; margin-bottom:10px; display:flex; align-items:center;">
        <span style="min-width:24px; height:24px; border-radius:50%; background-color:var(--primary); color:var(--white); text-align:center; line-height:24px; margin-right:10px; font-size:0.8rem;">SP</span>
        魚の見分け方
      </h3>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">日数/ダイブ：</div>
        <div class="info-value" style="font-size:0.9rem;">1日/2ダイブ</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">料金：</div>
        <div class="info-value" style="font-size:0.9rem;">¥27,500（税込）</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">内容：</div>
        <div class="info-value" style="font-size:0.9rem;">魚の見分け方の知識と観察方法</div>
      </div>
      <p style="color:var(--text-medium); font-size:0.9rem; margin-top:10px;">実習費、教材費、申請料込み</p>
    </div>
    
    <!-- デジタルフォトグラファー -->
    <div class="card">
      <h3 style="color:var(--primary); font-size:1.2rem; margin-bottom:10px; display:flex; align-items:center;">
        <span style="min-width:24px; height:24px; border-radius:50%; background-color:var(--primary); color:var(--white); text-align:center; line-height:24px; margin-right:10px; font-size:0.8rem;">SP</span>
        デジタルフォトグラファー
      </h3>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">日数/ダイブ：</div>
        <div class="info-value" style="font-size:0.9rem;">2日/2ダイブ</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">料金：</div>
        <div class="info-value" style="font-size:0.9rem;">¥27,500（税込）</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">内容：</div>
        <div class="info-value" style="font-size:0.9rem;">水中写真の撮影テクニック</div>
      </div>
      <p style="color:var(--text-medium); font-size:0.9rem; margin-top:10px;">実習費、教材費、申請料込み（カメラレンタル別）</p>
    </div>
    
    <!-- ナビゲーション -->
    <div class="card">
      <h3 style="color:var(--primary); font-size:1.2rem; margin-bottom:10px; display:flex; align-items:center;">
        <span style="min-width:24px; height:24px; border-radius:50%; background-color:var(--primary); color:var(--white); text-align:center; line-height:24px; margin-right:10px; font-size:0.8rem;">SP</span>
        ナビゲーション
      </h3>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">日数/ダイブ：</div>
        <div class="info-value" style="font-size:0.9rem;">1日/3ダイブ</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">料金：</div>
        <div class="info-value" style="font-size:0.9rem;">¥36,300（税込）</div>
      </div>
      <div class="info-row mb-5">
        <div class="info-label" style="min-width:100px; font-size:0.9rem;">内容：</div>
        <div class="info-value" style="font-size:0.9rem;">コンパスや自然の目印を使った水中ナビゲーション</div>
      </div>
      <p style="color:var(--text-medium); font-size:0.9rem; margin-top:10px;">実習費、教材費、申請料込み</p>
    </div>
    
    <!-- その他のスペシャルティコース（横スクロール形式で表示） -->
    <div class="card" style="grid-column: 1 / -1; padding: 0; overflow: hidden;">
      <div style="padding: 20px; background-color: rgba(30, 115, 190, 0.05);">
        <h3 style="color:var(--primary); font-size:1.2rem; margin-bottom:15px;">その他のスペシャルティコース</h3>
        <div style="overflow-x: auto; padding-bottom: 10px;">
          <div style="display: flex; gap: 15px;">
            <div class="card" style="min-width: 250px; margin: 0;">
              <h4 style="color:var(--primary); font-size:1.1rem; margin-bottom:10px;">ディープダイバー</h4>
              <p style="font-size:0.9rem; margin-bottom:5px;">2日/4ダイブ・¥49,800（税込）</p>
              <p style="font-size:0.9rem;">水深19m〜30mでの安全なダイビング技術</p>
            </div>
            <div class="card" style="min-width: 250px; margin: 0;">
              <h4 style="color:var(--primary); font-size:1.1rem; margin-bottom:10px;">ナチュラリスト</h4>
              <p style="font-size:0.9rem; margin-bottom:5px;">1日/2ダイブ・¥27,500（税込）</p>
              <p style="font-size:0.9rem;">海洋生態系と環境保全について学ぶ</p>
            </div>
            <div class="card" style="min-width: 250px; margin: 0;">
              <h4 style="color:var(--primary); font-size:1.1rem; margin-bottom:10px;">サーチ＆リカバリー</h4>
              <p style="font-size:0.9rem; margin-bottom:5px;">2日/4ダイブ・¥44,500（税込）</p>
              <p style="font-size:0.9rem;">水中での物体の捜索と回収技術</p>
            </div>
            <div class="card" style="min-width: 250px; margin: 0;">
              <h4 style="color:var(--primary); font-size:1.1rem; margin-bottom:10px;">ナイトダイバー</h4>
              <p style="font-size:0.9rem; margin-bottom:5px;">1日/3ダイブ・¥44,500（税込）</p>
              <p style="font-size:0.9rem;">夜間ダイビングの技術と安全管理</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- マスタースクーバダイバー -->
  <div class="card text-center mb-50 reveal-on-scroll">
    <h3 style="color:var(--primary); font-size:1.6rem; margin-bottom:15px;">マスター・スクーバ・ダイバー (MSD)</h3>
    <p class="mb-20">
      PADIアドバンスドオープンウォーターダイバー、PADIレスキューダイバー資格を持ち、5つのスペシャルティコースを修了すると、「マスター・スクーバ・ダイバー」の称号を取得できます。ダイビングの幅広い知識と経験を持つダイバーとして認められる証です。
    </p>
    <div style="display:inline-block; background-color:var(--primary); color:var(--white); padding:15px 30px; border-radius:var(--radius-sm); font-weight:bold;">
      取得条件：AOW + レスキュー + 5つのスペシャルティ + ログ50本以上
    </div>
  </div>
</div>

<!-- プロコース -->
<div id="pro" class="reveal-on-scroll" style="scroll-margin-top:80px;">
  <h2 class="section-title" style="display:flex; align-items:center; justify-content:center;">
    <span style="background-color:var(--primary); color:var(--white); width:40px; height:40px; border-radius:50%; text-align:center; line-height:40px; margin-right:15px;">5</span>
    プロフェッショナルコース
  </h2>
  
  <p class="text-center mb-30" style="max-width:900px; margin-left:auto; margin-right:auto;">
    ダイビングインストラクターを目指す方のためのプロフェッショナルコース。三浦 海の学校では、PADIコースディレクターが直接指導する高品質なプロ育成プログラムを提供しています。
  </p>

  <!-- プロコースの概要（アップルスタイル） -->
  <div class="apple-callout mb-50">
    <h3>ダイビングのプロを目指す</h3>
    <p>三浦 海の学校では、日本を代表するコースディレクターが直接指導する充実したプロフェッショナルコースをご用意。<br>あなたのスキルと情熱をプロフェッショナルキャリアに変えるお手伝いをします。</p>
    
    <div class="apple-grid">
      <div class="apple-grid-item">
        <h4>充実の設備</h4>
        <p>専用プールと目の前の海を活用した実践的な練習環境で、インストラクターとしての技術を磨きます。</p>
      </div>
      <div class="apple-grid-item">
        <h4>少人数制指導</h4>
        <p>インストラクター1名に対して最大4名までの少人数制で、きめ細かい指導を受けられます。</p>
      </div>
      <div class="apple-grid-item">
        <h4>就職サポート</h4>
        <p>コース修了後の就職先紹介や独立支援など、卒業後のキャリアパスもサポートします。</p>
      </div>
    </div>
  </div>
  
  <!-- ダイブマスター -->
<div class="card mb-30 reveal-on-scroll">
  <div class="flex-row gap-30">
    <div class="flex-1 min-width-300">
      <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
        <img src="https://miura-diving.com/wp-content/uploads/ダイブマスター.png" alt="Divemaster" class="course-icon">
        ダイブマスター
      </h3>
      <p class="mb-20">
        プロフェッショナルへの第一歩。ダイビングのリーダーシップスキルを身につけ、インストラクターのアシスタントとして活動できます。
      </p>

<!-- ▼ ダイブマスター：料金などの表 ▼ -->
<div class="mb-20">
  <div class="info-row">
    <div class="info-label">期間：</div>
    <div class="info-value">10日間〜</div>
  </div>

  <div class="info-row">
    <div class="info-label">前提条件：</div>
    <div class="info-value">18歳以上、レスキューダイバー、EFR、ログ40本以上</div>
  </div>

  <div class="info-row">
    <div class="info-label">料金：</div>
    <div class="info-value">¥132,000（税込）</div>
  </div>

  <div class="info-row">
    <div class="info-label">含まれるもの：</div>
    <div class="info-value">講習費</div>
  </div>
</div>
<!-- ▲ ここまで -->

      <a href="/contact" class="btn">お問い合わせ</a>
    </div>

    <div class="flex-1 min-width-300">
      <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
      <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
        <li>ダイビングの知識とスキルの向上</li>
        <li>ダイビングリーダーシップの開発</li>
        <li>ダイビング理論の習得</li>
        <li>実地でのダイビング管理</li>
        <li>インストラクターの補助業務</li>
        <li>ダイビングイベント企画と実施</li>
      </ul>
    </div>
  </div>
</div>

    
  <!-- アシスタントインストラクター -->
  <div class="card mb-30 reveal-on-scroll">
    <div class="flex-row gap-30">
      <div class="flex-1 min-width-300">
        <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
          <img src="https://miura-diving.com/wp-content/uploads/アシスタントインストラクター.png" alt="AI" class="course-icon">
          アシスタントインストラクター (AI)
        </h3>
        <p class="mb-20">
          インストラクターになるための準備段階。限定された範囲でプログラムを教えることができ、インストラクターの業務をより深く学びます。
        </p>
        <div class="mb-20">
          <div class="info-row">
            <div class="info-label">期間：</div>
            <div class="info-value">4日間〜</div>
          </div>
          <div class="info-row">
            <div class="info-label">前提条件：</div>
            <div class="info-value">18歳以上、ダイブマスター資格、ログ60本以上</div>
          </div>
          <div class="info-row">
            <div class="info-label">料金：</div>
            <div class="info-value">¥132,000（税込）</div>
          </div>
          <div class="info-row">
            <div class="info-label">含まれるもの：</div>
            <div class="info-value">講習費</div>
          </div>
        </div>
      </div>
      <div class="flex-1 min-width-300">
        <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
        <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
          <li>インストラクター開発の基礎</li>
          <li>教授法と評価技術</li>
          <li>プレゼンテーションスキル</li>
          <li>プール・限定水域での指導</li>
          <li>ダイビング器材の専門知識</li>
          <li>リスク管理と安全手順</li>
        </ul>
        <a href="/contact" class="btn">
          お問い合わせ
        </a>
      </div>
    </div>
  </div>
    
  <!-- インストラクター開発コース (IDC) -->
  <div class="card mb-50 reveal-on-scroll">
    <div class="flex-row gap-30">
      <div class="flex-1 min-width-300">
        <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
          <img src="https://miura-diving.com/wp-content/uploads/インストラクター.png" alt="IDC" class="course-icon">
          インストラクター開発コース (IDC)
        </h3>
        <p class="mb-20">
          PADIオープンウォーターインストラクターになるための包括的なトレーニングプログラム。コースディレクターが直接指導します。
        </p>
        <div class="mb-20">
          <div class="info-row">
            <div class="info-label">期間：</div>
            <div class="info-value">8〜10日間</div>
          </div>
          <div class="info-row">
            <div class="info-label">前提条件：</div>
            <div class="info-value">18歳以上、ダイブマスター、ログ60本以上</div>
          </div>
          <div class="info-row">
            <div class="info-label">料金：</div>
            <div class="info-value">¥220,000（税込）</div>
          </div>
          <div class="info-row">
            <div class="info-label">含まれるもの：</div>
            <div class="info-value">講習費</div>
          </div>
        </div>
      </div>
      <div class="flex-1 min-width-300">
        <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
        <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
          <li>PADIシステム概要と規格</li>
          <li>プロフェッショナルとしての教授技術</li>
          <li>学科講義の指導方法</li>
          <li>限定水域・オープンウォーターでのスキル指導</li>
          <li>リスク管理とダイバー安全</li>
          <li>インストラクター試験準備</li>
        </ul>
        <a href="/contact" class="btn">
          お問い合わせ
        </a>
      </div>
    </div>
  </div>
  
  <!-- IDCスタッフインストラクター -->
  <div class="card mb-30 reveal-on-scroll">
    <div class="flex-row gap-30">
      <div class="flex-1 min-width-300">
        <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
          <img src="https://miura-diving.com/wp-content/uploads/IDCスタッフインストラクター.png" alt="IDCS" class="course-icon">
          IDCスタッフインストラクター
        </h3>
        <p class="mb-20">
          PADIコースディレクターのアシスタントとして活動し、インストラクター育成に携わるための資格。
        </p>
        <div class="mb-20">
          <div class="info-row">
            <div class="info-label">期間：</div>
            <div class="info-value">最短4日間〜</div>
          </div>
          <div class="info-row">
            <div class="info-label">前提条件：</div>
            <div class="info-value">PADIインストラクター資格、ログ150本以上（推奨）</div>
          </div>
          <div class="info-row">
            <div class="info-label">料金：</div>
            <div class="info-value">¥98,000（税込）</div>
          </div>
          <div class="info-row">
            <div class="info-label">含まれるもの：</div>
            <div class="info-value">講習費</div>
          </div>
        </div>
      </div>
      <div class="flex-1 min-width-300">
        <h4 style="color:var(--primary); margin-bottom:15px;">学習内容</h4>
        <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
          <li>インストラクター候補生の評価方法</li>
          <li>IDCプログラムの運営補助</li>
          <li>インストラクター育成技術</li>
          <li>PADIコースの最新基準と教材</li>
          <li>プレゼンテーション指導テクニック</li>
          <li>教授法の応用と評価</li>
        </ul>
        <a href="/contact" class="btn">
          お問い合わせ
        </a>
      </div>
    </div>
  </div>

  <!-- スペシャルティインストラクター -->
  <div class="card mb-50 reveal-on-scroll">
    <div class="flex-row gap-30">
      <div class="flex-1 min-width-300">
        <h3 style="color:var(--primary); font-size:1.4rem; margin-bottom:15px; display:flex; align-items:center;">
          <img src="https://miura-diving.com/wp-content/uploads/スペシャルティインストラクター.png" alt="SPI" class="course-icon">
          スペシャルティインストラクター (SPI)
        </h3>
        <p class="mb-20">
          特定のスペシャルティコースを教えることができるインストラクター資格。各スペシャルティごとに取得が必要です。
        </p>
        <div class="mb-20">
          <div class="info-row">
            <div class="info-label">期間：</div>
            <div class="info-value">各スペシャルティ1〜2日間</div>
          </div>
          <div class="info-row">
            <div class="info-label">前提条件：</div>
            <div class="info-value">PADIインストラクター資格、該当スペシャルティのログ実績</div>
          </div>
          <div class="info-row">
            <div class="info-label">料金：</div>
            <div class="info-value">お問い合わせください</div>
          </div>
          <div class="info-row">
            <div class="info-label">含まれるもの：</div>
            <div class="info-value">講習費</div>
          </div>
        </div>
      </div>
      <div class="flex-1 min-width-300">
        <h4 style="color:var(--primary); margin-bottom:15px;">主なスペシャルティ</h4>
        <ul style="color:var(--text-medium); padding-left:20px; margin-bottom:20px; line-height:1.6;">
          <li>ナイトダイビング</li>
          <li>ディープダイビング</li>
          <li>水中ナビゲーション</li>
          <li>ドライスーツダイビング</li>
          <li>水中写真</li>
          <li>魚類の識別</li>
          <li>ボートダイビング</li>
          <li>ドリフトダイビング</li>
        </ul>
        <a href="/contact" class="btn">
          お問い合わせ
        </a>
      </div>
    </div>
  </div>
</div>

<!-- キャリアパスビジュアル（アップルスタイル） -->
<div class="apple-callout mb-50 reveal-on-scroll">
  <h3>あなたのダイビングキャリアパス</h3>
  <p>初心者からプロフェッショナルまで、ステップバイステップで成長できる体系的なプログラムをご用意しています。<br>あなたの目標や希望に合わせたカスタマイズも可能です。</p>
  
  <div style="max-width:800px; margin:0 auto; position:relative;">
    <div style="height:600px; width:100%; background:linear-gradient(to bottom, var(--primary-light), var(--primary-dark)); border-radius:20px; position:relative; overflow:hidden;">
      <!-- キャリアパスの図 -->
      <div style="position:absolute; width:80%; height:80%; top:10%; left:10%; display:flex; flex-direction:column; justify-content:space-between;">
        <!-- レベル1: 初心者 -->
        <div style="background:rgba(255,255,255,0.9); padding:15px; border-radius:10px; display:flex; align-items:center; box-shadow:0 5px 15px rgba(0,0,0,0.1); position:relative;">
          <div style="flex:0 0 60px; height:60px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:15px;">
            <span style="color:white; font-weight:bold; font-size:1.2rem;">1</span>
          </div>
          <div>
            <h4 style="margin:0 0 5px; color:var(--primary);">初心者</h4>
            <p style="margin:0; font-size:0.9rem;">体験ダイビング → オープンウォーターダイバー</p>
          </div>
          <div style="position:absolute; left:30px; bottom:-40px; width:2px; height:40px; background:rgba(255,255,255,0.7);"></div>
        </div>
        
        <!-- レベル2: 中級者 -->
        <div style="background:rgba(255,255,255,0.9); padding:15px; border-radius:10px; display:flex; align-items:center; box-shadow:0 5px 15px rgba(0,0,0,0.1); position:relative; margin-left:50px;">
          <div style="flex:0 0 60px; height:60px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:15px;">
            <span style="color:white; font-weight:bold; font-size:1.2rem;">2</span>
          </div>
          <div>
            <h4 style="margin:0 0 5px; color:var(--primary);">中級者</h4>
            <p style="margin:0; font-size:0.9rem;">アドバンス → EFR → レスキュー</p>
          </div>
          <div style="position:absolute; left:30px; bottom:-40px; width:2px; height:40px; background:rgba(255,255,255,0.7);"></div>
        </div>
        
        <!-- レベル3: 上級者 -->
        <div style="background:rgba(255,255,255,0.9); padding:15px; border-radius:10px; display:flex; align-items:center; box-shadow:0 5px 15px rgba(0,0,0,0.1); position:relative; margin-left:100px;">
          <div style="flex:0 0 60px; height:60px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:15px;">
            <span style="color:white; font-weight:bold; font-size:1.2rem;">3</span>
          </div>
          <div>
            <h4 style="margin:0 0 5px; color:var(--primary);">上級者</h4>
            <p style="margin:0; font-size:0.9rem;">各種スペシャルティ → マスタースクーバダイバー</p>
          </div>
          <div style="position:absolute; left:30px; bottom:-40px; width:2px; height:40px; background:rgba(255,255,255,0.7);"></div>
        </div>
        
        <!-- レベル4: プロフェッショナル -->
        <div style="background:rgba(255,255,255,0.9); padding:15px; border-radius:10px; display:flex; align-items:center; box-shadow:0 5px 15px rgba(0,0,0,0.1); position:relative; margin-left:150px;">
          <div style="flex:0 0 60px; height:60px; background:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:15px;">
            <span style="color:white; font-weight:bold; font-size:1.2rem;">4</span>
          </div>
          <div>
            <h4 style="margin:0 0 5px; color:var(--primary);">プロ</h4>
            <p style="margin:0; font-size:0.9rem;">ダイブマスター → アシスタントインストラクター → インストラクター</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- お問い合わせCTA -->
<div style="background:linear-gradient(135deg, var(--primary), var(--primary-light)); padding:40px; border-radius:var(--radius-lg); text-align:center; color:var(--white); margin-bottom:50px;" class="reveal-on-scroll">
  <h2 style="color:var(--white); font-size:2rem; margin-bottom:20px;">ダイビングライセンスの取得をサポートします</h2>
  <p style="font-size:1.1rem; margin-bottom:30px; line-height:1.6;">
    初心者の方も、ステップアップを目指す方も、プロを目指す方も、<br>三浦 海の学校で安心してスキルを身につけませんか？
  </p>
  <a href="/contact" class="btn" style="background-color:var(--white); color:var(--primary); padding:15px 40px; border-radius:var(--radius-xl); font-size:1.2rem; box-shadow:var(--shadow-lg);">
    お問い合わせ・お申し込みはこちら
  </a>
</div>

<!-- お客様の声セクション -->
<div class="card mb-50 reveal-on-scroll">
  <h2 class="section-title">お客様の声</h2>
  
  <div class="flex-row gap-20" style="justify-content:center; margin-bottom:30px;">
    <!-- レビュー1 -->
    <div class="stagger-item" style="flex:1; min-width:300px; max-width:380px; background-color:#f8f9fa; border-radius:var(--radius-md); padding:25px; position:relative;">
      <div style="position:absolute; top:-15px; left:20px; background-color:var(--accent-light); color:var(--text-dark); font-weight:bold; padding:5px 15px; border-radius:20px; font-size:0.9rem;">
        オープンウォーターコース
      </div>
      <div style="color:var(--primary); margin-bottom:10px;">
        <span style="color:var(--accent-light);">★★★★★</span> 5.0
      </div>
      <p style="color:var(--text-medium); line-height:1.6; margin-bottom:15px;">
        初めてのダイビングで不安でしたが、丁寧な説明と安全への配慮が徹底していたので安心して講習を受けられました。海の中は想像以上に美しく、感動しました！
      </p>
      <div style="display:flex; align-items:center;">
        <div style="width:50px; height:50px; border-radius:50%; overflow:hidden; margin-right:15px;">
          <img src="https://miura-diving.com/wp-content/uploads/guest-voice5.webp" alt="お客様" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <div>
          <p style="margin:0; font-weight:bold; color:var(--text-dark);">田中 健太 様</p>
          <p style="margin:0; font-size:0.9rem; color:var(--text-light);">東京都 30代</p>
        </div>
      </div>
    </div>
    
    <!-- レビュー2 -->
    <div class="stagger-item" style="flex:1; min-width:300px; max-width:380px; background-color:#f8f9fa; border-radius:var(--radius-md); padding:25px; position:relative;">
      <div style="position:absolute; top:-15px; left:20px; background-color:var(--accent-light); color:var(--text-dark); font-weight:bold; padding:5px 15px; border-radius:20px; font-size:0.9rem;">
        アドバンスコース
      </div>
      <div style="color:var(--primary); margin-bottom:10px;">
        <span style="color:var(--accent-light);">★★★★★</span> 5.0
      </div>
      <p style="color:var(--text-medium); line-height:1.6; margin-bottom:15px;">
        インストラクターの吉田さんの指導がとても分かりやすく、不安だったナビゲーションも楽しく学べました。スキルが上がったことで海中での余裕が生まれ、より楽しめるようになりました！
      </p>
      <div style="display:flex; align-items:center;">
        <div style="width:50px; height:50px; border-radius:50%; overflow:hidden; margin-right:15px;">
          <img src="https://miura-diving.com/wp-content/uploads/guest-voice6.webp" alt="お客様" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <div>
          <p style="margin:0; font-weight:bold; color:var(--text-dark);">佐藤 美香 様</p>
          <p style="margin:0; font-size:0.9rem; color:var(--text-light);">神奈川県 40代</p>
        </div>
      </div>
    </div>
    
    <!-- レビュー3 -->
    <div class="stagger-item" style="flex:1; min-width:300px; max-width:380px; background-color:#f8f9fa; border-radius:var(--radius-md); padding:25px; position:relative;">
      <div style="position:absolute; top:-15px; left:20px; background-color:var(--accent-light); color:var(--text-dark); font-weight:bold; padding:5px 15px; border-radius:20px; font-size:0.9rem;">
        体験ダイビング
      </div>
      <div style="color:var(--primary); margin-bottom:10px;">
        <span style="color:var(--accent-light);">★★★★★</span> 5.0
      </div>
      <p style="color:var(--text-medium); line-height:1.6; margin-bottom:15px;">
        家族での初めての体験ダイビングでしたが、子どもたちの対応も素晴らしく安心して楽しめました。スタッフの方々の笑顔と熱心なサポートに感謝です。また必ず訪れたいと思います！
      </p>
      <div style="display:flex; align-items:center;">
        <div style="width:50px; height:50px; border-radius:50%; overflow:hidden; margin-right:15px;">
          <img src="https://miura-diving.com/wp-content/uploads/guest-voice7.webp" alt="お客様" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <div>
          <p style="margin:0; font-weight:bold; color:var(--text-dark);">鈴木 一家 様</p>
          <p style="margin:0; font-size:0.9rem; color:var(--text-light);">千葉県</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- よくある質問 -->
<div class="card mb-50 reveal-on-scroll">
  <h2 class="section-title">よくある質問</h2>

  <div class="faq-list" style="max-width:900px;margin:0 auto;">
    <!-- Q1 -->
    <div class="faq-item">
      <button class="faq-question">
        Q. どのコースから始めれば良いですか？<span class="toggle-icon"></span>
      </button>
      <div class="faq-answer">
        A. 全くの初心者の方は、まず「体験ダイビング」で水中の感覚を体験するのがおすすめです。その後「オープンウォーターダイバー（OWD）」コースでライセンスを取得するのが一般的です。
      </div>
    </div>

    <!-- Q2 -->
    <div class="faq-item">
      <button class="faq-question">
        Q. ライセンスの有効期限はありますか？<span class="toggle-icon"></span>
      </button>
      <div class="faq-answer">
        A. PADIのライセンス自体に有効期限はありませんが、長期間潜っていない場合は「リフレッシュコース」を受講すると安心です。
      </div>
    </div>

    <!-- Q3 -->
    <div class="faq-item">
      <button class="faq-question">
        Q. コースの予約はどのくらい前が良いですか？<span class="toggle-icon"></span>
      </button>
      <div class="faq-answer">
        A. 週末や連休はすぐ満席になります。1〜2か月前のご予約がおすすめです。
      </div>
    </div>

    <!-- Q4 -->
    <div class="faq-item">
      <button class="faq-question">
        Q. お支払い方法を教えて下さい<span class="toggle-icon"></span>
      </button>
      <div class="faq-answer">
        A. 現金または事前銀行振込でお願いしております。クレジットカードはご利用いただけません。
      </div>
    </div>
  </div>
</div>

<!-- ===== FAQ 用の最小 CSS & JS ===== -->
<style>
.faq-question{
  width:100%;text-align:left;padding:15px;
  background:rgba(30,115,190,.05);border:none;cursor:pointer;
  font-weight:bold;color:var(--primary);position:relative;
}
.faq-answer{display:none;padding:15px;border-top:1px solid rgba(0,0,0,.05);}
.faq-item.is-open .faq-answer{display:block;}
.toggle-icon::after{
  content:'+';position:absolute;right:15px;transition:.2s;font-weight:normal;
}
.faq-item.is-open .toggle-icon::after{content:'−';transform:rotate(180deg);}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('.faq-question').forEach(function(btn){
    btn.addEventListener('click',function(){
      this.parentElement.classList.toggle('is-open');
    });
  });
});
</script>

<!-- コースディレクター紹介 -->
<div class="mb-50 reveal-on-scroll">
  <h2 class="section-title">コースディレクター紹介</h2>
  
  <div class="card" style="display:flex; flex-wrap:wrap; align-items:center; gap:30px;">
    <div style="width:200px; height:200px; border-radius:50%; overflow:hidden; flex-shrink:0; margin:0 auto; position:relative;">
      <img src="https://miura-diving.com/wp-content/uploads/IMG_1523-scaled.jpg" alt="吉田" style="width:100%; height:100%; object-fit:cover;">
      <div style="position:absolute; top:0; left:0; right:0; bottom:0; background:linear-gradient(to top, rgba(0,0,0,0.4), transparent); opacity:0; transition:var(--transition);"></div>
    </div>
    <div class="flex-1 min-width-300">
      <h3 style="color:var(--primary); margin:0 0 10px; font-size:1.6rem;">吉田</h3>
      <p style="color:var(--text-light); margin:0 0 20px; font-size:1rem;">PADIコースディレクター / ABL代表</p>
      <p style="color:var(--text-medium); margin:0 0 15px; line-height:1.6;">
        1997年からダイビングプロフェッショナルとして活動。これまでに育成したダイバーは1000名以上にのぼります。PADI認定の最高指導者資格であるコースディレクターとして、初心者からプロフェッショナルまで、あらゆるレベルのダイバーを指導しています。
      </p>
      <p style="color:var(--text-medium); margin:0; line-height:1.6;">
        「安全第一を常に心がけ、楽しくダイビングを学べる環境作り」をモットーに、一人ひとりの特性に合わせた丁寧な指導を行います。特にプロフェッショナルコースでは、技術指導だけでなく、就職支援やキャリアアドバイスも提供しています。
      </p>
    </div>
  </div>
</div>

</div>
</div>

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
                <h3 class="footer-title">ダイビングコース</h3>
                <ul class="footer-links" style="z-index: 10; position: relative; pointer-events: auto;">
                    <li><a href="https://miura-diving.com/license/#experience" style="display: inline-block; padding: 5px 0; pointer-events: auto;">体験ダイビング</a></li>
                    <li><a href="https://miura-diving.com/license/#beginner" style="display: inline-block; padding: 5px 0; pointer-events: auto;">初級コース</a></li>
                    <li><a href="https://miura-diving.com/license/#advanced" style="display: inline-block; padding: 5px 0; pointer-events: auto;">中級コース</a></li>
                    <li><a href="https://miura-diving.com/license/#specialty" style="display: inline-block; padding: 5px 0; pointer-events: auto;">スペシャルティ</a></li>
                    <li><a href="https://miura-diving.com/license/#pro" style="display: inline-block; padding: 5px 0; pointer-events: auto;">プロコース</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">お問い合わせ</h3>
                <p><strong>所在地:</strong> 〒238-0224 神奈川県三浦市三崎町諸磯1621</p>
                <p><strong>電話:</strong> <a href="tel:046-880-0835" style="pointer-events: auto;">046-880-0835</a></p>
                <p><strong>メール:</strong> <a href="mailto:info@miura-diving.com" style="pointer-events: auto;">info@miura-diving.com</a></p>
                <p><strong>営業時間:</strong> 8:00〜16:00　不定休</p>
            </div>
            
            <div class="footer-col">
                <h3 class="footer-title">リンク</h3>
                <ul class="footer-links" style="z-index: 10; position: relative; pointer-events: auto;">
                    <li><a href="https://miura-diving.com/" style="display: inline-block; padding: 5px 0; pointer-events: auto;">ホーム</a></li>
                    <li><a href="https://miura-diving.com/fun-diving/" style="display: inline-block; padding: 5px 0; pointer-events: auto;">ファンダイビング</a></li>
                    <li><a href="https://miura-diving.com/marine-activity/" style="display: inline-block; padding: 5px 0; pointer-events: auto;">マリンアクティビティ</a></li>
                    <li><a href="https://miura-diving.com/contact/" style="display: inline-block; padding: 5px 0; pointer-events: auto;">お問い合わせ</a></li>
                    <li><a href="https://aquabit-lab.com/" style="display: inline-block; padding: 5px 0; pointer-events: auto;">AquaBit LAB</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="copyright">Copyright © 2025 AquaBit LAB All Rights Reserved.</p>
            <p class="privacy-link"><a href="https://miura-diving.com/privacy/" style="pointer-events: auto;">プライバシーポリシー</a></p>
        </div>
    </div>
</footer>

<!-- スムーズスクロールとパララックス効果のためのJavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  // スムーズスクロール
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);
      
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: 'smooth'
        });
        
        // ナビゲーションのアクティブ状態を更新
        document.querySelectorAll('.course-nav-link').forEach(link => {
          link.classList.remove('active');
        });
        this.classList.add('active');
      }
    });
  });
  
  // FAQアコーディオン
  const questions = document.querySelectorAll('.faq-question');
  
  questions.forEach(question => {
    question.addEventListener('click', function() {
      const answer = this.nextElementSibling;
      const icon = this.querySelector('.toggle-icon');
      
      if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
        icon.textContent = '−';
      } else {
        answer.style.display = 'none';
        icon.textContent = '+';
      }
    });
  });
  
  // スクロール時のアニメーション
  const revealElements = document.querySelectorAll('.reveal-on-scroll');
  
  function checkReveal() {
    revealElements.forEach(element => {
      const elementTop = element.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;
      
      if (elementTop < windowHeight - 100) {
        element.classList.add('reveal-active');
      }
    });
  }
  
  window.addEventListener('scroll', checkReveal);
  checkReveal(); // 初期チェック
  
  // 現在のハッシュに基づいてナビゲーションのアクティブ状態を設定
  if (window.location.hash) {
    const activeLink = document.querySelector(`.course-nav-link[href="${window.location.hash}"]`);
    if (activeLink) {
      activeLink.classList.add('active');
    }
  }
  
  // ホバーエフェクト
  const links = document.querySelectorAll('a');
  links.forEach(link => {
    link.addEventListener('mouseenter', function() {
      this.style.opacity = '0.8';
    });
    link.addEventListener('mouseleave', function() {
      this.style.opacity = '1';
    });
  });
});
</script>

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

<?php get_footer(); ?>