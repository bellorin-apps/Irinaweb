# PLAN MAESTRO — Dra. Irina González Sáez

Versión: 1.1 · Fecha: 2026-10-08 · Fases: 14 (0–13; la 13 es la versión en inglés, Anexo A del MASTER_PROMPT 1.1)

## Cómo se calcula el avance

- Cada tarea tiene un **peso** (1 = pequeña, 2 = media, 3 = grande, 5 = crítica/estructural).
- Avance global = Σ(peso de tareas DONE) / Σ(peso total).
- Una tarea está DONE solo si cumple: IMPLEMENTED + TESTED + REVIEWED (Codex cuando aplica) + FIXED + VERIFIED.
- Estados: `TODO` · `DOING` · `BLOCKED` · `OWNER` (OWNER_DECISION_REQUIRED / depende del propietario) · `REVIEW` (implementado y verificado por Claude, pendiente de auditoría Codex) · `DONE` · `DESCOPED` (fuera de alcance por decisión del propietario; no entra en el total de PROGRESS y se informa aparte, D-076).
- Solo `DONE` suma el peso completo. `REVIEW` y todos los demás estados suman cero (encargo del propietario, 2026-10-10).
- "Trabajo técnico" excluye las tareas marcadas `ext` (dependencias externas: aprobación médica, fotos, documentos, legal).

## Checkpoints con el propietario

| Checkpoint | Fase | Qué se aprueba |
|---|---|---|
| CP1 | 0 | Discovery + información faltante |
| CP2 | 4 | Dirección visual |
| CP3 | 6 | Home + sistema visual representativo |
| CP4 | 7 | Contenido médico |
| CP5 | 11 | Pre-launch |

---

## FASE 0 — Descubrimiento y onboarding

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 0.1 | Inicializar repositorio, estructura documental, `.gitignore` | 2 | DONE | Commit inicial |
| 0.2 | Incorporar Prompt Maestro v1.0 en `MASTER_PROMPT.md` | 2 | DONE | |
| 0.3 | Recolectar información existente (Drive, correo, público) sin inventar | 3 | DONE | Ver `DISCOVERY.md` |
| 0.4 | Identificar dominio, hosting, estado de indexación | 2 | DONE | drairinagonzalez.com; Search Console activo |
| 0.5 | Extraer identidad visual existente (logo, paleta, tarjetas) | 2 | DONE | Ver `DISCOVERY.md` §Identidad |
| 0.6 | Redactar cuestionario de discovery agrupado (12 bloques) | 2 | DONE | `DISCOVERY.md` |
| 0.7 | Checklist de documentos requeridos | 1 | DONE | `DISCOVERY.md` |
| 0.8 | Plan maestro con fases, pesos y Definition of Done | 2 | DONE | Este archivo |
| 0.9 | Respuestas del propietario al cuestionario (lote 1: desbloqueadores) | 3 | DONE | Lotes 1–3 respondidos 2026-10-07 |
| 0.10 | Confirmación expresa de la Dra. Irina de credenciales y servicios | 3 | DONE | Briefing confirmado 2026-10-08 (80 respuestas) |
| 0.11 | CHECKPOINT 1 cerrado | 1 | DONE | 2026-10-08 |

