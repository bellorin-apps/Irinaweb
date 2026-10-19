#!/usr/bin/env bash
# Pruebas desechables de mkdeploy.sh (Q-079) con privados FICTICIOS: nunca se copian; destinos inválidos abortan sin borrar.
set -u
SELF="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd -P)"
REAL="$(git -C "$SELF" rev-parse --show-toplevel)"
W="$REAL/tmp/q079-selftest"; rm -rf -- "$W"; mkdir -p "$W"
fake_repo() { # $1 = dir
	mkdir -p "$1/tools/deploy/en" "$1/tmp/wp-en-local"
	git -C "$1" init -q && git -C "$1" -c user.name=t -c user.email=t@example.test commit -q --allow-empty -m init
	for f in lib.sh setup-site.sh setup-pages.sh setup-dra.sh setup-en.sh publish-en.sh upload-media.sh deploy-code.sh; do echo "# $f" > "$1/tools/deploy/$f"; done
	echo "<?php // x" > "$1/tools/deploy/en/setup-en.php"; echo "<?php // y" > "$1/tools/deploy/en/publish-en.php"
	echo 'SSH_KEY=FICTICIA-NO-COPIAR' > "$1/tools/deploy/env.sh"
	echo 'https://example.test/?t=FICTICIA-NO-COPIAR' > "$1/tools/deploy/briefing-urls.local.txt"
	echo '# lib local ficticio' > "$1/tmp/wp-en-local/lib.local.sh"
}
ok=0; bad=0
check() { if [ "$2" = "$3" ]; then ok=$((ok+1)); printf '  OK    %s\n' "$1"; else bad=$((bad+1)); printf '  FALLO %s (esperado %s, obtenido %s)\n' "$1" "$2" "$3"; fi; }
M="$SELF/mkdeploy.sh"
fake_repo "$W/r1"
DI_REPO_ROOT="$W/r1" bash "$M" >/dev/null 2>&1; check "preparación normal: salida" 0 $?
check "env.sh no copiado" 0 "$(grep -rl FICTICIA "$W/r1/tmp/wp-en-local/deploy" 2>/dev/null | wc -l | tr -d ' ')"
check "solo lista permitida (sin deploy-code.sh)" 0 "$(ls "$W/r1/tmp/wp-en-local/deploy" | grep -c deploy-code)"
check "lib.sh = lib.local.sh" "# lib local ficticio" "$(cat "$W/r1/tmp/wp-en-local/deploy/lib.sh")"
DI_REPO_ROOT="$W/r1" bash "$M" >/dev/null 2>&1; check "repetible" 0 $?
mkdir -p "$W/fuera"; echo '# lib' > "$W/fuera/lib.local.sh"
DI_REPO_ROOT="$W/r1" DI_LAB="$W/fuera" bash "$M" >/dev/null 2>&1; check "laboratorio fuera de <repo>/tmp → ALTO" 3 $?
mkdir -p "$W/norepo/tools/deploy"; echo x > "$W/norepo/tools/deploy/lib.sh"
DI_REPO_ROOT="$W/norepo" DI_LAB="$W/norepo" bash "$M" >/dev/null 2>&1; check "raíz sin .git → ALTO" 3 $?
# Enlaces: en Windows solo si el sistema permite crearlos; si no, se informa como no probado.
fake_repo "$W/r2"; mkdir -p "$W/victima"; echo vivo > "$W/victima/archivo"
rm -rf "$W/r2/tmp/wp-en-local/deploy"
if MSYS=winsymlinks:nativestrict ln -s "$W/victima" "$W/r2/tmp/wp-en-local/deploy" 2>/dev/null && [ -L "$W/r2/tmp/wp-en-local/deploy" ]; then
	DI_REPO_ROOT="$W/r2" bash "$M" >/dev/null 2>&1; check "deploy es enlace → ALTO" 3 $?
	check "destino del enlace intacto" vivo "$(cat "$W/victima/archivo")"
else
	echo "  —     enlaces simbólicos no disponibles en este Windows: caso no probado"
fi
rm -rf -- "$W"
echo "q079_selftest: $ok OK, $bad fallos"
[ "$bad" = 0 ]
