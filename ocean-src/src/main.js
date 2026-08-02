/**
 * 三浦 海の学校 — サイト全体に敷く水。
 *
 * ページの上に固定の一枚を張り、その中で場所によって役割を変える。
 *   ヒーロー … 写真の「海の部分」だけが本当に波立つ。空も人も動かない。
 *   その下   … 同じ水面を通った光の影が、白い背景の上をゆっくり流れる。
 *
 * 水面はWebGPUのコンピュートシェーダで波動方程式を解いている（ripple.js）。
 * カーソルを動かすと波が立ち、伝わり、跳ね返り、干渉して収まる。
 *
 * 読み込むかどうかは js/ocean/boot.js が先に判断済みで、
 * ここまで来た端末は「描いてよい」と分かっている。
 */
import {
  WebGPURenderer, Scene, OrthographicCamera, Color, Texture, Vector2,
  SRGBColorSpace, LinearFilter, ClampToEdgeWrapping,
} from 'three/webgpu';
import { uniform } from 'three/tsl';

import { PRESETS, detectTier, Governor } from './quality.js';
import { createRipple } from './ripple.js';
import { createSurface } from './surface.js';
import { createHud } from './hud.js';

/**
 * 写真の中の水平線を実測する。空と海の境目は縦方向の輝度差がいちばん大きい行。
 * 数本の列で探して中央値を取る（雲や岩に引っぱられないように）。
 * 写真を差し替えても勝手に追随するので、数字を書き換えなくてよい。
 * 返すのはテクスチャのv座標（画像の下端が0）。
 */
function findHorizon(img) {
  const FALLBACK = 0.473;
  try {
    const w = 240;
    const h = Math.max(2, Math.round((w * img.naturalHeight) / img.naturalWidth));
    const c = document.createElement('canvas');
    c.width = w; c.height = h;
    const g = c.getContext('2d', { willReadFrequently: true });
    g.drawImage(img, 0, 0, w, h);
    const d = g.getImageData(0, 0, w, h).data;
    const lum = (x, y) => {
      const i = (y * w + x) * 4;
      return 0.2126 * d[i] + 0.7152 * d[i + 1] + 0.0722 * d[i + 2];
    };
    const rows = [];
    for (const x of [0.3, 0.45, 0.6, 0.72].map((f) => Math.round(w * f))) {
      let best = -1, by = -1;
      for (let y = Math.round(h * 0.2); y < Math.round(h * 0.75); y++) {
        const dv = Math.abs(lum(x, y - 2) - lum(x, y + 2));
        if (dv > best) { best = dv; by = y; }
      }
      if (by >= 0) rows.push(by / h);
    }
    if (rows.length < 3) return FALLBACK;
    rows.sort((a, b) => a - b);
    return 1 - rows[Math.floor(rows.length / 2)];   // 上からの割合 → v座標
  } catch (e) {
    return FALLBACK;                         // 別ドメインの画像などで読めないとき
  }
}

