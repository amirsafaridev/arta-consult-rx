<?php
/**
 * Meta Boxes Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle custom meta boxes
 */
class Arta_Meta_Boxes {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'arta_program_details',
            __('جزئیات برنامه', 'arta-consult-rx'),
            array($this, 'arta_program_details_callback'),
            'arta_program',
            'normal',
            'high'
        );

        add_meta_box(
            'arta_program_goals',
            __('توضیحات اهداف', 'arta-consult-rx'),
            array($this, 'arta_program_goals_callback'),
            'arta_program',
            'normal',
            'high'
        );

        add_meta_box(
            'arta_program_benefits',
            __('توضیحات مزایا', 'arta-consult-rx'),
            array($this, 'arta_program_benefits_callback'),
            'arta_program',
            'normal',
            'high'
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;

        if (($hook == 'post.php' || $hook == 'post-new.php') && $post_type == 'arta_program') {
            wp_enqueue_script('select2');
            wp_enqueue_style('select2');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-datepicker');
            
            // Enqueue our custom admin styles and scripts
            wp_enqueue_style(
                'arta-admin-css',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                ARTA_CONSULT_RX_VERSION
            );
            
            wp_enqueue_script(
                'arta-admin-js',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'select2'),
                ARTA_CONSULT_RX_VERSION,
                true
            );
        }
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        if (is_singular('arta_program')) {
            wp_enqueue_style(
                'arta-single-program-css',
                ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/single-program.css',
                array(),
                ARTA_CONSULT_RX_VERSION
            );
        }
    }

    /**
     * Program details meta box callback
     */
    public function arta_program_details_callback($post) {
        // Add nonce for security
        wp_nonce_field('arta_program_details_nonce', 'arta_program_details_nonce');

        // Get current values
        $doctors = get_post_meta($post->ID, '_arta_program_doctors', true);
        $goals_description = get_post_meta($post->ID, '_arta_program_goals_description', true);
        $benefits_description = get_post_meta($post->ID, '_arta_program_benefits_description', true);
        $related_products = get_post_meta($post->ID, '_arta_program_related_products', true);
        
        // Ensure arrays are properly formatted
        if (!is_array($doctors)) {
            $doctors = array();
        }
        if (!is_array($related_products)) {
            $related_products = array();
        }

        // Get doctors list
        $doctor_users = Arta_User_Roles::get_doctor_users();

        // Get products list (if WooCommerce is active)
        $products = array();
        if (class_exists('WooCommerce')) {
            $products_query = get_posts(array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC'
            ));

            foreach ($products_query as $product) {
                $products[] = $product;
            }
        }

        ?>
        <div class="arta-admin">
            <div class="arta-card">
                <div class="arta-card-content">
                    <div style="display: block;">
                        <div class="arta-form-group">
                            <label for="arta_program_doctors" class="arta-form-label"><?php _e('انتخاب پزشک‌ها', 'arta-consult-rx'); ?></label>
                            <select id="arta_program_doctors" class="arta-form-select">
                                <option value=""><?php _e('پزشک را انتخاب کنید...', 'arta-consult-rx'); ?></option>
                                <?php foreach ($doctor_users as $doctor): ?>
                                    <option value="<?php echo esc_attr($doctor->ID); ?>">
                                        <?php echo esc_html($doctor->display_name); ?> #<?php echo esc_html($doctor->ID); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="arta_program_doctors_hidden" name="arta_program[doctors]" value="<?php echo esc_attr(implode(',', $doctors)); ?>">
                            <div id="arta_selected_doctors" class="arta-selected-items">
                                <?php if (!empty($doctors)): ?>
                                    <?php foreach ($doctors as $doctor_id): ?>
                                        <?php 
                                        $doctor = get_user_by('ID', $doctor_id);
                                        if ($doctor): 
                                        ?>
                                            <div class="arta-selected-item" data-id="<?php echo esc_attr($doctor_id); ?>">
                                                <span><?php echo esc_html($doctor->display_name); ?> <span class="arta-item-id">#<?php echo esc_html($doctor_id); ?></span></span>
                                                <button type="button" class="arta-remove-item">×</button>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <p style="font-size: 12px; color: rgba(33, 33, 33, 0.6); margin-top: 8px;">
                                <?php _e('پزشک‌های مسئول این برنامه را انتخاب کنید.', 'arta-consult-rx'); ?>
                            </p>
                        </div>


                        <?php if (!empty($products)): ?>
                        <div class="arta-form-group">
                            <label for="arta_program_related_products" class="arta-form-label"><?php _e('محصولات مرتبط', 'arta-consult-rx'); ?></label>
                            <select id="arta_program_related_products" class="arta-form-select">
                                <option value=""><?php _e('محصول را انتخاب کنید...', 'arta-consult-rx'); ?></option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo esc_attr($product->ID); ?>">
                                        <?php echo esc_html($product->post_title); ?> #<?php echo esc_html($product->ID); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="arta_program_related_products_hidden" name="arta_program[related_products]" value="<?php echo esc_attr(implode(',', $related_products)); ?>">
                            <div id="arta_selected_products" class="arta-selected-items">
                                <?php if (!empty($related_products)): ?>
                                    <?php foreach ($related_products as $product_id): ?>
                                        <?php 
                                        $product = get_post($product_id);
                                        if ($product): 
                                        ?>
                                            <div class="arta-selected-item" data-id="<?php echo esc_attr($product_id); ?>">
                                                <span><?php echo esc_html($product->post_title); ?> <span class="arta-item-id">#<?php echo esc_html($product_id); ?></span></span>
                                                <button type="button" class="arta-remove-item">×</button>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <p style="font-size: 12px; color: rgba(33, 33, 33, 0.6); margin-top: 8px;">
                                <?php _e('محصولات مرتبط با این برنامه را انتخاب کنید.', 'arta-consult-rx'); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add Material Design styling to form elements
            $('.arta-form-input, .arta-form-select, .arta-form-textarea').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                if (!$(this).val()) {
                    $(this).parent().removeClass('focused');
                }
            });

            // Initialize focused state for fields with values
            $('.arta-form-input, .arta-form-select, .arta-form-textarea').each(function() {
                if ($(this).val()) {
                    $(this).parent().addClass('focused');
                }
            });
        });
        </script>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        // Check if our nonces are set
        if (!isset($_POST['arta_program_details_nonce']) && 
            !isset($_POST['arta_program_goals_nonce']) && 
            !isset($_POST['arta_program_benefits_nonce'])) {
            return;
        }

        // Verify that the nonces are valid
        if (isset($_POST['arta_program_details_nonce']) && 
            !wp_verify_nonce($_POST['arta_program_details_nonce'], 'arta_program_details_nonce')) {
            return;
        }
        
        if (isset($_POST['arta_program_goals_nonce']) && 
            !wp_verify_nonce($_POST['arta_program_goals_nonce'], 'arta_program_goals_nonce')) {
            return;
        }
        
        if (isset($_POST['arta_program_benefits_nonce']) && 
            !wp_verify_nonce($_POST['arta_program_benefits_nonce'], 'arta_program_benefits_nonce')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if (isset($_POST['post_type']) && 'arta_program' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        // Sanitize and save the data
        if (isset($_POST['arta_program'])) {
            $program_data = $_POST['arta_program'];

            // Save doctors
            if (isset($program_data['doctors']) && !empty($program_data['doctors'])) {
                // Handle comma-separated string
                if (is_string($program_data['doctors'])) {
                    $doctors_array = explode(',', $program_data['doctors']);
                    $doctors = array_map('intval', array_filter($doctors_array));
                } else {
                    $doctors = array_map('intval', $program_data['doctors']);
                }
                update_post_meta($post_id, '_arta_program_doctors', $doctors);
            } else {
                delete_post_meta($post_id, '_arta_program_doctors');
            }

            // Save goals description
            if (isset($program_data['goals_description'])) {
                $goals_description = wp_kses_post($program_data['goals_description']);
                update_post_meta($post_id, '_arta_program_goals_description', $goals_description);
            }

            // Save benefits description
            if (isset($program_data['benefits_description'])) {
                $benefits_description = wp_kses_post($program_data['benefits_description']);
                update_post_meta($post_id, '_arta_program_benefits_description', $benefits_description);
            }

            // Save related products
            if (isset($program_data['related_products']) && !empty($program_data['related_products'])) {
                // Handle comma-separated string
                if (is_string($program_data['related_products'])) {
                    $products_array = explode(',', $program_data['related_products']);
                    $related_products = array_map('intval', array_filter($products_array));
                } else {
                    $related_products = array_map('intval', $program_data['related_products']);
                }
                update_post_meta($post_id, '_arta_program_related_products', $related_products);
            } else {
                delete_post_meta($post_id, '_arta_program_related_products');
            }
        }
    }

    /**
     * Goals meta box callback
     */
    public function arta_program_goals_callback($post) {
        wp_nonce_field('arta_program_goals_nonce', 'arta_program_goals_nonce');
        
        $goals_description = get_post_meta($post->ID, '_arta_program_goals_description', true);
        
        ?>
        <div class="arta-admin">
            <div class="arta-card">
                <div class="arta-card-content">
                    <div class="arta-form-group">
                        <label for="arta_program_goals_description" class="arta-form-label"><?php _e('توضیحات اهداف', 'arta-consult-rx'); ?></label>
                        <?php
                        wp_editor($goals_description, 'arta_program_goals_description', array(
                            'textarea_name' => 'arta_program[goals_description]',
                            'media_buttons' => true,
                            'textarea_rows' => 10,
                            'teeny' => false,
                            'tinymce' => array(
                                'toolbar1' => 'bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,link,unlink,|,spellchecker,fullscreen,wp_adv',
                                'toolbar2' => 'formatselect,|,pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo,wp_help'
                            )
                        ));
                        ?>
                        <p style="font-size: 12px; color: rgba(33, 33, 33, 0.6); margin-top: 8px;">
                            <?php _e('اهداف و مقاصد این برنامه را به تفصیل شرح دهید.', 'arta-consult-rx'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Benefits meta box callback
     */
    public function arta_program_benefits_callback($post) {
        wp_nonce_field('arta_program_benefits_nonce', 'arta_program_benefits_nonce');
        
        $benefits_description = get_post_meta($post->ID, '_arta_program_benefits_description', true);
        
        ?>
        <div class="arta-admin">
            <div class="arta-card">
                <div class="arta-card-content">
                    <div class="arta-form-group">
                        <label for="arta_program_benefits_description" class="arta-form-label"><?php _e('توضیحات مزایا', 'arta-consult-rx'); ?></label>
                        <?php
                        wp_editor($benefits_description, 'arta_program_benefits_description', array(
                            'textarea_name' => 'arta_program[benefits_description]',
                            'media_buttons' => true,
                            'textarea_rows' => 10,
                            'teeny' => false,
                            'tinymce' => array(
                                'toolbar1' => 'bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,link,unlink,|,spellchecker,fullscreen,wp_adv',
                                'toolbar2' => 'formatselect,|,pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo,wp_help'
                            )
                        ));
                        ?>
                        <p style="font-size: 12px; color: rgba(33, 33, 33, 0.6); margin-top: 8px;">
                            <?php _e('مزایا و فواید این برنامه را به تفصیل شرح دهید.', 'arta-consult-rx'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
