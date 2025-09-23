<?php
/**
 * Custom Taxonomies class
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Taxonomies class
 */
class Arta_Consult_RX_Taxonomies {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_taxonomies'));
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        $this->register_program_categories();
        $this->register_doctor_specialties();
        $this->register_medical_conditions();
    }

    /**
     * Register Program Categories taxonomy
     */
    private function register_program_categories() {
        $labels = array(
            'name'                       => _x('Program Categories', 'Taxonomy General Name', 'arta-consult-rx'),
            'singular_name'              => _x('Program Category', 'Taxonomy Singular Name', 'arta-consult-rx'),
            'menu_name'                  => __('Program Categories', 'arta-consult-rx'),
            'all_items'                  => __('All Categories', 'arta-consult-rx'),
            'parent_item'                => __('Parent Category', 'arta-consult-rx'),
            'parent_item_colon'          => __('Parent Category:', 'arta-consult-rx'),
            'new_item_name'              => __('New Category Name', 'arta-consult-rx'),
            'add_new_item'               => __('Add New Category', 'arta-consult-rx'),
            'edit_item'                  => __('Edit Category', 'arta-consult-rx'),
            'update_item'                => __('Update Category', 'arta-consult-rx'),
            'view_item'                  => __('View Category', 'arta-consult-rx'),
            'separate_items_with_commas' => __('Separate categories with commas', 'arta-consult-rx'),
            'add_or_remove_items'        => __('Add or remove categories', 'arta-consult-rx'),
            'choose_from_most_used'      => __('Choose from the most used', 'arta-consult-rx'),
            'popular_items'              => __('Popular Categories', 'arta-consult-rx'),
            'search_items'               => __('Search Categories', 'arta-consult-rx'),
            'not_found'                  => __('Not Found', 'arta-consult-rx'),
            'no_terms'                   => __('No categories', 'arta-consult-rx'),
            'items_list'                 => __('Categories list', 'arta-consult-rx'),
            'items_list_navigation'      => __('Categories list navigation', 'arta-consult-rx'),
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'program-category'),
        );

        register_taxonomy('arta_program_category', array('arta_program'), $args);
    }

    /**
     * Register Doctor Specialties taxonomy
     */
    private function register_doctor_specialties() {
        $labels = array(
            'name'                       => _x('Doctor Specialties', 'Taxonomy General Name', 'arta-consult-rx'),
            'singular_name'              => _x('Doctor Specialty', 'Taxonomy Singular Name', 'arta-consult-rx'),
            'menu_name'                  => __('Doctor Specialties', 'arta-consult-rx'),
            'all_items'                  => __('All Specialties', 'arta-consult-rx'),
            'parent_item'                => __('Parent Specialty', 'arta-consult-rx'),
            'parent_item_colon'          => __('Parent Specialty:', 'arta-consult-rx'),
            'new_item_name'              => __('New Specialty Name', 'arta-consult-rx'),
            'add_new_item'               => __('Add New Specialty', 'arta-consult-rx'),
            'edit_item'                  => __('Edit Specialty', 'arta-consult-rx'),
            'update_item'                => __('Update Specialty', 'arta-consult-rx'),
            'view_item'                  => __('View Specialty', 'arta-consult-rx'),
            'separate_items_with_commas' => __('Separate specialties with commas', 'arta-consult-rx'),
            'add_or_remove_items'        => __('Add or remove specialties', 'arta-consult-rx'),
            'choose_from_most_used'      => __('Choose from the most used', 'arta-consult-rx'),
            'popular_items'              => __('Popular Specialties', 'arta-consult-rx'),
            'search_items'               => __('Search Specialties', 'arta-consult-rx'),
            'not_found'                  => __('Not Found', 'arta-consult-rx'),
            'no_terms'                   => __('No specialties', 'arta-consult-rx'),
            'items_list'                 => __('Specialties list', 'arta-consult-rx'),
            'items_list_navigation'      => __('Specialties list navigation', 'arta-consult-rx'),
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'doctor-specialty'),
        );

        register_taxonomy('arta_doctor_specialty', array('arta_doctor'), $args);
    }

    /**
     * Register Medical Conditions taxonomy
     */
    private function register_medical_conditions() {
        $labels = array(
            'name'                       => _x('Medical Conditions', 'Taxonomy General Name', 'arta-consult-rx'),
            'singular_name'              => _x('Medical Condition', 'Taxonomy Singular Name', 'arta-consult-rx'),
            'menu_name'                  => __('Medical Conditions', 'arta-consult-rx'),
            'all_items'                  => __('All Conditions', 'arta-consult-rx'),
            'parent_item'                => __('Parent Condition', 'arta-consult-rx'),
            'parent_item_colon'          => __('Parent Condition:', 'arta-consult-rx'),
            'new_item_name'              => __('New Condition Name', 'arta-consult-rx'),
            'add_new_item'               => __('Add New Condition', 'arta-consult-rx'),
            'edit_item'                  => __('Edit Condition', 'arta-consult-rx'),
            'update_item'                => __('Update Condition', 'arta-consult-rx'),
            'view_item'                  => __('View Condition', 'arta-consult-rx'),
            'separate_items_with_commas' => __('Separate conditions with commas', 'arta-consult-rx'),
            'add_or_remove_items'        => __('Add or remove conditions', 'arta-consult-rx'),
            'choose_from_most_used'      => __('Choose from the most used', 'arta-consult-rx'),
            'popular_items'              => __('Popular Conditions', 'arta-consult-rx'),
            'search_items'               => __('Search Conditions', 'arta-consult-rx'),
            'not_found'                  => __('Not Found', 'arta-consult-rx'),
            'no_terms'                   => __('No conditions', 'arta-consult-rx'),
            'items_list'                 => __('Conditions list', 'arta-consult-rx'),
            'items_list_navigation'      => __('Conditions list navigation', 'arta-consult-rx'),
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'medical-condition'),
        );

        register_taxonomy('arta_medical_condition', array('arta_program', 'arta_doctor'), $args);
    }
}
