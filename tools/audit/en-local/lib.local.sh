# lib.sh LOCAL (pruebas 2026-10-19): sin env.sh ni SSH. rssh/rscp ejecutan contra la copia aislada de esta PC.
set -euo pipefail
L="/c/Users/bellg/Documents/Irinaweb/tmp/wp-en-local"
HERE="$L/deploy"
REPO="${DI_REPO:-/c/Users/bellg/Documents/Irinaweb}"  # DI_REPO solo para probar contenido modificado
ssh() { echo "BLOQUEADO: ssh en la copia local" >&2; return 97; }
scp() { echo "BLOQUEADO: scp en la copia local" >&2; return 97; }
WEBROOT="$(cygpath -m "$L/site")"
SITE_URL="${DI_SITE_URL:-http://127.0.0.1:8890}"  # DI_SITE_URL solo para simular red caída
SSH_USER=local
SSH_HOST=local
_LWP="$(cygpath -m "$L/../php/php.exe") -d display_errors=stderr -c $(cygpath -m "$L/php.ini") $(cygpath -m "$L/dl/wp-cli.phar")"
_TMPW="$(cygpath -m "$L/rtmp")"  # /tmp «remoto» propio: distinto del /tmp local del script
# Solo /tmp/ al inicio de una ruta (tras espacio, comilla, = o :), no dentro de .../Irinaweb/tmp/...
_fix_tmp() { printf '%s' "$1" | sed -E "s#(^|[ '\"=:])/tmp/#\\1${_TMPW}/#g"; }
_filter() { awk -f "$L/filtro.awk"; }
rssh() {
	local c
	c="$(_fix_tmp "$*")"
	c="${c//\/opt\/alt\/php84\/usr\/bin\/php \/usr\/local\/bin\/wp/$_LWP}"
	bash -c "$c" 2>&1 | _filter
	return "${PIPESTATUS[0]}"
}
rscp() {
	local a=("$@") d
	d="${a[${#a[@]}-1]}"
	unset 'a[${#a[@]}-1]'
	d="${d#*:}"
	d="$(_fix_tmp "$d")"
	local s
	for s in "${a[@]}"; do
		# En local, /tmp de origen y destino pueden ser el mismo archivo: no hay nada que copiar.
		if [ -e "$d" ] && [ ! -d "$d" ] && [ "$(cygpath -m "$s")" = "$(cygpath -m "$d")" ]; then continue; fi
		cp "$s" "$d"
	done
}
stamp() { date +%Y%m%d-%H%M%S; }
