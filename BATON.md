# BATON — Transferencia operativa Claude ↔ Codex

Última actualización: 2026-10-07 (segunda entrega) · Entrega: Claude → Codex

## Contexto operativo

- Proyecto: sitio web de la Dra. Irina González Sáez (ORL + trastornos respiratorios del sueño), Monterrey.
- Dominio existente: `https://drairinagonzalez.com` (WordPress, título "Dra. Irina González Sáez"). Registrado en Google Search Console; existe sitemap enviado y alertas de cobertura (páginas excluidas por `noindex` y por canónica elegida por Google distinta). Usuario WP `irina` creado el 2026-10-06.
- Hosting: probablemente Hostinger (correo de verificación de cuenta en agosto 2026) — **POR CONFIRMAR**. El otro sitio del propietario (idenbauer.com) corre Hello Elementor 3.4.9 + Elementor Pro + Fluent Forms + FluentSMTP + LiteSpeed Cache + Rank Math + Safe SVG, lo que sugiere el stack y licencias disponibles.
- Repositorio: `bellorin-apps/Irinaweb`, rama de trabajo `ccr-6254502d-xly8ww`. Aún no contiene código WordPress; solo documentación de Fase 0.
- Entorno de ejecución de Claude: contenedor cloud con PHP 8.3, Node 22, Composer, Git. Red de salida restringida: `drairinagonzalez.com` y `doctoralia.com.mx` denegados por política del entorno.

## Qué hizo Claude en esta entrega

1. Creó la base documental: `MASTER_PROMPT.md` (v1.0), `README.md`, `PLAN.md`, `STATUS.md`, `NEXT.md`, `BATON.md`, `DECISIONS.md`, `CHANGELOG.md`, `QA.md`, `SEO.md`, `DISCOVERY.md`, `docs/AUDIT_WP.md`, `docs/PHOTO_SHOTLIST.md`.
2. Recolectó información existente con fuente verificable (Drive del propietario, correo, búsqueda pública). Ningún dato se inventó; todo lo no confirmado por la Dra. está marcado.
3. Extrajo la identidad visual existente (logo vectorial, paleta, tarjetas).
4. Detectó que la única sesión fotográfica disponible (2022) es personal y no utilizable; preparó shot list.

## Archivos tocados

Todos los `.md` de raíz y `docs/`. Sin código todavía.

## Riesgos conocidos

- Datos de contacto y dirección provienen de tarjetas de presentación diseñadas por el propietario (2025) y coinciden con Doctoralia, pero **no están confirmados expresamente por la Dra.** No publicar hasta confirmación.
- Certificación del Consejo Mexicano de ORL y CCC: acreditación del examen (febrero 2025) con diploma anunciado para mayo 2025. Falta ver el diploma/vigencia y número.
- Cédula profesional mexicana: trámite iniciado en marzo 2025; estado desconocido.

## Segunda entrega (Fase 2 parcial)

Tres investigaciones producidas con subagentes y verificadas por Claude en su forma (no en su contenido visual, porque la red del entorno bloqueó todos los dominios de referencia):

- `docs/REFERENCE_RESEARCH.md` — cada afirmación marcada [S] (snippet/fuente secundaria) o [I] (inferencia a verificar); Anexo B con checklist de verificación visual.
- `docs/COMPETITION_MONTERREY.md` — 10 competidores ORL + 6 actores de sueño; 31 ítems marcados "no verificado".
- `KEYWORDS.md` — keyword map sin volúmenes; evidencia SERP; 11 reglas anticanibalización; títulos propuestos.

## Pedido a Codex

- Auditar `PLAN.md`: ¿la ponderación es razonable? ¿Falta alguna tarea exigida por el MASTER_PROMPT?
- Auditar `DISCOVERY.md`: comprobar que cada dato "CONFIRMADO POR DOCUMENTO" cite su fuente y que nada se haya elevado a confirmado sin respaldo.
- Auditar las tres investigaciones de Fase 2: afirmaciones sin fuente presentadas como hechos, volúmenes inventados, URLs propuestas que canibalicen, recomendaciones que contradigan el MASTER_PROMPT (p. ej. páginas doorway, keyword stuffing, testimonios fabricados).
- Clasificar hallazgos (BLOCKER/CRITICAL/HIGH/MEDIUM/LOW/SUGGESTION) en `QA.md`.

## Criterios de aceptación de esta entrega

- Documentación legible por un tercero sin contexto de chat.
- Ningún secreto en el repositorio.
- Ningún dato médico/profesional presentado como confirmado sin fuente.
