/* ==========================================================================
   三浦 海の学校 — 下層ページの動き（2026-09 リニューアル）
   --------------------------------------------------------------------------
   ・JSが無くてもページは完成している（料金は表で全部見える・目次は普通のリンク）。
     ここに書くのは「あると気持ちいい」拡張だけ。
   ・js/main.js（ヘッダー・メニュー・.reveal）のあとに読み込む。
   ========================================================================== */
(function () {
  'use strict';

  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var root = document.documentElement;
  var REDUCE = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var header = $('.site-header');
  var lnav = $('.lnav');
  var hero = $('.ph');

  /* ---------- 固定ヘッダー／目次の高さを CSS に渡す（アンカーの着地位置に使う） ---------- */
  function measure() {
    if (header) root.style.setProperty('--hdr', header.offsetHeight + 'px');
    if (lnav) root.style.setProperty('--lnav-h', lnav.offsetHeight + 'px');
  }
  measure();
  if (window.ResizeObserver) {
    var ro = new ResizeObserver(measure);
    if (header) ro.observe(header);
    if (lnav) ro.observe(lnav);
  } else {
    window.addEventListener('resize', measure);
  }

  /* ---------- 日本時間の「今日」 ---------- */
  function jstNow() {
    var n = new Date();
    return new Date(n.getTime() + n.getTimezoneOffset() * 60000 + 9 * 3600000);
  }
  function pad(n) { return (n < 10 ? '0' : '') + n; }
  function ymd(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }

  /* ==========================================================================
     1. 期限つきの表示（夏割など）
        data-expires="YYYY-MM-DD" … その日（日本時間）になったら外す
        data-deadline="YYYY-MM-DD" の中の .days に「あと◯日」を入れる
     ========================================================================== */
  (function expire() {
    var t = jstNow(), today = ymd(t);
    $$('[data-expires]').forEach(function (el) {
      if (today >= el.getAttribute('data-expires') && el.parentNode) el.parentNode.removeChild(el);
    });
    $$('[data-deadline]').forEach(function (w) {
      var p = (w.getAttribute('data-deadline') || '').split('-');
      if (p.length !== 3) return;
      var days = Math.round((Date.UTC(+p[0], +p[1] - 1, +p[2]) - Date.UTC(t.getFullYear(), t.getMonth(), t.getDate())) / 86400000);
      var slot = $('.days', w);
      if (days < 0) { w.hidden = true; return; }
      if (slot) slot.textContent = days === 0 ? '本日まで' : 'あと' + days + '日';
    });
  })();

  /* ==========================================================================
     2. LINEボタン：押したら「送る文面」をコピーしてからLINEを開く
     ========================================================================== */
  var toastEl = $('.toast');
  var toastTimer = 0;
  function toast(msg) {
    if (!toastEl) return;
    toastEl.textContent = msg;
    toastEl.classList.add('is-on');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toastEl.classList.remove('is-on'); }, 4200);
  }
  function copyText(text) {
    if (!text) return Promise.reject();
    if (navigator.clipboard && window.isSecureContext) return navigator.clipboard.writeText(text);
    return new Promise(function (res, rej) {
      try {
        var ta = document.createElement('textarea');
        ta.value = text; ta.setAttribute('readonly', ''); ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select();
        var ok = document.execCommand('copy'); document.body.removeChild(ta);
        ok ? res() : rej();
      } catch (e) { rej(e); }
    });
  }
  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('[data-line-msg]');
    if (!a) return;
    var msg = a.getAttribute('data-line-msg');
    if (!msg) return;
    copyText(msg).then(function () {
      toast('「' + msg + '」をコピーしました。LINEのトークに貼り付けて送ってください。');
    }, function () { /* コピーできなくてもLINEはそのまま開く */ });
  });

  /* ==========================================================================
     3. ページ内リンク：追従する目次の下に着地させる
        （main.js も同じリンクを拾うので、捕獲フェーズで先に処理して止める）
     ========================================================================== */
  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('a[href^="#"]');
    if (!a) return;
    var id = a.getAttribute('href').slice(1);
    if (!id) return;
    var target = document.getElementById(id);
    if (!target) return;
    e.preventDefault();
    e.stopPropagation();
    measure();
    target.scrollIntoView({ behavior: REDUCE ? 'auto' : 'smooth', block: 'start' });
    if (history.replaceState) history.replaceState(null, '', '#' + id);
    /* キーボード操作のとき、読み上げとフォーカスも移す */
    if (!target.hasAttribute('tabindex')) target.setAttribute('tabindex', '-1');
    target.focus({ preventScroll: true });
  }, true);

  /* ==========================================================================
     4. 目次：いま読んでいる節を光らせる＋進み具合＋「このページの水深」
     ========================================================================== */
  var links = lnav ? $$('.lnav__list a', lnav) : [];
  var targets = links.map(function (a) { return document.getElementById(a.getAttribute('href').slice(1)); });
  var list = lnav && $('.lnav__list', lnav);
  var readout = lnav && $('.lnav__depth b', lnav);
  var maxDepth = lnav ? parseFloat(lnav.getAttribute('data-depth') || '18') : 18;
  var bar = $('.mobile-cta-bar');
  var current = -1;
  var ticking = false;

  function update() {
    ticking = false;
    var vh = window.innerHeight;
    var y = window.scrollY || window.pageYOffset;
    var max = Math.max(1, root.scrollHeight - vh);

    if (bar && hero) bar.classList.toggle('is-hidden', hero.getBoundingClientRect().bottom > vh * 0.35);
    if (!lnav) return;

    /* 目次は追従（sticky）するので offsetTop は使えない。最初の節の位置を起点にする */
    var first = targets[0];
    var start = first ? first.getBoundingClientRect().top + y : 0;
    var p = Math.min(1, Math.max(0, (y + vh * 0.5 - start) / Math.max(1, max + vh * 0.5 - start)));
    lnav.style.setProperty('--p', p.toFixed(4));
    if (readout) readout.textContent = (p * maxDepth).toFixed(1) + 'm';

    var line = (parseFloat(getComputedStyle(root).getPropertyValue('--hdr')) || 60) + lnav.offsetHeight + vh * 0.18;
    var on = -1;
    targets.forEach(function (t, i) { if (t && t.getBoundingClientRect().top <= line) on = i; });
    if (on !== current) {
      current = on;
      links.forEach(function (a, i) {
        var hit = i === on;
        a.classList.toggle('is-on', hit);
        if (hit) a.setAttribute('aria-current', 'location'); else a.removeAttribute('aria-current');
      });
      /* 光っている項目が目次の帯の中に見えるように（縦スクロールは動かさない） */
      if (on >= 0 && list) {
        var a = links[on];
        var left = a.offsetLeft - (list.clientWidth - a.offsetWidth) / 2;
        list.scrollTo({ left: Math.max(0, left), behavior: REDUCE ? 'auto' : 'smooth' });
      }
    }
  }
  function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(update); } }
  /* 目次が帯に収まらないときだけ、右端をぼかして「横に続く」ことを示す */
  function overflowHint() { if (list) list.classList.toggle('is-overflow', list.scrollWidth > list.clientWidth + 4); }
  overflowHint();
  window.addEventListener('resize', overflowHint);
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll);
  update();

  /* ==========================================================================
     5. 総額シミュレーター（講習ページ）
        <div data-est> の中のラジオ（name=est-course / est-gear）から計算する。
        料金の正は HTML 側の data 属性。ここには数字を書かない。
     ========================================================================== */
  $$('[data-est]').forEach(function (box) {
    var yen = function (n) { return '¥' + n.toLocaleString('ja-JP'); };
    var out = {
      name: $('[data-est-name]', box), total: $('[data-est-total]', box),
      fee: $('[data-est-fee]', box), rent: $('[data-est-rent]', box), rentRow: $('[data-est-rent-row]', box),
      rentLbl: $('[data-est-rent-label]', box), disc: $('[data-est-disc]', box), discRow: $('[data-est-disc-row]', box),
      barA: $('.est__bar .a', box), barB: $('.est__bar .b', box), deal: $('[data-est-deal]', box),
      line: $('[data-est-line]', box)
    };
    function calc() {
      var c = $('input[name="est-course"]:checked', box);
      var g = $('input[name="est-gear"]:checked', box);
      if (!c || !g) return;
      var fee = +c.getAttribute('data-fee');
      var days = +c.getAttribute('data-days');
      var disc = +(c.getAttribute('data-disc') || 0);
      var perDay = +box.getAttribute('data-rent-per-day');
      var rent = g.value === 'rent' ? days * perDay : 0;
      var total = fee + rent - disc;
      if (out.name) out.name.textContent = c.getAttribute('data-name');
      if (out.total) out.total.textContent = yen(total);
      if (out.fee) out.fee.textContent = yen(fee);
      if (out.rent) out.rent.textContent = rent ? yen(rent) : '¥0';
      if (out.rentLbl) out.rentLbl.textContent = rent ? 'レンタル器材（' + days + '日 × ' + yen(perDay) + '）' : 'レンタル器材（自分の器材を使う）';
      if (out.discRow) out.discRow.hidden = !disc;
      if (out.disc) out.disc.textContent = '−' + yen(disc);
      var gross = fee + rent;
      if (out.barA) out.barA.style.setProperty('--w', (fee / gross * 100).toFixed(2) + '%');
      if (out.barB) out.barB.style.setProperty('--w', (rent / gross * 100).toFixed(2) + '%');
      if (out.deal) {
        /* 夏割：LINEからの申込でレンタル1日分が無料（講習ごとに1回。セットは2回） */
        var n = +(c.getAttribute('data-deal-days') || 0);
        if (g.value === 'rent' && n) {
          out.deal.hidden = false;
          var b = $('b', out.deal);
          if (b) b.textContent = yen(total - n * perDay);
          var nd = $('[data-est-deal-days]', out.deal);
          if (nd) nd.textContent = n;
        } else {
          out.deal.hidden = true;
        }
      }
      if (out.line) out.line.setAttribute('data-line-msg', c.getAttribute('data-line-msg') || out.line.getAttribute('data-line-msg'));
    }
    box.addEventListener('change', calc);
    calc();
  });
})();
