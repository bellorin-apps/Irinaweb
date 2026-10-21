// P3 de Q-081 en producción (solo navegación; sin formularios): invariante del aviso en transiciones reales.
// Invariante: si el aviso (con margen de 8 px) colisiona con un botón del hero, está en espera (invisible, sin clics ni foco);
// si no colisiona, está visible y no tapa nada. Se comprueba al cargar, con scroll de ida y vuelta, giro de pantalla, resize,
// tras load/fuentes y con menú abrir → resize → cerrar.
// Uso: node tools/audit/q081/transiciones-prod.cjs [base] [carpeta de capturas]
const { chromium } = require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core');
const B = (process.argv[2] || 'https://drairinagonzalez.com').replace(/\/$/, '');
const SHOTS = process.argv[3] || '';
const res = []; const ok = (n, c, x = '') => res.push(`${c ? 'OK  ' : 'FAIL'} ${n}${x ? ' — ' + x : ''}`);
const state = () => {
  const h = document.querySelector('.di-en-hint'); const s = getComputedStyle(h); const r = h.getBoundingClientRect(); const m = 8;
  const hit = [...document.querySelectorAll('.di-bleed a, .di-bleed button')].some(el => { const e = el.getBoundingClientRect(); return e.width > 0 && e.height > 0 && e.bottom > r.top - m && e.top < r.bottom + m && e.right > r.left - m && e.left < r.right + m; });
  const shown = s.display !== 'none' && s.visibility !== 'hidden' && Number(s.opacity) > 0;
  const cx = Math.round(r.left + r.width / 2), cy = Math.round(r.top + r.height / 2);
  const topEl = r.width && cy > 0 && cy < innerHeight ? document.elementFromPoint(cx, cy) : null;
  return { hit, shown, waiting: h.classList.contains('is-waiting'), pe: s.pointerEvents, vis: s.visibility, display: s.display, clickGoesToHint: !!(topEl && h.contains(topEl)), menuOpen: document.body.classList.contains('menu-open') };
};
const inv = (st) => st.display === 'none' ? true : (st.hit ? (st.waiting && !st.shown && st.pe === 'none' && !st.clickGoesToHint) : (st.shown && !st.waiting));
(async () => {
  const b = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  const c = await b.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
  const p = await c.newPage();
  await p.addInitScript("Object.defineProperty(navigator,'languages',{get:()=>['en-US','en']});Object.defineProperty(navigator,'language',{get:()=>'en-US'});");
  await p.goto(B + '/primera-consulta/', { waitUntil: 'load' }); await p.evaluate(() => document.fonts && document.fonts.ready); await p.waitForTimeout(500);
  let s = await p.evaluate(state); ok('carga + fuentes (390×844): colisión → en espera', s.hit && inv(s), JSON.stringify(s));
  // Sin foco: Tab nunca entra en el aviso mientras espera.
  let focusInHint = false;
  for (let i = 0; i < 40; i++) { await p.keyboard.press('Tab'); if (await p.evaluate(() => !!document.activeElement.closest('.di-en-hint'))) { focusInHint = true; break; } }
  ok('en espera: el teclado no entra en el aviso', !focusInHint);
  await p.evaluate(() => { document.activeElement && document.activeElement.blur(); window.scrollTo(0, 0); }); await p.waitForTimeout(200);
  if (SHOTS) await p.screenshot({ path: `${SHOTS}/trans-1-carga.png` });
  await p.evaluate(() => window.scrollTo(0, 180)); await p.waitForTimeout(400);
  s = await p.evaluate(state); ok('scroll ida (+180): visible sin tapar', !s.hit && s.shown && inv(s), JSON.stringify(s));
  if (SHOTS) await p.screenshot({ path: `${SHOTS}/trans-2-scroll.png` });
  await p.evaluate(() => window.scrollTo(0, 0)); await p.waitForTimeout(150);
  s = await p.evaluate(state); ok('scroll vuelta (0): vuelve a esperar al instante', s.hit && inv(s), JSON.stringify(s));
  await p.setViewportSize({ width: 844, height: 390 }); await p.waitForTimeout(500);
  s = await p.evaluate(state); ok('giro a horizontal 844×390: invariante', inv(s), JSON.stringify(s));
  if (SHOTS) await p.screenshot({ path: `${SHOTS}/trans-3-horizontal.png` });
  await p.evaluate(() => window.scrollTo(0, 220)); await p.waitForTimeout(400);
  s = await p.evaluate(state); ok('horizontal + scroll: invariante', inv(s), JSON.stringify(s));
  await p.setViewportSize({ width: 390, height: 844 }); await p.evaluate(() => window.scrollTo(0, 0)); await p.waitForTimeout(500);
  s = await p.evaluate(state); ok('vuelta a vertical: invariante', inv(s), JSON.stringify(s));
  // Menú abrir → resize → cerrar.
  const toggle = p.locator('.di-menu-toggle, [aria-controls*="menu"]').first();
  await p.evaluate(() => window.scrollTo(0, 180)); await p.waitForTimeout(300);
  await toggle.click(); await p.waitForTimeout(350);
  s = await p.evaluate(state); ok('menú abierto: aviso oculto', s.menuOpen && s.display === 'none', JSON.stringify(s));
  await p.setViewportSize({ width: 414, height: 896 }); await p.waitForTimeout(250);
  await p.evaluate(() => window.scrollTo(0, 0)); await p.waitForTimeout(150);
  await p.keyboard.press('Escape'); await p.waitForTimeout(200);
  if (await p.evaluate(() => document.body.classList.contains('menu-open'))) { await toggle.click(); await p.waitForTimeout(350); }
  s = await p.evaluate(state); ok('menú cerrar tras resize: invariante (sin reaparecer sobre el CTA)', !s.menuOpen && inv(s), JSON.stringify(s));
  if (SHOTS) await p.screenshot({ path: `${SHOTS}/trans-4-menu-cerrado.png` });
  await b.close(); console.log(res.join('\n')); console.log(`\n${res.filter(r => r.startsWith('FAIL')).length} fallos de ${res.length}`);
  process.exit(res.some(r => r.startsWith('FAIL')) ? 1 : 0);
})();
