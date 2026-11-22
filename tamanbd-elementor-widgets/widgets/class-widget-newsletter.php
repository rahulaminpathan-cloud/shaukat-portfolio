<?php
/**
 * Elementor Widget: TamanBD Newsletter Subscription Box
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Newsletter_Widget extends Widget_Base {

    public function get_name(): string {
        return 'tmb-newsletter';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Newsletter Signup', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-mail';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            array( 'label' => esc_html__( 'Newsletter Content', 'tamanbd-elementor' ) )
        );

        $this->add_control( 'kicker', array( 'label' => esc_html__( 'Kicker', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Restock & Dispatch Notes', 'tamanbd-elementor' ) ) );
        $this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Receive New Artisan Drops & Seasonal Offers', 'tamanbd-elementor' ) ) );
        $this->add_control( 'description', array( 'label' => esc_html__( 'Description', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXTAREA, 'default' => esc_html__( 'Monthly highlights from Dhaka workshops and early access to seasonal bundles.', 'tamanbd-elementor' ) ) );
        $this->add_control( 'placeholder', array( 'label' => esc_html__( 'Email Placeholder', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Enter your email address', 'tamanbd-elementor' ) ) );
        $this->add_control( 'button_text', array( 'label' => esc_html__( 'Button Text', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Subscribe', 'tamanbd-elementor' ) ) );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array( 'label' => esc_html__( 'Box & Button Style', 'tamanbd-elementor' ), 'tab' => Controls_Manager::TAB_STYLE )
        );

        $this->add_control(
            'box_bg',
            array(
                'label'     => esc_html__( 'Background Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F3F1EC',
                'selectors' => array( '{{WRAPPER}} .tmb-ew-newsletter-box' => 'background-color: {{VALUE}};' ),
            )
        );
        $this->add_control(
            'button_bg',
            array(
                'label'     => esc_html__( 'Button Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0F4C3A',
                'selectors' => array( '{{WRAPPER}} .tmb-ew-newsletter-btn' => 'background-color: {{VALUE}};' ),
            )
        );
        $this->add_responsive_control(
            'box_radius',
            array(
                'label'     => esc_html__( 'Border Radius', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
                'default'   => array( 'size' => 16, 'unit' => 'px' ),
                'selectors' => array( '{{WRAPPER}} .tmb-ew-newsletter-box' => 'border-radius: {{SIZE}}{{UNIT}};' ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .tmb-ew-newsletter-title' )
        );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <div class="tmb-ew-newsletter-box">
            <div>
                <?php if ( ! empty( $s['kicker'] ) ) : ?>
                    <p class="tmb-kicker"><?php echo esc_html( $s['kicker'] ); ?></p>
                <?php endif; ?>
                <h2 class="tmb-ew-newsletter-title" style="font-family:var(--tmb-font-display);font-size:30px;margin:6px 0 0;">
                    <?php echo esc_html( $s['heading'] ?? '' ); ?>
                </h2>
                <p style="font-size:14px;color:#57534E;margin:8px 0 0;"><?php echo esc_html( $s['description'] ?? '' ); ?></p>
            </div>
            <form class="tmb-ew-newsletter-form" method="post">
                <input type="email" name="email" required placeholder="<?php echo esc_attr( $s['placeholder'] ?? '' ); ?>" class="tmb-ew-newsletter-input">
                <button type="submit" class="tmb-ew-newsletter-btn"><?php echo esc_html( $s['button_text'] ?? 'Subscribe' ); ?></button>
                <div class="tmb-ew-newsletter-msg" aria-live="polite"></div>
            </form>
        </div>
        <?php
    }
}
