#!/usr/bin/env python3
"""Páginas base (Elementor con widgets DI), legales (HTML) y metadatos SEO. Solo datos confirmados; lo demás va marcado [PENDIENTE DE CONFIRMACIÓN].
Los textos clínicos se marcan como borrador pendiente de la Dra. Se cargan con tools/deploy/setup-pages.sh sin cambiar el estado de las páginas."""
import json, hashlib, pathlib

def eid(s): return hashlib.md5(s.encode()).hexdigest()[:7]
def container(key, widget, settings):
    return {"id": eid("c-" + key), "elType": "container", "isInner": False,
            "settings": {"content_width": "full", "flex_direction": "column",
                         "padding": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True},
                         "margin": {"unit": "px", "top": "0", "right": "0", "bottom": "0", "left": "0", "isLinked": True}},
            "elements": [{"id": eid("w-" + key), "elType": "widget", "widgetType": widget, "settings": settings, "elements": []}]}
def rep(items, *keys):
    return [dict({"_id": eid("r-" + str(i) + str(it))}, **dict(zip(keys, it))) for i, it in enumerate(items)]
PEND = "[PENDIENTE DE CONFIRMACIÓN]"

pages = {}

# D-076 (propietario, 2026-10-13): sitio corto, punto de encuentro con la Dra. Otorrinolaringología y Sueño son dos páginas cortas
# (una pantalla o poco más): qué atiende (nombres con una línea simple), cómo trabaja la Dra. y el CTA. Sin enlaces a fichas, sin
# explicaciones clínicas extensas ni afirmaciones de eficacia. Las 26 fichas quedan archivadas en borrador. La Dra. revisa solo estas
# dos páginas en /briefing/guia/ (se exportan a tools/briefing/guia/paginas.json al final de este script).
pages["otorrinolaringologia"] = [
    container("orl-bleed", "di-bleed", {"eyebrow": "Otorrinolaringología", "title": "Oído, <b>nariz y garganta,</b><br><em>desde los 0 meses</em>",
        "lead": "Consulta de oído, nariz y garganta para niños, adolescentes, adultos y adultos mayores en Monterrey.",
        "variant": "sand", "crumb": "Otorrinolaringología"}),
    container("orl-oido", "di-listado", {"eyebrow": "Oído", "title": "<b>Lo que</b> atiendo <em>del oído</em>", "post_type": "", "anchor": "oido",
        "items": rep([("Otitis", "Infección o inflamación del oído", ""), ("Tapón de cerumen", "Oído tapado por cerilla", ""), ("Hipoacusia", "Pérdida de audición", ""), ("Tinnitus", "Zumbido o pitido en el oído", ""), ("Vértigo", "Mareo o sensación de giro", "")], "label", "sub", "url")}),
    container("orl-nariz", "di-listado", {"eyebrow": "Nariz", "title": "<b>Lo que</b> atiendo <em>de la nariz</em>", "post_type": "", "anchor": "nariz", "tight": "yes",
        "items": rep([("Rinitis", "Nariz tapada, estornudos o escurrimiento frecuentes", ""), ("Sinusitis", "Inflamación de los senos paranasales", ""), ("Desviación del tabique nasal", "Dificultad para respirar por la nariz", ""), ("Pólipos nasales", "Crecimientos dentro de la nariz", ""), ("Sangrado nasal", "Epistaxis", "")], "label", "sub", "url")}),
    container("orl-garganta", "di-listado", {"eyebrow": "Garganta y voz", "title": "<b>Lo que</b> atiendo <em>de la garganta</em>", "post_type": "", "anchor": "garganta", "tight": "yes",
        "items": rep([("Amigdalitis", "Dolor de garganta frecuente", ""), ("Ronquera y cambios en la voz", "Incluye reflujo laringofaríngeo", "")], "label", "sub", "url")}),
    container("orl-proc", "di-listado", {"eyebrow": "Procedimientos", "title": "<b>Consultorio</b> y <em>cirugía</em>", "post_type": "", "anchor": "procedimientos", "tight": "yes",
        "note": "Toda cirugía se indica después de una valoración, en la que se explican opciones y alternativas.",
        "items": rep([("Endoscopia nasal y laringoscopia", "En el consultorio, cuando hace falta", ""), ("Septoplastia", "Cirugía del tabique nasal", ""), ("Turbinoplastia", "Cirugía de los cornetes", ""), ("Amígdalas y adenoides", "Cirugía en niños y adultos", ""), ("Cirugía endoscópica nasal", "Cirugía de senos paranasales", ""), ("Rinoplastia funcional", "Cirugía de la nariz para respirar", "")], "label", "sub", "url")}),
    container("orl-como", "di-narrativa", {"eyebrow": "Cómo trabajo", "title": "<b>Primero</b> escucharte, luego <em>explorar</em>",
        "lead": "La primera consulta dura alrededor de 30 minutos. Empieza por entender qué te pasa y desde cuándo; sigue con una exploración completa de oído, nariz y garganta, con endoscopia en el consultorio cuando hace falta, y termina con un plan explicado con claridad. Si tu caso necesita a otro especialista, te lo digo y te refiero.",
        "text": ""}),
    container("orl-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿Un <b>síntoma</b> que <em>no se va</em>?", "text": "Escríbenos por WhatsApp y te ayudamos a elegir el tipo de consulta.", "message": "Hola, quisiera agendar una consulta de otorrinolaringología con la Dra. Irina González Sáez."}),
]

