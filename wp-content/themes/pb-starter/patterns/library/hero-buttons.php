<?php
/**
 * Title: Hero, centered text with buttons
 * Slug: pb-starter/lib-hero-buttons
 * Description: A centered hero with an eyebrow, heading, short paragraph and two buttons.
 * Categories: banner, call-to-action
 * Keywords: hero, banner, cta, call to action, buttons, heading, homepage
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Hero","categories":["banner"],"patternName":"pb-starter/lib-hero-buttons"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:paragraph {"align":"center","textColor":"primary","fontSize":"small"} -->
	<p class="has-text-align-center has-primary-color has-text-color has-small-font-size"><?php esc_html_e( 'This is an eyebrow', 'pb-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"textAlign":"center","fontSize":"x-large"} -->
	<h2 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php esc_html_e( 'This is a primary heading', 'pb-starter' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"contrast-light"} -->
	<p class="has-text-align-center has-contrast-light-color has-text-color"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit.', 'pb-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-primary"} -->
		<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Primary action', 'pb-starter' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-secondary"} -->
		<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Secondary action', 'pb-starter' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
