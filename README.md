# Showpass Events API plugin ![Build Status](https://circleci.com/gh/showpass/showpass-wordpress-plugin/tree/master.svg?style=shield&circle-token=f3e19be350eadf845d1d0ded06a0391d6367f36f)

## Description

This plugin is made for easier access to Showpass Events API data. It allows to you easily GET data from API in JSON format and manipulate in HTML website structure.

## Installation
1. Download this repo to your desktop
2. Login to your Wordpress Dashboard and go to Plugins > Add New and upload `showpass.zip` OR unzip `showpass.zip` and upload the `showpass` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.
4. Register your organization and create your events if you haven't already here: `https://www.showpass.com/organizations/register/`
5. Add your organization id found at `https://www.showpass.com/dashboard/venues/edit/` to the Showpass API admin page

## Documentation

1. [Admin page](#1-admin-page)        
   1.1. [Configure parameters](#11-configure-parameters)    
2. [Shortcode - [showpass_events]](#2-shortcode-showpass_events)       
   2.1. [Creating custom templates](#21-creating-custom-templates)   
   2.2. [Type parameter](#22-type-parameter)   
   2.3. [Page size parameter](#23-page-size-parameter)   
   2.4. [Page number parameter](#24-page-number-parameter)    
   2.5. [Tags parameter](#25-tags-parameter)    
   2.6. [Template parameter](#26-template-parameter)   
   2.7. [Page redirect parameter](#27-page-redirect-parameter)   
   2.8. [Past events parameter](#28-past-events-parameter)   
   2.9. [Event ends on parameter](#29-events-ending-on-parameter)   
   2.10. [Event IDs parameter to display specific events](#210-event-ids-parameter)   
   2.11. [Recurring event parameters](#211-recurring-event-parameters)   
   2.12. [Ordering parameter](#212-ordering-parameter)   
   2.13. [Show parameter & testing](#213-show-parameter-&-testing)   
   2.14. [Other parameters](#214-other-parameters)   
3. [Functions](#3-functions)        
   3.1. [Showpass get Event Date](#31-showpass-get-event-date)    
   3.2. [Showpass get Event Time](#32-showpass-get-event-time)    
   3.3. [Showpass get Timezone](#33-showpass-get-timezone)    
   3.4. [Showpass get Price Range](#34-showpass-get-price-range)   
   3.5. [Showpass get Previous or next page](#35-showpass-get-previous-or-next-page)  
   3.6. [Showpass get Responsive Image](#36-showpass-get-responsive-image)
4. [JSON Data](#4-json-data)     
   4.1. [Single event](#41-single-event)    
   4.2. [List events](#42-list-events)    
5. [Shortcode - [showpass_calendar]](#5-shortcode-showpass_calendar)       
   5.1. [Page parameter](#51-page-parameter)     
   5.2. [Theme Dark parameter](#52-theme_dark-parameter)                                       
   5.3. [Starting date parameter](#53-starting_date-parameter)   
   5.4. [Use widget parameter](#54-use_widget-parameter)   
   5.5. [Tags parameter](#55-tags-parameter)   
   5.6. [Hide schedule parameter](#56-hide_schedule-parameter)   
   5.7. [Recurring events parameter](#57-recurring-events-parameter)   
   5.8. [Show parameter & testing](#58-show-parameter-&-testing)   
   5.9. [Week and month parameters](#59-week-and-month-parameters)
6. [Shortcode - [showpas_widget] - Buy Now Button](#6-shortcode-showpass_widget---buy-now-button)       
  6.1. [Parameters](#61-parameters)    
  6.2. [Widget Tracking](#62-widget-tracking-using-affiliate-tracking-links)
7. [Shortcode - [showpass_cart_button]](#7-shortcode-showpass_cart_button)
8. [Query Param - ?auto=slug - Automatically Open Showpass Widget](#8-auto-query-parameter)
9. [Shortcode - [showpass_products]](#9-shortcode-showpass_products)    
  9.1. [Parameters](#91-parameters)  
10. [Shortcode - [showpass_pricing_table]](#10-shortcode-showpass_pricing_table)  
  10.1. [Parameters](#101-parameters)  

## 1. Admin page

## 1.1. Configure parameters

After installation of the plugin, in Admin page there will be in Admin menu link for Showpass API. From the Showpass API admin page you can configure parameters.
* *Organization ID* - it is ID from venue that you want to get Events from.
ex. 5 - will get all events from venue with ID = 5.
* *Widget Color* - the hex code for the primary color of the showpass widget
ex. #ff0000 - red
* *Date format* - You can enter format that you want the date to be converted to.
ex. "l F d, Y" - will format to "Friday 21 April, 2017".
* *Time format* - You can enter format that you want the time to be converted to.
ex. "g:iA" - will format to "9:00AM".
More about date and time format <a href="http://php.net/manual/en/function.date.php">here</a>.
* *Enable Dark Theme* - use the dark theme for the widget instead of the default light theme
* *Keep Shopping* - By default the widget has buttons that say "Keep Shopping" that close the widget when clicked. You can have it simply say "Close" or any other custom label

## 2. Shortcode [showpass_events]

Use the `[showpass_events type="list"]` shortcode to automatically display a list of upcoming events on any page or post.

By default it will use the grid template, and the page_size will be 20 

## 2.1. Creating Custom Templates

If you wish to make custom templates for your event lists and detail pages use the following shortcode in your template files.

The shortcode returns JSON format data from API , so you can easily get it in page template with

`<?php $data = do_shortcode('[showpass_events]'); ?>` 

Use the method above to generate your own custom templates using Showpass data.

Because it is JSON data you need to decode it `$data = json_decode($data)` .

It will be received all data from API for the venue that is set from Admin Page ( organization ID ).

Please see our plugin/inc folder for examples and visit the wordpress custom template documentation: https://developer.wordpress.org/themes/template-files-section/page-template-files/

Also, there are few parameters that you can send it to the shortcode.

## 2.2. Type Parameter

The `type` parameter is **required** for this shortcode to work.

### `type="list"`

This type `[showpass_events type="list"]` will get all the data from the venue with the ID set on the Admin page ( organization ID ).

This type uses the `default-grid.php` as a base template - use the `template="data"` parameter to customize your own template.

#### More Info Button

Please see [Page redirect parameter](#27-page-redirect-parameter) if you wish to show a More Info button and redirect visitors to an event detail page.

### `type="detail"`

This type `[showpass_events type="detail"]` will get the data from the event specified with the `slug` in the url.

This will by default use `default-detail.php`. Use the `template="data"` parameter to customize your own template.

ex. `www.website.com/event-detail?event=123` or `www.website.com/event-detail?slug=event_slug` - will get all data for the event with `id = 123` or with `slug = event_slug` . So `?slug` in url is required for `type="detail"` type of shortcode.
`event` parameter receive event ID or event slug (id or slug from API).

## 2.3. Page size parameter

This parameter gives you choice for how many events you want to show on one page. This parameter is not required, and if you don't pass in shortcode, the default number of events on one page is 20.

ex. `[showpass_events type='list' page_size='5']` - It will get only 5 events on one page.

## 2.4. Page number parameter

This parameter you can use to jump to the page number that you want.

For example if you have a list of 30 events, and you have set `page_size="5"`, the API will return to you only 5 events on one page and you will have 6 pages with 5 events on each. So, with this parameter you can easily jump to the page that you want by passing the parameter through the website URL.

ex. `www.website.com/?page_number=4` - will get all data (events) from page 4.

## 2.5. Tags Parameter

This parameter you can use the shortcode to return events with a certain category

ex. `[showpass_events type='list' page_size='5' tags='featured']` - It will get only 5 events on one page with the `featured` tag. For best results use one word lowercase tags.

## 2.6. Template Parameter

This parameter you can use the shortcode to return a default template provided with the plugin

ex. `[showpass_events type='list' page_size='5' template='default']`

**Accepted parameters**

- `default`: Grid view
- `list`: list view
- `data`: returns raw data to use in custom templates

## 2.7. Page Redirect Parameter

When using included templates, use this parameter to set the redirect location for the event detail page.

ex. `[showpass_events type='list' page_size='5' template='default' detail_page='event-detail']`

This will require you to create a new Wordpress page with the url `event-detail` with the `type="detail"` shortcode usage

## 2.8. Past Events Parameter

Use this parameter to show past events from the current date.

ex. `[showpass_events type='list' page_size='5' template='default' detail_page='event-detail' show_past_events='true']`

## 2.9. Event Ends On Parameter

Use this parameter to get events ending before or after certain dates.

`ends_on__gte` will get events ending on and after the date specified.

`ends_on__lt` will get events ending before the date specified.

The date specified must be in ISO format `YYYY-MM-DD'T'HH:MM:SS.SSS'Z'`, for example `2019-06-23T19:30:00.000Z`.

ex. `[showpass_events type='list' page_size='5' template='default' detail_page='event-detail' ends_on__gte='2019-06-23T19:30:00.000Z']`

This will get events ending on and after `June 23, 2019 19:30:00.000`.

## 2.10. Event IDs Parameter to Display Specific Events

This parameter will let you display one or multiple specific events using the `type="list"` template. Specify the IDs of the events you would like to display.

ex. `[showpass_events type='list' page_size='5' template='default' detail_page='event-detail' event_ids='254,288,292']`

## 2.11. Recurring Event Parameters

If you have recurring events, you can set whether or not you want to display them or if you want to display only the main event and not each recurrence.

`hide_children='true'` will not show any recurring events at all.

`only_parents='true'` will show non-recurring events as well as the main recurring events. When using `only_parents='false'` this will show non-recurring events as well as all occurences of recurring events.

By default `hide_children='false'` and `only_parents='true'`.

## 2.12. Ordering Parameter

This parameter is used to manipulate the order the events appear in. By default events will be ordered by start date, from earliest to the latest.

**Accepted parameters**

- `starts_on`: Order events by event start date
- `id`: Order events by ID number
- `name`: Order alphabetically by event name

ex. `[showpass_events ordering='name' type='list' page_size='5' template='default' detail_page='event-detail']`

To reverse the order add `-` before the parameter.

ex. `[showpass_events  ordering='-name' type='list' page_size='5' template='default' detail_page='event-detail']`

This will order events by name starting from Z to A.

## 2.13. Show Parameter & Testing

Use this parameter for testing purposes. Using `show='all'` will show all events you have, regardless of their visibility setting.

ex. `[showpass_events type='list' template='default' detail_page='event-detail' show='all']`

## 2.14. Other Parameters

There are a few other parameters that API can receive and this plugin is compatible for all of these parameters. You can pass it through the URL and you will get the data from API with those parameters.

*** Examples

* *query parameter* - `?q=something`

This parameter is for a search event. You need to pass it through website url `?q=something` and it will get all events that have "something" in their content.

ex. `www.website.com/?q=something` .

* *tags parameter* - `?tags=Rock`

This parameter is for filtering events through the tags. You need to pass throught the website url `?tags=tag_you_want` and it will get all events which has that tag(category). Also works with multiple tags separated by comma

ex. `www.website.com/?tags=tag_one,tag_two`

* *other parameters* - `?stars_on__gte , ?starts_on__lt , ?ends_on__gte , ?ends_on__lt` etc.

- gte - greater than or equal

- lt - less then

These parameters receives date.

## 3. Functions      

## 3.1. Showpass get Event Date

* *`showpass_get_event_date($date, $zone)`* - This is function for getting date in the timezone for the event.

`$date` and `$zone` - these are parameters that you need to pass to function. They will be in data from API output.

ex. Event start date - `showpass_get_event_date($event->starts_on, $event->timezone)`

where `starts_on` and `timezone` are parameters received from API for the event.

The date will be showed on the website in format that is set from Showpass Admin Page.

* example `<?php echo showpass_get_event_date($event->starts_on, $event->timezone); ?>`

It will print the *date* when the event starts (ex. Friday 05 May, 2018).

## 3.2. Showpass get Event Time

* *`showpass_get_event_time($date, $zone)`* - This is function for getting time in the timezone for the event.

`$date` and `$zone` - these are parameters that you need to pass to function. They will be in data from API output.

ex. Event start time - `showpass_get_event_date($event->starts_on, $event->timezone)`

where `starts_on` and `timezone` are parameters received from API for the event.

The time will be showed on the website in the format that is set from the Showpass Admin Page.

* example `<?php echo showpass_get_event_time($event->starts_on, $event->timezone); ?>`

It will print the *time* when the event starts (ex. 9:00AM).

## 3.3. Showpass get Timezone

* *`showpass_get_timezone_abbr($timezone)`* - This is function for getting timezone (offset) from the event.

`timezone` - this parameter you need pass to function. It is the event timezone from the API.

* example `<?php echo showpass_get_timezone_abbr($event->timezone); ?>`

It will print the timezone of event (MDT, PDT ect.).

## 3.4. Showpass get Price Range

* *`showpass_get_price_range($ticket_types)`* - This is function for getting a price range for the event's tickets.

`ticket types` - this parameter is required for the API to return prices for the ticket types set for the event

* example `<?php echo showpass_get_price_range($event->ticket_types); ?>`

It will print either FREE, $x-$y, or $x (ex. $5-$30 if multiple price levels or $30 if only one price level)

## 3.5. Showpass get Previous or Next page

* *`showpass_get_events_next_prev($page)`* - This function is for pagination of the pages. This function sets up the `$page` parameter.

ex. You will have (the API will receive) 5 pages with 6 events on each page. So, for pagination you will use this function.

`$page` - The number of the page that you will get from the returned API data.

`showpass_get_events_next_prev($event->next_page_number)` or `showpass_get_events_next_prev($event->previous_page_number)` , depends on pagination.

`next_page_number` or `previous_page_number` is an integer, so you can easily put the number of whatever page you want.


 - This is one example of pagination

		<?php

		if($events->previous_page_number != NULL){ ?>
			<a style="float:left;" href="<?php echo showpass_get_events_next_prev($events->previous_page_number); ?>">	Page <?php echo $events->previous_page_number ; ?>
			</a>
		<?php }

		if($events->next_page_number != NULL){ ?>
			<a style="float: right;" href="<?php echo showpass_get_events_next_prev($events->next_page_number); ?>">	Page <?php echo $events->next_page_number; ?>
			</a>
		<?php } ?>

		?>

## 3.6. Showpass get responsive image

### Usage
```php
<?php
   // must declare showpass_image_formatter
   global $showpass_image_formatter;

   // get showpass event data
   $event_data = json_decode(do_shortcode('[showpass_events template="data"]'), true);

   // img src
   $img_url = $event_data['image_banner'];
   // img options
   $options = [
      'alt' => $event_data['name'], 
      'title' => $event_data['name']
      'attr' => [
         'id' => 'custom-id',
         'class' => 'custom-class'
      ]
   ];
?>
// template implementation ...

// echo responsive image: <picure>
<?= $showpass_image_formatter->getResponsiveImage($img_url, $options)  ?>

```

### `$showpass_image_formatter->getResponsiveImage($src, $options)`  
This function generates a responsive image.

- `$src` - __String__ Showpass event image source. __Must be a `CloudFront` image__  

- `$options` - __Array__  Image Options. `['option_name' => value]`
   - `alt` - __String__ Alt attribute.
   - `title` - __String__ Title attrinute.
   - `image-format` - __String__ Desired image format.  
   __default:__ `'jpeg'`
   - `attr` - __Array__ Additional html attributes. `['attribute_name' => value]`
   - `breakpoints` - __Array__ List of desired breakpoints. `[[size, media-query]]`.  
   __default:__
      ```php
      [
         [960, '(max-width: 960px) and (min-width: 781px)'], 
         [780, '(max-width: 780px) and (min-width: 601px)'], 
         [600, '(max-width: 600px) and (min-width: 376px)'], 
         [375, '(max-width: 375px)']
      ]
      ```

## 4. JSON Data

## 4.1. Single event
```
{  
   "id":19,
   "created":"2018-09-10T21:35:17.413662Z",
   "updated":"2018-10-26T18:31:11.180964Z",
   "slug":"test",
   "name":"Test event name",
   "subtitle":null,
   "venue":{  
      "id":1,
      "slug":"new-organization",
      "name":"New Organization",
      "twitter":null,
      "facebook":null,
      "web_address":null,
      "description":"",
      "phone_number":"14038500080",
      "street_name":"2600 Portland St SE",
      "city":"Calgary",
      "avatar":null,
      "currency":"CAD",
      "instagram":null,
      "default_refund_policy":"",
      "allow_messages_from_customers":true,
      "is_test":false
   },
   "location":{  
      "id":1,
      "street_name":"2600 Portland St SE",
      "city":"Calgary",
      "province":"Alberta",
      "postal_code":"T2G 4M6",
      "position":"51.0314208,-114.03099580000003",
      "venue":1,
      "name":"New Organization",
      "country":"Canada"
   },
   "starts_on":"2018-11-02T03:00:00Z",
   "ends_on":"2018-11-02T08:00:00Z",
   "opens_at":null,
   "terms":null,
   "description":"",
   "venue_fee":"0.00",
   "getqd_fee":"0.0540",
   "getqd_fee_added":"1.99",
   "image":"http://local.showpass.com:8000/media/images/events/new-organization/images/1bb2fa0f-0a7.png",
   "image_banner":"http://local.showpass.com:8000/media/images/events/new-organization/img-banner/1bb2fa0f-0a7.png",
   "thumbnail":"http://local.showpass.com:8000/media/images/events/new-organization/thumbnails/1bb2fa0f-0a7.png",
   "is_published":true,
   "is_published_for_sellers":true,
   "is_featured":true,
   "password_protected":false,
   "facebook_id":null,
   "ticket_types":[  
      {  
         "sold_out":true,
         "id":34,
         "created":"2018-10-10T23:23:52.164872Z",
         "updated":"2018-10-26T18:31:11.314003Z",
         "taxes":"0.00",
         "service_charges":"7.39",
         "total_price":"107.39",
         "event":19,
         "name":"Sold out ticket",
         "description":null,
         "sale_starts_on":"2018-10-10T23:23:52.151183Z",
         "sale_ends_on":"2018-11-02T03:00:00Z",
         "price":"100.00",
         "shipping_type":[  
            "st_print"
         ],
         "base_inventory":34,
         "purchase_limit":8,
         "seat_permissions":[  

         ],
         "voucher_purchases_only":false,
         "stats":{  
            "tickets":{  
               "sold":8.0,
               "sold_and_basket":8.0
            },
            "last_updated":"2018-10-26T18:31:36.471058Z"
         },
         "inventory":8,
         "minimum_purchase_limit":null,
         "show_inventory_amount_tt":null,
         "ticket_transfer_enabled":true,
         "voucher_quantity":null,
         "collect_info_first_name":true,
         "collect_info_last_name":true,
         "collect_info_company":false,
         "collect_info_phone_number":true,
         "collect_info_email":true,
         "collect_info_job_title":false,
         "collect_info_student_number":false,
         "collect_info_home_address":false,
         "enforce_box_office_info_collection":false,
         "info_collect_per_ticket":false,
         "info_collection_type":"gict_standard_info"
      }
   ],
   "assigned_space":null,
   "frontend_details_url":"http://local.showpass.com:8000/test/",
   "no_ticket_types_message":null,
   "social_share_enabled":false,
   "social_share_reward":"1.00",
   "timezone":"America/Edmonton",
   "currency":"CAD",
   "password_message":"",
   "external_link":null,
   "sold_out":false,
   "refund_policy":null,
   "facebook_official_events_id":null,
   "require_terms_acceptance":null,
   "terms_url_link":null,
   "local_starts_on":"2018-11-01T21:00:00-06:00",
   "info_collect_per_ticket":false,
   "initiate_purchase_button":"ipbd_buy_tickets",
   "post_purchase_message":null,
   "is_saved":true,
   "show_inventory_amount":null,
   "restrictions":[  

   ],
   "inventory":null,
   "event_metadata_dict":{  
      "director":{  
         "type":"text",
         "html":false,
         "field_name":"director",
         "value":"Directors Name Here",
         "title":"Director"
      },
      "time":{  
         "type":"text",
         "html":false,
         "field_name":"time",
         "value":"22 mins",
         "title":"Time"
      }
   },
   "has_related_events":true,
   "is_main":true,
   "collect_info_first_name":true,
   "collect_info_last_name":true,
   "collect_info_company":false,
   "collect_info_phone_number":true,
   "collect_info_email":true,
   "collect_info_job_title":false,
   "collect_info_student_number":false,
   "collect_info_home_address":false,
   "enforce_box_office_info_collection":false,
   "info_collection_type":"gict_standard_info",
   "related_events":[  
      {  
         "id":26,
         "...": 'full event object',
      },
      {
        "id":27,
        "...": 'full event object',
      }
   ]
}
```


## 4.2. List events
```
{  
   "count":1,
   "next":null,
   "previous":null,
   "next_page_number":null,
   "previous_page_number":null,
   "page_number":1,
   "num_pages":1,
   "next_list":[  

   ],
   "previous_list":[  

   ],
   "results":[  
      {  
         "id":19,
         "created":"2018-09-10T21:35:17.413662Z",
         "updated":"2018-10-26T18:31:11.180964Z",
         "slug":"test",
         "name":"Test event name",
         "subtitle":null,
         "venue":{  
            "id":1,
            "slug":"new-organization",
            "name":"New Organization",
            "twitter":null,
            "facebook":null,
            "web_address":null,
            "description":"",
            "phone_number":"14038500080",
            "street_name":"2600 Portland St SE",
            "city":"Calgary",
            "avatar":null,
            "currency":"CAD",
            "instagram":null,
            "default_refund_policy":"",
            "allow_messages_from_customers":true,
            "is_test":false
         },
         "location":{  
            "id":1,
            "street_name":"2600 Portland St SE",
            "city":"Calgary",
            "province":"Alberta",
            "postal_code":"T2G 4M6",
            "position":"51.0314208,-114.03099580000003",
            "venue":1,
            "name":"New Organization",
            "country":"Canada"
         },
         "starts_on":"2018-11-02T03:00:00Z",
         "ends_on":"2018-11-02T08:00:00Z",
         "opens_at":null,
         "terms":null,
         "description":"",
         "venue_fee":"0.00",
         "getqd_fee":"0.0540",
         "getqd_fee_added":"1.99",
         "image":"http://local.showpass.com:8000/media/images/events/new-organization/images/1bb2fa0f-0a7.png",
         "image_banner":"http://local.showpass.com:8000/media/images/events/new-organization/img-banner/1bb2fa0f-0a7.png",
         "thumbnail":"http://local.showpass.com:8000/media/images/events/new-organization/thumbnails/1bb2fa0f-0a7.png",
         "is_published":true,
         "is_published_for_sellers":true,
         "is_featured":true,
         "password_protected":false,
         "facebook_id":null,
         "ticket_types":[  
            {  
               "sold_out":true,
               "id":34,
               "created":"2018-10-10T23:23:52.164872Z",
               "updated":"2018-10-26T18:31:11.314003Z",
               "taxes":"0.00",
               "service_charges":"7.39",
               "total_price":"107.39",
               "event":19,
               "name":"Sold out ticket",
               "description":null,
               "sale_starts_on":"2018-10-10T23:23:52.151183Z",
               "sale_ends_on":"2018-11-02T03:00:00Z",
               "price":"100.00",
               "shipping_type":[  
                  "st_print"
               ],
               "base_inventory":34,
               "purchase_limit":8,
               "seat_permissions":[  

               ],
               "voucher_purchases_only":false,
               "stats":{  
                  "tickets":{  
                     "sold":8.0,
                     "sold_and_basket":8.0
                  },
                  "last_updated":"2018-10-26T18:31:36.471058Z"
               },
               "inventory":8,
               "minimum_purchase_limit":null,
               "show_inventory_amount_tt":null,
               "ticket_transfer_enabled":true,
               "voucher_quantity":null,
               "collect_info_first_name":true,
               "collect_info_last_name":true,
               "collect_info_company":false,
               "collect_info_phone_number":true,
               "collect_info_email":true,
               "collect_info_job_title":false,
               "collect_info_student_number":false,
               "collect_info_home_address":false,
               "enforce_box_office_info_collection":false,
               "info_collect_per_ticket":false,
               "info_collection_type":"gict_standard_info"
            }
         ],
         "assigned_space":null,
         "frontend_details_url":"http://local.showpass.com:8000/test/",
         "no_ticket_types_message":null,
         "social_share_enabled":false,
         "social_share_reward":"1.00",
         "timezone":"America/Edmonton",
         "currency":"CAD",
         "password_message":"",
         "external_link":null,
         "sold_out":false,
         "refund_policy":null,
         "facebook_official_events_id":null,
         "require_terms_acceptance":null,
         "terms_url_link":null,
         "local_starts_on":"2018-11-01T21:00:00-06:00",
         "info_collect_per_ticket":false,
         "initiate_purchase_button":"ipbd_buy_tickets",
         "post_purchase_message":null,
         "is_saved":true,
         "show_inventory_amount":null,
         "restrictions":[  

         ],
         "inventory":null,
         "event_metadata_dict":{  
            "director":{  
               "type":"text",
               "html":false,
               "field_name":"director",
               "value":"Directors Name Here",
               "title":"Director"
            },
            "time":{  
               "type":"text",
               "html":false,
               "field_name":"time",
               "value":"22 mins",
               "title":"Time"
            }
         },
         "has_related_events":true,
         "is_main":true,
         "collect_info_first_name":true,
         "collect_info_last_name":true,
         "collect_info_company":false,
         "collect_info_phone_number":true,
         "collect_info_email":true,
         "collect_info_job_title":false,
         "collect_info_student_number":false,
         "collect_info_home_address":false,
         "enforce_box_office_info_collection":false,
         "info_collection_type":"gict_standard_info",
         "related_events":[  
            {  
               "id":26,
               "...": 'full event object',
            },
            {
              "id":27,
              "...": 'full event object',
            }
         ]
      }
   ]
}
```
## 5. Shortcode [showpass_calendar]

You will need to add just this shortcode `[showpass_calendar]` and you will get complete calendar with all the events from the venue that is set in the admin page. If the venue(organisation ID) is not set, then you will get all events from the API.

This shortcode gives you calendar view with all events bound to their start dates for up to one year from today.

The calendar can also be set to Week view, where you are able to see the events week by week.

The calendar events have the all the Showpass event info that you need, and link directly to the event page (external link).

## 5.1. Deatil page parameter

This shortcode recieves `detail_page` parameter. If this parameter is set, it tells the plugin to use custom links for event's redirect.

Example:  `[showpass_calendar detail_page="event-detail"]`  - This will tell the plugin that on click on event it will not redirect to external link for event, but it will redirect on the same website with this url: `website.main.url/event-detail?slug=event-slug`

The `detail_page` parameter is `event-detail` in this example, but can be set to any slug

## 5.2. theme_dark Parameter
Add `theme_dark="true"` to use a dark theme for the calendar instead of the default light theme.

## 5.3. starting_date Parameter
Add `starting_date="1-12-2018"` use day-month-year to set a starting date for the calendar on initial load
Format is day, month, year with no leading zeros

## 5.4. use_widget Parameter
Add `use_widget="true"` if you want the ticket button to open the widget instead of redirecting to showpass or an event detail page use this

## 5.5. tags Parameter
Add `tags="tag"` if you want to only display events with certain tags or categories. For best results use one word lowercase tags.

## 5.6. hide_schedule Parameter
Add `hide_schedule="true"` if you do not want to display a daily schedule grouped by location.

## 5.7. Recurring Events Parameter
If you have recurring events, you can set whether or not you want to display them on the calendar or if you want to display only the main event and not each recurrence.

`hide_children='true'` will not show any recurring events at all.

`only_parents='true'` will show non-recurring events as well as the main recurring event. When using `only_parents='false'` this will show non-recurring events as well as all occurences of recurring events.

By default `hide_children='false'` and `only_parents='false'`. In this case, non-recurring events are shown, as well as each occurence of a recurring event.

## 5.8. Show Parameter & Testing

Use this parameter for testing purposes. Using `show='all'` will show all events you have, regardless of their visibility setting.

## 5.9. Week and Month Parameters

This shortcode also receives parameters `week` and `month` if you want to disable a view.

If you want disable week view you will need to put `[showpass_calendar week="disabled"]`

If you want disable month view you will need to put `[showpass_calendar month="disabled"]`

If both views are disabled, it will default to the week view, and by default both views are made available.

## 6. Shortcode [showpass_widget] - Buy Now Button

Use the showpass_widget shortcode to embed a button with the ticket widget on any page or post.

`[showpass_widget label="Button Label" slug="this-is-the-slug" class="button-class" keep_shopping="false"]`

### 6.1. Parameters

#### `label="Button Label"`
Customize the verbiage on the button.
`Default: Tickets`

#### `slug="this-is-the-slug"`
Required, the slug that appears `http://www.showpass.com/` in a URL. ex. `http://www.showpass.com/this-is-the-slug`

#### `class="button"`
Use a custom class to style your button - Showpass button style provided by default

#### `keep_shopping="false"`
Button to close widget says `Keep Shopping` if true, and `Close` if set to false.
`Default: true`

#### `theme="dark"`
Use the dark theme on the widget, default is the light theme.

## 6.2 Widget Tracking using Affiliate Tracking Links

### How it Works
Once you create a tracking link, you need to add a query parameter to the URL of your website address. Adding the `aff=8ee54af5` query parameter will create a cookie that will inject the `tracking-id` paremeter to the showpass widget SDK. The `[showpass_widget]` shortcode is automatically set up to look for tracking tokens.

### Create an Affiliate Tracking link
To learn how to create an affiliate tracking link - http://support.showpass.com/event-organizers/tracking-links/affiliate-tracking-links

### Add the tracking ID to your incoming url
Once you create your tracking link you will have a unique token `(ie 8ee54af5)` for that specific tracking link

#### Add the `aff` query parameter to a regular link

`www.website.com/page/?aff=8ee54af5`

#### Add the `aff` query parameter to a specific event-organizers

`www.website.com/?event=event_slug&aff=8ee54af5`

## 7. Shortcode [showpass_cart_button]
Add a button to initiate the shopping cart and checkout widget

`[showpass_cart_button]`

Will display `Shopping Cart (x)` inside the button, and the x variable will update with the number of items in a shopping cartx

## 8. Auto Query Parameter

You can automatically open the ticket widget as soon as a customer lands on any page on your site by using the `auto` query parameter

http://example.com?auto=this-is-the-slug

Just include the slug of your event in place of `this-is-the-slug`

## 9. Shortcode [showpass_products]

List & sell products from your Showpass organization account

`[showpass_products template="list" page_size="8"]`

## 9.1. Parameters

#### `template="list|grid|data"`
Set the display layout `Default: grid`

Use the `template="data"` parameter to customize your own template.

#### `page_size="int"`
Set the number of results per page `Default: 20`

#### `product_ids`
Display specific products by specifying the IDs of the products you would like to show.

ex. `[showpass_products template="list" product_ids="2,6,7"]`

## 10. Shortcode [showpass_pricing_table]
Similar to the grid view for the `[showpass_events]` shortcode, but displays events in a grid where all columns are of equal height. Allows you to customize what information is shown and include the event description. You must specify event IDs for events you want to display.

`[showpass_pricing_table ids="10125,10254,10288"]`

## 10.1. Parameters

#### `show_event_details`
Set `show_event_details='true'` to display the event date, time, and location. By default these are hidden.

#### `show_event_description`
Set `show_event_description='true'` to display the event description. By default this is hidden.