# BATON — Transferencia operativa Claude ↔ Codex

Última actualización: 2026-10-07 (segunda entrega) · Entrega: Claude → Codex

## Contexto operativo

- Proyecto: sitio web de la Dra. Irina González Sáez (ORL + trastornos respiratorios del sueño), Monterrey.
- Dominio existente: `https://drairinagonzalez.com` (WordPress, título "Dra. Irina González Sáez"). Registrado en Google Search Console; existe sitemap enviado y alertas de cobertura (páginas excluidas por `noindex` y por canónica elegida por Google distinta). Usuario WP `irina` creado el 2026-10-06.
- Hosting: probablemente Hostinger (correo de verificación de cuenta en agosto 2026) — **POR CONFIRMAR**. El otro sitio del propietario (idenbauer.com) corre Hello Elementor 3.4.9 + Elementor Pro + Fluent Forms + FluentSMTP + LiteSpeed Cache + Rank Math + Safe SVG, lo que sugiere el stack y licencias disponibles.
- Repositorio: `bellorin-apps/Irinaweb`, rama de trabajo `ccr-6254502d-xly8ww`. Aún no contiene código WordPress; solo documentación de Fase 0.
- Entorno de ejecución de Claude: contenedor cloud con PHP 8.3, Node 22, Composer, Git. Red de salida restringida: `drairinagonzalez.com` y `doctoralia.com.mx` denegados por política del entorno.

## Qué hizo Claude en esta entrega

1. Creó la base documental: `MASTER_PROMPT.md` (v1.0), `README.md`, `PLAN.md`, `STATUS.md`, `NEXT.md`, `BATON.md`, `DECISIONS.md`, `CHANGELOG.md`, `QA.md`, `SEO.md`, `DISCOVERY.md`, `docs/AUDIT_WP.md`, `docs/PHOTO_SHOTLIST.md`.
2. Recolectó información existente con fuente verificable (Drive del propietario, correo, búsqueda pública). Ningún dato se inventó; todo lo no confirmado por la Dra. está marcado.
3. Extrajo la identidad visual existente (logo vectorial, paleta, tarjetas).
4. Detectó que la única sesión fotográfica disponible (2022) es personal y no utilizable; preparó shot list.

## Archivos tocados

Todos los `.md` de raíz y `docs/`. Sin código todavía.

## Riesgos conocidos

- Datos de contacto y dirección provienen de tarjetas de presentación diseñadas por el propietario (2025) y coinciden con Doctoralia, pero **no están confirmados expresamente por la Dra.** No publicar hasta confirmación.
- Certificación del Consejo Mexicano de ORL y CCC: acreditación del examen (febrero 2025) con diploma anunciado para mayo 2025. Falta ver el diploma/vigencia y número.
- Cédula profesional mexicana: trámite iniciado en marzo 2025; estado desconocido.

## Segunda entrega (Fase 2 parcial)

Tres investigaciones producidas con subagentes y verificadas por Claude en su forma (no en su contenido visual, porque la red del entorno bloqueó todos los dominios de referencia):

- `docs/REFERENCE_RESEARCH.md` — cada afirmación marcada [S] (snippet/fuente secundaria) o [I] (inferencia a verificar); Anexo B con checklist de verificación visual.
- `docs/COMPETITION_MONTERREY.md` — 10 competidores ORL + 6 actores de sueño; 31 ítems marcados "no verificado".
- `KEYWORDS.md` — keyword map sin volúmenes; evidencia SERP; 11 reglas anticanibalización; títulos propuestos.

## Tercera entrega (Fase 2/3)

`STRATEGY.md`, `SITEMAP.md`, `ARCHITECTURE.md`, `PLUGINS.md` (v0.1). Decisiones D-013 a D-017 y OD-006 en `DECISIONS.md`.

## Cuarta entrega (Fase 5 scaffold)

`wp-content/plugins/dra-irina-core` y `wp-content/themes/irina-gonzalez`. PHPCS (WordPress + PHPCompatibilityWP) en verde con `composer install && vendor/bin/phpcs`. Sin pruebas en WordPress real todavía (bloqueo de red y sin acceso al sitio).

## Quinta entrega (Fase 1)

Backup, cambio de dominio principal, 301, inventario (`docs/audit/`) y clasificación en `docs/AUDIT_WP.md`. Briefing desplegado por la sesión local. Scripts corregidos por la sesión local (token sin SIGPIPE; filtro de salts en inventario).

## Sexta entrega (cierre de Fase 0, Fase 1 al 90%)

- Briefing de la Dra. confirmado; respuestas en `DISCOVERY.md` §0b.
- Producción: core 0.2.1 + tema activos, Rank Math configurado por ops, datos del consultorio cargados (`/wp-json/dra-irina/v1/practice`).
- Incidencia Q-005 (fatal por método privado) corregida; lección registrada.

## Pedido a Codex

