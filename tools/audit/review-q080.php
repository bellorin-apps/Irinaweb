<?php
/** Synthetic execution of Q-080 callbacks; no WordPress, private configuration or network. */
namespace DraIrina\Core\I18n {
    final class Language {
        public static string $lang = 'en';
        public static function current(): string { return self::$lang; }
    }
}
namespace DraIrina\Core\Settings {
    final class PracticeSettings {
        public static function get($key): string { return 'Dr. Irina González Sáez'; }
    }
}
namespace {
    $filters = [];
    $is404 = true;
    function add_action(...$args): void {}
    function add_filter($name, $cb, ...$args): void { $GLOBALS['filters'][$name] = $cb; }
    function is_404(): bool { return $GLOBALS['is404']; }
    function get_bloginfo($name): string { return 'Dra. Irina González Sáez'; }
    function wp_specialchars_decode($s, $flags): string { return html_entity_decode($s, $flags); }
    require dirname(__DIR__, 2) . '/wp-content/themes/irina-gonzalez/inc/i18n.php';
    $cases = [];
    $check = static function ($name, $actual, $expected) use (&$cases): void {
        if ($actual !== $expected) throw new \RuntimeException($name);
        $cases[] = $name;
    };
    $check('EN 404 brand', $filters['rank_math/frontend/title']('original'), 'Page not found | Dr. Irina González Sáez');
    $check('EN photo alt', $filters['wp_get_attachment_image_attributes'](['alt'=>'Dra. Irina González Sáez — Primera consulta']), ['alt'=>'Dr. Irina González Sáez — First visit']);
    $check('empty alt remains decorative', irina_alt_en(''), '');
    $check('unknown alt unchanged', irina_alt_en('Unmapped original'), 'Unmapped original');
    $check('already English unchanged', irina_alt_en('First visit'), 'First visit');
    $check('missing alt unchanged', $filters['wp_get_attachment_image_attributes'](['src'=>'x']), ['src'=>'x']);
    foreach (['rank_math/opengraph/facebook/og_image_alt','rank_math/opengraph/twitter/twitter_image_alt'] as $key) {
        $check($key.' EN', $filters[$key]('Ronquido y apnea del sueño'), 'Snoring and sleep apnea');
        $check($key.' non-string', $filters[$key](false), false);
    }
    $is404 = false;
    $check('EN ordinary title unchanged', $filters['rank_math/frontend/title']('original'), 'original');
    \DraIrina\Core\I18n\Language::$lang = 'es';
    $is404 = true;
    $check('ES 404 unchanged', $filters['rank_math/frontend/title']('original ES'), 'original ES');
    $check('ES photo unchanged', $filters['wp_get_attachment_image_attributes'](['alt'=>'Ronquido y apnea del sueño']), ['alt'=>'Ronquido y apnea del sueño']);
    $check('ES OG unchanged', $filters['rank_math/opengraph/facebook/og_image_alt']('Ronquido y apnea del sueño'), 'Ronquido y apnea del sueño');
    echo json_encode(['pass'=>count($cases),'cases'=>$cases,'limits'=>'Synthetic callbacks; real Rank Math/filter integration attested by local.log.'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n";
}
