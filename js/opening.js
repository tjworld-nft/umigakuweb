/* ==========================================================================
   三浦 海の学校 — トップのオープニング「水面へ」
   --------------------------------------------------------------------------
   深い海から水面へ上がり、顔を出すと、そこがサイト（三浦の海の写真）。

   ・2つの版がある。どちらも映像は等速（早回しは目が回るのでやめた・2026-10-11）。
       full … はじめての人（と、1週間あいた人）に。約6秒。「未知の世界は、／すぐそこに。」
       rise … 2回目からの人に。水面に出るところだけ、約2秒。「おかえりなさい。」
   ・映像は video/opening/（絵＝gpt-image-2.5、動き＝MiniMax H3 Max Turbo、音＝ElevenLabs）。
     実写ではなくイメージ映像なので、画面の隅に「イメージ映像」と出す。
   ・水面の線、流れ落ちる水の膜、レンズに残る水滴は WebGL でその場で描く。
     線より上は透明で、下にある本物のページがそのまま見える（写真は1ピクセルも作り変えない）。
   ・出す／出さない／どちらの版かは index.html の <head> の小さなスクリプトが決める（html に class="op"）。
     このファイルが読めなくても、<head> の CSS が5秒で自分から消える。
   ・音は自動では出せない（ブラウザの決まり）。full には「音を出す」ボタンがあり、押した人にだけ鳴らす。
     あとからもう一度見るのは [data-opening="sound"] のボタン（最初から音あり）。
   ・映像・音・静止画を差し替えたら下の VER と <head> の ?v= を上げる（サーバーが7日キャッシュする）。
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

  /* 版ごとの素材と進行表（秒）。tl＝動画のとき／stl＝動画を再生できない端末（静止画2枚で同じ流れ）
     copyOut 文字が去る／glow 水面に近づいて白む／wipe 水面の線が降りる／end 水滴が消えて片づく */
  var KINDS = {
    full: {
      video: '-long', still: ['0', '1'], lines: ['未知の世界は、', 'すぐそこに。'], stagger: 65, second: 1050,
      tl:  { copyOut: 2.55, glow: [4.70, 5.45], wipe: [5.30, 6.15], peak: 3.70, peakW: 1.00, end: 7.20 },
      stl: { copyOut: 2.20, glow: [2.30, 2.90], wipe: [2.80, 3.60], peak: 2.10, peakW: 0.70, end: 4.50, mix: [1.30, 2.70], zoom: 1.9 }
    },
    rise: {
      video: '-rise', still: ['2', '1'], lines: ['おかえりなさい。'], stagger: 45, second: 0,
      tl:  { copyOut: 0.85, glow: [0.25, 0.80], wipe: [0.85, 1.65], peak: 0.50, peakW: 0.60, end: 2.60 },
      stl: { copyOut: 0.80, glow: [0.20, 0.70], wipe: [0.80, 1.55], peak: 0.45, peakW: 0.45, end: 2.45, mix: [0.15, 0.80], zoom: 0.5 }
    }
  };

  var PILL = 'position:absolute;display:inline-flex;align-items:center;gap:.5em;padding:.75em 1.15em;color:#fff;' +
    'font:600 12px/1 "Zen Maru Gothic","Hiragino Maru Gothic ProN",sans-serif;letter-spacing:.08em;' +
    'text-shadow:0 1px 4px rgba(0,25,55,.7);border:1px solid rgba(255,255,255,.55);border-radius:99px;' +
    'background:rgba(4,34,58,.28);cursor:pointer;-webkit-appearance:none;appearance:none;' +
    'opacity:0;animation:op-fade .5s .35s forwards;transition:opacity .3s linear;';

  var CSS =
    '.op-root{position:fixed;inset:0;z-index:2147483001;overflow:hidden;contain:strict;cursor:pointer;' +
      '-webkit-tap-highlight-color:transparent;touch-action:manipulation;background:rgba(5,42,69,0)}' +
    '.op-root.bg{background:#052a45}' +
    '.op-root.fade{animation:op-rootin .25s linear both}' +
    '.op-cv{position:absolute;inset:0;width:100%;height:100%;display:block;opacity:0}' +
    '.op-vid{position:absolute;left:0;top:0;width:2px;height:2px;opacity:0;pointer-events:none}' +
    '.op-copy{position:absolute;left:0;right:0;top:35%;text-align:center;color:#fff;pointer-events:none;' +
      'font-family:"Hiragino Mincho ProN","Yu Mincho",YuMincho,"Noto Serif JP",serif;' +
      'text-shadow:0 0 26px rgba(0,60,120,.85),0 2px 12px rgba(0,25,55,.7);' +
      'transition:transform .7s cubic-bezier(.55,0,.9,.45),opacity .5s linear}' +
    '.op-line{display:block;font-size:clamp(25px,7.1vw,58px);font-weight:500;letter-spacing:.28em;text-indent:.28em;line-height:1.3}' +
    '.op-line+.op-line{margin-top:.55em}' +
    '.op-line i{display:inline-block;font-style:normal;opacity:0;transform:translateY(.45em);' +
      'animation:op-in .75s cubic-bezier(.2,.7,.2,1) forwards;animation-delay:calc(var(--i)*var(--s) + var(--d))}' +
    '.op-brand{display:block;margin-top:1.7em;font:700 12px/1 "Zen Maru Gothic","Hiragino Maru Gothic ProN",sans-serif;' +
      'letter-spacing:.5em;text-indent:.5em;opacity:0;animation:op-fade 1s var(--d) forwards}' +
    '.op-root.out .op-copy{transform:translateY(42vh) scale(.94);opacity:0}' +
    '.op-root.fin .op-copy{opacity:0;transition:transform .7s cubic-bezier(.55,0,.9,.45),opacity .16s linear}' +
    '.op-skip{' + PILL + 'right:max(14px,env(safe-area-inset-right));bottom:max(20px,env(safe-area-inset-bottom))}' +
    '.op-snd{' + PILL + 'left:max(14px,env(safe-area-inset-left));bottom:max(20px,env(safe-area-inset-bottom))}' +
    '.op-snd svg{width:15px;height:15px;fill:#fff;flex:none}' +
    '.op-snd.on{background:rgba(255,255,255,.9);color:#063a57;text-shadow:none;border-color:#fff}' +
    '.op-snd.on svg{fill:#063a57}' +
    '.op-skip:focus-visible,.op-snd:focus-visible{outline:2px solid #fff;outline-offset:3px}' +
    '.op-note{position:absolute;left:max(16px,env(safe-area-inset-left));top:max(13px,env(safe-area-inset-top));color:#fff;' +
      'font:400 10.5px/1 "Zen Maru Gothic","Hiragino Maru Gothic ProN",sans-serif;letter-spacing:.08em;' +
      'text-shadow:0 1px 4px rgba(0,25,55,.7);opacity:.72;pointer-events:none;transition:opacity .3s linear}' +
    '.op-root.fin .op-skip,.op-root.fin .op-snd,.op-root.fin .op-note{opacity:0!important;animation:none;pointer-events:none}' +
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
    '  float ca=uSpeed*0.010*dot(d,d);',                       /* 速い所だけ、外側で色がわずかににじむ */
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
    /* レンズに残る水滴：線が通り過ぎた所に出て、少し滑って消える。上の縁は暗く、下の縁と光の点は明るく */
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
    '    float dk=clamp(on*edge*hi*0.26,0.0,1.0);',
    '    oc*=1.0-dk;oa=dk+oa*(1.0-dk);',
    '    float da=clamp(on*(0.05*body+0.42*edge*lo+0.9*spec),0.0,1.0);',
    '    oc=vec3(da)+oc*(1.0-da);oa=da+oa*(1.0-da);',
    '  }',
    /* 水面の線そのもの（白い縁） */
    '  float k=exp(-abs(d)*uRes.y/(2.2*uPx))*0.94;',
    '  oc=vec3(k)+oc*(1.0-k);oa=k+oa*(1.0-k);',
    '  gl_FragColor=vec4(oc,oa)*uFade;',
    '}'
  ].join('\n');

  var SND_ICON = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3a4.5 4.5 0 0 0-2.5-4v8a4.5 4.5 0 0 0 2.5-4zM14 3.2v2.1a7 7 0 0 1 0 13.4v2.1a9 9 0 0 0 0-17.6z"/></svg>';

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

  function makeVideo(src) {
    var v = D.createElement('video');
    v.muted = true;
    v.defaultMuted = true;
    v.playsInline = true;
    v.setAttribute('muted', '');
    v.setAttribute('playsinline', '');
    v.preload = 'auto';
    v.src = src;
    return v;
  }

  /* --------------------------------------------------------------------------
     本体。opts.auto＝サイトを開いたとき（無音で始まる）／opts.sound＝ボタンから（最初から音あり）
     opts.kind＝'full'（約6秒）か 'rise'（約2秒）
     -------------------------------------------------------------------------- */
  function start(opts) {
    if (running) return;
    running = true;
    opts = opts || {};
    var pre = opts.pre || null;
    var kind = KINDS[opts.kind] ? opts.kind : 'full';
    var K = KINDS[kind];
    var orient = (pre && pre.o) || (portrait() ? 'p' : 'l');
    var tl = null;                     /* 走り出すときに進行表から写す（スキップで書き換えるため） */
    var t0 = 0, lastMs = 0, stalled = 0, raf = 0;
    var ended = false, revealed = false, opened = false, skipped = false, stillTried = false, swallowUntil = 0;
    var useAudioClock = false, wantSound = false, timers = [];
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
    K.lines.forEach(function (text, li) {
      var line = D.createElement('span');
      line.className = 'op-line';
      line.style.setProperty('--s', K.stagger + 'ms');
      line.style.setProperty('--d', (60 + li * K.second) + 'ms');
      text.split('').forEach(function (ch, i) {
        var el = D.createElement('i');
        el.textContent = ch;
        el.style.setProperty('--i', i);
        line.appendChild(el);
      });
      copy.appendChild(line);
    });
    var brand = D.createElement('span');
    brand.className = 'op-brand';
    brand.textContent = '三浦 海の学校';
    brand.style.setProperty('--d', (350 + (K.lines.length - 1) * K.second) + 'ms');
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
    /* 音を出すボタン：自動で始まった約6秒の版にだけ（ボタンから見るときは最初から鳴っている） */
    var sndBtn = null;
    if (opts.auto && kind === 'full') {
      sndBtn = D.createElement('button');
      sndBtn.type = 'button';
      sndBtn.className = 'op-snd';
      sndBtn.innerHTML = SND_ICON + '<span>音を出す</span>';
      root.appendChild(sndBtn);
    }
    root.appendChild(skipBtn);
    (D.body || H).appendChild(root);

    function later(fn, ms) { var id = setTimeout(fn, ms); timers.push(id); return id; }
    var DBG = /[?&]opdebug/.test(location.search);
    function dbg(m) { if (DBG) note.textContent += ' | ' + m + '@' + Math.round(now() - born0); }

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
        H.classList.remove('op', 'op-bye', 'op-run', 'op-rise');
        if (root.parentNode) root.parentNode.removeChild(root);
        try { if (video) { video.pause(); video.removeAttribute('src'); video.load(); } } catch (e) { /* noop */ }
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

    /* ---- 時計。ふだんは実時間。ボタンから音ありで見るときは、音の進みを時計にする ---- */
    function clock() {
      if (typeof W.__opT === 'number') return W.__opT;      /* 確認用：外から時刻を止めて、1コマずつ撮るための口 */
      if (useAudioClock && audio && !audio.paused && audio.currentTime > 0) return audio.currentTime;
      return (now() - t0) / 1000;
    }

    /* ---- 音を出す（自動で始まった版の途中から）。映像の今の位置に合わせて鳴らす ---- */
    function soundOn() {
      if (audio || ended) return;
      try {
        audio = new Audio(asset('video/opening/opening-2026-sound.m4a'));
        audio.preload = 'auto';
        lastAudio = audio;
        var a = audio;
        if (tl) {
          a.addEventListener('playing', function () {
            try { var t = clock(); if (t > 0.3 && Math.abs(a.currentTime - t) > 0.2) a.currentTime = t; } catch (e) { /* noop */ }
          }, { once: true });
        } else {
          wantSound = true;        /* 映像がまだ始まっていない：鳴らせる状態にだけして、始まったら頭から鳴らす */
        }
        var p = a.play();
        if (p && p.then) {
          p.then(function () { if (!tl && !a._started) a.pause(); })
           .catch(function () { audio = null; wantSound = false; if (sndBtn) sndBtn.classList.remove('on'); });
        }
        if (sndBtn) { sndBtn.classList.add('on'); sndBtn.lastChild.textContent = '音あり'; }
      } catch (e) { audio = null; }
    }

    /* ---- スキップ：どこを押しても、そこから0.45秒で水面を割ってサイトへ ---- */
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
    function onSnd(e) { return sndBtn && e.target && e.target.closest && e.target.closest('.op-snd'); }
    function onPointer(e) { if (onSnd(e)) return; e.preventDefault(); skip(true); }
    function onClick(e) {
      e.preventDefault();
      e.stopPropagation();
      if (onSnd(e)) { soundOn(); return; }
      skip(true);
    }
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
      var ms = now(), dt = Math.min(250, ms - (lastMs || ms));
      lastMs = ms;

      if (!tl) {
        /* 待っている間：最初の1枚（動画の1コマ目と同じ絵）を静かに出しておく */
        if (!poster) return;
        reveal((ms - poster.at) / 1000);
        gl.uniform1f(U.uTime, 0); gl.uniform1f(U.uLine, 1.3); gl.uniform1f(U.uSpeed, 0); gl.uniform1f(U.uZoom, 1);
        gl.uniform1f(U.uMix, 0); gl.uniform1f(U.uGlow, 0); gl.uniform1f(U.uFade, 1);
        gl.clear(gl.COLOR_BUFFER_BIT);
        gl.drawArrays(gl.TRIANGLES, 0, 3);
        return;
      }

      var frozen = typeof W.__opT === 'number';
      if (video && !frozen && !useAudioClock && !video.ended) {
        if (video.paused || video.readyState < 3) {
          /* 読み込みが追いつかず映像が止まった：時計も止めて待つ（水面の線だけ先に降りないように）。2.5秒待っても動かなければやめる */
          t0 += dt;
          stalled += dt;
          if (stalled > 2500) { dbg('stall'); finish(true); return; }
        } else if (Math.abs((ms - t0) / 1000 - video.currentTime) > 0.25) {
          t0 = ms - video.currentTime * 1000;               /* ずれたら映像に合わせ直す */
        }
      }
      var t = clock();

      if (frozen && video && Math.abs(video.currentTime - t) > 0.004) {
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
        zoom = 1 + tl.zoom * z * z;
        mix = sstep(tl.mix[0], tl.mix[1], t);
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
        /* 水面が降り始めたら、自分の背景を透明に戻し（線より上＝ページが見える）、下のページをすぐ触れるようにする
           （スキップで押した直後だけは、その指の click を飲む） */
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

    function go(table) {
      if (ended) return;
      tl = {};
      for (var k in table) tl[k] = Array.isArray(table[k]) ? table[k].slice() : table[k];
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
      if (sndBtn) sndBtn.style.display = 'none';
      Promise.race([Promise.all([posterP, loadImage(asset('image/opening/opening-2026-' + orient + K.still[1] + '.webp'))]), timeout(1500)])
        .then(function (ims) {
          if (ended || !ims[0]) { finish(true); return; }
          stills = ims;
          G.upload(0, ims[0]);
          G.upload(1, ims[1]);
          texW = ims[0].naturalWidth; texH = ims[0].naturalHeight;
          G.gl.uniform2f(G.U.uTex2Res, ims[1].naturalWidth, ims[1].naturalHeight);
          useAudioClock = false;
          if (audio) { try { audio.pause(); } catch (e) { /* noop */ } }
          go(K.stl);
        }).catch(function () { finish(true); });
    }

    function goVideo() {
      var rejected = false, begun = false;
      video = (pre && pre.el) || makeVideo(asset('video/opening/opening-2026-' + orient + K.video + '.mp4'));
      video.className = 'op-vid';
      video.setAttribute('aria-hidden', 'true');
      video.tabIndex = -1;
      video.disablePictureInPicture = true;
      root.appendChild(video);
      function begin() {
        if (begun || ended || stillTried || !video) return;
        begun = true;
        texW = video.videoWidth; texH = video.videoHeight;
        dbg('playing ' + texW);
        if (!texW) { goStill('nosize'); return; }
        if (audio && (opts.sound || wantSound)) {
          try {
            audio._started = true;
            audio.currentTime = 0;
            audio.volume = 1;
            var ap = audio.play();
            /* ボタンから見るときは、音を時計にする（映像と水面の線を音の進みに合わせる） */
            if (opts.sound) {
              useAudioClock = true;
              if (ap && ap.catch) ap.catch(function () { useAudioClock = false; });
            }
          } catch (e) { useAudioClock = false; }
        }
        go(K.tl);
      }
      if (pre && pre.rej) { goStill('reject'); return; }       /* <head> で試した時点で、自動再生を断られている */
      video.addEventListener('playing', begin, { once: true });
      video.addEventListener('error', function () { if (!begun) { dbg('error'); finish(true); } }, { once: true });
      ['loadedmetadata', 'canplay', 'waiting', 'stalled'].forEach(function (n) { video.addEventListener(n, function () { dbg(n); }, { once: true }); });
      var p = video.play();
      if (p && p.catch) p.catch(function (e) { rejected = true; if (!begun) goStill('reject:' + (e && e.name)); });
      /* 再生が始まるまで待つ時間：自動は <head> が走ってから2.6秒まで（その間は最初の1枚と文字が出ている）、ボタンからは6秒まで。
         それでも始まらないとき：もう動いている＝そのまま進む／データは届いているのに動かない＝静止画の流れへ／
         届いていない＝回線が遅い＝やめてサイトを出す */
      var limit = opts.auto ? Math.max(400, 2600 - (now() - ((pre && pre.t) || born0))) : 6000;
      later(function () {
        if (begun || rejected || ended || !video) return;
        if (video.readyState >= 3 && !video.paused) begin();
        else if (video.readyState >= 2) goStill('timeout rs' + video.readyState);
        else { dbg('slow'); finish(true); }
      }, limit);
    }

    /* ---- 走り出し ---- */
    /* 見張り：描画が進まない環境（画面は表に出ているのに描画だけ止められる場合がある）でも、かならず時間で片づける */
    later(function () { finish(true); }, opts.auto ? (kind === 'full' ? 13500 : 8500) : 16500);

    try { G = makeGL(canvas); } catch (e) { G = null; }
    if (!G) { finish(true); return; }
    canvas.addEventListener('webglcontextlost', function (e) { e.preventDefault(); finish(true); });

    /* 最初の1枚（動画の1コマ目と同じ絵）。動画の再生が始まるまでの間、これをくっきり出しておく。
       iPhoneのSafariは再生が始まるまで1秒以上かかることがあるので、この段を外さない */
    var posterP = ((pre && pre.i) ? new Promise(function (ok) {
      var im = pre.i;
      if (im.complete && im.naturalWidth) ok(im);
      else { im.addEventListener('load', function () { ok(im); }); im.addEventListener('error', function () { ok(null); }); }
    }) : loadImage(asset('image/opening/opening-2026-' + orient + K.still[0] + '.webp')).catch(function () { return null; }));
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

    goVideo();
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

  /* ---- あとからもう一度、音ありで見るボタン。押したその場で音の再生を始めておく（あとからでは鳴らせないブラウザがある） ---- */
  D.addEventListener('click', function (e) {
    var b = e.target && e.target.closest && e.target.closest('[data-opening]');
    if (!b) return;
    e.preventDefault();
    if (running) return;
    var audio = null;
    if (lastAudio) { try { lastAudio.pause(); } catch (err0) { /* noop */ } }     /* 前の回の余韻が残っていたら止める */
    try {
      audio = new Audio(asset('video/opening/opening-2026-sound.m4a'));
      audio.preload = 'auto';
      /* 再生→すぐ止める。これで、映像の準備ができたときに頭から鳴らせる（最初の0.1秒はほぼ無音） */
      var p = audio.play();
      if (p && p.then) p.then(function () { if (!audio._started) { audio.pause(); } }).catch(function () { /* 鳴らせない端末は無音で流す */ });
    } catch (err) { audio = null; }
    lastAudio = audio;
    start({ sound: true, audio: audio, kind: 'full' });
    if (b.blur) b.blur();
  });

  W.umigakuOpening = { play: function (o) { start(o || {}); } };

  /* ---- サイトを開いたとき（<head> のスクリプトが「出す」と決めたときだけ） ---- */
  var pre = W.__op;
  if (pre && !pre.dead && H.classList.contains('op')) {
    pre.go = 1;                 /* <head> の見張り（1.5秒で引っ込める）を止める */
    try {
      var t = Date.now();
      if (pre.k) localStorage.setItem(pre.k, JSON.stringify({ v: pre.ver, t: t, f: pre.kind === 'full' ? t : (pre.f || 0) }));
    } catch (e) { /* noop */ }
    if (D.visibilityState === 'hidden') {
      /* 裏のタブで開かれた（描画もタイマーも止められる）。出さずに、<head> が敷いた海の色だけ外す */
      H.classList.remove('op', 'op-run', 'op-rise');
      try { W.dispatchEvent(new Event('umigaku:opening-end')); } catch (e) { /* noop */ }
    } else {
      (function wait() {
        if (D.body) start({ auto: true, pre: pre, kind: pre.kind }); else setTimeout(wait, 16);
      })();
    }
  }
})();
