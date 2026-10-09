#!/usr/bin/env bash
# Configura el sitio tras desplegar código (idempotente): páginas del sitemap, Home v2 (noindex hasta CP3),
# menús, contenido de muestra privado para revisar las plantillas médicas, flush de rewrites y caché.
. "$(dirname "${BASH_SOURCE[0]}")/lib.sh"
WP="cd '$WEBROOT' && /opt/alt/php84/usr/bin/php /usr/local/bin/wp --skip-plugins=elementor,elementor-pro"
w(){ rssh "$WP $*"; }

echo "→ Páginas del sitemap (solo si faltan; se crean como BORRADOR salvo que se indique otro estado — decisión del propietario 2026-10-07: nada vacío publicado ni indexable antes del CP3)"
# --name= no encuentra borradores con --post_status=any (WP_Query); --post_name__in sí.
page_id(){ w "post list --post_type=page --post_name__in='$1' --post_status=any --orderby=ID --order=ASC --field=ID" | head -1; }
ensure_page(){ # slug título [estado=draft] → imprime id
  local id st="${3:-draft}"; id="$(page_id "$1")"
  if [ -z "$id" ]; then id="$(w "post create --post_type=page --post_status=$st --post_title='$2' --post_name='$1' --porcelain")"; echo "  creada $1 ($id, $st)" >&2; else echo "  existe $1 ($id)" >&2; fi
  echo "$id"
}
P_HOME="$(ensure_page inicio-v2 'Inicio' publish)"
P_DRA="$(ensure_page dra-irina-gonzalez-saez 'Dra. Irina González Sáez')"
P_ORL="$(ensure_page otorrinolaringologia 'Otorrinolaringología')"
P_SUENO="$(ensure_page sueno 'Sueño')"
P_PRIMERA="$(ensure_page primera-consulta 'Primera consulta')"
P_FAQ="$(ensure_page preguntas-frecuentes 'Preguntas frecuentes')"
P_CONTACTO="$(ensure_page contacto 'Contacto')"
P_PRIV="$(ensure_page aviso-de-privacidad 'Aviso de privacidad')"
P_MED="$(ensure_page aviso-medico 'Aviso médico')"
P_TERM="$(ensure_page terminos-de-uso 'Términos de uso')"

echo "→ Home v2 (página $P_HOME, noindex hasta CP3)"
# Foto del hero: adjunto con slug hero-home en la biblioteca (subir con tools/deploy/upload-media.sh). Si no existe, el widget usa el degradado.
HERO_ID="$(w "post list --post_type=attachment --post_name__in=hero-home --post_status=any --field=ID" | head -1)"
HERO_URL=""; [ -n "$HERO_ID" ] && HERO_URL="$(w "post get $HERO_ID --field=guid")"
# Fotos de los paneles de área: adjuntos con slug area-orl y area-sueno (upload-media.sh). Sin ellos, el panel usa el degradado.
ORL_ID="$(w "post list --post_type=attachment --post_name__in=area-orl --post_status=any --field=ID" | head -1)"; ORL_URL=""; [ -n "$ORL_ID" ] && ORL_URL="$(w "post get $ORL_ID --field=guid")"
SUENO_ID="$(w "post list --post_type=attachment --post_name__in=area-sueno --post_status=any --field=ID" | head -1)"; SUENO_URL=""; [ -n "$SUENO_ID" ] && SUENO_URL="$(w "post get $SUENO_ID --field=guid")"
sed -e "s#__HERO_ID__#${HERO_ID}#g" -e "s#__HERO_URL__#${HERO_URL}#g" -e "s#__ORL_IMG_ID__#${ORL_ID}#g" -e "s#__ORL_IMG_URL__#${ORL_URL}#g" -e "s#__SUENO_IMG_ID__#${SUENO_ID}#g" -e "s#__SUENO_IMG_URL__#${SUENO_URL}#g" "$REPO/tools/content/home-elementor.json" > /tmp/home-elementor.json
echo "  hero-home: ${HERO_ID:-sin foto} · area-orl: ${ORL_ID:-sin foto} · area-sueno: ${SUENO_ID:-sin foto}"
rscp /tmp/home-elementor.json "$SSH_USER@$SSH_HOST:/tmp/home-elementor.json"; rm -f /tmp/home-elementor.json
w "post meta update $P_HOME _wp_page_template elementor_header_footer" >/dev/null
w "post meta update $P_HOME _elementor_edit_mode builder" >/dev/null
w "post meta update $P_HOME _elementor_template_type wp-page" >/dev/null
w "post meta update $P_HOME _elementor_version 4.3.4" >/dev/null
# Igual que Elementor: JSON como string con wp_slash (wp post meta update aplicaría wp_unslash y rompería los \n).
w "eval 'update_post_meta( $P_HOME, \"_elementor_data\", wp_slash( file_get_contents( \"/tmp/home-elementor.json\" ) ) ); echo strlen( get_post_meta( $P_HOME, \"_elementor_data\", true ) ) . \" bytes\\n\";'"
rssh "rm -f /tmp/home-elementor.json"
w "post meta delete $P_HOME _elementor_css" >/dev/null 2>&1 || true
# Elementor cachea el HTML de cada elemento en post meta; sin borrarlo, los cambios de _elementor_data no se ven.
w "post meta delete $P_HOME _elementor_element_cache" >/dev/null 2>&1 || true
# noindex solo mientras NO sea la portada: tras go-live (page_on_front=168) marcarla vaciaba el sitemap de Rank Math (09-10).
if [ "$(w "option get page_on_front")" = "$P_HOME" ]; then
  w "post meta delete $P_HOME rank_math_robots" >/dev/null 2>&1 || true
  echo "  $P_HOME es la portada: sin noindex"
