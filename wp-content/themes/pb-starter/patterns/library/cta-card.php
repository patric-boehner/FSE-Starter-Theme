<?php
/**
 * Title: Call to action card
 * Slug: pb-starter/lib-cta-card
 * Description: A compact coloured card with a heading, short paragraph and buttons.
 * Categories: call-to-action
 * Keywords: cta, call to action, card, button, box, sidebar
 * Viewport Width: 600
 * Inserter: true
 *
 * A component pattern, not a section: drop it inside an existing column or sidebar.
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Call to action card","categories":["call-to-action"],"patternName":"pb-starter/lib-cta-card"},"backgroundColor":"primary","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-primary-background-color has-text-color has-background">

	<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"medium"} -->
	<h3 class="wp-block-heading has-text-align-center has-medium-font-size"><?php esc_html_e( 'Build with patterns', 'pb-starter' ); ?></h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
	<p class="has-text-align-center has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet.', 'pb-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-secondary"} -->
		<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Get started', 'pb-starter' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
