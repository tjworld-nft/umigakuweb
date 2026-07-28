#!/usr/bin/env python3
"""三浦 海の学校 公式LINE(@438towqp) Messaging API 配信ツール

トークン: ~/.config/line/token（長期チャネルアクセストークンを1行で保存）
名簿:     ~/.config/line/roster.json（userId↔表示名。git外・個人情報なので絶対にコミットしない）

使い方:
  status                        トークン確認・今月の配信数と無料枠残
  broadcast --text "本文"        全友だちに配信（--yes が無ければ内容表示のみ）
  broadcast --image <URL>       画像配信（https必須・originalContentUrl）
  broadcast --text A --text B --image <URL>   吹き出しは最大5個まで混在可
  push --to <USER_ID> --text "本文"           特定ユーザーへテスト送信

  === 絞り込み配信（送る相手を選ぶ） ===
  roster sync                   友だちのuserIdと表示名を取得して名簿を作る
  roster add <USER_ID> [--note] 名簿に1人追加（syncが使えないアカウント用）
  roster list                   名簿を表示（除外フラグつき）
  roster exclude "名前"          その人を配信対象から外す（部分一致）
  roster include "名前"          除外を解除
  multicast --text "本文"        名簿の「除外されていない人」だけに配信
  multicast --text "本文" --only "田中,佐藤"     指定した人だけに配信
  multicast --text "本文" --skip "山田"          その場かぎりで追加除外

例（テスト→本配信の流れ）:
  python3 line_broadcast.py push --to Uxxxx... --text "テスト"
  python3 line_broadcast.py broadcast --text "本文" --image https://miura-diving.com/image/banner.jpg
  → 内容を確認して問題なければ --yes を付けて再実行

注意:
  - コミュニケーションプラン無料枠は月200通。友だち約89人なので一斉配信は月2回まで。
    multicastは「送った人数ぶん」しか消費しないので、絞り込めばその月の残数を節約できる。
  - `roster sync` の元になる /v2/bot/followers/ids は**認証済アカウント以外は403**になる。
    その場合は roster add で手動登録するか、相手からトークが来たときにuserIdを控える。
  - 表示名はLINEのプロフィール名。本名とは限らないので --note に「OWD 2025.7受講」等を入れておくと探しやすい。
  - 画像はリポジトリにコミット→mainマージ→FTP自動デプロイでhttps化してから使う。
  - 予約配信APIは無い。時刻指定はClaude Codeのスケジュールタスクやcronでこのスクリプトを実行する。
"""
import argparse
import json
import sys
import urllib.request
import urllib.error
from pathlib import Path
from typing import List, Optional, Tuple

TOKEN_PATH = Path.home() / ".config/line/token"
ROSTER_PATH = Path.home() / ".config/line/roster.json"
API = "https://api.line.me/v2/bot"


def token() -> str:
    if not TOKEN_PATH.exists():
        sys.exit(f"トークンがありません: {TOKEN_PATH}\n"
                 "LINE Developersコンソールで長期チャネルアクセストークンを発行して保存してください。")
    return TOKEN_PATH.read_text().strip()


def api(method: str, path: str, body: Optional[dict] = None, soft: bool = False):
    """soft=True のときはHTTPエラーで終了せず (None, (code, body)) を返す。"""
    req = urllib.request.Request(
        f"{API}{path}",
        data=json.dumps(body).encode() if body is not None else None,
        method=method,
        headers={
            "Authorization": f"Bearer {token()}",
            "Content-Type": "application/json",
        },
    )
    try:
        with urllib.request.urlopen(req) as res:
            data = res.read()
            result = json.loads(data) if data else {}
            return (result, None) if soft else result
    except urllib.error.HTTPError as e:
        detail = e.read().decode()
        if soft:
            return None, (e.code, detail)
        sys.exit(f"APIエラー {e.code}: {detail}")


# ── 名簿（roster） ───────────────────────────────────────────────
# 形式: [{"userId": "U...", "name": "表示名", "note": "メモ", "exclude": false}, ...]

def load_roster() -> List[dict]:
    if not ROSTER_PATH.exists():
        return []
    return json.loads(ROSTER_PATH.read_text())


def save_roster(rows: List[dict]) -> None:
    ROSTER_PATH.parent.mkdir(parents=True, exist_ok=True)
    ROSTER_PATH.write_text(json.dumps(rows, ensure_ascii=False, indent=2))
    ROSTER_PATH.chmod(0o600)


def match(row: dict, needle: str) -> bool:
    n = needle.strip()
    return bool(n) and (n in row.get("name", "") or n in row.get("note", "") or n == row["userId"])


def display_name(user_id: str) -> str:
    res, err = api("GET", f"/profile/{user_id}", soft=True)
    if err:
        return "(取得できず)"
    return res.get("displayName", "(名前なし)")


