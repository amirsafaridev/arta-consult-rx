<?php
/**
 * Assigned Doctors Widget
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Arta_Assigned_Doctors_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'arta_assigned_doctors';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Assigned Doctors', 'arta-consult-rx');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-user-circle-o';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['consult-rx'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['doctors', 'physicians', 'medical', 'arta'];
    }

    /**
     * Get widget render on change
     */
    public function get_render_on_change() {
        return true;
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctors-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => __('Show Image', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'arta-consult-rx'),
                'label_off' => __('Hide', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->add_control(
            'show_description',
            [
                'label' => __('Show Description', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'arta-consult-rx'),
                'label_off' => __('Hide', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();


        // Style Section - Doctor Cards
        $this->start_controls_section(
            'doctor_card_style_section',
            [
                'label' => __('Doctor Cards', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => __('Background', 'arta-consult-rx'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .arta-doctor-card',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'label' => __('Box Shadow', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_margin',
            [
                'label' => __('Margin', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Doctor Image
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Doctor Image', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Image Size', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-image',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'label' => __('Box Shadow', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-image',
            ]
        );

        $this->end_controls_section();

        // Style Section - Doctor Name
        $this->start_controls_section(
            'name_style_section',
            [
                'label' => __('Doctor Name', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __('Typography', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'name_margin',
            [
                'label' => __('Margin', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // Style Section - Description
        $this->start_controls_section(
            'description_style_section',
            [
                'label' => __('Description', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __('Typography', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctor-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => __('Margin', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctor-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Container Style Section
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => __('Container', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => __('Background', 'arta-consult-rx'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .arta-doctors-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-doctors-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctors-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-doctors-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get current post
        $post = get_post();
        if (!$post || $post->post_type !== 'arta_program') {
            return;
        }

        $doctors = [];
        $doctor_ids = get_post_meta($post->ID, '_arta_program_doctors', true);
        error_log('Assigned Doctors Widget - Post ID: ' . $post->ID . ', Doctor IDs: ' . print_r($doctor_ids, true));
        
        if (is_array($doctor_ids) && !empty($doctor_ids)) {
            foreach ($doctor_ids as $doctor_id) {
                $doctor = get_user_by('ID', $doctor_id);
                if ($doctor) {
                $doctors[] = [
                        'name' => $doctor->display_name,
                        'description' => get_user_meta($doctor_id, 'description', true),
                        'image' => arta_get_doctor_avatar($doctor_id, 'medium'),
                    ];
                }
            }
            error_log('Assigned Doctors Widget - Processed Doctors: ' . print_r($doctors, true));
        }

        if (empty($doctors)) {
            return;
        }

        echo '<div class="arta-doctors-wrapper">';
        

        echo '<div class="arta-doctors-grid">';
        
        foreach ($doctors as $doctor) {
            echo '<div class="arta-doctor-card">';
            
            if ($settings['show_image'] === 'yes' && !empty($doctor['image'])) {
                echo '<div class="arta-doctor-image-wrapper">';
                echo '<img src="' . esc_url($doctor['image']) . '" alt="' . esc_attr($doctor['name']) . '" class="arta-doctor-image">';
                echo '</div>';
            }
            
            echo '<div class="arta-doctor-content">';
            echo '<h4 class="arta-doctor-name">' . esc_html($doctor['name']) . '</h4>';
            
            
            if ($settings['show_description'] === 'yes' && !empty($doctor['description'])) {
                echo '<p class="arta-doctor-description">' . esc_html($doctor['description']) . '</p>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        // Get first arta_program post for preview
        $first_program = get_posts(array(
            'post_type' => 'arta_program',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        $preview_doctors = array(
            array(
                'name' => 'Dr. John Smith',
                'description' => 'Expert in cardiovascular diseases with over 10 years of experience.',
                'image' => 'https://via.placeholder.com/200x200'
            ),
            array(
                'name' => 'Dr. Jane Doe', 
                'description' => 'Specialist in neurological disorders and brain health.',
                'image' => 'https://via.placeholder.com/200x200'
            )
        );
        
        if (!empty($first_program)) {
            $doctor_ids = get_post_meta($first_program[0]->ID, '_arta_program_doctors', true);
            if (is_array($doctor_ids) && !empty($doctor_ids)) {
                $preview_doctors = array();
                foreach (array_slice($doctor_ids, 0, 2) as $doctor_id) { // Limit to 2 for preview
                    $doctor = get_user_by('ID', $doctor_id);
                    if ($doctor) {
                        $preview_doctors[] = array(
                            'name' => $doctor->display_name,
                            'description' => get_user_meta($doctor_id, 'description', true) ?: 'Medical professional with expertise in patient care.',
                            'image' => arta_get_doctor_avatar($doctor_id, 'medium')
                        );
                    }
                }
            }
        }
        ?>
        <div class="arta-doctors-wrapper">
            <div class="arta-doctors-grid">
                <?php foreach ($preview_doctors as $doctor): ?>
                    <div class="arta-doctor-card">
                        <# if (settings.show_image === 'yes') { #>
                            <div class="arta-doctor-image-wrapper">
                            <img src="<?php echo esc_url($doctor['image']); ?>" alt="<?php echo esc_attr($doctor['name']); ?>" class="arta-doctor-image">
                            </div>
                        <# } #>
                        <div class="arta-doctor-content">
                        <h4 class="arta-doctor-name"><?php echo esc_html($doctor['name']); ?></h4>
                        <# if (settings.show_description === 'yes') { #>
                            <p class="arta-doctor-description"><?php echo esc_html($doctor['description']); ?></p>
                            <# } #>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
