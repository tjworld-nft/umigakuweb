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

/* 水面の傾きとコースティクス。どの層も同じ水を見ているので共通にする */
export function waterTools({ shared, ripple }) {
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

  /* 白い紙の上に落ちる影の濃さ。
     水面が平らなときは (1-caustic) がほぼ0なので、ここをいくら上げても
     何も起きない ── 効くのは波が立っている所だけ。0.075だった頃は、
     カーソルでかき混ぜている最中ですら合成後の差が最大4/255しかなく、
     「サイト全体に敷いた水」は事実上どこにも見えていなかった。
     静かなときは今までどおり透明なまま、波が通った所だけがそれと分かる濃さにする。 */
  const uAmbient = uniform(0.30);
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
export function createHeroPhoto({ shared, ripple, photo, seaMask }) {
  const { uAspect, uFade, colors } = shared;
  const tools = waterTools({ shared, ripple });

  const uRefract = uniform(0.022);      // 海がどれだけ歪むか
  const uSeaCaustic = uniform(0.95);    // 海面のきらめき（明るさの余白ぶんだけ効く）
  const uGlint = uniform(0.55);         // 光の反射（同上）
  const uSeaShade = uniform(0.13);      // 光が散った側の沈み
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

    /* どこが海か。起動時に写真から切り出したマスクを引くだけ（sea-mask.js）。
       以前はここで「水平線より下・青緑が強い」を毎フレーム判定していたが、
       それだと青緑のラッシュガードを着たお客さんまで水として波打っていた。 */
    const sea = texture(seaMask, tuv).r;

    /* 海の部分だけ屈折させる */
    const raw = texture(photo, tuv.add(g.mul(uRefract).mul(sea))).rgb;

    /* ここから先はsRGB空間で組み立てる。CSSのfilterやgradientはsRGBで効くので、
       リニア空間のまま混ぜると同じ数字でも眠い絵になる（実際に文字が読みにくくなった）。 */
    const srgb = pow(clamp(raw, 0, 1), float(1 / 2.2));

    /* 元のCSS（brightness 1.1 / saturate 1.08）と同じ見え方に揃える */
    const bright = srgb.mul(1.1);
    const lum = dot(bright, vec3(0.2126, 0.7152, 0.0722));
    const shot = mix(vec3(lum), bright, 1.08);

    /* 海面のきらめきと、傾いた面が空を返す反射。
       ここは足し算なので、そのまま足すと**もともと明るい浅瀬が白に張り付く**。
       この写真の売りは砂地と根が透けて見えていることなので、それを潰すと本末転倒になる
       （足しっぱなしだった頃は海の平均輝度が137→192、1.2%が純白に飽和していた）。
       そこで、足せる光の量を「白までの余白」で割り引く。浅瀬では静かに、
       光の届きにくい深い側ではしっかり効く ── 実際の海の見え方と同じ向きになる。 */
    const shotLum = dot(shot, vec3(0.2126, 0.7152, 0.0722));
    const head = clamp(float(1).sub(shotLum), 0, 1);
    const glint = pow(clamp(g.y.mul(2.2).add(0.5), 0, 1), 8.0);

    /* 光が集まった裏側では、その分だけ光が抜けている。ごくわずかに沈ませると、
       明るさの総量を上げずに水面の陰影だけが増える（＝白飛びさせずに波が見える）。 */
    const shade = float(1).sub(caustic).mul(uSeaShade).mul(sea);

    const lit = shot.mul(float(1).sub(shade))
      .add(cSun.mul(caustic.mul(uSeaCaustic).mul(head).mul(sea)))
      .add(cSun.mul(glint.mul(uGlint).mul(head).mul(sea)));

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

    /* 落としもsRGB空間で重ねてから、リニアに戻して出力する */
    const scrimSrgb = pow(clamp(cScrim, 0, 1), float(1 / 2.2));
    const out = mix(lit, scrimSrgb, clamp(scrim, 0, 1));
    return vec4(pow(clamp(out, 0, 1), float(2.2)), uFade);
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
    params: {
      refraction: uRefract, seaSparkle: uSeaCaustic, glint: uGlint,
      seaShade: uSeaShade, ...tools.params,
    },
    dispose() { geometry.dispose(); material.dispose(); },
  };
}