def build_messages(texts: List[str], images: List[str]) -> List[dict]:
    msgs: List[dict] = []
    for t in texts:
        msgs.append({"type": "text", "text": t})
    for u in images:
        if not u.startswith("https://"):
            sys.exit(f"画像URLはhttps必須です: {u}")
        msgs.append({"type": "image", "originalContentUrl": u, "previewImageUrl": u})
    if not msgs:
        sys.exit("--text か --image を最低1つ指定してください")
    if len(msgs) > 5:
        sys.exit("吹き出しは最大5個までです")
    return msgs


def cmd_status(_args):
    info = api("GET", "/info")
    quota = api("GET", "/message/quota")
    used = api("GET", "/message/quota/consumption")
    print(f"アカウント: {info.get('displayName')} (@{info.get('basicId', '?').lstrip('@')})")
    limit = quota.get("value", "無制限") if quota.get("type") == "limited" else "無制限"
    print(f"今月の配信数: {used.get('totalUsage')} / {limit}")
    if quota.get("type") == "limited":
        print(f"残り: {quota['value'] - used.get('totalUsage', 0)} 通")


def cmd_broadcast(args):
    msgs = build_messages(args.text, args.image)
    used = api("GET", "/message/quota/consumption").get("totalUsage", 0)
    print(f"=== 配信内容（全友だち宛・今月消費 {used} 通） ===")
    for i, m in enumerate(msgs, 1):
        if m["type"] == "text":
            print(f"[{i}] テキスト:\n{m['text']}\n")
        else:
            print(f"[{i}] 画像: {m['originalContentUrl']}\n")
    if not args.yes:
        print("→ 未送信です。この内容で配信するには --yes を付けて再実行してください。")
        return
    api("POST", "/message/broadcast", {"messages": msgs})
    print("✅ 配信しました")


def cmd_push(args):
    msgs = build_messages(args.text, args.image)
    api("POST", "/message/push", {"to": args.to, "messages": msgs})
    print(f"✅ {args.to} に送信しました")


def cmd_audience(args):
    """除外用オーディエンスの作成・確認。userIdのJSONはgit外に置くこと。"""
    if args.action == "list":
        res = api("GET", "/audienceGroup/list?page=1&size=40")
        groups = res.get("audienceGroups", [])
        if not groups:
            print("オーディエンスはまだありません。")
            return
        for g in groups:
            print(f"  [{g['audienceGroupId']}] {g['description']}  {g['status']}  {g.get('audienceCount', '?')}人")
        return

    if args.action == "create":
        src = Path(args.file).expanduser()
        if not src.exists():
            sys.exit(f"ファイルがありません: {src}")
        rows = json.loads(src.read_text())
        ids = [{"id": r["userId"]} for r in rows]
        print(f"{len(ids)} 人でオーディエンス「{args.name}」を作成します:")
        for r in rows:
            print(f"  ・{r['name']}")
        if not args.yes:
            print("→ 未作成です。作成するには --yes を付けて再実行してください。")
            return
        res = api("POST", "/audienceGroup/upload",
                  {"description": args.name, "isIfaAudience": False, "audiences": ids})
        print(f"✅ 作成しました: audienceGroupId={res['audienceGroupId']} status={res.get('audienceGroupStatus')}")
        return

    # status
    res = api("GET", f"/audienceGroup/{args.id}")
    g = res.get("audienceGroup", {})
    print(f"[{g.get('audienceGroupId')}] {g.get('description')}  status={g.get('status')}  {g.get('audienceCount')}人")


