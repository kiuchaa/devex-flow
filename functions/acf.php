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
	if ( ! function_exists('acf_register_block_type') ) {
		return;
	}

	// Specific settings for known blocks (titles, icons, keywords)
	$pixel_flow_blocks = [
		'text-media' => [
			'title'    => __( 'Tekst & beeld', 'pixel-flow' ),
			'icon'     => 'align-pull-left',
			'keywords' => [ 'text', 'media' ],
		],
		'hero-carousel' => [
			'title'    => __( 'Hero carousel', 'pixel-flow' ),
			'icon'     => 'images-alt2',
			'keywords' => [ 'hero', 'carousel', 'slider' ],
		],
		'post-type-carousel' => [
			'title'    => __( 'Post type carousel', 'pixel-flow' ),
			'icon'     => 'slides',
			'keywords' => [ 'carousel', 'posts', 'slider', 'agenda' ],
		],
        'post-type-grid' => [
            'title'    => __( 'Post type grid', 'pixel-flow' ),
            'icon'     => 'email-alt',
            'keywords' => [ 'post type', 'grid', 'post' ],
        ],
		'unique-selling-points' => [
			'title'    => __( 'Unique selling points', 'pixel-flow' ),
			'icon'     => 'list-view',
			'keywords' => [ 'usp', 'features', 'columns' ],
		],
		'video-block' => [
			'title'    => __( 'Video blok', 'pixel-flow' ),
			'icon'     => 'video-alt3',
			'keywords' => [ 'video', 'embed', 'player' ],
		],
		'faq' => [
			'title'    => __( 'Veelgestelde vragen blok', 'pixel-flow' ),
			'icon'     => 'list-view',
			'keywords' => [ 'faq', 'accordion', 'questions' ],
		],
		'heading' => [
			'title'    => __( 'Kop tekst', 'pixel-flow' ),
			'icon'     => 'heading',
			'keywords' => [ 'heading', 'title', 'header' ],
		],
		'form' => [
			'title'    => __( 'Formulier', 'pixel-flow' ),
			'icon'     => 'email-alt',
			'keywords' => [ 'formulier', 'contact', 'ninja forms', 'form' ],
		],
		'text-columns' => [
			'title'    => __( 'Tekst kolommen', 'pixel-flow' ),
			'icon'     => 'email-alt',
			'keywords' => [ 'tekst', 'kolommen', 'columns' ],
		],
		'call-to-action' => [
			'title'    => __( 'Call to action', 'pixel-flow' ),
			'icon'     => 'megaphone',
			'keywords' => [ 'call to action', 'cta', 'actie' ],
		],
	];

	// Auto-discover all blocks in the acf-blocks directory
	$block_files = glob( get_template_directory() . '/acf-blocks/*.php' );
	
	if ( ! $block_files ) {
		return;
	}

	foreach ( $block_files as $file ) {
		$slug = basename( $file, '.php' );
		$settings = isset( $pixel_flow_blocks[ $slug ] ) ? $pixel_flow_blocks[ $slug ] : [];
		
		$settings['name']            = $slug;
		$settings['title']           = $settings['title'] ?? ucfirst( str_replace( '-', ' ', $slug ) );
		$settings['api_version']     = 3;
		$settings['render_template'] = "acf-blocks/{$slug}.php";
		$settings['category']        = 'custom_blocks';
		$settings['icon']            = $settings['icon'] ?? 'block-default';
		$settings['mode']            = $settings['mode'] ?? 'edit';
		
		// Map supports
		$settings['supports']        = wp_parse_args( $settings['supports'] ?? [], [
			'align'  => false,
			'mode'   => false,
			'anchor' => true,
		] );

		// Enhancement 2: Block-Specific Asset Loading
		$css_path = "/assets/css/blocks/{$slug}.css";
		if ( file_exists( get_template_directory() . $css_path ) ) {
			$settings['enqueue_style'] = get_template_directory_uri() . $css_path . '?v=' . filemtime( get_template_directory() . $css_path );
		}

		acf_register_block_type( $settings );
	}
}
add_action( 'acf/init', 'register_pixel_flow_blocks' );