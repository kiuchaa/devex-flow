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

/**
 * Remove the default 'Berichten' (Posts) menu from the dashboard.
 */
add_action( 'admin_menu', function() {
    // 'edit.php' is the slug for the default WordPress Posts
    remove_menu_page( 'edit.php' );
});

/**
 * Remove the pre-defined blocks that are loaded in by Wordpress.
 */

add_filter( 'allowed_block_types_all', 'mijn_strenge_blokken_filter', 100, 2 );

function mijn_strenge_blokken_filter( $allowed_blocks, $editor_context ) {
    $registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $allowed = array();

    foreach ( $registered_blocks as $block_name => $block ) {

        // 1. Laat al jouw custom ACF blokken ALTIJD door
        if ( strpos( $block_name, 'acf/' ) === 0 ) {
            $allowed[] = $block_name;
            continue;
        }

        // 2. Blokkeer Yoast en alle Insluitingen (Embeds)
        // Check voor 'yoast' in de naam of 'core-embed' namespace
        if ( strpos( $block_name, 'yoast' ) !== false || strpos( $block_name, 'core-embed/' ) === 0 ) {
            continue; // Sla deze over, ze mogen er niet in
        }

        // 3. Blokkeer specifieke categorieën (Widgets, Thema, en 'Niet gecategoriseerd')
        $category = isset( $block->category ) ? $block->category : '';

        // Als categorie leeg is (uncategorized) OF in de blacklist staat
        if ( empty( $category ) || in_array( $category, array( 'widgets', 'theme', 'embed', 'uncategorized' ) ) ) {
            continue; // Sla over
        }

        // 4. Blokkeer specifieke WordPress core widgets die soms stiekem doorkomen
        $verborgen_core_blokken = array(
            'core/legacy-widget',
            'core/widget-group',
            'core/shortcode',
            'core/latest-posts',
            'core/latest-comments',
            'core/calendar',
            'core/rss',
            'core/search',
            'core/social-links',
            'core/tag-cloud',
            'core/page-list',
            'core/query', // Query Loop kan ook verwarrend zijn als je strikt bent
            'core/site-logo',
            'core/site-title',
            'core/site-tagline'
        );
        if ( in_array( $block_name, $verborgen_core_blokken ) ) {
            continue; // Sla over
        }

        // Als het blok door al deze beveiligingen heen komt (bijv. een standaard 'Paragraaf' of 'Afbeelding'), sta het dan toe
        $allowed[] = $block_name;
    }

    return $allowed;
}