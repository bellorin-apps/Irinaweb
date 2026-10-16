# COMPLIANCE — Checklist legal y regulatoria (México)

Actualizado: 2026-10-16 · Claude cloud. Entregable del MASTER_PROMPT §83, con la checklist de §51. Reúne lo que está publicado y lo que falta.

**Esto no es asesoría legal.** Es una lista de control técnica y documental para preparar la revisión de un abogado o de quien José designe (PLAN 8.6). Esa revisión es mejora continua por decisión de José (D-086).

Leyenda: ✅ implementado y publicado · ⏳ pendiente · 👤 depende de José o de la Dra. · ⚖️ conviene confirmarlo con un abogado

## 1. Protección de datos personales (LFPDPPP)

| Punto | Estado | Dónde / evidencia |
|---|---|---|
| Aviso de privacidad integral | ✅ | /aviso-de-privacidad/, §1–11 (D-051). Fuente: `tools/content/legal/aviso-de-privacidad.html` |
| Aviso de privacidad simplificado | ✅ | Misma página, §12 |
| Responsable identificado, domicilio y contacto | ✅ | Shortcodes `[di_responsable]`, `[di_address]` y `[di_email]`, que leen los ajustes del consultorio (fuente única) |
| Datos sensibles (salud) con consentimiento expreso | ✅ ⚖️ | §4: por escrito en consulta, y el expediente bajo la NOM-004. El sitio no pide datos de salud |
| Minimización en el formulario | ✅ | D-050: nombre, teléfono y motivo como categoría; correo y mensaje opcionales; casilla de consentimiento con enlace al aviso; nada se guarda en la base de datos (Q-051) |
| Transferencias y encargados (hospitales, aseguradoras, autoridades; Google, Meta y Cloudflare con transferencia internacional) | ✅ ⚖️ | §5 |
| Derechos ARCO, revocación y plazos (20/15 días hábiles) | ✅ | §7, con `[di_arco]` (correo para ARCO) |
| Conservación (5 años) | ✅ ⚖️ | §6 |
| Menores, con consentimiento de padre, madre o tutor | ✅ | §10 y Términos §8 |
| Medidas de seguridad | ✅ | §8; detalle técnico en SECURITY.md |
| Cambios al aviso con fecha visible | ✅ | §11, «Última actualización» |

## 2. Cookies y terceros

| Punto | Estado | Dónde / evidencia |
|---|---|---|
| Declaración de cookies técnicas y de terceros | ✅ | Aviso §9: Google Maps, WhatsApp/Meta, Doctoralia, GA4 y Cloudflare Turnstile |
| Medición agregada sin banner | ✅ ⚖️ | D-052: GA4 con IP truncada, sin señales de Google ni personalización de anuncios, respeta Do Not Track y no mide a los editores. La LFPDPPP no exige banner; se apoya en el aviso |
| Captcha (Cloudflare Turnstile) explicado en lenguaje sencillo | ✅ | Aviso §9 (D-085) |
| Widget de opiniones de Doctoralia | ⏳ | Construido y apagado. Revisión publicitaria y de privacidad de Codex antes de activarlo: `docs/CODEX_REVIEW_PROMPT_2026-10-16c.md` (D-012, D-091). Podría requerir ajustar §9 |

## 3. Aviso médico, urgencias y términos

| Punto | Estado | Dónde / evidencia |
|---|---|---|
| La información no sustituye una consulta | ✅ | /aviso-medico/ |
| No es canal de urgencias; llamar al 911 | ✅ | Aviso médico y Contacto. El número de urgencias de la Dra. no se publica (D-011) |
| Contenido revisado por la Dra. | ✅ | Puerta médica (D-078). Fichas con «Revisado médicamente por… · Última revisión» y `lastReviewed` (A1, 2026-10-16) |
| Resultados y expectativas sin promesas | ✅ ⚖️ | Aviso médico, «Resultados y expectativas» |
| Términos de uso (citas no confirmadas, propiedad intelectual, responsabilidad, jurisdicción Monterrey) | ✅ ⚖️ | /terminos-de-uso/ (D-051, D-053) |

## 4. Publicidad sanitaria (Ley General de Salud y Reglamento en materia de Publicidad, COFEPRIS)

| Punto | Estado | Dónde / evidencia |
|---|---|---|
| Aviso de Publicidad n.º de ingreso 2519012002A00712 | ✅ ⚖️ | Pie de todas las páginas y aviso médico (D-037). La leyenda exacta está sujeta a la revisión 8.6 |
| Aviso de Funcionamiento 2619015036A00445 (CAB Medical, 2026) | ✅ | Ídem |
| Cédulas profesionales verificables (Registro Nacional de Profesionistas) | ✅ / 👤 | Ajuste `publicar_cedulas`. El aviso médico remite al pie |
| Especialidad y certificación del Consejo (CMORL y CCC) | ✅ | Credenciales en Conóceme y en el schema (solo datos confirmados, DISCOVERY §1.1) |
| Sin promesas de curación, sin precios engañosos, sin «antes/después» | ✅ ⚖️ | Política editorial (MASTER_PROMPT). Contenido aprobado por la Dra. |
| Testimonios u opiniones de pacientes | ⏳ ⚖️ | Solo con el widget oficial de Doctoralia, sin editar (D-012), y tras el veredicto de Codex |
| Perfil de Google con los mismos datos que el sitio | ✅ / 👤 | 9.5: falta el horario real de la Dra. |

## 5. Pendientes

1. 👤 ⚖️ **Revisión legal (8.6), mejora continua (D-086):** leyenda COFEPRIS exacta, conservación, transferencias, jurisdicción y uso de opiniones.
2. ⏳ **Opiniones de Doctoralia:** veredicto de Codex (D-091) y, si es «GO», activación por José.
3. 👤 **Horario real** de la Dra. en el Perfil de Google. Si se publica en el sitio, revertir D-032.
4. 👤 **B1:** corregir la errata en la ficha de apnea, que está aprobada. El cambio requiere que la Dra. la apruebe otra vez (D-090).
5. **Revisión anual:** aviso de privacidad y contenido clínico con su `lastReviewed` (MAINTENANCE.md).
