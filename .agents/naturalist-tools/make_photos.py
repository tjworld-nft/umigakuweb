import sys, json
from pathlib import Path
from PIL import Image, ImageOps
D='/Users/tetsujiyoshida/Desktop/01_写真・素材/'
OUT=Path(sys.argv[1])
# (出力名, 元, 切り取り(左,上,右,下の割合) or None)
jobs=[
 ('kelp-nudibranch', D+'過去の写真・動画/2026-09/20260913/P9138233.JPG', None),
 ('green-algae', D+'過去の写真・動画/2026-08/20260818/P8187696.JPG', None),
 ('sponge-hermit', D+'2026-11/20261105/PB049148.JPG', None),
 ('sea-fans', D+'過去の写真・動画/2026-09/20260917/P9178300.JPG', (0.0,0.16,1.0,0.725)),
 ('anemone-field', D+'2026-10/20261029/PA299002.JPG', None),
 ('octopus', D+'2026-10/20261021/PA218876.JPG', None),
 ('squid', D+'過去の写真・動画/2026-08/20260830/P8307975.JPG', None),
 ('hermit-anemone', D+'過去の写真・動画/2026-09/20260923/P9238324.JPG', None),
 ('anemone-shrimp-crab', D+'過去の写真・動画/2026-09/20260905/P9058074.JPG', None),
 ('urchin-crab', D+'2026-10/20261024/PA248938.JPG', None),
 ('sea-squirt', D+'過去の写真・動画/2026-09/20260905/P9058086.JPG', (0.0,0.19,1.0,0.7525)),
 ('ray-sand', D+'過去の写真・動画/2026-08/20260811/P8117480.JPG', None),
 ('predation', D+'過去の写真・動画/2026-08/20260815/P8157529.JPG', None),
 ('diver-hover', D+'2026-11/20261107/PB079181.JPG', None),
 ('kelp-school', D+'2026-11/20261107/PB079178.JPG', None),
 ('damsel-anemone', D+'2026-11/20261116/PB169218.JPG', None),
]
info={}
for name,src,crop in jobs:
    im=ImageOps.exif_transpose(Image.open(src)).convert('RGB')
    w,h=im.size
    if crop:
        l,t,r,b=crop; im=im.crop((int(l*w),int(t*h),int(r*w),int(b*h)))
    # 4:3 に合わせる（中央）
    w,h=im.size
    if abs(w/h-4/3)>0.01:
        if w/h>4/3: nw=int(h*4/3); im=im.crop(((w-nw)//2,0,(w-nw)//2+nw,h))
        else: nh=int(w*3/4); im=im.crop((0,(h-nh)//2,w,(h-nh)//2+nh))
    im=im.resize((1000,750),Image.LANCZOS)
    p=OUT/f'{name}.webp'
    im.save(p,'WEBP',quality=78,method=6)   # EXIF・位置情報は書き出さない
    info[name]={'src':src.split('/')[-1],'bytes':p.stat().st_size}
print(json.dumps(info,ensure_ascii=False,indent=0))
