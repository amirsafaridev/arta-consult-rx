<?php
/**
 * The template for displaying all single 'arta_program' posts.
 *
 * @package Arta_Consult_RX
 * @since 1.0.0
 */

// Check if Elementor template exists first
if (class_exists('Elementor\Plugin')) {
    $templates = get_posts(array(
        'post_type' => 'elementor_library',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_elementor_template_type',
                'value' => 'single',
                'compare' => '='
            ),
            array(
                'relation' => 'OR',
                array(
                    'key' => '_elementor_conditions',
                    'value' => 'arta_program',
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_elementor_conditions',
                    'value' => 'singular/arta_program',
                    'compare' => 'LIKE'
                )
            )
        ),
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));

    if (!empty($templates)) {
        // Let Elementor handle the template
        return;
    }
}

get_header();

// Get post meta data
$doctors = get_post_meta(get_the_ID(), '_arta_program_doctors', true);
$related_products = get_post_meta(get_the_ID(), '_arta_program_related_products', true);
$goals_description = get_post_meta(get_the_ID(), '_arta_program_goals_description', true);
$benefits_description = get_post_meta(get_the_ID(), '_arta_program_benefits_description', true);

// Ensure arrays
$doctors = is_array($doctors) ? $doctors : array();
$related_products = is_array($related_products) ? $related_products : array();

// Get doctors data
$doctors_data = array();
if (!empty($doctors)) {
    foreach ($doctors as $doctor_id) {
        $doctor = get_user_by('ID', $doctor_id);
        if ($doctor) {
            // Get custom avatar if exists
            $avatar = arta_get_doctor_avatar($doctor->ID, 'thumbnail');
            
            $doctors_data[] = array(
                'id' => $doctor->ID,
                'name' => $doctor->display_name,
                'avatar' => $avatar
            );
        }
    }
}

// Get products data
$related_products_data = array();
if (!empty($related_products)) {
    foreach ($related_products as $product_id) {
        $product = wc_get_product($product_id);
        if ($product) {
            $related_products_data[] = array(
                'id' => $product->get_id(),
                'title' => $product->get_name(),
                'link' => get_permalink($product->get_id()),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
                'price' => $product->get_price_html()
            );
        }
    }
}
?>

<div class="arta-single-program">
    <div class="arta-container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('arta-program-article'); ?>>
                
                <!-- Program Header -->
                <header class="arta-program-header">
                    <div class="arta-program-hero">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="arta-program-featured-image">
                                <?php the_post_thumbnail('large', array('class' => 'arta-featured-img')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="arta-program-info">
                            <h1 class="arta-program-title"><?php the_title(); ?></h1>
                            <div class="arta-program-meta">
                                <span class="arta-program-date">
                                    <i class="arta-icon">📅</i>
                                    <?php echo get_the_date('j F Y'); ?>
                                </span>
                                <span class="arta-program-status">
                                    <i class="arta-icon">✅</i>
                                    <?php _e('فعال', 'arta-consult-rx'); ?>
                                </span>
                            </div>
                            
                            <!-- Reservation Button -->
                            <div class="arta-program-actions">
                                <button class="arta-btn arta-btn-primary arta-btn-reservation">
                                    <i class="arta-icon">📅</i>
                                    <?php _e('رزرو نوبت', 'arta-consult-rx'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Program Description -->
                <?php if (get_the_content()) : ?>
                <section class="arta-program-section">
                    <div class="arta-card">
                        <div class="arta-card-header">
                            <h2 class="arta-card-title">
                                <i class="arta-icon">📋</i>
                                <?php _e('توضیحات برنامه', 'arta-consult-rx'); ?>
                            </h2>
                        </div>
                        <div class="arta-card-content">
                            <div class="arta-program-description">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Program Goals -->
                <?php if (!empty($goals_description)) : ?>
                <section class="arta-program-section">
                    <div class="arta-card">
                        <div class="arta-card-header">
                            <h2 class="arta-card-title">
                                <i class="arta-icon">🎯</i>
                                <?php _e('اهداف برنامه', 'arta-consult-rx'); ?>
                            </h2>
                        </div>
                        <div class="arta-card-content">
                            <div class="arta-program-goals">
                                <?php echo wp_kses_post($goals_description); ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Program Benefits -->
                <?php if (!empty($benefits_description)) : ?>
                <section class="arta-program-section">
                    <div class="arta-card">
                        <div class="arta-card-header">
                            <h2 class="arta-card-title">
                                <i class="arta-icon">⭐</i>
                                <?php _e('مزایای برنامه', 'arta-consult-rx'); ?>
                            </h2>
                        </div>
                        <div class="arta-card-content">
                            <div class="arta-program-benefits">
                                <?php echo wp_kses_post($benefits_description); ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Responsible Doctors -->
                <?php if (!empty($doctors_data)) : ?>
                <section class="arta-program-section">
                    <div class="arta-card">
                        <div class="arta-card-header">
                            <h2 class="arta-card-title">
                                <i class="arta-icon">👨‍⚕️</i>
                                <?php _e('پزشک‌های مسئول', 'arta-consult-rx'); ?>
                            </h2>
                        </div>
                        <div class="arta-card-content">
                            <div class="arta-doctors-grid">
                                <?php foreach ($doctors_data as $doctor) : ?>
                                    <div class="arta-doctor-box">
                                        <div class="arta-doctor-avatar">
                                            <img src="<?php echo esc_url($doctor['avatar']); ?>" alt="<?php echo esc_attr($doctor['name']); ?>">
                                        </div>
                                        <div class="arta-doctor-info">
                                            <h3 class="arta-doctor-name"><?php echo esc_html($doctor['name']); ?></h3>
                                            <span class="arta-doctor-id">#<?php echo esc_html($doctor['id']); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Related Products -->
                <?php if (!empty($related_products_data)) : ?>
                <section class="arta-program-section">
                    <div class="arta-card">
                        <div class="arta-card-header">
                            <h2 class="arta-card-title">
                                <i class="arta-icon">🛍️</i>
                                <?php _e('محصولات مرتبط', 'arta-consult-rx'); ?>
                            </h2>
                        </div>
                        <div class="arta-card-content">
                            <div class="arta-products-grid">
                                <?php foreach ($related_products_data as $product) : ?>
                                    <div class="arta-product-box">
                                        <div class="arta-product-image">
                                            <?php if ($product['image']) : ?>
                                                <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['title']); ?>">
                                            <?php else : ?>
                                                <div class="arta-product-placeholder">
                                                    <i class="arta-icon">📦</i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="arta-product-info">
                                            <h3 class="arta-product-name"><?php echo esc_html($product['title']); ?></h3>
                                            <span class="arta-product-id">#<?php echo esc_html($product['id']); ?></span>
                                            <?php if ($product['price']) : ?>
                                                <div class="arta-product-price"><?php echo wp_kses_post($product['price']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="arta-product-action">
                                            <a href="<?php echo esc_url($product['link']); ?>" class="arta-btn arta-btn-outline" target="_blank">
                                                <?php _e('مشاهده', 'arta-consult-rx'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php
get_footer();