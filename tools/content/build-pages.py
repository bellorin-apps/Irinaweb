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

pages["otorrinolaringologia"] = [
    container("orl-bleed", "di-bleed", {"eyebrow": "Otorrinolaringología", "title": "Oído, nariz y garganta,<br><em>desde los 0 meses.</em>",
        "lead": "Diagnóstico y tratamiento de los padecimientos de oído, nariz y garganta en niños y adultos: con exploración completa en consultorio, endoscopia cuando hace falta y un plan explicado con claridad.",
        "variant": "sand", "crumb": "Otorrinolaringología"}),
    container("orl-narr", "di-narrativa", {"eyebrow": "¿Cuándo acudir al otorrino?", "title": "Si un síntoma de oído, nariz o garganta <em>no se va.</em>",
        "lead": "Dolor u oído tapado que dura más de unos días, nariz tapada todo el tiempo, sinusitis que se repite, ronquera que no mejora, dolor de garganta frecuente, mareo o zumbido: son motivos para una valoración especializada en lugar de seguir con remedios caseros.",
        "text": "", "draft": "yes"}),
    container("orl-oido", "di-listado", {"eyebrow": "Oído", "title": "Padecimientos <em>del oído.</em>", "post_type": "condicion", "area": "orl", "zona": "oido",
        "items": rep([("Otitis", "Dolor e infección de oído", "/padecimientos/otitis/"), ("Tapón de cerumen", "Oído tapado por cera; limpieza en consultorio", "/padecimientos/tapon-de-cerumen/"), ("Hipoacusia", "Pérdida de audición", "/padecimientos/hipoacusia/"), ("Tinnitus", "Zumbido o pitido en el oído", "/padecimientos/tinnitus/"), ("Vértigo", "Mareo de origen en el oído", "/padecimientos/vertigo/")], "label", "sub", "url")}),
    container("orl-nariz", "di-listado", {"eyebrow": "Nariz", "title": "Padecimientos <em>de la nariz.</em>", "post_type": "condicion", "area": "orl", "zona": "nariz", "tight": "yes",
        "items": rep([("Rinitis", "Nariz tapada, estornudos y alergia", "/padecimientos/rinitis/"), ("Sinusitis", "Aguda y crónica", "/padecimientos/sinusitis/"), ("Desviación del tabique nasal", "Obstrucción de un lado o de ambos", "/padecimientos/desviacion-de-tabique-nasal/"), ("Pólipos nasales", "Obstrucción y pérdida de olfato", "/padecimientos/polipos-nasales/"), ("Sangrado nasal (epistaxis)", "Causas y cuándo acudir", "/padecimientos/epistaxis/")], "label", "sub", "url")}),
    container("orl-garganta", "di-listado", {"eyebrow": "Garganta y voz", "title": "Padecimientos <em>de la garganta.</em>", "post_type": "condicion", "area": "orl", "zona": "garganta", "tight": "yes",
        "items": rep([("Amigdalitis", "Dolor de garganta frecuente; amígdalas en niños", "/padecimientos/amigdalitis/"), ("Reflujo laringofaríngeo", "Carraspera, ronquera y sensación de algo en la garganta", "/padecimientos/reflujo-laringofaringeo/")], "label", "sub", "url")}),
    container("orl-trat", "di-listado", {"eyebrow": "Tratamientos", "title": "Procedimientos y <em>cirugías.</em>", "post_type": "tratamiento", "area": "orl",
        "note": "Toda cirugía se indica tras una valoración; en la consulta se explican opciones, recuperación y alternativas.",
        "items": rep([("Septoplastia", "Cirugía del tabique nasal", "/tratamientos/septoplastia/"), ("Turbinoplastia", "Reducción de cornetes", "/tratamientos/turbinoplastia/"), ("Amígdalas y adenoides", "Amigdalectomía y adenoidectomía", "/tratamientos/amigdalas-y-adenoides/"), ("Rinoplastia funcional y estética", "Respirar y verse bien: valoración con equipo quirúrgico", "/tratamientos/rinoplastia/")], "label", "sub", "url")}),
    container("orl-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿Un síntoma que no se va?", "text": "Escríbenos por WhatsApp y te ayudamos a elegir el tipo de consulta.", "message": "Hola, quisiera agendar una consulta de otorrinolaringología con la Dra. Irina González Sáez."}),
]

