# Encargo para Codex: auditoría de 5 tareas pasadas a REVIEW (2026-10-13)

Lo preparó Claude (sesión cloud) con OK del propietario («Sí, todas»). Mismo rol y reglas que `docs/CODEX_REVIEW_PROMPT.md` y `AGENTS.md`:
veredicto por tarea con evidencia (`DONE`, `FIX` o `REVIEW` con motivo), QA.md, PLAN.md, PROGRESS.md (`python tools/audit/progress.py`),
BATON.md y mensaje para el otro agente. No despliegas; no pruebas el formulario en producción (envía correos reales).

| Tarea | Qué verificar | Evidencia de partida |
|---|---|---|
| 6.4 Página Dra. | 200, un h1/main, sin «Borrador», títulos D-049, schema Physician/ProfilePage, migas «Conóceme» (D-071), logos con alt/tooltip y teclado (D-068), «Escucharte» sin solape ni scroll horizontal (D-069), reduced-motion | Q-039 VERIFIED; D-066/D-068/D-069/D-071; BATON 2026-10-12/13 |
| 7.3 Primera consulta, Contacto, FAQ | 200, sin «Borrador» (Q-043), títulos dictados, FAQPage solo en FAQ, fondo continuo (is-spanned) y pasos ilustrados; decidir si «pacientes» queda cubierto | build-pages.py, setup-pages.sh, BATON |
| 8.9 Favicon y OG | `og:image`/`twitter:image` JPEG 1200×630 en todas las públicas; favicon/apple-touch/manifest | D-031, D-067, `tools/deploy/setup-og.sh` |
| 9.4 GA4 y eventos | Un gtag con G-5961XX8QZ7, carga tras «load», DNT, exclusión de editores, 6 eventos; Search Console solo si José aporta la verificación | D-052, D-073, `src/Tracking/Events.php` |
| 10.1 Rendimiento | Versión móvil q95 e irina-movil servida en ≤899 px con `?m=`; escritorio q100; PNG con alfa sin pérdida; un solo fetchpriority; pesos transferidos con UA real (D-062) | D-063/D-064/D-073/D-074; QA Q-025/Q-046/Q-047 |

Tarea 1.2 sigue en REVIEW hasta que la sesión local suba `docs/audit/2026-10-13/medios.csv` (lista de los 44 adjuntos con uso y la
propuesta KEEP/REPLACE/REMOVE de Claude, aprobada por José) y las entradas del pie/legal. No borrar ni mover medios.
