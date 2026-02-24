<?php
/**
 * Template Name: リフレッシュダイビングページ
 * Description: 三浦海の学校のリフレッシュダイビングプログラムを紹介するページテンプレート
 */

// WordPressのヘッダーを読み込む前に、このページ専用のヘッダースタイルを追加
add_action('wp_head', function() {
    echo '<style>
        /* テーマのヘッダー部分を非表示にする可能性があるスタイル */
        .site-header, #masthead, header.entry-header, .page-header {
            display: none !important;
        }
        
        /* ページの余白をリセット */
        body, .site, #page, .site-content, .content-area, #primary, #main {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
    </style>';
});

get_header(); // WordPressのヘッダーを読み込み
?>

<!-- リフレッシュダイビング用カスタムスタイル -->
<style>
    :root {
        --primary-color: #0071e3;
        --secondary-color: #47b0ff;
        --accent-color: #00b4d8;
        --light-blue: #e8f4ff;
        --text-color: #1d1d1f;
        --light-text: #86868b;
        --white: #ffffff;
        --light-grey: #f5f5f7;
        --mid-grey: #d2d2d7;
        --dark-grey: #424245;
    }
    
    /* 全体のリセットとベーススタイル */
    .refresh-diving-page * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    
    .refresh-diving-page {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        color: var(--text-color);
        line-height: 1.6;
        background-color: var(--white);
    }
    
    .refresh-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
    }
    
    /* ヘッダー部分の修正 */
    .refresh-header-wrapper {
        background-color: var(--light-blue);
        width: 100%;
        padding: 0;
        margin: 0;
        position: relative;
        z-index: 1;
    }
    
    .refresh-header {
        width: 100%;
        text-align: center;
        padding: 3rem 0;
    }
    
    .refresh-logo {
        width: 120px;
        height: auto;
        margin: 0 auto 1.5rem;
        display: block;
    }
    
    .refresh-header h1 {
        font-size: 2.5rem;
        font-weight: 600;
        margin: 0 auto 1.5rem;
        line-height: 1.3;
        color: var(--text-color);
        max-width: 90%;
    }
    
    .refresh-subtitle {
        font-size: 1.5rem;
        color: var(--light-text);
        margin: 0 auto 1rem;
        font-weight: 400;
        max-width: 90%;
    }
    
    /* ヒーロー画像のポジショニング修正 */
    .refresh-hero-container {
        position: relative;
        margin-top: -20px;
        padding: 0 20px;
        z-index: 2;
    }
    
    .refresh-hero-img {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        display: block;
        margin: 0 auto;
    }
    
    .refresh-section {
        padding: 3rem 0;
    }
    
    .refresh-section h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
    }
    
    .refresh-section h3 {
        font-size: 1.5rem;
        margin: 2rem 0 1rem;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .refresh-section p {
        margin-bottom: 1.2rem;
        font-size: 1.1rem;
        color: var(--text-color);
    }
    
    .refresh-highlight {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .refresh-intro {
        font-size: 1.2rem;
        line-height: 1.8;
    }
    
    .refresh-feature-card {
        background-color: var(--light-grey);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .refresh-feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .refresh-feature-card h3 {
        color: var(--text-color);
        margin-top: 0;
    }
    
    .refresh-feature-card img {
        width: 100%;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .refresh-feature-number {
        background-color: var(--primary-color);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-weight: 600;
    }
    
    .refresh-flow {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        counter-reset: flow-counter;
    }
    
    .refresh-flow-step {
        flex-basis: calc(33.333% - 20px);
        margin-bottom: 2rem;
        position: relative;
        counter-increment: flow-counter;
    }
    
    .refresh-flow-step::before {
        content: counter(flow-counter);
        position: absolute;
        top: 0;
        left: 0;
        width: 40px;
        height: 40px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.2rem;
    }
    
    .refresh-flow-step-content {
        background-color: var(--light-grey);
        border-radius: 12px;
        padding: 2rem;
        padding-top: 3rem;
        height: 100%;
    }
    
    .refresh-price-table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
    }
    
    .refresh-price-table th, .refresh-price-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--mid-grey);
    }
    
    .refresh-price-table th {
        background-color: var(--light-blue);
    }
    
    .refresh-cta {
        background-color: var(--light-blue);
        text-align: center;
        padding: 4rem 0;
        border-radius: 12px;
        margin: 3rem 0;
    }
    
    .refresh-cta h2 {
        margin-bottom: 1rem;
    }
    
    .refresh-button {
        display: inline-block;
        background-color: var(--primary-color);
        color: white !important;
        padding: 0.8rem 2rem;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 1rem;
        transition: all 0.3s ease;
    }
    
    .refresh-button:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
        text-decoration: none;
    }
    
    .refresh-faq-item {
        margin-bottom: 1.5rem;
    }
    
    .refresh-faq-question {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--primary-color);
    }
    
    .refresh-contact-info {
        display: flex;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    .refresh-contact-method {
        flex-basis: calc(50% - 1rem);
        margin-right: 1rem;
        margin-bottom: 1rem;
    }
    
    .refresh-image-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin: 2rem 0;
    }
    
    .refresh-image-grid img {
        width: 100%;
        border-radius: 12px;
        transition: transform 0.3s ease;
    }
    
    .refresh-image-grid img:hover {
        transform: scale(1.03);
    }
    
    .refresh-footer-section {
        background-color: var(--light-grey);
        padding: 3rem 0;
        text-align: center;
        margin-top: 3rem;
    }
    
    .refresh-footer-links {
        display: flex;
        justify-content: center;
        margin: 1rem 0;
        flex-wrap: wrap;
    }
    
    .refresh-footer-links a {
        margin: 0.5rem 1rem;
        color: var(--light-text);
        text-decoration: none;
    }
    
    .refresh-copyright {
        color: var(--light-text);
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .refresh-header h1 {
            font-size: 2rem;
        }
        
        .refresh-subtitle {
            font-size: 1.2rem;
        }
        
        .refresh-flow-step {
            flex-basis: 100%;
        }
        
        .refresh-contact-method {
            flex-basis: 100%;
            margin-right: 0;
        }
        
        .refresh-image-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- リフレッシュダイビングページのコンテンツ開始 -->
<div class="refresh-diving-page">
    <!-- ヘッダーセクション -->
    <div class="refresh-header-wrapper">
        <header class="refresh-header">
            <div class="refresh-container">
                <img src="https://miura-diving.com/wp-content/uploads/ヘッダーデザイン.png" alt="三浦海の学校ロゴ" class="refresh-logo">
                <h1>【久しぶりでも安心】<br>三浦でリフレッシュダイビング</h1>
                <p class="refresh-subtitle">日帰りOK・専用プール＆穏やかな湾内の海で再スタート</p>
            </div>
        </header>
    </div>
    
    <!-- メインコンテンツ -->
    <div class="refresh-hero-container">
        <?php 
        // アイキャッチ画像がある場合はそれを表示、なければデフォルト画像
        if (has_post_thumbnail()) {
            echo '<img src="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')) . '" alt="三浦の美しい海でダイビングを楽しむ様子" class="refresh-hero-img">';
        } else {
            echo '<img src="https://miura-diving.com/wp-content/uploads/リフレッシュダイビングメイン.png" alt="三浦の美しい海でダイビングを楽しむ様子" class="refresh-hero-img">';
        }
        ?>
    </div>
    
    <div class="refresh-container">
        <!-- イントロセクション -->
        <section class="refresh-section">
            <p class="refresh-intro">「久しぶりにダイビングしたいけど、ちょっと不安…」<br>「器材の扱い、ちゃんと覚えてるかな？」</p>
            <p class="refresh-intro">そんなあなたにぴったりなのが、<span class="refresh-highlight">三浦海の学校のリフレッシュダイビング</span>！</p>
            <p>東京から日帰りで通える立地に加え、<span class="refresh-highlight">ダイビング専用のプール</span>を完備。さらに、目の前に広がる海は<span class="refresh-highlight">穏やかな湾内のビーチダイビング</span>なので、久しぶりの方でも安心して潜れます。</p>
            <p>この記事では、リフレッシュダイビングがなぜ三浦海の学校で最適なのか、その魅力を徹底解説します！</p>
            
            <div class="refresh-image-grid">
                <img src="https://miura-diving.com/wp-content/uploads/1-12.png" alt="三浦海の学校の専用プール">
                <img src="https://miura-diving.com/wp-content/uploads/2-13.png" alt="穏やかな湾内でのダイビング風景">
            </div>
        </section>
        
        <!-- リフレッシュダイビングの説明 -->
        <section class="refresh-section">
            <h2>リフレッシュダイビングってなに？</h2>
            <p>リフレッシュダイビングとは、<span class="refresh-highlight">ダイビングにブランクがある方や、スキルに不安がある方のための再練習プログラム</span>です。</p>
            <ul style="margin-left: 1.5rem; margin-bottom: 1.5rem;">
                <li>「ライセンス（Cカード）は持ってるけど、何年も潜っていない」</li>
                <li>「中性浮力の取り方を忘れた気がする」</li>
                <li>「器材の扱いに自信がない…」</li>
            </ul>
            <p>そんな方に向けて、もう一度しっかりとダイビングの基本を思い出していただく内容になっています。</p>
            <img src="https://miura-diving.com/wp-content/uploads/リフレッシュ2.png" alt="インストラクターがダイビング器材の使い方を教えている様子" style="width: 100%; border-radius: 12px; margin: 1.5rem 0;">
        </section>
        
        <!-- 5つの理由 -->
        <section class="refresh-section">
            <h2>三浦海の学校でリフレッシュすべき5つの理由</h2>
            
            <div class="refresh-feature-card">
                <img src="https://miura-diving.com/wp-content/uploads/4-9.png" alt="京急三崎口駅と三浦海の学校の位置関係">
                <h3><span class="refresh-feature-number">1</span>【東京から日帰りOK】アクセス抜群の好立地</h3>
                <p>三浦海の学校は<span class="refresh-highlight">神奈川県三浦半島</span>にあり、都内・横浜方面から<span class="refresh-highlight">電車でも車でも日帰り可能</span>です。</p>
                <p>しかも、<span class="refresh-highlight">電車でお越しの方には京急三崎口駅から無料送迎あり！</span> 駅からお店までの移動もラクラクで、荷物が多くても安心です。</p>
            </div>
            
            <div class="refresh-feature-card">
                <img src="https://miura-diving.com/wp-content/uploads/1-13.png" alt="三浦海の学校の専用プールでの練習風景">
                <h3><span class="refresh-feature-number">2</span>専用プール完備！まずは安心して練習から</h3>
                <p>「いきなり海に入るのはちょっと不安…」という方も大丈夫。</p>
                <p>三浦海の学校では、<span class="refresh-highlight">ダイビング専用のプール</span>で基本スキルをしっかり練習してから海へ向かいます。呼吸の感覚・マスククリア・中性浮力など、丁寧に復習できるので自信を取り戻せます。</p>
            </div>
            
            <div class="refresh-feature-card">
                <img src="https://miura-diving.com/wp-content/uploads/2-14.png" alt="三浦海の学校から海へのアクセス">
                <h3><span class="refresh-feature-number">3</span>海はすぐ目の前！ストレスフリーな動線</h3>
                <p>プール練習の後は、<span class="refresh-highlight">そのまま海へ移動可能</span>。敷地の目の前が海という最高の立地で、<span class="refresh-highlight">器材を背負ったままスムーズにエントリー</span>できます。</p>
                <p>無駄な移動がなく、初心者にもやさしい動線です。</p>
            </div>
            
            <div class="refresh-feature-card">
                <img src="https://miura-diving.com/wp-content/uploads/3-13.png" alt="穏やかな湾内での安心ダイビング">
                <h3><span class="refresh-feature-number">4</span>穏やかな湾内・ビーチダイビングで安心感◎</h3>
                <p>三浦海の学校のダイビングポイントは、<span class="refresh-highlight">湾内のビーチダイビング</span>。海況が穏やかで、<span class="refresh-highlight">流れもほとんどなく、遠浅の地形</span>が特徴です。</p>
                <ul style="margin-left: 1.5rem; margin-bottom: 1.5rem;">
                    <li>最大水深：約6〜7m</li>
                    <li>徐々に深くなる地形なのでプレッシャーが少ない</li>
                    <li>足のつく浅瀬からスタートできる</li>
                </ul>
                <p>これほどリフレッシュに向いた環境はなかなかありません。</p>
            </div>
            
            <div class="refresh-feature-card">
                <img src="https://miura-diving.com/wp-content/uploads/5-2.png" alt="経験豊富なインストラクターによる少人数指導">
                <h3><span class="refresh-feature-number">5</span>経験豊富なインストラクター＆少人数制</h3>
                <p>担当するのは、<span class="refresh-highlight">経験豊富なPADIインストラクター</span>。参加者のブランク歴や不安をしっかりヒアリングしたうえで、<span class="refresh-highlight">丁寧にマンツーマンまたは少人数で指導</span>します。</p>
                <p>「もう一度楽しくダイビングをしたい！」という気持ちに寄り添いながらサポートいたします。</p>
            </div>
        </section>
        
        <!-- 当日の流れ -->
        <section class="refresh-section">
            <h2>当日の流れ</h2>
            <div class="refresh-flow">
                <div class="refresh-flow-step">
                    <div class="refresh-flow-step-content">
                        <h3>集合・受付</h3>
                        <p>京急三崎口駅から無料送迎（車の方は駐車場あり）</p>
                    </div>
                </div>
                <div class="refresh-flow-step">
                    <div class="refresh-flow-step-content">
                        <h3>カウンセリング</h3>
                        <p>不安なこと・ブランク期間の確認</p>
                    </div>
                </div>
                <div class="refresh-flow-step">
                    <div class="refresh-flow-step-content">
                        <h3>プール練習</h3>
                        <p>呼吸、中性浮力、器材の扱いなど基本スキルを復習</p>
                    </div>
                </div>
                <div class="refresh-flow-step">
                    <div class="refresh-flow-step-content">
                        <h3>海洋ダイブ</h3>
                        <p>穏やかな湾内での実践ダイビング</p>
                    </div>
                </div>
                <div class="refresh-flow-step">
                    <div class="refresh-flow-step-content">
                        <h3>シャワー・着替え</h3>
                        <p>温かいシャワーでリフレッシュ</p>
                    </div>
                </div>
                <div class="refresh-flow-step">
                    <div class="refresh-flow-step-content">
                        <h3>解散</h3>
                        <p>笑顔でまた会いましょう</p>
                    </div>
                </div>
            </div>
            
            <img src="https://miura-diving.com/wp-content/uploads/リフレッシュダイビング当日の流れ.png" alt="リフレッシュダイビングでの一日の様子" style="width: 100%; border-radius: 12px; margin: 2rem 0;">
        </section>
        
        <!-- 料金について -->
        <section class="refresh-section">
            <h2>料金について</h2>
            <table class="refresh-price-table">
                <tr>
                    <th>コース名</th>
                    <th>料金（税込）</th>
                    <th>内容</th>
                </tr>
                <tr>
                    <td>リフレッシュダイビング</td>
                    <td><span class="refresh-highlight">14,800円／1名</span></td>
                    <td>カウンセリング・プール練習・海洋ダイブ1本</td>
                </tr>
                <tr>
                    <td>フルレンタル器材<br>(ウェットスーツ)</td>
                    <td>5,500円〜</td>
                    <td>ウェットスーツ・BCD・レギュレーター他一式</td>
                </tr>
                <tr>
                    <td>フルレンタル器材<br>(ドライスーツ)</td>
                    <td>8,800円</td>
                    <td>ドライスーツ・BCD・レギュレーター他一式</td>
                </tr>
            </table>
            <p>ライセンスカード（Cカード）をお持ちであれば、どなたでもご参加いただけます。</p>
        </section>
        
        <!-- CTA -->
        <div class="refresh-cta">
            <h2>さあ、もう一度海の世界へ</h2>
            <p>ブランクがあっても大丈夫。三浦海の学校で安心して再スタート！</p>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="refresh-button">予約する</a>
        </div>
        
        <!-- よくある質問 -->
        <section class="refresh-section">
            <h2>よくある質問（Q&A）</h2>
            <div class="refresh-faq-item">
                <p class="refresh-faq-question">Q：何年も潜っていませんが大丈夫ですか？</p>
                <p>A：問題ありません！丁寧なカウンセリングとプール練習で、不安をしっかり解消できます。1年でも10年でもブランクの長さは問いません。</p>
            </div>
            <div class="refresh-faq-item">
                <p class="refresh-faq-question">Q：1人でも参加できますか？</p>
                <p>A：もちろんOKです。おひとりでのご参加も多く、安心してご利用いただけます。仲間と一緒の参加ももちろん歓迎です。</p>
            </div>
            <div class="refresh-faq-item">
                <p class="refresh-faq-question">Q：泳ぎが苦手でも大丈夫？</p>
                <p>A：泳げない方も参加可能です。プールからゆっくりスタートできます。ダイビングは泳ぎとは異なる技術なので、ぜひご安心ください。</p>
            </div>
            <div class="refresh-faq-item">
                <p class="refresh-faq-question">Q：持っていくものは何ですか？</p>
                <p>A：Cカード（ライセンスカード）、水着、タオル、着替えをお持ちください。その他の器材はレンタル可能です。</p>
            </div>
            <div class="refresh-faq-item">
                <p class="refresh-faq-question">Q：ダイビング後に観光もしたいのですが？</p>
                <p>A：三浦半島には観光スポットも多数！ダイビング後の三崎マグロや地元海鮮料理も人気です。お気軽にスタッフにご相談ください。</p>
            </div>
        </section>
        
        <!-- 予約方法・アクセス -->
        <section class="refresh-section">
            <h2>ご予約方法・アクセス</h2>
            <p>ご予約は公式LINEまたはお問い合わせフォームから簡単に行えます！</p>
            
            <div class="refresh-contact-info">
                <div class="refresh-contact-method">
                    <h3>公式LINE</h3>
                    <p>LINEからの予約が最もスムーズです！</p>
                    <a href="https://lin.ee/rfr0YG1" class="refresh-button" target="_blank">LINE友だち追加</a>
                </div>
                <div class="refresh-contact-method">
                    <h3>お問い合わせフォーム</h3>
                    <p>24時間受付中！お気軽にどうぞ</p>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="refresh-button">フォームを開く</a>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <h3>アクセス</h3>
                <p>住所：神奈川県三浦市三崎町諸磯1621<br>
                最寄駅：京急三崎口駅（無料送迎あり）<br>
                駐車場：完備</p>
                <img src="https://miura-diving.com/wp-content/uploads/マップ.png" alt="三浦海の学校への地図" style="width: 100%; border-radius: 12px; margin-top: 1rem;">
            </div>
        </section>
        
        <!-- まとめ -->
        <section class="refresh-section">
            <h2>まとめ：もう一度、海とつながる感動を</h2>
            <p>三浦海の学校のリフレッシュダイビングは、<span class="refresh-highlight">日帰りで気軽に、安心して「もう一度ダイビングしたい」気持ちを叶える場所</span>です。</p>
            <p>穏やかな海、専用プール、経験豊富なインストラクター。環境も人も、すべてがブランクダイバーに寄り添う設計になっています。</p>
            <p>「また潜りたい」その気持ちが芽生えた今が再スタートのタイミングです。ぜひ一緒に、もう一度海の世界へ戻りましょう。</p>
            
            <div class="refresh-image-grid" style="grid-template-columns: repeat(3, 1fr);">
                <img src="https://miura-diving.com/wp-content/uploads/1-14.png" alt="リフレッシュダイバーの笑顔1">
                <img src="https://miura-diving.com/wp-content/uploads/2-15.png" alt="リフレッシュダイバーの笑顔2">
                <img src="https://miura-diving.com/wp-content/uploads/3-14.png" alt="リフレッシュダイバーの笑顔3">
            </div>
            
            <div class="refresh-cta" style="margin-top: 3rem;">
                <h2>ご予約・お問い合わせ</h2>
                <p>まずはお気軽にご連絡ください！</p>
                <a href="https://lin.ee/rfr0YG1" class="refresh-button" target="_blank">LINE友だち追加</a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="refresh-button" style="margin-left: 1rem;">お問い合わせ</a>
            </div>