<?php

/**************************
* registering shortcode
**************************/


define('API_URL', 'https://www.myshowpass.com/api/public/events');

define('ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'&'));





function wpshp_get_data( $atts ) {

	$type = $_GET["type"];

	$event_id = $_GET["event_id"];
	$page_number = $_GET["page"];
	$query = $_GET['q'];

	/* get Organization ID that is configured in admin Showpass Event API page */
	$organization_id = get_option('option_organization_id');  

	/* passed in shortcode ex. type=single/list  ---> type can be single or list*/
	$type = $atts['type'];					

	if($type == "single"){
		if($event_id != NULL)
		{
			$final_api_url = API_URL . "/" . $event_id . "/";
		}
		else{
			echo "ERROR - Need event_id parameter in URL";
		}
	}

	if($type == "list"){
		$number_of_events_one_page = $atts['page_size'];

		$final_api_url = API_URL . '/?venue=' . $organization_id;

		if($number_of_events_one_page != NULL)
		{
			$final_api_url .= "&page_size=" . $number_of_events_one_page;
		}

		if($page_number != NULL)
		{
			$final_api_url .= "&page=" . $page_number;
		}

		if($query != NULL)
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

function showpass_get_event_date($date){

	$format_date = get_option('format_date');  

	if($format_date == "")
	{
		$format_date = "l F d, Y";
	}

	return date($format_date , strtotime($date));
}

/* Converting time */


function showpass_get_event_time($date){

	$format_time = get_option('format_time');  

	if($format_time == "")
	{
		$format_time = "g:iA";
	}

	return date($format_time , strtotime($date));
}

/* Function for next/prev page */

function showpass_get_events_next_prev($page)
{
	$page_link = ACTUAL_LINK . '&page=' . $page;

	return $page_link;
}




?>