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
