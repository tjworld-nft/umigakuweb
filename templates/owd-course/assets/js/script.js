// ================================================
// OWD Course Landing Page - JavaScript
// ================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('OWD Course Landing Page initialized');
    
    // Initialize all components
    initMobileMenu();
    initSmoothScroll();
    initFAQAccordion();
    initFormValidation();
    initAnimations();
    initFAQFilter();
    initNewsletterForm();
    initScrollIndicator();
});

// ================================================
// Mobile Menu
// ================================================
function initMobileMenu() {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    
    if (menuToggle && mobileNav) {
        menuToggle.addEventListener('click', function() {
            mobileNav.classList.toggle('active');
            
            // Animate hamburger lines
            const lines = menuToggle.querySelectorAll('.hamburger-line');
            if (mobileNav.classList.contains('active')) {
                lines[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                lines[1].style.opacity = '0';
                lines[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            } else {
                lines[0].style.transform = 'none';
                lines[1].style.opacity = '1';
                lines[2].style.transform = 'none';
            }
        });
        
        // Close menu when clicking on links
        const navLinks = mobileNav.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                const lines = menuToggle.querySelectorAll('.hamburger-line');
                lines[0].style.transform = 'none';
                lines[1].style.opacity = '1';
                lines[2].style.transform = 'none';
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!menuToggle.contains(e.target) && !mobileNav.contains(e.target)) {
                mobileNav.classList.remove('active');
                const lines = menuToggle.querySelectorAll('.hamburger-line');
                lines[0].style.transform = 'none';
                lines[1].style.opacity = '1';
                lines[2].style.transform = 'none';
            }
        });
    }
}

// ================================================
// Smooth Scroll
// ================================================
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ================================================
// FAQ Accordion
// ================================================
function initFAQAccordion() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        if (question) {
            question.addEventListener('click', function() {
                const isActive = item.classList.contains('active');
                
                // Close all other FAQ items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                if (isActive) {
                    item.classList.remove('active');
                } else {
                    item.classList.add('active');
                }
            });
        }
    });
}

// ================================================
// FAQ Filter
// ================================================
function initFAQFilter() {
    const categoryTabs = document.querySelectorAll('.category-tab');
    const faqItems = document.querySelectorAll('.faq-item');
    
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active tab
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Filter FAQ items
            faqItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                
                if (category === 'all' || itemCategory === category) {
                    item.style.display = 'block';
                    // Add animation
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.opacity = '1';
                    }, 100);
                } else {
                    item.style.display = 'none';
                }
                
                // Close all accordions when filtering
                item.classList.remove('active');
            });
        });
    });
}

// ================================================
// Form Validation
// ================================================
function initFormValidation() {
    const contactForm = document.getElementById('contact-form-main');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm(this)) {
                submitForm(this);
            }
        });
        
        // Real-time validation
        const inputs = contactForm.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Health conditions checkbox logic
        const healthCheckboxes = contactForm.querySelectorAll('input[name="health_conditions[]"]');
        const noneCheckbox = contactForm.querySelector('input[value="none"]');
        
        if (noneCheckbox) {
            noneCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    healthCheckboxes.forEach(checkbox => {
                        if (checkbox !== this) {
                            checkbox.checked = false;
                        }
                    });
                }
            });
            
            healthCheckboxes.forEach(checkbox => {
                if (checkbox !== noneCheckbox) {
                    checkbox.addEventListener('change', function() {
                        if (this.checked && noneCheckbox.checked) {
                            noneCheckbox.checked = false;
                        }
                    });
                }
            });
        }
    }
}

