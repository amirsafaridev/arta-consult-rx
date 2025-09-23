<?php
/**
 * Appointments Calendar Template
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current month and year from URL parameters
$current_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$current_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$selected_doctor = isset($_GET['doctor']) ? intval($_GET['doctor']) : 0;

// Validate month and year
if ($current_month < 1 || $current_month > 12) {
    $current_month = date('n');
}
if ($current_year < 2020 || $current_year > 2030) {
    $current_year = date('Y');
}

// Create date object for current month
$current_date = new DateTime("$current_year-$current_month-01");
$month_name = $current_date->format('F Y');
$days_in_month = $current_date->format('t');
$first_day = $current_date->format('w'); // 0 = Sunday, 1 = Monday, etc.

// Get appointments for the current month
$appointments = array();
if (!empty($appointments_data)) {
    foreach ($appointments_data as $appointment) {
        // Get appointment date from meta or created date
        $appointment_date_meta = $appointment->get_meta('_appointment_date');
        if ($appointment_date_meta) {
            $appointment_date = new DateTime($appointment_date_meta);
        } else {
            $appointment_date = $appointment->get_date_created();
        }
        
        $appointment_month = $appointment_date->format('n');
        $appointment_year = $appointment_date->format('Y');
        
        // Only show appointments for current month/year
        if ($appointment_month == $current_month && $appointment_year == $current_year) {
            // Apply doctor filter if selected
            if ($selected_doctor > 0 && $appointment->get_meta('_doctor_id') != $selected_doctor) {
                continue;
            }
            
            $day = $appointment_date->format('j');
            if (!isset($appointments[$day])) {
                $appointments[$day] = array();
            }
            $appointments[$day][] = $appointment;
        }
    }
}
?>

<div class="wrap">
    <h1><?php _e('Appointments Calendar', 'arta-consult-rx'); ?></h1>
    
  
    
    <!-- Legend -->
    <div class="arta-calendar-legend">
        <h3><?php _e('Legend', 'arta-consult-rx'); ?></h3>
        <div class="legend-items">
            <div class="legend-item">
                <div class="legend-color today"></div>
                <span><?php _e('Today', 'arta-consult-rx'); ?></span>
            </div>
            <div class="legend-item">
                <div class="legend-color has-appointments"></div>
                <span><?php _e('Has Appointments', 'arta-consult-rx'); ?></span>
            </div>
            <div class="legend-item">
                <div class="legend-color scheduled"></div>
                <span><?php _e('Scheduled', 'arta-consult-rx'); ?></span>
            </div>
            <div class="legend-item">
                <div class="legend-color completed"></div>
                <span><?php _e('Completed', 'arta-consult-rx'); ?></span>
            </div>
            <div class="legend-item">
                <div class="legend-color available"></div>
                <span><?php _e('Available', 'arta-consult-rx'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="arta-calendar">
        <div class="arta-calendar-header">
            <div class="arta-calendar-nav">
                <a href="?page=arta-appointments&month=<?php echo $current_month == 1 ? 12 : $current_month - 1; ?>&year=<?php echo $current_month == 1 ? $current_year - 1 : $current_year; ?>&doctor=<?php echo $selected_doctor; ?>" class="button arta-calendar-prev">
                    <?php _e('Previous', 'arta-consult-rx'); ?>
                </a>
                <a href="?page=arta-appointments&month=<?php echo $current_month == 12 ? 1 : $current_month + 1; ?>&year=<?php echo $current_month == 12 ? $current_year + 1 : $current_year; ?>&doctor=<?php echo $selected_doctor; ?>" class="button arta-calendar-next">
                    <?php _e('Next', 'arta-consult-rx'); ?>
                </a>
            </div>
            <div class="arta-calendar-filters">
                <form method="get" class="arta-doctor-filter">
                    <input type="hidden" name="page" value="arta-appointments" />
                    <input type="hidden" name="month" value="<?php echo $current_month; ?>" />
                    <input type="hidden" name="year" value="<?php echo $current_year; ?>" />
                    <label for="doctor-filter"><?php _e('Filter by Doctor:', 'arta-consult-rx'); ?></label>
                    <select name="doctor" id="doctor-filter" onchange="this.form.submit()">
                        <option value="0"><?php _e('All Doctors', 'arta-consult-rx'); ?></option>
                        <?php
                        $doctors = get_posts(array(
                            'post_type' => 'arta_doctor',
                            'posts_per_page' => -1,
                            'post_status' => 'publish',
                            'orderby' => 'title',
                            'order' => 'ASC',
                        ));
                        
                        foreach ($doctors as $doctor) {
                            $selected = ($selected_doctor == $doctor->ID) ? 'selected' : '';
                            echo '<option value="' . $doctor->ID . '" ' . $selected . '>' . esc_html($doctor->post_title) . '</option>';
                        }
                        ?>
                    </select>
                </form>
            </div>
            <h2 id="current-month" data-month="<?php echo $current_month; ?>" data-year="<?php echo $current_year; ?>">
                <?php echo esc_html($month_name); ?>
            </h2>
        </div>
        
        <div class="arta-calendar-grid">
            <!-- Calendar Headers -->
            <div class="arta-calendar-day-header"><?php _e('Sun', 'arta-consult-rx'); ?></div>
            <div class="arta-calendar-day-header"><?php _e('Mon', 'arta-consult-rx'); ?></div>
            <div class="arta-calendar-day-header"><?php _e('Tue', 'arta-consult-rx'); ?></div>
            <div class="arta-calendar-day-header"><?php _e('Wed', 'arta-consult-rx'); ?></div>
            <div class="arta-calendar-day-header"><?php _e('Thu', 'arta-consult-rx'); ?></div>
            <div class="arta-calendar-day-header"><?php _e('Fri', 'arta-consult-rx'); ?></div>
            <div class="arta-calendar-day-header"><?php _e('Sat', 'arta-consult-rx'); ?></div>
            
            <!-- Empty cells for days before the first day of the month -->
            <?php for ($i = 0; $i < $first_day; $i++): ?>
                <div class="arta-calendar-day empty"></div>
            <?php endfor; ?>
            
            <!-- Days of the month -->
            <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                <?php
                $day_class = 'arta-calendar-day';
                $is_today = ($day == date('j') && $current_month == date('n') && $current_year == date('Y'));
                $has_appointments = isset($appointments[$day]) && !empty($appointments[$day]);
                
                if ($is_today) {
                    $day_class .= ' today';
                }
                if ($has_appointments) {
                    $day_class .= ' has-appointments';
                }
                ?>
                <div class="<?php echo esc_attr($day_class); ?>" data-date="<?php echo sprintf('%04d-%02d-%02d', $current_year, $current_month, $day); ?>">
                    <div class="arta-calendar-day-number"><?php echo $day; ?></div>
                    
                    <?php if ($has_appointments): ?>
                        <div class="arta-calendar-appointments">
                            <?php foreach ($appointments[$day] as $appointment): ?>
                                <?php
                                $appointment_data = $appointment->get_meta('_appointment_data');
                                $doctor_id = $appointment->get_meta('_doctor_id');
                                $appointment_time = $appointment->get_meta('_appointment_time');
                                
                                // If no time in meta, try to get from appointment_data
                                if (!$appointment_time && is_array($appointment_data)) {
                                    $appointment_time = isset($appointment_data['appointment_time']) ? $appointment_data['appointment_time'] : '';
                                }
                                
                                $doctor_name = '';
                                if ($doctor_id) {
                                    $doctor = get_post($doctor_id);
                                    if ($doctor) {
                                        $doctor_name = $doctor->post_title;
                                    }
                                }
                                
                                $customer_name = trim($appointment->get_billing_first_name() . ' ' . $appointment->get_billing_last_name());
                                if (empty($customer_name)) {
                                    $customer_name = $appointment->get_billing_company() ?: 'Unknown Customer';
                                }
                                
                                $status = $appointment->get_status();
                                ?>
                                <div class="arta-calendar-appointment" 
                                     data-appointment-id="<?php echo $appointment->get_id(); ?>"
                                     data-status="<?php echo esc_attr($status); ?>"
                                     title="Order #<?php echo $appointment->get_id(); ?> - <?php echo esc_attr($customer_name); ?>">
                                    <div class="appointment-time">
                                        <?php echo $appointment_time ? esc_html(date('H:i', strtotime($appointment_time))) : 'N/A'; ?>
                                    </div>
                                    <div class="appointment-customer">
                                        <?php echo esc_html($customer_name); ?>
                                    </div>
                                    <?php if ($doctor_name): ?>
                                        <div class="appointment-doctor">
                                            Dr. <?php echo esc_html($doctor_name); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    
    <!-- Appointment Details Modal -->
    <div id="arta-appointment-modal" class="arta-modal" style="display: none;">
        <div class="arta-modal-content">
            <span class="arta-modal-close">&times;</span>
            <h2><?php _e('Appointment Details', 'arta-consult-rx'); ?></h2>
            <div id="appointment-details-content">
                <!-- Appointment details will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Appointments List -->
    <div class="arta-appointments-list">
        <h3><?php _e('All Appointments', 'arta-consult-rx'); ?></h3>
        <div class="appointments-table-container">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Order ID', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Date', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Time', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Customer', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Doctor', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Status', 'arta-consult-rx'); ?></th>
                        <th><?php _e('Actions', 'arta-consult-rx'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($appointments_data)): ?>
                        <?php foreach ($appointments_data as $appointment): ?>
                            <?php
                            // Apply doctor filter if selected
                            if ($selected_doctor > 0 && $appointment->get_meta('_doctor_id') != $selected_doctor) {
                                continue;
                            }
                            ?>
                            <?php
                            $appointment_date_meta = $appointment->get_meta('_appointment_date');
                            $appointment_time_meta = $appointment->get_meta('_appointment_time');
                            $doctor_id = $appointment->get_meta('_doctor_id');
                            
                            $appointment_date = $appointment_date_meta ? new DateTime($appointment_date_meta) : $appointment->get_date_created();
                            $appointment_time = $appointment_time_meta ?: 'N/A';
                            
                            $doctor_name = '';
                            if ($doctor_id) {
                                $doctor = get_post($doctor_id);
                                if ($doctor) {
                                    $doctor_name = $doctor->post_title;
                                }
                            }
                            
                            $customer_name = trim($appointment->get_billing_first_name() . ' ' . $appointment->get_billing_last_name());
                            if (empty($customer_name)) {
                                $customer_name = $appointment->get_billing_company() ?: 'Available Slot';
                            }
                            
                            $status = $appointment->get_status();
                            $status_class = '';
                            $status_text = '';
                            
                            // Get the actual database status from slot data
                            $slot_data = $appointment->get_slot_data();
                            $db_status = $slot_data->status;
                            
                            switch ($db_status) {
                                case 'available':
                                    $status_class = 'available';
                                    $status_text = 'Available';
                                    break;
                                case 'booked':
                                    $status_class = 'scheduled';
                                    $status_text = 'Scheduled';
                                    break;
                                case 'completed':
                                    $status_class = 'completed';
                                    $status_text = 'Completed';
                                    break;
                                default:
                                    $status_class = 'pending';
                                    $status_text = 'Pending';
                            }
                            ?>
                            <tr>
                                <td><strong>#<?php echo $appointment->get_id(); ?></strong></td>
                                <td><?php echo $appointment_date->format('Y-m-d'); ?></td>
                                <td><?php echo $appointment_time ? esc_html(date('H:i', strtotime($appointment_time))) : 'N/A'; ?></td>
                                <td><?php echo esc_html($customer_name); ?></td>
                                <td><?php echo $doctor_name ? esc_html($doctor_name) : 'N/A'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="button button-small view-appointment" data-appointment-id="<?php echo $appointment->get_id(); ?>">
                                        <?php _e('View', 'arta-consult-rx'); ?>
                                    </button>
                                    <button class="button button-small edit-appointment" data-appointment-id="<?php echo $appointment->get_id(); ?>">
                                        <?php _e('Edit', 'arta-consult-rx'); ?>
                                    </button>
                                    <button class="button button-small button-link-delete delete-appointment" data-appointment-id="<?php echo $appointment->get_id(); ?>">
                                        <?php _e('Delete', 'arta-consult-rx'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">
                                <?php _e('No appointments found.', 'arta-consult-rx'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.arta-calendar {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
}

.arta-calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f1;
    flex-wrap: wrap;
    gap: 15px;
}

.arta-calendar-nav {
    display: flex;
    gap: 10px;
}

.arta-calendar-filters {
    display: flex;
    align-items: center;
    gap: 10px;
}

.arta-doctor-filter {
    display: flex;
    align-items: center;
    gap: 8px;
}

.arta-doctor-filter label {
    font-weight: 600;
    color: #23282d;
    white-space: nowrap;
}

.arta-doctor-filter select {
    padding: 6px 12px;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    background: #fff;
    font-size: 14px;
    min-width: 150px;
}

.arta-doctor-filter select:focus {
    border-color: #0073aa;
    box-shadow: 0 0 0 1px #0073aa;
    outline: none;
}

.arta-calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #f0f0f1;
    border: 1px solid #f0f0f1;
}

.arta-calendar-day-header {
    background: #f9f9f9;
    font-weight: 600;
    text-align: center;
    padding: 10px;
    color: #23282d;
}

.arta-calendar-day {
    background: #fff;
    padding: 10px;
    min-height: 100px;
    position: relative;
    border: 1px solid #f0f0f1;
}

.arta-calendar-day.empty {
    background: #f9f9f9;
}

.arta-calendar-day.today {
    background: #e3f2fd;
}

.arta-calendar-day.has-appointments {
    background: #fff3e0;
}

.arta-calendar-day-number {
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.arta-calendar-appointments {
    max-height: 80px;
    overflow-y: auto;
}

.arta-calendar-appointment {
    background: #e3f2fd; /* Light blue for default */
    color: #0d47a1;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 11px;
    margin: 2px 0;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border-left: 3px solid #1976d2;
}

