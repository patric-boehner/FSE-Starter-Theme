<?php
/**
 * Title: Contact details and locations
 * Slug: pb-starter/lib-contact-info
 * Description: A heading followed by a responsive grid of contact details and office locations.
 * Categories: contact
 * Keywords: contact, address, location, email, phone, hours
 * Viewport Width: 1400
 * Inserter: true
 *
 * Adapted from the Twenty Twenty-Five theme (GPLv2 or later). See readme.txt.
 */
?>
<!-- wp:group {"metadata":{"name":"Contact","categories":["contact"],"patternName":"pb-starter/lib-contact-info"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:heading {"align":"wide","fontSize":"large"} -->
	<h2 class="wp-block-heading alignwide has-large-font-size"><?php esc_html_e( 'How to get in touch with us', 'pb-starter' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:group {"metadata":{"name":"Details"},"align":"wide","className":"is-style-large-gap","layout":{"type":"grid","minimumColumnWidth":"16rem"}} -->
	<div class="wp-block-group alignwide is-style-large-gap">

		<!-- wp:group {"metadata":{"name":"Contact"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Get in touch', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><a href="#"><?php esc_html_e( 'example@example.com', 'pb-starter' ); ?></a><br /><?php esc_html_e( '(555) 555-5555', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Location"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Main office', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( '123 Example Street', 'pb-starter' ); ?><br /><?php esc_html_e( 'Springfield, IL 62701', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Location"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Second office', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( '456 Example Avenue', 'pb-starter' ); ?><br /><?php esc_html_e( 'Atlanta, GA 30301', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Hours"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size"><?php esc_html_e( 'Opening hours', 'pb-starter' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Monday to Friday, 9am to 5pm', 'pb-starter' ); ?><br /><?php esc_html_e( 'Closed weekends and holidays', 'pb-starter' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
