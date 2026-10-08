#!/usr/bin/env bash
# Conmuta la portada a la Home v2 (168) por decisión del propietario (CP3, 2026-10-08). Reversible: ver "Rollback" al final.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
# `post update` de una página con plantilla elementor_header_footer falla con "Plantilla de página no válida"
# si Elementor no está cargado (WP-CLI valida page_template). Para esas órdenes, todos los plugins.
WPALL="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp"
wa(){ rssh "$WPALL $*"; }
NEW="${1:-168}"; OLD="${2:-106}"

echo "→ Estado previo"
echo "  show_on_front=$(w "option get show_on_front") page_on_front=$(w "option get page_on_front")"
echo "  $NEW: $(w "post get $NEW --field=post_status") · robots=$(w "post meta get $NEW rank_math_robots --format=json" 2>/dev/null)"

echo "→ Portada = $NEW (publicada, sin noindex)"
wa "post update $NEW --post_status=publish --post_title='Inicio'" >/dev/null
w "post meta delete $NEW rank_math_robots" >/dev/null 2>&1 || true
w "option update show_on_front page" >/dev/null
w "option update page_on_front $NEW" >/dev/null

echo "→ Portada anterior $OLD → borrador «Inicio (antiguo)»"
wa "post update $OLD --post_status=draft --post_title='Inicio (antiguo)' --post_name=inicio-antiguo" >/dev/null

echo "→ Plantillas Theme Builder heredadas (39 header, 79 footer) → borrador (elementor_library no admite papelera) (D-029)"
for t in 39 79; do w "post get $t --field=post_type" >/dev/null 2>&1 && w "post update $t --post_status=draft" >/dev/null && echo "  $t en borrador"; done
w "option delete elementor_pro_theme_builder_conditions" >/dev/null 2>&1 || true

echo "→ Rewrites y cachés"
w "option delete rewrite_rules" >/dev/null; curl -s -o /dev/null "$SITE_URL/"
w "post meta delete $NEW _elementor_element_cache" >/dev/null 2>&1 || true
w "cache flush" >/dev/null; w "litespeed-purge all" >/dev/null 2>&1 || true

echo "→ Verificación"
for u in "/" "/inicio-v2/" "/links/" "/op/" "/sitemap_index.xml" "/page-sitemap.xml"; do printf '  %-20s %s\n' "$u" "$(curl -sL -o /dev/null -w '%{http_code} → %{url_effective}' "$SITE_URL$u?cb=$(date +%s)")"; done
H="$(curl -sL "$SITE_URL/?cb=$(date +%s)")"
printf '  portada: di-header=%s di-bleed=%s noindex=%s Physician=%s\n' "$(echo "$H" | grep -c 'id="di-header"')" "$(echo "$H" | grep -c 'di-bleed__bg')" "$(echo "$H" | grep -c 'noindex')" "$(echo "$H" | grep -c '"@type":"Physician"')"
L="$(curl -sL "$SITE_URL/links/?cb=$(date +%s)")"
printf '  /links/: di-header=%s theme-builder=%s\n' "$(echo "$L" | grep -c 'id="di-header"')" "$(echo "$L" | grep -c 'data-elementor-type="header"')"
echo "  sitemap incluye /: $(curl -sL "$SITE_URL/page-sitemap.xml" | grep -c "<loc>$SITE_URL/</loc>")"
cat <<'ROLLBACK'
Rollback: wp option update page_on_front 106 && wp post update 106 --post_status=publish --post_title='Inicio' --post_name=inicio && wp post update 168 --post_status=publish && wp post meta update 168 rank_math_robots --format=json '["noindex","nofollow"]' && wp post update 39 79 --post_status=publish && rehacer _elementor_conditions (D-029) && purga.
ROLLBACK
