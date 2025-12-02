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
            'name'                  => __('برنامه‌ها', 'arta-consult-rx'),
            'singular_name'         => __('برنامه', 'arta-consult-rx'),
            'menu_name'             => __('برنامه‌ها', 'arta-consult-rx'),
            'name_admin_bar'        => __('برنامه', 'arta-consult-rx'),
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
            'featured_image'        => __('تصویر شاخص برنامه', 'arta-consult-rx'),
            'set_featured_image'    => __('تنظیم تصویر شاخص', 'arta-consult-rx'),
            'remove_featured_image' => __('حذف تصویر شاخص', 'arta-consult-rx'),
            'use_featured_image'    => __('استفاده به عنوان تصویر شاخص', 'arta-consult-rx'),
            'archives'              => __('آرشیو برنامه‌ها', 'arta-consult-rx'),
            'insert_into_item'      => __('درج در برنامه', 'arta-consult-rx'),
            'uploaded_to_this_item' => __('آپلود شده در این برنامه', 'arta-consult-rx'),
            'filter_items_list'     => __('فیلتر لیست برنامه‌ها', 'arta-consult-rx'),
            'items_list_navigation' => __('ناوبری لیست برنامه‌ها', 'arta-consult-rx'),
            'items_list'            => __('لیست برنامه‌ها', 'arta-consult-rx'),
        );

        $args = array(
            'label' => __('برنامه‌ها', 'arta-consult-rx'),
            'labels' => $labels,
            'description' => '',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'rest_base' => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'rest_namespace' => 'wp/v2',
            'has_archive' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'delete_with_user' => false,
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'hierarchical' => false,
            'can_export' => false,
            'rewrite' => array('slug' => 'program', 'with_front' => true),
            'query_var' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_graphql' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-calendar-alt',
        );

        register_post_type('arta_program', $args);
    }

    /**
     * Register arta_consultation post type
     */
    private function register_arta_consultation_post_type() {
        $labels = array(
            'name'                  => __('درخواست‌های مشاوره', 'arta-consult-rx'),
            'singular_name'         => __('درخواست مشاوره', 'arta-consult-rx'),
            'menu_name'             => __('درخواست‌های مشاوره', 'arta-consult-rx'),
            'name_admin_bar'        => __('درخواست مشاوره', 'arta-consult-rx'),
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
            'featured_image'        => __('تصویر شاخص درخواست', 'arta-consult-rx'),
            'set_featured_image'    => __('تنظیم تصویر شاخص', 'arta-consult-rx'),
            'remove_featured_image' => __('حذف تصویر شاخص', 'arta-consult-rx'),
            'use_featured_image'    => __('استفاده به عنوان تصویر شاخص', 'arta-consult-rx'),
            'archives'              => __('آرشیو درخواست‌ها', 'arta-consult-rx'),
            'insert_into_item'      => __('درج در درخواست', 'arta-consult-rx'),
            'uploaded_to_this_item' => __('آپلود شده در این درخواست', 'arta-consult-rx'),
            'filter_items_list'     => __('فیلتر لیست درخواست‌ها', 'arta-consult-rx'),
            'items_list_navigation' => __('ناوبری لیست درخواست‌ها', 'arta-consult-rx'),
            'items_list'            => __('لیست درخواست‌ها', 'arta-consult-rx'),
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
        
        // Add AJAX handlers for approval/rejection/completion
        add_action('wp_ajax_arta_approve_consultation', array($this, 'handle_consultation_approval'));
        add_action('wp_ajax_arta_reject_consultation', array($this, 'handle_consultation_rejection'));
        add_action('wp_ajax_arta_complete_consultation', array($this, 'handle_consultation_completion'));
        
        // Add admin footer script for consultation list
        add_action('admin_footer-edit.php', array($this, 'add_consultation_list_scripts'));
        
        // Add admin footer script for consultation list page
        add_action('admin_footer', array($this, 'add_consultation_list_scripts'));
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
        
        add_meta_box(
            'arta_consultation_approval',
            __('وضعیت درخواست', 'arta-consult-rx'),
            array($this, 'render_consultation_approval_meta_box'),
            'arta_consultation',
            'side',
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

        // Get programs info
        $programs = get_post_meta($post->ID, '_arta_programs', true);
        $program_titles = array();
        if (!empty($programs) && is_array($programs)) {
            foreach ($programs as $prog_id) {
                $program = get_post($prog_id);
                if ($program) {
                    $program_titles[] = $program->post_title;
                }
            }
        }
        
        // Get products info
        $products = get_post_meta($post->ID, '_arta_products', true);
        $product_titles = array();
        if (!empty($products) && is_array($products)) {
            foreach ($products as $prod_id) {
                $product = get_post($prod_id);
                if ($product) {
                    $product_titles[] = $product->post_title;
                }
            }
        }

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
                <h4>📅 <?php _e('اطلاعات نوبت', 'arta-consult-rx'); ?></h4>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label><?php _e('تاریخ نوبت:', 'arta-consult-rx'); ?></label>
                        <input type="date" name="arta_appointment_date" value="<?php echo esc_attr($appointment_date); ?>" class="arta-readonly" readonly>
                    </div>
                    <div class="arta-meta-field">
                        <label><?php _e('ساعت نوبت:', 'arta-consult-rx'); ?></label>
                        <input type="text" value="<?php echo $appointment ? esc_html($appointment->appointment_time) : ''; ?>" class="arta-readonly" readonly>
                    </div>
                </div>
                <div class="arta-meta-row single">
                    <div class="arta-meta-field">
                        <label><?php _e('پزشک:', 'arta-consult-rx'); ?></label>
                        <input type="text" value="<?php echo esc_html($doctor_name); ?>" class="arta-readonly" readonly>
                    </div>
                </div>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label><?php printf(__('برنامه‌ها (%d برنامه):', 'arta-consult-rx'), count($program_titles)); ?></label>
                        <textarea class="arta-readonly" readonly style="min-height: 60px;"><?php echo !empty($program_titles) ? implode("\n", $program_titles) : __('هیچ برنامه‌ای ثبت نشده', 'arta-consult-rx'); ?></textarea>
                    </div>
                    <div class="arta-meta-field">
                        <label><?php printf(__('محصولات (%d محصول):', 'arta-consult-rx'), count($product_titles)); ?></label>
                        <textarea class="arta-readonly" readonly style="min-height: 60px;"><?php echo !empty($product_titles) ? implode("\n", $product_titles) : __('هیچ محصولی ثبت نشده', 'arta-consult-rx'); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="arta-meta-section">
                <h4>👤 <?php _e('اطلاعات شخصی', 'arta-consult-rx'); ?></h4>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label><?php _e('نام و نام خانوادگی:', 'arta-consult-rx'); ?></label>
                        <input type="text" name="arta_full_name" value="<?php echo esc_attr($full_name); ?>">
                    </div>
                    <div class="arta-meta-field">
                        <label><?php _e('جنسیت:', 'arta-consult-rx'); ?></label>
                        <select name="arta_gender">
                            <option value="male" <?php selected($gender, 'male'); ?>><?php _e('مرد', 'arta-consult-rx'); ?></option>
                            <option value="female" <?php selected($gender, 'female'); ?>><?php _e('زن', 'arta-consult-rx'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label><?php _e('تاریخ تولد:', 'arta-consult-rx'); ?></label>
                        <input type="date" name="arta_birth_date" value="<?php echo esc_attr($birth_date); ?>">
                    </div>
                    <div class="arta-meta-field">
                        <label><?php _e('قد (سانتی‌متر):', 'arta-consult-rx'); ?></label>
                        <input type="number" name="arta_height" value="<?php echo esc_attr($height); ?>" min="100" max="250">
                    </div>
                </div>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label><?php _e('وزن (کیلوگرم):', 'arta-consult-rx'); ?></label>
                        <input type="number" name="arta_weight" value="<?php echo esc_attr($weight); ?>" min="30" max="200">
                    </div>
                    <div class="arta-meta-field">
                        <label><?php _e('تأیید مشاوره پزشکی:', 'arta-consult-rx'); ?></label>
                        <select name="arta_medical_consultation">
                            <option value="0" <?php selected($medical_consultation, '0'); ?>><?php _e('خیر', 'arta-consult-rx'); ?></option>
                            <option value="1" <?php selected($medical_consultation, '1'); ?>><?php _e('بله', 'arta-consult-rx'); ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="arta-meta-section">
                <h4>📞 <?php _e('اطلاعات تماس', 'arta-consult-rx'); ?></h4>
                <div class="arta-meta-row">
                    <div class="arta-meta-field">
                        <label><?php _e('ایمیل:', 'arta-consult-rx'); ?></label>
                        <input type="email" name="arta_email" value="<?php echo esc_attr($email); ?>">
                    </div>
                    <div class="arta-meta-field">
                        <label><?php _e('شماره تماس:', 'arta-consult-rx'); ?></label>
                        <input type="tel" name="arta_phone" value="<?php echo esc_attr($phone); ?>">
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="arta-meta-section">
                <h4>🏥 <?php _e('اطلاعات پزشکی', 'arta-consult-rx'); ?></h4>
                <div class="arta-meta-field">
                    <label><?php _e('بیماری‌های مزمن:', 'arta-consult-rx'); ?></label>
                    <textarea name="arta_chronic_diseases"><?php echo esc_textarea($chronic_diseases); ?></textarea>
                </div>
                <div class="arta-meta-field">
                    <label><?php _e('داروهای مصرفی:', 'arta-consult-rx'); ?></label>
                    <textarea name="arta_medications"><?php echo esc_textarea($medications); ?></textarea>
                </div>
                <div class="arta-meta-field">
                    <label><?php _e('سوابق درمانی:', 'arta-consult-rx'); ?></label>
                    <textarea name="arta_medical_history"><?php echo esc_textarea($medical_history); ?></textarea>
                </div>
                <div class="arta-meta-field">
                    <label><?php _e('هدف از برنامه:', 'arta-consult-rx'); ?></label>
                    <textarea name="arta_program_goal"><?php echo esc_textarea($program_goal); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div id="arta-rejection-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%;">
                <h3 style="margin-top: 0;"><?php _e('رد درخواست', 'arta-consult-rx'); ?></h3>
                <p><?php _e('لطفاً دلیل رد درخواست را وارد کنید:', 'arta-consult-rx'); ?></p>
                <textarea id="rejection-reason" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0;" placeholder="<?php esc_attr_e('دلیل رد درخواست (اختیاری)', 'arta-consult-rx'); ?>"></textarea>
                <div style="text-align: left; margin-top: 20px;">
                    <button id="confirm-rejection" style="background: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-left: 10px;"><?php _e('تایید رد', 'arta-consult-rx'); ?></button>
                    <button id="cancel-rejection" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;"><?php _e('لغو', 'arta-consult-rx'); ?></button>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Inline approval buttons
            $(document).on('click', '.arta-btn-approve-inline, .arta-btn-approve', function(e) {
                e.preventDefault();
                var postId = $(this).data('post-id');
                if (confirm('<?php echo esc_js(__('آیا مطمئن هستید که می‌خواهید این درخواست را تایید کنید؟', 'arta-consult-rx')); ?>')) {
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
                                location.reload();
                            } else {
                                alert(response.data.message || '<?php echo esc_js(__('خطا در تایید درخواست', 'arta-consult-rx')); ?>');
                            }
                        },
                        error: function() {
                            alert('<?php echo esc_js(__('خطا در ارتباط با سرور', 'arta-consult-rx')); ?>');
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
                            $btn.text('<?php echo esc_js(__('انجام شد درحال بارگذاری...', 'arta-consult-rx')); ?>');
                            location.reload();
                        } else {
                            alert(response.data.message || '<?php echo esc_js(__('خطا در رد درخواست', 'arta-consult-rx')); ?>');
                        }
                    },
                    error: function() {
                        alert('<?php echo esc_js(__('خطا در ارتباط با سرور', 'arta-consult-rx')); ?>');
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
     * Render consultation approval meta box (sidebar)
     */
    public function render_consultation_approval_meta_box($post) {
        $approval_status = get_post_meta($post->ID, '_arta_approval_status', true);
        if (!$approval_status) {
            $approval_status = 'pending';
        }
        
        $rejection_reason = get_post_meta($post->ID, '_arta_rejection_reason', true);
        
        wp_nonce_field('arta_consultation_approval_meta_box', 'arta_consultation_approval_meta_box_nonce');
        ?>
        <style>
            .arta-approval-meta-box {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            .arta-approval-field {
                margin-bottom: 15px;
            }
            .arta-approval-field label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
                color: #1d2327;
                font-size: 14px;
            }
            .arta-approval-field select {
                width: 100%;
                padding: 8px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
            }
            .arta-approval-field textarea {
                width: 100%;
                padding: 8px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 13px;
                resize: vertical;
                background: #f6f7f7;
            }
            .arta-status-indicator {
                display: inline-block;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin-left: 8px;
                vertical-align: middle;
            }
            .arta-status-pending {
                background-color: #ff9800;
            }
            .arta-status-approved {
                background-color: #4caf50;
            }
            .arta-status-rejected {
                background-color: #f44336;
            }
            .arta-status-completed {
                background-color: #2196f3;
            }
        </style>
        
        <div class="arta-approval-meta-box">
            <div class="arta-approval-field">
                
                <select name="arta_approval_status" id="arta_approval_status">
                    <option value="pending" <?php selected($approval_status, 'pending'); ?>>🟠 <?php _e('در انتظار بررسی', 'arta-consult-rx'); ?></option>
                    <option value="approved" <?php selected($approval_status, 'approved'); ?>>🟢 <?php _e('تایید شده', 'arta-consult-rx'); ?></option>
                    <option value="rejected" <?php selected($approval_status, 'rejected'); ?>>🔴 <?php _e('رد شده', 'arta-consult-rx'); ?></option>
                    <option value="completed" <?php selected($approval_status, 'completed'); ?>>🔵 <?php _e('تکمیل شده', 'arta-consult-rx'); ?></option>
                </select>
            </div>
            
            
            <div class="arta-approval-field" id="arta-rejection-reason-field" style="display: <?php echo $approval_status === 'rejected'  ? 'block' : 'none'; ?>;">
                <label><?php _e('دلیل رد:', 'arta-consult-rx'); ?></label>
                <textarea name="arta_rejection_reason" rows="4" placeholder="<?php esc_attr_e('دلیل رد درخواست را وارد کنید...', 'arta-consult-rx'); ?>"><?php echo esc_textarea($rejection_reason); ?></textarea>
            </div>
            
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Show/hide rejection reason field based on status selection
            $('#arta_approval_status').on('change', function() {
                var status = $(this).val();
                var $rejectionField = $('#arta-rejection-reason-field');
                
                if (status === 'rejected') {
                    $rejectionField.fadeIn(200);
                } else {
                    $rejectionField.fadeOut(200);
                }
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
            
            // Save rejection reason if provided
            if (isset($_POST['arta_rejection_reason'])) {
                $rejection_reason = sanitize_textarea_field($_POST['arta_rejection_reason']);
                update_post_meta($post_id, '_arta_rejection_reason', $rejection_reason);
            }
            
            // Update appointment status based on approval status change
            $appointment_id = get_post_meta($post_id, '_arta_appointment_id', true);
            if ($appointment_id) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'arta_appointments';
                
                // Map approval status to appointment status
                $appointment_status = '';
                
                switch ($new_status) {
                    case 'approved':
                        // If approved, set appointment to booked
                        $appointment_status = 'booked';
                        break;
                        
                    case 'rejected':
                        // If rejected, set appointment to available
                        $appointment_status = 'available';
                        break;
                        
                    case 'completed':
                        // If completed, set appointment to completed
                        $appointment_status = 'completed';
                        break;
                        
                    case 'pending':
                        // If pending, set appointment to booked (keep the reservation)
                        $appointment_status = 'booked';
                        break;
                }
                
                // Update appointment status if mapping exists
                if ($appointment_status) {
                    $result = $wpdb->update(
                        $table_name,
                        array('status' => $appointment_status),
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
        $new_columns['programs'] = __('برنامه‌ها', 'arta-consult-rx');
        $new_columns['products'] = __('محصولات', 'arta-consult-rx');
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
                
            case 'programs':
                $programs = get_post_meta($post_id, '_arta_programs', true);
                if (!empty($programs) && is_array($programs)) {
                    $program_names = array();
                    foreach ($programs as $program_id) {
                        $program = get_post($program_id);
                        if ($program) {
                            $program_names[] = '<a href="' . get_edit_post_link($program_id) . '" target="_blank">' . esc_html($program->post_title) . '</a>';
                        }
                    }
                    if (!empty($program_names)) {
                        echo '<span class="arta-programs-list">' . implode('<br>', $program_names) . '</span>';
                        echo '<br><small style="color: #666;">(' . count($program_names) . ' ' . __('برنامه', 'arta-consult-rx') . ')</small>';
                    } else {
                        echo '—';
                    }
                } else {
                    echo '—';
                }
                break;

            case 'products':
                $products = get_post_meta($post_id, '_arta_products', true);
                if (!empty($products) && is_array($products)) {
                    $product_names = array();
                    foreach ($products as $product_id) {
                        $product = get_post($product_id);
                        if ($product) {
                            $product_names[] = '<a href="' . get_edit_post_link($product_id) . '" target="_blank">' . esc_html($product->post_title) . '</a>';
                        }
                    }
                    if (!empty($product_names)) {
                        echo '<span class="arta-products-list">' . implode('<br>', $product_names) . '</span>';
                        echo '<br><small style="color: #666;">(' . count($product_names) . ' ' . __('محصول', 'arta-consult-rx') . ')</small>';
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
                    'rejected' => __('رد شده', 'arta-consult-rx'),
                    'completed' => __('تکمیل شده', 'arta-consult-rx')
                );
                
                $approval_colors = array(
                    'pending' => '#ff9800',   // نارنجی
                    'approved' => '#4caf50',  // سبز
                    'rejected' => '#f44336',  // قرمز
                    'completed' => '#2196f3'  // آبی
                );
                
                $approval_class = 'approval-' . $approval_status;
                $approval_text = isset($approval_labels[$approval_status]) ? $approval_labels[$approval_status] : $approval_status;
                $approval_color = isset($approval_colors[$approval_status]) ? $approval_colors[$approval_status] : '#999';
                
                echo '<span class="arta-approval-status ' . esc_attr($approval_class) . '">';
                echo '<span class="status-dot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: ' . esc_attr($approval_color) . '; margin-left: 6px;"></span>';
                echo esc_html($approval_text);
                echo '</span>';
                
                if ($approval_status === 'pending') {
                    echo '<div class="arta-approval-actions" style="margin-top: 5px;">';
                    echo '<a href="#" class="arta-btn-approve" data-post-id="' . $post_id . '" style="background: #4caf50; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; margin-left: 5px; text-decoration: none; display: inline-block;">' . __('تایید', 'arta-consult-rx') . '</a>';
                    echo '<a href="#" class="arta-btn-reject" data-post-id="' . $post_id . '" style="background: #f44336; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; text-decoration: none; display: inline-block;">' . __('رد', 'arta-consult-rx') . '</a>';
                    echo '</div>';
                } elseif ($approval_status === 'approved') {
                    echo '<div class="arta-approval-actions" style="margin-top: 5px;">';
                    echo '<a href="#" class="arta-btn-complete" data-post-id="' . $post_id . '" style="background: #2196f3; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; text-decoration: none; display: inline-block;">' . __('تکمیل شده', 'arta-consult-rx') . '</a>';
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
                '' => __('همه وضعیت‌ها', 'arta-consult-rx'),
                'pending' => __('در انتظار بررسی', 'arta-consult-rx'),
                'approved' => __('تایید شده', 'arta-consult-rx'),
                'rejected' => __('رد شده', 'arta-consult-rx'),
                'completed' => __('تکمیل شده', 'arta-consult-rx')
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
     * Handle consultation completion
     */
    public function handle_consultation_completion() {
        check_ajax_referer('arta_consultation_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die(__('شما مجوز لازم را ندارید', 'arta-consult-rx'));
        }

        $post_id = intval($_POST['post_id']);
        
        // Update approval status to completed
        update_post_meta($post_id, '_arta_approval_status', 'completed');
        
        // Update appointment status to completed
        $appointment_id = get_post_meta($post_id, '_arta_appointment_id', true);
        if ($appointment_id) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'arta_appointments';
            $wpdb->update(
                $table_name,
                array('status' => 'completed'),
                array('id' => $appointment_id),
                array('%s'),
                array('%d')
            );
        }

        wp_send_json_success(array('message' => __('درخواست به تکمیل شده تغییر یافت', 'arta-consult-rx')));
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
                    <h3 style="margin-top: 0;"><?php _e('رد درخواست', 'arta-consult-rx'); ?></h3>
                    <p><?php _e('لطفاً دلیل رد درخواست را وارد کنید:', 'arta-consult-rx'); ?></p>
                    <textarea id="rejection-reason" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0;" placeholder="<?php esc_attr_e('دلیل رد درخواست (اختیاری)', 'arta-consult-rx'); ?>"></textarea>
                    <div style="text-align: left; margin-top: 20px;">
                        <button id="confirm-rejection" style="background: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-left: 10px;"><?php _e('تایید رد', 'arta-consult-rx'); ?></button>
                        <button id="cancel-rejection" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;"><?php _e('لغو', 'arta-consult-rx'); ?></button>
                    </div>
                </div>
            </div>

            <script>
            jQuery(document).ready(function($) {
                // Approval buttons
                $(document).on('click', '.arta-btn-approve', function(e) {
                    e.preventDefault();
                    var postId = $(this).data('post-id');
                    if (confirm('<?php echo esc_js(__('آیا مطمئن هستید که می‌خواهید این درخواست را تایید کنید؟', 'arta-consult-rx')); ?>')) {
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
                                    location.reload();
                                } else {
                                    alert(response.data.message || '<?php echo esc_js(__('خطا در تایید درخواست', 'arta-consult-rx')); ?>');
                                }
                            },
                            error: function() {
                                alert('<?php echo esc_js(__('خطا در ارتباط با سرور', 'arta-consult-rx')); ?>');
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
                    $btn.prop('disabled', true).text('<?php echo esc_js(__('در حال پردازش...', 'arta-consult-rx')); ?>');
                    
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
                                $btn.text('<?php echo esc_js(__('انجام شد درحال بارگذاری...', 'arta-consult-rx')); ?>');
                                location.reload();
                            } else {
                                alert(response.data.message || '<?php echo esc_js(__('خطا در رد درخواست', 'arta-consult-rx')); ?>');
                                // Remove loading state
                                $btn.prop('disabled', false).text('<?php echo esc_js(__('تایید رد', 'arta-consult-rx')); ?>');
                            }
                        },
                        error: function() {
                            alert('<?php echo esc_js(__('خطا در ارتباط با سرور', 'arta-consult-rx')); ?>');
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

                // Completion buttons
                $(document).on('click', '.arta-btn-complete', function(e) {
                    e.preventDefault();
                    var postId = $(this).data('post-id');
                    if (confirm('<?php echo esc_js(__('آیا مطمئن هستید که می‌خواهید این درخواست را تکمیل شده علامت بزنید؟', 'arta-consult-rx')); ?>')) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'arta_complete_consultation',
                                post_id: postId,
                                nonce: '<?php echo wp_create_nonce('arta_consultation_nonce'); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    location.reload();
                                } else {
                                    alert(response.data.message || '<?php echo esc_js(__('خطا در تغییر وضعیت', 'arta-consult-rx')); ?>');
                                }
                            },
                            error: function() {
                                alert('<?php echo esc_js(__('خطا در ارتباط با سرور', 'arta-consult-rx')); ?>');
                            }
                        });
                    }
                });
            });
            </script>
            <?php
        }
    }
}
