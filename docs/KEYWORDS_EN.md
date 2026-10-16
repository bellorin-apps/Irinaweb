# KEYWORDS_EN — Keyword map en inglés (PLAN 13.2)

Creado: 2026-10-16 · Claude cloud (D-092, Anexo A). Mapa propio, no calcado del español. **Sin volúmenes ni posiciones inventados** (MASTER_PROMPT §3): las cifras se miden en Search Console después de publicar `/en/`.

**Público (Anexo A):** pacientes de habla inglesa en Monterrey (personas que trabajan en empresas extranjeras y en parques industriales, familias de expatriados, estudiantes) y quienes consultan desde fuera de México.

**Límite antes de redactar 👩‍⚕️:** DISCOVERY tiene «Idiomas de consulta: PENDIENTE». Mientras la Dra. no lo confirme expresamente:
- no se escribe «English-speaking ENT» ni se ofrece consulta en inglés;
- no se menciona turismo médico ni seguros extranjeros (Anexo A).

Las intenciones marcadas con 👩‍⚕️ dependen de esa respuesta.

| Intención | URL propuesta | Consultas objetivo (variantes) | Página ES equivalente | Nota |
|---|---|---|---|---|
| ENT en Monterrey / Cumbres | `/en/` | ENT doctor Monterrey · otolaryngologist Monterrey Mexico · ENT specialist San Pedro Garza García / Cumbres | `/` | Entidad local. GBP y schema con `inLanguage` |
| Médico ENT que hable inglés 👩‍⚕️ | `/en/` y `/en/dr-irina-gonzalez-saez/` | English speaking ENT Monterrey · ENT doctor who speaks English Monterrey | — | Solo si la Dra. confirma que atiende en inglés |
| Conocer a la Dra. | `/en/dr-irina-gonzalez-saez/` | Dr. Irina González Sáez · Irina Gonzalez ENT | `/dra-irina-gonzalez-saez/` | Credenciales con nombres oficiales (GLOSSARY_EN §1) |
| Oído, nariz y garganta | `/en/ent/` (anclas #ear #nose #throat) | ear infection doctor Monterrey · sinus specialist Monterrey · deviated septum Monterrey · tonsils children Monterrey · hearing loss doctor Monterrey | `/otorrinolaringologia/` | Página corta, como en español (D-076) |
| Ronquido y apnea | `/en/sleep/` | sleep apnea specialist Monterrey · snoring treatment Monterrey · sleep apnea doctor Mexico | `/sueno/` | |
| Estudio del sueño | `/en/sleep/home-sleep-test/` | home sleep test Monterrey · sleep study Monterrey | `/sueno/estudio-del-sueno/` | Solo si la Dra. elige traducir esta ficha (13.4) |
| CPAP | `/en/sleep/cpap/` | CPAP Monterrey · CPAP not working alternatives | `/sueno/cpap/` | 13.4 |
| Cirugía de ronquido y apnea | `/en/sleep/snoring-and-sleep-apnea-surgery/` | sleep apnea surgery Mexico · snoring surgery Monterrey | `/sueno/cirugia-de-ronquido-y-apnea/` | 13.4. Sin promesas de eficacia ni precios |
| Apnea / ronquido (fichas) | `/en/sleep/obstructive-sleep-apnea/`, `/en/sleep/snoring/` | obstructive sleep apnea symptoms treatment · why do I snore | `/sueno/apnea-…`, `/sueno/ronquido/` | 13.4 |
| Primera consulta | `/en/first-visit/` | what to expect ENT appointment · ENT consultation cost Monterrey | `/primera-consulta/` | Costos: «se confirman al agendar», igual que en español |
| Contacto y ubicación | `/en/contact/` | ENT near me (desde Monterrey) · CAB Medical Headquarters | `/contacto/` | Mismo NAP (practice.py) |
| FAQ | `/en/faq/` | — | `/preguntas-frecuentes/` | Apoyo, sin keyword propia |
| Legales | `/en/privacy-notice/`, `/en/medical-disclaimer/`, `/en/terms-of-use/` | — | legales | noindex opcional. Nota de prevalencia del español (13.6) |

**SEO técnico (13.5):**
- `hreflang` recíproco es/en y `x-default` → español.
- Title y meta propios, OG por idioma, `inLanguage: en-US` en el schema.
- Selector de idioma visible.
- Mensaje prellenado de WhatsApp en inglés.
- 404 y «gracias» en inglés.
- Sin doorway pages ni páginas por colonia (§14).
