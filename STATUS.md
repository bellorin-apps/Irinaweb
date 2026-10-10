# STATUS — Dra. Irina González Sáez

Actualizado: 2026-10-10 · Codex · cálculo: PLAN → PROGRESS.

**26,09 % global español: 66/253 pesos, 25/95 tareas DONE.** Solo DONE suma; inglés aparte (0/19). El 31 % anterior mezclaba crédito parcial y un resumen divergente. No se elevó ninguna tarea por pruebas locales.

**Estado operativo:** núcleo público en producción; auditoría de seguridad, SEO y móvil preparada en la rama, sin desplegar. Fases 10–11 en revisión; clínica de Fase 7 pendiente de aprobación. Inglés después del lanzamiento español.

## Evidencia actual

- Once rutas públicas en 360/390/430/768/1024/1440: diez 200 y 404 propia; un h1/main; sin desbordes ni excepción JS.
- Portada, Dra., Primera consulta, FAQ, Contacto, legales, Gracias y Links públicas. Gracias/Links noindex y fuera de sitemap. Cabeceras de seguridad presentes.
- Pilares y fichas sin destino público. No se modificaron estados clínicos.
- PHP/PHPCS, builds, sintaxis y regresiones locales pasan; FAQPage y parches de seguridad esperan despliegue.
- Escucharte: D-058 establece60/40 y foto−5px respecto de la división; rostro parcial aceptado. CSS implementado, pruebas locales de columnas/texto/tres tarjetas pasan; sin desplegar. Evidencia en docs/ESCUCHARTE_60_40_2026-10-10.md.
- Cambios previos de cuatro maquetas HTML preservados fuera de nuestros commits.

## Gates pendientes

| Gate | Estado | Responsable |
|---|---|---|
| Despliegue y verificación pública | Pendiente | Claude local, con OK de José |
| REST/Gutenberg y rol médico en WP completo | Pendiente; tests aislados pasan | Sesión local en copia/staging |
| Composición60/40 con figura grande y párrafo legible | FIXED_LOCAL según D-058; producción/revisión visual pendientes | Claude local despliega; José revisa; preguntas por Claude |
| Marcadores de borrador en páginas públicas | Aprobación del bloque por confirmar; Q-043 | Dra./José |
| Fichas médicas | Revisión/aprobación pendientes | Dra. |
| GA4, foto definitiva, cobertura GSC | Dependencias conocidas | José/Dra. |

No se certifica pre-launch completo: faltan gates autenticados, revisión clínica/legal y dispositivos fuera de Chrome. Sin cambios a CDN, Sensia, SMTP ni servidor.

Próximo paso: NEXT. Entrega: BATON. Evidencia: QA y `docs/AUDITORIA_CODEX_2026-10-10.md`.
