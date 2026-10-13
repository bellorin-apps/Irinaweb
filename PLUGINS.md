## Stack actual confirmado · 2026-10-13

Fuente: docs/audit/2026-10-13/inventario-produccion.md (WP-CLI,Claude local10:35). Tarea3.6DONE: listado actual contrastado con las decisiones; no se instala ni desactiva nada.

| Plugin | Estado | Versión | Destino |
|---|---|---|---|
| dra-irina-core | activo |0.4.5| KEEP, datos/workflow/widgets/formulario/analítica/medios |
| Elementor | activo |4.3.4| KEEP, composición |
| Elementor Pro | activo |4.3.1| KEEP, licencia/constructor actual |
| LiteSpeed Cache | activo |7.9.1| KEEP, caché |
| Rank Math | activo |1.0.280| KEEP, SEO/sitemap |
| Sensia | activo |0.2.7| KEEP, NO TOCAR; solo nombre/versión del informe |
| Hostinger preview-domain | MU |1.4.0| KEEP, proveedor |
| Hostinger auto-updates | MU |1.0.8| KEEP, proveedor |

El informe no indica actualizaciones pendientes en los seis activos, a la fecha del snapshot; no garantiza que sigan así en una fecha futura. Site Kit ausente. SafeSVG/Two-Factor/QueryMonitor no figuran instalados: las filas históricas son propuestas, no hechos ni pruebas de2FA. Hello3.5.1 padre e irina-gonzalez0.1.0child activo; Astra ausente. Conversión de fotos en core, selección móvil en tema (D-073/074); no atribuir ese pipeline a LiteSpeed.

La sección de revisión12/10 y tabla v0.1 siguientes son históricas: la falta de listado que mantenía3.6REVIEW queda resuelta con esta evidencia. Veredictos y límites: docs/audit/2026-10-13/veredictos-inventario.md.


## Inventario y decisiones · revisión2026-10-12
La tabla siguiente es de decisiones/propuestas. No es un listado de plugins activos y por eso3.6 sigue REVIEW. La muestra pública confirma assets de Elementor y ElementorPro, sitemap/schema RankMath y tema irina-gonzalez. El informe histórico confirma LiteSpeed y core; la lista completa actual (incluidos plugins sin assets y MU) requiere inventario de Claude local. No se consultan aplicaciones privadas.
WebpUpload del core convierte fotografías; el tema selecciona `<picture>` móvil1600. D-073/D-074 usan q95 solo en versión móvil; originales/escritorio q100 y recortes alfa lossless. «LiteSpeed» en la fila imágenes no describe el conversor actual. SafeSVG/Two-Factor/QueryMonitor son opciones, no instalaciones certificadas; no hay que instalarlos por esta tabla. Formularios/SMTP/GA4 son propios del core. Consumidores y allowlist Ops siguen Q-040.

# PLUGINS — Decisiones

Versión 0.1 · 2026-10-07 · Estado: REVIEW. Principio: menos plugins, mejores plugins. Se instala en staging, se valida y luego se despliega.

| Necesidad | Opción | Alternativa | Decisión | Razón |
|---|---|---|---|---|
| Builder | Elementor + Elementor Pro (licencia confirmada) | Gutenberg puro | **Elementor + Pro** | Mandato §28; licencia disponible; Pro aporta Theme Builder y formularios si se usan |
| SEO | Rank Math (free) | Yoast · SEOPress | **Rank Math** | Cubre titles, metas, canonical, sitemap, robots, OG, redirecciones y schema base; coherente con idenbauer.com. Sin Pro: el schema médico lo emite el plugin propio |
| Campos estructurados | Código propio (`register_post_meta` + meta boxes) | ACF free (sin repetidores) · ACF Pro (sin licencia) · SCF | **Código propio** | Sin lock-in; repetidores (FAQ, fuentes, síntomas) sin licencia; datos expuestos en REST con schema |
| Formularios | Core Contact\Form + di-form | Elementor/Fluent Forms | **Core propio (D-050)** | Sin guardar solicitudes en BD; sin plugin de formularios |
| SMTP | Constantes DI_SMTP_* + phpmailer_init | FluentSMTP | **Core propio (D-050)** | Configuración privada del servidor, HTML/texto y Bcc |
| Caché | LiteSpeed Cache | WP Rocket · FlyingPress | **LiteSpeed Cache** si el servidor es LiteSpeed (Hostinger lo es habitualmente; confirmar en Fase 1) | Caché a nivel de servidor + optimización de imágenes/WebP + object cache; sin duplicar con otro optimizador |
| Imágenes | Conversión WebP/AVIF de LiteSpeed Cache | Imagify · ShortPixel | **LiteSpeed** | No duplicar optimización |
| SVG | Safe SVG | Subir solo PNG | **Safe SVG** | Logos e iconos en SVG saneados |
| Seguridad | Hostinger (WAF, malware scanner) + hardening en código + 2FA nativo de WP o Two-Factor | Wordfence | **Hostinger + Two-Factor** | Evitar stack redundante; WAF del hosting; 2FA obligatorio |
| Backups | Backups de Hostinger (verificar frecuencia y restauración) | UpdraftPlus | **Hostinger**; UpdraftPlus solo si los backups del host resultan insuficientes | Verificar restauración en Fase 1 |
| Cookies/consentimiento | Ninguno si solo GA4 con consentimiento básico; evaluar banner propio ligero | Complianz · CookieYes | **Pendiente Fase 8** | Depende del aviso de privacidad y de si se cargan terceros no esenciales |
| Analítica | Core Tracking\Events | GTM/Site Kit | **GA4 directo (D-052)** | No carga con ID vacío; Site Kit retirado (D-024) |
| Desarrollo (solo staging) | Query Monitor · Health Check | — | **Sí en staging; nunca en producción** | |
| Operación remota | Ops REST admin + scripts locales SSH/WP-CLI | WPVibe | **D-018: sin WPVibe** | No se expone archivos/SQL; sesión local despliega |

Prohibidos por defecto: Jetpack completo, addons masivos de Elementor, segundo plugin SEO, segundo plugin de caché, sliders, overlays de accesibilidad, plugins de schema duplicados.

Actualización 2026-10-10: Hostinger/LiteSpeed confirmados; Pro activado según D-034 y encargo. Las filas de SVG/2FA/desarrollo son decisiones o propuestas, no inventario de plugins instalados. Inventario histórico en docs/AUDIT_WP.md; QA autenticado restante en BATON.
