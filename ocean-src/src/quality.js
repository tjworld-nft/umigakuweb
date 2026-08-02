/**
 * 端末ティア判定と、走らせながら効かせる品質ガバナ。
 *
 * 方針: 最初は控えめに始めて、実測のフレーム時間に余裕があれば上げる。
 *       重ければ描画解像度を落とす（水の見た目がいちばん崩れにくい）。
 *       波の計算そのものは元が安いので落とさない。
 */
export const PRESETS = [
  { name: 'low',  dpr: 1.0,  grid: 128 },
  { name: 'mid',  dpr: 1.25, grid: 160 },
  { name: 'high', dpr: 1.5,  grid: 208 },
];

export function detectTier({ webgpu, cores = 8, memory }) {
  const coarse = matchMedia('(pointer: coarse)').matches;
  let tier = 2;

  if (coarse) tier = 1;                       // スマホ・タブレットは中から
  if (!webgpu) tier = Math.min(tier, 1);      // WebGL2フォールバックは中まで
  if (cores <= 4) tier = Math.min(tier, 1);
  if (memory !== undefined && memory <= 4) tier = Math.min(tier, 1);
  if (cores <= 4 && coarse) tier = 0;

  return tier;
}

/**
 * フレーム時間の指数移動平均を見て、段階的に上げ下げする。
 * 上下でしきい値を離してあるので、境目でガタつかない。
 */
export class Governor {
  constructor({ onChange }) {
    this.onChange = onChange;
    this.ema = 16.7;
    this.good = 0;
    this.bad = 0;
    this.dprScale = 1;     // 解像度の倍率（0.6〜1）
  }

  /** @param {number} dtMs 直前のフレームにかかった実時間 */
  sample(dtMs) {
    if (dtMs > 250) return;                       // タブ復帰などの外れ値は捨てる
    this.ema += (dtMs - this.ema) * 0.05;

    if (this.ema > 22) { this.bad++; this.good = 0; }
    else if (this.ema < 13) { this.good++; this.bad = 0; }
    else { this.bad = 0; this.good = 0; }

    if (this.bad > 90) { this.bad = 0; this.step(-1); }
    else if (this.good > 420) { this.good = 0; this.step(+1); }
  }

  step(dir) {
    const before = this.dprScale;
    this.dprScale = dir < 0
      ? Math.max(0.6, this.dprScale - 0.2)
      : Math.min(1, this.dprScale + 0.2);
    if (this.dprScale !== before) this.onChange(this);
  }
}