def cmd_narrowcast(args):
    """オーディエンスを『除外』して、それ以外の友だち全員に配信する。"""
    msgs = build_messages(args.text, args.image)
    used = api("GET", "/message/quota/consumption").get("totalUsage", 0)
    quota = api("GET", "/message/quota")
    limit = quota.get("value") if quota.get("type") == "limited" else None
    reach = api("GET", f"/insight/followers?date={args.date}") if args.date else {}

    g = api("GET", f"/audienceGroup/{args.exclude_audience}").get("audienceGroup", {})
    if g.get("status") != "READY":
        sys.exit(f"オーディエンスがまだ使えません（status={g.get('status')}）。READY になるまで待ってください。")

    excluded = g.get("audienceCount", 0)
    targeted = reach.get("targetedReaches")
    est = (targeted - excluded) if targeted else None

    print(f"=== 絞り込み配信（オーディエンス除外）===")
    print(f"除外: [{args.exclude_audience}] {g.get('description')} … {excluded} 人")
    if targeted:
        print(f"配信可能な友だち: {targeted} 人 → 送信見込み: 約 {est} 人")
    print(f"今月消費: {used} 通" + (f" / 上限 {limit} 通" if limit else ""))
    print("\n--- 本文 ---")
    for i, m in enumerate(msgs, 1):
        print(f"[{i}] " + (f"テキスト:\n{m['text']}" if m["type"] == "text" else f"画像: {m['originalContentUrl']}") + "\n")

    if est is not None and est < 50:
        print("⚠ 絞り込み配信は対象が50人未満だと配信されません。")

    if not args.yes:
        print("→ 未送信です。この内容・この宛先で配信するには --yes を付けて再実行してください。")
        return
    req = urllib.request.Request(
        f"{API}/message/narrowcast",
        data=json.dumps({
            "messages": msgs,
            "recipient": {"type": "operator", "not": {"type": "audience", "audienceGroupId": int(args.exclude_audience)}},
        }).encode(),
        method="POST",
        headers={"Authorization": f"Bearer {token()}", "Content-Type": "application/json"},
    )
    try:
        with urllib.request.urlopen(req) as res:
            # requestIdはボディではなく X-Line-Request-Id ヘッダーで返る
            request_id = res.headers.get("X-Line-Request-Id")
    except urllib.error.HTTPError as e:
        sys.exit(f"APIエラー {e.code}: {e.read().decode()}")
    print(f"✅ 配信リクエストを送信しました（requestId: {request_id}）")
    print(f"   進捗:  python3 line_broadcast.py narrowcast-status --request-id {request_id}")
    print("   実際に送られたかは status の配信数の増分でも確認できます。")


def cmd_narrowcast_status(args):
    res = api("GET", f"/message/progress/narrowcast?requestId={args.request_id}")
    print(json.dumps(res, ensure_ascii=False, indent=2))


def cmd_roster(args):
    rows = load_roster()
    by_id = {r["userId"]: r for r in rows}

    if args.action == "sync":
        # /followers/ids は認証済アカウント限定。使えない場合は理由を出して roster add を案内する。
        fetched, start = [], None
        while True:
            path = "/followers/ids?limit=1000" + (f"&start={start}" if start else "")
            res, err = api("GET", path, soft=True)
            if err:
                code, detail = err
                print(f"❌ 友だち一覧を取得できませんでした（HTTP {code}）")
                print(f"   {detail}")
                print("\n/v2/bot/followers/ids は**認証済アカウント**でないと使えません。")
                print("代わりに、相手からトークが来たときのuserIdを控えて手動登録してください:")
                print('  python3 line_broadcast.py roster add U1234... --note "OWD 2025.7受講"')
                return
            fetched += res.get("userIds", [])
            start = res.get("next")
            if not start:
                break
        added = 0
        for uid in fetched:
            if uid not in by_id:
                rows.append({"userId": uid, "name": display_name(uid), "note": "", "exclude": False})
                added += 1
        save_roster(rows)
        print(f"✅ 友だち {len(fetched)} 人を確認し、{added} 人を名簿に追加しました → {ROSTER_PATH}")
        return

    if args.action == "add":
        if not args.value:
            sys.exit("userId を指定してください: roster add U1234...")
        uid = args.value
        if uid in by_id:
            sys.exit(f"すでに登録済みです: {uid}")
        rows.append({"userId": uid, "name": display_name(uid), "note": args.note or "", "exclude": False})
        save_roster(rows)
        print(f"✅ 追加しました: {rows[-1]['name']}（{uid}）")
        return

    if args.action in ("exclude", "include"):
        if not args.value:
            sys.exit("名前の一部を指定してください: roster exclude \"山田\"")
        hit = [r for r in rows if match(r, args.value)]
        if not hit:
            sys.exit(f"該当なし: {args.value}")
        for r in hit:
            r["exclude"] = (args.action == "exclude")
        save_roster(rows)
        verb = "配信対象から外しました" if args.action == "exclude" else "配信対象に戻しました"
        for r in hit:
            print(f"✅ {r['name']} を{verb}")
        return

    # list
    if not rows:
        print(f"名簿は空です（{ROSTER_PATH}）。roster sync か roster add で登録してください。")
        return
    print(f"名簿 {len(rows)} 人（{ROSTER_PATH}）")
    for r in rows:
        mark = "×除外" if r.get("exclude") else "○送信"
        note = f"  {r['note']}" if r.get("note") else ""
        print(f"  {mark}  {r['name']}{note}  [{r['userId'][:10]}…]")
    print(f"\n送信対象: {sum(1 for r in rows if not r.get('exclude'))} 人")


def resolve_targets(args) -> Tuple[List[dict], List[dict]]:
    """(送る人, 送らない人) を返す。"""
    rows = load_roster()
    if not rows:
        sys.exit(f"名簿がありません（{ROSTER_PATH}）。roster sync か roster add で登録してください。")
    only = [s for s in (args.only or "").split(",") if s.strip()]
    skip = [s for s in (args.skip or "").split(",") if s.strip()]
    send, hold = [], []
    for r in rows:
        if only:
            (send if any(match(r, o) for o in only) else hold).append(r)
        elif r.get("exclude") or any(match(r, s) for s in skip):
            hold.append(r)
        else:
            send.append(r)
    return send, hold


