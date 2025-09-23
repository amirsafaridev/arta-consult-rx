<?php
/**
 * AJAX class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX class
 */
class Arta_Consult_RX_Ajax {

    /**
     * Constructor
     */
    public function __construct() {
        // Frontend AJAX actions
        add_action('wp_ajax_arta_submit_consultation', array($this, 'submit_consultation'));
        add_action('wp_ajax_nopriv_arta_submit_consultation', array($this, 'submit_consultation'));
        
        add_action('wp_ajax_arta_book_appointment', array($this, 'book_appointment'));
        add_action('wp_ajax_nopriv_arta_book_appointment', array($this, 'book_appointment'));
        
        add_action('wp_ajax_arta_request_product', array($this, 'request_product'));
        add_action('wp_ajax_nopriv_arta_request_product', array($this, 'request_product'));
        
        add_action('wp_ajax_arta_get_available_slots', array($this, 'get_available_slots'));
        add_action('wp_ajax_nopriv_arta_get_available_slots', array($this, 'get_available_slots'));
        
        add_action('wp_ajax_arta_get_doctor_availability', array($this, 'get_doctor_availability'));
        add_action('wp_ajax_nopriv_arta_get_doctor_availability', array($this, 'get_doctor_availability'));

        // Admin AJAX actions
        add_action('wp_ajax_arta_bulk_create_slots', array($this, 'bulk_create_slots'));
        add_action('wp_ajax_arta_bulk_delete_slots', array($this, 'bulk_delete_slots'));
        add_action('wp_ajax_arta_update_order_status', array($this, 'update_order_status'));
        add_action('wp_ajax_arta_get_order_details', array($this, 'get_order_details'));
        add_action('wp_ajax_arta_get_appointment_details', array($this, 'get_appointment_details'));
        add_action('wp_ajax_arta_edit_appointment', array($this, 'edit_appointment'));
        add_action('wp_ajax_arta_delete_appointment', array($this, 'delete_appointment'));
        add_action('wp_ajax_arta_get_doctors_list', array($this, 'get_doctors_list'));
    }

