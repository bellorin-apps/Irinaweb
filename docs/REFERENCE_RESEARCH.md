# REFERENCE_RESEARCH — Referentes de diseño y UX para el sitio de la Dra. Irina González Sáez

Fecha: 2026-10-07 · Tarea PLAN 2.1 · Responsable: Claude · Estado: **completo con limitaciones de red** (ver §0.2)

Propósito: extraer **patrones** (no diseños) de sitios médicos de referencia para fundamentar la arquitectura, el sistema de contenido y la conversión del sitio de la Dra. Irina (otorrinolaringólogo, desórdenes respiratorios del dormir, ronquido y apnea obstructiva del sueño; consulta privada en Monterrey; WordPress + child theme; CTA principal WhatsApp según D-011). El mandato prohíbe copiar diseños: todo lo que aquí se recomienda es un principio o una estructura, nunca una réplica visual.

---

## 0. Alcance, método y limitaciones

### 0.1 Método

1. Búsqueda web (WebSearch) para localizar las páginas objetivo y sus subpáginas (centros, programas, perfiles de médicos, biblioteca de salud, premios).
2. Intento de lectura directa (WebFetch) de cada página.
3. Cuando la lectura directa fue bloqueada, se reconstruyó la estructura **únicamente** a partir de los extractos de búsqueda (títulos, descripciones, slugs de URL y fragmentos indexados) y de fuentes secundarias (notas de prensa, reseñas, directorios). Nada de lo que se afirma sobre el contenido de una página proviene de suposición; cuando algo es inferencia se marca como tal.

### 0.2 Limitación principal: bloqueo de red (EGRESS_BLOCKED)

El proxy del entorno bloqueó la lectura directa de **todos** los dominios intentados. Lista completa en el Anexo A. Consecuencias:

- No se pudo verificar la experiencia móvil real, micro-interacciones, tiempos de carga ni el flujo de agenda clic a clic de ningún referente. Esas observaciones aparecen como "no verificable" o se omiten.
- Lo que sí es sólido: arquitectura de información (visible en slugs y títulos indexados), bloques de contenido descritos en los extractos, políticas editoriales (fechas de revisión, firma de autor/revisor), señales de confianza y canales de contacto publicados.
- Recomendación: en cuanto haya un entorno con red abierta (o el propietario abra las páginas en su navegador), hacer una **pasada de verificación visual** de 30 minutos sobre los 6 referentes principales usando la lista de comprobación del Anexo B.

### 0.3 Leyenda de evidencia

- **[S]** = confirmado por extractos de búsqueda o fuente secundaria citada.
- **[I]** = inferencia razonable a partir de la arquitectura visible; verificar en la pasada visual.

---

## 1. Fichas de referencia

### 1.1 Stanford Health Care — Sleep Medicine Center y Sleep Surgery Program

**URLs**
- Centro: https://stanfordhealthcare.org/medical-clinics/sleep-medicine-center.html
- Programa quirúrgico: https://stanfordhealthcare.org/medical-clinics/sleep-surgery-program.html
- Consulta con especialista (qué esperar): https://stanfordhealthcare.org/medical-tests/s/sleep-disorder-tests/procedures/consultation.html
- Perfil médico (ejemplo): https://stanfordhealthcare.org/doctors/c/robson-capasso.html

**Qué funciona**
- **Separación limpia entre "medicina del sueño" y "cirugía del sueño"** como dos entidades que se enlazan mutuamente [S]. El programa quirúrgico se presenta dentro de ORL (Ear, Nose and Throat) y remite al centro de sueño para diagnóstico; el mensaje es "trabajamos juntos", no "competimos".
- **Autoridad por historia y por datos**: "primera clínica de sueño del mundo (1972)", "más de 35 años operando trastornos del sueño", ranking de U.S. News para ORL [S]. El dato es concreto y verificable, no adjetivos.
- **Lista explícita de condiciones** con vocabulario clínico y coloquial en la misma línea (apnea obstructiva, apnea central, ronquido, UARS, hipoventilación) [S]. Permite que el paciente se reconozca aunque no sepa el nombre técnico.
- **Página "qué esperar en la consulta"**: exploración breve + revisión de un **cuestionario que el paciente contesta antes de la cita** + plan de tratamiento conjunto [S]. Reduce ansiedad y filtra información útil antes de la visita.
- **Teléfonos diferenciados**: línea 24/7 y línea de citas [S]. Dos canales con propósito claro.
- **Perfil médico con secciones estables**: enfoque clínico, educación y certificaciones, **calificación de pacientes con número de reseñas**, idiomas adicionales, botón de cita [S]. En el caso Capasso: cargos, fellowships con año, certificación de consejo (American Board of Otolaryngology), idiomas (portugués, español) [S].
- **Contenido de divulgación con el propio médico** (videos del Dr. Kushida sobre apnea y horario de verano; historia de paciente "Seeking Peaceful Sleep") [S]: la autoridad se construye con material propio, no sólo con credenciales.

**Qué no funciona (para nuestro caso)**
- Es un sitio de sistema hospitalario: navegación amplia, muchas capas (clínica → programa → médico → ubicación). Para una consulta unipersonal eso es ruido [I].
- La DISE (endoscopia de sueño inducida) aparece como "lo usamos" sin explicar al paciente qué sentirá [S]. Nosotros debemos explicarlo.
- Las calificaciones de pacientes dependen de un sistema institucional (encuestas Press Ganey); no es replicable y no debe imitarse con estrellas sin fuente.

**Qué adaptamos (como patrón)**
- Dos pilares enlazados: **ORL general** y **Sueño** (nuestra "sleep experience"), cada uno con su listado de condiciones y su CTA, y una página de "cómo trabajamos juntas la vía aérea" que une ambos.
- Página **"Tu primera consulta"** con pasos, duración, qué llevar y un **cuestionario previo** (Epworth, escala de ronquido, STOP-Bang) que se puede contestar en el sitio o enviar por WhatsApp.
- Perfil con secciones fijas y verificables: enfoque clínico, formación con años, certificación de consejo con número cuando exista, idiomas, afiliaciones.
- Divulgación en primera persona (video corto, artículo firmado) como señal de autoridad.

**Qué evitar**
- Jerarquías de más de tres niveles.
- Afirmaciones de "primeros" o "mejores" sin documento que lo respalde.
- Estrellas o calificaciones sin fuente verificable.

---

### 1.2 Mayo Clinic — Sleep Medicine, páginas de condición y biografías

