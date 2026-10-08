# AUDITORÍA DEL WORDPRESS — drairinagonzalez.com

Inventario de producción: `docs/audit/inventory-20261007-174227.txt` (solo lectura, ejecutado por la sesión local "Irinaweb" el 2026-10-07 por SSH + WP-CLI). Backup completo descargado el mismo día a las 16:41 antes de cualquier cambio.

## 1. Infraestructura

| Área | Estado |
|---|---|
| Servidor | Hostinger compartido, LiteSpeed + hCDN; cuenta `u855694717` |
| Webroot | `/home/u855694717/domains/drairinagonzalez.com/public_html` |
| WordPress | 7.1.3 |
| PHP | Web 8.2.33; CLI por defecto 8.0.30; 8.4.23 disponible. **Decisión D-025:** subir a 8.4 en hPanel (Avanzado → Configuración PHP) y verificar /op |
| WP-CLI | 2.12.0 en `/usr/local/bin/wp` |
| home / siteurl | `https://drairinagonzalez.com` (sin www); `www` → 301 al apex |
| SSL | Activo para el apex; `otorrino-monterrey.com` sin certificado (aparcado, redirige por http) |
| Dominio alterno | `otorrino-monterrey.com` aparcado + redirección 301 en hPanel; verificada por el propietario el 2026-10-07 |
| wp-config | `WP_CACHE` true, `WP_DEBUG` false; **faltan** `DISALLOW_FILE_EDIT` y `FORCE_SSL_ADMIN` |
| .htaccess | Bloque LiteSpeed + WordPress estándar; existen `.htaccess.bk` y carpeta `.private` (investigar y limpiar) |
| Indexación | `blog_public = 1` (indexable); robots.txt generado por WP; `sitemap_index.xml` de Rank Math responde 200 |
| Zona horaria | `timezone_string` vacío → fijar `America/Monterrey` |
| Permalinks | `/%postname%/` ✓ |

## 2. Clasificación KEEP / REPLACE / REMOVE / INVESTIGATE

### Temas
| Tema | Versión | Decisión | Nota |
|---|---|---|---|
| Astra (activo, único) | 4.14.0 | REPLACE | Por Hello Elementor + child `irina-gonzalez` (D-003). Se borra una vez activo Hello |

### Plugins
| Plugin | Versión | Decisión | Nota |
|---|---|---|---|
| sensia | 0.2.6 | **KEEP — NO TOCAR** | App de la Dra. en `/op`; tabla y `uploads/sensia-privado/` |
| elementor | 4.3.4 | KEEP | Añadir Elementor Pro (licencia del propietario) |
| litespeed-cache | 7.9.1 | KEEP | Servidor LiteSpeed; reconfigurar en Fase 10 |
| seo-by-rank-math | 1.0.280 | KEEP | SEO único (PLUGINS.md); desactivar tipos de schema que duplica el core (D-015) |
| google-site-kit | 1.189.0 | REMOVE | Decisión del propietario (D-024); desconectar en Google antes de borrar |
| elementor-safe-mode (MU) | 1.0.0 | REMOVE | Residuo del modo seguro de Elementor |
| hostinger-preview-domain (MU) | 1.4.0 | KEEP | Gestionado por Hostinger |
| hostinger-auto-updates (MU) | 1.0.8 | KEEP | Gestionado por Hostinger; revisar política de auto-updates |

### Contenido
| Elemento | Decisión |
|---|---|
| Página 106 "Inicio" (portada estática, Astra/Elementor) | REMOVE (se reconstruye) |
| Página 143 "Links" | KEEP (se usa en Instagram/TikTok); rehacer con el nuevo diseño en `/links/`, noindex (D-023) |
| Página 3 "Privacy Policy" (borrador) | REMOVE (se redacta aviso de privacidad mexicano, Fase 8) |
| Entradas, menús | Ninguno |
| Medios | Pendiente listar; conservar solo logo/activos de marca si existen; nunca `sensia-privado` |

### Usuarios
| Usuario | Rol | Decisión |
|---|---|---|
| ID 1 (propietario) | administrator | KEEP; activar 2FA |
| ID 2 `irina` | subscriber | KEEP; pasará a rol `revisor_medico` cuando se active `dra-irina-core` |

### Otros hallazgos
- Reescrituras `oauth/*` y `.well-known/oauth-*`: **origen identificado** (sesión local, 2026-10-08): servidor MCP/OAuth embebido en Rank Math 1.0.280 (`vendor/wp-media/mcp-oauth`). Decisión: KEEP por ahora (viene con el plugin SEO elegido); Codex lo revisa en la auditoría de seguridad (Fase 10) y se desactiva si Rank Math ofrece ajuste y no se usa.
- Cron: Action Scheduler, LiteSpeed, Rank Math, Site Kit email reporting, elementor tracker, Astra partner weekly. Los de Astra y Site Kit desaparecen al eliminarlos.

## 3. Ejecutado por REST el 2026-10-07

- Zona horaria `America/Monterrey`, formato de fecha `j de F de Y`, hora `H:i`.
- Página 3 "Privacy Policy" (borrador) a papelera.
- Google Site Kit desactivado y eliminado.
- `/op` verificado después de cada cambio (302 a `/op/entrar/`).
- Pendiente: página 106 "Inicio" (Astra) se conserva como portada provisional hasta que exista la nueva Home.

## 3b. Ejecutado por la sesión local (SSH + WP-CLI) el 2026-10-08

- Idioma `es_MX` activado.
- Hello Elementor 3.5.1 instalado; Astra eliminado.
- `elementor-safe-mode.php` (MU) apartado a `~/sensia-backups/removed-2026-10-08/`.
- `wp-config.php`: `DISALLOW_FILE_EDIT` y `FORCE_SSL_ADMIN` (copia `.bak-2026-10-08`).
- Desplegados y activados `dra-irina-core` 0.1.0 y tema `irina-gonzalez` 0.1.0 (child de Hello); `irina` → rol `revisor_medico`.
- PHP del sitio a 8.4 (propietario, hPanel). Verificado: /op 302, home 200, wp-admin 302, REST OK, sin avisos PHP en el HTML.
- Caché LiteSpeed purgada.

## 4. Orden de limpieza restante (por REST desde cloud, con verificación de `/op` tras cada bloque)

1. Datos del consultorio en Ajustes → Consultorio (REST) con lo confirmado por el propietario.
2. Rank Math: título/descripción de la home, desactivar schema `Article` en páginas, sitemap sin tipos innecesarios, Open Graph.
3. LiteSpeed Cache: configuración base (Fase 10).
4. Elementor Pro: subir zip (propietario, una vez) y activar licencia.
5. 2FA para el administrador.
6. Página 106 "Inicio" se sustituye cuando exista la nueva Home (Fase 6).
