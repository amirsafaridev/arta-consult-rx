<?php
/**
 * Admin Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle admin functionality
 */
class Arta_Admin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_arta_create_appointments', array($this, 'ajax_create_appointments'));
        add_action('wp_ajax_arta_get_appointment_details', array($this, 'get_appointment_details'));
        add_action('wp_ajax_arta_get_appointment_for_edit', array($this, 'get_appointment_for_edit'));
        add_action('wp_ajax_arta_save_appointment_edit', array($this, 'save_appointment_edit'));
        add_action('wp_ajax_arta_delete_appointment', array($this, 'delete_appointment'));
        add_action('wp_ajax_arta_create_doctor', array($this, 'create_doctor'));
        add_action('wp_ajax_arta_get_appointments', array($this, 'ajax_get_appointments'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            __('مشاوره پزشکی ', 'arta-consult-rx'),
            __('مشاوره پزشکی ', 'arta-consult-rx'),
            'manage_options',
            'arta-consult-rx',
            array($this, 'admin_page'),
            'dashicons-calendar-alt',
            30
        );

        // Appointments submenu
        add_submenu_page(
            'arta-consult-rx',
            __('تعریف نوبت', 'arta-consult-rx'),
            __('تعریف نوبت', 'arta-consult-rx'),
            'manage_options',
            'arta-appointment-settings',
            array($this, 'appointment_settings_page')
        );

        // Calendar submenu
        add_submenu_page(
            'arta-consult-rx',
            __('تقویم نوبت‌ها', 'arta-consult-rx'),
            __('تقویم نوبت‌ها', 'arta-consult-rx'),
            'manage_options',
            'arta-appointment-calendar',
            array($this, 'appointment_calendar_page')
        );

        // Doctors submenu
        add_submenu_page(
            'arta-consult-rx',
            __('لیست پزشکان', 'arta-consult-rx'),
            __('لیست پزشکان', 'arta-consult-rx'),
            'manage_options',
            'arta-doctors-list',
            array($this, 'doctors_list_page')
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'arta-') !== false) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-datepicker');
            wp_enqueue_script('select2');
            wp_enqueue_style('select2');
            
            wp_enqueue_script(
                'arta-admin-js',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-datepicker'),
                ARTA_CONSULT_RX_VERSION,
                true
            );

            wp_enqueue_style(
                'arta-admin-css',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                ARTA_CONSULT_RX_VERSION
            );

            wp_localize_script('arta-admin-js', 'arta_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arta_admin_nonce'),
                'strings' => array(
                    'confirm_delete' => __('آیا مطمئن هستید که می‌خواهید این نوبت را حذف کنید؟', 'arta-consult-rx'),
                    'loading' => __('در حال بارگذاری...', 'arta-consult-rx'),
                    'error' => __('خطا در انجام عملیات', 'arta-consult-rx'),
                    'success' => __('عملیات با موفقیت انجام شد', 'arta-consult-rx')
                )
            ));
        }
    }

    /**
     * Main admin page
     */
    public function admin_page() {
        $programs_count = wp_count_posts('arta_program')->publish;
        $doctors_count = count(Arta_User_Roles::get_doctor_users());
        $today_appointments = count(Arta_Database::get_appointments(array('date' => current_time('Y-m-d'))));
        $booked_appointments = count(Arta_Database::get_appointments(array('status' => 'booked')));
        ?>
        <div class="wrap arta-admin">
            <h1><?php _e('Arta Consult RX', 'arta-consult-rx'); ?></h1>
            
            <div class="arta-stats-grid">
                <div class="arta-stat-card">
                    <div class="arta-stat-icon">📋</div>
                    <div class="arta-stat-number"><?php echo $programs_count; ?></div>
                    <div class="arta-stat-label"><?php _e('برنامه‌های فعال', 'arta-consult-rx'); ?></div>
                </div>
                
                <div class="arta-stat-card">
                    <div class="arta-stat-icon">👨‍⚕️</div>
                    <div class="arta-stat-number"><?php echo $doctors_count; ?></div>
                    <div class="arta-stat-label"><?php _e('پزشکان متخصص', 'arta-consult-rx'); ?></div>
                </div>
                
                <div class="arta-stat-card">
                    <div class="arta-stat-icon">📅</div>
                    <div class="arta-stat-number"><?php echo $today_appointments; ?></div>
                    <div class="arta-stat-label"><?php _e('نوبت‌های امروز', 'arta-consult-rx'); ?></div>
                </div>
                
                <div class="arta-stat-card">
                    <div class="arta-stat-icon">✅</div>
                    <div class="arta-stat-number"><?php echo $booked_appointments; ?></div>
                    <div class="arta-stat-label"><?php _e('نوبت‌های رزرو شده', 'arta-consult-rx'); ?></div>
                </div>
            </div>

            <div class="arta-card">
                <div class="arta-card-header">
                    <h2 class="arta-card-title"><?php _e('دسترسی سریع', 'arta-consult-rx'); ?></h2>
                    <p class="arta-card-subtitle"><?php _e('مدیریت آسان سیستم نوبت‌دهی', 'arta-consult-rx'); ?></p>
                </div>
                <div class="arta-card-content">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                        <a href="<?php echo admin_url('admin.php?page=arta-appointment-settings'); ?>" class="arta-btn arta-btn-primary">
                            <span>⚙️</span> <?php _e('تنظیمات نوبت', 'arta-consult-rx'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=arta-appointment-calendar'); ?>" class="arta-btn arta-btn-secondary">
                            <span>📅</span> <?php _e('تقویم نوبت‌ها', 'arta-consult-rx'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=arta-doctors-list'); ?>" class="arta-btn arta-btn-secondary">
                            <span>👨‍⚕️</span> <?php _e('مدیریت پزشکان', 'arta-consult-rx'); ?>
                        </a>
                        <a href="<?php echo admin_url('post-new.php?post_type=arta_program'); ?>" class="arta-btn arta-btn-secondary">
                            <span>➕</span> <?php _e('برنامه جدید', 'arta-consult-rx'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Appointment settings page
     */
    public function appointment_settings_page() {
        if (isset($_POST['submit']) && wp_verify_nonce($_POST['arta_appointment_nonce'], 'arta_appointment_settings')) {
            $this->handle_appointment_creation();
        }

        $doctors = Arta_User_Roles::get_doctor_users();
        ?>
        <div class="wrap arta-admin">
            <h1><?php _e('تعریف نوبت', 'arta-consult-rx'); ?></h1>
            
          

            <div class="arta-card">
                <div class="arta-card-header">
                    <h2 class="arta-card-title"><?php _e('ایجاد نوبت‌های دسته‌جمعی', 'arta-consult-rx'); ?></h2>
                    <p class="arta-card-subtitle"><?php _e('تعریف نوبت‌های متعدد برای یک بازه زمانی مشخص', 'arta-consult-rx'); ?></p>
                </div>
                <div class="arta-card-content">
                    <form method="post" action="" class="arta-form">
                        <?php wp_nonce_field('arta_appointment_settings', 'arta_appointment_nonce'); ?>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                            <div class="arta-form-group">
                                <label for="arta_doctor_id" class="arta-form-label"><?php _e('انتخاب پزشک', 'arta-consult-rx'); ?></label>
                                <select id="arta_doctor_id" name="arta_program[doctor_id]" class="arta-form-select" required>
                                    <option value=""><?php _e('انتخاب کنید...', 'arta-consult-rx'); ?></option>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?php echo esc_attr($doctor->ID); ?>">
                                            <?php echo esc_html($doctor->display_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="arta-form-group">
                                <label for="arta_start_date" class="arta-form-label"><?php _e('تاریخ شروع', 'arta-consult-rx'); ?></label>
                                <input type="date" id="arta_start_date" name="arta_program[start_date]" class="arta-form-input" required>
                            </div>

                            <div class="arta-form-group">
                                <label for="arta_end_date" class="arta-form-label"><?php _e('تاریخ پایان', 'arta-consult-rx'); ?></label>
                                <input type="date" id="arta_end_date" name="arta_program[end_date]" class="arta-form-input" required>
                            </div>

                            <div class="arta-form-group">
                                <label for="arta_start_time" class="arta-form-label"><?php _e('ساعت شروع', 'arta-consult-rx'); ?></label>
                                <input type="time" id="arta_start_time" name="arta_program[start_time]" class="arta-form-input" required>
                            </div>

                            <div class="arta-form-group">
                                <label for="arta_end_time" class="arta-form-label"><?php _e('ساعت پایان', 'arta-consult-rx'); ?></label>
                                <input type="time" id="arta_end_time" name="arta_program[end_time]" class="arta-form-input" required>
                            </div>

                            <div class="arta-form-group">
                                <label for="arta_interval_minutes" class="arta-form-label"><?php _e('فاصله بین نوبت‌ها (دقیقه)', 'arta-consult-rx'); ?></label>
                                <select id="arta_interval_minutes" name="arta_program[interval_minutes]" class="arta-form-select" required>
                                    <option value="15">15 <?php _e('دقیقه', 'arta-consult-rx'); ?></option>
                                    <option value="30" selected>30 <?php _e('دقیقه', 'arta-consult-rx'); ?></option>
                                    <option value="45">45 <?php _e('دقیقه', 'arta-consult-rx'); ?></option>
                                    <option value="60">60 <?php _e('دقیقه', 'arta-consult-rx'); ?></option>
                                    <option value="90">90 <?php _e('دقیقه', 'arta-consult-rx'); ?></option>
                                    <option value="120">120 <?php _e('دقیقه', 'arta-consult-rx'); ?></option>
                                </select>
                                <p style="font-size: 12px; color: rgba(33, 33, 33, 0.6); margin-top: 8px;">
                                    <?php _e('فاصله زمانی بین هر نوبت (حداقل 15 دقیقه)', 'arta-consult-rx'); ?>
                                </p>
                            </div>
                        </div>

                        <div class="arta-card-actions">
                            <button type="submit" name="submit" class="arta-btn arta-btn-primary">
                                <span>⚡</span> <?php _e('ایجاد نوبت‌ها', 'arta-consult-rx'); ?>
                            </button>
                            <button type="reset" class="arta-btn arta-btn-flat">
                                <?php _e('پاک کردن فرم', 'arta-consult-rx'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Handle appointment creation
     */
    private function handle_appointment_creation() {
        if (!isset($_POST['arta_program'])) {
            echo '<div class="arta-notice arta-notice-error"><p>' . __('خطا در دریافت اطلاعات فرم.', 'arta-consult-rx') . '</p></div>';
            return;
        }

        $program_data = $_POST['arta_program'];
        $doctor_id = intval($program_data['doctor_id']);
        $start_date = sanitize_text_field($program_data['start_date']);
        $end_date = sanitize_text_field($program_data['end_date']);
        $start_time = sanitize_text_field($program_data['start_time']);
        $end_time = sanitize_text_field($program_data['end_time']);
        $interval_minutes = intval($program_data['interval_minutes']);

        // Validation
        if (!$doctor_id || !$start_date || !$end_date || !$start_time || !$end_time) {
            echo '<div class="arta-notice arta-notice-error"><p>' . __('لطفاً تمام فیلدهای الزامی را پر کنید.', 'arta-consult-rx') . '</p></div>';
            return;
        }

        if (strtotime($start_date) > strtotime($end_date)) {
            echo '<div class="arta-notice arta-notice-error"><p>' . __('تاریخ شروع نمی‌تواند بعد از تاریخ پایان باشد.', 'arta-consult-rx') . '</p></div>';
            return;
        }

        if (strtotime($start_time) >= strtotime($end_time)) {
            echo '<div class="arta-notice arta-notice-error"><p>' . __('ساعت شروع باید قبل از ساعت پایان باشد.', 'arta-consult-rx') . '</p></div>';
            return;
        }

        // Debug table structure
        Arta_Database::debug_table_structure();
        
        $created_count = Arta_Database::create_bulk_appointments(
            $doctor_id,
            $start_date,
            $end_date,
            $start_time,
            $end_time,
            $interval_minutes
        );

        if ($created_count > 0) {
            echo '<div class="arta-notice arta-notice-success"><p>' . sprintf(__('✅ %d نوبت با موفقیت ایجاد شد.', 'arta-consult-rx'), $created_count) . '</p></div>';
        } else {
            echo '<div class="arta-notice arta-notice-error"><p>' . __('❌ خطا در ایجاد نوبت‌ها. لطفاً اطلاعات را بررسی کنید.', 'arta-consult-rx') . '</p></div>';
            echo '<div class="arta-notice arta-notice-info"><p>' . __('برای بررسی جزئیات خطا، فایل debug.log را بررسی کنید.', 'arta-consult-rx') . '</p></div>';
        }
    }

    /**
     * Appointment calendar page
     */
    public function appointment_calendar_page() {
        $current_month = isset($_GET['month']) ? sanitize_text_field($_GET['month']) : current_time('Y-m');
        $selected_doctor = isset($_GET['doctor']) ? intval($_GET['doctor']) : 0;

        $doctors = Arta_User_Roles::get_doctor_users();
        ?>
        <div class="wrap arta-admin">
            <h1><?php _e('تقویم نوبت‌ها', 'arta-consult-rx'); ?></h1>
            
            <div class="arta-filters-inline">
                <form method="get" action="" class="arta-form-inline">
                    <input type="hidden" name="page" value="arta-appointment-calendar">
                    
                    <div class="arta-filter-item">
                        <label for="arta_month_filter"><?php _e('ماه:', 'arta-consult-rx'); ?></label>
                        <select id="arta_month_filter" name="month" class="arta-form-select">
                            <?php
                            for ($i = -6; $i <= 6; $i++) {
                                $month = date('Y-m', strtotime("$i months"));
                                $month_name = date_i18n('F Y', strtotime($month));
                                $selected = ($month === $current_month) ? 'selected' : '';
                                echo "<option value='$month' $selected>$month_name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="arta-filter-item">
                        <label for="arta_doctor_filter"><?php _e('پزشک:', 'arta-consult-rx'); ?></label>
                        <select id="arta_doctor_filter" name="doctor" class="arta-form-select">
                            <option value="0"><?php _e('همه', 'arta-consult-rx'); ?></option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo esc_attr($doctor->ID); ?>" <?php selected($selected_doctor, $doctor->ID); ?>>
                                    <?php echo esc_html($doctor->display_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="arta-filter-item">
                        <button type="submit" class="arta-btn-simple">
                            <?php _e('نمایش', 'arta-consult-rx'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <div class="arta-section">
                <h2><?php echo date_i18n('F Y', strtotime($current_month . '-01')); ?></h2>
                <div id="arta-calendar-container">
                    <?php $this->render_calendar($current_month, $selected_doctor); ?>
                </div>
            </div>

            <div class="arta-section">
                <h2><?php _e('لیست نوبت‌ها', 'arta-consult-rx'); ?></h2>
                <div id="arta-appointments-list">
                    <?php $this->render_appointments_list($current_month, $selected_doctor); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render calendar
     */
    private function render_calendar($month, $doctor_id = 0) {
        $args = array(
            'doctor_id' => $doctor_id
            // Remove month filter to show all appointments
        );

        $appointments = Arta_Database::get_appointments($args);
        $appointments_by_date = array();
        



        foreach ($appointments as $appointment) {
            $appointments_by_date[$appointment->appointment_date][] = $appointment;
        }
        

        $first_day = new DateTime($month . '-01');
        $last_day = new DateTime($first_day->format('Y-m-t'));
        $start_date = clone $first_day;
        $start_date->modify('first day of this week');

        echo '<div class="arta-calendar-simple">';
        
        echo '<div class="arta-calendar-grid">';
        
        // Day headers
        $day_names = array(
            __('شنبه', 'arta-consult-rx'),
            __('یکشنبه', 'arta-consult-rx'),
            __('دوشنبه', 'arta-consult-rx'),
            __('سه‌شنبه', 'arta-consult-rx'),
            __('چهارشنبه', 'arta-consult-rx'),
            __('پنج‌شنبه', 'arta-consult-rx'),
            __('جمعه', 'arta-consult-rx')
        );
        
        foreach ($day_names as $day_name) {
            echo '<div class="arta-calendar-day-header">' . $day_name . '</div>';
        }

        $current_date = clone $start_date;
        for ($week = 0; $week < 6; $week++) {
            for ($day = 0; $day < 7; $day++) {
                $date_str = $current_date->format('Y-m-d');
                $is_current_month = $current_date->format('Y-m') === $month;
                $is_today = $date_str === current_time('Y-m-d');
                
                
                $class = 'arta-calendar-day';
                if (!$is_current_month) $class .= ' arta-other-month';
                if ($is_today) $class .= ' arta-today';
                
                $appointment_count = isset($appointments_by_date[$date_str]) ? count($appointments_by_date[$date_str]) : 0;
                $booked_count = 0;
                $completed_count = 0;
                $cancelled_count = 0;
                $available_count = 0;
                
                if (isset($appointments_by_date[$date_str])) {
                    foreach ($appointments_by_date[$date_str] as $appointment) {
                        switch ($appointment->status) {
                            case 'booked':
                                $booked_count++;
                                break;
                            case 'completed':
                                $completed_count++;
                                break;
                            case 'cancelled':
                                $cancelled_count++;
                                break;
                            case 'available':
                            default:
                                $available_count++;
                                break;
                        }
                    }
                }
                
                // Add status classes to calendar day
                if ($appointment_count > 0) {
                    if ($booked_count > 0) $class .= ' has-booked';
                    if ($completed_count > 0) $class .= ' has-completed';
                    if ($cancelled_count > 0) $class .= ' has-cancelled';
                    if ($available_count > 0) $class .= ' has-appointments';
                }
                
                echo "<div class='$class' data-date='$date_str'>";
                echo '<div class="arta-day-number">' . $current_date->format('j') . '</div>';
                
                if ($appointment_count > 0) {
                    echo '<div class="arta-appointments-list">';
                    $appointments_for_date = $appointments_by_date[$date_str];
                    
                    foreach ($appointments_for_date as $appointment) {
                        $status_class = 'available';
                        $status_text = 'آزاد';
                        switch ($appointment->status) {
                            case 'booked':
                                $status_class = 'booked';
                                $status_text = 'رزرو';
                                break;
                            case 'completed':
                                $status_class = 'completed';
                                $status_text = 'تکمیل';
                                break;
                            case 'cancelled':
                                $status_class = 'cancelled';
                                $status_text = 'لغو';
                                break;
                        }
                        
                        $time = date_i18n('H:i', strtotime($appointment->appointment_time));
                        echo '<div class="arta-appointment-item ' . $status_class . '">';
                        echo '<span class="arta-appointment-time">' . $time . '</span>';
                        echo '<span class="arta-appointment-status">' . $status_text . '</span>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                
                echo '</div>';
                
                $current_date->add(new DateInterval('P1D'));
            }
        }
        
        echo '</div>';
        echo '</div>';
    }

    /**
     * Render appointments list
     */
    private function render_appointments_list($month, $doctor_id = 0) {
        $args = array(
            'month' => $month,
            'doctor_id' => $doctor_id,
            'orderby' => 'appointment_date',
            'order' => 'ASC'
        );

        $appointments = Arta_Database::get_appointments($args);
        ?>
        <?php if (empty($appointments)): ?>
            <div class="arta-notice arta-notice-info">
                <p><?php _e('📅 هیچ نوبتی برای این ماه یافت نشد.', 'arta-consult-rx'); ?></p>
            </div>
        <?php else: ?>
            <div class="arta-table-container">
                <table class="arta-table">
                    <thead>
                        <tr>
                            <th><?php _e('تاریخ', 'arta-consult-rx'); ?></th>
                            <th><?php _e('ساعت', 'arta-consult-rx'); ?></th>
                            <th><?php _e('پزشک', 'arta-consult-rx'); ?></th>
                            <th><?php _e('وضعیت', 'arta-consult-rx'); ?></th>
                            <th><?php _e('بیمار', 'arta-consult-rx'); ?></th>
                            <th><?php _e('عملیات', 'arta-consult-rx'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <?php
                            $doctor = get_userdata($appointment->doctor_id);
                            $status_labels = array(
                                'available' => __('موجود', 'arta-consult-rx'),
                                'booked' => __('رزرو شده', 'arta-consult-rx'),
                                'completed' => __('تکمیل شده', 'arta-consult-rx'),
                                'cancelled' => __('لغو شده', 'arta-consult-rx')
                            );
                            $status_classes = array(
                                'available' => 'arta-badge-success',
                                'booked' => 'arta-badge-warning',
                                'completed' => 'arta-badge-info',
                                'cancelled' => 'arta-badge-error'
                            );
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo date_i18n('Y/m/d', strtotime($appointment->appointment_date)); ?></strong>
                                </td>
                                <td>
                                    <span style="font-family: monospace;">
                                        <?php echo date_i18n('H:i', strtotime($appointment->appointment_time)); ?>
                                    </span>
                                </td>
                                <td><?php echo $doctor ? esc_html($doctor->display_name) : '-'; ?></td>
                                <td>
                                    <span class="arta-badge <?php echo esc_attr($status_classes[$appointment->status] ?? 'arta-badge-info'); ?>">
                                        <?php echo esc_html($status_labels[$appointment->status] ?? $appointment->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($appointment->patient_name): ?>
                                        <strong><?php echo esc_html($appointment->patient_name); ?></strong>
                                        <?php if ($appointment->patient_phone): ?>
                                            <br><small><?php echo esc_html($appointment->patient_phone); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color: rgba(33, 33, 33, 0.5);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="arta-action-buttons">
                                        <button class="arta-btn-action arta-btn-view" data-id="<?php echo esc_attr($appointment->id); ?>" title="<?php _e('مشاهده جزئیات', 'arta-consult-rx'); ?>">
                                            👁️
                                        </button>
                                        <button class="arta-btn-action arta-btn-edit" data-id="<?php echo esc_attr($appointment->id); ?>" title="<?php _e('ویرایش نوبت', 'arta-consult-rx'); ?>">
                                            ✏️
                                        </button>
                                        <button class="arta-btn-action arta-btn-delete" data-id="<?php echo esc_attr($appointment->id); ?>" title="<?php _e('حذف نوبت', 'arta-consult-rx'); ?>">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Appointment Details Modal -->
        <div id="arta-appointment-modal" class="arta-modal" style="display: none;">
            <div class="arta-modal-content">
                <div class="arta-modal-header">
                    <h3 id="arta-modal-title"><?php _e('جزئیات نوبت', 'arta-consult-rx'); ?></h3>
                    <button class="arta-modal-close">&times;</button>
                </div>
                <div class="arta-modal-body" id="arta-modal-body">
                    <!-- Content will be loaded here -->
                </div>
                <div class="arta-modal-footer">
                    <button class="arta-btn-simple" id="arta-modal-close-btn"><?php _e('بستن', 'arta-consult-rx'); ?></button>
                </div>
            </div>
        </div>

        <!-- Edit Appointment Modal -->
        <div id="arta-edit-appointment-modal" class="arta-modal" style="display: none;">
            <div class="arta-modal-content">
                <div class="arta-modal-header">
                    <h3><?php _e('ویرایش نوبت', 'arta-consult-rx'); ?></h3>
                    <button class="arta-modal-close">&times;</button>
                </div>
                <div class="arta-modal-body">
                    <form id="arta-edit-appointment-form">
                        <input type="hidden" id="arta-edit-appointment-id" name="appointment_id">
                        
                        <div class="arta-form-group">
                            <label for="arta-edit-date"><?php _e('تاریخ', 'arta-consult-rx'); ?></label>
                            <input type="date" id="arta-edit-date" name="appointment_date" class="arta-form-input" required>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="arta-edit-time"><?php _e('ساعت', 'arta-consult-rx'); ?></label>
                            <input type="time" id="arta-edit-time" name="appointment_time" class="arta-form-input" required>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="arta-edit-status"><?php _e('وضعیت', 'arta-consult-rx'); ?></label>
                            <select id="arta-edit-status" name="status" class="arta-form-select">
                                <option value="available"><?php _e('موجود', 'arta-consult-rx'); ?></option>
                                <option value="booked"><?php _e('رزرو شده', 'arta-consult-rx'); ?></option>
                                <option value="completed"><?php _e('تکمیل شده', 'arta-consult-rx'); ?></option>
                                <option value="cancelled"><?php _e('لغو شده', 'arta-consult-rx'); ?></option>
                            </select>
                        </div>
                        
                    </form>
                </div>
                <div class="arta-modal-footer">
                    <button class="arta-btn-simple" id="arta-edit-save-btn"><?php _e('ذخیره', 'arta-consult-rx'); ?></button>
                    <button class="arta-btn-simple" id="arta-edit-cancel-btn"><?php _e('لغو', 'arta-consult-rx'); ?></button>
                </div>
            </div>
        </div>


        <script>
        jQuery(document).ready(function($) {
            // View appointment details
            $('.arta-btn-view').on('click', function() {
                var appointmentId = $(this).data('id');
                loadAppointmentDetails(appointmentId);
            });
            
            // Edit appointment
            $('.arta-btn-edit').on('click', function() {
                var appointmentId = $(this).data('id');
                loadAppointmentForEdit(appointmentId);
            });
            
            // Delete appointment
            $('.arta-btn-delete').on('click', function() {
                var appointmentId = $(this).data('id');
                if (confirm('<?php _e('آیا مطمئن هستید که می‌خواهید این نوبت را حذف کنید؟', 'arta-consult-rx'); ?>')) {
                    deleteAppointment(appointmentId);
                }
            });
            
            // Modal close
            $('.arta-modal-close, #arta-modal-close-btn, #arta-edit-cancel-btn').on('click', function() {
                $('.arta-modal').hide();
            });
            
            // Save edit
            $('#arta-edit-save-btn').on('click', function() {
                saveAppointmentEdit();
            });
            
            function loadAppointmentDetails(appointmentId) {
                var $btn = $('.arta-btn-view[data-id="' + appointmentId + '"]');
                var $row = $btn.closest('tr');
                
                // Add loading state
                $btn.addClass('arta-loading');
                $row.addClass('arta-loading');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'arta_get_appointment_details',
                        appointment_id: appointmentId,
                        nonce: '<?php echo wp_create_nonce('arta_appointment_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#arta-modal-body').html(response.data.html);
                            $('#arta-appointment-modal').show();
                        }
                    },
                    complete: function() {
                        // Remove loading state
                        $btn.removeClass('arta-loading');
                        $row.removeClass('arta-loading');
                    }
                });
            }
            
            function loadAppointmentForEdit(appointmentId) {
                var $btn = $('.arta-btn-edit[data-id="' + appointmentId + '"]');
                var $row = $btn.closest('tr');
                
                // Add loading state
                $btn.addClass('arta-loading');
                $row.addClass('arta-loading');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'arta_get_appointment_for_edit',
                        appointment_id: appointmentId,
                        nonce: '<?php echo wp_create_nonce('arta_appointment_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            var data = response.data;
                            $('#arta-edit-appointment-id').val(data.id);
                            $('#arta-edit-date').val(data.appointment_date);
                            $('#arta-edit-time').val(data.appointment_time);
                            $('#arta-edit-status').val(data.status);
                            $('#arta-edit-appointment-modal').show();
                        }
                    },
                    complete: function() {
                        // Remove loading state
                        $btn.removeClass('arta-loading');
                        $row.removeClass('arta-loading');
                    }
                });
            }
            
            function saveAppointmentEdit() {
                var $btn = $('#arta-edit-save-btn');
                var formData = $('#arta-edit-appointment-form').serialize();
                formData += '&action=arta_save_appointment_edit&nonce=<?php echo wp_create_nonce('arta_appointment_nonce'); ?>';
                
                // Add loading state
                $btn.addClass('arta-loading').prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert('<?php _e('نوبت با موفقیت به‌روزرسانی شد', 'arta-consult-rx'); ?>');
                            location.reload();
                        } else {
                            alert('<?php _e('خطا در به‌روزرسانی نوبت', 'arta-consult-rx'); ?>');
                        }
                    },
                    complete: function() {
                        // Remove loading state
                        $btn.removeClass('arta-loading').prop('disabled', false);
                    }
                });
            }
            
            function deleteAppointment(appointmentId) {
                var $btn = $('.arta-btn-delete[data-id="' + appointmentId + '"]');
                var $row = $btn.closest('tr');
                
                // Add loading state
                $btn.addClass('arta-loading');
                $row.addClass('arta-loading');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'arta_delete_appointment',
                        appointment_id: appointmentId,
                        nonce: '<?php echo wp_create_nonce('arta_appointment_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('<?php _e('نوبت با موفقیت حذف شد', 'arta-consult-rx'); ?>');
                            location.reload();
                        } else {
                            alert('<?php _e('خطا در حذف نوبت', 'arta-consult-rx'); ?>');
                        }
                    },
                    complete: function() {
                        // Remove loading state
                        $btn.removeClass('arta-loading');
                        $row.removeClass('arta-loading');
                    }
                });
            }
        });
        </script>
        <?php
    }

    /**
     * Doctors list page
     */
    public function doctors_list_page() {
        $doctors = Arta_User_Roles::get_doctor_users();
        ?>
        <div class="wrap arta-admin">
            <h1><?php _e('لیست پزشکان', 'arta-consult-rx'); ?></h1>
            
            <div class="arta-card">
               
                <div class="arta-card-actions">
                    <button type="button" id="arta-add-doctor-btn" class="arta-btn arta-btn-primary">
                        <span>➕</span> <?php _e('اضافه کردن پزشک جدید', 'arta-consult-rx'); ?>
                    </button>
                </div>
            </div>

            <div class="arta-card">
                <div class="arta-card-content">
                    <?php if (empty($doctors)): ?>
                        <div class="arta-notice arta-notice-info">
                            <p><?php _e('👨‍⚕️ هیچ پزشکی در سیستم ثبت نشده است.', 'arta-consult-rx'); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="arta-table-container">
                            <table class="arta-table">
                                <thead>
                                    <tr>
                                        <th><?php _e('پزشک', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('اطلاعات تماس', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('تاریخ عضویت', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('آخرین ورود', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('آمار نوبت‌ها', 'arta-consult-rx'); ?></th>
                                        <th><?php _e('عملیات', 'arta-consult-rx'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <?php
                                        $appointments_count = count(Arta_Database::get_appointments(array('doctor_id' => $doctor->ID)));
                                        $booked_appointments = count(Arta_Database::get_appointments(array('doctor_id' => $doctor->ID, 'status' => 'booked')));
                                        $available_appointments = $appointments_count - $booked_appointments;
                                        ?>
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 12px;">
                                                    <div style="width: 40px; height: 40px; background: var(--arta-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        <?php echo strtoupper(substr($doctor->display_name, 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo esc_html($doctor->display_name); ?></strong>
                                                        <br><small style="color: rgba(33, 33, 33, 0.6);"><?php _e('پزشک متخصص', 'arta-consult-rx'); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo esc_html($doctor->user_email); ?></strong>
                                                    <?php if ($doctor->user_url): ?>
                                                        <br><a href="<?php echo esc_url($doctor->user_url); ?>" target="_blank" style="color: var(--arta-primary);"><?php echo esc_html($doctor->user_url); ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span style="font-family: monospace;">
                                                    <?php echo date_i18n('Y/m/d', strtotime($doctor->user_registered)); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($doctor->last_login): ?>
                                                    <span style="font-family: monospace;">
                                                        <?php echo date_i18n('Y/m/d H:i', strtotime($doctor->last_login)); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span style="color: rgba(33, 33, 33, 0.5);"><?php _e('هرگز', 'arta-consult-rx'); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                                    <span class="arta-badge arta-badge-info" title="<?php _e('کل نوبت‌ها', 'arta-consult-rx'); ?>">
                                                        <?php echo $appointments_count; ?> <?php _e('کل', 'arta-consult-rx'); ?>
                                                    </span>
                                                    <span class="arta-badge arta-badge-warning" title="<?php _e('نوبت‌های رزرو شده', 'arta-consult-rx'); ?>">
                                                        <?php echo $booked_appointments; ?> <?php _e('رزرو', 'arta-consult-rx'); ?>
                                                    </span>
                                                    <span class="arta-badge arta-badge-success" title="<?php _e('نوبت‌های آزاد', 'arta-consult-rx'); ?>">
                                                        <?php echo $available_appointments; ?> <?php _e('آزاد', 'arta-consult-rx'); ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                                    <a href="<?php echo admin_url('user-edit.php?user_id=' . $doctor->ID); ?>" class="arta-btn arta-btn-flat" title="<?php _e('ویرایش اطلاعات پزشک', 'arta-consult-rx'); ?>">
                                                        ✏️
                                                    </a>
                                                    <a href="<?php echo admin_url('admin.php?page=arta-appointment-calendar&doctor=' . $doctor->ID); ?>" class="arta-btn arta-btn-flat" title="<?php _e('مشاهده نوبت‌های پزشک', 'arta-consult-rx'); ?>">
                                                        📅
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Add Doctor Modal -->
        <div id="arta-add-doctor-modal" class="arta-modal" style="display: none;">
            <div class="arta-modal-content">
                <div class="arta-modal-header">
                    <h3><?php _e('افزودن پزشک جدید', 'arta-consult-rx'); ?></h3>
                    <button class="arta-modal-close">&times;</button>
                </div>
                <div class="arta-modal-body">
                    <form id="arta-add-doctor-form">
                        <div class="arta-form-group">
                            <label for="doctor_username"><?php _e('نام کاربری', 'arta-consult-rx'); ?> *</label>
                            <input type="text" id="doctor_username" name="username" class="arta-form-input" required>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="doctor_display_name"><?php _e('نام پزشک', 'arta-consult-rx'); ?> *</label>
                            <input type="text" id="doctor_display_name" name="display_name" class="arta-form-input" required>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="doctor_email"><?php _e('ایمیل', 'arta-consult-rx'); ?> *</label>
                            <input type="email" id="doctor_email" name="email" class="arta-form-input" required>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="doctor_password"><?php _e('رمز عبور', 'arta-consult-rx'); ?> *</label>
                            <input type="password" id="doctor_password" name="password" class="arta-form-input" required>
                        </div>
                    </form>
                </div>
                <div class="arta-modal-footer">
                    <button class="arta-btn-simple" id="arta-add-doctor-save-btn"><?php _e('ایجاد پزشک', 'arta-consult-rx'); ?></button>
                    <button class="arta-btn-simple" id="arta-add-doctor-cancel-btn"><?php _e('لغو', 'arta-consult-rx'); ?></button>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Open add doctor modal
            $('#arta-add-doctor-btn').on('click', function() {
                $('#arta-add-doctor-modal').show();
            });

            // Close modal
            $('.arta-modal-close, #arta-add-doctor-cancel-btn').on('click', function() {
                $('#arta-add-doctor-modal').hide();
                $('#arta-add-doctor-form')[0].reset();
            });

            // Save doctor
            $('#arta-add-doctor-save-btn').on('click', function() {
                var formData = {
                    action: 'arta_create_doctor',
                    nonce: '<?php echo wp_create_nonce('arta_admin_nonce'); ?>',
                    username: $('#doctor_username').val(),
                    display_name: $('#doctor_display_name').val(),
                    email: $('#doctor_email').val(),
                    password: $('#doctor_password').val()
                };

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert(response.data.message);
                            location.reload();
                        } else {
                            alert(response.data.message || 'خطا در ایجاد پزشک');
                        }
                    },
                    error: function() {
                        alert('خطا در ارتباط با سرور');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * AJAX: Create appointments
     */
    public function ajax_create_appointments() {
        check_ajax_referer('arta_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('شما دسترسی لازم را ندارید.', 'arta-consult-rx'));
        }

        $program_id = intval($_POST['program_id']);
        $doctor_id = intval($_POST['doctor_id']);
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);
        $start_time = sanitize_text_field($_POST['start_time']);
        $end_time = sanitize_text_field($_POST['end_time']);
        $interval_minutes = intval($_POST['interval_minutes']);

        $created_count = Arta_Database::create_bulk_appointments(
            $program_id,
            $doctor_id,
            $start_date,
            $end_date,
            $start_time,
            $end_time,
            $interval_minutes
        );

        wp_send_json_success(array(
            'message' => sprintf(__('%d نوبت با موفقیت ایجاد شد.', 'arta-consult-rx'), $created_count),
            'count' => $created_count
        ));
    }

    /**
     * AJAX: Delete appointment
     */
    public function ajax_delete_appointment() {
        check_ajax_referer('arta_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('شما دسترسی لازم را ندارید.', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);

        $result = Arta_Database::delete_appointment($appointment_id);

        if ($result) {
            wp_send_json_success(array(
                'message' => __('نوبت با موفقیت حذف شد.', 'arta-consult-rx')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('خطا در حذف نوبت.', 'arta-consult-rx')
            ));
        }
    }

    /**
     * AJAX: Get appointments
     */
    public function ajax_get_appointments() {
        check_ajax_referer('arta_admin_nonce', 'nonce');

        $args = array();
        if (isset($_POST['date'])) {
            $args['date'] = sanitize_text_field($_POST['date']);
        }
        if (isset($_POST['doctor_id'])) {
            $args['doctor_id'] = intval($_POST['doctor_id']);
        }

        $appointments = Arta_Database::get_appointments($args);

        wp_send_json_success($appointments);
    }

    /**
     * Get appointment details for modal
     */
    public function get_appointment_details() {
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);
        $appointment = Arta_Database::get_appointment($appointment_id);

        if (!$appointment) {
            wp_send_json_error(__('نوبت یافت نشد', 'arta-consult-rx'));
        }

        $doctor = get_userdata($appointment->doctor_id);
        $status_labels = array(
            'available' => __('موجود', 'arta-consult-rx'),
            'booked' => __('رزرو شده', 'arta-consult-rx'),
            'completed' => __('تکمیل شده', 'arta-consult-rx'),
            'cancelled' => __('لغو شده', 'arta-consult-rx')
        );

        $html = '<div class="arta-appointment-details">';
        $html .= '<div class="arta-detail-row"><strong>' . __('تاریخ:', 'arta-consult-rx') . '</strong> ' . date_i18n('Y/m/d', strtotime($appointment->appointment_date)) . '</div>';
        $html .= '<div class="arta-detail-row"><strong>' . __('ساعت:', 'arta-consult-rx') . '</strong> ' . date_i18n('H:i', strtotime($appointment->appointment_time)) . '</div>';
        $html .= '<div class="arta-detail-row"><strong>' . __('پزشک:', 'arta-consult-rx') . '</strong> ' . ($doctor ? esc_html($doctor->display_name) : '-') . '</div>';
        $html .= '<div class="arta-detail-row"><strong>' . __('وضعیت:', 'arta-consult-rx') . '</strong> ' . esc_html($status_labels[$appointment->status] ?? $appointment->status) . '</div>';
        
        if ($appointment->patient_name) {
            $html .= '<div class="arta-detail-row"><strong>' . __('نام بیمار:', 'arta-consult-rx') . '</strong> ' . esc_html($appointment->patient_name) . '</div>';
        }
        if ($appointment->patient_phone) {
            $html .= '<div class="arta-detail-row"><strong>' . __('شماره تماس:', 'arta-consult-rx') . '</strong> ' . esc_html($appointment->patient_phone) . '</div>';
        }
        if ($appointment->patient_email) {
            $html .= '<div class="arta-detail-row"><strong>' . __('ایمیل:', 'arta-consult-rx') . '</strong> ' . esc_html($appointment->patient_email) . '</div>';
        }
        if ($appointment->notes) {
            $html .= '<div class="arta-detail-row"><strong>' . __('یادداشت:', 'arta-consult-rx') . '</strong> ' . esc_html($appointment->notes) . '</div>';
        }
        
        $html .= '</div>';

        wp_send_json_success(array('html' => $html));
    }

    /**
     * Get appointment data for edit
     */
    public function get_appointment_for_edit() {
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);
        $appointment = Arta_Database::get_appointment($appointment_id);

        if (!$appointment) {
            wp_send_json_error(__('نوبت یافت نشد', 'arta-consult-rx'));
        }

        wp_send_json_success($appointment);
    }

    /**
     * Save appointment edit
     */
    public function save_appointment_edit() {
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);
        $appointment_date = sanitize_text_field($_POST['appointment_date']);
        $appointment_time = sanitize_text_field($_POST['appointment_time']);
        $status = sanitize_text_field($_POST['status']);
        $patient_name = sanitize_text_field($_POST['patient_name']);
        $patient_phone = sanitize_text_field($_POST['patient_phone']);
        $patient_email = sanitize_email($_POST['patient_email']);
        $notes = sanitize_textarea_field($_POST['notes']);

        $result = Arta_Database::update_appointment($appointment_id, array(
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'status' => $status,
            'patient_name' => $patient_name,
            'patient_phone' => $patient_phone,
            'patient_email' => $patient_email,
            'notes' => $notes
        ));

        if ($result) {
            wp_send_json_success(__('نوبت با موفقیت به‌روزرسانی شد', 'arta-consult-rx'));
        } else {
            wp_send_json_error(__('خطا در به‌روزرسانی نوبت', 'arta-consult-rx'));
        }
    }

    /**
     * Delete appointment
     */
    public function delete_appointment() {
        if (!wp_verify_nonce($_POST['nonce'], 'arta_appointment_nonce')) {
            wp_die(__('خطای امنیتی', 'arta-consult-rx'));
        }

        $appointment_id = intval($_POST['appointment_id']);
        $result = Arta_Database::delete_appointment($appointment_id);

        if ($result) {
            wp_send_json_success(__('نوبت با موفقیت حذف شد', 'arta-consult-rx'));
        } else {
            wp_send_json_error(__('خطا در حذف نوبت', 'arta-consult-rx'));
        }
    }

    /**
     * Create new doctor
     */
    public function create_doctor() {
        check_ajax_referer('arta_admin_nonce', 'nonce');
        
        if (!current_user_can('create_users')) {
            wp_die(__('شما مجوز لازم را ندارید', 'arta-consult-rx'));
        }

        $username = sanitize_user($_POST['username']);
        $display_name = sanitize_text_field($_POST['display_name']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        // Validate inputs
        if (empty($username) || empty($display_name) || empty($email) || empty($password)) {
            wp_send_json_error(array('message' => __('تمام فیلدها الزامی است', 'arta-consult-rx')));
        }

        // Check if username exists
        if (username_exists($username)) {
            wp_send_json_error(array('message' => __('نام کاربری قبلاً استفاده شده است', 'arta-consult-rx')));
        }

        // Check if email exists
        if (email_exists($email)) {
            wp_send_json_error(array('message' => __('ایمیل قبلاً استفاده شده است', 'arta-consult-rx')));
        }

        // Create user
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
        }

        // Update display name
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $display_name
        ));

        // Assign doctor role
        $user = new WP_User($user_id);
        $user->set_role('arta_doctor');

        wp_send_json_success(array('message' => __('پزشک با موفقیت ایجاد شد', 'arta-consult-rx')));
    }

}
