<?php
// ★License 5 fixed - 5コース固定ライセンスページテンプレート
/**
 * Template Name: Diving License 5 Courses
 * 体験ダイビング・OWD・AOW・EFR・レスキューの5コース固定表示
 */

// YAMLデータ（5コース固定）
$yaml = <<<YAML
courses:
  - slug: try
    name: "体験ダイビング"
    price: 16500
    duration: "約3時間（1ダイブ）"
    desc: "インストラクターが常に横について安全に海中世界を案内。"
    bullet_left:
      - "所要時間： 約3時間"
      - "料金： ¥16,500（税込）"
      - "含まれるもの：器材レンタル一式、保険料、ガイド料"
      - "別途必要：水着、タオル、昼食"
    bullet_right:
      - "学習内容"
      - "‣ 基本安全ブリーフィング"
      - "‣ 水中での呼吸と浮力"
      - "対象者"
      - "‣ 10歳以上"
  - slug: owd
    name: "オープンウォーターダイバー（OWD）"
    price: 53900
    duration: "3日間"
    desc: "世界中で通用する入門ライセンス。"
    bullet_left:
      - "必要日数： 3日間"
      - "ダイブ数： プール＋海洋実習4ダイブ"
      - "料金： ¥53,900（税込）"
      - "含まれるもの：eラーニング、ログブック、プール・海洋実習費、申請料、保険料"
      - "別途必要：レンタル器材代、水着、タオル、昼食代、交通費"
    bullet_right:
      - "学習内容"
      - "‣ ダイビング器材の知識"
      - "‣ 耳抜き・呼吸・浮力"
      - "‣ 基本的な水中コミュニケーション"
      - "対象者"
      - "‣ 10歳以上"
      - "‣ 健康状態に問題がない方"
  - slug: aow
    name: "アドバンスド・オープンウォーター（AOW）"
    price: 53900
    duration: "2日間"
    desc: "5つのアドベンチャーダイブでスキルを拡張。"
    bullet_left:
      - "必要日数： 2日間"
      - "ダイブ数： 5ダイブ"
      - "料金： ¥53,900（税込）"
      - "含まれるもの：教材一式、実習費、申請料、保険料"
    bullet_right:
      - "学習内容"
      - "‣ ディープ / ナビゲーション"
      - "‣ 魚類識別・ドリフト etc."
      - "対象者"
      - "‣ OWDライセンス保持者"
      - "‣ 12歳以上"
  - slug: efr
    name: "エマージェンシー・ファーストレスポンス（EFR）"
    price: 22000
    duration: "1日"
    desc: "CPR や AED の一次・二次救命処置を学ぶ。"
    bullet_left:
      - "必要日数： 1日"
      - "料金： ¥22,000（税込）"
      - "含まれるもの：教材一式、実習費、申請料"
      - "別途必要：筆記用具、昼食代"
    bullet_right:
      - "学習内容"
      - "‣ 一次救命処置（CPR）"
      - "‣ AEDの使用方法"
      - "‣ 出血 / ショック対応"
      - "対象者"
      - "‣ 誰でも参加可能"
  - slug: red
    name: "レスキュー・ダイバー（RED）"
    price: 64900
    duration: "2日間"
    desc: "自己救助・他者救助スキルを学ぶ安全コース。"
    bullet_left:
      - "必要日数： 2日間"
      - "前提条件： EFR修了（2年以内）"
      - "料金： ¥64,900（税込）"
      - "含まれるもの：教材一式、実習費、申請料、保険料"
    bullet_right:
      - "学習内容"
      - "‣ 行方不明者の捜索"
      - "‣ 水面レスキュー技術"
      - "‣ 無反応ダイバー搬送"
      - "対象者"
      - "‣ 12歳以上"
      - "‣ AOWライセンス保持"
      - "‣ 有効なEFR資格"
  - slug: ppb
    name: "PPB（中性浮力）"
    price: 27500
    duration: "1日/2ダイブ"
    desc: "中性浮力の取り方とコントロール技術を習得"
  - slug: dry
    name: "ドライスーツダイバー"
    price: 27500
    duration: "1日/2ダイブ"
    desc: "ドライスーツ使用法と浮力調整技術"
  - slug: boat
    name: "ボートダイバー"
    price: 36300
    duration: "1日/2ダイブ"
    desc: "ボートからのエントリー／エキジット"
  - slug: fishid
    name: "魚の見分け方"
    price: 27500
    duration: "1日/2ダイブ"
    desc: "魚の見分け方の知識と観察方法"
  - slug: digi
    name: "デジタルフォトグラファー"
    price: 27500
    duration: "2日/2ダイブ"
    desc: "水中写真の撮影テクニック"
  - slug: nav
    name: "ナビゲーション"
    price: 36300
    duration: "1日/3ダイブ"
    desc: "コンパスと自然目印を使った水中ナビゲーション"
  - slug: deep
    name: "ディープダイバー"
    price: 49800
    duration: "2日/4ダイブ"
    desc: "水深19〜30 mの安全なダイビング技術"
  - slug: naturalist
    name: "ナチュラリスト"
    price: 27500
    duration: "1日/2ダイブ"
    desc: "海洋生態系と環境保全について学ぶ"
  - slug: sr
    name: "サーチ＆リカバリー"
    price: 44500
    duration: "2日/4ダイブ"
    desc: "水中での物体捜索と回収技術"
  - slug: night
    name: "ナイトダイバー"
    price: 44500
    duration: "1日/3ダイブ"
    desc: "夜間ダイビングの技術と安全管理"
  - slug: msd
    name: "マスター・スクーバ・ダイバー (MSD)"
    price: 0
    duration: "-"
    desc: "AOW + レスキュー + 5 つの SP + ログ 50 本以上で取得可能"
  - slug: dm
    name: "ダイブマスター"
    price: 132000
    duration: "10日間〜"
    desc: "リーダーシップスキルを身につけ、プロへの第一歩。"
    bullet_left:
      - "期間： 10日間〜"
      - "前提条件： 18歳以上、レスキュー、EFR、ログ40本以上"
      - "料金： ¥132,000（税込）"
      - "含まれるもの： 講習費"
    bullet_right:
      - "学習内容"
      - "‣ ダイビング知識とスキルの向上"
      - "‣ リーダーシップ開発"
      - "‣ 実地でのダイビング管理"
  - slug: ai
    name: "アシスタントインストラクター (AI)"
    price: 132000
    duration: "4日間〜"
    desc: "インストラクターになるための準備段階。"
    bullet_left:
      - "期間： 4日間〜"
      - "前提条件： 18歳以上、ダイブマスター、ログ60本以上"
      - "料金： ¥132,000（税込）"
      - "含まれるもの： 講習費"
    bullet_right:
      - "学習内容"
      - "‣ インストラクター開発の基礎"
      - "‣ プール／限定水域での指導"
  - slug: idc
    name: "インストラクター開発コース (IDC)"
    price: 220000
    duration: "8〜10日間"
    desc: "OWインストラクターになるための包括的トレーニング。"
    bullet_left:
      - "期間： 8〜10日間"
      - "前提条件： 18歳以上、ダイブマスター、ログ60本以上"
      - "料金： ¥220,000（税込）"
      - "含まれるもの： 講習費"
    bullet_right:
      - "学習内容"
      - "‣ PADIシステムと規格"
      - "‣ 教授法・リスク管理"
  - slug: staff
    name: "IDCスタッフインストラクター"
    price: 98000
    duration: "最短4日間〜"
    desc: "IDC で候補生を評価し、育成補助を行う資格。"
    bullet_left:
      - "期間： 最短4日間〜"
      - "前提条件： インストラクター、ログ150本以上（推奨）"
      - "料金： ¥98,000（税込）"
      - "含まれるもの： 講習費"
    bullet_right:
      - "学習内容"
      - "‣ IDCプログラム運営補助"
      - "‣ インストラクター育成技術"
  - slug: spi
    name: "スペシャルティインストラクター (SPI)"
    price: 0
    duration: "各SP 1〜2日間"
    desc: "各スペシャルティを教えられるインストラクター資格。"
    bullet_left:
      - "期間： 各SP 1〜2日間"
      - "前提条件： PADIインストラクター + 該当SPログ実績"
      - "料金： お問い合わせください"
      - "含まれるもの： 講習費"
    bullet_right:
      - "主なスペシャルティ"
      - "‣ ナイト／ディープ／ナビ"
      - "‣ ドライスーツ／水中写真"
      - "‣ 魚類識別／ボート／ドリフト"
