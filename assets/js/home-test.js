/**
 * HOME TEST PAGE V2 JAVASCRIPT
 * Miura Diving School - Enhanced Accessibility & SEO
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // =============================================
    // TINY SLIDER INITIALIZATION
    // =============================================
    if (typeof tns !== 'undefined') {
        const activitiesSlider = document.querySelector('.activities-slider');
        if (activitiesSlider) {
            const slider = tns({
                container: '.activities-slider',
                items: 1,
                slideBy: 'page',
                autoplay: true,
                autoplayTimeout: 5000,
                controls: true,
                nav: true,
                mouseDrag: true,
                responsive: {
                    640: {
                        items: 2
                    },
                    900: {
                        items: 3
                    }
                }
            });
        }
    } else {
        // Fallback: Simple horizontal scroll behavior
        const activitiesSlider = document.querySelector('.activities-slider');
        if (activitiesSlider) {
            // Add smooth scrolling
            activitiesSlider.style.scrollBehavior = 'smooth';
            
            // Auto-scroll functionality
            let scrollInterval;
            const startAutoScroll = () => {
                scrollInterval = setInterval(() => {
                    const maxScroll = activitiesSlider.scrollWidth - activitiesSlider.clientWidth;
                    if (activitiesSlider.scrollLeft >= maxScroll) {
                        activitiesSlider.scrollLeft = 0;
                    } else {
                        activitiesSlider.scrollLeft += 350; // Width of one card + gap
                    }
                }, 4000);
            };
            
            const stopAutoScroll = () => {
                clearInterval(scrollInterval);
            };
            
            // Start auto-scroll
            startAutoScroll();
            
            // Pause on hover
            activitiesSlider.addEventListener('mouseenter', stopAutoScroll);
            activitiesSlider.addEventListener('mouseleave', startAutoScroll);
        }
    }
    
    // =============================================
    // SMOOTH SCROLLING FOR ANCHOR LINKS
    // =============================================
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerOffset = 80; // Adjust based on fixed header height
                const elementPosition = targetElement.offsetTop;
                const offsetPosition = elementPosition - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // =============================================
    // INTERSECTION OBSERVER FOR ANIMATIONS
    // =============================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animateElements = document.querySelectorAll(
        '.why-card, .activity-card, .point-item, .timeline-item, .lab-card, .blog-card'
    );
    
    animateElements.forEach(el => {
        el.classList.add('animate-element');
        observer.observe(el);
    });
    
    // =============================================
    // HERO VIDEO HANDLING
    // =============================================
    const heroVideo = document.querySelector('.hero-media source[type="video/mp4"]');
    if (heroVideo) {
        // Create video element if video source exists
        const video = document.createElement('video');
        video.src = heroVideo.src;
        video.autoplay = true;
        video.muted = true;
        video.loop = true;
        video.playsInline = true;
        video.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
        
        // Replace placeholder when video loads
        video.addEventListener('loadeddata', () => {
            const placeholder = document.querySelector('.hero .ph');
            if (placeholder) {
                placeholder.style.display = 'none';
                heroVideo.parentNode.appendChild(video);
            }
        });
        
        // Fallback to placeholder if video fails
        video.addEventListener('error', () => {
            console.log('Hero video failed to load, using placeholder');
        });
    }
    
    // =============================================
    // PARALLAX EFFECT FOR HERO
    // =============================================
    const hero = document.querySelector('.hero');
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            if (scrolled < hero.offsetHeight) {
                hero.style.transform = `translateY(${rate}px)`;
            }
        });
    }
    
    // =============================================
    // COURSE COMPARISON TABS ENHANCEMENT
    // =============================================
    const tabLabels = document.querySelectorAll('.tab-label');
    tabLabels.forEach(label => {
        label.addEventListener('click', function() {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // =============================================
    // LOADING ANIMATION FOR IMAGES
    // =============================================
    const placeholders = document.querySelectorAll('.ph');
    placeholders.forEach(placeholder => {
        // Add loading animation
        placeholder.style.position = 'relative';
        placeholder.style.overflow = 'hidden';
        
        // Create shimmer effect
        const shimmer = document.createElement('div');
        shimmer.style.cssText = `
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        `;
        
        placeholder.appendChild(shimmer);
    });
    
    // =============================================
    // FORM VALIDATION (if contact forms exist)
    // =============================================
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#e74c3c';
                    field.addEventListener('input', function() {
                        this.style.borderColor = '';
                    });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('必須項目を入力してください。');
            }
        });
    });
    
    // =============================================
    // SCROLL TO TOP BUTTON
    // =============================================
    const scrollToTopBtn = document.createElement('button');
    scrollToTopBtn.innerHTML = '↑';
    scrollToTopBtn.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
    `;
    
    document.body.appendChild(scrollToTopBtn);
    
    // Show/hide scroll to top button
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.style.opacity = '1';
            scrollToTopBtn.style.transform = 'translateY(0)';
        } else {
            scrollToTopBtn.style.opacity = '0';
            scrollToTopBtn.style.transform = 'translateY(10px)';
        }
    });
    
    // Scroll to top functionality
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // =============================================
    // PERFORMANCE OPTIMIZATION
    // =============================================
    
    // Lazy loading for images (if supported)
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.loading = 'lazy';
        });
    }
    
    // Debounce scroll events
    let scrollTimeout;
    const originalScrollHandler = window.onscroll;
    
    window.addEventListener('scroll', () => {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        
        scrollTimeout = setTimeout(() => {
            if (originalScrollHandler) {
                originalScrollHandler();
            }
        }, 10);
    });
    
    // =============================================
    // ACCESSIBILITY ENHANCEMENTS
    // =============================================
    
    // Keyboard navigation for slider
    const sliderContainer = document.querySelector('.activities-slider');
    if (sliderContainer) {
        sliderContainer.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.scrollLeft -= 350;
                this.setAttribute('aria-live', 'polite');
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.scrollLeft += 350;
                this.setAttribute('aria-live', 'polite');
            }
        });
    }
    
    // Enhanced tab management with ARIA
    const tabInputs = document.querySelectorAll('input[name="course-tab"]');
    const tabLabels = document.querySelectorAll('.tab-label[role="tab"]');
    
    tabLabels.forEach((label, index) => {
        // Click handler
        label.addEventListener('click', function() {
            updateTabStates(index);
        });
        
        // Keyboard handler
        label.addEventListener('keydown', function(e) {
            let newIndex = index;
            
            switch(e.key) {
                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    newIndex = index > 0 ? index - 1 : tabLabels.length - 1;
                    break;
                case 'ArrowRight':
                case 'ArrowDown':
                    e.preventDefault();
                    newIndex = index < tabLabels.length - 1 ? index + 1 : 0;
                    break;
                case 'Home':
                    e.preventDefault();
                    newIndex = 0;
                    break;
                case 'End':
                    e.preventDefault();
                    newIndex = tabLabels.length - 1;
                    break;
                default:
                    return;
            }
            
            tabInputs[newIndex].checked = true;
            updateTabStates(newIndex);
            tabLabels[newIndex].focus();
        });
    });
    
    function updateTabStates(activeIndex) {
        tabLabels.forEach((label, index) => {
            if (index === activeIndex) {
                label.setAttribute('aria-selected', 'true');
                label.setAttribute('tabindex', '0');
            } else {
                label.setAttribute('aria-selected', 'false');
                label.setAttribute('tabindex', '-1');
            }
        });
    }
    
    // ARIA live regions for dynamic content
    const liveRegion = document.createElement('div');
    liveRegion.setAttribute('aria-live', 'polite');
    liveRegion.setAttribute('aria-atomic', 'true');
    liveRegion.className = 'sr-only';
    document.body.appendChild(liveRegion);
    
    // Announce page interactions
    function announceToScreenReader(message) {
        liveRegion.textContent = message;
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 1000);
    }
    
    // =============================================
    // SEO & ANALYTICS ENHANCEMENTS
    // =============================================
    
    // Track CTA interactions
    const ctaButtons = document.querySelectorAll('.btn-cta, .btn-primary');
    ctaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const buttonText = this.textContent.trim();
            const buttonHref = this.getAttribute('href');
            
            // Track with Google Analytics (if available)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'cta_click', {
                    'event_category': 'engagement',
                    'event_label': buttonText,
                    'value': 1
                });
            }
            
            // Announce to screen reader
            if (buttonHref.startsWith('#')) {
                announceToScreenReader('セクションに移動しました');
            } else if (buttonHref.startsWith('tel:')) {
                announceToScreenReader('電話アプリを開きます');
            } else if (buttonHref.startsWith('mailto:')) {
                announceToScreenReader('メールアプリを開きます');
            }
        });
    });
    
    // =============================================
    // PERFORMANCE MONITORING
    // =============================================
    
    // Monitor Core Web Vitals
    if ('PerformanceObserver' in window) {
        // Largest Contentful Paint
        const lcpObserver = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            console.log('LCP:', lastEntry.startTime);
        });
        lcpObserver.observe({ type: 'largest-contentful-paint', buffered: true });
        
        // Cumulative Layout Shift
        const clsObserver = new PerformanceObserver((list) => {
            let clsValue = 0;
            for (const entry of list.getEntries()) {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                }
            }
            console.log('CLS:', clsValue);
        });
        clsObserver.observe({ type: 'layout-shift', buffered: true });
    }
    
    // =============================================
    // ERROR HANDLING & FALLBACKS
    // =============================================
    
    // Graceful degradation for older browsers
    if (!window.IntersectionObserver) {
        // Fallback for animation elements
        animateElements.forEach(el => {
            el.classList.add('animate-in');
        });
    }
    
    // Service Worker registration (if available)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(() => console.log('Service Worker registered'))
            .catch(() => console.log('Service Worker registration failed'));
    }
    
    console.log('Home Test Page V2 JavaScript initialized successfully');
});

// =============================================
// CSS ANIMATIONS (Added via JavaScript)
// =============================================
const style = document.createElement('style');
style.textContent = `
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .animate-element {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .animate-element.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Smooth scrolling fallback */
    html {
        scroll-behavior: smooth;
    }
    
    /* Focus styles for accessibility */
    .tab-label:focus,
    .btn-cta:focus,
    .btn-primary:focus,
    .btn-secondary:focus {
        outline: 2px solid #3498db;
        outline-offset: 2px;
    }
    
    .activities-slider:focus {
        outline: 2px solid #3498db;
        outline-offset: 2px;
    }
