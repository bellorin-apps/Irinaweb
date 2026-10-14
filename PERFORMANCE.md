# PERFORMANCE — Medición Lighthouse (PLAN 10.2)

Fecha: 2026-10-14 · Claude cloud · Lighthouse 12.2.1, Chromium headless, 3 pasadas por página y modo (se informa la mediana).
Móvil = perfil por defecto de Lighthouse (Moto G, 4G lento simulado, CPU ×4). Escritorio = `--preset=desktop` (1350×940, DPR 1).
URLs canónicas con barra final (una primera tanda sin barra sumaba una redirección 301 de hasta 0,9 s y se descartó).

## Resultados (medianas)

| Página | Modo | n | Perf | A11y | BP | SEO | LCP s | FCP s | TBT ms | CLS | SI s | TTFB ms | Peso KiB |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| contacto | desktop | 3 | 75 | 95 | 100 | 100 | 3.67 | 1.16 | 0 | 0.021 | 1.79 | 172 | 4260 |
| contacto | mobile | 3 | 61 | 95 | 100 | 100 | 7.17 | 3.19 | 214 | 0.009 | 6.00 | 176 | 1298 |
| dra-irina-gonzalez-saez | desktop | 3 | 77 | 95 | 100 | 100 | 3.64 | 1.07 | 0 | 0.023 | 1.51 | 184 | 3942 |
| dra-irina-gonzalez-saez | mobile | 3 | 82 | 95 | 100 | 100 | 4.01 | 2.91 | 52 | 0.011 | 3.14 | 289 | 1269 |
| inicio | desktop | 3 | 63 | 91 | 100 | 100 | 3.74 | 1.18 | 0 | 0.236 | 1.82 | 380 | 5204 |
| inicio | mobile | 3 | 70 | 91 | 100 | 100 | 4.26 | 3.02 | 188 | 0.012 | 5.39 | 698 | 2015 |
| preguntas-frecuentes | desktop | 3 | 95 | 92 | 100 | 100 | 1.12 | 1.10 | 0 | 0.027 | 1.36 | 173 | 550 |
| preguntas-frecuentes | mobile | 3 | 78 | 93 | 100 | 100 | 3.88 | 3.62 | 69 | 0.000 | 5.06 | 251 | 527 |
| primera-consulta | desktop | 3 | 79 | 93 | 100 | 100 | 3.08 | 1.16 | 0 | 0.001 | 1.58 | 273 | 3955 |
| primera-consulta | mobile | 3 | 73 | 94 | 100 | 100 | 4.67 | 2.98 | 178 | 0.000 | 4.54 | 223 | 693 |

Perf = rendimiento; A11y = accesibilidad; BP = buenas prácticas. LCP = elemento más grande pintado; FCP = primer contenido; TBT = bloqueo del hilo; CLS = saltos de diseño; SI = índice de velocidad; TTFB = respuesta del servidor.

## Límites de esta medición (leer antes de comparar)

- Se midió desde un contenedor en la nube a través de un proxy de salida, no desde Monterrey ni desde un teléfono real. El proxy sirve los recursos del sitio como HTTP/1.1 (Lighthouse lo marca como «uses-http2»); en un navegador real el sitio va por HTTP/2 o HTTP/3. Ese aviso es del entorno, no del sitio.
- La red es ruidosa: Contacto móvil dio 4,4 s y 7,2–7,3 s de LCP en pasadas seguidas con el mismo HTML. Las cifras sirven para comparar órdenes de magnitud y detectar problemas, no como valor absoluto.
- Son datos de laboratorio. Los datos de campo (Core Web Vitals de usuarios reales) salen en Search Console › Experiencia, cuando haya tráfico suficiente.
- La misma tanda repetida desde la sesión local o con PageSpeed Insights (pagespeed.web.dev) es la referencia para el antes y después.

## Hallazgos

