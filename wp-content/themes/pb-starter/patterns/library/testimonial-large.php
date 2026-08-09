<?php
/**
 * Title: Testimonials with large heading
 * Slug: pb-starter/lib-testimonial-large
 * Description: A large statement and rating on the left beside two testimonial cards on the right.
 * Categories: testimonials, columns
 * Keywords: testimonial, review, rating, quote, heading, avatar
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Testimonials","categories":["testimonials"],"patternName":"pb-starter/lib-testimonial-large"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-tertiary-background-color has-background">

	<!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"is-style-large-gap"} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center is-style-large-gap">

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:paragraph {"textColor":"primary","fontSize":"small"} -->
			<p class="has-primary-color has-text-color has-small-font-size"><?php esc_html_e( 'Testimonials', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit curabitur.', 'pb-starter' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:separator {"backgroundColor":"border-light"} -->
			<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background"/>
			<!-- /wp:separator -->

			<!-- wp:group {"metadata":{"name":"Rating"},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
				<p><strong><?php esc_html_e( '“Working with this team has been a delight from start to finish.”', 'pb-starter' ); ?></strong></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"contrast-light","fontSize":"x-small"} -->
				<p class="has-contrast-light-color has-text-color has-x-small-font-size"><?php esc_html_e( 'Rated 5 out of 5 by 1,620 reviewers', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">

			<!-- wp:group {"metadata":{"name":"Testimonial"},"className":"is-style-card","layout":{"type":"constrained"}} -->
			<div class="wp-block-group is-style-card">
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"metadata":{"name":"Attribution"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-group">
					<!-- wp:image {"width":"64px","aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
					<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-4.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of the person quoted', 'Alt text for testimonial avatar.', 'pb-starter' ); ?>" style="aspect-ratio:1;object-fit:cover;width:64px"/></figure>
					<!-- /wp:image -->

					<!-- wp:paragraph -->
					<p><strong><?php esc_html_e( 'Alex Glacier', 'pb-starter' ); ?></strong></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"metadata":{"name":"Testimonial"},"className":"is-style-card","layout":{"type":"constrained"}} -->
			<div class="wp-block-group is-style-card">
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Sed imperdiet cursus bibendum suspendisse potenti. Aliquam viverra consectetur diam sed suscipit.', 'pb-starter' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"metadata":{"name":"Attribution"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-group">
					<!-- wp:image {"width":"64px","aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
					<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-2.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of the person quoted', 'Alt text for testimonial avatar.', 'pb-starter' ); ?>" style="aspect-ratio:1;object-fit:cover;width:64px"/></figure>
					<!-- /wp:image -->

					<!-- wp:paragraph -->
					<p><strong><?php esc_html_e( 'Maryann Alpine', 'pb-starter' ); ?></strong></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
