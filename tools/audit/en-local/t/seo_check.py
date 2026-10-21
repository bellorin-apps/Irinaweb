"""13.5 integrado (WordPress 7.1.3 + Rank Math 1.0.280): OG, canónicas, hreflang, sitemap, selector, schema, 404 y Gracias.
Solo lectura (GET; no envía formularios).
  Copia aislada:  python -I seo_check.py <salida.json>                  (http://127.0.0.1:8890)
  Producción:     python -I seo_check.py --base https://drairinagonzalez.com --prod <salida.json>
                  (gate de 13.5 tras un lanzamiento AUTORIZADO; añade enlaces internos de las EN, og:image y caché LiteSpeed)
  Autoprueba:     python -I seo_check.py --selftest
Sale con 1 si alguna aserción falla."""
import json
import re
import sys
import time
import urllib.error
import urllib.parse
import urllib.request

sys.stdout.reconfigure(encoding="utf-8")
S = "http://127.0.0.1:8890"
PROD = False
EN = ["/en/", "/en/dr-irina-gonzalez-saez/", "/en/ent/", "/en/sleep/", "/en/first-visit/", "/en/contact/", "/en/faq/",
      "/en/privacy-notice/", "/en/medical-disclaimer/", "/en/terms-of-use/", "/en/sleep/snoring/", "/en/sleep/obstructive-sleep-apnea/",
      "/en/sleep/home-sleep-test/", "/en/sleep/cpap-therapy/", "/en/sleep/snoring-and-sleep-apnea-surgery/"]
PAIR = {"/en/": "/", "/en/dr-irina-gonzalez-saez/": "/dra-irina-gonzalez-saez/", "/en/ent/": "/otorrinolaringologia/", "/en/sleep/": "/sueno/",
        "/en/first-visit/": "/primera-consulta/", "/en/contact/": "/contacto/", "/en/faq/": "/preguntas-frecuentes/",
        "/en/privacy-notice/": "/aviso-de-privacidad/", "/en/medical-disclaimer/": "/aviso-medico/", "/en/terms-of-use/": "/terminos-de-uso/",
        "/en/sleep/snoring/": "/sueno/ronquido/", "/en/sleep/obstructive-sleep-apnea/": "/sueno/apnea-obstructiva-del-sueno/",
        "/en/sleep/home-sleep-test/": "/sueno/estudio-del-sueno/", "/en/sleep/cpap-therapy/": "/sueno/cpap/",
        "/en/sleep/snoring-and-sleep-apnea-surgery/": "/sueno/cirugia-de-ronquido-y-apnea/"}


class _NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


_OPEN = urllib.request.build_opener(_NoRedirect)
_UA = {"User-Agent": "di-seo-check/13.5 (solo lectura)"}


def fetch(url, cache_bust=False):
    """GET sin seguir redirecciones (un 301 se ve como 301). Devuelve (código, cuerpo, cabeceras)."""
    if cache_bust:
        url += ("&" if "?" in url else "?") + "di_cb=%d" % time.time()
    try:
        r = _OPEN.open(urllib.request.Request(url, headers=_UA), timeout=60)
        return r.status, r.read().decode("utf-8", "replace"), {k.lower(): v for k, v in r.headers.items()}  # cabeceras sin distinguir mayúsculas
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode("utf-8", "replace"), {k.lower(): v for k, v in (e.headers or {}).items()}


# Nodos acordados para la paridad EN↔ES (Q-077). ImageObject, BreadcrumbList, etc. son auxiliares de Rank Math y pueden
# variar con la imagen OG o la caché; no forman parte del acuerdo (producción: la portada ES lleva ImageObject).
NODOS = {"WebSite", "WebPage", "ProfilePage", "ContactPage", "MedicalWebPage", "FAQPage", "Physician+Person"}


def get(path):
    code, body, _ = fetch(S + path)
    return code, body


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


def collect():
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
    if PROD:
        out["prod"] = collect_prod(out)
    return out


