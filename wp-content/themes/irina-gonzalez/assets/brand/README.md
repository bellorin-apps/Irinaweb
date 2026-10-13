# Marca

SVG del logotipo (exportados por el propietario desde Illustrator (logo1, logo2, logonegativo), texto en contornos). `isotipo.svg` se incrusta inline en header y footer vía `irina_brand_mark()`; variantes `-blanco` / `-mono` según fondo. Los .ai/.pdf originales no se versionan (Drive).

Favicon: `favicon.svg`, `favicon.ico` (16/32), `icon-192.png`, `icon-512.png` y `apple-touch-icon.png` se generan del isotipo (fondo púrpura #8C4EAA, isotipo blanco) y los enlaza `inc/setup.php` cuando no hay site icon en WordPress.

`site.webmanifest` enlaza los iconos192/512, usa la misma paleta y se emite siempre desde `inc/setup.php`, también si WordPress tiene site icon. Favicon/apple-touch de WordPress conservan prioridad. El modo de visualización es browser; no se añade service worker ni capturas de formularios.
