<?php
/**
 * Theme setup
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

add_action('nf_display_enqueue_scripts', 'ninjaforms_deregister_styles');
function ninjaforms_deregister_styles()
{
    wp_dequeue_style('nf-font-awesome');
}


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
     * see https://wordpress.stackexchange.com/questions/283828/wp-register-script-multiple-identifiers
     * We first register the script and afther that we enqueue it, see why:
     * https://wordpress.stackexchange.com/questions/82490/when-should-i-use-wp-register-script-with-wp-enqueue-script-vs-just-wp-enque
     * https://stackoverflow.com/questions/39653993/what-is-diffrence-between-wp-enqueue-script-and-wp-register-script
     */
    wp_register_script('jquery', false, ['jquery-core', 'jquery-migrate'], null, false);
    wp_enqueue_script('jquery');

    wp_register_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('bootstrap');

    // Swiper
    wp_register_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('swiper');

    // AOS
    wp_register_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', ['jquery'], null, true);
    wp_enqueue_script('aos');

    wp_register_script('pixel-flow-app', get_template_directory_uri() . '/assets/js/app.js', array(), null );
    wp_enqueue_script('pixel-flow-app');
}

// Include styles in header
function include_header_styles()
{
    // Enqueue theme assets
    wp_register_style( 'pixel-flow-app', get_template_directory_uri() . '/style.css', array(), null );
    wp_enqueue_style( 'pixel-flow-app' );

    wp_register_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], null);
    wp_enqueue_style('bootstrap');

    wp_register_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', [], null);
    wp_enqueue_style('swiper');

    wp_register_style('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], null);
    wp_enqueue_style('aos');

    wp_register_style('fontawesome', get_template_directory_uri() . '/assets/font-awesome/css/all.min.css');
    wp_enqueue_style('fontawesome');
}



// Only queue scripts and styles on front end
if (!is_admin()) {
    // Counter intuitively, enqueue scripts is also the right hook to enqueue styles
    add_action('wp_enqueue_scripts', 'include_scripts');
    add_action('wp_enqueue_scripts', 'include_header_styles');
}

add_action('wp_dashboard_setup', 'my_custom_dashboard_widget');

// Allow SVG upload for administrators
add_filter( 'upload_mimes', function ( $mimes ) {
    if ( ! current_user_can( 'administrator' ) ) {
        return $mimes;
    }
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
} );

// Alter the CMS color scheme to "Pixel Green"
add_action('admin_init', function() {
    wp_admin_css_color(
        'pixel-cms',
        __('Pixel CMS'),
        get_template_directory_uri() . '/assets/pixel-cms.css', // Path to your CSS file
        array('#19A463', '#fff', '#159358', '#117546') // Preview icon colors
    );
});

add_filter('get_user_option_admin_color', function($color_scheme) {
    return 'pixel-cms'; // Options: fresh (default), light, blue, coffee, ectoplasm, midnight, ocean, sunrise
}, 10, 1);

// Adding a custom Dashboard widget with our custom instructions.
function my_custom_dashboard_widget() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget('custom_help_widget', 'My Custom Block', 'custom_dashboard_help');
}

function custom_dashboard_help() {
    echo '<p>Welcome to your custom dashboard block! Add any HTML or PHP here.</p>';
}


