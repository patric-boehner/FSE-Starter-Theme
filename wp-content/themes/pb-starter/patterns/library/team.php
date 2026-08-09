<?php
/**
 * Title: Team members
 * Slug: pb-starter/lib-team
 * Description: An introduction followed by a responsive grid of team members with photos and roles.
 * Categories: team, pb-starter/features
 * Keywords: team, people, staff, about, members
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Ollie theme (GPLv3). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Team","categories":["team"],"patternName":"pb-starter/lib-team"},"align":"full","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-tertiary-background-color has-background">

	<!-- wp:group {"metadata":{"name":"Titles"},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","textColor":"primary","fontSize":"small"} -->
		<p class="has-text-align-center has-primary-color has-text-color has-small-font-size"><?php esc_html_e( 'Meet our people', 'pb-starter' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center"} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'A small but mighty team', 'pb-starter' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit sed imperdiet.', 'pb-starter' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Members"},"align":"wide","className":"is-style-large-gap","layout":{"type":"grid","minimumColumnWidth":"16rem"}} -->
	<div class="wp-block-group alignwide is-style-large-gap">

		<!-- wp:group {"metadata":{"name":"Member"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of a team member', 'Alt text for team photo.', 'pb-starter' ); ?>" style="aspect-ratio:3/4;object-fit:cover"/></figure>
			<!-- /wp:image -->

			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-text-align-center has-medium-font-size"><?php esc_html_e( 'Tracy Capitan', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"contrast-light","fontSize":"small"} -->
			<p class="has-text-align-center has-contrast-light-color has-text-color has-small-font-size"><?php esc_html_e( 'Product Designer', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Member"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-2.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of a team member', 'Alt text for team photo.', 'pb-starter' ); ?>" style="aspect-ratio:3/4;object-fit:cover"/></figure>
			<!-- /wp:image -->

			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-text-align-center has-medium-font-size"><?php esc_html_e( 'Michael Glacier', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"contrast-light","fontSize":"small"} -->
			<p class="has-text-align-center has-contrast-light-color has-text-color has-small-font-size"><?php esc_html_e( 'Developer', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Member"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-3.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of a team member', 'Alt text for team photo.', 'pb-starter' ); ?>" style="aspect-ratio:3/4;object-fit:cover"/></figure>
			<!-- /wp:image -->

			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-text-align-center has-medium-font-size"><?php esc_html_e( 'Maryann Alpine', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"contrast-light","fontSize":"small"} -->
			<p class="has-text-align-center has-contrast-light-color has-text-color has-small-font-size"><?php esc_html_e( 'Strategist', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Member"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/avatar-4.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Portrait of a team member', 'Alt text for team photo.', 'pb-starter' ); ?>" style="aspect-ratio:3/4;object-fit:cover"/></figure>
			<!-- /wp:image -->

			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-text-align-center has-medium-font-size"><?php esc_html_e( 'Alex Meadow', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"contrast-light","fontSize":"small"} -->
			<p class="has-text-align-center has-contrast-light-color has-text-color has-small-font-size"><?php esc_html_e( 'Account Manager', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
