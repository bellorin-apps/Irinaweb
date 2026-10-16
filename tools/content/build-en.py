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
from practice import CALLE, CENTRO, CIUDAD, COLONIA, ZONA

DIRECCION_EN_CORTA = f"{CENTRO}, {CALLE}, Office 6, 2nd floor"
DIRECCION_EN = f"{DIRECCION_EN_CORTA}, {COLONIA}, {CIUDAD}"

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
        "title": "<b>Breathe</b> well,<br>sleep well,<br>hear <em>well</em>",
        "lead": f"Dr. Irina González Sáez, board-certified otolaryngologist (ENT) with subspecialty training in snoring and sleep apnea. Ear, nose and throat care for children and adults in Cumbres, Monterrey. {SPANISH_NOTE}",
        "lead_mobile": f"Board-certified ENT with subspecialty training in snoring and sleep apnea. Children and adults, in Cumbres. {SPANISH_NOTE}",
        "trust": f"Mexican Board of Otolaryngology–Head and Neck Surgery\nSubspecialty in sleep-disordered breathing\n{CENTRO} · {ZONA}, {CIUDAD}",
        "image": {"id": "__HERO_ID__", "url": "__HERO_URL__"},
        "secondary_label": "Meet Dr. Irina", "secondary_url": "/en/dr-irina-gonzalez-saez/",
        "ticker": "Snoring and sleep apnea\nBlocked nose\nSinusitis\nBlocked ear\nHearing loss and tinnitus\nVertigo\nTonsils and adenoids\nVoice and reflux"}),
    container("areas", "di-areas", {
        "eyebrow": "Areas of care", "title": "<b>Two specialties</b>, one <br class=\"di-brd\">way of <em>caring</em>",
        "orl_eyebrow": "Otolaryngology (ENT)", "orl_title": "Ear, nose and throat, from birth",
        "orl_items": "Ear infections\nHearing loss\nTinnitus\nVertigo\nRhinitis\nSinusitis\nDeviated septum\nTonsils\nVoice",
        "orl_link": "/en/ent/", "orl_link_label": "See what I treat",
        "orl_image": {"id": "__ORL_IMG_ID__", "url": "__ORL_IMG_URL__"},
        "sleep_eyebrow": "Sleep and breathing", "sleep_title": "Snoring and sleep apnea, with complete diagnosis and treatment",
        "sleep_items": "Home sleep test\nSleep endoscopy\nCPAP\nPharyngoplasty\nNasal surgery",
        "sleep_link": "/en/sleep/", "sleep_link_label": "See the full pathway",
        "sleep_image": {"id": "__SUENO_IMG_ID__", "url": "__SUENO_IMG_URL__"}}),
    container("motivos", "di-motivos", {
        "eyebrow": "What are you feeling?", "title": "<b>Common reasons</b> to <em>visit</em>",
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
        "eyebrow": "Dr. Irina González Sáez", "quote": "“<b>Listening</b> <br class=\"di-brd\">is also part <br class=\"di-brd\">of <em>treatment</em>”",
        "text": "Medical degree from <abbr title=\"Universidad Nacional Experimental Francisco de Miranda\">UNEFM</abbr> and specialty in Otolaryngology from <abbr title=\"Universidad Centroccidental Lisandro Alvarado\">UCLA</abbr>, Venezuela. Subspecialty training in sleep-disordered breathing, snoring and applied rhinology at ISSSTE in Monterrey.",
        "creds": "Board-certified by the Mexican Board of Otolaryngology–Head and Neck Surgery\nIbero-American Society of Sleep Surgery · Nuevo León College of Otolaryngology\nPractices at Christus Muguerza, Zambrano Hellion, Ángeles Valle Oriente, Hospital Universitario and Hospitaria",
        "link": "/en/dr-irina-gonzalez-saez/", "link_label": "Meet Dr. Irina",
        "image": {"id": "__DRA_IMG_ID__", "url": "__DRA_IMG_URL__"}, "bg": {"id": "__DRA_BG_ID__", "url": "__DRA_BG_URL__"}, "bg_tone": "light"}),
    container("pasos", "di-pasos", {
        "eyebrow": "How we work", "title": "<b>What</b> to expect at your <em>visit</em>",
        "steps": rep([
            ("Listen", "Your symptoms, how long you’ve had them and how they affect your day. Bring previous tests and your list of medications.", "paso-escuchar"),
            ("Examine", "Ear, nose and throat; nasal endoscopy or laryngoscopy in the office when needed.", "paso-explorar"),
            ("Diagnose", "If the problem is sleep-related, respiratory polygraphy or a home sleep test performed by Dr. Irina herself.", "paso-diagnosticar"),
            ("Support", "A clear plan, with medical and surgical options explained, and follow-up in person or online.", "paso-acompanar"),
        ], "title", "text", "img")}),
    container("sueno", "di-sueno", {
        "eyebrow": "Sleep specialization", "title": "<b>From snoring</b><br>to rest, all in<br>one <em>place</em>",
        "lead": "In Monterrey, snoring and sleep apnea often go through several specialists. Dr. Irina evaluates the airway, performs the study, interprets the result and offers everything from CPAP to palate and nasal surgery.",
        "link": "/en/sleep/", "link_label": "Explore the treatment pathway",
        "steps": "Airway evaluation and sleep endoscopy\nRespiratory polygraphy or home sleep test, interpreted by Dr. Irina\nTreatment: CPAP with follow-up, positional therapy, palate or nasal surgery\nFollow-up and adjustment until you sleep well"}),
    container("ubicacion", "di-ubicacion", {
        "eyebrow": "Office", "title": "",
        "facts": rep([("Parking", "Free"), ("Access", "Elevator and wheelchair access"), ("Language", "Consultations are in Spanish")], "label", "value"),
        "map_embed": MAP_EMBED}),
    container("cta", "di-cta", {
        "eyebrow": "Book", "title": "<b>Shall we talk</b> about <br class=\"di-brd\">what’s keeping you <br class=\"di-brd\">from <em>resting</em>?",
        "text": f"Message us on WhatsApp and we’ll help you choose the right type of visit. {SPANISH_NOTE}", "message": ""}),
])


