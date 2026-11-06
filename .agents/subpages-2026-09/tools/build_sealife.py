#!/usr/bin/env python3
"""海の生き物図鑑の「1種1ページ」を作る。

  python3 .agents/subpages-2026-09/tools/build_sealife.py            # 全種を作る（写真のWebPも、無いものだけ作る）
  python3 .agents/subpages-2026-09/tools/build_sealife.py ao-umiushi # 1種だけ
  python3 .agents/subpages-2026-09/tools/sealife_blog.py             # （先に）ブログの記事の控えを新しくする

- 元は sea-life/creatures.json だけ（事実を足さない・直さない）。
- 出力：sea-life/<id>/index.html、写真は image/fish/web/<元の名前>.webp（幅1200・q80）。
- 一覧 sea-life/index.html の「名前から探す」の静的なリンク一覧（<!-- sealife:index:start/end --> の間）も書き換える。
- ヘッダー・フッター・相談の帯は build.py の部品をそのまま使う（ほかの下層ページと同じ骨組み）。
- ブログの「出てきた日」は tools/sealife-mentions.json（sealife_blog.py が作る控え）から毎回照合する。
- 何度回しても同じ結果になる（日付は MODIFIED の固定値。今日の日付や乱数を使わない）。
"""
import html
import json
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
import build as B  # noqa: E402

from PIL import Image, ImageOps  # noqa: E402

SITE = B.SITE
BASE = B.BASE
LINE = B.LINE
MODIFIED = '2026-11-07'
SL_CSS_V = '20261107'
FISH = os.path.join(SITE, 'image', 'fish')
WEB = os.path.join(FISH, 'web')
MENTIONS = os.path.join(HERE, 'sealife-mentions.json')  # 種ごとの照合結果だけ（本文なし・リポジトリに入れてよい）
import sealife_blog  # noqa: E402
BLOG_CACHE = sealife_blog.CACHE  # 本文入りの控え（git の外）
MAX_BLOG = 8   # 見せるのは最初の SHOW_BLOG 本、残りは「ほかに◯本」の中
SHOW_BLOG = 3
# 写真の同定が確かめられていない種は、吉田さんの確認までページを作らない（一覧の索引・mentions にも出さない。JS の図鑑はそのまま）
EXCLUDE = ['murasakiisoginchaku']
# 「この海で潜るには」の3枚目（/sea-conditions/ は 2026-11-07 本番公開・200 を確認）
CONDITIONS = ('三浦の海の水温（月別）', '/sea-conditions/', '水温・透明度・着るものを、月ごとに')

esc = B.esc
KATA = r'[゠-ヿㇰ-ㇿｦ-ﾟ]'  # カタカナ（長音「ー」と中黒も含む）


# ---------------------------------------------------------------- 写真
def web_name(file):
    return os.path.splitext(file)[0] + '.webp'


def make_webp(file):
    """原寸JPG → 幅1200のWebP。すでにあって元より新しければ作らない。大きさ(w,h)を返す。"""
    src = os.path.join(FISH, file)
    dst = os.path.join(WEB, web_name(file))
    if not (os.path.exists(dst) and os.path.getmtime(dst) >= os.path.getmtime(src)):
        os.makedirs(WEB, exist_ok=True)
        im = ImageOps.exif_transpose(Image.open(src)).convert('RGB')
        if im.width > 1200:
            im = im.resize((1200, round(im.height * 1200 / im.width)), Image.LANCZOS)
        im.save(dst, 'WEBP', quality=80, method=6)
    with Image.open(dst) as im:
        return im.size


def thumb(file):
    return '/image/fish/thumbs/' + web_name(file)


