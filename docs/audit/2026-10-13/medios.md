# Medios de producción: dónde se usa cada uno (sesión local, 2026-10-13)

Sirve para cerrar la tarea 1.2. Es una lista en solo lectura obtenida con WP-CLI: **no se borró ni se movió nada**. El detalle por archivo está en `medios.csv`.
La columna `propuesta` (KEEP/REPLACE/REMOVE) queda vacía: la nube propone y José aprueba.

Cómo se detecta el uso:
- Datos de Elementor, por ID, por slug referenciado o por nombre de archivo exacto.
- Contenido de entradas e imagen destacada.
- Ajustes `site_icon` y `custom_logo`.
- Imagen Open Graph por defecto de Rank Math.

Los archivos del tema (`assets/brand`, logos SVG) no son adjuntos y no aparecen.

| Grupo | Adjuntos | Peso de los originales |
|---|---:|---:|
| En uso | 18 | 24 MB |
| Versiones anteriores (`*-archivo-*`) | 23 | 55 MB |
| Sin uso detectado | 3 | 2,4 MB |
| **Total** | **44** | **82 MB** |

**Sin uso detectado:** 8 `favicon` y 151 `favicon2`, favicons antiguos; 351 `dra-irina-gonzalez-saez-2`, retrato duplicado.

**Ajustes:**
- `custom_logo` = 28. El tema no lo pinta: usa sus propios archivos de marca.
- `site_icon` = 240. Es el favicon publicado.
- og:image por defecto = 361, `og-default.jpg` (D-067).

**Otros:**
- 85 `logo-2-2` solo aparece en la plantilla Elementor «Pie de Pagina» (79), que está en borrador. El pie publicado es el del tema.
- 377 `pc-fondo` es un PNG de 1024 px, sin WebP ni versión móvil (774 KB).

## Entradas de pie y legal (menús)

| Menú | Entradas |
|---|---|
| Pie de página (footer) | Otorrinolaringología (170, borrador) · Sueño (171, borrador) · Primera consulta (172) · Preguntas frecuentes (173) · Contacto (174) |
| Legal (legal) | Aviso de privacidad (175) · Aviso médico (176) · Términos de uso (177) |

En el pie, Otorrinolaringología y Sueño apuntan a páginas en borrador. WordPress no muestra esas entradas a visitantes sin sesión hasta que se publiquen.

## Propuesta de clasificación (Claude cloud, 2026-10-13) · pendiente de aprobación de José

Criterio: se conserva todo lo que está en uso; de cada imagen resubida se conserva **la versión anterior más reciente** como respaldo para volver atrás (además del original en Recursos); se proponen para retirar las versiones antiguas, los favicons viejos sin uso y el retrato duplicado sin uso. La columna `propuesta` del CSV lleva la decisión y el motivo por fila.

| Propuesta | Archivos | Peso |
|---|---:|---:|
| KEEP | 25 | 31.5 MB |
| REMOVE | 18 | 50.3 MB |
| REPLACE (opcional) | 1 | 0.8 MB |

REMOVE: 8 favicon, 151 favicon2, 322 hero-home-archivo-322, 329 area-orl-archivo-329, 330 area-sueno-archivo-330, 332 dra-escucharte-bg-archivo-332, 334 dra-escucharte-archivo-334, 341 hero-dra-archivo-341, 342 hero-dra-archivo-342, 343 hero-home-archivo-343, 344 hero-dra-archivo-344, 345 dra-escucharte-archivo-345, 346 dra-escucharte-bg-archivo-346, 347 area-orl-archivo-347, 348 area-sueno-archivo-348, 351 dra-irina-gonzalez-saez-2, 365 dra-endoscopio-archivo-365, 367 dra-endoscopio-archivo-367.

Nada se borra sin aprobación explícita de José. Si la da: respaldo previo de `wp-content/uploads` (tar en `~/deploy-backups/`), borrado por WP-CLI solo de los IDs aprobados (`wp post delete <ID> --force` borra adjunto y archivos), purga y verificación de que ninguna página pública cambia (mismas URLs de imagen servidas). El pie: ORL y Sueño enlazan páginas en borrador, que WordPress oculta a visitantes hasta su publicación; no requiere acción.

**Aprobado por José el 2026-10-13** («Sí, retira los 18», D-075). Ejecuta la sesión local con respaldo previo.
