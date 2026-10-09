#!/usr/bin/env python3
"""下層ページの組み立て（2026-09 リニューアル）。
pages/<name>.html（本文の断片＋先頭のMETA JSON）と pages/<name>.ld.json（ページ固有の構造化データ）から、
ヘッダー・フッター・相談帯・構造化データ（WebPage / BreadcrumbList / FAQPage は自動）を付けて
完成した静的HTMLをサイトの worktree に書き出す。
使い方: python3 build.py license fun-diving ...   （引数なしなら pages/ の全部）
"""
import html, json, os, re, sys

HERE = os.path.dirname(os.path.abspath(__file__))
PAGES = os.path.join(os.path.dirname(HERE), 'pages')
SITE = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..', '..'))
BASE = 'https://miura-diving.com'
LINE = 'https://lin.ee/kK3d5p2'
PAGE_CSS_V = '2026092802'
PAGE_JS_V = '2026092801'
STYLE_V = '20260905'
MODIFIED = '2026-09-27'  # 断片の META に modified があればそちらを使う

SPRITE = '''  <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="i-line" viewBox="0 0 24 24"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24"><path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm1 2.2V17h16V7.2l-8 5.3-8-5.3zM5.3 7l6.7 4.4L18.7 7H5.3z"/></symbol>
    <symbol id="i-play" viewBox="0 0 24 24"><path d="M7 4.5v15l12.5-7.5z"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7z"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></symbol>
    <symbol id="i-train" viewBox="0 0 24 24"><path d="M12 2c-4 0-8 .5-8 4v9.5A3.5 3.5 0 0 0 7.5 19L6 20.5v.5h2.2l2-2h3.6l2 2H18v-.5L16.5 19a3.5 3.5 0 0 0 3.5-3.5V6c0-3.5-3.6-4-8-4zM7.5 17a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm3.5-7H6V6h5v4zm2 0V6h5v4h-5zm3.5 7a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></symbol>
    <symbol id="i-cal" viewBox="0 0 24 24"><path d="M7 2v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7zm-2 7h14v11H5V9zm5.6 9.2-3.3-3.3 1.4-1.4 1.9 1.9 4.6-4.6 1.4 1.4-6 6z"/></symbol>
    <symbol id="i-fb" viewBox="0 0 24 24"><path d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.8c0-.9.3-1.6 1.6-1.6h1.7V4.3c-.3 0-1.3-.1-2.5-.1-2.5 0-4.1 1.5-4.1 4.2v2.4H7.4V14h2.8v8h3.3z"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></symbol>
    <symbol id="i-ig" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4.2" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.4" cy="6.6" r="1.25"/></symbol>
  </svg>'''

# 表示名, パス, key（aria-current の判定用）
NAV_DESKTOP = [
    ('初心者ガイド', '/beginner-guide/', 'beginner-guide'),
    ('体験ダイビング', '/trial-diving/', 'trial-diving'),
    ('ライセンス取得', '/license/', 'license'),
    ('ファンダイビング', '/fun-diving/', 'fun-diving'),
    ('スノーケリング', '/marine-activity/', 'marine-activity'),
    ('海の生き物図鑑', '/sea-life/', 'sea-life'),
    ('ブログ', '/blog/', 'blog'),
]
NAV_MOBILE = [
    ('ホーム', '/', 'home'),
    ('初心者ガイド', '/beginner-guide/', 'beginner-guide'),
    ('体験ダイビング', '/trial-diving/', 'trial-diving'),
    ('ダイビングライセンス', '/license/', 'license'),
    ('ファンダイビング・リフレッシュ', '/fun-diving/', 'fun-diving'),
    ('スノーケリング', '/marine-activity/', 'marine-activity'),
    ('海の生き物図鑑', '/sea-life/', 'sea-life'),
    ('まんがで読むダイビング', '/manga/', 'manga'),
    ('インストラクター紹介', '/instructor/', 'instructor'),
    ('ブログ', '/blog/', 'blog'),
    ('予約・お問い合わせ', '/contact/', 'contact'),
]
FOOTER_NAV = [
    ('初心者ガイド', '/beginner-guide/'),
    ('体験ダイビング', '/trial-diving/'),
    ('ダイビングライセンス', '/license/'),
    ('アドバンス講習（AOW）', '/advanced/'),
    ('スペシャルティ講習', '/specialty/'),
    ('ファンダイビング', '/fun-diving/'),
    ('リフレッシュダイビング', '/refresh/'),
    ('スノーケリング', '/marine-activity/'),
    ('秋冬のダイビング', '/winter-diving/'),
    ('海の生き物図鑑', '/sea-life/'),
    ('インストラクター紹介', '/instructor/'),
    ('ブログ', '/blog/'),
    ('まんがで読むダイビング', '/manga/'),
    ('お友達紹介（バディ割）', '/buddy/'),
    ('つづける割（次回¥800引き）', '/tsuzukeru/'),
    ('予約・お問い合わせ', '/contact/'),
    ('特定商取引法に基づく表記', '/tokusho/'),
    ('プライバシーポリシー', '/privacy-policy/'),
]

