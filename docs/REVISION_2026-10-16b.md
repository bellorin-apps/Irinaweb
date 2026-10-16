# Auditoría de operación y restauración · Codex · 2026-10-16b

Base `de864bc`, que contiene `e58c91c`; pull realizado en `ccr-6254502d-xly8ww`. Producción declarada al inicio por José: `19e4440`; durante el trabajo llegan las atestaciones locales de D-087/Q-065 desplegados y confirmados. El HTTP acredita comportamiento, no el SHA del servidor. La instrucción reciente sustituye la base/producción y el estado histórico de Q-063/Q-064 del encargo16b. AGENTS, encargo base y coordinación leídos. Los cuatro HTML locales y `h.txt` preexistentes no se leen/modifican por Codex ni se incluyen en sus commits.

## Veredictos

| Tarea / hallazgo | Resultado | Evidencia / límite |
|---|---|---|
| 12.1 | FIX→DONE | Atestación QA: código restaurado en copia aparte idéntico al respaldo, lint79 y diferencia con producción concordante con Git; DB importada en base de prueba con30/30tablas y mismas filas. No se inspecciona el volcado ni se repite SQL. Correcciones locales de salvaguardas con19casos de stubs y dos regresiones originales reproducidas. El alcance aprobado por José es copia aparte; restauración en vivo expresamente no ensayada. |
| 12.2 | DONE | Quince URLs del sitemap200/index/follow/canónica propia;30vistas390/1440 sin overflow, imágenes fallidas ni pageerror. 404 real en español/noindex, auxiliaresnoindex, readme/xmlrpc403, cabeceras/SSL y301 reproducidos. Snapshot de servidor por atestación local. No hay lectura de visitas reales en hPanel/monitor404: no se certifica ausencia global de errores de visitantes; seguimiento queda en12.3. |
| 9.5 | OWNER | URLUTM200 con canónica limpia y WhatsApp528123685381 coincidente. Literal383caracteres ya presente, compatible con formación/edad/ubicación/previa cita del sitio; siete servicios cotejados en ORL/Sueño y fichas. Horario real/Doctoralia siguen mejora continua D-086; no entrada al perfil ni comprobación de visibilidad pública de sus cambios. |
| Q-062 | VERIFIED | Cierra la carencia documental: literal reproducido en QA y cotejado con contenido público. «Trastornos»/«desórdenes» y género profesional son variantes del mismo descriptor aprobado, no nuevos claims. No nueva consulta de GBP. |
| Q-063 | VERIFIED | `/?author=1`, author_name y /author/ de prueba404 con «Página no encontrada» y follow/noindex; filtro request temprano, fuera de admin/sesión. No exposición nueva del nombre de administrador. |
| Q-064 | VERIFIED | Archivos /recursos/, /padecimientos/, /tratamientos/ y query de condicion404 con título/noindex de404. Quince páginas siguen200 y cinco rutas antiguas301. Callback preserva admin/singles/preview/Sueño en fixture14casos; no sesión real nueva de wp-admin. |
| Q-041 | WONTFIX D-065 | Dos avisos de Marker obsoleto, sin errores JS. Mapas Home/Contacto renderizados/visibles con .gm-style, marcador clásico sin MapID y mismo enlace de llegada. Es decisión D-065 de estilo JSON, no un OWNER que espere MapID según la propuesta D-059 antigua. QA alineado; no se abre Google Maps/GBP. |

Actualización concurrente preservada: D-088 retira6.6/7.4/7.5(7pesos), pasando de237/90a230/87 sin sumarDONE.12.1 añade5pesos y12.2 añade2: **204/230 = 88,70%;78/87DONE**. 9.5OWNER y11.2REVIEW permanecen; no se audita ni despliega Q-065/D-087 en este lote. La atestación concurrente confirma D-087/Q-065 desplegados y vistos por José; se conserva11.2REVIEW sin cambiar su estado por esta auditoría.

## Restauración: qué acredita y qué no

QA «Despliegue…» y «Ensayo de la base» aporta ejecución local16/10: respaldo de código elegido, copia de estado para deshacer, restauración de árboles completos en carpeta aparte, diff/lint y producción intacta. El ensayo DB importa en1s y compara30tablas/filas; reporta archivo temporal eliminado. Esta atestación es suficiente para el ensayo **en copia** decidido por José; no se exige un rollback vivo contrario a ese alcance.

La igualdad de filas no equivale a igualdad criptográfica de todos los datos, ni la copia prueba el funcionamiento del sitio con el respaldo restaurado. No se comprueban backups privados, config, consultas de producción o cuentas por Codex. Los límites se conservan en ROLLBACK/MAINTENANCE/checklist; no se llama a un mock «restauración real».

`deploy-code.sh` exporta antes del código: umask077, marcador «Dump completed», gzip/gzip-t y chmod600; si falla la primera operación remota, lib.sh set-e/pipefail corta el deploy. Se adelanta umask antes de crear el directorio. Stubs reproducen éxito, export fallido, export incompleto y gzip fallido; en los tres fallos no se alcanza ninguna copia de código. No se ejecuta deploy real.

## FIX locales de herramientas

- **Q-066 P2 FIXED_LOCAL:** antes, la lista/conteo podía fallar o las tablas diferir y el script terminar con0, incluso «Ensayo terminado». Ahora set-euo/pipefail, lista no vacía y resultado con al menos una tabla/ninguna diferencia; errores/discrepancias retornan fallo. Descompresión fallida no queda ocultada por mysql0.
- **Q-067 P2 FIXED_LOCAL:** antes se escribían credenciales en una conexión y el trap solo se instalaba en otra; un fallo de transporte entre ambas dejaba archivo. Ahora una conexión, mktemp/chmod600, trap antes de leer stdin, limpieza en exit/señales manejadas. Se valida timestamp y rechaza contraseña multilínea/identificación vacía de producción. Ni secreto ni contenido de opciones viajan por argv.

