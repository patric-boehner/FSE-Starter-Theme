<?php
/**
 * Content Slots - the slot registry
 *
 * A slot is just a string, and registering one is optional: any slug typed into
 * a cf/content-slot block resolves without being registered first. Registering
 * adds a label and per-slot options, and is required only for hook delivery,
 * which has no block to be discovered from.
 *
 * Modelled on core's register_sidebar() / $wp_registered_sidebars.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register a content slot.
 *
 * @param string $slug Stable key referenced in block markup and code.
 * @param array  $args {
 *     @type string $label       Admin-facing name. Defaults to a prettified slug.
 *     @type string $description Shown in the block inspector.
 *     @type bool   $multiple    Render every match instead of one winner.
 *     @type string $render_hook Hook to auto-render on. Empty = block placement only.
 *     @type string $hook_type   'filter' or 'action'. Empty auto-detects.
 *     @type int    $priority    Hook priority.
 *     @type string $tag         Wrapper element.
 *     @type string $class       Extra wrapper class.
 * }
 * @return string|false The registered slug, or false if the slug was unusable.
 */
function cf_register_content_slot( $slug, $args = array() ) {

	$slug = sanitize_key( $slug );

	if ( empty( $slug ) ) {
		return false;
	}

	if ( ! isset( $GLOBALS['cf_registered_content_slots'] ) ) {
		$GLOBALS['cf_registered_content_slots'] = array();
	}

	$GLOBALS['cf_registered_content_slots'][ $slug ] = cf_parse_slot_args( $slug, $args );

	return $slug;

}


/**
 * Fill in slot defaults.
 *
 * Separate so unregistered slugs can borrow the same defaults, which is what
 * makes registration optional rather than a gate.
 *
 * @param string $slug Slot slug.
 * @param array  $args Raw args.
 * @return array Complete slot config.
 */
function cf_parse_slot_args( $slug, $args = array() ) {

	$defaults = array(
		'label'       => cf_prettify_slot_slug( $slug ),
		'description' => '',
		'multiple'    => false,
		'render_hook' => '',
		'hook_type'   => '',
		'priority'    => 10,
		'tag'         => 'div',
		'class'       => '',
		'registered'  => false,
	);

	$args = wp_parse_args( $args, $defaults );

	$args['slug']     = $slug;
	$args['multiple'] = (bool) $args['multiple'];
	$args['priority'] = (int) $args['priority'];
	$args['tag']      = preg_replace( '/[^a-z0-9]/', '', strtolower( $args['tag'] ) );

	if ( empty( $args['tag'] ) ) {
		$args['tag'] = 'div';
	}

	return $args;

}


/**
 * Turn a slug into a readable label: middle-of-content -> Middle Of Content.
 *
 * @param string $slug Slot slug.
 * @return string
 */
function cf_prettify_slot_slug( $slug ) {

	return ucwords( str_replace( array( '-', '_' ), ' ', $slug ) );

}


/**
 * Get every registered slot, keyed by slug.
 *
 * @return array
 */
function cf_get_content_slots() {

	if ( ! isset( $GLOBALS['cf_registered_content_slots'] ) ) {
		$GLOBALS['cf_registered_content_slots'] = array();
	}

	return $GLOBALS['cf_registered_content_slots'];

}


/**
 * Get a registered slot, or null if it was never registered.
 *
 * @param string $slug Slot slug.
 * @return array|null
 */
function cf_get_content_slot( $slug ) {

	$slots = cf_get_content_slots();
	$slug  = sanitize_key( $slug );

	return isset( $slots[ $slug ] ) ? $slots[ $slug ] : null;

}


/**
 * Get a usable config for any slug, registered or not.
 *
 * What the renderer calls. Never returns null, so an unregistered slug renders
 * with defaults instead of being rejected.
 *
 * @param string $slug Slot slug.
 * @return array
 */
function cf_get_slot_config( $slug ) {

	$slot = cf_get_content_slot( $slug );

	if ( $slot ) {
		$slot['registered'] = true;
		return $slot;
	}

	return cf_parse_slot_args( sanitize_key( $slug ) );

}
