# Q-080 · despliegue de código y verificación en producción · 2026-10-20 (sesión local)

**GO:** Codex (1d3b6cf). José pegó el encargo en el chat local.

**Entrega:**
- Rama `ccr-6254502d-xly8ww`, árbol en **1d3b6cf**.
- Alcance frente a lo desplegado antes (9b52e1e): solo `wp-content/themes/irina-gonzalez/inc/i18n.php` (+47/−2).
- `md5` de `i18n.php`: servidor = repositorio (`69e6347bb0ea`).

**Ejecución:**
- `tools/deploy/deploy-code.sh`, desde la sesión local.
- Respaldo `~/deploy-backups/20261020-162726/`: `db.sql.gz` (472 K, 30 tablas, comprobado por el script), más el `dra-irina-core` y el `irina-gonzalez` anteriores.
- Después, `wp litespeed-purge all` («Se ha vaciado todo»).

**No se ejecutaron** `setup-en` ni `publish-en`. No se tocaron contenido, adjuntos, aprobaciones ni estados.

## Verificaciones (antes en `antes.log`, después en `despues.log`)

| # | Comprobación | Antes | Después |
|---|---|---|---|
| 1 | 404 EN `/en/no-existe-q080/` | 404 · «Page not found \| Dra. Irina González Sáez» | **404 · «Page not found \| Dr. Irina González Sáez»** |
| 2 | 404 ES `/no-existe-q080/` | 404 · «Página no encontrada \| Dra. Irina González Sáez» | igual |
| 3 | Alts de las fotos EN | En español | **En inglés:** «…in her office», «Otolaryngology (ENT)», «Snoring and sleep apnea», «— First visit / Contact / Sleep / Otolaryngology (ENT)». El alt de la endoscopia sigue igual (ya estaba en inglés). 0 imágenes rotas. |
| 4 | Alts ES y alts vacíos | — | ES sin cambios. Los alts vacíos EN igualan a su pareja ES: portada 5/5, About 9/9, First visit 6/6, y 0 en ENT, Sleep y Contact. |
| 5 | `og:image:alt` | «Dra. … Tu Otorrino Especialista en Sueño» en todas | **/en/ y /en/sleep/: «Dr. Irina González Sáez · Your ENT and sleep specialist»**; `/` y `/sueno/` sin cambio. **`twitter:image:alt` no se emite (0 etiquetas)**, así que su filtro no tiene efecto observable. |
| 6 | `seo_check.py --prod` | — | **Salida 0**: sitemap 30 (15 + 15), sin Gracias ni Thank you; 30 enlaces sin errores; `og:image` con 200; HTTP, canónicas, OG, hreflang, selector y schema correctos (`seo.log`, `C-seo.json`). |
| 7 | Estado y contenido de las 16 EN | `estado-antes.txt` | **Idéntico** (`estado-despues.txt`): 16 en `publish`, con la misma `post_modified_gmt`, las mismas aprobaciones y la misma huella md5 de `_elementor_data`/`post_content`. |
| 8 | Regresión general | — | 9 páginas EN y ES con 200, menús correctos, 0 imágenes rotas y Doctoralia apagado. Consola sin errores. Escritorio y móvil (375 px, sin desborde) correctos en `/en/first-visit/`. Sin `debug.log` ni `error_log`. |

**Resultado: Q-080 VERIFIED en producción.**

**Límites:**
- La captura visual cubre una muestra.
- `twitter:image:alt` no se emite en esta configuración de Rank Math.
- No se probaron formularios ni SMTP.
