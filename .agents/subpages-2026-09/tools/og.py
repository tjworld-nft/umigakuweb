#!/usr/bin/env python3
"""下層ページのOGP画像（1200x630 JPG）。写真＋左からの暗いグラデーション＋見出し。AIは使わずPILだけ。
使い方: python3 og.py <key> ...   （引数なしなら全部）"""
import os, sys
from PIL import Image, ImageOps, ImageDraw, ImageFont, ImageFilter

SITE = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..', '..'))
F = '/System/Library/Fonts/'
W8 = F + 'ヒラギノ角ゴシック W8.ttc'
W6 = F + 'ヒラギノ角ゴシック W6.ttc'
W4 = F + 'ヒラギノ角ゴシック W4.ttc'

# key: (背景, 焦点x(0-1), 焦点y, ラベル, 見出し行, 補足)
JOBS = {
    'license': ('image/sub/license-hero-1600.webp', .55, .45, 'PADI Cカード取得講習｜城ヶ島・宮川湾',
                ['神奈川・三浦で、', 'ダイビングライセンスを取る。'], 'オープンウォーター 総額¥64,900（レンタル器材込み）・実技は最短2日'),
    'fun-diving': ('image/sub/fun-hero-1600.webp', .6, .45, 'Cカードをお持ちの方へ｜城ヶ島・宮川湾',
                   ['三浦の海を、', 'もっと潜ろう。'], 'ビーチ2本¥13,200／ボート2本¥19,800・少人数制のファンダイビング'),
    'trial-diving': ('image/sub/trial-hero-1600.webp', .5, .5, 'ライセンス不要・10歳から｜城ヶ島',
                     ['はじめての海は、', '体験ダイビングから。'], '¥19,800（レンタル器材込み）・半日・泳げなくても大丈夫'),
    'marine-activity': ('image/sub/snorkel-hero-1600.webp', .55, .5, 'ダイビングの前に、海に顔をつけるところから',
                        ['三浦で、', 'スノーケリング。'], '大人¥7,700／子ども¥5,500（器材・ライフジャケット込み）'),
    'instructor': ('image/sub/instructor-hero-1600.webp', .6, .45, 'PADI COURSE DIRECTOR',
                   ['教えるのは、', 'インストラクターを育てる人。'], '三浦 海の学校 代表 吉田 哲司｜1997年から講習ひとすじ'),
    'beginner-guide': ('image/sub/beginner-hero-1600.webp', .6, .5, 'ダイビング初心者ガイド',
                       ['体験とライセンス、', 'どっちから？'], '費用・日数・持ち物・泳げない不安まで、申し込む前に知りたいこと'),
    'contact': ('image/sub/contact-hero-1600.webp', .65, .45, '予約・お問い合わせ',
                ['まずは、LINEで', '聞いてみる。'], '空き日程・コース選び・泳げない不安——質問だけでも大丈夫です'),
    'sea-life': ('image/sub/nudi-pink-960.webp', .5, .5, '城ヶ島・宮川湾で出会える生き物',
                 ['三浦の海の', '生き物図鑑'], 'ウミウシ・魚・エビ・カニ——実際に撮った写真で'),
    'tokyo-diving-license': ('image/sub/tokyo-hero-1600.webp', .6, .5, '東京から日帰りで｜品川から京急で約70分',
                             ['東京から、', 'ダイビングライセンスを。'], 'PADIオープンウォーター 総額¥64,900・三崎口駅から無料送迎'),
    'yokohama-diving-license': ('image/sub/yokohama-hero-1600.webp', .6, .5, '横浜から京急で約55分',
                                ['横浜から、', 'ダイビングライセンスを。'], 'PADIオープンウォーター 総額¥64,900・三崎口駅から無料送迎'),
    'kanagawa-diving-license': ('image/sub/kanagawa-hero-1600.webp', .62, .5, '神奈川でスクールを選ぶ前に',
                                ['神奈川で、', 'ダイビングライセンスを取るなら。'], '少人数制・総額表示・PADIコースディレクターの講習'),
    'yokosuka-diving': ('image/sub/yokosuka-hero-1600.webp', .6, .5, '横須賀から京急で近い三浦の海',
                        ['横須賀から、', '三浦の海へ。'], '体験・ライセンス・ファンダイビング｜京急久里浜から約11分'),
    'kajinohama': ('image/optimized/miura-hero2.webp', .55, .5, '城ヶ島・梶の浜ビーチ',
                   ['遠浅の海で、', 'はじめての講習を。'], 'ビーチダイビング講習・体験ダイビングのポイント紹介'),
}


def cover(im, w, h, fx, fy):
    iw, ih = im.size
    s = max(w / iw, h / ih)
    im = im.resize((int(iw * s + .5), int(ih * s + .5)), Image.LANCZOS)
    iw, ih = im.size
    x = int(min(max(0, fx * iw - w / 2), iw - w)); y = int(min(max(0, fy * ih - h / 2), ih - h))
    return im.crop((x, y, x + w, y + h))


def make(key):
    src, fx, fy, label, lines, sub = JOBS[key]
    W, H = 1200, 630
    im = cover(ImageOps.exif_transpose(Image.open(os.path.join(SITE, src))).convert('RGB'), W, H, fx, fy)
    # 左から暗く（文字の下地）
    grad = Image.new('L', (W, H))
    gd = ImageDraw.Draw(grad)
    for x in range(W):
        a = max(0, 1 - x / (W * .78)) ** 1.25
        gd.line([(x, 0), (x, H)], fill=int(225 * a))
    dark = Image.new('RGB', (W, H), (4, 38, 58))
    im = Image.composite(dark, im, grad)
    # 下端も少し
    grad2 = Image.new('L', (W, H)); g2 = ImageDraw.Draw(grad2)
    for y in range(H):
        g2.line([(0, y), (W, y)], fill=int(150 * max(0, (y - H * .55) / (H * .45)) ** 1.5))
    im = Image.composite(dark, im, grad2)
    d = ImageDraw.Draw(im)
    x = 72
    f_label = ImageFont.truetype(W6, 26)
    f_title = ImageFont.truetype(W8, 64 if max(len(l) for l in lines) <= 12 else 56)
    f_sub = ImageFont.truetype(W6, 25)
    f_brand = ImageFont.truetype(W8, 30)
    # ブランド
    d.text((x, 62), '三浦 海の学校', font=f_brand, fill=(255, 255, 255))
    d.line([(x, 112), (x + 60, 112)], fill=(159, 232, 225), width=4)
    # ラベル
    y = 250 - 40 * (len(lines) - 2)
    d.text((x, y - 58), label, font=f_label, fill=(159, 232, 225))
    for ln in lines:
        d.text((x + 2, y + 3), ln, font=f_title, fill=(0, 20, 30))
        d.text((x, y), ln, font=f_title, fill=(255, 255, 255))
        y += f_title.size + 18
    d.text((x, y + 18), sub, font=f_sub, fill=(233, 246, 250))
    d.text((x, H - 58), 'miura-diving.com', font=ImageFont.truetype(W4, 22), fill=(200, 225, 232))
    out = os.path.join(SITE, 'image', f'og-{key}.jpg')
    im.save(out, quality=86, optimize=True, progressive=True)
    print(out, os.path.getsize(out) // 1024, 'KB')


if __name__ == '__main__':
    for k in (sys.argv[1:] or JOBS):
        make(k)
