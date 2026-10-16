<?php
/** Template guards and real analytics code, synthetic settings, no WordPress/accounts/network. */
declare(strict_types=1);
$root = dirname(__DIR__, 2);
function irina_practice($key) { return $GLOBALS['settings'][$key] ?? ''; }
function __($s, $domain = '') { return $s; }
function esc_attr($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function esc_html($s) { return esc_attr($s); }
function esc_url($s) { return esc_attr($s); }
function esc_attr_e($s, $domain = '') { echo esc_attr($s); }
function esc_html_e($s, $domain = '') { echo esc_html($s); }
function wp_parse_args($args, $defaults) { return array_merge($defaults, $args); }
function wp_kses($s, $allowed) { return strip_tags($s, '<em><b><br>'); }
function wp_json_encode($s) { return json_encode($s, JSON_THROW_ON_ERROR); }
function is_user_logged_in() { return false; }
function current_user_can($cap) { return false; }
function wp_print_inline_script_tag($s, $attrs) { $GLOBALS['analytics'] = $s; }
function get_option($key, $default = []) { return ['ga4_id'=>'G-TEST000001']; }
$valid = ['doctoralia_opiniones'=>true, 'doctoralia_url'=>'https://www.doctoralia.com.mx/synthetic-profile', 'doctoralia_widget_id'=>'synthetic-doctor'];
$cases = [];
foreach (['off','missingId','missingProfile','valid'] as $case) {
    $GLOBALS['settings'] = $valid;
    if ($case === 'off') { $GLOBALS['settings']['doctoralia_opiniones'] = false; }
    if ($case === 'missingId') { $GLOBALS['settings']['doctoralia_widget_id'] = ''; }
    if ($case === 'missingProfile') { $GLOBALS['settings']['doctoralia_url'] = ''; }
    $args = ['text'=>'Opiniones publicadas en Doctoralia. Son experiencias personales y no garantizan resultados.'];
    ob_start();
    require $root . '/wp-content/themes/irina-gonzalez/templates/parts/blocks/opiniones.php';
    $html = ob_get_clean();
    $pass = $case === 'valid' ? str_contains($html, 'data-di-doctoralia-load hidden') && !str_contains($html, '<script') : $html === '';
    if (!$pass) { throw new RuntimeException('Template guard: ' . $case); }
    $cases[$case] = $pass;
    if ($case === 'valid') { file_put_contents($root . '/tmp/doctoralia-fixture.html', $html); }
}
require $root . '/wp-content/plugins/dra-irina-core/src/Settings/PracticeSettings.php';
require $root . '/wp-content/plugins/dra-irina-core/src/Tracking/Events.php';
(new DraIrina\Core\Tracking\Events())->inline_script();
file_put_contents($root . '/tmp/doctoralia-events.js', $GLOBALS['analytics']);
$out = $root . '/docs/audit/2026-10-16c';
if (!is_dir($out)) { mkdir($out, 0777, true); }
file_put_contents($out . '/template.json', json_encode(['cases'=>$cases, 'limits'=>'Synthetic settings and WP helpers; no real options, account, widget id, publication or script execution.'], JSON_PRETTY_PRINT) . "\n");
echo "Doctoralia template: 4/4 guards pass; actual analytics exported to synthetic harness.\n";
