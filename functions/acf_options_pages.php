<?php
// Add options page from Advanced Custom Fields
if( function_exists('acf_add_options_page') ) {
    $parent = acf_add_options_page([
        'page_title' => __('Extra instellingen'),
        'menu_title' => __('Extra instellingen'),
        'menu_slug' => __('extra-instellingen'),
        'icon_url' => 'dashicons-admin-generic',
        'position' => '80.01',
        'update_button' => __('Opslaan', 'acf'),
        'updated_message' => __("Extra instellingen opgeslagen", 'acf'),
        'post_id' => 'extra-instellingen',
    ]);

    // add sub page
    acf_add_options_sub_page(array(
        'page_title' => __('Website instellingen'),
        'menu_title' => __('Website instellingen'),
        'menu_slug' => __('website-instellingen'),
        'icon_url' => 'dashicons-desktop',
        'update_button' => __('Opslaan', 'acf'),
        'updated_message' => __("Website instellingen opgeslagen", 'acf'),
        'parent_slug' 	=> 'extra-instellingen',
        'post_id' => 'options',
    ));

    // add sub page
    acf_add_options_sub_page(array(
        'page_title' => __('Contact informatie'),
        'menu_title' => __('Contact informatie'),
        'menu_slug' => __('contact-information'),
        'icon_url' => 'dashicons-location',
        'update_button' => __('Opslaan', 'acf'),
        'updated_message' => __("Contact informatie opgeslagen", 'acf'),
        'parent_slug' 	=> 'extra-instellingen',
        'post_id' => 'contact-info',
    ));

    // add sub page
    acf_add_options_sub_page(array(
        'page_title' => __('Thema instellingen'),
        'menu_title' => __('Thema instellingen'),
        'menu_slug' => __('thema-instellingen'),
        'icon_url' => 'dashicons-admin-generic',
        'update_button' => __('Aanpassen', 'acf'),
        'updated_message' => __("Thema bijgewerkt", 'acf'),
        'parent_slug' 	=> 'extra-instellingen',
        'post_id' => 'thema-instellingen',
    ));
}