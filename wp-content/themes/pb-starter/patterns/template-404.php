<?php
/**
 * Title: 404 error content.
 * Slug: fse-starter/template-404
 * Inserter: no
 */
?>
<!-- wp:pattern {"slug":"fse-starter/404-header"} /-->

<!-- wp:group {"metadata":{"name":"Content"},"className":"entry-content","layout":{"type":"constrained"}} -->
<div class="wp-block-group entry-content">

    <!-- wp:paragraph -->
    <p><?php echo esc_html__( 'Oops, the page you are looking for does not exist or is no longer available. Try the search form to find your way.', 'pb-starter' ); ?></p>
    <!-- /wp:paragraph -->
    <!-- wp:search {"showLabel":false,"widthUnit":"%","buttonText":"Search"} /-->

</div>
<!-- /wp:group -->