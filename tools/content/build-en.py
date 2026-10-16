#!/usr/bin/env python3
"""Versión en inglés (D-095, PLAN 13.3): páginas bajo /en/ como datos de Elementor con los mismos widgets DI que el español.

Reglas (Anexo A, D-095):
- traducción fiel del texto aprobado en español; nada médico se añade ni se «mejora»;
- la Dra. no atiende en inglés: se dice que la consulta es en español, sin prometer atención en inglés, turismo médico ni seguros
  extranjeros;
- terminología de docs/GLOSSARY_EN.md;
- todo se carga en BORRADOR con tools/deploy/setup-en.sh y solo se publica con la aprobación de la Dra. (13.6).

Salida en tools/content/en/: <slug>.elementor.json por página, seo.json (título y descripción de Rank Math) y practice-en.json
(textos del consultorio en inglés; setup-en.sh solo los escribe si están vacíos).
Las anclas inglesas de las páginas de área son #ear, #nose, #throat y #procedures (ENT), y #conditions y #pathway (Sleep).
"""
import hashlib, json, pathlib
from practice import CENTRO, CIUDAD, ZONA

OUT = pathlib.Path(__file__).with_name("en")


def eid(s):
    return hashlib.md5(("en-" + s).encode()).hexdigest()[:7]


def container(key, widget, settings):
    return {"id": eid("c-" + key), "elType": "container", "isInner": False,
            "settings": {"content_width": "full", "flex_direction": "column",
                         "padding": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True},
                         "margin": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True}},
            "elements": [{"id": eid("w-" + key), "elType": "widget", "widgetType": widget, "settings": settings, "elements": []}]}


def rep(items, *keys):
    return [dict({"_id": eid("r-" + str(i) + str(it))}, **dict(zip(keys, it))) for i, it in enumerate(items)]


SPANISH_NOTE = "Consultations are in Spanish."
MAP_EMBED = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3594.777590213581!2d-100.3765032!3d25.7117869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x866297c19d8eb1cb%3A0x5c3e8ac4d16be247!2sDra%20Irina%20Gonz%C3%A1lez%20S%C3%A1ez!5e0!3m2!1sen!2smx!4v1791433463370!5m2!1sen!2smx"

# Páginas: slug bajo /en/ → (título de WordPress, slug de la página española equivalente o «__front__», datos de Elementor).
pages = {}

