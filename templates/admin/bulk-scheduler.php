<?php
/**
 * Bulk Scheduler Template
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
    <h1><?php _e('Bulk Appointment Scheduler', 'arta-consult-rx'); ?></h1>
    
    <div class="arta-bulk-scheduler">
        <form id="bulk-scheduler-form">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="doctor_id"><?php _e('Doctor', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <select id="doctor_id" name="doctor_id" required style="width: 100%;">
                            <option value=""><?php _e('Select a doctor', 'arta-consult-rx'); ?></option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo $doctor->ID; ?>">
                                    <?php echo esc_html($doctor->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="start_date"><?php _e('Start Date', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="end_date"><?php _e('End Date', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="start_time"><?php _e('Start Time', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="time" id="start_time" name="start_time" required>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="end_time"><?php _e('End Time', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="time" id="end_time" name="end_time" required>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="interval"><?php _e('Interval (minutes)', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <select id="interval" name="interval" required>
                            <option value="15">15 <?php _e('minutes', 'arta-consult-rx'); ?></option>
                            <option value="30" selected>30 <?php _e('minutes', 'arta-consult-rx'); ?></option>
                            <option value="60">1 <?php _e('hour', 'arta-consult-rx'); ?></option>
                            <option value="90">1.5 <?php _e('hours', 'arta-consult-rx'); ?></option>
                            <option value="120">2 <?php _e('hours', 'arta-consult-rx'); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label><?php _e('Days of Week', 'arta-consult-rx'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <div class="days-of-week">
                            <label>
                                <input type="checkbox" name="days_of_week[]" value="1">
                                <?php _e('Monday', 'arta-consult-rx'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="days_of_week[]" value="2">
                                <?php _e('Tuesday', 'arta-consult-rx'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="days_of_week[]" value="3">
                                <?php _e('Wednesday', 'arta-consult-rx'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="days_of_week[]" value="4">
                                <?php _e('Thursday', 'arta-consult-rx'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="days_of_week[]" value="5">
                                <?php _e('Friday', 'arta-consult-rx'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="days_of_week[]" value="6">
                                <?php _e('Saturday', 'arta-consult-rx'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="days_of_week[]" value="0">
                                <?php _e('Sunday', 'arta-consult-rx'); ?>
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
            
            <div class="bulk-actions">
                <button type="button" id="bulk-create-slots" class="button button-primary">
                    <?php _e('Create Slots', 'arta-consult-rx'); ?>
                </button>
                
                <button type="button" id="bulk-delete-slots" class="button button-secondary">
                    <?php _e('Delete Slots', 'arta-consult-rx'); ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Preview Section -->
    <div id="bulk-preview" style="display: none;">
        <h2><?php _e('Preview', 'arta-consult-rx'); ?></h2>
        <div id="preview-content">
            <!-- Preview content will be loaded here -->
        </div>
    </div>
    
    <!-- Instructions -->
    <div class="arta-bulk-instructions">
        <h2><?php _e('Instructions', 'arta-consult-rx'); ?></h2>
        <ol>
            <li><?php _e('Select a doctor from the dropdown list.', 'arta-consult-rx'); ?></li>
            <li><?php _e('Choose the date range for creating appointment slots.', 'arta-consult-rx'); ?></li>
            <li><?php _e('Set the time range for daily availability.', 'arta-consult-rx'); ?></li>
            <li><?php _e('Select the interval between appointment slots.', 'arta-consult-rx'); ?></li>
            <li><?php _e('Choose which days of the week to create slots for.', 'arta-consult-rx'); ?></li>
            <li><?php _e('Click "Create Slots" to generate the appointment slots.', 'arta-consult-rx'); ?></li>
        </ol>
        
        <div class="notice notice-info">
            <p>
                <strong><?php _e('Note:', 'arta-consult-rx'); ?></strong>
                <?php _e('Creating slots for a large date range may take some time. Please be patient.', 'arta-consult-rx'); ?>
            </p>
        </div>
    </div>
</div>

<style>
/* Clean Material Design Styles */
.arta-bulk-scheduler {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 24px;
    margin: 20px 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
}

.arta-bulk-scheduler .form-table {
    margin-top: 0;
    border: none;
}

.arta-bulk-scheduler .form-table th {
    width: 180px;
    padding: 16px 0;
    font-weight: 500;
    color: #424242;
    font-size: 14px;
    vertical-align: top;
}

.arta-bulk-scheduler .form-table td {
    padding: 16px 0;
    vertical-align: top;
}

.arta-bulk-scheduler .form-table tr {
    border-bottom: 1px solid #f5f5f5;
}

.arta-bulk-scheduler .form-table tr:last-child {
    border-bottom: none;
}