pages["dr-irina-gonzalez-saez"] = ("Dr. Irina González Sáez", "dra-irina-gonzalez-saez", [
    container("dra-bleed", "di-bleed", {
        "eyebrow": "Your ENT | Sleep specialist",
        "title": "<b>Dr.</b> Irina<br>González <em>Sáez</em>",
        "lead": f"Board-certified otolaryngologist (ENT) in Monterrey, with subspecialty training in sleep-disordered breathing, snoring and rhinology. Children from birth, teenagers, adults and older adults. {SPANISH_NOTE}",
        "variant": "warm", "crumb": "About", "image": {"id": "__DRA_HERO_ID__", "url": "__DRA_HERO_URL__"}, "focus": "50% 0%", "focus_mobile": "92% 8%", "secondary_short": "Doctoralia",
        "secondary_label": "Reviews on Doctoralia", "secondary_url": "https://www.doctoralia.com.mx/perfil/irina-gonzalez-saez"}),
    container("dra-enfoque", "di-narrativa", {
        "eyebrow": "Approach", "title": "“<b>Listening</b> <br class=\"di-brd\">is also part <br class=\"di-brd\">of <em>treatment</em>”",
        "lead": "The first visit lasts about 30 minutes. It starts by understanding what is happening to you and since when; it continues with a complete ear, nose and throat examination, with endoscopy in the office when needed, and ends with a clearly explained plan, without promises that cannot be kept.",
        "text": "", "draft": "",
        "image": {"id": "__DRA_ENDO_ID__", "url": "__DRA_ENDO_URL__"}, "image_pos": "right", "image_alt": "Endoscopy camera head Dr. Irina uses in the office"}),
    container("dra-creds", "di-credenciales", {"eyebrow": "Training and certifications", "title": "<b>Verifiable</b> <em>credentials</em>", "tags_title": "", "extra_tags": ""}),
    container("dra-logos", "di-logos", {
        "eyebrow": "", "title": "<b>Memberships</b> and <em>hospitals</em>",
        "items": rep([
            ("CHRISTUS MUGUERZA Health System", "christus-muguerza", ""),
            ("Hospital Zambrano Hellion · TecSalud", "zambrano-hellion", ""),
            ("Hospital Ángeles Valle Oriente", "angeles-valle-oriente", ""),
            ("Hospital Universitario «Dr. José Eleuterio González», UANL", "hospital-universitario", ""),
            ("Hospitaria", "hospitaria", ""),
            ("Mexican Board of Otolaryngology–Head and Neck Surgery (CMORL y CCC)", "consejo-orl", ""),
            ("Mexican Federation of Otolaryngology–Head and Neck Surgery (FESORMEX)", "fesormex", ""),
            ("Ibero-American Society of Sleep Surgery (SIBECS)", "sociedad-iberoamericana-sueno", ""),
            ("Nuevo León College of Otolaryngology", "colegio-orl-nl", ""),
        ], "name", "slug", "url")}),
    container("dra-timeline", "di-trayectoria", {"eyebrow": "Career", "title": "Ongoing <b>training</b> in <em>sleep surgery</em>"}),
    container("dra-opiniones", "di-opiniones", {"eyebrow": "Reviews", "title": "<b>What</b> patients <em>say</em>",
        "text": "Reviews published on Doctoralia (in Spanish). They are personal experiences and do not guarantee results."}),
    container("dra-cta", "di-cta", {"eyebrow": "Book", "title": "<b>Book</b> your<br>first <em>visit</em>",
        "text": f"30 minutes to listen to you, examine you and propose a plan. {SPANISH_NOTE}", "message": ""}),
])

