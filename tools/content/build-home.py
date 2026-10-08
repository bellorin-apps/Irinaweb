#!/usr/bin/env python3
"""Genera home-elementor.json: contenido inicial del Home (sistema v2) como datos de Elementor con los widgets DI.
Se guarda con `wp eval` + wp_slash() (ver setup-site.sh), igual que hace Elementor."""
import json, hashlib, pathlib

def eid(s):
    return hashlib.md5(s.encode()).hexdigest()[:7]

def container(key, widget, settings):
    return {"id": eid("c-" + key), "elType": "container", "isInner": False,
            "settings": {"content_width": "full", "flex_direction": "column",
                         "padding": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True},
                         "margin": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True}},
            "elements": [{"id": eid("w-" + key), "elType": "widget", "widgetType": widget, "settings": settings, "elements": []}]}

def rep(items, *keys):
    return [dict({"_id": eid("r-" + str(i) + str(it))}, **dict(zip(keys, it))) for i, it in enumerate(items)]

data = [
    container("hero", "di-hero", {
        "eyebrow": "Otorrinolaringólogo en Monterrey",
        "title": "Respirar bien,<br>dormir bien,<br><em>oír bien.</em>",
        "lead": "Dra. Irina González Sáez, otorrinolaringólogo certificado con subespecialización en ronquido y apnea del sueño. Oído, nariz y garganta para niños y adultos, en Cumbres.",
        "trust": "Consejo Mexicano de ORL y CCC\nSubespecialidad en desórdenes respiratorios del sueño\nCAB Medical · Cumbres, Monterrey",
        "secondary_label": "Conocer a la Dra. Irina", "secondary_url": "/dra-irina-gonzalez-saez/",
        "ticker": "Ronquido y apnea\nNariz tapada\nSinusitis\nOído tapado\nHipoacusia y tinnitus\nVértigo\nAmígdalas y adenoides\nVoz y reflujo"}),
    container("areas", "di-areas", {
        "eyebrow": "Áreas de atención", "title": "Dos especialidades,<br>una misma forma de <em>atender.</em>",
        "orl_eyebrow": "Otorrinolaringología", "orl_title": "Oído, nariz y garganta, desde los 0 meses",
        "orl_items": "Otitis\nHipoacusia\nTinnitus\nVértigo\nRinitis\nSinusitis\nTabique desviado\nAmígdalas\nVoz",
        "orl_link": "/otorrinolaringologia/", "orl_link_label": "Padecimientos y tratamientos",
        "sleep_eyebrow": "Sueño y respiración", "sleep_title": "Ronquido y apnea del sueño, con diagnóstico y tratamiento completos",
        "sleep_items": "Poligrafía en casa\nEndoscopia de sueño\nCPAP\nFaringoplastia\nCirugía nasal",
        "sleep_link": "/sueno/", "sleep_link_label": "Conocer la ruta de tratamiento"}),
    container("motivos", "di-motivos", {
        "eyebrow": "¿Qué estás sintiendo?", "title": "Motivos de consulta <em>frecuentes.</em>",
        "note": "Orientación para encontrar la información adecuada. No sustituye una valoración médica.",
        "items": rep([
            ("Ronco o me dicen que dejo de respirar", "Ronquido, apnea del sueño", "/sueno/ronquido/"),
            ("Nariz tapada todo el tiempo", "Obstrucción nasal, tabique, cornetes", "/padecimientos/desviacion-de-tabique-nasal/"),
            ("Sinusitis que no se quita", "Sinusitis aguda y crónica", "/padecimientos/sinusitis/"),
            ("Dolor u oído tapado", "Otitis, tapón de cerumen", "/padecimientos/otitis/"),
            ("Escucho menos o me zumba el oído", "Hipoacusia, tinnitus", "/padecimientos/hipoacusia/"),
            ("Estornudos y alergia", "Rinitis alérgica", "/padecimientos/rinitis/"),
            ("Dolor de garganta frecuente", "Amigdalitis, amígdalas en niños", "/padecimientos/amigdalitis/"),
            ("Ronquera o cambios en la voz", "Laringe, reflujo laringofaríngeo", "/padecimientos/reflujo-laringofaringeo/"),
        ], "label", "sub", "url")}),
    container("doctora", "di-doctora", {
        "eyebrow": "Dra. Irina González Sáez", "quote": "«Escucharte también es parte del tratamiento.»",
        "text": "Médico cirujano por la Universidad Nacional Experimental Francisco de Miranda y especialista en Otorrinolaringología por la Universidad Centroccidental Lisandro Alvarado. Subespecialización en desórdenes respiratorios del dormir, ronquido y rinología aplicada en el ISSSTE de Monterrey.",
        "creds": "Certificada por el Consejo Mexicano de Otorrinolaringología y Cirugía de Cabeza y Cuello\nSociedad Iberoamericana de Cirugía de Sueño · Colegio de ORL de Nuevo León\nCirugía en Christus Muguerza, Zambrano Hellion y Ángeles Valle Oriente",
        "link": "/dra-irina-gonzalez-saez/", "link_label": "Conocer a la Dra. Irina"}),
    container("pasos", "di-pasos", {
        "eyebrow": "Cómo trabajamos", "title": "Qué esperar en <em>tu consulta.</em>",
        "steps": rep([
            ("Escuchar", "Tus síntomas, desde cuándo y cómo afectan tu día. Trae estudios previos y tu lista de medicamentos."),
            ("Explorar", "Oído, nariz y garganta; endoscopia nasal o laringoscopia en el consultorio cuando hace falta."),
            ("Diagnosticar", "Si el problema es de sueño, poligrafía o estudio en casa realizado por la propia Dra."),
            ("Acompañar", "Un plan claro, con opciones médicas y quirúrgicas explicadas, y seguimiento presencial o en línea."),
        ], "title", "text")}),
    container("sueno", "di-sueno", {
        "eyebrow": "Especialización en sueño", "title": "Del ronquido al descanso, <em>en un solo lugar.</em>",
        "lead": "En Monterrey, el ronquido y la apnea suelen pasar por varios especialistas. La Dra. Irina evalúa la vía aérea, realiza el estudio, interpreta el resultado y ofrece desde el CPAP hasta la cirugía de paladar y nariz.",
        "link": "/sueno/", "link_label": "Explorar la ruta de tratamiento",
        "steps": "Valoración de la vía aérea y endoscopia de sueño\nPoligrafía respiratoria o estudio en casa, interpretado por la Dra.\nTratamiento: CPAP con seguimiento, terapia posicional, cirugía de paladar o nasal\nControl y ajuste hasta dormir bien"}),
    container("ubicacion", "di-ubicacion", {
        "eyebrow": "Consultorio", "title": "",
        "facts": rep([("Estacionamiento", "Gratuito"), ("Acceso", "Elevador y acceso para silla de ruedas")], "label", "value"),
        "map_embed": "https://www.google.com/maps?q=25.7117869,-100.3765032&z=17&output=embed"}),
    container("cta", "di-cta", {
        "eyebrow": "Agenda", "title": "¿Hablamos de lo que te está quitando el descanso?",
        "text": "Escríbenos por WhatsApp y te ayudamos a elegir el tipo de consulta.", "message": ""}),
]
out = pathlib.Path(__file__).with_name("home-elementor.json")
s = json.dumps(data, ensure_ascii=False, separators=(",", ":"))
out.write_text(s, encoding="utf-8")
print(out, len(s), "bytes")