# ---------------------------------------------------------------- ブログの照合
def name_patterns(c):
    """名前の表記ゆれ。「（幼魚）」を外した名前でも探す。「〜の仲間」「〜のなかま」「〜の幼魚」は名前を含むので拾える。
    前後がカタカナの所は数えない（「ウツボ」が「トラウツボ」に、「スズメダイ」が「ソラスズメダイ」に当たらないように）。"""
    name = c['name_ja']
    base = re.sub(r'（[^）]*）', '', name).strip()
    pats = []
    if base.endswith('の卵'):  # アオリイカの卵：名前そのもの、または同じ文に「アオリイカ」と「卵」
        sp = base[:-2]
        pats.append(re.compile(re.escape(base)))
        pats.append(re.compile(rf'(?<!{KATA}){re.escape(sp)}(?!{KATA})[^。！？]*?卵|卵[^。！？]*?(?<!{KATA}){re.escape(sp)}(?!{KATA})'))
        return pats
    for n in dict.fromkeys([name, base]):
        pats.append(re.compile(rf'(?<!{KATA}){re.escape(n)}(?!{KATA})'))
    return pats


def blog_hits(c, posts):
    hits = []
    pats = name_patterns(c)
    for url, p in posts.items():
        txt = p.get('text', '')
        m = None
        for pat in pats:
            m = pat.search(txt)
            if m:
                break
        if not m:
            continue
        a, b = max(0, m.start() - 8), min(len(txt), m.end() + 22)
        snip = txt[a:b].strip()
        snip = ('…' if a > 0 else '') + snip + ('…' if b < len(txt) else '')
        hits.append({'url': url, 'title': p['title'], 'date': p['date'], 'snip': snip, 'word': m.group(0)})
    hits.sort(key=lambda h: (h['date'], h['url']), reverse=True)
    return hits


# ---------------------------------------------------------------- ページの部品
def place_tag(c):
    # ヒーローのタグ（どこの海か）。表の「見られる所」は json のまま
    return '三浦・宮川湾' if '宮川湾' in c['spot'] else '三浦・ビーチ'


def is_binomial(s):
    # 英名の欄に旧学名（「属名 種小名」）が入っている種がある。その場合は英名として出さない
    return bool(re.fullmatch(r'[A-Z][a-z]+ [a-z]+', s or ''))


def area_of(c):
    # 題名の地名。旧拠点で撮った写真が混じる可能性があるので「城ヶ島」は入れない（2026-11-07 ディレクター）
    return '三浦・宮川湾の海' if '宮川湾' in c['spot'] else '三浦の海'


def short_desc(s, n=130):
    if len(s) <= n:
        return s
    cut = s[:n]
    k = cut.rfind('。')
    return cut[:k + 1] if k >= 60 else cut[:n - 1] + '…'


def related(c, all_c):
    """同じ分類から最大6種（json の並びで近い順）。4種に満たなければ、同じ見られる所の生き物で埋める。"""
    idx = {x['id']: i for i, x in enumerate(all_c)}
    me = idx[c['id']]
    same = sorted((x for x in all_c if x['category'] == c['category'] and x['id'] != c['id']),
                  key=lambda x: (x.get('family') != c.get('family'), abs(idx[x['id']] - me), idx[x['id']]))[:6]
    fill = []
    if len(same) < 4:
        fill = sorted((x for x in all_c if x['category'] != c['category'] and x['spot'] == c['spot']),
                      key=lambda x: (abs(idx[x['id']] - me), idx[x['id']]))[:6 - len(same)]
    return same, fill


def card(x):
    f = x['photos'][0]['file']
    return f'''          <a class="card sl-card" href="/sea-life/{x['id']}/">
            <span class="card__img"><img src="{thumb(f)}" alt="{esc(x['name_ja'])}の写真" width="480" height="360" loading="lazy" decoding="async"></span>
            <span class="tag">{esc(x['category'])}</span>
            <h3>{esc(x['name_ja'])}<small lang="la">{esc(x['name_sci'])}</small></h3>
          </a>'''


