<?php
/**
 * Program Title Widget
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Arta_Program_Title_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'arta_program_title';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Program Title', 'arta-consult-rx');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-post-title';
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
        return ['program', 'title', 'heading', 'arta'];
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
            'title_tag',
            [
                'label' => __('HTML Tag', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
            ]
        );

        $this->add_control(
            'title_link',
            [
                'label' => __('Link', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'arta-consult-rx'),
                'condition' => [
                    'title_tag!' => 'div',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __('Title', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-program-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'label' => __('Text Shadow', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-title',
            ]
        );

        $this->add_responsive_control(
            'title_align',
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
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .arta-program-title' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __('Padding', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .arta-program-title-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-title-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-program-title-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Box Shadow', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-program-title-wrapper',
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

        $title = get_the_title();
        $title_tag = $settings['title_tag'];
        $title_link = $settings['title_link'];

        if (empty($title)) {
            return;
        }

        $this->add_render_attribute('title', 'class', 'arta-program-title');

        if (!empty($title_link['url'])) {
            $this->add_link_attributes('title', $title_link);
        }

        echo '<div class="arta-program-title-wrapper">';
        
        if (!empty($title_link['url'])) {
            echo '<' . esc_attr($title_tag) . ' ' . $this->get_render_attribute_string('title') . '>';
            echo esc_html($title);
            echo '</' . esc_attr($title_tag) . '>';
        } else {
            echo '<' . esc_attr($title_tag) . ' ' . $this->get_render_attribute_string('title') . '>';
            echo esc_html($title);
            echo '</' . esc_attr($title_tag) . '>';
        }
        
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
        
        $preview_title = __('Sample Program Title', 'arta-consult-rx');
        
        if (!empty($first_program)) {
            $title = get_the_title($first_program[0]->ID);
            if (!empty($title)) {
                $preview_title = $title;
            }
        }
        ?>
        <#
        var titleTag = settings.title_tag;
        var titleLink = settings.title_link;
        #>
        <div class="arta-program-title-wrapper">
            <# if (titleLink.url) { #>
                <{{{ titleTag }}} class="arta-program-title" href="{{{ titleLink.url }}}" {{{ titleLink.is_external ? 'target="_blank"' : '' }}} {{{ titleLink.nofollow ? 'rel="nofollow"' : '' }}}>
                    <?php echo esc_html($preview_title); ?>
                </{{{ titleTag }}}>
            <# } else { #>
                <{{{ titleTag }}} class="arta-program-title">
                    <?php echo esc_html($preview_title); ?>
                </{{{ titleTag }}}>
            <# } #>
        </div>
        <?php
    }
}
