# Briefing de la Dra. (versión alojada)

Página autónoma (sin WordPress) para que la Dra. responda a un toque. Se despliega en `public_html/briefing/` del dominio.

- `index.html` — cuestionario; guarda en `guardar.php` con el token de la URL (`/briefing/?t=TOKEN`).
- `guardar.php` — valida token, guarda JSON en un directorio privado fuera del webroot y envía correo al confirmar.
- `config.php` — se crea en el servidor con token aleatorio (no versionado).

Claude recupera las respuestas leyendo `briefing-latest.json` (vía el canal de operación remota) o por el correo de confirmación.
