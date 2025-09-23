<?php
/**
 * Appointment Booking Template
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$doctor_id = isset($atts['doctor_id']) ? intval($atts['doctor_id']) : 0;
$program_id = isset($atts['program_id']) ? intval($atts['program_id']) : 0;

// Get doctors if not specified
$doctors = get_posts(array(
    'post_type' => 'arta_doctor',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
));
?>

<div class="arta-appointment-booking">
    <h2><?php _e('Book an Appointment', 'arta-consult-rx'); ?></h2>
    
    <form id="appointment-booking-form" method="post">
        <?php wp_nonce_field('arta_appointment_booking', 'arta_appointment_nonce'); ?>
        
        <input type="hidden" name="program_id" value="<?php echo esc_attr($program_id); ?>">
        
        <!-- Doctor Selection -->
        <div class="arta-form-section">
            <h3><?php _e('Select Doctor', 'arta-consult-rx'); ?></h3>
            
            <div class="arta-form-group">
                <label for="doctor-select"><?php _e('Doctor', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                <select id="doctor-select" name="doctor_id" required>
                    <option value=""><?php _e('Select a doctor', 'arta-consult-rx'); ?></option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor->ID; ?>" <?php selected($doctor_id, $doctor->ID); ?>>
                            <?php echo esc_html($doctor->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- Date Selection -->
        <div class="arta-form-section">
            <h3><?php _e('Select Date', 'arta-consult-rx'); ?></h3>
            
            <div class="arta-form-group">
                <label for="appointment-date"><?php _e('Appointment Date', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                <input type="date" id="appointment-date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        
        <!-- Time Selection -->
        <div class="arta-form-section">
            <h3><?php _e('Select Time', 'arta-consult-rx'); ?></h3>
            
            <div id="time-slots">
                <p><?php _e('Please select a doctor and date first.', 'arta-consult-rx'); ?></p>
            </div>
            
            <input type="hidden" id="selected-time" name="appointment_time">
        </div>
        
        <!-- Additional Information -->
        <div class="arta-form-section">
            <h3><?php _e('Additional Information', 'arta-consult-rx'); ?></h3>
            
            <div class="arta-form-group">
                <label for="appointment-notes"><?php _e('Notes', 'arta-consult-rx'); ?></label>
                <textarea id="appointment-notes" name="notes" rows="3" placeholder="<?php _e('Any additional information or special requests', 'arta-consult-rx'); ?>"></textarea>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="arta-form-submit">
            <button type="submit" class="arta-btn arta-btn-primary">
                <?php _e('Book Appointment', 'arta-consult-rx'); ?>
            </button>
        </div>
    </form>
</div>

<!-- Doctor Availability Calendar (Optional) -->
<div id="availability-calendar" style="display: none;">
    <h3><?php _e('Doctor Availability', 'arta-consult-rx'); ?></h3>
    <div class="arta-calendar-container">
        <!-- Calendar will be loaded via AJAX -->
    </div>
</div>
