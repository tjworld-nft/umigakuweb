# Range対応の静的サーバー（動画のシーク確認用）
import http.server, os, re, sys
root = sys.argv[1]; port = int(sys.argv[2])
class H(http.server.SimpleHTTPRequestHandler):
    def __init__(s, *a, **k): super().__init__(*a, directory=root, **k)
    extensions_map = {**http.server.SimpleHTTPRequestHandler.extensions_map, '.vtt':'text/vtt', '.webp':'image/webp', '.mp4':'video/mp4', '.svg':'image/svg+xml', '.json':'application/json'}
    def log_message(s, *a): pass
    def send_head(s):
        rng = s.headers.get('Range')
        path = s.translate_path(s.path)
        if os.path.isdir(path):
            idx = os.path.join(path, 'index.html')
            if not s.path.split('?')[0].endswith('/'):
                s.send_response(301); s.send_header('Location', s.path.split('?')[0]+'/'); s.end_headers(); return None
            path = idx
        if not rng or not os.path.isfile(path): return super().send_head()
        m = re.match(r'bytes=(\d*)-(\d*)', rng); size = os.path.getsize(path)
        a = int(m.group(1)) if m.group(1) else 0; b = int(m.group(2)) if m.group(2) else size-1
        b = min(b, size-1); f = open(path, 'rb'); f.seek(a)
        s.send_response(206); s.send_header('Content-Type', s.guess_type(path)); s.send_header('Accept-Ranges','bytes')
        s.send_header('Content-Range', f'bytes {a}-{b}/{size}'); s.send_header('Content-Length', str(b-a+1)); s.end_headers()
        s._rem = b-a+1; return f
    def copyfile(s, src, dst):
        rem = getattr(s, '_rem', None)
        if rem is None: return super().copyfile(src, dst)
        while rem > 0:
            buf = src.read(min(65536, rem))
            if not buf: break
            dst.write(buf); rem -= len(buf)
http.server.ThreadingHTTPServer(('127.0.0.1', port), H).serve_forever()
