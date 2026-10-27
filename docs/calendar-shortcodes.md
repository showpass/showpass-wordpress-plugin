# Calendar shortcodes

Developer reference for modal and embedded calendars. For installation and additional examples, see the [WordPress guide](https://dev.showpass.com/wordpress/getting-started-install-and-configure).

## Attributes


Both `[showpass_calendar_widget]` and `[showpass_embed_calendar]` accept these optional attributes:

* `display_price_patron_type_id` - a positive venue-local PatronType ID. This changes displayed prices only and never filters availability, calendar contents, or ticket choices.
* `hide_price` - `true` or `false`. This hides calendar price text and the price-range filter without changing availability or ticket selection.
* `calendar_date` - a venue-local date such as `2026-09-14`.
* `calendar_timeslot` - a public timeslot key used with `calendar_date`.
* `calendar_special_event` - one attraction special-event key, multiple comma-separated keys, or `all` to select every currently filterable special event.
* `calendar_open_state` - `calendar` (default) or `special-events`. Opens the Special Events tab when rich special-event content is available; otherwise keeps the calendar view. This controls the view independently of special-event filtering. When combined with a date/timeslot target, the Special Events view opens first. Any configured quantity prompt still comes first.
* `highlight_multi_day_events_on_start_date_only` - `true` or `false`. Highlights multi-day events only on their local start date.

Example: `[showpass_embed_calendar calendar_date="2026-09-14" calendar_timeslot="evening" calendar_special_event="opening,closing" display_price_patron_type_id="17" hide_price="false"]`

Special Events example: `[showpass_embed_calendar is_attraction="true" event_id="YOUR_ATTRACTION_ID" calendar_special_event="all" calendar_open_state="special-events"]`

## Parameter naming

Use snake_case option names in WordPress shortcodes, public SDK objects, and widget URLs. Existing camelCase SDK calendar names and dashed shared options remain compatibility aliases. Explicit snake_case values take precedence, including false. Use quoted shortcode values and comma-separated special-event keys.

Release prerequisite: deploy the matching SPW-20291 frontend and rebuilt SDK before publishing plugin 4.1.0. See `TESTS.md` for the release order.
