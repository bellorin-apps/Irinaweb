# Checkpoint 3 — Home + sistema visual en WordPress

Preparado la noche del 8 al 9 de octubre de 2026 para la revisión del propietario. Todo lo listado está en producción pero **no es público**: `inicio-v2` con noindex; el resto en borrador (vista previa con sesión iniciada en wp-admin).

## Qué revisar

| Página | Cómo verla | Qué decidir |
|---|---|---|
| Home v2 | https://drairinagonzalez.com/inicio-v2/ | ¿Pasa a ser la portada? Cabecera: logotipo completo (actual) o isotipo + nombre (Ajustes → Consultorio) |
| Dra. Irina | wp-admin → Páginas → «Dra. Irina González Sáez» → Vista previa (169) | Texto de enfoque (borrador), orden de credenciales |
| Otorrinolaringología (pilar) | Vista previa (170) | Lista de padecimientos y tratamientos; «cirugía endoscópica nasal» pendiente de confirmar si la ofrece |
| Sueño (pilar, nocturno) | Vista previa (171) | Tono nocturno; FAQ provisionales |
| Primera consulta | Vista previa (172) | Costo de consulta; consulta en línea |
| Contacto | Vista previa (174) | Mapa y datos |
| Preguntas frecuentes | Vista previa (173) | Costo y seguros |
| Legales | Vistas previas (175, 176, 177) | Textos provisionales; la Dra. entrega el aviso de privacidad definitivo |
| Padecimientos y tratamientos | wp-admin → Padecimientos / Tratamientos (22 borradores) | La Dra. revisa, corrige y aprueba («MEDICALLY APPROVED») uno por uno |
| Artículo de muestra | wp-admin → Recursos (1 borrador) | Formato editorial |

## Decisiones tomadas (2026-10-08, D-034)

1. Portada: **sí**, Home v2 pasa a portada (`tools/deploy/go-live-home.sh`); 106 queda en borrador; plantillas 39/79 a papelera.
2. Cabecera: **logotipo completo** (la casilla alternativa se conserva).
3. Foto del hero: **autorizada por la Dra.**
4. Versión en inglés: **después del lanzamiento en español** (fecha propuesta en NEXT.md).
5. Horario: **no publicar por ahora** («Previa cita», D-032). Hospitales ampliados (D-033).

Pendientes de decisión: OD-009 rinoplastia; cargo en el Hospital Universitario; segunda publicación; publicación de las páginas base cuando la Dra. apruebe sus textos.

## Decisiones del propietario al cerrar CP3 (lista original)

1. Portada: cambiar la portada a Home v2 y retirar las plantillas viejas del Theme Builder (D-029).
2. Páginas base: publicarlas al aprobar sus textos (las que tengan [PENDIENTE DE CONFIRMACIÓN] pueden publicarse con el dato omitido o esperar).
3. Cabecera: logotipo completo o isotipo + nombre.
4. Fotos: autorización de la Dra. para la foto del hero y las que vengan (D-008); calidad de la CDN (D-030).
5. Fecha para la fase en inglés (NEXT.md, Fase 13).

## Pendientes técnicos del propietario (hPanel)

- CDN → optimización de imágenes (D-030).
- SSL de otorrino-monterrey.com (Q-007).
