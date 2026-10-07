# KEYWORDS — Investigación preliminar de palabras clave

Proyecto: drairinagonzalez.com · Dra. Irina González Sáez · Otorrinolaringólogo · Monterrey, N.L.
Fecha: 2026-10-07 · Estado: **preliminar** (Fase 2.3). Responsable: Claude.

> Regla de la constitución (MASTER_PROMPT §3 y "NO asumir volumen"): en este documento **no se inventa ni se estima ningún volumen de búsqueda**. Donde no se obtuvo cifra de una fuente citada se escribe `volumen: no disponible` y se registra la evidencia observada (resultados SERP, directorios, preguntas de pacientes).
>
> Toda página de servicio o tratamiento está marcada como **sujeto a confirmación de la Dra. (OFRECE / NO OFRECE)**. Nada de lo que aquí aparece autoriza publicar que la Dra. realiza un procedimiento.

---

## 1. Metodología y limitaciones

### 1.1 Qué se hizo

1. Se tomaron las intenciones listadas en `SEO.md §3` y en la constitución (§"Ejemplos a investigar, NO asumir volumen") y se ampliaron con las condiciones ORL + sueño típicas de la subespecialidad de la Dra. (CV: desórdenes respiratorios del dormir, ronquido, rinología aplicada; cursos de faringoplastia con suturas barbadas y endoscopia de sueño).
2. Se ejecutaron ~35 búsquedas web en español, siempre con "Monterrey" cuando la intención era local, y se registró para cada una: tipo de resultados dominantes (directorios, hospitales, prensa, académicos), si aparecían páginas de directorio geolocalizadas (señal indirecta de intención local/transaccional), variantes léxicas que usan los propios directorios (lo más cercano a "cómo busca la gente" que pudimos obtener) y títulos con formato de pregunta (señal de intención informativa / PAA).
3. Se cruzó con lo que ya se sabe del mercado local por `DISCOVERY.md` (ubicación en Cumbres 2.º Sector, perfil en Doctoralia, GBP existente).

### 1.2 Qué NO se pudo hacer (limitaciones reales)

| Limitación | Detalle | Consecuencia |
|---|---|---|
| Sin herramienta de volúmenes | No hay acceso a Google Keyword Planner, Search Console con datos históricos del dominio, Semrush, Ahrefs ni similares. | **Ningún volumen en este documento.** Todas las filas dicen `volumen: no disponible`. |
| Autocompletado bloqueado | Los endpoints de sugerencias de Google (`suggestqueries.google.com`), Bing y DuckDuckGo están bloqueados por el proxy de salida (HTTP 403 / EGRESS_BLOCKED). | No hay sugerencias de autocompletado reales. Las "variantes" listadas provienen de la nomenclatura que usan los directorios médicos mexicanos (Doctoralia, Top Doctors, TocDoc) y de títulos de resultados, no de autocompletado. |
| Directorios no navegables | `doctoralia.com.mx` y `topdoctors.mx` están bloqueados para lectura completa; solo se ven sus títulos y fragmentos en los resultados. | No se pudo leer el bloque "Preguntas frecuentes" de Doctoralia ni contar el número de especialistas por categoría. |
| Sesgo geográfico de la búsqueda | El buscador disponible está orientado a EE. UU.: aunque la consulta sea en español con "Monterrey", devuelve contenido de España, Chile, Argentina y, en el caso de "CPAP Monterrey", confunde con Monterey County, California. | No se puede afirmar si una consulta muestra **local pack** en Google México. Se registra solo "aparecen directorios geolocalizados: sí/no" como aproximación. |
| Sin SERP real de Google.com.mx | No se ve el bloque "Otras preguntas de los usuarios" ni "Búsquedas relacionadas" tal cual. | Las preguntas de la §3 se reconstruyen a partir de títulos con formato de pregunta, preguntas publicadas por pacientes en Doctoralia y foros, y preguntas tipo FAQ de hospitales. Se citan las fuentes. |
| Sitio del cliente no accesible | `drairinagonzalez.com` bloqueado; `site:` no devuelve resultados indexados (ver `DISCOVERY.md §1.6`). | No hay línea base de ranking. |

### 1.3 Cómo cerrar las brechas (siguiente paso recomendado)

- **Search Console** (propiedad ya verificada): exportar consultas de los últimos 16 meses en cuanto haya impresiones. Es la única fuente de volumen *propio* real.
- **Google Business Profile → Rendimiento**: términos de búsqueda con los que la ficha apareció. Lo administra el propietario.
- **Google Keyword Planner** (cuenta de Google Ads, sin gasto): rangos de volumen para México / Monterrey. Pedirlo al propietario o a Codex.
- Verificar manualmente en un navegador desde Monterrey (o con `&gl=mx&hl=es-419`) qué consultas de la tabla muestran local pack y qué preguntas aparecen en "Otras preguntas de los usuarios". Anotarlo en la columna "Evidencia".

### 1.4 Convenciones de la tabla

- **Volumen**: siempre `no disponible` en esta versión.
- **Evidencia SERP** (abreviaturas): `DIR-GEO` = aparecen páginas de directorio geolocalizadas (Doctoralia/Top Doctors/TocDoc con ciudad o colonia) → señal de intención local y de que Google trata la consulta como "buscar proveedor"; `INFO` = dominan artículos informativos (hospitales, prensa, enciclopedias médicas); `ACAD` = dominan artículos académicos/PDF (señal de poco contenido para paciente = oportunidad); `US-BIAS` = la herramienta devolvió resultados de EE. UU./España que no aplican.
- **Estado de servicio**: `[OFRECE / NO OFRECE: por confirmar]` en todas las páginas de servicio.

---

## 2. Keyword map

Formato de la constitución: `INTENCIÓN DE BÚSQUEDA → URL propuesta → KEYWORD PRIMARIA → SECUNDARIAS/variantes → ENTIDAD → CTA`.

Arquitectura de URL propuesta (**no definitiva**; la constitución pide no fijar la arquitectura hasta cerrar discovery): un pilar ORL (`/otorrinolaringologia/`), un pilar Sueño (`/sueno/`) con sus páginas hijas bajo el mismo prefijo porque es el diferenciador de la Dra., y las condiciones/tratamientos ORL generales bajo `/padecimientos/` y `/tratamientos/`. Los slugs usan la ortografía sin acentos ni eñe.

CTA principal en todo el sitio: **WhatsApp** (confirmado por el propietario, cierra OD-003). CTA secundario: teléfono de consultorio. Nunca el teléfono de urgencias.

### 2.1 Home y entidad local