**URLs**
- Departamento: https://www.mayoclinic.org/departments-centers/sleep-medicine/sections/overview/ovc-20407454
- Sleep Surgery Clinic: https://www.mayoclinic.org/departments-centers/sleep-surgery-clinic/overview/ovc-20469499
- Condición (pestañas): https://www.mayoclinic.org/diseases-conditions/obstructive-sleep-apnea/symptoms-causes/syc-20352090 · …/diagnosis-treatment/drc-… · …/doctors-departments/ddc-20352097 · …/care-at-mayo-clinic/mac-20352098
- Biografía (ejemplo): https://www.mayoclinic.org/biographies/olson-michael-d-m-d-m-s/bio-20435525
- Editores médicos: https://www.mayoclinic.org/about-this-site/meet-our-medical-editors

**Qué funciona**
- **Modelo de página de condición en cuatro pestañas** [S, visible en los slugs]: (1) síntomas y causas, (2) diagnóstico y tratamiento, (3) médicos y departamentos, (4) atención en Mayo Clinic. Separa la educación neutral del "por qué aquí", y eso protege la credibilidad del contenido educativo.
- **"Care at Mayo Clinic" con subsecciones fijas**: experiencia y rankings con cifras ("más de 57,000 personas atendidas por AOS al año"), ubicaciones, costos y seguros, ensayos clínicos [S]. Es el bloque de conversión, pero sigue siendo informativo.
- **Equipo multidisciplinario explicado para el paciente**: quién es cada especialista (neumólogo, neurólogo, ORL, dentista, cirujano maxilofacial) y cuándo interviene [S]. Educa sobre el "mapa" de la apnea.
- **Promesas operativas concretas**: evaluación, pruebas y plan en menos de 48 horas; ~30 % de visitas virtuales; no se requiere referencia médica en la mayoría de los casos [S]. Son argumentos de conveniencia, no de ego.
- **Acreditación externa** (AASM) en todas las sedes [S].
- **Biografía con estructura fija** [S]: resumen biográfico, intereses, educación (con años), actividades y honores (certificaciones, premios), membresías profesionales (con fechas y cargos), publicaciones (con enlace a PubMed). Es el estándar de "página de entidad" de un médico.
- **Página "Meet our medical editors"** [S]: la revisión médica tiene cara y nombre.

**Qué no funciona (para nuestro caso)**
- Texto largo y uniforme; sin fotografía del médico en contexto ni vídeo en la biografía [I].
- Escala y rankings no replicables; copiar el tono "somos los más grandes" sería falso para una consulta privada.
- El bloque de costos y seguros es vago ("algunas aseguradoras requieren referencia"); en México el paciente quiere saber si se acepta su seguro y cuánto cuesta la primera consulta.

**Qué adaptamos**
- Plantilla de condición con **bloques fijos en una sola página** (no pestañas): qué es / síntomas / causas y factores de riesgo / diagnóstico / tratamientos (quirúrgicos y no quirúrgicos) / cuándo consultar / cómo lo abordamos en consulta / preguntas frecuentes / fuentes y revisión. El bloque "cómo lo abordamos" es nuestro equivalente a "Care at Mayo Clinic".
- Biografía con **secciones canónicas** y enlaces a evidencia (artículo en International Journal of Head and Neck Surgery, consejo, sociedad iberoamericana).
- Un bloque "Quién revisa este contenido" con foto y credenciales, equivalente a "Meet our medical editors", en una sola persona.

**Qué evitar**
- Pestañas que esconden contenido en móvil.
- Afirmaciones de volumen ("miles de pacientes") sin registro.
- Bloques de "costos y seguros" sin información real.

---

### 1.3 Cleveland Clinic — Sleep Disorders Center, Surgical Sleep & Snoring, Health Library y perfiles

**URLs**
- Sleep Disorders Center: https://my.clevelandclinic.org/departments/neurological/depts/sleep-disorders
- Cirugía de sueño y ronquido (ORL): https://my.clevelandclinic.org/departments/head-neck/depts/surgical-sleep-snoring
- Servicio "Obstructive Sleep Apnea Treatment": https://my.clevelandclinic.org/services/obstructive-sleep-apnea-treatment
- Health Library: https://my.clevelandclinic.org/health/diseases/8718-sleep-apnea · https://my.clevelandclinic.org/health/diseases/15580-snoring · https://my.clevelandclinic.org/health/articles/apnea-hypopnea-index-ahi · https://my.clevelandclinic.org/health/treatments/22043-cpap-machine · https://my.clevelandclinic.org/health/treatments/25059-uvulopalatopharyngoplasty-uppp
- Política editorial: https://my.clevelandclinic.org/health/about
- Perfil (ejemplo): https://providers.clevelandclinic.org/provider/alan-kominsky/4269002
- Historia de paciente: https://my.clevelandclinic.org/patient-stories/738-unmasking-the-benefits-of-inspire-vs-cpap-helps-medina-woman

**Qué funciona**
- **Biblioteca de salud con sello editorial visible**: "Medically Reviewed. Last updated on MM/DD/YYYY" en cada artículo, referencias al pie **después** de la fecha de revisión, y una página pública que explica quién escribe (investigadores + periodistas) y qué fuentes se aceptan (revistas revisadas por pares, textos médicos, organismos oficiales) [S]. Ejemplos indexados: apnea del sueño actualizado 15-ene-2025; AOS 04-dic-2024; AHI 21-feb-2025; CPAP 11-jul-2024; ronquido revisado 04-jun-2026 [S].
- **Granularidad temática**: una página por concepto (AHI, CPAP, UPPP, implante de nervio hipogloso, apnea infantil, PAP, ASV) con títulos tipo "qué es, cómo funciona, efectos secundarios" [S]. Cubre la intención de búsqueda completa del paciente.
- **Antigüedad y acreditación como prueba**: centro desde 1978, "entre los primeros del país", acreditación AASM, equipo de 8 especialidades [S].
- **Sección quirúrgica con un responsable visible** (Dr. Kominsky, "Section Head of Surgical Sleep and Snoring", primer cirujano entrenado en Inspire del sistema) [S]: la cirugía de sueño tiene nombre y apellido.
- **Historia de paciente con título que resume el beneficio** ("Inspire vs CPAP…") [S]: narrativa, no testimonio genérico.
- **Perfil con bloques operativos**: departamento, sede principal, tipo de médico, idiomas, sedes con teléfono de citas y teléfono de escritorio, especialidades y tratamientos, calificación Press Ganey con número de reseñas, "Education & Professional Highlights", seguros aceptados [S].
- **Teléfonos con propósito** (línea de citas 24 h y línea del instituto) y visitas virtuales anunciadas [S].

**Qué no funciona (para nuestro caso)**
- Volumen de contenido imposible de mantener para una persona; el riesgo de imitar la biblioteca es publicar 40 artículos y no poder revisarlos cada año.
- Perfiles orientados a sistema (seguros, varias sedes): si lo copiamos tendremos secciones vacías.
- Las historias de paciente requieren consentimiento y, en México, cuidado con la publicidad médica; no replicar sin revisión legal (ver D-012).

