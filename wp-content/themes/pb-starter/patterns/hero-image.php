<?php
/**
 * Title: Hero, full width image
 * Slug: pb-starter/hero-image
 * Description: A full width background image with a heading, paragraph and button over it.
 * Categories: banner
 * Keywords: hero, banner, cover, image, heading, homepage
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twenty Twenty-Five theme (GPLv2 or later). See readme.txt.
 */
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-1.webp' ) ); ?>","dimRatio":50,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":70,"minHeightUnit":"vh","contentPosition":"center center","metadata":{"name":"Hero","categories":["banner"],"patternName":"pb-starter/hero-image"},"align":"full","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-base-color has-text-color" style="min-height:70vh">
	<span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim"></span>
	<img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-1.webp' ) ); ?>" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">

		<!-- wp:heading {"textAlign":"center","fontSize":"x-large"} -->
		<h2 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php esc_html_e( 'Tell your story', 'pb-starter' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet.', 'pb-starter' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-primary"} -->
			<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Learn more', 'pb-starter' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

	</div>
</div>
<!-- /wp:cover -->