| Intención | URL propuesta | Keyword primaria | Secundarias / variantes | Entidad | CTA | Volumen | Evidencia SERP |
|---|---|---|---|---|---|---|---|
| Local / transaccional: encontrar otorrino en Monterrey | `/` | otorrinolaringólogo en Monterrey | otorrino Monterrey · otorrinolaringóloga Monterrey · otorrino cerca de mí · otorrinolaringólogo Monterrey Cumbres · otorrino Cumbres Monterrey · otorrino Paseo de los Leones · otorrinolaringólogo Nuevo León · consulta otorrino Monterrey · otorrino adultos y niños Monterrey | Physician + MedicalBusiness (consultorio en CAB Medical Headquarters, Cumbres 2.º Sector) | WhatsApp "Agendar consulta" | no disponible | `DIR-GEO` fuerte: Doctoralia "Otorrinolaringólogos en Monterrey" y subpáginas por colonia (Centro, Los Doctores) y por aseguradora (GBG, MetLife, GNP, MAPFRE, AXA, Ve por Más); Top Doctors por padecimiento (otitis, sordera, "enfermedades de senos y oídos"); TocDoc "otorrinolaringólogos Monterrey". SERP dominada por directorios → la home compite con agregadores; la ficha de GBP y la página de entidad son la vía realista para el local pack. |
| Local por zona: Cumbres | `/` (sección "Consultorio en Cumbres") + GBP; **no crear página aparte** | otorrino Cumbres Monterrey | otorrinolaringólogo Cumbres · otorrino Cumbres 2do sector · otorrino cerca de Paseo de los Leones · otorrino poniente Monterrey | MedicalBusiness.address | WhatsApp + botón "Cómo llegar" (mapa) | no disponible | La búsqueda devuelve Doctoralia de municipios vecinos (García, Escobedo) y médicos de **Christus Muguerza Cumbres (Av. Valle de Cumbres 8001)**. Ojo: "Cumbres" para Google mezcla *Valle de Cumbres* y *Cumbres 2.º Sector*; conviene escribir la dirección completa y la colonia exacta en la home, la página de entidad y el GBP. |
| "Cerca de mí" | `/` + GBP | otorrino cerca de mí | otorrinolaringólogo cerca de mí · otorrino cerca de mi ubicación · otorrino abierto hoy | MedicalBusiness.geo + openingHours | WhatsApp | no disponible | Resultados: Top Doctors Monterrey, Doctoralia Los Doctores, TocDoc. Las consultas "cerca de mí" se resuelven casi por completo con el GBP (categoría, horario, dirección, reseñas); el sitio aporta la señal de entidad (NAP idéntico + schema). |
| Variante femenina | `/` y `/dra-irina-gonzalez-saez/` (en copy, no en títulos) | otorrinolaringóloga en Monterrey | otorrinolaringóloga Monterrey · doctora otorrino Monterrey · otorrino mujer Monterrey · otorrinolaringóloga Cumbres | Physician (gender) | WhatsApp | no disponible | La búsqueda "otorrinolaringóloga Monterrey doctora" devolvió a la propia Dra. (perfil de Doctoralia, que la etiqueta como "Otorrinolaringóloga") junto a Dras. Hernández Rosales, Jasso Ramírez, Rodríguez Botello, Dávalos García. Hay demanda real por médicas; ver §5 para manejarlo sin contradecir la marca "otorrinolaringólogo". |

### 2.2 Página de entidad (persona)

| Intención | URL propuesta | Keyword primaria | Secundarias / variantes | Entidad | CTA | Volumen | Evidencia SERP |
|---|---|---|---|---|---|---|---|
| Navegacional / confianza: buscar a la Dra. por nombre o verificar credenciales | `/dra-irina-gonzalez-saez/` | Dra. Irina González Sáez | Irina González Sáez otorrino · Dra. Irina González otorrinolaringólogo Monterrey · Irina González Sáez opiniones · Irina González Sáez Doctoralia · otorrinolaringólogo certificado Monterrey · otorrino especialista en sueño Monterrey | Physician (name, medicalSpecialty, memberOf, alumniOf, hasCredential — **solo datos confirmados**, ver DISCOVERY §1.1) | WhatsApp + enlace a opiniones (widget oficial Doctoralia) | no disponible | La búsqueda por nombre devuelve el perfil de Doctoralia como primer resultado (descripción: interés especial en ronquido y apnea obstructiva del sueño, patología de voz, nasal y cabeza-cuello; adultos y niños; consulta en línea; servicios "apnea del sueño" y "cirugía de ronquido"). El sitio propio no aparece. La página de entidad debe ganar la consulta de marca y servir de fuente canónica de la "entidad Dra. Irina" (sameAs → Doctoralia, GBP, redes @drairinagonzalezORL). |

### 2.3 Pilar ORL

| Intención | URL propuesta | Keyword primaria | Secundarias / variantes | Entidad | CTA | Volumen | Evidencia SERP |
|---|---|---|---|---|---|---|---|
| Informativa-comercial: qué trata un otorrino, cuándo acudir, y catálogo de padecimientos | `/otorrinolaringologia/` | otorrinolaringología Monterrey | qué trata el otorrinolaringólogo · cuándo ir al otorrino · síntomas para ir al otorrino · otorrino oído nariz garganta · otorrino para niños Monterrey (si la Dra. atiende pediatría — por confirmar) · consulta otorrinolaringología precio Monterrey | MedicalSpecialty: Otolaryngologic + enlaces a cada padecimiento | WhatsApp; enlaces a páginas hijas | no disponible | Para "cuándo ir al otorrino / qué trata": `INFO` (Tua Saúde "Otorrinolaringólogo: qué es, qué hace y cuándo consultar"; The Clinic "Cuándo consultar al otorrinolaringólogo"; Quirónsalud). Diagnósticos frecuentes citados en esos resultados: sinusitis, rinitis alérgica, infecciones de oído, amigdalitis recurrente, pérdida de audición, zumbidos, vértigo, desviación de tabique y apnea del sueño — coincide con la lista de páginas hijas propuesta. Doctoralia tiene "Visita Otorrinolaringología" como servicio geolocalizado (Escobedo) → la consulta de precio existe como intención. |

### 2.4 Pilar Sueño (diferenciador)

| Intención | URL propuesta | Keyword primaria | Secundarias / variantes | Entidad | CTA | Volumen | Evidencia SERP |
|---|---|---|---|---|---|---|---|
| Local / transaccional: encontrar quién trata ronquido y apnea en Monterrey | `/sueno/` | especialista en ronquido y apnea del sueño en Monterrey | especialista en sueño Monterrey · médico del sueño Monterrey · medicina del sueño Monterrey · clínica del sueño Monterrey (ver nota) · trastornos del sueño Monterrey · otorrino especialista en sueño Monterrey · otorrino ronquido Monterrey · cirugía de sueño Monterrey · desórdenes respiratorios del sueño · trastornos respiratorios del dormir · roncopatía Monterrey | Physician.medicalSpecialty + MedicalCondition (OSA, snoring) + enlaces a hijas | WhatsApp "Valoración de ronquido / apnea" | no disponible | "apnea del sueño Monterrey especialista": `DIR-GEO` (Top Doctors "Apnea obstructiva del sueño — Monterrey/Nuevo León", TocDoc "apnea del sueño"); los perfiles listados son mayoritariamente **neumólogos**, un centro privado (CEAS, Dr. Felipe Cantú Díaz) y maxilofaciales (Face X Surgery). "médico del sueño Monterrey": Top Doctors neurología/insomnio, Doctoralia "trastornos del sueño (insomnio) Monterrey", "poligrafía respiratoria Monterrey"; especialistas listados: neurólogos, psiquiatras, neumólogos, médico general. **Conclusión**: la SERP de sueño en Monterrey está repartida entre especialidades; el pilar debe posicionar explícitamente "otorrinolaringólogo especialista en ronquido y apnea" (vía aérea superior, cirugía) para diferenciarse de neumología/neurología. Top Doctors tiene categoría "Roncopatía y apnea: tratamiento — Otorrinolaringología Monterrey", útil como etiqueta de variante. **Nota "clínica del sueño"**: no usar en títulos salvo que exista una clínica real; la Dra. tiene consultorio, no clínica (constitución §3). Puede tratarse en copy como "¿Necesito ir a una clínica del sueño?" |

