<?php
/**
 * Enqueue scripts and styles.
 *
 * @package pb-starter
 **/


// Cache Busting
function fse_cache_version_id() {

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
		fse_cache_version_id() 
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
        fse_cache_version_id(),
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
 * Block Style Loader
 *
 * This setup automatically registers and enqueues CSS styles for blocks from any namespace.
 * - Scans /build/css/blocks/ folder for CSS files with {namespace}-{block-slug}.css naming.
 * - Loads each file as a block style on the frontend via wp_enqueue_block_style().
 * - Ensures the same styles are enqueued in the block editor for visual consistency.
 * - Supports unlimited namespaces by using the naming convention.
 *
 * File naming convention:
 * {namespace}-{block-slug}.css -> applies to {namespace}/{block-slug} block
 *
 * Example file structure:
 * build/css/blocks/
 *   core-button.css -> applies to core/button block
 *   core-image.css -> applies to core/image block
 *   cf-fancy-cta.css -> applies to cf/fancy-cta block
 *   acf-custom-field.css -> applies to acf/custom-field block
 *   my-plugin-special-block.css -> applies to my-plugin/special-block block
 */

add_action( 'init', 'fse_register_all_block_styles' );
function fse_register_all_block_styles() {

    $blocks_dir = get_stylesheet_directory() . '/build/css/blocks/';
    $blocks_uri = get_stylesheet_directory_uri() . '/build/css/blocks/';
    
    // Get all CSS files in the blocks directory
    $css_files = glob( $blocks_dir . '*.css' );
    
    if ( empty( $css_files ) ) {
        return;
    }
    
    foreach ( $css_files as $file_path ) {
        $filename = basename( $file_path );
        
        // Parse filename to extract namespace and block slug
        // Expected format: {namespace}-{block-slug}.css
        // Example: core-button.css -> namespace: core, block: button
        $name_without_extension = basename( $file_path, '.css' );
        
        // Split on first hyphen to separate namespace from block slug
        $parts = explode( '-', $name_without_extension, 2 );
        
        if ( count( $parts ) !== 2 ) {
            // Invalid filename format, skip
            continue;
        }
        
        list( $namespace, $block_slug ) = $parts;
        $block_name = $namespace . '/' . $block_slug;
        
        // Only proceed if the block is actually registered
        if ( ! WP_Block_Type_Registry::get_instance()->is_registered( $block_name ) ) {
            continue;
        }
        
        $handle = $namespace . '-' . $block_slug;
        
        wp_register_style(
            $handle,
            $blocks_uri . $filename,
            array(),
            fse_cache_version_id()
        );
        
        wp_enqueue_block_style( $block_name, array(
            'handle' => $handle,
            'src' => $blocks_uri . $filename,
            'path' => $file_path,
            'ver' => fse_cache_version_id(),
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