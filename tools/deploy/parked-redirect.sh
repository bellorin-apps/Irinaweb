#!/usr/bin/env bash
# Q-012: redirección 301 del dominio aparcado otorrino-monterrey.com (apex y www, http y https, CUALQUIER ruta) a la misma
# ruta en https://drairinagonzalez.com, en un solo salto. Inserta un bloque marcado en el .htaccess del webroot antes del
# bloque de WordPress (la redirección de hPanel solo cubre la raíz). Idempotente; respaldo previo del .htaccess.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
HT="$WEBROOT/.htaccess"
TS="$(stamp)"
if rssh "grep -q 'BEGIN DraIrina parked' '$HT'"; then
  echo "El bloque ya existe en $HT; nada que hacer."
else
  rssh "cp -a '$HT' \"\$HOME/deploy-backups/htaccess-$TS\" 2>/dev/null || true; mkdir -p \"\$HOME/deploy-backups\"; cp -a '$HT' \"\$HOME/deploy-backups/htaccess-$TS\""
  BLOCK='# BEGIN DraIrina parked (Q-012): otorrino-monterrey.com -> drairinagonzalez.com, misma ruta, 301 en un salto
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP_HOST} ^(www\.)?otorrino-monterrey\.com$ [NC]
RewriteRule ^(.*)$ https://drairinagonzalez.com/$1 [R=301,L]
</IfModule>
# END DraIrina parked
'
  # Antes del bloque de WordPress si existe; si no, al principio.
  printf '%s' "$BLOCK" | rssh "cat > /tmp/parked-block.txt && if grep -q '# BEGIN WordPress' '$HT'; then awk 'FNR==NR{b=b \$0 ORS; next} /# BEGIN WordPress/ && !done {printf \"%s\", b; done=1} {print}' /tmp/parked-block.txt '$HT' > /tmp/htaccess.new; else cat /tmp/parked-block.txt '$HT' > /tmp/htaccess.new; fi && cp /tmp/htaccess.new '$HT' && rm -f /tmp/parked-block.txt /tmp/htaccess.new"
  echo "Bloque insertado en $HT (respaldo en ~/deploy-backups/htaccess-$TS)."
fi
echo "→ Verificando (desde esta máquina)"
for u in "https://otorrino-monterrey.com/alguna-url-vieja/" "https://www.otorrino-monterrey.com/op/" "http://otorrino-monterrey.com/" "https://drairinagonzalez.com/"; do
  printf '%s → ' "$u"; curl -s -o /dev/null --max-time 20 -w '%{http_code} %{redirect_url}\n' "$u"
done
echo "Esperado: los tres primeros 301 a https://drairinagonzalez.com/<misma ruta>; el último 200."
