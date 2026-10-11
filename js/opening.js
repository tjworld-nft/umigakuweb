/* ==========================================================================
   三浦 海の学校 — トップのオープニング「水面へ」
   --------------------------------------------------------------------------
   深い海から水面へ上がり、顔を出すと、そこがサイト（三浦の海の写真）。

   ・映像は video/opening/（絵＝gpt-image-2.5、動き＝MiniMax H3 Max Turbo、音＝ElevenLabs）。
     実写ではなくイメージ映像なので、画面の隅に「イメージ映像」と出す。
   ・水面の線、流れ落ちる水の膜、レンズに残る水滴は WebGL でその場で描く。
     線より上は透明で、下にある本物のページがそのまま見える（写真は1ピクセルも作り変えない）。
   ・出す／出さないは index.html の <head> の小さなスクリプトが決める（html に class="op"）。
     このファイルが読めなくても、<head> の CSS が5秒で自分から消える。
   ・音あり版は [data-opening="sound"] のボタンから（押した人にだけ・音は別ファイル）。
   ・映像・音・静止画を差し替えたら下の VER を上げる（サーバーが7日キャッシュする）。
   ========================================================================== */
(function () {
  'use strict';

  var W = window, D = document, H = D.documentElement;
  if (W.umigakuOpening) return;

  var SELF = D.currentScript && D.currentScript.src;
  var ROOT = '';
  try { ROOT = SELF ? new URL('../', SELF).href : ''; } catch (e) { /* noop */ }
  var VER = '?v=1';
  var running = false, lastAudio = null;

  /* 確認用：?opfreeze=1.9 のように秒を付けると、その瞬間で止めて見られる（押せば消える）。実機のSafariで絵を確かめるための口 */
  try {
    var fz = /[?&]opfreeze=([\d.]+)/.exec(location.search);
    if (fz) W.__opT = +fz[1];
  } catch (e) { /* noop */ }

  function asset(p) { return ROOT + p + VER; }
  function now() { return performance.now(); }
  function clamp(x, a, b) { return x < a ? a : x > b ? b : x; }
  function sstep(a, b, x) { x = clamp((x - a) / (b - a), 0, 1); return x * x * (3 - 2 * x); }
  /* 水面の線の降り方：画面の外にある最初だけ速く、あとはほぼ一定で、最後に少しゆるむ */
  function lineEase(x) { x = clamp(x, 0, 1); return x * (1.25 - 0.25 * x); }
  function portrait() { return W.innerHeight >= W.innerWidth; }

  /* 進行表（秒）。short＝自動で流す2.2秒／long＝音あり6.4秒／still＝動画を再生できない端末（静止画2枚で同じ流れ） */
  var TIMELINES = {
    short: { copyOut: 0.85, glow: [1.20, 1.70], wipe: [1.50, 2.28], peak: 1.30, peakW: 0.38, end: 3.20 },
    long:  { copyOut: 2.40, glow: [4.70, 5.45], wipe: [5.30, 6.15], peak: 3.70, peakW: 1.00, end: 7.20 },
    still: { copyOut: 0.55, glow: [0.80, 1.25], wipe: [1.12, 1.90], peak: 0.80, peakW: 0.40, end: 2.80 }
  };

  var CSS =
    '.op-root{position:fixed;inset:0;z-index:2147483001;overflow:hidden;contain:strict;cursor:pointer;' +
      '-webkit-tap-highlight-color:transparent;touch-action:manipulation;background:rgba(5,42,69,0)}' +
    '.op-root.bg{background:#052a45}' +
    '.op-root.fade{animation:op-rootin .25s linear both}' +
    '.op-cv{position:absolute;inset:0;width:100%;height:100%;display:block;opacity:0}' +
    '.op-vid{position:absolute;left:0;top:0;width:2px;height:2px;opacity:0;pointer-events:none}' +
    '.op-copy{position:absolute;left:0;right:0;top:38%;text-align:center;color:#fff;pointer-events:none;' +
      'font-family:"Hiragino Mincho ProN","Yu Mincho",YuMincho,"Noto Serif JP",serif;' +
      'text-shadow:0 0 26px rgba(0,70,130,.75),0 2px 12px rgba(0,25,55,.6);' +
      'transition:transform .6s cubic-bezier(.55,0,.9,.45),opacity .42s linear}' +
    '.op-line{display:block;font-size:clamp(27px,7.6vw,60px);font-weight:500;letter-spacing:.3em;text-indent:.3em;line-height:1.3}' +
    '.op-line i{display:inline-block;font-style:normal;opacity:0;transform:translateY(.45em);' +
      'animation:op-in .7s cubic-bezier(.2,.7,.2,1) forwards;animation-delay:calc(var(--i)*65ms + 60ms)}' +
    '.op-brand{display:block;margin-top:1.3em;font:600 11.5px/1 "Montserrat","Helvetica Neue",Arial,sans-serif;' +
      'letter-spacing:.46em;text-indent:.46em;opacity:0;animation:op-fade .9s .55s forwards}' +
    '.op-root.out .op-copy{transform:translateY(42vh) scale(.94);opacity:0}' +
    '.op-root.fin .op-copy{opacity:0;transition:transform .6s cubic-bezier(.55,0,.9,.45),opacity .16s linear}' +
    '.op-skip,.op-note{position:absolute;color:#fff;font:600 12px/1 "Zen Maru Gothic","Hiragino Maru Gothic ProN",sans-serif;' +
      'letter-spacing:.08em;text-shadow:0 1px 4px rgba(0,25,55,.7);transition:opacity .3s linear}' +
    '.op-skip{right:max(14px,env(safe-area-inset-right));bottom:max(20px,env(safe-area-inset-bottom));padding:.75em 1.15em;' +
      'border:1px solid rgba(255,255,255,.55);border-radius:99px;background:rgba(4,34,58,.28);cursor:pointer;' +
      '-webkit-appearance:none;appearance:none;opacity:0;animation:op-fade .5s .35s forwards}' +
    '.op-skip:focus-visible{outline:2px solid #fff;outline-offset:3px}' +
    '.op-note{left:max(16px,env(safe-area-inset-left));bottom:max(31px,calc(env(safe-area-inset-bottom) + 11px));' +
      'font-size:10.5px;font-weight:400;opacity:.72;pointer-events:none}' +
    '.op-root.fin .op-skip,.op-root.fin .op-note{opacity:0!important;animation:none}' +
    'html.op-bye::before{transition:opacity .45s linear;opacity:0}' +
    '@keyframes op-in{to{opacity:1;transform:none}}' +
    '@keyframes op-fade{to{opacity:1}}' +
    '@keyframes op-rootin{from{opacity:0}}';

  var VS = 'attribute vec2 aPos;varying vec2 vUv;void main(){vUv=aPos*0.5+0.5;gl_Position=vec4(aPos,0.0,1.0);}';

  /* 画面の座標は下が0・上が1。uLine が水面の高さで、1.3（画面の上の外）から下へ降りていく。
     線より下＝水の中（映像）、線より上＝水の上（透明＝ページ）。色は最後までアルファ乗算済みで持つ。 */
  var FS = [
    '#ifdef GL_FRAGMENT_PRECISION_HIGH',
    'precision highp float;',
    '#else',
    'precision mediump float;',
    '#endif',
    'uniform sampler2D uTex,uTex2;',
    'uniform vec2 uRes,uTexRes,uTex2Res;',
    'uniform float uTime,uLine,uMix,uZoom,uSpeed,uFade,uGlow,uPx;',
    'varying vec2 vUv;',
    'float hash(float n){return fract(sin(n*127.1)*43758.5453);}',
    'float vn(float x){float i=floor(x),f=fract(x);f=f*f*(3.0-2.0*f);return mix(hash(i),hash(i+1.0),f);}',
    /* object-fit: cover と同じ切り取り */
    'vec2 cover(vec2 uv,vec2 tr){float rs=uRes.x/uRes.y,rt=tr.x/tr.y;',
    '  vec2 s=rs>rt?vec2(1.0,rt/rs):vec2(rs/rt,1.0);return (uv-0.5)*s+0.5;}',
    'vec3 sea(vec2 uv){',
    '  vec2 c=vec2(0.5,0.80);vec2 d=uv-c;',
    '  vec2 u=cover(c+d/uZoom,uTexRes);',
    '  float ca=uSpeed*0.016*dot(d,d);',                       /* 速いほど外側で色がにじむ */
    '  vec3 col=vec3(texture2D(uTex,u+d*ca).r,texture2D(uTex,u).g,texture2D(uTex,u-d*ca).b);',
    '  if(uMix>0.001){col=mix(col,texture2D(uTex2,cover(c+d/(1.0+(uZoom-1.0)*0.35),uTex2Res)).rgb,uMix);}',
    '  return col;}',
    'void main(){',
    '  vec2 uv=vUv;float asp=uRes.x/uRes.y;float x=uv.x*asp;',
    '  float wave=0.020*sin(x*5.3+uTime*6.5)+0.011*sin(x*11.7-uTime*10.0)+0.018*(vn(x*2.7+uTime*1.9)-0.5);',
    '  float h=uLine+wave+0.06*(uv.x-0.5);',
    '  float d=uv.y-h;',
    /* 水の中：線のすぐ下は像が縦に縮み、水面の裏が鏡のように明るい */
    '  float near=smoothstep(-0.14,0.0,d);',
    '  vec2 suv=vec2(uv.x+0.0035*sin(uv.y*46.0+uTime*7.0)*near,uv.y-near*near*0.05);',
    '  vec3 col=sea(suv);',
    '  vec2 q=uv-0.5;col*=1.0-0.55*dot(q,q);',
    '  col+=uGlow*vec3(0.62,0.86,1.0);',
    '  col=mix(col,vec3(0.88,0.98,1.0),near*near*0.7);',
    '  float a=step(d,0.0);',
    /* 水の上：流れ落ちていく薄い水の膜（筋になって切れる） */
    '  float film=(1.0-smoothstep(0.0,0.13,max(d,0.0)))*step(0.0,d);',
    '  float st=0.35+0.65*vn(x*31.0)*(0.6+0.4*vn(x*7.0+5.0));',
    '  float fa=film*film*st*0.6;',
    '  vec3 oc=col*a+vec3(0.93,0.985,1.0)*fa*(1.0-a);',
    '  float oa=a+fa*(1.0-a);',
    /* レンズに残る水滴：線が通り過ぎた所に出て、少し滑って消える */
    '  for(int i=0;i<6;i++){',
    '    float fi=float(i);',
    '    vec2 c=vec2((0.08+0.84*hash(fi*3.1+1.0))*asp,0.14+0.76*hash(fi*5.7+2.0));',
    '    float r=0.009+0.015*hash(fi*9.3+4.0);',
    '    float age=c.y-uLine;',
    '    float on=smoothstep(0.03,0.09,age)*(1.0-smoothstep(0.45,1.0,age));',
    '    c.y-=max(age,0.0)*0.06*(0.4+hash(fi*2.2));',
    '    vec2 p=(vec2(x,uv.y)-c)/r;p.y*=0.88;float dist=length(p);',
    '    float body=1.0-smoothstep(0.86,1.0,dist);',
    '    float edge=smoothstep(0.45,0.95,dist)*body;',
    '    float lo=smoothstep(-0.1,0.9,-p.y);float hi=smoothstep(-0.1,0.9,p.y);',
    '    vec2 sp=p-vec2(-0.34,0.38);',
    '    float spec=exp(-dot(sp,sp)/0.035)*body;',
    '    float dk=clamp(on*edge*hi*0.26,0.0,1.0);',               /* 上の縁は暗く */
    '    oc*=1.0-dk;oa=dk+oa*(1.0-dk);',
    '    float da=clamp(on*(0.05*body+0.42*edge*lo+0.9*spec),0.0,1.0);',   /* 下の縁と光の点は明るく */
    '    oc=vec3(da)+oc*(1.0-da);oa=da+oa*(1.0-da);',
    '  }',
    /* 水面の線そのもの（白い縁）。画面の外にある間は出さない */
    '  float k=exp(-abs(d)*uRes.y/(2.2*uPx))*0.94;',
    '  oc=vec3(k)+oc*(1.0-k);oa=k+oa*(1.0-k);',
    '  gl_FragColor=vec4(oc,oa)*uFade;',
    '}'
  ].join('\n');

  function injectCss() {
    if (D.getElementById('op-css')) return;
    var s = D.createElement('style');
    s.id = 'op-css';
    s.textContent = CSS;
    D.head.appendChild(s);
  }

  function makeGL(canvas) {
    var gl = canvas.getContext('webgl', {
      alpha: true, premultipliedAlpha: true, antialias: false, depth: false, stencil: false,
      powerPreference: 'high-performance'
    });
    if (!gl) return null;
    function sh(type, src) {
      var s = gl.createShader(type);
      gl.shaderSource(s, src);
      gl.compileShader(s);
      if (!gl.getShaderParameter(s, gl.COMPILE_STATUS)) throw new Error(gl.getShaderInfoLog(s) || 'shader');
      return s;
    }
    var p = gl.createProgram();
    gl.attachShader(p, sh(gl.VERTEX_SHADER, VS));
    gl.attachShader(p, sh(gl.FRAGMENT_SHADER, FS));
    gl.linkProgram(p);
    if (!gl.getProgramParameter(p, gl.LINK_STATUS)) throw new Error(gl.getProgramInfoLog(p) || 'link');
    gl.useProgram(p);
    gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    var loc = gl.getAttribLocation(p, 'aPos');
    gl.enableVertexAttribArray(loc);
    gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);
    var U = {};
    ['uTex', 'uTex2', 'uRes', 'uTexRes', 'uTex2Res', 'uTime', 'uLine', 'uMix', 'uZoom', 'uSpeed', 'uFade', 'uGlow', 'uPx']
      .forEach(function (n) { U[n] = gl.getUniformLocation(p, n); });
    gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
    function tex(unit) {
      var t = gl.createTexture();
      gl.activeTexture(gl.TEXTURE0 + unit);
      gl.bindTexture(gl.TEXTURE_2D, t);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
      return t;
    }
    tex(0); tex(1);
    gl.uniform1i(U.uTex, 0);
    gl.uniform1i(U.uTex2, 1);
    gl.disable(gl.DEPTH_TEST);
    gl.disable(gl.BLEND);
    gl.clearColor(0, 0, 0, 0);
    return {
      gl: gl, U: U,
      upload: function (unit, src) {
        gl.activeTexture(gl.TEXTURE0 + unit);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, gl.RGB, gl.UNSIGNED_BYTE, src);
      },
      dispose: function () {
        var ext = gl.getExtension('WEBGL_lose_context');
        if (ext) ext.loseContext();
      }
    };
  }

  function loadImage(url) {
    return new Promise(function (ok, ng) {
      var im = new Image();
      im.decoding = 'async';
      im.onload = function () { ok(im); };
      im.onerror = ng;
      im.src = url;
    });
  }

  function timeout(ms) { return new Promise(function (_, ng) { setTimeout(function () { ng(new Error('timeout')); }, ms); }); }

  /* --------------------------------------------------------------------------
     本体。opts.auto＝最初の表示で自動（無音・2.2秒）／opts.sound＝ボタンから（音あり・6.4秒）
     -------------------------------------------------------------------------- */
  function start(opts) {
    if (running) return;
    running = true;
    opts = opts || {};
    var pre = opts.pre || null;
    var orient = (pre && pre.o) || (portrait() ? 'p' : 'l');
    var mode = opts.sound ? 'long' : 'short';
    var tl = null;                     /* 走り出すときに TIMELINES から写す（スキップで書き換えるため） */
    var t0 = 0, raf = 0, ended = false, revealed = false, opened = false, skipped = false, stillTried = false, swallowUntil = 0;
    var useAudioClock = false, urls = [], timers = [];
    var video = null, audio = opts.audio || null, stills = null, G = null;
    var texW = 0, texH = 0, gotFrame = false, shown = false;
    var born0 = now(), poster = null, looping = false;

    injectCss();
    H.classList.add('op-run');          /* 水（js/ocean/boot.js）はこの印がある間、起動を待つ */

    var root = D.createElement('div');
    root.className = 'op-root' + (opts.auto ? '' : ' bg fade');
    var canvas = D.createElement('canvas');
    canvas.className = 'op-cv';
    canvas.setAttribute('aria-hidden', 'true');
    var copy = D.createElement('div');
    copy.className = 'op-copy';
    copy.setAttribute('aria-hidden', 'true');
    var line = D.createElement('span');
    line.className = 'op-line';
    '未知の世界へ。'.split('').forEach(function (ch, i) {
      var el = D.createElement('i');
      el.textContent = ch;
      el.style.setProperty('--i', i);
      line.appendChild(el);
    });
    var brand = D.createElement('span');
    brand.className = 'op-brand';
    brand.textContent = 'MIURA UMI NO GAKKO';
    copy.appendChild(line);
    copy.appendChild(brand);
    var note = D.createElement('span');
    note.className = 'op-note';
    note.textContent = 'イメージ映像';
    var skipBtn = D.createElement('button');
    skipBtn.type = 'button';
    skipBtn.className = 'op-skip';
    skipBtn.textContent = 'スキップ';
    skipBtn.setAttribute('aria-label', 'オープニングをスキップしてサイトを見る');
    root.appendChild(canvas);
    root.appendChild(copy);
    root.appendChild(note);
    root.appendChild(skipBtn);
    (D.body || H).appendChild(root);

    function later(fn, ms) { var id = setTimeout(fn, ms); timers.push(id); return id; }
    var DBG = /[?&]opdebug/.test(location.search), born = now();
    function dbg(m) { if (DBG) note.textContent += ' | ' + m + '@' + Math.round(now() - born); }

    /* ---- 片づけ。soft=true は海の色ごとふわっと消す（読み込みが間に合わなかったとき・始まる前のスキップ） ---- */
    function finish(soft) {
      if (ended) return;
      ended = true;
      cancelAnimationFrame(raf);
      timers.forEach(clearTimeout);
      W.removeEventListener('wheel', onScrollish, true);
      W.removeEventListener('touchmove', onScrollish, true);
      W.removeEventListener('keydown', onKey, true);
      W.removeEventListener('resize', resize);
      D.removeEventListener('visibilitychange', onVis);
      function gone() {
        H.classList.remove('op', 'op-bye', 'op-run');
        if (root.parentNode) root.parentNode.removeChild(root);
        try { if (video) { video.pause(); video.removeAttribute('src'); video.load(); } } catch (e) { /* noop */ }
        urls.forEach(function (u) { try { URL.revokeObjectURL(u); } catch (e) { /* noop */ } });
        try { if (G) G.dispose(); } catch (e) { /* noop */ }
        running = false;
        announce();
      }
      if (soft) {
        H.classList.add('op-bye');
        root.style.transition = 'opacity .45s linear';
        root.style.opacity = '0';
        root.style.pointerEvents = 'none';
        if (audio && !audio.paused) fadeAudio(400);
        setTimeout(gone, 470);
      } else {
        gone();
      }
    }

    /* ページ側への合図：水（js/ocean）はこれを待って起動し、起きたら水面に波紋を落とす。
       水の起動は重いので、水滴が消えきってから出す（途中で出すと最後の動きがカクつく） */
    function announce() {
      if (revealed) return;
      revealed = true;
      try { W.dispatchEvent(new Event('umigaku:opening-end')); } catch (e) { /* noop */ }
      welcomeRipples();
    }

    function fadeAudio(ms) {
      var a = audio, v0 = a.volume, s = now();
      (function step() {
        var k = clamp((now() - s) / ms, 0, 1);
        try { a.volume = v0 * (1 - k); } catch (e) { /* noop */ }
        if (k < 1) requestAnimationFrame(step); else { try { a.pause(); } catch (e) { /* noop */ } }
      })();
    }

    /* ---- スキップ：どこを押しても、そこから0.45秒で水面を割ってサイトへ ---- */
    function clock() {
      if (typeof W.__opT === 'number') return W.__opT;      /* 確認用：外から時刻を止めて、1コマずつ撮るための口 */
      if (useAudioClock && audio && !audio.paused && audio.currentTime > 0) return audio.currentTime;
      return (now() - t0) / 1000;
    }
    function skip(viaPointer) {
      if (ended || skipped) return;
      skipped = true;
      if (viaPointer) swallowUntil = now() + 420;
      if (!tl || typeof W.__opT === 'number') { finish(true); return; }   /* まだ映像が始まっていない／確認用に止めている */
      var t = clock();
      if (t < tl.wipe[0]) {
        if (useAudioClock) { useAudioClock = false; t0 = now() - t * 1000; }
        tl.copyOut = Math.min(tl.copyOut, t);
        tl.glow = [t - 0.2, t + 0.12];
        tl.wipe = [t, t + 0.45];
        tl.end = t + 1.2;
        if (audio && !audio.paused) fadeAudio(500);
      }
      root.classList.add('out', 'fin');
    }
    function onPointer(e) { e.preventDefault(); skip(true); }
    function onClick(e) { e.preventDefault(); e.stopPropagation(); skip(true); }
    function onScrollish() { skip(false); }
    function onKey(e) { if (e.key !== 'Tab' && e.key !== 'Shift') skip(false); }
    function onVis() { if (D.visibilityState === 'hidden') finish(false); }

    root.addEventListener('pointerdown', onPointer);
    root.addEventListener('click', onClick);
    W.addEventListener('wheel', onScrollish, { capture: true, passive: true });
    W.addEventListener('touchmove', onScrollish, { capture: true, passive: true });
    W.addEventListener('keydown', onKey, true);
    D.addEventListener('visibilitychange', onVis);

    /* ---- 描画 ---- */
    function resize() {
      if (!G) return;
      var dpr = Math.min(W.devicePixelRatio || 1, 2);
      var w = root.clientWidth, h = root.clientHeight;
      /* 画素数に上限（大きい画面でも約230万画素まで） */
      var k = Math.min(1, Math.sqrt(2300000 / Math.max(1, w * h * dpr * dpr)));
      canvas.width = Math.max(2, Math.round(w * dpr * k));
      canvas.height = Math.max(2, Math.round(h * dpr * k));
      G.gl.viewport(0, 0, canvas.width, canvas.height);
      G.gl.uniform2f(G.U.uRes, canvas.width, canvas.height);
      G.gl.uniform1f(G.U.uPx, canvas.height / Math.max(1, h));
    }

    /* ぼやけた海の色の上に、絵を0.28秒でくっきり出す。出きったら下の色を外して、自分の背景を海の色にする
       （iPhoneのSafariは、端まで届く固定要素の背景色を上下のバーの色に使う。水面を割るまでは海の色にしておく） */
    function reveal(sec) {
      if (shown) return;
      canvas.style.opacity = String(sstep(0, 0.28, sec));
      if (sec >= 0.3) {
        shown = true;
        canvas.style.opacity = '1';
        H.classList.remove('op');
        root.classList.remove('fade');
        root.classList.add('bg');
        /* ボタンから見るとき：隠れている間にページの先頭へ戻しておく（水面から顔を出した先を、最初の写真にする） */
        if (opts.sound) { try { W.scrollTo(0, 0); } catch (e) { /* noop */ } }
      }
    }

    function frame() {
      if (ended) return;
      raf = requestAnimationFrame(frame);
      var gl = G.gl, U = G.U;

      if (!tl) {
        /* 待っている間：最初の1枚（動画の1コマ目と同じ絵）を静かに出しておく */
        if (!poster) return;
        reveal((now() - poster.at) / 1000);
        gl.uniform1f(U.uTime, 0); gl.uniform1f(U.uLine, 1.3); gl.uniform1f(U.uSpeed, 0); gl.uniform1f(U.uZoom, 1);
        gl.uniform1f(U.uMix, 0); gl.uniform1f(U.uGlow, 0); gl.uniform1f(U.uFade, 1);
        gl.clear(gl.COLOR_BUFFER_BIT);
        gl.drawArrays(gl.TRIANGLES, 0, 3);
        return;
      }
      var t = clock();

      if (typeof W.__opT === 'number' && video && Math.abs(video.currentTime - t) > 0.004) {
        try { video.pause(); video.currentTime = Math.min(t, (video.duration || 99) - 0.02); } catch (e) { /* noop */ }
      }
      reveal(t);

      if (video && video.readyState >= 2 && (!video.ended || !gotFrame)) {
        try { G.upload(0, video); gotFrame = true; } catch (e) { finish(true); return; }
      }

      var wp = (t - tl.wipe[0]) / (tl.wipe[1] - tl.wipe[0]);
      var lineY;
      if (wp <= 0) lineY = 1.3;
      else if (wp < 1) lineY = 1.3 - 1.62 * lineEase(wp);
      else lineY = -0.32 - 0.85 * clamp((t - tl.wipe[1]) / (tl.end - tl.wipe[1]), 0, 1);

      var sp = Math.exp(-Math.pow((t - tl.peak) / tl.peakW, 2));
      var zoom = 1, mix = 0;
      if (stills) {
        var z = clamp(t / tl.wipe[1], 0, 1);
        zoom = 1 + 1.9 * z * z;
        mix = sstep(0.42, 1.12, t);
      }
      gl.uniform1f(U.uTime, t);
      gl.uniform1f(U.uLine, lineY);
      gl.uniform1f(U.uSpeed, sp);
      gl.uniform1f(U.uZoom, zoom);
      gl.uniform1f(U.uMix, mix);
      gl.uniform1f(U.uGlow, 0.22 * sstep(tl.glow[0], tl.glow[1], t));
      gl.uniform1f(U.uFade, 1);
      gl.clear(gl.COLOR_BUFFER_BIT);
      gl.drawArrays(gl.TRIANGLES, 0, 3);

      if (t >= tl.copyOut) root.classList.add('out');
      if (wp > 0 && !opened) {
        /* 水面が降り始めたら、下のページをすぐ触れるようにする（スキップで押した直後だけは、その指の click を飲む） */
        opened = true;
        shown = true;
        canvas.style.opacity = '1';
        H.classList.remove('op');
        root.classList.remove('bg', 'fade');
        root.classList.add('fin');
        later(function () { root.style.pointerEvents = 'none'; }, Math.max(0, swallowUntil - now()));
      }
      if (t >= tl.end) finish(false);
    }

    function loop() {
      if (looping || ended) return;
      looping = true;
      resize();
      W.addEventListener('resize', resize);
      raf = requestAnimationFrame(frame);
    }

    function go(kind) {
      if (ended) return;
      tl = {};
      var src = TIMELINES[kind];
      for (var k in src) tl[k] = Array.isArray(src[k]) ? src[k].slice() : src[k];
      G.gl.uniform2f(G.U.uTexRes, texW, texH);
      t0 = now();
      loop();
    }

    /* ---- 動画を再生できない端末（iPhoneの低電力モードなど）：静止画2枚で同じ流れを作る ---- */
    function goStill(why) {
      if (ended || stillTried) return;
      stillTried = true;
      dbg('still:' + why);
      if (video) { try { video.pause(); } catch (e) { /* noop */ } }
      video = null;
      Promise.race([Promise.all([posterP, loadImage(asset('image/opening/opening-2026-' + orient + '1.webp'))]), timeout(1500)])
        .then(function (ims) {
          if (ended || !ims[0]) { finish(true); return; }
          stills = ims;
          G.upload(0, ims[0]);
          G.upload(1, ims[1]);
          texW = ims[0].naturalWidth; texH = ims[0].naturalHeight;
          G.gl.uniform2f(G.U.uTex2Res, ims[1].naturalWidth, ims[1].naturalHeight);
          useAudioClock = false;
          if (audio) { try { audio.pause(); } catch (e) { /* noop */ } }
          go('still');
        }).catch(function () { finish(true); });
    }

    function goVideo(blob) {
      if (ended) return;
      var url = URL.createObjectURL(blob);
      urls.push(url);
      video = D.createElement('video');
      video.className = 'op-vid';
      video.muted = true;
      video.defaultMuted = true;
      video.playsInline = true;
      video.setAttribute('muted', '');
      video.setAttribute('playsinline', '');
      video.setAttribute('aria-hidden', 'true');
      video.tabIndex = -1;
      video.preload = 'auto';
      video.disablePictureInPicture = true;
      root.appendChild(video);
      var begun = false;
      function begin() {
        if (begun || ended || stillTried || !video) return;
        begun = true;
        texW = video.videoWidth; texH = video.videoHeight;
        dbg('playing ' + texW);
        if (!texW) { goStill('nosize'); return; }
        if (opts.sound && audio) {
          /* 音を時計にする（映像と水面の線は、音の進みに合わせる） */
          try {
            audio._started = true;
            audio.currentTime = 0;
            audio.volume = 1;
            var ap = audio.play();
            useAudioClock = true;
            if (ap && ap.catch) ap.catch(function () { useAudioClock = false; });
          } catch (e) { useAudioClock = false; }
        }
        go(mode);
      }
      video.addEventListener('playing', begin, { once: true });
      video.addEventListener('error', function () { if (!begun) goStill('error'); }, { once: true });
      ['loadedmetadata', 'loadeddata', 'canplay', 'stalled', 'suspend', 'waiting'].forEach(function (n) { video.addEventListener(n, function () { dbg(n); }, { once: true }); });
      video.src = url;
      var p = video.play();
      if (p && p.catch) p.catch(function (e) { if (!begun) goStill('reject:' + (e && e.name)); });
      /* 再生が始まるまで待つ時間：受け取ってから2秒まで（自動のときは、全体で2.8秒を超えない） */
      later(function () { if (!begun) goStill('timeout rs' + (video ? video.readyState : '-')); },
        opts.auto ? Math.max(300, Math.min(2000, 2800 - (now() - born0))) : 2500);
    }

    /* ---- 走り出し ---- */
    /* 見張り：描画が進まない環境（画面は表に出ているのに描画だけ止められる場合がある）でも、
       かならず時間で片づける。自動は7秒、ボタンからは16秒。 */
    later(function () { finish(true); }, opts.auto ? 7000 : 16000);
    try { G = makeGL(canvas); } catch (e) { G = null; }
    if (!G) { finish(true); return; }
    canvas.addEventListener('webglcontextlost', function (e) { e.preventDefault(); finish(true); });

    /* 最初の1枚（動画の1コマ目と同じ絵・約80KB）。動画の再生が始まるまでの間、これをくっきり出しておく */
    var posterP = ((pre && pre.i) ? new Promise(function (ok) {
      var im = pre.i;
      if (im.complete && im.naturalWidth) ok(im);
      else { im.addEventListener('load', function () { ok(im); }); im.addEventListener('error', function () { ok(null); }); }
    }) : loadImage(asset('image/opening/opening-2026-' + orient + '0.webp')).catch(function () { return null; }));
    posterP.then(function (im) {
      if (!im || tl || ended || stillTried) return;
      try {
        G.upload(0, im);
        G.gl.uniform2f(G.U.uTexRes, im.naturalWidth, im.naturalHeight);
        poster = { at: now() };
        dbg('poster');
        loop();
      } catch (e) { /* 出せなくても動画が始まれば進む */ }
    });

    var file = 'video/opening/opening-2026-' + orient + (mode === 'long' ? '-long' : '') + '.mp4';
    var blobP = (pre && pre.v) || fetch(asset(file)).then(function (r) { if (!r.ok) throw new Error('http'); return r.blob(); });
    /* 自動のときは待ちすぎない（<head> のスクリプトが走ってから1.5秒で動画が届かなければ、回線が遅いとみて
       オープニングをやめ、ふわっとサイトを出す）。押して見る人は6秒まで待つ */
    var limit = opts.auto ? Math.max(250, 1500 - (now() - ((pre && pre.t) || now()))) : 6000;
    Promise.race([blobP, timeout(limit)])
      .then(function (b) { dbg('blob ' + b.size); goVideo(b); })
      .catch(function () { dbg('slow'); finish(true); });
  }

  /* 水（js/ocean）が起きたら、水面を割った名残の波紋を3つ落とす。水が無い端末では何もしない */
  function welcomeRipples() {
    var n = 0;
    (function poll() {
      var o = W.__ocean;
      if (o && o.ripple && o.ripple.drop) {
        [[0.50, 0.44, 0.60, 350], [0.27, 0.36, 0.42, 640], [0.74, 0.33, 0.38, 930]].forEach(function (d) {
          setTimeout(function () { try { o.ripple.drop(d[0], d[1], d[2]); } catch (e) { /* noop */ } }, d[3]);
        });
      } else if (++n < 40) {
        setTimeout(poll, 250);
      }
    })();
  }

  /* ---- 音あり版のボタン。押したその場で音の再生を始めておく（あとからでは鳴らせないブラウザがある） ---- */
  D.addEventListener('click', function (e) {
    var b = e.target && e.target.closest && e.target.closest('[data-opening]');
    if (!b) return;
    e.preventDefault();
    if (running) return;
    var audio = null;
    if (lastAudio) { try { lastAudio.pause(); } catch (err0) { /* noop */ } }     /* 前の回の余韻が残っていたら止める */
    if (b.getAttribute('data-opening') === 'sound') {
      try {
        audio = new Audio(asset('video/opening/opening-2026-sound.m4a'));
        audio.preload = 'auto';
        /* 再生→すぐ止める。これで、映像の準備ができたときに頭から鳴らせる（最初の0.1秒はほぼ無音） */
        var p = audio.play();
        if (p && p.then) p.then(function () { if (!audio._started) { audio.pause(); } }).catch(function () { /* 鳴らせない端末は無音で流す */ });
      } catch (err) { audio = null; }
    }
    lastAudio = audio;
    start({ sound: true, audio: audio });
    if (b.blur) b.blur();
  });

  W.umigakuOpening = { play: function (o) { start(o || {}); } };

  /* ---- 最初の表示（<head> のスクリプトが「出す」と決めたときだけ） ---- */
  var pre = W.__op;
  if (pre && H.classList.contains('op')) {
    try { if (pre.k) localStorage.setItem(pre.k, JSON.stringify({ v: pre.ver, t: Date.now() })); } catch (e) { /* noop */ }
    if (D.visibilityState === 'hidden') {
      /* 裏のタブで開かれた（描画もタイマーも止められる）。出さずに、<head> が敷いた海の色だけ外す */
      H.classList.remove('op', 'op-run');
      try { W.dispatchEvent(new Event('umigaku:opening-end')); } catch (e) { /* noop */ }
    } else {
      (function wait() {
        if (D.body) start({ auto: true, pre: pre }); else setTimeout(wait, 16);
      })();
    }
  }
})();
