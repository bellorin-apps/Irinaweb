const { chromium } = require('playwright'); const fs = require('fs'); const path = require('path');
(async () => {
  const dir = __dirname; const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1200, height: 630 }, deviceScaleFactor: 1 });
  await p.route('https://drairinagonzalez.com/__og/**', r => { const f = path.basename(new URL(r.request().url()).pathname) || 'og.html';
    const types = { html: 'text/html; charset=utf-8', webp: 'image/webp', svg: 'image/svg+xml' }; const fp = path.join(dir, f === '' ? 'og.html' : f);
    r.fulfill({ body: fs.readFileSync(fp), contentType: types[f.split('.').pop()] || 'application/octet-stream' }); });
  await p.goto('https://drairinagonzalez.com/__og/og.html', { waitUntil: 'networkidle' });
  await p.evaluate(() => document.fonts.ready); const ff = await p.evaluate(() => document.fonts.check('600 78px iskra'));
  console.log('iskra loaded:', ff);
  await p.screenshot({ path: path.join(dir, 'og-default.jpg'), type: 'jpeg', quality: 92 }); await p.screenshot({ path: path.join(dir, 'og-default.png') }); await b.close();
})();