pages["sueno"] = [
    container("su-bleed", "di-bleed", {"eyebrow": "Sueño y respiración", "title": "Del <b>ronquido</b><br><em>al descanso</em>",
        "lead": "Otorrinolaringólogo con subespecialización en desórdenes respiratorios del dormir, ronquido y rinología.",
        "variant": "night", "crumb": "Sueño", "whatsapp_label": "Agendar valoración de ronquido y apnea"}),
    # D-082: las 5 fichas de sueño enlazan desde aquí; el bloque solo pinta el enlace si la ficha está publicada (irina_url_if_published).
    container("su-cond", "di-listado", {"eyebrow": "Qué atiendo", "title": "<b>Ronquido</b> y <em>apnea</em>", "post_type": "", "anchor": "atiendo",
        "items": rep([("Ronquido", "En adultos y en niños", "/padecimientos/ronquido/"), ("Apnea obstructiva del sueño", "Pausas en la respiración al dormir", "/padecimientos/apnea-obstructiva-del-sueno/")], "label", "sub", "url")}),
    container("su-ruta", "di-listado", {"eyebrow": "Estudios y tratamientos", "title": "<b>La</b> ruta <em>completa</em>", "post_type": "", "anchor": "ruta", "tight": "yes",
        "note": "El tratamiento se decide con el estudio en la mano y según dónde se estrecha la vía aérea.",
        "items": rep([("Estudio del sueño en casa", "Poligrafía respiratoria interpretada por la propia Dra.", "/tratamientos/estudio-del-sueno/"), ("Endoscopia de sueño", "Muestra dónde se cierra la vía aérea al dormir", ""), ("CPAP", "Indicación, ajuste y seguimiento", "/tratamientos/cpap/"), ("Cirugía de ronquido y apnea", "Nariz, paladar o amígdalas, según el caso", "/tratamientos/cirugia-de-ronquido-y-apnea/"), ("Dispositivo de avance mandibular", "Con una odontóloga especializada en sueño", "")], "label", "sub", "url")}),
    container("su-como", "di-narrativa", {"eyebrow": "Cómo trabajo", "title": "<b>Un</b> otorrino para la <em>vía aérea</em>",
        "lead": "Nariz, paladar, amígdalas y lengua: como otorrinolaringólogo exploro directamente dónde se estrecha la vía aérea durante el sueño. La valoración empieza en el consultorio; con el estudio en la mano te explico las opciones y decidimos juntos el tratamiento.",
        "text": ""}),
    container("su-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿Hablamos de lo que te <b>quita</b> el <em>descanso</em>?", "text": "Una valoración de 30 minutos define si necesitas un estudio.", "message": "Hola, quisiera agendar una valoración de ronquido o apnea con la Dra. Irina González Sáez."}),
]

