<?php
/**
 * Content Slots - list table
 *
 * Answers "what is placed where, and when does it show?" at a glance.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Define the columns.
 *
 * @param array $columns Existing columns.
 * @return array
 */
add_filter( 'manage_content_area_posts_columns', 'cf_content_area_columns' );
function cf_content_area_columns( $columns ) {

	// Categories appear in the Conditions column instead.
	unset( $columns['taxonomy-category'], $columns['categories'] );

	$date = isset( $columns['date'] ) ? $columns['date'] : '';
	unset( $columns['date'] );

	$columns['cf_slot']       = __( 'Slot', 'core-functionality' );
	$columns['cf_conditions'] = __( 'Conditions', 'core-functionality' );
	$columns['cf_priority']   = __( 'Priority', 'core-functionality' );

	if ( $date ) {
		$columns['date'] = $date;
	}

	return $columns;

}


/**
 * Render a column.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
add_action( 'manage_content_area_posts_custom_column', 'cf_render_content_area_column', 10, 2 );
function cf_render_content_area_column( $column, $post_id ) {

	if ( 'cf_slot' === $column ) {
		echo wp_kses_post( cf_content_area_slot_cell( $post_id ) );
		return;
	}

	if ( 'cf_conditions' === $column ) {
		echo wp_kses_post( cf_content_area_conditions_cell( $post_id ) );
		return;
	}

	if ( 'cf_priority' === $column ) {
		echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
	}

}


/**
 * Slot cell. Unregistered slugs get a prettified label from cf_get_slot_config(),
 * so registered or not they read the same - only a missing slot is flagged.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function cf_content_area_slot_cell( $post_id ) {

	$slug = cf_get_content_area_slot( $post_id );

	if ( empty( $slug ) ) {
		return '<span style="color:#b32d2e;">' . esc_html__( 'Not placed', 'core-functionality' ) . '</span>';
	}

	return sprintf(
		'%s<br><code>%s</code>',
		esc_html( cf_get_slot_config( $slug )['label'] ),
		esc_html( $slug )
	);

}


/**
 * Human-readable conditions summary.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function cf_content_area_conditions_cell( $post_id ) {

	$parts = array();

	foreach ( cf_get_condition_taxonomies() as $taxonomy ) {

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}

		$parts[] = esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );

	}

	$post_types = get_post_meta( $post_id, '_cf_post_types', true );

	if ( ! empty( $post_types ) ) {
		$parts[] = esc_html( implode( ', ', (array) $post_types ) );
	}

	if ( empty( $parts ) ) {
		return '<em>' . esc_html__( 'Default (shows when nothing else matches)', 'core-functionality' ) . '</em>';
	}

	return implode( '<br>', $parts );

}


/**
 * Make Slot and Priority sortable.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
add_filter( 'manage_edit-content_area_sortable_columns', 'cf_content_area_sortable_columns' );
function cf_content_area_sortable_columns( $columns ) {

	$columns['cf_slot']     = 'cf_slot';
	$columns['cf_priority'] = 'menu_order';

	return $columns;

}


/**
 * A dropdown to filter the list by slot.
 *
 * @param string $post_type Current post type.
 * @return void
 */
add_action( 'restrict_manage_posts', 'cf_content_area_slot_filter' );
function cf_content_area_slot_filter( $post_type ) {

	if ( 'content_area' !== $post_type ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list filter.
	$current = isset( $_GET['cf_slot'] ) ? sanitize_key( wp_unslash( $_GET['cf_slot'] ) ) : '';

	echo '<select name="cf_slot">';
	printf(
		'<option value="">%s</option>',
		esc_html__( 'All slots', 'core-functionality' )
	);

	foreach ( cf_get_editor_slot_options() as $option ) {
		printf(
			'<option value="%s" %s>%s</option>',
			esc_attr( $option['value'] ),
			selected( $current, $option['value'], false ),
			esc_html( $option['label'] )
		);
	}

	echo '</select>';

}


/**
 * Apply the slot filter and sorting to the admin query.
 *
 * @param WP_Query $query Query object.
 * @return void
 */
add_action( 'pre_get_posts', 'cf_content_area_admin_query' );
function cf_content_area_admin_query( $query ) {

	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'content_area' !== $query->get( 'post_type' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only list filter.
	$slot = isset( $_GET['cf_slot'] ) ? sanitize_key( wp_unslash( $_GET['cf_slot'] ) ) : '';

	if ( $slot ) {
		$query->set( 'meta_key', '_cf_slot' );
		$query->set( 'meta_value', $slot );
	} elseif ( 'cf_slot' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_cf_slot' );
		$query->set( 'orderby', 'meta_value' );
	}

}
