# STATUS — Dra. Irina González Sáez

**Corrección del aviso de caché — Codex, 2026-10-21:** auditado 2664c93. La herramienta normaliza los nombres de cabecera a minúsculas tanto en respuestas correctas como HTTPError; no cambia producto ni servidor. Autoprueba y seo_check --prod repetidos: salida 0, sin avisos. Se retira el límite anterior de las tres rutas EN de Sueño: era un falso aviso por mayúsculas, no ausencia acreditada de caché. Evidencia nueva: `docs/audit/2026-10-21-barra-horizontal-codex-prod/seo-cabecera-corregida.json`. La mejora horizontal conserva VERIFIED y el avance no cambia.


**Barra móvil horizontal VERIFIED en producción — Codex, 2026-10-21.** Entrega 7b8b6a2; código declarado c61ac01. Repetidos 7/7 de alcance en vivo, CSS público idéntico al repositorio, cuatro cache hit/HTML igual entre idiomas y SEO público sin fallos (sitemap 30). Matrices 210/210 y 132/132, regresión Q-081 y 16 registros idénticos cotejados como evidencia aportada. No repetir despliegue ni publicación ni cambiar contenido aprobado. Inglés 19/19; español 221/228, sin cambio. Límites: Chrome emulado; tres rutas de Sueño EN sin cabecera de caché en esta lectura. Informe `docs/REVISION_BARRA_HORIZONTAL_PRODUCCION_2026-10-21.md`.

## Historial previo

**Barra móvil horizontal — auditoría Codex de4411dda,21-oct:** mejora verificada en pruebas y GO de código con respaldo/verificación posterior.132/132 independientes con HTMLpúblico/CSS candidato,43sustituciones; ESLint/stylelint pasan.0/210local y regresionesQ-081 aportadas cotejadas. Sin cambios adicionales de producto en auditoría. No desplegada porCodex; no afirmar resolución en producción. Informe `docs/REVISION_BARRA_HORIZONTAL_2026-10-21.md`. Avance sin cambio.

## Historial previo

**P3 de Q-081 VERIFIED en producción — Codex, entrega1e91d88.** Código declarado f2e0603. Transiciones9/9 repetidas en vivo; JS/CSS públicos idénticos; caché4hit/HTMLigual entre idiomas y SEO público sin fallos/sitemap30. Logs63/63 y37/37 y16estados cotejados. No repetir despliegue/publicación; sin cambios de contenido. Cruce previo CTA/columna horizontal separado. Informe `docs/REVISION_Q081_P3_PRODUCCION_2026-10-20.md`. Avance sin cambio.

## Historial previo

**Auditoría P3 de Q-081 — Codex, base b465e4e.** Mejora verificada en pruebas con ajustes de auditoría: ocultación inmediata y recálculo al cerrar menú.63/63,37/37 y6/6 transiciones pasan; ESLint/stylelint OK. GO solo al árbol corregido, con respaldo y verificación posterior; no b465e4e aislado. No se desplegó. Q-081 publicado conserva VERIFIED, mejora P3 pendiente de producción. Informe `docs/REVISION_Q081_P3_2026-10-20.md`.

## Historial previo

**Q-081 VERIFIED — Codex, entrega165995e.** Código declarado4dcdc13, igual en wp-content a e129922. Repetidos37/37 y EN→ES en producción; caché4hit con HTML idéntico entre idiomas; seo_check --prod sin fallos, sitemap30.16registros iguales. P3 conocida del borde del CTA móvil conservada. No repetir despliegue/publicación ni modificar contenido. Inglés19/19; español221/228, sin cambio. Informe `docs/REVISION_Q081_POSTDEPLOY_2026-10-20.md`.

## Historial previo

**Reconsulta de despliegue Q-081 (Codex):** remoto aún en4dcdc13, sin entrega posterior al GO. GET de Primera consulta devuelve200/cache miss sin contenedor del aviso. Despliegue en REVIEW por falta de evidencia; Q-081 conserva VERIFIED_LOCAL. Falta SHA/evidencia de Claude; no repetir una entrega ya hecha. Informe `docs/REVISION_Q081_POSTDEPLOY_2026-10-20.md`.

**Auditoría Codex de 490b0e4:** despliegue 1c685c4 ratificado con HTTP y regresión SEO sin fallos. Q-081 VERIFIED_LOCAL con ajuste de persistencia del selector desde EN; 10 pruebas independientes, PHP/PHPCS/JS y ESLint/stylelint pasan. GO al árbol corregido de esta auditoría, con respaldo y verificación posterior; no a 71ae61c aislado. P3 de solapamiento del borde del CTA móvil conservada. Informe `docs/REVISION_1C685C4_Q081_2026-10-20.md`. Progreso sin cambio.

