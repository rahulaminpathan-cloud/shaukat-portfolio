<?php
/**
 * Elementor Widget: TamanBD Why Choose Us Pillars
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

class Why_Choose_Us_Widget extends Widget_Base {

    public function get_name(): string {
        return 'tmb-why-choose-us';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Why Choose Us', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-check-circle-o';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            array( 'label' => esc_html__( 'Service Pillars', 'tamanbd-elementor' ) )
        );

        $this->add_control( 'kicker', array( 'label' => esc_html__( 'Kicker', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Service Standards', 'tamanbd-elementor' ) ) );
        $this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Why Shop with TamanBD', 'tamanbd-elementor' ) ) );

        $repeater = new Repeater();
        $repeater->add_control( 'title', array( 'label' => esc_html__( 'Title', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Verified Quality Craft', 'tamanbd-elementor' ) ) );
        $repeater->add_control( 'description', array( 'label' => esc_html__( 'Description', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXTAREA, 'default' => esc_html__( 'Every item is inspected at our Dhaka hub prior to dispatch.', 'tamanbd-elementor' ) ) );

        $this->add_control(
            'pillars',
            array(
                'label'       => esc_html__( 'Pillars', 'tamanbd-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array( 'title' => 'Verified Quality Craft', 'description' => 'Inspected at our Dhaka hub prior to dispatch.' ),
                    array( 'title' => 'COD, bKash & Nagad', 'description' => 'Flexible Cash on Delivery across Bangladesh or mobile wallets.' ),
                    array( 'title' => '64-District Delivery', 'description' => '24h Dhaka Metro dispatch and insured nationwide courier.' ),
                    array( 'title' => '7-Day Easy Returns', 'description' => 'Simple replacement or refund if damaged or mismatched.' ),
                ),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => esc_html__( 'Columns', 'tamanbd-elementor' ),
                'type'           => Controls_Manager::SELECT,
                'options'        => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ),
                'default'        => '4',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .tmb-section-title' )
        );
        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Pillar Title Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1C1917',
                'selectors' => array( '{{WRAPPER}} .tmb-pillar h3' => 'color: {{VALUE}};' ),
            )
        );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s       = $this->get_settings_for_display();
        $pillars = $s['pillars'] ?? array();
        ?>
        <div>
            <div class="tmb-section-head">
                <div>
                    <?php if ( ! empty( $s['kicker'] ) ) : ?>
                        <p class="tmb-kicker"><?php echo esc_html( $s['kicker'] ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $s['heading'] ) ) : ?>
                        <h2 class="tmb-section-title"><?php echo esc_html( $s['heading'] ); ?></h2>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tmb-ew-grid">
                <?php foreach ( $pillars as $pillar ) : ?>
                    <div class="tmb-pillar">
                        <h3><?php echo esc_html( $pillar['title'] ?? '' ); ?></h3>
                        <p><?php echo esc_html( $pillar['description'] ?? '' ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
