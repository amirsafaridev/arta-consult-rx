<?php
/**
 * Admin class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include appointment slot class
require_once ARTA_CONSULT_RX_PLUGIN_DIR . 'includes/class/class-arta-appointment-slot.php';

/**
 * Admin class
 */
class Arta_Consult_RX_Admin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'handle_settings_save'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            __('Arta Consult RX', 'arta-consult-rx'),
            __('Arta Consult RX', 'arta-consult-rx'),
            'manage_options',
            'arta-consult-rx',
            array($this, 'admin_dashboard_page'),
            'dashicons-heart',
            30
        );

        // Dashboard submenu
        add_submenu_page(
            'arta-consult-rx',
            __('Dashboard', 'arta-consult-rx'),
            __('Dashboard', 'arta-consult-rx'),
            'manage_options',
            'arta-consult-rx',
            array($this, 'admin_dashboard_page')
        );

        // Consultations submenu
        add_submenu_page(
            'arta-consult-rx',
            __('Consultations', 'arta-consult-rx'),
            __('Consultations', 'arta-consult-rx'),
            'manage_options',
            'arta-consultations',
            array($this, 'consultations_page')
        );

        // Appointments submenu
        add_submenu_page(
            'arta-consult-rx',
            __('Appointments', 'arta-consult-rx'),
            __('Appointments', 'arta-consult-rx'),
            'manage_options',
            'arta-appointments',
            array($this, 'appointments_page')
        );

        // Product Requests submenu
        add_submenu_page(
            'arta-consult-rx',
            __('Product Requests', 'arta-consult-rx'),
            __('Product Requests', 'arta-consult-rx'),
            'manage_options',
            'arta-product-requests',
            array($this, 'product_requests_page')
        );

        // Bulk Scheduler submenu
        add_submenu_page(
            'arta-consult-rx',
            __('Bulk Scheduler', 'arta-consult-rx'),
            __('Bulk Scheduler', 'arta-consult-rx'),
            'manage_options',
            'arta-bulk-scheduler',
            array($this, 'bulk_scheduler_page')
        );

        // Settings submenu
        add_submenu_page(
            'arta-consult-rx',
            __('Settings', 'arta-consult-rx'),
            __('Settings', 'arta-consult-rx'),
            'manage_options',
            'arta-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our plugin pages
        $plugin_pages = array(
            'toplevel_page_arta-consult-rx',
            'arta-consult-rx_page_arta-consultations',
            'arta-consult-rx_page_arta-appointments',
            'arta-consult-rx_page_arta-product-requests',
            'arta-consult-rx_page_arta-bulk-scheduler',
            'arta-consult-rx_page_arta-settings'
        );
        
        if (!in_array($hook, $plugin_pages)) {
            return;
        }

        wp_enqueue_style(
            'arta-admin-style',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            ARTA_CONSULT_RX_VERSION
        );

        wp_enqueue_script(
            'arta-admin-script',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            ARTA_CONSULT_RX_VERSION,
            true
        );
        
        // Add simple AJAX script
        wp_enqueue_script(
            'arta-simple-ajax-script',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/simple-ajax.js',
            array('jquery'),
            ARTA_CONSULT_RX_VERSION,
            true
        );

        // Localize script
        wp_localize_script('arta-admin-script', 'arta_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('arta_admin_nonce'),
            'strings' => array(
                'confirm_delete' => __('Are you sure you want to delete this item?', 'arta-consult-rx'),
                'loading' => __('Loading...', 'arta-consult-rx'),
                'error' => __('An error occurred. Please try again.', 'arta-consult-rx'),
            )
        ));
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        // Settings are now handled directly in handle_settings_save()
    }

    /**
     * Admin dashboard page
     */
    public function admin_dashboard_page() {
        // Get statistics
        $stats = $this->get_dashboard_stats();
        
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }

    /**
     * Consultations page
     */
    public function consultations_page() {
        // Get consultation orders
        $consultations = $this->get_consultation_orders();
        
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/admin/consultations-list.php';
    }

    /**
     * Appointments page
     */
    public function appointments_page() {
        // Get appointment orders
        $appointments_data = $this->get_appointment_orders();
        
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/admin/appointments-calendar.php';
    }

    /**
     * Product requests page
     */
    public function product_requests_page() {
        // Get product request orders
        $product_requests = $this->get_product_request_orders();
        
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/admin/product-requests.php';
    }

    /**
     * Bulk scheduler page
     */
    public function bulk_scheduler_page() {
        // Get doctors for bulk scheduling
        $doctors = $this->get_doctors_for_scheduling();
        
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/admin/bulk-scheduler.php';
    }

    /**
     * Settings page
     */
    public function settings_page() {
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/admin/settings.php';
    }

    /**
     * Get dashboard statistics
     */
    private function get_dashboard_stats() {
        $stats = array(
            'total_consultations' => 0,
            'pending_consultations' => 0,
            'total_appointments' => 0,
            'upcoming_appointments' => 0,
            'total_product_requests' => 0,
            'pending_product_requests' => 0,
            'total_doctors' => 0,
            'total_programs' => 0,
        );

        // Get consultation statistics
        $consultation_orders = wc_get_orders(array(
            'status' => array('wc-consultation-pending', 'wc-consultation-approved', 'wc-consultation-rejected'),
            'limit' => -1,
        ));

        $stats['total_consultations'] = count($consultation_orders);
        $stats['pending_consultations'] = count(array_filter($consultation_orders, function($order) {
            return $order->get_status() === 'wc-consultation-pending';
        }));

        // Get appointment statistics from arta_appointment_slots table
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        // Get total appointments
        $total_appointments = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $stats['total_appointments'] = $total_appointments ? $total_appointments : 0;
        
        // Get upcoming appointments (booked status)
        $upcoming_appointments = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'booked'");
        $stats['upcoming_appointments'] = $upcoming_appointments ? $upcoming_appointments : 0;

        // Get product request statistics
        $product_request_orders = wc_get_orders(array(
            'status' => array('wc-product-request-pending', 'wc-product-approved', 'wc-product-rejected'),
            'limit' => -1,
        ));

        $stats['total_product_requests'] = count($product_request_orders);
        $stats['pending_product_requests'] = count(array_filter($product_request_orders, function($order) {
            return $order->get_status() === 'wc-product-request-pending';
        }));

        // Get doctors and programs count
        $stats['total_doctors'] = wp_count_posts('arta_doctor')->publish;
        $stats['total_programs'] = wp_count_posts('arta_program')->publish;

        return $stats;
    }

    /**
     * Get consultation orders
     */
    private function get_consultation_orders() {
        return wc_get_orders(array(
            'status' => array('wc-consultation-pending', 'wc-consultation-approved', 'wc-consultation-rejected'),
            'limit' => 50,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }

    /**
     * Get appointment orders
     */
    private function get_appointment_orders() {
        global $wpdb;
        
        // Get appointment slots from database
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $slots = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY slot_date DESC, slot_time DESC"
        );
        
        // Convert slots to appointment objects for compatibility
        $appointments = array();
        
        foreach ($slots as $slot) {
            // Create a mock appointment object
            $appointment = new Arta_Appointment_Slot($slot);
            $appointments[] = $appointment;
        }
        
        return $appointments;
    }

    /**
     * Get product request orders
     */
    private function get_product_request_orders() {
        return wc_get_orders(array(
            'status' => array('wc-product-request-pending', 'wc-product-approved', 'wc-product-rejected'),
            'limit' => 50,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }

    /**
     * Get doctors for scheduling
     */
    private function get_doctors_for_scheduling() {
        return get_posts(array(
            'post_type' => 'arta_doctor',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ));
    }

    /**
     * Handle settings form submission
     */
    public function handle_settings_save() {
        if (isset($_POST['submit']) && isset($_POST['arta_settings_nonce']) && wp_verify_nonce($_POST['arta_settings_nonce'], 'arta_save_settings')) {
            // Sanitize and save settings
            $consultation_duration = isset($_POST['consultation_duration']) ? intval($_POST['consultation_duration']) : 30;
            $appointment_duration = isset($_POST['appointment_duration']) ? intval($_POST['appointment_duration']) : 30;
            $appointment_advance_days = isset($_POST['appointment_advance_days']) ? intval($_POST['appointment_advance_days']) : 30;
            
            // Save to database
            update_option('arta_consult_rx_consultation_duration', $consultation_duration);
            update_option('arta_consult_rx_appointment_duration', $appointment_duration);
            update_option('arta_consult_rx_appointment_advance_days', $appointment_advance_days);
            
            // Show success message
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'arta-consult-rx') . '</p></div>';
            });
        }
    }
}
