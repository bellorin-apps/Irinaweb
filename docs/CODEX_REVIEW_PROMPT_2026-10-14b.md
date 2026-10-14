# Encargo para Codex: segunda ronda del 2026-10-14 (auditorías «Codex:» y bloque SEO/a11y/seguridad)

Lo preparó Claude (sesión cloud) a petición de José. Mismo rol y reglas que `docs/CODEX_REVIEW_PROMPT.md` y `AGENTS.md`: veredicto
por tarea con evidencia (`DONE`, `FIX` o `REVIEW` con motivo), QA.md, PLAN.md, PROGRESS.md (`python tools/audit/progress.py`),
BATON.md y mensaje para el otro agente. No despliegas, no publicas ni despublicas, no pruebas el formulario en producción (envía
correos reales), no pegas URLs con clave y no lees secretos. Producción declarada: hasta edf8c45 (BATON 2026-10-14). Punto de partida:
54/90 DONE, 60,76 %.

Decisiones recientes que prevalecen sobre documentos anteriores: D-076 sitio corto · D-077 DESCOPED · D-078 publicación con OK de José
tras aprobación médica · D-079 Contacto · D-080 sin staging · D-081 escritorio q95 · D-082 5 fichas de sueño · D-083 menú «Otorrino» y
«Sueño» · D-084 movimiento · D-085 Turnstile.

**Aviso de método (Q-059):** las peticiones REST que salen del entorno cloud llevan la contraseña de aplicación «claude-ops» del
administrador. Si tu entorno también sale por ahí, no tomes un 200 de la API como prueba de exposición anónima; usa la evidencia de la
sesión local o pídele la prueba. **Actualización 2026-10-14 17:55:** José revocó «claude-ops»; ya no hay canal REST de admin. Toda prueba REST es de visitante anónimo; lo que exija permisos lo consulta la sesión local por SSH/WP-CLI.

## A. Auditorías «Codex:» pendientes

| Tarea | Qué verificar | Evidencia de partida |
|---|---|---|
| 1.9 Seguridad del estado actual | Que SECURITY.md describe lo que hay en producción; cabeceras, versiones ocultas, enumeración, XML-RPC, API privilegiada, 2FA instalado, secretos fuera de Git | SECURITY.md, Q-023, Q-053, Q-054, Q-059 |
| 2.6 Estrategia y keyword map | Coherencia de STRATEGY.md y KEYWORDS.md con el sitio corto (D-076/D-082): qué keywords cubren las 2 páginas de área y las 5 fichas; ajustar o marcar lo que quedó fuera de alcance | STRATEGY.md, KEYWORDS.md, seo.json |
| 3.8 Arquitectura vs MASTER_PROMPT | ARCHITECTURE.md y SITEMAP.md frente al sitio real (páginas, CPT, permalinks /sueno/{slug}/, archivos en 404, mini apps) | ARCHITECTURE.md, SITEMAP.md, setup.php, MetaRegistry |
| 5.7 Código PHP/JS/CSS/seguridad | PHPCS del tema y el plugin, escapes, nonces, permisos REST propios (dra-irina/v1), SpamGuard (falsos positivos), Hardening, Turnstile, main.js (menú, carrusel, trust), revisar.php | vendor/bin/phpcs, src/, assets/ |
| 6.9 Núcleo | Plantillas y widgets di-* en uso tras D-076; restos sin uso; migas, listado con enlaces solo a publicadas | templates/, Rendering/Elementor/ |
| 9.6 QA SEO | Titles/descriptions dentro de límites, canónicas, robots, sitemaps (sin archivos 404, sin recursos), 301 de rutas de tipo a /sueno/, schema | bc65946, c7bb5b5, Q-048, Q-058 |
| 10.5 QA rendimiento + seguridad + accesibilidad | PERFORMANCE.md (antes/después), SECURITY.md, axe y Lighthouse a11y | PERFORMANCE.md, cae491d, SECURITY.md |
| 11.4 Consistencia vs MASTER_PROMPT | Documentos al día con D-076…D-085; NEXT/STATUS/BATON coherentes | todos los .md raíz |

## B. Tareas DOING trabajadas hoy (pueden pasar a DONE con evidencia)

| Tarea | Qué verificar | Evidencia |
|---|---|---|
| 9.1 On-page | 15 URL: un h1, canónica propia, títulos ≤62, descripciones ≤158, OG | bc65946; QA de la sesión local |
| 9.2 Sitemap, robots, 301 | sitemap_index (page, condicion, tratamiento), robots.txt, 301 del dominio aparcado y de /padecimientos/{slug} y /tratamientos/{slug} a /sueno/{slug}/ | Q-012, Q-048, D-082 |
| 9.3 Schema | Physician+Person, ProfilePage, ContactPage, FAQPage, MedicalWebPage (fichas y páginas de área), BreadcrumbList; validator.schema.org 0/0 | c7bb5b5, Q-058 |
| 10.3 Hardening | SECURITY.md completo; 2FA activado por José o pendiente explícito | SECURITY.md (nuevo), Q-053, Q-054 |
| 10.4 Accesibilidad | axe WCAG 2.2 AA sin violaciones salvo el falso positivo medido; Lighthouse a11y 100 en Contacto, CPAP y Dra.; menú con teclado (Q-056). El lector de pantalla manual queda pendiente por decisión de José | cae491d, edf8c45 |

Hallazgos que puedes cerrar si los confirmas: Q-058 (verificado) y Q-059 (INVALID, ver arriba).
