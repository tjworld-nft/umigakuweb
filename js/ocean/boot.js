/* ==========================================================================
   三浦 海の学校 — ヒーロー海中レイヤーの起動ゲート
   --------------------------------------------------------------------------
   本体（three.js同梱・約200KB）を落とすかどうかをここだけで決める。
   落とさない場合は従来どおり写真＋CSSのヒーローがそのまま残るので、
   このファイルが何もしなくてもページは完成している。
   ========================================================================== */
(function () {
  'use strict';

  var SELF = document.currentScript && document.currentScript.src;
  if (!SELF) return;

  var hero = document.querySelector('[data-ocean]');
  if (!hero) return;

  /* --- 1. 明示的なオプトアウト（?ocean=off でも切れる） ------------------ */
  try {
    if (location.search.indexOf('ocean=off') !== -1) return;
  } catch (e) { /* noop */ }

  /* --- 2. アニメーションを減らす設定を尊重 ------------------------------ */
  var mq = window.matchMedia;
  if (!mq) return;
  if (mq('(prefers-reduced-motion: reduce)').matches) return;

  /* --- 3. 通信量の節約設定・低速回線では読み込まない --------------------- */
  var conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
  if (conn) {
    if (conn.saveData) return;
    var et = conn.effectiveType || '';
    if (et === 'slow-2g' || et === '2g' || et === '3g') return;
  }

  /* --- 4. 端末の体力（分からない環境は「あり」とみなす） ------------------ */
  var cores = navigator.hardwareConcurrency || 8;
  var mem = navigator.deviceMemory;          /* Chromium系のみ返る */
  if (cores <= 3) return;
  if (mem !== undefined && mem < 4) return;

  /* --- 5. 描画バックエンド ---------------------------------------------- */
  var hasWebGPU = !!navigator.gpu;
  var hasWebGL2 = false;
  try {
    hasWebGL2 = !!document.createElement('canvas').getContext('webgl2');
  } catch (e) { /* noop */ }
  if (!hasWebGPU && !hasWebGL2) return;

  /* WebGPUの無いタッチ端末（＝旧世代のスマホ）は写真のまま。
     WebGL2フォールバックはデスクトップ級にだけ許可する。 */
  var coarse = mq('(pointer: coarse)').matches;
  if (!hasWebGPU && coarse) return;

  /* --- 6. ヒーローが画面内にある間の、暇な時間に読み込む ------------------ */
  var started = false;
  function launch() {
    if (started) return;
    started = true;
    import(new URL('./ocean.min.js', SELF).href)
      .then(function (mod) {
        return mod.start({ hero: hero, webgpu: hasWebGPU, cores: cores, memory: mem });
      })
      .catch(function (err) {
        /* 失敗しても写真のヒーローが残るだけなので、静かに諦める */
        if (window.console && console.debug) console.debug('[ocean] 起動を見送りました:', err);
      });
  }

  function whenIdle() {
    if ('requestIdleCallback' in window) {
      requestIdleCallback(launch, { timeout: 2500 });
    } else {
      setTimeout(launch, 400);
    }
  }

  function afterLoad() {
    /* ヒーローが最初から画面外（＝途中まで復元スクロールされた等）なら、
       戻ってくるまで待つ。無駄なダウンロードをしない。 */
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) {
          io.disconnect();
          whenIdle();
        }
      }, { rootMargin: '200px' });
      io.observe(hero);
    } else {
      whenIdle();
    }
  }

  if (document.readyState === 'complete') afterLoad();
  else window.addEventListener('load', afterLoad, { once: true });
})();
