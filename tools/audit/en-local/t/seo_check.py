"""13.5 integrado (WordPress 7.1.3 + Rank Math 1.0.280 locales): OG, canónicas, hreflang, sitemap, selector, schema, 404 y Gracias.
Solo lectura sobre http://127.0.0.1:8890 (copia aislada). Uso: python -I seo_check.py <salida.json>"""
import json
import re
import sys
import urllib.error
import urllib.request

sys.stdout.reconfigure(encoding="utf-8")
S = "http://127.0.0.1:8890"
EN = ["/en/", "/en/dr-irina-gonzalez-saez/", "/en/ent/", "/en/sleep/", "/en/first-visit/", "/en/contact/", "/en/faq/",
      "/en/privacy-notice/", "/en/medical-disclaimer/", "/en/terms-of-use/", "/en/sleep/snoring/", "/en/sleep/obstructive-sleep-apnea/",
      "/en/sleep/home-sleep-test/", "/en/sleep/cpap-therapy/", "/en/sleep/snoring-and-sleep-apnea-surgery/"]
PAIR = {"/en/": "/", "/en/dr-irina-gonzalez-saez/": "/dra-irina-gonzalez-saez/", "/en/ent/": "/otorrinolaringologia/", "/en/sleep/": "/sueno/",
        "/en/first-visit/": "/primera-consulta/", "/en/contact/": "/contacto/", "/en/faq/": "/preguntas-frecuentes/",
        "/en/privacy-notice/": "/aviso-de-privacidad/", "/en/medical-disclaimer/": "/aviso-medico/", "/en/terms-of-use/": "/terminos-de-uso/",
        "/en/sleep/snoring/": "/sueno/ronquido/", "/en/sleep/obstructive-sleep-apnea/": "/sueno/apnea-obstructiva-del-sueno/",
        "/en/sleep/home-sleep-test/": "/sueno/estudio-del-sueno/", "/en/sleep/cpap-therapy/": "/sueno/cpap/",
        "/en/sleep/snoring-and-sleep-apnea-surgery/": "/sueno/cirugia-de-ronquido-y-apnea/"}


def get(path):
    try:
        r = urllib.request.urlopen(S + path, timeout=60)
        return r.status, r.read().decode("utf-8")
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode("utf-8", "replace")


def meta(h, prop):
    m = re.search(r'<meta (?:property|name)="%s" content="([^"]*)"' % re.escape(prop), h)
    return m.group(1) if m else None


def head(h):
    graphs = []
    for b in re.findall(r'<script type="application/ld\+json"[^>]*>(.*?)</script>', h, re.S):
        try:
            j = json.loads(b)
            graphs += j.get("@graph", [j]) if isinstance(j, dict) else j
        except ValueError:
            graphs.append({"@type": "INVALID"})
    alts = dict((l, u.replace(S, "")) for u, l in re.findall(r'<link rel="alternate" href="([^"]+)" hreflang="([^"]+)"', h))
    alts.update(dict((l, u.replace(S, "")) for l, u in re.findall(r'<link rel="alternate" hreflang="([^"]+)" href="([^"]+)"', h)))
    canon = re.search(r'<link rel="canonical" href="([^"]+)"', h)
    sel = re.findall(r'<a[^>]*class="[^"]*di-lang[^"]*"[^>]*href="([^"]+)"', h) + re.findall(r'<a[^>]*href="([^"]+)"[^>]*class="[^"]*di-lang[^"]*"', h)
    types = []
    for g in graphs:
        t = g.get("@type")
        types.append("+".join(t) if isinstance(t, list) else str(t))
    return {
        "lang": (re.search(r'<html[^>]*lang="([^"]+)"', h) or [None, None])[1],
        "title": (re.search(r"<title>([^<]*)", h) or [None, None])[1],
        "robots": meta(h, "robots"),
        "canonical": canon.group(1).replace(S, "") if canon else None,
        "og": {k: meta(h, "og:" + k) for k in ("locale", "title", "description", "url", "image", "type")},
        "og_locale_alt": re.findall(r'<meta property="og:locale:alternate" content="([^"]*)"', h),
        "hreflang": alts,
        "selector": sorted({u.replace(S, "") for u in sel}),
        "schema_types": sorted(types),
        "inLanguage": sorted({g.get("inLanguage") for g in graphs if g.get("inLanguage")}),
        "physician_nodes": sum(1 for t in types if "Physician" in t),
        "faq_questions": sum(len(g.get("mainEntity") or []) for g in graphs if "FAQPage" in str(g.get("@type"))),
        "wa_text": sorted({re.sub(r"%20|\+", " ", m) for m in re.findall(r'wa\.me/\d+\?text=([^"&]+)', h)})[:2],
        "details_visible": len(re.findall(r"<details", h)),
    }


out = {"en": {}, "es": {}, "sitemap": {}, "extra": {}}
for p in EN:
    code, h = get(p)
    out["en"][p] = {"code": code, **head(h)}
for p in sorted(set(PAIR.values())) + ["/gracias/"]:
    code, h = get(p)
    out["es"][p] = {"code": code, **head(h)}
