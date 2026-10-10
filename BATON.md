> **Producción 2026-10-10 15:02 (sesión local): desplegado hasta 7a8fe3f** (logos con viewBox recortado ef587ce y tooltips; centro de recursos e8589c7/5a4b681; og-default ab0f4c4). Con OK de José: setup-medical (recursos 286/319/320 actualizados en draft con medical_review_required, área orl, fuentes), setup-og (ID 361, JPEG; Rank Math open_graph_image fijado; setup-og.sh corregido con `patch insert`), setup-dra, purgas. Verificado: og:image en /, Dra. y /recursos/; /recursos/ 200 con h1 y mensaje de «pronto», **pero meta robots index,follow (debía ser noindex sin recursos publicados: abierto para la nube)**; tooltips de logos OK en hover (1440) y toque (Galaxy), imágenes blancas; logos a 60/84/48 px. Capturas `/preview/qa/logos-{1440,galaxy,iphone13}-20261010-1449.png`, `logos-tooltip-{1440,galaxy}-20261010-1502.png`. Pendiente de José: fesormex.svg en positivo; nombres finales de los tooltips.

> **Producción 2026-10-10 14:46 (sesión local): desplegado hasta f270f4d** (logos D-066 + títulos de la Dra. c11d5c9 + plugin 0.4.4; backups `~/deploy-backups/20261010-144106/` y `…-144459/`; setup-dra; purga). Los 9 SVG (viewBox 178×100, blanco, trazados) están en `assets/brand/logos/` con los slugs. En vivo: `.di-logos` morado con h2 «Membresías y hospitales», 18 img (9×2) a 56/40 px, carrusel 70 s/45 s, consola limpia. Observación: los logos se ven pequeños (el arte no llena el viewBox) y fesormex.svg parece arte lineal con marco; propuesto a la nube subir el alto o recortar viewBox. Capturas `/preview/qa/logos-{1440,galaxy,iphone13}-20261010-1446.png`. También en vivo: migas con aire (1b65d60) y bloque overline/título/copy pegado a los botones en heros interiores (9e18d96).

> **Producción 2026-10-10 14:01 (sesión local): desplegado hasta c6b751c** (aa32c69: título ≤10vh, home sin fila de confianza a ≤760 px de alto, migas bajo la cabecera en heros cortos). Medido en 1440 con innerHeight real: home 828/900 y 677/749 con la marquesina asomando 72 px; Primera consulta 735/900 y 691/749, migas y=152; Dra. 828/900 (migas 185) y 719/749 (migas 152). Capturas `/preview/qa/peek-{home,primera,dra}-1440x{900,749}-20261010-1401.png`.

> **Producción 2026-10-10 13:54 (sesión local): desplegado hasta 7e71044** (q100 definitivo + `irina-movil` 1600 px con `<picture>`; heros con «peek»; migas sutiles). Fotos re-subidas a q100 (hero-home 355, hero-dra 356, dra-escucharte 357 PNG, bg 358, area-orl 359, area-sueno 360) + setup-site/setup-dra + purga; backups `~/deploy-backups/20261010-134047/` y `…-135249/`. Pesos medidos (transferSize) por dispositivo: escritorio → hero-home 3 391 KB, hero-dra 2 937 KB, fondo 3 133 KB, retrato PNG 725×1536 1 020 KB, áreas 768 px 538/473 KB, **total portada 8 567 KB**; Galaxy/iPhone → hero-home 1600×1067 **1 175 KB**, hero-dra 1600×1102 **1 475 KB**, fondo 1600 1 118 KB, retrato PNG completo 2 452 KB, áreas 1536 px 2 107/1 849 KB, **total portada 8 713 KB** (antes 13 MB con full en móvil; con q95 habría sido ≈ 2,5 MB). `<picture>` con 1 `<source>` en hero home, hero Dra. y fondo; `.di-bleed__bg:has(img)` sigue aplicando. Heros (viewport 1440×749 real en headless): home 957 px (contenido manda; no asoma la siguiente sección a esa altura), Dra. 766, Primera consulta 699 (min-height 677/512); iPhone 390×844: home 724 → la marquesina asoma 54 px sobre la barra; Dra. 724; Primera 538. Migas: 1440 Dra. y=144 (cabecera 112, eyebrow 182) centradas en el hueco; Primera consulta y=105–143 (pegadas a la cabecera: hueco corto); iPhone Dra. 183–236 en 2 líneas al 40 %, Primera 145. Capturas `/preview/qa/{home-hero,escucharte,dra-hero}-{1440,galaxy,iphone13}-20261010-1353.png` y `peek-{home,dra,primera}-{1440,iphone13}-20261010-1354.png`.

