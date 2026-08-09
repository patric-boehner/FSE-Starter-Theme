<?php
/**
 * Title: Testimonials, two columns with avatars
 * Slug: pb-starter/lib-testimonials-2-col
 * Description: Two testimonials side by side, each with a quote, avatar and attribution.
 * Categories: testimonials, columns
 * Keywords: testimonial, quote, review, avatar, columns
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twenty Twenty-Five theme (GPLv2 or later). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Testimonials","categories":["testimonials"],"patternName":"pb-starter/lib-testimonials-2-col"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:columns {"align":"wide","className":"is-style-large-gap"} -->
	<div class="wp-block-columns alignwide is-style-large-gap">

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"metadata":{"name":"Testimonial"},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
			<div class="wp-block-group">
				<!-- wp:image {"width":"64px","aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
				<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-2.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of the person quoted', 'Alt text for testimonial avatar.', 'pb-starter' ); ?>" style="aspect-ratio:1;object-fit:cover;width:64px"/></figure>
				<!-- /wp:image -->

				<!-- wp:quote {"fontSize":"medium"} -->
				<blockquote class="wp-block-quote has-medium-font-size">
					<!-- wp:paragraph -->
					<p><?php echo esc_html_x( '“Superb product and customer service.”', 'Sample testimonial.', 'pb-starter' ); ?></p>
					<!-- /wp:paragraph -->
					<cite><?php echo wp_kses_post( _x( 'Jo Mulligan <br /><sub>Atlanta, GA</sub>', 'Sample testimonial citation.', 'pb-starter' ) ); ?></cite>
				</blockquote>
				<!-- /wp:quote -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"metadata":{"name":"Testimonial"},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
			<div class="wp-block-group">
				<!-- wp:image {"width":"64px","aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
				<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-3.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of the person quoted', 'Alt text for testimonial avatar.', 'pb-starter' ); ?>" style="aspect-ratio:1;object-fit:cover;width:64px"/></figure>
				<!-- /wp:image -->

				<!-- wp:quote {"fontSize":"medium"} -->
				<blockquote class="wp-block-quote has-medium-font-size">
					<!-- wp:paragraph -->
					<p><?php echo esc_html_x( '“Amazing quality and care. I love all your products.”', 'Sample testimonial.', 'pb-starter' ); ?></p>
					<!-- /wp:paragraph -->
					<cite><?php echo wp_kses_post( _x( 'Otto Reid <br /><sub>Springfield, IL</sub>', 'Sample testimonial citation.', 'pb-starter' ) ); ?></cite>
				</blockquote>
				<!-- /wp:quote -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
