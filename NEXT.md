# NEXT — Próximo trabajo

Actualizado:2026-10-13 · Codex. Progreso105/253 (41,50%);16de17tareas auditadasDONE,1REVIEW.

1. Claude local: ampliar el snapshot público de inventario para1.2 con los44adjuntos (ID,slug/archivo,MIME y clasificación/destino) y entradas de pie/legal (etiqueta/URL). Conservar todos los medios; KEEP común es válido si queda identificada cada entrada. Sin usuarios,correos,secretos ni rutas/documentos privados.
2. Veredictos1.5/3.6DONE, ya documentados: configuración base y seisactivos/dosMU según informeWP-CLI13/10. No hay código ni despliegue nuevos por esta entrega. Correctivos8b2375c ya desplegados por Claude (BATON12/10). WP_DEBUG_DISPLAY=false sería opcional; no cambiarlo ni solicitar otra aprobación por este cierre.
3. Copia/staging: ejecutar setup-pages dos veces y comprobar mismosIDs/metadata/estados; edición de original1600a100 y nueva versión irina-movila95. Fixtures locales pasan, falta WP autenticado. Roles/Gutenberg y aprobación médica se prueban allí, nunca con fichas públicas.
4. Gates externos ya conocidos:GSC/cobertura, horarioGBP, Safari/iOS nativo y CWV; Dra. revisa23fichas,3recursos y ORL/Sueño. Qué llevar ya aprobado (Q-043); composición Samsung aprobada (Q-039).
5. Después de cerrar despliegue/entrega, archivo D-060 sin borrar, preservando cuatroHTML modificados de José. Q-040 consumidores/allowlist Ops y Q-042 NAP centralizado siguen pendientes; no incluirlos sin revisión propia.

Maps sigue D-065 (estilo embebido/marker clásico); D-059 histórico no genera una solicitud nueva de MapID. Fuentes siguen D-073. Inglés después del lanzamiento español. Preguntas al propietario por Claude.

Despliegues y trabajos visuales posteriores: BATON de Claude. El veredicto del inventario no certifica esos cambios ni modifica su planificación.

## Encargos 2026-10-13 (Claude cloud, OK del propietario)

- Codex: auditar 6.4, 7.3, 8.9, 9.4 y 10.1 (pasadas a REVIEW) con `docs/CODEX_REVIEW_PROMPT_2026-10-13.md`.
- Claude local (la sesión estaba inactiva al pedirlo): generar `docs/audit/2026-10-13/medios.csv` por WP-CLI con una fila por adjunto (ID, slug, título, mime, dimensiones, KB, fecha, post_parent, dónde se usa: _elementor_data, _thumbnail_id, ajustes, rank-math-options-titles; marcar *-archivo-<id>) y las páginas/menús del pie y legales con estado. Solo lista: no borrar ni mover. Claude cloud propone KEEP/REPLACE/REMOVE y José aprueba.
- José: crear staging protegido en Hostinger (guía en el chat cloud) y pasar a la Dra. la guía de revisión.
