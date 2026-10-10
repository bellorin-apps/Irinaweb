# NEXT — Próximo trabajo

Actualizado: 2026-10-10 · Codex. Estado/progreso: STATUS/PROGRESS. Evidencia: QA. Despliegue: BATON.

## NOW

Cierre del 2026-10-10 (cloud). Producción en HEAD 3b78ece (sesión local, BATON). Próxima sesión: lunes 2026-10-12.

**En producción hoy**: logos con viewBox ajustado, tooltips (D-068) y arrastre; títulos de la Dra. dictados; og:image por defecto (D-067); hub `/recursos/` en noindex con 3 artículos en borrador (8.2); «Escucharte» con tarjeta de cristal crema y endoscopio según el scroll (D-069, texto confirmado por la Dra.); lombrices acotadas en credenciales y trayectoria.

1. **Decisiones de José**: (a) calidad del hero para el LCP móvil, mantener q100 (D-064) o pasar a q95 (Q-025); (b) OK para GA4 en ocioso y encaje alfa diferido (Q-047), sin tocar calidad; (c) opcional: kit de fuentes bloqueante o `font-display: optional` para el CLS bajo red lenta (Q-046).
2. **Dra.**: revisar los 3 artículos de recursos (wp-admin → Recursos) y los bloques que siguen marcados «Borrador» en su página y en Primera consulta (D-058); solo con su aprobación se publican y se retira `draft=yes` del build.
3. **José en su teléfono**: probar el arrastre de los logos (la emulación entrega pocos eventos) y el endoscopio saliendo por debajo de la tarjeta.
4. **Técnico (cloud, próxima sesión)**: fetchpriority duplicado del hero (NEXT histórico); Q-040/Q-042; Fesormex queda con el SVG entregado por decisión de José.
5. **Maps**: cerrado con D-065 (estilo embebido, sin Map ID); no hay acción.
6. **Archivo (D-060)**: mover `tools/preview/` y `tools/legacy/` a `docs/archive/` al cerrar la entrega, sin borrar.

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
