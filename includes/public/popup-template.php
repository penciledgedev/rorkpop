<!-- RORKPOP Registration and Login Popup -->
<div id="rorkpop-overlay" class="rorkpop-overlay show">
    <div class="rorkpop-container">
        <div class="rorkpop-content">
            <div class="rorkpop-header">
                <h2>Hello!</h2>
                <h3>Welcome to RORK TV!</h3>
                <?php if (!empty($logo_url)) : ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="RORK TV Logo" class="rorkpop-logo">
                <?php endif; ?>
            </div>
            
            <div class="rorkpop-message">
                <p class="bold-message">
                    WE INSPIRE YOU TO LOVE JESUS AND EQUIP YOU TO WIN OTHERS
                </p>
                <p>Please sign up to access our content</p>
            </div>
            
            <div class="rorkpop-form-container">
                <!-- LOGIN FORM - Initially hidden -->
                <form id="rorkpop-login-form" style="display: none;">
                    <h4>LOG IN TO YOUR ACCOUNT</h4>
                    <input type="hidden" name="action" value="rorkpop_login_user">
                    <input type="hidden" name="rorkpop_login_nonce" id="rorkpop_login_nonce" value="<?php echo wp_create_nonce('rorkpop-public-nonce'); ?>">
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-login-username">USERNAME OR EMAIL</label>
                        <input type="text" id="rorkpop-login-username" name="username" required>
                    </div>
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-login-password">PASSWORD</label>
                        <input type="password" id="rorkpop-login-password" name="password" required>
                    </div>
                    
                    <div class="rorkpop-form-field checkbox">
                        <input type="checkbox" id="rorkpop-login-remember" name="remember">
                        <label for="rorkpop-login-remember">Remember me</label>
                    </div>
                    
                    <div class="rorkpop-form-submit">
                        <button type="submit" id="rorkpop-login-submit">LOG IN</button>
                    </div>
                    
                    <div id="rorkpop-login-message" class="rorkpop-response-message"></div>
                    
                    <div class="rorkpop-login-link" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; text-align: center;">
                        <p style="color: #333 !important; font-size: 15px; font-weight: 500; margin: 0;">
                            <a href="#" id="register-toggle-btn" style="color: #0073aa; text-decoration: none; font-weight: bold;">Need an account? Register here</a>
                        </p>
                        <p style="color: #333 !important; font-size: 15px; font-weight: 500; margin-top: 10px;">
                            <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>?action=lostpassword" target="_blank" style="color: #0073aa; text-decoration: none; font-weight: bold;">Forgot your password?</a>
                        </p>
                    </div>
                </form>
                
                <!-- REGISTRATION FORM -->
                <form id="rorkpop-form" class="rorkpop-form">
                    <h4>CREATE YOUR ACCOUNT</h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                        <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>?loginSocial=facebook" class="social-btn facebook" style="display: flex; align-items: center; justify-content: center; padding: 12px; border-radius: 4px; background-color: #1877F2; color: white !important; text-decoration: none; font-weight: bold;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; margin-right: 10px; background-color: rgba(255,255,255,0.2); border-radius: 50%;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="14" height="14" fill="white">
                                    <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                                </svg>
                            </span>
                            Continue with Facebook
                        </a>
                        <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>?loginSocial=google" class="social-btn google" style="display: flex; align-items: center; justify-content: center; padding: 12px; border-radius: 4px; background-color: #DB4437; color: white !important; text-decoration: none; font-weight: bold;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; margin-right: 10px; background-color: rgba(255,255,255,0.2); border-radius: 50%;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512" width="14" height="14" fill="white">
                                    <path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/>
                                </svg>
                            </span>
                            Continue with Google
                        </a>
                    </div>
                    
                    <div style="display: flex; align-items: center; margin: 20px 0; color: #666;">
                        <div style="flex: 1; border-bottom: 1px solid #ddd;"></div>
                        <span style="padding: 0 10px; font-size: 14px; text-transform: uppercase;">or</span>
                        <div style="flex: 1; border-bottom: 1px solid #ddd;"></div>
                    </div>
                    
                    <?php wp_nonce_field('rorkpop_registration', 'rorkpop_nonce'); ?>
                    <input type="hidden" name="action" value="rorkpop_register_user">
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-name">FULL NAME</label>
                        <input type="text" id="rorkpop-name" name="name" required>
                    </div>
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-email">EMAIL</label>
                        <input type="email" id="rorkpop-email" name="email" required>
                    </div>
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-password">PASSWORD</label>
                        <input type="password" id="rorkpop-password" name="password" required>
                    </div>
                    
                    <div class="rorkpop-form-field">
                        <label for="rorkpop-country">COUNTRY</label>
                        <select id="rorkpop-country" name="country" required>
                            <option value="">Select Country</option>
                            <?php
                            $countries = array(
                                'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria',
                                'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan',
                                'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia',
                                'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica',
                                'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt',
                                'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon',
                                'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
                                'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel',
                                'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, North', 'Korea, South', 'Kosovo',
                                'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania',
                                'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius',
                                'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia',
                                'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Macedonia', 'Norway', 'Oman',
                                'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal',
                                'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe',
                                'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia',
                                'South Africa', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Taiwan',
                                'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
                                'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City',
                                'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
                            );
                            
                            foreach ($countries as $country) {
                                echo '<option value="' . esc_attr($country) . '">' . esc_html($country) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="rorkpop-form-field checkbox">
                        <input type="checkbox" id="rorkpop-terms" name="terms" required>
                        <label for="rorkpop-terms">I agree to the <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" target="_blank">Terms and Conditions</a></label>
                    </div>
                    
                    <div class="rorkpop-form-submit">
                        <button type="submit" id="rorkpop-submit">REGISTER</button>
                    </div>
                    
                    <div id="rorkpop-message" class="rorkpop-response-message"></div>
                    
                    <div class="rorkpop-login-link" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; text-align: center;">
                        <p style="color: #333 !important; font-size: 15px; font-weight: 500; margin: 0;">
                            Already have an account? <a href="#" id="login-toggle-btn" style="color: #0073aa; text-decoration: none; font-weight: bold;">Login here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Self-contained JavaScript -->
<script type="text/javascript">
jQuery(document).ready(function($) {
    "use strict";
    
    // Show the popup immediately
    $('#rorkpop-overlay').addClass('show');
    
    // Toggle between login and registration forms
    $('#login-toggle-btn').on('click', function(e) {
        e.preventDefault();
        $('#rorkpop-form').hide();
        $('#rorkpop-message').hide();
        $('#rorkpop-login-form').show();
    });
    
    $('#register-toggle-btn').on('click', function(e) {
        e.preventDefault();
        $('#rorkpop-login-form').hide();
        $('#rorkpop-login-message').hide();
        $('#rorkpop-form').show();
    });
    
    // Handle registration form submission
    $('#rorkpop-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitButton = $('#rorkpop-submit');
        const $message = $('#rorkpop-message');
        
        // Validate password
        const password = $('#rorkpop-password').val();
        if (password.length < 6) {
            $message.removeClass('success').addClass('error').text('Password must be at least 6 characters long.').show();
            return;
        }
        
        // Validate terms checkbox
        if (!$('#rorkpop-terms').is(':checked')) {
            $message.removeClass('success').addClass('error').text('You must agree to the Terms and Conditions.').show();
            return;
        }
        
        // Collect form data
        const formData = {
            name: $('#rorkpop-name').val(),
            email: $('#rorkpop-email').val(),
            password: password,
            country: $('#rorkpop-country').val(),
            terms: $('#rorkpop-terms').is(':checked') ? 1 : 0,
            rorkpop_nonce: $('#rorkpop_nonce').val(),
            action: 'rorkpop_register_user'
        };
        
        // Store email for success message
        const userEmail = formData.email;
        
        // Disable submit button and show loading state
        $submitButton.prop('disabled', true).text('Registering...');
        
        // Send AJAX request
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                $submitButton.prop('disabled', false).text('Register');
                
                if (response.success) {
                    // Show verification message with user's email
                    $form.hide();
                    $message.removeClass('error').addClass('success').html(
                        '<h4>Successful Registration!</h4>' +
                        '<p>Check your email <strong>' + userEmail + '</strong> to verify your registration.</p>' +
                        '<p>A verification link has been sent to your inbox. Please click the link to complete your registration.</p>'
                    ).show();
                } else {
                    // Show error message
                    $message.removeClass('success').addClass('error').text(response.data.message).show();
                }
            },
            error: function() {
                $submitButton.prop('disabled', false).text('Register');
                $message.removeClass('success').addClass('error').text('An error occurred. Please try again.').show();
            }
        });
    });
    
    // Handle login form submission
    $('#rorkpop-login-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitButton = $('#rorkpop-login-submit');
        const $message = $('#rorkpop-login-message');
        
        // Collect form data
        const formData = {
            username: $('#rorkpop-login-username').val(),
            password: $('#rorkpop-login-password').val(),
            rorkpop_login_nonce: $('#rorkpop_login_nonce').val(),
            remember: $('#rorkpop-login-remember').is(':checked') ? 1 : 0,
            action: 'rorkpop_login_user'
        };
        
        // Disable submit button and show loading state
        $submitButton.prop('disabled', true).text('Logging in...');
        
        // Send AJAX request
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success message and reload page
                    $message.removeClass('error').addClass('success').text('Login successful! Redirecting...').show();
                    
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    $submitButton.prop('disabled', false).text('Log In');
                    $message.removeClass('success').addClass('error').text(response.data.message).show();
                }
            },
            error: function() {
                $submitButton.prop('disabled', false).text('Log In');
                $message.removeClass('success').addClass('error').text('An error occurred. Please try again.').show();
            }
        });
    });
    
    // Check for email verification success
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('email_verified') && urlParams.get('email_verified') === 'true') {
        // Show verification success message
        $('<div class="rorkpop-verification-success" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 999999; display: flex; justify-content: center; align-items: center;">' +
            '<div style="background-color: #fff; width: 90%; max-width: 500px; border-radius: 8px; box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3); padding: 30px; text-align: center;">' +
            '<h3 style="font-size: 24px; margin: 0 0 15px; color: #3c763d;">Email Verified Successfully!</h3>' +
            '<p style="margin-bottom: 20px; color: #333; line-height: 1.6;">Thank you for verifying your email. You now have full access to our content.</p>' +
            '<button id="rorkpop-verification-close" style="background-color: #0073aa; color: #fff; border: none; padding: 12px 25px; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold;">Continue to Website</button>' +
            '</div>' +
            '</div>').appendTo('body');
        
        // Handle close button click
        $('#rorkpop-verification-close').on('click', function() {
            $('.rorkpop-verification-success').fadeOut(function() {
                $(this).remove();
            });
            
            // Remove the query parameter from URL
            const url = new URL(window.location);
            url.searchParams.delete('email_verified');
            window.history.replaceState({}, '', url);
        });
    }
});
</script>

