<?php

/*
 Plugin Name: Showpass
 Plugin URI: https://github.com/showpass/showpass-wordpress-plugin
 Description: List events, display event details and products. Use the Showpass purchase widget for on site ticket & product purchases all with easy to use shortcodes. See our git repo here for full documentation. https://github.com/showpass/showpass-wordpress-plugin
 Author: Showpass / Up In Code Inc.
 Version: 4.0.8
 Author URI: https://www.showpass.com
 */

define('SHOWPASS_PLUGIN_VERSION', '4.0.8');

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
    add_shortcode('showpass_memberships', 'showpass_get_membership_data');
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

function showpass_sanitize_checkbox_option($value)
{
    return $value === 'true' ? 'true' : 'false';
}

function showpass_sanitize_keep_shopping_option($value)
{
    return $value === 'false' ? 'false' : 'true';
}

function showpass_sanitize_environment_option($value, $option_name)
{
    if ($value !== 'true') {
        return 'false';
    }

    $environment_options = array(
        'option_use_showpass_local',
        'option_use_showpass_beta',
        'option_use_showpass_demo',
    );

    foreach ($environment_options as $environment_option) {
        if ($environment_option === $option_name) {
            return 'true';
        }

        if (isset($_POST[$environment_option]) && $_POST[$environment_option] === 'true') {
            return 'false';
        }
    }

    return 'true';
}

function showpass_sanitize_local_environment_option($value)
{
    return showpass_sanitize_environment_option($value, 'option_use_showpass_local');
}

function showpass_sanitize_beta_environment_option($value)
{
    return showpass_sanitize_environment_option($value, 'option_use_showpass_beta');
}

function showpass_sanitize_demo_environment_option($value)
{
    return showpass_sanitize_environment_option($value, 'option_use_showpass_demo');
}

function showpass_option_is_enabled($option_name)
{
    return get_option($option_name) === 'true';
}

function register_wpshp_settings()
{
    /* register our settings */
    register_setting('wpshp-settings-group', 'option_organization_id');
    register_setting('wpshp-settings-group', 'option_widget_color');
    register_setting('wpshp-settings-group', 'format_date');
    register_setting('wpshp-settings-group', 'format_time');
    register_setting('wpshp-settings-group', 'option_theme_dark', 'showpass_sanitize_checkbox_option');
    register_setting('wpshp-settings-group', 'option_keep_shopping', 'showpass_sanitize_keep_shopping_option');
    register_setting('wpshp-settings-group', 'option_show_widget_description', 'showpass_sanitize_checkbox_option');
    register_setting('wpshp-settings-group', 'option_disable_verify_ssl', 'showpass_sanitize_checkbox_option');
    register_setting('wpshp-settings-group', 'option_use_showpass_local', 'showpass_sanitize_local_environment_option');
    register_setting('wpshp-settings-group', 'option_use_showpass_beta', 'showpass_sanitize_beta_environment_option');
    register_setting('wpshp-settings-group', 'option_use_showpass_demo', 'showpass_sanitize_demo_environment_option');
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
