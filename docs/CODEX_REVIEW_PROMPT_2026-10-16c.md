# Encargo para Codex: revisión publicitaria de las opiniones de Doctoralia ANTES de activarlas (D-012, D-091)

Lo preparó Claude (sesión cloud) el 2026-10-16. Mismo rol y reglas que `docs/CODEX_REVIEW_PROMPT.md` y `AGENTS.md`: veredicto con
evidencia, QA.md, BATON.md y mensaje para el otro agente. No despliegas, no activas el ajuste, no entras a Doctoralia con la cuenta
de la Dra., no lees secretos. No es asesoría legal: señala riesgos y lo que convendría consultar con un abogado (8.6).

## Qué hay construido (inactivo)

| Pieza | Dónde | Estado |
|---|---|---|
| Ajustes `doctoralia_opiniones` (casilla, apagada) y `doctoralia_widget_id` (vacío) | `dra-irina-core/src/Settings/PracticeSettings.php` | Sin activar |
| Widget Elementor `di-opiniones` | `src/Rendering/Elementor/Opiniones.php` | Registrado |
| Plantilla: no imprime nada si la casilla está apagada, falta el identificador o falta `doctoralia_url` | `irina-gonzalez/templates/parts/blocks/opiniones.php` | — |
| Enlace oficial `a#zl-url.zl-url` con `data-zlw-doctor`, `data-zlw-type="big"`, `data-zlw-opinion="true"`, `data-zlw-hide-branding="true"`, `rel="nofollow noopener"` y evento `doctoralia_click` | misma plantilla | — |
| Carga diferida de `https://platform.docplanner.com/js/widget.js` cuando la sección se acerca a 400 px de la pantalla | `assets/js/main.js` (`data-di-doctoralia`) | — |
| Sección «Opiniones · Lo que dicen *sus pacientes*» antes del CTA final de «Conóceme» | `tools/content/build-dra.py` → `dra-elementor.json` | Sin cargar con setup-dra.sh |

## Qué revisar

1. **Publicidad sanitaria.** Revisa si mostrar opiniones de pacientes encaja en la Ley General de Salud y en su Reglamento en materia de Publicidad: testimonios, promesas de resultados, Aviso de Publicidad COFEPRIS (D-037) y lo que exige el aviso médico. Revisa también si el título y el texto de la sección («Lo que dicen *sus pacientes*»; «Opiniones de pacientes publicadas en Doctoralia, mostradas con su widget oficial y sin editar») son adecuados o conviene otra redacción.
2. **D-012.** Confirma que no se transcribe, filtra ni edita ninguna opinión: solo el widget oficial.
3. **Marcado oficial.** El marcado se escribió según el formato público del widget de Docplanner/Doctoralia y no se ha comparado con el código del panel de la Dra. Indica qué debe copiar José del panel (Perfil › Widgets) y qué valor va en `doctoralia_widget_id`. Si el código oficial difiere (tipo, atributos o URL del script), propón el cambio.
4. **Privacidad.** Hoy el script de terceros se carga sin que la persona interactúe, en cuanto la sección se acerca a la pantalla. El aviso de privacidad (§9) dice que Doctoralia «puede instalar sus propias cookies cuando usted interactúa». Elige una de dos opciones:
   - (a) cargar el widget solo al pulsar «Ver opiniones en Doctoralia»;
   - (b) actualizar §9 del aviso.
   Recomienda una con su motivo.
5. **Rendimiento y accesibilidad.** Comprueba:
   - que la portada y las demás páginas no cargan el script;
   - el impacto en LCP/CLS de «Conóceme» (la sección reserva 120 px);
   - el foco y el nombre accesible del enlace;
   - que no rompe la CSP ni las cabeceras de SECURITY.md.
6. **Analítica.** Comprueba que `doctoralia_click` se mide igual que en el resto del sitio.

## Veredicto esperado

- `GO`, `GO con cambios` (con la lista exacta) o `NO-GO` con motivo, registrado en QA y en DECISIONS (D-091).
- Con `GO`, el orden es este:
  1. José copia el identificador del widget y marca la casilla en Dra. Irina › Consultorio.
  2. La sesión local ejecuta `setup-dra.sh` y verifica.
- Hasta el veredicto no se activa nada.