## Historial previo

**Q-080 VERIFIED — Codex, 20-oct, entrega a3e873a.** Producción declarada 1d3b6cf. Comprobación pública de títulos/alts/OG/ES y seo_check --prod sin fallos; 16 registros de estado idénticos antes/después. Respaldo y ejecución atestados. No repetir despliegue ni publicación. Inglés 19/19 (100 %); español 221/228, sin cambio. Informe `docs/REVISION_Q080_PRODUCCION_2026-10-20.md`.

## Historial previo

**Auditoría Codex de bef064b,20-oct:**13.4/13.5/13.7DONE; inglés19/19=100%,7/7DONE. LanzamientoD-103 ratificado, públicoENverificado conseo_check --prod salida0/sitemap30. Q-080VERIFIED_LOCAL:GO para deploy-code.sh conrespaldo yverificación, sin contenido/aprobaciones/estados. El scriptcopia plugin/tema; único delta producto en i18n.php deltema. No ejecutar setup/publicación; ENya publicado. Informe docs/REVISION_LANZAMIENTO_EN_Q080_2026-10-20.md.

## Historial previo

**Ratificación Codex de72a2288:13.6DONE.**8páginas aprobadas por laDra.,5fichas medically_approved y legalesD-099; contenido sincronizado según simulación, build local sin diferencias. Inglés11/19(57,89%),4/7DONE; español221/228sin cambio.13.5REVIEW/13.7DOING. **Falta orden expresa de José para publicar**: mantener16ENdraft yDoctoraliaapagado. Informe docs/REVISION_13_6_2026-10-19.md.

## Historial previo

**D-101 COMPLETADO Y VERIFICADO (auditoría Codex de aa821b1):** ejecución por José16:38 sobre9b52e1e, respaldo/purga atestados. HTTP independiente:15ES200/schemaOK, sitemap15/0EN,10EN404sinredirigir (Q-075), sin navegaciónEN niDoctoralia.16ENdraft por atestación; no publicación. Q-079VERIFIED_LOCAL de4b1ac95. Producción declarada9b52e1e.13.5REVIEW/13.7DOING/13.6OWNER. No repetir despliegue. Informe docs/REVISION_D101_POSTDEPLOY_2026-10-19.md.

## Historial previo (prevalece el estado anterior)

**Auditoría Codex sobre9b52e1e:** Q-079 **VERIFIED_LOCAL**,30/30 repetidos y ajuste de prevalidación de ambos destinos con regresión independiente. D-101 **NO EJECUTADO**, bloqueado por permisos locales según QA, sin respaldo nuevo ni verificación posterior. GO de código b83d9ee sigue vigente; resolver permisos por flujo normal, sin eludirlos. EN en borrador, sin publicar. Informe `docs/REVISION_D101_POSTDEPLOY_2026-10-19.md`.

## Historial (prevalece la auditoría anterior)

**Auditoría posterior solicitada:** pull/ramas remotas aún en b83d9ee, sin evidencia nueva del despliegue D-101 o corrección posterior Q-079. Despliegue/verificaciones REVIEW no acreditados; Q-079 FIX/OPEN. Solicitar SHA de entrega, sin repetir despliegue si ya se hizo. Informe `docs/REVISION_D101_POSTDEPLOY_2026-10-19.md`.

**GO de código D-101 (Codex, base72dbf9a):** Claude local puede ejecutar solo deploy-code.sh con respaldo y verificación posterior, manteniendo EN en borrador. Sin setup-en/publish-en ni activación Doctoralia. Q-079 no bloquea este alcance: sync.sh del laboratorio no forma parte de la entrega y no debe usarse. 13.5REVIEW/13.7DOING/13.6OWNER sin cambio. Evidencia y pasos en BATON e informe de entrega.

**Auditoría Codex de la entrega 62d920e:** Q-077 **VERIFIED_LOCAL ratificado**; Q-078 **ACCEPTED por D-100**, sin pedir otra decisión. Q-079 **FIX / OPEN**: 9/9 pasan, pero sync.sh permite enlaces en directorios intermedios; reproducción desechable acreditada. No usar sync sobre el laboratorio real hasta corregirlo. 13.5 REVIEW por verificación publicada,13.7 DOING,13.6 OWNER. Sin desplegar/publicar. Informe `docs/REVISION_ENTREGA_Q077_Q079_2026-10-19.md`.

## Historial previo (prevalece la auditoría anterior)

**21-oct · 8.6 y 9.5:** el abogado dio conformidad a todo (D-107); único cambio, publicar las cédulas solo en Conóceme (**Q-082**, encargado a la nube). 9.5: horario «Previa cita» hasta nuevo aviso (D-106); faltan las capturas de GBP y Doctoralia de José.

