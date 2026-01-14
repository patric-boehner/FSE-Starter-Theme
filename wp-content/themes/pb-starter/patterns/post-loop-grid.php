<?php
/**
 * Title: Grid of posts
 * Slug: fse-starter/post-loop-grid
 * Block Types: core/query
 * Inserter: false
 */
?>
<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[],"format":[]},"align":"wide"} -->
<div class="wp-block-query alignwide">

	<!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->

		<!-- wp:pattern {"slug":"fse-starter/card-blog-post"} /-->
		 
	<!-- /wp:post-template -->

	<!-- wp:pattern {"slug":"fse-starter/post-loop-pagination"} /-->

</div>
<!-- /wp:query -->