pages["sueno"] = [
    container("su-bleed", "di-bleed", {"eyebrow": "Sueño y respiración", "title": "Del ronquido<br><em>al descanso.</em>",
        "lead": "Otorrinolaringólogo con subespecialización en desórdenes respiratorios del dormir: valoración de la vía aérea, estudio del sueño interpretado por la propia Dra. y una ruta de tratamiento completa, desde el CPAP hasta la cirugía de paladar y nariz.",
        "variant": "night", "crumb": "Sueño", "whatsapp_label": "Agendar valoración de ronquido y apnea"}),
    container("su-narr", "di-narrativa", {"eyebrow": "Por qué un otorrino", "title": "El ronquido y la apnea empiezan en la <em>vía aérea.</em>",
        "lead": "Nariz, paladar, amígdalas y lengua: el otorrinolaringólogo es el especialista que explora directamente dónde se estrecha la vía aérea durante el sueño y puede tratar esa causa, de forma médica o quirúrgica. En Monterrey muchas personas pasan por varios especialistas antes de llegar a esta valoración.",
        "text": "", "draft": "yes"}),
    container("su-cond", "di-listado", {"eyebrow": "Padecimientos", "title": "Lo que <em>tratamos.</em>", "post_type": "condicion", "area": "sueno",
        "items": rep([("Ronquido", "Causas, cuándo preocuparse y tratamiento", "/sueno/ronquido/"), ("Apnea obstructiva del sueño", "Síntomas, diagnóstico y opciones", "/sueno/apnea-obstructiva-del-sueno/")], "label", "sub", "url")}),
    container("su-trat", "di-listado", {"eyebrow": "Diagnóstico y tratamientos", "title": "La <em>ruta.</em>", "post_type": "tratamiento", "area": "sueno", "tight": "yes",
        "note": "El tratamiento depende de dónde está la obstrucción y de su gravedad; se decide con el estudio en la mano.",
        "items": rep([("Estudio del sueño", "Poligrafía respiratoria en casa, interpretada por la Dra.", "/sueno/estudio-del-sueno/"), ("CPAP", "Indicación, titulación y seguimiento", "/sueno/cpap/"), ("Cirugía de ronquido y apnea", "Faringoplastia con suturas barbadas y cirugía nasal", "/sueno/cirugia-de-ronquido-y-apnea/"), ("Dispositivo de avance mandibular", "Se indica y se coordina con odontología", "/sueno/dispositivo-de-avance-mandibular/")], "label", "sub", "url")}),
    container("su-faq", "di-faq", {"eyebrow": "Dudas frecuentes", "title": "Antes de <em>consultar.</em>", "items": rep([
        ("¿Necesito ir a una clínica del sueño?", "La valoración y el estudio en casa se coordinan desde el consultorio; la Dra. realiza e interpreta el estudio. Texto pendiente de revisión de la Dra."),
        ("¿Apnea del sueño: otorrino o neumólogo?", "Cuando la causa está en la vía aérea superior (nariz, paladar, amígdalas), el otorrinolaringólogo puede diagnosticar y tratar la obstrucción. Texto pendiente de revisión de la Dra."),
        ("¿Hay tratamiento sin CPAP?", "Según el caso: cirugía de paladar o nasal, terapia posicional, dispositivo de avance mandibular. Se decide con el estudio del sueño. Texto pendiente de revisión de la Dra."),
    ], "pregunta", "text")}),
    container("su-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿Hablamos de lo que te está quitando el descanso?", "text": "Una valoración de 30 minutos define si necesitas un estudio.", "message": "Hola, quisiera agendar una valoración de ronquido o apnea con la Dra. Irina González Sáez."}),
]

