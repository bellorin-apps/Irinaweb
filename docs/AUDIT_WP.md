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
| Dominio alterno | `otorrino-monterrey.com` aparcado + redirección 301 en hPanel (pendiente verificar tras propagación/SSL) |
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
- Reescrituras `oauth/*` y `.well-known/oauth-*` con `mcp_oauth_endpoint`: INVESTIGATE (origen probable: servidor MCP de WordPress core 7.x o un plugin). Verificar y, si no se usa, desactivar.
- Cron: Action Scheduler, LiteSpeed, Rank Math, Site Kit email reporting, elementor tracker, Astra partner weekly. Los de Astra y Site Kit desaparecen al eliminarlos.

## 3. Orden de limpieza (por REST desde cloud, con verificación de `/op` tras cada bloque)

1. Ajustes: zona horaria `America/Monterrey`, idioma `es_MX`, formato de fecha, título y descripción del sitio.
2. Contenido: borrar páginas 106 y 3; conservar 143 "Links"; vaciar papelera.
3. Tema: instalar Hello Elementor (wp-admin o WP-CLI desde la sesión local), activar, borrar Astra.
4. Plugins: borrar `elementor-safe-mode` y `google-site-kit`.
5. Hardening: `DISALLOW_FILE_EDIT`, `FORCE_SSL_ADMIN` (sesión local, edita wp-config), 2FA.
6. Despliegue de `dra-irina-core` y `irina-gonzalez` (sesión local, `tools/deploy/deploy-code.sh`), activación y asignación de rol a `irina`.
