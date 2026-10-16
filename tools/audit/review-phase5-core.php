<?php
/** Registration fixture: real core classes, stub WordPress, no storage or network. */
declare(strict_types=1);
$root = dirname(__DIR__, 2);
spl_autoload_register(static function ($class) use ($root) {
    $prefix = 'DraIrina\\Core\\';
    if (str_starts_with($class, $prefix)) {
        require $root . '/wp-content/plugins/dra-irina-core/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    }
});
$types = $taxonomies = $metas = [];
function __($text, $domain = '') { return $text; }
function register_post_type($slug, $args) { $GLOBALS['types'][$slug] = $args; }
function register_taxonomy($slug, $objects, $args) { $GLOBALS['taxonomies'][$slug] = ['objects'=>$objects, 'args'=>$args]; }
function register_post_meta($type, $key, $args) { $GLOBALS['metas'][$type][$key] = $args; }
function current_user_can($cap, ...$args) { return $GLOBALS['allowed'] && $cap === 'edit_post' && $args === [123]; }
foreach (['Condicion','Tratamiento','Recurso','Credencial'] as $name) {
    $class = 'DraIrina\\Core\\PostTypes\\' . $name;
    (new $class())->register_post_type();
}
foreach (['Area','Zona','EstadoMedico'] as $name) {
    $class = 'DraIrina\\Core\\Taxonomies\\' . $name;
    (new $class())->register_taxonomy();
}
(new DraIrina\Core\Fields\MetaRegistry())->register_meta();
$checks = [];
$checks['fourTypes'] = count($types) === 4;
$checks['threeTaxonomies'] = count($taxonomies) === 3;
$checks['menuAndRest'] = !array_filter($types, static fn($a) => !$a['show_in_rest'] || $a['show_in_menu'] !== 'dra-irina-practice');
$checks['medicalTaxonomyGate'] = count(array_unique($taxonomies['estado_medico']['args']['capabilities'])) === 1
    && $taxonomies['estado_medico']['args']['capabilities']['assign_terms'] === 'approve_medical_content';
$count = 0;
foreach ($metas as $type => $fields) {
    foreach ($fields as $key => $args) {
        $count++;
        if (!str_starts_with($key, 'di_') || !isset($args['show_in_rest']['schema']) || !is_callable($args['sanitize_callback'])) {
            throw new RuntimeException('Invalid meta registration');
        }
        $GLOBALS['allowed'] = false;
        if ($args['auth_callback'](false, $key, 123)) { throw new RuntimeException('Anonymous edit allowed'); }
        $GLOBALS['allowed'] = true;
        if (!$args['auth_callback'](true, $key, 123)) { throw new RuntimeException('Authorized edit denied'); }
    }
}
$checks['registeredMetaSchemaAndPermissions'] = $count > 0;
if (in_array(false, $checks, true)) { throw new RuntimeException('Core registration regression'); }
file_put_contents($root . '/docs/audit/2026-10-16-phase5/registration.json', json_encode([
    'checks'=>$checks, 'types'=>array_keys($types), 'taxonomies'=>array_keys($taxonomies), 'metas'=>$count,
    'limits'=>'Stubbed registration and capabilities, no WP database, role mutation, editor, activation or publication.'
], JSON_PRETTY_PRINT) . "\n");
echo "Core registration: 4 CPT, 3 taxonomies, $count meta schemas/auth callbacks pass.\n";
