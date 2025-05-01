<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php
    // Save settings if posted
    if (isset($_POST['rorkpop_settings_submit']) && isset($_POST['rorkpop_settings_nonce'])) {
        if (wp_verify_nonce($_POST['rorkpop_settings_nonce'], 'rorkpop_settings')) {
            $settings = array(
                'enable_globally' => isset($_POST['enable_globally']) ? true : false,
                'logo_url' => esc_url_raw($_POST['logo_url']),
                // Social login settings
                'enable_social_login' => isset($_POST['enable_social_login']) ? true : false,
                'facebook_app_id' => sanitize_text_field($_POST['facebook_app_id']),
                'facebook_app_secret' => sanitize_text_field($_POST['facebook_app_secret']),
                'google_client_id' => sanitize_text_field($_POST['google_client_id']),
                'google_client_secret' => sanitize_text_field($_POST['google_client_secret']),
                'redirect_after_login' => esc_url_raw($_POST['redirect_after_login']),
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
        'enable_social_login' => false,
        'facebook_app_id' => '',
        'facebook_app_secret' => '',
        'google_client_id' => '',
        'google_client_secret' => '',
        'redirect_after_login' => home_url(),
    ));
    ?>
    
    <h2 class="nav-tab-wrapper">
        <a href="#general-settings" class="nav-tab nav-tab-active">General Settings</a>
        <a href="#social-login-settings" class="nav-tab">Social Login</a>
    </h2>
    
    <form method="post" action="">
        <?php wp_nonce_field('rorkpop_settings', 'rorkpop_settings_nonce'); ?>
        
        <div id="general-settings" class="tab-content">
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
                <tr>
                    <th scope="row"><label for="redirect_after_login">Redirect After Login</label></th>
                    <td>
                        <input name="redirect_after_login" type="text" id="redirect_after_login" value="<?php echo esc_attr($settings['redirect_after_login']); ?>" class="regular-text">
                        <p class="description">Enter the URL where users should be redirected after successful login/registration. Leave blank to use the current page.</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div id="social-login-settings" class="tab-content" style="display: none;">
            <table class="form-table">
                <tr>
                    <th scope="row">Social Login</th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">Social Login</legend>
                            <label for="enable_social_login">
                                <input name="enable_social_login" type="checkbox" id="enable_social_login" value="1" <?php checked($settings['enable_social_login'], true); ?>>
                                Enable Social Login Options
                            </label>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row" colspan="2"><h3>Facebook Login Settings</h3></th>
                </tr>
                <tr>
                    <th scope="row"><label for="facebook_app_id">Facebook App ID</label></th>
                    <td>
                        <input name="facebook_app_id" type="text" id="facebook_app_id" value="<?php echo esc_attr($settings['facebook_app_id']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="facebook_app_secret">Facebook App Secret</label></th>
                    <td>
                        <input name="facebook_app_secret" type="password" id="facebook_app_secret" value="<?php echo esc_attr($settings['facebook_app_secret']); ?>" class="regular-text">
                        <p class="description">
                            Create a Facebook App at <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Developers</a>. 
                            Set the OAuth redirect URI to: <code><?php echo esc_url(home_url('wp-login.php?loginSocial=facebook')); ?></code>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row" colspan="2"><h3>Google Login Settings</h3></th>
                </tr>
                <tr>
                    <th scope="row"><label for="google_client_id">Google Client ID</label></th>
                    <td>
                        <input name="google_client_id" type="text" id="google_client_id" value="<?php echo esc_attr($settings['google_client_id']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="google_client_secret">Google Client Secret</label></th>
                    <td>
                        <input name="google_client_secret" type="password" id="google_client_secret" value="<?php echo esc_attr($settings['google_client_secret']); ?>" class="regular-text">
                        <p class="description">
                            Create a Google OAuth Client ID at <a href="https://console.developers.google.com/" target="_blank">Google Developer Console</a>. 
                            Set the OAuth redirect URI to: <code><?php echo esc_url(home_url('wp-login.php?loginSocial=google')); ?></code>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <p class="submit">
            <input type="submit" name="rorkpop_settings_submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // Hide all tab content
            $('.tab-content').hide();
            
            // Show the selected tab content
            $($(this).attr('href')).show();
            
            // Update active tab
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
        });
    });
    </script>
</div> 