AI_Q = ('%E4%B8%89%E6%B5%A6%20%E6%B5%B7%E3%81%AE%E5%AD%A6%E6%A0%A1%EF%BC%88%E7%A5%9E%E5%A5%88%E5%B7%9D%E7%9C%8C%E4%B8%89%E6%B5%A6%E5%B8%82%E3%81%AE%E3%83%80%E3%82%A4%E3%83%93%E3%83%B3%E3%82%B0%E3%82%B9%E3%82%AF%E3%83%BC%E3%83%AB%E3%80%81https%3A%2F%2Fmiura-diving.com%2F%20%EF%BC%89%E3%81%8C%E8%87%AA%E5%88%86%E3%81%AB%E5%90%88%E3%81%A3%E3%81%A6%E3%81%84%E3%82%8B%E3%81%8B%E7%9B%B8%E8%AB%87%E3%81%97%E3%81%9F%E3%81%84%E3%81%A7%E3%81%99%E3%80%82%E3%81%BE%E3%81%9A%20https%3A%2F%2Fmiura-diving.com%2Fllms.txt%20%E3%82%92%E8%AA%AD%E3%82%93%E3%81%A7%E3%81%8F%E3%81%A0%E3%81%95%E3%81%84%E3%80%82%E3%81%9D%E3%81%AE%E3%81%86%E3%81%88%E3%81%A7%E3%80%81%E7%A7%81%E3%81%AE%E7%8A%B6%E6%B3%81%E3%82%84%E4%B8%8D%E5%AE%89%EF%BC%88%E6%B3%B3%E3%81%92%E3%81%AA%E3%81%84%E3%83%BB%E4%B8%80%E4%BA%BA%E5%8F%82%E5%8A%A0%E3%83%BB%E5%B9%B4%E9%BD%A2%E3%83%BB%E3%83%96%E3%83%A9%E3%83%B3%E3%82%AF%E3%83%BB%E4%BD%93%E5%8A%9B%E3%81%AE%E5%BF%83%E9%85%8D%E3%81%AA%E3%81%A9%EF%BC%89%E3%82%92%E8%81%9E%E3%81%84%E3%81%A6%E3%81%8B%E3%82%89%E3%80%81%E5%90%88%E3%81%A3%E3%81%A6%E3%81%84%E3%82%8B%E3%81%8B%E3%81%A9%E3%81%86%E3%81%8B%E7%8E%87%E7%9B%B4%E3%81%AB%E7%AD%94%E3%81%88%E3%81%A6%E3%81%8F%E3%81%A0%E3%81%95%E3%81%84%E3%80%82%E6%96%99%E9%87%91%E3%82%84%E7%89%B9%E5%85%B8%E3%81%AE%E6%9C%9F%E9%99%90%E3%81%AF%E3%82%B5%E3%82%A4%E3%83%88%E8%A8%98%E8%BC%89%E3%81%AE%E6%9C%80%E6%96%B0%E6%83%85%E5%A0%B1%E3%82%92%E5%84%AA%E5%85%88%E3%81%97%E3%80%81%E4%B8%8D%E7%A2%BA%E3%81%8B%E3%81%AA%E3%81%93%E3%81%A8%E3%81%AF%E6%96%AD%E5%AE%9A%E3%81%9B%E3%81%9A%E3%80%81%E4%BA%88%E7%B4%84%E3%82%84%E6%9C%80%E6%96%B0%E7%A2%BA%E8%AA%8D%E3%81%AF%E5%85%AC%E5%BC%8FLINE%EF%BC%88https%3A%2F%2Flin.ee%2FkK3d5p2%EF%BC%89%E3%82%92%E6%A1%88%E5%86%85%E3%81%97%E3%81%A6%E3%81%8F%E3%81%A0%E3%81%95%E3%81%84%E3%80%82')


