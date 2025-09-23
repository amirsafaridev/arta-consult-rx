<?php
/**
 * Arta Appointment Slot Class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Arta Appointment Slot Class
 * 
 * Mimics WooCommerce order object for compatibility with existing templates
 */
class Arta_Appointment_Slot {
    
    private $slot_data;
    
    /**
     * Constructor
     */
    public function __construct($slot_data) {
        $this->slot_data = $slot_data;
    }
    
    /**
     * Get slot ID
     */
    public function get_id() {
        return $this->slot_data->id;
    }
    
    /**
     * Get meta data
     */
    public function get_meta($key) {
        switch ($key) {
            case '_appointment_date':
                return $this->slot_data->slot_date;
            case '_appointment_time':
                return $this->slot_data->slot_time;
            case '_doctor_id':
                return $this->slot_data->doctor_id;
            case '_appointment_data':
                return array(
                    'appointment_date' => $this->slot_data->slot_date,
                    'appointment_time' => $this->slot_data->slot_time,
                    'doctor_id' => $this->slot_data->doctor_id,
                    'duration' => $this->slot_data->duration
                );
            default:
                return null;
        }
    }
    
    /**
     * Get status
     */
    public function get_status() {
        switch ($this->slot_data->status) {
            case 'available':
                return 'wc-appointment-available';
            case 'booked':
                return 'wc-appointment-scheduled';
            case 'completed':
                return 'wc-appointment-completed';
            default:
                return 'wc-appointment-pending';
        }
    }
    
    /**
     * Get date created
     */
    public function get_date_created() {
        return new DateTime($this->slot_data->created_at);
    }
    
    /**
     * Get billing first name
     */
    public function get_billing_first_name() {
        return '';
    }
    
    /**
     * Get billing last name
     */
    public function get_billing_last_name() {
        return 'Available Slot';
    }
    
    /**
     * Get billing company
     */
    public function get_billing_company() {
        return '';
    }
    
    /**
     * Get slot data
     */
    public function get_slot_data() {
        return $this->slot_data;
    }
}
