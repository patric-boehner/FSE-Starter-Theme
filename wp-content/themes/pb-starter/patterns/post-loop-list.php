<?php
/**
 * Title: List of posts
 * Slug: fse-starter/post-loop-list
 * Block Types: core/query
 * Inserter: false
 */
?>
<!-- wp:query {"queryId":1,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[],"format":[]}} -->
<div class="wp-block-query">
	
	<!-- wp:post-template -->

		<!-- wp:group {"tagName":"article","layout":{"type":"constrained"}} -->
		<article class="wp-block-group">

			<!-- wp:post-title {"isLink":true} /-->
			<!-- wp:post-date /-->
			<!-- wp:post-excerpt {"excerptLength":20} /-->

		</article>
		<!-- /wp:group -->

	<!-- /wp:post-template -->

	<!-- wp:pattern {"slug":"fse-starter/post-loop-pagination"} /-->

</div>
<!-- /wp:query -->