**Qué adaptamos**
- **Sello editorial en cada página clínica**: "Contenido médico revisado por la Dra. Irina González Sáez · Última revisión: fecha · Fuentes" con política editorial pública en `/politica-editorial/`.
- **Biblioteca pequeña y profunda**: 10 a 15 páginas de glosario y tratamiento (IAH, CPAP, DAM, faringoplastia, endoscopia de sueño, septoplastia, amígdalas en niños) en lugar de un blog amplio.
- Un **calendario de revisión** anual con fecha visible; mejor pocas páginas al día que muchas vencidas.

**Qué evitar**
- Publicar sin fecha de revisión.
- Testimonios transcritos o editados (D-012).
- Secciones de perfil que no aplican (seguros múltiples, varias sedes).

---

### 1.4 Johns Hopkins Medicine — Otolaryngology, Center for Snoring and Sleep Surgery, perfiles y biblioteca

**URLs**
- Departamento: https://www.hopkinsmedicine.org/otolaryngology/ · Áreas: https://www.hopkinsmedicine.org/otolaryngology/specialty-areas · Sedes: https://www.hopkinsmedicine.org/otolaryngology/locations
- Centro de ronquido y cirugía de sueño: https://www.hopkinsmedicine.org/otolaryngology/specialty-areas/snoring-sleep-surgery
- Perfil (ejemplo): https://profiles.hopkinsmedicine.org/provider/kevin-m-motz/2706104
- Biblioteca: https://www.hopkinsmedicine.org/health/conditions-and-diseases/obstructive-sleep-apnea · https://www.hopkinsmedicine.org/health/conditions-and-diseases/snoring · https://www.hopkinsmedicine.org/health/wellness-and-prevention/4-signs-you-might-have-sleep-apnea · https://www.hopkinsmedicine.org/health/conditions-and-diseases/obstructive-sleep-apnea/hypoglossal-nerve-stimulation
- Video: https://www.hopkinsmedicine.org/video/hypoglossal-nerve-stimulation-and-the-treatment-of-sleep-apnea

**Qué funciona**
- **Arquitectura departamento → áreas de especialidad → centro específico** [S]: el "Center for Snoring and Sleep Surgery" es una landing propia dentro de ORL, con posicionamiento claro: "atención colaborativa y multidisciplinaria para apnea obstructiva y ronquido **que no han respondido al tratamiento médico**" [S]. Define a quién sirve y a quién no.
- **Explicación del camino clínico en lenguaje de paciente**: si eres candidato a estimulación del hipogloso, el siguiente paso es una endoscopia de sueño inducida, "prueba no invasiva, dormido bajo anestesia, unos 30 minutos" [S]. Convierte un procedimiento intimidante en un paso concreto.
- **Perfil con rol institucional + enfoque clínico + formación con años + sede + teléfono + "Request an Appointment"** [S]; calificaciones con metodología explicada (CG-CAHPS vía Press Ganey, escala 1-5) [S]; seguros, idiomas, intereses de investigación [S].
- **Gancho estadístico al inicio del artículo** ("hasta 9 de cada 10 personas con AOS no saben que la tienen") [S] y artículos de "prevención y bienestar" con títulos de síntoma ("4 señales de que podrías tener apnea") [S]: buena puerta de entrada desde búsqueda.
- **Vídeo explicativo del tratamiento** enlazado desde la biblioteca [S].

**Qué no funciona (para nuestro caso)**
- Según los extractos, las áreas de especialidad del departamento se enumeran como "audición, implante coclear, cáncer de cabeza y cuello, senos paranasales, voz" [S]; el sueño queda un nivel abajo. Para nosotros el sueño es la diferenciación y debe estar en el nivel 1.
- Perfil con mucha carga institucional (cargos, docencia) y poca narrativa personal [I].

**Qué adaptamos**
- Landing "Ronquido y apnea" con **criterio de inclusión explícito**: para quién es (ronca, CPAP no tolerado, pareja preocupada, niño que ronca) y qué pasa cuando no es nuestro caso (referencia a neumología/neurología/dental).
- **"Siguiente paso" explicado** en cada tratamiento: qué estudio se hace antes, cuánto dura, si hay anestesia, cuándo se vuelve a casa.
- Artículos de entrada con **títulos de síntoma** y un gancho de dato con fuente.

**Qué evitar**
- Enterrar la subespecialidad bajo "Servicios".
- Describir un procedimiento sin decir qué sentirá el paciente ni el tiempo de recuperación.

---

### 1.5 Sleep Doctor / Dr. Michael Breus

**URLs**
- Inicio: https://sleepdoctor.com/ · Perfil: https://sleepdoctor.com/pages/dr-michael-breus · Equipo clínico: https://sleepdoctor.com/pages/clinical-care-team · "Why Sleep Doctor": https://sleepdoctor.com/pages/why-sleep-doctor
- Hub de apnea: https://sleepdoctor.com/pages/sleep-apnea · subpáginas: …/sleep-apnea/symptoms · …/sleep-apnea/treatment · …/sleep-apnea/cpap-alternatives · …/sleep-apnea/solutions-by-severity · …/sleep-apnea/mild-sleep-apnea · …/sleep-apnea/obstructive-sleep-apnea · …/sleep-apnea/central-sleep-apnea
- Ronquido: https://sleepdoctor.com/pages/snoring/how-to-stop-snoring
- Quiz de cronotipo: https://sleepdoctor.com/pages/chronotypes/chronotype-quiz
- Metodología de reseñas: https://sleepdoctor.com/pages/reviews/methodology
- Reseñas externas del producto: https://sleepfoundation.org/sleep-studies/sleep-doctor-home-sleep-test-review · https://ncoa.org/adviser/sleep/sleep-doctor-at-home-sleep-apnea-test-review

**Qué funciona**
- **Marca personal construida sobre una credencial específica y un diferencial memorable**: psicólogo clínico, Diplomado del American Board of Sleep Medicine, Fellow de la AASM, "uno de 168 psicólogos en el mundo que aprobó los boards de sueño sin ir a la escuela de medicina", 25+ años [S]. La credencial se cuenta como historia, no como lista.
- **Hub temático con subpáginas por intención** (síntomas, tratamiento, alternativas al CPAP, soluciones por severidad, apnea leve, central) [S]: cubre el embudo completo desde "¿ronco mucho?" hasta "no tolero el CPAP".
- **Firma editorial en cada página**: "escrito por / revisado por Michael J. Breus, PhD" y fecha de actualización visible (p. ej. hub de apnea 12-nov-2025; síntomas 05-dic-2025; alternativas al CPAP 21-sep-2026) [S].
- **Herramienta interactiva propia** (quiz de cronotipo con cuatro arquetipos: león, oso, lobo, delfín) y "conoce tu riesgo de apnea" [S]: la interacción genera recuerdo y datos.
- **Producto con servicio clínico**: prueba de sueño en casa (WatchPAT ONE) que incluye informe personalizado y dos consultas virtuales con un médico de sueño [S]. Cierra el ciclo "aprende → evalúate → consulta".

