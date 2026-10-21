# Auditoría de la barra móvil horizontal — Codex, 2026-10-21

Base4411dda, rama ccr-6254502d-xly8ww. Único delta de producto desde bc57b3e: cinco líneas de components.css, dentro de la misma condición que activa la columna (max-width899,landscape,max-height500).

## Revisión del CSS

La columna mide46px y su margen derecho es --gutter. Reservar58px adicionales en los contenedores deja12px entre contenido y columna: ancho viewport−2gutter−58; margen izquierdo gutter. Se preserva el máximo de contenedor normal/estrecho, y los fondos no se restringen. La cabecera, menús, sombras y reglas de foto no cambian. Vertical y anchos≥900 no entran en esta regla. No se ocultan CTA ni acciones.

Se inspeccionó la captura con fotos reales844x390 de Primera consulta: el CTA termina antes de la columna y las tres acciones conservan su posición. La revisión no presupone fidelidad de todos los posibles encuadres/tipos de contenido: solo estilos y muestras descritos.

## Evidencia y pruebas

Claude aporta WordPress local0/210, interacción0/132 (control45/132), banco público0/90 (control72/90), regresiónQ-08163/63,37/37,9/9 ySEOlocal sin fallos. Los logs0/210 y0/132 se cotejaron: filas y resumen coherentes; no hay controles secundarios bajo la columna en el log210. No se repitió toda la instalación WordPress local por Codex.

Codex endurece dos herramientas, sin más cambios de producto: solape-barra.cjs ahora falla también por enlaces secundarios bajo la columna y exige3acciones; interaccion.cjs no acepta puntos fuera de pantalla como alcanzables y admite CSS candidato con contador obligatorio de sustituciones. No cambia el comportamiento del sitio.

Codex repite **132/132** de interacción con HTML público y CSS candidato solo dentro del navegador:43sustituciones verificadas. ES/EN, ambos idiomas de navegador,667×375/740×360/844×390, cinco puntos táctiles por control, tres acciones46×46, menú/giro/texto largo y desborde pasan con el criterio endurecido. Evidencia docs/audit/2026-10-21-barra-horizontal-codex/interaccion.log y capturas. El texto largo de667×375 y la muestra844×390 fueron inspeccionados:CTAajustado, columna libre. No se afirma una instalaciónWordPresslocal ni despliegue a partir de ese banco.

ESLint/stylelint del proyecto y sintaxis de ambas herramientas pasan. Sin cambios PHP/JS del producto, no se repiten pruebas PHP ajenas a este delta. Los navegadores son contextos desechables, sin envío de formularios ni activación de enlaces WhatsApp/teléfono. El texto largo se inyecta solo en DOM de prueba, sin guardarlo.

## Veredicto y límites

**GO para desplegar la mejora CSS de4411dda, tras pull de la auditoría y con respaldo/verificación posterior.** La auditoría no añade más cambios de producto; solo endurece herramientas y documentación. La mejora queda verificada en pruebas, no en producción.

Ningún ensayo con HTML público y assets candidatos prueba despliegue o purga/caché de producción con el nuevoCSS. Chrome con tamaños/idiomas simulados; no dispositivos físicos,Safari oFirefox. La matriz mide vistas y desplazamientos muestreados, no todo ancho/texto o combinación posible.

Claude: tras pull/confirmarSHA, entrega con deploy-code.sh y respaldo comprobado/purga. El script sincroniza plugin/tema; el delta de producto de esta mejora es solo components.css. Sin manipulación temporal, setup-en/publish-en, contenido aprobado, medios, aprobaciones o estados. No repetir lanzamiento. Doctoralia apagado.

Después: usar herramientas endurecidas para matriz30×7 y desplazamiento completo, interacción/menú/giro/texto largo en ES/EN y con aviso visible/oculto; comprobar la reserva solo en la mediaquery, CTA/barra completos y pulsables, sin desborde, sin cambio de cabecera/fondos, Q-081 ySEO, cachéactualizada y16estados/huellas intactos. Registrar SHA,respaldo,purga,capturas ylímites para ratificación en producción. No afirmar resolución publicada antes de esa evidencia.

Q-081 y suP3 mantienen VERIFIED de su entrega anterior; esta mejora de barra es separada. Inglés19/19(100%) y español221/228 sin cambio. Codex no despliega/publica, no accede a secretos/cuentas/Sensia; cuatroHTMLpreviosdeJoséintactos/excluidos.
