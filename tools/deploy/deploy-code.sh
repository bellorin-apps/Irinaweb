#!/usr/bin/env bash
# Sincroniza plugin y tema del repo al servidor con respaldo previo. No activa nada.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
TS="$(stamp)"
for pair in "wp-content/plugins/dra-irina-core" "wp-content/themes/irina-gonzalez"; do
  SRC="$REPO/$pair"; DEST="$WEBROOT/$pair"
  echo "→ $pair"
  rssh "if [ -d '$DEST' ]; then cp -a '$DEST' '$DEST.bak-$TS'; fi; mkdir -p '$DEST'"
  tar -C "$SRC" -czf - --exclude=node_modules --exclude=vendor . | rssh "tar -C '$DEST' -xzf -"
done
echo "Listo. Respaldos: *.bak-$TS. Activar con wp-admin o WP-CLI cuando proceda."
