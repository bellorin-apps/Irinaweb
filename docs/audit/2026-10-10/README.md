# Evidencia del lote de auditoría

Fecha: 2026-10-10. Baseline de producción anterior a los parches; no hubo despliegue.

| Archivo | Qué demuestra |
|---|---|
| baseline-metrics.json / baseline-360/390/1440.png | Geometría y composición publicada |
| A/B-metrics.json / AB-*.png | HTML publicado con CSS/JS locales; A izquierda, B derecha |
| public.json | Once rutas/seis anchos, HTTP/landmarks/robots/schema parseable/headers; describe servidor sin parches |
| home-links.json | Diez destinos únicos404 de Home antes de la corrección |
| redirects.json | Redirección del dominio antiguo conservando ruta/query |
| interaction.json | Carrusel4s, toque/swipe, pausa8s, resize, reduced motion; assets locales |
| contrast.json | Muestras conservadoras en Escucharte360 con assets locales |
| php-regression.txt | Tests aislados con fixtures; API HTML oficial de copia externa WordPress6.8.3 |
| cta-live-390.png | CTA fotografiado en viewport después de scroll; confirma fondo morado |

Las capturas completas page-*.png y los recortes individuales A/B están disponibles localmente e ignorados para evitar duplicar decenas de MB en Git. Las comparaciones y tres baselines sí se versionan. Los artefactos no contienen HTML crudo, clave Maps, tokens ni respuestas de mini apps.

FaceVisiblePercent estima rostro con región x30–65 % del PNG, sin cabello; visibleImagePercent es porcentaje de la imagen completa. Son métricas distintas. Ver informe para límites y gates pendientes.
