# Encargo para Codex — Auditoría integral, corrección y mejora del sitio de la Dra. Irina González Sáez

Versión 1.0 · 2026-10-10 · Preparado por la sesión local (Claude, PC de José) a petición del propietario.
Pega este documento completo como primer mensaje de la sesión de Codex. Todo lo que dice aquí fue
inspeccionado en el repositorio y en el sitio en vivo; nada es supuesto.

---

## 1. Tu rol y el objetivo

Eres **Codex, el auditor y reparador** del proyecto. El ciclo del proyecto es
`BUILD → REVIEW → FIX → VERIFY → GATE`: Claude (sesión cloud «Sitio web») construye; tú auditas,
corriges y verificas; la sesión local de Claude (PC de José) despliega al hosting; José (propietario y
diseñador) decide producto, UX y prioridades.

Objetivo de este encargo, en orden:

1. **Auditar todo el código y la documentación** del repositorio (tema, plugin, scripts de despliegue,
   builds de contenido, mini apps, docs) y el sitio en vivo: errores, inconsistencias, incoherencias
   entre documentos, deuda técnica, limpieza, seguridad, accesibilidad, rendimiento, SEO/schema y
   calidad visual.
2. **Corregir** lo que sea claramente un error o una inconsistencia, en commits pequeños y explicados.
3. **Arreglar la sección «Escucharte también es parte del tratamiento»** de la portada en móvil, que
   el propietario sigue viendo mal (detalle completo en la sección 8). Esta es la prioridad visual.
4. **Proponer mejoras** (código y visuales) que no sean decisiones del propietario, y **listar como
   preguntas** las que sí lo sean.
5. Dejar el relevo documentado (`BATON.md`, `QA.md`, `CHANGELOG.md`, `STATUS.md`, `NEXT.md`).

No despliegas tú: la sesión local ejecuta `git pull` + scripts de `tools/deploy/` cuando se lo pidas
por `BATON.md`/mensaje. Diseña tus cambios para que se puedan desplegar con esos scripts.

---

## 2. Contexto del proyecto

- **Sitio:** https://drairinagonzalez.com — Dra. Irina González Sáez, otorrinolaringóloga con
  subespecialidad en desórdenes respiratorios del sueño, Monterrey (CAB Medical Headquarters,
  Cumbres). Consultas de ORL y sueño; WhatsApp como canal principal de cita.
- **Repositorio:** `https://github.com/bellorin-apps/Irinaweb`, rama de trabajo `ccr-6254502d-xly8ww`
  (es la rama principal del proyecto; no hay `main` con código). Trabaja sobre esa rama o en una rama
  `codex/<tema>` que luego se integre en ella; dilo en `BATON.md`.
- **Hosting:** Hostinger compartido (cuenta u855694717), LiteSpeed, hCDN (CDN de Hostinger) con
  optimización de imágenes activa (sirve WebP reescalado al ancho del dispositivo). WordPress 7.1.3,
  PHP 8.4 en web, Elementor 4.3.4 + Elementor Pro 4.3.1, Rank Math, LiteSpeed Cache.
- **Tema:** `wp-content/themes/irina-gonzalez` (hijo de Hello Elementor 3.5.1). CSS propio en
  `assets/css/{tokens,base,components,sleep}.css`, JS en `assets/js/{main,map}.js`, partes en
  `templates/parts/**`, helpers en `inc/*.php`. Prefijo de clases `di-`.
- **Plugin:** `wp-content/plugins/dra-irina-core` (v0.4.1, PSR-4 bajo `DraIrina\Core`): CPTs
  `condicion`, `tratamiento`, `recurso`, `credencial`; taxonomías `area`, `zona`, `estado_medico`;
  widgets de Elementor `di-*` (Hero/Bleed, Areas, Motivos, Pasos, Doctora, Sueno, Ubicacion, Cta,
  Faq, Listado, Enlaces, Narrative, Timeline, Credenciales, Formulario); ajustes del consultorio
  (`Settings\PracticeSettings`, opción `dra_irina_practice`, REST `dra-irina/v1/practice`);
  schema JSON-LD (`Schema\Graph`); formulario de contacto (`Contact\Form`, admin-post `di_contact`,
  sin base de datos, honeypot + tiempo mínimo + límite por IP + origen, SMTP por constantes
  `DI_SMTP_*` en `wp-config.php`, correo HTML multipart con Bcc opcional `contacto_copia`);
  cabeceras de seguridad (`Security\Headers`); GA4 sin plugin (`Tracking\Events`, sin ID cargado
  todavía); gate de revisión médica (`Workflow\MedicalReview`): nada clínico se publica sin
  aprobación de la Dra.
