<?php
/*
Template Name: アドバンスド・オープンウォーターコース
*/

// SEO設定を追加
function add_aow_seo_meta() {
    ?>
    <meta name="description" content="【神奈川・三浦】PADIアドバンスド・オープンウォーターダイバー(AOW)講習。東京から日帰りOK・専用プール完備・少人数制。5ダイブ(3ビーチ+2ボート)でスキルアップ。水深30mまで潜れるようになり、ダイビングの世界が広がります。">
    <meta name="keywords" content="PADIアドバンス,AOW,ダイビング,三浦,神奈川,東京近郊,日帰り,ダイビングライセンス,スキルアップ,ディープダイブ,ナビゲーション,ドライスーツ,ボートダイブ">
    <!-- 地域向けSEO対策 -->
    <meta name="geo.region" content="JP-14" />
    <meta name="geo.placename" content="三浦市" />
    <!-- OGPタグ -->
    <meta property="og:title" content="【神奈川・三浦】PADIアドバンス講習｜AOW取得でダイビングの世界を広げよう！｜三浦海の学校" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>" />
    <meta property="og:image" content="https://miura-diving.com/wp-content/uploads/2020/03/IMG_5283.jpg" />
    <meta property="og:description" content="神奈川県三浦市のダイビングスクール「三浦海の学校」のPADIアドバンスドコース。東京から日帰りOK・5ダイブで水深30mまで潜れるようになり、ダイビングの世界が広がります。" />
    <meta property="og:site_name" content="三浦海の学校" />
    <!-- Twitterカード -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="神奈川・三浦でPADIアドバンス講習" />
    <meta name="twitter:description" content="三浦海の学校のPADIアドバンスド講習で、ディープダイブやボートダイブなど幅広いスキルを習得。東京から日帰りOK。" />
    
    <!-- Schema.org マークアップ for Rich Snippets -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Course",
      "name": "PADIアドバンスド・オープンウォーターダイバーコース",
      "description": "オープンウォーターダイバー資格を持つ方のための次のステップ。水深30mまでのダイビングや多彩なスキルを身につけるコース。神奈川県三浦市の日帰りダイビングスクール。",
      "provider": {
        "@type": "Organization",
        "name": "三浦海の学校",
        "sameAs": "https://miura-diving.com/",
        "address": {
          "@type": "PostalAddress",
          "addressRegion": "神奈川県",
          "addressLocality": "三浦市",
          "postalCode": "238-0101"
        }
      },
      "hasCourseInstance": {
        "@type": "CourseInstance",
        "courseMode": "ONSITE",
        "duration": "P2D",
        "startDate": "<?php echo date('Y-m-d'); ?>",
        "location": {
          "@type": "Place",
          "name": "三浦海の学校",
          "address": {
            "@type": "PostalAddress",
            "addressRegion": "神奈川県",
            "addressLocality": "三浦市"
          }
        },
        "offers": {
          "@type": "Offer",
          "price": "53900",
          "priceCurrency": "JPY",
          "availability": "https://schema.org/InStock",
          "validFrom": "<?php echo date('Y-m-d', strtotime('-1 month')); ?>"
        }
      },
      "coursePrerequisites": "PADIオープンウォーターダイバーまたは同等の資格",
      "keywords": ["ダイビング", "PADI", "アドバンス", "AOW", "三浦", "神奈川", "日帰り", "東京近郊"]
    }
    </script>
    <?php
}
add_action('wp_head', 'add_aow_seo_meta', 1);

// カスタムタイトル設定
function custom_aow_page_title($title) {
    if (is_page_template('aow-course-template.php')) {
        return '【神奈川・三浦】PADIアドバンス講習｜AOW取得でダイビングの世界を広げよう！｜三浦海の学校';
    }
    return $title;
}
add_filter('pre_get_document_title', 'custom_aow_page_title');

get_header(); // ヘッダーを読み込む
?>

