<?php
/**
 * Content slot declarations.
 *
 * A slot is a named place a Content Area can appear. Slots do not have to be
 * declared here - typing a new name into a Content Slot block just works.
 * Declaring one adds a label and per-slot options, and is required only for
 * hook-delivered slots, which have no block to be found from.
 *
 * @package pb-starter
 */


add_action( 'cf_register_content_slots', 'fse_register_content_slots' );

/**
 * Declare this theme's slots.
 *
 * @return void
 */
function fse_register_content_slots() {

	// Do not fatal if Core Functionality is deactivated.
	if ( ! function_exists( 'cf_register_content_slot' ) ) {
		return;
	}

	cf_register_content_slot(
		'header',
		array(
			'label'       => __( 'Header', 'pb-starter' ),
			'description' => __( 'Above the site header. Announcements and notification bars.', 'pb-starter' ),
		)
	);

	cf_register_content_slot(
		'footer',
		array(
			'label'       => __( 'Footer', 'pb-starter' ),
			'description' => __( 'Inside the site footer.', 'pb-starter' ),
		)
	);

	cf_register_content_slot(
		'after-post',
		array(
			'label'       => __( 'After Post', 'pb-starter' ),
			'description' => __( 'Below post content. Changes with the post category.', 'pb-starter' ),
		)
	);

	cf_register_content_slot(
		'main-menu',
		array(
			'label'       => __( 'Main Menu', 'pb-starter' ),
			'description' => __( 'Inside the primary navigation.', 'pb-starter' ),
		)
	);

}