pages["ent"] = ("Otolaryngology (ENT)", "otorrinolaringologia", [
    container("orl-bleed", "di-bleed", {"eyebrow": "Otolaryngology (ENT)", "title": "<b>Ear, nose</b> and throat,<br><em>from birth</em>",
        "lead": f"Ear, nose and throat consultations for children, teenagers, adults and older adults in Monterrey. {SPANISH_NOTE}",
        # Foto de cabecera como /otorrinolaringologia/ (propietario, 2026-10-16): adjunto hero-orl, tono cálido.
        "variant": "warm", "crumb": "ENT", "image": {"id": "__ORL_HERO_ID__", "url": "__ORL_HERO_URL__"}, "focus": "50% 0%", "focus_mobile": "64% 50%"}),
    container("orl-oido", "di-listado", {"eyebrow": "Ear", "title": "<b>What</b> I treat <em>in the ear</em>", "post_type": "", "anchor": "ear",
        "items": rep([("Ear infections (otitis)", "Infection or inflammation of the ear", ""), ("Earwax blockage", "Ear blocked by earwax", ""), ("Hearing loss", "Reduced hearing", ""), ("Tinnitus", "Ringing or buzzing in the ear", ""), ("Vertigo", "Dizziness or a spinning sensation", "")], "label", "sub", "url")}),
    container("orl-nariz", "di-listado", {"eyebrow": "Nose", "title": "<b>What</b> I treat <em>in the nose</em>", "post_type": "", "anchor": "nose", "tight": "yes",
        "items": rep([("Rhinitis", "Frequent stuffy nose, sneezing or runny nose", ""), ("Sinusitis", "Inflammation of the sinuses", ""), ("Deviated septum", "Difficulty breathing through the nose", ""), ("Nasal polyps", "Growths inside the nose", ""), ("Nosebleeds", "Epistaxis", "")], "label", "sub", "url")}),
    container("orl-garganta", "di-listado", {"eyebrow": "Throat and voice", "title": "<b>What</b> I treat <em>in the throat</em>", "post_type": "", "anchor": "throat", "tight": "yes",
        "items": rep([("Tonsillitis", "Frequent sore throat", ""), ("Hoarseness and voice changes", "Including laryngopharyngeal reflux", "")], "label", "sub", "url")}),
    container("orl-proc", "di-listado", {"eyebrow": "Procedures", "title": "<b>Office</b> and <em>surgery</em>", "post_type": "", "anchor": "procedures", "tight": "yes",
        "note": "Any surgery is recommended only after an evaluation, where options and alternatives are explained.",
        "items": rep([("Nasal endoscopy and laryngoscopy", "In the office, when needed", ""), ("Septoplasty", "Surgery of the nasal septum", ""), ("Turbinoplasty", "Surgery of the turbinates", ""), ("Tonsils and adenoids", "Surgery in children and adults", ""), ("Endoscopic sinus surgery", "Surgery of the sinuses", ""), ("Functional and cosmetic rhinoplasty", "Nose surgery for function and for shape", ""), ("Sleep surgery", "Snoring and obstructive sleep apnea", "")], "label", "sub", "url")}),
    container("orl-como", "di-narrativa", {"eyebrow": "How I work", "title": "<b>First</b> listen, then <em>examine</em>",
        "lead": "The first visit lasts about 30 minutes. It starts by understanding what is happening to you and since when; it continues with a complete ear, nose and throat examination, with endoscopy in the office when needed, and ends with a clearly explained plan. If your case needs another specialist, I will tell you and refer you.",
        "text": ""}),
    container("orl-cta", "di-cta", {"eyebrow": "Book", "title": "<b>A symptom</b> that <em>won’t go away</em>?", "text": f"Message us on WhatsApp and we’ll help you choose the right type of visit. {SPANISH_NOTE}", "message": "Hello, I would like to book an ENT appointment with Dr. Irina González Sáez."}),
])

