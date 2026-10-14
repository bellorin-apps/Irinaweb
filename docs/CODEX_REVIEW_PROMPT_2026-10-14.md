# Encargo para Codex: auditoría de las tareas en REVIEW (2026-10-14)

Lo preparó Claude (sesión cloud). Mismo rol y reglas que `docs/CODEX_REVIEW_PROMPT.md` y `AGENTS.md`: veredicto por tarea con
evidencia (`DONE`, `FIX` o `REVIEW` con motivo), QA.md, PLAN.md, PROGRESS.md (`python tools/audit/progress.py`), BATON.md y mensaje
para el otro agente. No despliegas; no pruebas el formulario en producción (envía correos reales); no pegas URLs con clave; no
publicas ni despublicas nada. Producción declarada por la sesión local: hasta 73f04d6 (BATON 2026-10-14 14:39).

Cambios de alcance que debes aplicar al auditar (decisiones del propietario, prevalecen sobre documentos anteriores):
D-076 sitio corto · D-077 estado DESCOPED (no entra en el total) · D-078 publicación con OK de José tras la aprobación médica ·
D-079 Contacto con «previa cita» · D-080 sin staging · D-081 escritorio q95 en fotos de cabecera y fondo de Escucharte ·
D-082 5 fichas de sueño publicadas, 21 en borrador · D-083 menú «Otorrino» y «Sueño» · D-084 entrada del hero y transición entre páginas ·
D-085 Turnstile activo (clave secreta sin rotar por decisión de José).

| Tarea | Qué verificar | Evidencia de partida |
|---|---|---|
| 1.2 Inventario y medios | medios.csv/medios.md completos, limpieza D-075 con respaldo, ninguna página rota | docs/audit/2026-10-13/, BATON 16:40 |
| 1.4 Search Console | Capturas de José resumidas en PLAN: 5 motivos explicados, sitemap «Correcto» y reenviado; no inferir cobertura actual más allá de lo aportado | PLAN 1.4, chat cloud |
| 6.5 Página médica | /sueno/{slug}/ de las 5 fichas: 200, h1/main, sin «Borrador», schema, migas sin archivo (también BreadcrumbList), 301 desde /padecimientos/ y /tratamientos/, relacionados solo publicados; 10 de las 21 en borrador dan 404 | medical-page.php, setup.php, MetaRegistry::area_permalink |
| 7.1 ORL y Sueño | Páginas cortas publicadas; anclas #oido #nariz #garganta #procedimientos #atiendo #ruta bajo la cabecera; en Sueño, solo las 5 fichas con enlace; títulos D-049 | build-pages.py, D-076/D-082 |
| 7.6 Revisión médica | Aprobación por el usuario revisor_medico (no por las sesiones), con hora; nada publicado sin aprobación | revision-log (lo consulta la sesión local), QA |
| 7.7 Checkpoint 4 | OK de José registrado antes de publicar | D-078, D-082, BATON |
| 8.3 Contacto | Dirección completa, mapa D-065 con «Cómo llegar», estacionamiento, accesibilidad, «previa cita», formulario (sin enviarlo), WhatsApp/tel, aviso de urgencias | D-079, PLAN 8.3 |
| 10.2 Rendimiento | PERFORMANCE.md: «antes», «después» y «después 2», límites del entorno, CLS de portada, peso de escritorio tras D-081 | PERFORMANCE.md |

Hallazgos verificados por la sesión local que puedes cerrar: Q-048 (sitemaps sin archivos 404, sin recurso-sitemap) y Q-049
(credenciales h3, accesibilidad 100 en Dra.).

## Además, auditar lo construido después del encargo (sesión local y cloud, 2026-10-14)

| Cambio | Commits | Qué verificar |
|---|---|---|
| Entrada del hero y transición entre páginas (D-084, Q-050) | 3ceba23, 75afcb5 | Estado final idéntico a «reducir movimiento»; h1 y lead sin opacidad 0; LCP móvil sin regresión (A/B en PERFORMANCE.md) |
| Menú móvil rediseñado | edbb4cb, 640f5b0 | header.php (inner/foot), main.js (top medido al abrir), components.css y sleep.css; teclado y foco; barra inferior oculta con el menú abierto; «reducir movimiento» |
| Antispam del formulario (Q-051/Q-052) | cfab0bf, 3a519f4 | `Contact\SpamGuard::classify()`: falsos positivos con mensajes reales en español e inglés, correos y dominios sueltos; descarte silencioso sin correo; contador sin datos personales |
| Turnstile (D-085) | cfab0bf | Solo activo con claves; verificación en siteverify; si Cloudflare no responde, el envío pasa; reset tras error; sin claves en el repo |
| Enumeración de usuarios y versiones (Q-053/Q-054) | 4abe1ff, bf64525 | `Security\Hardening` (users REST, ?author=, oEmbed, generator, X-Powered-By) y `tools/deploy/harden-htaccess.sh` (idempotente, --remove) |

Puedes cerrar Q-050, Q-052, Q-053 y Q-054, ya verificados en producción por la sesión local, si tu revisión del código lo confirma.

