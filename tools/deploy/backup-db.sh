#!/usr/bin/env bash
# Respaldo privado y comprobado de la base de datos, sin desplegar nada (mismo bloque que deploy-code.sh, 12.1).
# Uso previsto: gate de lanzamiento de /en/ (docs/GATE_LANZAMIENTO_EN.md), antes de publish-en.sh --legal-ok --yes.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
TS="$(stamp)"
BK="\$HOME/deploy-backups/$TS"   # FUERA de wp-content.
rssh "set -e; umask 077; mkdir -p $BK; cd '$WEBROOT'; /opt/alt/php84/usr/bin/php /usr/local/bin/wp db export $BK/db.sql --skip-plugins --skip-themes --quiet; \
  tail -c 200 $BK/db.sql | grep -q 'Dump completed'; gzip $BK/db.sql; gzip -t $BK/db.sql.gz; chmod 600 $BK/db.sql.gz; \
  echo \"  db.sql.gz \$(du -h $BK/db.sql.gz | cut -f1), \$(zcat $BK/db.sql.gz | grep -c '^CREATE TABLE') tablas\""
echo "Respaldo en ~/deploy-backups/$TS/db.sql.gz"
