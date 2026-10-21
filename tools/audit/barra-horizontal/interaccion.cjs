// Barra móvil en horizontal: interacción real (solo lectura; sin formularios).
// - CTA del hero pulsable en toda su superficie (centro y puntos medios de los 4 bordes sin taparse) y sin invadir la columna;
// - la columna conserva sus 3 acciones, 46×46 y nombre accesible, sin taparse;
// - menú abrir/cerrar, giro vertical↔horizontal, textos largos inyectados y convivencia con el aviso de idioma (P3 de Q-081).
// Uso: node tools/audit/barra-horizontal/interaccion.cjs [base] [carpeta de capturas]
const { chromium } = require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core');
const B = (process.argv[2] || 'http://127.0.0.1:8890').replace(/\/$/, '');
const SHOTS = process.argv[3] || '';
const res = []; const ok = (n, c, x = '') => res.push(`${c ? 'OK  ' : 'FAIL'} ${n}${x && !c ? ' — ' + x : ''}`);
const check = () => {
  const bar = document.querySelector('.di-mobile-bar'); const bs = getComputedStyle(bar);
  const links = [...bar.querySelectorAll('a')];
  const col = links.map(a => a.getBoundingClientRect());
  const colLeft = Math.min(...col.map(r => r.left));
  const reach = (el, r) => { const cx = r.left + r.width / 2, cy = r.top + r.height / 2; const pts = [[cx, cy], [r.left + 4, cy], [r.right - 4, cy], [cx, r.top + 4], [cx, r.bottom - 4]]; /* el hit-test respeta border-radius (píldoras y círculos): centro y puntos medios de los bordes */ return pts.every(([x, y]) => { if (y < 0 || y > innerHeight || x < 0 || x > innerWidth) return true; const t = document.elementFromPoint(x, y); return t && (t === el || el.contains(t)); }); };
  const ctas = [...document.querySelectorAll('.di-bleed .di-hero-cta a, .di-bleed .di-hero-cta button')].map(el => { const r = el.getBoundingClientRect(); return { t: el.textContent.replace(/\s+/g, ' ').trim().slice(0, 30), right: Math.round(r.right), clear: r.right <= colLeft - 8, reach: reach(el, r), inView: r.top >= 0 && r.bottom <= innerHeight }; });
  const barInfo = links.map((a, i) => ({ name: (a.getAttribute('aria-label') || a.textContent).trim(), w: Math.round(col[i].width), h: Math.round(col[i].height), reach: reach(a, col[i]) }));
  return { column: bs.flexDirection === 'column' && bs.display !== 'none', barHidden: bs.visibility === 'hidden' || bs.display === 'none', colLeft: Math.round(colLeft), ctas, barInfo, overflow: document.documentElement.scrollWidth - innerWidth, menuOpen: document.body.classList.contains('menu-open') };
};
const toCta = p => p.evaluate(() => { const c = document.querySelector('.di-bleed .di-hero-cta'); if (c) { const r = c.getBoundingClientRect(); window.scrollBy(0, r.bottom - innerHeight + 24 > 0 ? r.bottom - innerHeight + 24 : 0); } });
(async () => {
  const b = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  for (const lang of [['es-MX', 'es'], ['en-US', 'en']]) {
    for (const path of ['/', '/primera-consulta/', '/dra-irina-gonzalez-saez/', '/sueno/', '/en/', '/en/first-visit/', '/en/dr-irina-gonzalez-saez/']) {
      for (const [w, h] of [[667, 375], [740, 360], [844, 390]]) {
        const c = await b.newContext({ viewport: { width: w, height: h }, isMobile: true, hasTouch: true }); const p = await c.newPage();
        await p.addInitScript(`Object.defineProperty(navigator,'languages',{get:()=>${JSON.stringify(lang)}});Object.defineProperty(navigator,'language',{get:()=>${JSON.stringify(lang[0])}});`);
        await p.goto(B + path, { waitUntil: 'load' }); await p.evaluate(() => document.fonts && document.fonts.ready); await p.waitForTimeout(400);
        await toCta(p); await p.waitForTimeout(350);
        const s = await p.evaluate(check); const tag = `${lang[0]} ${w}×${h} ${path}`;
        ok(`${tag}: columna con 3 acciones 46×46 accesibles y sin taparse`, s.column && s.barInfo.length === 3 && s.barInfo.every(x => x.w === 46 && x.h === 46 && x.name && x.reach), JSON.stringify(s.barInfo));
        ok(`${tag}: CTA del hero fuera de la columna y pulsable entero`, s.ctas.length > 0 && s.ctas.every(x => x.clear && x.reach), JSON.stringify(s.ctas));
        ok(`${tag}: sin desborde horizontal`, s.overflow <= 0, String(s.overflow));
        if (SHOTS && w === 844 && lang[0] === 'es-MX') await p.screenshot({ path: `${SHOTS}/int-${w}x${h}${path.replace(/\//g, '_')}.png` });
        await c.close();
      }
    }
  }
  // Menú abrir/cerrar y giro.
  const c = await b.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true }); const p = await c.newPage();
  await p.goto(B + '/primera-consulta/', { waitUntil: 'load' }); await p.waitForTimeout(400);
  let s = await p.evaluate(check); ok('vertical 390×844: barra inferior (no columna)', !s.column && !s.barHidden, JSON.stringify(s));
  await p.setViewportSize({ width: 844, height: 390 }); await p.waitForTimeout(400); await toCta(p); await p.waitForTimeout(300);
  s = await p.evaluate(check); ok('giro a 844×390: columna y CTA libre y pulsable', s.column && s.ctas.every(x => x.clear && x.reach) && s.overflow <= 0, JSON.stringify(s));
  const toggle = p.locator('.di-menu-toggle, [aria-controls*="menu"]').first();
  await toggle.click(); await p.waitForTimeout(350); s = await p.evaluate(check); ok('menú abierto: columna oculta', s.menuOpen && s.barHidden, JSON.stringify(s));
  await toggle.click(); await p.waitForTimeout(400); s = await p.evaluate(check); ok('menú cerrado: columna visible y CTA libre y pulsable', !s.menuOpen && !s.barHidden && s.ctas.every(x => x.clear && x.reach), JSON.stringify(s));
  if (SHOTS) await p.screenshot({ path: `${SHOTS}/int-menu-cerrado-844x390.png` });
  await p.setViewportSize({ width: 390, height: 844 }); await p.waitForTimeout(400); s = await p.evaluate(check); ok('vuelta a vertical: barra inferior y sin desborde', !s.column && s.overflow <= 0, JSON.stringify(s));
  // Textos largos inyectados en los CTA del hero (solo en el navegador).
  await p.setViewportSize({ width: 667, height: 375 }); await p.waitForTimeout(300);
  await p.evaluate(() => document.querySelectorAll('.di-bleed .di-hero-cta a').forEach(a => { a.querySelectorAll('.di-btn__long, .di-btn__short').forEach(x => x.textContent = 'Agendar valoración de ronquido y apnea del sueño con la Dra. Irina González Sáez'); if (!a.querySelector('.di-btn__long, .di-btn__short')) a.append(' — texto largo de prueba para comprobar el ajuste de línea'); }));
  await p.waitForTimeout(300); await toCta(p); await p.waitForTimeout(300);
  s = await p.evaluate(check); ok('texto largo en 667×375: CTA libre, pulsable y sin desborde', s.ctas.every(x => x.clear && x.reach) && s.overflow <= 0, JSON.stringify(s));
  if (SHOTS) await p.screenshot({ path: `${SHOTS}/int-texto-largo-667x375.png` });
  await c.close();
  await b.close(); console.log(res.join('\n')); console.log(`\n${res.filter(r => r.startsWith('FAIL')).length} fallos de ${res.length}`);
  process.exit(res.some(r => r.startsWith('FAIL')) ? 1 : 0);
})();