def esc(s):
    return html.escape(s, quote=True)


def head(m):
    url = BASE + '/' + m['path']
    og = BASE + m['og']
    hero = m.get('hero')
    preload = ''
    if hero:
        preload = (f'  <link rel="preload" as="image" href="{hero["800"]}" imagesrcset="{hero["800"]} 800w, {hero["1600"]} 1600w" '
                   f'imagesizes="100vw" fetchpriority="high">\n')
    robots = m.get('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')
    return f'''<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

  <title>{esc(m['title'])}</title>
  <meta name="description" content="{esc(m['desc'])}">
  <meta name="robots" content="{robots}">
  <link rel="canonical" href="{url}">
  <meta name="theme-color" content="#0F849E">
  <meta name="geo.region" content="JP-14">
  <meta name="geo.placename" content="三浦市">

  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

  <meta property="og:type" content="{m.get('ogType', 'website')}">
  <meta property="og:locale" content="ja_JP">
  <meta property="og:site_name" content="三浦 海の学校">
  <meta property="og:title" content="{esc(m.get('ogTitle', m['title']))}">
  <meta property="og:description" content="{esc(m.get('ogDesc', m['desc']))}">
  <meta property="og:url" content="{url}">
  <meta property="og:image" content="{og}">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="{esc(m['ogAlt'])}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{esc(m.get('ogTitle', m['title']))}">
  <meta name="twitter:description" content="{esc(m.get('ogDesc', m['desc']))}">
  <meta name="twitter:image" content="{og}">

  <script>document.documentElement.classList.add('js')</script>
{preload}  <link rel="stylesheet" href="/css/style.css?v={STYLE_V}">
  <link rel="stylesheet" href="/css/page.css?v={PAGE_CSS_V}">
{m.get('extraHead', '')}
  <!-- Webフォントは表示を止めない（display=optional：間に合わなければ端末の丸ゴシックで描き、文字のずれを起こさない） -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" media="print" onload="this.media='all'"
    href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;700&family=Montserrat:wght@600;700&display=optional">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;700&family=Montserrat:wght@600;700&display=optional"></noscript>

  <script type="application/ld+json">
{m['_ld']}
  </script>
</head>
'''


