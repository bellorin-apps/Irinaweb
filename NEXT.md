# NEXT — Próximo trabajo

**Auditoría Codex de la entrega 62d920e:** Q-077 **VERIFIED_LOCAL ratificado**; Q-078 **ACCEPTED por D-100**, sin pedir otra decisión. Q-079 **FIX / OPEN**: 9/9 pasan, pero sync.sh permite enlaces en directorios intermedios; reproducción desechable acreditada. No usar sync sobre el laboratorio real hasta corregirlo. 13.5 REVIEW por verificación publicada,13.7 DOING,13.6 OWNER. Sin desplegar/publicar. Informe `docs/REVISION_ENTREGA_Q077_Q079_2026-10-19.md`.

## Historial previo (prevalece la auditoría anterior)

**José autorizó (D-101):** cuando Codex dé el GO a c151258, 1f5e18e y 62d920e, la sesión local despliega solo el código (respaldo antes y verificación en vivo después), con el inglés en borrador. Sin GO de Codex no se despliega; sin la aprobación de la Dra. y una orden expresa no se publica.

**Q-077 VERIFIED_LOCAL (2026-10-19):** el 1f5e18e de la nube, integrado en la copia aislada, deja `seo_check.py` endurecido en salida 0: inLanguage en las 30 páginas, WebSite/WebPage en /en/, ProfilePage en About EN y sitemap 30. Codex audita c151258 y 1f5e18e. 13.5 sigue en REVIEW hasta esa auditoría y la verificación publicada.

**Respuesta de Claude local (2026-10-19, ver SHA en BATON):**
- Q-079 FIXED_LOCAL: lista permitida, raíz por git, destino validado y 9/9 pruebas.
- Verificador SEO endurecido: inLanguage obligatorio, paridad de nodos con ES, salida 1 y 10/10 mutaciones.
- Q-078 decidido por José: IndexNow se acepta tal como está (D-100).
- Q-077 (schema) sigue OPEN y lo corrige la nube. Codex audita al recibir el SHA.

**Auditoría de pendientes (Codex, 2026-10-19):** pull y ramas remotas reconsultados en `bc2deb0`; no se recibieron correcciones nuevas. Falta identificar SHA/repositorio de la entrega de Claude. Q-077 y Q-079: **FIX / OPEN**; Q-078: **REVIEW / OPEN**, pendiente decisión de José. 13.5 REVIEW y 13.7 DOING sin cambio. Informe `docs/REVISION_Q077_Q079_2026-10-19.md`. Sin desplegar ni publicar.

Actualizado: 2026-10-19 · Codex (b26efa4 / evidencia 43cc1df). Español221/228(96,93%),83/86DONE; inglés9/19(47,37%) aparte.

0. **Q-076 VERIFIED_LOCAL ratificado; no repetir la corrección.** Informe docs/REVISION_Q076_2026-10-19.md. Corregir/verificar Q-077: schema de /en/ y cobertura inLanguage (siete EN ausentes); el harness debe fallar si faltan nodos exigidos. José decide Q-078 (IndexNow); no asumir aceptación. Q-079: preparar laboratorio excluyendo privados antes de copiar y resolver raíz/rutas del helper. Mantener 13.5 REVIEW, 13.7 DOING y 13.6 OWNER; lanzamiento solo con aprobación médica y orden expresa. No desplegar ni publicar por este relevo.

1. ✅ Claude revisó Q-066/067 (dc1c104) sin objeciones (QA). Antes: pull y revisar Q-066/067 en tools/deploy. Usar herramientas actualizadas en la próxima operación autorizada; **no ejecutar deploy ni ensayo real por esta auditoría**, no pedir claves ni recrear base de prueba. Código/copiaDB atestados y guardas locales19casos:12.1DONE con límite de restauración en vivo no ensayada por José.
2. 12.2DONE como validación puntual; seguimiento12.3día7≈22/10 y día30≈14/11 con fuentes reales. Logs de visitantes solo hPanel/monitor404apagado: no se afirma haberlos auditado ni se entra a cuentas.
3. 9.5OWNER: Q-062literal y servicios cotejados, falta horario real de la Dra./Doctoralia como mejora continuaD-086. No solicitar de nuevo transcripción383ni entrar a perfiles por Codex. Q-041WONTFIXD-065: mapa JSON/marcadorclásico, sin pedirMapID.
4. 11.2DONE: José completó GalaxyS24+ incluida barra horizontal y menú wp-admin (94a2479 y QA D-087/Q-065). Producción de esa prueba: de864bc; última entrega atestada a89f14a. No repetir pruebas de cuentas/formulario; conservar límites de Firefox y atestaciones de dispositivos.
5. Fase5 cerrada:5.1/5.2/5.3/5.5DONE; Q-042VERIFIED. Q-068 comando npm Windows corregido/verificado, sin despliegue necesario. Q-040allowlistOps sigueOPEN como unidad separada. Legal8.6/GBP como mejora continua, CHECKPOINT5D-086 aprobado. Mantener practice.py y Consultorio cotejados al cambiar contacto; ESLint deprecado, actualización futura por lote separado.

Informe actual docs/REVISION_INGLES_2026-10-19.md. Cambios locales de idioma/protecciones y títulosEN en borrador, sin deploy/publicación ni SMTP/secretos/cuentas. CuatroHTML y h.txt del usuario intactos. Preguntas por Claude.

## Añadido por Claude cloud (2026-10-16, decisiones D-089…D-093 del GAP)

6. Doctoralia16c: **GO con cambios condicionado; no activar todavía**. Q-069/070 corregidos localmente: carga por botón y caja estable. José/sesión local cotejan HTML oficial del perfil y preparan verificación real autorizada (contenido, cookies, accesibilidad, rendimiento). Sin deploy/setup/activación automática. Informe docs/REVISION_DOCTORALIA_2026-10-16c.md.
7. José/Dra.: B1, errata de la ficha de apnea ya corregida en la fuente. Aplicarla en producción requiere que la Dra. la apruebe otra vez (D-090).
8. ✅ OD-008 resuelta (D-095): módulo nativo; consulta en español; páginas base, legales y 5 fichas. **Fecha: versión inglesa lista para la revisión de la Dra. hacia el 30-oct-2026** (Claude cloud construye 13.3/13.4/13.5 en borrador; nada se publica sin su aprobación).
9. José: monitoreo con UptimeRobot (D-093); al tenerlo, registrarlo en SECURITY y MAINTENANCE.
10. **Inglés:13.2/13.3DONE;13.5REVIEW por Q-077.** Integración local OG/canónicas/hreflang/sitemap y causa301 acreditadas; falta cierre de schema y verificación del entorno publicado tras un lanzamiento autorizado.13.6OWNER: faltan8páginas/5fichas aprobadas por la Dra.; legales yaaprobadosD-099. Q-072/073guardas corregidas con28casos; no garantía atómica; recuperación integrada local ya revisada (Q-076), lanzamiento aún pendiente en13.7. Sin deploy/setup/publicación por esta auditoría. D-098requiere aprobación y autorización antes de lanzar.

