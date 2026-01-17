<?php
/**
 * Title: Entry Share Links
 * Slug: fse-starter/hidden-entry-share
 * Inserter: false
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Social Share"},"layout":{"type":"constrained"}} -->
<section class="wp-block-group">

    <!-- wp:heading -->
    <h2 class="wp-block-heading">
        <?php echo esc_html( 'Share this Post', 'pb-starter' ); ?>
    </h2>
    <!-- /wp:heading -->

    <!-- wp:outermost/social-sharing {"layout":{"type":"flex","justifyContent":"left"}} -->
    <ul class="wp-block-outermost-social-sharing">

        <!-- wp:outermost/social-sharing-link {"service":"facebook"} /-->
        <!-- wp:outermost/social-sharing-link {"service":"linkedin"} /-->
        <!-- wp:outermost/social-sharing-link {"service":"bluesky"} /-->
        <!-- wp:outermost/social-sharing-link {"service":"mail"} /-->

    </ul>
    <!-- /wp:outermost/social-sharing -->

</section>
<!-- /wp:group -->