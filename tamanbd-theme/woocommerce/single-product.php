<?php
/**
 * WooCommerce Single Product Template
 *
 * Contiguous purchase module with gallery, regular/sale price, discount badge,
 * stock status, variations, quantity selector, Add to Cart, Buy Now, tabs, and related products.
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

        <?php
        while ( have_posts() ) :
            the_post();
            global $product;
            ?>
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'tmb-single-product', $product ); ?>>
                <?php do_action( 'woocommerce_before_single_product' ); ?>

                <div class="tmb-pdp-grid">
                    <div class="tmb-pdp-gallery">
                        <?php do_action( 'woocommerce_before_single_product_summary' ); ?>
                    </div>

                    <div class="tmb-pdp-summary summary entry-summary">
                        <?php
                        if ( is_a( $product, 'WC_Product' ) ) {
                            $discount = tamanbd_get_discount_percentage( $product );
                            if ( $discount > 0 ) {
                                printf(
                                    '<span class="tmb-pdp-discount">%s</span>',
                                    esc_html( sprintf( __( 'Save %d%%', 'tamanbd' ), $discount ) )
                                );
                            }
                        }
                        do_action( 'woocommerce_single_product_summary' );
                        ?>
                        <?php if ( is_a( $product, 'WC_Product' ) && $product->is_in_stock() && $product->is_purchasable() ) : ?>
                            <button type="button" class="tmb-btn tmb-btn--dark tmb-buy-now-btn" data-tmb-buy-now>
                                <?php esc_html_e( 'Buy Now — Instant Checkout', 'tamanbd' ); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tmb-pdp-after">
                    <?php do_action( 'woocommerce_after_single_product_summary' ); ?>
                </div>
            </div>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer( 'shop' );
