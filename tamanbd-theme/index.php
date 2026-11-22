<?php
/**
 * Main Index Fallback Template
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
    <div class="tmb-container">
        <?php tamanbd_render_breadcrumbs(); ?>

        <?php if ( have_posts() ) : ?>
            <div class="tmb-grid tmb-grid--3">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'tmb-post-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="tmb-post-media">
                                <?php the_post_thumbnail( 'tamanbd-card', array( 'loading' => 'lazy' ) ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="tmb-post-meta">
                            <span><?php echo esc_html( get_the_date() ); ?></span>
                        </div>
                        <h2 class="tmb-post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="tmb-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="tmb-pagination">
                <?php the_posts_pagination(); ?>
            </div>
        <?php else : ?>
            <p><?php esc_html_e( 'No content found.', 'tamanbd' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