export async function start({ host, hero, webgpu, cores, memory }) {
  const tier = detectTier({ webgpu, cores, memory });
  const preset = PRESETS[tier];

  const img = hero && hero.querySelector('.hero-bg img');
  if (!img) throw new Error('ヒーローの写真が見つかりません');
  if (!img.complete || !img.naturalWidth) {
    await new Promise((res, rej) => {
      img.addEventListener('load', res, { once: true });
      img.addEventListener('error', rej, { once: true });
    });
  }

  /* ---------- canvas ---------- */
  const canvas = document.createElement('canvas');
  canvas.className = 'page-water';
  canvas.setAttribute('aria-hidden', 'true');
  host.appendChild(canvas);

  /* ---------- renderer ---------- */
  const renderer = new WebGPURenderer({
    canvas,
    alpha: true,
    antialias: false,          // 全画面1枚なので線が出ない。MSAAは要らない
    powerPreference: 'high-performance',
    forceWebGL: !webgpu,
  });
  renderer.setClearColor(0x000000, 0);

  try {
    await renderer.init();
  } catch (err) {
    canvas.remove();
    throw err;
  }

  const useCompute = !!(renderer.backend && renderer.backend.isWebGPUBackend);
  const backendName = useCompute ? 'WebGPU' : 'WebGL2';

  /* ---------- 写真をテクスチャに（すでに読み込み済みの<img>を使い回す） ---------- */
  const photo = new Texture(img);
  photo.colorSpace = SRGBColorSpace;
  photo.minFilter = LinearFilter;
  photo.magFilter = LinearFilter;
  photo.generateMipmaps = false;
  photo.wrapS = photo.wrapT = ClampToEdgeWrapping;
  photo.needsUpdate = true;

  /* ---------- scene ----------
     画面いっぱいの板を1枚だけ描く。正射影で ±0.5 の枠にぴったり合わせてあるので、
     板のUVがそのまま画面座標になる（縦横比やfovの食い違いで狂う余地がない）。 */
  const scene = new Scene();
  const camera = new OrthographicCamera(-0.5, 0.5, 0.5, -0.5, 0, 1);
  camera.position.z = 0.5;

  const shared = {
    uTime: uniform(0),
    uAspect: uniform(1.6),
    uScroll: uniform(0),
    uFade: uniform(1),
    uPointer: uniform(new Vector2(0.5, 0.5)),
    colors: {
      sun: new Color('#dff6ff'),     // 海面に落ちる光
      scrim: new Color('#0a2c3a'),   // 文字を読ませるための落とし（元CSSと同色）
      wash: new Color('#8fc7dc'),    // 白い紙の上に落ちる水の影
    },
  };

  const ripple = createRipple({ shared, useCompute, grid: preset.grid });
  const surface = createSurface({ shared, ripple, photo });
  surface.setHorizon(findHorizon(img));
  scene.add(surface.object);

  /* ---------- 採寸 ---------- */
  let vw = 0, vh = 0;

  /* ビューポートの寸法は canvas 自身から取る。canvasは position:fixed / inset:0 なので
     これがそのままビューポート。環境によっては window.innerWidth が 0 を返すことがあり、
     そこを基準にすると写真の貼り位置が丸ごと狂う。 */
  function viewportSize() {
    const el = document.documentElement;
    return [
      canvas.clientWidth || el.clientWidth || window.innerWidth || 0,
      canvas.clientHeight || el.clientHeight || window.innerHeight || 0,
    ];
  }

  /* 引数で寸法を渡せるようにしてある（開発時に強制するため）。
     resizeイベントやResizeObserverから呼ばれたときは無視される。 */
  function resize(forceW, forceH) {
    const [w0, h0] = (typeof forceW === 'number' && typeof forceH === 'number')
      ? [forceW, forceH]
      : viewportSize();
    const w = Math.round(w0);
    const h = Math.round(h0);
    if (w < 8 || h < 8) return;              // 寸法が決まる前に測らない
    if (w === vw && h === vh) return;
    vw = w; vh = h;

    renderer.setSize(w, h, false);
    shared.uAspect.value = w / h;
    measureHero();
  }

  /**
   * ヒーローが画面のどこにいて、写真をどう貼るか。
   * object-fit:cover / object-position:center center の計算をここで済ませ、
   * シェーダには「画面座標 → 写真のUV」の一次変換だけを渡す。
   */
  let heroVisible = true;

  function measureHero() {
    const r = hero.getBoundingClientRect();
    heroVisible = r.bottom > 0 && r.top < vh;
    const iw = img.naturalWidth, ih = img.naturalHeight;
    if (!iw || !ih || r.width < 1 || r.height < 1) return;

    const s = Math.max(r.width / iw, r.height / ih);
    const dw = iw * s, dh = ih * s;
    const ox = (r.width - dw) / 2;
    const oy = (r.height - dh) / 2;

    surface.setHero({
      top: 1 - r.top / vh,
      bottom: 1 - r.bottom / vh,
      feather: 1.2 / vh,                       // 1px強だけぼかして境目のギザギザを消す
      map: [
        vw / dw, (-r.left - ox) / dw,          // texU = ax·p.x + bx
        vh / dh, 1 - (vh - r.top - oy) / dh,   // texV = ay·p.y + by
      ],
    });
  }

  resize();

  /* ---------- 品質ガバナ ---------- */
  const basePR = Math.min(window.devicePixelRatio || 1, preset.dpr);
  const gov = new Governor({
    onChange(g) {
      renderer.setPixelRatio(basePR * g.dprScale);
      renderer.setSize(vw, vh, false);
    },
  });
  renderer.setPixelRatio(basePR);

  /* ---------- カーソルが水を押す ---------- */
  const ptr = { x: 0.5, y: 0.5, px: 0.5, py: 0.5, moved: 0 };
  let poke = 0;

  function onPointer(e) {
    const t = e.touches ? e.touches[0] : e;
    const nx = t.clientX / vw;
    const ny = 1 - t.clientY / vh;
    ptr.moved += Math.hypot(nx - ptr.x, ny - ptr.y);
    ptr.x = nx; ptr.y = ny;
  }
  addEventListener('pointermove', onPointer, { passive: true });
  addEventListener('touchmove', onPointer, { passive: true });

  /* ---------- スクロール ---------- */
  let scrollStir = 0;
  let lastScroll = window.scrollY;

  function onScroll() {
    const y = window.scrollY;
    scrollStir += Math.min(0.06, Math.abs(y - lastScroll) / 2400);
    lastScroll = y;
    const max = Math.max(1, document.documentElement.scrollHeight - vh);
    shared.uScroll.value = Math.min(1, Math.max(0, y / max));
  }
  addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---------- 走らせる ---------- */
  const hud = createHud();
  let last = performance.now();
  let running = false;
  let disposed = false;
  let half = 0;

  if (useCompute && ripple.computeInit) await renderer.computeAsync(ripple.computeInit);

  function animate(now) {
    /* 起動時にビューポートの寸法が取れていなかった場合、取れるまで測り直す */
    if (vw < 8 || vh < 8) { resize(); if (vw < 8 || vh < 8) return; }

    const raw = Math.max(0, now - last);
    last = now;
    gov.sample(raw);
    shared.uTime.value += Math.min(raw, 50) / 1000;

    /* カーソルの勢いとスクロールの揺すりを、水を押す力にする */
    const stir = Math.min(0.9, ptr.moved * 6 + scrollStir);
    ptr.moved *= 0.55;
    scrollStir *= 0.80;
    poke += (stir - poke) * 0.35;
    ripple.setPoke(poke * 0.007);
    shared.uPointer.value.set(ptr.x, ptr.y);

    /* 貼り位置は毎フレーム測り直す。フォントの読み込みや折りたたみの開閉で
       ヒーローの高さが変わっても、写真がずれない。rectの読み出しは1回だけ。 */
    measureHero();

    /* 水が静まっていて、ヒーローも画面に無いときは半分の頻度で描く。
       絵はほとんど変わらないので、電池を無駄に使わない。 */
    const calm = poke < 0.02 && !heroVisible;
    half ^= 1;
    if (calm && half) return;

    if (useCompute) for (const step of ripple.computeSteps) renderer.compute(step);
    renderer.render(scene, camera);

    if (hud.visible) {
      hud.update({
        backend: backendName,
        sim: useCompute ? `波動方程式 ${preset.grid}×${preset.grid}` : '解析（コンピュート無し）',
        grid: preset.grid,
        dpr: renderer.getPixelRatio(),
        ms: gov.ema,
        poke,
        scroll: shared.uScroll.value,
        draws: renderer.info.render.drawCalls,
      });
    }
  }

  function play() {
    if (running || disposed) return;
    running = true;
    last = performance.now();
    renderer.setAnimationLoop(animate);
  }
  function pause() {
    if (!running) return;
    running = false;
    renderer.setAnimationLoop(null);
  }

  document.addEventListener('visibilitychange', () => {
    document.hidden ? pause() : play();
  });
  addEventListener('resize', resize, { passive: true });
  /* アドレスバーの伸縮など、resizeイベントの来ない高さ変化も拾う */
  new ResizeObserver(resize).observe(canvas);

  host.classList.add('is-ocean');
  requestAnimationFrame(() => canvas.classList.add('is-live'));
  play();

  /* ---------- 触って遊べるように置いておく ---------- */
  const api = {
    backend: backendName,
    compute: useCompute,
    tier: preset.name,
    grid: preset.grid,
    renderer, scene, camera, ripple, surface, shared,
    resize, measureHero,
    dispose() {
      disposed = true;
      pause();
      removeEventListener('pointermove', onPointer);
      removeEventListener('touchmove', onPointer);
      removeEventListener('scroll', onScroll);
      removeEventListener('resize', resize);
      surface.dispose();
      photo.dispose();
      renderer.dispose();
      canvas.remove();
      host.classList.remove('is-ocean');
    },
  };
  window.__ocean = api;

  if (window.console && console.info) {
    console.info(
      `%c🌊 三浦 海の学校%c  ${backendName} / ${preset.name}\n` +
      (useCompute
        ? `サイトの上に張った水面は、${preset.grid}×${preset.grid}のグリッドで2次元の波動方程式を\n` +
          `コンピュートシェーダで解いています。カーソルを動かすと波が立ち、伝わり、\n` +
          `壁で跳ね返り、干渉して収まります。波の形はどこにも書いていません。`
        : `このブラウザにWebGPUが無いため、水面は解析的な近似で動いています。`) + '\n' +
      `Shift+O で計測パネル、window.__ocean.ripple.params / .surface.params で係数を変えられます。\n` +
      `ダイビングの相談はこちら → https://miura-diving.com/contact/`,
      'font-weight:bold;font-size:14px;color:#0d9aa8',
      'color:#7a8a90'
    );
  }

  return api;
}