- **Contenido como código:** `tools/content/build-home.py`, `build-pages.py`, `build-dra.py`,
  `build-medical.py` generan los JSON (`home-elementor.json`, `pages/*.elementor.json`,
  `dra-elementor.json`, `medical-drafts.json`, `legal/`) que los scripts `tools/deploy/setup-*.sh`
  cargan en WordPress. **Regla:** si tocas un build, regenera y commitea su JSON (ya se olvidó una
  vez y los títulos salieron sin `<b>` en vivo).
- **Despliegue (solo la sesión local):** `tools/deploy/deploy-code.sh` (tema + plugin, con respaldo
  en `~/deploy-backups/`), `setup-site.sh` (Home 168 y menús), `setup-pages.sh` (páginas interiores
  y legales), `setup-dra.sh` (página de la Dra. 169 y credenciales), `setup-medical.sh` (fichas),
  `go-live-*.sh`, `upload-media.sh` (adjuntos por slug, con sufijo de versión en el nombre de
  archivo), `deploy-briefing.sh` (mini apps), `inventory.sh`. Ver `docs/DEPLOY.md`.
- **Mini apps privadas** en `/briefing/` (token; `tools/briefing/`): cuestionario, fichas y
  **revisión médica** (`/briefing/revision/`, D-040) donde la Dra. aprueba cada ficha. Tema claro
  obligatorio (`data-theme="light"`); sin correos de aviso (decisión del propietario).
- **QA en vivo:** capturas en `https://drairinagonzalez.com/preview/qa/<nombre>-<ancho>-<AAAAMMDD-HHMM>.png`
  (noindex, sin listado). La sesión local mide con Selenium + Chrome headless (390×844 @2×, 1440×900)
  y sube capturas ahí.

### Agentes y relevo

| Agente | Papel | Cómo se comunica |
|---|---|---|
| José (propietario/diseñador) | Decide producto, UX, prioridades, alcance, accesos | Chat de cada sesión |
| Claude cloud «Sitio web» | Construye (commits en la rama) | Commits + `BATON.md` + mensajes |
| Claude local (PC de José) | Despliega, mide, captura; comete solo arreglos de scripts | Commits + mensajes |
| **Codex (tú)** | Audita, corrige, verifica | Commits + `BATON.md` + `QA.md` |

Reglas del método (de `C:\Users\bellg\.claude\CLAUDE.md` del propietario): progreso con evidencia
(`DONE` = 1, lo demás 0), una sola fuente por concepto (progreso → `PROGRESS`/`STATUS`; siguiente
paso → `NEXT`; relevo → `BATON`; porqué → `DECISIONS`), cerrar por unidad significativa (tests,
docs, `STATUS`, `NEXT`, `BATON`), y preguntar a José lo que es suyo con formato *pregunta ·
opciones · pros/contras · recomendación · impacto · reversibilidad*.

---

## 3. Lectura obligatoria, en este orden

1. `MASTER_PROMPT.md` (v1.1; Anexo A = versión en inglés, después del lanzamiento en español)
2. `STATUS.md` → `NEXT.md` → `BATON.md` → `DECISIONS.md` (D-001…D-054) → `QA.md` → `CHANGELOG.md`
3. `DESIGN_SYSTEM.md`, `ARCHITECTURE.md`, `PLUGINS.md`, `SEO.md`, `SITEMAP.md`, `PLAN.md`
4. `docs/DEPLOY.md`, `HANDOFF_LOCAL.md`, `docs/AUDIT_WP.md`, `docs/CHECKPOINT3.md`
5. Código: `wp-content/plugins/dra-irina-core/**`, `wp-content/themes/irina-gonzalez/**`,
   `tools/content/*.py`, `tools/deploy/*.sh`, `tools/briefing/**`
6. `phpcs.xml.dist`, `composer.json`, `tools/package.json`

