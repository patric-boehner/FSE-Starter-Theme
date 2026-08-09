<?php
/**
 * Title: Large statement with text boxes
 * Slug: pb-starter/lib-large-text
 * Description: A large left aligned statement above a responsive grid of short text cards.
 * Categories: about, pb-starter/features
 * Keywords: statement, intro, features, boxes, cards, about
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Large statement","categories":["about"],"patternName":"pb-starter/lib-large-text"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:group {"metadata":{"name":"Titles"},"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:paragraph {"textColor":"primary","fontSize":"small"} -->
		<p class="has-primary-color has-text-color has-small-font-size"><?php esc_html_e( 'Built for the future', 'pb-starter' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"fontSize":"large"} -->
		<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur viverra congue urna aliquam viverra.', 'pb-starter' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Grid"},"align":"wide","className":"is-style-large-gap","layout":{"type":"grid","minimumColumnWidth":"18rem"}} -->
	<div class="wp-block-group alignwide is-style-large-gap">

		<!-- wp:group {"metadata":{"name":"Text box"},"className":"is-style-card","style":{"dimensions":{"minHeight":"100%"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group is-style-card" style="min-height:100%">
			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Text box"},"className":"is-style-card","style":{"dimensions":{"minHeight":"100%"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group is-style-card" style="min-height:100%">
			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Text box"},"className":"is-style-card","style":{"dimensions":{"minHeight":"100%"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group is-style-card" style="min-height:100%">
			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
