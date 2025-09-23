<?php
/**
 * Programs Shortcode Template
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

if (empty($programs)) {
    echo '<p>' . __('No programs found.', 'arta-consult-rx') . '</p>';
    return;
}
?>

<div class="arta-programs-container">
    <div class="arta-programs-grid">
        <?php foreach ($programs as $program): ?>
            <?php
            $program_id = $program->ID;
            $program_title = $program->post_title;
            $program_excerpt = $program->post_excerpt;
            $program_image = get_the_post_thumbnail_url($program_id, 'medium');
            $program_duration = get_post_meta($program_id, '_program_duration', true);
            $program_price = get_post_meta($program_id, '_program_price', true);
            $associated_doctor = get_post_meta($program_id, '_associated_doctor', true);
            $program_url = get_permalink($program_id);
            
            // Get doctor info
            $doctor_name = '';
            if ($associated_doctor) {
                $doctor = get_post($associated_doctor);
                if ($doctor) {
                    $doctor_name = $doctor->post_title;
                }
            }
            ?>
            
            <div class="arta-program-card">
                <?php if ($program_image): ?>
                    <img src="<?php echo esc_url($program_image); ?>" alt="<?php echo esc_attr($program_title); ?>" class="arta-program-image">
                <?php endif; ?>
                
                <div class="arta-program-content">
                    <h3 class="arta-program-title">
                        <a href="<?php echo esc_url($program_url); ?>"><?php echo esc_html($program_title); ?></a>
                    </h3>
                    
                    <?php if ($program_excerpt): ?>
                        <div class="arta-program-excerpt">
                            <?php echo wp_kses_post($program_excerpt); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="arta-program-meta">
                        <?php if ($program_duration): ?>
                            <div class="arta-program-duration">
                                <span class="dashicons dashicons-clock"></span>
                                <?php printf(_n('%d day', '%d days', $program_duration, 'arta-consult-rx'), $program_duration); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($program_price): ?>
                            <div class="arta-program-price">
                                $<?php echo esc_html(number_format($program_price, 2)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($doctor_name): ?>
                        <div class="arta-program-doctor">
                            <strong><?php _e('Doctor:', 'arta-consult-rx'); ?></strong> <?php echo esc_html($doctor_name); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="arta-program-actions">
                        <a href="<?php echo esc_url($program_url); ?>" class="arta-btn arta-btn-primary">
                            <?php _e('View Details', 'arta-consult-rx'); ?>
                        </a>
                        
                        <a href="<?php echo esc_url($program_url); ?>#consultation-form" class="arta-btn arta-btn-secondary">
                            <?php _e('Request Consultation', 'arta-consult-rx'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