Si un documento contradice a otro, la fuente de verdad es el código y el sitio en vivo; anota la
incoherencia y corrígela en los documentos.

---

## 4. Reglas que no se negocian

1. **Secretos:** nunca escribas claves, contraseñas, tokens ni la clave de Maps en el chat, en commits
   ni en documentos. `tools/deploy/env.sh` está ignorado por git; `wp-config.php` del servidor no está
   en el repo. No pidas credenciales.
2. **No tocar** `wp-content/plugins/sensia` ni `wp-content/uploads/sensia-privado` (otro proyecto en la
   misma cuenta de hosting).
3. **No tocar la CDN** ni su configuración (la gestiona José en hPanel).
4. **Gate médico:** `Workflow\MedicalReview::block_unapproved_publish` convierte en `pending` cualquier
   publicación clínica sin aprobación. No lo rodees, no publiques fichas, no cambies estados de
   contenido clínico. Las fichas (condiciones 205, 244–256; tratamientos 257–264, 293; recursos 286,
   319, 320) están en borrador hasta que la Dra. las apruebe en la mini app.
5. **No borres** adjuntos, páginas, plantillas ni respaldos. Las plantillas antiguas de Elementor
   (header 39 / footer 79) y la portada antigua (106) están en borrador a propósito.
6. **Decisiones del propietario:** no cambies textos de marca, nombres, horarios, precios (la Dra. no
   publica el costo de la consulta; se usa «Te confirmamos el costo al agendar por WhatsApp»),
   menús, sombras, ni las reglas de diseño de la sección 6 sin preguntar. Si algo visual te parece
   mejorable, propónlo como pregunta con captura, no lo cambies.
7. **Estándares:** PHP con `declare(strict_types=1)`, PHPCS según `phpcs.xml.dist` (WordPress), salida
   escapada, `wp_kses` con listas explícitas (`b`, `em`, `br[class]` en títulos), CSS con tokens de
   `tokens.css`, sin `!important` salvo justificado, JS sin dependencias, accesible (focus visible,
   `aria-*`, `prefers-reduced-motion`).
8. **Compatibilidad con el despliegue:** cualquier cambio de contenido tiene que entrar por los builds +
   JSON + `setup-*.sh`; cualquier cambio de ajustes del consultorio, por `PracticeSettings` (REST o
   WP-CLI). No metas contenido a mano en WordPress.
9. **Idioma:** español de México en el sitio y en los documentos (la versión en inglés es una fase
   posterior; no la empieces).

---

## 5. Qué hay en vivo hoy (verificado 2026-10-10)

- **Portada** (Home v2, página 168, `page_on_front`): hero a sangre con foto `hero-home` (322, PNG
  2400×1600 sin intermedias; la CDN sirve WebP 2400/1200), overline con «lombriz», título
  «**Respirar** bien, / dormir bien, / oír ***bien***», botón de scroll redondo glass con flecha
  (`.di-scroll-cue`, solo escritorio); marquee morado; **áreas** (dos tarjetas con foto, `area-orl`
  329 y `area-sueno` 330, fotos ancladas a la derecha, cápsulas glass) bajo «Dos **especialidades**,
  una misma forma de ***atender***»; **motivos de consulta** (lista 01–08 con lombriz solo en la línea
  superior); **pasos** 01–04 (lombriz sobre cada paso); **sección de la Dra.** «Escucharte…» (ver
  sección 8); **sueño** «Del **ronquido** al descanso, en un solo ***lugar***» con ruta 01–04
  (lombriz solo encima del primero); **ubicación** «CAB Medical, / *Headquarters*» con mapa de Google
  (Maps JavaScript API, estilo propio de la paleta, pin morado, idioma `es`/región `MX`); CTA morado
  «¿Hablamos de lo que te está quitando el *descanso*?»; **pie** (logotipo blanco | tagline, cédulas
  y consejo apagados, redes en círculos, COFEPRIS: Aviso de Publicidad 2519012002A00712 y Aviso de
  Funcionamiento 2619015036A00445, fila legal con lombriz).
