#!/usr/bin/env python3
"""5.3 / Q-042: comprueba que la dirección y los teléfonos tienen una sola fuente en los builds y que coinciden con lo publicado.

1. Ningún build (tools/content/build-*.py) escribe a mano la calle, el número interior, el código postal o los teléfonos:
   deben venir de tools/content/practice.py.
2. practice.py coincide con el schema publicado en la portada (PostalAddress y telephone), que sale de los ajustes del plugin.
Solo lectura (una petición GET anónima). Uso: python3 tools/audit/nap-check.py [--offline]
"""
import json, pathlib, re, sys, urllib.request

root = pathlib.Path(__file__).resolve().parents[2]
sys.path.insert(0, str(root / "tools/content"))
sys.dont_write_bytecode = True
import practice as p  # noqa: E402

fail = 0
def phone_forms(e164):
    d = e164[3:]
    return [e164, d, f"{d[:2]} {d[2:6]} {d[6:]}", f"({d[:2]}) {d[2:6]} {d[6:]}"]


literals = [p.CALLE, p.INTERIOR, p.CP] + phone_forms(p.TELEFONO) + phone_forms(p.WHATSAPP)
for build in sorted((root / "tools/content").glob("build-*.py")):
    text = build.read_text(encoding="utf-8")
    for lit in literals:
        if lit in text:
            fail += 1
            print(f"FALLA {build.name}: escribe «{lit}» a mano; usa practice.py")
print("Builds: sin dirección ni teléfonos escritos a mano" if not fail else f"Builds: {fail} repeticiones")

if "--offline" not in sys.argv:
    req = urllib.request.Request("https://drairinagonzalez.com/", headers={"User-Agent": "nap-check (Irinaweb audit)"})
    html = urllib.request.urlopen(req, timeout=30).read().decode("utf-8")
    nodes = []
    for block in re.findall(r'<script type="application/ld\+json"[^>]*>(.*?)</script>', html, re.S):
        data = json.loads(block)
        nodes += data.get("@graph", [data])
    node = next((n for n in nodes if isinstance(n, dict) and "address" in n), None)
    if node is None:
        fail += 1
        print("FALLA schema: no hay nodo con address en la portada")
    else:
        a = node["address"]
        expected = {"streetAddress": p.STREET_ADDRESS, "postalCode": p.CP, "addressLocality": p.CIUDAD, "addressRegion": p.ESTADO}
        for key, value in expected.items():
            if a.get(key) != value:
                fail += 1
                print(f"FALLA schema {key}: publicado «{a.get(key)}» ≠ practice.py «{value}»")
        if node.get("telephone") != p.TELEFONO:
            fail += 1
            print(f"FALLA schema telephone: publicado «{node.get('telephone')}» ≠ practice.py «{p.TELEFONO}»")
        if not fail:
            print("Schema publicado: dirección y teléfono iguales a practice.py")
sys.exit(1 if fail else 0)
