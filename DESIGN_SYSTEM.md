# DESIGN_SYSTEM — Dra. Irina González Sáez

Versión 0.2 · 2026-10-08 · Estado: REVIEW (v2 tras la primera ronda del Checkpoint 2; pendiente aprobación final y auditoría Codex)
Fuente única de tokens: `wp-content/themes/irina-gonzalez/assets/css/tokens.css` (copia en `tools/preview/tokens.css`). Maquetas: `tools/preview/` → `https://drairinagonzalez.com/preview/` (noindex).

## 0. Dirección aprobada en la primera ronda del CP2 (D-026)

Cálida y genuina; titulares gigantes en Iskra 300; minimalista (hairlines, sin tarjetas con borde); cabeceras a sangre con fondo fotográfico grande en todas las páginas; transiciones modernas (reveal al hacer scroll, parallax suave, header transparente que se vuelve sólido, marquesina de motivos); registro nocturno solo en las páginas de Sueño; Home con las mismas secciones; titular «Respirar bien, dormir bien, oír bien.» se conserva. La v1 queda en `tools/preview/v1/` como referencia descartada.

## 1. Principios

1. **Calidez genuina.** Superficies crema y arena (#FBF7F2 / #F3EBE2), fotografía con luz natural, tipografía gigante y ligera. Nada frío ni corporativo.
2. **La marca manda.** Púrpura y teal del logo existente; nada de azules médicos genéricos.
3. **Una voz, dos registros.** ORL general en superficie clara; Sueño en registro nocturno (sub-marca), sin romper la identidad.
4. **Conversión sin ruido.** Un CTA primario (WhatsApp) siempre visible; nada de popups, countdowns ni sliders.
5. **Accesible por defecto.** Contraste AA, foco visible, 44 px mínimos de toque, `prefers-reduced-motion` respetado.

## 2. Color

| Token | Valor | Uso |
|---|---|---|
| `--color-primary` | #8C4EAA | Marca, enlaces, acento de titulares, botón primario |
| `--color-primary-strong` | #6F3A8B | Hover y texto sobre fondos claros cuando se necesita más contraste |
| `--color-primary-soft` | #F1E8F4 | Fondos de tarjetas ORL, chips |
| `--color-secondary` | #3CA1A7 | WhatsApp, foco, eyebrows, iconografía de confianza |
| `--color-secondary-strong` | #2D8186 | Hover del CTA, eyebrows (contraste AA sobre blanco) |
| `--color-secondary-soft` | #E4F3F4 | Fondos de tarjetas Sueño en superficie clara |
| `--color-accent` | #9CCED1 | Detalles sobre fondo nocturno |
| `--color-surface` / `--color-surface-soft` | #FBF7F2 / #F3EBE2 (v2, cálidos; v1 era #FFFFFF / #F6F3F9) | Página y secciones alternas |
| `--color-sand` / `--color-peach` | #E9D9C8 / #F1DCCB | Paneles de área ORL, degradados cálidos de fondo |
| `--color-text` / `--color-muted` | #2B2730 / #6D6577 | Texto y secundario |
| `--color-border` | #E4DFEA | Bordes, divisores |
| `--color-night` / `--color-night-2` | #1B1630 / #2A2246 | Sub-marca Sueño, footer, barra de preview |
| `--color-success` / `--color-warning` / `--color-danger` | #2E8B57 / #B7791F / #B43C3C | Estados (formularios, avisos) |

Reglas: texto púrpura solo sobre blanco o `surface-soft`; texto blanco sobre `primary`, `secondary-strong` y `night`; nunca púrpura sobre teal ni viceversa. Contraste verificado: primary sobre blanco 5.6:1; secondary-strong sobre blanco 4.6:1; secondary (#3CA1A7) sobre blanco 3.1:1 → solo para elementos grandes o con texto blanco encima.

## 3. Tipografía

- Familia: **Iskra** (Adobe Fonts, kit `nlo5pss`, nombre CSS `"iskra"`). Display y cuerpo con la misma familia; la diferencia la dan peso y tamaño.
- Pesos permitidos: 300 (H1, H2, citas, numerales gigantes, lead), 400 (cuerpo, H3), 500 (nombre de marca, nav), 600 (botones, eyebrows). No usar 100, 200, 700, 800, 900.
- Escala (base 16 px, razón 1.25): `--text-sm` 14 · `--text-md` 16 · `--text-lg` 20 · `--text-xl` 25 · `--text-2xl` 31 · `--text-3xl` 39 · `--text-4xl` 49. H1 gigante `clamp(3.2rem, 9.5vw, 8.5rem)` con interlineado 0.95 y tracking −0.025em; H2 `clamp(2.4rem, 5.6vw, 4.6rem)`; H3 `clamp(1.8rem, 3.4vw, 2.8rem)`.
- Interlineado: titulares 1.15, cuerpo 1.6. Medida máxima 65ch; lead 52ch.
- Eyebrow: 14 px, mayúsculas, tracking 0.1em, peso 600, color `secondary-strong`.
- Fallback: Segoe UI / system-ui. Cargar con `font-display: swap` (lo gestiona Typekit); preconnect a `use.typekit.net`.

## 4. Espaciado, retícula y radios

- Escala 4/8: `2xs` 4 · `xs` 8 · `sm` 12 · `md` 16 · `lg` 24 · `xl` 40 · `2xl` 64 · `3xl` 96. Gutter fluido `clamp(16px, 4vw, 32px)`.
- Contenedores: 1200 px (secciones) y 760 px (lectura).
- Secciones: padding vertical `2xl` en móvil, `3xl` desde 1024 px.
- Radios: `sm` 6 (inputs, foco), `md` 12 (botones secundarios, tarjetas pequeñas), `lg` 20 (tarjetas, fotos), `pill` para CTAs.
- Sombras: `soft` para tarjetas en reposo; `elevated` solo en hover de tarjetas enlazadas y en la barra móvil.
- En Elementor: márgenes y paddings se fijan manualmente con estos valores; no se usa GAP (preferencia del propietario). Breakpoints: 360, 768, 1024, 1280, 1536.

## 5. Componentes

| Componente | Especificación |
|---|---|
| Header | Fijo, transparente en blanco sobre la cabecera a sangre y sólido (crema al 88 % con blur) al hacer scroll; 84 px; nav con subrayado animado desde 1024 px; CTA WhatsApp siempre visible; hamburguesa circular 46 px en móvil |
| Cabecera a sangre | 100svh en Home, 78svh en interiores; fondo fotográfico grande con grano y degradado de sombra; título gigante abajo a la izquierda; indicador de scroll |
| Marquesina | Línea de motivos de consulta en Iskra 300 que se desplaza 40 s en bucle; se detiene con `prefers-reduced-motion` |
| Botones | 48 px de alto, pill, peso 600. `primary` (púrpura), `whatsapp` (teal), `ghost` (borde), `light` (blanco sobre nocturno). Hover: −1 px de elevación |
| Tarjeta de área | Fondo `primary-soft` (ORL) o `night` (Sueño); icono Lucide 28 px; título + lista de motivos; enlace |
| Motivo de consulta | Fila de lista con numeral 01–08, título en Iskra 400 y flecha; hairline inferior; hover desplaza 0.8 rem y mueve la flecha |
| Pasos de consulta | Numeral gigante 01–04 en Iskra 300 teal sobre hairline, título Iskra 400, texto muted |
| Credencial | Eyebrow + título + institución; sin logotipos de terceros |
| Timeline | Año en columna fija, línea vertical `border`, texto |
| Callout | Fondo `secondary-soft`, borde izquierdo teal 4 px; para «Cuándo consultar» |
| Review badge | Icono check, "Revisado médicamente por …" y fecha; obligatorio en páginas médicas y artículos |
| TOC lateral | Sticky desde 1024 px, lista numerada, enlaces muted → primary |
| FAQ | `details/summary` nativos, chevron Lucide, borde inferior |
| Footer | Fondo `text` (#241F23), titular gigante «Respirar bien, dormir bien, oír bien.», NAP, horario, redes, legales; nombre y cédulas |
| Barra móvil | Fija abajo, 3 acciones (WhatsApp, Llamar, Cómo llegar), oculta desde 1024 px, con `safe-area-inset-bottom` |
| Iconografía | Lucide, trazo 2, `currentColor`; sprite propio en el tema; nunca emojis |

## 6. Sub-marca Sueño («Sleep experience»)

- Fondo `night` → `night-2` en degradado, texto blanco, acento `accent` y `secondary`.
- Elemento distintivo: círculo «respiración» (animación de escala 6 s, ease-in-out, infinita) que se detiene con `prefers-reduced-motion`.
- Fotografía: tonos fríos y nocturnos, sin camas de hotel ni stock evidente; detalle de equipo (poligrafía, CPAP) solo real.
- Lenguaje: descanso, respirar, energía diurna. Nunca "cura" ni "garantizado".
- Se aplica solo en: pilar `/sueno/` y sus hijas (D-026). En el Home, el bloque y la tarjeta de Sueño van en registro claro con degradado teal suave.

## 7. Fotografía e imagen

- Hasta la sesión profesional (2–4 semanas, `docs/PHOTO_SHOTLIST.md`): marcadores con degradado de marca, nunca stock genérico.
- Formato: WebP/AVIF vía LiteSpeed; retrato 4:5, hero 3:2, artículo 16:9. `width/height` y `loading="lazy"` salvo el hero.
- Logo: SVG saneado (Safe SVG); favicon derivado de `Logo-Favicon.ai` (pendiente de exportar).

## 8. Motion

`fast` 180 ms (hover), `base` 280 ms (apertura de menú, FAQ, subrayados), `slow` 450 ms (reveal de bloques con desplazamiento de 28 px y escalonado de 80 ms); parallax del fondo de cabecera al 25 % del scroll; zoom lento (1.04) del fondo de paneles al hover. Curva `ease-out` cubic-bezier(0.2, 0.7, 0.2, 1). Todo desactivado con `prefers-reduced-motion`. En WordPress: `assets/js/main.js` del tema, sin librerías.

## 9. Pendientes para Checkpoint 2

- Decisión del propietario sobre la dirección visual (maquetas en `/preview/`).
- Exportar favicon y logo en SVG desde los originales.
- Confirmar que el kit de Adobe Fonts tiene autorizado `drairinagonzalez.com`.