else
  w "post meta update $P_HOME rank_math_robots --format=json '[\"noindex\",\"nofollow\"]'" >/dev/null
fi

echo "→ Menús"
ensure_menu(){ w "menu list --fields=slug --format=csv" | grep -qx "$1" || w "menu create '$2'" >/dev/null; }
# El slug debe coincidir con el que WordPress deriva del nombre ("Pie de página" → pie-de-pagina).
ensure_menu principal Principal; ensure_menu pie-de-pagina 'Pie de página'; ensure_menu legal Legal
menu_items(){ w "menu item list $1 --fields=object_id --format=csv" | tail -n +2; }
add_items(){ local menu="$1"; shift; local have; have="$(menu_items "$menu")"; for id in "$@"; do echo "$have" | grep -qx "$id" || w "menu item add-post $menu $id" >/dev/null; done; }
add_items principal "$P_ORL" "$P_SUENO" "$P_DRA" "$P_PRIMERA" "$P_CONTACTO"
add_items pie-de-pagina "$P_ORL" "$P_SUENO" "$P_PRIMERA" "$P_FAQ" "$P_CONTACTO"
add_items legal "$P_PRIV" "$P_MED" "$P_TERM"
w "menu location assign principal primary" >/dev/null; w "menu location assign pie-de-pagina footer" >/dev/null; w "menu location assign legal legal" >/dev/null

echo "→ Contenido de muestra privado (solo para revisar plantillas; se borra antes del launch)"
S_ID="$(w "post list --post_type=condicion --post_name__in=apnea-obstructiva-del-sueno --post_status=any --orderby=ID --order=ASC --field=ID" | head -1)"
if [ -z "$S_ID" ]; then
  S_ID="$(w "post create --post_type=condicion --post_status=private --post_title='Apnea obstructiva del sueño' --post_name=apnea-obstructiva-del-sueno --post_content='<p>Durante el sueño, la vía aérea superior se estrecha o se cierra por momentos y la respiración se interrumpe. El cuerpo reacciona con microdespertares que fragmentan el descanso, aunque la persona no los recuerde.</p><p><strong>Borrador de muestra. El texto clínico lo redacta y aprueba la Dra.</strong></p>' --porcelain")"
  w "post term set $S_ID area sueno" >/dev/null
  w "post meta update $S_ID di_resumen_paciente 'Qué es, cómo se diagnostica y qué opciones de tratamiento existen, explicado por una otorrinolaringólogo especialista en sueño.'" >/dev/null
  w "post meta update $S_ID di_sintomas --format=json '[\"Ronquido fuerte con pausas que otros notan\",\"Sueño no reparador y somnolencia durante el día\",\"Dolor de cabeza al despertar, boca seca\",\"Dificultad para concentrarse, irritabilidad\"]'" >/dev/null
  w "post meta update $S_ID di_cuando_consultar --format=json '[\"Si roncas y alguien ha visto que dejas de respirar\",\"Si te despiertas cansado a pesar de dormir suficiente\",\"Si tienes presión alta difícil de controlar\"]'" >/dev/null
  w "post meta update $S_ID di_diagnostico 'La Dra. explora la nariz, el paladar y la garganta, con endoscopia en el consultorio, y realiza una poligrafía respiratoria o un estudio de sueño en casa que ella misma interpreta.'" >/dev/null
  w "post meta update $S_ID di_faq --format=json '[{\"pregunta\":\"¿La apnea se cura?\",\"respuesta\":\"Respuesta redactada y aprobada por la Dra.\"},{\"pregunta\":\"¿Tengo que dormir en un laboratorio para el estudio?\",\"respuesta\":\"Respuesta redactada y aprobada por la Dra.\"}]'" >/dev/null
  w "post meta update $S_ID di_fuentes --format=json '[{\"titulo\":\"Guías clínicas citadas por la Dra. (pendiente)\",\"autor\":\"\",\"anio\":\"\",\"url\":\"\",\"tipo\":\"guia\"}]'" >/dev/null
  echo "  creada condición privada $S_ID"
else echo "  existe condición $S_ID"; fi

echo "→ Rewrites y caché"
# No usar `rewrite flush` aquí: con --skip-plugins=elementor las reglas de Elementor/Pro se perderían.
# Borrar la opción hace que WordPress las regenere en la siguiente petición web con TODOS los plugins cargados.
w "option delete rewrite_rules" >/dev/null 2>&1 || true; w "cache flush" >/dev/null
curl -s -o /dev/null "$SITE_URL/?cb=$(date +%s)"
w "litespeed-purge all" >/dev/null 2>&1 || true

echo "→ Verificación"
for u in "/" "/inicio-v2/" "/op/" "/wp-json/dra-irina/v1/practice"; do printf '  %s → %s\n' "$u" "$(curl -sL -o /dev/null -w '%{http_code}' "$SITE_URL$u?cb=$(date +%s)")"; done
echo "  Home v2 referencia el tema: $(curl -sL "$SITE_URL/inicio-v2/?cb=$(date +%s)" | grep -c 'di-bleed\|di-header') elementos di-*"
echo "Home v2 (noindex): $SITE_URL/inicio-v2/  ·  Condición de muestra (privada, con sesión iniciada): $SITE_URL/sueno/apnea-obstructiva-del-sueno/"
