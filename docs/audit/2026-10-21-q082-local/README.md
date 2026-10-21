# Q-082 · cédulas solo en Conóceme (D-107) · integración local · 2026-10-21 (sesión local)

**Entrega de la nube:** 5f1d268. **Pendiente de auditoría y despliegue: no está en producción.**

## Corrección de un dato anterior

En la pregunta al abogado (D-107), la sesión local afirmó que el sitio **no** publicaba las cédulas. **Era falso:** en producción, `publicar_cedulas = 1` y el pie de todas las páginas muestra «Cédulas: 12438166 · 15111342» (comprobado el 21-oct).
- **Causa:** la comprobación anterior leyó el HTML con una codificación equivocada y no encontró «Cédula».
- **Efecto:** el criterio del abogado, «solo en Conóceme», sigue siendo aplicable. Q-082 **quita** las cédulas del pie y las muestra en Conóceme con su institución.
- **Comunicado a José** en el chat local.

## Copia aislada (WordPress local)

**Preparación:**
- Ajustes con los valores reales y públicos de D-107: números, instituciones y `publicar_cedulas`.
- Aviso médico ES (31) y EN (102) actualizados desde los builds de 5f1d268, **de forma dirigida**: solo esas dos páginas.

| Prueba | Resultado |
|---|---|
| `tools/audit/q082/cedulas_check.py` (`cedulas.log`) | **30/30.** Solo `/dra-irina-gonzalez-saez/` («Céd. Prof. 12438166 · UNEFM – UDEM», «Céd. Esp. 15111342 · Universidad Centroccidental Lisandro Alvarado») y `/en/dr-irina-gonzalez-saez/` («Professional license (cédula) …», «Specialty license (cédula) …»). Ninguna otra ruta muestra los números. El pie solo lleva el Consejo, en ES y EN. Los avisos médicos ES y EN enlazan a Conóceme y About, sin «al pie». |
| Capturas (`capturas/`) | About ES y EN a 1440 y 390 px: bloque discreto bajo las credenciales, visible (opacidad 1) |
| Regresión (`regresion.log`) | `seo_check` local sin fallos; aviso de idioma 37/37 y 9/9; barra horizontal en About ES/EN 14/14 |
| Estático | `php -l` en los 4 PHP, PHPCS con salida 0 y `tools/lint` (ESLint y stylelint) con salida 0 |

## Comprobación previa de producción (solo lectura, `precheck-legales-produccion.log`)

El aviso médico ES (176, publicado) y el EN (390, publicado) son **idénticos a sus builds anteriores** (d141669). Una actualización dirigida desde los builds de 5f1d268 solo cambia la frase de la remisión.

## Plan propuesto para producción (requiere el GO de Codex)

1. `deploy-code.sh`, con respaldo de la base de datos.
2. Antes de la purga, guardar `cedula_med_inst` = «UNEFM – UDEM» y `cedula_esp_inst` = «Universidad Centroccidental Lisandro Alvarado». Los números y `publicar_cedulas` ya están en producción.
3. **Actualización dirigida** del `post_content` de las páginas 176 (ES) y 390 (EN) desde los builds. Respaldo de su contenido actual en la carpeta del respaldo.
   - **No** se usa `setup-pages.sh`: además de los legales, reescribe las páginas Elementor aprobadas en español.
   - `setup-en` no toca la página EN publicada.
4. Purga de LiteSpeed.
5. Verificación:
   - `cedulas_check.py` con base en producción: 30/30;
   - `seo_check --prod`;
   - regresión del aviso de idioma y de la barra horizontal;
   - de las 16 EN, solo debe cambiar la 390 (la frase prevista); las demás, idénticas.

**Límites:** sin Rank Math ni LiteSpeed en la copia, salvo `seo_check` con Rank Math local. No acredita despliegue.
