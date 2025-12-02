<?php
/**
 * Program Description Widget
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Arta_Program_Description_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'arta_program_description';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Program Description', 'arta-consult-rx');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-text-area';
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
        return ['program', 'description', 'content', 'arta'];
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
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'arta-consult-rx'),
                'label_off' => __('Hide', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 1,
                'max' => 100,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_full_content',
            [
                'label' => __('Show Full Content', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'arta-consult-rx'),
                'label_off' => __('Hide', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'fallback_text',
            [
                'label' => __('Fallback Text', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('No description available for this program.', 'arta-consult-rx'),
                'rows' => 3,
            ]
        );

        $this->end_controls_section();

        // Style Section
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
                'selector' => '{{WRAPPER}} .arta-program-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-program-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_align',
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
                    '{{WRAPPER}} .arta-program-description' => 'text-align: {{VALUE}}',
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
                    '{{WRAPPER}} .arta-program-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => __('Padding', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .arta-program-description-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-description-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-description-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Box Shadow', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-description-wrapper',
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

        $content = '';
        
        if ($settings['show_excerpt'] === 'yes') {
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $content = wp_trim_words(get_the_content(), $settings['excerpt_length']);
            } else {
                $content = $excerpt;
            }
        } elseif ($settings['show_full_content'] === 'yes') {
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
        }

        if (empty($content)) {
            $content = $settings['fallback_text'];
        }

        if (empty($content)) {
            return;
        }

        echo '<div class="arta-program-description-wrapper">';
        echo '<div class="arta-program-description">';
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
        
        $preview_excerpt = __('This is a sample excerpt of the program description. It contains the main information about the program and its benefits.', 'arta-consult-rx');
        $preview_full = __('This is a sample full description of the program. It contains detailed information about the program, its goals, benefits, and how it works.', 'arta-consult-rx');
        
        if (!empty($first_program)) {
            $post = $first_program[0];
            
            // Get excerpt
            $excerpt = get_the_excerpt($post->ID);
            if (empty($excerpt)) {
                $excerpt = wp_trim_words($post->post_content, 20);
            }
            if (!empty($excerpt)) {
                $preview_excerpt = $excerpt;
            }
            
            // Get full content
            $content = $post->post_content;
            if (!empty($content)) {
                $preview_full = wp_strip_all_tags($content);
                if (strlen($preview_full) > 300) {
                    $preview_full = substr($preview_full, 0, 300) . '...';
                }
            }
        }
        ?>
        <div class="arta-program-description-wrapper">
            <div class="arta-program-description">
                <# if (settings.show_excerpt === 'yes') { #>
                    <?php echo esc_html($preview_excerpt); ?>
                <# } else { #>
                    <?php echo esc_html($preview_full); ?>
                <# } #>
            </div>
        </div>
        <?php
    }
}
