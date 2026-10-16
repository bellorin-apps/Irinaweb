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
| Q-041 | P2 | assets/js/map.js, Marker obsoleto  `wp-content/themes/irina-gonzalez/assets/js/map.js:18` | OWNER | Mantener hasta mapId; guard contra mapa duplicado corregido en `03db275` |
| Q-042 | P2 | NAP/horarios/hospitales repetidos en builds/defaults; fuente central existe  `tools/content/build-home.py:55` | OPEN | Migración a datos centrales al renderizar como próxima unidad técnica, sin cambiar textos aprobados |
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

**Q-062 · P2 · FIXED por Claude cloud 2026-10-16, pendiente de verificación (evidencia9.5):** literal añadido en «Perfil de Negocio de Google». Original: QA de Google solo conserva «texto del encargo (383 caracteres)» para la nueva descripción. Falta transcripción literal para compararla con el sitio. Claude debe añadirla desde su registro existente, sin secretos; Codex no entra al Perfil ni a Doctoralia. URLUTM200/canónica portada, reservaWhatsApp coherente; no se certifica el perfil actual ni la publicación de servicios en revisión. 9.5OWNER permanece.

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
- Chrome headless a 390 px (UA Android) y 1440 px, 30 vistas: un h1 por página, sin desborde horizontal ni imágenes rotas. En consola solo aparece, en Inicio y Contacto, el aviso de `google.maps.Marker` obsoleto (Q-041, OWNER: falta el Map ID).

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
