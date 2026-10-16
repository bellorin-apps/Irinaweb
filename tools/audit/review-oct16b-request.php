<?php
/** Actual request filters with stubbed auth/admin; no WordPress or credentials. */
declare(strict_types=1);
$callbacks=[];$admin=false;$logged=false;
function add_action(...$args){}
function add_filter($name,$callback,...$args){global $callbacks;if($name==='request')$callbacks[]=$callback;}
function is_admin(){global $admin;return $admin;}
function is_user_logged_in(){global $logged;return $logged;}
require __DIR__.'/../../wp-content/plugins/dra-irina-core/src/Security/Hardening.php';
(new DraIrina\Core\Security\Hardening())->register();
require __DIR__.'/../../wp-content/themes/irina-gonzalez/inc/setup.php';
$cases=[
 ['author',false,false,['author'=>1],['error'=>'404']],
 ['author_name',false,false,['author_name'=>'fixture'],['error'=>'404']],
 ['admin_author',true,true,['author'=>1],['author'=>1]],
 ['logged_author',false,true,['author'=>1],['author'=>1]],
 ['admin_no_session',true,false,['post_type'=>'condicion'],['post_type'=>'condicion']],
 ['condition_archive',false,false,['post_type'=>'condicion'],['error'=>'404']],
 ['treatment_archive',false,false,['post_type'=>'tratamiento'],['error'=>'404']],
 ['resource_archive',false,false,['post_type'=>'recurso'],['error'=>'404']],
 ['page',false,false,['post_type'=>'page'],['post_type'=>'page']],
 ['single_slug',false,false,['post_type'=>'condicion','name'=>'fixture'],['post_type'=>'condicion','name'=>'fixture']],
 ['single_id',false,false,['post_type'=>'tratamiento','p'=>42],['post_type'=>'tratamiento','p'=>42]],
 ['preview',false,true,['post_type'=>'condicion','preview'=>true,'p'=>42],['post_type'=>'condicion','preview'=>true,'p'=>42]],
 ['sleep',false,false,['post_type'=>'tratamiento','di_sueno_slug'=>'cpap'],['post_type'=>'tratamiento','di_sueno_slug'=>'cpap']],
 ['front',false,false,[],[]],
];
$rows=[];foreach($cases as [$name,$a,$l,$input,$expected]){$admin=$a;$logged=$l;$actual=$input;foreach($callbacks as $callback)$actual=$callback($actual);$pass=$actual===$expected;$rows[]=['case'=>$name,'pass'=>$pass];if(!$pass)throw new RuntimeException('Request filter failure: '.$name);}
file_put_contents(__DIR__.'/../../docs/audit/2026-10-16b/request.json',json_encode(['rows'=>$rows,'limits'=>'Real callback code, synthetic query vars/auth flags; no authenticated WP browser or DB.'],JSON_PRETTY_PRINT)."\n");
echo count($rows)." request cases pass\n";
