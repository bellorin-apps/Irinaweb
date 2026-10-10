#!/usr/bin/env python3
"""Genera dra-elementor.json (página de la Dra., widgets DI) y credenciales.json (CPT credencial, solo datos confirmados por documento/briefing)."""
import json, hashlib, pathlib

def eid(s): return hashlib.md5(s.encode()).hexdigest()[:7]
def rep(items, *keys):
    return [dict(zip(keys, it), _id=eid("r-" + it[1])) for it in items]
def container(key, widget, settings):
    return {"id": eid("c-" + key), "elType": "container", "isInner": False,
            "settings": {"content_width": "full", "flex_direction": "column",
                         "padding": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True},
                         "margin": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True}},
            "elements": [{"id": eid("w-" + key), "elType": "widget", "widgetType": widget, "settings": settings, "elements": []}]}

page = [
    container("dra-bleed", "di-bleed", {
        "eyebrow": "Tu Otorrino Especialista en Sueño",
        "title": "<b>Dra.</b> Irina<br>González <em>Sáez</em>",
        "lead": "Otorrinolaringólogo certificado en Monterrey, con subespecialización en desórdenes respiratorios del dormir, ronquido y rinología. Niños desde los 0 meses, adolescentes, adultos y adultos mayores.",
        "variant": "warm", "crumb": "Dra. Irina González Sáez", "image": {"id": "__DRA_HERO_ID__", "url": "__DRA_HERO_URL__"}, "focus": "50% 0%", "focus_mobile": "92% 8%", "secondary_short": "Doctoralia",
        "secondary_label": "Opiniones en Doctoralia", "secondary_url": "https://www.doctoralia.com.mx/perfil/irina-gonzalez-saez"}),
    container("dra-enfoque", "di-narrativa", {
        "eyebrow": "Enfoque", "title": "«<b>Escucharte</b> <br class=\"di-brd\">también es parte <br class=\"di-brd\">del <b><em>tratamiento</em></b>»",
        "lead": "La primera consulta dura alrededor de 30 minutos. Empieza por entender qué te pasa y desde cuándo; sigue con una exploración completa de oído, nariz y garganta, con endoscopia en el consultorio cuando hace falta, y termina con un plan explicado con claridad, sin promesas que no se puedan cumplir.",
        "text": "", "draft": "yes"}),
    container("dra-creds", "di-credenciales", {
        "eyebrow": "Formación y certificaciones", "title": "Credenciales <em>verificables</em>",
        "tags_title": "",  # vacío: membresías y hospitales van en la sección de logos (di-logos)
        "extra_tags": ""}),
    container("dra-logos", "di-logos", {
        "eyebrow": "", "title": "Membresías y <em>hospitales</em>",
        "items": rep([
            ("Christus Muguerza", "christus-muguerza", ""),
            ("Hospital Zambrano Hellion", "zambrano-hellion", ""),
            ("Hospital Ángeles Valle Oriente", "angeles-valle-oriente", ""),
            ("Hospital Universitario Dr. José Eleuterio González", "hospital-universitario", ""),
            ("Hospitaria", "hospitaria", ""),
            ("Consejo Mexicano de Otorrinolaringología y Cirugía de Cabeza y Cuello", "consejo-orl", ""),
            ("Federación Mexicana de Otorrinolaringología y Cirugía de Cabeza y Cuello (FESORMEX)", "fesormex", ""),
            ("Sociedad Iberoamericana de Cirugía de Sueño", "sociedad-iberoamericana-sueno", ""),
            ("Colegio de Otorrinolaringología de Nuevo León", "colegio-orl-nl", ""),
        ], "name", "slug", "url")}),
    container("dra-timeline", "di-trayectoria", {"eyebrow": "Trayectoria", "title": "<b>Formación</b> continua en <em>cirugía de sueño</em>"}),
    container("dra-cta", "di-cta", {"eyebrow": "Agenda", "title": "Agenda tu<br>primera <em>consulta</em>",
        "text": "30 minutos para escucharte, explorar y proponerte un plan.", "message": ""}),
]
# slug, título, tipo, institucion, lugar, anio, mostrar, orden. Fuente: DISCOVERY.md §Credenciales (CV, títulos, briefing confirmado).
creds = [
    ("medico-cirujano", "Médico Cirujano", "formacion", "Universidad Nacional Experimental Francisco de Miranda", "Santa Ana de Coro, Venezuela", "2010", 1, 40),
    ("especialista-orl", "Especialista en Otorrinolaringología", "especialidad", "Universidad Centroccidental Lisandro Alvarado", "Barquisimeto, Venezuela", "2018", 1, 10),
    ("subespecializacion-sueno", "Subespecialización en Desórdenes Respiratorios del Dormir, Ronquido y Rinología Aplicada", "especialidad", "ISSSTE, Clínica Hospital A Constitución", "Monterrey", "2018", 1, 20),
    ("consejo-orl", "Consejo Mexicano de Otorrinolaringología y Cirugía de Cabeza y Cuello, A.C.", "certificacion", "Certificación vigente a 2030", "", "2025", 1, 30),
    ("sociedad-iberoamericana-cirugia-sueno", "Sociedad Iberoamericana de Cirugía de Sueño", "membresia", "", "", "2020", 1, 50),
    ("colegio-orl-nuevo-leon", "Colegio de Otorrinolaringología de Nuevo León", "membresia", "", "", "", 1, 51),
    ("curso-faringoplastia-2024", "VI Curso Teórico Práctico de Faringoplastia Avanzada con Suturas Barbadas y Endoscopia de Sueño", "curso", "", "Monterrey", "2024", 1, 60),
    ("miembro-fundador-sics-2020", "Miembro fundador de la Sociedad Iberoamericana de Cirugía de Sueño", "experiencia", "", "Monterrey", "2020", 1, 61),
    ("publicacion-ijhns-2019", "«Selecting Different Approaches for Palate and Pharynx Surgery: Palatopharyngeal Arch Staging System»", "publicacion", "International Journal of Head and Neck Surgery, Vol. 10, n.º 4", "", "2019", 1, 62),
    ("curso-cirugia-medicina-sueno-2019", "Curso Internacional en Cirugía y Medicina de Sueño", "curso", "", "Monterrey", "2019", 1, 63),
    ("conferencia-cpap-2018", "«Manejo del CPAP»", "conferencia", "Curso Triológico", "Mazatlán", "2018", 1, 64),
    ("conferencia-faringoplastia-2017", "«Evolución de la faringoplastia en SAHOS, más allá de la UPPP»", "conferencia", "68.º Congreso Nacional de Otorrinolaringología", "Acapulco", "2017", 1, 65),
    ("hu-jose-eleuterio-gonzalez", "Médico adscrito del servicio de Otorrinolaringología, Servicios Médicos del Hospital Universitario «Dr. José Eleuterio González»", "experiencia", "", "Monterrey", "2025", 1, 59),
    ("trabajo-herniacion-duramadre-2016", "«Herniación de duramadre en oído medio»", "trabajo", "IX Triológico Venezolano de ORL", "", "2016", 1, 67),
    ("trabajo-carcinoma-nasofaringeo-2016", "«Presentación clásica de carcinoma nasofaríngeo: a propósito de un caso»", "trabajo", "IX Triológico Venezolano de ORL", "", "2016", 1, 68),
    ("trabajo-rinoescleroma-2016", "«Rinoescleroma: a propósito de un caso»", "trabajo", "IX Triológico Venezolano de ORL", "", "2016", 1, 69),
    ("docencia-pregrado", "Profesora de otorrinolaringología de pregrado en UNEFM, ULA y UNERG", "experiencia", "", "Venezuela", "", 1, 70),
]
here = pathlib.Path(__file__).parent
(here / "dra-elementor.json").write_text(json.dumps(page, ensure_ascii=False, indent=2), encoding="utf-8")
(here / "credenciales.json").write_text(json.dumps([dict(zip(["slug","titulo","tipo","institucion","lugar","anio","mostrar","orden"], c)) for c in creds], ensure_ascii=False, indent=1), encoding="utf-8")
print("ok", len(page), "bloques,", len(creds), "credenciales")
