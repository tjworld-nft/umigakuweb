/**
 * 夏割の帯を「水の中」にする層。
 *
 * ヒーローの写真は海を**外から**見ている。その真下に来るこの帯では、
 * 読者は水の中にいて、水面を**下から**見上げている ── スクロールで潜る。
 *
 * 描いているものは4つ。どれも同じ水面（ripple.js の波動方程式）から出ている。
 *
 *   水面   … 帯の上端に、下から見た水面がある。境目の高さは波の高さそのもの。
 *            雫が落ちれば、この線が実際に上下する。
 *   光の柱 … 水面を通った陽射しが、深さに応じて広がりながら沈んでいく。
 *            波の傾きで根元が揺れるので、光の柱も一緒に揺れる。
 *   コースティクス … 光が集まった網目。ヒーローや白地の面と同じ式。
 *   泡     … 下から上がってきて、水面で消える。
 *
 * 色は「夏の海」。ピンクは平らな塗りではなく**光**として残してある
 * （水面のぎらつきと光の柱を暖色に寄せてある）。白抜き文字のコントラストは
 * 平らなピンクだった頃の2.9:1から大きく上がる ── 測り方は AGENTS.md を見ること。
 *
 * 板は帯の矩形に幾何学的に重ねる（ヒーロー写真と同じやり方）。
 * canvasが載らない端末では、CSSのグラデーションが同じ配色でそのまま残る。
 */
import {
  Mesh, PlaneGeometry, MeshBasicNodeMaterial, NormalBlending, Color,
  LinearSRGBColorSpace, Vector4,
} from 'three/webgpu';
import {
  Fn, float, vec2, vec3, vec4, uniform, uv,
  dot, pow, mix, clamp, smoothstep, sin, exp, abs, floor, fract, max,
} from 'three/tsl';

import { waterTools } from './surface.js';

const LUMA = /*@__PURE__*/ vec3(0.2126, 0.7152, 0.0722);

/* CSS側の linear-gradient(180deg, …) と同じ配色。ここを変えたら index.html も直すこと。
   上端は「その色の上に白抜き文字が乗っても4.5:1を切らない」明るさに止めてある。 */
export const PALETTE = {
  top: '#0F849E',    // 水面のすぐ下
  mid: '#095574',    // 帯の中ほど（45%）
  deep: '#063A57',   // 帯の底
};

/**
 * CSSと同じ数字のまま色を持つ。
 * ⚠ `new Color('#0F849E')` は**リニア空間に変換して**保持する。この層はCSSに合わせて
 * sRGB空間で組み立てて最後に一度だけリニアへ戻すので、変換されると二重に暗くなる
 * （帯の中ほどで実測 (14,117,148) のはずが (12,69,103) になっていた）。
 * 作業色空間をリニアsRGBと明示して入れると、変換なしで数字がそのまま入る。
 */
function srgb(hex) {
  const n = parseInt(hex.slice(1), 16);
  return new Color().setRGB(
    ((n >> 16) & 255) / 255,
    ((n >> 8) & 255) / 255,
    (n & 255) / 255,
    LinearSRGBColorSpace
  );
}

/** 0〜1の擬似乱数を2つ。泡の初期配置と大きさに使う */
const hash2 = /*@__PURE__*/ Fn(([p]) => {
  const n = vec2(
    dot(p, vec2(127.1, 311.7)),
    dot(p, vec2(269.5, 183.3))
  );
  return fract(sin(n).mul(43758.5453));
});

/**
 * 泡の層。格子の1マスに1粒、上へ流す。
 * 1画素が見るのは自分のマスだけなので、粒がどれだけ増えても描画量は変わらない。
 * 粒はマスからはみ出さない大きさに抑えてある（はみ出すと縁で切れる）。
 */
