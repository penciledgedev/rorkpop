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
        // Only display on video category pages or if enabled globally
        $display_popup = false;
        
        // Check if we're on a category page and it's a video category
        if (is_category()) {
            $category = get_queried_object();
            $display_popup = has_term('video', 'category') || $category->slug === 'video' || $category->name === 'Video';
        }
        
        // Get the plugin settings
        $settings = get_option('rorkpop_settings', array(
            'enable_globally' => false,
            'logo_url' => '',
        ));
        
        // Also display if globally enabled in settings
        if (isset($settings['enable_globally']) && $settings['enable_globally']) {
            $display_popup = true;
        }
        
        // Don't show popup if user has already submitted the form (cookie check)
        if (isset($_COOKIE['rorkpop_submitted']) && $_COOKIE['rorkpop_submitted'] === 'true') {
            $display_popup = false;
        }
        
        if ($display_popup) {
            $logo_url = !empty($settings['logo_url']) ? $settings['logo_url'] : RORKPOP_PLUGIN_URL . 'assets/images/default-logo.png';
            include RORKPOP_PLUGIN_DIR . 'includes/public/popup-template.php';
        }
    }
    
    /**
     * Process form submission
     */
    public function process_form_submission() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rorkpop-public-nonce')) {
            wp_send_json_error(array('message' => 'Security check failed'));
        }
        
        // Validate required fields
        $required_fields = array('name', 'email', 'country');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array('message' => 'All fields are required'));
            }
        }
        
        // Validate email
        if (!is_email($_POST['email'])) {
            wp_send_json_error(array('message' => 'Please enter a valid email address'));
        }
        
        // Save to database
        global $wpdb;
        $table_name = $wpdb->prefix . 'rorkpop_submissions';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'email' => sanitize_email($_POST['email']),
                'country' => sanitize_text_field($_POST['country']),
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => 'Failed to save your data. Please try again.'));
        }
        
        // Set cookie to prevent showing popup again
        setcookie('rorkpop_submitted', 'true', time() + (30 * DAY_IN_SECONDS), '/');
        
        wp_send_json_success(array('message' => 'Thank you for your submission!'));
    }
} 