> **Producción 2026-10-10 13:23 (sesión local): plugin 0.4.2 (WebP q100 al subir, D-064) desplegado y las seis fotos re-subidas** (hero-home 343, hero-dra 344, dra-escucharte 345, dra-escucharte-bg 346, area-orl 347, area-sueno 348; setup-site + setup-dra + purga). Con la optimización de la CDN apagada por José (D-063), a cada dispositivo llega el archivo completo: hero-home WebP 2400×1600 **3 391 KB**, hero-dra WebP 2040×1405 **2 937 KB**, fondo WebP 2400×1600 **3 133 KB**, retrato PNG 1024×2170 (el WebP no pesó menos; alfa) **2 452 KB** en móvil / 1 020 KB (725×1536) en escritorio, tarjetas de área WebP 1536×1536 **2 107 / 1 849 KB** en móvil (768×768, 538/473 KB en escritorio). Portada en un teléfono ≈ **13 MB de imágenes** (antes ≈ 0,4 MB con la CDN reescalando). Calidad: íntegra. Capturas `/preview/qa/{home-hero,escucharte,dra-hero}-{1440,galaxy,iphone13}-20261010-1323.png`. **Decisión pendiente de José:** mantener q100 (peso) o bajar la calidad WebP a 85–90 (≈ 5–8× menos peso, sin diferencia visible) y/o reactivar el reescalado de la CDN a 2400 px.

> **Producción 2026-10-10 12:15 (sesión local): desplegado hasta bb15e45** (merge de 3f0f612 hero con fotografía + 2b4f32e versión en src/srcset de imágenes altas; antes 997dc17 y d5f8204). Respaldos `~/deploy-backups/20261010-121000/` y `…-121300/`; purgas hechas. Hero de la Dra. (foto definitiva hero-drairina2, adjunto 342 `hero-dra-v20261010120345.png`, focus 50% 0% / 95% 50%, clase `di-bleed--photo`): 1440 → foto a sangre sin parallax, rostro 147–314 bajo la cabecera (112), figura 883–1353 dentro, sombreado horizontal 90° en multiply (42 % → 0 % al 60 %); 390/360 → foto en banda superior (92–506) con la figura entera (172–371 / 143–342) y rostro libre (154–225), texto oscuro debajo sobre relleno #d3a98a, sin sombra; consola limpia. Capturas `/preview/qa/dra-hero-{1440,390,360}-20261010-1215.png`. Retrato de Escucharte con emulación de dispositivo (Galaxy S23 UA + client hints móviles; iPhone 13 UA): currentSrc `dra-escucharte.png?v=334-29fc94`, natural 360×762 / 390×826 (ratio 0.472 = completo), fit alfa activo (style.left −32 / −37 px), rostro 89 % / 93 %. Capturas `escucharte-{galaxy,iphone13}-20261010-1214.png`. José revisa en su Samsung y en el iPhone de la Dra.

