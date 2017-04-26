<?php

/**************************
* registering shortcode
**************************/


define('API_URL', 'https://www.myshowpass.com/api/public/events');

// define('ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'&'));
define('ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'?'));





function wpshp_get_data( $atts ) {

	if(isset($_GET["type"]))
	{
		$type = $_GET["type"];
	}

	if(isset($_GET['event']))
	{
		$event = $_GET["event"];
	}

	if(isset($_GET['page_number']))
	{
		$page_number = $_GET["page_number"];
	}

	if(isset($_GET['q'])){
		$query = $_GET['q'];
	}

	/* get Organization ID that is configured in admin Showpass Event API page */
	$organization_id = get_option('option_organization_id');  

	/* passed in shortcode ex. type=single/list  ---> type can be single or list*/
	$type = $atts['type'];			

	$final_api_url = API_URL;		

	if($type == "single"){
		if(isset($_GET['event']))
		{
			$final_api_url = API_URL . "/" . $event . "/";
		}
		else{
			echo "ERROR - Need event parameter in URL";
		}
	}

	if($type == "list"){

		$final_api_url = API_URL . '/?venue=' . $organization_id;

		if(isset($atts['page_size']))
		{
			$number_of_events_one_page = $atts['page_size'];
			$final_api_url .= "&page_size=" . $number_of_events_one_page;
		}


		if(isset($_GET['page_number']))
		{
			$final_api_url .= "&page=" . $page_number;
		}

		if(isset($_GET['q']))
		{
			$final_api_url .= "&q=" . $query;
		}

	}

	if($type == NULL)
	{
		echo "ERROR - Please enter type parameter in shortcode";
	}

	/* get data from API */
	$data = CallAPI($final_api_url);   

	return $data;
}

add_shortcode( 'showpass_events', 'wpshp_get_data' );



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




?>