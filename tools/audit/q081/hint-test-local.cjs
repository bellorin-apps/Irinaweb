// Adaptación LOCAL de hint-test.js (Q-081) a la copia aislada: Playwright de tmp/wp-en-local/node, Chrome local y puerto 8890.
// Uso: node tools/audit/q081/hint-test-local.cjs <archivo con una ruta ES sin pareja> <carpeta de capturas>. Mismos 37 casos.
// Q-081 (D-105): pruebas sintéticas del aviso inglés. Uso: node hint-test.js <archivo con una ruta ES sin pareja> <carpeta de capturas>; servidor en localhost:8089 con /en/ publicada (solo en local).
const { chromium } = require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core');
const B = 'http://127.0.0.1:8890';
const results = []; const ok = (name, cond, extra = '') => results.push(`${cond ? 'OK  ' : 'FAIL'} ${name}${extra ? ' — ' + extra : ''}`);
(async () => {
  const b = await chromium.launch({executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  const ctx = async (opts = {}) => b.newContext({ viewport: { width: 1440, height: 900 }, ...opts });
  const vis = (p) => p.evaluate(() => { const h = document.querySelector('.di-en-hint'); return !!h && !h.hidden && getComputedStyle(h).display !== 'none' && getComputedStyle(h).visibility !== 'hidden' && Number(getComputedStyle(h).opacity) > 0; });
  const langs = (arr) => `Object.defineProperty(navigator,'languages',{get:()=>${JSON.stringify(arr)}});Object.defineProperty(navigator,'language',{get:()=>${JSON.stringify(arr[0])}});`;

  let c = await ctx(); let p = await c.newPage(); await p.addInitScript(langs(['en-US', 'en']));
  await p.goto(B + '/primera-consulta/'); await p.waitForTimeout(400);
  ok('en-US en /primera-consulta/: visible', await vis(p));
  ok('enlace a /en/first-visit/', (await p.getAttribute('.di-en-hint__go', 'href')).endsWith('/en/first-visit/'));
  ok('texto', (await p.textContent('.di-en-hint__t')) === 'This page is also available in English.');
  ok('cierre aria-label Close', (await p.getAttribute('.di-en-hint__x', 'aria-label')) === 'Close');
  ok('no roba el foco', await p.evaluate(() => !document.activeElement.closest('.di-en-hint')));
  // Teclado: el enlace y el cierre son alcanzables con Tab; Escape cierra.
  let reach = { go: false, x: false };
  for (let i = 0; i < 80 && !(reach.go && reach.x); i++) { await p.keyboard.press('Tab'); const cls = await p.evaluate(() => document.activeElement.className || ''); if (cls.includes('di-en-hint__go')) reach.go = true; if (cls.includes('di-en-hint__x')) reach.x = true; }
  ok('Tab alcanza «View in English» y «Close»', reach.go && reach.x);
  await p.focus('.di-en-hint__go'); await p.keyboard.press('Escape'); await p.waitForTimeout(100);
  ok('Escape cierra', !(await vis(p)));
  ok('cierre guardado', (await p.evaluate(() => localStorage.getItem('di-en-hint'))) === 'closed');
  await p.reload(); await p.waitForTimeout(300); ok('tras cerrar, no reaparece al recargar', !(await vis(p)));
  await p.goto(B + '/sueno/ronquido/'); await p.waitForTimeout(300); ok('tras cerrar, tampoco en otra página', !(await vis(p)));
  await c.close();

  c = await ctx(); p = await c.newPage(); await p.addInitScript(langs(['en-US']));
  await p.goto(B + '/'); await p.waitForTimeout(300); ok('portada en-US: visible', await vis(p));
  await p.click('.di-en-hint__go'); await p.waitForLoadState(); ok('«View in English» lleva a /en/', p.url().endsWith('/en/'));
  ok('elección guardada «en»', (await p.evaluate(() => localStorage.getItem('di-en-hint'))) === 'en');
  ok('/en/ no tiene aviso', (await p.locator('.di-en-hint').count()) === 0);
  await c.close();

  for (const [name, arr] of [['es-MX', ['es-MX', 'en']], ['es', ['es']], ['en secundario', ['es-MX', 'en-US']], ['eng (no en-*)', ['eng']]]) {
    c = await ctx(); p = await c.newPage(); await p.addInitScript(langs(arr)); await p.goto(B + '/primera-consulta/'); await p.waitForTimeout(300);
    ok(`${name}: oculto`, !(await vis(p))); await c.close();
  }
  for (const arr of [['en-GB'], ['EN-us'], ['en']]) {
    c = await ctx(); p = await c.newPage(); await p.addInitScript(langs(arr)); await p.goto(B + '/primera-consulta/'); await p.waitForTimeout(300);
    ok(`${arr[0]}: visible`, await vis(p)); await c.close();
  }
  c = await ctx(); p = await c.newPage(); await p.addInitScript(langs(['en-US']));
  for (const u of ['/gracias/', require('fs').readFileSync(process.argv[2], 'utf8').trim(), '/en/first-visit/', '/en/sleep/snoring/']) {
    await p.goto(B + u); await p.waitForTimeout(200); ok(`${u}: sin contenedor`, (await p.locator('.di-en-hint').count()) === 0);
  }
  await c.close();

  // localStorage bloqueado: se muestra, el cierre vale para la vista y no hay errores.
  c = await ctx(); p = await c.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message));
  await p.addInitScript(langs(['en-US']) + "Object.defineProperty(window,'localStorage',{get(){throw new Error('blocked')}});");
  await p.goto(B + '/primera-consulta/'); await p.waitForTimeout(300); ok('storage bloqueado: visible', await vis(p));
  await p.click('.di-en-hint__x'); await p.waitForTimeout(100); ok('storage bloqueado: cierre en la vista', !(await vis(p)));
  await p.reload(); await p.waitForTimeout(300); ok('storage bloqueado: reaparece al recargar (sin persistencia)', await vis(p));
  ok('storage bloqueado: sin errores JS', errs.length === 0, errs.join('; '));
  await c.close();

  // Selector de idioma recuerda la elección.
  c = await ctx(); p = await c.newPage(); await p.addInitScript(langs(['en-US']));
  await p.goto(B + '/primera-consulta/'); await p.waitForTimeout(300);
  const sel = p.locator('a.di-lang').first();
  if (await sel.count()) { await sel.evaluate(a => a.addEventListener('click', e => e.preventDefault())); await sel.click({ force: true }); ok('selector guarda «chosen»', (await p.evaluate(() => localStorage.getItem('di-en-hint'))) === 'chosen'); } else ok('selector presente', false);
  await c.close();

  // Móvil: no tapa la barra inferior; horizontal: no tapa la columna; menú abierto: oculto; sin desplazar el contenido (CLS).
  for (const [name, vp] of [['móvil 390×844', { width: 390, height: 844 }], ['horizontal 844×390', { width: 844, height: 390 }], ['escritorio 1440×900', { width: 1440, height: 900 }]]) {
    c = await ctx({ viewport: vp, isMobile: vp.width < 900, hasTouch: vp.width < 900 }); p = await c.newPage(); await p.addInitScript(langs(['en-US']));
    await p.goto(B + '/primera-consulta/'); await p.waitForTimeout(600);
    const r = await p.evaluate(() => { const h = document.querySelector('.di-en-hint').getBoundingClientRect(); const bar = document.querySelector('.di-mobile-bar'); const bs = bar && getComputedStyle(bar).display !== 'none' ? [...bar.querySelectorAll('a')].map(a => a.getBoundingClientRect()) : []; const hit = bs.some(b => !(h.right <= b.left || h.left >= b.right || h.bottom <= b.top || h.top >= b.bottom)); return { hit, inView: h.top >= 0 && h.bottom <= innerHeight && h.left >= 0 && h.right <= innerWidth, w: Math.round(h.width), h: Math.round(h.height), pos: getComputedStyle(document.querySelector('.di-en-hint')).position }; });
    ok(`${name}: no tapa la barra móvil`, !r.hit, JSON.stringify(r)); ok(`${name}: dentro de la pantalla y fijo`, r.inView && r.pos === 'fixed');
    await p.screenshot({ path: `${process.argv[3]}/hint-${vp.width}x${vp.height}.png` });
    await c.close();
  }
  c = await ctx({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true }); p = await c.newPage(); await p.addInitScript(langs(['en-US']));
  await p.goto(B + '/primera-consulta/'); await p.waitForTimeout(400);
  const toggle = p.locator('.di-menu-toggle, [aria-controls*="menu"]').first();
  if (await toggle.count()) { await toggle.click(); await p.waitForTimeout(300); ok('menú abierto: aviso oculto', !(await vis(p))); } else ok('botón de menú encontrado', false);
  await c.close();

  await b.close(); console.log(results.join('\n')); console.log(`\n${results.filter(r => r.startsWith('FAIL')).length} fallos de ${results.length}`);
})();
