# 事実の取り方と、変わらない事実

記事に書く数字は**その場で本番サイトから取得する**。このファイルに料金を書き写さない（古くなって嘘を書く事故になる）。

## 料金・特典は本番ページから取る

```bash
fetch() { curl -sL "$1" | python3 -c "
import sys,re,html
t=re.sub(r'<(script|style)[\s\S]*?</\1>','',sys.stdin.read())
print('\n'.join(l.strip() for l in html.unescape(re.sub(r'<[^>]+>','\n',t)).split('\n') if l.strip()))
"; }

fetch https://miura-diving.com/buddy/          # バディ割（紹介特典）
fetch https://miura-diving.com/license/        # ライセンス講習の料金
fetch https://miura-diving.com/fun-diving/     # ファンダイビング料金・ボート追加本数
fetch https://miura-diving.com/trial-diving/   # 体験ダイビング
fetch https://miura-diving.com/marine-activity/ # SUP・スノーケリング
fetch https://miura-diving.com/tokusho/        # 特商法表記（キャンセル規定の正）
```

料金の書き方は**総額表示**に合わせる。「講習費」と「レンタル込みの総額」を混同しない。
期限つきの特典（夏割など）は、記事公開時点で有効かを必ず確認する。

## 進行中のキャンペーンを知る

- サイトの `/buddy/`, `/lp/owd/` に掲載中のもの
- LINE配信の履歴: `.agents/line/line_broadcast.py status`
- 迷ったらユーザーに「いま推したい特典はありますか」と1問だけ聞く

## 海況・現場の出来事は Facebook から取る

Facebook ページ https://www.facebook.com/miuraumigaku/ の投稿が一次情報。
**通常のページ表示はフィードが読み込めないことがある**ので、Meta Business Suite の一覧を使う。

1. Chrome（ログイン済みセッション）で `https://business.facebook.com/latest/posts/published_posts` を開く
2. `[role="row"]` の innerText を集める。日付は `20XX年X月X日(曜) HH:MM` 形式で入っている
3. 前回記事の公開日以降の投稿を対象にする

```javascript
// javascript_tool で実行。スクロールしながら収集する
window.__posts = new Map();
window.__job = (async () => {
  const sleep = ms => new Promise(r => setTimeout(r, ms));
  const grab = () => {
    for (const r of document.querySelectorAll('[role="row"]')) {
      const t = (r.innerText || '').replace(/\n+/g, ' | ').trim();
      const m = t.match(/(20\d\d年\d+月\d+日\([日月火水木金土]\) \d+:\d+)/);
      if (!m) continue;
      const caption = t.split(' | 写真 | ')[0].split(' | リール | ')[0].split(' | ドロップダウン')[0];
      window.__posts.set(m[1], caption.slice(0, 500));
    }
  };
  grab();
  for (let i = 0; i < 25; i++) { window.scrollBy(0, 900); await sleep(800); grab(); }
  window.__jobDone = true;
})(); 'started'
```

30秒ほど待ってから `JSON.stringify([...window.__posts.entries()])` で取り出す。
「この投稿にはテキストがありません」やリール宣伝の行は捨てる。

**FBの投稿文に無い数値（水温○℃・透明度○m）を書いてはいけない。**「水温も暖かく快適」のような
記述しかなければ、記事も体感表現に留める。

## 変わらない事実

- 屋号: 三浦 海の学校（AquaBit LAB のマリン事業）。代表・吉田 哲士（PADIコースディレクター・指導歴20年以上）
- PADI公認5スターIDCセンター
- **固定店舗を持たない**。開催は神奈川県三浦市の**城ヶ島・宮川湾**。集合場所は予約時に案内
- 京急**三崎口駅**から開催地まで無料送迎（要予約）
- 少人数制。インストラクター1名に対し最大4名まで
- 公式LINE: https://lin.ee/Y3nB18U
- ブログURL: https://miura-diving.com/blog/{slug}/

## 書いてはいけないこと

- **旧諸磯の住所**（`神奈川県三浦市三崎町諸磯1621` / `三崎町諸磯1621` / `諸磯1621`）。Facebookの基本情報には
  まだ残っているが、サイト・ブログでは表示禁止
- 猫耳キャラ/ロゴを「お店のロゴ」として扱う書き方（案内役キャラとしての登場はOK）
- 他スクールの名前を出した比較・批判
- 「絶対安全」「100%」などの断言
- 実在しないお客様の声

## 挿絵に使うキャラクター

- 案内役「クラゲ女子」: `image/kuragejyoshi.png`（公開URL: https://miura-diving.com/image/kuragejyoshi.png ）
  クラゲ帽子・白ボブ・黒チョーカー・ミニタンク
- 相棒キャラ: 日本のアニメ調の若い男性インストラクター（黒髪・日焼け・ティール系ラッシュガード）
- 絵柄は**日本のアニメ・漫画塗り**。western cartoon / Disney / Pixar 調は明示的に禁止
- 画像内に文字を入れない（日本語が乱れる）
