#!/usr/bin/env bash
# 12.1 (José, 2026-10-16): ensayo de restauración de la base en una base de PRUEBA vacía creada por José en hPanel.
# Uso: bash tools/deploy/drill-db.sh <carpeta de ~/deploy-backups con db.sql.gz>
# Lee tools/deploy/drill-db.local.txt (fuera de Git) con tres líneas: DB_NAME=…, DB_USER=…, DB_PASS=…  · No imprime la contraseña.
# Protecciones: nunca la base de producción; la base de prueba debe estar vacía; la clave viaja por stdin a un archivo 600 que se borra.
# No toca producción: solo lee su número de filas por tabla para comparar.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
TS_DIR="${1:?Indica la carpeta del respaldo, p. ej. 20261016-134414}"
CFG="$HERE/drill-db.local.txt"
[ -f "$CFG" ] || { echo "Falta $CFG"; exit 2; }
DB_NAME="$(sed -n 's/^DB_NAME=//p' "$CFG" | tr -d '\r')"
DB_USER="$(sed -n 's/^DB_USER=//p' "$CFG" | tr -d '\r')"
DB_PASS="$(sed -n 's/^DB_PASS=//p' "$CFG" | tr -d '\r')"
[ -n "$DB_NAME" ] && [ -n "$DB_USER" ] && [ -n "$DB_PASS" ] || { echo "drill-db.local.txt incompleto"; exit 2; }
case "$DB_NAME$DB_USER" in *[!A-Za-z0-9_]*) echo "Nombre o usuario con caracteres no válidos"; exit 2;; esac
case "$DB_PASS" in *'"'*|*'\'*) echo "La contraseña no puede llevar comillas dobles ni barra invertida"; exit 2;; esac
WP="/opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins --skip-themes"
PROD_DB="$(rssh "cd '$WEBROOT' && $WP config get DB_NAME")"
[ "$DB_NAME" != "$PROD_DB" ] || { echo "ALTO: la base de prueba es la de producción"; exit 3; }
DUMP="\$HOME/deploy-backups/$TS_DIR/db.sql.gz"
CNF="\$HOME/.di-drill-$$.cnf"
# La clave entra por stdin al archivo 600; nunca en la línea de comandos.
printf '[client]\nhost=127.0.0.1\nuser=%s\npassword="%s"\n' "$DB_USER" "$DB_PASS" | rssh "umask 077; cat > $CNF"
rssh "set -u; trap 'rm -f $CNF' EXIT
  test -s $DUMP && gzip -t $DUMP || { echo 'Respaldo no encontrado o dañado'; exit 4; }
  N=\$(mysql --defaults-extra-file=$CNF -N -e \"SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME'\") || { echo 'No se pudo conectar a la base de prueba'; exit 5; }
  [ \"\$N\" = 0 ] || { echo \"ALTO: la base de prueba no está vacía (\$N tablas)\"; exit 6; }
  T0=\$(date +%s)
  zcat $DUMP | mysql --defaults-extra-file=$CNF $DB_NAME || { echo 'Falló la importación'; exit 7; }
  echo \"Importado en \$(( \$(date +%s) - T0 )) s\"
  cd '$WEBROOT'
  $WP db query \"SELECT table_name FROM information_schema.tables WHERE table_schema=DATABASE() ORDER BY table_name\" --skip-column-names > /tmp/di-drill-tables-$$
  OK=0; BAD=0
  while read -r t; do
    a=\$($WP db query \"SELECT COUNT(*) FROM \\\`\$t\\\`\" --skip-column-names)
    b=\$(mysql --defaults-extra-file=$CNF -N -e \"SELECT COUNT(*) FROM \\\`\$t\\\`\" $DB_NAME 2>/dev/null || echo FALTA)
    if [ \"\$a\" = \"\$b\" ]; then OK=\$((OK+1)); else BAD=\$((BAD+1)); echo \"  distinta: \$t producción=\$a prueba=\$b\"; fi
  done < /tmp/di-drill-tables-$$
  rm -f /tmp/di-drill-tables-$$
  echo \"Tablas con el mismo número de filas: \$OK · distintas: \$BAD\"
  echo \"(Si hay diferencias pequeñas en options o sesiones, son cambios posteriores al respaldo.)\""
echo "Ensayo terminado. José borra la base de prueba en hPanel; borra también tools/deploy/drill-db.local.txt."
