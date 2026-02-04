<?php
/**
 * Text & Image Block Template.
 *
 * @package pixel-flow
 */

$block_id = 'text-image-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Load ACF fields
$title       = get_field( 'title' );
$text        = get_field( 'text' );
$button      = get_field( 'button' );
$image       = get_field( 'image' );
$orientation = get_field( 'orientation' ) ?: 'left';

// Wrapper classes
$wrapper_class = 'b-text-image ' . ( $orientation === 'right' ? 'image-right' : 'image-left' );
if ( ! empty( $block['className'] ) ) {
	$wrapper_class .= ' ' . $block['className'];
}

// Column ordering logic
// Always show image first on mobile (order-1), but swap on desktop (order-lg-1 / order-lg-2)
$content_order = ( $orientation === 'right' ) ? 'order-2 order-lg-1' : 'order-2 order-lg-2';
$image_order   = ( $orientation === 'right' ) ? 'order-1 order-lg-2' : 'order-1 order-lg-1';
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $wrapper_class ); ?>">
	<div class="container">
		<div class="row align-items-center gy-5 gx-0 gx-md-5">

			<!-- Content Column -->
			<div class="col-lg-6 <?php echo esc_attr( $content_order ); ?>">
				<div class="b-text-image__content">
					<?php if ( $title ) : ?>
						<h2 class="display-5 fw-bolder mb-4 ls-tight">
							<?php echo esc_html( $title ); ?>
						</h2>
					<?php endif; ?>

					<?php if ( $text ) : ?>
						<div class="lead text-secondary mb-5">
							<?php echo wp_kses_post( $text ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $button ) : ?>
						<div class="b-text-image__button">
							<?php
							get_template_part( 'components/button', null, [
								'link'    => $button,
								'variant' => 'primary',
                                'size' => 'btn-lg'
							] );
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- Image Column -->
			<div class="col-lg-6 <?php echo esc_attr( $image_order ); ?>">
				<?php if ( $image ) : ?>
					<div class="b-text-image__image-wrapper">
						<img src="<?php echo esc_url( $image['url'] ); ?>" 
							 alt="<?php echo esc_attr( $image['alt'] ); ?>" 
							 class="img-fluid">
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
