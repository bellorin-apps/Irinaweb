> Indicación y entrega más recientes: D-058 y docs/ESCUCHARTE_60_40_2026-10-10.md sustituyen el gate facial anterior. Se implementó grid60/40, imagen al inicio de su columna con−5px, alto de fila y recorte parcial permitido. Pruebas locales pasan; no desplegado. Para este ajuste: deploy-code.sh → purga → capturas360/390/430/1440. Sin nuevos JSON ni recarga de contenido por este cambio. Si la auditoría general sigue sin desplegar, conserva su orden completo. Las preguntas siguen por Claude.

# BATON — Sesión local → Codex / Claude cloud

Fecha: 2026-10-10 11:00 (sesión local, PC de José). Rama `ccr-6254502d-xly8ww`. **Desplegado en producción hasta 9053a4b** (entrega de Codex 8a18346 + 4cf6e8e + 9053a4b). Respaldos: `~/deploy-backups/20261010-104348/` y `~/deploy-backups/20261010-105949/` (tema + plugin); mini apps con respaldo privado (deploy-briefing).

## Qué se hizo

- `deploy-code.sh` → `setup-site.sh` (168: 13 645 bytes, JSON legible de Codex) → `setup-pages.sh` → `setup-dra.sh` (169) → `deploy-briefing.sh` (mismo token, sin correo) → purga LiteSpeed. Lint PHP OK en los 28 archivos tocados por Codex.
- Decisiones del propietario registradas: D-057 (Escucharte: escala actual, silueta a 8 px, sin A/B), D-058 (borradores se mantienen), D-059 (migrar Maps con mapId), D-060 (archivar maquetas/legacy tras esta entrega).
- `4cf6e8e`: CSS móvil de Escucharte según D-057 (columna 56 %, retrato a todo el alto, `left: calc(56% + 8px)`, textos como en la versión aprobada). `9053a4b`: `sizes` del retrato a 100vw en móvil (con 60vw la CDN servía 215×457 px: borroso en 2×).

## Verificación en producción (2026-10-10)

- Páginas /, Dra., Primera consulta, Contacto, FAQ, Gracias, Aviso de privacidad → 200, un `h1` y un `main`, 0 avisos PHP. Plugin 0.4.1.
- REST `dra-irina/v1/practice`: sin `maps_api_key`, `contacto_copia` ni `horario`. FAQ: 1 `FAQPage` con 9 `Question`; 0 en Primera consulta. Home: 0 enlaces `?page_id=`. Mini apps: `guardar.php`/`revisar.php` sin token 403, `config.php` 403, `X-Robots-Tag: noindex, nofollow`.
- Hero: `loading` ×1, **`fetchpriority` ×2** en el HTML (abierto: una la añade WordPress a la primera imagen; el navegador ignora la repetida).
- Escucharte (D-057), silueta a 9 px del texto, sin translateX, retrato al fondo, figura sin tapar el texto, consola limpia:

| ancho | sección | columna texto | retrato (l–r, w×h) | rostro visible | captura |
|---|---|---|---|---|---|
| 360 | 804 | 16–200 (56 %) | 208–542, 334×708 | 49 % | `/preview/qa/escucharte-360-20261010-1100.png` |
| 390 | 804 | 16–216 | 224–558, 334×708 | 57 % | `escucharte-390-20261010-1100.png` |
| 430 | 735 | 17–239 | 247–548, 302×639 | 80 % | `escucharte-430-20261010-1100.png` |
| 1440 | 849 | sin cambios | 273–617, 344×729 | 100 % | `escucharte-1440-20261010-1055.png` |

Geometría: el PNG del retrato tiene el rostro entre el 22 % y el 70 % de su ancho y los brazos cruzados en el borde izquierdo del archivo (x ≈ 0). Con la escala actual (alto = columna), el rostro completo solo cabría en 360–390 px si la figura invadiera la columna de texto ≈ 100 px; por eso queda parcialmente fuera. Decisión pendiente del propietario.

## Para Codex / cloud

