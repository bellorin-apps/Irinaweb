// Reviewed production transition suite, public navigation only, in disposable browser context.
const fs = require('fs');
const path = require('path');
const assert = require('assert');
const root = path.resolve(__dirname, '../../..');
const out = path.join(root, 'docs/audit/2026-10-20-q081-p3-codex-prod');
fs.mkdirSync(out, { recursive: true });
let source = fs.readFileSync(path.join(__dirname, 'transiciones-prod.cjs'), 'utf8');
const oldRequire = "require('C:/Users/bellg/Documents/Irinaweb/tmp/wp-en-local/node/node_modules/playwright-core')";
assert(source.includes(oldRequire));
source = source.replace(oldRequire, 'require(process.env.IRINA_PLAYWRIGHT)');
source = source.replace('(async () => {', 'module.exports = (async () => {');
const logs = [];
const proxy = Object.create(process);
proxy.argv = [process.argv[0], 'transiciones-prod.cjs', 'https://drairinagonzalez.com', out];
proxy.exit = code => { if (code) throw new Error('Transition suite exit ' + code); };
const mod = { exports: null };
new Function('require', 'process', 'module', 'console', source)(require, proxy, mod, {
  log: (...items) => logs.push(items.join(' '))
});
Promise.resolve(mod.exports).then(() => {
  fs.writeFileSync(path.join(out, 'transitions.log'), logs.join('\n') + '\n');
  assert(!logs.some(line => line.includes('FAIL')));
  assert(logs.join('\n').includes('0 fallos de 9'));
  console.log('P3 live transitions: 9/9, including invisible/non-clickable/non-focusable waiting and menu resize.');
}).catch(e => { console.error(e); process.exitCode = 1; });
