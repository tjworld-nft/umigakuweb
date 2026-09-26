/* ==========================================================================
   三浦 海の学校 — トップページの動き（2026-09 リニューアル）
   --------------------------------------------------------------------------
   ・JSが無くてもページは完成している（映像はポスター、コースは全部並んで見える）。
     ここに書くのは「あると気持ちいい」拡張だけ。
   ・動きを減らす設定／データ節約モードでは、自動再生の映像を一切読み込まない。
   ========================================================================== */
(function () {
  'use strict';

  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var mq = function (q) { return window.matchMedia ? window.matchMedia(q).matches : false; };
  var REDUCE = mq('(prefers-reduced-motion: reduce)');
  var conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
  var SAVE = !!(conn && (conn.saveData || /(^|-)2g$/.test(conn.effectiveType || '')));
  var CALM = REDUCE || SAVE;            /* 自動で動く映像を出さない */
  var root = document.documentElement;
  if (CALM) root.classList.add('no-motion');

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
     ========================================================================== */
  (function expire() {
    var today = ymd(jstNow());
    $$('[data-expires]').forEach(function (el) {
      if (today >= el.getAttribute('data-expires')) el.parentNode && el.parentNode.removeChild(el);
    });
    var t = jstNow();
    var days = Math.round((Date.UTC(2026, 8, 30) - Date.UTC(t.getFullYear(), t.getMonth(), t.getDate())) / 86400000);
    $$('[data-deadline-days]').forEach(function (w) {
      if (days < 0) { w.hidden = true; return; }
      var slot = $('.days', w);
      if (slot) slot.textContent = days === 0 ? '本日まで' : 'あと' + days + '日';
    });
  })();

  /* ==========================================================================
     2. トースト（「コピーしました」）
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

  /* ==========================================================================
     3. LINEボタン：押したら「送る文面」をコピーしてからLINEを開く
        （友だち追加の導線はそのまま。最初のひと言に迷わないように）
     ========================================================================== */
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
     4. シアター（映像を大きく、音ありで観る）
     ========================================================================== */
  var WIDE = function () { return window.innerWidth >= 1000 && !SAVE; };
  var FILMS = {
    pv: {
      title: '三浦 海の学校 プロモーション映像（1分38秒）',
      poster: 'image/home/pv-poster-1280.jpg',
      track: 'video/home/umigaku-pv-2026-ja.vtt?v=1',
      hi: [['video/home/umigaku-pv-2026-1080-hevc.mp4?v=1', 'video/mp4; codecs="hvc1.1.6.L120.90, mp4a.40.2"']],
      base: ['video/home/umigaku-pv-2026-720.mp4?v=1', 'video/mp4'],
      chapters: [['はじめから', 0], ['はじめての人へ', 27.3], ['ライセンス', 32.7], ['ブランク', 38.0], ['“迷子ダイバー”', 43.3], ['少人数制', 48.7], ['コースディレクター', 54.0], ['城ヶ島・宮川湾', 64.7]]
    },
    sites: {
      title: '東京から城ヶ島・宮川湾へ（38秒）',
      poster: 'image/home/sites-poster-1280.jpg',
      base: ['video/home/jogashima-miyagawa-720.mp4?v=1', 'video/mp4'],
      chapters: [['はじめから', 0], ['城ヶ島ビーチ', 12.32], ['宮川湾ボート', 19.52], ['城ヶ島ボート', 26.72]]
    },
    cm: {
      title: '三浦 海の学校の海（28秒）',
      vertical: true,
      poster: 'image/home/cm-poster-540.webp',
      base: ['video/home/umigaku-sea-cm-2026.mp4?v=1', 'video/mp4']
    }
  };

  var dlg = $('.theater');
  var tVideo = dlg && $('video', dlg);
  var tScreen = dlg && $('.theater__screen', dlg);
  var tTitle = dlg && $('#theater-title');
  var tChapters = dlg && $('.theater__chapters', dlg);
  var opener = null;
  var ambient = [];   /* 開いている間は止めておく、ページ内の自動再生映像 */

  function pauseAmbient() {
    ambient = $$('video.film-teaser, video.sea-video, video.phone-video').filter(function (v) { return !v.paused; });
    ambient.forEach(function (v) { v.pause(); });
  }
  function resumeAmbient() {
    ambient.forEach(function (v) { var p = v.play(); if (p && p.catch) p.catch(function () {}); });
    ambient = [];
  }

  function markChapter(t) {
    if (!tChapters) return;
    var btns = $$('button', tChapters), on = -1;
    btns.forEach(function (b, i) { if (t + 0.25 >= parseFloat(b.getAttribute('data-t'))) on = i; });
    btns.forEach(function (b, i) { b.classList.toggle('on', i === on); });
  }

  function openFilm(key, startAt, from) {
    var f = FILMS[key];
    if (!f || !dlg || typeof dlg.showModal !== 'function') return false;
    opener = from || document.activeElement;
    startAt = startAt || 0;

    /* 映像の差し替え（前の再生を完全に止めてから） */
    tVideo.pause();
    while (tVideo.firstChild) tVideo.removeChild(tVideo.firstChild);
    var frag = '#t=' + startAt.toFixed(2);
    var list = (WIDE() && f.hi) ? f.hi.concat([f.base]) : [f.base];
    list.forEach(function (s) {
      var el = document.createElement('source');
      el.src = s[0] + (startAt ? frag : ''); el.type = s[1];
      tVideo.appendChild(el);
    });
    if (f.track) {
      var tr = document.createElement('track');
      tr.kind = 'captions'; tr.srclang = 'ja'; tr.label = '日本語'; tr.src = f.track;
      tVideo.appendChild(tr);
    }
    tVideo.poster = f.poster;
    tVideo.muted = false;
    tTitle.textContent = f.title;
    dlg.classList.toggle('theater--vertical', !!f.vertical);

    tChapters.innerHTML = '';
    (f.chapters || []).forEach(function (c) {
      var b = document.createElement('button');
      b.type = 'button'; b.textContent = c[0]; b.setAttribute('data-t', c[1]);
      b.addEventListener('click', function () {
        try { tVideo.currentTime = c[1]; } catch (e) { /* noop */ }
        var p = tVideo.play(); if (p && p.catch) p.catch(function () {});
      });
      tChapters.appendChild(b);
    });
    tChapters.hidden = !(f.chapters && f.chapters.length);

    pauseAmbient();
    root.classList.add('is-theater');
    dlg.showModal();
    tVideo.load();
    if (startAt) {
      tVideo.addEventListener('loadedmetadata', function once() {
        tVideo.removeEventListener('loadedmetadata', once);
        if (Math.abs(tVideo.currentTime - startAt) > 0.5) { try { tVideo.currentTime = startAt; } catch (e) { /* noop */ } }
      });
    }
    /* クリックの流れの中で play() を呼ぶ（音ありの再生を許可してもらうため） */
    var p = tVideo.play();
    if (p && p.catch) p.catch(function () { /* 自動再生が止められたら、再生ボタンを押してもらう */ });

    /* 押した場所から、画面いっぱいへ広がる */
    if (!REDUCE && from && tScreen.animate) {
      var src = from.closest('.film-stage, .sea-screen, .phone') || from;
      var a = src.getBoundingClientRect(), b = tScreen.getBoundingClientRect();
      if (a.width > 40 && b.width > 0) {
        tScreen.animate([
          { transform: 'translate(' + (a.left - b.left) + 'px,' + (a.top - b.top) + 'px) scale(' + (a.width / b.width) + ',' + (a.height / b.height) + ')', opacity: .6 },
          { transform: 'none', opacity: 1 }
        ], { duration: 560, easing: 'cubic-bezier(.2,.8,.2,1)' });
      }
    }
    setTimeout(function () { var c = $('.theater__close', dlg); c && c.focus({ preventScroll: true }); }, 30);
    return true;
  }

  function closeFilm() {
    if (!dlg || !dlg.open) return;
    tVideo.pause();
    /* ダウンロードも止める */
    while (tVideo.firstChild) tVideo.removeChild(tVideo.firstChild);
    tVideo.removeAttribute('src');
    try { tVideo.load(); } catch (e) { /* noop */ }
    dlg.close();
    root.classList.remove('is-theater');
    resumeAmbient();
    if (opener && opener.focus) opener.focus({ preventScroll: true });
  }

  if (dlg) {
    $('.theater__close', dlg).addEventListener('click', closeFilm);
    dlg.addEventListener('cancel', function (e) { e.preventDefault(); closeFilm(); });
    dlg.addEventListener('click', function (e) { if (e.target === dlg) closeFilm(); });
    $$('[data-theater-close]', dlg).forEach(function (a) { a.addEventListener('click', closeFilm); });
    tVideo.addEventListener('timeupdate', function () { markChapter(tVideo.currentTime); });
  }

  document.addEventListener('click', function (e) {
    var b = e.target.closest && e.target.closest('[data-film]');
    if (!b) return;
    var ok = openFilm(b.getAttribute('data-film'), parseFloat(b.getAttribute('data-t') || '0'), b);
    if (ok) e.preventDefault();
  });

  /* ==========================================================================
     5. 動くポスター（PVのクライマックスを無音でループ）
     ========================================================================== */
  function lazyLoop(video, threshold) {
    if (!video || CALM || !('IntersectionObserver' in window)) return;
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting && !root.classList.contains('is-theater')) {
          if (!video.getAttribute('src')) video.src = video.getAttribute('data-src');
          var p = video.play(); if (p && p.catch) p.catch(function () {});
        } else if (!en.isIntersecting) {
          video.pause();
        }
      });
    }, { threshold: threshold || 0.25 });
    io.observe(video);
    video.addEventListener('playing', function () { video.classList.add('is-playing'); });
  }
  lazyLoop($('.film-teaser'), 0.3);

  /* ==========================================================================
     6. コース選び（タブ）— JSが無いときは4つとも並んで見える
     ========================================================================== */
  (function finder() {
    var tabs = $$('.finder-tab');
    if (!tabs.length) return;
    var wrap = $('#finder');
    wrap.classList.add('js-tabs');
    function select(tab, focus, fromUser) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        t.tabIndex = on ? 0 : -1;
        var panel = document.getElementById(t.getAttribute('aria-controls'));
        if (!panel) return;
        panel.hidden = !on;
        if (on) { panel.classList.remove('is-in'); void panel.offsetWidth; panel.classList.add('is-in'); }
      });
      if (focus) tab.focus();
      if (fromUser && window.innerWidth < 900) {
        var panel = document.getElementById(tab.getAttribute('aria-controls'));
        var r = panel.getBoundingClientRect();
        if (r.top > window.innerHeight * 0.75) panel.scrollIntoView({ behavior: REDUCE ? 'auto' : 'smooth', block: 'start' });
      }
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { select(t, false, true); });
      t.addEventListener('keydown', function (e) {
        var k = e.key, n = tabs.length, j = -1;
        if (k === 'ArrowRight' || k === 'ArrowDown') j = (i + 1) % n;
        else if (k === 'ArrowLeft' || k === 'ArrowUp') j = (i - 1 + n) % n;
        else if (k === 'Home') j = 0;
        else if (k === 'End') j = n - 1;
        if (j >= 0) { e.preventDefault(); select(tabs[j], true, false); }
      });
    });
    /* #finder-license のようなリンクで、その入口を開いた状態にできる */
    var m = /^#finder-(first|license|blank|fun)$/.exec(location.hash);
    select(m ? $('#tab-' + m[1]) : tabs[0], false, false);
  })();

  /* ==========================================================================
     7. 城ヶ島と宮川湾：スクロールで、空からの映像の章が切り替わる
        1本の映像（無音）を、章ごとの区間だけループ再生する
     ========================================================================== */
  (function sea() {
    var sec = $('#sea');
    if (!sec) return;
    var screen = $('.sea-screen', sec);
    var video = $('.sea-video', sec);
    var still = $('[data-sea-still]', sec);
    var steps = $$('.sea-step', sec);
    var dots = $$('.sea-dots span', sec);
    var bar = $('.sea-bar i', sec);
    if (!steps.length) return;
    root.classList.add('js-sea');

    var active = null, visible = false, near = false, raf = 0;
    var useVideo = !CALM && video && 'IntersectionObserver' in window;

    /* 章の区間の頭から、無音でループ再生する（見えているときだけ） */
    function run() {
      if (!useVideo || !near || !active) return;
      if (!video.getAttribute('src')) video.src = video.getAttribute('data-src');
      if (!visible || root.classList.contains('is-theater')) { video.pause(); return; }
      var p = video.play(); if (p && p.catch) p.catch(function () {});
      loop();
    }

    function seg(step) {
      return { start: parseFloat(step.getAttribute('data-start')), end: parseFloat(step.getAttribute('data-end')) };
    }

    function tick() {
      raf = 0;
      if (!active || !visible || video.paused) return;
      var s = seg(active), t = video.currentTime;
      if (t >= s.end - 0.06 || t < s.start - 0.3) {
        try { video.currentTime = s.start; } catch (e) { /* noop */ }
      }
      if (bar) bar.style.width = Math.max(0, Math.min(1, (t - s.start) / (s.end - s.start))) * 100 + '%';
      raf = requestAnimationFrame(tick);
    }
    function loop() { if (!raf) raf = requestAnimationFrame(tick); }

    function activate(step) {
      if (active === step) return;
      active = step;
      var idx = steps.indexOf(step);
      steps.forEach(function (s) { s.classList.toggle('is-active', s === step); });
      dots.forEach(function (d, i) { d.classList.toggle('on', i === idx); });
      if (still) still.src = step.getAttribute('data-still');
      if (!useVideo || !video.getAttribute('src')) return;
      try { video.currentTime = seg(step).start; } catch (e) { /* noop */ }
      run();
    }

    if (useVideo) {
      video.addEventListener('playing', function () { screen.classList.add('is-video'); loop(); });
      /* シーク直後に止まったままになることがあるので、シークが終わったら再生を念押し */
      video.addEventListener('seeked', function () { if (visible && video.paused) run(); });
      video.addEventListener('loadedmetadata', function () {
        if (active) { try { video.currentTime = seg(active).start; } catch (e) { /* noop */ } }
        run();
      });
      /* 近づいたら読み込み開始（それまでは1バイトも取りに行かない） */
      new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting && !near) { near = true; run(); }
      }, { rootMargin: '700px 0px' }).observe(screen);
      new IntersectionObserver(function (entries) {
        visible = entries[0].isIntersecting;
        run();
      }, { threshold: 0.15 }).observe(screen);
    }

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) { if (en.isIntersecting) activate(en.target); });
      }, { rootMargin: '-42% 0px -42% 0px' });
      steps.forEach(function (s) { io.observe(s); });
    }
    activate(steps[0]);
  })();

  /* ==========================================================================
     8. スマホの画面に流れるSNS用の映像
     ========================================================================== */
  (function phone() {
    var v = $('.phone-video');
    var btn = $('.phone__sound');
    if (!v) return;
    lazyLoop(v, 0.4);
    if (!btn) return;
    btn.addEventListener('click', function () {
      if (!v.getAttribute('src')) v.src = v.getAttribute('data-src');
      v.muted = !v.muted;
      if (!v.muted) { v.currentTime = 0; var p = v.play(); if (p && p.catch) p.catch(function () {}); v.classList.add('is-playing'); }
      btn.setAttribute('aria-pressed', v.muted ? 'false' : 'true');
      btn.setAttribute('aria-label', v.muted ? '音を出す' : '音を消す');
      $('use', btn).setAttribute('href', v.muted ? '#i-sound-off' : '#i-sound-on');
    });
  })();

  /* ==========================================================================
     8.5 写真の帯：横に流れる写真は、画面の外（横）にある間は遅延読み込みが働かない。
         帯が近づいたら、まとめて読み込んでおく（途中で白く抜けないように）
     ========================================================================== */
  (function marquee() {
    var m = $('.marquee');
    if (!m) return;
    var go = function () { $$('img', m).forEach(function (i) { i.loading = 'eager'; }); };
    if (!('IntersectionObserver' in window)) return go();
    var io = new IntersectionObserver(function (en) { if (en[0].isIntersecting) { io.disconnect(); go(); } }, { rootMargin: '900px 0px' });
    io.observe(m);
  })();

  /* ==========================================================================
     9. Facebook の投稿は、押したときだけ読み込む（重い・追跡されるため）
     ========================================================================== */
  (function fb() {
    var b = $('.fb-load');
    if (!b) return;
    b.addEventListener('click', function () {
      var f = document.createElement('iframe');
      f.src = 'https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fmiuraumigaku%2F&tabs=timeline&width=460&height=520&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false';
      f.title = 'Facebookページ「三浦 海の学校」の最新投稿';
      f.loading = 'lazy';
      f.setAttribute('allow', 'encrypted-media; picture-in-picture; web-share');
      b.parentNode.replaceChild(f, b);
    });
  })();

  /* ==========================================================================
     10. 今週のダイビングコンディション（Open-Meteo）
         判定の考え方は以前のトップと同じ（午前9〜13時の風向・風速）
     ========================================================================== */
  (function condition() {
    var box = $('#cond-cards');
    if (!box) return;
    var action = $('.cond-action');
    var LAT = 35.1547, LON = 139.6128;
    var W = 'https://api.open-meteo.com/v1/forecast?latitude=' + LAT + '&longitude=' + LON + '&daily=weathercode,temperature_2m_max,temperature_2m_min,precipitation_probability_max&hourly=windspeed_10m,winddirection_10m&timezone=Asia/Tokyo&forecast_days=7';
    var M = 'https://marine-api.open-meteo.com/v1/marine?latitude=' + LAT + '&longitude=' + LON + '&daily=wave_height_max&timezone=Asia/Tokyo&forecast_days=7';
    var ICON = { 0: '☀️', 1: '🌤', 2: '⛅', 3: '☁️', 45: '🌫', 48: '🌫', 51: '🌦', 53: '🌦', 55: '🌧', 56: '🌧', 57: '🌧', 61: '🌧', 63: '🌧', 65: '🌧', 66: '🌧', 67: '🌧', 71: '🌨', 73: '🌨', 75: '🌨', 77: '🌨', 80: '🌦', 81: '🌧', 82: '🌧', 85: '🌨', 86: '🌨', 95: '⛈', 96: '⛈', 99: '⛈' };
    var WX = { 0: '快晴', 1: '晴れ', 2: '晴れ時々くもり', 3: 'くもり', 45: '霧', 48: '霧', 51: '小雨', 53: '小雨', 55: '雨', 61: '雨', 63: '雨', 65: '強い雨', 80: 'にわか雨', 81: 'にわか雨', 82: '強いにわか雨', 95: '雷雨', 96: '雷雨', 99: '雷雨' };
    var DOW = ['日', '月', '火', '水', '木', '金', '土'];
    var DIRS = ['N', 'NE', 'NE', 'E', 'E', 'SE', 'SE', 'S', 'S', 'SW', 'SW', 'W', 'W', 'NW', 'NW', 'N'];
    var JA = ['北', '北北東', '北東', '東北東', '東', '東南東', '南東', '南南東', '南', '南南西', '南西', '西南西', '西', '西北西', '北西', '北北西'];

    function morning(h, i) {
      var max = 0, sum = 0, c = 0;
      for (var k = i * 24 + 9; k <= i * 24 + 13; k++) {
        var ws = h.windspeed_10m[k], wd = h.winddirection_10m[k];
        if (ws != null && wd != null) { if (ws > max) max = ws; sum += wd; c++; }
      }
      return { ws: max, wd: c ? Math.round(sum / c) : 0 };
    }
    function judge(kmh, deg) {
      var ms = kmh / 3.6, d = DIRS[Math.round(deg / 22.5) % 16];
      if (['N', 'NE', 'E', 'NW'].indexOf(d) >= 0) return ['best', '◎', '良好'];
      if (d === 'W') { if (ms <= 4) return ['best', '◎', '良好']; if (ms <= 7) return ['good', '○', 'OK']; return ['ng', '×', '厳しい']; }
      if (ms <= 4) return ['best', '◎', '良好']; if (ms <= 7) return ['good', '○', 'OK']; if (ms <= 9) return ['caution', '△', '注意'];
      return ['ng', '×', '厳しい'];
    }

    function pick(btn) {
      $$('.cond-card', box).forEach(function (b) { b.setAttribute('aria-pressed', b === btn ? 'true' : 'false'); });
      if (!action) return;
      var label = btn.getAttribute('data-label'), iso = btn.getAttribute('data-iso');
      $('[data-cond-label]', action).textContent = label;
      var line = $('.btn-line', action);
      line.setAttribute('data-line-msg', btn.getAttribute('data-day') + 'に参加したいです。空きはありますか？');
      $('[data-cond-form]', action).href = '/contact/?from=home-condition&date=' + iso;
      action.hidden = false;
    }

    function render(w, m) {
      var today = ymd(jstNow()), html = '';
      for (var i = 0; i < 7; i++) {
        var iso = w.daily.time[i];
        var dow = new Date(iso + 'T12:00:00Z').getUTCDay();
        var mm = parseInt(iso.slice(5, 7), 10), dd = parseInt(iso.slice(8, 10), 10);
        var code = w.daily.weathercode[i];
        var hi = Math.round(w.daily.temperature_2m_max[i]), lo = Math.round(w.daily.temperature_2m_min[i]);
        var mw = morning(w.hourly, i), ms = mw.ws / 3.6;
        var wave = m && m.daily && m.daily.wave_height_max ? m.daily.wave_height_max[i] : null;
        var j = judge(mw.ws, mw.wd);
        var dayTxt = mm + '月' + dd + '日(' + DOW[dow] + ')';
        var cls = dow === 0 ? 'sun' : dow === 6 ? 'sat' : '';
        html += '<button type="button" class="cond-card" aria-pressed="false" data-iso="' + iso + '" data-day="' + dayTxt + '"' +
          ' data-label="' + dayTxt + ' ' + j[1] + ' ' + j[2] + '（' + (WX[code] || '') + '・' + JA[Math.round(mw.wd / 22.5) % 16] + 'の風 ' + ms.toFixed(1) + 'm/s）"' +
          ' aria-label="' + dayTxt + '、判定 ' + j[2] + '。この日の空きを聞く">' +
          '<span class="d">' + (iso === today ? 'TODAY' : '') + '</span>' +
          '<span class="date ' + cls + '">' + mm + '/' + dd + '(' + DOW[dow] + ')</span>' +
          '<span class="ic" aria-hidden="true">' + (ICON[code] || '🌤') + '</span>' +
          '<span class="t">' + hi + '° / ' + lo + '°</span>' +
          '<span class="w">' + JA[Math.round(mw.wd / 22.5) % 16] + ' ' + ms.toFixed(1) + 'm/s' + (wave != null ? '<br>波 ' + wave.toFixed(1) + 'm' : '') + '</span>' +
          '<span class="rank rank-' + j[0] + '" aria-hidden="true">' + j[1] + '</span>' +
          '<span class="rl">' + j[2] + '</span></button>';
      }
      box.innerHTML = html;
      $$('.cond-card', box).forEach(function (b) { b.addEventListener('click', function () { pick(b); }); });
    }

    function load() {
      Promise.all([
        fetch(W).then(function (r) { return r.json(); }),
        fetch(M).then(function (r) { return r.json(); }).catch(function () { return null; })
      ]).then(function (res) { render(res[0], res[1]); })
        .catch(function () { box.innerHTML = '<p class="cond-loading">天気データを取得できませんでした。海況はLINEでお気軽にお尋ねください。</p>'; });
    }
    /* 見えそうになってから取りに行く（最初の表示を軽く） */
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (en) {
        if (en[0].isIntersecting) { io.disconnect(); load(); }
      }, { rootMargin: '600px 0px' });
      io.observe(box);
    } else { load(); }
  })();

  /* ==========================================================================
     11. 右端の水深計 ＋ スマホ下部のボタンの出し入れ
     ========================================================================== */
  (function depth() {
    var gauge = $('.depth');
    var read = gauge && $('[data-depth]', gauge);
    var links = gauge ? $$('a', gauge) : [];
    var targets = links.map(function (a) { return document.getElementById(a.getAttribute('href').slice(1)); });
    var hero = $('.hero');
    var bar = $('.mobile-cta-bar');
    var dark = [$('#film'), $('#contact'), $('.home-manga'), $('.site-footer'), $('.more-card--app')].filter(Boolean);
    var ticking = false;

    function update() {
      ticking = false;
      var y = window.scrollY || window.pageYOffset;
      var vh = window.innerHeight;
      var max = Math.max(1, document.documentElement.scrollHeight - vh);
      var heroBottom = hero ? hero.getBoundingClientRect().bottom : 0;

      if (bar) bar.classList.toggle('is-hidden', heroBottom > vh * 0.35);
      if (!gauge) return;
      gauge.classList.toggle('is-on', heroBottom < vh * 0.6);
      if (read) read.textContent = (Math.min(1, y / max) * 18).toFixed(1);

      var line = vh * 0.4, on = 0;
      targets.forEach(function (t, i) { if (t && t.getBoundingClientRect().top <= line) on = i; });
      links.forEach(function (a, i) { a.classList.toggle('on', i === on); if (i === on) a.setAttribute('aria-current', 'location'); else a.removeAttribute('aria-current'); });

      /* 濃い色の帯の上では、目盛りを白に */
      var mid = vh / 2, isDark = dark.some(function (s) { var r = s.getBoundingClientRect(); return r.top < mid && r.bottom > mid; });
      gauge.classList.toggle('is-dark', isDark);
    }
    function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(update); } }
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
  })();
})();