const bubbleLayer = /*@__PURE__*/ Fn(([p, cells, speed, seed, t, ceiling]) => {
  const q = vec2(p.x.mul(cells), p.y.mul(cells).add(t.mul(speed)).add(seed));
  const cell = floor(q);
  const f = fract(q);
  const r = hash2(cell.add(seed));

  /* 粒の大きさと、左右のゆらぎ（泡はまっすぐには上がらない） */
  const rad = mix(float(0.028), float(0.085), r.x.mul(r.x));
  const sway = sin(t.mul(mix(0.7, 1.8, r.y)).add(r.x.mul(6.28)).add(cell.y)).mul(0.10);
  /* マスの中で左右にばらけさせる。揃っていると格子だとすぐ分かってしまう */
  const center = vec2(float(0.5).add(sway).add(r.y.sub(0.5).mul(0.55)), 0.5);

  const d = f.sub(center);
  const dist = d.length();

  /* ふちが明るく、中は少しだけ明るい ── 泡は光を屈折させて縁で返す。
     smoothstepの2つの境目は必ず増加方向にする（逆向きは未定義動作） */
  const body = float(1).sub(smoothstep(rad.mul(0.5), rad.mul(0.95), dist)).mul(0.16);
  const e = dist.sub(rad.mul(0.85)).div(rad.mul(0.22));
  const rim = exp(e.mul(e).negate()).mul(0.55);

  /* 上から光が来ているので、玉の左上に小さな写り込みが出る。
     これが無いと、輪郭だけの「輪」に見えて泡に見えない。 */
  const sp = d.sub(vec2(rad.mul(-0.34), rad.mul(0.34))).div(rad.mul(0.26));
  const spec = exp(sp.dot(sp).negate()).mul(0.9);

  /* まばらに。全部のマスに泡がいると水槽の泡石になる */
  const alive = smoothstep(float(0.55), float(0.78), r.x);

  /* 水面に着いたら消える（浮かんで、割れる） */
  const pop = smoothstep(float(0), float(0.06), ceiling.sub(p.y));

  return max(body.add(rim).add(spec), float(0)).mul(alive).mul(pop);
});