## FASE 1 — Acceso, auditoría técnica, backup y staging

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 1.1 | Canal técnico: red amplia del entorno + Application Password del WordPress NUEVO (REST); SSH solo desde la PC | 2 | OWNER | D-018, D-021 |
| 1.2 | Inventario completo de producción + clasificación KEEP/REPLACE/REMOVE | 3 | DONE | CSV 44 IDs únicos y clasificación (26 KEEP/17 REMOVE/1 REPLACE opcional), pie/legal detallados; retirada D-075 atestada con respaldo y 322 preservado. 27 adjuntos actuales reportados, páginas sin roturas observadas. Informe: docs/REVISION_2026-10-14.md |
| 1.3 | Auditoría pública: robots, sitemap, canonicals, noindex, headers, cache, CWV baseline | 2 | DONE | Quince200/h1/main/canónicas propias, 80vistas, noindex auxiliares6, sitemap10/2/3, robots/cabeceras/cache contrastados; baseline PERFORMANCE, sin datos de campo nuevos. Informe: docs/REVISION_2026-10-16.md |
| 1.4 | Revisar Search Console: cobertura, URLs indexadas/excluidas, sitemap enviado | 2 | DONE | Capturas de José (datos al 8/10), cinco motivos y ejemplos explicados, sitemap Correcto y reenviado 14/10. Sitemap actual incluye cinco fichas y excluye archivos 404; no se infiere cobertura nueva. Informe: docs/REVISION_2026-10-14.md |
| 1.5 | Limpieza y configuración base: idioma, zona horaria, formatos, Site Kit fuera, Astra fuera, Hello activo, hardening wp-config | 2 | DONE | InformeWP-CLI13/10: es_MX,Monterrey,formatos,Hello/child,ausenciaAstra/SiteKit y hardening confirmados. DISPLAY=true sinDEBUG no bloquea. Ver docs/audit/2026-10-13/veredictos-inventario.md |
| 1.6 | Verificar backups del hosting y crear backup completo verificado (archivos + BD) | 3 | DONE | Manual 2026-10-07 16:41 descargado; semanal automático activo |
| 1.7 | Crear staging protegido (noindex, fuera de sitemap, auth) | 3 | DESCOPED | D-080: el plan Premium no incluye Staging y José no lo amplía; sitio corto, respaldo antes de cada despliegue y prueba de formulario de José con su correo |
| 1.8 | Documentar accesos (sin secretos) y procedimiento de rollback | 1 | DONE | Auditoría2026-10-12: FIX documental: rollback vigente según deploy-code.sh (~/deploy-backups/<ts>); runbook de reset marcado histórico y no ejecutable; sin credenciales. |
| 1.9 | Codex: auditoría de seguridad del estado actual | 2 | DONE | SECURITY contrastado: HTTP anónimo users/author404, settings/Ops401, XML-RPC/readme403; cabeceras front y versiones ocultas. 2FA por atestación local; secretos ignorados/no versionados, sin pentest. Informe: docs/REVISION_2026-10-14b.md |

## FASE 2 — Investigación y estrategia

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 2.1 | `docs/REFERENCE_RESEARCH.md`: Stanford Sleep, Mayo, Cleveland, Johns Hopkins, Sleep Doctor, ENT privados, referentes UX | 3 | DONE | Auditoría2026-10-12: Referentes identifican snippets/inferencias y bloqueo de lectura; checklist visual separado. Investigación preliminar útil, sin certificar UX ajena. |
| 2.2 | Competencia local Monterrey (ORL + sueño): sitios, GBP, Doctoralia | 2 | DONE | Auditoría2026-10-12: FIX documental: oportunidades son hipótesis de muestra, sin afirmar ausencia universal de competidores; precios/horarios se subordinan a D-054/D-032. |
| 2.3 | Keyword research local y de condiciones (`KEYWORDS.md`) | 3 | DONE | Auditoría2026-10-12: Keyword map sin volúmenes inventados, limitaciones de SERP declaradas; coherencia con slugs del build. GSC/GBP quedan OWNER. |
| 2.4 | Perfil de paciente + motivos de consulta top 5–10 | 2 | DONE | Auditoría2026-10-12: DISCOVERY §0b contiene11 motivos confirmados; ocho filas del build agrupan los11 (ronquido/apnea, oído/cerumen, amígdalas/niños). Sito fresco y estrategia coherentes. |
| 2.5 | `STRATEGY.md`: posicionamiento, mensaje central, propuesta de valor | 3 | DONE | Auditoría2026-10-12: FIX documental: CAB Medical, pediatría desde0meses y no publicar precios; mensaje publicado respirar/dormir/oír y agenda WhatsApp. Se retiran hipótesis presentadas como exclusividad. |
| 2.6 | Codex: revisión de estrategia y keyword map | 1 | DONE | FIX estrategia/keyword map alineados con dos áreas cortas y cinco fichas; ORL individual/recursos/navegador históricos, sin volumen/ranking inventado. Informe: docs/REVISION_2026-10-14b.md |

