<?php get_header(); ?>

<style>
.maintenance-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #0074E4 0%, #00C6FA 100%);
  color: white;
  text-align: center;
  padding: 2rem;
}

.maintenance-content {
  max-width: 600px;
  margin: 0 auto;
}

.maintenance-logo {
  max-width: 300px;
  width: 100%;
  height: auto;
  margin-bottom: 2rem;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.maintenance-title {
  font-size: clamp(1.8rem, 4vw, 2.5rem);
  font-weight: 700;
  margin-bottom: 1rem;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
}

.maintenance-subtitle {
  font-size: clamp(1.1rem, 2.5vw, 1.4rem);
  margin-bottom: 2rem;
  opacity: 0.9;
  line-height: 1.6;
}

.maintenance-contact {
  display: inline-block;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  padding: 1rem 2rem;
  border: 2px solid rgba(255, 255, 255, 0.7);
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  backdrop-filter: blur(8px);
}

.maintenance-contact:hover {
  background: rgba(255, 255, 255, 0.3);
  border-color: white;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
  .maintenance-page {
    padding: 1rem;
  }
  
  .maintenance-logo {
    max-width: 250px;
    margin-bottom: 1.5rem;
  }
  
  .maintenance-contact {
    padding: 0.8rem 1.5rem;
    font-size: 1rem;
  }
}
</style>

<div class="maintenance-page">
  <div class="maintenance-content">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/image/miura-hero.png" 
         alt="三浦 海の学校" 
         class="maintenance-logo"
         loading="eager">
    
    <h1 class="maintenance-title">ただいまメンテナンス中</h1>
    
    <p class="maintenance-subtitle">
      サイトの改善作業を行っております。<br>
      ご迷惑をおかけして申し訳ございません。<br>
      お急ぎの方は下記よりお問い合わせください。
    </p>
    
    <a href="mailto:info@miura-diving.com" class="maintenance-contact">
      📧 お問い合わせはこちら
    </a>
    
    <div style="margin-top: 2rem; font-size: 0.9rem; opacity: 0.7;">
      <p>三浦 海の学校 - PADI公認ダイビングスクール</p>
      <p>TEL: 046-880-0835</p>
    </div>
  </div>
</div>

<?php get_footer(); ?>