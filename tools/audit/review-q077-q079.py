"""Offline SEO review and disposable ancestor-link regression; no real lab/WordPress."""
from pathlib import Path
import importlib.util
import json
import os
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[2]
OUT = ROOT / 'docs/audit/2026-10-19-q077-q079'
BASH = 'C:/Program Files/Git/bin/bash.exe'
spec = importlib.util.spec_from_file_location('seo_check', ROOT / 'tools/audit/en-local/t/seo_check.py')
seo = importlib.util.module_from_spec(spec)
spec.loader.exec_module(seo)
evidence = json.loads((ROOT / 'docs/audit/2026-10-19-local/C-seo-1f5e18e.json').read_text(encoding='utf-8'))
fail, warnings = seo.evaluate(evidence)
assert not fail, fail
assert len(evidence['en']) == 15 and all(r['inLanguage'] == ['en-US'] for r in evidence['en'].values())
assert {'WebSite', 'WebPage'}.issubset(evidence['en']['/en/']['schema_types'])
assert 'ProfilePage' in evidence['en']['/en/dr-irina-gonzalez-saez/']['schema_types']
assert evidence['en']['/en/dr-irina-gonzalez-saez/']['physician_nodes'] == 1
result = {'base':'62d920e', 'seo_failures':fail, 'seo_warnings':warnings,
          'en_routes':15, 'es_home_inLanguage':evidence['es']['/']['inLanguage'],
          'sitemap':evidence['sitemap']['total']}
with tempfile.TemporaryDirectory(prefix='audit-q079-', dir=ROOT / 'tmp') as folder:
    fixture = Path(folder).resolve()
    assert fixture.is_relative_to((ROOT / 'tmp').resolve())
    repo = fixture / 'repo'
    repo.mkdir()
    subprocess.run(['git', 'init', '-q', str(repo)], check=True)
    subprocess.run(['git', '-C', str(repo), '-c', 'user.name=Audit', '-c', 'user.email=audit@example.test',
                    'commit', '-q', '--allow-empty', '-m', 'fixture'], check=True)
    lab = repo / 'tmp/lab'
    victim = fixture / 'victim'
    for rel in ['themes/irina-gonzalez', 'plugins/dra-irina-core']:
        src = repo / 'wp-content' / rel
        dst = victim / rel
        src.mkdir(parents=True)
        dst.mkdir(parents=True)
        (src / 'new.txt').write_text('synthetic source', encoding='utf-8')
        (dst / 'sentinel.txt').write_text('synthetic victim', encoding='utf-8')
        # Verify every destructive target of sync.sh is inside this disposable fixture.
        assert dst.resolve().is_relative_to(fixture)
    (lab / 'site').mkdir(parents=True)
    link = lab / 'site/wp-content'
    os.symlink(victim, link, target_is_directory=True)
    assert link.is_symlink() and link.resolve() == victim
    env = dict(os.environ, DI_REPO_ROOT=repo.as_posix(), DI_LAB=lab.as_posix())
    run = subprocess.run([BASH, (ROOT / 'tools/audit/en-local/sync.sh').as_posix()],
                         env=env, capture_output=True, text=True)
    intact = all((victim / rel / 'sentinel.txt').exists()
                 for rel in ['themes/irina-gonzalez', 'plugins/dra-irina-core'])
    result['sync_ancestor_link'] = {'exit':run.returncode, 'outside_lab_sentinels_intact':intact,
                                  'stdout':run.stdout.strip(), 'stderr':run.stderr.strip()}
    assert run.returncode == 0 and not intact, 'Expected unfixed c151258 ancestor-link regression'
    # Remove only the synthetic link before TemporaryDirectory cleanup.
    link.unlink()
OUT.mkdir(parents=True, exist_ok=True)
(OUT / 'review.json').write_text(json.dumps(result, ensure_ascii=False, indent=2)+'\n', encoding='utf-8')
print('Q-077: supplied JSON passes offline; Q-079: sync ancestor-link bypass reproduced in disposable fixture.')