pages["sleep"] = ("Snoring and sleep apnea", "sueno", [
    container("su-bleed", "di-bleed", {"eyebrow": "Sleep and breathing", "title": "<b>From snoring</b><br>to <em>rest</em>",
        "lead": f"Otolaryngologist with subspecialty training in sleep-disordered breathing, snoring and rhinology. {SPANISH_NOTE}",
        # Misma foto de cabecera que /sueno/ (propietario, 2026-10-16): adjunto hero-sueno; sin él, el degradado nocturno.
        "variant": "night", "crumb": "Sleep", "whatsapp_label": "Book a snoring and sleep apnea evaluation",
        "image": {"id": "__SU_HERO_ID__", "url": "__SU_HERO_URL__"}, "focus": "50% 0%", "focus_mobile": "68% 50%"}),
    container("su-cond", "di-listado", {"eyebrow": "What I treat", "title": "<b>Snoring</b> and <em>sleep apnea</em>", "post_type": "", "anchor": "conditions",
        "items": rep([("Snoring", "In adults and children", "/en/sleep/snoring/"), ("Obstructive sleep apnea", "Pauses in breathing during sleep", "/en/sleep/obstructive-sleep-apnea/")], "label", "sub", "url")}),
    container("su-ruta", "di-listado", {"eyebrow": "Tests and treatments", "title": "<b>The</b> full <em>pathway</em>", "post_type": "", "anchor": "pathway", "tight": "yes",
        "note": "Treatment is decided with the study results in hand and according to where the airway narrows.",
        "items": rep([("Home sleep test", "Respiratory polygraphy interpreted by Dr. Irina herself", "/en/sleep/home-sleep-test/"), ("Sleep endoscopy", "Shows where the airway closes during sleep", ""), ("CPAP", "Prescription, adjustment and follow-up", "/en/sleep/cpap-therapy/"), ("Snoring and sleep apnea surgery", "Nose, palate or tonsils, depending on the case", "/en/sleep/snoring-and-sleep-apnea-surgery/"), ("Mandibular advancement device", "With a dentist specialized in sleep", "")], "label", "sub", "url")}),
    container("su-como", "di-narrativa", {"eyebrow": "How I work", "title": "<b>An</b> ENT for the <em>airway</em>",
        "lead": "Nose, palate, tonsils and tongue: as an otolaryngologist I directly examine where the airway narrows during sleep. The evaluation starts in the office; with the study results in hand I explain the options and we decide on the treatment together.",
        "text": ""}),
    container("su-cta", "di-cta", {"eyebrow": "Book", "title": "<b>Shall we talk</b> about what’s keeping you from <em>resting</em>?", "text": f"A 30-minute evaluation determines whether you need a study. {SPANISH_NOTE}", "message": "Hello, I would like to book a snoring or sleep apnea evaluation with Dr. Irina González Sáez."}),
])