- **Páginas publicadas:** Dra. (169, cabecera warm con foto `hero-dra` 341 y encuadre
  `focus 65% 0%` / `focus_mobile 82% 50%`; José dice que **esa foto no es la definitiva**), Primera
  consulta, Preguntas frecuentes, Contacto (con formulario `di-form` antes de «Urgencias»), Gracias
  (335, `noindex`, fuera del sitemap), Aviso de privacidad (175), Aviso médico (176), Términos de uso
  (177), Links (143, `noindex`), 404 propia. **En borrador:** Otorrinolaringología (170) y Sueño (171)
  (pilares; se publican al aprobarse las primeras fichas), fichas clínicas y recursos.
- **Formulario:** probado de extremo a extremo (sin JS → 303 a `/gracias/`; con JS → JSON 200 y
  redirección; honeypot → ok silencioso; demasiado rápido → `di_error=fast`; origen ajeno →
  `di_error=origin`). Correo HTML (tabla de datos, `tel:`/`wa.me`, botones, pie) + texto plano; SMTP
  Hostinger 465/SSL desde `soy@drairinagonzalez.com`; Bcc a la copia del propietario. Confirmado por
  José que recibe las copias.
- **Seguridad/a11y:** cabeceras HSTS (1 año), nosniff, X-Frame-Options SAMEORIGIN, Referrer-Policy
  strict-origin-when-cross-origin, Permissions-Policy; `<main id="content">` único en plantillas de
  Elementor; skip link; teal fuerte `#2a787d` (4.9:1); área táctil 24 px en los puntos del carrusel.
- **SEO/schema:** Rank Math; sitemap sin `/gracias/` ni `/links/`; JSON-LD con `Physician` (imagen
  del hero, credenciales, hospitales, `sameAs`), `MedicalBusiness`, migas. Pendiente `FAQPage`
  (Q-018). Canónico sin `www` (D-022). Dominio antiguo `otorrino-monterrey.com` → 301 por ruta.
- **Medición:** GA4 preparado sin ID (`ga4_id` vacío → no se imprime nada); eventos
  `appointment_click`, `doctoralia_click`, `directions_click`, `contact_submit` en `/gracias/`.

---

## 6. Reglas de diseño dictadas por el propietario (vigentes)

Están en `DECISIONS.md` (D-046…D-054) y `DESIGN_SYSTEM.md`; resumen para que no las rompas:

1. **Títulos (D-049):** una palabra en color de acento en bold (`<b>`) y la **última** palabra en
   acento y cursiva (`<em>`), el resto en el color base; acento morado sobre fondos claros, turquesa
   sobre fotos/oscuros (`--title-accent`); **sin punto final** en ningún título; saltos de escritorio
   con `<br class="di-brd">` (ocultos en móvil). Reparto palabra a palabra fijado por José en los
   builds; no lo cambies.
2. **Separadores «lombriz»** (trazo del overline, estático) **solo** en: línea superior de motivos,
   fila de confianza del hero, sobre cada paso, encima del primer ítem de la ruta y fila legal del
   pie. Todo lo demás, línea fina de 1 px (FAQ, credenciales, trayectoria, índice, cabecera, panel de
   menú, barra móvil, tagline del pie). `.di-rule` y `--worm-*` quedan como utilidad.
3. **Botón de WhatsApp (D-053):** glass (teal translúcido + blur) sobre fotos y fondos oscuros; sólido
   teal fuerte sobre fondos claros. Botones glass sin borde.
4. **Hero de portada:** overline lombriz corta estática en el color del texto; sombra móvil suave desde
   la mitad del título; logotipo completo en la cabecera (no solo el isotipo); cabecera en modo
   blanco (`di-on-dark`) sobre heros con foto.
5. **Tarjetas de área:** foto sin degradado negro, etiquetas en cápsulas glass claras, foto anclada al
   borde derecho (`object-position: right center`).
6. **Sección de la Dra.:** ver sección 8 (reglas específicas).
7. **Mini apps:** siempre tema claro; sin correos de aviso.
8. **Correos del sitio:** HTML «bonitos, ordenados y amistosos» con identidad del consultorio y texto
   plano alternativo.
9. **No añadir sombras ni tocar el menú** cuando se ajusta una foto (indicación literal de José).

---

## 7. Problemas conocidos y sospechas para auditar

Confirmados por la sesión local (corrígelos o justifica por qué no):

