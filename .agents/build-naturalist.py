# -*- coding: utf-8 -*-
"""ナチュラリストSP 事前学習のページ（aow-learning/naturalist.html）を組み立てる。

使い方（リポジトリ直下で）:
    python3 .agents/build-naturalist.py

- 本文・問題の正は .agents/naturalist-learning-content.json。
- 正答は aow-learning/lib.php の course_answer_key('naturalist') と一致しないと止まる。
- 写真は ../image/（サイトの図鑑・下層ページの写真）と assets/naturalist/（この教材用に縮めた写真）。
  img の width/height は実際のファイルから読む。
- 章立て・保存・進捗表示は DPV と同じ「ガイド付き」の型（app.js の guidedStudy）。
  見た目の土台は dpv.css を共用し、naturalist.css で色と図を足す。
"""
import html
import json
import re
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parent.parent
APP = ROOT / 'aow-learning'
content = json.loads((ROOT / '.agents/naturalist-learning-content.json').read_text())
mods = content['modules']
esc = html.escape
TOTAL = len(mods)
QCOUNT = sum(len(m['questions']) for m in mods)


def check_answer_key():
    src = (APP / 'lib.php').read_text()
    block = re.search(r"if \(\$slug === 'naturalist'\) \{\s*return \[(.*?)\];\s*\}", src, re.S)
    assert block, 'lib.php に naturalist の正答キーがない'
    key = {}
    for mod, body in re.findall(r"'(\w+)' => \[(.*?)\]", block.group(1)):
        key[mod] = dict(re.findall(r"'(\w+)'=>'([abc])'", body))
    expected = {m['id']: {f"{m['id']}{i + 1}": q['answer'] for i, q in enumerate(m['questions'])} for m in mods}
    assert key == expected, ('lib.php の正答キーが本文と合わない', key, expected)


def size(src):
    path = (APP / src).resolve()
    with Image.open(path) as im:
        return im.size


def img(src, alt, eager=False, cls=''):
    w, h = size(src)
    c = f' class="{cls}"' if cls else ''
    return (f'<img{c} src="{src}" alt="{esc(alt)}" width="{w}" height="{h}" '
            f'loading="{"eager" if eager else "lazy"}" decoding="async">')


def fig(src, alt, caption, cls='nat-photo'):
    return f'<figure class="{cls}">{img(src, alt)}<figcaption>{caption}</figcaption></figure>'


F = '../image/fish/web/'
SUB = '../image/sub/'
A = 'assets/naturalist/'

