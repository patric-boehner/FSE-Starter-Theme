<?php
/**
 * Content Slots - the content index
 *
 * One query per request covering every slot, cached as a transient. A bespoke
 * site has tens of content areas, so loading them all with caches primed and
 * grouping in PHP beats a query per slot.
 *
 * Caches the index, not rendered output - output varies by URL and login state,
 * which makes for a fragile cache key, while resolving in memory is free.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Transient key for the content index.
 */
function cf_content_index_key() {

	return 'cf_content_index';

}


/**
 * Get every published content area, grouped by slot slug.
 *
 * @return array slug => list of item arrays.
 */
function cf_get_content_index() {

	static $index = null;

	if ( null !== $index ) {
		return $index;
	}

	$cached = get_transient( cf_content_index_key() );

	if ( is_array( $cached ) ) {
		$index = $cached;
		return $index;
	}

	$index = cf_build_content_index();

	set_transient( cf_content_index_key(), $index, DAY_IN_SECONDS );

	return $index;

}


/**
 * Query all published content areas and group them by slot.
 *
 * @return array
 */
function cf_build_content_index() {

	$query = new WP_Query(
		array(
			'post_type'              => 'content_area',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => true, // Primes the meta/term reads below,
			'update_post_term_cache' => true, // so cf_build_index_item() is query-free.
			'orderby'                => array(
				'menu_order' => 'DESC',
				'date'       => 'DESC',
			),
		)
	);

	$index = array();

	foreach ( $query->posts as $content_area ) {

		$slot = get_post_meta( $content_area->ID, '_cf_slot', true );
		$slot = sanitize_key( (string) $slot );

		if ( empty( $slot ) ) {
			continue;
		}

		$index[ $slot ][] = cf_build_index_item( $content_area );

	}

	return $index;

}


/**
 * Flatten one content area into the shape the resolver needs.
 *
 * @param WP_Post $content_area Content area post.
 * @return array
 */
function cf_build_index_item( $content_area ) {

	return array(
		'id'         => (int) $content_area->ID,
		'title'      => $content_area->post_title,
		'menu_order' => (int) $content_area->menu_order,
		'date'       => $content_area->post_date_gmt,
		'terms'      => cf_collect_item_terms( $content_area->ID ),
	);

}


/**
 * Collect a content area's targeting terms, keyed by taxonomy.
 *
 * Not expanded to ancestors - only the request context is. Targeting a parent
 * category should match its children, but not the other way round.
 *
 * @param int $post_id Content area ID.
 * @return array taxonomy => term IDs.
 */
function cf_collect_item_terms( $post_id ) {

	$collected = array();

	foreach ( cf_get_condition_taxonomies() as $taxonomy ) {

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}

		$collected[ $taxonomy ] = array_map( 'intval', wp_list_pluck( $terms, 'term_id' ) );

	}

	return $collected;

}


/**
 * Drop the cached index.
 *
 * @return void
 */
function cf_flush_content_index() {

	delete_transient( cf_content_index_key() );

}


/**
 * Flush when a content area is saved, trashed, restored or deleted.
 */
add_action( 'save_post_content_area', 'cf_flush_content_index' );
add_action( 'deleted_post', 'cf_flush_content_index_for_post' );
add_action( 'trashed_post', 'cf_flush_content_index_for_post' );
add_action( 'untrashed_post', 'cf_flush_content_index_for_post' );

/**
 * Flush only when the affected post is a content area.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function cf_flush_content_index_for_post( $post_id ) {

	if ( 'content_area' === get_post_type( $post_id ) ) {
		cf_flush_content_index();
	}

}


/**
 * Flush when a content area's terms change. Missing this is the classic
 * "I changed the category and nothing happened" support call.
 */
add_action( 'set_object_terms', 'cf_flush_content_index_for_terms', 10, 4 );

/**
 * @param int    $object_id Object ID.
 * @param array  $terms     Terms.
 * @param array  $tt_ids    Term taxonomy IDs.
 * @param string $taxonomy  Taxonomy.
 * @return void
 */
function cf_flush_content_index_for_terms( $object_id, $terms, $tt_ids, $taxonomy ) {

	if ( 'content_area' === get_post_type( $object_id ) ) {
		cf_flush_content_index();
	}

}


/**
 * Flush when slot or condition meta changes.
 */
add_action( 'added_post_meta', 'cf_flush_content_index_for_meta', 10, 3 );
add_action( 'updated_post_meta', 'cf_flush_content_index_for_meta', 10, 3 );
add_action( 'deleted_post_meta', 'cf_flush_content_index_for_meta', 10, 3 );

/**
 * @param int    $meta_id   Meta ID.
 * @param int    $object_id Object ID.
 * @param string $meta_key  Meta key.
 * @return void
 */
function cf_flush_content_index_for_meta( $meta_id, $object_id, $meta_key ) {

	if ( 0 !== strpos( (string) $meta_key, '_cf_' ) ) {
		return;
	}

	if ( 'content_area' === get_post_type( $object_id ) ) {
		cf_flush_content_index();
	}

}
