<?php

/**************************
* registering shortcode
**************************/

function wpshp_get_data( $atts ) {

	$type = $_GET["type"];

	$event_id = $_GET["event_id"];

	/* get MAIN API URL that is configured in admin Showpass Event API page */
	$main_api = get_option('option_main_api_url');  

	/* passed in shortcode ex. venue=5 */
	$api_call = $atts['api_call'];					

	$final_api_url = $main_api . $event_id;

	/* get data from API */
	$data = CallAPI($final_api_url . '/');   

	$data = json_decode($data);

	// var_dump($data->description);

	return $data->description;
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


?>