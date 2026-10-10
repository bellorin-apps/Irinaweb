<?php
/** Pruebas de límites de seguridad, sin base de datos, envíos ni cambios en WordPress. */
declare(strict_types=1);

$root = dirname(__DIR__, 2);
function add_action(...$args): void {}
function __($text, $domain = ''): string { return $text; }
function add_filter(...$args): void {}
function apply_filters($hook, $value) { return $value; }
function home_url($path = ''): string { return 'https://example.test' . $path; }
function is_page($slug = null): bool { return $slug === 'preguntas-frecuentes' && empty($GLOBALS['not_faq']); }
function is_front_page(): bool { return false; }
function is_singular($types): bool { return false; }
function get_queried_object_id(): int { return 1; }
function get_post_status($id): string { return $GLOBALS['post_status'] ?? 'publish'; }
function get_post_type($id): string { return 'condicion'; }
function current_user_can($cap, ...$args): bool { return (bool)($GLOBALS['medical_cap'] ?? false); }
function wp_set_object_terms($id, $state, ...$args) { $GLOBALS['state']=$state; return []; }
function delete_post_meta(...$args): bool { return true; }
function wp_update_post($data) { $GLOBALS['post_status']=$data['post_status'];return $data['ID']; }
function get_posts($query): array { return ($GLOBALS['destination_published'] ?? false) ? [(object)['post_status'=>'publish','post_name'=>'sueno/ronquido']] : []; }
function get_page_by_path($slug) { return (object)['post_status'=>'publish','post_name'=>$slug]; }
function wp_parse_url($url, $component = -1) { return parse_url($url, $component); }
function get_permalink($id = null): string { return is_object($id) ? 'https://example.test/' . $id->post_name . '/' : 'https://example.test/preguntas-frecuentes/'; }
function get_post_field(...$args): string { return 'preguntas-frecuentes'; }
function wp_strip_all_tags($s): string { return strip_tags($s); }
function do_shortcode($s): string { return str_replace('[di_phone]', '81 1234 5678', $s); }
function sanitize_text_field($s): string { return trim(strip_tags($s)); }
function wp_unslash($s) { return $s; }
function wp_kses_uri_attributes(): array { return ['href','src']; }
function esc_attr($s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function wp_json_encode($v, $flags = 0) { return json_encode($v, $flags); }
function get_option($name, $fallback = []) { return $GLOBALS['practice'] ?? $fallback; }
function get_post_meta($id, $key, $single = true) { return $key === '_elementor_data' ? json_encode($GLOBALS['elementor']) : ''; }
function wp_get_object_terms(...$args): array { return [$GLOBALS['state'] ?? 'draft']; }
function set_transient(...$args): bool { return true; }
function get_current_user_id(): int { return 5; }
function check(bool $ok, string $name): void { if (!$ok) { throw new RuntimeException($name); } echo "OK $name\n"; }

require $root . '/wp-content/plugins/dra-irina-core/src/Settings/PracticeSettings.php';
require $root . '/wp-content/plugins/dra-irina-core/src/Taxonomies/AbstractTaxonomy.php';
require $root . '/wp-content/plugins/dra-irina-core/src/Taxonomies/EstadoMedico.php';
require $root . '/wp-content/plugins/dra-irina-core/src/Workflow/MedicalReview.php';
require $root . '/wp-content/plugins/dra-irina-core/src/Schema/Graph.php';
require $root . '/wp-content/plugins/dra-irina-core/src/Contact/Form.php';
require $root . '/wp-content/plugins/dra-irina-core/src/Fields/MetaRegistry.php';
check(DraIrina\Core\Fields\MetaRegistry::sanitize('2026-02-30',['type'=>'string','format'=>'date'])==='', 'fecha de revisión inexistente rechazada');
check(DraIrina\Core\Fields\MetaRegistry::sanitize('2026-02-28',['type'=>'string','format'=>'date'])==='2026-02-28', 'fecha válida conservada');
require $root . '/wp-content/themes/irina-gonzalez/inc/template-tags.php';
check(irina_public_content_url('https://other.test/sueno/')==='https://other.test/sueno/','enlace externo conservado');
check(irina_public_content_url('/sueno/ronquido/')==='https://example.test/primera-consulta/', 'destino clínico no publicado usa salida pública');
$GLOBALS['destination_published']=true;
check(irina_public_content_url('/sueno/ronquido/')==='https://example.test/sueno/ronquido/', 'destino publicado resuelto por permalink');

$GLOBALS['practice'] = ['maps_api_key'=>'test-private', 'contacto_copia'=>'private@example.test', 'horario'=>'Lunes|09:00-18:00', 'horario_oculto'=>true];
$public = DraIrina\Core\Settings\PracticeSettings::public_data();
check(!isset($public['maps_api_key'], $public['contacto_copia']) && !isset($public['horario']), 'REST omite clave, Bcc y horario oculto');
$GLOBALS['practice']['horario_oculto'] = false;
check(isset(DraIrina\Core\Settings\PracticeSettings::public_data()['horario']), 'horario público al desmarcar oculto');
$tax = new ReflectionMethod(DraIrina\Core\Taxonomies\EstadoMedico::class, 'args');
$caps = $tax->invoke(new DraIrina\Core\Taxonomies\EstadoMedico())['capabilities'];
check(count(array_unique($caps)) === 1 && $caps['assign_terms'] === 'approve_medical_content', 'asignación REST exige aprobación médica');
$review = new DraIrina\Core\Workflow\MedicalReview();
foreach (['condicion','tratamiento','recurso'] as $type) {
 foreach (['publish','future'] as $status) {
  $GLOBALS['state']='draft';
  check($review->block_unapproved_publish(['post_type'=>$type,'post_status'=>$status],['ID'=>2])['post_status']==='pending', "$type $status sin aprobación queda pending");
  $GLOBALS['state']='medically_approved';
  check($review->block_unapproved_publish(['post_type'=>$type,'post_status'=>$status],['ID'=>2])['post_status']===$status, "$type $status aprobado conserva estado");
 }
}
$GLOBALS['elementor'] = [['elements'=>[['widgetType'=>'di-faq','settings'=>['items'=>[['pregunta'=>'¿Cómo agendar?','text'=>'Llama a [di_phone]'],['pregunta'=>'','text'=>'Sin pregunta']]]]]]];
$GLOBALS['state']='medically_approved';$GLOBALS['medical_cap']=false;
$review->review_changed_meta(1,2,'di_diagnostico');
check($GLOBALS['state']==='medical_review_required' && $GLOBALS['post_status']==='pending','editar clínica aprobada sin autoridad médica exige nueva revisión');
$GLOBALS['state']='medically_approved';$GLOBALS['post_status']='publish';$GLOBALS['medical_cap']=true;
$review->review_changed_meta(1,2,'di_diagnostico');
check($GLOBALS['state']==='medically_approved' && $GLOBALS['post_status']==='publish','edición de revisora conserva autoridad médica');
$graph = new DraIrina\Core\Schema\Graph();
$faq = $graph->graph();
check(count($faq)===1 && count($faq[0]['mainEntity'])===1 && $faq[0]['mainEntity'][0]['acceptedAnswer']['text']==='Llama a 81 1234 5678', 'FAQ única, desde widgets y shortcodes visibles');
$GLOBALS['not_faq']=true;
check($graph->graph()===[], 'FAQ no se emite fuera de Preguntas frecuentes');
$GLOBALS['not_faq']=false;
$GLOBALS['elementor']=json_decode(file_get_contents($root.'/tools/content/pages/preguntas-frecuentes.elementor.json'),true);
check(count($graph->graph()[0]['mainEntity'])===9, 'nueve FAQ reales del build sin contenido duplicado');
$GLOBALS['elementor'] = [['elements'=>[['widgetType'=>'di-faq','settings'=>['items'=>[['pregunta'=>'¿Cómo agendar?','text'=>'Llama a [di_phone]']]]]]]];
$GLOBALS['elementor'][0]['elements'][0]['settings']['items'][0]['text']='Texto </script><script>alert(1)</script>';
ob_start(); $graph->output(); $json=ob_get_clean();
check(substr_count($json,'</script>')===1, 'JSON-LD no puede cerrar el elemento script');
$process = new ReflectionMethod(DraIrina\Core\Contact\Form::class,'process');
$_SERVER['REQUEST_METHOD']='POST'; $_POST=['di_name'=>['malformed']];
check($process->invoke(new DraIrina\Core\Contact\Form())['error']==='form', 'campo array rechazado sin enviar correo');
$_SERVER['REQUEST_METHOD']='GET';$_POST=[];
check($process->invoke(new DraIrina\Core\Contact\Form())['ok']===false, 'GET rechazado sin enviar correo');

// API HTML real de WordPress (ruta externa opcional; no se versiona WordPress).
$wp = getenv('IRINA_WP_SOURCE');
if ($wp) {
	define('ABSPATH', rtrim($wp, '/\\') . '/');
	define('WPINC', 'wp-includes');
 foreach (['compat.php','utf8.php','html-api/class-wp-html-decoder.php','html-api/class-wp-html-attribute-token.php','html-api/class-wp-html-span.php','html-api/class-wp-html-text-replacement.php','html-api/class-wp-html-tag-processor.php'] as $file) {
  if (is_file($wp.'/wp-includes/'.$file)) { require_once $wp.'/wp-includes/'.$file; }
 }
 require $root . '/wp-content/themes/irina-gonzalez/inc/performance.php';
 $html=irina_normalize_image_attributes('<img src="photo.png" loading="eager" loading="lazy" fetchpriority="high" fetchpriority="low">');
 check(substr_count($html,'loading=')===1 && substr_count($html,'fetchpriority=')===1 && str_contains($html,'eager') && str_contains($html,'high'), 'atributos duplicados normalizados con API real de WordPress');
}
