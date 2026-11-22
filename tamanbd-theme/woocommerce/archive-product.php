<?php
/**
 * WooCommerce Product Archive & Category Template
 *
 * Preserves native WooCommerce hooks for filtering, sorting, and pagination.
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header( 'shop' );
?>

<main id="tmb-main-content" class="tmb-section" role="main">
    <div class="tmb-container">
        <?php tamanbd_render_breadcrumbs(); ?>

        <header class="tmb-archive-header">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="tmb-page-title"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

            <?php do_action( 'woocommerce_archive_description' ); ?>
        </header>

        <div class="tmb-archive-layout <?php echo is_active_sidebar( 'tamanbd-shop-sidebar' ) ? 'has-sidebar' : ''; ?>">
            <?php if ( is_active_sidebar( 'tamanbd-shop-sidebar' ) ) : ?>
                <aside class="tmb-shop-sidebar" aria-label="<?php esc_attr_e( 'Product Filters', 'tamanbd' ); ?>">
                    <?php dynamic_sidebar( 'tamanbd-shop-sidebar' ); ?>
                </aside>
            <?php endif; ?>

            <div class="tmb-archive-main">
                <?php if ( woocommerce_product_loop() ) : ?>
                    <div class="tmb-archive-toolbar">
                        <?php do_action( 'woocommerce_before_shop_loop' ); ?>
                    </div>

                    <?php
                    woocommerce_product_loop_start();

                    if ( wc_get_loop_prop( 'total' ) ) {
                        while ( have_posts() ) {
                            the_post();
                            do_action( 'woocommerce_shop_loop' );
                            wc_get_template_part( 'content', 'product' );
                        }
                    }

                    woocommerce_product_loop_end();
                    ?>

                    <div class="tmb-pagination">
                        <?php do_action( 'woocommerce_after_shop_loop' ); ?>
                    </div>
                <?php else : ?>
                    <?php do_action( 'woocommerce_no_products_found' ); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer( 'shop' );