pages["primera-consulta"] = [
    container("pc-bleed", "di-bleed", {"eyebrow": "Primera consulta", "title": "<b>Qué</b> esperar<br>en tu <em>consulta</em>",
        "lead": "Alrededor de 30 minutos para escucharte, explorar y proponerte un plan. Niños desde los 0 meses, adolescentes, adultos y adultos mayores.",
        # Foto de cabecera como la de «Conóceme» (propietario, 2026-10-12): adjunto hero-primera (hero-irina3.png), sustituido por setup-pages.sh.
        "variant": "warm", "crumb": "Primera consulta", "image": {"id": "__PC_HERO_ID__", "url": "__PC_HERO_URL__"}, "focus": "50% 6%", "focus_mobile": "85% 20%"}),
    # Ilustración encima de cada paso (propietario, 2026-10-12/13): adjuntos paso-* (Recursos (Irina)/800w/img-*.png).
    # Textos equilibrados (propietario, 2026-10-13): los cuatro ocupan 2 líneas a 1440 px y 3 a 1280 px.
    container("pc-pasos", "di-pasos", {"eyebrow": "Paso a paso", "title": "<b>Así</b> es la <em>consulta</em>", "steps": rep([
        ("Escuchar", "Tus síntomas, desde cuándo empezaron y cómo afectan tu día y tu descanso.", "paso-escuchar"),
        ("Explorar", "Oído, nariz y garganta, con endoscopia nasal o laringoscopia cuando hace falta.", "paso-explorar"),
        ("Diagnosticar", "Si el problema es de sueño, un estudio en casa interpretado por la propia Dra.", "paso-diagnosticar"),
        ("Acompañar", "Un plan claro con opciones médicas y quirúrgicas, y un seguimiento cercano.", "paso-acompanar"),
    ], "title", "text", "img")}),
    container("pc-narr", "di-narrativa", {"eyebrow": "Qué llevar", "title": "<b>Llega</b> con lo que <em>ya tienes</em>",
        "bg_image": {"id": "__PC_BG_ID__", "url": "__PC_BG_URL__"}, "bg_pos": "top",  # foto de fondo (propietario, 2026-10-13)
        # «Qué llevar» aprobado (propietario, 2026-10-12, captura del bloque): se retira la marca de borrador (D-058, Q-043).
        "text": "<ul><li>Identificación.</li><li>Estudios previos (audiometría, tomografía, estudio del sueño) si los tienes.</li><li>Lista de medicamentos que tomas.</li><li>Referencia médica, si la tienes.</li><li>Si vienes por ronquido o apnea: lo que tu pareja o familia ha notado al dormir.</li><li>Para niños: cartilla y antecedentes de infecciones de oído o garganta.</li></ul>", "draft": ""}),
    container("pc-faq", "di-faq", {"eyebrow": "Preguntas frecuentes", "title": "<b>Costo,</b> pagos y <em>seguros</em>",
        "bg_image": {"id": "__PC_BG_ID__", "url": "__PC_BG_URL__"}, "bg_pos": "bottom", "items": rep([
        ("¿Cuánto cuesta la consulta?", "Te confirmamos el costo al agendar por WhatsApp. Cirugías y estudios se cotizan después de la valoración."),
        ("¿Aceptan seguros de gastos médicos?", "Atención a pacientes particulares y reembolso de seguros de gastos médicos; la Dra. entrega la documentación para tu aseguradora."),
        ("¿Cómo puedo pagar?", "Efectivo, tarjeta y transferencia. Facturación disponible."),
        ("¿Hay consulta en línea?", "Solo para seguimiento de pacientes ya valorados en consultorio."),
        ("¿Atienden niños?", "Sí, desde los 0 meses."),
        ("¿Dónde está el consultorio y hay estacionamiento?", "CAB Medical Headquarters, Av. Paseo de los Leones 2341, Consultorio 6, Piso 2, Cumbres 2.º Sector, Monterrey. Estacionamiento gratuito; elevador y acceso para silla de ruedas."),
    ], "pregunta", "text")}),
    container("pc-cta", "di-cta", {"eyebrow": "Agenda", "title": "Agenda tu<br><em>primera consulta</em>", "text": "Escríbenos por WhatsApp y te confirmamos horario.", "message": ""}),
]