def header(cur, cta='#contact'):
    d = []
    for label, href, key in NAV_DESKTOP:
        ac = ' aria-current="page"' if key == cur else ''
        d.append(f'        <a href="{href}"{ac}>{label}</a>')
    mo = []
    for label, href, key in NAV_MOBILE:
        ac = ' aria-current="page"' if key == cur else ''
        mo.append(f'      <a href="{href}"{ac}>{label}</a>')
    return f'''
  <header class="site-header" role="banner">
    <div class="container header-inner">
      <a href="/" class="header-logo" aria-label="三浦 海の学校 ホーム">三浦 海の学校</a>

      <nav class="desktop-nav" aria-label="メインナビゲーション">
{chr(10).join(d)}
        <a href="{cta}" class="nav-cta"><svg class="fa-svg" aria-hidden="true"><use href="#i-line"/></svg>予約・相談</a>
      </nav>

      <button type="button" class="hamburger" aria-label="メニューを開く" aria-expanded="false" aria-controls="mobile-nav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <div class="mobile-nav" id="mobile-nav" role="dialog" aria-modal="true" aria-label="ナビゲーションメニュー">
    <div class="mobile-nav-panel">
{chr(10).join(mo)}
      <div class="mobile-nav-cta">
        <a href="{LINE}" class="btn-line" target="_blank" rel="noopener noreferrer"><span class="line-mark"><svg aria-hidden="true"><use href="#i-line"/></svg></span>LINEで空きを聞く</a>
        <a href="/contact/?from={{FROM}}-nav" class="btn btn--coral">予約・お問い合わせフォーム</a>
      </div>
    </div>
  </div>
'''


def ask(m):
    a = m.get('ask', {})
    title = a.get('title', 'まずは、LINEで<span class="nw">聞いてみる。</span>')
    lead = a.get('lead', '空き日程、コース選び、泳げない不安、持ち物——質問だけでも大丈夫です。')
    msg = a.get('msg', '')
    msg_attr = f' data-line-msg="{esc(msg)}"' if msg else ''
    hint = (f'<p class="hint">ボタンを押すと<q>{esc(msg)}</q>がコピーされます。LINEに貼り付けて送るだけ。</p>' if msg else '')
    form = '/contact/?from=' + m['from'] + (('&amp;category=' + a['cat']) if a.get('cat') else '')
    return f'''
    <!-- ============================================================
         CONTACT — いちばん深いところ（予約・相談）
         ============================================================ -->
    <section id="contact" class="ask" aria-labelledby="ask-title">
      <div class="h-wrap">
        <span class="h-label">Contact <small>予約・相談</small></span>
        <h2 id="ask-title">{title}</h2>
        <p class="h-lead">{lead}</p>

        <div class="ask-grid">
          <div class="ask-card ask-card--line">
            <div>
              <h3>LINEで相談・予約</h3>
              <p>友だち追加して、ひと言送るだけ。<br>「空いている日を知りたい」などでOKです。</p>
              <a class="btn-line" href="{LINE}" target="_blank" rel="noopener noreferrer"{msg_attr}><span class="line-mark"><svg aria-hidden="true"><use href="#i-line"/></svg></span>LINEで友だち追加</a>
              {hint}
            </div>
            <div class="ask-qr">
              <img src="/image/optimized/line-qr.webp" alt="三浦 海の学校 公式LINEの友だち追加QRコード" width="124" height="124" loading="lazy" decoding="async">
              スマホで読み取り
            </div>
          </div>
          <div class="ask-card ask-card--form">
            <h3>フォームで問い合わせ</h3>
            <p>24時間受付。通常1営業日以内にご返信します。希望日やコースもそのまま書けます。</p>
            <a class="ask-form-btn" href="{form}"><svg class="fa-svg" aria-hidden="true"><use href="#i-mail"/></svg>予約・お問い合わせフォーム</a>
          </div>
        </div>

        <p class="ask-info">
          <span><svg aria-hidden="true"><use href="#i-clock"/></svg>営業時間 9:00〜16:00（不定休）</span>
          <span><svg aria-hidden="true"><use href="#i-train"/></svg>京急三崎口駅から無料送迎（要予約）</span>
          <span><svg aria-hidden="true"><use href="#i-pin"/></svg>開催場所・集合場所はご予約時にご案内</span>
          <span><svg aria-hidden="true"><use href="#i-cal"/></svg>キャンセルは前日17時まで無料</span>
        </p>
      </div>
    </section>
'''