def build_one(c, all_c, posts):
    cid = c['id']
    name = c['name_ja']
    path = f'sea-life/{cid}/'
    url = f'{BASE}/{path}'
    area = area_of(c)
    photos = []
    for i, p in enumerate(c['photos'], start=1):
        w, h = make_webp(p['file'])
        photos.append({'src': '/image/fish/web/' + web_name(p['file']), 'w': w, 'h': h,
                       'alt': f'{name}の写真（{i}枚目・三浦 海の学校の図鑑より）' if len(c['photos']) > 1 else f'{name}の写真（三浦 海の学校の図鑑より）',
                       'credit': p.get('credit', '')})
    hero = photos[0]
    title = f'{name}｜{area}で見られる生き物図鑑｜三浦 海の学校'
    desc = short_desc(c['description'])
    hits = blog_hits(c, posts)
    same, fill = related(c, all_c)
    msg = f'図鑑の「{name}」を見ました。会えそうな日のダイビングを相談したいです。'

    # ---- 構造化データ
    images = []
    for i, ph in enumerate(photos, start=1):
        images.append({'@type': 'ImageObject', '@id': f'{url}#photo-{i}', 'contentUrl': BASE + ph['src'],
                       'url': BASE + ph['src'], 'name': f'{name}（{i}枚目）' if len(photos) > 1 else name,
                       'width': ph['w'], 'height': ph['h'], 'caption': ph['alt']})
    show_en = bool(c['name_en']) and c['name_en'] != c['name_sci'] and not is_binomial(c['name_en'])
    alt_names = [c['name_sci']] + ([c['name_en']] if show_en else [])
    graph = [
        {'@type': 'WebPage', '@id': url + '#webpage', 'url': url, 'name': title, 'description': desc,
         'isPartOf': {'@id': BASE + '/#website'}, 'inLanguage': 'ja', 'breadcrumb': {'@id': url + '#breadcrumb'},
         'dateModified': MODIFIED, 'primaryImageOfPage': {'@id': url + '#photo-1'},
         'image': [{'@id': f'{url}#photo-{i}'} for i in range(1, len(photos) + 1)],
         'about': {'@type': 'Thing', 'name': name, 'alternateName': alt_names if len(alt_names) > 1 else alt_names[0]},
         'publisher': {'@id': BASE + '/#org'}},
        {'@type': 'BreadcrumbList', '@id': url + '#breadcrumb', 'itemListElement': [
            {'@type': 'ListItem', 'position': 1, 'name': 'ホーム', 'item': BASE + '/'},
            {'@type': 'ListItem', 'position': 2, 'name': '海の生き物図鑑', 'item': BASE + '/sea-life/'},
            {'@type': 'ListItem', 'position': 3, 'name': name, 'item': url}]},
    ] + images
    ld = json.dumps({'@context': 'https://schema.org', '@graph': graph}, ensure_ascii=False, indent=2)

    m = {
        'path': path, 'title': title, 'desc': desc, 'from': f'sea-life-{cid}', 'og': hero['src'],
        'ogAlt': hero['alt'], 'ogTitle': f'{name}｜三浦の海の生き物図鑑',
        'ogType': 'article',
        '_ld': '\n'.join('  ' + l for l in ld.split('\n')),
        'extraHead': (f'  <link rel="preload" as="image" href="{hero["src"]}" fetchpriority="high">\n'
                      f'  <link rel="stylesheet" href="/css/sea-life.css?v={SL_CSS_V}">'),
        'ask': {'title': f'{esc(name)}に、<span class="nw">会いに行く日を相談。</span>',
                'lead': '会える場所や季節の目安、ビーチとボートのどちらがいいか。質問だけでも大丈夫です。',
                'msg': msg},
    }
    out = B.head(m)
    out = out.replace('<meta property="og:image:width" content="1200">', f'<meta property="og:image:width" content="{hero["w"]}">')
    out = out.replace('<meta property="og:image:height" content="630">', f'<meta property="og:image:height" content="{hero["h"]}">')
    out += '\n<body class="sub sl-page">\n  <a class="skip" href="#main">本文へスキップ</a>\n\n'
    out += '  <!-- アイコン（Font Awesome を読み込まず、使う分だけインラインで持つ） -->\n' + B.SPRITE + '\n'
    out += B.header('sea-life', '#contact').replace('{FROM}', m['from'])

    seasons = '・'.join(c['season'])
    fam_row = f'<tr><th scope="row">科</th><td>{esc(c["family"])}</td></tr>' if c.get('family') else ''
    en_row = (f'<tr><th scope="row">英名</th><td>{esc(c["name_en"])}</td></tr>'
              if show_en else '')
    figs = []
    for i, ph in enumerate(photos[1:], start=1):  # 1枚目はヒーロー。ここは2枚目から
        lazy = ' loading="lazy"'
        cap = f'<figcaption>撮影：{esc(ph["credit"])}</figcaption>' if ph['credit'] else ''
        figs.append(f'          <figure class="sl-photo"><img src="{ph["src"]}" alt="{esc(ph["alt"])}" width="{ph["w"]}" height="{ph["h"]}"{lazy} decoding="async">{cap}</figure>')

    photos_block = (f'''          <div class="sl-photos">
{chr(10).join(figs)}
          </div>
''' if figs else '')
    body = f'''
  <main id="main">

    <!-- ============================================================
         HERO（sea-life/creatures.json から build_sealife.py が作る。手で直さない）
         ============================================================ -->
    <section class="sl-head" aria-labelledby="page-title">
      <div class="sl-head__band">
        <div class="sl-head__in">
          <nav class="crumbs" aria-label="パンくずリスト">
            <ol><li><a href="/">ホーム</a></li><li><a href="/sea-life/">海の生き物図鑑</a></li><li aria-current="page">{esc(name)}</li></ol>
          </nav>
          <p class="sl-head__book">三浦の海の生き物図鑑</p>
          <h1 id="page-title"{' class="is-long"' if len(name) >= 10 else ''}>{esc(name)}<small lang="la" class="sl-sci">{esc(c['name_sci'])}</small></h1>
          <p class="sl-head__tags"><span>{esc(c['category'])}</span><span>{esc(place_tag(c))}</span></p>
        </div>
      </div>
      <div class="sl-head__body">
        <figure class="sl-head__photo"><img src="{hero['src']}" alt="{esc(hero['alt'])}" width="{hero['w']}" height="{hero['h']}" fetchpriority="high" decoding="async"></figure>
        <div class="sl-head__text">
          <p class="sl-head__lead">{esc(c['description'])}</p>
          <a class="sl-head__go" href="#about">図鑑の記録と見つけ方 ↓</a>
        </div>
      </div>
    </section>

    <!-- ============================================================
         FACTS — 図鑑の記録（2枚目からの写真）
         ============================================================ -->
    <section id="about" class="sec" aria-labelledby="about-title">
      <div class="h-wrap">
        <div class="sl-about{' sl-about--photos' if len(photos) > 1 else ''}">
{photos_block}          <div class="sl-facts">
            <h2 id="about-title" class="h-title">{esc(name)}の<span class="nw">図鑑の記録</span></h2>
            <p class="sl-desc">{esc(c['description'])}</p>
            <table class="tbl tbl--compact sl-tbl">
              <caption class="sr-only">{esc(name)}の分類・大きさ・水深・季節・見られる所</caption>
              <tbody>
                <tr><th scope="row">分類</th><td>{esc(c['category'])}</td></tr>
                {fam_row}
                <tr><th scope="row">学名</th><td><i lang="la">{esc(c['name_sci'])}</i></td></tr>
                {en_row}
                <tr><th scope="row">大きさ</th><td>{esc(c['size'])}</td></tr>
                <tr><th scope="row">水深</th><td>{esc(c['depth'])}</td></tr>
                <tr><th scope="row">季節</th><td>{esc(seasons)}</td></tr>
                <tr><th scope="row">見られる所</th><td>{esc(c['spot'])}</td></tr>
              </tbody>
            </table>
            <p class="sl-note">三浦 海の学校のダイビングで撮った写真と、潜って見てきた記録です。会えるかどうかは、その日の海しだいです。</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================
         TIPS — 見つけ方のコツ・ログに書くなら
         ============================================================ -->
    <section id="tips" class="sec sec--soft" aria-labelledby="tips-title">
      <div class="h-wrap">
        <div class="sec-head">
          <span class="h-label">How to find <small>見つけ方</small></span>
          <h2 id="tips-title" class="h-title">{esc(name)}の、<span class="nw">見つけ方のコツは？</span></h2>
        </div>
        <div class="grid grid--2">
          <div class="card sl-tip">
            <span class="tag">見つけ方のコツ</span>
            <p>{esc(c['beginner_tips'])}</p>
          </div>
          <div class="card sl-tip">
            <span class="tag">ログに書くなら</span>
            <p>{esc(c['log_point'])}</p>
          </div>
        </div>
        <p class="sl-mid"><a class="btn-line" href="{LINE}" target="_blank" rel="noopener noreferrer" data-line-msg="{esc(msg)}"><span class="line-mark"><svg aria-hidden="true"><use href="#i-line"/></svg></span>会えそうな日をLINEで聞く</a></p>
        <p class="sl-trial">Cカードがなくても、<a href="/trial-diving/">体験ダイビング</a>で会えることがあります。</p>
      </div>
    </section>
'''
    if hits:
        def li(h):
            d = h['date']
            dlabel = f'{int(d[:4])}年{int(d[5:7])}月{int(d[8:10])}日' if re.match(r'\d{4}-\d\d-\d\d', d) else d
            return f'''          <li><a href="{esc(h['url'])}"><time datetime="{esc(d)}">{dlabel}</time><b>{esc(h['title'])}</b><small>{esc(h['snip'])}</small></a></li>'''
        shown = [li(h) for h in hits[:SHOW_BLOG]]
        rest = [li(h) for h in hits[SHOW_BLOG:MAX_BLOG]]
        rest_block = ''
        if rest:
            rest_block = f'''        <details class="sl-blog-more">
          <summary>ほかに{len(rest)}本</summary>
          <ul class="sl-blog">
{chr(10).join(rest)}
          </ul>
        </details>
'''
        more = (f'<p class="sl-more">ほかにも {len(hits) - MAX_BLOG} 本の記事に出てきます。'
                f'<a href="/blog/">ブログの一覧へ</a></p>' if len(hits) > MAX_BLOG else '')
        body += f'''
    <!-- ============================================================
         BLOG — 名前が出てくる記事（sealife-mentions.json の照合）
         ============================================================ -->
    <section id="blog" class="sec" aria-labelledby="blog-title">
      <div class="h-wrap">
        <div class="sec-head">
          <span class="h-label">Our log <small>名前が出てくる記事</small></span>
          <h2 id="blog-title" class="h-title">{esc(name)}の名前が出てくる、<span class="nw">三浦 海の学校のブログ</span></h2>
          <p class="h-lead">ダイビングの日の記録や海の話の中に、{esc(name)}が出てくる記事です。新しい順に並べています。</p>
        </div>
        <ul class="sl-blog">
{chr(10).join(shown)}
        </ul>
{rest_block}        {more}
      </div>
    </section>
'''
    rel_cards = [card(x) for x in same]
    fill_cards = [card(x) for x in fill]
    head_same = f'同じ{esc(c["category"])}の<span class="nw">生き物</span>' if same else ''
    body += f'''
    <!-- ============================================================
         SAME — 同じ分類の生き物
         ============================================================ -->
    <section id="same" class="sec sec--soft" aria-labelledby="same-title">
      <div class="h-wrap">
        <div class="sec-head">
          <span class="h-label">More <small>ほかの生き物</small></span>
          <h2 id="same-title" class="h-title">{head_same if same else '同じ場所で見られる<span class="nw">生き物</span>'}</h2>
        </div>
        <div class="grid grid--3 sl-cards">
{chr(10).join(rel_cards if same else fill_cards)}
        </div>
'''
    if same and fill:
        body += f'''        <h3 class="sl-sub">同じ所（{esc(c['spot'])}）で見られる生き物</h3>
        <div class="grid grid--3 sl-cards">
{chr(10).join(fill_cards)}
        </div>
'''
    body += f'''        <p class="sl-back"><a class="link-arrow" href="/sea-life/#sealife-index">図鑑の{len(all_c)}種の名前の一覧へ</a></p>
      </div>
    </section>

    <!-- ============================================================
         GO — この海で潜るには
         ============================================================ -->
    <section id="go" class="sec" aria-labelledby="go-title">
      <div class="h-wrap">
        <div class="sec-head">
          <span class="h-label">Diving <small>会いに行く</small></span>
          <h2 id="go-title" class="h-title">この海で、<span class="nw">潜るには？</span></h2>
          <p class="h-lead">Cカード（ライセンス）をお持ちの方はファンダイビングで、はじめての方は体験ダイビングで、三浦の海に潜れます。</p>
        </div>
        <div class="grid grid--3">
          <a class="card" href="/fun-diving/">
            <span class="tag">Cカードをお持ちの方</span>
            <h3>ファンダイビング<small>ビーチ2本 ¥13,200／ボート2本 ¥19,800（税込・レンタル器材別）</small></h3>
            <span class="go">ファンダイビングの料金と日程を見る</span>
          </a>
          <a class="card" href="/trial-diving/">
            <span class="tag">はじめての方</span>
            <h3>体験ダイビング<small>¥19,800（税込）</small></h3>
            <span class="go">体験ダイビングの流れを見る</span>
          </a>
          <a class="card" href="{CONDITIONS[1]}">
            <span class="tag">海の季節</span>
            <h3>{CONDITIONS[0]}<small>{CONDITIONS[2]}</small></h3>
            <span class="go">{CONDITIONS[0]}を見る</span>
          </a>
        </div>
      </div>
    </section>
'''
    out += body
    out += B.ask(m)
    out += '\n  </main>\n'
    out += B.footer(m)
    dst = os.path.join(SITE, path, 'index.html')
    os.makedirs(os.path.dirname(dst), exist_ok=True)
    with open(dst, 'w', encoding='utf-8') as f:
        f.write(out)
    return {'id': cid, 'mentions': [{'url': h['url'], 'title': h['title'], 'date': h['date'], 'matched': h['word']} for h in hits],
            'blog': len(hits), 'photos': len(photos), 'related': len(same), 'fill': len(fill)}


