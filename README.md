# Showpass Events API plugin ![Build Status](https://circleci.com/gh/showpass/showpass-wordpress-plugin/tree/master.svg?style=shield&circle-token=f3e19be350eadf845d1d0ded06a0391d6367f36f)

## Description

This plugin is made for easier access to Showpass Events API data. It allows to you easily GET data from API in JSON format and manipulate with that in HTML website structure.

## Installation
1. Upload the `showpass-wordpress-plugin` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
2. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.

## Documentation

1. [Admin page](#1-admin-page)        
   1.1. [Configure parameters](#11-configure-parameters)    
2. [Shortcode - [showpass_events]](#2-shortcode-showpass_events)       
   2.1. [Adding shortcode and get data](#21-adding-shortcode-and-get-data)   
   2.2. [Type parameter](#22-type-parameter)   
   2.3. [Page size parameter](#23-page-size-parameter)   
   2.4. [Page number parameter](#24-page-number-parameter)   
   2.5. [Other parameters](#25-other-parameters)   
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
   5.2. [Week/month parameter](#52-week-month-parameter)


## 1. Admin page

## 1.1. Configure parameters

After installation of the plugin, in Admin page there will be in Admin menu link for Showpass API. From the Showpass API admin page you can configure parameters.
* *Organization ID* - it is ID from venue that you want to get Events from.
ex. 5 - will get all events form venue with ID = 5.
* *Date format* - You can enter format that you want date to be converted in.
ex. "l F d, Y" - it will be in this format "Friday 21 April, 2017".
* *Time format* - You can enter format that you want time to be converted in.
ex. "g:iA" - it will be in this format "9:00AM".
More about date and time format <a href="http://php.net/manual/en/function.date.php">here</a>.

## 2. Shortcode [showpass_events]

## 2.1. Adding shortcode and get data

The shortcode returns JSON format data from API , so you can easily get it in page template with
`<?php $data = do_shortcode('[showpass_events]'); ?>` .

Because it is JSON data , for manupulating with it, you need to decode it `$data = json_decode($data)` .

It will be recieved all data from API for the venue that is set from Admin Page ( organization ID ).

Also , there are few parameters that you can send it to the shortcode.

## 2.2. Type Parameter

Type parameter is required in shortcode to works.  You have `type="single"` for getting one specified event or `type="list"` to get all data(events) from the venue that is set from admin page.

### `type="single"`

This type `[showpass_events type="single"]` will get the data from specified event that will be send it through the `event_id` from url.

ex. `www.website.com/?event=123` or `www.website.com/?event=event_slug` - will get all data for the event with ID = 123 or with slug = event_slug . So `?event` in url is required for `type="single"` type of shortcode.
`event` parameter receive event ID or event slug (id or slug from API).

### `type="list"`

This type `[showpass_events type="list"]` will get all the data from venue with ID that is set from Admin page ( organization ID ).

## 2.3. Page size parameter

This parameter gives you choice for how many events do you want to show on one page. This parameter is not required, and if you don't pass in shortcode, the default number of events on one page is 20.

`[showpass_events type='list' page_size='5']` - It will get only 5 events on one page.

## 2.4. Page number parameter

This parameter you can use to jump on page number that you want.

For example if you have in some venue 30 events, and you have set `page_size="5"` , the API will returns to you only 5 events on one page and you will have 6 pages with 5 events on each. So, with this parameter you can easily jump to the page that you want with passing the parameter through the website URL.

ex. `www.website.com/?page_number=4` - will get all data (events) from page 4.

## 2.5. Tags Parameter

This parameter you can use the shortcode to return events with a certain category

`[showpass_events type='list' page_size='5' tags='featured']` - It will get only 5 events on one page with the `featured` tag

## 2.7. Other parameters

There are few parameters that API can receive and this plugin is compatible for all of these parameters. You can pass it through the URL and you will get the data from API with those parameters.

*** Examples

* *query parameter* - `?q=something`

This parameter is for search event. You need to pass it through website url `?q=something` and it will get all events that have "something" in their content.

ex. `www.website.com/?q=something` .

* *tags parameter* - `?tags=Rock`

This parameter is for filtering events through the tags. You need to pass throught the website url `?tags=tag_you_want` and it will get all events which has that tag(category). Also works with many tags separated by comma

ex. `www.website.com/?tags=tag_one,tag_two`

* *other parameters* - `?stars_on__gte , ?starts_on__lt , ?ends_on__gte , ?ends_on__lt` etc.

- gte - greater than or equal

- lt - less then

These parameters receives date.

## 3. Functions      

## 3.1. Showpass get Event Date

* *`showpass_get_event_date($date, $zone)`* - This is function for getting date in the timezone from the event.

`$date` and `$zone` - these are parameters that you need to pass to function. They will be in data from API output.

ex. Event start date - `showpass_get_event_date($event->starts_on, $event->timezone)`

where `starts_on` and `timezone` are parameters received from API for the event.

The date will be showed on the website in format that is set from Showpass Admin Page.

* example `<?php echo showpass_get_event_date($event->starts_on, $event->timezone); ?>`

It will print the *date* when starts the event (ex. Friday 05 May, 2017).

## 3.2. Showpass get Event Time

* *`showpass_get_event_time($date, $zone)`* - This is function for getting time in the timezone from the event.

`$date` and `$zone` - these are parameters that you need to pass to function. They will be in data from API output.

ex. Event start time - `showpass_get_event_date($event->starts_on, $event->timezone)`

where `starts_on` and `timezone` are parameters received from API for the event.

The time will be showed on the website in format that is set from Showpass Admin Page.

* example `<?php echo showpass_get_event_time($event->starts_on, $event->timezone); ?>`

It will print the *time* when starts the event (ex. 9:00AM).

## 3.3. Showpass get Timezone

* *`showpass_get_timezone_abbr($timezone)`* - This is function for getting timezone (offset) from the event.

`timezone` - this parameter you need pass to function. It is event timezone from API.

* example `<?php echo showpass_get_timezone_abbr($event->timezone); ?>`

It will print the timezone of event (MDT, PDT ect.).

## 3.4. Showpass get price range

* *`showpass_get_price_range($ticket_types)`* - This is function for getting a price range for the events tickets.

`ticket types` - this parameter you need pass to function. It is event ticket type object

* example `<?php echo showpass_get_price_range($event->ticket_types); ?>`

It will print either FREE, $5-$30 or $30

## 3.5. Showpass get Previous or Next page

* *`showpass_get_events_next_prev($page)`* - This function is for pagination of the pages. This function sets up the `$page` parameter.

ex. You will have (the API will receive) 5 pages with 6 events on each page. So , for pagination you will use this function.

`$page` - it is number of the page that you will get it from returned API data.

`showpass_get_events_next_prev($event->next_page_number)` or `showpass_get_events_next_prev($event->previous_page_number)` , depends on pagination.

`next_page_number` or `previous_page_number` is number , so you can easily put number of whatever page you want.


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

		{  
		   "id":123,
		   "created":"date and time of creation",
		   "updated":"date and time of update",
		   "slug":"event_slug",
		   "name":"Event Name",
		   "subtitle":"Event Subtitle",
		   "venue":{  
		      "id":venue_id,
		      "slug":"venue_slug",
		      "name":"Venue Name",
		      "twitter":"http://twitter.com/venueTwitter",
		      "facebook":"https://www.facebook.com/venueFacebook",
		      "web_address":"http://venuewebsite.com/contact/",
		      "description":"Description of venue",
		      "phone":Venue phone,
		      "street_name":"Venue Street",
		      "city":"Venue City",
		      "avatar":"Venue Avatar Picture",
		      "currency":"Currency"
		   },
		   "location":{  
		      "id":location_id,
		      "street_name":"Street",
		      "city":"City",
		      "province":"province code",
		      "postal_code":"postal code",
		      "position":"longitude, latitude",
		      "venue":venue_id,
		      "name":"venue name"
		   },
		   "starts_on":"Start date and time of the event",
		   "ends_on":"Ending date and time of the event",
		   "opens_at":opens_at,
		   "terms":"terms",
		   "description":"Event description",
		   "venue_fee":"venue_fee",
		   "getqd_fee":"getqd_fee",
		   "getqd_fee_added":"qetqd_fee_added",
		   "image":"URL Event image",
		   "thumbnail":"URL Event thumnail image",
		   "is_published":true/false,
		   "is_published_for_sellers":true/false,
		   "is_featured":true/false,
		   "password_protected":true/false,
		   "facebook_id":"facebook_id",
		   "ticket_types":[  
		   		ticket info
		   ],
		   "image_medium":"URL Event medium Image",
		   "assigned_space":null,
		   "frontend_details_url":"frontend_details_url",
		   "no_ticket_types_message":null,
		   "image_banner_xl":"URL Image banned XL",
		   "image_lg_square":"URL Image large square",
		   "image_stretch_banner_lg":"URL Image Stretch large banner",
		   "social_share_enabled":true/false,
		   "social_share_reward":"1.00",
		   "timezone":"Event timezone",
		   "currency":"currency"
		}



## 4.2. List events

		{  
		   "count":34,
		   "next":"https://www.myshowpass.com/api/public/events/?venue=venue_id&page=page_number",
		   "previous":null or url if has previous,
		   "next_page_number": next page number ex(2),
		   "previous_page_number":null or number if has previous,
		   "page_number":number of current page,
		   "num_pages": number of how many pages are,
		   "next_list":[  
		      list of next (array of numbers)
		   ],
		   "previous_list":[  
		      list of prev (array of numbers)
		   ],
		   "results":[
   			{  
			   "id":123,
			   "created":"date and time of creation",
			   "updated":"date and time of update",
			   "slug":"event_slug",
			   "name":"Event Name",
			   "subtitle":"Event Subtitle",
			   "venue":{  
			      "id":venue_id,
			      "slug":"venue_slug",
			      "name":"Venue Name",
			      "twitter":"http://twitter.com/venueTwitter",
			      "facebook":"https://www.facebook.com/venueFacebook",
			      "web_address":"http://venuewebsite.com/contact/",
			      "description":"Description of venue",
			      "phone":Venue phone,
			      "street_name":"Venue Street",
			      "city":"Venue City",
			      "avatar":"Venue Avatar Picture",
			      "currency":"Currency"
			   },
			   "location":{  
			      "id":location_id,
			      "street_name":"Street",
			      "city":"City",
			      "province":"province code",
			      "postal_code":"postal code",
			      "position":"longitude, latitude",
			      "venue":venue_id,
			      "name":"venue name"
			   },
			   "starts_on":"Start date and time of the event",
			   "ends_on":"Ending date and time of the event",
			   "opens_at":opens_at,
			   "terms":"terms",
			   "description":"Event description",
			   "venue_fee":"venue_fee",
			   "getqd_fee":"getqd_fee",
			   "getqd_fee_added":"qetqd_fee_added",
			   "image":"URL Event image",
			   "thumbnail":"URL Event thumnail image",
			   "is_published":true/false,
			   "is_published_for_sellers":true/false,
			   "is_featured":true/false,
			   "password_protected":true/false,
			   "facebook_id":"facebook_id",
			   "ticket_types":[  

			   ],
			   "image_medium":"URL Event medium Image",
			   "assigned_space":null,
			   "frontend_details_url":"frontend_details_url",
			   "no_ticket_types_message":null,
			   "image_banner_xl":"URL Image banned XL",
			   "image_lg_square":"URL Image large square",
			   "image_stretch_banner_lg":"URL Image Stretch large banner",
			   "social_share_enabled":true/false,
			   "social_share_reward":"1.00",
			   "timezone":"Event timezone",
			   "currency":"currency"
			}
	            ] /// events
	        }

## 5. Shortcode [showpass_calendar]

You will need to add just this shortcode `[showpass_calendar]` and you will get complete calendar with all the events from venue that is set in admin page above. If the venue(organisation ID) is not set, then you will get all events from API.

This shortcode gives you calendar view with all events binded in the dates of their start. You are able to go throuth the calendar and see the events that are one year after today.

Also , you can make calendar in Week view type, where you are able to see the events week by week.

The events have the all infos that you need, and you can go on the event page (external link) through the calendar.

## 5.1. Page parameter

This shortcode recieve `page` parameter. If this parameter is set, you tell the plugin to use custom links for event's redirect.

Example:  `[showpass_calendar page="event-detail"]`  - This will tell the plugin that on click on event it will not redirect to external link for event, but it will redirect on the same website with this url: `website.main.url/event-detail?slug=event-slug`

The `page` parameter is `event-detail` in this case, and it will be whatevet page you want.

## 5.2. Week/Month Parameter

This shortcode also recieve parameters `week` and `month` if you want disable some view...

If you want disable week view you will need to put `[showpass_calendar week="disabled"]`

If you want disable month view you will need to put `[showpass_calendar month="disabled"]`

If there is none of this parameters, there will be both views.

Enyoj!