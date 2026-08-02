/**
 * 三浦 海の学校 — ヒーロー海中レイヤーのビルド
 *
 *   npm install && npm run build
 *
 * three.js の WebGPU ビルドをツリーシェイクして、単一の ESM チャンク
 * ../js/ocean/ocean.min.js を吐く。生成物はリポジトリにコミットする
 * （本番はmainへのpushでFTP同期される静的配信のため、サーバ側にビルド工程がない）。
 */
import { build, context } from 'esbuild';
import { readFileSync, writeFileSync, statSync } from 'node:fs';
import { brotliCompressSync } from 'node:zlib';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';

const here = dirname(fileURLToPath(import.meta.url));
const outfile = join(here, '..', 'js', 'ocean', 'ocean.min.js');

const banner = `/*! 三浦 海の学校 — Ocean Layer (WebGPU/WebGL2)
 * https://miura-diving.com/  |  bundles three.js r185 (MIT, (c) three.js authors)
 * ソース: ocean-src/  ビルド: npm run build
 */`;

/** @type {import('esbuild').BuildOptions} */
const options = {
  entryPoints: [join(here, 'src', 'main.js')],
  bundle: true,
  format: 'esm',
  target: ['es2022'],
  minify: true,
  legalComments: 'none',
  treeShaking: true,
  banner: { js: banner },
  outfile,
};

const report = () => {
  const raw = readFileSync(outfile);
  const br = brotliCompressSync(raw).length;
  const kb = (n) => (n / 1024).toFixed(1) + ' KB';
  console.log(`  ocean.min.js  raw ${kb(raw.length)}  /  brotli ${kb(br)}`);
  // 本番のnginxはbrotliを返す。転送量が増えたら気づけるように上限を決めておく。
  const LIMIT = 230 * 1024;
  if (br > LIMIT) {
    console.error(`  ✗ brotli後が上限 ${kb(LIMIT)} を超えました。import を見直すこと。`);
    process.exit(1);
  }
  console.log('  ✓ 転送量の上限内');
};

if (process.argv.includes('--watch')) {
  const ctx = await context(options);
  await ctx.watch();
  console.log('watching ocean-src/src ...');
} else {
  await build(options);
  report();
}

// boot.js は素のJSなのでバンドルしない。存在だけ確かめておく。
try {
  statSync(join(here, '..', 'js', 'ocean', 'boot.js'));
} catch {
  console.error('  ✗ js/ocean/boot.js が見つかりません');
  process.exit(1);
}