pages["first-visit"] = ("First visit", "primera-consulta", [
    container("pc-bleed", "di-bleed", {"eyebrow": "First visit", "title": "<b>What</b> to expect<br>at your <em>visit</em>",
        "lead": f"About 30 minutes to listen to you, examine you and propose a plan. Children from birth, teenagers, adults and older adults. {SPANISH_NOTE}",
        "variant": "warm", "crumb": "First visit", "image": {"id": "__PC_HERO_ID__", "url": "__PC_HERO_URL__"}, "focus": "50% 6%", "focus_mobile": "85% 20%"}),
    container("pc-pasos", "di-pasos", {"eyebrow": "Step by step", "title": "<b>How</b> the <em>visit</em> works", "steps": rep([
        ("Listen", "Your symptoms, when they started and how they affect your day and your rest.", "paso-escuchar"),
        ("Examine", "Ear, nose and throat, with nasal endoscopy or laryngoscopy when needed.", "paso-explorar"),
        ("Diagnose", "If the problem is sleep-related, a home study interpreted by Dr. Irina herself.", "paso-diagnosticar"),
        ("Support", "A clear plan with medical and surgical options, and close follow-up.", "paso-acompanar"),
    ], "title", "text", "img")}),
    container("pc-narr", "di-narrativa", {"eyebrow": "What to bring", "title": "<b>Bring</b> what you <em>already have</em>",
        "bg_image": {"id": "__PC_BG_ID__", "url": "__PC_BG_URL__"}, "bg_pos": "top",
        "text": "<ul><li>ID.</li><li>Previous tests (audiometry, CT scan, sleep study) if you have them.</li><li>A list of the medications you take.</li><li>A medical referral, if you have one.</li><li>If you are coming for snoring or sleep apnea: what your partner or family has noticed while you sleep.</li><li>For children: vaccination record and history of ear or throat infections.</li></ul>", "draft": ""}),
    container("pc-faq", "di-faq", {"eyebrow": "Frequently asked questions", "title": "<b>Cost,</b> payments and <em>insurance</em>",
        "bg_image": {"id": "__PC_BG_ID__", "url": "__PC_BG_URL__"}, "bg_pos": "bottom", "items": rep([
        ("How much does a visit cost?", "We confirm the cost when you book via WhatsApp. Surgeries and tests are quoted after the evaluation."),
        ("Do you accept health insurance?", "We see private patients and offer reimbursement with Mexican private health insurance; Dr. Irina provides the paperwork for your insurer."),
        ("How can I pay?", "Cash, card and bank transfer. Invoices available."),
        ("Are online visits available?", "Only for follow-up of patients already evaluated in the office."),
        ("Do you see children?", "Yes, from birth."),
        ("In what language are visits?", "Consultations are in Spanish."),
        ("Where is the office and is there parking?", f"{DIRECCION_EN}. Free parking; elevator and wheelchair access."),
    ], "pregunta", "text")}),
    container("pc-cta", "di-cta", {"eyebrow": "Book", "title": "<b>Book</b> your<br><em>first visit</em>", "text": "Message us on WhatsApp and we’ll confirm a time.", "message": ""}),
])

pages["contact"] = ("Contact", "contacto", [
    container("co-bleed", "di-bleed", {"eyebrow": "Contact", "title": "<b>Office</b> in<br>Cumbres, <em>Monterrey</em>",
        "lead": f"{DIRECCION_EN_CORTA}. By appointment only: book via WhatsApp. {SPANISH_NOTE}",
        "variant": "warm", "crumb": "Contact", "image": {"id": "__CO_HERO_ID__", "url": "__CO_HERO_URL__"}, "focus": "50% 6%", "focus_mobile": "85% 20%"}),
    container("co-ubic", "di-ubicacion", {"eyebrow": "Getting here", "title": "<b>CAB</b> Medical,<br><em>Headquarters</em>",
        "facts": rep([("Parking", "Free"), ("Access", "Elevator and wheelchair access"), ("Language", "Consultations are in Spanish")], "label", "value"),
        "map_embed": MAP_EMBED}),
    container("co-form", "di-form", {"eyebrow": "Write to us", "title": "Would you like<br>us to <em>call you</em>?",
        "lead": "Leave your name and phone number and we’ll contact you during office hours. For faster booking, use WhatsApp.", "button": "Send request"}),
    container("co-narr", "di-narrativa", {"eyebrow": "Emergencies", "title": "<b>This website</b> is not an<br><em>emergency</em> channel",
        "lead": "If you have difficulty breathing, a nosebleed that won’t stop or severe pain, go to the nearest emergency department or call 911. For appointments and questions, message us on WhatsApp.", "text": ""}),
    container("co-cta", "di-cta", {"eyebrow": "Book", "title": "Shall we <em>book</em>?", "text": "WhatsApp is the fastest channel for appointments.", "message": ""}),
])

