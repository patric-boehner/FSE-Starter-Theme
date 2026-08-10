<?php
/**
 * Title: Call to action over an image
 * Slug: pb-starter/cta-text
 * Description: A centered heading, paragraph and button over a tinted background image.
 * Categories: call-to-action, banner
 * Keywords: cta, call to action, button, heading, banner
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-2.webp' ) ); ?>","dimRatio":90,"overlayColor":"primary","isUserOverlayColor":true,"metadata":{"name":"Call to action","categories":["call-to-action"],"patternName":"pb-starter/cta-text"},"align":"full","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-base-color has-text-color">
	<span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-90 has-background-dim"></span>
	<img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-2.webp' ) ); ?>" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">

		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'This is a primary heading', 'pb-starter' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-secondary"} -->
			<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Get started today', 'pb-starter' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

	</div>
</div>
<!-- /wp:cover -->
