<?php
/**
 * Template Name: 海の精霊シエルによるAI占い
 */
get_header(); ?>

<div id="content" class="content article">
    <div class="content-in">
        <main id="main" class="main">
            <article>
                <header class="article-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                    
                    <div class="fortune-container">
                        <div class="ocean-bg"></div>
                        <div class="fortune-intro">
                            <img src="https://miura-diving.com/wp-content/uploads/海の精霊シエル.png" alt="海の精霊シエル" class="siel-image">
                            <div class="siel-bubble">
                                <p>こんにちは、私は海の精霊シエルよ。<br>あなたの今日の運勢を海の声から聞き取ってお伝えするわ。<br>お名前と生年月日を教えてくれるかしら？<br>血液型や星座もわかれば教えてね。</p>
                            </div>
                        </div>
                        <!-- プライバシー情報の説明 - fortuneIntroとfortuneFormの間に挿入 -->
<div class="privacy-info">
    <div class="privacy-box">
        <h4><i class="fas fa-shield-alt"></i> 個人情報の取り扱いについて</h4>
        <p>この占いに入力された情報は、占い結果の生成にのみ使用され、保存されません。また、お名前はニックネームで構いません。</p>
        <p class="privacy-link"><a href="https://miura-diving.com/privacy-policy/" target="_blank"><i class="fas fa-external-link-alt"></i> プライバシーポリシーの詳細はこちら</a></p>
    </div>
</div>
                        <div id="fortune-form-container">
                            <form id="fortune-form" class="fortune-form">
                                <div class="form-group">
                                    <label for="user-name">お名前</label>
                                    <input type="text" id="user-name" name="user-name" required>
                                </div>
                                <div class="form-group">
                                    <label for="user-birthdate">生年月日</label>
                                    <input type="date" id="user-birthdate" name="user-birthdate" required>
                                </div>
                                <div class="form-group">
                                    <label for="user-bloodtype">血液型 (任意)</label>
                                    <select id="user-bloodtype" name="user-bloodtype">
                                        <option value="">選択してください</option>
                                        <option value="A">A型</option>
                                        <option value="B">B型</option>
                                        <option value="O">O型</option>
                                        <option value="AB">AB型</option>
                                        <option value="不明">わからない</option>
                                    </select>
                                </div>
                                <!-- 星座の入力欄を追加 -->
                                <div class="form-group">
                                    <label for="user-zodiac">星座 (任意)</label>
                                    <select id="user-zodiac" name="user-zodiac">
                                        <option value="">選択してください</option>
                                        <option value="おひつじ座">おひつじ座 (3/21-4/19)</option>
                                        <option value="おうし座">おうし座 (4/20-5/20)</option>
                                        <option value="ふたご座">ふたご座 (5/21-6/21)</option>
                                        <option value="かに座">かに座 (6/22-7/22)</option>
                                        <option value="しし座">しし座 (7/23-8/22)</option>
                                        <option value="おとめ座">おとめ座 (8/23-9/22)</option>
                                        <option value="てんびん座">てんびん座 (9/23-10/23)</option>
                                        <option value="さそり座">さそり座 (10/24-11/22)</option>
                                        <option value="いて座">いて座 (11/23-12/21)</option>
                                        <option value="やぎ座">やぎ座 (12/22-1/19)</option>
                                        <option value="みずがめ座">みずがめ座 (1/20-2/18)</option>
                                        <option value="うお座">うお座 (2/19-3/20)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="submit-button">
                                        <span class="button-text">占ってもらう</span>
                                        <div class="button-wave"></div>
                                    </button>
                                </div>
                                <div class="form-note">
                                    <p>※無料占いは1日1回までとなります</p>
                                </div>
                            </form>
                        </div>
                        
                        <div id="fortune-result" class="fortune-result"></div>
                        
                        <!-- SNSシェアボタン（占い結果が表示された時のみ表示） -->
                        <div id="share-container" class="share-container" style="display: none;">
                            <h3>占い結果をシェアする</h3>
                            <div class="share-buttons">
                                <button id="share-x" class="share-button share-x">
                                    <i class="fa fa-x"></i> X（旧Twitter）でシェア
                                </button>
                                <button id="share-facebook" class="share-button share-facebook">
                                    <i class="fa fa-facebook"></i> Facebookでシェア
                                </button>
                                <button id="share-instagram" class="share-button share-instagram">
                                    <i class="fa fa-instagram"></i> Instagram用に画像保存
                                </button>
                                <button id="share-line" class="share-button share-line">
                                    <i class="fa fa-line"></i> LINEでシェア
                                </button>
                            </div>
                        </div>
                        
                        <!-- 星座情報表示コンテナ -->
                        <div id="zodiac-info-container" class="zodiac-info-container" style="display: none;"></div>
                    </div>
                </div>
            </article>
        </main>
    </div>
