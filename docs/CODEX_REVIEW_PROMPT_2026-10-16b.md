# Encargo para Codex: auditoría de 12.2, edición del Perfil de Google y hallazgos P3 (2026-10-16b)

Lo preparó Claude (sesión local). El rol y las reglas son los de `docs/CODEX_REVIEW_PROMPT.md` y `AGENTS.md`. Entrega un veredicto por tarea con evidencia (`DONE`, `FIX` o `REVIEW` con motivo) y actualiza QA.md, PLAN.md, PROGRESS.md (`python tools/audit/progress.py`) y BATON.md. Termina con un mensaje para el otro agente.

Base: haz pull hasta 6fb2b19 o posterior. Producción declarada: d7e14ac (no hay código nuevo desde entonces).

**No hacer:** desplegar, enviar el formulario ni probar SMTP en producción (envía correos reales), entrar al Perfil de Google, a Doctoralia, a hPanel ni a wp-admin, pedir o imprimir claves, publicar o despublicar contenido.

| Tarea | Qué verificar | Evidencia de partida |
|---|---|---|
| 12.2 Día 0 / Día 1 (REVIEW) | **Reproduce en anónimo lo público:** 15 URL del sitemap con 200, `index, follow` y canónica propia; 301 de http/www y de /padecimientos/ y /tratamientos/; /links/ y /gracias/ con noindex; 404 real en español; 403 en readme y xmlrpc; cabeceras; SSL; consola a 390 y 1440 px. **Lo del servidor** (checksums, plugins, cron, usuarios, 2FA, registros de errores) es atestación de la sesión local: decide si basta o qué falta. **Juzga el límite declarado:** sin registros de acceso por SSH (solo hPanel) y monitor 404 apagado. ¿Alcanza para DONE o 12.2 debe esperar evidencia de visitas reales? | QA «Día 0 y Día 1 del lanzamiento»; PLAN 12.2; BATON 16/10 13:00 |
| 9.5 Perfil de Google (OWNER) | Coherencia del perfil con el sitio según QA «Perfil de Negocio» (sesión local) y la verificación de Q-062 (nube). Comprueba que la URL con UTM da 200 y canónica limpia, que el wa.me coincide con el sitio y que los 7 servicios coinciden con lo que el sitio ofrece. Sin entrar al perfil. 9.5 sigue OWNER por el horario real y Doctoralia. | QA «Perfil de Negocio»; Q-062 |
| Q-063 · P3 | /?author=1 da 404 con el título «José \| Dra. Irina González Sáez». Confirma, y valora el riesgo y el arreglo propuesto: que el 404 de `Security\Hardening` use el título de la 404. | QA Q-063; `src/Security/Hardening.php` |
| Q-064 · P3 | /recursos/ da 404 con el título «Recursos Archive» y `index, follow` en el HTML. Confirma, valora el impacto (solo cosmético por el 404) y dónde corregirlo (Rank Math o `inc/setup.php`). | QA Q-064 |
| Q-041 · P2 (OWNER) | Sigue igual: aviso de `google.maps.Marker` obsoleto en Inicio y Contacto hasta que José entregue el Map ID (D-059). Solo confirma que el aviso no rompe el mapa. | D-059; `assets/js/map.js` |

En 11.2 no cambies nada: sigue en REVIEW hasta la pasada en Android real (José).
