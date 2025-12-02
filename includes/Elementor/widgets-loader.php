<?php
/**
 * Elementor Widgets Loader
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include all widget files
require_once __DIR__ . '/program-title-widget.php';
require_once __DIR__ . '/program-description-widget.php';
require_once __DIR__ . '/program-goals-widget.php';
require_once __DIR__ . '/program-benefits-widget.php';
require_once __DIR__ . '/assigned-doctors-widget.php';
require_once __DIR__ . '/related-products-widget.php';
require_once __DIR__ . '/appointment-form-widget.php';

/**
 * Add Consult RX category to Elementor
 */
function arta_add_elementor_categories($elements_manager) {
    $elements_manager->add_category(
        'consult-rx',
        [
            'title' => __('Consult RX', 'arta-consult-rx'),
            'icon' => 'fa fa-heartbeat',
        ]
    );
}

/**
 * Register Elementor widgets
 */
function arta_register_elementor_widgets($widgets_manager) {
    // Program Title Widget
    if (class_exists('Arta_Program_Title_Widget')) {
        $widgets_manager->register(new Arta_Program_Title_Widget());
    }

    // Program Description Widget
    if (class_exists('Arta_Program_Description_Widget')) {
        $widgets_manager->register(new Arta_Program_Description_Widget());
    }

    // Program Goals Widget
    if (class_exists('Arta_Program_Goals_Widget')) {
        $widgets_manager->register(new Arta_Program_Goals_Widget());
    }

    // Program Benefits Widget
    if (class_exists('Arta_Program_Benefits_Widget')) {
        $widgets_manager->register(new Arta_Program_Benefits_Widget());
    }

    // Assigned Doctors Widget
    if (class_exists('Arta_Assigned_Doctors_Widget')) {
        $widgets_manager->register(new Arta_Assigned_Doctors_Widget());
    }

    // Related Products Widget
    if (class_exists('Arta_Related_Products_Widget')) {
        $widgets_manager->register(new Arta_Related_Products_Widget());
    }

    // Appointment Form Widget
    if (class_exists('Arta_Appointment_Form_Widget')) {
        $widgets_manager->register(new Arta_Appointment_Form_Widget());
    }
}

// Register category and widgets
add_action('elementor/elements/categories_registered', 'arta_add_elementor_categories');
add_action('elementor/widgets/register', 'arta_register_elementor_widgets');

