#!/usr/bin/env python3
"""ブログの記事を集めて、図鑑の生き物の名前が出てくる記事を探すための控えを作る。

  python3 .agents/subpages-2026-09/tools/sealife_blog.py          # 新しい記事・更新された記事だけ取る
  python3 .agents/subpages-2026-09/tools/sealife_blog.py --all    # 全部取り直す

- 記事の一覧は https://miura-diving.com/blog/sitemap.xml（一覧・カテゴリのページは除く）。
- 取得は1秒あける。失敗しても止まらない（失敗した記事は前回の控えを残す）。
- 控え：~/Documents/海学HP.php/.agents/seo/data/sealife/blog-cache.json（git の外。記事ごとに題名・公開日・lastmod・本文）。
  環境変数 SEALIFE_BLOG_CACHE で場所を変えられる。名前の照合は build_sealife.py が控えから毎回やり、
  結果（種ごとの url・題名・日付・当たった言葉だけ・本文なし）を tools/sealife-mentions.json に書く。
"""
import html
import json
import os
import re
import sys
import time
import urllib.request

HERE = os.path.dirname(os.path.abspath(__file__))
# 本文入りの控えは公開リポジトリに入れない（git の外）。リポジトリ側の sealife-mentions.json は build_sealife.py が種ごとの照合結果だけを書く
CACHE = os.environ.get('SEALIFE_BLOG_CACHE') or os.path.expanduser('~/Documents/海学HP.php/.agents/seo/data/sealife/blog-cache.json')
SITEMAP = 'https://miura-diving.com/blog/sitemap.xml'
UA = 'Mozilla/5.0 (compatible; umigaku-sealife-builder/1.0; +https://miura-diving.com/)'


def get(url):
    req = urllib.request.Request(url, headers={'User-Agent': UA})
    with urllib.request.urlopen(req, timeout=30) as r:
        return r.read().decode('utf-8', 'replace')


def text_of(page):
    # 本文だけ（記事の下の相談の帯・書いた人・関連記事・前後の記事は除く）
    m = re.search(r'<div class="prose"(.*?)(?:<footer class="post-foot"|</article>)', page, flags=re.S)
    body = m.group(1) if m else page
    body = re.sub(r'<(script|style)[^>]*>.*?</\1>', ' ', body, flags=re.S)
    body = re.sub(r'<[^>]+>', ' ', body)
    return re.sub(r'\s+', ' ', html.unescape(body)).strip()


def main():
    refetch_all = '--all' in sys.argv
    cache = {}
    if os.path.exists(CACHE):
        cache = json.load(open(CACHE, encoding='utf-8')).get('posts', {})
    sm = get(SITEMAP)
    urls = re.findall(r'<url><loc>([^<]+)</loc>(?:<lastmod>([^<]*)</lastmod>)?', sm)
    posts = [(u, lm) for u, lm in urls
             if re.search(r'/blog/[^/]+/$', u) and not re.search(r'/blog/(archive|category|page|tags)/', u)]
    got = skipped = failed = 0
    for u, lm in posts:
        old = cache.get(u)
        if old and old.get('lastmod') == lm and not refetch_all and old.get('text'):
            skipped += 1
            continue
        time.sleep(1)
        try:
            page = get(u)
        except Exception as e:  # 失敗しても止まらない
            print('失敗', u, e)
            failed += 1
            continue
        t = re.search(r'<title>(.*?)</title>', page, flags=re.S)
        title = html.unescape(t.group(1)).strip() if t else u
        title = re.sub(r'\s*[｜|]\s*三浦 海の学校.*$', '', title)
        d = re.search(r'"datePublished"\s*:\s*"([^"]+)"', page)
        cache[u] = {'title': title, 'date': (d.group(1)[:10] if d else (lm or '')),
                    'lastmod': lm, 'text': text_of(page)}
        got += 1
        print('取得', u)
    # サイトマップから消えた記事（転送・検索除外にした記事）は控えからも外す
    live = {u for u, _ in posts}
    removed = [u for u in cache if u not in live]
    for u in removed:
        del cache[u]
    out = {'source': SITEMAP, 'note': '本文のテキストは名前の照合用。公開しない（.agents 配下はサーバーに上げない）。',
           'posts': {u: cache[u] for u in sorted(cache)}}
    os.makedirs(os.path.dirname(CACHE), exist_ok=True)
    json.dump(out, open(CACHE, 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
    print(f'記事 {len(posts)}本：取得 {got}・前回のまま {skipped}・失敗 {failed}・外した {len(removed)}')


if __name__ == '__main__':
    main()