- Auditar `PLAN.md`: ¿la ponderación es razonable? ¿Falta alguna tarea exigida por el MASTER_PROMPT?
- Auditar `DISCOVERY.md`: comprobar que cada dato "CONFIRMADO POR DOCUMENTO" cite su fuente y que nada se haya elevado a confirmado sin respaldo.
- Auditar las tres investigaciones de Fase 2: afirmaciones sin fuente presentadas como hechos, volúmenes inventados, URLs propuestas que canibalicen, recomendaciones que contradigan el MASTER_PROMPT (p. ej. páginas doorway, keyword stuffing, testimonios fabricados).
- Auditar `ARCHITECTURE.md` contra MASTER_PROMPT §95–§104: separación theme/core, Elementor controlado, fuente única de verdad, workflow médico por capacidades, emisor único de schema; validar la decisión D-014 (campos nativos sin ACF) frente a coste de mantenimiento.
- Auditar `SITEMAP.md` y `STRATEGY.md`: canibalización, páginas doorway, claims no verificables.
- Revisar el scaffold: seguridad (nonces, capacidades, escaping, sanitización en `MetaRegistry::sanitize` y `PracticeSettings::sanitize`), el bloqueo de publicación en `Workflow\MedicalReview::block_unapproved_publish` (¿puede eludirse vía REST/Gutenberg? Propuesta: añadir filtro `rest_pre_insert_{post_type}`), las reescrituras `/sueno/{slug}` en `MetaRegistry::area_rewrites`, y la eliminación de nodos de Rank Math en `Schema\Graph::strip_rank_math_duplicates`.
- Auditar `src/Ops/Endpoints.php` (superficie de ataque: opciones escribibles por admin vía app password; ¿añadir lista blanca en vez de lista negra?) y `src/Settings/PracticeSettings.php` (POST REST).
- Validar el porcentaje reportado (17%) contra `PLAN.md` (§162).
- Clasificar hallazgos (BLOCKER/CRITICAL/HIGH/MEDIUM/LOW/SUGGESTION) en `QA.md`.

## Criterios de aceptación de esta entrega

- Documentación legible por un tercero sin contexto de chat.
- Ningún secreto en el repositorio.
- Ningún dato médico/profesional presentado como confirmado sin fuente.

## Cuarta entrega (Fase 4 — dirección visual) · 2026-10-08

Claude → Codex. Contexto actualizado: WordPress ya reiniciado y limpio en `https://drairinagonzalez.com` (canónico sin www); plugin 0.2.1 y tema activos; Sensia intacta en `/op`; CP1 cerrado con briefing confirmado (`DISCOVERY.md` §0b).

Entregado:

- `DESIGN_SYSTEM.md` v0.1 (principios, color con contraste, tipografía Iskra, espaciado, componentes, sub-marca Sueño, motion).
- `tools/preview/` (maquetas estáticas: `index.html`, `dra-irina.html`, `apnea.html`, `articulo.html`; parciales `_head/_foot/_*_body`; `preview.css` sobre `tokens.css` del tema). Publicadas en `/preview/` con `X-Robots-Tag: noindex`.
- `tools/deploy/deploy-preview.sh`.

Pedido a Codex:

1. Contraste AA de cada combinación de color usada en `preview.css` (en especial teal sobre blanco y texto sobre `night`).
2. Semántica y accesibilidad de las maquetas: un solo `h1`, orden de encabezados, `details/summary`, foco visible, tamaños de toque, barra móvil con `safe-area`.
3. Que ningún texto clínico de las maquetas se presente como aprobado: todo lo médico lleva `.draft` o referencia a la Dra.
4. Coherencia entre `DESIGN_SYSTEM.md`, `tokens.css` y `theme.json`.

## Quinta entrega (Fase 6 build + contenido base) · 2026-10-09

Claude → Codex. En producción (todo en borrador/noindex salvo la portada provisional): Home v2 `/inicio-v2/`, página de la Dra. (169), pilares ORL (170) y Sueño (171), Primera consulta (172), FAQ (173), Contacto (174), legales (175–177), 14 padecimientos y 8 tratamientos en borrador con `estado_medico=medical_review_required`.

Pedido a Codex:

1. Auditar `wp-content/themes/irina-gonzalez/` (header.php, footer.php, page.php, templates/parts/**, inc/*.php) y `wp-content/plugins/dra-irina-core/src/Rendering/Elementor/*`: escapado, saneado de SVG (`irina_brand_svg`), accesibilidad (un h1, foco, aria), rendimiento (CSS ≤ 90 KB total con Elementor), y que ningún texto clínico se renderice como aprobado sin `estado_medico` publicable.
2. Revisar `tools/content/*.py` y `tools/deploy/setup-*.sh`: idempotencia, uso de `wp_slash` con `_elementor_data`, borrado de `_elementor_element_cache`, y que ningún script pueda publicar contenido clínico.
3. `SEO.md` v0.2 y `tools/content/pages/seo.json`: longitud de titles/descriptions, duplicados, coherencia con `KEYWORDS.md` §4.
4. Contenido de `medical-drafts.json` y páginas base: señalar cualquier afirmación clínica que requiera fuente o que suene a promesa.
