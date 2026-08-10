<?php
/**
 * Title: Services, three columns with images
 * Slug: pb-starter/services-3-col
 * Description: A heading followed by three columns of image, subheading and text.
 * Categories: columns
 * Keywords: services, features, columns, images
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twenty Twenty-Five theme (GPLv2 or later). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Services","categories":["columns"],"patternName":"pb-starter/services-3-col"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:heading {"align":"wide"} -->
	<h2 class="wp-block-heading alignwide"><?php esc_html_e( 'Our services', 'pb-starter' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"align":"wide","className":"is-style-loose"} -->
	<div class="wp-block-columns alignwide is-style-loose">

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for service image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:cover"/></figure>
			<!-- /wp:image -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Collect', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-2.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for service image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:cover"/></figure>
			<!-- /wp:image -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Assemble', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
			<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-3.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for service image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:cover"/></figure>
			<!-- /wp:image -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Deliver', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit.', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
