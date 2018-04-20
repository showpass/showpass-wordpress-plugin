<?php
    /*
     Plugin Name: Showpass Events
     Plugin URI: https://github.com/showpass/showpass-wordpress-plugin
     Description: Display events, event details and products use the Showpass purchase widget for on site ticket & product purchases.
     Author: Showpass / Up In Code Inc.
     Version: 2.7.2
     Author URI: https://www.showpass.com
     */


if( ! defined('ABSPATH') ) {
	exit;
}

/*************************************
* create custom plugin settings menu
*************************************/
add_action('admin_menu', 'wpshp_admin_menu');

function wpshp_admin_menu() {

	/* create new top-level menu */
	add_menu_page('Showpass Events API', 'Showpass API', 'administrator', __FILE__, 'wpshp_settings_page' , plugins_url('/images/icon.png', __FILE__) );

	/* call register settings function */
	add_action( 'admin_init', 'register_wpshp_settings' );
}

function register_wpshp_settings() {
	/* register our settings */
	register_setting( 'wpshp-settings-group', 'option_organization_id' );
  register_setting( 'wpshp-settings-group', 'option_widget_color' );
	register_setting( 'wpshp-settings-group', 'format_date' );
	register_setting( 'wpshp-settings-group', 'format_time' );
	register_setting( 'wpshp-settings-group', 'option_theme_dark' );
  register_setting( 'wpshp-settings-group', 'option_keep_shopping' );
}


/******************************
*  includes
*******************************/

 @include('showpass-wordpress-plugin-admin-page.php');
 @include('showpass-wordpress-plugin-shortcode.php');
