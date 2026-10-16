"""Execute sanitized copies of the real scripts with local stubs; no env.sh, SSH, credentials or DB."""
from pathlib import Path
import gzip
import json
import os
import shlex
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[2]
OUT = ROOT / 'docs/audit/2026-10-16b'
OUT.mkdir(parents=True, exist_ok=True)
LAB = Path(tempfile.mkdtemp(prefix='audit16b-', dir=ROOT / 'tmp')).resolve()
BASH = 'C:/Program Files/Git/bin/bash.exe'

def posix(path):
    value = str(path).replace('\\', '/')
    return '/' + value[0].lower() + value[2:] if len(value) > 1 and value[1] == ':' else value

def put(path, text):
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(text, encoding='utf-8', newline='\n')

STUB = r'''#!/usr/bin/env bash
set -eu
[[ "$*" != *audit-only-password-9* ]] || exit 97
case "$(basename "$0")" in
audit-wp)
  case "$*" in
  *'config get DB_NAME'*) [ "$AUDIT_SCENARIO" != lookup_empty ] && echo production_db; exit 0;;
  *'db export'*)
    [ "$AUDIT_SCENARIO" != export_fail ] || exit 21
    dest="$3"; printf 'CREATE TABLE wp_one (id int);\n' > "$dest"
    [ "$AUDIT_SCENARIO" = export_truncated ] || echo '-- Dump completed on synthetic fixture' >> "$dest"
    echo export >> "$AUDIT_ROOT/events"; exit 0;;
  *'SELECT table_name'*)
    [ "$AUDIT_SCENARIO" != list_fail ] || exit 22
    [ "$AUDIT_SCENARIO" = list_empty ] || printf 'wp_one\nwp_two\n'; exit 0;;
  *'SELECT COUNT'*) [ "$AUDIT_SCENARIO" != production_count_fail ] || exit 23; echo 2; exit 0;;
  esac;;
mysql)
  case "$*" in
  *information_schema.tables*)
    [ "$AUDIT_SCENARIO" != connection_fail ] || exit 24
    if [ "$AUDIT_SCENARIO" = nonempty ]; then echo 1; else echo 0; fi; exit 0;;
  *'SELECT COUNT'*)
    [ "$AUDIT_SCENARIO" != test_count_fail ] || exit 25
    if [ "$AUDIT_SCENARIO" = mismatch ]; then echo 1; else echo 2; fi; exit 0;;
  esac
  cat > /dev/null; echo import >> "$AUDIT_ROOT/events"
  [ "$AUDIT_SCENARIO" != import_fail ]; exit $?;;
gzip)
  if [ "$AUDIT_SCENARIO" = gzip_test_fail ] && [ "${1:-}" = -t ]; then exit 26; fi
  exec /usr/bin/gzip "$@";;
zcat)
  if [ "$AUDIT_SCENARIO" = decompress_fail ]; then printf 'partial'; exit 27; fi
  exec /usr/bin/gzip -dc "$@";;
chmod) echo chmod >> "$AUDIT_ROOT/events"; exec /usr/bin/chmod "$@";;
tar) echo tar >> "$AUDIT_ROOT/events"; if [[ "$*" = *xzf* ]]; then cat > /dev/null; else printf fixture; fi; exit 0;;
esac
exit 98
'''

