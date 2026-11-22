<?php
/**
 * TamanBD Front Page Template
 *
 * Dynamically pulls all categories, products, customer reviews, and blog posts
 * from the active WordPress & WooCommerce database. Zero hardcoded dummy products.
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Customizer hero slides with fallback editorial visuals included in theme assets.
$slides = array(
    array(
        'kicker'   => (string) get_theme_mod( 'tamanbd_hero_1_kicker', __( 'Dhaka Living Edit', 'tamanbd' ) ),
        'title'    => (string) get_theme_mod( 'tamanbd_hero_1_title', __( 'Warm Teak & Handcrafted Home Essentials', 'tamanbd' ) ),
        'subtitle' => (string) get_theme_mod( 'tamanbd_hero_1_subtitle', __( 'Architectural shelving, brushed brass lighting, and artisanal stoneware crafted for modern Bangladeshi homes.', 'tamanbd' ) ),
        'cta_text' => (string) get_theme_mod( 'tamanbd_hero_1_cta_text', __( 'Explore Store Catalog', 'tamanbd' ) ),
        'cta_url'  => (string) get_theme_mod( 'tamanbd_hero_1_cta_url', '' ) ?: $shop_url,
        'image'    => (string) get_theme_mod( 'tamanbd_hero_1_image', '' ) ?: TAMANBD_URI . '/assets/images/hero-living.jpg',
    ),
    array(
        'kicker'   => (string) get_theme_mod( 'tamanbd_hero_2_kicker', __( 'Artisan & Botanical Capsule', 'tamanbd' ) ),
        'title'    => (string) get_theme_mod( 'tamanbd_hero_2_title', __( 'Handloom Textiles Meets Pure Botanical Care', 'tamanbd' ) ),
        'subtitle' => (string) get_theme_mod( 'tamanbd_hero_2_subtitle', __( 'Small-batch botanical serums, handwoven cottons, and solid acacia pantry ware delivered nationwide.', 'tamanbd' ) ),
        'cta_text' => (string) get_theme_mod( 'tamanbd_hero_2_cta_text', __( 'Shop Featured Goods', 'tamanbd' ) ),
        'cta_url'  => (string) get_theme_mod( 'tamanbd_hero_2_cta_url', '' ) ?: $shop_url,
        'image'    => (string) get_theme_mod( 'tamanbd_hero_2_image', '' ) ?: TAMANBD_URI . '/assets/images/hero-lifestyle.jpg',
    ),
);
?>

<main id="tmb-main-content" role="main">
    <!-- 1. Responsive Hero Slider -->
    <section class="tmb-hero" aria-label="<?php esc_attr_e( 'Featured Collections', 'tamanbd' ); ?>">
        <div class="tmb-container">
            <div class="tmb-hero-slider" data-hero-slider>
                <?php foreach ( $slides as $index => $slide ) : ?>
                    <div class="tmb-hero-slide <?php echo 0 === $index ? 'is-active' : ''; ?>" data-hero-slide="<?php echo esc_attr( (string) $index ); ?>">
                        <div class="tmb-hero-copy">
                            <div>
                                <p class="tmb-kicker"><?php echo esc_html( $slide['kicker'] ); ?></p>
                                <?php if ( 0 === $index ) : ?>
                                    <h1 class="tmb-hero-title"><?php echo esc_html( $slide['title'] ); ?></h1>
                                <?php else : ?>
                                    <h2 class="tmb-hero-title"><?php echo esc_html( $slide['title'] ); ?></h2>
                                <?php endif; ?>
                                <p class="tmb-hero-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></p>
                            </div>
                            <div class="tmb-hero-actions">
                                <a href="<?php echo esc_url( $slide['cta_url'] ); ?>" class="tmb-btn tmb-btn--primary">
                                    <?php echo esc_html( $slide['cta_text'] ); ?> &rarr;
                                </a>
                                <div class="tmb-slider-nav">
                                    <button type="button" class="tmb-slider-btn" data-hero-prev aria-label="<?php esc_attr_e( 'Previous slide', 'tamanbd' ); ?>">&lsaquo;</button>
                                    <button type="button" class="tmb-slider-btn" data-hero-next aria-label="<?php esc_attr_e( 'Next slide', 'tamanbd' ); ?>">&rsaquo;</button>
                                </div>
                            </div>
                        </div>
                        <div class="tmb-hero-media">
                            <img
                                src="<?php echo esc_url( $slide['image'] ); ?>"
                                alt="<?php echo esc_attr( $slide['title'] ); ?>"
                                loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
                                <?php echo 0 === $index ? 'fetchpriority="high"' : ''; ?>
                            >
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 2. Featured WooCommerce Categories -->
    <?php
    if ( taxonomy_exists( 'product_cat' ) ) :
        $categories = get_terms(
            array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => 0,
                'number'     => 4,
                'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
            )
        );
        if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) :
            ?>
            <section id="categories" class="tmb-section">
                <div class="tmb-container">
                    <div class="tmb-section-head">
                        <div>
                            <p class="tmb-kicker"><?php esc_html_e( 'Browse Departments', 'tamanbd' ); ?></p>
                            <h2 class="tmb-section-title"><?php esc_html_e( 'Featured Categories', 'tamanbd' ); ?></h2>
                        </div>
                        <a href="<?php echo esc_url( $shop_url ); ?>" class="tmb-text-link"><?php esc_html_e( 'View All Products →', 'tamanbd' ); ?></a>
                    </div>

                    <div class="tmb-grid tmb-grid--4">
                        <?php
                        foreach ( $categories as $cat ) :
                            $thumb_id  = (int) get_term_meta( $cat->term_id, 'thumbnail_id', true );
                            $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'tamanbd-card' ) : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'tamanbd-card' ) : '' );
                            $cat_link  = get_term_link( $cat );
                            if ( is_wp_error( $cat_link ) ) {
                                continue;
                            }
                            ?>
                            <a href="<?php echo esc_url( $cat_link ); ?>" class="tmb-cat-card">
                                <div class="tmb-cat-media">
                                    <?php if ( $thumb_url ) : ?>
                                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" loading="lazy">
                                    <?php endif; ?>
                                </div>
                                <div class="tmb-cat-body">
                                    <div class="tmb-cat-row">
                                        <h3 class="tmb-cat-title"><?php echo esc_html( $cat->name ); ?></h3>
                                        <span class="tmb-cat-count"><?php printf( esc_html__( '%d items', 'tamanbd' ), (int) $cat->count ); ?></span>
                                    </div>
                                    <?php if ( ! empty( $cat->description ) ) : ?>
                                        <p class="tmb-cat-desc"><?php echo esc_html( wp_trim_words( $cat->description, 10 ) ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
    endif;
    ?>

    <!-- 3. Dynamic WooCommerce Collections (Best Selling / Featured / Latest / Special Discounts) -->
    <?php if ( function_exists( 'wc_get_products' ) ) : ?>
    <section id="catalog" class="tmb-section tmb-section--white">
        <div class="tmb-container">
            <div class="tmb-section-head">
                <div>
                    <p class="tmb-kicker"><?php esc_html_e( 'Live Storefront Inventory', 'tamanbd' ); ?></p>
                    <h2 class="tmb-section-title"><?php esc_html_e( 'Curated Store Collections', 'tamanbd' ); ?></h2>
                </div>

                <div class="tmb-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Product collections', 'tamanbd' ); ?>">
                    <button type="button" role="tab" aria-selected="true" class="tmb-tab is-active" data-collection-tab="best_selling"><?php esc_html_e( 'Best Selling', 'tamanbd' ); ?></button>
                    <button type="button" role="tab" aria-selected="false" class="tmb-tab" data-collection-tab="featured"><?php esc_html_e( 'Featured', 'tamanbd' ); ?></button>
                    <button type="button" role="tab" aria-selected="false" class="tmb-tab" data-collection-tab="latest"><?php esc_html_e( 'Latest Products', 'tamanbd' ); ?></button>
                    <button type="button" role="tab" aria-selected="false" class="tmb-tab" data-collection-tab="sale"><?php esc_html_e( 'Special Discounts', 'tamanbd' ); ?></button>
                </div>
            </div>

            <?php
            $collections = array(
                'best_selling' => tamanbd_get_cached_product_ids( 'best_selling', 8 ),
                'featured'     => tamanbd_get_cached_product_ids( 'featured', 8 ),
                'latest'       => tamanbd_get_cached_product_ids( 'latest', 8 ),
                'sale'         => tamanbd_get_cached_product_ids( 'sale', 8 ),
            );

            foreach ( $collections as $key => $ids ) :
                $is_first = ( 'best_selling' === $key );
                ?>
                <div class="tmb-tab-panel <?php echo $is_first ? 'is-active' : ''; ?>" data-collection-panel="<?php echo esc_attr( $key ); ?>" <?php echo $is_first ? '' : 'hidden'; ?>>
                    <?php
                    if ( ! empty( $ids ) ) :
                        $products = wc_get_products(
                            array(
                                'include' => $ids,
                                'orderby' => 'post__in',
                                'limit'   => 8,
                            )
                        );
                        ?>
                        <div class="tmb-grid tmb-grid--4">
                            <?php
                            foreach ( $products as $product ) {
                                if ( is_a( $product, 'WC_Product' ) ) {
                                    tamanbd_render_product_card( $product );
                                }
                            }
                            ?>
                        </div>
                    <?php else : ?>
                        <p class="tmb-empty-notice"><?php esc_html_e( 'No products found in this collection yet.', 'tamanbd' ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- 4. Promotional Offer Band -->
    <section class="tmb-offer-band">
        <div class="tmb-container tmb-offer-inner">
            <div>
                <p class="tmb-kicker tmb-kicker--light"><?php echo esc_html( (string) get_theme_mod( 'tamanbd_offer_kicker', __( 'Seasonal Privilege · Nationwide Bangladesh', 'tamanbd' ) ) ); ?></p>
                <h2 class="tmb-offer-title"><?php echo esc_html( (string) get_theme_mod( 'tamanbd_offer_title', __( 'Complimentary Dhaka Metro Delivery Above ৳2,500', 'tamanbd' ) ) ); ?></h2>
                <p class="tmb-offer-desc"><?php echo esc_html( (string) get_theme_mod( 'tamanbd_offer_desc', __( 'Pay securely with Cash on Delivery upon parcel inspection, or settle instantly via bKash and Nagad.', 'tamanbd' ) ) ); ?></p>
            </div>
            <div>
                <a href="<?php echo esc_url( $shop_url ); ?>" class="tmb-btn tmb-btn--white"><?php esc_html_e( 'Shop Now', 'tamanbd' ); ?></a>
            </div>
        </div>
    </section>

    <!-- 5. Why Choose Us -->
    <section class="tmb-section tmb-section--white">
        <div class="tmb-container">
            <div class="tmb-section-head">
                <div>
                    <p class="tmb-kicker"><?php esc_html_e( 'Service Standards', 'tamanbd' ); ?></p>
                    <h2 class="tmb-section-title"><?php esc_html_e( 'Why Shop with TamanBD', 'tamanbd' ); ?></h2>
                </div>
            </div>
            <div class="tmb-grid tmb-grid--4">
                <div class="tmb-pillar">
                    <h3><?php esc_html_e( 'Verified Quality Craft', 'tamanbd' ); ?></h3>
                    <p><?php esc_html_e( 'Every item is inspected at our Dhaka hub before dispatch for finish and authenticity.', 'tamanbd' ); ?></p>
                </div>
                <div class="tmb-pillar">
                    <h3><?php esc_html_e( 'COD, bKash & Nagad', 'tamanbd' ); ?></h3>
                    <p><?php esc_html_e( 'Flexible Cash on Delivery across Bangladesh or instant mobile wallet checkout.', 'tamanbd' ); ?></p>
                </div>
                <div class="tmb-pillar">
                    <h3><?php esc_html_e( '64-District Delivery', 'tamanbd' ); ?></h3>
                    <p><?php esc_html_e( 'Fast 24-hour Dhaka Metro dispatch and insured 48–72 hour nationwide courier.', 'tamanbd' ); ?></p>
                </div>
                <div class="tmb-pillar">
                    <h3><?php esc_html_e( '7-Day Easy Returns', 'tamanbd' ); ?></h3>
                    <p><?php esc_html_e( 'Simple replacement or refund if an item arrives damaged or differs from specs.', 'tamanbd' ); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Verified Customer Reviews (from WooCommerce Product Reviews) -->
    <?php
    $wc_reviews = get_comments(
        array(
            'number'      => 3,
            'status'      => 'approve',
            'post_status' => 'publish',
            'post_type'   => 'product',
            'type'        => 'review',
        )
    );
    if ( ! empty( $wc_reviews ) ) :
        ?>
        <section id="reviews" class="tmb-section">
            <div class="tmb-container">
                <div class="tmb-section-head">
                    <div>
                        <p class="tmb-kicker"><?php esc_html_e( 'Verified Buyers', 'tamanbd' ); ?></p>
                        <h2 class="tmb-section-title"><?php esc_html_e( 'Customer Reviews', 'tamanbd' ); ?></h2>
                    </div>
                </div>
                <div class="tmb-grid tmb-grid--3">
                    <?php
                    foreach ( $wc_reviews as $review ) :
                        $rating       = (int) get_comment_meta( (int) $review->comment_ID, 'rating', true );
                        $product_name = get_the_title( (int) $review->comment_post_ID );
                        ?>
                        <article class="tmb-review-card">
                            <div class="tmb-review-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'tamanbd' ), $rating ) ); ?>">
                                <?php echo esc_html( str_repeat( '★', max( 1, min( 5, $rating ) ) ) ); ?>
                            </div>
                            <p class="tmb-review-quote">&ldquo;<?php echo esc_html( wp_trim_words( $review->comment_content, 28 ) ); ?>&rdquo;</p>
                            <div class="tmb-review-meta">
                                <strong><?php echo esc_html( $review->comment_author ); ?></strong>
                                <span><?php echo esc_html( $product_name ); ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- 7. Latest Blog Posts -->
    <?php
    $journal_query = new WP_Query(
        array(
            'post_type'           => 'post',
            'posts_per_page'      => 3,
            'post_status'         => 'publish',
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        )
    );
    if ( $journal_query->have_posts() ) :
        ?>
        <section id="journal" class="tmb-section tmb-section--white">
            <div class="tmb-container">
                <div class="tmb-section-head">
                    <div>
                        <p class="tmb-kicker"><?php esc_html_e( 'Editorial Journal', 'tamanbd' ); ?></p>
                        <h2 class="tmb-section-title"><?php esc_html_e( 'Stories & Buying Guides', 'tamanbd' ); ?></h2>
                    </div>
                </div>

                <div class="tmb-grid tmb-grid--3">
                    <?php
                    while ( $journal_query->have_posts() ) :
                        $journal_query->the_post();
                        ?>
                        <article class="tmb-post-card">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="tmb-post-media">
                                    <?php the_post_thumbnail( 'tamanbd-card', array( 'loading' => 'lazy' ) ); ?>
                                </a>
                            <?php endif; ?>
                            <div class="tmb-post-meta">
                                <span><?php echo esc_html( get_the_date() ); ?></span>
                            </div>
                            <h3 class="tmb-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="tmb-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php
get_footer();
