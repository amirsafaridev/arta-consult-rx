<?php
/**
 * Arta Checkout Fields Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle custom checkout fields
 */
class Arta_Checkout_Fields {

    /**
     * Constructor
     */
    public function __construct() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Add custom fields to checkout
        add_action('woocommerce_checkout_fields', array($this, 'add_checkout_fields'));

        // Set default values for checkout fields from user meta
        add_filter('woocommerce_checkout_get_value', array($this, 'get_checkout_field_value'), 10, 2);

        // Validate custom fields
        add_action('woocommerce_checkout_process', array($this, 'validate_checkout_fields'));

        // Save custom fields to order meta and user meta
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_checkout_fields'));

        // Display custom fields in order details
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'display_order_fields'));

        // Display custom fields in order emails
        add_action('woocommerce_email_order_meta', array($this, 'display_order_fields_in_email'), 10, 3);

        // Display custom fields in order view (frontend)
        add_action('woocommerce_order_details_after_order_table', array($this, 'display_order_fields_frontend'));
    }

    /**
     * Add custom fields to checkout
     *
     * @param array $fields
     * @return array
     */
    public function add_checkout_fields($fields) {
        // Get user meta values if user is logged in
        $user_id = get_current_user_id();
        $default_values = array();
        
        if ($user_id) {
            $default_values = array(
                'arta_gender' => get_user_meta($user_id, 'arta_gender', true),
                'arta_birth_date' => get_user_meta($user_id, 'arta_birth_date', true),
                'arta_height' => get_user_meta($user_id, 'arta_height', true),
                'arta_weight' => get_user_meta($user_id, 'arta_weight', true),
                'arta_chronic_diseases' => get_user_meta($user_id, 'arta_chronic_diseases', true),
                'arta_current_medications' => get_user_meta($user_id, 'arta_current_medications', true),
                'arta_medical_history' => get_user_meta($user_id, 'arta_medical_history', true),
                'arta_program_goal' => get_user_meta($user_id, 'arta_program_goal', true),
                'arta_allergies' => get_user_meta($user_id, 'arta_allergies', true),
            );
        }

        // Add custom fields to billing section
        $fields['billing']['arta_gender'] = array(
            'label' => __('جنسیت', 'arta-consult-rx'),
            'placeholder' => __('جنسیت را انتخاب کنید', 'arta-consult-rx'),
            'required' => true,
            'class' => array('form-row-wide'),
            'type' => 'select',
            'options' => array(
                '' => __('انتخاب کنید', 'arta-consult-rx'),
                'male' => __('مرد', 'arta-consult-rx'),
                'female' => __('زن', 'arta-consult-rx'),
            ),
            'default' => isset($default_values['arta_gender']) ? $default_values['arta_gender'] : '',
            'priority' => 25,
        );

        $fields['billing']['arta_birth_date'] = array(
            'label' => __('تاریخ تولد', 'arta-consult-rx'),
            'placeholder' => __('YYYY-MM-DD', 'arta-consult-rx'),
            'required' => true,
            'class' => array('form-row-wide'),
            'type' => 'date',
            'default' => isset($default_values['arta_birth_date']) ? $default_values['arta_birth_date'] : '',
            'priority' => 26,
        );

        $fields['billing']['arta_height'] = array(
            'label' => __('قد (سانتی‌متر)', 'arta-consult-rx'),
            'placeholder' => __('قد خود را وارد کنید', 'arta-consult-rx'),
            'required' => true,
            'class' => array('form-row-wide'),
            'type' => 'number',
            'default' => isset($default_values['arta_height']) ? $default_values['arta_height'] : '',
            'priority' => 27,
        );

        $fields['billing']['arta_weight'] = array(
            'label' => __('وزن (کیلوگرم)', 'arta-consult-rx'),
            'placeholder' => __('وزن خود را وارد کنید', 'arta-consult-rx'),
            'required' => true,
            'class' => array('form-row-wide'),
            'type' => 'number',
            'default' => isset($default_values['arta_weight']) ? $default_values['arta_weight'] : '',
            'priority' => 28,
        );

        $fields['billing']['arta_chronic_diseases'] = array(
            'label' => __('بیماری‌های مزمن', 'arta-consult-rx'),
            'placeholder' => __('بیماری‌های مزمن خود را وارد کنید', 'arta-consult-rx'),
            'required' => false,
            'class' => array('form-row-wide'),
            'type' => 'textarea',
            'default' => isset($default_values['arta_chronic_diseases']) ? $default_values['arta_chronic_diseases'] : '',
            'priority' => 29,
        );

        $fields['billing']['arta_current_medications'] = array(
            'label' => __('داروهای مصرفی فعلی', 'arta-consult-rx'),
            'placeholder' => __('داروهای مصرفی فعلی خود را وارد کنید', 'arta-consult-rx'),
            'required' => false,
            'class' => array('form-row-wide'),
            'type' => 'textarea',
            'default' => isset($default_values['arta_current_medications']) ? $default_values['arta_current_medications'] : '',
            'priority' => 30,
        );

        $fields['billing']['arta_medical_history'] = array(
            'label' => __('سوابق پزشکی', 'arta-consult-rx'),
            'placeholder' => __('سوابق پزشکی خود را وارد کنید', 'arta-consult-rx'),
            'required' => false,
            'class' => array('form-row-wide'),
            'type' => 'textarea',
            'default' => isset($default_values['arta_medical_history']) ? $default_values['arta_medical_history'] : '',
            'priority' => 31,
        );

        $fields['billing']['arta_program_goal'] = array(
            'label' => __('هدف از برنامه', 'arta-consult-rx'),
            'placeholder' => __('هدف خود از این برنامه را وارد کنید', 'arta-consult-rx'),
            'required' => false,
            'class' => array('form-row-wide'),
            'type' => 'textarea',
            'default' => isset($default_values['arta_program_goal']) ? $default_values['arta_program_goal'] : '',
            'priority' => 32,
        );

        $fields['billing']['arta_allergies'] = array(
            'label' => __('آلرژی‌ها', 'arta-consult-rx'),
            'placeholder' => __('آلرژی‌های خود را وارد کنید', 'arta-consult-rx'),
            'required' => false,
            'class' => array('form-row-wide'),
            'type' => 'textarea',
            'default' => isset($default_values['arta_allergies']) ? $default_values['arta_allergies'] : '',
            'priority' => 33,
        );

        return $fields;
    }

    /**
     * Get checkout field value from user meta
     *
     * @param string $value
     * @param string $input
     * @return string
     */
    public function get_checkout_field_value($value, $input) {
        // Only process our custom fields
        if (strpos($input, 'arta_') !== 0) {
            return $value;
        }

        // Get user meta if user is logged in
        $user_id = get_current_user_id();
        if ($user_id) {
            $user_meta_value = get_user_meta($user_id, $input, true);
            if ($user_meta_value) {
                return $user_meta_value;
            }
        }

        return $value;
    }

    /**
     * Validate checkout fields
     */
    public function validate_checkout_fields() {
        // Validate required fields
        $required_fields = array(
            'arta_gender' => __('جنسیت', 'arta-consult-rx'),
            'arta_birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
            'arta_height' => __('قد', 'arta-consult-rx'),
            'arta_weight' => __('وزن', 'arta-consult-rx'),
        );

        foreach ($required_fields as $field_key => $field_label) {
            if (empty($_POST[$field_key])) {
                wc_add_notice(
                    sprintf(__('%s الزامی است.', 'arta-consult-rx'), $field_label),
                    'error'
                );
            }
        }

        // Validate height and weight are numeric
        if (!empty($_POST['arta_height']) && !is_numeric($_POST['arta_height'])) {
            wc_add_notice(
                __('قد باید یک عدد باشد.', 'arta-consult-rx'),
                'error'
            );
        }

        if (!empty($_POST['arta_weight']) && !is_numeric($_POST['arta_weight'])) {
            wc_add_notice(
                __('وزن باید یک عدد باشد.', 'arta-consult-rx'),
                'error'
            );
        }
    }

    /**
     * Save custom fields to order meta and user meta
     *
     * @param int $order_id
     */
    public function save_checkout_fields($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        $user_id = $order->get_user_id();
        $fields = array(
            'arta_gender',
            'arta_birth_date',
            'arta_height',
            'arta_weight',
            'arta_chronic_diseases',
            'arta_current_medications',
            'arta_medical_history',
            'arta_program_goal',
            'arta_allergies',
        );

        // Fields that are textarea type
        $textarea_fields = array(
            'arta_chronic_diseases',
            'arta_current_medications',
            'arta_medical_history',
            'arta_program_goal',
            'arta_allergies',
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                // For textarea fields, use sanitize_textarea_field
                if (in_array($field, $textarea_fields)) {
                    $value = sanitize_textarea_field($_POST[$field]);
                } else {
                    $value = sanitize_text_field($_POST[$field]);
                }

                // Save to order meta
                $order->update_meta_data($field, $value);
                $order->save();

                // Save to user meta if user is logged in
                if ($user_id) {
                    update_user_meta($user_id, $field, $value);
                }
            }
        }
    }

    /**
     * Display custom fields in admin order details
     *
     * @param WC_Order $order
     */
    public function display_order_fields($order) {
        $fields = array(
            'arta_gender' => __('جنسیت', 'arta-consult-rx'),
            'arta_birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
            'arta_height' => __('قد (سانتی‌متر)', 'arta-consult-rx'),
            'arta_weight' => __('وزن (کیلوگرم)', 'arta-consult-rx'),
            'arta_chronic_diseases' => __('بیماری‌های مزمن', 'arta-consult-rx'),
            'arta_current_medications' => __('داروهای مصرفی فعلی', 'arta-consult-rx'),
            'arta_medical_history' => __('سوابق پزشکی', 'arta-consult-rx'),
            'arta_program_goal' => __('هدف از برنامه', 'arta-consult-rx'),
            'arta_allergies' => __('آلرژی‌ها', 'arta-consult-rx'),
        );

        echo '<div class="address">';
        echo '<p><strong>' . __('اطلاعات پزشکی', 'arta-consult-rx') . '</strong></p>';
        
        foreach ($fields as $field_key => $field_label) {
            $value = $order->get_meta($field_key);
            if ($value) {
                echo '<p><strong>' . esc_html($field_label) . ':</strong> ' . esc_html($value) . '</p>';
            }
        }
        
        echo '</div>';
    }

    /**
     * Display custom fields in order emails
     *
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
    public function display_order_fields_in_email($order, $sent_to_admin, $plain_text) {
        $fields = array(
            'arta_gender' => __('جنسیت', 'arta-consult-rx'),
            'arta_birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
            'arta_height' => __('قد (سانتی‌متر)', 'arta-consult-rx'),
            'arta_weight' => __('وزن (کیلوگرم)', 'arta-consult-rx'),
            'arta_chronic_diseases' => __('بیماری‌های مزمن', 'arta-consult-rx'),
            'arta_current_medications' => __('داروهای مصرفی فعلی', 'arta-consult-rx'),
            'arta_medical_history' => __('سوابق پزشکی', 'arta-consult-rx'),
            'arta_program_goal' => __('هدف از برنامه', 'arta-consult-rx'),
            'arta_allergies' => __('آلرژی‌ها', 'arta-consult-rx'),
        );

        if ($plain_text) {
            echo "\n" . __('اطلاعات پزشکی', 'arta-consult-rx') . "\n";
            echo "--------------------------------\n";
            foreach ($fields as $field_key => $field_label) {
                $value = $order->get_meta($field_key);
                if ($value) {
                    echo esc_html($field_label) . ': ' . esc_html($value) . "\n";
                }
            }
        } else {
            echo '<h2>' . __('اطلاعات پزشکی', 'arta-consult-rx') . '</h2>';
            echo '<ul>';
            foreach ($fields as $field_key => $field_label) {
                $value = $order->get_meta($field_key);
                if ($value) {
                    echo '<li><strong>' . esc_html($field_label) . ':</strong> ' . esc_html($value) . '</li>';
                }
            }
            echo '</ul>';
        }
    }

    /**
     * Display custom fields in order view (frontend)
     *
     * @param WC_Order $order
     */
    public function display_order_fields_frontend($order) {
        $fields = array(
            'arta_gender' => __('جنسیت', 'arta-consult-rx'),
            'arta_birth_date' => __('تاریخ تولد', 'arta-consult-rx'),
            'arta_height' => __('قد (سانتی‌متر)', 'arta-consult-rx'),
            'arta_weight' => __('وزن (کیلوگرم)', 'arta-consult-rx'),
            'arta_chronic_diseases' => __('بیماری‌های مزمن', 'arta-consult-rx'),
            'arta_current_medications' => __('داروهای مصرفی فعلی', 'arta-consult-rx'),
            'arta_medical_history' => __('سوابق پزشکی', 'arta-consult-rx'),
            'arta_program_goal' => __('هدف از برنامه', 'arta-consult-rx'),
            'arta_allergies' => __('آلرژی‌ها', 'arta-consult-rx'),
        );

        echo '<section class="woocommerce-order-details-section">';
        echo '<h2 class="woocommerce-order-details__title">' . __('اطلاعات پزشکی', 'arta-consult-rx') . '</h2>';
        echo '<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">';
        echo '<tbody>';
        
        foreach ($fields as $field_key => $field_label) {
            $value = $order->get_meta($field_key);
            if ($value) {
                echo '<tr>';
                echo '<th>' . esc_html($field_label) . ':</th>';
                echo '<td>' . esc_html($value) . '</td>';
                echo '</tr>';
            }
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</section>';
    }
}

