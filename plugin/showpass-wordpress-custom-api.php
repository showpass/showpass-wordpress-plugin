<?php

/*************************************
* create custom wordpress rest endpoints for the showpass plugin
* https://developer.wordpress.org/rest-api/
* https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
*************************************/

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Initiate the custom rest api /wp-json/showpass/v2/process-url
 */
add_action('rest_api_init', function() {
	register_rest_route('showpass/v1', '/process-url', [
		'method' => 'GET',
		'callback' => 'showpass_api_process_url',
		'permission_callback' => function() {
			return current_user_can('edit_posts');
		},
		'args' => [
			'url' => [
				'required' => true,
				'type' => 'string',
			],
		]
	]);
});

/**
 * Get token from plugin options and post event to Showpass
 */
function create_showpass_event($event_url) {
	$token = get_option('option_showpass_access_token');
  $request_url = 'https://local.showpass.com:9000/api/venue/22/events/';

	$body = json_encode([
		'name' => 'New Event request from Bits + Pieces', // event name [required]
		'venue' => 22,
		'description' => 'this is a Test Event', // event description
		'starts_on' => '2021-07-01T05:53:32.355328+00:00', // event start utc [required]
		'ends_on' => '2021-07-01T06:53:32.355349+00:00', // event end utc [required]
		'timezone' => 'Canada/Mountain', // [required]
		'address' => 'My Address', // event address [required]
		'visibility' => 2,
		'external_link' => $event_url
	]);

	$request = wp_remote_post($request_url, array(
		'method'      => 'POST',
		'timeout'     => 45,
		'headers'     => array(
			'Content-type' 	=> 'application/json; charset=utf-8',
			'Accept'     	=> 'application/json',
			'Authorization' => 'Token ' . $token,
		),
		'body' => $body,
		'data_format' => 'body',
		'sslverify' => false,
		'cookies' => array(),
  	));

  $response_code = wp_remote_retrieve_response_code( $request );

	if ( $response_code !== 201 ) {
		return rest_ensure_response(wp_send_json_error($request['body'], $status_code = 400));
	} else {
    $data = json_decode($request['body']);
		return rest_ensure_response( $data->slug );
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
		return rest_ensure_response($slug);
	} else if ($validURL && !$isShowpassEvent) {
    // If the url is valid and NOT showpass create event
        return create_showpass_event($url);
	} else {
		return rest_ensure_response(wp_send_json_error('Error: Invalid URL provided, please enter a valid URL', $status_code = 400));
  }

}