<?php
/**
 * Title: Logo row
 * Slug: pb-starter/lib-logos
 * Description: A heading above a centered row of client or partner logos.
 * Categories: banner
 * Keywords: logos, clients, partners, sponsors, brands
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twenty Twenty-Five theme (GPLv2 or later). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Logos","categories":["banner"],"patternName":"pb-starter/lib-logos"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-tertiary-background-color has-background">

	<!-- wp:paragraph {"align":"center","textColor":"contrast-light","fontSize":"small"} -->
	<p class="has-text-align-center has-contrast-light-color has-text-color has-small-font-size"><?php esc_html_e( 'Trusted by teams everywhere', 'pb-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"metadata":{"name":"Logos"},"align":"wide","className":"is-style-large-gap","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
	<div class="wp-block-group alignwide is-style-large-gap">

		<!-- wp:image {"aspectRatio":"4/3","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/logo-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Client logo', 'Alt text for logo image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:contain"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"4/3","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/logo-2.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Client logo', 'Alt text for logo image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:contain"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"4/3","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/logo-3.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Client logo', 'Alt text for logo image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:contain"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"4/3","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/logo-4.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Client logo', 'Alt text for logo image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:contain"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"4/3","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/logo-5.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Client logo', 'Alt text for logo image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:contain"/></figure>
		<!-- /wp:image -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
