<?php
/**
 * TamanBD Theme Functions and Definitions
 *
 * PHP 8.x compatible, shared-hosting optimized, native WordPress & WooCommerce hooks.
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TAMANBD_VERSION', '1.0.0' );
define( 'TAMANBD_DIR', get_template_directory() );
define( 'TAMANBD_URI', get_template_directory_uri() );

/**
 * Theme setup: menus, HTML5, WooCommerce support, image sizes.
 */
function tamanbd_setup(): void {
    load_theme_textdomain( 'tamanbd', TAMANBD_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 80,
            'width'       => 260,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
            'navigation-widgets',
        )
    );

    // Native WooCommerce support + gallery features.
    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 600,
            'single_image_width'    => 900,
            'product_grid'          => array(
                'default_rows'    => 4,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 4,
            ),
        )
    );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Optimized image sizes for shared hosting bandwidth efficiency.
    add_image_size( 'tamanbd-card', 640, 480, true );
    add_image_size( 'tamanbd-hero', 1440, 810, true );

    register_nav_menus(
        array(
            'primary'     => esc_html__( 'Primary Navigation', 'tamanbd' ),
            'mobile'      => esc_html__( 'Mobile Drawer Navigation', 'tamanbd' ),
            'footer_shop' => esc_html__( 'Footer Shop Links', 'tamanbd' ),
            'footer_care' => esc_html__( 'Footer Customer Care Links', 'tamanbd' ),
        )
    );
}
add_action( 'after_setup_theme', 'tamanbd_setup' );

/**
 * Register sidebar widget area for Shop filters.
 */
function tamanbd_widgets_init(): void {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Shop Filter Sidebar', 'tamanbd' ),
            'id'            => 'tamanbd-shop-sidebar',
            'description'   => esc_html__( 'Widgets placed here appear in the WooCommerce shop & category filter panel.', 'tamanbd' ),
            'before_widget' => '<div id="%1$s" class="tmb-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="tmb-widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'tamanbd_widgets_init' );

/**
 * Enqueue Google Fonts, theme CSS, and deferred Vanilla JS.
 */
function tamanbd_enqueue_assets(): void {
    $css_path = TAMANBD_DIR . '/assets/css/theme.css';
    $js_path  = TAMANBD_DIR . '/assets/js/theme.js';

    $css_ver = file_exists( $css_path ) ? (string) filemtime( $css_path ) : TAMANBD_VERSION;
    $js_ver  = file_exists( $js_path ) ? (string) filemtime( $js_path ) : TAMANBD_VERSION;

    wp_enqueue_style(
        'tamanbd-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'tamanbd-style',
        TAMANBD_URI . '/assets/css/theme.css',
        array( 'tamanbd-fonts' ),
        $css_ver
    );

    wp_enqueue_script(
        'tamanbd-script',
        TAMANBD_URI . '/assets/js/theme.js',
        array(),
        $js_ver,
        array(
            'in_footer' => true,
            'strategy'  => 'defer',
        )
    );

    wp_localize_script(
        'tamanbd-script',
        'tamanbdConfig',
        array(
            'ajaxUrl'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
            'checkoutUrl'  => class_exists( 'WooCommerce' ) ? esc_url_raw( wc_get_checkout_url() ) : '',
            'cartUrl'      => class_exists( 'WooCommerce' ) ? esc_url_raw( wc_get_cart_url() ) : '',
            'currency'     => class_exists( 'WooCommerce' ) ? html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8' ) : '৳',
        )
    );
}
add_action( 'wp_enqueue_scripts', 'tamanbd_enqueue_assets' );

/**
 * Rank Math SEO & WooCommerce compatibility:
 * Remove default WooCommerce breadcrumbs so we render unified semantic breadcrumbs.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Prevent duplicate Product JSON-LD if Rank Math SEO handles schema.
 */
add_filter(
    'woocommerce_structured_data_product',
    static function ( array $markup ): array {
        if ( defined( 'RANK_MATH_VERSION' ) ) {
            return array();
        }
        return $markup;
    },
    99
);

/**
 * Live header cart count + mini-cart drawer refresh via WooCommerce AJAX fragments.
 *
 * @param array<string, string> $fragments Existing fragments.
 * @return array<string, string>
 */
function tamanbd_cart_fragments( array $fragments ): array {
    if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
        return $fragments;
    }

    $count = (int) WC()->cart->get_cart_contents_count();

    ob_start();
    ?>
    <span class="tmb-cart-count" data-cart-count="<?php echo esc_attr( (string) $count ); ?>">
        <?php echo esc_html( (string) $count ); ?>
    </span>
    <?php
    $fragments['span.tmb-cart-count'] = (string) ob_get_clean();

    ob_start();
    echo '<div class="tmb-mini-cart-body">';
    woocommerce_mini_cart();
    echo '</div>';
    $fragments['div.tmb-mini-cart-body'] = (string) ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'tamanbd_cart_fragments' );

