#!/usr/bin/env bash
# Prepara <lab>/deploy con una LISTA PERMITIDA de scripts de tools/deploy y el lib.sh LOCAL (lib.local.sh), para ejecutar
# setup-*/publish-en contra la copia aislada, nunca contra el servidor (Q-079, 2026-10-19):
# - nunca copia env.sh, *.local.txt ni nada fuera de la lista (los privados no llegan a copiarse);
# - raíz del repositorio por git (no por ../..) y comprobada;
# - destino validado antes de borrar: dentro de <repo>/tmp/wp-en-local, sin enlaces simbólicos.
# Variables solo para pruebas: DI_REPO_ROOT (repositorio) y DI_LAB (copia local).
set -euo pipefail
SELF="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd -P)"
R="${DI_REPO_ROOT:-$(git -C "$SELF" rev-parse --show-toplevel)}"
R="$(cd "$R" && pwd -P)"
[ -f "$R/tools/deploy/lib.sh" ] && [ -d "$R/.git" ] || { echo "ALTO: $R no es la raíz del repositorio Irinaweb" >&2; exit 3; }
LAB="${DI_LAB:-$R/tmp/wp-en-local}"
[ -d "$LAB" ] && [ ! -L "$LAB" ] || { echo "ALTO: la copia local $LAB no existe o es un enlace" >&2; exit 3; }
LAB="$(cd "$LAB" && pwd -P)"
case "$LAB/" in "$R/tmp/"*) ;; *) echo "ALTO: la copia local debe estar en $R/tmp/" >&2; exit 3 ;; esac
[ -f "$LAB/lib.local.sh" ] || { echo "ALTO: falta $LAB/lib.local.sh" >&2; exit 3; }
D="$LAB/deploy"
if [ -e "$D" ] || [ -L "$D" ]; then
	[ ! -L "$D" ] || { echo "ALTO: $D es un enlace; no se borra" >&2; exit 3; }
	[ "$(cd "$D" && pwd -P)" = "$D" ] || { echo "ALTO: $D no resuelve a sí mismo" >&2; exit 3; }
	if find "$D" -type l | grep -q .; then echo "ALTO: $D contiene enlaces; no se borra" >&2; exit 3; fi
	rm -rf -- "$D"
fi
mkdir -p "$D/en"
# Lista permitida: lo que las pruebas ejecutan. Nada de env.sh, *.local.txt, briefing ni scripts de producción no usados.
ALLOW=(setup-site.sh setup-pages.sh setup-dra.sh setup-en.sh publish-en.sh upload-media.sh)
for f in "${ALLOW[@]}"; do cp -- "$R/tools/deploy/$f" "$D/$f"; done
for f in "$R"/tools/deploy/en/*.php; do cp -- "$f" "$D/en/"; done
cp -- "$LAB/lib.local.sh" "$D/lib.sh"
# Comprobación final: ningún privado ni archivo fuera de la lista.
if find "$D" \( -name 'env.sh' -o -name '*.local.txt' -o -name 'config.php' \) | grep -q .; then echo "ALTO: privado en $D" >&2; exit 4; fi
echo "deploy local listo (repo $(git -C "$R" rev-parse --short HEAD), $(find "$D" -type f | wc -l) archivos permitidos)"
