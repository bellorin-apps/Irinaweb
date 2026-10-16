// Local real theme/template + synthetic third-party response. No real widget/account/form/GA calls.
const fs = require('node:fs');
const {chromium} = require(process.env.IRINA_PLAYWRIGHT || 'playwright');
const out = 'docs/audit/2026-10-16c';
const base = 'https://drairinagonzalez.com';
const fixture = fs.readFileSync('tmp/doctoralia-fixture.html', 'utf8');
const main = fs.readFileSync('wp-content/themes/irina-gonzalez/assets/js/main.js', 'utf8');
const events = fs.readFileSync('tmp/doctoralia-events.js', 'utf8');
const css = ['tokens', 'base', 'components'].map(n => fs.readFileSync(`wp-content/themes/irina-gonzalez/assets/css/${n}.css`, 'utf8')).join('\n');
const assert = (value, reason) => { if (!value) throw new Error(reason); };
(async () => {
  const browser = await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  const rows = [];
  for (const width of [390, 1440]) {
    const page = await browser.newPage({viewport:{width, height:900}});
    let requests = 0;
    let fail = false;
    const errors = [];
    page.on('pageerror', e => errors.push(e.name));
    await page.route('**/*', async route => {
      if (route.request().url() === 'https://platform.docplanner.com/js/widget.js') {
        requests++;
        if (fail) return route.abort();
        return route.fulfill({contentType:'application/javascript', body:`var frame=document.createElement('iframe');frame.title='Opiniones sintéticas';frame.style.height='1000px';frame.srcdoc='<p>Contenido sintético, no opiniones reales</p>';document.querySelector('[data-di-doctoralia]').appendChild(frame);`});
      }
      return route.abort();
    });
    await page.setContent(`<style>${css}</style><div style="height:1100px">Cabecera sintética</div>${fixture}<p id="after">Después</p>`);
    await page.evaluate(() => {
      window.auditEvents = [];
      window.gtag = (...args) => window.auditEvents.push(args);
      document.addEventListener('click', e => { if(e.target.closest('a')) e.preventDefault(); });
    });
    if (width === 1440) await page.evaluate(() => { window.IntersectionObserver = undefined; });
    await page.addScriptTag({content:events});
    await page.addScriptTag({content:main});
    const button = page.locator('[data-di-doctoralia-load]');
    await button.scrollIntoViewIfNeeded();
    await page.waitForTimeout(200);
    assert(requests === 0, 'Connection before interaction');
    const before = await page.locator('#after').evaluate(e => e.getBoundingClientRect().top + scrollY);
    const boxBefore = await page.locator('[data-di-doctoralia]').boundingBox();
    await button.focus();
    await page.keyboard.press('Enter');
    await page.locator('iframe').waitFor();
    await page.keyboard.press('Enter');
    assert(requests === 1, 'Duplicate script');
    const after = await page.locator('#after').evaluate(e => e.getBoundingClientRect().top + scrollY);
    const boxAfter = await page.locator('[data-di-doctoralia]').boundingBox();
    assert(Math.abs(after - before) < 1 && boxAfter.height === boxBefore.height, 'Outer layout shift');
    assert(await button.evaluate(e => document.activeElement === e), 'Lost button focus');
    assert(await page.evaluate(() => window.auditEvents.length) === 0, 'Load button counted as outbound conversion');
    await page.locator('.zl-url').click();
    const tracked = await page.evaluate(() => window.auditEvents);
    assert(tracked.length === 1 && tracked[0][1] === 'doctoralia_click', 'Outbound analytics');
    const overflow = await page.evaluate(() => document.documentElement.scrollWidth > innerWidth);
    assert(!overflow && !errors.length, 'Overflow or JS exception');
    rows.push({width, requestsBeforeAction:0, requestsAfterTwoClicks:requests, outerShiftPx:after-before,
      boxHeight:boxAfter.height, overflow, errors, outboundEvent:tracked[0][1],
      accessibleButton:await button.innerText(), linkName:await page.locator('.zl-url').innerText()});
    await page.screenshot({path:`${out}/local-${width}.png`, fullPage:true});
    // Failed load + Space keyboard retry: fresh local document, same real JS.
    await page.setContent(fixture);
    fail = true;
    await page.addScriptTag({content:main});
    await page.locator('[data-di-doctoralia-load]').focus();
    await page.keyboard.press('Space');
    await page.waitForFunction(() => document.querySelector('[role=status]').textContent.includes('No se han podido'));
    assert(await page.locator('.zl-url').isVisible(), 'Missing fallback');
    fail = false;
    await page.keyboard.press('Space');
    await page.locator('iframe').waitFor();
    rows[rows.length-1].failedLoadAndKeyboardRetry = true;
    await page.close();
  }
  const page = await browser.newPage();
  await page.route('**/*', route => route.abort());
  const publicRows = [];
  for (const path of ['/', '/dra-irina-gonzalez-saez/', '/contacto/', '/primera-consulta/', '/sueno/', '/otorrinolaringologia/', '/preguntas-frecuentes/', '/sueno/cpap/']) {
    const response = await page.request.get(base+path);
    const html = await response.text();
    const hasWidget = html.includes('data-di-doctoralia') || /<script[^>]+src=["'][^"']*(?:docplanner|doctoralia)/i.test(html);
    assert(response.status() === 200 && !hasWidget, 'Live activation unexpected');
    publicRows.push({path,status:response.status(),hasWidget,csp:response.headers()['content-security-policy'] || null});
  }
  const noJs = await browser.newPage({javaScriptEnabled:false});
  await noJs.route('**/*', route => route.abort());
  await noJs.setContent(fixture);
  const noJsFallback = await noJs.locator('.zl-url').isVisible() && !await noJs.locator('[data-di-doctoralia-load]').isVisible();
  assert(noJsFallback, 'No-JS fallback');
  await browser.close();
  fs.writeFileSync(`${out}/browser.json`, JSON.stringify({rows,publicRows,noJsFallback,
    limits:'Local synthetic HTML + real theme CSS/JS/template and analytics; synthetic iframe, never real widget. Zero actual third-party requests allowed. Outer geometry stability is not real-widget CLS/LCP certification. Public checks are GET source only, not authenticated deployment or activation.'}, null, 2)+'\n');
  console.log('Doctoralia: keyboard/load/retry/layout/events pass at 390/1440; 8 public pages inactive.');
})().catch(e => { console.error(e.name, e.message); process.exit(1); });
