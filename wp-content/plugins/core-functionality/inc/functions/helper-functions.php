<?php

/**
 * Custom functions
 *
 * @author      Patrick Boehner
 * @link        http://www.patrickboehner.com
 * @package     Core Functionality
 * @copyright   Copyright (c) 2012, Patrick Boehner
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Check if the site URL contains any of the given patterns
 *
 * @since 1.5.2
 * @param array $patterns Array of strings to search for in the URL
 * @return boolean
 */
function cf_url_contains( $patterns ) {
	$url = home_url();
	foreach ( $patterns as $pattern ) {
		if ( strpos( $url, $pattern ) !== false ) {
			return true;
		}
	}
	return false;
}


/**
 * Get the current environment slug based on custom detection functions.
 *
 * @return string One of: 'local', 'development-staging', 'staging', 'production'
 */
function cf_get_environment_slug() {

	if ( cf_is_local_dev_site() ) {
		return 'local';
	}

	if ( cf_is_development_staging_site() ) {
		return 'development-staging';
	}

	if ( cf_is_staging_site() ) {
		return 'staging';
	}

	return 'production';
}


/**
 * Check if current site is a local development site
 *
 * @since 1.2.0
 * @return boolean
 */
function cf_is_local_dev_site() {

	if ( cf_url_contains( array( 'localdev', 'localhost', '.dev', '.local' ) ) ) {
		return true;
	}

	if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'local' ) {
		return true;
	}

	return false;

}


/**
 * Check if current site is a development staging site
 *
 * @since 1.2.0
 * @return boolean
 */
function cf_is_development_staging_site() {

	if ( cf_url_contains( array( 'wpclientstaging.com' ) ) ) {
		return true;
	}

	if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'development' ) {
		return true;
	}

	return false;

}


/**
 * Check if current site is a staging site
 *
 * @since 1.2.0
 * @return boolean
 */
function cf_is_staging_site() {

	if ( cf_url_contains( array( 'staging.', '.flywheelsites.com', '.wpengine.com', '.kinsta.cloud' ) ) ) {
		return true;
	}

	if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'staging' ) {
		return true;
	}

	return false;

}


/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/svg/{group}/ directory.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods. For icons with
 * custom (non-square) sizes, set 'size' => false.
 *
 * Icons will be loaded once in the footer and referenced throughout document.
 *
 * @param array $atts Shortcode Attributes.
 * @credit Bill Erickson
 */
function cf_icon( $atts = array() ) {
    $atts = shortcode_atts(
        [
            'icon' => false,
            'group' => 'utility',
            'size' => 24,
            'width' => false,
            'height' => false,
            'class' => false,
            'label' => false,
            'defs' => false,
            'force' => false,
        ],
        $atts
    );

    if ( empty( $atts['icon'] ) ) {
        return;
    }

    if ( is_admin() ) {
        $atts['force'] = true;
    }

    $icon_path = get_theme_file_path( '/build/svg/' . $atts['group'] . '/' . $atts['icon'] . '.svg' );

    if ( 'images' === $atts['group'] ) {
        $icon_path = get_theme_file_path( '/build/images/' . $atts['icon'] . '.svg' );
        $atts['size'] = false;
    }

    if ( ! file_exists( $icon_path ) ) {
        return;
    }

    // Display the icon directly.
    if ( true === $atts['force'] ) {
        ob_start();
        readfile( $icon_path );
        $icon = ob_get_clean();
        
        // Preserve existing attributes and add accessibility attributes
        $svg = preg_replace( 
            '/^<svg([^>]*)/', 
            '<svg$1 aria-hidden="true" role="img" focusable="false"', 
            trim( $icon ) 
        );
        
        $svg = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
        $svg = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.
        
        if ( ! empty( $atts['class'] ) ) {
            // Add class to existing attributes
            $svg = preg_replace( '/^<svg([^>]*class="[^"]*")([^>]*)/', '<svg$1 ' . esc_attr( $atts['class'] ) . '"$2', $svg );
            // If no existing class attribute, add one
            if ( strpos( $svg, 'class=' ) === false ) {
                $svg = preg_replace( '/^<svg([^>]*)/', '<svg$1 class="' . esc_attr( $atts['class'] ) . '"', $svg );
            }
        }

    // Display the icon as symbol in defs.
    } elseif ( true === $atts['defs'] ) {
        ob_start();
        readfile( $icon_path );
        $icon = ob_get_clean();
        
        // Preserve viewBox and other attributes for symbols
        $svg = preg_replace( '/^<svg([^>]*)/', '<svg$1 id="' . $atts['group'] . '-' . $atts['icon'] . '"', trim( $icon ) );
        $svg = str_replace( '<svg', '<symbol', $svg );
        $svg = str_replace( '</svg>', '</symbol>', $svg );
        $svg = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
        $svg = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

    // Display reference to icon.
    } else {
        // Create global variable
        global $cf_icons;

        // Create an empty array for the group of icons
        if ( empty( $cf_icons[ $atts['group'] ] ) ) {
            $cf_icons[ $atts['group'] ] = [];
        }

        // Track the icons being used
        if ( empty( $cf_icons[ $atts['group'] ][ $atts['icon'] ] ) ) {
            $cf_icons[ $atts['group'] ][ $atts['icon'] ] = 1;
        } else {
            $cf_icons[ $atts['group'] ][ $atts['icon'] ]++;
        }

        // Extract viewBox from the original SVG file
        $viewbox = '';
        if ( file_exists( $icon_path ) ) {
            $svg_content = file_get_contents( $icon_path );
            if ( preg_match( '/viewBox="([^"]*)"/', $svg_content, $matches ) ) {
                $viewbox = ' viewBox="' . esc_attr( $matches[1] ) . '"';
            }
        }

        $attr = '';

        if ( ! empty( $atts['class'] ) ) {
            $attr .= ' class="' . esc_attr( $atts['class'] ) . '"';
        }

        // Add viewBox to the svg element
        $attr .= $viewbox;

        // Don't add width/height attributes - let CSS handle sizing

        if ( ! empty( $atts['label'] ) ) {
            $attr .= ' aria-label="' . esc_attr( $atts['label'] ) . '"';
        } else {
            $attr .= ' aria-hidden="true" role="img" focusable="false"';
        }

        // Output the svg with the use reference
        $svg = '<svg' . $attr . '><use href="#' . $atts['group'] . '-' . $atts['icon'] . '"></use></svg>';
    }

    return $svg;
}

/**
 * Icon Definitions
 */
add_action( 'wp_footer', 'cf_icon_definitions', 20 );
function cf_icon_definitions() {

	global $cf_icons;

	if ( empty( $cf_icons ) ) {
		return;
	}

	echo '<svg style="display:none;"><defs>';
	foreach ( $cf_icons as $group => $icons ) {
		foreach ( $icons as $icon => $count ) {
			echo cf_icon( [ 'icon' => $icon, 'group' => $group, 'defs' => true ] );
		}
	}
	echo '</defs></svg>';

}