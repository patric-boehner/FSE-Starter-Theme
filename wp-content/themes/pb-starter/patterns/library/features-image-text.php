<?php
/**
 * Title: Text and image columns
 * Slug: pb-starter/lib-features-image-text
 * Description: A heading, button and two short feature notes beside a tall image.
 * Categories: pb-starter/features, columns
 * Keywords: features, text, image, columns, about
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Text and image","categories":["pb-starter/features"],"patternName":"pb-starter/lib-features-image-text"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-tertiary-background-color has-background">

	<!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"is-style-large-gap"} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center is-style-large-gap">

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">

			<!-- wp:group {"metadata":{"name":"Titles"},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"textColor":"primary","fontSize":"small"} -->
				<p class="has-primary-color has-text-color has-small-font-size"><?php esc_html_e( 'Pick your pattern', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:heading -->
				<h2 class="wp-block-heading"><?php esc_html_e( 'Beautiful design just got easier', 'pb-starter' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-primary"} -->
				<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'View all patterns', 'pb-starter' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

			<!-- wp:separator {"backgroundColor":"border-light"} -->
			<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background"/>
			<!-- /wp:separator -->

			<!-- wp:columns {"metadata":{"name":"Notes"}} -->
			<div class="wp-block-columns">
				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:heading {"level":3,"fontSize":"base"} -->
					<h3 class="wp-block-heading has-base-font-size"><?php esc_html_e( 'Responsive by default', 'pb-starter' ); ?></h3>
					<!-- /wp:heading -->

					<!-- wp:paragraph {"fontSize":"x-small"} -->
					<p class="has-x-small-font-size"><?php esc_html_e( 'Every pattern is designed with mobile and tablet in mind, scaling down cleanly.', 'pb-starter' ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:heading {"level":3,"fontSize":"base"} -->
					<h3 class="wp-block-heading has-base-font-size"><?php esc_html_e( 'Built on core blocks', 'pb-starter' ); ?></h3>
					<!-- /wp:heading -->

					<!-- wp:paragraph {"fontSize":"x-small"} -->
					<p class="has-x-small-font-size"><?php esc_html_e( 'No page builder and no plugin lock in. Everything is standard WordPress.', 'pb-starter' ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/portrait-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for section image.', 'pb-starter' ); ?>" style="aspect-ratio:3/4;object-fit:cover"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
