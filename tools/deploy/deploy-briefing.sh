#!/usr/bin/env bash
# Despliega el briefing de la Dra. en $WEBROOT/briefing/ y crea config.php con token. Idempotente.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"

SRC="$REPO/tools/briefing"
DEST="$WEBROOT/briefing"
DATA_DIR="$DOMAIN_ROOT/briefing-privado"
TS="$(stamp)"
echo "→ Respaldo privado antes de sobrescribir la mini app"
rssh "mkdir -p \"\$HOME/deploy-backups\"; if [ -d '$DEST' ]; then umask 077; tar -C '$WEBROOT' -czf \"\$HOME/deploy-backups/briefing-$TS.tar.gz\" briefing; fi"

echo "→ Preparando carpetas"
rssh "mkdir -p '$DEST' '$DATA_DIR' && chmod 700 '$DATA_DIR'"

echo "→ Subiendo archivos"
rscp "$SRC/index.html" "$SRC/guardar.php" "$SRC/.htaccess" "$SSH_USER@$SSH_HOST:$DEST/"

echo "→ Subiendo cuestionario de fichas (fichas/)"
rssh "mkdir -p '$DEST/fichas'"
rscp "$SRC/fichas/index.html" "$SRC/fichas/guardar.php" "$SRC/fichas/.htaccess" "$SSH_USER@$SSH_HOST:$DEST/fichas/"

echo "→ Subiendo mini app de revisión médica (revision/)"
rssh "mkdir -p '$DEST/revision'"
rscp "$SRC/revision/index.html" "$SRC/revision/revisar.php" "$SRC/revision/.htaccess" "$SSH_USER@$SSH_HOST:$DEST/revision/"

echo "→ Token y config.php (se conserva el token existente si ya hay config.php; sin correo por decisión del propietario 2026-10-09)"
TOKEN="$(rssh "if [ -f '$DEST/config.php' ]; then php -r 'echo (require \"$DEST/config.php\")[\"token\"];'; fi")"
if [ -z "$TOKEN" ]; then
  # 32 hex. No usar `tr </dev/urandom | head -c`: con pipefail en Git Bash muere por SIGPIPE (exit 141).
  TOKEN="$(od -An -tx1 -N16 /dev/urandom | tr -d ' \n')"
  rssh "cat > '$DEST/config.php' <<PHP
<?php
return [
	'token'    => '$TOKEN',
	'notify'   => '',
	'data_dir' => '$DATA_DIR',
];
PHP
chmod 600 '$DEST/config.php'"
fi

echo "→ Verificando"
code_index="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/")"
code_get="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/guardar.php?token=$TOKEN")"
code_403="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/guardar.php")"
code_cfg="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/config.php")"
code_rev="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/briefing/revision/revisar.php")"
# cut (no head -c): head cierra la tubería y curl termina con exit 23 bajo pipefail.
body_rev="$(curl -s "$SITE_URL/briefing/revision/revisar.php?token=$TOKEN&action=list")"
echo "index: $code_index · GET con token: $code_get · sin token: $code_403 · config.php: $code_cfg · revisar.php sin token: $code_rev"
[ "$code_index" = "200" ] && [ "$code_get" = "200" ] && [ "$code_403" = "403" ] && [ "$code_cfg" != "200" ] && [ "$code_rev" = "403" ] || { echo "VERIFICACIÓN FALLIDA"; exit 1; }
case "$body_rev" in *'"reviewer"'*) ;; *) echo "revisar.php no devolvió la lista: revisa que exista un usuario con rol revisor_medico (wp user list --role=revisor_medico) y que config.php tenga data_dir"; exit 1;; esac

echo
umask 077
printf '%s\n' "$SITE_URL/briefing/?t=$TOKEN" "$SITE_URL/briefing/fichas/?t=$TOKEN" "$SITE_URL/briefing/revision/?t=$TOKEN" > "$HERE/briefing-urls.local.txt"
echo "URLs privadas guardadas en tools/deploy/briefing-urls.local.txt (ignorado por Git); no pegarlas en chats ni documentos."
