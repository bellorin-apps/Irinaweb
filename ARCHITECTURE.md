# ARCHITECTURE — WordPress híbrido / custom

Versión 0.1 · 2026-10-07 · Estado: REVIEW (Codex). Cumple MASTER_PROMPT §95–§104, §28–§31, §40, §72, §98–§104.

## 1. Capas

```
WordPress (CMS, usuarios, medios, REST)                    ← Hostinger · LiteSpeed · PHP 8.3
├── hello-elementor (parent)                               ← sin modificar
├── irina-gonzalez (child theme)                           ← PRESENTACIÓN
│   style.css · functions.php · inc/ · assets/ · templates/
├── dra-irina-core (plugin)                                ← FUNCIONALIDAD Y DATOS
│   CPT · taxonomías · campos · settings · schema · workflow médico · shortcodes/bloques · tracking
├── Elementor + Elementor Pro                              ← COMPOSICIÓN EDITORIAL (Home, pilares, páginas estáticas)
└── Plugins externos mínimos                               ← ver PLUGINS.md
```

Regla de reparto: si desaparece el tema, el dato sigue existiendo (vive en el plugin). Si desaparece Elementor, las páginas de condición y tratamiento siguen renderizando (templates PHP del child theme leen los datos del plugin). Elementor compone Home, pilares y páginas estáticas con widgets propios que solo muestran datos del core (nunca los duplican).

## 2. Child theme `irina-gonzalez`

```
irina-gonzalez/
├── style.css                 cabecera del tema; importa nada (los CSS se encolan desde inc/enqueue.php)
├── functions.php             solo require de inc/*
├── screenshot.png
├── theme.json                tokens expuestos a Gutenberg/Elementor Global Styles (colores, tipografía)
├── inc/
│   ├── setup.php             add_theme_support, menús, tamaños de imagen, hooks de Hello (hello_elementor_* filters)
│   ├── enqueue.php           CSS/JS versionados por hash; preload de fuentes críticas; defer; sin jQuery en front
│   ├── performance.php       desactivar emojis/embeds/dashicons en front; limpiar head; lazy-load selectivo (nunca LCP)
│   ├── accessibility.php     skip link (Hello lo trae), focus visible, aria en menús, reduced-motion
│   ├── elementor.php         registro de categoría de widgets, desactivar iconos/kits innecesarios, Theme Locations
│   └── template-tags.php     helpers de presentación (no lógica de datos)
├── assets/
│   ├── css/  tokens.css · base.css · components/*.css · sections/*.css · sleep.css · print.css
│   ├── js/   main.js (módulos ES: reveal via IntersectionObserver, mobile bar, symptom navigator UI)
│   ├── fonts/ Iskra (WOFF2, subset latin + latin-ext; sujeto a licencia) · fallback del sistema
│   ├── icons/ Lucide como SVG inline (sprite generado en build; solo los iconos usados)
│   └── images/ OG template, patrones de la sección Sueño (SVG ligeros)
└── templates/
    ├── single-condicion.php · single-tratamiento.php · single-recurso.php
    ├── archive-condicion.php (hub padecimientos) · archive-tratamiento.php (hub tratamientos)
    ├── page-dra-irina.php (template de entidad) · page-contacto.php · 404.php · search.php
    └── parts/ hero, author-box, medical-review-badge, sources, faq, related, cta-whatsapp, location, mobile-bar
```

Hello Elementor se respeta mediante sus filtros documentados (`hello_elementor_enqueue_theme_style`, `hello_elementor_register_elementor_locations`, `hello_elementor_add_theme_support`, skip link). No se copian archivos del parent.

## 3. Plugin `dra-irina-core`

```
dra-irina-core/
├── dra-irina-core.php         bootstrap, constantes, autoload PSR-4 (namespace DraIrina\Core)
├── src/
│   ├── PostTypes/   Condicion.php · Tratamiento.php · Recurso.php · Credencial.php
│   ├── Taxonomies/  Area.php (orl | sueno) · Zona.php (oido | nariz | garganta | cuello | sueno) · EstadoMedico.php
│   ├── Fields/      MetaRegistry.php (register_post_meta con schema REST) · MetaBoxes.php (UI admin vanilla JS)
│   ├── Settings/    PracticeSettings.php (fuente única de verdad: NAP, horario, WhatsApp, redes, enlaces)
│   ├── Schema/      Graph.php · Physician.php · MedicalCondition.php · MedicalProcedure.php · MedicalWebPage.php · Breadcrumbs.php · Faq.php
│   ├── Workflow/    MedicalReview.php (estados, capacidades, bloqueo de publicación sin aprobación médica)
│   ├── Rendering/   Shortcodes.php · ElementorWidgets/ (CTA WhatsApp, Credenciales, Ubicación, Condiciones destacadas, Author box)
│   ├── Tracking/    Events.php (data-attributes para GA4: appointment_click, phone_click, directions_click, doctoralia_click, contact_submit)
│   └── Integrations/ Doctoralia.php (widget oficial) · Forms.php (hook de envío → página de gracias)
├── assets/admin/   css/js de los meta boxes
└── uninstall.php   no borra contenido (solo opciones transitorias)
```

