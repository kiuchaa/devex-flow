<?php
/**
 * Theme actions and hooks, responsible for flushing the wordpress og theme to speed up the website.
 */

define( 'THEME_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );

/**
 * Enqueue scripts and styles and dequeue standard WP assets
 */
add_action( 'wp_enqueue_scripts', 'pixel_flow_enqueue_assets' );
function pixel_flow_enqueue_assets() {
    // Move scripts to footer
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);

    // Dequeue standard WP bloat
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
}

/**
 * Remove standard WP head elements
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Remove blocks from the Wordpress CMS Dashboard
 */
add_action( 'admin_menu', 'remove_site_health_submenu', 999 );
function remove_site_health_submenu() {
    remove_submenu_page( 'tools.php', 'site-health.php' );
}
add_action('admin_init', 'remove_screen_options');
function remove_screen_options() {
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('wpseo-wincher-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'normal');
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Required for WordPress 3.8+
}
