<?php
function showpass_option_is_enabled($name) { return false; }
function get_option($name) { return $name === 'option_organization_id' ? '42' : null; }
function add_shortcode($name, $callback) {}
function add_action() {}
function is_admin() { return false; }
function esc_html($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
$_SERVER['REQUEST_URI'] = '/local-review';
require_once __DIR__ . '/../plugin/showpass-wordpress-plugin-shortcode.php';
function contains_attribute($html, $attribute) {
  if (strpos($html, $attribute) === false) throw new Exception('Missing attribute: ' . $attribute . '\n' . $html);
}
foreach (array('wpshp_calendar_widget', 'wpshp_embed_calendar') as $render) {
  $html = $render(array('calendarDate' => '2026-11-15', 'hide_price' => 'false', 'hidePrice' => 'true',
    'theme-primary' => 'old', 'theme_primary' => '24727b', 'keep-shopping' => 'true', 'keep_shopping' => 'false',
    'show_description' => 'false', 'tracking_id' => 'campaign', 'lang' => 'fr'));
  foreach (array('data-calendar-date="2026-11-15"', 'data-hide-price="false"',
    'data-theme-primary="24727b"', 'data-keep-shopping="false"', 'data-show-description="false"',
    'data-tracking-id="campaign"', 'data-lang="fr"') as $attribute) contains_attribute($html, $attribute);
  if (strpos($html, 'data-theme-primary="old"') !== false) throw new Exception('Legacy alias overrode canonical attribute');
}
$widget = showpass_widget_expand(array('slug'=>'local-event', 'show_description'=>'false', 'keep_shopping'=>'false', 'theme_primary'=>'24727b'));
contains_attribute($widget, 'data-show-description="false"');
contains_attribute($widget, 'data-keep-shopping="false"');
contains_attribute($widget, 'data-theme-primary="24727b"');
foreach (array('true', 'false') as $embedded) {
  $html = showpass_widget_expand(array('slug' => 'local-event', 'embedded' => $embedded,
    'show_description' => 'false', 'show_widget_description' => 'true', 'show_specific_tickets' => '17,18'));
  foreach (array('data-show-description=', 'data-show-specific-tickets=') as $name) {
    if (substr_count($html, $name) !== 1) throw new Exception('Duplicate attribute: ' . $name);
  }
  contains_attribute($html, 'data-show-description="false"');
}
echo "WordPress shortcode parameter compatibility tests passed.\n";