YAML;

// YAMLパース（フォールバック付き）
if (function_exists('yaml_parse')) {
    $courses = yaml_parse($yaml)['courses'];
} else {
    // YAMLが利用できない場合のフォールバック
    $courses = [
        [
            'slug' => 'try',
            'name' => '体験ダイビング',
            'price' => 16500,
            'duration' => '約3時間（1ダイブ）',
            'desc' => 'インストラクターが常に横について安全に海中世界を案内。',
            'bullet_left' => [
                '所要時間： 約3時間',
                '料金： ¥16,500（税込）',
                '含まれるもの：器材レンタル一式、保険料、ガイド料',
                '別途必要：水着、タオル、昼食'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ 基本安全ブリーフィング',
                '‣ 水中での呼吸と浮力',
                '対象者',
                '‣ 10歳以上'
            ]
        ],
        [
            'slug' => 'owd',
            'name' => 'オープンウォーターダイバー（OWD）',
            'price' => 53900,
            'duration' => '3日間',
            'desc' => '世界中で通用する入門ライセンス。',
            'bullet_left' => [
                '必要日数： 3日間',
                'ダイブ数： プール＋海洋実習4ダイブ',
                '料金： ¥53,900（税込）',
                '含まれるもの：eラーニング、ログブック、プール・海洋実習費、申請料、保険料',
                '別途必要：レンタル器材代、水着、タオル、昼食代、交通費'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ ダイビング器材の知識',
                '‣ 耳抜き・呼吸・浮力',
                '‣ 基本的な水中コミュニケーション',
                '対象者',
                '‣ 10歳以上',
                '‣ 健康状態に問題がない方'
            ]
        ],
        [
            'slug' => 'aow',
            'name' => 'アドバンスド・オープンウォーター（AOW）',
            'price' => 53900,
            'duration' => '2日間',
            'desc' => '5つのアドベンチャーダイブでスキルを拡張。',
            'bullet_left' => [
                '必要日数： 2日間',
                'ダイブ数： 5ダイブ',
                '料金： ¥53,900（税込）',
                '含まれるもの：教材一式、実習費、申請料、保険料'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ ディープ / ナビゲーション',
                '‣ 魚類識別・ドリフト etc.',
                '対象者',
                '‣ OWDライセンス保持者',
                '‣ 12歳以上'
            ]
        ],
        [
            'slug' => 'efr',
            'name' => 'エマージェンシー・ファーストレスポンス（EFR）',
            'price' => 22000,
            'duration' => '1日',
            'desc' => 'CPR や AED の一次・二次救命処置を学ぶ。',
            'bullet_left' => [
                '必要日数： 1日',
                '料金： ¥22,000（税込）',
                '含まれるもの：教材一式、実習費、申請料',
                '別途必要：筆記用具、昼食代'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ 一次救命処置（CPR）',
                '‣ AEDの使用方法',
                '‣ 出血 / ショック対応',
                '対象者',
                '‣ 誰でも参加可能'
            ]
        ],
        [
            'slug' => 'red',
            'name' => 'レスキュー・ダイバー（RED）',
            'price' => 64900,
            'duration' => '2日間',
            'desc' => '自己救助・他者救助スキルを学ぶ安全コース。',
            'bullet_left' => [
                '必要日数： 2日間',
                '前提条件： EFR修了（2年以内）',
                '料金： ¥64,900（税込）',
                '含まれるもの：教材一式、実習費、申請料、保険料'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ 行方不明者の捜索',
                '‣ 水面レスキュー技術',
                '‣ 無反応ダイバー搬送',
                '対象者',
                '‣ 12歳以上',
                '‣ AOWライセンス保持',
                '‣ 有効なEFR資格'
            ]
        ],
        [
            'slug' => 'ppb',
            'name' => 'PPB（中性浮力）',
            'price' => 27500,
            'duration' => '1日/2ダイブ',
            'desc' => '中性浮力の取り方とコントロール技術を習得'
        ],
        [
            'slug' => 'dry',
            'name' => 'ドライスーツダイバー',
            'price' => 27500,
            'duration' => '1日/2ダイブ',
            'desc' => 'ドライスーツ使用法と浮力調整技術'
        ],
        [
            'slug' => 'boat',
            'name' => 'ボートダイバー',
            'price' => 36300,
            'duration' => '1日/2ダイブ',
            'desc' => 'ボートからのエントリー／エキジット'
        ],
        [
            'slug' => 'fishid',
            'name' => '魚の見分け方',
            'price' => 27500,
            'duration' => '1日/2ダイブ',
            'desc' => '魚の見分け方の知識と観察方法'
        ],
        [
            'slug' => 'digi',
            'name' => 'デジタルフォトグラファー',
            'price' => 27500,
            'duration' => '2日/2ダイブ',
            'desc' => '水中写真の撮影テクニック'
        ],
        [
            'slug' => 'nav',
            'name' => 'ナビゲーション',
            'price' => 36300,
            'duration' => '1日/3ダイブ',
            'desc' => 'コンパスと自然目印を使った水中ナビゲーション'
        ],
        [
            'slug' => 'deep',
            'name' => 'ディープダイバー',
            'price' => 49800,
            'duration' => '2日/4ダイブ',
            'desc' => '水深19〜30 mの安全なダイビング技術'
        ],
        [
            'slug' => 'naturalist',
            'name' => 'ナチュラリスト',
            'price' => 27500,
            'duration' => '1日/2ダイブ',
            'desc' => '海洋生態系と環境保全について学ぶ'
        ],
        [
            'slug' => 'sr',
            'name' => 'サーチ＆リカバリー',
            'price' => 44500,
            'duration' => '2日/4ダイブ',
            'desc' => '水中での物体捜索と回収技術'
        ],
        [
            'slug' => 'night',
            'name' => 'ナイトダイバー',
            'price' => 44500,
            'duration' => '1日/3ダイブ',
            'desc' => '夜間ダイビングの技術と安全管理'
        ],
        [
            'slug' => 'msd',
            'name' => 'マスター・スクーバ・ダイバー (MSD)',
            'price' => 0,
            'duration' => '-',
            'desc' => 'AOW + レスキュー + 5 つの SP + ログ 50 本以上で取得可能'
        ],
        [
            'slug' => 'dm',
            'name' => 'ダイブマスター',
            'price' => 132000,
            'duration' => '10日間〜',
            'desc' => 'リーダーシップスキルを身につけ、プロへの第一歩。',
            'bullet_left' => [
                '期間： 10日間〜',
                '前提条件： 18歳以上、レスキュー、EFR、ログ40本以上',
                '料金： ¥132,000（税込）',
                '含まれるもの： 講習費'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ ダイビング知識とスキルの向上',
                '‣ リーダーシップ開発',
                '‣ 実地でのダイビング管理'
            ]
        ],
        [
            'slug' => 'ai',
            'name' => 'アシスタントインストラクター (AI)',
            'price' => 132000,
            'duration' => '4日間〜',
            'desc' => 'インストラクターになるための準備段階。',
            'bullet_left' => [
                '期間： 4日間〜',
                '前提条件： 18歳以上、ダイブマスター、ログ60本以上',
                '料金： ¥132,000（税込）',
                '含まれるもの： 講習費'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ インストラクター開発の基礎',
                '‣ プール／限定水域での指導'
            ]
        ],
        [
            'slug' => 'idc',
            'name' => 'インストラクター開発コース (IDC)',
            'price' => 220000,
            'duration' => '8〜10日間',
            'desc' => 'OWインストラクターになるための包括的トレーニング。',
            'bullet_left' => [
                '期間： 8〜10日間',
                '前提条件： 18歳以上、ダイブマスター、ログ60本以上',
                '料金： ¥220,000（税込）',
                '含まれるもの： 講習費'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ PADIシステムと規格',
                '‣ 教授法・リスク管理'
            ]
        ],
        [
            'slug' => 'staff',
            'name' => 'IDCスタッフインストラクター',
            'price' => 98000,
            'duration' => '最短4日間〜',
            'desc' => 'IDC で候補生を評価し、育成補助を行う資格。',
            'bullet_left' => [
                '期間： 最短4日間〜',
                '前提条件： インストラクター、ログ150本以上（推奨）',
                '料金： ¥98,000（税込）',
                '含まれるもの： 講習費'
            ],
            'bullet_right' => [
                '学習内容',
                '‣ IDCプログラム運営補助',
                '‣ インストラクター育成技術'
            ]
        ],
        [
            'slug' => 'spi',
            'name' => 'スペシャルティインストラクター (SPI)',
            'price' => 0,
            'duration' => '各SP 1〜2日間',
            'desc' => '各スペシャルティを教えられるインストラクター資格。',
            'bullet_left' => [
                '期間： 各SP 1〜2日間',
                '前提条件： PADIインストラクター + 該当SPログ実績',
                '料金： お問い合わせください',
                '含まれるもの： 講習費'
            ],
            'bullet_right' => [
                '主なスペシャルティ',
                '‣ ナイト／ディープ／ナビ',
                '‣ ドライスーツ／水中写真',
                '‣ 魚類識別／ボート／ドリフト'
            ]
        ]
    ];
}

