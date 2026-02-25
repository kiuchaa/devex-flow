<?php
function plt_hide_ninja_forms_metaboxes() {
    $screen = get_current_screen();
    if ( !$screen ) {
        return;
    }

    //Hide the "Add-On Licenses" meta box.
    remove_meta_box('nf_settings_licenses', $screen->id, 'advanced');
    //Hide the "Append a Ninja Form" meta box.
    remove_meta_box('nf_admin_metaboxes_appendaform', $screen->id, 'side');
    //Hide the "User Submitted Values" meta box.
    remove_meta_box('nf_sub_fields', $screen->id, 'normal');
    //Hide the "Submission Info" meta box.
    remove_meta_box('nf_sub_info', $screen->id, 'side');
}

add_action('add_meta_boxes', 'plt_hide_ninja_forms_metaboxes', 20);

function include_field_types_ninja_forms() {
    include_once('acf_ninja_forms.php');
}
add_action('acf/include_field_types', 'include_field_types_ninja_forms');

// Disable Ninja Forms default styling to use theme styles only
add_filter( 'ninja_forms_render_default_css', '__return_false' );
add_filter( 'ninja_forms_display_css', '__return_false' );
