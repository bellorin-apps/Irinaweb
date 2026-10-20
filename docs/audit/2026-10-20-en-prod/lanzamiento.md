# Lanzamiento de /en/ en producción · 2026-10-20 (sesión local)

**Orden expresa de José** (D-098 → D-103), en el chat local el 20-oct: «Sí, publícala hoy (Recomendado)», en respuesta a la pregunta «¿Publicamos la versión en inglés (/en/) en el sitio real?».

**Condiciones previas** (`docs/GATE_LANZAMIENTO_EN.md` §0), todas cumplidas:
- 13.6 DONE, ratificado por Codex en 98042a1: 8/8 páginas y 5/5 fichas;
- legales aprobados (D-099);
- código desplegado (D-101);
- IndexNow aceptado (D-100).

## Ejecución (repositorio en 98042a1)

1. `publish-en.sh --legal-ok`, solo revisión: **«16 entradas listas para publicar»**, sin bloqueos.
2. `backup-db.sh` → `~/deploy-backups/20261020-154356/db.sql.gz` (452 K, 30 tablas, comprobado).
3. `publish-en.sh --legal-ok --yes`: **las 16 publicadas**. «Success: Versión inglesa publicada.» La verificación del script dio 15/15 con HTTP 200 y alternates y selector en la portada.
4. `wp rankmath sitemap generate` y `wp litespeed-purge all`.

## Verificación final de 13.5

`seo_check.py --base https://drairinagonzalez.com --prod` → **TODAS LAS ASERCIONES PASAN, salida 0** (`seo.log`, `C-seo.json`).

- **HTTP:** las 15 EN dan 200 sin redirección; `/en/contact/` da 200 sin 301.
- **Canónicas y OG:**
  - canónica propia;
  - `og:locale en_US` y `og:url` correctos;
  - `og:image` (og-default) con 200.
- **Hreflang:** `es-MX` / `en-US` / `x-default` recíprocos en los 15 pares. En la portada ES: `{es-MX: /, en-US: /en/, x-default: /}`.
- **Selector:** cada EN apunta a su pareja ES y la portada ES apunta a /en/.
- **Sitemap:** **30** (15 EN + 15 ES), sin Gracias ni Thank you.
- **Schema:**
  - las 15 EN con `inLanguage en-US`;
  - `/en/` con WebSite y WebPage; About con ProfilePage;
  - un solo Physician por página.
- **Thank you:** 200 con `nofollow, noindex`. El 404 en inglés da 404 con `lang en-US`.
- **Enlaces internos:** 30 revisados, 0 rotos.
- **Caché:** cabecera LiteSpeed presente en las 15 (hit o miss).
- **Formulario EN:** lleva `di_lang=en`; el destino lo decide el servidor (`Form.php`, igual que en local). **No se envió ningún formulario.**

## Revisión visual y de interfaz

- **9 páginas** (`/en/`, sleep, ent, about, first-visit, contact, faq, ficha de apnea y la portada ES):
  - todas con 200 y `lang` correcto;
  - menú EN: Home, ENT, Sleep, About, First visit, Contact y Book via WhatsApp;
  - 0 imágenes rotas;
  - Doctoralia apagado;
  - `/en/sleep/` en modo nocturno (`di-dark`);
  - consola sin errores.
- **Escritorio:** captura de `/en/` correcta.
- **Móvil (375 px):**
  - `/en/sleep/` y `/en/ent/` con su foto y el mismo encuadre que en ES, sin desborde horizontal;
  - el fondo de ENT tarda unos 3 s en cargar (carga diferida de Elementor), igual que en `/otorrinolaringologia/`.
- **WhatsApp EN:** «Hello, I would like to book an appointment with Dr. Irina González Sáez.»
- **Servidor:** las 16 entradas en `publish`; no hay `debug.log` ni `error_log`.

## Hallazgo nuevo (no bloquea)

**Q-080 · P3 · OPEN**, textos en español que quedan en la versión inglesa:
- el título del 404 inglés usa el nombre del sitio en español: «Page not found | Dra. Irina González Sáez» (`themes/irina-gonzalez/inc/i18n.php:134`, `get_bloginfo('name')`);
- el `alt` de la foto principal de `/en/first-visit/` es el del adjunto en español: «Dra. Irina González Sáez — Primera consulta».

Ya existían en la copia local. Se encarga a la nube.

## Límites

- IndexNow: Rank Math avisó a los buscadores al publicar; no se deshace (D-100).
- No se probó el formulario ni SMTP en producción.
- La captura visual cubre una muestra, no las 16 rutas en todos los anchos.
