<?php
/**
 * The public-facing functionality of the plugin
 */
class RorkPop_Public {
    /**
     * Enqueue public styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'rorkpop-public',
            RORKPOP_PLUGIN_URL . 'assets/css/rorkpop-public.css',
            array(),
            RORKPOP_VERSION,
            'all'
        );
    }
    
    /**
     * Enqueue public scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'rorkpop-public',
            RORKPOP_PLUGIN_URL . 'assets/js/rorkpop-public.js',
            array('jquery'),
            RORKPOP_VERSION,
            true
        );
        
        wp_localize_script('rorkpop-public', 'rorkpop_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('rorkpop-public-nonce'),
        ));
    }
    
    /**
     * Display the popup on the front-end
     */
    public function display_popup() {
        // Only display if user is not logged in
        if (is_user_logged_in()) {
            return;
        }
        
        // Get the plugin settings
        $settings = get_option('rorkpop_settings', array(
            'enable_globally' => false,
            'logo_url' => '',
        ));
        
        // Display the popup everywhere unless specific settings say otherwise
        $display_popup = true;
        
        // Check if we only want to display on video category pages
        if (isset($settings['video_category_only']) && $settings['video_category_only']) {
            $display_popup = false;
            if (is_category()) {
                $category = get_queried_object();
                $display_popup = has_term('video', 'category') || $category->slug === 'video' || $category->name === 'Video';
            }
        }
        
        // Also display if globally enabled in settings
        if (isset($settings['enable_globally']) && $settings['enable_globally']) {
            $display_popup = true;
        }
        
        if ($display_popup) {
            $logo_url = !empty($settings['logo_url']) ? $settings['logo_url'] : RORKPOP_PLUGIN_URL . 'assets/images/default-logo.png';
            include RORKPOP_PLUGIN_DIR . 'includes/public/popup-template.php';
        }
    }
    
