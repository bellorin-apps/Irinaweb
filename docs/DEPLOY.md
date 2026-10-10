# DEPLOY — Canal de despliegue y operación

Actualizado: 2026-10-10. Sin credenciales en este archivo (la clave SSH vive solo en la PC del propietario y en hPanel).

## Servidor (Hostinger, cuenta compartida con Bankoin / Sensia)

| Dato | Valor | Fuente |
|---|---|---|
| Host SSH | 89.117.7.12 · puerto 65002 · usuario `u855694717` | Sesión local "Membretador" |
| Autenticación | Clave OpenSSH del alias `gibelab` en `~/.ssh/config` de la PC del propietario (misma cuenta `u855694717`); la ruta vive solo en `tools/deploy/env.sh` (no versionado) | Sesión local Irinaweb |
| Raíz del dominio | `/home/u855694717/domains/drairinagonzalez.com` (confirmado por Membretador con `ls ~/domains` el 2026-10-07; la carpeta `otorrino-monterrey.com` ya no existe) | Membretador |
| Webroot WordPress | `/home/u855694717/domains/drairinagonzalez.com/public_html` (dominio principal: drairinagonzalez.com; `otorrino-monterrey.com` aparcado con 301 a https://drairinagonzalez.com) | hPanel 2026-10-07 |
| PHP web | `/opt/alt/php84/usr/bin/php` (8.4.23) | Membretador |
| Servidor | LiteSpeed (cabecera X-LiteSpeed-Cache-Control); Hostinger sobrescribe Content-Security-Policy | Membretador |
| WP-CLI | 2.12.0 en `/usr/local/bin/wp`. **Receta que funciona en este host:** `/opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor <cmd>` (el php por defecto de la shell es 8.0 y Elementor 4.3.4 se carga dos veces en CLI al activar plugins) | Sesión local 2026-10-08 |
| Convivencia | Plugin `sensia` (ruta `/op`, `/op/*`) y carpeta privada `wp-content/uploads/sensia-privado/` (documentos de pacientes): **no tocar** | Membretador |
| Respaldos en servidor | `wp-config.php.bak-2026-10-08` (webroot); `~/sensia-backups/removed-2026-10-08/elementor-safe-mode.php`; Hostinger backup manual 2026-10-07 16:41 | Sesión local |

✅ Q-004 resuelto en hPanel el 2026-10-07: dominio principal `drairinagonzalez.com`; `otorrino-monterrey.com` aparcado y redirigido 301 a `https://drairinagonzalez.com`. Canónico sin www (D-022); HTTPS del dominio antiguo apex/www y 301 por ruta verificados el 2026-10-10.

## Por qué no se despliega desde la sesión cloud

El proxy de red del entorno cloud solo tuneliza TLS. Un túnel CONNECT a un puerto SSH se abre pero se cierra tras el intercambio de saludos (probado contra github.com:22 y registrado por el propio proxy). Por tanto SSH, SCP y SFTP solo funcionan desde una sesión local en la PC del propietario, que ya tiene la clave.

## Flujo acordado

```
Claude cloud (este repo) ──push──▶ GitHub ──pull──▶ Sesión local (PC de José) ──ssh/scp──▶ Hostinger
                                                       └── tools/deploy/*.sh (idempotentes)
```

Operación de WordPress desde cloud: secreto de red Basic (contraseña de aplicación) inyectado por el proxy del entorno hacia `drairinagonzalez.com/wp-json/`. Además de la REST nativa, el plugin expone `dra-irina/v1/ops/*` solo para administradores: `option/{name}` (GET/POST, con `merge`), `flush-rewrite`, `purge-cache`, `info`. Opciones críticas (siteurl, home, active_plugins, template…) están vetadas.

## Scripts (`tools/deploy/`)

- `env.example.sh` → copiar a `env.sh` (ignorado por Git) con host, puerto, usuario, ruta de clave y webroot.
- `deploy-briefing.sh` → sube `/briefing/`, genera token y `config.php`, crea carpeta privada, verifica con curl e imprime la URL con token.
- `inventory.sh` → inventario de solo lectura del WordPress (WP-CLI si existe; si no, PHP + lectura de ficheros) y lo guarda en `docs/audit/inventory-<fecha>.txt`.
- `deploy-code.sh` → sincroniza `wp-content/plugins/dra-irina-core` y `wp-content/themes/irina-gonzalez` por `rsync`/`tar` sin tocar nada más.

Todos leen `env.sh`, hacen `set -euo pipefail`, no borran nada fuera de sus carpetas y crean respaldo `.bak-<fecha>` antes de sobrescribir.

## Entrega Codex 2026-10-10

Orden y gates de esta entrega en `BATON.md`; no desplegar desde la auditoría ni publicar fichas. Los JSON llevan indentación estable, compatible con `wp_slash`. `upload-media.sh` conserva el adjunto anterior con slug de archivo e importa primero; resolver siempre por slug estable, nunca por nombre de archivo versionado. Desplegar medios/mini apps solo mediante sus scripts. El briefing tiene respaldo privado fuera del webroot antes de sobrescribirlo; nunca mostrar token/respuestas en logs.

## SMTP por constantes, sin valores en documentación

Configuración manual únicamente por la sesión local en wp-config.php del servidor, antes del comentario de cierre, con respaldo fuera del webroot. No copiar ese archivo al repositorio ni mostrar constantes resueltas en chats/logs. No modificarlo por esta auditoría.

| Constante | Uso |
|---|---|
| DI_SMTP_HOST | Servidor SMTP confirmado por proveedor |
| DI_SMTP_PORT | Puerto correspondiente a SSL/TLS |
| DI_SMTP_USER / DI_SMTP_PASS | Cuenta y contraseña de aplicación, privadas |
| DI_SMTP_SECURE | ssl o tls según proveedor |
| DI_SMTP_FROM / DI_SMTP_FROM_NAME | Remitente autorizado y nombre del consultorio |

Contacto y copia Bcc se configuran en PracticeSettings; Bcc no es público. Sin constantes SMTP el core utiliza wp_mail normal. La sesión local comprueba HTML/texto y recepción solo con autorización para los correos reales. La línea en blanco adicional antes del cierre es inocua y no requiere cambio.

No todos los scripts antiguos respaldaban cada operación: deploy-code respalda tema/core; deploy-briefing ahora respalda su árbol; upload-media conserva la versión anterior. Probar idempotencia en copia/staging antes de cerrar el gate. No ejecutar go-live ni publicación médica por una recarga de JSON.
