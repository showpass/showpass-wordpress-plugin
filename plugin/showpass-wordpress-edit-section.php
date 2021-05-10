<?php

/**
 * Add section to admin section when editing pages and posts to communicate with
 * Showpass API and create events to return shortcodes and URLS
 *
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Add javascript to admin head for processing meta form
 */

function showpass_edit_section_form_javascript() {
	wp_localize_script( 'wp-api', 'wpApiSettings', array(
		'root' => esc_url_raw( rest_url() ),
		'nonce' => wp_create_nonce( 'wp_rest' )
	 ) );
	wp_enqueue_style('showpass-flex-box', plugins_url( '/css/showpass-meta-box.css', __FILE__ ), array(), null);
	wp_enqueue_script('showpass-custom', plugins_url( 'js/showpass-edit-form-js.js', __FILE__ ), array('jquery'), null, false);
}

/**
 * Adds section to edit page/post
 *
 */

abstract class Showpass_Meta_Box {

    /**
     * Set up and add the meta box.
     */
    public static function add() {
        $screens = [ 'post', 'page' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'showpass_meta_box_id',          // Unique ID
                'Showpass URL & Shortcode Generator', // Box title
                [ self::class, 'html' ],   // Content callback, must be of type callable
                $screen
            );
        }
    }

    /**
     * Display the meta box HTML to the user.
     *
     * @param \WP_Post $post   Post object.
     */
    public static function html( $post ) { ?>
<div id="showpass-get-event-url">
    <label for="showpass_url_field">Event URL</label><br />
    <input type="text" id="showpass_url_field" /> <br />
    <button id="submit-event-url">Submit</button> <span class="loader dashicons dashicons-update"></span>
    <span id="error"></span>
    <span id="success"></span>
    <p class="response">URL: <span id="showpass-url"></span></p>
    <p class="response">Shortcode: <span id="showpass-shortcode"></span></p>
</div>
<?php }

}

/**
 * Only show the section if they have the access token filled out
 */
if (get_option('option_showpass_access_token')) {
  add_action( 'in_admin_footer', 'showpass_edit_section_form_javascript' );
  add_action( 'add_meta_boxes', [ 'Showpass_Meta_Box', 'add' ] );
}