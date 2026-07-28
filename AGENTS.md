# 三浦 海の学校 — サイト作業ガイド（AIエージェント向け）

新しいチャット・Codex・他のAIツールで続きを作業するときは、まずこのファイルを読むこと。
（このファイルは deploy.yml で本番アップロード対象外。サーバーに上げないこと）

## 現在の状態（2026-07-18 更新）

- **GitHub凍結は2026-07-17に解除確認済み**。通常フロー「`feature/site-polish` にコミット→push→PR→mainマージ→Actions FTP自動デプロイ」に復帰している。手動FTPSデプロイは不要（緊急時のバックアップ手段としてのみ）。
- 本番 https://miura-diving.com/ は main と同期。ローカル作業ブランチは `feature/site-polish`。

## 進行中: お友達紹介「バディ割」（2026-08-01開始予定）

- 紹介ページ **`buddy/index.html`**（https://miura-diving.com/buddy/ ・index可・転送される前提で執筆）。
- **設計・オファー・条件・未決事項の正は `.agents/buddy-campaign.md`**（deploy対象外）。
- 特典: 紹介した認定ダイバー=付き添いダイビング**¥6,600引き**（ビーチ¥13,200→¥6,600＝半額／ボート¥19,800→¥13,200）／受講する友達=器材レンタル1日分¥5,500無料（OWD・AOW）。
- 合言葉は「**バディ**」＋紹介者名。広告経由の「夏割」とは別（計測を混ぜないため）。フォームは `?from=buddy`。
- 導線: `license/`・`fun-diving/` にバナー、`lp/owd/` は料金直後の小ブロックのみ（広告LPのCTAは変えない）。
- LINE配信の下書きは `.agents/line/draft-buddy-2026-08.md`（**未送信**）。
- ⚠ セット受講（OWD+AOW）は特典を2回適用（−¥11,000・総額¥119,700）。1回だと別々に取るより高くなり逆転する。

## 進行中: OWD集客のGoogle広告（総予算¥5,000）

- 広告用LP **`lp/owd/index.html`** を2026-07-11に本番公開済み（https://miura-diving.com/lp/owd/ ・noindex）。
- **戦略・キーワード・広告文・計測手順の正は `.agents/google-ads-plan.md`**（deploy対象外）。
- 夏のLINE特典: 器材レンタル1日分(¥5,500)無料・8/31まで・LINE経由申込のみ → LP総額表示は¥59,400（通常¥64,900）。
- アクセス特典: 京急三崎口駅から開催地まで無料送迎あり（要予約）。広告・LPでは「送迎相談」ではなく「無料送迎」と明記する。
- 計測: LINE経由=合言葉「**夏割**」／フォーム経由=`?from=owd-lp`→通知メールに【流入元】行（contact/index.html+send_mail.php実装済み）。**8月末までLP URLは広告専用**（SNSに撒くと計測が混ざる）。
- Google Adsタグ `AW-669449671` とLINEクリックCV `YJYSCNDDp84cEMf7m78C` はLPへ実装済み。
- **2026-07-12 入稿・配信開始済み**: アカウント899-615-9101・キャンペーン「OWD夏2026_検索」（**総予算¥10,000**〈7/12に¥5,000から増額・ユーザー決定〉・7/11〜8/1・上限CPC¥250・広告審査通過・支払い設定済み）。開始直後のため表示0は正常（Google診断「問題なし」）。
- 2026-07-12監査で修正: 全53キーワードを部分一致→**フレーズ一致**に一括変更、除外キーワード20語（沖縄・伊豆・更新・求人 等）をキャンペーンレベルで登録。
- 残作業: サイトリンク4本の追加（推奨）・CVタグの初回発火確認・毎晩1分の検索語句チェック。実績数字をもらえたらヒーロー直下に信頼バー追加。
- 手動FTPSの認証情報は `~/.config/miura-deploy/netrc`（権限600・gitリポジトリ外・パスワードをユーザーに聞かなくてよい）。

## デプロイ方法

- **通常時**: mainへpushすると `.github/workflows/deploy.yml` がFTPで自動反映。
- **凍結中の手動デプロイ**: FTPS（ホスト `sv8718.xserver.jp`・メインFTPアカウント `yokosukatj`・パスワードは「サーバーパスワード」でユーザーが把握）。Python ftplib(FTP_TLS) でアップ・削除とも実績あり。アップ先は `/miura-diving.com/public_html/`。
- 反映前に必ずコンフリクトマーカー（`<<<<<<<`）を検索すること。
- 手動デプロイした変更も**必ずローカルでgitコミット**して本番とリポジトリの同期を保つこと。

