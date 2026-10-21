"""Q-082 (D-107): las cédulas salen SOLO en Conóceme (ES y EN), con su institución; el pie ya no las muestra; el aviso médico
remite a Conóceme/About. Solo lectura. Uso: python -I cedulas_check.py [base]"""
import re
import sys
import urllib.request

sys.stdout.reconfigure(encoding="utf-8")
B = (sys.argv[1] if len(sys.argv) > 1 else "http://127.0.0.1:8890").rstrip("/")
ES = ["/", "/otorrinolaringologia/", "/sueno/", "/dra-irina-gonzalez-saez/", "/primera-consulta/", "/contacto/", "/preguntas-frecuentes/", "/aviso-de-privacidad/", "/aviso-medico/", "/terminos-de-uso/", "/sueno/ronquido/", "/sueno/apnea-obstructiva-del-sueno/", "/sueno/estudio-del-sueno/", "/sueno/cpap/", "/sueno/cirugia-de-ronquido-y-apnea/"]
EN = ["/en/", "/en/ent/", "/en/sleep/", "/en/dr-irina-gonzalez-saez/", "/en/first-visit/", "/en/contact/", "/en/faq/", "/en/privacy-notice/", "/en/medical-disclaimer/", "/en/terms-of-use/", "/en/sleep/snoring/", "/en/sleep/obstructive-sleep-apnea/", "/en/sleep/home-sleep-test/", "/en/sleep/cpap-therapy/", "/en/sleep/snoring-and-sleep-apnea-surgery/"]
ABOUT = {"/dra-irina-gonzalez-saez/": ["Céd. Prof. 12438166 · UNEFM – UDEM", "Céd. Esp. 15111342 · Universidad Centroccidental Lisandro Alvarado"],
         "/en/dr-irina-gonzalez-saez/": ["Professional license (cédula) 12438166 · UNEFM – UDEM", "Specialty license (cédula) 15111342 · Universidad Centroccidental Lisandro Alvarado"]}
fail = []
def text(html):
    import html as h
    return h.unescape(re.sub(r"\s+", " ", re.sub(r"<[^>]+>", " ", html)))
for p in ES + EN:
    raw = urllib.request.urlopen(urllib.request.Request(B + p + "?q082=1", headers={"User-Agent": "di-q082"}), timeout=60).read().decode("utf-8")
    block = re.findall(r'<ul class="di-cedulas[^"]*">(.*?)</ul>', raw, re.S)
    foot = re.findall(r'<p class="di-footer__creds">(.*?)</p>', raw, re.S)
    nums = ("12438166" in raw, "15111342" in raw)
    if p in ABOUT:
        got = text(block[0]).strip() if block else ""
        ok = bool(block) and all(x in got for x in ABOUT[p])
        line = f"{'OK  ' if ok else 'FAIL'} {p} cédulas en Conóceme: «{got}»"
    else:
        ok = not block and not any(nums)
        line = f"{'OK  ' if ok else 'FAIL'} {p} sin cédulas" + ("" if ok else f" (bloque {bool(block)}, números {nums})")
    footer_ok = all("12438166" not in f and "Cédulas" not in f for f in foot)
    if not footer_ok: ok = False; line += f" · PIE CON CÉDULAS: {text(foot[0])}"
    elif foot: line += f" · pie: «{text(foot[0]).strip()[:60]}…»"
    if p in ("/aviso-medico/", "/en/medical-disclaimer/"):
        want = 'href="/dra-irina-gonzalez-saez/"' if p == "/aviso-medico/" else 'href="/en/dr-irina-gonzalez-saez/"'
        rem = want in raw and ("al pie de cada" not in raw and "foot of every" not in raw)
        ok = ok and rem; line += f" · remisión a Conóceme/About: {'sí' if rem else 'NO'}"
    if not ok: fail.append(p)
    print(line)
print(f"\n{len(fail)} fallos de {len(ES + EN)}")
sys.exit(1 if fail else 0)
