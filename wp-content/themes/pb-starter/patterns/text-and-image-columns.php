<?php
/**
 * Title: Text and Image Columns
 * Slug: fse-starter/text-and-image-columns-with-icons
 * Description: A two column layout with text and an image.
 * Categories:
 * Keywords: columns, text
 * Viewport Width: 1700
 * Block Types:
 * Post Types:
 * Inserter: true
 */
?>

<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">
    
    <!-- wp:columns {"align":"wide","className":"is-style-large-gap is-style-columns-reverse"} -->
    <div class="wp-block-columns alignwide is-style-large-gap is-style-columns-reverse">
        
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            
            <!-- wp:heading -->
            <h2 class="wp-block-heading">This is a primary heading</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum. Suspendisse potenti.</p>
            <!-- /wp:paragraph -->
            
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            
            <!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"color":{"duotone":"var:preset|duotone|secondary-and-white"}}} -->
            <figure class="wp-block-image size-large">
                <img src="http://fse-starter-theme.local/wp-content/uploads/2025/03/pexels-sevenstormphotography-381739-1200x900.jpg" alt="" style="aspect-ratio:1;object-fit:cover"/>
            </figure>
            <!-- /wp:image -->
            
        </div>
        <!-- /wp:column -->
        
    </div>
    <!-- /wp:columns -->
    
</div>
<!-- /wp:group -->