## FASE 3 — Arquitectura (información, SEO, WordPress)

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 3.1 | `SITEMAP.md`: URLs, intención por página, clusters ORL y Sueño | 3 | DONE | Auditoría2026-10-12: FIX sitemap vigente y menu D-072; recursos200 noindex; pilares y muestra clínica404. Catálogo real23 fichas y3 recursos documentado sin publicar. |
| 3.2 | `SEO.md`: keyword map, titles, canonicals, internal linking, mapa 301 | 3 | DONE | Auditoría2026-10-12: FIX reglas actuales:9 titles/descriptions públicos coinciden con seo.json, canonicals y robots;8 URLs en sitemap. Longitudes son guía, no edición de marca. Redirecciones/GSC con límites explícitos. |
| 3.3 | Content model: CPT condiciones / servicios / credenciales / FAQ / fuentes / revisores | 3 | DONE | Auditoría2026-10-12: FIX modelo documentado frente a MetaRegistry::definitions: cuatro CPT, prefijo di_, revisor_id, menu_order, estudio_previo texto y sin falsa UI. Implementación UI sigue5.2. |
| 3.4 | Schema map por tipo de página | 2 | DONE | Auditoría2026-10-12: FIX mapa de tipos publicado: HomePhysician, ConócemeProfilePage+Physician, ContactoContactPage+Physician, FAQPage9 preguntas solo FAQ, recursosCollectionPage. CPT descritos desde Graph, no certificados públicamente. |
| 3.5 | `ARCHITECTURE.md`: theme vs core, Elementor controlado, fuente única de verdad | 3 | DONE | Auditoría2026-10-12: FIX inventario de capas real (IIFEJS, CSS único, versión por filemtime, Adobe Fonts, header/footerPHP). NAP repetido Q-042 y UI pendientes no se presentan como entregados. |
| 3.6 | `PLUGINS.md`: tabla necesidad / opción / alternativa / decisión | 2 | DONE | ListadoWP-CLI13/10:6activos y2MU con versiones; PLUGINS actualizado y propuestas separadas. Ver docs/audit/2026-10-13/veredictos-inventario.md |
| 3.7 | Flujo de agenda y conversión (Doctoralia / WhatsApp / teléfono / formulario) | 2 | DONE | Auditoría2026-10-12: WhatsApp/teléfono/Maps/Doctoralia con destinos publicados. Eventos allowlist solo page_path; fixtures cola antesload y DNT incluso con gtag ajeno. Salida antes de descargarGA4 puede perder clic: límite declarado. |
| 3.8 | Codex: auditoría de arquitectura contra MASTER_PROMPT | 2 | DONE | FIX arquitectura/mapa: diez páginas+cinco fichas, rutas Sueño, archivos404, mini apps/gate, sin staging y D-081/083/084. Propuestas antiguas como historia. Informe: docs/REVISION_2026-10-14b.md |

## FASE 4 — Design System y dirección visual

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 4.1 | Derivar sistema digital de la identidad existente (paleta, tipografía, logo, favicon) | 3 | DONE | `DESIGN_SYSTEM.md` §2–3; favicon pendiente de exportar |
| 4.2 | Tokens globales (color, tipo, espacio, radio, sombra, motion) | 3 | DONE | `assets/css/tokens.css` |
| 4.3 | Componentes base: botones, inputs, cards, badges, iconografía Lucide | 3 | DONE | Auditoría2026-10-12: Botones D-053, cards, badges y sprite Lucide en código; sin alterar sombras. Inputs tienen tarea propia Fase8; responsive público cinco anchos sin desbordes. |
| 4.4 | Concepto visual "Sleep experience" dentro de la marca | 2 | DONE | `DESIGN_SYSTEM.md` §6; aplicado en maquetas |
| 4.5 | Mockups/prototipos: Home, Dra. Irina, página médica, artículo, móvil | 5 | DONE | Publicadas 2026-10-08 en `/preview/` (200 ×4, noindex) |
| 4.6 | `DESIGN_SYSTEM.md` | 2 | DONE | Auditoría2026-10-12: FIX addendum vigente de tokens, títulos D-049, separadores, migas D-071, logos y tarjeta beige; propuestas A/B quedan históricas. Sin cambiar tipografía/paleta aprobadas. |
| 4.7 | CHECKPOINT 2: dirección visual aprobada | 2 | DONE | 2026-10-08: v2 aprobada por el propietario («ciertos cambios los haremos después»); D-026 |

