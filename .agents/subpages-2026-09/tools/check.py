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
    26400, 31900, 38500,  # ボート2本＋ビーチ1本／その込み／ボート2本＋ビーチ2本の込み（2026-10-16）
    3500, 23300, 28800, 32100, 37600, 29900, 36500, 22500, 16700, 74300, 134200, 63300,  # 城ヶ島のボート＝宮川湾＋¥3,500（2026-11-19）・レンタル込み・つづける割・バディ割
    13750, 24750,  # マイドライ割（ドライSP半額・OWD受講者の特典の合計）
    133650, 150150, 161150, 143000, 170500, 215600, 166650, 17600,  # 冬スキル割（組み方の例の講習料・合計と、6つ目の差額）
    # 海の回数券（2026-11・正本 .agents/kaisuken-campaign.md）：券の値段／1回あたり／通常（1回¥13,200）との差／城ヶ島のボートの差額（返金の計算の例 ¥42,600 は 2026-11-19 にやめた）
    36000, 69000, 138000, 12000, 11500, 3600, 10200, 20400, 10100,
}
# /gear/ のメーカー希望小売価格・公式工賃（FACTS.md「器材の販売」。/gear/ だけで通す＝ほかのページに出たら今までどおり WARN）
PRICES_GEAR = {
    17600, 10450, 48400, 8250, 69300, 162800, 164560, 459800, 84700, 224400, 82500, 31900, 73700,
    110000, 228800, 52800, 248000, 4950, 58080, 19250, 23100,
    # 講習・レンタルと同じ数字も、/gear/ に出るメーカーの金額はここに書く（PRICES から外しても /gear/ が WARN にならないように）
    6600, 8800, 11000, 13200, 13750, 18700, 22000, 27500, 29700, 34100, 44000,
    3000, 500000,  # 価格の帯の物差しの両端（¥3,000〜¥500,000。商品の金額ではない）
}
FORBID = ['諸磯', '日本初', '唯一の', 'No.1', 'ナンバーワン', '業界最安', '絶対に安全', '必ず見られ', '数千人', '神奈川県内でも数少ない',
          '品川から約60分', '品川から60分', '品川60分', '横浜から約45分', '横浜から45分', '45分', '約60分', '60〜75分',
          '冬季や習熟度', '冬は3日', '冬は３日', '最短3日', '基本3日間', '実技3日間',
          'AggregateRating', 'font-awesome', 'class="fa ', 'class="fas', 'class="fab', 'wp-content/uploads']
# /gear/ だけの禁止語（<main> の中と JSON-LD を見る。ヘッダー・フッターは見ない）。オーナーの決定と法令の線（2026-10-11）
# 「お求めやすい」は入れない（「メーカー希望小売価格より、お求めやすい価格でご案内しています。」をオーナーが選んだ）
FORBID_GEAR = ['定価', '試着', '試し使い', '海で試せ', 'お得', '元が取れ', '一生もの', '寒くない', 'ご来店', '店頭', '国産', '8メーカー', '海学', '同じ人',
               '注文を受けません', 'その場では売りません', '帰ってから', 'お受けしていません', '切り出しません', '講習中はすすめません',
               '8日以内', 'A案', 'B案', '【G', '〔返品〕', '🔒']


# 海の回数券（/kaisuken/）。決まりの正本は .agents/kaisuken-campaign.md。値段を変えたら、正本 → ここ → ページの順に直す
KAISUKEN_MUST = ['¥36,000', '¥69,000', '¥138,000', '¥12,000', '¥11,500', '¥13,200', '¥5,500']  # /kaisuken/ の <main> に、全部出ていること
KAISUKEN_TICKETS = {'3': 36000, '6': 69000, '12': 138000}  # 「N回券」のすぐあとに出る値段と、JSON-LD の Offer の price
KAISUKEN_PER = {'3': 12000, '6': 11500, '12': 11500}        # 券の札（#ticket-N）の「1回あたり」
# 言い方の決まり（正本の5）。「お得」は見出しに書かない。ほかは <main> と JSON-LD のどこにも書かない
FORBID_KAISUKEN_HEAD = ['お得']
FORBID_KAISUKEN = ['最安', '業界初', '人気No.1', '必ず上達', '貯金', '積立', 'チャージ', 'いつでも全額返金', '計算し直', 'サブスク', '会員']


def strip_tags(x):
    return re.sub(r'\s+', ' ', html.unescape(re.sub(r'<[^>]+>', '', x)))


