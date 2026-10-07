# ドライスーツダイバーSPの事前学習 — 調査・運用記録

確認日: 2026-10-07 / Codex。教材と問題は当店の独自作成。公式教材本文、認証情報、顧客情報は保存しない。ユーザーの追加指示により、受講画面には参考資料一覧・参照リンクを置かず、この内部記録へ根拠を保持。ナイト側の参照リンク6章分と一覧も削除。

## 学習と管理

`aow-learning/dry.html` / `dry.css`。6章（suit / prepare / buoyancy / skills / problems / care）各5問、合計30問、解説・応用事例・給気体積の概念図・最終チェック。保護URLは `course.php?course=dry`。既存adminでプレビュー・登録コード・講座別進捗を管理。登録IDは共通、進捗・番号はAOW/night/dryで分離。DB版5、教材版1、DRY番号。学習記録は「事前学習完了（インストラクター確認待ち）」で、公式eLearning修了やPADI認定と区別。

## 根拠と編集方針

| 範囲 | 一次資料 | 反映内容 |
|---|---|---|
| 種類・保温・フィット | [PADI Dry Suits](https://www.padi.com/gear/dry-suits)、[PADI Buyer Guide](https://blog.padi.com/dry-suit-buyer-guide-for-scuba-divers/)、[DAN Diving Dry](https://dan.org/alert-diver/article/diving-dry/) | 防水と保温の役割、素材・インナー、動ける適合、シールの過度な圧迫を避ける |
| 点検・装着 | [DAN Extra Steps](https://dan.org/safety-prevention/diver-safety/case-summaries/drysuit-diving-requires-extra-steps/)、[Dive Rite Manual](https://diverite.com/wp-content/uploads/2024/10/901-Series-Drysuit-User-Manual.pdf) | シール・ファスナー・ホースの確認、接続と作動、装備脱着前にホースを切り離す |
| 空気・浮力 | [PADI Wetsuit vs Dry Suit](https://blog.padi.com/whats-the-difference-between-a-wetsuit-and-a-dry-suit/)、[Dive Rite FAQ](https://diverite.com/product-categories/drysuits/)、[DAN Buoyancy](https://dan.org/alert-diver/article/the-importance-of-buoyancy-control/) | 圧縮・膨張・給排気姿勢、両装置のガス把握、少量操作・変化を確認、適正ウエイト |
| 実習・水面 | [SANTI Manual 2024](https://santidiving.com/wp-content/uploads/2025/01/drysuit_manual24_EN.pdf)、[PADI Seven Reasons](https://blog.padi.com/7-reasons-you-need-a-dry-suit-certification/) | 限定水域の練習、水面BCD、潜降・浮上・停止、装備やインナー変更時の確認 |
| 異常・姿勢回復 | [DUI Current Manual](https://pdf.divedui.com/DUI_Manuals-Current/DUI-Drysuit_Manual-English.pdf)、[PADI Six Things](https://blog.padi.com/6-things-you-dont-know-about-dry-suit-diving/)、[DAN Five Lessons](https://dan.org/alert-diver/article/five-lessons-for-cold-water-diving/) | 過給気時の練習済みホース切離し・排気・制御終了、丸まり姿勢回復の概念、独習しない |
| 浸水・寒さ | [DAN Avoid the Chill](https://dan.org/safety-prevention/diver-safety/divers-blog/avoid-the-chill/)、[DAN Ice Diving 2025](https://dan.org/alert-diver/article/ice-diving-2/)、DAN Diving Dry | 保持ガス・断熱・動きへの影響を区別、早期終了、乾いた保温・支援、氷下活動はSPへ取り込まない |
| 手入れ | [Aqualung Care](https://us.aqualung.com/pages/care-and-maintenance-drysuits)、[DAN Tools](https://dan.org/alert-diver/article/tools-for-a-healthy-drysuit/)、SANTI Manual | 真水・内外乾燥・熱回避、材質に合う用品と保管、異常記録・サービス確認 |

Mac内PADI Instructor Manual 2026日本語版（79173J Rev.12/25）のSP一覧（PDF35）、一般規準ドライ・オリエンテーション（PDF20–21）、Dry Suit Adventure Dive（PDF92–93）を確認。ドライSPの最少年齢10、(Junior) OWD、限定水域1とOW2。ADの達成条件をSP全2ダイブへ流用しない。対応ADのクレジットは認定名だけで判断せず、知識・実技修了記録と現行規準を担当者が確認する。PDF原本・画面はリポジトリへ追加していない。

## 注意する区別と未確認

提供されたPADI日本語eLearning本文では、レクリエーションのシェルはスーツを水中浮力の主調整に使う方法、発泡ネオプレンはスクイズ防止量をスーツへ入れ、素材の圧縮による浮力低下をBCDで補う方法を説明している。クラッシュド・ネオプレンなどは素材名だけで手順を決めない。テクニカルは別の装備・訓練条件。これを教材に具体的に補足した一方、メーカーDive RiteなどはBCDを主に調整する指定があるため、全機種共通の強制手順にしない。使用器材マニュアルと担当講師が確定した方法に合わせ、必要量・両装置の空気・水面BCDを確認する。古いDAN 2009記事を現行PADI SP規準の証明には用いない。

浸水は水の重さで必ず水中で沈むと説明せず、断熱・保持ガス・浮力・動きを考えて終了。陸上では重量増加に配慮。重り投棄は過剰浮力への対処にせず、水面などの習得済み緊急手段と区別。呼吸ガスの停止や水中バルブ分解へ誘導しない。姿勢回復・ホース切離しなどは実技練習を監督下に限定する。

現行Dry Suit Specialty Instructor Guide、必要公式教材・Knowledge Review、SP各実習の全達成条件への完全照合は未完了。教材完了を正式知識開発の自動認定に使わない。

## 確認

隔離DB49項目合格。独立レビューでAOW41/night30/dry30の全101問採点キー一致、必須DOM・設問ID・ラジオ構造・講座権限と保存分離を確認。指摘の「ウエイトが重いと必ずスーツ内空気が増える」と読める解説を修正。ブラウザーで権限拒否→追加コード→誤答→保存再読込→30問完了→DRY記録と復元、AOW現行5章/旧3章・night番号維持を確認。375/430/768/1024/1440pxで横はみ出しなし。

Claude Codeへ公開予定コードのみ読み取りレビュー依頼、OAuth期限切れでis_error=true。認証設定は変更せず、Codex独立調査とレビューを受領。これはPADI監修承認ではない。


## 提供された公式日本語eLearningとの本文照合（2026-10-07）

ユーザーがChromeで開いたPADI Dry Suit Diver日本語プレーヤーを指定。既存タブのUIで導入と全11トピックの本文（限定水域1・海洋2の実習概要を含む）を確認。アプリ内ブラウザーではブロックされたがChromeで閲覧できた。2023年版教材パッケージで、2026年現行専用Instructor Guideへの完全照合とは区別する。動画再生、問題回答・送信、認定・完了ボタンの操作は行っていない。通常の閲覧で学習率表示が進むことを確認。個人アカウント・認証付きURL、公式本文・問題・画像を保存・転載しない。

独自6章30問と採点キーを維持し、熱損失の個人差、保温スーツ・素材・インナー選択、ネック圧迫、フィンの適合、素材別のBCD/スーツの役割、アルゴンを呼吸しないこと、重量配分と誤脱落、排気不能・浮上抵抗・水面での逆転姿勢、正常なホース再接続練習と故障終了、運搬・年次専門点検・素材別修理と実習の見通しを独自文で補足した。

本文のウエイト確認には終盤30barで5m停止を維持する目安があるが、30barをダイビングの終了目標残圧に使わない。ガス計画は消費量・帰路・浮上・停止・バディ支援から別途決める。給気ホースの再接続は正常系の監督下の実習として扱い、給気バルブ故障を再接続して潜水を再開する説明にしない。緊急シール排気は浸水を伴う習得済みの方法、フレアリングは呼吸を続けて浮上抵抗を増す考え方として説明し、読んだだけで実施できるとしない。バルブ分解・大きな破損・ファスナーや接着シール交換は専門業者へ。具体的な溶剤や接着剤配合、応急テープだけで潜る手順は載せない。

補足後の検証: 隔離DB49項目合格。各30問のfieldset（本文・選択肢・解説・順序）は補足前と完全一致し、保存用DOM・採点条件・教材版1を維持。新しい素材・給気ガス・修理の3説明を展開し、375/430/768/1024/1440pxで横はみ出しなし、外部参照リンクなし。独立レビュー後、発泡ネオプレンの区別と、排気を試みても浮上が速くなる場合のフレアリングを補助手段とする表現に明確化した。
