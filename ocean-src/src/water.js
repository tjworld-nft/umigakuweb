/**
 * 水そのもの。画面いっぱいの一枚板に、水中の見え方をまとめて描く。
 *
 *  1. 深さによる色。上は明るい水色、下へ行くほど濃紺に落ちる。
 *  2. 水面。上端でゆらぐ境目と、そこに散る光。
 *  3. 光条（god rays）。深い海では太陽が遠いので光の筋はほぼ平行に差す。
 *     角度の違う正弦を3本重ねて、幅の揃わない自然な筋にする。
 *  4. コースティクス。水面の起伏が集光してできる網目。正弦の干渉を
 *     高い冪で絞ると、あの細い光の網になる。
 *
 * この板は不透明。canvasが載らない端末に見えるCSSのグラデーションと
 * 同じ配色にしてあるので、フェードインしても色が飛ばない。
 * 文字が乗る帯（uGuard*）だけは光を弱める。読ませるのが先。
 */
import { Mesh, PlaneGeometry, MeshBasicNodeMaterial, NormalBlending } from 'three/webgpu';
import {
  Fn, float, vec2, vec3, uniform, uv, sin, abs, pow, mix, clamp, smoothstep,
} from 'three/tsl';
import { guardMask } from './tsl-utils.js';

export function createWater({ shared }) {
  const { uTime, uFade, uGuardCenter, uGuardHalf, colors } = shared;

  const uAspect = uniform(1.6);
  const uRayI = uniform(0.62);
  const uCausticI = uniform(0.22);
  const uSurfaceI = uniform(0.85);

  const cShallow = uniform(colors.waterShallow.clone());
  const cMid = uniform(colors.waterMid.clone());
  const cDeep = uniform(colors.waterDeep.clone());
  const cRay = uniform(colors.ray.clone());
  const cCaustic = uniform(colors.caustic.clone());
  const cSurface = uniform(colors.surface.clone());

  const material = new MeshBasicNodeMaterial({
    transparent: false,
    depthWrite: false,
    depthTest: false,
    blending: NormalBlending,
    fog: false,
  });

  /** 傾きの違う縞。f=本数, sp=傾き, ph=流れる速さ */
  const band = /*@__PURE__*/ Fn(([q, f, sp, ph]) => {
    return sin(q.x.add(q.y.mul(sp)).mul(f).add(uTime.mul(ph)));
  });

  material.colorNode = Fn(() => {
    const p = uv();
    const q = vec2(p.x.sub(0.5).mul(uAspect), p.y.sub(0.5)).toVar();
    const down = clamp(float(1).sub(p.y), 0, 1).toVar();   // 0=水面 1=深み

    /* --- 1. 深さの色 --- */
    const col = mix(cShallow, cMid, smoothstep(0.0, 0.46, down)).toVar();
    col.assign(mix(col, cDeep, smoothstep(0.42, 1.0, down)));

    /* --- 3. 光条 --- */
    const s = band(q, float(6.5), float(0.52), float(0.10))
      .mul(0.55)
      .add(band(q, float(12.0), float(0.64), float(-0.075)).mul(0.30))
      .add(band(q, float(21.0), float(0.46), float(0.05)).mul(0.15));
    const rays = pow(clamp(s.mul(0.5).add(0.5), 0, 1), 3.4)
      .mul(pow(smoothstep(-0.15, 0.95, p.y), 1.8))   // 上ほど強い
      .mul(smoothstep(1.15, 0.30, abs(q.x)));        // 左右の端は落とす

    /* --- 4. コースティクス --- */
    const c = vec2(q.x.mul(3.0), q.y.mul(2.2)).toVar();
    const c1 = sin(c.x.add(sin(c.y.mul(1.3).add(uTime.mul(0.31))).mul(1.6)).add(uTime.mul(0.42)));
    const c2 = sin(c.y.mul(1.17).sub(sin(c.x.mul(0.9).sub(uTime.mul(0.27))).mul(1.4)).sub(uTime.mul(0.35)));
    const caustic = pow(clamp(c1.add(c2).mul(0.25).add(0.5), 0, 1), 8.0)
      .mul(smoothstep(0.05, 0.85, p.y));

    /* --- 文字が乗る帯を守る --- */
    const guard = guardMask(p.y, uGuardCenter, uGuardHalf, float(0.22));

    col.addAssign(cRay.mul(rays.mul(uRayI)).mul(guard).mul(uFade));
    col.addAssign(cCaustic.mul(caustic.mul(uCausticI)).mul(guard).mul(uFade));

    /* --- 2. 水面。画面のいちばん上でゆらぐ境目 --- */
    const line = float(0.945)
      .add(sin(q.x.mul(2.9).add(uTime.mul(0.5))).mul(0.017))
      .add(sin(q.x.mul(6.7).sub(uTime.mul(0.38))).mul(0.009))
      .add(sin(q.x.mul(13.1).add(uTime.mul(0.26))).mul(0.004));
    const dy = p.y.sub(line);

    /* 水面の向こう側（明るい外界）と、こちら側から見上げたときの薄明かり */
    const above = smoothstep(-0.003, 0.010, dy);
    const glow = pow(smoothstep(-0.26, 0.0, dy), 2.6);
    const glitter = pow(clamp(
      sin(q.x.mul(31.0).add(uTime.mul(0.9))).mul(sin(q.x.mul(19.0).sub(uTime.mul(0.65)))), 0, 1
    ), 2.5);

    col.assign(mix(col, cSurface, above.mul(uSurfaceI).mul(uFade)));
    col.addAssign(cSurface.mul(glow.mul(0.30).add(glitter.mul(above).mul(0.5))).mul(uFade));

    return col;
  })();

  const mesh = new Mesh(new PlaneGeometry(1, 1), material);
  mesh.frustumCulled = false;
  mesh.renderOrder = -100;

  return {
    object: mesh,
    material,
    /** カメラの子にして、常に画面いっぱいを覆わせる */
    attachTo(camera, distance) {
      const h = 2 * Math.tan((camera.fov * Math.PI) / 360) * distance;
      mesh.position.set(0, 0, -distance);
      mesh.scale.set(h * camera.aspect, h, 1);
      uAspect.value = camera.aspect;
      if (mesh.parent !== camera) camera.add(mesh);
    },
    params: { rays: uRayI, caustics: uCausticI, surface: uSurfaceI },
    dispose() { mesh.geometry.dispose(); material.dispose(); },
  };
}
