# 縦長の全画面スクショを、列に折り返して1枚に並べる
import sys
from PIL import Image
src, out = sys.argv[1], sys.argv[2]; seg = int(sys.argv[3]) if len(sys.argv) > 3 else 2800
im = Image.open(src).convert('RGB'); w, h = im.size
n = (h + seg - 1) // seg
sheet = Image.new('RGB', (n * (w + 12), min(seg, h)), (40, 40, 40))
for i in range(n):
    part = im.crop((0, i * seg, w, min(h, (i + 1) * seg)))
    sheet.paste(part, (i * (w + 12), 0))
scale = min(1.0, 2400 / sheet.width)
if scale < 1: sheet = sheet.resize((int(sheet.width * scale), int(sheet.height * scale)), Image.LANCZOS)
sheet.save(out, quality=80)
print(out, sheet.size)
