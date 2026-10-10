# Imagen para compartir en redes (og-default.jpg)

Composición 1200×630 en HTML (`og.html`) renderizada con Playwright (`shot.cjs`): foto del hero de la portada con el degradado ciruela en multiplicar, logotipo en blanco, overline, nombre con acentos D-049, claim del pie y dominio. Tipografía Iskra desde el kit de Adobe Fonts del sitio (el kit solo sirve con referer del dominio; el script navega a una URL del dominio interceptada y sirve estos archivos en su lugar).

Regenerar (desde esta carpeta, con `playwright` disponible):

```
curl -A "Mozilla/5.0" -o hero-home.webp https://drairinagonzalez.com/wp-content/uploads/2026/10/<hero-home-vigente>.webp
cp ../../../wp-content/themes/irina-gonzalez/assets/brand/logotipo-blanco.svg .
node shot.cjs && cp og-default.jpg ../../../wp-content/themes/irina-gonzalez/assets/brand/og-default.jpg
```

Publicar: `tools/deploy/setup-og.sh` (sube `og-default` en JPEG, exento de la conversión WebP, y lo fija como imagen Open Graph por defecto en Rank Math). No se versionan la foto ni las salidas intermedias.
