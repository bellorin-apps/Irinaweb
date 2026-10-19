# STATUS — Dra. Irina González Sáez

Actualizado: 2026-10-19 · Claude local · integración real de la versión inglesa (WordPress/Rank Math local), sobre la auditoría de Codex (base 6d96ae1).

**Español:221/228pesos=96,93%;83/86DONE. Inglés aparte:9/19pesos=47,37%;3/7DONE.** NueveDESCOPED/25pesos por D-088/D-094; soloDONEsuma.

**Integración real 19/10 (sesión local, `docs/REVISION_INTEGRACION_EN_2026-10-19.md`).**
- 13.5: OG, canónicas, hreflang, sitemap 30, selector, menús, 404, Gracias, interfaz, formulario sin envío y Sleep/ENT pasan con WP 7.1.3 + Rank Math reales. El 301 de /en/contact/ viene de `redirect_guess_404_permalink`; el guard lo resuelve en local. Sigue REVIEW por Q-077 (schema de la portada /en/) y por la verificación en producción.
- 13.7: las protecciones funcionan con WP real, pero **Q-076 P1 bloquea el lanzamiento**: publish-en usa `--skip-plugins=elementor` y daría `invalid_page_template` en la primera página. Q-078: IndexNow no se revierte. Sin despliegue ni publicación; Doctoralia apagado.

13.2FIX documental→DONE (glosario/mapa actualizados D-095/CPAP);13.3FIX→DONE en borrador (8páginas+3legales, enlaces/catálogos/builds).13.5REVIEW:callbacks28checks pasan, falta WordPress+RankMath OG/canónicas/sitemapEN y comprobar301de /en/contact/.13.4/13.7DOING;13.6OWNER por aprobación médica real. Legales yaaprobados por José D-099; sin nueva petición de OK ni publicación.

Q-072/073FIXED_LOCAL:publicador valida16entradas/parejas/aprobaciones y fallos, intenta compensar estados y devuelve error; setup comprueba WP_Error antes de cast.28casos con stubs,2regresiones antiguas reproducidas. No transacción atómica ni restauración real ensayada;13.7no cerrado. Q-074estados Doctoralia/carrusel/errores de formulario localizados; Q-075desactiva guess404bajoEN (causa301vivo aún no acreditada). Ningún cambio desplegado.

Sueño/Otorrino públicosVERIFIED:8vistas390/393/832/1440, fotos/encuadres68/64 y50%0%, títulos/sombras/nocturno/borde#342b56 según decisiones. Sin cambios propios de fotos, marca, menú ni sombras. Inglés15rutas404+1ruta301aES; sitemap15españolas, sin selector nihreflangEN. Producción declarada4dab820; HTTP no certificaSHA. Vista realEN/nocturno por atestación anterior, no nueva sesión autenticada.

PHP85/bash21/PHPCSproducto+publisher/ESLint/stylelint pasan;setup conserva1warning meta_query preexistente.24salidas reproducibles,18enlaces válidos. Los mocks no equivalen a WordPress/SEO publicados. Informe docs/REVISION_INGLES_2026-10-19.md y evidencia docs/audit/2026-10-19/.

Doctoralia sigue apagado, GO condicionado y código oficial pendiente.9.5/8.6OWNER,12.3TODO; CHECKPOINT5D-086 intacto. Q-040OPEN. CuatroHTML previos conservados/excluidos. Sin deploy/setup/publicación, formularios/SMTP, cuentas, secretos ni Sensia.
