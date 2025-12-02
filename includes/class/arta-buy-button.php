<?php
/**
 * Arta Buy Button Shortcode Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle buy button shortcode
 */
class Arta_Buy_Button {

    /**
     * Constructor
     */
    public function __construct() {
        // Register shortcode
        add_shortcode('arta_buy_button', array($this, 'buy_button_shortcode'));
        
        // Handle AJAX request
        add_action('wp_ajax_arta_buy_and_checkout', array($this, 'handle_buy_and_checkout'));
        add_action('wp_ajax_nopriv_arta_buy_and_checkout', array($this, 'handle_buy_and_checkout'));
        
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'arta-buy-button',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/css/buy-button.css',
            array(),
            ARTA_CONSULT_RX_VERSION
        );

        wp_enqueue_script(
            'arta-buy-button',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/buy-button.js',
            array('jquery'),
            ARTA_CONSULT_RX_VERSION,
            true
        );

        wp_localize_script('arta-buy-button', 'arta_buy_button', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('arta_buy_button_nonce'),
            'checkout_url' => class_exists('WooCommerce') ? wc_get_checkout_url() : '',
            'processing_text' => __('در حال پردازش...', 'arta-consult-rx'),
            'error_text' => __('خطایی رخ داد', 'arta-consult-rx'),
            'server_error_text' => __('خطا در ارتباط با سرور', 'arta-consult-rx'),
        ));
    }

    /**
     * Buy button shortcode
     * 
     * Usage: [arta_buy_button product_id="123" text="خرید"]
     * 
     * @param array $atts Shortcode attributes
     * @return string Button HTML
     */
    public function buy_button_shortcode($atts) {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return '<p>' . __('WooCommerce is required for this feature.', 'arta-consult-rx') . '</p>';
        }

        // Parse attributes
        $atts = shortcode_atts(array(
            'product_id' => 0,
            'program_id' => 0,
            'text' => __('خرید', 'arta-consult-rx'),
            'class' => 'arta-buy-button',
        ), $atts, 'arta_buy_button');

        // Get product ID
        $product_id = intval($atts['product_id']);
        $program_id = intval($atts['program_id']);

        // If no product_id provided, try to get from current post
        if (empty($product_id) && empty($program_id)) {
            global $post;
            if ($post) {
                if ($post->post_type === 'product') {
                    $product_id = $post->ID;
                } elseif ($post->post_type === 'arta_program') {
                    $program_id = $post->ID;
                }
            }
        }

        // If program_id is provided, get the first related product
        if (!empty($program_id) && empty($product_id)) {
            $related_products = get_post_meta($program_id, '_arta_program_related_products', true);
            if (is_array($related_products) && !empty($related_products)) {
                $product_id = intval($related_products[0]);
            } else {
                return '<p>' . __('هیچ محصولی برای این برنامه تعریف نشده است.', 'arta-consult-rx') . '</p>';
            }
        }

        // Validate product
        if (empty($product_id)) {
            return '<p>' . __('محصولی یافت نشد.', 'arta-consult-rx') . '</p>';
        }

        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) {
            return '<p>' . __('این محصول قابل خرید نیست.', 'arta-consult-rx') . '</p>';
        }

        // Generate button
        $button_text = esc_html($atts['text']);
        $button_class = esc_attr($atts['class']);
        $nonce = wp_create_nonce('arta_buy_button_nonce');

        ob_start();
        ?>
        <div class="arta-buy-button-wrapper">
            <input 
                type="number" 
                class="arta-buy-button-quantity" 
                value="1" 
                min="1" 
                step="1"
                data-product-id="<?php echo esc_attr($product_id); ?>"
            />
            <button 
                type="button" 
                class="<?php echo $button_class; ?>" 
                data-product-id="<?php echo esc_attr($product_id); ?>"
                data-nonce="<?php echo esc_attr($nonce); ?>"
            >
                <?php echo $button_text; ?>
            </button>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Handle buy and checkout AJAX request
     */
    public function handle_buy_and_checkout() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'arta_buy_button_nonce')) {
            wp_send_json_error(array('message' => __('خطای امنیتی', 'arta-consult-rx')));
            return;
        }

        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(array('message' => __('WooCommerce فعال نیست', 'arta-consult-rx')));
            return;
        }

        // Get product ID
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if (empty($product_id)) {
            wp_send_json_error(array('message' => __('محصولی انتخاب نشده است', 'arta-consult-rx')));
            return;
        }

        // Validate product
        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) {
            wp_send_json_error(array('message' => __('این محصول قابل خرید نیست', 'arta-consult-rx')));
            return;
        }

        // Get quantity
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Empty the cart
        WC()->cart->empty_cart();

        // Add product to cart
        $variation_id = 0;
        $variation = array();
        $cart_item_data = array();

        // Handle variable products
        if ($product->is_type('variable')) {
            // Get default variation or first available variation
            $variations = $product->get_available_variations();
            if (!empty($variations)) {
                $variation_id = $variations[0]['variation_id'];
                $variation = $variations[0]['attributes'];
            } else {
                wp_send_json_error(array('message' => __('نوع محصول در دسترس نیست', 'arta-consult-rx')));
                return;
            }
        }

        // Add to cart
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation, $cart_item_data);

        if ($cart_item_key) {
            // Get checkout URL
            $checkout_url = wc_get_checkout_url();
            
            wp_send_json_success(array(
                'message' => __('محصول با موفقیت به سبد خرید اضافه شد', 'arta-consult-rx'),
                'checkout_url' => $checkout_url,
                'redirect' => true
            ));
        } else {
            wp_send_json_error(array('message' => __('خطا در افزودن محصول به سبد خرید', 'arta-consult-rx')));
        }
    }
}