1. **SEO y buenas prácticas 100** en las cinco páginas.
2. **Accesibilidad 91–95.** Fallos repetidos y ya corregidos en la rama (8bb93a0, pendiente de despliegue): contraste de las cédulas del pie (4,38:1) y de las migas (4,44:1); títulos del pie y de los pasos de la portada fuera de orden (h4 tras h2); puntos del carrusel de credenciales de 8 px (mínimo 24 px). Medida de comprobación: mismas posiciones y estilos en vivo con el CSS local, salvo los dos grises y +2 px de separación entre puntos.
3. **CLS de la portada en escritorio 0,236** (umbral bueno < 0,1). Es Q-046: la cabecera se mueve cuando llega la tipografía Iskra de Adobe (kit diferido). En navegador real sin estrangular red mide 0,035–0,083. José decidió no tocar las fuentes (D-073/Q-046); queda documentado. En móvil el CLS está bien (0,012).
4. **Peso en escritorio 3,9–5,2 MB** en Inicio, Dra., Primera consulta y Contacto. La causa es la foto de cabecera a calidad 100 (D-064), no su tamaño en píxeles: hero-home 2400×1600 = 3,4 MB; hero-dra, hero-contacto y hero-primera 2040×1405 = 2,6–3,3 MB. Ya son del ancho adecuado, así que reducir píxeles no ayuda. Prueba aproximada re-codificando hero-home: q95 ≈ 1,0 MB, q92 ≈ 0,7 MB, q90 ≈ 0,6 MB (−70 % a −83 %). **Propuesta para José, no aplicada:** escritorio a q95, como ya está el móvil (D-073), por la misma vía que irina-movil. Requiere su OK porque cambia D-064.
5. **Móvil: peso 0,5–2,0 MB**, ya con las versiones irina-movil q95 (D-073/D-074). LCP 3,9–4,7 s en red 4G lenta simulada; el mayor componente es la propia red simulada. Portada móvil: las dos tarjetas de área a 1600 px (577 y 374 KB) pesan más que la cabecera.
6. **TTFB 170–700 ms**: varía entre pasadas; las altas coinciden con caché de LiteSpeed fría tras la purga del despliegue D-076. Volver a medir con la caché caliente.
7. **GA4 (159 KB)** es el script más pesado; ya carga tras «load» (D-073).
8. **Primera consulta escritorio**: fondo pc-fondo en PNG de 775 KB (fallback documentado en NEXT); sin cambios sin autorización.

## Siguiente

- Desplegar 8bb93a0 (accesibilidad) y repetir la tanda desde la sesión local o PageSpeed Insights para el «después».
- Decisión de José sobre el punto 4.
- Revisar datos de campo en Search Console cuando existan.

## Después (sesión local, 2026-10-13 18:25, hora de Monterrey)

Lighthouse 12.2.1 (npx) en Chrome headless local (Windows, red doméstica), con 3 pasadas por página y modo y mediana. Es el mismo método y la misma versión que el «antes». Se usaron URLs con barra final y la caché de LiteSpeed estaba caliente. Medido después de desplegar a4a3109 (accesibilidad 8bb93a0 + D-081, escritorio a q95) y **antes** de D-082, con ORL y Sueño todavía en borrador. PageSpeed Insights sin clave respondió 429 y no se usó. Cada cifra de la tabla va de antes a después.

