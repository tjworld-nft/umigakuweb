/**
 * ★License 5 fixed - 5コース固定ライセンスページ JavaScript
 * 詳細アコーディオンのみ（フィルタ機能なし）
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // =============================================
    // COURSE DETAIL ACCORDION
    // =============================================
    const detailButtons = document.querySelectorAll('.btn-outline');
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const courseSlug = this.getAttribute('data-course');
            const detailElement = document.getElementById(`detail-${courseSlug}`);
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Close all other details
            detailButtons.forEach(otherBtn => {
                if (otherBtn !== this) {
                    const otherSlug = otherBtn.getAttribute('data-course');
                    const otherDetail = document.getElementById(`detail-${otherSlug}`);
                    otherBtn.setAttribute('aria-expanded', 'false');
                    otherBtn.textContent = '詳細を見る';
                    otherDetail.classList.remove('open');
                    otherDetail.setAttribute('aria-hidden', 'true');
                }
            });
            
            // Toggle current detail
            if (isExpanded) {
                this.setAttribute('aria-expanded', 'false');
                this.textContent = '詳細を見る';
                detailElement.classList.remove('open');
                detailElement.setAttribute('aria-hidden', 'true');
            } else {
                this.setAttribute('aria-expanded', 'true');
                this.textContent = '詳細を閉じる';
                detailElement.classList.add('open');
                detailElement.setAttribute('aria-hidden', 'false');
                
                // Scroll to make details visible
                setTimeout(() => {
                    detailElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 300);
            }
        });
    });

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
                const headerOffset = 80;
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
    // RIPPLE EFFECT FOR BUTTONS
    // =============================================
    document.querySelectorAll('.ripple, .btn-cta, .btn-primary').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.4);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // =============================================
    // CTA BUTTON TRACKING
    // =============================================
    const ctaButtons = document.querySelectorAll('.btn-cta, .btn-primary, .btn-secondary');
    ctaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const buttonText = this.textContent.trim();
            const buttonHref = this.getAttribute('href');
            
            // Track with Google Analytics (if available)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'license_5_cta_click', {
                    'event_category': 'engagement',
                    'event_label': buttonText,
                    'page_location': window.location.href,
                    'value': 1
                });
            }
            
            // Console log for debugging
            console.log('License 5 CTA clicked:', buttonText);
        });
    });

    // =============================================
    // PERFORMANCE OPTIMIZATION
    // =============================================
    
    // Intersection Observer for course cards animation
    const courseCards = document.querySelectorAll('.course-card');
    const cardObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    // Initially hide cards for animation
    courseCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        cardObserver.observe(card);
    });

    // =============================================
    // ACCESSIBILITY ENHANCEMENTS
    // =============================================
    
    // ARIA live regions for dynamic content
    const liveRegion = document.createElement('div');
    liveRegion.setAttribute('aria-live', 'polite');
    liveRegion.setAttribute('aria-atomic', 'true');
    liveRegion.className = 'sr-only';
    liveRegion.style.cssText = `
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    `;
    document.body.appendChild(liveRegion);
    
    // Announce page interactions to screen readers
    function announceToScreenReader(message) {
        liveRegion.textContent = message;
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 1000);
    }

    // Add skip link
    const skipLink = document.createElement('a');
    skipLink.href = '#courses-title';
    skipLink.textContent = 'コース一覧にスキップ';
    skipLink.className = 'skip-link';
    skipLink.style.cssText = `
        position: absolute;
        top: -40px;
        left: 6px;
        background: #000;
        color: white;
        padding: 8px;
        text-decoration: none;
        z-index: 10000;
        border-radius: 4px;
    `;
    skipLink.addEventListener('focus', function() {
        this.style.top = '6px';
    });
    skipLink.addEventListener('blur', function() {
        this.style.top = '-40px';
    });
    document.body.insertBefore(skipLink, document.body.firstChild);

    // =============================================
    // ERROR HANDLING & FALLBACKS
    // =============================================
    
    // Graceful degradation for older browsers
    if (!window.IntersectionObserver) {
        // Show all cards immediately
        courseCards.forEach(card => {
            card.style.opacity = '1';
            card.style.transform = 'none';
        });
    }

    console.log('License 5 page JavaScript initialized successfully');
});

// =============================================
// CSS ANIMATIONS (Added via JavaScript)
// =============================================
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Course card entrance animation */
    .course-card {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Smooth scrolling fallback */
    html {
        scroll-behavior: smooth;
    }
    
    /* Skip link styles */
    .skip-link:focus {
        top: 6px !important;
    }
`;

document.head.appendChild(style);