pages["primera-consulta"] = [
    container("pc-bleed", "di-bleed", {"eyebrow": "Primera consulta", "title": "Qué esperar<br><em>en tu consulta.</em>",
        "lead": "Alrededor de 30 minutos para escucharte, explorar y proponerte un plan. Niños desde los 0 meses, adolescentes, adultos y adultos mayores.",
        "variant": "warm", "crumb": "Primera consulta"}),
    container("pc-pasos", "di-pasos", {"eyebrow": "Paso a paso", "title": "Así es <em>la consulta.</em>", "steps": rep([
        ("Escuchar", "Tus síntomas, desde cuándo y cómo afectan tu día."),
        ("Explorar", "Oído, nariz y garganta; endoscopia nasal o laringoscopia en el consultorio cuando hace falta."),
        ("Diagnosticar", "Si el problema es de sueño, estudio en casa realizado e interpretado por la propia Dra."),
        ("Acompañar", "Plan claro con opciones médicas y quirúrgicas, y seguimiento."),
    ], "title", "text")}),
    container("pc-narr", "di-narrativa", {"eyebrow": "Qué llevar", "title": "Llega con lo que <em>ya tienes.</em>",
        "text": "<ul><li>Estudios previos (audiometría, tomografía, estudio del sueño) si los tienes.</li><li>Lista de medicamentos que tomas.</li><li>Si vienes por ronquido o apnea: lo que tu pareja o familia ha notado al dormir.</li><li>Para niños: cartilla y antecedentes de infecciones de oído o garganta.</li></ul>", "draft": "yes"}),
    container("pc-faq", "di-faq", {"eyebrow": "Preguntas frecuentes", "title": "Costo, pagos y <em>seguros.</em>", "items": rep([
        ("¿Cuánto cuesta la consulta?", "Costo de la consulta: " + PEND + ". Cirugías y estudios se cotizan después de la valoración."),
        ("¿Aceptan seguros de gastos médicos?", "Atención a pacientes particulares y reembolso de seguros de gastos médicos; la Dra. entrega la documentación para tu aseguradora."),
        ("¿Hay consulta en línea?", PEND + ": seguimiento a distancia según el caso."),
        ("¿Atienden niños?", "Sí, desde los 0 meses."),
        ("¿Dónde está el consultorio y hay estacionamiento?", "CAB Medical, Av. Paseo de los Leones 2341, Consultorio 6, Piso 2, Cumbres 2.º Sector, Monterrey. Estacionamiento gratuito; elevador y acceso para silla de ruedas."),
    ], "pregunta", "text")}),
    container("pc-cta", "di-cta", {"eyebrow": "Agenda", "title": "Agenda tu primera consulta", "text": "Escríbenos por WhatsApp y te confirmamos horario.", "message": ""}),
]

pages["contacto"] = [
    container("co-bleed", "di-bleed", {"eyebrow": "Contacto", "title": "Consultorio en<br><em>Cumbres, Monterrey.</em>",
        "lead": "CAB Medical, Av. Paseo de los Leones 2341, Consultorio 6, Piso 2. Consulta previa cita: agenda por WhatsApp.",
        "variant": "sand", "crumb": "Contacto"}),
    container("co-ubic", "di-ubicacion", {"eyebrow": "Cómo llegar", "title": "",
        "facts": rep([("Estacionamiento", "Gratuito"), ("Acceso", "Elevador y acceso para silla de ruedas")], "label", "value"),
        "map_embed": "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3594.777590213581!2d-100.3765032!3d25.7117869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x866297c19d8eb1cb%3A0x5c3e8ac4d16be247!2sDra%20Irina%20Gonz%C3%A1lez%20S%C3%A1ez!5e0!3m2!1ses!2smx!4v1791433463370!5m2!1ses!2smx"}),
    container("co-narr", "di-narrativa", {"eyebrow": "Urgencias", "title": "Este sitio no es un <em>canal de urgencias.</em>",
        "lead": "Si tienes dificultad para respirar, sangrado nasal que no se detiene o un dolor intenso, acude al servicio de urgencias más cercano. Para citas y dudas, escríbenos por WhatsApp.", "text": ""}),
    container("co-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿Agendamos?", "text": "WhatsApp es el canal más rápido para citas.", "message": ""}),
]

