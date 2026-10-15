<?php
/** Route-permission fixture; never executes mutation callbacks or reads real options. */
declare(strict_types=1);
require __DIR__.'/../../wp-content/plugins/dra-irina-core/src/Ops/Endpoints.php';
require __DIR__.'/../../wp-content/plugins/dra-irina-core/src/Settings/PracticeSettings.php';
$routes=[];$admin=false;
function register_rest_route($namespace,$route,$args){global $routes;foreach(isset($args['methods'])?[$args]:$args as $a)$routes[]=['route'=>$namespace.$route,'args'=>$a];}
function current_user_can($cap){global $admin;return $admin&&$cap==='manage_options';}
function __return_true(){return true;}
(new DraIrina\Core\Ops\Endpoints())->routes();
(new DraIrina\Core\Settings\PracticeSettings())->rest();
$rows=[];foreach($routes as $r){$fn=$r['args']['permission_callback'];$public=$r['args']['methods']==='GET'&&$r['route']==='dra-irina/v1/practice';$admin=false;$anon=$fn();$admin=true;$authorized=$fn();$pass=$anon===$public&&$authorized===true;$rows[]=['route'=>$r['route'],'method'=>$r['args']['methods'],'anonymous'=>$anon,'administrator'=>$authorized,'pass'=>$pass];if(!$pass)throw new RuntimeException('Permission regression');}
file_put_contents(__DIR__.'/../../docs/audit/2026-10-14b/core.json',json_encode(['routes'=>$rows,'limits'=>'Stubbed capabilities and registration; callbacks never invoked; not WordPress authentication integration.'],JSON_PRETTY_PRINT)."\n");
echo 'REST permissions: '.count($rows)."/".count($rows)." pass\n";
