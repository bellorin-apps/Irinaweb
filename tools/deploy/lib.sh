# Funciones comunes. Requiere env.sh.
set -euo pipefail
HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO="$(cd "$HERE/../.." && pwd)"
if [ ! -f "$HERE/env.sh" ]; then echo "Falta $HERE/env.sh (copia env.example.sh)"; exit 2; fi
# shellcheck source=/dev/null
. "$HERE/env.sh"
SSH_OPTS=(-i "$SSH_KEY" -p "$SSH_PORT" -o StrictHostKeyChecking=accept-new -o ConnectTimeout=20)
rssh() { ssh "${SSH_OPTS[@]}" "$SSH_USER@$SSH_HOST" "$@"; }
rscp() { scp -i "$SSH_KEY" -P "$SSH_PORT" -o StrictHostKeyChecking=accept-new "$@"; }
stamp() { date +%Y%m%d-%H%M%S; }
