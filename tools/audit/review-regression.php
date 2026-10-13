<?php
/** Fixtures sin WordPress autenticado, red, base de datos ni correos. */
declare(strict_types=1);
$root=dirname(__DIR__,2);
$filters=[];$scripts=[];$saved=[];
function add_action(...$a):void {}
function add_filter($hook,$callback,...$a):void {$GLOBALS['filters'][$hook][]=$callback;}
function wp_basename($s):string {return basename($s);}
function wp_normalize_path($s):string {return str_replace('\\','/',$s);}
function get_attached_file($id):string {return $GLOBALS['original'];}
function is_wp_error($v):bool {return $v instanceof Exception;}
function wp_get_image_editor($file) {return new class($file) {
 public function __construct(private string $file) {}
 public function set_quality($q):bool {$GLOBALS['saved'][]=['file'=>$this->file,'quality'=>$q];return true;}
 public function save($file,$mime) {return $GLOBALS['save_error']??['path'=>$file];}
};}
function get_option($name,$fallback=[]) {return ['ga4_id'=>'G-TEST1234'];}
function is_user_logged_in():bool {return false;}
function is_page($slug):bool {return false;}
function wp_print_inline_script_tag($script,$attrs):void {$GLOBALS['scripts'][$attrs['id']]=$script;}
function wp_json_encode($v):string {return json_encode($v,JSON_THROW_ON_ERROR);}
function check($ok,$name):void {if(!$ok)throw new RuntimeException($name);echo "OK $name\n";}
require $root.'/wp-content/themes/irina-gonzalez/inc/performance.php';
check($filters['wp_editor_set_quality'][0](82,'image/webp',['width'=>1600])===100,'original1600 conserva q100');
$GLOBALS['original']=$root.'/tmp/review-original.webp';file_put_contents($GLOBALS['original'],'original fixture');
$derived=$root.'/tmp/review-mobile.webp';file_put_contents($derived,'derived fixture');
$meta=['sizes'=>['irina-movil'=>['file'=>basename($derived),'mime-type'=>'image/webp'],'medium'=>['file'=>'medium.webp']]];
$updated=irina_mobile_metadata_quality($meta,1);
check(count($saved)===1&&$saved[0]['file']===$derived&&$saved[0]['quality']===95,'q95 solo derivado identificado');
check(file_get_contents($GLOBALS['original'])==='original fixture','original intacto');
irina_mobile_metadata_quality($updated,1);check(count($saved)===1,'metadata repetida no recomprime');
irina_mobile_metadata_quality(['sizes'=>[]],1);check(count($saved)===1,'sin tamaño móvil no edita');
irina_mobile_metadata_quality(['sizes'=>['irina-movil'=>['file'=>basename($GLOBALS['original']),'mime-type'=>'image/webp']]],1);check(count($saved)===1,'alias al original no edita');
$GLOBALS['save_error']=new Exception('fixture');$failed=irina_mobile_metadata_quality($meta,1);check(!isset($failed['sizes']['irina-movil']['irina_quality_95']),'fallo del editor no declara q95');
require $root.'/wp-content/plugins/dra-irina-core/src/Settings/PracticeSettings.php';
require $root.'/wp-content/plugins/dra-irina-core/src/Tracking/Events.php';
$events=new DraIrina\Core\Tracking\Events();$events->gtag();$events->inline_script();
file_put_contents($root.'/tmp/review-ga4.json',json_encode($scripts,JSON_THROW_ON_ERROR));
echo "OK scripts GA4 exportados para fixture JS\n";
function wp_parse_args($args,$defaults):array {return array_merge($defaults,$args);}
function esc_attr($s):string {return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
function esc_html($s):string {return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
function wp_kses($s,$allowed):string {return strip_tags($s,'<b><em><br>');}
$source=file_get_contents($root.'/tools/content/pages/primera-consulta.elementor.json');
foreach([['371','https://example.test/hero.webp'],['','']] as [$id,$url]) {
 $json=str_replace(['__PC_HERO_ID__','__PC_HERO_URL__'],[$id,$url],$source);
 $data=json_decode($json,true,512,JSON_THROW_ON_ERROR);
 check($json===str_replace(['__PC_HERO_ID__','__PC_HERO_URL__'],[$id,$url],$source),'sustitución estable desde build');
 check((int)$data[0]['elements'][0]['settings']['image']['id']===(int)$id,'ID con y sin adjunto');
}
$args=['image_id'=>0,'title'=>'Fixture','variant'=>'warm'];ob_start();require $root.'/wp-content/themes/irina-gonzalez/templates/parts/bleed.php';$html=ob_get_clean();
check(!str_contains($html,'<img')&&str_contains($html,'di-bleed__bg--warm')&&!str_contains($html,'di-bleed--photo'),'sin adjunto conserva degradado cálido');
