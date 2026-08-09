<?php
/**
 * Title: Statistics over an image
 * Slug: pb-starter/lib-stats
 * Description: Three key figures over a tinted background image.
 * Categories: pb-starter/features, banner
 * Keywords: numbers, statistics, figures, metrics, features
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-3.webp' ) ); ?>","dimRatio":90,"overlayColor":"primary","isUserOverlayColor":true,"metadata":{"name":"Statistics","categories":["pb-starter/features"],"patternName":"pb-starter/lib-stats"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull">
	<span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-90 has-background-dim"></span>
	<img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-3.webp' ) ); ?>" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">

		<!-- wp:columns {"align":"wide","className":"is-style-large-gap","textColor":"base"} -->
		<div class="wp-block-columns alignwide is-style-large-gap has-base-color has-text-color">

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"x-large"} -->
				<h3 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php esc_html_e( '100%', 'pb-starter' ); ?></h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"align":"center"} -->
				<p class="has-text-align-center"><strong><?php esc_html_e( 'Performance', 'pb-starter' ); ?></strong></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
				<p class="has-text-align-center has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna aliquam viverra consectetur.', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"x-large"} -->
				<h3 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php esc_html_e( '24/7', 'pb-starter' ); ?></h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"align":"center"} -->
				<p class="has-text-align-center"><strong><?php esc_html_e( 'Support', 'pb-starter' ); ?></strong></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
				<p class="has-text-align-center has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna aliquam viverra consectetur.', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"x-large"} -->
				<h3 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php esc_html_e( '10+', 'pb-starter' ); ?></h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"align":"center"} -->
				<p class="has-text-align-center"><strong><?php esc_html_e( 'Years experience', 'pb-starter' ); ?></strong></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
				<p class="has-text-align-center has-small-font-size"><?php esc_html_e( 'Curabitur viverra congue urna aliquam viverra consectetur.', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</div>
</div>
<!-- /wp:cover -->