# ---------- 図と写真（章ごと） ----------
# 'top' は「この章で分かること」の直後、数字は本文の何番目の節の後ろか（0始まり）。
visuals = {
    'ecosystem': {
        'top': (
            '<figure class="nat-wide">' + img(SUB + 'fish-school-960.webp', '三浦の海の根のまわりを群れて泳ぐ魚たち') +
            '<figcaption><b>ひとつの根にも、たくさんの「住所」と「職業」。</b>岩・海藻・砂・水の中層。それぞれの場所に、それぞれの暮らし方の生き物がいます。（三浦の海）</figcaption></figure>'
        ),
        0: (
            '<aside class="nat-board"><p class="section-number">見る範囲で、生態系は変わる</p><h3>生態系の中に、生態系。</h3>'
            '<div class="nat-nest" role="img" aria-label="地球の中に海、海の中に三浦の岩場、岩場の中に岩のすき間、という入れ子の図">'
            '<div><span>地球</span><div><span>海</span><div><span>三浦の岩場</span><div><span>岩のすき間</span><p>小さなエビやカニ、ゴカイの暮らす場所</p></div></div></div></div></div>'
            '<p class="nat-board-note">どこまでを「ひとまとまり」として見るかで、生態系の大きさは自由に決められます。</p></aside>'
        ),
        1: (
            '<figure class="nat-wide">' + img(A + 'anemone-field.webp', '砂地に広がるイソギンチャクの群れと、そのまわりを泳ぐ青い小魚たち') +
            '<figcaption><b>これも、ひとつの生物群集。</b>イソギンチャクの群れと、そのまわりで暮らす小魚たち。見えていない小さなエビやカニも、ここに住んでいます。（三浦の海）</figcaption></figure>'
            '<div class="nat-pair">'
            + fig(F + 'otohimeebi.webp', '岩のすき間にいる、赤と白のしま模様のオトヒメエビ',
                  '<b>住所：</b>岩のすき間やオーバーハングの下<br><b>職業：</b>魚の体の寄生虫を食べる「掃除屋」<span>オトヒメエビ（三浦の海）</span>')
            + fig(F + 'kaeruankou.webp', '岩の上でじっとしているカエルアンコウ',
                  '<b>住所：</b>岩の上・海藻のそば<br><b>職業：</b>疑似餌で小魚をおびき寄せる「待ち伏せの狩人」<span>カエルアンコウ（三浦の海）</span>')
            + '</div>'
        ),
        3: (
            '<figure class="nat-wide">' + img(A + 'kelp-school.webp', 'カジメの林の上を群れて泳ぐ小魚たち') +
            '<figcaption><b>海藻が景色をつくる、数少ない場所。</b>カジメの林の上を、小魚の群れが通っていきます。（三浦の海）</figcaption></figure>'
        ),
        2: (
            '<aside class="nat-board"><p class="section-number">色が消える順番</p><h3>深くなると、赤から消える。</h3>'
            '<div class="nat-colors" role="img" aria-label="浅い所では赤・オレンジ・黄・緑・青が見えるが、深くなるにつれて赤、オレンジ、黄の順に見えにくくなり、深い所では緑と青が残る図">'
            '<div><span>浅い</span><i class="c-r"></i><i class="c-o"></i><i class="c-y"></i><i class="c-g"></i><i class="c-b"></i></div>'
            '<div><span></span><i class="c-r off"></i><i class="c-o"></i><i class="c-y"></i><i class="c-g"></i><i class="c-b"></i></div>'
            '<div><span></span><i class="c-r off"></i><i class="c-o off"></i><i class="c-y"></i><i class="c-g"></i><i class="c-b"></i></div>'
            '<div><span>深い</span><i class="c-r off"></i><i class="c-o off"></i><i class="c-y off"></i><i class="c-g"></i><i class="c-b"></i></div>'
            '</div><p class="nat-board-note">何メートルで消えるかは、水の澄み方や光の強さで変わります。ライトを当てると、消えた赤がよみがえります。</p></aside>'
            '<div class="nat-compare" role="list">'
            '<div role="listitem"><b>約800倍</b><span>水の密度は空気の約800倍。一生浮いて暮らすプランクトンがいる。</span></div>'
            '<div role="listitem"><b>上の層だけ</b><span>光は水に吸収され、植物が育つのは明るい上の層だけ。</span></div>'
            '<div role="listitem"><b>浮力</b><span>重力がほぼ打ち消され、骨がなくても大きくなれる。</span></div>'
            '<div role="listitem"><b>酸素はまだら</b><span>波打ちぎわは多く、よどんだ泥の底は少ない。</span></div>'
            '</div>'
        ),
    },
    'relations': {
        0: (
            '<aside class="nat-board"><p class="section-number">食物連鎖のくらべっこ</p><h3>海の連鎖は、長い。</h3>'
            '<div class="nat-chain"><p class="nat-chain__label">陸の例（3段）</p><ol>'
            '<li>草</li><li>草を食べる動物</li><li>肉食の動物</li></ol></div>'
            '<div class="nat-chain nat-chain--sea"><p class="nat-chain__label">海の例（5段）</p><ol>'
            '<li>植物プランクトン</li><li>カイアシ類</li><li>イワシなどの小魚</li><li>イカ</li><li>サメ</li></ol></div>'
            '<p class="nat-board-note">矢印は「食べられる → 食べる」。実際は、いくつもの連鎖がからみ合った網（食物網）になっています。</p></aside>'
        ),
        1: (
            '<figure class="nat-wide">' + img(A + 'predation.webp', 'カジメの林の下で、オレンジ色の魚が小魚を頭から丸のみにしている。口から小魚の尾が出ている') +
            '<figcaption><b>食べる、食べられる。その一瞬。</b>カジメの林の下で、小魚を丸のみにした魚。口から獲物の尾が出ています。（三浦の海）</figcaption></figure>'
            '<div class="nat-gallery">'
            + fig(F + 'minokasago.webp', '大きなヒレを広げて泳ぐミノカサゴ', '<b>ミノカサゴ</b>ヒレで小魚を追い込む')
            + fig(F + 'kaeruankou.webp', '岩の上のカエルアンコウ', '<b>カエルアンコウ</b>疑似餌で待ち伏せ')
            + fig(F + 'tatsunootoshigo.webp', '海藻に尾を巻き付けたタツノオトシゴ', '<b>タツノオトシゴ</b>管のような口で吸い込む')
            + fig(F + 'bidai.webp', '岩のそばを泳ぐブダイ', '<b>ブダイ</b>海藻を削って食べる')
            + '</div><p class="nat-photo-note">写真はどれも三浦の海で撮ったものです。</p>'
        ),
        2: (
            '<aside class="nat-board nat-board--sym"><p class="section-number">共生を記号で見る</p><h3>どちらが、得をする？</h3><p class="nat-sym__legend">＋ 得をする　0 変わらない　− 害がある</p><div class="nat-sym">'
            '<div><p class="nat-sym__type">相利共生</p><p class="nat-sym__mark" aria-label="どちらも得">＋ / ＋</p><p>クマノミとイソギンチャク。掃除屋と、掃除してもらう魚。</p></div>'
            '<div><p class="nat-sym__type">片利共生</p><p class="nat-sym__mark" aria-label="一方が得、もう一方は変わらない">＋ / 0</p><p>大きな魚やサメにくっついて移動する、コバンザメ。</p></div>'
            '<div><p class="nat-sym__type">寄生</p><p class="nat-sym__mark" aria-label="一方が得、もう一方に害">＋ / −</p><p>魚のエラや口につく、寄生するカイアシ類やウオノエのなかま。</p></div>'
            '</div></aside>'
        ),
        3: (
            '<aside class="nat-board"><p class="section-number">三浦で見つけたら、考えてみよう</p><h3>いつも一緒。でも、どの関係？</h3><div class="nat-gallery">'
            + fig(A + 'anemone-shrimp-crab.webp', 'イソギンチャクの触手の間にいる、白と赤の小さなエビと、小さなカニ', '<b>イソギンチャクの中のエビとカニ</b>触手の間を住まいにしています')
            + fig(A + 'damsel-anemone.webp', 'イソギンチャクの触手の中にいる、白い点のある黒い小さな魚', '<b>イソギンチャクの中の小魚</b>クマノミのように触手を隠れ家に')
            + fig(A + 'hermit-anemone.webp', '貝殻の上にイソギンチャクをのせて砂の上を歩くヤドカリ', '<b>イソギンチャクをのせたヤドカリ</b>刺す触手がヤドカリを守る、と考えられています')
            + fig(A + 'urchin-crab.webp', 'ラッパウニと思われるウニの、花のような部分の間にいる小さなカニ', '<b>ウニの上のカニ</b>トゲの間に隠れて暮らすカニ')
            + '</div><p class="nat-board-note">どれも「いつも一緒にいる」2種です。得か害かは、写真だけでは決められません。見えた事実を書いて、浮上後に調べるのがナチュラリストの見方です。（三浦の海）</p></aside>'
        ),
    },
    'names': {
        1: (
            '<aside class="nat-board"><p class="section-number">7つの段を、1匹でたどる</p><h3>アオウミウシの住所録。</h3><div class="nat-rank">'
            '<figure>' + img(F + 'ao-umiushi.webp', '岩の上をはうアオウミウシ。青い体に黄色の縁取り') + '<figcaption>アオウミウシ（三浦の海）</figcaption></figure>'
            '<ol>'
            '<li><b>界</b><span>動物界</span></li>'
            '<li><b>門</b><span>軟体動物門</span></li>'
            '<li><b>綱</b><span>腹足綱（巻貝のなかま）</span></li>'
            '<li><b>目</b><span>裸鰓目（ウミウシのなかま）</span></li>'
            '<li><b>科</b><span>イロウミウシ科</span></li>'
            '<li><b>属</b><span>アオウミウシ属 <i>Hypselodoris</i></span></li>'
            '<li><b>種</b><span>アオウミウシ <i>Hypselodoris festiva</i></span></li>'
            '</ol></div><p class="nat-board-note">下へ行くほど、なかまのまとまりが小さくなります。学名は、いちばん下の2段（属と種）を並べたものです。</p></aside>'
        ),
        0: (
            '<aside class="nat-board"><p class="section-number">一つの魚に、いくつもの名前</p><h3>ブリは、地方と大きさで名前が変わる。</h3>'
            '<div class="nat-names"><p><b>関東</b><span>ワカシ</span><span>イナダ</span><span>ワラサ</span><span>ブリ</span></p>'
            '<p><b>関西</b><span>ツバス</span><span>ハマチ</span><span>メジロ</span><span>ブリ</span></p>'
            '<p><b>学名</b><span class="nat-names__sci"><i>Seriola quinqueradiata</i>（世界中で一つ）</span></p></div>'
            '<p class="nat-board-note">英語の dolphin が、イルカと魚のシイラの両方を指すこともあります。だから学名が役に立ちます。</p></aside>'
        ),
    },
    'invertebrates': {
        0: (
            '<div class="nat-gallery">'
            '<figure class="nat-photo nat-photo--plankton"><div class="nat-scope" role="img" aria-label="顕微鏡で見た植物プランクトンのイメージ図。丸や棒のような小さな細胞が並ぶ">'
            '<svg viewBox="0 0 200 200" aria-hidden="true"><circle cx="100" cy="100" r="96" fill="#e3f1e6"/>'
            '<g fill="none" stroke="#3e7b5a" stroke-width="3"><circle cx="70" cy="70" r="18"/><circle cx="70" cy="70" r="8"/>'
            '<rect x="108" y="40" width="54" height="18" rx="9"/><rect x="118" y="48" width="34" height="2" rx="1"/>'
            '<path d="M40 120l30 30M52 116l30 30"/><circle cx="130" cy="120" r="22"/><path d="M118 108l24 24M142 108l-24 24"/>'
            '<rect x="80" y="152" width="40" height="14" rx="7"/></g></svg></div>'
            '<figcaption><b>植物プランクトン</b>小さすぎて目では見えません（イメージ図）</figcaption></figure>'
            + fig(A + 'kelp-nudibranch.webp', '茶色い大きな海藻の葉と、その下の岩をはうアオウミウシ', '<b>褐藻</b>カジメのなかまの葉（下はアオウミウシ）')
            + fig(A + 'green-algae.webp', '緑色の海藻と、そのまわりにつく小さな生き物', '<b>緑藻</b>緑色の海藻のなかま')
            + '<figure class="nat-photo nat-photo--text"><div class="nat-textcard" aria-hidden="true">草</div><figcaption><b>海草</b>アマモなど。花を咲かせ、魚のゆりかごに</figcaption></figure>'
            + '</div><p class="nat-photo-note">写真は三浦の海で撮ったものです。海藻は、紅藻・褐藻・緑藻の3つに分けられます。</p>'
        ),
        2: '{PHYLA}',
    },
    'vertebrates': {
        0: (
            '<div class="nat-pair">'
            + fig(F + 'nekozame.webp', '岩のそばで休むネコザメ', '<b>ネコザメ</b>ドリルのような形の卵を産むサメ<span>三浦の海</span>')
            + fig(F + 'akaei.webp', '砂の上にいるアカエイ', '<b>アカエイ</b>子どもを産むエイ。尾に毒のトゲ<span>三浦の海</span>')
            + '</div>'
        ),
        1: (
            '<div class="nat-gallery">'
            + fig(F + 'oniokoze.webp', '砂と岩にまぎれるオニオコゼ', '<b>まぎれる</b>オニオコゼ')
            + fig(F + 'houbou.webp', '青い胸びれを広げたホウボウ', '<b>歩く</b>ホウボウ（胸びれの下の「あし」）')
            + fig(F + 'kawahagi.webp', '小さな口のカワハギ', '<b>つまむ</b>カワハギのおちょぼ口')
            + fig(F + 'suzumedai.webp', '群れで泳ぐスズメダイ', '<b>群れる</b>スズメダイ')
            + '</div>'
        ),
        3: (
            '<div class="nat-table-wrap"><table class="nat-table"><caption>アザラシ・アシカ・セイウチの見分け方</caption>'
            '<thead><tr><th scope="col"></th><th scope="col">耳たぶ</th><th scope="col">後ろあし</th></tr></thead><tbody>'
            '<tr><th scope="row">アザラシ</th><td>なし</td><td>前に曲げられない</td></tr>'
            '<tr><th scope="row">アシカ</th><td>あり</td><td>前に向けて歩ける</td></tr>'
            '<tr><th scope="row">セイウチ</th><td>なし</td><td>前に向けられる</td></tr>'
            '</tbody></table></div>'
        ),
        4: (
            '<aside class="nat-mission"><p class="section-number">1本目のダイブで探すもの</p><h3>「2・4・5」を見つけよう。</h3>'
            '<div class="nat-mission__grid"><div><b>2</b><span>植物</span><small>海藻・海草</small></div>'
            '<div><b>4</b><span>無脊椎動物</span><small>カイメン・イソギンチャク・ウミウシ・エビ・ウニなど</small></div>'
            '<div><b>5</b><span>脊椎動物</span><small>魚・エイ・サメなど</small></div></div>'
            '<p class="nat-board-note">どれも「〜種類以上」。数を競うのではなく、残圧・深さ・バディを優先して、落ち着いて探します。</p></aside>'
            '<div class="nat-pair">'
            + fig(F + 'hirame.webp', '砂に体をうめるヒラメ', '<b>ヒラメ</b>硬骨魚。ふつうの尾びれと、左側に並ぶ両目<span>三浦の海</span>')
            + fig(A + 'ray-sand.webp', '砂の上のエイを上から見た写真。むちのような細長い尾がのびている', '<b>エイ</b>軟骨魚。むちのような細長い尾<span>三浦の海</span>')
            + '</div>'
        ),
    },
    'myths': {
        1: (
            '<aside class="nat-board"><p class="section-number">行動を見分ける</p><h3>攻撃的な行動と、防御的な行動。</h3><div class="nat-two">'
            '<div><p class="nat-two__type">攻撃的な行動</p><b>生き物の側から始める</b><p>たいていは餌をとるため。人が餌のように見えてしまったときに起きます。</p></div>'
            '<div><p class="nat-two__type">防御的な行動</p><b>身を守る反応</b><p>自分・仲間・子ども・なわばりが危ないと感じたとき。海の「攻撃」のほとんどはこちら。</p></div>'
            '</div></aside>'
            '<div class="nat-gallery">'
            + fig(F + 'minokasago.webp', 'ヒレを広げたミノカサゴ', '<b>毒のトゲ</b>ミノカサゴ')
            + fig(F + 'oniokoze.webp', '砂にまぎれるオニオコゼ', '<b>毒のトゲ</b>オニオコゼ（踏まないように）')
            + fig(F + 'utsubo.webp', '岩の穴から顔を出すウツボ', '<b>かむ</b>ウツボ（穴に手を入れない）')
            + fig(F + 'yuureikurage.webp', '長い触手をのばして漂うユウレイクラゲ', '<b>刺す</b>ユウレイクラゲの触手')
            + '</div><p class="nat-photo-note">どれも、こちらが近づきすぎたり、ふれたりしたときに身を守る道具です。（三浦の海）</p>'
        ),
        3: (
            '<div class="nat-myths" role="list">'
            '<div role="listitem"><span>01</span><b>危険で悪いもの</b><p>話は伝わるうちに大げさに。映画も誇張します。</p></div>'
            '<div role="listitem"><span>02</span><b>ペットのように無害</b><p>人なつこく見えても野生。なでようとすると、かまれることも。</p></div>'
            '<div role="listitem"><span>03</span><b>生きていない物</b><p>サンゴやヒトデも生き物。動かないから大切でない、はまちがい。</p></div>'
            '<div role="listitem"><span>04</span><b>人のような心をもつ</b><p>人の気持ちを重ねず、行動をそのまま見る。</p></div>'
            '</div>'
        ),
    },
    'interact': {
        0: (
            '<aside class="nat-board"><p class="section-number">関わり方のものさし</p><h3>受動的 ←→ 能動的</h3>'
            '<div class="nat-scale"><div class="nat-scale__bar" aria-hidden="true"></div><div class="nat-scale__cols">'
            '<div><b>受動的</b><span>見る・観察する</span><span>魚ウォッチング</span><span>景色を楽しむ</span></div>'
            '<div><b>やり方しだい</b><span>写真・ビデオ</span><span>ナイトダイビング</span><span>沈船めぐり</span></div>'
            '<div><b>能動的</b><span>さわる・動かす</span><span>とる・集める</span><span>餌をやる</span></div>'
            '</div></div><p class="nat-board-note">ナチュラリストが目指すのは「受動的」。そのほうが、生き物の自然な姿に出会えます。</p></aside>'
        ),
        1: (
            '<div class="nat-gallery">'
            + fig(A + 'squid.webp', '海底の枝の上を、ヒレを動かして泳ぐイカ', '<b>01 1匹を見る</b>どう泳ぎ、どう向きを変える？')
            + fig(SUB + 'fish-red-960.webp', '赤い海藻のまわりを群れて泳ぐ赤い小魚たち', '<b>02 群れを見る</b>群れの形はどう変わる？')
            + fig(SUB + 'shrimp-anemone-960.webp', 'イソギンチャクの中にいる小さなエビ', '<b>03 ちがう種の関わり</b>だれと、どう暮らす？')
            + fig(F + 'kawahagi.webp', 'カワハギ', '<b>04 ダイバーへの反応</b>近づく？ 離れる？')
            + '</div>'
        ),
        3: (
            '<aside class="nat-rule"><p class="section-number">三浦の海のルール</p><h3>ダイビング中は、何もとらない。</h3><ul>'
            '<li><b>サザエ・トコブシなどの貝／ワカメ・テングサなどの海藻／タコ／イセエビ など</b><span>共同漁業権の対象。とると漁業権の侵害になることがあります（100万円以下の罰金の場合あり）。</span></li>'
            '<li><b>アワビ・ナマコ</b><span>許可なくとると、3年以下の拘禁刑または3,000万円以下の罰金の場合があります。</span></li>'
            '</ul><p class="nat-board-note">神奈川県水産課「<a href="https://www.pref.kanagawa.jp/docs/kb2/cnt/f790/p8874.html" target="_blank" rel="noopener">海のルールを守りましょう！！</a>」（2026年7月16日更新）より。旅先では、その土地のルールを確かめましょう。</p></aside>'
        ),
        5: (
            '<aside class="nat-mission nat-clues"><p class="section-number">2本目のダイブで探すもの</p><h3>「人のあと」と、その理由。</h3><div class="nat-clue-grid">'
            '<div><b>からんだもの</b><span>釣り糸・おもり・ロープ</span><small>どこから来た？ 生き物にからんでいない？</small></div>'
            '<div><b>落ちているもの</b><span>空き缶・ビニール・ガラス</span><small>流れてきた？ 捨てられた？</small></div>'
            '<div><b>動かされたもの</b><span>ひっくり返った石・こすれた岩</span><small>だれが、何のために？</small></div>'
            '<div><b>生き物のふるまい</b><span>すぐ寄ってくる魚・すぐ逃げる魚</span><small>餌付けやとられた経験のあとかも</small></div>'
            '</div><p class="nat-board-note">見つけても、さわらずにスレートへ。ごみを拾うかどうかは、生き物がすみかにしていないか確かめてから、講師の指示で。</p></aside>'
        ),
    },
    'protect': {
        0: (
            '<figure class="nat-trim"><div class="nat-trim__panels">'
            '<div class="nat-trim__panel is-good"><p>○ 水平で、底から離れる</p>'
            '<svg viewBox="0 0 340 170" role="img" aria-label="体を水平にして、水底から離れて泳ぐダイバーの図。器材は体に留まっている">'
            '<rect x="0" y="136" width="340" height="34" fill="#e8dcc0"/><path d="M0 136q40-8 80 0t80 0 80 0 100 0" fill="none" stroke="#c9b68e" stroke-width="3"/>'
            '<g transform="translate(60 62)"><rect x="40" y="-14" width="96" height="14" rx="7" fill="#7d8b8a"/>'
            '<ellipse cx="88" cy="12" rx="64" ry="17" fill="#1b4b47"/><circle cx="168" cy="10" r="14" fill="#1b4b47"/>'
            '<rect x="160" y="2" width="16" height="9" rx="3" fill="#9fd8cf"/>'
            '<path d="M26 10L-20 2M26 16L-20 22" stroke="#1b4b47" stroke-width="9" stroke-linecap="round"/>'
            '<path d="M-20 2l-30 -10 4 18z M-20 22l-30 6 10 14z" fill="#e0873b"/></g>'
            '<path d="M60 116v14M280 116v14" stroke="#2c7a5c" stroke-width="2" stroke-dasharray="4 4"/>'
            '</svg></div>'
            '<div class="nat-trim__panel is-bad"><p>× 足が下がり、器材がぶら下がる</p>'
            '<svg viewBox="0 0 340 170" role="img" aria-label="足が下がった姿勢で、フィンが底に当たって砂が舞い、予備の呼吸器がぶら下がっているダイバーの図">'
            '<rect x="0" y="136" width="340" height="34" fill="#e8dcc0"/>'
            '<g transform="translate(170 72) rotate(-38)"><rect x="-40" y="-26" width="90" height="14" rx="7" fill="#7d8b8a"/>'
            '<ellipse cx="0" cy="0" rx="60" ry="16" fill="#1b4b47"/><circle cx="76" cy="-2" r="14" fill="#1b4b47"/>'
            '<rect x="68" y="-10" width="16" height="9" rx="3" fill="#9fd8cf"/>'
            '<path d="M-54 -2L-104 -10M-54 6L-104 14" stroke="#1b4b47" stroke-width="9" stroke-linecap="round"/>'
            '<path d="M-104 -10l-30 -10 4 18z M-104 14l-30 6 10 14z" fill="#e0873b"/></g>'
            '<path d="M196 80q6 30 -6 58" fill="none" stroke="#e0b83b" stroke-width="3"/><circle cx="190" cy="140" r="6" fill="#e0b83b"/>'
            '<g fill="#d7c7a0" opacity=".9"><circle cx="72" cy="128" r="9"/><circle cx="88" cy="120" r="7"/><circle cx="60" cy="118" r="6"/><circle cx="100" cy="130" r="8"/><circle cx="200" cy="128" r="6"/></g>'
            '</svg></div></div>'
            '<figcaption>○の図のように、体を水平にして底から離れると、フィンも器材も生き物にふれません。×の図は、重すぎるウエイトや、留めていない器材で起きやすい姿勢です。（イメージ図）</figcaption></figure>'
            + '<figure class="nat-wide">' + img(A + 'diver-hover.webp', '根の上を、体を水平にして泳ぐダイバー。まわりを小魚が群れている') + '<figcaption><b>根の上を、水平に。</b>体を水平にして根から離れて泳げば、まわりの小魚も逃げません。（三浦の海）</figcaption></figure>'
        ),
        1: (
            '<div class="nat-aware"><div><span>市民科学</span><b>Dive Against Debris</b><p>水中のごみを回収し、種類と量を記録して報告。</p></div>'
            '<div><span>保護区を広げる</span><b>Adopt the Blue</b><p>ダイビングスポットを見守り、データを集める。</p></div>'
            '<div><span>あなたの1本</span><b>ログに書く</b><p>いつ・どこで・何を・何をしていたか。それも海を知る記録。</p></div></div>'
        ),
        3: (
            '<div class="nat-specialty">'
            '<div><span>写真・ビデオ</span><b>撮るのは写真だけ。残すのは泡だけ。</b></div>'
            '<div><span>水中スクーター</span><b>プロペラを底に向けない。早めに止まる。</b></div>'
            '<div><span>ナイト</span><b>狭い範囲をじっくり。夜の行動に出会う。</b></div>'
            '<div><span>調べる・記録する</span><b>見たことが、海の今を伝えるデータに。</b></div>'
            '</div>'
        ),
    },
}

