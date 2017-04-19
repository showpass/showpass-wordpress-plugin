<?php

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


?>