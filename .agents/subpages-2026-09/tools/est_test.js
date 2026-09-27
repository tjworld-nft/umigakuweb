const { chromium } = require('playwright-core');
(async () => {
  const browser = await chromium.launch({ executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome' });
  const page = await browser.newPage({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
  const errs = []; page.on('pageerror', e => errs.push(e.message)); page.on('console', m => { if (m.type() === 'error') errs.push(m.text()); });
  await page.goto('http://127.0.0.1:8978/license/', { waitUntil: 'load' });
  const read = () => page.evaluate(() => ({ total: document.querySelector('[data-est-total]').textContent, rent: document.querySelector('[data-est-rent]').textContent, disc: document.querySelector('[data-est-disc-row]').hidden, deal: (document.querySelector('[data-est-deal]') || {}).textContent, name: document.querySelector('[data-est-name]').textContent, msg: document.querySelector('[data-est-line]').getAttribute('data-line-msg') }));
  const out = [];
  out.push(['owd rent', await read()]);
  await page.check('input[name=est-course][value=aow]', { force: true }); out.push(['aow rent', await read()]);
  await page.check('input[name=est-course][value=set]', { force: true }); out.push(['set rent', await read()]);
  await page.check('input[name=est-gear][value=own]', { force: true }); out.push(['set own', await read()]);
  await page.check('input[name=est-course][value=owd]', { force: true }); out.push(['owd own', await read()]);
  for (const [k, v] of out) console.log(k, JSON.stringify(v));
  // 目次：#flow に飛んで、光る項目と水深
  await page.click('.lnav__list a[href="#flow"]'); await page.waitForTimeout(1200);
  console.log('lnav on:', await page.evaluate(() => [document.querySelector('.lnav__list a.is-on')?.textContent, document.querySelector('.lnav__depth b').textContent, location.hash, Math.round(document.getElementById('flow').getBoundingClientRect().top), getComputedStyle(document.documentElement).getPropertyValue('--hdr'), getComputedStyle(document.documentElement).getPropertyValue('--lnav-h')]));
  // モバイル下部バー（ヒーローの間は隠れる）
  await page.evaluate(() => window.scrollTo(0, 0)); await page.waitForTimeout(400);
  console.log('bar hidden at top:', await page.evaluate(() => document.querySelector('.mobile-cta-bar').classList.contains('is-hidden')));
  console.log('errors:', errs);
  await browser.close();
})();