code, h = get("/en/thank-you/")
out["extra"]["/en/thank-you/"] = {"code": code, **head(h)}
code, h = get("/en/no-existe-prueba/")
out["extra"]["/en/no-existe-prueba/"] = {"code": code, "h1": re.findall(r"<h1[^>]*>(.*?)</h1>", h, re.S)[:1], **head(h)}
code, h = get("/no-existe-prueba/")
out["extra"]["/no-existe-prueba/"] = {"code": code, **head(h)}
code, h = get("/en/contact/")
out["extra"]["form_en"] = {"di_lang": re.findall(r'name="di_lang" value="([^"]*)"', h), "privacy_link": re.findall(r'href="([^"]*privacy[^"]*)"', h)[:2],
                          "motivos": re.findall(r"<option[^>]*>([^<]+)", h)[:8], "redirect": re.findall(r'name="di_redirect" value="([^"]*)"', h)}
code, h = get("/contacto/")
out["extra"]["form_es"] = {"di_lang": re.findall(r'name="di_lang" value="([^"]*)"', h), "motivos": re.findall(r"<option[^>]*>([^<]+)", h)[:8]}
locs = []
for m in ("page-sitemap", "condicion-sitemap", "tratamiento-sitemap"):
    code, h = get("/%s.xml" % m)
    locs += [u.replace(S, "") for u in re.findall(r"<loc>([^<]+)</loc>", h)]
out["sitemap"] = {"total": len(locs), "en": sorted(u for u in locs if u.startswith("/en/")), "es": sorted(u for u in locs if not u.startswith("/en/")),
                  "thank_you": [u for u in locs if "thank-you" in u or "gracias" in u]}
json.dump(out, open(sys.argv[1], "w", encoding="utf-8"), ensure_ascii=False, indent=1)

# Resumen y aserciones
fail = []
for p, r in out["en"].items():
    es = PAIR[p]
    if r["code"] != 200: fail.append(f"{p}: HTTP {r['code']}")
    if r["lang"] != "en-US": fail.append(f"{p}: lang {r['lang']}")
    if r["canonical"] != p: fail.append(f"{p}: canonical {r['canonical']}")
    if r["og"]["locale"] != "en_US": fail.append(f"{p}: og:locale {r['og']['locale']}")
    if (r["og"]["url"] or "").replace(S, "") != p: fail.append(f"{p}: og:url {r['og']['url']}")
    if r["hreflang"] != {"es-MX": es, "en-US": p, "x-default": es}: fail.append(f"{p}: hreflang {r['hreflang']}")
    if r["inLanguage"] and r["inLanguage"] != ["en-US"]: fail.append(f"{p}: inLanguage {r['inLanguage']}")
    if r["physician_nodes"] > 1: fail.append(f"{p}: {r['physician_nodes']} nodos Physician")
    if r["selector"] != [es]: fail.append(f"{p}: selector {r['selector']}")
for es, r in out["es"].items():
    if es == "/gracias/": continue
    en = [k for k, v in PAIR.items() if v == es][0]
    if r["hreflang"] != {"es-MX": es, "en-US": en, "x-default": es}: fail.append(f"{es}: hreflang {r['hreflang']}")
    if r["og"]["locale"] != "es_MX": fail.append(f"{es}: og:locale {r['og']['locale']}")
    if r["selector"] != [en]: fail.append(f"{es}: selector {r['selector']}")
if out["sitemap"]["total"] != 30 or out["sitemap"]["thank_you"]: fail.append(f"sitemap {out['sitemap']['total']} / gracias {out['sitemap']['thank_you']}")
ty = out["extra"]["/en/thank-you/"]
if "noindex" not in (ty["robots"] or ""): fail.append(f"/en/thank-you/ robots {ty['robots']}")
nf = out["extra"]["/en/no-existe-prueba/"]
print("404 EN:", nf["code"], nf["lang"], nf["title"], "· 404 ES:", out["extra"]["/no-existe-prueba/"]["code"], out["extra"]["/no-existe-prueba/"]["title"])
print("Gracias EN:", ty["code"], ty["robots"], "· formulario EN:", out["extra"]["form_en"], "· ES di_lang:", out["extra"]["form_es"]["di_lang"])
print("Sitemap:", out["sitemap"]["total"], "URL (", len(out["sitemap"]["en"]), "EN +", len(out["sitemap"]["es"]), "ES ) · gracias/thank-you en sitemap:", out["sitemap"]["thank_you"])
print("OG imagen EN (muestra):", sorted({(v["og"]["image"] or "").replace(S, "") for v in out["en"].values()})[:3])
print("Tipos de schema EN (muestra):", {p: out["en"][p]["schema_types"] for p in ("/en/", "/en/faq/", "/en/sleep/cpap-therapy/", "/en/contact/")})
print("FAQ preguntas schema /en/faq/:", out["en"]["/en/faq/"]["faq_questions"], "· <details> visibles:", out["en"]["/en/faq/"]["details_visible"])
print("WhatsApp EN (muestra):", out["en"]["/en/"]["wa_text"])
print("FALLOS:" if fail else "TODAS LAS ASERCIONES PASAN", *fail, sep="\n  ")