# ---------------------------------------------------------------- 一覧ページの静的なリンク一覧
def index_section(all_c):
    groups = {}
    for x in all_c:
        groups.setdefault(x['category'], []).append(x)
    parts = []
    for cat, xs in groups.items():
        links = '\n'.join(f'            <li><a href="/sea-life/{x["id"]}/">{esc(x["name_ja"])}</a></li>' for x in xs)
        parts.append(f'''        <details class="sl-index__group">
          <summary><h3>{esc(cat)}（{len(xs)}種）</h3></summary>
          <ul>
{links}
          </ul>
        </details>''')
    return f'''<!-- sealife:index:start（build_sealife.py が書き換える。手で直さない） -->
    <section id="sealife-index" class="sec sec--tight sl-index" aria-labelledby="sealife-index-title">
      <div class="h-wrap">
        <div class="sec-head">
          <span class="h-label">Index <small>名前の一覧</small></span>
          <h2 id="sealife-index-title" class="h-title">名前の一覧から探す<span class="nw">（{len(all_c)}種）</span></h2>
          <p class="h-lead">分類を開くと名前が並びます。1種ずつ、写真・大きさ・季節・見つけ方のコツをまとめたページがあります。</p>
        </div>
        <div class="sl-index__grid">
{chr(10).join(parts)}
        </div>
      </div>
    </section>
    <!-- sealife:index:end -->'''