    /**
     * Submit consultation form
     */
    public function submit_consultation() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'arta_frontend_nonce')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        // Sanitize and validate input
        $consultation_data = $this->sanitize_consultation_data($_POST);
        
        if (!$this->validate_consultation_data($consultation_data)) {
            wp_send_json_error(array(
                'message' => __('Please fill in all required fields.', 'arta-consult-rx')
            ));
        }

        // Create WooCommerce order for consultation
        $order = $this->create_consultation_order($consultation_data);
        
        if ($order) {
            wp_send_json_success(array(
                'message' => __('Consultation request submitted successfully!', 'arta-consult-rx'),
                'order_id' => $order->get_id()
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Failed to submit consultation request.', 'arta-consult-rx')
            ));
        }
    }

    /**
     * Book appointment
     */
    public function book_appointment() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'arta_frontend_nonce')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        // Sanitize and validate input
        $appointment_data = $this->sanitize_appointment_data($_POST);
        
        if (!$this->validate_appointment_data($appointment_data)) {
            wp_send_json_error(array(
                'message' => __('Please fill in all required fields.', 'arta-consult-rx')
            ));
        }

        // Check if slot is available
        if (!$this->is_slot_available($appointment_data)) {
            wp_send_json_error(array(
                'message' => __('Selected time slot is no longer available.', 'arta-consult-rx')
            ));
        }

        // Create WooCommerce order for appointment
        $order = $this->create_appointment_order($appointment_data);
        
        if ($order) {
            // Reserve the slot
            $this->reserve_appointment_slot($appointment_data, $order->get_id());
            
            wp_send_json_success(array(
                'message' => __('Appointment booked successfully!', 'arta-consult-rx'),
                'order_id' => $order->get_id()
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Failed to book appointment.', 'arta-consult-rx')
            ));
        }
    }

    /**
     * Request product
     */
    public function request_product() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'arta_frontend_nonce')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        // Sanitize and validate input
        $product_data = $this->sanitize_product_request_data($_POST);
        
        if (!$this->validate_product_request_data($product_data)) {
            wp_send_json_error(array(
                'message' => __('Please fill in all required fields.', 'arta-consult-rx')
            ));
        }

        // Check if consultation is required
        if ($this->is_consultation_required($product_data['product_id'])) {
            if (!$this->has_valid_consultation($product_data['user_id'], $product_data['product_id'])) {
                wp_send_json_error(array(
                    'message' => __('A valid consultation is required for this product.', 'arta-consult-rx')
                ));
            }
        }

        // Create WooCommerce order for product request
        $order = $this->create_product_request_order($product_data);
        
        if ($order) {
            wp_send_json_success(array(
                'message' => __('Product request submitted successfully!', 'arta-consult-rx'),
                'order_id' => $order->get_id()
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Failed to submit product request.', 'arta-consult-rx')
            ));
        }
    }

    /**
     * Get available appointment slots
     */
    public function get_available_slots() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'arta_frontend_nonce')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $doctor_id = intval($_POST['doctor_id']);
        $date = sanitize_text_field($_POST['date']);

        if (!$doctor_id || !$date) {
            wp_send_json_error(array(
                'message' => __('Invalid parameters.', 'arta-consult-rx')
            ));
        }

        $slots = $this->get_doctor_available_slots($doctor_id, $date);
        
        wp_send_json_success(array(
            'slots' => $slots
        ));
    }

    /**
     * Get doctor availability
     */
    public function get_doctor_availability() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'arta_frontend_nonce')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $doctor_id = intval($_POST['doctor_id']);
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);

        if (!$doctor_id || !$start_date || !$end_date) {
            wp_send_json_error(array(
                'message' => __('Invalid parameters.', 'arta-consult-rx')
            ));
        }

        $availability = $this->get_doctor_availability_range($doctor_id, $start_date, $end_date);
        
        wp_send_json_success(array(
            'availability' => $availability
        ));
    }

    /**
     * Bulk create appointment slots (Admin)
     */
    public function bulk_create_slots() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $bulk_data = $this->sanitize_bulk_slot_data($_POST);
        
        if (!$this->validate_bulk_slot_data($bulk_data)) {
            wp_send_json_error(array(
                'message' => __('Invalid bulk slot data.', 'arta-consult-rx')
            ));
        }

        $created_slots = $this->create_bulk_slots($bulk_data);
        
        wp_send_json_success(array(
            'message' => sprintf(__('%d slots created successfully!', 'arta-consult-rx'), $created_slots),
            'created_slots' => $created_slots
        ));
    }

    /**
     * Bulk delete appointment slots (Admin)
     */
    public function bulk_delete_slots() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $doctor_id = intval($_POST['doctor_id']);
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);

        if (!$doctor_id || !$start_date || !$end_date) {
            wp_send_json_error(array(
                'message' => __('Invalid parameters.', 'arta-consult-rx')
            ));
        }

        $deleted_slots = $this->delete_bulk_slots($doctor_id, $start_date, $end_date);
        
        wp_send_json_success(array(
            'message' => sprintf(__('%d slots deleted successfully!', 'arta-consult-rx'), $deleted_slots),
            'deleted_slots' => $deleted_slots
        ));
    }

    /**
     * Update order status (Admin)
     */
    public function update_order_status() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $order_id = intval($_POST['order_id']);
        $new_status = sanitize_text_field($_POST['new_status']);

        if (!$order_id || !$new_status) {
            wp_send_json_error(array(
                'message' => __('Invalid parameters.', 'arta-consult-rx')
            ));
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            wp_send_json_error(array(
                'message' => __('Order not found.', 'arta-consult-rx')
            ));
        }

        $order->update_status($new_status);
        
        wp_send_json_success(array(
            'message' => __('Order status updated successfully!', 'arta-consult-rx')
        ));
    }

    /**
     * Get order details (Admin)
     */
    public function get_order_details() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $order_id = intval($_POST['order_id']);

        if (!$order_id) {
            wp_send_json_error(array(
                'message' => __('Invalid order ID.', 'arta-consult-rx')
            ));
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            wp_send_json_error(array(
                'message' => __('Order not found.', 'arta-consult-rx')
            ));
        }

        $order_details = $this->format_order_details($order);
        
        wp_send_json_success(array(
            'order_details' => $order_details
        ));
    }

    /**
     * Sanitize consultation data
     */
    private function sanitize_consultation_data($data) {
        return array(
            'first_name' => sanitize_text_field($data['first_name']),
            'last_name' => sanitize_text_field($data['last_name']),
            'gender' => sanitize_text_field($data['gender']),
            'date_of_birth' => sanitize_text_field($data['date_of_birth']),
            'height' => floatval($data['height']),
            'weight' => floatval($data['weight']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field($data['phone']),
            'chronic_diseases' => sanitize_textarea_field($data['chronic_diseases']),
            'current_medications' => sanitize_textarea_field($data['current_medications']),
            'medical_history' => sanitize_textarea_field($data['medical_history']),
            'program_objectives' => sanitize_textarea_field($data['program_objectives']),
            'consent' => isset($data['consent']) ? true : false,
            'program_id' => intval($data['program_id']),
            'doctor_id' => intval($data['doctor_id']),
        );
    }

    /**
     * Validate consultation data
     */
    private function validate_consultation_data($data) {
        $required_fields = array('first_name', 'last_name', 'email', 'phone', 'consent');
        
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        if (!is_email($data['email'])) {
            return false;
        }

        return true;
    }

    /**
     * Create consultation order
     */
    private function create_consultation_order($data) {
        $order = wc_create_order();
        
        if (!$order) {
            return false;
        }

        // Add consultation service product
        $consultation_product = $this->get_consultation_product($data['program_id']);
        if ($consultation_product) {
            $order->add_product($consultation_product, 1);
        }

        // Set customer data
        $order->set_billing_first_name($data['first_name']);
        $order->set_billing_last_name($data['last_name']);
        $order->set_billing_email($data['email']);
        $order->set_billing_phone($data['phone']);

        // Add consultation data as meta
        $order->update_meta_data('_consultation_data', $data);
        $order->update_meta_data('_program_id', $data['program_id']);
        $order->update_meta_data('_doctor_id', $data['doctor_id']);

        // Set initial status
        $order->set_status('wc-consultation-pending');

        $order->save();

        return $order;
    }

    /**
     * Get consultation product
     */
    private function get_consultation_product($program_id) {
        // Find or create consultation product for the program
        $products = get_posts(array(
            'post_type' => 'product',
            'meta_query' => array(
                array(
                    'key' => '_program_id',
                    'value' => $program_id,
                    'compare' => '=',
                ),
            ),
            'posts_per_page' => 1,
        ));

        if (!empty($products)) {
            return wc_get_product($products[0]->ID);
        }

        return null;
    }

    /**
     * Sanitize appointment data
     */
    private function sanitize_appointment_data($data) {
        return array(
            'doctor_id' => intval($data['doctor_id']),
            'appointment_date' => sanitize_text_field($data['appointment_date']),
            'appointment_time' => sanitize_text_field($data['appointment_time']),
            'duration' => intval($data['duration']),
            'notes' => sanitize_textarea_field($data['notes']),
            'user_id' => get_current_user_id(),
        );
    }

    /**
     * Validate appointment data
     */
    private function validate_appointment_data($data) {
        if (empty($data['doctor_id']) || empty($data['appointment_date']) || empty($data['appointment_time'])) {
            return false;
        }

        // Check if date is in the future
        if (strtotime($data['appointment_date']) < strtotime('today')) {
            return false;
        }

        return true;
    }

    /**
     * Check if slot is available
     */
    private function is_slot_available($data) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $existing_slot = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE doctor_id = %d AND slot_date = %s AND slot_time = %s AND status = 'available'",
            $data['doctor_id'],
            $data['appointment_date'],
            $data['appointment_time']
        ));

        return $existing_slot !== null;
    }

    /**
     * Create appointment order
     */
    private function create_appointment_order($data) {
        $order = wc_create_order();
        
        if (!$order) {
            return false;
        }

        // Add appointment service product
        $appointment_product = $this->get_appointment_product($data['doctor_id']);
        if ($appointment_product) {
            $order->add_product($appointment_product, 1);
        }

        // Set customer data
        $user = get_user_by('id', $data['user_id']);
        if ($user) {
            $order->set_billing_first_name($user->first_name);
            $order->set_billing_last_name($user->last_name);
            $order->set_billing_email($user->user_email);
        }

        // Add appointment data as meta
        $order->update_meta_data('_appointment_data', $data);
        $order->update_meta_data('_doctor_id', $data['doctor_id']);

        // Set initial status
        $order->set_status('wc-appointment-scheduled');

        $order->save();

        return $order;
    }

    /**
     * Get appointment product
     */
    private function get_appointment_product($doctor_id) {
        // Find or create appointment product for the doctor
        $products = get_posts(array(
            'post_type' => 'product',
            'meta_query' => array(
                array(
                    'key' => '_appointment_doctor',
                    'value' => $doctor_id,
                    'compare' => '=',
                ),
            ),
            'posts_per_page' => 1,
        ));

        if (!empty($products)) {
            return wc_get_product($products[0]->ID);
        }

        return null;
    }

    /**
     * Reserve appointment slot
     */
    private function reserve_appointment_slot($data, $order_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $wpdb->update(
            $table_name,
            array(
                'status' => 'booked',
                'order_id' => $order_id,
                'updated_at' => current_time('mysql'),
            ),
            array(
                'doctor_id' => $data['doctor_id'],
                'slot_date' => $data['appointment_date'],
                'slot_time' => $data['appointment_time'],
            )
        );
    }

    /**
     * Get doctor available slots
     */
    private function get_doctor_available_slots($doctor_id, $date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $slots = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE doctor_id = %d AND slot_date = %s AND status = 'available' ORDER BY slot_time",
            $doctor_id,
            $date
        ));

        return $slots;
    }

    /**
     * Get doctor availability range
     */
    private function get_doctor_availability_range($doctor_id, $start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $slots = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE doctor_id = %d AND slot_date BETWEEN %s AND %s ORDER BY slot_date, slot_time",
            $doctor_id,
            $start_date,
            $end_date
        ));

        return $slots;
    }

    /**
     * Sanitize bulk slot data
     */
    private function sanitize_bulk_slot_data($data) {
        return array(
            'doctor_id' => intval($data['doctor_id']),
            'start_date' => sanitize_text_field($data['start_date']),
            'end_date' => sanitize_text_field($data['end_date']),
            'start_time' => sanitize_text_field($data['start_time']),
            'end_time' => sanitize_text_field($data['end_time']),
            'interval' => intval($data['interval']),
            'days_of_week' => array_map('intval', $data['days_of_week']),
        );
    }

    /**
     * Validate bulk slot data
     */
    private function validate_bulk_slot_data($data) {
        if (empty($data['doctor_id']) || empty($data['start_date']) || empty($data['end_date'])) {
            return false;
        }

        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            return false;
        }

        if (empty($data['days_of_week'])) {
            return false;
        }

        return true;
    }

    /**
     * Create bulk slots
     */
    private function create_bulk_slots($data) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        $created_count = 0;
        
        $start_date = new DateTime($data['start_date']);
        $end_date = new DateTime($data['end_date']);
        
        while ($start_date <= $end_date) {
            $day_of_week = $start_date->format('w'); // 0 = Sunday, 1 = Monday, etc.
            
            if (in_array($day_of_week, $data['days_of_week'])) {
                $slots = $this->generate_time_slots($data['start_time'], $data['end_time'], $data['interval']);
                
                foreach ($slots as $time) {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'doctor_id' => $data['doctor_id'],
                            'slot_date' => $start_date->format('Y-m-d'),
                            'slot_time' => $time,
                            'duration' => $data['interval'],
                            'status' => 'available',
                            'created_at' => current_time('mysql'),
                        )
                    );
                    
                    if ($wpdb->insert_id) {
                        $created_count++;
                    }
                }
            }
            
            $start_date->add(new DateInterval('P1D'));
        }
        
        return $created_count;
    }

    /**
     * Generate time slots
     */
    private function generate_time_slots($start_time, $end_time, $interval) {
        $slots = array();
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        
        while ($start < $end) {
            $slots[] = $start->format('H:i:s');
            $start->add(new DateInterval('PT' . $interval . 'M'));
        }
        
        return $slots;
    }

    /**
     * Delete bulk slots
     */
    private function delete_bulk_slots($doctor_id, $start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $deleted = $wpdb->query($wpdb->prepare(
            "DELETE FROM $table_name WHERE doctor_id = %d AND slot_date BETWEEN %s AND %s AND status = 'available'",
            $doctor_id,
            $start_date,
            $end_date
        ));
        
        return $deleted;
    }

    /**
     * Format order details
     */
    private function format_order_details($order) {
        $details = array(
            'id' => $order->get_id(),
            'status' => $order->get_status(),
            'date' => $order->get_date_created()->format('Y-m-d H:i:s'),
            'total' => $order->get_total(),
            'customer' => array(
                'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                'email' => $order->get_billing_email(),
                'phone' => $order->get_billing_phone(),
            ),
            'items' => array(),
            'meta' => array(),
        );

        // Get order items
        foreach ($order->get_items() as $item) {
            $details['items'][] = array(
                'name' => $item->get_name(),
                'quantity' => $item->get_quantity(),
                'total' => $item->get_total(),
            );
        }

        // Get order meta
        $meta_keys = array('_consultation_data', '_appointment_data', '_product_request_data', '_program_id', '_doctor_id');
        foreach ($meta_keys as $key) {
            $value = $order->get_meta($key);
            if ($value) {
                $details['meta'][$key] = $value;
            }
        }

        return $details;
    }

    /**
     * Sanitize product request data
     */
    private function sanitize_product_request_data($data) {
        return array(
            'product_id' => intval($data['product_id']),
            'quantity' => intval($data['quantity']),
            'notes' => sanitize_textarea_field($data['notes']),
            'user_id' => get_current_user_id(),
        );
    }

    /**
     * Validate product request data
     */
    private function validate_product_request_data($data) {
        if (empty($data['product_id']) || empty($data['quantity'])) {
            return false;
        }

        if ($data['quantity'] <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if consultation is required
     */
    private function is_consultation_required($product_id) {
        return get_post_meta($product_id, '_requires_prescription', true) === 'yes';
    }

    /**
     * Check if user has valid consultation
     */
    private function has_valid_consultation($user_id, $product_id) {
        $consultations = wc_get_orders(array(
            'customer_id' => $user_id,
            'status' => 'wc-consultation-approved',
            'limit' => 1,
        ));

        return !empty($consultations);
    }

    /**
     * Create product request order
     */
    private function create_product_request_order($data) {
        $order = wc_create_order();
        
        if (!$order) {
            return false;
        }

        // Add product
        $product = wc_get_product($data['product_id']);
        if ($product) {
            $order->add_product($product, $data['quantity']);
        }

        // Set customer data
        $user = get_user_by('id', $data['user_id']);
        if ($user) {
            $order->set_billing_first_name($user->first_name);
            $order->set_billing_last_name($user->last_name);
            $order->set_billing_email($user->user_email);
        }

        // Add product request data as meta
        $order->update_meta_data('_product_request_data', $data);

        // Set initial status
        $order->set_status('wc-product-request-pending');

        $order->save();

        return $order;
    }

    /**
     * Get appointment details for popup
     */
    public function get_appointment_details() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);
        
        if (!$appointment_id) {
            wp_send_json_error(array(
                'message' => __('Invalid appointment ID.', 'arta-consult-rx')
            ));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $slot = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $appointment_id
        ));

        if (!$slot) {
            wp_send_json_error(array(
                'message' => __('Appointment not found.', 'arta-consult-rx')
            ));
        }

        // Get doctor information
        $doctor_name = '';
        if ($slot->doctor_id) {
            $doctor = get_post($slot->doctor_id);
            if ($doctor) {
                $doctor_name = $doctor->post_title;
            }
        }

        // Get order information if booked
        $order_info = null;
        if ($slot->order_id) {
            $order = wc_get_order($slot->order_id);
            if ($order) {
                $order_info = array(
                    'id' => $order->get_id(),
                    'status' => $order->get_status(),
                    'total' => $order->get_total(),
                    'customer' => array(
                        'name' => trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()),
                        'email' => $order->get_billing_email(),
                        'phone' => $order->get_billing_phone(),
                    ),
                    'date_created' => $order->get_date_created()->format('Y-m-d H:i:s'),
                );
            }
        }

        $details = array(
            'id' => $slot->id,
            'doctor_id' => $slot->doctor_id,
            'doctor_name' => $doctor_name,
            'slot_date' => $slot->slot_date,
            'slot_time' => $slot->slot_time,
            'duration' => $slot->duration,
            'status' => $slot->status, // Database status (available, booked, completed)
            'formatted_status' => $this->format_status($slot->status), // Formatted status for display
            'order_id' => $slot->order_id,
            'created_at' => $slot->created_at,
            'updated_at' => $slot->updated_at,
            'order_info' => $order_info,
        );

        wp_send_json_success($details);
    }

    /**
     * Edit appointment
     */
    public function edit_appointment() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);
        $doctor_id = intval($_POST['doctor_id']);
        $slot_date = sanitize_text_field($_POST['slot_date']);
        $slot_time = sanitize_text_field($_POST['slot_time']);
        $duration = intval($_POST['duration']);
        $status = sanitize_text_field($_POST['status']);

        if (!$appointment_id || !$doctor_id || !$slot_date || !$slot_time) {
            wp_send_json_error(array(
                'message' => __('Invalid appointment data.', 'arta-consult-rx')
            ));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $updated = $wpdb->update(
            $table_name,
            array(
                'doctor_id' => $doctor_id,
                'slot_date' => $slot_date,
                'slot_time' => $slot_time,
                'duration' => $duration,
                'status' => $status,
                'updated_at' => current_time('mysql'),
            ),
            array('id' => $appointment_id)
        );

        if ($updated === false) {
            wp_send_json_error(array(
                'message' => __('Failed to update appointment.', 'arta-consult-rx')
            ));
        }

        wp_send_json_success(array(
            'message' => __('Appointment updated successfully!', 'arta-consult-rx')
        ));
    }

    /**
     * Delete appointment
     */
    public function delete_appointment() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);
        
        if (!$appointment_id) {
            wp_send_json_error(array(
                'message' => __('Invalid appointment ID.', 'arta-consult-rx')
            ));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointment_slots';
        
        $deleted = $wpdb->delete(
            $table_name,
            array('id' => $appointment_id)
        );

        if ($deleted === false) {
            wp_send_json_error(array(
                'message' => __('Failed to delete appointment.', 'arta-consult-rx')
            ));
        }

        wp_send_json_success(array(
            'message' => __('Appointment deleted successfully!', 'arta-consult-rx')
        ));
    }

    /**
     * Get doctors list for edit form
     */
    public function get_doctors_list() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'arta_admin_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'arta-consult-rx'));
        }

        $doctors = get_posts(array(
            'post_type' => 'arta_doctor',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        $doctors_list = array();
        foreach ($doctors as $doctor) {
            $doctors_list[] = array(
                'id' => $doctor->ID,
                'name' => $doctor->post_title
            );
        }

        wp_send_json_success($doctors_list);
    }

    /**
     * Format status for display
     */
    private function format_status($status) {
        switch ($status) {
            case 'available':
                return 'Available';
            case 'booked':
                return 'Scheduled';
            case 'completed':
                return 'Completed';
            default:
                return 'Pending';
        }
    }
}
