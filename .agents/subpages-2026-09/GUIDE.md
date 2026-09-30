# 下層ページ作り直しガイド（三浦 海の学校・2026-09-27）

あなたは、トップページと同じデザインで下層ページを作り直す担当です。**すでに完成した見本が2つあります。必ず先に読んでください。**

- 見本1: `pages/license.html`（ライセンス取得）＋ `pages/license.ld.json`
- 見本2: `pages/fun-diving.html`（ファンダイビング）＋ `pages/fun-diving.ld.json`
- 組み立て: `tools/build.py`（ヘッダー・フッター・相談帯・構造化データ WebPage/BreadcrumbList/FAQPage を自動で付ける）
- デザインの部品: サイトの `css/page.css`（**編集禁止**。足りない見た目は、既存の部品の組み合わせと、どうしても必要なときだけ style 属性で）
- 動き: サイトの `js/page.js`（編集禁止）
- 事実の正本: `FACTS.md`（このフォルダ）。**ここと、元のページに書いてあること以外の事実を足さない。**
- 元のページの文字起こし: `text/<ページ名>.txt`（元のHTMLはサイトの `<ページ>/index.html` 。作り直すと上書きされるので、最初に `git show HEAD:<ページ>/index.html` で読むこと）

パス:
- scratchpad = `/private/tmp/claude-501/-Users-tetsujiyoshida-Documents---HP-php/148320cc-5f87-40da-95ce-e7e90d4ad4ee/scratchpad`
- サイト（git worktree）= `/Users/tetsujiyoshida/.codex/worktrees/umigaku-subpages-redesign/site`
- 確認用サーバー: http://127.0.0.1:8978/ （起動済み。サイトのフォルダをそのまま配信）
- スクショ: `node tools/shoot.js <出力フォルダ> <幅> <パス/> …`（例 `node tools/shoot.js shots/agentX 390 trial-diving/`）→ `python3 tools/sheet.py <png> <jpg> 2600` で1枚に並べて Read で目視

## やること（1ページごと）

1. 元のページを読む（`git show HEAD:<path>/index.html` と `text/*.txt`）。**書いてある事実・料金・注意書き・リンク先をすべて拾う**（消していいのは、FACTS.md と食い違う古い記述と、根拠のない誇張だけ。消したものは報告に書く）。
2. `pages/<name>.html` を書く。先頭の `<!--META {...} META-->` の JSON は見本と同じキー。
   - `path`（例 "trial-diving/"）、`current`（ナビで光らせるキー。build.py の NAV_* を参照）、`from`（フォームの流入元 ?from= に使う英数字）
   - `title`（32〜40字目安。「主な検索語｜差別化の数字や場所｜三浦 海の学校」）、`desc`（100〜130字。料金・場所・誰向けを入れる）、`ogTitle`、`ogDesc`、`og`（/image/og-<name>.jpg。無ければ私＝親が作るので、パスだけ書く）、`ogAlt`
   - `hero`（ヒーロー写真の 800w/1600w。無い写真は srcset を使わず単体で src だけにし、hero キーは省略可）
   - `crumbs`（[["表示名", "/path/"]]）、`about`（ページ固有の構造化データの @id。無ければ省略）
   - `ask`（相談帯の title / lead / msg（LINEで送る文面・コピーされる）/ cat（フォームの種別。contact/index.html の option の value と完全一致させる：体験ダイビング／OWDライセンス／AOWライセンス／ファンダイビング／リフレッシュ／マリンアクティビティ／書籍・講座／その他））
   - `noAsk: true` で相談帯を出さない（法的ページ用）
3. 本文の骨組みは見本どおり：`<main id="main">` → `.ph`（ヒーロー・パンくず・h1）→ `.facts`（4つの数字）→（期間限定があれば `.camp` data-expires="2026-10-01"）→ `.lnav`（目次・data-depth は「このページの水深」の最大m。体験=12、スノーケリング=5 など内容に合う数字）→ `.sec` の並び（白 / `.sec--soft` を交互）→ FAQ（`<details class="faq-item"><summary>質問</summary><div class="faq-answer">答え</div></details>` の形。**この形でないと構造化データに入らない**）→ `.about`（検索向けの読み物・元ページの末尾の説明文を活かす）。`</main>` は build.py が閉じるので書かない。
4. `pages/<name>.ld.json` に、そのページ固有の構造化データ（Service / Course / Person など）を配列で。WebPage・BreadcrumbList・FAQPage は自動なので書かない。料金は税込の数字の文字列。
5. `python3 tools/build.py <name>` で書き出し → 390px と 1366px でスクショ → 目視で崩れ（横はみ出し・文字の重なり・画像の欠け）を直す。shoot.js の出力に `BROKEN IMG` や `ERR` が出たら直す。`sw` と `cw` が違えば横はみ出し。
6. 最後に `python3 tools/check.py <name>` を実行（料金・禁止語・リンク切れの機械検査）。NG が0になるまで直す。

## 書き方の決まり

- 文体はトップと同じ。やわらかい「です・ます」、短い文、漢字を開きすぎない。**誇張しない**（日本初・唯一・No.1・必ず・絶対・業界最安 は使わない）。
- 料金は「税込」を明記し、レンタル器材が別のものは必ず「レンタル器材別（1日¥5,500）」か、足した**総額**を書く。
- 所在地は「神奈川県三浦市」「開催場所・集合場所はご予約時にご案内します」だけ。**旧住所（諸磯）は絶対に書かない。**
- 電話番号 080-4350-0412 は contact と tokusho 以外に出さない。
- お客様の声は FACTS.md の3つだけ（名前や在住地を足さない）。AggregateRating（星の数）は使わない。
- 内部リンクは `/license/` のようにルートからの相対パス（`index.html` を付けない）。まんがは `https://miura-diving.com/manga/<slug>/`。
- 画像は `width` `height` `loading="lazy"` `decoding="async"`（ヒーローだけ fetchpriority="high"・lazy なし）と、意味のある alt。使える写真は `image/sub/*`（今回撮影分・`tools/prep_images.py` の JOBS に一覧）と `image/optimized/*`、`image/home/*`（ただし `sites-step-*.webp` は文字入りなので使わない）。
- Font Awesome は使わない（アイコンは build.py の sprite: i-line i-mail i-play i-clock i-pin i-train i-cal i-fb i-x i-ig を `<svg aria-hidden="true"><use href="#i-line"/></svg>` で）。絵文字のアイコンも使わない。
- LINEボタンは `class="btn-line"` ＋ `data-line-msg="送る文面"`（押すと文面がコピーされる）。
- 期間限定の表示には `data-expires="終了日の翌日"` を付ける（例：夏割は `2026-10-01`。9/30で終了し、HTMLからも削除済み）。期間が終わったら JS 任せにせず HTML と pages/ からも消す（読み込み中のずれを防ぐ）。
- 新しいCSSクラスを作らない（page.css を読んで、あるものを使う）。どうしても必要なら style 属性で最小限。
- 見出しの階層を守る（h1 は1つ、h2 はセクション、h3 はその中）。

## 報告（最後に返すもの）

- 作ったページと、ビルド結果（バイト数・FAQ数）、スクショで確認した幅
- 元ページから**消した事実・直した事実**の一覧（理由つき）
- 確信が持てず、親（私）に判断してほしいこと
- `tools/check.py` の結果
