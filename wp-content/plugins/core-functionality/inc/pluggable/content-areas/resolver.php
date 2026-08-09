<?php
/**
 * Content Slots - resolution
 *
 * Which content area wins a slot, in one sentence:
 *
 *   A content area with conditions beats one without. If several match, higher
 *   Priority wins. Ties go to the most recently published.
 *
 * Specificity before priority is deliberate - it means "why is the generic one
 * showing?" always answers itself with "nothing specific matched", so you only
 * look at numbers when two specific items actually collide.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Resolve a slot to its winning content area(s).
 *
 * @param string     $slug    Slot slug.
 * @param array|null $context Resolve against this context instead of the
 *                            current request. Used by the REST route.
 * @return array List of index items. Empty when nothing matches.
 */
function cf_resolve_slot( $slug, $context = null ) {

	$slug  = sanitize_key( $slug );
	$index = cf_get_content_index();

	if ( empty( $index[ $slug ] ) ) {
		return array();
	}

	$config  = cf_get_slot_config( $slug );
	$context = null === $context ? cf_get_request_context() : $context;
	$matched = array();

	foreach ( $index[ $slug ] as $item ) {

		if ( ! cf_item_matches( $item, $context ) ) {
			continue;
		}

		$item['specific'] = cf_item_is_specific( $item ) ? 1 : 0;
		$matched[]        = $item;

	}

	if ( empty( $matched ) ) {
		return array();
	}

	usort( $matched, 'cf_compare_content_items' );

	return $config['multiple'] ? $matched : array( $matched[0] );

}


/**
 * How many content areas are assigned to this slot, before conditions.
 *
 * Lets the editor and debug output tell a typo'd slug apart from one whose
 * items all failed their conditions.
 *
 * @param string $slug Slot slug.
 * @return int
 */
function cf_count_slot_candidates( $slug ) {

	$index = cf_get_content_index();
	$slug  = sanitize_key( $slug );

	return isset( $index[ $slug ] ) ? count( $index[ $slug ] ) : 0;

}


/**
 * Does this item carry any conditions at all?
 *
 * @param array $item Index item.
 * @return bool
 */
function cf_item_is_specific( $item ) {

	return ! empty( $item['terms'] ) || ! empty( $item['post_types'] );

}


/**
 * Does this item match the current request?
 *
 * @param array $item    Index item.
 * @param array $context Request context.
 * @return bool
 */
function cf_item_matches( $item, $context ) {

	$matches = cf_item_matches_conditions( $item, $context );

	// Project-specific conditions hook in here, e.g. members-only areas.
	return (bool) apply_filters( 'cf_item_matches', $matches, $item, $context );

}


/**
 * Evaluate the built-in conditions. AND across types, OR within a type.
 *
 * @param array $item    Index item.
 * @param array $context Request context.
 * @return bool
 */
function cf_item_matches_conditions( $item, $context ) {

	if ( ! empty( $item['post_types'] ) ) {

		if ( empty( $context['post_type'] ) ) {
			return false;
		}

		if ( ! in_array( $context['post_type'], (array) $item['post_types'], true ) ) {
			return false;
		}
	}

	if ( ! empty( $item['terms'] ) ) {
		return cf_item_matches_terms( $item['terms'], $context );
	}

	return true;

}


/**
 * Any overlap between the item's target terms and the request's terms.
 *
 * @param array $item_terms taxonomy => term IDs.
 * @param array $context    Request context.
 * @return bool
 */
function cf_item_matches_terms( $item_terms, $context ) {

	foreach ( $item_terms as $taxonomy => $term_ids ) {

		$context_terms = isset( $context['terms'][ $taxonomy ] )
			? $context['terms'][ $taxonomy ]
			: array();

		if ( ! empty( array_intersect( (array) $term_ids, $context_terms ) ) ) {
			return true;
		}
	}

	return false;

}


/**
 * Sort comparator: specificity, then Priority, then newest.
 *
 * @param array $a Index item.
 * @param array $b Index item.
 * @return int
 */
function cf_compare_content_items( $a, $b ) {

	if ( $a['specific'] !== $b['specific'] ) {
		return $b['specific'] - $a['specific'];
	}

	if ( $a['menu_order'] !== $b['menu_order'] ) {
		return $b['menu_order'] - $a['menu_order'];
	}

	return strcmp( (string) $b['date'], (string) $a['date'] );

}


/**
 * Plain-language reason this item won, for the debug comment.
 *
 * @param array $item    Index item.
 * @param array $context Request context.
 * @return string
 */
function cf_describe_item_match( $item, $context ) {

	if ( ! cf_item_is_specific( $item ) ) {
		return 'default (no conditions)';
	}

	$reasons = array();

	foreach ( (array) $item['terms'] as $taxonomy => $term_ids ) {

		$context_terms = isset( $context['terms'][ $taxonomy ] )
			? $context['terms'][ $taxonomy ]
			: array();

		foreach ( array_intersect( (array) $term_ids, $context_terms ) as $term_id ) {

			$term = get_term( $term_id, $taxonomy );

			if ( $term && ! is_wp_error( $term ) ) {
				$reasons[] = $taxonomy . '=' . $term->slug;
			}
		}
	}

	if ( ! empty( $item['post_types'] ) ) {
		$reasons[] = 'post_type=' . implode( '|', (array) $item['post_types'] );
	}

	return $reasons ? implode( ' ', $reasons ) : 'conditions met';

}
