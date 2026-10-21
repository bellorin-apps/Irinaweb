// Barra móvil en horizontal (columna lateral WhatsApp / Llamar / Cómo llegar): ¿algún botón o enlace del contenido pasa por debajo?
// Recorre cada página (ES y su pareja EN) en varios tamaños, al cargar y desplazando en pasos de media pantalla hasta el final.
// Informa del solape con los botones de la barra (hero y resto por separado), del área pulsable de la barra y del desborde horizontal.
// Solo lectura. Uso: node tools/audit/barra-horizontal/solape-barra.cjs [base] [carpeta de capturas] [rutas separadas por coma] [tamaños AxB,...]
// Banco con HTML público: DI_CSS_CANDIDATO=<components.css local> sustituye, solo dentro del navegador de prueba, el components.css servido.
const { chromium } = require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core');
const B = (process.argv[2] || 'http://127.0.0.1:8890').replace(/\/$/, '');
const SHOTS = process.argv[3] || '';
const ES = ['/', '/otorrinolaringologia/', '/sueno/', '/dra-irina-gonzalez-saez/', '/primera-consulta/', '/contacto/', '/preguntas-frecuentes/', '/aviso-de-privacidad/', '/aviso-medico/', '/terminos-de-uso/', '/sueno/ronquido/', '/sueno/apnea-obstructiva-del-sueno/', '/sueno/estudio-del-sueno/', '/sueno/cpap/', '/sueno/cirugia-de-ronquido-y-apnea/'];
const EN = ['/en/', '/en/ent/', '/en/sleep/', '/en/dr-irina-gonzalez-saez/', '/en/first-visit/', '/en/contact/', '/en/faq/', '/en/privacy-notice/', '/en/medical-disclaimer/', '/en/terms-of-use/', '/en/sleep/snoring/', '/en/sleep/obstructive-sleep-apnea/', '/en/sleep/home-sleep-test/', '/en/sleep/cpap-therapy/', '/en/sleep/snoring-and-sleep-apnea-surgery/'];
const PAGES = process.argv[4] ? process.argv[4].split(',') : ES.concat(EN);
const VPS = process.argv[5] ? process.argv[5].split(',').map(v => v.split('x').map(Number)) : [[667, 375], [740, 360], [844, 390], [932, 430], [375, 667], [390, 844], [1440, 900]];
const CSS = process.env.DI_CSS_CANDIDATO ? require('fs').readFileSync(process.env.DI_CSS_CANDIDATO, 'utf8') : null;
let sustituidos = 0;
const probe = () => {
  const bar = document.querySelector('.di-mobile-bar');
  const barOn = !!bar && getComputedStyle(bar).display !== 'none' && getComputedStyle(bar).visibility !== 'hidden';
  const column = barOn && getComputedStyle(bar).flexDirection === 'column';
  const btns = barOn ? [...bar.querySelectorAll('a')].map(a => a.getBoundingClientRect()) : [];
  const zone = btns.length ? { l: Math.min(...btns.map(r => r.left)), r: Math.max(...btns.map(r => r.right)), t: Math.min(...btns.map(r => r.top)), b: Math.max(...btns.map(r => r.bottom)) } : null;
  const label = el => (el.textContent || el.getAttribute('aria-label') || el.getAttribute('name') || el.tagName).replace(/\s+/g, ' ').trim().slice(0, 26);
  const hits = { hero: [], other: [] };
  if (column) {
    const els = [...document.querySelectorAll('main a, main button, main input, main select, main textarea, main summary, footer a, footer button, .di-bleed a, .di-bleed button')]
      .filter(el => !bar.contains(el) && !el.closest('.di-en-hint, .di-header'));
    for (const el of els) {
      const s = getComputedStyle(el); if (s.visibility === 'hidden' || s.display === 'none') continue;
      const e = el.getBoundingClientRect(); if (!e.width || !e.height) continue;
      for (const r of btns) {
        const ix = Math.min(r.right, e.right) - Math.max(r.left, e.left), iy = Math.min(r.bottom, e.bottom) - Math.max(r.top, e.top);
        if (ix > 0 && iy > 0) { (el.closest('.di-bleed') ? hits.hero : hits.other).push(`${label(el)} ${Math.round(ix)}×${Math.round(iy)}`); break; }
      }
    }
  }
  const sizes = btns.map(r => `${Math.round(r.width)}×${Math.round(r.height)}`);
  const ctas = [...document.querySelectorAll('.di-bleed .di-hero-cta a, .di-bleed .di-hero-cta button')].map(el => { const e = el.getBoundingClientRect(); return { t: label(el), w: Math.round(e.width), r: Math.round(innerWidth - e.right) }; });
  return { barOn, column, zone, sizes, hits, overflow: document.documentElement.scrollWidth - innerWidth, ctas, y: Math.round(scrollY), max: document.documentElement.scrollHeight - innerHeight };
};
(async () => {
  const b = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  let bad = 0; const rows = [];
  for (const [w, h] of VPS) {
    const c = await b.newContext({ viewport: { width: w, height: h }, isMobile: w < 900, hasTouch: w < 900 });
    if (CSS) await c.route(/\/assets\/css\/components\.css/, r => { sustituidos++; return r.fulfill({ status: 200, contentType: 'text/css', body: CSS }); });
    for (const p of PAGES) {
      const pg = await c.newPage();
      await pg.goto(B + p, { waitUntil: 'load' }); await pg.evaluate(() => document.fonts && document.fonts.ready); await pg.waitForTimeout(400);
      const first = await pg.evaluate(probe);
      if (SHOTS && w === 844 && ['/', '/primera-consulta/', '/dra-irina-gonzalez-saez/', '/en/', '/en/dr-irina-gonzalez-saez/'].includes(p)) await pg.screenshot({ path: `${SHOTS}/${w}x${h}${p.replace(/\//g, '_') || '_'}.png` });
      const heroHits = new Set(first.hits.hero), otherHits = new Set(first.hits.other); let overflow = first.overflow;
      if (first.column) {
        for (let y = Math.round(h / 2); y <= first.max + h / 2; y += Math.round(h / 2)) {
          await pg.evaluate(v => window.scrollTo(0, v), y); await pg.waitForTimeout(120);
          const s = await pg.evaluate(probe); s.hits.hero.forEach(x => heroHits.add(x)); s.hits.other.forEach(x => otherHits.add(x)); overflow = Math.max(overflow, s.overflow);
        }
      }
      const tapOk = !first.column || (first.sizes.length === 3 && first.sizes.every(s => s === '46×46'));
      const ok = heroHits.size === 0 && otherHits.size === 0 && overflow <= 0 && tapOk;
      if (!ok) bad++;
      rows.push(`${ok ? 'OK  ' : 'FAIL'} ${w}×${h} ${p} ${first.column ? `columna x ${Math.round(first.zone.l)}–${Math.round(first.zone.r)} botones ${first.sizes.join(',')}` : first.barOn ? 'barra inferior' : 'sin barra'}`
        + ` · CTA hero: ${first.ctas.map(c => `«${c.t}» ${c.w}px, margen dcho ${c.r}px`).join(' | ') || '—'}`
        + (heroHits.size ? ` · HERO bajo la columna: ${[...heroHits].join(' | ')}` : '')
        + (otherHits.size ? ` · resto bajo la columna (${otherHits.size}): ${[...otherHits].slice(0, 6).join(' | ')}` : '')
        + (overflow > 0 ? ` · DESBORDE ${overflow}px` : ''));
      await pg.close();
    }
    await c.close();
  }
  await b.close(); console.log(rows.join('\n'));
  if (CSS) console.log(`components.css sustituido en el navegador ${sustituidos} veces (banco con HTML público; no acredita despliegue)`);
  console.log(`\n${bad} fallos de ${rows.length} (fallo = control del contenido bajo la columna, desborde horizontal o barra distinta de 3 botones 46×46)`);
  process.exit(bad ? 1 : 0);
})();
