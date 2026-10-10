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
| Q-021 | INFO | Seguridad | Clave de API de Google Maps en el HTML (inevitable con la Maps JavaScript API): debe restringirse por referente HTTP (`drairinagonzalez.com/*`) y a la API «Maps JavaScript API» en Google Cloud; no se expone en la REST pública (`maps_api_key` no publicable) y no se pega en chats ni en el repo. | Claude | REVIEW | Clave cargada por la sesión local desde archivo local (borrado), no expuesta en REST; API habilitada por José el 10-10 y mapa verificado en vivo por ambas sesiones (paleta, pin; 1440: 13 tiles, 390: 7 tiles, consola sin errores; interfaz en español con `language=es&region=MX`, desplegado 10-10). Pendiente: José confirma las restricciones de referente y de API en Google Cloud |
| Q-022 | MEDIUM | Accesibilidad | Auditoría automática en vivo (2026-10-10, home/contacto/Dra. 1440 y 390): sin `<main>` en las páginas con plantilla Elementor header/footer (el enlace «Ir al contenido» apuntaba a nada); eyebrows en teal fuerte a 4.3:1 sobre crema (AA exige 4.5); puntos del carrusel de 8 px sin área táctil; enlaces del pie de 19 px de alto. Correcto: 1 h1 por página, `lang=es`, imágenes con alt, botones con nombre accesible, campos con etiqueta, sin tabindex positivo, foco visible, sin errores de consola. | Claude | FIXED | `<main>` desde header/footer en esas plantillas; `--color-secondary-strong` #2a787d (4.9:1); área táctil 24 px en puntos; padding en enlaces del pie. Botón de WhatsApp: sólido teal sobre claros y glass sobre fotos (D-053) |
| Q-023 | LOW | Seguridad | Sin cabeceras HSTS, nosniff, X-Frame-Options, Referrer-Policy ni Permissions-Policy (solo `upgrade-insecure-requests` de la CDN). | Claude | FIXED | Módulo `Security\Headers` del plugin (0.4.0) las envía en el front; verificar con `curl -I` tras desplegar |

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
| Q-039 | P1 | baseline-360.png, 851 px y retrato 40,5 % visible  `wp-content/themes/irina-gonzalez/assets/css/components.css:317` | FIXED_LOCAL / OWNER | `03db275`: A 668 px, gap16, rostro estimado 98,6 %; elección y producción pendientes |
| Q-040 | P2 | Ops/Endpoints.php::DENY: superficie de opciones extensa para admin  `wp-content/plugins/dra-irina-core/src/Ops/Endpoints.php:19` | OPEN | Propuesta técnica: inventariar consumidores y pasar a allowlist; permiso manage_options y veto de secretos ya activos |
| Q-041 | P2 | assets/js/map.js, Marker obsoleto  `wp-content/themes/irina-gonzalez/assets/js/map.js:18` | OWNER | Mantener hasta mapId; guard contra mapa duplicado corregido en `03db275` |
| Q-042 | P2 | NAP/horarios/hospitales repetidos en builds/defaults; fuente central existe  `tools/content/build-home.py:55` | OPEN | Migración a datos centrales al renderizar como próxima unidad técnica, sin cambiar textos aprobados |
| Q-043 | P1 | build-pages.py pc-narr y build-dra.py narrativa draft=yes; marcas visibles en Dra./Primera consulta  `tools/content/build-pages.py:68` | OWNER | Confirmar aprobación antes de retirar marca en build; no ocultar ni publicar por inferencia |
| Q-044 | P2 | Ocho defaults de títulos Elementor conservaban punto final en em, contrario a D-049  `wp-content/plugins/dra-irina-core/src/Rendering/Elementor/Faq.php:31` | FIXED_LOCAL | e87c29b: retirar solo el punto; sin cambiar textos publicados ni reparto de acentos |
| Q-045 | P2 | MetaRegistry aceptaba 2026-02-30 como fecha por validar solo formato  `wp-content/plugins/dra-irina-core/src/Fields/MetaRegistry.php:235` | FIXED_LOCAL | e87c29b: checkdate; regresión de fecha inválida/válida |

Informe, variantes, límites y preguntas: `docs/AUDITORIA_CODEX_2026-10-10.md`. Datos de rectángulos: `docs/audit/2026-10-10/*-metrics.json`. Relevo: BATON. No se ejecutó ninguna prueba del formulario/SMTP en vivo.