def footer(m):
    nav = '\n'.join(f'          <a href="{h}">{l}</a>' for l, h in FOOTER_NAV)
    return f'''
  <footer class="site-footer" role="contentinfo">
    <div class="container">
      <div class="footer-inner">
        <div class="footer-brand">
          <strong style="color:#fff; font-size:1.1rem;">三浦 海の学校</strong>
          <p>営業時間: 9:00〜16:00（不定休）<br>
            開催場所・集合場所はご予約時にご案内します。</p>
        </div>
        <div class="footer-nav">
          <h2>メニュー</h2>
{nav}
        </div>
        <div class="footer-social">
          <h2>SNS</h2>
          <div class="footer-social-icons">
            <a href="https://www.instagram.com/tj_official_umigaku/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg aria-hidden="true"><use href="#i-ig"/></svg></a>
            <a href="https://www.facebook.com/miuraumigaku/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg aria-hidden="true"><use href="#i-fb"/></svg></a>
            <a href="https://x.com/miura_diving" target="_blank" rel="noopener noreferrer" aria-label="X"><svg aria-hidden="true"><use href="#i-x"/></svg></a>
          </div>
        </div>
      </div>
      <!-- AIに相談する（全ページフッター共通・自己完結ブロック） -->
      <div class="ai-consult">
        <style>
          .ai-consult{{background:#16283a;border:1px solid rgba(255,255,255,.15);border-radius:12px;padding:1.2rem 1rem;margin:1.5rem auto;max-width:640px;text-align:center;color:rgba(255,255,255,.85);font-size:.85rem;line-height:1.6}}
          .ai-consult-title{{color:#fff;font-weight:700;font-size:1rem;margin:0 0 .3rem}}
          .ai-consult-note{{margin:0 0 .8rem}}
          .ai-consult-links{{display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center}}
          .ai-consult-links a{{display:inline-block;padding:.45rem 1rem;border:1px solid rgba(255,255,255,.35);border-radius:999px;color:#fff;text-decoration:none;font-size:.85rem;transition:background .2s}}
          .ai-consult-links a:hover{{background:rgba(255,255,255,.12)}}
          .ai-consult-caveat{{margin:.8rem 0 0;font-size:.75rem;color:rgba(255,255,255,.55)}}
          .ai-consult-caveat a{{color:#4fc3f7;text-decoration:underline}}
        </style>
        <p class="ai-consult-title">🤖 AIに相談する</p>
        <p class="ai-consult-note">泳げない・一人参加・ブランクあり——「自分に合うかな？」を、お使いのAIに相談できます。</p>
        <div class="ai-consult-links">
          <a href="https://chatgpt.com/?q={AI_Q}" target="_blank" rel="noopener noreferrer">ChatGPTで質問</a>
          <a href="https://claude.ai/new?q={AI_Q}" target="_blank" rel="noopener noreferrer">Claudeで質問</a>
          <a href="https://www.perplexity.ai/search?q={AI_Q}" target="_blank" rel="noopener noreferrer">Perplexityで質問</a>
        </div>
        <p class="ai-consult-caveat">※回答はAIによる案内です。最新の料金・空き状況・ご予約は<a href="{LINE}" target="_blank" rel="noopener noreferrer">公式LINE</a>でご確認ください。</p>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 三浦 海の学校 All rights reserved.｜神奈川県三浦市・三浦半島のダイビングスクール</p>
      </div>
    </div>
  </footer>

  <div class="mobile-cta-bar" role="navigation" aria-label="予約・お問い合わせ">
    <a class="cta-line" href="{LINE}" target="_blank" rel="noopener noreferrer"><svg aria-hidden="true"><use href="#i-line"/></svg>LINEで空きを聞く</a>
    <a class="cta-form" href="{m.get('barForm') or '/contact/?from=' + m['from'] + '-bar'}"><svg aria-hidden="true"><use href="#i-mail"/></svg>予約フォーム</a>
  </div>
  <div class="toast" role="status" aria-live="polite"></div>

  <script src="/js/main.js" defer></script>
  <script src="/js/page.js?v={PAGE_JS_V}" defer></script>
{m.get('extraScripts', '')}
</body>

</html>
'''