pages["thank-you"] = ("Thank you", "gracias", [
    container("gr-bleed", "di-bleed", {"eyebrow": "Message sent", "title": "<b>Received</b>, <em>thank you</em>",
        "lead": "We’ll contact you during office hours by phone or WhatsApp. If it’s urgent, go to the nearest emergency department.",
        "variant": "sand", "crumb": "Thank you"}),
    container("gr-narr", "di-narrativa", {"eyebrow": "In the meantime", "title": "<b>What</b> you can get <em>ready</em>",
        "lead": "Previous tests, a list of your medications and, if it’s about snoring or sleep apnea, what the person who sleeps with you has noticed.", "text": ""}),
    container("gr-cta", "di-cta", {"eyebrow": "In a hurry?", "title": "<b>If</b> you prefer, <em>message us now</em>", "text": "WhatsApp is the fastest channel for booking.", "message": ""}),
])

pages["faq"] = ("Frequently asked questions", "preguntas-frecuentes", [
    container("faq-bleed", "di-bleed", {"eyebrow": "Frequently asked questions", "title": "<b>What</b> we’re asked <em>most</em>",
        "lead": "Hours, costs, insurance, children and what happens at the visit. Questions about your own case are answered at the evaluation.", "variant": "sand", "crumb": "FAQ"}),
    container("faq-serv", "di-faq", {"eyebrow": "Service", "title": "<b>Visits</b> and <em>booking</em>", "items": rep([
        ("How do I book?", "Via WhatsApp, in one tap from any page of the site, or by phone. Visits are by appointment only."),
        ("What are the hours?", "By appointment only: book via WhatsApp and we’ll confirm the available time."),
        ("How long is the visit?", "About 30 minutes."),
        ("How much does it cost?", "We confirm the cost when you book via WhatsApp. Surgeries and tests, after an evaluation."),
        ("Do you accept insurance?", "Private patients and reimbursement with Mexican private health insurance."),
        ("How can I pay?", "Cash, card and bank transfer. Invoices available."),
        ("Do you offer online visits?", "Only follow-up of patients already evaluated in the office."),
        ("Do you see children?", "Yes, from birth."),
        ("In what language are visits?", "Consultations are in Spanish."),
        ("At which hospitals does Dr. Irina operate?", "Christus Muguerza, Hospital Zambrano Hellion and Hospital Ángeles Valle Oriente."),
    ], "pregunta", "text")}),
    container("faq-cta", "di-cta", {"eyebrow": "Book", "title": "<b>Another</b> <em>question</em>?", "text": "Message us on WhatsApp.", "message": ""}),
])

# Legales: HTML (como en español) con los shortcodes del consultorio; nota de prevalencia del español (Anexo A, 13.6).
legal = {
    "privacy-notice": ("Privacy notice", "aviso-de-privacidad"),
    "medical-disclaimer": ("Medical disclaimer", "aviso-medico"),
    "terms-of-use": ("Terms of use", "terminos-de-uso"),
}

