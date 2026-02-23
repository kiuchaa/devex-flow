<?php
/*
    Template Name: Contact
*/

get_header();
?>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="mb-4"><?php single_post_title(); ?></h1>
            </div>

            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>

<?php
get_footer();