/* Input Fields */
.arta-bulk-scheduler input[type="text"],
.arta-bulk-scheduler input[type="date"],
.arta-bulk-scheduler input[type="time"],
.arta-bulk-scheduler select {
    padding: 12px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
    background: #ffffff;
    width: 100%;
    max-width: 280px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.arta-bulk-scheduler input[type="text"]:focus,
.arta-bulk-scheduler input[type="date"]:focus,
.arta-bulk-scheduler input[type="time"]:focus,
.arta-bulk-scheduler select:focus {
    border-color: #2196f3;
    box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.2);
    outline: none;
}

/* Days of Week */
.arta-bulk-scheduler .days-of-week {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.arta-bulk-scheduler .days-of-week label {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 400;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.arta-bulk-scheduler .days-of-week label:hover {
    background: #e3f2fd;
    border-color: #bbdefb;
    color: #1976d2;
}

.arta-bulk-scheduler .days-of-week input[type="checkbox"] {
    margin: 0;
    width: 16px;
    height: 16px;
    accent-color: #2196f3;
}

.arta-bulk-scheduler .days-of-week input[type="checkbox"]:checked + span {
    color: #1976d2;
    font-weight: 500;
}

.arta-bulk-scheduler .days-of-week label:has(input:checked) {
    background: #e8f5e8;
    border-color: #c8e6c9;
    color: #2e7d32;
}

/* Buttons */
.bulk-actions {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    gap: 12px;
}

.bulk-actions .button {
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: none;
    letter-spacing: 0.5px;
}

.bulk-actions .button-primary {
    background: #2196f3;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(33, 150, 243, 0.3);
}

.bulk-actions .button-primary:hover {
    background: #1976d2;
    box-shadow: 0 4px 8px rgba(33, 150, 243, 0.4);
    transform: translateY(-1px);
}

.bulk-actions .button-secondary {
    background: #f5f5f5;
    color: #424242;
    border: 1px solid #e0e0e0;
}

.bulk-actions .button-secondary:hover {
    background: #eeeeee;
    border-color: #bdbdbd;
    transform: translateY(-1px);
}

.bulk-actions .button:active {
    transform: translateY(0);
}

/* Instructions */
.arta-bulk-instructions {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.arta-bulk-instructions h2 {
    margin-top: 0;
    margin-bottom: 16px;
    color: #424242;
    font-size: 18px;
    font-weight: 500;
}

.arta-bulk-instructions ol {
    margin: 0;
    padding-left: 20px;
}

.arta-bulk-instructions li {
    margin: 8px 0;
    color: #616161;
    font-size: 14px;
    line-height: 1.5;
}

/* Preview */
#bulk-preview {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

#bulk-preview h4 {
    margin-top: 0;
    margin-bottom: 16px;
    color: #424242;
    font-size: 16px;
    font-weight: 500;
}

#preview-content {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 16px;
    background: #fafafa;
}

.preview-slots {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.preview-slot {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 12px;
    text-align: center;
    font-size: 13px;
    font-weight: 400;
    color: #424242;
    transition: all 0.2s ease;
}

.preview-slot.available {
    background: #e8f5e8;
    border-color: #c8e6c9;
    color: #2e7d32;
}

.preview-slot.booked {
    background: #ffebee;
    border-color: #ffcdd2;
    color: #c62828;
}

.preview-slot:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

/* Loading States */
.button.loading {
    position: relative;
    color: transparent !important;
    pointer-events: none;
}

.button.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Messages */
.arta-message {
    padding: 12px 16px;
    border-radius: 4px;
    margin: 16px 0;
    font-size: 14px;
    font-weight: 400;
    display: flex;
    align-items: center;
    gap: 8px;
}

.arta-message.success {
    background: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.arta-message.error {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

/* Responsive */
@media (max-width: 768px) {
    .arta-bulk-scheduler {
        padding: 16px;
        margin: 10px;
    }
    
    .arta-bulk-scheduler .form-table th,
    .arta-bulk-scheduler .form-table td {
        display: block;
        width: 100%;
        padding: 12px 0;
    }
    
    .arta-bulk-scheduler .form-table th {
        font-weight: 500;
        margin-bottom: 4px;
    }
    
    .arta-bulk-scheduler .days-of-week {
        flex-direction: column;
        gap: 6px;
    }
    
    .bulk-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .bulk-actions .button {
        width: 100%;
    }
    
    .preview-slots {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .arta-bulk-scheduler {
        padding: 12px;
        margin: 5px;
    }
    
    .arta-bulk-scheduler .form-table th,
    .arta-bulk-scheduler .form-table td {
        padding: 8px 0;
    }
    
    .bulk-actions {
        margin-top: 16px;
        padding-top: 16px;
    }
}
</style>

<script>
console.log('Bulk Scheduler template loaded');
</script>