> **Producción 2026-10-10 12:00 (sesión local): desplegado hasta e1943d1** (db8d1a8 tope de alto del retrato ≤430 px, 844229e encaje alfa/texto en main.js, e1943d1 docs) + 4452a92/2d8b6dc del mapa. Respaldo `~/deploy-backups/20261010-115947/`; purga hecha; sin recarga de contenido. Medido en vivo (consola limpia, carrusel 1 tarjeta/3 puntos, botón 52 px, retrato al fondo):
> 360 → sección 804, retrato 176–459 (283×600, `max-height` 600 px, `style.left` −32 px), rostro visible 89 %, palabra más cercana «respiratorios» a ≈5 px de la silueta; 390 → 757, retrato 189–490 (302×640, left −37 px), rostro 93 %; 430 → 717, retrato 230–522 (293×621, left −20 px), rostro 96 %; 1440 → sin cambios (849, sin `style.left`). Visual: rostro completo y silueta pegada al párrafo; la tarjeta de credencial pasa por encima del brazo (por diseño). **Aviso:** por el tope de alto, en 360 px el retrato mide 283×600 frente a los 334×708 de la versión anterior (≈15 % más pequeño); D-061/BATON dicen «sin reducir más la foto»: José decide si acepta el tope o se retira (`max-height`) manteniendo el encaje alfa. Capturas `/preview/qa/escucharte-{360,390,430,1440}-20261010-1200.png`.

> **Entrega vigente:** José autorizó acercar la silueta sin reducir más la foto. El HEAD posterior a db8d1a8 añade encaje alfa/texto y reserva del párrafo. Claude despliega código y purga; no recarga contenido. Ver sección final «Entrega vigente: silueta junto al texto». Q-039 requiere ambos teléfonos reales.

> Verificación más reciente: el CSS60/40 ya es público en las muestras móviles de Codex, pero José sigue viendo casi lo mismo. A360px la foto pasó deleft207,672 a207,797px y mantieneheight707,750px: el cambio fue prácticamente nulo. Ver docs/ESCUCHARTE_DISCREPANCIA_MOVIL_2026-10-10.md. Propuesta aislada−12px adicional solo en vista estrecha; sale del margen D-061 y Claude debe presentarla a José. No se modificó ni desplegó el tema en esta verificación. Q-039 abierto.

> **Producción 2026-10-10 11:07 (sesión local): desplegado hasta fdd6346** (incluye 30ef717 Escucharte 60/40 de Codex, 3a3c69e Map ID y eb1bd8f dedup de atributos). Respaldo `~/deploy-backups/20261010-110701/`; purga hecha. Verificado: hero con `loading`, `fetchpriority` y `decoding` ×1; mapa igual (sin Map ID: marcador clásico, 13 tiles, pin, consola limpia; `maps_map_id` no sale en REST). Escucharte 60/40 medido en vivo (grid 60/40, retrato a −5 px de la división, alto = fila, sin translateX, al fondo, consola limpia): 360 → sección 804, texto 16–213, retrato 208–542 (334×708), rostro visible 49 %; 390 → 735, texto 16–231, retrato 226–527 (301×639), rostro 67 %; 430 → 695, texto 17–255, retrato 250–532 (283×599), rostro 87 %; 1440 sin cambios (849). La silueta (brazos) pisa 4 px el borde de la columna de texto, no los glifos. Capturas `/preview/qa/escucharte-{360,390,430,1440}-20261010-1107.png`. Revisión visual de José pendiente.

> Indicación y entrega más recientes: D-061 y docs/ESCUCHARTE_60_40_2026-10-10.md sustituyen el gate facial anterior. Se implementó grid60/40, imagen al inicio de su columna con−5px, alto de fila y recorte parcial permitido. Pruebas locales pasan; no desplegado. Para este ajuste: deploy-code.sh → purga → capturas360/390/430/1440. Sin nuevos JSON ni recarga de contenido por este cambio. La auditoría general ya se desplegó hasta9053a4b según la sesión local; esta continuación no requiere repetir setup-site/pages/dra. Las preguntas siguen por Claude.

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

### Integración con el relevo concurrente de Claude

Se conservaron los commits9053a4b (sizes100vw para nitidez) y a1b6b83 (despliegue previo y decisionesD-057..D-060). D-058 sigue siendo la decisión de mantener borradores; D-059 ya autoriza migrar Maps cuando llegue el mapId; D-060 ya autoriza archivar tras cerrar la entrega. La nueva referencia60/40 usaD-061 para no reutilizar esos IDs. Último CSS de esta continuación:30ef717. No repetir preguntas ya respondidas.

## MENSAJE ACTUAL — resultado del teléfono

