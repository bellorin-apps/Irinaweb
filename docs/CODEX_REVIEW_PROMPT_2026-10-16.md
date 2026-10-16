# Encargo para Codex: cierre del lanzamiento (2026-10-16)

Lo preparó Claude (sesión cloud). Mismo rol y reglas que `docs/CODEX_REVIEW_PROMPT.md` y `AGENTS.md`: veredicto por tarea con
evidencia (`DONE`, `FIX` o `REVIEW` con motivo), QA.md, PLAN.md, PROGRESS.md (`python tools/audit/progress.py`), BATON.md y mensaje
para el otro agente. No despliegas, no publicas ni despublicas, no pruebas el formulario en producción (envía correos reales), no
pegas URLs con clave, no lees secretos y no entras al Perfil de Google ni a Doctoralia. Producción declarada: código hasta d7e14ac
(Q-061 y FAQ e434d2a en vivo, BATON 2026-10-14); después solo hay documentación. Punto de partida: 67/90 DONE, 73,84 %.

Decisiones que prevalecen: D-076 sitio corto · D-077 DESCOPED · D-078 publicación con OK de José tras aprobación médica · D-079
Contacto · D-080 sin staging · D-081 escritorio q95 · D-082 5 fichas de sueño · D-083 menú · D-084 movimiento · D-085 Turnstile ·
D-032 horario oculto («Previa cita»).

Límite de método (Q-059): las pruebas REST desde la nube son anónimas desde la revocación de «claude-ops»; lo que requiera permisos
lo consulta la sesión local por SSH/WP-CLI. Las capturas de la nube no están versionadas: valora la evidencia escrita y repite lo
que puedas medir tú.

## A. Tareas en REVIEW

| Tarea | Qué verificar | Evidencia de partida |
|---|---|---|
| 1.3 Auditoría pública | robots.txt, sitemaps solo con lo publicado, canónicas propias 15/15, index/noindex (gracias, links, preview, briefing), cabeceras | docs/audit/2026-10-15/qa-cierre.md, SECURITY.md, Q-048 |
| 6.7 Responsive y movimiento | 5 anchos (360/390/768/1024/1440) sin desbordes en 15 URL + 404; hero y view transitions desactivados con prefers-reduced-motion; LCP no empeora (Q-050) | qa-cierre.md, D-084, components.css, base.css |
| 6.8 QA visual | Que la evidencia escrita basta o repetir capturas a 390 y 1440 | qa-cierre.md |
| 11.1 QA funcional | Enlaces, 404, WhatsApp/tel/mapa, cookies solo GA4; formulario: prueba humana de José (2026-10-15), no la repitas | qa-cierre.md, LAUNCH_CHECKLIST |
| 11.2 QA dispositivos | Chromium, Edge 155, Firefox 158 (sesión local) y Safari en iPhone real (atestación de José). Android sin prueba explícita: decide si basta Chromium móvil o queda REVIEW | PLAN 11.2, BATON |
| 11.3 QA contenido | NAP idéntico en texto y schema; FAQ sin remitir a páginas de padecimiento; claims solo aprobados; títulos D-049 | qa-cierre.md, build-pages.py |
| 11.5 LAUNCH_CHECKLIST | Que cada ✅ tiene evidencia y que los 👤 (horario de GBP, Doctoralia, legal, aprobación) están explícitos | LAUNCH_CHECKLIST.md |
| 12.4 MAINTENANCE | Rutinas, despliegue y rollback, plan SEO y KPIs coherentes con el sitio corto | MAINTENANCE.md |

## B. Estados posiblemente atrasados (decide con evidencia, no por suposición)

| Tarea | Estado | Por qué revisarla |
|---|---|---|
| 5.3 Fuente única de NAP | TODO | Existe `Settings/PracticeSettings.php` y el schema lo usa, pero Q-042 (NAP repetido en builds) sigue OPEN: ¿DOING con Q-042 como lo pendiente? |
| 5.4 Schema propio sin duplicar | TODO | `Schema/Graph.php` emite el grafo y `strip_rank_math_duplicates` filtra `rank_math/json_ld`; Q-058 verificado |
| 12.1 Deploy con backup y rollback | TODO | Scripts con respaldo en `~/deploy-backups/` y rollback descrito en MAINTENANCE.md; el rollback no se ha probado |

## C. Registro de hoy (solo comprobar coherencia)

- 9.5 Perfil de Google editado por la sesión local con OK de José (QA «Perfil de Negocio», 109a2ae). Sigue OWNER: horario real de
  la Dra. y Doctoralia. Comprueba solo que la URL del sitio y la descripción del perfil coinciden con lo que dice QA y con el NAP
  del sitio; no lo marques DONE.
