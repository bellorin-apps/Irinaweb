#!/usr/bin/env bash
# Publica la versión inglesa (D-095, PLAN 13.7), preflight completo y restauración de estados al fallar. Sin --yes solo revisa.
# Las escrituras WP son secuenciales, no una transacción atómica ni una garantía sobre hooks/caché concurrente.
# Requisitos que comprueba: páginas aprobadas por la Dra. en su guía, fichas aprobadas en su app y --legal-ok (José acepta los legales).
# Uso: bash tools/deploy/publish-en.sh            (revisar)
#      bash tools/deploy/publish-en.sh --legal-ok --yes   (publicar, con el OK de José)
ARGS=""
for a in "$@"; do
  case "$a" in --yes) ARGS+=" yes" ;; --legal-ok) ARGS+=" legal-ok" ;; *) echo "Opción desconocida: $a" >&2; exit 2 ;; esac
done
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
TMP="/tmp/di-en-pub-$(stamp)"
rssh "mkdir -p '$TMP' && chmod 700 '$TMP'"
trap 'rssh "rm -rf '"'$TMP'"'"' EXIT
rscp "$REPO/tools/content/en/pages.json" "$REPO/tools/content/en/fichas-en.json" "$REPO/tools/deploy/en/publish-en.php" "$SSH_USER@$SSH_HOST:$TMP/"
rssh "$WP eval-file '$TMP/publish-en.php'$ARGS"
if [[ " $* " == *" --yes "* ]]; then
  rssh "$WP cache flush" >/dev/null 2>&1 || true; rssh "$WP litespeed-purge all" >/dev/null 2>&1 || true
  echo "→ Verificación pública"
  BAD=0
  for u in /en/ /en/ent/ /en/sleep/ /en/dr-irina-gonzalez-saez/ /en/first-visit/ /en/contact/ /en/faq/ /en/privacy-notice/ /en/medical-disclaimer/ /en/terms-of-use/ /en/sleep/snoring/ /en/sleep/obstructive-sleep-apnea/ /en/sleep/home-sleep-test/ /en/sleep/cpap-therapy/ /en/sleep/snoring-and-sleep-apnea-surgery/; do
    CODE="$(curl -sS -o /dev/null -w '%{http_code}' "$SITE_URL$u?cb=$(date +%s)")" || CODE=000
    printf '  %-42s %s\n' "$u" "$CODE"
    [ "$CODE" = 200 ] || BAD=1
  done
  HTML="$(curl -fsS "$SITE_URL/?cb=$(date +%s)")" || BAD=1
  for TAG in 'hreflang="es-MX"' 'hreflang="en-US"' 'hreflang="x-default"' 'class="di-lang'; do
    grep -q "$TAG" <<< "$HTML" || BAD=1
  done
  [ "$BAD" = 0 ] || { echo 'ERROR: verificación pública incompleta; revisar el estado real y la caché antes de dar el lanzamiento por válido.' >&2; exit 1; }
fi
