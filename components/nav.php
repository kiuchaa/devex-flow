<?php
/**
 * Template part for the navigation
 *
 * @package pixel-flow
 */

$logo_id = get_field('logo', 'options');
$primary_menu = has_nav_menu('navigatie_menu');
?>

<nav class="navbar navbar-expand-lg sticky-top main-navigation">
    <div class="container">
        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php if ($logo_id) : ?>
                <?php echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'd-inline-block align-top']); ?>
            <?php else : ?>
                <span class="fs-4"><?php bloginfo('name'); ?></span>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
            if (has_nav_menu('navigatie_menu')) {
                wp_nav_menu([
                    'theme_location' => 'navigatie_menu',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav ms-auto align-items-center',
                    'fallback_cb'    => '__return_false',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'          => 2,
                    // Note: walker needed for full Bootstrap dropdown support, currently using default or generic
                ]);
            }
            ?>
        </div>
    </div>
</nav>
