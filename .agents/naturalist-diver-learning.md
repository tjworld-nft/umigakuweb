# ナチュラリストSP 事前学習（2026-11-24 作成）

受講画面 `aow-learning/course.php?course=naturalist`（登録コードで受講権限を付ける）。8章・全40問。
完了すると `NAT-YYYYMMDD-XXXXXXXX` の記録が出る。表示は「事前学習完了（インストラクター確認待ち）」で、
**PADIの認定や公式の学科修了ではない**（管理画面の説明にも書いてある）。

## 何をもとにしたか

- 吉田さんが添付した PADI「Underwater Naturalist Specialty Course Instructor Guide」（日本語版・Product No. 70235J・Rev. 12/10・Version 2.0）。
  この手引きの「知識開発」の学習目標（13〜14ページ）を8章に振り分けた。ダイブ1・ダイブ2の準備（2・4・5の課題、共生と食べる場面、人の痕跡）も入れた。
  **手引きの文はそのまま写していない**（言い回しが近すぎる文は Fable の点検で書き直した）。PDF 本体と抜き出した文はリポジトリに入れない。
- 2010年版の手引きなので、いまの版の手引き・公式教材・Knowledge Review と同じかは**未確認**。講習の前に担当インストラクターが確かめる。
- 神奈川県の漁業のきまり（イセエビ・アワビ・サザエ・タコなどをとらない）は県のページで確認。PADI AWARE は PADI の公開情報。
- 生き物の名前と性質は、店の図鑑 `sea-life/creatures.json` の書き方に合わせた。はっきりしない種は「〜のなかま」「〜と思われる」。

## 8章

| id | 章 | 中身 |
|---|---|---|
| ecosystem | 海という生態系 | 入れ子の生態系・光と色・陸とのちがい |
| relations | 食べる・食べられる・共生 | 食べる鎖・共生の +/0/− |
| names | 名前と分類 | 分類の段・地方名と標準和名・学名 |
| invertebrates | 海の植物と背骨のない動物 | 海藻と海草・7つのなかま（カイメン〜ホヤ） |
| vertebrates | 背骨のある動物 | 魚・サメとエイ・は虫類・ほ乳類 |
| myths | 「危険」と誤解 | 生き物は人をおそわない・身を守る反応 |
| interact | 人と生きものの関わり方 | さわらない・追わない・えさをやらない・神奈川のきまり |
| protect | 守る潜り方とその先へ | 中性浮力とトリム・PADI AWARE・次の講習 |

## 作り方（直すとき）

- 文と問題の正本は `.agents/naturalist-learning-content.json`。直したら `python3 .agents/build-naturalist.py` → `aow-learning/naturalist.html` ができる。
  ビルドは `lib.php` の正解（`course_answer_key()` の naturalist）と JSON の `answer` が一致しないと止まる。**問題を足す・正解を変えるときは lib.php も一緒に直す。**
- 章ごとに5問ずつ答える「ガイド付き」の型（DPVと同じ・`app.js` の `guidedStudy`）。見た目は `styles.css`＋`dpv.css`（共通の型）＋`naturalist.css`（この講座だけ）。
  `dpv.css` を変えると DPV とナチュラリストの両方に効く。
- 写真は `aow-learning/assets/naturalist/`（16枚・WebP・1000×750・EXIFなし）と、店の図鑑 `image/fish/web/`・サイトの `image/sub/`。
  新しい写真は `.agents/naturalist-tools/make_photos.py`（デスクトップの元写真→回転を直して4:3に切ってWebP）。
- 回帰テスト `php .agents/tests/learning-courses.php`（使い捨てのDBで101項目。スキーマ7への移り変わり・登録コードの権限・にせの完了をはねる・ほかの講座と混ざらない ほか）。

## 新しく作った写真（元はデスクトップ `01_写真・素材/`）

kelp-nudibranch=P9138233（2026-09-13）／green-algae=P8187696（08-18）／sponge-hermit=PB049148（11-05）／sea-fans=P9178300（09-17・切り抜き）／
anemone-field=PA299002（10-29）／octopus=PA218876（10-21）／squid=P8307975（08-30）／hermit-anemone=P9238324（09-23）／
anemone-shrimp-crab=P9058074（09-05）／urchin-crab=PA248938（10-24）／sea-squirt=P9058086（09-05・切り抜き）／ray-sand=P8117480（08-11・重いので品質70と軽いぼかしで作り直し）／
predation=P8157529（08-15）／diver-hover=PB079181（11-07・顔なし）／kelp-school=PB079178（11-07）／damsel-anemone=PB169218（11-16）。
お客さまの顔が分かる写真は使っていない。

## Fable の点検（2026-11-24）で直したこと

ゼブラガニを共生の例に使っていた／40問中27問で正解がいちばん長い選択肢だった（全部ならべ直した）／答えが問いに書いてある問題／
エイのおなかのえらに頼った場面／ダイブ2の「人の痕跡」の課題がなかった／手引きに近すぎる文／
「陸にプランクトンにあたる生き物はいない」の言い過ぎ／「5つの界で分ける国」→ 説の一つ／読みがなのない言葉。
スマホ（375px）で見て直したこと：見出しの1文字だけの行、食べる鎖の矢印、縦に並んだときの「左・右」の言い方、なかまのカードの詰まり。

## まだのこと

- 7つのなかまのうち「ゴカイのなかま」だけ写真がなく、文字のカード（写真の準備中です）にしてある。撮れたら `build-naturalist.py` の `PHYLUM_PHOTO` の `worm` に足す。海草（アマモ）も写真なしで文だけ。
- いまの版の手引き・公式教材との照らし合わせ（上記）。
