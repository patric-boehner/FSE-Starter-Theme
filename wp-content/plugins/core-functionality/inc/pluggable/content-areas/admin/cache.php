<?php
/**
 * Content Slots - cache control
 *
 * The index invalidates itself on save, term and meta changes. This is the
 * escape hatch for anything those hooks miss, and it lives on the Content Areas
 * screen because that is where you would be standing when you noticed.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Are we on the Content Areas list screen?
 *
 * @return bool
 */
function cf_is_content_area_list_screen() {

	$screen = get_current_screen();

	return $screen && 'edit-content_area' === $screen->id;

}


/**
 * Add the button to the list table nav.
 *
 * manage_posts_extra_tablenav fires after core closes its own actions div, so
 * this brings its own wrapper to sit on the same line.
 *
 * @param string $which 'top' or 'bottom'.
 * @return void
 */
add_action( 'manage_posts_extra_tablenav', 'cf_render_flush_slots_button' );
function cf_render_flush_slots_button( $which ) {

	if ( 'top' !== $which || ! cf_is_content_area_list_screen() ) {
		return;
	}

	if ( ! cf_can_manage_slot_placement() ) {
		return;
	}

	printf(
		'<div class="alignleft actions"><a href="%s" class="button">%s</a></div>',
		esc_url(
			wp_nonce_url( add_query_arg( 'cf_action', 'flush_slots' ), 'cf_flush_slots' )
		),
		esc_html__( 'Clear slot cache', 'core-functionality' )
	);

}


/**
 * Handle the flush request.
 *
 * @return void
 */
add_action( 'admin_init', 'cf_maybe_flush_content_slots' );
function cf_maybe_flush_content_slots() {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified below.
	if ( empty( $_GET['cf_action'] ) || 'flush_slots' !== $_GET['cf_action'] ) {
		return;
	}

	if ( ! cf_can_manage_slot_placement() ) {
		return;
	}

	$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'cf_flush_slots' ) ) {
		return;
	}

	cf_flush_content_index();

	wp_safe_redirect(
		add_query_arg( 'cf_flushed', 1, remove_query_arg( array( 'cf_action', '_wpnonce' ) ) )
	);
	exit;

}


/**
 * Confirm it happened. Without this the button looks like it did nothing,
 * which is a poor result for a button whose whole job is reassurance.
 *
 * @return void
 */
add_action( 'admin_notices', 'cf_flushed_slots_notice' );
function cf_flushed_slots_notice() {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Display only.
	if ( empty( $_GET['cf_flushed'] ) || ! cf_is_content_area_list_screen() ) {
		return;
	}

	printf(
		'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
		esc_html__( 'Content slot cache cleared.', 'core-functionality' )
	);

}
