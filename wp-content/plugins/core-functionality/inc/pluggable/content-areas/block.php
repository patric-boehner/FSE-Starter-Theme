<?php
/**
 * Content Slots - block registration
 *
 * A plain server-rendered block, not an ACF block. The old one only registered
 * when ACF Pro was active, so a lapsed licence would silently empty the header,
 * footer and single template. ACF still drives the Content Area edit screen,
 * where the failure mode is only losing the editing UI.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register the block at priority 5, before the theme's block style loader runs
 * at 10 - it skips blocks that are not yet registered.
 */
add_action( 'init', 'cf_register_content_slot_block', 5 );
function cf_register_content_slot_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type( CORE_DIR . 'inc/pluggable/content-areas/block' );

}


/**
 * Hand the editor the list of slots to suggest.
 */
add_action( 'enqueue_block_editor_assets', 'cf_enqueue_content_slot_editor_data' );
function cf_enqueue_content_slot_editor_data() {

	if ( ! function_exists( 'generate_block_asset_handle' ) ) {
		return;
	}

	$handle = generate_block_asset_handle( 'cf/content-slot', 'editorScript' );

	wp_add_inline_script(
		$handle,
		'window.cfContentSlots = ' . wp_json_encode( cf_get_editor_slot_options() ) . ';',
		'before'
	);

}


/**
 * Slot suggestions: registered slots, plus any slug already in use. Unregistered
 * slugs fall back to a prettified label, so they read the same as the rest.
 *
 * @return array List of { value, label } pairs.
 */
function cf_get_editor_slot_options() {

	$options = array();
	$slugs   = array_merge(
		array_keys( cf_get_content_slots() ),
		array_keys( cf_get_content_index() )
	);

	foreach ( array_unique( $slugs ) as $slug ) {
		$options[ $slug ] = array(
			'value' => $slug,
			'label' => cf_get_slot_config( $slug )['label'],
		);
	}

	ksort( $options );

	return array_values( $options );

}
