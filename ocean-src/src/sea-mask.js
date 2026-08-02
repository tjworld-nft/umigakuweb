/**
 * 写真の中の「海」を、起動時に一度だけ切り出す。
 *
 * 元は「水平線より下で、青緑が強い画素」という式をシェーダの中で毎フレーム
 * 判定していた。式としては素直だが、**ダイビングの写真では人が青緑の服を着ている**。
 * 実測すると、ヒーロー写真の右のお客さんのラッシュガードは2割の画素が水として
 * 扱われ、最大98階調ぶん波打っていた（空は0で正しく除かれていた）。
 * 海の学校の写真は人が主役なので、これは直す価値がある。
 *
 * ここでやるのは「青緑の画素」ではなく「海とつながっている青緑の画素」を採ること。
 * 水平線のすぐ下から塗り広げるので、人の服のような**離れ小島は残らない**。
 * 写真を差し替えても数字を書き換えなくてよい点は元のやり方と同じ。
 *
 * 返すのは1チャンネルの小さなテクスチャ（写真UVでそのまま引ける）。
 * シェーダ側は1回読むだけで済み、毎フレームの判定が消える。
 */
import {
  DataTexture, RedFormat, UnsignedByteType, LinearFilter, ClampToEdgeWrapping,
} from 'three/webgpu';

const MASK_W = 192;          // これだけあれば輪郭は足りる。拡大は線形補間に任せる

/** sRGB(0〜255) → リニア(0〜1)。閾値は元のシェーダと同じリニア空間で揃える */
function toLinear(v) {
  const s = v / 255;
  return s <= 0.04045 ? s / 12.92 : Math.pow((s + 0.055) / 1.055, 2.4);
}

/**
 * @param {HTMLImageElement} img          ヒーロー写真（読み込み済み）
 * @param {number} horizonFromTop         水平線の位置（上端からの割合）
 * @returns {DataTexture|null}            画素を読めなかったときは null
 */
export function buildSeaMask(img, horizonFromTop) {
  const w = MASK_W;
  const h = Math.max(4, Math.round((w * img.naturalHeight) / img.naturalWidth));
  let px;
  try {
    const c = document.createElement('canvas');
    c.width = w; c.height = h;
    const g = c.getContext('2d', { willReadFrequently: true });
    g.drawImage(img, 0, 0, w, h);
    px = g.getImageData(0, 0, w, h).data;
  } catch (e) {
    return null;                       // 別ドメインの画像など。呼び出し側が写真の層を諦める
  }

  /* --- 1. 候補: 水平線より下で、青緑が赤より十分に強い画素 ---
     閾値は元の式と同じ（リニアで 0.17 以上）。ここではまだ人の服も混ざっている。 */
  const y0 = Math.min(h - 2, Math.max(1, Math.round(h * horizonFromTop)));
  const cand = new Uint8Array(w * h);
  for (let y = y0; y < h; y++) {
    for (let x = 0; x < w; x++) {
      const i = (y * w + x) * 4;
      const aqua = (toLinear(px[i + 1]) + toLinear(px[i + 2])) * 0.5 - toLinear(px[i]);
      if (aqua > 0.17) cand[y * w + x] = 1;
    }
  }

  /* --- 2. 水平線のすぐ下から塗り広げる ---
     海は水平線に接して一続きにつながっている。人の服はどこにも接していないので残らない。
     種は水平線の直下の数行ぶん取る（岩や島で欠けている列があるため）。 */
  const sea = new Uint8Array(w * h);
  const stack = [];
  for (let y = y0; y < Math.min(h, y0 + 3); y++) {
    for (let x = 0; x < w; x++) {
      const k = y * w + x;
      if (cand[k] && !sea[k]) { sea[k] = 1; stack.push(k); }
    }
  }
  while (stack.length) {
    const k = stack.pop();
    const x = k % w, y = (k / w) | 0;
    if (x > 0 && cand[k - 1] && !sea[k - 1]) { sea[k - 1] = 1; stack.push(k - 1); }
    if (x < w - 1 && cand[k + 1] && !sea[k + 1]) { sea[k + 1] = 1; stack.push(k + 1); }
    if (y > 0 && cand[k - w] && !sea[k - w]) { sea[k - w] = 1; stack.push(k - w); }
    if (y < h - 1 && cand[k + w] && !sea[k + w]) { sea[k + w] = 1; stack.push(k + w); }
  }

  /* --- 3. 少し痩せさせてから、なめらかにする ---
     輪郭でいきなり切り替わると、人の縁が1画素だけ波打って目につく。
     3×3で全部が海のときだけ残し（＝1画素ぶん痩せる）、そのあと2回ぼかす。 */
  const eroded = new Float32Array(w * h);
  for (let y = 0; y < h; y++) {
    for (let x = 0; x < w; x++) {
      let all = 1;
      for (let dy = -1; dy <= 1 && all; dy++) {
        for (let dx = -1; dx <= 1; dx++) {
          const nx = Math.min(w - 1, Math.max(0, x + dx));
          const ny = Math.min(h - 1, Math.max(0, y + dy));
          if (!sea[ny * w + nx]) { all = 0; break; }
        }
      }
      eroded[y * w + x] = all;
    }
  }

  let cur = eroded;
  for (let pass = 0; pass < 2; pass++) {
    const next = new Float32Array(w * h);
    for (let y = 0; y < h; y++) {
      for (let x = 0; x < w; x++) {
        let s = 0;
        for (let dy = -1; dy <= 1; dy++) {
          for (let dx = -1; dx <= 1; dx++) {
            const nx = Math.min(w - 1, Math.max(0, x + dx));
            const ny = Math.min(h - 1, Math.max(0, y + dy));
            s += cur[ny * w + nx];
          }
        }
        next[y * w + x] = s / 9;
      }
    }
    cur = next;
  }

  /* --- 4. テクスチャにする ---
     DataTexture は先頭行が v=0（＝下端）。canvas は先頭行が上端なので上下を入れ替える。 */
  const data = new Uint8Array(w * h);
  for (let y = 0; y < h; y++) {
    const src = y * w;
    const dst = (h - 1 - y) * w;
    for (let x = 0; x < w; x++) data[dst + x] = Math.round(cur[src + x] * 255);
  }

  const tex = new DataTexture(data, w, h, RedFormat, UnsignedByteType);
  tex.minFilter = LinearFilter;
  tex.magFilter = LinearFilter;
  tex.wrapS = tex.wrapT = ClampToEdgeWrapping;
  tex.generateMipmaps = false;
  tex.needsUpdate = true;

  /* どれくらい海として採れたか。極端に少ないときは切り出しに失敗している */
  let sum = 0;
  for (let i = 0; i < data.length; i++) sum += data[i];
  tex.userData.coverage = sum / (data.length * 255);

  return tex;
}