# Credenciales: traducción por slug de la credencial (setup-en.sh la guarda en la meta _di_en). Nombres propios sin traducir.
credentials_en = {
    "medico-cirujano": {"titulo": "Medical Degree (Physician and Surgeon)", "lugar": "Santa Ana de Coro, Venezuela"},
    "especialista-orl": {"titulo": "Specialist in Otolaryngology", "lugar": "Barquisimeto, Venezuela"},
    "subespecializacion-sueno": {"titulo": "Subspecialty in Sleep-Disordered Breathing, Snoring and Applied Rhinology"},
    "consejo-orl": {"titulo": "Mexican Board of Otolaryngology–Head and Neck Surgery (Consejo Mexicano de ORL y CCC)", "institucion": "Certification valid through 2030"},
    "sociedad-iberoamericana-cirugia-sueno": {"titulo": "Ibero-American Society of Sleep Surgery"},
    "colegio-orl-nuevo-leon": {"titulo": "Nuevo León College of Otolaryngology"},
    "curso-faringoplastia-2024": {"titulo": "6th Theoretical and Practical Course on Advanced Pharyngoplasty with Barbed Sutures and Sleep Endoscopy"},
    "miembro-fundador-sics-2020": {"titulo": "Founding member of the Ibero-American Society of Sleep Surgery"},
    "curso-cirugia-medicina-sueno-2019": {"titulo": "International Course on Sleep Surgery and Sleep Medicine"},
    "conferencia-cpap-2018": {"titulo": "“CPAP management”", "institucion": "Triological Course"},
    "conferencia-faringoplastia-2017": {"titulo": "“The evolution of pharyngoplasty in OSAHS, beyond UPPP”", "institucion": "68th National Congress of Otolaryngology"},
    "hu-jose-eleuterio-gonzalez": {"titulo": "Attending physician, Otolaryngology Department, Medical Services of Hospital Universitario «Dr. José Eleuterio González»"},
    "trabajo-herniacion-duramadre-2016": {"titulo": "“Dural herniation into the middle ear”", "institucion": "9th Venezuelan Triological Meeting of ORL"},
    "trabajo-carcinoma-nasofaringeo-2016": {"titulo": "“Classic presentation of nasopharyngeal carcinoma: a case report”", "institucion": "9th Venezuelan Triological Meeting of ORL"},
    "trabajo-rinoescleroma-2016": {"titulo": "“Rhinoscleroma: a case report”", "institucion": "9th Venezuelan Triological Meeting of ORL"},
    "docencia-pregrado": {"titulo": "Undergraduate otolaryngology professor at UNEFM, ULA and UNERG"},
}

