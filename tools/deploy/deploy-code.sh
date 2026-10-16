#!/usr/bin/env bash
# Sincroniza plugin y tema del repo al servidor con respaldo previo. No activa nada.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
TS="$(stamp)"
BK="\$HOME/deploy-backups/$TS"   # FUERA de wp-content: una copia dentro de plugins/ aparece como plugin en WordPress.
# 12.1 (José, 2026-10-16): copia privada de la base antes de cada despliegue, comprobada; si falla, no se despliega.
echo "→ base de datos"
rssh "set -e; mkdir -p $BK; umask 077; cd '$WEBROOT'; /opt/alt/php84/usr/bin/php /usr/local/bin/wp db export $BK/db.sql --skip-plugins --skip-themes --quiet; \
  tail -c 200 $BK/db.sql | grep -q 'Dump completed'; gzip $BK/db.sql; gzip -t $BK/db.sql.gz; chmod 600 $BK/db.sql.gz; \
  echo \"  db.sql.gz \$(du -h $BK/db.sql.gz | cut -f1), \$(zcat $BK/db.sql.gz | grep -c '^CREATE TABLE') tablas\""
for pair in "wp-content/plugins/dra-irina-core" "wp-content/themes/irina-gonzalez"; do
  SRC="$REPO/$pair"; DEST="$WEBROOT/$pair"; NAME="$(basename "$pair")"
  echo "→ $pair"
  rssh "mkdir -p $BK; if [ -d '$DEST' ]; then cp -a '$DEST' $BK/$NAME; fi; mkdir -p '$DEST'"
  tar -C "$SRC" -czf - --exclude=node_modules --exclude=vendor . | rssh "tar -C '$DEST' -xzf -"
done
echo "Listo. Respaldos en ~/deploy-backups/$TS/. Activar con wp-admin o WP-CLI cuando proceda."
