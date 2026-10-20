# Q-080: ratificación en producción — Codex, 2026-10-20

Base a3e873a, rama ccr-6254502d-xly8ww. **Q-080 VERIFIED**. La entrega de código y sus resultados quedan aceptados; no repetir despliegue ni publicación.

## Evidencia y comprobación independiente

La sesión local declara despliegue de 1d3b6cf, respaldo `~/deploy-backups/20261020-162726/` (472 K, 30 tablas y copias de plugin/tema) y purga LiteSpeed. La comparación Git entre 1d3b6cf y a3e873a no muestra cambios de producto. Respaldo, ejecución y comparación del archivo remoto se aceptan por atestación del propietario/Claude; Codex no accede a servidor, cuentas, configuración o base.

Codex ejecutó `tools/audit/review-q080-public.py`, con GET públicos en 14 rutas y comparación de los archivos de estado aportados:

- 404 EN: `Page not found | Dr. Irina González Sáez`, con HTTP 404; 404 ES conserva su título.
- Seis pares de páginas principales con HTTP 200. Alts EN sin el nombre español; First visit lleva el texto inglés esperado y su pareja ES conserva el español.
- Alts vacíos EN y ES con el mismo recuento por pareja; ningún relleno de alts decorativos observado.
- `og:image:alt` inglés en /en/ y /en/sleep/; equivalente español conservado en / y /sueno/.
- `twitter:image:alt` no aparece en la muestra. No se certifica la emisión de una etiqueta inexistente.
- `estado-antes.txt` y `estado-despues.txt` idénticos byte a byte: 16 registros en publish, con las fechas, aprobaciones y huellas recogidas sin cambios.

Evidencia nueva: `docs/audit/2026-10-20-q080-codex/review.json`. Se repitió además `seo_check.py --base https://drairinagonzalez.com --prod`: **salida 0**, sitemap 30 (15 EN + 15 ES), comprobaciones HTTP, canónicas, OG, hreflang, selector, schema, enlaces y auxiliares sin fallos. Resultado en `C-seo.json` del mismo directorio.

## Límites

Los estados son registros privados aportados, no una lectura nueva de la base por Codex. Sus huellas de contenido son prefijos; la igualdad de esos registros respalda la evidencia de no modificación, sin equivaler a una comparación íntegra e independiente de la base. El md5 local completo es `69e6347bb0ea3e6d779fb6bd46d2e5fc`, coherente con el prefijo remoto documentado; el informe no contiene el hash remoto completo para volver a compararlo independientemente.

Imágenes, consola, vistas móvil/escritorio, ausencia de archivos de error y Doctoralia apagado se aceptan de `docs/audit/2026-10-20-q080-prod/`. La muestra visual no cubre todos los anchos; ausencia de error_log no prueba ausencia universal de errores. La comprobación HTTP no certifica por sí sola el SHA desplegado. No se repiten lint/PHPCS del código inalterado, ya aprobados en 1d3b6cf.

## Cierre

Producción declarada: **1d3b6cf**. Q-080 pasa de VERIFIED_LOCAL a **VERIFIED**; 13.4, 13.5 y 13.7 mantienen DONE. Inglés **19/19, 100 %, 7/7 DONE**; español **221/228, 96,93 %, 83/86 DONE**, sin aumento. No quedan verificaciones de despliegue Q-080 pendientes en este alcance.

No ejecutar setup-en, publish-en ni repetir la entrega por esta ratificación. Conservar contenido/aprobaciones/estados publicados y Doctoralia apagado. Seguir mantenimiento y pendientes ya registrados por separado. Codex no desplegó, publicó, probó formularios/SMTP ni accedió a secretos o Sensia. Cuatro HTML previos de José intactos y excluidos.
