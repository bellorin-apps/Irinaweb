# Análisis de faltantes: MASTER_PROMPT + decisiones vs. estado real (2026-10-16)

Lo preparó Claude (sesión local) a petición de José: «Revisa el prompt inicial más lo añadido en el proceso y analiza qué falta».

**Fuentes:** MASTER_PROMPT §0–§165 y Anexo A; DECISIONS D-001…D-088; PLAN, PROGRESS, QA y BATON.

**Comprobaciones en vivo** (anónimas, solo lectura): 15 URL, una ficha (`/sueno/cpap/`) y la 404; DNS de correo; servidor por WP-CLI en solo lectura.

**Lo que se da por cumplido** (cubierto por tareas DONE, auditadas por Codex): arquitectura (tema hijo + plugin core + Elementor solo como capa editorial), diseño, rendimiento, SEO técnico, schema, accesibilidad automática, seguridad base, formulario con minimización de datos, legales publicados, analítica, lanzamiento y Día 0.

**Lo que no falta, porque se decidió así:** blog y centro de recursos, navegador de síntomas, más fichas (D-076/D-082/D-088), staging (D-080), horario «previa cita» (D-032/D-079), Map ID (D-065) y búsqueda interna (§70: no se justifica con un sitio corto).

## A. Faltantes reales que se pueden corregir sin decisión nueva

| # | Prioridad | Qué falta | Encargo | Evidencia | Propuesta |
|---|---|---|---|---|---|
| A1 | P1 | En las 5 fichas publicadas no se ve «Revisado médicamente por… · Última revisión: fecha», y el schema no lleva `lastReviewed` | §17 E-E-A-T, §59, §109 | `irina_medical_review_badge()` y `Graph.php:354` leen `di_fecha_revision_medica`, que está vacío en las 5. La app de revisión guarda `_di_approved_at` y `_di_approved_by` (D-040). | Nube: usar `_di_approved_at` como respaldo (es la fecha real de aprobación de la Dra., no se inventa nada) en la caja y en el schema. Despliegue y verificación local; auditoría de Codex. |
| A2 | P3 | Falta el entregable `COMPLIANCE.md` y la checklist legal específica | §51, §83; README lo enumera | No existe; lo legal está repartido en los legales publicados, DISCOVERY y LAUNCH_CHECKLIST | Nube: reunir en COMPLIANCE.md (LFPDPPP, COFEPRIS D-037, avisos, cookies, emergencias, publicidad médica, pendientes de 8.6), sin presentarlo como asesoría legal. |
| A3 | P3 | `wp-config.php` con permisos 644 | §49 «proteger wp-config» | stat en el servidor | Sesión local: pasarlo a 600 (PHP corre con el mismo usuario) y verificar el sitio. Reversible. |

## B. Lo que pidió el encargo y necesita una decisión de José

| # | Qué falta | Encargo | Estado |
|---|---|---|---|
| B1 | Errata en una ficha aprobada: «explicado por **una otorrinolaringólogo**» (en la tarjeta de Apnea, que aparece también en otras fichas) | §78 QA de contenido | Es contenido aprobado: corregir la concordancia no cambia el sentido médico, pero D-078 exige un OK para modificar una versión aprobada. |
| B2 | Reseñas de Doctoralia con su widget oficial | §20.11, D-012 | Decidido el 07/10 y nunca implementado (pendiente de la revisión publicitaria de Codex). |
| B3 | Versión en inglés | Anexo A, PLAN 13.x (19 pesos, aparte) | El criterio de entrada ya se cumple (contenido aprobado, CP3/CP5). El Anexo pide que Claude proponga fecha en NEXT y que José elija la herramienta (OD-008). |
| B4 | Monitoreo de disponibilidad | §49 «logging; monitoreo» | No hay ningún aviso si el sitio se cae. Requiere un servicio externo gratuito (cuenta de José). |
| B5 | Infraestructura de videos de la Dra. | §19.F | Sin construir; la Dra. tiene TikTok e Instagram. |
| B6 | Foto de la fachada o la entrada en Contacto | §56, D-079 | Pendiente opcional hasta que José la tenga. |

## C. En curso o con fecha (no son faltantes nuevos)

- **Codex audita** (REVIEW): 11.2, 12.1, 12.2 y Q-063/064/065.
- **Nube:** repaso de 5.1/5.2/5.5 y 5.3 (fuente única de NAP, Q-042) y Q-040.
- **Archivado de `tools/preview/` (D-060):** pendiente. Los cuatro HTML con cambios locales de José se conservan.
- **12.3:** día 7 ≈ 22-oct y día 30 ≈ 14-nov (Search Console, GA4, CWV de campo).
- **José:**
  - Perfil de Google: horario de la Dra., fotos y publicaciones mensuales, reseñas según MAINTENANCE;
  - datos de Doctoralia (9.5);
  - revisión legal (8.6);
  - prueba con lector de pantalla (límite de 10.4).
- **DMARC en `p=none` y sin `rua`:** el correo se entrega bien (SPF y DKIM correctos). Endurecerlo después es opcional (P3).
