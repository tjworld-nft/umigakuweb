/**
 * 開発者向けの覗き窓。Shift+O で開閉する。
 * 普段のお客さんには一切見えないし、DOMも押されるまで作らない。
 */
export function createHud() {
  let el = null;
  let on = false;

  function build() {
    el = document.createElement('div');
    el.setAttribute('aria-hidden', 'true');
    el.style.cssText = [
      'position:fixed', 'left:12px', 'bottom:12px', 'z-index:9999',
      'font:12px/1.55 ui-monospace,SFMono-Regular,Menlo,monospace',
      'color:#d8fbff', 'background:rgba(4,26,34,.82)', 'padding:10px 13px',
      'border:1px solid rgba(120,230,240,.35)', 'border-radius:10px',
      'pointer-events:none', 'white-space:pre', 'backdrop-filter:blur(6px)',
      'box-shadow:0 8px 30px rgba(0,0,0,.35)',
    ].join(';');
    document.body.appendChild(el);
  }

  addEventListener('keydown', (e) => {
    if (!e.shiftKey || e.key.toLowerCase() !== 'o') return;
    if (e.target && /^(INPUT|TEXTAREA|SELECT)$/.test(e.target.tagName)) return;
    on = !on;
    if (on && !el) build();
    if (el) el.style.display = on ? 'block' : 'none';
  });

  return {
    update(s) {
      if (!on || !el) return;
      el.textContent =
        `三浦 海の学校 / water\n` +
        `backend   ${s.backend}\n` +
        `水面      ${s.sim}\n` +
        `dpr       ${s.dpr.toFixed(2)}\n` +
        `frame     ${s.ms.toFixed(1)} ms  (${(1000 / Math.max(s.ms, 0.01)).toFixed(0)} fps)\n` +
        `波の勢い  ${s.poke.toFixed(3)}\n` +
        `スクロール ${(s.scroll * 100).toFixed(0)}%\n` +
        `draws     ${s.draws}\n` +
        `\nwindow.__ocean.ripple.params / .heroPhoto.params`;
    },
    get visible() { return on; },
  };
}