.arta-calendar-appointment:hover {
    filter: brightness(0.95);
}

/* Available slots - Light blue */
.arta-calendar-appointment[data-status="available"],
.arta-calendar-appointment[data-status="wc-appointment-available"] {
    background: #e3f2fd;
    color: #0d47a1;
    border-left-color: #1976d2;
}

/* Scheduled slots - Light orange */
.arta-calendar-appointment[data-status="booked"],
.arta-calendar-appointment[data-status="wc-appointment-scheduled"] {
    background: #fff3e0;
    color: #e65100;
    border-left-color: #ff9800;
}

/* Completed slots - Light green */
.arta-calendar-appointment[data-status="completed"],
.arta-calendar-appointment[data-status="wc-appointment-completed"] {
    background: #e8f5e9;
    color: #1b5e20;
    border-left-color: #4caf50;
}

.appointment-time {
    font-weight: 600;
}

.appointment-customer {
    font-size: 10px;
    opacity: 0.9;
}

.appointment-doctor {
    font-size: 10px;
    opacity: 0.8;
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

.arta-calendar-legend {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
}

.arta-calendar-legend h3 {
    margin-top: 0;
    margin-bottom: 15px;
}

.legend-items {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 3px;
    border: 1px solid #ddd;
}

.legend-color.today {
    background: #e3f2fd;
}

.legend-color.has-appointments {
    background: #fff3e0;
}

.legend-color.scheduled {
    background: #0073aa;
}

.legend-color.completed {
    background: #28a745;
}

.legend-color.available {
    background: #e8f5e8;
}

/* Appointments List Styles */
.arta-appointments-list {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
}

.arta-appointments-list h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #23282d;
}

