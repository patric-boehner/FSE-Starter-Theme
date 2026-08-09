<?php
/**
 * Title: Footer, three column navigation
 * Slug: pb-starter/lib-footer-3-col
 * Description: A footer with the site title, three columns of links, social icons and a copyright line.
 * Categories: footer
 * Keywords: footer, navigation, columns, links, sitemap
 * Block Types: core/template-part/footer
 * Post Types: wp_template_part
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twentig One theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Footer","categories":["footer"],"patternName":"pb-starter/lib-footer-3-col"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-tertiary-background-color has-background">

	<!-- wp:columns {"align":"wide","className":"is-style-large-gap"} -->
	<div class="wp-block-columns alignwide is-style-large-gap">

		<!-- wp:column {"width":"33.33%"} -->
		<div class="wp-block-column" style="flex-basis:33.33%">
			<!-- wp:site-title {"level":0} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"66.66%"} -->
		<div class="wp-block-column" style="flex-basis:66.66%">
			<!-- wp:columns -->
			<div class="wp-block-columns">

				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:navigation {"overlayMenu":"never","fontSize":"small","layout":{"type":"flex","orientation":"vertical"},"ariaLabel":"<?php esc_attr_e( 'About', 'pb-starter' ); ?>"} -->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Mission', 'pb-starter' ); ?>","url":"#"} /-->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Press &amp; media', 'pb-starter' ); ?>","url":"#"} /-->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Testimonials', 'pb-starter' ); ?>","url":"#"} /-->
					<!-- /wp:navigation -->
				</div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:navigation {"overlayMenu":"never","fontSize":"small","layout":{"type":"flex","orientation":"vertical"},"ariaLabel":"<?php esc_attr_e( 'Support', 'pb-starter' ); ?>"} -->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Help', 'pb-starter' ); ?>","url":"#"} /-->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'FAQs', 'pb-starter' ); ?>","url":"#"} /-->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Newsletter', 'pb-starter' ); ?>","url":"#"} /-->
					<!-- /wp:navigation -->
				</div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:navigation {"overlayMenu":"never","fontSize":"small","layout":{"type":"flex","orientation":"vertical"},"ariaLabel":"<?php esc_attr_e( 'Legal', 'pb-starter' ); ?>"} -->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Privacy', 'pb-starter' ); ?>","url":"#"} /-->
						<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Terms of use', 'pb-starter' ); ?>","url":"#"} /-->
					<!-- /wp:navigation -->
				</div>
				<!-- /wp:column -->

			</div>
			<!-- /wp:columns -->
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
