<?php

/*************************************
* create custom wordpress rest endpoints for the showpass plugin
* https://developer.wordpress.org/rest-api/
* https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
*************************************/

if (! defined('ABSPATH')) {
    exit;
}

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

function showpass_api_process_url($data) {
	$url = esc_url_raw($data['url']);
	$validURL = wp_http_validate_url($url);
	$showpassEvent = strpos($validURL, 'showpass.com');
	if ($validURL && $showpassEvent) {
		$splitURL = explode('/', $url);
		$slug = $splitURL[3];
		return rest_ensure_response($slug);
	} else if ($validURL && !$showpassEvent) {
		/**
		 * Get token from plugin options and post event to Showpass
		 */
		return ' do da query man ';
	} else {
		return wp_send_json_error('Error: Invalid URL provided, please enter a valid URL', $status_code = 400);
	}
}