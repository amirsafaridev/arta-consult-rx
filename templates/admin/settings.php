<?php
/**
 * Admin Settings Template
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
    <div class="arta-settings-header">
        <div class="arta-header-content">
            <div class="arta-header-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.11 3.89 23 5 23H19C20.11 23 21 22.11 21 21V9M19 9H14V4H5V21H19V9Z" fill="#2196f3"/>
                </svg>
            </div>
            <div class="arta-header-text">
                <h1><?php _e('Arta Consult RX Settings', 'arta-consult-rx'); ?></h1>
                <p class="description"><?php _e('Configure your consultation and appointment system settings to optimize your medical practice workflow.', 'arta-consult-rx'); ?></p>
            </div>
        </div>
        <div class="arta-header-actions">
            <div class="arta-status-indicator">
                <span class="arta-status-dot arta-status-active"></span>
                <span class="arta-status-text"><?php _e('System Active', 'arta-consult-rx'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="arta-settings-layout">
        <div class="arta-settings-main">
            <form method="post" action="" class="arta-settings-form">
                <?php wp_nonce_field('arta_save_settings', 'arta_settings_nonce'); ?>
                
                <div class="arta-settings-section">
                    <div class="arta-section-header">
                        <h2><?php _e('General Settings', 'arta-consult-rx'); ?></h2>
                        <p class="arta-section-description"><?php _e('Configure the basic settings for your consultation and appointment system.', 'arta-consult-rx'); ?></p>
                    </div>
                    
                    <div class="arta-form-grid">
                        <div class="arta-form-group">
                            <label for="consultation_duration" class="arta-form-label">
                                <span class="arta-label-icon">⏱️</span>
                                <?php _e('Default Consultation Duration', 'arta-consult-rx'); ?>
                            </label>
                            <div class="arta-form-field">
                                <input type="number" id="consultation_duration" name="consultation_duration" 
                                       value="<?php echo esc_attr(get_option('arta_consult_rx_consultation_duration', 30)); ?>" 
                                       min="15" step="15" class="arta-input" />
                                <span class="arta-input-suffix"><?php _e('minutes', 'arta-consult-rx'); ?></span>
                            </div>
                            <p class="arta-form-description"><?php _e('Default duration for consultation sessions.', 'arta-consult-rx'); ?></p>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="appointment_duration" class="arta-form-label">
                                <span class="arta-label-icon">📅</span>
                                <?php _e('Default Appointment Duration', 'arta-consult-rx'); ?>
                            </label>
                            <div class="arta-form-field">
                                <input type="number" id="appointment_duration" name="appointment_duration" 
                                       value="<?php echo esc_attr(get_option('arta_consult_rx_appointment_duration', 30)); ?>" 
                                       min="15" step="15" class="arta-input" />
                                <span class="arta-input-suffix"><?php _e('minutes', 'arta-consult-rx'); ?></span>
                            </div>
                            <p class="arta-form-description"><?php _e('Default duration for appointment sessions.', 'arta-consult-rx'); ?></p>
                        </div>
                        
                        <div class="arta-form-group">
                            <label for="appointment_advance_days" class="arta-form-label">
                                <span class="arta-label-icon">📆</span>
                                <?php _e('Appointment Advance Booking', 'arta-consult-rx'); ?>
                            </label>
                            <div class="arta-form-field">
                                <input type="number" id="appointment_advance_days" name="appointment_advance_days" 
                                       value="<?php echo esc_attr(get_option('arta_consult_rx_appointment_advance_days', 30)); ?>" 
                                       min="1" class="arta-input" />
                                <span class="arta-input-suffix"><?php _e('days', 'arta-consult-rx'); ?></span>
                            </div>
                            <p class="arta-form-description"><?php _e('How many days in advance can appointments be booked.', 'arta-consult-rx'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="arta-settings-actions">
                    <?php submit_button(__('Save Settings', 'arta-consult-rx'), 'primary', 'submit', false, array('class' => 'arta-button arta-button-primary')); ?>
                </div>
            </form>
        </div>
        
        <div class="arta-settings-sidebar">
            <div class="arta-settings-info">
                <div class="arta-info-header">
                    <h3><?php _e('System Information', 'arta-consult-rx'); ?></h3>
                    <p><?php _e('Current system status and version information.', 'arta-consult-rx'); ?></p>
                </div>
                
                <div class="arta-info-list">
                    <div class="arta-info-item">
                        <div class="arta-info-icon">🔧</div>
                        <div class="arta-info-content">
                            <div class="arta-info-label"><?php _e('Plugin Version', 'arta-consult-rx'); ?></div>
                            <div class="arta-info-value"><?php echo esc_html(ARTA_CONSULT_RX_VERSION); ?></div>
                        </div>
                    </div>
                    
                    <div class="arta-info-item">
                        <div class="arta-info-icon">🌐</div>
                        <div class="arta-info-content">
                            <div class="arta-info-label"><?php _e('WordPress Version', 'arta-consult-rx'); ?></div>
                            <div class="arta-info-value"><?php echo esc_html(get_bloginfo('version')); ?></div>
                        </div>
                    </div>
                    
                    <div class="arta-info-item">
                        <div class="arta-info-icon">🛒</div>
                        <div class="arta-info-content">
                            <div class="arta-info-label"><?php _e('WooCommerce', 'arta-consult-rx'); ?></div>
                            <div class="arta-info-value">
                                <?php 
                                if (class_exists('WooCommerce')) {
                                    echo '<span class="arta-status arta-status-success">' . esc_html(WC()->version) . '</span>';
                                } else {
                                    echo '<span class="arta-status arta-status-error">' . __('Not installed', 'arta-consult-rx') . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="arta-info-item">
                        <div class="arta-info-icon">🌍</div>
                        <div class="arta-info-content">
                            <div class="arta-info-label"><?php _e('WPML', 'arta-consult-rx'); ?></div>
                            <div class="arta-info-value">
                                <?php 
                                if (defined('ICL_SITEPRESS_VERSION')) {
                                    echo '<span class="arta-status arta-status-success">' . __('Active', 'arta-consult-rx') . '</span>';
                                } else {
                                    echo '<span class="arta-status arta-status-warning">' . __('Not active', 'arta-consult-rx') . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Material Design Settings Styles */
