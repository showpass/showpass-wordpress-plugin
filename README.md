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
		   "created":"2015-01-29T00:39:55.042Z",
		   "updated":"2017-04-17T01:20:46.206Z",
		   "slug":"evol-intent-jfb",
		   "name":"EVOL INTENT + JFB",
		   "subtitle":"",
		   "venue":{  
		      "id":12,
		      "slug":"tenx-nightclub",
		      "name":"Ten Nightclub",
		      "twitter":"http://twitter.com/nightclubten",
		      "facebook":"https://www.facebook.com/TENxnightclub",
		      "web_address":"http://tenxnightclub.com/contact/",
		      "description":"What do you get when the top 5 Promoters in Calgary all get under one roof ?? \r\n\r\nMAGIC!!! \r\n\r\n- BOODANG / SP / JAMTIGHT / TRANSMISSION / DUBSAC\r\n",
		      "phone":78,
		      "street_name":" 1140 10 Ave SW",
		      "city":"Calgary",
		      "avatar":"https://showpass-live.s3.amazonaws.com/media/images/venues/tenx-nightclub/avatars/1501488_590540317699134_243302273_o.png",
		      "currency":"CAD"
		   },
		   "location":{  
		      "id":7,
		      "street_name":" 1140 10 Ave SW",
		      "city":"Calgary",
		      "province":"AB ",
		      "postal_code":"T2R 0B6",
		      "position":"51.04426079556749,-114.08835053443909",
		      "venue":12,
		      "name":"TenX Nightclub"
		   },
		   "starts_on":"2015-02-28T04:00:00Z",
		   "ends_on":"2015-02-28T09:30:00Z",
		   "opens_at":null,
		   "terms":"",
		   "description":"Event description",
		   "venue_fee":"0.00",
		   "getqd_fee":"0.05",
		   "getqd_fee_added":"2.75",
		   "image":"https://showpass-live.s3.amazonaws.com/media/images/events/tenx-nightclub/images/ev_rad1200x1200.jpg",
		   "thumbnail":"https://showpass-live.s3.amazonaws.com/media/images/events/tenx-nightclub/thumbnails/ev_rad1200x1200.jpg",
		   "is_published":true,
		   "is_published_for_sellers":true,
		   "is_featured":false,
		   "password_protected":false,
		   "facebook_id":"743330102441150",
		   "ticket_types":[  

		   ],
		   "image_medium":"https://showpass-live.s3.amazonaws.com/media/images/events/tenx-nightclub/img-medium/ev_rad1200x1200.jpg",
		   "assigned_space":null,
		   "frontend_details_url":"https://www.myshowpass.com/evol-intent-jfb/",
		   "no_ticket_types_message":null,
		   "image_banner_xl":"https://showpass-live.s3.amazonaws.com/media/images/events/tenx-nightclub/img-banner-xl/ev_rad1200x1200.jpg",
		   "image_lg_square":"https://showpass-live.s3.amazonaws.com/media/images/events/tenx-nightclub/img-lg-square/ev_rad1200x1200.jpg",
		   "image_stretch_banner_lg":"https://showpass-live.s3.amazonaws.com/media/images/events/tenx-nightclub/img-stretch-banner-lg/ev_rad1200x1200.jpg",
		   "social_share_enabled":false,
		   "social_share_reward":"1.00",
		   "timezone":"US/Mountain",
		   "currency":"CAD"
		}