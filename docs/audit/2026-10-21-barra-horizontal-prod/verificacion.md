# Barra móvil en horizontal · despliegue y verificación en producción · 2026-10-21 (sesión local)

**GO:** Codex, c61ac01 (`docs/REVISION_BARRA_HORIZONTAL_2026-10-21.md`), con sus herramientas endurecidas:
- cualquier control del contenido bajo la columna cuenta como fallo;
- se exigen 3 acciones;
- un punto fuera de pantalla no cuenta como alcanzable.

**Entrega:**
- Rama `ccr-6254502d-xly8ww`, árbol en **c61ac01** con `wp-content` limpio.
- Delta frente a producción (f2e0603): solo 5 líneas de `assets/css/components.css`.
- El md5 del servidor coincide con c61ac01 en `components.css`, `main.js`, `i18n.php` y `Graph.php`. El `components.css` servido coincide con el del repositorio (`9abf405491d8`).

**Ejecución:**
- `deploy-code.sh` con respaldo `~/deploy-backups/20261021-083841/` (464 K, 30 tablas, comprobado, más el plugin y el tema anteriores).
- `wp litespeed-purge all`.

**No se hizo:** manipulación temporal, `setup-en`/`publish-en`, cambios de contenido, medios, aprobaciones o estados, ni formularios.

## Verificación

| # | Comprobación | Resultado |
|---|---|---|
| 1 | Matriz de 30 rutas ES/EN × 7 tamaños, con desplazamiento completo y herramienta endurecida (`matriz.log`) | **0 fallos de 210**: ningún control bajo la columna, la barra siempre con 3 botones 46×46 y sin desborde |
| 2 | Interacción (`interaccion.log`, `interaccion.cjs` endurecido) | **132/132**: CTA pulsable en el centro y en los puntos medios de los bordes, 3 acciones 46×46 accesibles y sin taparse, menú, giro, texto largo, es-MX y en-US |
| 3 y 4 | Alcance (`alcance.log`, `alcance-reserva.cjs`) | **7/7**. La reserva solo se aplica con la columna (667×375, 740×360 y 844×390): el contenido del hero y del pie termina a 85–90 px del borde, frente a los 27–32 px de la cabecera, que no se mueve. En 932×430, 375×667, 390×844 y 1440×900 no hay reserva. El fondo del hero sigue a todo el ancho (0/0 px); sin desborde ni errores JS. En el CSS servido, la regla aparece una sola vez y dentro de `@media (max-width: 899px) and (orientation: landscape) and (max-height: 500px)`. |
| 5 | Regresión de Q-081 en producción (`regresion-q081.log`) | `solape-cta` **63/63**, `hint-test` **37/37**, transiciones **9/9** y EN→ES OK |
| 6 | Caché, SEO y las 16 EN | `components.css?ver=1792562927` con la reserva; `/` y `/primera-consulta/` con hit y el mismo HTML para en-US y es-MX (`assets.log`). `seo_check --prod` sale con 0 (`seo.log`). Las 16 EN idénticas antes y después (`estado-antes.txt` y `estado-despues.txt`). |

Capturas (selección) en `interaccion/`: portada, Primera consulta y About EN a 844×390, menú cerrado y texto largo a 667×375, con fotos reales.

**Resultado: la mejora de la barra en horizontal está VERIFIED en producción.**

**Límites:**
- Chrome con tamaños emulados; sin dispositivos físicos, Safari ni Firefox.
- La interacción cubre 7 rutas representativas; la matriz cubre las 30.
