#!/usr/bin/env bash
# Sincroniza plugin y tema del repo al servidor con respaldo previo. No activa nada.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
TS="$(stamp)"
BK="\$HOME/deploy-backups/$TS"   # FUERA de wp-content: una copia dentro de plugins/ aparece como plugin en WordPress.
for pair in "wp-content/plugins/dra-irina-core" "wp-content/themes/irina-gonzalez"; do
  SRC="$REPO/$pair"; DEST="$WEBROOT/$pair"; NAME="$(basename "$pair")"
  echo "→ $pair"
  rssh "mkdir -p $BK; if [ -d '$DEST' ]; then cp -a '$DEST' $BK/$NAME; fi; mkdir -p '$DEST'"
  tar -C "$SRC" -czf - --exclude=node_modules --exclude=vendor . | rssh "tar -C '$DEST' -xzf -"
done
echo "Listo. Respaldos en ~/deploy-backups/$TS/. Activar con wp-admin o WP-CLI cuando proceda."
