# OD-008 — Herramienta para la versión en inglés: propuesta para que decida José

Creado: 2026-10-16 · Claude cloud (D-092, PLAN 13.1). Alcance (Anexo A): unas 10 páginas base, hasta 5 fichas de sueño que elija la Dra., legales, `/en/`, `hreflang`, selector de idioma, schema y sitemap por idioma.

**Prioridades del Anexo A:**
- no romper Sensia (`/op`);
- no romper el schema único (Graph propio + filtro de Rank Math);
- no empeorar el rendimiento.

**Además:**
- puerta médica igual que en español;
- Elementor como capa editorial;
- builds en Python que generan el JSON.

## Opciones

| | (a) Nativo en `dra-irina-core` | (b) Polylang (gratis) | (c) WPML (de pago) |
|---|---|---|---|
| **Cómo** | Páginas en inglés como hijas de una página «en» (URL `/en/...` sin reglas nuevas); meta `di_lang` y `di_translation_of` para emparejar es↔en; `locale` en_US en `/en/` para los textos del tema y el plugin (.po/.mo); hreflang, selector e `inLanguage` en el plugin | Plugin estándar: idioma por entrada (taxonomía), `/en/` por directorio, hreflang, selector y traducción de textos | Plugin comercial completo, con traducción de Elementor y de cadenas |
| **Costo** | Gratis (solo desarrollo) | Gratis | ~99 USD al año |
| **Peso / rendimiento** | Mínimo: unas pocas funciones, sin tablas ni consultas extra | Ligero, pero filtra todas las consultas por idioma | Pesado: tablas propias y más consultas |
| **Riesgo para Sensia y nuestro código** | Bajo: no altera las consultas globales | Medio: el filtro de idioma afecta a WP_Query, menús y CPT; hay que revisar Sensia, irina_url_if_published, migas y la 301 de /sueno/ | Medio-alto: mismo filtro, más integración |
| **Schema único** | Nuestro Graph añade `inLanguage` y los enlaces entre traducciones; sin choque | Rank Math + Polylang: hay que comprobar los duplicados que hoy filtramos | Similar a (b) |
| **Elementor** | Igual que hoy: cada página en inglés es una página con su JSON generado por un build `build-pages-en.py` | Funciona; con la versión gratis se duplican plantillas a mano | Mejor integración (traduce el widget) |
| **Puerta médica** | Las fichas en inglés son CPT con el mismo flujo de aprobación | Funciona, pero hay que comprobar que la traducción hereda el estado médico | Ídem |
| **Trabajo inicial** | Mayor: unas 2–3 jornadas de código (hreflang, selector, locale, sitemap) | Menor: configurar y adaptar | Menor, más la licencia |
| **Mantenimiento** | Nuestro: probado con lo que ya hay (lint, PHPCS, Codex) | Actualizaciones del plugin (Hostinger las aplica solo) | Actualizaciones y renovación |
| **Si se abandona** | Se borran las páginas `/en/` y se quita el módulo | Desinstalar deja taxonomías y metas | Ídem |

## Recomendación de Claude: (a) nativo

Es la misma que la provisional de OD-008, ahora con más evidencia.

1. **El alcance es pequeño:** 10–15 páginas. No compensa añadir un plugin que filtra todas las consultas del sitio.
2. **Protege lo que ya funciona:**
   - Sensia (`/op`);
   - la puerta médica;
   - el schema único (Q-058);
   - la 301 de `/sueno/{slug}/`;
   - los enlaces solo a páginas publicadas;
   - los sitemaps sin archivos.

   Polylang o WPML obligarían a volver a probar todo eso.
3. **Encaja con el método actual:** los builds generan el JSON, setup-pages lo carga y Codex audita. Las páginas en inglés serían otro build.
4. **Rendimiento:** no añade tablas ni consultas.

**Costo de (a):** desarrollo propio de hreflang, selector, cambio de locale y sitemap. Cada pieza se puede probar por separado y queda en el repo con su auditoría.

## Antes de redactar (13.3), José y la Dra. confirman

1. 👤 **Herramienta:** (a), (b) o (c).
2. 👩‍⚕️ **¿La Dra. atiende consultas en inglés?** Cambia el mensaje principal (DISCOVERY: «Idiomas de consulta: PENDIENTE»).
3. 👩‍⚕️ **Qué fichas** se traducen (13.4). Propuesta: las 5 publicadas de sueño.
4. 👤 **Quién revisa la traducción:** la Dra. u otra persona que ella designe (Anexo A).
5. 👤 **Fecha:** Claude propone tenerlo listo para revisión de la Dra. unas 2 semanas después de la decisión. La fecha se fija en NEXT.md cuando José la confirme.
