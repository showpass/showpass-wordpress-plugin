<?php

function wpshp_settings_page() {
?>
<div class="wrap">
<h1>Showpass Events API</h1>

<!-- TBD - More description -->
<p>The main API URL is<strong> https://www.showpass.com/api. </strong> <br />
You will need to add Organization ID (venue ID) that you want the data from.  EX. 5 , if you want data from organization 5. You can also enter in multiple organizations 5, 10, 20, 30 - NOT RECCOMENDED FOR PURCHASE WIDGET - you cannot purchase to more than one organization at once. </p>

<form method="post" action="options.php">
    <?php settings_fields( 'wpshp-settings-group' ); ?>
    <?php do_settings_sections( 'wpshp-settings-group' ); ?>


        <label for="main_api_url">Organization ID (required):</label><br/>
        <input type="text" placeholder="Venue ID Ex. 5" name="option_organization_id" value="<?php echo esc_attr( get_option('option_organization_id') ); ?>" /><br/><br/>

        <label for="main_api_url">Widget Color (Hex Code):</label><br/>
        <input type="text" placeholder="DD3333" name="option_widget_color" value="<?php echo esc_attr( get_option('option_widget_color') ); ?>" /><br/><br/>

        <label for="main_api_url">Enter Date Format: (if empty "l F d, Y" - ex. Friday 21 April, 2017)</label><br/>
        <input type="text" placeholder="l F d, Y" name="format_date" value="<?php echo esc_attr( get_option('format_date') ); ?>" /><br/><br/>

        <label for="main_api_url">Enter Time Format: (if empty "g:iA" - ex. 9:00AM)</label><br/>
        <input type="text" placeholder="g:iA" name="format_time" value="<?php echo esc_attr( get_option('format_time') ); ?>" />

    <?php submit_button(); ?>

</form>
</div>

<hr></hr>

<div class="wrap">
<h2>DOCS</h2>

<p>This plugin is made for easily getting data from Showpass Events Public API (https://www.showpass.com/api/public/events/).</p>
<h3>Setting the venue</h3>
<p>You can set the venue through the admin from Organization ID field. The Plugin works in that way that is sending API call and get all data from the venue with that ID. <br/></p>
<h2><strong>Shortcode - </strong> [showpass_events]</h2>
<p>- The shortcode receives parameter <strong>type</strong> and it can be <strong>single</strong> or <strong>list</strong>.
<br/>
* <strong>[showpass_events type="single"]</strong> is for receiving the data from single event that is specified through the event_id parameter in URL... ex. www.website.com/?event_id=123
<br/>
* <strong>[showpass_events type="list"]</strong> is for receiving the data from specified venue that is set in admin page (field organization ID). The API receives all events from the venue. <br/>
Also you can set in shortcode how many events do you want on page. Ex. <strong>[showpass_events type="list" page_size="5"]</strong> - it will receive 5 events on each page. The default page_size is 20 (20 events on page).
<br/>
The "list" type shortcode also can receive page number in website url... ex. <strong>www.website.com/?page_number=5</strong> <br/>
This is good for pagination... There is function that will be explained below. <br/>
Also, the "list" type can receive query parameter for searching through events... ex. <strong>www.website.com/?q=something</strong><br/>
It will return all events that has the string in their content.</p>

<h3>Functions</h3>
There are few functions for making easier building the structure and formating date and time: <br/> <br/>
<strong><i>showpass_get_event_date($date, $timezone)</i></strong><br/>
- Using: you can easily use it for print the date of the event on website...<br/>
ex.  <strong><code>&lt;?php echo showpass_get_event_date($event->starts_on, $event->timezone); ?></code></strong><br/>
where <strong><i>$event->starts_on</i></strong> and <strong><i>$event->timezone</i></strong> are parameters that are received from API. This function will have output date with formating that it is set from admin page.
<br/> <br/>
<strong><i>showpass_get_event_time($date, $timezone)</i></strong><br/>
- Using: you can easily use it for print the time of the event on website...<br/>
ex.  <strong><code>&lt;?php echo showpass_get_event_time($event->starts_on, $event->timezone); ?></code></strong><br/>
where <strong><i>$event->starts_on</i></strong> and <strong><i>$event->timezone</i></strong> are parameters that are received from API. This function will have output time with formating that it is set from admin page.
<br/> <br/>
<strong><i>showpass_get_events_next_prev($page)</i></strong><br/>
- Using: you can easily use it for pagination...<br/>
ex.  <strong><code>&lt;?php echo showpass_get_events_next_prev($event->next_page_number/previous_page_number); ?></code></strong><br/>
where <strong><i>$event->next_page_number</i></strong> or <strong><i>$event->previous_page_number</i></strong> are parameters that are received from API. This function will make the url <strong>www.website.com/?page_number=page_number</strong> and there will be received the events from that page.<br/><br/>
Click for more <a href="https://github.com/showpass/showpass-wordpress-plugin" target="_blank">Documentation</a>
</div>


<h2><strong>Shortcode - </strong> [showpass_calendar]</h2>
<p>You will need to add just this shortcode and you will get complete calendar with all the events from venue that is set in admin page above. If the venue(organisation ID) is not set, then you will get all events from API </p>
<p>This shortcode gives you calendar view (month view and week view) with all events binded in the dates of their start. You are able to go throuth the calendar and see the events that are one year after today.</p>
<p>The events have the all infos that you need, and you can go on the event page(external link) through the calendar</p>
<p>This shortcode recieve parameter <strong>page</strong>. If this parameter is set, you tell the plugin to use custom links for event's redirect.</p>
<p><strong>Example: [showpass_calendar page="event-detail"]</strong> - This will tell the plugin that on click on event it will not redirect to external link for event, but it will redirect on the same website with this url: <br/><strong>www.website.com/event-detail?slug=event-slug</strong></p>
<p>The <strong>page</strong> parameter is <strong>event-detail</strong> in this case, and it will be whatevet page you want.</p>

<p>This shortcode also recieve parameters <strong>week</strong> and <strong>month</strong> if you want disable some view...</p>
<p>If you want disable week view you will need to put [showpass_calendar week="disabled"]</p>
<p>If you want disable month view you will need to put [showpass_calendar month="disabled"]</p>
<p>If there is none of this parameters, there will be both views</p>
<p>Enyoj!</p>

<?php }


?>