pages["preguntas-frecuentes"] = [
    container("faq-bleed", "di-bleed", {"eyebrow": "Preguntas frecuentes", "title": "Lo que más <em>nos preguntan.</em>",
        "lead": "Horario, costos, seguros, niños y qué pasa en la consulta. Las dudas médicas se responden en cada página de padecimiento.", "variant": "sand", "crumb": "Preguntas frecuentes"}),
    container("faq-serv", "di-faq", {"eyebrow": "Servicio", "title": "Consulta y <em>agenda.</em>", "items": rep([
        ("¿Cómo agendo?", "Por WhatsApp, en un toque desde cualquier página del sitio, o por teléfono. La consulta es previa cita."),
        ("¿Cuál es el horario?", "Previa cita: agenda por WhatsApp y te confirmamos el horario disponible."),
        ("¿Cuánto dura la consulta?", "Alrededor de 30 minutos."),
        ("¿Cuánto cuesta?", "Costo de la consulta: " + PEND + ". Cirugías y estudios, previa valoración."),
        ("¿Aceptan seguros?", "Particulares y reembolso de seguros de gastos médicos."),
        ("¿Atienden niños?", "Sí, desde los 0 meses."),
        ("¿En qué hospitales opera la Dra.?", "Christus Muguerza, Hospital Zambrano Hellion y Hospital Ángeles Valle Oriente."),
    ], "pregunta", "text")}),
    container("faq-cta", "di-cta", {"eyebrow": "Agenda", "title": "¿Otra duda?", "text": "Escríbenos por WhatsApp.", "message": ""}),
]

pages["links"] = [container("links", "di-enlaces", {"title": "", "subtitle": "", "extra": []})]

legal = {}
legal["aviso-de-privacidad"] = f"""<p><strong>Aviso de privacidad (versión provisional)</strong>. Este texto es un borrador genérico conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP) y queda sujeto a la versión definitiva de la Dra. y a revisión legal. {PEND}</p>
<h2>Responsable</h2><p>{PEND}: Dra. Irina González Sáez, con domicilio en Av. Paseo de los Leones 2341, Consultorio 6, Piso 2, Cumbres 2.º Sector, 64610 Monterrey, Nuevo León, es responsable del tratamiento de sus datos personales.</p>
<h2>Datos que recabamos</h2><p>Datos de identificación y contacto (nombre, teléfono, correo electrónico) que usted proporciona al escribirnos por WhatsApp, por teléfono o por los formularios de este sitio. Los datos de salud que comparta en consulta se tratan en el expediente clínico, con las medidas de seguridad que exige la normativa sanitaria, y no a través de este sitio web.</p>
<h2>Finalidades</h2><p>Agendar y confirmar citas, dar seguimiento a su atención y responder dudas. No usamos sus datos para fines publicitarios sin su consentimiento.</p>
<h2>Transferencias</h2><p>No se transfieren datos a terceros salvo obligación legal. Los proveedores tecnológicos del sitio (alojamiento, mensajería) actúan como encargados.</p>
<h2>Derechos ARCO</h2><p>Puede ejercer sus derechos de acceso, rectificación, cancelación y oposición escribiendo a {PEND} (correo para derechos ARCO). Responderemos en los plazos que marca la ley.</p>
<h2>Cookies</h2><p>Este sitio usa cookies técnicas necesarias y, si usted lo acepta, cookies de medición anónima. Puede desactivarlas en su navegador.</p>
<h2>Cambios</h2><p>Cualquier modificación a este aviso se publicará en esta página. Última actualización: {PEND}.</p>"""
legal["aviso-medico"] = """<p>La información de este sitio tiene fines educativos y de orientación general. No sustituye la consulta médica, el diagnóstico ni el tratamiento indicados por un profesional de la salud que haya valorado su caso.</p>
<h2>No es un canal de urgencias</h2><p>Si tiene dificultad para respirar, un sangrado que no se detiene, dolor intenso o cualquier situación que considere urgente, acuda al servicio de urgencias más cercano o llame a los servicios de emergencia.</p>
<h2>Contenido revisado</h2><p>Los contenidos clínicos de este sitio se publican con revisión médica de la Dra. Irina González Sáez y se indican la fecha de revisión y las fuentes consultadas. Los resultados de cualquier tratamiento varían de una persona a otra y se explican individualmente en consulta.</p>
<h2>Publicidad</h2><p>Este sitio no ofrece promociones ni garantías de resultados. Datos regulatorios: [PENDIENTE DE CONFIRMACIÓN] (número de aviso de publicidad, si aplica).</p>"""
legal["terminos-de-uso"] = """<p>Al usar este sitio usted acepta estos términos. Si no está de acuerdo, le pedimos no utilizarlo.</p>
<h2>Uso del sitio</h2><p>El contenido es informativo. Está prohibido copiarlo, modificarlo o usarlo con fines comerciales sin autorización escrita. Las marcas, logotipos, textos y fotografías pertenecen a la Dra. Irina González Sáez o a sus autores.</p>
<h2>Enlaces a terceros</h2><p>Los enlaces a sitios externos (Doctoralia, redes sociales, mapas) se ofrecen como referencia; no somos responsables de su contenido ni de sus políticas.</p>
<h2>Citas y comunicación</h2><p>Las solicitudes por WhatsApp, teléfono o formulario no constituyen una cita confirmada hasta recibir confirmación del consultorio.</p>
<h2>Legislación aplicable</h2><p>Estos términos se rigen por las leyes de los Estados Unidos Mexicanos. [PENDIENTE DE CONFIRMACIÓN]: jurisdicción y datos de contacto legal.</p>"""

