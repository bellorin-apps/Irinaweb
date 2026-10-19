#!/usr/bin/env bash
# setup-en.sh real (lib local) contra WordPress local: aprobaciones, publicadas, fichas aprobadas y error de creación.
set -u
H="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"; cd "$H"
WP() { ./wp.sh "$@" 2>/dev/null; }
huella() { WP eval '
	use DraIrina\Core\I18n\Language;
	$o=[]; $root=Language::root_id();
	$ids=array_merge([$root], get_posts(["post_type"=>"page","post_status"=>"any","numberposts"=>-1,"fields"=>"ids","post_parent"=>$root]), get_posts(["post_type"=>["condicion","tratamiento"],"post_status"=>"any","numberposts"=>-1,"fields"=>"ids","meta_key"=>Language::META,"meta_value"=>"en"]));
	foreach(array_unique($ids) as $i){ $p=get_post($i); $o[$p->post_name]=[$p->post_status, (string)get_post_meta($i,"_di_page_approved_at",true)!=="" ? "aprobada" : "-", "page"===$p->post_type ? "" : \DraIrina\Core\Workflow\MedicalReview::state($i), md5((string)get_post_meta($i,"_elementor_data",true).$p->post_content), $p->post_modified_gmt]; }
	ksort($o); echo json_encode($o);'; }
cmpj() { python -I -c "
import json,sys; sys.stdout.reconfigure(encoding='utf-8')
a=json.loads(open(sys.argv[1]).read()); b=json.loads(open(sys.argv[2]).read())
d={k:(a.get(k),b.get(k)) for k in sorted(set(a)|set(b)) if a.get(k)!=b.get(k)}
print('      sin cambios' if not d else '\n'.join('      %s: %s → %s'%(k,v[0],v[1]) for k,v in d.items()))" "$1" "$2"; }
run() { local name="$1" base="$2" repo="$3"; shift 3
	cp "db/$base.sqlite" db/irina.sqlite; rm -f fallo.json fallo.log
	[ -n "${CFG:-}" ] && echo "$CFG" > fallo.json
	[ -n "${PREP:-}" ] && eval "$PREP"
	huella > "t/se-$name-antes.json"
	if [ "$repo" = mod ]; then DI_REPO="$H/repo-mod" bash deploy/setup-en.sh > "t/se-$name.log" 2>&1; else bash deploy/setup-en.sh > "t/se-$name.log" 2>&1; fi
	local rc=$?; rm -f fallo.json
	huella > "t/se-$name-despues.json"
	echo "  $name (base $base, contenido $repo): exit $rc"
	grep -E "Warning|Error|PUBLICADA" "t/se-$name.log" | sed 's/^/      log: /' | head -6
	cmpj "t/se-$name-antes.json" "t/se-$name-despues.json"
}
echo "== setup-en"
CFG= PREP= run S1-sin-cambios base-en-aprobado orig
CFG= PREP= run S2-faq-cambia base-en-aprobado mod
CFG= PREP= run S3-publicado-y-cambio en-publicado mod
CFG='{"modo":"crear-falla","slug":"faq"}' PREP='WP post delete "$(WP eval "echo get_page_by_path(\"en/faq\")->ID;")" --force >/dev/null' run S4-error-al-crear base-en-aprobado orig
[ -f fallo.log ] && sed 's/^/      inyección: /' fallo.log
cp db/en-publicado.sqlite db/irina.sqlite
