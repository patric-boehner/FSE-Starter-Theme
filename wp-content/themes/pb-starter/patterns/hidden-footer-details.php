<?php
/**
 * Title: Footer details
 * Slug: pb-starter/hidden-footer-details
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: false
 */
?>
<!-- wp:group {"metadata":{"name":"Details"},"align":"full","className":"footer-details","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull footer-details">

    <!-- wp:columns {"align":"wide","style":{"elements":{"link":{"color":[]}}}} -->
    <div class="wp-block-columns alignwide has-link-color">

        <!-- wp:column {"width":"55%"} -->
        <div class="wp-block-column" style="flex-basis:55%">

            <!-- wp:heading {"anchor":"our-company"} -->
            <h2 class="wp-block-heading" id="our-company"><?php esc_html_e( 'Our Company', 'pb-starter' ); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p><?php esc_html_e( 'With its clean, minimal design and powerful feature set, this theme enables agencies to build stylish and sophisticated WordPress websites.', 'pb-starter' ); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:social-links {"className":"is-style-logos-only","layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <ul class="wp-block-social-links is-style-logos-only">
                <!-- wp:social-link {"url":"#","service":"linkedin"} /-->

                <!-- wp:social-link {"url":"#","service":"youtube"} /-->

                <!-- wp:social-link {"url":"#","service":"feed"} /-->
            </ul>
            <!-- /wp:social-links -->

        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"15%"} -->
        <div class="wp-block-column" style="flex-basis:15%">

            <!-- wp:heading {"level":3,"anchor":"about-us"} -->
            <h3 class="wp-block-heading" id="about-us"><?php esc_html_e( 'About Us', 'pb-starter' ); ?></h3>
            <!-- /wp:heading -->

            <!-- wp:list {"className":"is-style-no-bullets"} -->
            <ul class="wp-block-list is-style-no-bullets">
                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Start Here', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Our Mission', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Brand Guide', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Newsletter', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Accessibility', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->
            </ul>
            <!-- /wp:list -->

        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"15%"} -->
        <div class="wp-block-column" style="flex-basis:15%">

            <!-- wp:heading {"level":3,"anchor":"services"} -->
            <h3 class="wp-block-heading" id="services"><?php esc_html_e( 'Services', 'pb-starter' ); ?></h3>
            <!-- /wp:heading -->

            <!-- wp:list -->
            <ul class="wp-block-list">
                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Web Design', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Development', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Copywriting', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Marketing', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e( 'Social Media', 'pb-starter' ); ?></a></li>
                <!-- /wp:list-item -->
            </ul>
            <!-- /wp:list -->

        </div>
        <!-- /wp:column -->

    </div>
    <!-- /wp:columns -->

</div>
<!-- /wp:group -->
