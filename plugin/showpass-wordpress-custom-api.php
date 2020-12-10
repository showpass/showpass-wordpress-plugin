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
			],
		]
	]);
});

function showpass_api_process_url($data) {
	$url = $data['url'];
	$validURL = wp_http_validate_url($url);
	if ($validURL) {
		$splitURL = explode('/', $url);
		$slug = $splitURL[3];
		return rest_ensure_response($slug);
	} else {
		return wp_send_json_error('Error: Invalid URL provided, please enter a valid URL', $status_code = 400);
	}
}
