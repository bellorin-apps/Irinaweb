#!/usr/bin/env bash
# Sube tools/preview/*.html, tokens.css y preview.css a $WEBROOT/preview/ (noindex). Idempotente.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
SRC="$REPO/tools/preview"; DEST="$WEBROOT/preview"
bash "$SRC/build.sh"
rssh "mkdir -p '$DEST'"
rssh "mkdir -p '$DEST/v1'"
rscp "$SRC"/index.html "$SRC"/dra-irina.html "$SRC"/apnea.html "$SRC"/articulo.html "$SRC"/tokens.css "$SRC"/preview.css "$SRC"/main.js "$SSH_USER@$SSH_HOST:$DEST/"
rscp "$SRC"/v1/*.html "$SRC"/v1/preview.css "$SSH_USER@$SSH_HOST:$DEST/v1/"
rssh "mkdir -p '$DEST/muestras'"
rscp "$SRC"/muestras/index.html "$SSH_USER@$SSH_HOST:$DEST/muestras/"
rssh "printf 'Options -Indexes\nHeader set X-Robots-Tag \"noindex, nofollow\"\nHeader set Cache-Control \"no-cache, must-revalidate\"\n' > '$DEST/.htaccess'"
code="$(curl -sL -o /dev/null -w '%{http_code}' "$SITE_URL/preview/")"
echo "preview: $code → $SITE_URL/preview/"
for p in dra-irina apnea articulo main.js v1/index muestras/index; do printf '%s: %s\n' "$p" "$(curl -sL -o /dev/null -w '%{http_code}' "$SITE_URL/preview/$p$( [ "$p" = main.js ] || echo .html )")"; done
# hCDN puede servir la primera respuesta sin la cabecera recién escrita: reintento con cache-busting.
curl -sIL "$SITE_URL/preview/" | grep -i x-robots-tag || curl -sIL "$SITE_URL/preview/?cb=$(date +%s)" | grep -i x-robots-tag || echo "AVISO: falta X-Robots-Tag noindex"
