# Veredictos de inventario · Codex · 2026-10-13

Fuente: `inventario-produccion.md`, snapshot WP-CLI de Claude local de las10:35. Código/documentos consultados en f5c1571. La actualización de rama quedó verificada con fetch y merge --ff-only del ref explícito tras un pull que falló por múltiples referencias de FETCH_HEAD. No se modificó la configuración Git.

| Tarea | Veredicto | Evidencia y límite |
|---|---|---|
| 1.2 | REVIEW | Plugins/temas y13 páginas identificados; menús y recuentos CPT/medios actuales. Falta lista individual de44 adjuntos (ID, archivo público/slug y tipo) y clasificación/destino verificables. Un recuento no satisface inventario completo por ítem. Se añade clasificación de lo identificado abajo, sin inferir qué medios existen ni borrar nada. |
| 1.5 | DONE | WPLANG/locale es_MX; America/Monterrey UTC−6; fecha con literales escapados (`j \d\e F \d\e Y`), H:i, semana lunes, permalinks /%postname%/. Astra/Site Kit ausentes; child irina-gonzalez activo y Hello padre. DISALLOW_FILE_EDIT y FORCE_SSL_ADMIN true; WP_DEBUG false; core minor. Los datos antes inaccesibles públicamente constan ahora en informe local seguro. |
| 3.6 | DONE | Seis plugins activos y dos MU enumerados con versión. Se contrasta/actualiza PLUGINS.md: stack real separado de propuestas; Safe SVG, Two-Factor y Query Monitor no aparecen instalados. No se afirma 2FA activa por esta tabla. |

## Clasificación y destino de elementos identificados

| Elementos | Clasificación | Destino |
|---|---|---|
| dra-irina-core0.4.5 | KEEP | Funcionalidad/datos del sitio; sin nuevo despliegue por este veredicto |
| Elementor4.3.4 / Pro4.3.1 | KEEP | Composición editorial actual |
| LiteSpeed Cache7.9.1 | KEEP | Caché actual; sin cambios de opciones |
| Rank Math1.0.280 | KEEP | SEO y sitemap, sin duplicar schema médico |
| Sensia0.2.7 | KEEP — NO TOCAR | Aplicación ajena a esta revisión; solo se utiliza nombre/versión del informe |
| Hostinger preview-domain1.4.0 / auto-updates1.0.8 (MU) | KEEP | Gestión del proveedor; no se modifica su política |
| Hello Elementor3.5.1 / irina-gonzalez0.1.0 | KEEP | Padre y child activo, respectivamente |
| Astra / Site Kit | Ausentes | No hay operación REMOVE pendiente ni instalación por esta revisión |
|168 inicio-v2 | KEEP | Portada publicada; no crear landing duplicada |
|169 dra-irina-gonzalez-saez | KEEP | Entidad publicada, menú Conóceme |
|172 primera-consulta | KEEP | Página publicada de orientación/agenda |
|173 preguntas-frecuentes | KEEP | FAQ publicada |
|174 contacto | KEEP | Contacto publicado |
|175 aviso-de-privacidad /176 aviso-medico /177 terminos-de-uso | KEEP | Legales publicados |
|143 links /335 gracias | KEEP | Publicadas, noindex según QA anterior; el inventario actual no mide robots |
|170 otorrinolaringologia /171 sueno | KEEP | Conservar borrador hasta aprobación correspondiente |
|106 inicio-antiguo | KEEP / ARCHIVO | Conservar en borrador según D-056; no borrar ni cambiar estado |
| Principal20 (6 ítems) / Pie21 (5) / Legal22 (3) | KEEP | Ubicaciones primary/footer/legal. Principal incluye ORL/Sueño en BD, ocultos al público mientras borradores según D-072/QA anterior; no interpretar la lista de BD como menú visible |
|14 condiciones /9 tratamientos /3 recursos | KEEP | Todos draft; no publicar por inventario |
|17 credenciales | KEEP | Publicadas; este veredicto no vuelve a certificar su aprobación documental |
|44 adjuntos (solo total) | KEEP preventivo | Conservar biblioteca completa por D-056. Identidad/clasificación por archivo aún pendientes; el total no permite cerrar1.2 |

No se propone eliminar medios, respaldos, páginas ni plugins. El inventario nuevo no enumera cada entrada de los menús pie/legal; para que quede trazable la clasificación por ítem, incluir también sus etiquetas/destinos en la ampliación local.

## WP_DEBUG_DISPLAY

WP_DEBUG_DISPLAY=true con WP_DEBUG=false no activa la rama de depuración de WordPress. Contrastado con `wp_debug_mode()` en el código local de WordPress6.8.3 (`wp-includes/load.php`): DISPLAY solo se aplica dentro de `if (WP_DEBUG)`. No prueba el valor de PHP `display_errors` del servidor y no se afirma que toda salida de errores esté desactivada a nivel de PHP.

Fijar DISPLAY=false sería un refuerzo opcional para una futura activación de debug; no es requisito pendiente del criterio1.5 ni motivo para mantenerlo en REVIEW. No se abrió/modificó wp-config ni se enviaron órdenes al servidor. `default_comment_status` aparece vacío en la salida literal: no se convierte aquí en un valor `closed` ni se certifica la configuración por página.

## Evidencia que falta para1.2

Claude local aporta, solo para medios públicos: ID, slug o nombre de archivo, MIME y clasificación KEEP/ARCHIVO/destino, sin usuarios, correos, tokens ni rutas/documentos privados. Añade entradas de menús pie/legal con etiquetas y destinos. Una clasificación común KEEP para todos los adjuntos es válida si la lista permite identificar cuáles son esos44. No se necesita tomar una decisión de borrado.

Este es un veredicto documental sobre el informe de Claude, no una nueva extracción autenticada independiente ni prueba de restauración/staging. Las otras tareas y gates conservan su estado. Progreso esperado tras1.5/3.6:105/253 (41,50%),41/95 DONE;1.2 sigue REVIEW.