def check_kaisuken(path, src, lds, ng):
    """回数券の値段の検査。/kaisuken/ は全部の値段と言い方、ほかのページは「N回券」のすぐあとの値段だけを見る。"""
    m_ = re.search(r'<main.*?</main>', src, re.S)
    main = m_.group(0) if m_ else src
    text = strip_tags(re.sub(r'<(script|style)\b.*?</\1>', '', src, flags=re.S))
    # 「3回券 ¥36,000」「6回券（¥69,000）」「12回券 138,000円」… 券の名前のすぐあとの値段が、正本と同じか（どのページでも）
    for m in re.finditer(r'(?<![0-9])(3|6|12)回券[^0-9¥]{0,12}¥?\s?([0-9]{1,3}(?:,[0-9]{3})+)', text):
        v = int(m.group(2).replace(',', ''))
        if v != KAISUKEN_TICKETS[m.group(1)]:
            ng.append(f'回数券の値段が正本と違う: {m.group(0)!r}（{m.group(1)}回券は ¥{KAISUKEN_TICKETS[m.group(1)]:,}）')
    if path != 'kaisuken/':
        if '回数券' in main and '/kaisuken/' not in main: ng.append('回数券の話があるのに /kaisuken/ へのリンクがない')
        return
    for w in KAISUKEN_MUST:
        if w not in main: ng.append(f'/kaisuken/ に値段が出ていない: {w}')
    for n, per in KAISUKEN_PER.items():
        card = re.search(r'id="ticket-%s".*?</li>\s*(?=<li class="kt|</ul>)' % n, main, re.S)
        ct = strip_tags(card.group(0)) if card else ''
        if f'¥{KAISUKEN_TICKETS[n]:,}' not in ct: ng.append(f'券の札 #ticket-{n} に ¥{KAISUKEN_TICKETS[n]:,} がない')
        if f'¥{per:,}' not in ct: ng.append(f'券の札 #ticket-{n} の「1回あたり」が ¥{per:,} でない')
        if f'data-ticket="{n}"' not in main: ng.append(f'申し込みボタンの data-ticket="{n}" がない')
        if f'<!-- STRIPE:{n} -->' not in main: ng.append(f'決済リンクの目印 <!-- STRIPE:{n} --> がない')
    offers = {}
    def walk(o):
        if isinstance(o, dict):
            if o.get('@type') == 'Offer' and 'price' in o: offers[o.get('name', '')] = str(o['price'])
            for v in o.values(): walk(v)
        elif isinstance(o, list):
            for v in o: walk(v)
    for j in lds:
        try: walk(json.loads(j))
        except Exception: pass
    for n, price in KAISUKEN_TICKETS.items():
        got = [v for k, v in offers.items() if re.search(r'(?<![0-9])%s回券' % n, k)]
        if got != [str(price)]: ng.append(f'JSON-LD の Offer（{n}回券）の price が {price} でない: {got}')
    heads = ' '.join(re.findall(r'<(?:title|h1|h2|h3|summary)\b[^>]*>(.*?)</(?:title|h1|h2|h3|summary)>', src, re.S))
    for w in FORBID_KAISUKEN_HEAD:
        if w in strip_tags(heads): ng.append(f'/kaisuken/ の見出しに書かない言葉: {w}')
    scope = main + '\n'.join(lds)
    for w in FORBID_KAISUKEN:
        if w in scope: ng.append(f'/kaisuken/ に書かない言葉: {w}')


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
    if name in ('home', '.'): path = ''   # トップ（手書きの index.html）。回数券の値段など、一部の検査だけ当てにする
    f = os.path.join(SITE, path, 'index.html')
    src = open(f, encoding='utf-8').read()
    ng, warn = [], []
    p = P(); p.feed(src)
    # 禁止語
    for w in FORBID:
        if w in src: ng.append(f'禁止語/誤り: {w}')
    # ライセンス講習の練習はプール（1日目の午前）。「浅場で練習」は体験ダイビングの話なので、ライセンス系のページにだけ禁止（2026-10-09）
    if path in ('license/', 'tokyo-diving-license/', 'yokohama-diving-license/', 'kanagawa-diving-license/'):
        for w in ('足の着く穏やかな場所で', '浅いところで練習'):
            if w in src: ng.append(f'ライセンスの練習場所はプール: {w}')
    if '080-4350-0412' in src and path not in PHONE_OK: ng.append('電話番号は contact/tokusho 以外に出さない')
    if path == 'gear/':
        m_ = re.search(r'<main.*?</main>', src, re.S)
        scope = (m_.group(0) if m_ else src) + '\n'.join(p.ld)
        for w in FORBID_GEAR:
            if w in scope: ng.append(f'/gear/ に書かない言葉: {w}')
    # 海の回数券（値段は正本どおりか）。/kaisuken/ と、回数券の話が出るページ
    if path == 'kaisuken/' or '回数券' in src: check_kaisuken(path, src, p.ld, ng)
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
        if v not in PRICES and not (path == 'gear/' and v in PRICES_GEAR): warn.append(f'FACTSに無い金額: ¥{m.group(1)}（前後: {body[max(0,m.start()-30):m.end()+10]!r}）')
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
