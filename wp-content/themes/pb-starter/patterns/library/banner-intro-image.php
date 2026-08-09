<?php
/**
 * Title: Banner, image left with heading and button
 * Slug: pb-starter/lib-banner-intro-image
 * Description: A two column banner with a square image on the left and a heading, paragraph and button on the right.
 * Categories: banner, columns
 * Keywords: banner, intro, image, heading, featured
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twenty Twenty-Five theme (GPLv2 or later). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Banner","categories":["banner"],"patternName":"pb-starter/lib-banner-intro-image"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:columns {"align":"wide","className":"is-style-large-gap"} -->
	<div class="wp-block-columns alignwide is-style-large-gap">

		<!-- wp:column {"width":"56%"} -->
		<div class="wp-block-column" style="flex-basis:56%">
			<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/square-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for banner image.', 'pb-starter' ); ?>" style="aspect-ratio:1;object-fit:cover"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php echo esc_html_x( 'New arrivals', 'Heading for banner pattern.', 'pb-starter' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-primary"} -->
				<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Learn more', 'pb-starter' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
