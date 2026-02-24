/**
 * カスタムJavaScript - ハンバーガーメニュー・スクロール効果・アクセシビリティ対応
 */
jQuery(document).ready(function($) {
  
  // ==========================================
  // ハンバーガーメニューの開閉制御
  // ==========================================
  const burger = $('#burger');
  const nav = document.querySelector('#mobileNav');
  const closeBtn = $('.close-btn');
  
  // メニューを開く
  function openMobileMenu() {
    burger.addClass('active').attr('aria-label', 'メニューを閉じる');
    nav.removeAttribute('hidden');
    nav.classList.remove('translate-x-full');
    $('body').addClass('menu-open');
    nav.querySelector('.close-btn').focus();
  }
  
  // メニューを閉じる
  function closeMobileMenu() {
    burger.removeClass('active').attr('aria-label', 'メニューを開く');
    nav.classList.add('translate-x-full');
    setTimeout(() => nav.setAttribute('hidden', ''), 300);
    $('body').removeClass('menu-open');
    burger.focus();
  }
  
  // イベントリスナー
  burger.get(0).addEventListener('click', () => {
    if (nav.hasAttribute('hidden')) {
      openMobileMenu();
    } else {
      closeMobileMenu();
    }
  });
  
  nav.addEventListener('click', e => {
    if (e.target === nav) closeMobileMenu();
  });
  
  // キーボードアクセシビリティ対応
  burger.on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      $(this).click();
    }
  });
  
  closeBtn.on('click', closeMobileMenu);
  closeBtn.on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      closeMobileMenu();
    }
  });
  
  // ESCキーでメニューを閉じる
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape' && !nav.hasAttribute('hidden')) {
      closeMobileMenu();
    }
  });
  
  // メニューリンククリック時にメニューを閉じる
  $('.mobile-nav-menu a').on('click', function() {
    closeMobileMenu();
  });
  
  // ==========================================
  // ヘッダーのスクロール効果
  // ==========================================
  const header = $('.modern-header');
  let lastScrollTop = 0;
  
  $(window).on('scroll', function() {
    const scrollTop = $(this).scrollTop();
    
    // スクロール時にシャドウを追加
    if (scrollTop > 10) {
      header.addClass('shadow-md');
    } else {
      header.removeClass('shadow-md');
    }
    
    lastScrollTop = scrollTop;
  });
  
  // ==========================================
  // スムーススクロール
  // ==========================================
  $('a[href^="#"], .scroll-down').on('click', function(e) {
    e.preventDefault();
    
    let target;
    if ($(this).hasClass('scroll-down')) {
      target = $('#next');
    } else {
      const href = this.getAttribute('href');
      target = $(href);
    }
    
    if (target.length) {
      const headerHeight = $('.modern-header').outerHeight() || 64;
      const targetPosition = target.offset().top - headerHeight;
      
      $('html, body').animate({
        scrollTop: targetPosition
      }, 800, 'easeInOutCubic');
    }
  });
  
  // ==========================================
  // アクセシビリティ強化
  // ==========================================
  
  // フォーカス可能な要素にtabindex="0"を追加（必要に応じて）
  $('.btn-primary, .scroll-down').each(function() {
    if (!$(this).attr('tabindex') && !$(this).is('a, button, input, select, textarea')) {
      $(this).attr('tabindex', '0').attr('role', 'button');
    }
  });
  
  // Enterキーでボタン操作
  $('[role="button"]').on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      $(this).trigger('click');
    }
  });
  
  // ==========================================
  // パフォーマンス最適化
  // ==========================================
  
  // 画像の遅延読み込み（Intersection Observer対応）
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
            img.classList.remove('lazy');
            observer.unobserve(img);
          }
        }
      });
    });
    
    // data-src属性を持つ画像を監視
    $('img[data-src]').each(function() {
      imageObserver.observe(this);
    });
  }
  
  // ==========================================
  // スクロールアニメーション
  // ==========================================
  
  function animateOnScroll() {
    $('.animate-on-scroll').each(function() {
      const element = $(this);
      const elementTop = element.offset().top;
      const elementBottom = elementTop + element.outerHeight();
      const viewportTop = $(window).scrollTop();
      const viewportBottom = viewportTop + $(window).height();
      
      if (elementBottom > viewportTop && elementTop < viewportBottom) {
        element.addClass('animated');
      }
    });
  }
  
  // 初期実行とスクロール時実行
  animateOnScroll();
  $(window).on('scroll', throttle(animateOnScroll, 100));
  
  // ==========================================
  // ユーティリティ関数
  // ==========================================
  
  // スロットル関数（パフォーマンス最適化用）
  function throttle(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
  
  // カスタムイージング関数
  $.easing.easeInOutCubic = function (x, t, b, c, d) {
    if ((t/=d/2) < 1) return c/2*t*t*t + b;
    return c/2*((t-=2)*t*t + 2) + b;
  };
  
  // ==========================================
  // 初期化完了処理
  // ==========================================
  
  // ページ読み込み完了時
  $(window).on('load', function() {
    // ローディングアニメーション終了
    $('body').addClass('loaded');
    
    // パフォーマンス測定（開発時のみ）
    if (window.performance) {
      const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
      console.log('Page load time: ' + loadTime + 'ms');
    }
  });
  
  // リサイズ時の処理
  $(window).on('resize', throttle(function() {
    // モバイルビューポート調整
    if ($(window).width() >= 768 && mobileNav.open) {
      closeMobileMenu();
    }
  }, 250));
  
});

// ==========================================
// ページ外部からのアクセス用グローバル関数
// ==========================================

// メニュー制御をグローバルに公開
window.MobileMenu = {
  open: function() {
    jQuery('#burger').click();
  },
  close: function() {
    const mobileNav = document.getElementById('mobileNav');
    if (mobileNav && mobileNav.open) {
      jQuery('.close-btn').click();
    }
  }
};

// スムーススクロール用グローバル関数
window.scrollToElement = function(selector) {
  const target = jQuery(selector);
  if (target.length) {
    const headerHeight = jQuery('.modern-header').outerHeight() || 64;
    jQuery('html, body').animate({
      scrollTop: target.offset().top - headerHeight
    }, 800);
  }
};