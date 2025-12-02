<?php
/**
 * Arta Checkout WhatsApp Button Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle WhatsApp consultation button in checkout
 */
class Arta_Checkout_WhatsApp_Button {

    /**
     * Constructor
     */
    public function __construct() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Register shortcode
        add_shortcode('arta_whatsapp_consultation_button', array($this, 'whatsapp_button_shortcode'));

        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only enqueue on checkout page
        if (!is_checkout()) {
            return;
        }

        wp_enqueue_script(
            'arta-checkout-whatsapp',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/checkout-whatsapp.js',
            array('jquery'),
            ARTA_CONSULT_RX_VERSION,
            true
        );

        wp_localize_script('arta-checkout-whatsapp', 'arta_whatsapp', array(
            'required_fields_error' => __('لطفاً تمام فیلدهای اجباری را پر کنید', 'arta-consult-rx'),
        ));
    }

    /**
     * WhatsApp button shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function whatsapp_button_shortcode($atts) {
        // Only show on checkout page
        if (!is_checkout()) {
            return '';
        }

        // Parse attributes
        $atts = shortcode_atts(array(
            'phone' => '989045605166',
            'text' => __('درخواست مشاوره دارم', 'arta-consult-rx'),
            'button_text' => __('شروع مشاوره', 'arta-consult-rx'),
            'class' => 'arta-whatsapp-consultation-button button alt',
        ), $atts, 'arta_whatsapp_consultation_button');

        // Sanitize phone number (remove any non-numeric characters except +)
        $phone = preg_replace('/[^0-9+]/', '', $atts['phone']);
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        $button_text = esc_html($atts['button_text']);
        $button_class = esc_attr($atts['class']);
        $initial_text = esc_attr($atts['text']);

        ob_start();
        ?>
        <button 
            type="button" 
            class="<?php echo $button_class; ?>" 
            data-phone="<?php echo esc_attr($phone); ?>"
            data-initial-text="<?php echo $initial_text; ?>"
        >
            <?php echo $button_text; ?>
        </button>
        <?php
        return ob_get_clean();
    }

    /**
     * Get checkout field labels
     *
     * @return array
     */
    public static function get_checkout_field_labels() {
        return array(
            'billing_first_name' => __('نام', 'arta-consult-rx'),
            'billing_last_name' => __('نام خانوادگی', 'arta-consult-rx'),
            'billing_company' => __('نام شرکت', 'arta-consult-rx'),
            'billing_country' => __('کشور', 'arta-consult-rx'),
            'billing_address_1' => __('آدرس', 'arta-consult-rx'),
            'billing_address_2' => __('آدرس (خط دوم)', 'arta-consult-rx'),
            'billing_city' => __('شهر', 'arta-consult-rx'),
            'billing_state' => __('استان', 'arta-consult-rx'),
            'billing_postcode' => __('کد پستی', 'arta-consult-rx'),
            'billing_phone' => __('شماره تماس', 'arta-consult-rx'),
            'billing_email' => __('ایمیل', 'arta-consult-rx'),
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
    }

    /**
     * Get required checkout fields
     *
     * @return array
     */
    public static function get_required_fields() {
        return array(
            'billing_first_name',
            'billing_last_name',
            'billing_address_1',
            'billing_city',
            'billing_postcode',
            'billing_phone',
            'billing_email',
            'arta_gender',
            'arta_birth_date',
            'arta_height',
            'arta_weight',
        );
    }
}



