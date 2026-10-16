#!/usr/bin/env python3
"""Fichas de sueño en inglés (D-095, PLAN 13.4): traducción fiel del texto APROBADO por la Dra. en producción
(tools/content/en/fichas-es-aprobadas.json, exportado el 2026-10-16), no de medical-drafts.json, que difiere en algunos campos.

Reglas: nada médico se añade, se quita ni se «mejora»; la errata española de la ficha de apnea («una otorrinolaringólogo»,
D-090) se traduce por su sentido. Términos según docs/GLOSSARY_EN.md. Las fichas se crean en BORRADOR con el estado «requiere
revisión médica»: la Dra. las revisa y aprueba en su app como cualquier ficha (13.6).
Las relaciones (relacionados, tratamientos, qué resuelve) se guardan como slugs españoles; setup-en.php las convierte en los IDs de
las fichas inglesas y descarta las que no tienen versión inglesa.
Salida: tools/content/en/fichas-en.json
"""
import json, pathlib

HERE = pathlib.Path(__file__).parent
approved = {f["slug"]: f for f in json.loads((HERE / "en" / "fichas-es-aprobadas.json").read_text(encoding="utf-8"))["fichas"]}
id_to_slug = {f["ID"]: f["slug"] for f in approved.values()}

EN_SLUG = {
    "ronquido": "snoring",
    "apnea-obstructiva-del-sueno": "obstructive-sleep-apnea",
    "estudio-del-sueno": "home-sleep-test",
    "cpap": "cpap-therapy",
    "cirugia-de-ronquido-y-apnea": "snoring-and-sleep-apnea-surgery",
}

