<?php
/**
 * Content Slots - authoring fields
 *
 * The one place this feature uses ACF. Deliberate: lose ACF and you lose the
 * editing UI, not the site, because the resolver reads plain get_post_meta().
 *
 * Two fields for one value because ACF selects cannot hold a value outside
 * their choices, which made new slots impossible to assign - a slug only became
 * an option once something already used it. So: a picker plus a Custom text box,
 * writing cf_slot_choice / cf_slot_custom, with _cf_slot derived on save.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * The picker value meaning "I am going to type a new one".
 *
 * @return string
 */
function cf_slot_custom_sentinel() {

	return '__custom__';

}


/**
 * Populate the slot picker.
 *
 * @param array $field ACF field.
 * @return array
 */
add_filter( 'acf/load_field/key=field_cf_content_area_slot', 'cf_load_slot_field_choices' );
function cf_load_slot_field_choices( $field ) {

	$field['choices'] = array();

	foreach ( cf_get_editor_slot_options() as $option ) {
		$field['choices'][ $option['value'] ] = $option['label'];
	}

	$field['choices'][ cf_slot_custom_sentinel() ] = __( 'Custom - create a new slot', 'core-functionality' );

	return $field;

}


/**
 * Populate the post type dropdown.
 *
 * @param array $field ACF field.
 * @return array
 */
add_filter( 'acf/load_field/key=field_cf_content_area_post_types', 'cf_load_post_type_field_choices' );
function cf_load_post_type_field_choices( $field ) {

	$field['choices'] = array();

	$post_types = get_post_types(
		array(
			'public'  => true,
			'show_ui' => true,
		),
		'objects'
	);

	foreach ( $post_types as $post_type ) {

		if ( in_array( $post_type->name, array( 'attachment', 'content_area' ), true ) ) {
			continue;
		}

		$field['choices'][ $post_type->name ] = $post_type->labels->singular_name;

	}

	return $field;

}


/**
 * Seed the picker from _cf_slot for areas saved before these fields existed.
 *
 * @param mixed $value   Stored value.
 * @param int   $post_id Post ID.
 * @return mixed
 */
add_filter( 'acf/load_value/key=field_cf_content_area_slot', 'cf_load_slot_choice_value', 10, 2 );
function cf_load_slot_choice_value( $value, $post_id ) {

	$slot = cf_get_content_area_slot( $post_id );

	// A slug typed into the Custom box becomes a real option once it is in use,
	// so show it as a normal selection instead of staying stuck on "Custom".
	if ( $slot && cf_slot_is_known( $slot ) ) {
		return $slot;
	}

	if ( ! empty( $value ) ) {
		return $value;
	}

	return $slot ? cf_slot_custom_sentinel() : $value;

}


/**
 * Same, for the custom text box.
 *
 * @param mixed $value   Stored value.
 * @param int   $post_id Post ID.
 * @return mixed
 */
add_filter( 'acf/load_value/key=field_cf_content_area_slot_custom', 'cf_load_slot_custom_value', 10, 2 );
function cf_load_slot_custom_value( $value, $post_id ) {

	if ( ! empty( $value ) ) {
		return $value;
	}

	$slot = cf_get_content_area_slot( $post_id );

	return ( $slot && ! cf_slot_is_known( $slot ) ) ? $slot : $value;

}


/**
 * Is this slug already offered by the picker?
 *
 * @param string $slug Slot slug.
 * @return bool
 */
function cf_slot_is_known( $slug ) {

	foreach ( cf_get_editor_slot_options() as $option ) {
		if ( $option['value'] === $slug ) {
			return true;
		}
	}

	return false;

}


/**
 * Derive _cf_slot from the picker and the custom box. Priority 20 so ACF has
 * finished writing its own values.
 *
 * @param int|string $post_id ACF post ID.
 * @return void
 */
add_action( 'acf/save_post', 'cf_save_content_area_slot', 20 );
function cf_save_content_area_slot( $post_id ) {

	if ( ! is_numeric( $post_id ) || 'content_area' !== get_post_type( $post_id ) ) {
		return;
	}

	// Editors cannot change placement, so never let a save from one blank it.
	if ( ! cf_can_manage_slot_placement() ) {
		return;
	}

	$choice = (string) get_post_meta( $post_id, 'cf_slot_choice', true );

	if ( cf_slot_custom_sentinel() === $choice ) {
		$choice = (string) get_post_meta( $post_id, 'cf_slot_custom', true );
	}

	update_post_meta( $post_id, '_cf_slot', sanitize_key( $choice ) );

}


/**
 * Hide placement fields from anyone who cannot manage placement. Returning false
 * removes the field rather than disabling it, so there is nothing to tamper
 * with; meta.php's auth_callback is the server-side half.
 *
 * @param array $field ACF field.
 * @return array|false
 */
add_filter( 'acf/prepare_field/key=field_cf_content_area_slot', 'cf_gate_slot_field' );
add_filter( 'acf/prepare_field/key=field_cf_content_area_slot_custom', 'cf_gate_slot_field' );
function cf_gate_slot_field( $field ) {

	return cf_can_manage_slot_placement() ? $field : false;

}