**Barra móvil en horizontal VERIFIED en producción (21-oct):** el CTA del hero y el contenido dejan libre la columna lateral (código c61ac01, respaldo `20261021-083841`): matriz 0/210, interacción 132/132, regresión de Q-081 sin fallos y `seo_check` en 0.

**P3 de Q-081 VERIFIED en producción (20-oct):** el aviso EN ya no tapa los botones del hero; espera y aparece al desplazar. Código f2e0603, respaldo `20261020-183957`; matriz 63/63, transiciones 9/9, regresión 37/37 y `seo_check` en 0.

**Q-081 VERIFIED en producción (20-oct):** el aviso «This page is also available in English» está activo (código 4dcdc13, respaldo `20261020-173714`): 37/37 en vivo, caché compatible y `seo_check` en 0.

**1c685c4 en producción (20-oct):** `og:image:alt` EN nuevo y portada ES con `es-MX` (cierra la P3); verificado, con `seo_check` en 0. **Q-081 (aviso EN) VERIFIED_LOCAL, 37/37: espera el GO de Codex para desplegar.**

**Mantenimiento (20-oct):** D-104, el español se queda en la raíz. D-105, aviso sugerido en inglés (sin redirección), encargado a la nube como **Q-081 (mejora, sin peso en el PLAN)**. Ciclo: la nube construye, la sesión local integra, Codex audita y la sesión local despliega.

**Q-080 VERIFIED en producción (20-oct):** código desplegado con respaldo `20261020-162726`. El 404, los alts y `og:image:alt` salen en inglés en /en/ y el español no cambia; `seo_check --prod` sale con 0; las 16 EN no cambiaron. **Inglés al 100 %.** Siguiente: el seguimiento del día 7 el 22-oct.

**Q-080 VERIFIED_LOCAL (8c5f7f9):** el 404 y los alts salen en inglés en /en/ y el español no cambia; la regresión de 13.5 pasa. **Espera el GO de Codex para desplegar** el código del tema.

**🚀 /en/ PUBLICADA (D-103, 20-oct):** José dio la orden expresa y se ejecutó el gate completo con respaldo `20261020-154356`. Las 16 entradas están publicadas; `seo_check --prod` sale con 0; sitemap 30; hreflang y selector funcionan; sin errores. 13.4, 13.5 y 13.7 en REVIEW a la espera de Codex. Q-080 P3 (textos ES residuales) encargado a la nube. Evidencia en `docs/audit/2026-10-20-en-prod/`.

**13.6 REVIEW (19-oct 17:48):** la Dra. aprobó las 8 páginas EN en su guía; 5/5 fichas; legales D-099; contenido = build. Todo sigue en borrador. **Las condiciones médicas y legales del gate se cumplen; falta la orden expresa de José para publicar (D-098)** y la ratificación de Codex.

**13.6 (D-102):** José confirmó que las aprobaciones del 17-oct son de la Dra. Los borradores están sincronizados (respaldo previo). **6/8 páginas y 5/5 fichas aprobadas; la Dra. debe aprobar About y Contact en su guía.** Después, el lanzamiento espera la orden expresa de José (D-098). Gate: `docs/GATE_LANZAMIENTO_EN.md`.

**Gate de lanzamiento EN preparado (`docs/GATE_LANZAMIENTO_EN.md`).** 13.6: en producción constan 12 aprobaciones del 17-oct hechas con el enlace de la Dra. (7/8 páginas y 5/5 fichas). **Falta Contact** y falta que José confirme que las hizo ella. 13.5 REVIEW, 13.6 OWNER y 13.7 DOING.

**D-101 DESPLEGADO (2026-10-19 16:38):** solo código, con el inglés en borrador. `/en/contact/` pasa de 301 a 404, las ES llevan `es-MX` y no hay regresiones. Evidencia en `docs/audit/2026-10-19-d101/verificacion.md`. Codex audita 9b52e1e y este registro.

**Q-079 sync.sh FIXED_LOCAL (30/30; control c151258: 13 fallos).**

**Q-077 VERIFIED_LOCAL (2026-10-19):** el 1f5e18e de la nube, integrado en la copia aislada, deja `seo_check.py` endurecido en salida 0: inLanguage en las 30 páginas, WebSite/WebPage en /en/, ProfilePage en About EN y sitemap 30. Codex audita c151258 y 1f5e18e. 13.5 sigue en REVIEW hasta esa auditoría y la verificación publicada.

