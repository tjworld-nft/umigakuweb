/**
 * Books Slider for Miura Diving School
 * Simple, lightweight book slider implementation
 */

(function($) {
    'use strict';
    
    let currentSlide = 0;
    const slidesToShow = 3; // デスクトップで表示する数
    let totalSlides = 0;
    let autoPlayInterval = null;
    
    function initBooksSlider() {
        const $container = $('#booksContainer');
        const $books = $('.book-card');
        const $prevBtn = $('#prevBtn');
        const $nextBtn = $('#nextBtn');
        const $dotsContainer = $('#dotsContainer');
        const $autoPlayToggle = $('#autoPlayToggle');
        
        if (!$container.length || !$books.length) return;
        
        totalSlides = Math.max(0, $books.length - slidesToShow + 1);
        
        // ドット作成
        $dotsContainer.empty();
        for (let i = 0; i < totalSlides; i++) {
            const $dot = $('<div class="dot"></div>');
            if (i === 0) $dot.addClass('active');
            $dot.on('click', function() { goToSlide(i); });
            $dotsContainer.append($dot);
        }
        
        // ボタンイベント
        $prevBtn.on('click', function() { 
            goToSlide(currentSlide - 1); 
            resetAutoPlay(); 
        });
        
        $nextBtn.on('click', function() { 
            goToSlide(currentSlide + 1); 
            resetAutoPlay(); 
        });
        
        $autoPlayToggle.on('click', toggleAutoPlay);
        
        // 初期表示
        updateSlider();
        
        // 自動再生開始
        startAutoPlay();
        
        // レスポンシブ対応
        $(window).on('resize', function() {
            updateSlider();
        });
    }
    
    function goToSlide(index) {
        currentSlide = Math.max(0, Math.min(index, totalSlides - 1));
        updateSlider();
    }
    
    function updateSlider() {
        const $container = $('#booksContainer');
        if (!$container.length) return;
        
        const offset = currentSlide * 340; // 320px + 20px gap
        
        $container.css('transform', 'translateX(-' + offset + 'px)');
        
        // ドット更新
        $('.dot').removeClass('active').eq(currentSlide).addClass('active');
        
        // ボタン状態更新
        $('#prevBtn').prop('disabled', currentSlide === 0);
        $('#nextBtn').prop('disabled', currentSlide === totalSlides - 1);
    }
    
    function nextSlide() {
        if (currentSlide < totalSlides - 1) {
            currentSlide++;
        } else {
            currentSlide = 0; // ループ
        }
        updateSlider();
    }
    
    function startAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, 3000);
    }
    
    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
    }
    
    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }
    
    function toggleAutoPlay() {
        const $toggle = $('#autoPlayToggle');
        if (autoPlayInterval) {
            stopAutoPlay();
            $toggle.text('自動再生 OFF').removeClass('active');
        } else {
            startAutoPlay();
            $toggle.text('自動再生 ON').addClass('active');
        }
    }
    
    // 初期化
    $(document).ready(function() {
        initBooksSlider();
    });
    
})(jQuery);