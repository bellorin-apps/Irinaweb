# CHANGELOG

## 2026-10-07

- Inicio del proyecto. Rama `ccr-6254502d-xly8ww`.
- Creada base documental: MASTER_PROMPT v1.0, README, PLAN, STATUS, NEXT, BATON, DECISIONS, QA, SEO, DISCOVERY, docs/AUDIT_WP, docs/PHOTO_SHOTLIST.
- Fase 0 al 70%: información existente recolectada con fuentes; cuestionario y checklist de documentos listos.
- Lote 1 de respuestas del propietario incorporado a DISCOVERY.md (nombre, género, contacto, sede, hosting, licencias, agenda, GBP, tipografía Iskra, opiniones, fotos, accesos).
- Mini app de briefing para la Dra. publicada como artefacto con guardado persistente (D-009).
- Fase 2 parcial: `docs/REFERENCE_RESEARCH.md`, `docs/COMPETITION_MONTERREY.md`, `KEYWORDS.md` (en REVIEW). Avance 8%.
- Fase 2/3: `STRATEGY.md`, `SITEMAP.md`, `ARCHITECTURE.md`, `PLUGINS.md` v0.1 (REVIEW). Decisiones D-013..D-017, OD-006. Avance 11%.
- Fase 5 scaffold: plugin `dra-irina-core` (CPT, taxonomías, meta con schema REST, ajustes del consultorio, workflow médico, schema JSON-LD, shortcodes, tracking) y child theme `irina-gonzalez` (tokens, base, componentes, sueño, JS, templates placeholder). PHPCS en verde.
- Bloqueo registrado: acceso de red del entorno a drairinagonzalez.com y doctoralia.com.mx.

## 2026-10-08

- Checkpoint 1 cerrado: briefing de la Dra. confirmado; horario, hospitales, redes y Doctoralia incorporados; datos cargados en `PracticeSettings` y Physician JSON-LD en vivo.
- Plugin `dra-irina-core` 0.2.1 (ops endpoints corregidos, Q-005) y tema activos en producción; Rank Math configurado; PHP 8.4; Elementor Pro instalado.
- Fase 4: sistema visual (tokens, componentes, sub-marca Sueño), `DESIGN_SYSTEM.md` v0.1 y maquetas Home / Dra. Irina / apnea / artículo en `tools/preview/`; `tools/deploy/deploy-preview.sh` para publicarlas en `/preview/` (noindex). Avance 20%.
- `env.example.sh`: `SITE_URL` canónico sin www.
- CP2 primera ronda: el propietario cambia la dirección (D-026). Maquetas v2 (cálidas, titulares gigantes, cabeceras a sangre, reveal/parallax) en `tools/preview/`; v1 archivada en `tools/preview/v1/`. `DESIGN_SYSTEM.md` v0.2.
- Checkpoint 2 cerrado: v2 con titulares en bold aprobada por el propietario; `/preview/` con assets versionados y sin caché. Inicia Fase 6.
- Fase 6 build: tokens cálidos v2 en el tema, header/footer PHP (D-027), plantillas de condición/tratamiento/recurso/archivos, partes del Home, plugin 0.3.0 con 8 widgets Elementor `di-*`, `tools/content/home-elementor.json` y `tools/deploy/setup-site.sh` (D-028).
- Página de la Dra.: 4 widgets más (cabecera genérica, narrativo, credenciales y trayectoria desde el CPT `credencial`), plugin 0.3.1, `tools/content/{dra-elementor,credenciales}.json`, `tools/deploy/setup-dra.sh`. QA visual del Home en producción OK; D-029.
- Logo real (SVG de José) en header/footer vía `irina_brand_svg()`; foto del hero a 2400×1600 q96 con srcset; mapa oficial embebido; favicon del isotipo como site icon (D-031); tipografía v2.1 más sutil; CDN de Hostinger recomprime imágenes (D-030, pendiente ajuste en hPanel).
- Noche 8→9 oct (mandato del propietario): cabecera con logotipo completo (opción isotipo+texto), hero móvil v3, widgets FAQ y Listado, page.php, páginas base en Elementor (ORL, Sueño, Primera consulta, Contacto, FAQ), legales provisionales, SEO titles/descriptions, 14 padecimientos y 8 tratamientos en borrador, `SEO.md` v0.2, Anexo A reflejado (Fase 13, OD-008), Q-007 SSL dominio aparcado.
- Hero móvil cerrado (v7): recorte superior de la foto, entradilla corta `lead_mobile`, CTAs apilados; verificado por la sesión local con capturas y medidas (rostro libre, texto abajo).
- 2026-10-08 (día): CP3 cerrado y **Home v2 publicada como portada** (D-034); horario oculto «Previa cita» (D-032); hospitales como fuente única con schema (D-033); credenciales en schema; OD-009 rinoplastia en borrador prudente; credencial HU y trabajos 2016; /links/ sin cabecera a sangre; Elementor Pro activado.
- `/links/` rehecha con `di-enlaces` e iconos de red (D-036); sprite versionado por mtime.
- Cuestionario de fichas médicas para la Dra. en `/briefing/fichas/` (D-038); avisos COFEPRIS en pie y aviso médico (D-037).
- 2026-10-09: la Dra. confirmó briefing y fichas. **23 fichas redactadas completas** (qué es, síntomas, causas, cuándo consultar, diagnóstico con sus estudios en consultorio, enfoque, FAQ, fuentes propuestas; tratamientos con candidatos, estudio previo, cómo se realiza, recuperación, riesgos y alternativas) en `build-medical.py` (D-039); DAM como referencia sin nombrar a terceros; rinoplastia «ofrece»; cirugía de oído como referencia. `setup-medical.sh` escribe todos los campos y no toca fichas publicadas. Cargo HU con el texto de la Dra.; FAQ de pagos y consulta en línea; 3 artículos pedidos por la Dra. en esqueleto. Tanda ejecutada por la sesión local: 23 fichas con todos los metas en borrador (ids 244–264, 205, 293), recursos 286/319/320, credencial 287 actualizada, 0 fichas clínicas publicadas.
