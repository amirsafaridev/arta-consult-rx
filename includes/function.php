<?php

// Include Elementor widgets
add_action('elementor/init', function() {
    require_once ARTA_CONSULT_RX_PLUGIN_DIR . 'includes/Elementor/widgets-loader.php';
});

/**
 * Main instance of Arta_Consult_RX
 *
 * @return Arta_Consult_RX
 */
function arta_consult_rx() {
    return Arta_Consult_RX::instance();
}

/**
 * Check if current language is RTL
 *
 * @return bool
 */
function arta_is_rtl() {
    // Check WordPress is_rtl function first (works in both admin and frontend)
    if (function_exists('is_rtl')) {
        return is_rtl();
    }
    
    // Check WordPress global is_rtl
    global $wp_locale;
    if (isset($wp_locale) && is_object($wp_locale) && isset($wp_locale->text_direction)) {
        return ($wp_locale->text_direction === 'rtl');
    }
    
    // Fallback: check locale
    $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
    
    // Also check current locale if available
    if (empty($locale) && function_exists('determine_locale')) {
        $locale = determine_locale();
    }
    
    // RTL languages
    $rtl_languages = array('fa_IR', 'fa', 'ar', 'he_IL', 'he', 'ur');
    
    // Check if locale starts with RTL language codes
    foreach ($rtl_languages as $rtl_lang) {
        if (strpos($locale, $rtl_lang) === 0) {
            return true;
        }
    }
    
    return false;
}

/**
 * Get text direction class based on language
 *
 * @return string
 */
function arta_get_direction_class() {
    return arta_is_rtl() ? 'arta-rtl' : 'arta-ltr';
}

/**
 * Get text direction attribute based on language
 *
 * @return string
 */
function arta_get_direction_attr() {
    return arta_is_rtl() ? 'rtl' : 'ltr';
}

// Initialize the plugin
arta_consult_rx();

// Enqueue Elementor widgets styles and scripts
add_action('wp_enqueue_scripts', function() {
    if (class_exists('Elementor\Plugin')) {
        wp_enqueue_style(
            'arta-elementor-widgets',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/elementor-widgets.css',
            array(),
            ARTA_CONSULT_RX_VERSION
        );
        
        wp_enqueue_script(
            'arta-appointment-form-widget',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/appointment-form-widget.js',
            array('jquery'),
            ARTA_CONSULT_RX_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('arta-appointment-form-widget', 'arta_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('arta_appointment_form_nonce'),
            'plugin_url' => ARTA_CONSULT_RX_PLUGIN_URL,
            'debug' => WP_DEBUG
        ));
    }
});

// AJAX handlers for appointment form
add_action('wp_ajax_arta_get_user_data', 'arta_get_user_data');
add_action('wp_ajax_arta_get_program_doctors', 'arta_get_program_doctors');
add_action('wp_ajax_nopriv_arta_get_program_doctors', 'arta_get_program_doctors');
add_action('wp_ajax_arta_get_available_time_slots', 'arta_get_available_time_slots');
add_action('wp_ajax_nopriv_arta_get_available_time_slots', 'arta_get_available_time_slots');
add_action('wp_ajax_arta_submit_appointment_form', 'arta_submit_appointment_form');
add_action('wp_ajax_nopriv_arta_submit_appointment_form', 'arta_submit_appointment_form');

// Test AJAX handler
add_action('wp_ajax_arta_test_ajax', 'arta_test_ajax');
add_action('wp_ajax_nopriv_arta_test_ajax', 'arta_test_ajax');

/**
 * Test AJAX handler
 */
function arta_test_ajax() {
    error_log('Test AJAX called');
    wp_send_json_success(array('message' => 'AJAX is working!', 'timestamp' => current_time('mysql')));
}

/**
 * Get user data for form pre-filling
 */
function arta_get_user_data() {
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        wp_send_json_error(array('message' => 'User not logged in'));
        return;
    }
    
    $user_data = array(
        'full_name' => get_user_meta($user_id, 'arta_full_name', true),
        'gender' => get_user_meta($user_id, 'arta_gender', true),
        'birth_date' => get_user_meta($user_id, 'arta_birth_date', true),
        'height' => get_user_meta($user_id, 'arta_height', true),
        'weight' => get_user_meta($user_id, 'arta_weight', true),
        'phone' => get_user_meta($user_id, 'arta_phone', true),
        'chronic_diseases' => get_user_meta($user_id, 'arta_chronic_diseases', true),
        'medications' => get_user_meta($user_id, 'arta_medications', true),
        'medical_history' => get_user_meta($user_id, 'arta_medical_history', true),
        'program_goal' => get_user_meta($user_id, 'arta_program_goal', true),
    );
    
    // Get email from user profile
    $user = get_user_by('ID', $user_id);
    if ($user) {
        $user_data['email'] = $user->user_email;
    }
    
    wp_send_json_success($user_data);
}

