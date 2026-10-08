# NEXT — Siguiente trabajo

Última actualización: 2026-10-08

## Prioridad 1 — Desbloqueadores (propietario)

- [x] Checkpoint 2 cerrado (2026-10-08): dirección v2 aprobada; ajustes menores se harán sobre el build real.
- [x] Adobe Fonts: Iskra carga en /preview/ (kit autorizado).

- [x] Briefing confirmado por la Dra. (2026-10-08).
- [x] Horario, hospitales, redes y Doctoralia confirmados (2026-10-08); sin laboratorio externo.
- [ ] Pendiente de la Dra.: aviso de privacidad actual y número/constancia del Aviso de Publicidad (no urgente).
- [ ] Conectar la licencia de Elementor Pro (wp-admin → Elementor → Licencia).

- [x] Reset ejecutado según `docs/RUNBOOK_RESET.md` (2026-10-07/08).
- [x] Adobe Fonts kit `nlo5pss` configurado en el tema.

- [x] Lotes de discovery respondidos; red del entorno ampliada; secreto de red REST configurado; despliegue de código por `tools/deploy/deploy-code.sh` desde la sesión local.
- [x] Briefing desplegado en `/briefing/` (sesión local, 2026-10-07). Enviar la URL con token a la Dra.
- [x] Links se conserva (D-023); canónico sin www (D-022); Site Kit fuera (D-024).
- [x] PHP 8.4 activo; /op verificado (D-025).
- [x] OD-004, OD-005, OD-006 y dominio canónico (D-020) decididos.

## Prioridad 2 — Claude (sin dependencias del propietario)

- [x] Plantillas 39/79 excluidas de la Home v2 (D-029); /inicio-v2/ con header/footer del tema y QA visual OK.
- [ ] QA de la página de la Dra. (169) y la condición de muestra (205) tras setup-dra.sh; capturas 5 tamaños (6.7, 6.8).
- [ ] Cargar `maps_url` y el iframe del mapa cuando el propietario pase el enlace de Google Maps.
- [ ] Checkpoint 3: presentar Home, Dra. y apnea al propietario; al aprobarse, publicar páginas, Home como portada y retirar 39/79.

- [x] `docs/REFERENCE_RESEARCH.md` (2.1) — en REVIEW.
- [x] `docs/COMPETITION_MONTERREY.md` (2.2) — en REVIEW.
- [x] `KEYWORDS.md` (2.3) — en REVIEW.
- [x] `STRATEGY.md` (2.5), `SITEMAP.md` (3.1), `ARCHITECTURE.md` (3.3–3.5), `PLUGINS.md` (3.6) — en REVIEW.
- [ ] `SEO.md` (3.2): consolidar keyword map, titles y reglas técnicas.
- [x] Scaffold de `wp-content/plugins/dra-irina-core` y `wp-content/themes/irina-gonzalez` con PHPCS (5.1, 5.2, 5.5 en DOING).
- [ ] Meta boxes con repetidores (FAQ, fuentes, síntomas) en `Fields/MetaBoxes.php`.
- [ ] Probar plugin y tema en un WordPress local del contenedor (wp-env o descarga directa si la red lo permite) antes de staging.
- [x] Sistema visual (4.1–4.4, 4.6) en REVIEW; maquetas (4.5) en despliegue.
- [ ] Exportar favicon y logo SVG desde `Logo-Favicon.ai` (tras CP2).
- [ ] Tras CP2: Fase 6 en WordPress (header, footer, Home con datos del core, templates).

## Prioridad 3 — Fase 1 restante

- [ ] 1.3 auditoría pública (robots, sitemap, headers, CWV baseline) y 1.4 Search Console (sitemap sin www).
- [ ] 1.7 staging protegido (evaluar si `/preview/` + Elementor en borrador basta hasta Fase 6).
- [ ] Codex: auditoría de seguridad del estado actual.

## Codex — próxima auditoría solicitada

- Auditar `DESIGN_SYSTEM.md` y `tools/preview/` (contraste, semántica, jerarquía, coherencia con tokens del tema).

- Revisar `PLAN.md` (ponderación y Definition of Done) y `DISCOVERY.md` (que no se haya inventado ningún dato: todo debe tener fuente).
- Auditar `docs/REFERENCE_RESEARCH.md`, `docs/COMPETITION_MONTERREY.md` y `KEYWORDS.md`: verificar que ninguna afirmación sin fuente se presente como hecho, que no haya volúmenes inventados y que las propuestas de URL no canibalicen.
