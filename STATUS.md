# STATUS — Dra. Irina González Sáez

Actualizado: 2026-10-10 · Codex · cálculo: PLAN → PROGRESS.

**26,09 % global español: 66/253 pesos, 25/95 tareas DONE.** Solo DONE suma; inglés aparte (0/19). El 31 % anterior mezclaba crédito parcial y un resumen divergente. No se elevó ninguna tarea por pruebas locales.

**Estado operativo:** núcleo público y auditoría general desplegados hasta9053a4b según el relevo de Claude local; nuevo ajuste móvil60/40 preparado y probado localmente, sin despliegue por Codex. Fases 10–11 en revisión; clínica de Fase 7 pendiente de aprobación. Inglés después del lanzamiento español.

## Evidencia actual

- Once rutas públicas en 360/390/430/768/1024/1440: diez 200 y 404 propia; un h1/main; sin desbordes ni excepción JS.
- Portada, Dra., Primera consulta, FAQ, Contacto, legales, Gracias y Links públicas. Gracias/Links noindex y fuera de sitemap. Cabeceras de seguridad presentes.
- Pilares y fichas sin destino público. No se modificaron estados clínicos.
- PHP/PHPCS, builds, sintaxis y regresiones locales pasan; sesión local verificó FAQPage y datos ocultos en producción. Fetchpriority duplicado sigue abierto.
- Escucharte60/40 verificado en producción en muestras360/390. José sigue viendo poco cambio: a360 el retrato se desplazó apenas0,125px respecto de la versión anterior. Q-039 abierto; propuesta estrecha aislada, sin aplicarla al tema. Evidencia en docs/ESCUCHARTE_DISCREPANCIA_MOVIL_2026-10-10.md.
- Cambios previos de cuatro maquetas HTML preservados fuera de nuestros commits.

## Gates pendientes

| Gate | Estado | Responsable |
|---|---|---|
| Despliegue y verificación pública del nuevo60/40 | Pendiente; auditoría general ya desplegada | Claude local |
| REST/Gutenberg y rol médico en WP completo | Pendiente; tests aislados pasan | Sesión local en copia/staging |
| Composición móvil satisfactoria en teléfono real | CSS60/40 público, pero resultado estrecho sigue abierto; propuesta−12px pendiente de revisión | Preguntas por Claude; prueba real por sesión local/José |
| Marcadores de borrador en páginas públicas | Aprobación del bloque por confirmar; Q-043 | Dra./José |
| Fichas médicas | Revisión/aprobación pendientes | Dra. |
| GA4, foto definitiva, cobertura GSC | Dependencias conocidas | José/Dra. |

No se certifica pre-launch completo: faltan gates autenticados, revisión clínica/legal y dispositivos fuera de Chrome. Sin cambios a CDN, Sensia, SMTP ni servidor.

Próximo paso: NEXT. Entrega: BATON. Evidencia: QA y `docs/AUDITORIA_CODEX_2026-10-10.md`.
Ajuste posterior autorizado: altura del retrato limitada en teléfonos <=430px; preparado localmente, pendiente despliegue de Claude y revisión real (ver BATON, portrait-cap.json).
Entrega posterior vigente: silueta calculada contra texto real, sin reducción adicional; CSS/JS listos localmente. Ver BATON. Q-039 abierto hasta despliegue de Claude y revisión en ambos teléfonos.
