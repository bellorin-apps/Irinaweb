#!/usr/bin/env bash
# Copia el tema y el plugin del repositorio a la copia local (sin enlaces simbólicos). Q-079: raíz por git y destino validado.
# Q-079 (acc4cdd): se valida CADA componente entre la copia local y el destino (site, wp-content, themes|plugins y el
# destino final): ninguno puede ser enlace ni junction y todos deben resolver físicamente a sí mismos dentro de la copia.
# Dentro del destino tampoco se admiten enlaces antes de borrarlo. Variables solo para pruebas: DI_REPO_ROOT y DI_LAB.
set -euo pipefail
SELF="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd -P)"
R="${DI_REPO_ROOT:-$(git -C "$SELF" rev-parse --show-toplevel)}"; R="$(cd "$R" && pwd -P)"
[ -d "$R/.git" ] && [ -d "$R/wp-content" ] || { echo "ALTO: $R no es la raíz del repositorio Irinaweb" >&2; exit 3; }
LAB="${DI_LAB:-$R/tmp/wp-en-local}"
[ -d "$LAB" ] && [ ! -L "$LAB" ] || { echo "ALTO: la copia local $LAB no existe o es un enlace" >&2; exit 3; }
LAB="$(cd "$LAB" && pwd -P)"
case "$LAB/" in "$R/tmp/"*) ;; *) echo "ALTO: la copia local debe estar en $R/tmp/" >&2; exit 3 ;; esac
alto() { echo "ALTO: $1" >&2; exit 3; }
# Recorre LAB/<rel> componente a componente; el último puede no existir todavía.
real_path() {
	local p="$LAB" part
	local -a parts
	IFS=/ read -ra parts <<< "$1"
	for part in "${parts[@]}"; do
		p="$p/$part"
		[ ! -L "$p" ] || alto "$p es un enlace o junction; no se toca"
		[ -e "$p" ] || continue
		[ -d "$p" ] || alto "$p no es un directorio"
		[ "$(cd "$p" && pwd -P)" = "$p" ] || alto "$p no resuelve a sí mismo dentro de la copia local"
	done
}
for rel in themes/irina-gonzalez plugins/dra-irina-core; do
	real_path "site/wp-content/$rel"
	dst="$LAB/site/wp-content/$rel"
	[ -d "$(dirname "$dst")" ] || alto "falta $(dirname "$dst")"
	if [ -e "$dst" ]; then
		if find "$dst" -type l | grep -q .; then alto "$dst contiene enlaces; no se borra"; fi
	fi
done
# Validar ambos destinos antes de cambiar el primero: un plugin inválido no debe dejar el tema actualizado a medias.
for rel in themes/irina-gonzalez plugins/dra-irina-core; do
	dst="$LAB/site/wp-content/$rel"
	if [ -e "$dst" ]; then rm -rf -- "$dst"; fi
	cp -r -- "$R/wp-content/$rel" "$dst"
done
echo "sync: $(git -C "$R" rev-parse --short HEAD)"