No asumir CSS viejo por el informe de José: la geometría nueva a360 es casi idéntica a la anterior. La captura390 mejoraba por la menor altura de la figura, no por moverla a la izquierda. Se verificó producción con perfil móvil/DPR2 y se dejó una propuesta concreta deleft−17px a≤360, actualmente fuera del tema. Claude consulta esa desviación de−2/−5 con José y coordina la comparación en su ancho/zoom real. No desplegar narrow-position-proposal.css como si estuviera aprobado. Las tres tarjetas pasan en producción; el problema sigue siendo la composición percibida.

## Ajuste de altura móvil autorizado · 2026-10-10
José autorizó corregir la dependencia entre renglones y escala del retrato. En <=430px, max-height: clamp(600px,164vw,640px) limita la imagen; mantiene altura100% cuando la fila es menor, proporción, fondo, texto, grid60/40, offset−5 y anclaje inferior. A360 pasa de708 a600px; a390 normal conserva639px. Esta reducción limitada en vistas estrechas sustituye el alto literal de D-057/D-061 por la autorización más reciente; no introduce las variantes A/B anteriores.
Pruebas: columns.cjs (tres tarjetas,360/390/430/768/1440), portrait-cap.cjs (320/360/390/430 y raíz tipográfica20px). Evidencia: docs/audit/2026-10-10/portrait-cap.json y portrait-cap-360.png. HTML público con CSS local: no demuestra despliegue ni el comportamiento del teléfono real. Q-039 continúa abierto hasta revisión real.
Claude local: desplegar solo código con tools/deploy/deploy-code.sh, purgar y verificar teléfono; no regenerar contenido por este CSS. Codex no despliega. Preguntas por Claude.

## Entrega vigente: silueta junto al texto · 2026-10-10
Autorización literal más reciente de José: «corrígelo para que la silueta quede casi pegada al texto en ambos teléfonos, sin hacer más pequeña a la doctora. Déjalo listo para que Claude lo despliegue».
Se conserva la escala de db8d1a8; no se reduce más. Hasta430px, el párrafo reserva24px a la derecha dentro de su columna60% (mismas palabras/fuentes). main.js calcula el encaje a partir de128 bandas conservadoras del canal alfa del retrato actual1024×2170 y las cajas de cada palabra del párrafo/cita/eyebrow, con4px de separación. Se recalcula al cargar imagen/fuentes, resize, ResizeObserver y fin de revelado. Solo cambia left de la imagen; altura, proporción y anclaje inferior permanecen. Tarjetas/botón conservan su fondo y capa superior. >430px vuelve al CSS previo; escritorio/tablet sin ajuste nuevo. No cambia fondo, marca, menú, sombras ni lógica del carrusel.
Evidencia normal320/360/390/393/412/430: silhouette-fit-metrics.json. Letras aumentadas20px: silhouette-large-metrics.json. En360, imagen600px y left175,797 (antes207,797); en390, imagen639,594 y left188,797 (antes225,797); en430 la nueva envoltura aumenta el alto621,078, nunca reduce la figura. Intersección del alfa con cajas del párrafo0% en las doce muestras; es verificación geométrica conservadora, no certificación visual de ambos dispositivos ni WCAG completa. columns.cjs verifica tres tarjetas, estabilidad, anclaje y sin overflow/error JS en360/390/430/768/1440. Node check main y harness pasa; sin PHP/build/JSON de contenido afectados.
El perfil está ligado al retrato actual; si cambia la fotografía debe regenerarse (portrait-position.cjs --profile), incluso si conserva proporción. El guard de proporción evita aplicarlo a una imagen de formato diferente. Sin JS conserva la posición CSS anterior como fallback.
Claude local: deploy-code.sh del HEAD de esta entrega y purga; NO setup-site/pages/dra ni SMTP. Verificar en los dos teléfonos reales; Q-039 sigue abierto hasta esa revisión. Preguntas por Claude. Codex no despliega. HTML público con assets locales no demuestra despliegue. Esta entrega sustituye la propuesta aislada−12px y el offset fijo como criterio de aceptación; conserva60/40 y prioriza la silueta visible por indicación actual.
