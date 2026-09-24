---
name: blog-post
description: 三浦 海の学校のブログ（miura-diving.com/blog/ ・Astro+Sanity）に記事を1本書いて公開するスキル。直近にダイビングがあれば現場レポート＋SEO要素、無ければ新特典やお店・ダイビング知識の集客SEO記事に自動で切り替える。Facebookから海況を取り、挿絵をAI生成し、Sanityに投入してデプロイするまで通しで行う。「ブログ書いて」「記事書いて」「ブログ更新して」「ブログ書ける？」「今週のブログ」「集客記事を書いて」などの依頼、および blog-update-reminder の定期実行から必ずこのスキルを使うこと。汎用の diving-blog スキルではなくこちらを使う（あちらはSanity投入・デプロイ・海況取得を知らない）。
---

# 三浦 海の学校ブログ — 記事1本を書いて公開する

記事を書くだけでなく、**ネタ決め → 事実確認 → 執筆 → 挿絵 → 機械検収 → 公開 → デプロイ**まで通す。

## 参照ファイル

| ファイル | 中身 | 読むタイミング |
|---|---|---|
| `references/writing-rules.md` | 文体・SEO・2モードの構成テンプレ・禁止事項 | 執筆の直前に必ず全部読む |
| `references/facts.md` | 料金や海況をどこから取るか、変わらない事実、NG表現 | 事実集めの前に読む |
| `scripts/gen_images.py` | 挿絵生成（fal Nano Banana Pro）＋WebP最適化 | 挿絵を作るとき |
| `scripts/preflight.mjs` | 公開前の機械チェック | 公開の直前に必ず走らせる |

## 前提

```bash
BLOG=~/Documents/umigaku-blog
[ -d "$BLOG" ] || git clone https://github.com/tjworld-nft/umigaku-blog.git "$BLOG"
ls ~/.config/sanity/miura-write-token   # Sanity書き込み（Editor）
ls ~/.config/fal/key                    # 画像生成
```

`$BLOG` に投入CLI（`npm run post`）とデプロイ（`npm run deploy:remote`）がある。詳細は `$BLOG/CLAUDE.md`。
`npm ci` は初回だけ必要（投入CLI自体は依存なしで動くが、ローカルビルドしたい場合に要る）。

---

## 1. モードを決める

### 1-1. 前回記事の日付を取る

```bash
curl -s --get "https://d2w2igz6.api.sanity.io/v2025-02-19/data/query/production" \
  --data-urlencode 'query=*[_type=="post"]|order(coalesce(publishedAt,_createdAt) desc)[0...5]{title,"slug":slug.current,"date":coalesce(publishedAt,_createdAt)}'
```

この5本のタイトルが**ネタかぶりの判定材料**になる。経過日数も出す（30日超なら記事内で更新が空いた旨に触れる）。

### 1-2. 前回記事以降のFacebook投稿を取る

`references/facts.md` の手順（Meta Business Suite ＋ javascript_tool）で収集する。

### 1-3. 分岐

**モードA（ログ記事）** — 前回記事以降のFB投稿に、実際に潜った・ツアーをした記述がある場合。
海況・生き物・現場の出来事が材料になる。SEO要素は「季節×三浦」の切り口で本文に混ぜる。

**モードB（集客SEO記事）** — 潜った記述が無い、または材料が薄い場合。ネタは次の優先順で選ぶ。

1. **進行中・開始予定の特典やキャンペーン**（`/buddy/` などサイトに載っているもの。開始日が近いものを優先）
2. **季節ネタ**（`三浦blog/files/seasonal-guide.md` と、その時季の海の話）
3. **ネタリスト**（`references/writing-rules.md` のリスト。1-1で取った既存記事とかぶるものは除外）

モードBでも、FB投稿に使える現場の話があれば1〜2段落だけ入れると記事が生きる。

**判断に迷ったら、選んだモードとネタを一言宣言してから書き始める。** 許可は求めない。

---

## 2. 事実を集める

`references/facts.md` に従う。要点だけ再掲する。

- **料金・特典は必ず本番ページから取得する。** 記憶やこのスキル内の記述から書かない
- **FB投稿に無い数値（水温○℃・透明度○m）は書かない。** 体感表現に留め「正確なところはご予約時に」に逃がす
- お客様のエピソードは実話のみ。FB投稿にあるものだけ使う
- 特典を扱うなら、期限・併用条件・上限も必ず書く

