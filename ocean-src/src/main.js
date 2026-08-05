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
  WebGPURenderer, Scene, OrthographicCamera, Color, TextureLoader, Vector2,
  SRGBColorSpace, LinearFilter, ClampToEdgeWrapping,
} from 'three/webgpu';
import { uniform } from 'three/tsl';

import { PRESETS, detectTier, Governor } from './quality.js';
import { createRipple } from './ripple.js';
import { createWash, createHeroPhoto } from './surface.js';
import { createCampaignPanel } from './campaign.js';
import { buildSeaMask } from './sea-mask.js';
import { createHud } from './hud.js';

/**
 * 写真の中の水平線を実測する。空と海の境目は縦方向の輝度差がいちばん大きい行。
 * 数本の列で探して中央値を取る（雲や岩に引っぱられないように）。
 * 写真を差し替えても勝手に追随するので、数字を書き換えなくてよい。
 * 返すのはテクスチャのv座標（画像の下端が0）。
 */
function findHorizon(img) {
  const FALLBACK = 0.527;
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
    return rows[Math.floor(rows.length / 2)];       // 上からの割合
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

  /* ---------- 写真をテクスチャに ----------
     <img>要素をそのままTextureに包むのではなく、TextureLoaderで読み直す。
     ブラウザのキャッシュに載っているので通信は発生しない。 */
  const photo = await new Promise((res, rej) => {
    new TextureLoader().load(img.currentSrc || img.src, res, undefined, rej);
  });
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

  /* ---------- どこが海か ----------
     写真から切り出す。切り出せなかった（画素を読めない）ときは、
     写真の層はあきらめて素の<img>をそのまま見せる。人物の上で水が
     波打つくらいなら、ヒーローは静止画のままのほうがよい。 */
  const seaMask = buildSeaMask(img, findHorizon(img));
  const seaOk = !!seaMask && seaMask.userData.coverage > 0.02;

  const ripple = createRipple({ shared, useCompute, grid: preset.grid });
  const wash = createWash({ shared, ripple });
  const heroPhoto = seaOk ? createHeroPhoto({ shared, ripple, photo, seaMask }) : null;
  scene.add(wash.object);
  if (heroPhoto) scene.add(heroPhoto.object);

  /* ---------- 帯を水の中にする（夏割） ----------
     [data-ocean-panel] があるページだけ。無ければ何もしない。 */
  const panelEl = document.querySelector('[data-ocean-panel]');
  const panel = panelEl ? createCampaignPanel({ shared, ripple, tier }) : null;
  if (panel) scene.add(panel.object);

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
    if (!heroPhoto) { heroVisible = false; return; }
    const r = hero.getBoundingClientRect();
    const iw = img.naturalWidth, ih = img.naturalHeight;
    /* 寸法が取れないうちは写真を出さない。古い位置のまま描くと大きくずれる */
    const ok = iw > 0 && ih > 0 && r.width >= 1 && r.height >= 1 && vw >= 8 && vh >= 8;
    heroVisible = ok && r.bottom > 0 && r.top < vh;
    heroPhoto.object.visible = heroVisible;
    if (heroVisible) heroPhoto.setBox(r, vw, vh, iw, ih);
  }

  /* 帯も同じやり方で毎フレーム測り直す。画面の外にいる間は描かない。 */
  const waterlineEl = panelEl && panelEl.querySelector('[data-ocean-waterline]');
  const guardEl = panelEl && panelEl.querySelector('[data-ocean-guard]');

  /* 帯の水面が画面のどこにあるか（下端0の画面比）。雫の落とし所に使う。
     画面の外にあるときは null。 */
  let panelSurfaceY = null;

  function measurePanel() {
    if (!panel) return;
    const r = panelEl.getBoundingClientRect();
    const ok = r.width >= 1 && r.height >= 1 && vw >= 8 && vh >= 8;
    const visible = ok && r.bottom > 0 && r.top < vh;
    panel.object.visible = visible;
    panelSurfaceY = null;
    if (!visible) return;
    /* 水面の高さはCSSの余白が決めている（その要素の下辺）。実寸で渡す。 */
    const surfacePx = waterlineEl
      ? waterlineEl.getBoundingClientRect().bottom - r.top
      : 0;
    panel.setBox(r, vw, vh, surfacePx, guardEl ? guardEl.getBoundingClientRect() : null);

    const y = 1 - (r.top + surfacePx) / vh;
    if (y > 0.05 && y < 0.95) panelSurfaceY = y;
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

  /* 最後に水が動いた時刻。カーソル・タップ・雫のどれでも更新する。
     これが古くなったら、描く頻度を落としてよいと判断する。 */
  let lastStir = performance.now();

  /* ---------- 触ると雫が落ちる ----------
     スマホにはカーソルが無いので、「動かすと水が押される」は起きない。
     触れた所に雫を落とせば、指で触った手応えがそのまま水に出る。

     ただし**指を置いた時点では落とさない**。スマホのスクロールは必ず
     指を置くところから始まるので、押した瞬間に落とすと、画面を送るたびに
     水しぶきが上がることになる。離すまで待って、ほとんど動いていなければ
     「触った」と見なす。マウスのクリックはこの条件を自然に満たす。 */
  let tapX = 0, tapY = 0, tapAt = 0;

  function onDown(e) {
    tapX = e.clientX; tapY = e.clientY; tapAt = performance.now();
  }

  function onUp(e) {
    if (vw < 8 || vh < 8 || !tapAt) return;
    const moved = Math.hypot(e.clientX - tapX, e.clientY - tapY);
    const held = performance.now() - tapAt;
    tapAt = 0;
    if (moved > 10 || held > 600) return;        // スクロールや長押しは水を落とさない
    ripple.drop(e.clientX / vw, 1 - e.clientY / vh, 0.55);
    lastStir = performance.now();
  }
  addEventListener('pointerdown', onDown, { passive: true });
  addEventListener('pointerup', onUp, { passive: true });
  addEventListener('pointercancel', () => { tapAt = 0; }, { passive: true });

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

  /* ---------- ときどき雫が落ちる ----------
     誰も触っていない間、水面は完全に静まってしまう。そうなると
     「サイトの上に水が張ってある」ことが誰にも伝わらない（実際、指を置いていない
     お客さんには何も起きていなかった）。数秒に一滴だけ落として、水を生かしておく。 */
  let nextDrop = lastStir + 1400;

  /* 輪は3〜4秒で薄れる（減衰0.9965／ステップ）。重ならないくらいの間隔を空ける */
  function scheduleDrop(now) { nextDrop = now + 4500 + Math.random() * 5500; }

  function maybeDrop(now) {
    if (now < nextDrop) return;
    scheduleDrop(now);
    /* ヒーローが見えているときは海のあたり（画面の下寄り）へ。
       夏割の帯が出ているときは、その水面のあたりへ ── 雫が落ちて線が上下するのが
       いちばんよく見える場所。それ以外は画面のどこでもよい。 */
    const x = 0.10 + Math.random() * 0.80;
    let y;
    if (heroVisible) y = 0.16 + Math.random() * 0.32;
    else if (panelSurfaceY !== null) y = panelSurfaceY + (Math.random() - 0.45) * 0.16;
    else y = 0.10 + Math.random() * 0.80;
    ripple.drop(x, y, 0.30);
    lastStir = now;
  }

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
    measurePanel();

    if (poke > 0.02) lastStir = now;
    maybeDrop(now);

    /* 何も起きていない間は半分の頻度で描く。残っているのは何十秒もかけて流れる
       うねりだけなので、30fpsでも動きは同じに見える。ノートPCやスマホを
       開きっぱなしにされたときに、GPUを回し続けないための歯止め。 */
    const idle = now - lastStir > 4000;
    half ^= 1;
    if (idle && half) return;

    if (useCompute) for (const step of ripple.computeSteps) renderer.compute(step);
    ripple.tick(Math.min(raw, 50) / 1000);
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
  const ro = new ResizeObserver(resize);
  ro.observe(canvas);

  /* ---------- 全部たたむ ---------- */
  function teardown() {
    if (disposed) return false;
    disposed = true;
    pause();
    removeEventListener('pointermove', onPointer);
    removeEventListener('touchmove', onPointer);
    removeEventListener('pointerdown', onDown);
    removeEventListener('pointerup', onUp);
    removeEventListener('scroll', onScroll);
    removeEventListener('resize', resize);
    ro.disconnect();
    canvas.remove();
    host.classList.remove('is-ocean');
    /* 帯の背景をCSSに返す。canvasが消えたのに透明のままだと、帯が白く抜ける */
    if (panelEl) panelEl.classList.remove('is-watered');
    return true;
  }

  /* ---------- 描画が続けられなくなったとき ----------
     GPUのデバイスは失われることがある（ドライバの更新・省電力・長く裏に置かれたタブ）。
     そうなるとcanvasは最後の絵のまま固まる。ヒーロー写真は不透明に貼ってあるので、
     止まった絵が本物の写真の上に残り続けてしまう。そうなったら黙って畳んで、
     元のHTMLの見た目に戻す ── 水が無くてもページは完成している。 */
  function surrender(why) {
    if (!teardown()) return;
    if (window.console && console.debug) console.debug('[ocean] 描画を諦めました:', why);
  }

  const device = renderer.backend && renderer.backend.device;
  if (device && device.lost) {
    device.lost.then((info) => surrender((info && info.message) || 'device lost'));
  }
  canvas.addEventListener('webglcontextlost', (e) => {
    e.preventDefault();
    surrender('webglcontextlost');
  });

  host.classList.add('is-ocean');
  requestAnimationFrame(() => canvas.classList.add('is-live'));
  play();

  /* ---------- 帯の背景をCSSからcanvasへ渡す ----------
     帯のCSSグラデーションと、この層が描く下地は同じ配色にしてある。
     渡すのはcanvasが完全に出てから ── 先に透かすと、帯が一瞬白く抜ける。
     （canvasは1.8秒かけてフェードインする。transitionendが来ない環境もあるので
       タイマーと両掛けにして、先に来たほうで渡す） */
  if (panelEl) {
    let handed = false;
    const handOver = () => {
      if (handed || disposed) return;
      handed = true;
      panelEl.classList.add('is-watered');
    };
    canvas.addEventListener('transitionend', handOver, { once: true });
    setTimeout(handOver, 2200);
  }

  /* ---------- 触って遊べるように置いておく ---------- */
  const api = {
    backend: backendName,
    compute: useCompute,
    tier: preset.name,
    grid: preset.grid,
    renderer, scene, camera, ripple, wash, heroPhoto, panel, shared,
    resize, measureHero, measurePanel,
    dispose() {
      teardown();
      wash.dispose();
      if (heroPhoto) heroPhoto.dispose();
      if (panel) panel.dispose();
      if (seaMask) seaMask.dispose();
      photo.dispose();
      renderer.dispose();
    },
  };
  window.__ocean = api;

  if (window.console && console.info) {
    console.info(
      `%c🌊 三浦 海の学校%c  ${backendName} / ${preset.name}\n` +
      (useCompute
        ? `サイトの上に張った水面は、${preset.grid}×${preset.grid}のグリッドで2次元の波動方程式を\n` +
          `コンピュートシェーダで解いています。カーソルを動かす・画面に触れる・数秒に一度落ちる雫。\n` +
          `どれも同じ式に入って、波が立ち、伝わり、壁で跳ね返り、干渉して収まります。\n` +
          `波の形はどこにも書いていません。`
        : `このブラウザにWebGPUが無いため、水面は解析的な近似で動いています。`) + '\n' +
      `Shift+O で計測パネル、window.__ocean.ripple.params / .heroPhoto.params / .wash.params で係数を変えられます。\n` +
      `ダイビングの相談はこちら → https://miura-diving.com/contact/`,
      'font-weight:bold;font-size:14px;color:#0d9aa8',
      'color:#7a8a90'
    );
  }

  return api;
}
