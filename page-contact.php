<?php
/**
 * Template Name: Contact Page
 * お問い合わせページテンプレート
 */

// セッション開始（エラーメッセージ表示用）
session_start();

// エラーメッセージと入力データの取得
$errors = $_SESSION['contact_errors'] ?? array();
$form_data = $_SESSION['contact_data'] ?? array();

// セッションクリア
unset($_SESSION['contact_errors']);
unset($_SESSION['contact_data']);

get_header(); ?>

<style>
/* Contact Form Styles */
.contact {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.contact__title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 2rem;
}

.contact__lead {
    text-align: center;
    color: #666;
    margin-bottom: 3rem;
    line-height: 1.7;
}

.contact__errors {
    background: #fee;
    border: 1px solid #f88;
    color: #c33;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.contact__errors ul {
    margin: 0;
    padding-left: 1.5rem;
}

.contact__form {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group--required .form-label::after {
    content: ' *';
    color: #e74c3c;
    font-weight: bold;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.form-submit {
    background: #3498db;
    color: white;
    border: none;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: block;
    margin: 2rem auto 0;
    min-width: 200px;
}

.form-submit:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
}

.form-note {
    font-size: 0.9rem;
    color: #666;
    text-align: center;
    margin-top: 1rem;
}

.contact-info {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    margin-top: 3rem;
    text-align: center;
}

.contact-info__title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.contact-info__list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.contact-info__item {
    margin-bottom: 0.5rem;
    color: #666;
}

.contact-info__item strong {
    color: #2c3e50;
}

@media (max-width: 768px) {
    .contact {
        margin: 1rem auto;
        padding: 0 0.5rem;
    }
    
    .contact__title {
        font-size: 1.6rem;
    }
    
    .contact__form {
        padding: 1.5rem;
    }
    
    .form-submit {
        width: 100%;
    }
}
</style>

<main class="contact" role="main">
    <h1 class="contact__title">お問い合わせ</h1>
    
    <p class="contact__lead">
        三浦 海の学校へのお問い合わせは、下記フォームよりお送りください。<br>
        2営業日以内にご返信いたします。お急ぎの場合はお電話でもお受けしております。
    </p>

    <?php if (!empty($errors)): ?>
    <div class="contact__errors" role="alert">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo esc_html($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form class="contact__form" method="POST" action="<?php echo get_stylesheet_directory_uri(); ?>/contact-handler.php" novalidate>
        <?php wp_nonce_field('umigaku_contact'); ?>
        
        <div class="form-group form-group--required">
            <label class="form-label" for="name_kanji">氏名（漢字）</label>
            <input 
                type="text" 
                id="name_kanji" 
                name="name_kanji" 
                class="form-input" 
                value="<?php echo esc_attr($form_data['name_kanji'] ?? ''); ?>"
                required
                aria-describedby="name_kanji_help"
            >
        </div>

        <div class="form-group form-group--required">
            <label class="form-label" for="name_romaji">氏名（ローマ字）</label>
            <input 
                type="text" 
                id="name_romaji" 
                name="name_romaji" 
                class="form-input" 
                value="<?php echo esc_attr($form_data['name_romaji'] ?? ''); ?>"
                required
                placeholder="Taro Yamada"
                aria-describedby="name_romaji_help"
            >
        </div>

        <div class="form-group form-group--required">
            <label class="form-label" for="email">メールアドレス</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input" 
                value="<?php echo esc_attr($form_data['email'] ?? ''); ?>"
                required
                placeholder="example@example.com"
                aria-describedby="email_help"
            >
        </div>

        <div class="form-group">
            <label class="form-label" for="preferred_date">ご希望日</label>
            <input 
                type="date" 
                id="preferred_date" 
                name="preferred_date" 
                class="form-input" 
                value="<?php echo esc_attr($form_data['preferred_date'] ?? ''); ?>"
                aria-describedby="preferred_date_help"
            >
        </div>

        <div class="form-group form-group--required">
            <label class="form-label" for="message">お問い合わせ内容</label>
            <textarea 
                id="message" 
                name="message" 
                class="form-textarea" 
                required
                placeholder="お問い合わせ内容をご記入ください。ダイビングコースや体験ダイビングについてなど、お気軽にご相談ください。"
                aria-describedby="message_help"
            ><?php echo esc_textarea($form_data['message'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="form-submit">
            送信する
        </button>

        <p class="form-note">
            <small>* 印は必須項目です。送信いただいた個人情報は、お問い合わせ対応以外の目的では使用いたしません。</small>
        </p>
    </form>

    <div class="contact-info">
        <h2 class="contact-info__title">その他のお問い合わせ方法</h2>
        <ul class="contact-info__list">
            <li class="contact-info__item"><strong>電話:</strong> 046-880-0835</li>
            <li class="contact-info__item"><strong>営業時間:</strong> 9:00〜16:00（不定休）</li>
            <li class="contact-info__item"><strong>所在地:</strong> 〒238-0224 神奈川県三浦市三崎町諸磯1621</li>
            <li class="contact-info__item">
                <a href="https://lin.ee/kK3d5p2" target="_blank" rel="noopener" style="color: #00c300; text-decoration: none; font-weight: 600;">
                    LINE でのお問い合わせも受け付けております
                </a>
            </li>
        </ul>
    </div>
</main>

<?php get_footer(); ?>