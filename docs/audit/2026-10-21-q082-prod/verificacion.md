# Q-082 · cédulas solo en Conóceme · despliegue dirigido y verificación en producción · 2026-10-21 (sesión local)

**GO:** Codex, b968f8f (`docs/REVISION_Q082_2026-10-21.md`): producto 5f1d268 con el plan dirigido de da392a3. Antes del despliegue, el comprobador endurecido dio **30/30** en la copia local (`local-endurecido.log`).

## Ejecución

1. **`deploy-code.sh`** con el árbol en b968f8f (producto = 5f1d268).
   - Respaldo `~/deploy-backups/20261021-152759/`: `db.sql.gz` (472 302 bytes, 30 tablas, comprobado), más `dra-irina-core` e `irina-gonzalez` anteriores.
   - El md5 del servidor coincide con 5f1d268 en `template-tags.php`, `footer.php`, `credenciales.php`, `PracticeSettings.php` y `components.css` (`ajustes-y-md5.txt` y `md5-5f1d268.txt`).
2. **Ajustes** (`aplicacion.log`):
   - Comprobado antes: `cedula_medicina` = 12438166, `cedula_especialidad` = 15111342 y `publicar_cedulas` = 1.
   - Guardados **solo** `cedula_med_inst` = «UNEFM – UDEM» y `cedula_esp_inst` = «Universidad Centroccidental Lisandro Alvarado».
   - Se pasa de 46 a 48 claves. La huella del resto de ajustes es la misma antes y después: `7c208e9c1b4c`.
3. **Precondición de 176 y 390:** slug, padre, idioma (`Language::of`: es y en), estado `publish` y contenido igual al build de d141669.
   - **Primera ejecución detenida sin cambios:** la comparación byte a byte falló.
   - **Conciliación:** la única diferencia eran los finales de línea. Producción guarda CRLF, porque se cargó desde Windows con `setup-pages.sh`, y el blob de git usa LF. Sin contar los CRLF, el texto es idéntico. Se repitió con esa única tolerancia.
4. **Respaldo exacto** de `post_content` en el mismo respaldo (`q082-176-post_content.html`, 3015 bytes; `q082-390-post_content.html`, 3009 bytes). **Actualización exclusiva de `post_content`:**
   - 176 ← `tools/content/pages/aviso-medico.html`;
   - 390 ← `tools/content/en/medical-disclaimer.html`;
   - en ambas: copia de trabajo con CRLF, como la carga original.

   Resultado:
   - siguen `publish`;
   - el contenido coincide con el build nuevo;
   - **metadatos idénticos** antes y después (sin plantilla ni Elementor; sin aprobaciones en estas páginas legales).

   **Cambios automáticos de WordPress:** `post_modified` pasa a 2026-10-21 15:29:58 y se crea una revisión nueva (176: 4 → 5; 390: 1 → 2).
5. **`wp litespeed-purge all`.**

**No se ejecutaron** `setup-pages.sh`, `setup-en` ni `publish-en`. Las páginas About no se tocaron (las cédulas salen de código y ajustes). No se probaron formularios.

## Verificación

| Comprobación | Resultado |
|---|---|
| `cedulas_check.py` endurecido en producción (`cedulas.log`) | **30/30** |
| URLs normales sin parámetros, segunda petición (`urls-normales.log` y `urls-normales-2.log`) | **30/30** en las dos pasadas: cédulas solo en About ES y EN, pie solo con el Consejo. Caché `hit` en 22/30 y 26/30, con `miss` intermitentes tras la purga; el contenido es correcto en todos los casos. |
| `seo_check --prod` (`regresion.log`) | Sin fallos, sitemap 30 |
| Aviso de idioma | 37/37, transiciones 9/9 y EN→ES OK |
| Barra horizontal en About ES y EN | 14/14 |
| Revisión visual (`capturas/`) | About ES y EN a 1440 y 390: «Céd. Prof. 12438166 · UNEFM – UDEM», «Céd. Esp. 15111342 · Universidad Centroccidental Lisandro Alvarado» y equivalentes en inglés. Pie de `/` y `/en/` solo con el Consejo y la leyenda COFEPRIS. Avisos médicos con el enlace «Conóceme» y «About». Consola sin errores. |
| Estados (`estado-antes.txt` y `estado-despues.txt`) | 15 de las 16 EN **idénticas**. La **390** cambia solo su contenido (la remisión) y la fecha. En ES solo cambia la **176**. Aprobaciones y estados intactos. Sin `error_log`. Doctoralia apagado. |

**Resultado: Q-082 VERIFIED en producción. La acción pedida por el abogado (D-107) está hecha, así que 8.6 queda lista para cerrarse con la ratificación de Codex.**

**Límites:**
- Chrome con tamaños emulados.
- Los `miss` de caché tras la purga son intermitentes y no afectan al contenido.
