<?php
/**
 * Elementor Widget: TamanBD Customer Testimonials / Live WooCommerce Reviews
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

class Testimonials_Widget extends Widget_Base {

    public function get_name(): string {
        return 'tmb-testimonials';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Testimonials & Reviews', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-review';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_content',
            array( 'label' => esc_html__( 'Reviews Source', 'tamanbd-elementor' ) )
        );

        $this->add_control( 'kicker', array( 'label' => esc_html__( 'Kicker', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Verified Buyers', 'tamanbd-elementor' ) ) );
        $this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Customer Reviews', 'tamanbd-elementor' ) ) );

        $this->add_control(
            'source_mode',
            array(
                'label'   => esc_html__( 'Source', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'wc_reviews',
                'options' => array(
                    'wc_reviews' => esc_html__( 'Live WooCommerce Product Reviews', 'tamanbd-elementor' ),
                    'custom'     => esc_html__( 'Custom Editable Testimonials', 'tamanbd-elementor' ),
                ),
            )
        );

        $this->add_control(
            'limit',
            array(
                'label'     => esc_html__( 'Number of Reviews', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3,
                'min'       => 1,
                'max'       => 9,
                'condition' => array( 'source_mode' => 'wc_reviews' ),
            )
        );

        $repeater = new Repeater();
        $repeater->add_control( 'author', array( 'label' => esc_html__( 'Customer Name', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => 'Sadia Rahman' ) );
        $repeater->add_control( 'subtitle', array( 'label' => esc_html__( 'Location / Item', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => 'Gulshan 2, Dhaka' ) );
        $repeater->add_control( 'rating', array( 'label' => esc_html__( 'Stars (1-5)', 'tamanbd-elementor' ), 'type' => Controls_Manager::NUMBER, 'default' => 5, 'min' => 1, 'max' => 5 ) );
        $repeater->add_control( 'quote', array( 'label' => esc_html__( 'Review Quote', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXTAREA, 'default' => 'Fast Dhaka delivery and authentic craftsmanship.' ) );

        $this->add_control(
            'custom_reviews',
            array(
                'label'       => esc_html__( 'Testimonials', 'tamanbd-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'condition'   => array( 'source_mode' => 'custom' ),
                'title_field' => '{{{ author }}}',
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => esc_html__( 'Columns', 'tamanbd-elementor' ),
                'type'           => Controls_Manager::SELECT,
                'options'        => array( '1' => '1', '2' => '2', '3' => '3' ),
                'default'        => '3',
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
                'selectors' => array( '{{WRAPPER}} .tmb-review-card' => 'background-color: {{VALUE}};' ),
            )
        );
        $this->add_responsive_control(
            'card_radius',
            array(
                'label'     => esc_html__( 'Border Radius', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
                'default'   => array( 'size' => 12, 'unit' => 'px' ),
                'selectors' => array( '{{WRAPPER}} .tmb-review-card' => 'border-radius: {{SIZE}}{{UNIT}};' ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array( 'name' => 'quote_typography', 'selector' => '{{WRAPPER}} .tmb-review-quote' )
        );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s     = $this->get_settings_for_display();
        $items = array();

        if ( 'custom' === ( $s['source_mode'] ?? 'wc_reviews' ) ) {
            foreach ( ( $s['custom_reviews'] ?? array() ) as $row ) {
                $items[] = array(
                    'author'   => (string) ( $row['author'] ?? '' ),
                    'subtitle' => (string) ( $row['subtitle'] ?? '' ),
                    'rating'   => (int) ( $row['rating'] ?? 5 ),
                    'quote'    => (string) ( $row['quote'] ?? '' ),
                );
            }
        } else {
            $comments = get_comments(
                array(
                    'number'      => absint( $s['limit'] ?? 3 ),
                    'status'      => 'approve',
                    'post_status' => 'publish',
                    'post_type'   => 'product',
                    'type'        => 'review',
                )
            );
            foreach ( $comments as $c ) {
                $items[] = array(
                    'author'   => (string) $c->comment_author,
                    'subtitle' => (string) get_the_title( (int) $c->comment_post_ID ),
                    'rating'   => (int) get_comment_meta( (int) $c->comment_ID, 'rating', true ),
                    'quote'    => wp_trim_words( (string) $c->comment_content, 28 ),
                );
            }
        }
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

            <?php if ( empty( $items ) ) : ?>
                <p><?php esc_html_e( 'Approved WooCommerce product reviews will appear here automatically.', 'tamanbd-elementor' ); ?></p>
            <?php else : ?>
                <div class="tmb-ew-grid">
                    <?php foreach ( $items as $item ) : ?>
                        <article class="tmb-review-card">
                            <div class="tmb-review-stars"><?php echo esc_html( str_repeat( '★', max( 1, min( 5, $item['rating'] ) ) ) ); ?></div>
                            <p class="tmb-review-quote">&ldquo;<?php echo esc_html( $item['quote'] ); ?>&rdquo;</p>
                            <div class="tmb-review-meta">
                                <strong><?php echo esc_html( $item['author'] ); ?></strong>
                                <span><?php echo esc_html( $item['subtitle'] ); ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
