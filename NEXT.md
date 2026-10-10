# NEXT — Próximo trabajo

Actualizado: 2026-10-10 · Codex. Estado/progreso: STATUS/PROGRESS. Evidencia: QA. Despliegue: BATON.

## NOW

1. José elige A/B de Escucharte y acepta el retrato proporcional con tope; A está implementada. Ver `docs/audit/2026-10-10/AB-360.png` y `AB-390.png`.
2. Sesión local despliega con OK del propietario siguiendo BATON. Si la elección visual espera, el corte `b4b0716` incluye seguridad, schema y enlaces antes del cambio visual; usar checkout limpio separado.
3. Repetir capturas/medidas, FAQ única, HTML de hero sin duplicados, horario oculto fuera de REST y enlaces Home sin 404. No publicar clínica.
4. Probar roles, REST/Gutenberg, nueva revisión e idempotencia en copia/staging. No usar fichas de producción para ensayos de estado.
5. Confirmar aprobación con la Dra. de bloques marcados borrador en Dra./Primera consulta; entonces retirar draft=yes del build y regenerar JSON. No inferir aprobación porque la página esté publicada.

## AFTER técnico

- Inventariar consumidores de Ops antes de allowlist (Q-040).
- Consolidar NAP/horarios/hospitales repetidos con PracticeSettings, preservando textos aprobados (Q-042).
- Evaluar WebP lossless del retrato por el pipeline: 2,61 MB → 1,36 MB en ensayo local; no tocar CDN ni borrar adjuntos.
- Cerrar QA Safari/iOS/Android/Firefox, CWV/Lighthouse, teclado completo y staging protegido; Chrome no sustituye esos gates.
- MapId/AdvancedMarker cuando José decida; Marker funciona, aviso documentado.
- Archivar maquetas/legacy sin borrar cuando José decida. Variante light conservada sin uso (D-054).

## Dependencias de José / Dra.

Aprobaciones en mini app; foto definitiva Dra.; GA4; revisión legal/regulatoria; horario GBP; cobertura Search Console. Maps ya restringida según el encargo: no se pide confirmar otra vez; inspección independiente en Cloud fuera de esta sesión.

## Inglés

Después del lanzamiento español y fecha elegida por José. Se retiran fechas hipotéticas contradictorias; no iniciar ahora.
