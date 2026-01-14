<?php
/**
 * Title: Three Column Features
 * Slug: pb-starter/three-column-features
 * Description: A centered heading with description followed by three equal columns of text content
 * Categories:
 * Keywords: columns, features
 * Viewport Width: 1700
 * Block Types:
 * Post Types:
 * Inserter: true 
*/
?>
<!-- wp:group {"align":"full","layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull">
    
    <!-- wp:group {"metadata":{"name":"Titles"},"layout":{"type":"constrained","justifyContent":"center"}} -->
    <div class="wp-block-group">
        
        <!-- wp:heading {"textAlign":"center"} -->
        <h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'This is a primary heading', 'pb-starter' ); ?></h2>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum. Suspendisse potenti.', 'pb-starter' ); ?></p>
        <!-- /wp:paragraph -->
        
    </div>
    <!-- /wp:group -->
    
    <!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"is-style-large-gap"} -->
    <div class="wp-block-columns alignwide are-vertically-aligned-center is-style-large-gap">
        
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            
            <!-- wp:group {"metadata":{"name":"Entry"},"layout":{"type":"constrained","justifyContent":"center"}} -->
            <div class="wp-block-group">
                
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
                <!-- /wp:paragraph -->
                
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            
            <!-- wp:group {"metadata":{"name":"Entry"},"layout":{"type":"constrained","justifyContent":"center"}} -->
            <div class="wp-block-group">
                
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
                <!-- /wp:paragraph -->
                
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            
            <!-- wp:group {"metadata":{"name":"Entry"},"layout":{"type":"constrained","justifyContent":"center"}} -->
            <div class="wp-block-group">
                
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph -->
                <p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
                <!-- /wp:paragraph -->
                
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:column -->
        
    </div>
    <!-- /wp:columns -->
    
</div>
<!-- /wp:group -->