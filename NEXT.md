# NEXT — Próximo trabajo

Actualizado: 2026-10-13 · Claude cloud. Progreso 118/242 (48,76%); 46/92 DONE; 8.1/8.2/8.8 DESCOPED (D-076/D-077).

0. **D-076 (sitio corto): implementado en la rama; falta desplegar.** Claude local: pull; `bash tools/deploy/deploy-code.sh` (Listado, setup.php, template-tags, components.css), `bash tools/deploy/setup-pages.sh` (ORL y Sueño siguen en borrador), `bash tools/deploy/setup-site.sh` (portada), `bash tools/deploy/deploy-briefing.sh` (guia + paginas.json + revisar.php + revision/) y purga. Verificar: vista previa de ORL/Sueño (anclas, listas sin flecha, CTA), motivos de la portada (van a Primera consulta mientras ORL/Sueño estén en borrador), `/padecimientos/`, `/tratamientos/` y `/recursos/` en 404, guía con las dos páginas sin pulsar aprobar, `paginas.json` 403 directo y aviso en revision/. Después José comparte la guía con la Dra.
1. Claude local: desplegar este lote con `bash tools/deploy/deploy-code.sh`, luego `bash tools/deploy/setup-dra.sh`, purgar LiteSpeed/CDN. Verificar manifest 200 e iconos, nueve logos originales accesibles y copias fuera de tabulación, pausa/tooltip con foco, títulos Membresías/Agenda y exclusión GA4 de editores/DNT. Sin probar formulario/SMTP en producción. Detalle en BATON e informe.
2. Cerrar 1.2: aportar docs/audit/2026-10-13/medios.csv con los 44 adjuntos, uso y propuesta KEEP/REPLACE/REMOVE aprobada por José, más entradas pie/legal. Solo inventario: conservar medios y no acceder a secretos/datos privados.
3. 10.2: medir Lighthouse/CWV actuales e informe de presupuesto/excepciones. Registrar escritorio q100, fuentes intactas y fallback PNG de Primera consulta (774 KiB); no cambiar calidad ni fotografía sin autorización.
4. Staging protegido: idempotencia de setup-pages y metadata móvil/original, roles/Gutenberg y aprobación médica. Fixtures locales no sustituyen WP autenticado. Cobertura GSC en 1.4, GBP y Safari/iOS nativo siguen pendientes.
5. Dra.: revisar solo ORL y Sueño cortas en `/briefing/guia/` (D-076); al aprobar ambas, publicar con OK de José. Fichas y recursos archivados en borrador, fuera de su revisión. Q-039 y Q-043 ya aprobados; no borrar los cuatro HTML locales.

Cinco tareas auditadas DONE; correcciones nuevas aún locales. Inglés tras español. Maps D-065 y fuentes D-073 conservados. Preguntas al propietario por Claude.
