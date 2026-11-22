<?php
/**
 * Standard Page Template (Supports Elementor Pages, WooCommerce Cart, Checkout, My Account)
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$is_elementor_page = false;
if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) ) {
    $document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
    if ( $document && $document->is_built_with_elementor() ) {
        $is_elementor_page = true;
    }
}
?>

<main id="tmb-main-content" class="<?php echo $is_elementor_page ? 'tmb-elementor-main' : 'tmb-section'; ?>" role="main">
    <?php
    while ( have_posts() ) :
        the_post();
        if ( $is_elementor_page ) :
            the_content();
        else :
            ?>
            <div class="tmb-container">
                <?php tamanbd_render_breadcrumbs(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'tmb-page-content' ); ?>>
                    <header class="tmb-page-header">
                        <h1 class="tmb-page-title"><?php the_title(); ?></h1>
                    </header>
                    <div class="tmb-entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            </div>
            <?php
        endif;
    endwhile;
    ?>
</main>

<?php
get_footer();
