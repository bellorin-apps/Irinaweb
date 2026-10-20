// Reuse reviewed navigation-only suites against production; no form submission or server mutation.
const fs = require('fs');
const path = require('path');
const assert = require('assert');
const root = path.resolve(__dirname, '../../..');
const out = path.join(root, 'docs/audit/2026-10-20-q081-codex-prod');
fs.mkdirSync(out, { recursive: true });
const input = path.join(out, 'no-pair.txt');
fs.writeFileSync(input, '/links/\n');
const originalRequire = "require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core')";
async function run(file, productionBase) {
  let source = fs.readFileSync(path.join(__dirname, file), 'utf8');
  assert(source.includes(originalRequire));
  source = source.replace(originalRequire, 'require(process.env.IRINA_PLAYWRIGHT)');
  if (productionBase) {
    assert(source.includes("const B = 'http://127.0.0.1:8890';"));
    source = source.replace("const B = 'http://127.0.0.1:8890';", "const B = 'https://drairinagonzalez.com';");
  }
  assert(source.includes('(async () => {'));
  source = source.replace('(async () => {', 'module.exports = (async () => {');
  const logs = [];
  const testProcess = Object.create(process);
  testProcess.argv = [process.argv[0], file, input, out];
  const mod = { exports: null };
  new Function('require', 'process', 'module', 'console', source)(require, testProcess, mod, {
    log: (...args) => logs.push(args.join(' '))
  });
  await mod.exports;
  assert(!logs.some(s => s.includes('FAIL')));
  return logs.join('\n');
}
(async () => {
  const first = await run('hint-test-local.cjs', true);
  assert(first.includes('0 fallos de 37'));
  const second = await run('eleccion-es-prod.cjs', false);
  assert(second.includes('OK') && second.includes('"guardado":"chosen"') && second.includes('"visible":false'));
  fs.writeFileSync(path.join(out, 'browser.log'), first + '\n' + second + '\n');
  console.log('Production browser: 37/37 and EN→ES choice pass. Fresh isolated contexts; no forms sent.');
})().catch(e => { console.error(e); process.exitCode = 1; });
