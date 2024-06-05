<?php

/**************************
* registering shortcode
**************************/
if (get_option('option_use_showpass_beta')) {
  define('SHOWPASS_API_URL', 'https://beta.showpass.com/api');
} else if (get_option('option_use_showpass_demo')) {
  define('SHOWPASS_API_URL', 'https://demo.showpass.com/api');
} else {
  define('SHOWPASS_API_URL', 'https://www.showpass.com/api');
}
define('SHOWPASS_ACTUAL_LINK', strtok($_SERVER["REQUEST_URI"],'?'));
define('SHOWPASS_API_PUBLIC_EVENTS', SHOWPASS_API_URL . '/public/events');
define('SHOWPASS_API_PUBLIC_PRODUCTS', SHOWPASS_API_URL . '/public/products');
define('DEFAULT_BUTTON_VERBIAGE', 'Get Tickets');

/* making connection and taking the data from API */
function call_showpass_api($url) {

  $args = array(
    'timeout' => 30,
    'sslverify' => true
  );

  if (get_option('option_disable_verify_ssl')) {
    $args['sslverify'] = false;
  };

  $response = wp_safe_remote_get($url, $args);
  $http_code = wp_remote_retrieve_response_code($response);
  if ($http_code === 200) {
    return wp_remote_retrieve_body($response);
  }
}

function generate_showpass_buy_now_button_shortcode ($slug) {
	if (get_option('option_showpass_default_button_class')) {
		$classes = sprintf('class="%s"', get_option('option_showpass_default_button_class'));
	} else {
		$classes = "";
	}
	$shortcode = sprintf('[showpass_widget slug="%s" label="%s" %s]', $slug, DEFAULT_BUTTON_VERBIAGE, $classes);
	return $shortcode;
}

