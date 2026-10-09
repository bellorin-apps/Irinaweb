#!/usr/bin/env bash
# Publica las fichas clínicas (condicion/tratamiento/recurso) que la Dra. aprobó en la mini app (estado medically_approved)
# y aún no están publicadas. Sin --yes solo lista candidatas (dry-run). El gate del plugin sigue activo: solo pasa lo aprobado.
# Uso: publish-approved.sh [--yes] [slug ...]   (sin slugs: todas las aprobadas)
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
YES=0; SLUGS=()
for a in "$@"; do if [ "$a" = "--yes" ]; then YES=1; else SLUGS+=("$a"); fi; done
ONLY="$(IFS=,; echo "${SLUGS[*]:-}")"
w "eval '
\$only = array_filter( explode( \",\", \"$ONLY\" ) );
\$posts = get_posts( [ \"post_type\" => [ \"condicion\", \"tratamiento\", \"recurso\" ], \"post_status\" => [ \"draft\", \"pending\", \"private\", \"future\" ], \"posts_per_page\" => 200, \"orderby\" => \"title\", \"order\" => \"ASC\" ] );
\$n = 0;
foreach ( \$posts as \$p ) {
  if ( \$only && ! in_array( \$p->post_name, \$only, true ) ) { continue; }
  \$state = \\DraIrina\\Core\\Workflow\\MedicalReview::state( \$p->ID );
  if ( ! in_array( \$state, [ \"medically_approved\", \"ready_to_publish\" ], true ) ) { continue; }
  ++\$n;
  if ( ! $YES ) { echo \"  candidata: \" . \$p->post_type . \" \" . \$p->post_name . \" (\" . \$p->ID . \") \" . \$state . \" aprobada \" . get_post_meta( \$p->ID, \"_di_approved_at\", true ) . \"\n\"; continue; }
  \$r = wp_update_post( [ \"ID\" => \$p->ID, \"post_status\" => \"publish\" ], true );
  \$st = get_post_status( \$p->ID );
  echo ( is_wp_error( \$r ) ? \"  ERROR \" . \$r->get_error_message() : \"  publicada \" ) . \$p->post_type . \" \" . \$p->post_name . \" (\" . \$p->ID . \") → \" . \$st . \" \" . get_permalink( \$p->ID ) . \"\n\";
}
echo ( \$n ? \$n : \"0\" ) . ( $YES ? \" procesadas\" : \" candidatas (dry-run; añade --yes para publicar)\" ) . \"\n\";
'"
if [ "$YES" = "1" ]; then
  w "cache flush" >/dev/null; w "litespeed-purge all" >/dev/null 2>&1 || true
  echo "→ Verificación"
  for u in $(w "post list --post_type=condicion,tratamiento,recurso --post_status=publish --field=url"); do
    printf '  %-70s %s\n' "$u" "$(curl -s -o /dev/null --max-time 20 -w '%{http_code}' "$u?cb=$(date +%s)")"
  done
  for sm in condicion-sitemap.xml tratamiento-sitemap.xml recurso-sitemap.xml; do
    printf '  %-30s %s\n' "$sm" "$(curl -s -o /dev/null --max-time 20 -w '%{http_code}' "$SITE_URL/$sm?cb=$(date +%s)")"
  done
  echo "Recuerda: José vacía la caché del CDN en hPanel; después, Search Console reenvía el sitemap si aparecen tipos nuevos."
fi
