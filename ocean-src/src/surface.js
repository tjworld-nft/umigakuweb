/**
 * 画面に重ねる2枚。
 *
 *  1. wash  … 画面いっぱい。同じ水面を通った光の影が、白い背景の上をゆっくり流れる。
 *  2. hero  … ヒーロー写真。**海の部分だけ**を水面の傾きで屈折させ、
 *             光の反射とコースティクスを乗せる。空・人・岩は1ピクセルも動かさない。
 *
 * heroの板は「ヒーローの矩形にぴったり重なる位置」に幾何学的に置く。
 * object-fit:cover の切り取りはUV属性として焼き込んであるので、
 * シェーダの中に貼り位置を合わせる式が一切ない（＝ずれる余地がない）。
 */
import {
  Mesh, PlaneGeometry, MeshBasicNodeMaterial, NormalBlending, Vector4,
} from 'three/webgpu';
import {
  Fn, float, vec2, vec3, vec4, uniform, uv, texture,
  dot, pow, mix, clamp, smoothstep,
} from 'three/tsl';

/* 水面の傾きとコースティクス。2枚で同じ水を見ているので共通にする */
function waterTools({ shared, ripple }) {
  const { uTime } = shared;
  const uCausticK = uniform(2.6);
  const uCausticP = uniform(4.0);
  const uCausticStep = uniform(0.012);

  /** 画面座標 s（0〜1）における水面の傾き */
  const slopeAt = /*@__PURE__*/ Fn(([s, aspect]) => {
    const drift = vec2(s.x.mul(aspect).add(shared.uTime.mul(0.008)), s.y.sub(uTime.mul(0.011)));
    return ripple.gradAt(s, drift);
  });

  /** 光線が集まっているか散っているか＝コースティクス */
  const causticAt = /*@__PURE__*/ Fn(([s, g, aspect]) => {
    const spread = slopeAt(s.add(g.mul(uCausticStep)), aspect).sub(g).length();
    return pow(clamp(float(1).sub(spread.mul(uCausticK)), 0, 1), uCausticP);
  });

  return { slopeAt, causticAt, params: { causticSharpness: uCausticP, causticContrast: uCausticK } };
}

/* ------------------------------------------------------------------------ */
/* 1. 画面いっぱいの層 — 白い紙の上に落ちる水の影                             */
/* ------------------------------------------------------------------------ */
export function createWash({ shared, ripple }) {
  const { uAspect, uScroll, uFade, colors } = shared;
  const tools = waterTools({ shared, ripple });

  const uAmbient = uniform(0.115);
  const cWash = uniform(colors.wash.clone());

  const material = new MeshBasicNodeMaterial({
    transparent: true, depthWrite: false, depthTest: false,
    blending: NormalBlending, fog: false,
  });

  material.outputNode = Fn(() => {
    const s = uv();                        // 画面いっぱいの板なので、これが画面座標
    const g = tools.slopeAt(s, uAspect);
    const caustic = tools.causticAt(s, g, uAspect);

    /* 光の当たっていないところに、淡い水色をごく薄く落とす。
       白い背景を暗くしすぎないよう、濃さは uAmbient で抑える。 */
    const a = float(1).sub(caustic)
      .mul(uAmbient)
      .mul(mix(0.85, 1.35, uScroll))       // 下へ行くほどわずかに濃く＝沈んでいく
      .mul(uFade);
    return vec4(cWash, a);
  })();

  const mesh = new Mesh(new PlaneGeometry(1, 1), material);
  mesh.frustumCulled = false;
  mesh.renderOrder = 0;

  return {
    object: mesh, material,
    params: { pageLight: uAmbient, ...tools.params },
    dispose() { mesh.geometry.dispose(); material.dispose(); },
  };
}

