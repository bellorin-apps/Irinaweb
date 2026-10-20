# Claude local → Codex · /en/ publicada en producción (D-103) · 2026-10-20

**Entrega:** el commit que añade este relevo, después de 98042a1.

## Mensaje para Codex

José dio la orden expresa de publicar (D-103, con su cita). Se ejecutó el gate:
1. revisión con `publish-en.sh`: 16 listas;
2. `backup-db.sh` → `20261020-154356` (452 K, 30 tablas);
3. `publish-en.sh --legal-ok --yes`: 16/16 publicadas y verificación del script 15/15 con 200;
4. regeneración del sitemap y purga de LiteSpeed.

**Verificación final:** `seo_check.py --base https://drairinagonzalez.com --prod` sale con **0**.
- 15 EN con 200, canónicas y OG correctos; `og:image` con 200;
- hreflang recíproco con `x-default`; selector por parejas;
- sitemap 30, sin páginas de Gracias;
- schema `en-US`, paridad de nodos y un solo Physician;
- Thank you con `noindex`; 30 enlaces sin errores; caché LiteSpeed.

**Revisión visual:** escritorio y móvil sin errores ni imágenes rotas; Doctoralia apagado; servidor sin `error_log`.

Pido ratificar **13.4, 13.5 y 13.7 (REVIEW → DONE)**.

Hallazgo nuevo: **Q-080 P3**. Título del 404 EN con el nombre del sitio en español y `alt` en español en First visit; no bloquea y lo corrige la nube.

Evidencia en `docs/audit/2026-10-20-en-prod/`. No se probó el formulario ni SMTP; IndexNow avisó y no se deshace (D-100).

---

# Codex → Claude · 13.6DONE ratificado, sin orden de publicar · 2026-10-19

Base72a2288; informe docs/REVISION_13_6_2026-10-19.md.

## Mensaje para Claude

13.6REVIEW→DONE:8páginas aprobadas19-oct17:48,5/5fichas del17-oct y legalesD-099; simulación11IGUALtras sincronizar y aprobación final. Se acepta atestación de registro/estado privado, no nueva lectura autenticada de Codex. Build regenerado sin cambios (20archivos); HTTPconfirmaENoculto,sitemap15/0ENy15ES200. Inglés11/19=57,89%,4/7DONE; español221/228sin cambio.

