# Harness de auditoría

Herramientas de solo lectura sobre producción, salvo las pruebas PHP aisladas que usan fixtures en memoria. No envían formularios, correos ni escrituras a WordPress.

- `visual.cjs`: baseline publicado o `--local --variant=A/B` sobre HTML público con assets del repo interceptados. Incluye360/390/414/430/483/768/1024/1440.
- `public.cjs`: once rutas, seis anchos, HTTP, landmarks, schema parseable, noindex y cabeceras. Recorre la página para cargar lazy; capturas completas locales ignoradas.
- `interaction.cjs`: giro4s, swipe, pausa8s, resize y reduced motion.
- `contrast.cjs`: contraste conservador por caja, fondo capturado sin texto/figura; región central para botón. No certifica WCAG completo.
- `regression.php`: gate, permisos declarados, datos ocultos, destinos no publicados, FAQ, JSON y campos array; sin WP real/DB/SMTP. `IRINA_WP_SOURCE` opcional permite usar parser HTML oficial de una copia WP externa.
- `progress.py`: genera PROGRESS desde PLAN, solo DONE.

Node requiere Playwright y sharp; `IRINA_PLAYWRIGHT`/`IRINA_SHARP` pueden apuntar a paquetes disponibles. Chrome: `C:/Program Files/Google/Chrome/Application/chrome.exe`; adaptar al host si cambia. PHP8.4/Composer de esta sesión están en tmp ignorado; no desplegarlos. Las rutas de salida llevan fecha fija del lote2026-10-10 para reproducir la evidencia.

La estimación facial usa intervalo x30–65 % del PNG, marcado visualmente sin cabello. No confundir rostro con ancho total del retrato. Las capturas A/B no demuestran despliegue: el servidor entrega todavía su versión anterior. No guardar HTML ni URLs Google con clave ni respuestas/token de mini apps.

Composición60/40: columns.cjs comprueba dimensiones,−5px y las tres tarjetas. portrait-position.cjs acepta --tag y --offsets para conservar resultados anteriores. El criterio facial≥80 % ya no aplica a D-061; no interpretar faceVisiblePercent como gate actual.
