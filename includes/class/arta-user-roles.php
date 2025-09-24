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
                'edit_posts' => false,
                'delete_posts' => false,
                'publish_posts' => false,
                'upload_files' => true,
                'read_private_pages' => false,
                'read_private_posts' => false,
                'edit_pages' => false,
                'edit_private_pages' => false,
                'edit_published_pages' => false,
                'delete_pages' => false,
                'delete_private_pages' => false,
                'delete_published_pages' => false,
                'edit_private_posts' => false,
                'edit_published_posts' => false,
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
                'upload_files' => true,
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
}
