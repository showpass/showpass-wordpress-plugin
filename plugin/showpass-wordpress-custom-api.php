<?php

/*************************************
* create custom wordpress rest endpoints for the showpass plugin
* https://developer.wordpress.org/rest-api/
* https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
*************************************/

if (! defined('ABSPATH')) {
    exit;
}

function create_showpass_api_success_response ($slug) {
	$response = [
		'slug' => $slug,
		'shortcode' => generate_showpass_buy_now_button_shortcode($slug)
	];
	return $response;
}

/**
 * Initiate the custom rest api /wp-json/showpass/v2/process-url
 */
add_action('rest_api_init', function() {
	register_rest_route('showpass/v1', '/process-url', [
		'method' 	=> 'GET',
		'callback' 	=> 'showpass_api_process_url',
		'permission_callback' => function() {
			return current_user_can('edit_posts');
		},
		'args' => [
			'url' => [
				'required'  => true,
				'type'      => 'string',
			],
		]
	]);
});

/**
 * Get token from plugin options and post event to Showpass
 */
function create_showpass_event($event_url) {
	$token = get_option('option_showpass_access_token');
	// If no token then return error
	if ( !$token ) {
		return rest_ensure_response(wp_send_json_error('Error: Please enter a valid Showpass Event URL', $status_code = 400));
	}
    $request_url = 'https://www.showpass.com/api/venue/5511/events/';

	/**
	 * Get the page title from the provided URL for the event name
	 */
	function get_title($url){
		$str = file_get_contents($url);
		if(strlen($str)>0){
			$str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
			preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
			return substr($title[1], 0, 80); // limit to 80 characters
		} else {
			return 'Event Addition Request';
		}
	}

	$body = json_encode([
		'name'          => get_title($event_url),
		'venue'         => 5511,
		'location'      => 12903,
		'starts_on'     => '2022-07-01T18:00:00Z',
		'ends_on'       => '2022-07-01T20:00:00Z',
		'timezone'      => 'Canada/Mountain',
		'visibility'    => 2,
		'external_link' => $event_url,
		'third_party_ticketing_link' => $event_url,
	]);

	$request = wp_remote_post($request_url, array(
		'method'      	=> 'POST',
		'timeout'     	=> 45,
		'headers'     	=> array(
			'Content-type'  => 'application/json; charset=utf-8',
			'Accept'     	=> 'application/json',
			'Authorization' => 'Token ' . $token,
		),
		'body'			=> $body,
		'data_format'   => 'body'
  	));

  	$response_code = wp_remote_retrieve_response_code( $request );

	if ( $response_code !== 201 ) {
		return rest_ensure_response(wp_send_json_error($request['body'], $status_code = 400));
	} else {
    	$data = json_decode($request['body']);
		$response = create_showpass_api_success_response($data->slug);
		return wp_send_json_success( $response, $status_code = 200 );
	}
}

/**
 * Process the URL and return the slug
 */
function showpass_api_process_url($data) {
    $url = esc_url_raw($data['url']);
    $validURL = wp_http_validate_url($url);
    $isShowpassEvent = strpos($validURL, 'showpass.com') !== false;

	if ($validURL && $isShowpassEvent) {
    	// If the url is valid and showpass return slug
		$splitURL = explode('/', $url);
		$slug = $splitURL[3];
		$attr['slug'] = $slug;
		$response = create_showpass_api_success_response($slug);
		return wp_send_json_success( $response, $status_code = 200 );
	} else if ($validURL && !$isShowpassEvent) {
    	// If the url is valid and NOT showpass create event
        return create_showpass_event($url);
	} else {
        return rest_ensure_response(wp_send_json_error('Error: Invalid URL provided, please enter a valid URL', $status_code = 400));
  }
}
