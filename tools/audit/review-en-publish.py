"""Execute real PHP publisher against synthetic WP state, plus shell stubs. No env/SSH/database."""
from pathlib import Path
import json
import os
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[2]
OUT = ROOT / os.environ.get('IRINA_AUDIT_OUTPUT', 'docs/audit/2026-10-19')
assert OUT.resolve().is_relative_to((ROOT / 'docs/audit').resolve())
OUT.mkdir(parents=True, exist_ok=True)
PHP = ROOT / 'tmp/php/php.exe'
rows = []
with tempfile.TemporaryDirectory(prefix='audit-en-', dir=ROOT / 'tmp') as folder:
    lab = Path(folder)
    assert lab.resolve().is_relative_to((ROOT / 'tmp').resolve())
    original = subprocess.check_output(['git', 'show', '6d96ae1:tools/deploy/en/publish-en.php'], cwd=ROOT)
    for baseline in [False, True]:
        for case in (['write-error', 'empty'] if baseline else ['setup-error', 'dry-run', 'no-approvals', 'no-approvals-dry', 'no-legal', 'missing-page', 'missing-pair', 'wrong-language', 'unknown', 'empty', 'truncated', 'wrong-kind', 'duplicate', 'valid', 'idempotent', 'write-error', 'zero-return', 'silent-block', 'throw', 'rollback-fail', 'root-error']):
            pages = json.loads((ROOT / 'tools/content/en/pages.json').read_text(encoding='utf-8'))
            fichas = json.loads((ROOT / 'tools/content/en/fichas-en.json').read_text(encoding='utf-8'))
            if case == 'empty': pages, fichas = {}, []
            if case == 'truncated': pages.pop('faq')
            if case == 'wrong-kind': pages['en']['kind'] = 'html'
            if case == 'duplicate': fichas[-1] = fichas[0]
            (lab / 'pages.json').write_text(json.dumps(pages), encoding='utf-8')
            (lab / 'fichas-en.json').write_text(json.dumps(fichas), encoding='utf-8')
            target = lab / 'publish-en.php'
            target.write_bytes(original if baseline else (ROOT / 'tools/deploy/en/publish-en.php').read_bytes())
            if case == 'setup-error':
                target.write_bytes((ROOT / 'tools/deploy/en/setup-en.php').read_bytes())
                (lab / 'seo.json').write_text('{}', encoding='utf-8')
            env = dict(os.environ, AUDIT_CASE=case, AUDIT_PUBLISH_PATH=str(target))
            run = subprocess.run([str(PHP), str(ROOT / 'tools/audit/review-en-publish.php')], env=env, capture_output=True, check=True)
            data = json.loads(run.stdout)
            if baseline:
                assert data['error'] is None and data['success'], data
                if case == 'write-error': assert not data['unchanged'] and 'draft' in data['statuses'].values(), data
            elif case == 'valid':
                assert not data['error'] and len(data['writes']) == 16 and set(data['statuses'].values()) == {'publish'} and data['writes'][0]['ID'] == 100, data
            elif case in ['dry-run', 'no-approvals-dry', 'idempotent']:
                assert data['unchanged'] and not data['writes'] and not data['error'], data
            elif case == 'rollback-fail':
                assert data['error'] and 'REVISIÓN MANUAL' in data['error'] and not data['success'], data
            elif case == 'root-error':
                assert data['unchanged'] and len(data['writes']) == 1 and not data['success'], data
                assert 'Estados restaurados.' in data['error'] and 'REVISIÓN MANUAL' not in data['error'], data
            else:
                assert data['error'] and data['unchanged'] and not data['success'], data
            rows.append({'baseline':baseline, 'case':case, 'pass':True, 'writes':len(data['writes']), 'unchanged':data['unchanged'], 'error':data['error']})
    # Q-076: before b26efa4 an unchanged root was written again during rollback,
    # producing a misleading manual-review error. This stub rejects both writes.
    for name, source_name in [('pages.json', 'pages.json'), ('fichas-en.json', 'fichas-en.json')]:
        (lab / name).write_bytes((ROOT / 'tools/content/en' / source_name).read_bytes())
    target.write_bytes(subprocess.check_output(['git', 'show', 'b26efa4^:tools/deploy/en/publish-en.php'], cwd=ROOT))
    env = dict(os.environ, AUDIT_CASE='root-error', AUDIT_PUBLISH_PATH=str(target))
    run = subprocess.run([str(PHP), str(ROOT / 'tools/audit/review-en-publish.php')], env=env, capture_output=True, check=True)
    data = json.loads(run.stdout)
    assert data['unchanged'] and len(data['writes']) == 2 and 'REVISIÓN MANUAL' in data['error'], data
    rows.append({'baseline':'before-b26efa4', 'case':'root-error', 'pass':True, 'writes':2, 'unchanged':True, 'error':data['error']})
    # Shell orchestration: replace exactly its lib source with synthetic functions; never execute rssh contents.
    shell = (ROOT / 'tools/deploy/publish-en.sh').read_text(encoding='utf-8')
    wp_command = next(line for line in shell.splitlines() if line.startswith('WP='))
    assert '--skip-plugins' not in wp_command and '--context=admin' not in wp_command, wp_command
    source = '. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"'
    assert shell.count(source) == 1
    stub = r'''set -euo pipefail
WEBROOT=/synthetic REPO=/synthetic SSH_USER=audit SSH_HOST=example.test SITE_URL=https://example.test
stamp(){ echo 20261019-100000; }
rscp(){ echo scp >> "$AUDIT_LOG"; }
rssh(){ echo ssh >> "$AUDIT_LOG"; if [[ "$*" == *eval-file* && "$AUDIT_SHELL_CASE" == php-fail ]]; then return 1; fi; }
curl(){ echo curl >> "$AUDIT_LOG"; if [[ "$*" == *http_code* ]]; then
  if [[ "$AUDIT_SHELL_CASE" == http-fail ]]; then echo 404; else echo 200; fi
else
  if [[ "$AUDIT_SHELL_CASE" == missing-alternates ]]; then echo missing; else echo 'hreflang="es-MX" hreflang="en-US" hreflang="x-default" class="di-lang'; fi
fi; }
'''
    target = lab / 'wrapper.sh'
    target.write_text(shell.replace(source, stub), encoding='utf-8', newline='\n')
    for case, args, ok in [('dry-run', [], True), ('unknown', ['--invalid'], False), ('php-fail', ['--yes','--legal-ok'], False), ('http-fail', ['--yes','--legal-ok'], False), ('missing-alternates', ['--yes','--legal-ok'], False), ('valid', ['--yes','--legal-ok'], True)]:
        log = lab / 'calls.txt'
        log.write_text('', encoding='utf-8')
        env = dict(os.environ, AUDIT_SHELL_CASE=case, AUDIT_LOG=str(log).replace('\\','/'))
        run = subprocess.run(['C:/Program Files/Git/bin/bash.exe', str(target).replace('\\','/'), *args], env=env, capture_output=True)
        calls = log.read_text(encoding='utf-8').splitlines()
        assert (run.returncode == 0) == ok, (case, run.stderr)
        if case == 'unknown': assert not calls
        if case in ['php-fail','dry-run']: assert 'curl' not in calls
        rows.append({'shell':True,'case':case,'pass':True,'exit':run.returncode,'calls':calls})
(OUT / 'publisher.json').write_text(json.dumps({'cases':rows,'limits':'Disposable copies and in-memory WP stubs only; no real publication, DB, env, SSH, credentials, hooks, cache, mail or WordPress rollback integration. Old false-success and empty-manifest bypass reproduced.'}, ensure_ascii=False, indent=2)+'\n', encoding='utf-8')
print(f'Publisher: {len(rows)} cases pass, including 2 original regressions and Q-076 reproduced.')
