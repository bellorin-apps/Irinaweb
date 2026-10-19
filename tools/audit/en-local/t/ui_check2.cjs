// Segunda pasada: encuadre real de la imagen (computed), carrusel y un envío limpio del formulario EN con correo desactivado.
const path = require('node:path');
const fs = require('node:fs');
const {chromium} = require(path.join(__dirname, '..', 'node', 'node_modules', 'playwright-core'));
const S = 'http://127.0.0.1:8890';
const UA = 'Mozilla/5.0 (Linux; Android 14; SM-S926B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36';
(async () => {
  const browser = await chromium.launch({executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  const out = {};
  const mk = async (mobile) => { const c = await browser.newContext(mobile ? {viewport: {width: 390, height: 844}, deviceScaleFactor: 2, isMobile: true, hasTouch: true, userAgent: UA} : {viewport: {width: 1440, height: 900}});
    await c.route('**/*', r => new URL(r.request().url()).hostname === '127.0.0.1' ? r.continue() : r.abort()); return c; };
  for (const mobile of [false, true]) {
    const c = await mk(mobile); const page = await c.newPage();
    for (const p of ['/en/sleep/', '/en/ent/']) {
      await page.goto(S + p, {waitUntil: 'networkidle'});
      out[`${p} ${mobile ? 390 : 1440}`] = await page.evaluate(() => {
        const sec = document.querySelector('.di-bleed'); const imgs = [...sec.querySelectorAll('img')];
        return {style: sec.getAttribute('style'), imgs: imgs.map(i => ({cls: i.className, parent: i.parentElement.className, op: getComputedStyle(i).objectPosition, src: i.currentSrc.split('/').pop()})),
          cssRule: [...document.styleSheets].some(s => { try { return [...s.cssRules].some(r => r.cssText && r.cssText.includes('--bleed-focus')); } catch (e) { return false; } })};
      });
    }
    await c.close();
  }
  { const c = await mk(false); const page = await c.newPage(); const errs = [];
    page.on('pageerror', e => errs.push(e.message.slice(0, 120)));
    await page.goto(S + '/en/', {waitUntil: 'networkidle'});
    await page.evaluate(() => document.querySelector('.di-section--doctor-bg').scrollIntoView());
    await page.waitForTimeout(2500);
    out.carousel = await page.evaluate(() => { const u = document.querySelector('.di-section--doctor-bg .di-creds');
      return {items: u ? u.children.length : 0, cls: u ? u.className : null, dots: [...document.querySelectorAll('.di-creds__dots button')].map(b => b.getAttribute('aria-label'))}; });
    out.carouselErrors = errs; await c.close(); }
  { const c = await mk(true); const page = await c.newPage(); const posts = [];
    page.on('response', async r => { if (r.request().method() === 'POST') posts.push({url: r.url().replace(S, ''), status: r.status(), body: (await r.text().catch(() => '')).slice(0, 160)}); });
    await page.goto(S + '/en/contact/', {waitUntil: 'networkidle'});
    await page.waitForTimeout(6000);
    const f = page.locator('form.di-form');
    await f.locator('[name=di_name]').fill('Prueba Sintética');
    await f.locator('[name=di_phone]').fill('8100000000');
    if (await f.locator('[name=di_email]').count()) await f.locator('[name=di_email]').fill('prueba@example.test');
    await f.locator('select[name=di_motivo]').selectOption({index: 1});
    if (await f.locator('[name=di_message]').count()) await f.locator('[name=di_message]').fill('Mensaje de prueba local, sin envío real.');
    await f.locator('[name=di_consent]').check();
    await f.locator('[type=submit]').first().click();
    await page.waitForTimeout(5000);
    out.form = {url: page.url().replace(S, ''), status: await page.evaluate(() => document.querySelector('.di-form__status')?.textContent.trim()), posts};
    await c.close(); }
  await browser.close();
  fs.writeFileSync(path.join(__dirname, 'C-ui2.json'), JSON.stringify(out, null, 1));
  console.log(JSON.stringify(out, null, 1));
})().catch(e => { console.error(e); process.exit(1); });