function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Email validation
    const emailField = form.querySelector('input[type="email"]');
    if (emailField && emailField.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value)) {
            showFieldError(emailField, 'メールアドレスの形式が正しくありません');
            isValid = false;
        }
    }
    
    // Phone validation
    const phoneField = form.querySelector('input[type="tel"]');
    if (phoneField && phoneField.value) {
        const phoneRegex = /^[0-9-]+$/;
        if (!phoneRegex.test(phoneField.value)) {
            showFieldError(phoneField, '電話番号は数字とハイフンで入力してください');
            isValid = false;
        }
    }
    
    // Privacy agreement validation
    const privacyCheckbox = form.querySelector('input[name="privacy_agreement"]');
    if (privacyCheckbox && !privacyCheckbox.checked) {
        showFieldError(privacyCheckbox, 'プライバシーポリシーに同意してください');
        isValid = false;
    }
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'この項目は必須です');
        return false;
    }
    
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'メールアドレスの形式が正しくありません');
            return false;
        }
    }
    
    if (field.type === 'tel' && value) {
        const phoneRegex = /^[0-9-]+$/;
        if (!phoneRegex.test(value)) {
            showFieldError(field, '電話番号は数字とハイフンで入力してください');
            return false;
        }
    }
    
    if (field.hasAttribute('pattern') && value) {
        const pattern = new RegExp(field.getAttribute('pattern'));
        if (!pattern.test(value)) {
            showFieldError(field, field.getAttribute('title') || '入力形式が正しくありません');
            return false;
        }
    }
    
    clearFieldError(field);
    return true;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#dc3545';
    
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.style.color = '#dc3545';
    errorElement.style.fontSize = '0.875rem';
    errorElement.style.marginTop = '4px';
    errorElement.textContent = message;
    
    field.parentNode.appendChild(errorElement);
}

function clearFieldError(field) {
    field.style.borderColor = '#ddd';
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

function submitForm(form) {
    const submitButton = form.querySelector('.submit-button');
    const buttonText = submitButton.querySelector('.button-text');
    const buttonLoading = submitButton.querySelector('.button-loading');
    
    // Show loading state
    if (buttonText && buttonLoading) {
        buttonText.style.display = 'none';
        buttonLoading.style.display = 'inline';
    }
    submitButton.disabled = true;
    
    // Prepare form data
    const formData = new FormData(form);
    
    // Simulate form submission (replace with actual submission logic)
    setTimeout(() => {
        // In a real implementation, you would send the data to your server
        console.log('Form data:', Object.fromEntries(formData));
        
        // Show success message
        showSuccessMessage('お申し込みありがとうございます。24時間以内にご連絡いたします。');
        
        // Reset form
        form.reset();
        
        // Reset button state
        if (buttonText && buttonLoading) {
            buttonText.style.display = 'inline';
            buttonLoading.style.display = 'none';
        }
        submitButton.disabled = false;
        
        // Scroll to top of form
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
    }, 2000); // Simulate network delay
}

function showSuccessMessage(message) {
    // Remove existing success message
    const existingMessage = document.querySelector('.success-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    const successElement = document.createElement('div');
    successElement.className = 'success-message';
    successElement.style.cssText = `
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
    `;
    successElement.textContent = message;
    
    const formContainer = document.querySelector('.form-container');
    if (formContainer) {
        formContainer.insertBefore(successElement, formContainer.firstChild);
    }
}

// ================================================
// Newsletter Form
// ================================================
function initNewsletterForm() {
    const newsletterForm = document.getElementById('newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const submitButton = this.querySelector('button[type="submit"]');
            
            if (emailInput && emailInput.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!emailRegex.test(emailInput.value)) {
                    alert('メールアドレスの形式が正しくありません');
                    return;
                }
                
                // Show loading state
                const originalText = submitButton.textContent;
                submitButton.textContent = '登録中...';
                submitButton.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    alert('メール配信の登録が完了しました！');
                    emailInput.value = '';
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                }, 1000);
            }
        });
    }
}

// ================================================
// Scroll Animations
// ================================================
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    // Add animation classes to elements
    const animatedElements = document.querySelectorAll(`
        .feature-card,
        .benefit-card,
        .review-card,
        .destination-card,
        .faq-item,
        .cost-item,
        .timeline-day,
        .section-header
    `);
    
    animatedElements.forEach((el, index) => {
        el.classList.add('fade-in');
        el.style.transitionDelay = `${index * 0.1}s`;
        observer.observe(el);
    });
}

