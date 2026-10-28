<?php

function wpshp_settings_page() {
    $showpass_environments = array(
        'production' => array('label' => 'Production', 'url' => 'https://www.showpass.com'),
        'local' => array('label' => 'Local', 'url' => 'https://localhost.showpass.com'),
        'beta' => array('label' => 'Beta', 'url' => 'https://beta.showpass.com'),
        'demo' => array('label' => 'Demo', 'url' => 'https://demo.showpass.com'),
    );
    $selected_environment = 'production';
    foreach (array('local', 'beta', 'demo') as $environment) {
        if (showpass_option_is_enabled('option_use_showpass_' . $environment)) {
            $selected_environment = $environment;
            break;
        }
    }
?>
<div class="wrap">
    <p><img src="<?php echo esc_url(plugins_url('images/showpass-logo.svg', __FILE__)); ?>"
        alt="Showpass" width="180" height="42" /></p>
    <h1 class="screen-reader-text">Showpass</h1>
    <?php settings_errors(); ?>
    <p>Connect your organization and configure the default appearance of your Showpass widgets.</p>

    <form method="post" action="options.php">
        <?php settings_fields('wpshp-settings-group'); ?>
        <?php do_settings_sections('wpshp-settings-group'); ?>

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><label for="option_organization_id">Organization ID (required)</label></th>
                <td>
                    <input type="text" id="option_organization_id" name="option_organization_id" class="regular-text"
                        value="<?php echo esc_attr(get_option('option_organization_id')); ?>"
                        aria-describedby="showpass-organization-help" required />
                    <div id="showpass-organization-help">
                        <p class="description">Enter the Organization ID for the organization whose data you want to display.</p>
                        <p class="description">To display data from multiple organizations, separate their IDs with commas,
                            such as <code>5433, 11040, 7230</code>.</p>
                        <p class="description"><strong>For purchase widgets, use a single Organization ID.</strong>
                            Each purchase can include items from only one organization.</p>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="option_widget_color">Widget color</label></th>
                <td>
                    <input type="text" id="option_widget_color" name="option_widget_color" class="regular-text"
                        placeholder="" value="<?php echo esc_attr(get_option('option_widget_color')); ?>"
                        aria-describedby="showpass-color-help" spellcheck="false" />
                    <p class="description" id="showpass-color-help">Enter a hex color without the <code>#</code>,
                        such as <code>1C39BB</code>. Leave blank to use the default color.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="option_showpass_default_button_class">Button CSS classes</label></th>
                <td>
                    <input type="text" id="option_showpass_default_button_class" name="option_showpass_default_button_class"
                        class="regular-text" value="<?php echo esc_attr(get_option('option_showpass_default_button_class')); ?>"
                        aria-describedby="showpass-button-class-help" spellcheck="false" />
                    <p class="description" id="showpass-button-class-help">Optional. Enter your theme's button classes,
                        separated by spaces. Example: <code>btn btn-success</code>.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Purchase widget</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">Purchase widget options</legend>
                        <label for="option_keep_shopping">
                            <input type="checkbox" id="option_keep_shopping" name="option_keep_shopping" value="false"
                                <?php checked('false', get_option('option_keep_shopping')); ?> />
                            Use &quot;Close&quot; instead of &quot;Keep Shopping&quot; on buttons that close the widget.
                        </label><br />
                        <label for="option_show_widget_description">
                            <input type="checkbox" id="option_show_widget_description" name="option_show_widget_description" value="true"
                                <?php checked('true', get_option('option_show_widget_description')); ?> />
                            Show the product or event description tab.
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>

        <details>
            <summary>Developer options</summary>
            <p class="description">Production is the default. Use Local, Beta, or Demo for development and testing.</p>

            <p>
                <label for="showpass-environment">Environment</label><br />
                <select id="showpass-environment" disabled>
                    <?php foreach ($showpass_environments as $environment => $settings) : ?>
                        <option value="<?php echo esc_attr($environment); ?>"
                            data-showpass-url="<?php echo esc_url($settings['url']); ?>"
                            <?php selected($selected_environment, $environment); ?>>
                            <?php echo esc_html($settings['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <noscript><span class="description">Enable JavaScript to change the environment.</span></noscript>
            </p>
            <?php foreach (array('local', 'beta', 'demo') as $environment) : ?>
                <input type="hidden" name="option_use_showpass_<?php echo esc_attr($environment); ?>"
                    id="showpass-environment-<?php echo esc_attr($environment); ?>"
                    value="<?php echo $selected_environment === $environment ? 'true' : 'false'; ?>" />
            <?php endforeach; ?>
            <p aria-live="polite">Showpass URL: <code id="showpass-url"><?php
                echo esc_html($showpass_environments[$selected_environment]['url']);
            ?></code></p>
            <p class="description">Save changes to apply the selected environment.</p>

            <p>
                <label for="option_disable_verify_ssl">
                    <input type="checkbox" id="option_disable_verify_ssl" name="option_disable_verify_ssl" value="true"
                        <?php checked('true', get_option('option_disable_verify_ssl')); ?> />
                    Disable SSL verification when connecting to the API.
                </label><br />
                <span class="description">Use only for local development when troubleshooting certificate errors.</span>
            </p>
        </details>

        <?php submit_button(); ?>
    </form>

    <hr />
    <h2>Docs and support</h2>
    <ul>
        <li><a href="https://dev.showpass.com/wordpress/getting-started-install-and-configure"
                target="_blank" rel="noopener noreferrer">Installation and configuration guide</a></li>
        <li><a href="https://github.com/showpass/showpass-wordpress-plugin"
                target="_blank" rel="noopener noreferrer">Plugin source on GitHub</a></li>
        <li><a href="https://www.showpass.com/organizations/register/"
                target="_blank" rel="noopener noreferrer">Create a Showpass organizer account</a></li>
    </ul>
    <p>Need help? Email <a href="mailto:solutions@showpass.com">solutions@showpass.com</a>.</p>
</div>
<script type="text/javascript">
    (function () {
        var environmentSelect = document.getElementById('showpass-environment');
        environmentSelect.disabled = false;
        environmentSelect.addEventListener('change', function () {
            ['local', 'beta', 'demo'].forEach(function (environment) {
                document.getElementById('showpass-environment-' + environment).value =
                    environmentSelect.value === environment ? 'true' : 'false';
            });
            document.getElementById('showpass-url').textContent =
                environmentSelect.options[environmentSelect.selectedIndex].getAttribute('data-showpass-url');
        });
    }());
</script>
<?php
}
