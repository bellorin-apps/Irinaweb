# NEXT — Siguiente trabajo

Última actualización: 2026-10-09 · Convención: las guías paso a paso para José se dan en el chat cloud («Sitio web»); la sesión local deja solo resúmenes.

## Prioridad 1 — Desbloqueadores (propietario)

- [x] La Dra. respondió briefing y fichas (2026-10-09). **23 fichas redactadas con todos los campos** (`tools/content/medical-drafts.json`, D-039); cargadas en WP por la sesión local (2026-10-09): condiciones 244–256 y 205, tratamientos 257–264 y 293, recursos 286/319/320, todas en borrador; credencial 287 con el cargo de la Dra.
- [ ] **La Dra. revisa y aprueba cada ficha en la mini app** `/briefing/revision/?t=…` (D-040; desplegar con `deploy-briefing.sh`, requiere usuario `revisor_medico`); nada se publica antes. Tras cada aprobación o nota: la sesión local lee `briefing-privado/revision-latest.json` y Claude publica (aprobadas) o corrige (notas). Prioridad sugerida: apnea, ronquido, estudio del sueño, CPAP, cirugía de ronquido, sinusitis, rinitis, tabique, septoplastia.
- [ ] Fichas fuera del sitemap inicial que la Dra. marcó como OFRECE (perforación timpánica, voz, disfagia, cuello, alergias, somnolencia, insomnio, terapia posicional): Fase 8.1.
- [ ] Artículos pedidos por la Dra. (oídos, postoperatorio de amígdalas, nariz): esqueletos en borrador; ella dicta el contenido (1–2 al mes).
- [x] Aviso de Funcionamiento 2026 publicado (2619015036A00445).

- [x] Checkpoint 3 cerrado (D-034): portada Home v2, logotipo completo, foto autorizada; conmutación con `tools/deploy/go-live-home.sh`.

- [x] OD-009 resuelto: mencionar rinoplastia desde ahora (borrador prudente). Cargo HU recibido. Segunda publicación: pendiente de la Dra.

- [ ] GBP: horario desactualizado → lo actualiza la Dra. cuando pueda (José, 2026-10-09): «previa cita» con los días/horarios que confirme; mantener WhatsApp y dirección (CAB Medical, consultorio 6, piso 2).
- [ ] Capturas de 169/170/171/205 con sesión: José inicia sesión en la pestaña wp-login de la sesión local y avisa «ya».
- [x] **SSL de otorrino-monterrey.com** (Q-007): activo (Lifetime SSL); https apex y www → 301 al sitio (verificado 2026-10-09 por ambas sesiones; Q-007 FIXED). Rutas profundas del dominio viejo → 301 a la misma ruta: aplicado y verificado (Q-012 FIXED). Search Console del dominio viejo (2026-10-09): propiedad https://otorrino-monterrey.com/ con cambio de dirección a drairinagonzalez.com ya registrado, 1 página indexada, 8 no indexadas, 0 clics en 90 días; pendiente ver la lista de Páginas y el sitemap de la propiedad nueva. Original:: hPanel → Seguridad → SSL, instalar certificado para el dominio aparcado y su www; después verificar que https://otorrino-monterrey.com/ → 301 → https://drairinagonzalez.com/.
- [x] Hero: CDN optimiza, WordPress no (D-043). hPanel → CDN → optimización de imágenes (2026-10-09): escritorio 2400 px / 100 %, móvil 1200 px / 90 %; verificado: hero servido 2400×1600 WebP (1.18 MB) y 1200×800 (54 KB). José sigue viendo pérdida → D-042: tema sin grano sobre foto + original completo en escritorio; D-043: la CDN optimiza (interruptores ACTIVOS, 2400/100 % y 1200/90 %) y WordPress no toca nada: CERRADO 2026-10-09: PNG editado (adjunto 322) como origen, CDN vaciada por José; servido WebP 2400×1600 (1.40 MB) escritorio y 1200×800 (67 KB) móvil, detalle del rostro íntegro al 100 % de zoom. Pendiente: José vacía la caché del CDN.

- [x] Checkpoint 2 cerrado (2026-10-08): dirección v2 aprobada; ajustes menores se harán sobre el build real.
- [x] Adobe Fonts: Iskra carga en /preview/ (kit autorizado).

- [x] Briefing confirmado por la Dra. (2026-10-08).
- [x] Horario, hospitales, redes y Doctoralia confirmados (2026-10-08); sin laboratorio externo.
- [ ] Pendiente de la Dra.: aviso de privacidad actual y número/constancia del Aviso de Publicidad (no urgente).
- [x] Licencia de Elementor Pro activada (2026-10-08).

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
- [x] `maps_url`, `gbp_url` y coordenadas cargados por REST (2026-10-08); iframe del mapa en home-elementor.json (pendiente recargar 168).
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

## AFTER — Versión en inglés (Anexo A, Fase 13)

- Decisión del propietario (D-034): la versión en inglés va **después del lanzamiento en español**. Propuesta de fecha: **inicio cuatro semanas después del launch español** (si el launch es la semana del 3 de noviembre de 2026, la fase inglesa arranca la semana del 1 de diciembre de 2026). El propietario confirma. Estimación: si CP3 cierra la semana del 13 de octubre y CP4 base la del 27 de octubre de 2026, la fase inglesa arrancaría la semana del 10 de noviembre de 2026. El propietario confirma la fecha.
- Antes de arrancar: OD-008 (herramienta), `docs/GLOSSARY_EN.md`, keyword map en inglés.