function showpass_get_event_data( $atts ) {
  if (!is_admin()) {
    /* get Organization ID that is configured in admin Showpass Event API page */
    $organization_id = get_option('option_organization_id');

    if (isset($atts["type"])) {
      $type = $atts["type"];
    } else {
      $type = "event-list";
    }

    if ($type == NULL) {
      echo "ERROR - Please enter type parameter in shortcode";
    }

    if (isset($atts["template"])){
      $template = $atts["template"];
    } else {
      $template = "default";
    }

    /* passed in shortcode ex. type=single/list  ---> type can be single or list*/

    $final_api_url = SHOWPASS_API_PUBLIC_EVENTS;

    if ($type == "event-detail" || $type == "single" || $type == "detail") {
      $filepath = 'inc/default-detail.php';
      if (isset($_GET['id'])) {
        $final_api_url = SHOWPASS_API_PUBLIC_EVENTS . "/" . $_GET['id'] . "/";
      } else if (isset($_GET['slug'])) {
        $final_api_url = SHOWPASS_API_PUBLIC_EVENTS . "/" . $_GET['slug'] . "/";
      } else {
        return "ERROR - Need parameter in URL (id or slug)";
      }
    } else if ($type == "event-list" || $type == "list") {

      if ($template == "list"){
        $filepath = 'inc/default-list.php';
      }
      else {
        $filepath = 'inc/default-grid.php';
      }

      $final_api_url = SHOWPASS_API_PUBLIC_EVENTS . '/?venue__in=' . $organization_id;

      # get any query parameters from URL
      $parameters = $_GET;

      foreach ($parameters as $parameter => $value) {
        if ($parameter == 'q' && !isset($atts['override_q'])) {
          $final_api_url .= "&" . $parameter . "=" . showpass_utf8_urldecode($value);
        } else if ($parameter == 'tags') {
          $final_api_url .= "&" . $parameter . "=" . showpass_utf8_urldecode($value);
        } else if ($parameter == 'page_number') 			{
          $final_api_url .= "&page=" . $value;
        } else if ($parameter == 'slug') {
          $final_api_url .= "&slug=" . $value;
        } else if ($parameter == 'starts_on__gte') {
          $final_api_url .= "&starts_on__gte=" . $value;
        } else if ($parameter == 'ends_on__lt') {
          $final_api_url .= "&ends_on__lt=" . $value;
        } else if ($parameter == 'ordering') {
          $final_api_url .= "&ordering=" . $value;
        }
      }

      if (isset($atts['tags']) && isset($tags) && $tags != '') {
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

      if (isset($atts['ends_on__gte'])) {
        $ends_on__gte = $atts['ends_on__gte'];
        $final_api_url .= "&ends_on__gte=" . $ends_on__gte;
      }

      if (isset($atts['ends_on__lt'])) {
        $ends_on__lte = $atts['ends_on__lt'];
        $final_api_url .= "&ends_on__lt=" . $ends_on__lte;
      }

      if (isset($atts['page'])) {
        $detail_page = $atts['page'];
      } else if (isset($atts['detail_page'])) {
         $detail_page = $atts['detail_page'];
      } else {
        $detail_page = NULL;
      }

      if (isset($atts['show'])) {
        $show = $atts['show'];
        $final_api_url .= "&show=" . $show;
      }

      if (isset($atts['hide_children'])) {
        $hide_children = $atts['hide_children'];
        $final_api_url .= "&hide_children=" . $hide_children;
      } else if (isset($atts['only_parents'])) {
        $only_parents = $atts['only_parents'];
        $final_api_url .= "&only_parents=" . $only_parents;
      } else {
        $final_api_url .= "&only_parents=true";
      }

      if (isset($atts['ordering'])) {
        $ordering = $atts['ordering'];
        $final_api_url .= "&ordering=" . $ordering;
      }

      if (isset($atts['show_past_events'])) {
        $show_past_events = $atts['show_past_events'];
        if ($show_past_events === 'true') {
          $now = new DateTime;
          $formatted_date = $now->format('Y-m-d\TH:i:s.u\Z');
          $final_api_url .= "&ends_on__lt=" . $formatted_date;
        }
      }

      if (isset($atts['event_ids'])) {
        $event_ids = $atts['event_ids'];
        $final_api_url .= "&id__in=" . $event_ids;
      }

    }

    $data = call_showpass_api($final_api_url);

    // decode data to to append related events to process properly
    $data = json_decode($data, TRUE);

    // Add tracking_id to data before encode
    if (isset($atts['tracking_id'])) {
      $data['tracking_id'] = $atts['tracking_id'];
    }

    // Add show_eyereturn to data before encode
    if (isset($atts['show_eyereturn'])) {
      $data['show_eyereturn'] = $atts['show_eyereturn'];
    }

    // encode json data to return properly
    $data = json_encode($data);
    if ($template == "data") {
      return $data;
    } else {
      // set shortcode flags for widget
      if (isset($atts['show_widget_description'])) {
        $show_widget_description = $atts['show_widget_description'];
      } else {
        $show_widget_description = get_option('option_show_widget_description') ? 'true' : 'false';
      }

      ob_start();
      include($filepath);
      $content = ob_get_clean();
      return $content;
    }
  }
}

function showpass_get_product_data( $atts ) {
  if (!is_admin()) {
    /* get Organization ID that is configured in admin Showpass Event API page */
    $organization_id = get_option('option_organization_id');

    if (isset($atts["type"])) {
      $type = $atts["type"];
    } else {
      $type = "list";
    }

    if ($type == NULL) {
      echo "ERROR - Please enter type parameter in shortcode";
    }

    if (isset($atts["template"])){
      $template = $atts["template"];
    } else {
      $template = "default";
    }

    /* passed in shortcode ex. type=single/list  ---> type can be single or list*/

    $final_api_url = SHOWPASS_API_PUBLIC_PRODUCTS;

    if ($type == "list") {

      if ($template == "list"){
        $filepath = 'inc/default-product-list.php';
      } else {
        $filepath = 'inc/default-product-grid.php';
      }

      $final_api_url = SHOWPASS_API_PUBLIC_PRODUCTS . '/?venue_id=' . $organization_id;
      $parameters = $_GET;

      foreach ($parameters as $parameter => $value) {
        # code...
        if ($parameter == 'q' || $parameter == 'tags') {
          $final_api_url .= "&" . $parameter . "=" . showpass_utf8_urldecode($value);
        } else if ($parameter == 'page_number') 			{
          $final_api_url .= "&page=" . $value;
        } else {
          $final_api_url .= "&" . $parameter . "=" . $value;
        }
      }

      if (isset($atts['page_size'])) {
        $number_of_events_one_page = $atts['page_size'];
        $final_api_url .= "&page_size=" . $number_of_events_one_page;
      } else {
        $number_of_events_one_page = 8;
        $final_api_url .= "&page_size=" . $number_of_events_one_page;
      }

      if (isset($atts['product_ids'])) {
        $product_ids = $atts['product_ids'];
        $final_api_url .= "&id__in=" . $product_ids;
      }
    }

    $data = call_showpass_api($final_api_url);

    // set shortcode flags for widget
    if (isset($atts['show_widget_description'])) {
      $show_widget_description = $atts['show_widget_description'];
    } else {
      $show_widget_description = get_option('option_show_widget_description') ? 'true' : 'false';
    }

    if ($template == "data") {
      return $data;
    } else {
      ob_start();
      include($filepath);
      $content = ob_get_clean();
      return $content;
    }
	}
}

function showpass_utf8_urldecode($str) {
  $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urlencode($str));
  return html_entity_decode($str,null,'UTF-8');;
}

