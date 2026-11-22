<?php
/**
 * TamanBD Template Helper Functions
 *
 * Securely escaped markup helpers for breadcrumbs, discount calculations,
 * and uniform WooCommerce product cards.
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render Rank Math SEO breadcrumbs when active, otherwise native WooCommerce breadcrumbs.
 */
function tamanbd_render_breadcrumbs(): void {
    if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
        echo '<nav class="tmb-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'tamanbd' ) . '">';
        rank_math_the_breadcrumbs();
        echo '</nav>';
        return;
    }

    if ( function_exists( 'woocommerce_breadcrumb' ) ) {
        woocommerce_breadcrumb(
            array(
                'delimiter'   => ' <span aria-hidden="true">/</span> ',
                'wrap_before' => '<nav class="tmb-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'tamanbd' ) . '">',
                'wrap_after'  => '</nav>',
            )
        );
    }
}

/**
 * Calculate maximum discount percentage for simple or variable WooCommerce products.
 *
 * @param WC_Product $product Product object.
 * @return int
 */
function tamanbd_get_discount_percentage( WC_Product $product ): int {
    if ( ! $product->is_on_sale() ) {
        return 0;
    }

    if ( $product->is_type( 'variable' ) && method_exists( $product, 'get_variation_prices' ) ) {
        $prices          = $product->get_variation_prices( true );
        $max_percentage  = 0;
        $regular_prices  = $prices['regular_price'] ?? array();
        $sale_prices     = $prices['sale_price'] ?? array();

        foreach ( $regular_prices as $vid => $reg ) {
            $reg_f  = (float) $reg;
            $sale_f = isset( $sale_prices[ $vid ] ) ? (float) $sale_prices[ $vid ] : 0.0;
            if ( $reg_f > 0 && $sale_f > 0 && $sale_f < $reg_f ) {
                $pct = (int) round( ( ( $reg_f - $sale_f ) / $reg_f ) * 100 );
                if ( $pct > $max_percentage ) {
                    $max_percentage = $pct;
                }
            }
        }
        return $max_percentage;
    }

    $regular = (float) $product->get_regular_price();
    $sale    = (float) $product->get_sale_price();

    if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
        return (int) round( ( ( $regular - $sale ) / $regular ) * 100 );
    }

    return 0;
}

/**
 * Render a single TamanBD product card using live WooCommerce product data.
 *
 * @param WC_Product $product Live WooCommerce product instance.
 */
function tamanbd_render_product_card( WC_Product $product ): void {
    $product_id    = $product->get_id();
    $permalink     = $product->get_permalink();
    $title         = $product->get_name();
    $discount_pct  = tamanbd_get_discount_percentage( $product );
    $rating_avg    = (float) $product->get_average_rating();
    $review_count  = (int) $product->get_review_count();
    $in_stock      = $product->is_in_stock();
    $cats          = wc_get_product_category_list( $product_id, ', ', '', '' );
    $primary_cat   = wp_strip_all_tags( (string) $cats );
    $sku           = $product->get_sku();
    ?>
    <article <?php wc_product_class( 'tmb-card', $product ); ?>>
        <div class="tmb-card-media">
            <a href="<?php echo esc_url( $permalink ); ?>" class="tmb-card-link" aria-label="<?php echo esc_attr( $title ); ?>">
                <?php
                if ( has_post_thumbnail( $product_id ) ) {
                    echo get_the_post_thumbnail(
                        $product_id,
                        'tamanbd-card',
                        array(
                            'class'   => 'tmb-card-img',
                            'loading' => 'lazy',
                            'alt'     => esc_attr( $title ),
                        )
                    );
                } else {
                    echo wc_placeholder_img( 'tamanbd-card', array( 'class' => 'tmb-card-img' ) );
                }
                ?>
            </a>

            <?php if ( $discount_pct > 0 ) : ?>
                <span class="tmb-card-tag"><?php printf( esc_html__( 'Save %d%%', 'tamanbd' ), $discount_pct ); ?></span>
            <?php elseif ( ! $in_stock ) : ?>
                <span class="tmb-card-tag tmb-card-tag--muted"><?php esc_html_e( 'Out of Stock', 'tamanbd' ); ?></span>
            <?php endif; ?>
        </div>

        <div class="tmb-card-body">
            <div>
                <div class="tmb-card-meta">
                    <span class="tmb-card-cat">
                        <?php
                        echo esc_html( $primary_cat ? $primary_cat : __( 'Store Item', 'tamanbd' ) );
                        if ( $sku ) {
                            echo ' · ' . esc_html( $sku );
                        }
                        ?>
                    </span>
                    <?php if ( $rating_avg > 0 ) : ?>
                        <span class="tmb-card-rating" title="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5 (%d reviews)', 'tamanbd' ), number_format_i18n( $rating_avg, 1 ), $review_count ) ); ?>">
                            ★ <?php echo esc_html( number_format_i18n( $rating_avg, 1 ) ); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h3 class="tmb-card-title">
                    <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
                </h3>
            </div>

            <div class="tmb-card-footer">
                <div class="tmb-card-price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </div>

                <?php
                $btn_classes = array(
                    'tmb-card-add',
                    'button',
                    'product_type_' . $product->get_type(),
                    $product->is_purchasable() && $in_stock ? 'add_to_cart_button' : '',
                    $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $in_stock ? 'ajax_add_to_cart' : '',
                );
                ?>
                <a
                    href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                    data-quantity="1"
                    data-product_id="<?php echo esc_attr( (string) $product_id ); ?>"
                    data-product_sku="<?php echo esc_attr( $sku ); ?>"
                    aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
                    class="<?php echo esc_attr( implode( ' ', array_filter( $btn_classes ) ) ); ?>"
                    rel="nofollow"
                >
                    <?php echo esc_html( $product->add_to_cart_text() ); ?>
                </a>
            </div>
        </div>
    </article>
    <?php
}