// グローバル変数として設定（JSON-LD用）
$GLOBALS['license_courses'] = $courses;

get_header(); ?>

<!-- Navigation Header -->
<header class="page-header">
    <div class="container">
        <nav class="page-nav">
            <a href="/" class="back-link">ホームへ戻る</a>
            <ul class="nav-links">
                <li><a href="/">ホーム</a></li>
                <li><a href="<?php echo get_permalink(); ?>" class="active">ライセンス</a></li>
                <li><a href="/fun-diving/">ファンダイビング</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="license-page" role="main">
    <!-- Hero Section -->
    <section class="hero license-hero" role="banner" aria-labelledby="hero-title">
        <div class="hero-media">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/diving-license-hero.png" 
                 alt="PADIダイビングライセンス講習 - 三浦海の学校" 
                 class="hero-image" width="1920" height="1080">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-inner">
            <h1 id="hero-title">PADI ダイビングライセンス講習</h1>
            <p class="hero-lead">
                <strong>体験ダイビングからレスキューダイバーまで5コース開講</strong><br>
                世界基準PADIライセンスを三浦の海で取得<br>
                あなたの目標に合わせたコース選択が可能
            </p>
            <a class="btn-cta ripple" href="#license-cta">コース詳細・お申し込み</a>
        </div>
    </section>

    <!-- コース一覧 -->
    <section class="courses-section" aria-labelledby="courses-title">
        <div class="container">
            <h2 id="courses-title" class="section-title">開講コース一覧</h2>
            <div class="courses-grid" role="list">
                <?php foreach ($courses as $course): ?>
                <article class="course-card" role="listitem">
                    <div class="course-header">
                        <span class="course-icon"></span>
                        <div class="course-info">
                            <h3 class="course-title"><?php echo esc_html($course['name']); ?></h3>
                            <p class="course-duration"><?php echo esc_html($course['duration']); ?></p>
                            <p class="course-price">¥<?php echo number_format($course['price']); ?></p>
                        </div>
                    </div>
                    <p class="course-desc"><?php echo esc_html($course['desc']); ?></p>
                    
                    <button class="btn-outline" data-course="<?php echo $course['slug']; ?>" aria-expanded="false">
                        詳細を見る
                    </button>
                    
                    <div class="course-detail" id="detail-<?php echo $course['slug']; ?>" aria-hidden="true">
                        <div class="detail-content">
                            <div class="detail-columns">
                                <div class="detail-left">
                                    <?php foreach ($course['bullet_left'] as $item): ?>
                                        <p><?php echo esc_html($item); ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="detail-right">
                                    <?php foreach ($course['bullet_right'] as $item): ?>
                                        <p><?php echo esc_html($item); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="detail-actions">
                                <a href="#license-cta" class="btn-primary">このコースに申し込む</a>
                                <a href="https://lin.ee/kK3d5p2" target="_blank" rel="noopener" class="btn-secondary line-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" width="20" height="20" style="margin-right: 8px;">
                                        <path fill="#00c300" d="M18 2C9.2 2 2 8.4 2 16.2c0 4.5 2.2 8.4 5.8 11-.2.8-.9 3.2-1 3.6 0 0-.1.3.1.4s.4.1.4.1c.5 0 3.6-1.2 4.2-1.5 1.2.3 2.6.4 4 .4 8.8 0 16-6.4 16-14.2S26.8 2 18 2zM18 26c-1.3 0-2.5-.1-3.7-.4l-1.2-.3-.9.5c-.8.4-1.7.7-2.6.9.3-.9.5-1.7.6-2.3l.2-.8-.6-.6c-2.9-2.3-4.6-5.5-4.6-9C4.3 9.6 10.4 4 18 4s13.7 5.6 13.7 12.2S25.6 26 18 26z"/>
                                        <path fill="#fff" d="M25 17.4c0-.4-.3-.6-.6-.7l-2.4-.8-1.6 3.3 1.6.8c.3.1.6 0 .7-.3l2.3-2.3zm-4.6-1.4-1.6-.8-1.6 3.3 1.6.8 1.6-3.3zm-3.4-1.7-1.6-.8-1.6 3.3 1.6.8 1.6-3.3zm-3.4-1.6-1.6-.8-1.6 3.3 1.6.8 1.6-3.3z"/>
                                    </svg>
                                    LINEで問い合わせ
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- スペシャルティコース -->
    <section id="sp-course" class="courses-section" aria-labelledby="sp-courses-title">
        <div class="container">
            <h2 id="sp-courses-title" class="section-title">スペシャルティコース</h2>
            <p class="section-lead">特定の分野でスキルを磨くスペシャルティコース。5つ取得で「マスター・スクーバ・ダイバー」の称号を得られます。</p>
            
            <div class="courses-grid" role="list">
                <?php foreach ($courses as $course):
                    if (in_array($course['slug'], ['ppb','dry','boat','fishid','digi','nav','deep','naturalist','sr','night'])): ?>
                <article class="course-card sp-card" role="listitem">
                    <div class="course-header">
                        <span class="course-icon sp"></span>
                        <div class="course-info">
                            <h3 class="course-title"><?php echo esc_html($course['name']); ?></h3>
                            <p class="course-duration"><?php echo esc_html($course['duration']); ?></p>
                            <p class="course-price"><?php echo $course['price'] ? '¥' . number_format($course['price']) : '価格はお問い合わせ'; ?></p>
                        </div>
                    </div>
                    <p class="course-desc"><?php echo esc_html($course['desc']); ?></p>
                    
                    <div class="sp-contact">
                        <p class="sp-contact-text">講習の詳細についてLINEでお気軽にお問い合わせください</p>
                        <a href="https://lin.ee/kK3d5p2" target="_blank" rel="noopener" class="btn-line-contact">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" width="18" height="18">
                                <path fill="#fff" d="M18 2C9.2 2 2 8.4 2 16.2c0 4.5 2.2 8.4 5.8 11-.2.8-.9 3.2-1 3.6 0 0-.1.3.1.4s.4.1.4.1c.5 0 3.6-1.2 4.2-1.5 1.2.3 2.6.4 4 .4 8.8 0 16-6.4 16-14.2S26.8 2 18 2zM18 26c-1.3 0-2.5-.1-3.7-.4l-1.2-.3-.9.5c-.8.4-1.7.7-2.6.9.3-.9.5-1.7.6-2.3l.2-.8-.6-.6c-2.9-2.3-4.6-5.5-4.6-9C4.3 9.6 10.4 4 18 4s13.7 5.6 13.7 12.2S25.6 26 18 26z"/>
                            </svg>
                            LINEで問い合わせ
                        </a>
                    </div>
                </article>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- MSD -->
    <section id="msd" class="msd-section" aria-labelledby="msd-title">
        <div class="container">
            <div class="msd-banner">
                <h2 id="msd-title">マスター・スクーバ・ダイバー (MSD)</h2>
                <p>PADIアドバンスドオープンウォーター、レスキューに加え、5つのスペシャルティを取得すると最上位ランク「MSD」の称号が得られます。</p>
                <div class="msd-badge">取得条件：AOW + レスキュー + 5スペシャルティ + ログ50本以上</div>
            </div>
        </div>
    </section>

    <!-- プロフェッショナルコース -->
    <section id="pro-course" class="courses-section" aria-labelledby="pro-courses-title">
        <div class="container">
            <h2 id="pro-courses-title" class="section-title">プロフェッショナルコース</h2>
            <p class="section-lead">ダイビングインストラクターを目指す方のためのプロフェッショナルプログラム。コースディレクターが直接指導します。</p>

            <div class="pro-feature">
                <div class="feat">充実の設備</div>
                <div class="feat">少人数制指導</div>
                <div class="feat">就職サポート</div>
            </div>

            <div class="courses-grid" role="list">
                <?php foreach ($courses as $course):
                    if (in_array($course['slug'], ['dm','ai','idc','staff','spi'])): ?>
                <article class="course-card pro-card" role="listitem">
                    <div class="course-header">
                        <span class="course-icon pro"></span>
                        <div class="course-info">
                            <h3 class="course-title"><?php echo esc_html($course['name']); ?></h3>
                            <p class="course-duration"><?php echo esc_html($course['duration']); ?></p>
                            <p class="course-price"><?php echo $course['price'] ? '¥' . number_format($course['price']) : 'お問い合わせ'; ?></p>
                        </div>
                    </div>
                    <p class="course-desc"><?php echo esc_html($course['desc']); ?></p>
                    
                    <button class="btn-outline" data-course="<?php echo $course['slug']; ?>" aria-expanded="false">
                        詳細を見る
                    </button>
                    
                    <div class="course-detail" id="detail-<?php echo $course['slug']; ?>" aria-hidden="true">
                        <div class="detail-content">
                            <div class="detail-columns">
                                <div class="detail-left">
                                    <?php foreach ($course['bullet_left'] as $item): ?>
                                        <p><?php echo esc_html($item); ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="detail-right">
                                    <?php foreach ($course['bullet_right'] as $item): ?>
                                        <p><?php echo esc_html($item); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="detail-actions">
                                <a href="#license-cta" class="btn-primary">このコースに申し込む</a>
                                <a href="https://lin.ee/kK3d5p2" target="_blank" rel="noopener" class="btn-secondary line-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" width="20" height="20" style="margin-right: 8px;">
                                        <path fill="#00c300" d="M18 2C9.2 2 2 8.4 2 16.2c0 4.5 2.2 8.4 5.8 11-.2.8-.9 3.2-1 3.6 0 0-.1.3.1.4s.4.1.4.1c.5 0 3.6-1.2 4.2-1.5 1.2.3 2.6.4 4 .4 8.8 0 16-6.4 16-14.2S26.8 2 18 2zM18 26c-1.3 0-2.5-.1-3.7-.4l-1.2-.3-.9.5c-.8.4-1.7.7-2.6.9.3-.9.5-1.7.6-2.3l.2-.8-.6-.6c-2.9-2.3-4.6-5.5-4.6-9C4.3 9.6 10.4 4 18 4s13.7 5.6 13.7 12.2S25.6 26 18 26z"/>
                                        <path fill="#fff" d="M25 17.4c0-.4-.3-.6-.6-.7l-2.4-.8-1.6 3.3 1.6.8c.3.1.6 0 .7-.3l2.3-2.3zm-4.6-1.4-1.6-.8-1.6 3.3 1.6.8 1.6-3.3zm-3.4-1.7-1.6-.8-1.6 3.3 1.6.8 1.6-3.3zm-3.4-1.6-1.6-.8-1.6 3.3 1.6.8 1.6-3.3z"/>
                                    </svg>
                                    LINEで問い合わせ
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="license-cta" id="license-cta" aria-labelledby="cta-title">
        <div class="container">
            <div class="cta-content">
                <h2 id="cta-title">ダイビングライセンス取得のお申し込み</h2>
                <p><strong>5コース対応</strong><br>あなたの目標に合わせたコース選択をサポートします</p>
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
                    <li>営業時間：9:00〜16:00｜不定休</li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>