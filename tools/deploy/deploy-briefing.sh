#!/usr/bin/env bash
# Despliega el briefing de la Dra. en $WEBROOT/briefing/ y crea config.php con token. Idempotente.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"

SRC="$REPO/tools/briefing"
DEST="$WEBROOT/briefing"
DATA_DIR="$DOMAIN_ROOT/briefing-privado"

echo "→ Preparando carpetas"
rssh "mkdir -p '$DEST' '$DATA_DIR' && chmod 700 '$DATA_DIR'"

echo "→ Subiendo archivos"
rscp "$SRC/index.html" "$SRC/guardar.php" "$SRC/.htaccess" "$SSH_USER@$SSH_HOST:$DEST/"

echo "→ Token y config.php (se conserva el token existente si ya hay config.php)"
TOKEN="$(rssh "if [ -f '$DEST/config.php' ]; then php -r 'echo (require \"$DEST/config.php\")[\"token\"];'; fi")"
if [ -z "$TOKEN" ]; then
  TOKEN="$(LC_ALL=C tr -dc 'a-z0-9' </dev/urandom | head -c 32)"
  rssh "cat > '$DEST/config.php' <<PHP
<?php
return [
	'token'    => '$TOKEN',
	'notify'   => '$NOTIFY_EMAIL',
	'data_dir' => '$DATA_DIR',
];
PHP
chmod 600 '$DEST/config.php'"
fi

echo "→ Verificando"
code_index="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/")"
body_get="$(curl -s "$SITE_URL/briefing/guardar.php?token=$TOKEN")"
code_403="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/guardar.php")"
code_cfg="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/config.php")"
echo "index: $code_index · GET con token: $body_get · sin token: $code_403 · config.php: $code_cfg"
[ "$code_index" = "200" ] && [ "$code_403" = "403" ] && [ "$code_cfg" != "200" ] || { echo "VERIFICACIÓN FALLIDA"; exit 1; }

echo
echo "URL para la Dra.: $SITE_URL/briefing/?t=$TOKEN"
