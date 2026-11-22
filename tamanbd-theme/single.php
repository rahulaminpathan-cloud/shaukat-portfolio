<?php
/**
 * Single Blog Post Template
 *
 * @package TamanBD
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="tmb-main-content" class="tmb-section" role="main">
    <div class="tmb-container tmb-container--narrow">
        <?php tamanbd_render_breadcrumbs(); ?>

        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'tmb-single-post' ); ?>>
                <header class="tmb-page-header">
                    <div class="tmb-post-meta">
                        <span><?php echo esc_html( get_the_date() ); ?></span>
                        <span aria-hidden="true">·</span>
                        <span><?php echo esc_html( get_the_author() ); ?></span>
                    </div>
                    <h1 class="tmb-page-title"><?php the_title(); ?></h1>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="tmb-single-thumb">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="tmb-entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
