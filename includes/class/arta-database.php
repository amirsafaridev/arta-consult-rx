<?php
/**
 * Database Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle database operations
 */
class Arta_Database {

    /**
     * Constructor
     */
    public function __construct() {
        register_activation_hook(ARTA_CONSULT_RX_PLUGIN_FILE, array($this, 'create_tables'));
        add_action('wp_loaded', array($this, 'maybe_create_tables'));
    }

    /**
     * Create database tables
     */
    public function create_tables() {
        $this->create_appointments_table();
        $this->update_existing_table();
    }

    /**
     * Update existing table structure
     */
    private function update_existing_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointments';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return;
        }
        
       
        $this->create_appointments_table();
    }

    /**
     * Maybe create tables if they don't exist
     */
    public function maybe_create_tables() {
        if (get_option('arta_consult_rx_db_version') !== ARTA_CONSULT_RX_VERSION) {
            $this->create_tables();
            update_option('arta_consult_rx_db_version', ARTA_CONSULT_RX_VERSION);
        }
    }

    /**
     * Create appointments table
     */
    private function create_appointments_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'arta_appointments';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            doctor_id bigint(20) NOT NULL,
            appointment_date date NOT NULL,
            appointment_time time NOT NULL,
            status varchar(20) DEFAULT 'available',
            patient_id bigint(20) DEFAULT NULL,
            patient_name varchar(255) DEFAULT NULL,
            patient_phone varchar(20) DEFAULT NULL,
            patient_email varchar(100) DEFAULT NULL,
            notes text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY doctor_id (doctor_id),
            KEY appointment_date (appointment_date),
            KEY status (status),
            KEY patient_id (patient_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Get appointments
     *
     * @param array $args
     * @return array
     */
    public static function get_appointments($args = array()) {
        global $wpdb;

        $defaults = array(
            'doctor_id' => null,
            'date' => null,
            'month' => null,
            'status' => null,
            'patient_id' => null,
            'limit' => -1,
            'offset' => 0,
            'orderby' => 'appointment_date',
            'order' => 'ASC'
        );

        $args = wp_parse_args($args, $defaults);

        $table_name = $wpdb->prefix . 'arta_appointments';
        $where_conditions = array('1=1');
        $where_values = array();

        if ($args['doctor_id'] && $args['doctor_id'] > 0) {
            $where_conditions[] = 'doctor_id = %d';
            $where_values[] = $args['doctor_id'];
        }

        if ($args['date']) {
            $where_conditions[] = 'appointment_date = %s';
            $where_values[] = $args['date'];
        }

        if ($args['month']) {
            $where_conditions[] = 'appointment_date LIKE %s';
            $where_values[] = $args['month'] . '%';
        }

        if ($args['status']) {
            $where_conditions[] = 'status = %s';
            $where_values[] = $args['status'];
        }

        if ($args['patient_id']) {
            $where_conditions[] = 'patient_id = %d';
            $where_values[] = $args['patient_id'];
        }

        $where_clause = implode(' AND ', $where_conditions);
        $order_clause = $args['orderby'] . ' ' . $args['order'];

        $sql = "SELECT * FROM $table_name WHERE $where_clause ORDER BY $order_clause";

        if ($args['limit'] > 0) {
            $sql .= $wpdb->prepare(" LIMIT %d OFFSET %d", $args['limit'], $args['offset']);
        }

        if (!empty($where_values)) {
            $sql = $wpdb->prepare($sql, $where_values);
        }

        return $wpdb->get_results($sql);
    }

    /**
     * Get appointment by ID
     *
     * @param int $appointment_id
     * @return object|null
     */
    public static function get_appointment($appointment_id) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'arta_appointments';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $appointment_id));
    }

    /**
     * Update appointment
     *
     * @param int $appointment_id
     * @param array $data
     * @return bool
     */
    public static function update_appointment($appointment_id, $data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';

        $result = $wpdb->update(
            $table_name,
            $data,
            array('id' => $appointment_id),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s'),
            array('%d')
        );

        return $result !== false;
    }

    /**
     * Delete appointment
     *
     * @param int $appointment_id
     * @return bool
     */
    public static function delete_appointment($appointment_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'arta_appointments';

        $result = $wpdb->delete(
            $table_name,
            array('id' => $appointment_id),
            array('%d')
        );

        return $result !== false;
    }

    /**
     * Create appointment
     *
     * @param array $data
     * @return int|false
     */
    public static function create_appointment($data) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'arta_appointments';

        $defaults = array(
            'program_id' => 0,
            'doctor_id' => 0,
            'appointment_date' => '',
            'start_time' => '',
            'end_time' => '',
            'status' => 'available',
            'patient_id' => null,
            'patient_name' => '',
            'patient_phone' => '',
            'patient_email' => '',
            'notes' => ''
        );

        $data = wp_parse_args($data, $defaults);

        $result = $wpdb->insert(
            $table_name,
            array(
                'program_id' => $data['program_id'],
                'doctor_id' => $data['doctor_id'],
                'appointment_date' => $data['appointment_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => $data['status'],
                'patient_id' => $data['patient_id'],
                'patient_name' => $data['patient_name'],
                'patient_phone' => $data['patient_phone'],
                'patient_email' => $data['patient_email'],
                'notes' => $data['notes']
            ),
            array(
                '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s'
            )
        );

        return $result ? $wpdb->insert_id : false;
    }



    /**
     * Create bulk appointments
     *
     * @param int $program_id
     * @param int $doctor_id
     * @param string $start_date
     * @param string $end_date
     * @param string $start_time
     * @param string $end_time
     * @param int $interval_minutes
     * @return int Number of appointments created
     */
    public static function create_bulk_appointments($doctor_id, $start_date, $end_date, $start_time, $end_time, $interval_minutes) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'arta_appointments';
        $appointments_created = 0;

        $start_datetime = new DateTime($start_date . ' ' . $start_time);
        $end_datetime = new DateTime($end_date . ' ' . $end_time);
        $interval = new DateInterval('PT' . $interval_minutes . 'M');

        $current_date = new DateTime($start_date);
        $end_date_obj = new DateTime($end_date);

        while ($current_date <= $end_date_obj) {
            $current_time = new DateTime($current_date->format('Y-m-d') . ' ' . $start_time);
            $day_end_time = new DateTime($current_date->format('Y-m-d') . ' ' . $end_time);

            while ($current_time < $day_end_time) {
                $next_time = clone $current_time;
                $next_time->add($interval);

                if ($next_time > $day_end_time) {
                    break;
                }

                $result = $wpdb->insert(
                    $table_name,
                    array(
                        'doctor_id' => $doctor_id,
                        'appointment_date' => $current_date->format('Y-m-d'),
                        'appointment_time' => $current_time->format('H:i:s'),
                        'status' => 'available'
                    ),
                    array('%d', '%s', '%s', '%s')
                );

                if ($result) {
                    $appointments_created++;
                } else {
                    // Debug: Log the error
                    error_log('Appointment creation failed: ' . $wpdb->last_error);
                    error_log('SQL: ' . $wpdb->last_query);
                }

                $current_time->add($interval);
            }

            $current_date->add(new DateInterval('P1D'));
        }

        return $appointments_created;
    }

    /**
     * Debug function to check table structure
     */
    public static function debug_table_structure() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'arta_appointments';
        
        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
        
        if (!$table_exists) {
            error_log('Table does not exist: ' . $table_name);
            return false;
        }
        
        // Get table structure
        $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name");
        
        error_log('Table structure for ' . $table_name . ':');
        foreach ($columns as $column) {
            error_log('- ' . $column->Field . ' (' . $column->Type . ')');
        }
        
        return true;
    }
}
