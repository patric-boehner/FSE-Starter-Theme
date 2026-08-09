<?php
/**
 * Block pattern categories and the opt-in section pattern library.
 *
 * @package pb-starter
 **/


/**
 * Whether the bundled section pattern library should be registered.
 *
 * Custom builds leave this off and author their own patterns in /patterns.
 * Pattern-driven builds define FSE_PATTERN_LIBRARY as true in wp-config.php,
 * or hook the filter below.
 *
 * @return bool
 */
function fse_pattern_library_enabled() {

	$enabled = defined( 'FSE_PATTERN_LIBRARY' ) && FSE_PATTERN_LIBRARY;

	return (bool) apply_filters( 'fse_pattern_library_enabled', $enabled );

}


/**
 * Keep library patterns out of core's theme pattern scan when the library is off.
 *
 * Core scans /patterns recursively, so a subdirectory hides nothing on its own.
 * Filtering the file list is cheaper than registering every pattern and then
 * unregistering it, and it runs before any pattern header is parsed.
 *
 * Array keys are paths relative to /patterns, e.g. "library/hero-centered.php".
 *
 * @param array  $files   Pattern files, keyed by relative path.
 * @param string $dirpath Absolute path to the patterns directory.
 * @return array
 */
add_filter( 'theme_block_pattern_files', 'fse_filter_pattern_library_files', 10, 2 );
function fse_filter_pattern_library_files( $files, $dirpath ) {

	if ( fse_pattern_library_enabled() ) {
		return $files;
	}

	foreach ( $files as $relative_path => $absolute_path ) {
		if ( 0 === strpos( $relative_path, 'library/' ) ) {
			unset( $files[ $relative_path ] );
		}
	}

	return $files;

}


/**
 * Register the pattern categories core does not provide.
 *
 * Core registers its own categories unconditionally, so removing support for
 * core block patterns in setup.php drops the patterns but keeps the categories.
 * Everything core already covers — banner, call-to-action, services,
 * testimonials, contact, team, about, text, columns, gallery, posts, header,
 * footer — is used directly rather than duplicated here.
 *
 * Priority 9 so the categories exist before patterns register at priority 10.
 *
 * @return void
 */
add_action( 'init', 'fse_register_pattern_categories', 9 );
function fse_register_pattern_categories() {

	$categories = array(
		'pb-starter/features' => array(
			'label'       => __( 'Features', 'pb-starter' ),
			'description' => __( 'Feature grids, benefit lists, and statistics.', 'pb-starter' ),
		),
		'pb-starter/pricing'  => array(
			'label'       => __( 'Pricing', 'pb-starter' ),
			'description' => __( 'Pricing tables and plan comparisons.', 'pb-starter' ),
		),
	);

	foreach ( $categories as $name => $properties ) {
		register_block_pattern_category( $name, $properties );
	}

}
