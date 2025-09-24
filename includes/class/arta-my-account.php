<?php
/**
 * My Account functionality for Arta Consult RX
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Arta_My_Account {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    /**
     * Initialize
     */
    public function init() {
        // Add menu item to WooCommerce My Account
        add_filter('woocommerce_account_menu_items', array($this, 'add_my_requests_menu_item'));
        add_action('init', array($this, 'add_my_requests_endpoint'));
        add_action('woocommerce_account_my-requests_endpoint', array($this, 'my_requests_content'));
        
        // Flush rewrite rules if needed
        add_action('init', array($this, 'maybe_flush_rewrite_rules'));
        
        // Add admin notice for rewrite rules
        add_action('admin_notices', array($this, 'admin_notice_rewrite_rules'));
        
        // Add admin action for manual flush
        add_action('admin_action_arta_flush_rewrite_rules', array($this, 'manual_flush_rewrite_rules'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Add My Requests menu item to WooCommerce My Account
     */
    public function add_my_requests_menu_item($items) {
        // Insert before logout
        $logout = $items['customer-logout'];
        unset($items['customer-logout']);
        
        $items['my-requests'] = __('درخواست‌های من', 'arta-consult-rx');
        $items['customer-logout'] = $logout;
        
        return $items;
    }

    /**
     * Add My Requests endpoint
     */
    public function add_my_requests_endpoint() {
        add_rewrite_endpoint('my-requests', EP_ROOT | EP_PAGES);
    }

    /**
     * Maybe flush rewrite rules
     */
    public function maybe_flush_rewrite_rules() {
        if (get_option('arta_my_requests_flush_rewrite_rules')) {
            flush_rewrite_rules();
            delete_option('arta_my_requests_flush_rewrite_rules');
        }
    }

    /**
     * Admin notice for rewrite rules
     */
    public function admin_notice_rewrite_rules() {
        if (get_option('arta_my_requests_flush_rewrite_rules')) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong><?php _e('Arta Consult RX:', 'arta-consult-rx'); ?></strong>
                    <?php _e('برای فعال‌سازی صفحه "درخواست‌های من" در حساب کاربری، لطفاً به', 'arta-consult-rx'); ?>
                    <a href="<?php echo admin_url('options-permalink.php'); ?>"><?php _e('تنظیمات پیوندهای یکتا', 'arta-consult-rx'); ?></a>
                    <?php _e('بروید و "ذخیره تغییرات" را کلیک کنید.', 'arta-consult-rx'); ?>
                </p>
            </div>
            <?php
        }
    }

    /**
     * My Requests content
     */
    public function my_requests_content() {
        if (!is_user_logged_in()) {
            echo '<p>' . __('برای مشاهده درخواست‌های خود باید وارد شوید.', 'arta-consult-rx') . '</p>';
            return;
        }

        $user_id = get_current_user_id();
        
        // Get user's consultation requests
        $consultations = get_posts(array(
            'post_type' => 'arta_consultation',
            'author' => $user_id,
            'numberposts' => -1,
            'post_status' => 'publish',
            'meta_key' => '_arta_approval_status',
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        ?>
        <div class="arta-my-requests">
            <h3><?php _e('درخواست‌های مشاوره من', 'arta-consult-rx'); ?></h3>
            
            <?php if (empty($consultations)): ?>
                <div class="arta-no-requests">
                    <p><?php _e('شما هنوز درخواست مشاوره‌ای ثبت نکرده‌اید.', 'arta-consult-rx'); ?></p>
                    <a href="<?php echo home_url('/programs/'); ?>" class="arta-btn arta-btn-primary">
                        <?php _e('مشاهده برنامه‌ها', 'arta-consult-rx'); ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="arta-requests-list">
                    <?php foreach ($consultations as $consultation): ?>
                        <?php
                        $approval_status = get_post_meta($consultation->ID, '_arta_approval_status', true);
                        $appointment_date = get_post_meta($consultation->ID, '_arta_appointment_date', true);
                        $appointment_time = get_post_meta($consultation->ID, '_arta_appointment_time', true);
                        $doctor_id = get_post_meta($consultation->ID, '_arta_doctor_id', true);
                        $program_id = get_post_meta($consultation->ID, '_arta_program_id', true);
                        $rejection_reason = get_post_meta($consultation->ID, '_arta_rejection_reason', true);
                        
                        $doctor = get_user_by('ID', $doctor_id);
                        $program = get_post($program_id);
                        
                        $status_labels = array(
                            'pending' => __('در انتظار بررسی', 'arta-consult-rx'),
                            'approved' => __('تایید شده', 'arta-consult-rx'),
                            'rejected' => __('رد شده', 'arta-consult-rx')
                        );
                        
                        $status_class = 'status-' . $approval_status;
                        $status_text = isset($status_labels[$approval_status]) ? $status_labels[$approval_status] : $approval_status;
                        ?>
                        
                        <div class="arta-request-item">
                            <div class="arta-request-header">
                                <div class="arta-request-info">
                                    <h4><?php echo sprintf(__('درخواست #%d', 'arta-consult-rx'), $consultation->ID); ?></h4>
                                    <div class="arta-request-meta">
                                        <span class="arta-request-date">
                                            <?php echo date_i18n('j F Y', strtotime($consultation->post_date)); ?>
                                        </span>
                                        <?php if ($appointment_date): ?>
                                            <span class="arta-appointment-date">
                                                <?php echo date_i18n('j F Y', strtotime($appointment_date)); ?>
                                                <?php if ($appointment_time): ?>
                                                    - <?php echo esc_html($appointment_time); ?>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="arta-request-status">
                                    <span class="arta-status-badge <?php echo esc_attr($status_class); ?>">
                                        <?php echo esc_html($status_text); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="arta-request-details">
                                <?php if ($doctor): ?>
                                    <div class="arta-detail-row">
                                        <strong><?php _e('پزشک:', 'arta-consult-rx'); ?></strong>
                                        <span><?php echo esc_html($doctor->display_name); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($program): ?>
                                    <div class="arta-detail-row">
                                        <strong><?php _e('برنامه:', 'arta-consult-rx'); ?></strong>
                                        <span><?php echo esc_html($program->post_title); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($approval_status === 'rejected' && $rejection_reason): ?>
                                    <div class="arta-rejection-reason">
                                        <strong><?php _e('دلیل رد:', 'arta-consult-rx'); ?></strong>
                                        <p><?php echo esc_html($rejection_reason); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($approval_status === 'approved'): ?>
                                <div class="arta-request-actions">
                                    <a href="<?php echo get_permalink($program_id); ?>" class="arta-btn arta-btn-secondary">
                                        <?php _e('مشاهده برنامه', 'arta-consult-rx'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        if (is_account_page()) {
            wp_enqueue_style(
                'arta-my-account',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/my-account.css',
                array(),
                ARTA_CONSULT_RX_VERSION
            );
        }
    }
}
