# QA — Hallazgos y resolución

Clasificación: BLOCKER · CRITICAL · HIGH · MEDIUM · LOW · SUGGESTION
Estados: OPEN · FIXING · FIXED · VERIFIED · WONTFIX (con motivo)

## Auditorías de Codex

| # | Fecha | Alcance | Hallazgos | Estado |
|---|---|---|---|---|
| — | — | Pendiente primera auditoría (ver `BATON.md`) | — | — |

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
| Q-012 | MEDIUM | SEO/Infra | La redirección de hPanel del dominio aparcado solo cubre la raíz: `https://otorrino-monterrey.com/<ruta-vieja>/` responde 404 del WordPress (mismo webroot) en vez de 301 a la misma ruta; además http→https→destino son 2 saltos (MASTER_PROMPT §35: OLD→NEW con 301, sin cadenas). | Sesión local | OPEN | `tools/deploy/parked-redirect.sh`: bloque host-based en el `.htaccess` del webroot antes del bloque de WordPress (apex y www, http y https, misma ruta, 1 salto). Pendiente: comprobar en Search Console si hay URLs indexadas del dominio viejo |

## QA visual

_(Fase 6 en adelante.)_

## QA funcional / dispositivos / SEO / performance / seguridad / contenido

_(Fase 11.)_

