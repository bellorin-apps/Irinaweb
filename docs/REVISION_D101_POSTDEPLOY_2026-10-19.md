# Auditoría del despliegue D-101 recibido en aa821b1 · Codex · 2026-10-19

## Veredicto vigente

**D-101 COMPLETADO Y VERIFICADO dentro del alcance de código, sin publicación EN.** Entregaaa821b1: José ejecutó deploy-code.sh a las16:38 locales sobre9b52e1e. El estado anterior de bloqueo queda superado por esta ejecución atestada; no se repitió ni se rodeó el control de permisos por Codex.

| Elemento | Veredicto / evidencia |
|---|---|
| Correspondencia con GO | Los árboles Git wp-content de b83d9ee,9b52e1e y aa821b1 son iguales:4e908bc75f35f24b252819f81a094fd9b2b64741. Acredita igualdad del producto versionado, no todos los bytes del servidor. |
| Despliegue y respaldo | VERIFIED por atestación José/Claude:9b52e1e entregado, respaldo ~/deploy-backups/20261019-163838/ con DB460K/30tablas y plugin/tema, purga LiteSpeed. No se accedió al respaldo/base/configuración privada. |
| Q-075 | VERIFIED en HTTP público: /en/contact/404 sin Location; otras9rutasEN también404. |
| EN sin anunciar | Sitemap15/0EN;15ESsin selector/hreflang/enlacesEN; Doctoralia ausente. Comprobación independiente de Codex pasa. |
| Schema y páginas ES |15/15HTTP200;14es-MX y portadaes (P3); máximo1Physician y sin tipos de página repetidos en cada salida recogida; sin mensajes PHPFatal/Warning en HTML. |
| Estado EN |16draft/0publish por atestación del propietario/Claude. El404público corrobora inaccesibilidad, no distingue draft/private/trash. El informe usa rangos de IDs, no un inventario enumerado auditable; no derivar un recuento independiente de esos rangos. |
| Fotos/menú/consola/logs | Atestación de6páginas clave con0imágenes rotas, menú/capturaSueño correctos, consola sin errores. La ausencia declarada de debug.log/error_log no prueba ausencia universal de errores. Codex no repitió navegador ni accedió al servidor. |

Evidencia aportada: docs/audit/2026-10-19-d101/verificacion.md. Comprobación nueva: tools/audit/review-d101-public.py, docs/audit/2026-10-19-d101-codex/public.json. Solo HTTPGETpúblico y análisis JSON-LD/XML; no formularios, cuentas ni publicación. Los checks prueban las condiciones consultadas; no certifican el SHAservidor porHTTP ni restauración real del respaldo. Los md5aportados están truncados en el informe y no se presentan como hashes completos revalidados por Codex.

**Q-079 permanece VERIFIED_LOCAL** de4b1ac95:30/30 y prevalidación final. El helper de laboratorio no se desplegó ni cambió el producto. Q-077 VERIFIED_LOCAL (EN) con schemaES ahora comprobado públicamente; Q-078 ACCEPTED D-100. Producción declarada9b52e1e. **13.5REVIEW,13.7DOING,13.6OWNER**: falta lanzamientoEN aprobado, no volver a pedir ni repetir el despliegueD-101.

QA/PLAN/STATUS/NEXT/BATON actualizados y PROGRESS regenerado sin aumento: español221/228,83/86DONE; inglés9/19,3/7DONE. Codex no desplegó ni publicó ni accedió a secretos/cuentas/Sensia; cuatroHTML de José intactos y excluidos.

---

## Auditoría histórica anterior (superada en el estado del despliegue)

# Auditoría D-101 y cierre local Q-079 · Codex · 2026-10-19

## Entrega recibida

Base9b52e1e, rama ccr-6254502d-xly8ww, pull al día. La nueva entrega aclara que D-101 no se ejecutó y corrige el bypass de Q-079. Sustituye la situación anterior de evidencia pendiente; aquella comprobación fue correcta para b83d9ee/db723fe.

