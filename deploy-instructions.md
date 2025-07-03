# 本番サイト更新手順

## 現在の問題
- ローカルファイルとGitHubには修正済みのindex.htmlがあります
- しかし本番サイト https://miura-diving.com/ には反映されていません
- 書籍スライダーを動作させるために以下の手順が必要です

## 必要な作業

### 1. 本番サイトにファイルをアップロード
以下のファイルを本番サーバーにアップロードしてください：

#### 更新が必要なファイル:
- `index.html` (jQueryと書籍スライダーのスクリプトタグを追加済み)
- `assets/js/books-slider.js` (新規ファイル)

#### ファイルの場所:
- ローカル: `/Users/tetsuji/Downloads/開発wind/umigaku-web/`
- GitHub: https://github.com/tjworld-nft/umigakuweb

### 2. 追加されたコード

#### index.htmlの変更点 (808行目付近):
```html
<script src="assets/js/home-test.js"></script>

<!-- jQuery Library for Books Slider -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Books Slider Script -->
<script src="assets/js/books-slider.js"></script>
```

#### 新規ファイル: assets/js/books-slider.js
- jQuery based books slider implementation
- 自動再生、手動ナビゲーション、レスポンシブ対応

### 3. 確認方法
アップロード後、以下を確認してください：
1. https://miura-diving.com/assets/js/books-slider.js にアクセスできる
2. ブラウザの開発者ツールでJavaScriptエラーがない
3. 書籍セクションでスライダーが動作する

## サーバー管理者への依頼事項
1. 上記ファイルを本番環境にデプロイ
2. CDNキャッシュがある場合はクリア
3. ブラウザキャッシュのクリア

## 緊急対応用
もし上記でうまくいかない場合は、index.htmlの書籍スライダー部分のHTMLを確認し、JavaScriptの初期化が正常に動作しているかブラウザコンソールでチェックしてください。