.appointments-table-container {
    overflow-x: auto;
}

.appointments-table-container table {
    width: 100%;
    border-collapse: collapse;
}

.appointments-table-container th,
.appointments-table-container td {
    padding: 12px 8px;
    text-align: left;
    border-bottom: 1px solid #f0f0f1;
}

.appointments-table-container th {
    background: #f9f9f9;
    font-weight: 600;
    color: #23282d;
}

.appointments-table-container tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

/* Scheduled - Light orange */
.status-badge.status-scheduled,
.status-badge.status-booked {
    background: #fff3e0;
    color: #e65100;
    border: 1px solid #ffe0b2;
}

/* Completed - Light green */
.status-badge.status-completed {
    background: #e8f5e9;
    color: #1b5e20;
    border: 1px solid #c8e6c9;
}

/* Pending - Light yellow */
.status-badge.status-pending {
    background: #fffde7;
    color: #f57f17;
    border: 1px solid #fff9c4;
}

/* Available - Light blue */
.status-badge.status-available {
    background: #e3f2fd;
    color: #0d47a1;
    border: 1px solid #bbdefb;
}

.view-appointment,
.edit-appointment,
.delete-appointment {
    margin-right: 5px;
    transition: all 0.2s ease;
    border-radius: 3px;
}

/* View button - Blue */
.view-appointment {
    background: #e3f2fd;
    color: #0d47a1;
    border: 1px solid #bbdefb;
}

