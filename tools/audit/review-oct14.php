<?php
/** Isolated fixtures: synthetic data only, no WordPress connection, network or real mail. */
declare(strict_types=1);
require __DIR__ . '/../../wp-content/plugins/dra-irina-core/src/Contact/SpamGuard.php';
require __DIR__ . '/../../wp-content/plugins/dra-irina-core/src/Contact/Form.php';
require __DIR__ . '/../../wp-content/plugins/dra-irina-core/src/Security/Hardening.php';
use DraIrina\Core\Contact\SpamGuard;
use DraIrina\Core\Security\Hardening;
$options=[]; $remote=['response'=>['code'=>200],'body'=>'{"success":true}']; $logged=false;
function get_option($k,$d=false){global $options;return $options[$k]??$d;}
function update_option($k,$v,$autoload=null){global $options;$options[$k]=$v;}
function current_time($f){return '2026-10-14 16:00:00';}
function wp_remote_post($url,$args){global $remote;return $remote;}
function is_wp_error($r){return $r instanceof RuntimeException;}
function wp_remote_retrieve_response_code($r){return $r['response']['code'];}
function wp_remote_retrieve_body($r){return $r['body'];}
function is_user_logged_in(){global $logged;return $logged;}
function sanitize_text_field($s){return trim(strip_tags($s));}
function sanitize_textarea_field($s){return trim(strip_tags($s));}
function wp_unslash($s){return $s;}
function wp_parse_url($u,$c){return parse_url($u,$c);}
function home_url($p=''){return 'https://audit.invalid'.$p;}
function __($s,$d=''){return $s;}
function wp_salt($s){return 'synthetic-test-salt';}
function get_transient($k){return false;}
function set_transient($k,$v,$t){return true;}
function sanitize_email($s){return $s;}
function sanitize_key($s){return $s;}
function is_email($s){return filter_var($s,FILTER_VALIDATE_EMAIL)!==false;}
function wp_mail(...$args){throw new RuntimeException('Unexpected mail attempt; blocked by fixture');}
define('HOUR_IN_SECONDS',3600);
$cases=[
 ['patient_es','Quisiera una cita para mi hijo por dolor de oído.',''],
 ['email_es','Mi correo es paciente@example.invalid y la encontré en doctoralia.com.mx.',''],
 ['patient_en','I found your website and would like to know your services and pricing for a sleep study.',''],
 ['patient_en_insurance','Can you let me know the pricing and services covered with our insurance for sleep apnea?',''],
 ['pitch_en','We help your business with our services and offer packages for your website traffic.','commercial_en'],
 ['pitch_es','Somos una agencia de marketing digital y ofrecemos posicionamiento web.','commercial'],
 ['link','Oferta en https://example.invalid','link'],
 ['domain','Encontré a la doctora en doctoralia.com.mx',''],
];
$results=[];$fail=0;
foreach($cases as [$id,$message,$expected]){$actual=SpamGuard::classify('Paciente','patient@example.invalid',$message);$results[]=['id'=>$id,'expected'=>$expected,'actual'=>$actual,'pass'=>$actual===$expected];if($actual!==$expected)$fail++;}
$captcha=[];$captcha['disabled']=!SpamGuard::captcha_enabled();
$options['di_turnstile_sitekey']='synthetic-public';$captcha['one_key_disabled']=!SpamGuard::captcha_enabled();
$options['di_turnstile_secret']='synthetic-secret';$captcha['enabled']=SpamGuard::captcha_enabled();
$captcha['empty_invalid']=SpamGuard::verify('')===false;$captcha['valid']=SpamGuard::verify('synthetic-token')===true;
$remote['body']='{"success":false}';$captcha['invalid']=SpamGuard::verify('synthetic-token')===false;
$remote=new RuntimeException('offline');$captcha['offline_null']=SpamGuard::verify('synthetic-token')===null;
$remote=['response'=>['code'=>503],'body'=>''];$captcha['http_error_null']=SpamGuard::verify('synthetic-token')===null;
SpamGuard::log('commercial');$captcha['log_minimal']=array_keys($options['di_contact_spam'])===['total','by','last','last_reason'];
$hard=new Hardening();$endpoints=['/wp/v2/users'=>[], '/wp/v2/users/(?P<id>[\d]+)'=>[], '/wp/v2/pages'=>[]];
$security=['public_users_removed'=>array_keys($hard->rest_endpoints($endpoints))===['/wp/v2/pages']];
$logged=true;$security['logged_routes_unchanged']=$hard->rest_endpoints($endpoints)===$endpoints;
$security['oembed_no_author']=$hard->oembed(['author_name'=>'synthetic','author_url'=>'https://audit.invalid','title'=>'Page'])===['title'=>'Page'];
$options=[];$_SERVER=['REQUEST_METHOD'=>'POST','HTTP_ORIGIN'=>'https://audit.invalid','REMOTE_ADDR'=>'192.0.2.1'];
$_POST=['di_t'=>(string)(time()-10),'di_name'=>'Paciente','di_phone'=>'8112345678','di_email'=>'','di_motivo'=>'sueno','di_consent'=>'1','di_message'=>'Somos una agencia de marketing digital'];
$process=new ReflectionMethod(DraIrina\Core\Contact\Form::class,'process');
$silent=$process->invoke(new DraIrina\Core\Contact\Form());$security['spam_silent_no_mail']=$silent===['ok'=>true]&&($options['di_contact_spam']['by']['commercial']??0)===1;
$all=['classification'=>$results,'captcha'=>$captcha,'security'=>$security,'limits'=>'Isolated stubs; synthetic tokens, no network, mail or real WordPress.'];
foreach(array_merge($captcha,$security) as $pass)if(!$pass)$fail++;
if(!is_dir(__DIR__.'/../../docs/audit/2026-10-14'))mkdir(__DIR__.'/../../docs/audit/2026-10-14',0777,true);
file_put_contents(__DIR__.'/../../docs/audit/2026-10-14/local.json',json_encode($all,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
echo "Fixtures failures: $fail\n";exit($fail?1:0);
