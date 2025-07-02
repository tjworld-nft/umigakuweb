<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PADIダイビングライセンス講習 - 3つのプランから選択 | 三浦海の学校</title>
    <meta name="description" content="三浦の海でPADIダイビングライセンス取得。スタンダード・ステップアップ・マイ器材付きの3プランから選択。申請料・ログブック・保険料込み。eラーニング自宅学習＋3日間実習で安全にライセンス取得。">
    <meta name="keywords" content="PADI,ダイビングライセンス,OWD,三浦,三浦海岸,ダイビング講習,eラーニング,器材レンタル">
    
    <!-- OGP -->
    <meta property="og:title" content="PADIダイビングライセンス講習 - 3つのプランから選択">
    <meta property="og:description" content="三浦の海でPADIダイビングライセンス取得。eラーニング自宅学習＋3日間実習で安全にライセンス取得。">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://miura-diving.com/owd-license/">
    <meta property="og:image" content="https://miura-diving.com/assets/img/ogp.png">
    <meta property="og:site_name" content="三浦海の学校">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="PADIダイビングライセンス講習 - 3つのプランから選択">
    <meta name="twitter:description" content="三浦の海でPADIダイビングライセンス取得。eラーニング自宅学習＋3日間実習で安全にライセンス取得。">
    <meta name="twitter:image" content="https://miura-diving.com/assets/img/ogp.png">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://miura-diving.com/owd-license/">
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌊</text></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "PADIダイビングライセンス講習",
        "description": "三浦の海でPADIダイビングライセンス取得。eラーニング自宅学習＋3日間実習で安全にライセンス取得。",
        "provider": {
            "@type": "Organization",
            "name": "三浦海の学校",
            "url": "https://miura-diving.com"
        },
        "url": "https://miura-diving.com/owd-license/",
        "image": "https://miura-diving.com/assets/img/ogp.png",
        "offers": [
            {
                "@type": "Offer",
                "name": "スタンダードプラン",
                "price": "70400",
                "priceCurrency": "JPY"
            },
            {
                "@type": "Offer",
                "name": "ステップアッププラン",
                "price": "130300",
                "priceCurrency": "JPY"
            },
            {
                "@type": "Offer",
                "name": "マイ器材付きプラン",
                "price": "120200",
                "priceCurrency": "JPY"
            }
        ],
        "duration": "P3D",
        "courseMode": "blended",
        "educationalLevel": "Beginner",
        "teaches": [
            "ダイビング基礎知識",
            "水中環境の基礎知識",
            "器材の組み立て・使い方",
            "基本的な水中スキル",
            "浮力コントロール",
            "水中ナビゲーション",
            "緊急時対応"
        ]
    }
    </script>
    
    <!-- CSS -->
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 80px 0;
            position: relative;
        }

        section:nth-child(even) {
            background: linear-gradient(135deg, rgba(252, 252, 252, 0.95) 0%, rgba(227, 242, 253, 0.8) 100%);
        }

        /* Special styling for benefits section */
        .benefits {
            background: linear-gradient(135deg, var(--pearl-white) 0%, rgba(227, 242, 253, 0.3) 100%) !important;
            position: relative;
        }

        .benefits::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='2' fill='rgba(78,205,196,0.1)'/%3E%3C/svg%3E") repeat;
            background-size: 50px 50px;
            opacity: 0.3;
            pointer-events: none;
        }

        section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, var(--ocean-teal) 50%, transparent 100%);
            opacity: 0.3;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 50%, var(--ocean-teal) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 100%);
            border-radius: 2px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--deep-sea);
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.8;
            line-height: 1.6;
        }

        /* Color Variables - Ocean Inspired */
        :root {
            --primary-blue: #0c2d48;
            --secondary-blue: #2e86ab;
            --ocean-blue: #a23b72;
            --deep-blue: #f18f01;
            --accent-coral: #ff6b6b;
            --ocean-teal: #4ecdc4;
            --wave-blue: #45b7d1;
            --sand-beige: #f4f3ee;
            --pearl-white: #fcfcfc;
            --deep-sea: #1a365d;
            --light-blue: #e3f2fd;
            --ocean-gradient-start: #667eea;
            --ocean-gradient-end: #764ba2;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --medium-gray: #495057;
            --dark-gray: #212529;
        }

        /* Buttons */
        .cta-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            min-height: 48px;
        }

        .cta-primary {
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.3);
            border: none;
        }

        .cta-primary:hover {
            background: linear-gradient(135deg, var(--wave-blue) 0%, var(--secondary-blue) 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(78, 205, 196, 0.4);
        }

        .cta-secondary {
            background: transparent;
            color: var(--secondary-blue);
            border: 2px solid var(--ocean-teal);
            position: relative;
            overflow: hidden;
        }

        .cta-secondary:hover {
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--secondary-blue) 100%);
            color: white;
            border-color: var(--wave-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(69, 183, 209, 0.3);
        }

        .cta-large {
            padding: 16px 32px;
            font-size: 1.1rem;
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-logo img {
            height: 40px;
        }

        .desktop-nav,
        .desktop-cta {
            display: none;
        }

        .mobile-menu-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .hamburger-line {
            width: 24px;
            height: 2px;
            background: var(--primary-blue);
            transition: all 0.3s ease;
        }

        .mobile-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .mobile-nav.active {
            display: block;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav .nav-item {
            border-bottom: 1px solid #eee;
        }

        .mobile-nav .nav-link {
            display: block;
            padding: 16px 20px;
            color: var(--dark-gray);
            font-weight: 500;
        }

        .mobile-nav .nav-link:hover {
            background: var(--light-gray);
            color: var(--primary-blue);
        }

        .mobile-cta-item {
            padding: 20px;
            text-align: center;
        }

        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            overflow: hidden;
            padding-top: 80px;
            background: linear-gradient(135deg, var(--ocean-gradient-start) 0%, var(--ocean-gradient-end) 100%);
        }

        @keyframes oceanWave {
            0% { transform: translateX(-100px) translateY(0px); }
            50% { transform: translateX(0px) translateY(-20px); }
            100% { transform: translateX(100px) translateY(0px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }

        /* Ocean Wave Effect */
        .ocean-effect {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 100%);
            opacity: 0.1;
            pointer-events: none;
            z-index: 1000;
        }

        .ocean-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1000 100'%3E%3Cpath d='M0,50 Q250,10 500,50 T1000,50 V100 H0 Z' fill='rgba(78,205,196,0.3)'/%3E%3C/svg%3E");
            animation: oceanWave 15s ease-in-out infinite;
        }

        /* Floating Elements */
        .floating {
            animation: float 3s ease-in-out infinite;
        }

        .floating:nth-child(2) {
            animation-delay: 0.5s;
        }

        .floating:nth-child(3) {
            animation-delay: 1s;
        }

        /* Scroll Animation */
        .fade-in-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Exciting Visual Enhancements */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translateY(0); }
            40%, 43% { transform: translateY(-15px); }
            70% { transform: translateY(-7px); }
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0) rotate(0deg); }
            50% { opacity: 1; transform: scale(1) rotate(180deg); }
        }

        /* Exciting Button Effects */
        .excitement-button {
            position: relative;
            overflow: hidden;
        }

        .excitement-button::before {
            content: '✨';
            position: absolute;
            top: 50%;
            left: -30px;
            transform: translateY(-50%);
            font-size: 1.2rem;
            animation: sparkle 2s ease-in-out infinite;
        }

        .excitement-button::after {
            content: '🌊';
            position: absolute;
            top: 50%;
            right: -30px;
            transform: translateY(-50%);
            font-size: 1.2rem;
            animation: sparkle 2s ease-in-out infinite 1s;
        }

        /* Hero enhancement */
        .hero-badge {
            animation: pulse 2s ease-in-out infinite;
        }

        /* Plan cards enhancement */
        .plan-card.featured {
            animation: bounce 2s ease-in-out infinite;
            background: linear-gradient(135deg, var(--pearl-white) 0%, rgba(78, 205, 196, 0.05) 100%);
        }

        /* Section dividers */
        .section-divider {
            height: 150px;
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 100%);
            position: relative;
            overflow: hidden;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z' fill='rgba(255,255,255,0.9)'/%3E%3C/svg%3E") no-repeat;
            background-size: cover;
            animation: oceanWave 10s ease-in-out infinite;
        }

        /* Floating particles */
        .floating-particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: var(--ocean-teal);
            border-radius: 50%;
            opacity: 0.6;
            animation: float 4s ease-in-out infinite;
        }

        .floating-particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .floating-particle:nth-child(2) { left: 20%; animation-delay: 1s; }
        .floating-particle:nth-child(3) { left: 30%; animation-delay: 2s; }
        .floating-particle:nth-child(4) { left: 40%; animation-delay: 3s; }
        .floating-particle:nth-child(5) { left: 50%; animation-delay: 4s; }
        .floating-particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .floating-particle:nth-child(7) { left: 70%; animation-delay: 6s; }
        .floating-particle:nth-child(8) { left: 80%; animation-delay: 7s; }
        .floating-particle:nth-child(9) { left: 90%; animation-delay: 8s; }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -2;
        }

        .hero-bg-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(12, 45, 72, 0.7) 0%, 
                rgba(46, 134, 171, 0.5) 50%, 
                rgba(78, 205, 196, 0.3) 100%);
            z-index: -1;
        }

        .hero-overlay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(78, 205, 196, 0.2) 0%, transparent 50%),
                        radial-gradient(circle at 70% 30%, rgba(69, 183, 209, 0.2) 0%, transparent 50%);
            animation: oceanWave 8s ease-in-out infinite;
        }

        .hero-content {
            text-align: center;
            z-index: 1;
            animation: fadeInUp 1.2s ease-out;
        }

        .hero-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.3);
            animation: fadeInUp 1.2s ease-out 0.3s both;
        }

        .hero-title {
            margin-bottom: 30px;
        }

        .hero-subtitle {
            display: block;
            font-size: 1.2rem;
            font-weight: 400;
            margin-bottom: 8px;
            opacity: 0.9;
            animation: fadeInUp 1.2s ease-out 0.4s both;
        }

        .hero-main-title {
            display: block;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1.2s ease-out 0.6s both;
        }

        .hero-emphasis {
            display: block;
            font-size: 1.3rem;
            font-weight: 500;
            opacity: 0.9;
            animation: fadeInUp 1.2s ease-out 0.8s both;
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 10px 20px;
            border-radius: 25px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            animation: fadeInUp 1.2s ease-out 1s both;
        }

        .feature-item:hover {
            background: rgba(78, 205, 196, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.2);
        }

        .feature-icon {
            font-size: 1.2rem;
        }

        .hero-cta {
            margin-top: 40px;
            animation: fadeInUp 1.2s ease-out 1.2s both;
        }

        /* Plans Section */
        .plans {
            background: var(--light-gray);
            padding: 80px 0;
        }

        .plans-grid {
            display: grid;
            gap: 30px;
            grid-template-columns: 1fr;
            margin-top: 50px;
        }

        .plan-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(78, 205, 196, 0.15);
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(78, 205, 196, 0.1);
        }

        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 100%);
        }

        .plan-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 60px rgba(78, 205, 196, 0.25);
        }

        .plan-card.featured {
            border: 3px solid var(--accent-orange);
            transform: scale(1.05);
        }

        .plan-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--accent-orange);
            color: white;
            padding: 8px 24px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .plan-header {
            background: var(--primary-blue);
            color: white;
            padding: 30px 20px 20px;
            text-align: center;
        }

        .plan-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .plan-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .plan-duration {
            background: rgba(255,255,255,0.1);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
        }

        .plan-content {
            padding: 30px 20px;
        }

        .plan-price {
            text-align: center;
            margin-bottom: 30px;
        }

        .price-breakdown {
            margin-bottom: 20px;
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .price-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--accent-orange);
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }

        .price-label {
            font-size: 0.9rem;
            color: var(--medium-gray);
        }

        .price-amount {
            font-weight: 600;
        }

        .plan-includes {
            margin-bottom: 30px;
        }

        .includes-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 16px;
        }

        .includes-list {
            list-style: none;
        }

        .include-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 0;
        }

        .include-icon {
            color: var(--success-green);
            font-weight: bold;
            font-size: 1.1rem;
        }

        .include-text {
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .plan-highlights {
            background: var(--light-gray);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .highlights-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 12px;
        }

        .highlights-list {
            list-style: none;
        }

        .highlight-item {
            padding: 4px 0;
            font-size: 0.9rem;
            color: var(--dark-gray);
            position: relative;
            padding-left: 20px;
        }

        .highlight-item::before {
            content: "★";
            position: absolute;
            left: 0;
            color: var(--accent-orange);
        }

        .plan-cta {
            text-align: center;
        }

        /* Price Comparison Styles */
        .price-comparison {
            margin-bottom: 30px;
        }

        .price-comparison-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .comparison-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent-orange);
            margin: 0;
        }

        .price-item.individual {
            background: #fff8f0;
            border: 1px solid #ffe4c4;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .price-details {
            margin-top: 10px;
        }

        .price-sub-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .price-sub-item:last-child {
            border-bottom: none;
        }

        .price-sub-item.total {
            border-top: 2px solid var(--accent-orange);
            padding-top: 12px;
            margin-top: 8px;
            font-weight: 600;
        }

        .price-sub-label {
            font-size: 0.9rem;
            color: var(--dark-gray);
        }

        .price-sub-amount {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .original-price {
            text-decoration: line-through;
            color: var(--medium-gray);
        }

        .price-item.discount {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
        }

        .price-final {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .price-amount.discounted {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }

        .savings {
            background: var(--accent-orange);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(255, 140, 66, 0.3);
        }

        .savings-amount {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .savings-amount::before {
            content: "💰";
            font-size: 1.1rem;
        }

        /* Schedule Section */
        .schedule {
            padding: 80px 0;
        }

        .schedule-overview {
            text-align: center;
            margin-bottom: 50px;
        }

        .overview-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent-orange);
        }

        .stat-label {
            font-size: 1rem;
            color: var(--medium-gray);
        }

        .schedule-timeline {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .timeline-day {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .day-header {
            background: var(--primary-blue);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .day-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .day-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .day-duration {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .day-content {
            padding: 30px;
        }

        .day-image {
            margin-bottom: 20px;
        }

        .timeline-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .day-subtitle {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 12px;
        }

        .day-description {
            color: var(--medium-gray);
            margin-bottom: 20px;
        }

        .schedule-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 16px;
        }

        .schedule-list {
            list-style: none;
        }

        .schedule-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .schedule-item:last-child {
            border-bottom: none;
        }

        .item-icon {
            font-size: 1.2rem;
            width: 30px;
            text-align: center;
        }

        .item-content {
            flex: 1;
        }

        .item-title {
            font-weight: 600;
            color: var(--dark-gray);
            display: block;
            margin-bottom: 4px;
        }

        .item-desc {
            font-size: 0.9rem;
            color: var(--medium-gray);
        }

        .day-notes {
            margin-top: 20px;
            padding: 16px;
            background: var(--light-gray);
            border-radius: 8px;
        }

        .note-text {
            font-size: 0.9rem;
            color: var(--dark-gray);
        }

        /* Features Section */
        .features {
            background: var(--light-gray);
            padding: 80px 0;
        }

        .features-grid {
            display: grid;
            gap: 30px;
            grid-template-columns: 1fr;
            margin-top: 50px;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(78, 205, 196, 0.1);
            transition: all 0.4s ease;
            border: 1px solid rgba(78, 205, 196, 0.1);
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(78, 205, 196, 0.2);
            border-color: var(--ocean-teal);
        }

        .feature-image {
            height: 200px;
            overflow: hidden;
        }

        .feature-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .feature-content {
            padding: 24px;
        }

        .feature-icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 12px;
        }

        .feature-description {
            color: var(--medium-gray);
            margin-bottom: 16px;
        }

        .feature-points {
            list-style: none;
        }

        .feature-points li {
            padding: 4px 0;
            padding-left: 20px;
            position: relative;
            color: var(--dark-gray);
        }

        .feature-points li::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--success-green);
            font-weight: bold;
        }

        /* Benefits Section */
        .benefits {
            padding: 80px 0;
        }

        .benefits-grid {
            display: grid;
            gap: 30px;
            grid-template-columns: 1fr;
        }

        .primary-benefit {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 30px;
            border-radius: 12px;
        }

        .benefit-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .benefit-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: var(--accent-orange);
            color: white;
            border-radius: 50%;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }

        .benefit-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .benefit-description {
            color: var(--medium-gray);
            margin-bottom: 16px;
        }

        .benefit-points {
            list-style: none;
            text-align: left;
        }

        .benefit-points li::before {
            content: "✓";
            color: var(--success-green);
            font-weight: bold;
            margin-right: 8px;
        }

        /* Reviews Section */
        .reviews {
            background: var(--light-gray);
            padding: 80px 0;
        }

        .review-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }

        .reviews-grid {
            display: grid;
            gap: 30px;
            grid-template-columns: 1fr;
        }

        .review-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(78, 205, 196, 0.1);
            border: 1px solid rgba(78, 205, 196, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .review-card::before {
            content: '💭';
            position: absolute;
            top: -10px;
            right: 20px;
            font-size: 1.5rem;
            background: white;
            padding: 5px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(78, 205, 196, 0.2);
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(78, 205, 196, 0.2);
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .reviewer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reviewer-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 4px;
        }

        .reviewer-meta {
            font-size: 0.9rem;
            color: var(--medium-gray);
        }

        .review-rating {
            color: var(--accent-orange);
            margin-top: 4px;
        }

        .review-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 12px;
        }

        .review-text {
            color: var(--dark-gray);
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .review-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            background: var(--primary-blue);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        /* FAQ Section */
        .faq {
            padding: 80px 0;
        }

        .faq-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 40px;
        }

        .category-tab {
            padding: 8px 16px;
            border: 2px solid var(--primary-blue);
            background: transparent;
            color: var(--primary-blue);
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-tab.active,
        .category-tab:hover {
            background: var(--primary-blue);
            color: white;
        }

        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .faq-item {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            cursor: pointer;
            background: var(--light-gray);
            transition: background 0.3s ease;
        }

        .faq-question:hover {
            background: #e9ecef;
        }

        .faq-question h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-gray);
            margin: 0;
            flex: 1;
        }

        .faq-toggle {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-blue);
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-toggle {
            transform: rotate(45deg);
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 20px;
            max-height: 200px;
        }

        .faq-answer p {
            color: var(--dark-gray);
            line-height: 1.7;
            margin: 0;
        }

        /* Contact Form */
        .contact-form {
            background: var(--light-gray);
            padding: 80px 0;
        }

        .form-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }

        .required {
            color: var(--danger-red);
            margin-left: 4px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            gap: 20px;
            grid-template-columns: 1fr;
        }

        .form-submit {
            text-align: center;
            margin-top: 30px;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 20px;
        }

        .footer-content {
            display: grid;
            gap: 30px;
            grid-template-columns: 1fr;
            margin-bottom: 30px;
        }

        .footer-section-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--accent-orange);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: #bdc3c7;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .contact-info {
            margin-top: 16px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .footer-bottom {
            border-top: 1px solid #34495e;
            padding-top: 20px;
            text-align: center;
            color: #bdc3c7;
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .container {
                padding: 0 40px;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
            
            .hero-main-title {
                font-size: 3.5rem;
            }
            
            .plans-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            
            .plan-card.featured {
                transform: scale(1.02);
            }
            
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .benefits-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .reviews-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-row {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-content {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .desktop-nav,
            .desktop-cta {
                display: block;
            }
            
            .mobile-menu-toggle {
                display: none;
            }
            
            .desktop-nav .nav-list {
                display: flex;
                gap: 30px;
            }
            
            .desktop-nav .nav-link {
                font-weight: 500;
                color: var(--dark-gray);
                padding: 8px 0;
                position: relative;
            }
            
            .desktop-nav .nav-link::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0;
                height: 2px;
                background: var(--accent-orange);
                transition: width 0.3s ease;
            }
            
            .desktop-nav .nav-link:hover::after {
                width: 100%;
            }
            
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .benefits-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .reviews-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .footer-content {
                grid-template-columns: 2fr 1fr 1fr;
            }
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="header-container">
            <div class="header-logo">
                <a href="/">
                    三浦海の学校
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="header-nav desktop-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="/" class="nav-link">ホーム</a>
                    </li>
                    <li class="nav-item">
                        <a href="#plans-section" class="nav-link">料金プラン</a>
                    </li>
                    <li class="nav-item">
                        <a href="#schedule-section" class="nav-link">講習の流れ</a>
                    </li>
                    <li class="nav-item">
                        <a href="#features-section" class="nav-link">特徴</a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact-form" class="nav-link">お問い合わせ</a>
                    </li>
                </ul>
            </nav>
            
            <!-- CTA Button -->
            <div class="header-cta desktop-cta">
                <a href="#contact-form" class="cta-button cta-primary">
                    お申し込み
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="メニューを開く">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
        
        <!-- Mobile Navigation -->
        <nav class="header-nav mobile-nav" id="mobile-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="/" class="nav-link">ホーム</a>
                </li>
                <li class="nav-item">
                    <a href="#plans-section" class="nav-link">料金プラン</a>
                </li>
                <li class="nav-item">
                    <a href="#schedule-section" class="nav-link">講習の流れ</a>
                </li>
                <li class="nav-item">
                    <a href="#features-section" class="nav-link">特徴</a>
                </li>
                <li class="nav-item">
                    <a href="#contact-form" class="nav-link">お問い合わせ</a>
                </li>
                <li class="nav-item mobile-cta-item">
                    <a href="#contact-form" class="cta-button cta-primary">
                        お申し込み
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero" id="hero-section">
            <div class="hero-background">
                <img src="/assets/img/owd-lp-hero.png" alt="三浦の海でPADIライセンス講習" class="hero-bg-image">
                <div class="hero-overlay"></div>
            </div>
            
            <div class="container">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="badge-text">PADI認定校</span>
                    </div>
                    
                    <h1 class="hero-title">
                        <span class="hero-subtitle">三浦の海で取得</span>
                        <span class="hero-main-title">PADIダイビングライセンス</span>
                        <span class="hero-emphasis">3つのプランから選択</span>
                    </h1>
                    
                    <div class="hero-features">
                        <div class="feature-item">
                            <span class="feature-icon">🏠</span>
                            <span class="feature-text">eラーニング自宅学習</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">📋</span>
                            <span class="feature-text">申請料・ログブック・保険料込み</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">🌊</span>
                            <span class="feature-text">美しい三浦の海</span>
                        </div>
                    </div>
                    
                    <div class="hero-cta">
                        <a href="#plans-section" class="cta-button cta-primary cta-large">
                            <span class="cta-text">プランを見る</span>
                            <span class="cta-arrow">→</span>
                        </a>
                        <div class="cta-support" style="margin-top: 15px; font-size: 0.9rem;">
                            <span class="support-text">お電話でのご相談</span>
                            <a href="tel:046-880-0835" style="color: var(--accent-orange); font-weight: 600; text-decoration: underline;">046-880-0835</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Plans Section -->
        <section class="plans" id="plans-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">3つのプランから選択</h2>
                    <p class="section-subtitle">目的やご希望に合わせてお選びいただけます。すべてのプランに申請料・ログブック・保険料が含まれています。</p>
                </div>
                
                <div class="plans-grid">
                    <!-- スタンダードプラン -->
                    <div class="plan-card">
                        <div class="plan-header">
                            <h3 class="plan-title">スタンダードプラン</h3>
                            <p class="plan-subtitle">PADI OWD講習</p>
                            <div class="plan-duration">最低日数：3日間</div>
                        </div>
                        
                        <div class="plan-content">
                            <div class="plan-price">
                                <div class="price-breakdown">
                                    <div class="price-item">
                                        <span class="price-label">講習料金</span>
                                        <span class="price-amount">¥53,900（税込）</span>
                                    </div>
                                    <div class="price-item">
                                        <span class="price-label">レンタル器材代</span>
                                        <span class="price-amount">¥16,500（税込）</span>
                                    </div>
                                    <div class="price-item">
                                        <span class="price-label">合計費用</span>
                                        <span class="price-amount">¥70,400（税込）</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="plan-includes">
                                <h4 class="includes-title">講習料金に含まれるもの</h4>
                                <ul class="includes-list">
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">PADI OWD講習</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">申請料</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">ログブック</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">保険料</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="plan-highlights">
                                <h4 class="highlights-title">こんな方におすすめ</h4>
                                <ul class="highlights-list">
                                    <li class="highlight-item">基本のライセンスを取得したい方</li>
                                    <li class="highlight-item">まずはダイビングを体験してみたい方</li>
                                    <li class="highlight-item">コストを抑えて始めたい方</li>
                                </ul>
                            </div>
                            
                            <div class="plan-cta">
                                <a href="#contact-form" class="cta-button cta-primary cta-large">
                                    このプランで申し込む
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ステップアッププラン -->
                    <div class="plan-card featured">
                        <div class="plan-badge">
                            <span class="badge-text">最もお得</span>
                        </div>
                        
                        <div class="plan-header">
                            <h3 class="plan-title">ステップアッププラン</h3>
                            <p class="plan-subtitle">OWD＋AOWセット</p>
                            <div class="plan-duration">最低日数：5日間</div>
                        </div>
                        
                        <div class="plan-content">
                            <!-- 価格比較表示 -->
                            <div class="price-comparison">
                                <div class="price-comparison-header">
                                    <h4 class="comparison-title">セット価格でお得！</h4>
                                </div>
                                
                                <div class="price-breakdown">
                                    <div class="price-item individual">
                                        <span class="price-label">個別に受講した場合</span>
                                        <div class="price-details">
                                            <div class="price-sub-item">
                                                <span class="price-sub-label">OWD講習＋器材レンタル</span>
                                                <span class="price-sub-amount">¥70,400</span>
                                            </div>
                                            <div class="price-sub-item">
                                                <span class="price-sub-label">AOW講習＋器材レンタル</span>
                                                <span class="price-sub-amount">¥68,400</span>
                                            </div>
                                            <div class="price-sub-item total">
                                                <span class="price-sub-label">通常価格合計</span>
                                                <span class="price-sub-amount original-price">¥138,800</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="price-item discount">
                                        <span class="price-label">セット価格</span>
                                        <div class="price-final">
                                            <span class="price-amount discounted">¥130,300（税込）</span>
                                            <div class="savings">
                                                <span class="savings-amount">¥8,500お得！</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="plan-includes">
                                <h4 class="includes-title">講習料金に含まれるもの</h4>
                                <ul class="includes-list">
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">PADI OWD講習</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">PADI AOW講習</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">申請料（2ライセンス分）</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">ログブック</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">保険料</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="plan-highlights">
                                <h4 class="highlights-title">こんな方におすすめ</h4>
                                <ul class="highlights-list">
                                    <li class="highlight-item">一気にステップアップしたい方</li>
                                    <li class="highlight-item">ディープダイビングを楽しみたい方</li>
                                    <li class="highlight-item">魚の見分け方を学びたい方</li>
                                    <li class="highlight-item">より幅広いダイビングを体験したい方</li>
                                </ul>
                            </div>
                            
                            <div class="plan-cta">
                                <a href="#contact-form" class="cta-button cta-primary cta-large">
                                    このプランで申し込む
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- マイ器材付きプラン -->
                    <div class="plan-card">
                        <div class="plan-badge">
                            <span class="badge-text">器材付き</span>
                        </div>
                        
                        <div class="plan-header">
                            <h3 class="plan-title">マイ器材付きプラン</h3>
                            <p class="plan-subtitle">OWD講習＋軽器材6点セット</p>
                            <div class="plan-duration">最低日数：3日間</div>
                        </div>
                        
                        <div class="plan-content">
                            <!-- 価格比較表示 -->
                            <div class="price-comparison">
                                <div class="price-comparison-header">
                                    <h4 class="comparison-title">器材購入がお得！</h4>
                                </div>
                                
                                <div class="price-breakdown">
                                    <div class="price-item individual">
                                        <span class="price-label">別々に購入した場合</span>
                                        <div class="price-details">
                                            <div class="price-sub-item">
                                                <span class="price-sub-label">OWD講習＋器材レンタル</span>
                                                <span class="price-sub-amount">¥70,400</span>
                                            </div>
                                            <div class="price-sub-item">
                                                <span class="price-sub-label">軽器材6点セット（通常価格）</span>
                                                <span class="price-sub-amount">¥75,000</span>
                                            </div>
                                            <div class="price-sub-item total">
                                                <span class="price-sub-label">通常価格合計</span>
                                                <span class="price-sub-amount original-price">¥145,400</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="price-item discount">
                                        <span class="price-label">セット価格</span>
                                        <div class="price-final">
                                            <span class="price-amount discounted">¥120,200（税込）</span>
                                            <div class="savings">
                                                <span class="savings-amount">¥25,200お得！</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="plan-includes">
                                <h4 class="includes-title">軽器材6点セットの内容</h4>
                                <ul class="includes-list">
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">マスク（GULL製）</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">スノーケル（GULL製）</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">フィン（GULL製）</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">ブーツ（GULL製）</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">グローブ（GULL製）</span>
                                    </li>
                                    <li class="include-item">
                                        <span class="include-icon">✓</span>
                                        <span class="include-text">メッシュバッグ</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="plan-highlights">
                                <h4 class="highlights-title">こんな方におすすめ</h4>
                                <ul class="highlights-list">
                                    <li class="highlight-item">これから長く続けたい方</li>
                                    <li class="highlight-item">高品質な器材を特別価格で購入したい方</li>
                                    <li class="highlight-item">自分専用の器材で快適にダイビングしたい方</li>
                                </ul>
                            </div>
                            
                            <div class="plan-cta">
                                <a href="#contact-form" class="cta-button cta-primary cta-large">
                                    このプランで申し込む
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Costs -->
                <div class="additional-info" style="margin-top: 60px; text-align: center;">
                    <h3 style="font-size: 1.5rem; color: var(--primary-blue); margin-bottom: 20px;">その他ご案内事項</h3>
                    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
                        <div style="display: grid; gap: 15px; text-align: left;">
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <span style="font-weight: 600;">駐車場利用料</span>
                                <span style="color: var(--accent-orange); font-weight: 600;">1日あたり¥1,100（税込）</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                                <span style="font-weight: 600;">昼食</span>
                                <span style="color: var(--medium-gray);">ご持参をお願いしております</span>
                            </div>
                        </div>
                        <p style="margin-top: 20px; font-size: 0.9rem; color: var(--medium-gray); font-style: italic;">
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Schedule Section -->
        <section class="schedule" id="schedule-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">講習の流れ</h2>
                    <p class="section-subtitle">eラーニング自宅学習＋3日間実習でライセンス取得</p>
                </div>
                
                <div class="schedule-overview">
                    <div class="overview-stats">
                        <div class="stat-item">
                            <span class="stat-number">eラーニング</span>
                            <span class="stat-label">自宅学習</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">3</span>
                            <span class="stat-label">日間実習</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">4</span>
                            <span class="stat-label">海洋ダイブ</span>
                        </div>
                    </div>
                </div>
                
                <!-- Schedule Timeline -->
                <div class="schedule-timeline">
                    <!-- eラーニング -->
                    <div class="timeline-day">
                        <div class="day-header">
                            <div class="day-number">事前学習</div>
                            <div class="day-title">eラーニング学科講習</div>
                            <div class="day-duration">約4-6時間（自宅学習）</div>
                        </div>
                        
                        <div class="day-content">
                            <div class="day-image">
                                <img src="/assets/img/elearning.png" alt="eラーニング自宅学習" class="timeline-img">
                            </div>
                            
                            <div class="day-details">
                                <h3 class="day-subtitle">PADI公式eラーニングで基礎知識を習得</h3>
                                <p class="day-description">
                                    自分のペースで24時間いつでも学習可能。理解できるまで繰り返し学習できます。
                                </p>
                                
                                <div class="day-schedule">
                                    <h4 class="schedule-title">学習内容</h4>
                                    <ul class="schedule-list">
                                        <li class="schedule-item">
                                            <span class="item-icon">📚</span>
                                            <div class="item-content">
                                                <span class="item-title">ダイビング基礎知識</span>
                                                <span class="item-desc">水中環境の基礎知識</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🔧</span>
                                            <div class="item-content">
                                                <span class="item-title">器材の知識</span>
                                                <span class="item-desc">ダイビング器材の使い方・メンテナンス</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">⚠️</span>
                                            <div class="item-content">
                                                <span class="item-title">安全ルール</span>
                                                <span class="item-desc">事故防止・緊急時対応</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🌊</span>
                                            <div class="item-content">
                                                <span class="item-title">海洋環境</span>
                                                <span class="item-desc">海の危険生物・環境保護</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="day-notes">
                                    <p class="note-text">
                                        <strong>📌 ポイント：</strong>24時間いつでも学習可能。理解できるまで繰り返しOK！
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Day 1: プール講習 -->
                    <div class="timeline-day">
                        <div class="day-header">
                            <div class="day-number">Day 1</div>
                            <div class="day-title">プール講習</div>
                            <div class="day-duration">約6時間</div>
                        </div>
                        
                        <div class="day-content">
                            <div class="day-image">
                                <img src="/assets/img/pool.png" alt="プール講習" class="timeline-img">
                            </div>
                            
                            <div class="day-details">
                                <h3 class="day-subtitle">温水プールで基本スキル習得</h3>
                                <p class="day-description">
                                    浅いプールから始めて、安全に基本スキルを習得。海に出る前の大切な準備です。
                                </p>
                                
                                <div class="day-schedule">
                                    <h4 class="schedule-title">習得スキル</h4>
                                    <ul class="schedule-list">
                                        <li class="schedule-item">
                                            <span class="item-icon">🤿</span>
                                            <div class="item-content">
                                                <span class="item-title">器材セッティング</span>
                                                <span class="item-desc">器材の組み立て・使い方など</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">😤</span>
                                            <div class="item-content">
                                                <span class="item-title">マスククリア・レギュクリア</span>
                                                <span class="item-desc">基本的な水中スキルなど</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🎈</span>
                                            <div class="item-content">
                                                <span class="item-title">中性浮力</span>
                                                <span class="item-desc">浮力コントロール・フィンキックなど</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🆘</span>
                                            <div class="item-content">
                                                <span class="item-title">緊急時スキル</span>
                                                <span class="item-desc">浮上・エア切れ対応など</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="day-notes">
                                    <p class="note-text">
                                        <strong>📌 ポイント：</strong>温水プールなので一年中快適！少人数制で個別指導。
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Day 2: 海洋実習1 -->
                    <div class="timeline-day">
                        <div class="day-header">
                            <div class="day-number">Day 2</div>
                            <div class="day-title">海洋実習（2ダイブ）</div>
                            <div class="day-duration">約6時間</div>
                        </div>
                        
                        <div class="day-content">
                            <div class="day-image">
                                <img src="/assets/img/owd1.png" alt="海洋実習2ダイブ" class="timeline-img">
                            </div>
                            
                            <div class="day-details">
                                <h3 class="day-subtitle">三浦の海で実践スキル習得</h3>
                                <p class="day-description">
                                    いよいよ本格的な海洋実習。プールで習得したスキルを海で実践します。
                                </p>
                                
                                <div class="day-schedule">
                                    <h4 class="schedule-title">実習内容</h4>
                                    <ul class="schedule-list">
                                        <li class="schedule-item">
                                            <span class="item-icon">🌊</span>
                                            <div class="item-content">
                                                <span class="item-title">1本目ダイブ</span>
                                                <span class="item-desc">浅場で基本スキルの確認など</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🐠</span>
                                            <div class="item-content">
                                                <span class="item-title">2本目ダイブ</span>
                                                <span class="item-desc">中性浮力・水中観察など</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🧭</span>
                                            <div class="item-content">
                                                <span class="item-title">水中ナビゲーション</span>
                                                <span class="item-desc">コンパス・自然物を使った方向確認など</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">👥</span>
                                            <div class="item-content">
                                                <span class="item-title">バディシステム</span>
                                                <span class="item-desc">水中でのコミュニケーションなど</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="day-notes">
                                    <p class="note-text">
                                        <strong>📌 ポイント：</strong>透明度の高い三浦の海で快適なダイビングを体験！
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Day 3: 海洋実習2 -->
                    <div class="timeline-day">
                        <div class="day-header">
                            <div class="day-number">Day 3</div>
                            <div class="day-title">海洋実習（2ダイブ）・認定手続き</div>
                            <div class="day-duration">約6時間</div>
                        </div>
                        
                        <div class="day-content">
                            <div class="day-image">
                                <img src="/assets/img/owd2.png" alt="海洋実習・認定手続き" class="timeline-img">
                            </div>
                            
                            <div class="day-details">
                                <h3 class="day-subtitle">最終海洋実習＋ライセンス認定</h3>
                                <p class="day-description">
                                    最後の2ダイブでスキルを完成させ、ログ付け・認定手続きを行います。
                                </p>
                                
                                <div class="day-schedule">
                                    <h4 class="schedule-title">実習内容</h4>
                                    <ul class="schedule-list">
                                        <li class="schedule-item">
                                            <span class="item-icon">🎯</span>
                                            <div class="item-content">
                                                <span class="item-title">3本目ダイブ</span>
                                                <span class="item-desc">応用スキル・緊急時対応など</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🎉</span>
                                            <div class="item-content">
                                                <span class="item-title">4本目ダイブ</span>
                                                <span class="item-desc">楽しいダイビング・水中探索など</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">📝</span>
                                            <div class="item-content">
                                                <span class="item-title">ログ付け</span>
                                                <span class="item-desc">ダイビングログの記録・振り返り</span>
                                            </div>
                                        </li>
                                        <li class="schedule-item">
                                            <span class="item-icon">🏆</span>
                                            <div class="item-content">
                                                <span class="item-title">認定手続き</span>
                                                <span class="item-desc">ライセンス発行手続き・おめでとうございます！</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="day-notes">
                                    <p class="note-text">
                                        <strong>📌 ポイント：</strong>認定後は世界中でダイビングを楽しめます！
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features" id="features-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">三浦海の学校の特徴</h2>
                    <p class="section-subtitle">安全で楽しいダイビング体験をお約束します</p>
                </div>
                
                <div class="features-grid">
                    <!-- Feature 1 -->
                    <div class="feature-card">
                        <div class="feature-image">
                            <img src="/assets/img/padi.png" alt="PADI認定インストラクター" class="feature-img">
                        </div>
                        <div class="feature-content">
                            <div class="feature-icon-circle">
                                <span class="icon">🎓</span>
                            </div>
                            <h3 class="feature-title">PADI認定インストラクター</h3>
                            <p class="feature-description">
                                世界最大のダイビング教育機関PADI認定の経験豊富なインストラクターが、安全に丁寧に指導いたします。
                            </p>
                            <ul class="feature-points">
                                <li>国際基準の指導資格</li>
                                <li>豊富な指導経験</li>
                                <li>少人数制で個別サポート</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="feature-card">
                        <div class="feature-image">
                            <img src="/assets/img/miura.png" alt="美しい三浦の海" class="feature-img">
                        </div>
                        <div class="feature-content">
                            <div class="feature-icon-circle">
                                <span class="icon">🌊</span>
                            </div>
                            <h3 class="feature-title">美しい三浦の海</h3>
                            <p class="feature-description">
                                透明度が高く、豊富な海洋生物が生息する三浦の海。年間を通じて快適にダイビングを楽しめます。
                            </p>
                            <ul class="feature-points">
                                <li>透明度の高い海域</li>
                                <li>豊富な海洋生物</li>
                                <li>年間通して潜水可能</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="feature-card">
                        <div class="feature-image">
                            <img src="/assets/img/pool2.png" alt="ダイビング専用プールがあるから安心" class="feature-img">
                        </div>
                        <div class="feature-content">
                            <div class="feature-icon-circle">
                                <span class="icon">💻</span>
                            </div>
                            <h3 class="feature-title">ダイビング専用プール完備</h3>
                            <p class="feature-description">
                                三浦海の学校では、ダイビング専用のプールを完備しています。海洋実習前に安全な環境でしっかりとスキルを習得できます。
                            </p>
                            <ul class="feature-points">
                                <li>専用プールで安全な学習環境</li>
                                <li>海洋実習前の十分なスキル習得</li>
                                <li>初心者でも安心して参加可能</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="benefits" id="benefits-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">PADIライセンス取得のメリット</h2>
                    <p class="section-subtitle">世界中の海で楽しめる可能性が広がります</p>
                </div>
                
                <div class="benefits-grid">
                    <!-- Main Benefits -->
                    <div class="benefit-card primary-benefit">
                        <div class="benefit-content">
                            <div class="benefit-icon">
                                <span class="icon">🌏</span>
                            </div>
                            <h3 class="benefit-title">世界中で通用するライセンス</h3>
                            <p class="benefit-description">
                                PADI認定のライセンスは世界180カ国以上で認められ、どこでもダイビングを楽しめます。
                            </p>
                            <ul class="benefit-points">
                                <li>沖縄・石垣島の美しいサンゴ礁</li>
                                <li>海外リゾートでのダイビング</li>
                                <li>世界各地のダイビングスポット</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Sub Benefits -->
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <span class="icon">🐠</span>
                        </div>
                        <h3 class="benefit-title">海洋生物との出会い</h3>
                        <p class="benefit-description">
                            水中では陸上では決して見ることのできない美しい海洋生物と出会えます。
                        </p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <span class="icon">🧘</span>
                        </div>
                        <h3 class="benefit-title">ストレス解消・リラックス</h3>
                        <p class="benefit-description">
                            水中での深い呼吸と無重力感覚で、日常のストレスから解放されます。
                        </p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <span class="icon">🤝</span>
                        </div>
                        <h3 class="benefit-title">新しいコミュニティ</h3>
                        <p class="benefit-description">
                            ダイビングを通じて同じ趣味を持つ仲間との出会いが待っています。
                        </p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <span class="icon">📸</span>
                        </div>
                        <h3 class="benefit-title">水中写真・動画撮影</h3>
                        <p class="benefit-description">
                            水中でしか撮影できない幻想的な写真や動画を撮影できます。
                        </p>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <span class="icon">💪</span>
                        </div>
                        <h3 class="benefit-title">体力向上・健康促進</h3>
                        <p class="benefit-description">
                            全身を使った運動で体力向上。心肺機能の向上にも効果的です。
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="reviews" id="reviews-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">受講生の声</h2>
                    <p class="section-subtitle">実際にPADIライセンス講習を受講された方の体験談</p>
                </div>
                
                <!-- Review Stats -->
                <div class="review-stats">
                    <div class="stat-item">
                        <span class="stat-number">1,200+</span>
                        <span class="stat-label">受講実績</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">4.9</span>
                        <span class="stat-label">満足度評価</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">合格率</span>
                    </div>
                </div>
                
                <!-- Reviews Grid -->
                <div class="reviews-grid">
                    <!-- Review 1 -->
                    <div class="review-card">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                            </div>
                            <div class="reviewer-details">
                                <h4 class="reviewer-name">田中さん（20代女性）</h4>
                                <div class="reviewer-meta">
                                    <span class="review-date">2024年5月受講</span>
                                    <div class="review-rating">
                                        <span class="star">★★★★★</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="review-content">
                            <h5 class="review-title">「eラーニングが便利でした」</h5>
                            <p class="review-text">
                                完全初心者でしたが、eラーニングで自分のペースで学習できたのが良かったです。
                                仕事が忙しい中でも空き時間に学習できて、実習前にしっかり知識が身についていました。
                                三浦の海は透明度が高くて感動しました！
                            </p>
                        </div>
                        
                        <div class="review-tags">
                            <span class="tag">初心者歓迎</span>
                            <span class="tag">eラーニング</span>
                            <span class="tag">三浦の海</span>
                        </div>
                    </div>
                    
                    <!-- Review 2 -->
                    <div class="review-card">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                            </div>
                            <div class="reviewer-details">
                                <h4 class="reviewer-name">佐藤さん（30代男性）</h4>
                                <div class="reviewer-meta">
                                    <span class="review-date">2024年4月受講</span>
                                    <div class="review-rating">
                                        <span class="star">★★★★★</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="review-content">
                            <h5 class="review-title">「ステップアッププランがお得」</h5>
                            <p class="review-text">
                                せっかくなのでOWD＋AOWのステップアッププランにしました。
                                5日間でかなりのスキルが身につき、ディープダイビングも体験できて大満足。
                                料金的にもお得で、一気にレベルアップできました。
                            </p>
                        </div>
                        
                        <div class="review-tags">
                            <span class="tag">ステップアップ</span>
                            <span class="tag">AOW</span>
                            <span class="tag">お得</span>
                        </div>
                    </div>
                    
                    <!-- Review 3 -->
                    <div class="review-card">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                            </div>
                            <div class="reviewer-details">
                                <h4 class="reviewer-name">山田さん（40代女性）</h4>
                                <div class="reviewer-meta">
                                    <span class="review-date">2024年3月受講</span>
                                    <div class="review-rating">
                                        <span class="star">★★★★★</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="review-content">
                            <h5 class="review-title">「マイ器材でより快適に」</h5>
                            <p class="review-text">
                                マイ器材付きプランを選んで正解でした。GULL製の器材は品質が良く、
                                自分専用の器材で講習を受けられるのは衛生的で安心。
                                講習後も自分の器材でダイビングを続けています。
                            </p>
                        </div>
                        
                        <div class="review-tags">
                            <span class="tag">マイ器材</span>
                            <span class="tag">GULL</span>
                            <span class="tag">継続</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq" id="faq-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">よくある質問</h2>
                    <p class="section-subtitle">PADIライセンス講習についてのご質問にお答えします</p>
                </div>
                
                <!-- FAQ Categories -->
                <div class="faq-categories">
                    <button class="category-tab active" data-category="all">すべて</button>
                    <button class="category-tab" data-category="course">講習内容</button>
                    <button class="category-tab" data-category="schedule">スケジュール</button>
                    <button class="category-tab" data-category="equipment">器材・持ち物</button>
                    <button class="category-tab" data-category="cost">料金</button>
                    <button class="category-tab" data-category="safety">安全・健康</button>
                </div>
                
                <!-- FAQ Items -->
                <div class="faq-list">
                    <!-- Course Related FAQs -->
                    <div class="faq-item" data-category="course">
                        <div class="faq-question">
                            <h3>完全初心者でも大丈夫ですか？</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>はい、全く問題ありません。PADIライセンス講習は初心者の方を対象としたコースです。eラーニングで基礎知識をしっかり学んでから実習に進むので、安心して受講いただけます。これまでの受講生の約8割が完全初心者の方でした。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" data-category="course">
                        <div class="faq-question">
                            <h3>eラーニングはどのくらい時間がかかりますか？</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>個人差はありますが、一般的に2〜4時間程度です。24時間いつでもアクセス可能で、途中で中断・再開できるので、自分のペースで学習を進められます。理解できるまで何度でも繰り返し学習できます。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" data-category="safety">
                        <div class="faq-question">
                            <h3>泳ぎが苦手でも大丈夫ですか？</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>基本的な泳力（25m程度）は必要ですが、競泳のように速く泳ぐ必要はありません。ダイビングは泳ぐというより、浮力を使って水中を移動します。不安な方は事前にプールで練習することをお勧めします。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" data-category="cost">
                        <div class="faq-question">
                            <h3>追加料金はかかりませんか？</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>各プランの料金に含まれない費用は、駐車場代（¥1,100/日）のみです。昼食はご持参をお願いしており、隠れた追加料金は一切ございません。明確な料金体系で安心してお申し込みいただけます。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" data-category="schedule">
                        <div class="faq-question">
                            <h3>3日間連続で受講する必要がありますか？</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>いいえ、連続でなくても大丈夫です。eラーニング→プール講習→海洋実習の順番は守っていただきますが、それぞれ別の日に受講可能です。お仕事やご都合に合わせてスケジュール調整いたします。</p>
                        </div>
                    </div>
                    
                    <div class="faq-item" data-category="equipment">
                        <div class="faq-question">
                            <h3>器材は購入する必要がありますか？</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>講習中は全てレンタル器材をご利用いただけるので、購入の必要はありません。ただし、マイ器材付きプランでは高品質なGULL製軽器材6点セットを特別価格でご提供しており、これから長く続けたい方におすすめです。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form Section -->
        <section class="contact-form" id="contact-form">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">お申し込み・お問い合わせ</h2>
                    <p class="section-subtitle">ご希望のプランやご質問など、お気軽にお問い合わせください</p>
                </div>
                
                <div class="form-container">
                    <form method="POST" action="/owd-form-handler.php">
                        <input type="hidden" name="course_type" value="PADIライセンス講習">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    お名前<span class="required">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    メールアドレス<span class="required">*</span>
                                </label>
                                <input type="email" id="email" name="email" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    電話番号<span class="required">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label for="plan" class="form-label">
                                    ご希望プラン<span class="required">*</span>
                                </label>
                                <select id="plan" name="plan" class="form-select" required>
                                    <option value="">選択してください</option>
                                    <option value="standard">スタンダードプラン（¥70,400）</option>
                                    <option value="stepup">ステップアッププラン（¥130,300）</option>
                                    <option value="equipment">マイ器材付きプラン（¥120,200）</option>
                                    <option value="consultation">まずは相談したい</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="participants" class="form-label">
                                参加人数
                            </label>
                            <select id="participants" name="participants" class="form-select">
                                <option value="">選択してください</option>
                                <option value="1">1名</option>
                                <option value="2">2名</option>
                                <option value="3">3名</option>
                                <option value="4">4名</option>
                                <option value="5+">5名以上</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="preferred_date" class="form-label">
                                ご希望日程
                            </label>
                            <input type="text" id="preferred_date" name="preferred_date" class="form-input" 
                                   placeholder="例：3月第2週頃、土日希望など">
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">
                                ご質問・ご要望
                            </label>
                            <textarea id="message" name="message" class="form-textarea" rows="5"
                                      placeholder="ご質問、ご要望、ダイビング経験など何でもお気軽にお書きください"></textarea>
                        </div>
                        
                        <div class="form-submit">
                            <button type="submit" class="cta-button cta-primary cta-large">
                                送信する
                            </button>
                            <div style="margin-top: 20px; text-align: center;">
                                <p style="font-size: 0.9rem; color: var(--medium-gray); margin-bottom: 10px;">
                                    または、お気軽にLINEでお問い合わせください
                                </p>
                                <a href="https://lin.ee/GbDNsQ0" target="_blank" class="cta-button" 
                                   style="background: #00B900; color: white; display: inline-flex; align-items: center; gap: 8px;">
                                    <span>💬</span>
                                    <span>LINE公式アカウント</span>
                                </a>
                            </div>
                            <p style="margin-top: 15px; font-size: 0.9rem; color: var(--medium-gray);">
                                送信後、24時間以内にスタッフよりご連絡いたします。
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Company Info -->
                <div class="footer-section">
                    <h3 class="footer-section-title">三浦海の学校</h3>
                    <p style="margin-bottom: 20px; color: #bdc3c7; line-height: 1.6;">
                        美しい三浦の海で安全で楽しいダイビング体験を提供するPADI認定ダイビングスクールです。
                    </p>
                    <div class="contact-info">
                        <div class="contact-item">
                            <span style="margin-right: 8px;">📍</span>
                            神奈川県三浦市三崎町諸磯1621
                        </div>
                        <div class="contact-item">
                            <span style="margin-right: 8px;">📞</span>
                            <a href="tel:046-880-0835" style="color: #bdc3c7;">046-880-0835</a>
                        </div>
                        <div class="contact-item">
                            <span style="margin-right: 8px;">✉️</span>
                            <a href="mailto:info@miura-diving.com" style="color: #bdc3c7;">info@miura-diving.com</a>
                        </div>
                        <div class="contact-item">
                            <span style="margin-right: 8px;">💬</span>
                            <a href="https://lin.ee/GbDNsQ0" target="_blank" style="color: #bdc3c7;">LINE公式アカウント</a>
                        </div>
                    </div>
                </div>
                
                <!-- Services -->
                <div class="footer-section">
                    <h3 class="footer-section-title">サービス</h3>
                    <ul class="footer-links">
                        <li>ライセンス講習</li>
                        <li>ファンダイビング</li>
                        <li>体験ダイビング</li>
                        <li>器材販売・レンタル</li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div class="footer-section">
                    <h3 class="footer-section-title">サポート</h3>
                    <ul class="footer-links">
                        <li><a href="#contact-form">お問い合わせ</a></li>
                        <li><a href="#faq-section">よくある質問</a></li>
                        <li><a href="https://miura-diving.com/privacy">プライバシーポリシー</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© 2024 三浦海の学校. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const mobileNav = document.getElementById('mobile-nav');
            
            if (menuToggle && mobileNav) {
                menuToggle.addEventListener('click', function() {
                    mobileNav.classList.toggle('active');
                    
                    const lines = menuToggle.querySelectorAll('.hamburger-line');
                    if (mobileNav.classList.contains('active')) {
                        lines[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                        lines[1].style.opacity = '0';
                        lines[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
                    } else {
                        lines[0].style.transform = 'none';
                        lines[1].style.opacity = '1';
                        lines[2].style.transform = 'none';
                    }
                });
                
                // Close menu when clicking on links
                const navLinks = mobileNav.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileNav.classList.remove('active');
                        const lines = menuToggle.querySelectorAll('.hamburger-line');
                        lines[0].style.transform = 'none';
                        lines[1].style.opacity = '1';
                        lines[2].style.transform = 'none';
                    });
                });
            }
            
            // FAQ Accordion
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                if (question) {
                    question.addEventListener('click', function() {
                        const isActive = item.classList.contains('active');
                        faqItems.forEach(otherItem => {
                            if (otherItem !== item) {
                                otherItem.classList.remove('active');
                            }
                        });
                        if (isActive) {
                            item.classList.remove('active');
                        } else {
                            item.classList.add('active');
                        }
                    });
                }
            });
            
            // FAQ Filter
            const categoryTabs = document.querySelectorAll('.category-tab');
            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    
                    categoryTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    faqItems.forEach(item => {
                        const itemCategory = item.getAttribute('data-category');
                        if (category === 'all' || itemCategory === category) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.style.opacity = '1';
                            }, 100);
                        } else {
                            item.style.display = 'none';
                        }
                        item.classList.remove('active');
                    });
                });
            });
            
            // Smooth scroll
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        const headerHeight = document.querySelector('.header').offsetHeight;
                        const targetPosition = targetElement.offsetTop - headerHeight - 20;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Ocean-inspired scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            // Observe elements for scroll animations
            document.querySelectorAll('.plan-card, .feature-card, .review-card, .faq-item').forEach(el => {
                el.classList.add('fade-in-on-scroll');
                observer.observe(el);
            });

            // Add floating animation to feature items
            document.querySelectorAll('.feature-item').forEach((el, index) => {
                el.classList.add('floating');
                el.style.animationDelay = `${index * 0.2}s`;
            });

            // Add excitement effects to buttons
            document.querySelectorAll('.cta-primary, .cta-secondary').forEach(button => {
                button.classList.add('excitement-button');
            });

            // Add bouncing animation to featured plan
            const featuredPlan = document.querySelector('.plan-card:nth-child(2)');
            if (featuredPlan) {
                featuredPlan.classList.add('featured');
            }

            // Create floating particles
            function createFloatingParticles() {
                const hero = document.querySelector('.hero');
                if (!hero) return;

                for (let i = 0; i < 9; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'floating-particle';
                    particle.style.left = `${10 + i * 10}%`;
                    particle.style.top = `${Math.random() * 100}%`;
                    particle.style.animationDelay = `${i}s`;
                    hero.appendChild(particle);
                }
            }

            createFloatingParticles();

            // Add section dividers between major sections
            const sections = document.querySelectorAll('section');
            sections.forEach((section, index) => {
                if (index > 0 && index < sections.length - 1) {
                    const divider = document.createElement('div');
                    divider.className = 'section-divider';
                    section.parentNode.insertBefore(divider, section);
                }
            });

            // Add click animation to cards
            document.querySelectorAll('.plan-card, .feature-card').forEach(card => {
                card.addEventListener('click', () => {
                    card.style.animation = 'pulse 0.5s ease-in-out';
                    setTimeout(() => {
                        card.style.animation = '';
                    }, 500);
                });
            });

            // Add progress indicator
            function updateScrollProgress() {
                const scrollTop = window.pageYOffset;
                const docHeight = document.body.scrollHeight - window.innerHeight;
                const scrollPercent = (scrollTop / docHeight) * 100;
                
                let progressBar = document.querySelector('.scroll-progress');
                if (!progressBar) {
                    progressBar = document.createElement('div');
                    progressBar.className = 'scroll-progress';
                    progressBar.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: ${scrollPercent}%;
                        height: 4px;
                        background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 100%);
                        z-index: 9999;
                        transition: width 0.3s ease;
                        box-shadow: 0 2px 10px rgba(78, 205, 196, 0.3);
                    `;
                    document.body.appendChild(progressBar);
                } else {
                    progressBar.style.width = `${scrollPercent}%`;
                }
            }

            window.addEventListener('scroll', updateScrollProgress);

            // Create ocean wave effect
            const oceanEffect = document.createElement('div');
            oceanEffect.className = 'ocean-effect';
            document.body.appendChild(oceanEffect);

            // Parallax effect for hero section
            let ticking = false;
            function updateParallax() {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero');
                if (hero) {
                    const rate = scrolled * -0.5;
                    hero.style.transform = `translate3d(0, ${rate}px, 0)`;
                }
                ticking = false;
            }

            function requestParallaxUpdate() {
                if (!ticking) {
                    requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            }

            window.addEventListener('scroll', requestParallaxUpdate);

            // Add shimmer effect to buttons
            document.querySelectorAll('.cta-primary').forEach(button => {
                button.addEventListener('mouseenter', () => {
                    button.style.background = 'linear-gradient(135deg, var(--ocean-teal) 0%, var(--wave-blue) 50%, var(--ocean-teal) 100%)';
                    button.style.backgroundSize = '200% 100%';
                    button.style.animation = 'shimmer 1.5s ease-in-out';
                });
                
                button.addEventListener('mouseleave', () => {
                    button.style.animation = '';
                });
            });

            // Form validation and submission handling
            const form = document.querySelector('form[action="/owd-form-handler.php"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Basic validation
                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const phone = document.getElementById('phone').value.trim();
                    const plan = document.getElementById('plan').value;

                    if (!name || !email || !phone || !plan) {
                        e.preventDefault();
                        alert('必須項目をすべて入力してください。');
                        return;
                    }

                    // Email validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        alert('正しいメールアドレスを入力してください。');
                        return;
                    }

                    // Show loading state
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalText = submitButton.textContent;
                    submitButton.textContent = '送信中...';
                    submitButton.disabled = true;
                    submitButton.style.opacity = '0.7';

                    // If validation passes, form will submit normally
                    // Restore button state after 5 seconds (fallback)
                    setTimeout(() => {
                        submitButton.textContent = originalText;
                        submitButton.disabled = false;
                        submitButton.style.opacity = '1';
                    }, 5000);
                });
            }
        });
    </script>
</body>
</html>