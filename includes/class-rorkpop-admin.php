<?php
/**
 * The admin-specific functionality of the plugin
 */
class RorkPop_Admin {
    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_menu_page(
            'RORK POP Settings',
            'RORK POP',
            'manage_options',
            'rorkpop',
            array($this, 'display_admin_page'),
            'dashicons-email',
            30
        );
        
        add_submenu_page(
            'rorkpop',
            'Submissions',
            'Submissions',
            'manage_options',
            'rorkpop-submissions',
            array($this, 'display_submissions_page')
        );
    }
    
    /**
     * Enqueue admin styles
     */
    public function enqueue_styles($hook) {
        if (strpos($hook, 'rorkpop') === false) {
            return;
        }
        
        wp_enqueue_style(
            'rorkpop-admin',
            RORKPOP_PLUGIN_URL . 'assets/css/rorkpop-admin.css',
            array(),
            RORKPOP_VERSION,
            'all'
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_scripts($hook) {
        if (strpos($hook, 'rorkpop') === false) {
            return;
        }
        
        wp_enqueue_script(
            'rorkpop-admin',
            RORKPOP_PLUGIN_URL . 'assets/js/rorkpop-admin.js',
            array('jquery'),
            RORKPOP_VERSION,
            false
        );
        
        wp_localize_script('rorkpop-admin', 'rorkpop_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('rorkpop-admin-nonce'),
        ));
    }
    
    /**
     * Display the main admin page
     */
    public function display_admin_page() {
        include RORKPOP_PLUGIN_DIR . 'includes/admin/settings-page.php';
    }
    
    /**
     * Display the submissions page
     */
    public function display_submissions_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rorkpop_submissions';
        
        // Process form deletion if requested
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_submission_' . $_GET['id'])) {
                $wpdb->delete($table_name, array('id' => $_GET['id']), array('%d'));
                echo '<div class="notice notice-success"><p>Submission deleted successfully.</p></div>';
            }
        }
        
        // Get submissions with pagination
        $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $items_per_page = 20;
        $offset = ($page - 1) * $items_per_page;
        
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $total_pages = ceil($total_items / $items_per_page);
        
        $submissions = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY created_at DESC LIMIT %d OFFSET %d", 
                $items_per_page, 
                $offset
            ), 
            ARRAY_A
        );
        
        include RORKPOP_PLUGIN_DIR . 'includes/admin/submissions-page.php';
    }
    
    /**
     * Export submissions to CSV
     */
    public function export_csv() {
        // Check nonce
        if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'rorkpop-admin-nonce')) {
            wp_die('Security check failed');
        }
        
        // Check capabilities
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'rorkpop_submissions';
        
        $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="rorkpop-submissions-' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add header row
        fputcsv($output, array('ID', 'Name', 'Email', 'Country', 'Date'));
        
        // Add data rows
        foreach ($submissions as $submission) {
            fputcsv($output, array(
                $submission['id'],
                $submission['name'],
                $submission['email'],
                $submission['country'],
                $submission['created_at']
            ));
        }
        
        fclose($output);
        exit;
    }
} 