/* ==========================================================================
   三浦 海の学校 — サイト全体に敷く水の、起動ゲート
   --------------------------------------------------------------------------
   本体（three.js同梱・約190KB）を落とすかどうかをここだけで決める。
   落とさない場合は従来どおりの見た目がそのまま残るので、
   このファイルが何もしなくてもページは完成している。
   ========================================================================== */
(function () {
  'use strict';

  var SELF = document.currentScript && document.currentScript.src;
  if (!SELF) return;

  var host = document.querySelector('[data-ocean]');
  var hero = document.querySelector('[data-ocean-hero]');
  if (!host || !hero) return;

  /* --- 1. 明示的なオプトアウト（?ocean=off でも切れる） ------------------ */
  try {
    if (location.search.indexOf('ocean=off') !== -1) return;
  } catch (e) { /* noop */ }

  /* --- 2. アニメーションを減らす設定を尊重 ------------------------------ */
  var mq = window.matchMedia;
  if (!mq) return;
  if (mq('(prefers-reduced-motion: reduce)').matches) return;

  /* --- 3. 通信量の節約設定・本当に遅い回線では読み込まない ----------------
     effectiveType は直近の実測からの推定値で、十分速い回線でも '3g' と
     報告されることが多い（実際に本番で踏んだ）。ここで 3g を弾くと
     多くの実ユーザーで無効になってしまうので、2g 系だけを対象にする。
     本体は表示が終わって暇になってから取りに行くので、3g でも表示は妨げない。 */
  var conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
  if (conn) {
    if (conn.saveData) return;
    var et = conn.effectiveType || '';
    if (et === 'slow-2g' || et === '2g') return;
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

  /* --- 6. ヒーローが画面内にある間の、暇な時間に読み込む --------------------
     水はページ全体に効くが、いちばん効くのはヒーロー。
     すぐ下まで来ていない読者のために先回りしてダウンロードはしない。 */
  var started = false;
  function launch() {
    if (started) return;
    started = true;
    /* 本体にも、このファイルに付いていたのと同じ ?v= を付けて取りに行く。
       js/ocean/* は7日キャッシュされるので、これが無いと中身を差し替えても
       一度来たことのある人には古い本体が出続ける（実際に踏んだ）。 */
    var ver = '';
    try { ver = new URL(SELF, location.href).search; } catch (e) { /* noop */ }
    import(new URL('./ocean.min.js' + ver, SELF).href)
      .then(function (mod) {
        return mod.start({ host: host, hero: hero, webgpu: hasWebGPU, cores: cores, memory: mem });
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
