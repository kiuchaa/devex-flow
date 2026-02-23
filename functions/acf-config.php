<?php

/**
 * The following function is very important.
 *  It ensures that all ACF field groups are stored based on name. This reads MUCH better.
 *
 *  Standard behaviour
 *      group_68ecaf06aaf8b.json
 *
 *  New behaviour
 *      extra-velden-thema-instellingen.json
 */
function custom_acf_json_filename( $filename, $post, $load_path ) {
    $filename = str_replace(
        array(
            ' ',
            '_',
        ),
        array(
            '-',
            '-'
        ),
        $post['title']
    );

    $filename = strtolower( $filename ) . '.json';

    return $filename;
}
add_filter( 'acf/json/save_file_name', 'custom_acf_json_filename', 10, 3 );

/**
 * Add a confirmation popup to an ACF Button Group field.
 */
add_action('acf/input/admin_footer', 'webshop_modus_confirm_script');

function webshop_modus_confirm_script() {
    ?>
    <script type="text/javascript">
        (function($) {
            if( typeof acf === 'undefined' ) return;
            acf.addAction('ready_field/name=webshop_modus', function( field ){
                var previousValue = field.val();

                field.$el.off('change', 'input').on('change', 'input', function( e ){
                    var newValue = $(this).val();
                    // Safety check: only run if the value actually changed
                    if (newValue === previousValue) return;
                    var message = "Weet je het heel zeker dat je dit wilt aanschakelen?";
                    if( !confirm(message) ) {
                        e.preventDefault();
                        field.val(previousValue);
                        return false;
                    }
                    previousValue = newValue;
                });
            });
        })(jQuery);
    </script>
    <?php
}

/**
 * Populate an ACF select field with all public post types.
 */
add_filter('acf/load_field/name=post_type', function($field) {
    // Clear any manual choices set in the UI
    $field['choices'] = array();

    // Get all public post types as objects
    $post_types = get_post_types(array('public' => true), 'objects');

    // Loop through post types and add to choices array
    // Value = post type slug (e.g., 'page'), Label = post type name (e.g., 'Pages')
    foreach ($post_types as $post_type) {
        $field['choices'][$post_type->name] = $post_type->label;
    }

    return $field;
});

