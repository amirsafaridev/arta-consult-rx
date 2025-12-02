/**
 * Arta Buy Button JavaScript
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Handle buy button click
        $(document).on('click', '.arta-buy-button', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $wrapper = $button.closest('.arta-buy-button-wrapper');
            var productId = $button.data('product-id');
            var nonce = $button.data('nonce');
            
            // Get quantity from input field
            var quantity = 1;
            if ($wrapper.length) {
                var $quantityInput = $wrapper.find('.arta-buy-button-quantity');
                if ($quantityInput.length) {
                    quantity = parseInt($quantityInput.val()) || 1;
                    if (quantity < 1) {
                        quantity = 1;
                    }
                }
            }

            // Disable button to prevent double clicks
            $button.prop('disabled', true);
            var originalText = $button.html();
            $button.html(arta_buy_button.processing_text || 'در حال پردازش...');

            // Make AJAX request
            $.ajax({
                url: arta_buy_button.ajax_url,
                type: 'POST',
                data: {
                    action: 'arta_buy_and_checkout',
                    product_id: productId,
                    quantity: quantity,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Redirect to checkout
                        if (response.data.redirect && response.data.checkout_url) {
                            window.location.href = response.data.checkout_url;
                        } else {
                            // Fallback: reload page to show updated cart
                            window.location.reload();
                        }
                    } else {
                        // Show error message
                        alert(response.data.message || (arta_buy_button.error_text || 'خطایی رخ داد'));
                        $button.prop('disabled', false);
                        $button.html(originalText);
                    }
                },
                error: function() {
                    alert(arta_buy_button.server_error_text || 'خطا در ارتباط با سرور');
                    $button.prop('disabled', false);
                    $button.html(originalText);
                }
            });
        });
    });

})(jQuery);

