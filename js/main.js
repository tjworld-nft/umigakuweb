/* ==========================================================================
   三浦 海の学校 — Main JavaScript（全ページ共通）
   --------------------------------------------------------------------------
   ⚠ このファイルはサーバーが7日キャッシュする。直したら、全ページの
     js/main.js?v=YYYYMMDD と、下の ASSET_V（js/ask.js の番号）を上げること。
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

  var ASSET_V = '2026111402';
  var REDUCE = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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
  var closeNav = function () {};

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

    closeNav = close;
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
      const id = this.getAttribute('href');
      if (!id || id === '#') return;
      let target = null;
      try { target = document.querySelector(id); } catch (err) { return; }
      if (target) {
        e.preventDefault();
        /* たたんである所（details）の中へ飛ぶときは、先にひらく */
        for (let d = target.closest('details:not([open])'); d; d = d.parentElement ? d.parentElement.closest('details:not([open])') : null) d.open = true;
        const offset = header ? header.offsetHeight : 0;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: REDUCE ? 'auto' : 'smooth' });
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
     相談シート（js/ask.js）— 「空き・日程」を聞くボタンを押すと、
     コース・日にち・人数をえらぶだけで文面ができる画面をひらく。
     ・ひらくのは：スマホ下部バーのLINE／メニューの中のLINE／ヒーローの
       「空き」「日程」を聞くLINEボタン／data-ask つきのリンク。
       （映像の画面の中のLINEボタンは、映像ごとの文面があるので今までどおり）
     ・質問・見積もり・器材相談など、専用の文面をもつボタンは今までどおり
       （文面をコピーしてLINEをひらく）。data-ask="off" でも外せる。
     ・ask.js を読めなかったら、リンク先（LINEの友だち追加）へそのまま進む。
     ========================================================================== */
  var CAT2COURSE = {
    '体験ダイビング': 'trial', 'OWDライセンス': 'license', 'AOWライセンス': 'step',
    'ファンダイビング': 'fun', 'リフレッシュ': 'refresh', 'マリンアクティビティ': 'snorkel'
  };
  var ASK_RE = /空き|日程/;
  var askLoading = null;

  function askTrigger(a) {
    if (!a || a.getAttribute('data-ask') === 'off') return false;
    if (a.hasAttribute('data-ask')) return true;
    /* ヘッダーの「予約・相談」（行き先がページ最後の相談帯のもの）。ひらけなければ、今までどおり相談帯へ進む */
    if (a.classList.contains('nav-cta') && a.getAttribute('href') === '#contact') return true;
    /* 下の3つは、行き先がLINEのボタンだけ（アプリ案内ページの下部バーはApp Storeへのリンク） */
    if (!/^https:\/\/(lin\.ee|line\.me)\//.test(a.getAttribute('href') || '')) return false;
    if (a.closest('.mobile-cta-bar') && a.classList.contains('cta-line')) return true;
    if (a.closest('.mobile-nav-cta') && a.classList.contains('btn-line')) return true;
    if (a.classList.contains('btn-line') && a.closest('.ph, .hero-buttons')) return ASK_RE.test(a.textContent || '');
    return false;
  }

  function askContext(a) {
    var ctx = { course: '', from: '', msg: '' };
    var pageForm = document.querySelector('.ask-form-btn');          /* 相談帯のフォーム（種別つき） */
    var pageLine = document.querySelector('#contact .btn-line[data-line-msg]');
    try {
      if (pageForm) {
        var u = new URL(pageForm.getAttribute('href'), window.location.href);
        var cat = u.searchParams.get('category');
        if (cat && CAT2COURSE[cat]) ctx.course = CAT2COURSE[cat];
        ctx.from = u.searchParams.get('from') || '';
      }
    } catch (e) {}
    var explicit = a.getAttribute('data-ask');
    if (explicit && explicit !== 'off' && explicit !== '') ctx.course = explicit;
    var own = a.getAttribute('data-line-msg') || '';
    var page = pageLine ? (pageLine.getAttribute('data-line-msg') || '') : '';
    /* 海況カードから（その日を入れてひらく）。文面はシートが作るので、ボタンの文面は使わない */
    var day = a.getAttribute('data-ask-date') || '';
    if (/^\d{4}-\d{2}-\d{2}$/.test(day)) ctx.day = day;
    else if (ASK_RE.test(own)) ctx.msg = own;
    else if (ASK_RE.test(page)) ctx.msg = page;
    if (!ctx.from) ctx.from = (window.location.pathname.replace(/^\/|\/$/g, '').split('/')[0] || 'home');
    var where = a.closest('.mobile-cta-bar') ? 'bar' : a.closest('.mobile-nav, .site-header') ? 'nav'
      : a.closest('.cond-action') ? 'cond' : a.closest('.ph, .hero') ? 'hero' : 'btn';
    ctx.from += '-' + where;
    return ctx;
  }

  function loadAsk() {
    if (window.UmiAsk) return Promise.resolve(window.UmiAsk);
    if (askLoading) return askLoading;
    askLoading = new Promise(function (resolve, reject) {
      var s = document.createElement('script');
      s.src = '/js/ask.js?v=' + ASSET_V;
      s.async = true;
      s.onload = function () { window.UmiAsk ? resolve(window.UmiAsk) : reject(new Error('ask')); };
      s.onerror = function () { askLoading = null; reject(new Error('ask')); };
      document.head.appendChild(s);
    });
    return askLoading;
  }

  var canAsk = !!(window.HTMLDialogElement && document.createElement('dialog').showModal);
  if (canAsk) {
    document.addEventListener('click', function (e) {
      if (e.defaultPrevented || e.button || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      var a = e.target.closest && e.target.closest('a[href]');
      if (!askTrigger(a)) return;
      var ctx = askContext(a);
      var href = a.href;
      e.preventDefault();
      e.stopPropagation();                      /* page.js / home.js の「コピーしてLINE」は動かさない */
      if (a.closest('.mobile-nav')) closeNav();  /* メニューの中から押したら、メニューは閉じる */
      loadAsk().then(function (api) {
        if (!api.open(ctx)) window.location.href = href;
      }, function () { window.location.href = href; });
    }, true);

    /* 押す前に用意しておく：触れた瞬間／少し手が空いたとき（通信の節約を選んでいる人は、押してから） */
    var warm = function (e) { var a = e.target.closest && e.target.closest('a[href]'); if (askTrigger(a)) loadAsk().catch(function () {}); };
    document.addEventListener('pointerover', warm, { passive: true });
    document.addEventListener('touchstart', warm, { passive: true });
    var conn = navigator.connection || {};
    if (!conn.saveData && document.querySelector('.mobile-cta-bar .cta-line, .mobile-nav-cta .btn-line')) {
      var idle = window.requestIdleCallback || function (f) { return setTimeout(f, 1); };
      window.addEventListener('load', function () { setTimeout(function () { idle(function () { loadAsk().catch(function () {}); }); }, 2600); });
    }
  }

  /* ==========================================================================
     よくある質問 — 答えをひらいたら、その下に「この続きを、LINEで聞く」を1つ足す。
     押すと、その質問を書いた文面をコピーしてLINEをひらく（コピーは page.js / home.js の
     data-line-msg の仕組み。どちらも無いページでは、LINEがそのままひらく）。
     ========================================================================== */
  document.addEventListener('toggle', function (e) {
    var d = e.target;
    if (!d || !d.open || !d.matches || !d.matches('details.faq-item') || d.querySelector('.faq-ask')) return;
    var ans = d.querySelector('.faq-answer');
    var sum = d.querySelector('summary');
    if (!ans || !sum) return;
    var q = (sum.textContent || '').replace(/\s+/g, ' ').trim();
    if (!q) return;
    var a = document.createElement('a');
    a.className = 'faq-ask';
    a.href = 'https://lin.ee/kK3d5p2';
    a.target = '_blank';
    a.rel = 'noopener noreferrer';
    a.setAttribute('data-line-msg', '「' + q + '」について、もう少しくわしく聞きたいです。');
    a.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3C6.5 3 2 6.6 2 11c0 2.5 1.5 4.8 3.8 6.2-.2.9-.7 2.3-1.6 3.4 1.9-.2 3.7-1 5-2 .9.2 1.8.4 2.8.4 5.5 0 10-3.6 10-8S17.5 3 12 3z"/></svg>この続きを、LINEで聞く';
    var p = document.createElement('p');
    p.className = 'faq-ask-row';
    p.appendChild(a);
    ans.appendChild(p);
  }, true);

  /* ==========================================================================
     水の輪 — ボタンやカードを押したところから、水面の輪がひろがる
     ========================================================================== */
  if (!REDUCE) {
    var RING = '.btn-line, .btn, .btn-ink, .btn-deep, .btn-glass, .cta-line, .cta-form, .nav-cta, .ask-form-btn, ' +
      '.course, .finder-tab, .perk, .sc-card, .sc-feature, .hero-campaign-pill, .film-stage, .hero-play, .wf-more, ' +
      '.uask__btn, .uask-chip, .uask-date, .cond-card, .lnav__list a, .chips-pick label, .mr-nav a, .manga-card, ' +
      '.faq-ask, .next-step a, .mr-end__btns a, .mr-more__card, .mh-start a';
    document.addEventListener('pointerdown', function (e) {
      if (e.button) return;
      var t = e.target.closest && e.target.closest(RING);
      if (!t) return;
      var ring = document.createElement('span');
      ring.className = 'tap-ring';
      ring.style.left = e.clientX + 'px';
      ring.style.top = e.clientY + 'px';
      (t.closest('dialog[open]') || document.body).appendChild(ring);
      var kill = function () { if (ring.parentNode) ring.parentNode.removeChild(ring); };
      ring.addEventListener('animationend', kill);
      setTimeout(kill, 1200);
    }, { passive: true });
  }

  /* ==========================================================================
     次のページを先に取りに行く（リンクに触れた／押しはじめたとき）。
     対応していないブラウザは何もしない。通信の節約中はブラウザが自分で止める。
     ========================================================================== */
  try {
    if (HTMLScriptElement.supports && HTMLScriptElement.supports('speculationrules')) {
      var rules = document.createElement('script');
      rules.type = 'speculationrules';
      rules.textContent = JSON.stringify({
        prefetch: [{
          source: 'document',
          where: { and: [
            { href_matches: '/*' },
            { not: { href_matches: ['/contact/send_mail.php*', '/aow-learning/*', '/staff/*', '/games/*', '/video/*', '/image/*', '/*.pdf', '/*.zip'] } },
            { not: { selector_matches: '[target=_blank], [download], [rel~=nofollow]' } }
          ] },
          eagerness: 'moderate'
        }]
      });
      document.head.appendChild(rules);
    }
  } catch (e) {}
});
