/**
 * 魚群。
 *
 * WebGPUがある端末では、結合・整列・分離のボイドをコンピュートシェーダで
 * 毎フレーム回す。群れの形は指定していない ── 3つの規則の結果として現れる。
 *
 * 総当たりはO(N²)で端末が焼けるので、各個体は毎フレーム少数の仲間だけを見る。
 * 個体ごとに違う奇数ストライドで巡回し、フレームごとに起点をずらすので、
 * 時間で均せば全部を見たのとほぼ同じ群れになる。
 *
 * さらに個体を SCHOOLS 個の群れに分け、仲間探しは同じ群れの中だけで行う。
 * 1つの巨大な塊に潰れるのを防ぎ、いくつかの群れが画面を横切る絵になる。
 * 各群れはゆっくり動く「向かう先」を持っていて、それが回遊の軌跡になる。
 *
 * WebGL2しか無い端末にはストレージバッファもコンピュートも無いので、
 * 同じ見た目を時間の関数だけで作る（状態を持たない手続き的な遊泳）。
 */
import {
  InstancedMesh, BufferGeometry, BufferAttribute, StorageInstancedBufferAttribute,
  MeshBasicNodeMaterial, NormalBlending, DoubleSide, Matrix4,
} from 'three/webgpu';
import {
  Fn, float, int, vec3, storage, instanceIndex, uniform, positionLocal, cameraPosition,
  screenUV, sin, cos, abs, pow, mix, clamp, smoothstep, cross, dot, If, Loop,
} from 'three/tsl';
import { rand1, rand3, safeNorm, flowField, guardMask } from './tsl-utils.js';

const SCHOOLS = 6;

/* --- 魚の形。x=進行方向 / z=背びれ方向。実際の魚と同じく側扁させてある --- */
function fishGeometry() {
  const N = [0.50, 0, 0.00];   // 鼻先
  const D = [0.10, 0, 0.15];   // 背
  const B = [0.14, 0, -0.12];  // 腹
  const P = [-0.26, 0, 0.01];  // 尾柄
  const T = [-0.50, 0, 0.21];  // 尾びれ上
  const U = [-0.50, 0, -0.17]; // 尾びれ下
  const tri = [N, D, B, D, P, B, P, T, U];

  const pos = new Float32Array(tri.length * 3);
  tri.forEach((v, i) => pos.set(v, i * 3));

  const geo = new BufferGeometry();
  geo.setAttribute('position', new BufferAttribute(pos, 3));
  return geo;
}

