#!/usr/bin/env bash
# Carga páginas base (Elementor), legales (HTML) y metadatos SEO desde tools/content/pages/. NO cambia el estado de las páginas (siguen en borrador hasta CP3).
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
SRC="$REPO/tools/content/pages"
pid(){ w "post list --post_type=page --post_name__in=$1 --post_status=any --field=ID" | head -1; }

echo "→ Páginas Elementor"
for slug in otorrinolaringologia sueno primera-consulta contacto preguntas-frecuentes links; do
  ID="$(pid "$slug")"; [ -n "$ID" ] || { echo "  falta $slug (ejecuta setup-site.sh)"; continue; }
  rscp "$SRC/$slug.elementor.json" "$SSH_USER@$SSH_HOST:/tmp/$slug.json"
  # Páginas con plantilla elementor_header_footer: WP-CLI valida la plantilla, por eso el meta va directo.
  w "post meta update $ID _wp_page_template elementor_header_footer" >/dev/null
  w "post meta update $ID _elementor_edit_mode builder" >/dev/null
  w "post meta update $ID _elementor_template_type wp-page" >/dev/null
  w "post meta update $ID _elementor_version 4.3.4" >/dev/null
  w "eval 'update_post_meta( $ID, \"_elementor_data\", wp_slash( file_get_contents( \"/tmp/$slug.json\" ) ) );'"
  w "post meta delete $ID _elementor_css" >/dev/null 2>&1 || true
  w "post meta delete $ID _elementor_element_cache" >/dev/null 2>&1 || true
  rssh "rm -f /tmp/$slug.json"
  echo "  $slug ($ID) estado $(w "post get $ID --field=post_status")"
done

echo "→ Legales (contenido HTML, plantilla por defecto)"
for slug in aviso-de-privacidad aviso-medico terminos-de-uso; do
  ID="$(pid "$slug")"; [ -n "$ID" ] || { echo "  falta $slug"; continue; }
  rscp "$SRC/$slug.html" "$SSH_USER@$SSH_HOST:/tmp/$slug.html"
  w "eval 'wp_update_post( [ \"ID\" => $ID, \"post_content\" => wp_slash( file_get_contents( \"/tmp/$slug.html\" ) ) ] );'"
  w "post meta delete $ID _wp_page_template" >/dev/null 2>&1 || true
  rssh "rm -f /tmp/$slug.html"
  echo "  $slug ($ID) estado $(w "post get $ID --field=post_status")"
done

echo "→ SEO (Rank Math title/description)"
rscp "$SRC/seo.json" "$SSH_USER@$SSH_HOST:/tmp/seo.json"
w "eval '
\$seo = json_decode( file_get_contents( \"/tmp/seo.json\" ), true );
foreach ( \$seo as \$slug => \$m ) {
  \$p = get_page_by_path( \$slug, OBJECT, \"page\" );
  if ( ! \$p ) { \$q = get_posts( [ \"post_type\" => \"page\", \"name\" => \$slug, \"post_status\" => \"any\", \"posts_per_page\" => 1 ] ); \$p = \$q ? \$q[0] : null; }
  if ( ! \$p ) { echo \"  sin página: \$slug\n\"; continue; }
  update_post_meta( \$p->ID, \"rank_math_title\", \$m[\"title\"] );
  update_post_meta( \$p->ID, \"rank_math_description\", \$m[\"description\"] );
  echo \"  \$slug (\" . \$p->ID . \") OK\n\";
}'"
rssh "rm -f /tmp/seo.json"
w "cache flush" >/dev/null; w "litespeed-purge all" >/dev/null 2>&1 || true
echo "Listo. Las páginas conservan su estado; vista previa con sesión: $SITE_URL/?page_id=<ID>&preview=true"
