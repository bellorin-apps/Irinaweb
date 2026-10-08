# DESIGN_SYSTEM — Dra. Irina González Sáez

Versión 0.1 · 2026-10-08 · Estado: REVIEW (pendiente Checkpoint 2 del propietario y auditoría Codex)
Fuente única de tokens: `wp-content/themes/irina-gonzalez/assets/css/tokens.css` (copia en `tools/preview/tokens.css`). Maquetas: `tools/preview/` → `https://drairinagonzalez.com/preview/` (noindex).

## 1. Principios

1. **Calma clínica, no frialdad.** Mucho aire, tipografía grande y ligera, un solo acento por bloque.
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
| `--color-surface` / `--color-surface-soft` | #FFFFFF / #F6F3F9 | Página y secciones alternas |
| `--color-text` / `--color-muted` | #2B2730 / #6D6577 | Texto y secundario |
| `--color-border` | #E4DFEA | Bordes, divisores |
| `--color-night` / `--color-night-2` | #1B1630 / #2A2246 | Sub-marca Sueño, footer, barra de preview |
| `--color-success` / `--color-warning` / `--color-danger` | #2E8B57 / #B7791F / #B43C3C | Estados (formularios, avisos) |

Reglas: texto púrpura solo sobre blanco o `surface-soft`; texto blanco sobre `primary`, `secondary-strong` y `night`; nunca púrpura sobre teal ni viceversa. Contraste verificado: primary sobre blanco 5.6:1; secondary-strong sobre blanco 4.6:1; secondary (#3CA1A7) sobre blanco 3.1:1 → solo para elementos grandes o con texto blanco encima.

## 3. Tipografía

- Familia: **Iskra** (Adobe Fonts, kit `nlo5pss`, nombre CSS `"iskra"`). Display y cuerpo con la misma familia; la diferencia la dan peso y tamaño.
- Pesos permitidos: 300 (lead y citas), 400 (cuerpo), 500 (titulares), 600 (botones, eyebrows, nombres), 700 (solo cifras destacadas). No usar 100, 200, 800, 900.
- Escala (base 16 px, razón 1.25): `--text-sm` 14 · `--text-md` 16 · `--text-lg` 20 · `--text-xl` 25 · `--text-2xl` 31 · `--text-3xl` 39 · `--text-4xl` 49. H1 fluido `clamp(2rem, 4.6vw, 3.4rem)`.
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
| Header | Sticky, blanco al 92 % con blur, 76 px, logo + nombre + especialidad; nav desde 1024 px; CTA WhatsApp siempre visible; hamburguesa 44 px en móvil |
| Botones | 48 px de alto, pill, peso 600. `primary` (púrpura), `whatsapp` (teal), `ghost` (borde), `light` (blanco sobre nocturno). Hover: −1 px de elevación |
| Tarjeta de área | Fondo `primary-soft` (ORL) o `night` (Sueño); icono Lucide 28 px; título + lista de motivos; enlace |
| Tarjeta de síntoma / motivo de consulta | Borde `border`, radio `lg`, icono teal, hover eleva y colorea borde |
| Pasos de consulta | Número en círculo púrpura, título 500, texto muted |
| Credencial | Eyebrow + título + institución; sin logotipos de terceros |
| Timeline | Año en columna fija, línea vertical `border`, texto |
| Callout | Fondo `secondary-soft`, borde izquierdo teal 4 px; para «Cuándo consultar» |
| Review badge | Icono check, "Revisado médicamente por …" y fecha; obligatorio en páginas médicas y artículos |
| TOC lateral | Sticky desde 1024 px, lista numerada, enlaces muted → primary |
| FAQ | `details/summary` nativos, chevron Lucide, borde inferior |
| Footer | Fondo `night`, texto blanco al 80 %, NAP, horario, redes, legales; nombre y cédulas |
| Barra móvil | Fija abajo, 3 acciones (WhatsApp, Llamar, Cómo llegar), oculta desde 1024 px, con `safe-area-inset-bottom` |
| Iconografía | Lucide, trazo 2, `currentColor`; sprite propio en el tema; nunca emojis |

## 6. Sub-marca Sueño («Sleep experience»)

- Fondo `night` → `night-2` en degradado, texto blanco, acento `accent` y `secondary`.
- Elemento distintivo: círculo «respiración» (animación de escala 6 s, ease-in-out, infinita) que se detiene con `prefers-reduced-motion`.
- Fotografía: tonos fríos y nocturnos, sin camas de hotel ni stock evidente; detalle de equipo (poligrafía, CPAP) solo real.
- Lenguaje: descanso, respirar, energía diurna. Nunca "cura" ni "garantizado".
- Se aplica en: pilar `/sueno/`, sus hijas, bloque Sueño del Home y tarjeta de área.

## 7. Fotografía e imagen

- Hasta la sesión profesional (2–4 semanas, `docs/PHOTO_SHOTLIST.md`): marcadores con degradado de marca, nunca stock genérico.
- Formato: WebP/AVIF vía LiteSpeed; retrato 4:5, hero 3:2, artículo 16:9. `width/height` y `loading="lazy"` salvo el hero.
- Logo: SVG saneado (Safe SVG); favicon derivado de `Logo-Favicon.ai` (pendiente de exportar).

## 8. Motion

`fast` 180 ms (hover), `base` 280 ms (apertura de menú, FAQ), `slow` 450 ms (aparición de secciones); curva `ease-out` cubic-bezier(0.2, 0.7, 0.2, 1). Todo a 0 ms con `prefers-reduced-motion`.

## 9. Pendientes para Checkpoint 2

- Decisión del propietario sobre la dirección visual (maquetas en `/preview/`).
- Exportar favicon y logo en SVG desde los originales.
- Confirmar que el kit de Adobe Fonts tiene autorizado `drairinagonzalez.com`.
