# QA — Hallazgos y resolución

Clasificación vigente: P0 bloquea · P1 importante · P2 mejora. Las severidades anteriores se conservan como historial.
Estados: OPEN · REVIEW · OWNER · FIXED_LOCAL (probado, sin desplegar) · VERIFIED (producción) · WONTFIX (decisión explícita). FIXED histórico no implica nueva verificación.

## Auditorías de Codex

| # | Fecha | Alcance | Hallazgos | Estado |
|---|---|---|---|---|
| 1 | 2026-10-10 | Tema, core, builds, scripts, mini apps, docs y 11 rutas públicas en seis anchos | Q-024–Q-045 y Q-018 | Correcciones locales; despliegue y gates autenticados pendientes |

## Hallazgos

| ID | Severidad | Área | Descripción | Detectado por | Estado | Resolución |
|---|---|---|---|---|---|---|
| Q-001 | HIGH | SEO | Search Console reporta páginas excluidas por `noindex` y por canónica duplicada en drairinagonzalez.com (alertas de sep-2026). Puede significar que el sitio "en construcción" tiene URLs conocidas por Google. | Claude (correo de Search Console) | OPEN | Verificar en Fase 1.4 qué URLs están afectadas y planear mapa 301/noindex correcto |
| Q-002 | MEDIUM | Marca/Contenido | El logotipo y las tarjetas usan "OTORRINOLARINGÓLOGO" (masculino). Impacta en consistencia de textos y SEO. | Claude | OPEN | OD-001 en `DECISIONS.md` |
| Q-003 | MEDIUM | Multimedia | No existe fotografía profesional utilizable de la Dra. para el sitio. | Claude (subagente de revisión de assets) | OPEN | `docs/PHOTO_SHOTLIST.md` |

| Q-004 | HIGH | SEO/Infra | El WordPress vive en `domains/otorrino-monterrey.com/public_html` y `drairinagonzalez.com` se sirve desde el mismo webroot: dos hosts para el mismo contenido. Riesgo de duplicidad y causa probable de "canónica diferente" en Search Console. | Sesión local Membretador | FIXED | 2026-10-07: dominio principal cambiado a drairinagonzalez.com; otorrino-monterrey.com aparcado + 301. Falta www canónico (VERIFIED cuando se fije home con www) |

| Q-005 | HIGH | Código | `Ops\Endpoints::admin_only` era `private static` y se usaba como `permission_callback` → fatal 500 en `/ops/*` en producción (core 0.2.0). | Sesión local Irinaweb | FIXED | 0.2.1: método público; lista de denegación unificada para GET y POST (secret/password/salt/token). Lección: probar activación y rutas en un WP local antes de desplegar (pendiente red/herramientas) |