seo = {
    "en": ("ENT doctor in Monterrey | Dr. Irina González Sáez",
           "Board-certified ENT in Cumbres, Monterrey: ear, nose and throat for children and adults, snoring and sleep apnea. Consultations are in Spanish."),
    "dr-irina-gonzalez-saez": ("Dr. Irina González Sáez | ENT and sleep specialist",
           "Board-certified by the Mexican Board of ORL–HNS, trained in two countries, with a subspecialty in sleep-disordered breathing. Cumbres, Monterrey."),
    "ent": ("ENT in Monterrey: ear, nose and throat | Dr. Irina González",
           "ENT in Cumbres, Monterrey: ear, nose and throat care for children from birth and adults. What I treat, how I work and how to book. In Spanish."),
    "sleep": ("Snoring and sleep apnea specialist in Monterrey",
           "Snoring and sleep apnea in Monterrey: airway evaluation, home sleep test, CPAP and surgery. Dr. Irina González Sáez, ENT. Consultations in Spanish."),
    "first-visit": ("First visit with Dr. Irina González Sáez | What to expect",
           "What an ENT visit is like: about 30 minutes, a complete examination and a clear plan. What to bring, costs and insurance. Consultations in Spanish."),
    "contact": ("Contact and location | Dr. Irina González Sáez, Cumbres",
           "Office at CAB Medical Headquarters, Cumbres, Monterrey. Map, parking and WhatsApp booking. Consultations are in Spanish."),
    "faq": ("Frequently asked questions | Dr. Irina González Sáez",
           "Hours, cost, insurance, children, hospitals and language of the visits at Dr. Irina González Sáez’s ENT office in Monterrey."),
    "thank-you": ("Message received | Dr. Irina González Sáez", "We received your request; we’ll contact you during office hours."),
    "privacy-notice": ("Privacy notice | Dr. Irina González Sáez", "How we process your personal data under Mexico’s LFPDPPP: what we collect, why, with whom we share it and how to exercise your ARCO rights."),
    "medical-disclaimer": ("Medical disclaimer | Dr. Irina González Sáez", "The information on this site is educational and does not replace a medical consultation. This is not an emergency channel."),
    "terms-of-use": ("Terms of use | Dr. Irina González Sáez", "Terms of use of Dr. Irina González Sáez’s website."),
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
index = {s: {"title": t, "es": e, "kind": "elementor"} for s, (t, e, _) in pages.items()}
index.update({s: {"title": t, "es": e, "kind": "html"} for s, (t, e) in legal.items()})
for s_ in legal:
    (OUT / f"{s_}.html").write_text((OUT / "legal" / f"{s_}.html").read_text(encoding="utf-8").strip() + "\n", encoding="utf-8")
(OUT / "pages.json").write_text(json.dumps(index, ensure_ascii=False, indent=2), encoding="utf-8")
(OUT / "credentials-en.json").write_text(json.dumps(credentials_en, ensure_ascii=False, indent=2), encoding="utf-8")
(OUT / "seo.json").write_text(json.dumps({s: {"title": t, "description": d} for s, (t, d) in seo.items()}, ensure_ascii=False, indent=2), encoding="utf-8")
(OUT / "practice-en.json").write_text(json.dumps(practice_en, ensure_ascii=False, indent=2), encoding="utf-8")
for s, (t, d) in seo.items():
    assert len(t) <= 62 and len(d) <= 158, (s, len(t), len(d))
print(f"{len(pages)} páginas Elementor, {len(legal)} legales, {len(credentials_en)} credenciales en {OUT}")

# Guía de revisión de la Dra. (13.6): cada página inglesa por bloques, con el bloque español equivalente para comparar.
# La sirve tools/briefing/revision/revisar.php (action=pages) desde tools/briefing/guia/paginas-en.json (no servible: .htaccess).
import re as _re


def _plain(h):
    h = _re.sub(r"</(li|p)>", "\n", h or "")
    return _re.sub(r"<br[^>]*>", " ", _re.sub(r"<(?!br)[^>]+>", "", h)).strip()


def _blocks(data):
    out = []
    keys = ("lead", "text", "quote", "trust", "creds", "orl_eyebrow", "orl_title", "orl_items", "sleep_eyebrow", "sleep_title", "sleep_items", "steps", "ticker")
    for c in data:
        w = c["elements"][0]
        st = w["settings"]
        items = []
        for field in ("items", "steps", "facts"):
            for it in st.get(field, []) if isinstance(st.get(field), list) else []:
                if isinstance(it, dict):
                    items.append([_plain(it.get("label") or it.get("title") or it.get("pregunta") or it.get("name") or ""), _plain(it.get("sub") or it.get("text") or it.get("value") or "")])
        text = "\n".join(_plain(st[k]) for k in keys if isinstance(st.get(k), str) and st.get(k))
        out.append({"tipo": w["widgetType"], "eyebrow": _plain(st.get("eyebrow", "")).replace("|", " "), "titulo": _plain(st.get("title", "")),
                    "texto": text, "nota": _plain(st.get("note", "")), "items": items})
    return out


_es_dir = pathlib.Path(__file__).parent
_es_data = {"__front__": _es_dir / "home-elementor.json", "dra-irina-gonzalez-saez": _es_dir / "dra-elementor.json"}
_review = {}
for slug, (title, es, data) in pages.items():
    es_file = _es_data.get(es, _es_dir / "pages" / f"{es}.elementor.json")
    es_blocks = _blocks(json.loads(es_file.read_text(encoding="utf-8"))) if es_file.exists() else []
    en_blocks = _blocks(data)
    for i, b in enumerate(en_blocks):
        b["es"] = es_blocks[i] if i < len(es_blocks) else None
    path = "en" if slug == "en" else f"en/{slug}"
    _review[path] = {"nombre": f"{title} (inglés)", "url": "/" + path + "/", "es_url": "/" if es == "__front__" else f"/{es}/", "bloques": en_blocks}
pathlib.Path(__file__).resolve().parents[1].joinpath("briefing", "guia", "paginas-en.json").write_text(json.dumps(_review, ensure_ascii=False, indent=1), encoding="utf-8")
print(len(_review), "páginas en la guía de revisión (paginas-en.json)")

