# 三浦 海の学校 — サイト作業ガイド（AIエージェント向け）

新しいチャット・Codex・他のAIツールで続きを作業するときは、まずこのファイルを読むこと。
（このファイルは deploy.yml で本番アップロード対象外。サーバーに上げないこと）

## 現在の状態（2026-07-10 更新）

- **GitHubアカウント(tjworld-nft)は凍結中**。解除申請済み・返信待ち。凍結中に新アカウントは絶対作らない（BAN回避扱いになる）。
- **本番 https://miura-diving.com/ はローカルブランチ `feature/site-polish` と同期済み**（FTPS手動デプロイで反映）。origin/main は古い（凍結でpush不可のため）。
- **凍結解除後にやること**: ① `git fetch` で回復確認 → ② `feature/site-polish` をpush → ③ PRを作りmainへマージ → ④ GitHub ActionsのFTP自動デプロイが走る（本番と同内容なので無害な再アップ）。以後は従来どおり「main push → 自動デプロイ」に戻る。

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
