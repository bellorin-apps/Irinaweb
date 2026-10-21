"""Offline negative controls for the actual Q-082 checker; no network or WordPress writes."""
import ast, contextlib, io, runpy, sys, urllib.request
from pathlib import Path
from unittest.mock import patch
source = Path(__file__).with_name('cedulas_check.py')
constants = {}
for node in ast.parse(source.read_text(encoding='utf-8')).body:
    if isinstance(node, ast.Assign) and isinstance(node.targets[0], ast.Name) and node.targets[0].id in ('ES', 'EN', 'ABOUT'):
        constants[node.targets[0].id] = ast.literal_eval(node.value)
class Output(io.StringIO):
    def reconfigure(self, **kwargs): pass
class Response:
    def __init__(self, data): self.data=data
    def read(self): return self.data.encode()
def fixture():
    pages={}
    for route in constants['ES']+constants['EN']:
        content=''
        if route in constants['ABOUT']:
            content='<ul class="di-cedulas di-reveal">'+''.join('<li>'+x+'</li>' for x in constants['ABOUT'][route])+'</ul>'
        if route in ('/aviso-medico/', '/en/medical-disclaimer/'):
            en=route.startswith('/en/')
            content+='<p>'+('professional licenses' if en else 'cédulas profesionales')+' <a href="'+('/en/dr-irina-gonzalez-saez/' if en else '/dra-irina-gonzalez-saez/')+'">About</a></p>'
        pages[route]=content+'<p class="di-footer__creds">Consejo</p>'
    return pages
cases={'baseline':fixture()}
for name,route,needle,replacement in [
    ('specialty in footer','/dra-irina-gonzalez-saez/','Consejo','Consejo 15111342'),
    ('medicine in footer','/dra-irina-gonzalez-saez/','Consejo','Consejo 12438166'),
    ('footer missing','/','<p class="di-footer__creds">Consejo</p>',''),
    ('institution missing','/dra-irina-gonzalez-saez/','UNEFM – UDEM',''),
    ('outside About','/','Consejo','Consejo 15111342'),
    ('ES link only in navigation','/aviso-medico/','<p>cédulas profesionales','<nav><a href="/dra-irina-gonzalez-saez/">About</a></nav><p>other'),
    ('EN link only in navigation','/en/medical-disclaimer/','<p>professional licenses','<nav><a href="/en/dr-irina-gonzalez-saez/">About</a></nav><p>other'),
]:
    data=fixture(); data[route]=data[route].replace(needle,replacement); cases[name]=data
for name,data in cases.items():
    def fetch(request, **kwargs):
        route=request.full_url.removeprefix('http://fixture').split('?')[0]
        return Response(data[route])
    output=Output()
    with patch.object(sys,'argv',[str(source),'http://fixture']), patch.object(urllib.request,'urlopen',fetch), contextlib.redirect_stdout(output):
        try: runpy.run_path(str(source),run_name='__main__')
        except SystemExit as e: code=e.code
    assert (code==0)==(name=='baseline'), (name,output.getvalue())
    print('OK',name)
print(f'{len(cases)}/{len(cases)} offline controls; actual checker, mocked HTTP only')
