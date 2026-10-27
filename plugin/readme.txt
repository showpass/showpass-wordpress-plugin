=== Showpass WordPress Extension ===
Tags: events, tickets, event calendar, memberships, products
Requires at least: 4.9
Tested up to: 7.1
Stable tag: 4.1.0
Requires PHP: 5.4.45
Contributors: marcshowpass, spapril, spzachary, cgarrovillosp, faghihy

Add event calendars, ticket purchases, products, and memberships to your WordPress site with Showpass widgets and shortcodes.

== Description ==

Showpass is an event management and ticketing platform. Connect your Showpass organization to WordPress to display events, calendars, products, and memberships. Customers can select tickets and products through the Showpass purchase widget on your website.

= What you can do =

* Display events and products in a list or grid.
* Show event details, images, dates, and purchase buttons.
* Add an embedded calendar or open a calendar in a purchase widget.
* Display memberships and open the membership purchase widget.
* Add a Showpass Buy Now Button with the WordPress block editor.
* Set a default widget color and use your theme's button CSS classes.

Use shortcodes to place Showpass content in your pages and posts. The Showpass Buy Now Button block requires WordPress 5.3 or later.

= Add a calendar =

Place `[showpass_embed_calendar]` in a Shortcode block to display a calendar on your page. See the [WordPress developer guide](https://dev.showpass.com/wordpress/getting-started-install-and-configure) for setup and shortcode options.

= Getting started =

You need a Showpass organizer account and your Organization ID. Install and activate the plugin, then open **Showpass** in your WordPress dashboard to connect your organization and configure your widgets.

For purchase widgets, use a single Organization ID. Each purchase can include items from only one organization. You can use multiple Organization IDs for supported event and product listings.

[Read the installation and configuration guide](https://dev.showpass.com/wordpress/getting-started-install-and-configure).

= Documentation and support =

For setup instructions and shortcode examples, visit the [Showpass developer documentation](https://dev.showpass.com/wordpress/getting-started-install-and-configure).

For plugin support, email solutions@showpass.com. For help using Showpass, visit the [Showpass Help Centre](https://help.showpass.com/).

= Follow Showpass =

Connect with Showpass on [LinkedIn](https://www.linkedin.com/company/showpass/) and [Instagram](https://www.instagram.com/showpassevents/).

== Installation ==

1. In your WordPress dashboard, open **Plugins > Add New**, search for **Showpass**, and select **Install Now**. If you have a plugin ZIP, select **Upload Plugin** instead.
2. Activate the plugin.
3. Open **Showpass** in the WordPress dashboard.
4. Enter your Organization ID and select **Save Changes**. If you do not have an account, use the organizer account registration link on the settings page.
5. Add a Showpass shortcode to a page or post, or use the **Showpass Buy Now Button** block to add a purchase button.
6. Preview the page and check that the correct events, products, or memberships appear before publishing it.

Use HTTPS on your WordPress website. See the [installation guide](https://dev.showpass.com/wordpress/getting-started-install-and-configure) for configuration details and shortcode examples.

== Frequently Asked Questions ==

= Do I need a Showpass account? =

Yes. You need a Showpass organizer account and an Organization ID. Manage your events, products, and memberships in Showpass, then display them on WordPress.

= What is my Organization ID? =

It identifies your Showpass organization and is also called a venue ID. It is different from an event or product ID. Enter it in **Showpass** settings. If you need help finding it, email solutions@showpass.com.

= Do I need to write code? =

No PHP changes are needed to use the included shortcodes or Showpass Buy Now Button block. Custom layouts and styling may require HTML or CSS knowledge.

= How do I add events, products, or memberships? =

Add a **Shortcode** block to your page and enter `[showpass_events]`, `[showpass_products]`, or `[showpass_memberships]`. Save your Organization ID in Showpass settings first, then preview the page.

= How do I add a purchase button? =

Add the **Showpass Buy Now Button** block, enter the full Showpass event URL, choose a label, and select **Add Button**. The block requires WordPress 5.3 or later.

= Which calendar should I use? =

Use `[showpass_embed_calendar]` to display a calendar directly on the page. Use `[showpass_calendar_widget]` for a button that opens the calendar in a purchase widget. See the documentation for attraction calendars and additional options.

= Can I customize the appearance? =

Yes. In **Showpass** settings, choose a widget color, button CSS classes, closing button text, and whether descriptions appear. Enter hex colors without `#`, such as `1C39BB`. Your theme controls the surrounding page layout.

= Can I display multiple organizations? =

Supported event and product listings accept comma-separated Organization IDs, such as `5, 10, 20`. Use a single Organization ID for purchase widgets: each purchase can contain items from only one organization.

= Can I display widgets in French? =

Yes. Add `lang="fr"` to a supported widget shortcode, for example `[showpass_embed_calendar lang="fr"]`. This changes the widget language, not your theme or plugin settings. Translate any custom button labels yourself.

= Why is my content not appearing? =

Check your Organization ID, shortcode filters, and content availability in Showpass. For live organizations, select **Production** under Developer options. Clear relevant WordPress or hosting caches after changes. If the issue continues, contact plugin support.

= Do I need to change Developer options? =

Usually not. Keep **Production** selected for a live customer website. Local, Beta, and Demo are for development or testing and may require separate data or setup.

= Does deleting the plugin remove my settings? =

No. Saved settings remain in your WordPress database and can reappear after reinstalling. Deleting the plugin does not delete your Showpass account or events.

= Where can I get help? =

See the [setup guide](https://dev.showpass.com/wordpress/getting-started-install-and-configure) or [Showpass Help Centre](https://help.showpass.com/). For plugin support, email solutions@showpass.com with your page URL, shortcode, WordPress version, and plugin version.

== Screenshots ==

1. Display merchandise with product images, prices, and Add to cart buttons on your website.
2. Show event details and descriptions in a branded Showpass purchase widget.
3. Let customers choose product options and quantities in the purchase widget.
4. Connect your organization and configure widget appearance in the WordPress dashboard.

== Changelog ==


= 4.1.0 =
* Verify core plugin flows on WordPress 7.1.3 and update compatibility metadata.
* Fix Buy Now block local URLs, custom labels, saved content and editor asset cache versions.
* Handle membership currency and optional pagination fields from the current API.
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

= 4.1.0 =
Refreshes the Showpass settings page and improves compatibility with WordPress 7.1. See the changelog for details.
