#!/usr/bin/env bash
# Sube una imagen local a la biblioteca de medios: upload-media.sh <ruta-local> <slug> ["Título"]. Reemplaza si el slug ya existe (borra el adjunto anterior).
# El archivo se guarda con sufijo de versión (<slug>-v<AAAAMMDDHHMM>.<ext>) para que la URL cambie en cada subida y ni el navegador
# ni la CDN sirvan la imagen anterior (2026-10-10: la foto nueva del hero no se veía porque hero-dra.png conservaba la URL). El slug no cambia.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
SRC="$1"; SLUG="$2"; TITLE="${3:-$2}"
[ -f "$SRC" ] || { echo "No existe $SRC"; exit 1; }
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
EXT="${SRC##*.}"; TMP="/tmp/$SLUG-v$(date +%Y%m%d%H%M).$EXT"
rscp "$SRC" "$SSH_USER@$SSH_HOST:$TMP"
OLD="$(w "post list --post_type=attachment --post_name__in=$SLUG --post_status=any --field=ID" | head -1)"
[ -n "$OLD" ] && w "post delete $OLD --force" >/dev/null && echo "  reemplazado adjunto $OLD"
ID="$(w "media import '$TMP' --title='$TITLE' --alt='$TITLE' --porcelain")"
w "post update $ID --post_name=$SLUG" >/dev/null
rssh "rm -f '$TMP'"
echo "ID=$ID slug=$(w "post get $ID --field=post_name") url=$(w "post get $ID --field=guid")"
w "media image-size" >/dev/null 2>&1 || true
