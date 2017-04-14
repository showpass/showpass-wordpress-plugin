<?php
    /*
     Plugin Name: Showpass Events API
     Plugin URI: 
     Description: Get data from Showpass Events API
     Author: Showpass
     Version: 0.1
     Author URI: 
     */	
	

if( ! defined('ABSPATH') )
{
	exit;
}

// create custom plugin settings menu
add_action('admin_menu', 'wpshp_admin_menu');

function wpshp_admin_menu() {

	//create new top-level menu
	add_menu_page('Showpass Events API', 'Showpass API', 'administrator', __FILE__, 'wpshp_settings_page' , plugins_url('/images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_wpshp_settings' );
}


function register_wpshp_settings() {
	//register our settings
	register_setting( 'wpshp-settings-group', 'option_main_api_url' );
}

function wpshp_settings_page() {
?>
<div class="wrap">
<h1>Showpass Events API</h1>

<!-- TBD - More description -->
<p>Here you will set the main API url ex. <strong>( www.website.com/api/ )</strong> and the API calls will be set in shortcode.</p>

<form method="post" action="options.php">
    <?php settings_fields( 'wpshp-settings-group' ); ?>
    <?php do_settings_sections( 'wpshp-settings-group' ); ?>


        <label for="main_api_url">Main API url:</label>
        <input type="text" name="option_main_api_url" value="<?php echo esc_attr( get_option('option_main_api_url') ); ?>" />
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 

	
	// registering shortcode

	function wpshp_get_data( $atts ) {

		$main_api = get_option('option_main_api_url');
		$api_call = $atts['api_call'];
		$type = $atts['type'];

		// TBD

		return $main_api;
	}
	add_shortcode( 'showpass_events', 'wpshp_get_data' );



?>
 