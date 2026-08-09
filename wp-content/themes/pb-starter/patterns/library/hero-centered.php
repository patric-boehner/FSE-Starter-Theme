<?php
/**
 * Title: Hero, centered text above a wide image
 * Slug: pb-starter/lib-hero-centered
 * Description: A centered eyebrow, heading, paragraph and buttons above a wide image.
 * Categories: banner, call-to-action
 * Keywords: hero, banner, homepage, heading, image, screenshot
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Hero","categories":["banner"],"patternName":"pb-starter/lib-hero-centered"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:group {"metadata":{"name":"Titles"},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","textColor":"primary","fontSize":"small"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-small-font-size"><?php esc_html_e( 'This is an eyebrow', 'pb-starter' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"x-large"} -->
		<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php esc_html_e( 'Build your site with clicks, not code', 'pb-starter' ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","textColor":"contrast-light"} -->
		<p class="has-text-align-center has-contrast-light-color has-text-color"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-primary"} -->
			<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Get started', 'pb-starter' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"is-style-secondary"} -->
			<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Learn more', 'pb-starter' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:image {"align":"wide","aspectRatio":"16/9","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
	<figure class="wp-block-image alignwide size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-2.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for hero image.', 'pb-starter' ); ?>" style="aspect-ratio:16/9;object-fit:cover"/></figure>
	<!-- /wp:image -->

</div>
<!-- /wp:group -->
