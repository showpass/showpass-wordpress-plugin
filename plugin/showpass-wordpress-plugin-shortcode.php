<?php

/**************************
* registering shortcode
**************************/


define('API_URL', 'https://www.myshowpass.com/api');

// define('ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'&'));
define('ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'?'));


define('API_PUBLIC_EVENTS', API_URL . '/public/events');


function wpshp_get_data( $atts ) {

	/* get Organization ID that is configured in admin Showpass Event API page */
	$organization_id = get_option('option_organization_id');  


	if(isset($atts["type"]))
	{
		$type = $atts["type"];
	}
	else{
		$type = NULL;
	}

	if($type == NULL)
	{
		echo "ERROR - Please enter type parameter in shortcode";
	}

	/* passed in shortcode ex. type=single/list  ---> type can be single or list*/	

	$final_api_url = API_PUBLIC_EVENTS;		

	if($type == "single"){
		if(isset($_GET['id']))
		{
			$final_api_url = API_PUBLIC_EVENTS . "/" . $_GET['id'] . "/";
		}
		else if(isset($_GET['slug']))
		{
			$final_api_url = API_PUBLIC_EVENTS . "/" . $_GET['slug'] . "/";
		}
		else{
			echo "ERROR - Need parameter in URL (id or slug)";
		}
	}

	if($type == "list"){

		$final_api_url = API_PUBLIC_EVENTS . '/?venue=' . $organization_id;

		$parameters = $_GET;


		foreach ($parameters as $parameter => $value) {
			# code...
			if($parameter == 'q' || $parameter == 'tags')
			{
				$final_api_url .= "&" . $parameter . "=" . utf8_urldecode($value);
			}
			else if($parameter == 'page_number')
			{
				$final_api_url .= "&page=" . $value;
			}
			else {
				$final_api_url .= "&" . $parameter . "=" . $value;
			}
		}


		if(isset($atts['page_size']))
		{
			$number_of_events_one_page = $atts['page_size'];
			$final_api_url .= "&page_size=" . $number_of_events_one_page;
		}

	}

	$data = CallAPI($final_api_url);   


	// if($type == "list"){
	// 	$final = getListTemplate($data);
	// }

	// if($type == "single"){
	// 	$final = getSingleTemplate($data);
	// }


	/* get data from API */


    return $data;  

	// return $final;
}

add_shortcode( 'showpass_events', 'wpshp_get_data' );


 function utf8_urldecode($str) {
    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urlencode($str));
    return html_entity_decode($str,null,'UTF-8');;
  }


/* making connection and taking the data from API */
function CallAPI($url, $method = "GET", $data = false)
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}


/* Converting date */

function showpass_get_event_date($date, $zone){

	$format_date = get_option('format_date');  

	$datetime = new Datetime($date); // current time = server time
	$otherTZ  = new DateTimeZone($zone);
	$datetime->setTimezone($otherTZ);


	if($format_date == "")
	{
		$format_date = "l F d, Y";
	}

	$new_date = $datetime->format($format_date);

	return $new_date;
}

/* Converting time */


function showpass_get_event_time($date, $zone){

	$format_time = get_option('format_time');  

	$datetime = new Datetime($date); // current time = server time
	$otherTZ  = new DateTimeZone($zone);
	$datetime->setTimezone($otherTZ);

	if($format_time == "")
	{
		$format_time = "g:iA";
	}
	
	$new_date = $datetime->format($format_time);

	return $new_date;
}

function showpass_get_timezone_abbr($timezone)
{

	date_default_timezone_set($timezone);
	
	$new_date = date('T');

	return $new_date;
}

/* Function for next/prev page */

function showpass_get_events_next_prev($page)
{
	if(isset($_GET['q']))
	{
		$page_link = ACTUAL_LINK . '?page_number=' . $page . "&q=" . $_GET['q'];
	}
	else{
		$page_link = ACTUAL_LINK . '?page_number=' . $page;
	}

	return $page_link;
}


function getSingleTemplate($data)
{

	$event = json_decode($data);

	$html = "";

	$html = "<div><p>" . $event->name . "</p></div>";

	return $html;
}

function getListTemplate($data)
{

	$events = json_decode($data);

	$html = "";

	foreach ($events->results as $key => $event) {
		
		$html .= "<div><p>" . $event->name . "</p></div>";
	
	}


	return $html;
}




?>