FICHAS = {
    "ronquido": {
        "title": "Snoring",
        "seo": "Snoring treatment in Monterrey | Dr. Irina González",
        "content": "<p>Snoring is not normal, even though it is common. It happens when air has trouble passing through an airway that narrows during sleep: a blocked nose, a long soft palate and uvula, large tonsils or the tongue falling back make the tissues vibrate and produce the noise.</p><p>There is <strong>primary snoring</strong>, which is noise without pauses in breathing and mainly affects whoever sleeps next to you, and snoring that is the visible symptom of <strong>obstructive sleep apnea</strong>, with pauses, drops in oxygen and sleep that is not restful. Knowing which one you have completely changes the treatment, which is why the evaluation starts there.</p>",
        "meta": {
            "causas": ["Nasal obstruction (deviated septum, rhinitis, enlarged turbinates)", "Long soft palate and uvula, or large tonsils", "Excess weight and fat buildup around the neck", "Sleeping on your back, alcohol and sedatives at night", "In children: large tonsils and adenoids"],
            "cuando_consultar": ["When your partner or family notice pauses in your breathing", "Daytime sleepiness or tiredness even when you sleep enough", "Snoring in children, at any age", "Snoring that started or got worse with weight gain", "High blood pressure that is hard to control, or waking up with a choking sensation"],
            "diagnostico": "<p>Dr. Irina examines the nose, mouth and throat and performs a nasal and airway endoscopy to see where the airflow narrows. If she suspects sleep apnea, she orders a <strong>home sleep test</strong> (respiratory polygraphy), which she interprets herself. In selected cases she performs a drug-induced sleep endoscopy (DISE) in the operating room or orders an in-lab polysomnography.</p><p>When the obstruction is nasal and surgery is being considered, Dr. Irina orders a CT scan or MRI as appropriate.</p>",
            "enfoque_dra": "<p>Treatment depends on whether we are talking about primary snoring or snoring caused by sleep apnea. That is why Dr. Irina does not treat “the noise” without first knowing what is behind it. With a clear diagnosis, the options range from general measures and nasal treatment to palate or nasal surgery, CPAP or an oral appliance, always chosen according to where your airway closes and what you are willing to use.</p>",
            "faq": [
                {"pregunta": "Is snoring dangerous?", "respuesta": "Simple snoring is not, but it can be a sign of sleep apnea, which does have consequences for the heart, blood pressure and concentration. A home sleep test clarifies it."},
                {"pregunta": "Is there surgery to stop snoring?", "respuesta": "Yes, when the cause is in the nose or the palate and the study supports it. Not everyone is a candidate, and Dr. Irina is clear about that from the first visit."},
                {"pregunta": "Should children snore?", "respuesta": "No. Frequent snoring in a child deserves a check-up: it is usually due to large tonsils or adenoids and affects their rest, behavior and growth."},
            ],
            "resumen_paciente": "Snoring is the noise air makes as it passes through a narrow airway during sleep. It can be merely a nuisance or the visible sign of sleep apnea.",
            "sintomas": ["Loud, frequent snoring, most nights", "Pauses in breathing that others notice", "Unrefreshing sleep and tiredness on waking", "Dry mouth or headache in the morning", "Daytime sleepiness", "In children: snoring, mouth breathing, night sweats and behavior changes"],
        },
    },
    "apnea-obstructiva-del-sueno": {
        "title": "Obstructive sleep apnea",
        "seo": "Obstructive sleep apnea in Monterrey | Dr. Irina González",
        "content": "<p>In obstructive sleep apnea, the airway closes again and again while you sleep. Each closure stops your breathing for a few seconds, lowers your oxygen and forces your brain to wake you briefly so you can breathe again. These are awakenings you don’t remember, but they break up your sleep hundreds of times a night: that is why you wake up tired even after sleeping “eight hours.”</p><p>Leaving it untreated has consequences: high blood pressure, higher cardiovascular risk, drowsiness while driving, and problems with concentration and mood. The good news is that it is diagnosed with a simple test you can do in your own bed, and today there are several effective treatments, chosen according to the cause of the obstruction and your preferences.</p>",
        "meta": {
            "causas": ["A narrow airway due to anatomy: palate, tongue, tonsils, small jaw", "Nasal obstruction", "Excess weight and obesity", "Age, male sex and menopause in women", "Alcohol, sedatives and sleeping on your back, which make it worse"],
            "cuando_consultar": ["If you snore and someone has seen you stop breathing", "If you wake up tired despite sleeping enough", "If you have high blood pressure that is hard to control", "If you fall asleep watching TV, reading or driving", "If you use CPAP and can’t tolerate it: there are alternatives to consider"],
            "diagnostico": "<p>Dr. Irina examines the nose, mouth and throat and performs a nasal and airway endoscopy to see where the airflow narrows. If she suspects sleep apnea, she orders a <strong>home sleep test</strong> (respiratory polygraphy), which she interprets herself. In selected cases she performs a drug-induced sleep endoscopy (DISE) in the operating room or orders an in-lab polysomnography.</p><p>The home test records your breathing, oxygen, snoring and position during a normal night, and Dr. Irina interprets it personally to explain the result and grade the apnea. With that, and with what she saw on examination, she defines the treatment.</p>",
            "enfoque_dra": "<p>Dr. Irina has a subspecialty in sleep-disordered breathing and approaches sleep apnea from both sides: the medical one, with CPAP, oral appliances and general measures, and the surgical one, with nasal and palate surgery when the anatomy calls for it. Her role is to help you choose a treatment you can keep up over time, because sleep apnea is controlled with consistency, and to support you until you sleep well.</p>",
            "faq": [
                {"pregunta": "Is CPAP the only option?", "respuesta": "No. It is the most effective treatment for moderate and severe sleep apnea, but there are alternatives: nasal or palate surgery, mandibular advancement devices, positional therapy and weight control. They are chosen according to your case."},
                {"pregunta": "Can I have the test at home?", "respuesta": "Yes. Home respiratory polygraphy is the first-choice test for most adults with suspected sleep apnea. Dr. Irina lends you the device, explains how to put it on and interprets it herself."},
                {"pregunta": "Can sleep apnea be cured?", "respuesta": "It depends on the cause. When it is due to large tonsils or a correctable obstruction, surgery can resolve it. In other cases it is effectively controlled with ongoing treatment, just like high blood pressure."},
                {"pregunta": "Can children have sleep apnea?", "respuesta": "Yes, almost always because of large tonsils and adenoids. Dr. Irina evaluates selected pediatric cases and, when appropriate, coordinates with pediatrics."},
            ],
            # Traducción por sentido: el español aprobado dice «una otorrinolaringólogo» (errata pendiente, D-090).
            "resumen_paciente": "What it is, how it is diagnosed and what treatment options exist, explained by an otolaryngologist (ENT) with a subspecialty in sleep-disordered breathing.",
            "sintomas": ["Loud snoring with pauses that others notice", "Unrefreshing sleep and daytime sleepiness", "Headache on waking and dry mouth", "Waking up with a choking sensation", "Difficulty concentrating, irritability and poor performance", "Excessive daytime sleepiness", "Getting up several times at night to urinate"],
        },
    },
    "estudio-del-sueno": {
        "title": "Home sleep test",
        "seo": "Home sleep test in Monterrey | Dr. Irina González",
        "content": "<p>The home sleep test, or respiratory polygraphy, records over one night the airflow through your nose, your breathing effort, the oxygen level in your blood, snoring, heart rate and the position you sleep in. It is a small device you put on before going to sleep, and Dr. Irina shows you how to use it at the office.</p><p>It is the first-choice test to confirm obstructive sleep apnea in most adults, because it measures what matters on a normal night of your life, not in a laboratory. Dr. Irina interprets it personally and explains the result to you.</p>",
        "meta": {
            "candidatos": ["Snoring with pauses in breathing", "Daytime sleepiness or tiredness", "Before deciding on a snoring or sleep apnea treatment", "To check the result of a treatment (CPAP, surgery, oral appliance)", "High blood pressure that is hard to control, or a previous diagnosis of arrhythmias"],
            "como_se_realiza": "<p>You pick up the device at the office with written instructions, put it on at home at your usual bedtime and return it the next day. Dr. Irina downloads and analyzes the recording and schedules a visit to explain the result and the treatment plan.</p>",
            "enfoque_dra": "<p>Dr. Irina performs and interprets the test herself, without intermediaries: this means the result turns into a concrete plan at the same follow-up visit. And if your case needs an in-lab study, she tells you clearly.</p>",
            "estudio_previo": "<p>An evaluation visit with examination and airway endoscopy. Dr. Irina reviews your sleepiness and risk questionnaires and decides whether the home test is appropriate or whether your case requires an in-lab polysomnography, which she orders when other sleep disorders or associated conditions are suspected.</p>",
            "faq": [
                {"pregunta": "Is it the same as a polysomnography?", "respuesta": "No. Polysomnography is done in a sleep laboratory and records brain activity. The home test is simpler and records breathing; it is enough to diagnose obstructive sleep apnea in most adults."},
                {"pregunta": "Will I sleep normally with the device on?", "respuesta": "The device is light and most people sleep normally. If a night is not representative, the test is repeated."},
                {"pregunta": "What happens after the test?", "respuesta": "Dr. Irina schedules a visit to review the results and propose treatment according to the degree of sleep apnea and what she found on examination."},
            ],
            "recuperacion": "<p>There is no recovery: it is a painless test with no medication. The next day you go about your normal life.</p>",
            "resumen_paciente": "Respiratory polygraphy done in your own bed and interpreted by Dr. Irina herself: it records breathing, oxygen and snoring during the night to confirm and grade sleep apnea.",
            "riesgos_y_alternativas": "<p>It is a safe test. Its limitation is that in some cases the night is not representative (sensors that move, insomnia that night) and it has to be repeated, or that the case requires a full in-lab polysomnography.</p>",
        },
    },
    "cpap": {
        "title": "CPAP",
        "seo": "CPAP for sleep apnea in Monterrey | Dr. Irina González",
        "content": "<p>CPAP is a small machine that delivers air at gentle pressure through a mask while you sleep. That pressure works like an air splint that keeps the airway from closing, eliminates the pauses and the snoring, and finally lets your sleep be restful. It is the most effective treatment for moderate and severe obstructive sleep apnea.</p><p>The challenge with CPAP is not whether it works, but whether you use it. That is why support during the first weeks, choosing the right mask and adjusting the pressure make the difference between a machine stored in the closet and a person who sleeps well again.</p>",
        "meta": {
            "candidatos": ["Obstructive sleep apnea confirmed by a study, moderate or severe", "Mild sleep apnea with significant symptoms or associated conditions", "People who are not candidates for surgery or prefer a non-surgical treatment", "Patients who already use CPAP and can’t adapt: there is a lot that can be adjusted", "CPAP is considered the gold-standard treatment for sleep apnea"],
            "como_se_realiza": "<p>Dr. Irina prescribes the type of machine and mask according to your anatomy and habits, adjusts the pressure (by automatic titration or with a study) and sees you during the first weeks to resolve discomfort: leaks, dryness, pressure that is too high or too low. Modern machines record usage and allow remote adjustment.</p>",
            "enfoque_dra": "<p>Dr. Irina doesn’t just hand over a machine and say goodbye. She supports the adaptation, treats the nose if that is what keeps you from tolerating it and, when after trying it CPAP is not for you, she proposes alternatives with the same honesty.</p>",
            "estudio_previo": "<p>A home sleep test that confirms and grades the sleep apnea, and an examination of the nose and airway: a blocked nose is the most common reason for not tolerating CPAP, and it can be treated.</p>",
            "faq": [
                {"pregunta": "Will I have to use it for life?", "respuesta": "As long as the sleep apnea is present, yes, just like a blood pressure treatment. If the cause changes (weight, surgery), it is re-evaluated with a new study."},
                {"pregunta": "Can I travel with my CPAP?", "respuesta": "Yes. The machines are compact and are allowed in the cabin. Dr. Irina advises you on how to carry it."},
                {"pregunta": "I can’t tolerate the mask. What should I do?", "respuesta": "Don’t give up on it without consulting. Changing the mask, adjusting the pressure or treating the nose solves most adaptation problems."},
            ],
            "recuperacion": "<p>There is no recovery. Adapting usually takes a few days; during that time, close follow-up is part of the treatment. Many people notice a difference in their energy from the first nights of full use.</p>",
            "resumen_paciente": "A machine that keeps the airway open with air pressure during sleep. Dr. Irina prescribes it, adjusts the pressure and provides follow-up so you adapt well and use it consistently.",
            "riesgos_y_alternativas": "<p>CPAP is safe. The usual discomforts are dryness, mask marks or a feeling of pressure, and all of them can be solved with adjustments. Alternatives when it is not tolerated: nasal or palate surgery, a mandibular advancement device or positional therapy, depending on the study and the examination.</p>",
        },
    },
    "cirugia-de-ronquido-y-apnea": {
        "title": "Snoring and sleep apnea surgery",
        "seo": "Snoring & sleep apnea surgery, Monterrey | Dr. Irina González",
        "content": "<p>Snoring and sleep apnea surgery aims to widen and firm up the part of the airway that closes during sleep. It is not a single operation: it can be nasal surgery (septoplasty, turbinoplasty), palate surgery such as <strong>barbed-suture pharyngoplasty</strong>, which repositions and tightens the soft palate without cutting tissue, tonsil removal, or a combination of these.</p><p>The key is choosing well. That is why Dr. Irina performs a drug-induced sleep endoscopy (DISE) in the operating room, under sedation, to see exactly where and how your airway closes while you sleep, and recommends surgery only when the anatomy has a surgical solution.</p>",
        "meta": {
            "candidatos": ["Snoring or sleep apnea with obstruction located in the palate or nose", "People who don’t tolerate CPAP, depending on the evaluation", "Large tonsils that contribute to sleep apnea", "Nasal obstruction that prevents using CPAP or makes it ineffective"],
            "como_se_realiza": "<p>In the hospital, under general anesthesia. Depending on the plan, Dr. Irina corrects the nose, performs the barbed-suture pharyngoplasty and, if applicable, removes the tonsils at the same time. The stay is usually one night.</p>",
            "enfoque_dra": "<p>Dr. Irina has specific training in sleep surgery and is a member of the Ibero-American Society of Sleep Surgery. Her approach is clear: operate on those whose anatomy allows it, and say so honestly to those whose anatomy doesn’t, so that each person gets the treatment that truly works for them.</p>",
            "estudio_previo": "<p>A home sleep test to grade the sleep apnea, examination and endoscopy in the office, and drug-induced sleep endoscopy (DISE) in the operating room to locate the site of obstruction. A CT scan when the nose is part of the plan. Laboratory tests and an anesthesia evaluation.</p>",
            "faq": [
                {"pregunta": "Does surgery cure sleep apnea?", "respuesta": "In well-selected cases, especially with a localized obstruction, it can resolve it or reduce it significantly. In others, it improves snoring and makes CPAP easier to tolerate. Sleep endoscopy helps predict the result."},
                {"pregunta": "What is drug-induced sleep endoscopy (DISE)?", "respuesta": "A short procedure in the operating room in which, under sedation that simulates sleep, Dr. Irina uses an endoscope to see where your airway closes. It is the basis for deciding what to operate on."},
                {"pregunta": "Can I have surgery if I don’t want to use CPAP?", "respuesta": "It depends on your anatomy and the degree of sleep apnea. Dr. Irina tells you clearly whether you are a candidate after the study and the sleep endoscopy."},
            ],
            "recuperacion": "<p>Sore throat for one to two weeks after palate surgery, controlled with pain relievers and a soft diet; congestion after nasal surgery. Dr. Irina sees you for follow-up and, after a few months, repeats the home sleep test to measure the result.</p>",
            "resumen_paciente": "Pharyngoplasty and nasal surgery to widen the airway when the anatomy calls for it. Sleep endoscopy helps locate where it closes.",
            "riesgos_y_alternativas": "<p>Like any surgery, it has risks: bleeding, infection, discomfort from anesthesia, and the possibility that the improvement is partial or that an additional procedure is needed. Dr. Irina explains the specific risks of your case and the alternatives before you decide, and provides written informed consent.</p><p>In sleep apnea surgery, the improvement may be partial and in some cases it is combined with CPAP at a lower pressure or with an oral appliance. Alternatives: CPAP, a mandibular advancement device, positional therapy and weight control.</p>",
        },
    },
}

