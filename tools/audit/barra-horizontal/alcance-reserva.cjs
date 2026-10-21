// Barra en horizontal: la reserva lateral solo se aplica con la columna; la cabecera y los fondos del hero no cambian. Solo lectura.
// Uso: node tools/audit/barra-horizontal/alcance-reserva.cjs [base]
const { chromium } = require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core');
const B = (process.argv[2] || 'https://drairinagonzalez.com').replace(/\/$/, '');
(async () => {
  const b = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  const rows = []; let bad = 0;
  for (const [w, h] of [[667, 375], [740, 360], [844, 390], [932, 430], [375, 667], [390, 844], [1440, 900]]) {
    const c = await b.newContext({ viewport: { width: w, height: h }, isMobile: w < 900, hasTouch: w < 900 }); const p = await c.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message));
    await p.goto(B + '/primera-consulta/', { waitUntil: 'load' }); await p.waitForTimeout(500);
    const r = await p.evaluate(() => {
      const gutter = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--gutter')) || 0;
      const g = el => el && el.getBoundingClientRect();
      const bar = document.querySelector('.di-mobile-bar'); const column = getComputedStyle(bar).display !== 'none' && getComputedStyle(bar).flexDirection === 'column';
      const head = g(document.querySelector('.di-header__inner')); const bg = g(document.querySelector('.di-bleed__bg')); const cont = g(document.querySelector('.di-bleed .di-container')); const foot = g(document.querySelector('.di-footer .di-container'));
      const gpx = parseFloat(getComputedStyle(document.querySelector('.di-bleed .di-container')).marginLeft);
      return { column, iw: innerWidth, headRight: Math.round(innerWidth - head.right), bgLeft: Math.round(bg.left), bgRight: Math.round(innerWidth - bg.right), contRight: Math.round(innerWidth - cont.right), footRight: Math.round(innerWidth - foot.right), contLeft: Math.round(cont.left), overflow: document.documentElement.scrollWidth - innerWidth };
    });
    const reserved = r.contRight >= r.headRight + 50; // con reserva, el contenido termina ≥ 58 px más a la izquierda que la cabecera (redondeo)
    const ok = (r.column ? reserved && r.footRight >= r.headRight + 50 : !reserved) && r.bgLeft <= 0 && r.bgRight <= 0 && r.overflow <= 0 && !errs.length;
    if (!ok) bad++;
    rows.push(`${ok ? 'OK  ' : 'FAIL'} ${w}×${h} ${r.column ? 'columna' : 'sin columna'} · cabecera a ${r.headRight}px del borde dcho · contenido hero a ${r.contRight}px (izq ${r.contLeft}px) · pie a ${r.footRight}px · fondo hero ${r.bgLeft}/${r.bgRight}px (a todo el ancho) · desborde ${r.overflow} · reserva ${reserved ? 'SÍ' : 'no'}${errs.length ? ' · errores JS: ' + errs.join('; ') : ''}`);
    await c.close();
  }
  await b.close(); console.log(rows.join('\n')); console.log(`\n${bad} fallos de ${rows.length}`); process.exit(bad ? 1 : 0);
})();
