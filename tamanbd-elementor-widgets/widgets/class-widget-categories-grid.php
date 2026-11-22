<?php
/**
 * Elementor Widget: TamanBD Dynamic WooCommerce Categories Grid
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Categories_Grid_Widget extends Widget_Base {
    use TamanBD_Widget_Helpers;

    public function get_name(): string {
        return 'tmb-categories-grid';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Categories Grid', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-product-categories';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_query',
            array( 'label' => esc_html__( 'Category Query', 'tamanbd-elementor' ) )
        );

        $this->add_control(
            'kicker',
            array(
                'label'   => esc_html__( 'Section Kicker', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Browse Departments', 'tamanbd-elementor' ),
            )
        );

        $this->add_control(
            'heading',
            array(
                'label'   => esc_html__( 'Section Heading', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Featured Categories', 'tamanbd-elementor' ),
            )
        );

        $this->add_control(
            'selected_categories',
            array(
                'label'       => esc_html__( 'Select Categories (leave empty for top categories)', 'tamanbd-elementor' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_product_categories_options(),
                'label_block' => true,
            )
        );

        $this->add_control(
            'limit',
            array(
                'label'   => esc_html__( 'Number of Categories', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 12,
            )
        );

        $this->add_control(
            'show_count',
            array(
                'label'   => esc_html__( 'Show Item Count', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
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

        $this->add_responsive_control(
            'card_radius',
            array(
                'label'     => esc_html__( 'Card Border Radius', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
                'default'   => array( 'size' => 12, 'unit' => 'px' ),
                'selectors' => array( '{{WRAPPER}} .tmb-cat-card' => 'border-radius: {{SIZE}}{{UNIT}};' ),
            )
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        if ( ! taxonomy_exists( 'product_cat' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce is required to display product categories.', 'tamanbd-elementor' ) . '</p>';
            return;
        }

        $s     = $this->get_settings_for_display();
        $slugs = $s['selected_categories'] ?? array();
        $args  = array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'number'     => absint( $s['limit'] ?? 4 ),
        );

        if ( ! empty( $slugs ) && is_array( $slugs ) ) {
            $args['slug'] = array_map( 'sanitize_title', $slugs );
        } else {
            $args['parent']  = 0;
            $args['exclude'] = array( (int) get_option( 'default_product_cat' ) );
        }

        $terms = get_terms( $args );
        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return;
        }
        ?>
        <div class="tmb-ew-cats">
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
                <?php
                foreach ( $terms as $cat ) :
                    $link      = get_term_link( $cat );
                    $thumb_id  = (int) get_term_meta( $cat->term_id, 'thumbnail_id', true );
                    $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'tamanbd-card' ) : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'tamanbd-card' ) : '' );
                    if ( is_wp_error( $link ) ) {
                        continue;
                    }
                    ?>
                    <a href="<?php echo esc_url( $link ); ?>" class="tmb-cat-card">
                        <div class="tmb-cat-media">
                            <?php if ( $thumb_url ) : ?>
                                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div class="tmb-cat-body">
                            <div class="tmb-cat-row">
                                <h3 class="tmb-cat-title"><?php echo esc_html( $cat->name ); ?></h3>
                                <?php if ( 'yes' === ( $s['show_count'] ?? '' ) ) : ?>
                                    <span class="tmb-cat-count"><?php printf( esc_html__( '%d items', 'tamanbd-elementor' ), (int) $cat->count ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