export function createSchool({ count, neighbors, useCompute, shared, volume }) {
  const geometry = fishGeometry();
  const perSchool = Math.max(8, Math.floor(count / SCHOOLS));

  /* --- 群れの性格。数字は見ながら詰めたもの --- */
  const uCohesion = uniform(1.5);    // 仲間の中心へ寄る
  const uAlign = uniform(2.8);       // 仲間と向きを揃える
  const uSeparate = uniform(4.2);    // 近すぎる相手を避ける
  const uAnchor = uniform(2.4);      // 群れ全体が向かう先へ
  const uPerception = uniform(3.2);
  const uPersonal = uniform(1.05);
  const uSpeed = uniform(2.1);
  const uInertia = uniform(2.6);     // まっすぐ行きたい気持ち（大きいほど鈍い）
  const uTurn = uniform(4.0);        // 1秒で向きを変えられる割合
  const uFlow = uniform(0.4);
  const uBound = uniform(2.6);
  const uScale = uniform(0.42);
  const uFrame = uniform(0, 'int');

  const centre = volume.center;
  const half = volume.half;
  const { uTime, uDelta, uPointer, uPointerR, uPointerK, uFade,
          uGuardCenter, uGuardHalf } = shared;

  let posBuf = null, velBuf = null, computeInit = null, computeStep = null;
  let posAttr = null, velAttr = null;   // 中身を覗きたいとき用（window.__ocean.school.buffers）

  /** 群れ番号から、その群れが今向かっている場所を出す（全経路で共用）。
      群れごとに速さと軌道を変えて、同じ動きが並ばないようにする。 */
  const anchorOf = /*@__PURE__*/ Fn(([sf]) => {
    const spd = mix(0.055, 0.125, rand1(sf.add(11.1)));
    const a = uTime.mul(spd).add(sf.mul(1.7));
    return centre.add(vec3(
      sin(a).mul(half.x.mul(mix(0.55, 0.85, rand1(sf.add(2.2))))),
      sin(a.mul(0.63).add(sf.mul(0.9))).mul(half.y.mul(0.58)),
      cos(a.mul(0.81).add(sf.mul(2.1))).mul(half.z.mul(0.72))
    ));
  });

  /** 群れの「密度の個性」。ぎゅっと固まる群れと、ゆるい群れができる */
  const spreadOf = /*@__PURE__*/ Fn(([sf]) => mix(0.72, 1.55, rand1(sf.add(5.5))));
  /** 群れごとの体格差。奥の群れが小さく見えるのとは別の、種の違いに見せる */
  const sizeOf = /*@__PURE__*/ Fn(([sf]) => mix(0.78, 1.3, rand1(sf.add(8.8))));

  /* ------------------------------------------------------------------
     位置と速度をどこから取るか。ここだけが2つの経路で違う。
     ------------------------------------------------------------------ */
  let readPos, readVel;

  if (useCompute) {
    posAttr = new StorageInstancedBufferAttribute(count, 3);
    velAttr = new StorageInstancedBufferAttribute(count, 3);
    posBuf = storage(posAttr, 'vec3', count);
    velBuf = storage(velAttr, 'vec3', count);

    /* --- 初期配置：群れごとに固まって泳ぎ出す --- */
    computeInit = Fn(() => {
      const r = rand3(float(instanceIndex)).toVar();
      const q = rand3(float(instanceIndex).add(101.3)).toVar();
      const sf = float(int(instanceIndex).mod(int(SCHOOLS)));
      posBuf.element(instanceIndex).assign(
        anchorOf(sf).add(r.sub(0.5).mul(vec3(3.4, 1.8, 3.0)))
      );
      velBuf.element(instanceIndex).assign(
        safeNorm(q.sub(0.5).mul(vec3(2, 0.4, 1.2))).mul(uSpeed)
      );
    })().compute(count);

    /* --- 毎フレームのボイド --- */
    computeStep = Fn(() => {
      const posRef = posBuf.element(instanceIndex);
      const velRef = velBuf.element(instanceIndex);
      const p = vec3(posRef).toVar();
      const v = vec3(velRef).toVar();

      const sumPos = vec3(0).toVar();
      const sumVel = vec3(0).toVar();
      const push = vec3(0).toVar();
      const n = float(0).toVar();

      const self = int(instanceIndex);
      const schools = int(SCHOOLS);
      const per = int(perSchool);
      const school = self.mod(schools);
      const local = self.div(schools);          // 群れの中での番号
      const stride = local.mul(2).add(1);        // 奇数。perが2のべき乗なので全体を巡回する

      const per2 = uPerception.mul(uPerception);
      const spread = spreadOf(float(school));
      const personal = uPersonal.mul(spread);
      const near2 = personal.mul(personal);

      Loop(neighbors, ({ i }) => {
        /* 同じ群れの仲間だけを見る */
        const jl = local.add(i.add(uFrame).mul(stride)).mod(per);
        const j = jl.mul(schools).add(school);

        const op = vec3(posBuf.element(j));
        const d = op.sub(p);
        const d2 = dot(d, d);

        If(d2.lessThan(per2).and(d2.greaterThan(1e-4)), () => {
          n.addAssign(1);
          sumPos.addAssign(op);
          sumVel.addAssign(vec3(velBuf.element(j)));
          If(d2.lessThan(near2), () => {
            push.subAssign(d.div(d2.max(0.04)));   // 近いほど強く押しのける
          });
        });
      });

      const acc = vec3(0).toVar();
      If(n.greaterThan(0.5), () => {
        acc.addAssign(safeNorm(sumPos.div(n).sub(p)).mul(uCohesion));
        acc.addAssign(safeNorm(sumVel).mul(uAlign));
        acc.addAssign(safeNorm(push).mul(uSeparate));
      });

      /* 群れ全体の行き先 */
      acc.addAssign(safeNorm(anchorOf(float(school)).sub(p)).mul(uAnchor));

      /* 潮の流れ */
      acc.addAssign(flowField(p, uTime).mul(uFlow));

      /* 箱から出かけたら中へ戻す（壁ではなく“戻る気持ち”として効かせる） */
      const rel = p.sub(centre);
      const over = abs(rel).sub(half).max(vec3(0));
      acc.subAssign(clamp(rel.mul(1e6), vec3(-1), vec3(1)).mul(over).mul(uBound));

      /* ポインタ＝天敵。近づくと割れて、通り過ぎるとまた閉じる */
      const away = p.sub(uPointer);
      const pd = away.length();
      acc.addAssign(safeNorm(away).mul(smoothstep(uPointerR, uPointerR.mul(0.15), pd)).mul(uPointerK));

      /* 速さは一定に保ち、向きだけを変える。
         「今の向き × 慣性 ＋ 操舵の合力」を行きたい向きとし、そこへ
         1フレームで進める割合に上限をつける（＝魚らしい滑らかな旋回）。
         加速度をそのまま速度に足すと、速さに対して力が小さすぎて
         ほとんど曲がらない。向きの合成として扱うのが正しい。 */
      const dir = safeNorm(v);
      const want = safeNorm(dir.mul(uInertia).add(acc));
      const nv = safeNorm(mix(dir, want, clamp(uTurn.mul(uDelta), 0, 1))).mul(uSpeed);

      velRef.assign(nv);
      posRef.assign(clamp(
        p.add(nv.mul(uDelta)),
        centre.sub(half.mul(1.25)),
        centre.add(half.mul(1.25))
      ));
    })().compute(count);

    /* 描画側は読み取り専用のビューを別に作る。同じノードに toReadOnly() を
       かけるとコンピュート側まで read-only になって書き込めなくなる。 */
    const ro = storage(posAttr, 'vec3', count).toReadOnly();
    const rv = storage(velAttr, 'vec3', count).toReadOnly();
    readPos = () => vec3(ro.element(instanceIndex));
    readVel = () => vec3(rv.element(instanceIndex));

  } else {
    /* --- WebGL2用：状態を持たない遊泳。群れの行き先のまわりを回り続ける --- */
    const swim = /*@__PURE__*/ Fn(([which]) => {
      const id = float(instanceIndex);
      const r = rand3(id).toVar();
      const q = rand3(id.add(77.7)).toVar();
      const sf = float(int(instanceIndex).mod(int(SCHOOLS)));

      const w = mix(0.55, 0.95, r.z);
      const ang = uTime.mul(w).add(r.x.mul(6.2832));
      const rad = mix(0.9, 2.6, q.x);
      const ry = mix(0.25, 0.9, q.y);
      const ph = r.y.mul(6.2832);

      const off = vec3(
        sin(ang).mul(rad),
        sin(ang.mul(0.7).add(ph)).mul(ry),
        cos(ang).mul(rad.mul(0.85))
      );
      /* 速度は上式の時間微分。向きが位置と必ず整合する */
      const d = vec3(
        cos(ang).mul(rad).mul(w),
        cos(ang.mul(0.7).add(ph)).mul(ry).mul(w.mul(0.7)),
        sin(ang).mul(rad.mul(0.85)).mul(w).negate()
      );
      return mix(anchorOf(sf).add(off), safeNorm(d).mul(uSpeed), float(which));
    });

    readPos = () => swim(float(0));
    readVel = () => swim(float(1));
  }

  /* ------------------------------------------------------------------
     見た目。ここから先は2つの経路で完全に同じコード。
     ------------------------------------------------------------------ */
  const material = new MeshBasicNodeMaterial({
    transparent: true,
    depthWrite: false,
    depthTest: false,
    blending: NormalBlending,
    side: DoubleSide,
    fog: false,
  });

  const bodyCol = uniform(shared.colors.fishLit.clone());
  const deepCol = uniform(shared.colors.fishDeep.clone());

  material.positionNode = Fn(() => {
    const p = readPos().toVar();
    const fwd = safeNorm(readVel()).toVar();
    const right = safeNorm(cross(vec3(0, 1, 0), fwd)).toVar();
    const up = cross(fwd, right).toVar();

    const scale = uScale.mul(sizeOf(float(int(instanceIndex).mod(int(SCHOOLS)))));
    const lx = positionLocal.x;
    const lz = positionLocal.z;

    /* 尾を振る。頭は動かず、尾に向かうほど大きく振れる */
    const phase = rand1(float(instanceIndex)).mul(6.2832);
    const wag = sin(uTime.mul(7.5).add(phase))
      .mul(smoothstep(0.18, -0.5, lx))
      .mul(0.26);

    return p
      .add(fwd.mul(lx.mul(scale)))
      .add(up.mul(lz.mul(scale)))
      .add(right.mul(wag.mul(scale)));
  })();

  /* 横腹がこちらを向いた瞬間だけ銀色に光る（イワシの群れのきらめき） */
  const flash = /*@__PURE__*/ Fn(() => {
    const p = readPos().toVar();
    const fwd = safeNorm(readVel()).toVar();
    const right = safeNorm(cross(vec3(0, 1, 0), fwd)).toVar();
    const view = safeNorm(p.sub(cameraPosition));
    return pow(abs(dot(view, right)), 4.0);
  });

  const distToCam = /*@__PURE__*/ Fn(() => readPos().sub(cameraPosition).length());

  material.colorNode = Fn(() => {
    const near = smoothstep(volume.far, volume.near, distToCam());
    return mix(deepCol, bodyCol, flash().mul(0.85).add(near.mul(0.15)).min(1));
  })();

  material.opacityNode = Fn(() => {
    const d = distToCam();
    /* 手前で巨大に映るのと、奥で点になるのを両方フェードで消す */
    const fadeNear = smoothstep(volume.near.mul(0.5), volume.near, d);
    const fadeFar = smoothstep(volume.far, volume.far.mul(0.68), d);
    /* 文字の乗る帯では群れも薄くする。水が濁って見えるだけで、絵は壊れない */
    const guard = guardMask(screenUV.y, uGuardCenter, uGuardHalf, float(0.34));
    return uFade.mul(fadeNear).mul(fadeFar).mul(guard).mul(0.9);
  })();

  const mesh = new InstancedMesh(geometry, material, count);
  mesh.frustumCulled = false;
  mesh.renderOrder = 20;

  /* positionNodeで全部決めているのでインスタンス行列は単位行列でよい */
  const m = new Matrix4();
  for (let i = 0; i < count; i++) mesh.setMatrixAt(i, m);
  mesh.instanceMatrix.needsUpdate = true;

  return {
    object: mesh,
    material,
    computeInit,
    computeStep,
    maxCount: count,
    schools: SCHOOLS,
    buffers: { pos: posAttr, vel: velAttr },
    /* コンソールから群れの性格をいじれるように。書き換えれば次のフレームで効く */
    params: {
      cohesion: uCohesion, alignment: uAlign, separation: uSeparate, anchor: uAnchor,
      perception: uPerception, personalSpace: uPersonal,
      speed: uSpeed, inertia: uInertia, turnRate: uTurn, flow: uFlow, size: uScale,
      fear: shared.uPointerK, fearRadius: shared.uPointerR,
    },
    /* 数を減らすときは群れの数で割り切れる位置で切る（群れが欠けないように） */
    setCount(n) {
      const clamped = Math.max(SCHOOLS * 8, Math.min(count, n | 0));
      mesh.count = Math.floor(clamped / SCHOOLS) * SCHOOLS;
    },
    tickFrame(f) { uFrame.value = (f * 7) % perSchool; },
    dispose() { geometry.dispose(); material.dispose(); mesh.dispose(); },
  };
}
