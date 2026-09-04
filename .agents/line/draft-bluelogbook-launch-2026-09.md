# LINE配信: Blue Logbook（iPhoneアプリ）公開のお知らせ

## 状態: 下書き（未配信）— 2026-09-04 作成

- 事実の正本: App Store公開 2026-09-03 19:20 JST（reviewSubmission COMPLETE）／lookup API・apps.apple.com とも反映済みを 2026-09-04 に確認
- App Store: https://apps.apple.com/jp/app/id6806158093 （無料・iPhone / iPad・iOS 15以上・約38MB）
- 案内ページ: https://miura-diving.com/bluelogbook/ （入れ方・使い方・ブラウザ版からの引っ越し・FAQ）
- まんが第20話: https://miura-diving.com/manga/logbook-app/ （P11を「公開中」に差し替え済み）
- 画像: https://miura-diving.com/image/bluelogbook/launch-2026-09-1080.jpg （1080x1080・130KB・原本/プレビュー兼用）

## 無料枠の裁定（吉田さんの判断が要る）

- 9月の無料枠は 200通。前回8/25の narrowcast（除外8人）で届いたのは **114人**。
  いま全員配信すると **約114〜122通** を使い、**9/15前後に予定していた「夏割あと2週間」リマインドの全員配信は枠に入らない**（合計228超）。
- 選択肢:
  1. **今すぐアプリ告知を全員に送る（推奨）** ＋ 末尾に「夏割は9/30まで」の1行を添える → 9/15のリマインドは送らない（or 個別push数人）
  2. アプリ告知を9/15のリマインドに相乗りさせる（1通で両方）→ まんが・ブログで「出せたらすぐ知らせる」と約束しているので遅れる
  3. 両方送る → ライトプラン（月¥5,000・5,000通）に9月だけ切替
- 推奨は 1。理由: 相手は認定ダイバーが多数派＝ログブックの当事者で、まんが・ブログ・ASCの導線の締めがこの1通。夏割は8/25に一度届いている。

## 配信コマンド（プレビューのみ。送るときは `--yes` を付ける）

```bash
cd "/Users/tetsujiyoshida/Documents/海学HP.php" && python3 .agents/line/line_broadcast.py narrowcast --exclude-audience 2352293998572 --image https://miura-diving.com/image/bluelogbook/launch-2026-09-1080.jpg --text "$(sed -n '/^```text$/,/^```$/p' .agents/line/draft-bluelogbook-launch-2026-09.md | sed '1d;$d')"
```

※ `--exclude-audience 2352293998572` は8/25と同じ「配信除外リスト」（8人）。除外せず全員に送るなら `narrowcast --exclude-audience …` を `broadcast` に置き換える。`--yes` を付けるまでは送られない（プレビューと送信見込み人数だけ出る）。

## 配信本文（1吹き出し目=この本文・500字以内、2吹き出し目=画像。CLIはテキスト→画像の順に組む＝プッシュ通知のプレビューに冒頭の文が出る）

```text
【iPhoneアプリ、できました📱🤿】

まんが第20話でお知らせしたログブックのアプリ
「Blue Logbook（ブルー・ログブック）」が
App Storeで公開になりました。

■ Blue Logbook
・iPhone / iPad・無料・アカウント登録なし
・潜った日の記録・写真・出会った生き物・Cカード・器材を1つに
・三浦で会える89種のマイ図鑑つき
・記録はスマホの中。書き出せば機種変更でも持っていけます

▼ App Store
https://apps.apple.com/jp/app/id6806158093

▼ 入れ方・使い方・ブラウザ版からの引っ越し
https://miura-diving.com/bluelogbook/

ブラウザ版をお使いの方は、書き出したファイルをアプリの「保存」タブで読み込むだけで引っ越せます。
Androidの方は、ブラウザ版をこれまでどおりお使いください。

紙のログブックはこれからも使います。そのうえで、手のひらにも1冊どうぞ。
使ってみて「ここが使いにくい」があれば、このトークで教えてください。次の版に入れます。

（夏割・アドバンス夏割は 9月30日 までです）
```

## 検品メモ（2026-09-04）

- 機械: 500字以内（本文 約420字）・URL2本は https・旧住所なし・「日本初／唯一／No.1」なし
- 事実: 無料・登録なし・89種・書き出し引っ越し・Android未対応 = `store/metadata_ja.md` と [[umigaku-logbook-app]] の記録どおり。公開日はASC/lookup APIで確認
- リーガル: 価格表示は「無料」のみ（二重価格なし）。レビュー依頼の見返りなし（Appleの規約上、レビューと引き換えの特典は不可）
- 文体: 1通1CTA（入れる）。夏割の1行は括弧の追記に留めた（枠の裁定で消してよい）
