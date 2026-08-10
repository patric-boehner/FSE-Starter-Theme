<?php
/**
 * Title: Call to action, centered heading
 * Slug: pb-starter/cta-centered
 * Description: A centered heading, paragraph and button on a plain background.
 * Categories: call-to-action
 * Keywords: cta, call to action, button, heading, centered
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twenty Twenty-Five theme (GPLv2 or later). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Call to action","categories":["call-to-action"],"patternName":"pb-starter/cta-centered"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:heading {"textAlign":"center","fontSize":"large"} -->
	<h2 class="wp-block-heading has-text-align-center has-large-font-size"><?php esc_html_e( 'This is a primary heading', 'pb-starter' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center"} -->
	<p class="has-text-align-center"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-primary"} -->
		<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Learn more', 'pb-starter' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
