#!/usr/bin/env bash
# Copia el tema y el plugin del repositorio a la copia local (sin enlaces simbólicos).
set -e
H="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"; R="$(cd "$H/../.." && pwd)"
rm -rf "$H/site/wp-content/themes/irina-gonzalez" "$H/site/wp-content/plugins/dra-irina-core"
cp -r "$R/wp-content/themes/irina-gonzalez" "$H/site/wp-content/themes/"
cp -r "$R/wp-content/plugins/dra-irina-core" "$H/site/wp-content/plugins/"
echo "sync: $(cd "$R" && git rev-parse --short HEAD)"
