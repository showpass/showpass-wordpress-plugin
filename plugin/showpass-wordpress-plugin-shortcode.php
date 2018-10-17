<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
/**************************
* registering shortcode
**************************/

define('API_URL', 'http://local.showpass.com:8000/api');
// define('ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'&'));
define('ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'?'));
define('API_PUBLIC_EVENTS', API_URL . '/public/events');
define('API_PUBLIC_PRODUCTS', API_URL . '/public/products');

function wpshp_get_data( $atts ) {

	/* get Organization ID that is configured in admin Showpass Event API page */
	$organization_id = get_option('option_organization_id');

	if(isset($atts["type"])) {
		$type = $atts["type"];
	} else {
		$type = "event-list";
	}

	if($type == NULL) {
		echo "ERROR - Please enter type parameter in shortcode";
	}

	if(isset($atts["template"])){
		$template = $atts["template"];
	} else {
		$template = "default";
	}

	/* passed in shortcode ex. type=single/list  ---> type can be single or list*/

	$final_api_url = API_PUBLIC_EVENTS;

	if($type == "event-detail" || $type == "single") {
		$filepath = 'inc/default-detail.php';
		if(isset($_GET['id'])) {
			$final_api_url = API_PUBLIC_EVENTS . "/" . $_GET['id'] . "/";
		} else if (isset($_GET['slug'])) {
			$final_api_url = API_PUBLIC_EVENTS . "/" . $_GET['slug'] . "/";
		} else {
			return "ERROR - Need parameter in URL (id or slug)";
		}
	} else if ($type == "event-list" || $type == "list") {

		if($template == "list"){
			$filepath = 'inc/default-list.php';
		}
		else {
			$filepath = 'inc/default-grid.php';
		}

		$final_api_url = API_PUBLIC_EVENTS . '/?venue__in=' . $organization_id;

		# get any query parameters from URL
		$parameters = $_GET;
		foreach ($parameters as $parameter => $value) {
			if($parameter == 'q') {
				$final_api_url .= "&" . $parameter . "=" . utf8_urldecode($value);
			} else if($parameter == 'tags') {
				$tags = utf8_urldecode($value);
			} else if($parameter == 'page_number') 			{
				$final_api_url .= "&page=" . $value;
			} else if($parameter == 'slug') {
				$final_api_url .= "&slug=" . $value;
			} else if($parameter == 'starts_on__gte') {
				$final_api_url .= "&starts_on__gte=" . $value;
			} else if($parameter == 'ends_on__lt') {
				$final_api_url .= "&ends_on__lt=" . $value;
			} else if($parameter == 'ordering') {
				$final_api_url .= "&ordering=" . $value;
			}
		}

		if (isset($atts['tags']) && $tags != '') {
			$tags .= ','.$atts['tags'];
			$final_api_url .= "&tags_exact=" . $tags;
		} else if (isset($atts['tags'])) {
			$tags = $atts['tags'];
			$final_api_url .= "&tags_exact=" . $tags;
		}

		if (isset($atts['page_size'])) {
			$number_of_events_one_page = $atts['page_size'];
			$final_api_url .= "&page_size=" . $number_of_events_one_page;
		} else {
			$number_of_events_one_page = 8;
			$final_api_url .= "&page_size=" . $number_of_events_one_page;
		}

		if(isset($atts['ends_on__gte'])) {
			$ends_on__gte = $atts['ends_on__gte'];
			$final_api_url .= "&ends_on__gte=" . $ends_on__gte;
		}

		if(isset($atts['ends_on__lt'])) {
			$ends_on__lte = $atts['ends_on__lt'];
			$final_api_url .= "&ends_on__lt=" . $ends_on__lte;
		}

		if(isset($atts["page"])) {
			$detail_page = $atts["page"];
		} else {
			$detail_page = NULL;
		}

		if(isset($atts['condensed'])) {
			$condensed = $atts['condensed'];
			$final_api_url .= "&condensed=" . $condensed;
		}

	}

	echo $final_api_url;
	$data = CallAPI($final_api_url);

	// decode data to to append related events to process properly
	$data = json_decode($data, TRUE);

	// find related events if any
	if ($data && $data['has_related_events'] && $type == "single") {
		$related_url = API_URL . '/public/events/' . $_GET['slug'] . '/related/?venue_id=' . $organization_id;
		$related_data = CallAPI($related_url);
		$related_data = json_decode($related_data, TRUE);
		$data['related_events'] = $related_data['results'];
	}

	// encode json data to return properly
	$data = json_encode($data);

	if ($template == "data") {
		return $data;
	} else {

		ob_start();
	  include($filepath);
	  $content = ob_get_clean();
	  return $content;

	}
}

add_shortcode( 'showpass_events', 'wpshp_get_data' );

function wpshp_get_product_data( $atts ) {

	/* get Organization ID that is configured in admin Showpass Event API page */
	$organization_id = get_option('option_organization_id');

	if(isset($atts["type"])) {
		$type = $atts["type"];
	} else {
		$type = "product-list";
	}

	if($type == NULL) {
		echo "ERROR - Please enter type parameter in shortcode";
	}

	if(isset($atts["template"])){
		$template = $atts["template"];
	} else {
		$template = "default";
	}

	/* passed in shortcode ex. type=single/list  ---> type can be single or list*/

	$final_api_url = API_PUBLIC_PRODUCTS;

	if ($type == "product-list") {

		if($template == "list"){
			$filepath = 'inc/default-product-list.php';
		}
		else {
			$filepath = 'inc/default-product-grid.php';
		}

		$final_api_url = API_PUBLIC_PRODUCTS . '/?venue_id=' . $organization_id;
		$parameters = $_GET;

		foreach ($parameters as $parameter => $value) {
			# code...
			if($parameter == 'q' || $parameter == 'tags') {
				$final_api_url .= "&" . $parameter . "=" . utf8_urldecode($value);
			} else if($parameter == 'page_number') 			{
				$final_api_url .= "&page=" . $value;
			} else {
				$final_api_url .= "&" . $parameter . "=" . $value;
			}
		}

		if (isset($atts['page_size'])) {
			$number_of_events_one_page = $atts['page_size'];
			$final_api_url .= "&page_size=" . $number_of_events_one_page;
		}
		else {
			$number_of_events_one_page = 8;
			$final_api_url .= "&page_size=" . $number_of_events_one_page;
		}
	}

	$data = CallAPI($final_api_url);

	if ($template == "data") {
		return $data;
	} else {

	  ob_start();
	  include($filepath);
	  $content = ob_get_clean();
	  return $content;

	}

}

add_shortcode( 'showpass_products', 'wpshp_get_product_data' );

function utf8_urldecode($str) {
  $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urlencode($str));
  return html_entity_decode($str,null,'UTF-8');;
}