// ================================================
// Scroll Indicator
// ================================================
function initScrollIndicator() {
    const scrollIndicator = document.querySelector('.scroll-indicator');
    
    if (scrollIndicator) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            const windowHeight = window.innerHeight;
            
            // Hide scroll indicator after scrolling 50% of viewport
            if (scrollPosition > windowHeight * 0.5) {
                scrollIndicator.style.opacity = '0';
            } else {
                scrollIndicator.style.opacity = '1';
            }
        });
    }
}

// ================================================
// Utility Functions
// ================================================

// Debounce function for performance optimization
function debounce(func, wait) {
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

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Format phone number as user types
function formatPhoneNumber(value) {
    // Remove all non-digit characters
    const digits = value.replace(/\D/g, '');
    
    // Format as xxx-xxxx-xxxx
    if (digits.length <= 3) {
        return digits;
    } else if (digits.length <= 7) {
        return `${digits.slice(0, 3)}-${digits.slice(3)}`;
    } else {
        return `${digits.slice(0, 3)}-${digits.slice(3, 7)}-${digits.slice(7, 11)}`;
    }
}

// Apply phone formatting to phone inputs
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            const formatted = formatPhoneNumber(this.value);
            if (formatted !== this.value) {
                this.value = formatted;
            }
        });
    });
});

// ================================================
// Performance Monitoring
// ================================================
function logPerformance() {
    if (window.performance && window.performance.timing) {
        const timing = window.performance.timing;
        const loadTime = timing.loadEventEnd - timing.navigationStart;
        
        console.log(`Page load time: ${loadTime}ms`);
        
        // You can send this data to analytics
        // analytics.track('page_load_time', { duration: loadTime });
    }
}

// Log performance after page load
window.addEventListener('load', function() {
    setTimeout(logPerformance, 100);
});

// ================================================
// Error Handling
// ================================================
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    
    // In production, you might want to send errors to a logging service
    // errorLogger.log(e.error, e.filename, e.lineno);
});

// Handle unhandled promise rejections
window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled promise rejection:', e.reason);
    
    // Prevent the default browser behavior
    e.preventDefault();
});

// ================================================
// Accessibility Enhancements
// ================================================
function initAccessibility() {
    // Focus management for mobile menu
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    
    if (mobileMenuToggle && mobileNav) {
        mobileMenuToggle.addEventListener('click', function() {
            if (mobileNav.classList.contains('active')) {
                const firstLink = mobileNav.querySelector('a');
                if (firstLink) {
                    firstLink.focus();
                }
            }
        });
    }
    
    // Keyboard navigation for FAQ items
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.setAttribute('tabindex', '0');
        question.setAttribute('role', 'button');
        question.setAttribute('aria-expanded', 'false');
        
        question.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
                
                const isExpanded = this.parentElement.classList.contains('active');
                this.setAttribute('aria-expanded', isExpanded);
            }
        });
    });
    
    // Skip to main content link
    const skipLink = document.createElement('a');
    skipLink.href = '#hero-section';
    skipLink.textContent = 'メインコンテンツにスキップ';
    skipLink.className = 'skip-link';
    skipLink.style.cssText = `
        position: absolute;
        top: -40px;
        left: 6px;
        background: #000;
        color: #fff;
        padding: 8px;
        text-decoration: none;
        z-index: 10000;
        transition: top 0.3s;
    `;
    
    skipLink.addEventListener('focus', function() {
        this.style.top = '6px';
    });
    
    skipLink.addEventListener('blur', function() {
        this.style.top = '-40px';
    });
    
    document.body.insertBefore(skipLink, document.body.firstChild);
}

// Initialize accessibility features
document.addEventListener('DOMContentLoaded', initAccessibility);