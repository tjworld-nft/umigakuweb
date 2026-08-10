/* ============================================================
   まんがビューア
   .mr-page（各ページ）をクリック/タップすると全画面で開く。
   ・← → / スワイプ でページ送り
   ・タップ（画像）または ＋ で 1x → 2x → 3.2x と拡大、ドラッグで移動
   ・Esc / × で閉じる。閉じたときは読んでいたページまでスクロールを戻す
   JSが無くてもページ画像はそのまま縦に読めるので、これは拡張機能。
   ============================================================ */
(function () {
  'use strict';

  var pages = Array.prototype.slice.call(document.querySelectorAll('.mr-page'));
  if (!pages.length) return;

  var ZOOMS = [1, 2, 3.2];
  var index = 0;
  var zoomStep = 0;
  var tx = 0, ty = 0;
  var dragging = false, moved = false, startX = 0, startY = 0, baseX = 0, baseY = 0;
  var swipeX = 0, swipeY = 0, swipeTime = 0;

  /* ---------- ビューアのDOMを作る ---------- */
  var viewer = document.createElement('div');
  viewer.className = 'mv';
  viewer.setAttribute('role', 'dialog');
  viewer.setAttribute('aria-modal', 'true');
  viewer.setAttribute('aria-label', 'まんがの拡大表示');
  viewer.innerHTML =
    '<div class="mv__stage">' +
    '  <img class="mv__img" alt="">' +
    '  <p class="mv__hint">タップで拡大／左右にスワイプでページ送り</p>' +
    '  <button class="mv__btn mv__close" type="button" aria-label="閉じる">✕</button>' +
    '</div>' +
    '<div class="mv__bar">' +
    '  <button class="mv__btn" type="button" data-act="prev" aria-label="前のページ">‹</button>' +
    '  <span class="mv__count" aria-live="polite"></span>' +
    '  <button class="mv__btn" type="button" data-act="next" aria-label="次のページ">›</button>' +
    '  <button class="mv__btn" type="button" data-act="zoom" aria-label="拡大する">＋</button>' +
    '</div>';
  document.body.appendChild(viewer);

  var stage = viewer.querySelector('.mv__stage');
  var img = viewer.querySelector('.mv__img');
  var hint = viewer.querySelector('.mv__hint');
  var count = viewer.querySelector('.mv__count');
  var btnPrev = viewer.querySelector('[data-act="prev"]');
  var btnNext = viewer.querySelector('[data-act="next"]');
  var btnZoom = viewer.querySelector('[data-act="zoom"]');

  /* ---------- 表示 ---------- */
  function applyTransform() {
    var z = ZOOMS[zoomStep];
    img.style.transform = 'translate(' + tx + 'px,' + ty + 'px) scale(' + z + ')';
    img.classList.toggle('is-zoomed', zoomStep > 0);
    btnZoom.textContent = zoomStep === ZOOMS.length - 1 ? '－' : '＋';
    btnZoom.setAttribute('aria-label', zoomStep === ZOOMS.length - 1 ? '拡大を戻す' : '拡大する');
  }

  function resetZoom() {
    zoomStep = 0; tx = 0; ty = 0;
    applyTransform();
  }

  function show(i) {
    index = Math.max(0, Math.min(pages.length - 1, i));
    var src = pages[index].querySelector('img');
    img.src = src.currentSrc || src.src;
    img.alt = src.alt || (index + 1) + 'ページ目';
    count.textContent = (index + 1) + ' / ' + pages.length;
    btnPrev.disabled = index === 0;
    btnNext.disabled = index === pages.length - 1;
    resetZoom();
  }

  function open(i) {
    show(i);
    viewer.classList.add('is-open');
    document.body.classList.add('mv-open');
    hint.classList.remove('is-hidden');
    setTimeout(function () { hint.classList.add('is-hidden'); }, 2600);
    viewer.querySelector('.mv__close').focus();
  }

  function close() {
    viewer.classList.remove('is-open');
    document.body.classList.remove('mv-open');
    img.removeAttribute('src');
    var target = pages[index];
    if (target) {
      var top = target.getBoundingClientRect().top + window.pageYOffset - 80;
      window.scrollTo({ top: top, behavior: 'auto' });
      target.focus({ preventScroll: true });
    }
  }

  function step(d) {
    if (index + d < 0 || index + d > pages.length - 1) return;
    show(index + d);
  }

  function cycleZoom() {
    zoomStep = (zoomStep + 1) % ZOOMS.length;
    tx = 0; ty = 0;
    applyTransform();
  }

  /* ---------- 起動 ---------- */
  pages.forEach(function (el, i) {
    el.addEventListener('click', function () { open(i); });
  });

  /* ---------- 操作 ---------- */
  viewer.querySelector('.mv__close').addEventListener('click', close);
  btnPrev.addEventListener('click', function () { step(-1); });
  btnNext.addEventListener('click', function () { step(1); });
  btnZoom.addEventListener('click', cycleZoom);

  stage.addEventListener('click', function (e) {
    if (e.target === stage) close();
  });

  document.addEventListener('keydown', function (e) {
    if (!viewer.classList.contains('is-open')) return;
    if (e.key === 'Escape') { close(); }
    else if (e.key === 'ArrowLeft') { step(-1); }
    else if (e.key === 'ArrowRight') { step(1); }
    else if (e.key === ' ') { e.preventDefault(); cycleZoom(); }
  });

  /* ---------- ドラッグで移動 / タップで拡大 / スワイプでページ送り ---------- */
  img.addEventListener('pointerdown', function (e) {
    dragging = true; moved = false;
    startX = e.clientX; startY = e.clientY;
    baseX = tx; baseY = ty;
    swipeX = e.clientX; swipeY = e.clientY; swipeTime = Date.now();
    img.setPointerCapture(e.pointerId);
  });

  img.addEventListener('pointermove', function (e) {
    if (!dragging) return;
    var dx = e.clientX - startX;
    var dy = e.clientY - startY;
    if (Math.abs(dx) > 6 || Math.abs(dy) > 6) moved = true;
    if (zoomStep > 0) {
      tx = baseX + dx;
      ty = baseY + dy;
      img.style.transform = 'translate(' + tx + 'px,' + ty + 'px) scale(' + ZOOMS[zoomStep] + ')';
    }
  });

  img.addEventListener('pointerup', function (e) {
    if (!dragging) return;
    dragging = false;
    var dx = e.clientX - swipeX;
    var dy = e.clientY - swipeY;
    var quick = Date.now() - swipeTime < 600;

    if (!moved) { cycleZoom(); return; }
    // 拡大していないときだけ、横スワイプをページ送りにする
    if (zoomStep === 0 && quick && Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy) * 1.4) {
      step(dx < 0 ? 1 : -1);
    }
  });

  img.addEventListener('pointercancel', function () { dragging = false; });
  img.addEventListener('dragstart', function (e) { e.preventDefault(); });
})();
