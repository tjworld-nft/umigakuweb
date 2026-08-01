/**
 * 端末ティア判定と、走らせながら効かせる品質ガバナ。
 *
 * 方針: 最初は控えめに始めて、実測のフレーム時間に余裕があれば上げる。
 *       重ければ 解像度 → 群れの数 の順に落とす（見た目の劣化が小さい順）。
 */

/* 魚の数は「群れの数 × 2のべき乗」にする。近傍サンプリングで
   `(i * 奇数) mod 群れの人数` を使うため、人数が2のべき乗だと必ず全員を巡回できる。 */
export const PRESETS = [
  { name: 'low',  fish: 384,  motes: 600,  bubbles: 10, dpr: 1.0,  neighbors: 12 },
  { name: 'mid',  fish: 768,  motes: 1100, bubbles: 16, dpr: 1.25, neighbors: 16 },
  { name: 'high', fish: 1536, motes: 2000, bubbles: 22, dpr: 1.5,  neighbors: 24 },
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
  constructor({ tier, onChange }) {
    this.maxTier = tier;
    this.tier = tier;
    this.onChange = onChange;
    this.ema = 16.7;
    this.good = 0;
    this.bad = 0;
    this.scale = 1;        // 群れの数の倍率（0.5〜1）
    this.dprScale = 1;     // 解像度の倍率（0.65〜1）
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
    const before = this.dprScale + this.scale;
    if (dir < 0) {
      // まず解像度、それでも重ければ群れを減らす
      if (this.dprScale > 0.66) this.dprScale = Math.max(0.65, this.dprScale - 0.175);
      else if (this.scale > 0.5) this.scale = Math.max(0.5, this.scale - 0.25);
    } else {
      if (this.scale < 1) this.scale = Math.min(1, this.scale + 0.25);
      else if (this.dprScale < 1) this.dprScale = Math.min(1, this.dprScale + 0.175);
    }
    if (this.dprScale + this.scale !== before) this.onChange(this);
  }
}
