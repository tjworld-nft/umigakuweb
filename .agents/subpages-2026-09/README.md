# 下層ページの組み立て一式（2026-09-27 リニューアル時）

2026-09-27 に下層ページ15本（license / fun-diving / trial-diving / marine-activity / beginner-guide / instructor / contact / sea-life /
diving-point/jogashima-kajinohama / tokusho / privacy-policy / 地域ページ4本）を作り直したときの道具と原稿。**デプロイ対象外**（`.agents/`）。

- `pages/<名前>.html` … 本文の断片（先頭に META JSON）。`pages/<名前>.ld.json` … そのページ固有の構造化データ。
- `tools/build.py` … 断片にヘッダー・フッター・相談帯・構造化データ（WebPage / BreadcrumbList / FAQPage は自動）を付けて各 `index.html` を書き出す。
- `tools/check.py` … 機械検査（禁止語・誤った所要時間・電話番号の出し場所・FACTSに無い金額・リンク切れ・画像切れ・期限つき文言の data-expires 漏れ・h1の数）。
- `tools/og.py` … OGP画像（写真＋見出し・PIL）。`tools/prep_images.py` … 撮影写真（~/Desktop/日付/）→ `image/sub/*.webp`。
- `FACTS.md` … 料金・所要時間など事実の正本（2026-09-27時点）。`GUIDE.md` … 分担したときの作り方の決まり。

⚠ **公開後の正は各 `index.html`**。ここから作り直すと、その後にHTMLへ直接入れた修正が消える。
使うなら先に `git diff` で HTML とここの差を確かめ、断片の側へ取り込んでから `python3 tools/build.py <名前>`。
ヘッダー・フッターの文言を全ページで変えたいときは、build.py を直して全部を書き出すのがいちばん確実（そのときも上の確認をする）。
