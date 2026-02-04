<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package pixel-flow
 */

get_header();
?>

<div class="container py-5">
    <div class="row gx-0 no-gutters">
        <div class="col-12">
            <?php
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    if (is_page()) {
                        the_content();
                    } else {
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('mb-4'); ?>>
                            <header class="entry-header">
                                <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                            </header>
                            <div class="entry-content">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                        <?php
                    }

                endwhile;

                the_posts_navigation();

            else :

                ?>
                <section class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e( 'Niets gevonden', 'pixel-flow' ); ?></h1>
                    </header>
                    <div class="page-content">
                        <p><?php esc_html_e( 'Het lijkt erop dat we niet konden vinden wat je zocht. Misschien helpt de zoekfunctie?', 'pixel-flow' ); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                </section>
                <?php

            endif;
            ?>
        </div>
    </div>
</div>

<?php
get_footer();