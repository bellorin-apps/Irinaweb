# NEXT — Próximo trabajo

Actualizado: 2026-10-10 · Codex. Estado/progreso: STATUS/PROGRESS. Evidencia: QA. Despliegue: BATON.

## NOW

1. **Desplegado 2026-10-10 (sesión local)**: entrega de Codex hasta 8a18346 + ajuste del propietario de «Escucharte» (4cf6e8e, D-057) + corrección de `sizes` del retrato (9053a4b, 100vw en móvil). Producción verificada: páginas 200 con un h1/main y sin avisos PHP; REST sin `maps_api_key`/`contacto_copia`/`horario`; FAQPage único en FAQ (9 preguntas), ninguno en Primera consulta; Home sin enlaces a borradores; mini apps 403 sin token y noindex; plugin 0.4.1. Abierto: el hero sigue con `fetchpriority` duplicado en el HTML (una copia la añade WordPress a la primera imagen; `loading` ya va una vez): Codex/cloud deciden si se retira la del tema.
2. **Escucharte (D-057)**: medido en vivo con la silueta a 9 px del texto: rostro visible 49 % (360), 57 % (390), 80 % (430); la figura no tapa el texto; escritorio sin cambios. Con la escala actual, el rostro completo no cabe en 360–390 px sin invadir la columna de texto: el propietario decide si lo acepta así o elige otra vía (opciones presentadas en el chat local).
3. **Maps (D-059)**: José crea el Map ID en Google Cloud (Google Maps Platform → Map Management → Create Map ID, tipo JavaScript, con el estilo asociado); la sesión cloud migra `map.js` a `AdvancedMarkerElement` y añade el ajuste `maps_map_id`; la sesión local lo carga y despliega.
4. **Borradores (D-058)**: confirmar con la Dra. la aprobación de los bloques marcados «Borrador» en Dra./Primera consulta; solo entonces retirar `draft=yes` del build y regenerar JSON.
5. **Archivo (D-060)**: tras cerrar esta entrega, mover `tools/preview/` y `tools/legacy/` a `docs/archive/` con enlaces actualizados (sin borrar).
6. Probar roles, REST/Gutenberg, nueva revisión e idempotencia en copia/staging. No usar fichas de producción para ensayos de estado.

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