<!-- Minimal inline styles to ensure functionality -->
<style type="text/css">
.rorkpop-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 999999;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 1;
    visibility: visible;
}

.rorkpop-container {
    background-color: #fff;
    width: 90%;
    max-width: 500px;
    border-radius: 8px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    max-height: 85vh;
    overflow-y: auto;
}

.rorkpop-content {
    padding: 25px;
}

.rorkpop-header {
    text-align: center;
    margin-bottom: 20px;
}

.rorkpop-logo {
    max-width: 180px;
    margin: 15px auto;
    display: block;
}

.rorkpop-header h2 {
    font-size: 32px;
    margin: 10px 0 5px;
    color: #333;
    font-weight: bold;
}

.rorkpop-header h3 {
    font-size: 24px;
    margin: 0 0 15px;
    color: #555;
    font-weight: bold;
}

.rorkpop-message {
    margin-bottom: 25px;
    text-align: center;
}

.bold-message {
    font-weight: 900;
    font-size: 18px;
    letter-spacing: 0.5px;
    color: #333;
}

.rorkpop-form-container {
    background-color: #f8f8f8;
    padding: 20px;
    border-radius: 6px;
}

.rorkpop-form-container h4 {
    text-align: center;
    font-size: 18px;
    margin: 0 0 20px;
    color: #333;
    font-weight: bold;
    letter-spacing: 0.5px;
}

