<?php
/**
 * Title: Testimonial card
 * Slug: pb-starter/testimonial-card
 * Description: A single testimonial card with a quote, avatar, name and role.
 * Categories: text
 * Keywords: testimonial, card, avatar, quote, review
 * Viewport Width: 600
 * Inserter: true
 *
 * A component pattern, not a section: drop it inside an existing column or grid.
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Testimonial card","categories":["text"],"patternName":"pb-starter/testimonial-card"},"className":"is-style-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card">

	<!-- wp:paragraph -->
	<p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum suspendisse potenti.', 'pb-starter' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"metadata":{"name":"Attribution"},"style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
	<div class="wp-block-group">

		<!-- wp:image {"width":"64px","aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
		<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of the person quoted', 'Alt text for testimonial avatar.', 'pb-starter' ); ?>" style="aspect-ratio:1;object-fit:cover;width:64px"/></figure>
		<!-- /wp:image -->

		<!-- wp:group {"metadata":{"name":"Name and role"},"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph -->
			<p><strong><?php esc_html_e( 'Alex Glacier', 'pb-starter' ); ?></strong></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"textColor":"contrast-light","fontSize":"small"} -->
			<p class="has-contrast-light-color has-text-color has-small-font-size"><?php esc_html_e( 'Director, Example Company', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
