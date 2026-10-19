# Integración real de la versión inglesa (13.5) y protecciones de publish-en (13.7) · sesión local · 2026-10-19

Encargo de Codex del 2026-10-19 (BATON y `docs/REVISION_INGLES_2026-10-19.md`), pegado por José en el chat local. Base: `ade0109`.

**Qué no se hizo:**
- No se desplegó, ni se ejecutó `setup-en`/`publish-en` contra producción, ni se publicó nada.
- No se usaron claves, cuentas reales, correo ni Sensia.
- Doctoralia sigue apagado.
- Los cuatro HTML de José no se tocaron.

## Entorno

Copia local aislada en esta PC (`tmp/wp-en-local/`; herramientas en `tools/audit/en-local/`, ver su README).
- **Stack, idéntico a producción:** WordPress 7.1.3, Elementor 4.3.5 y Pro 4.3.1, Rank Math 1.0.280 y Hello Elementor 3.5.1.
- **Infraestructura:** PHP 8.4.26 y SQLite.
- **Datos:** sintéticos.
- **Aislamiento:**
  - red externa bloqueada y registrada;
  - correo anulado y registrado;
  - `ssh`/`scp` bloqueados en el `lib.sh` local.

Con José autorizó por chat:
- copiar en solo lectura el código de WordPress, plugins y tema desde el servidor;
- descargar el plugin SQLite, WP-CLI y playwright-core.

**Cómo se reprodujo producción:** la base española se cargó con los scripts reales (`setup-site`, `setup-pages`, `setup-dra`) y las 5 fichas aprobadas. Resultado: 15 URL en el sitemap y Rank Math activo con `og:locale es_MX`. Después, `setup-en.sh` real creó las 11 páginas y 5 fichas inglesas en borrador, igual que en producción.

Hubo dos fallos del propio entorno, corregidos antes de dar resultados. Las pruebas afectadas se repitieron:
- Un aviso de Elementor contaminaba los IDs leídos por WP-CLI.
- El servidor embebido arrancó sin `-t site`, así que los CSS y JS no cargaban.

## Tres niveles de evidencia

| Nivel | Qué cubre |
|---|---|
| **Sintético** (harnesses de Codex, en esta PC) | `review-en-publish.py`: 28 casos; `review-en-i18n.php`: 28 checks; `review-en-ui.cjs`: pasa. Las salidas son idénticas a las versionadas (sin diff en `docs/audit/2026-10-19/`). |
| **Integración WordPress/Rank Math local** | Todo lo de abajo, con estados de publicación solo locales. |
| **Pendiente en producción** | Tras el lanzamiento autorizado (13.6 → 13.7): repetir `/en/contact/` sin seguir redirecciones, `og:image` real, sitemap y hreflang con el dominio, LiteSpeed/CDN e IndexNow. |

## 13.5 · Integración (inglés publicado en local)