    /**
     * Register user with WordPress
     */
    public function register_user() {
        // Check nonce
        if (!isset($_POST['rorkpop_nonce']) || !wp_verify_nonce($_POST['rorkpop_nonce'], 'rorkpop_registration')) {
            wp_send_json_error(array('message' => 'Security check failed'));
        }
        
        // Validate required fields
        $required_fields = array('name', 'email', 'password', 'country');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array('message' => 'All fields are required'));
            }
        }
        
        // Validate email
        $email = sanitize_email($_POST['email']);
        if (!is_email($email)) {
            wp_send_json_error(array('message' => 'Please enter a valid email address'));
        }
        
        // Check if email already exists
        if (email_exists($email)) {
            wp_send_json_error(array('message' => 'This email address is already registered. Please login instead.'));
        }
        
        // Generate username from email
        $username = $this->generate_username_from_email($email);
        
        // Create the user
        $user_id = wp_create_user($username, $_POST['password'], $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
        }
        
        // Update user meta
        $name_parts = explode(' ', sanitize_text_field($_POST['name']), 2);
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
        
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        update_user_meta($user_id, 'rorkpop_country', sanitize_text_field($_POST['country']));
        
        // Set display name
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => sanitize_text_field($_POST['name']),
        ));
        
        // Save to submissions table for tracking
        global $wpdb;
        $table_name = $wpdb->prefix . 'rorkpop_submissions';
        
        $wpdb->insert(
            $table_name,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'email' => $email,
                'country' => sanitize_text_field($_POST['country']),
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s')
        );
        
        // Send verification email
        $verification_key = wp_generate_password(20, false);
        update_user_meta($user_id, 'rorkpop_email_verification_key', $verification_key);
        update_user_meta($user_id, 'rorkpop_email_verified', 0);
        
        $verification_link = add_query_arg(array(
            'rorkpop_verify' => $verification_key,
            'user' => $user_id
        ), home_url());
        
        $subject = 'Verify Your Email Address - RORK TV';
        $message = "Hello $first_name,\n\n";
        $message .= "Thank you for registering with RORK TV. Please click the link below to verify your email address:\n\n";
        $message .= $verification_link . "\n\n";
        $message .= "If you did not register for RORK TV, please ignore this email.\n\n";
        $message .= "Best regards,\nThe RORK TV Team";
        
        wp_mail($email, $subject, $message);
        
        // Return success
        wp_send_json_success(array('message' => 'Registration successful! Please check your email to verify your account.'));
    }
    
    /**
     * Login user with WordPress
     */
    public function login_user() {
        // Check nonce
        if (!isset($_POST['rorkpop_login_nonce']) || !wp_verify_nonce($_POST['rorkpop_login_nonce'], 'rorkpop-public-nonce')) {
            wp_send_json_error(array('message' => 'Security check failed'));
        }
        
        // Validate required fields
        $required_fields = array('username', 'password');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array('message' => 'Username and password are required'));
            }
        }
        
        // Get credentials
        $credentials = array(
            'user_login'    => sanitize_text_field($_POST['username']),
            'user_password' => $_POST['password'],
            'remember'      => isset($_POST['remember']) ? true : false
        );
        
        // Attempt to sign the user in
        $user = wp_signon($credentials, is_ssl());
        
        // Check if login was successful
        if (is_wp_error($user)) {
            wp_send_json_error(array('message' => 'Invalid username or password'));
        }
        
        // Check if email is verified
        $email_verified = get_user_meta($user->ID, 'rorkpop_email_verified', true);
        if ($email_verified === '0') {
            // Send a new verification email
            $this->resend_verification_email($user->ID, $user->user_email);
            wp_send_json_error(array('message' => 'Please verify your email before logging in. A new verification email has been sent.'));
        }
        
        // Login successful
        wp_send_json_success(array('message' => 'Login successful'));
    }
    
    /**
     * Resend verification email
     */
    private function resend_verification_email($user_id, $email) {
        $user = get_userdata($user_id);
        $first_name = $user->first_name ?: $user->display_name;
        
        // Generate new verification key
        $verification_key = wp_generate_password(20, false);
        update_user_meta($user_id, 'rorkpop_email_verification_key', $verification_key);
        
        $verification_link = add_query_arg(array(
            'rorkpop_verify' => $verification_key,
            'user' => $user_id
        ), home_url());
        
        $subject = 'Verify Your Email Address - RORK TV';
        $message = "Hello $first_name,\n\n";
        $message .= "Please click the link below to verify your email address:\n\n";
        $message .= $verification_link . "\n\n";
        $message .= "If you did not register for RORK TV, please ignore this email.\n\n";
        $message .= "Best regards,\nThe RORK TV Team";
        
        wp_mail($email, $subject, $message);
    }
    
    /**
     * Generate a unique username from an email address
     */
    private function generate_username_from_email($email) {
        $username = substr($email, 0, strpos($email, '@'));
        $username = sanitize_user($username, true);
        
        // Ensure uniqueness
        $original_username = $username;
        $count = 1;
        
        while (username_exists($username)) {
            $username = $original_username . $count;
            $count++;
        }
        
        return $username;
    }
    
    /**
     * Verify user email
     */
    public function verify_email() {
        if (isset($_GET['rorkpop_verify']) && isset($_GET['user'])) {
            $verification_key = sanitize_text_field($_GET['rorkpop_verify']);
            $user_id = intval($_GET['user']);
            
            $stored_key = get_user_meta($user_id, 'rorkpop_email_verification_key', true);
            
            if ($stored_key && $stored_key === $verification_key) {
                // Mark as verified
                update_user_meta($user_id, 'rorkpop_email_verified', 1);
                delete_user_meta($user_id, 'rorkpop_email_verification_key');
                
                // Auto login user
                $user = get_user_by('ID', $user_id);
                if ($user) {
                    wp_set_current_user($user_id, $user->user_login);
                    wp_set_auth_cookie($user_id);
                    do_action('wp_login', $user->user_login, $user);
                }
                
                // Redirect to homepage with verification success parameter
                wp_redirect(add_query_arg('email_verified', 'true', home_url()));
                exit;
            }
        }
    }
    
    /**
     * Register AJAX handlers
     */
    public function register_ajax_handlers() {
        add_action('wp_ajax_nopriv_rorkpop_register_user', array($this, 'register_user'));
        add_action('wp_ajax_nopriv_rorkpop_login_user', array($this, 'login_user'));
        add_action('init', array($this, 'verify_email'));
    }
} 