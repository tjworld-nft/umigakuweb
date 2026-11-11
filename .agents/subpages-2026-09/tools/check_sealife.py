#!/usr/bin/env python3
"""図鑑の1種1ページ（sea-life/<id>/index.html）を全部検査する。check.py は pages/ 用なのでこちらを使う。

  python3 .agents/subpages-2026-09/tools/check_sealife.py

見るもの：title（字数・重複）、description（字数・重複）、canonical（自分のURL）、h1 が1つ、
OGP（og:image が実在・大きさ）、img の width/height/alt、サイト内リンクの行き先が実在（リンク切れ）、
JSON-LD の構文と中身（WebPage.about・BreadcrumbList・ImageObject の contentUrl が実在）、
creatures.json との一致（名前・学名・説明がページにある）、禁止の言葉、一覧ページの静的リンク89本。
NG があれば終了コード1。
"""
import json
import os
import re
import sys
from html.parser import HTMLParser

HERE = os.path.dirname(os.path.abspath(__file__))
SITE = os.path.abspath(os.path.join(HERE, '..', '..', '..'))
BASE = 'https://miura-diving.com'
_M = os.path.join(HERE, 'sealife-mentions.json')
BLOG = {x['url'] for v in json.load(open(_M, encoding='utf-8'))['species'].values() for x in v} if os.path.exists(_M) else set()
# この作業コピーにまだ無いが、本番で 200 を確かめたページ（2026-11-07）
LIVE_OK = {'/sea-conditions/'}
FORBID = ['——', 'ブログに出てきた日', '5スター', 'IDC', 'No.1', '唯一', '必ず会え', '一年中見られ', '安全です', '諸磯', 'SUP', 'シーカヤック', '相場']


class P(HTMLParser):
    def __init__(self):
        super().__init__()
        self.tags = []
        self.ld = []
        self._in_ld = False
        self.h1 = 0
        self.title = ''
        self._in_title = False

    def handle_starttag(self, t, a):
        a = dict(a)
        self.tags.append((t, a))
        if t == 'script' and a.get('type') == 'application/ld+json':
            self._in_ld = True
            self.ld.append('')
        if t == 'h1':
            self.h1 += 1
        if t == 'title':
            self._in_title = True

    def handle_endtag(self, t):
        if t == 'script':
            self._in_ld = False
        if t == 'title':
            self._in_title = False

    def handle_data(self, d):
        if self._in_ld:
            self.ld[-1] += d
        if self._in_title:
            self.title += d


def local_target(href):
    """サイト内のパス → 実在するファイルか。外部・#・mailto は None。"""
    if href.startswith(BASE):
        href = href[len(BASE):]
    if not href.startswith('/') or href.startswith('//'):
        return None
    path = href.split('#')[0].split('?')[0]
    f = os.path.join(SITE, path.lstrip('/'))
    if path.endswith('/'):
        f = os.path.join(f, 'index.html')
    return f


