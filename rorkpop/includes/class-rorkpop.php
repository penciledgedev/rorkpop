<?php
/**
 * The main plugin class
 */
class RorkPop {
    /**
     * The admin instance
     *
     * @var RorkPop_Admin
     */
    protected $admin;
    
    /**
     * The public instance
     *
     * @var RorkPop_Public
     */
    protected $public;
    
    /**
     * Initialize the plugin
     */
    public function __construct() {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    /**
     * Load dependencies
     */
    private function load_dependencies() {
        $this->admin = new RorkPop_Admin();
        $this->public = new RorkPop_Public();
    }
    
    /**
     * Define admin hooks
     */
    private function define_admin_hooks() {
        add_action('admin_menu', array($this->admin, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this->admin, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this->admin, 'enqueue_scripts'));
        add_action('wp_ajax_rorkpop_export_csv', array($this->admin, 'export_csv'));
    }
    
    /**
     * Define public hooks
     */
    private function define_public_hooks() {
        add_action('wp_enqueue_scripts', array($this->public, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this->public, 'enqueue_scripts'));
        add_action('wp_footer', array($this->public, 'display_popup'));
        add_action('wp_ajax_rorkpop_submit_form', array($this->public, 'process_form_submission'));
        add_action('wp_ajax_nopriv_rorkpop_submit_form', array($this->public, 'process_form_submission'));
    }
    
    /**
     * Run the plugin
     */
    public function run() {
        // Plugin is now running
    }
} 