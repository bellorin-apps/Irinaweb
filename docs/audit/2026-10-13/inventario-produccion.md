# Inventario de producción (sesión local, 2026-10-13 10:35)

Para cerrar las tareas en REVIEW 1.2 (inventario), 1.5 (configuración base) y 3.6 (plugins). Obtenido por WP-CLI en el servidor
(`/opt/alt/php84/usr/bin/php /usr/local/bin/wp`), en solo lectura. No se abrió ni imprimió `wp-config.php`: las constantes se
consultaron con `defined()`/`constant()` y solo se muestran los valores booleanos, enteros o de palabra clave. No incluye usuarios,
correos, claves ni opciones privadas. Sensia aparece solo como nombre y versión: no se tocó.

Lectura rápida:
- Plugins activos: dra-irina-core 0.4.5, Elementor 4.3.4, Elementor Pro 4.3.1, LiteSpeed Cache 7.9.1, Rank Math 1.0.280 y Sensia 0.2.7. Ninguno tiene actualización pendiente.
- MU-plugins: solo los dos de Hostinger, preview-domain y auto-updates.
- Site Kit y Astra no están instalados. El tema activo es irina-gonzalez 0.1.0, hijo de Hello Elementor 3.5.1.
- Configuración: es_MX, America/Monterrey (UTC−6), fecha «j de F de Y», hora 24 h, la semana empieza el lunes, enlaces /%postname%/, comentarios cerrados por defecto y registro cerrado.
- Hardening: DISALLOW_FILE_EDIT true, FORCE_SSL_ADMIN true, WP_DEBUG false, actualizaciones del núcleo menores.
  WP_DEBUG_DISPLAY vale true, pero no tiene efecto con WP_DEBUG false. Se podría fijar en false como cinturón; es decisión de Codex o de José y no se tocó.
- Contenido: 44 adjuntos. Condiciones 14, tratamientos 9 y recursos 3, todos en borrador. 17 credenciales publicadas.
  Páginas ORL y Sueño en borrador, e inicio-antiguo en borrador.

## Salida literal

```
### Plugins (wp plugin list)
name,status,version,update
dra-irina-core,active,0.4.5,none
elementor,active,4.3.4,none
elementor-pro,active,4.3.1,none
litespeed-cache,active,7.9.1,none
seo-by-rank-math,active,1.0.280,none
sensia,active,0.2.7,none
hostinger-preview-domain,must-use,1.4.0,
hostinger-auto-updates,must-use,1.0.8,

### MU-plugins
name,version
hostinger-preview-domain,1.4.0
hostinger-auto-updates,1.0.8
hostinger-auto-updates.php
hostinger-preview-domain.php

### Temas
name,status,version
hello-elementor,parent,3.5.1
irina-gonzalez,active,0.1.0

### Ajustes
WPLANG: 'es_MX'
timezone_string: 'America/Monterrey'
gmt_offset: -6.0
date_format: 'j \\d\\e F \\d\\e Y'
time_format: 'H:i'
start_of_week: '1'
blog_public: '1'
permalink_structure: '/%postname%/'
default_comment_status: ''
users_can_register: '0'
locale: es_MX
wp: 7.1.3 · php: 8.4.26

### Constantes de hardening (solo definidas sí/no y valor booleano)
DISALLOW_FILE_EDIT: true
DISALLOW_FILE_MODS: no definida
WP_DEBUG: false
WP_DEBUG_DISPLAY: true
WP_DEBUG_LOG: false
FORCE_SSL_ADMIN: true
WP_AUTO_UPDATE_CORE: 'minor'
AUTOMATIC_UPDATER_DISABLED: no definida
WP_POST_REVISIONS: true
EMPTY_TRASH_DAYS: 30

### Páginas
ID,post_name,post_status
177,terminos-de-uso,publish
176,aviso-medico,publish
175,aviso-de-privacidad,publish
335,gracias,publish
173,preguntas-frecuentes,publish
174,contacto,publish
172,primera-consulta,publish
169,dra-irina-gonzalez-saez,publish
171,sueno,draft
170,otorrinolaringologia,draft
168,inicio-v2,publish
143,links,publish
106,inicio-antiguo,draft

### Menús
term_id,name,locations,count
22,Legal,legal,3
21,"Pie de página",footer,5
20,Principal,primary,6
position,title,object_id
1,Inicio,168
2,Otorrinolaringología,170
3,Sueño,171
4,Conóceme,169
5,"Primera consulta",172
6,Contacto,174

### Medios y CPT
adjuntos: 44
condicion: publish 0 · draft 14 · pending 0
tratamiento: publish 0 · draft 9 · pending 0
recurso: publish 0 · draft 3 · pending 0
credencial: publish 17 · draft 0 · pending 0
post: publish 0 · draft 0 · pending 0
```
