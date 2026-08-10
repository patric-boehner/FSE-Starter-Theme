<?php
/**
 * Title: Lead magnet card
 * Slug: pb-starter/cta-newsletter
 * Description: A card with an image beside a heading, short paragraph and button, for a download or signup offer.
 * Categories: call-to-action
 * Keywords: cta, newsletter, signup, download, lead magnet, subscribe
 * Viewport Width: 800
 * Inserter: true
 *
 * A component pattern, not a section: drop it inside post content or a column.
 * Pair the button with a Gravity Forms page rather than embedding a form here.
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Lead magnet","categories":["call-to-action"],"patternName":"pb-starter/cta-newsletter"},"backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-tertiary-background-color has-background">

	<!-- wp:columns {"verticalAlignment":"center","className":"is-style-loose"} -->
	<div class="wp-block-columns are-vertically-aligned-center is-style-loose">

		<!-- wp:column {"verticalAlignment":"center","width":"33.33%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:33.33%">
			<!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-3.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for lead magnet image.', 'pb-starter' ); ?>" style="aspect-ratio:3/4;object-fit:cover"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"66.66%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66.66%">
			<!-- wp:paragraph {"textColor":"primary","fontSize":"small"} -->
			<p class="has-primary-color has-text-color has-small-font-size"><?php esc_html_e( 'Free download', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'This is a lead magnet heading', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-primary"} -->
				<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Download now', 'pb-starter' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
