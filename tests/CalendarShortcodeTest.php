<?php

if (!function_exists('showpass_option_is_enabled')) {
  function showpass_option_is_enabled($option) { return false; }
  function get_option($option) {
    return isset($GLOBALS['calendar_test_options'][$option])
      ? $GLOBALS['calendar_test_options'][$option]
      : null;
  }
  function add_shortcode($name, $callback) {}
  function is_admin() { return false; }
  function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
  function sanitize_text_field($value) { return trim(strip_tags($value)); }
  function add_action() {}
}

$GLOBALS['calendar_test_options'] = array('option_organization_id' => '42');
$_SERVER['REQUEST_URI'] = '/calendar';
require_once __DIR__ . '/../plugin/showpass-wordpress-plugin-shortcode.php';

class CalendarShortcodeTest extends PHPUnit\Framework\TestCase
{
  public function testSpecialEventsOpenStateAndAllSelection()
  {
    foreach (array('wpshp_calendar_widget', 'wpshp_embed_calendar') as $shortcode) {
      $html = $shortcode(array('calendar_open_state' => ' special-events ', 'calendar_special_event' => 'all'));
      $this->assertContains('data-calendar-open-state="special-events"', $html);
      $this->assertContains('data-calendar-special-event="all"', $html);
      $this->assertContains('data-calendar-open-state="calendar"', $shortcode(array('calendar_open_state' => 'calendar')));
      foreach (array('invalid', '', array('special-events')) as $invalid) {
        $this->assertNotContains('data-calendar-open-state', $shortcode(array('calendar_open_state' => $invalid)));
      }
    }
  }

  public function testCalendarShortcodesExposeAllSdkOptions()
  {
    $attributes = array(
      'calendar_date' => '2026-09-14',
      'calendar_timeslot' => 'evening',
      'calendar_special_event' => 'opening, closing',
      'display_price_patron_type_id' => '17',
      'hide_price' => 'true',
      'highlight_multi_day_events_on_start_date_only' => 'true',
    );

    $modal = wpshp_calendar_widget($attributes);
    $embedded = wpshp_embed_calendar($attributes);

    foreach (array($modal, $embedded) as $html) {
      $this->assertContains('data-calendar-date="2026-09-14"', $html);
      $this->assertContains('data-calendar-timeslot="evening"', $html);
      $this->assertContains('data-calendar-special-event="opening,closing"', $html);
      $this->assertContains('data-display-price-patron-type-id="17"', $html);
      $this->assertContains('data-hide-price="true"', $html);
      $this->assertContains('data-highlight-multi-day-events-on-start-date-only="true"', $html);
    }
  }

  public function testCalendarShortcodesPreserveExistingMarkupWhenOptionsAreOmitted()
  {
    $this->assertSame(
      '<span data-tags="" data-org-id="42" data-is-attraction="" data-event-id="" class="showpass-button open-calendar-widget" href="#">Get Tickets</span>',
      wpshp_calendar_widget(array())
    );
    $this->assertSame(
      '<div id="showpass-calendar-widget" data-org-id="42" data-tags="" data-is-attraction="" data-event-id=""></div>',
      wpshp_embed_calendar(array())
    );
  }

  public function testCalendarShortcodesOmitMalformedTypedOptions()
  {
    $html = wpshp_calendar_widget(array(
      'calendar_date' => '',
      'calendar_timeslot' => array('invalid'),
      'calendar_special_event' => ', ,',
      'display_price_patron_type_id' => '0',
      'hide_price' => 'sometimes',
      'highlight_multi_day_events_on_start_date_only' => 'sometimes',
    ));

    $this->assertNotContains('data-calendar-date', $html);
    $this->assertNotContains('data-calendar-timeslot', $html);
    $this->assertNotContains('data-calendar-special-event', $html);
    $this->assertNotContains('data-display-price-patron-type-id', $html);
    $this->assertNotContains('data-hide-price', $html);
    $this->assertNotContains('data-highlight-multi-day-events-on-start-date-only', $html);
  }

  public function testCalendarShortcodesPreserveExplicitFalseAndEscapeValues()
  {
    $html = wpshp_embed_calendar(array(
      'hide_price' => 'false',
      'calendar_special_event' => 'one&two',
    ));

    $this->assertContains('data-hide-price="false"', $html);
    $this->assertContains('data-calendar-special-event="one&amp;two"', $html);
  }
}
