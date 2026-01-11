<?php
/**
 * Title: Home Header
 * Slug: fse-starter/home-header
 * Inserter: false
 * 
 * In the future, this could be replaced with a dynamic title and description using block bindings
 */
?>
<!-- wp:group {"tagName":"header","metadata":{"name":"Entry Header"},"align":"wide","className":"entry-header","layout":{"type":"default"}} -->
<header class="wp-block-group alignwide entry-header">
    
    <!-- wp:heading {"level":1} -->
    <h1 class="wp-block-heading"><?php echo esc_html__( 'Archive', 'pb-starter' ); ?></h1>
    <!-- /wp:heading -->

</header>
<!-- /wp:group -->