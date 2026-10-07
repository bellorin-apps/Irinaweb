# Dra. Irina González Sáez — Sitio web profesional

Repositorio del proyecto de desarrollo del sitio **drairinagonzalez.com** (WordPress híbrido / custom).

## Cómo retomar una sesión (Claude o Codex)

Leer en este orden, siempre:

1. `MASTER_PROMPT.md` — constitución del proyecto (cómo debe funcionar).
2. `STATUS.md` — estado real y métricas.
3. `NEXT.md` — siguiente trabajo y prioridades.
4. `BATON.md` — transferencia operativa Claude ↔ Codex.
5. `DECISIONS.md` — decisiones tomadas.

Después: `PLAN.md` (fases y tareas ponderadas), `DISCOVERY.md` (información confirmada / por confirmar, preguntas, documentos), `docs/` (auditorías e investigación).

## Mapa documental

| Archivo | Propósito |
|---|---|
| `MASTER_PROMPT.md` | Mandato íntegro del propietario, versionado |
| `STATUS.md` | Dashboard y métricas de avance |
| `NEXT.md` | Cola de trabajo priorizada |
| `BATON.md` | Contexto operativo compartido Claude ↔ Codex |
| `DECISIONS.md` | Registro de decisiones (ADR ligero) |
| `CHANGELOG.md` | Cambios por fecha |
| `QA.md` | Hallazgos de Codex y su resolución |
| `SEO.md` | Arquitectura SEO (se completa en Fase 2–3) |
| `PLAN.md` | Fases, tareas, pesos, definición de Done |
| `DISCOVERY.md` | Fase 0: confirmado / por confirmar, preguntas, checklist de documentos |
| `docs/AUDIT_WP.md` | Auditoría del WordPress existente |
| `docs/PHOTO_SHOTLIST.md` | Shot list para sesión fotográfica |

Los entregables finales (`STRATEGY.md`, `REFERENCE_RESEARCH.md`, `SITEMAP.md`, `KEYWORDS.md`, `DESIGN_SYSTEM.md`, `ARCHITECTURE.md`, `PLUGINS.md`, `COMPLIANCE.md`, `PERFORMANCE.md`, `SECURITY.md`, `LAUNCH_CHECKLIST.md`, `MAINTENANCE.md`) se crean en la fase que los produce.

## Estructura prevista del código

```
wp-content/
├── themes/irina-gonzalez/      # child theme de Hello Elementor (presentación)
└── plugins/dra-irina-core/     # plugin propio (CPT, campos, schema, lógica)
```

## Reglas no negociables

- Nunca inventar información médica. Lo no confirmado se marca `[PENDIENTE DE CONFIRMACIÓN]`.
- Nunca guardar contraseñas, tokens ni secretos en este repositorio.
- Nunca cambios destructivos en producción sin backup, staging y rollback.
- La aprobación médica (`MEDICALLY APPROVED`) solo la otorga la Dra. Irina.
