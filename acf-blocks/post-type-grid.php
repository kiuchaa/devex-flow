<?php
/**
 * Block Name: Post Type Grid
 *
 * This is the template that displays the Post Type Grid block.
 */

$block_id = 'post-type-grid-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// CSS classes
$classes = ['post-type-grid'];
if ( ! empty( $block['className'] ) ) {
    $classes = array_merge( $classes, explode( ' ', $block['className'] ) );
}
if ( ! empty( $block['align'] ) ) {
    $classes[] = 'align' . $block['align'];
}

// ACF Fields
$content = get_field('content') ?: [];
$options = get_field('opties') ?: [];

$label     = $content['label'] ?? '';
$title     = $content['title'] ?? 'Overzicht';
$text      = $content['text'] ?? '';
$cta_link  = $content['cta_link'] ?? null;
$post_type = $content['post_type'] ?: 'post';
$limit     = $content['limit'] ?: 6;
$columns   = isset($content['columns']) ? intval($content['columns']) : 3;

// Options
$bg_color_val = $options['achtergrondkleur'] ?: 'white';
$icon_color = $options['accent_kleur'] ?: 'black';

// Contrast Logic
$text_context_class = 'text-white';
$text_muted = 'text-white-50';
if (in_array($bg_color_val, ['white', 'info'])) {
    $text_context_class = 'text-dark';
    $text_muted = 'text-muted';
}

$classes[] = "bg-{$bg_color_val}";
$classes[] = $text_context_class;
$classes[] = "py-5";
$wrapper_class = esc_attr( implode( ' ', $classes ) );

$post_term = get_post_type_object($post_type);
$plural_label = $post_term ? $post_term->label : 'Overzicht';
$singular_label = $post_term ? $post_term->labels->singular_name : 'Item';

// Setup grid columns logic
$col_class = 'col-lg-4 col-md-6';
if ($columns === 2) {
    $col_class = 'col-lg-6 col-md-6';
} elseif ($columns === 4) {
    $col_class = 'col-lg-3 col-md-6';
}

// Query
$args = [
	'post_type'      => $post_type,
	'posts_per_page' => $limit,
	'post_status'    => 'publish',
];
$query = new WP_Query( $args );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo $wrapper_class; ?>" data-pf-block="post-type-grid">
    <div class="container">
        
        <div class="row align-items-end mb-5">
			<div class="col-lg-8">
				<?php if ( $label ) : ?>
					<h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.9rem; opacity: 0.8;">
						<?php echo esc_html( $label ?: $plural_label ); ?>
					</h6>
				<?php endif; ?>

				<?php if ( $title ) : ?>
					<h2 class="display-5 fw-bold mb-3">
						<?php echo esc_html( $title ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<p class="mb-0 <?php echo esc_attr($text_muted); ?>" style="max-width: 600px;">
						<?php echo html_entity_decode( $text ); ?>
					</p>
				<?php endif; ?>
			</div>
			
			<div class="col-lg-4 d-flex justify-content-lg-end mt-4 mt-lg-0 align-items-center gap-3">
				<?php if ( $cta_link ) :
                    get_template_part('components/button', null, [
                            'link' => [
                                    'url' => esc_url( $cta_link['url'] ),
                                    'title' => esc_html( $cta_link['title']),
                                    'target' => $cta_link['target'] ?: '_self'
                            ],
                            'variant' => $icon_color,
                            'class' => 'px-4 py-2 fw-medium'
                    ]);
                endif; ?>
			</div>
		</div>

        <div class="row g-4 pt-3">
			<?php if ( $query->have_posts() ) : ?>
                <?php while ( $query->have_posts() ) : $query->the_post(); 
                    $categories = get_the_terms( get_the_ID(), 'category' );
                    $cat_name = !empty($categories) ? $categories[0]->name : '';
                    $location = get_field('location');
                ?>
                    <div class="<?php echo esc_attr($col_class); ?>">
                        <div class="card h-100 bg-transparent border-0 post-type-grid__card">
                            <!-- Image Wrapper -->
                            <div class="position-relative rounded-3 overflow-hidden mb-4" style="aspect-ratio: 4/3;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php 
                                    the_post_thumbnail( 'large', [
                                        'class' => 'w-100 h-100 object-fit-cover transition-transform duration-500 hover-scale',
                                        'decoding' => 'async',
                                        'loading' => 'lazy'
                                    ] ); 
                                    ?>
                                <?php else : ?>
                                    <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center">
                                        <span class="<?php echo esc_attr($text_muted); ?>"><i class="fa-solid fa-image fa-2xl"></i></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <div class="card-body p-0 d-flex flex-column">
                                <?php if ( $cat_name ) : ?>
                                    <div class="mb-2">
                                        <span class="badge bg-dark border border-secondary fw-normal px-3 py-2 rounded-2"><?php echo esc_html( $cat_name ); ?></span>
                                    </div>
                                <?php endif; ?>

                                <h4 class="card-title fw-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="text-reset text-decoration-none stretched-link">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                
                                <?php if($location): ?>
                                    <div class="<?php echo esc_attr($text_muted); ?> mb-3 small"><?php echo esc_html($location); ?></div>
                                <?php endif; ?>

                                <div class="card-text <?php echo esc_attr($text_muted); ?> mb-4 clamp-2">
                                    <?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
                                </div>

                                <div class="mt-auto d-flex align-items-center fw-bold hover-translate">
                                    Bekijk <?= strtolower($singular_label); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right ms-2" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<div class="col-12">
                    <div class="alert alert-info bg-transparent border-secondary <?php echo esc_attr($text_muted); ?>">
                        Geen records gevonden.
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
</section>