/**
 * Get formatted event dates string.
 *
 * @param Object $event
 *
 * @return String html date element
 */
function showpass_display_date ($event, $small = false) {
  // grab values needed to calculate event times
  $starts_on = $event['starts_on'];
  $ends_on = $event['ends_on'];
  $timezone = $event['timezone'];
  $recurring = $event['is_recurring_parent'];
  $event_tbd = $event['date_time_to_be_determined'];

  /**
   * get difference between start and end times
   * Used to display multi day vs single day events
   */
  $diff_in_seconds = strtotime($ends_on) - strtotime($starts_on);
  $diff_in_hours = $diff_in_seconds / 3600; // 3600 seconds in an hour

  /**
   * If the difference between start and end date is > 24 hours,
   * then display start and end dates/times.
   * Else just return start day and event times.
   */
  if ($diff_in_hours >= 24) {
    $starts_date_element = '';
    $ends_date_element = '';

    // small elements used for grids, so text does not get cut off
    if ($small) {
      // start element
      $starts_date_element .= ''
        .'<div class="info dates">'
        .'<i class="fa fa-calendar icon-center display-inline-block"></i>'
        .'<span class="start-date">'
        .sprintf('<div class="display-inline-block label">Starts: </div><div class="display-inline-block"><div class="day">%s</div>', showpass_get_event_date($starts_on, $timezone));
        if (!$recurring) {
          $starts_date_element .= sprintf('<div class="time">%s %s</div></div>', showpass_get_event_time($starts_on, $timezone), showpass_get_timezone_abbr($timezone));
        } else {
          $starts_date_element .= '</div>';
        }
        $starts_date_element .= '</span>'
          .'</div>';
      // end element
      $ends_date_element .= ''
        .'<div class="info dates">'
        .'<i class="fa fa-calendar icon-center display-inline-block"></i>'
        .'<span class="end-date">'
        .sprintf('<div class="display-inline-block label">Ends: </div><div class="display-inline-block"><div class="day">%s</div>', showpass_get_event_date($ends_on, $timezone));
        if (!$recurring) {
          $ends_date_element .= sprintf('<div class="time">%s %s</div></div>', showpass_get_event_time($ends_on, $timezone), showpass_get_timezone_abbr($timezone));
        } else {
          $ends_date_element .= '</div>';
        }
        $ends_date_element .= '</span>'
        .'</div>';
    } else {
      // start element
      $starts_date_element .= ''
        .'<div class="info dates">'
        .'<i class="fa fa-calendar icon-center display-inline-block"></i>'
        .'<span class="start-date">'
        .sprintf('<span>Starts: </span><span class="day">%s</span> ', showpass_get_event_date($starts_on, $timezone));
      if (!$recurring) {
        $starts_date_element .= sprintf('<span class="time">&commat; %s %s</span>', showpass_get_event_time($starts_on, $timezone), showpass_get_timezone_abbr($timezone));
      }
      $starts_date_element .= '</span>'
        .'</div>';
      // end element
      $ends_date_element .= ''
        .'<div class="info dates">'
        .'<i class="fa fa-calendar icon-center display-inline-block"></i>'
        .'<span class="end-date">'
        .sprintf('<span>Ends: </span><span><span class="day">%s</span> ', showpass_get_event_date($ends_on, $timezone));
      if (!$recurring) {
        $ends_date_element .= sprintf('<span class="time">&commat; %s %s</span>', showpass_get_event_time($ends_on, $timezone), showpass_get_timezone_abbr($timezone));
      }
      $ends_date_element .='</span>'
        .'</div>';
    }
        
    // Display TBD for date if set
    if ($event_tbd) {
      // start element
      $starts_date_element = '';
      $starts_date_element .= ''
        .'<div class="info dates">'
        .'<i class="fa fa-calendar icon-center display-inline-block"></i>'
        .'<span class="start-date"><span>Date & Time TBD</span></span>'
        .'</div>';
      // end element
      $ends_date_element = '';
    }

    // concat start and end element, then wrap in a div
    return sprintf('%s%s', $starts_date_element , $ends_date_element);
  }

  // element with start date and event times
  $element =  ''
    .'<div>'
    .sprintf('<div class="info"><i class="fa fa-calendar icon-center"></i><span>%s</span></div>', showpass_get_event_date($starts_on, $timezone))
    .sprintf('<div class="info"><i class="fa fa-clock-o icon-center"></i><span>%s</span> - <span>%s</span> <span>%s</span></div>', showpass_get_event_time($starts_on, $timezone), showpass_get_event_time($ends_on, $timezone), showpass_get_timezone_abbr($timezone))
    .'</div>';

  return $element;
}