/* making connection and taking the data from API */
function CallAPI($url, $method = "GET", $data = false) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

/* Converting date */

function showpass_get_event_date ($date, $zone) {

	if ($date && $zone) {
		$format_date = get_option('format_date');
		$datetime = new Datetime($date); // current time = server time
		$otherTZ  = new DateTimeZone($zone);
		$datetime->setTimezone($otherTZ);
		if($format_date == "") {
			$format_date = "D M d, Y";
		}
		$new_date = $datetime->format($format_date);
		return $new_date;
	}

}

/* Converting time */

function showpass_get_event_time ($date, $zone) {
		if ($date && $zone) {
			$format_time = get_option('format_time');
			$datetime = new Datetime($date); // current time = server time
			$otherTZ  = new DateTimeZone($zone);
			$datetime->setTimezone($otherTZ);
			if($format_time == "") {
				$format_time = "g:iA";
			}
			$new_date = $datetime->format($format_time);
			return $new_date;
		}
}

function showpass_get_timezone_abbr ($timezone) {
	if ($timezone) {
		date_default_timezone_set($timezone);
		$new_date = date('T');
		return $new_date;
	}
}

/* RETURN TRUE IF ALL TICKET TYPES ARE SOLD OUT */
function showpass_ticket_sold_out ($data) {
	if ($data) {
		$ticket_types = $data;

		if (!$ticket_types) {
			return null;
		}

		$soldout_count = 0;
		$ticket_types_count = sizeOf($data);

		foreach ($ticket_types as $ticket) {
			if ($ticket['sold_out']) {
					$soldout_count ++ ;
			}
		}

		if($soldout_count == $ticket_types_count){
			return true;
		}
		else{
			return false;
		}
	}
}

