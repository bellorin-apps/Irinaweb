# Rollback de código · runbook revisado 2026-10-16

**No ensayado en producción.** Este documento prepara la revisión de 12.1; no acredita una restauración real ni autoriza ejecutarla ahora. Solo Claude local, con respaldo y motivo concreto, realiza el despliegue/restauración. Sin staging D-080; no sustituir una prueba real por un fixture.

## Alcance y preparación

1. Identificar el despliegue que se quiere revertir y el timestamp exacto del respaldo previo en `~/deploy-backups/`. `deploy-code.sh` respalda `irina-gonzalez` y `dra-irina-core` fuera del webroot, y luego extrae código sin borrar archivos anteriores.
2. Comprobar en lectura que ambos árboles existen, pertenecen a ese despliegue y contienen los archivos esperados. No usar una carpeta vacía, fecha supuesta ni elegir automáticamente «la última». Resolver y comprobar los dos destinos: exclusivamente el tema `irina-gonzalez` y plugin `dra-irina-core` del sitio correcto. No tocar otros plugins, medios ni carpetas privadas.
3. Respaldar el estado actual de esos dos árboles en una carpeta nueva fuera del webroot, para poder deshacer también el rollback. No sobrescribir el respaldo elegido ni ninguno anterior.

## Restauración y verificación (sin ejecutar en esta auditoría)

Claude local prepara los comandos concretos con rutas ya verificadas. Para recuperar exactamente una versión, conserva primero los árboles actuales y repone los árboles completos desde el respaldo; copiar encima puede dejar archivos nuevos que no existían antes. No usar borrados recursivos ni comandos genéricos sobre todo `wp-content`.

Tras restaurar: purgar LiteSpeed/CDN, comprobar Inicio/Contacto/una ficha y el fallo que motivó revertir; registrar HTTP, versión/archivos, imágenes y errores JS, sin enviar formulario. Si la reversión falla, volver a los árboles actuales conservados y registrar el resultado. No declarar 12.1 DONE hasta que exista evidencia de una restauración controlada y su verificación.

## Operaciones distintas

- `.htaccess`: su copia `htaccess-<timestamp>` y el bloque de hardening son independientes. `harden-htaccess.sh --remove` solo retira el bloque de ese script; no restaura otras reglas ni código. Verificar y conservar antes el `.htaccess` actual. No desactivar seguridad por rutina.
- Contenido/configuración/base de datos: la copia de tema/plugin no revierte páginas, opciones, metadatos, aprobaciones ni usuarios. Requieren respaldo propio y autorización del cambio concreto; no revertir estados clínicos o configuración como efecto lateral de un rollback de código.
- Medios: respaldos de uploads y versiones anteriores conservadas son independientes; no borrar ni reimportar por esta revisión.
- Mini apps: respaldo propio de su despliegue; no imprimir tokens ni respuestas. No restaurar ni rotar claves al revertir código.

Registro mínimo para cerrar 12.1: motivo, respaldo elegido, destinos comprobados, copia del estado previo al rollback, acciones exactas, verificación posterior y resultado/plan de regreso. No adjuntar secretos ni URLs con clave. La auditoría de 16/10 solo revisa el procedimiento y constata despliegues con respaldo anteriores.
