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
        
        // Add endpoint - use wp_loaded to ensure WooCommerce is ready
        add_action('wp_loaded', array($this, 'add_my_requests_endpoint'));
        
        // Handle the endpoint content
        add_action('woocommerce_account_my-requests_endpoint', array($this, 'my_requests_content'));
        
        // Manual check for my-requests page
        add_action('template_redirect', array($this, 'check_my_requests_page'));
        
        // Flush rewrite rules if needed
        add_action('init', array($this, 'maybe_flush_rewrite_rules'));
        
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
        // Use WooCommerce method if available
        if (function_exists('WC') && WC()) {
            // Add to WooCommerce query vars
            add_filter('woocommerce_get_query_vars', array($this, 'add_my_requests_query_var'));
            
            // Get WooCommerce endpoints mask
            $wc_query = WC()->query;
            $mask = $wc_query->get_endpoints_mask();
            add_rewrite_endpoint('my-requests', $mask);
        } else {
            // Fallback to standard WordPress method
            add_rewrite_endpoint('my-requests', EP_PAGES);
        }
    }
    
    /**
     * Add my-requests to WooCommerce query vars
     */
    public function add_my_requests_query_var($vars) {
        $vars['my-requests'] = 'my-requests';
        return $vars;
    }

    /**
     * Maybe flush rewrite rules
     */
    public function maybe_flush_rewrite_rules() {
        if (get_option('arta_my_requests_flush_rewrite_rules')) {
            flush_rewrite_rules();
            delete_option('arta_my_requests_flush_rewrite_rules');
            // Force flush to ensure endpoints work
            if (function_exists('WC')) {
                flush_rewrite_rules(false);
            }
        }
    }
    
    /**
     * Force flush rewrite rules on activation
     */
    public function force_flush_rewrite_rules() {
        flush_rewrite_rules();
    }
    
    /**
     * Check if we're on my-requests page and handle it manually if needed
     */
    public function check_my_requests_page() {
        global $wp_query;
        
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = $_SERVER['REQUEST_URI'];
            if (strpos($request_uri, '/my-account/my-requests') !== false) {
                // Force set the query var and fix 404
                $wp_query->query_vars['my-requests'] = '';
                $wp_query->is_404 = false;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                
                // Set the queried object to My Account page
                $myaccount_page_id = wc_get_page_id('myaccount');
                if ($myaccount_page_id) {
                    $wp_query->queried_object = get_post($myaccount_page_id);
                    $wp_query->queried_object_id = $myaccount_page_id;
                }
                
                // Force trigger the WooCommerce endpoint action
                // add_action('woocommerce_account_content', array($this, 'my_requests_content'));
            }
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        if (is_account_page()) {
            wp_enqueue_style('arta-my-account-css', ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/my-account.css', array(), ARTA_CONSULT_RX_VERSION);
            wp_enqueue_script('jquery');
        }
    }

    /**
     * My Requests content
     */
    public function my_requests_content() {
        error_log('Arta My Account: my_requests_content called');
        
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
        
        error_log('Arta My Account: Found ' . count($consultations) . ' consultations for user ' . $user_id);

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
                                    <h4><?php printf(__('درخواست #%s', 'arta-consult-rx'), $consultation->ID); ?></h4>
                                    <div class="arta-request-meta">
                                        <span class="arta-request-date">
                                            <?php _e('تاریخ درخواست:', 'arta-consult-rx'); ?> <?php echo date_i18n('j F Y - H:i', strtotime($consultation->post_date)); ?>
                                        </span>
                                        <?php if ($appointment_date && $appointment_time): ?>
                                            <span class="arta-appointment-date">
                                                <?php _e('تاریخ نوبت:', 'arta-consult-rx'); ?> <?php echo date_i18n('j F Y', strtotime($appointment_date)); ?> - <?php echo $appointment_time; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="arta-request-status">
                                    <span class="arta-status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                    <button class="arta-view-details-btn" type="button">
                                        <?php _e('مشاهده جزئیات', 'arta-consult-rx'); ?>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="arta-request-content">
                                <div class="arta-request-details">
                                    <?php if ($doctor): ?>
                                        <div class="arta-detail-row">
                                            <strong><?php _e('پزشک:', 'arta-consult-rx'); ?></strong>
                                            <span><?php echo $doctor->display_name; ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($program): ?>
                                        <div class="arta-detail-row">
                                            <strong><?php _e('برنامه:', 'arta-consult-rx'); ?></strong>
                                            <span><?php echo $program->post_title; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php
                                // Get additional consultation details
                                $full_name = get_post_meta($consultation->ID, '_arta_full_name', true);
                                $phone = get_post_meta($consultation->ID, '_arta_phone', true);
                                $email = get_post_meta($consultation->ID, '_arta_email', true);
                                $age = get_post_meta($consultation->ID, '_arta_age', true);
                                $height = get_post_meta($consultation->ID, '_arta_height', true);
                                $weight = get_post_meta($consultation->ID, '_arta_weight', true);
                                $chronic_diseases = get_post_meta($consultation->ID, '_arta_chronic_diseases', true);
                                $medications = get_post_meta($consultation->ID, '_arta_medications', true);
                                $medical_history = get_post_meta($consultation->ID, '_arta_medical_history', true);
                                $program_goal = get_post_meta($consultation->ID, '_arta_program_goal', true);
                                ?>
                                
                                <?php if ($full_name || $phone || $email || $age): ?>
                                    <div class="arta-additional-details">
                                        <h5><?php _e('اطلاعات شخصی', 'arta-consult-rx'); ?></h5>
                                        <div class="arta-detail-grid">
                                            <?php if ($full_name): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('نام کامل', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($full_name); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($phone): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('شماره تماس', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($phone); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($email): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('ایمیل', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($email); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($age): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('سن', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($age); ?> <?php _e('سال', 'arta-consult-rx'); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($height): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('قد', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($height); ?> <?php _e('سانتی‌متر', 'arta-consult-rx'); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($weight): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('وزن', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($weight); ?> <?php _e('کیلوگرم', 'arta-consult-rx'); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($chronic_diseases || $medications || $medical_history): ?>
                                    <div class="arta-additional-details">
                                        <h5><?php _e('اطلاعات پزشکی', 'arta-consult-rx'); ?></h5>
                                        <div class="arta-detail-grid">
                                            <?php if ($chronic_diseases): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('بیماری‌های مزمن', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($chronic_diseases); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($medications): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('داروهای مصرفی', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($medications); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($medical_history): ?>
                                                <div class="arta-detail-item">
                                                    <strong><?php _e('سابقه پزشکی', 'arta-consult-rx'); ?></strong>
                                                    <span><?php echo esc_html($medical_history); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($program_goal): ?>
                                    <div class="arta-additional-details">
                                        <h5><?php _e('هدف از برنامه', 'arta-consult-rx'); ?></h5>
                                        <p style="margin: 0; color: #666; line-height: 1.6;"><?php echo esc_html($program_goal); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($approval_status === 'rejected' && $rejection_reason): ?>
                                    <div class="arta-rejection-reason">
                                        <strong><?php _e('دلیل رد درخواست:', 'arta-consult-rx'); ?></strong>
                                        <p><?php echo esc_html($rejection_reason); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            console.log('Arta My Account Script Loaded');
            console.log('Found accordion buttons:', $('.arta-view-details-btn').length);
            
            // Accordion functionality
            $('.arta-view-details-btn').click(function(e) {
                console.log('Accordion button clicked');
                e.preventDefault();
                e.stopPropagation();
                
                var $btn = $(this);
                var $header = $btn.closest('.arta-request-header');
                var $content = $header.next('.arta-request-content');
                var $item = $header.closest('.arta-request-item');
                
                console.log('Button:', $btn);
                console.log('Header:', $header);
                console.log('Content:', $content);
                console.log('Item:', $item);
                
                // Toggle active class
                $header.toggleClass('active');
                $content.toggleClass('active');
                $btn.toggleClass('active');
                
                console.log('Content has active class:', $content.hasClass('active'));
                
                // Update button text
                if ($content.hasClass('active')) {
                    $btn.text('<?php _e('بستن جزئیات', 'arta-consult-rx'); ?>');
                    console.log('Button text changed to: بستن جزئیات');
                } else {
                    $btn.text('<?php _e('مشاهده جزئیات', 'arta-consult-rx'); ?>');
                    console.log('Button text changed to: مشاهده جزئیات');
                }
                
                // Close other accordions (optional - remove if you want multiple open)
                $('.arta-request-item').not($item).find('.arta-request-header').removeClass('active');
                $('.arta-request-item').not($item).find('.arta-request-content').removeClass('active');
                $('.arta-request-item').not($item).find('.arta-view-details-btn').removeClass('active').text('<?php _e('مشاهده جزئیات', 'arta-consult-rx'); ?>');
                
                console.log('Accordion toggled successfully');
            });
        });
        </script>
        <?php
    }
}