def collect_prod(out):
    """Solo producción: enlaces internos de las 15 EN, og:image y cabecera de caché (sin seguir redirecciones)."""
    host = urllib.parse.urlparse(S).netloc
    links = {}
    for p in EN:
        _, h, hd = fetch(S + p)
        out["en"][p]["cache"] = hd.get("x-litespeed-cache")  # el servidor varía las mayúsculas del nombre (X-Litespeed-Cache…)
        for u in re.findall(r'<a[^>]+href="([^"#]+)"', h):
            pu = urllib.parse.urlparse(urllib.parse.urljoin(S + p, u))
            if pu.scheme in ("http", "https") and pu.netloc == host:
                links.setdefault(pu.path + (("?" + pu.query) if pu.query else ""), set()).add(p)
    bad = {}
    for path in sorted(links):
        code = fetch(S + path)[0]
        if code != 200:
            bad[path] = {"code": code, "from": sorted(links[path])}
    imgs = sorted({r["og"]["image"] for r in out["en"].values() if r["og"]["image"]})
    return {"links_checked": len(links), "links_bad": bad,
            "og_images": {u: fetch(u)[0] if u.startswith("https://") else "NO-HTTPS" for u in imgs}}


def evaluate(out):
    """Aserciones de 13.5. Devuelve (fallos, avisos). inLanguage es OBLIGATORIO (en-US) en las 15 EN y cada EN lleva al menos
    los nodos de schema de su pareja ES (WebSite/WebPage en /en/, ProfilePage/Physician en About; Q-077, endurecido 2026-10-19)."""
    fail, warn = [], []
    for p, r in out["en"].items():
        es = PAIR[p]
        if r["code"] != 200: fail.append(f"{p}: HTTP {r['code']}")
        if r["lang"] != "en-US": fail.append(f"{p}: lang {r['lang']}")
        if r["canonical"] != p: fail.append(f"{p}: canonical {r['canonical']}")
        if r["og"]["locale"] != "en_US": fail.append(f"{p}: og:locale {r['og']['locale']}")
        if (r["og"]["url"] or "").replace(S, "") != p: fail.append(f"{p}: og:url {r['og']['url']}")
        if r["hreflang"] != {"es-MX": es, "en-US": p, "x-default": es}: fail.append(f"{p}: hreflang {r['hreflang']}")
        if r["inLanguage"] != ["en-US"]: fail.append(f"{p}: inLanguage {r['inLanguage'] or 'VACÍO'}")
        if r["physician_nodes"] > 1: fail.append(f"{p}: {r['physician_nodes']} nodos Physician")
        falta = sorted((set(out["es"][es]["schema_types"]) - set(r["schema_types"])) & NODOS)
        if falta: fail.append(f"{p}: faltan nodos de su pareja ES {falta}")
        if r["selector"] != [es]: fail.append(f"{p}: selector {r['selector']}")
    for es, r in out["es"].items():
        if es == "/gracias/": continue
        en = [k for k, v in PAIR.items() if v == es][0]
        if r["hreflang"] != {"es-MX": es, "en-US": en, "x-default": es}: fail.append(f"{es}: hreflang {r['hreflang']}")
        if r["og"]["locale"] != "es_MX": fail.append(f"{es}: og:locale {r['og']['locale']}")
        if r["selector"] != [en]: fail.append(f"{es}: selector {r['selector']}")
        if "en-US" in r["inLanguage"]: fail.append(f"{es}: inLanguage inglés en página española")
        if not r["inLanguage"]: warn.append(f"{es}: sin inLanguage (español; fuera del alcance de 13.5)")
    if out["sitemap"]["total"] != 30 or out["sitemap"]["thank_you"]: fail.append(f"sitemap {out['sitemap']['total']} / gracias {out['sitemap']['thank_you']}")
    ty = out["extra"]["/en/thank-you/"]
    if "noindex" not in (ty["robots"] or ""): fail.append(f"/en/thank-you/ robots {ty['robots']}")
    pr = out.get("prod")
    if pr is not None:
        for path, b in pr["links_bad"].items(): fail.append(f"enlace interno {path} → {b['code']} (desde {', '.join(b['from'][:3])})")
        for u, c in pr["og_images"].items():
            if c != 200: fail.append(f"og:image {u} → {c}")
        if not pr["og_images"]: fail.append("ninguna EN declara og:image")
        for p, r in out["en"].items():
            if r.get("cache") is None: warn.append(f"{p}: sin cabecera x-litespeed-cache (¿caché desactivada o CDN?)")
    return fail, warn


