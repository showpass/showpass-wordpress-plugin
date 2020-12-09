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
	]);
});

function showpass_api_process_url($data) {
	$response = 'Hello there!';
	return rest_ensure_response($response);
}