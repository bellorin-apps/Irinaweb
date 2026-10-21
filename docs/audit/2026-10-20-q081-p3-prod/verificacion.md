# P3 de Q-081 · despliegue y verificación en producción · 2026-10-20 (sesión local)

**GO:** Codex, f2e0603 (`docs/REVISION_Q081_P3_2026-10-20.md`), con sus ajustes:
- ocultación inmediata al pasar a espera;
- recálculo al cerrar el menú (MutationObserver de clases del body);
- pruebas que exigen visibilidad real.

**Entrega:**
- Rama `ccr-6254502d-xly8ww`, árbol en **f2e0603** con `wp-content` limpio.
- Delta frente a producción (4dcdc13): solo `assets/js/main.js` y `assets/css/components.css` del tema.
- El md5 del servidor coincide con f2e0603 en `main.js`, `components.css`, `i18n.php` y `Graph.php` (`md5-servidor.txt` y `md5-f2e0603.txt`).

**Ejecución:**
- `deploy-code.sh` con respaldo `~/deploy-backups/20261020-183957/` (464 K, 30 tablas, comprobado, más el plugin y el tema anteriores).
- `wp litespeed-purge all`.

**No se hizo:** manipulación temporal de archivos, `setup-en`/`publish-en`, cambios de contenido, medios, aprobaciones o estados, ni formularios.

## Verificación

| # | Comprobación | Resultado |
|---|---|---|
| 1 | Matriz de 9 páginas × 7 tamaños (`matriz.log`, `solape-cta.cjs` actualizado por Codex, en producción) | **0 fallos de 63**: sin solape visible con los botones del hero ni con la barra, al cargar y tras desplazar |
| 2 | Espera invisible, sin clics ni foco (`transiciones.log`) | En colisión: `.is-waiting`, `visibility: hidden`, `pointer-events: none`; el punto central no llega al aviso y Tab nunca entra en él. Aparece al despejarse. |
| 3 | Transiciones reales (`transiciones-prod.cjs`) | **9/9**: carga y fuentes; scroll de ida (visible) y vuelta (espera al instante); giro a 844×390 y scroll; vuelta a vertical; menú abierto (oculto) → resize a 414×896 → cerrar, sin reaparecer sobre el CTA. |
| 4 | Regresión del aviso (`regresion.log`) | `hint-test-local.cjs` (versión de Codex) con base en producción: **37/37**. `eleccion-es-prod.cjs`: EN→ES OK (`chosen`, aviso oculto). |
| 5 | LiteSpeed (`assets.log`) | Tras la purga, el HTML apunta a los nuevos `main.js` y `components.css` (`?ver=1792542010`); los archivos servidos contienen `is-waiting` y `MutationObserver`. `/` y `/primera-consulta/` dan hit con **el mismo HTML** para en-US y es-MX. |
| 6 | `seo_check --prod` y las 16 EN | Salida 0, sitemap 30. Las 16 EN idénticas antes y después (`estado-antes.txt` y `estado-despues.txt`). |
| — | Regresión general | 6 páginas ES y EN sin imágenes rotas; menús correctos; consola sin errores; Doctoralia apagado; sin `error_log`. |

Capturas en `capturas/` (selección): 390×844 y 844×390 al cargar y tras desplazar; 1440×900; y 4 transiciones.

**Resultado: P3 de Q-081 corregida y VERIFIED en producción.**

**Límites:**
- Idioma simulado con `navigator.languages`; Chrome sin dispositivos físicos ni Safari o Firefox.
- La matriz cubre 9 páginas representativas.

**Fuera de alcance, sin cambios:** en horizontal, el botón ancho del hero pasa por debajo de la columna lateral de la barra móvil.