def run(script, scenario, timestamp='20261016-134414', target='test_db', baseline=False):
    case = LAB / (f'{script}-{scenario}' + ('-baseline' if baseline else ''))
    case.mkdir()
    for name in ['audit-wp', 'mysql', 'gzip', 'zcat', 'tar', 'chmod']:
        put(case / 'bin' / name, STUB)
    for part in ['wp-content/plugins/dra-irina-core', 'wp-content/themes/irina-gonzalez']:
        (case / 'repo' / part).mkdir(parents=True)
        (case / 'webroot' / part).mkdir(parents=True)
    put(case / 'config' / 'drill-db.local.txt', f'DB_NAME={target}\nDB_USER=test_user\nDB_PASS=audit-only-password-9\n')
    dump = case / 'deploy-backups/20261016-134414/db.sql.gz'
    dump.parent.mkdir(parents=True)
    dump.write_bytes(gzip.compress(b'CREATE TABLE wp_one (id int);\n'))
    if scenario == 'corrupt_dump':
        dump.write_bytes(b'not gzip')
    source = (subprocess.check_output(['git', 'show', f'e58c91c:tools/deploy/{script}.sh'], cwd=ROOT).decode('utf-8')
              if baseline else (ROOT / 'tools/deploy' / f'{script}.sh').read_text(encoding='utf-8'))
    include = '. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"'
    assert source.count(include) == 1
    source = source.replace(include, '# lib.sh deliberately excluded by audit fixture')
    source = source.replace('/opt/alt/php84/usr/bin/php /usr/local/bin/wp', 'audit-wp')
    setup = f'''set -euo pipefail
export AUDIT_ROOT={shlex.quote(posix(case))}
export AUDIT_SCENARIO={shlex.quote(scenario)}
export PATH={shlex.quote(posix(case / 'bin'))}:"$PATH"
HERE={shlex.quote(posix(case / 'config'))}
WEBROOT={shlex.quote(posix(case / 'webroot'))}
REPO={shlex.quote(posix(case / 'repo'))}
stamp() {{ echo 20261016-150001; }}
rssh() {{
  [[ "$*" != *audit-only-password-9* ]] || return 97
  local command="$1"
  if [ "$AUDIT_SCENARIO" = transport_fail ] && [[ "$command" = *'set -euo pipefail'* || "$command" = *'set -u; trap'* ]]; then return 40; fi
  command="${{command//\\$HOME/$AUDIT_ROOT}}"
  bash -c "$command"
}}
'''
    executable = case / 'script.sh'
    put(executable, setup + source)
    proc = subprocess.run([BASH, posix(executable), timestamp], cwd=ROOT, capture_output=True, timeout=25)
    events = (case / 'events').read_text().splitlines() if (case / 'events').exists() else []
    leftovers = list(case.glob('.di-drill*'))
    row = {'script': script, 'scenario': scenario, 'exit': proc.returncode,
            'imported': 'import' in events, 'codeCopied': 'tar' in events,
            'cleanup': not leftovers, 'chmodRequested': 'chmod' in events,
            'passwordNotInOutput': b'audit-only-password-9' not in proc.stdout + proc.stderr}
    for leftover in leftovers:
        assert LAB in leftover.resolve().parents
        leftover.unlink()  # Only synthetic files belonging to this fixture; never real credentials.
    return row

rows = []
for scenario in ['ok', 'production_target', 'nonempty', 'connection_fail', 'corrupt_dump',
                 'import_fail', 'decompress_fail', 'list_fail', 'list_empty',
                 'production_count_fail', 'test_count_fail', 'mismatch', 'lookup_empty', 'invalid_timestamp', 'transport_fail']:
    row = run('drill-db', scenario, '../invalid' if scenario == 'invalid_timestamp' else '20261016-134414',
              'production_db' if scenario == 'production_target' else 'test_db')
    row['pass'] = (row['exit'] == 0 if scenario == 'ok' else row['exit'] != 0) and row['cleanup'] and row['passwordNotInOutput']
    if scenario in ['production_target', 'nonempty', 'connection_fail', 'corrupt_dump', 'lookup_empty', 'invalid_timestamp']:
        row['pass'] = row['pass'] and not row['imported']
    if row['imported']:
        row['pass'] = row['pass'] and row['chmodRequested']
    rows.append(row)
for scenario in ['ok', 'export_fail', 'export_truncated', 'gzip_test_fail']:
    row = run('deploy-code', scenario)
    row['pass'] = row['exit'] == 0 and row['codeCopied'] if scenario == 'ok' else row['exit'] != 0 and not row['codeCopied']
    rows.append(row)
baseline = [run('drill-db', scenario, baseline=True) for scenario in ['mismatch', 'transport_fail']]
reproduced = baseline[0]['exit'] == 0 and not baseline[1]['cleanup']
(OUT / 'shell.json').write_text(json.dumps({'rows': rows, 'baselineRegressions': baseline, 'regressionsReproduced': reproduced, 'limits': 'Sanitized script copies and synthetic data/stubs only; lib/env excluded, no SSH/MySQL server or real secrets. HOME tokens virtualized to workspace fixtures without changing HOME. chmod requested is checked, not Linux permissions on Windows.'}, indent=2) + '\n', encoding='utf-8')
print(json.dumps({'cases': len(rows), 'failures': [r for r in rows if not r['pass']], 'oldRegressionsReproduced': reproduced}))
raise SystemExit(0 if all(r['pass'] for r in rows) and reproduced else 1)