## FASE 5 — Infraestructura de código

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 5.1 | Child theme `irina-gonzalez` (estructura, enqueue, hooks Hello, setup) | 5 | REVIEW | Repaso 16/10 (Claude cloud) contra código y producción: header.php/footer.php/404/page y plantillas reales en vivo desde el go-live; enqueue con versión por filemtime; tipografía Iskra por Adobe Fonts (D-010: no se autoaloja, licencia web solo vía kit); sprite assets/icons/lucide.svg con 24 símbolos y 0 faltantes frente a los 16 iconos usados en 8 URL públicas. La nota «placeholder/falta fuentes, sprite, header/footer» era de la fase 5 inicial |
| 5.2 | Plugin `dra-irina-core`: CPT, taxonomías, campos, settings centrales (NAP, horarios, enlaces) | 5 | REVIEW | Repaso 16/10: 4 CPT, 3 taxonomías, MetaRegistry con schema REST, PracticeSettings, puerta médica por capacidades, Graph, shortcodes, tracking y 17 widgets Elementor di-* (Rendering/Elementor) en producción desde el go-live (pruebas en WP real: QA de cierre, Q-063/Q-064/D-087). UI de meta boxes con repetidores: no se construye; el contenido clínico se carga con los builds y se aprueba en la app de revisión (D-040/D-076), sin edición manual en wp-admin. Menú único «Dra. Irina» (D-087) |
| 5.3 | Fuente única de verdad: módulo de datos de contacto/profesionales reutilizable | 3 | REVIEW | En el sitio: PracticeSettings alimenta schema, pie, barra móvil, formulario, WhatsApp y shortcodes. En los builds (Q-042): la dirección sale de tools/content/practice.py (build-pages FAQ/Contacto/SEO y build-home); JSON regenerado idéntico (sin despliegue de contenido). tools/audit/nap-check.py falla si un build escribe calle, interior, CP o teléfonos a mano y compara practice.py con el schema publicado: 0 fallas (prueba negativa incluida). Hospitales y credenciales siguen como texto aprobado (D-049), fuera de NAP |
| 5.4 | Schema JSON-LD propio sin duplicar con el plugin SEO | 3 | DONE | Graph propio y filtro RankMath contrastados: fixture conserva WebSite/WebPage/Breadcrumb/Image y elimina duplicados profesionales. Diez nodos públicos, uno por página, mismoNAP; Q-058 verificado. Informe: docs/REVISION_2026-10-16.md |
| 5.5 | Tooling: linters (PHPCS WPCS, stylelint, eslint), build de assets, fuentes WOFF2 locales | 2 | REVIEW | PHPCS+WPCS en verde. tools/lint: ESLint 9 (recommended) y stylelint 16 (config-recommended) con versiones fijas; `npm ci && npm run lint` en verde. Hallazgos corregidos: «}» suelta en components.css desde el 10-oct que anulaba una regla de grano (se quitan ambas; capturas antes/después idénticas píxel a píxel en Sueño, Inicio y Primera consulta); `items` redeclarado en main.js (credenciales → credItems; carrusel probado). Sin build de sprite (archivo estático de 24 símbolos) ni WOFF2 locales (D-010) |
| 5.6 | Instalación/configuración plugins aprobados en staging | 2 | DESCOPED | D-080: sin staging; los plugins ya están en producción con respaldo previo |
| 5.7 | Codex: auditoría de código (PHP/JS/CSS/seguridad) | 3 | DONE | PHPCS/PHP79/JS externo+inline/bash18 pasan; permisos REST propios 7/7, corpus antispam/regresiones, escapes/nonces/admin/revisar revisados. Sin mutaciones; Q-040/Q-042 conservan alcance. Informe: docs/REVISION_2026-10-14b.md |

