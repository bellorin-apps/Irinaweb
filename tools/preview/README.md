# Maquetas Checkpoint 2 (dirección visual)

Páginas estáticas con el sistema visual (tokens del tema + Iskra vía Adobe Fonts). Se despliegan en `https://drairinagonzalez.com/preview/` (noindex) con `tools/deploy/deploy-preview.sh` para revisión del propietario en escritorio y móvil.

- **v2 (vigente, raíz):** dirección cálida, titulares gigantes, minimalista, cabeceras a sangre, reveal/parallax en `main.js`. Sueño nocturno solo en páginas de Sueño (`apnea.html`).
- **v1 (`v1/`):** primera dirección, descartada por el propietario el 2026-10-08; se conserva para comparar.
- `index.html` Home · `dra-irina.html` página de la Dra. · `apnea.html` página médica (sub-marca Sueño) · `articulo.html` artículo.
- Los `_*.html` son parciales; se ensamblan con `sed "s/__TITLE__/…/" _head.html > x.html; cat _body >> x.html; cat _foot.html >> x.html`.
- Fotografías: marcadores hasta la sesión. Textos clínicos: borradores marcados, pendientes de la Dra.
