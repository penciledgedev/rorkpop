<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php
    // Save settings if posted
    if (isset($_POST['rorkpop_settings_submit']) && isset($_POST['rorkpop_settings_nonce'])) {
        if (wp_verify_nonce($_POST['rorkpop_settings_nonce'], 'rorkpop_settings')) {
            $settings = array(
                'enable_globally' => isset($_POST['enable_globally']) ? true : false,
                'logo_url' => esc_url_raw($_POST['logo_url']),
            );
            
            update_option('rorkpop_settings', $settings);
            echo '<div class="notice notice-success"><p>Settings saved successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Security check failed.</p></div>';
        }
    }
    
    // Get current settings
    $settings = get_option('rorkpop_settings', array(
        'enable_globally' => false,
        'logo_url' => '',
    ));
    ?>
    
    <form method="post" action="">
        <?php wp_nonce_field('rorkpop_settings', 'rorkpop_settings_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">Display Settings</th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text">Display Settings</legend>
                        <label for="enable_globally">
                            <input name="enable_globally" type="checkbox" id="enable_globally" value="1" <?php checked($settings['enable_globally'], true); ?>>
                            Enable popup on all pages (not just video category pages)
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="logo_url">Logo URL</label></th>
                <td>
                    <input name="logo_url" type="text" id="logo_url" value="<?php echo esc_attr($settings['logo_url']); ?>" class="regular-text">
                    <p class="description">Enter the URL for your logo image to display in the popup.</p>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="rorkpop_settings_submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div> 