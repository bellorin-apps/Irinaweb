<?php
/** Real language/theme/schema callbacks over synthetic posts. No WP storage, mail or account. */
declare(strict_types=1);
$root = dirname(__DIR__, 2);
define('OBJECT', 'OBJECT');
define('IRINA_THEME_DIR', $root . '/wp-content/themes/irina-gonzalez');
define('IRINA_THEME_URI', 'https://example.test/theme');
define('IRINA_THEME_VERSION', 'audit');
define('IRINA_MOVIL_WIDTH', 1600);
class WP_Post { public function __construct(public int $ID, public string $post_type, public string $post_name, public string $post_status = 'draft', public int $post_parent = 0) {} }
$posts = [];
foreach ([10=>'home',11=>'sueno',12=>'otorrinolaringologia',13=>'preguntas-frecuentes',14=>'contacto',15=>'gracias'] as $id=>$slug) $posts[$id] = new WP_Post($id,'page',$slug,'publish');
foreach ([100=>'en',101=>'sleep',102=>'ent',103=>'faq',104=>'contact',105=>'thank-you'] as $id=>$slug) $posts[$id] = new WP_Post($id,'page',$slug,'draft',$id===100 ? 0 : 100);
$posts[90] = new WP_Post(90,'tratamiento','cpap','publish');
$posts[200] = new WP_Post(200,'tratamiento','cpap-therapy');
$pairs = [100=>10,101=>11,102=>12,103=>13,104=>14,105=>15,200=>90];
$actions = $filters = $styles = $rules = [];
$query = 0; $admin = false; $editor = false;
function get_post($id) { return $id instanceof WP_Post ? $id : ($GLOBALS['posts'][$id] ?? null); }
function get_post_type($id = 0) { return get_post($id ?: $GLOBALS['query'])?->post_type; }
function get_page_by_path($path, ...$rest) { foreach ($GLOBALS['posts'] as $p) { if ($p->post_type==='page' && ($p->post_parent ? 'en/' : '').$p->post_name===$path) return $p; } return null; }
function get_post_ancestors($post) { return $post->post_parent ? [$post->post_parent] : []; }
function get_post_status($id) { return get_post($id)?->post_status; }
function get_post_field($key, $id) { return get_post($id)?->$key ?? ''; }
function get_the_title($id) { return get_post($id)?->post_name ?? ''; }
function get_page_template_slug($id = 0) { return 'elementor_header_footer'; }
function get_post_meta($id, $key, $single = true) {
    if ($key==='_di_translation_of') return $GLOBALS['pairs'][$id] ?? '';
    if ($key==='_di_lang') return $id===200 ? 'en' : '';
    if ($key==='_elementor_data' && $id===103) return file_get_contents($GLOBALS['root'].'/tools/content/en/faq.elementor.json');
    if ($key==='_elementor_edit_mode') return 'builder';
    if ($key==='_wp_page_template') return 'elementor_header_footer';
    return '';
}
function get_posts($q) { foreach ($GLOBALS['pairs'] as $en=>$es) if ((string)$es===(string)($q['meta_value']??'')) return [$en]; return []; }
function get_permalink($id) { $p=get_post($id); $id=$p->ID; return 'https://example.test/'.($id===10 ? '' : ($id===200?'en/sleep/':($p->post_parent?'en/':'')).$p->post_name.'/'); }
function get_option($key, $fallback = []) { return $key==='page_on_front'?10:['whatsapp'=>'+528123685381','whatsapp_mensaje'=>'Hola','whatsapp_mensaje_en'=>'Hello']; }
function home_url($path = '/') { return 'https://example.test'.$path; }
function wp_parse_url($url, $component = -1) { return parse_url($url,$component); }
function wp_unslash($s) { return $s; }
function absint($v) { return abs((int)$v); }
function wp_get_current_user() { return null; }
function current_user_can(...$args) { return $GLOBALS['editor']; }
function get_queried_object_id() { return $GLOBALS['query']; }
function did_action($s) { return 1; }
function is_admin() { return $GLOBALS['admin']; }
function wp_doing_cron() { return false; }
function is_singular($types = null) { return $GLOBALS['query'] > 0 && ($types===null || in_array(get_post_type(),(array)$types,true)); }
function is_page($slugs = null) { return get_post_type()==='page' && ($slugs===null || in_array(get_post_field('post_name',$GLOBALS['query']),(array)$slugs,true)); }
function is_front_page() { return $GLOBALS['query']===10; }
function is_post_type_archive(...$args) { return false; }
function is_404() { return false; }
function has_term($slug, $tax, $post = null) { return $slug==='sueno' && get_post_type()==='tratamiento'; }
function add_action($h,$f,...$args) { $GLOBALS['actions'][$h][]=$f; }
function add_filter($h,$f,...$args) { $GLOBALS['filters'][$h][]=$f; }
function add_rewrite_rule(...$args) { $GLOBALS['rules'][]=$args; }
function esc_attr($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function esc_url($s) { return esc_attr($s); }
function esc_html($s) { return esc_attr($s); }
function wp_strip_all_tags($s) { return strip_tags($s); }
function do_shortcode($s) { return $s; }
function wp_enqueue_style($handle,...$args) { $GLOBALS['styles'][]=$handle; }
function wp_enqueue_script(...$args) {}
require $root.'/wp-content/plugins/dra-irina-core/src/I18n/Language.php';
require $root.'/wp-content/plugins/dra-irina-core/src/Settings/PracticeSettings.php';
require $root.'/wp-content/plugins/dra-irina-core/src/Schema/Graph.php';
require $root.'/wp-content/plugins/dra-irina-core/src/Contact/Form.php';
require IRINA_THEME_DIR.'/inc/i18n.php';
require IRINA_THEME_DIR.'/inc/setup.php';
require IRINA_THEME_DIR.'/inc/enqueue.php';
use DraIrina\Core\I18n\Language;
$language = new Language(); $checks = [];
function check($ok,$name) { if (!$ok) throw new RuntimeException($name); $GLOBALS['checks'][$name]=true; }
function context($id,$path) { $GLOBALS['query']=$id; $_SERVER['REQUEST_URI']=$path; (new Language())->reset_current(); }
context(11,'/sueno/');
check(Language::current()==='es' && Language::of(101)==='en','language by page hierarchy');
check(irina_language_switcher()==='','switch hidden while root draft');
ob_start(); $language->hreflang(); $tags=ob_get_clean(); check($tags==='','draft counterpart has no hreflang');
$posts[100]->post_status=$posts[101]->post_status='publish';
context(11,'/sueno/'); ob_start();$language->hreflang();$es=ob_get_clean();
context(101,'/en/sleep/');ob_start();$language->hreflang();$en=ob_get_clean();
check($es===$en && substr_count($en,'hreflang=')===3 && str_contains($en,'x-default'),'reciprocal hreflang three tags');
check(str_contains(irina_language_switcher(),'https://example.test/sueno/'),'EN switch to paired ES');
context(12,'/otorrinolaringologia/');check(Language::switch_url('en')==='https://example.test/en/','missing published counterpart fallback');
context(101,'/en/sleep/');check(irina_is_page('sueno') && !irina_is_page('otorrinolaringologia'),'EN Sleep maps to ES Sleep');
foreach (['/en/','/en/sleep/','/en/nonexistent/'] as $path) { context(0,$path);check($language->locale('es_MX')==='en_US','locale '.$path); }
context(0,'/enough/');check($language->locale('es_MX')==='es_MX','prefix is boundary aware');
context(0,'/en/contact/');check(!$language->guess_404(true),'EN404 never guesses a Spanish destination');
context(0,'/contact/');check($language->guess_404(true),'ES canonical guessing unchanged');
$admin=true;context(0,'/en/');check($language->locale('es_MX')==='es_MX','admin locale unchanged');$admin=false;
$_GET=['preview'=>'true','page_id'=>101];context(0,'/?preview=true&page_id=101');
check($language->locale('es_MX')==='es_MX','anonymous preview does not choose draft locale');
$editor=true;check($language->locale('es_MX')==='en_US','authorized preview locale');$editor=false;$_GET=[];
foreach ([11,101,12,102] as $id) {
    context($id,$id>=100?'/en/':'/');$styles=[];
    foreach ($actions['wp_enqueue_scripts'] as $fn) $fn();
    $classes=[];foreach ($filters['body_class'] as $fn) $classes=$fn($classes);
    $sleep=in_array($id,[11,101],true);
    check(in_array('irina-sleep',$styles,true)===$sleep && in_array('di-dark',$classes,true)===$sleep,'sleep CSS/body '.$id);
}
$method=new ReflectionMethod(DraIrina\Core\Schema\Graph::class,'area_page');
foreach ([11=>'es-MX',101=>'en-US'] as $id=>$lang) { $area=$method->invoke(new DraIrina\Core\Schema\Graph(),$id);check($area['inLanguage']===$lang && count($area['about'])===2,'area schema '.$lang); }
$faq=(new ReflectionMethod(DraIrina\Core\Schema\Graph::class,'faq_page'))->invoke(new DraIrina\Core\Schema\Graph(),103);
check($faq['inLanguage']==='en-US' && count($faq['mainEntity'])===10,'FAQ schema from ten real build questions');
context(101,'/en/sleep/');check(str_contains(DraIrina\Core\Settings\PracticeSettings::whatsapp_url(),'Hello'),'English WhatsApp message');
check(DraIrina\Core\Contact\Form::motivos()['sueno']==='Snoring or sleep apnea','English form categories without submission');
$posts[105]->post_status='publish';check(DraIrina\Core\Contact\Form::thanks_url('en')==='https://example.test/en/thank-you/','English thanks destination');
$posts[105]->post_status='draft';check(DraIrina\Core\Contact\Form::thanks_url('en')==='https://example.test/gracias/','unpublished thanks safe fallback');
$language->rewrites();check(str_contains($rules[0][0],'^en/sleep/') && str_contains($rules[0][1],'di_lang=en'),'EN clinical rewrite');
file_put_contents($root.'/docs/audit/2026-10-19/i18n.json',json_encode(['checks'=>$checks,'limits'=>'Real callbacks on synthetic posts/capabilities and fixture publication states; not real WordPress, Rank Math integration, sitemap, authentication, SMTP, medical review or production publication.'],JSON_PRETTY_PRINT)."\n");
echo 'I18n: '.count($checks)." checks pass.\n";
