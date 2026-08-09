<?php
/**
 * Content Slots - request context
 *
 * Answers "what page are we on?" once per request, from the queried object.
 *
 * Never uses get_the_ID(). On a term archive that returns the first post of the
 * archive, not the term - core sets $GLOBALS['post'] before any loop runs - so
 * the old code picked its CTA from whichever post happened to sort first.
 *
 * Term matching includes ancestors, on singular and archive alike, so a child
 * category inherits a content area targeted at its parent.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Which taxonomies participate in conditional matching.
 *
 * Filterable so a client site with a CPT and its own taxonomy works without
 * touching this plugin.
 *
 * @return array Taxonomy names.
 */
function cf_get_condition_taxonomies() {

	return (array) apply_filters( 'cf_condition_taxonomies', array( 'category' ) );

}


/**
 * Get the request context, built once and memoized.
 *
 * @return array
 */
function cf_get_request_context() {

	static $context = null;

	if ( null !== $context ) {
		return $context;
	}

	$built = cf_build_request_context();

	// Only memoize once the main query exists; earlier answers are not reusable.
	if ( did_action( 'wp' ) ) {
		$context = $built;
	}

	return $built;

}


/**
 * Build the request context from the queried object.
 *
 * @return array
 */
function cf_build_request_context() {

	$context = array(
		'type'      => 'other',
		'post_id'   => 0,
		'post_type' => '',
		'terms'     => array(),
	);

	// REST preview has no front-end query, so use the post being edited.
	$preview_id = cf_get_preview_post_id();

	if ( $preview_id ) {
		return cf_build_singular_context( $preview_id, 'singular' );
	}

	$queried = get_queried_object();

	if ( is_singular() && $queried instanceof WP_Post ) {
		return cf_build_singular_context( $queried->ID, 'singular' );
	}

	if ( is_tax() || is_category() || is_tag() ) {
		if ( $queried instanceof WP_Term ) {
			$context['type']                        = 'archive';
			$context['terms'][ $queried->taxonomy ] = cf_term_ids_with_ancestors(
				array( $queried->term_id ),
				$queried->taxonomy
			);
		}
		return apply_filters( 'cf_request_context', $context );
	}

	if ( is_post_type_archive() && $queried instanceof WP_Post_Type ) {
		$context['type']      = 'archive';
		$context['post_type'] = $queried->name;
		return apply_filters( 'cf_request_context', $context );
	}

	$context['type'] = cf_get_context_type();

	return apply_filters( 'cf_request_context', $context );

}


/**
 * Build context for a single post.
 *
 * @param int    $post_id Post ID.
 * @param string $type    Context type label.
 * @return array
 */
function cf_build_singular_context( $post_id, $type = 'singular' ) {

	$context = array(
		'type'      => $type,
		'post_id'   => (int) $post_id,
		'post_type' => (string) get_post_type( $post_id ),
		'terms'     => array(),
	);

	foreach ( cf_get_condition_taxonomies() as $taxonomy ) {

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}

		$context['terms'][ $taxonomy ] = cf_term_ids_with_ancestors(
			wp_list_pluck( $terms, 'term_id' ),
			$taxonomy
		);

	}

	return apply_filters( 'cf_request_context', $context );

}


/**
 * Expand term IDs to include their ancestors.
 *
 * @param array  $term_ids Term IDs.
 * @param string $taxonomy Taxonomy name.
 * @return array Unique term IDs.
 */
function cf_term_ids_with_ancestors( $term_ids, $taxonomy ) {

	$term_ids = array_map( 'intval', (array) $term_ids );

	if ( ! is_taxonomy_hierarchical( $taxonomy ) ) {
		return $term_ids;
	}

	$all = $term_ids;

	foreach ( $term_ids as $term_id ) {
		$all = array_merge( $all, get_ancestors( $term_id, $taxonomy, 'taxonomy' ) );
	}

	return array_values( array_unique( array_map( 'intval', $all ) ) );

}


/**
 * Coarse label for the current request, exposed to the cf_item_matches filter.
 *
 * @return string
 */
function cf_get_context_type() {

	if ( is_front_page() ) {
		return 'front';
	}

	if ( is_home() ) {
		return 'home';
	}

	if ( is_search() ) {
		return 'search';
	}

	if ( is_404() ) {
		return '404';
	}

	if ( is_archive() ) {
		return 'archive';
	}

	return 'other';

}


/**
 * The post being edited, during a REST preview. Passed by the block through
 * ServerSideRender's urlQueryArgs so the preview resolves like a real page.
 *
 * @return int Post ID, or 0 when not a preview request.
 */
function cf_get_preview_post_id() {

	if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
		return 0;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only preview context; REST handles its own auth.
	if ( empty( $_GET['post_id'] ) ) {
		return 0;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only preview context; REST handles its own auth.
	return absint( $_GET['post_id'] );

}
