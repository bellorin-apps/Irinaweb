#!/usr/bin/env bash
# Errores de escritura durante la publicación (wrapper real publish-en.sh --legal-ok --yes contra WordPress local), desde la base
# con todo aprobado. Debe terminar con error, sin anunciar éxito, y dejar todo en borrador salvo los IDs que la restauración no pudo.
set -u
H="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"; cd "$H"
WP() { ./wp.sh "$@" 2>/dev/null; }
base() { cp db/base-en-aprobado.sqlite db/irina.sqlite; rm -f fallo.json fallo.log; }
caso() {
	local name="$1" cfg="$2"
	base; echo "$cfg" > fallo.json
	bash deploy/publish-en.sh --legal-ok --yes > "t/fw-$name.log" 2>&1; local rc=$?
	rm -f fallo.json
	WP eval-file t/estado.php > "t/fw-$name.json"
	local pub; pub="$(python -I -c "import json,sys; d=json.load(open(sys.argv[1])); print(','.join(k+':'+v['slug'] for k,v in d.items() if v['status']=='publish') or '-')" "t/fw-$name.json")"
	printf '  %-24s exit %-2s éxito anunciado: %-3s publicadas tras el fallo: %s\n' "$name" "$rc" "$(grep -q 'Versión inglesa publicada' "t/fw-$name.log" && echo SÍ || echo no)" "$pub"
	grep -oE 'Error: .{0,170}' "t/fw-$name.log" | sed 's/^/      /'
	[ -f fallo.log ] && sed 's/^/      inyección: /' fallo.log
}
echo "== Fallos de escritura (fallo en /en/contact/, 6.ª entrada)"
caso wp_error '{"modo":"wperror","slug":"en/contact"}'
caso estado-retenido '{"modo":"retenido","slug":"en/contact"}'
caso excepcion '{"modo":"excepcion","slug":"en/contact"}'
caso restauracion-falla '{"modo":"excepcion","slug":"en/contact","restaurar_falla":"en/ent"}'
caso falla-en-la-raiz '{"modo":"wperror","slug":"en"}'
base
