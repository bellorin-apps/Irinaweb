#!/usr/bin/env bash
# Crea/actualiza las 23 fichas (padecimientos y tratamientos) desde tools/content/medical-drafts.json con todos los campos clínicos.
# Siempre en DRAFT con estado medical_review_required; el gate médico impide publicarlas. Las ya publicadas (aprobadas por la Dra.) no se tocan.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
rscp "$REPO/tools/content/medical-drafts.json" "$SSH_USER@$SSH_HOST:/tmp/medical-drafts.json"
w "eval '
\$d = json_decode( file_get_contents( \"/tmp/medical-drafts.json\" ), true );
\$ids = [];
foreach ( [ \"condicion\", \"tratamiento\" ] as \$type ) {
  foreach ( \$d[\$type] as \$r ) {
    \$q = get_posts( [ \"post_type\" => \$type, \"post_name__in\" => [ \$r[\"slug\"] ], \"post_status\" => \"any\", \"posts_per_page\" => 1, \"orderby\" => \"ID\", \"order\" => \"ASC\" ] ); // name+any no encuentra borradores/privadas (duplicó páginas el 07-10)
    \$content = wp_slash( \$r[\"html\"] );
    if ( \$q ) { \$id = \$q[0]->ID; if ( \"publish\" !== \$q[0]->post_status ) { wp_update_post( [ \"ID\" => \$id, \"post_title\" => \$r[\"titulo\"], \"post_content\" => \$content ] ); } echo \"  existe \"; }
    else { \$id = wp_insert_post( [ \"post_type\" => \$type, \"post_status\" => \"draft\", \"post_title\" => \$r[\"titulo\"], \"post_name\" => \$r[\"slug\"], \"post_content\" => \$content ] ); echo \"  creada \"; }
    \$ids[ \$r[\"slug\"] ] = \$id;
    if ( \"publish\" === get_post_status( \$id ) ) { echo \$type . \" \" . \$r[\"slug\"] . \" (\" . \$id . \") publicada: aprobada por la Dra., sin cambios\n\"; continue; }
    wp_set_object_terms( \$id, \$r[\"area\"], \"area\" );
    wp_set_object_terms( \$id, \$r[\"zona\"], \"zona\" );
    wp_set_object_terms( \$id, \"medical_review_required\", \"estado_medico\" );
    update_post_meta( \$id, \"di_resumen_paciente\", \$r[\"resumen\"] );
    update_post_meta( \$id, \"di_enfoque_dra\", \$r[\"enfoque_dra\"] );
    update_post_meta( \$id, \"di_faq\", \$r[\"faq\"] );
    update_post_meta( \$id, \"di_fuentes\", \$r[\"fuentes\"] );
    if ( \"condicion\" === \$type ) { foreach ( [ \"sintomas\", \"causas\", \"cuando_consultar\", \"diagnostico\" ] as \$k ) { update_post_meta( \$id, \"di_\" . \$k, \$r[\$k] ); } }
    else { foreach ( [ \"tipo\", \"oferta\", \"candidatos\", \"estudio_previo\", \"como_se_realiza\", \"recuperacion\", \"riesgos_y_alternativas\" ] as \$k ) { update_post_meta( \$id, \"di_\" . \$k, \$r[\$k] ); } }
    echo \$type . \" \" . \$r[\"slug\"] . \" (\" . \$id . \") \" . get_post_status( \$id ) . \"\n\";
  }
}
foreach ( \$d[\"condicion\"] as \$r ) { if ( \"publish\" === get_post_status( \$ids[ \$r[\"slug\"] ] ) ) { continue; } \$rel = array_values( array_filter( array_map( fn( \$s ) => \$ids[ \$s ] ?? 0, \$r[\"tratamientos\"] ) ) ); update_post_meta( \$ids[ \$r[\"slug\"] ], \"di_tratamientos_relacionados\", \$rel ); }
foreach ( \$d[\"tratamiento\"] as \$r ) { if ( \"publish\" === get_post_status( \$ids[ \$r[\"slug\"] ] ) ) { continue; } \$rel = array_values( array_filter( array_map( fn( \$s ) => \$ids[ \$s ] ?? 0, \$r[\"resuelve\"] ) ) ); update_post_meta( \$ids[ \$r[\"slug\"] ], \"di_que_resuelve\", \$rel ); }
foreach ( array_merge( \$d[\"condicion\"], \$d[\"tratamiento\"] ) as \$r ) { \$id = \$ids[ \$r[\"slug\"] ]; if ( \"publish\" === get_post_status( \$id ) ) { continue; } \$rel = array_values( array_filter( array_map( fn( \$s ) => \$ids[ \$s ] ?? 0, \$r[\"relacionados\"] ) ) ); update_post_meta( \$id, \"di_relacionados\", \$rel ); }
'"
rssh "rm -f /tmp/medical-drafts.json"
echo "Borradores listos (no publicados). Revisión de la Dra. en wp-admin → Padecimientos / Tratamientos."

echo "→ Recursos (artículos) de muestra en borrador"
rscp "$REPO/tools/content/recursos-drafts.json" "$SSH_USER@$SSH_HOST:/tmp/recursos-drafts.json"
w "eval '
foreach ( json_decode( file_get_contents( \"/tmp/recursos-drafts.json\" ), true ) as \$r ) {
  \$q = get_posts( [ \"post_type\" => \"recurso\", \"post_name__in\" => [ \$r[\"slug\"] ], \"post_status\" => \"any\", \"posts_per_page\" => 1, \"orderby\" => \"ID\", \"order\" => \"ASC\" ] );
  if ( \$q ) { echo \"  existe \" . \$r[\"slug\"] . \" (\" . \$q[0]->ID . \")\n\"; continue; }
  \$id = wp_insert_post( [ \"post_type\" => \"recurso\", \"post_status\" => \"draft\", \"post_title\" => \$r[\"titulo\"], \"post_name\" => \$r[\"slug\"], \"post_excerpt\" => \$r[\"excerpt\"], \"post_content\" => wp_slash( \$r[\"html\"] ) ] );
  wp_set_object_terms( \$id, \"medical_review_required\", \"estado_medico\" );
  echo \"  creado \" . \$r[\"slug\"] . \" (\" . \$id . \") draft\n\";
}'"
rssh "rm -f /tmp/recursos-drafts.json"