## FASE 6 — Build del núcleo visual

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 6.1 | Header + navegación + CTA + mobile bar | 3 | DONE | Verificado en /inicio-v2/; «Cómo llegar» aparece al cargar maps_url |
| 6.2 | Footer | 2 | DONE | Verificado en /inicio-v2/ |
| 6.3 | Home | 5 | DONE | /inicio-v2/ en producción (noindex): 8 widgets, header/footer del tema, QA visual OK escritorio y móvil; pendiente CP3 |
| 6.4 | Página Dra. Irina (entidad/autora) | 5 | DONE | FIX→DONE: pública 200/h1/main/schema/migas, narrativa y reduced-motion verificados. Teclado de logos y dos títulos D-049 corregidos y probados localmente; despliegue pendiente. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |
| 6.5 | Template página médica (condición/servicio) + 1 página representativa | 5 | DONE | Cinco fichas /sueno/ 200, h1/main, sin Borrador, schema y migas/canónicas correctas. Cinco rutas antiguas del tipo correspondiente 301; diez borradores 404. Relacionados filtrados por publish. Informe: docs/REVISION_2026-10-14.md |
| 6.6 | Template artículo con metadatos de autoría y revisión médica | 3 | DESCOPED | D-088: recursos archivados (D-076); single-recurso.php se conserva para cuando se reabra |
| 6.7 | Responsive real (5 tamaños) y motion con `prefers-reduced-motion` | 3 | DONE | 16rutas×5anchos sin overflow; hero final y reduced-motion/transition comprobados. Q-050 A/B14/10 vigente, no nuevo CWV. Logos pendientes lazy resueltos, error captcha del harness aclarado. Informe: docs/REVISION_2026-10-16.md |
| 6.8 | QA visual (screenshots desktop/tablet/móvil) | 2 | DONE | Dos hojas de contacto versionadas390/1440 (16rutas top/medio/pie) vistas; cuatro capturas nativas Home390/768/1440 y Contacto390 revisadas. Sin cambios visuales ni certificación Safari. Informe: docs/REVISION_2026-10-16.md |
| 6.9 | Codex: auditoría del núcleo | 3 | DONE | FIX Q-060 helper para CPT publicados en /sueno/{slug}/ con taxonomía/ancla; 7/7 fixtures. Widgets/templates/filtros publish revisados, sin borrar legacy. Correctivo local pendiente despliegue. Informe: docs/REVISION_2026-10-14b.md |
| 6.10 | CHECKPOINT 3: Home + sistema visual aprobados | 2 | DONE | 2026-10-08: portada = Home v2, logotipo completo, foto autorizada (D-034) |

## FASE 7 — Contenido médico

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 7.1 | Drafts: Home, Dra. Irina, ORL pilar, Sueño pilar | 5 | DONE | ORL/Sueño cortas publicadas, títulos D-049, seis anclas bajo cabecera, cinco enlaces de fichas en Sueño y sin desborde en cuatro anchos. Publicación declarada 13/10 18:50 tras aprobación y OK. Informe: docs/REVISION_2026-10-14.md |
| 7.2 | Drafts: condiciones y servicios confirmados (OFRECE) | 5 | DONE | 14 condiciones + 9 tratamientos redactados con las respuestas de la Dra. (D-039); D-076: archivados en borrador, sin enlaces, menú ni archivo público (404) |
| 7.3 | Drafts: primera consulta, pacientes, contacto | 2 | DONE | DONE: Primera consulta/Contacto/FAQ 200, sin Borrador; títulos, FAQPage, fondo continuo y cuatro pasos verificados. Pacientes cubierto por Primera consulta + FAQ. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |
| 7.4 | Bibliografía verificable por página | 2 | DESCOPED | D-088: fuentes propuestas por ficha (SMORL, AAO-HNS, AASM, EPOS, ARIA) archivadas con las fichas; las páginas cortas no hacen afirmaciones que requieran cita |
| 7.5 | Revisión técnica/SEO de drafts | 2 | DESCOPED | D-088: los borradores son las 21 fichas archivadas (D-082); se reabre si José decide publicar más |
| 7.6 | Revisión médica por la Dra. Irina (MEDICAL REVIEW → APPROVED) | 5 | DONE | Atestación PLAN/BATON/D-082: revisor_medico aprobó ORL 13/10 18:03 y Sueño 18:38; 26 fichas aprobadas, última 18:18. Backend rol/capacidad/fecha/autor/log revisado; sin nueva lectura del log privado ni aprobaciones de agentes. Informe: docs/REVISION_2026-10-14.md |
| 7.7 | CHECKPOINT 4 | 1 | DONE | OK previo de José documentado D-078/D-082/BATON; publicación local declarada 13/10 18:50. Se corrige fecha histórica 14/10 en notas, sin publicar ni alterar clínica. Informe: docs/REVISION_2026-10-14.md |

