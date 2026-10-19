# 13.6 · Sincronización de borradores EN y aprobaciones de la Dra. · 2026-10-19 (sesión local)

## Decisión de José (D-102, chat local, 2026-10-19)

- «Sí, las revisó ella»: las 12 aprobaciones del 17-oct, entre las 10:44 y las 10:48 (Monterrey), hechas con el enlace de su guía (`by: irina`; usuario 2, `revisor_medico`), son la revisión médica real de la Dra.
- «Sincronizar y pedirle 2 (Recomendado)»: aplicar el texto actual a los borradores y pedirle que apruebe About y Contact.

## Antes: simulación de solo lectura (`tools/audit/simula-setup-en.php`)

- 6 páginas iguales y aprobadas: en, ent, faq, first-visit, sleep y thank-you.
- Legales iguales (D-099).
- **About (386):** cambia la negrita del titular («Ongoing <b>training</b>» → «<b>Ongoing</b> training», D-096), así que perdería la aprobación.
- **Contact (385):** cambia la negrita del titular y no tenía aprobación.
- La guía que vio la Dra. (`paginas-en.json`, desplegada el 17-oct a las 09:18 y con md5 igual al del repositorio) ya mostraba la negrita nueva.
- `build-en.py` regenera los builds sin diferencias con lo versionado.

## Ejecución

1. `tools/deploy/backup-db.sh` → `~/deploy-backups/20261019-172941/db.sql.gz` (448 K, 30 tablas, comprobado).
2. `tools/deploy/setup-en.sh` (solo borradores):
   - aviso únicamente en dr-irina-gonzalez-saez («la Dra. debe aprobarla de nuevo»);
   - las 5 fichas: «aprobada o publicada, sin cambios»;
   - textos del consultorio: ninguno escrito;
   - menús EN asignados.
3. `wp litespeed-purge all`.

## Después

| ID | Entrada | Estado | Aprobación |
|---|---|---|---|
| 384 | en | draft | 17-oct 10:45 |
| 387 | ent | draft | 17-oct 10:45 |
| 394 | sleep | draft | 17-oct 10:46 |
| 389 | first-visit | draft | 17-oct 10:46 |
| 397 | thank-you | draft | 17-oct 10:46 |
| 388 | faq | draft | 17-oct 10:47 |
| **386** | **dr-irina-gonzalez-saez** | draft | **pendiente (retirada por el cambio de formato)** |
| **385** | **contact** | draft | **pendiente** |
| 412–416 | 5 fichas | draft | 17-oct 10:47–10:48 (sin cambios) |
| 390, 392, 395 | legales | draft | D-099 (José) |

- **Repetición de la simulación:** las 11 páginas salen **IGUAL**; los borradores coinciden exactamente con el build del repositorio.
- **Comprobación pública:**
  - `/en/`, `/en/contact/` y `/en/dr-irina-gonzalez-saez/` → 404;
  - sitemap 10 + 2 + 3, sin EN;
  - `/`, `/contacto/` y `/dra-irina-gonzalez-saez/` → 200, sin hreflang ni enlaces /en/.
- **Qué no se hizo:** no se ejecutó `publish-en`, no se publicó nada y no se tocaron las aprobaciones a mano.