**Qué no funciona (para nuestro caso)**
- **Mezcla de divulgación y comercio**: tienda, programa de 28 días, libros, reseñas de productos con comisiones de afiliados (declaradas por terceros que lo reseñan) [S]. Para un médico con cédula en México, vender productos junto a contenido clínico erosiona la confianza y puede chocar con la regulación de publicidad sanitaria.
- Las reseñas externas señalan que la prueba en casa es menos precisa que el estudio en laboratorio y tiene riesgo de error de uso [S]; el sitio vende conveniencia donde conviene matizar.
- Es un sitio de contenido masivo (SEO) con equipo editorial; no es replicable por una persona.

**Qué adaptamos**
- **Narrativa de credencial**: contar la formación como trayectoria (Venezuela → Monterrey → subespecialidad en desórdenes respiratorios del dormir → consejo mexicano → sociedad iberoamericana) con un diferencial preciso y verdadero, no un superlativo.
- **Hub de sueño con subpáginas por intención**, incluida "No tolero el CPAP: alternativas" y "Apnea leve: qué hacer".
- **Una herramienta interactiva sobria**: autoevaluación de ronquido/apnea (STOP-Bang o Epworth) cuyo resultado termina en un CTA de WhatsApp con el resultado prellenado, sin diagnóstico.
- Fecha de actualización y revisor en cada página.

**Qué evitar**
- Tienda, afiliados, "programas" de pago junto al contenido clínico.
- Promesas de diagnóstico por cuestionario; el resultado siempre es "conviene valorarlo en consulta".
- Tono de gurú.

---

### 1.6 Consultas privadas de ORL y cirugía de sueño

#### 1.6.1 Sleep Apnea Surgery Center — Dr. Kasey Li (Palo Alto, EE. UU.)

**URLs**: https://sleepapneasurgery.com/ · https://sleepapneasurgery.com/about-dr-kasey-li/ · https://sleepapneasurgery.com/patient-experiences/ · https://sleepapneasurgery.com/patient-experiences-maxillomandibular-advancement/ · https://sleepapneasurgery.com/patient-experiences-ease-tpd/ · https://sleepapneasurgery.com/research-publications/

**Qué funciona**
- **Posicionamiento ultra específico y verificable**: triple certificación de consejo (ORL, cirugía maxilofacial, cirugía plástica facial) presentada como hecho singular [S]; colaboración de dos décadas con Christian Guilleminault reconocida por una sociedad científica [S].
- **Testimonios organizados por procedimiento** (avance maxilomandibular, EASE/expansión) [S], no una página genérica de "opiniones". El paciente que busca un procedimiento encuentra a sus iguales.
- **Página de publicaciones con PDF descargables** [S]: la evidencia está a un clic.
- **Tono de consulta sin presión** reflejado en los testimonios ("nunca insistente", "anima a consultar con otros cirujanos y con la familia") [S]: la confianza se demuestra con la actitud, no con slogans.

**Qué no funciona (para nuestro caso)**
- Sitio en WordPress con estética aparentemente datada (PDFs alojados en `wp-content/uploads/2023`, estructura de páginas larga) [I]; verificar en pasada visual.
- Testimonios extensos en texto: en México deben pasar revisión de publicidad médica y consentimiento (D-012); el widget de Doctoralia es el camino acordado.

**Qué adaptamos**
- Un **diferencial verificable y específico** (subespecialidad en desórdenes respiratorios del dormir, miembro fundador de la Sociedad Iberoamericana de Cirugía de Sueño, autoría en revista indexada) en el hero de la página de la Dra. y en la landing de sueño.
- **Página "Investigación y docencia"** con la publicación (enlace/DOI), conferencias dictadas y cursos, como prueba de experiencia.
- Tono "sin presión" en microcopy: "si tu caso no es quirúrgico, te lo diré".

**Qué evitar**
- Testimonios transcritos. Páginas de texto largo sin jerarquía visual.

#### 1.6.2 Melbourne Sleep Surgery — Mr Nathan Hayward (Melbourne, Australia)

**URLs**: https://melbournesleepsurgery.com.au/ · https://melbournesleepsurgery.com.au/mr-nathan-hayward/ · https://melbournesleepsurgery.com.au/patient-information/ · https://melbournesleepsurgery.com.au/surgery-for-sleep-apnea/ · https://melbournesleepsurgery.com.au/uppp/ · https://melbournesleepsurgery.com.au/sleep-apnea-in-children/ · https://melbournesleepsurgery.com.au/sleep-study-melbourne/ · https://melbournesleepsurgery.com.au/fess/ · https://melbournesleepsurgery.com.au/locations/blackburn/

**Qué funciona**
- **Sub-marca de sueño con dominio propio** de un ORL que además ejerce ORL general en un grupo (ENT Specialists Group) [S]. Es el caso más parecido al nuestro: un cirujano, una subespecialidad, una marca enfocada.
- **Posicionamiento por escasez verificable**: "uno de los pocos cirujanos ORL en Australia con formación post-fellowship en cirugía de sueño; el único en Melbourne" [S].
- **Una página por procedimiento** (UPPP/faringoplastia modificada, FESS, cirugía de apnea en adultos, apnea en niños, estudio de sueño, DISE) [S] con explicación clínica directa (p. ej. "la amigdalectomía con faringoplastia es la base de la cirugía de paladar"; "en niños, amígdalas y adenoides resuelven la AOS en ~90 %") [S].
- **Página de información para pacientes nuevos** que pide **tres cuestionarios antes de la cita** (Snoring Severity Scale, Epworth, FOSQ) [S]: profesionaliza la primera consulta y ahorra tiempo.
- **Páginas por ubicación** con intención local ("Obstructive Sleep Apnoea Surgery – Blackburn", "Treatment Morwell") [S].
- Teléfono y dirección de la sede principal en los extractos [S].

**Qué no funciona (para nuestro caso)**
- Todos los extractos muestran teléfono como único canal; no aparece agenda en línea ni mensajería [S]. En Monterrey el canal es WhatsApp.
- El nombre "Melbourne Sleep Surgery" encierra al médico en "cirugía"; la Dra. también ofrece manejo no quirúrgico (CPAP, DAM, rinología) y debe comunicarlo.

**Qué adaptamos**
- Sección de sueño con **nombre propio dentro del mismo dominio** (no dominio aparte), con una página por tratamiento y una por estudio diagnóstico.
- **Cuestionarios previos** integrados en "Tu primera consulta" y enviables por WhatsApp.
- Página de ubicación con intención local (Cumbres / Monterrey / San Pedro) sin crear páginas falsas de ciudades donde no se atiende.

