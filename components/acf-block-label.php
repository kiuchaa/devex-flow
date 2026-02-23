<?php
// Haal de titel en eventueel een custom label op
$block_title = $block['title'];
$anchor = !empty($block['anchor']) ? ' #' . $block['anchor'] : '';

if ( $is_preview ) {
    // Dit wordt alleen getoond in de Gutenberg Editor
    echo '<div class="acf-block-admin-label" style="
        background: #f0f0f1;
        padding: 10px 15px;
        margin-bottom: 20px;
        font-family: monospace;
        font-size: 12px;
        text-transform: uppercase;
        color: #3c434a;
        display: flex;
        justify-content: space-between;
    ">';
    echo '<span><strong>BLOCK:</strong> ' . esc_html($block_title) . '</span>';
    echo '<span style="opacity: 0.5;">' . esc_html($anchor) . '</span>';
    echo '</div>';
}
?>