<?php
/**
 * Title: List of Related Posts
 * Slug: fse-starter/hidden-related-post-loop
 * Inserter: false
 */
?>
<!-- wp:group {"metadata":{"name":"Related Posts"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:query {"queryId":1,"query":{"order":"desc","orderBy":"date","postType":"posts","perPage":3,"offset":0,"inherit":false},"namespace":"related-posts","align":"wide"} -->
	<div class="wp-block-query alignwide">

		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Related Posts', 'pb-starter' ) ?></h2>
		<!-- /wp:heading -->

		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->

			 <!-- wp:pattern {"slug":"fse-starter/hidden-card-related-post"} /-->
	 
		<!-- /wp:post-template -->

	</div>
	<!-- /wp:query -->

</div>
<!-- /wp:group -->