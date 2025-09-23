<?php
/**
 * Custom Post Types class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Post Types class
 */
class Arta_Consult_RX_Post_Types {

    /**
     * Constructor
     */
    public function __construct() {
        // Use a lower priority (20) to ensure this runs after the admin menu is set up
        add_action('init', array($this, 'register_post_types'), 20);
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        $this->register_program_post_type();
        $this->register_doctor_post_type();
    }

    /**
     * Register Program post type
     */
    private function register_program_post_type() {
        $labels = array(
            'name'                  => _x('Programs', 'Post type general name', 'arta-consult-rx'),
            'singular_name'         => _x('Program', 'Post type singular name', 'arta-consult-rx'),
            'menu_name'             => _x('Programs', 'Admin Menu text', 'arta-consult-rx'),
            'name_admin_bar'        => _x('Program', 'Add New on Toolbar', 'arta-consult-rx'),
            'add_new'               => __('Add New', 'arta-consult-rx'),
            'add_new_item'          => __('Add New Program', 'arta-consult-rx'),
            'new_item'              => __('New Program', 'arta-consult-rx'),
            'edit_item'             => __('Edit Program', 'arta-consult-rx'),
            'view_item'             => __('View Program', 'arta-consult-rx'),
            'all_items'             => __('All Programs', 'arta-consult-rx'),
            'search_items'          => __('Search Programs', 'arta-consult-rx'),
            'parent_item_colon'     => __('Parent Programs:', 'arta-consult-rx'),
            'not_found'             => __('No programs found.', 'arta-consult-rx'),
            'not_found_in_trash'    => __('No programs found in Trash.', 'arta-consult-rx'),
            'featured_image'        => _x('Program Image', 'Overrides the "Featured Image" phrase', 'arta-consult-rx'),
            'set_featured_image'    => _x('Set program image', 'Overrides the "Set featured image" phrase', 'arta-consult-rx'),
            'remove_featured_image' => _x('Remove program image', 'Overrides the "Remove featured image" phrase', 'arta-consult-rx'),
            'use_featured_image'    => _x('Use as program image', 'Overrides the "Use as featured image" phrase', 'arta-consult-rx'),
            'archives'              => _x('Program archives', 'The post type archive label', 'arta-consult-rx'),
            'insert_into_item'      => _x('Insert into program', 'Overrides the "Insert into post" phrase', 'arta-consult-rx'),
            'uploaded_to_this_item' => _x('Uploaded to this program', 'Overrides the "Uploaded to this post" phrase', 'arta-consult-rx'),
            'filter_items_list'     => _x('Filter programs list', 'Screen reader text for the filter links', 'arta-consult-rx'),
            'items_list_navigation' => _x('Programs list navigation', 'Screen reader text for the pagination', 'arta-consult-rx'),
            'items_list'            => _x('Programs list', 'Screen reader text for the items list', 'arta-consult-rx'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'arta-consult-rx',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'program'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-clipboard',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
        );

        register_post_type('arta_program', $args);
    }

    /**
     * Register Doctor post type
     */
    private function register_doctor_post_type() {
        $labels = array(
            'name'                  => _x('Doctors', 'Post type general name', 'arta-consult-rx'),
            'singular_name'         => _x('Doctor', 'Post type singular name', 'arta-consult-rx'),
            'menu_name'             => _x('Doctors', 'Admin Menu text', 'arta-consult-rx'),
            'name_admin_bar'        => _x('Doctor', 'Add New on Toolbar', 'arta-consult-rx'),
            'add_new'               => __('Add New', 'arta-consult-rx'),
            'add_new_item'          => __('Add New Doctor', 'arta-consult-rx'),
            'new_item'              => __('New Doctor', 'arta-consult-rx'),
            'edit_item'             => __('Edit Doctor', 'arta-consult-rx'),
            'view_item'             => __('View Doctor', 'arta-consult-rx'),
            'all_items'             => __('All Doctors', 'arta-consult-rx'),
            'search_items'          => __('Search Doctors', 'arta-consult-rx'),
            'parent_item_colon'     => __('Parent Doctors:', 'arta-consult-rx'),
            'not_found'             => __('No doctors found.', 'arta-consult-rx'),
            'not_found_in_trash'    => __('No doctors found in Trash.', 'arta-consult-rx'),
            'featured_image'        => _x('Doctor Photo', 'Overrides the "Featured Image" phrase', 'arta-consult-rx'),
            'set_featured_image'    => _x('Set doctor photo', 'Overrides the "Set featured image" phrase', 'arta-consult-rx'),
            'remove_featured_image' => _x('Remove doctor photo', 'Overrides the "Remove featured image" phrase', 'arta-consult-rx'),
            'use_featured_image'    => _x('Use as doctor photo', 'Overrides the "Use as featured image" phrase', 'arta-consult-rx'),
            'archives'              => _x('Doctor archives', 'The post type archive label', 'arta-consult-rx'),
            'insert_into_item'      => _x('Insert into doctor', 'Overrides the "Insert into post" phrase', 'arta-consult-rx'),
            'uploaded_to_this_item' => _x('Uploaded to this doctor', 'Overrides the "Uploaded to this post" phrase', 'arta-consult-rx'),
            'filter_items_list'     => _x('Filter doctors list', 'Screen reader text for the filter links', 'arta-consult-rx'),
            'items_list_navigation' => _x('Doctors list navigation', 'Screen reader text for the pagination', 'arta-consult-rx'),
            'items_list'            => _x('Doctors list', 'Screen reader text for the items list', 'arta-consult-rx'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'arta-consult-rx',
            'query_var'          => true,
            'rewrite'            => array('slug' => 'doctor'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 6,
            'menu_icon'          => 'dashicons-admin-users',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'       => true,
        );

        register_post_type('arta_doctor', $args);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Program meta boxes
        add_meta_box(
            'arta_program_details',
            __('Program Details', 'arta-consult-rx'),
            array($this, 'program_details_meta_box'),
            'arta_program',
            'normal',
            'high'
        );

        add_meta_box(
            'arta_program_doctor',
            __('Associated Doctor', 'arta-consult-rx'),
            array($this, 'program_doctor_meta_box'),
            'arta_program',
            'side',
            'high'
        );

        add_meta_box(
            'arta_program_products',
            __('Related Products', 'arta-consult-rx'),
            array($this, 'program_products_meta_box'),
            'arta_program',
            'side',
            'default'
        );

        // Doctor meta boxes
        add_meta_box(
            'arta_doctor_details',
            __('Doctor Details', 'arta-consult-rx'),
            array($this, 'doctor_details_meta_box'),
            'arta_doctor',
            'normal',
            'high'
        );

        add_meta_box(
            'arta_doctor_availability',
            __('Availability Settings', 'arta-consult-rx'),
            array($this, 'doctor_availability_meta_box'),
            'arta_doctor',
            'normal',
            'default'
        );
    }

    /**
     * Program details meta box
     */
    public function program_details_meta_box($post) {
        wp_nonce_field('arta_program_meta_box', 'arta_program_meta_box_nonce');
        
        $objectives = get_post_meta($post->ID, '_program_objectives', true);
        $benefits = get_post_meta($post->ID, '_program_benefits', true);
        $duration = get_post_meta($post->ID, '_program_duration', true);
        $price = get_post_meta($post->ID, '_program_price', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="program_objectives"><?php _e('Program Objectives', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <textarea id="program_objectives" name="program_objectives" rows="4" cols="50" style="width: 100%;"><?php echo esc_textarea($objectives); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="program_benefits"><?php _e('Program Benefits', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <textarea id="program_benefits" name="program_benefits" rows="4" cols="50" style="width: 100%;"><?php echo esc_textarea($benefits); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="program_duration"><?php _e('Duration (days)', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <input type="number" id="program_duration" name="program_duration" value="<?php echo esc_attr($duration); ?>" min="1" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="program_price"><?php _e('Price', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <input type="number" id="program_price" name="program_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0" />
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Program doctor meta box
     */
    public function program_doctor_meta_box($post) {
        $associated_doctor = get_post_meta($post->ID, '_associated_doctor', true);
        
        $doctors = get_posts(array(
            'post_type' => 'arta_doctor',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        ?>
        <select name="associated_doctor" id="associated_doctor" style="width: 100%;">
            <option value=""><?php _e('Select a doctor', 'arta-consult-rx'); ?></option>
            <?php foreach ($doctors as $doctor): ?>
                <option value="<?php echo $doctor->ID; ?>" <?php selected($associated_doctor, $doctor->ID); ?>>
                    <?php echo esc_html($doctor->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Program products meta box
     */
    public function program_products_meta_box($post) {
        $related_products = get_post_meta($post->ID, '_related_products', true);
        if (!is_array($related_products)) {
            $related_products = array();
        }
        
        $products = get_posts(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        ?>
        <div id="related-products-container">
            <?php foreach ($products as $product): ?>
                <label>
                    <input type="checkbox" name="related_products[]" value="<?php echo $product->ID; ?>" 
                           <?php checked(in_array($product->ID, $related_products)); ?> />
                    <?php echo esc_html($product->post_title); ?>
                </label><br>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Doctor details meta box
     */
    public function doctor_details_meta_box($post) {
        wp_nonce_field('arta_doctor_meta_box', 'arta_doctor_meta_box_nonce');
        
        $credentials = get_post_meta($post->ID, '_doctor_credentials', true);
        $experience = get_post_meta($post->ID, '_doctor_experience', true);
        $education = get_post_meta($post->ID, '_doctor_education', true);
        $phone = get_post_meta($post->ID, '_doctor_phone', true);
        $email = get_post_meta($post->ID, '_doctor_email', true);
        $address = get_post_meta($post->ID, '_doctor_address', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="doctor_credentials"><?php _e('Credentials', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <input type="text" id="doctor_credentials" name="doctor_credentials" value="<?php echo esc_attr($credentials); ?>" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="doctor_experience"><?php _e('Experience (years)', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <input type="number" id="doctor_experience" name="doctor_experience" value="<?php echo esc_attr($experience); ?>" min="0" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="doctor_education"><?php _e('Education', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <textarea id="doctor_education" name="doctor_education" rows="3" cols="50" style="width: 100%;"><?php echo esc_textarea($education); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="doctor_phone"><?php _e('Phone', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <input type="tel" id="doctor_phone" name="doctor_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="doctor_email"><?php _e('Email', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <input type="email" id="doctor_email" name="doctor_email" value="<?php echo esc_attr($email); ?>" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="doctor_address"><?php _e('Address', 'arta-consult-rx'); ?></label>
                </th>
                <td>
                    <textarea id="doctor_address" name="doctor_address" rows="3" cols="50" style="width: 100%;"><?php echo esc_textarea($address); ?></textarea>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Doctor availability meta box
     */
    public function doctor_availability_meta_box($post) {
        $available_slots = get_post_meta($post->ID, '_available_slots', true);
        if (!is_array($available_slots)) {
            $available_slots = array();
        }
        ?>
        <div id="availability-settings">
            <p><?php _e('Configure doctor availability for appointment booking.', 'arta-consult-rx'); ?></p>
            <div id="availability-container">
                <!-- Availability settings will be managed via JavaScript -->
                <p><?php _e('Availability settings will be managed through the admin interface.', 'arta-consult-rx'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save program meta
        if (isset($_POST['arta_program_meta_box_nonce']) && wp_verify_nonce($_POST['arta_program_meta_box_nonce'], 'arta_program_meta_box')) {
            $this->save_program_meta($post_id);
        }

        // Save doctor meta
        if (isset($_POST['arta_doctor_meta_box_nonce']) && wp_verify_nonce($_POST['arta_doctor_meta_box_nonce'], 'arta_doctor_meta_box')) {
            $this->save_doctor_meta($post_id);
        }
    }

    /**
     * Save program meta data
     */
    private function save_program_meta($post_id) {
        $fields = array(
            'program_objectives',
            'program_benefits',
            'program_duration',
            'program_price',
            'associated_doctor'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                if ($field === 'program_objectives' || $field === 'program_benefits') {
                    $value = sanitize_textarea_field($_POST[$field]);
                }
                update_post_meta($post_id, '_' . $field, $value);
            }
        }

        // Save related products
        if (isset($_POST['related_products'])) {
            $related_products = array_map('intval', $_POST['related_products']);
            update_post_meta($post_id, '_related_products', $related_products);
        } else {
            update_post_meta($post_id, '_related_products', array());
        }
    }

    /**
     * Save doctor meta data
     */
    private function save_doctor_meta($post_id) {
        $fields = array(
            'doctor_credentials',
            'doctor_experience',
            'doctor_education',
            'doctor_phone',
            'doctor_email',
            'doctor_address'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                if ($field === 'doctor_education' || $field === 'doctor_address') {
                    $value = sanitize_textarea_field($_POST[$field]);
                }
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }
}