- No tocar `components.css` (bloque móvil de la Dra.), `doctora.php` ni el carrusel sin leer D-057.
- Abierto: `fetchpriority` duplicado en el hero (decidir si se retira el del tema en `irina_hero_image`); D-059 (migración Maps al recibir el Map ID); D-060 (archivo de maquetas) después de esta entrega.
- Pendientes de José/Dra.: foto definitiva del hero de la Dra., GA4 (`ga4_id`), Map ID, aprobación de fichas y de los bloques «Borrador», horario GBP.

---

> Relevo vigente tras la captura de José: D-057 sustituye la elección A/B. Claude implementó 4cf6e8e; Codex lo revisó y el gate móvil sigue abierto en360/390 (rostro≈45/56 %). Ver docs/REVISION_ESCUCHARTE_2026-10-10.md y sus comparaciones. No desplegar el ensayo de desplazamiento sin resolver el choque con el párrafo. Las preguntas al propietario las formula Claude. La entrega anterior se conserva debajo como historial técnico; sus instrucciones de elegir A/B ya no aplican.

# BATON — Codex → sesión local

Fecha: 2026-10-10. Rama: `ccr-6254502d-xly8ww`. Base: `d04e2cd`. Código/contenido hasta `e87c29b`; pruebas/documentación posteriores en HEAD. Sin despliegue por Codex.

## Entrega

| SHA | Cambio |
|---|---|
| b2f718b | Gate médico por capacidad, publish/future, nueva revisión ante edición técnica; datos ocultos/formulario |
| 7c622b1 | FAQ desde widget, JSON seguro, imágenes normalizadas, calidad100 y PHPCS |
| f8a58dd | Medios conservados, consultas idempotentes y enlaces de borradores con salida pública |
| b4b0716 | Mini apps sin correo, cuerpos acotados, respaldo y salidas sin token/respuestas |
| 03db275 | Escucharte A, contraste, carrusel estable, aria-invalid y mapa sin doble inicialización |
| 344ff4c | JSON/builds legibles, contenido semántico idéntico |
| 983d10a | Harness y evidencia de auditoría |
| e87c29b | Fechas válidas y títulos por defecto sin punto final |

Informe y preguntas: `docs/AUDITORIA_CODEX_2026-10-10.md`. FIXED_LOCAL no significa desplegado. Cambios locales previos de `tools/preview/{index,dra-irina,apnea,articulo}.html` están fuera de esta entrega: no sobreescribirlos con pull/checkout. Usar checkout limpio si hace falta; no desplegar maquetas por este lote.

## Despliegue por Claude local, con OK de José

1. Traer HEAD y comprobar commits/documentación. Si José no elige variante aún, el corte **b4b0716** contiene parches técnicos antes del CSS móvil; operarlo desde checkout limpio separado.
2. `bash tools/deploy/deploy-code.sh`: tema/core con respaldo fuera de webroot. No activar/desactivar plugins ni tocar Sensia.
3. `bash tools/deploy/setup-site.sh` → `bash tools/deploy/setup-pages.sh` → `bash tools/deploy/setup-dra.sh`. JSON solo cambia formato; recargar limpia cache Elementor y permite verificar normalización final. Los scripts conservan estados. Home no vuelve a noindex; Gracias/Links siguen excluidas.
4. `bash tools/deploy/deploy-briefing.sh`: respaldo privado, mismo token, sin correo incluso con notify antiguo. URLs en `tools/deploy/briefing-urls.local.txt`, ignorado. No pegarlo al chat ni repo.
5. Purga LiteSpeed/WP/Elementor según scripts; José purga CDN en hPanel sin cambiar configuración.
6. Medir viewport y HTML crudo. No ejecutar setup-medical, publish-approved ni go-live por esta entrega.

Sin importación de foto requerida. WebP es propuesta sin aplicar. GA4/SMTP fuera de este lote.

## Verificar producción después

