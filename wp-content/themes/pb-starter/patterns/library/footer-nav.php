<?php
/**
 * Title: Footer, copyright and inline navigation
 * Slug: pb-starter/lib-footer-nav
 * Description: A single row footer with a copyright notice on the left and inline links on the right.
 * Categories: footer
 * Keywords: footer, navigation, links, copyright, minimal
 * Block Types: core/template-part/footer
 * Post Types: wp_template_part
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twentig One theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Footer","categories":["footer"],"patternName":"pb-starter/lib-footer-nav"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
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

		<!-- wp:navigation {"overlayMenu":"never","fontSize":"small","ariaLabel":"<?php esc_attr_e( 'Footer', 'pb-starter' ); ?>"} -->
			<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Newsletter', 'pb-starter' ); ?>","url":"#"} /-->
			<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Privacy', 'pb-starter' ); ?>","url":"#"} /-->
			<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Terms of use', 'pb-starter' ); ?>","url":"#"} /-->
		<!-- /wp:navigation -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
