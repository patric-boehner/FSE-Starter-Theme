<?php
/**
 * Content Slots - hook delivery
 *
 * Lets a slot render at a WordPress hook with no block anywhere - the only way
 * to place things with no natural home in a template: a site-wide banner, a
 * popup, content appended after the post.
 *
 * Rendered on template_redirect and merely echoed on the hook, because
 * template-canvas.php builds the whole template BEFORE wp_head(). Calling
 * do_blocks() inside a wp_body_open callback would enqueue that content's block
 * styles after wp_head() had already fired.
 *
 * Note wp_body_open output lands outside .wp-site-blocks, so the theme's
 * contextual spacing does not reach it - style these slots explicitly.
 *
 * @package    CoreFunctionality
 * @subpackage Content Slots
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Render every hook-delivered slot and attach it to its hook.
 */
add_action( 'template_redirect', 'cf_prepare_hook_slots', 20 );
function cf_prepare_hook_slots() {

	foreach ( cf_get_content_slots() as $slug => $config ) {

		if ( empty( $config['render_hook'] ) ) {
			continue;
		}

		$html = cf_get_slot_html( $slug );

		if ( '' === trim( $html ) ) {
			continue;
		}

		cf_attach_slot_to_hook( $html, $config );

	}

}


/**
 * Attach pre-rendered slot HTML to its hook.
 *
 * @param string $html   Rendered slot HTML.
 * @param array  $config Slot config.
 * @return void
 */
function cf_attach_slot_to_hook( $html, $config ) {

	$hook     = $config['render_hook'];
	$priority = $config['priority'];

	if ( 'filter' === cf_slot_hook_type( $config ) ) {

		add_filter(
			$hook,
			function ( $content ) use ( $html ) {
				return cf_append_slot_to_content( $content, $html );
			},
			$priority
		);

		return;

	}

	add_action(
		$hook,
		function () use ( $html ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Pre-rendered block HTML.
			echo $html;
		},
		$priority
	);

}


/**
 * Is this slot delivered through a filter rather than an action?
 *
 * Auto-detected for the_content; hook_type is the escape hatch for anything else.
 *
 * @param array $config Slot config.
 * @return string 'filter' or 'action'.
 */
function cf_slot_hook_type( $config ) {

	if ( ! empty( $config['hook_type'] ) ) {
		return 'filter' === $config['hook_type'] ? 'filter' : 'action';
	}

	return 'the_content' === $config['render_hook'] ? 'filter' : 'action';

}


/**
 * Append slot HTML to the real post body only - without the guard this also
 * fires for excerpts and every other the_content call on the page.
 *
 * @param string $content Post content.
 * @param string $html    Slot HTML.
 * @return string
 */
function cf_append_slot_to_content( $content, $html ) {

	if ( ! is_singular() || ! is_main_query() || ! in_the_loop() ) {
		return $content;
	}

	return $content . $html;

}