/**
 * Get program doctors
 */
function arta_get_program_doctors() {
    try {
        $program_id = intval($_POST['program_id']);
        $doctors = array();
        
        error_log('Getting doctors for program ID: ' . $program_id);
        
        if ($program_id) {
            $doctor_ids = get_post_meta($program_id, '_arta_program_doctors', true);
            error_log('Doctor IDs from meta: ' . print_r($doctor_ids, true));
            
            if (is_array($doctor_ids) && !empty($doctor_ids)) {
                foreach ($doctor_ids as $doctor_id) {
                    $doctor = get_user_by('ID', $doctor_id);
                    if ($doctor) {
                        $avatar_url = '';
                        if (function_exists('arta_get_doctor_avatar')) {
                            $avatar_url = arta_get_doctor_avatar($doctor_id, 'medium');
                        } else {
                            $avatar_url = get_avatar_url($doctor_id, array('size' => 150));
                        }
                        
                        $doctors[] = array(
                            'id' => $doctor_id,
                            'name' => $doctor->display_name,
                            'description' => get_user_meta($doctor_id, 'description', true) ?: 'پزشک متخصص',
                            'avatar' => $avatar_url
                        );
                    }
                }
            } else {
                error_log('No doctor IDs found for program: ' . $program_id);
            }
        } else {
            error_log('Invalid program ID: ' . $program_id);
        }
        
        error_log('Returning doctors: ' . print_r($doctors, true));
        wp_send_json_success($doctors);
        
    } catch (Exception $e) {
        error_log('Error in arta_get_program_doctors: ' . $e->getMessage());
        wp_send_json_error(array('message' => 'خطا در دریافت لیست پزشکان: ' . $e->getMessage()));
    }
}

/**
 * Get available time slots
 */
function arta_get_available_time_slots() {
    $doctor_id = intval($_POST['doctor_id']);
    $date = sanitize_text_field($_POST['date']);
    $time_slots = array();
    
    if ($doctor_id && $date) {
        // Get available time slots for the doctor on the selected date
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';
        
        $available_slots = $wpdb->get_results($wpdb->prepare(
            "SELECT appointment_time, status FROM {$table_name} 
             WHERE doctor_id = %d AND appointment_date = %s AND status = 'available'",
            $doctor_id,
            $date
        ));
        
        foreach ($available_slots as $slot) {
            $time_slots[] = array(
                'time' => $slot->appointment_time,
                'available' => true
            );
        }
    }
    
    wp_send_json_success($time_slots);
}

/**
 * Submit appointment form
 */
function arta_submit_appointment_form() {
    $form_data = $_POST['form_data'];
    
    // Create consultation post
    $post_data = array(
        'post_type' => 'arta_consultation',
        'post_title' => 'درخواست مشاوره - ' . sanitize_text_field($form_data['full_name']),
        'post_status' => 'publish',
        'meta_input' => array(
            '_arta_full_name' => sanitize_text_field($form_data['full_name']),
            '_arta_gender' => sanitize_text_field($form_data['gender']),
            '_arta_birth_date' => sanitize_text_field($form_data['birth_date']),
            '_arta_height' => sanitize_text_field($form_data['height']),
            '_arta_weight' => sanitize_text_field($form_data['weight']),
            '_arta_email' => sanitize_email($form_data['email']),
            '_arta_phone' => sanitize_text_field($form_data['phone']),
            '_arta_chronic_diseases' => sanitize_textarea_field($form_data['chronic_diseases']),
            '_arta_medications' => sanitize_textarea_field($form_data['medications']),
            '_arta_medical_history' => sanitize_textarea_field($form_data['medical_history']),
            '_arta_program_goal' => sanitize_textarea_field($form_data['program_goal']),
            '_arta_medical_consultation' => sanitize_text_field($form_data['medical_consultation']),
            '_arta_program_id' => intval($form_data['program_id']),
            '_arta_doctor_id' => intval($form_data['doctor_id']),
            '_arta_appointment_date' => sanitize_text_field($form_data['appointment_date']),
            '_arta_approval_status' => 'pending'
        )
    );
    
    $post_id = wp_insert_post($post_data);
    
    if ($post_id) {
        wp_send_json_success(array(
            'message' => 'درخواست شما با موفقیت ثبت شد. به زودی با شما تماس خواهیم گرفت.'
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'خطا در ثبت درخواست. لطفاً دوباره تلاش کنید.'
        ));
    }
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, array(arta_consult_rx(), 'activate'));
register_deactivation_hook(__FILE__, array(arta_consult_rx(), 'deactivate'));