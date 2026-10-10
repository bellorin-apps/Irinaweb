// Ajusta el viewBox de cada SVG de assets/brand/logos/ al contenido real (los exportes de Illustrator traen la mesa de trabajo
// completa de 178×100 con mucho aire y los logos salían diminutos). Solo cambia el atributo viewBox; conserva trazados y colores.
// Uso: node tools/brand/logos-fit.cjs   (requiere playwright; imprime el viewBox nuevo y la proporción de cada logo).
const { chromium } = require('playwright'); const fs = require('fs'); const path = require('path');
const D = path.resolve(__dirname, '../../wp-content/themes/irina-gonzalez/assets/brand/logos');
const PAD = 1; // unidades del viewBox
(async () => {
  const files = fs.readdirSync(D).filter(f => f.endsWith('.svg')).sort();
  const b = await chromium.launch(); const p = await b.newPage();
  await p.setContent('<body></body>');
  for (const f of files) {
    const src = fs.readFileSync(path.join(D, f), 'utf8');
    const box = await p.evaluate((src) => { const w = document.createElement('div'); w.innerHTML = src; document.body.appendChild(w);
      const svg = w.querySelector('svg'); const els = [...svg.querySelectorAll('path,rect,circle,ellipse,polygon,polyline,line')];
      let x0 = Infinity, y0 = Infinity, x1 = -Infinity, y1 = -Infinity;
      for (const e of els) { const bb = e.getBBox(); if (!bb.width && !bb.height) continue; x0 = Math.min(x0, bb.x); y0 = Math.min(y0, bb.y); x1 = Math.max(x1, bb.x + bb.width); y1 = Math.max(y1, bb.y + bb.height); }
      w.remove(); return [x0, y0, x1 - x0, y1 - y0]; }, src);
    const vb = [box[0] - PAD, box[1] - PAD, box[2] + 2 * PAD, box[3] + 2 * PAD].map(v => +v.toFixed(2));
    const out = src.replace(/viewBox="[^"]*"/, `viewBox="${vb.join(' ')}"`);
    if (out !== src) fs.writeFileSync(path.join(D, f), out);
    console.log(f.padEnd(34), 'viewBox', vb.join(' '), 'ratio', (vb[2] / vb[3]).toFixed(2));
  }
  await b.close();
})();
