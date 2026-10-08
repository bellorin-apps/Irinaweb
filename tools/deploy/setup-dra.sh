#!/usr/bin/env bash
# Página de la Dra. (borrador hasta CP3) y credenciales del CPT `credencial` desde tools/content/*.json. Idempotente.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }

echo "→ Credenciales"
rscp "$REPO/tools/content/credenciales.json" "$SSH_USER@$SSH_HOST:/tmp/credenciales.json"
w "eval '
\$rows = json_decode( file_get_contents( \"/tmp/credenciales.json\" ), true );
foreach ( \$rows as \$r ) {
  \$q = get_posts( [ \"post_type\" => \"credencial\", \"name\" => \$r[\"slug\"], \"post_status\" => \"any\", \"posts_per_page\" => 1 ] );
  \$id = \$q ? \$q[0]->ID : wp_insert_post( [ \"post_type\" => \"credencial\", \"post_status\" => \"publish\", \"post_title\" => \$r[\"titulo\"], \"post_name\" => \$r[\"slug\"], \"menu_order\" => (int) \$r[\"orden\"] ] );
  wp_update_post( [ \"ID\" => \$id, \"post_title\" => \$r[\"titulo\"], \"menu_order\" => (int) \$r[\"orden\"] ] );
  foreach ( [ \"tipo\", \"institucion\", \"lugar\", \"anio\" ] as \$k ) { update_post_meta( \$id, \"di_\" . \$k, \$r[\$k] ); }
  update_post_meta( \$id, \"di_mostrar\", (bool) \$r[\"mostrar\"] );
  update_post_meta( \$id, \"di_verificado\", true );
  echo ( \$q ? \"  existe \" : \"  creada \" ) . \$r[\"slug\"] . \" (\" . \$id . \")\n\";
}'"
rssh "rm -f /tmp/credenciales.json"

echo "→ Página de la Dra. (se conserva el estado actual: borrador hasta CP3)"
P_DRA="$(w "post list --post_type=page --post_name__in=dra-irina-gonzalez-saez --post_status=any --field=ID" | head -1)"
[ -n "$P_DRA" ] || { echo "No existe la página dra-irina-gonzalez-saez; ejecuta setup-site.sh antes."; exit 1; }
rscp "$REPO/tools/content/dra-elementor.json" "$SSH_USER@$SSH_HOST:/tmp/dra-elementor.json"
w "post meta update $P_DRA _wp_page_template elementor_header_footer" >/dev/null
w "post meta update $P_DRA _elementor_edit_mode builder" >/dev/null
w "post meta update $P_DRA _elementor_template_type wp-page" >/dev/null
w "post meta update $P_DRA _elementor_version 4.3.4" >/dev/null
w "eval 'update_post_meta( $P_DRA, \"_elementor_data\", wp_slash( file_get_contents( \"/tmp/dra-elementor.json\" ) ) ); echo strlen( get_post_meta( $P_DRA, \"_elementor_data\", true ) ) . \" bytes\\n\";'"
rssh "rm -f /tmp/dra-elementor.json"
w "post meta delete $P_DRA _elementor_css" >/dev/null 2>&1 || true
# Elementor cachea el HTML de cada elemento en post meta; sin borrarlo, los cambios de _elementor_data no se ven.
w "post meta delete $P_DRA _elementor_element_cache" >/dev/null 2>&1 || true
w "post meta update $P_DRA rank_math_robots --format=json '[\"noindex\",\"nofollow\"]'" >/dev/null
w "cache flush" >/dev/null; w "litespeed-purge all" >/dev/null 2>&1 || true
echo "Página $P_DRA lista (estado: $(w "post get $P_DRA --field=post_status")). Vista previa con sesión iniciada: $SITE_URL/?page_id=$P_DRA&preview=true"