/**
 * Support "Buy Now" direct-to-checkout submission on single product pages.
 */
function tamanbd_handle_buy_now_redirect( string $url ): string {
    if (
        isset( $_REQUEST['tamanbd_buy_now'] ) &&
        '1' === sanitize_text_field( wp_unslash( (string) $_REQUEST['tamanbd_buy_now'] ) ) &&
        function_exists( 'wc_get_checkout_url' )
    ) {
        return wc_get_checkout_url();
    }
    return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'tamanbd_handle_buy_now_redirect' );

/**
 * Shared-Hosting Optimization: Cache homepage product ID lists with Transients API.
 *
 * @param string $collection Collection key: best_selling|featured|latest|sale.
 * @param int    $limit      Number of products.
 * @return int[]
 */
function tamanbd_get_cached_product_ids( string $collection, int $limit = 4 ): array {
    if ( ! function_exists( 'wc_get_products' ) ) {
        return array();
    }

    $cache_key = 'tamanbd_hp_' . sanitize_key( $collection ) . '_' . $limit;
    $cached    = get_transient( $cache_key );

    if ( is_array( $cached ) ) {
        return array_map( 'intval', $cached );
    }

    $args = array(
        'status'  => 'publish',
        'limit'   => $limit,
        'return'  => 'ids',
        'orderby' => 'date',
        'order'   => 'DESC',
    );

    switch ( $collection ) {
        case 'best_selling':
            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = 'total_sales';
            break;
        case 'featured':
            $args['featured'] = true;
            break;
        case 'sale':
            $sale_ids = wc_get_product_ids_on_sale();
            if ( empty( $sale_ids ) ) {
                set_transient( $cache_key, array(), 15 * MINUTE_IN_SECONDS );
                return array();
            }
            $args['include'] = array_slice( array_map( 'intval', $sale_ids ), 0, 24 );
            break;
        case 'latest':
        default:
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
    }

    $ids = wc_get_products( $args );
    $ids = is_array( $ids ) ? array_map( 'intval', $ids ) : array();

    set_transient( $cache_key, $ids, 15 * MINUTE_IN_SECONDS );

    return $ids;
}

/**
 * Flush homepage product transients whenever products or orders change.
 */
function tamanbd_flush_product_transients(): void {
    $keys = array( 'best_selling', 'featured', 'latest', 'sale' );
    foreach ( $keys as $key ) {
        delete_transient( 'tamanbd_hp_' . $key . '_4' );
        delete_transient( 'tamanbd_hp_' . $key . '_8' );
    }
}
add_action( 'woocommerce_update_product', 'tamanbd_flush_product_transients' );
add_action( 'woocommerce_new_product', 'tamanbd_flush_product_transients' );
add_action( 'woocommerce_new_order', 'tamanbd_flush_product_transients' );

/**
 * Elementor Compatibility (Free + Pro):
 * Register "TamanBD Layouts" CPT so Header, Footer, and reusable blocks
 * can be edited visually in Elementor Free without requiring Elementor Pro.
 */
function tamanbd_register_elementor_layouts_cpt(): void {
    register_post_type(
        'tmb_template',
        array(
            'labels' => array(
                'name'          => __( 'TamanBD Layouts', 'tamanbd' ),
                'singular_name' => __( 'TamanBD Layout', 'tamanbd' ),
                'add_new_item'  => __( 'Add New Elementor Layout', 'tamanbd' ),
                'edit_item'     => __( 'Edit Layout', 'tamanbd' ),
            ),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-layout',
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'show_in_rest'        => true,
            'supports'            => array( 'title', 'editor', 'elementor' ),
        )
    );

    add_post_type_support( 'tmb_template', 'elementor' );
}
add_action( 'init', 'tamanbd_register_elementor_layouts_cpt' );

/**
 * Enable Elementor Canvas & Full Width on TamanBD Layouts CPT and pages.
 */
function tamanbd_register_elementor_locations( $elementor_theme_manager ): void {
    if ( method_exists( $elementor_theme_manager, 'register_all_core_location' ) ) {
        $elementor_theme_manager->register_all_core_location();
    }
}
add_action( 'elementor/theme/register_locations', 'tamanbd_register_elementor_locations' );

/**
 * Render an Elementor Free layout CPT safely if assigned and Elementor is active.
 */
function tamanbd_render_elementor_layout( int $layout_id ): bool {
    if ( $layout_id <= 0 || ! did_action( 'elementor/loaded' ) ) {
        return false;
    }

    if ( 'publish' !== get_post_status( $layout_id ) ) {
        return false;
    }

    if ( class_exists( '\Elementor\Plugin' ) ) {
        $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $layout_id );
        if ( ! empty( $content ) ) {
            echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor sanitized frontend output.
            return true;
        }
    }

    return false;
}

/**
 * Load Customizer and Template Tags.
 */
require_once TAMANBD_DIR . '/inc/customizer.php';
require_once TAMANBD_DIR . '/inc/template-tags.php';
