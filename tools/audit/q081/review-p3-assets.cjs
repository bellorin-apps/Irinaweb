// Public HTML with candidate JS/CSS fulfilled only inside disposable browser contexts. No deployment.
const fs = require('fs');
const path = require('path');
const assert = require('assert');
const root = path.resolve(__dirname, '../../..');
const out = path.join(root, 'docs/audit/2026-10-20-q081-p3-codex');
fs.mkdirSync(out, { recursive: true });
const routes = { js: 0, css: 0, documentsWithCandidateCss: 0 };
async function wire(ctx) {
  // LiteSpeed combines CSS in production. Append candidate rules after the published head styles.
  await ctx.route('**/*', async r => {
    if (r.request().resourceType() !== 'document') return r.continue();
    const response = await r.fetch();
    const html = await response.text();
    assert(html.includes('</head>'));
    const css = fs.readFileSync(path.join(root, 'wp-content/themes/irina-gonzalez/assets/css/components.css'), 'utf8');
    routes.documentsWithCandidateCss++;
    return r.fulfill({ response, body: html.replace('</head>', `<style data-codex-candidate="p3">${css}</style></head>`) });
  });
  for (const [kind, pattern, file, contentType] of [
    ['js', '**/themes/irina-gonzalez/assets/js/main.js*', 'assets/js/main.js', 'application/javascript'],
    ['css', '**/themes/irina-gonzalez/assets/css/components.css*', 'assets/css/components.css', 'text/css']
  ]) {
    await ctx.route(pattern, r => {
      routes[kind]++;
      return r.fulfill({ contentType, body: fs.readFileSync(path.join(root, 'wp-content/themes/irina-gonzalez', file), 'utf8') });
    });
  }
  return ctx;
}
async function run(name, source, args) {
  source = source.replace("require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core')", 'require(process.env.IRINA_PLAYWRIGHT)');
  source = source.replace('(async () => {', 'module.exports = (async () => {');
  const logs = [];
  const proxy = Object.create(process);
  proxy.argv = [process.argv[0], name, ...args];
  proxy.exit = code => { if (code) throw new Error(name + ': exit ' + code); };
  const mod = { exports: null };
  new Function('require', 'process', 'module', 'console', 'wire', source)(require, proxy, mod, {
    log: (...items) => logs.push(items.join(' '))
  }, wire);
  await mod.exports;
  fs.writeFileSync(path.join(out, name + '.log'), logs.join('\n') + '\n');
  assert(!logs.some(line => line.includes('FAIL')));
  if (name === 'matrix63') {
    assert(logs.join('\n').includes('0 fallos de 63'));
    assert(!logs.join('\n').includes('sin aviso'));
  }
  console.log(name + ': pass');
}
(async () => {
  let matrix = fs.readFileSync(path.join(__dirname, 'solape-cta.cjs'), 'utf8');
  const matrixContext = 'const c = await b.newContext({ viewport: { width: w, height: h }, isMobile: w < 900, hasTouch: w < 900 });';
  assert(matrix.includes(matrixContext));
  matrix = matrix.replace(matrixContext, matrixContext + '\n    await wire(c);');
  await run('matrix63', matrix, ['https://drairinagonzalez.com', '']);
  let regression = fs.readFileSync(path.join(__dirname, 'hint-test-local.cjs'), 'utf8');
  regression = regression.replace("const B = 'http://127.0.0.1:8890';", "const B = 'https://drairinagonzalez.com';");
  const factory = 'const ctx = async (opts = {}) => b.newContext({ viewport: { width: 1440, height: 900 }, ...opts });';
  assert(regression.includes(factory));
  regression = regression.replace(factory, 'const ctx = async (opts = {}) => wire(await b.newContext({ viewport: { width: 1440, height: 900 }, ...opts }));');
  // The reviewed visibility helper also excludes waiting via visibility/opacity.
  assert(regression.includes("getComputedStyle(h).visibility !== 'hidden'"));
  const input = path.join(out, 'no-pair.txt');
  fs.writeFileSync(input, '/links/\n');
  await run('regression37', regression, [input, out]);
  assert(routes.js > 0 && routes.documentsWithCandidateCss > 0, routes);
  fs.writeFileSync(path.join(out, 'assets.json'), JSON.stringify({ routes, limits: 'Public HTML with browser-only candidate assets, no WordPress local installation or server cache deployment certification.' }, null, 2));
})().catch(e => { console.error(e); process.exitCode = 1; });
