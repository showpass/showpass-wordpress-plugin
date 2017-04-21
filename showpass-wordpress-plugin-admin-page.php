<?php

function wpshp_settings_page() {
?>
<div class="wrap">
<h1>Showpass Events API</h1>

<!-- TBD - More description -->
<p>The main API URL is<strong> www.myshowpass.com/api/public/events. </strong> <br />
You will need to add Organization ID (venue ID) that you want the data from.  EX. 5 , if you want data from venue 5.</p>

<form method="post" action="options.php">
    <?php settings_fields( 'wpshp-settings-group' ); ?>
    <?php do_settings_sections( 'wpshp-settings-group' ); ?>


        <label for="main_api_url">Organization ID (required):</label><br/>
        <input type="text" placeholder="Venue ID Ex. 5" name="option_organization_id" value="<?php echo esc_attr( get_option('option_organization_id') ); ?>" /><br/><br/>

        <label for="main_api_url">Enter Date Format: (if empty "l F d, Y" - ex. Friday 21 April, 2017)</label><br/>
        <input type="text" placeholder="l F d, Y" name="format_date" value="<?php echo esc_attr( get_option('format_date') ); ?>" /><br/><br/>

        <label for="main_api_url">Enter Time Format: (if empty "g:iA" - ex. 9:00AM)</label><br/>
        <input type="text" placeholder="g:iA" name="format_time" value="<?php echo esc_attr( get_option('format_time') ); ?>" />
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 


?>