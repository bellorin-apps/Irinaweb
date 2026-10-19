# Gate de lanzamiento de /en/ (13.6 → 13.7 → cierre de 13.5)

Preparado por Claude local el 2026-10-19, a petición de Codex (c725b1b). **Este documento no autoriza publicar.** Se ejecuta solo cuando se cumplen todas las condiciones previas de abajo.

## 0. Condiciones previas (todas)

| # | Condición | Fuente | Estado al 19-oct |
|---|---|---|---|
| 1 | La Dra. aprobó en su guía las **8 páginas** (`_di_page_approved_at`) sobre el texto que se va a publicar | 13.6, D-098 | **8/8** (19-oct 17:48, en su guía; contenido = build) |
| 2 | La Dra. aprobó en su app las **5 fichas** (`medically_approved`) | 13.6, D-098 | **5/5** (17-oct, D-102) |
| 3 | Legales EN aceptados por José (`--legal-ok`) | D-099 | Hecho; no volver a pedirlo |
| 4 | **Orden expresa de José** de publicar `/en/` | D-098 | No dada |
| 5 | Código vigente desplegado | D-101 | Hecho (9b52e1e, verificado el 19-oct) |
| 6 | IndexNow aceptado tal como está | D-100 | Hecho: restaurar estados no revierte avisos externos, hooks ni cachés |

Si el texto de una página cambia después de aprobarse (por ejemplo, al aplicar `setup-en.sh` con builds nuevos), `setup-en.php` le **retira la aprobación** y la Dra. debe aprobarla de nuevo. Primero se sincroniza y después se aprueba; nunca al revés.

## 1. Lanzamiento (sesión local, con la orden de José)

1. `git pull` en `ccr-6254502d-xly8ww`, con el árbol limpio salvo los HTML de José.
2. `bash tools/deploy/publish-en.sh`: revisión sin `--yes`. Debe listar las 16 entradas listas y ningún bloqueo.
3. `bash tools/deploy/backup-db.sh`: respaldo comprobado. Anotar `~/deploy-backups/<ts>/`.
4. `bash tools/deploy/publish-en.sh --legal-ok --yes`: publicación todo o nada (D-098), con purga de caché y verificación pública. Si sale con 1, no improvisar:
   - leer qué restauró o qué quedó para revisión manual;
   - no volver a publicar para «arreglarlo».
5. `wp litespeed-purge all`. Si hay CDN, purgarlo también con permiso de José; no tocar la configuración del CDN.

## 2. Verificación final que cierra 13.5 (solo lectura)

```bash
python -I tools/audit/en-local/t/seo_check.py --base https://drairinagonzalez.com --prod docs/audit/<fecha>-en-prod/C-seo.json
```

Debe salir con **0**. Cubre:
- **HTTP:** 200 en las 15 EN sin seguir redirecciones; `/en/contact/` sin 301.
- **Canónicas y OG:** canónica propia, `og:locale en_US`, `og:url`, y `og:image` en HTTPS con respuesta 200.
- **Hreflang:** recíproco `es-MX` / `en-US` / `x-default` en los 15 pares.
- **Selector:** apunta a la pareja.
- **Sitemap:** 30 URL, sin Gracias ni Thank you.
- **Schema:** `inLanguage en-US`, paridad de nodos acordados con ES y un solo Physician.
- **Thank you:** `noindex`.
- **Enlaces internos:** los de las 15 EN responden 200.
- **Caché:** cabecera `x-litespeed-cache` (si falta, solo es aviso).

Además, a mano o con el navegador:
- vista móvil (390) y escritorio (1440) de `/en/`, `/en/sleep/` y `/en/ent/`;
- WhatsApp en inglés;
- consola sin errores;
- Doctoralia apagado.

**No** se prueba el formulario en producción.

Pasada de referencia previa (19-oct, inglés en borrador): `docs/audit/2026-10-19-13.5-prod/prelanzamiento.log`. Falla como se espera: 15 EN con 404 y sitemap 15/0.

## 3. Si algo falla después de publicar

- Si `publish-en.sh` falla, restaura los estados; leer su salida.
- Si la verificación pública falla después de un `Success`, **no se revierte nada automáticamente**:
  - revisar la caché y el estado real;
  - para retirar el inglés, usar la restauración de estados documentada o el respaldo del paso 3, con decisión de José.
- Los avisos de IndexNow ya enviados no se deshacen (D-100).

## 4. Aprobaciones registradas en producción (lectura del 19-oct)

Registro `briefing-privado/revision-log.jsonl` (`by: irina`, enlace de la guía de la Dra.) y metadatos de WordPress (usuario 2, rol `revisor_medico`). Horas de Monterrey.

| ID | Entrada | Aprobación | ¿Texto aprobado = borrador actual? | ¿= build actual del repositorio? |
|---|---|---|---|---|
| 384 | /en/ | 17-oct 10:45 | sí | sí |
| 386 | /en/dr-irina-gonzalez-saez/ | 17-oct 10:45 | sí | **no**: un titular cambió de formato (D-096: `<b>Ongoing</b> training…`) |
| 387 | /en/ent/ | 17-oct 10:45 | sí | sí |
| 394 | /en/sleep/ | 17-oct 10:46 | sí | sí |
| 389 | /en/first-visit/ | 17-oct 10:46 | sí | sí |
| 397 | /en/thank-you/ | 17-oct 10:46 | sí | sí |
| 388 | /en/faq/ | 17-oct 10:47 | sí | sí |
| 385 | /en/contact/ | **sin aprobar** | — | **no**: formato del titular (`<b>Would you</b> like…`) |
| 412–416 | 5 fichas (snoring, obstructive-sleep-apnea, home-sleep-test, cpap-therapy, snoring-and-sleep-apnea-surgery) | 17-oct 10:47–10:48 | sí | sí (sin cambios desde el 16-oct) |
| 390, 392, 395 | 3 legales | D-099 (José) | — | — |

La comparación se hizo texto a texto de `_elementor_data` contra `tools/content/en/*.elementor.json`, con los marcadores de imagen como comodín. Las dos diferencias son solo de negrita en el titular y no cambian la redacción.

**Actualización (D-102):** José confirmó que las aprobó la Dra. Se sincronizaron los borradores (`docs/audit/2026-10-19-13.6/sincronizacion.md`). Quedan **6/8 páginas y 5/5 fichas**; faltan About y Contact. Antes de ejecutar `setup-en.sh` otra vez, simular con `tools/audit/simula-setup-en.php` para saber qué aprobaciones caerían.
