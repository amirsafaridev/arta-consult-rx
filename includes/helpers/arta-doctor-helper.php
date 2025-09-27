<?php
/**
 * Doctor Helper Functions
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get doctor avatar URL
 *
 * @param int $doctor_id Doctor user ID
 * @param string $size Image size (thumbnail, medium, large, full)
 * @return string Avatar URL
 */
function arta_get_doctor_avatar($doctor_id, $size = 'thumbnail') {
    // Get custom avatar if exists
    $avatar_id = get_user_meta($doctor_id, 'arta_doctor_avatar', true);
    
    if ($avatar_id) {
        $avatar_url = wp_get_attachment_image_url($avatar_id, $size);
        if ($avatar_url) {
            return $avatar_url;
        }
    }
    
    // Fallback to default WordPress avatar
    return get_avatar_url($doctor_id, array('size' => 80));
}

/**
 * Get doctor avatar HTML
 *
 * @param int $doctor_id Doctor user ID
 * @param string $size Image size
 * @param array $attributes Additional attributes for img tag
 * @return string Avatar HTML
 */
function arta_get_doctor_avatar_html($doctor_id, $size = 'thumbnail', $attributes = array()) {
    $avatar_url = arta_get_doctor_avatar($doctor_id, $size);
    $doctor = get_user_by('ID', $doctor_id);
    $alt = $doctor ? $doctor->display_name : __('پزشک', 'arta-consult-rx');
    
    $default_attributes = array(
        'src' => $avatar_url,
        'alt' => $alt,
        'class' => 'arta-doctor-avatar-img'
    );
    
    $attributes = array_merge($default_attributes, $attributes);
    
    $attr_string = '';
    foreach ($attributes as $key => $value) {
        $attr_string .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
    }
    
    return '<img' . $attr_string . '>';
}