REL_KEYS = ("relacionados", "tratamientos_relacionados", "que_resuelve")
KEEP_KEYS = ("fuentes", "tipo", "oferta")  # se copian tal cual del español aprobado

out = []
for es_slug, en in FICHAS.items():
    src = approved[es_slug]
    m = dict(en["meta"])
    for k in KEEP_KEYS:
        if "di_" + k in src["meta"]:
            m[k] = src["meta"]["di_" + k]
    missing = [k[3:] for k in src["meta"] if k.startswith("di_") and k[3:] not in m and k[3:] not in REL_KEYS]
    assert not missing, (es_slug, missing)
    rel = {k: [id_to_slug[i] for i in src["meta"].get("di_" + k, []) if i in id_to_slug] for k in REL_KEYS if "di_" + k in src["meta"]}
    out.append({"es_slug": es_slug, "slug": EN_SLUG[es_slug], "post_type": src["post_type"], "title": en["title"], "seo_title": en["seo"],
                "content": en["content"], "meta": m, "relations": rel, "terms": {"area": src["terms"]["area"], "zona": src["terms"]["zona"]}})
    assert len(en["seo"]) <= 62, (es_slug, len(en["seo"]))
(HERE / "en" / "fichas-en.json").write_text(json.dumps(out, ensure_ascii=False, indent=1), encoding="utf-8")
print(len(out), "fichas →", HERE / "en" / "fichas-en.json")
