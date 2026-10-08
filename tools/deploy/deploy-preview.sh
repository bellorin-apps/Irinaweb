#!/usr/bin/env bash
# Sube tools/preview/*.html, tokens.css y preview.css a $WEBROOT/preview/ (noindex). Idempotente.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
SRC="$REPO/tools/preview"; DEST="$WEBROOT/preview"
rssh "mkdir -p '$DEST'"
rscp "$SRC"/index.html "$SRC"/dra-irina.html "$SRC"/apnea.html "$SRC"/articulo.html "$SRC"/tokens.css "$SRC"/preview.css "$SSH_USER@$SSH_HOST:$DEST/"
rssh "printf 'Options -Indexes\nHeader set X-Robots-Tag \"noindex, nofollow\"\n' > '$DEST/.htaccess'"
code="$(curl -sL -o /dev/null -w '%{http_code}' "$SITE_URL/preview/")"
echo "preview: $code → $SITE_URL/preview/"
for p in dra-irina apnea articulo; do printf '%s: %s\n' "$p" "$(curl -sL -o /dev/null -w '%{http_code}' "$SITE_URL/preview/$p.html")"; done
curl -sIL "$SITE_URL/preview/" | grep -i x-robots-tag || echo "AVISO: falta X-Robots-Tag noindex"
