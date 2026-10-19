# Copia local aislada para probar la versión inglesa (13.5/13.7)

Montada por la sesión local el 2026-10-19 en `tmp/wp-en-local/` (ignorada por Git). Estos archivos permiten reproducirla.

**Qué es:**
- WordPress 7.1.3, Elementor 4.3.5, Elementor Pro 4.3.1, Rank Math 1.0.280 y Hello Elementor 3.5.1, copiados en solo lectura desde producción, sin `wp-config.php`, base de datos, medios ni Sensia.
- Base SQLite (plugin oficial *SQLite Database Integration*); `tmp/php/php.exe` 8.4 con un `php.ini` propio.
- WP-CLI 2.12 y servidor embebido de PHP en `http://127.0.0.1:8890` (`-t site`, con `router.php`).

**Aislamiento:**
- `WP_HTTP_BLOCK_EXTERNAL` y `mu-plugins/aislamiento-local.php` bloquean y registran toda petición HTTP fuera de localhost, y anulan el correo (`pre_wp_mail`; con `correo-ok.flag` simula un envío correcto sin enviar nada).
- En el navegador de prueba, toda petición a otro host se aborta.
- `lib.local.sh` sustituye a `tools/deploy/lib.sh`: sin `env.sh`; `ssh`/`scp` devuelven error 97; `rssh`/`rscp` ejecutan en la copia; `/tmp/` remoto → `rtmp/`.

**Datos sintéticos:**
- Imágenes generadas e importadas con `upload-media.sh` local.
- Páginas españolas con los `setup-site/pages/dra` reales.
- Las 5 fichas españolas desde `tools/content/en/fichas-es-aprobadas.json`, aprobadas por una revisora sintética local (rol `revisor_medico`).
- Ajustes de Rank Math y del consultorio sin `maps_api_key`, `maps_map_id`, `ga4_id` ni `contacto_copia`.
- Las aprobaciones inglesas de las pruebas son sintéticas y solo existen en esta copia.

**Uso:**
1. `sync.sh` (tema y plugin del repositorio) y `mkdeploy.sh [con-elementor]`. La variante `con-elementor` es solo de prueba (Q-076).
2. `t/preflight.sh`, `t/fallos.sh`, `t/setup-en.sh`, `t/seo_check.py`, `t/ui_check2.cjs` y `t/form_check.cjs`.
3. `mu-plugins/zz-inyeccion-fallos.php` solo actúa con `fallo.json`.

**Límites:**
- SQLite ≠ MariaDB.
- Sin LiteSpeed, sin CDN y sin las reglas del servidor de Hostinger.
- Sin Turnstile (claves no copiadas).
- El host es `127.0.0.1`, no el dominio.
