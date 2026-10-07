# NEXT — Siguiente trabajo

Última actualización: 2026-10-07

## Prioridad 1 — Desbloqueadores (propietario)

- [ ] Responder `DISCOVERY.md` → Lote 1 (preguntas marcadas ⚡).
- [ ] Red del entorno cloud: subir el nivel de acceso (la lista de dominios bloqueados incluye wordpress.org y todos los referentes); como mínimo permitir `drairinagonzalez.com`, `www.drairinagonzalez.com`, `wordpress.org`, `api.wordpress.org`, `downloads.wordpress.org`, `fonts.googleapis.com`, `fonts.gstatic.com`, `doctoralia.com.mx`.
- [ ] Crear en wp-admin (Usuarios → tu perfil → Contraseñas de aplicación) una contraseña de aplicación "claude-ops" y guardarla como secretos del entorno: `IRINA_WP_URL=https://www.drairinagonzalez.com`, `IRINA_WP_USER`, `IRINA_WP_APP_PASSWORD`.
- [ ] Hostinger hPanel → Git: desplegar rama `deploy/core` en `public_html/wp-content/plugins/dra-irina-core` y `deploy/theme` en `public_html/wp-content/themes/irina-gonzalez` (Claude genera las ramas).
- [ ] Briefing: Claude lo despliega en `/briefing/` en cuanto exista el canal de operación; entonces entrega la URL con token para enviársela a la Dra.
- [ ] Buscar el recibo/licencia de la fuente Iskra (MyFonts o TypeTogether).
- [ ] Decidir OD-004 (posicionamiento respecto al Dr. Moreno) y OD-005 (nombre exacto de CAB Medical).

## Prioridad 2 — Claude (sin dependencias del propietario)

- [x] `docs/REFERENCE_RESEARCH.md` (2.1) — en REVIEW.
- [x] `docs/COMPETITION_MONTERREY.md` (2.2) — en REVIEW.
- [x] `KEYWORDS.md` (2.3) — en REVIEW.
- [x] `STRATEGY.md` (2.5), `SITEMAP.md` (3.1), `ARCHITECTURE.md` (3.3–3.5), `PLUGINS.md` (3.6) — en REVIEW.
- [ ] `SEO.md` (3.2): consolidar keyword map, titles y reglas técnicas.
- [x] Scaffold de `wp-content/plugins/dra-irina-core` y `wp-content/themes/irina-gonzalez` con PHPCS (5.1, 5.2, 5.5 en DOING).
- [ ] Meta boxes con repetidores (FAQ, fuentes, síntomas) en `Fields/MetaBoxes.php`.
- [ ] Probar plugin y tema en un WordPress local del contenedor (wp-env o descarga directa si la red lo permite) antes de staging.
- [ ] Propuesta de sistema digital derivado de la identidad existente (4.1) — paleta, tipografía candidata, favicon desde `Logo-Favicon.ai`.
- [ ] Preparar scaffold de `irina-gonzalez` (child theme) y `dra-irina-core` (plugin) en el repo, sin desplegar.

## Prioridad 3 — En cuanto haya acceso al WP

- [ ] Fase 1 completa: inventario, auditoría pública, Search Console, backup verificado, staging.
- [ ] Clasificación KEEP / REPLACE / REMOVE / INVESTIGATE.
- [ ] Codex: auditoría de seguridad del estado actual.

## Codex — próxima auditoría solicitada

- Revisar `PLAN.md` (ponderación y Definition of Done) y `DISCOVERY.md` (que no se haya inventado ningún dato: todo debe tener fuente).
- Auditar `docs/REFERENCE_RESEARCH.md`, `docs/COMPETITION_MONTERREY.md` y `KEYWORDS.md`: verificar que ninguna afirmación sin fuente se presente como hecho, que no haya volúmenes inventados y que las propuestas de URL no canibalicen.
