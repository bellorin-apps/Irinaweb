# Auditoría de cinco tareas · 2026-10-13

Base79d24ff, rama ccr-6254502d-xly8ww. Encargo CODEX_REVIEW_PROMPT_2026-10-13.md. Producción previa declarada f5c1571. CuatroHTML de José preservados; sin despliegue, SMTP, aprobación médica ni operaciones con aplicaciones privadas.

## Veredictos y alcance

| Tarea | Resultado final | Evidencia |
|---|---|---|
|6.4 Página Dra.|FIX→DONE| Página pública200, un h1/main, sin Borrador, Physician/ProfilePage, miga Conóceme. Endoscopio/texto revisados visualmente y con geometría en Galaxy/iPhone/desktop, normal/reduced-motion. Se corrige teclado de logos (copias sin foco, original visible/tooltip, pausa con foco) y dos títulos D-049 (Membresías/Agenda), mismas palabras. Código/template/build verificados localmente; patch pendiente de despliegue. |
|7.3 Primera consulta/Contacto/FAQ/pacientes|DONE| Tres públicas200 sin Borrador. Títulos dictados conservados; FAQPage solo FAQ. Fondo de Primera consulta continuo antes/después de abrir FAQ, cuatro pasos ilustrados; sin overflow con UA real. Pacientes queda cubierto por preparación/qué esperar/niños/pagos/seguros en Primera consulta+FAQ: no requiere una URL vacía /pacientes/. |
|8.9 Favicon/app/OG|FIX→DONE| OG y Twitter en once rutas: mismo JPEG200,1200×630,120068bytes. Iconos/apple-touch públicos200. Faltaba manifest: añadido con iconos192/512 existentes, dimensiones verificadas y hook que funciona con/sin site iconWP. Manifest solo está preparado localmente, no observado en producción. |
|9.4 GA4/GSC/eventos|FIX→DONE| En once rutas, un di-ga4 y un proveedor con G-5961XX8QZ7; se inserta con readyState=complete. Fixtures prueban seis eventos, cola, DNT incluso con gtag externo y exclusión de editor/ID inválido. Se extiende la exclusión de editores también al listener, no solo al head. Propiedad GSC confirmada por la evidencia aportada en DISCOVERY§1.6 y D-022 (apex); no se entra en consola ni se certifica cobertura actual, que sigue en1.4. |
|10.1 Optimizaciones de rendimiento|DONE| Políticas D-063/064/073/074 contrastadas: heros/fondo/áreas móviles1600 con m, fuentes intactas por decisión, originales escritorioq100, alfaWebP lossless, lazy y atributos únicos, gtag/encaje diferidos. Pesos reales/transferencias con UA completo registrados. Este DONE acredita las optimizaciones aprobadas, no cumplimiento de todos los presupuestos numéricos antiguos ni CWV/Lighthouse actuales; medición integral/informe queda10.2. |

DONE corresponde a IMPLEMENTED+TESTED+REVIEWED+FIXED+VERIFIED en repo y a la evidencia pública de lo ya desplegado. Las correcciones nuevas se distinguen como locales; no se afirma que el código preparado esté desplegado ni se certifica staging. La verificación pública del patch pertenece al relevo de Claude.1.2 permanece REVIEW por medios/menús sin detalle.

## Hallazgos corregidos

- Las nueve copias de logos tenían aria-hidden=true pero tabindex0. Ahora llevan−1; se conserva accesibilidad/nombres/alt de los nueve originales. Al enfocar se detiene el carril, vuelve a posición accesible y muestra el elemento con scroll nearest; al salir se reanuda. El gesto normal no cambia; regresiones de punteros/cancelación/rAF pasan.
- Membresías y hospitales / Agenda tu primera consulta carecían del primer tramo b en la página Dra. Se añade en build, default de widget y template de logos. Se regenera dra-elementor.json: solo dos settings.title; comparación sin etiquetas confirma mismas palabras. Credenciales.json sin cambios. No se altera el CTA dictado de Primera consulta ni textos/menús/sombras.
- `site.webmanifest` faltaba en todas las páginas inspeccionadas. Se genera con nombre/paleta/iconos actuales y display browser; enlace global escapado en wp_head. Mantiene site iconWP. No se introducen dependencias ni service worker.
- El head excluía editores, pero el listener no. Ahora ambos retornan para editor o ID inválido; se evita enviar eventos si otro componente aporta gtag.
- PHPCS detectó tres avisos de alineación en pasos/ubicación recientes; corregidos sin cambio funcional.

