#!/usr/bin/env python3
"""ブログ記事の挿絵を fal(Nano Banana Pro) で生成し、WebPに最適化して出す。

    python3 gen_images.py spec.json --out ./images

spec.json は配列。1要素 = 1枚。

    [
      {"name": "hero",    "aspect": "16:9", "width": 1600, "ref": true,
       "prompt": "Two anime girl scuba divers underwater ..."},
      {"name": "sealife", "aspect": "16:9", "width": 1200, "ref": false,
       "prompt": "Cute anime style underwater illustration of ..."}
    ]

  ref: true  → クラゲ女子を参照画像に渡す（キャラを出す絵）。fal-ai/nano-banana-pro/edit
  ref: false → 参照なしのtext-to-image。fal-ai/nano-banana-pro
  width      → 最終WebPの横幅。hero は 1600、本文中は 1000〜1200 が目安

絵柄指定（アニメ塗り・文字なし）は自動で付く。プロンプトは英語で、被写体と構図だけ書けばよい。

**WebP化は必須。** ブログの記事ページは Sanity の asset.url を素のまま <img src> に出すので、
CDNの変換が効かない。PNGのまま上げると1〜2MBがそのまま配信される。
"""
import argparse
import json
import os
import sys

sys.path.insert(0, "/Users/tetsujiyoshida/Documents/short-factory")
_KEY = os.path.expanduser("~/.config/fal/key")
if os.path.exists(_KEY):
    os.environ.setdefault("FAL_KEY", open(_KEY).read().strip())

from fal_helpers import fal_queue, first_url, download  # noqa: E402
from PIL import Image  # noqa: E402

REF_URL = "https://miura-diving.com/image/kuragejyoshi.png"

STYLE = (
    "Japanese anime and manga illustration style, thin clean lineart, anime cel shading, "
    "glossy anime eyes, bright saturated colors, soft natural light. "
    "Absolutely NOT western cartoon, NOT Disney style, NOT Pixar style, NOT 3D render, NOT photorealistic. "
    "No text, no letters, no numbers, no watermark, no logo anywhere in the image."
)

CHARACTER = (
    "The guide character is exactly the referenced girl: white bob hair, translucent jellyfish hat, "
    "black choker, teal and white wetsuit. Keep her design faithful to the reference. "
)


def generate(spec: dict, out_dir: str) -> str:
    use_ref = bool(spec.get("ref"))
    model = "fal-ai/nano-banana-pro/edit" if use_ref else "fal-ai/nano-banana-pro"
    prompt = spec["prompt"].strip()
    if use_ref:
        prompt = f"{prompt} {CHARACTER}{STYLE}"
    else:
        prompt = f"{prompt} {STYLE}"

    payload = {"prompt": prompt, "num_images": 1, "output_format": "png"}
    if use_ref:
        payload["image_urls"] = [REF_URL]
    if spec.get("aspect"):
        payload["aspect_ratio"] = spec["aspect"]

    print(f"\n=== {spec['name']} ({model} / {spec.get('aspect','auto')}) ===")
    res = fal_queue(model, payload, timeout_s=600)
    url = first_url(res)
    if not url:
        sys.exit(f"URLが取れませんでした: {str(res)[:400]}")

    raw = os.path.join(out_dir, f"{spec['name']}.png")
    download(url, raw)

    width = int(spec.get("width", 1200))
    webp = os.path.join(out_dir, f"{spec['name']}.webp")
    im = Image.open(raw).convert("RGB")
    height = round(im.height * width / im.width)
    im.resize((width, height), Image.LANCZOS).save(webp, "WEBP", quality=82, method=6)
    print(f"optimized: {webp} ({width}x{height}, {os.path.getsize(webp) // 1024}KB)")
    return webp


def main() -> None:
    ap = argparse.ArgumentParser()
    ap.add_argument("spec", help="画像仕様のJSONファイル")
    ap.add_argument("--out", default=".", help="出力ディレクトリ")
    args = ap.parse_args()

    if not os.environ.get("FAL_KEY"):
        sys.exit(f"FAL_KEYが未設定です（{_KEY} が無い）")

    os.makedirs(args.out, exist_ok=True)
    specs = json.load(open(args.spec, encoding="utf-8"))
    made = [generate(s, args.out) for s in specs]
    print("\n生成完了:")
    for m in made:
        print(f"  {m}")


if __name__ == "__main__":
    main()
