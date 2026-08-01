/**
 * 漂うもの ── マリンスノー（微粒子）と泡。
 *
 * どちらも状態を持たない。位置を時間の関数として書いているので、
 * WebGPUでもWebGL2でも同じ式が動き、バッファもコンピュートも要らない。
 * fract() で高さを巻き戻し、継ぎ目は透明度で消している。
 */
import { InstancedMesh, PlaneGeometry, SpriteNodeMaterial, AdditiveBlending, Matrix4 } from 'three/webgpu';
import {
  Fn, float, vec3, uniform, uv, instanceIndex, cameraPosition,
  sin, fract, pow, mix, smoothstep, length,
} from 'three/tsl';
import { rand3, flowField } from './tsl-utils.js';

function identityMatrices(mesh, count) {
  const m = new Matrix4();
  for (let i = 0; i < count; i++) mesh.setMatrixAt(i, m);
  mesh.instanceMatrix.needsUpdate = true;
}

/* ------------------------------------------------------------------------ */
/* マリンスノー：ゆっくり沈みながら潮に流される微粒子                          */
/* ------------------------------------------------------------------------ */
export function createMotes({ count, shared, volume }) {
  const { uTime, uFade, colors } = shared;
  const centre = volume.center;
  const half = volume.half;
  const uSize = uniform(1.0);

  const material = new SpriteNodeMaterial({
    transparent: true,
    depthWrite: false,
    depthTest: false,
    blending: AdditiveBlending,
    fog: false,
  });

  /* 個体ごとの寿命位置（0=上, 1=下）。これを高さと透明度の両方に使う */
  const life = Fn(() => {
    const q = rand3(float(instanceIndex).add(13.1));
    const r = rand3(float(instanceIndex));
    return fract(r.y.sub(uTime.mul(mix(0.006, 0.021, q.x))));
  });

  const place = Fn(() => {
    const r = rand3(float(instanceIndex)).toVar();
    const base = vec3(
      centre.x.add(r.x.sub(0.5).mul(half.x.mul(2.1))),
      centre.y.add(life().sub(0.5).mul(half.y.mul(2.2))),
      centre.z.add(r.z.sub(0.5).mul(half.z.mul(2)))
    ).toVar();
    return base.add(flowField(base, uTime.mul(0.5)).mul(0.6));
  });

  material.positionNode = place();
  material.scaleNode = Fn(() => {
    const q = rand3(float(instanceIndex).add(13.1));
    return mix(0.014, 0.05, q.y).mul(uSize);
  })();

  material.colorNode = uniform(colors.mote.clone());
  material.opacityNode = Fn(() => {
    const t = life();
    const seam = smoothstep(0.0, 0.09, t).mul(smoothstep(1.0, 0.91, t));  // 継ぎ目を隠す
    const d = place().sub(cameraPosition).length();
    const depth = smoothstep(volume.far, volume.near, d);
    const dot = pow(smoothstep(1.0, 0.0, length(uv().sub(0.5)).mul(2)), 2.0);
    return uFade.mul(seam).mul(mix(0.18, 0.85, depth)).mul(dot);
  })();

  const mesh = new InstancedMesh(new PlaneGeometry(1, 1), material, count);
  mesh.frustumCulled = false;
  mesh.renderOrder = 10;
  identityMatrices(mesh, count);

  return {
    object: mesh,
    material,
    maxCount: count,
    setCount(n) { mesh.count = Math.max(32, Math.min(count, n | 0)); },
    dispose() { mesh.geometry.dispose(); material.dispose(); mesh.dispose(); },
  };
}

/* ------------------------------------------------------------------------ */
/* 泡：ゆらぎながら立ちのぼる。縁だけ明るい輪として描く                        */
/* ------------------------------------------------------------------------ */
export function createBubbles({ count, shared, volume }) {
  const { uTime, uFade, colors } = shared;
  const centre = volume.center;
  const half = volume.half;

  const material = new SpriteNodeMaterial({
    transparent: true,
    depthWrite: false,
    depthTest: false,
    blending: AdditiveBlending,
    fog: false,
  });

  const life = Fn(() => {
    const q = rand3(float(instanceIndex).add(41.7));
    const r = rand3(float(instanceIndex));
    return fract(r.y.add(uTime.mul(mix(0.045, 0.10, q.x))));
  });

  const place = Fn(() => {
    const r = rand3(float(instanceIndex)).toVar();
    const q = rand3(float(instanceIndex).add(41.7)).toVar();
    const t = life();
    /* 上がるほど左右の揺れが大きくなる。実際の泡もそう見える */
    const sway = sin(uTime.mul(mix(0.9, 1.9, q.y)).add(r.x.mul(6.2832)))
      .mul(mix(0.15, 0.5, q.z))
      .mul(t.add(0.35));
    return vec3(
      centre.x.add(r.x.sub(0.5).mul(half.x.mul(1.9))).add(sway),
      centre.y.add(t.sub(0.5).mul(half.y.mul(2.2))),
      centre.z.add(r.z.sub(0.5).mul(half.z.mul(1.4)))
    );
  });

  material.positionNode = place();
  material.scaleNode = Fn(() => {
    const q = rand3(float(instanceIndex).add(41.7));
    return mix(0.09, 0.28, q.y).mul(life().mul(0.35).add(0.8));  // 上がるほど少し膨らむ
  })();

  material.colorNode = uniform(colors.bubble.clone());
  material.opacityNode = Fn(() => {
    const d = length(uv().sub(0.5)).mul(2);
    /* 泡は中身より縁が光る。細い輪＋ごく薄い中身で「気泡」に見せる */
    const rim = smoothstep(1.0, 0.88, d).mul(smoothstep(0.62, 0.9, d));
    const fill = smoothstep(1.0, 0.1, d).mul(0.05);
    const t = life();
    const seam = smoothstep(0.0, 0.12, t).mul(smoothstep(1.0, 0.86, t));
    return uFade.mul(seam).mul(rim.mul(0.72).add(fill));
  })();

  const mesh = new InstancedMesh(new PlaneGeometry(1, 1), material, count);
  mesh.frustumCulled = false;
  mesh.renderOrder = 30;
  identityMatrices(mesh, count);

  return {
    object: mesh,
    material,
    maxCount: count,
    setCount(n) { mesh.count = Math.max(4, Math.min(count, n | 0)); },
    dispose() { mesh.geometry.dispose(); material.dispose(); mesh.dispose(); },
  };
}
