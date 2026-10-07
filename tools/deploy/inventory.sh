#!/usr/bin/env bash
# Inventario de solo lectura del WordPress en producción. No modifica nada.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
OUT="$REPO/docs/audit"; mkdir -p "$OUT"; FILE="$OUT/inventory-$(stamp).txt"

rssh "bash -s" > "$FILE" <<REMOTE
set -u
cd '$WEBROOT' || exit 1
echo '== dominios en la cuenta =='; ls ~/domains
echo '== webroot =='; pwd; ls -la | head -60
echo '== PHP =='; php -v | head -1; '/opt/alt/php84/usr/bin/php' -v | head -1 2>/dev/null
echo '== WP-CLI =='; (command -v wp && wp --version) || echo 'wp no disponible'
if command -v wp >/dev/null; then
  for c in 'core version' 'option get home' 'option get siteurl' 'option get blog_public' 'option get permalink_structure' 'option get timezone_string' 'theme list --format=csv' 'plugin list --format=csv' 'post list --post_type=page --post_status=any --fields=ID,post_title,post_name,post_status --format=csv' 'post list --post_type=post --post_status=any --fields=ID,post_title,post_name,post_status --format=csv' 'user list --fields=ID,user_login,roles --format=csv' 'cron event list --fields=hook,next_run_relative --format=csv' 'rewrite list --format=csv' 'menu list --format=csv'; do
    echo "== wp \$c =="; wp \$c 2>&1 | head -200
  done
else
  echo '== versión por archivo =='; grep -m1 "wp_version =" wp-includes/version.php
  echo '== plugins (carpetas) =='; ls wp-content/plugins
  echo '== themes (carpetas) =='; ls wp-content/themes
  echo '== mu-plugins =='; ls wp-content/mu-plugins 2>/dev/null || echo 'ninguno'
fi
echo '== wp-config (solo flags) =='; grep -E "WP_DEBUG|WP_CACHE|DISALLOW_FILE_EDIT|WP_MEMORY_LIMIT|FORCE_SSL|WP_HOME|WP_SITEURL" wp-config.php | grep -viE "salt|key" || echo 'sin flags'
echo '== robots.txt =='; cat robots.txt 2>/dev/null || echo '(no existe archivo; lo genera WP)'
echo '== .htaccess (primeras 60 líneas) =='; head -60 .htaccess 2>/dev/null
echo '== cabeceras home =='; curl -sI '$SITE_URL/' | head -25
echo '== cabeceras dominio alterno =='; curl -sI 'https://otorrino-monterrey.com/' | head -15
echo '== robots/sitemap por http =='; curl -s '$SITE_URL/robots.txt'; curl -sI '$SITE_URL/sitemap_index.xml' | head -3; curl -sI '$SITE_URL/wp-sitemap.xml' | head -3
REMOTE
echo "Inventario guardado en $FILE"