### 2.5 Páginas de condición / tratamiento (ORL general)

Todas: `[OFRECE / NO OFRECE: por confirmar]`. Intención informativa en cada una ("qué es", "síntomas", "tratamiento", "cuándo ir al otorrino") + variante local/transaccional donde sea natural.

| # | Intención | URL propuesta | Keyword primaria | Secundarias / variantes (informativas + local) | Entidad | CTA | Volumen | Evidencia SERP | Servicio |
|---|---|---|---|---|---|---|---|---|---|
| 1 | Informativa + local: dolor de oído, infección | `/padecimientos/otitis/` | otitis: síntomas y tratamiento | qué es la otitis · otitis media · otitis externa · infección de oído · dolor de oído cuándo ir al otorrino · otitis en adultos · oído tapado y no escucho · otorrino otitis Monterrey · infección de oídos Monterrey | MedicalCondition: Otitis | WhatsApp | no disponible | `DIR-GEO` (Top Doctors "otitis Monterrey", "infección de oídos Monterrey" por aseguradora) + `INFO` (Quirónsalud). Preguntas reales en JustAnswer: "mi hija tiene otitis pero se le tapó su oído y no escucha". | por confirmar |
| 2 | Informativa + transaccional: oído tapado por cera | `/padecimientos/tapon-de-cerumen/` | tapón de cerumen: síntomas y cómo se quita | tapón de cera en el oído · cómo saber si tengo un tapón de cera · lavado de oído · limpieza de oídos otorrino · quién hace limpieza de oído · cómo sacar cera del oído · limpieza de oído Monterrey · lavado de oídos Monterrey | MedicalCondition: Cerumen impaction + MedicalProcedure: lavado/aspiración | WhatsApp | no disponible | `INFO` (Top Doctors diccionario "tapones de cera", Farmatodo, El Universo). Preguntas en formato FAQ encontradas: "¿Cómo saber si tienes un tapón de cera?", "¿Cómo se limpian los oídos?", "¿Quién te hace limpieza de oído?", "¿Cómo sacar mucha cera de los oídos?". Top Doctors tiene página "limpieza de oído Monterrey" → la variante transaccional local existe. | por confirmar |
| 3 | Informativa + local: pérdida de audición | `/padecimientos/hipoacusia/` | hipoacusia (pérdida de audición): causas y tratamiento | qué es la hipoacusia · pérdida de audición repentina · sordera súbita · no escucho bien de un oído · audiometría Monterrey · hipoacusia Monterrey · otorrino pérdida de audición Monterrey · aparatos auditivos (solo si aplica) | MedicalCondition: Hearing loss + MedicalTest: Audiometría | WhatsApp | no disponible | `DIR-GEO` (Top Doctors "hipoacusia Nuevo León/Monterrey", "pérdida de audición Nuevo León"), pero la categoría la dominan **audiología** y centros como Sonica Audición y Equilibrio. Diferenciar: el otorrino diagnostica causa y trata lo médico/quirúrgico; aclarar en copy si se hace audiometría en consultorio (por confirmar). | por confirmar |
| 4 | Informativa: zumbido | `/padecimientos/tinnitus/` | tinnitus (acúfeno): por qué zumba el oído | zumbido en el oído · pitido en el oído · acúfenos · zumbido de oídos cuándo ir al médico · tinnitus causas · tinnitus tiene cura · tinnitus Monterrey · acúfenos otorrino Monterrey | MedicalCondition: Tinnitus | WhatsApp | no disponible | `INFO` con títulos-pregunta: "¿Sientes un pitido en el oído? Cuándo el tinnitus puede convertirse en una señal de alerta"; "Escuchar zumbidos o sonido de grillos en los oídos es señal de que hay que ir al médico". `DIR-GEO` débil (Top Doctors "acúfenos/tinnitus Nuevo León", San Pedro). | por confirmar |
| 5 | Informativa + local: mareo/vértigo | `/padecimientos/vertigo/` | vértigo: causas y cuándo ir al otorrino | mareo y vértigo · vértigo postural benigno · vértigo otorrino o neurólogo · vértigo de oído · otoneurología · maniobra de Epley · vértigo Monterrey · otorrino vértigo Monterrey | MedicalCondition: Vertigo (BPPV) | WhatsApp | no disponible | `DIR-GEO` (Top Doctors "mareos y vértigo Monterrey"; Doctoralia "vértigo postural benigno Monterrey"). Pregunta frecuente: "¿debería evaluarlo un otorrinolaringólogo o un otoneurólogo?" (JustAnswer). Competencia local de otoneurólogos (Dr. Mancilla Mejía, Sonica). | por confirmar |
| 6 | Informativa + local: nariz tapada, alergia | `/padecimientos/rinitis/` | rinitis alérgica: síntomas y tratamiento | rinitis · nariz tapada constante · congestión nasal crónica · rinitis alérgica Monterrey · otorrino alergias Monterrey · pruebas de alergia (solo si aplica) · rinitis o sinusitis diferencia | MedicalCondition: Allergic rhinitis | WhatsApp | no disponible | `DIR-GEO` fuerte (Top Doctors "rinitis alérgica Monterrey/Nuevo León" por aseguradora AXA, GNP, Seguros Monterrey). | por confirmar |
| 7 | Informativa + local: sinusitis | `/padecimientos/sinusitis/` | sinusitis: síntomas, duración y cuándo ir al otorrino | sinusitis crónica · sinusitis aguda · sinusitis tratamiento · cirugía de senos paranasales · cirugía endoscópica nasal · sinusitis Monterrey · otorrino sinusitis Monterrey · sinusitis alérgica | MedicalCondition: Sinusitis + MedicalProcedure: cirugía endoscópica (si OFRECE) | WhatsApp | no disponible | `DIR-GEO` (Top Doctors "sinusitis Monterrey", "sinusitis alérgica Monterrey") + `INFO` con títulos-pregunta: "Sinusitis: síntomas, duración y cuándo ir al médico"; "Operación para sinusitis: cuándo está indicada y recuperación". | por confirmar |
| 8 | Informativa: tabique desviado | `/padecimientos/desviacion-de-tabique-nasal/` | desviación del tabique nasal: síntomas y cuándo operar | tabique desviado · cómo saber si tengo el tabique desviado · tabique desviado síntomas · tabique desviado ronquido · desviación de tabique Monterrey | MedicalCondition: Deviated septum | WhatsApp → enlaza a septoplastia | no disponible | `INFO` (Mayo Clinic, MD.Saúde, Tua Saúde, "Cómo saber si tienes el tabique nasal desviado o perforado"). Sin directorios geolocalizados en la muestra → página informativa que alimenta a la de tratamiento. | n/a (condición) |
| 9 | Transaccional + informativa: cirugía de tabique | `/tratamientos/septoplastia/` | septoplastia en Monterrey | septoplastia qué es · septoplastia recuperación · septoplastia precio Monterrey · cirugía de tabique nasal Monterrey · septoplastia vs rinoplastia · cirugía funcional de nariz Monterrey · septoplastia y turbinoplastia | MedicalProcedure: Septoplasty | WhatsApp "Valoración quirúrgica" | no disponible | `INFO`/`US-BIAS` (Quirónsalud, Clínica Las Condes). Los otorrinos de Monterrey se anuncian como "cirugía funcional y estética de nariz" (Doctoralia/Top Doctors) → la variante "cirugía funcional de nariz" es relevante. | por confirmar |
| 10 | Transaccional + informativa: cornetes | `/tratamientos/turbinoplastia/` | turbinoplastia (cirugía de cornetes) en Monterrey | hipertrofia de cornetes · cornetes inflamados · reducción de cornetes · turbinoplastia precio · turbinoplastia con radiofrecuencia (solo si aplica) · cornetes Monterrey | MedicalProcedure: Turbinoplasty + MedicalCondition: Hipertrofia de cornetes | WhatsApp | no disponible | `DIR-GEO` (Doctoralia "hipertrofia de cornetes San Pedro Garza García"; Top Doctors "cornetes Nuevo León"). Hay intención de precio ("desde $…" en directorios). | por confirmar |
| 11 | Informativa + transaccional: pólipos | `/padecimientos/polipos-nasales/` | pólipos nasales: síntomas y tratamiento | qué son los pólipos nasales · pólipos nasales cirugía · cirugía endoscópica de pólipos nasales Monterrey · poliposis nasosinusal · pólipos nasales Monterrey | MedicalCondition: Nasal polyps + MedicalProcedure (si OFRECE) | WhatsApp | no disponible | `DIR-GEO` fuerte: Doctoralia "Cirugía de pólipos nasales por endoscopia en Monterrey / Nuevo León / Guadalupe". | por confirmar |
| 12 | Informativa (+ urgencia): sangrado nasal | `/padecimientos/epistaxis/` | sangrado nasal (epistaxis): causas y cuándo acudir al otorrino | sangrado de nariz · hemorragia nasal · por qué sangra la nariz · sangrado nasal frecuente · cómo detener sangrado de nariz · epistaxis Monterrey | MedicalCondition: Epistaxis | WhatsApp + aviso claro de urgencias (sin publicar número de urgencias; ver DISCOVERY) | no disponible | `INFO` (Quirónsalud "¿Qué hacer si sangra la nariz?", MSD Manuals). Sin señales locales. Página de apoyo/FAQ, bajo potencial transaccional. | n/a (condición) |
| 13 | Informativa + local: garganta | `/padecimientos/amigdalitis/` | amigdalitis: síntomas y tratamiento | amigdalitis recurrente · amigdalitis crónica · anginas · dolor de garganta frecuente · amigdalitis Monterrey · otorrino amigdalitis Monterrey | MedicalCondition: Tonsillitis | WhatsApp | no disponible | `DIR-GEO` (Top Doctors "amigdalitis Nuevo León", "amigdalitis — cirugía pediátrica Monterrey"; Doctoralia "amigdalitis Monterrey"). | por confirmar |
| 14 | Transaccional + informativa: cirugía de amígdalas/adenoides | `/tratamientos/amigdalas-y-adenoides/` | cirugía de amígdalas y adenoides en Monterrey | amigdalectomía Monterrey · adenoidectomía · adenoides en niños · vegetaciones · amígdalas grandes ronquido niños · cuándo operar las amígdalas · amigdalectomía adultos · recuperación amigdalectomía | MedicalProcedure: Tonsillectomy / Adenoidectomy | WhatsApp | no disponible | `DIR-GEO` (Top Doctors "amigdalectomía Monterrey"; etiqueta "vegetaciones o adenoides"). `INFO` (Médica Sur folleto, Cochrane). Puente natural con sueño: amígdalas/adenoides y apnea infantil. **Pediatría por confirmar.** | por confirmar |
| 15 | Informativa: reflujo "silencioso" | `/padecimientos/reflujo-laringofaringeo/` | reflujo laringofaríngeo: síntomas y tratamiento | reflujo faringolaríngeo · reflujo silencioso · carraspera constante · sensación de algo en la garganta · tos crónica reflujo · ronquera por reflujo · reflujo laringofaríngeo otorrino Monterrey | MedicalCondition: LPR | WhatsApp | no disponible | `ACAD`/`INFO` (IntraMed, GPnotebook, Medigraphic). Nada local → oportunidad de contenido en español sencillo. Los resultados citan que RLF ≈ 10 % de la consulta ORL (IntraMed) — cita, no volumen. | por confirmar |