## FASE 8 — Escalado del sitio

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 8.1 | Resto de páginas de condición/servicio | 5 | DESCOPED | D-076: sitio corto; las 26 fichas quedan en borrador, sin borrarse |
| 8.2 | Centro de recursos/artículos iniciales | 3 | DESCOPED | D-076: `/recursos/` responde 404; los 3 artículos siguen en borrador con fuentes |
| 8.3 | Contacto completo (mapa, acceso, estacionamiento, horario, formulario) | 3 | DONE | Contacto público completo: dirección/mapa/Cómo llegar, estacionamiento/acceso, previa cita D-079, WhatsApp/tel, formulario y urgencias. Captcha DOM presente; entrega real la prueba José, no agentes. Informe: docs/REVISION_2026-10-14.md |
| 8.4 | Formulario con minimización de datos + SMTP + gracias (noindex) | 3 | DONE | En vivo 2026-10-10 (D-050); SMTP por constantes con contraseña de aplicación |
| 8.5 | Legal: borradores aviso de privacidad (integral/simplificado), cookies, términos, disclaimer, emergencias | 3 | DONE | Publicados 2026-10-10 (D-051); el propietario puede pedir cambios (8.6) |
| 8.6 | Revisión legal/regulatoria del propietario (COFEPRIS, privacidad) | 3 | OWNER | Mejora continua por decisión de José (D-086); no bloquea el lanzamiento |
| 8.7 | 404 útil (noindex), búsqueda interna si procede | 2 | DONE | `404.php`: cabecera a sangre, rutas publicadas (omite borradores), WhatsApp, nota para enlaces del dominio viejo; sin buscador (sitio pequeño) |
| 8.8 | Navegador de síntomas (orientación, no diagnóstico) | 3 | DESCOPED | D-076: la portada enlaza los motivos a las anclas de ORL y Sueño |
| 8.9 | Favicon/app icons, OG template | 1 | DONE | FIX→DONE: JPEG OG/Twitter 1200×630 e iconos públicos verificados en once rutas; manifest e integración con site icon preparados y probados localmente. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |

## FASE 9 — SEO técnico, schema, analítica, local

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 9.1 | On-page completo: titles, metas, H1, canonicals, OG por página | 3 | DONE | Quince indexables200, h1/main único, canónica propia, title39–62/description64–158, OG presente. Sin nuevos cambios de copy/build. Informe: docs/REVISION_2026-10-14b.md |
| 9.2 | Sitemap, robots, redirects 301 (mapa OLD → NEW) | 2 | DONE | Sitemap page10/condicion2/tratamiento3 sin archivos404/recursos, robots público correcto. Dominio aparcado raíz301 confirmado; cinco rutas antiguas301 recomprobadas en support.json. Informe: docs/REVISION_2026-10-14b.md |
| 9.3 | Validación de schema (Physician/Person/WebSite/Article/Breadcrumb) | 2 | DONE | Q-058 confirmado: Physician+Person único, ProfilePage/ContactPage/FAQPage/MedicalWebPage de áreas/fichas y BreadcrumbList. Validator0/0 Sueño/Dra. por atestación local, no nueva validación externa universal. Informe: docs/REVISION_2026-10-14b.md |
| 9.4 | GA4 + Search Console + eventos de conversión | 2 | DONE | FIX→DONE: GA4 único tras load; seis eventos, DNT y exclusión de editores/ID inválido probados sin enviar a Google. GSC confirmado en DISCOVERY §1.6/D-022; cobertura actual sigue 1.4. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |
| 9.5 | Alineación Google Business Profile y Doctoralia (NAP) | 2 | OWNER | Q-062VERIFIED: literal383caracteres cotejado con sitio, siete servicios en ORL/Sueño, URLUTM200/canónica limpia y reservaWhatsApp coincidente. Fuente es QA local, no lectura nueva del perfil. Horario real y Doctoralia siguen mejora continua D-086. Informe: docs/REVISION_2026-10-16b.md |
| 9.6 | Codex: QA SEO | 2 | DONE | QA quince200/h1/main/canónicas, longitudes≤62/158 y OG/schema/migas. Robots/redirecciones/sitemaps contrastados; sin inferir indexación. Informe: docs/REVISION_2026-10-14b.md |