/* GET PRICE RANGE FOR TICKETS */
function showpass_get_price_range ($data) {
	if ($data) {
		$ticket_types = $data;
		if (!$ticket_types) {
			return null;
		}

		$min = 999999999;
		$max = 0;

		foreach ($ticket_types as $ticket) {
			if ($ticket['price'] < $min) {
				$min = $ticket['price'];
			}
			if ($ticket['price'] > $max) {
				$max = $ticket['price'];
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
}

/* GET PRICE RANGE FOR PRODUCTS */
function showpass_get_product_price_range ($data) {
	if ($data) {
		$product_att = $data;
		if (!$product_att) {
			return null;
		}

		$min = 999999999;
		$max = 0;

		foreach ($product_att as $product) {
			if ($product['price'] < $min) {
				$min = $product['price'];
			}
			if ($product['price'] > $max) {
				$max = $product['price'];
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
}

/* Function for next/prev page */

function showpass_get_events_next_prev($page) {
	if(isset($_GET['q'])) {
		$page_link = ACTUAL_LINK . '?page_number=' . $page . "&q=" . $_GET['q'];
	} else {
		$page_link = ACTUAL_LINK . '?page_number=' . $page;
	}
	return $page_link;
}

function getSingleTemplate($data) {
	$event = json_decode($data);
	$html = "";
	$html = "<div><p>" . $event->name . "</p></div>";
	return $html;
}

function getListTemplate($data) {
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


function wpshp_calendar($atts) {
	// registering style and script

  wp_enqueue_style('tooltipster-css', plugins_url( '/css/vendor/tooltipster.css', __FILE__ ), array(), '1.0.0', 'all' );
	wp_enqueue_style('showpass-calendar-style', plugins_url( '/css/showpass-calendar-style.css', __FILE__ ), array(), null);
  wp_enqueue_style('showpass-calendar-css', plugins_url( '/css/showpass-calendar-style.css', __FILE__ ), array(), null);
  wp_enqueue_script('dateformat-timezone-showpass');
  wp_enqueue_script('showpass-lodash');
  wp_enqueue_script('tooltipster');
  wp_enqueue_script('showpass-calendar-script');

	$organization_id = get_option('option_organization_id');

  // get query params if any
  $parameters = $_GET;

  // set single date var if query param present
	if(isset($parameters["date"])) {
		$single_date = $parameters["date"];
	} else if (isset($atts["single_date"])) {
    $single_date = $atts["single_date"];
  } else {
    $single_date = null;
  }

  // redirection page for event detail
	if(isset($atts["page"])) {
		$page = $atts["page"];
	}

  // Month view enabled by default - to disable month="disabled"
	if(isset($atts["month"])) {
		$month_enable = $atts["month"];
	}

  // week view enabled by default - to disable week="disabled"
	if(isset($atts["week"])) {
		$week_enable = $atts["week"];
	}

  // by default use light theme | theme_dark="true"
  if (isset($atts["theme_dark"])) {
    $theme = 'dark';
  } else {
    $theme = '';
  }

  // open widget when user clicks the ticket button
  if (isset($atts["use_widget"])) {
    $use_widget = true;
  }else {
		$use_widget = false;
	}

  // search for specific tags
  if (isset($atts["tags"])) {
    $tags = $atts["tags"];
  } else {
		$tags = '';
	}
  function createGlobalVars($value) {
    $GLOBALS['current_month'] = date('M', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
    $GLOBALS['current_month_number'] = date('n', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
    $GLOBALS['current_month_prev'] = $GLOBALS['current_month_number'] - 1;
    $GLOBALS['current_month_next'] = $GLOBALS['current_month_number'] + 1;
    $GLOBALS['current_year'] = date('Y', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
    $GLOBALS['current_day'] = date('j', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
    $GLOBALS['month'] = date('m', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
    $GLOBALS['days'] = date('t', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
  }
  $html = "";
  // display a single day if query parameter set
  if ($single_date != null) {
    $value = explode('-', $single_date);
    createGlobalVars($value);
  } else if (isset($atts["starting_date"])) {
    // if starting_date parameter is set 'j-n-Y' format (month, day, year) no leading zeros
    $value = explode('-', $atts["starting_date"]);
    $html .= "<input type='hidden' id='starting-date' value='" . $atts["starting_date"] . "' />";
    createGlobalVars($value);
  } else {
    $GLOBALS['current_month'] = date('M');
    $GLOBALS['current_month_number'] = date('n');
    $GLOBALS['current_month_prev'] = date('n') - 1;
    $GLOBALS['current_month_next'] = date('n') + 1;
    $GLOBALS['current_year'] = date('Y');
    $GLOBALS['current_day'] = date('j');
    $GLOBALS['month'] = date('m');
    $GLOBALS['days'] = date('t');
  }

	$prev_week = (int)$GLOBALS['current_day'] - 7;
	$next_week = (int)$GLOBALS['current_day'] + 7;

	$first = '01-' . $GLOBALS['month'] . "-" . $GLOBALS['current_year'];
	$first_of_the_month_day = date('N', strtotime($first));

	$array_days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
	$array_months = ['', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];

	$html .= "<div class='showpass-calendar " .  $theme . "'>";


  if (isset($page)) {
    $html .= "<input type='hidden' id='page_type' value='" . $page . "' />";
  } else {
    $html .= "<input type='hidden' id='page_type' value='' />";
  }

  $html .= "<input type='hidden' id='calendar-day' value='" . $GLOBALS['current_day'] . "' />";
	$html .= "<input type='hidden' id='calendar-month' value='" . $GLOBALS['month'] . "' />";
  $html .= "<input type='hidden' id='calendar-year' value='" . $GLOBALS['current_year'] . "' />";
	$html .= "<input type='hidden' id='current_day' value='" . $GLOBALS['current_day'] . "' />";
	$html .= "<input type='hidden' id='current-month' value='" . $GLOBALS['current_month_number'] . "' />";
  $html .= "<input type='hidden' id='current-year' value='" . $GLOBALS['current_year'] . "' />";
	$html .= "<input type='hidden' id='site_url' value='" . get_home_url() . "' />";
	$html .= "<input type='hidden' id='venue_id' value='" . $organization_id . "' />";
  $html .= "<input type='hidden' id='use-widget' value='" . $use_widget . "' />";
  $html .= "<input type='hidden' id='tags' value='" . $tags . "' />";
  $html .= "<input type='hidden' id='single-day' value='" . $single_date . "' />";

  if (isset($month_enable)) {
    $html .= "<input type='hidden' id='month_enable' value='" . $month_enable . "' />";
  }

  if (isset($week_enable)) {
    $html .= "<input type='hidden' id='week_enable' value='" . $week_enable . "' />";
  }

  $hide_daily = '';
  $hide_calendar = '';
  $html .= "<div class='clearfix control-container'><select id='view-select'><option  class='month' value='month'>Month View</option><option class='week' value='week'>Week View</option><option class='day' value='day'>Day View</option></select>";
  //$html .= "<div class='showpass-month-view showpass-view'>Month</div>";
  //$html .= "<div class='showpass-week-view showpass-view'>Week</div>";
  //$html .= "<div class='showpass-day-view showpass-view'>Day</div>";
  $html .= "<div class='daily-view-toggle'><span id='card-view' class='icon-button'><i class='fa fa-list-alt'></i></span><span id='scedule-view' class='icon-button'><i class='fa fa-list'></i></span></div></div>";

  // Generate Month/Week view stuff
	$html .= "<div class='showpass-calendar-month'><div class='showpass-prev-month disabled' data-month='" . $GLOBALS['current_month_prev'] . "'></div><p class='showpass-month'>" . $GLOBALS['current_month'] ."</p> <p class='showpass-year'>" . $GLOBALS['current_year'] ."</p><div class='showpass-next-month' data-month='" . $GLOBALS['current_month_next'] . "'></div></div>";
	$html .= "<div class='showpass-calendar-week'><div class='showpass-prev-week' data-prev-week=''></div><p class='showpass-week'></p><div class='showpass-next-week' data-next-week=''></div> </div>";
  $html .= "<div class='calendar-contain-desktop'><div class='showpass-calendar-head-container clearfix'>";

  for($i = 0; $i < sizeof($array_days); $i++) {
		$html .= "<div class='showpass-calendar-head'>" . $array_days[$i] ."</div>";
	}

  $html .= "</div>";
	$html .= "<div class='calendar-contain'><div class='showpass-calendar-body clearfix'>";

  $html .= "</div></div><div class='loader-home'><div class='loader'>Loading...</div></div></div>";
  //$html .= "<div class='calendar-contain-mobile'><div class='showpass-calendar-mobile'></div><div class='loader-home'><div class='loader'>Loading...</div></div></div>";

  // Generate single day html
  $html .= "<div class='horizontal-schedule-display'><div class='showpass-calendar-day'><div class='showpass-prev-day' data-day='dummy'></div><p class='showpass-day'></p><div class='showpass-next-day' data-date='dummy'></div></div>";
  $html .= "<div id='schedule-display'></div>";
  $html .= "<div id='daily-card-view' class='showpass-flex-box'><div class='showpass-layout-flex'></div></div>";
  $html .= "<div class='loader-home'><div class='loader'>Loading...</div></div>";
  $html .= "</div></div></div>";

  return $html;

}

add_shortcode('showpass_calendar','wpshp_calendar');

//[showpass_widget label="Patrons Circle Tickets" slug="wff-patrons-circle"]
function showpass_widget_expand($atts, $content = null) {
  if (get_option('option_widget_color')) {
    $widget_color = get_option('option_widget_color');
  } else {
    $widget_color = 'DD3333';
  }

	if ($atts['slug']) {

		$slug = $atts['slug'];

		if ($atts['label']) {
			$label = $atts['label'];
		} else {
			$label = 'Tickets';
		}

    if (isset($atts['class'])) {
			$class = $atts['class'];
		} else {
      if ($widget_color) {
        $style = '<style type="text/css">.showpass-button {background-color:#'.$widget_color.' !important;}</style>';
      } else {
        $style = '';
      }
			$class = 'showpass-button';
		}

    if ((isset($atts['keep_shopping']) && $atts['keep_shopping'] === 'true') || (get_option('option_keep_shopping') === 'false')) {
			$keep_shopping = 'true';
		} else {
			$keep_shopping = 'false';
		}

    if ((isset($atts['keep_shopping']) && $atts['keep_shopping'] === 'false') || (get_option('option_keep_shopping') != 'false')) {
			$keep_shopping = 'false';
		} else {
			$keep_shopping = 'true';
		}

		if( (get_option('option_theme_dark') === 'true') || (isset($atts['theme']) && $atts['theme'] === 'dark')){
			$theme_dark = 'true';
		} else {
			$theme_dark = 'false';
		}

		//update to template as needed
		$button = '';
		$button .= $style.'<div><span id="'.$slug.'" class="open-ticket-widget '.$class.'" data-color="'.$widget_color.'" data-shopping="'.$keep_shopping.'" data-theme="'.$theme_dark.'"><i class="fa fa-plus" style="margin-right: 10px;"></i>';
		$button .= '<span>'.$label.'</span></div>';
		return $button;

	} else {
		return 'No slug provided for Showpass widget';
	}
}
add_shortcode('showpass_widget', 'showpass_widget_expand');

function wpshp_get_pricing_table( $atts ) {

	/* get Organization ID that is configured in admin Showpass Event API page */
	$organization_id = get_option('option_organization_id');
  $event_ids = str_replace(' ', '', $atts['ids']);

	if($event_ids == NULL) {
		echo "ERROR - Please enter the `ids` parameter in shortcode";
	}

	$final_api_url = API_PUBLIC_EVENTS;
  $filepath = 'inc/default-pricing-table.php';
  $final_api_url = $final_api_url . '/?id__in=' . $event_ids;
	$data = CallAPI($final_api_url);

  $events = array();
  $sort_order = explode(',', $event_ids);
  $events_data = json_decode($data, true)['results'];
  $events = array();
  foreach( $sort_order as $sort_id ) {
     foreach( $events_data as $event ) {
        if( $event['id'] == $sort_id ) {
          array_push($events, $event);
          break;
        }
     }
  }

  ob_start();
	include($filepath);
  $content = ob_get_clean();
  return $content;

}

add_shortcode( 'showpass_pricing_table', 'wpshp_get_pricing_table' );

//[showpass_cart_button]
function wpshp_cart_button($atts, $content = null) {
  return '<span class="showpass-button showpass-cart-button" href="#"><i class="fa fa-shopping-cart"></i><span>Shopping Cart</span></span>';
}

add_shortcode('showpass_cart_button', 'wpshp_cart_button');

function showpass_scripts(){
  wp_dequeue_script('jquery');
  if (!is_admin()) {
    wp_enqueue_script('showpass-sdk', plugins_url( '/js/showpass-sdk.js', __FILE__ ), array('jquery'), '1.0.0', true );
    wp_register_script('showpass-calendar-script', plugins_url( '/js/showpass-calendar.js', __FILE__ ), array('jquery'), '1.0.0', true );
    wp_register_script('moment-showpass', plugins_url( '/js/moment.js', __FILE__ ), array(),false, '1.0.1');
    wp_register_script('moment-timezone-showpass', plugins_url( '/js/moment-timezone.js', __FILE__ ), array(),false, '1.0.2');
    wp_register_script('dateformat-timezone-showpass', plugins_url( '/js/dateFormat.js', __FILE__ ), array(),false, '1.0.3');
    wp_register_script('tooltipster', plugins_url( '/js/vendor/tooltipster.js', __FILE__ ), array(),false, '4.2.5');
    wp_register_script('tooltipster', plugins_url( '/js/vendor/js.cookie.js', __FILE__ ), array(),false, '2.2.0');
    wp_register_script('showpass-lodash', '//cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js', array());
		wp_enqueue_script('moment-showpass');
		wp_enqueue_script('moment-timezone-showpass');
    wp_enqueue_style('showpass-style', plugins_url( '/css/showpass-style.css', __FILE__ ), array(), null);
    wp_enqueue_style('showpass-flex-box', plugins_url( '/css/showpass-flex-box.css', __FILE__ ), array(), null);
    wp_enqueue_script('js-cookie', '//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.js', array(), '2.2.0', true );
    wp_enqueue_script('showpass-custom', plugins_url( '/js/showpass-custom.js', __FILE__ ), array('jquery'), null);
    wp_enqueue_script('jquery-showpass');
  }
}

add_action( 'init', 'showpass_scripts' );

function your_function() {
    echo '<input type="hidden" id="option_keep_shopping" value="'.get_option('option_keep_shopping').'">';
    echo '<input type="hidden" id="option_theme_dark" value="'.get_option('option_theme_dark').'">';
    echo '<input type="hidden" id="option_widget_color" value="'.get_option('option_widget_color').'">';
}
add_action( 'wp_footer', 'your_function', 100 );

function style_function() {
		echo '<style>';
    echo '.showpass-button { background-color: #'.get_option('option_widget_color').' !important; }';
    echo '.showpass-button:hover { background-color: #'.get_option('option_widget_color').' !important; }';
    echo '.showpass-detail-buy { background-color: #'.get_option('option_widget_color').' !important; }';
    echo '.showpass-detail-buy:hover { background-color: #'.get_option('option_widget_color').' !important; }';
    echo '.showpass-pagination .current { background-color: #'.get_option('option_widget_color').' !important; }';
    echo '.showpass-price-display { color: #'.get_option('option_widget_color').' !important; }';
		echo '</style>';
}
add_action( 'wp_head', 'style_function', 100 );
