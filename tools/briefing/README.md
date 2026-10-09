# Briefing de la Dra. (versión alojada)

Página autónoma (sin WordPress) para que la Dra. responda a un toque. Se despliega en `public_html/briefing/` del dominio.

- `index.html` — cuestionario; guarda en `guardar.php` con el token de la URL (`/briefing/?t=TOKEN`).
- `guardar.php` — valida token, guarda JSON en un directorio privado fuera del webroot y envía correo al confirmar.
- `config.php` — se crea en el servidor con token aleatorio (no versionado).

Claude recupera las respuestas leyendo `briefing-latest.json` (vía el canal de operación remota) o por el correo de confirmación.

## Mini app de revisión médica (`revision/`, D-040)

La Dra. revisa y aprueba las fichas clínicas sin entrar a wp-admin: `/briefing/revision/?t=TOKEN` (mismo token).

- `revision/index.html` — lista de fichas con progreso y filtros; cada ficha con secciones plegables editables (texto plano con párrafos; las listas, una línea por punto), autosave por campo, y botones **Aprobar ficha**, **Pedir cambios** (con nota) y **No publicar** (con nota).
- `revision/revisar.php` — carga WordPress (`wp-load.php`) y ejecuta como el usuario con rol `revisor_medico` (`wp_set_current_user`), así el gate del plugin y las capacidades son los reales. Acciones: `list`, `get`, `save` (post_content/título y metas `di_*` con `wp_kses_post` / `sanitize_text_field`), `state` (`approve` → `medically_approved` + `_di_approved_by/_at`; `changes` → `technical_review` + `_di_review_note`; `hold` → `draft` + nota). **Nunca publica**: las fichas publicadas son de solo lectura en la app (solo admiten «Pedir cambios»).
- Registro en `data_dir`: `revision-log.jsonl` (cada guardado y cambio de estado, con fecha, ficha, acción, nota y usuario) y `revision-latest.json` (resumen de estados). Sin correos (instrucción del propietario 2026-10-09).
- Requisito: un usuario con rol `revisor_medico` (`wp user list --role=revisor_medico`); opcional `'reviewer_login' => 'irina'` en `config.php`.

Las tres mini apps van siempre en versión clara (`data-theme="light"`, D-041).