1. `templates/parts/home/hero.php`: el `<img>` del hero sale con `fetchpriority="high"` y `loading`
   **duplicados** (dos atributos iguales en la misma etiqueta). Revisa `inc/performance.php`
   (`irina_hero_image` / `irina_image_original`).
2. El retrato recortado de la sección de la Dra. (`dra-escucharte`, 334, PNG RGBA 1024×2170, 2.5 MB)
   es pesado: en móvil tarda en aparecer (la sección se pinta sin él un instante). Valorar WebP con
   alfa en origen o `fetchpriority`/precarga al acercarse. Hoy el tema sirve `dra-escucharte-966x2048`
   / `725x1536` según `sizes`, y la CDN reescala al ancho del viewport.
3. `assets/js/map.js` usa `google.maps.Marker` (aviso de obsoleto en consola). Migrar a
   `AdvancedMarkerElement` exige `mapId`; decide con José o deja documentado.
4. La **variante `light`** de la cabecera a sangre (`Bleed.php` opción `light`, CSS `.di-bleed--light`,
   rama en `main.js`) quedó **sin uso** tras revertirla José. Evalúa si se conserva como opción
   documentada o se retira (pregunta al propietario si dudas; no la reactives).
5. `STATUS.md` (panel del 2026-10-08, 31 %, «4 pendientes del propietario» ya cerrados) y `BATON.md`
   (2026-10-07) están **desactualizados** respecto a `NEXT.md`, `CHANGELOG.md` y el sitio. Actualízalos
   con evidencia (tareas DONE = 1) según `PLAN.md`. No hay `AGENTS.md` ni `PROGRESS.md` en la raíz
   (el método del propietario los espera; `STATUS.md` hace de panel): crea `PROGRESS.md` solo si
   `PLAN.md` da base para contarlo; si no, deja `PROGRESS BASELINE REQUIRED` y pregunta.
6. `QA.md`: Q-018 (`FAQPage` ausente en `/preguntas-frecuentes/`), Q-021 (restricción de la clave de
   Maps: José la restringió en Google Cloud; comprueba que el sitio no la expone fuera del `<script>`
   de Google y que la REST no la devuelve). Revisa que todas las Q-0xx tengan estado real.
7. Los builds de Python escriben JSON «compacto en una línea»; los diffs son ilegibles. Valorar
   `indent` estable (sin cambiar el contenido cargado) para revisar cambios.
8. En `components.css` conviven reglas móviles de la sección de la Dra. añadidas en muchas iteraciones
   (ver sección 8): limpiar, ordenar y comentar, sin cambiar el resultado aprobado salvo lo que se
   pide en la sección 8.
9. `wp-config.php` del servidor tiene una línea en blanco extra antes de «That's all» (inocua) y las
   constantes `DI_SMTP_*`; no está en el repo. No lo toques; documenta en `docs/DEPLOY.md` cómo se
   configura el SMTP sin exponer valores.
10. `upload-media.sh` ahora nombra `<slug>-v<AAAAMMDDHHMM>.<ext>` para que la URL cambie; comprueba
    que ningún script o widget dependa del nombre de archivo (deben resolver por **slug** del adjunto).
11. Comprueba la coherencia de nombres y datos en todo el sitio: «CAB Medical Headquarters» (nombre
    comercial del consultorio; los legales no citan ninguna razón social y no debes introducir una), dirección
    completa (Av. Paseo de los Leones 2341, Consultorio 6, Piso 2, Cumbres 2.º Sector, 64610
    Monterrey, N. L.), teléfonos, correos, horario «Previa cita. Agenda por WhatsApp», hospitales
    («Atiende en Christus Muguerza, Zambrano Hellion, Ángeles Valle Oriente, Hospital Universitario y
    Hospitaria»), cédulas 12438166 · 15111342, siglas UNEFM/UCLA con `<abbr>`; todo debe salir de
    `PracticeSettings` o de los builds, nunca duplicado a mano en plantillas.
12. `tools/preview/*.html` (maquetas de la Fase 4) y `tools/legacy/`: verifica si siguen siendo
    referencia o ya son ruido; propón archivarlos (no los borres sin preguntar).
