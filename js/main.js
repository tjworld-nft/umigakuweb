/* ==========================================================================
   三浦 海の学校 — Main JavaScript
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

  /* ==========================================================================
     Scroll Header
     ========================================================================== */
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 60);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ==========================================================================
     Mobile Navigation
     ========================================================================== */
  const burger = document.querySelector('.hamburger');
  const mobileNav = document.querySelector('.mobile-nav');

  if (burger && mobileNav) {
    const open = () => {
      burger.classList.add('is-open');
      mobileNav.classList.add('is-open');
      document.body.style.overflow = 'hidden';
      burger.setAttribute('aria-expanded', 'true');
    };
    const close = () => {
      burger.classList.remove('is-open');
      mobileNav.classList.remove('is-open');
      document.body.style.overflow = '';
      burger.setAttribute('aria-expanded', 'false');
    };

    burger.addEventListener('click', () => {
      mobileNav.classList.contains('is-open') ? close() : open();
    });

    // Close on overlay click
    mobileNav.addEventListener('click', (e) => {
      if (e.target === mobileNav) close();
    });

    // Close on link click
    mobileNav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', close);
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) close();
    });
  }

  /* ==========================================================================
     Smooth scroll for anchor links
     ========================================================================== */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = header ? header.offsetHeight : 0;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  /* ==========================================================================
     Intersection Observer — reveal animations
     ========================================================================== */
  const reveals = document.querySelectorAll('.reveal');
  if (reveals.length > 0 && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          // Stagger siblings
          const delay = entry.target.dataset.delay || 0;
          setTimeout(() => {
            entry.target.classList.add('is-visible');
          }, delay * 100);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    reveals.forEach(el => observer.observe(el));
  } else {
    // Fallback: show everything
    reveals.forEach(el => el.classList.add('is-visible'));
  }

  /* ==========================================================================
     Performance log
     ========================================================================== */
  if (console && console.log) {
    console.log('🌊 三浦 海の学校 — New Design Loaded!');
  }
});
