/**
 * TSLの小道具。WebGPU（WGSL）とWebGL2（GLSL）の両方に同じ式からコンパイルされる。
 */
import { Fn, float, vec3, sin, cos, fract, abs, mix, smoothstep } from 'three/tsl';

/** 0〜1の擬似乱数。個体ごとの位相や初期配置に使う。 */
export const rand1 = /*@__PURE__*/ Fn(([n]) => {
  return fract(sin(float(n).mul(127.1).add(311.7)).mul(43758.5453));
});

/** 相関の弱い3つの乱数をまとめて。 */
export const rand3 = /*@__PURE__*/ Fn(([n]) => {
  const s = float(n);
  return vec3(rand1(s), rand1(s.add(19.19)), rand1(s.add(57.73)));
});

/** ゼロ割りでNaNを撒かない正規化。ボイドでは頻繁にゼロベクトルが出る。 */
export const safeNorm = /*@__PURE__*/ Fn(([v]) => {
  return v.div(v.length().max(1e-4));
});

/**
 * 潮の流れ。3方向の正弦を位相違いで重ねただけの、安くて滑らかな流れ場。
 * 魚群にも微粒子にも同じ場を食わせるので、水全体が一つの流れに見える。
 */
export const flowField = /*@__PURE__*/ Fn(([p, t]) => {
  const x = sin(p.z.mul(0.17).add(t.mul(0.23))).add(sin(p.y.mul(0.26).sub(t.mul(0.19)))).mul(0.5);
  const y = sin(p.x.mul(0.21).sub(t.mul(0.17))).mul(0.4);
  const z = cos(p.x.mul(0.14).add(t.mul(0.13))).mul(0.35);
  return vec3(x, y, z);
});

/**
 * 文字が乗っている帯を弱めるためのマスク。
 * y は画面の下端0・上端1。center/half は文字ブロックの位置と高さ（uniform）。
 * floor は「いちばん弱めたときにどこまで残すか」。
 */
export const guardMask = /*@__PURE__*/ Fn(([y, center, half, floor]) => {
  return mix(floor, float(1), smoothstep(half.mul(0.5), half.mul(1.7), abs(y.sub(center))));
});
