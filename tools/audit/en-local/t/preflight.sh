#!/usr/bin/env bash
# Casos de preflight de publish-en.php contra WordPress local real (todo aprobado de base): cada caso debe terminar en error
# con `yes legal-ok` y sin cambiar el estado de las 16 entradas. Restaura la base aprobada entre casos.
set -u
H="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"; R="$(cd "$H/../.." && pwd)"; cd "$H"
WP() { ./wp.sh "$@" 2>/dev/null; }
base() { cp db/base-en-aprobado.sqlite db/irina.sqlite; }
caso() { # nombre, preparación (función), args
	local name="$1" prep="$2"; shift 2
	base
	local D; D="$(cygpath -m "$H/rtmp/pf-$name")"; rm -rf "$D"; mkdir -p "$D"
	cp "$R/tools/content/en/pages.json" "$R/tools/content/en/fichas-en.json" "$R/tools/deploy/en/publish-en.php" "$D/"
	$prep "$D"
	WP eval-file t/estado.php > "t/pf-$name-antes.json"
	./wp.sh eval-file "$D/publish-en.php" "$@" > "t/pf-$name.log" 2>&1; local rc=$?
	# eval-file vía wp.sh: el código de salida es el de WP-CLI (pipefail en wp.sh).
	WP eval-file t/estado.php > "t/pf-$name-despues.json"
	local st="SIN CAMBIOS"; cmp -s "t/pf-$name-antes.json" "t/pf-$name-despues.json" || st="CAMBIÓ"
	printf '  %-28s exit %-2s %-12s %s\n' "$name" "$rc" "$st" "$(grep -oE 'Error: .{0,110}' "t/pf-$name.log")"
}
py() { "$(command -v python)" -I -c "$1" "$2"; }
p_ok()        { :; }
p_vacio()     { echo '{}' > "$1/pages.json"; }
p_truncado()  { py "import json,sys;p=sys.argv[1]+'/pages.json';d=json.load(open(p,encoding='utf-8'));d.pop('faq');json.dump(d,open(p,'w',encoding='utf-8'))" "$1"; }
p_json_roto() { head -c 200 "$1/pages.json" > "$1/x"; mv "$1/x" "$1/pages.json"; }
p_ficha_dup() { py "import json,sys;p=sys.argv[1]+'/fichas-en.json';d=json.load(open(p,encoding='utf-8'));d[1]=d[0];json.dump(d,open(p,'w',encoding='utf-8'))" "$1"; }
p_fichas_4()  { py "import json,sys;p=sys.argv[1]+'/fichas-en.json';d=json.load(open(p,encoding='utf-8'));json.dump(d[:4],open(p,'w',encoding='utf-8'))" "$1"; }
p_tipo_pag()  { py "import json,sys;p=sys.argv[1]+'/pages.json';d=json.load(open(p,encoding='utf-8'));d['privacy-notice']['kind']='elementor';json.dump(d,open(p,'w',encoding='utf-8'))" "$1"; }
p_tipo_fic()  { py "import json,sys;p=sys.argv[1]+'/fichas-en.json';d=json.load(open(p,encoding='utf-8'));d[0]['post_type']='tratamiento';json.dump(d,open(p,'w',encoding='utf-8'))" "$1"; }
p_sin_pagina(){ WP post update "$(WP eval 'echo get_page_by_path("en/faq")->ID;')" --post_name=faq-movida >/dev/null; }
p_sin_ficha() { WP post meta delete "$(WP eval 'echo get_posts(["post_type"=>"condicion","post_name__in"=>["snoring"],"post_status"=>"any","fields"=>"ids"])[0];')" _di_lang >/dev/null; }
p_idioma()    { WP post update "$(WP eval 'echo get_page_by_path("en/contact")->ID;')" --post_parent=0 >/dev/null; }
p_pareja_es() { WP post update "$(WP eval 'echo get_page_by_path("preguntas-frecuentes")->ID;')" --post_status=draft >/dev/null; }
p_ficha_es()  { WP post update "$(WP eval 'echo get_posts(["post_type"=>"tratamiento","post_name__in"=>["cpap"],"post_status"=>"any","fields"=>"ids"])[0];')" --post_status=draft >/dev/null; }
p_sin_aprob_pag() { WP post meta delete "$(WP eval 'echo get_page_by_path("en/sleep")->ID;')" _di_page_approved_at >/dev/null; }
p_sin_aprob_fic() { WP post term set "$(WP eval 'echo get_posts(["post_type"=>"tratamiento","post_name__in"=>["cpap-therapy"],"post_status"=>"any","fields"=>"ids"])[0];')" estado_medico medical_review_required >/dev/null; }
echo "== Preflight (debe bloquear con yes legal-ok y no cambiar nada)"
caso manifiesto-vacio p_vacio yes legal-ok
caso manifiesto-truncado p_truncado yes legal-ok
caso json-invalido p_json_roto yes legal-ok
caso ficha-duplicada p_ficha_dup yes legal-ok
caso fichas-incompletas p_fichas_4 yes legal-ok
caso tipo-pagina-incorrecto p_tipo_pag yes legal-ok
caso tipo-ficha-incorrecto p_tipo_fic yes legal-ok
caso pagina-ausente p_sin_pagina yes legal-ok
caso ficha-sin-idioma p_sin_ficha yes legal-ok
caso pagina-idioma-incorrecto p_idioma yes legal-ok
caso pareja-es-no-publicada p_pareja_es yes legal-ok
caso ficha-es-no-publicada p_ficha_es yes legal-ok
caso falta-aprobacion-pagina p_sin_aprob_pag yes legal-ok
caso falta-aprobacion-ficha p_sin_aprob_fic yes legal-ok
caso falta-legal-ok p_ok yes
caso argumento-desconocido p_ok yes legal-ok otro
base
p_meta_es()   { WP post meta update "$(WP eval 'echo get_page_by_path("en/contact")->ID;')" _di_lang es >/dev/null; }
caso pagina-bajo-en-marcada-es p_meta_es yes legal-ok
base