# 背骨のない動物の7つのグループのカード（本文の正は JSON の phyla）。
PHYLUM_PHOTO = {
    'sponge': (A + 'sponge-hermit.webp', '岩につく、ピンク色のぶつぶつした塊。カイメンのなかまと思われる。上にヤドカリがいる', 'ピンク色の塊がカイメンのなかま（上はヤドカリ）'),
    'cnidaria': (A + 'sea-fans.webp', '岩壁に広がる、黄色とむらさき色の扇のようなヤギのなかま', '黄色とむらさきの扇は、ヤギのなかま'),
    'worm': None,
    'mollusc': (A + 'octopus.webp', '岩のくぼみで、貝殻を前に置いて隠れるタコ', '貝殻を盾にして隠れるタコ'),
    'arthropod': (F + 'monhanasyako.webp', '虹のような色のモンハナシャコ', 'モンハナシャコ'),
    'echinoderm': (A + 'urchin-crab.webp', 'ラッパウニと思われるウニ。花のように見える部分が並ぶ', 'ラッパウニと思われるウニ。花のような部分も体の一部'),
    'tunicate': (A + 'sea-squirt.webp', '岩についた、青いすじのある透明なホヤのなかま', '青いすじのある、透明なホヤのなかま'),
}


def phyla_cards(module):
    cards = []
    for ph in module['phyla']:
        photo = PHYLUM_PHOTO.get(ph['key'])
        if photo:
            src, alt, cap = photo
            media = img(src, alt) + f'<p class="nat-phylum__cap">{esc(cap)}（三浦の海）</p>'
        else:
            media = f'<div class="nat-textcard" aria-hidden="true">{esc(ph["name"][0])}</div><p class="nat-phylum__cap">写真の準備中です</p>'
        cards.append(
            f'<article class="nat-phylum">{media}<div class="nat-phylum__body">'
            f'<h4>{esc(ph["name"])}<small>（{esc(ph["reading"])}）</small></h4>'
            f'<p class="nat-phylum__meaning">名前の意味：{esc(ph["meaning"])}　／　{esc(ph["examples"])}</p>'
            f'<dl><dt>体</dt><dd>{esc(ph["body"])}</dd><dt>食べ方・守り方</dt><dd>{esc(ph["food"])}</dd></dl>'
            f'<p class="nat-phylum__note">{esc(ph["note"])}</p></div></article>')
    return '<div class="nat-phyla">' + ''.join(cards) + '</div>'


