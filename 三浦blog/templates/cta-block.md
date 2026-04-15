<!--
================================================================
  BlogCTA — ブログ記事末尾の共通CTAコンポーネント
================================================================
  目的  : 記事を読んだ読者を「次の行動」へ自然に誘導する
  導線  : ① ライセンス取得ページ  ② よくある質問  ③ LINEで相談
  適用  : 記事末尾（著者情報ブロックの後）に挿入
  出し分: frontmatter の category / tags で下のバリアントを選択
----------------------------------------------------------------
  【バリアント一覧】
    - beginner  : 初心者向け記事（tags に "初心者" / category: beginner）
    - license   : ライセンス講習記事（tags に "ライセンス" / "OWD" / "AOW" 等）
    - log       : 海況・日記・季節ガイド（category: diving-log / seasonal-guide / sea-life）
  ※ どれにも当てはまらない場合は「beginner」を既定にする
================================================================

  【Props（差し替え可能な値）】
    heading       : 見出し（例: "初めての方へ" / "次のステップへ" / "また海に潜ろう"）
    reassurance   : 不安解消の一文
    primaryLabel  : メインボタンのラベル
    primaryHref   : メインボタンのリンク先
    secondaryLabel: サブボタンのラベル
    secondaryHref : サブボタンのリンク先
    lineHref      : LINE相談のリンク先（通常は固定）

  使い方: 記事末尾に、該当バリアントのブロック1つだけをコピペして貼る。
         文言を変えたい場合は下の Props に従って書き換える。
================================================================
-->

---

<!-- ============================================================
     Variant: beginner  （初心者向け記事用 — 既定）
     対象: tags に "初心者" / category: beginner / "ダイビング 始め方" 系
============================================================ -->

## 初めての方へ

「泳ぎが苦手でも大丈夫かな？」「体力に自信がない…」そんな不安も、三浦 海の学校では専用プールでの事前練習と少人数制でしっかりサポートします。まずは気軽にのぞいてみてください。

<div class="blog-cta" style="display:flex;flex-direction:column;gap:12px;max-width:480px;margin:24px auto;">
  <a href="/license/" style="display:block;padding:16px 20px;background:#006fb9;color:#fff;text-align:center;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    ▶ ダイビングライセンス取得ページを見る
  </a>
  <a href="/beginner-diving-guide/#faq" style="display:block;padding:16px 20px;background:#fff;color:#006fb9;text-align:center;border:2px solid #006fb9;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    ❓ よくある質問を見る
  </a>
  <a href="https://lin.ee/kK3d5p2" style="display:block;padding:16px 20px;background:#06c755;color:#fff;text-align:center;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    💬 LINEで気軽に相談する
  </a>
</div>

<p style="text-align:center;font-size:13px;color:#666;margin-top:-8px;">迷ったらLINEへ。質問1つでもOKです。</p>

<!-- ============================================================
     Variant: license  （ライセンス講習記事用）
     対象: tags に "ライセンス" / "OWD" / "AOW" / "PADI" / category に license系
     → /license/ を最優先導線に固定
============================================================ -->
<!--
## ライセンス取得を考えている方へ

料金・日程・取得までの流れは、ライセンス取得ページですべて確認できます。ご不明点は何でもお気軽にご相談ください。

<div class="blog-cta" style="display:flex;flex-direction:column;gap:12px;max-width:480px;margin:24px auto;">
  <a href="/license/" style="display:block;padding:18px 20px;background:#006fb9;color:#fff;text-align:center;border-radius:8px;text-decoration:none;font-weight:700;font-size:17px;line-height:1.4;">
    ▶ ダイビングライセンス取得ページを見る（最優先）
  </a>
  <a href="/owd-license/#faq" style="display:block;padding:16px 20px;background:#fff;color:#006fb9;text-align:center;border:2px solid #006fb9;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    ❓ ライセンス講習のよくある質問
  </a>
  <a href="https://lin.ee/kK3d5p2" style="display:block;padding:16px 20px;background:#06c755;color:#fff;text-align:center;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    💬 LINEで日程・料金を相談する
  </a>
</div>

<p style="text-align:center;font-size:13px;color:#666;margin-top:-8px;">「自分に合うコースが分からない」— そんな相談からでもOKです。</p>
-->

<!-- ============================================================
     Variant: log  （海況・日記・季節ガイド・生き物紹介用）
     対象: category: diving-log / seasonal-guide / sea-life
     → ファンダイビング優先、初心者向けは2ndで保険
============================================================ -->
<!--
## また三浦の海に潜りに来ませんか

今日の海況を読んで「潜りたい」と思ったら、その気持ちが何よりのタイミング。経験者の方はファンダイビングへ、これからの方はライセンス取得からご案内します。

<div class="blog-cta" style="display:flex;flex-direction:column;gap:12px;max-width:480px;margin:24px auto;">
  <a href="/fun-diving/" style="display:block;padding:16px 20px;background:#006fb9;color:#fff;text-align:center;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    🐠 ファンダイビングのご案内を見る
  </a>
  <a href="/license/" style="display:block;padding:16px 20px;background:#fff;color:#006fb9;text-align:center;border:2px solid #006fb9;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    🎓 ライセンス取得ページを見る
  </a>
  <a href="https://lin.ee/kK3d5p2" style="display:block;padding:16px 20px;background:#06c755;color:#fff;text-align:center;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;line-height:1.4;">
    💬 LINEで海況を相談する
  </a>
</div>

<p style="text-align:center;font-size:13px;color:#666;margin-top:-8px;">海況・透明度・生き物情報はLINEが一番早いです。</p>
-->

---

<p style="text-align:center;font-size:14px;color:#555;">
  📞 <a href="tel:046-880-0835">046-880-0835</a>（9:00〜16:00）　✉️ <a href="/contact/">お問い合わせフォーム</a>
</p>
