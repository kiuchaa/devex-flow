<?php
/**
 * The template for displaying all single posts
 *
 * @package pixel-flow
 */

get_header();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header mb-4">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="post-thumbnail mb-4">
                                <?php the_post_thumbnail('full', ['class' => 'img-fluid rounded']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-meta text-muted mb-2">
                            <span class="posted-on">
                                <?php echo get_the_date(); ?>
                            </span>
                            <span class="byline">
                                <?php _e('door', 'pixel-flow'); ?> <?php the_author(); ?>
                            </span>
                        </div>
                    </header>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pagina\'s:', 'pixel-flow' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <footer class="entry-footer mt-5 pt-4 border-top">
                        <div class="cat-links">
                            <strong><?php _e('Categorieën:', 'pixel-flow'); ?></strong> <?php the_category(', '); ?>
                        </div>
                        <?php the_tags('<div class="tags-links"><strong>' . __('Tags:', 'pixel-flow') . '</strong> ', ', ', '</div>'); ?>
                    </footer>
                </article>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