seo = {
    "inicio-v2": ("Otorrinolaringólogo en Monterrey | Dra. Irina González Sáez", "Otorrinolaringólogo certificado en Monterrey, especialista en ronquido y apnea del sueño. Oído, nariz y garganta para niños y adultos en Cumbres. Agenda por WhatsApp."),
    "dra-irina-gonzalez-saez": ("Dra. Irina González Sáez, otorrinolaringólogo especialista en sueño", "Formación en dos países, certificación del Consejo Mexicano de ORL y CCC y subespecialización en desórdenes respiratorios del dormir. Consultorio en Cumbres, Monterrey."),
    "otorrinolaringologia": ("Otorrinolaringología en Monterrey: oído, nariz y garganta", "Padecimientos y tratamientos de oído, nariz y garganta en niños y adultos. Cuándo acudir al otorrino y qué esperar. Dra. Irina González Sáez, Cumbres."),
    "sueno": ("Especialista en ronquido y apnea del sueño en Monterrey", "Otorrinolaringólogo especialista en desórdenes respiratorios del dormir: estudio del sueño, CPAP y cirugía de ronquido y apnea. Dra. Irina González Sáez."),
    "primera-consulta": ("Primera consulta con la Dra. Irina González Sáez | Qué esperar", "Cómo es la consulta de otorrinolaringología: 30 minutos, exploración completa y plan claro. Qué llevar, costos y seguros."),
    "contacto": ("Contacto y ubicación | Dra. Irina González Sáez, Cumbres", "Consultorio en CAB Medical, Av. Paseo de los Leones 2341, Cumbres 2.º Sector, Monterrey. Horario, mapa, estacionamiento y WhatsApp."),
    "preguntas-frecuentes": ("Preguntas frecuentes | Dra. Irina González Sáez", "Horario, costo de consulta, seguros, atención a niños y hospitales. Respuestas de servicio del consultorio de la Dra. Irina González Sáez."),
    "aviso-de-privacidad": ("Aviso de privacidad | Dra. Irina González Sáez", "Cómo tratamos sus datos personales conforme a la LFPDPPP."),
    "aviso-medico": ("Aviso médico | Dra. Irina González Sáez", "La información del sitio es educativa y no sustituye la consulta médica. No es un canal de urgencias."),
    "terminos-de-uso": ("Términos de uso | Dra. Irina González Sáez", "Condiciones de uso del sitio web de la Dra. Irina González Sáez."),
}

out = pathlib.Path(__file__).parent / "pages"
out.mkdir(exist_ok=True)
for slug, data in pages.items():
    (out / f"{slug}.elementor.json").write_text(json.dumps(data, ensure_ascii=False, separators=(",", ":")), encoding="utf-8")
for slug, html in legal.items():
    (out / f"{slug}.html").write_text(html.strip() + "\n", encoding="utf-8")
(out / "seo.json").write_text(json.dumps({k: {"title": v[0], "description": v[1]} for k, v in seo.items()}, ensure_ascii=False, indent=1), encoding="utf-8")
print("ok", len(pages), "elementor,", len(legal), "legales,", len(seo), "seo")