| Elemento | Veredicto | Evidencia y límite |
|---|---|---|
| Q-079 | VERIFIED_LOCAL, con ajuste final de auditoría | Repetidos30/30, symlinks y junctions reales en Windows, sin saltos. Control c151258 aportado:13fallos. Codex reproduce independientemente el bypass antiguo y confirma rechazo actual con salida3/marcadores preservados. |
| D-101 | NO EJECUTADO; bloqueo operativo de permisos atestado | QA y relevo de9b52e1e declaran que el control de permisos de la sesión local bloqueó deploy-code.sh y no se rodeó. No hay respaldo nuevo ni entrega de código. Codex no reproduce esa operación. |
| Verificación posterior | PENDIENTE, todavía no procede | Las lecturas registradas son previas, no posteriores:16ENdraft/0publish, plugin0.4.5, /en/contact/301aES, /en/ y /en/sleep/404, portadaES con es. No acreditan el guard nuevo desplegado. |

## Revisión y ajuste Q-079

sync.sh recorre cada componente desde LAB y rechaza enlaces/junctions o resoluciones físicas distintas antes de borrar; también examina enlaces dentro del destino. Bash syntax pasa. La suite30/30 incluye mkdeploy, preparación normal, privados ficticios excluidos, repetibilidad, destinos externos, enlaces intermedios/finales, junctions y preservación externa.

Codex completa la prevalidación requerida: se validan **ambos destinos antes de modificar el primero**. En9b52e1e todavía se validaba y modificaba el tema antes de revisar el plugin. Se separan las dos fases y se añade el negativo tema válido/plugin enlazado: salida3, tema local intacto. Es un ajuste del helper de laboratorio, sin cambio del producto.

Se actualiza tools/audit/review-q077-q079.py: ya no exige que el código actual sea vulnerable. Compara c151258 (salida0, marcadores externos modificados) con el helper corregido (salida3, intactos), y comprueba prevalidación de ambos destinos. Evidencia nueva en docs/audit/2026-10-19-q079-final/review.json; el JSON histórico de acc4cdd no se sobrescribe. Los ensayos son desechables, con rutas destructivas comprobadas dentro de la fixture y sin ejecutar el laboratorio real.

El cotejo offline del JSON SEO sigue sin fallos:15ENen-US, schema portada/About y sitemap30. Q-077 permanece VERIFIED_LOCAL y Q-078 ACCEPTED D-100. No se ensaya publicación para obtener evidencia adicional. No se afirma aislamiento universal frente a concurrencia hostil ni archivos fuente manipulados; los casos comprobados cubren el defecto de destino reportado.

## D-101: GO y siguiente paso

El GO de b83d9ee sigue vigente para **código solamente, EN en borrador**: no hay diff de producto bajo wp-content entre72dbf9a y9b52e1e. El bloqueo del control de permisos pertenece a la sesión local: no se elude ni se interpreta como despliegue completado. Claude/José deben resolverlo mediante el flujo normal de permisos local antes de ejecutar deploy-code.sh. No se necesita otro GO técnico por estos cambios exclusivos del laboratorio.

Una vez ejecutado el despliegue autorizado, registrar respaldo y SHA realmente entregado y comprobar16EN en borrador, /en/contact/404 sin301, ausencia selector/hreflang/sitemapEN, schemaES, páginas/fotos/menús y errores. No ejecutar setup-en/publish-en ni formularios/SMTP; Doctoralia apagado. El 301 observado antes de entregar es línea base, no fallo del guard ya desplegado.

13.5REVIEW,13.7DOING,13.6OWNER sin cambio; español221/228,83/86DONE; inglés9/19,3/7DONE. QA, PLAN, STATUS, NEXT, BATON y criterio de decisiones actualizados; PROGRESS regenerado. Codex no desplegó ni publicó, no leyó secretos ni accedió a cuentas/Sensia. CuatroHTML previos conservados/excluidos.