| Q-006 | INFO | Workflow | Prueba del gate de publicación médica: `wp post update 205 --post_status=publish` sobre una condición sin aprobación quedó en `pending` (también desde WP-CLI). | Sesión local Irinaweb | VERIFIED | 2026-10-08: comportamiento esperado (solo la Dra. asigna MEDICALLY APPROVED). Sin cambios de código |
| Q-007 | HIGH | SEO/Infra | `otorrino-monterrey.com` (dominio aparcado) no tiene certificado SSL: http → 301 a https, pero https no responde (TLS falla), así que la redirección 301 a drairinagonzalez.com nunca se ejecuta por https y las URLs antiguas indexadas con https quedan rotas. | Sesión local Irinaweb | FIXED | 2026-10-09: Lifetime SSL (Let's Encrypt, SAN apex+www, válido hasta 2027-01-07); http y https, apex y www → 301 a https://drairinagonzalez.com/ (verificado por la sesión local sin proxy). Rutas profundas y cadena de 2 saltos: Q-012 |
| Q-008 | MEDIUM | Scripts | `get_posts( [ "name" => $slug, "post_status" => "any" ] )` no encuentra borradores ni privadas: la 2.ª ejecución de setup-site/setup-medical habría duplicado páginas y fichas (pasó con 9 páginas el 7 oct). | Sesión local Irinaweb | FIXED | 2026-10-09: búsquedas por `post_name__in` (commits 9cf6103, 0608ff8, 5e4b3a1); regla para scripts futuros |
| Q-009 | INFO | Infra | El WAF/CDN de Hostinger devuelve 403 a ráfagas de peticiones de Chromium headless desde el entorno cloud (curl sigue en 200): falsa alarma en el QA visual tras el go-live. | Claude | OPEN | Espaciar capturas desde cloud; QA visual con el Chrome de la sesión local; no tocar el WAF |
| Q-010 | INFO | Seguridad | Mini app de revisión (D-040): revisión de seguridad al construirla. Token con `hash_equals` (GET y POST), sin cookies ni nonce (no hay sesión de navegador); ejecuta como `revisor_medico` con `current_user_can( edit_post )` y la capacidad `approve_medical_content` reales; entrada saneada con `wp_kses_post`, `sanitize_text_field`, `esc_url_raw` y enums; límite de cuerpo 400 KB; sin publicar nunca (fichas publicadas en solo lectura); respuestas `no-store` + `X-LiteSpeed-Cache-Control: no-cache` + noindex; `config.php` denegado por `.htaccess`; no expone correos, RFC/CURP ni rutas. Prueba funcional en servidor hecha por la sesión local (lista, get, save, changes, log; 0 errores de consola). | Claude | REVIEW | Desplegada 2026-10-09; auditoría de Codex en la próxima entrega |
| Q-011 | LOW | Plugin | El rol `revisor_medico` no tenía `edit_private_*`: la mini app devolvía `forbidden` para la ficha 205 (estado `private`). La sesión local la pasó a `draft`. | Sesión local | FIXED | 0.3.8: `edit_private_{condiciones,tratamientos,recursos}` en el rol y `ensure_role()` idempotente en `init` (aplica a instalaciones existentes sin reactivar) |
| Q-012 | MEDIUM | SEO/Infra | La redirección de hPanel del dominio aparcado solo cubre la raíz: `https://otorrino-monterrey.com/<ruta-vieja>/` responde 404 del WordPress (mismo webroot) en vez de 301 a la misma ruta; además http→https→destino son 2 saltos (MASTER_PROMPT §35: OLD→NEW con 301, sin cadenas). | Sesión local | FIXED | 2026-10-09: `parked-redirect.sh` aplicado (bloque entre LSCACHE y WordPress, respaldo guardado). Verificado: cualquier ruta de apex/www por https → 301 a la misma ruta en 1 salto (query conservada); por http el borde de Hostinger fuerza https antes (2 saltos inevitables, aceptable). Pendiente menor: José comprueba Search Console del dominio viejo |
| Q-013 | MEDIUM | SEO | `sitemap_index.xml` y `page-sitemap.xml` en 404 desde WordPress: `setup-site.sh` (reejecutado para el hero) volvía a poner `noindex` en la portada 168 («Home v2 noindex hasta CP3»); con la portada excluida y `/links/` noindex el proveedor de páginas quedaba vacío y Rank Math responde 404. | Claude (cloud) | FIXED | Sesión local: meta borrada en 168; `setup-site.sh` corregido (si la página es portada, borra `rank_math_robots`). Verificado 200 text/xml con `/` en `page-sitemap.xml`; portada `index, follow` |
| Q-014 | LOW | Diseño | Pilar Sueño en móvil: rótulos «Llamar» y «Cómo llegar» de la barra inferior en acento claro sobre superficie crema (herencia de `.di-dark a`), contraste insuficiente. | Sesión local (capturas con sesión) | FIXED | `sleep.css`: color de texto estándar en `.di-mobile-bar` dentro de `body.di-dark` |
| Q-015 | INFO | QA | Capturas con sesión de 169/170/171/205 (1440 y 390 @2×) en `/preview/qa/` (noindex): Dra., ORL, Sueño y Apnea correctos (breadcrumb, overline, h1, lead, CTAs, Iskra). Artefactos del método, no del sitio: barra de admin, sprite de iconos no pintado desde `file://`, cabecera en estado scrolled. | Sesión local | REVIEW | Pendiente QA visual en navegador real (Chrome local) tras publicar las páginas |
| Q-016 | HIGH | Tema | Tras publicar las cuatro páginas (D-044), el menú principal y el pie mostraban enlaces a páginas en borrador como `?page_id=170/171/175/176/177` (404 para el visitante): `wp_nav_menu` pinta ítems de borradores con esa URL. | Sesión local | FIXED | Filtro `wp_nav_menu_objects` en `inc/setup.php`: se omiten ítems `post_type` cuyo objeto no esté publicado (todos los menús). Verificado en producción: 0 `page_id=` en portada; menú con Dra. Irina, Primera consulta y Contacto |
| Q-017 | HIGH | Contenido | `/primera-consulta/` y `/preguntas-frecuentes/` publicadas con «Costo de la consulta: [PENDIENTE DE CONFIRMACIÓN]» visible. | Sesión local | FIXED | Texto sustituido por «Te confirmamos el costo al agendar por WhatsApp…» en `build-pages.py`; José decide si publica el costo. Verificado en producción: 0 marcadores en las cuatro páginas |
| Q-018 | LOW | SEO | `/preguntas-frecuentes/` sin `FAQPage` en JSON-LD (el widget `di-faq` no lo emite). | Sesión local | OPEN | Valorar emitir FAQPage desde el widget en una sola página (evitar duplicar en Primera consulta) |
| Q-019 | INFO | QA | Tanda 1 (D-046) verificada en producción por la sesión local: cabecera 22/12 px y logo 68/48 px, barra glass con sombra, 4 círculos de redes, logo de WhatsApp real (0 «message-circle»), overline sin guion, botones glass, h1/h2 1em y párrafos 1.3em, móvil con rostro a la derecha, sombra solo abajo, CTAs 50/50, burger alineado. Capturas en `/preview/qa/portada-*-20261009-1646.png`. | Sesión local | FIXED | Muestras confirmadas y aplicadas (lombriz fina animada 1.4 s en currentColor vía máscara, botones glass sin bordes, sombra móvil desde la mitad del título, overline 1em y en dos líneas en móvil); verificado en producción 2026-10-09 17:10. Pendiente aparte: SVG oficial de Doctoralia |
| Q-020 | MEDIUM | Diseño | Paneles de área con foto: etiquetas y overline tenues sobre zonas claras; en móvil 2× la foto llegaba al ancho CSS a 1× (`sizes="auto, …"` de WordPress en imágenes diferidas). | Sesión local | FIXED | Contraste resuelto (opción B, cápsulas glass). Nitidez móvil: la CDN reescala al ancho del viewport sin DPR; el propietario decide mantener la optimización inteligente y aceptar 1× en imágenes no a sangre (2026-10-10). `wp_img_tag_add_auto_sizes` desactivado |
| Q-021 | INFO | Seguridad | Clave de API de Google Maps en el HTML (inevitable con la Maps JavaScript API): debe restringirse por referente HTTP (`drairinagonzalez.com/*`) y a la API «Maps JavaScript API» en Google Cloud; no se expone en la REST pública (`maps_api_key` no publicable) y no se pega en chats ni en el repo. | Claude | FIXED | Clave restringida por José el 2026-10-10 a `https://drairinagonzalez.com/*` y `https://www.drairinagonzalez.com/*` y solo a Maps JavaScript API; verificado tras aplicar: mapa carga en / y /contacto/ con marcador y sin error de Google. Map ID c29395b665a43c57de4f3a3f con el diseño `paleta-irina` publicado (comercios ocultos). No expuesta en REST. Pendiente: José confirma las restricciones de referente y de API en Google Cloud |
| Q-022 | MEDIUM | Accesibilidad | Auditoría automática en vivo (2026-10-10, home/contacto/Dra. 1440 y 390): sin `<main>` en las páginas con plantilla Elementor header/footer (el enlace «Ir al contenido» apuntaba a nada); eyebrows en teal fuerte a 4.3:1 sobre crema (AA exige 4.5); puntos del carrusel de 8 px sin área táctil; enlaces del pie de 19 px de alto. Correcto: 1 h1 por página, `lang=es`, imágenes con alt, botones con nombre accesible, campos con etiqueta, sin tabindex positivo, foco visible, sin errores de consola. | Claude | FIXED | `<main>` desde header/footer en esas plantillas; `--color-secondary-strong` #2a787d (4.9:1); área táctil 24 px en puntos; padding en enlaces del pie. Botón de WhatsApp: sólido teal sobre claros y glass sobre fotos (D-053) |
| Q-023 | LOW | Seguridad | Sin cabeceras HSTS, nosniff, X-Frame-Options, Referrer-Policy ni Permissions-Policy (solo `upgrade-insecure-requests` de la CDN). | Claude | FIXED | Módulo `Security\Headers` del plugin (0.4.0) las envía en el front; verificar con `curl -I` tras desplegar |
| Q-024 | HIGH | CDN | La optimización móvil de la CDN de Hostinger **recortaba** el retrato alto de la Dra. (dra-escucharte 1024×2170 → 1024×1496 WebP con User-Agent móvil): en Samsung e iPhone la sección «Escucharte…» salía con la figura ancha y cortada mientras las capturas con UA de escritorio y la ventana estrecha se veían bien. | Claude (cloud) | FIXED | Diagnóstico con UA móvil real (2026-10-10). Fix: `?v=<id>-<hash>` en src/srcset de adjuntos con alto ≥ 1.5× ancho (`inc/performance.php`); con query string la CDN sirve el archivo completo. Confirmado por el propietario en Samsung e iPhone: «Ahora sí, perfecto». Lección para QA: emular dispositivos con su User-Agent (`devices['Galaxy S9+']`, `devices['iPhone 13']`), no solo el ancho |
| Q-025 | INFO | Rendimiento | Peso de imágenes de la portada con la regla D-063/D-064 (WebP q100 + móvil 1600): escritorio 8 567 KB (hero 3 391, fondo 3 133, retrato PNG 1 020, áreas 538/473), móvil 8 713 KB (heros 1 175/1 475, fondo 1 118, retrato PNG 2 452, áreas 1536 px 2 107/1 849). Con q95 el hero quedaría en 747 KB escritorio / 265 KB móvil. | Sesión local / cloud | OPEN | Lighthouse 13.5 (cloud, 2026-10-10, 4G simulado): portada móvil 52 (LCP 10,2 s; hero 1 175 KiB), escritorio 60 (LCP 3,8 s); Dra. móvil 68 (LCP 10,1 s; hero 1 475 KiB), escritorio 78 (LCP 3,3 s). Accesibilidad 91–95, buenas prácticas 100, SEO 100. El LCP móvil lo fija el hero: decisión del propietario entre q100 (D-064) y q95 (~265 KB móvil, LCP estimado 3–4 s) |
| Q-046 | INFO | Rendimiento | CLS: Lighthouse escritorio da 0,26 en la portada (cabecera `.di-bleed .di-container`), pero en Chromium real a 1440×900 sin estrangular la red mide 0,035 (dos desplazamientos de 32 px al cargar la tipografía Iskra, kit cargado como `media=print` → swap). Dra.: 0,009. | Cloud | OPEN | Si se quiere bajar también bajo red lenta: cargar el kit de Adobe de forma bloqueante con `preconnect` (10 KB) o fijar `font-display: optional` en el kit; decisión de diseño del propietario |
| Q-047 | INFO | Rendimiento | TBT móvil 670 ms en la portada (Dra. 130 ms): documento 2,8 s de JS (inline de Elementor), jQuery 327 ms, gtag 355 ms, main.js 345 ms (encaje por canal alfa del retrato en «Escucharte»). | Cloud | OPEN | Candidatos: cargar gtag con `requestIdleCallback`, retrasar el encaje alfa hasta que la sección entre en pantalla, revisar si jQuery es necesario en el front |

## QA visual

_(Fase 6 en adelante.)_

## QA funcional / dispositivos / SEO / performance / seguridad / contenido

_(Fase 11.)_


## Reconciliación de Q-001–Q-023 · Codex, 2026-10-10

| ID | Estado real actual | Evidencia / límite |
|---|---|---|
| Q-001 | OPEN | Search Console necesita revisión del propietario; no se atribuye cobertura a una petición HTTP |
| Q-002 | WONTFIX | Género masculino dictado por OD-001; no es error por corregir |
| Q-003 | OWNER | Hay fotos autorizadas en vivo; sigue pendiente foto definitiva del hero Dra. |
| Q-004 | VERIFIED | Canónico sin www y 301 HTTPS por ruta/query del dominio antiguo; D-022 sustituye la nota antigua de www |
| Q-005 | FIXED histórico | Callback público y Ops sin sesión 401; no se ejecutó Ops autenticado |
| Q-006 | VERIFIED histórico / ampliado localmente | Prueba local del gate en tres CPT, publish/future; permisos completos en staging pendientes (Q-024) |
| Q-007 | VERIFIED | HTTPS apex/www antiguo responden 301, sin fallo TLS |
| Q-008 | FIXED_LOCAL ampliado | Setup-site/medical corregidos previamente; dos consultas remanentes en Dra./SEO ahora usan post_name__in (Q-036) |
| Q-009 | WONTFIX en este entorno | Chrome local accede; no modificar WAF/CDN por limitaciones de cloud |
| Q-010 | REVIEW | Backend revisado, cuerpos acotados y token tipado; sin token 403; guardar/aprobar requiere prueba autenticada local |
| Q-011 | FIXED histórico | Capacidades privadas presentes; no se operó el usuario real |
| Q-012 | VERIFIED | HTTPS 301 conserva ruta/query en un salto; HTTP tiene salto previo de borde |
| Q-013 | VERIFIED | Ambos sitemaps 200; Home indexable; setup-site conserva guard de portada |
| Q-014 | FIXED histórico | Regla sleep existe; pilar en borrador, sin nueva captura autenticada |
| Q-015 | REVIEW | Capturas privadas históricas conservadas; no se autentica esta sesión |
| Q-016 | VERIFIED | Cero enlaces ?page_id= en las rutas públicas; destinos clínicos 404 por otro mecanismo: Q-025 |
| Q-017 | VERIFIED | Costo por WhatsApp y sin [PENDIENTE] en público; marcadores draft=yes pendientes distintos: Q-043 |
| Q-018 | FIXED_LOCAL | FAQPage desde datos del widget, solo FAQ publicada; pendiente validar tras desplegar  `wp-content/plugins/dra-irina-core/src/Schema/Graph.php:257` |
| Q-019 | VERIFIED parcial / histórico | Sprite oficial Doctoralia presente; no queda solicitud de SVG provisional; no se reasigna aprobación visual completa |
| Q-020 | WONTFIX para nitidez 1× | Decisión de mantener CDN de José; fotos/cápsulas en vivo; no cambiar CDN |
| Q-021 | VERIFIED público | REST no expone clave; script Google es su uso público esperado. Restricciones confirmadas por José en encargo; Cloud no inspeccionado independientemente |
| Q-022 | VERIFIED parcial | Un main/h1 en 11 rutas; CSS conserva foco y área de puntos. Contraste Escucharte tratado en Q-038, no certificación WCAG completa |
| Q-023 | VERIFIED | Cinco cabeceras presentes en las 11 rutas públicas |

## Nuevos hallazgos · código corregido pendiente de despliegue

| ID | Severidad | Evidencia | Estado | Acción / commit |
|---|---|---|---|---|
| Q-024 | P1 | `Taxonomies/EstadoMedico.php::args`, `Workflow/MedicalReview.php`, `Fields/MetaRegistry.php`; capacidades genéricas, scheduled y aprobación reutilizable  `wp-content/plugins/dra-irina-core/src/Taxonomies/EstadoMedico.php:37` | FIXED_LOCAL | `b2f718b`: aprobación por capacidad, publish/future, revocación ante cambio técnico y edit_post por ID; probar REST en staging |
| Q-025 | P1 | `docs/audit/2026-10-10/home-links.json`: diez destinos clínicos/pilares 404 | FIXED_LOCAL | `f8a58dd`: fallback público de Home y omitir sidebar de borradores; no publicar |
| Q-026 | P2 | `public.json` duplicateHero=true en / y Dra.; atributo repetido en HTML, no en DOM normalizado por navegador  `wp-content/themes/irina-gonzalez/inc/performance.php:90` | FIXED_LOCAL | `7c622b1`: parser HTML en hero/Elementor; API real de WP probada; purga y comprobar HTML crudo |
| Q-027 | P2 | `inc/performance.php`, filtro 94 después de 100  `wp-content/themes/irina-gonzalez/inc/performance.php:65` | FIXED_LOCAL | `7c622b1`: retirar contradicción con calidad aprobada |
| Q-028 | P1 | `tools/deploy/upload-media.sh`, post delete antes de import  `tools/deploy/upload-media.sh:16` | FIXED_LOCAL | `f8a58dd`: conservar original/ID, importar primero, versionar en segundos; sintaxis probada, ensayo remoto pendiente |
| Q-029 | P1 | `public.json` hiddenHoursExposed=true; PracticeSettings::public_data  `wp-content/plugins/dra-irina-core/src/Settings/PracticeSettings.php:195` | FIXED_LOCAL | `b2f718b`: horario oculto fuera de REST; Maps/Bcc ya privados |
| Q-030 | P2 | builds home/pages/dra y JSON en una línea  `tools/content/build-home.py:80` | FIXED_LOCAL | `344ff4c`: indent=2, regeneración y igualdad semántica comprobada |
| Q-031 | P2 | STATUS 31 %, PLAN resumen divergente, BATON inicial sin código, NEXT CP3 pendiente | FIXED_LOCAL | PROGRESS reproducible; estado y relevo actuales; no subir estados sin evidencia |
| Q-032 | P2 | `assets/js/main.js`, medida de tarjetas anterior a fuentes y sin resize; touch sin swipe no pausaba  `wp-content/themes/irina-gonzalez/assets/js/main.js:106` | FIXED_LOCAL | `03db275`: medida tras fuentes/resize, pausa al tocar y aria-live off; interaction.json PASS |
| Q-033 | P2 | `Contact/Form.php::process`, `main.js::showError`  `wp-content/plugins/dra-irina-core/src/Contact/Form.php:72` | FIXED_LOCAL | `b2f718b` + `03db275`: POST/escalares, aria-invalid, impedir doble envío; sin envío real |
| Q-034 | P2 | `inc/enqueue.php`, `Schema/Graph.php::output`  `wp-content/themes/irina-gonzalez/inc/enqueue.php:85` | FIXED_LOCAL | `7c622b1`: JSON recodificado/HEX evita salir del script |
| Q-035 | P2 | guardar.php/fichas; deploy-briefing conservaba config notify y mostraba token/answers  `tools/deploy/deploy-briefing.sh:56` | FIXED_LOCAL | `b4b0716`: sin correos, entrada acotada, respaldo; URLs privadas ignoradas, salida sin respuestas |
| Q-036 | P2 | setup-dra y fallback SEO en setup-pages, consultas name+any  `tools/deploy/setup-dra.sh:12` | FIXED_LOCAL | `f8a58dd`: post_name__in; verificar idempotencia en copia WP |
| Q-037 | P2 | uninstall.php, LIKE con underscores y sin strict  `wp-content/plugins/dra-irina-core/uninstall.php:16` | FIXED_LOCAL | `b2f718b`: esc_like/prepare; sin eliminación de contenido/ajustes |
| Q-038 | P2 | contrast.json; overline anterior 4,1:1 sobre foto  `wp-content/themes/irina-gonzalez/assets/css/components.css:261` | FIXED_LOCAL | `03db275`: tokens más oscuros, glass conservado; overline 4,9 y botón 6,46 |
| Q-039 | P1 | baseline-360.png, 851 px y retrato 40,5 % visible  `wp-content/themes/irina-gonzalez/assets/css/components.css:317` | VERIFIED | `03db275` y posteriores (D-069, encaje alfa). 2026-10-12: José lo revisó en su Samsung en producción, «Listo, todo perfecto» (con los logos y el hero de Primera consulta) |
| Q-040 | P2 | Ops/Endpoints.php::DENY: superficie de opciones extensa para admin  `wp-content/plugins/dra-irina-core/src/Ops/Endpoints.php:19` | OPEN | Propuesta técnica: inventariar consumidores y pasar a allowlist; permiso manage_options y veto de secretos ya activos |
| Q-041 | P2 | assets/js/map.js, Marker obsoleto  `wp-content/themes/irina-gonzalez/assets/js/map.js:18` | WONTFIX | D-065 vigente (sustituye a D-059): sin Map ID, estilo JSON y marcador clásico; el aviso de Marker es cosmético. Se retoma solo si José lo pide o Google anuncia la retirada. Antes: mantener hasta mapId; guard contra mapa duplicado corregido en `03db275` |
| Q-042 | P2 | NAP/horarios/hospitales repetidos en builds/defaults; fuente central existe  `tools/content/build-home.py:55` | VERIFIED | 2026-10-16 Codex: nueve campos públicos/nap-check/negativo/builds14JSON sin cambios, ver docs/REVISION_FASE5_2026-10-16.md. Implementación Claude cloud: dirección de los builds desde tools/content/practice.py; JSON idéntico; tools/audit/nap-check.py en verde (builds sin NAP a mano; practice.py = schema publicado). Hospitales/credenciales: texto aprobado, fuera de NAP. Antes: Migración a datos centrales al renderizar como próxima unidad técnica, sin cambiar textos aprobados |
| Q-043 | P1 | build-pages.py pc-narr y build-dra.py narrativa draft=yes; marcas visibles en Dra./Primera consulta  `tools/content/build-pages.py:68` | VERIFIED | Dra.: marca retirada el 2026-10-10 con el texto confirmado (D-058). Primera consulta «Qué llevar»: aprobado por el propietario el 2026-10-12 con la captura del bloque y marca retirada en build-pages. Quedan marcas en ORL y Sueño (orl-narr, su-narr), páginas aún en borrador |
| Q-044 | P2 | Ocho defaults de títulos Elementor conservaban punto final en em, contrario a D-049  `wp-content/plugins/dra-irina-core/src/Rendering/Elementor/Faq.php:31` | FIXED_LOCAL | e87c29b: retirar solo el punto; sin cambiar textos publicados ni reparto de acentos |
| Q-045 | P2 | MetaRegistry aceptaba 2026-02-30 como fecha por validar solo formato  `wp-content/plugins/dra-irina-core/src/Fields/MetaRegistry.php:235` | FIXED_LOCAL | e87c29b: checkdate; regresión de fecha inválida/válida |

Informe, variantes, límites y preguntas: `docs/AUDITORIA_CODEX_2026-10-10.md`. Datos de rectángulos: `docs/audit/2026-10-10/*-metrics.json`. Relevo: BATON. No se ejecutó ninguna prueba del formulario/SMTP en vivo.

## Revisión posterior de Q-039 · 4cf6e8e

Estado vigente: **OPEN / REVIEW**. La indicación D-057 conserva retrato grande y sustituye A/B. Resultado local de Claude4cf6e8e: gap8px; rostro estimado44,7/56,0 % a360/390. Mover48px adicionales a360 conserva tamaño y alcanza85,7 %, pero el hombro cruza4,9 % de cajas de palabras del párrafo. No se valida esa superposición. Evidencia por ancho y capturas: docs/REVISION_ESCUCHARTE_2026-10-10.md; CSS components.css:316. Codex no modifica el CSS del commit revisado. Sin despliegue ni cambios clínicos.

## Q-039 · cierre local con la referencia60/40 de José (D-061)

**FIXED_LOCAL; producción pendiente.** D-061 acepta rostro parcial y sustituye el mínimo80 %. Columnas60/40, foto−5px de la división y alto completo de fila. Medidos360/390/414/430/483/768/1024/1440; párrafo sin intersección de alfa en360/390/430; tres tarjetas sin salto de sección, sin desbordes ni errores de consola/JS en360/390/430/768/1440. Desktop1440 sigue849px. Fuente: components.css, bloque móvil60/40; docs/ESCUCHARTE_60_40_2026-10-10.md y columns-carousel.json. No se cambian textos, fondo, menú, sombras ni JS. Repetir en producción antes de VERIFIED; preguntas por Claude.

## Q-039 · reabierto por el resultado en teléfono

**OPEN.** José observa casi lo mismo aun en incógnito. Muestras de producción móvil360/390 sí cargan60/40 (CSSver1791651570). En360:left anterior207,672px → actual207,797px, height707,750px en ambos. La implementación geométrica no produjo el acercamiento visible esperado. Propuesta adicional12px a360 conserva tamaño y arroja0 % de intersección de alfa con cajas del párrafo, pero su offset total−17 requiere revisión por Claude/José frente a D-061. Sin cambios del tema ni despliegue por Codex. Evidencia: docs/ESCUCHARTE_DISCREPANCIA_MOVIL_2026-10-10.md; live-phone-css.json y narrow-position-metrics.json. No certificar el teléfono de José desde la emulación.

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


## Auditoría REVIEW · grupo1 · 2026-10-12

| ID | Veredicto final | Evidencia / límite |
|---|---|---|
| 1.2 | REVIEW | Inventario del7–8/10 histórico. HTML no enumera plugins sin assets, borradores, menús internos ni medios completos; falta inventario actual de Claude local sin datos privados. |
| 1.5 | REVIEW | HTML es y REST America/Monterrey, UTC−6 verificados. es_MX, formatos, ausencia completa de Astra/Site Kit y constantes de hardening necesitan informe local de valores permitidos, sin abrir wp-config. |
| 1.8 | DONE | FIX documental: rollback vigente según deploy-code.sh (~/deploy-backups/<ts>); runbook de reset marcado histórico y no ejecutable; sin credenciales. |

Fuente: docs/audit/2026-10-12/public-review.json, código/builds y documentos contrastados. No se consultaron secretos, ni se publicó clínica, ni se enviaron formularios. DONE documental no acredita staging ni gates clínicos. Ver docs/REVISION_2026-10-12.md para pruebas y entrega.


## Auditoría REVIEW · grupo2 · 2026-10-12

| ID | Veredicto final | Evidencia / límite |
|---|---|---|
| 2.1 | DONE | Referentes identifican snippets/inferencias y bloqueo de lectura; checklist visual separado. Investigación preliminar útil, sin certificar UX ajena. |
| 2.2 | DONE | FIX documental: oportunidades son hipótesis de muestra, sin afirmar ausencia universal de competidores; precios/horarios se subordinan a D-054/D-032. |
| 2.3 | DONE | Keyword map sin volúmenes inventados, limitaciones de SERP declaradas; coherencia con slugs del build. GSC/GBP quedan OWNER. |
| 2.4 | DONE | DISCOVERY §0b contiene11 motivos confirmados; ocho filas del build agrupan los11 (ronquido/apnea, oído/cerumen, amígdalas/niños). Sito fresco y estrategia coherentes. |
| 2.5 | DONE | FIX documental: CAB Medical, pediatría desde0meses y no publicar precios; mensaje publicado respirar/dormir/oír y agenda WhatsApp. Se retiran hipótesis presentadas como exclusividad. |

Fuente: docs/audit/2026-10-12/public-review.json, código/builds y documentos contrastados. No se consultaron secretos, ni se publicó clínica, ni se enviaron formularios. DONE documental no acredita staging ni gates clínicos. Ver docs/REVISION_2026-10-12.md para pruebas y entrega.


## Auditoría REVIEW · grupo3 · 2026-10-12

| ID | Veredicto final | Evidencia / límite |
|---|---|---|
| 3.1 | DONE | FIX sitemap vigente y menu D-072; recursos200 noindex; pilares y muestra clínica404. Catálogo real23 fichas y3 recursos documentado sin publicar. |
| 3.2 | DONE | FIX reglas actuales:9 titles/descriptions públicos coinciden con seo.json, canonicals y robots;8 URLs en sitemap. Longitudes son guía, no edición de marca. Redirecciones/GSC con límites explícitos. |
| 3.3 | DONE | FIX modelo documentado frente a MetaRegistry::definitions: cuatro CPT, prefijo di_, revisor_id, menu_order, estudio_previo texto y sin falsa UI. Implementación UI sigue5.2. |
| 3.4 | DONE | FIX mapa de tipos publicado: HomePhysician, ConócemeProfilePage+Physician, ContactoContactPage+Physician, FAQPage9 preguntas solo FAQ, recursosCollectionPage. CPT descritos desde Graph, no certificados públicamente. |
| 3.5 | DONE | FIX inventario de capas real (IIFEJS, CSS único, versión por filemtime, Adobe Fonts, header/footerPHP). NAP repetido Q-042 y UI pendientes no se presentan como entregados. |
| 3.6 | REVIEW | Tabla de decisiones separada de inventario. Assets prueban Elementor/Pro; Rank Math por sitemap y schema, no inventario completo. Falta listado actual de plugins activos/MU sin secretos. |
| 3.7 | DONE | WhatsApp/teléfono/Maps/Doctoralia con destinos publicados. Eventos allowlist solo page_path; fixtures cola antesload y DNT incluso con gtag ajeno. Salida antes de descargarGA4 puede perder clic: límite declarado. |

Fuente: docs/audit/2026-10-12/public-review.json, código/builds y documentos contrastados. No se consultaron secretos, ni se publicó clínica, ni se enviaron formularios. DONE documental no acredita staging ni gates clínicos. Ver docs/REVISION_2026-10-12.md para pruebas y entrega.


## Auditoría REVIEW · grupo4 · 2026-10-12

| ID | Veredicto final | Evidencia / límite |
|---|---|---|
| 4.3 | DONE | Botones D-053, cards, badges y sprite Lucide en código; sin alterar sombras. Inputs tienen tarea propia Fase8; responsive público cinco anchos sin desbordes. |
| 4.6 | DONE | FIX addendum vigente de tokens, títulos D-049, separadores, migas D-071, logos y tarjeta beige; propuestas A/B quedan históricas. Sin cambiar tipografía/paleta aprobadas. |

Fuente: docs/audit/2026-10-12/public-review.json, código/builds y documentos contrastados. No se consultaron secretos, ni se publicó clínica, ni se enviaron formularios. DONE documental no acredita staging ni gates clínicos. Ver docs/REVISION_2026-10-12.md para pruebas y entrega.

## Entrega técnica 2026-10-12 · Codex
FIXED_LOCAL: calidad95 ligada al derivado metadata y originales1600 a100; Do Not Track en listener; cancelación/puntero secundario y cambio reduced-motion en logos; PHPCS5errores/3avisos corregidos. PHP77, JS aplicación2 + harnesses, bash18 pasan. PHPCS completo0/0. Fixtures de GA4,logos,metadata,editorGD real y hero sin adjunto pasan. Detalle y límites: docs/REVISION_2026-10-12.md y docs/audit/2026-10-12/. No despliegue, SMTP, datos privados ni publicación médica. GD aislado6.8.3 no equivale a WP autenticado7.1.3 ni Imagick. Idempotencia DB de setup-pages requiere staging. La cola GA4 no garantiza entrega si se abandona antes de cargar proveedor; no se cambia D-073.

## Integración concurrente de Claude · 9ad3a67
Durante el push llegó9ad3a67 (fondo decorativo de Qué llevar/FAQ de Primera consulta), integrado sin perder cambios en12e10c4. Se conserva su build/JSON,CSS,widgets,helper y setup-pages. Los cincoPHP nuevos/modificados pasan sintaxis; PHPCS detectó un aviso de alineación en narrative.php, corregido; bash-n setup-pages pasa. Build-pages regenerado conserva exactamente la semántica recibida; solo se añadieron campos bg_image, sin cambios de textos/estados. Fixture con/sinID de hero y fondo deja0placeholders; helper sinID no emitebloque. No se certificó la foto definitiva de pc-fondo ni su contraste/encuadre: la evidencia pública y las capturas anteriores son ANTES de ese fondo, aún sin desplegar según Claude. No cambia el veredicto de las17tareas.
La corrección8b2375c no requiere contenido nuevo. Para activar también el fondo de Claude: archivo aprobado con `bash tools/deploy/upload-media.sh <archivo_aprobado> pc-fondo "Fondo de Primera consulta"`, luego `bash tools/deploy/setup-pages.sh` (conserva estados) y purga. Esa activación es una operación separada de los correctivos de auditoría; validar el fondo real en360/393/430/1440, lectura del texto y ausencia de franjas antes de cerrar su QA. No publicar pilares/CPT por ello. El deploy-code del HEAD incluye el soporte opcional integrado, aunque aún no se asigne el adjunto.


## Veredictos de inventario · 2026-10-13

| Tarea | Veredicto | Evidencia / límite |
|---|---|---|
|1.2|REVIEW| Snapshot actual identifica categorías/páginas/plugins;44adjuntos solo como total y pie/legal solo por recuento. Falta lista individual y clasificación/destino. Clasificación de lo conocido añadida sin borrar nada. |
|1.5|DONE| es_MX,MonterreyUTC−6,formatos escapados,H:i,permalinks,Hello/child,Astra/SiteKit ausentes y hardening por informeWP-CLI local sin secretos. DISPLAY=true conDEBUG=false no bloquea; no se modifica servidor. |
|3.6|DONE| Seisactivos/dosMU y versiones contrastados; PLUGINS actualizado. OpcionesSafeSVG/TwoFactor/QueryMonitor separadas de instalaciones reales. |

Fuente: docs/audit/2026-10-13/inventario-produccion.md. Detalle: docs/audit/2026-10-13/veredictos-inventario.md. Revisión documental, sin nueva extracción autenticada, despliegue ni pruebasPHP/SMTP; no cambió código. Se conserva la evidencia de revisión12/10 como historial. Progreso recalculado desdePLAN; no se concede crédito parcial a1.2.

## Cinco tareas · revisión 2026-10-13

| Tarea | Veredicto | Evidencia / límite |
|---|---|---|
| 6.4 | DONE | FIX→DONE: pública 200/h1/main/schema/migas, narrativa y reduced-motion verificados. Teclado de logos y dos títulos D-049 corregidos y probados localmente; despliegue pendiente. |
| 7.3 | DONE | DONE: Primera consulta/Contacto/FAQ 200, sin Borrador; títulos, FAQPage, fondo continuo y cuatro pasos verificados. Pacientes cubierto por Primera consulta + FAQ. |
| 8.9 | DONE | FIX→DONE: JPEG OG/Twitter 1200×630 e iconos públicos verificados en once rutas; manifest e integración con site icon preparados y probados localmente. |
| 9.4 | DONE | FIX→DONE: GA4 único tras load; seis eventos, DNT y exclusión de editores/ID inválido probados sin enviar a Google. GSC confirmado en DISCOVERY §1.6/D-022; cobertura actual sigue 1.4. |
| 10.1 | DONE | DONE: políticas de optimización aprobadas verificadas con UA Galaxy/iPhone/desktop y pesos registrados. Móvil 1600/q95, escritorio q100, alfa lossless y diferidos. No certifica CWV ni todos los presupuestos antiguos; informe integral en 10.2. |

Informe: docs/REVISION_CINCO_TAREAS_2026-10-13.md. Evidencias five-public/local/visual/summary.json y capturas en docs/audit/2026-10-13/. Los correctivos de logos, títulos, manifest y listener GA4 quedan FIXED_LOCAL, pendientes de despliegue y comprobación pública por Claude. PHP 77, PHPCS sin errores/avisos, JS/bash 18 y regresiones pasan. Emulación Chromium con UA, no Safari nativo; sin SMTP ni eventos reales enviados a Google. Abandonar antes de cargar gtag puede perder un evento (D-073). Fondo PNG de Primera consulta 774 KiB, fallback existente menor de 1200 px; oportunidad en 10.2. 1.2 sigue REVIEW.

## Despliegue D-076 · sitio corto (sesión local, 2026-10-13 18:00)

Desplegados 0679222, f21bed0 y 063ba34: deploy-code (backup `~/deploy-backups/20261013-173450/`), setup-pages, setup-site, deploy-briefing y purga.

| Comprobación | Resultado |
|---|---|
| ORL (170) y Sueño (171) | Siguen en borrador. Público: 404. Render del servidor sin sesión: anclas oido/nariz/garganta/procedimientos y atiendo/ruta; sin «Borrador», sin FAQ, sin enlaces a fichas ni flechas. |
| Portada | 16 enlaces internos con 200. Tarjetas de área, motivos y ruta llevan a /primera-consulta/ mientras ORL y Sueño estén en borrador. |
| /padecimientos/, /tratamientos/, /recursos/ | 404 |
| /briefing/guia/ | 200 con X-Robots-Tag noindex, nofollow. En iPhone/Galaxy: «0 de 2 aprobadas» con las dos páginas. No se pulsaron botones. |
| /briefing/guia/paginas.json | 403 |
| /briefing/revision/ | Aviso «Revisar Otorrinolaringología y Sueño»; `?id=205` abre la ficha. |
| Consola | Limpia, salvo el /favicon.ico 404 ya existente |

**Q-046b · VERIFIED (local, f21bed0):** `revisar.php?action=pages` respondía 405 porque el bloque GET de D-076 iba después del filtro de solo POST, y la guía no cargaba. Se movió delante del filtro.

**Límites:** la vista visual de ORL y Sueño en borrador (anclas bajo la cabecera fija) necesita sesión iniciada y no se verificó en navegador; se comprobó la estructura renderizada en el servidor. Durante la depuración, el token del briefing apareció en la salida de consola de la sesión local; no está en el repositorio ni en documentos. José decide si se rota.

## Publicación D-082 (sesión local, 2026-10-13 18:50)

| Comprobación | Resultado |
|---|---|
| ORL 170 y Sueño 171 | publish; 200; sin «Borrador»; anclas a 96 px del borde en 1440 y Galaxy, con la cabecera de 76/66 px |
| 5 fichas de sueño | 200 en /sueno/{slug}/; rutas /padecimientos/ y /tratamientos/{slug}/ → 301; enlazadas solo desde Sueño |
| 21 fichas en borrador (muestra de 10) | 404 en /padecimientos/, /tratamientos/, /sueno/ y /otorrinolaringologia/{slug}/ |
| Sitemaps | Las 5 publicadas presentes. **Q-048 P2 FIXED (rama, sin desplegar):** incluían /padecimientos/, /tratamientos/ y /recursos/ (404); filtro de archivo y exclusión de recurso en inc/setup.php |
| Robots | 205 tenía noindex de muestra → se borró `rank_math_robots`; ahora index, follow. Las otras 4 y las dos páginas, por defecto |
| Schema y migas | MedicalWebPage + MedicalTherapy; BreadcrumbList Inicio › Sueño › CPAP; canonical correcto |
| Portada, menú y pie | Motivos a anclas de ORL y a /sueno/#atiendo; menú y pie con ORL y Sueño |
| Consola | Limpia en 1440 y Galaxy |
| Lighthouse (antes de D-082) | Accesibilidad 100 salvo Dra. 98/99: **Q-049 P2 FIXED (rama, sin desplegar)** heading-order: h4 de credenciales → h3 |

## D-084 desplegado (sesión local, 2026-10-14 09:45)

3ceba23 con deploy-code (`~/deploy-backups/20261014-091517/`) y purga. En 1440 y Galaxy, en Inicio, ORL, Sueño, Dra. y Contacto: las animaciones di-hero-in y di-hero-photo arrancan al cargar. Al terminar, la posición, el ancho y la opacidad son idénticos a la carga con «reducir movimiento», sin transform y sin animaciones vivas. Con «reducir movimiento» no hay animación. La navegación por el menú dispara una view transition (pagereveal con viewTransition), que no ocurre con «reducir movimiento»; la cabecera lleva view-transition-name `di-header`. Consola limpia. Safari/iPhone real: no probado desde la PC.

**Q-050 · P1 · FIX en la rama (sin desplegar; falta A/B Lighthouse):** h1 y entradilla solo con transform. Antes: con la entrada del hero, el LCP móvil de Sueño pasa de 3,81 a 6,17 s (+2,36 s; 3 de 3 pasadas). El elemento LCP es texto que arranca con opacidad 0. Detalle en PERFORMANCE.md.

## Menú móvil y antispam (sesión local, 2026-10-14 09:45)

- **Menú móvil (edbb4cb, 640f5b0):** rediseño pedido por José («muy feo y tosco… más elegante y sutil, con hovers elegantes»). Opciones numeradas, líneas finas, flecha al pasar o enfocar, página actual en morado (turquesa en la variante nocturna), entrada en cascada y pie con WhatsApp y redes. Verificado en Galaxy, iPhone y 985 px: el panel arranca bajo la cabecera (92 / 112 px), hover morado con flecha, cierre correcto, la barra inferior se oculta con el menú abierto y vuelve al cerrarlo, sin scroll horizontal, consola limpia.
- **Q-051 · P1 · FIXED (cfab0bf):** el formulario dejaba pasar ofertas comerciales escritas a mano (caso real: un SEO con enlace). Añadidos `Contact\SpamGuard` (descarte silencioso por enlaces u ofertas comerciales; envíos sin di_t) y Turnstile opcional. Prueba en el servidor con `classify()` y 8 mensajes, sin enviar nada: 3 spams descartados y 5 de pacientes pasan (ronquido, niño, inglés, factura, vacío). El formulario no se probó en producción porque envía correos reales. **Turnstile inactivo hasta tener claves** (OWNER).

**Q-052 · P1 · FIX en la rama (sin desplegar):** SpamGuard descartaba en silencio mensajes de pacientes con un correo o un dominio en el texto (`\b[a-z0-9-]+\.(com|…)`): «mi correo es juan@gmail.com», «doctoralia.com.mx». Ahora solo http/https/www. Prueba local de classify(): 8/8 correctos. Pendiente: desplegar y que Codex audite falsos positivos.
- **Q-052 · P1 · VERIFIED (3a519f4, desplegado 2026-10-14 09:47):** la regla de enlace de cfab0bf contaba dominios sueltos (gmail.com, doctoralia.com.mx), así que un paciente que escribiera su correo en el mensaje se descartaba en silencio. Ahora solo cuentan enlaces explícitos (http/https/www.). La opción `di_contact_spam` no existía tras el despliegue: **0 descartes** en la ventana del fallo (09:44–09:47), sin mensajes perdidos. Prueba en producción con classify(): correo en el mensaje, hotmail, doctoralia y paciente en inglés pasan; SEO con https, www. y agencia en español se descartan. Error de la sesión local; lo detectó la nube.
- **Turnstile activo (sesión local, 2026-10-14):** las claves de `tools/deploy/turnstile.local.txt` (ignorado por Git; cada línea con etiqueta + clave) se extrajeron y se pasaron al servidor por stdin, sin imprimirlas ni dejarlas en archivos remotos. Opciones `di_turnstile_sitekey` (24 caracteres) y `di_turnstile_secret` (35); `captcha_enabled()` = sí; purga hecha. siteverify con el secreto guardado y un token ficticio devuelve HTTP 200, `success: false`, `invalid-input-response`, lo que confirma que el secreto es válido. En /contacto/ (1440 e iPhone UA) el script y el widget cargan con `interaction-only`, sin errores de consola y con el botón activo. Al navegador automatizado Cloudflare le muestra la casilla «Verifique que es un ser humano»: es lo esperado en modo Managed con un robot. No se envió el formulario (manda correos reales). Pendiente: prueba de José en su teléfono y mención de Cloudflare en el aviso de privacidad.

## Seguridad pública (sesión local, 2026-10-14 14:35)

Comprobación en solo lectura: XML-RPC POST 403; debug.log 403; listado de uploads 403; HSTS, nosniff, X-Frame-Options, Referrer-Policy y Permissions-Policy presentes.

- **Q-053 · P1 · VERIFIED (4abe1ff):** /wp-json/wp/v2/users exponía al administrador (id 1) y /?author=1 redirigía a /author/bellgigagmail-com/, un slug derivado de su correo. `Security\Hardening`: sin sesión, quita las rutas /wp/v2/users; las páginas de autor y ?author= dan 404 antes de la redirección canónica; oEmbed sin autor. Verificado: users y users/1 404, ?author=1 404 sin redirección, /author/… 404, oEmbed sin author_*. Portada, Sueño, Contacto, /wp-json/, la API del sitio y el sitemap con 200.
- **Q-054 · P2 · VERIFIED (bf64525, 2026-10-14 14:39):** deploy-code y `harden-htaccess.sh`: bloque insertado antes del de WordPress, con respaldo `~/deploy-backups/htaccess-20261014-143907`; la segunda pasada no cambia nada. readme.html, license.txt y wp-config-sample.php dan 403; portada, Contacto, Sueño, wp-login, Dra., Primera consulta, ORL, /sueno/cpap/, sitemap, imagen y CSS 200; wp-admin 302 al login; /op/ 302 a /op/entrar/. X-Powered-By 0 (también en estáticos) y meta generator 0. El editor de Elementor y wp-admin con sesión no se probaron porque exigen iniciar sesión: los comprueba José.
- **OWNER:** 2FA del usuario administrador (10.3).

**Q-054 · P2 · FIX en la rama (sin desplegar):** Hardening oculta el generator de Elementor y X-Powered-By de PHP; `harden-htaccess.sh` deja readme.html/license.txt/wp-config-sample.php en 403 y quita X-Powered-By también en páginas cacheadas. Pendiente: deploy-code, el script y purga.

## Auditoría Codex completa · 2026-10-14

Base dec8adb; producción declarada 73f04d6. Informe vigente: docs/REVISION_2026-10-14.md.

| Tarea | Veredicto | Evidencia / límite |
|---|---|---|
| 1.2 | DONE | CSV 44 IDs únicos y clasificación (26 KEEP/17 REMOVE/1 REPLACE opcional), pie/legal detallados; retirada D-075 atestada con respaldo y 322 preservado. 27 adjuntos actuales reportados, páginas sin roturas observadas. |
| 1.4 | DONE | Capturas de José (datos al 8/10), cinco motivos y ejemplos explicados, sitemap Correcto y reenviado 14/10. Sitemap actual incluye cinco fichas y excluye archivos 404; no se infiere cobertura nueva. |
| 6.5 | DONE | Cinco fichas /sueno/ 200, h1/main, sin Borrador, schema y migas/canónicas correctas. Cinco rutas antiguas del tipo correspondiente 301; diez borradores 404. Relacionados filtrados por publish. |
| 7.1 | DONE | ORL/Sueño cortas publicadas, títulos D-049, seis anclas bajo cabecera, cinco enlaces de fichas en Sueño y sin desborde en cuatro anchos. Publicación declarada 13/10 18:50 tras aprobación y OK. |
| 7.6 | DONE | Atestación PLAN/BATON/D-082: revisor_medico aprobó ORL 13/10 18:03 y Sueño 18:38; 26 fichas aprobadas, última 18:18. Backend rol/capacidad/fecha/autor/log revisado; sin nueva lectura del log privado ni aprobaciones de agentes. |
| 7.7 | DONE | OK previo de José documentado D-078/D-082/BATON; publicación local declarada 13/10 18:50. Se corrige fecha histórica 14/10 en notas, sin publicar ni alterar clínica. |
| 8.3 | DONE | Contacto público completo: dirección/mapa/Cómo llegar, estacionamiento/acceso, previa cita D-079, WhatsApp/tel, formulario y urgencias. Captcha DOM presente; entrega real la prueba José, no agentes. |
| 10.2 | DONE | PERFORMANCE antes/después/después 2 y límites auditados; CLS/peso q95 documentados. Nuevo A/B tras Q-050: Sueño móvil LCP 4,446/4,454 s (3 pasadas por modo), CLS 0. Laboratorio, no CWV de campo. |

Hallazgos adicionales:

- Q-048 / Q-049 VERIFIED: sitemaps sin archivos 404/recursos; credenciales h3 y A11y 100 documentada por sesión local. No nueva auditoría completa AA.
- Q-050 VERIFIED: h1/lead sin fundido, fin opacity 1/transform none; A/B nuevo de seis pasadas no reproduce regresión. PERFORMANCE actualizado.
- Q-052 VERIFIED: correos/dominios sueltos pasan. Q-053 / Q-054 VERIFIED: HTTP público y fixtures Hardening; awk extraído del script conserva/restaura reglas. Editor autenticado y 2FA a cargo de José.
- Q-055 P1 FIXED_LOCAL: preguntas legítimas en inglés por services/pricing se descartaban. Regla ahora exige además oferta comercial del remitente; corpus 8/8. No garantiza cero falsos positivos; enlaces explícitos mantienen descarte aprobado.
- Q-056 P1 FIXED_LOCAL: menú abierto dejaba escapar foco. Primer enlace al abrir, ciclo Tab/Shift+Tab, Escape/restauración y resize pasan en normal/reducido. Barra inferior oculta y captura local revisada.
- Q-057 P2 FIXED_LOCAL: reset Turnstile añadido a error de red/JSON además de rechazo HTTP. Dos POST sintéticos interceptados, cero envíos reales. Siteverify simulado: válido/inválido/vacío/offline; contadores sin datos personales. Fail-open solo al fallo server-side del proveedor, no a token vacío.

PHP 79 sin errores, PHPCS completo sin avisos/errores; JS y bash 18 pasan. public.json = estado público; ui.json menú/form = JS local sobre HTML público, no prueba despliegue. Lighthouse A/B con GA bloqueado, laboratorio Windows; no CWV de campo ni Safari nativo. No claves, log privado, publicación, borrado, SMTP ni despliegue. D-080 elimina el gate de staging; límites autenticados se documentan. Evidencia en docs/audit/2026-10-14/.

## Despliegue de la auditoría de Codex (sesión local, 2026-10-14 16:30)

Codex 11f8412/32a394b con deploy-code (`~/deploy-backups/20261014-162703/`) y purga; sin builds, publicaciones ni claves. SpamGuard en el servidor, sin correo: un paciente que pregunta precios en inglés y un mensaje con correo pasan; SEO con enlace, oferta en inglés sin enlace y agencia en español se descartan (Q-055 VERIFIED). Menú con teclado a 390: Enter abre con foco en «Inicio»; Tab recorre 6 opciones, WhatsApp, 4 redes y el botón, y vuelve sin salir del panel; Shift+Tab correcto; Escape cierra, devuelve el foco al botón y quita menu-open. Ensanchar a 1200 lo cierra. Con «reducir movimiento» no hay animación (opacidad 1). Consola limpia (Q-056 VERIFIED). Fallo restante encontrado: al pasar de 390 a 985 con el menú abierto, el panel quedaba en 92 px con la cabecera en 112, porque la cabecera crece por transición después del evento resize. Corregido en 6e6bb37 con un ResizeObserver sobre la cabecera (backup `…-162853/`): 985 → 112/112, 390 → 92/92, 800 → 92/92. Q-057 (reset del captcha tras fallo de red) desplegado; no se probó, porque exige provocar un fallo de red en un envío real.

## SEO on-page bc65946 (sesión local, 2026-10-14 17:00)

deploy-code (`~/deploy-backups/20261014-165612/`), setup-pages (16 entradas OK, ninguna «sin página») y purga. Verificados en vivo título y descripción de la portada (59/145 c), Dra. (57/155), ORL (57/146), Sueño (55/147) y aviso de privacidad (46/142): iguales a seo.json. Las 5 fichas tienen el título nuevo con «Monterrey» (59–62 c) y conservan el extracto como descripción (152–158 c). JSON-LD: ORL y Sueño con MedicalWebPage, lastReviewed 2026-10-13, reviewedBy #physician, specialty Otolaryngologic; en Sueño, about Apnea y Ronquido. validator.schema.org con /sueno/: 0 errores, 3 advertencias. **Q-058 · P2 · VERIFIED (c7bb5b5, 2026-10-14 17:10):** el nodo #physician pasa a ["Physician","Person"]. Se sirve así en la portada, Dra. y Sueño, también a los UA de Googlebot y del Structured Data Testing Tool. validator.schema.org da 0 errores y 0 advertencias en /sueno/ (BreadcrumbList + MedicalWebPage) y en /dra-irina-gonzalez-saez/ (BreadcrumbList + ProfilePage). Nota: el validador conserva el último resultado si solo cambia el fragmento #url; hay que recargar la página para una lectura nueva.

## Accesibilidad 10.4 cae491d (sesión local, 2026-10-14 17:20)

deploy-code (`~/deploy-backups/20261014-171813/`) y purga; php -l de ubicacion.php y hero.php OK; node --check OK. Verificado en 1440 y Galaxy: #di-map con role=region y mapa renderizado en la portada y en Contacto; `.di-btn--primary` rgba(140,78,170,0.92); `.di-hero-trust` con aria-label «Credenciales», tabindex=0 solo en Galaxy (desplazable) y sin tabindex en escritorio (no desplazable); consola limpia. Lighthouse 12.2.1 de accesibilidad: Contacto 100/100 y /sueno/cpap/ 100/100, en móvil y escritorio, sin fallos. Pendiente fuera del alcance de los agentes: prueba manual con VoiceOver y TalkBack.

**Q-059 · INVALID — no es una vulnerabilidad (descartado por la sesión local, 2026-10-14):** las peticiones de la nube van autenticadas con la contraseña de aplicación «claude-ops» del admin (canal REST de la tarea 1.1, D-018/D-021), que la salida de red de este entorno añade como Basic Auth sin que la sesión vea la cabecera (httpbin no lo mostró porque la inyección es por dominio). Desde la PC de José, anónimo: users/me 404, settings 401, abilities 401, MCP 401/403. Sin plugins que fijen el usuario, sin administradores nuevos, sin cambios ajenos en 48 h. Lección: las pruebas «anónimas» de REST desde la nube no son anónimas; para eso, la sesión local. Texto original: peticiones REST anónimas (sin cookies ni Authorization; el proxy de la nube no inyecta cabeceras, comprobado con httpbin) se ejecutan como el administrador id 1: `GET /wp-json/wp/v2/users/me?context=edit` devuelve id, usuario y correo del admin; `GET /wp-json/wp/v2/settings` (solo admin) 200; `wp-abilities/v1/abilities/core/get-user-info/run` devuelve el admin y `core/get-environment-info` WP 7.1.3 / PHP 8.4.26 / MariaDB. 32 abilities listadas, entre ellas rank-math/set-* (escritura; no probadas). Explica la regresión de Q-053 (`/wp/v2/users` 200: Hardening ve is_user_logged_in() = true). Namespaces nuevos: `mcp` (mcp-adapter-default-server, mcp-oauth-server), `elementor-mcp-composer/v1.0.19`, `elementor/v1/mcp-proxy`. Pendiente: confirmar desde otra red (PC de José), identificar el filtro determine_current_user/plugin responsable, desactivarlo con OK de José, auditar administradores, application passwords y cambios recientes, rotar contraseña y cerrar sesiones. Solo lecturas en las pruebas.

## Alerta de la nube «REST anónimo como admin» (sesión local, 2026-10-14 17:50): descartada

La nube veía /wp/v2/users/me como admin y /wp/v2/settings con 200. Desde la PC de José, sin cookies, el anónimo no es admin: users/me 404, settings 401, abilities 401. Causa: la contraseña de aplicación «claude-ops» del admin (creada el 2026-10-07; último uso el 2026-10-14 23:47 UTC desde 160.79.106.133, el entorno de la nube), es decir, el canal REST de la tarea 1.1. Solo hay hooks de autenticación del núcleo; plugins y MU conocidos; 1 admin y 1 revisor_medico; los cambios de 48 h son despliegues propios. Rutas MCP de Elementor 4.3.5 (autoactualizado) y abilities: 401/403 para anónimos (mcp-proxy 401 con parámetros completos). No hubo acciones correctivas. **Decisión de José (2026-10-14):** revocar «claude-ops». Revocada con `wp user application-password delete`; el admin queda con 0 contraseñas de aplicación. La nube ya no tiene canal REST de administrador; los despliegues siguen por SSH desde la PC.


## Segunda auditoría Codex · 2026-10-14b

Base44221ba; producción declarada edf8c45. Informe docs/REVISION_2026-10-14b.md.

| Tarea | Veredicto | Evidencia / límites |
|---|---|---|
| 1.9 | DONE | SECURITY contrastado: HTTP anónimo users/author404, settings/Ops401, XML-RPC/readme403; cabeceras front y versiones ocultas. 2FA por atestación local; secretos ignorados/no versionados, sin pentest. |
| 2.6 | DONE | FIX estrategia/keyword map alineados con dos áreas cortas y cinco fichas; ORL individual/recursos/navegador históricos, sin volumen/ranking inventado. |
| 3.8 | DONE | FIX arquitectura/mapa: diez páginas+cinco fichas, rutas Sueño, archivos404, mini apps/gate, sin staging y D-081/083/084. Propuestas antiguas como historia. |
| 5.7 | DONE | PHPCS/PHP79/JS externo+inline/bash18 pasan; permisos REST propios 7/7, corpus antispam/regresiones, escapes/nonces/admin/revisar revisados. Sin mutaciones; Q-040/Q-042 conservan alcance. |
| 6.9 | DONE | FIX Q-060 helper para CPT publicados en /sueno/{slug}/ con taxonomía/ancla; 7/7 fixtures. Widgets/templates/filtros publish revisados, sin borrar legacy. Correctivo local pendiente despliegue. |
| 9.6 | DONE | QA quince200/h1/main/canónicas, longitudes≤62/158 y OG/schema/migas. Robots/redirecciones/sitemaps contrastados; sin inferir indexación. |
| 10.5 | DONE | PERFORMANCE, SECURITY, axe32 muestras y LH a11y seis100 revisados. Sin CWV de campo/pentest/lector de pantalla ni certificación universal AA. |
| 11.4 | DONE | FIX documentos activos y coordinación coherentes con D-076…085. README/HANDOFF sin staging ni envío de URL con token; historia etiquetada, gate médico intacto. |
| 9.1 | DONE | Quince indexables200, h1/main único, canónica propia, title39–62/description64–158, OG presente. Sin nuevos cambios de copy/build. |
| 9.2 | DONE | Sitemap page10/condicion2/tratamiento3 sin archivos404/recursos, robots público correcto. Dominio aparcado raíz301 confirmado; cinco rutas antiguas301 según evidencia14/10. |
| 9.3 | DONE | Q-058 confirmado: Physician+Person único, ProfilePage/ContactPage/FAQPage/MedicalWebPage de áreas/fichas y BreadcrumbList. Validator0/0 Sueño/Dra. por atestación local, no nueva validación externa universal. |
| 10.3 | DONE | SECURITY revisado y medidas contrastadas; Two Factor instalado/WP2FA activo por atestación local14/10 18:20, Hostinger/Google por propietario. Revocación REST y D-085 respetados; no login ni secretos. |
| 10.4 | DONE | Axe4.14: quince+404×1440/390, cero violaciones detectadas; incomplete contraste no aprobado manualmente. LH12.2.1 a11y seis100 y menú público teclado normal/reducido pasan. Lector de pantalla pendiente explícito según encargo. |

Q-058 VERIFIED: tipo doble Physician+Person y ausencia de nodo profesional duplicado confirmados; validación externa histórica de Sueño/Dra. aportada por Claude. Q-059 INVALID: respuestas de admin previas a revocación no prueban exposición pública; nuevas peticiones anónimas confirman denegación. Se sustituye cualquier recomendación de investigar/rotar por esa alerta descartada, sin reabrir decisión.

Q-060 P2 VERIFIED (desplegado 2026-10-14 18:36, sesión local; antes FIXED_LOCAL): helper de URL publicada trataba /sueno/{slug}/ como page. Ahora consulta condicion/tratamiento publish por slug y area=sueno, conserva ancla y excluye borrador/otra área; fixture 7/7. No altera copy, estado clínico, menú ni sombras. Despliegue a cargo de Claude.

Axe4.14.0 (distinto del4.10 anterior): 32 muestras sin violaciones detectadas; incomplete color-contrast permanece como comprobación manual, no un pass automático. Lighthouse12.2.1 accesibilidad100 en Contacto/CPAP/Dra. × móvil/escritorio. No certificado AA/lector de pantalla/Safari ni CWV de campo. PHP79/PHPCS/JS2+inline4/bash18 y regresiones pasan; permisos REST7/7. GET sin sesión/Authorization; cuerpos privilegiados no leídos, todos POST bloqueados en harness. Sin SMTP ni tokens reales. Evidencias docs/audit/2026-10-14b/. 2FA/roles por atestación local, no nuevo login. Secretos ignorados/no versionados, sin análisis forense de historial.

Support14b: comprobación GET nueva de OG JPEG1200×630, sitemap10/2/3 y cinco301 médicos. Home/Contacto con cabeceras completas; Links/Gracias noindex, archivos404 (meta index/follow no convierte un HTTP404 en página indexable). Sin cambio de robots necesario.

## Despliegue de la segunda auditoría de Codex (sesión local, 2026-10-14 18:40)

3928e49 (Q-060 en e0352be, template-tags.php) con deploy-code (`~/deploy-backups/20261014-183634/`) y purga; sin builds, publicaciones ni claves. Recorrido de las 15 URL públicas: 15 enlaces internos distintos, todos 200 sin redirección. Las 5 fichas se enlazan en /sueno/{slug}/ (canónico) y ningún enlace usa /padecimientos/ o /tratamientos/. Las 21 fichas y recursos en borrador dan 404 en sus 5 rutas posibles (105 de 105). Q-060 VERIFIED. Progreso de Codex: 67/90 DONE (73,84 %).

## Cierre: FAQ y navegadores (sesión local, 2026-10-14 18:55)

- Entradilla de FAQ (e434d2a) cargada con setup-pages y purga; en vivo «Las dudas sobre tu caso se resuelven en la valoración.» y ya no aparece «página de padecimiento».
- **11.2, Edge 155 (Chromium) headless:** 15 URL + 404 a 1440 y 390 (32 vistas). Todas con un h1, main, cabecera y pie; sin scroll horizontal, sin imágenes rotas y con la consola limpia (solo el 404 esperado de la página de error).
- **Q-061 · P2 · VERIFIED (1ce1cd7):** el `404_title` de Rank Math estaba guardado como «Page Not Found %sep% %sitename%». Aplicado `option patch update` a «Página no encontrada %sep% %sitename%» y purga; /no-existe-qa/ da HTTP 404 con el título «Página no encontrada | Dra. Irina González Sáez».
- **11.2, Firefox 158 headless** (descargado por Selenium Manager a su caché con OK de José, sin instalarlo en el sistema): las mismas 32 vistas, todas con un h1, main, cabecera y pie, sin scroll horizontal ni imágenes rotas, con CSS y la tipografía Iskra cargados; menú móvil abierto en top 92. Firefox no expone la consola a Selenium, así que no se comprobó.
- **Pendiente para 11.2:** Safari en iPhone real, lo prueba José.


## Perfil de Negocio de Google, PLAN 9.5 (sesión local, 2026-10-16 12:30)

Editado en el Chrome del consultorio (cuenta que administra el perfil), con Claude in Chrome y el OK de José a cada decisión. Sin contraseñas ni verificaciones; no se tocaron nombre, dirección, mapa, teléfonos, horario, áreas de servicio, fotos, reseñas ni publicaciones.

| Campo | Antes | Después | Estado en Google |
|---|---|---|---|
| Sitio web | https://www.drairinagonzalez.com/ | https://drairinagonzalez.com/?utm_source=google&utm_medium=organic&utm_campaign=gbp | Aplicado |
| Facebook | profile.php?id=61578579501339&mibextid=wwXIfr&mibextid=wwXIfr | profile.php?id=61578579501339 (Instagram igual) | Pendiente (≈10 min) |
| Descripción | «Otorrinolaringólogo, especialista en desórdenes respiratorios del sueño, ronquido, apnea y Rinologia» | Texto del encargo (383 caracteres) | Aplicado |
| Categorías | Otorrinolaringólogo | Otorrinolaringólogo (principal) + Clínica del sueño | Guardada (en revisión con los servicios) |
| Reservas | www.doctoralia.com.mx | Doctoralia + https://wa.me/528123685381 | Guardado |
| Servicios | 6 (estudios de sueño, rinología, trastornos respiratorios del sueño, limpieza de oídos, cuerdas vocales, endoscopia de oído) | +7 sin precio: Consulta de otorrinolaringología, Ronquido y apnea del sueño, Estudio del sueño en casa, CPAP, Cirugía de ronquido y apnea, Endoscopia nasal, Rinoplastia funcional y estética | Pendiente de revisión (hasta 1 día) |

- Catálogo de categorías: «sueño» → solo Clínica del sueño; «otorrino» → nada (ya es la principal); «cirujano» → Cirujano, Cirujano oral/oncólogo/plástico, ninguna de ORL.
- Solo reportado: horario lun cerrado, mar–vie 10:00–12:30, sáb 9:00–15:00, dom cerrado (pendiente de la Dra.); áreas de servicio García, Mitras Poniente, Escobedo, Parque Industrial Mitras 66023; aviso «Google actualizó tus atributos» de accesibilidad sin aceptar.
- Límite: «aplicado» es lo que muestra el editor; la ficha pública puede tardar en reflejarlo.
- Descripción aplicada, literal (383 caracteres; es el texto del encargo de Claude cloud del 2026-10-16 que la sesión local reportó como aplicado sin cambios):

  > Otorrinolaringóloga certificada por el Consejo Mexicano de ORL y CCC, con subespecialidad en trastornos respiratorios del sueño. Atiende oído, nariz y garganta en niños desde los 0 meses, adolescentes y adultos, y ronquido y apnea del sueño con estudio del sueño en casa, CPAP y cirugía. Consultorio en CAB Medical Headquarters, Cumbres, Monterrey. Consulta previa cita por WhatsApp.

  Cotejo con el sitio (builds): «Consejo Mexicano de ORL y CCC», «desde los 0 meses», CAB Medical Headquarters · Cumbres y «previa cita» (D-079) coinciden; el sitio dice «desórdenes respiratorios del sueño» y el perfil «trastornos respiratorios del sueño» (sinónimos, sin cambio de claim). Límite: la transcripción viene del encargo y del reporte de la sesión local, no de una lectura nueva del perfil.

## Auditoría de cierre Codex · 2026-10-16

Base3d47aa9, producción declarada d7e14ac. Informe docs/REVISION_2026-10-16.md.

| Tarea | Veredicto / estado | Evidencia y límites |
|---|---|---|
| 1.3 | DONE | Quince200/h1/main/canónicas propias, 80vistas, noindex auxiliares6, sitemap10/2/3, robots/cabeceras/cache contrastados; baseline PERFORMANCE, sin datos de campo nuevos. |
| 6.7 | DONE | 16rutas×5anchos sin overflow; hero final y reduced-motion/transition comprobados. Q-050 A/B14/10 vigente, no nuevo CWV. Logos pendientes lazy resueltos, error captcha del harness aclarado. |
| 6.8 | DONE | Dos hojas de contacto versionadas390/1440 (16rutas top/medio/pie) vistas; cuatro capturas nativas Home390/768/1440 y Contacto390 revisadas. Sin cambios visuales ni certificación Safari. |
| 11.1 | DONE | Quince destinos internos200, WhatsApp/tel/mapa únicos; formulario soloContacto, prueba humanaJosé15/10 según atestación, no repetida. CookiesGA4 por muestra escrita15/10; GA bloqueado en nueva prueba. |
| 11.2 | REVIEW | Chromium80vistas; Edge155/Firefox158 por reporte local32cadauno; SafariiPhone por atestaciónJosé15/10. Falta pasada explícita actual Android real; emulación de ancho y Samsung de composición anterior no la sustituyen. Firefox sin comprobación de consola. |
| 11.3 | DONE | NAP idéntico en10nodos profesionales; contacto/footer coherentes, horario oculto y Previa cita. FAQ vieja ausente, e434d2a en vivo; sin Borrador/placeholders. Claims/fechas por aprobación D-078/D-082, sin nueva revisión clínica. |
| 11.5 | DONE | FIX checklist con trazabilidad por bloque y límites: auxiliaresnoindex, validaciónschema no universal, GSC fechado, Android y rollback pendientes. 9.5/8.6OWNER explícitos;11.6DONE por aprobación concurrente José D-086, no por inferencia del auditor. |
| 12.4 | DONE | FIX rutinas/KPIs sin cadencia automática de fichas; eventos no pacientes/correos, objetivo indexación solo15indexables. Runbook código/htaccess/DB/medios/miniapps con rollback no probado; seguimiento7/30 no crea automatización. |
| 5.3 | DOING | PracticeSettings existe y alimenta schema/helpers/formulario; NAP duplicado en builds (FAQ/Contacto), Q-042 OPEN. No fuente única completa todavía. |
| 5.4 | DONE | Graph propio y filtro RankMath contrastados: fixture conserva WebSite/WebPage/Breadcrumb/Image y elimina duplicados profesionales. Diez nodos públicos, uno por página, mismoNAP; Q-058 verificado. |
| 12.1 | DOING | Backups previos de tema/core y despliegues atestados; docs/ROLLBACK.md revisado, restauración real no ensayada. NoDONE; respaldo de código no revierte DB/contenido. |
| 9.5 | OWNER | EdiciónGBP16/10 por sesión local atestada; URLUTM200/canónica limpia y WhatsApp coinciden con sitio. Q-062 falta literal descripción nueva383caracteres para cotejo; no se accede al perfil. Siguen horario real y Doctoralia. |

FIX documentales completados: checklist con fuentes y pendientes; mantenimiento sin publicación mensual automática y con objetivos/eventos correctamente definidos; runbook de rollback con alcances separados y sin falsa prueba de restauración. Sin cambios PHP/JS/CSS ni builds de producción.

**Q-062 · P2 · VERIFIED por Codex16b (literal383 y sitio cotejados; no lectura nueva del perfil):** literal añadido en «Perfil de Negocio de Google». Original: QA de Google solo conserva «texto del encargo (383 caracteres)» para la nueva descripción. Falta transcripción literal para compararla con el sitio. Claude debe añadirla desde su registro existente, sin secretos; Codex no entra al Perfil ni a Doctoralia. URLUTM200/canónica portada, reservaWhatsApp coherente; no se certifica el perfil actual ni la publicación de servicios en revisión. 9.5OWNER permanece.

11.2REVIEW hasta pasada explícita de Android real. Chromium Windows con cinco anchos no se renombra Chrome Android. SafariiPhone y prueba humana del formulario15/10 aceptados como atestaciones del encargo/PLAN/checklist; no nuevos ensayos de Codex. 11.5DONE acredita documento revisado;11.6DONE se incorpora de D-086, aprobación expresa concurrente de José. 12.1DOING: no ejecutar rollback/despliegue por esta auditoría.

80vistas sin desbordes;15destinos internos200,15canónicas propias,10nodos profesionales con unNAP. Seis auxiliaresnoindex; sitemap10/2/3. Logos marcados inicialmente como imageCandidates estaban pendientes lazy: eager soloDOM carga18/18, sin rotos. Un TurnstileError inicial por bloquear tráfico noGET del proveedor; followup permite Cloudflare manteniendo POSTsitio bloqueado y no registra errores. No borrar estas señales ni tratarlas como fallos de producción.

Fotos/hojas nuevas inspeccionadas; resumen comprimido no mide proporciones/legibilidad, cuatro PNG nativos complementan móvil/tablet/escritorio. GA y Doctoralia bloqueados; cookiesGA4 por evidencia escrita15/10, no nueva garantía de terceros. PHP79/PHPCS/JS2+harnesses/bash18 pasan; fixture schema sin DB ni validador externo. Sin formulario/SMTP, secretos, claves, publicación, borrado, despliegue ni acceso a perfiles. Evidencia en docs/audit/2026-10-16/.

Integración remota al subir:27765b2 registra D-086 y11.6DONE; se conserva sin reabrir aprobación. b06ea87 añade enlace público de reseñas y reglas en MAINTENANCE, preservados sin visitar el perfil. Progreso final197/237=83,12%,76/90DONE;9.5/8.6OWNER como mejora continua.

## Día 0 y Día 1 del lanzamiento, PLAN 12.2 (sesión local, 2026-10-16 13:00)

Todo en solo lectura: sin formularios, SMTP, despliegues ni cambios de ajustes.

**Público**
- Las 15 URL del sitemap (page/condicion/tratamiento) dan 200, `index, follow` y canónica propia. TTFB en caché (hit de LiteSpeed): 0,21–0,29 s; sin caché, 0,54–1,19 s.
- Redirecciones 301: http → https, www → sin www (con ruta) y /padecimientos/ y /tratamientos/ → /sueno/{slug}/. La URL con UTM del Perfil de Google da 200.
- /links/ y /gracias/ dan 200 con noindex. La 404 real tiene título en español. /readme.html y /xmlrpc.php dan 403; /wp-json/wp/v2/users da 404.
- robots.txt y sitemap_index son correctos.
- Cabeceras: HSTS, nosniff, SAMEORIGIN, Referrer-Policy y Permissions-Policy, sin X-Powered-By. SSL de Let's Encrypt válido hasta el 07-ene-2027.
- Chrome headless a 390 px (UA Android) y 1440 px, 30 vistas: un h1 por página, sin desborde horizontal ni imágenes rotas. En consola solo aparece, en Inicio y Contacto, el aviso de `google.maps.Marker` obsoleto (Q-041 WONTFIX según D-065: estilo JSON y marcador clásico; no se pide Map ID).

**Servidor**
- WordPress 7.1.3 con checksums correctos; PHP 8.4.26; tema irina-gonzalez 0.1.0 sobre Hello 3.5.1.
- Plugins sin actualizaciones pendientes. Checksums de wp.org correctos en 4 de 7; los otros 3 (dra-irina-core, elementor-pro y sensia) no están en wp.org.
- Cron sin eventos vencidos. 0 transitorios caducados. 89 KB de opciones autocargadas.
- Usuarios: admin con 2FA y revisor_medico, ninguno con contraseñas de aplicación. Registro cerrado y blog_public 1.
- Turnstile con claves; contador de SpamGuard en 0.
- Contenido: 12 páginas publicadas; fichas publicadas: 2 condiciones y 3 tratamientos; el resto en borrador. Sin cambios de contenido desde el 14/10.
- Sin errores PHP del dominio: no hay error_log ni debug.log en el dominio, `~/error_log` no tiene ninguna línea del dominio y WP_DEBUG y la depuración de LiteSpeed están apagados.

**Hallazgos nuevos (P3, para la nube)**
- **Q-063 · P3 · VERIFIED (19e4440, desplegado 16/10 13:37):** Hardening::author_request convierte ?author=/author_name en 404 en el filtro `request` (sin sesión, fuera de wp-admin). Original: /?author=1 da 404 (correcto), pero con el título «José | Dra. Irina González Sáez»: muestra el nombre visible del administrador. No expone el usuario ni el correo. Propuesta: que el 404 de Hardening use el título de la 404.
- **Q-064 · P3 · VERIFIED (19e4440, desplegado 16/10 13:37):** setup.php convierte el archivo de condicion/tratamiento/recurso en 404 en el filtro `request`; también /padecimientos/ y /tratamientos/, que tenían el mismo «… Archive» con index (comprobado en vivo). Original: /recursos/ da 404 con el título «Recursos Archive» (en inglés) y `index, follow` en el HTML. Google no lo indexa por el 404; es solo cosmético.

**Límites**
- Los registros de acceso (404 y 5xx de visitas reales) solo se pueden ver en hPanel, no por SSH. El monitor 404 de Rank Math está apagado.
- No se probó el formulario; José lo probó el 15/10.
- 12.2 queda en REVIEW; el seguimiento del día 7 (≈22/10) es la 12.3.

## Despliegue de Q-063/Q-064 y ensayo de restauración 12.1 (sesión local, 2026-10-16 13:40)

**Q-063/Q-064 (19e4440).** Desplegado con deploy-code (respaldo `~/deploy-backups/20261016-133733/`) y purga de LiteSpeed; sin builds, contenido ni ajustes.
- Sin sesión: /?author=1, /?author_name=…, /author/admin/, /recursos/, /padecimientos/, /tratamientos/ y /?post_type=condicion dan 404 con el título «Página no encontrada | Dra. Irina González Sáez» y `follow, noindex`.
- Regresión: portada y 4 páginas en 200; las 5 /sueno/{slug}/ en 200 con su título; /links/ en 200 con noindex; las 301 de /padecimientos/ronquido/ y /tratamientos/cpap/ siguen; los borradores (reflujo-laringofaringeo en /sueno/ y /padecimientos/) siguen en 404. Sitemaps 3 / 10 / 2 / 3 loc, sin cambios.
- Con sesión, por WP-CLI aplicando el filtro `request` (sin entrar a wp-admin): con el admin, ?author=1 pasa y la vista previa de un borrador pasa; sin sesión, ?author=1 y el archivo de condicion dan 404, y /sueno/{slug}/ pasa. Falta que José compruebe en wp-admin el listado de fichas y el filtro por autor.

**Ensayo de restauración 12.1 (solo código, sin tocar producción; decisión de José: «Ensayo en copia aparte»).** Se siguió docs/ROLLBACK.md en una carpeta de prueba fuera del webroot:
1. Respaldo elegido: `~/deploy-backups/20261014-183634/` (previo al despliegue de 3928e49). Contiene style.css, functions.php y dra-irina-core.php; 73 archivos del tema y 44 del plugin.
2. Copia del estado actual de los dos árboles, para poder deshacer.
3. Restauración de los árboles completos en el destino de prueba (no encima): 39 ms.
4. Verificación:
   - lo restaurado es idéntico al respaldo (diff -r);
   - PHP lint de 79 archivos, sin errores;
   - la única diferencia con producción es `inc/template-tags.php`, igual que `git diff cae491d 3928e49` en esas carpetas;
   - producción quedó sin tocar.
5. La carpeta de ensayo se borró.

**Límites del ensayo.** No se cambió producción y no se purgó caché: la restauración real en vivo no se ensayó.
- **Base de datos:** no hay volcado propio en ~/deploy-backups. El usuario MySQL solo tiene permisos sobre la base del sitio, así que no se puede crear una base aparte por SSH. Los respaldos de la base son los diarios de Hostinger (hPanel).
- **12.1 sigue DOING** hasta que José decida cómo cubrir la base de datos.
- **Base de datos (decisión de José, 2026-10-16):** «respaldo propio + ensayo en base de prueba».
  - `deploy-code.sh` guarda ahora `db.sql.gz` (privado, 600, fuera del webroot) antes de cada despliegue y aborta si el volcado no termina bien. Probado con un despliegue del mismo código: `~/deploy-backups/20261016-134414/db.sql.gz`, 412 KB, 30 tablas (igual que en vivo).
  - `tools/deploy/drill-db.sh` restaura ese volcado en una base de prueba que José crea en hPanel y compara las filas de cada tabla con producción. Protecciones probadas: se detiene si apunta a la base de producción o si no conecta, y borra su archivo de credenciales. La clave se lee de `drill-db.local.txt`, fuera de Git, y nunca se imprime.
  - Pendiente: que José cree la base de prueba.
- **Ensayo de la base (2026-10-16 14:05):** José creó en hPanel la base de prueba u855694717_drill y el archivo local de credenciales (la nube le guió sin ver la clave). `drill-db.sh 20261016-134414`:
  - importación completa en 1 s;
  - **las 30 tablas con el mismo número de filas que producción**;
  - producción solo se leyó;
  - el archivo temporal de credenciales en el servidor quedó borrado (0 restantes).
  La «distinta» del informe era una línea vacía de la lista de tablas, no una tabla; el script ya la ignora.
  José borra la base y el usuario «drill» y el archivo local. **Hecho (José, 2026-10-16):** base u855694717_drill y su usuario eliminados en hPanel y drill-db.local.txt borrado de su PC.
  Con el ensayo del código y este, **12.1 → REVIEW** para Codex. Límite: la restauración sobre el sitio en vivo no se ensayó, por decisión de José («copia aparte»).

## Android real, PLAN 11.2 (José, 2026-10-16)

Samsung Galaxy S24+, Chrome, con la checklist de 7 puntos (Inicio, menú, WhatsApp/Llamar, Primera consulta, Contacto con mapa y «Cómo llegar» sin enviar el formulario, una ficha de Sueño, giro a horizontal). Reporte de José: «todo bien». Única observación: en horizontal la barra inferior «WhatsApp / Llamar / Cómo llegar» restaba alto útil.

- **Q-065 · P3 · FIXED_LOCAL (Claude cloud, 2026-10-16; probado en harness, sin desplegar):** con `max-width: 899px`, `orientation: landscape` y `max-height: 500px` la barra pasa a una columna de 3 botones redondos de 46 px a la derecha, bajo el ☰ (mismo eje y tamaño), sin reservar alto abajo. Propuesta de José. El texto sigue dentro del enlace como nombre accesible. Comprobado con Chromium sobre el HTML público y el CSS local a 832×340 (Inicio, Contacto, Sueño): columna en x=754 alineada con el ☰ (x=754), sin desborde, `padding-bottom` 0; en vertical (384×760) la barra inferior sigue igual. Límite: harness con CSS local, no demuestra el despliegue; falta que José lo vea en su S24+.


## Despliegue de D-087 / Q-065, de864bc (sesión local, 2026-10-16 14:25)

Desplegado con deploy-code (respaldo `~/deploy-backups/20261016-142116/`, con db.sql.gz de 412 KB y 30 tablas) y purga de LiteSpeed. Sin setup, regeneraciones ni flush. PHP lint en el servidor de los dos archivos cambiados: sin errores. Portada, Contacto, Sueño y wp-login dan 200.

- **Q-065 · VERIFIED** (Chrome con emulación de Galaxy S24+, Inicio y Contacto):
  - 832×340 en horizontal: la barra es una columna flex de 3 botones de 46×46 px (WhatsApp, Llamar, Cómo llegar) en top 100 px y right 32 px. Su eje (cx 777) coincide con el del ☰ (cx 777). Sin desborde y con el padding-bottom del body en 0.
  - 384×760 en vertical: sin cambios; barra inferior en grid de 3 × 124×52 px y padding-bottom de 72 px.
- **Menú de wp-admin (D-087)**, comprobado con los ganchos `admin_menu` ejecutados como el admin por WP-CLI, sin Elementor:
  - un solo elemento de primer nivel «Dra. Irina» [dra-irina-practice];
  - submenús: Consultorio, Padecimientos, Tratamientos, Recursos, Credenciales, Áreas y Zonas;
  - los 4 tipos de contenido tienen show_in_menu = dra-irina-practice;
  - el filtro parent_file resalta «Dra. Irina» en taxonomy=area y no cambia category.
- **«Estado médico» no aparece al admin, y es lo correcto:** su capacidad es `approve_medical_content`, que solo tiene revisor_medico (admin: no). Es la puerta médica, ya era así antes de D-087 y solo la ve quien aprueba.
- **Incidencia de la prueba (no del sitio):** un primer intento con `wp eval-file --context=admin` cargó Elementor Pro en un contexto de administración de CLI incompleto y dio un error fatal (`editor-one-trait.php:14`, get_modules() sobre null). WordPress registró el modo de recuperación y **envió al correo del administrador el aviso de «problema técnico»** (recovery_mode_email_last_sent a las 14:21). El sitio en vivo no se vio afectado (200 en las URL comprobadas). No usar `--context=admin` con Elementor activo.
- **Pendiente de José, en wp-admin con su sesión:**
  - el menú se ve como arriba;
  - edit.php?post_type=condicion y edit-tags.php?taxonomy=area abren con «Dra. Irina» desplegado;
  - los ajustes de Consultorio guardan;
  - la vista previa de un borrador abre.
- **11.2:** con Q-065 en producción ya se puede cerrar con la prueba en el S24+ de José (QA «Android real»).
- **José en producción (2026-10-16, tras fd2479d):** «todo bien». En el Galaxy S24+ en horizontal, la columna de 3 botones bajo el ☰. En wp-admin: el menú único «Dra. Irina» con 7 submenús, fichas y Áreas con el menú desplegado, Consultorio guarda y la vista previa de un borrador abre. «Estado médico» no se muestra al admin porque su capacidad es approve_medical_content (puerta médica, sin cambios). Con esto, la evidencia de 11.2 (Android real) queda completa para que Codex la cierre.

## Auditoría16b · operación y copia de restauración (Codex,2026-10-16)

Base de864bc contiene e58c91c; producción declarada19e4440. Informe docs/REVISION_2026-10-16b.md. Instrucción reciente de José prevalece sobre el encargo16b histórico que aún pedía proponer Q-063/064 y MapID.

| Tarea | Veredicto | Evidencia / límites |
|---|---|---|
| 12.1 | DONE | FIX→DONE: ensayos aportados en copia aparte (código idéntico/lint79; DB30tablas/mismasfilas), alcance decidido por José. Guardas Q-066/067 corregidas localmente y19casos de stubs pasan; no SQL/SSH real por Codex. Restauración en vivo no ensayada, límite aceptado, no exigirla. |
| 12.2 | DONE | Quince200/index/follow/canónica propia,30vistas390/1440 sin overflow/pageerror/imágenes fallidas;404español/noindex, auxiliaresnoindex,403readme/xmlrpc,301/cabeceras/SSL. Servidor por atestación local. Sin logs de visitas de hPanel: snapshot operativo, no certificado de tráfico real; seguimiento12.3. |
| 9.5 | OWNER | Q-062VERIFIED: literal383caracteres cotejado con sitio, siete servicios en ORL/Sueño, URLUTM200/canónica limpia y reservaWhatsApp coincidente. Fuente es QA local, no lectura nueva del perfil. Horario real y Doctoralia siguen mejora continua D-086. |

- Q-063/Q-064 VERIFIED por HTTP nuevo: autor, archivos y querycondicion404 con «Página no encontrada»/follow,noindex;15páginas200 y cinco301sin regresión.14fixtures request preservan admin/sesión/single/preview/Sueño, sin nueva sesiónWP.
- Q-062 VERIFIED: transcripción383caracteres y siete servicios coherentes con el sitio. No se confirma perfil público/servicios en revisión ni se entra a Google/Doctoralia.9.5OWNER intacto.
- Q-041 WONTFIX D-065: dos avisosMarker, mapas Home/Contacto renderizados/visibles, JSON+marcadorclásico. La referencia de Día0 a OWNER/MapID se corrige; no pedir MapID por D-059 histórico.
- Q-066 P2 FIXED_LOCAL: drill podía retornar éxito si fallaba lista/conteo o había diferencias. set-euo/pipefail y validación de lista/resultado dan error; pipelines fallidos no quedan ocultos.
- Q-067 P2 FIXED_LOCAL: credenciales se creaban antes del trap en una conexión distinta. Ahora una conexión con mktemp/chmod600/trap antes de stdin, timestamp validado y guardas adicionales. Nada de secreto en argv.
- Revisión de Claude cloud (2026-10-16) de dc1c104: `bash -n` correcto en drill-db.sh y deploy-code.sh. Una sola conexión SSH con mktemp, chmod 600 y trap antes de leer stdin; timestamp validado; se aborta si la lista o el conteo fallan o si hay diferencias (exit 9); `umask 077` antes de mkdir deja la carpeta de respaldo en 700. Los bucles usan `-e` o una consulta como argumento (no consumen stdin del archivo de tablas). Sin objeciones. Límite: revisión de lectura, sin ejecutar contra el servidor.

19casos actuales con stubs pasan; dos regresiones del código e58c91c reproducidas (éxito falso y temporal residual por fallo de transporte), con datos sintéticos y limpieza de ficheros propios. lib/env excluidos; no se usan claves/SSH/MySQL real ni se lee dump. Windows verifica llamada chmod600, no permisosLinux remotos. Los ensayos reales30/30en copia y diff/lintcódigo son atestación local aportada, no un mock presentado como ejecución real.

12.1DONE corresponde a copia aparte acordada por José; no se certifica restauración viva ni igualdad de contenido SQL por checksum. Guardas nuevas probadas localmente, no repetidas con base real.12.2DONE acepta snapshot operativo15/16oct y servidor atestado; no afirma cero404/5xx de visitantes sin hPanel. Monitor404 no se activa; seguimiento12.3.

PHP79/PHPCS/JS y bash19 pasan. No deploy, formularios/SMTP, cuentas, secretos, contenido ni archivos privados. Se conservan cuatroHTML y h.txt preexistentes, sin leer h.txt. 11.2REVIEW/Q-065 y D-087 preexistentes no se alteran; fuente de producción y código local no se confunden.

Durante el trabajo llegaron fd2479d/94a2479/4fa21ca: se preservan atestaciones D-087/Q-065 y decisión D-088 de retirar6.6/7.4/7.5(7pesos,3tareas). No se cambia11.2REVIEW. Denominador230pesos/87tareas,8DESCOPED/23pesos; con12.1/12.2DONE,204/230=88,70%,78/87DONE. No se cuenta scope retirado como trabajo terminado.

Consola16b: se conservan consoleErrors en public.json. FollowupHome/Contacto identifica bloqueos deliberados GA/telemetríaMaps y diagnósticos Cloudflare en automatización, sin origen propio ni pageerror; no se afirma consola vacía. Solo host/flags, sin claves/URLcompleta. HTTPSwwwCPAP→apex301 confirmado como segundo salto de HTTPwww.

## Cierre11.2 · Codex · 2026-10-16

DONE por instrucción reciente de José y evidencia94a2479: GalaxyS24+ Chrome, checklist Android y confirmación posterior de barra horizontal Q-065 y menú D-087 en producción de864bc. Se revisaron QA «Android real» y «Despliegue de D-087 / Q-065»; Edge155/Firefox158 de sesión local y SafariiPhone de José completan la matriz. Límite: Firefox sin consola y pruebas reales aceptadas por atestación, no realizadas de nuevo por Codex. No acceso a wp-admin, formularios o secretos. 206/230=89,57%;79/87DONE. Los tres commits solicitados ya son ancestros y la auditoría16b ya estaba en remoto; este cierre actualiza sus estados anteriores.

## Despliegue de 91c1b09 (fase 5) y 5612f0c (A1) (sesión local, 2026-10-16 14:40)

deploy-code (respaldos `~/deploy-backups/20261016-143729/` y `…143814/`, cada uno con db.sql.gz y 30 tablas) y purga. Sin setup ni regeneraciones; la errata B1 no se aplicó en producción (requiere el OK de José y de la Dra.: re-aprobación).

- **A1 · VERIFIED:** las 5 /sueno/{slug}/ muestran «Revisado médicamente por Dra. Irina González Sáez · Última revisión: …», con la fecha del día de `_di_approved_at`, y `lastReviewed` coincide:

  | Ficha | `_di_approved_at` | Fecha mostrada y `lastReviewed` |
  |---|---|---|
  | ronquido | 2026-10-09 17:28:45 | 9 de octubre de 2026 · 2026-10-09 |
  | estudio-del-sueno | 2026-10-09 17:29:40 | 9 de octubre de 2026 · 2026-10-09 |
  | apnea-obstructiva-del-sueno | 2026-10-09 17:28:00 | 9 de octubre de 2026 · 2026-10-09 |
  | cpap | 2026-10-13 15:33:28 | 13 de octubre de 2026 · 2026-10-13 |
  | cirugia-de-ronquido-y-apnea | 2026-10-13 15:38:21 | 13 de octubre de 2026 · 2026-10-13 |

- **Regresión (Chrome headless):**
  - carrusel de credenciales de la portada con 3 puntos; pasa del ítem 0 al 1 en 5 s;
  - `.di-bleed__bg::after` sin grano en Sueño e Inicio (content none);
  - sin errores de consola;
  - barra a 832×340 en columna, top 100, botones de 46 px (Q-065 igual);
  - portada, Contacto, Sueño y Dra. dan 200;
  - `nap-check.py` en verde.
- **No ejecutado aquí:** `npm ci && npm run lint`, porque descarga paquetes; queda para Codex o la nube.

## Despliegue de a89f14a: opiniones de Doctoralia apagadas, D-091 (sesión local, 2026-10-16 14:45)

deploy-code (respaldo `~/deploy-backups/20261016-144154/`) y purga. Sin setup-dra.sh, por indicación de la nube: la sección se carga solo tras el GO de Codex (docs/CODEX_REVIEW_PROMPT_2026-10-16c.md) y con la casilla activada por José.
- PHP lint en el servidor de Opiniones.php y blocks/opiniones.php: sin errores.
- Ajustes: `doctoralia_opiniones` y `doctoralia_widget_id` sin valor (NULL), es decir, apagado.
- Portada, Conóceme, Contacto y /sueno/cpap/: 0 «zl-url», 0 `data-di-doctoralia`, 0 «docplanner» y ninguna sección `di-opiniones`.
- Regresión: el carrusel de credenciales rota, sin grano, la barra horizontal igual y sin errores de consola.
- Pendiente de José: que «Dra. Irina › Consultorio» muestre los 2 campos nuevos y guarde. No activar la casilla hasta el GO de Codex.

## Auditoría fase5 · Codex · 2026-10-16

| Tarea | Veredicto | Evidencia |
|---|---|---|
| 5.1 | DONE | style.css declara Hello parent; functions.php carga seis módulos de presentación. setup.php registra menús/tamaños/hooks Hello y templates médicos; enqueue.php ordena tokens→base→components, sleep condicional y main defer, versiones por filemtime (no hash de contenido). header/footer/page/404 y templates reales existentes. Ocho páginas anónimas200 con cabecera/pie/main y kit Adobe; sprite24 símbolos, cero referencias faltantes en la muestra. D-010 exige Adobe Fonts, no WOFF2 locales. |
| 5.2 | FIX documental→DONE | Bootstrap del plugin independiente del tema; cuatro CPT, tres taxonomías,43metas con schema/sanitizador/auth edit_post capturados desde las clases reales en fixture. PracticeSettings GET público proyecta campos permitidos; POST/Ops requieren manage_options (7/7 callbacks de permisos). Fixture de regresión28casos: fechas, gate publish/future, cambio de clínica, privacidad REST, FAQ y rechazo de entradas sin correo. Widgets registra18clases ahora, incluye Opiniones apagado de otra entrega: registro no es GO publicitario. Sin UI de repetidores de meta clínica: builds + app médica D-040/D-076; comentario y arquitectura antiguos corregidos para no prometer MetaBoxes.php. |
| 5.3 | DONE; Q-042 VERIFIED | Runtime centralizado en PracticeSettings: schema, helpers, pie, barra, formulario, WhatsApp y shortcodes. Builds importan practice.py; nueve campos públicos de centro/dirección/teléfonos coinciden. nap-check anónimo pasa y fixture negativo en árbol temporal detecta calle duplicada. build-home y build-pages regenerados:14JSON cotejados por hash, cero cambios; Git sin diff en contenido. Hospitales/credenciales siguen textos aprobados D-049. |
| 5.5 | FIX→DONE; Q-068 VERIFIED local | npm ci --ignore-scripts --no-audit --no-fund instala187paquetes con lock existente. El comando entregado fallaba en Windows: «tools no se reconoce» por ejecutable con barras Unix. Se usa eslint desde PATH de npm manteniendo cwd en raíz (necesario para alcance del flat config). npm run lint completo pasa: ESLint9.39.5 recomendado y stylelint16.26.1 recomendado; excepciones CSS documentadas y sin cambios de reglas. PHP81/PHPCS y bash19 sin fallos. Sprite estático24símbolos, sin etapa de build necesaria; Adobe autorizado en lugar de WOFF2. |


Q-068 · P2 · VERIFIED local: npm run lint fallaba en Windows por ruta Unix del ejecutable; corregido PATH npm/cwd raíz y ESLint+stylelint pasan. Sin reglas relajadas ni paquetes actualizados.

PHP81/PHPCS/bash19 pasan; registro4CPT/3tax/43metas y permisos7/7; regresión28casos. OchoGET públicos,9camposNAP,6assets,14JSON sin cambios y negativo de deriva pasan. Fuentes/límites completos en docs/REVISION_FASE5_2026-10-16.md y evidencia docs/audit/2026-10-16-phase5/. Configuración WordPress y espejo de builds cotejados, no sync automática. Consola/editor/carrusel por atestación anterior, no nueva sesión. Q-040OPEN; sin GO Doctoralia/inglés. Avance221/228=96,93%,83/86DONE; D-094 mantiene9DESCOPED/25pesos. Sin deploy/formulario/cuentas/secretos; cuatroHTML de José conservados.

## Auditoría16c · Doctoralia · Codex · 2026-10-16

**GO con cambios condicionado; no activar todavía.** Informe docs/REVISION_DOCTORALIA_2026-10-16c.md con fuentes oficiales LGS/Reglamento/Docplanner y límites. No certifica expedienteCOFEPRIS ni opiniones reales. Título neutral conservado y entradilla sin garantía en build/JSON; D-012 sin transcripción/filtro/schema de reseñas propio. Cotejo del HTML oficial del perfil y verificación real de privacidad/contenido/accesibilidad pendientes; sin cuenta ni ID real por Codex.

- Q-069 · P2 · FIXED_LOCAL: script antes cargaba al acercarse400px o automáticamente sinIO, contrario a interacción del aviso§9. Ahora botón explícito con aviso previo/cookies, fallback externo y reintento.0peticiones antes del clic; sin modificar aviso legal.
- Q-070 · P2 · FIXED_LOCAL:120px mínimos no contienen embed variable; caja clamp320/60vh/520 con scroll/región. Iframe sintético1000px,390/1440: desplazamiento exterior0px. No equivale a CLS/LCP real ni accesibilidad del proveedor.

GuardsPHP4/4; Enter/Espacio, foco, doble activación1script, error/reintento, enlace sinJS y eventoexterno1doctoralia_click pasan. Cargar opiniones no cuenta conversión; eventos dentro del iframe no se miden. OchoGETpúblicos200 sinwidget; CSPupgrade-insecure-requests sin allowlist ni cambios. Scriptoficial200 reconoce marcado, no verifica panel. PHP81/PHPCS/JS/ESLint/stylelint/bash19 pasan. Dos capturas locales sintéticas revisadas. Evidencia docs/audit/2026-10-16c/. Sin deploy/activación/cuentas/claves/forms; cuatroHTML preservados.221/228=96,93% sin cambios,9.5/8.6OWNER.

## Versión en inglés: entrega 1 (Claude cloud, 2026-10-16, D-095)

Módulo nativo e Inicio en inglés en borrador. Probado en un WordPress 7.1.3 local (SQLite, Elementor y Rank Math libres, tema y plugin del repo enlazados; scratchpad de la sesión cloud):
- **Visitante anónimo, con /en/ en borrador:** la portada sigue en `lang="es"`, sin `<link hreflang>`, sin selector (`a.di-lang` = 0) y sin enlaces a /en/. `/en/` y la vista previa sin sesión dan 404.
- **Administrador, vista previa del borrador (`?page_id=…&preview=true`):** `lang="en-US"`, textos del tema en inglés («Skip to content», «Book via WhatsApp», pie «© 2026 Dr. Irina González Sáez. All rights reserved.»), barra «WhatsApp / Call» y mensaje de WhatsApp en inglés. El hero dice «Consultations are in Spanish.».
- **Publicando /en/ solo en local (después se volvió a borrador):** selector «EN» en la portada hacia /en/ y «ES» en /en/ hacia /; hreflang es-MX / en-US / x-default recíproco en las dos. De vuelta a borrador, /en/ da 404.
- **Fichas:** /sueno/{slug}/ solo resuelve fichas españolas y /en/sleep/{slug}/ solo inglesas (`_di_lang`). Las URL cruzadas usan la redirección de adivinanza de WordPress (301), como cualquier slug inexistente.
- **setup-en.php idempotente:** crea la raíz `en` en borrador, empareja con page_on_front, título y descripción de Rank Math (49/143), textos del consultorio en inglés solo si están vacíos y flush de rewrites. No modifica páginas inglesas publicadas.
- PHP lint, PHPCS (tema, plugin y loader), ESLint/stylelint y nap-check en verde.
- Límite: en local no hay fotos, Rank Math sin configurar ni Elementor Pro. La verificación real la hace la sesión local en producción.

- **Q-068 · P3 · FIXED_LOCAL (Claude cloud, 2026-10-16):** `register_meta` avisaba «llamada de forma incorrecta» en cada carga porque `di_tipo` (tratamiento y credencial) es una lista cerrada con default '' fuera de la lista. Ahora no se registra default cuando no es válido; `get_post_meta` sigue devolviendo ''. debug.log local: 0 líneas tras cargar una ficha.


## Despliegue de la versión inglesa, entrega 1 (af2533d) (sesión local, 2026-10-16 15:25)

deploy-code (respaldo `~/deploy-backups/20261016-152400/` con db.sql.gz) y purga; `setup-en.sh` y purga.
- setup-en creó la página 384 «en» en **borrador** (pareja española 168), escribió los 6 textos `*_en` de Consultorio (estaban vacíos) y actualizó las reglas de reescritura. Nada publicado.
- Con el mismo deploy-code subió el código de Doctoralia de Codex (766e589: carga por botón). Sigue **inerte**, con la casilla y el ID vacíos; no se ejecutó setup-dra.sh. En portada y Conóceme: 0 zl-url, 0 data-di-doctoralia y 0 docplanner.
- **Verificación anónima de las 15 URL del sitemap:**
  - todas con `<html lang="es">`, 0 hreflang, 0 selector `di-lang`, 0 enlaces a /en/ y `body.di-lang-es`;
  - las 5 fichas, con la caja de revisión médica.
- /en/ y /en/sleep/ dan 404; /en/sleep/cpap/ hace 301 de adivinanza a /sueno/cpap/, como se esperaba.
- Sitemaps de page, condicion y tratamiento sin /en/. Las 301 de /padecimientos/ y /tratamientos/ siguen; un borrador de /sueno/ sigue en 404.
- Regresión: carrusel, grano, barra en horizontal y consola, sin cambios. No hay debug.log y `~/error_log` no tiene ninguna línea del dominio.
- **Pendiente de José con sesión:**
  - vista previa de la página 384 (lang en-US, hero en inglés con «Consultations are in Spanish.», pie y barra en inglés, WhatsApp con mensaje en inglés);
  - los 6 campos «Inglés · …» en Consultorio.

## Versión en inglés: entrega 2 (Claude cloud, 2026-10-16, D-095)

Páginas base y legales en inglés en borrador. Probado en el WordPress 7.1.3 local con páginas españolas de referencia (builds del repo) y las 16 credenciales:
- **setup-en.php** crea 10 borradores hijos de /en/ (contact, dr-irina-gonzalez-saez, ent, faq, first-visit, sleep, thank-you y los 3 legales en HTML con shortcodes), cada uno emparejado con su página española, más las credenciales `_di_en` (16) y los menús primary_en, footer_en y legal_en.
- **Anónimo, con todo en borrador:** /en/, /en/ent/ y /en/privacy-notice/ dan 404 (/en/contact/ hace la 301 de adivinanza de WordPress a /contacto/). La portada, Contacto y Conóceme en español: 0 hreflang, 0 selector y 0 enlaces a /en/.
- **Publicando todo solo en local (después se volvió a borrador):** las 11 URL en 200 con `lang="en-US"` y hreflang recíproco; /en/nope/ da 404 con «Page not found». El menú inglés muestra Home, ENT, Sleep, About, First visit y Contact; el legal inglés, Privacy notice, Medical disclaimer y Terms of use. Selector «ES» hacia la portada española. Las credenciales salen en inglés en Conóceme (Specialty, Subspecialty, Certification). Sin desborde a 1440 ni a 390 en 5 páginas y sin errores JS.
- **Formulario en inglés:** etiquetas y motivos en inglés. Por fetch, un error de validación devuelve «Please enter your name.» (en español con `di_lang=es`). El correo generado, capturado con un filtro local sin enviarlo, va en español con la fila «Idioma: Inglés: escribió desde /en/. La consulta es en español.».
- **Rewrites:** setup-en.php ya no hace `flush_rewrite_rules` con Elementor desactivado (perdería sus reglas, como advierte setup-site.sh). Borra `rewrite_rules` y setup-en.sh visita la portada para regenerarlas con todos los plugins. **Producción:** la entrega 1 sí hizo el flush; conviene borrar `rewrite_rules` y visitar la portada una vez.
- PHPCS, ESLint/stylelint y nap-check en verde. Catálogo del tema: 128 cadenas (más las 3 de la carga de Doctoralia por botón de Codex y «Dr. Irina»).
- **Límite:** traducción de Claude, pendiente de la revisión de la Dra. (13.6). Sin fotos ni Rank Math configurado en local (los títulos de Rank Math se escriben en `rank_math_title`).


## Inglés, entrega 2 (cedc216) y exportación de las fichas aprobadas (sesión local, 2026-10-16 15:45)

- **Reglas de reescritura (corrección de la entrega 1):** `option delete rewrite_rules`, `cache flush` y una visita a la portada. Quedan 197 reglas, con las de Elementor (e-floating-buttons), Sensia (^op/) y ^en/sleep. /op/, /inicio-v2/, /wp-json/ y la API del consultorio dan 200; las 15 URL, 200. Falta que José abra el editor de Elementor con su sesión.
- **Entrega 2:** deploy-code (respaldo `~/deploy-backups/20261016-153449/`) y purga; `setup-en.sh` y purga.
  - Borradores creados: 385 contact, 386 dr-irina-gonzalez-saez, 387 ent, 388 faq, 389 first-visit, 390 medical-disclaimer, 392 privacy-notice, 394 sleep, 395 terms-of-use y 397 thank-you, cada uno con su pareja española. La 384 «en» se actualizó.
  - 16 credenciales con texto en inglés; menús primary_en, footer_en y legal_en; reglas regeneradas en la siguiente visita.
  - Nada publicado.
- **Verificación anónima:**
  - las 15 URL y /gracias/: 200, lang es, 0 hreflang, 0 selector, 0 enlaces /en/ y 0 di_lang;
  - motivos del formulario de Contacto en español;
  - las 11 /en/… dan 404, salvo /en/contact/, que hace 301 de adivinanza a /contacto/;
  - sitemaps sin /en/.
- **Exportación (solo lectura):** `tools/content/en/fichas-es-aprobadas.json` contiene las 5 fichas publicadas (IDs 256, 205, 261, 262 y 263), todas medically_approved y sin `_di_approved_by` ni metas internas.
- **Diferencias de contenido entre medical-drafts.json y lo aprobado en producción** (ediciones de la Dra. en su app):
  - apnea-obstructiva-del-sueno: `resumen` (una letra: la concordancia de B1 está en el repo, no en producción) y `sintomas` (producción añade «Somnolencia excesiva diurna»);
  - cpap: `candidatos` (producción añade «El CPAP es considerado el tratamiento gold standard para la apnea del sueño») y `recuperacion` («algunas semanas» en el repo → «algunos días» en producción).
  - Solo de formato: relacionados, tratamientos y que_resuelve guardados como IDs (en el repo, slugs) y la `nota` interna del borrador.

## Doctoralia: prueba del widget REAL en entorno aislado (sesión local, 2026-10-16 16:00)

Encargo: docs/REVISION_DOCTORALIA_2026-10-16c.md, GO condicionado. **El widget sigue apagado en producción.** No se ejecutó setup-dra.sh ni se tocaron la casilla ni el ID; el código de la carga por botón (766e589) ya está en producción e inerte.

**Entorno:** copia local de Conóceme en http://localhost:8765, solo en la PC de José, sin publicar nada.
- HTML vivo, con GA sustituido por un stub que no envía nada.
- Sección de opiniones idéntica a blocks/opiniones.php, con el CSS y el JS de producción.
- Chrome headless a 390 px (UA Android) y 1440 px.
- Script de la prueba: dp_harness.py, en el scratchpad de la sesión (no versionado).

**Identificador PROVISIONAL:** `data-zlw-doctor="irina-gonzalez-saez"` y `href` https://www.doctoralia.com.mx/perfil/irina-gonzalez-saez (el doctoralia_url del sitio; 200 y canónica propia). **Todavía no se ha cotejado con el HTML oficial del generador**: falta que José lo copie.

**Resultados con el widget real:**
- **Privacidad antes del clic:** 0 peticiones a docplanner o doctoralia y 0 cookies, también tras recorrer toda la página.
- **Después del clic:**
  - peticiones a `platform.docplanner.com/js/widget.js` y `widgets.doctoralia.com.mx/doctor/widget/big/irina-gonzalez-saez`;
  - 0 cookies de Doctoralia observadas. Límite: Chrome headless sin perfil; Safari, Firefox o un perfil real pueden comportarse distinto.
- **Perfil:** correcto (nombre, «Otorrinolaringólogo», 13 opiniones).
- **Contenido del tipo `big` con `opinion=true`:**
  - es una tarjeta de **reserva** de Doctoralia (foto, estrellas, «13 opiniones», «Ofrece consultas online», botón azul «Agendar cita») con **una sola** opinión, la más reciente, truncada y con «Ver todas las opiniones (13)»;
  - aparece el logotipo de Doctoralia;
  - 212 caracteres de texto en el iframe; 0 coincidencias de palabras de riesgo (cura, garantiza, 100 %, milagro, «el/la mejor», definitivo);
  - el texto de la opinión no se copia aquí (D-012).
- **Accesibilidad:**
  - iframe con title «Docplanner Booking Widget» (en inglés, lo pone el proveedor);
  - con teclado, 4 tabulaciones desde el botón: entra en el iframe y sale de la sección sin trampa.
- **Layout y rendimiento:**
  - caja exterior estable (506 px a 390 y 449 px a 1440, antes y después), sin desborde horizontal;
  - iframe de 358×350 y 745×350;
  - CLS tras el clic de 0,0085 (390) y 0,0035 (1440).
  - Tras cargar siguen visibles el aviso de cookies, el botón (aria-disabled) y el estado «Si las opiniones no aparecen…», así que la caja necesita desplazamiento interno aunque el widget mide 350 px.
- **Fallo del proveedor** (dominios bloqueados): estado «No se han podido cargar las opiniones. Puedes reintentar…», botón habilitado para reintentar y enlace alternativo presente.
- **Analítica:** clic en el enlace alternativo = 1 `doctoralia_click` y 0 peticiones del widget. El botón de carga no se midió en esta ronda (Codex lo probó con su harness).
- **Consola:** sin errores del widget. Los errores del sprite SVG y del manifiesto son del entorno local (origen cruzado), no de producción.
- **No medido:** LCP y CLS reales de Conóceme en producción con el widget (requiere activarlo), un lector de pantalla dentro del iframe y Safari/Firefox.

**Para decidir (José, Codex y la nube):**
1. El tipo `big` muestra sobre todo una tarjeta de reserva con UNA opinión, no un listado. Con el título «Lo que dicen sus pacientes» el resultado no es lo que promete la sección. El código oficial del generador puede ofrecer otro formato (otro data-zlw-type).
2. El botón «Agendar cita» de Doctoralia compite con WhatsApp, el CTA principal (D-011).
3. «Ofrece consultas online» viene del perfil de Doctoralia: confirmar que es cierto.
4. Tras cargar conviene ocultar el aviso, el botón y el estado, para que el widget quepa sin desplazamiento interno.

## Versión en inglés: entrega 3 (Claude cloud, 2026-10-16, D-095)

Las 5 fichas de sueño en inglés y el paquete de revisión de la Dra.
- **Fuente:** el texto APROBADO exportado de producción (`tools/content/en/fichas-es-aprobadas.json`, sesión local, solo lectura), no medical-drafts.json. Se traducen las ediciones de la Dra.: «Somnolencia excesiva diurna»; CPAP «gold standard»; «algunos días». La errata española de apnea se traduce por su sentido («explained by an otolaryngologist», D-090).
- **setup-en.php:** crea 5 CPT en borrador (snoring, obstructive-sleep-apnea, home-sleep-test, cpap-therapy, snoring-and-sleep-apnea-surgery) con `_di_lang=en`, la pareja española, área y zona sueño, estado «requiere revisión médica», metas di_* traducidas (fuentes, tipo y oferta copiadas), relaciones solo entre fichas inglesas y rank_math_title (≤62). No toca fichas inglesas aprobadas ni publicadas. Corregido: las búsquedas usan `post_name__in`, porque `name` con `post_status=any` no encuentra entradas (como advierte setup-site).
- **Local, aprobándolas y publicándolas solo en prueba:**
  - /en/sleep/{slug}/ en 200 con lang en-US y hreflang recíproco con /sueno/{slug}/ (también desde la española);
  - migas Home › Sleep (/en/sleep/);
  - secciones en inglés y relacionados ingleses;
  - caja «Medically reviewed by … · Last reviewed: October 20, 2026»; el formato de fecha ya es traducible y en español sigue «13 de octubre de 2026»;
  - /en/sleep/ enlaza las 5 fichas inglesas;
  - las URL cruzadas usan la 301 de adivinanza.

  Después se devolvieron a borrador y a «requiere revisión».
- **Guía de revisión** (/briefing/guia/, con token): `build-en.py` exporta `paginas-en.json`, es decir, las 8 páginas inglesas por bloques con el bloque español desplegable. revisar.php las sirve y acepta «Aprobar página» o «Pedir cambios» para los slugs ingleses; guarda `_di_page_approved_at` y nunca publica. Probado en local: 10 tarjetas, aprobar en/sleep deja la página en draft con la fecha de aprobación, y un slug ajeno da `error: page`. `.htaccess` deniega paginas(-en).json (no verificable con el servidor PHP local, que ignora .htaccess) y deploy-briefing.sh sube el nuevo JSON.
- **App de revisión de fichas:** las inglesas llevan la etiqueta «Inglés» (4e57fcf).
- **Q-071 · P3 · FIXED_LOCAL:** build-medical.py y medical-drafts.json diferían del texto aprobado en 3 campos (apnea síntomas; CPAP candidatos y recuperación). Ahora coinciden; la única diferencia es la corrección B1 («un otorrinolaringólogo»), pendiente de la aprobación de la Dra. setup-medical no toca fichas publicadas, así que no hay efecto en producción.
- PHPCS, ESLint/stylelint, sintaxis bash y JS de la guía en verde.
- **Límite:** traducción de Claude; la aprobación clínica es de la Dra. en su app (13.6).


## Inglés, entrega 3 (6643e9b): 5 fichas en borrador y paquete de revisión (sesión local, 2026-10-16 16:00)

- deploy-code (respaldo `~/deploy-backups/20261016-154610/`) y purga.
- `setup-en.sh`: páginas 384–397 actualizadas (borrador). Fichas inglesas creadas en **borrador** con `medical_review_required` y su pareja española:
  - 412 snoring (256)
  - 413 obstructive-sleep-apnea (205)
  - 414 home-sleep-test (261)
  - 415 cpap-therapy (262)
  - 416 snoring-and-sleep-apnea-surgery (263)

  Las 5 españolas siguen publish + medically_approved.
- **Público:**
  - las 15 URL dan 200, sin hreflang, selector ni /en/;
  - las 5 /sueno/{slug}/ con la caja en español («9/13 de octubre de 2026»);
  - las 5 /en/sleep/{slug}/ dan 404.
- `deploy-briefing.sh`: el primer intento se cortó al subir guia/ (Hostinger reinició la conexión SSH). **Durante unos 5 minutos (15:48–15:53) `/briefing/guia/paginas-en.json` respondió 200**: se subió antes que el .htaccess nuevo. Contiene las traducciones en borrador, sin datos personales ni token. El segundo intento terminó: paginas-en.json y paginas.json dan 403; config.php 403; revisar.php sin token 403; el token se conserva.
- **Con token** (sin imprimirlo ni aprobar nada): la guía muestra 10 tarjetas, 8 marcadas «(inglés)».
- **Hallazgo:** la app de revisión (/briefing/revision/) muestra todavía el modo de sitio corto («Ya no hace falta revisar fichas… dos páginas») y **no lista fichas**, así que las 5 inglesas no aparecen con la etiqueta «Inglés». La Dra. no puede revisarlas ahí hasta que la nube ajuste esa vista.
- **Menor:** /favicon.ico da 404 (el icono del sitio sí carga por `<link>`). Lo vio la consola de la app de revisión.
- **Corrección de la entrega 3 (Claude cloud, 2026-10-16):** la app de revisión de fichas seguía en el modo de sitio corto (aviso fijo «Ya no hace falta revisar fichas», sin lista), así que la Dra. no veía las fichas inglesas (hallazgo de la sesión local). Ahora lista solo las fichas con `lang=en`; `?id=` sigue abriendo cualquier ficha, para uso interno. Título «Fichas en inglés», con enlace a la guía y desde la guía a las fichas. Local: 5 fichas «Inglés · … · Pendiente de tu revisión», «0 de 5 aprobadas», la ficha abre con su editor y botones, sin errores JS. deploy-briefing.sh sube cada `.htaccess` antes que los demás archivos, por la incidencia de los 5 minutos con paginas-en.json en 200.


## App de revisión con las fichas en inglés (5214a99) y /favicon.ico (sesión local, 2026-10-16 16:00)

- `deploy-briefing.sh`: config.php y token conservados. paginas(-en).json y config.php dan 403; revisar.php sin token, 403.
- **Con token, solo lectura:**
  - /briefing/revision/ muestra «Fichas en inglés», «0 de 5 aprobadas» y 5 ítems «Inglés · Padecimiento/Tratamiento · Sueño · Pendiente de tu revisión»;
  - al abrir una ficha aparecen su texto y el editor (12 campos). **No se aprobó ni se guardó nada.**
  - La guía mantiene 8 tarjetas «(inglés)» y el enlace a las fichas.
  - Sin errores de JS.
- **/favicon.ico:** `site_icon` = 240 estaba configurado, pero la CDN de Hostinger (hcdn) devuelve 404 a /favicon.ico sin pasar por WordPress. Se copió el `assets/brand/favicon.ico` del tema (isotipo, 16 y 32 px) a la raíz del sitio; es reversible borrando ese archivo.
  - Con parámetro responde 200 image/x-icon.
  - Sin parámetro la CDN sirvió todavía su 404 en caché (Age 36 s) después de purgar LiteSpeed. Caducará solo; no se tocó la CDN.

## Cabecera de Sueño con foto (propietario, 2026-10-16 17:00, sesión local)

José subió `Recursos (Irina)/hero-sueno.png` (2040×1349: la Dra. a la derecha bajo un cielo nocturno) y pidió adaptarla «siguiendo los parámetros de las demás páginas, en escritorio y responsive».
- **Medios:** `upload-media.sh` → adjunto 417 `hero-sueno` (WebP q100, 2521 KB). `irina-escritorio` q95, 591 KB; `irina-movil` 1600 px.
- **Build:** build-pages.py `su-bleed` con image `__SU_HERO_ID__`/`__SU_HERO_URL__`, focus «50% 0%» (escritorio; con «50% 30%» se cortaba la cabeza) y focus_mobile «86% 50%». setup-pages.sh sustituye los marcadores. Solo cambia sueno.elementor.json.
- **Tema:**
  - bleed.php: con foto va la sombra (`di-bleed__shade`); los anillos de respiración quedan solo sin foto (antes la variante night los ponía en lugar de la sombra y tapaban la cara);
  - components.css: sombra con el tono noche de la paleta (#0f0c1f, sin multiplicar), de izquierda a derecha en escritorio y desde abajo en móvil.
- **Carga:** setup-pages actualizó Sueño (171); la conexión SSH se cortó en las páginas siguientes, que no cambiaban (Contacto, FAQ y legales verificados en 200 con su contenido). El ajuste de encuadre se cargó solo en la 171, con los mismos pasos del script. Tras `cache flush` y borrar `_elementor_element_cache`, purga.
- **Verificación con Chrome:**
  - 1440 px: escritorio q95 servida, object-position 50% 0%;
  - 390 px con UA Android y 393 px con UA iPhone: irina-movil 1600, 86% 50%;
  - 832×340 en horizontal;
  - sin desborde, sin errores de consola y texto legible.
- **Sin cambios:** las 5 fichas /sueno/{slug}/ siguen con el degradado nocturno y los anillos (sin foto); Primera consulta e Inicio siguen con sombra.
- **Ajustes del mismo día (propietario):**
  - **Foto en móvil:** focus_mobile pasa de «86% 50%» a «100% 50%»: la Dra. queda más a la izquierda y se ven más estrellas arriba a la derecha. Comparado en 390 px frente a 96 %.
  - **Títulos con la secuencia del sitio** (`<b>` al inicio, base, `<em>` al final), mismas palabras:
    - Sueño, cabecera: «**Del ronquido** al *descanso*»;
    - Sueño, cierre: «¿**Hablamos** de lo que te quita el *descanso*?»;
    - Otorrino, cabecera: «**Oído, nariz** y garganta, *desde los 0 meses*»;
    - Otorrino, cierre: «¿**Un síntoma** que *no se va*?».
  - **Carga:** solo las páginas 170 y 171, en una sesión SSH (mismos pasos que setup-pages), con cache flush y purga. Verificado en el HTML y en capturas de 1440 y 390 px.

## Cabecera de Otorrino con foto y títulos con la secuencia del sitio (propietario, 2026-10-16 17:20, sesión local)

Decisiones de José (AskUserQuestion): Otorrino con tono **cálido** como Primera consulta y Contacto; **ajustar todos los títulos** fuera de secuencia; quiere foto en FAQ (la subirá); confirma que la Dra. **sí ofrece consulta en línea**.
- **Otorrino:**
  - `upload-media.sh` → adjunto 423 `hero-orl` (2040×1405, WebP q100 3526 KB; escritorio q95 1104 KB; irina-movil 1600).
  - build-pages: orl-bleed `variant` sand → warm, image `__ORL_HERO__`, focus «50% 0%», focus_mobile «88% 50%» (comparado con 78 % y 96 % a 390 px: con 88 % el otoscopio sale del cuadro y el título queda limpio). setup-pages.sh sustituye `__ORL_HERO__`.
  - Carga solo de la 170.
  - Escritorio: escritorio q95 en 50% 0%; móvil: irina-movil en 88% 50%; sin desborde.
- **Títulos** (mismas palabras; `<b>` al inicio, `<em>` al final), sustituidos en producción solo en `settings.title`, sin recargar las páginas:
  - Primera consulta (172): cierre «**Agenda** tu *primera consulta*».
  - FAQ (173): «**Lo que** más *nos preguntan*» y «¿**Otra** *duda*?».
  - Gracias (335): «**Lo que** puedes ir *preparando*» y «**Si** prefieres, *escríbenos ahora*».
  - Conóceme (169), 3 títulos: «**Credenciales** *verificables*», «**Membresías** y *hospitales*» y «**Agenda** tu primera *consulta*».
  - El título de opiniones («**Lo que** dicen *sus pacientes*») solo cambia en build-dra/dra-elementor.json: la sección no está en producción (sin setup-dra hasta el GO de Codex).
  - Sin cambios: «¿*Agendamos*?» de Contacto (una palabra, definido así por José) y los títulos de Inicio (llevan negrita y cursiva juntas al final, otro patrón; pendiente de consultar a José).
- **Contradicción para resolver:** la FAQ de Primera consulta dice «¿Hay consulta en línea? Solo para seguimiento de pacientes ya valorados en consultorio», pero José confirma que la Dra. ofrece consulta en línea (y así lo muestra Doctoralia). Pendiente: decidir el texto con la Dra.
- **Correcciones del mismo día (17:40):**
  - **Encuadre en móvil:** José pidió a la Dra. **a la derecha, con aire**, como en las demás páginas. focus_mobile de Sueño 100 % → **68 %** y de Otorrino 88 % → **64 %** (comparados en 390 px con Primera consulta como referencia). Aplicado solo en `focus_mobile` de las páginas 170 y 171.
  - **Títulos de Inicio** igualados al resto (decisión de José: «Igualarlos al resto del sitio»): `<b>` al inicio y `<em>` al final, sin la negrita+cursiva final; mismas palabras. Seis títulos en la 168, más la cita de Conóceme (169): «**Escucharte** también es parte del *tratamiento*». La cursiva conserva peso 600 y color de acento (comprobado en una captura).
  - **«Dos especialidades»** se mantiene: José lo dictó en d3344b3, después de D-047, que proponía «Dos áreas». D-047 queda superada en ese punto.
- **Consulta en línea (2026-10-17, José):** la Dra. ofrece consulta en línea a cualquier paciente. Texto aprobado por José: «Sí. Agenda por WhatsApp y te indicamos cómo conectarte.» Sustituye «Solo (para) seguimiento de pacientes ya valorados en consultorio» en la FAQ de Primera consulta (172) y en Preguntas frecuentes (173). Cambio solo de ese valor exacto en `_elementor_data`, con cache flush y purga. En vivo: 0 restos del texto anterior; en Preguntas frecuentes aparece 2 veces (visible + FAQPage). El inglés lo cambia la nube.
- `1b90a6d` (inglés: encuadres 68/64 %, títulos de Inicio y cita, D-096) desplegado con setup-en; lo público no cambia.

## Publicación de /en/ y registro nocturno de /en/sleep/ (Claude cloud, 2026-10-17, D-098)

- **publish-en.sh** (WordPress 7.1.3 local con las 8 páginas, 3 legales y 5 fichas en borrador):
  - sin aprobaciones: lista las 16 entradas con su motivo y «No se publica nada (todo o nada)»;
  - con aprobaciones simuladas y sin `legal-ok`: bloquea solo por los 3 legales; con `legal-ok` y sin `yes`: «16 entradas listas» (dry-run, nada cambia);
  - texto cambiado en faq y terms-of-use y setup-en.sh de nuevo: avisa «el texto cambió» y borra la aprobación; publish-en bloquea por /en/faq/. Sin cambios, setup-en no revoca nada;
  - `legal-ok yes`: publica raíz, 10 hijas y 5 fichas (16 → publish); repetirlo no hace nada; setup-en ya no toca las 11 páginas publicadas.
  - eval-file recibe `yes`/`legal-ok` posicionales (rechaza opciones desconocidas); el .sh traduce `--yes`/`--legal-ok` y rechaza cualquier otra.
- **/en/sleep/ nocturno:** body `di-dark` y sleep.css en /sueno/ y /en/sleep/ (antes solo /sueno/). Schema inglés: /en/faq/ FAQPage, /en/contact/ ContactPage, /en/sleep/ MedicalWebPage con «Obstructive sleep apnea» y «Snoring», /en/ con Physician. Capturas 1440 px de /sueno/, /en/sleep/ y /en/sleep/snoring/.
- **Separadores y lombrices:** `.di-dark` redefine `--color-border` y `--worm-border` (azul-morado tenue); filas, rutas, FAQ, índice, migas y caja de revisión los heredan. El pie no cambia.
- PHP -l, PHPCS (sin errores nuevos; el aviso meta_query de setup-en.php ya existía), stylelint y bash -n en verde.
- **Límites:** aprobaciones simuladas en local, no las de la Dra.; servidor PHP local sin LiteSpeed ni .htaccess; la parte remota del .sh (purga, curl) no se ejecutó. Nada desplegado ni publicado en producción.


## Despliegue de 4dab820: Sueño nocturno en inglés, separadores azul-morados y publish-en en revisión (sesión local, 2026-10-17 10:05)

- deploy-code (respaldo `~/deploy-backups/20261017-100022/` con db.sql.gz) y purga. Sin setup-en.
- **Regresión del español:** los tipos de schema y `body.di-dark` de /, /otorrinolaringologia/, /sueno/, /preguntas-frecuentes/ (FAQPage), /contacto/ (ContactPage) y /sueno/cpap/ son **idénticos antes y después** del despliegue (instantánea comparada).
- **Separadores en producción:** `--color-border` es #342b56 en /sueno/ y en /sueno/cpap/ (borde de la caja de revisión rgb(52,43,86)). Capturas a 1440 y 390 px de «Ronquido y apnea» y «La ruta completa»: líneas onduladas y separadores en azul-morado tenue, sin crema ni blanco.
- **Inglés** (vistas previas simuladas por WP-CLI como el admin, porque la sesión local no inicia sesión): la 394 (/en/sleep/) da `irina_is_page('sueno')` = sí y `body.di-dark` = sí, con es_slug sueno; la 387 (/en/ent/) no es nocturna. La traducción de «Sueño» en en_US es «Sleep» (eyebrow de las fichas EN, área sueno, _di_lang en). Falta la mirada de José en la vista previa real.
- **`publish-en.sh` en modo revisión** (sin --yes ni --legal-ok), salida literal:
```
  /en/                                         draft    falta la aprobación de la Dra. en la guía
  /en/dr-irina-gonzalez-saez/                  draft    falta la aprobación de la Dra. en la guía
  /en/ent/                                     draft    falta la aprobación de la Dra. en la guía
  /en/sleep/                                   draft    falta la aprobación de la Dra. en la guía
  /en/first-visit/                             draft    falta la aprobación de la Dra. en la guía
  /en/contact/                                 draft    falta la aprobación de la Dra. en la guía
  /en/thank-you/                               draft    falta la aprobación de la Dra. en la guía
  /en/faq/                                     draft    falta la aprobación de la Dra. en la guía
  /en/privacy-notice/                          draft    legal: falta --legal-ok (OK de José)
  /en/medical-disclaimer/                      draft    legal: falta --legal-ok (OK de José)
  /en/terms-of-use/                            draft    legal: falta --legal-ok (OK de José)
  /en/sleep/snoring/                           draft    estado medical_review_required: falta la aprobación de la Dra.
  /en/sleep/obstructive-sleep-apnea/           draft    estado medical_review_required: falta la aprobación de la Dra.
  /en/sleep/home-sleep-test/                   draft    estado medical_review_required: falta la aprobación de la Dra.
  /en/sleep/cpap-therapy/                      draft    estado medical_review_required: falta la aprobación de la Dra.
  /en/sleep/snoring-and-sleep-apnea-surgery/   draft    estado medical_review_required: falta la aprobación de la Dra.
Warning: No se publica nada (todo o nada). Pendiente: /en/: falta la aprobación de la Dra. en la guía; /en/dr-irina-gonzalez-saez/: falta la aprobación de la Dra. en la guía; /en/ent/: falta la aprobación de la Dra. en la guía; /en/sleep/: falta la aprobación de la Dra. en la guía; /en/first-visit/: falta la aprobación de la Dra. en la guía; /en/contact/: falta la aprobación de la Dra. en la guía; /en/thank-you/: falta la aprobación de la Dra. en la guía; /en/faq/: falta la aprobación de la Dra. en la guía; /en/privacy-notice/: legal: falta --legal-ok (OK de José); /en/medical-disclaimer/: legal: falta --legal-ok (OK de José); /en/terms-of-use/: legal: falta --legal-ok (OK de José); ficha snoring: medical_review_required; ficha obstructive-sleep-apnea: medical_review_required; ficha home-sleep-test: medical_review_required; ficha cpap-therapy: medical_review_required; ficha snoring-and-sleep-apnea-surgery: medical_review_required
```
  Tras el dry-run, /en/, /en/sleep/ y /en/sleep/snoring/ siguen en 404: no se publicó nada.

## Auditoría de inglés/cabeceras · Codex · 2026-10-19

| Unidad | Veredicto | Evidencia y límite |
|---|---|---|
| 13.2 | FIX documental → DONE | Glosario consistente con ENT/OSA/HSAT/CPAP y estados de confirmación clínica/legal visibles. Mapa sin volúmenes inventados, intenciones/URL propias y sin doorway por colonia. Se actualiza D-095: consulta en español confirmada, no pendiente; intención de atención en inglés excluida y cinco fichas ya elegidas. Ruta CPAP corregida a `/en/sleep/cpap-therapy/`. Es un entregable de referencia; no certifica términos pendientes de la Dra. ni revisión clínica. |
| 13.3 | FIX → DONE en alcance de borrador | Ocho páginas Elementor, tres legales y cinco fichas en archivos separados; revisión médica no simulada como aprobación real. Enlaces internos: 18 ocurrencias, cero destinos/anclas faltantes. Nota de consulta en español, NAP actual, costes al agendar, consulta online D-097 y prevalencia del español presentes. Regeneración de 24 salidas de contenido/catálogos estable. Ajustes de formato D-096 en dos títulos sin cambiar palabras; JSON regenerado. Legales aprobados por José D-099 sin cambios. 13.6OWNER sigue como aprobación de la Dra.;13.4DOING y13.7DOING no se cierran. |
| 13.5 | REVIEW | 28 checks de callbacks reales: idioma por jerarquía/meta, locale de 404/vista previa autorizada, hreflang recíproco y x-default solo entre publicados, selector, fallback, WhatsApp, gracias, categorías del formulario sin envío, ruta clínica, schema FAQ/MedicalWebPage y registro nocturno por pareja. Catálogos GNU135/14 y estados de Doctoralia localizados. Falta prueba integrada de Rank Math para Open Graph/canónica e inclusión correcta de EN en sitemap al publicarse, y verificación de la corrección del301. Fixtures no equivalen al stack publicado. |
| publish-en (13.7) | FIXED_LOCAL; integración/atomicidad en REVIEW | Se reproducen dos fallos originales: éxito falso al fallar escritura y bypass con manifiestos vacíos. Ahora validación completa11+5/tipos/parejas ES publicadas, argumentos permitidos, dry-run, aprobaciones y legal-ok; parada y restauración compensatoria de estados en errores/retenciones/excepciones. Wrapper valida HTTP y alternates, no imprime éxito ante errores. 28 casos pasan con stubs, dos de ellos reproducciones del original. No transacción atómica ni garantía sobre hooks/caché; no ejecución real ni publicación. |
| Sueño/Otorrino ES | VERIFIED público | Ocho vistas:390/393/832×340/1440. Foto cargada, sombra presente/anillos ausentes cuando hay foto, encuadre móvil68%/64% y escritorio50%0%, títulos b/em, sin overflow ni pageerror. Sueño nocturno y borde#342b56; ORL cálido, sin di-dark. Ocho capturas; escritorio,390 yhorizontal inspeccionados visualmente. No cambios propios en fotos, menús, marca ni sombras. |
| Sueño/ENT EN | VERIFIED en callbacks; vista real pendiente | `Language::is_page` y pareja española aplican sleep.css/di-dark a Sleep y no a ENT; schema de área inglés correcto en fixture. Build reutiliza fotos y encuadres68/64. QA local17/10 aporta prueba WP-CLI. No entrada de Codex a vistas previas privadas ni certificado visual de páginas inglesas. |

Hallazgos de esta auditoría:

- **Q-072 · P1 · FIXED_LOCAL:** publish-en podía anunciar publicación completa tras un WP_Error, omitir entradas con JSON vacío/truncado o aceptar un cambio de kind que evitara la aprobación médica. Se exige el manifiesto exacto D-095/D-098, tipo/idioma de destinos, pareja ES publicada y se conserva la revisión de todas las entradas antes de escribir. Un intento `yes` bloqueado devuelve error. Los argumentos desconocidos se rechazan antes de cargar lib/env en el wrapper. Ante error,0,estado retenido o excepción se para y se intenta restaurar únicamente los estados de esta ejecución; si falla, lista IDs para revisión manual y sale con error. HTTP404/red o alternates ausentes después también son error.
- **Q-073 · P1 · FIXED_LOCAL:** setup-en convertía `wp_insert_post(...,true)` a int antes de `is_wp_error`, pudiendo tratar un objeto de error como ID1. Ahora comprueba error/0 antes del cast en páginas y fichas. Caso de creación fallida: aborta sin escrituras posteriores. Se mantiene no tocar publicadas, preservar fichas aprobadas y retirar aprobación de página si cambia el contenido.
- **Q-074 · P3 · FIXED_LOCAL:** estados de carga/error de Doctoralia, validación/error local del formulario y etiquetas accesibles del carrusel estaban codificados en español en JS. Plantilla entrega atributos traducidos, JS los usa con fallback compatible con HTML anterior y MO/PO se regeneran. Prueba local de error en inglés:0peticiones antes del botón y1después, tercero bloqueado. No cambia el GO condicionado, el formato ni la casilla apagada de Doctoralia. El formulario se prueba solo como HTML sintético con fetch simulado, sin petición real ni correo.
- **Q-075 · P3 · FIXED_LOCAL / comportamiento vivo pendiente:** `/en/contact/` responde301 a `/contacto/`, no404 como se había supuesto. Es compatible con adivinación canónica de WordPress; no se acredita la causa exacta sin configuración del servidor. Se desactiva esa adivinación solo bajo `/en/` mediante el hook documentado. Fixture confirma EN sin guess y español sin cambio. Si el301 procede de otra regla, seguirá pendiente de corregir; debe repetirse la petición después de una entrega autorizada. No se ha publicado contenido inglés.


Evidencia docs/audit/2026-10-19/:publisher28casos, i18n28checks,8vistasES,15EN404+1EN301aES,15sitemap sinEN,18enlaces válidos y24salidas reproducibles. PHP85/bash21/PHPCSproducto+publisher/ESLint/stylelint pasan; setup1warning meta_query preexistente. No rollback ni publicación reales, no OG/sitemap deEN publicado certificados. Español221/228; inglés9/19.13.5REVIEW/13.6OWNER/13.7DOING. D-099 legales yaaprobados. Informe docs/REVISION_INGLES_2026-10-19.md. Sin deploy/setup/formularios/cuentas/secretos; HTML de José preservados.

## Integración real de la versión inglesa (13.5) y protecciones de publish-en (13.7) · sesión local · 2026-10-19

Encargo de Codex del 2026-10-19. Copia local aislada: WordPress 7.1.3, Elementor 4.3.5 y Pro 4.3.1, Rank Math 1.0.280 y Hello 3.5.1, idénticos a producción; SQLite; datos sintéticos; red y correo bloqueados; ssh/scp bloqueados. Sin despliegue, sin scripts contra producción y sin publicar nada. Informe: `docs/REVISION_INTEGRACION_EN_2026-10-19.md`; evidencia: `docs/audit/2026-10-19-local/`; herramientas: `tools/audit/en-local/`.

- **Sintético:** los harnesses de Codex pasan en esta PC (28 publisher, 28 i18n y UI), con salidas idénticas a las versionadas.
- **13.5 integrado:**
  - OG `en_US`, canónica propia y hreflang recíproco (es-MX/en-US/x-default→ES) en las 15 EN y sus 15 parejas;
  - sitemap con **30** URL, sin Gracias/Thank you; sin pareja publicada no se anuncia y baja a 29;
  - selector y menús ocultos con la raíz en borrador; con todo publicado, a la pareja o a la portada del otro idioma;
  - schema con un solo Physician y FAQ = 10 visibles;
  - 404 EN «Page not found»; /en/thank-you/ con noindex; el formulario EN lleva a /en/thank-you/ y el ES a /gracias/;
  - WhatsApp, carrusel y validación/error del formulario en inglés, sin envío real;
  - Sleep con di-dark y sleep.css, ENT cálido, encuadres 68 % y 64 % y escritorio en 50% 0%, sin desborde.
- **Q-075 reproducido:** sin el guard, el 301 de `/en/contact/` sale de `redirect_canonical` ← `redirect_guess_404_permalink()`; con el guard, las 16 EN dan 404. **Pendiente en producción** (guard sin desplegar).
- **13.7 integrado:**
  - dry-run sin cambios;
  - 16 casos de preflight bloquean (salida 1) sin cambios;
  - opción desconocida: salida 2 antes de lib;
  - la 2.ª ejecución es idempotente;
  - WP_Error, estado retenido y excepción → «Estados restaurados»;
  - un fallo de restauración informa el ID y sale con 1;
  - la verificación HTTP 500, sin alternates, sin red y con fallo PHP sale con 1;
  - setup-en: aborta si no puede crear; solo la página cambiada pierde la aprobación; no toca publicadas ni fichas aprobadas.
- **Q-076 · histórico FIXED_LOCAL, superado por VERIFIED_LOCAL debajo (Claude cloud, 2026-10-19):** publish-en.sh ya carga Elementor (sin `--skip-plugins`) y la restauración da por restaurada la entrada que no llegó a cambiar. Prueba en WordPress local (sin Elementor): falla forzada en /en/sleep/ tras publicar 3 → «Estados restaurados», 16/16 en borrador; sin fallo, 16 publicadas; devueltas a borrador. Falta repetir la integración con Elementor real (sesión local). Antes: `publish-en.sh` usa `--skip-plugins=elementor,elementor-pro`, y sin Elementor `wp_update_post` da `invalid_page_template` en las páginas `elementor_header_footer`. **La publicación real fallaría en /en/ sin publicar nada**, con el aviso engañoso «no restaurados IDs <raíz>» aunque no cambió. Con Elementor cargado publica las 16 y verifica. Lo corrige la nube.
- **Q-077 · P3 · OPEN:** la portada /en/ no lleva WebSite/WebPage de Rank Math ni ningún `inLanguage`; la ES sí.
- **Q-078 · P2 · OPEN (límite):** IndexNow de Rank Math (instant-indexing activo) avisa al publicar: 5 POST en una publicación fallida (luego restaurada) y 10 en la completa. No se deshace.
- **Veredicto:** 13.5 sigue en **REVIEW** por Q-077 y por la verificación en producción tras el lanzamiento autorizado. 13.7 sigue en **DOING**; el bloqueo histórico Q-076 queda resuelto localmente por la prueba de b26efa4 indicada debajo; no es atómico (secuencial y compensatorio); hooks, caché e IndexNow no se revierten.
- **Q-076 · P1 · VERIFIED_LOCAL (b26efa4, sesión local 2026-10-19 19:30).** Repetida la integración con el `publish-en.sh` real del repositorio (sin variante de prueba) y Elementor 4.3.5/Pro 4.3.1 cargados:
  - WP_Error, estado retenido y excepción en /en/contact/ → «Estados restaurados» y las 16 en borrador;
  - fallo de restauración → «no restaurados IDs 99» (la única publicada);
  - **fallo en la raíz /en/** (el escenario de Q-076) → «Estados restaurados», sin el aviso engañoso;
  - publicación completa: 16/16, verificación con 15 rutas en 200 y alternates/selector, salida 0;
  - la 2.ª ejecución no cambia nada;
  - los 16 casos de preflight bloquean sin cambios.

  Evidencia: `docs/audit/2026-10-19-local/` (`*-b26efa4*`). **Pendiente en producción:** solo cuando se autorice el lanzamiento (13.6 → orden de José).

## Auditoría de b26efa4 y prueba local recibida en 43cc1df · Codex · 2026-10-19

Informe: `docs/REVISION_Q076_2026-10-19.md`. **Q-076 VERIFIED_LOCAL ratificado;13.5 REVIEW (Q-077),13.7 DOING,13.6 OWNER**. Español221/228; inglés9/19. No se repitió publicación ni WordPress; sin despliegue, formulario, cuentas o secretos.

- Código: carga Elementor sin contexto admin; evita restaurar estados que no cambiaron, conserva alertas por restauración fallida. PHP lint, PHPCS del publicador y bash syntax OK.
- Evidencia recibida: 16 publicaciones, 15 rutasHTTP200 (Thankyou fuera de la lista), 16 negativos de preflight sin cambios y un control positivo por idioma jerárquico; cinco fallos, cuatro restaurados y el restante informa ID99. Idempotencia/alternates/salida final0 atestados en QA; no log dedicado de segunda ejecución b26efa4. Hashes identifican artefactos, no certifican ejecución.
- Verificación independiente: 30 casos sintéticos (incluye regresión raíz antes/después y restauración fallida) y cotejo offline de15pares/sitemap30; evidencia `docs/audit/2026-10-19-q076/`. No certifica producción ni MariaDB/LiteSpeed/CDN/hooks externos.
- **Q-077 P3 OPEN:** portada sinWebSite/WebPage; sieteENsin inLanguage en C-seo.json (portada, doctora, primera visita, contacto, treslegales). Harness SEO permite vacío y no sale con error al imprimir fallos; no cierra schema.
- **Q-078 P2 OPEN:** IndexNow no compensado, pendiente decisión de José; no se acepta el riesgo por esta auditoría.
- **Q-079 P2 OPEN (FIX laboratorio):** mkdeploy.sh copia toda tools/deploy antes de borrar env.sh/*.local.txt y calcula raíz por ../..; excluir privados antes de copiar, resolver raíz y validar destino antes de borrarlo. Solo inspección de fuente; sin ejecutar helper ni constatar exposición. No invalida los resultados funcionales de Q-076.

## Auditoría de correcciones solicitadas Q-077/Q-079 y pendiente Q-078 · Codex · 2026-10-19

Pull: **Already up to date**, HEAD62e8cba; sin nuevos commits ni cambios locales de los archivos afectados. Informe `docs/REVISION_Q077_Q079_2026-10-19.md`.

| Hallazgo | Veredicto | Evidencia / pendiente |
|---|---|---|
| Q-077 | FIX / OPEN | C-seo conserva siete EN sin inLanguage; seo_check.py acepta vacío y no devuelve error por sus fallos. No llegó corrección de schema/harness. |
| Q-079 | FIX / OPEN | mkdeploy.sh conserva copia total previa a exclusión, raíz ../.. y borrado sin validación de destino. No llegó corrección ni nuevas pruebas. No se ejecutó ni constató exposición. |
| Q-078 | REVIEW / OPEN | Efectos IndexNow no compensados; sin decisión nueva de José ni mitigación acreditada. |

Cotejo offline de evidencia pasa sin modificar su JSON; bash -n del helper pasa (no acredita aislamiento). Sin WordPress/publicación ni ejecución del helper. No cambios PHP/JS/producto. Q-076 VERIFIED_LOCAL;13.5REVIEW/13.7DOING/13.6OWNER. PROGRESS regenerado sin aumento. Sin desplegar, publicar, formularios, cuentas o secretos; cuatroHTML de José intactos.

### Reconsulta del remoto tras aviso de correcciones

Pull y ls-remote confirman solo ccr-6254502d-xly8ww en bc2deb0; helpers afectados aún en1ca68b6. No se recibieron correcciones auditables. Q-077/Q-079 FIX/OPEN; Q-078 REVIEW/OPEN. Se pide SHA/repositorio de la entrega. Informe docs/REVISION_Q077_Q079_2026-10-19.md, añadido final. Sin nuevas pruebas ni despliegue/publicación.

## Q-079 corregido, verificador SEO endurecido y Q-078 decidido · Claude local · 2026-10-19

Respuesta a la auditoría de Codex (`docs/REVISION_Q077_Q079_2026-10-19.md`). Laboratorio y documentación únicamente: sin producto, despliegue ni publicación, y sin WordPress de producción. Los cuatro HTML de José quedan excluidos.

- **Q-079 · P2 · FIXED_LOCAL.** Se reescribieron `tools/audit/en-local/mkdeploy.sh` y `sync.sh`:
  - la raíz se obtiene por `git rev-parse` y se comprueba;
  - el destino debe estar dentro de `<repo>/tmp/` y, antes de cualquier `rm -rf`, se verifica que no es un enlace ni contiene enlaces;
  - se copia solo una lista permitida, así que los privados nunca llegan a copiarse;
  - una comprobación final sale con 4 si apareciera alguno;
  - la variante `con-elementor` se retiró y los scripts de `tmp/wp-en-local/` son solo delegadores.

  `t/q079_selftest.sh` ejecuta 9 casos con `env.sh` y `*.local.txt` ficticios: **9 OK, 0 fallos**. Incluye el caso de enlace simbólico real, que devuelve 3 y deja intacto el destino. También pasa `bash -n`, y la preparación real del laboratorio da «9 archivos permitidos». Evidencia: `docs/audit/2026-10-19-local/q079-selftest.log`.
- **Q-077 (parte del verificador) · FIXED_LOCAL.** `t/seo_check.py` exige `inLanguage` = `["en-US"]` en las 15 EN y que cada EN tenga al menos los nodos de schema de su pareja ES. También falla si una ES declara `en-US`, y sale con 1 cuando hay algún fallo. `--selftest`: caso base sin fallos y **10/10 mutaciones negativas detectadas** (`seo-selftest.log`).
  - **Reevaluación de la evidencia del 19/10** (`seo-reevaluacion.log`, sin servidor): **9 fallos, todos de Q-077**:
    - siete EN sin `inLanguage`: `/en/`, About, first-visit, contact y los tres legales;
    - `/en/` sin WebSite/WebPage;
    - About EN sin ProfilePage ni Physician.
  - Hay además 6 avisos de ES sin `inLanguage`, fuera del alcance de 13.5.
- **Q-077 (schema) · OPEN.** Se pidió a la nube. Se verificará con el verificador endurecido sobre la copia local, sin desplegar.
- **Q-078 · P2 · ACCEPTED (D-100).** José eligió aceptar IndexNow tal como está. No se modifica la configuración real.
- **Estado de las tareas:** 13.5 REVIEW, 13.7 DOING y 13.6 OWNER, sin cambio. Español 221/228 e inglés 9/19.

## Q-077 corregido: schema con idioma en todas las páginas (Claude cloud, 2026-10-19)

- **Q-077 · P3 · FIXED_LOCAL.** En `Graph.php`:
  - `/en/` (raíz, no portada de WordPress) emite WebSite y WebPage propios, con `inLanguage` en-US y `isPartOf`. Si Rank Math los emitiera ahí, `strip_rank_math_duplicates()` los quita para que no se dupliquen.
  - About se detecta en los dos idiomas con `Language::is_page('dra-irina-gonzalez-saez')`: el slug inglés es `dr-…`, y por eso antes no recibía ProfilePage ni Physician.
  - ProfilePage y ContactPage llevan `@id`, `name` e `inLanguage`.
  - Resto de páginas (Primera consulta, legales, Gracias): WebPage con su idioma, solo si no hay otro nodo de página. Rank Math solo emite BreadcrumbList en ellas.
  - Español: también reciben es-MX (punto 4 opcional). La portada ES no cambia: su WebSite y WebPage siguen saliendo de Rank Math.
- **Local** (WordPress 7.1.3 sin Rank Math, las 16 EN publicadas solo en prueba y devueltas a borrador):
  - `inLanguage` en-US en las 11 EN comprobadas y es-MX en las ES;
  - /en/: Physician + WebPage + WebSite; About EN: Physician + ProfilePage;
  - un solo Physician por página;
  - `seo_check.py --selftest` sigue pasando.
- **Límite:** sin Rank Math local no se ve su BreadcrumbList ni su WebSite/WebPage de la portada ES. La aceptación es `seo_check.py` = 0 en la copia aislada (sesión local).


### Q-077 · VERIFIED_LOCAL (1f5e18e de la nube, integrado por la sesión local · 2026-10-19)

- **Entorno:** `sync.sh` con 1f5e18e en la copia aislada (WP 7.1.3 + Rank Math 1.0.280 reales), con la versión inglesa publicada solo en local desde la prueba de Q-076.
- **Resultado:** `seo_check.py` endurecido → **«TODAS LAS ASERCIONES PASAN», salida 0** (`docs/audit/2026-10-19-local/seo-1f5e18e.log` y `C-seo-1f5e18e.json`).
  - Las 15 EN llevan `inLanguage` `["en-US"]`.
  - `/en/` lleva WebSite y WebPage; About EN lleva ProfilePage y Physician.
  - Primera visita, los legales y Gracias llevan WebPage.
  - Hay un solo Physician por página y ningún tipo duplicado.
  - Sitemap con 30 URL.
- **Primera pasada:** dio sitemap 29, sin `/en/faq/`. Era la caché de Rank Math que quedó de la prueba con FAQ en borrador. Tras regenerarla (`wp rankmath sitemap generate`), salieron 30. En producción, `publish-en` purga la caché, pero conviene vigilarlo en el gate de 13.7.
- **Español:** las ES llevan `es-MX`. **Observación P3, sin bloqueo:** la portada ES sigue con el `inLanguage` `es` que pone Rank Math (la nube no la tocó a propósito); el resto del sitio dice `es-MX`.
- **Comprobaciones:** `php -l` y PHPCS de `Graph.php` pasan. `debug.log` no tiene errores nuevos.
- **Límite:** es local, sin desplegar. En producción se verifica dentro del gate de 13.7, tras el lanzamiento autorizado.
- **Estado:** 13.5 sigue en REVIEW hasta la auditoría de Codex y la verificación publicada. Español 221/228 e inglés 9/19, sin cambio.

## Auditoría recibida c151258/1f5e18e/62d920e · Codex · 2026-10-19

Informe `docs/REVISION_ENTREGA_Q077_Q079_2026-10-19.md`. Sustituye los pendientes de entrega de las comprobaciones previas.

| Hallazgo | Veredicto | Evidencia |
|---|---|---|
| Q-077 | VERIFIED_LOCAL ratificado | JSON integrado evaluado offline:0fallos/avisos,15ENen-US, WebSite/WebPage en /en/, ProfilePage/Physician en AboutEN, sitemap30. PHP lint/PHPCS OK; caso base y10/10mutaciones pasan. PortadaES conserva es (P3). |
| Q-078 | ACCEPTED D-100 | José acepta IndexNow tal como está; no requiere otra decisión. Sigue límite no reversible, sin cambio de configuración. |
| Q-079 | FIX / OPEN, parcial | mkdeploy pasa9/9; sync.sh acepta enlace intermedio site/wp-content y modifica dos marcadores fuera del laboratorio con salida0. Fixture desechable de Codex, ningún dato real. Añadir validación de componentes/ambos destinos y negativos antes de reutilizar sync. |

Evidencia independiente: `docs/audit/2026-10-19-q077-q079/review.json`; reproducción `tools/audit/review-q077-q079.py` (espera reproducir el defecto actual, no certifica seguridad). Bash syntax de3scripts OK. No publicación ni WordPress repetidos por Codex; integración real aportada por Claude.13.5REVIEW/13.7DOING/13.6OWNER; avance sin aumento. Sin deploy/formularios/cuentas/secretos/Sensia; cuatroHTML preservados.

## GO de código D-101 · Codex · 2026-10-19

72dbf9a solo añade documentación respecto a acc4cdd; no se repiten ensayos del producto sin cambios. deploy-code.sh inspeccionado: respaldo DB/código previo y sincronización solo plugin/tema, no helper sync del laboratorio ni setup/publicación. **GO para entrega de código por Claude local**, EN en borrador, con verificación posterior según BATON. Q-079 FIX no bloquea este alcance. No hay despliegue ni producción verificada por Codex en esta ronda. Q-077VERIFIED_LOCAL, Q-078ACCEPTED D-100;13.5REVIEW/13.7DOING/13.6OWNER.

## Solicitud de auditoría posterior D-101/Q-079 · Codex · 2026-10-19

Pull/ls-remote siguen en b83d9ee, única rama; sin registro nuevo del despliegue o corrección posterior de sync (último cambio c151258). Despliegue/verificaciones REVIEW por evidencia no recibida; Q-079 FIX/OPEN. No se atribuye despliegue a partir del GO. Informe docs/REVISION_D101_POSTDEPLOY_2026-10-19.md. Sin nuevas pruebas ni operaciones reales; estados y avance intactos.

### Q-079 (sync.sh, enlaces intermedios de acc4cdd) · FIXED_LOCAL · y estado del despliegue D-101 · Claude local · 2026-10-19

- **Corrección:** `tools/audit/en-local/sync.sh` recorre componente a componente `site/wp-content/{themes,plugins}/<destino>`. Sale con 3 sin tocar nada si algún componente es enlace o junction, no es un directorio o no resuelve físicamente a sí mismo dentro de la copia. También sale con 3 si el destino contiene enlaces antes de borrarlo. Además comprueba la raíz del repositorio.
- **Pruebas:** `t/q079_selftest.sh` da **30 OK, 0 fallos** (`docs/audit/2026-10-19-local/q079-selftest.log`).
  - Cubre symlink y junction (`mklink /J`) en `site`, `site/wp-content`, `site/wp-content/plugins` y en el destino del tema, además de un enlace dentro del destino. Todos dan ALTO y dejan intactos los marcadores sintéticos de fuera.
  - Cada enlace se retira uno a uno, y la carpeta no se borra si queda alguno.
- **Control negativo:** con el `sync.sh` de c151258 fallan 13 casos y los marcadores de fuera se sobrescriben (`q079-selftest-control-c151258.log`).
- **Pendiente de Codex:** `tools/audit/review-q077-q079.py` de Codex afirma que el bypass existe, así que ahora fallará esa aserción; es lo esperado.
- **Laboratorio:** no se volvió a ejecutar `sync.sh` sobre el laboratorio real.
- **Despliegue D-101 · NO EJECUTADO.**
  - Hay GO de Codex (b83d9ee) y autorización de José (D-101).
  - Comprobación previa en producción, solo lectura:
    - 16 entradas EN en borrador (11 páginas y 5 fichas), 0 publicadas;
    - plugin 0.4.5 activo;
    - línea base `/en/contact/` → 301 a `/contacto/`;
    - `/en/` y `/en/sleep/` → 404;
    - portada con `inLanguage` `es`.
  - La ejecución de `tools/deploy/deploy-code.sh` la bloqueó el control de permisos de la sesión local; no se rodeó. Producción sigue sin cambios: no hubo respaldo nuevo ni entrega. El despliegue queda a la espera de que José lo autorice en la sesión o lo ejecute él.

## Auditoría de9b52e1e · Codex · 2026-10-19

- **Q-079 VERIFIED_LOCAL**:30/30 repetidos en Windows con enlaces/junctions disponibles. Sintaxis bash OK. Reproducción independiente: c151258 permite bypass; actual sale3 y preserva marcadores. Ajuste de auditoría: prevalidar ambos destinos antes de modificar ninguno; negativo plugin enlazado preserva tema local. Evidencia docs/audit/2026-10-19-q079-final/review.json. Fixture desechable, no laboratorio real.
- **D-101 NO EJECUTADO** según QA/Claude y propietario: control de permisos local rechazó ejecución; no hubo nuevo respaldo ni entrega. No se elude. Lectura previa:16draftEN/0publish, plugin0.4.5, contact301aES, root/sleep404. No son verificaciones posteriores.
- GO de código b83d9ee vigente; producto sin cambios. Resolver permisos por flujo normal local y aportar evidencia tras entrega autorizada. No nueva autorización de publicación. Q-077VERIFIED_LOCAL;Q-078ACCEPTED D-100;13.5REVIEW/13.7DOING/13.6OWNER y avance sin cambio.
- Informe docs/REVISION_D101_POSTDEPLOY_2026-10-19.md. Sin deploy/publicación/formularios/cuentas/secretos/Sensia por Codex; cuatroHTML conservados.

### D-101 · despliegue de código EJECUTADO y verificado en vivo · 2026-10-19 16:38

- **Despliegue:** lo ejecutó José desde su terminal con `deploy-code.sh`. Árbol en 9b52e1e, con `wp-content` igual al de b83d9ee (base del GO de Codex).
- **Respaldo:** `~/deploy-backups/20261019-163838/` (base 460 K y 30 tablas, más el plugin y el tema anteriores). Después se purgó LiteSpeed.
- **Verificación en vivo** (`docs/audit/2026-10-19-d101/verificacion.md`):
  - **`/en/contact/` pasa de 301 a 404 (Q-075 VERIFIED en producción)**;
  - las 16 EN siguen en borrador;
  - sitemap con 15 URL y 0 EN;
  - selector, hreflang y enlaces /en/ = 0 en las 15 ES;
  - schema ES con `es-MX` (portada `es`, P3);
  - 15/15 ES con HTTP 200, sin imágenes rotas, menú correcto, sin errores de consola ni de PHP;
  - Doctoralia apagado.
- **Estado de las tareas:** 13.5 sigue en REVIEW (la verificación publicada del inglés llega con 13.7), 13.7 DOING y 13.6 OWNER.

## Auditoría aa821b1: despliegue D-101 · Codex · 2026-10-19

**D-101 COMPLETADO Y VERIFICADO en alcance código**. Respaldo/purga/SHA9b52e1e/16ENdraft atestados por José/Claude; árboles Git wp-content iguales a b83d9ee. Se supera el bloqueo anterior, sin repetir operación. HTTP independiente:15ES200, schema14es-MX+portadaes, máximo1Physician, sin tipos de página repetidos; sitemap15/0EN;10EN404sinLocation(incluyecontact);0selector/hreflang/enlacesEN/Doctoralia;0PHPFatal/WarningHTML. Evidencia docs/audit/2026-10-19-d101-codex/public.json; script tools/audit/review-d101-public.py.

Límites: fotos/menús/consola y respaldo por atestación; fotos cubren6páginas clave, no15. HTTPno certificaSHAservidor/estados privadosDB; md5aportados truncados e intervalosIDsno inventario16exactos. Ausencia de logs no prueba ausencia universal de errores. Informe docs/REVISION_D101_POSTDEPLOY_2026-10-19.md. Q-075VERIFIEDHTTP,Q-077/Q-079VERIFIED_LOCAL,Q-078ACCEPTED.13.5REVIEW/13.7DOING/13.6OWNER, avance sin cambio. Codex no desplegó/publicó ni probó formulario/cuentas/secretos/Sensia; HTMLpreviosintactos.

### Gate de lanzamiento EN preparado y estado real de 13.6 · Claude local · 2026-10-19 (tras c725b1b)

- **13.5, verificación de producción preparada (sin publicar).**
  - `seo_check.py` acepta `--base` y `--prod`: hace GET sin seguir redirecciones y revisa los enlaces internos de las EN, `og:image` en HTTPS y la cabecera LiteSpeed.
  - La paridad de schema se limita a los nodos acordados. En producción la portada ES lleva además ImageObject de Rank Math, que habría dado un falso fallo.
  - `--selftest`: **14/14** (base, 13 mutaciones y 3 nuevas: enlace roto, og:image 404 y About sin ProfilePage). Local sin `--prod`: 0 fallos.
  - Pasada de referencia en producción (`docs/audit/2026-10-19-13.5-prod/prelanzamiento.log`): falla como se espera, con 15 EN en 404 y sitemap 15/0. **No es verificación de 13.5.**
  - Procedimiento en `docs/GATE_LANZAMIENTO_EN.md`.
  - Nuevo `tools/deploy/backup-db.sh`, con el mismo bloque de respaldo que `deploy-code.sh` (diff idéntico). No se ejecutó.
- **13.6, hallazgo (lectura de producción):**
  - Hay **12 aprobaciones registradas el 17-oct entre las 10:44 y las 10:48 (Monterrey)** con el enlace de la guía de la Dra. (`by: irina`; usuario 2, `revisor_medico`): 7 de 8 páginas EN y 5 de 5 fichas.
  - **Contact EN no tiene aprobación.**
  - Ningún documento del repositorio las registraba. La comparación texto a texto muestra que lo aprobado coincide con los borradores actuales. Contra el build actual solo difieren About y Contact, en la negrita de un titular (D-096).
  - **13.6 sigue OWNER**: José debe confirmar que las aprobó la Dra. No se modificó ninguna aprobación.
- **Evidencia D-101:** el inventario exacto de los 16 IDs EN se añadió a `docs/audit/2026-10-19-d101/verificacion.md`.
- **Qué no se hizo:** no se ejecutaron `setup-en`, `publish-en` ni `deploy-code`, y no se publicó nada. Estado de las tareas: 13.5 REVIEW, 13.6 OWNER y 13.7 DOING, sin cambio.

### 13.6 · aprobaciones confirmadas (D-102) y borradores sincronizados · Claude local · 2026-10-19

- **Decisión:** José confirmó que las 12 aprobaciones del 17-oct son de la Dra. y eligió sincronizar los borradores y pedirle 2 páginas.
- **Simulación previa de solo lectura** (`tools/audit/simula-setup-en.php`): solo About perdería la aprobación (negrita del titular, D-096); Contact cambia y no estaba aprobada.
- **Ejecución:**
  - respaldo `~/deploy-backups/20261019-172941/` (448 K, 30 tablas);
  - `setup-en.sh` (solo borradores): aviso únicamente en About y las fichas sin cambios;
  - purga de LiteSpeed.
- **Resultado:**
  - **6/8 páginas y 5/5 fichas aprobadas; faltan About y Contact.**
  - La segunda simulación da las 11 páginas IGUAL al build.
  - Las 16 EN siguen en borrador; `/en/` da 404; sitemap sin EN; las ES no tienen hreflang ni enlaces /en/.
- **Evidencia:** `docs/audit/2026-10-19-13.6/sincronizacion.md`.
- **Estado:** 13.6 sigue OWNER (faltan 2 aprobaciones de la Dra.); 13.5 REVIEW y 13.7 DOING. No se publicó nada.

### 13.6 · aprobación médica EN completa · Claude local · 2026-10-19 17:48

- **Lectura de producción:** las 8 páginas EN aprobadas por la Dra. en su guía (19-oct, entre las 17:48:14 y las 17:48:30; registro `page_approve`, `by: irina`, sin notas; `_di_page_approved_by` = 2). Las 5 fichas siguen `medically_approved` (17-oct). Legales por D-099.
- **Simulación:** las 11 páginas salen IGUAL al build. Las 16 EN siguen en borrador.
- **Nota:** el primer aviso de José llegó antes de que la aprobación se guardara. A las 17:38 no constaba nada; se reenvió el enlace y quedó registrada a las 17:48.
- **Estados:** 13.6 pasa a **REVIEW** (falta la ratificación de Codex; el avance no cambia). 13.5 REVIEW y 13.7 DOING.
- **Siguiente paso:** el lanzamiento solo se hace con la orden expresa de José (D-098), siguiendo `docs/GATE_LANZAMIENTO_EN.md`.

## Ratificación13.6 sobre72a2288 · Codex · 2026-10-19

**13.6DONE**:8page_approve por irina19-oct17:48,5fichas medically_approved17-oct y legalesD-099. Lecturas/registro privados por atestación Claude+propietario/D-102; no nueva entrada de Codex a cuentas/DB. Simulación posterior11IGUAL al contenido del build; inspeccionado el comparador, PHP lintOK. Codex regenera20archivosENsin diferencias y verifica manifiesto11/8Elementor. HTTPpúblico sigue15ES200,sitemap15/0EN,10EN404,ENoculto. Evidencia docs/audit/2026-10-19-13.6-codex/build-check.json; informe docs/REVISION_13_6_2026-10-19.md.

Límites: simulador compara cuerpoHTML/Elementor, no todos los metadatos; aprobación real y16draft por atestación.13.5REVIEW/13.7DOING; orden expresa de publicación aún no dada. Inglés11/19(57,89%),4/7DONE; español221/228sin cambio. Sin deploy/setup/publicación/formularios/cuentas/secretos/Sensia; HTMLpreviospreservados.

## Lanzamiento de /en/ en producción (D-103) · Claude local · 2026-10-20

- **Orden y condiciones:** José dio la orden expresa en el chat local. Gate completo:
  - revisión de `publish-en.sh`: 16 listas;
  - respaldo `20261020-154356` (452 K, 30 tablas);
  - publicación 16/16 («Versión inglesa publicada»), con 15/15 HTTP 200 en la verificación del script;
  - regeneración del sitemap y purga de caché.
- **Verificación final:** `seo_check.py --prod` → **salida 0**.
  - 15 EN con 200 sin redirección, canónicas y OG correctos;
  - hreflang recíproco con `x-default` y selector por parejas;
  - sitemap 30; schema `en-US` y paridad de nodos;
  - Thank you con `noindex`; 30 enlaces internos sin errores; `og:image` con 200; caché LiteSpeed presente.
- **Revisión visual:**
  - 9 páginas sin imágenes rotas ni errores de consola; menú EN; Doctoralia apagado;
  - móvil (375) de Sleep y ENT con foto y encuadre como en ES, sin desborde;
  - sin `error_log`.
- **Q-080 · P3 · OPEN:** el título del 404 EN usa el nombre del sitio en español («Dra. …»), y el `alt` de la foto de First visit está en español. No bloquea; se encarga a la nube.
- **Estados:** 13.4, 13.5 y 13.7 pasan a **REVIEW** para que Codex los ratifique. El avance solo cambia con DONE.
- **Evidencia:** `docs/audit/2026-10-20-en-prod/` (`lanzamiento.md`, `seo.log`, `C-seo.json`).
- **Límites:** IndexNow avisó y no se deshace (D-100); no se probó el formulario ni SMTP.

## Q-080 corregido: textos en español en /en/ (Claude cloud, 2026-10-20)

- **Q-080 · P3 · FIXED_LOCAL.** Cambios solo en `inc/i18n.php` del tema. No toca el contenido Elementor aprobado ni los adjuntos.
  1. **Título de la 404 en /en/:** «Page not found | Dr. Irina González Sáez». Toma el `nombre_profesional` inglés del Consultorio; si falta, usa el del sitio.
  2. **Alt de las fotos:** `irina_alt_en()` traduce en /en/ el alt de la Biblioteca solo si coincide exactamente con uno de los alts españoles vistos en producción el 20-oct:
     - hero-home, hero-dra, hero-primera, hero-contacto, hero-sueno y hero-orl;
     - area-orl, area-sueno y dra-escucharte.

     Un alt distinto, como el inglés que ya trae un widget (endoscopio, logos), no se toca.
  3. **og:image:alt en /en/:** también salía en español («Tu Otorrino Especialista en Sueño»). Ahora es «Dr. Irina González Sáez · Your ENT and sleep specialist», con los filtros de Rank Math `opengraph/facebook/og_image_alt` y `twitter/twitter_image_alt`.
- **Local:** adjunto de prueba con el alt de Primera consulta:
  - por /en/first-visit/ → «Dr. Irina González Sáez — First visit»; por /primera-consulta/ → sin cambio;
  - alt explícito del widget en inglés → sin cambio;
  - filtro og en /en/ → inglés; en español → sin cambio.

  Adjunto de prueba borrado. PHPCS y php -l en verde.
- **Límite:** sin Rank Math local. El título de la 404 y el nombre exacto del filtro og (`rank_math/opengraph/{red}/{propiedad con _}`) se verifican en la copia aislada o en producción. Si se edita un alt español en Medios, hay que actualizar su traducción en `irina_alt_en()`.


### Q-080 (8c5f7f9 de la nube) · VERIFIED_LOCAL · Claude local · 2026-10-20

- **Revisión de código:**
  - `php -l` y PHPCS de `inc/i18n.php` pasan.
  - Los nombres de los filtros están confirmados en el código de Rank Math 1.0.280: `Opengraph::tag()` aplica `rank_math/opengraph/{red}/{propiedad con _}`, así que `og:image:alt` → `rank_math/opengraph/facebook/og_image_alt`.
- **Cobertura (lectura de producción):** los alts de los 12 adjuntos y de la imagen social por defecto (id 361) coinciden exactamente con las claves del mapa. Las 16 EN publicadas solo muestran alts en español que están en el mapa (portada, About, ENT, Sleep, First visit y Contact). El alt de la cámara de endoscopia ya está en inglés en el widget.
- **Copia aislada** (WP y Rank Math reales; alts de producción copiados a los adjuntos locales; imagen social por defecto local), en `docs/audit/2026-10-20-q080/local.log`:
  - 404 EN: «Page not found | Dr. Irina González Sáez»; 404 ES sin cambio.
  - `og:image:alt` en `/en/` y `/en/sleep/` en inglés; en `/` y `/sueno/` sin cambio. Rank Math no emite `twitter:image:alt` en esta configuración.
  - Los alts de los heroes y las áreas en las 6 EN, en inglés; las ES, sin cambio.
  - Regresión de 13.5 (`seo_check.py`): sin fallos.
  - Efecto del montaje: en la portada local el hero muestra el alt social porque se usó hero-home como imagen social. En producción son adjuntos distintos.
- **Despliegue:** pendiente del GO de Codex, con `deploy-code.sh`. El contenido aprobado no cambia; es solo código del tema.

## Ratificación D-103 y GO Q-080 · Codex · 2026-10-20

13.4/13.5/13.7REVIEW→DONE. OrdenJoséD-103,preflight/respaldo20261020-154356/16publish/purga poratestación;13.6DONEprevio. Codex repite seo_check --prod (GETsin formularios):salida0,sitemap30/15EN200/OG/hreflang/selector/schema/enlacesyauxiliaresOK. Evidencia docs/audit/2026-10-20-codex/C-seo.json. Visualconsola/imágenes porClaude,muestra9páginas/móvil375. NoSHAservidor porHTTP ni restauraciónrespaldo. Inglés19/19,7/7DONE;ES221/228sin cambio.

Q-080VERIFIED_LOCAL ratificado:PHPlint/PHPCS y14callbacksreales con dobles pasan; evidencia q080-callbacks.json. IntegraciónRankMathreal/hook poratestación/local.log. Twitteraltvacío no se certifica emitido. **GOdeploy-code.sh** conrespaldo/verificación;únicodeltaproducto tema/inc/i18n.php, scriptcopiaambosplugin/tema. Sincontenido/aprobaciones/estados ni nueva publicación. Q-080queda pendienteverificaciónpublicada. Informe docs/REVISION_LANZAMIENTO_EN_Q080_2026-10-20.md. Sinoperacionesremotas/cuentas/secretos/Sensia porCodex;HTMLusuariointactos.

### Q-080 · VERIFIED en producción · Claude local · 2026-10-20

- **Entrega:** `deploy-code.sh` con el árbol en 1d3b6cf, tras el GO de Codex. Único delta de producto: `themes/irina-gonzalez/inc/i18n.php`, con md5 igual en servidor y repositorio.
- **Respaldo:** `~/deploy-backups/20261020-162726/` (472 K, 30 tablas, más plugin y tema). Después, purga de LiteSpeed.
- **Verificaciones 1–8** (`docs/audit/2026-10-20-q080-prod/verificacion.md`):
  - el 404 EN dice «Dr.» y el ES no cambia;
  - los alts conocidos salen en inglés en /en/; los ES y los vacíos se conservan;
  - `og:image:alt` en inglés en /en/ y /en/sleep/, y sin cambio en ES (`twitter:image:alt` no se emite);
  - `seo_check --prod` sale con 0 (sitemap 30, 30 enlaces sin errores);
  - las 16 EN son idénticas antes y después (`publish`, fechas, aprobaciones y huella del contenido);
  - menús, imágenes, consola y móvil correctos; sin `error_log`; Doctoralia apagado.
- **Estado:** Q-080 queda VERIFIED. 13.4, 13.5 y 13.7 siguen DONE (Codex, 1d3b6cf). Inglés 19/19; español 221/228.

## Ratificación Q-080 VERIFIED — Codex — 2026-10-20

Entrega a3e873a, código declarado 1d3b6cf. Respaldo 20261020-162726/472 K/30 tablas y purga atestados. Codex verifica 14 rutas públicas: 404 EN/ES, alts EN/ES y vacíos por seis pares, OG inglés/español conservado; sin Twitter alt emitido. Seo_check --prod repetido: salida 0, sitemap 30 y regresión sin fallos. Archivos estado-antes/después idénticos byte a byte, 16 publish. Evidencia `docs/audit/2026-10-20-q080-codex/`; script `tools/audit/review-q080-public.py`.

**Q-080 VERIFIED**. Límites: estado privado y hashes remotos por evidencia aportada; huellas/prefijo MD5 truncados en el informe, no comparación remota completa por Codex. Visual/consola/imágenes y respaldo atestados; HTTP no certifica SHA ni todos los anchos. Informe `docs/REVISION_Q080_PRODUCCION_2026-10-20.md`. Tareas DONE y avance intactos, no repetir entrega/publicación. Sin formulario/SMTP/cuentas/secretos/Sensia por Codex; HTML previos conservados.

## Ajustes de José tras Q-080 (Claude cloud, 2026-10-20)

- **og:image:alt en /en/:** «Dr. Irina González Sáez · Your ENT | Sleep specialist», como el subtítulo aprobado de About (decisión de José).
- **inLanguage de Rank Math:** la portada ES decía «es». Ahora `strip_rank_math_duplicates()` normaliza cualquier `inLanguage` de Rank Math al del idioma de la página: es-MX o en-US, como el resto del grafo.
- **Local** (filtro `rank_math/json_ld` simulado): / → es-MX; /en/ → en-US; los nodos Person se siguen quitando. PHPCS y php -l en verde.
- **Despliegue:** José autoriza subirlo sin esperar el GO de Codex, que lo audita después. Verificar en producción: og:image:alt EN, inLanguage es-MX en la portada ES y `seo_check --prod` con salida 0.

## Q-081: aviso sugerido en inglés (Claude cloud, 2026-10-20, D-105)

- **Código:**
  - `inc/i18n.php`: `irina_en_hint_url()` y un contenedor oculto en `wp_footer`. Sale solo en páginas o entradas ES con pareja EN publicada (`Language::translation`), y nunca en Gracias. El contenedor es igual para todos los visitantes, así que es compatible con la caché de LiteSpeed. Lleva `lang="en"`, role region con etiqueta, «This page is also available in English.», «View in English» y la X con `aria-label="Close"`.
  - `main.js`:
    - se muestra solo si `navigator.languages[0]` (o `navigator.language`) es `en` o `en-*`;
    - guarda en localStorage `di-en-hint` = closed / en / chosen (el selector EN/ES también cuenta como elección), con try/catch;
    - Escape cierra;
    - no roba el foco, no redirige y no añade seguimiento.
  - `components.css`: fijo, sin CLS:
    - en móvil, sobre la barra inferior (72 px + zona segura);
    - en horizontal bajo, a la izquierda de la columna de botones;
    - en escritorio, abajo a la izquierda, con un máximo de 26 rem;
    - oculto con el menú abierto y al imprimir; sin animación con movimiento reducido.

  No cambia textos de marca, menús, sombras existentes, contenido Elementor, adjuntos ni aprobaciones.
- **Pruebas** (`tools/audit/q081/hint-test.js`, Playwright sobre WordPress local con /en/ publicada solo en prueba y devuelta a borrador): **37/37 OK**.
  - Idioma: en-US, en-GB, EN-us y en → visible; es-MX, es, en como idioma secundario y eng → oculto.
  - Rutas: el enlace lleva a la pareja; la portada lleva a /en/. Sin contenedor en /gracias/, en una página sin pareja ni en /en/…; con las EN en borrador, tampoco hay contenedor.
  - Memoria: cerrar o Escape → no reaparece al recargar ni en otra página; «View in English» guarda «en»; el selector guarda «chosen».
  - localStorage bloqueado: se ve, se cierra en la vista, reaparece al recargar y no hay errores JS.
  - Teclado: Tab alcanza el enlace y el cierre.
  - Pantallas: 390×844, 844×390 y 1440×900 sin solaparse con la barra ni salirse de la pantalla; menú abierto → oculto.
  - PHP -l, PHPCS, ESLint, stylelint y `node --check` en verde.
- **Límites:**
  - el idioma del navegador se simula con `navigator.languages`;
  - no se probó en Safari ni Firefox reales, ni con lector de pantalla;
  - el servidor PHP local no tiene LiteSpeed; la integración con caché real queda para la sesión local.

  Sin desplegar.


### 1c685c4 desplegado y verificado; Q-081 VERIFIED_LOCAL (espera GO) · Claude local · 2026-10-20

- **1c685c4** (ajustes de José; él autorizó desplegarlo sin esperar el GO de Codex, que lo audita después):
  - `og:image:alt` EN = «Dr. Irina González Sáez · Your ENT | Sleep specialist».
  - `strip_rank_math_duplicates()` normaliza el `inLanguage` de Rank Math (la portada ES pasa de `es` a `es-MX`; **cierra la observación P3**).
  - **Despliegue solo de 1c685c4:** los 3 archivos del tema de Q-081 se dejaron temporalmente en su versión de 1c685c4 (`git checkout 1c685c4 -- …`), se ejecutó `deploy-code.sh` y se restauraron a HEAD. El repositorio quedó limpio.
  - Respaldo `~/deploy-backups/20261020-171246/` (464 K, 30 tablas) y purga de LiteSpeed.
  - El md5 del servidor coincide con 1c685c4 en `i18n.php`, `main.js`, `components.css` y `Graph.php`.
- **Verificación en producción** (`docs/audit/2026-10-20-1c685c4-prod/`):
  - `og:image:alt` nuevo en /en/ y /en/sleep/; ES sin cambio;
  - `inLanguage` `es-MX` en `/` (3 nodos) y en `/sueno/`; EN con `en-US`;
  - ningún `di-en-hint` en producción (Q-081 sin desplegar);
  - `seo_check --prod` sale con 0;
  - las 16 EN idénticas antes y después (estado, fechas, aprobaciones y huella).
- **Q-081 (71ae61c) · VERIFIED_LOCAL** (`docs/audit/2026-10-20-q081-local/`):
  - `php -l` y PHPCS pasan.
  - La prueba de la nube, adaptada a la copia (`tools/audit/q081/hint-test-local.cjs`: Playwright local, Chrome y puerto 8890, con los mismos casos), da **37/37** con WP y Rank Math reales: idioma del navegador, enlace y texto, teclado, Escape, persistencia, `localStorage` bloqueado, selector, Gracias, página sin pareja (una página sintética solo en la copia), páginas EN, móvil, horizontal, escritorio y menú abierto.
  - El contenedor sale con la URL de la pareja en `/`, `/primera-consulta/` y `/sueno/ronquido/`, y no aparece en Gracias, en la página sin pareja ni en las EN.
  - Regresión de 13.5 local sin fallos. Capturas en 390×844, 844×390 y 1440×900.
  - **Observación P3:** en móvil, en la primera pantalla de Primera consulta, el aviso tapa unos píxeles del borde inferior del botón WhatsApp del hero. Se puede cerrar y no impide pulsar el botón.
  - Límites:
    - sin LiteSpeed en la copia (el contenedor es igual para todos y la lógica va en el cliente);
    - ESLint y stylelint no instalados en esta PC (`npx` sin paquetes): no se ejecutaron aquí.
- **Despliegue de Q-081:** espera el GO de Codex.

## Auditoría 490b0e4 — 1c685c4 y Q-081 — Codex, 2026-10-20

1c685c4 ratificado: cuatro prefijos MD5 aportados coinciden (sin nombres/hash completo), 16 registros antes/después idénticos; GET comprueba OG alt EN nuevo, es-MX ES, sin hint en producción. Seo_check --prod repetido, salida 0/sitemap30. Respaldo 20261020-171246 y ejecución por atestación José/Claude; no repetidos por Codex.

Q-081 VERIFIED_LOCAL con ajuste final: selector desde EN no guardaba elección al faltar contenedor; Codex registra el listener fuera de esa condición. Diez pruebas reales del bloque JS en navegador sintético pasan y reproducen fallo antiguo; evidence choice.json. PHP lint/PHPCS de Graph/i18n, node --check, ESLint/stylelint OK. Las 37/37 integradas son aportadas; servidor local no atendía y no se inició ni se leyó configuración. Captura390 inspeccionada: P3 borde de CTA hero cubierto, barra inferior accesible. Sin prueba LiteSpeed local.

**GO para versión corregida de auditoría**, no 71ae61c aislado; entrega con respaldo/verificación según BATON. Sin contenido/aprobaciones/estados ni setup/publicación. Q-081 pendiente de verificación en producción. Informe docs/REVISION_1C685C4_Q081_2026-10-20.md; evidencia docs/audit/2026-10-20-q081-codex/. Progreso intacto. Sin deploy/formularios/cuentas/secretos/Sensia por Codex; HTML usuario intactos.

## Reconsulta de despliegue Q-081 — Codex, 2026-10-20

Pull/ls-remote al día en4dcdc13, única rama, sin registro de entrega nueva. GET Primera consulta200/cache miss sin aviso; ENFirstvisit/Gracias200sin aviso (esperado). No se infiere despliegue ni SHA de esa ausencia. **Q-081 conserva VERIFIED_LOCAL; despliegue REVIEW**, no VERIFIED. Falta SHA y evidencia de Claude, ya solicitados. Informe docs/REVISION_Q081_POSTDEPLOY_2026-10-20.md; evidencia docs/audit/2026-10-20-q081-deploy-pending/public.json. Sin operaciones reales, contenido, formularios, secretos/cuentas/Sensia; avance y HTMLusuario intactos.

### Q-081 · VERIFIED en producción · Claude local · 2026-10-20

- **Entrega:** `deploy-code.sh` con el código de 4dcdc13 (GO de Codex, con su ajuste en `main.js`). El árbol estaba en e129922, solo con documentación añadida; md5 igual en los 4 archivos.
- **Respaldo:** `20261020-173714` (464 K, 30 tablas) y purga.
- **Verificación** (`docs/audit/2026-10-20-q081-prod/verificacion.md`):
  - contenedor solo en ES con pareja, con la URL correcta; ninguno en Gracias, `/links/` ni las EN;
  - caché hit con el mismo HTML para navegadores en inglés y en español;
  - prueba de navegador 37/37 en producción;
  - elección ES desde EN, OK;
  - `seo_check --prod` sale con 0;
  - las 16 EN idénticas antes y después;
  - sin imágenes rotas ni errores de consola; Doctoralia apagado.
- **Pendiente:** se mantiene la P3 del borde del botón WhatsApp del hero en móvil.
- **Límite:** el idioma se simula en el navegador; no se probó en dispositivos físicos.

## Ratificación Q-081 VERIFIED en producción — Codex, 2026-10-20

Entrega165995e, código4dcdc13 (wp-content idéntico a e129922). **Q-081 VERIFIED**. Repetidas37/37 y EN→ES en producción con suites revisadas, contexto nuevo e idioma simulado; sin formularios. CuatroGET de Primera consulta:4cachehit y SHA256HTMLidéntico entre en-US/es-MX; contenedorESconpareja y ausenteGracias/links/EN. Seo_check --prod repetido:salida0,sitemap30. Captura390inspeccionada:barra inferior libre,P3bordeCTAheroconservada. Evidencia docs/audit/2026-10-20-q081-codex-prod/,reproductor tools/audit/q081/review-production.cjs.

16registrosprivadosaportadosantes/despuésidénticos y4prefijosMD5coincidentes; respaldo20261020-173714/464K/30tablas yejecución/purga poratestación. No SHAservidor porHTTP ni accesoDB/respaldo porCodex. Sin pruebasSafari/Firefox/dispositivosfísicos; cachéacredita respuestas muestreadas, no todoestadofuturo. P3nobloqueantependiente comoobservación de mantenimiento, sin cambioUX.

No repetirentrega/publicación; contenido/aprobaciones/estadosintactos,Doctoraliaapagado. Inglés19/19 yES221/228sin cambio. Sin deploy/formularios/cuentas/secretos/Sensia porCodex;HTMLusuariopreservados. Informe docs/REVISION_Q081_POSTDEPLOY_2026-10-20.md.

### P3 de Q-081 (el aviso tapaba los botones del hero) · FIXED_LOCAL · Claude local · 2026-10-20

- **Alcance:** encargo de José tras 980f810. Solo `assets/js/main.js` y `assets/css/components.css` del tema; **sin desplegar**.
- **Diagnóstico:** `tools/audit/q081/solape-cta.cjs` (9 páginas ES × 7 tamaños), con 24/63 fallos usando el código de producción. Solape de 5–6 px en vertical en 5 páginas y de unos 40 px en horizontal en la portada y en Conóceme.
- **Corrección:** mientras el aviso tape un botón del hero (`.di-bleed`, margen de 8 px), queda en espera con `.is-waiting` (invisible y sin clics) y aparece al desplazar. Se recalcula con scroll, resize, load y fonts.ready. Sin cambiar posiciones, textos ni PHP.
- **Resultado local:** **0/63**, regresión del aviso 37/37 y `seo_check` local sin fallos. Capturas en 7 tamaños.
- **Evidencia:** `docs/audit/2026-10-20-q081-p3/`.
- **Límites:** ESLint y stylelint no disponibles aquí (`node --check` OK); sin LiteSpeed ni dispositivos físicos.
- **Observación aparte, previa:** en horizontal, el botón ancho del hero pasa por debajo de la columna lateral de la barra móvil.
- **Siguiente paso:** auditoría de Codex y GO antes de desplegar.

## Auditoría b465e4e: mejora P3 del aviso — Codex, 2026-10-20

**GO al árbol corregido de esta auditoría, no b465e4e aislado**. Reproducidas dos regresiones: ocultación tardía al esperar y medición errónea con menúdisplay:none, reapareciendo sobre CTA al cerrarlo trasresize. Codex hace espera inmediata y recálculo por clase debody, sin medir display:none.6/6 transiciones con geometría sintética y JS/CSS reales. Se endurecen helpers de visibilidad/presencia.

63/63 y37/37 repetidos con HTML público y assets candidatos solo en navegador:29esperan al cargar,todos libres tras160px.86intercepciones de cadaasset y92documentos con CSScandidato;sin servidor modificado. SintaxisJS/ESLint/stylelint pasan. Evidencia docs/audit/2026-10-20-q081-p3-codex/;informe docs/REVISION_Q081_P3_2026-10-20.md. WP/RankMathlocal ySEOlocal son evidencia recibida,no repetida;harness sobreHTMLpúblico no demuestra despliegue/LiteSpeed nuevos.

Q-081 publicado conserva VERIFIED. La mejora P3 queda verificada en pruebas y pendiente de entrega/verificación pública con respaldo/purga, sin contenido/aprobaciones/estados. Observación previa CTAhero/columna móvil horizontal fuera de alcance. No publicar ni setupen/formularios/cuentas/secretos/Sensia porCodex;HTMLusuariopreservados. Avance sin cambio.

### P3 de Q-081 · VERIFIED en producción · Claude local · 2026-10-20

- **Entrega:** GO de Codex en f2e0603. `deploy-code.sh` con el árbol en f2e0603 (delta: `main.js` y `components.css`), con md5 igual en el servidor. Respaldo `20261020-183957` (464 K, 30 tablas) y purga.
- **Verificación** (`docs/audit/2026-10-20-q081-p3-prod/verificacion.md`):
  - matriz 63/63;
  - transiciones reales 9/9 (espera sin clics ni foco; scroll de ida y vuelta; giro; menú abrir → resize → cerrar);
  - regresión del aviso 37/37 y EN→ES OK;
  - LiteSpeed sirve los nuevos assets con hit y el mismo HTML para en-US y es-MX;
  - `seo_check --prod` sale con 0;
  - las 16 EN idénticas;
  - sin imágenes rotas ni errores de consola; Doctoralia apagado.
- **Límites:** idioma simulado; Chrome sin dispositivos físicos.
- **Fuera de alcance:** el cruce previo del CTA en horizontal con la columna de la barra.

## Ratificación P3 de Q-081 en producción — Codex, 2026-10-20

**P3 VERIFIED**, entrega1e91d88/código f2e0603.9/9 transiciones repetidas en producción; recursosmain.js/components.css públicos idénticos byte a byte al repo;4cachehit/HTMLSHA256igual entre en-US/es-MX. Seo_check --prod repetido:salida0,sitemap30. Captura390 inspeccionada:CTAhero libre mientras aviso espera. Logs aportados63/63 y37/37/EN→ES consistentes;16registros privados antes/después idénticos,4prefijosMD5coinciden.

Respaldo20261020-183957/464K/30tablas, ejecución/purga y no manipulación por atestaciónJosé/Claude. Codexno accede a servidor/base/respaldo/cuentas. Matriz63/37 no repetida en esta ronda; sí9transiciones/caché/HTTP/SEO. Chrome/idioma/viewport simulados, sin dispositivos físicos/Safari/Firefox. Fuera de alcance cruceCTA/columna horizontal previo. Informe docs/REVISION_Q081_P3_PRODUCCION_2026-10-20.md; evidencia docs/audit/2026-10-20-q081-p3-codex-prod/.

No repetir despliegue/publicación/setup ni cambiarcontenido/aprobaciones/estados. Q-081VERIFIED,inglés19/19 yES221/228sin cambio. Sin formulario/SMTP/secretos/Sensia porCodex;cuatroHTMLusuariopreservados.

### Barra móvil en horizontal: el CTA del hero cruzaba la columna lateral · FIXED_LOCAL (pendiente de auditoría y despliegue) · Claude local · 2026-10-21

- **Alcance:** encargo de José y Codex (bc57b3e). Es una mejora separada de Q-081. Solo `components.css`, dentro de la consulta de medios que convierte la barra en columna (≤ 899 px de ancho, horizontal, ≤ 500 px de alto).
- **Diagnóstico** (`tools/audit/barra-horizontal/solape-barra.cjs`, 30 rutas ES/EN × 7 tamaños, al cargar y desplazando):
  - 72/210 fallos: el CTA del hero bajo la columna en 24/30 rutas a 667×375, 740×360 y 844×390 (las legales no tienen CTA);
  - además, otros botones o enlaces pasaban bajo la columna al desplazar en 25–30 rutas por tamaño;
  - sin desborde; a 932 px no hay barra móvil.
- **Corrección:**
  - los contenedores de `.di-main` y `.di-footer` reservan la franja de la columna (46 + 12 = 58 px, como el aviso de idioma) y se alinean al margen izquierdo;
  - los fondos siguen a todo el ancho (encuadres intactos); la cabecera no cambia; no se oculta nada.
- **Resultados:**
  - WordPress local: 0/210, y nada de ninguna sección bajo la columna.
  - Interacción en local: 0/132. Incluye CTA pulsable en el centro y en los puntos medios de los bordes, columna con 3 acciones 46×46 accesibles, menú, giro, texto largo y es/en. El control con el CSS anterior da 45/132.
  - Banco con HTML público y CSS candidato solo en el navegador: 72/90 → 0/90.
  - Regresión del aviso de idioma: 63/63, 37/37 y 9/9.
  - `seo_check` local sin fallos; ESLint y stylelint (`tools/lint`) con salida 0.
- **Evidencia:** `docs/audit/2026-10-20-barra-horizontal/`.
- **Límites:** no acredita despliegue ni caché de producción; imágenes sintéticas en local; tamaños emulados.
- **Corrección de un registro anterior:** la P3 de Q-081 decía que ESLint y stylelint no estaban disponibles en esta PC. Sí lo están, en `tools/lint` (`npm run lint`).

## Auditoría4411dda: barra móvil horizontal — Codex, 2026-10-21

**GO de código** para mejoraCSS,conrespaldo/verificación posterior. Producto solo components.css,+5líneas bajo la mediaquery de la columna; no cambios adicionales de producto porCodex.132/132 independientes con HTMLpúblico yCSScandidato servido43veces solo al navegador;captura667texto largo y844fotos reales inspeccionadas. ESLint/stylelint/sintaxis herramientas pasan.

Se endurecen herramientas: otros controles bajo la columna también fallan,3accionesobligatorias y puntos fuera del viewport no se consideran alcanzables. Logs aportados0/210 y0/132 consistentes, sin enlaces secundarios bajo columna; banco0/90/regresionesQ-081/SEOlocal poratestación deClaude. No repetición deWordPresslocal; bancoHTMLnoacredita despliegue/LiteSpeed. Informe docs/REVISION_BARRA_HORIZONTAL_2026-10-21.md;evidencia docs/audit/2026-10-21-barra-horizontal-codex/.

Pendiente entrega yverificación pública con herramientas endurecidas. No contenido/aprobaciones/estados/setup/publicación. Q-081ysuP3VERIFIEDprevios;avance intacto. Sin deploy/formularios/cuentas/secretos/Sensia porCodex;HTMLusuariopreservados.