### 2.6 Páginas de condición / tratamiento (sueño)

| # | Intención | URL propuesta | Keyword primaria | Secundarias / variantes | Entidad | CTA | Volumen | Evidencia SERP | Servicio |
|---|---|---|---|---|---|---|---|---|---|
| 16 | Informativa + local: ronco | `/sueno/ronquido/` | ronquido: causas, cuándo preocuparse y tratamiento | por qué ronco · cómo dejar de roncar · ronquidos causas · ronquido fuerte · ronquido y apnea diferencia · mi pareja ronca · ronquido en niños · tratamiento ronquido Monterrey · otorrino ronquido Monterrey · roncopatía | MedicalCondition: Snoring | WhatsApp "Valoración de ronquido" | no disponible | `INFO` con títulos-pregunta: "Ronquidos: causas y cuándo consultar a un médico"; "¿Por qué roncamos y cuándo puede ser un problema?"; "¿Se puede dejar de roncar? Esto dice un especialista". Doctoralia tiene página de enfermedad "Ronquidos" con preguntas frecuentes y Q&A de pacientes ("ronco mucho al dormir y en el día respiro con un ronquido constante…"). | n/a (condición; tratamiento en #19–#21) |
| 17 | Informativa + local: apnea | `/sueno/apnea-obstructiva-del-sueno/` | apnea obstructiva del sueño: síntomas, diagnóstico y tratamiento | apnea del sueño · apnea del sueño síntomas · qué es la apnea del sueño · apnea del sueño tratamiento · apnea del sueño sin CPAP · apnea del sueño Monterrey · otorrino apnea del sueño Monterrey · SAOS / SAHOS · apnea del sueño otorrino o neumólogo · apnea del sueño consecuencias · apnea del sueño en niños | MedicalCondition: Obstructive sleep apnea | WhatsApp | no disponible | `DIR-GEO` (Top Doctors "apnea obstructiva del sueño Monterrey / Nuevo León / otorrinolaringología"; Doctoralia "apnea del sueño — información, expertos y preguntas frecuentes"; "apnea del sueño de tipo obstructivo"). `INFO`: "¿Roncas? Descubre qué es la apnea del sueño"; "Apnea del sueño: ¿cuáles son los síntomas y tratamientos?"; "tratamiento sin CPAP". Competencia local: neumólogos y CEAS. | por confirmar (diagnóstico/manejo) |
| 18 | Transaccional + informativa: estudio diagnóstico | `/sueno/estudio-del-sueno/` | estudio del sueño en Monterrey | polisomnografía Monterrey · poligrafía respiratoria Monterrey · poligrafía del sueño · estudio del sueño en casa · estudio del sueño precio Monterrey · cuánto cuesta un estudio del sueño · polisomnografía vs poligrafía · cómo se hace un estudio del sueño · dónde hacer estudio del sueño Monterrey | MedicalTest: Polysomnography / Home sleep apnea test | WhatsApp "Preguntar por estudio del sueño" | no disponible | `DIR-GEO`: Doctoralia "Poligrafía del sueño en Monterrey" (ORL Dra. Martínez Hinojosa; Dr. Rafael Moreno Sales "desde $3,500", Doctors Hospital) y "Poligrafía respiratoria en Monterrey". `US-BIAS` para "polisomnografía" (CUN, Sanitas, Quirónsalud). Intención de precio explícita. **Indicar claramente si la Dra. realiza, indica o refiere el estudio (OFRECE/REFERENCIA).** | por confirmar |
| 19 | Transaccional + informativa: CPAP | `/sueno/cpap/` | CPAP para apnea del sueño: qué es y cómo adaptarse | CPAP Monterrey · titulación de CPAP · no tolero el CPAP · alternativas al CPAP · CPAP o cirugía · CPAP precio (no dar precios de equipos ajenos) · dónde comprar CPAP Monterrey (intención comercial de proveedores; no perseguir) · manejo del CPAP | MedicalTherapy: CPAP | WhatsApp | no disponible | `US-BIAS` total: "CPAP Monterrey" devuelve Monterey County (California) y literatura; "venta CPAP Monterrey" devuelve spam y un contrato IMSS. No se encontró proveedor local en la muestra. La Dra. dictó "Manejo del CPAP" (CV) → su ángulo creíble es **manejo clínico y adherencia**, no venta. Publicar solo si OFRECE seguimiento de CPAP. | por confirmar |
| 20 | Transaccional + informativa: cirugía | `/sueno/cirugia-de-ronquido-y-apnea/` (faringoplastia como sección y H2; ver §4) | cirugía de ronquido en Monterrey | cirugía de ronquido precio Monterrey · cirugía para dejar de roncar · cirugía de apnea del sueño Monterrey · faringoplastia · faringoplastia con suturas barbadas · uvulopalatofaringoplastia (UPPP) · cirugía de paladar ronquido · operación de ronquidos recuperación · cirugía de ronquido duele · cirugía de sueño | MedicalProcedure: Pharyngoplasty / UPPP | WhatsApp "Valoración quirúrgica" | no disponible | `DIR-GEO`: Top Doctors "tratamiento de ronquidos Monterrey", "roncopatía y apnea tratamiento — ORL Monterrey"; Doctoralia "Cirugía del ronquido — información, expertos y preguntas frecuentes"; perfiles locales con precio público (Dr. Ramírez Leal "desde 48,000"; Dr. Salinas Camacho). "faringoplastia" sola: `ACAD` (Acta Otorrinolaringológica Española; USAL) + prensa argentina → poco contenido para paciente en español mexicano = oportunidad; la Dra. es coautora de un artículo sobre faringoplastia (CV; verificar DOI). | por confirmar |
| 21 | Informativa + transaccional: férula oral | `/sueno/dispositivo-de-avance-mandibular/` | dispositivo de avance mandibular para ronquido y apnea | DAM apnea · férula de avance mandibular · guarda para ronquido · aparato para dejar de roncar · dispositivo de avance mandibular precio · DAM o CPAP · dispositivo de avance mandibular Monterrey | MedicalDevice: Mandibular advancement device | WhatsApp | no disponible | Sin resultados locales (patentes, Colgate MX, Sleep & Sinus). `INFO`/`ACAD`. En México suelen fabricarlo odontólogos → aclarar el rol de la Dra. (indica/coordina/refiere). | por confirmar |
| 22 | Informativa (paciente avanzado): endoscopia | `/sueno/endoscopia-de-sueno/` | endoscopia de sueño (DISE): qué es y para qué sirve | endoscopia del sueño inducida por fármacos · DISE · somnoscopia · videofibrosomnoscopia · endoscopia de sueño Monterrey · para qué sirve la endoscopia de sueño · endoscopia de sueño antes de cirugía | MedicalProcedure: Drug-induced sleep endoscopy | WhatsApp | no disponible | `ACAD` puro (Elsevier/Acta ORL Española, USAL, Quirónsalud Alicante). Sin resultados mexicanos → consulta de nicho pero sin competencia; la Dra. cursó "Endoscopia de Sueño" (Monterrey 2024). | por confirmar |

### 2.7 Páginas de soporte (sin keyword propia; evitan dispersión)

| Página | URL | Intención | Keyword | Nota |
|---|---|---|---|---|
| Contacto / cómo llegar | `/contacto/` | Navegacional | "Dra. Irina González contacto", "consultorio Dra. Irina González dirección" | NAP idéntico al GBP; mapa; estacionamiento (PENDIENTE). No competir con la home por "otorrino Monterrey". |
| Preguntas frecuentes | `/preguntas-frecuentes/` | Informativa de servicio (precio, seguros, horario, primera consulta) | "cuánto cuesta consulta otorrino Monterrey", "otorrino que acepte seguro Monterrey" | Aseguradoras aparecen como filtro recurrente en Doctoralia/Top Doctors (GNP, AXA, MetLife, MAPFRE, Seguros Monterrey). Solo publicar las que la Dra. confirme. |
| Blog (opcional, fase posterior) | `/blog/` | Informativa long-tail | preguntas de §3 que no quepan en una página de padecimiento | No crear hasta cerrar las páginas núcleo. |

---

## 3. Preguntas de pacientes (estilo "Otras preguntas de los usuarios")

Origen: títulos-pregunta de resultados, preguntas publicadas por pacientes (Doctoralia Q&A, JustAnswer), bloques FAQ de hospitales/aseguradoras y páginas "Información, expertos y preguntas frecuentes" de Doctoralia. **No son un volcado literal del bloque PAA de Google México** (ver §1.2). Antes de publicarlas como FAQ, **la Dra. debe validar y redactar cada respuesta** (constitución §3); aquí solo se listan las preguntas.

### 3.1 Elegir otorrino / primera consulta
- ¿Qué trata un otorrinolaringólogo?
- ¿Cuándo debo ir al otorrino? ¿Qué síntomas lo justifican?
- ¿Es lo mismo otorrino que otorrinolaringólogo?
- ¿El otorrino atiende niños y adultos?
- ¿Cuánto cuesta la consulta con un otorrino en Monterrey? (los directorios muestran rangos públicos; la Dra. decide si publica precio)
- ¿Aceptan seguro de gastos médicos? ¿Cuáles?
- ¿Hay consulta en línea? (Doctoralia muestra que la Dra. la ofrece — por confirmar)
- ¿Qué debo llevar a la primera consulta?

### 3.2 Ronquido
- ¿Por qué ronco?
- ¿Roncar es peligroso? ¿Cuándo el ronquido es un problema?
- ¿Cómo dejar de roncar?
- ¿Cuál es la diferencia entre ronquido y apnea del sueño?
- ¿Se puede operar el ronquido? ¿Duele? ¿Cuánto tarda la recuperación?
- ¿Cuánto cuesta la cirugía de ronquido en Monterrey?
- ¿Por qué roncan los niños? ¿Es normal?
- ¿El tabique desviado causa ronquido?
- Ronco mucho al dormir y de día respiro con un ronquido constante; me he despertado ahogándome. ¿Qué especialista debo ver? (pregunta real de paciente en Doctoralia)

### 3.3 Apnea obstructiva del sueño
- ¿Qué es la apnea obstructiva del sueño?
- ¿Cuáles son los síntomas de la apnea del sueño?
- ¿Cómo sé si tengo apnea del sueño si duermo solo/a?
- ¿La apnea del sueño se cura?
- ¿Qué pasa si no trato la apnea del sueño? (hipertensión, infartos, accidentes — fuentes: Doctoralia, Consumer)
- ¿Quién trata la apnea del sueño: otorrino, neumólogo o neurólogo?
- ¿Hay tratamiento de apnea sin CPAP?
- ¿La apnea del sueño da en niños? ¿Se relaciona con amígdalas y adenoides?
- ¿Bajar de peso quita la apnea?

### 3.4 Estudio del sueño
- ¿Qué es una polisomnografía?
- ¿Cuál es la diferencia entre polisomnografía y poligrafía respiratoria?
- ¿Se puede hacer el estudio del sueño en casa?
- ¿Cuánto cuesta un estudio del sueño en Monterrey?
- ¿Cómo me preparo para el estudio del sueño?
- ¿Cuánto tarda el resultado?
- ¿Necesito ir a una clínica del sueño?

### 3.5 CPAP y alternativas
- ¿Qué es el CPAP y cómo funciona?
- ¿Tengo que usar el CPAP toda la vida?
- No tolero el CPAP, ¿qué opciones tengo?
- ¿Qué es un dispositivo de avance mandibular y para quién sirve?
- ¿Qué es la endoscopia de sueño (DISE) y por qué se hace antes de operar?
- ¿Qué es una faringoplastia? ¿Es lo mismo que la UPPP?

### 3.6 Nariz
- ¿Cómo saber si tengo el tabique desviado?
- ¿Cuándo se opera el tabique desviado?
- ¿Septoplastia y rinoplastia son lo mismo?
- ¿Cuánto dura la recuperación de la septoplastia?
- ¿Qué son los cornetes y por qué se inflaman?
- ¿Cuánto dura la sinusitis y cuándo debo ir al otorrino?
- ¿Cuándo se opera la sinusitis?
- ¿Rinitis y sinusitis son lo mismo?
- ¿Qué son los pólipos nasales? ¿Vuelven a salir después de la cirugía?
- ¿Por qué me sangra la nariz? ¿Cuándo es urgencia?

### 3.7 Oído y equilibrio
- ¿Cómo saber si tengo un tapón de cera?
- ¿Cómo se limpian los oídos correctamente? ¿Quién hace la limpieza de oído?
- ¿Es malo usar hisopos?
- Tengo otitis y el oído tapado; ¿cuándo volveré a escuchar bien?
- ¿Por qué me zumba el oído? ¿El tinnitus tiene cura? ¿Cuándo es señal de alarma?
- ¿Por qué no escucho bien de un oído? ¿Qué es la hipoacusia súbita?
- ¿Qué es la audiometría y la hace el otorrino?
- Vértigo: ¿voy al otorrino o al neurólogo?
- ¿Qué es el vértigo postural benigno?

### 3.8 Garganta
- ¿Cuándo hay que operar las amígdalas?
- ¿Qué son las adenoides ("vegetaciones")?
- ¿Cómo es la recuperación después de quitar amígdalas y adenoides?
- ¿Qué es el reflujo laringofaríngeo (reflujo silencioso)?
- ¿Por qué tengo carraspera o sensación de algo en la garganta?

---

## 4. Canibalización y plan de URLs; title tags

### 4.1 Riesgos de canibalización detectados y cómo los evita el plan

| Riesgo | Páginas en conflicto | Regla del plan |
|---|---|---|
| "otorrino Monterrey" repartido entre home, entidad y contacto | `/`, `/dra-irina-gonzalez-saez/`, `/contacto/` | Solo la **home** lleva "otorrinolaringólogo en Monterrey" en title/H1. La entidad lleva el **nombre** como primaria y la especialidad como complemento. Contacto no optimiza esa keyword (title "Contacto y ubicación…"). |
| "Cumbres" como página aparte | posible `/otorrino-cumbres/` | **No se crea.** Google mezcla "Valle de Cumbres" (hospital Muguerza) con "Cumbres 2.º Sector"; una página doorway por colonia es débil y duplicaría la home. La señal de zona va en home + entidad + GBP con dirección completa. |
| Pilar Sueño vs página de apnea | `/sueno/` vs `/sueno/apnea-obstructiva-del-sueno/` | Pilar = **quién** y **cómo** (especialista, método, "médico del sueño"); Apnea = **qué** (condición, síntomas, diagnóstico, opciones). "apnea del sueño Monterrey" se asigna a la página de apnea; "especialista/médico del sueño Monterrey" al pilar. |
| Ronquido (condición) vs Cirugía de ronquido (tratamiento) | `/sueno/ronquido/` vs `/sueno/cirugia-de-ronquido-y-apnea/` | Ronquido responde "por qué ronco / cuándo preocuparse"; Cirugía responde "cómo se opera / para quién / recuperación". Enlace cruzado único y explícito. |
| Faringoplastia como página propia | posible `/sueno/faringoplastia/` | **No en esta fase.** "faringoplastia" solo tiene resultados académicos; el paciente busca "cirugía de ronquido". Se trata como H2 + entidad `MedicalProcedure` dentro de la página de cirugía. Si Search Console muestra impresiones propias de "faringoplastia", se desdobla. |
| Tabique (condición) vs Septoplastia (tratamiento) | `/padecimientos/desviacion-de-tabique-nasal/` vs `/tratamientos/septoplastia/` | Misma regla condición/tratamiento. La página de septoplastia lleva la variante local y de precio; la de tabique, las preguntas "cómo saber si…". |
| Rinitis vs Sinusitis | `/padecimientos/rinitis/` vs `/padecimientos/sinusitis/` | Son condiciones distintas con directorios distintos; cada una incluye un bloque "¿rinitis o sinusitis?" que enlaza a la otra en lugar de duplicar contenido. |
| Amigdalitis (condición) vs Amígdalas y adenoides (cirugía) | `/padecimientos/amigdalitis/` vs `/tratamientos/amigdalas-y-adenoides/` | La cirugía concentra "amigdalectomía/adenoidectomía/cuándo operar"; la condición concentra "síntomas/recurrente". |
| Hipoacusia vs Tinnitus vs Tapón de cerumen | tres páginas de oído | Síntoma "oído tapado" aparece en las tres: se asigna la frase "oído tapado y no escucho" a **tapón de cerumen** (causa más frecuente), y las otras dos enlazan. |
| Estudio del sueño vs Apnea | `/sueno/estudio-del-sueno/` vs apnea | "polisomnografía / poligrafía / precio del estudio" solo en estudio del sueño; apnea enlaza con un párrafo de "cómo se diagnostica". |
| Blog futuro vs páginas de padecimiento | `/blog/*` | Un artículo de blog nunca repite la keyword primaria de una página de padecimiento; cubre long-tail (p. ej., "ronquido en el embarazo") y enlaza a la página madre. |
| Variantes de género | todas | No se crean páginas ni títulos separados para "otorrinolaringóloga". Ver §5. |

Regla general: **una intención = una URL**; una keyword primaria aparece en un solo title/H1 del sitio. Canonical autorreferente en todas; sin parámetros indexables; `noindex` en gracias/404/staging (ya en `SEO.md §6`).

### 4.2 Title tags propuestos (8 URLs más importantes)

Orientados a intención, sin patrón "Inicio | …", ≤ 60 caracteres (conteo entre paréntesis). Todos sujetos a la revisión médica y de marca.

| # | URL | Title tag | Nota |
|---|---|---|---|
| 1 | `/` | Otorrinolaringólogo en Monterrey · Dra. Irina González (54) | Masculino por decisión de marca; la variante femenina va en meta description y copy. |
| 2 | `/dra-irina-gonzalez-saez/` | Dra. Irina González Sáez, otorrinolaringólogo en Monterrey (58) | Nombre completo para la consulta de marca. |
| 3 | `/otorrinolaringologia/` | Otorrinolaringología en Monterrey: oído, nariz y garganta (57) | Pilar. |
| 4 | `/sueno/` | Especialista en ronquido y apnea del sueño en Monterrey (55) | Pilar diferenciador; evita "clínica del sueño" (no existe como tal). |
| 5 | `/sueno/apnea-obstructiva-del-sueno/` | Apnea del sueño en Monterrey: diagnóstico y tratamiento (55) | Alternativa informativa: "Apnea obstructiva del sueño: síntomas y tratamiento" (51). |
| 6 | `/sueno/ronquido/` | Ronquido: causas y cuándo ver al otorrino en Monterrey (54) | Pregunta implícita del paciente. |
| 7 | `/sueno/estudio-del-sueno/` | Estudio del sueño en Monterrey: polisomnografía y poligrafía (60) | Solo si OFRECE o REFIERE; ajustar al rol real. |
| 8 | `/sueno/cirugia-de-ronquido-y-apnea/` | Cirugía de ronquido y apnea en Monterrey · Faringoplastia (57) | Solo si OFRECE. |

Reservas (si alguna de las anteriores se descarta por NO OFRECE): `/tratamientos/septoplastia/` → "Septoplastia en Monterrey: desviación de tabique nasal" (54); `/padecimientos/sinusitis/` → "Sinusitis: síntomas, duración y cuándo ir al otorrino" (53).

Meta descriptions (regla): mencionar "Cumbres, Monterrey", el canal WhatsApp y, cuando aplique, "adultos y niños" (por confirmar). En la home y la entidad, incluir "otorrinolaringóloga" de forma natural en la description (ver §5).

---

## 5. Variantes de género: otorrinolaringólogo / otorrinolaringóloga / otorrino

### 5.1 Hechos

- Decisión de marca confirmada por el propietario (DISCOVERY §0, cierra OD-001): **"otorrinolaringólogo"** en todo el sitio (textos, títulos, SEO), coherente con logo y tarjetas.
- Los directorios usan el **masculino genérico en categorías** ("Otorrinolaringólogos en Monterrey") pero el **femenino en el perfil individual** (Doctoralia describe a la Dra. como "Otorrinolaringóloga"). Es decir, Google ya asocia la entidad "Irina González Sáez" con ambas formas.
- La búsqueda "otorrinolaringóloga Monterrey doctora" devuelve perfiles de médicas, incluida la Dra. → existe intención real de pacientes que buscan específicamente una doctora. Volumen: no disponible.
- "otorrino" es el acortamiento coloquial y neutro en género; los propios resultados lo tratan como sinónimo ("muchas veces llamado simplemente otorrino").

### 5.2 Regla editorial propuesta

1. **Title, H1, logo, schema `medicalSpecialty` y nombre de categoría**: siempre "otorrinolaringólogo" (decisión de marca). En schema, `Physician.gender: Female` y `jobTitle: "Otorrinolaringólogo"` conviven sin conflicto; el género lo aporta la propiedad, no el sustantivo.
2. **Meta description, intro de la home y página de entidad**: una sola frase natural con la forma femenina, p. ej. *"La Dra. Irina González Sáez es otorrinolaringóloga certificada…"* seguida del descriptor de marca. Una mención por página es suficiente; no repetir.
3. **"Otorrino"**: usarlo en preguntas y subtítulos conversacionales ("¿Cuándo ir al otorrino?", "Tu otorrino en Cumbres") porque es como habla el paciente; nunca como sustituto del descriptor de marca en títulos principales.
4. **Variantes que NO se escriben en el sitio**: "otorrinolaringólogo/a", "otorrinolaringólog@", "otorrinolaringóloga/o". Rompen la lectura y no aportan señal adicional.
5. **Enlaces internos**: anchor en la forma que corresponda al contexto de la frase; no forzar las tres formas en una misma página.
6. **GBP**: la categoría de Google es fija ("Otorrinolaringólogo"); en la descripción del perfil puede ir "otorrinolaringóloga" una vez, igual que en la home.
7. **Prueba**: si en 3–6 meses Search Console muestra consultas con "otorrinolaringóloga" que aterrizan en la home con CTR bajo, ajustar la meta description antes que el title.

Resultado: cobertura de las tres formas sin keyword stuffing y sin contradecir el logo ni la decisión del propietario.

---

## 6. Fuentes consultadas (resultados de búsqueda, 2026-10-07)

Directorios y perfiles locales
- https://www.doctoralia.com.mx/perfil/irina-gonzalez-saez
- https://www.doctoralia.com.mx/otorrinolaringologo/monterrey (y subpáginas por colonia/aseguradora)
- https://www.doctoralia.com.mx/tratamientos-servicios/poligrafia-del-sueno/monterrey
- https://www.doctoralia.com.mx/tratamientos-servicios/poligrafia-respiratoria/monterrey
- https://www.doctoralia.com.mx/tratamientos-servicios/cirugia-de-polipos-nasales-por-endoscopia/monterrey
- https://www.doctoralia.com.mx/enfermedades/apnea-del-sueno · /enfermedades/apnea-del-sueno-de-tipo-obstructivo · /tratamientos-servicios/cirugia-del-ronquido
- https://www.doctoralia.com.mx/enfermedades/hipertrofia-de-cornetes/san-pedro-garza-garcia
- https://www.doctoralia.com.mx/enfermedades/vertigo-postural-benigno/monterrey · /enfermedades/amigdalitis/monterrey · /enfermedades/trastornos-del-sueno-insomnio/monterrey
- https://www.doctoralia.com.mx/preguntas-respuestas/ronco-mucho-al-dormir-y-en-el-dia-respiro-con-un-ronquido-constante-leve-me-he-despertado-queriendome
- https://www.doctoralia.com.mx/perfil/felipe-cantu-diaz · /z/EvMqdn (Ramírez Leal) · /perfil/carlos-salinas-camacho
- https://www.topdoctors.mx/monterrey/apnea-obstructiva-del-sueno/ · /nuevo-leon/otorrinolaringologia/apnea-obstructiva-del-sueno/
- https://www.topdoctors.mx/monterrey/otorrinolaringologia/roncopatia-y-apnea-tratamiento/ · /otorrinolaringologia/tratamiento-de-ronquidos/seguros-monterrey/
- https://www.topdoctors.mx/monterrey/otorrinolaringologia/otitis/ · /monterrey/limpieza-de-oido/ · /diccionario-medico/tapones-de-cera/
- https://www.topdoctors.mx/nuevo-leon/hipoacusia/ · /nuevo-leon/perdida-de-audicion/ · /nuevo-leon/centro/otorrinolaringologia/acufenos-tinnitus/
- https://www.topdoctors.mx/otorrinolaringologia/mareos-y-vertigo/seguros-monterrey/
- https://www.topdoctors.mx/monterrey/otorrinolaringologia/rinitis-alergica/ · /monterrey/otorrinolaringologia/sinusitis/axa/ · /nuevo-leon/otorrinolaringologia/cornetes/
- https://www.topdoctors.mx/monterrey/otorrinolaringologia/amigdalectomia/ · /nuevo-leon/amigdalitis/ · /monterrey/trastorno-del-sueno/ · /monterrey/neurologia/insomnio/
- https://www.tocdoc.com/doctores/otorrinolaringologos/monterrey · /doctores/apnea-del-sueno

Informativas / preguntas de pacientes
- https://www.tuasaude.com/es/otorrinolaringologo/ · https://www.theclinic.cl/2026/02/26/cuando-consultar-otorrinolaringologo/
- https://www.meganoticias.cl/calidad-de-vida/428271-ronquidos-causas-y-cuando-consultar-a-un-medico-sintomas-pdp-13-10-2023.html
- https://www.elobservador.com.uy/nota/por-que-roncamos-y-cuando-puede-ser-un-problema-20227571129/amp
- https://www.eltiempo.com/salud/ronquidos-se-puede-dejar-de-roncar-esto-dice-especialista-682660
- https://www.consumer.es/salud/roncas-descubre-que-es-la-apnea-del-sueno.html
- https://www.primerahora.com/estilos-de-vida/ph-mas-saludable/notas/apnea-del-sueno-cuales-son-los-sintomas-y-tratamientos/
- https://www.thoracic.org/patients/patient-resources/resources/spanish/other-therapies-for-sleep-apnea.pdf
- https://www.quironsalud.com/pozuelo/es/servicios-medicos/neumologia/escuela-pacientes/sindrome-apnea-sueno/diagnostica-apnea-sueno
- https://archbronconeumol.org/es-poligrafia-respiratoria-el-diagnostico-del-articulo-S0300289615306566
- https://www.elsevier.es/es-revista-acta-otorrinolaringologica-espanola-402-articulo-pharyngoplasty-for-obstructive-sleep-apnea-S217357352200103X
- https://www.elsevier.es/es-topic-endoscopia-sueno-inducida-por-drogas-246668 · https://www.quironsalud.com/alicante/es/cartera-servicios/otorrinolaringologia/cirugia-mayor-ambulatoria/videofibrosomnoscopia
- https://www.colgate.com/es-mx/oral-health/threats-to-dental-health/mandibular-advancement-devices-mad-and-sleep-apnea
- https://www.mayoclinic.org/es/tests-procedures/septoplasty/about/pac-20384670 · https://www.saludonnet.com/blog/como-saber-si-tienes-el-tabique-nasal-desviado-o-perforado/
- https://www.meganoticias.cl/calidad-de-vida/522216-sinusitis-sintomas-duracion-y-cuando-ir-al-medico.html · https://www.tuasaude.com/es/sinusitis-operacion/
- https://www.quironsalud.com/blogs/es/escuela-cuidado/hacer-sangra-nariz · https://www.msdmanuals.com/es/hogar/trastornos-otorrinolaringológicos/síntomas-de-las-enfermedades-de-la-nariz-y-la-garganta/hemorragia-nasal
- https://www.radioagricultura.cl/noticias/dato-practico/sientes-un-pitido-en-el-oido-cuando-el-tinnitus-puede-convertirse-en-una-senal-de-alerta_20260907/
- https://www.diariolasamericas.com/bienestar/escuchar-zumbidos-o-sonido-grillos-los-oidos-es-senal-que-hay-que-ir-al-medico-n5394300
- https://www.justanswer.es/medicina-es/vwats-deberia-evaluarlo-un-otorrinolaringologo-un-oto-neurologo.html · https://www.justanswer.es/medicina-es/jstsf-mi-hija-tiene-otitis-pero-se-le-tapo-su-oido-no-escucha.html
- https://www.clubmitsubishiasx.com/faq/como-saber-si-tienes-un-tapon-de-cera (y FAQs hermanas) · https://www.farmatodo.com.co/blog/lavado-oidos-beneficios-y-cada-cuanto-debo-hacerlo.html
- https://www.intramed.net/49364 (reflujo laringofaríngeo) · https://gpnotebook.com/es/pages/otorrinolaringologia/reflujo-laringofaringeo-rpl
- https://www.medicasur.com.mx/work/models/ms/Resource/8540/1/images/170919-folleto-UCEC-amigdalas-diciembre.pdf
- https://www.ilerna.es/blog/diferencias-audioprotesista-otorrino · https://www.wiktionary.com/wiki/otorrino

Internas
- `SEO.md`, `DISCOVERY.md`, `MASTER_PROMPT.md` (§3, "Ejemplos a investigar, NO asumir volumen", keyword map).

---

## 7. Pendientes para cerrar esta fase

1. Exportar consultas de Search Console y rendimiento del GBP → rellenar la columna "Volumen" solo con datos reales y su fecha.
2. Verificar en Google México (gl=mx) local pack y "Otras preguntas" para las 10 consultas locales clave; anotar en "Evidencia".
3. Catálogo OFRECE / NO OFRECE / REFERENCIA de la Dra. (mini app de briefing) → eliminar o convertir en informativa toda página de servicio no confirmada.
4. Confirmar atención pediátrica (afecta otitis, amígdalas/adenoides, ronquido infantil).
5. Decidir publicación de precios y aseguradoras (afecta FAQ y variantes "precio").
6. Fijar arquitectura definitiva (`/padecimientos/`, `/tratamientos/`, `/sueno/`) en `DECISIONS.md`.
