<?php
/**
 * Title: Card Blog Post
 * Slug: fse-starter/card-blog-post
 * Block Types: core/query
 * Inserter: false
 */
?>
<!-- wp:group {"tagName":"article","metadata":{"name":"Post Card"},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"space-between","justifyContent":"stretch"}} -->
<article class="wp-block-group">

	<!-- wp:group {"metadata":{"name":"Post Content"},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">

		<!-- wp:post-featured-image {"height":"300px"} /-->
		<!-- wp:post-title {"isLink":true} /-->
		<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}}} /-->
		<!-- wp:post-excerpt {"moreText":"Read More","showMoreOnNewLine":false} /-->

	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Post Meta"},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
	<div class="wp-block-group">

		<!-- wp:post-terms {"term":"category"} /-->

	</div>
	<!-- /wp:group -->

</article>
<!-- /wp:group -->