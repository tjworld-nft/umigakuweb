/**
 * Books Slider with Swiper.js
 * Responsive slider for displaying book recommendations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for books slider
    const booksSwiper = new Swiper('.books-swiper', {
        // Responsive breakpoints
        breakpoints: {
            // Mobile phones (1 slide)
            320: {
                slidesPerView: 1,
                spaceBetween: 20,
                centeredSlides: true,
            },
            // Tablets (2 slides) 
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
                centeredSlides: false,
            },
            // Desktop (3 slides)
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
                centeredSlides: false,
            }
        },
        
        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        
        // Pagination dots
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        
        // Auto-scroll (optional)
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        
        // Loop mode
        loop: true,
        
        // Smooth transitions
        speed: 600,
        
        // Allow dragging/swiping
        grabCursor: true,
        
        // Accessibility
        a11y: {
            enabled: true,
            prevSlideMessage: '前のスライド',
            nextSlideMessage: '次のスライド',
            paginationBulletMessage: 'スライド {{index}}へ移動',
        },
        
        // Lazy loading for images
        lazy: {
            loadPrevNext: true,
        },
        
        // Effect
        effect: 'slide',
        
        // Mouse wheel control
        mousewheel: {
            invert: false,
        },
        
        // Keyboard control
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
    });
    
    // Pause autoplay on hover
    const swiperContainer = document.querySelector('.books-swiper');
    if (swiperContainer && booksSwiper.autoplay) {
        swiperContainer.addEventListener('mouseenter', () => {
            booksSwiper.autoplay.stop();
        });
        
        swiperContainer.addEventListener('mouseleave', () => {
            booksSwiper.autoplay.start();
        });
    }
    
    // Analytics tracking for book clicks (optional)
    const bookLinks = document.querySelectorAll('.book-link');
    bookLinks.forEach((link, index) => {
        link.addEventListener('click', function(e) {
            // Track book click analytics here if needed
            console.log(`Book clicked: ${link.querySelector('.book-title')?.textContent || 'Unknown'}`);
            
            // Add click animation
            const card = this.closest('.book-card');
            if (card) {
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 150);
            }
        });
    });
    
    // Handle touch events for better mobile experience
    let touchStartY = 0;
    swiperContainer?.addEventListener('touchstart', function(e) {
        touchStartY = e.touches[0].clientY;
    });
    
    swiperContainer?.addEventListener('touchmove', function(e) {
        const touchY = e.touches[0].clientY;
        const touchDiff = touchStartY - touchY;
        
        // If vertical scroll is detected, don't prevent default
        if (Math.abs(touchDiff) > 20) {
            // Allow vertical scrolling
            return;
        }
    });
});