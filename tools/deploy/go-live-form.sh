#!/usr/bin/env bash
# Pone en marcha el formulario de contacto (PLAN 8.4): publica /gracias/ (conserva noindex) y recarga Contacto con el widget di-form.
# Requiere OK de José. Uso: go-live-form.sh [--rollback]
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WPA="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp"
wa(){ rssh "$WPA $*"; }
GID="$(wa "post list --post_type=page --post_name__in=gracias --post_status=any --field=ID" | head -1)"
[ -n "$GID" ] || { echo "falta la página gracias (ejecuta setup-site.sh y setup-pages.sh)"; exit 1; }
if [ "${1:-}" = "--rollback" ]; then
  wa "post update $GID --post_status=draft" >/dev/null; echo "gracias ($GID) → borrador"; exit 0
fi
wa "post update $GID --post_status=publish" >/dev/null
wa "post meta update $GID rank_math_robots --format=json '[\"noindex\",\"nofollow\"]'" >/dev/null
echo "gracias ($GID): $(wa "post get $GID --field=post_status") · robots=$(wa "post meta get $GID rank_math_robots --format=json")"
wa "cache flush" >/dev/null; wa "litespeed-purge all" >/dev/null 2>&1 || true
echo "Comprueba: $SITE_URL/gracias/ (noindex) y el formulario en $SITE_URL/contacto/#di-form. SMTP: constantes DI_SMTP_* en wp-config.php (ver docs/HANDOFF_LOCAL o NEXT)."
