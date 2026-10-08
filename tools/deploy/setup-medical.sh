#!/usr/bin/env bash
# Crea/actualiza borradores de padecimientos y tratamientos desde tools/content/medical-drafts.json. Siempre en DRAFT; el gate médico impide publicarlos.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
rscp "$REPO/tools/content/medical-drafts.json" "$SSH_USER@$SSH_HOST:/tmp/medical-drafts.json"
w "eval '
\$d = json_decode( file_get_contents( \"/tmp/medical-drafts.json\" ), true );
\$ids = [];
foreach ( [ \"condicion\", \"tratamiento\" ] as \$type ) {
  foreach ( \$d[\$type] as \$r ) {
    \$q = get_posts( [ \"post_type\" => \$type, \"name\" => \$r[\"slug\"], \"post_status\" => \"any\", \"posts_per_page\" => 1 ] );
    \$content = \"<p>\" . esc_html( \$r[\"resumen\"] ) . \"</p><p><strong>\" . esc_html( \$r[\"nota\"] ) . \"</strong></p>\";
    if ( \$q ) { \$id = \$q[0]->ID; if ( \"publish\" !== \$q[0]->post_status ) { wp_update_post( [ \"ID\" => \$id, \"post_title\" => \$r[\"titulo\"] ] ); } echo \"  existe \"; }
    else { \$id = wp_insert_post( [ \"post_type\" => \$type, \"post_status\" => \"draft\", \"post_title\" => \$r[\"titulo\"], \"post_name\" => \$r[\"slug\"], \"post_content\" => \$content ] ); echo \"  creada \"; }
    \$ids[ \$r[\"slug\"] ] = \$id;
    wp_set_object_terms( \$id, \$r[\"area\"], \"area\" );
    wp_set_object_terms( \$id, \$r[\"zona\"], \"zona\" );
    wp_set_object_terms( \$id, \"medical_review_required\", \"estado_medico\" );
    update_post_meta( \$id, \"di_resumen_paciente\", \$r[\"resumen\"] );
    if ( \"condicion\" === \$type ) { update_post_meta( \$id, \"di_sintomas\", \$r[\"sintomas\"] ); update_post_meta( \$id, \"di_cuando_consultar\", \$r[\"cuando_consultar\"] ); }
    else { update_post_meta( \$id, \"di_tipo\", \$r[\"tipo\"] ); update_post_meta( \$id, \"di_oferta\", \$r[\"oferta\"] ); update_post_meta( \$id, \"di_candidatos\", \$r[\"candidatos\"] ); }
    echo \$type . \" \" . \$r[\"slug\"] . \" (\" . \$id . \") \" . get_post_status( \$id ) . \"\n\";
  }
}
foreach ( \$d[\"condicion\"] as \$r ) { \$rel = array_values( array_filter( array_map( fn( \$s ) => \$ids[ \$s ] ?? 0, \$r[\"tratamientos\"] ) ) ); update_post_meta( \$ids[ \$r[\"slug\"] ], \"di_tratamientos_relacionados\", \$rel ); }
foreach ( \$d[\"tratamiento\"] as \$r ) { \$rel = array_values( array_filter( array_map( fn( \$s ) => \$ids[ \$s ] ?? 0, \$r[\"resuelve\"] ) ) ); update_post_meta( \$ids[ \$r[\"slug\"] ], \"di_que_resuelve\", \$rel ); }
'"
rssh "rm -f /tmp/medical-drafts.json"
echo "Borradores listos (no publicados). Revisión de la Dra. en wp-admin → Padecimientos / Tratamientos."

echo "→ Recursos (artículos) de muestra en borrador"
rscp "$REPO/tools/content/recursos-drafts.json" "$SSH_USER@$SSH_HOST:/tmp/recursos-drafts.json"
w "eval '
foreach ( json_decode( file_get_contents( \"/tmp/recursos-drafts.json\" ), true ) as \$r ) {
  \$q = get_posts( [ \"post_type\" => \"recurso\", \"name\" => \$r[\"slug\"], \"post_status\" => \"any\", \"posts_per_page\" => 1 ] );
  if ( \$q ) { echo \"  existe \" . \$r[\"slug\"] . \" (\" . \$q[0]->ID . \")\n\"; continue; }
  \$id = wp_insert_post( [ \"post_type\" => \"recurso\", \"post_status\" => \"draft\", \"post_title\" => \$r[\"titulo\"], \"post_name\" => \$r[\"slug\"], \"post_excerpt\" => \$r[\"excerpt\"], \"post_content\" => wp_slash( \$r[\"html\"] ) ] );
  wp_set_object_terms( \$id, \"medical_review_required\", \"estado_medico\" );
  echo \"  creado \" . \$r[\"slug\"] . \" (\" . \$id . \") draft\n\";
}'"
rssh "rm -f /tmp/recursos-drafts.json"
