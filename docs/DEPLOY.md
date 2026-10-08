# DEPLOY — Canal de despliegue y operación

Actualizado: 2026-10-07. Sin credenciales en este archivo (la clave SSH vive solo en la PC del propietario y en hPanel).

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

✅ Q-004 resuelto en hPanel el 2026-10-07: dominio principal `drairinagonzalez.com`; `otorrino-monterrey.com` aparcado y redirigido 301 a `https://drairinagonzalez.com`. Pendiente: canónico con `www` (home/siteurl) y SSL para www.

## Por qué no se despliega desde la sesión cloud

El proxy de red del entorno cloud solo tuneliza TLS. Un túnel CONNECT a un puerto SSH se abre pero se cierra tras el intercambio de saludos (probado contra github.com:22 y registrado por el propio proxy). Por tanto SSH, SCP y SFTP solo funcionan desde una sesión local en la PC del propietario, que ya tiene la clave.

## Flujo acordado

```
Claude cloud (este repo) ──push──▶ GitHub ──pull──▶ Sesión local (PC de José) ──ssh/scp──▶ Hostinger
                                                       └── tools/deploy/*.sh (idempotentes)
```

Operación de WordPress desde cloud (lectura/escritura de contenido y ajustes) queda pendiente de una contraseña de aplicación en secretos del entorno (D-018).

## Scripts (`tools/deploy/`)

- `env.example.sh` → copiar a `env.sh` (ignorado por Git) con host, puerto, usuario, ruta de clave y webroot.
- `deploy-briefing.sh` → sube `/briefing/`, genera token y `config.php`, crea carpeta privada, verifica con curl e imprime la URL con token.
- `inventory.sh` → inventario de solo lectura del WordPress (WP-CLI si existe; si no, PHP + lectura de ficheros) y lo guarda en `docs/audit/inventory-<fecha>.txt`.
- `deploy-code.sh` → sincroniza `wp-content/plugins/dra-irina-core` y `wp-content/themes/irina-gonzalez` por `rsync`/`tar` sin tocar nada más.

Todos leen `env.sh`, hacen `set -euo pipefail`, no borran nada fuera de sus carpetas y crean respaldo `.bak-<fecha>` antes de sobrescribir.
