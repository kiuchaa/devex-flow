<?php
/**
 * Button Component
 *
 * $link - The target URL, defined by ACF Link (return type array[])
 * $variant - can be primary or secondary, or any other defined in _button.scss
 * $class - the class override, you can call this if you want one button to look a bit different
 * $size - the size of the button
 */

$link    = $args['link'] ?? null;
$variant = $args['variant'] ?? 'primary'; // primary or secondary
$class   = $args['class'] ?? '';

if ( ! $link || empty( $link['url'] ) ) {
	return;
}

$url    = $link['url'];
$title  = $link['title'];
$target = $link['target'] ?: '_self';

$button_classes = 'btn fw-semibold ';

if ( $variant === 'secondary' ) {
	$button_classes .= 'btn-light text-dark';
} else {
	$button_classes .= 'btn-dark d-inline-flex align-items-center';
}

if ( $class ) {
	$button_classes .= ' ' . $class;
}

?>

<a href="<?php echo esc_url( $url ); ?>" 
   class="<?php echo esc_attr( $button_classes ); ?>" 
   target="<?php echo esc_attr( $target ); ?>">
	<?php echo esc_html( $title ); ?>
	<?php if ( $variant === 'primary' ) : ?>
		<span class="ms-2">→</span>
	<?php endif; ?>
</a>
