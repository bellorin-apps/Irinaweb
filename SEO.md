# SEO — Arquitectura SEO

Estado: esqueleto (se completa en Fases 2–3 y 9). No asumir volúmenes de búsqueda; investigar.

## 1. Objetivo local

Monterrey, Nuevo León (ubicación real del consultorio, POR CONFIRMAR: Cumbres 2.º Sector).

## 2. Entidad principal

- Persona/Physician: Dra. Irina González Sáez.
- Especialidad: Otorrinolaringología; subespecialización en desórdenes respiratorios del dormir, ronquido y rinología aplicada (según CV, POR CONFIRMAR).
- Página de entidad: `/dra-irina-gonzalez-saez/` (propuesta).

## 3. Intenciones a investigar (sin asumir volumen)

- otorrinolaringólogo / otorrino / otorrinolaringóloga Monterrey
- especialista en sueño / apnea del sueño / ronquido Monterrey
- médico del sueño / clínica del sueño Monterrey
- condiciones ORL frecuentes (a definir con los motivos de consulta reales)

## 4. Keyword map

`SEARCH_INTENT → URL → PRIMARY KEYWORD → SECONDARY → ENTITY → CTA`

_(Se completa en `KEYWORDS.md`, Fase 2.3.)_

## 5. Clusters

- Pilar ORL → condiciones/tratamientos reales.
- Pilar Sueño → apnea, ronquido, trastornos respiratorios del dormir, diagnóstico, estudios, tratamiento (solo lo que la Dra. OFRECE).

## 6. Técnico

- HTTPS, www/no-www, trailing slash, canonicals, robots, sitemap, noindex de staging/gracias/404.
- Mapa `OLD URL → NEW URL → 301` tras auditoría de indexación (Fase 1.4).
- Schema map por tipo de página (Fase 3.4). Un solo emisor de JSON-LD.

## 7. Estado conocido de indexación

- Propiedad verificada en Search Console (correos de alertas, sep-2026).
- Sitemap enviado (alerta "páginas de un sitemap").
- Exclusiones: `noindex` y "Duplicada: Google ha elegido una canónica diferente". Detalle pendiente (correos en papelera, no accesibles por API).
