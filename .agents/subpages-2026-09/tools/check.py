#!/usr/bin/env python3
"""書き出したページの機械検査。使い方: python3 check.py <pageパス or 名前> ...（例 license fun-diving tokyo-diving-license）
NG は必ず直す。WARN は目で確かめる。"""
import html, json, os, re, sys
from html.parser import HTMLParser

SITE = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..', '..'))
PHONE_OK = {'contact/', 'tokusho/', 'privacy-policy/'}
# FACTS.md にある金額（税込）。ここに無い金額は WARN
PRICES = {
    19800, 6600, 53900, 11000, 64900, 59800, 70800, 5000, 108700, 130700, 135700, 5500, 59400, 65300, 119700,
    13200, 18700, 24200, 28600, 25300, 8800, 2750, 1100, 550, 1500, 23650, 45100, 27500, 36300, 33000, 55000,
    44000, 22000, 64800, 4000, 150000, 132000, 220000, 88000, 7700, 13000, 800, 12400, 19000, 9800,
    22000, 16500, 29700, 34100, 30800, 7150, 18700, 25300,
}
FORBID = ['諸磯', '日本初', '唯一の', 'No.1', 'ナンバーワン', '業界最安', '絶対に安全', '必ず見られ', '数千人', '神奈川県内でも数少ない',
          '品川から約60分', '品川から60分', '品川60分', '横浜から約45分', '横浜から45分', '45分', '約60分', '60〜75分',
          'AggregateRating', 'font-awesome', 'class="fa ', 'class="fas', 'class="fab', 'wp-content/uploads']


class P(HTMLParser):
    def __init__(s):
        super().__init__(convert_charrefs=True)
        s.stack = []; s.hrefs = []; s.srcs = []; s.ids = set(); s.h1 = 0; s.text_noexp = []; s.ld = []; s._ld = None
        s.void = {'img', 'br', 'hr', 'meta', 'link', 'input', 'source', 'use', 'path', 'rect', 'circle', 'area', 'wbr'}
    def handle_starttag(s, t, a):
        a = dict(a)
        if 'id' in a: s.ids.add(a['id'])
        if t == 'a' and a.get('href'): s.hrefs.append(a['href'])
        if t in ('img', 'source') and (a.get('src') or a.get('srcset')):
            for part in (a.get('srcset') or a.get('src')).split(','):
                s.srcs.append(part.strip().split(' ')[0])
        if t == 'link' and a.get('rel') in ('stylesheet', 'preload') and a.get('href'): s.srcs.append(a['href'])
        if t == 'script' and a.get('src'): s.srcs.append(a['src'])
        if t == 'h1': s.h1 += 1
        if t == 'script' and a.get('type') == 'application/ld+json': s._ld = ''
        if t not in s.void:
            s.stack.append((t, 'data-expires' in a))
    def handle_endtag(s, t):
        if t == 'script' and s._ld is not None: s.ld.append(s._ld); s._ld = None
        for i in range(len(s.stack) - 1, -1, -1):
            if s.stack[i][0] == t: del s.stack[i:]; break
    def handle_data(s, d):
        if s._ld is not None: s._ld += d; return
        if any(t in ('script', 'style') for t, _ in s.stack): return
        if re.search(r'夏割|9/30|9月30日|夏のLINE特典', d) and not any(e for _, e in s.stack):
            s.text_noexp.append(d.strip()[:60])


def check(name):
    path = name if name.endswith('/') else name + '/'
    f = os.path.join(SITE, path, 'index.html')
    src = open(f, encoding='utf-8').read()
    ng, warn = [], []
    p = P(); p.feed(src)
    # 禁止語
    for w in FORBID:
        if w in src: ng.append(f'禁止語/誤り: {w}')
    if '080-4350-0412' in src and path not in PHONE_OK: ng.append('電話番号は contact/tokusho 以外に出さない')
    # h1
    if p.h1 != 1: ng.append(f'h1 が {p.h1} 個')
    # title / description
    t = re.search(r'<title>(.*?)</title>', src, re.S).group(1)
    d = re.search(r'<meta name="description"\s+content="(.*?)">', src, re.S)
    if not (24 <= len(t) <= 46): warn.append(f'title {len(t)}字: {t}')
    if not d: ng.append('description なし')
    elif not (80 <= len(d.group(1)) <= 140): warn.append(f'description {len(d.group(1))}字')
    # JSON-LD
    for j in p.ld:
        try: json.loads(j)
        except Exception as e: ng.append(f'JSON-LD 壊れ: {e}')
    # リンク・画像
    for h in p.hrefs:
        if h.startswith('#'):
            if h[1:] and h[1:] not in p.ids: ng.append(f'ページ内リンク先なし: {h}')
        elif h.startswith('/') and not h.startswith('//'):
            u = h.split('#')[0].split('?')[0]
            if u.startswith('/blog/'): continue
            loc = os.path.join(SITE, u.lstrip('/'))
            if not (os.path.isfile(loc) or os.path.isfile(os.path.join(loc, 'index.html'))): ng.append(f'リンク切れ: {h}')
        elif h.startswith('https://miura-diving.com/'):
            u = h[len('https://miura-diving.com/'):].split('#')[0].split('?')[0]
            if u.startswith('blog/') or u == '': continue
            loc = os.path.join(SITE, u)
            if not (os.path.isfile(loc) or os.path.isfile(os.path.join(loc, 'index.html'))): ng.append(f'リンク切れ: {h}')
        elif h.startswith('../') or (not re.match(r'^(https?:|mailto:|tel:|/|#)', h)):
            warn.append(f'相対リンク（/ から書く）: {h}')
    for s in p.srcs:
        if s.startswith('http') or s.startswith('data:'): continue
        u = s.split('?')[0]
        loc = os.path.join(SITE, u.lstrip('/')) if u.startswith('/') else os.path.normpath(os.path.join(SITE, path, u))
        if not os.path.isfile(loc): ng.append(f'画像/ファイルなし: {s}')
    # 期限つき
    for x in p.text_noexp: warn.append(f'data-expires の外に期限つきの文言: 「{x}」')
    # 金額
    body = re.sub(r'<script.*?</script>', '', src, flags=re.S)
    for m in re.finditer(r'¥\s?([0-9,]{3,})', body):
        v = int(m.group(1).replace(',', ''))
        if v not in PRICES: warn.append(f'FACTSに無い金額: ¥{m.group(1)}（前後: {body[max(0,m.start()-30):m.end()+10]!r}）')
    # 絵文字（本文のアイコン代わり）
    main = re.search(r'<main.*?</main>', src, re.S)
    if main and re.search(r'[\U0001F300-\U0001FAFF]', main.group(0)): warn.append('本文に絵文字がある')
    print(f'== {path}  NG {len(ng)} / WARN {len(warn)}')
    for x in ng: print('  NG  ', x)
    for x in sorted(set(warn)): print('  WARN', x)
    return len(ng)


if __name__ == '__main__':
    bad = sum(check(n) for n in sys.argv[1:])
    sys.exit(1 if bad else 0)
