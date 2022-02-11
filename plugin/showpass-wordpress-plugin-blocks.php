<?php

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function create_block_showpass_wordpress_blocks_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build-blocks/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/showpass-wordpress-blocks" block first.'
		);
	}
	$index_js     = 'build-blocks/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'create-block-showpass-wordpress-blocks-block-editor-script',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		false
		//$script_asset['version']
	);
	if (function_exists('wp_set_script_translations')) {
		wp_set_script_translations( 'create-block-showpass-wordpress-blocks-block-editor', 'showpass-wordpress-blocks' );
	}

	$editor_css = 'build-blocks/index.css';
	wp_register_style(
		'create-block-showpass-wordpress-blocks-block-editor-style',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" ),
		false
	);

	$style_css = 'build-blocks/style-index.css';
	wp_register_style(
		'create-block-showpass-wordpress-blocks-block-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" ),
		false
	);
	if (function_exists('register_block_type')) {
		register_block_type( 'create-block/showpass-wordpress-blocks', array(
			'editor_script' => 'create-block-showpass-wordpress-blocks-block-editor-script',
			'editor_style'  => 'create-block-showpass-wordpress-blocks-block-editor-style',
			'style'         => 'create-block-showpass-wordpress-blocks-block-style',
		) );
	}
}
add_action( 'init', 'create_block_showpass_wordpress_blocks_block_init' );

function showpass_plugin_block_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'showpass-blocks',
				'title' => __( 'Showpass', 'showpass-blocks' ),
			),
		)
	);
}
add_filter( 'block_categories_all', 'showpass_plugin_block_category', 10, 2);