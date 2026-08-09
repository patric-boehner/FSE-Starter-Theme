<?php
/**
 * Content Slots - cache control
 *
 * The index invalidates itself on save, term and meta changes. This is the
 * escape hatch for anything those hooks miss.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add the flush link to the admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
 * @return void
 */
add_action( 'admin_bar_menu', 'cf_content_slot_admin_bar_node', 100 );
function cf_content_slot_admin_bar_node( $wp_admin_bar ) {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$wp_admin_bar->add_node(
		array(
			'id'     => 'cf-flush-content-slots',
			'parent' => 'top-secondary',
			'title'  => __( 'Clear slot cache', 'core-functionality' ),
			'href'   => wp_nonce_url(
				add_query_arg( 'cf_action', 'flush_slots' ),
				'cf_flush_slots'
			),
		)
	);

}


/**
 * Handle the flush request.
 *
 * @return void
 */
add_action( 'init', 'cf_maybe_flush_content_slots' );
function cf_maybe_flush_content_slots() {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified below.
	if ( empty( $_GET['cf_action'] ) || 'flush_slots' !== $_GET['cf_action'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'cf_flush_slots' ) ) {
		return;
	}

	cf_flush_content_index();

	wp_safe_redirect( remove_query_arg( array( 'cf_action', '_wpnonce' ) ) );
	exit;

}