**Respuesta de Claude local (2026-10-19, ver SHA en BATON):**
- Q-079 FIXED_LOCAL: lista permitida, raíz por git, destino validado y 9/9 pruebas.
- Verificador SEO endurecido: inLanguage obligatorio, paridad de nodos con ES, salida 1 y 10/10 mutaciones.
- Q-078 decidido por José: IndexNow se acepta tal como está (D-100).
- Q-077 (schema) sigue OPEN y lo corrige la nube. Codex audita al recibir el SHA.

**Auditoría de pendientes (Codex, 2026-10-19):** pull y ramas remotas reconsultados en `bc2deb0`; no se recibieron correcciones nuevas. Falta identificar SHA/repositorio de la entrega de Claude. Q-077 y Q-079: **FIX / OPEN**; Q-078: **REVIEW / OPEN**, pendiente decisión de José. 13.5 REVIEW y 13.7 DOING sin cambio. Informe `docs/REVISION_Q077_Q079_2026-10-19.md`. Sin desplegar ni publicar.

Actualizado: 2026-10-19 · Codex · auditoría de b26efa4 y evidencia local recibida en 43cc1df. Informe: docs/REVISION_Q076_2026-10-19.md.

**Español:221/228pesos=96,93%;83/86DONE. Inglés aparte:9/19pesos=47,37%;3/7DONE.** NueveDESCOPED/25pesos por D-088/D-094; soloDONEsuma.

**Integración real 19/10 (sesión local, `docs/REVISION_INTEGRACION_EN_2026-10-19.md`).**
- 13.5: OG, canónicas, hreflang, sitemap 30, selector, menús, 404, Gracias, interfaz, formulario sin envío y Sleep/ENT pasan con WP 7.1.3 + Rank Math reales. El 301 de /en/contact/ viene de `redirect_guess_404_permalink`; el guard lo resuelve en local. Sigue REVIEW por Q-077 (schema de la portada /en/) y por la verificación en producción.
- 13.7 DOING: **Q-076 VERIFIED_LOCAL ratificado**. b26efa4 carga Elementor y evita la falsa alerta al no cambiar la raíz; evidencia local: 16/16 publicadas, 15 HTTP 200, 16 preflight sin cambios y fallos compensados o informados. Codex: 30 casos sintéticos y cotejo offline pasan. Q-078 OPEN: IndexNow no se revierte; Q-079 OPEN: excluir privados antes de copiar al laboratorio. Sin despliegue/publicación; Doctoralia apagado. Q-077 mantiene 13.5 REVIEW (siete EN sin inLanguage en la evidencia).

**Historial anterior, sustituido en integración/recuperación por la auditoría de arriba:**

13.2FIX documental→DONE (glosario/mapa actualizados D-095/CPAP);13.3FIX→DONE en borrador (8páginas+3legales, enlaces/catálogos/builds).13.5REVIEW:callbacks28checks pasan, falta WordPress+RankMath OG/canónicas/sitemapEN y comprobar301de /en/contact/.13.4/13.7DOING;13.6OWNER por aprobación médica real. Legales yaaprobados por José D-099; sin nueva petición de OK ni publicación.

Q-072/073FIXED_LOCAL:publicador valida16entradas/parejas/aprobaciones y fallos, intenta compensar estados y devuelve error; setup comprueba WP_Error antes de cast.28casos con stubs,2regresiones antiguas reproducidas. No transacción atómica ni restauración real ensayada;13.7no cerrado. Q-074estados Doctoralia/carrusel/errores de formulario localizados; Q-075desactiva guess404bajoEN (causa301vivo aún no acreditada). Ningún cambio desplegado.

Sueño/Otorrino públicosVERIFIED:8vistas390/393/832/1440, fotos/encuadres68/64 y50%0%, títulos/sombras/nocturno/borde#342b56 según decisiones. Sin cambios propios de fotos, marca, menú ni sombras. Inglés15rutas404+1ruta301aES; sitemap15españolas, sin selector nihreflangEN. Producción declarada4dab820; HTTP no certificaSHA. Vista realEN/nocturno por atestación anterior, no nueva sesión autenticada.

PHP85/bash21/PHPCSproducto+publisher/ESLint/stylelint pasan;setup conserva1warning meta_query preexistente.24salidas reproducibles,18enlaces válidos. Los mocks no equivalen a WordPress/SEO publicados. Informe docs/REVISION_INGLES_2026-10-19.md y evidencia docs/audit/2026-10-19/.

Doctoralia sigue apagado, GO condicionado y código oficial pendiente.9.5/8.6OWNER,12.3TODO; CHECKPOINT5D-086 intacto. Q-040OPEN. CuatroHTML previos conservados/excluidos. Sin deploy/setup/publicación, formularios/SMTP, cuentas, secretos ni Sensia.