## FASE 10 — Performance, seguridad, accesibilidad

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 10.1 | Performance budget, caché, imágenes, fuentes, JS/CSS mínimos | 3 | DONE | Auditoría 13/10: políticas de optimización aprobadas verificadas con UA Galaxy/iPhone/desktop y pesos registrados. Móvil 1600/q95, escritorio q100, alfa lossless y diferidos. No certifica CWV ni todos los presupuestos antiguos; informe integral en 10.2. Informe histórico: docs/REVISION_CINCO_TAREAS_2026-10-13.md. D-081 sustituye q100 escritorio por derivados q95 en cabeceras/fondo; ver auditoría 14/10 y PERFORMANCE |
| 10.2 | Medición CWV/Lighthouse antes vs después (`PERFORMANCE.md`) | 2 | DONE | PERFORMANCE antes/después/después 2 y límites auditados; CLS/peso q95 documentados. Nuevo A/B tras Q-050: Sueño móvil LCP 4,446/4,454 s (3 pasadas por modo), CLS 0. Laboratorio, no CWV de campo. Informe: docs/REVISION_2026-10-14.md |
| 10.3 | Hardening (`SECURITY.md`): 2FA, login, headers, permisos, XML-RPC/REST, backups | 3 | DONE | SECURITY revisado y medidas contrastadas; Two Factor instalado/WP2FA activo por atestación local14/10 18:20, Hostinger/Google por propietario. Revocación REST y D-085 respetados; no login ni secretos. Informe: docs/REVISION_2026-10-14b.md |
| 10.4 | Accesibilidad WCAG 2.2 AA: teclado, focus, contraste, labels, skip link | 3 | DONE | Axe4.14: quince+404×1440/390, cero violaciones detectadas; incomplete contraste no aprobado manualmente. LH12.2.1 a11y seis100 y menú público teclado normal/reducido pasan. Lector de pantalla pendiente explícito según encargo. Informe: docs/REVISION_2026-10-14b.md |
| 10.5 | Codex: QA performance + seguridad + accesibilidad | 3 | DONE | PERFORMANCE, SECURITY, axe32 muestras y LH a11y seis100 revisados. Sin CWV de campo/pentest/lector de pantalla ni certificación universal AA. Informe: docs/REVISION_2026-10-14b.md |

## FASE 11 — QA integral y pre-launch

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 11.1 | QA funcional (enlaces, forms, email, agenda, WhatsApp, maps, 404, cookies, analytics) | 3 | DONE | Quince destinos internos200, WhatsApp/tel/mapa únicos; formulario soloContacto, prueba humanaJosé15/10 según atestación, no repetida. CookiesGA4 por muestra escrita15/10; GA bloqueado en nueva prueba. Informe: docs/REVISION_2026-10-16.md |
| 11.2 | QA dispositivos (Chrome, Edge, Safari, Firefox, Android, iOS) | 2 | DONE | Chromium 80 vistas; Edge 155/Firefox 158 (sesión local, 32 c/u); Safari en iPhone (José 15/10); Android real: Galaxy S24+ Chrome, José 16/10, «todo bien» salvo la barra en horizontal (Q-065 desplegada y verificada; José la confirma en el S24+ el 16/10: «todo bien»). QA «Android real» | Cierre Codex16/10 autorizado por José: confirmación94a2479 y QA «Despliegue de D-087 / Q-065», producción declarada de864bc; evidencia de dispositivo por atestación, no nueva sesión de Codex. Firefox conserva el límite de consola no comprobada.
| 11.3 | QA contenido (consistencia NAP, nombres, títulos, claims, fechas) | 2 | DONE | NAP idéntico en10nodos profesionales; contacto/footer coherentes, horario oculto y Previa cita. FAQ vieja ausente, e434d2a en vivo; sin Borrador/placeholders. Claims/fechas por aprobación D-078/D-082, sin nueva revisión clínica. Informe: docs/REVISION_2026-10-16.md |
| 11.4 | Consistency check de Codex contra MASTER_PROMPT | 2 | DONE | FIX documentos activos y coordinación coherentes con D-076…085. README/HANDOFF sin staging ni envío de URL con token; historia etiquetada, gate médico intacto. Informe: docs/REVISION_2026-10-14b.md |
| 11.5 | `LAUNCH_CHECKLIST.md` completado | 2 | DONE | FIX checklist con trazabilidad por bloque y límites: auxiliaresnoindex, validaciónschema no universal, GSC fechado, Android y rollback pendientes. 9.5/8.6/11.6 OWNER siguen explícitos; documentoDONE no aprueba lanzamiento. Informe: docs/REVISION_2026-10-16.md |
| 11.6 | CHECKPOINT 5: pre-launch aprobado | 2 | DONE | 2026-10-16: José aprueba LAUNCH_CHECKLIST.md; horario de GBP, Doctoralia y revisión legal quedan como mejora continua (D-086) |