export function createCampaignPanel({ shared, ripple, tier = 2 }) {
  const { uTime, uAspect, uFade } = shared;
  const tools = waterTools({ shared, ripple });

  /* 帯が画面のどこにいるか（x, y, w, h ／ 0〜1・下が0）。
     水面は画面全体で1枚なので、板の中のUVを画面座標に直すのに使う。 */
  const uBox = uniform(new Vector4(0, 0, 1, 1));
  /* 帯の上端から水面までの距離（板の高さに対する割合）。JS側が実寸から決める */
  const uSurface = uniform(0.10);
  /* 水面の高さの効き。雫が落ちたとき、境目がどれだけ動くか */
  const uWave = uniform(0.045);
  /* 陽射しの落ちてくる位置（板ローカルのx） */
  const uSunX = uniform(0.66);
  const uRays = uniform(0.30);        // 光の柱の強さ
  const uSparkle = uniform(0.26);     // コースティクスの強さ
  const uBubbles = uniform(tier >= 1 ? 1 : 0);
  const uGlass = uniform(0.85);       // 水面の映り込みの強さ
  /* 文字の乗っている帯（水面の線の下から、見出しの塊の下まで）。
     ここだけ足す光を弱める。JS側が実寸から入れる。帯の上端からの割合。 */
  const uGuard0 = uniform(0.10);
  const uGuard1 = uniform(0.30);
  const uGuardFloor = uniform(0.13);

  const cTop = uniform(srgb(PALETTE.top));
  const cMid = uniform(srgb(PALETTE.mid));
  const cDeep = uniform(srgb(PALETTE.deep));
  const cSun = uniform(srgb('#FFE3EC'));    // 陽射し。夏割のピンクをここに残す
  const cSky = uniform(srgb('#CFF3FF'));    // 水面が返す空の色

  const material = new MeshBasicNodeMaterial({
    transparent: true, depthWrite: false, depthTest: false,
    blending: NormalBlending, fog: false,
  });

  material.outputNode = Fn(() => {
    const local = uv();                       // 板の中の位置（0〜1・下が0）
    const s = vec2(                           // 画面座標（水面はこちらで引く）
      uBox.x.add(local.x.mul(uBox.z)),
      uBox.y.add(local.y.mul(uBox.w))
    );
    const t = float(1).sub(local.y);           // 帯の上端からの割合

    /* ---- 水面の位置 ----------------------------------------------------
       帯の上端の少し下に水面がある。その高さは波そのもの。
       画面のいちばん上（uBox.y + uBox.w）あたりの水を、xだけずらして読む。 */
    const surfS = vec2(s.x, uBox.y.add(uBox.w).sub(uSurface.mul(uBox.w)));
    const drift = vec2(surfS.x.mul(uAspect).add(uTime.mul(0.008)), surfS.y.sub(uTime.mul(0.011)));
    const h = ripple.heightAt(surfS, drift);
    /* 大きい波ほど頭打ちにする。生の高さをそのまま使うと、雫の真上だけが
       角の生えたような尖った峰になって、水面ではなく破綻に見える。
       小さい揺れは線形のまま（＝うねりの表情は残る）。 */
    const swing = h.div(float(1).add(abs(h).mul(0.9)));
    const line = uSurface.sub(swing.mul(uWave));   // 上端からの距離。波が高いと線は上へ

    /* ---- 下地 ----------------------------------------------------------
       CSSの linear-gradient(180deg, top 0%, mid 45%, deep 100%) と同じ計算。
       ここがずれると、canvasが出た瞬間に帯の色が飛ぶ。 */
    const g1 = mix(cTop, cMid, clamp(t.div(0.45), 0, 1));
    const base = mix(g1, cDeep, clamp(t.sub(0.45).div(0.55), 0, 1));

    /* ---- 水（水面より下） ------------------------------------------- */
    const grad = tools.slopeAt(s, uAspect);
    const caustic = tools.causticAt(s, grad, uAspect);
    const depth = max(t.sub(line), float(0));       // 水面からの深さ

    /* 光の柱。水面の一点から扇形に広がる ＝ 深さで割った横位置が柱の番号になる。
       根元を波の傾きでずらすと、水面のうねりに合わせて柱全体が揺れる。
       細く・数を絞って落とす。太く広げると、ただの霞になって帯が眠くなる。 */
    const fan = local.x.sub(uSunX).sub(depth.mul(0.10)).div(depth.add(0.09));
    const w = fan.mul(7.4).add(uTime.mul(0.09)).add(grad.x.mul(1.8));
    const shaft = pow(abs(sin(w)), 16.0).mul(0.66)
      .add(pow(abs(sin(w.mul(0.43).add(2.3))), 22.0).mul(0.34));
    const rayFade = exp(depth.mul(-3.4))                       // 深いほど届かない
      .mul(smoothstep(float(0), float(0.04), depth))           // 水面のすぐ下から始まる
      .mul(mix(0.55, 1.0, caustic));                           // 網目と一緒に瞬く
    const rays = shaft.mul(rayFade).mul(uRays);

    /* コースティクス。光が集まった所だけ明るい */
    const web = caustic.mul(uSparkle).mul(exp(depth.mul(-1.7)));

    /* 泡。上がってきて水面で消える。
       板は横長なので、そのままマス目を切ると泡が楕円になる。帯の縦横比で伸ばす。 */
    const boxAspect = uAspect.mul(uBox.z).div(uBox.w);
    const bp = vec2(local.x.mul(boxAspect), local.y);
    const ceiling = float(1).sub(line);
    const bubbles = bubbleLayer(bp, float(4.0), float(0.040), float(3.7), uTime, ceiling).mul(0.55)
      .add(bubbleLayer(bp.add(vec2(0.37, 0)), float(8.0), float(0.105), float(11.3), uTime, ceiling).mul(0.24))
      .mul(uBubbles)
      .mul(smoothstep(float(0), float(0.12), depth));          // 水面の直下だけ静かに

    /* ---- 水面（下から見上げる） --------------------------------------
       水中から見上げた水面は、明るい板ではない。ほとんどは水そのものを映した
       暗い銀色で、太陽の方向にだけ明るい窓が開く（スネルの窓）。
       ここを一様に明るくすると、帯の上端が白い霞になって見出しが読めなくなる。 */
    const under = float(1).sub(smoothstep(line.sub(0.004), line.add(0.004), t));  // 1 = 水面より上
    const glare = pow(clamp(float(1).sub(abs(local.x.sub(uSunX)).mul(1.9)), 0, 1), 4.5)
      .mul(mix(0.45, 1.0, caustic));
    const silver = mix(cDeep, cTop, clamp(grad.y.mul(1.6).add(0.30), 0, 1));  // 水を映した面
    const sky = mix(silver, cSky, clamp(glare.mul(0.85), 0, 1));              // 太陽の側だけ空が抜ける
    /* 窓の芯は暖色に寄せる。夏割のピンクを、平らな塗りではなく陽射しとして残す */
    const mirror = mix(sky, cSun, clamp(glare.mul(glare).mul(0.75), 0, 1));
    const edge = t.sub(line).div(0.005);
    const skin = exp(edge.mul(edge).negate());                 // 境目の明線

    /* 水面より上に見えている、うねりの重なり。
       ここを一様に塗ると霞にしか見えない。水面は近くほど大きく、遠くほど詰まって
       見えるので、水面からの距離で縞をつくり、波の高さで位相をずらす。 */
    const above = clamp(line.sub(t).div(max(line, float(0.02))), 0, 1);
    const crest = pow(abs(sin(pow(above, float(0.65)).mul(11.0).add(h.mul(2.6)).sub(uTime.mul(0.5)))), float(2.5))
      .mul(float(1).sub(above.mul(0.55)))     // 遠く（帯の上端）ほど淡く
      .mul(under);

    /* ---- 合成 ----------------------------------------------------------
       足す光は「白までの余白」で割り引く。そうしないと、水面のあたりが
       白に張り付いて、上に載っている白抜き文字が読めなくなる（ヒーローで踏んだのと同じ罠）。
       さらに、見出しの乗っている帯では足す光そのものを弱める。 */
    /* コースティクスは光を配り直すもので、光を増やすものではない。
       集まった所を足すだけにすると帯全体が持ち上がって、CSSの下地から色が浮く
       （canvasが載った瞬間に帯が明るくなって見える）。抜けた側は同じだけ沈める。 */
    const water = base.mul(mix(float(1), float(0.82), float(1).sub(caustic)));
    const col0 = mix(water, mirror, under.mul(uGlass));

    /* 文字の帯の中では、足す光を uGuardFloor まで落とす。
       境目は少しぼかす（急に暗くなると、そこに帯があることが見えてしまう）。 */
    const fade = float(0.03);
    const inText = smoothstep(uGuard0.sub(fade), uGuard0, t)
      .mul(float(1).sub(smoothstep(uGuard1, uGuard1.add(fade), t)));
    const guard = mix(float(1), uGuardFloor, inText);

    const lum = dot(col0, LUMA);
    const head = clamp(float(1).sub(lum), 0, 1);
    /* 見出しの上で弱めるのは、面として広がる光だけ。
       水面の明線は6pxの線で、文字の20px上にしか出ない ── ここを鈍らせると
       「水面がある」ことが伝わらなくなるので、guardの外に置く。 */
    const spread = cSun.mul(rays.add(glare.mul(under).mul(0.55)).add(crest.mul(0.30)))
      .add(cSky.mul(web).add(cSky.mul(bubbles)));
    const linelight = cSky.mul(skin.mul(0.55));

    const lit = col0.add(spread.mul(head).mul(guard)).add(linelight.mul(head));

    /* 帯のいちばん下は、光の届かない側へ落とす（帯の下端が締まる） */
    const floorDim = smoothstep(float(0.86), float(1.0), t).mul(0.12);
    const out = clamp(lit.mul(float(1).sub(floorDim)), 0, 1);

    /* 出力はリニア空間。ここまではCSSと同じsRGB空間で組み立てている */
    return vec4(pow(out, float(2.2)), uFade);
  })();

  const geometry = new PlaneGeometry(1, 1);
  const mesh = new Mesh(geometry, material);
  mesh.frustumCulled = false;
  mesh.renderOrder = 2;

  return {
    object: mesh,
    material,
    /**
     * 帯の矩形に板を重ねる。
     * @param {DOMRect} r        帯の位置（ビューポート基準・CSSピクセル）
     * @param {number} vw
     * @param {number} vh        ビューポートの大きさ
     * @param {number} surfacePx 帯の上端から水面までの実寸（CSSが決めている）
     * @param {DOMRect} [headRect] 見出しの塊。ここでは足す光を弱める
     */
    setBox(r, vw, vh, surfacePx, headRect) {
      mesh.position.set(
        (r.left + r.width / 2) / vw - 0.5,
        0.5 - (r.top + r.height / 2) / vh,
        0.002
      );
      mesh.scale.set(r.width / vw, r.height / vh, 1);
      uBox.value.set(r.left / vw, 1 - (r.top + r.height) / vh, r.width / vw, r.height / vh);

      /* 水面の高さはCSSが決めている（帯の上端の余白の下辺）。
         割合で決め打ちにすると、帯が縦に伸びるスマホで水面が真ん中まで下りてくる。 */
      const px = surfacePx > 0 ? surfacePx : r.height * 0.1;
      uSurface.value = Math.min(0.25, Math.max(0.02, px / Math.max(1, r.height)));

      /* 文字の帯 ── 水面の線のすぐ下から、見出しの塊の下まで（帯の上端からの割合） */
      if (headRect && headRect.height > 0 && r.height > 0) {
        uGuard0.value = uSurface.value + 0.004;
        uGuard1.value = Math.min(0.9, (headRect.bottom - r.top) / r.height + 0.01);
      }
    },
    params: {
      surface: uSurface, wave: uWave, rays: uRays, sparkle: uSparkle,
      bubbles: uBubbles, glass: uGlass, sunX: uSunX, ...tools.params,
    },
    dispose() { geometry.dispose(); material.dispose(); },
  };
}
