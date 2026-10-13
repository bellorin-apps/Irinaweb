# NEXT — Próximo trabajo

Actualizado: 2026-10-12 · Claude local. Estado/progreso: STATUS/PROGRESS. Evidencia: QA. Despliegue: BATON.

## NOW

Cierre del 2026-10-12 (sesión local). Producción en edfa4f0 (BATON). Progreso sin cambios: 25/95 tareas DONE (PROGRESS).

**En producción hoy**: migas en franja bajo el hero y «Conóceme» (D-071); menú Inicio + Conóceme (D-072); «Escucharte» con tarjeta beige translúcida y endoscopio nuevo tapado y con recorrido en móvil; retrato y endoscopio en WebP sin pérdida (plugin 0.4.5); carrusel de logos con inercia nativa, fricción 0,4 y relevo continuo al autoplay.

1. **Codex**: auditar con `docs/CODEX_REVIEW_PROMPT.md` (17 tareas REVIEW + entrega del 2026-10-12). José abre la sesión de Codex en el repo y le pasa el documento. Samsung verificado por José el 2026-10-12.
2. **Decisiones de José**: (a) y (b) resueltas en D-073 (móvil q95, GA4 y encaje diferidos); (c) fuentes sin cambios; pendiente: (d) opcional, exportar el endoscopio a 2048 px; (e) horario de la ficha de Google (GBP); (f) resuelta en D-074 (fondo y áreas a q95 en móvil).
3. **Dra.**: revisar los 3 artículos de recursos y las fichas en la mini app, (Primera consulta «Qué llevar» ya aprobado; quedan ORL y Sueño, en borrador). Solo con su aprobación se publica.
4. **Codex**: auditar las 17 tareas REVIEW y la entrega de hoy con `docs/CODEX_REVIEW_PROMPT.md` (José se lo pasa). **Técnico (cloud/Codex)**: fetchpriority duplicado del hero; Q-040 (allowlist de Ops); Q-042 (NAP centralizado).
5. **Archivo (D-060)**: mover `tools/preview/` y `tools/legacy/` a `docs/archive/` al cerrar la entrega, sin borrar.

## AFTER técnico

- Inventariar consumidores de Ops antes de allowlist (Q-040).
- Consolidar NAP/horarios/hospitales repetidos con PracticeSettings, preservando textos aprobados (Q-042).
- Evaluar WebP lossless del retrato por el pipeline: 2,61 MB → 1,36 MB en ensayo local; no tocar CDN ni borrar adjuntos.
- Cerrar QA Safari/iOS/Android/Firefox, CWV/Lighthouse, teclado completo y staging protegido; Chrome no sustituye esos gates.
- Migrar a AdvancedMarker según D-059 al recibir el Map ID; la decisión ya está tomada.
- Archivar maquetas/legacy según D-060 después de cerrar la entrega, conservando los cambios locales previos. Variante light sigue sin uso (D-054).

## Dependencias de José / Dra.

Aprobaciones en mini app; foto definitiva Dra.; GA4; revisión legal/regulatoria; horario GBP; cobertura Search Console. Maps ya restringida según el encargo: no se pide confirmar otra vez; inspección independiente en Cloud fuera de esta sesión.

## Inglés

Después del lanzamiento español y fecha elegida por José. Se retiran fechas hipotéticas contradictorias; no iniciar ahora.
Prioridad actual Escucharte: Claude despliega el límite de altura móvil autorizado y compara en teléfono real; sustituye la propuesta aislada de desplazamiento−12px, que no se incorpora.
Prioridad vigente: Claude despliega el encaje de silueta del HEAD posterior a db8d1a8 y comprueba ambos teléfonos. No aplicar la propuesta aislada−12px. Ver última entrega BATON.
