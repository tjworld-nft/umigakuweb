/**
 * 三浦 海の学校 — 「水面の下」セクションの海。
 *
 * ヒーローの浜辺の写真はそのまま。その次のセクションを丸ごと水にして、
 * 水そのもの・光条・コースティクス・マリンスノー・泡・魚群を描く。
 * canvasが載らない端末では、CSSの同じ配色のグラデーションが残るだけで
 * セクションとしては成立する。
 *
 * 読み込むかどうかは js/ocean/boot.js が先に判断済みで、
 * ここまで来た端末は「描いてよい」と分かっている。
 */
import { WebGPURenderer, Scene, PerspectiveCamera, Vector3, Color } from 'three/webgpu';
import { uniform } from 'three/tsl';

import { PRESETS, detectTier, Governor } from './quality.js';
import { createSchool } from './fish.js';
import { createWater } from './water.js';
import { createMotes, createBubbles } from './drift.js';
import { createHud } from './hud.js';

const FOV = 42;
const VOLUME_Z = -26;      // 群れを泳がせる箱の中心の奥行き
const VOLUME_DEPTH = 12;   // その前後の厚み

export async function start({ hero, webgpu, cores, memory }) {
  const tier = detectTier({ webgpu, cores, memory });
  const preset = PRESETS[tier];

  /* ---------- canvas ---------- */
  const canvas = document.createElement('canvas');
  canvas.className = 'ocean-canvas';
  canvas.setAttribute('aria-hidden', 'true');
  hero.appendChild(canvas);

  /* ---------- renderer ---------- */
  const renderer = new WebGPURenderer({
    canvas,
    alpha: true,
    antialias: tier >= 2,
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

  /* ---------- scene ---------- */
  const scene = new Scene();
  const camera = new PerspectiveCamera(FOV, 1, 0.5, 120);
  camera.position.set(0, 0, 0);
  scene.add(camera);

  const volume = {
    center: uniform(new Vector3(0, 0, VOLUME_Z)),
    half: uniform(new Vector3(12, 8, VOLUME_DEPTH)),
    near: uniform(8),
    far: uniform(Math.abs(VOLUME_Z) + VOLUME_DEPTH + 8),
  };

  const shared = {
    uTime: uniform(0),
    uDelta: uniform(1 / 60),
    uPointer: uniform(new Vector3(0, 0, 9999)),
    uPointerR: uniform(4.6),
    uPointerK: uniform(9.0),
    uFade: uniform(1),
    /* 文字ブロックの位置。水も魚もここを避けて薄くなる */
    uGuardCenter: uniform(0.5),
    uGuardHalf: uniform(0.28),
    colors: {
      /* 水の色はCSSの .dive のグラデーションと合わせてある。
         canvasがフェードインしても色が飛ばないように。 */
      waterShallow: new Color('#1AA2B6'),
      waterMid: new Color('#06607C'),
      waterDeep: new Color('#02273B'),
      fishLit: new Color('#f4feff'),   // 横腹が光を返した瞬間の銀
      fishDeep: new Color('#17566B'),  // 背側。深い水に溶ける色
      ray: new Color('#a9e9ff'),
      caustic: new Color('#cdf8ff'),
      surface: new Color('#e8feff'),
      mote: new Color('#bfeeff'),
      bubble: new Color('#e6feff'),
    },
  };

  const school = createSchool({
    count: preset.fish,
    neighbors: preset.neighbors,
    useCompute,
    shared,
    volume,
  });
  const water = createWater({ shared });
  const motes = createMotes({ count: preset.motes, shared, volume });
  const bubbles = createBubbles({ count: preset.bubbles, shared, volume });

  scene.add(school.object, motes.object, bubbles.object);
  water.attachTo(camera, 2);

  /* ---------- 画面サイズに合わせて箱と板を作り直す ---------- */
  let vw = 0, vh = 0;
  let heroTop = 0, heroH = 1;
  let density = 1;      // 縦長の画面は箱が細くなるぶん、数を減らさないと混みすぎる

  /** 群れ・微粒子・泡の数を、品質ガバナと画面比の両方から決める */
  function applyCounts() {
    const k = gov.scale * density;
    school.setCount(preset.fish * k);
    motes.setCount(preset.motes * k);
    bubbles.setCount(preset.bubbles * k);
  }

  function layout() {
    const rect = hero.getBoundingClientRect();
    /* スクロール量の基準はここで取っておく。毎フレームrectを読むとレイアウトを
       強制させてしまうので、変化したときだけ測る。 */
    heroTop = rect.top + window.scrollY;
    heroH = Math.max(1, rect.height);

    const w = Math.round(rect.width);
    const h = Math.round(rect.height);

    /* まだ寸法が決まっていない（折りたたみの中・非表示など）ときに測ると、
       縦横比が潰れた箱ができてしまう。決まるまで何もしない。 */
    if (w < 8 || h < 8) return;
    if (w === vw && h === vh) return;
    vw = w; vh = h;

    camera.aspect = w / h;
    camera.updateProjectionMatrix();
    renderer.setSize(w, h, false);

    /* 箱は画面より少し広くする。端から出入りする魚がいた方が海に見える */
    const halfH = Math.tan((FOV * Math.PI) / 360) * Math.abs(VOLUME_Z);
    volume.half.value.set(halfH * camera.aspect * 1.25, halfH * 1.15, VOLUME_DEPTH);

    density = Math.min(1, Math.max(0.5, camera.aspect / 1.6));
    applyCounts();

    water.attachTo(camera, 2);

    /* 文字がどこに乗っているか測って、そこだけ光を弱める。読ませるのが先 */
    const content = hero.querySelector('.dive-inner, .hero-content');
    if (content) {
      const c = content.getBoundingClientRect();
      const centerY = (c.top + c.height / 2 - rect.top) / h;
      shared.uGuardCenter.value = 1 - centerY;                       // シェーダのyは下端が0
      shared.uGuardHalf.value = Math.min(0.48, (c.height / h) * 0.62);
    }
  }

  /* ---------- 品質ガバナ ---------- */
  const basePR = Math.min(window.devicePixelRatio || 1, preset.dpr);
  const gov = new Governor({
    tier,
    onChange() {
      renderer.setPixelRatio(basePR * gov.dprScale);
      renderer.setSize(vw, vh, false);
      applyCounts();
    },
  });
  renderer.setPixelRatio(basePR);
  layout();

  /* ---------- ポインタ＝天敵 ---------- */
  const coarse = matchMedia('(pointer: coarse)').matches;
  const ndc = { x: 0, y: 0, active: false };
  const pointerAt = new Vector3(0, 0, 9999);
  const pointerTo = new Vector3(0, 0, 9999);
  const ray = new Vector3();

  function ndcToVolume(x, y, out) {
    ray.set(x, y, 0.5).unproject(camera);
    ray.sub(camera.position).normalize();
    const dist = (VOLUME_Z - camera.position.z) / ray.z;
    return out.copy(camera.position).addScaledVector(ray, dist);
  }

  function onMove(e) {
    const rect = hero.getBoundingClientRect();
    const t = e.touches ? e.touches[0] : e;
    ndc.x = ((t.clientX - rect.left) / rect.width) * 2 - 1;
    ndc.y = -(((t.clientY - rect.top) / rect.height) * 2 - 1);
    ndc.active = true;
  }
  function onLeave() { ndc.active = false; }

  if (!coarse) {
    hero.addEventListener('pointermove', onMove, { passive: true });
    hero.addEventListener('pointerleave', onLeave, { passive: true });
  } else {
    hero.addEventListener('touchmove', onMove, { passive: true });
    hero.addEventListener('touchend', onLeave, { passive: true });
  }

  /* ---------- 走らせる ---------- */
  const hud = createHud();
  let frame = 0;
  let last = performance.now();
  let running = false;
  let disposed = false;

  if (useCompute) await renderer.computeAsync(school.computeInit);

  function animate(now) {
    const raw = Math.max(0, now - last);
    last = now;
    const dt = Math.min(raw, 50) / 1000;   // タブ復帰で群れが吹き飛ばないように上限
    gov.sample(raw);

    shared.uTime.value += dt;
    shared.uDelta.value = dt;
    frame++;
    school.tickFrame(frame);

    /* 画面をどれだけ覆っているかで濃度を決める。端では静かに消える */
    const vpH = window.innerHeight;
    const top = heroTop - window.scrollY;
    const covered = Math.min(top + heroH, vpH) - Math.max(top, 0);
    const cover = Math.min(1, Math.max(0, covered / Math.min(heroH, vpH)));
    const fade = Math.min(1, Math.max(0, (cover - 0.06) / 0.34));
    shared.uFade.value = fade;

    /* 通り過ぎる間、カメラをゆっくり沈める。スクロールが“潜行”になる */
    const through = Math.min(1, Math.max(0, (window.scrollY + vpH - heroTop) / (heroH + vpH)));
    camera.position.y = (0.5 - through) * 3.2;
    camera.position.z = (through - 0.5) * 2.2;

    if (fade > 0.01) {
      /* タッチ端末は指がないので、ゆっくり漂う流れを天敵の代わりに置く */
      if (ndc.active) {
        ndcToVolume(ndc.x, ndc.y, pointerTo);
      } else if (coarse) {
        const t = shared.uTime.value * 0.16;
        ndcToVolume(Math.sin(t * 0.9) * 0.6, Math.cos(t * 0.67) * 0.4, pointerTo);
      } else {
        pointerTo.set(0, 0, 9999);
      }
      pointerAt.lerp(pointerTo, 0.09);
      shared.uPointer.value.copy(pointerAt);

      if (useCompute) renderer.compute(school.computeStep);
      renderer.render(scene, camera);
    }

    if (hud.visible) {
      hud.update({
        backend: backendName,
        compute: useCompute,
        fish: school.object.count, fishMax: school.maxCount,
        neighbors: preset.neighbors,
        motes: motes.object.count, bubbles: bubbles.object.count,
        dpr: renderer.getPixelRatio(),
        ms: gov.ema,
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

  /* 画面に無い間・タブが裏の間は1フレームも回さない */
  let inView = true;
  const io = new IntersectionObserver(
    (entries) => {
      inView = entries[0].isIntersecting;
      inView && !document.hidden ? play() : pause();
    },
    { threshold: 0 }
  );
  io.observe(hero);
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) pause();
    else if (inView) play();
  });

  const ro = new ResizeObserver(layout);
  ro.observe(hero);
  addEventListener('scroll', () => { heroTop = hero.getBoundingClientRect().top + window.scrollY; }, { passive: true });

  /* 何で描いているのかを、その場の実測値で書いておく */
  const note = hero.querySelector('[data-ocean-note]');
  if (note) {
    note.textContent = useCompute
      ? `この背景はWebGPUのコンピュートシェーダで、${preset.fish.toLocaleString('ja-JP')}匹の群れをリアルタイムに計算して描いています`
      : `この背景はWebGL2で、${preset.fish.toLocaleString('ja-JP')}匹の群れをリアルタイムに描いています`;
  }

  hero.classList.add('is-ocean');
  requestAnimationFrame(() => canvas.classList.add('is-live'));
  play();

  /* ---------- 触って遊べるように置いておく ---------- */
  const api = {
    backend: backendName,
    compute: useCompute,
    tier: preset.name,
    renderer, scene, camera, school, water, motes, bubbles, shared, volume,
    layout,
    dispose() {
      disposed = true;
      pause();
      io.disconnect(); ro.disconnect();
      school.dispose(); water.dispose(); motes.dispose(); bubbles.dispose();
      renderer.dispose();
      canvas.remove();
      hero.classList.remove('is-ocean');
    },
  };
  window.__ocean = api;

  if (window.console && console.info) {
    console.info(
      `%c🌊 三浦 海の学校%c  ${backendName} / ${preset.name}\n` +
      `魚 ${preset.fish}匹の群れは${useCompute
        ? 'コンピュートシェーダのボイド（結合・整列・分離＋ポインタ回避）'
        : '手続き的な遊泳（このブラウザにWebGPUが無いため）'}で動いています。\n` +
      `Shift+O で計測パネル、window.__ocean.school で係数をいじれます。\n` +
      `ダイビングの相談はこちら → https://miura-diving.com/contact/`,
      'font-weight:bold;font-size:14px;color:#0d9aa8',
      'color:#7a8a90'
    );
  }

  return api;
}
