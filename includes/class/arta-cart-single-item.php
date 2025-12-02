<?php
/**
 * Arta Cart Single Item Class
 * 
 * این کلاس باعث می‌شود که هر زمان محصولی به سبد خرید اضافه می‌شود،
 * محصولات قبلی پاک شوند و فقط همان محصول جدید در سبد خرید باقی بماند.
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle single item cart functionality
 */
class Arta_Cart_Single_Item {

    /**
     * Flag to prevent infinite loop
     *
     * @var bool
     */
    private static $is_clearing = false;

    /**
     * Constructor
     */
    public function __construct() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Hook into WooCommerce add to cart validation
        // This runs before product is added, so we can clear cart first
        add_filter('woocommerce_add_to_cart_validation', array($this, 'clear_cart_on_add'), 10, 3);
        
        // Also handle AJAX add to cart (as backup)
        add_action('woocommerce_ajax_added_to_cart', array($this, 'clear_cart_before_add_ajax'), 10);
        
        // Redirect to checkout after adding to cart (for both AJAX and non-AJAX)
        add_filter('woocommerce_add_to_cart_redirect', array($this, 'redirect_to_checkout'), 10, 1);
        
        // Handle AJAX add to cart redirect
        add_filter('woocommerce_ajax_add_to_cart_fragments', array($this, 'ajax_redirect_to_checkout'), 10, 1);
        
        // Also hook into the AJAX response directly
        add_action('woocommerce_ajax_added_to_cart', array($this, 'set_ajax_redirect'), 20);
        
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Suppress "added to cart" message
        add_filter('wc_add_to_cart_message_html', '__return_false');
    }


    /**
     * Clear cart before adding new product via AJAX
     * 
     * @param int $product_id Product ID
     */
    public function clear_cart_before_add_ajax($product_id) {
        // Prevent infinite loop
        if (self::$is_clearing) {
            return;
        }

        // Check if cart exists
        if (!WC()->cart) {
            return;
        }

        // Get current cart items
        $cart_items = WC()->cart->get_cart();
        
        // If cart is empty, no need to clear
        if (empty($cart_items)) {
            return;
        }

        // Set flag to prevent infinite loop
        self::$is_clearing = true;

        // Find the newly added item (it should be the last one)
        $cart_item_keys = array_keys($cart_items);
        $new_item_key = end($cart_item_keys);

        // Remove all items except the newly added one
        foreach ($cart_items as $key => $item) {
            if ($key !== $new_item_key) {
                WC()->cart->remove_cart_item($key);
            }
        }

        // Reset flag
        self::$is_clearing = false;

        // Recalculate totals
        WC()->cart->calculate_totals();
    }

    /**
     * Clear cart when adding a new product (before validation)
     * 
     * @param bool $passed Validation result
     * @param int $product_id Product ID
     * @param int $quantity Quantity
     * @return bool
     */
    public function clear_cart_on_add($passed, $product_id, $quantity) {
        // Prevent infinite loop
        if (self::$is_clearing) {
            return $passed;
        }

        // Check if cart exists
        if (!WC()->cart) {
            return $passed;
        }

        // Get current cart items
        $cart_items = WC()->cart->get_cart();
        
        // If cart is not empty, clear it before adding new product
        if (!empty($cart_items)) {
            // Set flag to prevent infinite loop
            self::$is_clearing = true;
            
            // Empty the cart
            WC()->cart->empty_cart();
            
            // Reset flag
            self::$is_clearing = false;
        }

        return $passed;
    }

    /**
     * Redirect to checkout page after adding product to cart
     * 
     * @param string $url Redirect URL
     * @return string Checkout URL
     */
    public function redirect_to_checkout($url) {
        // Get checkout URL
        $checkout_url = wc_get_checkout_url();
        
        // Return checkout URL to redirect
        return $checkout_url;
    }

    /**
     * Handle AJAX redirect to checkout
     * 
     * @param array $fragments Fragments array
     * @return array Modified fragments with redirect
     */
    public function ajax_redirect_to_checkout($fragments) {
        // Get checkout URL
        $checkout_url = wc_get_checkout_url();
        
        // Add redirect to fragments
        $fragments['redirect'] = true;
        $fragments['redirect_url'] = $checkout_url;
        
        return $fragments;
    }

    /**
     * Set AJAX redirect after product is added
     * 
     * @param int $product_id Product ID
     */
    public function set_ajax_redirect($product_id) {
        // This is handled by the fragments filter and JavaScript
        // This method is here as a backup
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only enqueue on frontend
        if (is_admin()) {
            return;
        }

        // Enqueue JavaScript for AJAX redirect
        wp_enqueue_script(
            'arta-cart-single-item',
            ARTA_CONSULT_RX_PLUGIN_URL . 'assets/js/cart-single-item.js',
            array('jquery', 'wc-add-to-cart'),
            ARTA_CONSULT_RX_VERSION,
            true
        );
        
        // Localize script with checkout URL
        wp_localize_script('arta-cart-single-item', 'arta_cart_single_item', array(
            'checkout_url' => wc_get_checkout_url(),
            'home_url' => home_url('/')
        ));
    }
}

