<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="site" content="website door Pixel Creation">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
// Navigation
get_template_part('components/nav');
?>
    <main id="primary" class="site-main">
