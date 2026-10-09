#!/usr/bin/env bash
# Publica páginas no clínicas por decisión del propietario («publica las cuatro», 2026-10-09): Dra. Irina, Primera consulta,
# Contacto y Preguntas frecuentes. Quita el noindex de trabajo, publica (con todos los plugins: plantilla elementor_header_footer),
# limpia cachés de Elementor, purga y verifica. Reversible: go-live-pages.sh --rollback [slug ...].
# Uso: go-live-pages.sh [--rollback] [slug ...]   (sin slugs: las cuatro)
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
WPALL="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp"
wa(){ rssh "$WPALL $*"; }
pid(){ w "post list --post_type=page --post_name__in=$1 --post_status=any --field=ID" | head -1; }
ROLLBACK=0; SLUGS=()
for a in "$@"; do if [ "$a" = "--rollback" ]; then ROLLBACK=1; else SLUGS+=("$a"); fi; done
[ "${#SLUGS[@]}" -gt 0 ] || SLUGS=(dra-irina-gonzalez-saez primera-consulta contacto preguntas-frecuentes)

for slug in "${SLUGS[@]}"; do
  ID="$(pid "$slug")"; [ -n "$ID" ] || { echo "  falta $slug"; continue; }
  if [ "$ROLLBACK" = "1" ]; then
    wa "post update $ID --post_status=draft" >/dev/null
    w "post meta update $ID rank_math_robots --format=json '[\"noindex\",\"nofollow\"]'" >/dev/null
    echo "  $slug ($ID) → borrador, noindex"
    continue
  fi
  echo "→ $slug ($ID): $(w "post get $ID --field=post_status") · robots=$(w "post meta get $ID rank_math_robots --format=json" 2>/dev/null)"
  w "post meta delete $ID rank_math_robots" >/dev/null 2>&1 || true
  wa "post update $ID --post_status=publish" >/dev/null
  w "post meta delete $ID _elementor_element_cache" >/dev/null 2>&1 || true
  w "post meta delete $ID _elementor_css" >/dev/null 2>&1 || true
  echo "  ahora: $(w "post get $ID --field=post_status") · robots=$(w "post meta get $ID rank_math_robots --format=json" 2>/dev/null || echo '(sin meta)')"
done

echo "→ Cachés"
w "cache flush" >/dev/null; w "litespeed-purge all" >/dev/null 2>&1 || true
[ "$ROLLBACK" = "1" ] && { echo "Rollback hecho."; exit 0; }

echo "→ Verificación"
for slug in "${SLUGS[@]}"; do
  u="$SITE_URL/$slug/"; html="$(curl -s -A 'Mozilla/5.0 (Windows NT 10.0) Chrome/130' "$u?cb=$(date +%s)")"
  code="$(curl -s -o /dev/null -w '%{http_code}' "$u?cb=$(date +%s)")"
  printf '  %-28s %s · title=%s · robots=%s · canonical=%s · ld=%s\n' "$slug" "$code" \
    "$(printf '%s' "$html" | grep -o '<title>[^<]*</title>' | head -1 | sed 's/<[^>]*>//g' | cut -c1-60)" \
    "$(printf '%s' "$html" | grep -o '<meta name="robots" content="[^"]*"' | head -1 | sed 's/.*content="//;s/"$//' | cut -c1-20)" \
    "$(printf '%s' "$html" | grep -o '<link rel="canonical" href="[^"]*"' | head -1 | sed 's/.*href="//;s/"$//')" \
    "$(printf '%s' "$html" | grep -c 'application/ld+json')"
done
echo "  sitemap: $(curl -s "$SITE_URL/page-sitemap.xml?cb=$(date +%s)" | grep -o '<loc>[^<]*</loc>' | sed 's/<[^>]*>//g' | tr '\n' ' ')"
echo "  menú principal (enlaces visibles en portada): $(curl -s "$SITE_URL/?cb=$(date +%s)" | grep -o 'href="https://drairinagonzalez.com/[a-z-]*/"' | sort -u | tr '\n' ' ')"
echo "Recuerda: José vacía la caché del CDN en hPanel y, en Search Console, reenvía sitemap_index.xml. Rollback: $0 --rollback"
