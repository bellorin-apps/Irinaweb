# STATUS — Dra. Irina González Sáez

Última actualización: 2026-10-08 (Claude) · Fuente de cálculo: `PLAN.md`

```
PROYECTO — DRA. IRINA

Avance global
████░░░░░░░░░░░░░░░░ 21%

Fase actual
█████░░░░░ 55%
Fase 6/12 — Build del núcleo visual en WordPress (CP2 cerrado; Fase 4 en REVIEW de Codex)

Estado
🟢 CP2 cerrado con la dirección v2; empieza la construcción en WordPress
```

## Métricas

| Métrica | Valor |
|---|---|
| Fase | 6/12 |
| Tareas | 13 / 97 DONE · 20 en REVIEW · 5 DOING |
| Peso completado | 54 / 253 |
| En curso | 3 (5.1, 5.2, 5.5 infraestructura) |
| Bloqueadas | 0 |
| Pendientes del propietario | 2 (licencia Elementor Pro, aviso de privacidad de la Dra.) |

## Estado por área

```
Discovery        ██████████ 100%
Auditoría WP     ████░░░░░░  30%
Investigación    █████░░░░░  46%
Arquitectura     ████░░░░░░  38%
Diseño           ██████░░░░  55%
Desarrollo       ░░░░░░░░░░   0%  (scaffold en DOING; cuenta al probarse)
Contenido        ░░░░░░░░░░   0%
SEO              ██░░░░░░░░  20%
QA               ░░░░░░░░░░   0%
Launch           ░░░░░░░░░░   0%
```

## LISTO

- Checkpoint 1 cerrado: briefing de la Dra. confirmado (80 respuestas)
- WordPress limpio en `drairinagonzalez.com` (canónico sin www); Sensia intacta en `/op`
- Plugin `dra-irina-core` 0.2.1 y tema `irina-gonzalez` activos; datos del consultorio, cédulas, horario y redes cargados; Physician JSON-LD en vivo
- Rank Math configurado; Elementor + Pro instalados; PHP 8.4; Adobe Fonts (Iskra) en el tema
- Estrategia, sitemap, keywords, arquitectura y plugins v0.1 (REVIEW)
- Sistema visual: tokens, componentes, sub-marca Sueño y `DESIGN_SYSTEM.md` v0.1 (REVIEW)
- Maquetas Home, Dra. Irina, página médica (apnea) y artículo, responsive, en `tools/preview/`

## AHORA

- Claude: Fase 6 en WordPress (header, footer, Home, plantillas) con el sistema v2
- Codex: auditorías pendientes según `BATON.md`

## FALTA

- Fase 5: meta boxes con repetidores, widgets Elementor, pruebas en WP real, auditoría Codex
- Fase 6: header/footer/Home/templates reales en WordPress (tras CP2)
- Contenido médico, SEO on-page, legal, performance, QA, launch

## Bloqueos

| ID | Bloqueo | Tipo | Desbloquea |
|---|---|---|---|
| — | Ninguno activo | | |

## Riesgo de entrega

🟡 Medio — falta la sesión fotográfica profesional (2 a 4 semanas) y la licencia de Elementor Pro sin conectar; el resto de dependencias está cubierto.

## Entrega estimada

Núcleo visual en WordPress (Checkpoint 3) en 2 a 3 semanas desde la aprobación del CP2; sitio completo listo para pre-launch en 6 a 8 semanas, condicionado a fotos y revisión médica. Confianza: Media.

## Dependencias externas (no cuentan como trabajo técnico)

- Sesión fotográfica profesional
- Aviso de privacidad y Aviso de Publicidad de la Dra.
- Revisión médica de contenidos (Fase 7)
