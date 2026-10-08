# STATUS — Dra. Irina González Sáez

Última actualización: 2026-10-07 (Claude) · Fuente de cálculo: `PLAN.md`

```
PROYECTO — DRA. IRINA

Avance global
███░░░░░░░░░░░░░░░░░ 17%

Fase actual
█████████░ 90%
Fase 1/12 — Acceso, auditoría y limpieza (Fase 0 cerrada; Fases 2, 3 y 5 avanzadas)

Estado
🟢 Fase 1 en marcha: backup, dominio, inventario y briefing desplegado; falta canal REST desde cloud (secreto de red) para la limpieza
```

## Métricas

| Métrica | Valor |
|---|---|
| Fase | 1/12 |
| Tareas | 12 / 97 DONE · 14 en REVIEW |
| Peso completado | 43 / 253 |
| En curso | 0 |
| Bloqueadas | 1 (red del entorno: bloquea el sitio y casi toda la web) |
| Pendientes del propietario | 4 (autorizar WPVibe, compartir mini app con la Dra., ampliar red del entorno, decisiones OD-004/005/006) |

## Estado por área

```
Discovery        ██████████ 100%
Auditoría WP     ████████░░  80%
Investigación    ████░░░░░░  40%
Arquitectura     ███░░░░░░░  33%
Desarrollo       ██░░░░░░░░  15%
Arquitectura     ░░░░░░░░░░   0%
Diseño           ░░░░░░░░░░   0%
Desarrollo       ░░░░░░░░░░   0%
Contenido        ░░░░░░░░░░   0%
SEO              ██░░░░░░░░  20%
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

- Checkpoint 1 cerrado: briefing de la Dra. confirmado (80 respuestas)
- Plugin 0.2.1 y tema activos; datos del consultorio cargados; Rank Math configurado; Elementor Pro activo (licencia pendiente)
- Siguiente: Fase 4 (sistema visual y mockups del núcleo) hacia Checkpoint 2
- Esperando acceso técnico al WordPress para iniciar Fase 1

## FALTA

- Auditoría técnica WP + backup + staging
- Investigación y estrategia
- Arquitectura, diseño, desarrollo, contenido, SEO, QA, launch

## Bloqueos

| ID | Bloqueo | Tipo | Desbloquea |
|---|---|---|---|
| B1 | La política de red del entorno (nivel Limited) deniega `drairinagonzalez.com`, Doctoralia, wordpress.org, Google Fonts, Awwwards y todos los sitios de referencia; solo pasan buscadores. Recomendación: subir el nivel de acceso de red del entorno, no solo añadir dominios | Propietario (configuración del entorno) | Fase 1 y verificación visual de la investigación |
| B2 | ~~Sin canal al servidor~~ RESUELTO: sesión local "Irinaweb servidor deployment" con SSH + WP-CLI. Pendiente solo el canal REST desde cloud (secreto de red Basic + acceso a red amplio) | Propietario | Limpieza y configuración por REST desde cloud |

## Riesgo de entrega

🟡 Medio — falta la sesión fotográfica profesional (prevista en 2 a 4 semanas); el resto de dependencias del propietario está cubierto.

## Entrega estimada

Ventana preliminar: núcleo visual (Checkpoint 3) en 2 a 3 semanas de trabajo; sitio completo listo para pre-launch en 6 a 8 semanas, condicionado a fotos y revisión médica de contenidos. Confianza: Baja (sin historial de velocidad suficiente).

## Dependencias externas (no cuentan como trabajo técnico)

- Respuestas del cuestionario (lote 1)
- Confirmación de la Dra. Irina sobre credenciales y servicios
- Sesión fotográfica profesional
- Revisión legal/regulatoria
- Accesos: hosting, Search Console, GBP, Doctoralia, licencias
