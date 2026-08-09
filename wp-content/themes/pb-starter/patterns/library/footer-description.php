<?php
/**
 * Title: Footer, site description and social icons
 * Slug: pb-starter/lib-footer-description
 * Description: A footer with the site title and a short description, social icons, and a copyright line.
 * Categories: footer
 * Keywords: footer, description, about, social, copyright
 * Block Types: core/template-part/footer
 * Post Types: wp_template_part
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twentig One theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Footer","categories":["footer"],"patternName":"pb-starter/lib-footer-description"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-tertiary-background-color has-background">

	<!-- wp:columns {"align":"wide","className":"is-style-large-gap"} -->
	<div class="wp-block-columns alignwide is-style-large-gap">

		<!-- wp:column {"width":"60%"} -->
		<div class="wp-block-column" style="flex-basis:60%">
			<!-- wp:site-title {"level":0} /-->

			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><?php esc_html_e( 'This may be a good place to introduce yourself and your site, or to include some credits.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:social-links {"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"right"}} -->
			<ul class="wp-block-social-links is-style-logos-only">
				<!-- wp:social-link {"url":"#","service":"instagram"} /-->
				<!-- wp:social-link {"url":"#","service":"linkedin"} /-->
			</ul>
			<!-- /wp:social-links -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"metadata":{"name":"Footer base"},"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:paragraph {"textColor":"contrast-light","fontSize":"x-small"} -->
		<p class="has-contrast-light-color has-text-color has-x-small-font-size">
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
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