| Página | Modo | Perf | A11y | BP | SEO | LCP s | FCP s | TBT ms | CLS | SI s | TTFB ms | Peso KiB | Fallos de accesibilidad |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| contacto | desktop | 75 → 87 | 95 → 100 | 100 | 100 | 3.67 → 2.21 | 0.83 | 0 | 0.021 → 0.001 | 1.34 | 65 | 4260 → 1763 | — |
| contacto | mobile | 61 → 75 | 95 → 100 | 100 | 100 | 7.17 → 4.76 | 2.91 | 94 | 0.009 → 0.009 | 4.06 | 199 | 1298 → 1295 | — |
| dra-irina-gonzalez-saez | desktop | 77 → 88 | 95 → 98 | 100 | 100 | 3.64 → 2.20 | 0.88 | 0 | 0.023 → 0.001 | 0.96 | 63 | 3942 → 1817 | heading-order |
| dra-irina-gonzalez-saez | mobile | 82 → 78 | 95 → 99 | 100 | 100 | 4.01 → 4.65 | 2.98 | 26 | 0.011 → 0.000 | 2.98 | 193 | 1269 → 1332 | heading-order |
| inicio | desktop | 63 → 83 | 91 → 100 | 100 | 100 | 3.74 → 2.89 | 0.91 | 0 | 0.236 → 0.001 | 1.11 | 197 | 5204 → 6678 | — |
| inicio | mobile | 70 → 75 | 91 → 100 | 100 | 100 | 4.26 → 5.30 | 2.95 | 69 | 0.012 → 0.000 | 3.04 | 198 | 2015 → 2772 | — |
| preguntas-frecuentes | desktop | 95 → 97 | 92 → 100 | 100 | 100 | 1.12 → 1.18 | 0.89 | 0 | 0.027 → 0.001 | 0.89 | 66 | 550 → 551 | — |
| preguntas-frecuentes | mobile | 78 → 78 | 93 → 100 | 100 | 100 | 3.88 → 4.27 | 3.53 | 40 | 0.0 → 0.017 | 3.53 | 209 | 527 → 527 | — |
| primera-consulta | desktop | 79 → 97 | 93 → 100 | 100 | 100 | 3.08 → 1.10 | 0.85 | 0 | 0.001 → 0.001 | 1.18 | 682 | 3955 → 1640 | — |
| primera-consulta | mobile | 73 → 85 | 94 → 100 | 100 | 100 | 4.67 → 3.63 | 2.79 | 26 | 0.0 → 0.000 | 2.79 | 196 | 693 → 1480 | — |

**Lectura:**
- **Accesibilidad 100** en todas las páginas salvo Dra. (98/99): queda un `<h4>` fuera de orden (heading-order).
- **CLS de la portada en escritorio: 0,236 → 0,001.**
- **Escritorio:** el peso baja a menos de la mitad en Dra. (3942 → 1817 KiB), Primera consulta (3955 → 1640) y Contacto (4260 → 1763) gracias a D-081. El LCP baja a 1,1–2,2 s.
- **Portada:** pesa más (escritorio 5204 → 6678 KiB). Esta vez la medición cargó el fondo de «Escucharte» `dra-escucharte-bg` a q100 en 2400 px (**3133 KiB**, el recurso más pesado), que en el «antes» no aparecía, probablemente por la carga diferida. La foto de cabecera sí bajó (3391 → 1071 KiB). Aplicar D-081 también a ese fondo en escritorio es decisión de José.
- **Primera consulta en móvil:** 693 → 1480 KiB. El peso lo da `pc-fondo` en PNG (775 KiB, 1024 px, sin WebP; ver NEXT). Con un original de 1600 px o más se reduciría a unos 80 KiB en móvil.
- **Móvil:** el LCP sigue en 3,6–5,3 s con 4G lento simulado. La red simulada pesa más que el sitio. TTFB 190–210 ms en móvil y 60–200 ms en escritorio.
- **Límites:** es una sola máquina en una red doméstica y la medición tiene ruido. Por ejemplo, Dra. móvil bajó de 82 a 78 con más peso de endoscopio visible. Sirve para comparar órdenes de magnitud.

## Después 2 (sesión local, 2026-10-14 09:10): fondo de «Escucharte» a q95 en escritorio (D-081) y Q-049

Mismo método (Lighthouse 12.2.1 local, 3 pasadas, mediana).

| Página | Modo | Perf | A11y | LCP s | CLS | Peso KiB |
|---|---|---|---|---|---|---|
| inicio | desktop | 83 → 86 | 100 | 2.89 → 2.45 | 0.001 | 6678 → 4070 |
| dra-irina-gonzalez-saez | desktop | 88 → 89 | 98 → 100 | 2.20 → 2.15 | 0.001 | 1817 |
| dra-irina-gonzalez-saez | mobile | 78 → 76 | 99 → 100 | 4.65 → 5.13 | 0.000 | 1332 |

`dra-escucharte-bg` en escritorio pasa de 3133 KiB (q100) a 525 KiB (q95), sin cambio visible a 1440. Accesibilidad 100 en las cinco páginas. El rendimiento móvil de Dra. varía ±4 entre tandas con el mismo peso: es ruido de la red simulada.