13. Scripts bash: `set -euo pipefail` en `lib.sh`; trampas conocidas en Git Bash de Windows
    (`head -c` tras `tr`/`curl` rompe por SIGPIPE; usar `od`/`cut`; barras invertidas en heredocs).
    Revisa idempotencia de `setup-*.sh` (`--post_name__in` en vez de `--name` para encontrar
    borradores), y que `setup-site.sh` no vuelva a marcar la portada como `noindex` (ya pasó).

---

## 8. La sección «Escucharte también es parte del tratamiento» (prioridad visual)

### Qué es

Sección de la portada (`templates/parts/home/doctora.php`, widget `Rendering\Elementor\Doctora`,
datos en `build-home.py` → `home-elementor.json`, CSS en `components.css` líneas ~241–316 y bloque
móvil ~295–312, JS del carrusel en `assets/js/main.js`). Composición: fondo fotográfico
`dra-escucharte-bg` (332, crema con tulipanes, 2400×1600), retrato recortado de la Dra. con alfa
`dra-escucharte` (334, 1024×2170), columna de texto con overline «Dra. Irina González Sáez», cita
«**Escucharte** también es parte del ***tratamiento***», párrafo de formación (UNEFM, UCLA Venezuela,
ISSSTE Monterrey), credenciales (3 ítems) como **carrusel de una tarjeta glass** con puntos y anillo
de progreso de 4 s (también en escritorio, tarjeta al 50 % de la columna), y botón «Conocer a la Dra.»
(corto en móvil).

### Reglas del propietario para esta sección (no cambiar sin preguntar)

- Escritorio (≥ 900 px): retrato a la izquierda, texto a la derecha, fondo de tulipanes completo;
  **se considera correcto** (captura `escucharte-1440-20261010-0806.png`).
- Móvil: retrato **a todo el alto de la columna de texto**, con silueta natural a **16 px** de la
  columna, anclado abajo, **sin desplazamientos** (`translateX`), recortado solo por el borde derecho
  del viewport; se acepta que «la cara se esconda un poco», pero no que desaparezca; sin franja
  clara entre retrato y borde; fondo de tulipanes a todo el ancho anclado abajo y **un poco
  escondido por la izquierda** (`object-position: -8vw bottom`); credenciales de una en una con
  puntos alineados con el texto y anillo de progreso; párrafo corto con siglas; **sin lista de
  credenciales completa**; botón de una línea; no agrandar demasiado el alto de la sección.
- Carrusel: deslizable con el dedo, transición sutil y rápida (220 ms), pausa 8 s al tocar,
  respeta `prefers-reduced-motion`.

### Historial (para que no repitas lo descartado)

Iteraciones del 2026-10-09/10, todas en `CHANGELOG.md`: retrato con `translateX` 46 %/50 % (rostro
fuera en 360 px → descartado), retrato por ancho con `calc(100% + 64px)` (José: «mucho espacio
arriba» → descartado), retrato a todo el alto desplazado −20 % (rechazado por José con captura de
referencia), **versión vigente**: `left: calc(56% + 16px); right: -gutter; top:0; bottom:0` e
`img { left:0; bottom:0; height:100%; width:auto }`; fondo `cover` desde la izquierda; carrusel
con anillo (antes aplastado por `img, svg { max-width:100% }` de `base.css`, ya corregido con
`max-width:none`); tarjeta glass; credenciales ocultas → descartado (ahora carrusel).

### Estado medido en vivo (2026-10-10 08:06, HEAD a041be6)

| ancho | alto sección | columna texto (l–r) | retrato img (l–r, w×h) | visible del retrato | cita (font-size) | tarjeta creds (w) |
|---|---|---|---|---|---|---|
| 360 | 851 | 16–200 | 216–572, 356×755 | 144 px (40 %) | 28.8 px, 4 líneas | 184 |
| 390 | 829 | 16–216 | 232–578, 346×733 | 158 px (46 %) | 28.8 px, 4 líneas | 200 |
| 430 | 756 | 17–239 | 255–566, 311×660 | 175 px (56 %) | 28.8 px | 222 |
| 1440 | 849 | 681–1372 | 273–617, 344×729 | 100 % | 57.6 px | 565 (50 %) |

Capturas: `/preview/qa/escucharte-{360,390,430,1440}-20261010-0806.png`.