## 3. 書く

`references/writing-rules.md` を全部読んでから書く。作業ファイルは scratchpad に置く。

```
<scratchpad>/blog/<slug>/article.md   # 記事
<scratchpad>/blog/<slug>/images.json  # 挿絵の仕様
```

frontmatter は最低これだけ。モードBは `article_type: "seo"` と `target_keyword` も入れる。

```yaml
---
title: "（30〜40字・三浦/神奈川/ダイビングを含む）"
description: "（120字以内）"
date: "YYYY-MM-DD"        # 必ず「書いた当日」。1日に何本書いても全部その日にする
slug: "（英小文字とハイフン・末尾に日付。dateと同じ日）"
tags: ["ダイビング", "..."]
keywords: ["三浦 ダイビング", "..."]
mainImage: ./hero.webp
---
```

## 4. 挿絵を作る

3枚が基本（メイン1＋本文2）。`images.json` を書いて実行する。

```json
[
  {"name": "hero",  "aspect": "16:9", "width": 1600, "ref": true,
   "prompt": "被写体と構図を英語で。絵柄指定は自動で付くので書かなくてよい"},
  {"name": "sub1",  "aspect": "16:9", "width": 1200, "ref": false, "prompt": "..."},
  {"name": "sub2",  "aspect": "4:3",  "width": 1000, "ref": true,  "prompt": "..."}
]
```

```bash
python3 .claude/skills/blog-post/scripts/gen_images.py <scratchpad>/blog/<slug>/images.json \
  --out <scratchpad>/blog/<slug>
```

生成された `.webp` を Read で**必ず目視する**。表情が不安げ・構図が破綻・文字が写り込んだ、のいずれかなら
プロンプトを直して作り直す。良かったら記事に差し込む。

```markdown
![意味のあるalt。SEOキーワードを自然に含める](./sub1.webp)
```

## 5. 機械検収

```bash
node .claude/skills/blog-post/scripts/preflight.mjs <scratchpad>/blog/<slug>/article.md
```

NG が出たら直してから再実行する。WARN は1件ずつ目で見て、直すか意図的に見送るかを決める。
「裏付けの要る数値」のWARNは、FB投稿に実際の記録があるなら無視してよい。

## 6. 公開する

```bash
cd ~/Documents/umigaku-blog
npm run post -- <記事.md> --dry-run    # 変換結果の確認（トークン不要）
npm run post -- <記事.md> --publish    # 公開ドキュメントとして投入
```

**下書きにするか公開するかの既定**

- **ユーザーと対話中** … `--publish` で公開まで進める（毎回確認は取らない）
- **無人実行（blog-update-reminder などの定期実行）** … `--publish` を付けず**下書きで止め**、
  Studioのリンクを添えて通知する。人が読まずに公開されるのを避ける

ユーザーが「下書きで」「公開して」と明示したら、そちらに従う。

## 7. デプロイ

```bash
npm run deploy:remote
```

Sanityに入れただけでは本番に出ない。**FTPが `Error: Timeout (control socket)` で落ちることがある**
（2026-07-29に発生）。1回失敗しても異常ではないので、run IDを拾って再実行する。

```bash
gh run list -R tjworld-nft/umigaku-blog -L 1 --json databaseId --jq '.[0].databaseId'
gh run rerun <id> -R tjworld-nft/umigaku-blog --failed
```

## 8. 反映を確認して報告

```bash
curl -s -o /dev/null -w "%{http_code}\n" "https://miura-diving.com/blog/<slug>/"
curl -sL "https://miura-diving.com/blog/" | grep -oE 'datetime="[^"]*"' | head -3
```

報告に必ず入れる: 記事URL / 選んだモードとその理由 / 使った事実の出どころ / **判断して書かなかったこと**
（数値を避けた、など）/ デプロイが再実行になったならその事実。

---

## やらないこと

- 記事を書かずに「ネタ案だけ」出して終わる（1本仕上げるのがこのスキルの仕事）
- 料金・水温を推測で書く
- 他スクールとの比較で相手を貶める
- 同じ内容の記事を二重に出す（1-1のタイトル一覧と突き合わせる）
- 画像を最適化せずPNGのまま上げる（記事ページは `asset.url` を素で出すので重いまま配信される）
