<?php
/**
 * Arta Appointment Form Handler
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Arta_Appointment_Form {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_arta_submit_appointment', array($this, 'handle_appointment_submission'));
        add_action('wp_ajax_nopriv_arta_submit_appointment', array($this, 'handle_appointment_submission'));
        add_action('wp_ajax_arta_get_available_slots', array($this, 'get_available_slots'));
        add_action('wp_ajax_nopriv_arta_get_available_slots', array($this, 'get_available_slots'));
        add_action('wp_ajax_arta_get_program_doctors', array($this, 'get_program_doctors'));
        add_action('wp_ajax_nopriv_arta_get_program_doctors', array($this, 'get_program_doctors'));
        add_action('wp_ajax_arta_get_user_data', array($this, 'get_user_data'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        if (is_singular('arta_program')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('arta-appointment-form', ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/appointment-form.js', array('jquery'), ARTA_CONSULT_RX_VERSION, true);
            wp_enqueue_style('arta-appointment-form', ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/appointment-form.css', array(), ARTA_CONSULT_RX_VERSION);
            
            wp_localize_script('arta-appointment-form', 'arta_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arta_appointment_nonce'),
                'program_id' => get_the_ID(),
                'strings' => array(
                    'loading' => __('در حال بارگذاری...', 'arta-consult-rx'),
                    'error' => __('خطا در ارسال اطلاعات', 'arta-consult-rx'),
                    'success' => __('درخواست شما با موفقیت ثبت شد', 'arta-consult-rx'),
                    'required_field' => __('این فیلد الزامی است', 'arta-consult-rx'),
                    'invalid_email' => __('ایمیل معتبر نیست', 'arta-consult-rx'),
                    'invalid_phone' => __('شماره تماس معتبر نیست', 'arta-consult-rx'),
                )
            ));
        }
    }

    /**
     * Handle appointment form submission
     */
    public function handle_appointment_submission() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        // Sanitize and validate data
        $data = $this->sanitize_appointment_data($_POST);
        
        $validation_result = $this->validate_appointment_data($data);
        if (!$validation_result['valid']) {
            wp_send_json_error(array(
                'message' => __('اطلاعات وارد شده معتبر نیست', 'arta-consult-rx'),
                'errors' => $validation_result['errors']
            ));
        }

        // Create consultation post
        $consultation_id = $this->create_consultation_post($data);
        
        if (!$consultation_id) {
            wp_send_json_error(array('message' => __('خطا در ثبت درخواست', 'arta-consult-rx')));
        }

        // Update appointment status
        $this->update_appointment_status($data['appointment_id']);

        // Save user data if logged in
        if (is_user_logged_in()) {
            $this->save_user_data($data);
        }

        // Send confirmation email
        $this->send_confirmation_email($data, $consultation_id);

        wp_send_json_success(array(
            'message' => __('درخواست شما با موفقیت ثبت شد. ایمیل تاییدیه برای شما ارسال شد.', 'arta-consult-rx'),
            'consultation_id' => $consultation_id
        ));
    }

    /**
     * Get available time slots for a doctor
     */
    public function get_available_slots() {
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        $doctor_id = intval($_POST['doctor_id']);
        $date = sanitize_text_field($_POST['date']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';

        // Get available slots for the doctor on the selected date
        $available_slots = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table_name} 
             WHERE doctor_id = %d 
             AND appointment_date = %s 
             AND status = 'available' 
             ORDER BY appointment_time",
            $doctor_id,
            $date
        ));

        wp_send_json_success($available_slots);
    }

    /**
     * Get program doctors
     */
    public function get_program_doctors() {
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        $program_id = intval($_POST['program_id']);
        
        // Get doctors from program meta
        $doctors = get_post_meta($program_id, '_arta_program_doctors', true);
        
        if (!is_array($doctors)) {
            $doctors = array();
        }
        
        $doctors_data = array();
        foreach ($doctors as $doctor_id) {
            $doctor = get_user_by('ID', $doctor_id);
            if ($doctor) {
                $doctors_data[] = array(
                    'id' => $doctor->ID,
                    'name' => $doctor->display_name,
                    'avatar' => get_avatar_url($doctor->ID, array('size' => 80))
                );
            }
        }
        
        wp_send_json_success($doctors_data);
    }

    /**
     * Sanitize appointment data
     */
    private function sanitize_appointment_data($data) {
        return array(
            'program_id' => intval($data['program_id']),
            'doctor_id' => intval($data['doctor_id']),
            'appointment_id' => intval($data['appointment_id']),
            'full_name' => sanitize_text_field($data['full_name']),
            'gender' => sanitize_text_field($data['gender']),
            'birth_date' => sanitize_text_field($data['birth_date']),
            'height' => isset($data['height']) ? sanitize_text_field($data['height']) : '',
            'weight' => isset($data['weight']) ? sanitize_text_field($data['weight']) : '',
            'email' => isset($data['email']) ? sanitize_email($data['email']) : '',
            'phone' => sanitize_text_field($data['phone']),
            'chronic_diseases' => isset($data['chronic_diseases']) ? sanitize_textarea_field($data['chronic_diseases']) : '',
            'medications' => isset($data['medications']) ? sanitize_textarea_field($data['medications']) : '',
            'medical_history' => isset($data['medical_history']) ? sanitize_textarea_field($data['medical_history']) : '',
            'program_goal' => isset($data['program_goal']) ? sanitize_textarea_field($data['program_goal']) : '',
            'medical_consultation' => isset($data['medical_consultation']) ? 1 : 0,
        );
    }

    /**
     * Validate appointment data
     */
    private function validate_appointment_data($data) {
        $errors = array();
        $required_fields = array(
            'full_name' => __('نام و نام خانوادگی', 'arta-consult-rx'),
            'gender' => __('جنسیت', 'arta-consult-rx'),
            'birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
            'email' => __('ایمیل', 'arta-consult-rx'),
            'phone' => __('شماره تماس', 'arta-consult-rx')
        );
        
        foreach ($required_fields as $field => $label) {
            if (empty($data[$field])) {
                $errors[] = sprintf(__('%s الزامی است', 'arta-consult-rx'), $label);
            }
        }

        if (!empty($data['email']) && !is_email($data['email'])) {
            $errors[] = __('فرمت ایمیل معتبر نیست', 'arta-consult-rx');
        }

        if (!empty($data['phone']) && !preg_match('/^09[0-9]{9}$/', $data['phone'])) {
            $errors[] = __('شماره تماس باید با 09 شروع شود و 11 رقم باشد', 'arta-consult-rx');
        }

        return array(
            'valid' => empty($errors),
            'errors' => $errors
        );
    }

    /**
     * Create consultation post
     */
    private function create_consultation_post($data) {
        $post_data = array(
            'post_title' => sprintf(__('درخواست مشاوره - %s', 'arta-consult-rx'), $data['full_name']),
            'post_content' => $this->format_consultation_content($data),
            'post_status' => 'publish',
            'post_type' => 'arta_consultation',
            'post_author' => is_user_logged_in() ? get_current_user_id() : 1,
        );

        $consultation_id = wp_insert_post($post_data);

        if ($consultation_id) {
            // Save meta data
            foreach ($data as $key => $value) {
                update_post_meta($consultation_id, '_arta_' . $key, $value);
            }
            
            // Set appointment date and time
            global $wpdb;
            $table_name = $wpdb->prefix . 'arta_appointments';
            $appointment = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$table_name} WHERE id = %d",
                $data['appointment_id']
            ));
            
            if ($appointment) {
                update_post_meta($consultation_id, '_arta_appointment_date', $appointment->appointment_date);
                update_post_meta($consultation_id, '_arta_appointment_time', $appointment->appointment_time);
            }
            
            // Set default approval status
            update_post_meta($consultation_id, '_arta_approval_status', 'pending');
        }

        return $consultation_id;
    }

    /**
     * Format consultation content
     */
    private function format_consultation_content($data) {
        $content = '<h3>' . __('اطلاعات شخصی', 'arta-consult-rx') . '</h3>';
        $content .= '<p><strong>' . __('نام و نام خانوادگی:', 'arta-consult-rx') . '</strong> ' . $data['full_name'] . '</p>';
        $content .= '<p><strong>' . __('جنسیت:', 'arta-consult-rx') . '</strong> ' . $data['gender'] . '</p>';
        $content .= '<p><strong>' . __('تاریخ تولد:', 'arta-consult-rx') . '</strong> ' . $data['birth_date'] . '</p>';
        $content .= '<p><strong>' . __('قد:', 'arta-consult-rx') . '</strong> ' . $data['height'] . ' سانتی‌متر</p>';
        $content .= '<p><strong>' . __('وزن:', 'arta-consult-rx') . '</strong> ' . $data['weight'] . ' کیلوگرم</p>';
        $content .= '<p><strong>' . __('ایمیل:', 'arta-consult-rx') . '</strong> ' . $data['email'] . '</p>';
        $content .= '<p><strong>' . __('شماره تماس:', 'arta-consult-rx') . '</strong> ' . $data['phone'] . '</p>';

        $content .= '<h3>' . __('اطلاعات پزشکی', 'arta-consult-rx') . '</h3>';
        $content .= '<p><strong>' . __('بیماری‌های مزمن:', 'arta-consult-rx') . '</strong><br>' . $data['chronic_diseases'] . '</p>';
        $content .= '<p><strong>' . __('داروهای مصرفی:', 'arta-consult-rx') . '</strong><br>' . $data['medications'] . '</p>';
        $content .= '<p><strong>' . __('سوابق درمانی:', 'arta-consult-rx') . '</strong><br>' . $data['medical_history'] . '</p>';

        $content .= '<h3>' . __('هدف از برنامه', 'arta-consult-rx') . '</h3>';
        $content .= '<p>' . $data['program_goal'] . '</p>';

        $content .= '<h3>' . __('تایید مشاوره پزشکی', 'arta-consult-rx') . '</h3>';
        $content .= '<p>' . ($data['medical_consultation'] ? __('تایید شده', 'arta-consult-rx') : __('تایید نشده', 'arta-consult-rx')) . '</p>';

        return $content;
    }

    /**
     * Update appointment status
     */
    private function update_appointment_status($appointment_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';
        
        $wpdb->update(
            $table_name,
            array('status' => 'booked'),
            array('id' => $appointment_id),
            array('%s'),
            array('%d')
        );
    }

    /**
     * Save user data if logged in
     */
    private function save_user_data($data) {
        $user_id = get_current_user_id();
        
        // Save all form data to user meta
        update_user_meta($user_id, 'arta_full_name', $data['full_name']);
        update_user_meta($user_id, 'arta_gender', $data['gender']);
        update_user_meta($user_id, 'arta_birth_date', $data['birth_date']);
        update_user_meta($user_id, 'arta_height', $data['height']);
        update_user_meta($user_id, 'arta_weight', $data['weight']);
        update_user_meta($user_id, 'arta_phone', $data['phone']);
        update_user_meta($user_id, 'arta_chronic_diseases', $data['chronic_diseases']);
        update_user_meta($user_id, 'arta_medications', $data['medications']);
        update_user_meta($user_id, 'arta_medical_history', $data['medical_history']);
        update_user_meta($user_id, 'arta_program_goal', $data['program_goal']);
        
        // Handle email logic
        $user = get_user_by('ID', $user_id);
        $user_email = $user ? $user->user_email : '';
        
        // If user has email in profile, use it and don't save custom email
        if ($user_email) {
            // Don't save custom email to user meta, use profile email
            update_user_meta($user_id, 'arta_has_profile_email', '1');
        } else {
            // User doesn't have email in profile, save the one they entered
            update_user_meta($user_id, 'arta_email', $data['email']);
            update_user_meta($user_id, 'arta_has_profile_email', '0');
        }
    }

    /**
     * Send confirmation email
     */
    private function send_confirmation_email($data, $consultation_id) {
        $to = $data['email'];
        $subject = __('تایید رزرو نوبت مشاوره', 'arta-consult-rx');
        
        // Get doctor info
        $doctor = get_user_by('ID', $data['doctor_id']);
        $doctor_name = $doctor ? $doctor->display_name : __('نامشخص', 'arta-consult-rx');
        
        // Get program info
        $program = get_post($data['program_id']);
        $program_title = $program ? $program->post_title : __('نامشخص', 'arta-consult-rx');
        
        // Get appointment info
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';
        $appointment = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE id = %d",
            $data['appointment_id']
        ));
        
        $appointment_date = $appointment ? $appointment->appointment_date : __('نامشخص', 'arta-consult-rx');
        $appointment_time = $appointment ? $appointment->appointment_time : __('نامشخص', 'arta-consult-rx');
        
        $message = $this->get_email_template($data, $doctor_name, $program_title, $appointment_date, $appointment_time, $consultation_id);
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Get email template
     */
    private function get_email_template($data, $doctor_name, $program_title, $appointment_date, $appointment_time, $consultation_id) {
        $template = '
        <!DOCTYPE html>
        <html dir="rtl" lang="fa">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>تایید رزرو نوبت</title>
            <style>
                body { font-family: Tahoma, Arial, sans-serif; direction: rtl; text-align: right; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2196f3; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-right: 4px solid #2196f3; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>تایید رزرو نوبت مشاوره</h1>
                </div>
                <div class="content">
                    <p>سلام ' . $data['full_name'] . '،</p>
                    <p>درخواست رزرو نوبت شما با موفقیت ثبت شد. جزئیات نوبت شما به شرح زیر است:</p>
                    
                    <div class="info-box">
                        <h3>اطلاعات نوبت</h3>
                        <p><strong>برنامه:</strong> ' . $program_title . '</p>
                        <p><strong>پزشک:</strong> ' . $doctor_name . '</p>
                        <p><strong>تاریخ:</strong> ' . $appointment_date . '</p>
                        <p><strong>ساعت:</strong> ' . $appointment_time . '</p>
                        <p><strong>شماره درخواست:</strong> ' . $consultation_id . '</p>
                    </div>
                    
                    <div class="info-box">
                        <h3>اطلاعات تماس</h3>
                        <p><strong>ایمیل:</strong> ' . $data['email'] . '</p>
                        <p><strong>تلفن:</strong> ' . $data['phone'] . '</p>
                    </div>
                    
                    <p>لطفاً 15 دقیقه قبل از زمان نوبت در محل حضور داشته باشید.</p>
                    <p>در صورت نیاز به تغییر یا لغو نوبت، با ما تماس بگیرید.</p>
                </div>
                <div class="footer">
                    <p>این ایمیل به صورت خودکار ارسال شده است. لطفاً پاسخ ندهید.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $template;
    }

    /**
     * Get user data for logged in users
     */
    public function get_user_data() {
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('کاربر لاگین نشده', 'arta-consult-rx')));
        }

        $user_id = get_current_user_id();
        $user = get_user_by('ID', $user_id);
        
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

        // Handle email logic
        $has_profile_email = get_user_meta($user_id, 'arta_has_profile_email', true);
        
        if ($has_profile_email && $user && $user->user_email) {
            // User has email in profile
            $user_data['has_profile_email'] = true;
            $user_data['profile_email'] = $user->user_email;
        } else {
            // User has custom email or no email
            $user_data['has_profile_email'] = false;
            $user_data['email'] = get_user_meta($user_id, 'arta_email', true);
        }

        wp_send_json_success($user_data);
    }
}

// Initialize the class
new Arta_Appointment_Form();
