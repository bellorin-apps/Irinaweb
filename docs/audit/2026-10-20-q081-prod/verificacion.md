# Q-081 · aviso sugerido EN · despliegue y verificación en producción · 2026-10-20 (sesión local)

**GO:** Codex, 4dcdc13 (incluye su ajuste en `main.js`: recordar la elección ES desde EN).

**Entrega:**
- `deploy-code.sh` con el árbol en e129922. Su `wp-content` es idéntico al de 4dcdc13; e129922 solo añade documentación de Codex.
- Alcance frente a producción (1c685c4): `components.css`, `main.js` e `inc/i18n.php` del tema.
- El md5 del servidor coincide con 4dcdc13 en los 4 archivos (`md5-servidor.txt` y `md5-4dcdc13.txt`).

**Respaldo:** `~/deploy-backups/20261020-173714/` (464 K, 30 tablas, comprobado, más el plugin y el tema anteriores). Después se purgó LiteSpeed.

**No se hizo:** manipulación temporal de archivos, `setup-en`/`publish-en`, cambios de contenido, medios, aprobaciones o estados, ni formularios.

## Resultados

| Comprobación | Resultado |
|---|---|
| Contenedor por tipo de página (`publico.log`) | `/`, `/primera-consulta/`, `/sueno/`, `/sueno/ronquido/` y `/contacto/` → `data-en-url` de su pareja correcta. Sin contenedor en `/gracias/`, `/links/` (sin pareja) y las EN, incluida `/en/thank-you/`. |
| Caché | `/primera-consulta/` con `Accept-Language` en-US y es-MX (dos veces cada uno): **hit en las 4** y **el mismo HTML** (md5 igual). El aviso lo decide el navegador, no la caché. |
| Prueba de 37 casos en producción (`navegador.log`; `hint-test-local.cjs` con base en producción y `/links/` como ruta sin pareja) | **37/37**: idioma principal, texto y enlace, teclado, Escape, persistencia, `localStorage` bloqueado, selector, Gracias, sin pareja, EN, móvil, horizontal, escritorio y menú abierto. |
| Elección ES desde EN (ajuste de Codex; `tools/audit/q081/eleccion-es-prod.cjs`) | **OK**: navegador en inglés, de `/en/first-visit/` con «ES» → `/primera-consulta/` con `chosen` guardado y el aviso oculto. |
| `seo_check --prod` (`seo.log`) | **Salida 0**, sitemap 30. |
| 16 EN (`estado-antes.txt` y `estado-despues.txt`) | **Idénticas**: `publish`, fechas, aprobaciones y huella del contenido. |
| Regresión general | 6 páginas ES y EN sin imágenes rotas; menús correctos; consola sin errores; Doctoralia apagado; sin `error_log`. Capturas reales en 390×844, 844×390 y 1440×900. |
| P3 conocida | En móvil, el aviso tapa el borde inferior del botón WhatsApp del hero de Primera consulta. Se puede cerrar y la barra inferior queda libre. Sin cambios, como acordó Codex. |

**Resultado: Q-081 VERIFIED en producción.**

**Límites:**
- Las pruebas de navegador simulan el idioma con `navigator.languages`.
- No se probó en dispositivos físicos ni en Safari ni Firefox.