**Qué evitar**
- Dominio separado para la sub-marca (divide autoridad y mantenimiento).
- Encerrar la marca en "cirugía".

#### 1.6.3 Dr. Carlos O'Connor Reina — Unidad de ronquido y apnea, Quirónsalud Marbella (España)

**URLs**: https://www.quironsalud.com/marbella/es/cuadro-medico/carlos-connor-reina · https://www.quironsalud.com/marbella/es/cartera-servicios/otorrinolaringologia/ronquido-apnea · https://www.quironsalud.com/es/comunicacion/actualidad/terapia-miofuncional-forma-abordar-apnea-sueno-doctor-oconn

**Qué funciona**
- **Perfil en español con credenciales jerarquizadas**: doctorado (Sevilla, 2002), acreditación como experto en sueño por la SES y la ESRS, presidencia de la Comisión de Ronquido y Apnea de la SEORL-CCC, premio "Expert Somnologist" (ESRS 2016), más de 50 publicaciones indexadas, ponente habitual [S]. Modelo de cómo ordenar credenciales de mayor a menor peso en nuestro idioma.
- **Enfoque multidisciplinario explicado**: cirugía + terapia miofuncional + nuevas tecnologías [S]; la unidad se presenta como "pionera en España" y "centro de referencia nacional en terapia miofuncional" con el matiz "alternativas no quirúrgicas ni con CPAP" [S]. Vende opciones, no una sola solución.
- **Divulgación propia** (libro, notas de prensa, webinars de cátedra de investigación) [S].

**Qué no funciona (para nuestro caso)**
- Es una ficha dentro de un portal hospitalario: sin narrativa personal, sin fotografía de contexto, CTA genérico del hospital [I]. El médico no controla su "página de entidad".
- Documentación en PDF (cartera de servicios) [S]: contenido no indexable ni accesible en móvil.

**Qué adaptamos**
- **Orden de credenciales** para la Dra.: certificación del Consejo Mexicano de ORL y CCC → subespecialidad en desórdenes respiratorios del dormir (ISSSTE Monterrey, 2018) → especialidad (UCLA Barquisimeto, 2018) → membresías (Sociedad Iberoamericana de Cirugía de Sueño, fundadora) → publicación y conferencias. Todo sujeto a confirmación documental (DISCOVERY §1.1).
- Narrativa de "opciones, no una solución": CPAP, dispositivo de avance mandibular, cirugía nasal, faringoplastia, terapia miofuncional/posicional cuando aplique.

**Qué evitar**
- Depender de un portal de terceros como única página de entidad.
- Contenido clave en PDF.

#### 1.6.4 Mr Ryan Chin Taw Cheong — cirujano de sueño, Londres (Reino Unido) — contra-ejemplo de dependencia de directorios

**URLs**: https://topdoctors.co.uk/doctor/ryan-chin-taw-cheong · https://clevelandcliniclondon.uk/doctors/7419156-mr-ryan-chin-taw-cheong · https://onewelbeck.com/consultants/mr-ryan-chin-taw-cheong · https://www.phin.org.uk/profiles/consultants/ryan-chin-taw-cheong-340189

**Observación**: un cirujano con méritos muy específicos (única práctica NHS a tiempo completo dedicada a ronquido y AOS; primer implante Inspire del Reino Unido en una paciente; fundador del International Sleep Surgery Course) [S] cuya presencia digital, según los resultados, se reparte entre **cinco directorios y portales hospitalarios** y no muestra sitio propio en los primeros resultados. Las credenciales se repiten con ligeras variaciones y el paciente no tiene una fuente canónica.

**Lección**: la Dra. ya aparece en Doctoralia y TopDoctors (DISCOVERY §1.3; el extracto de Doctoralia muestra incluso "cirugía de ronquido desde $2,700" [S]). El sitio propio debe ser la **fuente canónica** (schema `Physician` + `sameAs` hacia los directorios), con credenciales idénticas en todos los perfiles, y debe controlar la narrativa de precios (decisión del propietario) en lugar de dejar que la fije el directorio.

---

### 1.7 Referentes premiados de UX en salud

#### 1.7.1 Clinic Beethovenstrasse — Dra. Colette Camenisch (Zúrich) — CSS Design Awards: Website of the Day, Best UI, Best UX, Best Innovation

**URLs**: https://www.clinic-beethovenstrasse.ch/ · ficha: https://webflow.com/made-in-webflow/website/clinic-beethovenstrasse

**Qué se documenta** [S]: sitio de una clínica dirigida por una médica, reconocido por CSSDA en cuatro categorías; la descripción destaca una experiencia "fluida y sobria a pesar de contener gran cantidad de información" y la implicación directa de la médica en la estética.

**Patrón**: densidad de información clínica **no** riñe con sobriedad si (a) la jerarquía tipográfica es estricta, (b) cada pantalla responde una sola pregunta, (c) la fotografía real sustituye a la iconografía genérica. La implicación personal de la médica en la dirección de arte es parte del resultado, lo cual coincide con nuestra decisión de sesión fotográfica propia (PHOTO_SHOTLIST).

**Qué evitar**: animaciones pesadas que castiguen el móvil (no verificable aquí; medir con Lighthouse si se toma como inspiración).

#### 1.7.2 Longstreet Clinic (Georgia, EE. UU.) — W3 Awards (plata, Healthcare Services)

**URLs**: https://www.longstreetclinic.com/clinic-website-honored-with-international-award/ · caso: https://www.forumspeaks.com/case-study/longstreet-clinic-website/

**Qué se documenta** [S]: la agencia encuestó a pacientes y médicos y construyó el sitio alrededor de **las tres preguntas que más hacían los pacientes**: qué especialidades hay, quiénes son los médicos y dónde están. El sitio anterior no era móvil y la mayoría del tráfico era móvil; el rediseño fue mobile-first con navegación móvil y SEO.

**Patrón**: definir las **tres preguntas del paciente** y hacer que la home las responda en el primer scroll. Para nosotros: (1) ¿qué trata la Dra. y puede ayudarme con mi ronquido/apnea?, (2) ¿quién es y por qué confiar?, (3) ¿dónde está, cuánto cuesta y cómo agendo? Todo lo demás es secundario.

#### 1.7.3 Praxis Dr. Natalie Herzel (Hamburgo) — galería de Awwwards

**URLs**: https://www.awwwards.com/inspiration/photos-natalie-herzel-dental-clinic · caso: https://silver-motorcycle-bf6.notion.site/Comprehensive-website-redesign-for-a-dental-clinic-in-Germany-Natalie-Herzel-Praxis-1213939dad548102830edc4ed780a864

**Qué se documenta** [S]: rediseño integral de una clínica unipersonal con objetivos explícitos: que el paciente **encuentre el servicio y agende**, atraer y retener pacientes, reforzar imagen; navegación simple y selector de idioma; páginas de servicios; galería con desktop, móvil, fotos, menú completo y 404 diseñada.

