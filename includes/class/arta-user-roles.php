<?php
/**
 * User Roles Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle custom user roles
 */
class Arta_User_Roles {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'add_custom_roles'));
        add_action('init', array($this, 'add_custom_capabilities'));
        register_activation_hook(ARTA_CONSULT_RX_PLUGIN_FILE, array($this, 'add_custom_roles'));
        register_deactivation_hook(ARTA_CONSULT_RX_PLUGIN_FILE, array($this, 'remove_custom_roles'));
        
        // Prevent WooCommerce redirect for doctors
        add_filter('woocommerce_prevent_admin_access', array($this, 'allow_doctor_admin_access'), 10, 1);
        add_filter('woocommerce_disable_admin_bar', array($this, 'enable_admin_bar_for_doctors'), 10, 1);
        
        // Hide unnecessary admin menus for doctors
        add_action('admin_menu', array($this, 'remove_doctor_menu_items'), 999);
        
        // Filter consultation requests for doctors
        add_action('pre_get_posts', array($this, 'filter_doctor_consultations'));
    }

    /**
     * Add custom user roles
     */
    public function add_custom_roles() {
        // Add arta_doctor role
        add_role(
            'arta_doctor',
            __('پزشک', 'arta-consult-rx'),
            array(
                'read' => true,
                'read_dashboard' => true, // Important: Allow access to dashboard
                'edit_posts' => true, // Allow viewing/editing consultation posts
                'delete_posts' => false,
                'publish_posts' => false,
                'upload_files' => false, // Removed media access
                'read_private_pages' => false,
                'read_private_posts' => true, // Allow reading consultation posts
                'edit_pages' => false,
                'edit_private_pages' => false,
                'edit_published_pages' => false,
                'delete_pages' => false,
                'delete_private_pages' => false,
                'delete_published_pages' => false,
                'edit_private_posts' => true, // Allow editing private consultation posts
                'edit_published_posts' => true, // Allow editing published consultation posts
                'delete_private_posts' => false,
                'delete_published_posts' => false,
                'manage_categories' => false,
                'manage_links' => false,
                'moderate_comments' => false,
                'unfiltered_html' => false,
                'edit_others_posts' => false,
                'delete_others_posts' => false,
                'publish_pages' => false,
                'edit_others_pages' => false,
                'delete_others_pages' => false,
                'delete_users' => false,
                'create_users' => false,
                'manage_options' => false,
                'moderate_comments' => false,
                'manage_categories' => false,
                'manage_links' => false,
                'import' => false,
                'export' => false,
                'manage_woocommerce' => false,
                'view_woocommerce_reports' => false,
                'edit_shop_orders' => false,
                'edit_shop_coupons' => false,
                'edit_shop_webhooks' => false,
                'edit_products' => false,
                'read_products' => true,
                'delete_products' => false,
                'edit_published_products' => false,
                'delete_published_products' => false,
                'edit_private_products' => false,
                'delete_private_products' => false,
                'manage_product_terms' => false,
                'edit_product_terms' => false,
                'delete_product_terms' => false,
                'assign_product_terms' => false,
                'edit_shop_orders' => false,
                'read_shop_orders' => true,
                'delete_shop_orders' => false,
                'edit_published_shop_orders' => false,
                'delete_published_shop_orders' => false,
                'edit_private_shop_orders' => false,
                'delete_private_shop_orders' => false,
                'manage_shop_order_terms' => false,
                'edit_shop_order_terms' => false,
                'delete_shop_order_terms' => false,
                'assign_shop_order_terms' => false,
                'edit_shop_coupons' => false,
                'read_shop_coupons' => true,
                'delete_shop_coupons' => false,
                'edit_published_shop_coupons' => false,
                'delete_published_shop_coupons' => false,
                'edit_private_shop_coupons' => false,
                'delete_private_shop_coupons' => false,
                'manage_shop_coupon_terms' => false,
                'edit_shop_coupon_terms' => false,
                'delete_shop_coupon_terms' => false,
                'assign_shop_coupon_terms' => false,
                'edit_shop_webhooks' => false,
                'read_shop_webhooks' => true,
                'delete_shop_webhooks' => false,
                'edit_published_shop_webhooks' => false,
                'delete_published_shop_webhooks' => false,
                'edit_private_shop_webhooks' => false,
                'delete_private_shop_webhooks' => false,
                'manage_shop_webhook_terms' => false,
                'edit_shop_webhook_terms' => false,
                'delete_shop_webhook_terms' => false,
                'assign_shop_webhook_terms' => false,
                // Custom capabilities for arta_doctor
                'arta_view_own_appointments' => true,
                'arta_create_appointments' => true,
                'arta_edit_own_appointments' => true,
                'arta_delete_own_appointments' => true,
                'arta_view_consultation_requests' => true,
                'arta_respond_to_consultations' => true,
            )
        );
    }

    /**
     * Add custom capabilities to existing roles
     */
    public function add_custom_capabilities() {
        // Add capabilities to administrator
        $admin_role = get_role('administrator');
        if ($admin_role) {
            $admin_role->add_cap('arta_manage_programs');
            $admin_role->add_cap('arta_manage_appointments');
            $admin_role->add_cap('arta_manage_doctors');
            $admin_role->add_cap('arta_view_all_appointments');
            $admin_role->add_cap('arta_edit_all_appointments');
            $admin_role->add_cap('arta_delete_all_appointments');
            $admin_role->add_cap('arta_manage_consultations');
        }

        // Add capabilities to editor
        $editor_role = get_role('editor');
        if ($editor_role) {
            $editor_role->add_cap('arta_view_all_appointments');
            $editor_role->add_cap('arta_edit_all_appointments');
        }
        
        // Ensure doctor role has dashboard access
        $doctor_role = get_role('arta_doctor');
        if ($doctor_role) {
            $doctor_role->add_cap('read_dashboard');
            $doctor_role->add_cap('edit_posts'); // View consultation posts
            $doctor_role->add_cap('edit_published_posts'); // Edit consultation posts
            $doctor_role->add_cap('edit_private_posts'); // Edit private consultation posts
            $doctor_role->add_cap('read_private_posts'); // Read consultation posts
            $doctor_role->add_cap('arta_view_own_appointments');
            $doctor_role->add_cap('arta_create_appointments');
            $doctor_role->add_cap('arta_edit_own_appointments');
            $doctor_role->add_cap('arta_delete_own_appointments');
            $doctor_role->add_cap('arta_view_consultation_requests');
            $doctor_role->add_cap('arta_respond_to_consultations');
        }
    }

    /**
     * Remove custom user roles
     */
    public function remove_custom_roles() {
        remove_role('arta_doctor');
    }

    /**
     * Add doctor role (static method for activation)
     */
    public static function add_doctor_role() {
        add_role(
            'arta_doctor',
            __('پزشک', 'arta-consult-rx'),
            array(
                'read' => true,
                'upload_files' => true,
            )
        );
    }

    /**
     * Remove doctor role (static method for deactivation)
     */
    public static function remove_doctor_role() {
        remove_role('arta_doctor');
    }

    /**
     * Get doctor users
     *
     * @return array
     */
    public static function get_doctor_users() {
        $users = get_users(array(
            'role' => 'arta_doctor',
            'orderby' => 'display_name',
            'order' => 'ASC'
        ));

        return $users;
    }

    /**
     * Check if user is doctor
     *
     * @param int $user_id
     * @return bool
     */
    public static function is_doctor($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $user = get_userdata($user_id);
        return $user && in_array('arta_doctor', $user->roles);
    }

    /**
     * Allow doctors to access admin area (prevent WooCommerce redirect)
     *
     * @param bool $prevent_access
     * @return bool
     */
    public function allow_doctor_admin_access($prevent_access) {
        if (self::is_doctor()) {
            return false; // Don't prevent access for doctors
        }
        return $prevent_access;
    }

    /**
     * Enable admin bar for doctors
     *
     * @param bool $disable_admin_bar
     * @return bool
     */
    public function enable_admin_bar_for_doctors($disable_admin_bar) {
        if (self::is_doctor()) {
            return false; // Don't disable admin bar for doctors
        }
        return $disable_admin_bar;
    }

    /**
     * Remove unnecessary menu items for doctors
     * Uses whitelist approach: removes all menus except allowed ones
     */
    public function remove_doctor_menu_items() {
        if (!self::is_doctor()) {
            return;
        }

        global $menu, $submenu;
        
        // Whitelist: only these menus are allowed
        $allowed_menus = array(
            'index.php',              // Dashboard (پیشخوان)
            'arta-consult-rx',        // Arta Consult RX (منوی افزونه ما)
            'separator1',             // WordPress separators
            'separator2',
            'separator-last',
        );
        
        // Remove all menus except whitelisted ones
        if (is_array($menu)) {
            foreach ($menu as $key => $menu_item) {
                // $menu_item[2] contains the menu slug
                if (isset($menu_item[2]) && !in_array($menu_item[2], $allowed_menus)) {
                    // Remove this menu
                    unset($menu[$key]);
                    
                    // Also remove its submenus
                    if (isset($submenu[$menu_item[2]])) {
                        unset($submenu[$menu_item[2]]);
                    }
                }
            }
        }
        
        // Log removed menus for debugging (optional - remove in production)
        // error_log('Doctor menus after filtering: ' . print_r($menu, true));
    }

    /**
     * Filter consultation posts to show only doctor's own consultations
     */
    public function filter_doctor_consultations($query) {
        // Only in admin area
        if (!is_admin()) {
            return;
        }

        // Only for doctors
        if (!self::is_doctor()) {
            return;
        }

        // Only for consultation post type
        if (!isset($query->query_vars['post_type']) || $query->query_vars['post_type'] !== 'arta_consultation') {
            return;
        }

        // Only for main query
        if (!$query->is_main_query()) {
            return;
        }

        // Get current doctor ID
        $current_doctor_id = get_current_user_id();

        // Add meta query to filter by doctor
        $meta_query = array(
            array(
                'key' => '_arta_doctor_id',
                'value' => $current_doctor_id,
                'compare' => '='
            )
        );

        $query->set('meta_query', $meta_query);
    }
}