### Qué sigue mal (lo que José ve en su teléfono, ≈ 360–390 px CSS)

1. **Solo se ve media cara**: el retrato arranca a 216–232 px y su rostro cae en el borde derecho
   (ojo derecho sobre el borde); en 360 solo queda visible el 40 % del retrato (brazo y mitad del
   rostro). El propietario aceptó «que se esconda un poco», no la mitad.
2. **Columna de texto estrecha** (56 % = 184–200 px): la cita se parte en 4 líneas, el párrafo en 9,
   y la **tarjeta de credencial** queda de 5 líneas (muy alta), con lo que la sección mide 830–850 px.
3. **Fondo de tulipanes detrás del texto** (tulipán rosa bajo la tarjeta y el botón): legible gracias
   a la tarjeta glass, pero la composición se ve cargada; José pidió «esconder un poco los tulipanes
   a la izquierda», no quitarlos.
4. **Equilibrio general**: en el teléfono real la sección no se ve «bien»: demasiada altura, retrato
   cortado, texto apretado. José no ha dictado una solución; quiere que se vea bien respetando las
   reglas de arriba.

### Lo que te pido

- Diagnostica con el harness (360, 390, 414, 430, 483 y 1440; alto suficiente para capturar la
  sección entera) y **propón 2 variantes** que cumplan las reglas, con capturas lado a lado y
  medidas (gap, % de rostro visible, alto de sección, líneas de la cita y del párrafo, alto de la
  tarjeta). Ideas que caben dentro de las reglas: columna de texto 60–62 % y retrato dimensionado
  para que el **rostro completo** quede dentro (por ejemplo, limitar el alto del retrato a
  `min(100%, 150vw)` o dimensionar por ancho con tope, y anclarlo abajo); cita y párrafo con
  `clamp()` en móvil; tarjeta de credencial con `min-height` fija y tipografía 0.86 rem; fondo con
  `object-position` ajustado o un velo crema muy suave bajo la columna (sin sombras); ordenar las
  reglas CSS móviles en un solo bloque comentado. **No** toques el menú, **no** añadas sombras, **no**
  quites el carrusel ni el anillo, **no** cambies textos.
- Implementa la variante que mejor cumpla, deja la otra documentada con captura, y plantea la
  elección a José en una pregunta corta (opciones, pros/contras, recomendación).
- Verifica además: rostro visible ≥ 80 % de su ancho en 360 px; gap retrato-texto = 16 px; sin
  franja a la derecha; `img.bottom = sección.bottom`; carrusel 1 ítem, 3 puntos, anillo animando;
  botón en una línea; consola sin errores; sin `!important` nuevos; contraste AA del texto sobre el
  fondo.

---

## 9. Alcance de la auditoría (qué revisar y cómo reportar)

Revisa, en este orden, y anota cada hallazgo con **severidad** (P0 bloquea, P1 importante, P2 mejora),
**evidencia** (archivo:línea o URL + medida/captura) y **acción** (arreglado en commit X / propuesta /
pregunta al propietario):

1. **Corrección PHP** (plugin y tema): errores, avisos, tipos, escapado, nonces donde aplique (el
   formulario no usa nonce a propósito por la caché; mantenlo), `wp_kses` coherente, hooks duplicados,
   `strict_types`, PHPCS limpio.
2. **Seguridad:** REST (`dra-irina/v1/*`, qué expone; `maps_api_key` nunca), admin-post, mini apps
   (`guardar.php`, `revisar.php`: token `hash_equals`, rutas, permisos del usuario `irina`), cabeceras,
   `.htaccess` (bloque «DraIrina parked»), `uninstall.php`, subida de medios.
3. **Accesibilidad:** landmarks, orden de encabezados (un `h1` por página), foco, contraste, controles
   del carrusel y del mapa, `aria-live`, reduced motion, formulario (labels, errores, `aria-invalid`).
4. **Rendimiento:** CSS/JS enqueued (versión por `filemtime`), imágenes (srcset vs original, PNG
   pesados, `fetchpriority`, lazy), fuentes Adobe (Iskra, kit `nlo5pss`), LiteSpeed, consola limpia.
