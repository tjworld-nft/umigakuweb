#!/usr/bin/env node
/**
 * 記事Markdownの公開前チェック。文体・SEO・禁止事項・slug重複を機械的に見る。
 *
 *   node preflight.mjs 記事.md
 *
 * NG が1件でもあれば exit 1。WARN は落とさないが必ず目で確認する。
 * 依存パッケージなし。slug重複チェックだけネットワークを使う（Sanityの公開APIのみ・トークン不要）。
 */
import { readFileSync, existsSync } from 'node:fs';
import { resolve, dirname } from 'node:path';

const PROJECT_ID = 'd2w2igz6';
const API = `https://${PROJECT_ID}.api.sanity.io/v2025-02-19/data/query/production`;

const file = process.argv[2];
if (!file) {
  console.error('使い方: node preflight.mjs 記事.md');
  process.exit(2);
}

const raw = readFileSync(resolve(file), 'utf8');
const fmMatch = raw.match(/^---\r?\n([\s\S]*?)\r?\n---\r?\n?/);
const meta = {};
if (fmMatch) {
  for (const line of fmMatch[1].split(/\r?\n/)) {
    const kv = line.match(/^([A-Za-z_][\w-]*)\s*:\s*(.*)$/);
    if (kv) meta[kv[1]] = kv[2].trim().replace(/^["'](.*)["']$/, '$1');
  }
}
const body = fmMatch ? raw.slice(fmMatch[0].length) : raw;

// 固定フッターより前を「本文」として数える
const footerIdx = body.indexOf('## 三浦 海の学校について');
const main = footerIdx >= 0 ? body.slice(0, footerIdx) : body;
const plain = main.replace(/!\[[^\]]*\]\([^)]*\)/g, '').replace(/\[([^\]]*)\]\([^)]*\)/g, '$1');
const chars = plain.replace(/\s/g, '').length;
const lead = plain.replace(/^#.*$/gm, '').trim().slice(0, 100);

const ng = [];
const warn = [];
const ok = [];

const check = (cond, msg, level = 'ng') => {
  if (cond) ok.push(msg);
  else (level === 'ng' ? ng : warn).push(msg);
};

// --- frontmatter ---
check(!!meta.title, 'title がある');
check(!!meta.slug, 'slug がある');
check(!!meta.description, 'description がある');
check(meta.description ? [...meta.description].length <= 120 : false,
  `description が120字以内（現在 ${meta.description ? [...meta.description].length : 0}字）`);
check(!!(meta.date || meta.publishedAt), 'date か publishedAt がある');
if (meta.article_type === 'seo') {
  check(!!meta.target_keyword, '集客記事なので target_keyword がある');
}

// --- タイトル ---
const titleHasKw = /三浦|神奈川|ダイビング/.test(meta.title || '');
check(titleHasKw, 'title に「三浦」「神奈川」「ダイビング」のいずれかが入っている');
const titleLen = [...(meta.title || '')].length;
check(titleLen >= 20 && titleLen <= 50, `title の長さが20〜50字（現在 ${titleLen}字）`, 'warn');

// --- 冒頭 ---
check(/^こんにちは、三浦 海の学校の吉田です。/.test(plain.trim()),
  '冒頭が「こんにちは、三浦 海の学校の吉田です。」で始まっている');
check(/三浦/.test(lead) && /ダイビング/.test(lead), '冒頭100字に「三浦」と「ダイビング」がある');

// --- 文体 ---
check(chars >= 2800 && chars <= 4200, `本文が3,000〜4,000字（現在 ${chars}字・フッター除く）`, 'warn');
const watashi = (plain.match(/私は|私が|私も|私の/g) || []).length;
check(watashi === 0, `一人称が「僕」に統一されている（「私」の使用 ${watashi}件）`, 'warn');
check(!/[\u{1F300}-\u{1FAFF}\u{2600}-\u{27BF}]/u.test(main), '絵文字を使っていない');

// --- SEO ---
const miuraCount = (plain.match(/三浦/g) || []).length;
const divingCount = (plain.match(/ダイビング/g) || []).length;
check(miuraCount >= 5, `本文に「三浦」が5回以上（現在 ${miuraCount}回）`);
check(divingCount >= 5, `本文に「ダイビング」が5回以上（現在 ${divingCount}回）`);
const h2s = [...main.matchAll(/^## (.+)$/gm)].map(m => m[1]);
check(h2s.length >= 3, `## 見出しが3つ以上（現在 ${h2s.length}個）`);
const h2NoKw = h2s.filter(h => !/三浦|神奈川|ダイビング|バディ|海|ライセンス|体験/.test(h));
check(h2NoKw.length === 0, `全ての ## 見出しにキーワードが入っている（無い見出し: ${h2NoKw.join(' / ') || 'なし'}）`, 'warn');
check(footerIdx >= 0, '固定フッター「## 三浦 海の学校について」がある');

// --- 禁止事項 ---
check(!/諸磯\s*1621|三崎町諸磯/.test(raw), '旧諸磯住所を書いていない');
check(!/絶対安全|100%大丈夫|絶対に安全|必ず安全/.test(main), '「絶対安全」等の断言をしていない');
const relLinks = [...main.matchAll(/\]\((\/[^)]*)\)/g)].map(m => m[1]);
check(relLinks.length === 0, `内部リンクが絶対URL（相対リンク: ${relLinks.join(', ') || 'なし'}）`, 'warn');

// --- 裏付けの要る数値 ---
const numeric = [...main.matchAll(/(水温|透明度|気温)[^。]{0,12}?(\d+(?:\.\d+)?)\s*(℃|度|m|メートル)/g)]
  .map(m => m[0]);
check(numeric.length === 0,
  `裏付けの要る数値がない（見つかった記述: ${numeric.join(' / ') || 'なし'}／実測記録があるなら無視してよい）`, 'warn');

// --- 画像 ---
const imgs = [...raw.matchAll(/!\[[^\]]*\]\(([^)\s]+)\)/g)].map(m => m[1]);
const missing = [];
for (const p of [...imgs, meta.mainImage].filter(Boolean)) {
  if (/^https?:/.test(p)) continue;
  if (!existsSync(resolve(dirname(resolve(file)), p))) missing.push(p);
}
check(missing.length === 0, `参照している画像が全部ある（無い: ${missing.join(', ') || 'なし'}）`);
check(!!meta.mainImage, 'mainImage がある', 'warn');
check(imgs.length >= 1, `本文中の画像が1枚以上（現在 ${imgs.length}枚）`, 'warn');

