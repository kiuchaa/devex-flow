<?php

function forceer_documentoverzicht_gutenberg() {
    // Dit script draait alleen binnen de blok-editor
    $script = "
        window.onload = function() {
            wp.domReady(function() {
                // Controleer of de lijstweergave al open is
                const isListViewOpen = wp.data.select('core/edit-post').isListViewOpened();
                
                if (!isListViewOpen) {
                    // Forceer het openen van de lijstweergave
                    wp.data.dispatch('core/edit-post').setIsListViewOpened(true);
                }
            });
        };
    ";
    wp_add_inline_script('wp-edit-post', $script);
}
add_action('enqueue_block_editor_assets', 'forceer_documentoverzicht_gutenberg');