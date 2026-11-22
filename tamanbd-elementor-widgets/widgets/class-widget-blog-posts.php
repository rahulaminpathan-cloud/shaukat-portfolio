<?php
/**
 * Elementor Widget: TamanBD Latest Blog Posts
 */

declare(strict_types=1);

namespace TamanBD\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Blog_Posts_Widget extends Widget_Base {
    use TamanBD_Widget_Helpers;

    public function get_name(): string {
        return 'tmb-blog-posts';
    }

    public function get_title(): string {
        return esc_html__( 'TamanBD Blog Posts', 'tamanbd-elementor' );
    }

    public function get_icon(): string {
        return 'eicon-posts-grid';
    }

    public function get_categories(): array {
        return array( 'tamanbd-storefront' );
    }

    protected function register_controls(): void {
        $this->start_controls_section(
            'section_query',
            array( 'label' => esc_html__( 'Blog Query', 'tamanbd-elementor' ) )
        );

        $this->add_control( 'kicker', array( 'label' => esc_html__( 'Kicker', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Editorial Journal', 'tamanbd-elementor' ) ) );
        $this->add_control( 'heading', array( 'label' => esc_html__( 'Heading', 'tamanbd-elementor' ), 'type' => Controls_Manager::TEXT, 'default' => esc_html__( 'Stories & Buying Guides', 'tamanbd-elementor' ) ) );

        $this->add_control(
            'category_id',
            array(
                'label'   => esc_html__( 'Filter by Category', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_blog_categories_options(),
            )
        );

        $this->add_control(
            'posts_count',
            array(
                'label'   => esc_html__( 'Number of Posts', 'tamanbd-elementor' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 12,
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
            array( 'label' => esc_html__( 'Typography', 'tamanbd-elementor' ), 'tab' => Controls_Manager::TAB_STYLE )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array( 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .tmb-post-title' )
        );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s    = $this->get_settings_for_display();
        $args = array(
            'post_type'           => 'post',
            'posts_per_page'      => absint( $s['posts_count'] ?? 3 ),
            'post_status'         => 'publish',
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        );
        if ( ! empty( $s['category_id'] ) ) {
            $args['cat'] = absint( $s['category_id'] );
        }
        $q = new WP_Query( $args );
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

            <?php if ( $q->have_posts() ) : ?>
                <div class="tmb-ew-grid">
                    <?php
                    while ( $q->have_posts() ) :
                        $q->the_post();
                        ?>
                        <article class="tmb-post-card">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="tmb-post-media">
                                    <?php the_post_thumbnail( 'tamanbd-card', array( 'loading' => 'lazy' ) ); ?>
                                </a>
                            <?php endif; ?>
                            <div class="tmb-post-meta"><span><?php echo esc_html( get_the_date() ); ?></span></div>
                            <h3 class="tmb-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="tmb-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'Published blog posts will appear here.', 'tamanbd-elementor' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
