// OWD講習詳細ページ用JavaScript
jQuery(document).ready(function($) {
  // スムーススクロール
  $('a[href^="#"]').on('click', function(e) {
    e.preventDefault();
    var target = $(this.getAttribute('href'));
    if (target.length) {
      $('html, body').stop().animate({
        scrollTop: target.offset().top - 80
      }, 800, 'easeInOutExpo');
    }
  });

  // FAQのトグル機能
  $('.owd-faq-question').on('click', function() {
    $(this).parent('.owd-faq-item').toggleClass('active');
    $(this).parent('.owd-faq-item').siblings().removeClass('active');
  });

  // 施設スライダー自動スクロール
  function autoScrollFacility() {
    var $slider = $('.owd-facility-slider');
    var scrollAmount = 0;
    var sliderWidth = $slider.width();
    var scrollSpeed = 2; // スクロール速度（小さいほど遅く）
    
    // マウスホバー時に停止
    $slider.hover(
      function() {
        clearInterval(scrollInterval);
      },
      function() {
        scrollInterval = setInterval(scrollFacilitySlider, 20);
      }
    );
    
    function scrollFacilitySlider() {
      scrollAmount += scrollSpeed;
      if (scrollAmount >= sliderWidth) {
        scrollAmount = 0;
      }
      $slider.scrollLeft(scrollAmount);
    }
    
    var scrollInterval = setInterval(scrollFacilitySlider, 20);
  }
  
  // 画面幅が768px以上の場合のみ自動スクロールを有効化
  if ($(window).width() > 768) {
    autoScrollFacility();
  }

  // 証言スライダー
  var testimonialIndex = 0;
  var testimonialItems = $('.owd-testimonial-item');
  var testimonialCount = testimonialItems.length;

  // 初期表示（最初の testimonial のみ表示）
  testimonialItems.hide();
  testimonialItems.eq(0).show();

  function showNextTestimonial() {
    testimonialItems.eq(testimonialIndex).fadeOut(500, function() {
      testimonialIndex = (testimonialIndex + 1) % testimonialCount;
      testimonialItems.eq(testimonialIndex).fadeIn(500);
    });
  }

  // 自動スライド（7秒ごと）
  var testimonialInterval = setInterval(showNextTestimonial, 7000);

  // マウスホバー時に停止
  $('.owd-testimonials-slider').hover(
    function() {
      clearInterval(testimonialInterval);
    },
    function() {
      testimonialInterval = setInterval(showNextTestimonial, 7000);
    }
  );

  // ナビゲーションのハイライト
  $(window).on('scroll', function() {
    var scrollPosition = $(this).scrollTop();

    // 各セクションの位置を取得
    $('.owd-course-content > div[id]').each(function() {
      var target = $(this);
      var targetTop = target.offset().top - 200;
      var targetBottom = targetTop + target.outerHeight();
      var targetId = target.attr('id');

      // スクロール位置がセクション内にある場合
      if (scrollPosition >= targetTop && scrollPosition <= targetBottom) {
        $('a[href="#' + targetId + '"]').addClass('active');
      } else {
        $('a[href="#' + targetId + '"]').removeClass('active');
      }
    });
  });

  // フォーム送信前のバリデーション
  $('.owd-application-form-wrapper form').on('submit', function(e) {
    var requiredFields = $(this).find('[required]');
    var valid = true;

    requiredFields.each(function() {
      if (!$(this).val()) {
        $(this).addClass('error');
        valid = false;
      } else {
        $(this).removeClass('error');
      }
    });

    if (!valid) {
      e.preventDefault();
      alert('必須項目を入力してください。');
      return false;
    }
  });

  // 入力フィールドのフォーカス/ブラー効果
  $('.owd-application-form-wrapper input, .owd-application-form-wrapper textarea, .owd-application-form-wrapper select').on('focus', function() {
    $(this).closest('.owd-form-col').addClass('focused');
  }).on('blur', function() {
    $(this).closest('.owd-form-col').removeClass('focused');
  });

  // スクロールアニメーション
  function animateOnScroll() {
    $('.owd-highlight-card, .owd-day-item, .owd-price-card, .owd-instructor-card, .owd-facility-slide, .owd-faq-item').each(function() {
      var element = $(this);
      var elementPosition = element.offset().top;
      var windowHeight = $(window).height();
      var scrollPosition = $(window).scrollTop();

      if (scrollPosition + windowHeight > elementPosition + 100) {
        element.addClass('animated');
      }
    });
  }

  // 初期ロード時と、スクロール時にアニメーション実行
  animateOnScroll();
  $(window).on('scroll', animateOnScroll);

  // 画像の遅延ロード
  function lazyLoadImages() {
    var lazyImages = $('img[data-src]');
    lazyImages.each(function() {
      var img = $(this);
      var src = img.attr('data-src');
      
      // IntersectionObserverを使用してビューポート内に要素が入った時に画像を読み込む
      if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries, observer) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              img.attr('src', src);
              img.removeAttr('data-src');
              observer.unobserve(entry.target);
            }
          });
        });
        observer.observe(img[0]);
      } else {
        // IntersectionObserverがサポートされていない場合のフォールバック
        img.attr('src', src);
        img.removeAttr('data-src');
      }
    });
  }
  
  lazyLoadImages();

  // ページ読み込み完了時のアニメーション
  $(window).on('load', function() {
    $('.owd-hero-section').addClass('loaded');
  });

  // easeInOutExpo イージング関数の追加（スムーススクロール用）
  $.extend($.easing, {
    easeInOutExpo: function (x, t, b, c, d) {
      if (t==0) return b;
      if (t==d) return b+c;
      if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
      return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
    }
  });
});

// アニメーション用のCSSクラスを追加
(function() {
  var style = document.createElement('style');
  style.type = 'text/css';
  var keyframes = `
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translate3d(0, 50px, 0);
      }
      to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
      }
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translate3d(0, -50px, 0);
      }
      to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    .owd-highlight-card, .owd-day-item, .owd-price-card, .owd-instructor-card, .owd-facility-slide, .owd-faq-item {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .owd-highlight-card.animated, .owd-day-item.animated, .owd-price-card.animated, .owd-instructor-card.animated, .owd-facility-slide.animated, .owd-faq-item.animated {
      opacity: 1;
      transform: translateY(0);
    }

    .owd-form-col.focused label {
      color: #00bbf0;
    }

    .owd-hero-section.loaded .owd-main-title,
    .owd-hero-section.loaded .owd-subtitle,
    .owd-hero-section.loaded .owd-badge,
    .owd-hero-section.loaded .owd-cta-button-wrapper {
      animation-play-state: running;
    }
  `;
  style.innerHTML = keyframes;
  document.getElementsByTagName('head')[0].appendChild(style);
})();