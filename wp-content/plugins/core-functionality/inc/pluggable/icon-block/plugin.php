<?php
/**
 * Feature: Register the Icon Block
 *
 * @package     Core Functionality
 * @subpackage  Pluggable Features
 * @author      Patrick Boehner
 * @link        https://patrickboehner.com
 * @copyright   Copyright (c) 2012-{Current Year}, Patrick Boehner
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 *
 * @link: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 * @link: https://wpfieldwork.com/a-guide-to-registering-a-custom-acf-block-with-block-json/
 * @link: https://www.modernwpdev.co/acf-blocks/registering-blocks/#block-data
 * @link: https://fullstackdigital.io/blog/acf-block-json-with-wordpress-scripts-the-ultimate-custom-block-development-workflow/
 */

//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;

// error_log('Icon block plugin loaded');


add_action( 'init', 'cf_register_icon_block', 5 );
function cf_register_icon_block() {

    // Check availability of block editor
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // adjust location(s) as needed for block.json
    register_block_type( CORE_DIR . 'inc/pluggable/icon-block/block' );

}


/**
 * Dynamic Icon Select
 * Lists icons found in theme's /build/svg directory
 *
 * https://www.billerickson.net/dynamic-dropdown-fields-in-acf/
 */
add_filter('acf/load_field/name=icon_select', 'cf_acf_icon_select' );
function cf_acf_icon_select( $field ) {

    $field['choices'] = array( '' => '(None)' );

    if( ! function_exists( 'cf_get_theme_icons' ) )
        return $field;

    // Get icons from multiple directories
    $directories = array( 'utility', 'decorative' );

    foreach( $directories as $directory ) {
        $icons = cf_get_theme_icons( $directory );
        foreach( $icons as $icon ) {
            // Use directory/icon format as the value, nice label as display
            $field['choices'][ $directory . '/' . $icon ] = ucwords( str_replace( ['-', '_'], ' ', $icon ) );
        }
    }

    return $field;

}


/**
 * Get Theme Icons from theme's /build/svg directory
 * Refresh cache by bumping WP_THEME_VERSION (or manually clear with cf_clear_icon_cache())
 */
function cf_get_theme_icons( $directory = 'decorative' ) {

    $icons = get_option( 'cf_theme_icons_' . $directory );
    $version = get_option( 'cf_theme_icons_' . $directory . '_version' );
    $current_version = defined( 'WP_THEME_VERSION' ) ? WP_THEME_VERSION : wp_get_theme()->get('Version');

    if( empty( $icons ) || version_compare( $current_version, $version, '>' ) ) {

        // Get icons from theme's build/svg directory
        $svg_path = get_template_directory() . '/build/svg/' . $directory;
        
        if( ! file_exists( $svg_path ) ) {
            return array();
        }

        $icons = scandir( $svg_path );
        $icons = array_diff( $icons, array( '..', '.' ) );
        $icons = array_values( $icons );

        if( empty( $icons ) ) {
            return $icons;
        }

        // remove the .svg extension
        foreach( $icons as $i => $icon ) {
            if( substr( $icon, -4 ) === '.svg' ) {
                $icons[ $i ] = substr( $icon, 0, -4 );
            }
        }

        update_option( 'cf_theme_icons_' . $directory, $icons );
        update_option( 'cf_theme_icons_' . $directory . '_version', $current_version );

    }

    return $icons;

}


/**
 * Helper function to manually clear icon cache
 * Call this function when you add new icons and want to see them immediately
 */
function cf_clear_icon_cache() {

    $directories = array( 'utility', 'decorative' );

    foreach( $directories as $directory ) {
        delete_option( 'cf_theme_icons_' . $directory );
        delete_option( 'cf_theme_icons_' . $directory . '_version' );
    }

}

// Uncomment the line below to clear cache on next page load (then comment it back out)
add_action( 'init', 'cf_clear_icon_cache' );