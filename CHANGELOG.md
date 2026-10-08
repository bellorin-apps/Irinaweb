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
