# RUNBOOK — Reinicio desde cero del sitio público, conservando Sensia

Fecha: 2026-10-07 · Decisión D-021. Fuente de los datos de Sensia: sesión local "Membretador".

## Situación de partida

- Un solo WordPress en `domains/otorrino-monterrey.com/public_html`, con `home = https://drairinagonzalez.com` (sin www). Sirve el sitio público "en construcción" y la app Sensia en `/op`.
- `drairinagonzalez.com` está en la cuenta como dominio apuntado a ese sitio (no es sitio independiente).
- Sensia es autónoma: construye URLs con `home_url()`, no guarda URLs absolutas, no depende de plugins ni del tema. Conservar: WordPress, plugin `sensia` activo, su tabla `{prefijo}sensia_documentos`, `wp-content/uploads/sensia-privado/` con su `.htaccess`, los usuarios con capacidad `sensia_convertir` y los administradores, el acceso SSH de la cuenta.

## Orden de ejecución (quién hace cada paso)

| # | Paso | Quién | Verificación |
|---|---|---|---|
| 1 | Backup en hPanel: Websites → otorrino-monterrey.com → Backups → generar y **descargar** archivos y base de datos | José | Archivo descargado |
| 2 | Respaldo adicional de la base (`wp db export`) y de `uploads/sensia-privado/` por SSH | Membretador (José autoriza allí) | Ficheros en `~/sensia-backups/` |
| 3 | Cambiar `home` y `siteurl` del WP viejo a `https://otorrino-monterrey.com` (wp-admin → Ajustes → Generales, los dos campos) | José (o Membretador por WP-CLI) | `https://otorrino-monterrey.com/op/` responde 302 a `/op/entrar/`; la Dra. entra, abre un documento ("Ver") y descarga "Con membrete" |
| 4 | Avisar a la Dra.: el enlace de Sensia pasa a ser `https://otorrino-monterrey.com/op/`; tendrá que iniciar sesión una vez | José | — |
| 5 | Membretador ajusta `URL_APP` de su `deploy.sh` al nuevo host | Membretador | — |
| 6 | hPanel → Websites → otorrino-monterrey.com → Manage → dominios del sitio: **quitar** `drairinagonzalez.com` | José | `drairinagonzalez.com` libre en Domains |
| 7 | hPanel → Websites → Add website → WordPress → dominio existente `drairinagonzalez.com` → usuario administrador propio con contraseña fuerte (no compartirla) → instalar. No instalar plugins, plantillas ni "AI builder" del asistente | José | Aparece como sitio independiente; carpeta `domains/drairinagonzalez.com/public_html` |
| 8 | hPanel → Security → SSL: certificado para `drairinagonzalez.com` y `www.drairinagonzalez.com`; activar "Forzar HTTPS" | José | `https://www.drairinagonzalez.com/` carga con candado |
| 9 | Sitio nuevo: Usuarios → perfil → Contraseñas de aplicación → `claude-ops`; guardar `IRINA_WP_URL=https://www.drairinagonzalez.com`, `IRINA_WP_USER`, `IRINA_WP_APP_PASSWORD` en secretos del entorno cloud; nivel de red amplio | José | Claude verifica `GET /wp-json/wp/v2/users/me` |
| 10 | WP viejo: `.htaccess` con el bloque de `tools/legacy/htaccess-otorrino-monterrey.txt` + Ajustes → Lectura → "Disuadir a los motores de búsqueda" | Membretador (SSH) o José (File Manager) | `curl -I https://otorrino-monterrey.com/` → 301 al canónico; `/op/` → 302 a `/op/entrar` |
| 11 | Sitio nuevo, por REST desde cloud: idioma, zona horaria, permalinks, `home/siteurl` con `www`, borrar contenido demo, instalar plugins aprobados (PLUGINS.md), desplegar briefing, Search Console con la propiedad `www` y sitemap | Claude | Checklist en `docs/AUDIT_WP.md` |
| 12 | Plugin `dra-irina-core` y tema `irina-gonzalez`: Git de Hostinger (ramas `deploy/*`) o `tools/deploy/deploy-code.sh` desde la PC | José (1 vez) / Claude | Activación por REST |

## Qué NO hacer

- No borrar ni reinstalar el sitio `otorrino-monterrey.com`: contiene Sensia y documentos de pacientes.
- No usar "Cambiar dominio principal" de Hostinger en el sitio viejo (movería la carpeta y rompería las rutas de Sensia).
- No quitar `drairinagonzalez.com` del sitio viejo antes del paso 3 (dejaría `/op` redirigiendo a un host sin Sensia).

## Rollback

- Pasos 3 y 10 se revierten editando los dos campos de Ajustes → Generales y quitando el bloque del `.htaccess`.
- Paso 6 se revierte volviendo a añadir el dominio al sitio viejo en hPanel.
- Paso 7: el sitio nuevo se puede eliminar sin afectar al viejo.