| Comprobación | Resultado |
|---|---|
| Open Graph | Las 15 EN: `og:locale en_US`, `og:url` = su `/en/…` y título y descripción en inglés. Con la imagen por defecto apuntando a un adjunto local, las EN usan la misma `og:image` que las ES. Sin metadatos españoles heredados. |
| Canónicas | Las 15 EN apuntan a sí mismas, no a la pareja española. |
| Hreflang | ES y EN recíprocos: `es-MX`, `en-US` y `x-default` → ES. Con `/en/faq/` en borrador, FAQ ES no anuncia alternates y el resto sigue recíproco. Con la raíz en borrador, ninguna página ES tiene alternates ni enlaces a `/en/`. |
| Sitemap | **30** (15 ES + 15 EN), sin Gracias ni Thank you. Con una EN en borrador baja a 29 y la excluye. |
| Selector y menús | Ocultos con la raíz en borrador. Publicado: el selector lleva a la pareja y, sin pareja publicada, a la portada del otro idioma (FAQ ES → `/en/`). Menú EN: Home, ENT, Sleep, About, First visit y Contact; pie EN con legales EN. |
| Schema | Las 15 EN con un solo nodo Physician+Person. FAQPage con 10 preguntas = 10 `<details>` visibles. MedicalWebPage y ContactPage con `inLanguage en-US`. **Ver Q-077:** la portada inglesa no lleva WebSite/WebPage ni ningún `inLanguage`. |
| 404 y Gracias | `/en/no-existe…`: 404, `lang en-US`, «Page not found \| …». `/en/thank-you/`: 200, `nofollow, noindex`. Destino del formulario: EN → `/en/thank-you/`, ES → `/gracias/` (envío simulado). |
| Interfaz | WhatsApp EN «Hello, I would like to book an appointment with Dr. Irina González Sáez.». Carrusel con «Credential 1 of 3». Validación: «Please enter your name.» y «We need your consent to contact you.». Error de envío: 422 con «We couldn’t send your message. Please message us on WhatsApp…». Sin errores JS. |
| Formulario | EN: `di_lang=en`, motivos y privacidad en inglés; ES sin `di_lang`. **Ningún envío real**: `pre_wp_mail` capturó los intentos sin enviarlos. Turnstile no probado (claves no copiadas). |
| Sleep/ENT | `/en/sleep/`: `di-dark` y `sleep.css`. `/en/ent/`: cálido, sin `di-dark`. Fotos hero-sueno y hero-orl; encuadre `50% 0%` en escritorio, **68 %** y **64 %** en móvil (390 px); sin desborde a 390 y 1440. |
| `/en/contact/` 301 (Q-075) | **Causa reproducida y acreditada:** sin el guard, `/en/contact/` → 301 a `/contacto/` vía `redirect_canonical` ← `redirect_guess_404_permalink()` (registrado); las demás EN dan 404, el mismo patrón que producción. **Con el guard** las 16 dan 404. En producción sigue pendiente: el guard (d10018d) no está desplegado. |
| Doctoralia | Apagado: sin sección ni `data-di-doctoralia` en About. |

**Veredicto 13.5: REVIEW.** Todas las comprobaciones integradas pasan salvo Q-077, que forma parte del alcance de 13.5 («schema `inLanguage` correcto»). Falta corregir Q-077 y verificarlo, y después repetir en producción lo del nivel 3 dentro del gate de 13.7.

## 13.7 · Protecciones de publish-en con WordPress real

| # | Prueba | Resultado |
|---|---|---|
| 1 | Dry-run, sin y con aprobaciones | Salida 0, estado, contenido y aprobaciones **sin cambios**; con todo aprobado: «16 entradas listas…». |
| 2 | Preflight (16 casos con `yes legal-ok`) | **Los 16 bloquean** (salida 1) y **ninguno cambia el estado**: manifiesto vacío, truncado, JSON inválido, ficha duplicada, fichas incompletas, tipo de página o de ficha incorrecto, página ausente, ficha sin idioma, página sacada de `/en/`, pareja ES de página y de ficha no publicada, falta aprobación de página o de ficha, falta `legal-ok` y argumento desconocido. Caso adicional: una página bajo `/en/` con meta `_di_lang=es` **sí se publica**, porque para páginas el idioma lo define solo la jerarquía `/en/` (`Language::of`), por diseño. |
| 3 | Opción desconocida en el wrapper | Salida 2 antes de cargar `lib.sh` y sin crear nada en el remoto. |
| 4 | Idempotencia | La 2.ª ejecución completa no cambia estados ni `post_modified` y no hace peticiones IndexNow. |
| 5 | Errores de escritura en la 6.ª entrada (`/en/contact/`) | `WP_Error`, estado retenido por hook y excepción: salida 1, sin éxito anunciado, **«Estados restaurados»** y las 16 en borrador al final. |
| 6 | Fallo de la restauración | «REVISIÓN MANUAL: no restaurados IDs 99» y salida 1; solo esa queda publicada. |
| 7 | Verificación del wrapper | 500 en una ruta, sin alternates ni selector, red caída (000) y fallo de PHP: **todos salida 1**. En los tres primeros se imprime antes el `Success` de PHP: manda la salida final; no hay reversión automática. |
| 8 | setup-en | Error al crear: aborta («No se pudo crear faq»), salida 1, sin procesar las siguientes. Texto de FAQ cambiado: solo FAQ pierde la aprobación (aviso). Sin cambios: conserva las 8 aprobaciones. Con todo publicado y contenido cambiado: no toca publicadas ni fichas aprobadas. Detalle: los 3 legales actualizan `post_modified` aunque el texto no cambie. |

