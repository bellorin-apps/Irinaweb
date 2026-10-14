# NEXT — Próximo trabajo

Actualizado: 2026-10-13 · Claude cloud. Progreso 118/242 (48,76%); 46/92 DONE; 8.1/8.2/8.8 DESCOPED (D-076/D-077).

0. **Claude local: desplegar accesibilidad (8bb93a0) y D-081.** Pull; `bash tools/deploy/deploy-code.sh` (tema: performance.php, components.css, footer.php, home/pasos.php); después `wp eval 'print_r( irina_regenerate_desktop_heroes() );'` (crea `*-escritorio.webp` q95 de cada hero-*; repetirlo no rehace nada) y purga. Verificar en escritorio que Inicio, Conóceme, Primera consulta y Contacto piden `…-escritorio.webp?m=` y pesan ~1 MB; en móvil sigue `-1600x…?m=`; pie con títulos h2 y cédulas legibles; pasos h3; puntos de credenciales en su sitio. Luego repetir Lighthouse (o PSI) para el «después» de 10.2.
1. Cloud (D-078): 8.3 en REVIEW para Codex (D-079; foto de fachada pendiente opcional de José) y 10.2 medición Lighthouse en vivo (5 páginas, móvil y escritorio, 3 pasadas) con informe en `PERFORMANCE.md`. El lote de Codex (logos, manifest, títulos Dra.) ya está desplegado (BATON 15:30).
2. Cerrar 1.2: aportar docs/audit/2026-10-13/medios.csv con los 44 adjuntos, uso y propuesta KEEP/REPLACE/REMOVE aprobada por José, más entradas pie/legal. Solo inventario: conservar medios y no acceder a secretos/datos privados.
3. 10.2: «antes» medido (PERFORMANCE.md). D-081: escritorio a q95 tras aprobar José la comparación 1:1 (enviada en el chat, PSNR 44,8 dB, 3,3 → 1,0 MB en hero-home); luego implementar la versión de escritorio q95 y medir el «después» con la accesibilidad de 8bb93a0 desplegada.
4. Sin staging (D-080): 1.7 y 5.6 DESCOPED. Gates de Codex que pedían staging se verifican en producción en modo lectura o quedan como límite documentado. GSC (1.4): José envía capturas de Sitemaps, Páginas e Inspección. GBP y Safari/iOS nativo siguen pendientes.
5. Dra.: revisar solo ORL y Sueño cortas en `/briefing/guia/` (D-076). Al aprobar ambas, Claude avisa a José, José revisa y da OK y la sesión local publica (D-078). Clave sin rotar (D-078). Fichas y recursos archivados en borrador, fuera de su revisión. Q-039 y Q-043 ya aprobados; no borrar los cuatro HTML locales.

Cinco tareas auditadas DONE; correcciones nuevas aún locales. Inglés tras español. Maps D-065 y fuentes D-073 conservados. Preguntas al propietario por Claude.