.wrap {
    max-width: none !important;
    margin: 0 !important;
    padding: 0 !important;
}

.arta-settings-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 32px;
    margin: 20px 20px 32px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.arta-header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.arta-header-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.arta-header-icon svg {
    filter: brightness(0) invert(1);
}

.arta-header-text h1 {
    margin: 0 0 8px 0;
    color: #424242;
    font-size: 32px;
    font-weight: 600;
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
    line-height: 1.2;
}

.arta-header-text .description {
    margin: 0;
    color: #616161;
    font-size: 16px;
    line-height: 1.5;
    max-width: 600px;
}

.arta-header-actions {
    display: flex;
    align-items: center;
}

.arta-status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: #e8f5e8;
    border: 1px solid #c8e6c9;
    border-radius: 8px;
}

.arta-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #4caf50;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.arta-status-text {
    color: #2e7d32;
    font-size: 14px;
    font-weight: 500;
}

.arta-settings-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 24px;
    margin: 0 20px 20px 20px;
}

.arta-settings-main {
    min-width: 0;
}

.arta-settings-form {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.arta-settings-section {
    margin-bottom: 0;
}

.arta-section-header {
    margin-bottom: 32px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f5f5f5;
}

.arta-section-header h2 {
    margin: 0 0 8px 0;
    color: #424242;
    font-size: 24px;
    font-weight: 600;
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
}

.arta-section-description {
    margin: 0;
    color: #616161;
    font-size: 15px;
    line-height: 1.5;
}

.arta-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 32px;
}

.arta-form-group {
    margin-bottom: 0;
}

.arta-form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    color: #424242;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.4;
}

.arta-label-icon {
    font-size: 18px;
}

.arta-form-field {
    position: relative;
    display: flex;
    align-items: center;
    max-width: 100%;
}

.arta-input {
    padding: 16px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    background: #ffffff;
    width: 100%;
    transition: all 0.3s ease;
    font-family: inherit;
}

.arta-input:focus {
    border-color: #2196f3;
    box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
    outline: none;
    transform: translateY(-1px);
}

.arta-input-suffix {
    margin-left: 12px;
    color: #616161;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.arta-form-description {
    margin: 12px 0 0 0;
    color: #616161;
    font-size: 14px;
    line-height: 1.5;
}

.arta-settings-actions {
    margin-top: 40px;
    padding-top: 24px;
    border-top: 2px solid #f5f5f5;
    display: flex;
    justify-content: flex-end;
}

.arta-button {
    padding: 16px 32px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: none;
    letter-spacing: 0.5px;
    font-family: inherit;
    min-width: 160px;
}

.arta-button-primary {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.arta-button-primary:hover {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    box-shadow: 0 6px 16px rgba(33, 150, 243, 0.4);
    transform: translateY(-2px);
}

.arta-button-primary:active {
    transform: translateY(0);
}

/* Sidebar */
.arta-settings-sidebar {
    min-width: 320px;
}

.arta-settings-info {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    position: sticky;
    top: 20px;
}

.arta-info-header {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f5f5f5;
}

.arta-info-header h3 {
    margin: 0 0 8px 0;
    color: #424242;
    font-size: 20px;
    font-weight: 600;
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
}

.arta-info-header p {
    margin: 0;
    color: #616161;
    font-size: 14px;
    line-height: 1.5;
}

.arta-info-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.arta-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.arta-info-item:hover {
    background: #e3f2fd;
    border-color: #bbdefb;
    transform: translateY(-1px);
}

.arta-info-icon {
    font-size: 20px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    flex-shrink: 0;
}

.arta-info-content {
    flex: 1;
    min-width: 0;
}

.arta-info-label {
    color: #616161;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.arta-info-value {
    color: #424242;
    font-size: 14px;
    font-weight: 600;
    word-break: break-word;
}

.arta-status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.arta-status-success {
    background: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.arta-status-error {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

.arta-status-warning {
    background: #fff3e0;
    color: #ef6c00;
    border: 1px solid #ffcc02;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .arta-settings-layout {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .arta-settings-sidebar {
        min-width: auto;
    }
    
    .arta-settings-info {
        position: static;
    }
}

@media (max-width: 768px) {
    .arta-settings-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        padding: 24px;
        margin: 10px;
    }
    
    .arta-header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .arta-header-icon {
        width: 48px;
        height: 48px;
    }
    
    .arta-header-text h1 {
        font-size: 28px;
    }
    
    .arta-settings-layout {
        margin: 0 10px 10px 10px;
        gap: 16px;
    }
    
    .arta-settings-form {
        padding: 24px;
    }
    
    .arta-form-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .arta-settings-actions {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .arta-settings-header {
        padding: 20px;
        margin: 5px;
    }
    
    .arta-header-text h1 {
        font-size: 24px;
    }
    
    .arta-settings-layout {
        margin: 0 5px 5px 5px;
    }
    
    .arta-settings-form {
        padding: 20px;
    }
    
    .arta-settings-info {
        padding: 20px;
    }
    
    .arta-button {
        width: 100%;
        min-width: auto;
    }
}
</style>
