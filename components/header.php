<?php
/**
 * Header Hero Component
 *
 * @package pixel-flow
 */

$title = "Dit is een test";
$subtitle = "Dit is een hero";
$button_text = "Lees meer";
$button_url = "#";
$section_class = "header-hero bg-primary text-white py-5";
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container py-5 text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-3 fw-bold mb-3"><?php echo esc_html($title); ?></h1>
                <p class="lead mb-4"><?php echo esc_html($subtitle); ?></p>
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-light btn-lg px-5 fw-bold">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
        </div>
    </div>
</section>
