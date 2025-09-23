<?php
/**
 * Frontend class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Frontend class
 */
class Arta_Consult_RX_Frontend {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('init', array($this, 'init'));
        add_filter('template_include', array($this, 'template_include'));
        add_action('wp_head', array($this, 'add_rtl_support'));
    }

    /**
     * Initialize frontend
     */
    public function init() {
        // Add shortcodes
        add_shortcode('arta_programs', array($this, 'programs_shortcode'));
        add_shortcode('arta_consultation_form', array($this, 'consultation_form_shortcode'));
        add_shortcode('arta_appointment_booking', array($this, 'appointment_booking_shortcode'));
        add_shortcode('arta_products', array($this, 'products_shortcode'));
        add_shortcode('arta_doctor_info', array($this, 'doctor_info_shortcode'));
        add_shortcode('arta_user_dashboard', array($this, 'user_dashboard_shortcode'));
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_frontend_scripts() {
        wp_enqueue_style(
            'arta-frontend-style',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            ARTA_CONSULT_RX_VERSION
        );

        wp_enqueue_script(
            'arta-frontend-script',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            ARTA_CONSULT_RX_VERSION,
            true
        );

        // Localize script
        wp_localize_script('arta-frontend-script', 'arta_frontend', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('arta_frontend_nonce'),
            'strings' => array(
                'loading' => __('Loading...', 'arta-consult-rx'),
                'error' => __('An error occurred. Please try again.', 'arta-consult-rx'),
                'success' => __('Success!', 'arta-consult-rx'),
            )
        ));

        // Enqueue RTL styles if needed
        if (is_rtl()) {
            wp_enqueue_style(
                'arta-frontend-rtl',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/rtl.css',
                array('arta-frontend-style'),
                ARTA_CONSULT_RX_VERSION
            );
        }
    }

    /**
     * Add RTL support
     */
    public function add_rtl_support() {
        if (is_rtl()) {
            echo '<style>.arta-container { direction: rtl; }</style>';
        }
    }

    /**
     * Template include
     */
    public function template_include($template) {
        if (is_singular('arta_program')) {
            $custom_template = ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/single-program.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        if (is_singular('arta_doctor')) {
            $custom_template = ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/single-doctor.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        if (is_post_type_archive('arta_program')) {
            $custom_template = ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/archive-programs.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        if (is_post_type_archive('arta_doctor')) {
            $custom_template = ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/archive-doctors.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        return $template;
    }

    /**
     * Programs shortcode
     */
    public function programs_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'category' => '',
            'featured' => false,
            'layout' => 'grid',
        ), $atts);

        $args = array(
            'post_type' => 'arta_program',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'arta_program_category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ),
            );
        }

        if ($atts['featured']) {
            $args['meta_query'] = array(
                array(
                    'key' => '_featured_program',
                    'value' => 'yes',
                    'compare' => '=',
                ),
            );
        }

        $programs = get_posts($args);

        ob_start();
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/programs-shortcode.php';
        return ob_get_clean();
    }

    /**
     * Consultation form shortcode
     */
    public function consultation_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'program_id' => '',
            'doctor_id' => '',
        ), $atts);

        ob_start();
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/consultation-form.php';
        return ob_get_clean();
    }

    /**
     * Appointment booking shortcode
     */
    public function appointment_booking_shortcode($atts) {
        $atts = shortcode_atts(array(
            'doctor_id' => '',
            'program_id' => '',
        ), $atts);

        ob_start();
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/appointment-booking.php';
        return ob_get_clean();
    }

    /**
     * Products shortcode
     */
    public function products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'program_id' => '',
            'type' => 'medical_product',
        ), $atts);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
        );

        if (!empty($atts['program_id'])) {
            $args['meta_query'] = array(
                array(
                    'key' => '_linked_programs',
                    'value' => $atts['program_id'],
                    'compare' => 'LIKE',
                ),
            );
        }

        $products = get_posts($args);

        ob_start();
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/products-shortcode.php';
        return ob_get_clean();
    }

    /**
     * Doctor info shortcode
     */
    public function doctor_info_shortcode($atts) {
        $atts = shortcode_atts(array(
            'doctor_id' => '',
            'show_availability' => true,
            'show_contact' => true,
        ), $atts);

        if (empty($atts['doctor_id'])) {
            return '';
        }

        $doctor = get_post($atts['doctor_id']);
        if (!$doctor || $doctor->post_type !== 'arta_doctor') {
            return '';
        }

        ob_start();
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/doctor-info.php';
        return ob_get_clean();
    }

    /**
     * User dashboard shortcode
     */
    public function user_dashboard_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view your dashboard.', 'arta-consult-rx') . '</p>';
        }

        $user_id = get_current_user_id();
        $consultations = $this->get_user_consultations($user_id);
        $appointments = $this->get_user_appointments($user_id);
        $product_requests = $this->get_user_product_requests($user_id);

        ob_start();
        include ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/frontend/user-dashboard.php';
        return ob_get_clean();
    }

    /**
     * Get user consultations
     */
    private function get_user_consultations($user_id) {
        return wc_get_orders(array(
            'customer_id' => $user_id,
            'status' => array('wc-consultation-pending', 'wc-consultation-approved', 'wc-consultation-rejected'),
            'limit' => 20,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }

    /**
     * Get user appointments
     */
    private function get_user_appointments($user_id) {
        return wc_get_orders(array(
            'customer_id' => $user_id,
            'status' => array('wc-appointment-scheduled', 'wc-appointment-completed'),
            'limit' => 20,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }

    /**
     * Get user product requests
     */
    private function get_user_product_requests($user_id) {
        return wc_get_orders(array(
            'customer_id' => $user_id,
            'status' => array('wc-product-request-pending', 'wc-product-approved', 'wc-product-rejected'),
            'limit' => 20,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }
}
