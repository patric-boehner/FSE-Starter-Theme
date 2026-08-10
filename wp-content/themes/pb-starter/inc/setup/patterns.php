<?php
/**
 * Block pattern categories.
 *
 * @package pb-starter
 **/


/**
 * Register the pattern categories core does not provide.
 *
 * Core registers its own categories unconditionally, so removing support for
 * core block patterns in setup.php drops the patterns but keeps the categories.
 * Everything core already covers — banner, call-to-action, services,
 * testimonials, contact, team, about, text, columns, gallery, posts, header,
 * footer — is used directly rather than duplicated here.
 *
 * An empty category does not render in the inserter, so one stops appearing as
 * soon as no pattern uses it. Nothing needs unregistering.
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
	);

	foreach ( $categories as $name => $properties ) {
		register_block_pattern_category( $name, $properties );
	}

}
