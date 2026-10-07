# STATUS — Dra. Irina González Sáez

Última actualización: 2026-10-07 (Claude) · Fuente de cálculo: `PLAN.md`

```
PROYECTO — DRA. IRINA

Avance global
██░░░░░░░░░░░░░░░░░░ 11%

Fase actual
██████████████░░░░░░ 70%
Fase 0/12 — Descubrimiento (Fases 2 y 3 avanzadas en paralelo; 9 tareas en REVIEW)

Estado
🟡 Lote 1 del propietario respondido; esperando briefing de la Dra. y autorización WPVibe
```

## Métricas

| Métrica | Valor |
|---|---|
| Fase | 0/12 |
| Tareas | 8 / 97 DONE · 9 en REVIEW |
| Peso completado | 28 / 253 |
| En curso | 0 |
| Bloqueadas | 1 (red del entorno: bloquea el sitio y casi toda la web) |
| Pendientes del propietario | 4 (autorizar WPVibe, compartir mini app con la Dra., ampliar red del entorno, decisiones OD-004/005/006) |

## Estado por área

```
Discovery        ███████░░░  70%
Auditoría WP     ░░░░░░░░░░   0%
Investigación    ████░░░░░░  40%
Arquitectura     ███░░░░░░░  33%
Desarrollo       █░░░░░░░░░  10%
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

- Codex: auditoría de investigaciones, STRATEGY, SITEMAP, ARCHITECTURE y PLUGINS (`BATON.md`)
- Claude: scaffold de `dra-irina-core` y `irina-gonzalez` creado y en verde con PHPCS (5.1, 5.2, 5.5 DOING; no cuentan hasta probarse en WordPress real)

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
| B2 | Sin canal de operación al WordPress desde cloud (WPVibe descartado; SSH imposible por el proxy TLS). La sesión local "Membretador" tiene la clave SSH pero su clasificador exige autorización del propietario en esa sesión. Opciones: autorizarla allí, ejecutar `tools/deploy/*.sh` desde Git Bash, o Application Password en secretos del entorno para REST | Propietario | Inventario Fase 1, briefing alojado, despliegue de código |

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
