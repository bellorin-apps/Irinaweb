# QA — Hallazgos y resolución

Clasificación: BLOCKER · CRITICAL · HIGH · MEDIUM · LOW · SUGGESTION
Estados: OPEN · FIXING · FIXED · VERIFIED · WONTFIX (con motivo)

## Auditorías de Codex

| # | Fecha | Alcance | Hallazgos | Estado |
|---|---|---|---|---|
| — | — | Pendiente primera auditoría (ver `BATON.md`) | — | — |

## Hallazgos

| ID | Severidad | Área | Descripción | Detectado por | Estado | Resolución |
|---|---|---|---|---|---|---|
| Q-001 | HIGH | SEO | Search Console reporta páginas excluidas por `noindex` y por canónica duplicada en drairinagonzalez.com (alertas de sep-2026). Puede significar que el sitio "en construcción" tiene URLs conocidas por Google. | Claude (correo de Search Console) | OPEN | Verificar en Fase 1.4 qué URLs están afectadas y planear mapa 301/noindex correcto |
| Q-002 | MEDIUM | Marca/Contenido | El logotipo y las tarjetas usan "OTORRINOLARINGÓLOGO" (masculino). Impacta en consistencia de textos y SEO. | Claude | OPEN | OD-001 en `DECISIONS.md` |
| Q-003 | MEDIUM | Multimedia | No existe fotografía profesional utilizable de la Dra. para el sitio. | Claude (subagente de revisión de assets) | OPEN | `docs/PHOTO_SHOTLIST.md` |

## QA visual

_(Fase 6 en adelante.)_

## QA funcional / dispositivos / SEO / performance / seguridad / contenido

_(Fase 11.)_
