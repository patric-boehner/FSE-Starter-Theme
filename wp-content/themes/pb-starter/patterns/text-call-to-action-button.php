<?php
/**
 * Title: Text Call To Action Button
 * Slug: fse-starter/text-call-to-action-button
 * Description: A call to action with text and a button.
 * Categories:
 * Keywords: cta, call to action, buttons, heading
 * Viewport Width: 1700
 * Block Types:
 * Post Types:
 * Inserter: true
 */
?>

<!-- wp:group {"metadata":{"name":"Text Call To Action"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">
    
    <!-- wp:group {"metadata":{"name":"Titles"},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group">
        
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center"><?php esc_html_e( 'Eyebrow Text', 'pb-starter' ); ?></p>
        <!-- /wp:paragraph -->
        
        <!-- wp:heading {"textAlign":"center"} -->
        <h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'This is a call-to-action heading', 'pb-starter' ); ?></h2>
        <!-- /wp:heading -->
        
    </div>
    <!-- /wp:group -->
    
    <!-- wp:group {"metadata":{"name":"Text and Button"},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group">
        
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum. Suspendisse potenti', 'pb-starter' ); ?></p>
        <!-- /wp:paragraph -->
        
        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
        <div class="wp-block-buttons">
            <!-- wp:button -->
            <div class="wp-block-button">
                <a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Button Text', 'pb-starter' ); ?></a>
            </div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
        
    </div>
    <!-- /wp:group -->
    
</div>
<!-- /wp:group -->