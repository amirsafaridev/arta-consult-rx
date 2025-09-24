<?php
/**
 * Main Plugin Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Arta Consult RX Class
 */
class Arta_Consult_RX {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Single instance of the class
     *
     * @var Arta_Consult_RX
     */
    protected static $_instance = null;

    /**
     * Main Arta_Consult_RX Instance
     *
     * @return Arta_Consult_RX
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define plugin constants
     */
    private function define_constants() {
        $this->define('ARTA_CONSULT_RX_ABSPATH', dirname(ARTA_CONSULT_RX_PLUGIN_FILE) . '/');
        $this->define('ARTA_CONSULT_RX_PLUGIN_BASENAME', plugin_basename(ARTA_CONSULT_RX_PLUGIN_FILE));
    }

    /**
     * Define constant if not already set
     *
     * @param string $name
     * @param string|bool $value
     */
    private function define($name, $value) {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Include required core files
     */
    public function includes() {
        // Core classes
        include_once ARTA_CONSULT_RX_ABSPATH . 'includes/class/arta-post-types.php';
        include_once ARTA_CONSULT_RX_ABSPATH . 'includes/class/arta-user-roles.php';
        include_once ARTA_CONSULT_RX_ABSPATH . 'includes/class/arta-meta-boxes.php';
            include_once ARTA_CONSULT_RX_ABSPATH . 'includes/class/arta-database.php';
            include_once ARTA_CONSULT_RX_ABSPATH . 'includes/class/arta-admin.php';
            include_once ARTA_CONSULT_RX_ABSPATH . 'includes/class/arta-appointment-form.php';
            include_once ARTA_CONSULT_RX_ABSPATH . 'includes/class/arta-my-account.php';
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'), 0);
        add_action('plugins_loaded', array($this, 'plugins_loaded'));
    }

    /**
     * Init Arta Consult RX when WordPress Initialises
     */
    public function init() {
        // Before init action
        do_action('arta_consult_rx_before_init');

        // Load plugin text domain
        $this->load_plugin_textdomain();

        // Initialize classes
        $this->init_classes();

        // After init action
        do_action('arta_consult_rx_init');
    }

    /**
     * Load Localisation files
     */
    public function load_plugin_textdomain() {
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'arta-consult-rx');

        unload_textdomain('arta-consult-rx');
        load_textdomain('arta-consult-rx', WP_LANG_DIR . '/arta-consult-rx/arta-consult-rx-' . $locale . '.mo');
        load_plugin_textdomain('arta-consult-rx', false, plugin_basename(dirname(ARTA_CONSULT_RX_PLUGIN_FILE)) . '/languages');
    }

    /**
     * Initialize classes
     */
    private function init_classes() {
        // Initialize post types
        new Arta_Post_Types();
        
        // Initialize user roles
        new Arta_User_Roles();
        
        // Initialize meta boxes
        new Arta_Meta_Boxes();
        
        // Initialize database
        new Arta_Database();
        
        // Initialize admin
        if (is_admin()) {
            new Arta_Admin();
        }
        
        // Initialize appointment form
        new Arta_Appointment_Form();
        
        // Initialize my account
        new Arta_My_Account();
    }

    /**
     * When WP has loaded all plugins, trigger the `arta_consult_rx_loaded` hook
     */
    public function plugins_loaded() {
        do_action('arta_consult_rx_loaded');
    }

    /**
     * Get the plugin url
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit(plugins_url('/', ARTA_CONSULT_RX_PLUGIN_FILE));
    }

    /**
     * Get the plugin path
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit(plugin_dir_path(ARTA_CONSULT_RX_PLUGIN_FILE));
    }

    /**
     * Get the template path
     *
     * @return string
     */
    public function template_path() {
        return apply_filters('arta_consult_rx_template_path', 'arta-consult-rx/');
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Set flag to flush rewrite rules
        update_option('arta_consult_rx_flush_rewrite_rules', true);
        
        // Create database tables
        if (class_exists('Arta_Database')) {
            $database = new Arta_Database();
            $database->create_tables();
        }
        
        // Add user roles
        if (class_exists('Arta_User_Roles')) {
            Arta_User_Roles::add_doctor_role();
        }
        
        // Set flag to flush rewrite rules for WooCommerce endpoints
        update_option('arta_my_requests_flush_rewrite_rules', true);
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Remove user roles
        if (class_exists('Arta_User_Roles')) {
            Arta_User_Roles::remove_doctor_role();
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}