.view-appointment:hover {
    background: #bbdefb;
    border-color: #90caf9;
    color: #0d47a1;
}

/* Edit button - Orange */
.edit-appointment {
    background: #fff3e0;
    color: #e65100;
    border: 1px solid #ffe0b2;
}

.edit-appointment:hover {
    background: #ffe0b2;
    border-color: #ffcc80;
    color: #e65100;
}

/* Delete button - Red (softer) */
.delete-appointment {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

.delete-appointment:hover {
    background: #ffcdd2;
    border-color: #ef9a9a;
    color: #c62828;
}

/* Appointment Details Modal Styles */
.appointment-details {
    max-width: 100%;
}

.detail-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
    background-color: #fafafa;
    padding: 15px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.detail-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-section h3 {
    margin: 0 0 15px 0;
    color: #1976d2;
    font-size: 16px;
    font-weight: 500;
    border-bottom: 2px solid #bbdefb;
    padding-bottom: 8px;
    display: inline-block;
}

.detail-section p {
    margin: 8px 0;
    color: #555;
    line-height: 1.5;
}

.detail-section strong {
    color: #333;
    font-weight: 500;
}

.appointment-details .status-badge {
    display: inline-block;
    margin-left: 5px;
}

/* Edit Form Styles */
.appointment-edit-form {
    max-width: 100%;
}

.form-section {
    margin-bottom: 25px;
    background-color: #fafafa;
    padding: 20px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.form-section h3 {
    margin: 0 0 20px 0;
    color: #1976d2;
    font-size: 16px;
    font-weight: 500;
    border-bottom: 2px solid #bbdefb;
    padding-bottom: 8px;
    display: inline-block;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
    font-size: 14px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
    background: #fff;
    transition: all 0.3s ease;
    color: #333;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #90caf9;
    box-shadow: 0 0 0 2px rgba(144, 202, 249, 0.2);
    outline: none;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.form-actions .button {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Save button - Blue */
.form-actions .button-primary {
    background: #e3f2fd;
    color: #0d47a1;
    border: 1px solid #bbdefb;
}

.form-actions .button-primary:hover {
    background: #bbdefb;
    border-color: #90caf9;
}

/* Cancel button - Gray */
.form-actions .button:not(.button-primary) {
    background: #f5f5f5;
    color: #616161;
    border: 1px solid #e0e0e0;
}

.form-actions .button:not(.button-primary):hover {
    background: #eeeeee;
    border-color: #bdbdbd;
}

@media (max-width: 768px) {
    .arta-calendar-header {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .arta-calendar-nav {
        justify-content: center;
    }
    
    .arta-calendar-filters {
        justify-content: center;
    }
    
    .arta-doctor-filter {
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    
    .arta-doctor-filter select {
        min-width: 200px;
    }
    
    .arta-calendar-grid {
        font-size: 12px;
    }
    
    .arta-calendar-day {
        min-height: 80px;
        padding: 5px;
    }
    
    .arta-calendar-appointment {
        font-size: 10px;
        padding: 1px 4px;
    }
    
    .legend-items {
        flex-direction: column;
        gap: 10px;
    }
    
    .arta-modal-content {
        width: 95%;
        padding: 20px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Appointment click handler
    $('.arta-calendar-appointment').on('click', function() {
        var appointmentId = $(this).data('appointment-id');
        if (appointmentId) {
            // Load appointment details via AJAX
            loadAppointmentDetails(appointmentId);
        }
    });
    
    // Close modal
    $('.arta-modal-close').on('click', function() {
        $('#arta-appointment-modal').hide();
    });
    
    // Close modal on outside click
    $(document).on('click', function(e) {
        if ($(e.target).hasClass('arta-modal')) {
            $('#arta-appointment-modal').hide();
        }
    });
    
    function loadAppointmentDetails(appointmentId, editMode) {
        $('#appointment-details-content').html('<p>Loading appointment details...</p>');
        $('#arta-appointment-modal').show();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'arta_get_appointment_details',
                appointment_id: appointmentId,
                nonce: arta_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    var details = response.data;
                    var html = '<div class="appointment-details">';
                    
                    html += '<div class="detail-section">';
                    html += '<h3>Appointment Information</h3>';
                    html += '<p><strong>ID:</strong> #' + details.id + '</p>';
                    html += '<p><strong>Date:</strong> ' + details.slot_date + '</p>';
                    html += '<p><strong>Time:</strong> ' + details.slot_time + '</p>';
                    html += '<p><strong>Duration:</strong> ' + details.duration + ' minutes</p>';
                    var statusText = details.formatted_status;
                    var statusClass = details.status;
                    html += '<p><strong>Status:</strong> <span class="status-badge status-' + statusClass + '">' + statusText + '</span></p>';
                    html += '</div>';
                    
                    html += '<div class="detail-section">';
                    html += '<h3>Doctor Information</h3>';
                    html += '<p><strong>Doctor:</strong> ' + (details.doctor_name || 'N/A') + '</p>';
                    html += '</div>';
                    
                    if (details.order_info) {
                        html += '<div class="detail-section">';
                        html += '<h3>Order Information</h3>';
                        html += '<p><strong>Order ID:</strong> #' + details.order_info.id + '</p>';
                        html += '<p><strong>Status:</strong> ' + details.order_info.status + '</p>';
                        html += '<p><strong>Total:</strong> $' + details.order_info.total + '</p>';
                        html += '<p><strong>Customer:</strong> ' + details.order_info.customer.name + '</p>';
                        html += '<p><strong>Email:</strong> ' + details.order_info.customer.email + '</p>';
                        html += '<p><strong>Phone:</strong> ' + details.order_info.customer.phone + '</p>';
                        html += '<p><strong>Order Date:</strong> ' + details.order_info.date_created + '</p>';
                        html += '</div>';
                    }
                    
                    html += '<div class="detail-section">';
                    html += '<h3>System Information</h3>';
                    html += '<p><strong>Created:</strong> ' + details.created_at + '</p>';
                    html += '<p><strong>Updated:</strong> ' + details.updated_at + '</p>';
                    html += '</div>';
                    
                    html += '</div>';
                    
                    $('#appointment-details-content').html(html);
                } else {
                    $('#appointment-details-content').html('<p class="error">Error: ' + response.data.message + '</p>');
                }
            },
            error: function() {
                $('#appointment-details-content').html('<p class="error">Failed to load appointment details.</p>');
            }
        });
    }
    
    function loadEditForm(appointmentId) {
        $('#appointment-details-content').html('<p>Loading edit form...</p>');
        $('#arta-appointment-modal').show();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'arta_get_appointment_details',
                appointment_id: appointmentId,
                nonce: arta_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    var details = response.data;
                    var html = '<div class="appointment-edit-form">';
                    
                    html += '<form id="edit-appointment-form">';
                    html += '<input type="hidden" name="appointment_id" value="' + details.id + '" />';
                    html += '<input type="hidden" name="nonce" value="' + arta_admin.nonce + '" />';
                    
                    html += '<div class="form-section">';
                    html += '<h3>Edit Appointment</h3>';
                    
                    html += '<div class="form-group">';
                    html += '<label for="edit-doctor">Doctor:</label>';
                    html += '<select name="doctor_id" id="edit-doctor" required>';
                    html += '<option value="">Select Doctor</option>';
                    
                    // Get doctors list
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'arta_get_doctors_list',
                            nonce: arta_admin.nonce
                        },
                        success: function(doctorsResponse) {
                            if (doctorsResponse.success) {
                                var doctorsHtml = '<option value="">Select Doctor</option>';
                                doctorsResponse.data.forEach(function(doctor) {
                                    var selected = (doctor.id == details.doctor_id) ? 'selected' : '';
                                    doctorsHtml += '<option value="' + doctor.id + '" ' + selected + '>' + doctor.name + '</option>';
                                });
                                $('#edit-doctor').html(doctorsHtml);
                            }
                        }
                    });
                    
                    html += '</select>';
                    html += '</div>';
                    
                    html += '<div class="form-group">';
                    html += '<label for="edit-date">Date:</label>';
                    html += '<input type="date" name="slot_date" id="edit-date" value="' + details.slot_date + '" required />';
                    html += '</div>';
                    
                    html += '<div class="form-group">';
                    html += '<label for="edit-time">Time:</label>';
                    html += '<input type="time" name="slot_time" id="edit-time" value="' + details.slot_time + '" required />';
                    html += '</div>';
                    
                    html += '<div class="form-group">';
                    html += '<label for="edit-duration">Duration (minutes):</label>';
                    html += '<input type="number" name="duration" id="edit-duration" value="' + details.duration + '" min="15" step="15" required />';
                    html += '</div>';
                    
                    html += '<div class="form-group">';
                    html += '<label for="edit-status">Status:</label>';
                    html += '<select name="status" id="edit-status" required>';
                    
                    // Use database status directly
                    var currentStatus = details.status;
                    
                    html += '<option value="available"' + (currentStatus == 'available' ? ' selected' : '') + '>Available</option>';
                    html += '<option value="booked"' + (currentStatus == 'booked' ? ' selected' : '') + '>Scheduled</option>';
                    html += '<option value="completed"' + (currentStatus == 'completed' ? ' selected' : '') + '>Completed</option>';
                    html += '</select>';
                    html += '</div>';
                    
                    html += '<div class="form-actions">';
                    html += '<button type="submit" class="button button-primary">Save Changes</button>';
                    html += '<button type="button" class="button cancel-edit">Cancel</button>';
                    html += '</div>';
                    
                    html += '</form>';
                    html += '</div>';
                    
                    $('#appointment-details-content').html(html);
                    
                    // Handle form submission
                    $('#edit-appointment-form').on('submit', function(e) {
                        e.preventDefault();
                        saveAppointmentChanges();
                    });
                    
                    // Handle cancel
                    $('.cancel-edit').on('click', function() {
                        $('#arta-appointment-modal').hide();
                    });
                    
                } else {
                    $('#appointment-details-content').html('<p class="error">Error: ' + response.data.message + '</p>');
                }
            },
            error: function() {
                $('#appointment-details-content').html('<p class="error">Failed to load edit form.</p>');
            }
        });
    }
    
    function saveAppointmentChanges() {
        var formData = $('#edit-appointment-form').serialize();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData + '&action=arta_edit_appointment',
            success: function(response) {
                if (response.success) {
                    alert('Appointment updated successfully!');
                    $('#arta-appointment-modal').hide();
                    location.reload(); // Refresh to show changes
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function() {
                alert('Failed to update appointment. Please try again.');
            }
        });
    }
    
    // View appointment handler
    $(document).on('click', '.view-appointment', function() {
        var appointmentId = $(this).data('appointment-id');
        if (appointmentId) {
            loadAppointmentDetails(appointmentId);
        }
    });
    
    // Edit appointment handler
    $(document).on('click', '.edit-appointment', function() {
        var appointmentId = $(this).data('appointment-id');
        if (appointmentId) {
            loadEditForm(appointmentId);
        }
    });
    
    // Delete appointment handler
    $(document).on('click', '.delete-appointment', function() {
        var appointmentId = $(this).data('appointment-id');
        var $row = $(this).closest('tr');
        
        if (appointmentId && confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'arta_delete_appointment',
                    appointment_id: appointmentId,
                    nonce: arta_admin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() {
                            $(this).remove();
                        });
                        alert('Appointment deleted successfully!');
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function() {
                    alert('Failed to delete appointment. Please try again.');
                }
            });
        }
    });
});
</script>
