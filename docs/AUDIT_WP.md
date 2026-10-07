# AUDITORÍA DEL WORDPRESS EXISTENTE — drairinagonzalez.com

Estado: **planificada, bloqueada por acceso** (B1, B2). Se ejecuta en Fase 1.

## 1. Hallazgos previos (sin acceso al sitio)

| Área | Hallazgo | Fuente |
|---|---|---|
| Dominio | drairinagonzalez.com, WordPress activo, título "Dra. Irina González Sáez" | Notificación WP 2026-10-06 |
| Usuarios | Admin con correo bellgiga@gmail.com; usuario `irina` creado 2026-10-06 (rol desconocido) | Notificación WP |
| Search Console | Propiedad verificada; sitemap enviado; páginas excluidas por `noindex` (16-sep-2026) y por canónica duplicada (06-sep-2026) | Correos sc-noreply |
| Indexación | Sin resultados visibles en búsqueda `site:`; GSC sí conoce URLs | Búsqueda |
| Hosting | Hostinger (probable) → esperable LiteSpeed + hPanel con staging y backups | Correo Hostinger |
| Red del entorno | El contenedor cloud no puede alcanzar el dominio (política de red) | Prueba curl/WebFetch |

## 2. Qué se revisará (inventario)

### Infraestructura
- Versión de WordPress, PHP, servidor web (LiteSpeed/Apache/Nginx), SSL y redirección http→https, www/no-www.
- Proveedor de hosting, plan, backups automáticos, staging disponible, CDN/Cloudflare.
- Cabeceras HTTP (seguridad, caché), tiempos de respuesta.

### Instalación
- Theme activo, themes instalados, child themes.
- Plugins activos/inactivos, MU plugins, versiones, mantenimiento y solapamientos.
- Usuarios, roles, capacidades, 2FA, intentos de login.
- Opciones clave: permalinks, lectura (`blog_public`/noindex), zona horaria, idioma, comentarios, pingbacks, XML-RPC, REST.
- Cron jobs, integraciones (SMTP, analytics, formularios, agenda).

### Contenido
- Páginas, entradas, CPT, medios, menús, widgets, plantillas Elementor, kits importados, contenido demo ("Hello world", "Sample page").
- Clasificación KEEP / REPLACE / REMOVE / INVESTIGATE.

### SEO y público
- robots.txt, sitemap(s), canonicals, metas noindex, titles, OG, schema existente, 404.
- Search Console: cobertura, URLs indexadas/excluidas, rendimiento.
- Mapa `OLD URL → NEW URL → 301` si existen URLs conocidas.

### Performance (baseline ANTES)
- Lighthouse móvil/desktop, CWV de laboratorio, peso de página, requests, DOM.

### Seguridad
- Versiones vulnerables, admin expuesto, permisos de archivos, `wp-config`, edición de archivos en admin, listado de directorios, usuarios "admin".

## 3. Método

1. Lectura: WPVibe (REST + WP-CLI emulado) y, si se obtiene, panel del hosting.
2. Público: curl/WebFetch (cuando la red lo permita), Lighthouse, validadores de schema.
3. Resultado en este archivo + clasificación en tabla.
4. Nada se borra en Fase 1: solo inventario, backup verificado y staging.

## 4. Resultados

_(Pendiente de acceso.)_
