# Auditoría de c151258, 1f5e18e y 62d920e — Codex, 2026-10-19

Entrega recibida en `ccr-6254502d-xly8ww`, HEAD `62d920e`, pull al día. Estos commits son posteriores a las comprobaciones históricas sin entrega en bc2deb0/0d220f0; sustituyen sus pendientes, sin alterar el registro de lo observado entonces.

| Hallazgo | Veredicto | Evidencia |
|---|---|---|
| Q-077 | **VERIFIED_LOCAL ratificado** | `1f5e18e` corrige Graph.php; `62d920e` aporta integración WP/Rank Math reales y salida 0. Codex repite offline evaluate sobre C-seo-1f5e18e.json: cero fallos/avisos, 15 EN con en-US, WebSite/WebPage en /en/, ProfilePage y un Physician en About EN, sitemap30. |
| Q-078 | **ACCEPTED, D-100** | Decisión expresa de José registrada en DECISIONS y confirmada por su mensaje: acepta IndexNow tal como está. Sin cambio de configuración. No requiere otra decisión ni prueba de una mitigación que no se implementará. El efecto externo no reversible permanece como límite aceptado. |
| Q-079 | **FIX / OPEN, corrección parcial** | El preparador mejora y pasa9/9; se reproduce un bypass de aislamiento en sync.sh que los casos aportados no cubren. Ver detalle debajo. |

## Q-077: revisión y límites

Graph usa Language::is_page para About en ambos idiomas; añade ProfilePage/ContactPage con ID e idioma y WebPage para páginas sin nodo específico. La raíz EN emite WebSite/WebPage y elimina esos tipos duplicados de Rank Math en esa ruta. Las ramas específicas de áreas, fichas y FAQ se conservan. PHP lint y PHPCS pasan.

El verificador ahora exige en-US y paridad de tipos con ES y devuelve salida1 ante fallos. Se repiten el caso base y10/10 mutaciones negativas, sin servidor. El cotejo del JSON confirma lo declarado en el log seo-1f5e18e.log. Ese formato resumido no conserva todos los IDs/referencias del grafo: el cotejo no certifica referencias o ausencia de duplicados más allá de los campos recogidos. No se vuelve a ejecutar WordPress/publicación.

La portada ES conserva `["es"]`, observación P3 no bloqueante ya declarada; no afirmar que todas las rutas ES llevan exactamente es-MX. La primera integración tuvo sitemap29 por caché, luego30 tras regeneración; conservar comprobación del sitemap/caché en el gate autorizado. **13.5 sigue REVIEW por verificación publicada pendiente; 13.7 DOING y13.6 OWNER**. Las aprobaciones de la copia son sintéticas.

## Q-079: bypass reproducido

`mkdeploy.sh` excluye privados antes de copiar, resuelve raíz con git y comprueba el destino deploy. Su suite repetida pasa **9/9**, incluido enlace final. La variante con-elementor ya se retiró.

Sin embargo, `sync.sh` valida LAB y solo comprueba `-L` en el destino final antes de `rm -rf`. Un enlace en `LAB/site/wp-content` dirige `themes/irina-gonzalez` y `plugins/dra-irina-core` a otra carpeta, aunque los destinos finales no sean enlaces. En una fixture sintética de Codex, sync devuelve **0** y borra/reemplaza los dos marcadores fuera del laboratorio. Todos los destinos destructivos de la reproducción se verificaron dentro de la carpeta desechable; ningún dato real o laboratorio existente se tocó.

Reproducción: `tools/audit/review-q077-q079.py`; resultado `docs/audit/2026-10-19-q077-q079/review.json`, campo `sync_ancestor_link`: exit0 y marcadores intactos=false. El test espera el fallo de aislamiento para documentar esta versión; no debe interpretarse su éxito como aprobación del helper.

**Corrección pendiente para Claude:** validar todos los componentes del destino antes de borrar o copiar, rechazar enlaces intermedios y comprobar resolución física dentro del laboratorio esperado. Prevalidar ambos destinos antes de modificar el primero. Añadir pruebas de enlaces en site/wp-content/themes/plugins, preservación de marcadores y rechazo sin cambios. Revisar también equivalentes de enlaces/junctions en Windows y ajustar las afirmaciones de README. No ejecutar sync sobre el laboratorio real mientras siga pendiente. La sintaxis de mkdeploy/sync/selftest pasa; no demuestra aislamiento.

No se constató exposición de secretos. El hallazgo limita el aislamiento del helper; no invalida por sí mismo el JSON SEO ya obtenido. No se ha corregido sync en esta ronda de auditoría.

## Entrega y restricciones

QA, PLAN, STATUS, NEXT, BATON y criterio de decisiones actualizados; PROGRESS regenerado sin aumento: español221/228,83/86DONE; inglés9/19,3/7DONE. Q-076 conserva VERIFIED_LOCAL. Doctoralia apagado. Sin despliegue, publicación, formularios/SMTP, cuentas, secretos ni Sensia. Cuatro HTML de José intactos y fuera del commit.

## GO de código según D-101 · base 72dbf9a

Pull al día en72dbf9a; el único cambio desde acc4cdd es documental (D-101, BATON y NEXT). El código del producto y los ensayos de la auditoría anterior siguen siendo los mismos. **GO para que Claude local ejecute únicamente la entrega de código autorizada por D-101, con el inglés en borrador.** No es GO de publicación ni cierre de13.5/13.7.

Se inspeccionó deploy-code.sh sin ejecutarlo ni leer su configuración: comprueba un respaldo privado de base antes de continuar y respalda/sincroniza exclusivamente dra-irina-core e irina-gonzalez. No llama sync.sh del laboratorio, setup-en ni publish-en. Por tanto el FIX restante de Q-079 no bloquea este alcance: no reutilizar ese helper inseguro. El publicador corregido de tools/deploy queda actualizado en la copia local por git; deploy-code.sh no lo copia al servidor.

Claude local: hacer pull, confirmar rama y borradores por comprobaciones de lectura sin --context=admin, ejecutar tools/deploy/deploy-code.sh con su respaldo previo y verificar en vivo. Confirmar /en/contact/ sin seguir redirecciones (404 en borrador, sin301 a ES), ausencia de selector/hreflang/sitemap EN y que las16entradasEN conservan borrador. Revisar schema español sin duplicados y con idioma esperado (portadaES es, P3 aceptado), páginas ES, fotos/menús y ausencia de errores nuevos. Doctoralia sigue apagado. Si falla, revisar/restaurar código con el procedimiento documentado; no publicar para resolverlo. Registrar SHA efectivamente entregado y evidencia de verificación; no atribuir producción al presente GO.

No ejecutar setup-en/publish-en ni formularios/SMTP. Publicar EN continúa sujeto a13.6 y orden expresa D-098. Codex no despliega. Progreso sin cambio; Q-077VERIFIED_LOCAL, Q-078ACCEPTED D-100 y Q-079FIX/OPEN.
