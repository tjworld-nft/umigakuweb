# 撮影写真（~/Desktop/日付/）→ サイト用WebP。EXIFの回転はPILで反映（cwebpは回転を落とすため使わない）
import os
from PIL import Image, ImageOps, ImageEnhance
# 2026-10-02 にデスクトップ整理で日付フォルダが 01_写真・動画/2026-09/ へ移った
SRC = os.path.expanduser('~/Desktop/01_写真・動画/2026-09')
OUT = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..', '..', 'image', 'sub'))
# name: (source, widths, crop box as fractions (l,t,r,b) or None, enhance)
JOBS = {
  'license-hero':   ('20260917/P9178295.JPG', (800, 1600), None, True),
  'fun-hero':       ('20260913/P9138235.JPG', (800, 1600), None, True),
  'snorkel-hero':   ('20260922/P9228311.JPG', (800, 1600), None, False),
  'aow-compass':    ('20260906/aowcompas2.JPG', (960,), None, True),
  'owd-skill':      ('20260906/owdyouheiminano2.JPG', (960,), None, True),
  'owd-frame':      ('20260906/owdkatayama2.JPG', (960,), None, True),
  'boat-tank':      ('20260910/P9108194.JPG', (960,), None, False),
  'fish-school':    ('20260917/P9178293.JPG', (960,), None, True),
  'fish-red':       ('20260905/P9058102.JPG', (960,), None, True),
  'fish-banner':    ('20260908/P9088190.JPG', (960,), None, True),
  'nudi-pink':      ('20260926/P9268448.JPG', (960,), None, False),
  'shrimp-anemone': ('20260924/P9248381.JPG', (960,), None, False),
  'coral-cowrie':   ('20260925/P9258410.JPG', (960,), None, False),
  'descent-rope':   ('20260913/P9138254.JPG', (960,), None, True),
  'surface-blue':   ('20260924/P9248333.JPG', (960,), None, False),
  'divers-thumbs':  ('20260926/P9268450.JPG', (960,), None, True),
  # 追加（2026-09-27 各ページのヒーロー）
  'trial-hero':     ('20260924/P9248333.JPG', (800, 1600), None, False),
  'instructor-hero':('20260906/owdyouheiminano2.JPG', (800, 1600), None, True),
  'beginner-hero':  ('20260923/P9238316.JPG', (800, 1600), None, False),
  'tokyo-hero':     ('20260913/P9138238.JPG', (800, 1600), None, True),
  'yokohama-hero':  ('20260926/P9268453.JPG', (800, 1600), None, True),
  'yokosuka-hero':  ('20260917/P9178302.JPG', (800, 1600), None, True),
  'kanagawa-hero':  ('20260913/P9138236.JPG', (800, 1600), None, True),
  'contact-hero':   ('20260913/P9138214.JPG', (800, 1600), None, True),
  'fish-school2':   ('20260913/P9138238.JPG', (960,), None, True),
  # 2026-09-27 吉田さん確認：P9248333（trial-hero・surface-blue）＝城ヶ島・梶の浜ビーチ（白い灯台の堤防）／P9108194（boat-tank）＝城ヶ島ボート。P9088189 も同じ堤防が写る梶の浜
  'beach-surface':  ('20260908/P9088189.JPG', (960,), None, False),
  'kajinohama-card':('20260924/P9248333.JPG', (960,), (0, .22, 1, 1), False),   # 写真カード用：人物が上寄りになるよう空を切る
  'boat-divers':    ('20260913/P9138220.JPG', (960,), None, True),
  # 2026-10-09 新設ページのヒーロー（アドバンス／リフレッシュ／スペシャルティ）
  'advanced-hero':  ('20260906/aowcompas2.JPG', (800, 1600), None, True),
  'refresh-hero':   ('20260912/P9128200.JPG', (800, 1600), None, True),
  'specialty-hero': ('20260916/P9168268.JPG', (800, 1600), None, True),
  # 宮川湾のポイントページ（9/13 宮川湾ボートの撮影分）
  'miyagawa-hero':  ('20260913/P9138255.JPG', (800, 1600), None, True),
  'miyagawa-seahorse': ('20260913/P9138230.JPG', (960,), None, True),
}
import sys
if len(sys.argv) > 1: JOBS = {k: v for k, v in JOBS.items() if k in sys.argv[1:]}
def enhance(im):
    im = ImageOps.autocontrast(im, cutoff=(0.4, 0.4), preserve_tone=True)
    im = ImageEnhance.Color(im).enhance(1.12)
    return im
for name, (src, widths, crop, enh) in JOBS.items():
    im = ImageOps.exif_transpose(Image.open(os.path.join(SRC, src))).convert('RGB')
    if crop:
        w, h = im.size; im = im.crop((int(crop[0]*w), int(crop[1]*h), int(crop[2]*w), int(crop[3]*h)))
    if enh: im = enhance(im)
    for w in widths:
        r = im.copy(); r.thumbnail((w, w * 10), Image.LANCZOS)
        p = os.path.join(OUT, f'{name}-{w}.webp'); r.save(p, 'WEBP', quality=78, method=6)
        print(f'{name}-{w}.webp', r.size, os.path.getsize(p) // 1024, 'KB')
