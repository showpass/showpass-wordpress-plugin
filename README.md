# Showpass Events API plugin

## Description

This plugin is made for easier access to Showpass Events API data. It allows to you easily GET data from API in JSON format and manipulate with that in HTML website structure.

## Installation
1. Upload the `showpass-wordpress-plugin` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
2. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.

## Documentation

1. [Admin page](#1-admin-page)        
   1.1 [Configure parameters](#11-configure-parameters)    
2. [Shortcode](#2-shortcode)       
   2.1. [Adding shortcode and get data](#21-adding-shortcode-and-get-data)   
   2.2. [Type parameter](#22-type-parameter)   
   2.3. [Page size parameter](#23-page-size-parameter)   
   2.4. [Page number parameter](#24-page-number-parameter)   
   2.5. [Query parameter](#25-query-parameter)   



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
