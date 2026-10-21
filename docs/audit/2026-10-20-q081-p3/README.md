# P3 de Q-081 · el aviso EN tapaba los botones del hero · corrección local · 2026-10-20 (sesión local)

**Encargo:** José, en el chat local, tras la ratificación de Q-081 por Codex (980f810): corregir la P3 con alcance propio, con pruebas y capturas locales, commit y push para auditoría, **sin desplegar**.

## Diagnóstico (`antes.log`, código de producción 4dcdc13)

El medidor `tools/audit/q081/solape-cta.cjs` recorre 9 páginas ES con pareja en 7 tamaños: 360×640, 375×667, 390×844, 414×896, 430×932, 844×390 y 1440×900. Resultado: **24 fallos de 63**. La P3 era más amplia de lo anotado:
- **Vertical:** 5–6 px sobre el botón principal del hero en Primera consulta, Otorrino, Sueño, Conóceme y Contacto.
- **Horizontal 844×390:** unos 40 px sobre los botones del hero en la portada («Agendar por WhatsApp» y «Conocer a la Dra. Irina») y en Conóceme («Agendar…» y «Opiniones en Doctoralia»).
- **Escritorio:** sin solape.

## Corrección (solo `main.js` y `components.css` del tema)

- Mientras el aviso tape un botón o enlace del hero (`.di-bleed a`, `.di-bleed button`), con un margen de 8 px, recibe la clase `.is-waiting`: invisible, sin clics y fuera del árbol de accesibilidad (`visibility: hidden`).
- Al desplazar la página, el botón sube y el aviso aparece.
- Se vuelve a comprobar con `scroll` (pasivo, limitado a un cálculo por fotograma), `resize`, `load` y `document.fonts.ready`, porque el hero puede recolocarse al cargar fuentes e imágenes.
- El margen de 8 px cubre la animación de entrada y deja aire visual.
- Con `prefers-reduced-motion`, sin transición.
- **No cambia:** posiciones, textos, marca, menús, sombras, PHP ni contenido aprobado. Las reglas del aviso (idioma, persistencia, Gracias y pareja) quedan igual.

## Resultados en la copia aislada (WP 7.1.3 + Rank Math reales)

| Prueba | Resultado |
|---|---|
| `solape-cta.cjs` (`despues.log`) | **0 fallos de 63**. Al cargar, 23 casos esperan (taparían un botón del hero) y 40 se muestran sin tapar nada. Tras desplazar 160 px, todos se ven sin tapar botones del hero ni de la barra móvil. |
| Regresión del aviso (`regresion-37.log`, `hint-test-local.cjs`) | **37/37** |
| Regresión de 13.5 (`seo_check.py` local) | Sin fallos |
| Capturas (`despues/`) | Primera consulta en los 7 tamaños, al cargar y tras desplazar 160 px. `antes/` conserva dos capturas de referencia. |

**Límites:**
- ESLint y stylelint no están instalados en esta PC (`npx` sin paquetes); se validó con `node --check`.
- Sin LiteSpeed en la copia, aunque el cambio es solo del cliente y no varía el HTML.
- Idioma simulado y sin dispositivos físicos.

**Observación aparte, previa y fuera de este alcance:** en horizontal (844×390), el botón ancho del hero pasa por debajo de la columna lateral de la barra móvil (WhatsApp, Llamar y Cómo llegar). Ya ocurría antes de Q-081.
