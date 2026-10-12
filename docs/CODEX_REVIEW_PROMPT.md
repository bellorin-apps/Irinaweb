# Encargo para Codex: auditoría de las 17 tareas en REVIEW y de la entrega del 2026-10-12

Versión 1.0 · 2026-10-12 · Lo preparó la sesión local (Claude, PC de José) a petición del propietario.
Pega este documento completo como primer mensaje de la sesión de Codex.

---

## 1. Rol y objetivo

Eres **Codex, el auditor** del proyecto. El ciclo es `BUILD → REVIEW → FIX → VERIFY → GATE`. Claude construye
(sesión cloud «Sitio web») y despliega (sesión local). Tú auditas, corriges y verificas. José, propietario y
diseñador, decide producto, UX y prioridades.

En `PLAN.md` hay **17 tareas en estado `REVIEW`**: Claude las implementó y verificó, y solo les falta tu
auditoría. Una tarea pasa a `DONE` únicamente si cumple IMPLEMENTED + TESTED + REVIEWED + FIXED + VERIFIED
(`PLAN.md` §«Cómo se calcula el avance»).

Objetivo, en orden:

1. Auditar cada una de las 17 tareas y dar un veredicto con evidencia: `DONE`, `FIX` (corriges tú y luego
   `DONE`) o `REVIEW` (se queda, con el motivo concreto).
2. Auditar la entrega de código del 2026-10-12, descrita en la §4.
3. Actualizar `PLAN.md` y regenerar `PROGRESS.md` con `python tools/audit/progress.py`. Registrar la evidencia
   en `QA.md` y dejar el relevo en `BATON.md`. Actualizar `STATUS.md` y `NEXT.md`.

Si todas pasan, el avance español sube de 66/253 a 108/253 de peso (de 26 % a 43 %). Que sea por evidencia, no
por cumplir la cifra.

## 2. Reglas (de `AGENTS.md`, obligatorias)

- Rama `ccr-6254502d-xly8ww`, o una rama `codex/` que se integre allí. Haz `git pull` antes de empezar.
  Commits pequeños y separados de los cambios del usuario. Los `tools/preview/*.html` modificados en local
  son de José: no los toques ni los incluyas.
- **No despliegas.** Si un cambio necesita producción, déjalo en `BATON.md` con el script exacto de
  `tools/deploy/` y la sesión local lo ejecuta.
- No leas ni imprimas secretos (`env.sh`, `config.php`, `*.local.txt`). No toques
  `wp-content/plugins/sensia` ni `wp-content/uploads/sensia-privado`.
- No publiques contenido clínico ni te saltes la aprobación médica. No borres medios, páginas, plantillas
  ni respaldos.
- No cambies textos de marca, menús ni sombras. Respeta D-049 (títulos sin punto final) y D-053.
- Contenido: edita el build, regenera su JSON y comprueba que el diff es el esperado.
- Verifica PHP (`php -l`), PHPCS (`phpcs.xml.dist`), sintaxis JS (`node --check`) y bash (`bash -n`).
  No pruebes el formulario ni SMTP en producción, porque envía correos reales.
- Documenta los límites de cada prueba. Un harness local sobre HTML público no demuestra que algo esté
  desplegado.
- La instrucción más reciente del propietario prevalece sobre documentos históricos (`DECISIONS.md`
  hasta D-073).

## 3. Las 17 tareas en REVIEW

Criterio común: el documento existe, es coherente con el sitio actual y con `DECISIONS.md`, no contradice
otros documentos y no contiene secretos. Las notas de «v0.1» o «pendiente» no impiden `DONE` si lo que falta
depende del propietario y queda anotado. En ese caso se separa como tarea OWNER o como pregunta.

