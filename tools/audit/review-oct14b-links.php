<?php
/** Isolated canonical-link fixture; no WordPress/network/DB. */
declare(strict_types=1);
require __DIR__.'/../../wp-content/themes/irina-gonzalez/inc/template-tags.php';
$seen=[];
function wp_parse_url($u,$component){return parse_url($u,$component);}
function home_url(){return 'https://audit.invalid/';}
function get_posts($args){
 global $seen;$seen[]=$args;
 $records=[['slug'=>'sueno','type'=>'page','status'=>'publish','area'=>''],['slug'=>'cpap','type'=>'tratamiento','status'=>'publish','area'=>'sueno'],['slug'=>'apnea','type'=>'condicion','status'=>'publish','area'=>'sueno'],['slug'=>'draft','type'=>'tratamiento','status'=>'draft','area'=>'sueno'],['slug'=>'otitis','type'=>'condicion','status'=>'publish','area'=>'orl']];
 return array_values(array_filter($records,fn($r)=>in_array($r['type'],(array)$args['post_type'],true)&&in_array($r['slug'],$args['post_name__in'],true)&&$r['status']===$args['post_status']&&(!isset($args['tax_query'])||$r['area']===$args['tax_query'][0]['terms'])));
}
function get_permalink($r){return 'https://audit.invalid/'.($r['type']==='page'?'':'sueno/').$r['slug'].'/';}
$cases=['/sueno/'=>'https://audit.invalid/sueno/','/sueno/cpap/#riesgos'=>'https://audit.invalid/sueno/cpap/#riesgos','/sueno/apnea/'=>'https://audit.invalid/sueno/apnea/','/sueno/draft/'=>'','/sueno/otitis/'=>'','/tratamientos/cpap/'=>'https://audit.invalid/sueno/cpap/','https://other.invalid/sueno/cpap/'=>'https://other.invalid/sueno/cpap/'];
$rows=[];foreach($cases as $input=>$expected){$got=irina_url_if_published($input);$rows[]=['input'=>$input,'pass'=>$got===$expected];if($got!==$expected)throw new RuntimeException('Link fixture failed');}
file_put_contents(__DIR__.'/../../docs/audit/2026-10-14b/links.json',json_encode(['rows'=>$rows,'limits'=>'Stubbed get_posts and taxonomy; no real WP integration.'],JSON_PRETTY_PRINT)."\n");
echo "Canonical links: 7/7 pass\n";
