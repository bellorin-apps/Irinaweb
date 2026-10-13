<?php
/** Editor GD real de WordPress6.8.3 con funciones periféricas aisladas, sin base de datos. */
declare(strict_types=1);
$root=dirname(__DIR__,2);$hooks=[];
function add_action(...$a):void {}
function add_filter($h,$f,...$a):void {$GLOBALS['hooks'][$h][]=$f;}
function apply_filters($h,$v,...$a) {foreach($GLOBALS['hooks'][$h]??[] as $f)$v=$f($v,...$a);return $v;}
function __($s,...$a):string {return $s;}
function wp_raise_memory_limit(...$a):void {}
function wp_get_image_mime($s) {return getimagesize($s)['mime'];}
function wp_getimagesize($s) {return getimagesize($s);}
function is_gd_image($i):bool {return $i instanceof GdImage;}
function is_wp_error($i):bool {return $i instanceof WP_Error;}
function wp_get_mime_types():array {return ['webp'=>'image/webp','jpg|jpeg|jpe'=>'image/jpeg','png'=>'image/png'];}
function wp_get_default_extension_for_mime_type($mime) {return explode('|',(string)array_search($mime,wp_get_mime_types(),true))[0];}
function wp_get_image_editor_output_format(...$a):array {return [];}
function wp_basename($s,$suffix=''):string {return basename($s,$suffix);}
function trailingslashit($s):string {return rtrim($s,'/\\').'/';}
function wp_normalize_path($s):string {return str_replace('\\','/',$s);}
function wp_is_stream($s):bool {return false;}
function wp_filesize($s) {return filesize($s);}
function wp_mkdir_p($s):bool {return is_dir($s)||mkdir($s,0777,true);}
function get_attached_file($id):string {return $GLOBALS['original'];}
function wp_get_image_editor($s) {$e=new WP_Image_Editor_GD($s);$result=$e->load();$GLOBALS['last_editor']=$e;return is_wp_error($result)?$result:$e;}
foreach(['class-wp-error.php','class-wp-image-editor.php','class-wp-image-editor-gd.php'] as $f)require $root.'/tmp/wordpress-source/wordpress/wp-includes/'.$f;
// Carga solo la función real de lectura WebP, sin inicializar WordPress.
$media=file_get_contents($root.'/tmp/wordpress-source/wordpress/wp-includes/media.php');
$start=strpos($media,'function wp_get_webp_info(');$end=strpos($media,"\n}",$start)+2;eval(substr($media,$start,$end-$start));
require $root.'/wp-content/themes/irina-gonzalez/inc/performance.php';
$img=imagecreatetruecolor(1600,900);
for($x=0;$x<1600;$x+=8)for($y=0;$y<900;$y+=8)imagefilledrectangle($img,$x,$y,$x+7,$y+7,imagecolorallocate($img,($x+$y)%256,($x*3)%256,($y*5)%256));
$original=$root.'/tmp/real-original1600.webp';$mobile=$root.'/tmp/real-mobile1600.webp';imagewebp($img,$original,100);imagewebp($img,$mobile,100);
$hash=hash_file('sha256',$original);$before=filesize($mobile);
$editor=wp_get_image_editor($original);if($editor->get_quality()!==100)throw new RuntimeException('original quality');
$metadata=irina_mobile_metadata_quality(['sizes'=>['irina-movil'=>['file'=>basename($mobile),'mime-type'=>'image/webp']]],1);
if(hash_file('sha256',$original)!==$hash||empty($metadata['sizes']['irina-movil']['irina_quality_95']))throw new RuntimeException('metadata/original');
clearstatcache(true,$mobile);$dimensions=getimagesize($mobile);$after=filesize($mobile);if($dimensions[0]!==1600||$dimensions[1]!==900||$GLOBALS['last_editor']->get_quality()!==95)throw new RuntimeException('dimensions/codec');
$row=['editor'=>'WP_Image_Editor_GD6.8.3','original1600Quality'=>100,'originalSha256Unchanged'=>true,'derivedQuality'=>95,'derivedBeforeBytes'=>$before,'derivedAfterBytes'=>$after,'derivedDimensions'=>[$dimensions[0],$dimensions[1]],'note'=>'Fixture sintético: q95 no garantiza menor peso en todas las imágenes.'];
file_put_contents($root.'/docs/audit/2026-10-12/image-gd.json',json_encode($row,JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR)."\n");echo json_encode($row)."\n";
