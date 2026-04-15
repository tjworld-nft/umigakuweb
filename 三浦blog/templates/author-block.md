<!-- 著者情報ブロック（E-E-A-T対応） — 全ブログ記事の末尾に挿入
     レンダラー導入前は下記をそのままコピーして使用。
     `author:` フィールド（tetsuji-yoshida など）を frontmatter で指定しておくこと。
     ================================================================ -->

---

<!-- verification: 海況・体験記事のみ挿入。不要な記事では丸ごと削除 -->
> ✅ **現地確認済み** ｜ 現地確認日: {{verification.checked_at}} ｜ {{verification.location}}
>
> {{verification.note}}

## この記事を書いた人

**{{author.name}}（{{author.name_kana}}）**
{{author.role}}

{{author.bio}}

- 🎖 {{author.credentials[0]}}
- 🎖 {{author.credentials[1]}}
- 🌊 {{author.experience.summary}}

📘 **関連書籍:** [{{author.books[0].title}}]({{author.books[0].url}})

👉 [プロフィール詳細を見る]({{author.profile_url}})
