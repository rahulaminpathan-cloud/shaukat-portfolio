<?php
/**
 * The footer template and slide-out WooCommerce mini-cart drawer for TamanBD
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$address = (string) get_theme_mod( 'tamanbd_store_address', __( 'House #2, Road #12, Merul Badda, DIT Project, Dhaka-1212', 'tamanbd' ) );
$phone   = (string) get_theme_mod( 'tamanbd_store_phone', '+880 1823-920184' );
$email   = (string) get_theme_mod( 'tamanbd_store_email', 'info@tamanbd.com' );

$elementor_footer_rendered = false;
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
    $elementor_footer_rendered = true;
} else {
    $custom_footer_id = (int) get_theme_mod( 'tamanbd_elementor_footer_id', 0 );
    if ( $custom_footer_id > 0 && function_exists( 'tamanbd_render_elementor_layout' ) ) {
        $elementor_footer_rendered = tamanbd_render_elementor_layout( $custom_footer_id );
    }
}

if ( ! $elementor_footer_rendered ) :
?>

<footer class="tmb-footer" role="contentinfo">
    <div class="tmb-container">
        <div class="tmb-footer-grid">
            <div class="tmb-footer-brand">
                <span class="tmb-footer-wordmark"><?php bloginfo( 'name' ); ?></span>
                <p class="tmb-footer-desc">
                    <?php
                    $desc = get_bloginfo( 'description' );
                    echo esc_html( $desc ? $desc : __( 'Curated Bangladeshi lifestyle marketplace offering home living, kitchenware, botanical care, and handloom crafts.', 'tamanbd' ) );
                    ?>
                </p>
                <ul class="tmb-footer-contact">
                    <li><?php echo esc_html( $address ); ?></li>
                    <li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
                    <li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
                </ul>
            </div>

            <div class="tmb-footer-col">
                <h3 class="tmb-footer-heading"><?php esc_html_e( 'Shop Departments', 'tamanbd' ); ?></h3>
                <?php
                if ( has_nav_menu( 'footer_shop' ) ) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer_shop',
                            'menu_class'     => 'tmb-footer-links',
                            'container'      => false,
                            'depth'          => 1,
                        )
                    );
                } elseif ( function_exists( 'get_terms' ) && taxonomy_exists( 'product_cat' ) ) {
                    $cats = get_terms(
                        array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'number'     => 5,
                            'parent'     => 0,
                        )
                    );
                    if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
                        echo '<ul class="tmb-footer-links">';
                        foreach ( $cats as $cat ) {
                            $link = get_term_link( $cat );
                            if ( ! is_wp_error( $link ) ) {
                                echo '<li><a href="' . esc_url( $link ) . '">' . esc_html( $cat->name ) . '</a></li>';
                            }
                        }
                        echo '</ul>';
                    }
                }
                ?>
            </div>

            <div class="tmb-footer-col">
                <h3 class="tmb-footer-heading"><?php esc_html_e( 'Customer Care', 'tamanbd' ); ?></h3>
                <?php
                if ( has_nav_menu( 'footer_care' ) ) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer_care',
                            'menu_class'     => 'tmb-footer-links',
                            'container'      => false,
                            'depth'          => 1,
                        )
                    );
                } elseif ( function_exists( 'wc_get_page_permalink' ) ) {
                    ?>
                    <ul class="tmb-footer-links">
                        <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'My Account & Orders', 'tamanbd' ); ?></a></li>
                        <li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Shopping Cart', 'tamanbd' ); ?></a></li>
                        <li><a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Checkout', 'tamanbd' ); ?></a></li>
                        <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'All Products', 'tamanbd' ); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>

            <div class="tmb-footer-col">
                <h3 class="tmb-footer-heading"><?php esc_html_e( 'Accepted Payments', 'tamanbd' ); ?></h3>
                <p class="tmb-footer-pay">
                    <?php esc_html_e( 'Cash on Delivery · bKash · Nagad · Visa / Mastercard', 'tamanbd' ); ?>
                </p>
            </div>
        </div>

        <div class="tmb-footer-bottom">
            <p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'tamanbd' ); ?></p>
        </div>
    </div>
</footer>
<?php endif; ?>

<!-- Slide-Out WooCommerce Mini-Cart Drawer -->
<?php if ( function_exists( 'WC' ) ) : ?>
<div class="tmb-drawer-backdrop" id="tmb-cart-drawer" hidden>
    <div class="tmb-drawer-overlay" data-drawer-close></div>
    <aside class="tmb-drawer tmb-drawer--right" aria-label="<?php esc_attr_e( 'Shopping Cart', 'tamanbd' ); ?>">
        <div class="tmb-drawer-head">
            <h2 class="tmb-drawer-title"><?php esc_html_e( 'Your Shopping Bag', 'tamanbd' ); ?></h2>
            <button type="button" class="tmb-icon-btn" data-drawer-close aria-label="<?php esc_attr_e( 'Close cart', 'tamanbd' ); ?>">&times;</button>
        </div>
        <div class="tmb-mini-cart-body">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </aside>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