<!-- メインコンテンツ部分 -->
<main class="container aow-course-container">
    <div class="page-header">
        <h1 class="main-title">PADIアドバンスド・オープンウォーターダイバー講習</h1>
        <p class="subtitle">「もっと自由に潜りたい」「深いところへ行ってみたい」「ナビゲーションや中性浮力をもっと極めたい」<br>そんなあなたにぴったりなコースです</p>
    </div>
    
    <section id="intro">
        <div class="benefits-box">
            <p>三浦海の学校では、<span class="highlight">東京から日帰りOK・専用プール完備・少人数制の安心環境</span>で、一人ひとりに合わせたペースでスキルアップをサポートします。初めての方や女性の方も安心してご参加いただけます。</p>
        </div>
        
        <div class="grid">
            <div class="card">
                <div class="card-image" style="background-image: url('https://miura-diving.com/wp-content/uploads/2-16.png');"></div>
                <div class="card-content">
                    <h3 class="card-title">アクセス抜群</h3>
                    <p>都心から約90分、京急三崎口駅から無料送迎。日帰りでライセンス取得可能です。</p>
                </div>
            </div>
            <div class="card">
                <div class="card-image" style="background-image: url('https://miura-diving.com/wp-content/uploads/1-15.png');"></div>
                <div class="card-content">
                    <h3 class="card-title">専用プール完備</h3>
                    <p>安心の専用プール環境で基礎トレーニングができ、自信を持って海に入れます。</p>
                </div>
            </div>
            <div class="card">
                <div class="card-image" style="background-image: url('https://miura-diving.com/wp-content/uploads/3-15.png');"></div>
                <div class="card-content">
                    <h3 class="card-title">少人数制指導</h3>
                    <p>経験豊富なインストラクターが一人ひとりのペースに合わせて丁寧に指導します。</p>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <section id="about">
        <h2>アドバンスド・オープンウォーターとは？</h2>
        <p>AOWは、オープンウォーター講習（OWD）を修了した方が受けられる<span class="highlight">次のステップ</span>。最大水深が30mになり、より多彩なダイビングを安全に楽しめるようになります。</p>
        
        <p>講習では、全部で<span class="highlight">5ダイブ（3ビーチダイブ、2ボートダイブ）</span>を行います。</p>

        <h3>必須科目</h3>
        <ul class="feature-list">
            <li>ディープダイビング</li>
            <li>アンダーウォーターナビゲーション</li>
        </ul>
        
        <h3>選択科目（3つ選択）</h3>
        <ul class="feature-list">
            <li>PPB（ピークパフォーマンス・ボイヤンシー/中性浮力）</li>
            <li>ナチュラリスト</li>
            <li>魚の見分け方</li>
            <li>サーチ＆リカバリー</li>
            <li>ドライスーツ</li>
            <li>ボート</li>
            <li>水中フォト（水中デジカメ所有者のみ）</li>
        </ul>
        
        <p>経験の幅を広げ、自信をつけたい方に最適なプログラムです。</p>
        
        <div class="card">
            <div class="card-content">
                <h3>こんな方におすすめ！</h3>
                <ul class="feature-list">
                    <li>OWDを取ってしばらく経ったけど、もっと上達したい</li>
                    <li>ディープや沈船など、上級スポットにチャレンジしたい</li>
                    <li>ファンダイブをもっと快適に楽しみたい</li>
                    <li>海外旅行に向けてステップアップしたい</li>
                </ul>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <section id="benefits">
        <h2>三浦でAOW講習を受けるメリット</h2>
        
        <div class="card">
            <div class="card-content">
                <h3>✨ 東京・横浜から日帰りOKのアクセス</h3>
                <p>都心から約90分で到着。<span class="highlight">京急三崎口駅からは無料送迎あり</span>なので、重たい器材があっても安心です。</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-content">
                <h3>✨ 専用プール完備＆穏やかな湾内の海</h3>
                <ul class="feature-list">
                    <li>最初はプールでしっかり感覚を取り戻し</li>
                    <li>その後、<span class="highlight">流れの少ないビーチポイント</span>で実践</li>
                </ul>
                <p>三浦の海は<span class="highlight">最大水深6〜7mの遠浅地形</span>。講習にも最適な環境です。</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-content">
                <h3>✨ 経験豊富なインストラクターによる少人数制指導</h3>
                <p>三浦海の学校のインストラクターは、<span class="highlight">一人ひとりの不安や目標に寄り添った指導</span>がモットー。質問しやすく、和やかな雰囲気の中でマイペースに進められます。女性インストラクターも在籍しているので、女性の方も安心してご参加いただけます。</p>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <section id="schedule">
        <h2>講習スケジュール（2日間の例）</h2>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>日程</th>
                        <th>内容</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1日目</td>
                        <td>ナビゲーション／中性浮力／水中フォトなど</td>
                    </tr>
                    <tr>
                        <td>2日目</td>
                        <td>ディープダイブ／ナチュラリスト／ログ付け＆申請</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <p>※内容はご希望によりカスタマイズ可能。お気軽にご相談ください！</p>
    </section>

    <div class="section-divider"></div>

    <section id="details">
        <h2>料金・持ち物・参加条件</h2>
        
        <div class="grid">
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title">料金</h3>
                    <p><span class="highlight">講習料金：53,900円（税込）</span><br>※教材・申請料込み／レンタル器材別</p>
                    <p>レンタル器材：</p>
                    <ul class="feature-list">
                        <li><span class="highlight">フルセット（ウェット）：5,500円（税込）</span></li>
                        <li><span class="highlight">フルセット（ドライスーツ）：5,500円（税込）</span></li>
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title">参加条件</h3>
                    <p><span class="highlight">PADI OWD（または他団体の同等資格）を取得済みの方</span></p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title">持ち物</h3>
                    <ul class="feature-list">
                        <li>水着</li>
                        <li>タオル</li>
                        <li>ログブック</li>
                        <li>Cカード</li>
                        <li>飲み物</li>
                        <li>防寒具（季節によって）</li>
                        <li>日焼け止め・帽子（夏場）</li>
                        <li>メイク落とし（女性）</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <section id="faq">
        <h2>よくある質問</h2>
        
        <div class="faq-content">
            <div class="faq-group">
                <h3>Q：女性一人でも参加できますか？</h3>
                <p>もちろんです！女性の方も多く参加されていますし、女性インストラクターも在籍しています。更衣室やシャワーも男女別で完備していますので、安心してご参加いただけます。</p>
            </div>
            
            <div class="faq-group">
                <h3>Q：ブランクがあるけど大丈夫？</h3>
                <p>大丈夫です！リフレッシュ講習との組み合わせも可能です。久しぶりの方向けにプールで基本スキルを復習してから海に入るので、安心してください。</p>
            </div>
            
            <div class="faq-group">
                <h3>Q：1人で参加しても大丈夫？</h3>
                <p>もちろんOK！ほとんどの方が1人参加です。アットホームな雰囲気の中、インストラクターがしっかりサポートします。</p>
            </div>
            
            <div class="faq-group">
                <h3>Q：自分に合ったアドベンチャーダイブを選べる？</h3>
                <p>はい。事前にカウンセリングを行い、目的や経験、興味に合わせて最適な内容をご提案します。不安なことがあれば、何でもご相談ください。</p>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <section id="contact" class="cta-section">
        <h2>ご予約・お問い合わせ</h2>
        
        <div class="contact-methods">
            <div class="contact-method">
                <div class="contact-icon">💬</div>
                <h3>LINE</h3>
                <p>LINEからのご予約が最もスムーズです！</p>
                <a href="https://lin.ee/rfr0YG1" class="btn">LINE友だち追加</a>
            </div>
            
            <div class="contact-method">
                <div class="contact-icon">✉️</div>
                <h3>メール</h3>
                <p>詳細なお問い合わせはこちらから</p>
                <a href="https://miura-diving.com/contact/" class="btn btn-secondary">お問い合わせフォーム</a>
            </div>
            
            <div class="contact-method">
                <div class="contact-icon">📱</div>
                <h3>お電話</h3>
                <p>お急ぎの方はお電話でどうぞ</p>
                <a href="tel:0468800835" class="btn">今すぐ電話する</a>
            </div>
        </div>
    </section>

    <section id="summary">
        <h2>まとめ：次の海へ、自信を持って進もう</h2>
        
        <p>PADIアドバンス講習は、あなたのダイビングライフを次のステージへ導いてくれる鍵。</p>
        <p>三浦海の学校なら、<span class="highlight">安心・近い・アットホームな雰囲気</span>の中で、楽しみながらスキルアップできます。</p>
        <p>「もっと自由に、もっと深く海を楽しみたい」 そんなあなたの一歩を、私たちが笑顔でサポートします。</p>
        
        <div style="text-align: center; margin: 40px 0;">
            <a href="#contact" class="btn">今すぐ講習のご予約はこちらから</a>
            <a href="https://lin.ee/rfr0YG1" class="btn btn-secondary">不安や質問がある方はLINEでお気軽にご相談ください！</a>
        </div>
    </section>
</main>


<?php
get_footer(); // フッターを読み込む
?>