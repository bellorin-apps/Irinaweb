// Producción, solo navegación: navegador en inglés, de /en/first-visit/ a ES con el selector → el aviso no debe aparecer (ajuste de Codex 4dcdc13).
const { chromium } = require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core');
(async () => {
  const b = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  const c = await b.newContext({ viewport: { width: 1440, height: 900 } }); const p = await c.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message));
  await p.addInitScript("Object.defineProperty(navigator,'languages',{get:()=>['en-US','en']});Object.defineProperty(navigator,'language',{get:()=>'en-US'});");
  await p.goto('https://drairinagonzalez.com/en/first-visit/'); await p.waitForTimeout(500);
  await p.locator('a.di-lang').first().click(); await p.waitForLoadState('load'); await p.waitForTimeout(600);
  const r = await p.evaluate(() => { const h = document.querySelector('.di-en-hint'); return { url: location.pathname, guardado: localStorage.getItem('di-en-hint'), contenedor: !!h, visible: !!h && !h.hidden && getComputedStyle(h).display !== 'none' }; });
  const ok = r.url === '/primera-consulta/' && r.guardado === 'chosen' && r.contenedor && !r.visible && !errs.length;
  console.log((ok ? 'OK  ' : 'FAIL') + ' EN→ES con el selector: ' + JSON.stringify(r) + (errs.length ? ' errores: ' + errs.join('; ') : ''));
  await b.close();
})();
