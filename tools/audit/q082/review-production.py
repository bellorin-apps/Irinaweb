"""Run the hardened checker on normal public URLs; GET only, no asset substitution."""
import json, runpy, sys, urllib.request
from pathlib import Path
from unittest.mock import patch
OUT=Path('docs/audit/2026-10-21-q082-codex-prod')
real=urllib.request.urlopen
rows=[]
def fetch(request, **kwargs):
    url=request.full_url.split('?')[0]
    response=real(urllib.request.Request(url,headers={'User-Agent':'di-q082-review'}),**kwargs)
    rows.append({'url':url,'status':response.status,'cache':response.headers.get('X-LiteSpeed-Cache')})
    assert response.status==200
    return response
with patch.object(sys,'argv',['cedulas_check.py','https://drairinagonzalez.com']), patch.object(urllib.request,'urlopen',fetch):
    try: runpy.run_path(str(Path(__file__).with_name('cedulas_check.py')),run_name='__main__')
    except SystemExit as e: code=e.code
(OUT/'normal-headers.json').write_text(json.dumps(rows,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
assert len(rows)==30
sys.exit(code)