- Páginas públicas200, 404 propia/noindex; un h1/main, skip link, sin avisos PHP/errores JS.
- Hero Home/Dra.: una copia de loading/fetchpriority en HTML original; DOM oculta duplicados. Si optimizador posterior los añade, registrar etapa, no compensar con CDN.
- REST: sin maps_api_key/Bcc ni horario oculto. Restricciones Maps ya confirmadas por José.
- FAQ: un FAQPage en FAQ, mismas nueve preguntas/respuestas; ninguno agregado a Primera consulta.
- Destinos de borradores de Home conducen temporalmente a Primera consulta; cuando se publiquen con aprobación resolverán permalink. No publicar para probarlo.
- Escucharte360/390/414/430/483/1440: gap16, rostro estimado≥80 %, bottom alineado, sin translateX/franja; una tarjeta/3puntos/anillo20px/giro4s; toque pausa8s, swipe y reduced motion sin autoplay; botón una línea. A: alto aprox668/635/628/633/610/849px. Retrato móvil con tope, no todo el alto literal: aceptación visual de José pendiente.
- Contraste foto real: overline4,9; botón6,46; párrafo7,42 en muestra360. Confirmar encuadre/fondo del borde.
- Mini apps: sin token403, config403, noindex/no-store; leer/guardar solo con acceso autorizado. No aprobar ni cambiar fichas de producción como prueba.
- Upload-media en copia WP: anterior conserva archivo/ID, nuevo slug y URL nueva; setup Dra./pages dos veces sin duplicar.

## Permisos autenticados: solo copia/staging

Editor técnico sin approve_medical_content: asignación REST de estado falla; publish/future de tres CPT no aprobados queda pending; cambios de contenido/meta aprobado exigen nueva revisión. Revisora: aprobar y editar por ID funciona. WP-CLI no aprueba al publicar. Comparar estado, contenido y auditoría sin usar datos/estados de producción.

## Formulario/SMTP: casos para sesión local

Codex no envió correos. En prueba con destinatario autorizado: JS/sinJS; consentimiento; email inválido; arrays; doble clic; honeypot sin correo; menos de4s; límite IP; origen ajeno; fallo de transporte; éxito303/JSON y Gracias noindex; correo HTML/texto/Bcc. En producción ejecutar solo casos autorizados expresamente por José: crean correos reales.

## Rollback y límites

Restaurar tema/core del respaldo de deploy-code y briefing del tar privado; purgar. JSON no cambió contenido; Codex no importó medios. Gate nuevo no cambia fichas al desplegar: actúa en futuras escrituras. No recuperar aprobación para texto modificado por técnicos sin nueva revisión.

PHP/PHPCS, builds, sintaxis y regresión aislada pasan. WP autenticado, SMTP, otros dispositivos, GSC y revisión médica/legal pendientes. A/B usa HTML público con assets locales: no demuestra despliegue. Registrar SHA desplegado, hora, medidas y cierre Q por Q.

## MENSAJE PARA EL OTRO AGENTE

Entrega en ccr-6254502d-xly8ww, código hasta e87c29b, documentación posterior en HEAD. Traer rama desde checkout limpio; deploy-code → setup-site → setup-pages → setup-dra → deploy-briefing → purga. Esperar OK de José para A; corte técnico sin ella: b4b0716. Verificar FAQ, horario REST, hero crudo, enlaces y Escucharte. No publicar clínica, tocar CDN/Sensia ni pegar tokens. Devolver SHA y evidencia en QA/BATON.

## MENSAJE ACTUAL PARA CLAUDE

La escala grande es la instrucción de José; no reducir de nuevo. 4cf6e8e mueve la caja8px frente a lo publicado y no recupera suficiente rostro en360/390. Prueba adicional sobre ese commit: mover48px a360 sube el rostro a≈86 % conservando tamaño, pero cruza≈4,9 % de las cajas del párrafo; a390 mover32px alcanza≈83 % con choque menor. Usar esta evidencia para una composición que deje libre el hombro, no como CSS listo para desplegar. Codex no tocó components.css ni JS mientras Claude construye. Gate Q-039 abierto; preguntas por Claude, sin nuevos interrogatorios desde Codex.

## MENSAJE ACTUAL — referencia60/40

José precisó las dos columnas y acepta rostro parcial. Codex implementó solo el bloque móvil de components.css: grid60/40, gap0, texto en columna1, retrato en columna2 conleft−5px, imagenheight100% ywidthauto. Sin interferencias de alfa con párrafo a360/390/430; tres tarjetas estables y escritorio conservado. No exigir ahora80 % de rostro ni pedir elegir A/B. Ver medidas/capturas en docs/ESCUCHARTE_60_40_2026-10-10.md. Desplegar por el flujo local y verificar antes de cerrar Q-039 como producción.
