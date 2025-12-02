<?php
/**
 * Related Products Widget
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Arta_Related_Products_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'arta_related_products';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Related Products', 'arta-consult-rx');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-products';
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
        return ['products', 'woocommerce', 'shop', 'arta'];
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
            'section_title',
            [
                'label' => __('Section Title', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Related Products', 'arta-consult-rx'),
                'placeholder' => __('Enter section title', 'arta-consult-rx'),
            ]
        );

        $this->add_control(
            'products_source',
            [
                'label' => __('Products Source', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'meta',
                'options' => [
                    'meta' => __('Program Meta Field', 'arta-consult-rx'),
                    'category' => __('By Category', 'arta-consult-rx'),
                    'tag' => __('By Tag', 'arta-consult-rx'),
                    'custom' => __('Custom List', 'arta-consult-rx'),
                ],
            ]
        );

        $this->add_control(
            'product_category',
            [
                'label' => __('Product Category', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_product_categories(),
                'multiple' => true,
                'condition' => [
                    'products_source' => 'category',
                ],
            ]
        );

        $this->add_control(
            'product_tag',
            [
                'label' => __('Product Tag', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_product_tags(),
                'multiple' => true,
                'condition' => [
                    'products_source' => 'tag',
                ],
            ]
        );

        $this->add_control(
            'products_count',
            [
                'label' => __('Number of Products', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 20,
                'condition' => [
                    'products_source!' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'products_list',
            [
                'label' => __('Products List', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_products_list(),
                'multiple' => true,
                'condition' => [
                    'products_source' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

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
                'default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-products-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
            'show_title',
            [
                'label' => __('Show Title', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'arta-consult-rx'),
                'label_off' => __('Hide', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' => __('Show Price', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'arta-consult-rx'),
                'label_off' => __('Hide', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_add_to_cart',
            [
                'label' => __('Show Add to Cart', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'arta-consult-rx'),
                'label_off' => __('Hide', 'arta-consult-rx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
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
                'selector' => '{{WRAPPER}} .arta-products-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-products-title' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .arta-products-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Product Cards
        $this->start_controls_section(
            'product_card_style_section',
            [
                'label' => __('Product Cards', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => __('Background', 'arta-consult-rx'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .arta-product-card',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-product-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'label' => __('Box Shadow', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-product-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .arta-product-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Product Image
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Product Image', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .arta-product-image' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .arta-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-product-image',
            ]
        );

        $this->end_controls_section();

        // Style Section - Product Title
        $this->start_controls_section(
            'product_title_style_section',
            [
                'label' => __('Product Title', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'product_title_typography',
                'label' => __('Typography', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-product-title',
            ]
        );

        $this->add_control(
            'product_title_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-product-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'product_title_margin',
            [
                'label' => __('Margin', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Product Price
        $this->start_controls_section(
            'price_style_section',
            [
                'label' => __('Product Price', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => __('Typography', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-product-price',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-product-price' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_margin',
            [
                'label' => __('Margin', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Add to Cart Button
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => __('Add to Cart Button', 'arta-consult-rx'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-add-to-cart-btn',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => __('Background', 'arta-consult-rx'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .arta-add-to-cart-btn',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .arta-add-to-cart-btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-add-to-cart-btn',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-add-to-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .arta-add-to-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .arta-products-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'arta-consult-rx'),
                'selector' => '{{WRAPPER}} .arta-products-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'arta-consult-rx'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .arta-products-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .arta-products-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get product categories
     */
    private function get_product_categories() {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $options = [];
        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }

        return $options;
    }

    /**
     * Get product tags
     */
    private function get_product_tags() {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $tags = get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
        ]);

        $options = [];
        foreach ($tags as $tag) {
            $options[$tag->term_id] = $tag->name;
        }

        return $options;
    }

    /**
     * Get products list
     */
    private function get_products_list() {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $products = get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        $options = [];
        foreach ($products as $product) {
            $options[$product->ID] = $product->post_title;
        }

        return $options;
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!class_exists('WooCommerce')) {
            echo '<div class="arta-products-wrapper"><p>' . __('WooCommerce is not active', 'arta-consult-rx') . '</p></div>';
            return;
        }

        // Get current post
        $post = get_post();
        if (!$post || $post->post_type !== 'arta_program') {
            return;
        }

        $products = [];

        if ($settings['products_source'] === 'meta') {
            $product_ids = get_post_meta($post->ID, '_arta_program_related_products', true);
            error_log('Related Products Widget - Post ID: ' . $post->ID . ', Product IDs: ' . print_r($product_ids, true));
            if (is_array($product_ids) && !empty($product_ids)) {
                $products = array_map('wc_get_product', $product_ids);
                $products = array_filter($products);
                error_log('Related Products Widget - Processed Products: ' . print_r($products, true));
            }
        } elseif ($settings['products_source'] === 'category') {
            $args = [
                'post_type' => 'product',
                'posts_per_page' => $settings['products_count'],
                'post_status' => 'publish',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $settings['product_category'],
                    ],
                ],
            ];
            $product_posts = get_posts($args);
            $products = array_map('wc_get_product', wp_list_pluck($product_posts, 'ID'));
        } elseif ($settings['products_source'] === 'tag') {
            $args = [
                'post_type' => 'product',
                'posts_per_page' => $settings['products_count'],
                'post_status' => 'publish',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_tag',
                        'field' => 'term_id',
                        'terms' => $settings['product_tag'],
                    ],
                ],
            ];
            $product_posts = get_posts($args);
            $products = array_map('wc_get_product', wp_list_pluck($product_posts, 'ID'));
        } else {
            $products = array_map('wc_get_product', $settings['products_list']);
            $products = array_filter($products);
        }

        if (empty($products)) {
            return;
        }

        echo '<div class="arta-products-wrapper">';
        
        if (!empty($settings['section_title'])) {
            echo '<h3 class="arta-products-title">' . esc_html($settings['section_title']) . '</h3>';
        }

        echo '<div class="arta-products-grid">';
        
        foreach ($products as $product) {
            if (!$product) continue;
            
            echo '<div class="arta-product-card">';
            
            if ($settings['show_image'] === 'yes') {
                echo '<div class="arta-product-image-wrapper">';
                echo '<a href="' . esc_url($product->get_permalink()) . '">';
                echo $product->get_image('medium', ['class' => 'arta-product-image']);
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="arta-product-content">';
            
            if ($settings['show_title'] === 'yes') {
                echo '<h4 class="arta-product-title">';
                echo '<a href="' . esc_url($product->get_permalink()) . '">';
                echo esc_html($product->get_name());
                echo '</a>';
                echo '</h4>';
            }
            
            if ($settings['show_price'] === 'yes') {
                echo '<div class="arta-product-price">';
                echo $product->get_price_html();
                echo '</div>';
            }
            
            if ($settings['show_add_to_cart'] === 'yes') {
                echo '<div class="arta-product-actions">';
                echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="arta-add-to-cart-btn">';
                echo esc_html($product->add_to_cart_text());
                echo '</a>';
                echo '</div>';
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
        
        $preview_products = array(
            array(
                'name' => 'Sample Product 1',
                'price' => '$29.99',
                'image' => 'https://via.placeholder.com/300x300',
                'permalink' => '#'
            ),
            array(
                'name' => 'Sample Product 2',
                'price' => '$39.99',
                'image' => 'https://via.placeholder.com/300x300',
                'permalink' => '#'
            )
        );
        
        if (!empty($first_program) && class_exists('WooCommerce')) {
            $product_ids = get_post_meta($first_program[0]->ID, '_arta_program_related_products', true);
            if (is_array($product_ids) && !empty($product_ids)) {
                $preview_products = array();
                foreach (array_slice($product_ids, 0, 2) as $product_id) { // Limit to 2 for preview
                    $product = wc_get_product($product_id);
                    if ($product && $product->is_visible()) {
                        $preview_products[] = array(
                            'name' => $product->get_name(),
                            'price' => $product->get_price_html(),
                            'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium') ?: wc_placeholder_img_src('medium'),
                            'permalink' => get_permalink($product->get_id())
                        );
                    }
                }
            }
        }
        ?>
        <div class="arta-products-wrapper">
            <# if (settings.section_title) { #>
                <h3 class="arta-products-title">{{{ settings.section_title }}}</h3>
            <# } #>
            <div class="arta-products-grid">
                <?php foreach ($preview_products as $product): ?>
                    <div class="arta-product-card">
                        <# if (settings.show_image === 'yes') { #>
                            <div class="arta-product-image-wrapper">
                            <a href="<?php echo esc_url($product['permalink']); ?>">
                                <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>" class="arta-product-image">
                                </a>
                            </div>
                        <# } #>
                        <div class="arta-product-content">
                            <# if (settings.show_title === 'yes') { #>
                                <h4 class="arta-product-title">
                                <a href="<?php echo esc_url($product['permalink']); ?>"><?php echo esc_html($product['name']); ?></a>
                                </h4>
                            <# } #>
                            <# if (settings.show_price === 'yes') { #>
                            <div class="arta-product-price"><?php echo wp_kses_post($product['price']); ?></div>
                            <# } #>
                            <# if (settings.show_add_to_cart === 'yes') { #>
                                <div class="arta-product-actions">
                                    <a href="#" class="arta-add-to-cart-btn">Add to Cart</a>
                                </div>
                            <# } #>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
