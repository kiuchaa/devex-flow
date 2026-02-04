<?php
/**
 * ACF Blocks & Options
 */

// Custom block category
function pixel_flow_block_categories( $categories ) {
	return array_merge(
		$categories,
		[
			[
				'slug' => 'custom_blocks',
				'title' => __( 'Custom Blocks', 'pixel-flow' ),
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
		
		// Hero Block
		acf_register_block_type([
			'name'            => 'hero',
			'title'           => __( 'Hero', 'pixel-flow' ),
			'render_template' => 'acf-blocks/hero.php',
			'category'        => 'custom_blocks',
			'icon'            => 'align-full-width',
			'keywords'        => [ 'hero', 'banner' ],
			'mode'            => 'edit',
			'supports'        => [
				'align'  => false,
				'mode'   => false,
			]
		]);

		// Text & Image Block
		acf_register_block_type([
			'name'            => 'text-image',
			'title'           => __( 'Text & Image', 'pixel-flow' ),
			'render_template' => 'acf-blocks/text-image.php',
			'category'        => 'custom_blocks',
			'icon'            => 'align-pull-left',
			'keywords'        => [ 'text', 'image' ],
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
	}
}
add_action( 'acf/init', 'register_pixel_flow_blocks' );

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