</div>

<style>
.fortune-container {
    max-width: 800px;
    margin: 0 auto;
    font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif;
    position: relative;
    padding: 30px 20px;
    overflow: hidden;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.ocean-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(225, 245, 254, 0.3) 0%, rgba(179, 229, 252, 0.1) 50%, rgba(225, 245, 254, 0.3) 100%);
    z-index: -1;
    animation: oceanWaves 15s infinite linear;
}

@keyframes oceanWaves {
    0% {
        background-position: 0% 0%;
    }
    100% {
        background-position: 100% 100%;
    }
}

.fortune-intro {
    display: flex;
    align-items: flex-start;
    margin-bottom: 30px;
}

.siel-image {
    width: 120px;
    height: auto;
    margin-right: 20px;
    border-radius: 50%;
    border: 4px solid #8ed0f9;
    box-shadow: 0 0 15px rgba(142, 208, 249, 0.6);
    transition: all 0.3s ease;
    animation: floatingAvatar 3s ease-in-out infinite;
}

@keyframes floatingAvatar {
    0% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0); }
}

.siel-bubble {
    position: relative;
    background: linear-gradient(135deg, #e1f5fe, #bbdefb);
    border-radius: 15px;
    padding: 15px 20px;
    max-width: 70%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    border-left: 3px solid #4fc3f7;
}

.siel-bubble:before {
    content: '';
    position: absolute;
    left: -15px;
    top: 20px;
    border-width: 8px;
    border-style: solid;
    border-color: transparent #e1f5fe transparent transparent;
}

.fortune-form {
    background: linear-gradient(to bottom, #f5f5f5, #e3f2fd);
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 30px;
    border: 1px solid #bbdefb;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #1976d2;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #bbdefb;
    border-radius: 5px;
    font-size: 16px;
    background-color: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
}

.form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #4fc3f7;
    box-shadow: 0 0 5px rgba(79, 195, 247, 0.5);
}

.form-group button {
    position: relative;
    overflow: hidden;
    background: linear-gradient(to right, #4db6ac, #26a69a);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 3px 5px rgba(38, 166, 154, 0.3);
    width: 100%;
    max-width: 200px;
    display: block;
    margin: 0 auto;
}

.form-group button:hover {
    background: linear-gradient(to right, #26a69a, #00897b);
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(38, 166, 154, 0.4);
}

.button-wave {
    position: absolute;
    top: 0;
    left: -100%;
    width: 200%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
}

.form-group button:hover .button-wave {
    left: 100%;
}

.form-note {
    margin-top: 10px;
    font-size: 14px;
    color: #757575;
    text-align: center;
}

.fortune-result {
    margin-top: 30px;
}

.message {
    display: flex;
    margin-bottom: 20px;
    animation: messageAppear 0.6s ease forwards;
}

@keyframes messageAppear {
    from {
        opacity: 0;
        transform: translateY(15px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.message.user {
    justify-content: flex-end;
}

.message.siel {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 70%;
    padding: 15px;
    border-radius: 15px;
}

.message.user .message-bubble {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-bottom-right-radius: 0;
    border-right: 3px solid #81c784;
}

.message.siel .message-bubble {
    background: linear-gradient(135deg, #e1f5fe, #bbdefb);
    border-bottom-left-radius: 0;
    border-left: 3px solid #4fc3f7;
}

.message p {
    margin: 0 0 10px;
    line-height: 1.6;
}

.message p:last-child {
    margin-bottom: 0;
}

.loading {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.loading-dots {
    display: flex;
    justify-content: center;
    align-items: center;
}

.loading-dots span {
    width: 10px;
    height: 10px;
    margin: 0 5px;
    background: #4db6ac;
    border-radius: 50%;
    animation: dots 1.5s infinite ease-in-out;
}

.loading-dots span:nth-child(2) {
    animation-delay: 0.5s;
}

.loading-dots span:nth-child(3) {
    animation-delay: 1s;
}

@keyframes dots {
    0%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.2);
        opacity: 1;
    }
}

.error-message {
    color: #d32f2f;
    background: #ffebee;
    padding: 15px;
    border-radius: 5px;
    margin-top: 20px;
    text-align: center;
}

.fortune-item {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px dashed #bbdefb;
}

.fortune-item:last-child {
    border-bottom: none;
}

.fortune-item-title {
    font-weight: bold;
    color: #0288d1;
    margin-bottom: 5px;
}

.fortune-stars {
    color: #ffc107;
    letter-spacing: 2px;
}

/* シェアボタンのスタイル */
.share-container {
    margin-top: 20px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-top: 10px;
}

.share-button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: opacity 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
}

.share-button:hover {
    opacity: 0.8;
}

.share-button:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

.share-button:focus:not(:active)::after {
    animation: ripple 1s ease-out;
}

@keyframes ripple {
    0% {
        transform: scale(0, 0);
        opacity: 0.5;
    }
    20% {
        transform: scale(25, 25);
        opacity: 0.3;
    }
    100% {
        opacity: 0;
        transform: scale(40, 40);
    }
}

.share-x {
    background-color: #000;
}

.share-facebook {
    background-color: #3b5998;
}

.share-instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}

.share-line {
    background-color: #00b900;
}

/* 星座情報のスタイル */
.zodiac-info-container {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 10px;
    padding: 15px;
    margin: 15px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease-out;
}

.zodiac-sea-info h4 {
    color: #1e88e5;
    margin-top: 0;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 8px;
}

.zodiac-sea-info p {
    line-height: 1.6;
    margin-bottom: 12px;
}

.zodiac-sea-info ul {
    list-style: none;
    padding-left: 0;
}

.zodiac-sea-info ul li {
    padding: 4px 0;
    border-bottom: 1px dashed #e0e0e0;
}

.zodiac-sea-info ul li:last-child {
    border-bottom: none;
}

/* 結果表示のアニメーション */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* モバイル対応 */
@media (max-width: 767px) {
    .fortune-intro {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .siel-image {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .siel-bubble {
        max-width: 90%;
    }
    
    .siel-bubble:before {
        left: 50%;
        top: -15px;
        transform: translateX(-50%);
        border-color: transparent transparent #e1f5fe transparent;
    }
    
    .message-bubble {
        max-width: 85%;
    }
    
    .fortune-container {
        padding: 20px 15px;
    }
    
    /* シェアボタンのモバイル対応 */
    .share-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .share-button {
        width: 100%;
        justify-content: center;
        padding: 12px 15px;
        margin-bottom: 8px;
    }
}

/* 小さいスマートフォン向け */
@media screen and (max-width: 480px) {
    .fortune-container {
        padding: 10px;
    }
    
    h2 {
        font-size: 20px;
    }
    
    h3 {
        font-size: 16px;
    }
    
    .siel-image {
        max-width: 100px;
    }
}

/* 結果の特別スタイル */
.highlight-text {
    font-weight: bold;
    color: #0288d1;
}

.ocean-emoji {
    font-size: 1.2em;
    margin-right: 5px;
}

.fortune-message {
    font-style: italic;
    color: #01579b;
    padding: 10px;
    background-color: rgba(225, 245, 254, 0.5);
    border-radius: 8px;
    margin-top: 10px;
}
.message p {
    margin: 0 0 10px;
    line-height: 1.6;
}

.message p:last-child {
    margin-bottom: 0;
}
/* プライバシー情報のスタイル - styleタグ内に追加 */
.privacy-info {
    margin: 20px 0;
}

.privacy-box {
    background: linear-gradient(135deg, #f5f9ff 0%, #dff1ff 100%);
    border-left: 4px solid #4fc3f7;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.privacy-box h4 {
    color: #0288d1;
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.privacy-box p {
    margin: 10px 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

.privacy-link {
    text-align: right;
    margin-top: 12px;
    margin-bottom: 0 !important;
}

.privacy-link a {
    color: #0288d1;
    text-decoration: none;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.privacy-link a:hover {
    color: #01579b;
    text-decoration: underline;
}

@media (max-width: 767px) {
    .privacy-box {
        padding: 12px;
    }
}
</style>

<!-- FontAwesomeの追加 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- html2canvas ライブラリの追加 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// ajax_object のフォールバック対策
if (typeof ajax_object === 'undefined' || !ajax_object.ajaxurl) {
    console.warn('ajax_object が見つかりません。フォールバックを設定します。');
    window.ajax_object = {
        ajaxurl: '<?php echo esc_url(admin_url("admin-ajax.php")); ?>'
    };
}

document.addEventListener('DOMContentLoaded', function() {
    const fortuneForm = document.getElementById('fortune-form');
    const fortuneResult = document.getElementById('fortune-result');
    const submitButton = document.getElementById('submit-button');
    const shareContainer = document.getElementById('share-container');
    
    // デバッグ用：ajaxurlが正しく設定されているか確認
    console.log('Ajax URL:', typeof ajax_object !== 'undefined' ? ajax_object.ajaxurl : 'ajax_object undefined');
    
    // localStorageから最後の占い日を取得
    const lastFortuneDate = localStorage.getItem('lastFortuneDate');
    const today = new Date().toDateString();
    
    // 生年月日から星座を自動選択する関数
    function getZodiacSign(date) {
        const birthDate = new Date(date);
        const month = birthDate.getMonth() + 1; // JavaScriptの月は0から始まる
        const day = birthDate.getDate();
        
        // 星座の判定
        if ((month === 3 && day >= 21) || (month === 4 && day <= 19)) return "おひつじ座";
        if ((month === 4 && day >= 20) || (month === 5 && day <= 20)) return "おうし座";
        if ((month === 5 && day >= 21) || (month === 6 && day <= 21)) return "ふたご座";
        if ((month === 6 && day >= 22) || (month === 7 && day <= 22)) return "かに座";
        if ((month === 7 && day >= 23) || (month === 8 && day <= 22)) return "しし座";
        if ((month === 8 && day >= 23) || (month === 9 && day <= 22)) return "おとめ座";
        if ((month === 9 && day >= 23) || (month === 10 && day <= 23)) return "てんびん座";
        if ((month === 10 && day >= 24) || (month === 11 && day <= 22)) return "さそり座";
        if ((month === 11 && day >= 23) || (month === 12 && day <= 21)) return "いて座";
        if ((month === 12 && day >= 22) || (month === 1 && day <= 19)) return "やぎ座";
        if ((month === 1 && day >= 20) || (month === 2 && day <= 18)) return "みずがめ座";
        if ((month === 2 && day >= 19) || (month === 3 && day <= 20)) return "うお座";
        
        return ""; // 該当なし（エラー）
    }
    
    // 生年月日入力時に星座を自動選択
    document.getElementById('user-birthdate').addEventListener('change', function() {
        const birthdate = this.value;
        if (birthdate) {
            const zodiacSign = getZodiacSign(birthdate);
            document.getElementById('user-zodiac').value = zodiacSign;
        }
    });
    
    // URLパラメータから値を取得して自動入力
    const urlParams = new URLSearchParams(window.location.search);
    
    // 名前の取得と設定
    const name = urlParams.get('user-name');
    if (name) {
        document.getElementById('user-name').value = decodeURIComponent(name);
    }
    
    // 生年月日の取得と設定
    const birthdate = urlParams.get('user-birthdate');
    if (birthdate) {
        document.getElementById('user-birthdate').value = birthdate;
    }
    
    // 血液型の取得と設定
    const bloodType = urlParams.get('user-bloodtype');
    if (bloodType) {
        document.getElementById('user-bloodtype').value = bloodType;
    }
    
    // 星座の取得と設定
    const zodiac = urlParams.get('user-zodiac');
    if (zodiac) {
        document.getElementById('user-zodiac').value = decodeURIComponent(zodiac);
    } else if (birthdate) {
        // 生年月日から星座を自動選択
        const zodiacSign = getZodiacSign(birthdate);
        document.getElementById('user-zodiac').value = zodiacSign;
    }

    // 今日すでに占いを行っているかチェック
    if (lastFortuneDate === today) {
        const lastFortune = localStorage.getItem('lastFortune');
        if (lastFortune) {
            fortuneForm.style.display = 'none';
            fortuneResult.innerHTML = '<div class="message siel"><div class="message-bubble">' +
                '<p>本日の占いの結果よ：</p>' +
                lastFortune +
                '</div></div>';
            
            // 注意メッセージを追加
            fortuneResult.innerHTML += '<div class="form-note" style="text-align: center; margin-top: 20px;">' +
                '<p>※無料占いは1日1回までとなります。また明日お越しくださいね。</p>' +
                '</div>';
            
            // シェアボタンを表示
            shareContainer.style.display = 'block';
        }
    }
    
    
    // 星座に関連した海の情報
    const zodiacSeaInfo = {
        'おひつじ座': {
            element: '火',
            seaCreature: 'イルカ',
            description: '情熱的で活発なおひつじ座は、活発に泳ぎ回るイルカと相性が良いでしょう。海では思い切り体を動かすアクティビティが運気を高めます。',
            luckyBeach: '開放的な砂浜',
            waterType: '温かな海'
        },
        'おうし座': {
            element: '地',
            seaCreature: 'ウミガメ',
            description: '忍耐強く堅実なおうし座は、ゆっくりと確実に泳ぐウミガメと相性が良いでしょう。海ではのんびりと過ごすことで心が満たされます。',
            luckyBeach: '岩場の多い海岸',
            waterType: '穏やかな入り江'
        },
        'ふたご座': {
            element: '風',
            seaCreature: 'カニ',
            description: '好奇心旺盛で多才なふたご座は、左右の大きな鋏を持つカニと相性が良いでしょう。海では新しい発見を求める探索が運気を高めます。',
            luckyBeach: '変化に富んだ海岸線',
            waterType: '浅瀬と深場が交互にある海'
        },
        'かに座': {
            element: '水',
            seaCreature: 'タコ',
            description: '感受性豊かで家庭的なかに座は、柔軟で適応力の高いタコと相性が良いでしょう。海では静かに水中世界を眺めることで心が安らぎます。',
            luckyBeach: '入り江の静かな浜',
            waterType: '透明度の高い海'
        },
        'しし座': {
            element: '火',
            seaCreature: 'マンタ',
            description: '堂々として威厳のあるしし座は、優雅に泳ぐ大きなマンタと相性が良いでしょう。海では主役になれるマリンアクティビティが運気を高めます。',
            luckyBeach: '広々とした開放的なビーチ',
            waterType: '青く澄んだ深い海'
        },
        'おとめ座': {
            element: '地',
            seaCreature: 'クラゲ',
            description: '几帳面で分析的なおとめ座は、繊細な構造を持つクラゲと相性が良いでしょう。海では細部まで観察する海洋生物の観察が心を落ち着かせます。',
            luckyBeach: '細かな貝殻が散らばる浜辺',
            waterType: '澄んだ浅い海'
        },
        'てんびん座': {
            element: '風',
            seaCreature: 'サンゴ',
            description: '調和と美を愛するてんびん座は、美しい群体を形成するサンゴと相性が良いでしょう。海では美しい景観を楽しむことで運気が高まります。',
            luckyBeach: '美しい景観の海岸',
            waterType: 'カラフルな生き物が多い海'
        },
        'さそり座': {
            element: '水',
            seaCreature: 'サメ',
            description: '情熱的で直感力の高いさそり座は、力強く神秘的なサメと相性が良いでしょう。海では深海の神秘を探ることで運気が高まります。',
            luckyBeach: '神秘的な雰囲気のある海岸',
            waterType: '深く濃い青色の海'
        },
        'いて座': {
            element: '火',
            seaCreature: 'クジラ',
            description: '自由を愛し冒険心あふれるいて座は、広大な海を旅するクジラと相性が良いでしょう。海では長距離を泳ぐような挑戦が運気を高めます。',
            luckyBeach: '開けた水平線が見える浜',
            waterType: '広大で開放的な海'
        },
        'やぎ座': {
            element: '地',
            seaCreature: 'フグ',
            description: '現実的で堅実なやぎ座は、独特の防御機能を持つフグと相性が良いでしょう。海では計画的な活動が安心感をもたらします。',
            luckyBeach: '岩礁のある海岸',
            waterType: '深く静かな海'
        },
        'みずがめ座': {
            element: '風',
            seaCreature: 'イカ',
            description: '独創的で革新的なみずがめ座は、知性の高いイカと相性が良いでしょう。海では従来とは違った楽しみ方が運気を高めます。',
            luckyBeach: 'ユニークな地形の海岸',
            waterType: '変化に富んだ海流がある海'
        },
        'うお座': {
            element: '水',
            seaCreature: 'クマノミ',
            description: '夢見がちで共感力の高いうお座は、イソギンチャクと共生するクマノミと相性が良いでしょう。海では瞑想的な時間を持つことで運気が高まります。',
            luckyBeach: '神秘的で静かな入り江',
            waterType: '幻想的な色合いの海'
        }
    };
    
    // 星座情報をHTML形式で表示するための関数
    function getZodiacSeaInfoHTML(zodiacSign) {
        const info = zodiacSeaInfo[zodiacSign];
        if (!info) return '';
        
        return `
        <div class="zodiac-sea-info">
            <h4>🌊 ${zodiacSign}と海の相性 🌊</h4>
            <p>${info.description}</p>
            <ul>
                <li><strong>元素:</strong> ${info.element}</li>
                <li><strong>相性の良い海の生き物:</strong> ${info.seaCreature}</li>
                <li><strong>ラッキービーチ:</strong> ${info.luckyBeach}</li>
                <li><strong>相性の良い海:</strong> ${info.waterType}</li>
            </ul>
        </div>`;
    }
    
    // 星座情報を表示する関数
    function displayZodiacSeaInfo() {
        const zodiacSign = document.getElementById('user-zodiac').value;
        const container = document.getElementById('zodiac-info-container');
        
        if (zodiacSign && container) {
            container.innerHTML = getZodiacSeaInfoHTML(zodiacSign);
            container.style.display = 'block';
        } else if (container) {
            container.style.display = 'none';
        }
    }
    
    // 星座選択変更時のイベント
    document.getElementById('user-zodiac').addEventListener('change', displayZodiacSeaInfo);
    
    // 初期表示（URLから読み込まれた場合）
    displayZodiacSeaInfo();
    
    // 占いフォームの送信処理（Ajax）
fortuneForm.addEventListener('submit', function(e) {
    e.preventDefault(); // ページリロードを防ぐ

    const name = document.getElementById('user-name').value;
    const birthdate = document.getElementById('user-birthdate').value;
    const bloodtype = document.getElementById('user-bloodtype').value;
    const zodiac = document.getElementById('user-zodiac').value;

    // 必須項目のバリデーション
    if (!name || !birthdate) {
        fortuneResult.innerHTML = '<div class="error-message">お名前と生年月日は必須です。</div>';
        return;
    }

    // ローディング表示
    fortuneResult.innerHTML = '<div class="message siel"><div class="message-bubble">シエルが海の声を聞いています...🔮</div></div>' +
        '<div class="loading"><div class="loading-dots"><span></span><span></span><span></span></div></div>';
    
    // デバッグ情報をコンソールに表示
    console.log('送信データ:', {name, birthdate, bloodtype, zodiac});
    console.log('送信先URL:', ajax_object.ajaxurl);

    // Ajaxリクエスト
    fetch(ajax_object.ajaxurl, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
        action: 'fortune_ajax',
        name: name,        // user-name から取得した値
        birthdate: birthdate,  // user-birthdate から取得した値
        bloodtype: bloodtype,  // user-bloodtype から取得した値
        zodiac: zodiac      // user-zodiac から取得した値
    })
})
    .then(response => {
        console.log('サーバーからの応答:', response);
        if (!response.ok) {
            throw new Error('サーバーエラー: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('処理結果:', data);
        if (data.success) {
            fortuneForm.style.display = 'none';
            fortuneResult.innerHTML = '<div class="message siel"><div class="message-bubble">' +
                '<p>本日の占いの結果よ：</p>' +
                data.data.fortune +
                '</div></div>';

            const today = new Date().toDateString();
            localStorage.setItem('lastFortuneDate', today);
            localStorage.setItem('lastFortune', data.data.fortune);

            // シェアボタン表示
            shareContainer.style.display = 'block';
        } else {
            fortuneResult.innerHTML = '<div class="error-message">エラー: ' + (data.data && data.data.message ? data.data.message : '不明なエラーが発生しました') + '</div>';
        }
    })
    .catch(error => {
        console.error('通信エラー:', error);
        fortuneResult.innerHTML = '<div class="error-message">通信エラーが発生しました。再度お試しください。<br>詳細: ' + error.message + '</div>';
    });
});
});

// SNSシェアボタンのイベントリスナーを設定
document.getElementById('share-x').addEventListener('click', function() {
    const text = encodeURIComponent('海の精霊シエルによる占い結果をシェアします！');
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
});

document.getElementById('share-facebook').addEventListener('click', function() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
});

document.getElementById('share-line').addEventListener('click', function() {
    const text = encodeURIComponent('海の精霊シエルによる占い結果をシェアします！');
    const url = encodeURIComponent(window.location.href);
    window.open(`https://social-plugins.line.me/lineit/share?text=${text}&url=${url}`, '_blank');
});

document.getElementById('share-instagram').addEventListener('click', function() {
    // Instagram用に画像キャプチャ処理
    try {
        // html2canvasが読み込まれているか確認
        if (typeof html2canvas === 'undefined') {
            alert('画像キャプチャ機能が読み込まれていません。ページを再読み込みしてください。');
            return;
        }
        
        // 対象要素を確実に取得
        const resultElement = document.querySelector('.message.siel');
        if (!resultElement) {
            alert('占い結果が見つかりません。');
            return;
        }
        
        alert('占い結果の画像を保存します。保存された画像をInstagramでシェアしてください。');
        
        // 画像キャプチャ処理
        html2canvas(resultElement, {
            useCORS: true,
            backgroundColor: '#f0f8ff', // 淡い青色の背景
            scale: 2 // 高解像度に
        }).then(function(canvas) {
            // キャンバスを画像として保存
            const link = document.createElement('a');
            link.download = '海の精霊シエル占い結果.png';
            link.href = canvas.toDataURL('image/png');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch(function(error) {
            console.error('画像キャプチャエラー:', error);
            alert('画像のキャプチャに失敗しました。');
        });
    } catch (error) {
        console.error('エラー:', error);
        alert('処理中にエラーが発生しました。');
    }
});
</script>