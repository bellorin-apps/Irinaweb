# Ratificación lanzamiento EN y GO Q-080 · Codex · 2026-10-20

Basebef064b, rama ccr-6254502d-xly8ww; revisados7a0001a,8c5f7f9,bef064b. D-103 contiene orden expresa de José y supera la espera histórica de D-098. Codex no ejecuta despliegue ni publicación.

| Elemento | Veredicto |
|---|---|
|13.4|REVIEW→DONE:5fichas aprobadas y publicadas bajo /en/sleep/, con200/schema/parejasES comprobados.|
|13.5|REVIEW→DONE:verificador --prod repetido por Codex sobre HTTPpúblico, salida0, sitemap30(15EN+15ES), canónicas/OG/hreflang/selector/schema/enlaces y auxiliares correctos.|
|13.7|REVIEW→DONE:lanzamiento D-103 autorizado y atestado, respaldo20261020-154356/452K/30tablas,16publicadas,purga; verificación pública repetida. IndexNow aceptado D-100.|
|Q-080|VERIFIED_LOCAL ratificado; GO de código. No afirmar desplegado ni corregido en producción todavía.|

## Evidencia de lanzamiento y límites

Fuentes: docs/audit/2026-10-20-en-prod/lanzamiento.md,seo.log,C-seo.json. Aprobaciones reales/16estadospublish/respaldo/operaciones remotas por atestación del propietario yClaude; Codex no accede a cuentas, base, respaldo o secretos. Comprobación pública independiente nueva: docs/audit/2026-10-20-codex/C-seo.json, generada con seo_check.py --base https://drairinagonzalez.com --prod, sin fallos. Son GET públicos, no formulario/SMTP. La comprobación no certifica por HTTPelSHA desplegado ni restaura respaldos.

La evidencia visual aportada cubre9páginas y móvil375deSleep/ENT; no16rutas en todos los anchos. WhatsAppEN, consola/imágenes yDoctoraliaapagado por atestación de la sesión local. No se exige repetir publicación para ratificarla. Q-080P3 es posterior al gate, no bloqueante. La portadaES conserva es(P3). La publicación sigue siendo secuencial/compensatoria, no atómica ni reversible respecto a avisosIndexNow.

## Q-080: revisión

El único delta de producto desde la base lanzada es themes/irina-gonzalez/inc/i18n.php. Traduce en runtime el nombre profesional del404y alts conocidos cuando irina_lang()=en; ES, alts desconocidos/vacíos e inglés previo se conservan. No escribe adjuntos, contenido aprobado ni estados. El mapa exacto debe actualizarse si cambian los alts fuente; no es traducción universal.

PHP lint yPHPCS pasan. tools/audit/review-q080.php ejecuta callbacks reales con dobles sintéticos:14/14, incluidos404/imagen/OG/ES/nonstrings. Evidencia docs/audit/2026-10-20-codex/q080-callbacks.json. No es una repetición de WordPress/RankMath. La integración real y existencia del hook og_image_alt enRankMath1.0.280 se aceptan de docs/audit/2026-10-20-q080/local.log y la atestación del propietario. Ese log acredita og:image:alt; twitter:image:alt aparece vacío, por lo que no se certifica su emisión real aunque el callback esté cubierto sintéticamente.

## GO limitado a código

**GO para que Claude local ejecute deploy-code.sh con respaldo verificado y comprobación posterior**, sin tocar contenido aprobado. El script sincroniza plugin ytema; el plugin no tiene delta de producto en esta entrega. No describirlo como un script que solo copia el tema. Confirmar rama ySHA antes de ejecutar. No ejecutar setup-en,publish-en,regenerar contenido remoto,alterar aprobaciones odespublicar. EN ya está publicado por D-103: conservar sus estados actuales. Doctoraliaapagado.

Después: verificar404EN con nombre inglés y404ESsin cambios, imagenalt deFirstvisitEN yOGaltEN, altsES sin cambio; repetirseo_check --prod y comprobar30URLsinGracias, contenido/16estadospublish sin alteraciones, imágenes/menús/errores. Registrar respaldo/SHA/evidencia. Q-080 solo pasa a verificado en producción con esa entrega; no repetir el lanzamientoEN. Codex no despliega.

PLAN/PROGRESS/STATUS/NEXT/QA/BATON actualizados. Inglés **19/19=100%,7/7DONE**; español **221/228=96,93%,83/86DONE**, sin cambio. Cierre no borra el seguimiento de mantenimiento niQ-080pendiente de verificación publicada. CuatroHTMLdeJoséintactos/excluidos; sin secretos/cuentas/Sensia.
