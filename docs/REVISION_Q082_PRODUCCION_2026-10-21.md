# Q-082 en producción y cierre de 8.6 — Codex, 2026-10-21

## Veredicto

**Q-082 VERIFIED; 8.6 DONE.** Se ratifica la entrega 0a1fa76, producto declarado 5f1d268 desplegado desde b968f8f. La acción pendiente de D-107 (cédulas solo en Conóceme ES/EN, con institución y remisión correcta) está implementada y verificada. El cierre se apoya en la conformidad del abogado comunicada por José y en esta verificación técnica; no es un dictamen jurídico independiente de Codex.

9.5 continúa OWNER por las capturas pendientes de José (D-106). Doctoralia sigue apagado; el cierre legal no autoriza activarlo. No repetir despliegue, publicación ni actualización de los avisos.

## Comprobaciones independientes de producción

- cedulas_check.py endurecido: **30/30**; cédulas e instituciones correctas únicamente en las dos páginas About de la matriz, pie sin números y avisos con remisión en su párrafo regulatorio.
- El mismo comprobador sobre URLs normales sin parámetros: **30/30**. El wrapper review-production.py solo quita el parámetro de consulta y registra cabeceras; no sustituye HTML/CSS ni escribe en WordPress. En esta lectura: **27 hit, 3 miss**; contenido correcto en todas. No se afirma 30/30 cache hit.
- seo_check --prod: salida 0, sin avisos, sitemap 30 (15 ES + 15 EN).
- Párrafos regulatorios públicos de ambos avisos idénticos al texto del build nuevo, normalizando etiquetas/espacios del HTML renderizado. CSS público idéntico byte por byte al repositorio.
- Evidencia propia: docs/audit/2026-10-21-q082-codex-prod/ (cedulas.log, normal.log, normal-headers.json, seo.json y cotejo.json).

## Cotejo de la entrega y límites

Se cotejaron los cinco prefijos MD5 del servidor con md5-5f1d268.txt: iguales. Es evidencia aportada del servidor; Codex no leyó sus archivos PHP por SSH. Los 17 registros de páginas aportados (176 y 16 EN) solo difieren en 176 y 390; las otras 15 EN son idénticas, incluidos estado y aprobación registrados. No se vuelve a leer la base ni se certifica una comparación exhaustiva de todas las páginas ES.

La entrega documenta respaldo 20261021-152759, base de 472 302 bytes y 30 tablas, plugin/tema y copias exactas previas de los dos post_content. Ajustes: dos nuevas instituciones; huella del resto 7c208e9c1b4c intacta. Metadatos de ambas páginas iguales y estados publish; post_modified y una revisión nueva por página como efectos automáticos. Estos hechos son atestados por la sesión local y sus registros, no una restauración ni inspección independiente de la base.

La precondición inicial detuvo la actualización sin cambios según la entrega. Se acepta la conciliación limitada CRLF→LF descrita: finales de línea no alteran el texto legal. No se autoriza ignorar espacios, texto u otras diferencias en futuras actualizaciones. No se dispone aquí del contenido privado previo completo ni del script ejecutado para repetir esa comparación; la igualdad normalizada previa y el alcance de escritura se apoyan en aplicacion.log/verificacion.md y la atestación del propietario. Los archivos históricos de la auditoría anterior se conservan.

Regresión recibida: idioma 37/37, transiciones 9/9 y EN→ES, barra About 14/14; visual ES/EN a 390/1440. Se cotejaron los registros; esas matrices no se repitieron en esta ratificación. Tamaños emulados en Chrome, sin prueba física ni Safari/Firefox. La ausencia de errores y Doctoralia apagado son también parte del reporte de entrega, sin acceso a logs privados por Codex.

## Documentación y avance

PLAN 8.6 pasa de OWNER a DONE, peso 3; PROGRESS se regenera. Español: **224/228 (98,25 %), 84/86 tareas DONE**. Inglés conserva **19/19 (100 %), 7/7 DONE**. No se cierran otras tareas ni se activa mantenimiento automático.

Codex no desplegó, publicó, ejecutó setup-pages/setup-en/publish-en, envió formularios ni accedió a cuentas o secretos. Los cuatro HTML de preview previos del usuario se mantienen fuera del commit.
