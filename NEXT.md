# NEXT — Próximo trabajo

Actualizado: 2026-10-10 · Codex. Estado/progreso: STATUS/PROGRESS. Evidencia: QA. Despliegue: BATON.

## NOW

0b. **og:image (D-067)**: sesión local: `deploy-code.sh` (plugin con la exención `og-*`), `tools/deploy/setup-og.sh`, purga y comprobar `og:image`/`twitter:image` en portada y Dra.; probar la vista previa en WhatsApp/LinkedIn (debuggers de Facebook/LinkedIn si la caché muestra la antigua).
0. **Logos (D-066)**: José exporta los 9 SVG (fill blanco, sin texto en vivo) con estos nombres: `christus-muguerza`, `zambrano-hellion`, `angeles-valle-oriente`, `hospital-universitario`, `hospitaria`, `consejo-orl`, `fesormex`, `sociedad-iberoamericana-sueno`, `colegio-orl-nl` (+ `.svg`). La sesión local los copia a `wp-content/themes/irina-gonzalez/assets/brand/logos/`, hace commit aparte, `deploy-code.sh`, `setup-dra.sh` (JSON ya regenerado) y purga; capturas de escritorio y móvil con UA real (D-062). Cloud revisa aire, velocidad y contraste con los logos reales.
1. **Desplegado 2026-10-10 (sesión local)**: entrega de Codex hasta 8a18346 + ajuste del propietario de «Escucharte» (4cf6e8e, D-057) + corrección de `sizes` del retrato (9053a4b, 100vw en móvil). Producción verificada: páginas 200 con un h1/main y sin avisos PHP; REST sin `maps_api_key`/`contacto_copia`/`horario`; FAQPage único en FAQ (9 preguntas), ninguno en Primera consulta; Home sin enlaces a borradores; mini apps 403 sin token y noindex; plugin 0.4.1. Abierto: el hero sigue con `fetchpriority` duplicado en el HTML (una copia la añade WordPress a la primera imagen; `loading` ya va una vez): Codex/cloud deciden si se retira la del tema.
2. **Escucharte en teléfono**: CSS60/40 ya observado en producción; en360 el inicio de foto apenas cambió0,125px y la altura sigue707,75px. Q-039 abierto por el resultado real. Claude presenta la propuesta aislada de12px adicionales a≤360 (offset total−17, fuera del margen D-061), conservando tamaño; sesión local compara el mismo ancho/zoom. Ver docs/ESCUCHARTE_DISCREPANCIA_MOVIL_2026-10-10.md.
3. **Maps (D-059)**: José crea el Map ID en Google Cloud (Google Maps Platform → Map Management → Create Map ID, tipo JavaScript, con el estilo asociado); la sesión cloud migra `map.js` a `AdvancedMarkerElement` y añade el ajuste `maps_map_id`; la sesión local lo carga y despliega.
4. **Borradores (D-058)**: confirmar con la Dra. la aprobación de los bloques marcados «Borrador» en Dra./Primera consulta; solo entonces retirar `draft=yes` del build y regenerar JSON.
5. **Archivo (D-060)**: tras cerrar esta entrega, mover `tools/preview/` y `tools/legacy/` a `docs/archive/` con enlaces actualizados (sin borrar).
6. Probar roles, REST/Gutenberg, nueva revisión e idempotencia en copia/staging. No usar fichas de producción para ensayos de estado.

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
