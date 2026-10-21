// Actual hint JS/CSS in synthetic geometry; no WordPress or external requests.
const fs = require('fs');
const path = require('path');
const cp = require('child_process');
const { chromium } = require(process.env.IRINA_PLAYWRIGHT);
const root = path.resolve(__dirname, '../../..');
const checks = [];
async function scenario(browser, baseline) {
  const load = file => baseline ? cp.execFileSync('git', ['show', 'b465e4e:' + file], { cwd: root, encoding: 'utf8' }) : fs.readFileSync(path.join(root, file), 'utf8');
  const js = load('wp-content/themes/irina-gonzalez/assets/js/main.js');
  const css = load('wp-content/themes/irina-gonzalez/assets/css/components.css');
  const block = js.slice(js.indexOf('// Aviso «This page'), js.lastIndexOf('})();'));
  const ctx = await browser.newContext({ viewport: { width: 390, height: 844 } });
  await ctx.addInitScript(() => Object.defineProperty(navigator, 'languages', { get: () => ['en-US'] }));
  await ctx.route('**/*', r => r.fulfill({ contentType: 'text/html', body: `<style>:root{--gutter:16px;--motion-base:200ms;--ease-out:ease}body{height:2200px}.di-bleed a{position:absolute;left:16px;top:690px;width:340px;height:45px}${css.slice(css.indexOf('.di-en-hint {'))}</style><div class="di-bleed"><a href="#">Hero CTA</a></div><div class="di-en-hint" hidden><p>This page is also available in English.</p><a class="di-en-hint__go">View in English</a><button class="di-en-hint__x">Close</button></div><script>(()=>{${block}})();</script>` }));
  const p = await ctx.newPage();
  await p.goto('https://q081.test/');
  await p.waitForTimeout(260);
  const state = () => p.evaluate(() => {
    const h = document.querySelector('.di-en-hint');
    const s = getComputedStyle(h);
    return { waiting: h.classList.contains('is-waiting'), visible: s.display !== 'none' && s.visibility !== 'hidden' && Number(s.opacity) > 0 };
  });
  const initial = await state();
  await p.evaluate(() => { document.body.classList.add('menu-open'); window.dispatchEvent(new Event('resize')); });
  await p.waitForTimeout(80);
  await p.evaluate(() => document.body.classList.remove('menu-open'));
  await p.waitForTimeout(260);
  const afterMenu = await state();
  // A new overlap must hide immediately, without a visibility delay.
  await p.evaluate(() => document.querySelector('.di-bleed a').style.top = '1200px');
  await p.evaluate(() => window.dispatchEvent(new Event('resize')));
  await p.waitForTimeout(260);
  await p.evaluate(() => document.querySelector('.di-bleed a').style.top = '690px');
  await p.evaluate(() => window.dispatchEvent(new Event('resize')));
  await p.waitForTimeout(50);
  const immediate = await state();
  await p.evaluate(() => window.scrollTo(0, 160));
  await p.waitForTimeout(260);
  const scrolled = await state();
  await ctx.close();
  return { initial, afterMenu, immediate, scrolled };
}
(async () => {
  const browser = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  try {
    const baseline = await scenario(browser, true);
    const current = await scenario(browser, false);
    const check = (name, value) => { if (!value) throw new Error(name + '\n' + JSON.stringify({ baseline, current })); checks.push(name); };
    check('baseline menu bug reproduced', baseline.afterMenu.visible && !baseline.afterMenu.waiting);
    check('baseline delayed hiding reproduced', baseline.immediate.visible);
    check('initial overlap waits', current.initial.waiting && !current.initial.visible);
    check('menu close recomputes overlap', current.afterMenu.waiting && !current.afterMenu.visible);
    check('new overlap immediately hidden', current.immediate.waiting && !current.immediate.visible);
    check('scroll reveals clear hint', !current.scrolled.waiting && current.scrolled.visible);
    fs.mkdirSync(path.dirname(process.argv[2]), { recursive: true });
    fs.writeFileSync(process.argv[2], JSON.stringify({ pass: checks.length, checks, baseline, current, limits: 'Synthetic geometry with actual JS/CSS, not WordPress/LiteSpeed integration.' }, null, 2));
    console.log(`${checks.length}/${checks.length} P3 transition checks pass.`);
  } finally { await browser.close(); }
})().catch(e => { console.error(e); process.exitCode = 1; });
