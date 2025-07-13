<?php
/**
 * Enqueue scripts and styles.
 *
 * @package pb-starter
 **/


// Cache Busting
function cache_version_id() {

	if ( WP_DEBUG ) {
		return time();
	} else {
		return THEME_VERSION;
	}

}


/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'fse_starter_enqueue_stylesheet' );
function fse_starter_enqueue_stylesheet() {

	wp_enqueue_style( 
		'frontend-style',
		THEME_URL . 'assets/css/frontend.min.css',
		array(),
		cache_version_id() 
	);

}


/**
 * Gutenberg scripts and styles
 */
add_action( 'enqueue_block_editor_assets', 'fse_enqueue_block_editor_customizations' );
function fse_enqueue_block_editor_customizations() {

    // List of block styles you want to unregister
    $hidden_styles = [
        'core/social-links' => ['logos-only', 'pill-shape'],
        'core/button'       => ['fill', 'squared', 'outline'],
        'core/quote'        => ['default', 'plain'],
        'core/image'        => ['rounded'],
        'core/separator'    => ['wide', 'dots'],
    ];

    // Custom styles you want to register
	$custom_styles = [
		'core/button' => [
			[ 'name' => 'primary', 'label' => 'Primary', 'isDefault' => true ],
            [ 'name' => 'secondary', 'label' => 'Secondary' ],
		],
		'core/list' => [
            [ 'name' => 'default', 'label' => 'Default', 'isDefault' => true ],
            [ 'name' => 'no-bullets', 'label' => 'No Bullets' ],
			[ 'name' => 'checkmarks', 'label' => 'Checkmarks' ],
            [ 'name' => 'arrows', 'label' => 'Arrows' ],
		],
	];

    // Enqueue your editor JS with dependencies
    wp_enqueue_script(
        'my-block-editor-js',
        get_template_directory_uri() . '/assets/js/editor.js', // Adjust path as needed
        [ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
        cache_version_id(),
        true
    );

    // Pass the hidden styles list to JS as a global variable
    wp_localize_script(
        'my-block-editor-js',
        'myEditorOptions',
        [
            'hiddenStyles' => $hidden_styles,
            'registerStyles' => $custom_styles,
        ]
    );

}


/**
 * Block Style Loader for Core and Custom Blocks
 *
 * This setup automatically registers and enqueues CSS styles for both core and custom blocks.
 * - Scans /assets/css/blocks/core/ and /assets/css/blocks/custom/ for .css files.
 * - Loads each file as a block style on the frontend via wp_enqueue_block_style().
 * - Ensures the same styles are enqueued in the block editor for visual consistency.
 *
 * Folder structure expected:
 * assets/
 * └── css/
 *     └── blocks/
 *         ├── core/
 *         │   ├── button.css       → applies to core/button block
 *         │   └── image.css        → applies to core/image block
 *         └── custom/
 *             ├── fancy-cta.css    → applies to custom/fancy-cta block
 *             └── product.css      → applies to custom/product block
 */

add_action( 'init', 'fse_register_all_block_styles' );
function fse_register_all_block_styles() {

	// Load core block styles
	fse_register_block_styles_from_dir(
		'core',
		get_stylesheet_directory() . '/assets/css/blocks/core/',
		get_stylesheet_directory_uri() . '/assets/css/blocks/core/',
		cache_version_id()
	);

	// Load custom block styles
	fse_register_block_styles_from_dir(
		'custom',
		get_stylesheet_directory() . '/assets/css/blocks/custom/',
		get_stylesheet_directory_uri() . '/assets/css/blocks/custom/',
		cache_version_id()
	);
}

/**
 * Registers and enqueues block styles for all CSS files in the given directory.
 *
 * @param string $namespace Block namespace (e.g. 'core' or 'custom').
 * @param string $dir       Absolute path to the CSS directory.
 * @param string $uri       URI to the CSS directory.
 * @param string $version   Theme version string.
 */
function fse_register_block_styles_from_dir( $namespace, $dir, $uri, $version ) {
    foreach ( glob( $dir . '*.min.css' ) as $file_path ) {
        $filename = basename( $file_path );
        $block_slug = basename( $file_path, '.min.css' );
        
        if ( $namespace === 'custom' ) {
            $potential_cf_block = 'cf/' . $block_slug;
            if ( WP_Block_Type_Registry::get_instance()->is_registered( $potential_cf_block ) ) {
                $block_name = $potential_cf_block;
            } else {
                $block_name = $namespace . '/' . $block_slug;
            }
        } else {
            $block_name = $namespace . '/' . $block_slug;
        }
        
        $handle = $namespace . '-' . $block_slug;
        
        wp_register_style(
            $handle,
            $uri . $filename,
            array(),
            $version . '.' . filemtime( $file_path )
        );
        
        wp_enqueue_block_style( $block_name, array(
            'handle' => $handle,
            'src' => $uri . $filename,
            'path' => $file_path,
            'ver' => $version . '.' . filemtime( $file_path ),
        ) );
    }
}

/**
 * Ensures block styles are enqueued in the editor.
 */
add_action( 'enqueue_block_editor_assets', 'fse_enqueue_editor_styles_for_registered_blocks' );
function fse_enqueue_editor_styles_for_registered_blocks() {
	
	global $wp_styles;

	if ( ! empty( $wp_styles->registered ) ) {
		foreach ( $wp_styles->registered as $handle => $style ) {
			if (
				str_starts_with( $handle, 'core-' ) ||
				str_starts_with( $handle, 'custom-' )
			) {
				wp_enqueue_style( $handle );
			}
		}
	}
}
