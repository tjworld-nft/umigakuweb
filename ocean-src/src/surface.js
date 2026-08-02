/**
 * 画面いっぱいの一枚。ページ全体をこれ一枚で覆って、場所によって役割を変える。
 *
 *  ヒーローの中  … 写真をそのまま出す。ただし**海の部分だけ**を水面の傾きで
 *                  屈折させ、光の反射とコースティクスを乗せる。空・人・岩は
 *                  1ピクセルも動かさない（写真の色と水平線から海を見分けている）。
 *  それより下    … 同じ水面を通った光の影が、白い背景の上をゆっくり流れる。
 *                  白基調を壊さないよう、淡い水色をごく薄く重ねるだけ。
 *
 * コースティクスは水面の傾きの発散から出している。傾きの方向へ少し進んで
 * もう一度傾きを測り、光線が集まっているか散っているかを見る、という手。
 */
import { Mesh, PlaneGeometry, MeshBasicNodeMaterial, NormalBlending, Vector4 } from 'three/webgpu';
import {
  Fn, float, vec2, vec3, vec4, uniform, uv, texture, dot, pow, mix, clamp, smoothstep,
} from 'three/tsl';

export function createSurface({ shared, ripple, photo }) {
  const { uTime, uAspect, uScroll, uFade, colors } = shared;

  /* --- ヒーローの居場所（画面座標・下端0 上端1）。JS側が毎フレーム入れる --- */
  const uHeroTop = uniform(1.4);
  const uHeroBottom = uniform(0.0);
  const uFeather = uniform(0.002);
  /* 画面座標 → 写真のUV への一次変換。object-fit:cover の計算はJS側で済ませる */
  const uPhotoMap = uniform(new Vector4(1, 0, 1, 0));
  const uHorizon = uniform(0.473);      // 写真の中の水平線（v座標・下端が0）。起動時に実測して上書きする

  /* --- 効きの強さ。window.__ocean.surface.params から触れる --- */
  const uRefract = uniform(0.011);      // 海がどれだけ歪むか
  const uSeaCaustic = uniform(0.26);    // 海面のきらめき
  const uGlint = uniform(0.24);         // 光の反射
  const uAmbient = uniform(0.115);      // ページ側に落ちる水の影
  const uCausticK = uniform(2.6);
  const uCausticP = uniform(4.0);
  const uCausticStep = uniform(0.012);

  const cSun = uniform(colors.sun.clone());
  const cScrim = uniform(colors.scrim.clone());
  const cWash = uniform(colors.wash.clone());

  const material = new MeshBasicNodeMaterial({
    transparent: true,
    depthWrite: false,
    depthTest: false,
    blending: NormalBlending,
    fog: false,
  });

  /* 水面の傾き。うねりはゆっくり流して、止まって見えないようにする */
  const slopeAt = /*@__PURE__*/ Fn(([p]) => {
    const drift = vec2(p.x.mul(uAspect).add(uTime.mul(0.008)), p.y.sub(uTime.mul(0.011)));
    return ripple.gradAt(p, drift);
  });

  /* 光線が集まっているか散っているか＝コースティクス */
  const causticAt = /*@__PURE__*/ Fn(([p, g]) => {
    const spread = slopeAt(p.add(g.mul(uCausticStep))).sub(g).length();
    return pow(clamp(float(1).sub(spread.mul(uCausticK)), 0, 1), uCausticP);
  });

  /* 色と不透明度は同じ計算から出るので、vec4のまま outputNode に渡す。
     ここでは変数（toVar）も代入も使わず、式だけで組む。TSLは2回以上参照される
     ノードを自動で一時変数にまとめるので、書き味を変えても計算量は増えない。 */
  const shade = Fn(() => {
    /* 画面座標（左下が0,0）。正射影カメラに 1×1 の板を正対させてあるので、
       このUVはキャンバスと厳密に1:1で対応する。 */
    const p = uv();
    const g = slopeAt(p);
    const caustic = causticAt(p, g);

    /* ---------- ヒーローの中かどうか ---------- */
    /* smoothstep は edge0 < edge1 でないと結果が未定義（WGSL）。
       上端の判定は「超えたら0」なので、順序を保ったまま 1 から引く。 */
    const inHero = smoothstep(uHeroBottom.sub(uFeather), uHeroBottom.add(uFeather), p.y)
      .mul(float(1).sub(smoothstep(uHeroTop.sub(uFeather), uHeroTop.add(uFeather), p.y)));

    /* ---------- 写真 ---------- */
    const tuv = vec2(
      uPhotoMap.x.mul(p.x).add(uPhotoMap.y),
      uPhotoMap.z.mul(p.y).add(uPhotoMap.w)
    );

    /* 海だけを見分ける。水平線より下で、かつ青緑が強いところ。
       閾値は実際の写真の画素を測って決めた（リニア空間での値）:
         空 0.28〜0.39 ／ 海 0.26〜0.35 ／ 人 0.00〜0.16 ／ 岩と木 0.13
       空は水平線で、人と岩はこの青緑さで外れる。
       水平線より下＝vが小さい側が海。 */
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

    /* 海面のきらめきと、傾いた面が空を返す反射。
       傾きが大きいところで一気に飽和しないよう、ゲインは控えめにする。 */
    const glint = pow(clamp(g.y.mul(2.2).add(0.5), 0, 1), 8.0);
    const lit = shot
      .add(cSun.mul(caustic.mul(uSeaCaustic).mul(sea)))
      .add(cSun.mul(glint.mul(uGlint).mul(sea)));

    /* 文字を読ませるための落とし。元の .hero-bg::after と同じ配合 */
    const t = uHeroTop.sub(p.y).div(uHeroTop.sub(uHeroBottom).max(1e-3)).clamp(0, 1);
    const band = mix(
      mix(float(0.36), float(0.30), smoothstep(0, 0.42, t)),
      float(0.50),
      smoothstep(0.42, 1.0, t)
    );
    const rd = vec2(p.x.sub(0.5).mul(uAspect), t.sub(0.45)).length();
    const scrim = band.add(float(0.22).mul(float(1).sub(smoothstep(0, 0.55, rd))));
    const heroCol = mix(lit, cScrim, clamp(scrim, 0, 1));

    /* ---------- ヒーローの外：白い紙の上に落ちる水の影 ---------- */
    const washAlpha = float(1).sub(caustic)
      .mul(uAmbient)
      .mul(mix(0.85, 1.35, uScroll));    // 下へ行くほどわずかに濃く＝沈んでいく

    return vec4(
      mix(cWash, heroCol, inHero),
      mix(washAlpha, float(1), inHero).mul(uFade)
    );
  });

  material.outputNode = shade();

  /* 正射影カメラ（左右±0.5・上下±0.5）にぴったり収まる板 */
  const mesh = new Mesh(new PlaneGeometry(1, 1), material);
  mesh.frustumCulled = false;

  return {
    object: mesh,
    material,
    /**
     * ヒーローが画面のどこにいるか、写真をどう貼るかを渡す。
     * top/bottom は画面座標（下端0・上端1）、map は screen→texture の一次変換。
     */
    setHero(m) {
      uHeroTop.value = m.top;
      uHeroBottom.value = m.bottom;
      uFeather.value = m.feather;
      uPhotoMap.value.set(m.map[0], m.map[1], m.map[2], m.map[3]);
    },
    setHorizon(v) { uHorizon.value = v; },
    params: {
      refraction: uRefract, seaSparkle: uSeaCaustic, glint: uGlint,
      pageLight: uAmbient, causticSharpness: uCausticP, horizon: uHorizon,
    },
    /* 貼り位置が合っているか外から見られるように（開発用） */
    debug: { photoMap: uPhotoMap, heroTop: uHeroTop, heroBottom: uHeroBottom },
    dispose() { mesh.geometry.dispose(); material.dispose(); },
  };
}
