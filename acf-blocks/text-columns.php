<?php
/**
 * Block Name: Text Columns
 *
 * This is the template that displays the Text Columns block.
 */

$block_id = 'text-columns-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// CSS classes
$classes = ['text-columns-block', 'py-5'];
if ( ! empty( $block['className'] ) ) {
    $classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}
if ( ! empty( $block['align'] ) ) {
    $classes[] = 'align' . $block['align']; // Note: ACF outputs alignfull, alignwide, etc.
}

// ACF Fields
$content = get_field('content') ?: [];
$options = get_field('opties') ?: [];

$title     = $content['title'] ?? '';
$text      = $content['text'] ?? '';
$columns   = isset($content['columns']) && is_array($content['columns']) ? $content['columns'] : [];

// Options
$bg_color_val = $options['achtergrondkleur'] ?? 'white';

// Contrast Logic
$text_context_class = 'text-white';
$text_muted = 'text-white-50';
if (in_array($bg_color_val, ['white', 'info', 'light'])) {
    $text_context_class = 'text-dark';
    $text_muted = 'text-muted';
}

$classes[] = "bg-{$bg_color_val}";
$classes[] = $text_context_class;
$wrapper_class = esc_attr( implode( ' ', $classes ) );

// Determine column sizing based on count
$col_count = count($columns);
$col_class = 'col-lg-6'; // Default to half width for 1-2
if ($col_count === 3) {
    $col_class = 'col-lg-4 col-md-6';
} elseif ($col_count >= 4) {
    $col_class = 'col-lg-3 col-md-6';
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo $wrapper_class; ?>" data-pf-block="text-columns">
    <div class="container">
        
        <?php if ($title || $text): ?>
            <div class="row mb-5 justify-content-center text-center">
                <div class="col-lg-8">
                    <?php if ( $title ) : ?>
                        <h2 class="display-6 fw-bold mb-3">
                            <?php echo esc_html( $title ); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if ( $text ) : ?>
                        <p class="mb-0 <?php echo esc_attr($text_muted); ?> fs-5">
                            <?php echo html_entity_decode( $text ); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($columns)): ?>
            <div class="row g-4">
                <?php foreach ($columns as $column): ?>
                    <div class="<?php echo esc_attr($col_class); ?>">
                        <div class="text-columns-block__column h-100">
                            <?php 
                                // Output the WYSIWYG editor content directly
                                echo wp_kses_post( $column['column_text'] ); 
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>