## Evidencia

`docs/audit/2026-10-13/five-public.json`: once rutas200, un h1/main, sin Borrador/erroresJS/overflow, once JPEG sociales correctos, respuestas de iconos, GA append complete, variantes móviles y desktop. En la inspección pública las copias de logos aún tienen foco, los títulos aún carecen del nuevo b y no hay manifest: son los problemas del estado ANTES del patch.

`five-local.json`: templatePHP real con nueve originales y copias−1, teclado/tooltip visible, pausa, reduced-motion; manifest con/sin site icon y dimensiones reales de iconos; seis eventos y cola despuésload, DNT/editor/ID inválido. No envía nada a Google ni prueba un editor autenticado real. `contact_submit` se registra al visitar Gracias; no se envió un formulario ni se acredita recepción de correo/conversión real. Abandonar antes de cargar gtag aún puede perder un evento; se mantiene D-073.

`five-visual.json` y doctor-Galaxy/iPhone.png: emulación Chromium con UA completo,360/393/1440, normal/reduced-motion. Palabras dentro de la tarjeta y ningún elemento por delante del texto en las muestras visibles; sin desborde; endo-p1 en reduced-motion. La superposición parcial del instrumento bajo la tarjeta es intencional (D-069), no un requisito de cero intersección de cajas. No certifica Safari nativo ni AA completo.

Continuidad del fondo: se comparan cajas de las imágenes, no de sus ventanas recortadas. Desktop: ambas alturas1216px antes /1276px al abrirFAQ, diferencia de tops0,047px. Las ventanas de sección tienen alturas distintas por diseño; eso no significa una costura. Los cuatro pasos cargan en Primera consulta y Home; layouts con UA Galaxy/iPhone sin overflow.

### Pesos seleccionados (KiB, no todos los recursos de la página)

| Imagen | Galaxy/iPhone | Desktop |
|---|---:|---:|
|Hero Home|262/262|3391|
|ÁreaORL|577/577|537|
|ÁreaSueño|373/373|472|
|Fondo Home|96/96|3133|
|Retrato Home|662/1090|662|
|Hero Dra.|263/263|2936|
|Endoscopio|450/450|450|
|Hero Primera consulta|125/125|2575|
|Hero Contacto|265/265|3255|
|Fondo Qué llevar/FAQ|774/774|774|

Heros/fondo/áreas móviles1600 y ?m presentes. Retrato/endoscopio conservan alfa y codificación VP8L sin pérdida; retrato se selecciona vía srcset725×1536/966×2048: no se afirma identidad de píxeles con el original1024×2170 después del remuestreo. Fondo PC es PNG RGB1024×1623, sin derivado móvil (menor que el umbral1200 de conversión); fallback existente aprobado/documentado en BATON, no se reescala ni se sustituye en esta revisión. Sus774KiB quedan como oportunidad en10.2.

`five-summary.json` separa tamaño de imágenes seleccionadas de transferenciaResourceTiming: Home2,018MB Galaxy /2,456MB iPhone /8,393MB desktop; bytes de transferencia pueden ser0 por caché. No son peso total de CSS/JS/fuentes, ni una medida LCP/CLS/INP. La calidad95/100 se acredita por pipeline/código y evidencia previa de generación, no se infiere de la extensión/peso. Calidad100 escritorio y fuentes sin cambios son decisiones; los objetivos históricos≤150KiB deLCP/fuentes no se declaran cumplidos.

PHP77/PHPCS completo sin errores/avisos; JS aplicación/harnesses y bash18 pasan. Fixtures de calidad/alias de original y hero opcional pasan. Se reutilizan evidencias de seguridad pertinentes; no se repiten envíos ni pruebas destructivas. SearchConsole/roles/staging completos conservan gates separados.

## Relevo

Claude local: deploy-code.sh, luego setup-dra.sh para los dos títulos y purga LiteSpeed/CDN. No setup-pages/site, upload-media, regeneración ni publicación médica por este lote. Comprobar manifest200/JSON/iconos, originales/copies en tabulación, pausa/tooltip, títulos y DNT/editor. No probar formulario/SMTP en producción. El cambio de títulos no altera palabras ni credenciales; diff revisado.

Para1.2, esperar medios.csv y entradas pie/legal aprobadas por José, sin borrar/mover medios. Para10.2, medir Lighthouse/CWV actual y documentar presupuestos/excepciones (desktopq100, fuentes y PNG de PC), sin reducir calidad a espaldas del propietario. No se solicita otra aprobación de decisiones ya registradas.