.rorkpop-form-field {
    margin-bottom: 15px;
}

.rorkpop-form-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
    font-size: 16px;
    letter-spacing: 0.5px;
}

.rorkpop-form-field.checkbox {
    display: flex;
    align-items: flex-start;
}

.rorkpop-form-field.checkbox input[type="checkbox"] {
    width: auto;
    margin-right: 10px;
    margin-top: 3px;
}

.rorkpop-form-field.checkbox label {
    display: inline;
    font-weight: normal;
}

.rorkpop-form-field input,
.rorkpop-form-field select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

.rorkpop-form-submit {
    text-align: center;
    margin-top: 20px;
}

.rorkpop-form-submit button {
    background-color: #0073aa;
    color: #fff;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.2s ease;
    letter-spacing: 0.5px;
}

.rorkpop-response-message {
    margin-top: 15px;
    padding: 10px;
    text-align: center;
    border-radius: 4px;
    display: none;
}

.rorkpop-response-message.success {
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
    display: block;
}

.rorkpop-response-message.success h4 {
    color: #3c763d;
    font-size: 18px;
    margin: 0 0 10px;
    font-weight: bold;
}

.rorkpop-response-message.error {
    background-color: #f2dede;
    color: #a94442;
    border: 1px solid #ebccd1;
    display: block;
}
</style> 