| ID | Tarea | Peso | Evidencia actual | Qué verificar |
|---|---|---:|---|---|
| 1.2 | Inventario de producción + KEEP/REPLACE/REMOVE | 3 | `docs/audit/`, `docs/AUDIT_WP.md` | Que el inventario corresponda a producción hoy (plugins, páginas, menús, medios) y que cada ítem tenga clasificación y destino |
| 1.5 | Limpieza y configuración base | 2 | Ejecutado el 7 y 8 de octubre | Idioma es_MX, zona horaria America/Monterrey, formatos, Site Kit y Astra fuera, Hello activo, hardening de wp-config. Comprobarlo por HTML público y `/wp-json/`, sin credenciales |
| 1.8 | Accesos (sin secretos) y rollback | 1 | `docs/DEPLOY.md`, `docs/RUNBOOK_RESET.md` | Que el procedimiento coincida con los scripts actuales (`deploy-code.sh` y sus backups en `~/deploy-backups/<ts>/`) y que no haya secretos |
| 2.1 | Investigación de referentes | 3 | `docs/REFERENCE_RESEARCH.md` | Coherencia y utilidad. Los dominios no verificados por bloqueo de red deben estar marcados como tales |
| 2.2 | Competencia local Monterrey | 2 | `docs/COMPETITION_MONTERREY.md` | Igual. Los ítems «no verificado» deben estar marcados |
| 2.3 | Keyword research | 3 | `KEYWORDS.md` | Coherencia con `SEO.md` y `SITEMAP.md`. Cruzar con Search Console queda como pendiente del owner |
| 2.4 | Perfil de paciente + motivos | 2 | `DISCOVERY.md` §0b | Los 11 motivos de la Dra. reflejados en el sitio y la estrategia |
| 2.5 | Estrategia | 3 | `STRATEGY.md` | Mensaje y propuesta de valor coherentes con lo publicado (portada, Conóceme, Primera consulta) |
| 3.1 | Sitemap | 3 | `SITEMAP.md` | Que coincida con las URLs reales, borradores incluidos, y con el menú de D-072 |
| 3.2 | SEO | 3 | `SEO.md`, `tools/content/pages/seo.json` | Titles, descriptions, canonicals y enlazado frente a producción. 301 documentados |
| 3.3 | Modelo de contenido | 3 | `ARCHITECTURE.md` §4 | Que los CPT y metacampos descritos existan en `dra-irina-core` |
| 3.4 | Mapa de schema | 2 | `ARCHITECTURE.md` §5 | Que el JSON-LD publicado coincida con el mapa por tipo de página |
| 3.5 | Arquitectura | 3 | `ARCHITECTURE.md` | Tema frente a core, uso controlado de Elementor y fuente única. Comparar con el código |
| 3.6 | Plugins | 2 | `PLUGINS.md` | Que la tabla coincida con los plugins activos |
| 3.7 | Flujo de agenda y conversión | 2 | D-011, `Tracking/Events.php` | WhatsApp principal, eventos GA4 permitidos y destinos reales de los botones |
| 4.3 | Componentes base | 3 | `assets/css/components.css` | Botones, cards, badges e iconos Lucide coherentes con `DESIGN_SYSTEM.md`. Los inputs de la Fase 8 quedan aparte |
| 4.6 | Design system | 2 | `DESIGN_SYSTEM.md` | Tokens y reglas al día: títulos D-049, lombrices, migas D-071, tarjeta beige de «Escucharte» |

## 4. Entrega de código del 2026-10-12, para auditar

Producción en `9a9329a`; ver `BATON.md`. Claude la construyó y la desplegó. Necesita un auditor
independiente.

1. **Carrusel de logos** (`assets/js/main.js`, bloque `.di-logos`; commits `fd9fb2e`, `280f968`, `193c499`
   y `fff239c`). Inercia al soltar con velocidad de los últimos 70 ms, fricción 0,4^s, relevo al autoplay a
   velocidad plena hacia la izquierda y continuo al invertir. Revisa fugas de `requestAnimationFrame`,
   `pointercancel` y `lostpointercapture`, `touch-action: pan-y` en Safari iOS, y `prefers-reduced-motion`.
2. **D-073** (`6870987`):
   - `wp_editor_set_quality` da 95 para el ancho de `IRINA_MOVIL_WIDTH` (1600) y 100 para lo demás. Riesgo
     a revisar: una imagen original de exactamente 1600 px editada en wp-admin se guardaría a 95.
   - `irina_image_original()` versiona la URL móvil con `?m=<filemtime>`.
   - GA4 (`Tracking/Events.php`): la cola dataLayer existe al instante y gtag.js carga tras «load» en
     tiempo ocioso. Verifica que no se pierden eventos y que «Do Not Track» se respeta.
   - Encaje alfa del retrato (`main.js`): solo se calcula con `IntersectionObserver` a menos de 800 px.
   - PHPCS **no** se pudo ejecutar en la PC porque no tiene PHP local. Ejecútalo tú sobre los archivos
     tocados.
3. **Hero de Primera consulta** (`f255242`, `9a9329a`). `build-pages.py` con la imagen `hero-primera` y
   encuadre 50 % 6 % en escritorio y 85 % 20 % en móvil. `setup-pages.sh` sustituye `__PC_HERO_ID__` y
   `__PC_HERO_URL__`. Revisa la idempotencia y el comportamiento sin adjunto (debe usar el degradado
   cálido).

## 5. Entregables

- Un commit por grupo de tareas auditadas, con el veredicto y la evidencia en `QA.md`, una fila por tarea.
- `PLAN.md` con estados actualizados solo donde haya evidencia. Después, `python tools/audit/progress.py`
  y commit de `PROGRESS.md`.
- `STATUS.md` y `NEXT.md` al día. `BATON.md` con lo que la sesión local deba desplegar, si hace falta.
- Preguntas al propietario, si las hay, con este formato: pregunta, opciones, pros y contras,
  recomendación, impacto y reversibilidad.
- Al final, un bloque `MENSAJE PARA EL OTRO AGENTE` con lo terminado, el SHA, lo que debe desplegar la
  sesión local y los bloqueos.
