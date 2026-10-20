<?php
// Run without PHPUnit so template regressions are checked before the legacy suite.
function showpass_option_is_enabled($option) { return false; }
function get_option($option) { return null; }
function add_shortcode($name, $callback) {}
function is_admin() { return false; }
function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function add_action() {}
function plugin_dir_url($file) { return '/plugin/'; }

$_SERVER['REQUEST_URI'] = '/events';
$_GET = array();
require_once __DIR__ . '/../plugin/showpass-wordpress-plugin-shortcode.php';

function check_image($condition, $message) {
  if (!$condition) {
    throw new RuntimeException($message);
  }
}

function render_card($template, $item, $detail_page = null) {
  $data = json_encode(array('count' => 1, 'results' => array($item), 'num_pages' => 1, 'tracking_id' => 'campaign'));
  $show_widget_description = 'true';
  $lang = 'fr';
  ob_start();
  include __DIR__ . '/../plugin/inc/' . $template;
  $html = ob_get_clean();
  $document = new DOMDocument();
  // HTML5 layout tags are irrelevant to the links under test.
  @$document->loadHTML($html);
  return new DOMXPath($document);
}

$event = array(
  'id' => 123, 'slug' => 'test-event', 'name' => 'Test Event',
  'external_link' => null, 'inventory_sold_out' => false, 'sold_out' => false,
  'is_recurring_parent' => false, 'date_time_to_be_determined' => false,
  'starts_on' => '2026-10-19T18:00:00Z', 'ends_on' => '2026-10-19T20:00:00Z',
  'timezone' => 'America/Edmonton', 'currency' => 'CAD', 'location' => array('name' => 'Test Venue'),
  'ticket_types' => array(array('price' => 10)),
  'initiate_purchase_button' => 'ipbd_buy_tickets',
);

foreach (array('default-grid.php', 'default-list.php') as $template) {
  foreach (array(null, '') as $external_link) {
    $event['external_link'] = $external_link;
    $xpath = render_card($template, $event);
    $images = $xpath->query('//a[contains(concat(" ", normalize-space(@class), " "), " showpass-image ")]');
    check_image($images->length === ($template === 'default-list.php' ? 2 : 1), $template . ': image count');
    foreach ($images as $image) {
      check_image(strpos($image->getAttribute('class'), 'open-ticket-widget') !== false, $template . ': empty external link must open widget');
      foreach (array('id' => 'test-event', 'data-tracking' => 'campaign', 'data-show-description' => 'true', 'data-lang' => 'fr') as $name => $value) {
        check_image($image->getAttribute($name) === $value, $template . ': ' . $name);
      }
    }
  }

  foreach (array('external', 'detail', 'sold-out') as $scenario) {
    $item = $event;
    $item['external_link'] = $scenario === 'external' ? 'https://example.com/event' : null;
    $item['sold_out'] = $scenario === 'sold-out';
    // External destinations take precedence even when a detail page is configured.
    $detail_page = $scenario === 'sold-out' ? null : 'event-details';
    $xpath = render_card($template, $item, $detail_page);
    foreach ($xpath->query('//a[contains(concat(" ", normalize-space(@class), " "), " showpass-image ")]') as $image) {
      check_image(strpos($image->getAttribute('class'), 'open-ticket-widget') === false, $template . ': ' . $scenario . ' must not open widget');
      $expected = $scenario === 'external' ? 'https://example.com/event' : ($scenario === 'detail' ? '/event-details/?slug=test-event' : 'javascript:void(0);');
      check_image($image->getAttribute('href') === $expected, $template . ': ' . $scenario . ' destination');
    }
  }
}

$membership = array('id' => 456, 'name' => 'Test Membership', 'membership_levels' => array(array('price' => 10)), 'currency' => 'CAD');
$xpath = render_card('default-membership-grid.php', $membership);
$image = $xpath->query('//a[contains(concat(" ", normalize-space(@class), " "), " showpass-image ")]')->item(0);
check_image($image !== null, 'membership image exists');
check_image($image->getAttribute('href') === '#', 'membership image remains keyboard accessible');
check_image(strpos($image->getAttribute('class'), 'open-membership-widget') !== false, 'membership image opens membership widget');
foreach (array('id' => '456', 'data-show-description' => 'true', 'data-lang' => 'fr') as $name => $value) {
  check_image($image->getAttribute($name) === $value, 'membership: ' . $name);
}

// Existing product image widget bindings must continue to render.
$product = array('id' => 789, 'name' => 'Test Product', 'product_attributes' => array(array('price' => 10)), 'currency' => 'CAD', 'is_public' => true);
foreach (array('default-product-grid.php', 'default-product-list.php') as $template) {
  $xpath = render_card($template, $product);
  $image = $xpath->query('//a[contains(concat(" ", normalize-space(@class), " "), " showpass-image ")]')->item(0);
  check_image($image !== null && strpos($image->getAttribute('class'), 'open-product-widget') !== false, $template . ': product image widget');
}

echo "Rendered card image widget tests passed.\n";