## 4. Content model (datos estructurados)

### 4.1 `condicion` (padecimiento) — URL `/padecimientos/{slug}/` o `/sueno/{slug}/` según `area`

| Campo | Tipo | Nota |
|---|---|---|
| Título, extracto, imagen destacada | core | H1 = título; extracto = meta description por defecto |
| `resumen_paciente` | texto corto | "Qué es" en una frase |
| `sintomas` | lista (JSON array de strings) | Se renderiza como lista y alimenta `MedicalCondition.signOrSymptom` |
| `causas` | lista | |
| `cuando_consultar` | lista | Módulo "¿Cuándo debería consultar?" |
| `diagnostico` | texto enriquecido | Cómo se evalúa en consulta |
| `tratamientos_relacionados` | relación → `tratamiento` | |
| `enfoque_dra` | texto enriquecido | Solo con contenido aprobado por la Dra. |
| `faq` | repetidor {pregunta, respuesta} | Solo preguntas reales; `FAQPage` solo si la Dra. aprueba |
| `fuentes` | repetidor {titulo, autor/organismo, año, url, tipo} | Bibliografía verificable |
| `revisor` | relación → usuario (rol médico) | Por defecto la Dra. |
| `fecha_revision_medica` | fecha | "Última revisión médica" |
| `proxima_revision` | fecha | Recordatorio en admin |
| `estado_medico` | taxonomía | draft → technical_review → medical_review_required → medically_approved → ready → published |
| `oferta` | enum: ofrece / refiere / no | Si `no`, la página no se publica; si `refiere`, el CTA cambia a "Orientación" |
| `relacionados` | relación → `condicion` | Enlazado contextual |

### 4.2 `tratamiento` — URL `/tratamientos/{slug}/` o `/sueno/{slug}/`

Igual que `condicion` más: `que_resuelve` (relación → `condicion`), `candidatos` (lista), `como_se_realiza` (texto), `recuperacion` (texto), `riesgos_y_alternativas` (texto, obligatorio para cirugía), `estudio_previo` (relación → `tratamiento` de tipo estudio o texto), `tipo` (consulta | procedimiento en consultorio | cirugía | estudio | terapia).

### 4.3 `credencial`

`tipo` (formación | especialidad | certificación | membresía | publicación | curso | experiencia | conferencia), `titulo`, `institucion`, `lugar`, `anio`, `anio_fin`, `url` (DOI o institución), `verificado` (bool, solo lo marca un administrador tras ver el documento), `mostrar` (bool), `orden`. Se renderiza en la página de la Dra. y alimenta `Physician.hasCredential`, `alumniOf`, `memberOf`.

### 4.4 `recurso` (artículo para pacientes) — URL `/recursos/{slug}/`

Contenido en bloques nativos (no Elementor) para portabilidad; mismos campos de autoría, revisión, fuentes y estado médico. `BlogPosting` + `MedicalWebPage`.

### 4.5 Settings (`PracticeSettings`) — fuente única de verdad

Página de ajustes "Consultorio" en el admin: nombre profesional, especialidad, nombre del centro, dirección (calle, número, interior, colonia, CP, ciudad, estado), geo, enlace de Maps, teléfono consultorio, WhatsApp (número + mensaje por defecto), correo, horario por día, redes, URL Doctoralia, URL GBP, cédulas (con casilla "publicar"), responsable de datos personales. Expuesto por `dra_irina_practice()` (PHP), shortcodes `[di_phone]`, `[di_whatsapp]`, `[di_address]`, widgets de Elementor y el grafo de schema. Nada de esto se escribe a mano en Elementor.

### 4.6 Workflow médico

- Las capacidades `publish_condicion` y `publish_tratamiento` solo se conceden si `estado_medico = medically_approved` o superior; el intento de publicar sin aprobación devuelve un aviso en el editor.
- `medically_approved` solo lo puede asignar un usuario con rol `revisor_medico` (la Dra.). Los administradores técnicos no pueden autoasignarlo.
- Registro de quién y cuándo aprobó (post meta + nota en revisiones).

