<?php
/**
 * Elementor Widget: TamanBD Promotional Banners
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

class Promo_Banners_Widget extends Widget_Base {

    public function get_name(): string {
        return 'tmb-promo-banners';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Promotional Banners', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-banner';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_banners',
            array( 'label' => esc_html__( 'Banners', 'tamanbd-elementor' ) )
        );

        $repeater = new Repeater();
        $repeater->add_control( 'eyebrow', array( 'label' => esc_html__( 'Eyebrow', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Kitchen & Pantry', 'tamanbd-elementor' ) ) );
        $repeater->add_control( 'title', array( 'label' => esc_html__( 'Title', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Stoneware & Acacia Organizers', 'tamanbd-elementor' ) ) );
        $repeater->add_control( 'description', array( 'label' => esc_html__( 'Description', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXTAREA, 'default' => esc_html__( 'Up to 18% off curated dining and airtight spice rack bundles.', 'tamanbd-elementor' ) ) );
        $repeater->add_control( 'btn_text', array( 'label' => esc_html__( 'Button Text', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Shop Now', 'tamanbd-elementor' ) ) );
        $repeater->add_control( 'btn_link', array( 'label' => esc_html__( 'Link', 'tamanbd-elementor' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '/shop/' ) ) );
        $repeater->add_control( 'image', array( 'label' => esc_html__( 'Banner Image', 'tamanbd-elementor' ), 'type' => Controls_Manager::MEDIA ) );

        $this->add_control(
            'banners',
            array(
                'label'       => esc_html__( 'Banner Items', 'tamanbd-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array( 'eyebrow' => 'Kitchen & Pantry', 'title' => 'Stoneware & Acacia Organizers', 'btn_text' => 'Shop Kitchen' ),
                    array( 'eyebrow' => 'Warm Ambient Lighting', 'title' => 'Brushed Brass & Teak Accents', 'btn_text' => 'Shop Home Decor' ),
                ),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => esc_html__( 'Columns', 'tamanbd-elementor' ),
                'type'           => Controls_Manager::SELECT,
                'options'        => array( '1' => '1', '2' => '2', '3' => '3' ),
                'default'        => '2',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'selectors'      => array(
                    '{{WRAPPER}} .tmb-ew-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array( 'label' => esc_html__( 'Style', 'tamanbd-elementor' ), 'tab' => Controls_Manager::TAB_STYLE )
        );

        $this->add_control(
            'card_bg',
            array(
                'label'     => esc_html__( 'Card Background', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => array( '{{WRAPPER}} .tmb-ew-promo-card' => 'background-color: {{VALUE}};' ),
            )
        );

        $this->add_responsive_control(
            'card_radius',
            array(
                'label'     => esc_html__( 'Border Radius', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array( 'px' => array( 'min' => 0, 'max' => 28 ) ),
                'default'   => array( 'size' => 14, 'unit' => 'px' ),
                'selectors' => array( '{{WRAPPER}} .tmb-ew-promo-card' => 'border-radius: {{SIZE}}{{UNIT}};' ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array( 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .tmb-ew-promo-title' )
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s       = $this->get_settings_for_display();
        $banners = $s['banners'] ?? array();
        if ( empty( $banners ) ) {
            return;
        }
        ?>
        <div class="tmb-ew-grid">
            <?php foreach ( $banners as $banner ) : ?>
                <div class="tmb-ew-promo-card">
                    <div class="tmb-ew-promo-copy">
                        <div>
                            <p class="tmb-kicker"><?php echo esc_html( $banner['eyebrow'] ?? '' ); ?></p>
                            <h3 class="tmb-ew-promo-title" style="font-family:var(--tmb-font-display);font-size:24px;margin:6px 0 0;"><?php echo esc_html( $banner['title'] ?? '' ); ?></h3>
                            <p style="font-size:13px;color:#57534E;margin:8px 0 0;"><?php echo esc_html( $banner['description'] ?? '' ); ?></p>
                        </div>
                        <?php if ( ! empty( $banner['btn_text'] ) ) : ?>
                            <a href="<?php echo esc_url( $banner['btn_link']['url'] ?? '/shop/' ); ?>" class="tmb-text-link">
                                <?php echo esc_html( $banner['btn_text'] ); ?> &rarr;
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="tmb-ew-promo-media">
                        <?php if ( ! empty( $banner['image']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $banner['image']['url'] ); ?>" alt="<?php echo esc_attr( $banner['title'] ?? '' ); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
