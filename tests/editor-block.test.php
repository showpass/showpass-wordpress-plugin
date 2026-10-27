<?php
// Standalone REST / shortcode contract checks. No HTTP requests are permitted.
define('ABSPATH', __DIR__ . '/');
function get_option($name) { return isset($GLOBALS['editor_test_options'][$name]) ? $GLOBALS['editor_test_options'][$name] : null; }
function showpass_option_is_enabled($name) { return get_option($name) === 'true'; }
function add_action() {}
function add_shortcode() {}
function is_admin() { return false; }
function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_url_raw($value) { return trim($value); }
function wp_parse_url($url, $component = -1) { return parse_url($url, $component); }
function wp_http_validate_url($url) {
  if (parse_url($url, PHP_URL_HOST) === 'localhost.showpass.com') return false;
  return filter_var($url, FILTER_VALIDATE_URL);
}
function wp_send_json_success($data, $status) { return array('success' => true, 'data' => $data, 'status' => $status); }
function wp_send_json_error($data, $status) { return array('success' => false, 'data' => $data, 'status' => $status); }
function rest_ensure_response($data) { return $data; }
function wp_remote_post() { throw new RuntimeException('Offline tests must never make an HTTP request'); }
$_SERVER['REQUEST_URI'] = '/editor-test';
require_once __DIR__ . '/../plugin/showpass-wordpress-plugin-shortcode.php';
require_once __DIR__ . '/../plugin/showpass-wordpress-custom-api.php';
function check_editor($condition, $message) {
  if (!$condition) throw new RuntimeException($message);
}

$GLOBALS['editor_test_options'] = array('option_use_showpass_local' => 'true');
$response = showpass_api_process_url(array(
  'url' => 'https://localhost.showpass.com/local-event/?campaign=one&source=two',
  'label' => 'Local QA & Tickets',
));
check_editor($response['success'] && $response['status'] === 200, 'local URL is accepted in Local mode');
check_editor($response['data']['slug'] === 'local-event', 'URL query is not part of the event slug');
check_editor(strpos($response['data']['shortcode'], 'label="Local QA &amp; Tickets"') !== false, 'custom label is escaped and forwarded');

$response = showpass_api_process_url(array('url' => 'https://localhost.showpass.com/'));
check_editor(!$response['success'] && $response['status'] === 400, 'an event path is required');
$response = showpass_api_process_url(array('url' => 'not-a-url'));
check_editor(!$response['success'] && $response['status'] === 400, 'invalid URL returns an error');

$GLOBALS['editor_test_options'] = array();
$response = showpass_api_process_url(array('url' => 'https://localhost.showpass.com/local-event/'));
check_editor(!$response['success'], 'local validation exception requires Local mode');
$response = showpass_api_process_url(array('url' => 'https://www.showpass.com/offline-contract-event/'));
check_editor($response['success'], 'existing public Showpass URL parsing still works without fetching it');
check_editor(strpos($response['data']['shortcode'], 'label="Get Tickets"') !== false, 'omitting a label preserves the default');

$GLOBALS['editor_test_options']['option_showpass_default_button_class'] = 'button" data-test="value';
$shortcode = generate_showpass_buy_now_button_shortcode('event"slug', 'A "label"');
check_editor(strpos($shortcode, 'slug="event&quot;slug"') !== false, 'slug quotes are escaped');
check_editor(strpos($shortcode, 'label="A &quot;label&quot;"') !== false, 'label quotes are escaped');
check_editor(strpos($shortcode, 'class="button&quot; data-test=&quot;value"') !== false, 'class quotes are escaped');
echo "Editor endpoint and shortcode regression tests passed.\n";
