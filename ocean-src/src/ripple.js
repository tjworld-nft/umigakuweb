/**
 * 水面。サイトの上に一枚だけ張ってある水の膜。
 *
 * WebGPUがある端末では、これは絵ではなく**計算**で動いている。
 * グリッド上で2次元の波動方程式を解いていて、
 *
 *     h(t+1) = 2h(t) - h(t-1) + c²·∇²h(t)      （減衰つき）
 *
 * カーソルが水を押すと、そこから波が立ち、伝わり、壁で跳ね返り、
 * 干渉して、やがて収まる。波の形はどこにも書いていない ── 式の結果として現れる。
 *
 * 1フレームに2ステップ進める。前後2枚のバッファを入れ替えながら使うので、
 * 2回進めると必ず最新が同じバッファに戻る（描画側は常に同じ所を読めばよい）。
 * そのあと勾配（水面の傾き）だけを別バッファに焼いておく。屈折とコースティクスは
 * 傾きしか要らないので、描画のたびに近傍を舐め直さずに済む。
 *
 * WebGPUが無い端末にはコンピュートもストレージバッファも無いので、
 * 同じ「傾き」を時間の関数として解析的に出す（うねり＋カーソルのふくらみ）。
 * 波の伝播はしないが、見た目の質感は揃う。
 */
import { StorageInstancedBufferAttribute, Vector2 } from 'three/webgpu';
import {
  Fn, float, int, vec2, storage, instanceIndex, uniform,
  sin, cos, exp, floor, clamp, mix, min, max,
} from 'three/tsl';

/* うねりの成分。振幅・向き・速さ。両方の経路でこれを共通に足すので、
   WebGPUでもWebGL2でも水の"きめ"が揃う。 */
const SWELL = [
  { a: 0.55, d: [5.9, 3.1], w: 0.62 },
  { a: 0.34, d: [-4.3, 6.8], w: -0.47 },
  { a: 0.22, d: [9.7, -2.4], w: 0.81 },
  { a: 0.13, d: [-12.1, -8.3], w: 0.55 },
];

/** うねりの傾き（解析微分なので厳密） */
const swellGrad = /*@__PURE__*/ Fn(([uv, t]) => {
  let g = null;
  for (const s of SWELL) {
    const c = cos(uv.x.mul(s.d[0]).add(uv.y.mul(s.d[1])).add(t.mul(s.w))).mul(s.a);
    const term = vec2(c.mul(s.d[0]), c.mul(s.d[1]));
    g = g === null ? term : g.add(term);
  }
  return g;
});

