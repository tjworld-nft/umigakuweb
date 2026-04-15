# ブログ記事テンプレート（frontmatter + 構成ガイド）

## frontmatter フォーマット

```yaml
---
title: "記事タイトル｜神奈川・三浦"
description: "120文字以内のmeta description"
date: "YYYY-MM-DD"
slug: "slug-name-YYYY-MM-DD"
category: "column | seasonal-guide | diving-log | beginner | sea-life"
tags: ["タグ1", "タグ2"]
keywords: ["検索キーワード1", "検索キーワード2"]

# 著者は ID で指定。実体は /三浦blog/authors/{id}.yml
author: "tetsuji-yoshida"

# 任意: 海況記事・体験ログ・取材記事で信頼性を示したい場合だけ付ける
verification:
  type: "field-verified"          # field-verified | experience-based | research-based
  checked_at: "2026-04-15"        # 現地確認日
  location: "三浦半島 城ヶ島"
  note: "水温・透明度・海況はすべて現地で実測しています。"
---
```

## 記事構成

1. **書き出し**: 「こんにちは、三浦 海の学校の吉田です。」＋ 今日の海況や季節感
2. **本文**: H2で区切ったセクション（5〜7セクション目安）
3. **内部リンク**: 本文中に自然に埋め込む（下記参照）
4. **まとめ**: 読者への呼びかけ ＋ `/contact/` リンク
5. **著者情報コンポーネント**: `templates/author-block.md` を貼り付け（トークン部分を authors/{id}.yml の値で置換）
6. **CTAブロック（BlogCTA）**: `templates/cta-block.md` から**該当バリアント1つだけ**をコピーして末尾に貼り付け

## BlogCTA バリアント判定（記事末尾の共通CTA）

`templates/cta-block.md` には3つのバリアントがコメントアウトで並んでいます。記事の `category` / `tags` に応じて、使うブロックのコメント（`<!--` / `-->`）を外して有効化します。

| 判定条件（いずれか該当） | 使うバリアント | 最優先導線 |
|---|---|---|
| `tags` に "初心者" を含む / `category: beginner` | **beginner** | `/license/`（初心者向け見出し「初めての方へ」） |
| `tags` に "ライセンス" / "OWD" / "AOW" / "PADI" を含む | **license** | `/license/` を最大強調（1ボタン目を太字化） |
| `category` が `diving-log` / `seasonal-guide` / `sea-life` | **log** | `/fun-diving/` を主導線、`/license/` は保険 |
| どれにも該当しない / 迷ったら | **beginner** を既定 | 同上 |

**運用ルール**:
- 各記事には**1バリアントのみ**貼る（重複NG）
- 文言を記事ごとにチューニングする場合は、バリアント先頭の「Props」コメントに従って `heading` / `reassurance` / `primaryLabel` などを書き換える
- LINEリンク（`https://lin.ee/kK3d5p2`）は全バリアント共通・変更不要
- HTMLインラインスタイルで組んであるため、Markdown→HTML変換（Next.js等）でもそのまま描画される

## 著者プロフィールコンポーネントの適用方法

### 現在の運用（Markdown手書き）

1. `/三浦blog/authors/` から該当著者の yml を開く
2. `templates/author-block.md` を記事末尾にコピー
3. `{{author.xxx}}` / `{{verification.xxx}}` を実値に置換
4. `verification` が不要な記事（通常コラムなど）は該当ブロックごと削除

### 将来の運用（PHP/静的サイトジェネレータでレンダリング）

frontmatter から `author` ID を読み、`templates/author-block.php` に `$author` と `$verification` を渡して include する。

```php
$post        = parse_frontmatter($md);
$author      = miura_load_author($post['author']);         // authors/{id}.yml をロード
$verification = $post['verification'] ?? null;
include __DIR__ . '/三浦blog/templates/author-block.php';
```

CSS は `templates/author-block.css` を読み込む。

## 複数著者対応

- 新しい著者は `/三浦blog/authors/{new-id}.yml` を追加するだけ
- 記事の frontmatter で `author: "{new-id}"` と指定
- コンポーネントは著者IDを使ってレジストリを引くので、テンプレート自体は変更不要

## 内部リンク挿入ガイド

記事カテゴリに応じて、本文中に以下のリンクを自然に含める:

| 記事カテゴリ | 必須リンク | 推奨リンク |
|---|---|---|
| beginner（初心者向け） | `/license/`, `/owd-license/` | `/kanagawa-diving-license/`, `/contact/` |
| column（コラム） | `/license/` or `/fun-diving/` | `/sea-life/`, `/contact/` |
| seasonal-guide（季節ガイド） | `/fun-diving/`, `/marine-activity/` | `/license/`, `/sea-life/` |
| diving-log（ダイビングログ） | `/fun-diving/`, `/sea-life/` | `/license/` |
| sea-life（生き物紹介） | `/sea-life/` | `/fun-diving/` |
