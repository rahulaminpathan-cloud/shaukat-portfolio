<?php
/**
 * Plugin Name: TamanBD Elementor Widgets
 * Plugin URI: https://tamanbd.com/
 * Description: Lightweight, PHP 8.x compatible custom Elementor widgets for TamanBD WooCommerce storefronts. Works with Elementor Free (no Elementor Pro required).
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 8.0
 * Author: TamanBD Engineering
 * Text Domain: tamanbd-elementor
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TMB_EW_VERSION', '1.0.0' );
define( 'TMB_EW_PATH', plugin_dir_path( __FILE__ ) );
define( 'TMB_EW_URL', plugin_dir_url( __FILE__ ) );

final class TamanBD_Elementor_Widgets_Plugin {

    private static ?self $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    public function init(): void {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'notice_missing_elementor' ) );
            return;
        }

        add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
        add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
        add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
        add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // AJAX Newsletter endpoint.
        add_action( 'wp_ajax_tamanbd_newsletter_subscribe', array( $this, 'handle_newsletter_subscribe' ) );
        add_action( 'wp_ajax_nopriv_tamanbd_newsletter_subscribe', array( $this, 'handle_newsletter_subscribe' ) );
    }

    public function notice_missing_elementor(): void {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        echo '<div class="notice notice-warning is-dismissible"><p>';
        esc_html_e( 'TamanBD Elementor Widgets requires the Elementor plugin to be installed and activated.', 'tamanbd-elementor' );
        echo '</p></div>';
    }

    public function register_category( \Elementor\Elements_Manager $elements_manager ): void {
        $elements_manager->add_category(
            'tamanbd-storefront',
            array(
                'title' => esc_html__( 'TamanBD Storefront', 'tamanbd-elementor' ),
                'icon'  => 'eicon-cart-medium',
            )
        );
    }

    public function enqueue_styles(): void {
        wp_enqueue_style(
            'tamanbd-ew-styles',
            TMB_EW_URL . 'assets/css/widgets.css',
            array(),
            TMB_EW_VERSION
        );
    }

    public function enqueue_scripts(): void {
        wp_enqueue_script(
            'tamanbd-ew-scripts',
            TMB_EW_URL . 'assets/js/widgets.js',
            array( 'jquery' ),
            TMB_EW_VERSION,
            true
        );

        wp_localize_script(
            'tamanbd-ew-scripts',
            'tmbEwConfig',
            array(
                'ajaxUrl' => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
                'nonce'   => wp_create_nonce( 'tamanbd_ew_newsletter' ),
            )
        );
    }

    public function register_widgets( \Elementor\Widgets_Manager $widgets_manager ): void {
        require_once TMB_EW_PATH . 'widgets/trait-tamanbd-helpers.php';

        $widget_files = array(
            'class-widget-header.php'         => '\TamanBD\Elementor\Widgets\Header_Widget',
            'class-widget-hero-slider.php'    => '\TamanBD\Elementor\Widgets\Hero_Slider_Widget',
            'class-widget-promo-banners.php'  => '\TamanBD\Elementor\Widgets\Promo_Banners_Widget',
            'class-widget-categories-grid.php'=> '\TamanBD\Elementor\Widgets\Categories_Grid_Widget',
            'class-widget-products-grid.php'  => '\TamanBD\Elementor\Widgets\Products_Grid_Widget',
            'class-widget-why-choose-us.php'  => '\TamanBD\Elementor\Widgets\Why_Choose_Us_Widget',
            'class-widget-testimonials.php'   => '\TamanBD\Elementor\Widgets\Testimonials_Widget',
            'class-widget-blog-posts.php'     => '\TamanBD\Elementor\Widgets\Blog_Posts_Widget',
            'class-widget-newsletter.php'     => '\TamanBD\Elementor\Widgets\Newsletter_Widget',
            'class-widget-footer.php'         => '\TamanBD\Elementor\Widgets\Footer_Widget',
        );

        foreach ( $widget_files as $file => $class_name ) {
            $path = TMB_EW_PATH . 'widgets/' . $file;
            if ( file_exists( $path ) ) {
                require_once $path;
                if ( class_exists( $class_name ) ) {
                    $widgets_manager->register( new $class_name() );
                }
            }
        }
    }

    public function handle_newsletter_subscribe(): void {
        check_ajax_referer( 'tamanbd_ew_newsletter', 'nonce' );

        $raw_email = isset( $_POST['email'] ) ? wp_unslash( (string) $_POST['email'] ) : '';
        $email     = sanitize_email( $raw_email );

        if ( ! is_email( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'tamanbd-elementor' ) ) );
        }

        $subscribers = get_option( 'tamanbd_newsletter_subscribers', array() );
        if ( ! is_array( $subscribers ) ) {
            $subscribers = array();
        }

        if ( ! in_array( $email, $subscribers, true ) ) {
            $subscribers[] = $email;
            update_option( 'tamanbd_newsletter_subscribers', $subscribers, false );
        }

        wp_send_json_success( array( 'message' => __( 'Thank you for subscribing to TamanBD updates.', 'tamanbd-elementor' ) ) );
    }
}

TamanBD_Elementor_Widgets_Plugin::instance();