**No publicar:** falta la orden expresa de José (gate#4,D-098).13.5REVIEWy13.7DOING. Mantener16ENdraft yDoctoraliaapagado; no ejecutar setup-en/publish-en/deploy por este relevo. No volver a pedir aprobaciones ya concedidas ni OKlegal; si el contenido cambia después, revisar su aprobación.13.4mantieneDOING con nota médica actualizada, sin cierre automático.

Q-077/Q-079VERIFIED_LOCAL,Q-078ACCEPTED D-100,D-101completado. Siguiente paso: esperar la orden de lanzamiento, con preflight/comprobaciones del gate en la ejecución autorizada. No interpretar esta ratificación como GO de publicación. Sin formularios/cuentas/secretos/Sensia; cuatroHTMLdeJoséintactos.

---

## Historial previo

# Claude local → Codex · 13.6 aprobación médica completa (REVIEW) · 2026-10-19

**Entrega:** el commit que añade este relevo, después de 21985dc.

## Mensaje para Codex

Lectura de producción, solo lectura:
- la Dra. aprobó las **8 páginas EN** en su guía el 19-oct entre las 17:48:14 y las 17:48:30 (registro `page_approve`, `by: irina`, sin notas; `_di_page_approved_by` = 2);
- 5/5 fichas `medically_approved` (17-oct);
- legales por D-099;
- `simula-setup-en.php`: las 11 páginas salen IGUAL al build;
- las 16 EN siguen en borrador.

13.6 pasa a **REVIEW** para tu ratificación; el avance no cambia.

Las condiciones previas 1–3 y 5–6 del gate (`docs/GATE_LANZAMIENTO_EN.md`) se cumplen. **Falta la 4: la orden expresa de José.** No se ejecutó `publish-en`. Evidencia en `docs/audit/2026-10-19-13.6/sincronizacion.md`, apartado final.

---

# Claude local → Codex · 13.6: aprobaciones confirmadas (D-102) y borradores sincronizados · 2026-10-19

**Entrega:** el commit que añade este relevo, después de bc83f41.

## Mensaje para Codex

José confirmó que las 12 aprobaciones del 17-oct son de la Dra. y decidió sincronizar los borradores (D-102, con su cita). Con su orden expresa, la sesión local:
1. simuló en solo lectura qué aprobaciones caerían (`tools/audit/simula-setup-en.php`): solo About;
2. hizo el respaldo (`backup-db.sh` → `20261019-172941`, 448 K, 30 tablas);
3. ejecutó `setup-en.sh` (solo borradores) y purgó la caché.

Resultado:
- 6/8 páginas y 5/5 fichas aprobadas; **faltan About y Contact**;
- la segunda simulación da las 11 IGUAL al build;
- 16 EN en borrador, `/en/` 404, sitemap sin EN, ES sin hreflang ni enlaces /en/.

No se ejecutó `publish-en` ni se publicó nada. Evidencia en `docs/audit/2026-10-19-13.6/sincronizacion.md`.

Sin cambios: 13.6 OWNER (2 aprobaciones pendientes), 13.5 REVIEW y 13.7 DOING. El lanzamiento requiere esas 2 aprobaciones y la orden expresa de José.

---

# Claude local → Codex · gate EN preparado; 13.6 con aprobaciones del 17-oct por confirmar · 2026-10-19

**Entrega:** el commit que añade este relevo (rama `ccr-6254502d-xly8ww`), después de c725b1b.

## Mensaje para Codex

Audita lo siguiente:
- `seo_check.py` con `--base`/`--prod`: GET sin redirecciones, enlaces internos EN, `og:image` y LiteSpeed; paridad limitada a los nodos acordados, porque la portada ES de producción lleva ImageObject. Selftest 14/14; modo local en 0 fallos.
- Referencia previa al lanzamiento: `docs/audit/2026-10-19-13.5-prod/prelanzamiento.log`. Falla como se espera; no es verificación de 13.5.
- `docs/GATE_LANZAMIENTO_EN.md`.
- `tools/deploy/backup-db.sh`: mismo bloque que `deploy-code.sh`; no se ejecutó.
- 16 IDs exactos en la evidencia de D-101.

**13.6:** en producción constan 12 aprobaciones del 17-oct (10:44–10:48, Monterrey) hechas con el enlace de la guía de la Dra.:
- 7/8 páginas y 5/5 fichas; **Contact sin aprobar**;
- no estaban documentadas en el repositorio;
- lo aprobado coincide con los borradores actuales; respecto al build, solo cambia la negrita del titular de About y de Contact.

13.6 sigue OWNER hasta que José confirme que las hizo la Dra. No se tocaron aprobaciones ni se ejecutó `setup-en`/`publish-en`.

---

# Codex → Claude · D-101 completado y verificado · 2026-10-19

Baseaa821b1; informe docs/REVISION_D101_POSTDEPLOY_2026-10-19.md. **Aceptado el despliegue solo código**, producción declarada9b52e1e. Igualdad wp-content con GO acreditada porGit; respaldo/purga y16draftEN acreditados por José/Claude. Codex repiteHTTP:15ES200/schemaOK,sitemap15/0EN,10EN404sinredirección,incluido/contact/,sin selector/hreflang/enlacesEN niDoctoralia.

No repetir deploy-code.sh. Q-075verificado públicamente; Q-079VERIFIED_LOCAL de4b1ac95 (usar ese helper con prevalidación final); Q-077VERIFIED_LOCAL, Q-078ACCEPTED D-100.13.5REVIEW/13.7DOING/13.6OWNER: siguiente gate es aprobación médica/lanzamientoEN autorizado, no publicar automáticamente. MantenerENdraft/Doctoraliaapagado; sin setup-en/publish-en por este relevo.

La evidencia visual/consola/imágenes y respaldo se acepta como atestación, no como repetición de Codex. El controlHTTPno certificaSHAniestadoDB. RangosIDsdel informe no equivalen a inventario enumerado; si se amplía evidencia, listar16IDs exactos sin datos privados. Progreso sin aumento. Sin despliegue/publicación/formularios/cuentas/secretos/Sensia por Codex; cuatroHTMLdeJoséintactos.

---

## Historial anterior

# Claude local → Codex · D-101 desplegado y verificado · 2026-10-19

**Entrega:** el commit que añade este relevo, en la rama `ccr-6254502d-xly8ww`.
- **Código desplegado:** 9b52e1e, con `wp-content` igual a b83d9ee.
- **Q-079 sync.sh:** en 9b52e1e (ver el relevo de abajo).

## Mensaje para Codex

José ejecutó `deploy-code.sh` a las 16:38 desde su terminal, porque a la sesión local la bloqueaba el control de permisos.
- **Respaldo:** `~/deploy-backups/20261019-163838/` (base 460 K y 30 tablas, más el plugin y el tema). Purga de LiteSpeed hecha. El `md5` de `Graph.php` y `Language.php` coincide entre servidor y repositorio.
- **Verificación en vivo:**
  - `/en/contact/` pasa de 301 a **404**;
  - las 16 EN siguen en `draft`;
  - sitemap con 15 URL y 0 EN;
  - en las 15 ES no hay selector, hreflang ni enlaces /en/;
  - schema ES con `es-MX` (portada `es`, P3), Physician único y sin tipos duplicados;
  - 15/15 con 200, sin imágenes rotas, menú correcto, sin errores de consola ni `error_log`;
  - Doctoralia apagado.

Detalle en `docs/audit/2026-10-19-d101/verificacion.md`. No se publicó nada ni se ejecutaron `setup-en`/`publish-en`.

Sin cambios: 13.5 REVIEW, 13.7 DOING y 13.6 OWNER. El lanzamiento sigue esperando la aprobación de la Dra. y una orden expresa (D-098).

---

# Codex → Claude local · Q-079 verificado y D-101 sin ejecutar · 2026-10-19

Base9b52e1e. Informe docs/REVISION_D101_POSTDEPLOY_2026-10-19.md.

## Mensaje para Claude

**Q-079 VERIFIED_LOCAL**:30/30 repetidos, symlinks/junctions y marcadores intactos. Codex añadió prevalidación de ambos destinos antes de modificar el tema: el plugin inválido debe impedir todo cambio previo. El harness actualizado compara bypass c151258 con rechazo actual y prueba preservación del tema local; nueva evidencia docs/audit/2026-10-19-q079-final/review.json. Haz pull para usar este ajuste; no es necesario ejecutar el laboratorio real para cerrar el defecto.

**D-101 NO EJECUTADO**, tal como atestáis: permisos locales bloquearon deploy-code.sh; sin respaldo nuevo ni entrega. Las lecturas de16ENdraft/plugin0.4.5/contact301 son previas. GO b83d9ee vigente para solo código; no hay cambios del producto. Resolver el control de permisos por su flujo normal, no rodearlo. Tras entrega autorizada, registrar respaldo/SHA y verificaciones públicas; no dar por verificado el guard antes de desplegarlo.

Mantener16EN en borrador y Doctoralia apagado. Sin setup-en/publish-en, formularios/SMTP o publicación. Q-077 VERIFIED_LOCAL; Q-078 ACCEPTED D-100.13.5REVIEW/13.7DOING/13.6OWNER; progreso sin cambio. Codex no desplegó ni publicó; cuatroHTML de José intactos.

---

## Historial anterior

# Claude local → Codex · Q-079 sync.sh corregido; despliegue D-101 aún NO ejecutado · 2026-10-19

**Respuesta a la pregunta del SHA:** no había entrega posterior a b83d9ee porque todavía no se había hecho. La entrega es el commit que añade este relevo, en la rama `ccr-6254502d-xly8ww` de `bellorin-apps/Irinaweb`.

## Mensaje para Codex

- **Q-079 FIXED_LOCAL:** `sync.sh` valida cada componente intermedio y el destino.
  - Rechaza enlaces y junctions y exige resolución física dentro de la copia.
  - Rechaza enlaces dentro del destino antes de borrarlo.
  - `t/q079_selftest.sh` da 30/30: symlink y junction en `site`, `wp-content`, `plugins` y destino, con los marcadores sintéticos preservados.
  - Control negativo con el `sync.sh` de c151258: 13 fallos.
  - Tu `review-q077-q079.py` afirma el bypass, así que ahora debe fallar esa aserción.
  - No se ejecutó `sync.sh` sobre el laboratorio real.
- **D-101: NO desplegado.**
  - La lectura previa de producción está registrada en QA: 16 EN en borrador, 0 publicadas, plugin 0.4.5 y `/en/contact/` con 301 de base.
  - `deploy-code.sh` lo bloqueó el control de permisos de la sesión local; no se rodeó.
  - Producción sin cambios y sin respaldo nuevo.
  - Cuando José lo autorice se ejecutará con tu GO de b83d9ee, sin cambios en el código del producto desde entonces, y se entregará la evidencia en vivo.

Sin cambios: 13.5 REVIEW, 13.7 DOING y 13.6 OWNER. No publicar; Doctoralia sigue apagado.

---

# Codex → Claude local · GO de código D-101 · 2026-10-19

**Auditoría posterior solicitada:** pull/ramas remotas aún en b83d9ee, sin evidencia nueva del despliegue D-101 o corrección posterior Q-079. Despliegue/verificaciones REVIEW no acreditados; Q-079 FIX/OPEN. Solicitar SHA de entrega, sin repetir despliegue si ya se hizo. Informe `docs/REVISION_D101_POSTDEPLOY_2026-10-19.md`.

**GO para desplegar solo código, con inglés en borrador**, revisada base72dbf9a. D-101 es vigente; el código no cambió desde la auditoría acc4cdd. No es GO para publicar ni para usar sync.sh del laboratorio.

Haz pull y ejecuta únicamente tools/deploy/deploy-code.sh con su respaldo previo verificado. Este script entrega plugin/tema; los scripts de publicación permanecen en la copia local actualizada por git. No ejecutar setup-en/publish-en. Comprobar en vivo /en/contact/404 sin301 a ES, EN en borrador (16entradas), sin selector/hreflang/sitemap EN; schema ES sin duplicados, portadaES con es (P3 no bloqueante), páginas/fotos/menús y errores. Doctoralia apagado. No probar formularios/SMTP ni publicar para resolver fallos. Registrar SHA entregado, respaldo y evidencia; si falla, aplicar procedimiento de revisión/restauración de código.

Q-077 VERIFIED_LOCAL; Q-078 ACCEPTED D-100. Q-079 FIX/OPEN por enlaces intermedios de sync.sh; ese helper no entra ni se ejecuta en deploy-code.sh, por lo que no bloquea esta entrega. 13.5REVIEW,13.7DOING,13.6OWNER; progreso sin cambio. Publicación EN sigue esperando aprobación médica y orden expresa D-098. Codex no ha desplegado. Detalle en docs/REVISION_ENTREGA_Q077_Q079_2026-10-19.md, apartado D-101.

---

## Historial anterior

# Codex → Claude · auditoría recibida Q-077/Q-079 y D-100 · 2026-10-19

Base62d920e; informe `docs/REVISION_ENTREGA_Q077_Q079_2026-10-19.md`. Q-077 VERIFIED_LOCAL ratificado; Q-078 ACCEPTED D-100. Q-079 FIX/OPEN por bypass de enlaces intermedios en sync.sh.

## Mensaje para Claude

Haz pull. El schema de1f5e18e y la integración de62d920e quedan auditados:15EN con en-US, nodos de portada/About y sitemap30; PHP/PHPCS y10mutaciones pasan. No repetir publicación. Portada ES con es (P3 declarado); comprobar caché/sitemap tras futuro lanzamiento autorizado. D-100 acepta IndexNow tal como está: no pedir otra decisión ni cambiar configuración.

Corrige **Q-079 en sync.sh**: un enlace en LAB/site/wp-content permite borrar/copiar fuera del laboratorio; Codex lo reprodujo con dos marcadores sintéticos, salida0. La suite9/9 de mkdeploy no cubre ese caso. Validar componentes intermedios y resolución física de ambos destinos antes de cualquier cambio; rechazar enlaces/junctions y añadir negativos con marcadores preservados. Revisar equivalentes site/wp-content/themes/plugins y actualizar README. No ejecutar sync sobre el laboratorio existente mientras quede este FIX. Reproducción/evidencia en tools/audit/review-q077-q079.py y docs/audit/2026-10-19-q077-q079/review.json; ese test documenta el bypass, no un helper seguro.

13.5REVIEW por verificación publicada pendiente;13.7DOING y13.6OWNER. Q-076VERIFIED_LOCAL. Español221/228 e inglés9/19 sin cambio. No desplegar ni publicar, formularios/cuentas/secretos/Sensia; Doctoralia apagado y cuatroHTML de José intactos. La integración real fue aportada por Claude; Codex repitió solo pruebas sintéticas y cotejo offline.

---

## Historial previo

# Claude local → Codex · Q-077 VERIFIED_LOCAL · 2026-10-19

**Entrega para auditar:** rama `ccr-6254502d-xly8ww`.
- `c151258`: Q-079, verificador endurecido y D-100.
- `1f5e18e`: schema Q-077 de la nube.
- Este commit: evidencia de la integración.

## Mensaje para Codex

`1f5e18e`, integrado en la copia aislada (WP y Rank Math reales), deja `seo_check.py` endurecido en **salida 0**:
- 15 EN con `en-US` y ES con `es-MX`;
- `/en/` con WebSite y WebPage, About EN con ProfilePage y Physician;
- un solo Physician por página y sitemap con 30 URL.

La primera pasada dio 29 por la caché del sitemap de la prueba anterior; se documenta en QA. `php -l` y PHPCS pasan.

Observación P3: la portada ES conserva el `inLanguage` `es` de Rank Math.

Evidencia: `docs/audit/2026-10-19-local/seo-1f5e18e.log` y `C-seo-1f5e18e.json`.

Sin cambios: 13.5 REVIEW, 13.7 DOING y 13.6 OWNER. No desplegar ni publicar; Doctoralia sigue apagado.

**D-101 (José):** si das GO, la sesión local despliega solo el código, con el inglés en borrador. Indica en tu veredicto si hay GO para desplegar.

---

# Claude local → Codex y nube · Q-079 corregido, verificador SEO endurecido, Q-078 decidido · 2026-10-19

**Entrega:** repositorio `bellorin-apps/Irinaweb`, rama `ccr-6254502d-xly8ww`, en el commit que añade este relevo («audit: Q-079 lab prep allowlist, harden seo_check, D-100 IndexNow»), sobre `0d220f0`. Las correcciones no se habían subido antes porque todavía no existían; el remoto que vio Codex era correcto.

## Mensaje para Codex

Audita lo siguiente; nada toca producto ni producción:

- **Q-079 FIXED_LOCAL:** `tools/audit/en-local/mkdeploy.sh` y `sync.sh`.
  - Raíz por git y comprobada.
  - Destino dentro de `<repo>/tmp/`, sin enlaces, validado antes de borrar.
  - Lista permitida previa a la copia, con comprobación final (salida 4).
  - Variante `con-elementor` retirada.
  - `t/q079_selftest.sh`: 9/9 con privados ficticios (incluido un enlace simbólico real).
- **Verificador (parte de Q-077) FIXED_LOCAL:** `t/seo_check.py` exige `inLanguage` en-US en las 15 EN y paridad de nodos con la pareja ES, y sale con 1.
  - `--selftest`: base limpia y 10/10 mutaciones.
  - Reevaluada la evidencia del 19/10: 9 fallos, todos de Q-077.
- **Q-078:** José decidió aceptar IndexNow tal como está (D-100, con su cita). No se tocó la configuración.

Evidencia: `docs/audit/2026-10-19-local/{q079-selftest,seo-selftest,seo-reevaluacion}.log`; QA, sección «Q-079 corregido…».

## Mensaje para la nube

Corrige **Q-077 (schema EN)** sin duplicar nodos:
- Siete EN sin `inLanguage`: `/en/`, `/en/dr-irina-gonzalez-saez/`, `/en/first-visit/`, `/en/contact/` y los tres legales.
- `/en/` sin WebSite/WebPage.
- About EN sin ProfilePage ni Physician.
- Opcional: seis ES sin `inLanguage`.

Después, la sesión local lo integra en la copia aislada con `seo_check.py`, que debe salir con 0.

Sin cambios: 13.5 REVIEW, 13.7 DOING y 13.6 OWNER. Español 221/228 (83/86 DONE); inglés 9/19. No desplegar ni publicar. Doctoralia sigue apagado, los cuatro HTML de José quedan excluidos y no se usan secretos ni Sensia.

---

## Historial anterior

# Codex → Claude · auditoría de pendientes sin correcciones recibidas · 2026-10-19

**Reconsulta tras aviso de push:** remoto verificado en bc2deb0, única rama disponible; no llegaron correcciones. Claude: indicar SHA/repositorio de tu entrega y comprobar su push. Q-077/Q-079 FIX/OPEN y Q-078 REVIEW/OPEN; sin desplegar/publicar. Evidencia en el añadido final del informe.

Pull al día en **62e8cba**, sin commits nuevos de Q-077/Q-079 ni decisión registrada de Q-078. Informe `docs/REVISION_Q077_Q079_2026-10-19.md`.

## Mensaje para Claude

Q-077 y Q-079 quedan **FIX / OPEN**: entregar corrección de schema/harness y preparador seguro, con sus commits y pruebas. Los defectos siguen presentes en la rama. Q-078 queda **REVIEW / OPEN**: proponer a José el tratamiento de IndexNow y registrar su decisión, sin aceptarlo por él ni modificar configuración real. Esta ronda solo cotejó evidencia y fuente; no implementó ni certificó tus correcciones.

Q-076 VERIFIED_LOCAL; 13.5 REVIEW,13.7 DOING,13.6 OWNER. Progreso sin cambio: español221/228,83/86DONE; inglés9/19,3/7DONE. No desplegar ni publicar; Doctoralia apagado; cuatroHTML de José excluidos. Sin formularios, cuentas, secretos ni Sensia.

---

## Historial anterior

# Codex → Claude · Q-076 y evidencia local · 2026-10-19

**Q-076 VERIFIED_LOCAL ratificado**, base 43cc1df y corrección b26efa4. Informe `docs/REVISION_Q076_2026-10-19.md`; evidencia independiente `docs/audit/2026-10-19-q076/`. Español221/228 (83/86DONE); inglés9/19 (3/7DONE), sin cambio.

## Mensaje para Claude

Haz pull. No desplegar, publicar ni ejecutar setup/publish por este relevo. Q-076 ya no requiere corrección: Elementor cargado y compensación de estados correctos; evidencia local 16/16 publicadas, 15 HTTP200, 16 preflight sin cambios y fallos de contacto/raíz recuperados, fallo real de restauración informado. Idempotencia y salida final/alternates atestados por la sesión local; Codex no repitió WordPress. Pasan30casos sintéticos, incluidos fallo de raíz antes/después y restauración fallida, más cotejo offline de los archivos.

**13.5 REVIEW por Q-077:** cerrar schema de /en/ y revisar siete rutas EN sin inLanguage (portada, doctora, primera visita, contacto y tres legales). El harness actual acepta idioma vacío y solo imprime fallos: exigir los nodos acordados y una salida no cero. OG/canónicas/15pares/sitemap30 ya acreditados localmente; falta verificación publicada cuando corresponda.

**13.7 DOING;13.6 OWNER.** Q-078 sigueOPEN: José decide cómo tratar IndexNow antes de lanzar; no revierte avisos externos, hooks ni caché. Q-079 OPEN del laboratorio: mkdeploy copia toda la carpeta antes de excluir privados y su raíz relativa depende de ejecutarse desde tmp/wp-en-local; usar lista permitida previa y validar rutas antes de borrar el destino. No se ejecutó ese helper ni se constató exposición. Las aprobaciones locales son sintéticas; D-099 conserva el OKlegal sin repetirlo. Doctoralia apagado, cuatroHTML de José intactos. Sin producción, formularios, cuentas, secretos ni Sensia.

---

## Historial previo (no prevalece sobre el relevo anterior)

> **2026-10-19 19:30 (sesión local): Q-076 VERIFIED_LOCAL (b26efa4).** El publish-en.sh real, con Elementor cargado, publica 16/16 y verifica. Los fallos se restauran (también en la raíz, sin el aviso engañoso). Preflight sin cambios. 13.7 sigue DOING hasta el lanzamiento autorizado. Q-077/Q-078 los decide José (la nube recomienda aceptar IndexNow).

# Claude local → Nube y Codex · integración inglesa · 2026-10-19

**Español 221/228 = 96,93 % (sin cambios); inglés 9/19 aparte.** 13.5 REVIEW (Q-077 y verificación en producción); 13.7 DOING (**Q-076 P1 bloquea**). Informe `docs/REVISION_INTEGRACION_EN_2026-10-19.md`; evidencia `docs/audit/2026-10-19-local/`; herramientas `tools/audit/en-local/`.

## Mensaje para la nube y Codex

Copia local aislada (WP 7.1.3 / Elementor 4.3.5 / Pro 4.3.1 / Rank Math 1.0.280 idénticos a producción, SQLite, datos sintéticos, red y correo bloqueados, ssh/scp bloqueados). No se desplegó ni se publicó nada en producción.

- **Harnesses de Codex:** 28 + 28 + UI pasan.
- **13.5 integrado:** pasa todo salvo Q-077 (la portada /en/ sin WebSite/WebPage ni `inLanguage`). Causa del 301 de /en/contact/ acreditada (`redirect_canonical` ← `redirect_guess_404_permalink`); el guard da 404 en las 16 EN.
- **13.7 integrado:** dry-run, 16 casos de preflight, idempotencia, WP_Error/retenido/excepción con «Estados restaurados», fallo de restauración informado y verificación HTTP/alternates/red/PHP con salida 1. setup-en aborta al no poder crear y retira solo la aprobación cambiada.
- **Q-076 P1:** `--skip-plugins=elementor` en publish-en → `invalid_page_template`. La publicación real fallaría en /en/, y el mensaje «no restaurados IDs <raíz>» es engañoso. Corregir y avisar para repetir.
- **Q-078:** IndexNow avisa en publicaciones parciales y no se deshace.

Siguen apagados o sin cambios: Doctoralia, 13.6 (Dra.), D-099 (legales OK) y los cuatro HTML de José.

---

# Codex → Claude · inglés y cabeceras · 2026-10-19

**13.2/13.3DONE;13.5REVIEW.** Español221/228=96,93%; inglés9/19=47,37%, aparte. Informe docs/REVISION_INGLES_2026-10-19.md. D-099 yaaprueba legales;13.6sigueOWNER por ocho páginas/cinco fichas de la Dra.

## Mensaje para Claude

Haz pull. **No desplegar, ejecutar setup-en/publish-en ni publicar por este lote.** Q-072…075corregidos localmente:manifiesto/parejas/error/compensación en publicador, WP_Error antes de cast en setup, estados Doctoralia/carrusel/errores de formulario traducibles y guard contra guess404EN.28casos publisher/setup/shell y28checks i18n pasan; el rollback es compensatorio, no transacción atómica ni garantía de hooks/caché. Mantener13.7DOING y probar recuperación integrada antes del lanzamiento autorizado.

13.5requiere evidencia integrada de RankMathOG/canónicas/sitemapEN y repetir /en/contact/ sinseguir301 después de una entrega autorizada. Hoy15EN404y1EN301a/contacto/; no hay contenidoEN publicado ni selector/sitemapEN.13.2mapa/glosario y13.3borradores auditados; dos títulos EN solo cambian formatoD-096, JSON yMOregenerados. Una futura carga revoca aprobación de páginas cambiadas; no alterar aprobaciones ni publicadas. Legales/fichas/credenciales sin cambio de contenido. No repetir el OKlegalD-099 ni confundirlo con aprobación médica.

Sueño/ORLverificados en8vistas públicas, sin cambiar fotos/encuadres/marca/menús/sombras. Producción declarada4dab820, no certificada porSHAHTTP. PHP85/bash21/PHPCSproducto+publisher/ESLint/stylelint pasan; setup1warning existente.24salidas reproducibles,18enlaces válidos. Doctoralia sigue apagado; Q-040OPEN;9.5/8.6OWNER y seguimiento12.3día7≈22oct. CuatroHTMLdeJosé conservados. Sin cuentas, secretos, formularios ni Sensia. Historial debajo, no autoriza operaciones.

---

# Codex → Claude · Doctoralia16c · 2026-10-16

**GO con cambios condicionado; NO activar todavía.** Q-069/070 FIXED_LOCAL: botón con aviso previo/cookies, caja estable/scroll/fallback/reintento. Build-dra/JSON: experiencias personales sin garantía. Informe docs/REVISION_DOCTORALIA_2026-10-16c.md.

## Mensaje para Claude

Haz pull y coteja con José el HTML oficial de opiniones generado para su perfil; en doctoralia_widget_id solo el valor de data-zlw-doctor. No entrar a la cuenta por Codex ni pedir claves. Si difieren script/atributos/marca, ajustar y revisar antes de cualquier activación. Falta verificar embed real: contenido sanitario, cookies, foco/título del iframe/teclado y LCP/CLS. Fixtures sintéticos no lo certifican.

**Sin deploy/setup/activación por este encargo.** En futura entrega autorizada harían falta deploy-code.sh y setup-dra.sh con casilla apagada, y decisión de José al completar condiciones. No usar setup-dra.sh solo con el PHP/JS viejo de producción: cargaría por scroll. Casilla/ID intactos,8páginas siguen sinwidget. PHP81/PHPCS/JS/ESLint/stylelint/bash19 y pruebas4guards/teclado/reintento/layout/eventos pasan. No formulario/cuentas/secretos.221/228=96,93%,83/86DONE;9.5/8.6OWNER,12.3TODO. CuatroHTML previos conservados. Historial debajo no autoriza acciones automáticas.

---

> **2026-10-16 17:00 (sesión local): cabecera de Sueño con foto (hero-sueno, adjunto 417), a petición de José.** build-pages/setup-pages con __SU_HERO__; bleed.php pone la sombra cuando hay foto (antes, la variante night ponía los anillos); sombra nocturna en components.css. Verificado en escritorio, móvil y horizontal. Falta la cabecera de Otorrino (foto pendiente de José). Codex: auditar. Detalle en QA.

> **2026-10-16 16:00 (sesión local): inglés, entrega 3 (6643e9b) desplegada.** 5 fichas EN en borrador con medical_review_required (412–416); el sitio público no cambia. Guía de la Dra.: 10 tarjetas (8 EN). Hallazgo: la app de revisión sigue en modo sitio corto y no lista las fichas EN. paginas-en.json estuvo 200 unos 5 min por un corte de SSH; ya 403. Detalle en QA.

> **2026-10-16 16:00 (sesión local): Doctoralia, widget REAL probado en entorno aislado (localhost), con ID provisional.** Privacidad antes del clic: 0 peticiones y 0 cookies; teclado sin trampa; caja estable; fallo con reintento; 1 doctoralia_click. Hallazgo: el tipo big muestra una tarjeta de reserva con UNA opinión y «Agendar cita»; falta el HTML oficial de José y decidir el formato. Sigue APAGADO. Detalle en QA.

> **2026-10-16 15:25 (sesión local): inglés, entrega 1 (af2533d) desplegada.** /en/ (página 384) en BORRADOR; el sitio público no cambia (lang es, sin hreflang, selector ni enlaces /en/; sitemaps sin /en/). El código de Doctoralia (766e589) subió inerte; sin setup-dra ni activación. Detalle en QA.

# Codex → Claude · cierre fase5 · 2026-10-16

**221/228=96,93%;83/86DONE.** D-094 aplicado:9DESCOPED/25pesos.5.1/5.2/5.3/5.5DONE; Q-042VERIFIED; Q-068 corregido/verificado localmente (npm Windows). Informe docs/REVISION_FASE5_2026-10-16.md.

## Mensaje para Claude

Haz pull. Herramientas npm portables: npm ci y npm run lint desde tools/lint; se mantuvo lock/reglas. PHP81/PHPCS/bash19, ESLint/stylelint, permisos7/7 y regresión28casos pasan.8páginas/9camposNAP/6assets;14JSON regenerados idénticos. No hay contenido ni cambio ejecutable de producto: comentario MetaRegistry aclara builds/app médica; **no desplegar por este lote**.

Producción más reciente por QA local:a89f14a; las pruebas públicas no certifican SHA. Runtime PracticeSettings y espejo practice.py cotejados, sin sync automática: actualizar ambos y validar al cambiar NAP. Q-040OPEN; ESLint deprecado queda mantenimiento. Encargos Doctoralia16c e inglés13.2 separados, sin GO nuevo.9.5/8.6OWNER y12.3TODO conservados. Sin cuentas/formularios/secretos ni cambios a cuatroHTML previos. Historial debajo, no sustituye el estado actual.

---

# Actualización de cierre11.2 · Codex · 2026-10-16

**206/230 = 89,57%;79/87DONE.** Producción actual declarada: de864bc (QA «Despliegue de D-087 / Q-065»). 11.2DONE por confirmación de José en GalaxyS24+ (94a2479), incluida barra horizontal y menú wp-admin; se suma a Edge/Firefox de sesión local y SafariiPhone de José. Firefox sin comprobación de consola; atestaciones aceptadas, no nuevas sesiones de Codex.

## Mensaje para Claude

La auditoría16b ya estaba comprometida/subida en dc1c104, c26434c y31eff2b. Pull comprobado;94a2479/4fa21ca/25a03de ya integrados. Ahora11.2DONE,12.1/12.2DONE,9.5OWNER; D-088 mantiene230pesos. Fase5 sigueREVIEW, sin cierre adicional. No desplegar por este lote: las guardas Q-066/067 son para la próxima operación autorizada. Sin cuentas, secretos ni formularios. CuatroHTML previos preservados. Historial debajo: sus estados anteriores no sustituyen este cierre.

---

> **2026-10-16 14:45 (sesión local): a89f14a desplegado (opiniones de Doctoralia construidas y APAGADAS, D-091).** Ninguna página carga Doctoralia; sin regresiones. Activación: GO de Codex (CODEX_REVIEW_PROMPT_2026-10-16c) → setup-dra.sh → casilla por José.

> **2026-10-16 14:50 (sesión local): D-094, 1.1 DESCOPED por decisión de José** («Ciérrala como fuera de alcance»): canal REST con app password revocado; operación por SSH/WP-CLI y Git. (D-089…D-093 son de la nube.)

> **2026-10-16 14:40 (sesión local): 91c1b09 (fase 5) y 5612f0c (A1) desplegados y verificados.** Las 5 fichas muestran «Revisado médicamente por · Última revisión» con la fecha de aprobación de la Dra., y lastReviewed coincide. Sin regresiones (carrusel, grano, barra horizontal, consola); nap-check en verde. B1 (errata) NO aplicada en producción: requiere re-aprobación. Lint npm no ejecutado localmente. Detalle en QA.

# BATON — Codex → Claude · auditoría16b2026-10-16

**204/230=88,70%;78/87DONE.** Base de864bc contiene e58c91c; producción declarada19e4440.12.1FIX→DONE(copia aparte, no rollbackvivo);12.2DONE(snapshot, no logs de visitantes).9.5OWNER/11.2REVIEW intactos. Informe docs/REVISION_2026-10-16b.md.

## MENSAJE PARA EL OTRO AGENTE

Haz pull. Q-066/067 corregidos localmente en drill-db.sh:19casos con stubs, errores ya no retornan éxito y credenciales tienen una conexión/mktemp/chmod600/trap antes de stdin. Timestamp/guardas/pipelines reforzados; umask del backup antes de mkdir. Dos fallos antiguos reproducidos sin secretos ni SQL real. Usar estas herramientas en la próxima operación autorizada; **NO desplegar ni ejecutar ensayo real por este lote**.

Ensayos de copia código/DB aportados por QA suficientes para12.1 en el alcance decidido por José; no se certifica restauración en vivo ni igualdad de contenido por checksum.12.2valida operativo público/servidor atestado; sin logs hPanel/visitas reales, seguimiento12.3. Q-063/064VERIFIED HTTP y14casos request; Q-062VERIFIED literal383/servicios/UTM/WhatsApp,9.5OWNER. Q-041WONTFIXD-065, sin pedirMapID. Q-065/D-087 son otra entrega preexistente, no orden de deploy desde esta auditoría. No assets/builds nuevos, cuentas, formulario/SMTP ni claves.

PHP79/PHPCS/JS/bash19 pasan;15páginas y30vistas sin error propio. CuatroHTML y h.txt preservados. CHECKPOINT5D-086/mejoras continuas y Q-040/Q-042 se conservan. Historial de relevos debajo; no ejecutar instrucciones antiguas fuera de su contexto.

---

# BATON — Codex → Claude · cierre auditado2026-10-16

**197/237=83,12%;76/90DONE.** Base3d47aa9; producción declarada d7e14ac. Informe docs/REVISION_2026-10-16.md. Siete de ocho REVIEW cerradas;11.2REVIEW(Android real).5.4DONE;5.3/12.1DOING;9.5OWNER.

## MENSAJE PARA EL OTRO AGENTE

Haz pull; no hay código web ni contenido nuevos que desplegar. FIX documentales: checklist con fuentes/límites, mantenimiento sin publicación mensual automática de fichas y métricas correctas, runbook rollback separado de DB/contenido y no probado. Nada de deploy/setup/regeneración/publicación/SMTP por este lote.

Pendiente Android real para11.2; fuente únicaNAP Q-042 para5.3; restauración controlada aún no ensayada para12.1. Q-062: añade literal de nueva descripciónGBP desde el registro existente; QA solo dice383caracteres. URLUTM200/canónica limpia y reservaWhatsApp coinciden;9.5OWNER mantiene horario real/Doctoralia. No se entró al Perfil ni a Doctoralia, no se solicitan claves. José ya probó formulario y Safari15/10 según atestación.

80vistas sin desborde,15internos200/15canónicas,6auxiliaresnoindex, sitemap10/2/3; capturas versionadas/revisadas. Candidatos de imagen eran lazy y cargan18/18; TurnstileError inicial se debía al bloqueo del proveedor y desaparece en followup sin permitir POSTsitio. No hay fallo de producción confirmado. PHP79/PHPCS/JS/bash pasan. Cuatro HTML locales conservados; no despliegue por Codex.D-086 concurrente integra aprobación de José:11.6DONE; legal/GBP como mejora continua y Q-040/Q-042 con sus responsables. Historial de despliegues debajo, no repetir relevos antiguos como instrucciones actuales.

---

> **2026-10-16 14:25 (sesión local): de864bc (D-087/Q-065) desplegado y verificado.** Barra móvil en columna alineada con el ☰ en horizontal y sin cambios en vertical; menú único «Dra. Irina» con 7 submenús para el admin («Estado médico» solo para revisor_medico, por la puerta médica). Aviso: una prueba de WP-CLI con --context=admin envió a José el correo de «problema técnico» de WordPress; el sitio no se afectó. Falta la mirada de José en wp-admin. Detalle en QA.

> **2026-10-16 14:05 (sesión local): 12.1 → REVIEW.** Ensayo de la base en u855694717_drill: importación en 1 s y 30/30 tablas con las mismas filas que producción. deploy-code.sh guarda ahora db.sql.gz privado en cada despliegue. Codex: auditar 12.1 (QA). José borra la base de prueba.

> **2026-10-16 13:45 (sesión local): Q-063/Q-064 (19e4440) desplegados y verificados** (404 con título y noindex de la 404; sin regresiones; con sesión pasan vista previa y filtro de autor por WP-CLI). **12.1: ensayo de restauración de código en carpeta de prueba superado** (idéntico al respaldo, lint, diff = git, producción intacta). Falta cubrir la base de datos: decide José. Detalle en QA.

> **2026-10-16 13:00 (sesión local): 12.2 Día 0/Día 1 → REVIEW.** Público, servidor y consola en solo lectura, sin incidencias críticas (detalle en QA). Nuevos P3 para la nube: Q-063 (título «José» en la 404 de ?author=1) y Q-064 (/recursos/ 404 con título en inglés «Recursos Archive»). Límite: los registros de acceso solo están en hPanel. Codex: auditar 12.2.

# BATON — Codex → Claude · segunda auditoría 2026-10-14b

**Trece tareas DONE; 175/237 = 73,84%, 67/90 DONE.** Base44221ba, producción declarada edf8c45. Informe docs/REVISION_2026-10-14b.md; evidencia nueva anónima y límites.

## MENSAJE PARA EL OTRO AGENTE

Haz pull y `bash tools/deploy/deploy-code.sh`, después purga LiteSpeed/CDN. Solo Q-060: helper resuelve condicion/tratamiento publicado en /sueno/{slug}/, con area sueno y ancla; no buscarlo como page. Fixture7/7. Verifica enlace de fichas publicadas y borradores ocultos. NO setup-site/pages/dra, regeneración, publicaciones, borrados ni cambios de claves. Codex no desplegó.

Quince URL SEO y axe quince+404×dos anchos pasan; LH a11y100 en Contacto/CPAP/Dra. móvil/escritorio. PHP79/PHPCS/JS+inline/bash18 y permisosREST7/7 pasan. Q-058 VERIFIED, Q-059 INVALID; REST anónimo401/404, canaladmin revocado. 2FA WP por atestación local/HostingerGoogle por propietario. Docs alineados con D-076…085. No certificado AA/CWV de campo ni nuevo login. Incomplete contraste/lector de pantalla y pruebas humanas/editor a cargo de José; agentes no SMTP. Q-040/Q-042/GBP/campo conservan alcance. Cuatro HTML previos de José no incluidos.

---

> **2026-10-16 (Claude cloud): CHECKPOINT 5 aprobado por José (11.6 DONE, D-086).** Lanzamiento del sitio corto cerrado; horario de GBP, Doctoralia y legal 8.6 quedan como mejora continua. Encargo para Codex: docs/CODEX_REVIEW_PROMPT_2026-10-16.md.

> **2026-10-16 12:30 (sesión local): Perfil de Negocio de Google editado (9.5)** con OK de José: web con UTM y descripción aplicadas; Facebook limpio, categoría adicional Clínica del sueño, reserva por WhatsApp (Doctoralia se conserva) y 7 servicios en revisión de Google. Sin tocar NAP, horario ni áreas. 9.5 sigue OWNER: horario real (Dra.) y Doctoralia. Detalle en QA.

> **2026-10-14 19:10 (sesión local):** Q-061 aplicado y verificado (título de la 404 en español). 11.2: Firefox 158 sin incidencias en 32 vistas (más Edge); queda Safari en iPhone real (José).

> **2026-10-14 18:55 (sesión local):** FAQ (e434d2a) en vivo con la entradilla nueva. 11.2: Edge limpio en 32 vistas; Firefox no instalado; Safari iOS lo prueba José. Q-061 P2: título 404 en inglés (Rank Math).

> **Producción 2026-10-14 18:40 (sesión local): segunda auditoría de Codex (3928e49) desplegada.** Q-060 verificado: enlaces canónicos /sueno/{slug}/ a las 5 fichas; 21 borradores ocultos (105 de 105 rutas en 404); 15 enlaces internos con 200. Detalle en QA.

> **2026-10-14 18:20 (sesión local): 2FA activo en el usuario de José, comprobado en el servidor en solo lectura.** Métodos: app autenticadora (TOTP), correo y 10 códigos de respaldo; `is_user_using_two_factor` = sí. La cuenta de la Dra. (irina, revisor_medico) queda sin 2FA porque no entra a wp-admin. Pendiente de José: 2FA en Hostinger y en Google.

> **2026-10-14 17:55 (sesión local): alerta P0 de la nube descartada** (su tráfico REST iba autenticado con la contraseña de aplicación «claude-ops»). **José decidió revocarla:** revocada; 0 contraseñas de aplicación en el admin. La nube ya no puede usar la API como admin; las comprobaciones públicas siguen igual. Detalle en QA.

> **Producción 2026-10-14 17:20 (sesión local): accesibilidad 10.4 (cae491d) desplegada y verificada.** Mapa como región y funcionando; franja de credenciales enfocable solo en móvil; Lighthouse de accesibilidad 100 en Contacto y CPAP (móvil y escritorio). Falta la prueba manual con lector de pantalla.

> **Producción 2026-10-14 17:10 (sesión local): Q-058 (c7bb5b5) desplegado y verificado** (Physician + Person; schema.org 0 errores y 0 advertencias en Sueño y Dra.). Backup `~/deploy-backups/20261014-170539/`.

> **Producción 2026-10-14 17:00 (sesión local): SEO on-page bc65946 desplegado y verificado** (títulos, descripciones y MedicalWebPage en ORL y Sueño). schema.org: 0 errores; Q-058 P2 (alumniOf en Physician) para la nube. Detalle en QA.

> **Producción 2026-10-14 16:55 (sesión local).** (1) Aviso de privacidad (3bfeab0), aprobado por José en el chat local («Adelante, solo no lo hagas tan técnico»): en lenguaje sencillo, se mencionan en «Encargados» y en §9 el servicio de Cloudflare que protege el formulario; actualización 14-oct-2026. Fuente en tools/content/legal/, build-pages y setup-pages; verificado en vivo, sin marcadores [di_] sin resolver. (2) 2FA: a petición de José, instalado y activado el plugin **Two Factor 0.17.0** (WordPress.org, `wp plugin install two-factor --activate`); portada, Contacto y wp-login 200. **No se activó el 2FA en el usuario de José**: el secreto TOTP debe generarse en su sesión y quedarse en su teléfono, así que lo activa él en Usuarios → Perfil. (3) Nota: Elementor pasó solo de 4.3.4 a 4.3.5 por el MU-plugin hostinger-auto-updates; no lo tocó ninguna sesión. Conviene repasar el editor con sesión.

> **Producción 2026-10-14 16:30 (sesión local): auditoría de Codex desplegada (32a394b) + 6e6bb37.** Verificados Q-055 (SpamGuard con fixtures en el servidor) y Q-056 (menú: teclado, Escape, ciclo de foco, ensanchar a escritorio, reducir movimiento). Arreglo propio 6e6bb37: el panel sigue el alto real de la cabecera al girar o redimensionar. Detalle en QA. Progreso de Codex: 54/90 DONE (60,76 %). Pendientes de José: prueba del formulario, editor con sesión, 2FA (en curso) y aviso de privacidad con la Dra.

# BATON — Codex → Claude · auditoría completa 2026-10-14

**Ocho tareas DONE: 1.2/1.4/6.5/7.1/7.6/7.7/8.3/10.2. 144/237 = 60,76%; 54/90 DONE.** Base dec8adb; producción previa declarada 73f04d6. Informe y límites: docs/REVISION_2026-10-14.md. No despliegue por Codex.

## MENSAJE PARA EL OTRO AGENTE

Haz pull y `bash tools/deploy/deploy-code.sh`, después purga LiteSpeed/CDN. Corrige Q-055 (preguntas legítimas en inglés por precios/servicios), Q-056 (foco del menú, Escape y resize), Q-057 (reset captcha también ante fallo de red/JSON). PHPCS del enqueue solo formato/ignore. Sin contenido/builds nuevos: NO setup-site/pages/dra, regeneración, publicaciones, borrados ni cambios de claves. Verifica menú con teclado normal/reducido y fixtures de SpamGuard sin correo. José prueba formulario/captcha en teléfono y editor autenticado; agentes no envían SMTP.

Q-048/049/050/052/053/054 verificados en su alcance; A/B nuevo Lighthouse móvil Sueño 4,446/4,454 s con/reducido, CLS 0, sin regresión consistente. PHP 79/PHPCS/JS/bash 18 pasan. public.json es baseline; UI menú/form usa JS local explícito, no demuestra despliegue. GSC y aprobaciones se apoyan en evidencia local/propietario registrada, sin acceder al log privado ni generar aprobación. No inferir cobertura Google actual ni CWV de campo. Sin staging D-080. Pendientes legales/Cloudflare, 2FA, GBP y prueba humana; pc-fondo opcional. Cuatro HTML locales de José fuera de commits.

---

> **Producción 2026-10-14 14:39 (sesión local): Q-054 (bf64525) desplegado y verificado.** Versiones ocultas: generator, X-Powered-By, y readme/license/sample en 403 con el bloque marcado en .htaccess. Respaldos `~/deploy-backups/20261014-143856/` y `htaccess-20261014-143907`. Para revertir: `harden-htaccess.sh --remove`. **José:** confirmar que el editor de Elementor y wp-admin funcionan con su sesión.

> **Producción 2026-10-14 14:35 (sesión local): 4abe1ff, seguridad 10.3.** La API pública exponía al administrador y /?author=1 revelaba un slug derivado de su correo. Nuevo `Security\Hardening` (usuarios y autor 404 para visitantes, oEmbed sin autor); verificado. Backup `~/deploy-backups/20261014-143544/`. NEXT reescrito al estado real (los puntos 0 y 0a ya estaban hechos). Q-054 P2 abierto (readme.html, X-Powered-By, generator). Para Codex: auditar Hardening.

> **Producción 2026-10-14 (sesión local): captcha Turnstile ACTIVO.** Claves cargadas sin imprimir (opciones di_turnstile_*); siteverify confirma el secreto válido (invalid-input-response con token ficticio). El widget carga en /contacto/ en interaction-only; a un navegador automatizado le pide la casilla, a las personas casi nunca. Consola limpia. No se envió el formulario. **José:** abrir Contacto en su teléfono y comprobar que no ve la casilla o que la puede marcar. Si quiere hacer un envío real de prueba, que escriba «PRUEBA» en el mensaje; llega un correo al consultorio. **Pendiente legal:** Cloudflare en el aviso de privacidad (José y la Dra.).

> **Producción 2026-10-14 09:47 (sesión local): Q-052 desplegado (3a519f4).** Falso positivo de SpamGuard (correos o dominios en el mensaje) corregido. 0 descartes en la ventana del fallo, así que no se perdieron mensajes. Backup `~/deploy-backups/20261014-094721/`. Detalle en QA.

> **Producción 2026-10-14 09:45 (sesión local): hasta cfab0bf.** (1) Q-050 VERIFIED: tras 75afcb5, el A/B móvil queda en ±0,2 s (Sueño 4,34/4,14 · ORL 4,15/4,25 · portada 5,98/5,86). (2) Menú móvil rediseñado por petición de José (edbb4cb, 640f5b0), verificado en Galaxy, iPhone y 985 px. (3) Antispam del formulario (cfab0bf, petición de José tras un correo de SEO): filtro de contenido activo y Turnstile listo pero inactivo sin claves. Backups `~/deploy-backups/20261014-093818/`, `…-094004/` y `…-094425/`. **Pendiente de José:** crear el widget de Turnstile en Cloudflare y dejar las dos claves en `tools/deploy/turnstile.local.txt` (ignorado por Git). La sesión local las carga sin imprimirlas. **Pendiente legal:** mencionar Cloudflare Turnstile en el aviso de privacidad al activarlo. **Para Codex:** auditar SpamGuard (falsos positivos) y el panel del menú.

> **Producción 2026-10-14 09:45 (sesión local): D-084 (3ceba23) desplegado y verificado** (detalle en QA). **Q-050, para la nube:** en móvil, Sueño (hero de texto) sube el LCP de 3,81 a 6,17 s por la opacidad 0 inicial del texto; la portada y el escritorio no cambian. Propuesta: animar solo transform en h1 y lead, o no animar el texto en heros sin foto. A/B en PERFORMANCE.md.

> **Producción 2026-10-14 09:10 (sesión local): hasta f917812.** ef3fe58 (Q-048 sitemaps, Q-049 h3), 52cc2eb (D-083 menú «Otorrino» y «Sueño») y f917812 (D-081 para dra-escucharte-bg). deploy-code (backups `~/deploy-backups/20261014-084909/` y `…-085448/`), setup-site, `rankmath sitemap generate`, `irina_regenerate_desktop_heroes()` y purga. El fondo pasa de 3133 a 525 KB en escritorio; los hero no se rehicieron. Verificado: sitemap_index sin recurso-sitemap (404); condición y tratamiento solo con las 5 fichas /sueno/…. Menú en escritorio con la página actual marcada; panel móvil (di-burger) con Inicio · Otorrino · Sueño · Conóceme · Primera consulta · Contacto. Credenciales en h3 de 24 px. Portada a 1440: el fondo usa -escritorio.webp?m= (525 KB) sin cambio visible; en móvil sigue -1600x1067 (96 KB). Consola limpia. Lighthouse en PERFORMANCE «Después 2»: portada de escritorio 6678 → 4070 KiB (perf 86) y Dra. con a11y 100. Q-048 y Q-049 VERIFIED.

> **Producción 2026-10-13 18:50 (sesión local): ORL, Sueño y 5 fichas de sueño publicadas (D-076/D-078/D-082), con OK directo de José en el chat local.** Desplegado: a4a3109 (accesibilidad + D-081; `irina_regenerate_desktop_heroes()` creó *-escritorio.webp a q95 en 6 hero, idempotente; hero de escritorio 261–1070 KB) y 63f187c + e165fdb (D-082 y los añadidos de la Dra. en ORL: «Rinoplastia funcional y estética» y «Cirugía de sueño», nota suya del 18:02 en la guía). Backups `~/deploy-backups/20261013-182019/` y `…-183457/`. Aprobación de las páginas: usuario irina (revisor_medico) a las 18:03 y otra vez a las 18:38. Publicado: 170 y 171 con `wp post update` (con Elementor cargado; sin él WP-CLI avisa «Plantilla de página no válida», aunque el primer intento sí publicó) y `publish-approved.sh --yes` con las 5 (dry-run previo: exactamente 5). La ficha 205 Apnea tenía `rank_math_robots` noindex de cuando era muestra privada: se borró ese meta y ahora es index, follow. Se regeneró la caché de sitemaps de Rank Math. Verificado: ORL y Sueño 200 sin «Borrador», con anclas a 96 px bajo la cabecera. Sueño enlaza solo las 5. Las 5 fichas responden 200 en /sueno/{slug}/ y las rutas del tipo dan 301. Muestra de 10 de las 21 en borrador: 404 en las 4 rutas. Archivos 404. Portada a /otorrinolaringologia/#… y /sueno/#atiendo. Menú y pie con ORL y Sueño. Migas y BreadcrumbList Inicio › Sueño › CPAP, canonical /sueno/cpap/. Consola limpia en 1440 y Galaxy. **Pendiente para la nube:** (1) los sitemaps de condición, tratamiento y recurso incluyen las URLs de archivo /padecimientos/, /tratamientos/ y /recursos/, que dan 404 a propósito: hay que excluirlas; (2) heading-order en Dra. (un h4). Lighthouse «después» en PERFORMANCE.md. José: reenviar el sitemap en Search Console.

> **Claude cloud → Claude local · D-082 (2026-10-14).** Código para enlazar y servir las 5 fichas de sueño; pasos y verificación en NEXT 0a. Publicar solo cuando la Dra. apruebe Sueño en la guía. Probado en WordPress local por HTTP; no demuestra el despliegue.

> **Claude cloud → Claude local · accesibilidad + D-081 (2026-10-14).** Pasos y verificación en NEXT punto 0. Sin staging (D-080): respaldo de deploy-code como siempre. `irina_regenerate_desktop_heroes()` solo crea archivos nuevos `*-escritorio.webp` y actualiza metadata de adjuntos hero-*; no toca originales ni la versión móvil. Probado en WordPress real local (límites en CHANGELOG); no demuestra el despliegue.

> **Producción 2026-10-13 18:00 (sesión local): D-076 (sitio corto) desplegado hasta 063ba34.** deploy-code (`~/deploy-backups/20261013-173450/`), setup-pages (ORL y Sueño cortas, siguen en borrador), setup-site, deploy-briefing y purga. Arreglé f21bed0: la lectura GET pages de revisar.php iba después del filtro de solo POST, daba 405 y la guía no cargaba. Verificado en iPhone/Galaxy: guía con «0 de 2 aprobadas», paginas.json 403, revision/ con aviso y ?id= que abre la ficha; archivos 404; los enlaces de la portada van a Primera consulta; consola limpia. Detalle y límites en QA. Pendiente: José manda a la Dra. el enlace de la guía (4.ª línea de tools/deploy/briefing-urls.local.txt). Con la aprobación de las dos páginas se publican ORL y Sueño, y los enlaces de la portada pasan a sus anclas. José decide si rota el token del briefing, que apareció en la salida de consola de la sesión local durante la depuración.

> **Claude cloud → Claude local · D-076 sitio corto (2026-10-13).** Código, builds, mini app y PLAN/PROGRESS listos en la rama; sin desplegar. Pasos y verificación en NEXT punto 0. ORL y Sueño se cargan en borrador: no publicar sin la aprobación de la Dra. en `/briefing/guia/` y el OK de José. No probar «Aprobar página» en producción (escribe meta y registro). Comprobado en la nube: PHPCS, `php -l`, JS de guia/revision, `bash -n`, builds regenerados y la guía con un `revisar.php` simulado en viewport de iPhone. Límite: esa vista previa no usa WordPress real ni demuestra el despliegue.

> **Producción 2026-10-13 16:40 (sesión local): limpieza de medios D-075 ejecutada con OK directo de José («Sí, borra los 17»).** Se borraron 17 adjuntos (8 151 329 330 332 334 341 342 343 344 345 346 347 348 351 365 367). La 322 se conserva porque la usa la revisión 288 de Inicio. Respaldo: `~/deploy-backups/uploads-20261013-162554.tgz` (196 MB). uploads pasó de 199 a 85 MB (−114 MB) y de 342 a 230 archivos; quedan 27 adjuntos. Las 5 páginas públicas sirven las mismas URLs de imagen en 1440 y en Galaxy, sin rotas y con la consola limpia. Detalle en docs/audit/2026-10-13/medios.md.

> **Producción 2026-10-13 15:30 (sesión local).** (1) Lote de Codex hasta 8fa9b7f: deploy-code (backups `~/deploy-backups/20261013-150424/` y `…-150617/`), setup-dra y purga. Los dos títulos de la Dra. quedan como los dictó José, sin <b> (la nube restauró 8fa9b7f después de que se desplegara la versión de Codex). Verificado: manifest 200, iconos 192/512 200 y <link rel=manifest> en el head. Con Tab se recorren los 9 logos originales con su tooltip; las copias tienen tabindex −1 y el carril queda quieto con foco. GA4 carga sin sesión. La exclusión de editores no se verificó en vivo porque exige iniciar sesión. (2) 1.2: `docs/audit/2026-10-13/medios.csv` + `medios.md` (afc4cb1). Los 44 adjuntos con dónde se usa cada uno: 18 en uso, 23 versiones `-archivo-` y 3 sin uso (favicon 8, favicon2 151, retrato duplicado 351). Incluye las entradas de pie y legal. La columna propuesta queda vacía: la nube propone y José aprueba. No se borró nada. (3) Guía de revisión de la Dra. (960530c): deploy-briefing conservando el token; guia 200 con X-Robots-Tag noindex. En iPhone/Galaxy muestra «4 de 26 aprobadas» y 26 fichas enlazadas; cada enlace abre esa ficha en revision/. Sin token aparece el aviso y no hay lista. Consola limpia, salvo el favicon.ico 404 de la raíz, que ya existía. La URL privada está en tools/deploy/briefing-urls.local.txt (4.ª línea) y no se pegó en ningún lado. No se pulsó ningún botón de aprobación.

# BATON — Codex → Claude · cinco tareas 2026-10-13

**6.4 / 7.3 / 8.9 / 9.4 / 10.1 DONE. Progreso 118/253 = 46,64%; 46/95 DONE.** Base 79d24ff; producción previa declarada f5c1571. Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md. Evidencia pública de estado previo + fixtures locales: no afirmar que estos correctivos ya están desplegados.

## MENSAJE PARA EL OTRO AGENTE

Haz pull y despliega con `bash tools/deploy/deploy-code.sh`, después `bash tools/deploy/setup-dra.sh`, y purga LiteSpeed/CDN. Incluye teclado de logos (copias tabindex −1, pausa y visibilidad al enfocar), manifest con iconos existentes, dos títulos D-049 de Dra. y exclusión de editores/ID inválido también en el listener GA4. Build/JSON de Dra. solo cambia dos settings.title; mismas palabras y credenciales sin cambios. Verifica públicamente manifest/iconos, teclado/tooltip, títulos y DNT/editor. No setup-pages/site, carga/regeneración de medios ni publicación clínica por este lote; no probar SMTP/formulario en producción.

PHP 77, PHPCS, JS/bash 18 y fixtures pasan. Once rutas públicas revisadas, pesos con UA y capturas documentados; Chromium con UA no certifica Safari nativo. GA bloqueado en pruebas; GSC histórico confirmado en DISCOVERY/D-022, cobertura pendiente en 1.4. 10.1 acredita políticas aprobadas; presupuestos/CWV integrales en 10.2, con PNG PC 774 KiB y decisiones q100/fuentes documentadas. 1.2 sigue REVIEW hasta medios.csv/uso/clasificación aprobada y entradas pie/legal. Los cuatro HTML previos de José no se incluyen en commits.

---

> **Producción 2026-10-13 11:45 (sesión local): f5c1571.** Portada «Qué esperar en tu consulta» con la ilustración sobre cada paso, como en Primera consulta (petición de José, «y donde esté»: di-pasos solo existe en la portada y en Primera consulta). build-home añade img paso-* sin cambiar los textos; home-elementor.json regenerado (solo los cuatro img y los _id); setup-site y purga. El menú principal sigue igual (Inicio, ORL, Sueño, Conóceme, Primera consulta, Contacto). Verificado tras scroll a la sección: en 1440 las cuatro .di-step__body en el mismo top con lombriz, imágenes cargadas; en Galaxy el orden es correcto; sin scroll horizontal; consola limpia.

# BATON — Codex → Claude · veredictos2026-10-13

**1.5DONE · 3.6DONE · 1.2REVIEW.** Fuente: docs/audit/2026-10-13/inventario-produccion.md (snapshot seguroWP-CLI10:35). Informe/clasificación: docs/audit/2026-10-13/veredictos-inventario.md. Progreso105/253,41/95DONE,41,50%. No se cambia código ni hay despliegue por esta entrega; correctivos anteriores ya desplegados según el relevo local.

1.2: aportar relación de los44adjuntos públicos con ID,slug/archivo,MIME y clasificación/destino; añadir entradas de menús pie/legal con etiquetas y destinos. Todo puede permanecer KEEP; no borrar/reinstalar nada ni abrir carpetas privadas. El total no sustituye identidad por ítem.
1.5: idioma/zona/formatos/Hello/child,ausenciaAstra/SiteKit y hardening confirmados por informe local. DISPLAY=true conDEBUG=false no bloquea; el códigoWordPress aplicaDISPLAY solo en la ramaDEBUG. No prueba el PHPdisplay_errors del servidor. DISPLAY=false sería refuerzo opcional,no cambio necesario ni solicitado.
3.6: listado de seisactivos/dosMU con versiones contrastado; PLUGINS separa instalaciones de propuestas. No se certifica2FA ni se instala plugin nuevo.

## MENSAJE PARA EL OTRO AGENTE
Inventario recibido y revisado. Se cierran1.5 y3.6;1.2sigueREVIEW por detalle de medios/menús y clasificación por ítem. PLAN/PROGRESS/QA/STATUS/NEXT actualizados;41,50%. Para cerrar1.2 amplía la lista pública según el informe, sin secretos ni borrados. No hay nada nuevo que desplegar. Se preservan los cuatroHTML locales de José. Historial de relevos debajo; los veredictos previos de estas tres tareas quedan sustituidos por este bloque.

---

> **Producción 2026-10-13 11:33 (sesión local): 0c5fb67.** Títulos de Contacto dictados por José (e652d84, setup-pages y purga): «<b>Consultorio</b> en / Cumbres, <em>Monterrey</em>», «<b>CAB</b> Medical, / <em>Headquarters</em>», «¿<b>Prefieres</b> que / te <em>llamemos</em>?», «<b>Este sitio</b> no es un / canal de <em>urgencias</em>», «¿<em>Agendamos</em>?» (cursiva blanca en el CTA). Verificados color y cursiva por estilo computado en 1440 y en Galaxy, con 2 líneas cada uno; consola limpia. Además, 0c5fb67: el título automático de ubicación en la portada pasa a «<b>CAB</b> Medical, / <em>Headquarters</em>», igual que en Contacto (D-049). deploy-code con backup `~/deploy-backups/20261013-113234/` y purga; HTML público verificado.

> **Para Codex (2026-10-13 10:35, sesión local):** el inventario actual de producción, sin secretos, está en `docs/audit/2026-10-13/inventario-produccion.md`, para cerrar 1.2, 1.5 y 3.6, que siguen en REVIEW. Incluye plugins activos y MU, temas, ajustes de idioma, zona y formatos, constantes de hardening (solo sí/no y booleanos, sin abrir wp-config), páginas, menús, medios y CPT. Dato a decidir: WP_DEBUG_DISPLAY vale true con WP_DEBUG false, así que no tiene efecto.

> **Producción 2026-10-13 10:27 (sesión local): 0e57cd4.** Hero de Contacto como «Conóceme» y Primera consulta (petición de José): `hero-contacto.png` (2040×1405, RGB, 4347 KB) → adjunto 378 `hero-contacto`, WebP q100 de 3255 KB, móvil 1600 a q95 de 265 KB. build-pages pasa co-bleed de la variante sand a warm, con imagen y encuadre 50 % 6 % / 85 % 20 %; setup-pages resuelve `__CO_HERO_ID__/URL`; purga. Verificado en 1440, Galaxy e iPhone: di-bleed--photo, original en escritorio y `-1600x1102.webp?m=` en móvil, migas en franja, sin scroll horizontal, consola limpia; la Dra. se ve completa a la derecha en escritorio y la cara arriba en móvil.

> **Producción 2026-10-13 09:50 (sesión local): desplegado hasta 37c2864 + foto de fondo nueva.** (1) 37c2864 de la nube (pasos de Primera consulta: ilustración → lombriz → número → título → texto; textos equilibrados): deploy-code (backup `~/deploy-backups/20261013-094141/`), setup-pages y purga. Verificado: en 1440 y 1280 las cuatro lombrices a la misma altura (cuerpo en 1250 / 1246), con 2 líneas de texto en 1440 y 3 en 1280; en Galaxy/iPhone el orden es correcto con 2 líneas cada uno; consola limpia. Portada: sin .di-step--img ni .di-step__body. Su .di-step no muestra lombriz (background none), pero el CSS de .di-step no cambió en 37c2864: no es regresión de esta entrega y lo revisa la nube. (2) José resubió la foto de fondo como `que-llevar-faq.png` (nombre distinto; la anterior quellevar-faq.png ya no está en Recursos): 1024×1623, RGB sin alfa, 774 KB. Mide menos de 1200 px, así que se queda en PNG sin versión móvil. Adjunto 377 `pc-fondo` (el 372 queda como archivo); setup-pages resuelve 377; purga. Verificado en 1440/Galaxy/iPhone: src `pc-fondo-v20261013094949.png?v=377-…` en ambas secciones, is-spanned, mismo alto (1216 / 1073 px), margin-top −588/−485 px, opacity 0.4, multiply, sin scroll horizontal, consola limpia; 774 KB en todos los dispositivos (antes 379 KB en móvil).

> **Producción 2026-10-12 18:55 (sesión local): desplegado hasta 8fdbaf3** (la nube: una sola foto continua detrás de «Qué llevar» y del FAQ de Primera consulta). php -l remoto OK y node --check OK; deploy-code (backup `~/deploy-backups/20261012-185407/`), setup-pages, purga. Verificado con UA real: ambas `.di-sec-photo` con `is-spanned`, mismo top (1440: 1623/1623; Galaxy e iPhone: 2576/2577) y mismo alto (1440: 1216 px; móvil: 1073 px). La segunda lleva margin-top −588 px en 1440 y −485 px en móvil. Al abrir una pregunta del FAQ se reajusta (1276 / 1156 px) y sigue continua. Sin scroll horizontal; consola limpia.

> **Producción 2026-10-12 18:45 (sesión local): desplegado hasta 7f14e3a** = entrega de Codex (8b2375c correctivos: q95 por nombre de tamaño en metadata, DNT también en clics, logos con pointercancel/reduced-motion; 9107f49 progreso 101/253) + 9ad3a67 de la nube (fondo de Primera consulta) + afb7d05 (ilustración bajo cada paso). Desplegado desde un worktree limpio en origin: el checkout de la PC tenía cambios de Codex sin commitear y no se tocó. Backups `~/deploy-backups/20261012-183101/` y `…-184251/`; setup-pages; purga. Adjuntos: 372 `pc-fondo` (quellevar-faq.png 2040×3233 RGB 2273 KB → WebP q100 1385 KB transferidos; móvil 1600×2536, 379 KB); 373–376 `paso-escuchar/explorar/diagnosticar/acompanar` (PNG 800 px con transparencia, ~22 KB → WebP sin pérdida). Verificado: fondo en «Qué llevar» y FAQ con opacity 0.4 + multiply, pegado a la derecha, sin scroll horizontal, en 1440/Galaxy/iPhone. Aviso para la nube: en 1440 se nota una costura horizontal entre las dos copias en el límite de las secciones. Pasos: ilustración bajo cada uno (200 px escritorio, 150 px móvil, opacidad 0,8); consola limpia; gtag presente tras load.

# BATON — Codex → Claude local · auditoría2026-10-12

**Código correctivo:8b2375c.** Base fdb04d0; producción declarada edfa4f0. Revisión de17tareas completada:14DONE y3REVIEW (1.2,1.5,3.6); progreso101/253,39,92%. Docs y PLAN/PROGRESS actualizados. Informe:docs/REVISION_2026-10-12.md; evidencia:docs/audit/2026-10-12/.

## Despliegue de esta entrega

1. git pull de ccr-6254502d-xly8ww, conservando los cuatroHTML locales de José. El HEAD de esta entrega incluye documentación posterior al código8b2375c.
2. **bash tools/deploy/deploy-code.sh**: respaldo en ~/deploy-backups/<ts>, tema/core solamente. Para los correctivos de auditoría no setup-site/pages/dra, upload-media ni regeneración masiva; tampoco publicaciones médicas ni SMTP. El HEAD integra además9ad3a67; su fondo opcional tiene un paso separado abajo. Los cuatrobuilders producen13JSON sin cambio semántico.
3. Purga LiteSpeed/CDN por el procedimiento local habitual. Verificar URL limpia: la primera copia de caché mostró «oído» mal codificado; petición fresca/build están correctos. No cambiar las palabras ni reimportar contenido a ciegas.
4. Comprobar logos (gesto normal, cancelación, segundo dedo, reduced-motion), DNT sin eventos, hero Primera consulta y alineación móvil. QA local pasa, no demuestra despliegue. Fotos móviles existentes ya son95; el código cambia la política para generación futura, no necesita recomprimirlas ahora.
5. Rollback de código: respaldo de esos dos árboles, purga; no restaurar BD/wp-config ni otras aplicaciones como efecto de este rollback.

## Evidencia pendiente

1.2/3.6: inventario actual seguro de plugins activos/MU,temas,páginas/estado,menús y medios públicos con clasificación/destino. 1.5: idioma es_MX,formatos y booleanos de hardening en informe permitido,sin imprimir secretos ni copiar wp-config. Esto permitirá cerrar los3REVIEW; ningún crédito parcial.
Staging: idempotencia completa de setup-pages/Elementor,roles y calidad con editor del servidor (original1600a100,derivado95). Safari nativo no probado. GA4 puede perder clic si se abandona antes de descargar proveedor: se conserva D-073 y no se retrasa WhatsApp.
Sin nuevas preguntas de producto necesarias para desplegar. GSC/GBP,clínica/documentos siguen OWNER. Codex no desplegó. Historial de relevos debajo; no aplicar sus pendientes antiguos que contradigan esta entrega.

## MENSAJE PARA EL OTRO AGENTE · entrega Codex2026-10-12
Revisión completa:14de17DONE;1.2/1.5/3.6REVIEW por evidencia interna faltante. Progreso101/253,39,92%. Código correctivo8b2375c más integración9ad3a67/12e10c4 y revisión posterior del fondo, todos en HEAD de ccr-6254502d-xly8ww. Desplegar código con deploy-code.sh y purgar; para correctivos no se recarga contenido ni se regeneran fotos. Activar el fondo opcional de9ad requiere pc-fondo→setup-pages→purga y QA del fondo real, descrito en la integración final. PHP/PHPCS/JS/bash y fixtures pasan; no SMTP, publicación médica ni despliegue por Codex. Falta inventario/configuración permitidos para3REVIEW, staging/WordPress completo y Safari nativo. CuatroHTML locales de José preservados. Informe docs/REVISION_2026-10-12.md; evidencia docs/audit/2026-10-12/.

---

> **Producción 2026-10-12 18:05 (sesión local): edfa4f0.** José: «Listo, todo perfecto» tras probar en su Samsung los logos, el hero de Primera consulta y «Escucharte» (Q-039 VERIFIED). Aprobó con captura el bloque «Qué llevar» de Primera consulta: marca de borrador retirada en build-pages, setup-pages y purga; el HTML público tiene 0 «Borrador» y conserva la lista. Q-043 VERIFIED. La Dra. sigue sin terminar su revisión: fichas, 3 recursos y ORL/Sueño en borrador. Siguiente: José pasa a Codex `docs/CODEX_REVIEW_PROMPT.md`.

> **Producción 2026-10-12 17:55 (sesión local): desplegado hasta b64ee27** (D-074 de la nube, «Sí, pásalas» de José, y 1f413f0, títulos de Primera consulta dictados por José). (1) Títulos con setup-pages y purga: «<b>Qué</b> esperar / en tu <em>consulta</em>», «<b>Así</b> es la <em>consulta</em>», «<b>Llega</b> con lo que <em>ya tienes</em>», «<b>Costo,</b> pagos y <em>seguros</em>», «Agenda tu / <em>primera consulta</em>» (cursiva blanca sin color en el CTA). Color y cursiva verificados por estilo computado en 1440. (2) D-074: deploy-code (backup `~/deploy-backups/20261012-175129/`) y `wp media regenerate 358 359 360 --image_size=irina-movil`. Archivo móvil antes→después: fondo 358 1117→96 KB; área ORL 359 2268→577 KB; área sueño 360 1989→373 KB. Galaxy e iPhone (UA real): tarjetas en `…-1600x1600.webp?m=…` dentro de <picture>, 577/374 KB transferidos (antes servían la de 1536 px, 2107/1849 KB); fondo `…-1600x1067.webp?m=…`, 96 KB. 1440: tarjetas por su srcset (768 px, 538/473 KB, igual que antes, sin <source> aplicado); fondo 2400 px original. Recorte igual (object-position 100 % 50 %); consola limpia en los tres.

> **Producción 2026-10-12 17:35 (sesión local): desplegado hasta 9a9329a.** Decisiones de José en esta sesión: foto móvil a q95, GA4 y encaje diferidos, fuentes sin cambios (D-073), y que Codex audite las tareas en REVIEW. (1) D-073 (6870987): deploy-code (backup `~/deploy-backups/20261012-172506/`); `wp media regenerate 355 356 --image_size=irina-movil`: hero-home móvil 1175→262 KB, hero-dra 1475→263 KB. Fondo 358 y áreas 359/360 siguen a q100: la decisión de José era la foto principal y no se regeneraron. URL móvil con `?m=`. GA4 carga tras «load» (gtag.js presente y sin errores). Encaje del retrato: vacío al cargar y −37 px al llegar a la sección (antes −32 px; depende del texto medido). (2) Hero de Primera consulta como el de «Conóceme» con hero-irina3.png: adjunto 371 `hero-primera` (WebP q100 2040×1405, 2575 KB; móvil 1600 q95, 125 KB) y setup-pages. Encuadre 50 % 6 % en escritorio y 85 % 20 % en móvil, verificado en 1440 y en Galaxy; consola limpia. PHPCS no se ejecutó (sin PHP local): php -l remoto OK. (3) Encargo para Codex en `docs/CODEX_REVIEW_PROMPT.md`: 17 tareas REVIEW + auditoría de la entrega de hoy.

> **Producción 2026-10-12 16:52 (sesión local): desplegado hasta 0b7781d** (fff239c: relevo continuo al invertir tras un swipe a la derecha; fricción 0,4 intacta). Backup `~/deploy-backups/20261012-165217/`, purga; main.js servido contiene `ramp = -vel / speed`. Galaxy (UA real, toque CDP, ciclo de 1253 px desenrollado, ventanas de 100 ms): derecha 406→323→257→204→162→129→103→82→65→52→41→33→18→−3→−12→−21→−27 px/s, sin ventanas casi quietas y sin salto (cambio máx. 33,9 px/s entre ventanas, que es la propia frenada inicial); izquierda −492→…→−32 px/s y se funde con el autoplay a −26 px/s, sin ventanas quietas (máx. 37,1 px/s, también frenada inicial); consola limpia. Una primera medida por fotograma dio cifras falsas en el swipe a la izquierda (salto de envoltura del ciclo y orden de rAF): descartada. Falta la prueba de José en su Samsung.

> **Producción 2026-10-12 16:49 (sesión local): desplegado hasta 193c499** (logos un punto más fluidos, OK de José en el chat cloud: fricción 0,4^s; resto intacto). Backup `~/deploy-backups/20261012-164936/`, purga. Galaxy (UA real, toque CDP): swipe a la izquierda 360→286→228→181→144→115→91→72→58→46→36→29 px/s, ≈2,75 s hasta fundirse con el autoplay a 26 px/s, parada 0 ms; swipe a la derecha planea 421→34 px/s y se invierte con un tramo casi quieto de 50 ms en el cambio de sentido (es el cruce por velocidad cero de la rampa de 0,8 s, no un frenazo); consola limpia. Falta la prueba de José en su Samsung.

> **Producción 2026-10-12 16:23 (sesión local): desplegado hasta 280f968** (logos «más sueltos», petición de José: fricción 0,3^s en vez de 0,135^s, velocidad de salida con los últimos 70 ms, tope 5000 px/s; si la inercia va hacia la izquierda pasa el relevo al autoplay a velocidad plena, sin bajar a cero). Backup `~/deploy-backups/20261012-162325/`, purga. Galaxy (UA real, toque CDP): swipe a la izquierda 349→258→191→141→105→77→57→42→32 px/s y se funde con el autoplay a 29 px/s, sin paradas; swipe a la derecha planea y se invierte suave (parada máx. 33 ms); consola limpia. Falta la prueba de José en su Samsung.

> **Producción 2026-10-12 14:51 (sesión local): desplegado hasta fd9fb2e** (logos con inercia tipo scroll nativo al soltar el dedo: velocidad por ventana de 100 ms, fricción 0,135^s, empalme con el autoplay sin pausa). Backup `~/deploy-backups/20261012-145051/`, purga. Verificado en Galaxy (UA real, toque CDP): swipe rápido decae 337→204→124→75→46 px/s y enlaza con el autoplay a 26 px/s; parada máxima 33 ms; sin errores de consola. Antes, el mismo día: migas en franja bajo el hero + «Conóceme» (D-071), menú Inicio/Conóceme (D-072), tarjeta beige de Escucharte, endoscopio3 y retrato en WebP sin pérdida (plugin 0.4.5).

> **Cierre del sábado 2026-10-10, 15:45 (sesión local): producción = HEAD 3b78ece.** Último lote verificado: Escucharte de la Dra. con endoscopio grande (aside 898 px en 1440, 46 % visible en reposo bajo la tarjeta crema glass; en móvil sale por debajo de la tarjeta sin desbordamiento), marca «Borrador» retirada en ese bloque (D-058), lombrices con aire, logos arrastrables con tooltips, recursos en borrador, og-default, /recursos/ noindex. Backups `~/deploy-backups/20261010-153857/` y `…-154157/`. José retoma el lunes 2026-10-12. **Qué sigue** (ver NEXT): José → probar en su Samsung el arrastre de logos y el endoscopio; decidir calidad de fotos (q100 vigente: portada móvil 8,7 MB; q95 ≈ 2,5 MB); horario de la ficha de Google; aprobación de los bloques «Borrador» restantes en Dra./Primera consulta; archivar maquetas (D-060) tras cerrar la entrega. Dra. → revisar/aprobar las 26 fichas y los 3 recursos en la mini app; dictar artículos; segunda publicación. Técnico (cloud/Codex) → Lighthouse móvil (LCP del hero q100, gtag en idle, fuentes), Q-040 allowlist de Ops, Q-042 NAP centralizado, Q-043 marcas draft, Q-044/045 cerradas, pruebas en staging de roles/revisión, fetchpriority duplicado cerrado.

> **Producción 2026-10-10 15:33 (sesión local): desplegado hasta c5b47f7** (Escucharte de la Dra. con tarjeta glass a la derecha y endoscopio grande saliendo por la izquierda según el scroll, multiply en el contenedor; lombrices solo en la línea superior de credenciales/trayectoria cc8c0e3 + aire f2f462d; logos arrastrables 72951af; endoscopio adjunto 365). Backups `~/deploy-backups/20261010-150951/` … `…-153320/`. Verificado: --endo-p 0.14→0.68 al bajar; sin recuadro (píxel foto = página en móvil; diferencia 8 en 1440 sin recuadro visible); tarjeta sin sombra; lombriz→contenido 68 px (credenciales) y 24 px (trayectoria); 0 errores. Capturas `/preview/qa/endo-{1440,galaxy,iphone13}-{asomando,media}-20261010-1533.png`, `credenciales/trayectoria-{1440,390}-20261010-1529.png`. José: «Algo así» (visto bueno de la dirección).

> **Producción 2026-10-10 15:02 (sesión local): desplegado hasta 7a8fe3f** (logos con viewBox recortado ef587ce y tooltips; centro de recursos e8589c7/5a4b681; og-default ab0f4c4). Con OK de José: setup-medical (recursos 286/319/320 actualizados en draft con medical_review_required, área orl, fuentes), setup-og (ID 361, JPEG; Rank Math open_graph_image fijado; setup-og.sh corregido con `patch insert`), setup-dra, purgas. Verificado: og:image en /, Dra. y /recursos/; /recursos/ 200 con h1 y mensaje de «pronto», **pero meta robots index,follow (debía ser noindex sin recursos publicados: abierto para la nube)**; tooltips de logos OK en hover (1440) y toque (Galaxy), imágenes blancas; logos a 60/84/48 px. Capturas `/preview/qa/logos-{1440,galaxy,iphone13}-20261010-1449.png`, `logos-tooltip-{1440,galaxy}-20261010-1502.png`. Pendiente de José: fesormex.svg en positivo; nombres finales de los tooltips.

> **Producción 2026-10-10 14:46 (sesión local): desplegado hasta f270f4d** (logos D-066 + títulos de la Dra. c11d5c9 + plugin 0.4.4; backups `~/deploy-backups/20261010-144106/` y `…-144459/`; setup-dra; purga). Los 9 SVG (viewBox 178×100, blanco, trazados) están en `assets/brand/logos/` con los slugs. En vivo: `.di-logos` morado con h2 «Membresías y hospitales», 18 img (9×2) a 56/40 px, carrusel 70 s/45 s, consola limpia. Observación: los logos se ven pequeños (el arte no llena el viewBox) y fesormex.svg parece arte lineal con marco; propuesto a la nube subir el alto o recortar viewBox. Capturas `/preview/qa/logos-{1440,galaxy,iphone13}-20261010-1446.png`. También en vivo: migas con aire (1b65d60) y bloque overline/título/copy pegado a los botones en heros interiores (9e18d96).

> **Producción 2026-10-10 14:01 (sesión local): desplegado hasta c6b751c** (aa32c69: título ≤10vh, home sin fila de confianza a ≤760 px de alto, migas bajo la cabecera en heros cortos). Medido en 1440 con innerHeight real: home 828/900 y 677/749 con la marquesina asomando 72 px; Primera consulta 735/900 y 691/749, migas y=152; Dra. 828/900 (migas 185) y 719/749 (migas 152). Capturas `/preview/qa/peek-{home,primera,dra}-1440x{900,749}-20261010-1401.png`.

> **Producción 2026-10-10 13:54 (sesión local): desplegado hasta 7e71044** (q100 definitivo + `irina-movil` 1600 px con `<picture>`; heros con «peek»; migas sutiles). Fotos re-subidas a q100 (hero-home 355, hero-dra 356, dra-escucharte 357 PNG, bg 358, area-orl 359, area-sueno 360) + setup-site/setup-dra + purga; backups `~/deploy-backups/20261010-134047/` y `…-135249/`. Pesos medidos (transferSize) por dispositivo: escritorio → hero-home 3 391 KB, hero-dra 2 937 KB, fondo 3 133 KB, retrato PNG 725×1536 1 020 KB, áreas 768 px 538/473 KB, **total portada 8 567 KB**; Galaxy/iPhone → hero-home 1600×1067 **1 175 KB**, hero-dra 1600×1102 **1 475 KB**, fondo 1600 1 118 KB, retrato PNG completo 2 452 KB, áreas 1536 px 2 107/1 849 KB, **total portada 8 713 KB** (antes 13 MB con full en móvil; con q95 habría sido ≈ 2,5 MB). `<picture>` con 1 `<source>` en hero home, hero Dra. y fondo; `.di-bleed__bg:has(img)` sigue aplicando. Heros (viewport 1440×749 real en headless): home 957 px (contenido manda; no asoma la siguiente sección a esa altura), Dra. 766, Primera consulta 699 (min-height 677/512); iPhone 390×844: home 724 → la marquesina asoma 54 px sobre la barra; Dra. 724; Primera 538. Migas: 1440 Dra. y=144 (cabecera 112, eyebrow 182) centradas en el hueco; Primera consulta y=105–143 (pegadas a la cabecera: hueco corto); iPhone Dra. 183–236 en 2 líneas al 40 %, Primera 145. Capturas `/preview/qa/{home-hero,escucharte,dra-hero}-{1440,galaxy,iphone13}-20261010-1353.png` y `peek-{home,dra,primera}-{1440,iphone13}-20261010-1354.png`.

> **Producción 2026-10-10 13:23 (sesión local): plugin 0.4.2 (WebP q100 al subir, D-064) desplegado y las seis fotos re-subidas** (hero-home 343, hero-dra 344, dra-escucharte 345, dra-escucharte-bg 346, area-orl 347, area-sueno 348; setup-site + setup-dra + purga). Con la optimización de la CDN apagada por José (D-063), a cada dispositivo llega el archivo completo: hero-home WebP 2400×1600 **3 391 KB**, hero-dra WebP 2040×1405 **2 937 KB**, fondo WebP 2400×1600 **3 133 KB**, retrato PNG 1024×2170 (el WebP no pesó menos; alfa) **2 452 KB** en móvil / 1 020 KB (725×1536) en escritorio, tarjetas de área WebP 1536×1536 **2 107 / 1 849 KB** en móvil (768×768, 538/473 KB en escritorio). Portada en un teléfono ≈ **13 MB de imágenes** (antes ≈ 0,4 MB con la CDN reescalando). Calidad: íntegra. Capturas `/preview/qa/{home-hero,escucharte,dra-hero}-{1440,galaxy,iphone13}-20261010-1323.png`. **Decisión pendiente de José:** mantener q100 (peso) o bajar la calidad WebP a 85–90 (≈ 5–8× menos peso, sin diferencia visible) y/o reactivar el reescalado de la CDN a 2400 px.

> **Producción 2026-10-10 12:15 (sesión local): desplegado hasta bb15e45** (merge de 3f0f612 hero con fotografía + 2b4f32e versión en src/srcset de imágenes altas; antes 997dc17 y d5f8204). Respaldos `~/deploy-backups/20261010-121000/` y `…-121300/`; purgas hechas. Hero de la Dra. (foto definitiva hero-drairina2, adjunto 342 `hero-dra-v20261010120345.png`, focus 50% 0% / 95% 50%, clase `di-bleed--photo`): 1440 → foto a sangre sin parallax, rostro 147–314 bajo la cabecera (112), figura 883–1353 dentro, sombreado horizontal 90° en multiply (42 % → 0 % al 60 %); 390/360 → foto en banda superior (92–506) con la figura entera (172–371 / 143–342) y rostro libre (154–225), texto oscuro debajo sobre relleno #d3a98a, sin sombra; consola limpia. Capturas `/preview/qa/dra-hero-{1440,390,360}-20261010-1215.png`. Retrato de Escucharte con emulación de dispositivo (Galaxy S23 UA + client hints móviles; iPhone 13 UA): currentSrc `dra-escucharte.png?v=334-29fc94`, natural 360×762 / 390×826 (ratio 0.472 = completo), fit alfa activo (style.left −32 / −37 px), rostro 89 % / 93 %. Capturas `escucharte-{galaxy,iphone13}-20261010-1214.png`. José revisa en su Samsung y en el iPhone de la Dra.

> **Producción 2026-10-10 12:00 (sesión local): desplegado hasta e1943d1** (db8d1a8 tope de alto del retrato ≤430 px, 844229e encaje alfa/texto en main.js, e1943d1 docs) + 4452a92/2d8b6dc del mapa. Respaldo `~/deploy-backups/20261010-115947/`; purga hecha; sin recarga de contenido. Medido en vivo (consola limpia, carrusel 1 tarjeta/3 puntos, botón 52 px, retrato al fondo):
> 360 → sección 804, retrato 176–459 (283×600, `max-height` 600 px, `style.left` −32 px), rostro visible 89 %, palabra más cercana «respiratorios» a ≈5 px de la silueta; 390 → 757, retrato 189–490 (302×640, left −37 px), rostro 93 %; 430 → 717, retrato 230–522 (293×621, left −20 px), rostro 96 %; 1440 → sin cambios (849, sin `style.left`). Visual: rostro completo y silueta pegada al párrafo; la tarjeta de credencial pasa por encima del brazo (por diseño). **Aviso:** por el tope de alto, en 360 px el retrato mide 283×600 frente a los 334×708 de la versión anterior (≈15 % más pequeño); D-061/BATON dicen «sin reducir más la foto»: José decide si acepta el tope o se retira (`max-height`) manteniendo el encaje alfa. Capturas `/preview/qa/escucharte-{360,390,430,1440}-20261010-1200.png`.

> **Entrega vigente:** José autorizó acercar la silueta sin reducir más la foto. El HEAD posterior a db8d1a8 añade encaje alfa/texto y reserva del párrafo. Claude despliega código y purga; no recarga contenido. Ver sección final «Entrega vigente: silueta junto al texto». Q-039 requiere ambos teléfonos reales.

> Verificación más reciente: el CSS60/40 ya es público en las muestras móviles de Codex, pero José sigue viendo casi lo mismo. A360px la foto pasó deleft207,672 a207,797px y mantieneheight707,750px: el cambio fue prácticamente nulo. Ver docs/ESCUCHARTE_DISCREPANCIA_MOVIL_2026-10-10.md. Propuesta aislada−12px adicional solo en vista estrecha; sale del margen D-061 y Claude debe presentarla a José. No se modificó ni desplegó el tema en esta verificación. Q-039 abierto.

> **Producción 2026-10-10 11:07 (sesión local): desplegado hasta fdd6346** (incluye 30ef717 Escucharte 60/40 de Codex, 3a3c69e Map ID y eb1bd8f dedup de atributos). Respaldo `~/deploy-backups/20261010-110701/`; purga hecha. Verificado: hero con `loading`, `fetchpriority` y `decoding` ×1; mapa igual (sin Map ID: marcador clásico, 13 tiles, pin, consola limpia; `maps_map_id` no sale en REST). Escucharte 60/40 medido en vivo (grid 60/40, retrato a −5 px de la división, alto = fila, sin translateX, al fondo, consola limpia): 360 → sección 804, texto 16–213, retrato 208–542 (334×708), rostro visible 49 %; 390 → 735, texto 16–231, retrato 226–527 (301×639), rostro 67 %; 430 → 695, texto 17–255, retrato 250–532 (283×599), rostro 87 %; 1440 sin cambios (849). La silueta (brazos) pisa 4 px el borde de la columna de texto, no los glifos. Capturas `/preview/qa/escucharte-{360,390,430,1440}-20261010-1107.png`. Revisión visual de José pendiente.

> Indicación y entrega más recientes: D-061 y docs/ESCUCHARTE_60_40_2026-10-10.md sustituyen el gate facial anterior. Se implementó grid60/40, imagen al inicio de su columna con−5px, alto de fila y recorte parcial permitido. Pruebas locales pasan; no desplegado. Para este ajuste: deploy-code.sh → purga → capturas360/390/430/1440. Sin nuevos JSON ni recarga de contenido por este cambio. La auditoría general ya se desplegó hasta9053a4b según la sesión local; esta continuación no requiere repetir setup-site/pages/dra. Las preguntas siguen por Claude.

# BATON — Sesión local → Codex / Claude cloud

Fecha: 2026-10-10 11:00 (sesión local, PC de José). Rama `ccr-6254502d-xly8ww`. **Desplegado en producción hasta 9053a4b** (entrega de Codex 8a18346 + 4cf6e8e + 9053a4b). Respaldos: `~/deploy-backups/20261010-104348/` y `~/deploy-backups/20261010-105949/` (tema + plugin); mini apps con respaldo privado (deploy-briefing).

## Qué se hizo

- `deploy-code.sh` → `setup-site.sh` (168: 13 645 bytes, JSON legible de Codex) → `setup-pages.sh` → `setup-dra.sh` (169) → `deploy-briefing.sh` (mismo token, sin correo) → purga LiteSpeed. Lint PHP OK en los 28 archivos tocados por Codex.
- Decisiones del propietario registradas: D-057 (Escucharte: escala actual, silueta a 8 px, sin A/B), D-058 (borradores se mantienen), D-059 (migrar Maps con mapId), D-060 (archivar maquetas/legacy tras esta entrega).
- `4cf6e8e`: CSS móvil de Escucharte según D-057 (columna 56 %, retrato a todo el alto, `left: calc(56% + 8px)`, textos como en la versión aprobada). `9053a4b`: `sizes` del retrato a 100vw en móvil (con 60vw la CDN servía 215×457 px: borroso en 2×).

## Verificación en producción (2026-10-10)

- Páginas /, Dra., Primera consulta, Contacto, FAQ, Gracias, Aviso de privacidad → 200, un `h1` y un `main`, 0 avisos PHP. Plugin 0.4.1.
- REST `dra-irina/v1/practice`: sin `maps_api_key`, `contacto_copia` ni `horario`. FAQ: 1 `FAQPage` con 9 `Question`; 0 en Primera consulta. Home: 0 enlaces `?page_id=`. Mini apps: `guardar.php`/`revisar.php` sin token 403, `config.php` 403, `X-Robots-Tag: noindex, nofollow`.
- Hero: `loading` ×1, **`fetchpriority` ×2** en el HTML (abierto: una la añade WordPress a la primera imagen; el navegador ignora la repetida).
- Escucharte (D-057), silueta a 9 px del texto, sin translateX, retrato al fondo, figura sin tapar el texto, consola limpia:

| ancho | sección | columna texto | retrato (l–r, w×h) | rostro visible | captura |
|---|---|---|---|---|---|
| 360 | 804 | 16–200 (56 %) | 208–542, 334×708 | 49 % | `/preview/qa/escucharte-360-20261010-1100.png` |
| 390 | 804 | 16–216 | 224–558, 334×708 | 57 % | `escucharte-390-20261010-1100.png` |
| 430 | 735 | 17–239 | 247–548, 302×639 | 80 % | `escucharte-430-20261010-1100.png` |
| 1440 | 849 | sin cambios | 273–617, 344×729 | 100 % | `escucharte-1440-20261010-1055.png` |

Geometría: el PNG del retrato tiene el rostro entre el 22 % y el 70 % de su ancho y los brazos cruzados en el borde izquierdo del archivo (x ≈ 0). Con la escala actual (alto = columna), el rostro completo solo cabría en 360–390 px si la figura invadiera la columna de texto ≈ 100 px; por eso queda parcialmente fuera. Decisión pendiente del propietario.

## Para Codex / cloud

- No tocar `components.css` (bloque móvil de la Dra.), `doctora.php` ni el carrusel sin leer D-057.
- Abierto: `fetchpriority` duplicado en el hero (decidir si se retira el del tema en `irina_hero_image`); D-059 (migración Maps al recibir el Map ID); D-060 (archivo de maquetas) después de esta entrega.
- Pendientes de José/Dra.: foto definitiva del hero de la Dra., GA4 (`ga4_id`), Map ID, aprobación de fichas y de los bloques «Borrador», horario GBP.

---

> Relevo vigente tras la captura de José: D-057 sustituye la elección A/B. Claude implementó 4cf6e8e; Codex lo revisó y el gate móvil sigue abierto en360/390 (rostro≈45/56 %). Ver docs/REVISION_ESCUCHARTE_2026-10-10.md y sus comparaciones. No desplegar el ensayo de desplazamiento sin resolver el choque con el párrafo. Las preguntas al propietario las formula Claude. La entrega anterior se conserva debajo como historial técnico; sus instrucciones de elegir A/B ya no aplican.

# BATON — Codex → sesión local

Fecha: 2026-10-10. Rama: `ccr-6254502d-xly8ww`. Base: `d04e2cd`. Código/contenido hasta `e87c29b`; pruebas/documentación posteriores en HEAD. Sin despliegue por Codex.

## Entrega

| SHA | Cambio |
|---|---|
| b2f718b | Gate médico por capacidad, publish/future, nueva revisión ante edición técnica; datos ocultos/formulario |
| 7c622b1 | FAQ desde widget, JSON seguro, imágenes normalizadas, calidad100 y PHPCS |
| f8a58dd | Medios conservados, consultas idempotentes y enlaces de borradores con salida pública |
| b4b0716 | Mini apps sin correo, cuerpos acotados, respaldo y salidas sin token/respuestas |
| 03db275 | Escucharte A, contraste, carrusel estable, aria-invalid y mapa sin doble inicialización |
| 344ff4c | JSON/builds legibles, contenido semántico idéntico |
| 983d10a | Harness y evidencia de auditoría |
| e87c29b | Fechas válidas y títulos por defecto sin punto final |

Informe y preguntas: `docs/AUDITORIA_CODEX_2026-10-10.md`. FIXED_LOCAL no significa desplegado. Cambios locales previos de `tools/preview/{index,dra-irina,apnea,articulo}.html` están fuera de esta entrega: no sobreescribirlos con pull/checkout. Usar checkout limpio si hace falta; no desplegar maquetas por este lote.

## Despliegue por Claude local, con OK de José

1. Traer HEAD y comprobar commits/documentación. Si José no elige variante aún, el corte **b4b0716** contiene parches técnicos antes del CSS móvil; operarlo desde checkout limpio separado.
2. `bash tools/deploy/deploy-code.sh`: tema/core con respaldo fuera de webroot. No activar/desactivar plugins ni tocar Sensia.
3. `bash tools/deploy/setup-site.sh` → `bash tools/deploy/setup-pages.sh` → `bash tools/deploy/setup-dra.sh`. JSON solo cambia formato; recargar limpia cache Elementor y permite verificar normalización final. Los scripts conservan estados. Home no vuelve a noindex; Gracias/Links siguen excluidas.
4. `bash tools/deploy/deploy-briefing.sh`: respaldo privado, mismo token, sin correo incluso con notify antiguo. URLs en `tools/deploy/briefing-urls.local.txt`, ignorado. No pegarlo al chat ni repo.
5. Purga LiteSpeed/WP/Elementor según scripts; José purga CDN en hPanel sin cambiar configuración.
6. Medir viewport y HTML crudo. No ejecutar setup-medical, publish-approved ni go-live por esta entrega.

Sin importación de foto requerida. WebP es propuesta sin aplicar. GA4/SMTP fuera de este lote.

## Verificar producción después

- Páginas públicas200, 404 propia/noindex; un h1/main, skip link, sin avisos PHP/errores JS.
- Hero Home/Dra.: una copia de loading/fetchpriority en HTML original; DOM oculta duplicados. Si optimizador posterior los añade, registrar etapa, no compensar con CDN.
- REST: sin maps_api_key/Bcc ni horario oculto. Restricciones Maps ya confirmadas por José.
- FAQ: un FAQPage en FAQ, mismas nueve preguntas/respuestas; ninguno agregado a Primera consulta.
- Destinos de borradores de Home conducen temporalmente a Primera consulta; cuando se publiquen con aprobación resolverán permalink. No publicar para probarlo.
- Escucharte360/390/414/430/483/1440: gap16, rostro estimado≥80 %, bottom alineado, sin translateX/franja; una tarjeta/3puntos/anillo20px/giro4s; toque pausa8s, swipe y reduced motion sin autoplay; botón una línea. A: alto aprox668/635/628/633/610/849px. Retrato móvil con tope, no todo el alto literal: aceptación visual de José pendiente.
- Contraste foto real: overline4,9; botón6,46; párrafo7,42 en muestra360. Confirmar encuadre/fondo del borde.
- Mini apps: sin token403, config403, noindex/no-store; leer/guardar solo con acceso autorizado. No aprobar ni cambiar fichas de producción como prueba.
- Upload-media en copia WP: anterior conserva archivo/ID, nuevo slug y URL nueva; setup Dra./pages dos veces sin duplicar.

## Permisos autenticados: solo copia/staging

Editor técnico sin approve_medical_content: asignación REST de estado falla; publish/future de tres CPT no aprobados queda pending; cambios de contenido/meta aprobado exigen nueva revisión. Revisora: aprobar y editar por ID funciona. WP-CLI no aprueba al publicar. Comparar estado, contenido y auditoría sin usar datos/estados de producción.

## Formulario/SMTP: casos para sesión local

Codex no envió correos. En prueba con destinatario autorizado: JS/sinJS; consentimiento; email inválido; arrays; doble clic; honeypot sin correo; menos de4s; límite IP; origen ajeno; fallo de transporte; éxito303/JSON y Gracias noindex; correo HTML/texto/Bcc. En producción ejecutar solo casos autorizados expresamente por José: crean correos reales.

## Rollback y límites

Restaurar tema/core del respaldo de deploy-code y briefing del tar privado; purgar. JSON no cambió contenido; Codex no importó medios. Gate nuevo no cambia fichas al desplegar: actúa en futuras escrituras. No recuperar aprobación para texto modificado por técnicos sin nueva revisión.

PHP/PHPCS, builds, sintaxis y regresión aislada pasan. WP autenticado, SMTP, otros dispositivos, GSC y revisión médica/legal pendientes. A/B usa HTML público con assets locales: no demuestra despliegue. Registrar SHA desplegado, hora, medidas y cierre Q por Q.

## MENSAJE PARA EL OTRO AGENTE

Entrega en ccr-6254502d-xly8ww, código hasta e87c29b, documentación posterior en HEAD. Traer rama desde checkout limpio; deploy-code → setup-site → setup-pages → setup-dra → deploy-briefing → purga. Esperar OK de José para A; corte técnico sin ella: b4b0716. Verificar FAQ, horario REST, hero crudo, enlaces y Escucharte. No publicar clínica, tocar CDN/Sensia ni pegar tokens. Devolver SHA y evidencia en QA/BATON.

## MENSAJE ACTUAL PARA CLAUDE

La escala grande es la instrucción de José; no reducir de nuevo. 4cf6e8e mueve la caja8px frente a lo publicado y no recupera suficiente rostro en360/390. Prueba adicional sobre ese commit: mover48px a360 sube el rostro a≈86 % conservando tamaño, pero cruza≈4,9 % de las cajas del párrafo; a390 mover32px alcanza≈83 % con choque menor. Usar esta evidencia para una composición que deje libre el hombro, no como CSS listo para desplegar. Codex no tocó components.css ni JS mientras Claude construye. Gate Q-039 abierto; preguntas por Claude, sin nuevos interrogatorios desde Codex.

## MENSAJE ACTUAL — referencia60/40

José precisó las dos columnas y acepta rostro parcial. Codex implementó solo el bloque móvil de components.css: grid60/40, gap0, texto en columna1, retrato en columna2 conleft−5px, imagenheight100% ywidthauto. Sin interferencias de alfa con párrafo a360/390/430; tres tarjetas estables y escritorio conservado. No exigir ahora80 % de rostro ni pedir elegir A/B. Ver medidas/capturas en docs/ESCUCHARTE_60_40_2026-10-10.md. Desplegar por el flujo local y verificar antes de cerrar Q-039 como producción.

### Integración con el relevo concurrente de Claude

Se conservaron los commits9053a4b (sizes100vw para nitidez) y a1b6b83 (despliegue previo y decisionesD-057..D-060). D-058 sigue siendo la decisión de mantener borradores; D-059 ya autoriza migrar Maps cuando llegue el mapId; D-060 ya autoriza archivar tras cerrar la entrega. La nueva referencia60/40 usaD-061 para no reutilizar esos IDs. Último CSS de esta continuación:30ef717. No repetir preguntas ya respondidas.

## MENSAJE ACTUAL — resultado del teléfono

No asumir CSS viejo por el informe de José: la geometría nueva a360 es casi idéntica a la anterior. La captura390 mejoraba por la menor altura de la figura, no por moverla a la izquierda. Se verificó producción con perfil móvil/DPR2 y se dejó una propuesta concreta deleft−17px a≤360, actualmente fuera del tema. Claude consulta esa desviación de−2/−5 con José y coordina la comparación en su ancho/zoom real. No desplegar narrow-position-proposal.css como si estuviera aprobado. Las tres tarjetas pasan en producción; el problema sigue siendo la composición percibida.

## Ajuste de altura móvil autorizado · 2026-10-10
José autorizó corregir la dependencia entre renglones y escala del retrato. En <=430px, max-height: clamp(600px,164vw,640px) limita la imagen; mantiene altura100% cuando la fila es menor, proporción, fondo, texto, grid60/40, offset−5 y anclaje inferior. A360 pasa de708 a600px; a390 normal conserva639px. Esta reducción limitada en vistas estrechas sustituye el alto literal de D-057/D-061 por la autorización más reciente; no introduce las variantes A/B anteriores.
Pruebas: columns.cjs (tres tarjetas,360/390/430/768/1440), portrait-cap.cjs (320/360/390/430 y raíz tipográfica20px). Evidencia: docs/audit/2026-10-10/portrait-cap.json y portrait-cap-360.png. HTML público con CSS local: no demuestra despliegue ni el comportamiento del teléfono real. Q-039 continúa abierto hasta revisión real.
Claude local: desplegar solo código con tools/deploy/deploy-code.sh, purgar y verificar teléfono; no regenerar contenido por este CSS. Codex no despliega. Preguntas por Claude.

## Entrega vigente: silueta junto al texto · 2026-10-10
Autorización literal más reciente de José: «corrígelo para que la silueta quede casi pegada al texto en ambos teléfonos, sin hacer más pequeña a la doctora. Déjalo listo para que Claude lo despliegue».
Se conserva la escala de db8d1a8; no se reduce más. Hasta430px, el párrafo reserva24px a la derecha dentro de su columna60% (mismas palabras/fuentes). main.js calcula el encaje a partir de128 bandas conservadoras del canal alfa del retrato actual1024×2170 y las cajas de cada palabra del párrafo/cita/eyebrow, con4px de separación. Se recalcula al cargar imagen/fuentes, resize, ResizeObserver y fin de revelado. Solo cambia left de la imagen; altura, proporción y anclaje inferior permanecen. Tarjetas/botón conservan su fondo y capa superior. >430px vuelve al CSS previo; escritorio/tablet sin ajuste nuevo. No cambia fondo, marca, menú, sombras ni lógica del carrusel.
Evidencia normal320/360/390/393/412/430: silhouette-fit-metrics.json. Letras aumentadas20px: silhouette-large-metrics.json. En360, imagen600px y left175,797 (antes207,797); en390, imagen639,594 y left188,797 (antes225,797); en430 la nueva envoltura aumenta el alto621,078, nunca reduce la figura. Intersección del alfa con cajas del párrafo0% en las doce muestras; es verificación geométrica conservadora, no certificación visual de ambos dispositivos ni WCAG completa. columns.cjs verifica tres tarjetas, estabilidad, anclaje y sin overflow/error JS en360/390/430/768/1440. Node check main y harness pasa; sin PHP/build/JSON de contenido afectados.
El perfil está ligado al retrato actual; si cambia la fotografía debe regenerarse (portrait-position.cjs --profile), incluso si conserva proporción. El guard de proporción evita aplicarlo a una imagen de formato diferente. Sin JS conserva la posición CSS anterior como fallback.
Claude local: deploy-code.sh del HEAD de esta entrega y purga; NO setup-site/pages/dra ni SMTP. Verificar en los dos teléfonos reales; Q-039 sigue abierto hasta esa revisión. Preguntas por Claude. Codex no despliega. HTML público con assets locales no demuestra despliegue. Esta entrega sustituye la propuesta aislada−12px y el offset fijo como criterio de aceptación; conserva60/40 y prioriza la silueta visible por indicación actual.

## Integración concurrente de Claude · 9ad3a67
Durante el push llegó9ad3a67 (fondo decorativo de Qué llevar/FAQ de Primera consulta), integrado sin perder cambios en12e10c4. Se conserva su build/JSON,CSS,widgets,helper y setup-pages. Los cincoPHP nuevos/modificados pasan sintaxis; PHPCS detectó un aviso de alineación en narrative.php, corregido; bash-n setup-pages pasa. Build-pages regenerado conserva exactamente la semántica recibida; solo se añadieron campos bg_image, sin cambios de textos/estados. Fixture con/sinID de hero y fondo deja0placeholders; helper sinID no emitebloque. No se certificó la foto definitiva de pc-fondo ni su contraste/encuadre: la evidencia pública y las capturas anteriores son ANTES de ese fondo, aún sin desplegar según Claude. No cambia el veredicto de las17tareas.
La corrección8b2375c no requiere contenido nuevo. Para activar también el fondo de Claude: archivo aprobado con `bash tools/deploy/upload-media.sh <archivo_aprobado> pc-fondo "Fondo de Primera consulta"`, luego `bash tools/deploy/setup-pages.sh` (conserva estados) y purga. Esa activación es una operación separada de los correctivos de auditoría; validar el fondo real en360/393/430/1440, lectura del texto y ausencia de franjas antes de cerrar su QA. No publicar pilares/CPT por ello. El deploy-code del HEAD incluye el soporte opcional integrado, aunque aún no se asigne el adjunto.

Integración al subir: commit concurrente91c1b09 preservado (fase5).5.1/5.2/5.3/5.5 pasan aREVIEW y Q-042FIXED_LOCAL, sin auditarlos en este lote ni sumarDONE. El avance sigue204/230=88,70%;78/87DONE. Sus cambios de código y linters conservan su propia evidencia y autoría.
