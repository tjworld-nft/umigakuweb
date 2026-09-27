// 使い方: node section.js <path> <selector> <width> <out.png>
const { chromium } = require('playwright-core');
(async () => {
  const [path, sel, w, out] = process.argv.slice(2);
  const b = await chromium.launch({ executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome' });
  const p = await b.newPage({ viewport: { width: +w, height: 900 }, isMobile: +w < 800 });
  await p.route(u => u.pathname.endsWith('/'), async r => { const f = await r.fetch(); await r.fulfill({ response: f, body: (await f.text()).replace(/ loading="lazy"/g, ''), headers: { ...f.headers(), 'content-type': 'text/html; charset=utf-8' } }); });
  await p.goto('http://127.0.0.1:8978/' + path, { waitUntil: 'load' });
  await p.addStyleTag({ content: '.site-header,.lnav,.mobile-cta-bar{visibility:hidden!important}' });
  const el = await p.$(sel);
  await el.scrollIntoViewIfNeeded();
  await p.evaluate(async s => { const imgs = [...document.querySelector(s).querySelectorAll('img')]; await Promise.all(imgs.map(i => i.complete && i.naturalWidth ? 0 : new Promise(r => { i.onload = i.onerror = r; setTimeout(r, 6000); }))); await Promise.all(imgs.map(i => i.decode ? i.decode().catch(() => 0) : 0)); }, sel);
  await p.waitForTimeout(500);
  await el.screenshot({ path: out });
  await b.close();
})();
