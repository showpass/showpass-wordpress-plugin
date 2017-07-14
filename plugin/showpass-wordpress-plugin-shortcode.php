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
	} else if ($type == "list"){

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
			else if($parameter == 'slug')
			{
				$final_api_url .= "&slug=";
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

		if(isset($atts['tags']))
		{
			$tags = $atts['tags'];
			$final_api_url .= "&tags=" . $tags;
		}

		if(isset($atts['ends_on__gte']))
		{
			$ends_on__gte = $atts['ends_on__gte'];
			$final_api_url .= "&ends_on__gte=" . $ends_on__gte;

		}

		if(isset($atts['ends_on__lt']))
		{
			$ends_on__lte = $atts['ends_on__lt'];
			$final_api_url .= "&ends_on__lt=" . $ends_on__lte;
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

/* GET PRICE RANGE FOR TICKETS */
function showpass_get_price_range ($data) {
	$ticket_types = $data;
	if (!$ticket_types) {
		return null;
	}

	$min = 999999999;
	$max = 0;

	foreach ($ticket_types as $ticket) {
		if ($ticket->price < $min) {
			$min = $ticket->price;
		}
		if ($ticket->price > $max) {
			$max = $ticket->price;
		}
	}
	if ($max === 0) {
		return 'FREE';
	} else if ($max == $min) {
		return '$'.$min;
	} else if ($min === 0) {
		return '$0 - $'.$max;
	} else {
		return '$'.$min.' - $'.$max;
	}
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


////////////////////////////////////////////////////////////////////////
//                    calendar shortcode
///////////////////////////////////////////////////////////////////////


function wpshp_calendar($atts)
{
	// registering style and script
	wp_enqueue_style('showpass-calendar-style', plugins_url( '/css/style.css', __FILE__ ), array(), '1.0.0', 'all' );

	/////////////////////////////////////////////////////////

	$organization_id = get_option('option_organization_id');


	if(isset($atts["page"]))
	{
		$page = $atts["page"];
	}

	if(isset($atts["month"]))
	{
		$month_enable = $atts["month"];
	}

	if(isset($atts["week"]))
	{
		$week_enable = $atts["week"];
	}

	$current_month = date('M');
	$current_month_number = date('n');
	$current_month_prev = date('n') - 1;
	$current_month_next = date('n') + 1;
	$current_year = date('Y');
	$current_day = date('j');
	$prev_week = (int)$current_day - 7;
	$next_week = (int)$current_day + 7;
	$month = date('m');
	$first = '01-' . $month . "-" . $current_year;
	$first_of_the_month_day = date('N', strtotime($first));
	$days = date('t');

	$array_days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
	$array_months = ['', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
	
	$html = "<div class='showpass-calendar'>";


		$html .= "<div class='showpass-month-view showpass-view active'>Month View</div>";	




		$html .= "<div class='showpass-week-view showpass-view'>Week View</div>";


	$html .= "<input type='hidden' id='page_type' value='" . $page . "' />";
	$html .= "<input type='hidden' id='current_day' value='" . $current_day . "' />";
	$html .= "<input type='hidden' id='current-month' value='" . $current_month_number . "' />";
	$html .= "<input type='hidden' id='site_url' value='" . get_home_url() . "' />";
	$html .= "<input type='hidden' id='venue_id' value='" . $organization_id . "' />";
	$html .= "<input type='hidden' id='month_enable' value='" . $month_enable . "' />";
	$html .= "<input type='hidden' id='week_enable' value='" . $week_enable . "' />";

	
	
		$html .= "<div class='showpass-calendar-month'><div class='showpass-prev-month' data-month='" .$current_month_prev . "'></div><p class='showpass-month'>" . $current_month ."</p> <p class='showpass-year'>" . $current_year ."</p><div class='showpass-next-month' data-month='" . $current_month_next . "'></div></div>";
	
	
	
	
		$html .= "<div class='showpass-calendar-week'><div class='showpass-prev-week' data-prev-week='" . $prev_week . "'></div><p class='showpass-week'>Week of " . $current_day ." of " . $current_month . "</p><div class='showpass-next-week' data-next-week='" . $next_week . "'></div> </div>";
	



	for($i = 0; $i < sizeof($array_days); $i++)
	{
		$html .= "<div class='showpass-calendar-head'>" . $array_days[$i] ."</div>";

	}
	$html .= "<div class='showpass-calendar-body'>";

	if($first_of_the_month_day == 7){
		for($i = (int)$first_of_the_month_day - 6 ; $i <= (int)$days; $i++)
		{

			$html .= "<div class='showpass-calendar-item'>" . $i ."</div>";
		}
	}
	else{
		for($i = ((int)$first_of_the_month_day * (-1)) + 1 ; $i <= (int)$days; $i++)
		{
			if($i < 1)
			{
				$html .= "<div class='showpass-calendar-item'></div>";
			}
			else{
				$html .= "<div class='showpass-calendar-item'><div class='day_number_showpass'>" . $i ."</div></div>";
			}
		}
	}





	$html .= "</div></div>";

	$html .= "<div class='loader_home'><div class='loader'>Loading...</div></div>";

	return $html;
}

add_shortcode('showpass_calendar','wpshp_calendar');


function showpass_scripts(){
	wp_dequeue_script('jquery');
	wp_register_script('jquery-showpass', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',false, '3.2.1');
	wp_register_script('showpass-calendar-script', plugins_url( '/js/main.js', __FILE__ ), array('jquery'), '1.0.0', true );
	wp_register_script('timezone-showpass', plugins_url( '/js/timezone.js', __FILE__ ), array(),false, '1.0.1');
	wp_register_script('moment-timezone-showpass', plugins_url( '/js/moment-timezone.js', __FILE__ ), array(),false, '1.0.2');
	wp_register_script('dateformat-timezone-showpass', plugins_url( '/js/dateFormat.js', __FILE__ ), array(),false, '1.0.3');
	


	wp_enqueue_script('jquery-showpass');
	wp_enqueue_script('dateformat-timezone-showpass');
	wp_enqueue_script('showpass-calendar-script');
	wp_enqueue_script('jquery');
	wp_enqueue_script('moment-timezone-showpass');
	wp_enqueue_script('timezone-showpass');
}
add_action( 'init', 'showpass_scripts' );




?>
