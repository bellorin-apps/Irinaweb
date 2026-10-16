"""Read-only public evidence and local reproducibility for phase 5. No credentials/forms."""
import hashlib
import json
import pathlib
import re
import subprocess
import sys
import tempfile
import urllib.request

ROOT = pathlib.Path(__file__).resolve().parents[2]
sys.path.insert(0, str(ROOT / 'tools/content'))
sys.dont_write_bytecode = True
import practice  # noqa: E402


def get(path):
    with urllib.request.urlopen('https://drairinagonzalez.com' + path, timeout=30) as response:
        return response.status, response.read()


def sha(data):
    return hashlib.sha256(data).hexdigest()


result = {'base': subprocess.check_output(['git', 'rev-parse', 'HEAD'], cwd=ROOT, text=True).strip()}
sprite = ROOT / 'wp-content/themes/irina-gonzalez/assets/icons/lucide.svg'
symbols = set(re.findall(r'<symbol[^>]+id="([^"]+)"', sprite.read_text(encoding='utf-8')))
rows = []
used = set()
for path in ['/', '/dra-irina-gonzalez-saez/', '/primera-consulta/', '/contacto/',
             '/otorrinolaringologia/', '/sueno/', '/preguntas-frecuentes/', '/sueno/cpap/']:
    status, raw = get(path)
    text = raw.decode('utf-8')
    icons = set(re.findall(r'<use[^>]+(?:href|xlink:href)=["\'][^"\']*#([^"\']+)', text))
    used.update(icons)
    row = {'path': path, 'status': status, 'header': 'id="di-header"' in text,
           'footer': 'class="di-footer' in text, 'main': bool(re.search(r'<main\b', text)),
           'adobe': 'use.typekit.net/nlo5pss.css' in text, 'icons': sorted(icons),
           'missingIcons': sorted(icons - symbols)}
    rows.append(row)
result['public'] = rows
result['sprite'] = {'symbols': len(symbols), 'used': sorted(used), 'missing': sorted(used - symbols)}
status, raw = get('/wp-json/dra-irina/v1/practice')
public = json.loads(raw)
expected = {'centro': practice.CENTRO, 'calle': practice.CALLE, 'interior': practice.INTERIOR,
            'colonia': practice.COLONIA, 'cp': practice.CP, 'ciudad': practice.CIUDAD,
            'estado': practice.ESTADO, 'telefono': practice.TELEFONO, 'whatsapp': practice.WHATSAPP}
result['practice'] = {'status': status, 'matches': {k: public.get(k) == v for k, v in expected.items()},
                      'privateFieldsAbsent': all(k not in public for k in ['maps_api_key', 'contacto_copia', 'doctoralia_widget_id']),
                      'hiddenHoursAbsent': bool(public.get('horario_oculto')) and 'horario' not in public}
# Capture only hashes/lengths of public static assets, never raw page content or query keys.
assets = []
for relative in ['css/tokens.css', 'css/base.css', 'css/components.css', 'css/sleep.css', 'js/main.js', 'js/map.js']:
    path = '/wp-content/themes/irina-gonzalez/assets/' + relative
    status, raw = get(path)
    local = ROOT / path.lstrip('/')
    assets.append({'path': path, 'status': status, 'bytes': len(raw),
                   'publicSha256': sha(raw), 'localSha256': sha(local.read_bytes()),
                   'sameAfterEolNormalization': raw.replace(b'\r\n', b'\n') == local.read_bytes().replace(b'\r\n', b'\n')})
result['assets'] = assets
# Regenerate only known public build outputs; record any differences rather than conceal them.
outputs = [ROOT / p for p in subprocess.check_output(['git', 'ls-files', 'tools/content', 'tools/briefing/guia/paginas.json'], cwd=ROOT, text=True).splitlines() if p.endswith('.json')]
before = {str(p.relative_to(ROOT)): sha(p.read_bytes()) for p in outputs}
for script in ['build-home.py', 'build-pages.py']:
    subprocess.run([sys.executable, '-B', str(ROOT / 'tools/content' / script)], cwd=ROOT, check=True, stdout=subprocess.DEVNULL)
result['builds'] = {'outputsChecked': len(outputs), 'changed': [p for p, digest in before.items() if sha((ROOT / p).read_bytes()) != digest]}
# Negative case uses a disposable synthetic tree, never modifies a real build.
with tempfile.TemporaryDirectory(prefix='irina-nap-') as lab:
    base = pathlib.Path(lab)
    (base / 'tools/audit').mkdir(parents=True)
    (base / 'tools/content').mkdir()
    (base / 'tools/content/practice.py').write_bytes((ROOT / 'tools/content/practice.py').read_bytes())
    target = base / 'tools/audit/nap-check.py'
    target.write_bytes((ROOT / 'tools/audit/nap-check.py').read_bytes())
    (base / 'tools/content/build-negative.py').write_text(repr(practice.CALLE), encoding='utf-8')
    run = subprocess.run([sys.executable, '-B', str(target), '--offline'], capture_output=True)
    result['napNegativeDetected'] = run.returncode == 1 and b'build-negative.py' in run.stdout
result['limits'] = 'Anonymous GET only; no browser execution, form, authenticated editor or server account. Public assets do not prove server Git SHA. Builds regenerated locally, no upload. NAP has a central runtime option and a build mirror checked for drift, not automatic bidirectional sync.'
out = ROOT / 'docs/audit/2026-10-16-phase5'
out.mkdir(parents=True, exist_ok=True)
(out / 'public-builds.json').write_text(json.dumps(result, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
assert all(r['status'] == 200 and r['header'] and r['footer'] and r['main'] and r['adobe'] and not r['missingIcons'] for r in rows), rows
assert all(result['practice']['matches'].values()) and result['practice']['privateFieldsAbsent'] and result['practice']['hiddenHoursAbsent']
assert not result['builds']['changed'] and result['napNegativeDetected']
assert all(a['status'] == 200 and a['sameAfterEolNormalization'] for a in assets)
print('Phase5: 8 pages, 9 NAP fields, 6 assets, unchanged builds and negative drift case pass.')