5. **SEO/schema:** títulos y descripciones (Rank Math + `pages/seo.json`), `noindex` correctos,
   sitemap, JSON-LD válido (Physician, MedicalBusiness, BreadcrumbList; añadir `FAQPage`),
   canónicos, 404, redirecciones del dominio antiguo.
6. **CSS:** duplicados, reglas muertas (variante light, utilidades sin uso), especificidad y orden
   (ya hubo un bug por reglas base después del media query), tokens, nombres `di-*`, comentarios
   que citan decisiones.
7. **JS:** `main.js` (cabecera, reveal, carrusel, formulario con `fetch(form.getAttribute('action'))`,
   scroll-cue), `map.js`; errores silenciosos, listeners duplicados, reduced motion.
8. **Builds y scripts:** Python (`build-*.py`) y bash (`tools/deploy/*.sh`): idempotencia,
   trampas de Git Bash/Windows, mensajes, respaldos, que no haya rutas o IDs hardcodeados que ya
   cambiaron (IDs de páginas y adjuntos se resuelven por slug).
9. **Documentación:** coherencia entre `STATUS`, `NEXT`, `BATON`, `DECISIONS`, `QA`, `CHANGELOG`,
   `DESIGN_SYSTEM`, `ARCHITECTURE`, `PLUGINS`, `docs/DEPLOY`; fechas; decisiones sin registrar
   (busca en `CHANGELOG.md` cambios «por decisión del propietario» que no tengan D-0xx).
10. **Visual:** recorre la portada y las páginas publicadas en 360/390/430/768/1024/1440: solapes,
    desbordes de texto, botones del kit de Elementor pisando `.di-btn` (ya pasó dos veces),
    alineaciones, aire entre secciones, lombriz solo donde toca, títulos según D-049 y sin punto.

### Entregables

1. Commits pequeños en la rama, mensaje en español, con el porqué; nada de «fix».
2. `QA.md`: tabla de hallazgos (Q-0xx nuevos) con severidad, evidencia, estado.
3. `CHANGELOG.md`, `STATUS.md` (panel actualizado con evidencia), `NEXT.md` (NOW/AFTER), `DECISIONS.md`
   (si registras decisiones ya tomadas por José que faltaban) y `BATON.md` (qué hiciste, qué debe
   desplegar la sesión local y en qué orden: `deploy-code.sh` → `setup-site.sh`/`setup-pages.sh`/
   `setup-dra.sh` si cambiaron JSON → purga → qué medir).
4. Un bloque final **PREGUNTAS AL PROPIETARIO** con solo lo que es suyo (cada una: pregunta, opciones,
   pros/contras, recomendación, impacto, reversibilidad).
5. Un bloque **MENSAJE PARA EL OTRO AGENTE** (sesión local) con SHA, pasos de despliegue y qué
   verificar.

### Cómo verificar

- PHP: `php -l` en cada archivo tocado; `composer install && vendor/bin/phpcs` según
  `phpcs.xml.dist`.
- Builds: `python tools/content/build-home.py`, `build-pages.py`, `build-dra.py` → JSON regenerados y
  commiteados; `git diff` sin cambios inesperados.
- Harness visual: Chrome headless (o Playwright) en 360×800, 390×844, 430×932, 768×1024,
  1024×768, 1440×900; si no puedes acceder al sitio en vivo desde tu entorno, monta el HTML con los
  assets del repo y dilo; la sesión local repetirá las medidas en producción.
- No puedes probar el formulario ni el SMTP en vivo (envía correos reales): revisa el código y deja
  los casos de prueba escritos; la sesión local los ejecuta.

---

## 10. Formato de respuesta esperado

1. **Diagnóstico de normalización** breve (estado real inspeccionado, metodología que ya hay, qué se
   conserva, qué falta, riesgos).
2. **Tabla de hallazgos** (P0/P1/P2) con evidencia.
3. **Lo corregido** (lista de commits con SHA y una línea cada uno).
4. **Propuestas** (no aplicadas) con captura o medida.
5. **Sección «Escucharte»**: variantes A/B con capturas y medidas; cuál implementaste y por qué.
6. **PREGUNTAS AL PROPIETARIO**.
7. **MENSAJE PARA EL OTRO AGENTE**.

Trabaja hasta terminar el alcance; si algo te bloquea (acceso, decisión), termina todo lo demás y
deja el bloqueo explícito.
