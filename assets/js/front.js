// ★IMPROVE v2: Polish UX最適化 - スクロールヘッダー・特徴グリッド展開・パフォーマンス向上

document.addEventListener('DOMContentLoaded', function() {
  
  /* ==========================================================================
     ★ v2: Scroll Header Control
     ========================================================================== */
  const siteHeader = document.querySelector('.site-header') || document.querySelector('header');
  let lastScrollY = window.scrollY;
  
  function handleScrollHeader() {
    const currentScrollY = window.scrollY;
    
    if (siteHeader) {
      if (currentScrollY > 80) {
        siteHeader.classList.add('is-scrolled');
      } else {
        siteHeader.classList.remove('is-scrolled');
      }
    }
    
    lastScrollY = currentScrollY;
  }
  
  // Throttled scroll handler
  let headerTicking = false;
  function throttledHeaderScroll() {
    if (!headerTicking) {
      requestAnimationFrame(() => {
        handleScrollHeader();
        headerTicking = false;
      });
      headerTicking = true;
    }
  }
  
  window.addEventListener('scroll', throttledHeaderScroll);
  
  /* ==========================================================================
     Mobile Navigation Control
     ========================================================================== */
  const burger = document.getElementById('burger');
  const mobileNav = document.getElementById('mobileNav');
  const body = document.body;
  
  if (burger && mobileNav) {
    // ハンバーガーメニュー開閉
    burger.addEventListener('click', function(e) {
      e.preventDefault();
      toggleMobileNav();
    });
    
    // オーバーレイクリックで閉じる
    const overlay = mobileNav.querySelector('.mobile-nav-overlay');
    if (overlay) {
      overlay.addEventListener('click', closeMobileNav);
    }
    
    // 閉じるボタン
    const closeBtn = mobileNav.querySelector('.close-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', closeMobileNav);
    }
    
    // ESCキーで閉じる
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
        closeMobileNav();
      }
    });
    
    // メニューリンククリック時に閉じる
    const menuLinks = mobileNav.querySelectorAll('a');
    menuLinks.forEach(link => {
      link.addEventListener('click', closeMobileNav);
    });
  }
  
  function toggleMobileNav() {
    if (mobileNav.classList.contains('active')) {
      closeMobileNav();
    } else {
      openMobileNav();
    }
  }
  
  function openMobileNav() {
    mobileNav.classList.add('active');
    body.classList.add('lock');
    burger.classList.add('active');
    
    // フォーカストラップ
    const closeBtn = mobileNav.querySelector('.close-btn');
    if (closeBtn) closeBtn.focus();
  }
  
  function closeMobileNav() {
    mobileNav.classList.remove('active');
    body.classList.remove('lock');
    burger.classList.remove('active');
    
    // フォーカスをハンバーガーに戻す
    if (burger) burger.focus();
  }
  
  /* ==========================================================================
     ★ v2: 特徴グリッド展開機能
     ========================================================================== */
  const toggleButton = document.getElementById('toggleFeatures');
  const pointsGrid = document.getElementById('pointsGrid');
  
  if (toggleButton && pointsGrid) {
    toggleButton.addEventListener('click', function() {
      pointsGrid.classList.toggle('open');
      
      if (pointsGrid.classList.contains('open')) {
        this.textContent = '折りたたむ';
      } else {
        this.textContent = 'もっと見る';
      }
    });
  }
  
  /* ==========================================================================
     Activity Slider (Simple Auto-scroll)
     ========================================================================== */
  const activitySlider = document.getElementById('activitySlider');
  
  if (activitySlider && window.innerWidth <= 767) {
    // モバイルのみ横スクロール対応
    activitySlider.style.display = 'flex';
    activitySlider.style.overflowX = 'auto';
    activitySlider.style.scrollSnapType = 'x mandatory';
    
    const slides = activitySlider.querySelectorAll('.activity-slide');
    slides.forEach(slide => {
      slide.style.minWidth = '280px';
      slide.style.scrollSnapAlign = 'start';
    });
  }
  
  /* ==========================================================================
     Sticky CTA Control
     ========================================================================== */
  const stickyCTA = document.querySelector('.sticky-cta');
  
  if (stickyCTA) {
    let lastScrollY = window.scrollY;
    let isScrollingDown = false;
    
    // 初期表示制御
    const heroSection = document.querySelector('.hero');
    const heroHeight = heroSection ? heroSection.offsetHeight : 600;
    
    function handleStickyScroll() {
      const currentScrollY = window.scrollY;
      isScrollingDown = currentScrollY > lastScrollY;
      
      // ヒーロー通過後に表示開始
      if (currentScrollY > heroHeight * 0.8) {
        stickyCTA.classList.add('show');
      } else {
        stickyCTA.classList.remove('show');
      }
      
      lastScrollY = currentScrollY;
    }
    
    // スクロールイベント（throttle付き）
    let ticking = false;
    function throttledScrollHandler() {
      if (!ticking) {
        requestAnimationFrame(() => {
          handleStickyScroll();
          ticking = false;
        });
        ticking = true;
      }
    }
    
    window.addEventListener('scroll', throttledScrollHandler);
  }
  
  /* ==========================================================================
     Smooth Scroll Enhancement
     ========================================================================== */
  const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
  
  smoothScrollLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      
      // 空のハッシュは無視
      if (href === '#' || href === '#!') return;
      
      const target = document.querySelector(href);
      
      if (target) {
        e.preventDefault();
        
        const headerHeight = 64; // --header-height
        const targetPosition = target.offsetTop - headerHeight;
        
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
        
        // アクセシビリティ: フォーカス移動
        target.setAttribute('tabindex', '-1');
        target.focus();
        
        // URL更新
        history.pushState(null, null, href);
      }
    });
  });
  
  /* ==========================================================================
     Scroll Animations (Intersection Observer)
     ========================================================================== */
  const observeElements = document.querySelectorAll([
    '.why-card',
    '.activity-slide', 
    '.point-card',
    '.step-item',
    '.course-block',
    '.blog-card'
  ].join(','));
  
  if (observeElements.length > 0) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    
    // 初期状態設定
    observeElements.forEach(element => {
      element.style.opacity = '0';
      element.style.transform = 'translateY(20px)';
      element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(element);
    });
  }
  
  /* ==========================================================================
     Course Comparison Hover Effect
     ========================================================================== */
  const courseBlocks = document.querySelectorAll('.course-block');
  
  courseBlocks.forEach(block => {
    block.addEventListener('mouseenter', function() {
      // 他のブロックを薄くする
      courseBlocks.forEach(otherBlock => {
        if (otherBlock !== this) {
          otherBlock.style.opacity = '0.7';
        }
      });
    });
    
    block.addEventListener('mouseleave', function() {
      // 全ブロックを元に戻す
      courseBlocks.forEach(otherBlock => {
        otherBlock.style.opacity = '1';
      });
    });
  });
  
  /* ==========================================================================
     Performance: Lazy Loading Images
     ========================================================================== */
  const lazyImages = document.querySelectorAll('img[loading="lazy"]');
  
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          
          // WebP フォールバック処理
          if (img.src.includes('.webp')) {
            const fallbackSrc = img.src.replace('.webp', '.jpg');
            
            img.onerror = function() {
              this.src = fallbackSrc;
            };
          }
          
          imageObserver.unobserve(img);
        }
      });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
  }
  
  /* ==========================================================================
     Contact Form Enhancement (if exists)
     ========================================================================== */
  const contactButtons = document.querySelectorAll('a[href^="tel:"], a[href^="mailto:"]');
  
  contactButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      // Analytics tracking (GA4 example)
      if (typeof gtag !== 'undefined') {
        const action = this.href.startsWith('tel:') ? 'phone_call' : 'email_click';
        gtag('event', action, {
          event_category: 'contact',
          event_label: this.href
        });
      }
    });
  });
  
  /* ==========================================================================
     ★ v2: Performance Metrics & Core Web Vitals
     ========================================================================== */
  if (console && console.log) {
    console.log('🌊 三浦 海の学校 v2 - Polish loaded successfully!');
    
    // Performance metrics
    window.addEventListener('load', function() {
      if ('performance' in window) {
        const loadTime = performance.now();
        const navigation = performance.getEntriesByType('navigation')[0];
        
        console.log(`⚡ Page loaded in ${Math.round(loadTime)}ms`);
        
        if (navigation) {
          console.log(`📊 LCP candidate: ${Math.round(navigation.loadEventEnd - navigation.loadEventStart)}ms`);
          console.log(`🎯 Target: Performance ≥85, Accessibility ≥90, CLS ≤0.1`);
        }
      }
    });
    
    // CLS monitoring
    if ('PerformanceObserver' in window) {
      try {
        const observer = new PerformanceObserver((list) => {
          let clsValue = 0;
          for (const entry of list.getEntries()) {
            if (!entry.hadRecentInput) {
              clsValue += entry.value;
            }
          }
          if (clsValue > 0.1) {
            console.warn(`⚠️ CLS above target: ${clsValue.toFixed(3)}`);
          }
        });
        observer.observe({ entryTypes: ['layout-shift'] });
      } catch (e) {
        // PerformanceObserver not supported
      }
    }
  }
  
});

/* ==========================================================================
   Export functions for external use
   ========================================================================== */
window.MiuraFront = {
  openMobileNav: function() {
    const event = new Event('click');
    const burger = document.getElementById('burger');
    if (burger) burger.dispatchEvent(event);
  },
  
  closeMobileNav: function() {
    const mobileNav = document.getElementById('mobileNav');
    if (mobileNav && mobileNav.classList.contains('active')) {
      const event = new Event('click');
      const burger = document.getElementById('burger');
      if (burger) burger.dispatchEvent(event);
    }
  },
  
  scrollToSection: function(sectionId) {
    const target = document.getElementById(sectionId);
    if (target) {
      const headerHeight = 64;
      const targetPosition = target.offsetTop - headerHeight;
      
      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    }
  }
};