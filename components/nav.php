<?php
/**
 * Template part for the navigation
 *
 * @package pixel-flow
 */

$logo_id = get_field('logo', 'options');
$primary_menu = has_nav_menu('navigatie_menu');
?>

<nav class="navbar navbar-expand-lg fixed-top main-navigation">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Read more about Seminole tax hike">
            <?php if ($logo_id) : ?>
                <?php echo wp_get_attachment_image($logo_id, 'medium_large', false, ['class' => 'd-inline-block align-top me-2']); ?>
            <?php else : ?>
                <div class="logo-placeholder me-2">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="16" cy="16" r="16" fill="black"/>
                        <path d="M16 8V24M8 16H24" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
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
                    'menu_class'     => 'navbar-nav mx-auto align-items-center',
                    'fallback_cb'    => '__return_false',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'          => 3,
                    'walker'         => new bootstrap_5_wp_nav_menu_walker(),
                ]);
            }
            ?>
            
            <div class="nav-cta d-flex flex-wrap align-items-center mt-3 mt-lg-0">
                <?php if (get_option('thema-instellingen_webshop_modus') == 'shop' && class_exists( 'WooCommerce' )) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="nav-icon-link me-3" aria-label="Shop">
                        <i class="fa-solid fa-shop"></i>
                    </a>
                    <a href="<?php echo esc_url(wc_get_page_permalink('cart')); ?>" class="nav-icon-link me-3" aria-label="Cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="nav-icon-link me-3" aria-label="Mijn account">
                        <i class="fa-solid fa-user"></i>
                    </a>
                <?php endif; ?>

                <?php 
                get_template_part('components/button', null, [
                    'link' => [
                        'url' => '/contact',
                        'title' => 'Contact',
                        'target' => '_self'
                    ],
                    'variant' => 'primary',
                    'class' => 'px-4 py-2 fw-medium'
                ]);
                ?>
            </div>
        </div>
    </div>
</nav>
<div class="navbar-overlay"></div>
