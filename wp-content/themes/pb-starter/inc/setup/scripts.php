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
		THEME_URL . 'build/css/frontend.css',
		array(),
		cache_version_id() 
	);

}


/**
 * Gutenberg scripts and styles
 * For styles and scripts that impact the editor interface
 */
add_action( 'enqueue_block_editor_assets', 'fse_enqueue_block_editor_customizations' );
function fse_enqueue_block_editor_customizations() {

    $screen = get_current_screen();
    $context = $screen ? $screen->id : '';
    
    // Get dependencies based on context
    $dependencies = ['wp-blocks', 'wp-dom-ready'];
    switch ($context) {
        case 'site-editor':
            $dependencies[] = 'wp-edit-site';
            break;
        default:
            $dependencies[] = 'wp-edit-post';
            break;
    }
    
    wp_enqueue_script(
        'fse-block-editor-js',
        get_template_directory_uri() . '/build/js/editor.js',
        $dependencies,
        cache_version_id(),
        true
    );
    
    // Pass configuration to JavaScript
    wp_localize_script(
        'fse-block-editor-js',
        'myEditorOptions',
        [
            'hiddenStyles' => get_hidden_block_styles(),
            'registerStyles' => get_custom_block_styles(),
            'hiddenBlocks' => get_hidden_blocks(),
            'unregisteredVariations' => get_unregistered_block_variations(),
            'context' => $context,
            'postType' => get_post_type(),
        ]
    );
}

/**
 * Block Style Loader for Multiple Namespaces
 *
 * This setup automatically registers and enqueues CSS styles for blocks from any namespace.
 * - Scans /build/css/blocks/{namespace}/ folders for .css files.
 * - Loads each file as a block style on the frontend via wp_enqueue_block_style().
 * - Ensures the same styles are enqueued in the block editor for visual consistency.
 * - Supports unlimited namespaces by simply adding new folders.
 *
 * Folder structure expected:
 * build/
 * └── css/
 *     └── blocks/
 *         ├── core/
 *         │   ├── button.css → applies to core/button block
 *         │   └── image.css → applies to core/image block
 *         ├── cf/
 *         │   ├── fancy-cta.css → applies to cf/fancy-cta block
 *         │   └── product.css → applies to cf/product block
 *         ├── acf/
 *         │   └── custom-field.css → applies to acf/custom-field block
 *         └── my-plugin/
 *             └── special-block.css → applies to my-plugin/special-block block
 */

add_action( 'init', 'fse_register_all_block_styles' );
function fse_register_all_block_styles() {

    $blocks_base_dir = get_stylesheet_directory() . '/build/css/blocks/';
    $blocks_base_uri = get_stylesheet_directory_uri() . '/build/css/blocks/';
    
    // Get all namespace directories
    $namespace_dirs = glob( $blocks_base_dir . '*', GLOB_ONLYDIR );
    
    if ( empty( $namespace_dirs ) ) {
        return;
    }
    
    foreach ( $namespace_dirs as $namespace_dir ) {
        $namespace = basename( $namespace_dir );
        
        // Skip hidden directories or unwanted folders
        if ( str_starts_with( $namespace, '.' ) ) {
            continue;
        }
        
        fse_register_block_styles_from_dir(
            $namespace,
            $namespace_dir . '/',
            $blocks_base_uri . $namespace . '/',
            cache_version_id()
        );
    }
    
}

/**
 * Registers and enqueues block styles for all CSS files in the given directory.
 *
 * @param string $namespace Block namespace (e.g. 'core', 'cf', 'acf', etc.).
 * @param string $dir Absolute path to the CSS directory.
 * @param string $uri URI to the CSS directory.
 * @param string $version Theme version string.
 */
function fse_register_block_styles_from_dir( $namespace, $dir, $uri, $version ) {

    foreach ( glob( $dir . '*.css' ) as $file_path ) {

        $filename = basename( $file_path );
        $block_slug = basename( $file_path, '.css' );
        $block_name = $namespace . '/' . $block_slug;
        
        // Only proceed if the block is actually registered
        if ( ! WP_Block_Type_Registry::get_instance()->is_registered( $block_name ) ) {
            continue;
        }
        
        $handle = $namespace . '-' . $block_slug;
        
        wp_register_style(
            $handle,
            $uri . $filename,
            array(),
            cache_version_id()
        );
        
        wp_enqueue_block_style( $block_name, array(
            'handle' => $handle,
            'src' => $uri . $filename,
            'path' => $file_path,
            'ver' => cache_version_id(),
        ) );
    }

}

/**
 * Ensures block styles are enqueued in the editor.
 * Now dynamically handles any namespace prefix.
 */
add_action( 'enqueue_block_editor_assets', 'fse_enqueue_editor_styles_for_registered_blocks' );
function fse_enqueue_editor_styles_for_registered_blocks() {
    
    global $wp_styles;
    
    if ( empty( $wp_styles->registered ) ) {
        return;
    }
    
    // Get all registered block style handles that match our naming pattern
    foreach ( $wp_styles->registered as $handle => $style ) {

        // Check if this handle matches the pattern: {namespace}-{block-slug}
        if ( preg_match( '/^[a-zA-Z0-9_-]+-[a-zA-Z0-9_-]+$/', $handle ) ) {
            
            // Additional check to ensure this is one of our block styles
            // by verifying the file path contains our blocks directory
            if ( isset( $style->src ) && strpos( $style->src, '/build/css/blocks/' ) !== false ) {
                wp_enqueue_style( $handle );
            }

        }

    }

}