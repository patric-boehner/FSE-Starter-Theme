<?php
/**
 * Title: Footer, copyright and social icons
 * Slug: pb-starter/lib-footer-simple
 * Description: A single row footer with a copyright notice and social icons.
 * Categories: footer
 * Keywords: footer, copyright, social, simple
 * Block Types: core/template-part/footer
 * Post Types: wp_template_part
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twentig One theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Footer","categories":["footer"],"patternName":"pb-starter/lib-footer-simple"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-tertiary-background-color has-background">

	<!-- wp:group {"metadata":{"name":"Footer row"},"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:paragraph {"fontSize":"small"} -->
		<p class="has-small-font-size">
			<?php
			printf(
				/* translators: 1: Current year, 2: Site name. */
				esc_html__( '© %1$s %2$s. All rights reserved.', 'pb-starter' ),
				esc_html( gmdate( 'Y' ) ),
				esc_html( get_bloginfo( 'name' ) )
			);
			?>
		</p>
		<!-- /wp:paragraph -->

		<!-- wp:social-links {"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"right"}} -->
		<ul class="wp-block-social-links is-style-logos-only">
			<!-- wp:social-link {"url":"#","service":"instagram"} /-->
			<!-- wp:social-link {"url":"#","service":"linkedin"} /-->
		</ul>
		<!-- /wp:social-links -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
