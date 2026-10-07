# DEPLOY — Canal de despliegue y operación

Actualizado: 2026-10-07. Sin credenciales en este archivo (la clave SSH vive solo en la PC del propietario y en hPanel).

## Servidor (Hostinger, cuenta compartida con Bankoin / Sensia)

| Dato | Valor | Fuente |
|---|---|---|
| Host SSH | 89.117.7.12 · puerto 65002 · usuario `u855694717` | Sesión local "Membretador" |
| Autenticación | Clave OpenSSH (en la PC del propietario); alternativa: añadir clave pública en hPanel → Avanzado → Acceso SSH | Membretador |
| Raíz del dominio | `/home/u855694717/domains/otorrino-monterrey.com` | Membretador |
| Webroot WordPress | `/home/u855694717/domains/otorrino-monterrey.com/public_html` — **drairinagonzalez.com se sirve desde este webroot** | Membretador |
| PHP web | `/opt/alt/php84/usr/bin/php` (8.4.23) | Membretador |
| Servidor | LiteSpeed (cabecera X-LiteSpeed-Cache-Control); Hostinger sobrescribe Content-Security-Policy | Membretador |
| WP-CLI | No verificado | — |
| Convivencia | Plugin `sensia` (ruta `/op`, `/op/*`) y carpeta privada `wp-content/uploads/sensia-privado/` (documentos de pacientes): **no tocar** | Membretador |

⚠️ Hallazgo SEO (Q-004): el WordPress vive bajo el dominio `otorrino-monterrey.com` y `drairinagonzalez.com` apunta al mismo webroot. Hay que verificar `home`/`siteurl`, qué host responde a cada dominio y fijar un único canónico con 301 (candidato: `www.drairinagonzalez.com`, marca; `otorrino-monterrey.com` es exact-match y puede redirigir). Probable causa de la alerta "canónica diferente" en Search Console.

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
