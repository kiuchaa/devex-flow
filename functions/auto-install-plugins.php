<?php
/**
 * TGM Plugin Activation
 *
 * @package    TGM-Plugin-Activation
 * @version    2.6.1
 */

require_once get_template_directory() . '/libs/install-required-plugins/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'pf_register_required_plugins' );

function pf_register_required_plugins() {
	$plugins = array(
		array(
			'name'               => 'Advanced Custom Fields PRO',
			'slug'               => 'advanced-custom-fields-pro',
			'source'             => get_template_directory() . '/libs/install-required-plugins/plugins/advanced-custom-fields-pro.zip',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
        array(
            'name'     => 'Post Types Order',
            'slug'     => 'post-types-order',
            'required' => true,
        ),
		array(
			'name'     => 'Ninja Forms',
			'slug'     => 'ninja-forms',
			'required' => true,
		),
		array(
			'name'     => 'Yoast SEO',
			'slug'     => 'wordpress-seo',
			'required' => true,
		),
		array(
			'name'     => 'Admin Columns',
			'slug'     => 'codepress-admin-columns',
			'required' => true,
		),
		array(
			'name'     => 'Postmark for WordPress',
			'slug'     => 'postmark-approved-wordpress-plugin',
			'required' => true,
		),
        array(
            'name'     => 'Advanced Custom Fields: Font Awesome Field',
            'slug'     => 'advanced-custom-fields-font-awesome',
            'required' => true,
        ),
		array(
			'name'     => 'Better Search Replace',
			'slug'     => 'better-search-replace',
			'required' => true,
		),
	);

	$config = array(
		'id'           => 'pf',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => true,
		'message'      => '',
	);

	tgmpa( $plugins, $config );
}