`;

document.head.appendChild(style);

// ★TOP polish v3 - ヘッダーフェード
const header = document.querySelector('header.site-header');
if (header) {
    window.addEventListener('scroll', () => {
        header.classList.toggle('is-scrolled', window.scrollY > 80);
    });
}

// ★WHY 6cards fix - IntersectionObserver & アニメーション

// Intersection Observer for parallax
const io = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.remove('wait');
            e.target.classList.add('in-view');
        }
    });
}, { threshold: .4 });

document.querySelectorAll('.parallax').forEach(el => {
    el.classList.add('wait'); // 初期状態
    io.observe(el);
});

// Count-up numbers
document.querySelectorAll('[data-countup]').forEach(el => {
    const target = +el.dataset.countup;
    const step = target / 60;
    let cur = 0;
    
    const countObserver = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting && !el.dataset.done) {
                el.dataset.done = 1;
                const timer = setInterval(() => {
                    cur += step;
                    if (cur >= target) {
                        cur = target;
                        clearInterval(timer);
                    }
                    el.textContent = Math.round(cur);
                }, 30);
            }
        });
    }, { threshold: .4 });
    
    countObserver.observe(el);
});

console.log('Hero+Wow effects initialized');

// =========================================
// ブログ自動更新機能
// =========================================

async function loadLatestBlog() {
    const blogContainer = document.getElementById('latest-blog');
    
    try {
        const response = await fetch('/api/latest-blog.php');
        const data = await response.json();
        
        if (data.success && data.posts && data.posts.length > 0) {
            // ブログ記事HTMLを生成
            const blogHTML = data.posts.map(post => `
                <article class="blog-card" role="listitem">
                    <a href="${post.url}" class="blog-link">
                        ${post.image ? `<div class="blog-image">
                            <img src="${post.image}" alt="${post.title}" loading="lazy">
                        </div>` : ''}
                        <div class="blog-content">
                            <h3 class="blog-title">${post.title}</h3>
                            <p class="blog-excerpt">${post.excerpt}</p>
                            <time class="blog-date">${new Date(post.date).toLocaleDateString('ja-JP')}</time>
                        </div>
                    </a>
                </article>
            `).join('');
            
            blogContainer.innerHTML = blogHTML;
        } else {
            // フォールバック表示
            blogContainer.innerHTML = `
                <article class="blog-card" role="listitem">
                    <a href="/blog/" class="blog-link">
                        <div class="blog-content">
                            <h3 class="blog-title">ブログを見る</h3>
                            <p class="blog-excerpt">最新の海の情報やダイビング体験談をお届けします。</p>
                            <time class="blog-date">${new Date().toLocaleDateString('ja-JP')}</time>
                        </div>
                    </a>
                </article>
            `;
        }
    } catch (error) {
        console.warn('ブログの読み込みに失敗しました:', error);
        // エラー時のフォールバック表示
        blogContainer.innerHTML = `
            <article class="blog-card" role="listitem">
                <a href="/blog/" class="blog-link">
                    <div class="blog-content">
                        <h3 class="blog-title">ブログを見る</h3>
                        <p class="blog-excerpt">最新の海の情報やダイビング体験談をお届けします。</p>
                        <time class="blog-date">${new Date().toLocaleDateString('ja-JP')}</time>
                    </div>
                </a>
            </article>
        `;
    }
}

// ページ読み込み時にブログを読み込み
document.addEventListener('DOMContentLoaded', loadLatestBlog);

// 5分ごとに自動更新（オプション）
setInterval(loadLatestBlog, 5 * 60 * 1000);