/* Converting date */
function showpass_get_event_date ($date, $zone) {
	if ($date && $zone) {
		$format_date = get_option('format_date');
		$datetime = new Datetime($date); // current time = server time
		$otherTZ  = new DateTimeZone($zone);
		$datetime->setTimezone($otherTZ);
		if ($format_date == "") {
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
    if ($format_time == "") {
      $format_time = "g:i A";
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

/* RETURNS TRUE IF ALL TICKET TYPES ARE SOLD OUT OR NOT AVAILABLE */
function showpass_ticket_sold_out ($data) {
	if ($data) {
		// Check what the parameter object is passed in
		if (isset($data['id'])) {
			// If $data contains an id then it is receiving the event object (new function)
			if ($data['is_recurring_parent']) {
				return $data['child_count'] === 0 || $data['inventory_sold_out'] || $data['sold_out'] || !$data['stats']['is_available'];
			}
			return count($data['ticket_types']) === 0 || $data['inventory_sold_out'] || $data['sold_out'];
		} else {
			// Receiving ticket type object (old functionality)
			// The below should be depreciated in the future as it does not account for recurring events
			$ticket_types = $data;
			if (!$ticket_types) {
				return null;
			}
			$soldout_count = 0;
			$ticket_types_count = sizeOf($data);
			foreach ($ticket_types as $ticket) {
        // use isset to make sure there is no error if the key doesn't exist
        // or if sold_out exists but is falsy
				if (isset($ticket['sold_out'])) {
						$soldout_count ++ ;
				}
      }
			if ($soldout_count == $ticket_types_count){
				return true;
			}
			else {
				return false;
			}
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
	// see if any query filter parameters
	if (isset($_GET)) {
		if (isset($_GET['page_number'])) {
			// if any page_number parameters, remove it and replace with current $page
			unset($_GET['page_number']);
			$page_link = SHOWPASS_ACTUAL_LINK . '?page_number=' . $page . '&' . http_build_query($_GET);
		} else {
			$page_link = SHOWPASS_ACTUAL_LINK . '?page_number=' . $page . '&' . http_build_query($_GET);
		}
	} else {
		$page_link = SHOWPASS_ACTUAL_LINK . '?page_number=' . $page;
	}
	return $page_link;
}

////////////////////////////////////////////////////////////////////////
//                    calendar shortcode
///////////////////////////////////////////////////////////////////////

function showpass_display_calendar($atts) {
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
	if (isset($parameters["date"])) {
		$single_date = $parameters["date"];
	} else if (isset($atts["single_date"])) {
    $single_date = $atts["single_date"];
  } else {
    $single_date = null;
	}

  // redirection page for event detail
	if (isset($atts["page"])) {
		$page = $atts["page"];
	} else if (isset($atts["detail_page"])) {
	  $page = $atts["detail_page"];
	}

  // Month view enabled by default - to disable month="disabled"
	if (isset($atts["month"])) {
		$month_enable = $atts["month"];
	}

  // week view enabled by default - to disable week="disabled"
	if (isset($atts["week"])) {
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
  } else {
		$use_widget = false;
	}

  // search for specific tags
  if (isset($atts["tags"])) {
    $tags = $atts["tags"];
  } else {
		$tags = '';
	}

  // hide schedule display
  if (isset($atts["hide_schedule"])) {
    $hide_schedule = $atts["hide_schedule"] === 'false' ? false : true;
  } else {
    $hide_schedule = true;
  }

	// show children by default if recurring events is not hidden
	if (isset($atts['hide_children'])) {
		$hide_children = $atts['hide_children'];
	} else {
	  $hide_children = false;
	}

  if (isset($atts['only_parents'])) {
    $only_parents = $atts['only_parents'];
  } else {
    $only_parents = false;
  }

  // white arrows
  if (isset($atts['arrows'])) {
    $arrows = $atts['arrows'];
  } else {
    $arrows = '';
  }

  // white arrows
  if (isset($atts['show'])) {
    $show_all = $atts["show"];
  } else {
	$show_all = false;
  }

  // white arrows
  if (isset($atts['hide_view_select'])) {
    $hide_view_select = $atts["hide_view_select"] === 'true' ? true : false;
  } else {
    $hide_view_select = false;
  }

  if (!function_exists('showpass_calendar_global_vars')) {
    function showpass_calendar_global_vars ($value) {
      $GLOBALS['current_month'] = date('M', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
      $GLOBALS['current_month_number'] = date('n', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
      $GLOBALS['current_month_prev'] = $GLOBALS['current_month_number'] - 1;
      $GLOBALS['current_month_next'] = $GLOBALS['current_month_number'] + 1;
      $GLOBALS['current_year'] = date('Y', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
      $GLOBALS['current_day'] = date('j', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
      $GLOBALS['month'] = date('m', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
      $GLOBALS['days'] = date('t', mktime(0, 0, 0, $value[1], $value[0], $value[2]));
    }
  }

  $html = "";
  // display a single day if query parameter set
  if ($single_date != null) {
    $value = explode('-', $single_date);
    showpass_calendar_global_vars($value);
  } else if (isset($atts["starting_date"])) {
    // if starting_date parameter is set 'j-n-Y' format (month, day, year) no leading zeros
    $value = explode('-', $atts["starting_date"]);
    $html .= "<input type='hidden' id='starting-date' value='" . $atts["starting_date"] . "' />";
    showpass_calendar_global_vars($value);
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

  $html .= "<input type='hidden' id='showpass-default-square' value='" . plugin_dir_url(__FILE__). "images/default-square.jpg' />";
  $html .= "<input type='hidden' id='showpass-default-banner' value='" . plugin_dir_url(__FILE__). "images/default-banner.jpg' />";
  $html .= "<input type='hidden' id='calendar-month' value='" . $GLOBALS['month'] . "' />";
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
  $html .= "<input type='hidden' id='hide-schedule' value='" . $hide_schedule . "' />";
  $html .= "<input type='hidden' id='only-parents' value='" . $only_parents . "' />";
  $html .= "<input type='hidden' id='hide-children' value='" . $hide_children . "' />";
  $html .= "<input type='hidden' id='show-all' value='" . $show_all . "' />";

  if (isset($month_enable)) {
    $html .= "<input type='hidden' id='month_enable' value='" . $month_enable . "' />";
  } else {
    $month_enable = "";
  }

  if (isset($week_enable)) {
    $html .= "<input type='hidden' id='week_enable' value='" . $week_enable . "' />";
  } else {
    $week_enable = "";
  }

  $hide_daily = '';
  $hide_calendar = '';
  $html .= "<div class='clearfix control-container'>";

  if (!$hide_view_select) {
    $html .= "<select id='view-select'><option ". $month_enable . " class='month' value='month'>Month View</option><option ". $week_enable . " class='week' value='week'>Week View</option><option class='day' value='day'>Day View</option></select>";
  }
  if (!$hide_schedule) {
    $html .= "<div class='daily-view-toggle'><span id='card-view' class='icon-button'><i class='fa fa-list-alt'></i></span><span id='schedule-view' class='icon-button'><i class='fa fa-list'></i></span></div>";
  }
  $html .= '</div>';
  // Generate Month/Week view stuff
	$html .= "<div class='showpass-calendar-month'><div class='showpass-prev-month disabled " . $arrows . "' data-month='" . $GLOBALS['current_month_prev'] . "'></div><p class='showpass-month'>" . $GLOBALS['current_month'] ."</p> <p class='showpass-year'>" . $GLOBALS['current_year'] ."</p><div class='showpass-next-month " . $arrows . "' data-month='" . $GLOBALS['current_month_next'] . "'></div></div>";
	$html .= "<div class='showpass-calendar-week'><div class='showpass-prev-week " . $arrows . "' data-prev-week=''></div><p class='showpass-week'></p><div class='showpass-next-week " . $arrows . "' data-next-week=''></div> </div>";
  $html .= "<div class='calendar-contain-desktop'><div class='showpass-calendar-head-container clearfix'>";

  for($i = 0; $i < sizeof($array_days); $i++) {
		$html .= "<div class='showpass-calendar-head'>" . $array_days[$i] ."</div>";
	}

  $html .= "</div>";
	$html .= "<div class='calendar-contain'><div class='showpass-calendar-body clearfix'>";
  $html .= "</div></div><div class='loader-home'><div class='loader'>Loading...</div></div></div>";
  //$html .= "<div class='calendar-contain-mobile'><div class='showpass-calendar-mobile'></div><div class='loader-home'><div class='loader'>Loading...</div></div></div>";

  // Generate single day html
  $html .= "<div class='horizontal-schedule-display'><div class='showpass-calendar-day'><div class='showpass-prev-day " . $arrows . "' data-day='dummy'></div><p class='showpass-day'></p><div class='showpass-next-day " . $arrows . "' data-date='dummy'></div></div>";
  $html .= "<div id='schedule-display'></div>";
  $html .= "<div id='daily-card-view' class='showpass-flex-box'><div class='showpass-layout-flex'></div></div>";
  $html .= "<div class='loader-home'><div class='loader'>Loading...</div></div>";
  $html .= "</div></div></div>";
  return $html;
}

function showpass_widget_expand($atts, $content = null) {

    if (get_option('option_widget_color')) {
      	$widget_color = get_option('option_widget_color');
    } else {
      	$widget_color = 'DD3333';
    }

  	if (isset($atts['slug'])) {
    	$slug = $atts['slug'];

		if (isset($atts['label'])) {
			$label = $atts['label'];
		} else {
			$label = DEFAULT_BUTTON_VERBIAGE;
		}
    
		if (isset($atts['tracking_id'])) {
			$tracking = $atts['tracking_id'];
		} else {
			$tracking = '';
		}

    	$style = '';

		if (isset($atts['class']) && isset($atts['class']) != "") {
			$class = $atts['class'];
			$include_icon = false;
		} else if (get_option('option_showpass_default_button_class')) {
			$class = get_option('option_showpass_default_button_class');
			$include_icon = false;
		} else {
			if ($widget_color) {
				$style = '<style type="text/css">.showpass-button {background-color:#'.$widget_color.' !important;}</style>';
			}
			$class = 'showpass-button';
			$include_icon = true;
		}

		if (isset($atts['keep_shopping'])) {
			$keep_shopping = $atts['keep_shopping'];
		}

		if ((get_option('option_theme_dark') === 'true') || (isset($atts['theme']) && $atts['theme'] === 'dark')){
			$theme_dark = 'true';
		} else {
			$theme_dark = 'false';
		}

		if (isset($atts['show_widget_description'])) {
			$show_description = $atts['show_widget_description'];
		} 

    if (isset($atts['show_specific_tickets'])) {
			$show_specific_tickets = $atts['show_specific_tickets'];
		} 

		//update to template as needed
		$button = '';
		$button .= $style
				.'<a '
				.sprintf('id="%s" ', $slug)
				.sprintf('class="open-ticket-widget %s" ', $class);

		if ($tracking) {
			$button .= sprintf('data-tracking="%s" ', $tracking);
		}

    if ($show_description) {
      $button .= sprintf('data-show-description="%s" ', $show_description);
    }

    if ($keep_shopping) {
      $button .= sprintf('data-shopping="%s" ', $keep_shopping);
    }

    if ($show_specific_tickets) {
      $button .= sprintf('data-show-specific-tickets="%s" ', $show_specific_tickets);
    }

		if ($include_icon) {
			$button .='><i class="fa fa-ticket" style="margin-right: 10px;"></i>';
		} else {
			$button .='>';
		}

		$button .= '<span>'.$label.'</span></a>';
		return $button;

	} else {
		return 'No slug provided for Showpass widget';
	}
}

function wpshp_get_pricing_table( $atts ) {
  $event_ids = str_replace(' ', '', $atts['ids']);

	if ($event_ids == NULL) {
		echo "ERROR - Please enter the `ids` parameter in shortcode";
	}

	$final_api_url = SHOWPASS_API_PUBLIC_EVENTS;
  $filepath = 'inc/default-pricing-table.php';
  $final_api_url = $final_api_url . '/?id__in=' . $event_ids;

  if (isset($atts['show'])) {
    $show = $atts['show'];
    $final_api_url .= "&show=" . $show;
  }

  if (isset($atts['show_event_details'])) {
    $show_event_details = $atts['show_event_details'] === 'true' ? true : false;
  } else {
    $show_event_details = true;
  }

  if (isset($atts['show_event_description'])) {
    $show_event_description = $atts['show_event_description'] === 'true' ? true : false;
  } else {
    $show_event_description = 'true';
  }

  if (!is_admin()) {
  	$data = call_showpass_api($final_api_url);
    $events = array();
    $sort_order = explode(',', $event_ids);
    $events_data = json_decode($data, true)['results'];
    $events = array();
    if ($events_data) {
      foreach( $sort_order as $sort_id ) {
        foreach( $events_data as $event ) {
          if ( $event['id'] == $sort_id ) {
            array_push($events, $event);
            break;
          }
        }
      }
    }

    // set shortcode flags for widget
    if (isset($atts['show_widget_description'])) {
      $show_widget_description = $atts['show_widget_description'];
    } else {
      $show_widget_description = get_option('option_show_widget_description') ? 'true' : 'false';
    }

    ob_start();
    include($filepath);
    $content = ob_get_clean();
    return $content;
  }
}

//[showpass_cart_button]
function wpshp_cart_button($atts, $content = null) {
  return '<span class="showpass-button showpass-cart-button" href="#"><i class="fa fa-shopping-cart"></i><span>Shopping Cart</span></span>';
}

add_shortcode('showpass_cart_button', 'wpshp_cart_button');

//[showpass_calendar_widget]
function wpshp_calendar_widget($atts, $content = null) {

  $organization_id = get_option('option_organization_id');

  $tags = isset($atts['tags']) ? $atts['tags']
    : null;

  if ($organization_id) {
    if (isset($atts['label'])) {
      $label = $atts['label'];
    } else {
      $label = 'Get Tickets';
    }
	if (isset($atts['class'])) {
		$class = $atts['class'];
	} else {
		$class = 'showpass-button';
	}
    $button = '<span data-tags="'.$tags.'" data-org-id="'.$organization_id.'" class="'.$class.' open-calendar-widget" href="#">'.$label.'</span>';
    return $button;
  } else {
    return 'Please add your Showpass Organizer ID to your Wordpress Dashboard.';
  }

}

add_shortcode('showpass_calendar_widget', 'wpshp_calendar_widget');

//[showpass_embed_calendar]
function wpshp_embed_calendar($atts, $content = null) {
	$organization_id = get_option('option_organization_id');

	$tags = isset($atts['tags']) ? $atts['tags']
								 : null;

	if ($organization_id) {
		return '<div id="showpass-calendar-widget" data-org-id="'.$organization_id.'" data-tags="'.$tags.'"></div>';
	} else {
		return 'Please add your Showpass Organizer ID to your Wordpress Dashboard.';
	}
}

add_shortcode('showpass_embed_calendar', 'wpshp_embed_calendar');

function showpass_scripts(){
	if (!is_admin()) {
		wp_enqueue_style('showpass-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css', array(), null);
		wp_enqueue_style('showpass-style', plugins_url( '/css/showpass-style.css', __FILE__ ), array(), null);
		wp_enqueue_style('showpass-flex-box', plugins_url( '/css/showpass-flex-box.css', __FILE__ ), array(), null);
      if (get_option('option_use_showpass_beta')) {
        wp_enqueue_script('showpass-beta-sdk', plugins_url( '/js/showpass-beta-sdk.js', __FILE__ ), array('jquery'), null, true );
      } else if (get_option('option_use_showpass_demo')){
        wp_enqueue_script('showpass-demo-sdk', plugins_url( '/js/showpass-demo-sdk.js', __FILE__ ), array('jquery'), null, true );
      } else {
        wp_enqueue_script('showpass-sdk', plugins_url( '/js/showpass-sdk.js', __FILE__ ), array('jquery'), null, true );
      }
		wp_register_script('showpass-calendar-script', plugins_url( '/js/showpass-calendar.js', __FILE__ ), array('jquery'), '3.8.7', true);
		wp_register_script('moment-showpass', plugins_url( '/js/moment.js', __FILE__ ), array(), '1.0.1', true);
		wp_register_script('moment-timezone-showpass', plugins_url( '/js/moment-timezone.js', __FILE__ ), array(), '1.0.2', true);
		wp_register_script('dateformat-timezone-showpass', plugins_url( '/js/dateFormat.js', __FILE__ ), array(), '1.0.3', true);
		wp_register_script('showpass-lodash', plugins_url( '/js/vendor/lodash.js', __FILE__ ), array(), '1.8.3', true);
		wp_enqueue_script('moment-showpass');
		wp_enqueue_script('moment-timezone-showpass');
		wp_enqueue_script('js-cookie', plugins_url( '/js/vendor/js.cookie.js', __FILE__ ), array(), '2.2.0', true);
		wp_enqueue_script('showpass-custom', plugins_url( '/js/showpass-custom.js', __FILE__ ), array('jquery'), '3.8.10', true);
	}
}

add_action( 'init', 'showpass_scripts' );

function showpass_widget_options() {
  echo '<input type="hidden" id="option_keep_shopping" value="'.get_option('option_keep_shopping').'">';
  echo '<input type="hidden" id="option_show_widget_description" value="'.get_option('option_show_widget_description').'">';
  echo '<input type="hidden" id="option_theme_dark" value="'.get_option('option_theme_dark').'">';
  echo '<input type="hidden" id="option_widget_color" value="'.get_option('option_widget_color').'">';
  echo '<input type="hidden" id="option_use_showpass_beta" value="'.get_option('option_use_showpass_beta').'">';
  echo '<input type="hidden" id="option_use_showpass_demo" value="'.get_option('option_use_showpass_demo').'">';
}

add_action( 'wp_footer', 'showpass_widget_options', 100 );

function showpass_style_function() {
  echo '<style>';
  echo '.showpass-button { background-color: #'.get_option('option_widget_color').' !important; }';
  echo '.showpass-button:hover { background-color: #'.get_option('option_widget_color').' !important; }';
  echo '.showpass-detail-buy { background-color: #'.get_option('option_widget_color').' !important; }';
  echo '.showpass-detail-buy:hover { background-color: #'.get_option('option_widget_color').' !important; }';
  echo '.showpass-pagination .current { background-color: #'.get_option('option_widget_color').' !important; }';
  echo '.showpass-price-display { color: #'.get_option('option_widget_color').' !important; }';
  echo '.showpass-pagination a:hover, .showpass-pagination button:hover  { color: #'.get_option('option_widget_color').' !important; }';
  echo '</style>';
}

add_action( 'wp_head', 'showpass_style_function', 100 );
