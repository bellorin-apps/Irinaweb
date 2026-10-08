#!/usr/bin/env bash
# Ensambla las 4 páginas desde los parciales y versiona los assets con el hash corto de Git (rompe la caché del navegador en cada despliegue).
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")"
V="$(git rev-parse --short HEAD 2>/dev/null || date +%s)"
build(){ sed -e "s/__TITLE__/$2/" -e "s/__V__/$V/g" _head.html > "$1"; cat "$3" >> "$1"; cat _foot.html >> "$1"; }
build index.html "Maqueta v2 · Inicio" _home_body.html
build dra-irina.html "Maqueta v2 · Dra. Irina" _dra_body.html
build apnea.html "Maqueta v2 · Apnea del sueño" _apnea_body.html
build articulo.html "Maqueta v2 · Artículo" _art_body.html
echo "build $V"
