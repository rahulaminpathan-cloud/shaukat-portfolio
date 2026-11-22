<?php
/**
 * Elementor Widget: TamanBD Header (Announcement + Logo + Nav + Search + Cart)
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Header_Widget extends Widget_Base {
    use TamanBD_Widget_Helpers;

    public function get_name(): string {
        return 'tmb-header';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Store Header', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-header';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            array( 'label' => esc_html__( 'Header Content', 'tamanbd-elementor' ) )
        );

        $this->add_control(
            'show_announcement',
            array(
                'label'   => esc_html__( 'Show Announcement Bar', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->add_control(
            'announcement_text',
            array(
                'label'     => esc_html__( 'Announcement Text', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Free Dhaka delivery on orders above ৳2,500 · Cash on Delivery, bKash & Nagad accepted', 'tamanbd-elementor' ),
                'condition' => array( 'show_announcement' => 'yes' ),
            )
        );

        $this->add_control(
            'logo_image',
            array(
                'label' => esc_html__( 'Custom Logo Image (optional)', 'tamanbd-elementor' ),
                'type'  => Controls_Manager::MEDIA,
            )
        );

        $this->add_control(
            'brand_text',
            array(
                'label'   => esc_html__( 'Brand Wordmark (if no image)', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => get_bloginfo( 'name' ) ?: 'TamanBD',
            )
        );

        $this->add_control(
            'menu_id',
            array(
                'label'   => esc_html__( 'Navigation Menu', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'options' => $this->get_wp_menus_options(),
            )
        );

        $this->add_control(
            'show_search',
            array(
                'label'   => esc_html__( 'Show Product Search Bar', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->add_control(
            'search_placeholder',
            array(
                'label'     => esc_html__( 'Search Placeholder', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Search products...', 'tamanbd-elementor' ),
                'condition' => array( 'show_search' => 'yes' ),
            )
        );

        $this->add_control(
            'show_account',
            array(
                'label'   => esc_html__( 'Show My Account Link', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->add_control(
            'show_cart',
            array(
                'label'   => esc_html__( 'Show Cart Button', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array(
                'label' => esc_html__( 'Header Style', 'tamanbd-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'announcement_bg',
            array(
                'label'     => esc_html__( 'Announcement Background', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0F4C3A',
                'selectors' => array( '{{WRAPPER}} .tmb-announcement' => 'background-color: {{VALUE}};' ),
            )
        );

        $this->add_control(
            'header_bg',
            array(
                'label'     => esc_html__( 'Header Background', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FAF9F6',
                'selectors' => array( '{{WRAPPER}} .tmb-ew-header-wrap' => 'background-color: {{VALUE}};' ),
            )
        );

        $this->add_control(
            'nav_color',
            array(
                'label'     => esc_html__( 'Navigation Link Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#44403C',
                'selectors' => array( '{{WRAPPER}} .tmb-nav-list a' => 'color: {{VALUE}};' ),
            )
        );

        $this->add_control(
            'cart_btn_bg',
            array(
                'label'     => esc_html__( 'Cart Button Background', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0F4C3A',
                'selectors' => array( '{{WRAPPER}} .tmb-cart-trigger' => 'background-color: {{VALUE}};' ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'nav_typography',
                'label'    => esc_html__( 'Navigation Typography', 'tamanbd-elementor' ),
                'selector' => '{{WRAPPER}} .tmb-nav-list a',
            )
        );

        $this->add_responsive_control(
            'header_padding',
            array(
                'label'      => esc_html__( 'Header Padding', 'tamanbd-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tmb-header-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s           = $this->get_settings_for_display();
        $cart_count  = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
        $account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
        $cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );

        if ( 'yes' === ( $s['show_announcement'] ?? '' ) && ! empty( $s['announcement_text'] ) ) :
            ?>
            <div class="tmb-announcement">
                <div class="tmb-container tmb-announcement-inner">
                    <p class="tmb-announcement-text"><?php echo esc_html( $s['announcement_text'] ); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="tmb-ew-header-wrap">
            <div class="tmb-container tmb-header-inner">
                <div class="tmb-brand-group">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="tmb-wordmark" rel="home">
                        <?php if ( ! empty( $s['logo_image']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $s['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( $s['brand_text'] ); ?>" style="max-height:42px;width:auto;">
                        <?php else : ?>
                            <?php echo esc_html( $s['brand_text'] ); ?>
                        <?php endif; ?>
                    </a>
                </div>

                <nav class="tmb-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'tamanbd-elementor' ); ?>">
                    <?php
                    if ( ! empty( $s['menu_id'] ) ) {
                        wp_nav_menu(
                            array(
                                'menu'       => (int) $s['menu_id'],
                                'menu_class' => 'tmb-nav-list',
                                'container'  => false,
                                'depth'      => 2,
                            )
                        );
                    } elseif ( has_nav_menu( 'primary' ) ) {
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_class'     => 'tmb-nav-list',
                                'container'      => false,
                                'depth'          => 2,
                            )
                        );
                    }
                    ?>
                </nav>

                <div class="tmb-header-actions">
                    <?php if ( 'yes' === ( $s['show_search'] ?? '' ) ) : ?>
                        <form role="search" method="get" class="tmb-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $s['search_placeholder'] ?? '' ); ?>" class="tmb-search-input">
                            <input type="hidden" name="post_type" value="product">
                            <button type="submit" class="tmb-search-submit" aria-label="<?php esc_attr_e( 'Search', 'tamanbd-elementor' ); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ( 'yes' === ( $s['show_account'] ?? '' ) ) : ?>
                        <a href="<?php echo esc_url( $account_url ); ?>" class="tmb-action-link">
                            <span><?php esc_html_e( 'Account', 'tamanbd-elementor' ); ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if ( 'yes' === ( $s['show_cart'] ?? '' ) ) : ?>
                        <a href="<?php echo esc_url( $cart_url ); ?>" class="tmb-cart-trigger">
                            <span class="tmb-cart-label"><?php esc_html_e( 'Cart', 'tamanbd-elementor' ); ?></span>
                            <span class="tmb-cart-count"><?php echo esc_html( (string) $cart_count ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
