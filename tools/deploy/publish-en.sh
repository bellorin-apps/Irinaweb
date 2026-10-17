#!/usr/bin/env bash
# Publica la versión inglesa (D-095, PLAN 13.7), todo o nada. Sin --yes solo revisa (dry-run).
# Requisitos que comprueba: páginas aprobadas por la Dra. en su guía, fichas aprobadas en su app y --legal-ok (José acepta los legales).
# Uso: bash tools/deploy/publish-en.sh            (revisar)
#      bash tools/deploy/publish-en.sh --legal-ok --yes   (publicar, con el OK de José)
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
TMP="/tmp/di-en-pub-$(stamp)"
rssh "mkdir -p '$TMP' && chmod 700 '$TMP'"
trap 'rssh "rm -rf '"'$TMP'"'"' EXIT
rscp "$REPO/tools/content/en/pages.json" "$REPO/tools/content/en/fichas-en.json" "$REPO/tools/deploy/en/publish-en.php" "$SSH_USER@$SSH_HOST:$TMP/"
ARGS=""
for a in "$@"; do
  case "$a" in --yes) ARGS+=" yes" ;; --legal-ok) ARGS+=" legal-ok" ;; *) echo "Opción desconocida: $a" >&2; exit 2 ;; esac
done
rssh "$WP eval-file '$TMP/publish-en.php'$ARGS"
if [[ " $* " == *" --yes "* ]]; then
  rssh "$WP cache flush" >/dev/null 2>&1 || true; rssh "$WP litespeed-purge all" >/dev/null 2>&1 || true
  echo "→ Verificación pública"
  for u in /en/ /en/ent/ /en/sleep/ /en/dr-irina-gonzalez-saez/ /en/first-visit/ /en/contact/ /en/faq/ /en/privacy-notice/ /en/medical-disclaimer/ /en/terms-of-use/ /en/sleep/snoring/ /en/sleep/obstructive-sleep-apnea/ /en/sleep/home-sleep-test/ /en/sleep/cpap-therapy/ /en/sleep/snoring-and-sleep-apnea-surgery/; do
    printf '  %-42s %s\n' "$u" "$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL$u?cb=$(date +%s)")"
  done
  echo "  hreflang en portada: $(curl -s "$SITE_URL/?cb=$(date +%s)" | grep -c 'hreflang=')  · selector: $(curl -s "$SITE_URL/?cb=$(date +%s)" | grep -c 'class="di-lang')"
fi
