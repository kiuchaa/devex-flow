<?php
// Remove posts from admin menu
//function post_remove () { remove_menu_page('edit.php');}
//add_action('admin_menu', 'post_remove');

// Creating the Call to Action post type
function call_to_action_post_type() {

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => __('Call to actions'),
        'singular_name'       => __('Call to action'),
        'menu_name'           => __('Call to actions'),
        'parent_item_colon'   => __(''),
        'all_items'           => __('Alle call to actions'),
        'view_item'           => __('Bekijk call to action'),
        'add_new_item'        => __('Call to action toevoegen'),
        'add_new'             => __('Nieuwe call to action'),
        'edit_item'           => __('Bewerk call to action'),
        'update_item'         => __('Update call to action'),
        'search_items'        => __('Zoek call to action'),
        'not_found'           => __('Geen call to action gevonden'),
        'not_found_in_trash'  => __('Geen call to action in prullenbak gevonden'),
    );

    // Set other options for Custom Post Type

    $args = array(
        'label'               => __('Call to actions'),
        'description'         => __('Hier worden call to actions voor je website beheerd'),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', 'editor' ),
        'taxonomies'          => [],
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'menu_position'       => 22,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'page',
        'menu_icon'           => 'dashicons-megaphone'
    );

    // Registering your Custom Post Type
    register_post_type( 'call-to-actions', $args );
}
add_action( 'init', 'call_to_action_post_type', 0 );

// Creating the Medewerkers post type
function medewerkers_post_type() {

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                  => __('Medewerkers'),
        'singular_name'         => __('Medewerker'),
        'menu_name'             => __('Medewerkers'),
        'parent_item_colon'     => __(''),
        'all_items'             => __('Alle medewerkers'),
        'view_item'             => __('Bekijk medewerker'),
        'add_new_item'          => __('Medewerker toevoegen'),
        'add_new'               => __('Nieuwe medewerker'),
        'edit_item'             => __('Bewerk medewerker'),
        'update_item'           => __('Update medewerker'),
        'search_items'          => __('Zoek medewerker'),
        'not_found'             => __('Geen medewerker gevonden'),
        'not_found_in_trash'    => __('Geen medewerker in prullenbak gevonden'),
        'insert_into_item'      => __('Toevoegen aan medewerker'),
        'uploaded_to_this_item' => __('Geupload naar deze medewerker'),
        'featured_image'        => __('Foto van medewerker'),
        'set_featured_image'    => __('Foto instellen'),
        'remove_featured_image' => __('Foto verwijderen'),
        'use_featured_image'    => __('Gebruiker als foto van medewerker'),
    );

    // Set other options for Custom Post Type
    $args = array(
        'label'               => __('Medewerkers'),
        'description'         => __('Hier worden de medewerkers getoond'),
        'labels'              => $labels,
        'supports'            => array( 'title', 'custom-fields', 'thumbnail'),
        'taxonomies'          => [],
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 23,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'menu_icon'           => 'dashicons-businessman'
    );

    // Registering your Custom Post Type
    register_post_type( 'medewerkers', $args );
}
add_action( 'init', 'medewerkers_post_type', 0 );