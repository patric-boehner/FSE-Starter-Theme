<?php
/**
 * Content Slots - meta registration
 *
 * Placement is a plain string in meta, not a taxonomy term. The old term-based
 * approach meant templates referenced numeric term IDs that only mean anything
 * on one install.
 *
 * _cf_slot is admin-only: editors write CTA copy, admins decide where it goes.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register content area meta. show_in_rest is false throughout - ACF sends its
 * own payload, and nothing else needs these over REST.
 */
add_action( 'init', 'cf_register_content_slot_meta' );
function cf_register_content_slot_meta() {

	register_post_meta(
		'content_area',
		'_cf_slot',
		array(
			'type'              => 'string',
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => false,
			'sanitize_callback' => 'sanitize_key',
			'auth_callback'     => 'cf_can_manage_slot_placement',
		)
	);

}


/**
 * Who may change where a content area appears.
 *
 * @return bool
 */
function cf_can_manage_slot_placement() {

	return current_user_can( 'manage_options' );

}



/**
 * Read a content area's slot.
 *
 * @param int $post_id Content area ID.
 * @return string
 */
function cf_get_content_area_slot( $post_id ) {

	return sanitize_key( (string) get_post_meta( $post_id, '_cf_slot', true ) );

}
