#!/usr/bin/env bash
# Imagen por defecto para compartir en redes (og:image / twitter:image), 1200×630 JPEG. Idempotente.
# Sube wp-content/themes/irina-gonzalez/assets/brand/og-default.jpg con el slug og-default (se conserva el adjunto anterior)
# y la fija como imagen Open Graph por defecto de Rank Math (open_graph_image + open_graph_image_id en rank-math-options-titles).
# Rank Math la usa en toda página sin imagen destacada propia; la tarjeta de Twitter/X reutiliza la de Facebook (ajuste por defecto).
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }
"$HERE/upload-media.sh" "$REPO/wp-content/themes/irina-gonzalez/assets/brand/og-default.jpg" og-default 'Dra. Irina González Sáez · Tu Otorrino Especialista en Sueño'
ID="$(w "post list --post_type=attachment --post_name__in=og-default --post_status=any --field=ID" | head -1)"
URL="$(w "post get $ID --field=guid")"
case "$URL" in *.jpg|*.jpeg) ;; *) echo "La imagen no quedó en JPEG ($URL): revisar WebpUpload (prefijo og-)"; exit 1;; esac
w "option patch update rank-math-options-titles open_graph_image '$URL'" >/dev/null
w "option patch update rank-math-options-titles open_graph_image_id $ID" >/dev/null
echo "og-default: ID=$ID url=$URL"
echo "Rank Math: open_graph_image=$(w "option pluck rank-math-options-titles open_graph_image") id=$(w "option pluck rank-math-options-titles open_graph_image_id")"
echo "Después: purga (tools/deploy) y comprobar: curl -s https://drairinagonzalez.com/ | grep -o '<meta property=\"og:image[^>]*>'"
