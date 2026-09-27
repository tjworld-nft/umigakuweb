// 使い方: node shoot.js <outdir> <width> page1 page2 ...   （全画面・遅延画像とrevealを強制表示）
const { chromium } = require('playwright-core');
(async () => {
  const [out, w, ...pages] = process.argv.slice(2);
  const browser = await chromium.launch({ executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', args: ['--disable-gpu'] });
  const ctx = await browser.newContext({ viewport: { width: +w, height: +w < 800 ? 844 : 900 }, deviceScaleFactor: 1, isMobile: +w < 800, hasTouch: +w < 800 });
  for (const p of pages) {
    const page = await ctx.newPage();
    // 撮影用：遅延読み込みを外したHTMLを返す（スクショで画像が欠けないように）
    await page.route(u => u.pathname.endsWith('/') || u.pathname.endsWith('.html'), async route => {
      const r = await route.fetch(); let body = await r.text();
      body = body.replace(/ loading="lazy"/g, '');
      await route.fulfill({ response: r, body, headers: { ...r.headers(), 'content-type': 'text/html; charset=utf-8' } });
    });
    const errs = [];
    page.on('pageerror', e => errs.push(e.message));
    page.on('console', m => { if (m.type() === 'error') errs.push(m.text()); });
    await page.goto('http://127.0.0.1:8978/' + p + '?ocean=off', { waitUntil: 'load' }).catch(e => errs.push(String(e)));
    await page.evaluate(() => { document.querySelectorAll('.reveal').forEach(e => e.classList.add('is-visible')); });
    await page.evaluate(async () => {
      for (let y = 0; y < document.documentElement.scrollHeight; y += 400) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 140)); }
      window.scrollTo(0, 0);
      await Promise.all([...document.images].map(i => i.complete ? 0 : new Promise(r => { i.onload = i.onerror = r; setTimeout(r, 4000); })));
    });
    await page.waitForTimeout(600);
    const broken = await page.evaluate(() => [...document.images].filter(i => !i.naturalWidth).map(i => i.getAttribute('src')));
    if (broken.length) errs.push('BROKEN IMG: ' + broken.join(', '));
    const info = await page.evaluate(() => ({ sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth, h: document.documentElement.scrollHeight }));
    const name = (p.replace(/\/$/, '').replace(/\//g, '_') || 'home') + '-' + w + '.png';
    await page.screenshot({ path: out + '/' + name, fullPage: true });
    console.log(name, JSON.stringify(info), errs.length ? 'ERR:' + errs.slice(0, 3).join(' | ') : '');
    await page.close();
  }
  await browser.close();
})();
