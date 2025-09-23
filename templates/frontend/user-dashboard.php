<?php
/**
 * User Dashboard Template
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$user = wp_get_current_user();
?>

<div class="arta-user-dashboard">
    <h2><?php printf(__('Welcome, %s!', 'arta-consult-rx'), $user->display_name); ?></h2>
    
    <!-- Dashboard Tabs -->
    <div class="arta-dashboard-tabs">
        <button class="arta-dashboard-tab active" data-tab="consultations">
            <?php _e('Consultations', 'arta-consult-rx'); ?>
        </button>
        <button class="arta-dashboard-tab" data-tab="appointments">
            <?php _e('Appointments', 'arta-consult-rx'); ?>
        </button>
        <button class="arta-dashboard-tab" data-tab="product-requests">
            <?php _e('Product Requests', 'arta-consult-rx'); ?>
        </button>
    </div>
    
    <!-- Consultations Section -->
    <div class="arta-dashboard-section active" data-section="consultations">
        <div class="arta-dashboard-section-header">
            <h3><?php _e('My Consultations', 'arta-consult-rx'); ?></h3>
            <button class="arta-refresh-data" data-section="consultations">
                <?php _e('Refresh', 'arta-consult-rx'); ?>
            </button>
        </div>
        
        <div class="arta-dashboard-content">
            <?php if (!empty($consultations)): ?>
                <?php foreach ($consultations as $consultation): ?>
                    <div class="arta-dashboard-item">
                        <div class="arta-dashboard-item-header">
                            <div class="arta-dashboard-item-title">
                                <?php printf(__('Consultation #%d', 'arta-consult-rx'), $consultation->get_id()); ?>
                            </div>
                            <div class="arta-dashboard-item-status arta-status-<?php echo esc_attr($consultation->get_status()); ?>">
                                <?php echo esc_html($consultation->get_status()); ?>
                            </div>
                        </div>
                        <div class="arta-dashboard-item-meta">
                            <p><strong><?php _e('Date:', 'arta-consult-rx'); ?></strong> <?php echo $consultation->get_date_created()->format('Y-m-d H:i:s'); ?></p>
                            <p><strong><?php _e('Total:', 'arta-consult-rx'); ?></strong> $<?php echo $consultation->get_total(); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php _e('No consultations found.', 'arta-consult-rx'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Appointments Section -->
    <div class="arta-dashboard-section" data-section="appointments">
        <div class="arta-dashboard-section-header">
            <h3><?php _e('My Appointments', 'arta-consult-rx'); ?></h3>
            <button class="arta-refresh-data" data-section="appointments">
                <?php _e('Refresh', 'arta-consult-rx'); ?>
            </button>
        </div>
        
        <div class="arta-dashboard-content">
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $appointment): ?>
                    <div class="arta-dashboard-item">
                        <div class="arta-dashboard-item-header">
                            <div class="arta-dashboard-item-title">
                                <?php printf(__('Appointment #%d', 'arta-consult-rx'), $appointment->get_id()); ?>
                            </div>
                            <div class="arta-dashboard-item-status arta-status-<?php echo esc_attr($appointment->get_status()); ?>">
                                <?php echo esc_html($appointment->get_status()); ?>
                            </div>
                        </div>
                        <div class="arta-dashboard-item-meta">
                            <p><strong><?php _e('Date:', 'arta-consult-rx'); ?></strong> <?php echo $appointment->get_date_created()->format('Y-m-d H:i:s'); ?></p>
                            <p><strong><?php _e('Total:', 'arta-consult-rx'); ?></strong> $<?php echo $appointment->get_total(); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php _e('No appointments found.', 'arta-consult-rx'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Product Requests Section -->
    <div class="arta-dashboard-section" data-section="product-requests">
        <div class="arta-dashboard-section-header">
            <h3><?php _e('My Product Requests', 'arta-consult-rx'); ?></h3>
            <button class="arta-refresh-data" data-section="product-requests">
                <?php _e('Refresh', 'arta-consult-rx'); ?>
            </button>
        </div>
        
        <div class="arta-dashboard-content">
            <?php if (!empty($product_requests)): ?>
                <?php foreach ($product_requests as $product_request): ?>
                    <div class="arta-dashboard-item">
                        <div class="arta-dashboard-item-header">
                            <div class="arta-dashboard-item-title">
                                <?php printf(__('Product Request #%d', 'arta-consult-rx'), $product_request->get_id()); ?>
                            </div>
                            <div class="arta-dashboard-item-status arta-status-<?php echo esc_attr($product_request->get_status()); ?>">
                                <?php echo esc_html($product_request->get_status()); ?>
                            </div>
                        </div>
                        <div class="arta-dashboard-item-meta">
                            <p><strong><?php _e('Date:', 'arta-consult-rx'); ?></strong> <?php echo $product_request->get_date_created()->format('Y-m-d H:i:s'); ?></p>
                            <p><strong><?php _e('Total:', 'arta-consult-rx'); ?></strong> $<?php echo $product_request->get_total(); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php _e('No product requests found.', 'arta-consult-rx'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.arta-dashboard-tabs {
    display: flex;
    border-bottom: 1px solid #e0e0e0;
    margin-bottom: 30px;
}

.arta-dashboard-tab {
    background: none;
    border: none;
    padding: 15px 20px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.arta-dashboard-tab:hover {
    background: #f9f9f9;
}

.arta-dashboard-tab.active {
    border-bottom-color: #0073aa;
    color: #0073aa;
    font-weight: 600;
}

.arta-dashboard-section {
    display: none;
}

.arta-dashboard-section.active {
    display: block;
}

.arta-dashboard-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.arta-dashboard-section-header h3 {
    margin: 0;
    color: #333;
}

.arta-refresh-data {
    background: #f0f0f0;
    border: 1px solid #ddd;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.arta-refresh-data:hover {
    background: #e0e0e0;
}

.arta-dashboard-content {
    min-height: 200px;
}
</style>
