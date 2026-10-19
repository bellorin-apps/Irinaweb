// Envío del formulario EN con el JS real (fetch), correo desactivado: texto mostrado y respuesta JSON del servidor.
const path = require('node:path');
const {chromium} = require(path.join(__dirname, '..', 'node', 'node_modules', 'playwright-core'));
const S = 'http://127.0.0.1:8890';
(async () => {
  const b = await chromium.launch({executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  const c = await b.newContext({viewport: {width: 390, height: 844}, isMobile: true, hasTouch: true});
  await c.route('**/*', r => new URL(r.request().url()).hostname === '127.0.0.1' ? r.continue() : r.abort());
  const p = await c.newPage(); const resp = [];
  p.on('response', async r => { if (r.request().method() === 'POST') { let t = ''; try { t = await r.text(); } catch (e) { t = String(e).slice(0, 60); } resp.push({status: r.status(), body: t.slice(0, 200)}); } });
  await p.goto(S + '/en/contact/', {waitUntil: 'networkidle'}); await p.waitForTimeout(6000);
  const f = p.locator('form.di-form');
  await f.locator('[name=di_name]').fill('Prueba Sintética'); await f.locator('[name=di_phone]').fill('8100000000');
  await f.locator('select[name=di_motivo]').selectOption({index: 1}); await f.locator('[name=di_consent]').check();
  await f.locator('[type=submit]').first().click(); await p.waitForTimeout(5000);
  console.log(JSON.stringify({url: p.url().replace(S, ""), statuses: await p.$$eval('.di-form__status', els => els.map(e => ({hidden: e.hidden, text: e.textContent.trim()}))), resp}, null, 1));
  await b.close();
})();
