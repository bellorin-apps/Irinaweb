#!/usr/bin/env bash
# Copia el tema y el plugin del repositorio a la copia local (sin enlaces simbólicos). Q-079: raíz por git y destino validado.
set -euo pipefail
SELF="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd -P)"
R="${DI_REPO_ROOT:-$(git -C "$SELF" rev-parse --show-toplevel)}"; R="$(cd "$R" && pwd -P)"
LAB="${DI_LAB:-$R/tmp/wp-en-local}"; LAB="$(cd "$LAB" && pwd -P)"
case "$LAB/" in "$R/tmp/"*) ;; *) echo "ALTO: la copia local debe estar en $R/tmp/" >&2; exit 3 ;; esac
for rel in themes/irina-gonzalez plugins/dra-irina-core; do
	dst="$LAB/site/wp-content/$rel"
	if [ -e "$dst" ] || [ -L "$dst" ]; then
		[ ! -L "$dst" ] || { echo "ALTO: $dst es un enlace; no se borra" >&2; exit 3; }
		rm -rf -- "$dst"
	fi
	cp -r -- "$R/wp-content/$rel" "$dst"
done
echo "sync: $(git -C "$R" rev-parse --short HEAD)"
