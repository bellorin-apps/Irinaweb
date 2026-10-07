# RUNBOOK — Reinicio desde cero EN SITIO, conservando Sensia en /op

Fecha: 2026-10-07 · Decisión D-021 (revisada: Sensia permanece en `drairinagonzalez.com/op`).

## Situación de partida

- Un solo WordPress en `domains/otorrino-monterrey.com/public_html`, `home = https://drairinagonzalez.com` (sin www). Sirve el sitio público "en construcción" y Sensia en `/op`.
- `drairinagonzalez.com` está en la cuenta como dominio apuntado a ese sitio.
- Sensia es autónoma (Membretador): URLs por `home_url()`, sin URLs absolutas, sin dependencias de plugins ni tema. **Conservar**: core de WordPress, plugin `sensia` (activo), tabla `{prefijo}sensia_documentos`, `wp-content/uploads/sensia-privado/` con su `.htaccess`, usuarios con capacidad `sensia_convertir` y administradores, acceso SSH de la cuenta.

## Orden de ejecución

| # | Paso | Quién | Verificación |
|---|---|---|---|
| 1 | Backup manual en hPanel (Archivos → Copias de seguridad → "Respaldo del sitio web" de hoy → Descargar) | José | Archivo en la PC |
| 2 | Contraseña de aplicación `claude-ops` en wp-admin → Usuarios → perfil; secretos del entorno `IRINA_WP_URL`, `IRINA_WP_USER`, `IRINA_WP_APP_PASSWORD`; nivel de red amplio en el entorno | José | Claude lee `/wp-json/wp/v2/users/me` |
| 3 | Inventario por REST: plugins, temas, páginas, entradas, medios, usuarios (sin emails), ajustes. Clasificación KEEP (Sensia) / REMOVE (resto) en `docs/AUDIT_WP.md` | Claude | Tabla publicada en el repo; José la aprueba |
| 4 | Limpieza por REST: borrar páginas, entradas, comentarios y medios **excepto** `uploads/sensia-privado/`; desactivar y borrar plugins excepto `sensia`; vaciar menús y widgets | Claude | `/op/` sigue respondiendo 302 a `/op/entrar/`; la Dra. entra, "Ver" un documento, descarga "Con membrete" |
| 5 | Temas: instalar Hello Elementor y borrar los demás (wp-admin → Apariencia → Temas, 1 minuto) o por WP-CLI desde la PC | José / Membretador | Hello activo |
| 6 | Plugins aprobados (PLUGINS.md): instalación desde el directorio por REST; Elementor Pro por subida de zip en wp-admin | Claude / José | Lista de plugins = Sensia + aprobados |
| 7 | Dominio canónico: wp-admin → Ajustes → Generales → ambas direcciones a `https://www.drairinagonzalez.com`; SSL para www activo en hPanel → Seguridad → SSL; "Forzar HTTPS" | José | `/op/` responde en `https://www.drairinagonzalez.com/op/`; la Dra. inicia sesión una vez |
| 8 | `otorrino-monterrey.com` y `www.otorrino-monterrey.com` → 301 al canónico (hPanel → Dominios → Redirecciones, o regla por host en `.htaccess`); `drairinagonzalez.com` sin www → 301 a www (lo hace WordPress al tener home con www) | José / Claude | `curl -I https://otorrino-monterrey.com/` → 301 |
| 9 | Membretador: ajustar `URL_APP` de su deploy a `https://www.drairinagonzalez.com/op/` y verificar /op | Membretador | — |
| 10 | Despliegue de `dra-irina-core` y tema `irina-gonzalez` (Git de Hostinger o `tools/deploy/deploy-code.sh` desde la PC); briefing en `/briefing/`; Search Console propiedad `www` + sitemap | Claude / José (1 vez) | Checklist Fase 1 |

## Qué NO hacer

- No borrar ni reinstalar el sitio `otorrino-monterrey.com` en hPanel: es el WordPress que contiene Sensia.
- No "Cambiar dominio" ni "Borrar" en hPanel para este sitio.
- No tocar `wp-content/uploads/sensia-privado/` ni el plugin `sensia`.

## Rollback

- Cualquier paso se revierte restaurando el "Respaldo del sitio web" de hoy desde hPanel (Restaurar y descargar → Restaurar sitio web).
- Paso 7 se revierte editando los dos campos de Ajustes → Generales.
