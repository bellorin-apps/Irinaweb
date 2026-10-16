#!/usr/bin/env bash
# Versión en inglés en BORRADOR (D-095, PLAN 13.3): crea o actualiza las páginas de tools/content/en/ bajo /en/, sin publicar nada.
# Requiere el código desplegado (deploy-code.sh) con el módulo DraIrina\Core\I18n\Language. No toca páginas inglesas publicadas.
# Uso: python3 tools/content/build-en.py && bash tools/deploy/setup-en.sh
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
TMP="/tmp/di-en-$(stamp)"
rssh "mkdir -p '$TMP' && chmod 700 '$TMP'"
trap 'rssh "rm -rf '"'$TMP'"'"' EXIT
rscp "$REPO"/tools/content/en/*.json "$REPO"/tools/content/en/*.html "$REPO/tools/deploy/en/setup-en.php" "$SSH_USER@$SSH_HOST:$TMP/"
rssh "$WP eval-file '$TMP/setup-en.php'"
rssh "cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp cache flush" >/dev/null 2>&1 || true
curl -s -o /dev/null "$SITE_URL/?cb=$(date +%s)"   # regenera las reglas de reescritura con todos los plugins
echo "Listo: páginas inglesas en borrador. Purga LiteSpeed/CDN; /en/ no debe ser visible para visitantes."