def update_index(all_c):
    p = os.path.join(SITE, 'sea-life', 'index.html')
    s = open(p, encoding='utf-8').read()
    sec = index_section(all_c)
    # いったん外して、ファン・体験の入口と読み物（section.about）の後ろ＝相談の帯の前に置く
    s = re.sub(r'\n*    <!-- sealife:index:start.*?<!-- sealife:index:end -->\n*', '\n\n', s, flags=re.S)
    mm = re.search(r'<section class="about".*?</section>\n', s, flags=re.S)
    assert mm, '一覧ページに差し込む位置（section.about）が見つかりません'
    s = s[:mm.end()] + '\n    ' + sec + '\n' + s[mm.end():]
    # JS の図鑑のモーダルの「くわしく見る」を、ページの無い種には出さない
    s = re.sub(r'var NO_PAGE = \[[^\]]*\]; // sealife:nopage', lambda _: 'var NO_PAGE = ' + json.dumps(EXCLUDE) + '; // sealife:nopage', s)
    link = '<link rel="stylesheet" href="/css/sea-life.css?v=' + SL_CSS_V + '">'
    s = re.sub(r'\n  <link rel="stylesheet" href="/css/sea-life\.css\?v=[^"]*">', '', s)
    s = re.sub(r'(<link rel="stylesheet" href="/css/page\.css\?v=[^"]*">)', lambda mm: mm.group(1) + '\n  ' + link, s, count=1)
    open(p, 'w', encoding='utf-8').write(s)


