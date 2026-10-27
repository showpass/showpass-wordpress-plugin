=== Showpass Wordpress Extension ===
Tags: showpass, events, tickets, sell tickets, event calendar, purchase tickets, custom event pages
Requires at least: 4.9
Tested up to: 6.8.2
Stable tag: 4.1.0
Requires PHP: 5.4.45
Contributors: marcshowpass, spapril, spzachary, cgarrovillosp, faghihy

List events, display event details and products. Use the Showpass purchase widget seamless purchases with no redirects, all with easy to use shortcodes.

== Description ==

List events, display event details and products. Use the Showpass purchase widget seamless purchases with no redirects, all with easy to use shortcodes.

Showpass is a full event management solution, allowing organizations and individuals to organize and execute successful events.

== Calendar shortcode attributes ==

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

The new options require the corresponding calendar frontend and rebuilt SDK to be deployed before releasing this plugin update.

Whether you are selling tickets to your fundraiser, or an assigned seating theatre show, Showpass has the technology you need to get the job done.

Wordpress version 5.3 is required for editor blocks

= Plugin / Theme Support =

For plugin and theme support please email us at wordpress-support@showpass.com

= Special Features =
* No coding required, use the included default templates and shortcodes to list your events and sell tickets
* List and grid views for events and products
* Our integrated shopping experience keeps your brand in the forefront, no redirecting to complete purchases

= Feedback =
* We are open for your suggestions and feedback
* Drop us a line [@showpassevents](http://twitter.com/#!/showpassevents) on Twitter
* Follow us on [our Facebook page](http://www.facebook.com/Showpass)

== Installation ==

1. Upload the entire `showpass` folder to the `/wp-content/plugins/` directory
2. Register your organization with Showpass at [https://www.showpass.com/organizations/register/](https://www.showpass.com/organizations/register/)
3. Find your Organization ID at [https://www.showpass.com/dashboard/venues/edit/](https://www.showpass.com/dashboard/venues/edit/) in the top right corner
4. Enter in your organization ID in the Showpass API section of your Wordpress dashboard
5. Visit our git repo for full documentation [https://github.com/showpass/showpass-wordpress-plugin](https://github.com/showpass/showpass-wordpress-plugin)
6. We highly recommend you enable an SSL certificate on your Wordpress installation for your customers piece of mind

== Frequently Asked Questions ==

= Where can I get support for using the Showpass platform =
Please visit our support documentation at [http://support.showpass.com](http://support.showpass.com]

== Screenshots ==

1. Easily create an event calendar on any page or post
2. Easily create event or product listings in a grid or list view, on any page or post
3. Create event detail page to provide more information to customers
4. This plugin utilizes the Showpass Purchase Widget for a seamless checkout experience - no redirects means higher conversion rates

== Parameter naming ==

Use snake_case option names in WordPress shortcodes, public SDK objects and widget URLs. Existing camelCase SDK calendar names and dashed shared options remain compatibility aliases. Explicit snake_case values take precedence, including false. WordPress shortcodes use quoted values and comma-separated special-event keys.

This update requires the SPW-20291 frontend and rebuilt SDK release before publishing the plugin. Existing plugins and integration names continue to work with that SDK. Use the naming and migration guide at https://dev.showpass.com/sdk/parameter-naming after it is published.

== Changelog ==

= 4.1.0 =
* Refresh the Showpass settings page with clearer instructions, accessible labels, and save confirmations.
* Simplify developer environment selection while preserving existing settings.
* Add Showpass branding and update documentation and support links.

= 4.0.10 =
* Align outgoing SDK option names with snake_case shortcode attributes and widget URL parameters.
* Accept compatible shortcode aliases and shared options while preserving explicit false values.
* Requires the matching frontend and rebuilt SDK release before publication.

= 4.0.9 =
* Expose current calendar SDK options through the modal and embedded calendar shortcodes
* Restore event and membership card image widgets, including events with empty external links and keyboard access to membership images; preserve external URLs, detail pages, and sold-out behavior

= 4.0.7 =
* Force redirect-url param for Affirm support

= 4.0.6 =
* Add French language support for widgets

= 4.0.3 = 
* Fix analytics tracking for embedded widgets

= 4.0.2 =
* Fix file caching

= 4.0.1 =
* Added membership list shortcode

= 4.0.0 =
* Update to new SDK
* Added membership widget

= 3.1.1 =
* Update to use native wp_remote_get() for api calls
* fix to product list template

 == Upgrade Notice ==

For all previous releases please visit our git repo https://github.com/showpass/showpass-wordpress-plugin/releases

== Translations ==

* English
