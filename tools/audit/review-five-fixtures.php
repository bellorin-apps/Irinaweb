<?php
/** Código real en fixtures sin WP autenticado, correo ni red. */
declare(strict_types=1);
$root=dirname(__DIR__,2);$case=[];$scripts=[];$actions=[];
function get_option($n,$d=[]) {return ['ga4_id'=>$GLOBALS['case']['id']??'G-5961XX8QZ7'];}
function is_user_logged_in():bool {return $GLOBALS['case']['logged']??false;}
function current_user_can($c):bool {return $GLOBALS['case']['editor']??false;}
function is_page($s):bool {return $s==='gracias'&&($GLOBALS['case']['thanks']??false);}
function wp_json_encode($v):string {return json_encode($v,JSON_THROW_ON_ERROR);}
function wp_print_inline_script_tag($s,$a):void {$GLOBALS['scripts'][$a['id']]=$s;}
function add_action($h,$f,...$a):void {$GLOBALS['actions'][$h][]=$f;}
function add_filter(...$a):void {}
function __($s,...$a):string {return $s;}
function wp_parse_args($a,$d):array {return array_merge($d,$a);}
function sanitize_key($s):string {return preg_replace('/[^a-z0-9_-]/','',strtolower($s));}
function esc_attr($s):string {return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
function esc_url($s):string {return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
function esc_html($s):string {return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
function wp_strip_all_tags($s):string {return strip_tags($s);}
function wp_kses($s,$a):string {return strip_tags($s,'<b><em><br>');}
function irina_asset_version($s):string {return 'fixture';}
function has_site_icon():bool {return $GLOBALS['site_icon']??false;}
require $root.'/wp-content/plugins/dra-irina-core/src/Settings/PracticeSettings.php';
require $root.'/wp-content/plugins/dra-irina-core/src/Tracking/Events.php';
$cases=[];
foreach(['public'=>[], 'thanks'=>['thanks'=>true], 'editor'=>['logged'=>true,'editor'=>true], 'invalid'=>['id'=>'invalid']] as $name=>$case){
 $scripts=[];$events=new DraIrina\Core\Tracking\Events();$events->gtag();$events->inline_script();
 if(in_array($name,['editor','invalid'],true)&&$scripts)throw new RuntimeException('exclusion');
 $cases[$name]=$scripts;
}
file_put_contents($root.'/tmp/five-ga4.json',json_encode($cases,JSON_THROW_ON_ERROR));
define('IRINA_THEME_DIR',$root.'/wp-content/themes/irina-gonzalez');define('IRINA_THEME_URI','https://example.test/theme');
$build=json_decode(file_get_contents($root.'/tools/content/dra-elementor.json'),true,512,JSON_THROW_ON_ERROR);
foreach($build as $c)foreach($c['elements'] as $e)if($e['widgetType']==='di-logos')$args=$e['settings'];
ob_start();require IRINA_THEME_DIR.'/templates/parts/blocks/logos.php';$html=ob_get_clean();
file_put_contents($root.'/tmp/five-logos.html',$html);
require IRINA_THEME_DIR.'/inc/setup.php';
foreach([false,true] as $site_icon){ob_start();foreach($actions['wp_head']??[] as $f)$f();$head=ob_get_clean();if(substr_count($head,'rel="manifest"')!==1)throw new RuntimeException('manifest');}
echo "OK editores/ID inválido excluidos; plantilla logos y manifest con/sin site_icon\n";