## 5. Schema map

Un solo emisor de JSON-LD. Rank Math emite `WebSite`, `WebPage`, `BreadcrumbList` y `Organization` básico; el plugin emite todo lo médico y desactiva en Rank Math los tipos que duplicaría (Person/LocalBusiness por página).

| Página | Tipos | Fuente de datos |
|---|---|---|
| Home | `Physician` (@id principal; subtipo de MedicalBusiness) con `name`, `image`, `url`, `telephone`, `address`, `geo`, `openingHoursSpecification`, `medicalSpecialty: Otolaryngologic`, `sameAs` (Doctoralia, GBP, redes), `availableService` (solo OFRECE) | PracticeSettings + credenciales |
| Dra. Irina | `ProfilePage` → `mainEntity: Physician` (mismo @id) + `alumniOf`, `hasCredential`, `memberOf`, `knowsAbout` | Credenciales verificadas |
| Pilares | `MedicalWebPage` + `about: MedicalSpecialty` | Página |
| Condición | `MedicalWebPage` + `about: MedicalCondition` (`signOrSymptom`, `possibleTreatment` → tratamientos), `reviewedBy: Physician`, `lastReviewed`, `citation` | CPT |
| Tratamiento | `MedicalWebPage` + `about: MedicalProcedure` / `MedicalTest` / `MedicalTherapy` (`procedureType`, `howPerformed`, `preparation`, `followup`) | CPT |
| Recurso | `BlogPosting` + `MedicalWebPage` (`author`, `reviewedBy`, `datePublished`, `dateModified`, `citation`) | CPT |
| Contacto | `ContactPage` + referencia al `Physician` | Settings |
| FAQ (solo real) | `FAQPage` únicamente si la Dra. aprobó las respuestas | CPT/página |

No se emiten `aggregateRating` ni `review` propios (las opiniones viven en Doctoralia).

## 6. Elementor: límites

- Se usa en: Home, pilares, Dra. Irina (composición alrededor de datos del core), primera consulta, contacto, legales.
- No se usa en: single de condición, tratamiento y recurso (templates PHP). Así el contenido médico no depende del builder.
- Global Styles de Elementor se alimentan de `theme.json`/tokens; prohibido CSS por widget salvo excepción documentada.
- Widgets propios (categoría "Dra. Irina"): CTA WhatsApp, Credenciales, Ubicación y horario, Condiciones destacadas, Author/Review box, Navegador de síntomas. Sin addons de terceros.
- Theme Builder solo para header y footer si aporta edición visual; en caso contrario, header/footer en PHP del child theme (decisión en Fase 6 según prueba).

## 7. Performance budget (objetivo inicial, móvil)

| Recurso | Presupuesto |
|---|---|
| HTML | ≤ 60 KB |
| CSS total | ≤ 90 KB (Elementor incluido; se desactivan módulos no usados) |
| JS propio | ≤ 30 KB; JS total ≤ 150 KB |
| Fuentes | ≤ 2 familias, ≤ 4 archivos WOFF2, ≤ 120 KB |
| Imagen LCP | ≤ 150 KB, AVIF/WebP, `fetchpriority=high`, sin lazy |
| Peticiones | ≤ 35 en Home |
| Terceros | GA4 solo; Doctoralia widget diferido tras interacción o al entrar en viewport |

## 8. Seguridad (resumen; detalle en SECURITY.md)

2FA para administradores; mínimo de usuarios admin; `DISALLOW_FILE_EDIT`; XML-RPC desactivado salvo necesidad; REST pública solo para lo necesario (los CPT médicos sí exponen REST para el editor, con lectura pública limitada a publicados); cabeceras de seguridad desde el servidor; backups del hosting verificados; nonces y capacidades en todo formulario de admin; escaping en toda salida.

## 9. Entorno de desarrollo y despliegue

- Código en este repositorio bajo `wp-content/themes/irina-gonzalez` y `wp-content/plugins/dra-irina-core`.
- Linters: PHPCS con WordPress Coding Standards, stylelint, eslint. Build de assets con esbuild/lightningcss (mínimo).
- Despliegue: Hostinger Git deploy o rsync desde sesión local; nunca editar en producción.
- Staging en Hostinger, protegido y `noindex`.

## 10. Desviaciones respecto al MASTER_PROMPT

Ninguna. Decisiones técnicas propias registradas en `DECISIONS.md` (D-013 URLs, D-014 campos nativos sin ACF Pro, D-015 emisor único de schema).