pages["contacto"] = [
    container("co-bleed", "di-bleed", {"eyebrow": "Contacto", "title": "<b>Consultorio</b> en<br>Cumbres, <em>Monterrey</em>",
        "lead": "CAB Medical Headquarters, Av. Paseo de los Leones 2341, Consultorio 6, Piso 2. Consulta previa cita: agenda por WhatsApp.",
        # Foto de cabecera como «Conóceme» y Primera consulta (propietario, 2026-10-13): adjunto hero-contacto, sustituido por setup-pages.sh.
        "variant": "warm", "crumb": "Contacto", "image": {"id": "__CO_HERO_ID__", "url": "__CO_HERO_URL__"}, "focus": "50% 6%", "focus_mobile": "85% 20%"}),
    container("co-ubic", "di-ubicacion", {"eyebrow": "Cómo llegar", "title": "<b>CAB</b> Medical,<br><em>Headquarters</em>",
        "facts": rep([("Estacionamiento", "Gratuito"), ("Acceso", "Elevador y acceso para silla de ruedas")], "label", "value"),
        "map_embed": "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3594.777590213581!2d-100.3765032!3d25.7117869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x866297c19d8eb1cb%3A0x5c3e8ac4d16be247!2sDra%20Irina%20Gonz%C3%A1lez%20S%C3%A1ez!5e0!3m2!1ses!2smx!4v1791433463370!5m2!1ses!2smx"}),
    container("co-form", "di-form", {"eyebrow": "Escríbenos", "title": "¿<b>Prefieres</b> que<br>te <em>llamemos</em>?",
        "lead": "Déjanos tu nombre y teléfono y te contactamos en horario de consultorio. Para agendar más rápido, WhatsApp.", "button": "Enviar solicitud"}),
    container("co-narr", "di-narrativa", {"eyebrow": "Urgencias", "title": "<b>Este sitio</b> no es un<br>canal de <em>urgencias</em>",
        "lead": "Si tienes dificultad para respirar, sangrado nasal que no se detiene o un dolor intenso, acude al servicio de urgencias más cercano. Para citas y dudas, escríbenos por WhatsApp.", "text": ""}),
    container("co-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿<em>Agendamos</em>?", "text": "WhatsApp es el canal más rápido para citas.", "message": ""}),
]

pages["gracias"] = [
    container("gr-bleed", "di-bleed", {"eyebrow": "Mensaje enviado", "title": "<b>Recibido</b>, <em>gracias</em>",
        "lead": "Te contactamos en horario de consultorio por teléfono o WhatsApp. Si es urgente, acude al servicio de urgencias más cercano.",
        "variant": "sand", "crumb": "Gracias"}),
    container("gr-narr", "di-narrativa", {"eyebrow": "Mientras tanto", "title": "Lo que <b>puedes</b> ir <em>preparando</em>",
        "lead": "Estudios previos, lista de medicamentos y, si es por ronquido o apnea, lo que te ha contado quien duerme contigo.", "text": ""}),
    container("gr-cta", "di-cta", {"eyebrow": "¿Prisa?", "title": "Si prefieres, <em>escríbenos ahora</em>", "text": "WhatsApp es el canal más rápido para agendar.", "message": ""}),
]

pages["preguntas-frecuentes"] = [
    container("faq-bleed", "di-bleed", {"eyebrow": "Preguntas frecuentes", "title": "Lo que <b>más</b> <em>nos preguntan</em>",
        "lead": "Horario, costos, seguros, niños y qué pasa en la consulta. Las dudas médicas se responden en cada página de padecimiento.", "variant": "sand", "crumb": "Preguntas frecuentes"}),
    container("faq-serv", "di-faq", {"eyebrow": "Servicio", "title": "<b>Consulta</b> y <em>agenda</em>", "items": rep([
        ("¿Cómo agendo?", "Por WhatsApp, en un toque desde cualquier página del sitio, o por teléfono. La consulta es previa cita."),
        ("¿Cuál es el horario?", "Previa cita: agenda por WhatsApp y te confirmamos el horario disponible."),
        ("¿Cuánto dura la consulta?", "Alrededor de 30 minutos."),
        ("¿Cuánto cuesta?", "Te confirmamos el costo al agendar por WhatsApp. Cirugías y estudios, previa valoración."),
        ("¿Aceptan seguros?", "Particulares y reembolso de seguros de gastos médicos."),
        ("¿Cómo puedo pagar?", "Efectivo, tarjeta y transferencia. Facturación disponible."),
        ("¿Atienden en línea?", "Solo seguimiento de pacientes ya valorados en consultorio."),
        ("¿Atienden niños?", "Sí, desde los 0 meses."),
        ("¿En qué hospitales opera la Dra.?", "Christus Muguerza, Hospital Zambrano Hellion y Hospital Ángeles Valle Oriente."),
    ], "pregunta", "text")}),
    container("faq-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿Otra duda?", "text": "Escríbenos por WhatsApp.", "message": ""}),
]

pages["links"] = [container("links", "di-enlaces", {"title": "", "subtitle": "", "extra": []})]

# Textos legales (PLAN 8.5, D-051): fuente en tools/content/legal/<slug>.html; los shortcodes [di_responsable], [di_address], [di_arco],
# [di_email], [di_phone] se resuelven en WordPress con los datos del consultorio (fuente única de verdad).
legal = {}
for _f in sorted((pathlib.Path(__file__).parent / "legal").glob("*.html")):
    legal[_f.stem] = _f.read_text(encoding="utf-8")