El harness ejecuta copias sanitizadas de los scripts reales con lib/env excluidos, WP/mysql/SSH simulados y datos sintéticos dentro de tmp. No cambia HOME: virtualiza sus referencias solo en el comando stub. Reproduce en `e58c91c` el éxito falso ante diferencias y el archivo temporal residual ante fallo de la segunda conexión; prueba que los scripts nuevos evitan ambos. Limpia únicamente ficheros sintéticos propios.19casos pasan; no se leen secretos ni se conecta a un servidor.

Permisos: se verifica llamada chmod600, no se certifica chmodLinux desde Windows. El ensayo exige Bash remoto con pipefail; no se prueba la integración remota del script corregido. Los stubs adicionales reproducen fallo de transporte sin dejar fichero. La protección de destino usa nombre distinto/vacío y respaldo propio confiable; no es sandbox para SQL arbitrario. Producción se leería solo para identificar DB y comparar filas en un uso real autorizado; no se escribe por el ensayo. Ninguna nueva prueba real ni clave se solicita en esta revisión.

## Día0/Día1 y límites de observación

Nuevo `public.json`: sitemap10/2/3,15canónicas propias/robotsindex,30vistas sin excepciones JS; la consola conserva bloqueos deliberados/diagnósticos de terceros, SSL validado por navegador sin ignoreHTTPS. Certificado observado para drairinagonzalez.com hasta2027-01-07T11:49:53Z; no garantía de renovación futura. Cabeceras front y cacheHit/Miss registrados, sin X-Powered-By.

HTTP→HTTPS y www→apex301 conservan rutas. HTTP+www puede pasar por dos301 (primero HTTPS, después apex); no se presenta como salto único. Cinco rutas de tipo301 a Sueño. Ocho casos404 de autor/archivos/error usan título español/noindex; Links/Gracias200/noindex; readme/xmlrpc403 y users404. No se prueba formulario/SMTP ni se generan pageviews GA, que se bloquea.

Servidor, por atestación QA13:00: checksums WordPress, plugins/cron/usuarios/2FA, opciones y registros disponibles. No se convierten registros ausentes/debug apagado en prueba de cero errores reales. No se consulta hPanel ni se activa monitor404. Para12.2 basta validación operativa15/16oct y snapshot local sin incidencias críticas; no hay requisito de un mínimo de visitas para cerrar una validación técnica. Día7/30 y seguimiento de visitantes siguen12.3, con fuente y límites reales.

## GBP y mapa

La descripción aplicada de383caracteres se coteja desde QA, no desde una cuenta: Consejo/ORL, sueño, niños desde0meses, CAB/Cumbres/Monterrey y previa cita coinciden con sitio/builds. Servicios: consulta ORL y endoscopia nasal/rinoplastia funcional-estética en ORL; ronquido/apnea, estudio en casa, CPAP y cirugía de ronquido/apnea en Sueño. No implica que la categoría/servicios en revisión ya estén visibles en Google.

Maps clásico renderiza en Home/Contacto con aviso de obsolescencia, como D-065 dispone. No se requiere MapID, no se cambian estilos ni marcador. El encargo16b que aún cita D-059 es histórico frente a la instrucción reciente y D-065.

## Evidencia y relevo

`docs/audit/2026-10-16b/`: public.json, console-followup.json, request.json y shell.json. Harnesses review-oct16b-public.cjs, review-oct16b-request.php y review-oct16b-shell.py. PHP79/PHPCS, JS y bash19 pasan. No assets web/builds modificados; solo herramientas locales de despliegue/ensayo y documentación. No se leyeron h.txt, secretos, volcados ni aplicaciones privadas. Los cuatro HTML de José se preservan.

Claude: pull. Avance88,70%,78/87DONE;12.1FIX→DONE y12.2DONE,9.5OWNER/11.2REVIEW. Q-063/064VERIFIED, Q-062VERIFIED, Q-041WONTFIXD-065. Q-066/067corregidos localmente con19casos: usar herramientas actualizadas en la próxima operación autorizada. **No desplegar ni ejecutar un ensayo real por este lote**, ni pedir claves/recrear base/hacer formularios. Q-065/D-087 es trabajo preexistente de otra entrega; no se introduce su despliegue como parte de esta auditoría. CHECKPOINT5D-086 y mejoras continuas siguen vigentes.

Consola: public.json registra consoleErrors, no se declara consola vacía. El follow-up de Home/Contacto identifica origen de bloqueos GA y telemetría POST de Maps abortados por el harness, más diagnósticos de challenges.cloudflare.com. Se registran solo host/flags, sin URLs ni mensajes completos que puedan incluir claves. Cero pageerror en las30vistas y mapas renderizados; no se convierte un diagnóstico del captcha automatizado en prueba de fallo del envío humano. También se confirma HTTPSwwwCPAP→apexCPAP301 como segundo salto de HTTPwww.

Integración al subir: commit concurrente91c1b09 preservado (fase5).5.1/5.2/5.3/5.5 pasan aREVIEW y Q-042FIXED_LOCAL, sin auditarlos en este lote ni sumarDONE. El avance sigue204/230=88,70%;78/87DONE. Sus cambios de código y linters conservan su propia evidencia y autoría.
