<?php
/**
 * Elementor Widget: TamanBD Store Footer
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Footer_Widget extends Widget_Base {
    use TamanBD_Widget_Helpers;

    public function get_name(): string {
        return 'tmb-footer';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Store Footer', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-footer';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            array( 'label' => esc_html__( 'Footer Content', 'tamanbd-elementor' ) )
        );

        $this->add_control( 'brand_name', array( 'label' => esc_html__( 'Brand Title', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => get_bloginfo( 'name' ) ?: 'TamanBD' ) );
        $this->add_control( 'brand_desc', array( 'label' => esc_html__( 'Store Description', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXTAREA, 'default' => esc_html__( 'Curated Bangladeshi lifestyle marketplace offering solid teak furniture, stoneware dining, handloom cottons, and botanical skincare.', 'tamanbd-elementor' ) ) );
        $this->add_control( 'address', array( 'label' => esc_html__( 'Address', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => 'House #2, Road #12, Merul Badda, DIT Project, Dhaka-1212' ) );
        $this->add_control( 'phone', array( 'label' => esc_html__( 'Phone', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => '+880 1823-920184' ) );
        $this->add_control( 'email', array( 'label' => esc_html__( 'Email', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => 'info@tamanbd.com' ) );

        $this->add_control( 'menu_shop', array( 'label' => esc_html__( 'Shop Links Menu', 'tamanbd-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => $this->get_wp_menus_options() ) );
        $this->add_control( 'menu_care', array( 'label' => esc_html__( 'Customer Care Menu', 'tamanbd-elementor' ), 'type' => Controls_Manager::SELECT, 'options' => $this->get_wp_menus_options() ) );

        $this->add_control( 'payments_text', array( 'label' => esc_html__( 'Accepted Payments Text', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => 'Cash on Delivery · bKash · Nagad · Visa / Mastercard' ) );
        $this->add_control( 'copyright_text', array( 'label' => esc_html__( 'Copyright Line', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => '© 2026 TamanBD. All rights reserved.' ) );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array( 'label' => esc_html__( 'Footer Style', 'tamanbd-elementor' ), 'tab' => Controls_Manager::TAB_STYLE )
        );

        $this->add_control(
            'footer_bg',
            array(
                'label'     => esc_html__( 'Background Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1C1917',
                'selectors' => array( '{{WRAPPER}} .tmb-footer' => 'background-color: {{VALUE}};' ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array( 'name' => 'footer_typography', 'selector' => '{{WRAPPER}} .tmb-footer' )
        );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <footer class="tmb-footer">
            <div class="tmb-container">
                <div class="tmb-footer-grid">
                    <div class="tmb-footer-brand">
                        <span class="tmb-footer-wordmark"><?php echo esc_html( $s['brand_name'] ?? 'TamanBD' ); ?></span>
                        <p class="tmb-footer-desc"><?php echo esc_html( $s['brand_desc'] ?? '' ); ?></p>
                        <ul class="tmb-footer-contact">
                            <?php if ( ! empty( $s['address'] ) ) : ?><li><?php echo esc_html( $s['address'] ); ?></li><?php endif; ?>
                            <?php if ( ! empty( $s['phone'] ) ) : ?><li><?php echo esc_html( $s['phone'] ); ?></li><?php endif; ?>
                            <?php if ( ! empty( $s['email'] ) ) : ?><li><?php echo esc_html( $s['email'] ); ?></li><?php endif; ?>
                        </ul>
                    </div>

                    <div class="tmb-footer-col">
                        <h3 class="tmb-footer-heading"><?php esc_html_e( 'Shop Departments', 'tamanbd-elementor' ); ?></h3>
                        <?php
                        if ( ! empty( $s['menu_shop'] ) ) {
                            wp_nav_menu( array( 'menu' => (int) $s['menu_shop'], 'menu_class' => 'tmb-footer-links', 'container' => false, 'depth' => 1 ) );
                        } elseif ( has_nav_menu( 'footer_shop' ) ) {
                            wp_nav_menu( array( 'theme_location' => 'footer_shop', 'menu_class' => 'tmb-footer-links', 'container' => false, 'depth' => 1 ) );
                        }
                        ?>
                    </div>

                    <div class="tmb-footer-col">
                        <h3 class="tmb-footer-heading"><?php esc_html_e( 'Customer Care', 'tamanbd-elementor' ); ?></h3>
                        <?php
                        if ( ! empty( $s['menu_care'] ) ) {
                            wp_nav_menu( array( 'menu' => (int) $s['menu_care'], 'menu_class' => 'tmb-footer-links', 'container' => false, 'depth' => 1 ) );
                        } elseif ( has_nav_menu( 'footer_care' ) ) {
                            wp_nav_menu( array( 'theme_location' => 'footer_care', 'menu_class' => 'tmb-footer-links', 'container' => false, 'depth' => 1 ) );
                        }
                        ?>
                    </div>

                    <div class="tmb-footer-col">
                        <h3 class="tmb-footer-heading"><?php esc_html_e( 'Accepted Payments', 'tamanbd-elementor' ); ?></h3>
                        <p class="tmb-footer-pay"><?php echo esc_html( $s['payments_text'] ?? '' ); ?></p>
                    </div>
                </div>

                <div class="tmb-footer-bottom">
                    <p><?php echo esc_html( $s['copyright_text'] ?? '' ); ?></p>
                </div>
            </div>
        </footer>
        <?php
    }
}
