<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @package pb-starter
 **/


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since 0.8.0
 *
 * @return void
 */

add_action( 'after_setup_theme', 'fse_starter_setup' );	
function fse_starter_setup() {

    /**
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_theme_textdomain( 'pb-starter', THEME_PATH . 'languages' );

    // Remove core block patterns.
    remove_theme_support( 'core-block-patterns' );

    // Disable the loading of remote patterns from the Dotorg pattern directory.
    add_filter( 'should_load_remote_block_patterns', '__return_false' );

    /**
     * Enable editor styles and add theme editor stylesheet.
     * 
     * When using add_editor_style(), WordPress automatically:
     * 1. Wraps all selectors with .editor-styles-wrapper for proper scoping
     * 2. Inlines the processed CSS in the editor's <head> section
     * 
     * This prevents editor styles from affecting the admin interface.
     */

    // Enable editor styles support
    add_theme_support( 'editor-styles' );

    // Add your editor stylesheet
    add_editor_style( '/assets/css/editor-min.css' );

}