def check_page(c, titles, descs, ng, warn, stats, all_c):
    cid = c['id']
    f = os.path.join(SITE, 'sea-life', cid, 'index.html')
    if not os.path.exists(f):
        ng.append(f'{cid}: ページが無い')
        return
    s = open(f, encoding='utf-8').read()
    p = P()
    p.feed(s)
    url = f'{BASE}/sea-life/{cid}/'
    meta = {}
    links = []
    for t, a in p.tags:
        if t == 'meta':
            k = a.get('name') or a.get('property')
            if k:
                meta[k] = a.get('content', '')
        if t == 'link' and a.get('rel') == 'canonical':
            meta['canonical'] = a.get('href')
        if t == 'a' and a.get('href'):
            links.append(a['href'])
        if t == 'img':
            for k in ('width', 'height'):
                if not a.get(k):
                    ng.append(f'{cid}: img {a.get("src")} に {k} が無い')
            if 'alt' not in a:
                ng.append(f'{cid}: img {a.get("src")} に alt が無い')
            elif not a['alt'] and a.get('fetchpriority') != 'high':  # 空の alt はヒーローの背景（同じ写真を下に alt つきで出している）だけ
                warn.append(f'{cid}: img {a.get("src")} の alt が空')
            src = a.get('src', '')
            tf = local_target(src)
            if tf and not os.path.exists(tf):
                ng.append(f'{cid}: 画像が無い {src}')
    title = p.title.strip()
    titles.setdefault(title, []).append(cid)
    if not (20 <= len(title) <= 60):
        warn.append(f'{cid}: title {len(title)}字')
    if c['name_ja'] not in title[:len(c['name_ja']) + 1]:
        ng.append(f'{cid}: title の先頭が名前でない')
    d = meta.get('description', '')
    descs.setdefault(d, []).append(cid)
    if not (40 <= len(d) <= 130):
        ng.append(f'{cid}: description {len(d)}字')
    if meta.get('canonical') != url:
        ng.append(f'{cid}: canonical {meta.get("canonical")}')
    if meta.get('og:url') != url:
        ng.append(f'{cid}: og:url')
    if p.h1 != 1:
        ng.append(f'{cid}: h1 が {p.h1} 個')
    og = meta.get('og:image', '')
    tf = local_target(og)
    if not tf or not os.path.exists(tf):
        ng.append(f'{cid}: og:image が無い {og}')
    else:
        from PIL import Image
        with Image.open(tf) as im:
            if str(im.size[0]) != meta.get('og:image:width') or str(im.size[1]) != meta.get('og:image:height'):
                ng.append(f'{cid}: og:image の大きさが違う')
    if meta.get('robots', '').startswith('noindex'):
        ng.append(f'{cid}: noindex')
    for h in links:
        tf = local_target(h)
        if tf is None:
            continue
        bp = h[len(BASE):] if h.startswith(BASE) else h
        if bp.startswith('/blog/'):  # ブログは別のリポジトリ：ブログのサイトマップの控えにある記事か、ブログのトップなら良し
            if bp != '/blog/' and BASE + bp.split('#')[0] not in BLOG:
                ng.append(f'{cid}: ブログの記事が見つからない {h}')
            continue
        if not os.path.exists(tf) and bp.split('#')[0] not in LIVE_OK:
            ng.append(f'{cid}: リンク切れ {h}')
        if '#' in h and h.split('#')[0] in ('', f'/sea-life/{cid}/'):
            frag = h.split('#')[1]
            if frag and f'id="{frag}"' not in s:
                ng.append(f'{cid}: ページ内リンク切れ #{frag}')
    # JSON-LD
    if len(p.ld) != 1:
        ng.append(f'{cid}: JSON-LD が {len(p.ld)} 個')
    for raw in p.ld:
        try:
            ld = json.loads(raw)
        except Exception as e:
            ng.append(f'{cid}: JSON-LD 構文 {e}')
            continue
        g = {x['@type']: [] for x in ld.get('@graph', [])}
        for x in ld['@graph']:
            g[x['@type']].append(x)
        wp = (g.get('WebPage') or [{}])[0]
        if wp.get('url') != url or wp.get('about', {}).get('name') != c['name_ja']:
            ng.append(f'{cid}: WebPage の url/about')
        alt = wp.get('about', {}).get('alternateName')
        alts = alt if isinstance(alt, list) else [alt]
        if c['name_sci'] not in alts:
            ng.append(f'{cid}: about.alternateName に学名が無い')
        bc = (g.get('BreadcrumbList') or [{}])[0].get('itemListElement', [])
        if len(bc) != 3 or bc[-1].get('item') != url:
            ng.append(f'{cid}: BreadcrumbList')
        imgs = g.get('ImageObject', [])
        if len(imgs) != len(c['photos']):
            ng.append(f'{cid}: ImageObject {len(imgs)} / 写真 {len(c["photos"])}')
        for im in imgs:
            tf = local_target(im.get('contentUrl', ''))
            if not tf or not os.path.exists(tf):
                ng.append(f'{cid}: ImageObject.contentUrl が無い')
        if 'Taxon' in g:
            ng.append(f'{cid}: Taxon を使っている')
    # creatures.json と一致
    import html as H
    plain = H.unescape(re.sub(r'<[^>]+>', ' ', s))
    for k in ('name_ja', 'name_sci', 'description', 'beginner_tips', 'log_point', 'size', 'depth', 'spot'):
        if c[k] not in plain:
            ng.append(f'{cid}: {k} がページに無い')
    if '/contact/?from=sea-life-page' not in s:
        ng.append(f'{cid}: フォームの from が sea-life-page でない')
    if s.count('<li><a href="https://miura-diving.com/blog/') and s.find('<details class="sl-blog-more"') == -1 and s.count('<li><a href="https://miura-diving.com/blog/') > 3:
        ng.append(f'{cid}: ブログの記事が4本以上むき出し')
    main = re.search(r'<main id="main">(.*?)</main>', s, flags=re.S).group(1)
    # 禁止の言葉は、このページで足した文だけを見る（creatures.json の文とブログの抜き書きは元の文なので除く）
    mine = re.sub(r'<section id="blog".*?</section>', '', main, flags=re.S)
    mine = H.unescape(re.sub(r'<[^>]+>', ' ', mine))
    for t in sorted({x[k] for x in all_c for k in ('description', 'beginner_tips', 'log_point', 'name_ja', 'name_en', 'family')}, key=len, reverse=True):
        mine = mine.replace(t, '')
    for w in FORBID:
        if w in mine:
            ng.append(f'{cid}: 禁止の言葉「{w}」')
    # 見えている本文の字数（main の中・相談の帯を除く）
    vis = re.sub(r'<section id="contact".*', '', main, flags=re.S)
    vis = re.sub(r'<(script|style)[^>]*>.*?</\1>', '', vis, flags=re.S)
    vis = re.sub(r'<[^>]+>', '', vis)
    vis = re.sub(r'\s+', '', H.unescape(vis))
    stats.append((cid, len(vis), s.count('id="blog"')))


