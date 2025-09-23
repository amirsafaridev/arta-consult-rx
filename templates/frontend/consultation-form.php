<?php
/**
 * Consultation Form Template
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$program_id = isset($atts['program_id']) ? intval($atts['program_id']) : 0;
$doctor_id = isset($atts['doctor_id']) ? intval($atts['doctor_id']) : 0;
?>

<div class="arta-consultation-form">
    <h2><?php _e('Medical Consultation Request', 'arta-consult-rx'); ?></h2>
    
    <form id="arta-consultation-form" method="post">
        <?php wp_nonce_field('arta_consultation_form', 'arta_consultation_nonce'); ?>
        
        <input type="hidden" name="program_id" value="<?php echo esc_attr($program_id); ?>">
        <input type="hidden" name="doctor_id" value="<?php echo esc_attr($doctor_id); ?>">
        
        <!-- Personal Information Section -->
        <div class="arta-form-section">
            <h3><?php _e('Personal Information', 'arta-consult-rx'); ?></h3>
            
            <div class="arta-form-row">
                <div class="arta-form-group">
                    <label for="first_name"><?php _e('First Name', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="arta-form-group">
                    <label for="last_name"><?php _e('Last Name', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="arta-form-row">
                <div class="arta-form-group">
                    <label for="gender"><?php _e('Gender', 'arta-consult-rx'); ?></label>
                    <select id="gender" name="gender">
                        <option value=""><?php _e('Select Gender', 'arta-consult-rx'); ?></option>
                        <option value="male"><?php _e('Male', 'arta-consult-rx'); ?></option>
                        <option value="female"><?php _e('Female', 'arta-consult-rx'); ?></option>
                        <option value="other"><?php _e('Other', 'arta-consult-rx'); ?></option>
                    </select>
                </div>
                
                <div class="arta-form-group">
                    <label for="date_of_birth"><?php _e('Date of Birth', 'arta-consult-rx'); ?></label>
                    <input type="date" id="date_of_birth" name="date_of_birth">
                </div>
            </div>
            
            <div class="arta-form-row">
                <div class="arta-form-group">
                    <label for="height"><?php _e('Height (cm)', 'arta-consult-rx'); ?></label>
                    <input type="number" id="height" name="height" min="100" max="250" step="0.1">
                </div>
                
                <div class="arta-form-group">
                    <label for="weight"><?php _e('Weight (kg)', 'arta-consult-rx'); ?></label>
                    <input type="number" id="weight" name="weight" min="20" max="300" step="0.1">
                </div>
            </div>
            
            <div class="arta-form-row">
                <div class="arta-form-group">
                    <label for="email"><?php _e('Email Address', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="arta-form-group">
                    <label for="phone"><?php _e('Phone Number', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            </div>
        </div>
        
        <!-- Medical Information Section -->
        <div class="arta-form-section">
            <h3><?php _e('Medical Information', 'arta-consult-rx'); ?></h3>
            
            <div class="arta-form-group">
                <label for="chronic_diseases"><?php _e('Chronic Diseases', 'arta-consult-rx'); ?></label>
                <textarea id="chronic_diseases" name="chronic_diseases" rows="3" placeholder="<?php _e('Please list any chronic diseases or conditions', 'arta-consult-rx'); ?>"></textarea>
            </div>
            
            <div class="arta-form-group">
                <label for="current_medications"><?php _e('Current Medications', 'arta-consult-rx'); ?></label>
                <textarea id="current_medications" name="current_medications" rows="3" placeholder="<?php _e('Please list all current medications and dosages', 'arta-consult-rx'); ?>"></textarea>
            </div>
            
            <div class="arta-form-group">
                <label for="medical_history"><?php _e('Medical History', 'arta-consult-rx'); ?></label>
                <textarea id="medical_history" name="medical_history" rows="4" placeholder="<?php _e('Please provide relevant medical history', 'arta-consult-rx'); ?>"></textarea>
            </div>
            
            <div class="arta-form-group">
                <label for="program_objectives"><?php _e('Program Objectives', 'arta-consult-rx'); ?></label>
                <textarea id="program_objectives" name="program_objectives" rows="3" placeholder="<?php _e('What are your goals and objectives for this program?', 'arta-consult-rx'); ?>"></textarea>
            </div>
        </div>
        
        <!-- Consent Section -->
        <div class="arta-form-section">
            <h3><?php _e('Consent and Agreement', 'arta-consult-rx'); ?></h3>
            
            <div class="arta-checkbox-group">
                <input type="checkbox" id="consent" name="consent" required>
                <label for="consent">
                    <?php _e('I consent to medical consultation and agree to the terms and conditions.', 'arta-consult-rx'); ?> <span class="required">*</span>
                </label>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="arta-form-submit">
            <button type="submit" class="arta-btn arta-btn-primary">
                <?php _e('Submit Consultation Request', 'arta-consult-rx'); ?>
            </button>
        </div>
    </form>
</div>
