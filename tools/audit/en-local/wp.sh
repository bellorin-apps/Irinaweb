#!/usr/bin/env bash
# WP-CLI de la copia local aislada; filtra los avisos de depreciación de Elementor (filtro.awk).
H="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
set -o pipefail
"$H/../php/php.exe" -d display_errors=stderr -c "$H/php.ini" "$H/dl/wp-cli.phar" --path="$H/site" "$@" | awk -f "$H/filtro.awk"