// --- Sanity 側の重複 ---
const q = async (query) => {
  const u = new URL(API);
  u.searchParams.set('query', query);
  const r = await fetch(u);
  return (await r.json()).result;
};

let dup = null;
let similar = [];
try {
  dup = await q(`*[_type=="post" && slug.current=="${meta.slug}"][0]{_id}`);
  const titles = await q('*[_type=="post"]|order(coalesce(publishedAt,_createdAt) desc)[0...40]{title,"slug":slug.current}');
  const words = [...new Set((meta.title || '').match(/[ぁ-んァ-ヶ一-龠A-Za-z]{3,}/g) || [])];
  similar = (titles || [])
    .map(t => ({ ...t, hits: words.filter(w => t.title.includes(w)).length }))
    .filter(t => t.hits >= 3)
    .sort((a, b) => b.hits - a.hits)
    .slice(0, 3);
} catch (e) {
  warn.push(`Sanityの重複チェックに失敗（${e.message}）— 手で確認する`);
}
check(!dup, `slug "${meta.slug}" が未使用`);
check(similar.length === 0,
  `既存記事とタイトルが似ていない${similar.length ? '（似: ' + similar.map(s => `${s.title}（${s.slug}）`).join(' / ') + '）' : ''}`, 'warn');

// --- 出力 ---
console.log(`\n=== preflight: ${meta.slug || file} ===`);
console.log(`本文 ${chars}字 / 見出し ${h2s.length} / 画像 ${imgs.length}枚 / モード ${meta.article_type === 'seo' ? '集客SEO' : 'ログ'}`);
if (ok.length) console.log(`\n✅ OK ${ok.length}件`);
if (warn.length) {
  console.log(`\n⚠️  WARN ${warn.length}件（要目視）`);
  for (const w of warn) console.log(`   - ${w}`);
}
if (ng.length) {
  console.log(`\n❌ NG ${ng.length}件（直すまで公開しない）`);
  for (const n of ng) console.log(`   - ${n}`);
  process.exit(1);
}
console.log('\nNGなし。公開して差し支えない。');
