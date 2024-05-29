<?php

function wpshp_settings_page() {
?>
<div class="wrap">
    <h1>Showpass Events API</h1>

    <!-- TBD - More description -->
    <p>The main API URL is<strong> https://www.showpass.com/api. </strong> <br />
        You will need to add Organization ID (venue ID) that you want the data from. EX. 5 , if you want data from
        organization 5. You can also enter in multiple organizations 5, 10, 20, 30 - NOT RECOMENDED FOR PURCHASE WIDGET
        - you cannot purchase to more than one organization at once. </p>

    <form method="post" action="options.php">
        <?php settings_fields( 'wpshp-settings-group' ); ?>
        <?php do_settings_sections( 'wpshp-settings-group' ); ?>

        <label for="main_api_url">Organization ID (required):</label><br />
        <input type="text" placeholder="Venue ID Ex. 5" name="option_organization_id"
            value="<?php echo esc_attr( get_option('option_organization_id') ); ?>" /><br /><br />

        <label for="main_api_url">Widget Color (Hex Code):</label><br />
        <input type="text" placeholder="DD3333" name="option_widget_color"
            value="<?php echo esc_attr( get_option('option_widget_color') ); ?>" /><br /><br />

        <?php /*
        <label for="main_api_url">Enter Date Format: (if empty "l F d, Y" - ex. Friday April 21, 2017)</label><br />
        <input type="text" placeholder="l F d, Y" name="format_date"
            value="<?php echo esc_attr( get_option('format_date') ); ?>" /><br /><br />

        <label for="main_api_url">Enter Time Format: (if empty "g:iA" - ex. 9:00AM)</label><br />
        <input type="text" placeholder="g:iA" name="format_time"
            value="<?php echo esc_attr( get_option('format_time') ); ?>" /><br /><br />

        <input type="checkbox" name="option_theme_dark" value="true"
            <?php checked('true', get_option('option_theme_dark'), true); ?> />
        <label for="main_api_url">Enable Dark Theme</label><br /><br />
        */ ?>

        <label for="option_showpass_default_button_class">Default Button Class</label><br />
        <input type="text" placeholder="" name="option_showpass_default_button_class"
            value="<?php echo esc_attr( get_option('option_showpass_default_button_class') ); ?>" /><br />
        <small>If your theme has custom button classes, add them here. Example: btn btn-success</small>
        <br /><br />

        <input type="checkbox" name="option_keep_shopping" value="false"
            <?php checked('false', get_option('option_keep_shopping'), true); ?> />
        <label for="main_api_url">Use "Close" verbiage on buttons instead of "Keep Shopping" to close the
            widget.</label><br /><br />

        <input type="checkbox" name="option_show_widget_description" value="true"
            <?php checked('true', get_option('option_show_widget_description'), true); ?> />
        <label for="main_api_url">Show Product/Event description tab in the purchase widget.</label><br /><br />

        <input type="checkbox" name="option_disable_verify_ssl" value="true"
            <?php checked('true', get_option('option_disable_verify_ssl'), true); ?> />
        <label for="main_api_url">Disable SSL verification when connecting to the API.</label><br />
        <small>Disable to fix Local SSL Expired issue.</small><br /><br />

        <input type="checkbox" name="option_use_showpass_beta" value="true"
            <?php checked('true', get_option('option_use_showpass_beta'), true); ?> />
        <label for="main_api_url">Connect to beta.showpass.com</label><br />
        <small>CAUTION: This is for testing purposes only.</small><br /><br />

        <input type="checkbox" name="option_use_showpass_demo" value="true"
            <?php checked('true', get_option('option_use_showpass_demo'), true); ?> />
        <label for="main_api_url">Connect to demo.showpass.com</label><br />
        <small>CAUTION: This is for demo purposes only.</small><br /><br />

        <?php submit_button(); ?>

    </form>
</div>

<hr>
</hr>

<div class="wrap">
    <h2>DOCS</h2>

    <p>For full documentation please visit <a href="https://github.com/showpass/showpass-wordpress-plugin"
            target="_blank">https://github.com/showpass/showpass-wordpress-plugin</a></p>

    <p>For futher installation instructions please visit <a
            href="https://help.showpass.com/hc/en-us/articles/360023833073-Installing-the-Showpass-wordpress-extension"
            target="_blank">this support article.</a></p>

    <p>To register for an Organizer Showpass account to start selling tickets <a
            href="https://www.showpass.com/organizations/register/" target="_blank">click here</a></p>

    <p>For help please email wordpress-support [at] showpass.com</p>

    <p>Thank you for choosing Showpass!</p>

    <?php }


?>
