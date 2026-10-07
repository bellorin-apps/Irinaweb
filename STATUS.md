# STATUS — Dra. Irina González Sáez

Última actualización: 2026-10-07 (Claude) · Fuente de cálculo: `PLAN.md`

```
PROYECTO — DRA. IRINA

Avance global
██░░░░░░░░░░░░░░░░░░ 8%

Fase actual
██████████████░░░░░░ 70%
Fase 0/12 — Descubrimiento (Fase 2 iniciada en paralelo: 3 investigaciones en REVIEW)

Estado
🟡 Lote 1 del propietario respondido; esperando briefing de la Dra. y autorización WPVibe
```

## Métricas

| Métrica | Valor |
|---|---|
| Fase | 0/12 |
| Tareas | 8 / 97 DONE · 3 en REVIEW |
| Peso completado | 20 / 253 |
| En curso | 0 |
| Bloqueadas | 1 (red del entorno: bloquea el sitio y casi toda la web) |
| Pendientes del propietario | 3 (autorizar WPVibe, compartir mini app con la Dra., permitir dominio en la red del entorno) |

## Estado por área

```
Discovery        ███████░░░  70%
Auditoría WP     ░░░░░░░░░░   0%
Investigación    ████░░░░░░  40%
Arquitectura     ░░░░░░░░░░   0%
Diseño           ░░░░░░░░░░   0%
Desarrollo       ░░░░░░░░░░   0%
Contenido        ░░░░░░░░░░   0%
SEO              ░░░░░░░░░░   0%
QA               ░░░░░░░░░░   0%
Launch           ░░░░░░░░░░   0%
```

## LISTO

- Repositorio y base documental
- MASTER_PROMPT v1.0
- Recolección de información existente (CV, título, consejo, tarjetas, logo, dominio, Search Console)
- Cuestionario de discovery y checklist de documentos
- Plan maestro ponderado (13 fases, 97 tareas)

## AHORA

- Codex: auditoría de las tres investigaciones (`BATON.md`)
- Claude: `ARCHITECTURE.md`, `SITEMAP.md` y content model a partir de KEYWORDS.md

- Briefing de la Dra. (mini app publicada) para cerrar Checkpoint 1
- Esperando acceso técnico al WordPress para iniciar Fase 1

## FALTA

- Auditoría técnica WP + backup + staging
- Investigación y estrategia
- Arquitectura, diseño, desarrollo, contenido, SEO, QA, launch

## Bloqueos

| ID | Bloqueo | Tipo | Desbloquea |
|---|---|---|---|
| B1 | La política de red del entorno (nivel Limited) deniega `drairinagonzalez.com`, Doctoralia, wordpress.org, Google Fonts, Awwwards y todos los sitios de referencia; solo pasan buscadores. Recomendación: subir el nivel de acceso de red del entorno, no solo añadir dominios | Propietario (configuración del entorno) | Fase 1 y verificación visual de la investigación |
| B2 | Conexión WPVibe no autorizada (link de un clic en wp-admin) | Propietario (dijo que la autorizará) | Lectura/escritura WP vía REST y WP-CLI |

## Riesgo de entrega

🟡 Medio — no hay fotografía profesional utilizable de la Dra. (la sesión de 2022 es personal) y falta confirmación médica de credenciales/servicios.

## Entrega estimada

Aún no confiable. Motivo: faltan respuestas del Checkpoint 1, acceso técnico al WordPress y fotografía.

## Dependencias externas (no cuentan como trabajo técnico)

- Respuestas del cuestionario (lote 1)
- Confirmación de la Dra. Irina sobre credenciales y servicios
- Sesión fotográfica profesional
- Revisión legal/regulatoria
- Accesos: hosting, Search Console, GBP, Doctoralia, licencias
