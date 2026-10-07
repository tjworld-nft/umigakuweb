# ナイトダイバーSP事前学習 — 調査・運用記録

確認日: 2026-10-07 / Codex。公開予定コードと独自教材のみ。認証情報・生徒情報・公式教材本文は保存しない。

## 実装と運用

既存 `aow-learning/admin.php` へナイトSPのプレビュー・登録コード選択・講座別進捗を追加。同じ匿名IDでAOWとnightを別々に学ぶ。保護URLは `course.php?course=night`。6章30問、各問の即時解説、サーバー保存・再開、全問正解＋最終チェックで学習記録を発行する。2026-10-07の追加指示で、受講画面の章別参照リンクと参考資料一覧は削除。根拠は本記録だけに保持。PADI公式設問の複製ではなく当店オリジナル。

学習記録は「事前学習完了（インストラクター確認待ち）」のみ。公式eLearningの修了証やPADI認定証ではない。正式知識開発・ナレッジリビュー・実技の評価は担当インストラクターの確認が必要。正式学科を自動認定する機能はない。

## 一次資料と対応

| 章 | 主な一次資料 | 確認・編集方針 |
|---|---|---|
| 計画 | [DAN Night Diving](https://dan.org/alert-diver/article/night-diving/)、[DAN Safe Diving Practices](https://world.dan.org/safety-prevention/diver-safety/safe-diving-practices/) | 昼の下見、夜間出入り、海況、出口・灯火故障の代替、深度時間、ガス・帰路・停止の余裕、支援とEAP |
| 装備 | [DAN Gear Maintenance](https://world.dan.org/safety-prevention/diver-safety/divers-blog/gear-maintenance-protect-your-investment-and-prevent-dive-accidents/)、[DAN Diving in the Dark](https://dan.org/alert-diver/article/diving-in-the-dark/)、[PADI Giant Stride](https://blog.padi.com/how-to-giant-stride/) | 各自主/予備、位置マーカーは補助、密閉・電源・操作・収納、計器・水面器材、入水域を照らして確認 |
| 合図 | [PADI Night Diving Tips](https://blog.padi.com/night-diving-tips/)、DAN Diving in the Dark | 円/横振りの一般的意味、手を照らす、返答、顔へ直射しない、バディ本人と距離、水面合図は事前合意 |
| ナビ | DAN Night Diving、[PADI Ascend and Descend Safely](https://blog.padi.com/how-to-ascend-and-descend-safely-in-scuba-diving/) | 基準線・深度/浮力/バディ、往復方位・距離・流れによるずれ、帰路照合、浮上速度・停止・頭上 |
| 対応 | [DAN Low-Visibility Diving](https://dan.org/alert-diver/article/low-visibility-diving/)、[DAN Buddy Separation Case](https://dan.org/safety-prevention/diver-safety/case-summaries/buddy-separation-during-scientific-diver-training/) | 事前に離別手順を合わせ、約1分以内の短い探索→安全浮上・再集合、必要な減圧義務の維持、早期終了、習得済み緊急スキル |
| 観察 | [PADI Pros / Green Fins](https://pros-blog.padi.com/how-to-manage-customers-with-cameras/)、[AWARE 10 Tips](https://pros-blog.padi.com/wp-content/uploads/2018/10/PA_10Tips_Poster.pdf)、[PADI Light With Care](https://blog.padi.com/minimize-impact-underwater-as-a-scuba-diver/) | 休む魚を起こさない、照射・距離、触らない/餌をやらない/追わない、浮力と器材、勝手に消灯しない、帰還確認と整備 |

[公式Night Diver概要](https://www.padi.com/courses/night-diver)、[PADI日本語FAQ](https://www.padi.com/ja/help)も確認。公式Night Diver eLearningの日本語対応を確認。公式eLearningとは別のページであることを冒頭と完了記録に表示。

Mac内のPADI Instructor Manual 2026日本語版（Product 79173J、Rev.12/25）のコース概要と一般規準、Night Adventure Diveのページを抽出・目視確認。最低年齢12歳、(Junior) OWD、Night SPは3ダイブ。Night ADでは各自ライト、合図、計器、バディ接触、基準を使った潜降/浮上とナビゲーションが扱われる。ただしADの達成条件をSP全3ダイブへ流用しない。PDF原本・画面はリポジトリへ追加していない。

AOW→SPクレジットは認定名だけでは判断しない。対応するナイトADの知識・実技修了記録を担当者が確認して判断する。[PADI公式AOW Training Record](https://pro-cms.padi.com/sites/default/files/documents/training-hub/660DT_Advanced_Open_Water_Training_Record_v102.pdf)と[公式コースリンク解説](https://blog.padi.com/advanced-open-water-diver/)を確認。

## 未確認・正式運用前の照合

専用の現行Night Diver Specialty Instructor GuideはMac内で未発見。公開検索では旧版や転載サイトのみで、現行公式ガイドとしては採用していない。各ダイブの全達成条件、必要教材、Knowledge Reviewの扱い、SP固有の深度/監督等の完全照合は未完了。ユーザーに保存場所を質問済み。

主ライト故障時に予備で帰還を始め、観察を継続しない説明を、提供された公式日本語eLearning本文でも確認した（下記）。当店の設問と終了方針は変更しない。専用の現行SPガイドの必須規準を照合済みとは扱わない。離別は当日のブリーフィングと習得済みスキルを優先する。

## 検証

隔離DB21項目: 新規/既存DB移行、AOW旧版1/現行2の番号と進捗保全、nightの採点と偽完了拒否、講座間保存分離、NIGHT番号の再保存維持、コードの1回使用。ブラウザー18項目: 権限/CSRF、登録→誤答→保存再読込→30問完了→記録復元、旧AOW3章、新規night単独登録、管理プレビュー無保存。375/430/768/1024/1440pxでページの横はみ出しなし、JSエラーなし。教材・PHP正答キー30件一致。独立レビューの入水域確認の追記を反映。

Claude Code読み取りレビューは既存OAuthが期限切れで受領不可（設定は変更していない）。Codex独立担当2名の調査とレビューを受領。これはPADIの監修承認ではない。

## 同日のドライSP追加

同じPRでdryを追加し、DB版5・共有JS講座設定へ更新。AOW/night/dryの記録は分離。詳細は `dry-diver-learning.md`。回帰DB検証は49項目へ拡張し合格。


## 提供された公式日本語eLearningとの本文照合（2026-10-07）

ユーザーがChromeで開いているPADI Night Diver日本語プレーヤーを指定。既存のユーザータブをブラウザーUIで読み、全11トピックの本文とコース3ダイブの概要を確認した。アプリ内ブラウザーではブロックされたが、Chromeでは本文を閲覧できた。プレーヤーは2022年版の教材パッケージ。現行2026年専用Instructor Guideの代わりとしては扱わない。動画は再生せず、練習問題の回答・送信、認定・完了ボタンの操作は行っていない。通常のページ閲覧で学習率表示が変化することを確認。個人情報・認証付きの完全URL、公式本文、問題、画像は保存・転載していない。

6章30問の構成と採点を維持し、当店の独自の説明へ次を補足した。

- 計画: 夜のレクリエーションでオーバーヘッドへ進入しないこと、絡まり・うねり、明るいうちの準備、反復潜水・疲労・保温と支援を判断に含める。
- 装備: 光源の比較、電池の混在を避ける、充電・誤点灯・水冷専用機種の扱い、マーカーの用途、入退出の手順と近隣への配慮。
- 合図: 水面のOKと援助要請の具体例、現地チーム・クルーとの事前合意。
- ナビ: 地形・深度・水の動き・光の複数の手掛かりと方位の記録、出口を示す2つの固定目印の考え方。
- 対応: 離別時の短い光の遮蔽と全方向探索、全光源故障時の手掛かりと安全な終了、浸水ライトの電池・内圧を専門家へ報告する理由。
- 観察: 海・淡水の生態例と行動、光に集まる生物の現地確認、3実習の見通しを自習の実技達成と区別する。3回目のフリー潜降でもラインや傾斜した水底を視覚的な基準にする。

公式本文の8m帰還範囲、50barを残した帰還、3分の消灯静止などは実習の文脈で確認した。数値を全サイトの帰還残圧、単独消灯の許可、現行の全達成条件の証明に転用しない。浮上速度も当日の計画・コンピューター・講師指示のうち保守的な条件を守る説明を維持する。水没ライトの自力分解、種を問わない酢の使用、呼吸ガスのパージによる生物排除は、メーカーや現地の安全条件を欠く一般手順として追加しない。

補足後の検証: 隔離DB49項目合格。各30問のfieldset（本文・選択肢・解説・順序）は補足前と完全一致し、保存用DOM・採点条件・教材版1を維持。新しい8個の説明を展開し、375/430/768/1024/1440pxで横はみ出しなし、外部参照リンクなし。独立レビュー後、浸水ライトは外観に異常がなくても自己判断で開けない表現に明確化した。
