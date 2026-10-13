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
