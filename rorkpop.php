<?php
/**
 * Plugin Name: RORK POP
 * Plugin URI: https://penciledge.com
 * Description: A popup plugin for email and data capture, especially on video category pages.
 * Version: 1.0.0
 * Author: Uyi Moses
 * Author URI: https://penciledge.net
 * Text Domain: rorkpop
 * License: GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RORKPOP_VERSION', '1.0.0');
define('RORKPOP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RORKPOP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once RORKPOP_PLUGIN_DIR . 'includes/class-rorkpop.php';
require_once RORKPOP_PLUGIN_DIR . 'includes/class-rorkpop-admin.php';
require_once RORKPOP_PLUGIN_DIR . 'includes/class-rorkpop-public.php';

// Initialize the plugin
function rorkpop_init() {
    $plugin = new RorkPop();
    $plugin->run();
}
add_action('plugins_loaded', 'rorkpop_init');

// Register activation hook
register_activation_hook(__FILE__, 'rorkpop_activate');
function rorkpop_activate() {
    // Create database table for storing form submissions
    global $wpdb;
    $table_name = $wpdb->prefix . 'rorkpop_submissions';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        country varchar(100) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'rorkpop_deactivate');
function rorkpop_deactivate() {
    // Cleanup if needed
} 