/* ------------------------------------------------------------------------ */
/* 2. ヒーロー写真 — 海だけが波立つ                                           */
/* ------------------------------------------------------------------------ */
export function createHeroPhoto({ shared, ripple, photo }) {
  const { uAspect, uFade, colors } = shared;
  const tools = waterTools({ shared, ripple });

  const uRefract = uniform(0.022);      // 海がどれだけ歪むか
  const uSeaCaustic = uniform(0.50);    // 海面のきらめき
  const uGlint = uniform(0.42);         // 光の反射
  const uHorizon = uniform(0.473);      // 写真の中の水平線（写真UVのv・下端が0）
  /* ヒーローが画面のどこにいるか（x, y, w, h ／ 0〜1・下が0）。
     水面は画面全体で1枚なので、板の中のUVを画面座標に直すのに使う。 */
  const uBox = uniform(new Vector4(0, 0, 1, 1));
  /* object-fit:cover の切り取り（u0, uの幅, v0, vの幅）。板のUVをこれで写真UVに直す */
  const uCrop = uniform(new Vector4(0, 1, 0, 1));

  const cSun = uniform(colors.sun.clone());
  const cScrim = uniform(colors.scrim.clone());

  const material = new MeshBasicNodeMaterial({
    transparent: true, depthWrite: false, depthTest: false,
    blending: NormalBlending, fog: false,
  });

  material.outputNode = Fn(() => {
    const local = uv();                                   // 板の中の位置（0〜1・下が0）
    const tuv = vec2(                                     // cover の切り取りを当てた写真UV
      uCrop.x.add(local.x.mul(uCrop.y)),
      uCrop.z.add(local.y.mul(uCrop.w))
    );
    const s = vec2(                                       // 画面座標に直す
      uBox.x.add(local.x.mul(uBox.z)),
      uBox.y.add(local.y.mul(uBox.w))
    );

    const g = tools.slopeAt(s, uAspect);
    const caustic = tools.causticAt(s, g, uAspect);

    /* 海だけを見分ける。水平線より下で、かつ青緑が強いところ。
       閾値は実際の写真の画素を測って決めた（リニア空間）:
         空 0.28〜0.39 ／ 海 0.26〜0.35 ／ 人 0.00〜0.16 ／ 岩と木 0.13
       空は水平線で、人と岩はこの青緑さで外れる。
       写真UVは v=0 が画像の下端なので、海は v が小さい側。 */
    const flat = texture(photo, tuv);
    const aqua = flat.g.add(flat.b).mul(0.5).sub(flat.r);
    const sea = float(1).sub(smoothstep(uHorizon.sub(0.012), uHorizon.add(0.045), tuv.y))
      .mul(smoothstep(0.17, 0.26, aqua));

    /* 海の部分だけ屈折させる */
    const raw = texture(photo, tuv.add(g.mul(uRefract).mul(sea))).rgb;

    /* 元のCSS（brightness 1.1 / saturate 1.08）と同じ見え方に揃える */
    const bright = raw.mul(1.1);
    const lum = dot(bright, vec3(0.2126, 0.7152, 0.0722));
    const shot = mix(vec3(lum), bright, 1.08);

    /* 海面のきらめきと、傾いた面が空を返す反射 */
    const glint = pow(clamp(g.y.mul(2.2).add(0.5), 0, 1), 8.0);
    const lit = shot
      .add(cSun.mul(caustic.mul(uSeaCaustic).mul(sea)))
      .add(cSun.mul(glint.mul(uGlint).mul(sea)));

    /* 文字を読ませるための落とし。元の .hero-bg::after と同じ配合。
       t は板の中の「上からの割合」なので、ヒーローの高さに自動で追随する。 */
    const t = float(1).sub(local.y);
    const band = mix(
      mix(float(0.36), float(0.30), smoothstep(0, 0.42, t)),
      float(0.50),
      smoothstep(0.42, 1.0, t)
    );
    const rd = vec2(local.x.sub(0.5).mul(uAspect), t.sub(0.45)).length();
    const scrim = band.add(float(0.22).mul(float(1).sub(smoothstep(0, 0.55, rd))));

    return vec4(mix(lit, cScrim, clamp(scrim, 0, 1)), uFade);
  })();

  const geometry = new PlaneGeometry(1, 1);
  const mesh = new Mesh(geometry, material);
  mesh.frustumCulled = false;
  mesh.renderOrder = 1;

  return {
    object: mesh,
    material,
    /**
     * ヒーローの矩形に板を重ね、object-fit:cover の切り取りをUVに焼き込む。
     * @param {DOMRect} r      ヒーローの位置（ビューポート基準・CSSピクセル）
     * @param {number} vw
     * @param {number} vh      ビューポートの大きさ
     * @param {number} iw
     * @param {number} ih      写真の元寸
     */
    setBox(r, vw, vh, iw, ih) {
      /* --- 板を矩形にぴったり重ねる（正射影の ±0.5 空間） --- */
      mesh.position.set(
        (r.left + r.width / 2) / vw - 0.5,
        0.5 - (r.top + r.height / 2) / vh,
        0.001
      );
      mesh.scale.set(r.width / vw, r.height / vh, 1);

      /* --- cover の切り取り。板のUV(0〜1)を写真UVに写す一次変換にする --- */
      const s = Math.max(r.width / iw, r.height / ih);
      const uSpan = r.width / (iw * s);
      const vSpan = r.height / (ih * s);
      uCrop.value.set((1 - uSpan) / 2, uSpan, (1 - vSpan) / 2, vSpan);   // center center

      /* --- 水面を引くための画面座標（下が0） --- */
      uBox.value.set(r.left / vw, 1 - (r.top + r.height) / vh, r.width / vw, r.height / vh);
    },
    /** 写真の中の水平線（上端からの割合を渡す） */
    setHorizon(fromTop) { uHorizon.value = 1 - fromTop; },
    params: {
      refraction: uRefract, seaSparkle: uSeaCaustic, glint: uGlint,
      horizon: uHorizon, ...tools.params,
    },
    dispose() { geometry.dispose(); material.dispose(); },
  };
}
