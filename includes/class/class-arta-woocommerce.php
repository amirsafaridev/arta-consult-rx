<?php
/**
 * WooCommerce Integration class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Integration class
 */
class Arta_Consult_RX_WooCommerce {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('woocommerce_init', array($this, 'woocommerce_init'));
        add_filter('woocommerce_product_data_tabs', array($this, 'add_product_tabs'));
        add_action('woocommerce_product_data_panels', array($this, 'add_product_panels'));
        add_action('woocommerce_process_product_meta', array($this, 'save_product_meta'));
        add_filter('woocommerce_register_shop_order_post_statuses', array($this, 'register_custom_order_statuses'));
        add_action('woocommerce_order_status_changed', array($this, 'handle_order_status_change'), 10, 3);
    }

    /**
     * Initialize WooCommerce integration
     */
    public function init() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Add custom product types
        add_filter('product_type_selector', array($this, 'add_custom_product_types'));
        add_filter('woocommerce_product_class', array($this, 'get_custom_product_class'), 10, 2);
    }

    /**
     * WooCommerce initialization
     */
    public function woocommerce_init() {
        // Register custom order statuses
        $this->register_custom_order_statuses();
    }

    /**
     * Add custom product types
     */
    public function add_custom_product_types($types) {
        $types['medical_product'] = __('Medical Product', 'arta-consult-rx');
        $types['consultation_service'] = __('Consultation Service', 'arta-consult-rx');
        $types['appointment_service'] = __('Appointment Service', 'arta-consult-rx');
        
        return $types;
    }

    /**
     * Get custom product class
     */
    public function get_custom_product_class($classname, $product_type) {
        switch ($product_type) {
            case 'medical_product':
                return 'WC_Product_Medical_Product';
            case 'consultation_service':
                return 'WC_Product_Consultation_Service';
            case 'appointment_service':
                return 'WC_Product_Appointment_Service';
            default:
                return $classname;
        }
    }

    /**
     * Add product data tabs
     */
    public function add_product_tabs($tabs) {
        $tabs['medical_settings'] = array(
            'label'    => __('Medical Settings', 'arta-consult-rx'),
            'target'   => 'medical_settings_panel',
            'class'    => array('show_if_medical_product'),
            'priority' => 25,
        );

        $tabs['consultation_settings'] = array(
            'label'    => __('Consultation Settings', 'arta-consult-rx'),
            'target'   => 'consultation_settings_panel',
            'class'    => array('show_if_consultation_service'),
            'priority' => 26,
        );

        $tabs['appointment_settings'] = array(
            'label'    => __('Appointment Settings', 'arta-consult-rx'),
            'target'   => 'appointment_settings_panel',
            'class'    => array('show_if_appointment_service'),
            'priority' => 27,
        );

        return $tabs;
    }

    /**
     * Add product data panels
     */
    public function add_product_panels() {
        global $post;
        ?>
        <div id="medical_settings_panel" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php
                woocommerce_wp_checkbox(array(
                    'id'          => '_requires_prescription',
                    'label'       => __('Requires Prescription', 'arta-consult-rx'),
                    'description' => __('Check if this product requires a prescription', 'arta-consult-rx'),
                ));

                woocommerce_wp_select(array(
                    'id'          => '_medical_product_type',
                    'label'       => __('Medical Product Type', 'arta-consult-rx'),
                    'options'     => array(
                        'medication' => __('Medication', 'arta-consult-rx'),
                        'supplement' => __('Supplement', 'arta-consult-rx'),
                        'equipment'  => __('Medical Equipment', 'arta-consult-rx'),
                        'other'      => __('Other', 'arta-consult-rx'),
                    ),
                ));

                woocommerce_wp_checkbox(array(
                    'id'          => '_doctor_approval_required',
                    'label'       => __('Doctor Approval Required', 'arta-consult-rx'),
                    'description' => __('Check if doctor approval is required for this product', 'arta-consult-rx'),
                ));

                // Related programs
                $related_programs = get_post_meta($post->ID, '_linked_programs', true);
                if (!is_array($related_programs)) {
                    $related_programs = array();
                }
                
                $programs = get_posts(array(
                    'post_type' => 'arta_program',
                    'posts_per_page' => -1,
                    'post_status' => 'publish'
                ));
                ?>
                <p class="form-field">
                    <label for="linked_programs"><?php _e('Linked Programs', 'arta-consult-rx'); ?></label>
                    <select id="linked_programs" name="linked_programs[]" multiple="multiple" style="width: 100%;">
                        <?php foreach ($programs as $program): ?>
                            <option value="<?php echo $program->ID; ?>" <?php selected(in_array($program->ID, $related_programs)); ?>>
                                <?php echo esc_html($program->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
        </div>

        <div id="consultation_settings_panel" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(array(
                    'id'          => '_consultation_duration',
                    'label'       => __('Consultation Duration (minutes)', 'arta-consult-rx'),
                    'type'        => 'number',
                    'custom_attributes' => array('min' => '15', 'step' => '15'),
                ));

                woocommerce_wp_textarea_input(array(
                    'id'          => '_consultation_requirements',
                    'label'       => __('Consultation Requirements', 'arta-consult-rx'),
                    'description' => __('List any specific requirements for this consultation', 'arta-consult-rx'),
                ));

                // Associated doctor
                $associated_doctor = get_post_meta($post->ID, '_consultation_doctor', true);
                $doctors = get_posts(array(
                    'post_type' => 'arta_doctor',
                    'posts_per_page' => -1,
                    'post_status' => 'publish'
                ));
                ?>
                <p class="form-field">
                    <label for="consultation_doctor"><?php _e('Associated Doctor', 'arta-consult-rx'); ?></label>
                    <select id="consultation_doctor" name="consultation_doctor" style="width: 100%;">
                        <option value=""><?php _e('Select a doctor', 'arta-consult-rx'); ?></option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo $doctor->ID; ?>" <?php selected($associated_doctor, $doctor->ID); ?>>
                                <?php echo esc_html($doctor->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
        </div>

        <div id="appointment_settings_panel" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(array(
                    'id'          => '_appointment_duration',
                    'label'       => __('Appointment Duration (minutes)', 'arta-consult-rx'),
                    'type'        => 'number',
                    'custom_attributes' => array('min' => '15', 'step' => '15'),
                ));

                woocommerce_wp_text_input(array(
                    'id'          => '_appointment_advance_days',
                    'label'       => __('Advance Booking (days)', 'arta-consult-rx'),
                    'type'        => 'number',
                    'custom_attributes' => array('min' => '1'),
                ));

                // Associated doctor
                $associated_doctor = get_post_meta($post->ID, '_appointment_doctor', true);
                $doctors = get_posts(array(
                    'post_type' => 'arta_doctor',
                    'posts_per_page' => -1,
                    'post_status' => 'publish'
                ));
                ?>
                <p class="form-field">
                    <label for="appointment_doctor"><?php _e('Associated Doctor', 'arta-consult-rx'); ?></label>
                    <select id="appointment_doctor" name="appointment_doctor" style="width: 100%;">
                        <option value=""><?php _e('Select a doctor', 'arta-consult-rx'); ?></option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo $doctor->ID; ?>" <?php selected($associated_doctor, $doctor->ID); ?>>
                                <?php echo esc_html($doctor->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Save product meta data
     */
    public function save_product_meta($post_id) {
        // Medical product settings
        if (isset($_POST['_requires_prescription'])) {
            update_post_meta($post_id, '_requires_prescription', 'yes');
        } else {
            update_post_meta($post_id, '_requires_prescription', 'no');
        }

        if (isset($_POST['_medical_product_type'])) {
            update_post_meta($post_id, '_medical_product_type', sanitize_text_field($_POST['_medical_product_type']));
        }

        if (isset($_POST['_doctor_approval_required'])) {
            update_post_meta($post_id, '_doctor_approval_required', 'yes');
        } else {
            update_post_meta($post_id, '_doctor_approval_required', 'no');
        }

        if (isset($_POST['linked_programs'])) {
            $linked_programs = array_map('intval', $_POST['linked_programs']);
            update_post_meta($post_id, '_linked_programs', $linked_programs);
        } else {
            update_post_meta($post_id, '_linked_programs', array());
        }

        // Consultation settings
        if (isset($_POST['_consultation_duration'])) {
            update_post_meta($post_id, '_consultation_duration', intval($_POST['_consultation_duration']));
        }

        if (isset($_POST['_consultation_requirements'])) {
            update_post_meta($post_id, '_consultation_requirements', sanitize_textarea_field($_POST['_consultation_requirements']));
        }

        if (isset($_POST['consultation_doctor'])) {
            update_post_meta($post_id, '_consultation_doctor', intval($_POST['consultation_doctor']));
        }

        // Appointment settings
        if (isset($_POST['_appointment_duration'])) {
            update_post_meta($post_id, '_appointment_duration', intval($_POST['_appointment_duration']));
        }

        if (isset($_POST['_appointment_advance_days'])) {
            update_post_meta($post_id, '_appointment_advance_days', intval($_POST['_appointment_advance_days']));
        }

        if (isset($_POST['appointment_doctor'])) {
            update_post_meta($post_id, '_appointment_doctor', intval($_POST['appointment_doctor']));
        }
    }

    /**
     * Register custom order statuses
     */
    public function register_custom_order_statuses($order_statuses) {
        $new_statuses = array(
            'wc-consultation-pending' => array(
                'label'                     => _x('Consultation Pending', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Consultation Pending <span class="count">(%s)</span>', 'Consultation Pending <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
            'wc-consultation-approved' => array(
                'label'                     => _x('Consultation Approved', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Consultation Approved <span class="count">(%s)</span>', 'Consultation Approved <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
            'wc-consultation-rejected' => array(
                'label'                     => _x('Consultation Rejected', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Consultation Rejected <span class="count">(%s)</span>', 'Consultation Rejected <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
            'wc-appointment-scheduled' => array(
                'label'                     => _x('Appointment Scheduled', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Appointment Scheduled <span class="count">(%s)</span>', 'Appointment Scheduled <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
            'wc-appointment-completed' => array(
                'label'                     => _x('Appointment Completed', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Appointment Completed <span class="count">(%s)</span>', 'Appointment Completed <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
            'wc-product-request-pending' => array(
                'label'                     => _x('Product Request Pending', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Product Request Pending <span class="count">(%s)</span>', 'Product Request Pending <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
            'wc-product-approved' => array(
                'label'                     => _x('Product Approved', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Product Approved <span class="count">(%s)</span>', 'Product Approved <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
            'wc-product-rejected' => array(
                'label'                     => _x('Product Rejected', 'Order status', 'arta-consult-rx'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Product Rejected <span class="count">(%s)</span>', 'Product Rejected <span class="count">(%s)</span>', 'arta-consult-rx'),
            ),
        );

        return array_merge($order_statuses, $new_statuses);
    }

    /**
     * Handle order status change
     */
    public function handle_order_status_change($order_id, $old_status, $new_status) {
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return;
        }

        // Handle consultation status changes
        if (strpos($new_status, 'consultation') !== false) {
            $this->handle_consultation_status_change($order, $new_status);
        }

        // Handle appointment status changes
        if (strpos($new_status, 'appointment') !== false) {
            $this->handle_appointment_status_change($order, $new_status);
        }

        // Handle product request status changes
        if (strpos($new_status, 'product') !== false) {
            $this->handle_product_request_status_change($order, $new_status);
        }
    }

    /**
     * Handle consultation status change
     */
    private function handle_consultation_status_change($order, $status) {
        // Add consultation-specific logic here
        // For example, send notifications, update related records, etc.
        
        switch ($status) {
            case 'wc-consultation-approved':
                // Send approval notification
                $this->send_consultation_approval_notification($order);
                break;
            case 'wc-consultation-rejected':
                // Send rejection notification
                $this->send_consultation_rejection_notification($order);
                break;
        }
    }

    /**
     * Handle appointment status change
     */
    private function handle_appointment_status_change($order, $status) {
        // Add appointment-specific logic here
        
        switch ($status) {
            case 'wc-appointment-scheduled':
                // Create appointment slot
                $this->create_appointment_slot($order);
                break;
            case 'wc-appointment-completed':
                // Mark appointment as completed
                $this->complete_appointment($order);
                break;
        }
    }

    /**
     * Handle product request status change
     */
    private function handle_product_request_status_change($order, $status) {
        // Add product request-specific logic here
        
        switch ($status) {
            case 'wc-product-approved':
                // Send approval notification
                $this->send_product_approval_notification($order);
                break;
            case 'wc-product-rejected':
                // Send rejection notification
                $this->send_product_rejection_notification($order);
                break;
        }
    }

    /**
     * Send consultation approval notification
     */
    private function send_consultation_approval_notification($order) {
        // Implementation for sending approval notification
        // This would integrate with email system
    }

    /**
     * Send consultation rejection notification
     */
    private function send_consultation_rejection_notification($order) {
        // Implementation for sending rejection notification
    }

    /**
     * Create appointment slot
     */
    private function create_appointment_slot($order) {
        // Implementation for creating appointment slot
        // This would store appointment data in the database
    }

    /**
     * Complete appointment
     */
    private function complete_appointment($order) {
        // Implementation for completing appointment
    }

    /**
     * Send product approval notification
     */
    private function send_product_approval_notification($order) {
        // Implementation for sending product approval notification
    }

    /**
     * Send product rejection notification
     */
    private function send_product_rejection_notification($order) {
        // Implementation for sending product rejection notification
    }
}
