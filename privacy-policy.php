<?php
/**
 * Template Name: プライバシーポリシーテンプレート
 * Description: プライバシーポリシー専用のテンプレート
 */

get_header(); ?>

<div class="content-wrap">
    <div class="main-content">
        <main class="main-contents">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="article-header entry-header">
                    <h1 class="entry-title page-title" itemprop="headline" style="font-size: 2.5rem; font-weight: 600; letter-spacing: -0.02em; margin-bottom: 0.8rem;"><?php the_title(); ?></h1>
                <p style="text-align: center; color: #86868b; font-size: 1.2rem; margin-bottom: 2rem;">AquaBit LAB/三浦 海の学校のプライバシーに関する取り組み</p>
                </header>

                <div class="entry-content" style="font-size: 16px; line-height: 1.6; color: #1d1d1f;">
                    <!-- 最終更新日とイントロ -->
                    <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e7f2ff 100%); padding: 1.5rem; border-radius: 12px; margin: 1rem 0 2.5rem;">
                        <p style="color: #0066cc; margin-bottom: 0.5rem; font-size: 1rem; font-weight: 500;">最終更新日: 2025.02.22</p>
                        <p style="margin-bottom: 0;">このプライバシーポリシーは、AquaBit LAB/三浦 海の学校ウェブサイト（https://miura-diving.com）と提供されるすべてのサービス（ダイビング関連サービスおよび占いサービスを含む）に適用されます。本ポリシーをお読みいただき、当社がお客様の情報をどのように収集、使用、保護しているかをご理解ください。</p>
                    </div>
                    
                    <!-- 目次 -->
                    <div style="background-color: #f8f8fa; border-radius: 16px; padding: 2rem; margin: 2rem 0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <h3 style="margin-top: 0; margin-bottom: 1.2rem; color: #0066cc; font-size: 1.3rem;">目次</h3>
                        <ul style="list-style-type: none; margin-left: 0; padding-left: 0;">
                            <li style="margin-bottom: 0.9rem;"><a href="#about" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">1</span>私たちについて</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#general-policy" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">2</span>一般的なプライバシーポリシー</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#fortune-policy" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">3</span>占いサービスについて</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#cookies" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">4</span>Cookieとアクセス解析</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#third-party" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">5</span>第三者への提供</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#minors" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">6</span>未成年者について</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#opt-out" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">7</span>オプトアウト方法</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#contact" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">8</span>問い合わせ先</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#data-retention" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">9</span>データ保存期間</a></li>
                            <li style="margin-bottom: 0.9rem;"><a href="#your-rights" style="display: flex; align-items: center; text-decoration: none;"><span style="background-color: #e7f2ff; width: 24px; height: 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8rem; color: #0066cc;">10</span>データに対するあなたの権利</a></li>
                        </ul>
                    </div>
                    
                    <!-- 一般的なプライバシーポリシー -->
                    <section id="about">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">私たちについて
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <p>私たちのサイトアドレスは <a href="https://miura-diving.com" target="_blank">https://miura-diving.com</a> です。</p>
                    </section>
                    
                    <section id="general-policy">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">一般的なプライバシーポリシー
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">ダイビングサービス</span>
                        
                        <h3>個人情報の収集について</h3>
                        <p>当サイトでは、お問い合わせフォーム、LINE公式アカウント、SNSを通じて、お名前・メールアドレス・電話番号などの個人情報を収集する場合があります。</p>
                        
                        <h3>個人情報の利用目的</h3>
                        <p>収集した個人情報は、以下の目的で利用します。</p>
                        <ul>
                            <li>ダイビング体験やライセンス講習の予約受付・管理</li>
                            <li>お問い合わせ対応</li>
                            <li>キャンペーン・特典情報のご案内（LINE・メール）</li>
                        </ul>
                    </section>
                    
                    <hr style="height: 1px; background-color: #f5f5f7; margin: 3rem 0; border: none;">
                    
                    <!-- 占いサービスのプライバシーポリシー -->
                    <section id="fortune-policy">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">占いサービスについて
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">占いサービス</span>
                        
                        <h3>情報の収集と利用</h3>
                        <p>当サイトの占いサービスでは、ユーザーの安心・安全を最優先に考え、以下のような方針で個人情報を取り扱っています。</p>
                        <ul>
                            <li>占いに入力された<strong>氏名・生年月日・相談内容</strong>などの情報は、<strong>占い結果の生成にのみ使用</strong>されます。</li>
                            <li>お名前の入力は<strong>ニックネームでも構いません</strong>。本名でなくても正確な結果を得ることができます。</li>
                        </ul>
                        
                        <h3>データの保存について</h3>
                        <ul>
                            <li>入力内容は<strong>保存されません</strong>。占い終了後、自動的に削除され、運営側が閲覧・管理することはありません。</li>
                            <li>占いに利用しているAI（OpenAI API）は、<strong>入力データを学習に使用しない設定</strong>で運用しています。</li>
                        </ul>
                    </section>
                    
                    <hr style="height: 1px; background-color: #f5f5f7; margin: 3rem 0; border: none;">
                    
                    <!-- Cookie情報 -->
                    <section id="cookies">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">Cookieとアクセス解析
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">全サービス共通</span>
                        
                        <h3>クッキー（Cookie）とアクセス解析について</h3>
                        <p>当サイトでは、Google Analyticsを使用し、サイトの利用状況を分析しています。これにより、ユーザーの訪問履歴などの情報が収集されます。</p>
                        
                        <h3>Cookieの詳細</h3>
                        <p>サイトにコメントを残す際、お名前、メールアドレス、サイトを Cookie に保存することにオプトインできます。これはあなたの便宜のためであり、他のコメントを残す際に詳細情報を再入力する手間を省きます。この Cookie は1年間保持されます。</p>
                        <p>もしあなたがアカウントを持っており、このサイトにログインすると、私たちはあなたのブラウザーが Cookie を受け入れられるかを判断するために一時 Cookie を設定します。この Cookie は個人データを含んでおらず、ブラウザーを閉じた時に廃棄されます。</p>
                        <p>ログインの際さらに、ログイン情報と画面表示情報を保持するため、私たちはいくつかの Cookie を設定します。ログイン Cookie は2日間、画面表示オプション Cookie は1年間保持されます。「ログイン状態を保存する」を選択した場合、ログイン情報は2週間維持されます。ログアウトするとログイン Cookie は消去されます。</p>
                        <p>もし投稿を編集または公開すると、さらなる Cookie がブラウザーに保存されます。この Cookie は個人データを含まず、単に変更した投稿の ID を示すものです。1日で有効期限が切れます。</p>
                        
                        <h3>他サイトからの埋め込みコンテンツ</h3>
                        <p>このサイトの投稿には埋め込みコンテンツ (動画、画像、投稿など) が含まれます。他サイトからの埋め込みコンテンツは、訪問者がそのサイトを訪れた場合とまったく同じように振る舞います。</p>
                        <p>これらのサイトは、あなたのデータの収集、Cookie の使用、サードパーティによる追加トラッキングの埋め込み、埋め込みコンテンツとのやりとりの監視を行うことがあります。アカウントを使ってそのサイトにログイン中の場合、埋め込みコンテンツとのやりとりのトラッキングも含まれます。</p>
                    </section>
                    
                    <hr style="height: 1px; background-color: #f5f5f7; margin: 3rem 0; border: none;">
                    
                    <!-- 第三者提供情報 -->
                    <section id="third-party">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">第三者への提供について
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">全サービス共通</span>
                        <p>取得した個人情報は、適切に管理し、第三者へ提供することはありません。ただし、法令に基づき開示が求められた場合を除きます。</p>
                    </section>
                    
                    <!-- 未成年者についての方針 -->
                    <section id="minors">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">未成年者について
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">全サービス共通</span>
                        <p>当サイトのサービスは、原則として18歳以上の方を対象としています。未成年者がサービスを利用する場合は、保護者の同意を得た上でご利用ください。特に占いサービスについては、その性質上、内容を正しく理解できる年齢の方のご利用をお願いしています。</p>
                        <p>未成年者の個人情報については特に慎重に取り扱い、法令に基づいた適切な保護措置を講じています。未成年の方の情報が当サイトに登録されていることが判明した場合、保護者からの要請に応じて速やかに削除いたします。</p>
                    </section>
                    
                    <!-- オプトアウト方法 -->
                    <section id="opt-out">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">オプトアウト方法
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">全サービス共通</span>
                        <h3>Cookieの管理</h3>
                        <p>ブラウザの設定からCookieを無効化または削除することができます。主要ブラウザでのCookieの管理方法は以下の通りです：</p>
                        <ul>
                            <li><strong>Google Chrome</strong>: 設定 → プライバシーとセキュリティ → Cookie と他のサイトデータ</li>
                            <li><strong>Safari</strong>: 環境設定 → プライバシー → Cookie とウェブサイトのデータ</li>
                            <li><strong>Firefox</strong>: オプション → プライバシー → 履歴</li>
                            <li><strong>Microsoft Edge</strong>: 設定 → Cookie と サイトのアクセス許可</li>
                        </ul>
                        
                        <h3>Google Analyticsのオプトアウト</h3>
                        <p>Googleが提供するオプトアウトアドオンをインストールすることで、Google Analyticsによるデータ収集を停止することができます。詳細は<a href="https://tools.google.com/dlpage/gaoptout" target="_blank">こちら</a>をご覧ください。</p>
                        
                        <h3>メール配信の停止</h3>
                        <p>メールマガジンやお知らせメールの配信停止は、メール内に記載されている配信停止リンクをクリックするか、お問い合わせフォームからご連絡ください。</p>
                    </section>
                    
                    <!-- 問い合わせ先 -->
                    <section id="contact">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">問い合わせ先
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">全サービス共通</span>
                        <p>【運営者】AquaBit LAB/三浦 海の学校<br>
                        【運営責任者】吉田 哲司<br>
                        【メール】<a href="mailto:info@miura-diving.com">info@miura-diving.com</a><br>
                        【住所】神奈川県三浦市三崎町諸磯1621</p>
                    </section>
                    
                    <!-- データ保存期間 -->
                    <section id="data-retention">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">データを保存する期間
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">全サービス共通</span>
                        <p>あなたがコメントを残すと、コメントとそのメタデータが無期限に保持されます。これは、モデレーションキューにコメントを保持しておく代わりに、フォローアップのコメントを自動的に認識し承認できるようにするためです。</p>
                        <p>このサイトに登録したユーザーがいる場合、その方がユーザープロフィールページで提供した個人情報を保存します。すべてのユーザーは自分の個人情報を表示、編集、削除することができます (ただしユーザー名は変更することができません)。サイト管理者もそれらの情報を表示、編集できます。</p>
                    </section>
                    
                    <!-- ユーザーの権利 -->
                    <section id="your-rights">
                        <h2 style="position: relative; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1d1d1f;">データに対するあなたの権利
                        <span style="position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background-color: #0066cc;"></span>
                    </h2>
                        <span style="display: inline-block; background-color: #f0f0f2; color: #424245; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.9rem; margin-right: 0.5rem; margin-bottom: 1rem; border: 1px solid #e5e5e7;">全サービス共通</span>
                        <p>このサイトのアカウントを持っているか、サイトにコメントを残したことがある場合、私たちが保持するあなたについての個人データ (提供したすべてのデータを含む) をエクスポートファイルとして受け取るリクエストを行うことができます。また、個人データの消去リクエストを行うこともできます。これには、管理、法律、セキュリティ目的のために保持する義務があるデータは含まれません。</p>
                    </section>
                </div>
            </article>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href') === "#" ? "top" : this.getAttribute('href');
            let targetElement;
            
            if (targetId === "top") {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            } else {
                targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
</script>

<?php get_footer(); ?>