<?php
/**
 * Content Slots - rendering
 *
 * Two deliberate departures from the old code:
 *
 * 1. No global $post swap. Blocks in a content area resolve against the VIEWED
 *    post, which is what you want; render_block_context is the tool if not.
 *    (The old swap was also broken - see cf_render_content_item().)
 *
 * 2. do_blocks() rather than apply_filters('the_content'), which would invite
 *    every third-party content injector into the header and sidebar.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Render a slot's content, without any wrapper.
 *
 * @param string $slug Slot slug.
 * @return array { debug, inner } - both empty strings when nothing resolves.
 */
function cf_render_slot_parts( $slug ) {

	$items = cf_resolve_slot( $slug );
	$inner = '';

	foreach ( $items as $item ) {
		$inner .= cf_render_content_item( $item['id'], $slug );
	}

	return array(
		'debug' => cf_slot_debug_comment( $slug, $items, cf_get_request_context() ),
		'inner' => trim( $inner ),
	);

}


/**
 * Get a slot's HTML for hook delivery, wrapper included.
 *
 * Hook-delivered slots have no block, so get_block_wrapper_attributes() cannot
 * be used here - it reads WP_Block_Supports::$block_to_render, which only exists
 * during a block render. The block path uses core's builder instead; see
 * cf_get_slot_block_html().
 *
 * @param string $slug Slot slug.
 * @return string Empty string when nothing resolves.
 */
function cf_get_slot_html( $slug ) {

	$slug = sanitize_key( $slug );

	if ( empty( $slug ) ) {
		return '';
	}

	$parts = cf_render_slot_parts( $slug );

	if ( '' === $parts['inner'] ) {
		return $parts['debug'];
	}

	$config  = cf_get_slot_config( $slug );
	$classes = cf_slot_classes( $config );

	return $parts['debug'] . sprintf(
		'<%1$s class="%2$s">%3$s</%1$s>',
		tag_escape( $config['tag'] ),
		esc_attr( implode( ' ', $classes ) ),
		$parts['inner'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered block HTML.
	);

}


/**
 * Get a slot's HTML for the block, letting core build the wrapper.
 *
 * get_block_wrapper_attributes() handles anchor, custom class and every other
 * enabled block support, so turning on spacing or colour later needs no changes
 * here.
 *
 * @param string $slug Slot slug.
 * @return string Empty string when nothing resolves.
 */
function cf_get_slot_block_html( $slug ) {

	$slug = sanitize_key( $slug );

	if ( empty( $slug ) ) {
		return '';
	}

	$parts = cf_render_slot_parts( $slug );

	if ( '' === $parts['inner'] ) {
		return $parts['debug'];
	}

	$config = cf_get_slot_config( $slug );

	$attributes = get_block_wrapper_attributes(
		array( 'class' => implode( ' ', cf_slot_classes( $config ) ) )
	);

	return $parts['debug'] . sprintf(
		'<%1$s %2$s>%3$s</%1$s>',
		tag_escape( $config['tag'] ),
		$attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core.
		$parts['inner'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered block HTML.
	);

}


/**
 * Render one content area's block content.
 *
 * @param int    $post_id Content area ID.
 * @param string $slug    Slot slug, passed to the filter for context.
 * @return string
 */
function cf_render_content_item( $post_id, $slug ) {

	// A content area whose block points back at its own slot would recurse.
	static $rendering = array();

	if ( isset( $rendering[ $post_id ] ) ) {
		return '';
	}

	$rendering[ $post_id ] = true;

	$html = get_post_field( 'post_content', $post_id );
	$html = do_blocks( $html );
	$html = wptexturize( $html ); // Or slot copy gets straight quotes, post copy curly.
	$html = wp_filter_content_tags( $html, 'cf_content_slot' );
	$html = do_shortcode( $html );

	unset( $rendering[ $post_id ] );

	return apply_filters( 'cf_content_slot_content', $html, $post_id, $slug );

}


/**
 * The slot's own classes, shared by both wrappers.
 *
 * @param array $config Slot config.
 * @return array
 */
function cf_slot_classes( $config ) {

	$classes = array( 'cf-content-slot', 'cf-content-slot--' . $config['slug'] );

	if ( ! empty( $config['class'] ) ) {
		$classes = array_merge( $classes, explode( ' ', $config['class'] ) );
	}

	return array_unique( array_filter( array_map( 'sanitize_html_class', $classes ) ) );

}


/**
 * An HTML comment explaining what resolved and why. Emitted by the real
 * resolver on the real request, so unlike a simulated inspector it cannot lie.
 *
 * @param string $slug    Slot slug.
 * @param array  $items   Winning items.
 * @param array  $context Request context.
 * @return string
 */
function cf_slot_debug_comment( $slug, $items, $context ) {

	if ( ! cf_show_slot_debug() ) {
		return '';
	}

	$candidates = cf_count_slot_candidates( $slug );
	$parts      = array(
		'cf-slot: ' . $slug,
		$candidates . ' candidate' . ( 1 === $candidates ? '' : 's' ),
	);

	if ( empty( $items ) ) {
		$parts[] = 0 === $candidates
			? 'nothing assigned to this slot'
			: 'no conditions matched';
	}

	foreach ( $items as $item ) {
		$parts[] = sprintf(
			'chose #%d "%s" | %s | priority %d',
			$item['id'],
			$item['title'],
			cf_describe_item_match( $item, $context ),
			$item['menu_order']
		);
	}

	// Stripping "--" is the whole requirement inside a comment; esc_html() would
	// only turn every quote into &quot; and make titles harder to read.
	$comment = str_replace( array( '--', '>', '<' ), '', implode( ' | ', $parts ) );

	return "\n<!-- " . $comment . " -->\n";

}


/**
 * Off by default - opt in when you are actually debugging:
 *
 *   define( 'CF_DEBUG_SLOTS', true );   // wp-config.php
 *
 * @return bool
 */
function cf_show_slot_debug() {

	$show = defined( 'CF_DEBUG_SLOTS' ) && CF_DEBUG_SLOTS;

	return (bool) apply_filters( 'cf_show_slot_debug', $show );

}
