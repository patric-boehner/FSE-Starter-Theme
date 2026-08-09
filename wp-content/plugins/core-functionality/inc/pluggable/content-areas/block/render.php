<?php
/**
 * Content Slot block - server render.
 *
 * Thin on purpose: a block is only one way a slot gets delivered, so the work
 * lives in the shared renderer.
 *
 * @package CoreFunctionality
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block inner content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$cf_slug      = isset( $attributes['slot'] ) ? sanitize_key( $attributes['slot'] ) : '';
$cf_in_editor = defined( 'REST_REQUEST' ) && REST_REQUEST;

// Unconfigured block: show a placeholder in the editor, nothing on the front end.
if ( empty( $cf_slug ) ) {

	if ( $cf_in_editor ) {
		printf(
			'<div class="components-placeholder"><div class="components-placeholder__label">%s</div><div class="components-placeholder__instructions">%s</div></div>',
			esc_html__( 'Content Slot', 'core-functionality' ),
			esc_html__( 'Choose a slot in the block settings sidebar.', 'core-functionality' )
		);
	}

	return;
}

$cf_items = cf_resolve_slot( $cf_slug );

// An empty slot is legitimate on the front end, but looks broken in the editor.
if ( empty( $cf_items ) ) {

	if ( $cf_in_editor ) {
		$cf_candidates = cf_count_slot_candidates( $cf_slug );

		printf(
			'<div class="components-placeholder"><div class="components-placeholder__label">%s</div><div class="components-placeholder__instructions">%s</div></div>',
			esc_html( sprintf( /* translators: %s: slot name */ __( 'Content Slot: %s', 'core-functionality' ), $cf_slug ) ),
			esc_html(
				$cf_candidates
					? __( 'No Content Area matches this page yet. Publish one with no conditions to act as the default.', 'core-functionality' )
					: __( 'No Content Area is assigned to this slot yet.', 'core-functionality' )
			)
		);
	}

	return;
}

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered block HTML; wrapper attributes are escaped in cf_wrap_slot_html().
echo cf_get_slot_html(
	$cf_slug,
	array(
		'id'    => isset( $attributes['anchor'] ) ? $attributes['anchor'] : '',
		'class' => isset( $attributes['className'] ) ? $attributes['className'] : '',
	)
);
