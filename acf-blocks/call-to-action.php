<?php
/**
 * Block Name: Call to Action
 *
 * This is the template that displays the Call to Action block.
 */

$block_id = 'call-to-action-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// CSS classes
$classes = ['call-to-action-block'];
if ( ! empty( $block['className'] ) ) {
    $classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}
if ( ! empty( $block['align'] ) ) {
    $classes[] = 'align' . $block['align']; 
}

// ACF Fields directly bounded to the block
$content = get_field('content') ?: [];
$options = get_field('opties') ?: [];

$cta_id = $content['cta_post_id'] ?? null;

// Options
$bg_color_val = $options['achtergrondkleur'] ?? 'primary';
$text_align   = $options['text_align'] ?? 'text-center';

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

if ( ! $cta_id ) {
    echo '<div class="alert alert-warning m-3">Selecteer aub een Call to Action in het blok.</div>';
    return;
}

// Fetch data from the selected Call to Action Post Type
$title       = get_the_title( $cta_id );
$description = get_post_field( 'post_content', $cta_id );
$image_url   = get_the_post_thumbnail_url( $cta_id, 'large' );

// ACF Repeater from the Call to Action Post
$knoppen     = get_field( 'knoppen', $cta_id );

?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo $wrapper_class; ?>" data-pf-block="call-to-action">
    <div class="row g-0">
        <?php if ($image_url) : ?>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="call-to-action-block__image-wrapper h-100">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                </div>
            </div>
        <?php endif; ?>

        <div class="col-lg-<?php echo $image_url ? '6' : '12'; ?> d-flex align-items-center">
            <div class="container py-5 px-lg-5 <?php echo esc_attr($text_align); ?>">
                <div class="call-to-action-block__content-inner mx-auto <?php echo $image_url && $text_align === 'text-center' ? 'px-4' : ''; ?>" style="<?php echo !$image_url && $text_align === 'text-center' ? 'max-width: 800px;' : ''; ?>">
                    
                    <?php if ( $title ) : ?>
                        <h2 class="display-5 fw-bold mb-4">
                            <?php echo esc_html( $title ); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if ( $description ) : ?>
                        <div class="call-to-action-block__description mb-4 fs-5 <?php echo esc_attr($text_muted); ?>">
                            <?php echo wp_kses_post( wpautop($description) ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty($knoppen) && is_array($knoppen) ) : ?>
                        <div class="call-to-action-block__buttons d-flex flex-wrap gap-3 <?php echo $text_align === 'text-center' ? 'justify-content-center' : ''; ?> <?php echo $text_align === 'text-end' ? 'justify-content-end' : ''; ?> mt-4">
                            <?php 
                            // Render Buttons
                            foreach ( $knoppen as $index => $row ) {
                                $knop = $row['knop'] ?? null;
                                if ($knop && is_array($knop)) {
                                    
                                    // Make sure primary background has contrasting buttons
                                    $btn_variant = 'white'; 
                                    if ($index > 0) {
                                        $btn_variant = 'tertiary'; // secondary variant
                                    }
                                    if ($text_context_class === 'text-dark') {
                                        $btn_variant = $index === 0 ? 'primary' : 'black';
                                    }

                                    get_template_part('components/button', null, [
                                        'link'    => $knop,
                                        'variant' => $btn_variant,
                                        'class'   => 'px-4 py-3'
                                    ]);
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
