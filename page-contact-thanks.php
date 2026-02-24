<?php
/**
 * Template Name: Contact Thanks
 * お問い合わせ完了ページテンプレート
 */

get_header(); ?>

<style>
/* Contact Thanks Styles */
.thanks {
    max-width: 600px;
    margin: 3rem auto;
    padding: 0 1rem;
    text-align: center;
}

.thanks__icon {
    font-size: 4rem;
    color: #27ae60;
    margin-bottom: 1.5rem;
}

.thanks__title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1.5rem;
}

.thanks__message {
    font-size: 1.1rem;
    color: #666;
    line-height: 1.7;
    margin-bottom: 2rem;
}

.thanks__info {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 3rem;
    border-left: 4px solid #3498db;
}

.thanks__info h3 {
    font-size: 1.3rem;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.thanks__info p {
    color: #666;
    margin-bottom: 0.5rem;
}

.thanks__buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-block;
    padding: 1rem 2rem;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    min-width: 180px;
}

.btn--primary {
    background: #3498db;
    color: white;
}

.btn--primary:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
    color: white;
}

.btn--secondary {
    background: #27ae60;
    color: white;
}

.btn--secondary:hover {
    background: #229954;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
    color: white;
}

.contact-urgency {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 2rem;
}

.contact-urgency h4 {
    margin-bottom: 0.5rem;
    color: #856404;
}

.contact-urgency a {
    color: #856404;
    font-weight: 600;
}

@media (max-width: 768px) {
    .thanks {
        margin: 2rem auto;
        padding: 0 0.5rem;
    }
    
    .thanks__title {
        font-size: 1.6rem;
    }
    
    .thanks__icon {
        font-size: 3rem;
    }
    
    .thanks__buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
    }
    
    .thanks__info {
        padding: 1.5rem;
    }
}
</style>

<main class="thanks" role="main">
    <div class="thanks__icon" aria-hidden="true">✓</div>
    
    <h1 class="thanks__title">お問い合わせありがとうございます</h1>
    
    <p class="thanks__message">
        この度は三浦 海の学校へお問い合わせいただき、誠にありがとうございます。<br>
        お送りいただいた内容を確認の上、2営業日以内にご返信いたします。
    </p>

    <div class="thanks__info">
        <h3>今後の流れ</h3>
        <p><strong>1.</strong> 自動返信メールをお送りしました（迷惑メールフォルダもご確認ください）</p>
        <p><strong>2.</strong> スタッフが内容を確認いたします</p>
        <p><strong>3.</strong> 2営業日以内に詳細なご回答をお送りします</p>
    </div>

    <div class="thanks__buttons">
        <a href="<?php echo home_url('/'); ?>" class="btn btn--primary">
            トップページに戻る
        </a>
        <a href="<?php echo home_url('/diving-license/'); ?>" class="btn btn--secondary">
            ライセンス講習を見る
        </a>
    </div>

    <div class="contact-urgency">
        <h4>お急ぎの場合</h4>
        <p>
            緊急のお問い合わせや当日のご予約については、<br>
            お電話 <a href="tel:046-880-0835">046-880-0835</a> または 
            <a href="https://lin.ee/kK3d5p2" target="_blank" rel="noopener">LINE</a> でお気軽にご連絡ください。
        </p>
        <p><small>営業時間: 9:00〜16:00（不定休）</small></p>
    </div>
</main>

<script>
// ページ読み込み時にスクロール位置をトップに
window.addEventListener('load', function() {
    window.scrollTo(0, 0);
});

// 戻るボタンで直接アクセスされた場合の処理
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>

<?php get_footer(); ?>