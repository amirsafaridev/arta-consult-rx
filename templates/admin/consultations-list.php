<?php
/**
 * Consultations List Template
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
    <h1><?php _e('Consultations', 'arta-consult-rx'); ?></h1>
    
    <div class="arta-consultations-list">
        <?php if (!empty($consultations)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Order ID', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Customer', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Program', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Doctor', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Status', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Date', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Actions', 'arta-consult-rx'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultations as $consultation): ?>
                        <?php
                        $consultation_data = $consultation->get_meta('_consultation_data');
                        $program_id = $consultation->get_meta('_program_id');
                        $doctor_id = $consultation->get_meta('_doctor_id');
                        
                        $program_title = '';
                        if ($program_id) {
                            $program = get_post($program_id);
                            if ($program) {
                                $program_title = $program->post_title;
                            }
                        }
                        
                        $doctor_name = '';
                        if ($doctor_id) {
                            $doctor = get_post($doctor_id);
                            if ($doctor) {
                                $doctor_name = $doctor->post_title;
                            }
                        }
                        
                        $customer_name = $consultation->get_billing_first_name() . ' ' . $consultation->get_billing_last_name();
                        $status = $consultation->get_status();
                        $status_label = wc_get_order_status_name($status);
                        ?>
                        <tr>
                            <td>
                                <strong>#<?php echo $consultation->get_id(); ?></strong>
                            </td>
                            <td>
                                <strong><?php echo esc_html($customer_name); ?></strong><br>
                                <small><?php echo esc_html($consultation->get_billing_email()); ?></small>
                            </td>
                            <td>
                                <?php if ($program_title): ?>
                                    <a href="<?php echo get_edit_post_link($program_id); ?>">
                                        <?php echo esc_html($program_title); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="description"><?php _e('No program', 'arta-consult-rx'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($doctor_name): ?>
                                    <a href="<?php echo get_edit_post_link($doctor_id); ?>">
                                        <?php echo esc_html($doctor_name); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="description"><?php _e('No doctor', 'arta-consult-rx'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="arta-status-badge arta-status-<?php echo esc_attr($status); ?>">
                                    <?php echo esc_html($status_label); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $consultation->get_date_created()->format('Y-m-d H:i:s'); ?>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <span class="view">
                                        <a href="#" class="arta-view-details" data-order-id="<?php echo $consultation->get_id(); ?>">
                                            <?php _e('View', 'arta-consult-rx'); ?>
                                        </a>
                                    </span>
                                    
                                    <?php if ($status === 'wc-consultation-pending'): ?>
                                        <span class="approve">
                                            <a href="#" class="arta-update-status" data-order-id="<?php echo $consultation->get_id(); ?>" data-status="wc-consultation-approved">
                                                <?php _e('Approve', 'arta-consult-rx'); ?>
                                            </a>
                                        </span>
                                        <span class="reject">
                                            <a href="#" class="arta-update-status" data-order-id="<?php echo $consultation->get_id(); ?>" data-status="wc-consultation-rejected">
                                                <?php _e('Reject', 'arta-consult-rx'); ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <span class="edit">
                                        <a href="<?php echo get_edit_post_link($consultation->get_id()); ?>">
                                            <?php _e('Edit', 'arta-consult-rx'); ?>
                                        </a>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="notice notice-info">
                <p><?php _e('No consultations found.', 'arta-consult-rx'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.arta-consultations-list {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    overflow: hidden;
}

.arta-consultations-list table {
    width: 100%;
    border-collapse: collapse;
}

.arta-consultations-list th,
.arta-consultations-list td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #f0f0f1;
}

.arta-consultations-list th {
    background: #f9f9f9;
    font-weight: 600;
    color: #23282d;
}

.arta-consultations-list tr:hover {
    background: #f9f9f9;
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

@media (max-width: 768px) {
    .arta-consultations-list table {
        font-size: 12px;
    }
    
    .arta-consultations-list th,
    .arta-consultations-list td {
        padding: 8px;
    }
    
    .row-actions {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
}
</style>
