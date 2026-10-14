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
| 1.2 | Inventario completo de producción + clasificación KEEP/REPLACE/REMOVE | 3 | REVIEW | Inventario13/10 actualizado; falta lista de44adjuntos y clasificación/destino por ítem, más entradas pie/legal. Ver docs/audit/2026-10-13/veredictos-inventario.md |
| 1.3 | Auditoría pública: robots, sitemap, canonicals, noindex, headers, cache, CWV baseline | 2 | TODO | |
| 1.4 | Revisar Search Console: cobertura, URLs indexadas/excluidas, sitemap enviado | 2 | REVIEW | Capturas de José 2026-10-14 (datos de GSC al 8/10/26): 5 indexadas, 9 sin indexar por 5 motivos: redirección 3, noindex 2, 404 1, rastreada sin indexar 2, duplicada con canónica elegida por Google 1; portada, Dra., Primera consulta y Contacto «La URL está en Google» (José, 2026-10-14). Aviso de traslado de otro sitio (otorrino-monterrey.com, 301 verificado). Ejemplos revisados 2026-10-14: 404 = `/wp-*.php` (patrón literal rastreado el 10 ago, no es una página; 404 correcto, sin acción); rastreada sin indexar = aviso-de-privacidad y aviso-medico (legales, rastreadas el 10 oct, index + canónica correctas; decisión de Google, sin acción); duplicada = /links/ (rastreada el 8 ago; hoy noindex por diseño, pasará a «excluida por noindex»). Sin problemas a corregir. Sitemaps: sitemap_index.xml enviado el 13 oct, «Correcto»; Google leyó page-sitemap.xml por última vez el 4 oct (2 URL; hoy lista 8). José lo reenvió el 2026-10-14 para forzar nueva lectura; repetir tras publicar las 5 fichas de sueño (aparecerán condicion- y tratamiento-sitemap) |
| 1.5 | Limpieza y configuración base: idioma, zona horaria, formatos, Site Kit fuera, Astra fuera, Hello activo, hardening wp-config | 2 | DONE | InformeWP-CLI13/10: es_MX,Monterrey,formatos,Hello/child,ausenciaAstra/SiteKit y hardening confirmados. DISPLAY=true sinDEBUG no bloquea. Ver docs/audit/2026-10-13/veredictos-inventario.md |
| 1.6 | Verificar backups del hosting y crear backup completo verificado (archivos + BD) | 3 | DONE | Manual 2026-10-07 16:41 descargado; semanal automático activo |
| 1.7 | Crear staging protegido (noindex, fuera de sitemap, auth) | 3 | DESCOPED | D-080: el plan Premium no incluye Staging y José no lo amplía; sitio corto, respaldo antes de cada despliegue y prueba de formulario de José con su correo |
| 1.8 | Documentar accesos (sin secretos) y procedimiento de rollback | 1 | DONE | Auditoría2026-10-12: FIX documental: rollback vigente según deploy-code.sh (~/deploy-backups/<ts>); runbook de reset marcado histórico y no ejecutable; sin credenciales. |
| 1.9 | Codex: auditoría de seguridad del estado actual | 2 | TODO | |

