const { chromium } = require('playwright-core');
(async () => {
  const b = await chromium.launch({ executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome' });
  const p = await b.newPage({ viewport: { width: 320, height: 800 }, isMobile: true });
  await p.goto('http://127.0.0.1:8978/' + process.argv[2], { waitUntil: 'load' });
  await p.waitForTimeout(500);
  console.log(await p.evaluate(() => {
    const cw = document.documentElement.clientWidth;
    return [...document.querySelectorAll('body *')].filter(e => e.getBoundingClientRect().right > cw + 1 && !e.closest('.mobile-nav, .lnav, .site-header'))
      .map(e => { const r = e.getBoundingClientRect(); return e.tagName + '.' + String(e.className).slice(0, 40) + ' w=' + Math.round(r.width) + ' r=' + Math.round(r.right) + ' ' + (e.textContent || '').trim().slice(0, 30); }).slice(0, 15);
  }));
  await b.close();
})();