def cmd_multicast(args):
    msgs = build_messages(args.text, args.image)
    send, hold = resolve_targets(args)
    if not send:
        sys.exit("送信対象が0人です。条件を見直してください。")
    used = api("GET", "/message/quota/consumption").get("totalUsage", 0)
    quota = api("GET", "/message/quota")
    limit = quota.get("value") if quota.get("type") == "limited" else None

    print(f"=== 絞り込み配信（今月消費 {used} 通" + (f" / 上限 {limit} 通）" if limit else "）") + " ===")
    print(f"\n送る人 {len(send)} 人:")
    for r in send:
        print(f"  ○ {r['name']}" + (f"  {r['note']}" if r.get("note") else ""))
    if hold:
        print(f"\n送らない人 {len(hold)} 人:")
        for r in hold:
            print(f"  × {r['name']}" + (f"  {r['note']}" if r.get("note") else ""))
    print(f"\n消費見込み: {len(send)} 通" + (f"（送信後の残り {limit - used - len(send)} 通）" if limit else ""))
    print("\n--- 本文 ---")
    for i, m in enumerate(msgs, 1):
        print(f"[{i}] " + (f"テキスト:\n{m['text']}" if m["type"] == "text" else f"画像: {m['originalContentUrl']}") + "\n")

    if not args.yes:
        print("→ 未送信です。この内容・この宛先で配信するには --yes を付けて再実行してください。")
        return
    ids = [r["userId"] for r in send]
    for i in range(0, len(ids), 500):  # multicastは1回500人まで
        api("POST", "/message/multicast", {"to": ids[i:i + 500], "messages": msgs})
    print(f"✅ {len(ids)} 人に配信しました")


def main():
    p = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    sub = p.add_subparsers(dest="cmd", required=True)

    sub.add_parser("status", help="トークン確認・配信数と無料枠")

    for name in ("broadcast", "push", "multicast"):
        sp = sub.add_parser(name)
        sp.add_argument("--text", action="append", default=[], help="テキスト吹き出し（複数可・各500文字まで）")
        sp.add_argument("--image", action="append", default=[], help="画像URL（https必須）")
        if name == "push":
            sp.add_argument("--to", required=True, help="送信先ユーザーID（Uで始まる）")
        else:
            sp.add_argument("--yes", action="store_true", help="確認なしで即配信")
        if name == "multicast":
            sp.add_argument("--only", help="この人たちだけに送る（名前の一部をカンマ区切り）")
            sp.add_argument("--skip", help="今回だけ外す人（名前の一部をカンマ区切り）")

    ap = sub.add_parser("audience", help="除外用オーディエンスの作成・確認")
    ap.add_argument("action", choices=["create", "list", "status"])
    ap.add_argument("--name", help="オーディエンス名（create）")
    ap.add_argument("--file", help="userIdのJSON（create）。例: ~/.config/line/exclude-list.json")
    ap.add_argument("--id", help="audienceGroupId（status）")
    ap.add_argument("--yes", action="store_true", help="確認なしで作成")

    np_ = sub.add_parser("narrowcast", help="オーディエンスを除外して、それ以外の全員に配信")
    np_.add_argument("--text", action="append", default=[], help="テキスト吹き出し（複数可・各500文字まで）")
    np_.add_argument("--image", action="append", default=[], help="画像URL（https必須）")
    np_.add_argument("--exclude-audience", required=True, help="除外するaudienceGroupId")
    np_.add_argument("--date", help="送信見込みの算出に使う日付（YYYYMMDD・任意）")
    np_.add_argument("--yes", action="store_true", help="確認なしで即配信")

    ns = sub.add_parser("narrowcast-status", help="絞り込み配信の進捗確認")
    ns.add_argument("--request-id", required=True)

    rp = sub.add_parser("roster", help="配信名簿の管理（userId↔表示名・除外フラグ）")
    rp.add_argument("action", choices=["sync", "add", "list", "exclude", "include"])
    rp.add_argument("value", nargs="?", help="add=userId / exclude・include=名前の一部")
    rp.add_argument("--note", help="add のときのメモ（例: OWD 2025.7受講）")

    args = p.parse_args()
    {
        "status": cmd_status,
        "broadcast": cmd_broadcast,
        "push": cmd_push,
        "multicast": cmd_multicast,
        "roster": cmd_roster,
        "audience": cmd_audience,
        "narrowcast": cmd_narrowcast,
        "narrowcast-status": cmd_narrowcast_status,
    }[args.cmd](args)


if __name__ == "__main__":
    main()
