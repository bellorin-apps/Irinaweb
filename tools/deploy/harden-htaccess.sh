#!/usr/bin/env bash
# Q-054: el sitio no anuncia versiones. Bloque marcado en el .htaccess del webroot, antes del de WordPress: readme.html,
# license.txt y wp-config-sample.php (archivos del núcleo, no se borran) responden 403, y se quita la cabecera X-Powered-By
# también en las páginas que sirve la caché de LiteSpeed (el plugin la quita en las que genera PHP). Idempotente; respaldo previo.
# Uso: harden-htaccess.sh            inserta (si falta) y verifica
#      harden-htaccess.sh --remove   quita el bloque (con respaldo) y verifica
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
HT="$WEBROOT/.htaccess"
TS="$(stamp)"
backup(){ rssh "mkdir -p \"\$HOME/deploy-backups\" && cp -a '$HT' \"\$HOME/deploy-backups/htaccess-$TS\""; echo "Respaldo: ~/deploy-backups/htaccess-$TS"; }
if [ "${1:-}" = "--remove" ]; then
  backup
  rssh "awk '/# BEGIN DraIrina hardening/{skip=1} !skip{print} /# END DraIrina hardening/{skip=0}' '$HT' > /tmp/htaccess.new && cp /tmp/htaccess.new '$HT' && rm -f /tmp/htaccess.new"
  echo "Bloque retirado."
elif rssh "grep -q 'BEGIN DraIrina hardening' '$HT'"; then
  echo "El bloque ya existe en $HT; nada que hacer."
else
  backup
  BLOCK='# BEGIN DraIrina hardening (Q-054): sin archivos que revelan la versión ni cabecera X-Powered-By
<FilesMatch "^(readme\.html|license\.txt|wp-config-sample\.php)$">
Require all denied
</FilesMatch>
<IfModule mod_headers.c>
Header always unset X-Powered-By
Header unset X-Powered-By
</IfModule>
# END DraIrina hardening
'
  printf '%s' "$BLOCK" | rssh "cat > /tmp/hardening-block.txt && if grep -q '# BEGIN WordPress' '$HT'; then awk 'FNR==NR{b=b \$0 ORS; next} /# BEGIN WordPress/ && !done {printf \"%s\", b; done=1} {print}' /tmp/hardening-block.txt '$HT' > /tmp/htaccess.new; else cat /tmp/hardening-block.txt '$HT' > /tmp/htaccess.new; fi && cp /tmp/htaccess.new '$HT' && rm -f /tmp/hardening-block.txt /tmp/htaccess.new"
  echo "Bloque insertado en $HT."
fi
echo "→ Verificación (desde esta máquina; purga antes la caché si acabas de desplegar)"
for u in readme.html license.txt wp-config-sample.php "" contacto/ sueno/ wp-login.php; do
  printf '  %-24s %s\n' "/$u" "$(curl -s -o /dev/null --max-time 20 -w '%{http_code}' "$SITE_URL/$u?cb=$(date +%s)")"
done
printf '  X-Powered-By en portada: %s\n' "$(curl -sI --max-time 20 "$SITE_URL/" | grep -ci '^x-powered-by' || true)"
printf '  meta generator en portada: %s\n' "$(curl -s --max-time 20 "$SITE_URL/?cb=$(date +%s)" | grep -c '<meta name="generator"' || true)"
echo "Esperado: readme/license/sample 403; portada, contacto, sueno y wp-login 200; X-Powered-By 0; meta generator 0."
