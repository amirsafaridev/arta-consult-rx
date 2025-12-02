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
        add_action('wp_ajax_nopriv_arta_get_user_data', array($this, 'get_user_data'));
        add_action('wp_ajax_arta_add_item_to_consultation', array($this, 'add_item_to_consultation'));
    }   

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        if (is_singular('arta_program')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('arta-appointment-form-widget', ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/appointment-form-widget.js', array('jquery'), ARTA_CONSULT_RX_VERSION, true);
            wp_enqueue_style('arta-appointment-form', ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/appointment-form.css', array(), ARTA_CONSULT_RX_VERSION);
            
            // Get user data for pre-filling
            $user_data = $this->get_user_data_for_form();
            
            wp_localize_script('arta-appointment-form-widget', 'arta_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arta_appointment_nonce'),
                'program_id' => get_the_ID(),
                'user_logged_in' => is_user_logged_in(),
                'user_data' => $user_data,
                'is_rtl' => function_exists('arta_is_rtl') ? arta_is_rtl() : false,
                'direction' => function_exists('arta_get_direction_class') ? arta_get_direction_class() : 'arta-rtl',
                'directionAttr' => function_exists('arta_get_direction_attr') ? arta_get_direction_attr() : 'rtl',
                'strings' => array(
                    'loading' => __('در حال بارگذاری...', 'arta-consult-rx'),
                    'error' => __('خطا در ارسال اطلاعات', 'arta-consult-rx'),
                    'success' => __('درخواست شما با موفقیت ثبت شد', 'arta-consult-rx'),
                    'required_field' => __('این فیلد الزامی است', 'arta-consult-rx'),
                    'invalid_email' => __('ایمیل معتبر نیست', 'arta-consult-rx'),
                    'invalid_phone' => __('شماره تماس معتبر نیست', 'arta-consult-rx'),
                    'step1' => __('اطلاعات شخصی', 'arta-consult-rx'),
                    'step2' => __('انتخاب پزشک', 'arta-consult-rx'),
                    'step3' => __('انتخاب زمان', 'arta-consult-rx'),
                    'personal_info' => __('اطلاعات شخصی', 'arta-consult-rx'),
                    'full_name' => __('نام و نام خانوادگی', 'arta-consult-rx'),
                    'gender' => __('جنسیت', 'arta-consult-rx'),
                    'select_option' => __('انتخاب کنید', 'arta-consult-rx'),
                    'male' => __('مرد', 'arta-consult-rx'),
                    'female' => __('زن', 'arta-consult-rx'),
                    'birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
                    'height' => __('قد (سانتی‌متر)', 'arta-consult-rx'),
                    'weight' => __('وزن (کیلوگرم)', 'arta-consult-rx'),
                    'email' => __('ایمیل', 'arta-consult-rx'),
                    'phone' => __('شماره تماس', 'arta-consult-rx'),
                    'chronic_diseases' => __('بیماری‌های مزمن', 'arta-consult-rx'),
                    'medications' => __('داروهای مصرفی', 'arta-consult-rx'),
                    'medical_history' => __('سوابق درمانی', 'arta-consult-rx'),
                    'program_goal' => __('هدف از برنامه', 'arta-consult-rx'),
                    'select_doctor' => __('انتخاب پزشک', 'arta-consult-rx'),
                    'loading_doctors' => __('لیست پزشکان در حال بارگذاری...', 'arta-consult-rx'),
                    'select_time' => __('انتخاب زمان نوبت', 'arta-consult-rx'),
                    'appointment_date' => __('تاریخ نوبت', 'arta-consult-rx'),
                    'loading_slots' => __('در حال بارگذاری...', 'arta-consult-rx'),
                    'searching' => __('در حال جستجو...', 'arta-consult-rx'),
                    'field_required' => __('این فیلد اجباری است', 'arta-consult-rx'),
                    'select_doctor_first' => __('لطفاً یک پزشک انتخاب کنید', 'arta-consult-rx'),
                    'select_time_first' => __('لطفاً یک زمان انتخاب کنید', 'arta-consult-rx'),
                    'sending' => __('در حال ارسال...', 'arta-consult-rx'),
                    'medical_consultation_required' => __('تایید مشاوره پزشکی الزامی است', 'arta-consult-rx'),
                    'select_doctor_date_first' => __('لطفاً ابتدا پزشک و تاریخ را انتخاب کنید', 'arta-consult-rx'),
                    'no_slots_available' => __('هیچ زمان خالی برای این تاریخ وجود ندارد', 'arta-consult-rx'),
                )
            ));
        }
    }

    /**
     * Handle appointment form submission
     */
    public function handle_appointment_submission() {
        // Verify nonce
        
        // دریافت داده‌ها از form_data
        $form_data = isset($_POST['form_data']) ? $_POST['form_data'] : $_POST;
        

        // Sanitize and validate data
        $data = $this->sanitize_appointment_data($form_data);
        
        $validation_result = $this->validate_appointment_data($data);
        if (!$validation_result['valid']) {
            wp_send_json_error(array(
                'message' => __('اطلاعات وارد شده معتبر نیست', 'arta-consult-rx'),
                'errors' => $validation_result['errors'],
                'data' => $data,
                'post_data' => $_POST
            ));
        }

        // Create consultation post
        $consultation = $this->create_consultation_post($data);
        
        if ($consultation['status'] == false) {
          wp_send_json_error(array('message' => $consultation['message']));
        }
        $consultation_id = $consultation['consultation_id'];
        $appointment_id = $this->get_appointment_id_from_data($data);
        // Update appointment status (فقط اگر appointment_id داریم)
        if ($appointment_id !== false) {
            $this->update_appointment_status($appointment_id,'booked');
        }

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
       

        $doctor_id = intval($_POST['doctor_id']);
        $date = sanitize_text_field($_POST['date']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';

        // Get current date and time
        $current_date = current_time('Y-m-d');
        $current_time = current_time('H:i:s');
        
        error_log('Current date: ' . $current_date);
        error_log('Current time: ' . $current_time);
        error_log('Selected date: ' . $date);

        // Get available slots for the doctor on the selected date that are in the future
        $available_slots = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table_name} 
             WHERE doctor_id = %d 
             AND appointment_date = %s 
             AND status = 'available' 
             AND (
                appointment_date > %s 
                OR (appointment_date = %s AND appointment_time >= %s)
             )
             ORDER BY appointment_time",
            $doctor_id,
            $date,
            $current_date,
            $current_date,
            $current_time
        ));

        wp_send_json_success($available_slots);
    }

    /**
     * Get program doctors
     */
    public function get_program_doctors() {
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
                // Get custom avatar if exists
                $avatar = arta_get_doctor_avatar($doctor->ID, 'thumbnail');
                
                // Get doctor specialty and experience from user meta
                $specialty = get_user_meta($doctor->ID, 'specialty', true);
                $experience = get_user_meta($doctor->ID, 'experience', true);
                
                $doctors_data[] = array(
                    'id' => $doctor->ID,
                    'name' => $doctor->display_name,
                    'image' => $avatar,
                    'specialty' => $specialty ?: 'متخصص',
                    'experience' => $experience ?: 'تجربه کافی'
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
            'programs' => isset($data['program_id']) ? intval($data['program_id']) : 0,
            'products' => isset($data['product_id']) ? sanitize_text_field($data['product_id']) : 0,
            'doctor_id' => isset($data['doctor_id']) ? intval($data['doctor_id']) : 0,
            'appointment_id' => isset($data['appointment_id']) ? intval($data['appointment_id']) : 0,
            'timeslotsselected' => isset($data['timeslotsselected']) ? intval($data['timeslotsselected']) : 0,
            'appointment_date' => isset($data['appointment_date']) ? sanitize_text_field($data['appointment_date']) : '',
            'time_slot' => isset($data['time_slot']) ? sanitize_text_field($data['time_slot']) : '',
            'full_name' => isset($data['full_name']) ? sanitize_text_field($data['full_name']) : '',
            'gender' => isset($data['gender']) ? sanitize_text_field($data['gender']) : '',
            'birth_date' => isset($data['birth_date']) ? sanitize_text_field($data['birth_date']) : '',
            'height' => isset($data['height']) ? sanitize_text_field($data['height']) : '',
            'weight' => isset($data['weight']) ? sanitize_text_field($data['weight']) : '',
            'email' => isset($data['email']) ? sanitize_email($data['email']) : '',
            'phone' => isset($data['phone']) ? sanitize_text_field($data['phone']) : '',
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
   private function get_appointment_by_id($appointment_id){
    global $wpdb;
    $table_name = $wpdb->prefix . 'arta_appointments';
    $appointment = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$table_name} WHERE id = %d",
        $appointment_id
    ));
    return $appointment;
   }
   private function get_appointment_id_from_data($data){
    if(isset($data['timeslotsselected'])){
        return $data['timeslotsselected'];
    }
    if(isset($data['appointment_id'])){
        return $data['appointment_id'];
    }
    return false;
   }
    /**
     * Create consultation post
     */
    private function create_consultation_post($data) {
        // return ["status" => false, "message" => $data];

        $user_id = get_current_user_id();
        $appointment_id = $this->get_appointment_id_from_data($data);
        $appointment = $this->get_appointment_by_id($appointment_id);

        if (!$appointment || $appointment_id < 0 ) {
                return ["status" => false, "message" => __('نوبت یافت نشد', 'arta-consult-rx')];
        }elseif($appointment->status != 'available'){
                return ["status" => false, "message" => __('نوبت موجود نیست', 'arta-consult-rx')];
        }
        $post_data = array(
            'post_title' => sprintf(__('درخواست مشاوره - %s', 'arta-consult-rx'), $data['full_name']),
            'post_content' => $this->format_consultation_content($data),
            'post_status' => 'publish',
            'post_type' => 'arta_consultation',
            'post_author' =>  $user_id ? $user_id : 1,
        );

        $consultation_id = wp_insert_post($post_data);
        
        if ($consultation_id) {
            // Save meta data
            foreach ($data as $key => $value) {

                update_post_meta($consultation_id, '_arta_' . $key, $value);
            }
            $product = $data['products']==0 || $data['products']=='' ? [] : [$data['products']];
            $program = $data['programs']==0 || $data['programs']=='' ? [] : [$data['programs']];
            update_post_meta($consultation_id, '_arta_products', $product);
            update_post_meta($consultation_id, '_arta_programs', $program);

            update_post_meta($consultation_id, '_arta_appointment_date', $appointment->appointment_date);
            update_post_meta($consultation_id, '_arta_appointment_time', $appointment->appointment_time);
            update_post_meta($consultation_id, '_arta_appointment_id', $appointment_id);
          
            // Save user ID if logged in
            
            if ($user_id) {
                update_post_meta($consultation_id, '_arta_user_id', $user_id);
            }
          
            // Set default approval status
            update_post_meta($consultation_id, '_arta_approval_status', 'pending');
        }

        return ["status" => true, "message" => __('درخواست شما با موفقیت ثبت شد', 'arta-consult-rx'), "consultation_id" => $consultation_id];
    }
  
    /**
     * Format consultation content
     */
    private function format_consultation_content($data) {

        
        $content = '<h3>' . __('اطلاعات نوبت', 'arta-consult-rx') . '</h3>';
        
        // اطلاعات نوبت
        if (!empty($data['appointment_date'])) {
            $content .= '<p><strong>' . __('تاریخ نوبت:', 'arta-consult-rx') . '</strong> ' . $data['appointment_date'] . '</p>';
        }
        if (!empty($data['time_slot'])) {
            $content .= '<p><strong>' . __('ساعت نوبت:', 'arta-consult-rx') . '</strong> ' . $data['time_slot'] . '</p>';
        }
        
        $content .= '<h3>' . __('اطلاعات شخصی', 'arta-consult-rx') . '</h3>';
        $content .= '<p><strong>' . __('نام و نام خانوادگی:', 'arta-consult-rx') . '</strong> ' . $data['full_name'] . '</p>';
        $content .= '<p><strong>' . __('جنسیت:', 'arta-consult-rx') . '</strong> ' . ($data['gender'] == 'male' ? 'مرد' : 'زن') . '</p>';
        $content .= '<p><strong>' . __('تاریخ تولد:', 'arta-consult-rx') . '</strong> ' . $data['birth_date'] . '</p>';
        
        if (!empty($data['height'])) {
            $content .= '<p><strong>' . __('قد:', 'arta-consult-rx') . '</strong> ' . $data['height'] . ' سانتی‌متر</p>';
        }
        if (!empty($data['weight'])) {
            $content .= '<p><strong>' . __('وزن:', 'arta-consult-rx') . '</strong> ' . $data['weight'] . ' کیلوگرم</p>';
        }
        
        $content .= '<p><strong>' . __('ایمیل:', 'arta-consult-rx') . '</strong> ' . $data['email'] . '</p>';
        $content .= '<p><strong>' . __('شماره تماس:', 'arta-consult-rx') . '</strong> ' . $data['phone'] . '</p>';

        // اطلاعات پزشکی (اگر وارد شده باشند)
        if (!empty($data['chronic_diseases']) || !empty($data['medications']) || !empty($data['medical_history'])) {
            $content .= '<h3>' . __('اطلاعات پزشکی', 'arta-consult-rx') . '</h3>';
            
            if (!empty($data['chronic_diseases'])) {
                $content .= '<p><strong>' . __('بیماری‌های مزمن:', 'arta-consult-rx') . '</strong><br>' . nl2br($data['chronic_diseases']) . '</p>';
            }
            if (!empty($data['medications'])) {
                $content .= '<p><strong>' . __('داروهای مصرفی:', 'arta-consult-rx') . '</strong><br>' . nl2br($data['medications']) . '</p>';
            }
            if (!empty($data['medical_history'])) {
                $content .= '<p><strong>' . __('سوابق درمانی:', 'arta-consult-rx') . '</strong><br>' . nl2br($data['medical_history']) . '</p>';
            }
        }

        if (!empty($data['program_goal'])) {
            $content .= '<h3>' . __('هدف از برنامه', 'arta-consult-rx') . '</h3>';
            $content .= '<p>' . nl2br($data['program_goal']) . '</p>';
        }

        $content .= '<h3>' . __('تایید مشاوره پزشکی', 'arta-consult-rx') . '</h3>';
        $content .= '<p>' . ($data['medical_consultation'] ? __('تایید شده', 'arta-consult-rx') : __('تایید نشده', 'arta-consult-rx')) . '</p>';

        return $content;
    }

    /**
     * Update appointment status
     */
    private function update_appointment_status($appointment_id, $status='booked') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';
        
        $wpdb->update(
            $table_name,
            array('status' => $status),
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
            'email' => $user->user_email ?? '',
        );

        

        wp_send_json_success($user_data);
    }

    /**
     * Get user data for form pre-filling (non-AJAX version)
     */
    private function get_user_data_for_form() {
        if (!is_user_logged_in()) {
            return array();
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

        return $user_data;
    }

    /**
     * Add item (program or product) to existing consultation
     */
    public function add_item_to_consultation() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'arta_add_item_to_consultation')) {
            wp_send_json_error(array('message' => 'خطای امنیتی'));
            return;
        }

        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'لطفاً وارد شوید'));
            return;
        }

        // Get parameters
        $consultation_id = isset($_POST['consultation_id']) ? intval($_POST['consultation_id']) : 0;
        $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
        $item_type = isset($_POST['item_type']) ? sanitize_text_field($_POST['item_type']) : '';

        // Validate parameters
        if (empty($consultation_id) || empty($item_id) || empty($item_type)) {
            wp_send_json_error(array('message' => 'اطلاعات ناقص است'));
            return;
        }

        // Verify consultation exists and belongs to current user
        $consultation = get_post($consultation_id);
        if (!$consultation || $consultation->post_type !== 'arta_consultation') {
            wp_send_json_error(array('message' => 'درخواست یافت نشد'));
            return;
        }

        if ($consultation->post_author != get_current_user_id()) {
            wp_send_json_error(array('message' => 'شما دسترسی به این درخواست ندارید'));
            return;
        }

        // Get existing items
        if ($item_type === 'program') {
            $existing_items = get_post_meta($consultation_id, '_arta_programs', true);
            if (!is_array($existing_items)) {
                $existing_items = array();
            }

            // Check if already added
            if (in_array($item_id, $existing_items)) {
                wp_send_json_error(array('message' => 'این برنامه قبلاً به درخواست اضافه شده است'));
                return;
            }

            // Add to array
            $existing_items[] = $item_id;
            update_post_meta($consultation_id, '_arta_programs', $existing_items);

            wp_send_json_success(array('message' => 'برنامه با موفقیت به درخواست شما اضافه شد'));
        } elseif ($item_type === 'product') {
            $existing_items = get_post_meta($consultation_id, '_arta_products', true);
            if (!is_array($existing_items)) {
                $existing_items = array();
            }

            // Check if already added
            if (in_array($item_id, $existing_items)) {
                wp_send_json_error(array('message' => 'این محصول قبلاً به درخواست اضافه شده است'));
                return;
            }

            // Add to array
            $existing_items[] = $item_id;
            update_post_meta($consultation_id, '_arta_products', $existing_items);

            wp_send_json_success(array('message' => 'محصول با موفقیت به درخواست شما اضافه شد'));
        } else {
            wp_send_json_error(array('message' => 'نوع آیتم نامعتبر است'));
        }
    }
}

// Initialize the class
new Arta_Appointment_Form();
