# Barra móvil en horizontal: el CTA del hero cruzaba la columna lateral · corrección local · 2026-10-20/21 (sesión local)

**Encargo:** José y Codex (bc57b3e). Es una mejora separada de Q-081: el aviso de idioma y su P3 siguen VERIFIED. **Pendiente de auditoría y despliegue: no está en producción.**

## Diagnóstico (antes; código de producción bc57b3e)

Medidor `tools/audit/barra-horizontal/solape-barra.cjs`:
- 30 rutas (15 ES y sus 15 parejas EN) × 7 tamaños: horizontal 667×375, 740×360, 844×390 y 932×430; vertical 375×667 y 390×844; escritorio 1440×900.
- Mide al cargar y desplazando en pasos de media pantalla hasta el final de cada página.
- Calcula la intersección de cada botón o enlace con los 3 botones de la columna, el tamaño de esos botones y el desborde.

Resultado con **WordPress local** (`antes.log`): **72 fallos de 210**.

| Tamaño | Botón del hero bajo la columna | Otros botones o enlaces bajo la columna al desplazar | Desborde |
|---|---|---|---|
| 667×375 | 24/30 (todas salvo las 6 legales, sin CTA) | 25/30 | 0 |
| 740×360 | 24/30 | 30/30 | 0 |
| 844×390 | 24/30 | 30/30 | 0 |
| 932×430, 375×667, 390×844 y 1440×900 | 0 | 0 | 0 (a 932 px no hay barra móvil) |

Ejemplos de intersección (667×375):
- Primera consulta: «Agendar por WhatsApp» 46×44 px.
- Conóceme: «Opiniones en Doctoralia» 46×38 px.
- Portada: «Conocer a la Dra. Irina» 46×11 px.

**Causa:**
- Con `max-width: 899px`, `orientation: landscape` y `max-height: 500px`, la barra pasa a una columna fija a la derecha (46 px más el margen).
- `.di-container` ocupa todo el ancho útil, así que el CTA ancho del hero, y cualquier contenido al desplazar, pasa por debajo.

## Corrección (solo CSS, `components.css`, dentro de esa misma consulta de medios)

- Los contenedores del contenido y del pie (`.di-main` y `.di-footer`, con `.di-container` y `.di-container--narrow`) reservan a la derecha la franja de la columna: 46 px más 12 px de aire, es decir, los mismos 58 px que ya usa el aviso de idioma en horizontal. Se alinean al margen izquierdo.
- Los fondos de los heros y de las secciones siguen a todo el ancho, así que **el encuadre de las fotos no cambia**.
- **La cabecera no cambia:** el ☰ comparte eje con la columna.
- No se oculta nada. La barra conserva sus 3 acciones de 46×46 con nombre accesible.
- Sin cambios en PHP, JS, contenido, textos, marca, menús, sombras ni encuadres.

## Resultados

| Prueba | Tipo | Antes | Después |
|---|---|---|---|
| `solape-barra.cjs`, 30 rutas × 7 tamaños, al cargar y desplazando (`despues.log`) | WordPress local | 72/210 fallos | **0/210**; además 0 botones o enlaces de cualquier sección bajo la columna (antes, 25–30 páginas por tamaño); sin desborde |
| `interaccion.cjs` (`interaccion.log`): 7 rutas ES/EN × 3 tamaños horizontales × navegador en es-MX y en-US. CTA pulsable en su centro y en los puntos medios de los 4 bordes; columna con 3 acciones accesibles y sin taparse; menú abrir y cerrar; giro vertical → horizontal → vertical; texto largo inyectado | WordPress local | 45/132 fallos (control con el CSS anterior, `interaccion-control-antes.log`) | **0/132** |
| Banco con HTML público (`publico.log`): páginas reales de producción, con el `components.css` servido sustituido **solo en el navegador de prueba** (90 sustituciones); 30 rutas × 3 tamaños horizontales | HTML público y asset candidato | 72/90 fallos | **0/90** |
| Regresión del aviso de idioma (`regresion.log`): `solape-cta.cjs` 63, `hint-test-local.cjs` 37 y `transiciones-prod.cjs` con base local 9 | WordPress local | — | **63/63, 37/37 y 9/9** |
| `seo_check.py` local | WordPress local | — | Sin fallos |
| `tools/lint` (ESLint y stylelint del proyecto) y `node --check` | Estático | — | Salida 0 |

**Capturas:**
- `antes/` y `despues/`: 844×390 de la portada, Conóceme, Primera consulta, `/en/` y About EN, desde WordPress local.
- `interaccion/`: CTA en horizontal, menú cerrado y texto largo a 667×375.
- `publico/`: banco con fotos reales.

**Límites:**
- Ninguna prueba acredita despliegue ni la caché de producción con este CSS.
- WordPress local usa imágenes sintéticas.
- El banco público sustituye el CSS solo en el navegador.
- Chrome con tamaños emulados, sin dispositivos físicos, Safari ni Firefox.
- La franja reservada solo existe en horizontal con poca altura (la misma condición que activa la columna).
