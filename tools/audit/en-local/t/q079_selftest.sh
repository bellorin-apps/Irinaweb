#!/usr/bin/env bash
# Pruebas desechables de mkdeploy.sh y sync.sh (Q-079) con privados y marcadores FICTICIOS: los privados nunca se copian;
# destinos inválidos (enlaces o junctions en cualquier componente) abortan con 3 sin borrar ni escribir fuera de la copia.
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
	rm -f -- "$W/r2/tmp/wp-en-local/deploy"
else
	echo "  —     enlaces simbólicos no disponibles en este Windows: caso no probado"
fi
# ── sync.sh (acc4cdd: enlaces en componentes intermedios) ──
S="$SELF/sync.sh"
win() { cygpath -w "$1"; }
mklink_dir() { # $1 enlace, $2 destino, $3 symlink|junction; 0 si se creó
	if [ "$3" = junction ]; then cmd //c mklink //J "$(win "$1")" "$(win "$2")" >/dev/null 2>&1; else MSYS=winsymlinks:nativestrict ln -s "$2" "$1" 2>/dev/null; fi
	[ -e "$1" ] || [ -L "$1" ]
}
unlink_dir() { if [ "$2" = junction ]; then cmd //c rmdir "$(win "$1")" >/dev/null 2>&1; else rm -f -- "$1"; fi; }
sync_repo() { # $1 = dir: repositorio ficticio con tema/plugin y copia local con site/wp-content/{themes,plugins}
	fake_repo "$1"
	for rel in themes/irina-gonzalez plugins/dra-irina-core; do mkdir -p "$1/wp-content/$rel" "$1/tmp/wp-en-local/site/wp-content/$rel"; echo nuevo > "$1/wp-content/$rel/nuevo.txt"; done
}
victima() { # $1 = dir con marcadores fuera de la copia
	for rel in themes/irina-gonzalez plugins/dra-irina-core; do mkdir -p "$1/wp-content/$rel"; echo vivo > "$1/wp-content/$rel/marcador.txt"; done
}
intactos() { [ "$(cat "$1/wp-content/themes/irina-gonzalez/marcador.txt" "$1/wp-content/plugins/dra-irina-core/marcador.txt" 2>/dev/null)" = "vivo
vivo" ] && [ ! -e "$1/wp-content/themes/irina-gonzalez/nuevo.txt" ] && echo si || echo no; }
sync_repo "$W/s0"
DI_REPO_ROOT="$W/s0" bash "$S" >/dev/null 2>&1; check "sync normal: salida" 0 $?
check "sync normal: copia el tema" nuevo "$(cat "$W/s0/tmp/wp-en-local/site/wp-content/themes/irina-gonzalez/nuevo.txt" 2>/dev/null)"
n=0
for tipo in symlink junction; do
	for comp in site site/wp-content site/wp-content/plugins site/wp-content/themes/irina-gonzalez; do
		n=$((n+1)); r="$W/s$n"; v="$W/v$n"; sync_repo "$r"; L="$r/tmp/wp-en-local"
		case "$comp" in
			site) victima "$v/site-real"; tgt="$v/site-real"; vroot="$v/site-real" ;;
			site/wp-content) victima "$v"; tgt="$v/wp-content"; vroot="$v" ;;
			site/wp-content/plugins) victima "$v"; tgt="$v/wp-content/plugins"; vroot="$v" ;;
			site/wp-content/themes/irina-gonzalez) victima "$v"; tgt="$v/wp-content/themes/irina-gonzalez"; vroot="$v" ;;
		esac
		rm -rf -- "${L:?}/$comp"
		if mklink_dir "$L/$comp" "$tgt" "$tipo"; then
			DI_REPO_ROOT="$r" bash "$S" >/dev/null 2>&1; check "sync: $tipo en $comp → ALTO" 3 $?
			check "sync: $tipo en $comp → marcadores fuera intactos" si "$(intactos "$vroot")"
			unlink_dir "$L/$comp" "$tipo"
		else
			echo "  —     $tipo no disponible en este Windows: caso $comp no probado"
		fi
	done
done
# Enlace DENTRO del destino: no se borra el destino ni lo enlazado.
sync_repo "$W/sx"; victima "$W/vx"
if mklink_dir "$W/sx/tmp/wp-en-local/site/wp-content/plugins/dra-irina-core/enlace" "$W/vx/wp-content/plugins" symlink; then
	DI_REPO_ROOT="$W/sx" bash "$S" >/dev/null 2>&1; check "sync: enlace dentro del destino → ALTO" 3 $?
	check "sync: enlace dentro del destino → marcador intacto" vivo "$(cat "$W/vx/wp-content/plugins/dra-irina-core/marcador.txt")"
	unlink_dir "$W/sx/tmp/wp-en-local/site/wp-content/plugins/dra-irina-core/enlace" symlink
fi
DI_REPO_ROOT="$W/s0" DI_LAB="$W/fuera" bash "$S" >/dev/null 2>&1; check "sync: laboratorio fuera de <repo>/tmp → ALTO" 3 $?
# Limpieza: los enlaces ya se retiraron uno a uno; ninguno queda antes del borrado recursivo.
if find "$W" -type l | grep -q .; then echo "  AVISO: quedan enlaces en $W; no se borra" >&2; bad=$((bad+1)); else rm -rf -- "$W"; fi
echo "q079_selftest: $ok OK, $bad fallos"
[ "$bad" = 0 ]