**Patrón**: una clínica pequeña puede tener nivel de diseño de galería si limita el alcance (servicios + médico + cita) y cuida piezas que otros descuidan (menú completo, 404, fotografía). El selector de idioma es relevante si se decide una versión en inglés para pacientes extranjeros en Monterrey (decisión del propietario, no asumida).

#### 1.7.4 eHealthcare Leadership Awards 2025 — "Best Site Design"

**URLs**: https://ehealthcareawards.com/2025-winners/best-site-design/ · https://ehealthcareawards.com/2025-ehealthcare-leadership-award-winners-announced/

**Qué se documenta** [S]: entre los platinos, UNC Health (con Huge) con el título de la candidatura "A Trusted, Patient-First Digital Experience – Built for Clarity, Credibility and Care"; Ohio State Wexner; Baton Rouge General con "Where Form Meets Function". El vocabulario premiado en 2025 es **claridad, credibilidad, cuidado y utilidad**, no espectáculo visual.

---

### 1.8 Contexto competitivo local (directorios)

- Doctoralia y TopDoctors dominan las búsquedas "otorrinolaringólogo Monterrey ronquido/apnea" [S]. Las fichas muestran: sede, consultorio, precio de primera consulta o de servicio, opiniones y botón de cita.
- Implicación: el sitio propio compite en la SERP con páginas de directorio muy optimizadas. Debe ganar en **especificidad** (una página por condición y tratamiento), **autoridad verificable** (credenciales con documentos, publicación, consejo) y **señales locales** (GBP, dirección, mapa, cómo llegar, estacionamiento) que el directorio no tiene.
- La tarea PLAN 2.2 (competencia local) debe revisar visualmente a los competidores de Monterrey cuando la red lo permita.

---

## 2. Síntesis: patrones transversales para nuestro sitio

