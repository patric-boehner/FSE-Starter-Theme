<?php
/**
 * Title: 404 error content.
 * Slug: fse-starter/template-404
 * Inserter: false
 */
?>
<!-- wp:group {"tagName":"header","metadata":{"name":"Archive Header"},"className":"archive-header","layout":{"type":"constrained"}} -->
<header class="wp-block-group archive-header">
    <!-- wp:heading {"level":1} -->
    <h1><?php echo esc_html__( 'Not found, error 404', 'pb-starter' ); ?></h1>
    <!-- /wp:heading -->
</header>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Content"},"className":"entry-content","layout":{"type":"constrained"}} -->
<div class="wp-block-group entry-content">

    <!-- wp:paragraph -->
    <p><?php echo esc_html__( 'Oops, the page you are looking for does not exist or is no longer available. Try the search form to find your way.', 'pb-starter' ); ?></p>
    <!-- /wp:paragraph -->
    <!-- wp:search {"showLabel":false,"widthUnit":"%","buttonText":"Search"} /-->

</div>
<!-- /wp:group -->