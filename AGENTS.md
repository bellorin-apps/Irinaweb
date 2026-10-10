# Auditoría y colaboración

Trabajar en `ccr-6254502d-xly8ww` o una rama `codex/` que se integre allí. Leer el encargo activo, MASTER_PROMPT, STATUS, NEXT, BATON y DECISIONS. La instrucción más reciente del propietario prevalece sobre documentos históricos.

- No desplegar: lo ejecuta Claude local con los scripts de `tools/deploy/`.
- No leer ni imprimir secretos; `env.sh`, `config.php` y `*.local.txt` se mantienen fuera de Git. No tocar Sensia ni su carpeta privada.
- No publicar clínica ni saltarse la aprobación médica. No borrar medios, páginas, plantillas o respaldos.
- Conservar cambios previos del usuario y separar los commits propios.
- No cambiar textos de marca, menús ni sombras durante ajustes fotográficos. Respetar D-049 y D-053.
- Contenido: editar el build, regenerar su JSON y comprobar que los cambios son los esperados.
- Verificar PHP, PHPCS, sintaxis JS/bash y los tamaños relevantes. No probar formulario/SMTP en producción: envía correos reales.
- Progreso: solo DONE suma. PLAN define tareas; PROGRESS calcula; STATUS resume; NEXT fija el próximo paso; BATON entrega; QA registra evidencia; DECISIONS explica decisiones.
- Documentar límites de las pruebas: un harness con assets locales sobre HTML público no demuestra que el cambio esté desplegado.

No delegar en subagentes salvo petición expresa del usuario.