seo = {
    "inicio-v2": ("Otorrinolaringólogo en Monterrey | Dra. Irina González Sáez", "Otorrinolaringólogo certificado en Monterrey, especialista en ronquido y apnea del sueño. Oído, nariz y garganta para niños y adultos en Cumbres. Agenda por WhatsApp."),
    "dra-irina-gonzalez-saez": ("Dra. Irina González Sáez, otorrinolaringólogo especialista en sueño", "Formación en dos países, certificación del Consejo Mexicano de ORL y CCC y subespecialización en desórdenes respiratorios del dormir. Consultorio en Cumbres, Monterrey."),
    "otorrinolaringologia": ("Otorrinolaringología en Monterrey: oído, nariz y garganta", "Padecimientos y tratamientos de oído, nariz y garganta en niños y adultos. Cuándo acudir al otorrino y qué esperar. Dra. Irina González Sáez, Cumbres."),
    "sueno": ("Especialista en ronquido y apnea del sueño en Monterrey", "Otorrinolaringólogo especialista en desórdenes respiratorios del dormir: estudio del sueño, CPAP y cirugía de ronquido y apnea. Dra. Irina González Sáez."),
    "primera-consulta": ("Primera consulta con la Dra. Irina González Sáez | Qué esperar", "Cómo es la consulta de otorrinolaringología: 30 minutos, exploración completa y plan claro. Qué llevar, costos y seguros."),
    "contacto": ("Contacto y ubicación | Dra. Irina González Sáez, Cumbres", "Consultorio en CAB Medical Headquarters, Av. Paseo de los Leones 2341, Cumbres 2.º Sector, Monterrey. Horario, mapa, estacionamiento y WhatsApp."),
    "preguntas-frecuentes": ("Preguntas frecuentes | Dra. Irina González Sáez", "Horario, costo de consulta, seguros, atención a niños y hospitales. Respuestas de servicio del consultorio de la Dra. Irina González Sáez."),
    "aviso-de-privacidad": ("Aviso de privacidad | Dra. Irina González Sáez", "Cómo tratamos sus datos personales conforme a la LFPDPPP."),
    "aviso-medico": ("Aviso médico | Dra. Irina González Sáez", "La información del sitio es educativa y no sustituye la consulta médica. No es un canal de urgencias."),
    "terminos-de-uso": ("Términos de uso | Dra. Irina González Sáez", "Condiciones de uso del sitio web de la Dra. Irina González Sáez."),
    "gracias": ("Mensaje recibido | Dra. Irina González Sáez", "Recibimos tu solicitud; te contactamos en horario de consultorio."),
}

out = pathlib.Path(__file__).parent / "pages"
out.mkdir(exist_ok=True)
for slug, data in pages.items():
    (out / f"{slug}.elementor.json").write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
for slug, html in legal.items():
    (out / f"{slug}.html").write_text(html.strip() + "\n", encoding="utf-8")
(out / "seo.json").write_text(json.dumps({k: {"title": v[0], "description": v[1]} for k, v in seo.items()}, ensure_ascii=False, indent=1), encoding="utf-8")
print("ok", len(pages), "elementor,", len(legal), "legales,", len(seo), "seo")

# D-076: lo que la Dra. revisa en /briefing/guia/ (texto plano de las dos páginas cortas, en el orden en que se ven).
import re as _re
def _plain(h): return _re.sub(r"<br[^>]*>", " ", _re.sub(r"<(?!br)[^>]+>", "", h or "")).strip()
_review = {}
for _slug, _name in (("otorrinolaringologia", "Otorrinolaringología"), ("sueno", "Sueño")):
    _blocks = []
    for _c in pages[_slug]:
        _w = _c["elements"][0]; _st = _w["settings"]
        _b = {"tipo": _w["widgetType"], "eyebrow": _st.get("eyebrow", ""), "titulo": _plain(_st.get("title", "")),
              "texto": _st.get("lead") or _st.get("text") or "", "nota": _st.get("note", ""),
              "items": [[it.get("label", ""), it.get("sub", "")] for it in _st.get("items", []) if isinstance(it, dict)]}
        _blocks.append(_b)
    _review[_slug] = {"nombre": _name, "url": "/" + _slug + "/", "bloques": _blocks}
pathlib.Path(__file__).resolve().parents[1].joinpath("briefing", "guia", "paginas.json").write_text(json.dumps(_review, ensure_ascii=False, indent=1), encoding="utf-8")
