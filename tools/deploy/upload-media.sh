#!/usr/bin/env bash
# Sube una imagen local: upload-media.sh <ruta-local> <slug> ["Título"]. Conserva el adjunto anterior con slug de archivo.
# El archivo se guarda con sufijo de versión (<slug>-v<AAAAMMDDHHMM>.<ext>) para que la URL cambie en cada subida y ni el navegador
# ni la CDN sirvan la imagen anterior (2026-10-10: la foto nueva del hero no se veía porque hero-dra.png conservaba la URL). El slug no cambia.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
SRC="$1"; SLUG="$2"; TITLE="${3:-$2}"
[[ "$SLUG" =~ ^[a-z0-9]+(-[a-z0-9]+)*$ ]] || { echo "Slug inválido"; exit 2; }
[[ "$TITLE" != *"'"* ]] || { echo "El título no puede contener comilla simple en este canal SSH"; exit 2; }
[ -f "$SRC" ] || { echo "No existe $SRC"; exit 1; }
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
EXT="${SRC##*.}"; TMP="/tmp/$SLUG-v$(date +%Y%m%d%H%M%S).$EXT"
[[ "$EXT" =~ ^[a-zA-Z0-9]+$ ]] || { echo "Extensión inválida"; exit 2; }
rscp "$SRC" "$SSH_USER@$SSH_HOST:$TMP"
OLD="$(w "post list --post_type=attachment --post_name__in=$SLUG --post_status=any --field=ID" | head -1)"
ID="$(w "media import '$TMP' --title='$TITLE' --alt='$TITLE' --porcelain")"
# Solo después de importar con éxito: se conserva archivo, ID y referencias existentes.
if [ -n "$OLD" ]; then
  w "post update $OLD --post_name=$SLUG-archivo-$OLD" >/dev/null
  echo "  conservado adjunto anterior $OLD"
fi
w "post update $ID --post_name=$SLUG" >/dev/null
if [ "$SLUG" = "hero-home" ]; then w "transient delete irina_physician_image_id" >/dev/null 2>&1 || true; fi
rssh "rm -f '$TMP'"
echo "ID=$ID slug=$(w "post get $ID --field=post_name") url=$(w "post get $ID --field=guid")"
w "media image-size" >/dev/null 2>&1 || true
