# Showpass Events API plugin ![Build Status](https://circleci.com/gh/showpass/showpass-wordpress-plugin/tree/master.svg?style=shield&circle-token=f3e19be350eadf845d1d0ded06a0391d6367f36f)

## Description

This plugin is made for easier access to Showpass Events API data. It allows to you easily GET data from API in JSON format and manipulate in HTML website structure.

## Installation
1. Download this repo to your desktop
2. Login to your Wordpress Dashboard and go to Plugins > Add New and upload `showpass-wordpress-plugin.zip` OR unzip `showpass-wordpress-plugin.zip` and upload the `showpass-wordpress-plugin` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.
4. Add your organization id found at `https://www.showpass.com/dashboard/venues/edit/` to the Showpass API admin page

## Documentation

1. [Admin page](#1-admin-page)        
   1.1. [Configure parameters](#11-configure-parameters)    
2. [Shortcode - [showpass_events]](#2-shortcode-showpass_events)       
   2.1. [Adding shortcode and get data](#21-adding-shortcode-and-get-data)   
   2.2. [Type parameter](#22-type-parameter)   
   2.3. [Page size parameter](#23-page-size-parameter)   
   2.4. [Page number parameter](#24-page-number-parameter)    
   2.5. [Tags Parameter](#25-tags-parameter)    
   2.6. [Template Parameter](#26-template-parameter)   
   2.7. [Page parameter](#27-page-parameter)   
   2.8. [Other parameters](#28-other-parameters)   
3. [Functions](#3-functions)        
   3.1. [Showpass get Event Date](#31-showpass-get-event-date)    
   3.2. [Showpass get Event Time](#32-showpass-get-event-time)    
   3.3. [Showpass get Timezone](#33-showpass-get-timezone)    
   3.4. [Showpass get Previous or next page](#34-showpass-get-previous-or-next-page)   
4. [JSON Data](#4-json-data)     
   4.1. [Single event](#41-single-event)    
   4.2. [List events](#42-list-events)    
5. [Shortcode - [showpass_calendar]](#5-shortcode-showpass_calendar)       
   5.1. [Page parameter](#51-page-parameter)     
   5.2. [Theme Dark parameter](#52-theme_dark-paramerter)                                       
   5.3. [Starting date parameter](#53-starting_date-paramerter)   
   5.4. [Use widget parameter](#54-use_widget-paramerter)   
   5.5. [Tags parameter](#55-tags-paramerter)   
   <!---5.5. [Week and month parameters](#54-week-and-month-parameters)-->
6. [Shortcode - [showpas_widget]](#5-shortcode-showpass_widget)       
  6.1. [Parameters](#61-widget-parameter)
  6.2. [Widget Tracking](#62-widget-tracking)
7. [Shortcode - [showpass_cart_button]](#7-shortcode-showpass_cart_button)
8. [Query Param - ?auto=slug](#8-auto-query-parameter)


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

## 2.1. Adding shortcode and get data

If you wish to make custom templates for your event lists and detail pages use the following shortcode in your template files.

The shortcode returns JSON format data from API , so you can easily get it in page template with
`<?php $data = do_shortcode('[showpass_events]'); ?>` .

Because it is JSON data you need to decode it `$data = json_decode($data)` .

It will be received all data from API for the venue that is set from Admin Page ( organization ID ).

Also , there are few parameters that you can send it to the shortcode.

## 2.2. Type Parameter

Type parameter is required for shortcode to work.  You have `type="single"` for getting one specified event or `type="list"` to get all data(events) from the venue that is set in the admin page.

### `type="single"`

This type `[showpass_events type="single"]` will get the data from the event specified with the `event_id` in the url.

ex. `www.website.com/?event=123` or `www.website.com/?event=event_slug` - will get all data for the event with ID = 123 or with slug = event_slug . So `?event` in url is required for `type="single"` type of shortcode.
`event` parameter receive event ID or event slug (id or slug from API).

### `type="list"`

This type `[showpass_events type="list"]` will get all the data from the venue with the ID set on the Admin page ( organization ID ).

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

Released in version 2.0 currently the plugin only has one template, which is 'default'

## 2.7. Page Redirect Parameter

When using included templates, use this parameter to set the redirect location for the event detail page.

This will be the Wordpress page with the `type="single"` shortcode usage

ex. `[showpass_events type='list' page_size='5' template='default' page='event-detail']`

## 2.8. Other parameters

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

## 3.4. Showpass get price range

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


## 4. JSON Data

## 4.1. Single event
```
{  
   "id":8996,
   "created":"2018-02-15T23:09:54.745729Z",
   "updated":"2018-09-07T12:18:36.919415Z",
   "slug":"circle",
   "name":"Circle 2018",
   "subtitle":null,
   "venue":{  
      "id":649,
      "slug":"circle",
      "name":"Circle",
      "twitter":"https://twitter.com/circlecarnival",
      "facebook":"https://www.facebook.com/CircleCarnival/",
      "web_address":"http://circlecarnival.com",
      "description":"",
      "phone_number":"14034722209",
      "street_name":"409 9A ST NE",
      "city":"Calgary",
      "avatar":"https://showpass-live.s3.amazonaws.com/media/images/venues/circle/avatars/e3ae8526-4e8.png",
      "currency":"CAD",
      "instagram":"https://www.instagram.com/circlecarnival/",
      "default_refund_policy":"",
      "allow_messages_from_customers":true,
      "is_test":false
   },
   "location":{  
      "id":6217,
      "street_name":"1220 9 Ave SW",
      "city":"Calgary",
      "province":"Alberta",
      "postal_code":"T2P 2C4",
      "position":"51.046149,-114.09067900000002",
      "venue":649,
      "name":"Shaw Millennium Park",
      "country":"Canada"
   },
   "starts_on":"2018-09-08T17:00:00Z",
   "ends_on":"2018-09-09T05:00:00Z",
   "opens_at":"2018-09-08T17:00:00Z",
   "terms":null,
   "description":"",
   "venue_fee":"0.00",
   "getqd_fee":"0.0700",
   "getqd_fee_added":"1.50",
   "image":"https://showpass-live.s3.amazonaws.com/media/images/events/circle/images/6dddbf47-34d.png",
   "image_banner":"https://showpass-live.s3.amazonaws.com/media/images/events/circle/img-banner/4ebea0a0-e74.png",
   "thumbnail":"https://showpass-live.s3.amazonaws.com/media/images/events/circle/thumbnails/6dddbf47-34d.png",
   "is_published":true,
   "is_published_for_sellers":true,
   "is_featured":true,
   "password_protected":false,
   "facebook_id":null,
   "ticket_types":[  
      {  
         "sold_out":false,
         "id":21486,
         "created":"2018-02-15T23:09:55.025426Z",
         "updated":"2018-09-04T18:26:04.981989Z",
         "taxes":"1.75",
         "service_charges":"3.95",
         "total_price":"40.70",
         "event":8996,
         "name":"General Admission",
         "description":null,
         "sale_starts_on":"2018-04-20T20:11:52.739918Z",
         "sale_ends_on":"2018-09-08T17:00:00Z",
         "price":"35.00",
         "shipping_type":[  
            "st_print"
         ],
         "base_inventory":21486,
         "purchase_limit":10,
         "seat_permissions":[  

         ],
         "voucher_purchases_only":false,
         "stats":{  
            "tickets":{  
               "sold":1278,
               "sold_and_basket":1280
            },
            "last_updated":"2018-09-07T20:30:38.010464Z"
         },
         "inventory":4000,
         "minimum_purchase_limit":null,
         "show_inventory_amount_tt":null,
         "ticket_transfer_enabled":true,
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
      },
      {  
         "sold_out":false,
         "id":21488,
         "created":"2018-02-15T23:09:55.224200Z",
         "updated":"2018-09-04T18:26:05.027127Z",
         "taxes":"0.25",
         "service_charges":"1.85",
         "total_price":"7.10",
         "event":8996,
         "name":"Child Age 4-14",
         "description":"<p>Limit four per transaction.&nbsp;Kids 3 and under are free.&nbsp;</p>",
         "sale_starts_on":"2018-02-15T23:09:55.214053Z",
         "sale_ends_on":"2018-09-08T17:00:00Z",
         "price":"5.00",
         "shipping_type":[  
            "st_print"
         ],
         "base_inventory":21488,
         "purchase_limit":4,
         "seat_permissions":[  

         ],
         "voucher_purchases_only":false,
         "stats":{  
            "tickets":{  
               "sold":497,
               "sold_and_basket":497
            },
            "last_updated":"2018-09-07T20:30:38.013591Z"
         },
         "inventory":500,
         "minimum_purchase_limit":null,
         "show_inventory_amount_tt":null,
         "ticket_transfer_enabled":true,
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
   "frontend_details_url":"https://www.showpass.com/circle/",
   "no_ticket_types_message":null,
   "social_share_enabled":false,
   "social_share_reward":"2.00",
   "timezone":"US/Mountain",
   "currency":"CAD",
   "password_message":"",
   "external_link":null,
   "sold_out":false,
   "refund_policy":"All tickets sales for Circle Carnival final and are non-refundable.",
   "facebook_official_events_id":"185409158761229",
   "require_terms_acceptance":null,
   "terms_url_link":null,
   "local_starts_on":"2018-09-08T11:00:00-06:00",
   "info_collect_per_ticket":false,
   "initiate_purchase_button":"ipbd_buy_tickets",
   "post_purchase_message":null,
   "is_saved":false,
   "show_inventory_amount":null,
   "restrictions":[  

   ],
   "inventory":null,
   "collect_info_first_name":true,
   "collect_info_last_name":true,
   "collect_info_company":false,
   "collect_info_phone_number":true,
   "collect_info_email":true,
   "collect_info_job_title":false,
   "collect_info_student_number":false,
   "collect_info_home_address":false,
   "enforce_box_office_info_collection":false,
   "info_collection_type":"gict_standard_info"
}
```


## 4.2. List events
```
		{  
   "count":4,
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
         "id":554,
         "created":"2018-06-15T16:43:20.852436Z",
         "updated":"2018-08-29T18:40:07.003717Z",
         "slug":"october-5-2018",
         "name":"October 5, 2018",
         "subtitle":null,
         "venue":{  
            "id":170,
            "slug":"screamfest",
            "name":"ScreamFest",
            "twitter":null,
            "facebook":null,
            "web_address":null,
            "description":"",
            "phone_number":"14038269182",
            "street_name":"",
            "city":"",
            "avatar":null,
            "currency":"CAD",
            "instagram":null,
            "default_refund_policy":"",
            "allow_messages_from_customers":true,
            "is_test":false
         },
         "location":{  
            "id":253,
            "street_name":"20 Roundup Way SE",
            "city":"Calgary",
            "province":"Alberta",
            "postal_code":"T2G 5A2",
            "position":"51.038176,-114.05651799999998",
            "venue":170,
            "name":"The Grandstand Building",
            "country":"Canada"
         },
         "starts_on":"2018-10-06T01:00:00Z",
         "ends_on":"2018-10-31T08:00:00Z",
         "opens_at":null,
         "terms":null,
         "description":"",
         "venue_fee":"0.00",
         "getqd_fee":"0.0540",
         "getqd_fee_added":"1.99",
         "image":"https://showpass-beta.s3.amazonaws.com/media/images/events/screamfest/images/46438724-466.png",
         "image_banner":"https://showpass-beta.s3.amazonaws.com/media/images/events/screamfest/images/46438724-466.png",
         "thumbnail":"https://showpass-beta.s3.amazonaws.com/media/images/events/screamfest/thumbnails/46438724-466.png",
         "is_published":true,
         "is_published_for_sellers":true,
         "is_featured":true,
         "password_protected":false,
         "facebook_id":null,
         "ticket_types":[  
            {  
               "sold_out":false,
               "id":917,
               "created":"2018-06-15T16:43:21.192685Z",
               "updated":"2018-08-29T18:40:07.094749Z",
               "taxes":"0.00",
               "service_charges":"3.61",
               "total_price":"33.61",
               "event":554,
               "name":"General Admission",
               "description":null,
               "sale_starts_on":"2018-06-15T16:43:21.179608Z",
               "sale_ends_on":"2018-10-06T01:00:00Z",
               "price":"30.00",
               "shipping_type":[  
                  "st_print"
               ],
               "base_inventory":917,
               "purchase_limit":8,
               "seat_permissions":[  

               ],
               "voucher_purchases_only":false,
               "stats":{  
                  "tickets":{  
                     "sold":0.0,
                     "sold_and_basket":0.0
                  },
                  "last_updated":"2018-09-07T20:25:59.697862Z"
               },
               "inventory":50,
               "minimum_purchase_limit":null,
               "show_inventory_amount_tt":null,
               "ticket_transfer_enabled":true,
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
            },
            {  
               "sold_out":false,
               "id":918,
               "created":"2018-06-15T16:43:21.286942Z",
               "updated":"2018-08-29T18:40:07.130429Z",
               "taxes":"0.00",
               "service_charges":"4.15",
               "total_price":"44.15",
               "event":554,
               "name":"Killer Cash Combo",
               "description":null,
               "sale_starts_on":"2018-06-15T16:43:21.279427Z",
               "sale_ends_on":"2018-10-06T01:00:00Z",
               "price":"40.00",
               "shipping_type":[  
                  "st_print"
               ],
               "base_inventory":918,
               "purchase_limit":8,
               "seat_permissions":[  

               ],
               "voucher_purchases_only":false,
               "stats":{  
                  "tickets":{  
                     "sold":0.0,
                     "sold_and_basket":0.0
                  },
                  "last_updated":"2018-09-07T20:25:59.717233Z"
               },
               "inventory":50,
               "minimum_purchase_limit":null,
               "show_inventory_amount_tt":null,
               "ticket_transfer_enabled":true,
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
            },
            {  
               "sold_out":false,
               "id":919,
               "created":"2018-06-15T16:43:21.373111Z",
               "updated":"2018-08-29T18:40:07.198052Z",
               "taxes":"0.00",
               "service_charges":"4.69",
               "total_price":"54.69",
               "event":554,
               "name":"SpeedPass",
               "description":null,
               "sale_starts_on":"2018-06-15T16:43:21.365267Z",
               "sale_ends_on":"2018-10-06T01:00:00Z",
               "price":"50.00",
               "shipping_type":[  
                  "st_print"
               ],
               "base_inventory":919,
               "purchase_limit":8,
               "seat_permissions":[  

               ],
               "voucher_purchases_only":false,
               "stats":{  
                  "tickets":{  
                     "sold":0.0,
                     "sold_and_basket":0.0
                  },
                  "last_updated":"2018-09-07T20:25:59.730088Z"
               },
               "inventory":50,
               "minimum_purchase_limit":null,
               "show_inventory_amount_tt":null,
               "ticket_transfer_enabled":true,
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
         "frontend_details_url":"https://beta.showpass.com/october-5-2018/",
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
         "local_starts_on":"2018-10-05T19:00:00-06:00",
         "info_collect_per_ticket":false,
         "initiate_purchase_button":"ipbd_buy_tickets",
         "post_purchase_message":null,
         "is_saved":false,
         "show_inventory_amount":null,
         "restrictions":[  

         ],
         "inventory":null,
         "collect_info_first_name":true,
         "collect_info_last_name":true,
         "collect_info_company":false,
         "collect_info_phone_number":true,
         "collect_info_email":true,
         "collect_info_job_title":false,
         "collect_info_student_number":false,
         "collect_info_home_address":false,
         "enforce_box_office_info_collection":false,
         "info_collection_type":"gict_standard_info"
      }
   ]
}
```
## 5. Shortcode [showpass_calendar]

You will need to add just this shortcode `[showpass_calendar]` and you will get complete calendar with all the events from the venue that is set in the admin page. If the venue(organisation ID) is not set, then you will get all events from the API.

This shortcode gives you calendar view with all events bound to their start dates for up to one year from today.

The calendar can also be set to Week view, where you are able to see the events week by week.

The calendar events have the all the Showpass event info that you need, and link directly to the event page (external link).

## 5.1. Page parameter

This shortcode recieves `page` parameter. If this parameter is set, it tells the plugin to use custom links for event's redirect.

Example:  `[showpass_calendar page="event-detail"]`  - This will tell the plugin that on click on event it will not redirect to external link for event, but it will redirect on the same website with this url: `website.main.url/event-detail?slug=event-slug`

The `page` parameter is `event-detail` in this example, but can be set to whatevet page you want.

## 5.2. theme_dark Paramerter
Add `theme_dark="true"` to use a dark theme for the calendar instead of the default light theme.

## 5.3. starting_date Paramerter
Add `starting_date="1-12-2018"` use day-month-year to set a starting date for the calendar on initial load
Format is day, month, year with no leading zeros

## 5.4. use_widget Paramerter
Add `use_widget="true"` if you want the ticket button to open the widget instead of redirecting to showpass or an event detail page use this

## 5.5. tags Paramerter
Add `tags="tag"` if you want to only display events with certain tags or categories. For best results use one word lowercase tags.

<!---## 5.5. Week and Month Parameters - Currently Disabled

This shortcode also receives parameters `week` and `month` if you want to disable a view.

If you want disable week view you will need to put `[showpass_calendar week="disabled"]`

If you want disable month view you will need to put `[showpass_calendar month="disabled"]`

If there is neither of this parameters, both views are enabled.-->

## 6. Shortcode [showpass_widget]

Use the showpass_widget shortcode to embed a button with the ticket widget on any page or post.

`[showpass_widget label="Button Label" slug="this-is-the-slug" class="button" keep_shopping="false" theme="dark"]`

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

##7 Shortcode [showpass_cart_button]
Add a button to initiate the shopping cart and checkout widget

`[showpass_cart_button]`

Will display `Shopping Cart (x)` inside the button, and the x variable will update with the number of items in a shopping cartx

## 8. Auto Query Parameter

You can automatically open the ticket widget as soon as a customer lands on any page on your site by using the `auto` query parameter

http://example.com?auto=this-is-the-slug

Just include the slug of your event in place of `this-is-the-slug`
