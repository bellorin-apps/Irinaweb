# Rollback de código · runbook revisado 2026-10-16

**Ensayado en copia aparte; no en producción.** QA16/10 registra restauración de código idéntica al respaldo/lint79 y restauración DB con30/30tablas y mismas filas.12.1DONE en ese alcance acordado por José; no acredita restauración del servicio en vivo ni autoriza ejecutarla ahora. Solo Claude local, con respaldo y motivo concreto, realiza el despliegue/restauración. Sin staging D-080; no sustituir una prueba real por un fixture.

## Alcance y preparación

1. Identificar el despliegue que se quiere revertir y el timestamp exacto del respaldo previo en `~/deploy-backups/`. `deploy-code.sh` respalda `irina-gonzalez` y `dra-irina-core` fuera del webroot, y luego extrae código sin borrar archivos anteriores.
2. Comprobar en lectura que ambos árboles existen, pertenecen a ese despliegue y contienen los archivos esperados. No usar una carpeta vacía, fecha supuesta ni elegir automáticamente «la última». Resolver y comprobar los dos destinos: exclusivamente el tema `irina-gonzalez` y plugin `dra-irina-core` del sitio correcto. No tocar otros plugins, medios ni carpetas privadas.
3. Respaldar el estado actual de esos dos árboles en una carpeta nueva fuera del webroot, para poder deshacer también el rollback. No sobrescribir el respaldo elegido ni ninguno anterior.

## Restauración y verificación (sin ejecutar en esta auditoría)

Claude local prepara los comandos concretos con rutas ya verificadas. Para recuperar exactamente una versión, conserva primero los árboles actuales y repone los árboles completos desde el respaldo; copiar encima puede dejar archivos nuevos que no existían antes. No usar borrados recursivos ni comandos genéricos sobre todo `wp-content`.

Tras restaurar: purgar LiteSpeed/CDN, comprobar Inicio/Contacto/una ficha y el fallo que motivó revertir; registrar HTTP, versión/archivos, imágenes y errores JS, sin enviar formulario. Si la reversión falla, volver a los árboles actuales conservados y registrar el resultado. La evidencia de restauración controlada en copia aparte ya existe en QA16/10; la restauración del servicio en vivo no se ensayó por decisión de José.

## Operaciones distintas

- `.htaccess`: su copia `htaccess-<timestamp>` y el bloque de hardening son independientes. `harden-htaccess.sh --remove` solo retira el bloque de ese script; no restaura otras reglas ni código. Verificar y conservar antes el `.htaccess` actual. No desactivar seguridad por rutina.
- Contenido/configuración/base de datos: la copia de tema/plugin no revierte páginas, opciones, metadatos, aprobaciones ni usuarios. Requieren respaldo propio y autorización del cambio concreto; no revertir estados clínicos o configuración como efecto lateral de un rollback de código.
- Medios: respaldos de uploads y versiones anteriores conservadas son independientes; no borrar ni reimportar por esta revisión.
- Mini apps: respaldo propio de su despliegue; no imprimir tokens ni respuestas. No restaurar ni rotar claves al revertir código.

Registro mínimo para cerrar 12.1: motivo, respaldo elegido, destinos comprobados, copia del estado previo al rollback, acciones exactas, verificación posterior y resultado/plan de regreso. No adjuntar secretos ni URLs con clave. La auditoría16b incorpora la atestación de los ensayos en copia y verifica las salvaguardas con stubs locales; no repite SQL real ni inspecciona volcados privados.

## Volcado privado y ensayo DB · auditoría16b

`deploy-code.sh` crea db.sql.gz fuera del webroot con umask077/chmod600 antes de sincronizar código; requiere marcador de volcado completo y gzip válido, y aborta antes de copiar si falla. No leer, adjuntar ni versionar el volcado: puede contener datos y configuración privados. Esta auditoría no abre la base ni el archivo.

`drill-db.sh` limita timestamp al formato esperado, identifica producción y rechaza ese nombre de destino; solo importa en una base vacía distinta, con usuario dedicado de prueba según el procedimiento. El respaldo debe ser el export propio y confiable del despliegue; no se presenta el script como sandbox de SQL arbitrario.

FIX locales Q-066/Q-067: una conexión crea/usa/limpia el archivo temporal con mktemp, chmod600 y trap antes de leer stdin. La contraseña no viaja por argv. Errores de descompresión/importación/listado/conteo o discrepancias retornan fallo; no se declara éxito con cero tablas o diferencias. La comparación sigue siendo de cantidades de filas, no checksum del contenido ni prueba de funcionamiento de la app sobre la copia.

19casos con stubs y dos regresiones anteriores reproducidas, sin SSH/MySQL real ni secretos. Windows comprueba que se solicita chmod600, no certifica los permisos del servidor remoto; cleanup probado para salidas normales y errores simulados. No ejecutar ensayo ni deploy automáticamente por estas correcciones; usar las herramientas actualizadas en la próxima operación autorizada de Claude.

Diferencias de filas pueden deberse a cambios posteriores al respaldo; el script devuelve fallo para revisión, no diagnostica por sí solo corrupción ni modifica producción para igualar conteos.
