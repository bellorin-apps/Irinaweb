# D-101 · Despliegue solo de código con el inglés en borrador · 2026-10-19

**Ejecución:** José, desde la terminal de su PC, a las 16:38 hora local, con `tools/deploy/deploy-code.sh`. Antes, a la sesión local la había bloqueado el control de permisos.

**Versión desplegada:**
- Árbol de trabajo en **9b52e1e**.
- `wp-content` es idéntico al de b83d9ee, que es la base del GO de Codex. Desde ese GO no cambió el código del producto.
- `md5` de `Graph.php` (683ff4b3f9d9…) y de `Language.php` (93770612808a…): servidor = repositorio.

**Respaldo:** `~/deploy-backups/20261019-163838/` contiene:
- `db.sql.gz`: 460 K, 30 tablas, comprobado por el script;
- `dra-irina-core` e `irina-gonzalez` anteriores.

**Caché:** después se ejecutó `wp litespeed-purge all` («Se ha vaciado todo»).

## Antes (solo lectura)

- 16 entradas EN en borrador y 0 publicadas.
- `/en/contact/` → **301 a /contacto/**; `/en/` y `/en/sleep/` → 404.
- Portada con `inLanguage` `es`.

## Después

| Comprobación | Resultado |
|---|---|
| 16 entradas EN (IDs 384–397, 412–416) | Las 16 en `draft`; ninguna página publicada bajo /en/ |
| `/en/contact/` sin seguir redirecciones | **404, sin 301** (Q-075 resuelto en vivo) |
| Otras 9 rutas EN probadas | 404 |
| Sitemap | page 10 + condición 2 + tratamiento 3 = **15 URL, 0 EN** |
| Selector, hreflang y enlaces /en/ en las 15 ES | 0 / 0 / 0. `di-lang-es` es solo una clase del body. |
| Schema ES | 14 páginas con `es-MX`; portada con `es` de Rank Math (P3 aceptado). Physician 1 donde corresponde, 0 en las que no lo llevan; sin tipos de página duplicados. |
| Páginas ES | 15/15 HTTP 200, sin «Fatal/Warning» en el HTML |
| Fotos y menú | 6 páginas clave: 0 imágenes rotas; menú Inicio, Otorrino, Sueño, Conóceme, Primera consulta, Contacto y WhatsApp. Captura de /sueno/ correcta. |
| Errores | Consola sin errores. En el servidor no hay `debug.log` ni `error_log`. |
| Doctoralia | Apagado (sin `data-di-doctoralia`) |

**Qué no se hizo:** no se ejecutaron `setup-en` ni `publish-en`, no se publicó nada, no se probaron formularios ni SMTP y no se leyeron secretos.