export function createRipple({ shared, useCompute, grid }) {
  const N = grid;
  const CELLS = N * N;

  const uSpeed = uniform(0.28);      // c²。0.5を超えると発散する
  const uDamp = uniform(0.9965);
  const uPokeAmp = uniform(0);       // カーソルの速さに応じてJS側が入れる
  const uPokeSharp = uniform(900);   // 押す範囲の狭さ
  /* 雫。カーソルと違って一瞬だけ強く落とす。ここから輪が広がっていく。
     スマホにはカーソルが無いので、水が生きて見えるのはほぼこれのおかげになる。 */
  const uDrop = uniform(new Vector2(0.5, 0.5));
  const uDropAmp = uniform(0);
  const uDropSharp = uniform(5200);  // カーソルよりずっと狭い＝点で落ちる
  const uDropAge = uniform(0);       // 落ちてからの秒数（コンピュート無しの経路だけが使う）
  const uSwell = uniform(0.085);    // うねりの効き
  const uRippleK = uniform(3.4);    // 計算した波の効き
  const { uTime, uPointer, uAspect } = shared;

  let attrs = null;
  let computeInit = null;
  let computeSteps = [];
  let gradAt;

  if (useCompute) {
    /* h(t) と h(t-1)。役割は毎ステップ入れ替わる */
    const aAttr = new StorageInstancedBufferAttribute(CELLS, 1);
    const bAttr = new StorageInstancedBufferAttribute(CELLS, 1);
    const gAttr = new StorageInstancedBufferAttribute(CELLS, 2);
    attrs = { a: aAttr, b: bAttr, g: gAttr };

    const A = storage(aAttr, 'float', CELLS);
    const B = storage(bAttr, 'float', CELLS);
    const G = storage(gAttr, 'vec2', CELLS);

    computeInit = Fn(() => {
      A.element(instanceIndex).assign(0);
      B.element(instanceIndex).assign(0);
      G.element(instanceIndex).assign(vec2(0));
    })().compute(CELLS);

    /** cur を読み、prev を読み、次の高さを prev に上書きする（＝入れ替え） */
    const makeStep = (cur, prev) => Fn(() => {
      const i = int(instanceIndex);
      const nI = int(N);
      const x = i.mod(nI);
      const y = i.div(nI);
      const xm = max(x.sub(1), int(0));
      const xp = min(x.add(1), int(N - 1));
      const ym = max(y.sub(1), int(0));
      const yp = min(y.add(1), int(N - 1));

      const h = cur.element(i);
      /* 端は値を折り返している＝壁。波はここで跳ね返る */
      const lap = cur.element(y.mul(nI).add(xm))
        .add(cur.element(y.mul(nI).add(xp)))
        .add(cur.element(ym.mul(nI).add(x)))
        .add(cur.element(yp.mul(nI).add(x)))
        .sub(h.mul(4));

      /* カーソルが水を押す。画面の縦横比を補正して真円に押す */
      const uv = vec2(float(x), float(y)).div(float(N - 1));
      const d = uv.sub(uPointer).mul(vec2(uAspect, 1));
      const push = exp(d.dot(d).mul(uPokeSharp).negate()).mul(uPokeAmp);

      /* 雫が落ちる。1〜2ステップだけ入って、あとは波動方程式が輪を広げてくれる */
      const dd = uv.sub(uDrop).mul(vec2(uAspect, 1));
      const drop = exp(dd.dot(dd).mul(uDropSharp).negate()).mul(uDropAmp);

      prev.element(i).assign(
        h.mul(2).sub(prev.element(i)).add(lap.mul(uSpeed)).mul(uDamp).add(push).add(drop)
      );
    })().compute(CELLS);

    /* 2回進めると最新は必ず A に戻る */
    const stepAB = makeStep(A, B);
    const stepBA = makeStep(B, A);

    /* 最新の高さ(A)から傾きを焼く。描画側はこれを1回読むだけで済む */
    const bake = Fn(() => {
      const i = int(instanceIndex);
      const nI = int(N);
      const x = i.mod(nI);
      const y = i.div(nI);
      const xm = max(x.sub(1), int(0));
      const xp = min(x.add(1), int(N - 1));
      const ym = max(y.sub(1), int(0));
      const yp = min(y.add(1), int(N - 1));
      G.element(i).assign(vec2(
        A.element(y.mul(nI).add(xp)).sub(A.element(y.mul(nI).add(xm))).mul(0.5),
        A.element(yp.mul(nI).add(x)).sub(A.element(ym.mul(nI).add(x))).mul(0.5)
      ));
    })().compute(CELLS);

    computeSteps = [stepAB, stepBA, bake];

    /* 描画側は読み取り専用の別ビューから読む（同じノードに toReadOnly() を
       かけるとコンピュート側まで書き込み不可になる） */
    const GR = storage(gAttr, 'vec2', CELLS).toReadOnly();

    /** グリッドを双一次補間して、任意の位置の傾きを返す */
    const sampleGrad = /*@__PURE__*/ Fn(([uv]) => {
      const g = clamp(uv, 0, 1).mul(float(N - 1));
      const base = floor(g);
      const f = g.sub(base);
      const x0 = int(base.x);
      const y0 = int(base.y);
      const x1 = min(x0.add(1), int(N - 1));
      const y1 = min(y0.add(1), int(N - 1));
      const nI = int(N);
      const p00 = vec2(GR.element(y0.mul(nI).add(x0)));
      const p10 = vec2(GR.element(y0.mul(nI).add(x1)));
      const p01 = vec2(GR.element(y1.mul(nI).add(x0)));
      const p11 = vec2(GR.element(y1.mul(nI).add(x1)));
      return mix(mix(p00, p10, f.x), mix(p01, p11, f.x), f.y);
    });

    gradAt = (uv, detailUv) =>
      sampleGrad(uv).mul(uRippleK).add(swellGrad(detailUv, uTime).mul(uSwell));

  } else {
    /* --- WebGL2用 --- 波は伝わらないが、うねりとカーソルのふくらみは効く */
    const bulgeGrad = /*@__PURE__*/ Fn(([uv]) => {
      const d = uv.sub(uPointer).mul(vec2(uAspect, 1));
      const q = d.dot(d);
      /* h = A·exp(-S·q) の勾配 = h·(-2S)·d */
      return d.mul(exp(q.mul(uPokeSharp).negate()).mul(uPokeAmp).mul(uPokeSharp).mul(-2));
    });

    /* 雫の輪。こちらには波動方程式が無いので、広がって薄れる輪を時間の式で描く。
       伝わり方は本物ではないが、「雫が落ちて輪が広がった」ことは同じように見える。 */
    const dropRingGrad = /*@__PURE__*/ Fn(([uv]) => {
      const d = uv.sub(uDrop).mul(vec2(uAspect, 1));
      const r = max(d.length(), float(1e-4));
      const front = r.sub(uDropAge.mul(0.42));          // 輪の半径は時間に比例して広がる
      const env = exp(front.mul(front).mul(-900))
        .mul(exp(uDropAge.mul(-1.7)))                   // 時間とともに薄れる
        .mul(uDropAmp);
      /* 輪の前後で符号が変わるので、山と谷ができる */
      return d.div(r).mul(env.mul(front).mul(-1800));
    });

    gradAt = (uv, detailUv) => bulgeGrad(uv).mul(uRippleK)
      .add(dropRingGrad(uv).mul(uRippleK))
      .add(swellGrad(detailUv, uTime).mul(uSwell));
  }

  /* 雫を何フレーム入れ続けるか。コンピュート経路は1フレーム入れれば、
     あとは波動方程式が勝手に輪を広げてくれるので手を離す。 */
  let dropFrames = 0;

  return {
    computeInit,
    computeSteps,
    gradAt,
    attrs,
    params: {
      waveSpeed: uSpeed, damping: uDamp, swell: uSwell, strength: uRippleK,
      pokeSize: uPokeSharp, dropSize: uDropSharp,
    },
    /** カーソルの勢い。0にすると水は静まっていく */
    setPoke(v) { uPokeAmp.value = v; },
    /** 雫を1滴落とす。x,y は画面比（下端が0）、strength は落とす強さ */
    drop(x, y, strength) {
      uDrop.value.set(x, y);
      uDropAmp.value = strength;
      uDropAge.value = 0;
      dropFrames = 1;
    },
    /** 1フレームぶん進める。雫の後始末はここでやる */
    tick(dt) {
      if (useCompute) {
        if (dropFrames > 0 && --dropFrames <= 0) uDropAmp.value = 0;
      } else if (uDropAmp.value > 0) {
        uDropAge.value += dt;
        if (uDropAge.value > 6) uDropAmp.value = 0;   // 輪が薄れきったら畳む
      }
    },
    grid: N,
  };
}

/* うねりだけ欲しいとき（コースティクスの下地など）に外からも使う */
export { swellGrad };
