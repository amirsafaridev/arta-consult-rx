<?php
/**
 * Program Benefits Widget
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Arta_Program_Benefits_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'arta_program_benefits';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Program Benefits', 'arta-consult-rx');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-star';
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
        return ['program', 'benefits', 'advantages', 'arta'];
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
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'fallback_text',
            [
                'label' => __('Fallback Text', 'arta-consult-rx'),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('No benefits available for this program.', 'arta-consult-rx'),
                'rows' => 3,
            ]
        );

        $this->end_controls_section();



        // Benefits Style Section
        $this->start_controls_section(
            'benefits_style_section',
            [
                'label' => __('Benefits', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'benefits_typography',
                'label' => __('Typography', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-benefits',
            ]
        );

        $this->add_control(
            'benefits_text_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-program-benefits' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'benefits_align',
            [
                'label' => __('Alignment', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justify', 'arta-consult-rx'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-program-benefits' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'benefits_margin',
            [
                'label' => __('Margin', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-benefits' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'benefits_padding',
            [
                'label' => __('Padding', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-benefits' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .arta-program-benefits-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-benefits-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-benefits-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Box Shadow', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-benefits-wrapper',
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

        $content = get_post_meta($post->ID, '_arta_program_benefits_description', true);
        error_log('Program Benefits Widget - Post ID: ' . $post->ID . ', Benefits Description: ' . print_r($content, true));
        
        if (!empty($content)) {
            $content = apply_filters('the_content', $content);
        } else {
            $content = $settings['fallback_text'];
        }

        if (empty($content)) {
            return;
        }

        echo '<div class="arta-program-benefits-wrapper">';
        echo '<div class="arta-program-benefits">';
        echo $content;
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
        
        $preview_content = __('This is a sample benefits description. It contains detailed information about the program benefits and advantages.', 'arta-consult-rx');
        
        if (!empty($first_program)) {
            $benefits_content = get_post_meta($first_program[0]->ID, '_arta_program_benefits_description', true);
            if (!empty($benefits_content)) {
                $preview_content = wp_strip_all_tags($benefits_content);
                if (strlen($preview_content) > 200) {
                    $preview_content = substr($preview_content, 0, 200) . '...';
                }
            }
        }
        ?>
        <div class="arta-program-benefits-wrapper">
            <div class="arta-program-benefits">
                <?php echo esc_html($preview_content); ?>
            </div>
        </div>
        <?php
    }
}
