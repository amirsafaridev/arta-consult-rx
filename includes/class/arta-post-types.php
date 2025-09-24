<?php
/**
 * Post Types Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle custom post types
 */
class Arta_Post_Types {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_post_types'), 5);
        add_filter('template_include', array($this, 'template_loader'));
        add_action('init', array($this, 'flush_rewrite_rules_if_needed'));
        add_action('template_redirect', array($this, 'handle_arta_program_redirect'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        if (!is_blog_installed()) {
            return;
        }

        $this->register_arta_program_post_type();
        $this->register_arta_consultation_post_type();
    }

    /**
     * Register arta_program post type
     */
    private function register_arta_program_post_type() {
        $labels = array(
            'name'                  => _x('برنامه‌ها', 'Post type general name', 'arta-consult-rx'),
            'singular_name'         => _x('برنامه', 'Post type singular name', 'arta-consult-rx'),
            'menu_name'             => _x('برنامه‌ها', 'Admin Menu text', 'arta-consult-rx'),
            'name_admin_bar'        => _x('برنامه', 'Add New on Toolbar', 'arta-consult-rx'),
            'add_new'               => __('افزودن جدید', 'arta-consult-rx'),
            'add_new_item'          => __('افزودن برنامه جدید', 'arta-consult-rx'),
            'new_item'              => __('برنامه جدید', 'arta-consult-rx'),
            'edit_item'             => __('ویرایش برنامه', 'arta-consult-rx'),
            'view_item'             => __('مشاهده برنامه', 'arta-consult-rx'),
            'all_items'             => __('همه برنامه‌ها', 'arta-consult-rx'),
            'search_items'          => __('جستجوی برنامه‌ها', 'arta-consult-rx'),
            'parent_item_colon'     => __('برنامه والد:', 'arta-consult-rx'),
            'not_found'             => __('برنامه‌ای یافت نشد.', 'arta-consult-rx'),
            'not_found_in_trash'    => __('هیچ برنامه‌ای در سطل زباله یافت نشد.', 'arta-consult-rx'),
            'featured_image'        => _x('تصویر شاخص برنامه', 'Overrides the "Featured Image" phrase', 'arta-consult-rx'),
            'set_featured_image'    => _x('تنظیم تصویر شاخص', 'Overrides the "Set featured image" phrase', 'arta-consult-rx'),
            'remove_featured_image' => _x('حذف تصویر شاخص', 'Overrides the "Remove featured image" phrase', 'arta-consult-rx'),
            'use_featured_image'    => _x('استفاده به عنوان تصویر شاخص', 'Overrides the "Use as featured image" phrase', 'arta-consult-rx'),
            'archives'              => _x('آرشیو برنامه‌ها', 'The post type archive label', 'arta-consult-rx'),
            'insert_into_item'      => _x('درج در برنامه', 'Overrides the "Insert into post" phrase', 'arta-consult-rx'),
            'uploaded_to_this_item' => _x('آپلود شده در این برنامه', 'Overrides the "Uploaded to this post" phrase', 'arta-consult-rx'),
            'filter_items_list'     => _x('فیلتر لیست برنامه‌ها', 'Screen reader text for the filter links', 'arta-consult-rx'),
            'items_list_navigation' => _x('ناوبری لیست برنامه‌ها', 'Screen reader text for the pagination', 'arta-consult-rx'),
            'items_list'            => _x('لیست برنامه‌ها', 'Screen reader text for the items list', 'arta-consult-rx'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'program', 'with_front' => false),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_nav_menus'  => true,
        );

        register_post_type('arta_program', $args);
    }

    /**
     * Register arta_consultation post type
     */
    private function register_arta_consultation_post_type() {
        $labels = array(
            'name'                  => _x('درخواست‌های مشاوره', 'Post type general name', 'arta-consult-rx'),
            'singular_name'         => _x('درخواست مشاوره', 'Post type singular name', 'arta-consult-rx'),
            'menu_name'             => _x('درخواست‌های مشاوره', 'Admin Menu text', 'arta-consult-rx'),
            'name_admin_bar'        => _x('درخواست مشاوره', 'Add New on Toolbar', 'arta-consult-rx'),
            'add_new'               => __('افزودن جدید', 'arta-consult-rx'),
            'add_new_item'          => __('افزودن درخواست جدید', 'arta-consult-rx'),
            'new_item'              => __('درخواست جدید', 'arta-consult-rx'),
            'edit_item'             => __('ویرایش درخواست', 'arta-consult-rx'),
            'view_item'             => __('مشاهده درخواست', 'arta-consult-rx'),
            'all_items'             => __('همه درخواست‌ها', 'arta-consult-rx'),
            'search_items'          => __('جستجوی درخواست‌ها', 'arta-consult-rx'),
            'parent_item_colon'     => __('درخواست والد:', 'arta-consult-rx'),
            'not_found'             => __('درخواستی یافت نشد.', 'arta-consult-rx'),
            'not_found_in_trash'    => __('هیچ درخواستی در سطل زباله یافت نشد.', 'arta-consult-rx'),
            'featured_image'        => _x('تصویر شاخص درخواست', 'Overrides the "Featured Image" phrase', 'arta-consult-rx'),
            'set_featured_image'    => _x('تنظیم تصویر شاخص', 'Overrides the "Set featured image" phrase', 'arta-consult-rx'),
            'remove_featured_image' => _x('حذف تصویر شاخص', 'Overrides the "Remove featured image" phrase', 'arta-consult-rx'),
            'use_featured_image'    => _x('استفاده به عنوان تصویر شاخص', 'Overrides the "Use as featured image" phrase', 'arta-consult-rx'),
            'archives'              => _x('آرشیو درخواست‌ها', 'The post type archive label', 'arta-consult-rx'),
            'insert_into_item'      => _x('درج در درخواست', 'Overrides the "Insert into post" phrase', 'arta-consult-rx'),
            'uploaded_to_this_item' => _x('آپلود شده در این درخواست', 'Overrides the "Uploaded to this post" phrase', 'arta-consult-rx'),
            'filter_items_list'     => _x('فیلتر لیست درخواست‌ها', 'Screen reader text for the filter links', 'arta-consult-rx'),
            'items_list_navigation' => _x('ناوبری لیست درخواست‌ها', 'Screen reader text for the pagination', 'arta-consult-rx'),
            'items_list'            => _x('لیست درخواست‌ها', 'Screen reader text for the items list', 'arta-consult-rx'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'arta-consult-rx',
            'show_in_rest'       => false,
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 6,
            'menu_icon'          => 'dashicons-calendar-alt',
                'supports'           => array('title'),
            'show_in_nav_menus'  => false,
        );

        register_post_type('arta_consultation', $args);
        
        // Add meta box for consultation
        add_action('add_meta_boxes', array($this, 'add_consultation_meta_boxes'));
        add_action('save_post', array($this, 'save_consultation_meta_boxes'));
        
        // Add custom columns to consultation table
        add_filter('manage_arta_consultation_posts_columns', array($this, 'add_consultation_columns'));
        add_action('manage_arta_consultation_posts_custom_column', array($this, 'populate_consultation_columns'), 10, 2);
        add_filter('manage_edit-arta_consultation_sortable_columns', array($this, 'make_consultation_columns_sortable'));
        
        // Add filters
        add_action('restrict_manage_posts', array($this, 'add_consultation_filters'));
        add_filter('parse_query', array($this, 'filter_consultation_queries'));
        
        // Add AJAX handlers for approval/rejection
        add_action('wp_ajax_arta_approve_consultation', array($this, 'handle_consultation_approval'));
        add_action('wp_ajax_arta_reject_consultation', array($this, 'handle_consultation_rejection'));
        
        // Add admin footer script for consultation list
        add_action('admin_footer-edit.php', array($this, 'add_consultation_list_scripts'));
        
        // Add admin footer script for consultation list page
        add_action('admin_footer', array($this, 'add_consultation_list_scripts'));
    }

    /**
     * Template loader
     */
    public function template_loader($template) {
        global $wp_query;
        
        // Check if this is a 404 and we're looking for an arta_program
        if (is_404() && isset($wp_query->query_vars['name'])) {
            $post_name = $wp_query->query_vars['name'];
            
            // Try to find the post by name
            $post = get_page_by_path($post_name, OBJECT, 'arta_program');
            
            if ($post) {
                // Set up the global post
                $GLOBALS['post'] = $post;
                setup_postdata($post);
                
                // Load our custom template
                $plugin_template = ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/single-arta_program.php';
                
                if (file_exists($plugin_template)) {
                    return $plugin_template;
                }
            }
        }
        
        // Regular template loading for non-404 cases
        if (is_singular('arta_program')) {
            $plugin_template = ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/single-arta_program.php';
            
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        
        return $template;
    }

    /**
     * Handle arta_program redirect for 404 cases
     */
    public function handle_arta_program_redirect() {
        global $wp_query;
        
        if (is_404() && isset($wp_query->query_vars['name'])) {
            $post_name = $wp_query->query_vars['name'];
            
            // Try to find the post by name
            $post = get_page_by_path($post_name, OBJECT, 'arta_program');
            
            if ($post) {
                // Set up the global post
                $GLOBALS['post'] = $post;
                setup_postdata($post);
                
                // Clear 404 status
                $wp_query->is_404 = false;
                $wp_query->is_single = true;
                $wp_query->is_singular = true;
                $wp_query->queried_object = $post;
                $wp_query->queried_object_id = $post->ID;
                $wp_query->posts = array($post);
                $wp_query->post_count = 1;
                $wp_query->found_posts = 1;
                $wp_query->max_num_pages = 1;
                
                // Load our custom template
                $plugin_template = ARTA_CONSULT_RX_PLUGIN_DIR . 'templates/single-arta_program.php';
                
                if (file_exists($plugin_template)) {
                    include $plugin_template;
                    exit;
                }
            }
        }
    }

    /**
     * Flush rewrite rules if needed
     */
    public function flush_rewrite_rules_if_needed() {
        if (get_option('arta_consult_rx_flush_rewrite_rules')) {
            flush_rewrite_rules();
            delete_option('arta_consult_rx_flush_rewrite_rules');
        }
    }

    /**
     * Add meta boxes for consultation
     */
    public function add_consultation_meta_boxes() {
        add_meta_box(
            'arta_consultation_details',
            __('جزئیات درخواست مشاوره', 'arta-consult-rx'),
            array($this, 'render_consultation_meta_box'),
            'arta_consultation',
            'normal',
            'high'
        );
    }

    /**
     * Render consultation meta box
     */
    public function render_consultation_meta_box($post) {
        // Get meta values
        $appointment_id = get_post_meta($post->ID, '_arta_appointment_id', true);
        $doctor_id = get_post_meta($post->ID, '_arta_doctor_id', true);
        $program_id = get_post_meta($post->ID, '_arta_program_id', true);
        $full_name = get_post_meta($post->ID, '_arta_full_name', true);
        $gender = get_post_meta($post->ID, '_arta_gender', true);
        $birth_date = get_post_meta($post->ID, '_arta_birth_date', true);
        $height = get_post_meta($post->ID, '_arta_height', true);
        $weight = get_post_meta($post->ID, '_arta_weight', true);
        $email = get_post_meta($post->ID, '_arta_email', true);
        $phone = get_post_meta($post->ID, '_arta_phone', true);
        $chronic_diseases = get_post_meta($post->ID, '_arta_chronic_diseases', true);
        $medications = get_post_meta($post->ID, '_arta_medications', true);
        $medical_history = get_post_meta($post->ID, '_arta_medical_history', true);
        $program_goal = get_post_meta($post->ID, '_arta_program_goal', true);
        $medical_consultation = get_post_meta($post->ID, '_arta_medical_consultation', true);
        $appointment_date = get_post_meta($post->ID, '_arta_appointment_date', true);

        // Get appointment details
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';
        $appointment = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE id = %d",
            $appointment_id
        ));

        // Get doctor info
        $doctor = get_user_by('ID', $doctor_id);
        $doctor_name = $doctor ? $doctor->display_name : __('نامشخص', 'arta-consult-rx');

        // Get program info
        $program = get_post($program_id);
        $program_title = $program ? $program->post_title : __('نامشخص', 'arta-consult-rx');

        wp_nonce_field('arta_consultation_meta_box', 'arta_consultation_meta_box_nonce');
        ?>
        <div class="arta-consultation-meta-box">
            <style>
                .arta-consultation-meta-box {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }
                .arta-meta-section {
                    background: #f8f9fa;
                    border: 1px solid #e9ecef;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .arta-meta-section h4 {
                    margin: 0 0 15px 0;
                    color: #495057;
                    font-size: 16px;
                    font-weight: 600;
                    border-bottom: 2px solid #dee2e6;
                    padding-bottom: 8px;
                }
                .arta-meta-row {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    margin-bottom: 15px;
                }
                .arta-meta-row.single {
                    grid-template-columns: 1fr;
                }
                .arta-meta-field {
                    margin-bottom: 15px;
                }
                .arta-meta-field label {
                    display: block;
                    font-weight: 500;
                    margin-bottom: 5px;
                    color: #495057;
                }
                .arta-meta-field input,
                .arta-meta-field select,
                .arta-meta-field textarea {
                    width: 100%;
                    padding: 8px 12px;
                    border: 1px solid #ced4da;
                    border-radius: 4px;
                    font-size: 14px;
                }
                .arta-meta-field textarea {
                    resize: vertical;
                    min-height: 80px;
                }
                .arta-readonly {
                    background: #e9ecef;
                    color: #6c757d;
                }
                @media (max-width: 768px) {
                    .arta-meta-row {
                        grid-template-columns: 1fr;
                    }
                }
            </style>

            <!-- Appointment Information -->
            <div class="arta-meta-section">
                <h4>📅 اطلاعات نوبت</h4>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label>تاریخ نوبت:</label>
                        <input type="date" name="arta_appointment_date" value="<?php echo esc_attr($appointment_date); ?>" class="arta-readonly" readonly>
                    </div>
                    <div class="arta-meta-field">
                        <label>ساعت نوبت:</label>
                        <input type="text" value="<?php echo $appointment ? esc_html($appointment->appointment_time) : ''; ?>" class="arta-readonly" readonly>
                    </div>
                </div>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label>پزشک:</label>
                        <input type="text" value="<?php echo esc_html($doctor_name); ?>" class="arta-readonly" readonly>
                    </div>
                    <div class="arta-meta-field">
                        <label>برنامه:</label>
                        <input type="text" value="<?php echo esc_html($program_title); ?>" class="arta-readonly" readonly>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="arta-meta-section">
                <h4>👤 اطلاعات شخصی</h4>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label>نام و نام خانوادگی:</label>
                        <input type="text" name="arta_full_name" value="<?php echo esc_attr($full_name); ?>">
                    </div>
                    <div class="arta-meta-field">
                        <label>جنسیت:</label>
                        <select name="arta_gender">
                            <option value="male" <?php selected($gender, 'male'); ?>>مرد</option>
                            <option value="female" <?php selected($gender, 'female'); ?>>زن</option>
                        </select>
                    </div>
                </div>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label>تاریخ تولد:</label>
                        <input type="date" name="arta_birth_date" value="<?php echo esc_attr($birth_date); ?>">
                    </div>
                    <div class="arta-meta-field">
                        <label>قد (سانتی‌متر):</label>
                        <input type="number" name="arta_height" value="<?php echo esc_attr($height); ?>" min="100" max="250">
                    </div>
                </div>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label>وزن (کیلوگرم):</label>
                        <input type="number" name="arta_weight" value="<?php echo esc_attr($weight); ?>" min="30" max="200">
                    </div>
                    <div class="arta-meta-field">
                        <label>تأیید مشاوره پزشکی:</label>
                        <select name="arta_medical_consultation">
                            <option value="0" <?php selected($medical_consultation, '0'); ?>>خیر</option>
                            <option value="1" <?php selected($medical_consultation, '1'); ?>>بله</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="arta-meta-section">
                <h4>📞 اطلاعات تماس</h4>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label>ایمیل:</label>
                        <input type="email" name="arta_email" value="<?php echo esc_attr($email); ?>">
                    </div>
                    <div class="arta-meta-field">
                        <label>شماره تماس:</label>
                        <input type="tel" name="arta_phone" value="<?php echo esc_attr($phone); ?>">
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="arta-meta-section">
                <h4>🏥 اطلاعات پزشکی</h4>
                <div class="arta-meta-field">
                    <label>بیماری‌های مزمن:</label>
                    <textarea name="arta_chronic_diseases"><?php echo esc_textarea($chronic_diseases); ?></textarea>
                </div>
                <div class="arta-meta-field">
                    <label>داروهای مصرفی:</label>
                    <textarea name="arta_medications"><?php echo esc_textarea($medications); ?></textarea>
                </div>
                <div class="arta-meta-field">
                    <label>سوابق درمانی:</label>
                    <textarea name="arta_medical_history"><?php echo esc_textarea($medical_history); ?></textarea>
                </div>
                <div class="arta-meta-field">
                    <label>هدف از برنامه:</label>
                    <textarea name="arta_program_goal"><?php echo esc_textarea($program_goal); ?></textarea>
                </div>
            </div>

            <!-- Approval Section -->
            <div class="arta-meta-section">
                <h4>✅ تایید/رد درخواست</h4>
                <?php
                $approval_status = get_post_meta($post->ID, '_arta_approval_status', true);
                if (!$approval_status) {
                    $approval_status = 'pending';
                }
                
                $rejection_reason = get_post_meta($post->ID, '_arta_rejection_reason', true);
                ?>
                
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label>وضعیت تایید:</label>
                        <select name="arta_approval_status">
                            <option value="pending" <?php selected($approval_status, 'pending'); ?>>در انتظار بررسی</option>
                            <option value="approved" <?php selected($approval_status, 'approved'); ?>>تایید شده</option>
                            <option value="rejected" <?php selected($approval_status, 'rejected'); ?>>رد شده</option>
                        </select>
                    </div>
                </div>
                
                <?php if ($approval_status === 'rejected' && $rejection_reason): ?>
                <div class="arta-meta-field">
                    <label>دلیل رد:</label>
                    <textarea name="arta_rejection_reason" rows="3" readonly><?php echo esc_textarea($rejection_reason); ?></textarea>
                </div>
                <?php endif; ?>
                
                <div class="arta-approval-actions" style="margin-top: 15px;">
                    <a href="#" class="arta-btn-approve-inline" data-post-id="<?php echo $post->ID; ?>" style="background: #4caf50; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-left: 10px; text-decoration: none; display: inline-block;">تایید درخواست</a>
                    <a href="#" class="arta-btn-reject-inline" data-post-id="<?php echo $post->ID; ?>" style="background: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">رد درخواست</a>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div id="arta-rejection-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%;">
                <h3 style="margin-top: 0;">رد درخواست</h3>
                <p>لطفاً دلیل رد درخواست را وارد کنید:</p>
                <textarea id="rejection-reason" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0;" placeholder="دلیل رد درخواست (اختیاری)"></textarea>
                <div style="text-align: left; margin-top: 20px;">
                    <button id="confirm-rejection" style="background: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-left: 10px;">تایید رد</button>
                    <button id="cancel-rejection" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">لغو</button>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Inline approval buttons
            $(document).on('click', '.arta-btn-approve-inline, .arta-btn-approve', function(e) {
                e.preventDefault();
                var postId = $(this).data('post-id');
                if (confirm('آیا مطمئن هستید که می‌خواهید این درخواست را تایید کنید؟')) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'arta_approve_consultation',
                            post_id: postId,
                            nonce: '<?php echo wp_create_nonce('arta_consultation_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.data.message);
                                location.reload();
                            } else {
                                alert(response.data.message || 'خطا در تایید درخواست');
                            }
                        },
                        error: function() {
                            alert('خطا در ارتباط با سرور');
                        }
                    });
                }
            });

            // Inline rejection buttons
            $(document).on('click', '.arta-btn-reject-inline, .arta-btn-reject', function(e) {
                e.preventDefault();
                var postId = $(this).data('post-id');
                $('#arta-rejection-modal').show();
                $('#confirm-rejection').data('post-id', postId);
            });

            // Confirm rejection
            $('#confirm-rejection').on('click', function() {
                var postId = $(this).data('post-id');
                var reason = $('#rejection-reason').val();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'arta_reject_consultation',
                        post_id: postId,
                        rejection_reason: reason,
                        nonce: '<?php echo wp_create_nonce('arta_consultation_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.data.message);
                            location.reload();
                        } else {
                            alert(response.data.message || 'خطا در رد درخواست');
                        }
                    },
                    error: function() {
                        alert('خطا در ارتباط با سرور');
                    }
                });
            });

            // Cancel rejection
            $('#cancel-rejection').on('click', function() {
                $('#arta-rejection-modal').hide();
                $('#rejection-reason').val('');
            });
        });
        </script>
        <?php
    }

    /**
     * Save consultation meta boxes
     */
    public function save_consultation_meta_boxes($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['arta_consultation_meta_box_nonce'])) {
            return;
        }

        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['arta_consultation_meta_box_nonce'], 'arta_consultation_meta_box')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if (isset($_POST['post_type']) && 'arta_consultation' == $_POST['post_type']) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        // Save meta data
        $fields = array(
            'arta_full_name',
            'arta_gender',
            'arta_birth_date',
            'arta_height',
            'arta_weight',
            'arta_email',
            'arta_phone',
            'arta_chronic_diseases',
            'arta_medications',
            'arta_medical_history',
            'arta_program_goal',
            'arta_medical_consultation',
            'arta_appointment_date'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Handle approval status change
        if (isset($_POST['arta_approval_status'])) {
            $old_status = get_post_meta($post_id, '_arta_approval_status', true);
            $new_status = sanitize_text_field($_POST['arta_approval_status']);
            
            // Update approval status
            update_post_meta($post_id, '_arta_approval_status', $new_status);
            
            // Update appointment status based on approval status change
            $appointment_id = get_post_meta($post_id, '_arta_appointment_id', true);
            if ($appointment_id) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'arta_appointments';
                
                if ($new_status === 'approved') {
                    // If approved, set appointment to booked
                    $wpdb->update(
                        $table_name,
                        array('status' => 'booked'),
                        array('id' => $appointment_id),
                        array('%s'),
                        array('%d')
                    );
                } elseif ($new_status === 'rejected') {
                    // If rejected, set appointment to available
                    $wpdb->update(
                        $table_name,
                        array('status' => 'available'),
                        array('id' => $appointment_id),
                        array('%s'),
                        array('%d')
                    );
                }
            }
        }
    }

    /**
     * Add custom columns to consultation table
     */
    public function add_consultation_columns($columns) {
        // Remove default columns we don't need
        unset($columns['date']);
        
        // Add our custom columns
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['request_id'] = __('شماره درخواست', 'arta-consult-rx');
        $new_columns['patient_name'] = __('نام بیمار', 'arta-consult-rx');
        $new_columns['appointment_date'] = __('تاریخ نوبت', 'arta-consult-rx');
        $new_columns['appointment_time'] = __('ساعت نوبت', 'arta-consult-rx');
        $new_columns['doctor'] = __('پزشک', 'arta-consult-rx');
        $new_columns['program'] = __('برنامه', 'arta-consult-rx');
        $new_columns['status'] = __('وضعیت', 'arta-consult-rx');
        $new_columns['approval'] = __('تایید/رد', 'arta-consult-rx');
        $new_columns['date'] = __('تاریخ ثبت', 'arta-consult-rx');
        
        return $new_columns;
    }

    /**
     * Populate custom columns
     */
    public function populate_consultation_columns($column, $post_id) {
        switch ($column) {
            case 'request_id':
                echo '<strong>#' . $post_id . '</strong>';
                break;
                
            case 'patient_name':
                $full_name = get_post_meta($post_id, '_arta_full_name', true);
                echo $full_name ? esc_html($full_name) : '—';
                break;
                
            case 'appointment_date':
                $appointment_date = get_post_meta($post_id, '_arta_appointment_date', true);
                if ($appointment_date) {
                    echo esc_html(date_i18n('j F Y', strtotime($appointment_date)));
                } else {
                    echo '—';
                }
                break;
                
            case 'appointment_time':
                $appointment_id = get_post_meta($post_id, '_arta_appointment_id', true);
                if ($appointment_id) {
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'arta_appointments';
                    $appointment = $wpdb->get_row($wpdb->prepare(
                        "SELECT appointment_time FROM {$table_name} WHERE id = %d",
                        $appointment_id
                    ));
                    if ($appointment) {
                        echo esc_html($appointment->appointment_time);
                    } else {
                        echo '—';
                    }
                } else {
                    echo '—';
                }
                break;
                
            case 'doctor':
                $doctor_id = get_post_meta($post_id, '_arta_doctor_id', true);
                if ($doctor_id) {
                    $doctor = get_user_by('ID', $doctor_id);
                    if ($doctor) {
                        echo esc_html($doctor->display_name);
                    } else {
                        echo '—';
                    }
                } else {
                    echo '—';
                }
                break;
                
            case 'program':
                $program_id = get_post_meta($post_id, '_arta_program_id', true);
                if ($program_id) {
                    $program = get_post($program_id);
                    if ($program) {
                        echo '<a href="' . get_edit_post_link($program_id) . '">' . esc_html($program->post_title) . '</a>';
                    } else {
                        echo '—';
                    }
                } else {
                    echo '—';
                }
                break;
                
            case 'status':
                $appointment_id = get_post_meta($post_id, '_arta_appointment_id', true);
                if ($appointment_id) {
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'arta_appointments';
                    $appointment = $wpdb->get_row($wpdb->prepare(
                        "SELECT status FROM {$table_name} WHERE id = %d",
                        $appointment_id
                    ));
                    if ($appointment) {
                        $status_labels = array(
                            'available' => __('آزاد', 'arta-consult-rx'),
                            'booked' => __('رزرو شده', 'arta-consult-rx'),
                            'completed' => __('تکمیل شده', 'arta-consult-rx'),
                            'cancelled' => __('لغو شده', 'arta-consult-rx')
                        );
                        $status_class = 'status-' . $appointment->status;
                        $status_text = isset($status_labels[$appointment->status]) ? $status_labels[$appointment->status] : $appointment->status;
                        echo '<span class="arta-status ' . esc_attr($status_class) . '">' . esc_html($status_text) . '</span>';
                    } else {
                        echo '—';
                    }
                } else {
                    echo '—';
                }
                break;
                
            case 'approval':
                $approval_status = get_post_meta($post_id, '_arta_approval_status', true);
                if (!$approval_status) {
                    $approval_status = 'pending';
                }
                
                $approval_labels = array(
                    'pending' => __('در انتظار بررسی', 'arta-consult-rx'),
                    'approved' => __('تایید شده', 'arta-consult-rx'),
                    'rejected' => __('رد شده', 'arta-consult-rx')
                );
                
                $approval_class = 'approval-' . $approval_status;
                $approval_text = isset($approval_labels[$approval_status]) ? $approval_labels[$approval_status] : $approval_status;
                
                echo '<span class="arta-approval-status ' . esc_attr($approval_class) . '">' . esc_html($approval_text) . '</span>';
                
                if ($approval_status === 'pending') {
                    echo '<div class="arta-approval-actions" style="margin-top: 5px;">';
                    echo '<a href="#" class="arta-btn-approve" data-post-id="' . $post_id . '" style="background: #4caf50; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; margin-left: 5px; text-decoration: none; display: inline-block;">تایید</a>';
                    echo '<a href="#" class="arta-btn-reject" data-post-id="' . $post_id . '" style="background: #f44336; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; text-decoration: none; display: inline-block;">رد</a>';
                    echo '</div>';
                }
                break;
        }
    }

    /**
     * Make columns sortable
     */
    public function make_consultation_columns_sortable($columns) {
        $columns['patient_name'] = 'patient_name';
        $columns['appointment_date'] = 'appointment_date';
        $columns['doctor'] = 'doctor';
        $columns['program'] = 'program';
        $columns['status'] = 'status';
        
        return $columns;
    }

    /**
     * Add filters to consultation table
     */
    public function add_consultation_filters() {
        global $typenow;
        
        if ($typenow == 'arta_consultation') {
            // Doctor filter
            $doctors = get_users(array('role' => 'arta_doctor'));
            echo '<select name="filter_doctor">';
            echo '<option value="">همه پزشکان</option>';
            foreach ($doctors as $doctor) {
                $selected = isset($_GET['filter_doctor']) ? selected($_GET['filter_doctor'], $doctor->ID, false) : '';
                echo '<option value="' . $doctor->ID . '" ' . $selected . '>' . esc_html($doctor->display_name) . '</option>';
            }
            echo '</select>';

            // Program filter
            $programs = get_posts(array('post_type' => 'arta_program', 'numberposts' => -1));
            echo '<select name="filter_program">';
            echo '<option value="">همه برنامه‌ها</option>';
            foreach ($programs as $program) {
                $selected = isset($_GET['filter_program']) ? selected($_GET['filter_program'], $program->ID, false) : '';
                echo '<option value="' . $program->ID . '" ' . $selected . '>' . esc_html($program->post_title) . '</option>';
            }
            echo '</select>';

            // Approval status filter
            echo '<select name="filter_approval">';
            $approval_options = array(
                '' => 'همه وضعیت‌ها',
                'pending' => 'در انتظار بررسی',
                'approved' => 'تایید شده',
                'rejected' => 'رد شده'
            );
            foreach ($approval_options as $value => $label) {
                $selected = isset($_GET['filter_approval']) ? selected($_GET['filter_approval'], $value, false) : '';
                echo '<option value="' . $value . '" ' . $selected . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
        }
    }

    /**
     * Filter consultation queries
     */
    public function filter_consultation_queries($query) {
        global $pagenow, $typenow;

        if ($pagenow == 'edit.php' && $typenow == 'arta_consultation') {
            $meta_query = array();

            if (isset($_GET['filter_doctor']) && $_GET['filter_doctor'] != '') {
                $meta_query[] = array(
                    'key' => '_arta_doctor_id',
                    'value' => $_GET['filter_doctor'],
                    'compare' => '='
                );
            }

            if (isset($_GET['filter_program']) && $_GET['filter_program'] != '') {
                $meta_query[] = array(
                    'key' => '_arta_program_id',
                    'value' => $_GET['filter_program'],
                    'compare' => '='
                );
            }

            if (isset($_GET['filter_approval']) && $_GET['filter_approval'] != '') {
                $meta_query[] = array(
                    'key' => '_arta_approval_status',
                    'value' => $_GET['filter_approval'],
                    'compare' => '='
                );
            }

            if (!empty($meta_query)) {
                $query->set('meta_query', $meta_query);
            }
        }
    }

    /**
     * Handle consultation approval
     */
    public function handle_consultation_approval() {
        check_ajax_referer('arta_consultation_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('شما مجوز لازم را ندارید', 'arta-consult-rx'));
        }

        $post_id = intval($_POST['post_id']);
        
        // Update approval status
        update_post_meta($post_id, '_arta_approval_status', 'approved');
        
        // Update appointment status to booked
        $appointment_id = get_post_meta($post_id, '_arta_appointment_id', true);
        if ($appointment_id) {
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

        wp_send_json_success(array('message' => __('درخواست با موفقیت تایید شد', 'arta-consult-rx')));
    }

    /**
     * Handle consultation rejection
     */
    public function handle_consultation_rejection() {
        check_ajax_referer('arta_consultation_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('شما مجوز لازم را ندارید', 'arta-consult-rx'));
        }

        $post_id = intval($_POST['post_id']);
        $rejection_reason = sanitize_textarea_field($_POST['rejection_reason']);
        
        // Update approval status
        update_post_meta($post_id, '_arta_approval_status', 'rejected');
        update_post_meta($post_id, '_arta_rejection_reason', $rejection_reason);
        
        // Update appointment status back to available
        $appointment_id = get_post_meta($post_id, '_arta_appointment_id', true);
        if ($appointment_id) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'arta_appointments';
            $wpdb->update(
                $table_name,
                array('status' => 'available'),
                array('id' => $appointment_id),
                array('%s'),
                array('%d')
            );
        }

        wp_send_json_success(array('message' => __('درخواست رد شد و نوبت آزاد شد', 'arta-consult-rx')));
    }

    /**
     * Add scripts to consultation list page
     */
    public function add_consultation_list_scripts() {
        global $typenow;
        
        if ($typenow == 'arta_consultation') {
            ?>
            <!-- Rejection Modal -->
            <div id="arta-rejection-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%;">
                    <h3 style="margin-top: 0;">رد درخواست</h3>
                    <p>لطفاً دلیل رد درخواست را وارد کنید:</p>
                    <textarea id="rejection-reason" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0;" placeholder="دلیل رد درخواست (اختیاری)"></textarea>
                    <div style="text-align: left; margin-top: 20px;">
                        <button id="confirm-rejection" style="background: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-left: 10px;">تایید رد</button>
                        <button id="cancel-rejection" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">لغو</button>
                    </div>
                </div>
            </div>

            <script>
            jQuery(document).ready(function($) {
                // Approval buttons
                $(document).on('click', '.arta-btn-approve', function(e) {
                    e.preventDefault();
                    var postId = $(this).data('post-id');
                    if (confirm('آیا مطمئن هستید که می‌خواهید این درخواست را تایید کنید؟')) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'arta_approve_consultation',
                                post_id: postId,
                                nonce: '<?php echo wp_create_nonce('arta_consultation_nonce'); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert(response.data.message);
                                    location.reload();
                                } else {
                                    alert(response.data.message || 'خطا در تایید درخواست');
                                }
                            },
                            error: function() {
                                alert('خطا در ارتباط با سرور');
                            }
                        });
                    }
                });

                // Rejection buttons
                $(document).on('click', '.arta-btn-reject', function(e) {
                    e.preventDefault();
                    var postId = $(this).data('post-id');
                    $('#arta-rejection-modal').show();
                    $('#confirm-rejection').data('post-id', postId);
                });

                // Confirm rejection
                $('#confirm-rejection').on('click', function() {
                    var $btn = $(this);
                    var postId = $btn.data('post-id');
                    var reason = $('#rejection-reason').val();
                    
                    // Add loading state
                    $btn.prop('disabled', true).text('در حال پردازش...');
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'arta_reject_consultation',
                            post_id: postId,
                            rejection_reason: reason,
                            nonce: '<?php echo wp_create_nonce('arta_consultation_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.data.message);
                                location.reload();
                            } else {
                                alert(response.data.message || 'خطا در رد درخواست');
                                // Remove loading state
                                $btn.prop('disabled', false).text('تایید رد');
                            }
                        },
                        error: function() {
                            alert('خطا در ارتباط با سرور');
                            // Remove loading state
                            $btn.prop('disabled', false).text('تایید رد');
                        }
                    });
                });

                // Cancel rejection
                $('#cancel-rejection').on('click', function() {
                    $('#arta-rejection-modal').hide();
                    $('#rejection-reason').val('');
                });
            });
            </script>
            <?php
        }
    }
}
