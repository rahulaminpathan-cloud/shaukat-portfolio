<?php
/**
 * The header template for TamanBD
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$show_announcement = (bool) get_theme_mod( 'tamanbd_announcement_enable', true );
$announcement_text = (string) get_theme_mod(
    'tamanbd_announcement_text',
    __( 'Free Dhaka delivery on orders above ৳2,500 · Cash on Delivery, bKash & Nagad accepted', 'tamanbd' )
);

$cart_count   = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
$account_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$shop_url     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$wishlist_url = function_exists( 'YITH_WCWL' ) ? YITH_WCWL()->get_wishlist_url() : $account_url;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'tmb-body' ); ?>>
<?php wp_body_open(); ?>

<a class="tmb-skip-link" href="#tmb-main-content"><?php esc_html_e( 'Skip to content', 'tamanbd' ); ?></a>

<?php
$elementor_header_rendered = false;
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
    $elementor_header_rendered = true;
} else {
    $custom_header_id = (int) get_theme_mod( 'tamanbd_elementor_header_id', 0 );
    if ( $custom_header_id > 0 && function_exists( 'tamanbd_render_elementor_layout' ) ) {
        $elementor_header_rendered = tamanbd_render_elementor_layout( $custom_header_id );
    }
}

if ( ! $elementor_header_rendered ) :
?>

<?php if ( $show_announcement && '' !== trim( $announcement_text ) ) : ?>
    <div class="tmb-announcement" id="tmb-announcement">
        <div class="tmb-container tmb-announcement-inner">
            <p class="tmb-announcement-text"><?php echo esc_html( $announcement_text ); ?></p>
            <button type="button" class="tmb-announcement-close" id="tmb-dismiss-announcement" aria-label="<?php esc_attr_e( 'Dismiss announcement', 'tamanbd' ); ?>">
                &times;
            </button>
        </div>
    </div>
<?php endif; ?>

<header class="tmb-header" role="banner">
    <div class="tmb-container tmb-header-inner">
        <!-- Brand & Mobile Menu Toggle -->
        <div class="tmb-brand-group">
            <button type="button" class="tmb-icon-btn tmb-mobile-toggle" id="tmb-mobile-menu-open" aria-label="<?php esc_attr_e( 'Open menu', 'tamanbd' ); ?>" aria-expanded="false" aria-controls="tmb-mobile-drawer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>

            <?php if ( has_custom_logo() ) : ?>
                <div class="tmb-logo-wrap"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="tmb-wordmark" rel="home">
                    <?php bloginfo( 'name' ); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Primary Navigation -->
        <nav class="tmb-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'tamanbd' ); ?>">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'tmb-nav-list',
                        'container'      => false,
                        'depth'          => 2,
                    )
                );
            } else {
                ?>
                <ul class="tmb-nav-list">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'tamanbd' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop', 'tamanbd' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#categories' ) ); ?>"><?php esc_html_e( 'Categories', 'tamanbd' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#journal' ) ); ?>"><?php esc_html_e( 'Journal', 'tamanbd' ); ?></a></li>
                </ul>
                <?php
            }
            ?>
        </nav>

        <!-- Search & Commerce Actions -->
        <div class="tmb-header-actions">
            <form role="search" method="get" class="tmb-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="tmb-sr-only" for="tmb-header-search"><?php esc_html_e( 'Search products', 'tamanbd' ); ?></label>
                <input
                    id="tmb-header-search"
                    type="search"
                    name="s"
                    value="<?php echo esc_attr( get_search_query() ); ?>"
                    placeholder="<?php esc_attr_e( 'Search products...', 'tamanbd' ); ?>"
                    class="tmb-search-input"
                >
                <input type="hidden" name="post_type" value="product">
                <button type="submit" class="tmb-search-submit" aria-label="<?php esc_attr_e( 'Search', 'tamanbd' ); ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </form>

            <button type="button" class="tmb-icon-btn tmb-mobile-search-toggle" id="tmb-mobile-search-toggle" aria-label="<?php esc_attr_e( 'Toggle search', 'tamanbd' ); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>

            <a href="<?php echo esc_url( $account_url ); ?>" class="tmb-action-link tmb-account-link" aria-label="<?php esc_attr_e( 'My Account', 'tamanbd' ); ?>">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span class="tmb-action-label"><?php esc_html_e( 'Account', 'tamanbd' ); ?></span>
            </a>

            <a href="<?php echo esc_url( $wishlist_url ); ?>" class="tmb-icon-btn" aria-label="<?php esc_attr_e( 'Wishlist', 'tamanbd' ); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </a>

            <button type="button" class="tmb-cart-trigger" id="tmb-cart-drawer-open" aria-label="<?php esc_attr_e( 'Open shopping cart', 'tamanbd' ); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span class="tmb-cart-label"><?php esc_html_e( 'Cart', 'tamanbd' ); ?></span>
                <span class="tmb-cart-count" data-cart-count="<?php echo esc_attr( (string) $cart_count ); ?>">
                    <?php echo esc_html( (string) $cart_count ); ?>
                </span>
            </button>
        </div>
    </div>

    <!-- Mobile Expandable Search -->
    <div class="tmb-mobile-search" id="tmb-mobile-search" hidden>
        <div class="tmb-container">
            <form role="search" method="get" class="tmb-search-form tmb-search-form--mobile" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search TamanBD catalog...', 'tamanbd' ); ?>" class="tmb-search-input">
                <input type="hidden" name="post_type" value="product">
                <button type="submit" class="tmb-search-submit"><?php esc_html_e( 'Go', 'tamanbd' ); ?></button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div class="tmb-drawer-backdrop" id="tmb-mobile-drawer" hidden>
    <div class="tmb-drawer-overlay" data-drawer-close></div>
    <aside class="tmb-drawer tmb-drawer--left" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'tamanbd' ); ?>">
        <div class="tmb-drawer-head">
            <span class="tmb-wordmark"><?php bloginfo( 'name' ); ?></span>
            <button type="button" class="tmb-icon-btn" data-drawer-close aria-label="<?php esc_attr_e( 'Close menu', 'tamanbd' ); ?>">&times;</button>
        </div>
        <nav class="tmb-mobile-nav">
            <?php
            if ( has_nav_menu( 'mobile' ) || has_nav_menu( 'primary' ) ) {
                wp_nav_menu(
                    array(
                        'theme_location' => has_nav_menu( 'mobile' ) ? 'mobile' : 'primary',
                        'menu_class'     => 'tmb-mobile-nav-list',
                        'container'      => false,
                    )
                );
            } else {
                ?>
                <ul class="tmb-mobile-nav-list">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'tamanbd' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop All Products', 'tamanbd' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $account_url ); ?>"><?php esc_html_e( 'My Account', 'tamanbd' ); ?></a></li>
                </ul>
                <?php
            }
            ?>
        </nav>
    </aside>
</div>
<?php endif; ?>