def selftest():
    """Casos negativos sintéticos: cada mutación debe producir al menos un fallo; el caso base, ninguno."""
    import copy
    def page(p, lang, inl, alts, sel):
        return {"code": 200, "lang": lang, "canonical": p, "og": {"locale": "en_US" if lang == "en-US" else "es_MX", "url": S + p},
                "hreflang": alts, "inLanguage": inl, "physician_nodes": 1, "selector": sel,
                "schema_types": ["BreadcrumbList", "Physician+Person", "WebPage"] + (["WebSite"] if p in ("/", "/en/") else [])
                + (["ProfilePage"] if p.endswith("irina-gonzalez-saez/") else [])}
    base = {"en": {}, "es": {}, "sitemap": {"total": 30, "thank_you": []}, "extra": {"/en/thank-you/": {"robots": "nofollow, noindex"}}}
    for en, es in PAIR.items():
        alts = {"es-MX": es, "en-US": en, "x-default": es}
        base["en"][en] = page(en, "en-US", ["en-US"], alts, [es])
        base["es"][es] = page(es, "es", ["es-MX"], alts, [en])
    muts = {
        "inLanguage vacío": lambda o: o["en"]["/en/"].__setitem__("inLanguage", []),
        "inLanguage español en EN": lambda o: o["en"]["/en/faq/"].__setitem__("inLanguage", ["es-MX"]),
        "canónica a la pareja ES": lambda o: o["en"]["/en/ent/"].__setitem__("canonical", "/otorrinolaringologia/"),
        "hreflang sin x-default": lambda o: o["en"]["/en/sleep/"]["hreflang"].pop("x-default"),
        "og:locale es_MX en EN": lambda o: o["en"]["/en/contact/"]["og"].__setitem__("locale", "es_MX"),
        "sitemap 29": lambda o: o["sitemap"].__setitem__("total", 29),
        "thank-you indexable": lambda o: o["extra"]["/en/thank-you/"].__setitem__("robots", "index, follow"),
        "selector a la portada": lambda o: o["en"]["/en/faq/"].__setitem__("selector", ["/"]),
        "dos nodos Physician": lambda o: o["en"]["/en/"].__setitem__("physician_nodes", 2),
        "prod: enlace interno roto": lambda o: o.__setitem__("prod", {"links_checked": 1, "links_bad": {"/en/x/": {"code": 404, "from": ["/en/"]}}, "og_images": {"https://e/x.jpg": 200}}),
        "prod: og:image 404": lambda o: o.__setitem__("prod", {"links_checked": 1, "links_bad": {}, "og_images": {"https://e/x.jpg": 404}}),
        "About EN sin ProfilePage": lambda o: o["en"]["/en/dr-irina-gonzalez-saez/"].__setitem__("schema_types", ["BreadcrumbList", "Physician+Person", "WebPage"]),
        "portada EN sin WebSite/WebPage": lambda o: o["en"]["/en/"].__setitem__("schema_types", ["BreadcrumbList", "Physician+Person"]),
    }
    ok = not evaluate(base)[0]
    print(("  OK " if ok else "  FALLO ") + "caso base sin fallos")
    for name, m in muts.items():
        o = copy.deepcopy(base); m(o); f, _ = evaluate(o)
        print(("  OK " if f else "  FALLO ") + f"{name} → {len(f)} fallo(s)")
        ok = ok and bool(f)
    return ok


if __name__ == "__main__":
    args = sys.argv[1:]
    if args and args[0] == "--selftest":
        sys.exit(0 if selftest() else 1)
    if "--base" in args:
        i = args.index("--base"); S = args[i + 1].rstrip("/"); del args[i:i + 2]
    if "--prod" in args:
        args.remove("--prod"); PROD = True
    if len(args) != 1:
        sys.exit("Uso: seo_check.py [--base URL] [--prod] <salida.json> | --selftest")
    print("Base:", S, "· producción" if PROD else "")
    out = collect()
    json.dump(out, open(args[0], "w", encoding="utf-8"), ensure_ascii=False, indent=1)
    fail, warn = evaluate(out)
    print("Sitemap:", out["sitemap"]["total"], "URL ·", len(out["sitemap"]["en"]), "EN +", len(out["sitemap"]["es"]), "ES")
    for w in warn: print("  aviso:", w)
    print("FALLOS:" if fail else "TODAS LAS ASERCIONES PASAN", *fail, sep="\n  ")
    sys.exit(1 if fail else 0)
