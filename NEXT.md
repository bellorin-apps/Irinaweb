# NEXT — Próximo trabajo

Actualizado:2026-10-16 · Codex ·221/228(96,93%),83/86DONE (D-094 aplicado).

1. ✅ Claude revisó Q-066/067 (dc1c104) sin objeciones (QA). Antes: pull y revisar Q-066/067 en tools/deploy. Usar herramientas actualizadas en la próxima operación autorizada; **no ejecutar deploy ni ensayo real por esta auditoría**, no pedir claves ni recrear base de prueba. Código/copiaDB atestados y guardas locales19casos:12.1DONE con límite de restauración en vivo no ensayada por José.
2. 12.2DONE como validación puntual; seguimiento12.3día7≈22/10 y día30≈14/11 con fuentes reales. Logs de visitantes solo hPanel/monitor404apagado: no se afirma haberlos auditado ni se entra a cuentas.
3. 9.5OWNER: Q-062literal y servicios cotejados, falta horario real de la Dra./Doctoralia como mejora continuaD-086. No solicitar de nuevo transcripción383ni entrar a perfiles por Codex. Q-041WONTFIXD-065: mapa JSON/marcadorclásico, sin pedirMapID.
4. 11.2DONE: José completó GalaxyS24+ incluida barra horizontal y menú wp-admin (94a2479 y QA D-087/Q-065). Producción de esa prueba: de864bc; última entrega atestada a89f14a. No repetir pruebas de cuentas/formulario; conservar límites de Firefox y atestaciones de dispositivos.
5. Fase5 cerrada:5.1/5.2/5.3/5.5DONE; Q-042VERIFIED. Q-068 comando npm Windows corregido/verificado, sin despliegue necesario. Q-040allowlistOps sigueOPEN como unidad separada. Legal8.6/GBP como mejora continua, CHECKPOINT5D-086 aprobado. Mantener practice.py y Consultorio cotejados al cambiar contacto; ESLint deprecado, actualización futura por lote separado.

Informe actual docs/REVISION_FASE5_2026-10-16.md. Sin web/builds nuevos, sin SMTP/secretos/cuentas. CuatroHTML y h.txt del usuario intactos. Preguntas por Claude.

## Añadido por Claude cloud (2026-10-16, decisiones D-089…D-093 del GAP)

6. Doctoralia16c: **GO con cambios condicionado; no activar todavía**. Q-069/070 corregidos localmente: carga por botón y caja estable. José/sesión local cotejan HTML oficial del perfil y preparan verificación real autorizada (contenido, cookies, accesibilidad, rendimiento). Sin deploy/setup/activación automática. Informe docs/REVISION_DOCTORALIA_2026-10-16c.md.
7. José/Dra.: B1, errata de la ficha de apnea ya corregida en la fuente. Aplicarla en producción requiere que la Dra. la apruebe otra vez (D-090).
8. ✅ OD-008 resuelta (D-095): módulo nativo; consulta en español; páginas base, legales y 5 fichas. **Fecha: versión inglesa lista para la revisión de la Dra. hacia el 30-oct-2026** (Claude cloud construye 13.3/13.4/13.5 en borrador; nada se publica sin su aprobación).
9. José: monitoreo con UptimeRobot (D-093); al tenerlo, registrarlo en SECURITY y MAINTENANCE.
10. **Publicar /en/ (13.7, D-098):** cuando la Dra. apruebe las 8 páginas (guía) y las 5 fichas (app) (los legales ya los aprobó José, D-099), la sesión local despliega código y tema, ejecuta `bash tools/deploy/publish-en.sh` (revisión) y luego `--legal-ok --yes`. Si algo falta, el script no publica nada.

