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
