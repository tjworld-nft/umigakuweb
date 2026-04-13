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
author:
  name: "吉田 哲士"
  role: "三浦 海の学校 代表 / PADIコースディレクター"
  url: "https://miura-diving.com"
  credentials: "PADIコースディレクター / PADI 5スターIDCセンター運営 / 指導歴20年以上"
---
```

## 記事構成

1. **書き出し**: 「こんにちは、三浦 海の学校の吉田です。」＋ 今日の海況や季節感
2. **本文**: H2で区切ったセクション（5〜7セクション目安）
3. **内部リンク**: 本文中に自然に埋め込む（下記参照）
4. **まとめ**: 読者への呼びかけ ＋ `/contact/` リンク
5. **著者情報**: `templates/author-block.md` をコピー
6. **CTAブロック**: `templates/cta-block.md` をコピー

## 内部リンク挿入ガイド

記事カテゴリに応じて、本文中に以下のリンクを自然に含める:

| 記事カテゴリ | 必須リンク | 推奨リンク |
|---|---|---|
| beginner（初心者向け） | `/license/`, `/owd-license/` | `/kanagawa-diving-license/`, `/contact/` |
| column（コラム） | `/license/` or `/fun-diving/` | `/sea-life/`, `/contact/` |
| seasonal-guide（季節ガイド） | `/fun-diving/`, `/marine-activity/` | `/license/`, `/sea-life/` |
| diving-log（ダイビングログ） | `/fun-diving/`, `/sea-life/` | `/license/` |
| sea-life（生き物紹介） | `/sea-life/` | `/fun-diving/` |