pages["en"] = ("Home", "__front__", [
    container("hero", "di-hero", {
        "eyebrow": "Your ENT|in Monterrey",
        "title": "<b>Breathe</b> well,<br>sleep well,<br>hear <b><em>well</em></b>",
        "lead": f"Dr. Irina González Sáez, board-certified otolaryngologist (ENT) with subspecialty training in snoring and sleep apnea. Ear, nose and throat care for children and adults in Cumbres, Monterrey. {SPANISH_NOTE}",
        "lead_mobile": f"Board-certified ENT with subspecialty training in snoring and sleep apnea. Children and adults, in Cumbres. {SPANISH_NOTE}",
        "trust": f"Mexican Board of Otolaryngology–Head and Neck Surgery\nSubspecialty in sleep-disordered breathing\n{CENTRO} · {ZONA}, {CIUDAD}",
        "image": {"id": "__HERO_ID__", "url": "__HERO_URL__"},
        "secondary_label": "Meet Dr. Irina", "secondary_url": "/en/dr-irina-gonzalez-saez/",
        "ticker": "Snoring and sleep apnea\nBlocked nose\nSinusitis\nBlocked ear\nHearing loss and tinnitus\nVertigo\nTonsils and adenoids\nVoice and reflux"}),
    container("areas", "di-areas", {
        "eyebrow": "Areas of care", "title": "Two <b>specialties</b>, one <br class=\"di-brd\">way of <b><em>caring</em></b>",
        "orl_eyebrow": "Otolaryngology (ENT)", "orl_title": "Ear, nose and throat, from birth",
        "orl_items": "Ear infections\nHearing loss\nTinnitus\nVertigo\nRhinitis\nSinusitis\nDeviated septum\nTonsils\nVoice",
        "orl_link": "/en/ent/", "orl_link_label": "See what I treat",
        "orl_image": {"id": "__ORL_IMG_ID__", "url": "__ORL_IMG_URL__"},
        "sleep_eyebrow": "Sleep and breathing", "sleep_title": "Snoring and sleep apnea, with complete diagnosis and treatment",
        "sleep_items": "Home sleep test\nSleep endoscopy\nCPAP\nPharyngoplasty\nNasal surgery",
        "sleep_link": "/en/sleep/", "sleep_link_label": "See the full pathway",
        "sleep_image": {"id": "__SUENO_IMG_ID__", "url": "__SUENO_IMG_URL__"}}),
    container("motivos", "di-motivos", {
        "eyebrow": "What are you feeling?", "title": "Common <b>reasons</b> to <b><em>visit</em></b>",
        "note": "A guide to finding the right information. It does not replace a medical evaluation.",
        "items": rep([
            ("I snore, or I’ve been told I stop breathing", "Snoring, sleep apnea", "/en/sleep/#conditions"),
            ("My nose is always blocked", "Nasal obstruction, septum, turbinates", "/en/ent/#nose"),
            ("Sinusitis that won’t go away", "Acute and chronic sinusitis", "/en/ent/#nose"),
            ("Ear pain or a blocked ear", "Ear infection, earwax blockage", "/en/ent/#ear"),
            ("I hear less or my ear rings", "Hearing loss, tinnitus", "/en/ent/#ear"),
            ("Sneezing and allergies", "Allergic rhinitis", "/en/ent/#nose"),
            ("Frequent sore throat", "Tonsillitis, tonsils in children", "/en/ent/#throat"),
            ("Hoarseness or voice changes", "Larynx, laryngopharyngeal reflux", "/en/ent/#throat"),
        ], "label", "sub", "url")}),
    container("doctora", "di-doctora", {
        "eyebrow": "Dr. Irina González Sáez", "quote": "“<b>Listening</b> <br class=\"di-brd\">is also part <br class=\"di-brd\">of <b><em>treatment</em></b>”",
        "text": "Medical degree from <abbr title=\"Universidad Nacional Experimental Francisco de Miranda\">UNEFM</abbr> and specialty in Otolaryngology from <abbr title=\"Universidad Centroccidental Lisandro Alvarado\">UCLA</abbr>, Venezuela. Subspecialty training in sleep-disordered breathing, snoring and applied rhinology at ISSSTE in Monterrey.",
        "creds": "Board-certified by the Mexican Board of Otolaryngology–Head and Neck Surgery\nIbero-American Society of Sleep Surgery · Nuevo León College of Otolaryngology\nPractices at Christus Muguerza, Zambrano Hellion, Ángeles Valle Oriente, Hospital Universitario and Hospitaria",
        "link": "/en/dr-irina-gonzalez-saez/", "link_label": "Meet Dr. Irina",
        "image": {"id": "__DRA_IMG_ID__", "url": "__DRA_IMG_URL__"}, "bg": {"id": "__DRA_BG_ID__", "url": "__DRA_BG_URL__"}, "bg_tone": "light"}),
    container("pasos", "di-pasos", {
        "eyebrow": "How we work", "title": "What to <b>expect</b> at your <b><em>visit</em></b>",
        "steps": rep([
            ("Listen", "Your symptoms, how long you’ve had them and how they affect your day. Bring previous tests and your list of medications.", "paso-escuchar"),
            ("Examine", "Ear, nose and throat; nasal endoscopy or laryngoscopy in the office when needed.", "paso-explorar"),
            ("Diagnose", "If the problem is sleep-related, respiratory polygraphy or a home sleep test performed by Dr. Irina herself.", "paso-diagnosticar"),
            ("Support", "A clear plan, with medical and surgical options explained, and follow-up in person or online.", "paso-acompanar"),
        ], "title", "text", "img")}),
    container("sueno", "di-sueno", {
        "eyebrow": "Sleep specialization", "title": "From <b>snoring</b><br>to rest, all in<br>one <b><em>place</em></b>",
        "lead": "In Monterrey, snoring and sleep apnea often go through several specialists. Dr. Irina evaluates the airway, performs the study, interprets the result and offers everything from CPAP to palate and nasal surgery.",
        "link": "/en/sleep/", "link_label": "Explore the treatment pathway",
        "steps": "Airway evaluation and sleep endoscopy\nRespiratory polygraphy or home sleep test, interpreted by Dr. Irina\nTreatment: CPAP with follow-up, positional therapy, palate or nasal surgery\nFollow-up and adjustment until you sleep well"}),
    container("ubicacion", "di-ubicacion", {
        "eyebrow": "Office", "title": "",
        "facts": rep([("Parking", "Free"), ("Access", "Elevator and wheelchair access"), ("Language", "Consultations are in Spanish")], "label", "value"),
        "map_embed": MAP_EMBED}),
    container("cta", "di-cta", {
        "eyebrow": "Book", "title": "Shall we talk about <br class=\"di-brd\">what’s keeping you <br class=\"di-brd\">from <em>resting</em>?",
        "text": f"Message us on WhatsApp and we’ll help you choose the right type of visit. {SPANISH_NOTE}", "message": ""}),
])

seo = {
    "en": ("ENT doctor in Monterrey | Dr. Irina González Sáez",
           "Board-certified ENT in Cumbres, Monterrey: ear, nose and throat for children and adults, snoring and sleep apnea. Consultations are in Spanish."),
}

practice_en = {
    "nombre_profesional_en": "Dr. Irina González Sáez",
    "especialidad_en": "Otolaryngologist (ENT)",
    "subespecialidad_en": "Subspecialty in sleep-disordered breathing",
    "interior_en": "Office 6, 2nd floor",
    "consejo_certificado_en": "Board-certified by the Mexican Board of Otolaryngology–Head and Neck Surgery",
    "whatsapp_mensaje_en": "Hello, I would like to book an appointment with Dr. Irina González Sáez.",
}

OUT.mkdir(exist_ok=True)
for slug, (title, es, data) in pages.items():
    (OUT / f"{slug}.elementor.json").write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
(OUT / "pages.json").write_text(json.dumps({s: {"title": t, "es": e} for s, (t, e, _) in pages.items()}, ensure_ascii=False, indent=2), encoding="utf-8")
(OUT / "seo.json").write_text(json.dumps({s: {"title": t, "description": d} for s, (t, d) in seo.items()}, ensure_ascii=False, indent=2), encoding="utf-8")
(OUT / "practice-en.json").write_text(json.dumps(practice_en, ensure_ascii=False, indent=2), encoding="utf-8")
for s, (t, d) in seo.items():
    assert len(t) <= 62 and len(d) <= 158, (s, len(t), len(d))
print(f"{len(pages)} página(s) en {OUT}")
