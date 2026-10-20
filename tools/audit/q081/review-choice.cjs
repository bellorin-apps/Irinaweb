// Real Q-081 JS block in a synthetic browser page. No WordPress, network or live site changes.
const fs = require('fs');
const path = require('path');
const cp = require('child_process');
const { chromium } = require(process.env.IRINA_PLAYWRIGHT);
const root = path.resolve(__dirname, '../../..');
const file = 'wp-content/themes/irina-gonzalez/assets/js/main.js';
const current = fs.readFileSync(path.join(root, file), 'utf8');
const old = cp.execFileSync('git', ['show', '71ae61c:' + file], { cwd: root, encoding: 'utf8' });
const block = source => source.slice(source.indexOf('// Aviso «This page'), source.lastIndexOf('})();'));
const checks = [];
const ok = (name, value) => { if (!value) throw new Error(name); checks.push(name); };
(async () => {
  const browser = await chromium.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
  try {
    async function context(source, languages = ['en-US'], blocked = false) {
      const ctx = await browser.newContext();
      await ctx.addInitScript(({ languages, blocked }) => {
        Object.defineProperty(navigator, 'languages', { get: () => languages });
        if (blocked) Object.defineProperty(window, 'localStorage', { get() { throw new Error('blocked'); } });
      }, { languages, blocked });
      await ctx.route('**/*', route => {
        const en = new URL(route.request().url()).pathname === '/en/';
        const hint = en ? '' : '<div class="di-en-hint" hidden><a class="di-en-hint__go" href="/en/">View in English</a><button class="di-en-hint__x">Close</button></div>';
        return route.fulfill({ contentType: 'text/html', body: `<html><body><a class="di-lang" href="${en ? '/es/' : '/en/'}">Language</a>${hint}<script>(()=>{${block(source)}})();</script></body></html>` });
      });
      return ctx;
    }
    for (const [name, source, expectedVisible] of [['baseline', old, true], ['fixed', current, false]]) {
      const ctx = await context(source);
      const page = await ctx.newPage();
      await page.goto('https://q081.test/en/');
      await page.click('.di-lang');
      await page.waitForURL('**/es/');
      ok(name + ': EN selector -> ES remembers choice', await page.locator('.di-en-hint').isVisible() === expectedVisible);
      if (name === 'fixed') ok('choice persisted on EN without banner', await page.evaluate(() => localStorage.getItem('di-en-hint')) === 'chosen');
      await ctx.close();
    }
    for (const [name, languages, expected] of [['English', ['en-US'], true], ['Spanish primary', ['es-MX', 'en-US'], false]]) {
      const ctx = await context(current, languages);
      const page = await ctx.newPage();
      await page.goto('https://q081.test/es/');
      ok(name + ': visibility', await page.locator('.di-en-hint').isVisible() === expected);
      if (expected) {
        await page.click('.di-en-hint__x');
        ok('close hides', !await page.locator('.di-en-hint').isVisible());
        await page.reload();
        ok('close survives reload', !await page.locator('.di-en-hint').isVisible());
      }
      await ctx.close();
    }
    const ctx = await context(current, ['en-US'], true);
    const page = await ctx.newPage();
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.goto('https://q081.test/es/');
    ok('blocked storage shows', await page.locator('.di-en-hint').isVisible());
    await page.click('.di-en-hint__x');
    ok('blocked storage closes', !await page.locator('.di-en-hint').isVisible());
    ok('blocked storage no JS errors', errors.length === 0);
    await ctx.close();
  } finally { await browser.close(); }
  fs.mkdirSync(path.dirname(process.argv[2]), { recursive: true });
  fs.writeFileSync(process.argv[2], JSON.stringify({ pass: checks.length, checks, limits: 'Synthetic HTML with actual JS block; old regression reproduced. No WordPress or LiteSpeed integration repeated.' }, null, 2));
  console.log(`${checks.length}/${checks.length} Q-081 checks pass (including old regression).`);
})().catch(e => { console.error(e); process.exitCode = 1; });
