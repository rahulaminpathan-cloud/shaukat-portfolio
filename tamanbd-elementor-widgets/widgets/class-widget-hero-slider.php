<?php
/**
 * Elementor Widget: TamanBD Hero Slider
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Hero_Slider_Widget extends Widget_Base {

    public function get_name(): string {
        return 'tmb-hero-slider';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Hero Slider', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-slides';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_slides',
            array( 'label' => esc_html__( 'Hero Slides', 'tamanbd-elementor' ) )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'kicker',
            array(
                'label'   => esc_html__( 'Kicker Label', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Dhaka Living Edit', 'tamanbd-elementor' ),
            )
        );

        $repeater->add_control(
            'headline',
            array(
                'label'   => esc_html__( 'Heading', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Warm Teak & Handcrafted Home Essentials', 'tamanbd-elementor' ),
            )
        );

        $repeater->add_control(
            'description',
            array(
                'label'   => esc_html__( 'Description', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Architectural shelving, brushed brass lighting, and artisanal stoneware crafted for modern Bangladeshi homes.', 'tamanbd-elementor' ),
            )
        );

        $repeater->add_control(
            'button_text',
            array(
                'label'   => esc_html__( 'Button Text', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Explore Collection', 'tamanbd-elementor' ),
            )
        );

        $repeater->add_control(
            'button_link',
            array(
                'label'   => esc_html__( 'Button Link', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::URL,
                'default' => array( 'url' => '/shop/' ),
            )
        );

        $repeater->add_control(
            'slide_image',
            array(
                'label' => esc_html__( 'Slide Image (16:9 recommended)', 'tamanbd-elementor' ),
                'type'  => Controls_Manager::MEDIA,
            )
        );

        $this->add_control(
            'slides',
            array(
                'label'       => esc_html__( 'Slides', 'tamanbd-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'kicker'      => esc_html__( 'Dhaka Living Edit', 'tamanbd-elementor' ),
                        'headline'    => esc_html__( 'Warm Teak & Handcrafted Home Essentials', 'tamanbd-elementor' ),
                        'description' => esc_html__( 'Architectural shelving, brushed brass lighting, and artisanal stoneware delivered across Bangladesh.', 'tamanbd-elementor' ),
                        'button_text' => esc_html__( 'Shop Home & Living', 'tamanbd-elementor' ),
                    ),
                ),
                'title_field' => '{{{ headline }}}',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array(
                'label' => esc_html__( 'Hero Styling', 'tamanbd-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'section_bg',
            array(
                'label'     => esc_html__( 'Section Background', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F3F1EC',
                'selectors' => array( '{{WRAPPER}} .tmb-hero' => 'background-color: {{VALUE}};' ),
            )
        );

        $this->add_control(
            'heading_color',
            array(
                'label'     => esc_html__( 'Heading Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1C1917',
                'selectors' => array( '{{WRAPPER}} .tmb-hero-title' => 'color: {{VALUE}};' ),
            )
        );

        $this->add_control(
            'button_bg',
            array(
                'label'     => esc_html__( 'Button Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0F4C3A',
                'selectors' => array( '{{WRAPPER}} .tmb-btn--primary' => 'background-color: {{VALUE}};' ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'heading_typography',
                'label'    => esc_html__( 'Heading Typography', 'tamanbd-elementor' ),
                'selector' => '{{WRAPPER}} .tmb-hero-title',
            )
        );

        $this->add_responsive_control(
            'card_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'tamanbd-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
                'default'    => array( 'size' => 16, 'unit' => 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tmb-hero-copy, {{WRAPPER}} .tmb-hero-media' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'section_padding',
            array(
                'label'      => esc_html__( 'Section Padding', 'tamanbd-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .tmb-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s      = $this->get_settings_for_display();
        $slides = $s['slides'] ?? array();
        if ( empty( $slides ) ) {
            return;
        }
        $fallback_img = defined( 'TAMANBD_URI' ) ? TAMANBD_URI . '/assets/images/hero-living.jpg' : '';
        ?>
        <section class="tmb-hero">
            <div class="tmb-container">
                <div class="tmb-hero-slider" data-hero-slider>
                    <?php foreach ( $slides as $idx => $slide ) : ?>
                        <?php
                        $img_url = ! empty( $slide['slide_image']['url'] ) ? $slide['slide_image']['url'] : $fallback_img;
                        $btn_url = ! empty( $slide['button_link']['url'] ) ? $slide['button_link']['url'] : '/shop/';
                        ?>
                        <div class="tmb-hero-slide <?php echo 0 === $idx ? 'is-active' : ''; ?>" data-hero-slide="<?php echo esc_attr( (string) $idx ); ?>">
                            <div class="tmb-hero-copy">
                                <div>
                                    <?php if ( ! empty( $slide['kicker'] ) ) : ?>
                                        <p class="tmb-kicker"><?php echo esc_html( $slide['kicker'] ); ?></p>
                                    <?php endif; ?>
                                    <h1 class="tmb-hero-title"><?php echo esc_html( $slide['headline'] ?? '' ); ?></h1>
                                    <p class="tmb-hero-subtitle"><?php echo esc_html( $slide['description'] ?? '' ); ?></p>
                                </div>
                                <div class="tmb-hero-actions">
                                    <?php if ( ! empty( $slide['button_text'] ) ) : ?>
                                        <a href="<?php echo esc_url( $btn_url ); ?>" class="tmb-btn tmb-btn--primary">
                                            <?php echo esc_html( $slide['button_text'] ); ?> &rarr;
                                        </a>
                                    <?php endif; ?>
                                    <?php if ( count( $slides ) > 1 ) : ?>
                                        <div class="tmb-slider-nav">
                                            <button type="button" class="tmb-slider-btn" data-hero-prev aria-label="<?php esc_attr_e( 'Previous', 'tamanbd-elementor' ); ?>">&lsaquo;</button>
                                            <button type="button" class="tmb-slider-btn" data-hero-next aria-label="<?php esc_attr_e( 'Next', 'tamanbd-elementor' ); ?>">&rsaquo;</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="tmb-hero-media">
                                <?php if ( $img_url ) : ?>
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $slide['headline'] ?? '' ); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
