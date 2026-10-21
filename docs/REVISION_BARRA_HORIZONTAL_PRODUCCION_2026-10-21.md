# Barra móvil horizontal: ratificación en producción — Codex, 2026-10-21

## Veredicto

**VERIFIED en producción.** Se ratifica la entrega 7b8b6a2 y el despliegue declarado de c61ac01. Frente a f2e0603, el delta de producto son únicamente cinco líneas de components.css. El GO anterior queda ejecutado; no debe repetirse el despliegue ni la publicación.

## Evidencia independiente

- Se ejecutó alcance-reserva.cjs contra producción: **7/7**. Solo se sustituyó la ruta de carga de Playwright para usar el runtime disponible. En 667×375, 740×360 y 844×390 hay reserva de 58 px; en 932×430, 375×667, 390×844 y 1440×900 no la hay. Cabecera conservada, fondos a todo el ancho, desborde cero y sin errores JS en estas cargas. Registro: docs/audit/2026-10-21-barra-horizontal-codex-prod/alcance.log.
- CSS servido idéntico byte por byte al repositorio (78 377 bytes; SHA-256 dcecdbfd7ad9e33e5cc95b200c60561749cf3b65f8842c1cac6deebd641dd108). No se sustituyeron assets en el navegador: es evidencia pública del CSS desplegado.
- Portada y Primera consulta: cuatro GET, todos cache hit; HTML idéntico entre Accept-Language en-US y es-MX por ruta. Cotejo registrado en cotejo.json de la misma carpeta.
- seo_check.py --base https://drairinagonzalez.com --prod: salida 0, todas las aserciones pasan, sitemap 30 URL (15 EN y 15 ES). Resultado completo en seo.json. Tres rutas EN de Sueño no devolvieron cabecera x-litespeed-cache: aviso conservado, no fallo SEO ni acreditación de hit en esas rutas.

## Evidencia recibida y cotejada

En docs/audit/2026-10-21-barra-horizontal-prod/: matriz.log contiene 210 filas OK y cero fallos; interaccion.log contiene 132 filas OK y cero fallos. Herramientas endurecidas en c61ac01, sin cambios de producto adicionales. No se repitieron esas dos matrices completas en esta ratificación.

Regresión Q-081 aportada: 63/63, 37/37, 9/9 y EN→ES; se cotejó el registro, sin atribuir su ejecución a Codex. Los cuatro prefijos MD5 del servidor coinciden con los del repositorio en los archivos aportados. Los 16 registros de estado antes/después son idénticos. Esto coteja artefactos; no constituye lectura independiente de la base de producción.

Respaldo 20261021-083841 (464 K, 30 tablas), ejecución y purga atestados por José/Claude; no se accedió al servidor ni se ensayó restauración.

## Alcance y cierre

La reserva usa la misma consulta horizontal de la columna; conserva cabecera, fondos y acciones. Sin cambios de contenido, aprobaciones, estados, textos de marca, menús, sombras o encuadres. Q-081 y su mejora anterior siguen VERIFIED. Doctoralia permanece apagado según evidencia recibida.

Inglés: 19/19 (100 %, 7/7 tareas DONE); español: 221/228 (83/86 tareas DONE). No se añade avance por esta mejora. PLAN conserva sus estados; PROGRESS se regenera sin cambios esperados.

Límites: Chrome con tamaños emulados, sin teléfonos físicos, Safari ni Firefox. La matriz recibida cubre 30 rutas; las siete comprobaciones independientes de geometría se hicieron en Primera consulta. Las verificaciones no garantizan toda combinación futura de contenido y dispositivo.

Codex no desplegó, publicó, ejecutó setup-en/publish-en, envió formularios ni entró en cuentas. Se conservan fuera del commit los cuatro HTML de preview previamente modificados por el usuario.

## Aclaración posterior: aviso de caché

**Corrección del aviso de caché — Codex, 2026-10-21:** auditado 2664c93. La herramienta normaliza los nombres de cabecera a minúsculas tanto en respuestas correctas como HTTPError; no cambia producto ni servidor. Autoprueba y seo_check --prod repetidos: salida 0, sin avisos. Se retira el límite anterior de las tres rutas EN de Sueño: era un falso aviso por mayúsculas, no ausencia acreditada de caché. Evidencia nueva: `docs/audit/2026-10-21-barra-horizontal-codex-prod/seo-cabecera-corregida.json`. La mejora horizontal conserva VERIFIED y el avance no cambia.

El resultado histórico seo.json se conserva; la nueva ejecución sustituye su interpretación sobre cabeceras. La autoprueba existente valida las aserciones SEO; no incluye por sí sola una simulación específica de capitalización de cabeceras.
