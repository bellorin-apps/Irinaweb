# Q-081: ratificación en producción — Codex, 2026-10-20

## Veredicto vigente

**Q-081 VERIFIED** sobre la entrega165995e. Código declarado desplegado4dcdc13 desde árbol e129922; sus árboles wp-content son idénticos (a3ab77264093162394b68aff967f2e3721f4c954). Se supera la comprobación histórica sin entrega de e129922: entonces no se había ejecutado; ahora la evidencia documenta ejecución y resultados. No repetir despliegue ni publicación.

## Comprobaciones independientes de Codex

Se reutilizaron las suites de navegación revisadas con runtime disponible, base pública y contextos nuevos mediante tools/audit/q081/review-production.cjs. **37/37** pasan en producción y el caso adicional **EN→ES** confirma rutaES, chosen guardado, contenedor presente y aviso oculto. Sin envíos de formulario ni cambios del servidor; localStorage se usa solo en contextos de prueba desechables.

Se comprueban idioma principal, teclado/Escape, persistencia, almacenamiento bloqueado, selector, Gracias/sin pareja/EN, móvil390x844, horizontal844x390, escritorio1440x900 y ocultación al abrir menú. No se probaron dispositivos físicos, Safari oFirefox. La captura móvil fue inspeccionada: barra inferior libre; **P3 conocida conservada**, el aviso cubre el borde inferior del botón WhatsApp del hero. Se puede cerrar. No se afirma ausencia de todo solapamiento.

GET independientes confirman contenedor oculto en ES con pareja y URLEN correspondiente, sin contenedor en Gracias, /links/ o EN. Cuatro consultas de Primera consulta con Accept-Language en-US/es-MX: **4hit y el mismo SHA-256 del HTML completo**. Esto acredita consistencia de esas respuestas de caché, no todos los estados futuros de LiteSpeed.

Evidencia nueva: docs/audit/2026-10-20-q081-codex-prod/browser.log, public.json y capturas. Los archivos aportados estado-antes/después son idénticos,16publish; cuatro prefijos MD5 de servidor/base coinciden. Es un cotejo de artefactos privados aportados, no lectura independiente de base o archivos remotos; los prefijos no equivalen a hashes completos revalidados por Codex.

La ejecución, respaldo ~/deploy-backups/20261020-173714/ (464 K,30tablas y plugin/tema), purga y ausencia de manipulación temporal se aceptan por atestación del propietario/Claude en docs/audit/2026-10-20-q081-prod/. Codex no accede al respaldo ni ejecuta herramientas remotas. Los registros de estado son huellas recogidas, no copia completa de la base. Consola/imágenes/Doctoralia y ausencia de error_log se aceptan además de la evidencia aportada; no tener error_log no prueba ausencia universal de errores.

## Regresión y cierre

Codex repitió seo_check.py --base https://drairinagonzalez.com --prod: salida0 y sitemap30 (15EN+15ES), sin fallos. C-seo.json y seo.log en el directorio nuevo de evidencia. La salida aportada por Claude también pasa.

Producción declarada4dcdc13. Inglés19/19 (100%,7/7DONE); español221/228 (96,93%,83/86DONE), sin aumento. No ejecutar deploy-code.sh,setup-en ni publish-en por este cierre. Conservar contenido aprobado, aprobaciones y estados publicados, Doctoraliaapagado. La P3 visual queda como observación de mantenimiento separada y no se corrige sin alcance propio.

Codex no desplegó ni publicó, no probó formularios/SMTP, accedió a secretos/cuentas/Sensia ni modificó contenido. CuatroHTMLdeJoséintactos/excluidos. La repetición de las pruebas de navegador no activa servicios ni el widgetDoctoralia.

---

## Registro histórico anterior (superado por entrega165995e)

# Q-081: auditoría de despliegue solicitada — Codex, 2026-10-20

## Veredicto

**REVIEW del despliegue solicitado; Q-081 conserva VERIFIED_LOCAL. No procede ratificar VERIFIED en producción con la evidencia disponible.**

Pull: Already up to date en4dcdc13. Consulta de ramas remotas: solo ccr-6254502d-xly8ww en4dcdc138ae32167e002ce89fe2a2f6bd43c37fae. No hay commit nuevo con ejecución, respaldo, SHA desplegado, estados antes/después ni verificaciones posteriores a ese GO. Los documentos todavía dicen que Q-081 no está desplegado.

GET público con Accept-Language en-US: /primera-consulta/200 sin contenedor di-en-hint (LiteSpeed miss), /en/first-visit/200 sin contenedor (hit), /gracias/200 sin contenedor (hit). La ausencia en EN/Gracias es esperada; en Primera consulta no acredita el despliegue del aviso, cuya elegibilidad se emite en servidor con independencia del idioma del navegador. No se infiere un SHA ni se descarta totalmente una entrega con problemas de caché a partir de estos GET.

Evidencia: docs/audit/2026-10-20-q081-deploy-pending/public.json. Solo lecturas públicas y Git; sin ensayo remoto, envío de formularios o alteración del navegador del usuario. No se repiten pruebas locales del código inalterado para simular verificación en producción.

## Pendiente para Claude

Indicar/subir SHA de la nueva entrega y evidencia del despliegue (respaldo, purga, código de4dcdc13 o posterior), reglas de elegibilidad/idioma, elección desdeEN/ES, cierre/teclado/storage bloqueado, caché hit/miss, muestra móvil/escritorio y P3 del hero, regresión seo_check --prod,16estados/huellas intactos. Si ya desplegó, no repetirlo por esta solicitud; aportar evidencia. El GO previo sigue vigente, pero no equivale a una ejecución.

Inglés19/19 y español221/228 sin cambio. No tocar contenido/aprobaciones/estados ni ejecutar setup-en/publish-en. Doctoraliaapagado. Codex no despliega ni publica, no accede a secretos/cuentas/Sensia; cuatroHTMLdeJoséintactos/excluidos.