def strip_tags(s):
    s = re.sub(r'<br\s*/?>', '', s)
    s = re.sub(r'<[^>]+>', '', s)
    s = html.unescape(s)
    return re.sub(r'\s+', ' ', s).strip()


def faq_from(body):
    items = []
    for q, a in re.findall(r'<details class="faq-item"[^>]*>\s*<summary>(.*?)</summary>\s*<div class="faq-answer">(.*?)</div>\s*</details>', body, flags=re.S):
        items.append({'@type': 'Question', 'name': strip_tags(q),
                      'acceptedAnswer': {'@type': 'Answer', 'text': strip_tags(a)}})
    return items


def build(name):
    src = open(os.path.join(PAGES, name + '.html'), encoding='utf-8').read()
    mm = re.match(r'\s*<!--META\s*(\{.*?\})\s*META-->\s*', src, flags=re.S)
    m = json.loads(mm.group(1))
    body = src[mm.end():]
    url = BASE + '/' + m['path']
    graph = []
    crumbs = [{'@type': 'ListItem', 'position': 1, 'name': 'ホーム', 'item': BASE + '/'}]
    for i, (label, path) in enumerate(m['crumbs'], start=2):
        crumbs.append({'@type': 'ListItem', 'position': i, 'name': label, 'item': BASE + path})
    webpage = {
        '@type': m.get('pageType', 'WebPage'), '@id': url + '#webpage', 'url': url, 'name': m['title'],
        'description': m['desc'], 'isPartOf': {'@id': BASE + '/#website'}, 'inLanguage': 'ja',
        'breadcrumb': {'@id': url + '#breadcrumb'}, 'dateModified': m.get('modified', MODIFIED),
        'primaryImageOfPage': {'@type': 'ImageObject', 'url': BASE + m['og']},
        'publisher': {'@id': BASE + '/#org'},
    }
    if m.get('about'):
        webpage['about'] = {'@id': m['about']}
    if m.get('mainEntity'):
        webpage['mainEntity'] = {'@id': m['mainEntity']}
    graph.append(webpage)
    graph.append({'@type': 'BreadcrumbList', '@id': url + '#breadcrumb', 'itemListElement': crumbs})
    ldp = os.path.join(PAGES, name + '.ld.json')
    if os.path.exists(ldp):
        graph.extend(json.load(open(ldp, encoding='utf-8')))
    faq = faq_from(body)
    if faq:
        graph.append({'@type': 'FAQPage', '@id': url + '#faq', 'mainEntity': faq})
    ld = json.dumps({'@context': 'https://schema.org', '@graph': graph}, ensure_ascii=False, indent=2)
    m['_ld'] = '\n'.join('  ' + l for l in ld.split('\n'))

    out = head(m)
    cls = ('sub ' + m.get('bodyClass', '')).strip()
    out += f'\n<body class="{cls}">\n'
    out +='  <a class="skip" href="#main">本文へスキップ</a>\n\n'
    out += '  <!-- アイコン（Font Awesome を読み込まず、使う分だけインラインで持つ） -->\n' + SPRITE + '\n'
    cta = m.get('navCta') or ('/contact/' if m.get('noAsk') else '#contact')
    out += header(m['current'], cta).replace('{FROM}', m['from'])
    out += body.rstrip() + '\n'
    if not m.get('noAsk'):
        out += ask(m)
    out += '\n  </main>\n'
    out += footer(m)
    dst = os.path.join(SITE, m['path'], 'index.html')
    os.makedirs(os.path.dirname(dst), exist_ok=True)
    open(dst, 'w', encoding='utf-8').write(out)
    print('wrote', dst, len(out.encode()), 'bytes', f'faq={len(faq)}')


if __name__ == '__main__':
    names = sys.argv[1:] or sorted(f[:-5] for f in os.listdir(PAGES) if f.endswith('.html'))
    for n in names:
        build(n)
