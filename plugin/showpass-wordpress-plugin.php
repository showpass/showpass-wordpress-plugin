<?php

/*
 Plugin Name: Showpass
 Plugin URI: https://github.com/showpass/showpass-wordpress-plugin
 Description: List events, display event details and products. Use the Showpass purchase widget for on site ticket & product purchases all with easy to use shortcodes. See our git repo here for full documentation. https://github.com/showpass/showpass-wordpress-plugin
 Author: Showpass / Up In Code Inc.
 Version: 3.8.12
 Author URI: https://www.showpass.com
 */

if (! defined('ABSPATH')) {
    exit;
}

/*************************************
* create custom plugin settings menu
*************************************/

/**
 * imports and sets Showpass\ImageFormatter whenever a frontend template is loaded.
 */
add_action('template_redirect', function () {
    require_once plugin_dir_path(__FILE__) . 'inc/image-formatter.class.php';
    global $showpass_image_formatter;
    $showpass_image_formatter = new Showpass\ImageFormatter();
    add_shortcode('showpass_events', 'showpass_get_event_data');
    add_shortcode('showpass_products', 'showpass_get_product_data');
    add_shortcode('showpass_calendar', 'showpass_display_calendar');
    add_shortcode('showpass_widget', 'showpass_widget_expand');
    add_shortcode('showpass_pricing_table', 'wpshp_get_pricing_table');
});

function wpshp_admin_menu()
{
    /* create new top-level menu */
    add_menu_page('Showpass Events API', 'Showpass API', 'administrator', __FILE__, 'wpshp_settings_page', plugins_url('/images/icon.png', __FILE__));
}

add_action('admin_menu', 'wpshp_admin_menu');

function register_wpshp_settings()
{
    /* register our settings */
    register_setting('wpshp-settings-group', 'option_organization_id');
    register_setting('wpshp-settings-group', 'option_widget_color');
    register_setting('wpshp-settings-group', 'format_date');
    register_setting('wpshp-settings-group', 'format_time');
    register_setting('wpshp-settings-group', 'option_theme_dark');
    register_setting('wpshp-settings-group', 'option_keep_shopping');
    register_setting('wpshp-settings-group', 'option_show_widget_description');
    register_setting('wpshp-settings-group', 'option_disable_verify_ssl');
    register_setting('wpshp-settings-group', 'option_use_showpass_beta');
    register_setting('wpshp-settings-group', 'option_use_showpass_demo');
    register_setting('wpshp-settings-group', 'option_showpass_access_token');
    register_setting('wpshp-settings-group', 'option_showpass_default_button_class');
}

/* call register settings function */
add_action('admin_init', 'register_wpshp_settings');

/******************************
*  includes
*******************************/

@include('showpass-wordpress-plugin-admin-page.php');
@include('showpass-wordpress-plugin-shortcode.php');
@include('showpass-wordpress-plugin-blocks.php');
@include('showpass-wordpress-custom-api.php');
@include('showpass-wordpress-edit-section.php');
