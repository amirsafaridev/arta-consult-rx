<?php
/**
 * Appointment Form Widget
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Appointment Form Widget Class
 */
class Arta_Appointment_Form_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'arta_appointment_form';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('فرم رزرو نوبت مشاوره', 'arta-consult-rx');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['arta-consult-rx'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['appointment', 'form', 'consultation', 'booking', 'نوبت', 'مشاوره', 'رزرو'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('محتوای فرم', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'form_description',
            [
                'label' => __('توضیحات فرم', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => __('توضیحات کوتاه در مورد فرم', 'arta-consult-rx'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'auto_detect_program',
            [
                'label' => __('تشخیص خودکار برنامه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('فعال', 'arta-consult-rx'),
                'label_off' => __('غیرفعال', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('برنامه را از صفحه فعلی تشخیص دهد', 'arta-consult-rx'),
            ]
        );



        $this->add_control(
            'program_id',
            [
                'label' => __('برنامه مرتبط', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_programs_options(),
                'default' => '',
                'description' => __('برنامه مرتبط با این فرم را انتخاب کنید', 'arta-consult-rx'),
                'condition' => [
                    'auto_detect_program' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Login Required Message Section
        $this->start_controls_section(
            'login_required_content_section',
            [
                'label' => __('پیام نیاز به ورود', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'require_login',
            [
                'label' => __('نیاز به ورود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('بله', 'arta-consult-rx'),
                'label_off' => __('خیر', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('آیا کاربر باید وارد شده باشد تا بتواند فرم را پر کند؟', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'login_required_title',
            [
                'label' => __('عنوان پیام', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('برای رزرو نوبت باید وارد شوید', 'arta-consult-rx'),
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'login_required_message',
            [
                'label' => __('متن پیام', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('لطفاً ابتدا وارد حساب کاربری خود شوید تا بتوانید نوبت مشاوره رزرو کنید.', 'arta-consult-rx'),
                'rows' => 3,
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'login_button_text',
            [
                'label' => __('متن دکمه ورود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('ورود', 'arta-consult-rx'),
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'login_button_link',
            [
                'label' => __('لینک دکمه ورود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-login-page.com', 'arta-consult-rx'),
                'default' => [
                    'url' => wp_login_url(),
                ],
                'description' => __('لینک صفحه ورود را وارد کنید. برای استفاده از صفحه ورود پیش‌فرض وردپرس، خالی بگذارید. از لینک‌های داینامیک مانند {{home_url}}/login نیز پشتیبانی می‌شود.', 'arta-consult-rx'),
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'login_button_target',
            [
                'label' => __('باز کردن در تب جدید', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('بله', 'arta-consult-rx'),
                'label_off' => __('خیر', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'login_button_nofollow',
            [
                'label' => __('افزودن nofollow', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('بله', 'arta-consult-rx'),
                'label_off' => __('خیر', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => '',
                'description' => __('برای لینک‌های خارجی توصیه می‌شود', 'arta-consult-rx'),
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Form Fields Section
        $this->start_controls_section(
            'fields_section',
            [
                'label' => __('فیلدهای فرم', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Personal Information Fields
        $this->add_control(
            'show_personal_info',
            [
                'label' => __('نمایش اطلاعات شخصی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('نمایش', 'arta-consult-rx'),
                'label_off' => __('مخفی', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'required_fields',
            [
                'label' => __('فیلدهای اجباری', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'full_name' => __('نام و نام خانوادگی', 'arta-consult-rx'),
                    'gender' => __('جنسیت', 'arta-consult-rx'),
                    'birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
                    'email' => __('ایمیل', 'arta-consult-rx'),
                    'phone' => __('شماره تماس', 'arta-consult-rx'),
                    'program_goal' => __('هدف از برنامه', 'arta-consult-rx'),
                    'medical_consultation' => __('تایید مشاوره پزشکی', 'arta-consult-rx'),
                ],
                'default' => ['full_name', 'gender', 'birth_date', 'email', 'phone', 'program_goal', 'medical_consultation'],
                'condition' => [
                    'show_personal_info' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'medical_consultation_text',
            [
                'label' => __('متن تایید مشاوره پزشکی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('تایید مشاوره پزشکی', 'arta-consult-rx'),
                'description' => __('متن چک‌باکس تایید مشاوره پزشکی را وارد کنید', 'arta-consult-rx'),
                'condition' => [
                    'show_personal_info' => 'yes',
                    'required_fields' => 'medical_consultation',
                ],
            ]
        );

        $this->add_control(
            'show_medical_info',
            [
                'label' => __('نمایش اطلاعات پزشکی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('نمایش', 'arta-consult-rx'),
                'label_off' => __('مخفی', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_personal_info' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_doctor_selection',
            [
                'label' => __('نمایش انتخاب پزشک', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('نمایش', 'arta-consult-rx'),
                'label_off' => __('مخفی', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_time_selection',
            [
                'label' => __('نمایش انتخاب زمان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('نمایش', 'arta-consult-rx'),
                'label_off' => __('مخفی', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Button Section
        $this->start_controls_section(
            'button_section',
            [
                'label' => __('دکمه‌ها', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('متن دکمه شروع', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('رزرو نوبت مشاوره', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'next_button_text',
            [
                'label' => __('متن دکمه بعدی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('بعدی', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'prev_button_text',
            [
                'label' => __('متن دکمه قبلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('قبلی', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'submit_button_text',
            [
                'label' => __('متن دکمه ارسال', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('ثبت درخواست', 'arta-consult-rx'),
            ]
        );

        $this->end_controls_section();

        // No Data Messages Section
        $this->start_controls_section(
            'no_data_messages_section',
            [
                'label' => __('پیام‌های عدم وجود داده', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'no_data_select_message',
            [
                'label' => __('متن انتخاب پزشک و تاریخ', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('لطفاً ابتدا پزشک و تاریخ را انتخاب کنید', 'arta-consult-rx'),
                'description' => __('پیامی که وقتی پزشک و تاریخ انتخاب نشده نمایش داده می‌شود', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'no_data_slots_message',
            [
                'label' => __('متن عدم وجود زمان خالی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('هیچ زمان خالی برای این تاریخ وجود ندارد', 'arta-consult-rx'),
                'description' => __('پیامی که وقتی زمانی خالی نیست نمایش داده می‌شود', 'arta-consult-rx'),
            ]
        );

        $this->end_controls_section();

        // Success Message Section
        $this->start_controls_section(
            'success_message_section',
            [
                'label' => __('پیام موفقیت', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

       

        $this->add_control(
            'success_title',
            [
                'label' => __('عنوان پیام موفقیت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('درخواست شما با موفقیت ثبت شد!', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label' => __('متن پیام موفقیت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('درخواست مشاوره شما با موفقیت ثبت شد. به زودی با شما تماس خواهیم گرفت.', 'arta-consult-rx'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'success_button_text',
            [
                'label' => __('متن دکمه بازگشت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('بازگشت به صفحه اصلی', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'success_button_link',
            [
                'label' => __('لینک دکمه بازگشت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'arta-consult-rx'),
                'default' => [
                    'url' => home_url('/'),
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Form
        $this->start_controls_section(
            'form_style_section',
            [
                'label' => __('استایل فرم', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'form_background',
                'label' => __('پس‌زمینه فرم', 'arta-consult-rx'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .arta-appointment-form',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'form_border',
                'label' => __('حاشیه فرم', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-appointment-form',
            ]
        );

        $this->add_control(
            'form_border_radius',
            [
                'label' => __('شعاع حاشیه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-appointment-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'form_padding',
            [
                'label' => __('فاصله داخلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-appointment-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'form_box_shadow',
                'label' => __('سایه فرم', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-appointment-form, {{WRAPPER}} .arta-appointment-form-direct',
            ]
        );

        $this->end_controls_section();

        // Style Section - Modal
        $this->start_controls_section(
            'modal_style_section',
            [
                'label' => __('استایل مودال', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'modal_content_box_shadow',
                'label' => __('سایه محتوای مودال', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-modal-content',
            ]
        );

        $this->end_controls_section();


        // Style Section - Fields
        $this->start_controls_section(
            'fields_style_section',
            [
                'label' => __('استایل فیلدها', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_label_color',
            [
                'label' => __('رنگ برچسب فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_label_alignment',
            [
                'label' => __('تراز برچسب فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __('راست', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __('وسط', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left' => [
                        'title' => __('چپ', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-left',
                    ],
                ],
                'default' => 'right',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group label' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_label_background_color',
            [
                'label' => __('رنگ پس‌زمینه برچسب فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_label_padding',
            [
                'label' => __('فاصله داخلی برچسب فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'field_label_margin',
            [
                'label' => __('فاصله خارجی برچسب فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'field_label_border_radius',
            [
                'label' => __('شعاع حاشیه برچسب فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'field_label_typography',
                'label' => __('تایپوگرافی برچسب فیلدها', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-form-group label',
            ]
        );

        $this->add_control(
            'field_input_color',
            [
                'label' => __('رنگ متن فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group input, {{WRAPPER}} .arta-form-group select, {{WRAPPER}} .arta-form-group textarea' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_input_background',
            [
                'label' => __('پس‌زمینه فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group input, {{WRAPPER}} .arta-form-group select, {{WRAPPER}} .arta-form-group textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_input_border_color',
            [
                'label' => __('رنگ حاشیه فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group input, {{WRAPPER}} .arta-form-group select, {{WRAPPER}} .arta-form-group textarea' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'field_input_border_radius',
            [
                'label' => __('شعاع حاشیه فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group input, {{WRAPPER}} .arta-form-group select, {{WRAPPER}} .arta-form-group textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'field_input_padding',
            [
                'label' => __('فاصله داخلی فیلدها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-form-group input, {{WRAPPER}} .arta-form-group select, {{WRAPPER}} .arta-form-group textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_input_focus_box_shadow',
                'label' => __('سایه فیلدها هنگام فوکوس', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-form-group input:focus, {{WRAPPER}} .arta-form-group select:focus, {{WRAPPER}} .arta-form-group textarea:focus',
            ]
        );

        $this->end_controls_section();

        // Style Section - Buttons
        $this->start_controls_section(
            'buttons_style_section',
            [
                'label' => __('استایل دکمه‌ها', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __('رنگ پس‌زمینه دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('رنگ متن دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => __('رنگ حاشیه دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __('شعاع حاشیه دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label' => __('فاصله داخلی دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('تایپوگرافی دکمه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-btn',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'label' => __('سایه دکمه هنگام هاور', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-btn:hover',
            ]
        );

        $this->end_controls_section();

        // Style Section - Doctor Selection
        $this->start_controls_section(
            'doctor_style_section',
            [
                'label' => __('استایل انتخاب پزشک', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'doctor_item_box_shadow',
                'label' => __('سایه آیتم پزشک', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-item:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'doctor_item_selected_box_shadow',
                'label' => __('سایه آیتم پزشک انتخاب شده', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-item.selected',
            ]
        );

        $this->add_control(
            'doctor_item_background_color',
            [
                'label' => __('رنگ پس‌زمینه آیتم پزشک', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'doctor_item_border_color',
            [
                'label' => __('رنگ حاشیه آیتم پزشک', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'doctor_item_selected_background_color',
            [
                'label' => __('رنگ پس‌زمینه آیتم پزشک انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item.selected' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'doctor_item_selected_border_color',
            [
                'label' => __('رنگ حاشیه آیتم پزشک انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item.selected' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'doctor_item_selected_text_color',
            [
                'label' => __('رنگ متن آیتم پزشک انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item.selected' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'doctor_item_border_radius',
            [
                'label' => __('شعاع حاشیه آیتم پزشک', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'doctor_item_padding',
            [
                'label' => __('فاصله داخلی آیتم پزشک', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'doctor_item_text_alignment',
            [
                'label' => __('تراز متن آیتم پزشک', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __('راست', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __('وسط', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left' => [
                        'title' => __('چپ', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-left',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'doctor_item_typography',
                'label' => __('تایپوگرافی آیتم پزشک', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-item',
            ]
        );

        $this->end_controls_section();

        // Style Section - Checkbox
        $this->start_controls_section(
            'checkbox_style_section',
            [
                'label' => __('استایل چک‌باکس', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'checkbox_hover_box_shadow',
                'label' => __('سایه چک‌باکس هنگام هاور', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-checkbox-label:hover',
            ]
        );

        $this->add_control(
            'checkbox_background_color',
            [
                'label' => __('رنگ پس‌زمینه چک‌باکس', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_border_color',
            [
                'label' => __('رنگ حاشیه چک‌باکس', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-label' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_text_color',
            [
                'label' => __('رنگ متن چک‌باکس', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_checked_background_color',
            [
                'label' => __('رنگ پس‌زمینه چک‌باکس انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-label:has(input[type="checkbox"]:checked)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_checked_border_color',
            [
                'label' => __('رنگ حاشیه چک‌باکس انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-label:has(input[type="checkbox"]:checked)' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_checked_text_color',
            [
                'label' => __('رنگ متن چک‌باکس انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-label:has(input[type="checkbox"]:checked) .arta-checkbox-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_border_radius',
            [
                'label' => __('شعاع حاشیه چک‌باکس', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_padding',
            [
                'label' => __('فاصله داخلی چک‌باکس', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-checkbox-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'checkbox_typography',
                'label' => __('تایپوگرافی چک‌باکس', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-checkbox-text',
            ]
        );

        $this->end_controls_section();

        // Style Section - Time Slots
        $this->start_controls_section(
            'time_slots_style_section',
            [
                'label' => __('استایل زمان‌های موجود', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'time_slot_background_color',
            [
                'label' => __('رنگ پس‌زمینه زمان‌های موجود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_border_color',
            [
                'label' => __('رنگ حاشیه زمان‌های موجود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_text_color',
            [
                'label' => __('رنگ متن زمان‌های موجود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_selected_background_color',
            [
                'label' => __('رنگ پس‌زمینه زمان انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot.selected' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_selected_border_color',
            [
                'label' => __('رنگ حاشیه زمان انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot.selected' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_selected_text_color',
            [
                'label' => __('رنگ متن زمان انتخاب شده', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot.selected' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_border_radius',
            [
                'label' => __('شعاع حاشیه زمان‌های موجود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_padding',
            [
                'label' => __('فاصله داخلی زمان‌های موجود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'time_slot_text_alignment',
            [
                'label' => __('تراز متن زمان‌های موجود', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __('راست', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __('وسط', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left' => [
                        'title' => __('چپ', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-left',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-time-slot' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'time_slot_typography',
                'label' => __('تایپوگرافی زمان‌های موجود', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-time-slot',
            ]
        );

        $this->end_controls_section();

        // Style Section - Progress Steps
        $this->start_controls_section(
            'progress_steps_style_section',
            [
                'label' => __('استایل مراحل پیشرفت', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'step_text_1',
            [
                'label' => __('متن مرحله اول', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('اطلاعات شخصی', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'step_text_2',
            [
                'label' => __('متن مرحله دوم', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('انتخاب پزشک', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'step_text_3',
            [
                'label' => __('متن مرحله سوم', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('انتخاب زمان', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'step_background_color',
            [
                'label' => __('رنگ پس‌زمینه مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-step' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_text_color',
            [
                'label' => __('رنگ متن مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-step' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_active_background_color',
            [
                'label' => __('رنگ پس‌زمینه مرحله فعال', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-step.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_active_text_color',
            [
                'label' => __('رنگ متن مرحله فعال', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-step.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_border_radius',
            [
                'label' => __('شعاع حاشیه مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-step' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_padding',
            [
                'label' => __('فاصله داخلی مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'step_typography',
                'label' => __('تایپوگرافی مرحله', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-step',
            ]
        );

        $this->end_controls_section();

        // Style Section - Error Messages
        $this->start_controls_section(
            'error_style_section',
            [
                'label' => __('استایل پیام‌های خطا', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'error_background_color',
            [
                'label' => __('رنگ پس‌زمینه پیام خطا', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-error' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'error_text_color',
            [
                'label' => __('رنگ متن پیام خطا', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-error' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'error_border_color',
            [
                'label' => __('رنگ حاشیه پیام خطا', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-error' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'error_border_radius',
            [
                'label' => __('شعاع حاشیه پیام خطا', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-error' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'error_padding',
            [
                'label' => __('فاصله داخلی پیام خطا', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'error_margin',
            [
                'label' => __('فاصله خارجی پیام خطا', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-error' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'error_text_alignment',
            [
                'label' => __('تراز متن پیام خطا', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __('راست', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __('وسط', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left' => [
                        'title' => __('چپ', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-left',
                    ],
                ],
                'default' => 'right',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-error' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'error_typography',
                'label' => __('تایپوگرافی پیام خطا', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-error',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'error_box_shadow',
                'label' => __('سایه پیام خطا', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-error',
            ]
        );

        // Step Error Specific Controls
        $this->add_control(
            'step_error_heading',
            [
                'label' => __('خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'step_error_background_color',
            [
                'label' => __('رنگ پس‌زمینه خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-step-error' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_error_text_color',
            [
                'label' => __('رنگ متن خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-step-error' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_error_border_color',
            [
                'label' => __('رنگ حاشیه خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-step-error' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_error_border_radius',
            [
                'label' => __('شعاع حاشیه خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-step-error' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_error_padding',
            [
                'label' => __('فاصله داخلی خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-step-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_error_margin',
            [
                'label' => __('فاصله خارجی خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-step-error' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_error_text_alignment',
            [
                'label' => __('تراز متن خطای مرحله', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __('راست', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __('وسط', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left' => [
                        'title' => __('چپ', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-left',
                    ],
                ],
                'default' => 'right',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-step-error' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'step_error_typography',
                'label' => __('تایپوگرافی خطای مرحله', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-step-error',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'step_error_box_shadow',
                'label' => __('سایه خطای مرحله', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-step-error',
            ]
        );

        $this->end_controls_section();

        // Style Section - Progress Bar
        $this->start_controls_section(
            'progress_style_section',
            [
                'label' => __('استایل نوار پیشرفت', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'progress_background_color',
            [
                'label' => __('رنگ پس‌زمینه نوار پیشرفت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'progress_fill_color',
            [
                'label' => __('رنگ نوار پیشرفت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-progress-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'progress_height',
            [
                'label' => __('ارتفاع نوار پیشرفت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-progress-bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - No Data Message
        $this->start_controls_section(
            'no_data_style_section',
            [
                'label' => __('استایل پیام عدم وجود داده', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'no_data_text_color',
            [
                'label' => __('رنگ متن', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-no-data' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'no_data_background_color',
            [
                'label' => __('رنگ پس‌زمینه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-no-data' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'no_data_border_color',
            [
                'label' => __('رنگ حاشیه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-no-data' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'no_data_border',
                'label' => __('حاشیه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-no-data',
            ]
        );

        $this->add_control(
            'no_data_border_radius',
            [
                'label' => __('شعاع حاشیه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-no-data' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'no_data_padding',
            [
                'label' => __('فاصله داخلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-no-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'no_data_margin',
            [
                'label' => __('فاصله خارجی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-no-data' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'no_data_text_alignment',
            [
                'label' => __('تراز متن', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right' => [
                        'title' => __('راست', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'center' => [
                        'title' => __('وسط', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'left' => [
                        'title' => __('چپ', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-left',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-no-data' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'no_data_typography',
                'label' => __('تایپوگرافی', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-no-data',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'no_data_box_shadow',
                'label' => __('سایه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-no-data',
            ]
        );

        $this->end_controls_section();

        // Style Section - Success Message
        $this->start_controls_section(
            'success_style_section',
            [
                'label' => __('استایل پیام موفقیت', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'success_background_color',
            [
                'label' => __('رنگ پس‌زمینه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d4edda',
                'selectors' => [
                    '{{WRAPPER}} .arta-success-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        

       


        $this->add_control(
            'success_title_heading',
            [
                'label' => __('عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'success_title_color',
            [
                'label' => __('رنگ عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#155724',
                'selectors' => [
                    '{{WRAPPER}} .arta-success-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'success_title_typography',
                'label' => __('تایپوگرافی عنوان', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-success-title',
            ]
        );

        $this->add_control(
            'success_message_heading',
            [
                'label' => __('متن پیام', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'success_message_color',
            [
                'label' => __('رنگ متن', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#155724',
                'selectors' => [
                    '{{WRAPPER}} .arta-success-message' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'success_message_typography',
                'label' => __('تایپوگرافی متن', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-success-message',
            ]
        );

        $this->add_control(
            'success_button_heading',
            [
                'label' => __('دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'success_button_bg_color',
            [
                'label' => __('رنگ پس‌زمینه دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#28a745',
                'selectors' => [
                    '{{WRAPPER}} .arta-success-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'success_button_text_color',
            [
                'label' => __('رنگ متن دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .arta-success-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'success_button_typography',
                'label' => __('تایپوگرافی دکمه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-success-button',
            ]
        );

        $this->add_control(
            'success_container_heading',
            [
                'label' => __('کانتینر', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'success_border',
                'label' => __('حاشیه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-success-container',
            ]
        );

        $this->add_control(
            'success_border_radius',
            [
                'label' => __('شعاع حاشیه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-success-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'success_padding',
            [
                'label' => __('فاصله داخلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-success-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'success_box_shadow',
                'label' => __('سایه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-success-container',
            ]
        );

        $this->end_controls_section();

        // Style Section - Login Required Message
        $this->start_controls_section(
            'login_required_style_section',
            [
                'label' => __('استایل پیام نیاز به ورود', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'login_required_background_color',
            [
                'label' => __('رنگ پس‌زمینه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-required-message' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_required_border_color',
            [
                'label' => __('رنگ حاشیه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e9ecef',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-required-message' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_required_border_radius',
            [
                'label' => __('شعاع حاشیه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-login-required-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'login_required_padding',
            [
                'label' => __('فاصله داخلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '40',
                    'right' => '20',
                    'bottom' => '40',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-login-required-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'login_icon_heading',
            [
                'label' => __('آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'login_icon_size',
            [
                'label' => __('اندازه آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 48,
                ],
            ]
        );

        $this->add_control(
            'login_icon_box_size',
            [
                'label' => __('اندازه باکس آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
            ]
        );

        $this->add_control(
            'login_icon_color',
            [
                'label' => __('رنگ آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1976d2',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-icon svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_icon_bg_color',
            [
                'label' => __('رنگ پس‌زمینه آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e3f2fd',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_title_heading',
            [
                'label' => __('عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'login_title_color',
            [
                'label' => __('رنگ عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'login_title_typography',
                'label' => __('تایپوگرافی عنوان', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-login-title',
            ]
        );

        $this->add_control(
            'login_message_heading',
            [
                'label' => __('پیام', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'login_message_color',
            [
                'label' => __('رنگ پیام', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-message' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'login_message_typography',
                'label' => __('تایپوگرافی پیام', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-login-message',
            ]
        );

        $this->add_control(
            'login_buttons_heading',
            [
                'label' => __('دکمه‌ها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'login_primary_button_bg',
            [
                'label' => __('رنگ پس‌زمینه دکمه اصلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1976d2',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-actions .arta-btn-primary' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'login_primary_button_text',
            [
                'label' => __('رنگ متن دکمه اصلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .arta-login-actions .arta-btn-primary' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'login_buttons_typography',
                'label' => __('تایپوگرافی دکمه‌ها', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-login-actions .arta-btn',
            ]
        );

        $this->add_control(
            'login_buttons_padding',
            [
                'label' => __('فاصله داخلی دکمه‌ها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-login-actions .arta-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'login_buttons_border_radius',
            [
                'label' => __('شعاع حاشیه دکمه‌ها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-login-actions .arta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Existing Consultation Box Content Section
        $this->start_controls_section(
            'existing_consultation_content_section',
            [
                'label' => __('باکس نوبت قبلی - محتوا', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'existing_consultation_title',
            [
                'label' => __('عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('شما قبلاً نوبت رزرو کرده‌اید', 'arta-consult-rx'),
                'placeholder' => __('عنوان را وارد کنید', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_date_text',
            [
                'label' => __('متن "در تاریخ"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('شما در تاریخ', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_time_text',
            [
                'label' => __('متن "ساعت"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('ساعت', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_doctor_text',
            [
                'label' => __('متن "با دکتر"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('با دکتر', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_appointment_text',
            [
                'label' => __('متن "نوبت مشاوره رزرو کرده‌اید"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('نوبت مشاوره رزرو کرده‌اید.', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_note_text',
            [
                'label' => __('متن توضیحات', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('شما نمی‌توانید نوبت جدید رزرو کنید، اما می‌توانید {item_type} را به درخواست قبلی خود اضافه کنید.', 'arta-consult-rx'),
                'description' => __('از {item_type} برای نمایش "این برنامه" یا "این محصول" استفاده کنید', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_program_text',
            [
                'label' => __('متن "این برنامه"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('این برنامه', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_product_text',
            [
                'label' => __('متن "این محصول"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('این محصول', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_add_button_text',
            [
                'label' => __('متن دکمه افزودن', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('افزودن به درخواست قبلی', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_adding_text',
            [
                'label' => __('متن "در حال افزودن..."', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('در حال افزودن...', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_already_added_program',
            [
                'label' => __('متن "برنامه اضافه شده"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('این برنامه قبلاً به درخواست شما اضافه شده است.', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'existing_consultation_already_added_product',
            [
                'label' => __('متن "محصول اضافه شده"', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('این محصول قبلاً به درخواست شما اضافه شده است.', 'arta-consult-rx'),
            ]
        );

        $this->end_controls_section();

        // Existing Consultation Box Style Section
        $this->start_controls_section(
            'existing_consultation_style_section',
            [
                'label' => __('باکس نوبت قبلی - استایل', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'existing_consultation_box_background_type',
            [
                'label' => __('نوع پس‌زمینه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'gradient',
                'options' => [
                    'solid' => __('یکرنگ', 'arta-consult-rx'),
                    'gradient' => __('گرادیانت', 'arta-consult-rx'),
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_box_background_color',
            [
                'label' => __('رنگ پس‌زمینه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'condition' => [
                    'existing_consultation_box_background_type' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_box_gradient_color_1',
            [
                'label' => __('رنگ گرادیانت 1', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'condition' => [
                    'existing_consultation_box_background_type' => 'gradient',
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_box_gradient_color_2',
            [
                'label' => __('رنگ گرادیانت 2', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#764ba2',
                'condition' => [
                    'existing_consultation_box_background_type' => 'gradient',
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_box_gradient_angle',
            [
                'label' => __('زاویه گرادیانت', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 135,
                ],
                'condition' => [
                    'existing_consultation_box_background_type' => 'gradient',
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_box_text_color',
            [
                'label' => __('رنگ متن', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'existing_consultation_box_border_radius',
            [
                'label' => __('گردی گوشه‌ها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '16',
                    'right' => '16',
                    'bottom' => '16',
                    'left' => '16',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'existing_consultation_box_padding',
            [
                'label' => __('فاصله داخلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '40',
                    'right' => '30',
                    'bottom' => '40',
                    'left' => '30',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'existing_consultation_box_shadow',
                'label' => __('سایه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-existing-consultation-box',
            ]
        );

        $this->add_control(
            'existing_consultation_title_style_heading',
            [
                'label' => __('عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'existing_consultation_title_typography',
                'label' => __('تایپوگرافی عنوان', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-consultation-title',
            ]
        );

        $this->add_control(
            'existing_consultation_title_color',
            [
                'label' => __('رنگ عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_responsive_control(
            'existing_consultation_title_spacing',
            [
                'label' => __('فاصله پایین عنوان', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_icon_style_heading',
            [
                'label' => __('آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'existing_consultation_icon_size',
            [
                'label' => __('اندازه آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 48,
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_icon_box_size',
            [
                'label' => __('اندازه باکس آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_icon_color',
            [
                'label' => __('رنگ آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'existing_consultation_icon_bg_color',
            [
                'label' => __('رنگ پس‌زمینه آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.2)',
            ]
        );

        $this->add_responsive_control(
            'existing_consultation_icon_spacing',
            [
                'label' => __('فاصله پایین آیکون', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'existing_consultation_info_style_heading',
            [
                'label' => __('باکس اطلاعات', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'existing_consultation_info_bg_color',
            [
                'label' => __('رنگ پس‌زمینه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.15)',
            ]
        );

        $this->add_control(
            'existing_consultation_info_border_radius',
            [
                'label' => __('گردی گوشه‌ها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'existing_consultation_info_padding',
            [
                'label' => __('فاصله داخلی', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'existing_consultation_info_spacing',
            [
                'label' => __('فاصله پایین', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'existing_consultation_message_typography',
                'label' => __('تایپوگرافی پیام', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-consultation-message',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'existing_consultation_note_typography',
                'label' => __('تایپوگرافی توضیحات', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-consultation-note',
            ]
        );

        $this->add_control(
            'existing_consultation_button_style_heading',
            [
                'label' => __('دکمه افزودن', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'existing_consultation_button_bg_color',
            [
                'label' => __('رنگ پس‌زمینه دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'existing_consultation_button_text_color',
            [
                'label' => __('رنگ متن دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
            ]
        );

        $this->add_control(
            'existing_consultation_button_hover_bg_color',
            [
                'label' => __('رنگ پس‌زمینه دکمه (hover)', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9ff',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'existing_consultation_button_typography',
                'label' => __('تایپوگرافی دکمه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-add-to-consultation-btn',
            ]
        );

        $this->add_control(
            'existing_consultation_button_border_radius',
            [
                'label' => __('گردی گوشه‌ها', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'existing_consultation_button_padding',
            [
                'label' => __('فاصله داخلی دکمه', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '14',
                    'right' => '30',
                    'bottom' => '14',
                    'left' => '30',
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'existing_consultation_button_shadow',
                'label' => __('سایه دکمه', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-add-to-consultation-btn',
            ]
        );

        $this->end_controls_section();
    }
    protected function get_consultation_by_user_id() {
        $user_id = get_current_user_id();
        if(empty($user_id)){
            return false;
        }
        
        // Get current date and time
        $current_datetime = current_time('Y-m-d H:i:s');
        
        // Query arguments
        $args = array(
            'post_type' => 'arta_consultation',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'author' => $user_id,
            'meta_query' => array(
                array(
                    'key' => '_arta_approval_status',
                    'value' => 'rejected',
                    'compare' => '!='
                )
            )
        );
        
        $consultations = get_posts($args);
      
        // Filter by future date and time
        $future_consultations = array();
        foreach ($consultations as $consultation) {
            $appointment_date = get_post_meta($consultation->ID, '_arta_appointment_date', true);
            $appointment_time = get_post_meta($consultation->ID, '_arta_appointment_time', true);
            
            if (!empty($appointment_date) && !empty($appointment_time)) {
                // Combine date and time
                $appointment_datetime = $appointment_date . ' ' . $appointment_time;
                
                // Compare with current datetime
                if (strtotime($appointment_datetime) > strtotime($current_datetime)) {
                    $future_consultations[] = $consultation->ID;
                }
            }
        }
        
        // Return first consultation ID or false
        return !empty($future_consultations) ? $future_consultations[0] : false;
    }

    /**
     * Get consultation details
     */
    protected function get_consultation_details($consultation_id) {
        if (empty($consultation_id)) {
            return false;
        }

        $consultation = get_post($consultation_id);
        if (!$consultation) {
            return false;
        }

        $appointment_date = get_post_meta($consultation_id, '_arta_appointment_date', true);
        $appointment_time = get_post_meta($consultation_id, '_arta_appointment_time', true);
        $doctor_id = get_post_meta($consultation_id, '_arta_doctor_id', true);
        
        // Get doctor name
        $doctor_name = '';
        if (!empty($doctor_id)) {
            $doctor = get_userdata($doctor_id);
            if ($doctor) {
                $doctor_name = $doctor->display_name;
            }
        }

        // Get programs and products
        $programs = get_post_meta($consultation_id, '_arta_programs', true);
        $products = get_post_meta($consultation_id, '_arta_products', true);

        return array(
            'id' => $consultation_id,
            'date' => $appointment_date,
            'time' => $appointment_time,
            'doctor_id' => $doctor_id,
            'doctor_name' => $doctor_name,
            'programs' => !empty($programs) ? $programs : array(),
            'products' => !empty($products) ? $products : array(),
        );
    }

    /**
     * Render existing consultation box
     */
    protected function render_existing_consultation_box($consultation_id, $program_id, $product_id, $post) {
        $consultation_details = $this->get_consultation_details($consultation_id);
        
        if (!$consultation_details) {
            return;
        }

        $settings = $this->get_settings_for_display();

        // Format date
        $formatted_date = '';
        if (!empty($consultation_details['date'])) {
            $date_obj = \DateTime::createFromFormat('Y-m-d', $consultation_details['date']);
            if ($date_obj) {
                $formatted_date = date_i18n('j F Y', $date_obj->getTimestamp());
            }
        }

        // Check if current item is already in consultation
        $is_already_added = false;
        $current_item_type = '';
        $current_item_id = '';
        $item_type_text = '';
        $already_added_text = '';
        
        if (!empty($program_id)) {
            $current_item_type = 'program';
            $current_item_id = $program_id;
            $is_already_added = in_array($program_id, $consultation_details['programs']);
            $item_type_text = !empty($settings['existing_consultation_program_text']) ? $settings['existing_consultation_program_text'] : 'این برنامه';
            $already_added_text = !empty($settings['existing_consultation_already_added_program']) ? $settings['existing_consultation_already_added_program'] : 'این برنامه قبلاً به درخواست شما اضافه شده است.';
        } elseif (!empty($product_id)) {
            $current_item_type = 'product';
            $current_item_id = $product_id;
            $is_already_added = in_array($product_id, $consultation_details['products']);
            $item_type_text = !empty($settings['existing_consultation_product_text']) ? $settings['existing_consultation_product_text'] : 'این محصول';
            $already_added_text = !empty($settings['existing_consultation_already_added_product']) ? $settings['existing_consultation_already_added_product'] : 'این محصول قبلاً به درخواست شما اضافه شده است.';
        }

        // Get text settings
        $title = !empty($settings['existing_consultation_title']) ? $settings['existing_consultation_title'] : 'شما قبلاً نوبت رزرو کرده‌اید';
        $date_text = !empty($settings['existing_consultation_date_text']) ? $settings['existing_consultation_date_text'] : 'شما در تاریخ';
        $time_text = !empty($settings['existing_consultation_time_text']) ? $settings['existing_consultation_time_text'] : 'ساعت';
        $doctor_text = !empty($settings['existing_consultation_doctor_text']) ? $settings['existing_consultation_doctor_text'] : 'با دکتر';
        $appointment_text = !empty($settings['existing_consultation_appointment_text']) ? $settings['existing_consultation_appointment_text'] : 'نوبت مشاوره رزرو کرده‌اید.';
        $note_text = !empty($settings['existing_consultation_note_text']) ? $settings['existing_consultation_note_text'] : 'شما نمی‌توانید نوبت جدید رزرو کنید، اما می‌توانید {item_type} را به درخواست قبلی خود اضافه کنید.';
        $note_text = str_replace('{item_type}', $item_type_text, $note_text);
        $button_text = !empty($settings['existing_consultation_add_button_text']) ? $settings['existing_consultation_add_button_text'] : 'افزودن به درخواست قبلی';
        $adding_text = !empty($settings['existing_consultation_adding_text']) ? $settings['existing_consultation_adding_text'] : 'در حال افزودن...';

        // Get style settings
        $bg_type = !empty($settings['existing_consultation_box_background_type']) ? $settings['existing_consultation_box_background_type'] : 'gradient';
        $bg_color = !empty($settings['existing_consultation_box_background_color']) ? $settings['existing_consultation_box_background_color'] : '#667eea';
        $gradient_color_1 = !empty($settings['existing_consultation_box_gradient_color_1']) ? $settings['existing_consultation_box_gradient_color_1'] : '#667eea';
        $gradient_color_2 = !empty($settings['existing_consultation_box_gradient_color_2']) ? $settings['existing_consultation_box_gradient_color_2'] : '#764ba2';
        $gradient_angle = !empty($settings['existing_consultation_box_gradient_angle']['size']) ? $settings['existing_consultation_box_gradient_angle']['size'] : 135;
        $text_color = !empty($settings['existing_consultation_box_text_color']) ? $settings['existing_consultation_box_text_color'] : '#ffffff';
        $icon_size = !empty($settings['existing_consultation_icon_size']['size']) ? $settings['existing_consultation_icon_size']['size'] : 48;
        $icon_box_size = !empty($settings['existing_consultation_icon_box_size']['size']) ? $settings['existing_consultation_icon_box_size']['size'] : 80;
        $icon_color = !empty($settings['existing_consultation_icon_color']) ? $settings['existing_consultation_icon_color'] : '#ffffff';
        $icon_bg_color = !empty($settings['existing_consultation_icon_bg_color']) ? $settings['existing_consultation_icon_bg_color'] : 'rgba(255, 255, 255, 0.2)';
        $info_bg_color = !empty($settings['existing_consultation_info_bg_color']) ? $settings['existing_consultation_info_bg_color'] : 'rgba(255, 255, 255, 0.15)';
        $button_bg_color = !empty($settings['existing_consultation_button_bg_color']) ? $settings['existing_consultation_button_bg_color'] : '#ffffff';
        $button_text_color = !empty($settings['existing_consultation_button_text_color']) ? $settings['existing_consultation_button_text_color'] : '#667eea';
        $button_hover_bg_color = !empty($settings['existing_consultation_button_hover_bg_color']) ? $settings['existing_consultation_button_hover_bg_color'] : '#f8f9ff';
        $title_color = !empty($settings['existing_consultation_title_color']) ? $settings['existing_consultation_title_color'] : '#ffffff';

        ?>
        <div class="arta-existing-consultation-box" data-widget-id="<?php echo $this->get_id(); ?>">
            <div class="arta-consultation-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($icon_size); ?>" height="<?php echo esc_attr($icon_size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            
            <h3 class="arta-consultation-title"><?php echo esc_html($title); ?></h3>
            
            <div class="arta-consultation-info">
                <p class="arta-consultation-message">
                    <?php echo esc_html($date_text); ?> <strong><?php echo esc_html($formatted_date); ?></strong> 
                    <?php echo esc_html($time_text); ?> <strong><?php echo esc_html($consultation_details['time']); ?></strong> 
                    <?php echo esc_html($doctor_text); ?> <strong><?php echo esc_html($consultation_details['doctor_name']); ?></strong> 
                    <?php echo esc_html($appointment_text); ?>
                </p>
                
                <p class="arta-consultation-note">
                    <?php echo esc_html($note_text); ?>
                </p>
            </div>

            <?php if (!$is_already_added && !empty($current_item_id)): ?>
                <button 
                    type="button" 
                    class="arta-add-to-consultation-btn"
                    data-consultation-id="<?php echo esc_attr($consultation_id); ?>"
                    data-item-id="<?php echo esc_attr($current_item_id); ?>"
                    data-item-type="<?php echo esc_attr($current_item_type); ?>"
                    data-adding-text="<?php echo esc_attr($adding_text); ?>"
                    data-button-text="<?php echo esc_attr($button_text); ?>"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <?php echo esc_html($button_text); ?>
                </button>
            <?php else: ?>
                <div class="arta-already-added-message">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <?php echo esc_html($already_added_text); ?>
                </div>
            <?php endif; ?>
        </div>

        <style>
            [data-widget-id="<?php echo $this->get_id(); ?>"].arta-existing-consultation-box {
                <?php if ($bg_type === 'gradient'): ?>
                background: linear-gradient(<?php echo esc_attr($gradient_angle); ?>deg, <?php echo esc_attr($gradient_color_1); ?> 0%, <?php echo esc_attr($gradient_color_2); ?> 100%);
                <?php else: ?>
                background: <?php echo esc_attr($bg_color); ?>;
                <?php endif; ?>
                <?php if (!empty($settings['existing_consultation_box_border_radius'])): ?>
                border-radius: <?php echo esc_attr($settings['existing_consultation_box_border_radius']['top']); ?>px <?php echo esc_attr($settings['existing_consultation_box_border_radius']['right']); ?>px <?php echo esc_attr($settings['existing_consultation_box_border_radius']['bottom']); ?>px <?php echo esc_attr($settings['existing_consultation_box_border_radius']['left']); ?>px;
                <?php endif; ?>
                <?php if (!empty($settings['existing_consultation_box_padding'])): ?>
                padding: <?php echo esc_attr($settings['existing_consultation_box_padding']['top']); ?>px <?php echo esc_attr($settings['existing_consultation_box_padding']['right']); ?>px <?php echo esc_attr($settings['existing_consultation_box_padding']['bottom']); ?>px <?php echo esc_attr($settings['existing_consultation_box_padding']['left']); ?>px;
                <?php endif; ?>
                text-align: center;
                color: <?php echo esc_attr($text_color); ?>;
                max-width: 600px;
                margin: 0 auto;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-consultation-icon {
                width: <?php echo esc_attr($icon_box_size); ?>px;
                height: <?php echo esc_attr($icon_box_size); ?>px;
                background: <?php echo esc_attr($icon_bg_color); ?>;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto <?php echo !empty($settings['existing_consultation_icon_spacing']['size']) ? esc_attr($settings['existing_consultation_icon_spacing']['size']) : '20'; ?>px;
                backdrop-filter: blur(10px);
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-consultation-icon svg {
                color: <?php echo esc_attr($icon_color); ?>;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-consultation-title {
                color: <?php echo esc_attr($title_color); ?>;
                margin-bottom: <?php echo !empty($settings['existing_consultation_title_spacing']['size']) ? esc_attr($settings['existing_consultation_title_spacing']['size']) : '20'; ?>px;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-consultation-info {
                background: <?php echo esc_attr($info_bg_color); ?>;
                <?php if (!empty($settings['existing_consultation_info_border_radius'])): ?>
                border-radius: <?php echo esc_attr($settings['existing_consultation_info_border_radius']['top']); ?>px <?php echo esc_attr($settings['existing_consultation_info_border_radius']['right']); ?>px <?php echo esc_attr($settings['existing_consultation_info_border_radius']['bottom']); ?>px <?php echo esc_attr($settings['existing_consultation_info_border_radius']['left']); ?>px;
                <?php endif; ?>
                <?php if (!empty($settings['existing_consultation_info_padding'])): ?>
                padding: <?php echo esc_attr($settings['existing_consultation_info_padding']['top']); ?>px <?php echo esc_attr($settings['existing_consultation_info_padding']['right']); ?>px <?php echo esc_attr($settings['existing_consultation_info_padding']['bottom']); ?>px <?php echo esc_attr($settings['existing_consultation_info_padding']['left']); ?>px;
                <?php endif; ?>
                margin-bottom: <?php echo !empty($settings['existing_consultation_info_spacing']['size']) ? esc_attr($settings['existing_consultation_info_spacing']['size']) : '25'; ?>px;
                backdrop-filter: blur(10px);
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-consultation-message {
                margin-bottom: 15px;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-consultation-note {
                opacity: 0.9;
                margin: 0;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-add-to-consultation-btn {
                background: <?php echo esc_attr($button_bg_color); ?>;
                color: <?php echo esc_attr($button_text_color); ?>;
                border: none;
                <?php if (!empty($settings['existing_consultation_button_padding'])): ?>
                padding: <?php echo esc_attr($settings['existing_consultation_button_padding']['top']); ?>px <?php echo esc_attr($settings['existing_consultation_button_padding']['right']); ?>px <?php echo esc_attr($settings['existing_consultation_button_padding']['bottom']); ?>px <?php echo esc_attr($settings['existing_consultation_button_padding']['left']); ?>px;
                <?php endif; ?>
                <?php if (!empty($settings['existing_consultation_button_border_radius'])): ?>
                border-radius: <?php echo esc_attr($settings['existing_consultation_button_border_radius']['top']); ?>px <?php echo esc_attr($settings['existing_consultation_button_border_radius']['right']); ?>px <?php echo esc_attr($settings['existing_consultation_button_border_radius']['bottom']); ?>px <?php echo esc_attr($settings['existing_consultation_button_border_radius']['left']); ?>px;
                <?php endif; ?>
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-add-to-consultation-btn:hover {
                background: <?php echo esc_attr($button_hover_bg_color); ?>;
                transform: translateY(-2px);
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-add-to-consultation-btn svg {
                stroke: currentColor;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-add-to-consultation-btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-already-added-message {
                background: rgba(255, 255, 255, 0.2);
                padding: 14px 20px;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 15px;
                backdrop-filter: blur(10px);
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-already-added-message svg {
                color: <?php echo esc_attr($text_color); ?>;
            }

            /* RTL Support */
            [dir="rtl"] [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-add-to-consultation-btn,
            [dir="rtl"] [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-already-added-message {
                flex-direction: row-reverse;
            }

            /* Responsive */
            @media (max-width: 768px) {
                [data-widget-id="<?php echo $this->get_id(); ?>"].arta-existing-consultation-box {
                    padding: 30px 20px;
                }
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('[data-widget-id="<?php echo $this->get_id(); ?>"] .arta-add-to-consultation-btn').on('click', function() {
                var $btn = $(this);
                var consultationId = $btn.data('consultation-id');
                var itemId = $btn.data('item-id');
                var itemType = $btn.data('item-type');
                var addingText = $btn.data('adding-text');
                var buttonText = $btn.data('button-text');

                // Disable button
                $btn.prop('disabled', true).text(addingText);

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'arta_add_item_to_consultation',
                        consultation_id: consultationId,
                        item_id: itemId,
                        item_type: itemType,
                        nonce: '<?php echo wp_create_nonce('arta_add_item_to_consultation'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $btn.replaceWith(
                                '<div class="arta-already-added-message">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                                '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>' +
                                '<polyline points="22 4 12 14.01 9 11.01"></polyline>' +
                                '</svg>' +
                                response.data.message +
                                '</div>'
                            );
                        } else {
                            alert(response.data.message || 'خطا در افزودن به درخواست');
                            $btn.prop('disabled', false).html(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                                '<line x1="12" y1="5" x2="12" y2="19"></line>' +
                                '<line x1="5" y1="12" x2="19" y2="12"></line>' +
                                '</svg>' +
                                buttonText
                            );
                        }
                    },
                    error: function() {
                        alert('خطا در ارتباط با سرور');
                        $btn.prop('disabled', false).html(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                            '<line x1="12" y1="5" x2="12" y2="19"></line>' +
                            '<line x1="5" y1="12" x2="19" y2="12"></line>' +
                            '</svg>' +
                            buttonText
                        );
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Render login required message
     */
    protected function render_login_required_message($settings) {
        // Get style settings
        $icon_size = !empty($settings['login_icon_size']['size']) ? $settings['login_icon_size']['size'] : 48;
        $icon_box_size = !empty($settings['login_icon_box_size']['size']) ? $settings['login_icon_box_size']['size'] : 80;
        
        // Get content settings
        $title = !empty($settings['login_required_title']) ? $settings['login_required_title'] : __('برای رزرو نوبت باید وارد شوید', 'arta-consult-rx');
        $message = !empty($settings['login_required_message']) ? $settings['login_required_message'] : __('لطفاً ابتدا وارد حساب کاربری خود شوید تا بتوانید نوبت مشاوره رزرو کنید.', 'arta-consult-rx');
        $login_button_text = !empty($settings['login_button_text']) ? $settings['login_button_text'] : __('ورود', 'arta-consult-rx');
        
        // Get login button link
        $login_link = wp_login_url(get_permalink());
        $login_target = '';
        
        if (!empty($settings['login_button_link']['url'])) {
            $login_link = $settings['login_button_link']['url'];
            // Support for dynamic links
            if (strpos($login_link, '{{') !== false) {
                $login_link = \Elementor\Plugin::$instance->frontend->apply_builder_in_content($login_link);
            }
        }
        
        // Get target and rel settings
        $login_attributes = array();
        
        if (!empty($settings['login_button_target']) && $settings['login_button_target'] === 'yes') {
            $login_attributes[] = 'target="_blank"';
            $login_attributes[] = 'rel="noopener"';
        }
        
        if (!empty($settings['login_button_nofollow']) && $settings['login_button_nofollow'] === 'yes') {
            if (in_array('rel="noopener"', $login_attributes)) {
                $login_attributes[array_search('rel="noopener"', $login_attributes)] = 'rel="noopener nofollow"';
            } else {
                $login_attributes[] = 'rel="nofollow"';
            }
        }
        
        $login_target = !empty($login_attributes) ? ' ' . implode(' ', $login_attributes) : '';
        ?>
        <div class="arta-login-required-message" data-widget-id="<?php echo $this->get_id(); ?>">
            <div class="arta-login-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($icon_size); ?>" height="<?php echo esc_attr($icon_size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <circle cx="12" cy="16" r="1"></circle>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h3 class="arta-login-title"><?php echo esc_html($title); ?></h3>
            <p class="arta-login-message"><?php echo esc_html($message); ?></p>
            <div class="arta-login-actions">
                <a href="<?php echo esc_url($login_link); ?>" class="arta-btn arta-btn-primary"<?php echo $login_target; ?>>
                    <?php echo esc_html($login_button_text); ?>
                </a>
            </div>
        </div>

        <style>
            [data-widget-id="<?php echo $this->get_id(); ?>"].arta-login-required-message {
                text-align: center;
                max-width: 500px;
                margin: 0 auto;
                border: 1px solid;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-icon {
                width: <?php echo esc_attr($icon_box_size); ?>px;
                height: <?php echo esc_attr($icon_box_size); ?>px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-icon svg {
                width: <?php echo esc_attr($icon_size); ?>px;
                height: <?php echo esc_attr($icon_size); ?>px;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-title {
                margin-bottom: 15px;
                font-size: 20px;
                font-weight: 600;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-message {
                margin-bottom: 25px;
                line-height: 1.6;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-actions {
                display: flex;
                gap: 15px;
                justify-content: center;
                flex-wrap: wrap;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-actions .arta-btn {
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                display: inline-block;
            }

            [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-actions .arta-btn:hover {
                transform: translateY(-2px);
            }

            /* RTL Support */
            [dir="rtl"] [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-actions {
                flex-direction: row-reverse;
            }

            /* Responsive */
            @media (max-width: 480px) {
                [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-actions {
                    flex-direction: column;
                    align-items: center;
                }

                [data-widget-id="<?php echo $this->get_id(); ?>"] .arta-login-actions .arta-btn {
                    width: 100%;
                    max-width: 200px;
                }
            }
        </style>
        <?php
    }

    /**
     * Render widget
     */
    protected function render() {
        global $post;
        $program_id = '';
        $product_id = '';
        if ( $post->post_type == 'arta_program') {
            $program_id = $post->ID;
        }
        if($post->post_type == 'product'){
            $product_id = $post->ID;
        }
        $settings = $this->get_settings_for_display();
        $consultation_id = $this->get_consultation_by_user_id();
        $require_login = $settings['require_login'] === 'yes';
        $is_user_logged_in = is_user_logged_in();
       
        // ساخت یک ID یونیک برای هر ویجت
        $unique_id = 'arta-appointment-form-container-' . $this->get_id();

        $this->add_render_attribute('wrapper', 'class', 'arta-appointment-form-wrapper');
        $this->add_render_attribute('wrapper', 'data-program-id', $program_id);
        $this->add_render_attribute('wrapper', 'data-product-id', $product_id);
        
        // Check if login is required and user is not logged in
        if ($require_login && !$is_user_logged_in) {
            // Show login required message
            $this->render_login_required_message($settings);
        } elseif ($consultation_id !== false) {
            // User has existing consultation, show info box
            $this->render_existing_consultation_box($consultation_id, $program_id, $product_id, $post);
        } else {
            // User doesn't have consultation, show form
            ?>
            <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
                <?php if (!empty($settings['form_description'])): ?>
                    <p class="arta-form-description"><?php echo esc_html($settings['form_description']); ?></p>
                <?php endif; ?>
                <input type="hidden" id="arta-consultation-id" value="<?php echo esc_attr($consultation_id); ?>">
                <input type="hidden" id="arta-program-id" value="<?php echo esc_attr($program_id); ?>">
                <input type="hidden" id="arta-product-id" value="<?php echo esc_attr($product_id); ?>">
                <!-- Form will be rendered directly -->
                <div id="<?php echo esc_attr($unique_id); ?>">
                    <!-- Form content will be loaded here -->
                </div>
            </div>

            <?php
            // Add JavaScript for form functionality
            $this->render_form_script($settings, $unique_id);
        }
    }

    /**
     * Render form script
     */
    protected function render_form_script($settings, $unique_id = null) {
        $required_fields = $settings['required_fields'] ?: [];
        $show_personal_info = $settings['show_personal_info'] === 'yes';
        $show_medical_info = $settings['show_medical_info'] === 'yes';
        $show_doctor_selection = $settings['show_doctor_selection'] === 'yes';
        $show_time_selection = $settings['show_time_selection'] === 'yes';
        
        $program_id = $settings['program_id'];
        if (empty($program_id)) {
            $programs = get_posts(array(
                'post_type' => 'arta_program',
                'posts_per_page' => 1,
                'post_status' => 'publish'
            ));
            if (!empty($programs)) {
                $program_id = $programs[0]->ID;
            }
        }
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Form settings
            var formSettings = {
                containerId: '<?php echo esc_js($unique_id ?: 'arta-appointment-form-container'); ?>',
                programId: <?php echo json_encode($program_id); ?>,
                requiredFields: <?php echo json_encode($required_fields); ?>,
                showPersonalInfo: <?php echo json_encode($show_personal_info); ?>,
                showMedicalInfo: <?php echo json_encode($show_medical_info); ?>,
                showDoctorSelection: <?php echo json_encode($show_doctor_selection); ?>,
                showTimeSelection: <?php echo json_encode($show_time_selection); ?>,
                isRTL: <?php echo json_encode(function_exists('arta_is_rtl') ? arta_is_rtl() : false); ?>,
                direction: '<?php echo esc_js(function_exists('arta_get_direction_class') ? arta_get_direction_class() : 'arta-rtl'); ?>',
                directionAttr: '<?php echo esc_js(function_exists('arta_get_direction_attr') ? arta_get_direction_attr() : 'rtl'); ?>',
                buttonTexts: {
                    next: '<?php echo esc_js(!empty($settings['next_button_text']) ? $settings['next_button_text'] : __('بعدی', 'arta-consult-rx')); ?>',
                    prev: '<?php echo esc_js(!empty($settings['prev_button_text']) ? $settings['prev_button_text'] : __('قبلی', 'arta-consult-rx')); ?>',
                    submit: '<?php echo esc_js(!empty($settings['submit_button_text']) ? $settings['submit_button_text'] : __('ثبت درخواست', 'arta-consult-rx')); ?>'
                },
                noDataMessages: {
                    selectDoctorDate: '<?php echo esc_js(!empty($settings['no_data_select_message']) ? $settings['no_data_select_message'] : __('لطفاً ابتدا پزشک و تاریخ را انتخاب کنید', 'arta-consult-rx')); ?>',
                    noSlots: '<?php echo esc_js(!empty($settings['no_data_slots_message']) ? $settings['no_data_slots_message'] : __('هیچ زمان خالی برای این تاریخ وجود ندارد', 'arta-consult-rx')); ?>'
                },
                successMessages: {
                    title: '<?php echo esc_js($settings['success_title']); ?>',
                    message: '<?php echo esc_js($settings['success_message']); ?>',
                },
                medicalConsultationText: '<?php echo esc_js(wp_strip_all_tags($settings['medical_consultation_text'])); ?>',
                strings: typeof arta_ajax !== 'undefined' && arta_ajax.strings ? arta_ajax.strings : {
                    step1: '<?php echo esc_js(__('اطلاعات شخصی', 'arta-consult-rx')); ?>',
                    step2: '<?php echo esc_js(__('انتخاب پزشک', 'arta-consult-rx')); ?>',
                    step3: '<?php echo esc_js(__('انتخاب زمان', 'arta-consult-rx')); ?>',
                    personal_info: '<?php echo esc_js(__('اطلاعات شخصی', 'arta-consult-rx')); ?>',
                    full_name: '<?php echo esc_js(__('نام و نام خانوادگی', 'arta-consult-rx')); ?>',
                    gender: '<?php echo esc_js(__('جنسیت', 'arta-consult-rx')); ?>',
                    select_option: '<?php echo esc_js(__('انتخاب کنید', 'arta-consult-rx')); ?>',
                    male: '<?php echo esc_js(__('مرد', 'arta-consult-rx')); ?>',
                    female: '<?php echo esc_js(__('زن', 'arta-consult-rx')); ?>',
                    birth_date: '<?php echo esc_js(__('تاریخ تولد', 'arta-consult-rx')); ?>',
                    height: '<?php echo esc_js(__('قد (سانتی‌متر)', 'arta-consult-rx')); ?>',
                    weight: '<?php echo esc_js(__('وزن (کیلوگرم)', 'arta-consult-rx')); ?>',
                    email: '<?php echo esc_js(__('ایمیل', 'arta-consult-rx')); ?>',
                    phone: '<?php echo esc_js(__('شماره تماس', 'arta-consult-rx')); ?>',
                    chronic_diseases: '<?php echo esc_js(__('بیماری‌های مزمن', 'arta-consult-rx')); ?>',
                    medications: '<?php echo esc_js(__('داروهای مصرفی', 'arta-consult-rx')); ?>',
                    medical_history: '<?php echo esc_js(__('سوابق درمانی', 'arta-consult-rx')); ?>',
                    program_goal: '<?php echo esc_js(__('هدف از برنامه', 'arta-consult-rx')); ?>',
                    select_doctor: '<?php echo esc_js(__('انتخاب پزشک', 'arta-consult-rx')); ?>',
                    loading_doctors: '<?php echo esc_js(__('لیست پزشکان در حال بارگذاری...', 'arta-consult-rx')); ?>',
                    select_time: '<?php echo esc_js(__('انتخاب زمان نوبت', 'arta-consult-rx')); ?>',
                    appointment_date: '<?php echo esc_js(__('تاریخ نوبت', 'arta-consult-rx')); ?>',
                    loading_slots: '<?php echo esc_js(__('در حال بارگذاری...', 'arta-consult-rx')); ?>',
                    searching: '<?php echo esc_js(__('در حال جستجو...', 'arta-consult-rx')); ?>',
                    field_required: '<?php echo esc_js(__('این فیلد اجباری است', 'arta-consult-rx')); ?>',
                    select_doctor_first: '<?php echo esc_js(__('لطفاً یک پزشک انتخاب کنید', 'arta-consult-rx')); ?>',
                    select_time_first: '<?php echo esc_js(__('لطفاً یک زمان انتخاب کنید', 'arta-consult-rx')); ?>',
                    sending: '<?php echo esc_js(__('در حال ارسال...', 'arta-consult-rx')); ?>',
                    invalid_email: '<?php echo esc_js(__('ایمیل معتبر نیست', 'arta-consult-rx')); ?>',
                    invalid_phone: '<?php echo esc_js(__('شماره تماس معتبر نیست', 'arta-consult-rx')); ?>',
                    medical_consultation_required: '<?php echo esc_js(__('تایید مشاوره پزشکی الزامی است', 'arta-consult-rx')); ?>',
                    select_doctor_date_first: '<?php echo esc_js(__('لطفاً ابتدا پزشک و تاریخ را انتخاب کنید', 'arta-consult-rx')); ?>',
                    no_slots_available: '<?php echo esc_js(__('هیچ زمان خالی برای این تاریخ وجود ندارد', 'arta-consult-rx')); ?>'
                }
            };
            
            // Initialize form directly
            if (typeof window.artaAppointmentForm !== 'undefined') {
                window.artaAppointmentForm.initDirect(formSettings);
            } else {
                console.error('Appointment form script not loaded');
            }
        });
        </script>
        <?php
    }

    /**
     * Get program ID based on settings and current page
     */
    private function get_program_id($settings) {
        // If auto detect is enabled, try to get from current page
        if ($settings['auto_detect_program'] === 'yes') {
            global $post;
            
            // Check if we're on a single program page
            if (is_singular('arta_program') && $post) {
                return $post->ID;
            }
            
            // Check if we're on a single product page and it's related to a program
            if (is_singular('product') && $post) {
                $related_programs = get_post_meta($post->ID, '_arta_program_related_products', true);
                if (is_array($related_programs) && !empty($related_programs)) {
                    // Get the first program that has this product
                    foreach ($related_programs as $program_id) {
                        $program_products = get_post_meta($program_id, '_arta_program_related_products', true);
                        if (is_array($program_products) && in_array($post->ID, $program_products)) {
                            return $program_id;
                        }
                    }
                }
            }
            
            // Check if we're on a single consultation page
            if (is_singular('arta_consultation') && $post) {
                $program_id = get_post_meta($post->ID, '_arta_program_id', true);
                if ($program_id) {
                    return $program_id;
                }
            }
        }
        
        // Fallback to manual selection
        $program_id = $settings['program_id'];
        if (empty($program_id)) {
            // Get first program if none selected
            $programs = get_posts(array(
                'post_type' => 'arta_program',
                'posts_per_page' => 1,
                'post_status' => 'publish'
            ));
            if (!empty($programs)) {
                $program_id = $programs[0]->ID;
            }
        }
        
        return $program_id;
    }

    /**
     * Get programs options
     */
    private function get_programs_options() {
        $programs = get_posts(array(
            'post_type' => 'arta_program',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));

        $options = array();
        foreach ($programs as $program) {
            $options[$program->ID] = $program->post_title;
        }

        return $options;
    }

    /**
     * Get program doctors data
     */
    private function get_program_doctors_data($program_id) {
        if (empty($program_id)) {
            return array();
        }

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
                
                $doctors_data[] = array(
                    'id' => $doctor->ID,
                    'name' => $doctor->display_name,
                    'avatar' => $avatar
                );
            }
        }
        
        return $doctors_data;
    }

    /**
     * Content template for live preview
     */
    protected function content_template() {
       
        $first_program = get_posts(array(
            'post_type' => 'arta_program',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        $preview_title = __('فرم رزرو نوبت مشاوره', 'arta-consult-rx');
        $preview_description = __('فرم رزرو نوبت مشاوره', 'arta-consult-rx');
        $doctors_data = array();
        
        if (!empty($first_program)) {
            $program_title = get_the_title($first_program[0]->ID);
            if (!empty($program_title)) {
                $preview_title = $program_title;
                $preview_description = sprintf(__('فرم رزرو نوبت مشاوره برای برنامه %s', 'arta-consult-rx'), $program_title);
            }
            // Get doctors for preview
            $doctors_data = $this->get_program_doctors_data($first_program[0]->ID);
        }
        ?>
        <div class="arta-appointment-form-wrapper">
            <p class="arta-form-description"><?php echo esc_html($preview_description); ?></p>
            <div class="arta-appointment-form-direct">
                <div class="arta-form-progress">
                    <div class="arta-progress-bar">
                        <div class="arta-progress-fill" style="width: 33.33%"></div>
                    </div>
                    <div class="arta-progress-steps">
                        <span class="arta-step active" data-step="1">اطلاعات شخصی</span>
                        <span class="arta-step" data-step="2">انتخاب پزشک</span>
                        <span class="arta-step" data-step="3">انتخاب زمان</span>
                    </div>
                </div>
                <div class="arta-form-step active">
                    <h3>اطلاعات شخصی</h3>
                    <div class="arta-form-row">
                        <div class="arta-form-group">
                            <label>نام و نام خانوادگی *</label>
                            <input type="text" placeholder="نام و نام خانوادگی خود را وارد کنید">
                        </div>
                        <div class="arta-form-group">
                            <label>جنسیت *</label>
                            <select>
                                <option>انتخاب کنید</option>
                                <option>مرد</option>
                                <option>زن</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="arta-form-step" data-step="2" style="display: none;">
                    <h3>انتخاب پزشک</h3>
                    <div id="arta-doctors-list" class="arta-doctors-list">
                        <?php if (!empty($doctors_data)): ?>
                            <?php foreach ($doctors_data as $doctor): ?>
                                <div class="arta-doctor-item" data-doctor-id="<?php echo esc_attr($doctor['id']); ?>">
                                    <div class="arta-doctor-avatar">
                                        <?php if (!empty($doctor['avatar'])): ?>
                                            <img src="<?php echo esc_url($doctor['avatar']); ?>" alt="<?php echo esc_attr($doctor['name']); ?>">
                                        <?php else: ?>
                                            <div class="arta-doctor-placeholder"><?php echo esc_html(substr($doctor['name'], 0, 1)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="arta-doctor-name"><?php echo esc_html($doctor['name']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="arta-no-doctors">هیچ پزشکی برای این برنامه تعریف نشده است.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="arta-form-step" data-step="3" style="display: none;">
                    <h3>انتخاب زمان</h3>
                    <div id="arta-time-slots" class="arta-time-slots">
                        <p>ابتدا پزشک مورد نظر خود را انتخاب کنید.</p>
                    </div>
                </div>
                <div class="arta-form-footer">
                    <button type="button" class="arta-btn arta-btn-primary">بعدی</button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get render on change
     */
    public function get_render_on_change() {
        return true;
    }
}
