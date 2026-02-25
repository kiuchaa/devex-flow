<?php
// Custom block category
function pixel_flow_block_categories( $categories ) {
	return array_merge(
		$categories,
		[
			[
				'slug' => 'custom_blocks',
				'title' => __( 'Website blokken', 'pixel-flow' ),
			],
		]
	);
}
add_filter( 'block_categories_all', 'pixel_flow_block_categories', 10, 2 );

// Allowed block types
add_filter( 'allowed_block_types_all', 'pixel_flow_allowed_block_types', 10, 2 );
function pixel_flow_allowed_block_types( $allowed_block_types, $editor_context ) {
	// For now, allow everything or restrict to specific ones
	// Returning true allows all blocks. Returning an array restricts them.
//	return [
//		'core/paragraph',
//		'core/heading',
//		'core/list',
//		'core/image',
//		'core/buttons',
//		'acf/hero',
//		'acf/text-image',
//		'acf/hero',
//	];

    return true;
}

// Register blocks
function register_pixel_flow_blocks() {
	if ( function_exists('acf_register_block_type') ) {

		// Text & Image Block
		acf_register_block_type([
			'name'            => 'text-media',
			'title'           => __( 'Tekst & Beeld', 'pixel-flow' ),
			'render_template' => 'acf-blocks/text-media.php',
			'category'        => 'custom_blocks',
			'icon'            => 'align-pull-left',
			'keywords'        => [ 'text', 'media' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
			]
		]);

		// Hero Carousel Block
		acf_register_block_type([
			'name'            => 'hero-carousel',
			'title'           => __( 'Hero Carousel', 'pixel-flow' ),
			'render_template' => 'acf-blocks/hero-carousel.php',
			'category'        => 'custom_blocks',
			'icon'            => 'images-alt2',
			'keywords'        => [ 'hero', 'carousel', 'slider' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
			]
		]);

		// Post Type Carousel Block
		acf_register_block_type([
			'name'            => 'post-type-carousel',
			'title'           => __( 'Post Type Carousel', 'pixel-flow' ),
			'render_template' => 'acf-blocks/post-type-carousel.php',
			'category'        => 'custom_blocks',
			'icon'            => 'slides',
			'keywords'        => [ 'carousel', 'posts', 'slider', 'agenda' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
				'anchor' => true,
			]
		]);
		// Unique Selling Points Block
		acf_register_block_type([
			'name'            => 'unique-selling-points',
			'title'           => __( 'Unique Selling Points', 'pixel-flow' ),
			'render_template' => 'acf-blocks/unique_selling_points.php',
			'category'        => 'custom_blocks',
			'icon'            => 'list-view',
			'keywords'        => [ 'usp', 'features', 'columns' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
				'anchor' => true,
			]
		]);

		// Video Block
		acf_register_block_type([
			'name'            => 'video-block',
			'title'           => __( 'Video Blok', 'pixel-flow' ),
			'render_template' => 'acf-blocks/video_block.php',
			'category'        => 'custom_blocks',
			'icon'            => 'video-alt3',
			'keywords'        => [ 'video', 'embed', 'player' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
				'anchor' => true,
			]
		]);

		// FAQ Block
		acf_register_block_type([
			'name'            => 'faq-block',
			'title'           => __( 'FAQ Blok', 'pixel-flow' ),
			'render_template' => 'acf-blocks/faq-block.php',
			'category'        => 'custom_blocks',
			'icon'            => 'list-view',
			'keywords'        => [ 'faq', 'accordion', 'questions' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
				'anchor' => true,
			]
		]);

		// Heading Block
		acf_register_block_type([
			'name'            => 'heading',
			'title'           => __( 'Kop', 'pixel-flow' ),
			'render_template' => 'acf-blocks/heading.php',
			'category'        => 'custom_blocks',
			'icon'            => 'heading',
			'keywords'        => [ 'heading', 'title', 'header' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
				'anchor' => true,
			]
		]);

		// Formulier Block
		acf_register_block_type([
			'name'            => 'formulier',
			'title'           => __( 'Formulier', 'pixel-flow' ),
			'render_template' => 'acf-blocks/formulier.php',
			'category'        => 'custom_blocks',
			'icon'            => 'email-alt',
			'keywords'        => [ 'formulier', 'contact', 'ninja forms', 'form' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
				'anchor' => true,
			]
		]);

	}
}
add_action( 'acf/init', 'register_pixel_flow_blocks' );