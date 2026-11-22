<?php
/**
 * Elementor Widget: TamanBD Dynamic WooCommerce Products Grid
 *
 * Supports Featured, Best-Selling, Latest, On-Sale/Discount, By Category, or Manual Selection.
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Products_Grid_Widget extends Widget_Base {
    use TamanBD_Widget_Helpers;

    public function get_name(): string {
        return 'tmb-products-grid';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD WooCommerce Products', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-products';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_query',
            array( 'label' => esc_html__( 'Product Query & Layout', 'tamanbd-elementor' ) )
        );

        $this->add_control(
            'kicker',
            array(
                'label'   => esc_html__( 'Section Kicker', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Live Catalog', 'tamanbd-elementor' ),
            )
        );

        $this->add_control(
            'heading',
            array(
                'label'   => esc_html__( 'Section Heading', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Best Selling Products', 'tamanbd-elementor' ),
            )
        );

        $this->add_control(
            'source',
            array(
                'label'   => esc_html__( 'Product Source', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'best_selling',
                'options' => array(
                    'best_selling' => esc_html__( 'Best-Selling Products', 'tamanbd-elementor' ),
                    'featured'     => esc_html__( 'Featured Products', 'tamanbd-elementor' ),
                    'latest'       => esc_html__( 'Latest Products', 'tamanbd-elementor' ),
                    'sale'         => esc_html__( 'Special Discount / On-Sale', 'tamanbd-elementor' ),
                    'category'     => esc_html__( 'By Selected Categories', 'tamanbd-elementor' ),
                    'manual'       => esc_html__( 'Manual Product Selection', 'tamanbd-elementor' ),
                ),
            )
        );

        $this->add_control(
            'categories',
            array(
                'label'       => esc_html__( 'Product Categories', 'tamanbd-elementor' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_product_categories_options(),
                'label_block' => true,
                'condition'   => array( 'source' => 'category' ),
            )
        );

        $this->add_control(
            'manual_ids',
            array(
                'label'       => esc_html__( 'Select Specific Products', 'tamanbd-elementor' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_wc_products_options(),
                'label_block' => true,
                'condition'   => array( 'source' => 'manual' ),
            )
        );

        $this->add_control(
            'limit',
            array(
                'label'   => esc_html__( 'Number of Products', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 24,
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

        $this->add_control(
            'button_text',
            array(
                'label'   => esc_html__( 'Section Link Label', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'View All →', 'tamanbd-elementor' ),
            )
        );

        $this->add_control(
            'button_link',
            array(
                'label'   => esc_html__( 'Section Link URL', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::URL,
                'default' => array( 'url' => '/shop/' ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array( 'label' => esc_html__( 'Card & Section Style', 'tamanbd-elementor' ), 'tab' => Controls_Manager::TAB_STYLE )
        );

        $this->add_control(
            'card_bg',
            array(
                'label'     => esc_html__( 'Card Background', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => array( '{{WRAPPER}} .tmb-card' => 'background-color: {{VALUE}};' ),
            )
        );

        $this->add_control(
            'accent_color',
            array(
                'label'     => esc_html__( 'Accent / Button Hover Color', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0F4C3A',
                'selectors' => array(
                    '{{WRAPPER}} .tmb-kicker, {{WRAPPER}} .tmb-card-tag, {{WRAPPER}} .tmb-text-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tmb-card-add:hover' => 'background-color: {{VALUE}}; color: #FFFFFF;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array( 'name' => 'heading_typography', 'label' => esc_html__( 'Section Heading Typography', 'tamanbd-elementor' ), 'selector' => '{{WRAPPER}} .tmb-section-title' )
        );

        $this->add_responsive_control(
            'card_radius',
            array(
                'label'     => esc_html__( 'Card Border Radius', 'tamanbd-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
                'default'   => array( 'size' => 12, 'unit' => 'px' ),
                'selectors' => array( '{{WRAPPER}} .tmb-card' => 'border-radius: {{SIZE}}{{UNIT}};' ),
            )
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        if ( ! function_exists( 'wc_get_products' ) ) {
            echo '<p>' . esc_html__( 'WooCommerce must be active to render products.', 'tamanbd-elementor' ) . '</p>';
            return;
        }

        $s      = $this->get_settings_for_display();
        $source = $s['source'] ?? 'best_selling';
        $limit  = max( 1, absint( $s['limit'] ?? 4 ) );
        $args   = array(
            'status' => 'publish',
            'limit'  => $limit,
            'return' => 'objects',
        );

        switch ( $source ) {
            case 'best_selling':
                $args['orderby']  = 'meta_value_num';
                $args['meta_key'] = 'total_sales';
                $args['order']    = 'DESC';
                break;
            case 'featured':
                $args['featured'] = true;
                break;
            case 'sale':
                $sale_ids = wc_get_product_ids_on_sale();
                if ( empty( $sale_ids ) ) {
                    echo '<p>' . esc_html__( 'No discounted products currently active.', 'tamanbd-elementor' ) . '</p>';
                    return;
                }
                $args['include'] = array_slice( array_map( 'intval', $sale_ids ), 0, $limit );
                break;
            case 'category':
                if ( ! empty( $s['categories'] ) && is_array( $s['categories'] ) ) {
                    $args['category'] = array_map( 'sanitize_title', $s['categories'] );
                }
                break;
            case 'manual':
                if ( ! empty( $s['manual_ids'] ) && is_array( $s['manual_ids'] ) ) {
                    $args['include'] = array_map( 'absint', $s['manual_ids'] );
                    $args['orderby'] = 'post__in';
                }
                break;
            case 'latest':
            default:
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
                break;
        }

        $products = wc_get_products( $args );
        ?>
        <div class="tmb-ew-products">
            <div class="tmb-section-head">
                <div>
                    <?php if ( ! empty( $s['kicker'] ) ) : ?>
                        <p class="tmb-kicker"><?php echo esc_html( $s['kicker'] ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $s['heading'] ) ) : ?>
                        <h2 class="tmb-section-title"><?php echo esc_html( $s['heading'] ); ?></h2>
                    <?php endif; ?>
                </div>
                <?php if ( ! empty( $s['button_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $s['button_link']['url'] ?? '/shop/' ); ?>" class="tmb-text-link">
                        <?php echo esc_html( $s['button_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ( empty( $products ) ) : ?>
                <p class="tmb-empty-notice"><?php esc_html_e( 'No matching WooCommerce products found.', 'tamanbd-elementor' ); ?></p>
            <?php else : ?>
                <div class="tmb-ew-grid">
                    <?php
                    foreach ( $products as $product ) {
                        if ( is_a( $product, 'WC_Product' ) && function_exists( 'tamanbd_render_product_card' ) ) {
                            tamanbd_render_product_card( $product );
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
