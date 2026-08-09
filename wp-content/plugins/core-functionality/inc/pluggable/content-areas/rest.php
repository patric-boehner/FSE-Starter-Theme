<?php
/**
 * Content Slots - REST
 *
 * Tells the editor which Content Area a slot currently resolves to, so the
 * block can offer an "Edit Content Area" button the way template parts offer
 * "Edit Original".
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register the route.
 */
add_action( 'rest_api_init', 'cf_register_slot_rest_route' );
function cf_register_slot_rest_route() {

	register_rest_route(
		'cf/v1',
		'/slot/(?P<slot>[a-zA-Z0-9_-]+)',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'cf_rest_resolve_slot',
			'permission_callback' => 'cf_rest_can_read_slots',
			'args'                => array(
				'slot'    => array(
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_key',
				),
				'post_id' => array(
					'type'              => 'integer',
					'sanitize_callback' => 'absint',
				),
			),
		)
	);

}


/**
 * Only people who can edit content areas need this.
 *
 * @return bool
 */
function cf_rest_can_read_slots() {

	$post_type = get_post_type_object( 'content_area' );

	return $post_type && current_user_can( $post_type->cap->edit_posts );

}


/**
 * Resolve a slot for the given post context.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function cf_rest_resolve_slot( $request ) {

	$slug    = sanitize_key( $request['slot'] );
	$post_id = absint( $request->get_param( 'post_id' ) );

	// Resolve against the post being edited when there is one. A template has no
	// post context, so it falls back to whatever matches with no conditions.
	$context = $post_id
		? cf_build_singular_context( $post_id )
		: cf_build_request_context();

	$items = cf_resolve_slot( $slug, $context );

	if ( empty( $items ) ) {
		return rest_ensure_response(
			array(
				'id'    => 0,
				'count' => cf_count_slot_candidates( $slug ),
			)
		);
	}

	$item = $items[0];

	return rest_ensure_response(
		array(
			'id'        => $item['id'],
			'title'     => $item['title'],
			'editLink'  => current_user_can( 'edit_post', $item['id'] )
				? get_edit_post_link( $item['id'], 'raw' )
				: '',
			'count'     => cf_count_slot_candidates( $slug ),
		)
	);

}
