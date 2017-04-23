# Showpass Events API plugin

## Description

This plugin is made for easier access to Showpass Events API data. It allows to you easily GET data from API in JSON format and manipulate with that in HTML website structure.

## Installation
1. Upload the `showpass-wordpress-plugin` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
2. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.

## Documentation

1. [Admin page](#1-admin-page)        
   1.1. [Configure parameters](#11-configure-parameters)    
2. [Shortcode](#2-shortcode)       
   2.1. [Adding shortcode and get data](#21-adding-shortcode-and-get-data)   
   2.2. [Type parameter](#22-type-parameter)   
   2.3. [Page size parameter](#23-page-size-parameter)   
   2.4. [Page number parameter](#24-page-number-parameter)   
   2.5. [Query parameter](#25-query-parameter)   
3. [Functions](#3-functions)        
   3.1. [Showpass get Event Date](#31-showpass-get-event-date)    
   3.2. [Showpass get Event Time](#32-showpass-get-event-time)    
   3.3. [Showpass get Previous or next page](#33-showpass-get-previous-or-next-page)   
4. [JSON Data](#4-json-data)     
   4.1. [Single event](#41-single-event)
   4.2. [List events](#42-list-events)



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

## 2. Shortcode

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

ex. `www.website.com/&event_id=123` - will get all data for the event with ID = 123 . So `&event_id` in url is required for `type="single"` type of shortcode.

### `type="list"`

This type `[showpass_events type="list"]` will get all the data from venue with ID that is set from Admin page ( organization ID ).

## 2.3. Page size parameter

This parameter gives you choice for how many events do you want to show on one page. This parameter is not required, and if you don't pass in shortcode, the default number of events on one page is 20.

`[showpass_events type='list' page_size='5']` - It will get only 5 events on one page.

## 2.4. Page number parameter

This parameter you can use to jump on page number that you want.

For example if you have in some venue 30 events, and you have set `page_size="5"` , the API will returns to you only 5 events on one page and you will have 6 pages with 5 events on each. So, with this parameter you can easily jump to the page that you want with passing the parameter through the website URL.

ex. `www.website.com/&page=4` - will get all data (events) from page 4.

## 2.5. Query parameter

This parameter is for search event. You need to pass it through website url `&q=something` and it will get all events that have "something" in their content.

ex. `www.website.com/&q=something` .


## 3. Functions      

## 3.1. Showpass get Event Date

* *`showpass_get_event_date($date, $zone)`* - This is function for getting date in the timezone from the event.

`$date` and `$zone` - these are parameters that you need to pass to function. They will be in data from API output.

ex. Event start date - `showpass_get_event_date($event->starts_on, $event->timezone)` 

where `starts_on` and `timezone` are parameters received from API for the event.

The date will be showed on the website in format that is set from Showpass Admin Page.

## 3.2. Showpass get Event Time

* *`showpass_get_event_time($date, $zone)`* - This is function for getting time in the timezone from the event.

`$date` and `$zone` - these are parameters that you need to pass to function. They will be in data from API output.

ex. Event start time - `showpass_get_event_date($event->starts_on, $event->timezone)` 

where `starts_on` and `timezone` are parameters received from API for the event.

The time will be showed on the website in format that is set from Showpass Admin Page.

## 3.3. Showpass get Previous or Next page

* *`showpass_get_events_next_prev($page)`* - This function is for pagination of the pages. This function sets up the `$page` parameter.

ex. You will have (the API will receive) 5 pages with 6 events on each page. So , for pagination you will use this function.

`$page` - it is number of the page that you will get it from returned API data.

`showpass_get_events_next_prev($event->next_page_number)` or `showpass_get_events_next_prev($event->previous_page_number)` , depends on pagination.

`next_page_number` or `previous_page_number` is number , so you can easily put number of whatever page you want.


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
