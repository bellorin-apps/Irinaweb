# MAINTENANCE — Mantenimiento, plan SEO 6–12 meses y KPIs

Actualizado: 2026-10-16 · Codex · revisión de evidencia y límites. PLAN 12.4. Sitio corto (D-076): punto de encuentro para conocer a la Dra., no enciclopedia.

## Rutinas

| Frecuencia | Qué | Quién |
|---|---|---|
| Tras cada actualización automática de Hostinger (WordPress, Elementor, plugins) | Abrir portada, Contacto y una ficha; comprobar que el editor de Elementor carga | José o sesión local |
| Semanal | Leer los correos del formulario; si llega spam, avisar (ajustar SpamGuard o Turnstile) | José |
| Mensual | Search Console: Páginas (motivos de no indexación), Rendimiento (consultas y clics), Experiencia (Core Web Vitals) | José, con guía |
| Mensual | GA4: clics en WhatsApp, llamadas, «Cómo llegar», Doctoralia y envíos del formulario | José |
| Trimestral | Usuarios de WordPress (solo admin y revisor_medico), 2FA activo, sin contraseñas de aplicación; revisar plugins instalados | Sesión local (WP-CLI, solo lectura) |
| Trimestral | Lighthouse de portada, Contacto y una ficha; comparar con PERFORMANCE.md | Nube o sesión local |
| Anual | Revisión médica de ORL, Sueño y fichas publicadas por la Dra. (fecha en lastReviewed); revisión del aviso de privacidad | Dra. y José |

## Cambios y despliegue

- Todo cambio de código o contenido pasa por la rama `ccr-6254502d-xly8ww`: la nube o Codex escriben, la sesión local despliega con `tools/deploy/*.sh` (respaldo previo automático en `~/deploy-backups/`) y verifica en vivo.
- Contenido clínico nuevo: solo con aprobación de la Dra. en su app y OK de José (D-078). Fichas archivadas: cualquier publicación futura requiere decisión expresa de José y aprobación médica vigente de la versión concreta; una aprobación antigua no autoriza contenido modificado ni un calendario automático.
- Fotos: subir en PNG/JPEG; el sitio genera WebP, versión móvil q95 y, en cabeceras, escritorio q95 (D-081).
- Rollback: procedimiento revisado en docs/ROLLBACK.md. Ensayos de código/DB en copia aparte atestados16/10:12.1DONE en ese alcance; restauración del servicio en vivo no ensayada por decisión de José. Código, .htaccess, base de datos, medios y mini apps tienen respaldos/alcances distintos; retirar el bloque de hardening no equivale a restaurar todo el sitio.

## Plan SEO 6–12 meses

1. **Perfil de Google (GBP)** — mayor impacto local: NAP idéntico al sitio, horario real, categorías «Otorrinolaringólogo» y relacionadas con sueño, fotos del consultorio, publicaciones mensuales y respuesta a cada reseña.
2. **Reseñas**: pedir reseña en Google y Doctoralia tras la consulta a **todas** las pacientes, sin incentivos (política de Google). Enlace directo de Google: https://g.page/r/CUfia9HEij5cEAE/review (mensaje de WhatsApp guardado como respuesta rápida `/resena`). La Dra. responde cada reseña sin confirmar que es paciente ni mencionar salud. Meta: +4 al mes.
3. **Doctoralia y directorios**: mismo nombre, dirección y teléfono; enlace al sitio.
4. **Enlaces de confianza**: perfiles en hospitales (Christus Muguerza, Zambrano Hellion, Ángeles Valle Oriente), Consejo, sociedades y colegio con enlace al sitio.
5. **Contenido opcional**: revisar demanda real de Search Console sin compromiso de publicar una ficha al mes. D-076/D-082 mantienen el sitio corto y las 21 archivadas; ampliar solo si José lo decide, con revisión médica vigente y posterior OK de publicación. No reabrir la enciclopedia por una rutina SEO.
6. **Search Console**: cada mes, consultas con impresiones y CTR bajo → ajustar títulos y descripciones en `seo.json`.

## KPIs

| Indicador | Fuente | Meta a 6 meses |
|---|---|---|
| Eventos de contacto (WhatsApp, llamada, formulario), no pacientes únicos | GA4 | Tendencia creciente mes a mes; no sumar eventos duplicados de un mismo clic ni interpretar Gracias como correo recibido |
| Clics orgánicos | Search Console | Tendencia creciente; base: primer mes completo tras el cierre |
| Posición media en «otorrino monterrey», «otorrino cumbres», «apnea del sueño monterrey», «ronquido tratamiento monterrey» | Search Console | Top 10 en al menos 2 |
| Páginas indexadas / indexables canónicas | Search Console | Objetivo sobre las 15 URLs indexables; excluir Links/Gracias, borradores y archivos404. Indexación decidida por Google, sin garantía |
| Core Web Vitals (campo) | Search Console › Experiencia | «Buenas» en móvil y escritorio |
| Reseñas en Google | GBP | +24 en 6 meses |

## Revisiones de día 7 y día 30 (PLAN 12.2/12.3)

- **Día 7**: Search Console (cobertura, errores), GA4 recibiendo eventos, recepción del formulario confirmada por José y sin errores 404 nuevos. Solo José realiza cualquier nuevo envío real; los agentes no prueban formulario/SMTP en producción.
- **Día 30**: indexación de las 15 URL, primeras consultas y clics, contactos por canal; ajustar títulos según datos.

## Medición y responsabilidades

Las metas SEO son objetivos sin volumen/ranking prometido, no resultados ni garantía de indexación. Registrar baseline, fecha, filtros y fuente; no generar clics de conversión o envíos por agentes para rellenar métricas. GA4 mide eventos y la visita a Gracias no prueba entrega SMTP. Cookies observadas en la muestra de 15/10 fueron GA4; revisar cambios de terceros tras actualizaciones sin guardar tokens ni datos de pacientes.

Fechas orientativas de seguimiento de la ronda QA del 15/10: día7 22/10 y día30 14/11. No son por sí mismas una aprobación ni una automatización creada; José ya aprobó CHECKPOINT5 por D-086 (11.6DONE). Horario/Doctoralia/legal continúan como mejora continua, no como bloqueos del lanzamiento. 12.2/12.3 conservan su estado hasta disponer de evidencia de cada revisión. GBP/Doctoralia los gestiona el canal autorizado: esta auditoría no accede a esas cuentas. Horario real pendiente de la Dra.; sitio Previa cita D-032.
