<?php
/**
 * The template for displaying the blog index (home page for posts)
 *
 * @package pixel-flow
 */

get_header();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4"><?php single_post_title(); ?></h1>

            <?php if ( have_posts() ) : ?>
                <div class="row">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="col-md-6 mb-4">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
                                <?php if (has_post_thumbnail()): ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h2 class="card-title h5">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    <div class="card-text text-muted mb-2">
                                        <small><?php echo get_the_date(); ?></small>
                                    </div>
                                    <div class="card-text">
                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                        <?php _e('Lees meer', 'pixel-flow'); ?>
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="pagination-wrapper mt-4">
                    <?php the_posts_pagination([
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'class'     => 'pagination justify-content-center'
                    ]); ?>
                </div>

            <?php else : ?>
                <p><?php _e('Geen berichten gevonden.', 'pixel-flow'); ?></p>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
get_footer();
