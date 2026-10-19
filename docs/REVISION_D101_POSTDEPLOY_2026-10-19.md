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
