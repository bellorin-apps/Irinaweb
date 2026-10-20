"""Q-080 public GET checks and comparison of supplied state records; no private access."""
from concurrent.futures import ThreadPoolExecutor
from html.parser import HTMLParser
from pathlib import Path
import hashlib
import json
import urllib.error
import urllib.request

ROOT = Path(__file__).resolve().parents[2]
SITE = 'https://drairinagonzalez.com'
OUT = ROOT / 'docs/audit/2026-10-20-q080-codex'

class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs):
        return None

class Page(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.alts = []
        self.meta = {}
        self.title = ''
        self.in_title = False
    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == 'img' and 'alt' in attrs: self.alts.append(attrs['alt'])
        if tag == 'meta': self.meta[attrs.get('property', attrs.get('name'))] = attrs.get('content')
        if tag == 'title': self.in_title = True
    def handle_endtag(self, tag):
        if tag == 'title': self.in_title = False
    def handle_data(self, text):
        if self.in_title: self.title += text

def get(path):
    try:
        response = urllib.request.build_opener(NoRedirect).open(SITE + path, timeout=30)
    except urllib.error.HTTPError as e:
        response = e
    with response as r:
        page = Page()
        page.feed(r.read().decode('utf-8', 'replace'))
        return {'http':r.code, 'location':r.headers.get('Location'), 'title':page.title,
                'alts':page.alts, 'og_alt':page.meta.get('og:image:alt'),
                'twitter_alt':page.meta.get('twitter:image:alt')}

pairs = {'/en/':'/', '/en/dr-irina-gonzalez-saez/':'/dra-irina-gonzalez-saez/',
         '/en/ent/':'/otorrinolaringologia/', '/en/sleep/':'/sueno/',
         '/en/first-visit/':'/primera-consulta/', '/en/contact/':'/contacto/'}
nf = ['/en/no-existe-q080/', '/no-existe-q080/']
paths = list(pairs) + list(pairs.values()) + nf
with ThreadPoolExecutor(max_workers=5) as pool:
    pages = dict(zip(paths, pool.map(get, paths)))
for en, es in pairs.items():
    assert pages[en]['http'] == pages[es]['http'] == 200
    assert not pages[en]['location'] and not pages[es]['location']
    assert pages[en]['alts'].count('') == pages[es]['alts'].count(''), (en, es)
    assert not any('Dra. Irina' in a for a in pages[en]['alts']), en
assert 'Dr. Irina González Sáez — First visit' in pages['/en/first-visit/']['alts']
assert 'Dra. Irina González Sáez — Primera consulta' in pages['/primera-consulta/']['alts']
for en, es in [('/en/', '/'), ('/en/sleep/', '/sueno/')]:
    assert pages[en]['og_alt'] == 'Dr. Irina González Sáez · Your ENT and sleep specialist'
    assert pages[es]['og_alt'] == 'Dra. Irina González Sáez · Tu Otorrino Especialista en Sueño'
assert pages[nf[0]]['http'] == pages[nf[1]]['http'] == 404
assert pages[nf[0]]['title'] == 'Page not found | Dr. Irina González Sáez'
assert pages[nf[1]]['title'] == 'Página no encontrada | Dra. Irina González Sáez'
assert all(not row['twitter_alt'] for row in pages.values())
source = ROOT / 'docs/audit/2026-10-20-q080-prod'
before = (source / 'estado-antes.txt').read_bytes()
after = (source / 'estado-despues.txt').read_bytes()
assert before == after
records = before.decode('utf-8-sig').splitlines()
assert len(records) == 16 and all(line.split()[1] == 'publish' for line in records)
OUT.mkdir(parents=True, exist_ok=True)
(OUT / 'review.json').write_text(json.dumps({'base':'a3e873a', 'public':pages,
    'supplied_state_records_equal':True, 'published_records':16,
    'state_artifact_sha256':hashlib.sha256(before).hexdigest(),
    'local_i18n_md5':hashlib.md5((ROOT / 'wp-content/themes/irina-gonzalez/inc/i18n.php').read_bytes()).hexdigest(),
    'limits':'Public outputs checked independently; supplied private-state snapshots compared only as artifacts. No DB, backup, browser console or server hash access.'},
    ensure_ascii=False, indent=2)+'\n', encoding='utf-8')
print('Q-080: 14 public routes OK; EN/ES titles and alts, decorative blanks and OG checked; 16 supplied state records identical.')
