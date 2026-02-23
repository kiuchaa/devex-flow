<?php
/**
 * Block Name: Heading
 *
 * This is the template that displays the Heading block.
 */

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'heading';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// Get the fields
$content_group = get_field('content'); 
$options_group = get_field('options');

// Content sub-fields
$mini_title = $content_group['mini_title'] ?? '';
$title      = $content_group['title'] ?? '';
$text       = $content_group['text'] ?? '';
$button     = $content_group['button'] ?? '';
$image      = $content_group['background_image'] ?? '';

// Options sub-fields
$use_bg_image = $options_group['use_background_image'] ?? false;
$bg_color     = $options_group['background_color'] ?? 'white';
$icon_color   = $options_group['accent_kleur'] ?? 'black';
$width        = $options_group['width'] ?? 'container';
$text_align   = $options_group['text_alignment'] ?? 'text-center'; // Default center

// Initialize styles
$section_style = '';
$container_style = '';
$overlay_class = '';

if ( $use_bg_image && !empty($image) ) {
    $bg_url = esc_url($image['url']);
    
    if ($width === 'container') {
        // Apply background to the inner container
        $container_style = 'style="background-image: url(' . $bg_url . ');"';
        $class_name .= ' heading--container-bg';
    } else {
        // Apply background to the full section
        $section_style = 'background-image: url(' . $bg_url . ');';
        $class_name .= ' heading--full-bg';
    }
    $overlay_class = 'heading__overlay';
    
    // Text is usually white on bg image
    $class_name .= ' text-white';
} else {
    // Apply background color class (using Bootstrap/Utility classes)
    $class_name .= ' bg-' . esc_attr($bg_color);
    
    // Determine text color based on background for contrast
    if (in_array($bg_color, ['white', 'info'])) {
        $class_name .= ' text-dark';
    } else {
        $class_name .= ' text-white';
    }
}

// Combine section styles
$final_section_style = !empty($section_style) ? 'style="' . $section_style . '"' : '';

// Helper to check alignment for flex justification
$justify_class = 'justify-content-center';
if ($text_align === 'text-start') {
    $justify_class = 'justify-content-start';
} elseif ($text_align === 'text-end') {
    $justify_class = 'justify-content-end';
}

?>

<section class="<?php echo esc_attr( $class_name ); ?>" <?php echo $final_section_style; ?>>
    <?php if ( $use_bg_image && $width === 'full-width' ) : ?>
        <div class="<?php echo $overlay_class; ?>"></div>
    <?php endif; ?>

    <div class="container heading__container" <?php echo $container_style; ?>>
        <?php if ( $use_bg_image && $width === 'container' ) : ?>
            <div class="<?php echo $overlay_class; ?>"></div>
        <?php endif; ?>

        <div class="heading__content <?php echo esc_attr( $text_align ); ?>">
            <?php if ( $mini_title ) : ?>
                <span class="heading__mini-title text-<?php echo esc_attr($icon_color); ?>">
                    <?php echo esc_html( $mini_title ); ?>
                </span>
            <?php endif; ?>
            
            <?php if ( $title ) : ?>
                <h2 class="heading__title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $text ) : ?>
                <div class="heading__text">
                    <?php echo wp_kses_post( $text ); ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $button ) : ?>
                <div class="heading__actions <?php echo esc_attr($justify_class); ?>">
                    <?php 
                    $btn_title = $button['title'] ?? 'Button';
                    $btn_url = $button['url'] ?? '#';
                    $btn_target = $button['target'] ?? '_self';

                    get_template_part('components/button', null, [
                        'link' => [
                            'url' => $btn_url,
                            'title' => $btn_title,
                            'target' => $btn_target
                        ],
                        'variant' => $icon_color,
                    ]);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
