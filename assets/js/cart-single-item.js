/**
 * Arta Cart Single Item JavaScript
 * 
 * Handle automatic redirect to checkout after adding product to cart
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Handle AJAX add to cart redirect
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            // Check if redirect is needed
            if (fragments && fragments.redirect_url) {
                // Redirect to checkout
                window.location.href = fragments.redirect_url;
                return;
            }
            
            if (fragments && fragments.redirect) {
                // Get checkout URL from localized data
                var checkoutUrl = '';
                
                if (typeof arta_cart_single_item !== 'undefined' && arta_cart_single_item.checkout_url) {
                    checkoutUrl = arta_cart_single_item.checkout_url;
                } else if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.checkout_url) {
                    checkoutUrl = wc_add_to_cart_params.checkout_url;
                } else if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.cart_url) {
                    // Fallback to cart URL if checkout URL not available
                    checkoutUrl = wc_add_to_cart_params.cart_url.replace('/cart/', '/checkout/');
                } else {
                    // Last resort
                    checkoutUrl = '/checkout/';
                }
                
                window.location.href = checkoutUrl;
                return;
            }
        });

        // Also intercept WooCommerce AJAX add to cart
        // This handles cases where the event might not fire properly
        $(document.body).on('wc_fragment_refresh', function() {
             // Check if we need to redirect
             if (typeof arta_cart_single_item !== 'undefined' && arta_cart_single_item.checkout_url) {
                 // Only redirect if we just added to cart - but wc_fragment_refresh fires on page load too
                 // So we need to be careful.
                 // Checking cookies or sessionStorage might be overkill.
                 // Let's rely on added_to_cart for now.
             }
        });
    });

})(jQuery);