def main():
    all_c = [c for c in json.load(open(os.path.join(SITE, 'sea-life', 'creatures.json'), encoding='utf-8'))
             if c['id'] not in EXCLUDE]
    posts = {}
    if os.path.exists(BLOG_CACHE):
        posts = json.load(open(BLOG_CACHE, encoding='utf-8')).get('posts', {})
    else:
        print('注意：ブログの控え', BLOG_CACHE, 'が無いので「出てくる記事」は空です（先に sealife_blog.py を）')
        sys.exit(1)
    ids = sys.argv[1:]
    res = [build_one(c, all_c, posts) for c in all_c if not ids or c['id'] in ids]
    if not ids:
        update_index(all_c)
        # creatures.json から消えた種のページは残さない（作った覚えのあるページだけ消す）
        live = {c['id'] for c in all_c}
        root = os.path.join(SITE, 'sea-life')
        for d in sorted(os.listdir(root)):
            f = os.path.join(root, d, 'index.html')
            if os.path.isdir(os.path.join(root, d)) and d not in live and os.path.exists(f) \
                    and 'build_sealife.py が作る' in open(f, encoding='utf-8').read():
                os.remove(f)
                if not os.listdir(os.path.join(root, d)):
                    os.rmdir(os.path.join(root, d))
                print('消した（creatures.json に無い・EXCLUDE）', f)
    nb = sum(1 for r in res if r['blog'])
    print(f'作ったページ {len(res)}・ブログに出てくる種 {nb}・出てこない種 {len(res) - nb}・写真1枚の種 {sum(1 for r in res if r["photos"] == 1)}')
    if not ids:
        ment = {'note': 'build_sealife.py が書く。種ごとにブログで名前が出てくる記事（本文は ~/Documents/海学HP.php/.agents/seo/data/sealife/blog-cache.json・git の外）。',
                'posts_checked': len(posts), 'species': {r['id']: r['mentions'] for r in res}}
        json.dump(ment, open(MENTIONS, 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
        summ = [{k: v for k, v in r.items() if k != 'mentions'} for r in res]
        json.dump(summ, open(os.path.join(HERE, 'sealife-build-summary.json'), 'w', encoding='utf-8'), ensure_ascii=False, indent=1)


if __name__ == '__main__':
    main()
