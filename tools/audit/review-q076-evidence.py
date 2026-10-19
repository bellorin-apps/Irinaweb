"""Read tracked evidence only; no WordPress, server, private files or network."""
from pathlib import Path
import hashlib
import json
import re

ROOT = Path(__file__).resolve().parents[2]
SOURCE = ROOT / 'docs/audit/2026-10-19-local'
OUT = ROOT / 'docs/audit/2026-10-19-q076'
names = ['publish-en-completa-b26efa4.log', 'fallos-b26efa4.txt',
         'preflight-b26efa4.txt', 'C-seo.json']
texts = {name: (SOURCE / name).read_text(encoding='utf-8') for name in names}
published = re.findall(r'^  publicada http://127\.0\.0\.1:8890(/\S+) → publish$', texts[names[0]], re.M)
http = re.findall(r'^\s+(/en/\S*)\s+(200)$', texts[names[0]], re.M)
negative = re.findall(r'^\s+(\S+)\s+exit 1\s+SIN CAMBIOS', texts[names[2]], re.M)
assert len(published) == len(set(published)) == 16
assert len(http) == 15 and len(negative) == 16
assert 'pagina-bajo-en-marcada-es    exit 0  CAMBIÓ' in texts[names[2]]
assert texts[names[1]].count('exit 1  éxito anunciado: no') == 5
assert texts[names[1]].count('Estados restaurados.') == 4
assert 'REVISIÓN MANUAL: no restaurados IDs 99' in texts[names[1]]
seo = json.loads(texts['C-seo.json'])
assert len(seo['en']) == 15
for path, row in seo['en'].items():
    assert row['code'] == 200 and row['lang'] == 'en-US' and row['canonical'] == path
    assert row['og']['locale'] == 'en_US' and row['og']['url'] == 'http://127.0.0.1:8890' + path
    pair = row['hreflang']['es-MX']
    assert row['hreflang'] == {'es-MX':pair, 'en-US':path, 'x-default':pair}
    assert seo['es'][pair]['hreflang'] == row['hreflang']
    assert row['selector'] == [pair] and seo['es'][pair]['selector'] == [path]
assert seo['sitemap']['total'] == 30 and not seo['sitemap']['thank_you']
assert len(seo['sitemap']['en']) == len(seo['sitemap']['es']) == 15
missing = [path for path, row in seo['en'].items() if not row['inLanguage']]
assert '/en/' in missing and len(missing) == 7  # Q-077 remains open.
result = {
    'reviewed_commit':'b26efa4', 'evidence_commit':'43cc1df',
    'publication_log_entries':len(published), 'http_200_routes':len(http),
    'negative_preflights_unchanged':len(negative),
    'positive_control':'Hierarchy determines page language; /en/ page marked es is allowed.',
    'failed_publications':5, 'restored_cases':4, 'reported_restore_failure_id':99,
    'seo_en_routes':15, 'reciprocal_pairs':15, 'sitemap_urls':30,
    'missing_inLanguage':missing,
    'sha256':{name:hashlib.sha256((SOURCE / name).read_bytes()).hexdigest() for name in names},
    'limits':[
        'Offline assertions over supplied local evidence, not a fresh WordPress execution.',
        'Idempotence and final wrapper exit/alternates are attested in QA; no dedicated b26efa4 idempotence log.',
        'Hashes identify reviewed artifacts, not the executed source or environment.',
        'SQLite/local HTTP and synthetic approvals do not certify production, caches, external hooks or medical approval.'
    ]
}
OUT.mkdir(parents=True, exist_ok=True)
(OUT / 'evidence-review.json').write_text(json.dumps(result, ensure_ascii=False, indent=2)+'\n', encoding='utf-8')
print('Evidence: 16 publications, 15 HTTP 200, 16 blocked preflights, 5 failures; Q-077 still open (7 missing inLanguage).')