«Retorno 0» de `wp_update_post` no se puede provocar en WordPress real con `$wp_error=true` (devuelve WP_Error). Queda cubierto por los stubs de Codex.

## Hallazgos nuevos

- **Q-076 · P1 · OPEN** (bloquea el lanzamiento): `publish-en.sh` ejecuta WP-CLI con `--skip-plugins=elementor,elementor-pro`. Sin Elementor, `wp_update_post` de una página con plantilla `elementor_header_footer` devuelve `invalid_page_template` («Plantilla de página no válida»). **La publicación real fallaría en la primera entrada (`/en/`) y no publicaría nada.** El fallo es seguro, pero el mensaje dice «no restaurados IDs <raíz>» aunque su estado nunca cambió: la restauración falla por el mismo motivo. Con Elementor cargado (variante de prueba local) `publish-en.php` publica las 16 y supera la verificación. Propuesta para la nube:
  - quitar `--skip-plugins` en `publish-en.sh` (Elementor 4.3.5 con PHP 8.4 en CLI funcionó aquí y en producción el 13-oct);
  - dar por restaurada una entrada cuyo estado ya es el previo.

  Repetir la integración tras el cambio.
- **Q-077 · P3 · OPEN:** la portada inglesa (`/en/`) no lleva los nodos WebSite/WebPage de Rank Math ni ningún `inLanguage`; la española sí. Las interiores EN tienen `inLanguage en-US` donde aplica. Propuesta: tratar la raíz `/en/` como portada en Graph/Rank Math, con WebSite y WebPage `inLanguage en-US`.
- **Q-078 · P2 · OPEN (límite):** Rank Math tiene activo `instant-indexing`. En una publicación que falla a mitad, se intentaron 5 avisos `POST api.indexnow.org` de URLs que luego se restauraron a borrador; en la publicación completa, 10. La restauración no deshace esos avisos: en producción saldrían de verdad. Opciones:
  - desactivar IndexNow durante `publish-en` (filtro o módulo) y avisar solo tras una verificación correcta;
  - aceptarlo como límite.

## Límites (no garantizado)

- D-098 es **secuencial y compensatorio, no atómico**. Entre la primera escritura y el fallo hay visibilidad intermedia (en la prueba, 5 páginas publicadas unos segundos). Hooks, caché y avisos externos (IndexNow, Q-078) no se deshacen. Un fallo de la verificación HTTP no revierte nada.
- Entorno: SQLite ≠ MariaDB; sin LiteSpeed ni CDN; sin reglas de Hostinger; host `127.0.0.1`; sin Turnstile.
- Las aprobaciones de las 8 páginas y 5 fichas usadas aquí son **sintéticas y locales**. La aprobación real de la Dra. (13.6) sigue pendiente. D-099 ya cubre los legales.

## Estado

- **Español** sin cambios: 221/228 (96,93 %).
- **Inglés** aparte, sin cambios de peso: 13.5 REVIEW (Q-077) y 13.7 DOING (Q-076 bloquea).
- Siguiente: la nube corrige Q-076 (y Q-077/Q-078 si José y Codex lo deciden), la sesión local repite esta integración y Codex audita. El lanzamiento real solo con la aprobación de la Dra. y la instrucción expresa de José.