## マスコットキャラ「クラゲ女子」

- 立ち絵: `image/kuragejyoshi.png`（クラゲ帽子+白猫耳+白ボブ+黒チョーカー+ミニタンク）。ナビ用アバター: `image/optimized/kurage-navi.webp`。
- **2026-07-10 ユーザー承認**: このキャラを「案内役」としてページ内イラスト・吹き出しに使用する。体験ダイビング・初心者ガイドに導入済み。
- ただし**ヘッダーのロゴ/ホームリンクには使わない**（従来ルール継続。ホームリンクはテキスト）。
- 相棒キャラ: 日本のアニメ調の若い男性インストラクター（黒髪・日焼け・ティール系ラッシュガード+ホイッスル）。

## 画像生成（fal API）

- APIキー: `~/.config/fal/key`（1行・gitリポジトリ外。公開・コミット厳禁）。
- キャライラストは **Nano Banana Pro** (`fal-ai/nano-banana-pro/edit`) に `kuragejyoshi.png` を参照画像として渡す。
- **絵柄は「日本のアニメ・漫画塗り」を強く指定**（thin lineart / anime cel shading / glossy anime eyes。western cartoon・Disney/Pixar風は明示的に禁止指定）。ユーザーはカートゥーン調をNGにしている。
- 3頭身（chibi 3-heads-tall）指定。画像内に文字を入れない（no text指定・日本語文字は乱れるため）。テキストはPILで後乗せ（フォント: ヒラギノ角ゴW7/丸ゴProN）。
- 加工: 4:3→800x600 / 16:9→900x502 のWebP(q82)にして `image/optimized/` へ。OGPは1200x630のJPG。

## 公式LINE配信（Messaging API・2026-07-18稼働）

- 配信CLI: **`.agents/line/line_broadcast.py`**（Python3標準ライブラリのみ・deploy対象外）。
- トークン: `~/.config/line/token`（長期チャネルアクセストークン・gitリポジトリ外。公開・コミット厳禁）。
- コマンド: `status`（配信数と無料枠確認）／ `broadcast --text "本文" --image <URL>`（全友だち配信。**--yes を付けるまでプレビューのみで送信されない**）／ `push --to <UserID>`（テスト送信）／ `roster`・`multicast`（絞り込み配信）。
- 画像は本サイトにコミット→mainマージで https://miura-diving.com/... のURLにしてから指定（https必須）。
- **無料枠は月200通・友だち約89人＝一斉配信は月2回まで**。配信前に必ず `status` で残数確認。
- **配信実行（--yes）は必ずユーザーの明示的な承認を得てから**。勝手に配信しない。
- **絞り込み配信**: `roster sync`→`roster exclude "名前"`→`multicast` で送る相手を選べる。multicastは送った人数ぶんしか無料枠を消費しない。
  ただし **2026-07-28 実測で `GET /v2/bot/followers/ids` は 403**（未認証アカウントのため友だち一覧が引けない）。
  認証済アカウント申請を通すまでは、管理画面のチャットから個別送信するか `roster add <userId>` で手動登録する。詳細は `.agents/buddy-campaign.md`。
- 名簿 `~/.config/line/roster.json` は表示名を含む個人情報。git外・600で保存。**コミット厳禁**。

## 実装済みコンポーネント

- `.navi-bubble`（クラゲちゃん吹き出し）: trial-diving と beginner-guide の各`<style>`内に同一CSSあり。アバター+吹き出し+左向き三角。新ページに使うときはコピーする。
- `.flow-illust`（trial-divingの流れカード画像）/ `.guide-illust`（beginner-guideの本文イラスト）。
- 画像は必ず `width`/`height` 属性・`loading="lazy"`・意味のあるalt（SEOキーワード自然に）を付ける。

## サイト共通ルール

- 旧諸磯住所・旧固定電話は本文・フッター・メール・構造化データのどこにも出さない（非公開）。所在地表現は「神奈川県三浦市」「開催場所・集合場所はご予約時にご案内します」。
- 開催地は城ヶ島・宮川湾。体験ダイビング正価は¥19,800（税込）。
- トップのヒーロー画像 `image/optimized/home-hero-soft-diving.webp` は差し替えない。
- 公開ページは静的HTML。現役PHPは `contact/send_mail.php` のみ（WPテーマ系phpは配信されない残骸）。
- LINEリンクは lin.ee/kK3d5p2 と lin.ee/Y3nB18U どちらも同一アカウントに着地するので混在OK。
