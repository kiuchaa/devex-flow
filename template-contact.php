<?php
/*
    Template Name: Contact
*/

get_header();

while ( have_posts() ) :
    the_post();

    // Hero section logic
    $thumbnail_id = get_post_thumbnail_id();
    $image_url    = $thumbnail_id ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
    ?>

    <header class="contact-header">
        <?php if ( $image_url ) : ?>
            <img src="<?php echo esc_url( $image_url ); ?>" class="contact-header-image" alt="<?php the_title_attribute(); ?>">
            <div class="contact-header-overlay"></div>
        <?php endif; ?>
        
        <div class="container text-center">
            <h1 class="display-1 fw-bold text-white mb-0" data-aos="fade-up" data-aos-duration="1000">
                <?php the_title(); ?>
            </h1>
        </div>
    </header>

    <div class="contact-blocks">
        <?php the_content(); ?>
    </div>

    <?php
endwhile;

get_footer();

