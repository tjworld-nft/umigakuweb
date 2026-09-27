// 全ページ×5幅：横はみ出し・JSエラー・画像切れ・h1の数・リンク先の見出し
const { chromium } = require('playwright-core');
const pages = process.argv.slice(2);
(async () => {
  const browser = await chromium.launch({ executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome' });
  for (const w of (process.env.W || '320,390,768,1024,1440').split(',').map(Number)) {
    const ctx = await browser.newContext({ viewport: { width: w, height: 900 }, isMobile: w < 800, hasTouch: w < 800 });
    for (const p of pages) {
      const page = await ctx.newPage();
      await page.route(u => u.pathname.endsWith('/'), async route => { const r = await route.fetch(); const b = (await r.text()).replace(/ loading="lazy"/g, ''); await route.fulfill({ response: r, body: b, headers: { ...r.headers(), 'content-type': 'text/html; charset=utf-8' } }); });
      const errs = [];
      page.on('pageerror', e => errs.push(e.message));
      page.on('console', m => { if (m.type() === 'error' && !/favicon/.test(m.text())) errs.push(m.text()); });
      await page.goto('http://127.0.0.1:8978/' + p, { waitUntil: 'load' });
      await page.waitForTimeout(400);
      const r = await page.evaluate(() => {
        const wide = [...document.querySelectorAll('body *')].filter(e => { const b = e.getBoundingClientRect(); return b.right > document.documentElement.clientWidth + 1 && getComputedStyle(e).position !== 'fixed' && !e.closest('.lnav__list, .tbl-wrap, .ai-consult, .mobile-nav, [aria-hidden="true"]'); }).slice(0, 3).map(e => e.tagName + '.' + (e.className || '').toString().slice(0, 30));
        const broken = [...document.images].filter(i => i.complete && !i.naturalWidth && i.offsetParent).map(i => i.getAttribute('src'));
        return { sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth, wide, broken };
      });
      const bad = r.sw > r.cw || r.wide.length || r.broken.length || errs.length;
      if (bad) console.log(w, p, JSON.stringify(r), errs.slice(0, 2));
      await page.close();
    }
    await ctx.close();
    console.log('width', w, 'done');
  }
  await browser.close();
})();