ANS = {m['id']: [q['answer'] for q in m['questions']] for m in mods}


def build():
    check_answer_key()
    hero_photos = [
        (F + 'ao-umiushi.webp', 'アオウミウシ'),
        (F + 'otohimeebi.webp', 'オトヒメエビ'),
        (F + 'kaeruankou.webp', 'カエルアンコウ'),
        (F + 'nekozame.webp', 'ネコザメ'),
    ]
    mosaic = ''.join(f'<figure>{img(src, name + "（三浦の海）", eager=True)}<figcaption>{name}</figcaption></figure>' for src, name in hero_photos)
    parts = [f'''<!doctype html>
<html lang="ja"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#0f3f3a"><meta name="robots" content="noindex,nofollow,noarchive"><title>ナチュラリストSP 事前学習 | 三浦 海の学校</title><meta name="description" content="三浦の海の写真と図で学ぶ、ナチュラリストSPの事前学習。{TOTAL}章{QCOUNT}問で、生態系・共生・分類・海の生き物・関わり方・守る潜り方を確認します。"><link rel="stylesheet" href="styles.css"><link rel="stylesheet" href="dpv.css"><link rel="stylesheet" href="naturalist.css"></head>
<body class="dpv-course nat-course"><a class="skip-link" href="#main">本文へ移動</a>
<header class="hero dpv-hero nat-hero"><nav class="topbar" aria-label="サイトナビゲーション"><a class="brand" href="../">三浦 海の学校</a><span class="course-label">UNDERWATER NATURALIST / PRE-STUDY</span></nav>
<div class="hero__inner"><div class="hero__copy"><p class="eyebrow">ナチュラリスト SP · 事前学習</p><h1>名前の先にある、<br>海の暮らしを<wbr>見に行こう。</h1><p class="hero__lead">食べる・隠れる・ともに暮らす。<br class="wide-only">しくみが分かると、いつもの三浦の海が、ぜんぶちがって見えてきます。</p>
<div class="hero__meta"><span><b>{TOTAL}</b> 章</span><span><b>{QCOUNT}</b> 問</span><span><b>80–110</b> 分が目安</span></div>
<a class="dpv-start" href="#{mods[0]['id']}">01 から学ぶ <span aria-hidden="true">↗</span></a><a class="dpv-menu-link" href="#dashboard-title">学習メニューを見る</a></div>
<div class="dpv-hero-side"><div class="nat-mosaic">{mosaic}<p>三浦の海で撮った写真</p></div>
<div class="hero__progress" aria-label="全体の学習進捗"><div class="progress-ring" id="progressRing" style="--progress:0"><div><strong id="progressNumber">0</strong><span>%</span></div></div><div><p class="progress-caption">あなたの学習進捗</p><p id="progressMessage">最初のレッスンから始めましょう</p></div></div></div></div></header>
''']
    parts.append('<nav class="lesson-nav" aria-label="レッスン一覧">')
    for i, m in enumerate(mods):
        parts.append(f'<a href="#{m["id"]}" data-nav="{m["id"]}" aria-label="第{i + 1}章 {m["label"]}"><span>{i + 1:02}</span>{m["short"]}</a>')
    parts.append(f'''</nav><main id="main"><section class="dpv-intro page-width" aria-labelledby="welcome-title"><div><p class="section-number">はじめる前に</p><h2 id="welcome-title">1章ずつ、<br>自分のペースで。</h2></div><div><ol class="dpv-study-steps"><li><b>読む</b><span>本文と三浦の海の写真・図で、しくみを理解する。</span></li><li><b>確かめる</b><span>5問に回答。解説を読み、何度でもやり直せます。</span></li><li><b>完了して次へ</b><span>全問正解後に章の完了ボタンを押します。途中の回答も自動保存。</span></li></ol><p class="dpv-save-note">中断しても、同じ受講者IDでログインすれば再開できます。画面の「保存しました」を確認してから閉じましょう。</p><details class="dpv-about"><summary>この教材と、講習当日の流れ</summary><p>三浦 海の学校の独自の事前学習教材です。PADI公式の教材やeLearningとは別のページです。{TOTAL}章を終えたら最終チェックを行い、学習記録を講師に提示してください。担当インストラクターが理解度を確かめ、海での2本のダイブで実際に観察します。</p><p>1本目は、植物・無脊椎動物・脊椎動物を見つけて名前を確かめるダイブ。2本目は、共生や食べる・食べられるの関係と、人が海に残した影響を探すダイブです。2本は1日で行うこともできます。アドバンス講習でナチュラリストのダイブをした方は、講師の判断で1本目として数えられる場合があります。</p><p>写真は、三浦 海の学校が三浦の海で撮ったものです。生き物にはふれず、離れて撮っています。</p></details><noscript><p class="notice">回答と進捗保存にはJavaScriptが必要です。ブラウザーで有効にするか、担当者へ別の学習方法をご相談ください。</p></noscript></div></section>
<section class="dashboard page-width" aria-labelledby="dashboard-title"><div class="section-heading"><div><p class="section-number">{TOTAL} LESSONS / {QCOUNT} QUESTIONS</p><h2 id="dashboard-title">学習メニュー</h2></div><p>各章 約10–14分 · 確認問題5問</p></div><div class="lesson-cards">''')
    card_photos = {
        'ecosystem': SUB + 'fish-school-960.webp', 'relations': SUB + 'shrimp-anemone-960.webp',
        'names': F + 'ao-umiushi.webp', 'invertebrates': SUB + 'coral-cowrie-960.webp',
        'vertebrates': F + 'nekozame.webp', 'myths': F + 'utsubo.webp',
        'interact': SUB + 'fish-red-960.webp', 'protect': SUB + 'nudi-pink-960.webp',
    }
    for i, m in enumerate(mods):
        parts.append(f'''<a class="lesson-card" href="#{m['id']}" data-card="{m['id']}"><span class="nat-card-photo">{img(card_photos[m['id']], '')}</span><div class="lesson-card__top"><span>{i + 1:02}</span><em data-status="{m['id']}">未開始</em></div><h3>{esc(m['label'])}</h3><p>{esc(m['lead'])}</p><span class="lesson-card__cta">この章を学ぶ <span aria-hidden="true">↗</span></span></a>''')
    parts.append('</div></section>')
    for i, m in enumerate(mods):
        mid = m['id']
        v = visuals.get(mid, {})
        parts.append(f'''<article class="lesson{' lesson--alt' if i % 2 else ''}" id="{mid}" data-module="{mid}"><header class="lesson-hero page-width"><div class="lesson-hero__number">{i + 1:02}</div><div><p class="section-number">第{i + 1}章 / 全{TOTAL}章 · 確認問題5問</p><h2 tabindex="-1">{esc(m['title'])}</h2><p>{esc(m['lead'])}</p></div></header><div class="lesson-body page-width"><section class="objectives"><p class="margin-label">この章で分かること</p><ul>''')
        parts += ['<li>' + esc(g) + '</li>' for g in m['goals']]
        parts.append('</ul></section>')
        if 'top' in v:
            parts.append(v['top'])
        for j, section in enumerate(m['sections']):
            parts.append(f'<section class="dpv-topic" id="{mid}-topic-{j + 1}"><h3>{esc(section["title"])}</h3>{section["html"]}</section>')
            if j in v:
                parts.append(phyla_cards(m) if v[j] == '{PHYLA}' else v[j])
        case = m['scenario']
        parts.append(f'''<aside class="dpv-case"><p class="section-number">海での場面を考える</p><h3>あなたなら、どう見る？</h3><p>{esc(case['question'])}</p><details><summary>考え方を確認する</summary><p>{esc(case['answer'])}</p></details></aside><section class="quiz" aria-labelledby="{mid}-quiz"><div class="quiz__intro"><p class="section-number">確認問題 · 第{i + 1}章</p><h3 id="{mid}-quiz">理由まで、確かめよう。</h3><p>答えを選ぶと解説が出ます。5問すべてに正解したら、この章を完了できます。</p></div><p class="dpv-quiz-status" data-lesson-status="{mid}" role="status"></p><div class="questions">''')
        for j, q in enumerate(m['questions']):
            qid = f'{mid}{j + 1}'
            assert len(q['options']) == 3 and q['answer'] in 'abc'
            parts.append(f'<fieldset class="question" id="{qid}" data-question="{qid}" data-answer="{q["answer"]}"><legend><span>{j + 1:02} / 05</span>{esc(q["question"])}</legend>')
            for val, txt in zip('abc', q['options']):
                parts.append(f'<label><input type="radio" name="{qid}" value="{val}"><span>{esc(txt)}</span></label>')
            parts.append(f'<p class="feedback" data-feedback></p><template data-correct>正解です。{esc(q["explanation"])}</template><template data-wrong>もう一度考えてみましょう。{esc(q["explanation"])}</template></fieldset>')
        parts.append(f'</div><button class="complete-button" type="button" id="{mid}-complete" data-complete="{mid}" disabled>{esc(m["label"])}を完了する</button><a class="dpv-back" href="#{mid}">この章の本文に戻る ↑</a></section></div></article>')
    parts.append(f'''<section class="finish page-width" id="finish" aria-labelledby="finish-title"><div class="finish__mark" aria-hidden="true">✓</div><div><p class="section-number">ナチュラリスト SP · 事前学習</p><h2 id="finish-title" tabindex="-1">{TOTAL}章を学び、海へ見に行く準備を。</h2><p id="finishText">全{QCOUNT}問と最終チェックを終えると、事前学習完了の記録を発行できます。</p></div><div class="day-checklist" id="dayChecklist" hidden><label><input type="checkbox" data-ready="gear"><span>鉛筆つきの水中スレート（あれば水中図鑑やライト）を用意し、持ち物を講師と確認する</span></label><label><input type="checkbox" data-ready="condition"><span>1本目（植物2・無脊椎4・脊椎5）と2本目（共生と食べる関係・人の影響）の課題、さわらない・巻き上げない潜り方を、講師とバディと確認する</span></label><label><input type="checkbox" data-ready="question"><span>分かりにくかったことを講師へ伝え、知識と実技の確認を受ける</span></label><div class="completion-form"><div class="learner-identity"><span>受講者ID</span><b id="completionLearnerPreview">—</b></div><button id="issueCompletion" type="button" disabled>事前学習の完了画面を発行</button></div></div><div class="completion-proof" id="completionProof" hidden><span>UNDERWATER NATURALIST / PRE-STUDY</span><strong>ナチュラリストSP 事前学習完了</strong><p class="dpv-pending">インストラクター確認待ち</p><div class="completion-meta"><p><span>受講者ID</span><b id="completionName"></b></p><p><span>学習完了日時</span><b id="completionDate"></b></p><p><span>学習記録番号</span><b id="completionCode"></b></p></div><p id="completionSummary">全{TOTAL}章・{QCOUNT}問を終えた学習記録です。PADI公式eLearningの修了証・SP認定証ではありません。</p></div></section>
<section class="dpv-brief page-width" aria-labelledby="brief-title"><p class="section-number">講習当日へ</p><h2 id="brief-title">次は、海で見てみましょう。</h2><div class="dpv-brief-grid"><div><span>01</span><h3>見つけて、名前を確かめる</h3><p>1本目は「植物2・無脊椎4・脊椎5」。種名が分からなくても、特徴を書けば大丈夫。</p></div><div><span>02</span><h3>つながりを見る</h3><p>2本目は、共生と食べる・食べられるの関係。いつも一緒にいる2種を探します。</p></div><div><span>03</span><h3>人の影響を探す</h3><p>ごみ、絡んだ釣り糸、ひっくり返った岩、人に寄ってくる魚。何が原因かを考えます。</p></div></div>
<div class="nat-slate" role="img" aria-label="水中スレートの記録の例。日付と場所、植物2つ、無脊椎動物4つ、脊椎動物5つ、気づいたことが書かれている"><p class="nat-slate__head">スレートの記録の例</p><ul><li><b>植物</b>カジメ／赤い海藻（名前は浮上後に）</li><li><b>無脊椎</b>アオウミウシ／ウニ／岩の穴のエビ／黄色いカイメン？</li><li><b>脊椎</b>スズメダイ（群れ）／カワハギ／ベラのなかま？／ハゼ（砂の穴）／ネコザメ</li><li><b>気づいたこと</b>エビがウツボの口のそばにいた。釣り糸が岩に絡んでいた。</li></ul></div>
<p>学習完了画面を担当インストラクターに提示し、分かりにくかったことをお伝えください。</p></section></main>
<footer><a href="../">三浦 海の学校</a><span>ナチュラリストSP · 事前学習</span></footer><div class="mobile-progress" aria-label="学習進捗"><div><span>ナチュラリスト SP</span><b id="mobileProgress">0 / {TOTAL} 完了</b></div><a href="#{mods[0]['id']}" id="nextLesson">最初のレッスンへ</a></div><script src="app.js"></script></body></html>''')
    out = '\n'.join(parts)
    assert '{PHYLA}' not in out and '{PHOTO:' not in out
    (APP / 'naturalist.html').write_text(out)
    print('Built naturalist.html:', TOTAL, 'modules,', QCOUNT, 'questions,', len(out), 'bytes')


if __name__ == '__main__':
    build()