1. **Dos pilares, una vía aérea.** ORL general y Sueño como dos entradas de nivel 1 que se enlazan entre sí (Stanford, Hopkins, Melbourne). La sub-marca de sueño vive dentro del mismo dominio.
2. **Las tres preguntas del paciente en el primer scroll** de la home: qué trata, quién es, cómo agendo/dónde (Longstreet).
3. **Página de condición con bloques fijos**: qué es, síntomas, causas, diagnóstico, tratamientos (no quirúrgicos y quirúrgicos), cuándo consultar, cómo lo abordamos, FAQ, fuentes y revisión (Mayo, Cleveland).
4. **Una página por tratamiento y por estudio** con "qué sentirás, cuánto dura, anestesia, recuperación, siguiente paso" (Hopkins, Melbourne, Cleveland).
5. **Criterio de inclusión explícito** en la landing de sueño: para quién es y a quién se refiere a otra especialidad (Hopkins).
6. **Credenciales jerarquizadas y verificables**, contadas como trayectoria y no como lista; cada afirmación con documento detrás (Mayo bio, O'Connor, Breus, Li).
7. **Sello editorial en toda página clínica**: autor/revisor con credenciales, fecha de última revisión, fuentes al pie, política editorial pública (Cleveland, Sleep Doctor).
8. **"Qué esperar en la primera consulta"** con cuestionarios previos (Epworth, escala de ronquido, STOP-Bang) y lista de qué llevar (Stanford, Melbourne).
9. **Opciones, no una solución**: CPAP, DAM, cirugía nasal, faringoplastia, terapia posicional/miofuncional según el caso; texto que diga cuándo no se opera (O'Connor, Mayo, testimonios de Li).
10. **Divulgación en primera persona** (video corto, artículo firmado) como señal de experiencia, con un gancho de dato citado (Stanford, Hopkins).
11. **Una herramienta interactiva sobria** de autoevaluación que termina en WhatsApp con el resultado prellenado y sin diagnóstico (Sleep Doctor, adaptado).
12. **Canales con propósito**: WhatsApp (principal), teléfono del consultorio, Doctoralia (opiniones y cita alternativa); nunca más de tres, nunca el de urgencias (D-011).
13. **Señales locales fuertes**: dirección completa, mapa, piso y consultorio, cómo llegar, estacionamiento, fotografía de fachada y recepción (PHOTO_SHOTLIST 9–11).
14. **Fuente canónica**: schema `Physician` + `MedicalWebPage` + `sameAs` a Doctoralia/TopDoctors/redes; credenciales idénticas en todos los perfiles (lección Cheong).
15. **Biblioteca pequeña y mantenida** (10–15 páginas de glosario/tratamiento con fecha de revisión) antes que un blog amplio (Cleveland, adaptado a una persona).

---

## 3. Anti-patrones (lo que no haremos)

- Copiar layouts, paletas o componentes de cualquiera de los referentes (mandato).
- Pestañas o acordeones que esconden contenido clínico esencial en móvil.
- Superlativos sin documento ("la mejor", "la única", "miles de pacientes").
- Calificaciones con estrellas sin fuente o testimonios transcritos/editados (D-012: sólo widget oficial de Doctoralia tras revisión de Codex).
- Tienda, afiliados, cursos de pago o productos junto al contenido clínico (Sleep Doctor).
- Cuestionarios que "diagnostican" o dan porcentajes de riesgo como si fueran clínicos.
- Páginas de ciudad falsas o contenido duplicado por barrio.
- Contenido clave en PDF.
- Dominio separado para la sub-marca de sueño.
- Secciones de perfil que no aplican (seguros múltiples, varias sedes, cargos académicos inexistentes).
- Páginas sin fecha de revisión ni fuentes.
- Fotografía de stock de "doctor sonriente" y de "mujer durmiendo" genérica; iconografía de luna/estrellas como único recurso visual del sueño.
- Más de tres CTAs por pantalla; CTA de urgencias publicado.
- Animaciones o vídeos de fondo que penalicen Core Web Vitals en móvil.

---

## 4. Recomendaciones específicas

### (a) Página de la Dra. como página de autoridad / entidad

Objetivo: ser la fuente canónica sobre "Irina González Sáez" para pacientes, buscadores y modelos de IA.

Estructura propuesta (orden de lectura):
1. **Hero**: retrato real (toma 2/3 del shot list), nombre, "Otorrinolaringólogo · Especialista en desórdenes respiratorios del dormir, ronquido y apnea del sueño", una frase de posicionamiento verdadera, CTA WhatsApp + teléfono.
2. **Credenciales clave (3–5 tarjetas)**: certificación del Consejo Mexicano de ORL y CCC (número y vigencia cuando se tengan), subespecialidad en desórdenes respiratorios del dormir (ISSSTE Monterrey, 2018), especialidad ORL (UCLA, 2018), miembro fundador de la Sociedad Iberoamericana de Cirugía de Sueño, cédula profesional (cuando se emita). Cada tarjeta con enlace a "ver documento" o a la institución. Nada se publica sin confirmación (DISCOVERY §3).
3. **Trayectoria narrada** (200–300 palabras, primera persona o tercera, decisión de voz en Fase 4): formación, residencias, llegada a Monterrey, por qué el sueño.
4. **Enfoque clínico**: qué trata (lista enlazada a páginas de condición), qué no trata y a quién refiere.
5. **Investigación, docencia y conferencias**: publicación con enlace/DOI, ponencias con año y congreso, cursos de faringoplastia y endoscopia de sueño.
6. **Cómo es una consulta con la Dra.**: enlace a "Tu primera consulta".
7. **Opiniones**: widget oficial de Doctoralia (cuando se autorice).
8. **Consultorio**: dirección, mapa, foto, horario, cómo llegar.
9. **Prensa y comunidad** (si existe material): entrevistas, redes.
10. **Datos estructurados**: `Physician` (name, image, medicalSpecialty Otolaryngology, address, telephone, url, `sameAs` a Doctoralia/TopDoctors/Instagram/LinkedIn, `memberOf`, `alumniOf`, `hasCredential`), `ProfilePage` como contenedor, `BreadcrumbList`. Fotografía headshot cuadrada (toma 4) como `image`.

Microcopy de confianza: "si tu caso no requiere cirugía, te lo diré"; "trabajo en conjunto con neumología, neurología y odontología del sueño cuando tu caso lo necesita".

### (b) Plantilla de página de condición / servicio

Una sola plantilla para condiciones (ronquido, apnea obstructiva, apnea en niños, obstrucción nasal, rinitis, sinusitis, etc.) y una variante para tratamientos/estudios (CPAP, DAM, septoplastia, faringoplastia, endoscopia de sueño, polisomnografía/estudio en casa).

Bloques (en este orden; todos visibles sin pestañas):
1. Título H1 con nombre coloquial y técnico ("Ronquido (roncopatía)").
2. **Resumen en 3 líneas** + "¿Cuándo consultar?" destacado.
3. Sello editorial compacto: revisado por, fecha, enlace a política editorial.
4. Qué es / síntomas (lista escaneable) / causas y factores de riesgo.
5. Cómo se diagnostica (qué estudio, dónde, cuánto dura, si es en casa).
6. Tratamientos: no quirúrgicos primero, quirúrgicos después; cada uno con una línea "para quién es" y enlace a su página.
7. **"Cómo lo abordamos en consulta"**: el equivalente a "Care at Mayo Clinic", en primera persona de la práctica.
8. Preguntas frecuentes (5–8, con `FAQPage` sólo si el contenido es visible).
9. CTA de cierre: WhatsApp con mensaje prellenado específico de la condición.
10. Fuentes (3–6 referencias: guías AASM, SEORL/SEPAR, NOM aplicables, revisiones) y fecha de próxima revisión.
11. Relacionados (condición ↔ tratamientos ↔ estudio).

Variante de tratamiento: añade "qué sentirás", "anestesia", "duración", "recuperación y reposo", "riesgos y alternativas", "siguiente paso".

Datos estructurados: `MedicalWebPage` con `about` (`MedicalCondition` o `MedicalProcedure`), `reviewedBy` (Physician), `lastReviewed`, `dateModified`.

### (c) Sección "sleep experience" (sub-marca de sueño)

- **Nombre y ruta**: nombre propio en español, corto, dentro del dominio (p. ej. `/sueno/`; el nombre comercial lo decide el propietario en PLAN 4.4). Nunca dominio separado.
- **Promesa**: "respirar bien de noche" como beneficio, no "cirugía" como medio (lección Melbourne).
- **Landing**: para quién es (ronca, se cansa de día, pareja preocupada, CPAP no tolerado, niño que ronca); el camino en 4 pasos (consulta → estudio → plan → seguimiento); opciones de tratamiento con "para quién es"; equipo y colaboraciones; autoevaluación; CTA.
- **Identidad visual**: misma tipografía (Iskra) y paleta de marca, con **predominio del teal y tintes claros** y un tratamiento fotográfico más íntimo y de luz suave (toma 6 del shot list) para diferenciarla del pilar ORL (púrpura); sin luna/estrellas como recurso principal. Verificar contraste AA.
- **Contenido**: hub de apnea con subpáginas por intención (síntomas, estudio en casa vs. laboratorio, CPAP y alternativas, DAM, cirugía nasal, faringoplastia, apnea infantil, apnea leve) con sello editorial.
- **Herramienta**: autoevaluación STOP-Bang/Epworth con resultado "bajo / medio / alto — conviene valorarlo" y botón de WhatsApp con el resultado prellenado. Sin almacenar datos de salud en el servidor (cálculo en cliente).
- **Sin dependencia de cirugía**: el 100 % de la sección debe tener sentido para un paciente que terminará en CPAP o DAM.

### (d) Estrategia de CTA móvil (WhatsApp principal)

- **Barra inferior fija en móvil** con dos acciones: "WhatsApp" (primaria, color de marca, icono) y "Llamar" (secundaria). Altura 56–64 px, dentro de la zona del pulgar, con `safe-area-inset-bottom`. Se oculta al abrir el teclado o formularios; se muestra tras 1 pantalla de scroll en la home y siempre en páginas de condición/tratamiento.
- **Mensaje prellenado por contexto** (`wa.me/528123685381?text=…`): "Hola, Dra. Irina. Vi la página de [condición] y quisiera agendar una valoración." En la autoevaluación, incluye el resultado. Sin datos de salud sensibles más allá del motivo genérico.
- **Enlace `wa.me` con número sin espacios y UTM** en el enlace interno para medir clics (evento `whatsapp_click` con página de origen). Fallback a WhatsApp Web en escritorio.
- **Expectativa de respuesta visible** junto al botón: "Respondemos en horario de consultorio, lun–vie 9–19 h" (horario pendiente de confirmar). Mensaje de ausencia configurado en WhatsApp Business.
- **Flujo recomendado en WhatsApp** (operativo, no del sitio): motivo genérico → 3 opciones de horario → nombre y teléfono → confirmación → recordatorio 24 h antes con dirección y piso. Datos clínicos sólo en consulta/expediente (privacidad). Las cifras de mejora que circulan en guías de proveedores (conversión y no-show) son afirmaciones comerciales [S]; medir las propias.
- **Jerarquía de CTAs en escritorio**: botón WhatsApp en cabecera, teléfono en texto, Doctoralia como enlace secundario en el perfil y en el pie.
- **Nunca**: pop-ups de intención de salida, chat bots emergentes, más de dos botones flotantes, número de urgencias (D-011).
- **Accesibilidad**: etiqueta `aria-label="Agendar por WhatsApp"`, foco visible, contraste AA sobre el verde/teal, objetivo táctil ≥ 44 px.

### (e) Componentes E-E-A-T

1. **Caja de revisión médica** (parte superior compacta + parte inferior extendida en cada página clínica): foto headshot, "Revisado por la Dra. Irina González Sáez, Otorrinolaringólogo, certificada por el Consejo Mexicano de ORL y CCC", cédula (cuando se emita), fecha de última revisión, enlace al perfil y a la política editorial. Implementación: campos de post meta en el plugin `dra-irina-core` (`reviewed_by`, `last_reviewed`, `next_review`, `sources`) renderizados por el child theme; no depender de un plugin de terceros.
2. **Fecha de última revisión médica** distinta de la fecha de publicación/modificación; visible y en `lastReviewed`. Regla editorial: revisión anual o al cambiar una guía clínica; página con próxima revisión vencida muestra aviso interno (no público) en el panel.
3. **Fuentes** al pie con formato uniforme (organismo/autor, título, año, enlace), 3–6 por página, priorizando guías de sociedades (AASM, SEORL-CCC, SEPAR, AAO-HNS), NOM y revisiones sistemáticas. Sin enlaces a blogs comerciales.
4. **Política editorial pública** (`/politica-editorial/`): quién escribe, quién revisa, qué fuentes se aceptan, cómo se corrigen errores, qué no es este sitio (no sustituye consulta), publicidad (ninguna).
5. **Página de entidad de la Dra.** (ver a) como ancla de todos los `reviewedBy`.
6. **Aviso de alcance** en cada página clínica: "Información educativa; no sustituye una valoración médica."
7. **Datos estructurados**: `MedicalWebPage` con `reviewedBy` → `Physician` (mismo `@id` que en el perfil), `lastReviewed`, `citation` cuando aplique; `Organization`/`MedicalClinic` para el consultorio con `address`, `telephone`, `openingHoursSpecification`, `sameAs`.
8. **Consistencia externa**: nombre, especialidad, dirección y teléfono idénticos en el sitio, GBP, Doctoralia y TopDoctors; credenciales redactadas igual en todos.
9. **Transparencia de precios y alcance** (decisión del propietario): si el directorio publica precios, el sitio debería al menos indicar "costo de primera consulta: $X" o "se informa por WhatsApp", para no perder frente al directorio.

---

## Anexo A — Dominios bloqueados por el proxy (EGRESS_BLOCKED) en esta sesión

Institucionales: stanfordhealthcare.org · mayoclinic.org · my.clevelandclinic.org · hopkinsmedicine.org · profiles.hopkinsmedicine.org · uclahealth.org · keck2.usc.edu
Marca personal / consultas privadas: sleepdoctor.com · sleepapneasurgery.com · melbournesleepsurgery.com.au · clinic-beethovenstrasse.ch · quironsalud.com · onewelbeck.com
Premios y galerías: awwwards.com · webflow.com · ehealthcareawards.com · longstreetclinic.com
Directorios y secundarias: doctoralia.com.mx · topdoctors.mx · freshysites.com · cyberoptik.net · tygartmedia.com · aurorainbox.com · wordpress.org

Ningún dominio pudo leerse directamente. Todo el contenido de este documento proviene de extractos de búsqueda y fuentes secundarias indexadas.

## Anexo B — Lista de comprobación para la pasada visual (cuando haya red)

Para cada referente principal (Stanford Sleep, Mayo OSA, Cleveland Health Library, Hopkins Snoring Center, Sleep Doctor hub, Melbourne Sleep Surgery), en móvil y escritorio:
- [ ] Qué hay en el primer scroll y cuántos CTAs.
- [ ] Posición y comportamiento del CTA en móvil (fijo, flotante, en cabecera).
- [ ] Profundidad real de navegación para llegar de la home a "agendar".
- [ ] Cómo se presenta la caja de revisión médica y la fecha (arriba, abajo, ambas).
- [ ] Uso de fotografía real vs. stock; presencia del médico en imagen.
- [ ] Tratamiento del FAQ (visible vs. acordeón) y de las fuentes.
- [ ] Lighthouse móvil (rendimiento, accesibilidad) como referencia de lo que no debemos empeorar.

## Anexo C — Fuentes consultadas (extractos de búsqueda)

- Stanford: páginas de Sleep Medicine Center, Sleep Surgery Program, consulta con especialista, perfiles Capasso/Pelayo/Kutscher/Robinson/Yoon; videos Kushida; historia de paciente 2011.
- Mayo Clinic: Sleep Medicine overview; Center for Sleep Medicine (noticia para profesionales); OSA en pestañas symptoms-causes / diagnosis-treatment / doctors-departments / care-at-mayo-clinic; biografías Olson y otras; "Meet our medical editors".
- Cleveland Clinic: Sleep Disorders Center; Surgical Treatment of Sleep Apnea and Snoring; Head & Neck appointments; Health Library (sleep apnea, OSA, central, AHI, CPAP, PAP, UPPP, implante, ronquido); página "about" de la Health Library; perfil Kominsky; historia de paciente Inspire vs CPAP.
- Johns Hopkins: Department of Otolaryngology; Specialty Areas; Center for Snoring and Sleep Surgery; perfil Motz; Health (OSA, snoring, hypoglossal nerve stimulation, "4 signs"); video.
- Sleep Doctor: perfil Breus; clinical care team; why Sleep Doctor; hub de apnea y subpáginas; cronotipos; metodología de reseñas; reseñas externas de Sleep Foundation y NCOA.
- Privados: sleepapneasurgery.com (about, patient experiences, research publications); melbournesleepsurgery.com.au (home, Mr Nathan Hayward, patient information, procedimientos, ubicaciones); Quirónsalud (perfil O'Connor Reina, unidad ronquido-apnea, notas de prensa); perfiles de Ryan Cheong en TopDoctors, Cleveland Clinic London, OneWelbeck, PHIN, Bupa.
- Premios: CSSDA / Webflow (Clinic Beethovenstrasse); W3 Awards vía Longstreet Clinic y Forum Communications; Awwwards y caso en Notion (Natalie Herzel Praxis, RedSquirrel); eHealthcare Leadership Awards 2025 (Best Site Design, Best Overall Internet Site).
- E-E-A-T y schema: lseo.com ("Reviewed by" tag), seo-day.de, tygartmedia.com (guía YMYL para consultorios en WordPress), schema.org (`lastReviewed`, `reviewedBy`), plantilla WAX (MedicalWebPage con reviewedBy).
- WhatsApp y agenda: guías de proveedores (Aurora Inbox, Medesk, GuruSup) — tratadas como afirmaciones comerciales, no como evidencia.
- Directorios locales: Doctoralia México y TopDoctors México (listados de ORL en Monterrey con ronquido/apnea).