## FASE 2 — Investigación y estrategia

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 2.1 | `docs/REFERENCE_RESEARCH.md`: Stanford Sleep, Mayo, Cleveland, Johns Hopkins, Sleep Doctor, ENT privados, referentes UX | 3 | DONE | Auditoría2026-10-12: Referentes identifican snippets/inferencias y bloqueo de lectura; checklist visual separado. Investigación preliminar útil, sin certificar UX ajena. |
| 2.2 | Competencia local Monterrey (ORL + sueño): sitios, GBP, Doctoralia | 2 | DONE | Auditoría2026-10-12: FIX documental: oportunidades son hipótesis de muestra, sin afirmar ausencia universal de competidores; precios/horarios se subordinan a D-054/D-032. |
| 2.3 | Keyword research local y de condiciones (`KEYWORDS.md`) | 3 | DONE | Auditoría2026-10-12: Keyword map sin volúmenes inventados, limitaciones de SERP declaradas; coherencia con slugs del build. GSC/GBP quedan OWNER. |
| 2.4 | Perfil de paciente + motivos de consulta top 5–10 | 2 | DONE | Auditoría2026-10-12: DISCOVERY §0b contiene11 motivos confirmados; ocho filas del build agrupan los11 (ronquido/apnea, oído/cerumen, amígdalas/niños). Sito fresco y estrategia coherentes. |
| 2.5 | `STRATEGY.md`: posicionamiento, mensaje central, propuesta de valor | 3 | DONE | Auditoría2026-10-12: FIX documental: CAB Medical, pediatría desde0meses y no publicar precios; mensaje publicado respirar/dormir/oír y agenda WhatsApp. Se retiran hipótesis presentadas como exclusividad. |
| 2.6 | Codex: revisión de estrategia y keyword map | 1 | TODO | |

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
| 3.8 | Codex: auditoría de arquitectura contra MASTER_PROMPT | 2 | TODO | |

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
| 5.1 | Child theme `irina-gonzalez` (estructura, enqueue, hooks Hello, setup) | 5 | DOING | Scaffold en repo: style, functions, inc/*, tokens, base, components, sleep, main.js, templates placeholder. Falta: fuentes, sprite Lucide completo, header/footer, templates reales (Fase 6) |
| 5.2 | Plugin `dra-irina-core`: CPT, taxonomías, campos, settings centrales (NAP, horarios, enlaces) | 5 | DOING | Scaffold: 4 CPT, 3 taxonomías, MetaRegistry (REST schema + sanitización), PracticeSettings, workflow médico por capacidades, schema Graph, shortcodes, tracking. Falta: UI de meta boxes (repetidores), widgets Elementor, pruebas en WP real |
| 5.3 | Fuente única de verdad: módulo de datos de contacto/profesionales reutilizable | 3 | TODO | |
| 5.4 | Schema JSON-LD propio sin duplicar con el plugin SEO | 3 | TODO | |
| 5.5 | Tooling: linters (PHPCS WPCS, stylelint, eslint), build de assets, fuentes WOFF2 locales | 2 | DOING | PHPCS + WPCS configurados y en verde. Falta: stylelint/eslint, build de sprite, fuentes |
| 5.6 | Instalación/configuración plugins aprobados en staging | 2 | DESCOPED | D-080: sin staging; los plugins ya están en producción con respaldo previo |
| 5.7 | Codex: auditoría de código (PHP/JS/CSS/seguridad) | 3 | TODO | |

## FASE 6 — Build del núcleo visual

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 6.1 | Header + navegación + CTA + mobile bar | 3 | DONE | Verificado en /inicio-v2/; «Cómo llegar» aparece al cargar maps_url |
| 6.2 | Footer | 2 | DONE | Verificado en /inicio-v2/ |
| 6.3 | Home | 5 | DONE | /inicio-v2/ en producción (noindex): 8 widgets, header/footer del tema, QA visual OK escritorio y móvil; pendiente CP3 |
| 6.4 | Página Dra. Irina (entidad/autora) | 5 | DONE | FIX→DONE: pública 200/h1/main/schema/migas, narrativa y reduced-motion verificados. Teclado de logos y dos títulos D-049 corregidos y probados localmente; despliegue pendiente. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |
| 6.5 | Template página médica (condición/servicio) + 1 página representativa | 5 | DOING | medical-page.php; 23 fichas completas en borrador; falta QA visual con sesión |
| 6.6 | Template artículo con metadatos de autoría y revisión médica | 3 | DOING | single-recurso.php |
| 6.7 | Responsive real (5 tamaños) y motion con `prefers-reduced-motion` | 3 | TODO | |
| 6.8 | QA visual (screenshots desktop/tablet/móvil) | 2 | TODO | |
| 6.9 | Codex: auditoría del núcleo | 3 | TODO | |
| 6.10 | CHECKPOINT 3: Home + sistema visual aprobados | 2 | DONE | 2026-10-08: portada = Home v2, logotipo completo, foto autorizada (D-034) |

## FASE 7 — Contenido médico

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 7.1 | Drafts: Home, Dra. Irina, ORL pilar, Sueño pilar | 5 | DOING | Home y Dra. publicadas. D-076: ORL y Sueño reescritas como páginas cortas (qué atiendo con una línea por motivo, cómo trabajo, CTA), sin marcadores ni FAQ, en borrador hasta 7.6; despliegue pendiente |
| 7.2 | Drafts: condiciones y servicios confirmados (OFRECE) | 5 | DONE | 14 condiciones + 9 tratamientos redactados con las respuestas de la Dra. (D-039); D-076: archivados en borrador, sin enlaces, menú ni archivo público (404) |
| 7.3 | Drafts: primera consulta, pacientes, contacto | 2 | DONE | DONE: Primera consulta/Contacto/FAQ 200, sin Borrador; títulos, FAQPage, fondo continuo y cuatro pasos verificados. Pacientes cubierto por Primera consulta + FAQ. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |
| 7.4 | Bibliografía verificable por página | 2 | DOING | Fuentes propuestas por ficha (SMORL, AAO-HNS, AASM, EPOS, ARIA), archivadas con las fichas. D-076: las páginas cortas no hacen afirmaciones que requieran cita; Codex decide si se cierra |
| 7.5 | Revisión técnica/SEO de drafts | 2 | TODO | |
| 7.6 | Revisión médica por la Dra. Irina (MEDICAL REVIEW → APPROVED) | 5 | OWNER | D-076: solo ORL y Sueño cortas, en la app `/briefing/guia/` (aprobar o pedir cambios; nunca publica) |
| 7.7 | CHECKPOINT 4 | 1 | OWNER | ext |

## FASE 8 — Escalado del sitio

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 8.1 | Resto de páginas de condición/servicio | 5 | DESCOPED | D-076: sitio corto; las 26 fichas quedan en borrador, sin borrarse |
| 8.2 | Centro de recursos/artículos iniciales | 3 | DESCOPED | D-076: `/recursos/` responde 404; los 3 artículos siguen en borrador con fuentes |
| 8.3 | Contacto completo (mapa, acceso, estacionamiento, horario, formulario) | 3 | REVIEW | Verificado en vivo 2026-10-13: dirección completa (CAB, piso 2, consultorio 6), mapa con estilo D-065 + «Cómo llegar», estacionamiento gratuito, elevador y silla de ruedas, «previa cita» (D-032, José lo mantiene), formulario, WhatsApp/tel y aviso de urgencias; un h1. José: sin referencias extra (basta el mapa); foto de fachada pendiente, opcional (D-079) |
| 8.4 | Formulario con minimización de datos + SMTP + gracias (noindex) | 3 | DONE | En vivo 2026-10-10 (D-050); SMTP por constantes con contraseña de aplicación |
| 8.5 | Legal: borradores aviso de privacidad (integral/simplificado), cookies, términos, disclaimer, emergencias | 3 | DONE | Publicados 2026-10-10 (D-051); el propietario puede pedir cambios (8.6) |
| 8.6 | Revisión legal/regulatoria del propietario (COFEPRIS, privacidad) | 3 | OWNER | ext |
| 8.7 | 404 útil (noindex), búsqueda interna si procede | 2 | DONE | `404.php`: cabecera a sangre, rutas publicadas (omite borradores), WhatsApp, nota para enlaces del dominio viejo; sin buscador (sitio pequeño) |
| 8.8 | Navegador de síntomas (orientación, no diagnóstico) | 3 | DESCOPED | D-076: la portada enlaza los motivos a las anclas de ORL y Sueño |
| 8.9 | Favicon/app icons, OG template | 1 | DONE | FIX→DONE: JPEG OG/Twitter 1200×630 e iconos públicos verificados en once rutas; manifest e integración con site icon preparados y probados localmente. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |

## FASE 9 — SEO técnico, schema, analítica, local

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 9.1 | On-page completo: titles, metas, H1, canonicals, OG por página | 3 | DOING | Base de Rank Math configurada (2026-10-08) |
| 9.2 | Sitemap, robots, redirects 301 (mapa OLD → NEW) | 2 | DOING | Sitemap restringido a tipos públicos; 301 del dominio viejo |
| 9.3 | Validación de schema (Physician/Person/WebSite/Article/Breadcrumb) | 2 | DOING | Physician/WebSite/WebPage/ProfilePage/ContactPage validados en vivo (2026-10-10); `image` añadida; Article/Breadcrumb al publicar fichas |
| 9.4 | GA4 + Search Console + eventos de conversión | 2 | DONE | FIX→DONE: GA4 único tras load; seis eventos, DNT y exclusión de editores/ID inválido probados sin enviar a Google. GSC confirmado en DISCOVERY §1.6/D-022; cobertura actual sigue 1.4. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |
| 9.5 | Alineación Google Business Profile y Doctoralia (NAP) | 2 | OWNER | Requiere acceso |
| 9.6 | Codex: QA SEO | 2 | TODO | |

## FASE 10 — Performance, seguridad, accesibilidad

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 10.1 | Performance budget, caché, imágenes, fuentes, JS/CSS mínimos | 3 | DONE | DONE: políticas de optimización aprobadas verificadas con UA Galaxy/iPhone/desktop y pesos registrados. Móvil 1600/q95, escritorio q100, alfa lossless y diferidos. No certifica CWV ni todos los presupuestos antiguos; informe integral en 10.2. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md |
| 10.2 | Medición CWV/Lighthouse antes vs después (`PERFORMANCE.md`) | 2 | DOING | «Antes» medido 2026-10-14 (5 páginas × móvil/escritorio × 3 pasadas, medianas y límites en PERFORMANCE.md); a11y corregida en 8bb93a0. Falta el «después» tras desplegar, desde la sesión local o PSI, y la decisión de José sobre la foto de escritorio |
| 10.3 | Hardening (`SECURITY.md`): 2FA, login, headers, permisos, XML-RPC/REST, backups | 3 | DOING | Cabeceras de seguridad en el plugin (Q-023); resto pendiente |
| 10.4 | Accesibilidad WCAG 2.2 AA: teclado, focus, contraste, labels, skip link | 3 | DOING | Auditoría en vivo y correcciones (Q-022); falta decisión sobre el contraste del botón de WhatsApp |
| 10.5 | Codex: QA performance + seguridad + accesibilidad | 3 | TODO | |

## FASE 11 — QA integral y pre-launch

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 11.1 | QA funcional (enlaces, forms, email, agenda, WhatsApp, maps, 404, cookies, analytics) | 3 | TODO | |
| 11.2 | QA dispositivos (Chrome, Edge, Safari, Firefox, Android, iOS) | 2 | TODO | |
| 11.3 | QA contenido (consistencia NAP, nombres, títulos, claims, fechas) | 2 | TODO | |
| 11.4 | Consistency check de Codex contra MASTER_PROMPT | 2 | TODO | |
| 11.5 | `LAUNCH_CHECKLIST.md` completado | 2 | TODO | |
| 11.6 | CHECKPOINT 5: pre-launch aprobado | 2 | OWNER | ext |

## FASE 12 — Launch y post-launch

| ID | Tarea | Peso | Estado | Notas |
|---|---|---|---|---|
| 12.1 | Deploy a producción con backup y rollback (sin staging, D-080) | 5 | TODO | Scripts de tools/deploy/ con respaldo en ~/deploy-backups/; falta documentar y probar el rollback |
| 12.2 | Día 0 validación; Día 1 errores críticos | 2 | TODO | |
| 12.3 | Día 7 Search Console + analytics; Día 30 indexación/keywords/conversiones/CWV | 2 | TODO | |
| 12.4 | `MAINTENANCE.md` + plan SEO 6–12 meses + KPIs | 3 | TODO | |

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
