<?php
/**
 * Product Requests Template
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Product Requests', 'arta-consult-rx'); ?></h1>
    
    <div class="arta-product-requests-list">
        <?php if (!empty($product_requests)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Order ID', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Customer', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Product', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Quantity', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Status', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Date', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Actions', 'arta-consult-rx'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product_requests as $product_request): ?>
                        <?php
                        $product_request_data = $product_request->get_meta('_product_request_data');
                        $product_id = isset($product_request_data['product_id']) ? $product_request_data['product_id'] : 0;
                        $quantity = isset($product_request_data['quantity']) ? $product_request_data['quantity'] : 1;
                        $notes = isset($product_request_data['notes']) ? $product_request_data['notes'] : '';
                        
                        $product_title = '';
                        if ($product_id) {
                            $product = get_post($product_id);
                            if ($product) {
                                $product_title = $product->post_title;
                            }
                        }
                        
                        $customer_name = $product_request->get_billing_first_name() . ' ' . $product_request->get_billing_last_name();
                        $status = $product_request->get_status();
                        $status_label = wc_get_order_status_name($status);
                        ?>
                        <tr>
                            <td>
                                <strong>#<?php echo $product_request->get_id(); ?></strong>
                            </td>
                            <td>
                                <strong><?php echo esc_html($customer_name); ?></strong><br>
                                <small><?php echo esc_html($product_request->get_billing_email()); ?></small>
                            </td>
                            <td>
                                <?php if ($product_title): ?>
                                    <a href="<?php echo get_edit_post_link($product_id); ?>">
                                        <?php echo esc_html($product_title); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="description"><?php _e('Product not found', 'arta-consult-rx'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo esc_html($quantity); ?></strong>
                            </td>
                            <td>
                                <span class="arta-status-badge arta-status-<?php echo esc_attr($status); ?>">
                                    <?php echo esc_html($status_label); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $product_request->get_date_created()->format('Y-m-d H:i:s'); ?>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <span class="view">
                                        <a href="#" class="arta-view-details" data-order-id="<?php echo $product_request->get_id(); ?>">
                                            <?php _e('View', 'arta-consult-rx'); ?>
                                        </a>
                                    </span>
                                    
                                    <?php if ($status === 'wc-product-request-pending'): ?>
                                        <span class="approve">
                                            <a href="#" class="arta-update-status" data-order-id="<?php echo $product_request->get_id(); ?>" data-status="wc-product-approved">
                                                <?php _e('Approve', 'arta-consult-rx'); ?>
                                            </a>
                                        </span>
                                        <span class="reject">
                                            <a href="#" class="arta-update-status" data-order-id="<?php echo $product_request->get_id(); ?>" data-status="wc-product-rejected">
                                                <?php _e('Reject', 'arta-consult-rx'); ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <span class="edit">
                                        <a href="<?php echo get_edit_post_link($product_request->get_id()); ?>">
                                            <?php _e('Edit', 'arta-consult-rx'); ?>
                                        </a>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        
                        <?php if ($notes): ?>
                            <tr class="product-request-notes">
                                <td colspan="7">
                                    <div class="notes-content">
                                        <strong><?php _e('Notes:', 'arta-consult-rx'); ?></strong>
                                        <?php echo esc_html($notes); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="notice notice-info">
                <p><?php _e('No product requests found.', 'arta-consult-rx'); ?></p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Product Request Details Modal -->
    <div id="arta-product-request-modal" class="arta-modal" style="display: none;">
        <div class="arta-modal-content">
            <span class="arta-modal-close">&times;</span>
            <h2><?php _e('Product Request Details', 'arta-consult-rx'); ?></h2>
            <div id="product-request-details-content">
                <!-- Product request details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.arta-product-requests-list {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    overflow: hidden;
}

.arta-product-requests-list table {
    width: 100%;
    border-collapse: collapse;
}

.arta-product-requests-list th,
.arta-product-requests-list td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #f0f0f1;
}

.arta-product-requests-list th {
    background: #f9f9f9;
    font-weight: 600;
    color: #23282d;
}

.arta-product-requests-list tr:hover {
    background: #f9f9f9;
}

.product-request-notes {
    background: #f9f9f9;
}

.notes-content {
    padding: 10px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    margin: 5px 0;
}

.row-actions {
    white-space: nowrap;
}

.row-actions span {
    margin-right: 10px;
}

.row-actions a {
    text-decoration: none;
    color: #0073aa;
}

.row-actions a:hover {
    color: #005a87;
}

.arta-status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.arta-status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.arta-status-approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.arta-status-rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.arta-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
}

.arta-modal-content {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.arta-modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.arta-modal-close:hover {
    color: #333;
}

@media (max-width: 768px) {
    .arta-product-requests-list table {
        font-size: 12px;
    }
    
    .arta-product-requests-list th,
    .arta-product-requests-list td {
        padding: 8px;
    }
    
    .row-actions {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .arta-modal-content {
        width: 95%;
        padding: 20px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Close modal
    $('.arta-modal-close').on('click', function() {
        $('#arta-product-request-modal').hide();
    });
    
    // Close modal on outside click
    $(document).on('click', function(e) {
        if ($(e.target).hasClass('arta-modal')) {
            $('#arta-product-request-modal').hide();
        }
    });
});
</script>
