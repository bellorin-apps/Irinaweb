# QA de cierre — 2026-10-15 (Claude cloud)

Producción hasta 968cc92. Chromium (Playwright) contra el sitio en vivo, sin sesión (desde la revocación de «claude-ops» las peticiones de la nube son anónimas). Script: rastreo de 15 URL públicas + una 404 de prueba en 5 anchos (360, 390, 768, 1024, 1440), con scroll completo para cargar lo diferido.

## 11.1 Funcional

| Comprobación | Resultado |
|---|---|
| Estado HTTP | 15/15 URL públicas 200 en los 5 anchos; `/no-existe-qa/` 404 en los 5 (página 404 propia, un h1, enlaces de salida) |
| Un h1 por página | 80/80 vistas |
| Desborde horizontal | 0 px en 80/80 |
| Imágenes rotas | 0 |
| Recursos del sitio con error (≥400) | 0 |
| Errores de JavaScript | 0. En consola: el 404 de la propia página de prueba (esperado) y un mensaje interno del captcha de Cloudflare en /contacto/ (de terceros, inocuo) |
| WhatsApp | Un único destino en todo el sitio: wa.me/528123685381 |
| Teléfono | Un único `tel:+528115695744` |
| Mapa | Mapa con estilo (Maps JS) en portada y Contacto + «Cómo llegar» a maps.app.goo.gl/yT1H6zgSCcnZDP9d8 |
| Formulario | Solo en /contacto/; no se envió (manda correos reales). Antispam y captcha verificados antes (Q-052, Q-055, D-085) |
| Cookies | Solo `_ga` y `_ga_5961XX8QZ7` (GA4, declarado en el aviso de privacidad §9) |
| Enlaces internos | Sin rotos (Q-060, sesión local: 15 enlaces distintos 200; 105/105 rutas de borradores en 404) |

## 11.3 Contenido

- NAP coherente: la dirección aparece siempre como «Av. Paseo de los Leones 2341, Consultorio 6, Piso 2, Cumbres 2.º Sector, Monterrey», con el mismo WhatsApp y teléfono; coincide con el schema Physician.
- Corregido: la entradilla de Preguntas frecuentes remitía a «cada página de padecimiento», que ya no existen (D-076). Ahora: «Las dudas sobre tu caso se resuelven en la valoración.» (build-pages.py; pendiente de setup-pages).
- Títulos D-049 y textos dictados por José sin cambios. Claims clínicos: solo los aprobados por la Dra. (ORL, Sueño y 5 fichas).
- Fechas: aviso de privacidad actualizado el 14-oct-2026; fichas con lastReviewed de su aprobación.

## 6.7 / 6.8 Responsive y QA visual

- 5 anchos sin desbordes; hojas de contacto revisadas a 390 y 1440 (16 páginas cada una): cabecera, hero con foto o variante nocturna, migas en franja, barra móvil inferior y botones legibles en todos.
- Movimiento: entradas del hero y transición entre páginas desactivadas con «reducir movimiento» (D-084, verificado por la sesión local).

## 11.2 Dispositivos — límite

Este entorno solo tiene Chromium. Quedan sin probar en este informe Safari (iOS/macOS) y Firefox reales; la sesión local probó Galaxy (Chrome Android) e iPhone emulado en varios cambios. Para cerrar 11.2: una pasada manual de José o la sesión local en iPhone (Safari) y Firefox de escritorio.
