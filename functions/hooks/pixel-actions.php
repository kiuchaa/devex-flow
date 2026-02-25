<?php
/*
 * Our config file for our theme (like our functions.php)
 * This is the main configuration file that defines our Pixel Flow theme.
 */

/**
 * Pre-define menu locations in the theme
 */
add_action( 'after_setup_theme', 'pixel_flow_setup' );
function pixel_flow_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
            'navigatie_menu' => esc_html__( 'Navigatie Menu', 'pixel-flow' ),
            'footer_menu' => esc_html__( 'Footer Menu', 'pixel-flow' ),
            'footer_bar' => esc_html__( 'Footer Bar', 'pixel-flow' ),
		)
	);
}

/**
 * Auto create menus on theme activation
 */
add_action( 'after_switch_theme', 'pf_create_default_menus' );
function pf_create_default_menus() {
    $menus = [
        'navigatie_menu' => 'Hoofdmenu',
        'footer_menu'    => 'Footermenu'
    ];

    $locations = get_theme_mod('nav_menu_locations');

    foreach ($menus as $location => $name) {
        // Check if the menu already exists
        $menu_exists = wp_get_nav_menu_object($name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($name);
            
            // Assign to location
            $locations[$location] = $menu_id;
        } else {
            // Even if it exists, ensure it is assigned to the location if the location is empty
            if (empty($locations[$location])) {
                $locations[$location] = $menu_exists->term_id;
            }
        }
    }

    set_theme_mod('nav_menu_locations', $locations);
}

/**
 * Removes the Ninja Form default styling
 */
add_action('nf_display_enqueue_scripts', 'ninjaforms_deregister_styles');
function ninjaforms_deregister_styles()
{
    wp_dequeue_style('nf-font-awesome');
}

/**
 * Registering our scripts
 */
/**
 * Registering our scripts
 */
function include_scripts()
{
    wp_deregister_script('jquery');
    wp_deregister_script('jquery-core');
    wp_deregister_script('jquery-migrate');

    // Register jQuery in the head
    wp_register_script('jquery-core', 'https://code.jquery.com/jquery-3.5.1.min.js', [], null, false);
    wp_register_script('jquery-migrate', 'https://code.jquery.com/jquery-migrate-3.3.2.min.js', [], null, false);

    /**
     * Register jquery using jquery-core as a dependency, so other scripts could use the jquery handle
     */
    wp_register_script('jquery', false, ['jquery-core', 'jquery-migrate'], null, false);
    wp_enqueue_script('jquery');

    wp_register_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('bootstrap');

    // Swiper
    wp_register_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('swiper');

    // AOS
    wp_register_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', ['jquery'], null, true);
    wp_enqueue_script('aos');

    // Theme JS with versioning
    $js_path = '/assets/js/app.js';
    $js_full_path = get_stylesheet_directory() . $js_path;
    $js_ver = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? time() : (file_exists($js_full_path) ? filemtime($js_full_path) : '1.0.0');
    
    wp_register_script('pixel-flow-app', get_stylesheet_directory_uri() . $js_path, array('jquery'), $js_ver, true);
    wp_enqueue_script('pixel-flow-app');
}

/**
 * Registering our stylesheets
 */
function include_header_styles()
{
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap', [], null);

    // Main Theme Stylesheet with versioning
    $css_path = '/style.css';
    $css_full_path = get_stylesheet_directory() . $css_path;
    $css_ver = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? time() : (file_exists($css_full_path) ? filemtime($css_full_path) : '1.0.0');
    
    wp_register_style( 'pixel-flow-app', get_stylesheet_directory_uri() . $css_path, array(), $css_ver );
    wp_enqueue_style( 'pixel-flow-app' );

    // External Libraries
    wp_register_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null);
    wp_enqueue_style('swiper');

    wp_register_style('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], null);
    wp_enqueue_style('aos');

    // FontAwesome with versioning
    $fa_path = '/assets/font-awesome/css/all.min.css';
    $fa_full_path = get_stylesheet_directory() . $fa_path;
    $fa_ver = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? time() : (file_exists($fa_full_path) ? filemtime($fa_full_path) : '6.0.0');
    wp_register_style('fontawesome', get_stylesheet_directory_uri() . $fa_path, [], $fa_ver);
    wp_enqueue_style('fontawesome');
}

/**
 * Only enqueue our scripts and stylesheet on the front-end, leaving the CMS as it is.
 */
if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'include_scripts');
    add_action('wp_enqueue_scripts', 'include_header_styles');
}

/**
 * Enqueue assets for the Block Editor
 */
function include_editor_assets() {
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true);
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null);
}
add_action('enqueue_block_editor_assets', 'include_editor_assets');

/**
 * Allow admins to upload SVGs without an external plug-in
 */
add_filter( 'upload_mimes', function ( $mimes ) {
    if ( ! current_user_can( 'administrator' ) ) {
        return $mimes;
    }
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
} );

/**
 * Alter the CMS color to Pixel Green - looks at the stylesheet in /assets/misc/
 */
add_action('admin_init', function() {
    wp_admin_css_color(
        'pixel-cms',
        __('Pixel CMS'),
        get_template_directory_uri() . '/assets/misc/pixel-cms.css',
        array( '#002F34', '#555', '#777', '#999' )
    );
});

add_filter('get_user_option_admin_color', function($color_scheme) {
    return 'pixel-cms';
}, 10, 1);

/**
 * Our custom Dashboard widget
 */
function my_custom_dashboard_widget() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget('custom_help_widget', 'My Custom Block', 'custom_dashboard_help');
}
add_action('wp_dashboard_setup', 'my_custom_dashboard_widget');

function custom_dashboard_help() {
    echo '<p>Welcome to your custom dashboard block! Add any HTML or PHP here.</p>';
}

/**
 * Swaps out the Wordpress logo top left to our Pixel P
 */
function pixel_flow_custom_admin_logo() {
    echo '
    <style type="text/css">
        #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
            background-image: url(' . get_template_directory_uri() . '/assets/img/pixel-p-white.svg) !important;
            background-position: center !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
            color: transparent !important;
            content: "" !important;
            display: block !important;
            width: 20px !important;
            height: 20px !important;
        }
        #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon {
            width: 20px !important;
            height: 20px !important;
            margin-right: 0 !important;
        }
    </style>
    ';
}
add_action('admin_head', 'pixel_flow_custom_admin_logo');
add_action('wp_head', 'pixel_flow_custom_admin_logo');


add_action('admin_head', 'admin_styles');
function admin_styles()
{
    echo '<style>.block-editor-block-list__block:not(.wp-block-heading):not(.wp-block-buttons):not(.wp-block-button):not(.wp-block-paragraph):not(.wp-block-list)::before{content:attr(data-title);position:relative;display:block;font-size:20px;font-weight:700;max-width:1050px;margin:0 auto 16px;line-height:1;}.block-editor-block-list__block .acf-block-component{max-width:1050px;margin:0 auto;}</style>';
}

/**
 * Late-init Theme Support for WooCommerce based on ACF Option
 */
add_action( 'after_setup_theme', function() {
    // Check the 'thema-instellingen' post_id for the webshop_modus button group
    $webshop_modus = get_option('thema-instellingen_webshop_modus');

    if ( $webshop_modus === 'shop' ) {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}, 20 ); // Priority 20 ensures it runs after most theme setup
