# SECURITY — Dra. Irina González Sáez

Actualizado: 2026-10-14 · Claude cloud. Estado verificado en producción; las pruebas de visitante anónimo las hizo la sesión local desde la PC de José (ver «Límites»). PLAN 10.3.

## Medidas activas

| Área | Medida | Dónde | Evidencia |
|---|---|---|---|
| Cabeceras | HSTS 1 año, X-Frame-Options SAMEORIGIN, X-Content-Type-Options nosniff, Referrer-Policy strict-origin-when-cross-origin, Permissions-Policy (cámara, micro, geolocalización, pago, USB desactivados), CSP `upgrade-insecure-requests` | `dra-irina-core/src/Security/Headers.php` | Q-023; cabeceras leídas en vivo 2026-10-14 |
| Versiones | Sin meta generator (WordPress ni Elementor), sin X-Powered-By (también en caché de LiteSpeed), readme.html, license.txt y wp-config-sample.php en 403 | `Security/Hardening.php`, `tools/deploy/harden-htaccess.sh` | Q-054 |
| Enumeración de usuarios | Para visitantes: rutas `/wp/v2/users` retiradas de la API, páginas de autor y `?author=` en 404, oEmbed sin autor | `Security/Hardening.php` | Q-053 (anónimo desde la PC de José: users 404, users/me 404) |
| XML-RPC | No disponible (403) | Hosting / configuración | Leído en vivo 2026-10-14 |
| API REST privilegiada | settings, abilities, MCP y Elementor One exigen sesión o permisos (401/403 para anónimo) | Núcleo y plugins | Sesión local 2026-10-14 (Q-059 descartado) |
| Acceso | Plugin Two Factor 0.17 instalado (TOTP + códigos de respaldo); 2 usuarios: 1 administrador y 1 revisor_medico (la Dra., que no puede publicar) | wp-admin | BATON 2026-10-14 |
| Formulario | Datos mínimos y sin guardar en la base de datos (D-050); campo trampa; filtro de contenido SpamGuard con descarte silencioso; Cloudflare Turnstile (Managed); SMTP por constantes | `Contact/Form.php`, `Contact/SpamGuard.php` | Q-051/Q-052/Q-055, D-085 |
| Mini apps de la Dra. | `/briefing/` con token (config.php fuera del repo), noindex, `paginas.json` denegado, la app de revisión nunca publica | `tools/briefing/` | D-040, D-076 |
| Publicación clínica | Gate médico del plugin: nada clínico se publica sin aprobación de la revisora | `Workflow/MedicalReview.php` | D-078/D-082 |
| Secretos | `env.sh`, `config.php` y `*.local.txt` fuera de Git; las sesiones no los imprimen; claves de Turnstile cargadas sin mostrarlas | `.gitignore`, AGENTS.md | D-085 |
| Copias | Respaldo automático antes de cada despliegue (`~/deploy-backups/`), también del `.htaccess`; copias del hosting | `tools/deploy/*.sh` | BATON |

## Decisiones y riesgos aceptados

- **D-085:** la Secret Key de Turnstile quedó visible en una captura del chat; José decidió no rotarla. Se puede rotar en cualquier momento (Turnstile › widget › Rotate Secret Key).
- **Contraseña de aplicación «claude-ops» revocada (José, 2026-10-14):** era el canal REST de la sesión cloud (tarea 1.1) y hacía que sus pruebas se vieran como administrador (Q-059). El admin queda sin contraseñas de aplicación; lo que requiera permisos lo consulta la sesión local por SSH/WP-CLI. Comprobado desde la nube tras la revocación: users/me, settings, users y abilities sin acceso.
- **Actualizaciones automáticas de Hostinger** activas (p. ej. Elementor 4.3.4 → 4.3.5 el 2026-10-14): mantienen los parches al día; tras cada una conviene revisar el sitio.

## Pendiente

1. **José:** activar el 2FA en su usuario (Perfil › Opciones de dos factores: escanear el QR, escribir el código y pulsar Verify/Enviar, generar códigos de respaldo y «Actualizar perfil»). Dijo haberlo hecho el 2026-10-14, pero el servidor no tiene ningún dato de Two Factor en el usuario 1 (sesión local, WP-CLI de solo lectura): pendiente de completar y volver a comprobar. Recomendado también en Hostinger y Google.
2. Revisión de Codex (1.9, 5.7, 10.5) sobre este estado.

## Límites

- Hasta la revocación de «claude-ops» (2026-10-14), las peticiones REST desde la nube iban autenticadas; las pruebas anónimas anteriores a esa fecha valen solo si las hizo la sesión local.
- No se han hecho pruebas de penetración ni escaneo de dependencias de terceros; el formulario no se prueba en producción porque envía correos reales.
