"""Read-only public HTTP checks for D-101; no accounts, WP-CLI or form submissions."""
from concurrent.futures import ThreadPoolExecutor
from pathlib import Path
import json
import re
import urllib.error
import urllib.request
import xml.etree.ElementTree as ET

ROOT = Path(__file__).resolve().parents[2]
SITE = 'https://drairinagonzalez.com'
OUT = ROOT / 'docs/audit/2026-10-19-d101-codex'

class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None

def get(path):
    opener = urllib.request.build_opener(NoRedirect)
    try:
        with opener.open(SITE + path, timeout=30) as r:
            return r.status, r.read().decode('utf-8', 'replace'), r.headers.get('Location')
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode('utf-8', 'replace'), e.headers.get('Location')

maps = ['/page-sitemap.xml', '/condicion-sitemap.xml', '/tratamiento-sitemap.xml']
routes = []
for path in maps:
    code, xml, _ = get(path)
    assert code == 200, (path, code)
    routes += [n.text.removeprefix(SITE) for n in ET.fromstring(xml).iter()
               if n.tag.endswith('}loc') or n.tag == 'loc']
assert len(routes) == len(set(routes)) == 15 and not any(p.startswith('/en/') for p in routes)

def es_check(path):
    code, html, location = get(path)
    nodes = []
    for block in re.findall(r'<script[^>]*type=[\"\']application/ld\+json[\"\'][^>]*>(.*?)</script>', html, re.S):
        value = json.loads(block)
        nodes += value.get('@graph', [value]) if isinstance(value, dict) else value
    types = [t for node in nodes for t in
             (node.get('@type', []) if isinstance(node.get('@type'), list) else [node.get('@type')])]
    lang = sorted({n['inLanguage'] for n in nodes if isinstance(n.get('inLanguage'), str)})
    row = {'path':path, 'http':code, 'location':location, 'inLanguage':lang,
           'physician_nodes':types.count('Physician'),
           'page_types':[t for t in types if t in ['WebPage', 'MedicalWebPage', 'ProfilePage', 'ContactPage', 'FAQPage']],
           'en_links':len(re.findall(r'href=[\"\'](?:https://drairinagonzalez\.com)?/en(?:/|[\"\'])', html)),
           'hreflang':len(re.findall(r'<link\b[^>]*\bhreflang=', html)),
           'selector':bool(re.search(r'class=[\"\'][^\"\']*\bdi-lang(?:\s|[\"\'])', html)),
           'doctoralia_active':'data-di-doctoralia' in html,
           'php_errors':bool(re.search(r'(?:Fatal error|Warning):', html))}
    assert code == 200 and not location, row
    assert lang == (['es'] if path == '/' else ['es-MX']), row
    assert row['physician_nodes'] <= 1 and len(row['page_types']) == len(set(row['page_types'])), row
    assert not any(row[k] for k in ['en_links', 'hreflang', 'selector', 'doctoralia_active', 'php_errors']), row
    return row

en = ['/en/', '/en/contact/', '/en/sleep/', '/en/ent/', '/en/faq/',
      '/en/first-visit/', '/en/dr-irina-gonzalez-saez/', '/en/privacy-notice/',
      '/en/medical-disclaimer/', '/en/terms-of-use/']
with ThreadPoolExecutor(max_workers=5) as pool:
    pages = list(pool.map(es_check, routes))
    responses = list(pool.map(get, en))
english = [{'path':p, 'http':r[0], 'location':r[2]} for p, r in zip(en, responses)]
assert all(r['http'] == 404 and not r['location'] for r in english), english
OUT.mkdir(parents=True, exist_ok=True)
(OUT / 'public.json').write_text(json.dumps({'site':SITE, 'sitemap':routes, 'es':pages, 'en':english,
    'limits':'Public HTTP only; no certification of private drafts, backups, deployed SHA, browser console, images or server logs.'},
    ensure_ascii=False, indent=2)+'\n', encoding='utf-8')
print('D-101 public: 15 ES HTTP 200/schema OK; sitemap 15/0 EN; 10 EN HTTP 404 without redirect; no EN navigation or Doctoralia.')