## FASE 12 — Launch y post-launch

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 12.1 | Deploy a producción con backup y rollback (sin staging, D-080) | 5 | DONE | FIX→DONE: ensayos aportados en copia aparte (código idéntico/lint79; DB30tablas/mismasfilas), alcance decidido por José. Guardas Q-066/067 corregidas localmente y19casos de stubs pasan; no SQL/SSH real por Codex. Restauración en vivo no ensayada, límite aceptado, no exigirla. Informe: docs/REVISION_2026-10-16b.md |
| 12.2 | Día 0 validación; Día 1 errores críticos | 2 | DONE | Quince200/index/follow/canónica propia,30vistas390/1440 sin overflow/pageerror/imágenes fallidas;404español/noindex, auxiliaresnoindex,403readme/xmlrpc,301/cabeceras/SSL. Servidor por atestación local. Sin logs de visitas de hPanel: snapshot operativo, no certificado de tráfico real; seguimiento12.3. Informe: docs/REVISION_2026-10-16b.md |
| 12.3 | Día 7 Search Console + analytics; Día 30 indexación/keywords/conversiones/CWV | 2 | TODO | |
| 12.4 | `MAINTENANCE.md` + plan SEO 6–12 meses + KPIs | 3 | DONE | FIX rutinas/KPIs sin cadencia automática de fichas; eventos no pacientes/correos, objetivo indexación solo15indexables. Runbook código/htaccess/DB/medios/miniapps con rollback en vivo no probado (ensayos en copia12.1 cerrados16b); seguimiento7/30 no crea automatización. Informe: docs/REVISION_2026-10-16.md |

## FASE 13 — Versión en inglés (`/en/`, Anexo A) · cuenta aparte, no infla el avance del sitio en español

Criterio de entrada: contenido español base aprobado por la Dra. + CP3 superado + fecha confirmada por el propietario (propuesta en `NEXT.md`).

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 13.1 | Decisión de herramienta (plugin multiidioma vs. nativo en el core) · OD-008 | 2 | OWNER | Prioridad: que no rompa Sensia, el schema único ni el rendimiento |
| 13.2 | Glosario clínico es/en en `docs/GLOSSARY_EN.md` y keyword map en inglés propio | 2 | TODO | |
| 13.3 | Páginas base en inglés (Home, Dra., ORL, Sueño, Primera consulta, Contacto, legales) en borrador | 5 | TODO | Nada se publica sin revisión de la Dra. |
| 13.4 | Condiciones/tratamientos elegidos por la Dra. | 3 | TODO | |
| 13.5 | hreflang recíproco + x-default, sitemap, OG, schema `inLanguage`, selector de idioma, WhatsApp, 404/gracias | 3 | TODO | |
| 13.6 | Revisión de la Dra. y legal (nota de prevalencia del español) | 2 | OWNER | ext |
| 13.7 | QA y publicación de `/en/` | 2 | TODO | |

---

## Resumen reproducible

Ver `PROGRESS.md`, generado por `python tools/audit/progress.py`. La Fase 13 cuenta aparte. Se retiró el resumen manual porque sus filas no coincidían con las 95 tareas y 253 pesos reales del plan español.

Normalización 2026-10-10: solo DONE suma; no se cambian estados clínicos ni se da por verificado un despliegue pendiente. Las notas históricas «borrador», «falta CP3» o «scaffold» se interpretan con el estado operativo actual en STATUS; no son instrucciones de despliegue.
