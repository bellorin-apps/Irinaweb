#!/usr/bin/env bash
# Copia tools/deploy del repositorio y sustituye lib.sh por la versión LOCAL (lib.local.sh): nunca contra el servidor.
set -e
H="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"; R="$(cd "$H/../.." && pwd)"
rm -rf "$H/deploy"; mkdir -p "$H/deploy"; cp -r "$R/tools/deploy/." "$H/deploy/"
rm -f "$H/deploy/env.sh" "$H/deploy/"*.local.txt
cp "$H/lib.local.sh" "$H/deploy/lib.sh"
echo "deploy local listo (repo $(cd "$R" && git rev-parse --short HEAD))"
# Variante SOLO DE PRUEBA (Q-076): publish-en con Elementor cargado, para poder probar el resto de protecciones.
if [ "${1:-}" = "con-elementor" ]; then
	sed -i 's#/usr/local/bin/wp --skip-plugins=elementor,elementor-pro"#/usr/local/bin/wp"#' "$H/deploy/publish-en.sh"
	echo "  publish-en.sh local SIN --skip-plugins (variante de prueba)"
fi
