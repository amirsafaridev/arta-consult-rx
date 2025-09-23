<?php
/**
 * Main plugin class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
class Arta_Consult_RX_Main {

    /**
     * Plugin instance
     *
     * @var Arta_Consult_RX_Main
     */
    private static $instance = null;

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = ARTA_CONSULT_RX_VERSION;

    /**
     * Plugin directory path
     *
     * @var string
     */
    public $plugin_dir;

    /**
     * Plugin directory URL
     *
     * @var string
     */
    public $plugin_url;

    /**
     * Get plugin instance
     *
     * @return Arta_Consult_RX_Main
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->plugin_dir = ARTA_CONSULT_RX_PLUGIN_DIR;
        $this->plugin_url = ARTA_CONSULT_RX_PLUGIN_URL;
        
        $this->init_hooks();
        $this->load_dependencies();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'), 0);
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_init', array($this, 'check_dependencies'));
    }

    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        // Load core classes
        $this->load_class('class-arta-admin');
        $this->load_class('class-arta-post-types');
        $this->load_class('class-arta-taxonomies');
        $this->load_class('class-arta-woocommerce');
        $this->load_class('class-arta-frontend');
        $this->load_class('class-arta-ajax');
    }

    /**
     * Load class file
     *
     * @param string $class_name Class name without .php extension
     */
    private function load_class($class_name) {
        $file_path = $this->plugin_dir . 'includes/class/' . $class_name . '.php';
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize post types and taxonomies
        if (class_exists('Arta_Consult_RX_Post_Types')) {
            new Arta_Consult_RX_Post_Types();
        }
        
        if (class_exists('Arta_Consult_RX_Taxonomies')) {
            new Arta_Consult_RX_Taxonomies();
        }

        // Initialize WooCommerce integration
        if (class_exists('WooCommerce') && class_exists('Arta_Consult_RX_WooCommerce')) {
            new Arta_Consult_RX_WooCommerce();
        }

        // Initialize admin
        if (is_admin() && class_exists('Arta_Consult_RX_Admin')) {
            new Arta_Consult_RX_Admin();
        }

        // Initialize frontend
        if (!is_admin() && class_exists('Arta_Consult_RX_Frontend')) {
            new Arta_Consult_RX_Frontend();
        }

        // Initialize AJAX
        if (class_exists('Arta_Consult_RX_Ajax')) {
            new Arta_Consult_RX_Ajax();
        }
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'arta-consult-rx',
            false,
            dirname(ARTA_CONSULT_RX_PLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Check plugin dependencies
     */
    public function check_dependencies() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
        }
    }

    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <?php
                printf(
                    esc_html__('Arta Consult RX requires WooCommerce to be installed and active. %s', 'arta-consult-rx'),
                    '<a href="' . esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '">' . esc_html__('Install WooCommerce', 'arta-consult-rx') . '</a>'
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * Plugin activation
     */
    public static function activate() {
        // Create database tables if needed
        self::create_tables();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set default options
        self::set_default_options();
    }

    /**
     * Plugin deactivation
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Create database tables
     */
    private static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Create appointment slots table
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            doctor_id bigint(20) NOT NULL,
            slot_date date NOT NULL,
            slot_time time NOT NULL,
            duration int(11) DEFAULT 30,
            status varchar(20) DEFAULT 'available',
            order_id bigint(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY doctor_id (doctor_id),
            KEY slot_date (slot_date),
            KEY status (status)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Set default plugin options
     */
    private static function set_default_options() {
        $default_options = array(
            'consultation_form_fields' => array(
                'personal_info' => true,
                'medical_info' => true,
                'consent' => true
            ),
            'appointment_duration' => 30,
            'appointment_advance_days' => 30,
            'email_notifications' => true,
            'sms_notifications' => false
        );

        add_option('arta_consult_rx_options', $default_options);
    }
}
