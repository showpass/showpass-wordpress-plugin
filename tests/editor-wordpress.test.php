<?php
// Run through WP-CLI eval-file on a local test site. No API calls or database writes.
if (!defined('ABSPATH') || !function_exists('do_shortcode')) {
    throw new RuntimeException('Run this test with wp --skip-plugins --skip-themes eval-file tests/editor-wordpress.test.php');
}
// Keep WordPress's shutdown cron outside this focused rendering check.
remove_action('shutdown', '_wp_cron');
function showpass_option_is_enabled($name) { return get_option($name) === 'true'; }
add_filter('pre_option_option_use_showpass_local', function () { return 'true'; });
add_filter('pre_option_option_showpass_default_button_class', function () { return 'test-button'; });
add_filter('pre_http_request', function () { throw new RuntimeException('This test must not make HTTP requests'); });
$_SERVER['REQUEST_URI'] = '/editor-wordpress-test';
require_once __DIR__ . '/../plugin/showpass-wordpress-plugin-shortcode.php';
add_shortcode('showpass_widget', 'showpass_widget_expand');
$labels = array('Buy [VIP]', 'Buy [[VIP]]', 'A "label"', 'Friends & family <VIP> [Friday]', "Tonight's tickets");
foreach ($labels as $label) {
    $shortcode = generate_showpass_buy_now_button_shortcode('regression-event', $label);
    $html = do_shortcode($shortcode);
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="UTF-8"><div>' . $html . '</div>');
    $buttons = $doc->getElementsByTagName('a');
    if ($buttons->length !== 1 || $buttons->item(0)->getAttribute('id') !== 'regression-event') {
        throw new RuntimeException('Expected exactly one event button for: ' . $label);
    }
    if (trim($doc->textContent) !== $label) {
        throw new RuntimeException('Rendered label changed or left extra page text for: ' . $label . '; got: ' . trim($doc->textContent));
    }
    if ($doc->getElementsByTagName('script')->length !== 0 || $doc->getElementsByTagName('vip')->length !== 0) {
        throw new RuntimeException('Label became HTML markup');
    }
}
echo "WordPress shortcode rendering passed for brackets, quotes, ampersands and HTML-like labels.\n";
