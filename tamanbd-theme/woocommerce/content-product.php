<?php
/**
 * WooCommerce Loop Product Card Override
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if ( empty( $product ) || ! is_a( $product, 'WC_Product' ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( 'tmb-wc-loop-item', $product ); ?>>
    <?php tamanbd_render_product_card( $product ); ?>
</li>
