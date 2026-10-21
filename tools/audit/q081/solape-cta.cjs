// P3 de Q-081: el aviso sugerido no debe tapar botones ni enlaces visibles del hero, ni la barra móvil.
// Por página española con pareja y por tamaño, con el navegador en inglés:
//   1) al cargar: o el aviso está visible y no tapa nada, o espera invisible (.is-waiting) porque taparía un botón del hero;
//   2) tras desplazar 160 px: el aviso está visible y no tapa ningún botón del hero ni de la barra móvil.
// Solo lectura. Uso: node tools/audit/q081/solape-cta.cjs [base] [carpeta de capturas]   (por defecto, la copia aislada 127.0.0.1:8890)
const { chromium } = require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core');
const B = (process.argv[2] || 'http://127.0.0.1:8890').replace(/\/$/, '');
const SHOTS = process.argv[3] || '';
const PAGES = ['/', '/primera-consulta/', '/otorrinolaringologia/', '/sueno/', '/dra-irina-gonzalez-saez/', '/contacto/', '/preguntas-frecuentes/', '/sueno/apnea-obstructiva-del-sueno/', '/aviso-de-privacidad/'];
const VPS = [[360, 640], [375, 667], [390, 844], [414, 896], [430, 932], [844, 390], [1440, 900]];
const measure = () => {
  const hint = document.querySelector('.di-en-hint');
  if (!hint || hint.hidden || getComputedStyle(hint).display === 'none') return { state: 'none' };
  const hs = getComputedStyle(hint);
  const waiting = hint.classList.contains('is-waiting') || hs.visibility === 'hidden';
  const hr = hint.getBoundingClientRect();
  const cut = el => { const e = el.getBoundingClientRect(); if (!e.width || !e.height) return 0; const s = getComputedStyle(el); if (s.visibility === 'hidden' || s.display === 'none') return 0; const ix = Math.min(hr.right, e.right) - Math.max(hr.left, e.left), iy = Math.min(hr.bottom, e.bottom) - Math.max(hr.top, e.top); return ix > 0 && iy > 0 ? Math.round(iy) : 0; };
  const label = el => (el.textContent || el.getAttribute('aria-label') || '').replace(/\s+/g, ' ').trim().slice(0, 22);
  const hero = [...document.querySelectorAll('.di-bleed a, .di-bleed button')].map(el => [label(el), cut(el)]).filter(x => x[1]);
  const bar = document.querySelector('.di-mobile-bar');
  const barHits = bar && getComputedStyle(bar).display !== 'none' ? [...bar.querySelectorAll('a, button')].map(el => [label(el), cut(el)]).filter(x => x[1]) : [];
  const other = [...document.querySelectorAll('main a, main button')].filter(el => !el.closest('.di-bleed') && !hint.contains(el)).map(el => [label(el), cut(el)]).filter(x => x[1]);
  return { state: waiting ? 'waiting' : 'visible', top: Math.round(hr.top), bottom: Math.round(hr.bottom), hero, barHits, other };
};
const fmt = r => r.state === 'none' ? 'sin aviso' : `${r.state} ${r.top}–${r.bottom}` + (r.hero.length ? ` · hero: ${r.hero.map(x => x.join(' ') + 'px').join(' | ')}` : '') + (r.barHits.length ? ` · barra: ${r.barHits.map(x => x.join(' ')).join(' | ')}` : '') + (r.other.length ? ` · contenido: ${r.other.map(x => x.join(' ') + 'px').join(' | ')}` : '');
(async () => {
  const b = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  let bad = 0; const rows = [];
  for (const [w, h] of VPS) {
    const c = await b.newContext({ viewport: { width: w, height: h }, isMobile: w < 900, hasTouch: w < 900 });
    for (const p of PAGES) {
      const pg = await c.newPage();
      await pg.addInitScript("Object.defineProperty(navigator,'languages',{get:()=>['en-US','en']});Object.defineProperty(navigator,'language',{get:()=>'en-US'});");
      await pg.goto(B + p, { waitUntil: 'load' }); await pg.waitForTimeout(700);
      const a = await pg.evaluate(measure);
      if (SHOTS && p === '/primera-consulta/') await pg.screenshot({ path: `${SHOTS}/p3-${w}x${h}-carga.png` });
      await pg.evaluate(() => window.scrollBy(0, 160)); await pg.waitForTimeout(500);
      const d = await pg.evaluate(measure);
      if (SHOTS && p === '/primera-consulta/') await pg.screenshot({ path: `${SHOTS}/p3-${w}x${h}-scroll160.png` });
      const okLoad = a.state !== 'none' && (a.state === 'waiting' || (!a.hero.length && !a.barHits.length));
      const okScroll = d.state === 'visible' && !d.hero.length && !d.barHits.length;
      const ok = okLoad && okScroll;
      if (!ok) bad++;
      rows.push(`${ok ? 'OK  ' : 'FAIL'} ${w}×${h} ${p}\n       carga: ${fmt(a)}\n       +160px: ${fmt(d)}`);
      await pg.close();
    }
    await c.close();
  }
  await b.close(); console.log(rows.join('\n')); console.log(`\n${bad} fallos de ${rows.length}`);
  process.exit(bad ? 1 : 0);
})();