def main():
    sys.path.insert(0, HERE)
    from build_sealife import EXCLUDE
    all_c = [c for c in json.load(open(os.path.join(SITE, 'sea-life', 'creatures.json'), encoding='utf-8')) if c['id'] not in EXCLUDE]
    ng, warn, stats = [], [], []
    for x in EXCLUDE:  # 公開しない種：ページ・索引・mentions のどこにも無いこと
        if os.path.exists(os.path.join(SITE, 'sea-life', x)):
            ng.append(f'{x}: 公開しない種のページ（フォルダ）が残っている')
        if f'/sea-life/{x}/' in open(os.path.join(SITE, 'sea-life', 'index.html'), encoding='utf-8').read():
            ng.append(f'{x}: 一覧ページから公開しない種へのリンクがある')
        if not re.search(r'var NO_PAGE = \[[^\]]*"' + x + r'"', open(os.path.join(SITE, 'sea-life', 'index.html'), encoding='utf-8').read()):
            ng.append(f'{x}: JS のモーダルの NO_PAGE に入っていない（404 へのリンクになる）')
        if os.path.exists(_M) and x in json.load(open(_M, encoding='utf-8'))['species']:
            ng.append(f'{x}: mentions に公開しない種がある')
    titles, descs = {}, {}
    for c in all_c:
        check_page(c, titles, descs, ng, warn, stats, all_c)
    for t, ids in titles.items():
        if len(ids) > 1:
            ng.append(f'title が重複 {ids}')
    for d, ids in descs.items():
        if len(ids) > 1:
            ng.append(f'description が重複 {ids}')
    # 一覧ページ
    idx = open(os.path.join(SITE, 'sea-life', 'index.html'), encoding='utf-8').read()
    sec = re.search(r'<!-- sealife:index:start.*?<!-- sealife:index:end -->', idx, flags=re.S)
    if not sec:
        ng.append('一覧ページに静的なリンク一覧が無い')
    else:
        got = set(re.findall(r'href="/sea-life/([a-z0-9-]+)/"', sec.group(0)))
        if got != {c['id'] for c in all_c}:
            ng.append(f'一覧のリンクと creatures.json が合わない（{len(got)}本）')
    if 'id="searchInput"' not in idx or 'fetch(jsonUrl)' not in idx:
        ng.append('一覧ページの JS の仕組みが消えている')
    n = len(stats)
    avg = sum(x[1] for x in stats) / n if n else 0
    print(f'ページ {n}・見えている本文の平均 {avg:.0f}字（最小 {min(x[1] for x in stats)}・最大 {max(x[1] for x in stats)}）・ブログの節あり {sum(x[2] for x in stats)}')
    for w in warn:
        print('WARN', w)
    for e in ng:
        print('NG', e)
    print(f'NG {len(ng)}・WARN {len(warn)}')
    sys.exit(1 if ng else 0)


if __name__ == '__main__':
    main()
