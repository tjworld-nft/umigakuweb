// ★FIX: dialog廃止、transform:translateX + body.lock実装
/**
 * Mobile Navigation & Body Lock Control
 */

function openMobileNav() {
  const nav = document.getElementById('mobileNav');
  const body = document.body;
  
  nav.classList.add('active');
  body.classList.add('lock');
  
  // フォーカストラップ
  nav.querySelector('.close-btn').focus();
}

function closeMobileNav() {
  const nav = document.getElementById('mobileNav');
  const body = document.body;
  
  nav.classList.remove('active');
  body.classList.remove('lock');
  
  // フォーカスをハンバーガーに戻す
  document.getElementById('burger').focus();
}

document.addEventListener('DOMContentLoaded', function() {
  const burger = document.getElementById('burger');
  const nav = document.getElementById('mobileNav');
  
  // ハンバーガークリック
  burger.addEventListener('click', function(e) {
    e.preventDefault();
    if (nav.classList.contains('active')) {
      closeMobileNav();
    } else {
      openMobileNav();
    }
  });
  
  // ESCキー
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && nav.classList.contains('active')) {
      closeMobileNav();
    }
  });
  
  // メニューリンククリック時に閉じる
  nav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', closeMobileNav);
  });
  
  // Testimonials Carousel (既存機能)
  const carousel = document.getElementById('testimonialsCarousel');
  if (carousel) {
    const slides = carousel.querySelectorAll('.testimonial-slide');
    const dots = carousel.querySelectorAll('.dot');
    const prevBtn = carousel.querySelector('.prev');
    const nextBtn = carousel.querySelector('.next');
    let currentSlide = 0;
    
    function showSlide(index) {
      slides.forEach(slide => slide.classList.remove('active'));
      dots.forEach(dot => dot.classList.remove('active'));
      
      slides[index].classList.add('active');
      dots[index].classList.add('active');
      currentSlide = index;
    }
    
    function nextSlide() {
      const next = (currentSlide + 1) % slides.length;
      showSlide(next);
    }
    
    function prevSlide() {
      const prev = (currentSlide - 1 + slides.length) % slides.length;
      showSlide(prev);
    }
    
    nextBtn?.addEventListener('click', nextSlide);
    prevBtn?.addEventListener('click', prevSlide);
    
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => showSlide(index));
    });
    
    // Auto-play carousel every 5 seconds
    setInterval(nextSlide, 5000);
  }
});