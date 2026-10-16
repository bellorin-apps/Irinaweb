# Arquitectura vigente · auditoría 2026-10-14b

D-076…085 sustituyen el alcance proyectado: WordPress + Hello/child PHP y plugin dra-irina-core; Elementor compone páginas, singles médicos usan templates PHP. CSS tokens/base/components/sleep/print y main.js IIFE, sin build esbuild entregado. Header/footer del tema. Scripts de despliegue de Claude local con respaldo/rollback; sin staging D-080.

Publicadas diez páginas indexables y cinco CPT médicos bajo /sueno/{slug}/. Links/Gracias noindex; archivos /padecimientos/, /tratamientos/, /recursos/ 404. Cuatro CPT registrados (condicion, tratamiento, recurso, credencial) siguen existiendo; 21 fichas clínicas restantes en borrador, sin borrarlos. Widgets di-* siguen registrados para compatibilidad y revisión; no eliminar legacy por esta auditoría. Mapa definitivo en SITEMAP.

Schema: Graph Physician+Person (Q-058), ProfilePage, ContactPage, MedicalWebPage en ORL/Sueño y cinco fichas con entidad apropiada. Rank Math WebSite/WebPage/BreadcrumbList; FAQPage solo FAQ. No ratings/testimonios propios. Un nodo profesional por página donde corresponde, alumniOf válido por Person. Validación schema.org 0/0 aportada por Claude, no repetida como certificación clínica.

REST /practice GET proyecta datos públicos; POST y Ops requieren manage_options. Metas edit_post, gate médico y nonce en administración. Briefing con token fuera de Git, rol médico y log; nunca publica. Cloud sin contraseña de aplicación tras revocación; operaciones privilegiadas solo local SSH/WP-CLI. Formularios sin nonce por caché, con validación/antiabuso/Turnstile; no probar SMTP en producción. Q-040 consumidores/allowlist Ops sigue abierto. Q-042 VERIFIED en auditoría fase5: PracticeSettings runtime y practice.py en builds, con cotejo nap-check; no sincronización automática.

Fotografía: originales q100 conservados; derivados escritorio q95 cabeceras/fondo (D-081), móvil 1600 q95, alfa lossless. Adobe Fonts intactas por decisión; presupuestos originales son objetivos, no resultados cumplidos. PERFORMANCE acredita medición, no CWV de campo. 2FA WP activo según atestación local; editor autenticado no probado por Codex. Reporte de versiones local, sin inferir desde cabeceras ocultas.

La declaración histórica «ninguna desviación» de abajo ya no aplica: decisiones del propietario reescopan enciclopedia, staging, fotografía y menús. Actualización fase5: no se construye UI de repetidores clínicos en wp-admin; el flujo vigente es builds + app de revisión (D-040/D-076). NAP centralizado por ámbito y cotejado; tooling PHP/ESLint/stylelint operativo (docs/REVISION_FASE5_2026-10-16.md).

---

## Historial y propuestas anteriores (no definen el alcance actual)

## Implementación y mapa vigentes · auditoría2026-10-12
Esta sección prevalece sobre los árboles/especificaciones v0.1 de abajo. Tema: `inc/setup.php`, `enqueue.php`, `performance.php`, `template-tags.php`, `elementor.php`, CSS `tokens/base/components/sleep/print`, main.js IIFE sin módulos/build ES, assets/icons/lucide.svg y marcas, templates/parts; header/footerPHP del child. Versiones de assets por filemtime, Adobe Fonts kitnlo5pss (no fuentes locales entregadas). Core: cuatro CPT y tres taxonomías, MetaRegistry, PracticeSettings, Workflow/MedicalReview, Schema/Graph, widgets de Rendering/Elementor, Contact/Form, Tracking/Events, Media/WebpUpload y Ops. PHP8.4 según informe local; no se certifica por una cabecera web oculta.
El modelo real usa prefijo `di_`: revisor es `di_revisor_id`; fuentes/FAQ son arrays con schemaREST; `di_estudio_previo` es texto, no relación. Condicion/tratamiento comparten oferta/enfoque_dra/relacionados; recurso tiene revisión/fuentes/FAQ y contenido core. Credencial usa título y menu_order del core; no existe meta `di_orden`. Riesgos de cirugía no son validación de obligatoriedad implementada. UI de repetidores pendiente5.2, no entregada. REST de edición protegido por capacidades y workflow; validación clínica queda en revisión médica.
PracticeSettings es fuente del schema/contacto por defecto; hay textos NAP duplicados en builds (Q-042), así que fuente única completa sigue pendiente5.3. Desinstalación conserva contenido. No existe el árbol hipotético de clases separadas Physician/MedicalProcedure del v0.1: Graph concentra el emisor.
Mapa observado en HTML público: Home WebSite/ImageObject/WebPage (RankMath) y Physician (core); Conóceme BreadcrumbList+Physician+ProfilePage; Contacto BreadcrumbList+Physician+ContactPage; FAQ BreadcrumbList+FAQPage (9 preguntas); Primera consulta, legales, Links y Gracias solo BreadcrumbList en la muestra; Recursos WebSite/BreadcrumbList/CollectionPage, noindex. No hay FAQPage en Primera consulta ni aggregateRating propio. Physician es un solo nodo por página; el horario oculto no sale en schema ni REST.
Desde Graph, sin publicarlos: CPT médicos MedicalWebPage con MedicalCondition o MedicalProcedure/MedicalTest/MedicalTherapy; recurso añade BlogPosting. ProfilePage requiere datos profesionales configurados. Pilares todavía borradores y no emiten un MedicalWebPage propio en Graph: el mapa v0.1 es objetivo futuro. hasCredential/alumniOf/memberOf proceden de credenciales mostrar=true; verificado no es filtro en Graph actual, así que esa propiedad no certifica por sí sola el documento.
El mapa es DONE como documentación contrastada, no acredita tareas5.2/5.3/5.4 ni aprobación médica. Rendimiento/linters de v0.1 son presupuestos y tooling pendientes, no inventario de herramientas ya activas.

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

- Administradores/editores tienen capacidades de publicar CPT, pero `wp_insert_post_data` convierte publish/future en pending sin estado médico aprobado. La taxonomía exige approve_medical_content al asignar por REST/editor; un editor técnico que modifica texto/meta aprobado fuerza nueva revisión. La revisora conserva su autoridad. Ver Workflow\MedicalReview y Q-024; pruebas completas en WP pendientes.
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

## Estado verificado 2026-10-10

Contacto: Contact\Form con admin-post, sin almacenamiento de mensajes ni nonce por caché, antiabuso y SMTP por constantes privadas. Tracking\Events carga GA4 solo con ID válido; ID vacío en producción según encargo. Graph emite FAQPage solo en FAQ desde widgets almacenados; Physician es el nodo observado en Home, sin segundo MedicalBusiness explícito. Los widgets leen ajustes por defecto/datos de builds; repeticiones NAP aún pendientes Q-042. Meta boxes de repetidores no están implementadas: no tratarlas como entregadas.
