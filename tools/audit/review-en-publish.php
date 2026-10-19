<?php
/** Real publisher/setup in disposable directory; every WP function here is a synthetic stub. */
namespace DraIrina\Core\I18n {
    final class Language {
        public const META = '_di_lang';
        public static function root_id() { return 100; }
        public static function of($id) { return $id < 100 || $GLOBALS['case'] === 'wrong-language' ? 'es' : 'en'; }
        public static function translation($id, $lang) { return $GLOBALS['case'] === 'missing-pair' && $id === 101 ? 0 : 1; }
    }
}
namespace DraIrina\Core\Workflow {
    final class MedicalReview {
        public static function state($id) { return $GLOBALS['case'] === 'no-approvals' ? 'medical_review_required' : 'medically_approved'; }
        public static function is_publishable($id) { return self::state($id) === 'medically_approved'; }
    }
}
namespace {
    final class WP_Error { public function get_error_message() { return 'synthetic failure'; } }
    final class WP_CLI {
        public static function error($s) { throw new \RuntimeException($s); }
        public static function log($s) { $GLOBALS['messages'][] = $s; }
        public static function warning($s) { $GLOBALS['messages'][] = $s; }
        public static function success($s) { $GLOBALS['success'][] = $s; }
    }
    function is_wp_error($v) { return $v instanceof WP_Error; }
    function get_page_by_path($path) { $id = $GLOBALS['paths'][$path] ?? 0; return $id ? (object)['ID'=>$id] : null; }
    function get_post_status($id) { return $id < 100 ? 'publish' : ($GLOBALS['statuses'][$id] ?? false); }
    function get_post_type($id) { return $id < 200 ? 'page' : $GLOBALS['types'][$id]; }
    function get_post_meta($id, $key, $single = true) { return $GLOBALS['case'] === 'no-approvals' ? '' : '2026-10-19 10:00:00'; }
    function get_posts($query) { $id = $GLOBALS['slugs'][$query['post_name__in'][0]] ?? 0; return $id ? [(object)['ID'=>$id]] : []; }
    function get_permalink($id) { return 'https://example.test/fixture/' . $id; }
    function wp_insert_post($data, $errors = false) { return new WP_Error(); }
    function wp_update_post($data, $errors = false) {
        $GLOBALS['writes'][] = $data;
        $n = count($GLOBALS['writes']);
        $id = $data['ID'];
        $case = $GLOBALS['case'];
        if ($case === 'root-error' && $id === 100) return new WP_Error();
        if ($data['post_status'] !== 'publish' && $case === 'rollback-fail' && $id === 100) return new WP_Error();
        if ($n === 3 && in_array($case, ['write-error','rollback-fail','zero-return','silent-block','throw'], true)) {
            if ($case === 'throw') throw new \RuntimeException('synthetic hook failure');
            if ($case === 'silent-block') { $GLOBALS['statuses'][$id] = 'pending'; return $id; }
            return $case === 'zero-return' ? 0 : new WP_Error();
        }
        $GLOBALS['statuses'][$id] = $data['post_status'];
        return $id;
    }
    $case = getenv('AUDIT_CASE');
    $script = getenv('AUDIT_PUBLISH_PATH');
    $pages = json_decode(file_get_contents(dirname($script) . '/pages.json'), true);
    $fichas = json_decode(file_get_contents(dirname($script) . '/fichas-en.json'), true);
    $paths = $slugs = $statuses = $types = $messages = $success = $writes = [];
    $n = 100;
    foreach ($pages ?: [] as $slug=>$def) {
        $paths[$slug === 'en' ? 'en' : 'en/'.$slug] = $n;
        $statuses[$n++] = 'draft';
    }
    $n = 200;
    foreach ($fichas ?: [] as $f) {
        $slugs[$f['slug']] = $n;
        $types[$n] = $f['post_type'];
        $statuses[$n++] = 'draft';
    }
    if ($case === 'missing-page') unset($paths['en/faq']);
    if ($case === 'idempotent') $statuses = array_map(static fn()=>'publish', $statuses);
    $before = $statuses;
    $args = in_array($case, ['dry-run','no-approvals-dry'], true) ? ['legal-ok'] : ['yes','legal-ok'];
    if ($case === 'no-legal') $args = ['yes'];
    if ($case === 'unknown') $args[] = 'unexpected';
    if ($case === 'no-approvals-dry') $case = 'no-approvals';
    $error = null;
    try { require $script; } catch (\Throwable $e) { $error = $e->getMessage(); }
    echo json_encode(['case'=>$case,'error'=>$error,'writes'=>$writes,'statuses'=>$statuses,'unchanged'=>$before===$statuses,'success'=>$success,'messages'=>$messages], JSON_PRETTY_PRINT)."\n";
}
