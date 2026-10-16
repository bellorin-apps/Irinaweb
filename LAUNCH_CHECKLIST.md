# LAUNCH_CHECKLIST — drairinagonzalez.com

Actualizado: 2026-10-16 · Codex · revisión del encargo de cierre. El sitio está en producción desde el go-live de la portada (D-034) y se ha ido publicando por tramos con aprobación médica (D-078/D-082). Esta lista confirma que el sitio corto (D-076) está listo para considerarse lanzado (PLAN 11.5) y deja a la vista las mejoras continuas aceptadas por José (CHECKPOINT5 aprobado D-086,11.6DONE).

Leyenda: ✅ hecho y verificado · ⏳ pendiente · 👤 depende de José

## Contenido y aprobación

- ✅ Páginas públicas: Inicio, Conóceme, Otorrino, Sueño, Primera consulta, Contacto, Preguntas frecuentes, 3 legales, 5 fichas de sueño (15 URL).
- ✅ Contenido clínico aprobado por la Dra. en su app (ORL, Sueño, 26 fichas; se publican 5 por decisión de José, D-082). Las 21 restantes en borrador, sin enlaces (404).
- ✅ Textos de marca y títulos dictados por José respetados (D-049).
- ✅ Aviso de privacidad, aviso médico y términos publicados; Cloudflare mencionado en lenguaje sencillo.
- 👤 Revisión legal/regulatoria final (COFEPRIS, privacidad) — PLAN 8.6.

## Técnica

- ✅ 15/15 URL 200, 404 propia, sin enlaces rotos, sin desbordes en 5 anchos, sin errores JS (docs/audit/2026-10-15/qa-cierre.md).
- ✅ HTTPS, HSTS y cabeceras de seguridad; versiones ocultas; enumeración de usuarios cerrada; XML-RPC desactivado (SECURITY.md).
- ✅ 2FA en WordPress, Hostinger y Google; sin contraseñas de aplicación activas.
- ✅ Formulario con datos mínimos, antispam y captcha; SMTP operativo desde el 10-oct.
- ✅ Prueba real del formulario con «PRUEBA» hecha por José (2026-10-15).
- ✅ Respaldo de código antes de deploy-code y copias del hosting según reporte. ✅ Ensayos de código y DB en copia aparte según QA16/10 (12.1DONE); restauración en vivo no ensayada por decisión de José, no se presenta como certificada.

## SEO

- ✅ Títulos y descripciones dentro de límites; canónicas propias e index en las 15 URLs indexables. Links/Gracias públicos noindex; archivos404 y borradores fuera del sitemap.
- ✅ Sitemaps de Rank Math solo con lo publicado (sin archivos 404 ni recursos); reenviado en Search Console.
- ✅ Tipos/duplicados de schema contrastados en las páginas públicas. Validator.schema.org 0/0 aportado por la sesión local para Sueño y Dra.; no se afirma validación externa individual de todas las URLs (Q-058, auditoría14b).
- ✅ 301 del dominio aparcado y de las rutas de tipo a /sueno/{slug}/.
- ✅ Evidencia aportada de Search Console del14/10: portada, Conóceme, Primera consulta y Contacto indexadas; motivos históricos revisados. No afirma cobertura de hoy sin una nueva consulta del propietario.
- ✅ Edición de Perfil de Google atestada (no cierre de9.5): Perfil de Google editado el 2026-10-16 (sesión local con OK de José; QA «Perfil de Negocio»): web con UTM, descripción, Facebook limpio, categoría adicional Clínica del sueño, reserva por WhatsApp y 7 servicios. Nombre, dirección y teléfonos sin cambios.
- 👤 Horario de GBP pendiente de que la Dra. confirme el real (hoy en Google: lun cerrado, mar–vie 10:00–12:30, sáb 9:00–15:00); el sitio sigue con «Previa cita» (D-032). Doctoralia lo alinea la Dra. (PLAN 9.5).

## Rendimiento y accesibilidad

- ✅ Lighthouse «antes/después» (PERFORMANCE.md): CLS de portada 0,236 → 0,001; Dra./Primera consulta/Contacto escritorio a menos de la mitad con q95. Home 5204→4070KiB en las tandas registradas; no es medición CWV de campo.
- ✅ Accesibilidad: axe WCAG 2.2 AA sin violaciones reales; Lighthouse a11y 100 en las páginas medidas; menú con teclado.
- ✅ Chromium medido; Edge/Firefox por reporte local y Safari iPhone real por atestación de José15/10. ⏳ Android real: falta pasada explícita de cierre, 11.2 permanece REVIEW. ⏳ Lector de pantalla manual (VoiceOver/TalkBack), fuera del alcance automático y sin certificar WCAG completo.

## Medición

- ✅ GA4 con carga diferida, exclusión de editores y eventos (WhatsApp, llamada, cómo llegar, Doctoralia, formulario).
- ⏳ Día 7 y día 30 tras el cierre: revisar Search Console y GA4 (PLAN 12.3, MAINTENANCE.md).

## CHECKPOINT 5 — aprobado por José (D-086)

1. ✅ Horario de GBP, Doctoralia y revisión legal quedan como mejora continua (José, 2026-10-16, D-086). Prueba del formulario: ✅.
2. ✅ Pasada en iPhone (Safari) — José, 2026-10-15.
3. ✅ José aprueba esta lista (2026-10-16, D-086): CHECKPOINT 5 cerrado; pendientes de horario/Doctoralia/legal como mejora continua.

## Trazabilidad y límites de los checks

| Bloque | Evidencia |
|---|---|
| Páginas, títulos, responsive y enlaces | qa-cierre15/10 + nueva auditoría16/10 y public.json/capturas; estados clínicos según atestación local D-078/D-082 |
| Aprobación médica y publicación | PLAN7.6/7.7, BATON13/10 18:50, D-082; no nuevas aprobaciones por Codex |
| Legales publicados | HTTP y builds; actualización Cloudflare aprobada por José y desplegada según BATON14/10 16:55; no equivale a revisión regulatoria8.6 |
| Seguridad/2FA/backup | SECURITY, reportes locales14/10 y scripts; no nuevo login ni restore por auditor |
| Formulario real | Atestación de José15/10 aportada por el encargo y esta lista; no repetida por agentes, sin datos del mensaje guardados |
| SEO/schema/GA4 | Auditorías14b/16, PERFORMANCE y fixtures de eventos; no se certifica cobertura Google ni conversiones recibidas en backend |
| Navegadores | QA local14/10 Edge/Firefox, atestación Safari de José15/10; Android completo pendiente |
| Perfil de Google | QA16/10 12:30, solo atestación de edición; URL UTM contrastada con sitio. Descripción nueva referida como «texto del encargo383caracteres» sin transcripción: falta literal para cotejo. No se entra al perfil ni se confirma publicación de servicios |

La lista revisada está DONE como documento (11.5). Durante esta auditoría llegó la aprobación de José D-086:11.6DONE; horario/Doctoralia9.5 y revisión legal8.6 siguenOWNER como mejora continua, sin bloquear el lanzamiento. Los límites técnicos se conservan y no se presentan como pruebas completadas. 2FA activo no equivale a login autenticado probado por Codex. La frase antigua «prueba pendiente de formulario» queda superada por la atestación15/10.
