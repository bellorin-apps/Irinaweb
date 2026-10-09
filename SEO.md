# SEO — Arquitectura y reglas

Versión 0.2 · 2026-10-08 · Estado: REVIEW. Deriva de `KEYWORDS.md` (keyword map y canibalización), `SITEMAP.md` (URLs) y `ARCHITECTURE.md` (schema). Nada de volúmenes de búsqueda: no se han medido.

## 1. Entidad y objetivo local

- Entidad: **Dra. Irina González Sáez**, `Physician` (+ `MedicalBusiness` del consultorio en CAB Medical, Cumbres 2.º Sector, Monterrey). Fuente única: `PracticeSettings` del plugin; el grafo JSON-LD lo emite solo `dra-irina-core` (Rank Math no emite Person/LocalBusiness/Organization).
- Objetivo local: Monterrey, N.L.; variante de zona «Cumbres» solo en copy del Home, Contacto y GBP (sin páginas por colonia).
- Género: «otorrinolaringólogo» en títulos y H1 (D-OD-001); «otorrinolaringóloga» solo en copy de Home y entidad.

## 2. Keyword → URL (resumen; detalle en `KEYWORDS.md`)

| URL | Keyword primaria | Secundarias (en copy) |
|---|---|---|
| `/` | otorrinolaringólogo en Monterrey | otorrino Monterrey · otorrino Cumbres · otorrinolaringóloga Monterrey |
| `/dra-irina-gonzalez-saez/` | Dra. Irina González Sáez | otorrinolaringólogo certificado Monterrey · especialista en sueño |
| `/otorrinolaringologia/` | otorrinolaringología Monterrey | cuándo ir al otorrino · oído nariz garganta |
| `/sueno/` | especialista en ronquido y apnea del sueño en Monterrey | médico del sueño Monterrey · cirugía de sueño |
| `/sueno/apnea-obstructiva-del-sueno/` | apnea obstructiva del sueño | apnea del sueño Monterrey · SAHOS |
| `/sueno/ronquido/` | ronquido: causas y tratamiento | por qué ronco · cómo dejar de roncar |
| `/sueno/estudio-del-sueno/` | estudio del sueño en Monterrey | poligrafía respiratoria · polisomnografía |
| `/sueno/cirugia-de-ronquido-y-apnea/` | cirugía de ronquido en Monterrey | faringoplastia · suturas barbadas |
| `/padecimientos/*`, `/tratamientos/*` | ver `KEYWORDS.md` §2.5 | — |
| `/primera-consulta/`, `/contacto/`, `/preguntas-frecuentes/` | sin keyword propia | navegacional / servicio |

## 3. Titles y descriptions

Fuente: `tools/content/pages/seo.json` (se cargan como `rank_math_title` / `rank_math_description` con `setup-pages.sh`). Regla: title ≤ 60 caracteres útiles, marca al final con `|`; description 120–155 caracteres con beneficio y lugar; sin «mejor», «cura», «garantizado».

## 4. Técnico

- Canónico `https://drairinagonzalez.com/` (sin www, D-022); `www` y `http` → 301. `otorrino-monterrey.com` (apex y www, http y https) → 301 a la misma ruta en `https://drairinagonzalez.com` en un salto (Q-007 cerrado; regla host-based de `parked-redirect.sh`, Q-012).
- Barra final siempre; slugs sin acentos.
- `noindex`: `/inicio-v2/` (hasta ser portada), `/preview/*`, `/gracias/`, búsqueda, páginas en borrador (no indexables por defecto), `/links/` (D-023).
- Sitemap: Rank Math (`sitemap_index.xml`); excluye noindex y borradores; enviar en Search Console tras CP3 (1.4).
- Robots: permitir todo salvo `/wp-admin/`, `/preview/`, `/briefing/`; `/op/` sin indexar (Sensia gestiona su propio noindex: verificar en Fase 9).
- Open Graph: Rank Math por página; imagen por defecto = retrato (cuando exista la sesión); `og:locale` es_MX.
- Schema por tipo: Home `Physician`+`MedicalBusiness`; entidad `Physician` (sameAs Doctoralia, GBP, redes); condición `MedicalWebPage`+`MedicalCondition`; tratamiento `MedicalWebPage`+`MedicalProcedure`/`MedicalTherapy`/`MedicalTest`; FAQ `FAQPage` solo donde la FAQ sea visible; migas `BreadcrumbList`.
- Migas visibles por template (`irina_breadcrumbs`); el `BreadcrumbList` lo emite Rank Math (configurado) para no duplicar.
- Rendimiento como señal: ver `PERFORMANCE.md` (Fase 10).

## 5. Enlazado interno

Reglas de `SITEMAP.md` §5. Implementación: widget `di-listado` (pilares → hijas), `di_tratamientos_relacionados` / `di_que_resuelve` (condición ↔ tratamiento), `di_relacionados` (lateral), Home → pilares, Dra., primera consulta y contacto.

## 6. Redirecciones

Mapa `OLD → NEW` pendiente de la auditoría de Search Console (1.4). Regla: 301 directo, sin cadenas, nunca todo a Home.

## 7. Estado de indexación conocido

Search Console registrado; alertas históricas de `noindex` y canónica elegida por Google distinta (dominio duplicado, Q-004 resuelto). Revisar cobertura y sitemap tras el cambio de portada (CP3).

## 8. Versión en inglés

Anexo A del MASTER_PROMPT: `/en/`, hreflang recíproco + `x-default` es, keyword map en inglés propio, `inLanguage` en schema. Fase 13; no antes del CP3.
